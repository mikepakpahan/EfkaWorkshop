<?php
// Panggil autoload dari Composer
require 'vendor/autoload.php';

// Import semua kelas yang dibutuhkan
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Compilers\BladeCompiler;

// Tentukan path ke folder views dan cache
$viewsPath = __DIR__ . '/resources/views';
$cachePath = __DIR__ . '/cache';

// Inisialisasi komponen-komponen Blade
$filesystem = new Filesystem;
$eventDispatcher = new Dispatcher;
$viewResolver = new EngineResolver;
$bladeCompiler = new BladeCompiler($filesystem, $cachePath);

// Daftarin engine Blade
$viewResolver->register('blade', function () use ($bladeCompiler) {
    return new CompilerEngine($bladeCompiler);
});

// Bikin Factory View utama
$viewFinder = new FileViewFinder($filesystem, [$viewsPath]);
$blade = new Factory($viewResolver, $viewFinder, $eventDispatcher);

// Variabel $blade sekarang siap dipake di file lain! ✨