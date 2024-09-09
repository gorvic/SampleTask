<?php

use src\Calculate;

require_once 'src/bootstrap.php';

$data_file = $argv[1] ?: 'input.txt';

Calculate::commissions($data_file);

die('ok');