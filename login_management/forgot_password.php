<!DOCTYPE HTML>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .form-signin {
            max-width: 330px;
            padding: 1rem;
            margin: auto;
        }
        .form-floating > .form-control, .form-floating > .form-control-plaintext {
            margin: 5px;
        }

    </style>
</head>
<body>
    <?php
    $page_title = "User Authentication - Passowrd Reset";
    include_once '../partials/headers.php';
    include_once '../partials/parsePasswordReset.php';

    if (isset($_GET['$id'])) {
        $encode_id = $_GET['id'];
        $decode_id = base64_decode($encode_id);
        $id_array = explode("encodeuserid", $decode_id);
        $id = $id_array[1];
    }
    ?>
    <main class="form-signin">
      <form method="post" action="">
        <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Reset Password Form</h1>

            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>


        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="new_password" value="" placeholder="password">
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="confirm_password" value="" placeholder="password">
            <label for="floatingPassword">Confirm Password</label>
        </div>

        <input type="hidden" name="user_id" value="<?php if(isset($id)) echo $id; ?>" />

        <button class="btn btn-primary w-100 py-2" type="submit" name="passwordResetBtn" value="Reset Password">Reset Password</button>
        <p class="mt-5 mb-3 text-body-secondary">© 2024–2024</p>
      </form>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>
</html>
