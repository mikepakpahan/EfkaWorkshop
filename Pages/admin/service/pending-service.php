<?php

$pageTitle = 'Pending Service';
$activeMenu = 'pending';

include '../template-header.php';
include '../template-sidebar.php';
?>

<link rel="stylesheet" href="../style.css">
<style>/* Styling untuk container tabel */
    .table-container {
        background-color: #FFFFFF;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Styling dasar tabel */
    .pending-table {
        width: 100%;
        border-collapse: collapse; /* Menghilangkan spasi antar border */
        font-size: 0.9rem;
    }

    /* Header tabel */
    .pending-table thead th {
        text-align: left;
        padding: 12px 16px;
        border-bottom: 2px solid #E5E7EB; /* Garis bawah tebal untuk header */
        background-color: #F9FAFB;
        font-weight: 600;
    }

    /* Sel data tabel */
    .pending-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #F3F4F6; /* Garis bawah tipis antar baris */
    }

    /* Warna baris selang-seling */
    .pending-table tbody tr.alternate-row {
        background-color: #F9FAFB;
    }

    /* Agar deskripsi tidak terlalu panjang */
    .description-cell {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        background-color: #FFC20E; /* Kuning sesuai screenshot, bisa diganti ke warna merah seperti #EF4444 */
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
                    <th>Type of Motorbike</th>
                    <th>Date</th>
                    <th>Deskripsi</th>
                    <th>Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>M. Rifky Kembaren</td>
                    <td>M.RifkyKembaren@gmail.com</td>
                    <td>Stylo 160</td>
                    <td>13/05/2025</td>
                    <td class="description-cell">XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX</td>
                    <td class="action-cell">
                        <button class="btn btn-accept">Accept</button>
                        <button class="btn btn-reject">Reject</button>
                    </td>
                </tr>
                <tr class="alternate-row">
                    <td>2</td>
                    <td>Michael Babista Pakpahan</td>
                    <td>michael@gmail.com</td>
                    <td>ZX 10</td>
                    <td>9/05/2025</td>
                    <td class="description-cell">XXXXXXXXXXXXXXXXXXXXXXXX XXXXXXXXXXXXXXXXXXXXXXXX</td>
                    <td class="action-cell">
                        <button class="btn btn-accept">Accept</button>
                        <button class="btn btn-reject">Reject</button>
                    </td>
                </tr>
                </tbody>
        </table>
    </div>
</main>

<script src="../script.js"></script>