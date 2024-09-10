<?php

use src\Calculate;

require_once 'src/bootstrap.php';

$data_file = $argv[1] ?: 'input.txt';

echo (new Calculate())->getCommissions($data_file);
