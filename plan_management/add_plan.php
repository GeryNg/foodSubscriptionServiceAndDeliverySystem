<?php
$page_title = "Add Plan";
$current_page = basename(__FILE__);
include_once '../partials/staff_nav.php';
include_once '../partials/parsePlan.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

$seller_access = $_SESSION['access'] ?? '';

if (empty($seller_access) || $seller_access !== 'verify') {
    echo '<p>You do not have permission to access this page.</p>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        .main-content {
            padding: 20px;
            background-color: #f3f3f3;
        }
        .container {
            background: white;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        label {
            margin-bottom: 10px;
            display: block;
            color: #666;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .button1 {
            background-color: #5C67F2;
            color: white;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            float: right;
            margin-top: 10px;
        }
        .button1:hover {
            background-color: #7a85ff;
        }
        form {
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <?php if ($seller_access === 'verify'): ?>
                <h1>Add New Plan</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <br />

                    <?php if (isset($result) || !empty($form_errors)): ?>
                        <div>
                            <?php echo show_combined_messages($result, $form_errors); ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>

                    <label>Name: <input type="text" name="plan_name"></label><br>
                    <label>Description: <textarea name="description"></textarea></label><br>
                    <label>Price: <input type="text" name="price"></label><br>
                    <label>Date From: <input type="date" name="date_from" id="date_from"></label><br>
                    <label>Date To: <input type="date" name="date_to" id="date_to"></label><br>
                    <label>Section:</label>
                        <input type="checkbox" name="sections[]" value="Lunch"> Lunch<br>
                        <input type="checkbox" name="sections[]" value="Dinner"> Dinner<br><br>
                    <label>Images (up to 6, format: jpg/jpeg/png):</label><br>
                    <input type="file" name="images[]" accept=".jpg, .jpeg, .png" multiple><br><br>
                    <button type="submit" name="AddPlanBtn" value="AddPlan" class="button1">Add Plan</button>
                </form>
            <?php else: ?>
                <p>You do not have permission to add a plan at this time.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');

            const today = new Date().toISOString().split('T')[0];
            dateFrom.setAttribute('min', today);

            dateFrom.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const minDateTo = new Date(selectedDate);
                minDateTo.setDate(minDateTo.getDate() + 7);

                dateTo.setAttribute('min', minDateTo.toISOString().split('T')[0]);
                dateTo.value = minDateTo.toISOString().split('T')[0];
            });

            dateTo.addEventListener('change', function() {
                const selectedDateFrom = new Date(dateFrom.value);
                const selectedDateTo = new Date(this.value);
                const differenceInDays = (selectedDateTo - selectedDateFrom) / (1000 * 60 * 60 * 24);

                if (differenceInDays < 7) {
                    alert("Date To must be at least 7 days after Date From.");
                    dateTo.value = new Date(selectedDateFrom.setDate(selectedDateFrom.getDate() + 7)).toISOString().split('T')[0];
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                const planName = document.querySelector('input[name="plan_name"]').value;
                const price = document.querySelector('input[name="price"]').value;

                if (planName.trim() === '' || price.trim() === '') {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill in all required fields!',
                    });
                }
            });
        });
    </script>
</body>
</html>
