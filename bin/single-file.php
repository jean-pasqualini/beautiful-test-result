<?php
namespace EntryPoint;

use App\SingleFileCommand;
use Symfony\Component\Console\Application;

require __DIR__.'/../vendor/autoload.php';
$application = new Application();
$application->add(new SingleFileCommand());
$application->run();


