<?php include 'inc/header.php'; ?>
<?php
    require_once 'config/database.php';

    $sql = "SELECT * FROM rooms";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching rooms: " . mysqli_error($conn));
    }
    
    // Fetch all rooms as an associative array
    $rooms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }
    ?>
?>
    <link rel="stylesheet" href="styles/rooms.css"/>
    <div class="banner">
        <img src="./assets/banner.png" alt="">
    </div>
    <div class="container">
        <?php foreach ($rooms as $room):?>
            <div class="room">
            <div class="overlay">
                <a href=""><i class="fas fa-eye"></i></a>
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
                <button class="book">Enquire</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php include 'inc/footer.php'; ?>