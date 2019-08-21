<?php
namespace Skrz\Meta\XML;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Nette\Utils\Strings;
use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\AbstractModule;
use Skrz\Meta\MetaException;
use Skrz\Meta\MetaSpecMatcher;
use Skrz\Meta\PHP\StatementAndExpressionVO;
use Skrz\Meta\PropertySerializerInterface;
use Skrz\Meta\Reflection\ArrayType;
use Skrz\Meta\Reflection\BoolType;
use Skrz\Meta\Reflection\FloatType;
use Skrz\Meta\Reflection\IntType;
use Skrz\Meta\Reflection\MixedType;
use Skrz\Meta\Reflection\Property;
use Skrz\Meta\Reflection\ScalarType;
use Skrz\Meta\Reflection\Type;
use Skrz\Meta\Stack;
use Skrz\Meta\Transient;

class XmlModule extends AbstractModule
{

	/** @var PropertySerializerInterface[] */
	private $propertySerializers = array();

	public function addPropertySerializer(PropertySerializerInterface $propertySerializer)
	{
		$this->propertySerializers[] = $propertySerializer;
		return $this;
	}

	public function onAdd(AbstractMetaSpec $spec, MetaSpecMatcher $matcher)
	{
		// nothing
	}

	public function onBeforeGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type)
	{
		$hasDefaultElement = false;
		foreach ($type->getAnnotations(XmlElement::class) as $xmlElement) {
			/** @var XmlElement $xmlElement */

			if ($xmlElement->name === null) {
				$xmlElement->name = $type->getShortName();
			}

			if ($xmlElement->group === XmlElement::DEFAULT_GROUP) {
				$hasDefaultElement = true;
			}
		}

		if (!$hasDefaultElement) {
			$annotations = $type->getAnnotations();
			$annotations[] = $xmlElement = new XmlElement();
			$xmlElement->name = $type->getShortName();
			$type->setAnnotations($annotations);
		}

		foreach ($type->getProperties() as $property) {
			if ($property->hasAnnotation(Transient::class)) {
				continue;
			}

			if (get_class($property->getType()) === MixedType::class) {
				throw new MetaException(
					"Property {$type->getName()}::\${$property->getName()} of type mixed. " .
					"Either add @var annotation with non-mixed type, " .
					"or mark it using @Transient annotation."
				);
			}

			$hasDefaultGroup = false;
			foreach ($property->getAnnotations(XmlAnnotationInterface::class) as $annotation) {
				/** @var XmlAnnotationInterface $annotation */
				if ($annotation->getGroup() === XmlElement::DEFAULT_GROUP) {
					$hasDefaultGroup = true;
				}

				if ($annotation instanceof XmlElement) {
					/** @var XmlElement $annotation */
					if ($annotation->name === null) {
						$annotation->name = $property->getName();
					}

				} elseif ($annotation instanceof XmlAttribute) {
					/** @var XmlAttribute $annotation */
					if ($annotation->name === null) {
						$annotation->name = $property->getName();
					}
				}
			}

			if (!$hasDefaultGroup) {
				$annotations = $property->getAnnotations();

				$annotations[] = $arrayOffset = new XmlElement();
				$arrayOffset->name = $property->getName();

				$property->setAnnotations($annotations);
			}
		}
	}

	public function onGenerate(AbstractMetaSpec $spec, MetaSpecMatcher $matcher, Type $type, ClassType $class)
	{
		$ns = $class->getNamespace();

		$ns->addUse(XmlMetaInterface::class);
		$ns->addUse($type->getName(), null, $typeAlias);
		$class->addImplement(XmlMetaInterface::class);

		$groups = array();

		$i = 0;

		$valueGroupIdMask = 0;

		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlAnnotationInterface::class) as $xmlAnnotation) {
				/** @var XmlAnnotationInterface $xmlAnnotation */
				if (!isset($groups[$xmlAnnotation->getGroup()])) {
					$groups[$xmlAnnotation->getGroup()] = 1 << $i++;
				}

				if ($xmlAnnotation instanceof XmlValue) {
					$valueGroupIdMask |= $groups[$xmlAnnotation->getGroup()];
				}
			}
		}

		$class->addProperty("xmlGroups", $groups)->setStatic(true)
			->setVisibility("private")
			->addComment("@var array<string,int>");

		// fromXml()
		$fromXml = $class->addMethod("fromXml");
		$fromXml->setStatic(true);
		$fromXml->addParameter("xml");
		$fromXml->addParameter("group")->setOptional(true);
		$fromXml->addParameter("object")->setOptional(true);
		$fromXml
			->addComment("Creates \\{$type->getName()} from XML")
			->addComment("")
			->addComment("@param \\XMLReader|\\DOMElement \$xml")
			->addComment("@param string \$group")
			->addComment("@param {$typeAlias} \$object")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return {$typeAlias}");

		$fromXml
			->addBody("if (!isset(self::\$xmlGroups[\$group])) {")
			->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . Helpers::dump($type->getName()) . " . '.');")
			->addBody("} else {")
			->addBody("\t\$id = self::\$xmlGroups[\$group];")
			->addBody("}")
			->addBody("")
			->addBody("if (\$object === null) {")
			->addBody("\t\$object = new {$typeAlias}();")
			->addBody("} elseif (!(\$object instanceof {$typeAlias})) {")
			->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
			->addBody("}")
			->addBody("")
			->addBody("if (\$xml instanceof \\XMLReader) {")
			->addBody("\treturn self::fromXmlReader(\$xml, \$group, \$id, \$object);")
			->addBody("} elseif (\$xml instanceof \\DOMElement) {")
			->addBody("\treturn self::fromXmlElement(\$xml, \$group, \$id, \$object);")
			->addBody("} else {")
			->addBody("\tthrow new \\InvalidArgumentException('Expected XMLReader or DOMElement, got ' . gettype(\$xml) . (is_object(\$xml) ? ' of class ' . get_class(\$xml) : '') . '.');")
			->addBody("}");

		$fromXmlReader = $class->addMethod("fromXmlReader");
		$fromXmlReader->setStatic(true)->setVisibility("private");
		$fromXmlReader->addParameter("xml")->setTypeHint("\\XMLReader");
		$fromXmlReader->addParameter("group");
		$fromXmlReader->addParameter("id");
		$fromXmlReader->addParameter("object")->setTypeHint($type->getName());

		$fromXmlReaderProperty = $class->addProperty("fromXmlReader");
		$fromXmlReaderProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$class->getNamespace()->addUse(\Closure::class, null, $closureAlias);

		$fromXmlReader
			->addBody("if (self::\${$fromXmlReaderProperty->getName()} === null) {")
			->addBody("\tself::\${$fromXmlReaderProperty->getName()} = {$closureAlias}::bind(static function (\\XMLReader \$xml, \$group, \$id, \$object) {")
			->addBody("\t\tif (\$xml->nodeType !== \\XMLReader::ELEMENT) {")
			->addBody("\t\t\tthrow new \\InvalidArgumentException('Expects XMLReader to be positioned on ELEMENT node.');")
			->addBody("\t\t}")
			->addBody("");

		$attributesByName = array();

		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlAttribute::class) as $xmlAttribute) {
				/** @var XmlAttribute $xmlAttribute */

				$groupId = $groups[$xmlAttribute->group];
				$name = strtolower($xmlAttribute->name);

				if (!isset($attributesByName[$name])) {
					$attributesByName[$name] = "";
				}

				$attributesByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " . Helpers::dump($xmlAttribute->namespace) . ") {\n";
				$attributesByName[$name] .= Strings::indent($this->assignObjectProperty($xmlAttribute, $property, "\$xml->value"), 1, "\t") . "\n";
				$attributesByName[$name] .= "}\n";

			}
		}

		if (!empty($attributesByName)) {
			$fromXmlReader
				->addBody("\t\tif (\$xml->moveToFirstAttribute()) {")
				->addBody("\t\t\tdo {")
				->addBody("\t\t\t\tswitch (strtolower(\$xml->localName)) {");

			$i = 0;
			foreach ($attributesByName as $name => $code) {
				$fromXmlReader
					->addBody("\t\t\t\t\tcase " . Helpers::dump($name) . ":")
					->addBody(Strings::indent($code, 6, "\t"))
					->addBody("\t\t\t\t\t\tbreak;");

				if ($i < count($attributesByName) - 1) {
					$fromXmlReader->addBody("");
				}

				++$i;
			}

			$fromXmlReader
				->addBody("\t\t\t\t}")
				->addBody("\t\t\t} while (\$xml->moveToNextAttribute());")
				->addBody("")
				->addBody("\t\t\t\$xml->moveToElement();")
				->addBody("\t\t}")
				->addBody("");
		}

		$fromXmlReader->addBody("\t\tif ((\$id & {$valueGroupIdMask}) > 0) {");

		$valueCount = 0;
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlValue::class) as $xmlValue) {
				/** @var XmlValue $xmlValue */
				$groupId = $groups[$xmlValue->group];

				$fromXmlReader
					->addBody("\t\t\tif ((\$id & {$groupId}) > 0) {")
					->addBody("\t\t\t\t\$value = {$class->getName()}::xmlReadValue(\$xml);")
					->addBody(Strings::indent($this->assignObjectProperty($xmlValue, $property, "\$value"), 4, "\t"))
					->addBody("\t\t\t}")
					->addBody("");

				++$valueCount;
			}
		}

		if (!$valueCount) {
			$fromXmlReader->addBody("\t\t\t// @XmlValue not specified");
		}

		$fromXmlReader->addBody("\t\t} else {");

		$elementsByName = array();
		$endElementsByName = array();

		$wrappers = [];
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlElementWrapper::class) as $xmlElementWrapper) {
				/** @var XmlElementWrapper $xmlElementWrapper */

				$groupId = $groups[$xmlElementWrapper->group];
				$name = strtolower($xmlElementWrapper->name);
				$wrapperId = $xmlElementWrapper->group . ":" . $property->getName();

				if (!isset($wrappers[$wrapperId])) {
					$wrappers[$wrapperId] = 1 << count($wrappers);
				}

				if (!isset($elementsByName[$name])) {
					$elementsByName[$name] = "";
				}

				$elementsByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " . Helpers::dump($xmlElementWrapper->namespace) . " && \$depth === 2) {\n";
				$elementsByName[$name] .= "\t\$wrapped |= {$wrappers[$wrapperId]};\n";
				$elementsByName[$name] .= "}\n";

				if (!isset($endElementsByName[$name])) {
					$endElementsByName[$name] = "";
				}

				$endElementsByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " . Helpers::dump($xmlElementWrapper->namespace) . " && \$depth === 2) {\n";
				$endElementsByName[$name] .= "\t\$wrapped &= ~{$wrappers[$wrapperId]};\n";
				$endElementsByName[$name] .= "}\n";
			}

			foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
				/** @var XmlElement $xmlElement */

				$groupId = $groups[$xmlElement->group];
				$name = strtolower($xmlElement->name);
				$wrapperId = $xmlElement->group . ":" . $property->getName();

				if (!isset($elementsByName[$name])) {
					$elementsByName[$name] = "";
				}

				$isArray = false;
				$propertyType = $property->getType();

				if ($propertyType instanceof ArrayType) {
					$isArray = true;
					$propertyType = $propertyType->getBaseType();
				}

				if ($propertyType instanceof ArrayType) {
					throw new MetaException("fromXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
				}

				$elementsByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " . Helpers::dump($xmlElement->namespace) . " && ";
				if (isset($wrappers[$wrapperId])) {
					$elementsByName[$name] .= "(\$depth === 2 || (\$depth === 3 && (\$wrapped & {$wrappers[$wrapperId]}) > 0))";
				} else {
					$elementsByName[$name] .= "\$depth === 2";
				}
				$elementsByName[$name] .= ") {\n";

				if ($propertyType instanceof Type) {
					$propertyTypeMetaClassName = $spec->createMetaClassName($propertyType);
					$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
					$elementsByName[$name] .=
						"\t\$value = {$propertyTypeMetaClassNameAlias}::fromXml(\$xml, \$group" .
						($isArray ? "" : ", isset(\$object->{$property->getName()}) ? \$object->{$property->getName()} : null") .
						");\n";

				} else {
					$elementsByName[$name] .= "\t\$value = {$class->getName()}::xmlReadValue(\$xml);\n";
				}

				$elementsByName[$name] .= Strings::indent($this->assignObjectProperty($xmlElement, $property, "\$value", $isArray), 1, "\t") . "\n";
				$elementsByName[$name] .= "}\n";

			}
		}

		if (empty($elementsByName)) {
			$fromXmlReader->addBody("\t\t\t// @XmlElement not specified");

		} else {
			$fromXmlReader
				->addBody("\t\t\t\$depth = intval(!\$xml->isEmptyElement);")
				->addBody("\t\t\t\$wrapped = 0;")
				->addBody("\t\t\twhile (\$depth > 0 && \$xml->read()) {")
				->addBody("\t\t\t\tif (\$xml->nodeType === \\XMLReader::ELEMENT) {")
				->addBody("\t\t\t\t\t++\$depth;")
				->addBody("\t\t\t\t\tswitch (strtolower(\$xml->localName)) {");

			$i = 0;
			foreach ($elementsByName as $name => $code) {
				$fromXmlReader
					->addBody("\t\t\t\t\t\tcase " . Helpers::dump($name) . ":")
					->addBody(Strings::indent($code, 7, "\t"))
					->addBody("\t\t\t\t\t\t\tbreak;");

				if ($i < count($elementsByName) - 1) {
					$fromXmlReader->addBody("");
				}

				++$i;
			}

			$fromXmlReader
				->addBody("\t\t\t\t\t}")
				->addBody("\t\t\t\t}")
				->addBody("")
				->addBody("\t\t\t\tif (\$xml->nodeType === \\XMLReader::END_ELEMENT || (\$xml->nodeType === \\XMLReader::ELEMENT && \$xml->isEmptyElement)) {");

			if (!empty($endElementsByName)) {
				$fromXmlReader->addBody("\t\t\t\t\tswitch (strtolower(\$xml->localName)) {");

				$i = 0;
				foreach ($endElementsByName as $name => $code) {
					$fromXmlReader
						->addBody("\t\t\t\t\t\tcase " . Helpers::dump($name) . ":")
						->addBody(Strings::indent($code, 7, "\t"))
						->addBody("\t\t\t\t\t\t\tbreak;");

					if ($i < count($endElementsByName) - 1) {
						$fromXmlReader->addBody("");
					}

					++$i;
				}

				$fromXmlReader->addBody("\t\t\t\t\t}");
			}

			$fromXmlReader
				->addBody("\t\t\t\t\t--\$depth;")
				->addBody("\t\t\t\t}")
				->addBody("\t\t\t}");
		}

		$fromXmlReader
			->addBody("\t\t}")
			->addBody("")
			->addBody("\t\treturn \$object;")
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$fromXmlReaderProperty->getName()})(\$xml, \$group, \$id, \$object);");

		$xmlReadValue = $class->addMethod("xmlReadValue");
		$xmlReadValue->setStatic(true)->setVisibility("public")->addComment("@internal");
		$xmlReadValue->addParameter("xml")->setTypeHint("\\XMLReader");

		$xmlReadValue
			->addBody("\$value = null;")
			->addBody("\$valueDepth = intval(!\$xml->isEmptyElement);")
			->addBody("while (\$valueDepth > 0 && \$xml->read()) {")
			->addBody("\tif (\$xml->nodeType === \\XMLReader::ELEMENT && !\$xml->isEmptyElement) {")
			->addBody("\t\t++\$valueDepth;")
			->addBody("\t} elseif (\$xml->nodeType === \\XMLReader::END_ELEMENT) {")
			->addBody("\t\t--\$valueDepth;")
			->addBody("\t} elseif (\$xml->nodeType === \\XMLReader::TEXT || \$xml->nodeType === \\XMLReader::CDATA) {")
			->addBody("\t\t\$value .= \$xml->value;")
			->addBody("\t}")
			->addBody("}")
			->addBody("return \$value;");

		$fromXmlElement = $class->addMethod("fromXmlElement");
		$fromXmlElement->setStatic(true)->setVisibility("private");
		$fromXmlElement->addParameter("xml")->setTypeHint("\\DOMElement");
		$fromXmlElement->addParameter("group");
		$fromXmlElement->addParameter("id");
		$fromXmlElement->addParameter("object")->setTypeHint($type->getName());

		$fromXmlElementProperty = $class->addProperty("fromXmlElement");
		$fromXmlElementProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$fromXmlElement
			->addBody("if (self::\${$fromXmlElementProperty->getName()} === null) {")
			->addBody("\tself::\${$fromXmlElementProperty->getName()} = {$closureAlias}::bind(static function (\\DOMElement \$xml, \$group, \$id, \$object) {");

		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlAttribute::class) as $xmlAttribute) {
				/** @var XmlAttribute $xmlAttribute */

				$groupId = $groups[$xmlAttribute->group];

				if ($xmlAttribute->namespace) {
					$fromXmlElement->addBody(
						"\t\tif ((\$id & {$groupId}) > 0 && " .
						"\$xml->hasAttributeNS(" . Helpers::dump($xmlAttribute->namespace) . ", " .
						Helpers::dump($xmlAttribute->name) . ")) {"
					);
					$expr = "\$xml->getAttributeNS(" . Helpers::dump($xmlAttribute->namespace) . ", " . Helpers::dump($xmlAttribute->name) . ")";
				} else {
					$fromXmlElement->addBody(
						"\t\tif ((\$id & {$groupId}) > 0 && " .
						"\$xml->hasAttribute(" . Helpers::dump($xmlAttribute->name) . ")) {"
					);
					$expr = "\$xml->getAttribute(" . Helpers::dump($xmlAttribute->name) . ")";
				}

				$fromXmlElement
					->addBody(Strings::indent($this->assignObjectProperty($xmlAttribute, $property, $expr), 3, "\t"))
					->addBody("\t\t}")
					->addBody("");
			}
		}

		$fromXmlElement->addBody("\t\tif ((\$id & {$valueGroupIdMask}) > 0) {");

		$valueCount = 0;
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlValue::class) as $xmlValue) {
				/** @var XmlValue $xmlValue */
				$groupId = $groups[$xmlValue->group];

				$fromXmlElement
					->addBody("\t\t\tif ((\$id & {$groupId}) > 0) {")
					->addBody(Strings::indent($this->assignObjectProperty($xmlValue, $property, "\$xml->textContent"), 4, "\t"))
					->addBody("\t\t\t}")
					->addBody("");

				++$valueCount;
			}
		}

		if (!$valueCount) {
			$fromXmlElement->addBody("\t\t\t// @XmlValue not specified");
		}

		$fromXmlElement->addBody("\t\t} elseif (\$xml->childNodes->length > 0) {");

		$elementsByName = array();
		$wrappers = array();
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlElementWrapper::class) as $xmlElementWrapper) {
				/** @var XmlElementWrapper $xmlElementWrapper */

				$groupId = $groups[$xmlElementWrapper->group];
				$name = strtolower($xmlElementWrapper->name);
				$wrapperId = $xmlElementWrapper->group . ":" . $property->getName();

				if (!isset($wrappers[$wrapperId])) {
					$wrappers[$wrapperId] = 1 << count($wrappers);
				}

				if (!isset($elementsByName[$name])) {
					$elementsByName[$name] = "";
				}

				$elementsByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " .
					Helpers::dump(empty($xmlElementWrapper->namespace) ? null : $xmlElementWrapper->namespace) .
					" && \$sp === 0 && \$node->childNodes->length > 0) {\n";
				$elementsByName[$name] .= "\t\$wrapped |= {$wrappers[$wrapperId]};\n";
				$elementsByName[$name] .= "\t\$push = true;\n";
				$elementsByName[$name] .= "}\n";
			}

			foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
				/** @var XmlElement $xmlElement */

				$groupId = $groups[$xmlElement->group];
				$name = strtolower($xmlElement->name);
				$wrapperId = $xmlElement->group . ":" . $property->getName();

				if (!isset($elementsByName[$name])) {
					$elementsByName[$name] = "";
				}

				$isArray = false;
				$propertyType = $property->getType();

				if ($propertyType instanceof ArrayType) {
					$isArray = true;
					$propertyType = $propertyType->getBaseType();
				}

				if ($propertyType instanceof ArrayType) {
					throw new MetaException("fromXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
				}

				$elementsByName[$name] .= "if ((\$id & {$groupId}) > 0 && \$xml->namespaceURI === " . Helpers::dump(empty($xmlElement->namespace) ? null : $xmlElement->namespace) . " && ";
				if (isset($wrappers[$wrapperId])) {
					$elementsByName[$name] .= "(\$sp === 0 || (\$sp === 1 && (\$wrapped & {$wrappers[$wrapperId]}) > 0))";
				} else {
					$elementsByName[$name] .= "\$sp === 0";
				}
				$elementsByName[$name] .= ") {\n";

				if ($propertyType instanceof Type) {
					$propertyTypeMetaClassName = $spec->createMetaClassName($propertyType);
					$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
					$elementsByName[$name] .=
						"\t\$value = {$propertyTypeMetaClassNameAlias}::fromXml(\$node, \$group" .
						($isArray ? "" : ", isset(\$object->{$property->getName()}) ? \$object->{$property->getName()} : null") .
						");\n";

				} else {
					$elementsByName[$name] .= "\t\$value = \$node->textContent;\n";
				}

				$elementsByName[$name] .= Strings::indent($this->assignObjectProperty($xmlElement, $property, "\$value", $isArray), 1, "\t") . "\n";
				$elementsByName[$name] .= "}\n";
			}
		}

		if (empty($elementsByName)) {
			$fromXmlElement->addBody("\t\t\t// @XmlElement not specified");

		} else {
			$fromXmlElement
				->addBody("\t\t\t\$stack = [[\$xml->childNodes, 0]];")
				->addBody("\t\t\t\$sp = 0;")
				->addBody("\t\t\t\$wrapped = 0;")
				->addBody("\t\t\t\$push = false;")
				->addBody("\t\t\twhile (!empty(\$stack)) {")
				->addBody("\t\t\t\t\$node = \$stack[\$sp][0]->item(\$stack[\$sp][1]);")
				->addBody("\t\t\t\tif (\$node->nodeType !== XML_ELEMENT_NODE) {")
				->addBody("\t\t\t\t\tcontinue;")
				->addBody("\t\t\t\t}")
				->addBody("")
				->addBody("\t\t\t\tswitch (strtolower(\$node->localName)) {");

			$i = 0;
			foreach ($elementsByName as $name => $code) {
				$fromXmlElement
					->addBody("\t\t\t\t\tcase " . Helpers::dump($name) . ":")
					->addBody(Strings::indent($code, 6, "\t"))
					->addBody("\t\t\t\t\t\tbreak;");

				if ($i < count($elementsByName) - 1) {
					$fromXmlElement->addBody("");
				}

				++$i;
			}

			$fromXmlElement
				->addBody("\t\t\t\t}")
				->addBody("\t\t\t\t++\$stack[\$sp][1];")
				->addBody("\t\t\t\tif (\$stack[\$sp][1] >= \$stack[\$sp][0]->length) {")
				->addBody("\t\t\t\t\tunset(\$stack[\$sp]);")
				->addBody("\t\t\t\t\t--\$sp;")
				->addBody("\t\t\t\t\t\$wrapped = 0;")
				->addBody("\t\t\t\t}")
				->addBody("\t\t\t\tif (\$push) {")
				->addBody("\t\t\t\t\t\$push = false;")
				->addBody("\t\t\t\t\t\$stack[++\$sp] = [\$node->childNodes, 0];")
				->addBody("\t\t\t\t}")
				->addBody("\t\t\t}");
		}

		$fromXmlElement
			->addBody("\t\t}")
			->addBody("");

		$fromXmlElement
			->addBody("\t\treturn \$object;")
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$fromXmlElementProperty->getName()})(\$xml, \$group, \$id, \$object);");


		// toXml()
		$toXml = $class->addMethod("toXml");
		$toXml->setStatic(true);
		$toXml->addParameter("object");
		$toXml->addParameter("group")->setOptional(true);
		$toXml->addParameter("filterOrXml");
		$toXml->addParameter("xml")->setOptional(true);
		$toXml->addParameter("el")->setOptional(true);
		$toXml
			->addComment("Serializes \\{$type->getName()} to XML")
			->addComment("")
			->addComment("@param {$typeAlias} \$object")
			->addComment("@param string \$group")
			->addComment("@param array|\\XMLWriter|\\DOMDocument \$filterOrXml")
			->addComment("@param \\XMLWriter|\\DOMDocument|\\DOMElement \$xml")
			->addComment("@param \\DOMElement \$el")
			->addComment("")
			->addComment("@throws \\InvalidArgumentException")
			->addComment("")
			->addComment("@return \\DOMElement|void");

		$ns->addUse(Stack::class, null, $stackAlias);

		$toXml
			->addBody("if (\$object === null) {")
			->addBody("\treturn null;")
			->addBody("}")
			->addBody("")
			->addBody("if (!isset(self::\$xmlGroups[\$group])) {")
			->addBody("\tthrow new \\InvalidArgumentException('Group \\'' . \$group . '\\' not supported for ' . " . Helpers::dump($type->getName()) . " . '.');")
			->addBody("} else {")
			->addBody("\t\$id = self::\$xmlGroups[\$group];")
			->addBody("}")
			->addBody("")
			->addBody("if (!(\$object instanceof {$typeAlias})) {")
			->addBody("\tthrow new \\InvalidArgumentException('You have to pass object of class {$type->getName()}.');")
			->addBody("}")
			->addBody("")
			->addBody("if ({$stackAlias}::\$objects === null) {")
			->addBody("\t{$stackAlias}::\$objects = new \\SplObjectStorage();")
			->addBody("}")
			->addBody("")
			->addBody("if ({$stackAlias}::\$objects->contains(\$object)) {")
			->addBody("\treturn null;")
			->addBody("}")
			->addBody("")
			->addBody("{$stackAlias}::\$objects->attach(\$object);")
			->addBody("")
			->addBody("if (\$filterOrXml instanceof \\XMLWriter || \$filterOrXml instanceof \\DOMDocument) {")
			->addBody("\t\$filter = null;")
			->addBody("\t\$el = \$xml;")
			->addBody("\t\$xml = \$filterOrXml;")
			->addBody("} else {")
			->addBody("\t\$filter = \$filterOrXml;")
			->addBody("}")
			->addBody("")
			->addBody("try {")
			->addBody("\tif (\$xml instanceof \\XMLWriter) {")
			->addBody("\t\tself::toXmlWriter(\$object, \$group, \$id, \$filter, \$xml);")
			->addBody("\t\t\$ret = null;")
			->addBody("\t} elseif (\$xml instanceof \\DOMDocument) {")
			->addBody("\t\t\$ret = self::toXmlElement(\$object, \$group, \$id, \$filter, \$xml, \$el);")
			->addBody("\t} else {")
			->addBody("\t\tthrow new \\InvalidArgumentException('You have to supply either XMLWriter, or DOMDocument.');")
			->addBody("\t}")
			->addBody("} finally {")
			->addBody("\t{$stackAlias}::\$objects->detach(\$object);")
			->addBody("}")
			->addBody("")
			->addBody("return \$ret;");

		$toXmlWriter = $class->addMethod("toXmlWriter");
		$toXmlWriter->setStatic(true)->setVisibility("private");
		$toXmlWriter->addParameter("object")->setTypeHint($type->getName());
		$toXmlWriter->addParameter("group");
		$toXmlWriter->addParameter("id");
		$toXmlWriter->addParameter("filter");
		$toXmlWriter->addParameter("xml")->setTypeHint("\\XMLWriter");

		$toXmlWriterProperty = $class->addProperty($toXmlWriter->getName());
		$toXmlWriterProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$toXmlWriter
			->addBody("if (self::\${$toXmlWriterProperty->getName()} === null) {")
			->addBody("\tself::\${$toXmlWriterProperty->getName()} = {$closureAlias}::bind(static function ({$typeAlias} \$object, \$group, \$id, \$filter, \\XMLWriter \$xml) {")
			->addBody("\t\tif (count({$stackAlias}::\$objects) < 2) {");

		foreach ($type->getAnnotations(XmlElement::class) as $xmlElement) {
			/** @var XmlElement $xmlElement */

			$groupId = $groups[$xmlElement->group];

			$toXmlWriter->addBody("\t\t\tif ((\$id & {$groupId}) > 0) {");

			if ($xmlElement->namespace) {
				$toXmlWriter->addBody(
					"\t\t\t\t\$xml->startElementNS(null, " . Helpers::dump($xmlElement->name) . ", " .
					Helpers::dump($xmlElement->namespace) . ");"
				);
			} else {
				$toXmlWriter->addBody("\t\t\t\t\$xml->startElement(" . Helpers::dump($xmlElement->name) . ");");
			}

			$toXmlWriter->addBody("\t\t\t}");
		}

		$toXmlWriter->addBody("\t\t}")->addBody("");

		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlAttribute::class) as $xmlAttribute) {
				/** @var XmlAttribute $xmlAttribute */

				$groupId = $groups[$xmlAttribute->group];

				$toXmlWriter->addBody("\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[" . Helpers::dump("@" . $xmlAttribute->name) . "]))) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlAttribute->getGroup())) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlAttribute->group, "\$object->{$property->getName()}");

				} elseif ($property->getType() instanceof ScalarType) {
					$sevo = StatementAndExpressionVO::withExpression("(string)\$object->{$property->getName()}");

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				if ($sevo->getStatement()) {
					$toXmlWriter->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
				}

				if ($xmlAttribute->namespace) {
					$toXmlWriter->addBody("\t\t\t\$xml->writeAttributeNS(null, " . Helpers::dump($xmlAttribute->name) . ", " . Helpers::dump($xmlAttribute->namespace) . ", {$sevo->getExpression()});");

				} else {
					$toXmlWriter->addBody("\t\t\t\$xml->writeAttribute(" . Helpers::dump($xmlAttribute->name) . ", {$sevo->getExpression()});");
				}

				$toXmlWriter
					->addBody("\t\t}")
					->addBody("");
			}
		}

		$toXmlWriter->addBody("\t\tif ((\$id & {$valueGroupIdMask}) > 0) {");

		$valueCount = 0;
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlValue::class) as $xmlValue) {
				/** @var XmlValue $xmlValue */
				$groupId = $groups[$xmlValue->group];

				$toXmlWriter->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()})) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlValue->getGroup())) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlValue->group, "\$object->{$property->getName()}");
					if ($sevo->getStatement()) {
						$toXmlWriter->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
					}
					$toXmlWriter->addBody("\t\t\t\t\$xml->text({$sevo->getExpression()});");

				} elseif ($property->getType() instanceof ScalarType) {
					$toXmlWriter->addBody("\t\t\t\t\$xml->text((string)\$object->{$property->getName()});");

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				$toXmlWriter->addBody("\t\t\t}")->addBody("");

				++$valueCount;
			}
		}

		if (!$valueCount) {
			$toXmlWriter->addBody("\t\t\t// @XmlValue not specified");
		}

		$toXmlWriter->addBody("\t\t} else {");

		$elementCount = 0;

		$wrappers = array();

		foreach ($type->getProperties() as $property) {
			$wrappedGroups = array();
			foreach ($property->getAnnotations(XmlElementWrapper::class) as $xmlElementWrapper) {
				$nameKey = $xmlElementWrapper->namespace . ":" . $xmlElementWrapper->name;

				$wrappedGroups[$xmlElementWrapper->group] = true;

				if (!isset($wrappers[$nameKey])) {
					$wrappers[$nameKey] = [];
				}

				if (!isset($wrappers[$nameKey][$xmlElementWrapper->group])) {
					$wrappers[$nameKey][$xmlElementWrapper->group] = [$xmlElementWrapper, []];
				}

				// select first XmlElementWrapper per group
				if (!isset($wrappers[$nameKey][1][$property->getName()])) {
					$wrappers[$nameKey][$xmlElementWrapper->group][1][$property->getName()] = $property;
					++$elementCount;
				}
			}

			foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
				if (isset($wrappedGroups[$xmlElement->group])) {
					continue;
				}

				++$elementCount;

				$groupId = $groups[$xmlElement->group];
				$elementNamespaceStr = Helpers::dump($xmlElement->namespace);
				$elementNameStr = Helpers::dump($xmlElement->name);

				$toXmlWriter->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[{$elementNameStr}]))) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlElement->group)) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				$baseType = $property->getType();
				$isArray = false;

				if ($baseType instanceof ArrayType) {
					$isArray = true;
					$baseType = $baseType->getBaseType();
				}

				if ($baseType instanceof ArrayType) {
					throw new MetaException("toXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
				}

				$indent = "\t\t\t\t";
				$value = "\$object->{$property->getName()}";
				if ($isArray) {
					$toXmlWriter->addBody("\t\t\t\tforeach ({$value} instanceof \\Traversable ? {$value} : (array){$value} as \$item) {");
					$indent = "\t\t\t\t\t";
					$value = "\$item";
				}

				if ($xmlElement->namespace) {
					$toXmlWriter->addBody("{$indent}\$xml->startElementNS(null, {$elementNameStr}, {$elementNamespaceStr});");
				} else {
					$toXmlWriter->addBody("{$indent}\$xml->startElement({$elementNameStr});");
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlElement->group, $value);
					if ($sevo->getStatement()) {
						$toXmlWriter->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
					}
					$toXmlWriter->addBody("{$indent}\$xml->text({$sevo->getExpression()});");

				} elseif ($baseType instanceof ScalarType) {
					$toXmlWriter->addBody("{$indent}\$xml->text((string){$value});");

				} elseif ($baseType instanceof Type) {
					$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
					$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
					$toXmlWriter->addBody(
						"{$indent}{$propertyTypeMetaClassNameAlias}::toXml({$value}, " .
						"\$group, " .
						"\$filter === null ? null : \$filter[{$elementNameStr}], " .
						"\$xml" .
						");"
					);

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				$toXmlWriter->addBody("{$indent}\$xml->endElement();");

				if ($isArray) {
					$toXmlWriter->addBody("\t\t\t\t}");
				}

				$toXmlWriter->addBody("\t\t\t}");
			}
		}

		foreach ($wrappers as $wrapper) {
			foreach ($wrapper as $groupWrapper) {
				list($xmlElementWrapper, $properties) = $groupWrapper;
				/** @var Property[] $properties */

				$groupId = $groups[$xmlElementWrapper->group];
				$namespaceStr = Helpers::dump($xmlElementWrapper->namespace);
				$nameStr = Helpers::dump($xmlElementWrapper->name);

				$propertiesIssets = [];

				foreach ($properties as $property) {
					$propertiesIssets[] = "isset(\$object->{$property->getName()})";
				}

				$toXmlWriter->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && (" . implode(" || ", $propertiesIssets) . ") && (\$filter === null || isset(\$filter[{$nameStr}]))) {");

				if ($xmlElementWrapper->namespace) {
					$toXmlWriter->addBody("\t\t\t\t\$xml->startElementNS(null, {$nameStr}, {$namespaceStr});");
				} else {
					$toXmlWriter->addBody("\t\t\t\t\$xml->startElement({$nameStr});");
				}

				foreach ($properties as $property) {
					foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
						if ($xmlElement->group !== $xmlElementWrapper->group) { // important!
							continue;
						}

						$elementNamespaceStr = Helpers::dump($xmlElement->namespace);
						$elementNameStr = Helpers::dump($xmlElement->name);

						// no need to check group ID, already checked by wrapper
						$toXmlWriter->addBody("\t\t\t\tif (isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[{$nameStr}][{$elementNameStr}]))) {");

						$matchingPropertySerializer = null;
						foreach ($this->propertySerializers as $propertySerializer) {
							if ($propertySerializer->matchesSerialize($property, $xmlElement->group)) {
								$matchingPropertySerializer = $propertySerializer;
								break;
							}
						}

						$baseType = $property->getType();
						$isArray = false;

						if ($baseType instanceof ArrayType) {
							$isArray = true;
							$baseType = $baseType->getBaseType();
						}

						if ($baseType instanceof ArrayType) {
							throw new MetaException("toXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
						}

						$indent = "\t\t\t\t\t";
						$value = "\$object->{$property->getName()}";
						if ($isArray) {
							$toXmlWriter->addBody("\t\t\t\t\tforeach ({$value} instanceof \\Traversable ? {$value} : (array){$value} as \$item) {");
							$indent = "\t\t\t\t\t\t";
							$value = "\$item";
						}

						if ($xmlElement->namespace) {
							$toXmlWriter->addBody("{$indent}\$xml->startElementNS(null, {$elementNameStr}, {$elementNamespaceStr});");
						} else {
							$toXmlWriter->addBody("{$indent}\$xml->startElement({$elementNameStr});");
						}

						if ($matchingPropertySerializer) {
							$sevo = $matchingPropertySerializer->serialize($property, $xmlElement->group, $value);
							if ($sevo->getStatement()) {
								$toXmlWriter->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
							}
							$toXmlWriter->addBody("{$indent}\$xml->text({$sevo->getExpression()});");

						} elseif ($baseType instanceof ScalarType) {
							$toXmlWriter->addBody("{$indent}\$xml->text((string)\$object->{$property->getName()});");

						} elseif ($baseType instanceof Type) {
							$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
							$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
							$toXmlWriter->addBody(
								"{$indent}{$propertyTypeMetaClassNameAlias}::toXml({$value}, " .
								"\$group, " .
								"\$filter === null ? null : \$filter[{$nameStr}][{$elementNameStr}], " .
								"\$xml" .
								");"
							);

						} else {
							throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
						}

						$toXmlWriter->addBody("{$indent}\$xml->endElement();");

						if ($isArray) {
							$toXmlWriter->addBody("\t\t\t\t\t}");
						}

						$toXmlWriter->addBody("\t\t\t\t}");
					}
				}

				$toXmlWriter->addBody("\t\t\t\t\$xml->endElement();");

				$toXmlWriter->addBody("\t\t\t}");
			}
		}

		if (!$elementCount) {
			$toXmlWriter->addBody("\t\t\t// @XmlElement not specified");
		}

		$toXmlWriter
			->addBody("\t\t}")->addBody("");

		$toXmlWriter
			->addBody("\t\tif (count({$stackAlias}::\$objects) < 2) {")
			->addBody("\t\t\t\$xml->endElement();")
			->addBody("\t\t}")
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$toXmlWriterProperty->getName()})(\$object, \$group, \$id, \$filter, \$xml);");

		$toXmlElement = $class->addMethod("toXmlElement");
		$toXmlElement->setStatic(true)->setVisibility("private");
		$toXmlElement->addParameter("object")->setTypeHint($type->getName());
		$toXmlElement->addParameter("group");
		$toXmlElement->addParameter("id");
		$toXmlElement->addParameter("filter");
		$toXmlElement->addParameter("xml")->setTypeHint("\\DOMDocument");
		$toXmlElement->addParameter("el")->setTypeHint("\\DOMElement")->setNullable(true);

		$toXmlElementProperty = $class->addProperty($toXmlElement->getName());
		$toXmlElementProperty->setStatic(true)
			->setVisibility("private")
			->addComment("@var callable");

		$toXmlElement
			->addBody("if (self::\${$toXmlElementProperty->getName()} === null) {")
			->addBody("\tself::\${$toXmlElementProperty->getName()} = {$closureAlias}::bind(static function ({$typeAlias} \$object, \$group, \$id, \$filter, \\DOMDocument \$xml, ?\\DOMElement \$el) {");

		foreach ($type->getAnnotations(XmlElement::class) as $xmlElement) {
			/** @var XmlElement $xmlElement */

			$groupId = $groups[$xmlElement->group];

			$toXmlElement->addBody("\t\tif (!isset(\$el) && (\$id & {$groupId}) > 0) {");

			if ($xmlElement->namespace) {
				$toXmlElement->addBody("\t\t\t\$el = \$xml->createElementNS(" . Helpers::dump($xmlElement->namespace) . ", " . Helpers::dump($xmlElement->name) . ");");
			} else {
				$toXmlElement->addBody("\t\t\t\$el = \$xml->createElement(" . Helpers::dump($xmlElement->name) . ");");
			}

			$toXmlElement->addBody("\t\t}")->addBody("");
		}

		$toXmlElement
			->addBody("\t\tif (!isset(\$el)) {")
			->addBody("\t\t\tthrow new \\LogicException('Element has to exist by now.');")
			->addBody("\t\t}")
			->addBody("");

		$attributeCount = 0;
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlAttribute::class) as $xmlAttribute) {
				++$attributeCount;
				$groupId = $groups[$xmlAttribute->group];
				$attributeNamespaceStr = Helpers::dump($xmlAttribute->namespace);
				$attributeNameStr = Helpers::dump($xmlAttribute->name);
				$attributeFilterStr = Helpers::dump("@" . $xmlAttribute->name);

				$toXmlElement->addBody("\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[{$attributeFilterStr}]))) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlAttribute->getGroup())) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlAttribute->group, "\$object->{$property->getName()}");

				} elseif ($property->getType() instanceof ScalarType) {
					$sevo = StatementAndExpressionVO::withExpression("(string)\$object->{$property->getName()}");

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				if ($sevo->getStatement()) {
					$toXmlElement->addBody(Strings::indent($sevo->getStatement(), 1, "\t"));
				}

				if ($xmlAttribute->namespace) {
					$toXmlElement->addBody("\t\t\t\$el->setAttributeNS({$attributeNamespaceStr}, {$attributeNameStr}, {$sevo->getExpression()});");
				} else {
					$toXmlElement->addBody("\t\t\t\$el->setAttribute({$attributeNameStr}, {$sevo->getExpression()});");
				}

				$toXmlElement->addBody("\t\t}");
			}
		}

		if ($attributeCount) {
			$toXmlElement->addBody("");
		}

		$toXmlElement->addBody("\t\tif ((\$id & {$valueGroupIdMask}) > 0) {");

		$valueCount = 0;
		foreach ($type->getProperties() as $property) {
			foreach ($property->getAnnotations(XmlValue::class) as $xmlValue) {
				/** @var XmlValue $xmlValue */
				$groupId = $groups[$xmlValue->group];

				$toXmlElement->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()})) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlValue->getGroup())) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlValue->group, "\$object->{$property->getName()}");
					if ($sevo->getStatement()) {
						$toXmlElement->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
					}
					$toXmlElement->addBody("\t\t\t\t\$el->appendChild(new \\DOMText({$sevo->getExpression()}));");

				} elseif ($property->getType() instanceof ScalarType) {
					$toXmlElement->addBody("\t\t\t\t\$el->appendChild(new \\DOMText((string)\$object->{$property->getName()}));");

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				$toXmlElement->addBody("\t\t\t}");

				++$valueCount;
			}
		}

		if (!$valueCount) {
			$toXmlElement->addBody("\t\t\t// @XmlValue not specified");
		}

		$toXmlElement->addBody("\t\t} else {");

		$wrappers = array();

		foreach ($type->getProperties() as $property) {
			$wrappedGroups = array();
			foreach ($property->getAnnotations(XmlElementWrapper::class) as $xmlElementWrapper) {
				$nameKey = $xmlElementWrapper->namespace . ":" . $xmlElementWrapper->name;

				$wrappedGroups[$xmlElementWrapper->group] = true;

				if (!isset($wrappers[$nameKey])) {
					$wrappers[$nameKey] = [];
				}

				if (!isset($wrappers[$nameKey][$xmlElementWrapper->group])) {
					$wrappers[$nameKey][$xmlElementWrapper->group] = [$xmlElementWrapper, []];
				}

				// select first XmlElementWrapper per group
				if (!isset($wrappers[$nameKey][1][$property->getName()])) {
					$wrappers[$nameKey][$xmlElementWrapper->group][1][$property->getName()] = $property;
					++$elementCount;
				}
			}

			foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
				if (isset($wrappedGroups[$xmlElement->group])) {
					continue;
				}

				++$elementCount;

				$groupId = $groups[$xmlElement->group];
				$elementNamespaceStr = Helpers::dump($xmlElement->namespace);
				$elementNameStr = Helpers::dump($xmlElement->name);

				$toXmlElement->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[{$elementNameStr}]))) {");

				$matchingPropertySerializer = null;
				foreach ($this->propertySerializers as $propertySerializer) {
					if ($propertySerializer->matchesSerialize($property, $xmlElement->group)) {
						$matchingPropertySerializer = $propertySerializer;
						break;
					}
				}

				$baseType = $property->getType();
				$isArray = false;

				if ($baseType instanceof ArrayType) {
					$isArray = true;
					$baseType = $baseType->getBaseType();
				}

				if ($baseType instanceof ArrayType) {
					throw new MetaException("toXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
				}

				$indent = "\t\t\t\t";
				$value = "\$object->{$property->getName()}";
				if ($isArray) {
					$toXmlElement->addBody("\t\t\t\tforeach ({$value} instanceof \\Traversable ? {$value} : (array){$value} as \$item) {");
					$indent = "\t\t\t\t\t";
					$value = "\$item";
				}

				if ($xmlElement->namespace) {
					$toXmlElement->addBody("{$indent}\$subEl = \$xml->createElementNS({$elementNamespaceStr}, {$elementNameStr});");
				} else {
					$toXmlElement->addBody("{$indent}\$subEl = \$xml->createElement({$elementNameStr});");
				}

				if ($matchingPropertySerializer) {
					$sevo = $matchingPropertySerializer->serialize($property, $xmlElement->group, $value);
					if ($sevo->getStatement()) {
						$toXmlElement->addBody(Strings::indent($sevo->getStatement(), 2, "\t"));
					}
					$toXmlElement->addBody("{$indent}\$subEl->appendChild(new \\DOMText({$sevo->getExpression()}));");

				} elseif ($baseType instanceof ScalarType) {
					$toXmlElement->addBody("{$indent}\$subEl->appendChild(new \\DOMText((string){$value}));");

				} elseif ($baseType instanceof Type) {
					$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
					$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
					$toXmlElement->addBody(
						"{$indent}{$propertyTypeMetaClassNameAlias}::toXml({$value}, " .
						"\$group, " .
						"\$filter === null ? null : \$filter[{$elementNameStr}], " .
						"\$xml, " .
						"\$subEl" .
						");"
					);

				} else {
					throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
				}

				$toXmlElement->addBody("{$indent}\$el->appendChild(\$subEl);");

				if ($isArray) {
					$toXmlElement->addBody("\t\t\t\t}");
				}

				$toXmlElement->addBody("\t\t\t}");
			}
		}

		foreach ($wrappers as $wrapper) {
			foreach ($wrapper as $groupWrapper) {
				list($xmlElementWrapper, $properties) = $groupWrapper;
				/** @var Property[] $properties */

				$groupId = $groups[$xmlElementWrapper->group];
				$namespaceStr = Helpers::dump($xmlElementWrapper->namespace);
				$nameStr = Helpers::dump($xmlElementWrapper->name);

				$propertiesIssets = [];

				foreach ($properties as $property) {
					$propertiesIssets[] = "isset(\$object->{$property->getName()})";
				}

				$toXmlElement->addBody("\t\t\tif ((\$id & {$groupId}) > 0 && (" . implode(" || ", $propertiesIssets) . ") && (\$filter === null || isset(\$filter[{$nameStr}]))) {");

				if ($xmlElementWrapper->namespace) {
					$toXmlElement->addBody("\t\t\t\t\$wrapperEl = \$xml->createElementNS({$namespaceStr}, {$nameStr});");
				} else {
					$toXmlElement->addBody("\t\t\t\t\$wrapperEl = \$xml->createElement({$nameStr});");
				}

				foreach ($properties as $property) {
					foreach ($property->getAnnotations(XmlElement::class) as $xmlElement) {
						if ($xmlElement->group !== $xmlElementWrapper->group) { // important!
							continue;
						}

						$elementNamespaceStr = Helpers::dump($xmlElement->namespace);
						$elementNameStr = Helpers::dump($xmlElement->name);

						// no need to check group ID, already checked by wrapper
						$toXmlElement->addBody("\t\t\t\tif (isset(\$object->{$property->getName()}) && (\$filter === null || isset(\$filter[{$nameStr}][{$elementNameStr}]))) {");

						$matchingPropertySerializer = null;
						foreach ($this->propertySerializers as $propertySerializer) {
							if ($propertySerializer->matchesSerialize($property, $xmlElement->group)) {
								$matchingPropertySerializer = $propertySerializer;
								break;
							}
						}

						$baseType = $property->getType();
						$isArray = false;

						if ($baseType instanceof ArrayType) {
							$isArray = true;
							$baseType = $baseType->getBaseType();
						}

						if ($baseType instanceof ArrayType) {
							throw new MetaException("toXml() cannot process multi-dimensional arrays ({$type->getName()}::\${$property->getName()}).");
						}

						$indent = "\t\t\t\t\t";
						$value = "\$object->{$property->getName()}";
						if ($isArray) {
							$toXmlElement->addBody("\t\t\t\t\tforeach ({$value} instanceof \\Traversable ? {$value} : (array){$value} as \$item) {");
							$indent = "\t\t\t\t\t\t";
							$value = "\$item";
						}

						if ($xmlElement->namespace) {
							$toXmlElement->addBody("{$indent}\$subEl = \$xml->createElementNS({$elementNamespaceStr}, {$elementNameStr});");
						} else {
							$toXmlElement->addBody("{$indent}\$subEl = \$xml->createElement({$elementNameStr});");
						}

						if ($matchingPropertySerializer) {
							$sevo = $matchingPropertySerializer->serialize($property, $xmlElement->group, $value);
							if ($sevo->getStatement()) {
								$toXmlElement->addBody(Strings::indent($sevo->getStatement(), 4, "\t"));
							}
							$toXmlElement->addBody("{$indent}\$subEl->appendChild(new \\DOMText({$sevo->getExpression()}));");

						} elseif ($baseType instanceof ScalarType) {
							$toXmlElement->addBody("{$indent}\$subEl->appendChild(new \\DOMText((string)\$object->{$property->getName()}));");

						} elseif ($baseType instanceof Type) {
							$propertyTypeMetaClassName = $spec->createMetaClassName($baseType);
							$ns->addUse($propertyTypeMetaClassName, null, $propertyTypeMetaClassNameAlias);
							$toXmlElement->addBody(
								"{$indent}{$propertyTypeMetaClassNameAlias}::toXml({$value}, " .
								"\$group, " .
								"\$filter === null ? null : \$filter[{$nameStr}][{$elementNameStr}], " .
								"\$xml, " .
								"\$subEl" .
								");"
							);

						} else {
							throw new MetaException("Unsupported property type " . get_class($property->getType()) . ".");
						}

						$toXmlElement->addBody("{$indent}\$wrapperEl->appendChild(\$subEl);");

						if ($isArray) {
							$toXmlElement->addBody("\t\t\t\t\t}");
						}

						$toXmlElement->addBody("\t\t\t\t}");
					}
				}

				$toXmlElement->addBody("\t\t\t\t\$el->appendChild(\$wrapperEl);");

				$toXmlElement->addBody("\t\t\t}");
			}
		}

		if (!$elementCount) {
			$toXmlElement->addBody("\t\t\t// @XmlElement not specified");
		}

		$toXmlElement->addBody("\t\t}")->addBody("");

		$toXmlElement
			->addBody("\t\treturn \$el;")
			->addBody("\t}, null, {$typeAlias}::class);")
			->addBody("}")
			->addBody("")
			->addBody("return (self::\${$toXmlElementProperty->getName()})(\$object, \$group, \$id, \$filter, \$xml, \$el);");
	}

	private function assignObjectProperty(XmlAnnotationInterface $xmlAnnotation, Property $property, $expr, $isArray = false)
	{
		$matchingPropertySerializer = null;
		foreach ($this->propertySerializers as $propertySerializer) {
			if ($propertySerializer->matchesSerialize($property, $xmlAnnotation->getGroup())) {
				$matchingPropertySerializer = $propertySerializer;
				break;
			}
		}

		$ret = "";

		if ($isArray) {
			$ret .= "if (!is_array(\$object->{$property->getName()})) {\n";
			$ret .= "\t\$object->{$property->getName()} = array();\n";
			$ret .= "}\n";
		}

		if ($matchingPropertySerializer !== null) {
			$sevo = $matchingPropertySerializer->deserialize($property, $xmlAnnotation->getGroup(), $expr);
			if ($sevo->getStatement()) {
				$ret .= $sevo->getStatement() . "\n";
			}
			$ret .= "\$object->{$property->getName()}" . ($isArray ? "[]" : "") . " = {$sevo->getExpression()};";

		} elseif ($property->getType() instanceof IntType) {
			$ret .= "\$object->{$property->getName()}" . ($isArray ? "[]" : "") . " = intval(trim({$expr}));";

		} elseif ($property->getType() instanceof FloatType) {
			$ret .= "\$object->{$property->getName()}" . ($isArray ? "[]" : "") . " = floatval(trim({$expr}));";

		} elseif ($property->getType() instanceof BoolType) {
			$ret .= "\$object->{$property->getName()}" . ($isArray ? "[]" : "") . " = !!trim({$expr});";

		} else {
			$ret .= "\$object->{$property->getName()}" . ($isArray ? "[]" : "") . " = {$expr};";
		}

		return $ret;
	}

}
