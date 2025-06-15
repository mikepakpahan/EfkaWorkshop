<?php

require '../../../backend/config.php';

$pageTitle = 'Pending Service';
$activeMenu = 'pending';

include '../template-header.php'; 
include '../template-sidebar.php';

$sql = "SELECT 
            sb.id, 
            u.name, 
            u.email, 
            sb.motor_type, 
            sb.booking_date, 
            sb.complaint 
        FROM service_bookings sb
        JOIN users u ON sb.user_id = u.id
        WHERE sb.status = 'pending'
        ORDER BY sb.booking_date ASC";

$result = $conn->query($sql);

?>

<link rel="stylesheet" href="../style.css">

<style>
    /* ... (Semua CSS Anda sebelumnya tetap di sini) ... */

    /* Styling untuk container tabel */
    .table-container {
        background-color: #FFFFFF;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Styling dasar tabel */
    .pending-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    /* Header tabel */
    .pending-table thead th {
        text-align: left;
        padding: 12px 16px;
        border-bottom: 2px solid #E5E7EB;
        background-color: #F9FAFB;
        font-weight: 600;
    }

    /* Sel data tabel */
    .pending-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #F3F4F6;
    }

    /* Warna baris selang-seling */
    .pending-table tbody tr:nth-child(even) {
        background-color: #F9FAFB;
    }

    /* Mengubah CSS untuk deskripsi agar bisa text-wrap */
    .description-cell {
        max-width: 300px; /* Lebar maksimal kolom deskripsi */
        white-space: normal; /* Memungkinkan teks untuk turun (wrap) */
        word-break: break-word; /* Memaksa teks panjang tanpa spasi untuk patah */
    }

    /* Styling untuk tombol di dalam tabel */
    .action-cell {
        display: flex;
        gap: 8px;
    }

    .btn-accept, .btn-reject {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.8rem;
        color: #1F2937;
    }

    .btn-accept {
        background-color: #FFC20E; /* Kuning */
    }

    .btn-reject {
        background-color: #EF4444; /* Merah */
    }
</style>

<main class="main-content">
    <div class="table-container">
        <table class="pending-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tipe Motor</th>
                    <th>Tanggal Booking</th>
                    <th>Keluhan/Deskripsi</th>
                    <th>Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Cek jika ada data yang ditemukan
                if ($result && $result->num_rows > 0) {
                    $counter = 1; // Variabel untuk nomor urut
                    // Looping untuk setiap baris data
                    while($row = $result->fetch_assoc()) {
                        // Format tanggal dari YYYY-MM-DD menjadi DD/MM/YYYY
                        $formatted_date = date('d/m/Y', strtotime($row['booking_date']));
                        
                        echo "<tr>";
                        echo "<td>" . $counter . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['motor_type']) . "</td>";
                        echo "<td>" . $formatted_date . "</td>";
                        echo "<td class='description-cell'>" . htmlspecialchars($row['complaint']) . "</td>";
                        echo "<td class='action-cell'>
                                <button class='btn btn-accept' data-booking-id='" . $row['id'] . "'>Accept</button>
                                <button class='btn btn-reject' data-booking-id='" . $row['id'] . "'>Reject</button>
                              </td>";
                        echo "</tr>";
                        $counter++;
                    }
                } else {
                    // Tampilkan pesan jika tidak ada data pending
                    echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>Tidak ada service yang sedang pending.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<script src="../script.js"></script>