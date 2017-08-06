<?php
$phar = new Phar('install-emoticon-phpunit.phar');
$phar->startBuffering();
$defaultStub = $phar->createDefaultStub('bin/single-file.php');
$phar->buildFromDirectory(dirname(__FILE__));
$stub = '#!/usr/bin/env php '.PHP_EOL.$defaultStub;
$phar->setStub($stub);
$phar->stopBuffering();