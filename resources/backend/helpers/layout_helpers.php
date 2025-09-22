<?php

function getSharedLayoutData($conn)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // --- LOGIKA MENGHITUNG CART ---
    $cart_count = 0;
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $user_id = $_SESSION['user_id'];
        $sql_count = 'SELECT COUNT(id) AS total_items FROM carts WHERE user_id = ?';
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param('i', $user_id);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        if ($result_count) {
            $row_count = $result_count->fetch_assoc();
            $cart_count = $row_count['total_items'];
        }
    }

    // --- LOGIKA MENGHITUNG RATING ---
    $average_rating = 0;
    $total_reviews = 0;
    $sql_rating = 'SELECT AVG(rating) as avg_rating, COUNT(id) as total_reviews FROM reviews';
    $result_rating = $conn->query($sql_rating);
    if ($result_rating && $result_rating->num_rows > 0) {
        $rating_data = $result_rating->fetch_assoc();
        $average_rating = number_format($rating_data['avg_rating'] ?? 0, 1);
        $total_reviews = $rating_data['total_reviews'] ?? 0;
    }

    return [
        'cart_count' => $cart_count,
        'average_rating' => $average_rating,
        'total_reviews' => $total_reviews,
    ];
}
