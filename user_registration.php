<?php
session_start();
require 'require/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$firstname, $lastname, $email, $password])) {
        $_SESSION['message'] = "Registration successful! You can now log in.";
        header("Location: user_login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register</title>
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
        <a href="no_acc_index.php" class="navbar-brand bg-primary d-flex align-items-center px-4 px-lg-5">
            <img src="img/ssgv_booking_logo.png" alt="SSGV" style="max-height: 100px;">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="no_acc_index.php" class="nav-item nav-link">Home</a>
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="#services" class="nav-item nav-link">Amenities & Services</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"></a>
                    <div class="dropdown-menu fade-up m-0">
                        <a href="user_login.php" class="dropdown-item">Login</a>

                        <a href="team.html" class="dropdown-item">Settings</a>
                        <a href="testimonial.html" class="dropdown-item">Support</a>
                    </div>
                </div>
                <a href="user_login.php" class="nav-item nav-link">Book Now</a>
            </div>
            
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <!-- Carousel End -->


    <!-- About Start -->
    <!-- About End -->


    <!-- Fact Start -->
    <!-- Fact End -->


    <!-- Service Start -->
    <!-- Service End -->


    <!-- Feature Start -->
    
    <!-- Feature End -->


    <!-- Pricing Start -->
    <!-- Pricing End -->


    <!-- Quote Start -->
    <div class="container-xxl py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="text-secondary text-uppercase mb-3">Siquijor Sunset Glamping Village Inc.</h6>
                    <h1 class="mb-5">Create an Account</h1>
                    
                    <div class="d-flex align-items-center">
                        <i class="fa fa-headphones fa-2x flex-shrink-0 bg-primary p-3 text-white"></i>
                        <div class="ps-4">
                            <h6>Already have an accout?</h6>
                            <p class="text-primary m-0"><a href="user_login.php">Login here</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="bg-light text-center p-5 wow fadeIn" data-wow-delay="0.5s">
                        <center><img src="images/ssgv_booking_logo.png" alt="" style="max-width: 50%;"></center>
                        <form action="" method="post">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <input type="text" id="firstname" name="firstname" class="form-control border-0" placeholder="Your Firstame" style="height: 55px;" oninput="capitalizeFirstLetter(this)" required>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" id="firstname" name="lastname"  class="form-control border-0" placeholder="Your Lastame" style="height: 55px;" oninput="capitalizeFirstLetter(this)" required>
                                </div>
                                <div class="col-12">
                                    <input type="email" id="email" name="email" class="form-control border-0" placeholder="Enter your Email" style="height: 55px;" required>
                                </div>
                                <div class="col-12">
                                    <input type="password" id="password" name="password" class="form-control border-0" placeholder="Enter your Password" style="height: 55px;" required>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quote End -->


    <!-- Team Start -->

    <!-- Team End -->
    <script>
        function capitalizeFirstLetter(input) {
            const value = input.value;

            // Capitalize the first letter and concatenate with the rest of the string
            if (value.length > 0) {
                input.value = value.charAt(0).toUpperCase() + value.slice(1);
            }
        }
    </script>

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
                    <h4 class="text-light mb-4">Amenities</h4>
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
