<?php

use UserEx\Todo\Core\Kernel;
use Nette\Http\RequestFactory;

/**
 * @var ClassLoader $loader
 */
require_once  __DIR__.'/../vendor/autoload.php';


$requestFactory = new RequestFactory();
$request = $requestFactory->createHttpRequest();

$kernel = new Kernel();
$kernel->handle($request);
