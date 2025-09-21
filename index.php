<?php
require __DIR__ . '/vendor/autoload.php';

use Jenssegers\Blade\Blade;

$blade = new Blade(__DIR__ . '/resources/views', __DIR__ . '/storage/cache');

// Contoh: render admin dashboard
echo $blade->render('layout.partials.admin');
