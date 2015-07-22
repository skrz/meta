<?php
namespace Skrz\Meta;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class MetaCommand extends Command
{

	protected function configure()
	{
		$this
			->setName("meta")
			->setDescription("Processes file/directory of files according to given meta spec class.")
			->addOption(
				"spec",
				"s",
				InputOption::VALUE_REQUIRED,
				"Meta spec class name."
			)
			->addOption(
				"file",
				"f",
				InputOption::VALUE_REQUIRED,
				"File to be processed."
			)
			->addOption(
				"directory",
				"d",
				InputOption::VALUE_REQUIRED,
				"Directory to be processed."
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if (($specClassName = $input->getOption("spec")) === null) {
			throw new \InvalidArgumentException("You have to specify --spec option.");
		}

		// intentionally triggers autoload
		if (!class_exists($specClassName)) {
			throw new \InvalidArgumentException("Spec class '{$specClassName}' is not loadable.");
		}

		/** @var AbstractMetaSpec $spec */
		$spec = new $specClassName();

		if (($fileName = $input->getOption("file")) !== null) {
			$spec->processFiles(array(
				$fileName
			));

		} elseif (($directoryName = $input->getOption("directory")) !== null) {
			$fileNames = array_map(function (\SplFileInfo $file) {
				return $file->getPathname();
			}, iterator_to_array(
				(new Finder())
					->in($directoryName)
					->name("*.php")
					->notName("*Meta*")
			));

			$spec->processFiles($fileNames);

		} else {
			throw new \InvalidArgumentException("You have to specify either --file, or --directory option.");
		}
	}

}
