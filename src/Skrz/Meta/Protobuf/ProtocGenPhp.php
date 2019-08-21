<?php
namespace Skrz\Meta\Protobuf;

use Google\Protobuf\Compiler\CodeGeneratorRequest;
use Google\Protobuf\Compiler\CodeGeneratorResponse;
use Google\Protobuf\DescriptorProto;
use Google\Protobuf\EnumDescriptorProto;
use Google\Protobuf\FieldDescriptorProto;
use Google\Protobuf\FileDescriptorProto;
use Google\Protobuf\Meta\DescriptorProtoMeta;
use Google\Protobuf\Meta\FileDescriptorProtoMeta;
use Google\Protobuf\SourceCodeInfo;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\PhpFile;
use Skrz\Meta\AbstractMetaSpec;
use Skrz\Meta\MetaSpec;
use Skrz\Meta\Reflection\Type;
use Skrz\Meta\Result;

class ProtocGenPhp
{

	/** @var FileDescriptorProto */
	private $file;

	/** @var DescriptorProto[] */
	private $messages;

	/** @var EnumDescriptorProto[] */
	private $enums;

	/** @var int[][] */
	private $paths;

	/** @var SourceCodeInfo[] */
	private $sourceCodeInfo;

	public function handle(CodeGeneratorRequest $request)
	{
		$files = array();

		foreach ((array)$request->getProtoFile() as $file) {
			/** @var FileDescriptorProto $file */

			$this->collectFile($file, array());

			$specClassName = MetaSpec::class;
			$customSpecClassName = false;

			foreach ($file->getSourceCodeInfo()->getLocation() as $location) {
				if ($location->getPath() === array(FileDescriptorProtoMeta::PACKAGE_PROTOBUF_FIELD) &&
						$location->getLeadingComments() &&
						preg_match("/@spec\\s+([a-zA-Z0-9_\\\\]+)/", $location->getLeadingComments(), $m)
				) {
					$specClassName = $m[1];
					$customSpecClassName = true;
				}
			}

			uksort($this->messages, function ($a, $b) {
				if (strlen($a) === strlen($b)) {
					return strcmp($b, $a);
				}

				return strlen($b) - strlen($a);
			});

			$tmpFiles = array();

			foreach ($this->messages as $className => $message) {
				// compile message file
				$codeFile = new CodeGeneratorResponse\File();
				$codeFile->setName(str_replace("\\", "/", $className) . ".php");

				$result = $this->generateMessage($className, $message);
				$codeFile->setContent((string)$result->getFile());

				$files[$className] = $codeFile;

				// compile meta file
				if (!class_exists($className)) {
					$tmpFiles[] = $tmpFile = tempnam(sys_get_temp_dir(), "protoc-gen-php");
					file_put_contents($tmpFile, (string)$result->getFile());
					require_once $tmpFile;
				}

				/** @var AbstractMetaSpec $spec */
				$spec = new $specClassName();

				if (!$customSpecClassName) {
					$spec->match($className)->addModule(new ProtobufModule());
				}

				$metaResult = $spec->compile($type = Type::fromString($className));

				if ($metaResult !== null) {
					$metaFile = new CodeGeneratorResponse\File();
					$metaFile->setName(str_replace("\\", "/", $spec->createMetaClassName($type)) . ".php");
					$metaFile->setContent((string)$metaResult->getFile());
					$files[$metaResult->getClass()->getName()] = $metaFile;
				}
			}

			foreach ($tmpFiles as $tmpFile) {
				unlink($tmpFile);
			}

			foreach ($this->enums as $className => $enum) {
				$enumFile = new CodeGeneratorResponse\File();
				$enumFile->setName(str_replace("\\", "/", $className) . ".php");

				$result = $this->generateEnum($className, $enum);
				$enumFile->setContent((string)$result->getFile());

				$files[$className] = $enumFile;
			}
		}

		$response = new CodeGeneratorResponse();
		$response->setFile($files);

		return $response;
	}

