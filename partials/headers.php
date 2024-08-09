<?php
include_once __DIR__ . '/../resource/session.php';
include_once __DIR__ . '/../resource/Database.php';
include_once __DIR__ . '/../resource/utilities.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <!--<link href="../css/custom.css" rel="stylesheet" />-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>
        <?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Default Title'; ?>
    </title>
    <style>
        .icon-link {
            color: #212427 !important;
        }
        .icon-link:hover {
            color: #f4623a !important;
            transition: color 0.3s;
        }
        .dtext-end {
            margin: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="../index.php">Makan Apa</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == '#' ? 'active' : ''; ?>" href="#">Plan</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == '#' ? 'active' : ''; ?>" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == '#' ? 'active' : ''; ?>" href="#">Delivery Area</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == 'add_plan.php' ? 'active' : ''; ?>" href="../plan_management/add_plan.php">SellerPage(temporary)</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo $current_page == '#' ? 'active' : ''; ?>" href="../customer_support/live_chat.php">Customer Service</a></li>
                </ul>
                <div class="dtext-end">
                    <a href="../profile_management/profile.php" class="d-block link-body-emphasis text-decoration-none icon-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="font-size: 30px;">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"></path>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <br />
    <br />
    <br />

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Other JS scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>
</html>
