<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (strtotime($check_in) >= strtotime($check_out)) {
        echo "Check-out date must be after the check-in date.";
        exit();
    }

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$user_id) {
        echo "You must be logged in to make a booking.";
        exit();
    }

    $sql = "SELECT * FROM bookings WHERE room_id = ? AND (
                (check_in_date BETWEEN ? AND ?) OR
                (checkout_date BETWEEN ? AND ?)
            )";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issss", $room_id, $check_in, $check_out, $check_in, $check_out);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
  
    if (mysqli_num_rows($result) > 0) {
        echo "Sorry, the room is already booked for the selected dates.";
        mysqli_free_result($result);
        exit();
    }

    mysqli_free_result($result);

    $sql = "INSERT INTO bookings (user_id, room_id, check_in_date, checkout_date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiss", $user_id, $room_id, $check_in, $check_out);
    $is_booked = mysqli_stmt_execute($stmt);

    if ($is_booked) {
        echo "<script type='text/javascript'>
                alert('Booking successful! You can proceed to your booking details.');
                window.location.href = 'rooms.php';
              </script>";
        exit();
    } else {
        echo "There was an error processing your booking. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