	private function convertPackageToNamespace($package)
	{
		return implode("\\", array_map(function ($s) {
			return ucfirst($s);
		}, explode(".", trim($package, "."))));
	}

	private function collectFile(FileDescriptorProto $file, $path)
	{
		$this->file = $file;
		$this->messages = array();
		$this->enums = array();
		$this->paths = array();
		$this->sourceCodeInfo = array();

		foreach ((array)$file->getMessageType() as $i => $nestedMessage) {
			$this->collectMessage(
				$file->getPackage(),
				$nestedMessage,
				array_merge($path, array(FileDescriptorProtoMeta::MESSAGE_TYPE_PROTOBUF_FIELD, $i))
			);
		}

		foreach ((array)$file->getEnumType() as $i => $nestedEnum) {
			$this->collectEnum(
				$file->getPackage(),
				$nestedEnum,
				array_merge($path, array(FileDescriptorProtoMeta::ENUM_TYPE_PROTOBUF_FIELD, $i))
			);
		}
	}

	private function collectMessage($package, DescriptorProto $message, $path)
	{
		$messagePackage = $package . "." . $message->getName();
		$className = $this->convertPackageToNamespace($messagePackage);

		$this->messages[$className] = $message;
		$this->setPath($className, $path);

		foreach ((array)$message->getNestedType() as $i => $nestedMessage) {
			$this->collectMessage(
				$messagePackage,
				$nestedMessage,
				array_merge($path, array(DescriptorProtoMeta::NESTED_TYPE_PROTOBUF_FIELD, $i))
			);
		}

		foreach ((array)$message->getEnumType() as $i => $nestedEnum) {
			$this->collectEnum(
				$messagePackage,
				$nestedEnum,
				array_merge($path, array(DescriptorProtoMeta::ENUM_TYPE_PROTOBUF_FIELD, $i))
			);
		}
	}

	private function collectEnum($package, EnumDescriptorProto $enum, $path)
	{
		$enumPackage = $package . "." . $enum->getName();
		$className = $this->convertPackageToNamespace($enumPackage) . "Enum";

		$this->enums[$className] = $enum;
		$this->setPath($className, $path);
	}

	private function setPath($className, $path)
	{
		$this->paths[$className] = $path;
		$this->sourceCodeInfo[$className] = $this->file->getSourceCodeInfo();
	}

	private function getSourceCodeInfo($className, $path = null)
	{
		if ($path === null) {
			$path = $this->paths[$className];
		}

		foreach ((array)$this->sourceCodeInfo[$className]->getLocation() as $location) {
			/** @var SourceCodeInfo\Location $location */

			if ($location->getPath() === $path) {
				return $location;
			}
		}

		return null;
	}

	private function generateEnum($className, EnumDescriptorProto $enum)
	{
		$file = new PhpFile();

		$class = $file->addClass($className);
		$class->setFinal(true);

		if (($info = $this->getSourceCodeInfo($className)) && $info->getLeadingComments()) {
			$class->addComment(trim($info->getLeadingComments()));
		}

		$this->addAutoGeneratedWarning($class);

		foreach ($enum->getValue() as $value) {
			$class->addConstant($value->getName(), $value->getNumber());
		}

		return new Result($file, $class);
	}

