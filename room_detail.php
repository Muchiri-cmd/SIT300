<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<?php
include 'inc/header.php';
require_once 'config/database.php';

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    $sql = "SELECT * FROM rooms WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $room_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $room = mysqli_fetch_assoc($result);
    } else {
        die("Room not found.");
    }
} else {
    die("Room ID not provided.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (strtotime($check_in) < strtotime($check_out)) {
        $query = "SELECT * FROM bookings WHERE room_id = ? AND (check_in <= ? AND check_out >= ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $room_id, $check_out, $check_in);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo "Sorry, this room is already booked for the selected dates.";
        } else {
            $insert_query = "INSERT INTO bookings (user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $room_id, $check_in, $check_out);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "Room booked successfully!";
            } else {
                echo "Error booking room: " . mysqli_stmt_error($stmt);
            }
        }
    } else {
        echo "Invalid check-out date!";
    }
}
?>


<div class="container py-5">
    <div class="row">
       
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <img src="<?php echo $room['image']; ?>" class="card-img-top" alt="Room Image" style="object-fit: cover; height: 100%; width: 100%;">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    
                    <p class="card-text"><?php echo $room['description']; ?></p>
                    <p class="lead text-muted">Price: Kes <?php echo number_format($room['price'], 2); ?> / night</p>

                    <form action="process_booking.php" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <div class="mb-3">
                            <label for="check_in" class="form-label">Check-in Date</label>
                            <input type="date" class="form-control" id="check_in" name="check_in" required>
                        </div>
                        <div class="mb-3">
                            <label for="check_out" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="check_out" name="check_out" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Book Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'inc/footer.php'; ?>
