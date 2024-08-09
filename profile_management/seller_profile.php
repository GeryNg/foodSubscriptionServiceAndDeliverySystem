<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Authentication - Seller Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .pull-right {
            margin-top: 10px;
            float: right !important;
        }
        .badge-status {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

    <?php
    $page_title = "User Authentication - Seller Profile";
    include_once '../resource/session.php';
    include_once '../partials/staff_nav.php';
    include_once '../partials/parseSellerProfile.php';

    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    
        $sqlQuery = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute(array(':id' => $id));
    
        while ($rs = $statement->fetch()) {
            $username = $rs['username'];
            $email = $rs['email'];
            $date_joined = (new DateTime($rs["join_date"]))->format('M d, Y');
            $avatar = $rs['avatar'];
        }
    
        $user_pic = !empty($avatar) ? $avatar : "../uploads/default.jpg";
    } else {

    }
    ?>

    <div class="container" style="margin-top:20px;">
        <div>
            <h1>Profile</h1>
            <?php if (!isset($_SESSION['username'])): ?>
            <p class="lead">You are not authorized to view this page <a href="../login_management/login.php">Login</a>
                Not yet a member? <a href="../login_management/singup.php">Signup</a></p>
            <?php else: ?>
                <section class="col col-lg-7">
                    <div class="row col-lg-3"  style="margin-bottom:10px;">
                        <img src="<?php echo $user_pic; ?>" alt="User Avatar" class="" width="200" >
                    </div>
                    <table class="table table-bordered table-condens">
                        <tr><th style="width:20%">Username: </th><td><?php if (isset($username))
                            echo $username; ?></td></tr>
                        <tr><th>Email: </th><td><?php if (isset($email))
                            echo $email; ?></td></tr>
                        <tr><th>Date Joined: </th><td><?php if (isset($date_joined))
                            echo $date_joined; ?></td></tr>
                        <tr><th></th><td><a class="pull-right" href="seller_edit_profile.php?user_identity=<?php if (isset($encode_id))
                            echo $encode_id; ?>>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            Edit Profile</a></td></tr>
                    </table>
                </section>

            <?php
            if (isset($access) && ($access === 'pending' || $access === 'verify')) {
                $sqlQuerySeller = "SELECT * FROM seller WHERE user_id = :user_id";
                $statementSeller = $db->prepare($sqlQuerySeller);
                $statementSeller->execute(array(':user_id' => $id));
                $sellers = $statementSeller->fetchAll();

                if ($sellers) {
                    echo '
                        <br/>
                        <hr/>
                        <br/>
                        <div style="display: flex; align-items: center;">
                            <h1>Basic Information</h1>
                                <span class="badge badge-status ' .
                                    ($access === 'pending' ? 'badge-warning' :
                                    ($access === 'inactive' ? 'badge-info' :
                                    ($access === 'rejected' ? 'badge-danger' :
                                    ($access === 'verify' ? 'badge-success' : '')))) .
                                '">' . ucfirst($access) . '</span>
                        </div>
                        <table class="table table-bordered">
                    <tbody>';
                    foreach ($sellers as $seller) {
                        echo '
                        <tr><th style="width: 20%;">Name</th><td>' . htmlspecialchars($seller['name']) . '</td></tr>
                        <tr><th style="width: 20%;">Detail</th><td>' . htmlspecialchars($seller['detail']) . '</td></tr>
                        <tr><th style="width: 20%;">Contact Number</th><td>' . htmlspecialchars($seller['contact_number']) . '</td></tr>
                        <tr><th style="width: 20%;">Address</th><td>' . htmlspecialchars($seller['address']) . '</td></tr>
                        <tr><th style="width: 20%;">Bank Account</th><td>' . htmlspecialchars($seller['bank_account']) . '</td></tr>
                        <tr><th style="width: 20%;">Actions</th><td><a href="seller_edit_information.php?id=' . htmlspecialchars($seller['id']) . '" class="btn btn-warning btn-sm">Edit</a></td></tr>';
                    }
                    echo '</tbody>
                  </table>';
                } else {
                    echo '<div class="alert alert-info" role="alert">No seller data available.</div>';
                }
            }
             endif 
             ?>
        </div>
    </div>
    <br />
    <br />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>
</html>
