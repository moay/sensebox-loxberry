<?php

use LoxBerryPoppins\Frontend\Routing\PageRouterInterface;
use LoxBerryPoppins\PluginKernel;

require_once 'REPLACELBPDATADIR/vendor/autoload.php';

$kernel = new PluginKernel('REPLACELBPDATADIR');

$pageRouter = $kernel->getContainer()->get(PageRouterInterface::class);
$pageRouter->process();
