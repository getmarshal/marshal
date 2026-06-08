<?php

use Marshal\Scheduler\TaskRunner;

$cwd = __DIR__;

// autoload classes
require "$cwd/vendor/autoload.php";

// build container
$container = require "$cwd/config/container.php";

// execute the runner
$runner = $container->get(TaskRunner::class);
\assert($runner instanceof TaskRunner);
$runner->run();
