<?php
namespace Skrz\Meta\Reflection;

class Reflector 
{

	/** @var resource */
	private $process;

	/** @var resource */
	private $workerIn;

	/** @var resource */
	private $workerOut;

	public function __construct()
	{
		if (!defined("PHP_BINARY")) {
			throw new \RuntimeException(
				"PHP_BINARY constant is not defined. Probably you use PHP < 5.4, please upgrade."
			);
		}

		if (intval(ini_get("mbstring.func_overload")) & 0x2 !== 0) {
			throw new \RuntimeException(
				"mbstring.func_overload overloads strlen() function. Please disable it, otherwise Reflector cannot be used."
			);
		}

		$composerAutoloader = null;
		foreach (get_declared_classes() as $className) {
			if (preg_match("/^ComposerAutoloaderInit/", $className)) {
				$composerAutoloader = $className;
			}
		}

		if ($composerAutoloader === null) {
			throw new \RuntimeException(
				"No Composer autoloader found."
			);
		}

		$rc = new \ReflectionClass($composerAutoloader);

		$this->process = proc_open(
			PHP_BINARY .
			" " . __DIR__ . DIRECTORY_SEPARATOR . "ReflectorWorker.php" .
			" " . $rc->getFileName() .
			" " . $rc->getName(),
			array(
				0 => STDIN,
				1 => STDOUT,
				2 => STDERR,
				3 => array("pipe", "r"),
				4 => array("pipe", "w")
			),
			$pipes
		);

		$this->workerIn = $pipes[3];
		$this->workerOut = $pipes[4];
	}

	/**
	 * @param string $className
	 * @return Type
	 * @throws ReflectorProtocolUnsupportedOperationException
	 * @throws ReflectorClassNotFoundException
	 */
	public function reflect($className)
	{
		$this->writeMessage(array(ReflectorProtocol::REFLECT_CLASS_REQUEST, $className));
		$message = $this->readMessage();
		list($messageType) = $message;

		if ($messageType === ReflectorProtocol::REFLECT_CLASS_RESPONSE) {
			return $message[1];
		} elseif ($messageType === ReflectorProtocol::REFLECT_CLASS_NOT_FOUND_RESPONSE) {
			throw new ReflectorClassNotFoundException();
		} else {
			throw new ReflectorProtocolUnsupportedOperationException("Message type '{$messageType}'.");
		}
	}

	/**
	 * @param string $fileName
	 * @throws ReflectorFileNotFoundException
	 * @throws ReflectorProtocolUnsupportedOperationException
	 * @return Type[]
	 */
	public function reflectFile($fileName)
	{
		$this->writeMessage(array(ReflectorProtocol::REFLECT_FILE_REQUEST, $fileName));
		$message = $this->readMessage();
		list($messageType) = $message;

		if ($messageType === ReflectorProtocol::REFLECT_FILE_RESPONSE) {
			return $message[1];
		} elseif ($messageType === ReflectorProtocol::REFLECT_FILE_NOT_FOUND_RESPONSE) {
			throw new ReflectorFileNotFoundException();
		} else {
			throw new ReflectorProtocolUnsupportedOperationException("Message type '{$messageType}'.");
		}
	}

	private function writeMessage($message)
	{
		ReflectorProtocol::writeMessage($this->workerIn, $message);
	}

	private function readMessage()
	{
		return ReflectorProtocol::readMessage($this->workerOut);
	}

	public function __destruct()
	{
		fclose($this->workerIn);
		fclose($this->workerOut);
		proc_close($this->process);
	}

}
