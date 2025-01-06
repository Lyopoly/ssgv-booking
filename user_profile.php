<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: no_acc_index.php");
    exit();
}

require 'require/db.php';

try {
    // Prepare the SQL query to fetch user's booking requests
    $stmt = $pdo->prepare("SELECT * FROM tents WHERE uid = :uid ORDER BY updated_at DESC");
    $stmt->bindParam(':uid', $_SESSION['user_id'], PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all results
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>



<?php
// Include the database connection file
require 'require/db.php';

// Get today's date
$today = date('Y-m-d');

// Query to select pending bookings that need to be cancelled
$sql = "SELECT tent_id, tent_category, checkin, checkout, number_of_days, to_pay, lastname, firstname, uid, email, phone 
        FROM tents 
        WHERE checkout = :checkout AND status = 'pending'";

$stmt = $pdo->prepare($sql);
$stmt->execute(['checkout' => $today]);

// Fetch all results
$results = $stmt->fetchAll();

foreach ($results as $row) {
    // Prepare the insert statement for the cancelled table
    $insertSql = "INSERT INTO expired_bookings (tent_id, tent_category, checkin, checkout, number_of_days, to_pay, lastname, firstname, uid, email, phone) 
                   VALUES (:tent_id, :tent_category, :checkin, :checkout, :number_of_days, :to_pay, :lastname, :firstname, :uid, :email, :phone)";
    
    $insertStmt = $pdo->prepare($insertSql);
    
    // Execute the insert statement
    $insertStmt->execute([
        'tent_id' => $row['tent_id'],
        'tent_category' => $row['tent_category'],
        'checkin' => $row['checkin'],
        'checkout' => $row['checkout'],
        'number_of_days' => $row['number_of_days'],
        'to_pay' => $row['to_pay'],
        'lastname' => $row['lastname'],
        'firstname' => $row['firstname'],
        'uid' => $row['uid'],
        'email' => $row['email'],
        'phone' => $row['phone']
    ]);

    // Prepare the update statement for the tents table
    $updateSql = "UPDATE tents SET checkin = NULL, checkout = NULL, number_of_days = NULL, to_pay = NULL, lastname = NULL, firstname = NULL, uid = NULL, email = NULL, phone = NULL, booked = FALSE WHERE tent_id = :tent_id";
    
    $updateStmt = $pdo->prepare($updateSql);
    
    // Execute the update statement
    $updateStmt->execute(['tent_id' => $row['tent_id']]);
}

// Close the connection (optional, as it will close automatically at the end of the script)
$pdo = null;
?>


<?php
require 'require/db.php';

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Step 1: Insert data into checked_outs table
    $insertQuery = "
        INSERT INTO checked_outs (tent_id, tent_category, checkin, checkout, number_of_days, per_night, to_pay, lastname, firstname, uid, email, phone)
        SELECT tent_id, tent_category, checkin, checkout, number_of_days, per_night, to_pay, lastname, firstname, uid, email, phone
        FROM tents
        WHERE status = 'paid'
    ";
    $pdo->exec($insertQuery);

    // Step 2: Update the tents table
    $updateQuery = "
        UPDATE tents
        SET checkin = NULL,
            checkout = NULL,
            number_of_days = NULL,
            per_night = NULL,
            to_pay = NULL,
            lastname = NULL,
            firstname = NULL,
            uid = NULL,
            email = NULL,
            phone = NULL,
            booked = FALSE,
            status = 'pending'
        WHERE status = 'paid'
    ";
    $pdo->exec($updateQuery);

    // Commit the transaction
    $pdo->commit();
    
} catch (Exception $e) {
    // Rollback the transaction if something failed
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile</title>
    <link rel="icon" href="images/ssgv_booking_logo.png" type="image/png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow border-top border-5 border-primary sticky-top p-0">
        <a href="index.php" class="navbar-brand bg-primary d-flex align-items-center px-4 px-lg-5">
            <img src="img/ssgv_booking_logo.png" alt="SSGV" style="max-height: 100px;">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="index.php#about" class="nav-item nav-link">About</a>
                <a href="index.php#services" class="nav-item nav-link">Amenities & Services</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"></a>
                    <div class="dropdown-menu fade-up m-0">
                        <a href="user_profile.php" class="dropdown-item">Your Profile</a>
                        <a href="news_and_notifs.php" class="dropdown-item">Announcements</a>
   
                        <a href="" class="dropdown-item">Settings</a>
                        <a href="" class="dropdown-item">Support</a>
                        <a href="user_logout.php" class="dropdown-item">Logout</a>
                    </div>
                </div>
                <a href="booknow.php" class="nav-item nav-link">Book Now</a>
            </div>
            
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->

    <!-- Carousel End -->


    <!-- About Start -->

    <!-- About End -->


    <!-- Fact Start -->
    <div class="container-xxl py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="text-secondary text-uppercase mb-3">Hello,</h6>
                    <h1 class="mb-5"><?php echo htmlspecialchars($_SESSION['lastname']); ?>, <?php echo htmlspecialchars($_SESSION['firstname']); ?></h1>
                    <p class="mb-5">This is your profile page. This is where you can see your previous Booking Requests</p>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-headphones fa-2x flex-shrink-0 bg-primary p-3 text-white"></i>
                        <div class="ps-4">
                            <h3 class="text-primary m-0">Your Previous Booking Requests</h3>
                        </div>
                    </div>
                </div>
                <?php foreach ($bookings as $row): ?>
                    <div class="col-lg-11">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-6">
                                <div class="bg-light p-4 wow fadeIn" data-wow-delay="0.5s" style="margin: 2px;">
                                    <h2 class="text-black mb-2">Requested at: <?php echo htmlspecialchars($row['updated_at']); ?></h2>
                                    <p class="text-black mb-0">Tent Category: <?php echo htmlspecialchars($row['tent_category']); ?><br>Checkin Date: <?php echo htmlspecialchars($row['checkin']); ?><br>Checkout Date:<?php echo htmlspecialchars($row['checkout']); ?><br> To pay: &#8369;<?php echo htmlspecialchars($row['to_pay']); ?></p>
                                    <form action="cancel_booking.php" method="POST">
                                            <input type="hidden" name="tent_id" value="<?php echo $row['tent_id']; ?>">
                                            <input type="submit" class="btn-slide mt-2" value="Cancel booking" style="background-color: transparent; border:0px;color: red;">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                

                
            </div>
        </div>
    </div>
    <!-- Fact End -->


    <!-- Service Start -->
    <!-- Service End -->


    <!-- Feature Start -->
    
    <!-- Feature End -->


    <!-- Pricing Start -->
    
    <!-- Pricing End -->


    <!-- Quote Start -->
    
    <!-- Quote End -->


    <!-- Team Start -->
    <!-- Team End -->


    <!-- Testimonial Start -->
    
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 wow fadeIn" data-wow-delay="0.1s" style="margin-top: 6rem;">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Address</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Solangon, San Juan Siquijor</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0977 654 2030</p>
                    <p class="mb-2" style ="font-size:90%;"><i class="fa fa-envelope me-3"></i>siquijorsunsetglampingvillage@gmail.com</p>
                    <div class="d-flex pt-2">
                
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Services</h4>
                    <a class="btn btn-link" href="">Bitaog Restaurant</a>
                    <a class="btn btn-link" href="">SSGV Fitness Gym</a>
                    <a class="btn btn-link" href="">Glamping Tents</a>
                    <a class="btn btn-link" href="">Nightly Accoustics</a>
                    <a class="btn btn-link" href="">Kayaks & Paddleboards</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Quick Links</h4>
                    <a class="btn btn-link" href="">Profile</a>
                    <a class="btn btn-link" href="">News & Notifications</a>
                    <a class="btn btn-link" href="">Terms & Conditions</a>
                    <a class="btn btn-link" href="">Settings</a>
                    <a class="btn btn-link" href="">Support</a>
                </div>
                
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">SSGV Booking</a>, All Right Reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-0 back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
