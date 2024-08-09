<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .pull-right {
            float: right !important;
        }
        .btn {
            margin-top:10px;
        }
    </style>
</head>
<body>

    <?php
    $page_title = "User Authentication - Edit Profile";
    include_once "../partials/staff_nav.php";
    include_once "../partials/parseSellerProfile.php";
    ?>

    <div class="container" style="margin-top:20px;">
        <section class="col col-lg-7">
            <h2>Edit basic information</h2>

            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?php if(!isset($_SESSION['username'])): ?>
                <p class="lead">You are not authorized to view this page. <a href="login.php">Login</a>
                    Not yet a member? <a href="../login_management/signup.php">Signup</a></p>
            <?php else: ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="emailField">Email</label>
                        <input type="text" name="email" class="form-control" id="emailField" value="<?php if(isset($email)) echo $email ?>" />
                    </div>

                    <div class="form-group">
                        <label for="usernameField">Username</label>
                        <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>" class="form-control" id="usernameField"  />
                    </div>

                    <div class="form-group">
                        <label for="fileField">Avatar</label>
                        <input type="file" name="avatar" id="filefield"  />
                    </div>

                    <input type="hidden" name="hidden_id" value="<?php if(isset($id))echo $id; ?>"/>
                    <button type="submit" name="updateProfileBtn" class="btn btn-primary pull-right">Update Profiles</button>
                </form>
            <?php endif ?>
        </section>
        <br />
        <br />
        <br />
        <br />
        <p><a href="seller_profile.php">Back</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>
</html>
