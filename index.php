<?php
// 1. Panggil semua library dari Composer
require 'vendor/autoload.php';

// 2. Import kelas-kelas yang dibutuhin
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\View\Compilers\BladeCompiler;

// 3. Tentukan lokasi folder "panggung"
$viewsPath = __DIR__ . '/resources/views';
$cachePath = __DIR__ . '/cache';

// 4. Setup "mesin" Blade-nya
$filesystem = new Filesystem;
$eventDispatcher = new Dispatcher;

// Bikin resolver mesin view
$viewResolver = new EngineResolver;

// Bikin compiler Blade
$bladeCompiler = new BladeCompiler($filesystem, $cachePath);

// Daftarin Blade ke resolver
$viewResolver->register('blade', function () use ($bladeCompiler) {
    return new CompilerEngine($bladeCompiler);
});

// 5. Bikin "pabrik" view-nya
$viewFinder = new FileViewFinder($filesystem, [$viewsPath]);
$viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

// 6. Saatnya Manggung! Render view-nya
// Misal lo punya file 'salam.blade.php' di folder 'views'
echo $viewFactory->make('salam', [
    'nama' => 'Mike Ganteng', // Kirim data ke view
    'kampus' => 'Universitas Sumatera Utara'
])->render();
