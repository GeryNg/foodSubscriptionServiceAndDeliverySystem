<!DOCTYPE HTML>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        .pull-right {
            float: right !important;
        }

        .btn {
            margin-top: 10px
        }
    </style>
</head>
<body>
    <?php
    $page_title = "User Authentication - Password Recovery";
    include_once '../partials/headers.php';
    include_once '../partials/parseForgot_password.php';
    ?>

    <div class="container">
        <section class="col col-lg-7">
            <h2>Password Revovery</h2><hr />

            <div>
                <?php if(isset($result)) echo $result;?>>
                <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
            </div>
            <div class="clearfix"></div>

            To request password reset link, please enter your email address in the form below<br/><br />

            <form action=" " method="post">
                <div class="form-group">
                    <label for="emailField">Email Address</label>
                    <input type="text" class="form-control" name="email" id="emailField" placeholder="email" />
                </div>
                <button type="submit" name="passwordRecoveryBtn" class="btn btn-primary pull-right">
                    Recover Password
                </button>
            </form>
        </section>
        <p><a href="index.php">Back</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>
</html>

