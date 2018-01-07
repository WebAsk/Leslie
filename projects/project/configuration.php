<?php

defined('PROJECT_ROOT') or die('Project can not work');

require_once realpath(PROJECT_ROOT . '/../..') . '/configuration.php';

$PROJECT['NAME'] = 'Project';
$PROJECT['TEMPLATE'] = 'default';
$PROJECT['LOGO'] = null; // $PROJECT['URL']['IMAGES'] . '/logos/logo.png';
$PROJECT['FAVICON'] = $PROJECT['URL']['IMAGES'] . '/favicons/favicon-32x32.png';