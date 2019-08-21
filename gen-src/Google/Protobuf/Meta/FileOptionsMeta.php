<?php
namespace Google\Protobuf\Meta;

use Closure;
use Google\Protobuf\FileOptions;
use Skrz\Meta\MetaInterface;
use Skrz\Meta\Protobuf\Binary;
use Skrz\Meta\Protobuf\ProtobufException;
use Skrz\Meta\Protobuf\ProtobufMetaInterface;

/**
 * Meta class for \Google\Protobuf\FileOptions
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!                                                     !!!
 * !!!   THIS CLASS HAS BEEN AUTO-GENERATED, DO NOT EDIT   !!!
 * !!!                                                     !!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
final class FileOptionsMeta implements MetaInterface, ProtobufMetaInterface
{
	const JAVA_PACKAGE_PROTOBUF_FIELD = 1;
	const JAVA_OUTER_CLASSNAME_PROTOBUF_FIELD = 8;
	const JAVA_MULTIPLE_FILES_PROTOBUF_FIELD = 10;
	const JAVA_GENERATE_EQUALS_AND_HASH_PROTOBUF_FIELD = 20;
	const JAVA_STRING_CHECK_UTF8_PROTOBUF_FIELD = 27;
	const OPTIMIZE_FOR_PROTOBUF_FIELD = 9;
	const GO_PACKAGE_PROTOBUF_FIELD = 11;
	const CC_GENERIC_SERVICES_PROTOBUF_FIELD = 16;
	const JAVA_GENERIC_SERVICES_PROTOBUF_FIELD = 17;
	const PY_GENERIC_SERVICES_PROTOBUF_FIELD = 18;
	const DEPRECATED_PROTOBUF_FIELD = 23;
	const CC_ENABLE_ARENAS_PROTOBUF_FIELD = 31;
	const OBJC_CLASS_PREFIX_PROTOBUF_FIELD = 36;
	const CSHARP_NAMESPACE_PROTOBUF_FIELD = 37;
	const JAVANANO_USE_DEPRECATED_PACKAGE_PROTOBUF_FIELD = 38;
	const UNINTERPRETED_OPTION_PROTOBUF_FIELD = 999;

	/** @var FileOptionsMeta */
	private static $instance;

	/** @var callable */
	private static $reset;

	/** @var callable */
	private static $hash;

	/** @var callable */
	private static $fromProtobuf;

	/** @var callable */
	private static $toProtobuf;


	/**
	 * Constructor
	 */
	private function __construct()
	{
		self::$instance = $this; // avoids cyclic dependency stack overflow
	}


	/**
	 * Returns instance of this meta class
	 *
	 * @return FileOptionsMeta
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			new self(); // self::$instance assigned in __construct
		}
		return self::$instance;
	}


	/**
	 * Creates new instance of \Google\Protobuf\FileOptions
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return FileOptions
	 */
	public static function create()
	{
		switch (func_num_args()) {
			case 0:
				return new FileOptions();
			case 1:
				return new FileOptions(func_get_arg(0));
			case 2:
				return new FileOptions(func_get_arg(0), func_get_arg(1));
			case 3:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2));
			case 4:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));
			case 5:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			case 6:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));
			case 7:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6));
			case 8:
				return new FileOptions(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5), func_get_arg(6), func_get_arg(7));
			default:
				throw new \InvalidArgumentException('More than 8 arguments supplied, please be reasonable.');
		}
	}


	/**
	 * Resets properties of \Google\Protobuf\FileOptions to default values
	 *
	 *
	 * @param FileOptions $object
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return void
	 */
	public static function reset($object)
	{
		if (!($object instanceof FileOptions)) {
			throw new \InvalidArgumentException('You have to pass object of class Google\Protobuf\FileOptions.');
		}

		if (self::$reset === null) {
			self::$reset = Closure::bind(static function ($object) {
				$object->javaPackage = null;
				$object->javaOuterClassname = null;
				$object->javaMultipleFiles = null;
				$object->javaGenerateEqualsAndHash = null;
				$object->javaStringCheckUtf8 = null;
				$object->optimizeFor = null;
				$object->goPackage = null;
				$object->ccGenericServices = null;
				$object->javaGenericServices = null;
				$object->pyGenericServices = null;
				$object->deprecated = null;
				$object->ccEnableArenas = null;
				$object->objcClassPrefix = null;
				$object->csharpNamespace = null;
				$object->javananoUseDeprecatedPackage = null;
				$object->uninterpretedOption = null;
			}, null, FileOptions::class);
		}

		return (self::$reset)($object);
	}


	/**
	 * Computes hash of \Google\Protobuf\FileOptions
	 *
	 * @param object $object
	 * @param string|resource $algoOrCtx
	 * @param bool $raw
	 *
	 * @return string|void
	 */
	public static function hash($object, $algoOrCtx = 'md5', $raw = false)
	{
		if (self::$hash === null) {
			self::$hash = Closure::bind(static function ($object, $algoOrCtx, $raw) {
				if (is_string($algoOrCtx)) {
					$ctx = hash_init($algoOrCtx);
				} else {
					$ctx = $algoOrCtx;
				}

				if (isset($object->javaPackage)) {
					hash_update($ctx, 'javaPackage');
					hash_update($ctx, (string)$object->javaPackage);
				}

				if (isset($object->javaOuterClassname)) {
					hash_update($ctx, 'javaOuterClassname');
					hash_update($ctx, (string)$object->javaOuterClassname);
				}

				if (isset($object->javaMultipleFiles)) {
					hash_update($ctx, 'javaMultipleFiles');
					hash_update($ctx, (string)$object->javaMultipleFiles);
				}

				if (isset($object->javaGenerateEqualsAndHash)) {
					hash_update($ctx, 'javaGenerateEqualsAndHash');
					hash_update($ctx, (string)$object->javaGenerateEqualsAndHash);
				}

				if (isset($object->javaStringCheckUtf8)) {
					hash_update($ctx, 'javaStringCheckUtf8');
					hash_update($ctx, (string)$object->javaStringCheckUtf8);
				}

				if (isset($object->optimizeFor)) {
					hash_update($ctx, 'optimizeFor');
					hash_update($ctx, (string)$object->optimizeFor);
				}

				if (isset($object->goPackage)) {
					hash_update($ctx, 'goPackage');
					hash_update($ctx, (string)$object->goPackage);
				}

				if (isset($object->ccGenericServices)) {
					hash_update($ctx, 'ccGenericServices');
					hash_update($ctx, (string)$object->ccGenericServices);
				}

				if (isset($object->javaGenericServices)) {
					hash_update($ctx, 'javaGenericServices');
					hash_update($ctx, (string)$object->javaGenericServices);
				}

				if (isset($object->pyGenericServices)) {
					hash_update($ctx, 'pyGenericServices');
					hash_update($ctx, (string)$object->pyGenericServices);
				}

				if (isset($object->deprecated)) {
					hash_update($ctx, 'deprecated');
					hash_update($ctx, (string)$object->deprecated);
				}

				if (isset($object->ccEnableArenas)) {
					hash_update($ctx, 'ccEnableArenas');
					hash_update($ctx, (string)$object->ccEnableArenas);
				}

				if (isset($object->objcClassPrefix)) {
					hash_update($ctx, 'objcClassPrefix');
					hash_update($ctx, (string)$object->objcClassPrefix);
				}

				if (isset($object->csharpNamespace)) {
					hash_update($ctx, 'csharpNamespace');
					hash_update($ctx, (string)$object->csharpNamespace);
				}

				if (isset($object->javananoUseDeprecatedPackage)) {
					hash_update($ctx, 'javananoUseDeprecatedPackage');
					hash_update($ctx, (string)$object->javananoUseDeprecatedPackage);
				}

				if (isset($object->uninterpretedOption)) {
					hash_update($ctx, 'uninterpretedOption');
					foreach ($object->uninterpretedOption instanceof \Traversable ? $object->uninterpretedOption : (array)$object->uninterpretedOption as $v0) {
						UninterpretedOptionMeta::hash($v0, $ctx);
					}
				}

				if (is_string($algoOrCtx)) {
					return hash_final($ctx, $raw);
				} else {
					return null;
				}
			}, null, FileOptions::class);
		}

		return (self::$hash)($object, $algoOrCtx, $raw);
	}


	/**
	 * Creates \Google\Protobuf\FileOptions object from serialized Protocol Buffers message.
	 *
	 * @param string $input
	 * @param FileOptions $object
	 * @param int $start
	 * @param int $end
	 *
	 * @throws \Exception
	 *
	 * @return FileOptions
	 */
	public static function fromProtobuf($input, $object = null, &$start = 0, $end = null)
	{
		if (self::$fromProtobuf === null) {
			self::$fromProtobuf = Closure::bind(static function ($input, $object, &$start, $end) {
				if ($object === null) {
					$object = new FileOptions();
				}

				if ($end === null) {
					$end = strlen($input);
				}

				while ($start < $end) {
					$tag = Binary::decodeVarint($input, $start);
					$wireType = $tag & 0x7;
					$number = $tag >> 3;
					switch ($number) {
						case 1:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->javaPackage = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 8:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->javaOuterClassname = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 10:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->javaMultipleFiles = (bool)Binary::decodeVarint($input, $start);
							break;
						case 20:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->javaGenerateEqualsAndHash = (bool)Binary::decodeVarint($input, $start);
							break;
						case 27:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->javaStringCheckUtf8 = (bool)Binary::decodeVarint($input, $start);
							break;
						case 9:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->optimizeFor = Binary::decodeVarint($input, $start);
							break;
						case 11:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->goPackage = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 16:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->ccGenericServices = (bool)Binary::decodeVarint($input, $start);
							break;
						case 17:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->javaGenericServices = (bool)Binary::decodeVarint($input, $start);
							break;
						case 18:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->pyGenericServices = (bool)Binary::decodeVarint($input, $start);
							break;
						case 23:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->deprecated = (bool)Binary::decodeVarint($input, $start);
							break;
						case 31:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->ccEnableArenas = (bool)Binary::decodeVarint($input, $start);
							break;
						case 36:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->objcClassPrefix = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 37:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->csharpNamespace = substr($input, $start, $length);
							$start += $length;
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						case 38:
							if ($wireType !== 0) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 0.', $number);
							}
							$object->javananoUseDeprecatedPackage = (bool)Binary::decodeVarint($input, $start);
							break;
						case 999:
							if ($wireType !== 2) {
								throw new ProtobufException('Unexpected wire type ' . $wireType . ', expected 2.', $number);
							}
							if (!(isset($object->uninterpretedOption) && is_array($object->uninterpretedOption))) {
								$object->uninterpretedOption = array();
							}
							$length = Binary::decodeVarint($input, $start);
							$expectedStart = $start + $length;
							if ($expectedStart > $end) {
								throw new ProtobufException('Not enough data.');
							}
							$object->uninterpretedOption[] = UninterpretedOptionMeta::fromProtobuf($input, null, $start, $start + $length);
							if ($start !== $expectedStart) {
								throw new ProtobufException('Unexpected start. Expected ' . $expectedStart . ', got ' . $start . '.', $number);
							}
							break;
						default:
							switch ($wireType) {
								case 0:
									Binary::decodeVarint($input, $start);
									break;
								case 1:
									$start += 8;
									break;
								case 2:
									$start += Binary::decodeVarint($input, $start);
									break;
								case 5:
									$start += 4;
									break;
								default:
									throw new ProtobufException('Unexpected wire type ' . $wireType . '.', $number);
							}
					}
				}

				return $object;
			}, null, FileOptions::class);
		}

		return (self::$fromProtobuf)($input, $object, $start, $end);
	}


	/**
	 * Serialized \Google\Protobuf\FileOptions to Protocol Buffers message.
	 *
	 * @param FileOptions $object
	 * @param array $filter
	 *
	 * @throws \Exception
	 *
	 * @return string
	 */
	public static function toProtobuf($object, $filter = null)
	{
		if (self::$toProtobuf === null) {
			self::$toProtobuf = Closure::bind(static function (FileOptions $object, $filter) {
				$output = '';

				if (isset($object->javaPackage) && ($filter === null || isset($filter['javaPackage']))) {
					$output .= "\x0a";
					$output .= Binary::encodeVarint(strlen($object->javaPackage));
					$output .= $object->javaPackage;
				}

				if (isset($object->javaOuterClassname) && ($filter === null || isset($filter['javaOuterClassname']))) {
					$output .= "\x42";
					$output .= Binary::encodeVarint(strlen($object->javaOuterClassname));
					$output .= $object->javaOuterClassname;
				}

				if (isset($object->javaMultipleFiles) && ($filter === null || isset($filter['javaMultipleFiles']))) {
					$output .= "\x50";
					$output .= Binary::encodeVarint((int)$object->javaMultipleFiles);
				}

				if (isset($object->javaGenerateEqualsAndHash) && ($filter === null || isset($filter['javaGenerateEqualsAndHash']))) {
					$output .= "\xa0\x01";
					$output .= Binary::encodeVarint((int)$object->javaGenerateEqualsAndHash);
				}

				if (isset($object->javaStringCheckUtf8) && ($filter === null || isset($filter['javaStringCheckUtf8']))) {
					$output .= "\xd8\x01";
					$output .= Binary::encodeVarint((int)$object->javaStringCheckUtf8);
				}

				if (isset($object->optimizeFor) && ($filter === null || isset($filter['optimizeFor']))) {
					$output .= "\x48";
					$output .= Binary::encodeVarint($object->optimizeFor);
				}

				if (isset($object->goPackage) && ($filter === null || isset($filter['goPackage']))) {
					$output .= "\x5a";
					$output .= Binary::encodeVarint(strlen($object->goPackage));
					$output .= $object->goPackage;
				}

				if (isset($object->ccGenericServices) && ($filter === null || isset($filter['ccGenericServices']))) {
					$output .= "\x80\x01";
					$output .= Binary::encodeVarint((int)$object->ccGenericServices);
				}

				if (isset($object->javaGenericServices) && ($filter === null || isset($filter['javaGenericServices']))) {
					$output .= "\x88\x01";
					$output .= Binary::encodeVarint((int)$object->javaGenericServices);
				}

				if (isset($object->pyGenericServices) && ($filter === null || isset($filter['pyGenericServices']))) {
					$output .= "\x90\x01";
					$output .= Binary::encodeVarint((int)$object->pyGenericServices);
				}

				if (isset($object->deprecated) && ($filter === null || isset($filter['deprecated']))) {
					$output .= "\xb8\x01";
					$output .= Binary::encodeVarint((int)$object->deprecated);
				}

				if (isset($object->ccEnableArenas) && ($filter === null || isset($filter['ccEnableArenas']))) {
					$output .= "\xf8\x01";
					$output .= Binary::encodeVarint((int)$object->ccEnableArenas);
				}

				if (isset($object->objcClassPrefix) && ($filter === null || isset($filter['objcClassPrefix']))) {
					$output .= "\xa2\x02";
					$output .= Binary::encodeVarint(strlen($object->objcClassPrefix));
					$output .= $object->objcClassPrefix;
				}

				if (isset($object->csharpNamespace) && ($filter === null || isset($filter['csharpNamespace']))) {
					$output .= "\xaa\x02";
					$output .= Binary::encodeVarint(strlen($object->csharpNamespace));
					$output .= $object->csharpNamespace;
				}

				if (isset($object->javananoUseDeprecatedPackage) && ($filter === null || isset($filter['javananoUseDeprecatedPackage']))) {
					$output .= "\xb0\x02";
					$output .= Binary::encodeVarint((int)$object->javananoUseDeprecatedPackage);
				}

				if (isset($object->uninterpretedOption) && ($filter === null || isset($filter['uninterpretedOption']))) {
					foreach ($object->uninterpretedOption instanceof \Traversable ? $object->uninterpretedOption : (array)$object->uninterpretedOption as $k => $v) {
						$output .= "\xba\x3e";
						$buffer = UninterpretedOptionMeta::toProtobuf($v, $filter === null ? null : $filter['uninterpretedOption']);
						$output .= Binary::encodeVarint(strlen($buffer));
						$output .= $buffer;
					}
				}

				return $output;
			}, null, FileOptions::class);
		}

		return (self::$toProtobuf)($object, $filter);
	}
}
