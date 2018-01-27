<?php
use UserEx\Todo\Core\Kernel;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/vendor/autoload.php';

$kernel = new Kernel();
$container = $kernel->getContainer();

return ConsoleRunner::createHelperSet($container['em']);