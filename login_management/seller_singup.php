<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .main-container {
            margin-top:20px;
        }
        .form-signin {
            max-width: 500px;
            padding: 3rem 5rem 3rem 5rem;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .form-floating > .form-control, .form-floating > .form-control-plaintext {
            margin: 5px;
        }
        p {
            margin-bottom: 5px !important;
        }
    </style>
</head>
<body>
    <?php
    $page_title = "User Authentication - Seller Sign Up Page";
    include_once '../partials/headers.php';
    include_once '../partials/parseSellerSignup.php';
    ?>

    <div class="main-container">
        <main class="form-signin">
            <form method="post" action="">
                <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
                <h1 class="h3 mb-3 fw-normal">Come to Join Us</h1>

                <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
                <?php endif; ?>
                <div class="clearfix"></div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="email" value="" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="username" value="" placeholder="username">
                    <label for="floatingPassword">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password1" value="" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                 <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password2" value="" placeholder="Comfirm Password">
                    <label for="floatingPassword">Comfirm Password</label>
                </div>
                <br />
                <button class="btn btn-primary w-100 py-2" type="submit" name="signupBtn" value="Signup">Sign Up</button>
                <br />
                <hr />
                <p><a href="forgot_password.php">Forget Password</a></p>
                <p><a href="login.php">Already have an account? Login!</a></p>
                <p class="mt-5 mb-3 text-body-secondary">© 2024–2024</p>
            </form>
        </main>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>

</html>