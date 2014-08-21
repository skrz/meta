<?php
namespace Skrz\Meta\Reflection;

class ReflectorWorker
{

	/** @var resource */
	private $in;

	/** @var resource */
	private $out;

	public function __construct()
	{
		$this->in = fopen("php://fd/3", "r");
		$this->out = fopen("php://fd/4", "w");
	}


	private function readMessage()
	{
		return ReflectorProtocol::readMessage($this->in);
	}

	private function writeMessage($message)
	{
		ReflectorProtocol::writeMessage($this->out, $message);
	}

	public function run()
	{
		try {
			while (!feof($this->in) && $message = $this->readMessage()) {
				list($messageType) = $message;

				if ($messageType === ReflectorProtocol::REFLECT_CLASS_REQUEST) {
					list(, $className) = $message;

					// intentionally triggers autoload
					if (!class_exists($className) && !interface_exists($className) && !trait_exists($className)) {
						$this->writeMessage(array(
							ReflectorProtocol::REFLECT_CLASS_NOT_FOUND_RESPONSE,
							$className
						));

					} else {
						$this->writeMessage(array(
							ReflectorProtocol::REFLECT_CLASS_RESPONSE,
							Type::fromReflection(new \ReflectionClass($className))
						));
					}

				} elseif ($messageType === ReflectorProtocol::REFLECT_FILE_REQUEST) {
					list(, $fileName) = $message;
					$fileName = realpath($fileName);

					if (!$fileName || !is_readable($fileName)) {
						$this->writeMessage(array(
							ReflectorProtocol::REFLECT_FILE_NOT_FOUND_RESPONSE,
							$fileName
						));
					} else {
						require_once $fileName;

						$types = array();
						foreach (array_merge(get_declared_classes(), get_declared_interfaces(), get_declared_traits()) as $typeName) {
							$rc = new \ReflectionClass($typeName);
							if ($rc->getFileName() && realpath($rc->getFileName()) === $fileName) {
								$types[] = Type::fromReflection($rc);
							}
						}

						$this->writeMessage(array(
							ReflectorProtocol::REFLECT_FILE_RESPONSE,
							$types
						));
					}

				} else {
					fwrite(STDERR, "Unsupported message type '{$messageType}'.");
					break;
				}
			}
		} catch (ReflectorProtocolEofException $e) {

		}
	}

}

// Reflector passes Composer autoload file as first parameter
require_once $_SERVER["argv"][1];

// Reflector passes ComposerAutoloaderInit* class name as second parameter
$autoloader = $_SERVER["argv"][2];
$autoloader::getLoader();

$worker = new ReflectorWorker();
$worker->run();
