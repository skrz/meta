<?php
namespace Skrz\Meta\Reflection;

class ReflectorProtocol
{

	const REFLECT_CLASS_REQUEST = "REFLECT_CLASS_REQUEST";

	const REFLECT_CLASS_RESPONSE = "REFLECT_CLASS_RESPONSE";

	const REFLECT_CLASS_NOT_FOUND_RESPONSE = "REFLECT_CLASS_NOT_FOUND_RESPONSE";

	const REFLECT_FILE_REQUEST = "REFLECT_FILE_REQUEST";

	const REFLECT_FILE_RESPONSE = "REFLECT_FILE_RESPONSE";

	const REFLECT_FILE_NOT_FOUND_RESPONSE = "REFLECT_FILE_NOT_FOUND_RESPONSE";

	const BLOCK_SIZE = 4096;

	public static function writeMessage($stream, $message)
	{
		$serialized = serialize($message);
		$data = pack("N", strlen($serialized)) . $serialized;

		for ($written = 0; $written < strlen($data); $written += $newlyWritten) {
			$newlyWritten = @fwrite($stream, substr($data, $written)); // intentionally @

			if ($newlyWritten === false) {
				throw new \RuntimeException("Date could not be written to stream.");
			}
		}
	}

	public static function readMessage($stream)
	{
		$length = fread($stream, 4);

		if (feof($stream)) {
			throw new ReflectorProtocolEofException();
		}

		if (strlen($length) !== 4) {
			throw new \RuntimeException("Data could not be read from stream.");
		}

		list(,$length) = unpack("N", $length);

		for ($serialized = "", $read = 0; strlen($serialized) < $length; $read += strlen($newlyRead)) {
			$serialized .= $newlyRead = fread($stream, min(self::BLOCK_SIZE, $length - strlen($serialized)));
		}

		if (strlen($serialized) !== $length) {
			throw new \RuntimeException("Data could not be read from stream.");
		}

		$unserialized = @unserialize($serialized); // intentionally @

		if ($unserialized === false) {
			throw new \RuntimeException("Read data could not be unserialized.");
		}

		return $unserialized;
	}

}
