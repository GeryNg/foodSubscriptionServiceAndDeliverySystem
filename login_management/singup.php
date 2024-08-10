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
        .form-floating > .form-select {
            margin: 5px;
            height: calc(3.5rem + 2px);
        }
        p {
            margin-bottom: 5px !important;
        }
    </style>
</head>
<body>
    <?php
    $page_title = "User Authentication - Sign Up Page";
    include_once '../partials/headers.php';
    include_once '../partials/parseSignup.php';
    ?>

    <div class="main-container">
        <main class="form-signin">
            <form method="post" action="">
                <img class="mb-4" src="../image/logo-rounded.png" alt="Logo" width="80" height="80">
                <h1 class="h3 mb-3 fw-normal">Register Form</h1>

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
                    <input type="password" class="form-control" id="floatingPassword" name="password2" value="" placeholder="Confirm Password">
                    <label for="floatingPassword">Confirm Password</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="name" value="" placeholder="Name">
                    <label for="floatingInput">Name</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="gender" name="gender">
                        <option value="" selected>Select Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="O">Other</option>
                    </select>
                    <label for="gender">Gender</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInput" name="phoneNumber" value="" placeholder="Phone Number">
                    <label for="floatingInput">Phone Number</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="securityQuestion1" name="securityQuestion1" onchange="updateSecurityQuestions()">
                        <option value="" selected>Select a Security Question</option>
                        <option value="1">What was your first pet's name?</option>
                        <option value="2">What is your mother's maiden name?</option>
                        <option value="3">What was the name of your first school?</option>
                        <option value="4">What is your favorite food?</option>
                        <option value="5">What city were you born in?</option>
                    </select>
                    <label for="securityQuestion1">Security Question 1</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="securityAnswer1" name="securityAnswer1" placeholder="Answer">
                    <label for="securityAnswer1">Answer</label>
                </div>

                <div class="form-floating">
                    <select class="form-select" id="securityQuestion2" name="securityQuestion2" onchange="updateSecurityQuestions()" required>
                        <option value="" selected>Select a Security Question</option>
                    </select>
                    <label for="securityQuestion2">Security Question 2</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="securityAnswer2" name="securityAnswer2" placeholder="Answer" required>
                    <label for="securityAnswer2">Answer</label>
                </div>
                <br />
                <button class="btn btn-primary w-100 py-2" type="submit" name="signupBtn" value="Signup">Sign Up</button>
                <br />
                <hr />
                <p><a href="forgot_password.php">Forgot Password</a></p>
                <p><a href="login.php">Already have an account? Login!</a></p>
                <p class="mt-5 mb-3 text-body-secondary">© 2024-2024</p>
            </form>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
    <script>
        const allQuestions = {
            1: "What was your first pet's name?",
            2: "What is your mother's maiden name?",
            3: "What was the name of your first school?",
            4: "What is your favorite food?",
            5: "What city were you born in?"
        };

        function updateSecurityQuestions() {
            const question1 = document.getElementById('securityQuestion1').value;
            const question2 = document.getElementById('securityQuestion2');
            const selectedValue = question2.value; 

            question2.innerHTML = '<option value="" selected>Select a Security Question</option>';

            Object.keys(allQuestions).forEach(key => {
                if (key != question1) {
                    question2.innerHTML += `<option value="${key}">${allQuestions[key]}</option>`;
                }
            });

            if (selectedValue && selectedValue != question1) {
                question2.value = selectedValue;
            }
        }

        document.addEventListener('DOMContentLoaded', updateSecurityQuestions);
        document.getElementById('securityQuestion1').addEventListener('change', updateSecurityQuestions);
    </script>
</body>
</html>
