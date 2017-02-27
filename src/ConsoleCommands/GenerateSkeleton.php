<?php namespace IndexIO\Operatur\ConsoleCommands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Exception;
use InvalidArgumentException;

class GenerateSkeleton extends Command
{
	protected function configure()
	{
		$appFolderName = 'app';

		$this->setName('operatur:skeleton')
			->setDescription('Generates the skeleton (folders and files) for working with operatur library.')
			->setDefinition([
				new InputOption('app-folder-name', 'p', InputOption::VALUE_OPTIONAL, 'Where the Workers should sit, main app folder', $appFolderName)
			])
			->setHelp('TODO: Help function');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = $input->getOption('app-folder-name');
		
		$globablPath = __DIR__ . '/../../' . $path;
		if (!file_exists($globablPath)) {
			$globablPath = __DIR__ . '/../../../../../' . $path;
			if (!file_exists($globablPath)) {
				throw new InvalidArgumentException('The folder provided does not exist.');
			}
		}

		$output->writeln('exists');
		$this->createWorkersFolder($globablPath);
		
		$config = $this->createConfigFile($globablPath);
		$routes = $this->createRoutesFile($globablPath);

		$this->createSampleWorkersFiles($globablPath, $routes);
	}

	public function createWorkersFolder($path)
	{
		$path = $path . '/Workers';
		if (!file_exists($path)){
			mkdir($path);
		}
	}

	public function createConfigFile($path)
	{
		$configFile = $path . '/Workers/config.php';
		list($rawData, $arrayData) = $this->getConfigFileSample();

		try {
			$handle = fopen($configFile, 'w');
			fwrite($handle, $rawData);
			fclose($handle);
		} catch (Exception $e) {
			throw new InvalidArgumentException('Cannot create config file on path ' . $configFile);
		}

		return $arrayData;
	}

	public function getConfigFileSample()
	{
		$configSamplePath = __DIR__ . '/../../' . 'sample/config.php';
		try {
			$handle = fopen($configSamplePath, 'r');
			$rawData = fread($handle, filesize($configSamplePath));
			fclose($handle);
		} catch (Exception $e) {
			throw new InvalidArgumentException('Cannot open sample config file at ' . $configSamplePath);
		}

		$arrayData = require_once $configSamplePath;
		return [
			$rawData,
			$arrayData
		];
	}

	public function createRoutesFile($path)
	{
		$routesFile = $path . '/Workers/routes.php';
		list($rawData, $arrayData) = $this->getRoutesFileSample();

		try {
			$handle = fopen($routesFile, 'w');
			fwrite($handle, $rawData);
			fclose($handle);
		} catch (Exception $e) {
			throw new InvalidArgumentException('Cannot create routes file on path ' . $routesFile);
		}

		return $arrayData;
	}

	public function getRoutesFileSample()
	{
		$routesSamplePath = __DIR__ . '/../../' . 'sample/routes.php';
		try {
			$handle = fopen($routesSamplePath, 'r');
			$rawData = fread($handle, filesize($routesSamplePath));
			fclose($handle);
		} catch (Exception $e) {
			throw new InvalidArgumentException('Cannot open sample routes file at ' . $routesSamplePath);
		}

		$arrayData = require_once $routesSamplePath;
		return [
			$rawData,
			$arrayData
		];
	}

	public function createSampleWorkersFiles($path)
	{
		$workers = $this->getSampleWorkerFiles();
		
		foreach ($workers as $worker) {
			$name = $worker['name'];
			$data = $worker['data'];

			$workerFile = $path . '/Workers/' . $name . '.php';

			try {
				$handle = fopen($workerFile, 'w');
				fwrite($handle, $data);
				fclose($handle);
			} catch (Exception $e) {
				throw new InvalidArgumentException('Cannot create worker file on path ' . $workerFile);
			}
		}
	}

	public function getSampleWorkerFiles()
	{
		$paths = [
			'SendWelcomeEmail' => __DIR__ . '/../../' . 'sample/SendWelcomeEmail.php'
		];

		$workers = [];
		foreach ($paths as $fileName => $path) {
			try {
				$handle = fopen($path, 'r');
				$data = fread($handle, filesize($path));
				$workers[] = [
					'name' => $fileName,
					'data' => $data
				];

				fclose($handle);
			} catch (Exception $e) {
				throw new InvalidArgumentException('Cannot open sample worker file at ' . $path);
			}
		}

		return $workers;
	}
}