<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Debug .env File</title>
    <style>
        body { font-family: monospace; background-color: #333; color: #f2f2f2; padding: 20px; }
        .result { background-color: #444; border: 1px solid #555; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .result h3 { margin-top: 0; border-bottom: 1px solid #666; padding-bottom: 5px; }
        .pass { color: #28a745; font-weight: bold; }
        .fail { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Detektif untuk File .env</h1>

    <div class="result">
        <h3>1. Path yang Dicari oleh PHP:</h3>
        <?php
            // Ini adalah path tempat PHP mencari file .env
            $dotenv_path = __DIR__ . '/..';
            echo "<p>PHP mencari file .env di dalam folder: <br><strong>" . $dotenv_path . "</strong></p>";
        ?>
    </div>

    <div class="result">
        <h3>2. Apakah File .env Ditemukan?</h3>
        <?php
            $env_file_path = $dotenv_path . '/.env';
            if (file_exists($env_file_path)) {
                echo "<p class='pass'>YA, file .env ditemukan di lokasi yang benar!</p>";
            } else {
                echo "<p class='fail'>TIDAK, file .env TIDAK ditemukan di lokasi tersebut. Ini adalah sumber masalahnya.</p>";
            }
        ?>
    </div>

    <div class="result">
        <h3>3. Apakah File .env Bisa Dibaca?</h3>
         <?php
            if (is_readable($env_file_path)) {
                echo "<p class='pass'>YA, file .env bisa dibaca oleh server.</p>";
            } else {
                echo "<p class='fail'>TIDAK, file .env ditemukan tapi TIDAK BISA dibaca. Cek masalah permission file.</p>";
            }
        ?>
    </div>

    <div class="result">
        <h3>4. Daftar Semua File di Folder Proyek:</h3>
        <p>Periksa daftar ini dengan teliti. Apakah nama filenya persis '.env' atau ada tambahan '.txt' di belakangnya?</p>
        <pre><?php print_r(scandir($dotenv_path)); ?></pre>
    </div>

</body>
</html>