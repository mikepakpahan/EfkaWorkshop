<?php
require_once 'config.php';
header('Content-Type: application/json');

// Keamanan: Wajib login untuk mengirim rating
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk memberi rating.']);
    exit;
}

// Pastikan method adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $history_id = isset($_POST['history_id']) ? intval($_POST['history_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Validasi input
    if (empty($history_id) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid. Mohon pilih rating bintang.']);
        exit;
    }

    // Gunakan transaction untuk menjaga integritas data
    $conn->begin_transaction();
    try {
        // 1. Masukkan ulasan ke tabel 'reviews'
        $stmt_insert = $conn->prepare("INSERT INTO reviews (user_id, history_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiis", $user_id, $history_id, $rating, $comment);
        $stmt_insert->execute();

        // 2. Tandai transaksi di tabel 'history' sebagai sudah dirating
        $stmt_update = $conn->prepare("UPDATE history SET is_rated = 1 WHERE id = ? AND user_id = ?");
        $stmt_update->bind_param("ii", $history_id, $user_id);
        $stmt_update->execute();

        // Jika semua query berhasil, simpan perubahan
        $conn->commit();
        
        // --- INI BAGIAN YANG DIPERBAIKI ---
        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        // Jika ada yang gagal, batalkan semua perubahan
        $conn->rollback();
        
        // Cek jika error karena duplicate entry (sudah pernah rating)
        if($conn->errno === 1062){
             echo json_encode(['status' => 'error', 'message' => 'Anda sudah pernah memberi ulasan untuk transaksi ini.']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()]);
        }
    }
    
    $stmt_insert->close();
    $stmt_update->close();
    $conn->close();
    exit;
}
?>