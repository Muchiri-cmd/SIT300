<?php
    include 'inc/header.php';
    require_once 'config/database.php';

    $check_in = isset($_GET['check_in']) ? $_GET['check_in'] : null;
    $check_out = isset($_GET['check_out']) ? $_GET['check_out'] : null;

    $sql = "SELECT * FROM rooms";

    if ($check_in && $check_out) {
        if (strtotime($check_in) >= strtotime($check_out)) {
            echo "Check-out date must be after the check-in date.";
            exit();
        }
        $sql = "SELECT * FROM rooms WHERE id NOT IN (
                    SELECT room_id FROM bookings WHERE (
                        (check_in_date BETWEEN ? AND ?) OR
                        (checkout_date BETWEEN ? AND ?)
                    )
                )";
    }
    $stmt = mysqli_prepare($conn, $sql);

    if ($check_in && $check_out) {
        mysqli_stmt_bind_param($stmt, "ssss", $check_in, $check_out, $check_in, $check_out);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        die("Error fetching rooms: " . mysqli_error($conn));
    }

    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
?>
<link rel="stylesheet" href="styles/rooms.css"/>
<div class="banner">
    <img src="./assets/banner.png" alt="">
</div>
<div class="filter">
    <form method="GET" action="rooms.php">
        <label for="check_in">Check-in Date:</label>
        <input type="date" id="check_in" name="check_in" required>

        <label for="check_out">Check-out Date:</label>
        <input type="date" id="check_out" name="check_out" required>

        <button type="submit" class="availability">Check Availability</button>
    </form>
</div>

<div class="container">
    <?php if (empty($rooms)): ?>
        <p>No rooms are available for the selected dates.</p>
    <?php else: ?>
        <?php foreach ($rooms as $room): ?>
            <div class="room">
                <div class="overlay">
                    <a href="room_detail.php?room_id=<?php echo $room['id']; ?>"><i class="fas fa-eye"></i></a>
                </div>
                <img src="<?php echo $room['image'];?>" alt="<?php echo $room['image'];?>">
              
                <div class="icons">
                    <p class="icon"><i class="fa-solid fa-star"></i></span></p>
                    <p class="icon"><i class="fa-solid fa-star"></i></span></p>
                    <p class="icon"><i class="fa-solid fa-star"></i></span></p>
                    <p class="icon"><i class="fa-solid fa-star"></i></span></p>
                    <p class="icon"><i class="fa-solid fa-star-half-stroke"></i></p>
                </div>
                <div class="text">
                    <p><?php echo $room['description'];?></p>
                    <div class="complimentaries">
                        <span><i class="fa-solid fa-mug-saucer"></i></span>
                        <span><i class="fa-solid fa-wifi"></i></span>
                        <span><i class="fa-solid fa-utensils"></i></span>
                        <span><i class="fa-solid fa-tv"></i></span>
                    </div>
                    <div class="line"></div>
                    <p>Kes <?php echo number_format($room['price'], 2); ?> / night </p>
                    <a href="room_detail.php?room_id=<?php echo $room['id']; ?>">
                        <button class="book">Book</button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'inc/footer.php'; ?>
