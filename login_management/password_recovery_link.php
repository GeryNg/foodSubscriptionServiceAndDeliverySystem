<?php
$page_title = "User Authentication - Password Recovery";
include_once '../partials/headers.php';
?>

<!DOCTYPE HTML>
<html>

<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .main-container {
            margin-top: 20px;
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

        .form-floating>.form-control,
        .form-floating>.form-control-plaintext {
            margin: 5px;
        }

        .form-floating>.form-select {
            margin: 5px;
            height: calc(3.5rem + 2px);
        }

        p {
            margin-bottom: 5px !important;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <main class="form-signin">
            <form method="post" action="send_password_reset.php">
                <img class="mb-4" src="../image/logo-rounded.png" alt="Logo" width="80" height="80">
                <h1 class="h3 mb-3 fw-normal">Password Revovery</h1>
                <?php if (isset($result) || !empty($form_errors)): ?>
                    <div>
                        <?php echo show_combined_messages($result, $form_errors); ?>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="emailField" value="" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <br />
                <button class="btn btn-primary w-100 py-2" type="submit" name="resetPassword" value="RecoverPassword">Recover Password</button>
                <br />
                <hr />
                <p><a href="login.php">Already have an account? Login!</a></p>
                <p class="mt-5 mb-3 text-body-secondary">© 2024-2024</p>
            </form>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>

</html>

<?php include_once '../partials/footer.php'; ?>