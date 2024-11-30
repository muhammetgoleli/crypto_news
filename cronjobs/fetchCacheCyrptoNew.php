<?php

$projectRoot = dirname(__DIR__);

exec("php {$projectRoot}/artisan schedule:run");

echo "schedule:run triggered.\n";
