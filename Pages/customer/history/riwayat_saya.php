<?php
require '../../../backend/config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /EfkaWorkshop/Pages/login/login-page.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$activePage = 'history';

// --- JURUS SQL BARU DENGAN UNION ---
// Query ini akan menggabungkan data dari 'orders' (yang masih aktif)
// dan 'history' (yang sudah selesai) menjadi satu daftar.
$sql = "(
    SELECT 
        id, 
        'processing' AS status,
        'sparepart' AS transaction_type,
        NULL AS description,
        total_amount AS final_price,
        order_date AS transaction_date,
        0 AS is_rated
    FROM orders
    WHERE user_id = ? AND status = 'processing'
)
UNION ALL
(
    SELECT 
        id, 
        'completed' AS status,
        transaction_type,
        description,
        final_price,
        completion_date AS transaction_date,
        is_rated
    FROM history
    WHERE user_id = ?
)
ORDER BY transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Transaksi - EFKA Workshop</title>
    <?php include '../header.php'; ?>
    <style>
        /* ... (CSS lama Anda tetap di sini) ... */
        .history-container { max-width: 900px; margin: 2rem auto; padding: 2rem; }
        .history-card { /* ... */ }
        
        /* Tambahkan style untuk status 'processing' */
        .history-card.status-processing {
            border-left: 5px solid #ffc107; /* Oranye untuk order aktif */
        }
        .btn-view-qr {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="history-container">
    <h1 style="text-align: center; margin-bottom: 2rem;">Riwayat Transaksi Anda</h1>
    
    <?php if (!empty($transactions)): ?>
        <?php foreach ($transactions as $item): ?>
            <?php
            // Logika untuk menentukan tampilan berdasarkan status
            $is_processing = ($item['status'] === 'processing');
            $card_class = $is_processing ? 'status-processing' : '';
            
            $icon = ($item['transaction_type'] === 'service') ? '<i class="fas fa-wrench icon"></i>' : '<i class="fas fa-box-open icon"></i>';
            $title = $is_processing ? 'Pesanan Sparepart (Menunggu Pembayaran)' : (($item['transaction_type'] === 'service') ? 'Riwayat Servis' : 'Riwayat Pembelian Sparepart');
            
            // Jika deskripsi kosong (dari tabel orders), buat deskripsi default
            $description = $item['description'] ?? 'Detail pesanan ada di dalam tiket QR.';
            ?>

            <div class="history-card <?php echo $card_class; ?>">
                <div class="history-header">
                    <div class="history-header-info"><?php echo $icon; ?> <span><?php echo $title; ?></span></div>
                    <div class="history-date"><?php echo date('d F Y', strtotime($item['transaction_date'])); ?></div>
                </div>
                <div class="history-body">
                    <p class="description"><?php echo htmlspecialchars($description); ?></p>
                </div>
                <div class="history-footer">
                    <span class="total-price">Total: Rp <?php echo number_format($item['final_price'], 0, ',', '.'); ?></span>
                    
                    <?php if ($is_processing): ?>
                        <a href="/EfkaWorkshop/order_success.php?order_id=<?php echo $item['id']; ?>" class="btn-view-qr">Lihat Tiket QR</a>
                    <?php else: ?>
                        <?php if ($item['is_rated'] == 0): ?>
                            <button class="btn-rate" data-historyid="<?php echo $item['id']; ?>">Beri Ulasan</button>
                        <?php else: ?>
                            <span class="rated-badge">Sudah Diulas</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style='text-align:center;'>Anda belum memiliki riwayat transaksi.</p>
    <?php endif; ?>
</div>

<?php
    include '../footer.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const isLoggedIn = <?php echo json_encode($is_logged_in); ?>;

    // --- SCRIPT UTAMA: HANYA SATU BLOK INI ---

    if (!isLoggedIn) {
        // Jika tidak login, tampilkan popup dan arahkan ke halaman lain
        Swal.fire({
            title: 'Akses Dibatasi',
            text: "Anda harus login untuk melihat halaman ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FFC72C',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/EfkaWorkshop/Pages/login/login-page.php';
            } else {
                window.location.href = '/EfkaWorkshop/index.php';
            }
        });
    } else {
        // Jika login, baru kita pasang listener untuk tombol rating
        const historyContainer = document.querySelector('.history-container');
        if (historyContainer) {
            historyContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-rate')) {
                    const historyId = event.target.dataset.historyid;
                    
                    Swal.fire({
                        title: 'Beri Ulasan & Rating',
                        html: `
                            <div class="rating-stars">
                                <span class="star" data-value="1">&#9733;</span>
                                <span class="star" data-value="2">&#9733;</span>
                                <span class="star" data-value="3">&#9733;</span>
                                <span class="star" data-value="4">&#9733;</span>
                                <span class="star" data-value="5">&#9733;</span>
                            </div>
                            <input type="hidden" id="swal-rating" value="0">
                            <textarea id="swal-comment" class="swal2-textarea" placeholder="Tulis komentarmu di sini... (opsional)"></textarea>
                        `,
                        confirmButtonText: 'Kirim Ulasan',
                        confirmButtonColor: '#FFC72C',
                        didOpen: () => {
                            const stars = document.querySelectorAll('.rating-stars .star');
                            stars.forEach(star => {
                                star.addEventListener('click', () => {
                                    const value = parseInt(star.dataset.value);
                                    document.getElementById('swal-rating').value = value;
                                    stars.forEach((s, i) => { s.classList.toggle('selected', i < value); });
                                });
                            });
                        },
                        preConfirm: () => {
                            const rating = document.getElementById('swal-rating').value;
                            if (rating == 0) {
                                Swal.showValidationMessage('Mohon pilih minimal 1 bintang rating');
                            }
                            return {
                                rating: rating,
                                comment: document.getElementById('swal-comment').value
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('history_id', historyId);
                            formData.append('rating', result.value.rating);
                            formData.append('comment', result.value.comment);

                            fetch('/EfkaWorkshop/backend/proses_rating.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.status === 'success'){
                                    Swal.fire('Terkirim!', 'Terima kasih atas ulasan Anda.', 'success').then(() => window.location.reload());
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                }
                            });
                        }
                    });
                }
            });
        }
    }
});
</script>

</body>
</html>