	private function generateMessage($className, DescriptorProto $message)
	{
		$file = new PhpFile();

		$class = $file->addClass($className);
		$class->setFinal(true);
		$ns = $class->getNamespace();

		$ns->addUse(ProtobufField::class, null, $protobufFieldAlias);

		if (($info = $this->getSourceCodeInfo($className)) && $info->getLeadingComments()) {
			$class->addComment(trim($info->getLeadingComments()));
		}

		$this->addAutoGeneratedWarning($class);

		foreach ((array)$message->getField() as $i => $field) {
			/** @var FieldDescriptorProto $field */

			$propertyName = lcfirst(implode("", array_map(function ($s) {
				return ucfirst($s);
			}, explode("_", $field->getName()))));

			$property = $class->addProperty($propertyName);
			$property->setVisibility("protected");

			if (($info = $this->getSourceCodeInfo($className, array_merge($this->paths[$className], [DescriptorProtoMeta::FIELD_PROTOBUF_FIELD, $i]))) &&
				$info->getLeadingComments()
			) {
				$property->addComment(str_replace("*/", "* /", trim($info->getLeadingComments())));
				$property->addComment("");
			}

			switch ($field->getType()) {
				case FieldDescriptorProto\TypeEnum::TYPE_DOUBLE:
					$wireType = WireTypeEnum::FIXED64;
					$phpType = "float";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_FLOAT:
					$wireType = WireTypeEnum::FIXED32;
					$phpType = "float";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_UINT64:
				case FieldDescriptorProto\TypeEnum::TYPE_UINT32:
					$unsigned = true;
				case FieldDescriptorProto\TypeEnum::TYPE_INT64:
				case FieldDescriptorProto\TypeEnum::TYPE_INT32:
					$wireType = WireTypeEnum::VARINT;
					$phpType = "int";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_FIXED64:
					$unsigned = true;
				case FieldDescriptorProto\TypeEnum::TYPE_SFIXED64:
					$wireType = WireTypeEnum::FIXED64;
					$phpType = "int";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_FIXED32:
					$unsigned = true;
				case FieldDescriptorProto\TypeEnum::TYPE_SFIXED32:
					$wireType = WireTypeEnum::FIXED32;
					$phpType = "int";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_BOOL:
					$wireType = WireTypeEnum::VARINT;
					$phpType = "bool";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_STRING:
				case FieldDescriptorProto\TypeEnum::TYPE_BYTES:
					$wireType = WireTypeEnum::STRING;
					$phpType = "string";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_MESSAGE:
					$wireType = WireTypeEnum::STRING;
					$fieldClassName = $this->convertPackageToNamespace($field->getTypeName());
					$ns->addUse($fieldClassName, null, $phpType);
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_ENUM:
					$wireType = WireTypeEnum::VARINT;
					$fieldClassName = $this->convertPackageToNamespace($field->getTypeName() . "Enum");
					$ns->addUse($fieldClassName, null, $see);
					$phpType = "int";
					break;

				case FieldDescriptorProto\TypeEnum::TYPE_SINT32:
				case FieldDescriptorProto\TypeEnum::TYPE_SINT64:
					$wireType = WireTypeEnum::ZIGZAG;
					$phpType = "int";
					break;

				default:
					throw new \LogicException("Unhandled type '{$field->getType()}'.");
			}

			if ($field->getLabel() === FieldDescriptorProto\LabelEnum::LABEL_REPEATED) {
				$phpType .= "[]";
			}

			$property->addComment("@var {$phpType}");

			if (isset($see)) {
				$property->addComment("@see {$see}");
				unset($see);
			}

			$property
				->addComment("")
				->addComment(
					"@" . ProtobufField::class . "(" .
					"number={$field->getNumber()}" .
					", wireType=\"{$wireType}\"" .
					", unsigned=" . Helpers::dump(isset($unsigned) ? $unsigned : false) .
					", packed=" . Helpers::dump($field->getOptions() ? $field->getOptions()->getPacked() : false) .
					")"
				);


			$getter = $class->addMethod("get" . ucfirst($propertyName));
			$getter
				->addComment("@return {$phpType}");
			$getter
				->addBody("return \$this->{$propertyName};");
			$setter = $class->addMethod("set" . ucfirst($propertyName));
			$setter->addParameter($propertyName);
			$setter
				->addComment("@param {$phpType} \${$propertyName}")
				->addComment("")
				->addComment("@return self");
			$setter
				->addBody("\$this->{$propertyName} = \${$propertyName};")
				->addBody("return \$this;");
		}

		return new Result($file, $class);
	}

	private function addAutoGeneratedWarning(ClassType $class)
	{
		if ($class->getComment() !== null && $class->getComment() !== "") {
			$class->addComment("");
		}

		$class
			->addComment("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
			->addComment("!!!                                                     !!!")
			->addComment("!!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!")
			->addComment("!!!                                                     !!!")
			->addComment("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
	}

}
