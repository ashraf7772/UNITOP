<?php
session_start();

require_once('php/connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id']; 
    $new_stock_level = $_POST['new_stock_level'];

    $query = "UPDATE products SET stock = :stock WHERE product_id = :product_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':stock', $new_stock_level, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);


    if ($statement->execute()) {
        // If the stock updates, redirect to admin_dashboard.php
        header("Location: admin_dashboard.php?success=Stock%20has%20changed");
        exit;
    } else {
        // If there's an error during the stock update, set the error message
        $error_message = "Failed to update stock level.";
        // Redirect to admin_dashboard.php with error message added to the URL
        header("Location: admin_dashboard.php?error=" . urlencode($error_message));
        exit;
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>
