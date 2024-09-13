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
        .container-fluid {
            margin-bottom: 5%;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin: 3rem 0 0.5rem 0;
            font-weight: 800;
            line-height: 1.2;
        }

        .breadcrumb {
            background-color: transparent;
        }

        .container1 {
            background: white;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .title p {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: bold;
            line-height: 1.2;
        }

        label {
            margin-bottom: 10px;
            display: block;
            font-weight: bold;
            color: #666;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
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
            font-weight: bold;
            border-radius: 10px;
        }

        .button2 {
            background-color: #5C67F2;
            color: white;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            float: left;
            font-weight: bold;
            border-radius: 10px;
            margin-right: 20px;
        }

        .button1:hover {
            background-color: #7a85ff;
        }

        form {
            overflow: auto;
        }

        .addon-item2 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Add New Plan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Add Plan</li>
        </ol>
        <div class="container1">
            <?php if ($seller_access === 'verify'): ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <br />
                    <?php if (isset($result) || !empty($form_errors)): ?>
                        <div>
                            <?php echo show_combined_messages($result, $form_errors); ?>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>

                    <label>Name: <input type="text" name="plan_name" class="form-control"></label><br>
                    <label>Description: <input type="text" name="description" class="form-control"></textarea></label><br>
                    <label>Price: <input type="text" name="price" class="form-control"></label><br>
                    <label>Date From: <input type="date" name="date_from" id="date_from" class="form-control"></label><br>
                    <label>Date To: <input type="date" name="date_to" id="date_to" class="form-control"></label><br>
                    <label>Section:</label>
                    <input type="checkbox" name="sections[]" value="Lunch"> Lunch<br>
                    <input type="checkbox" name="sections[]" value="Dinner"> Dinner<br><br>
                    <label>Images (up to 6, format: jpg/jpeg/png):</label><br>
                    <input type="file" name="images[]" accept=".jpg, .jpeg, .png" multiple class="form-control"><br><br>
                    <label>
                        <input type="checkbox" id="hasAddonsCheckbox" name="has_addons" value="1"> Add-ons (Optional)
                    </label>
                    <!-- Add-ons section, initially hidden -->
                    <div id="addonsSection" style="display:none;">
                        <label>Add-Ons:</label>
                        <div id="addon-container">
                            <div class="addon-item">
                                <input type="text" name="addon_name[]" placeholder="Add-on Name" class="form-control">
                                <input type="text" name="addon_price[]" placeholder="Add-on Price" class="form-control">
                                <button type="button" class="add-addon-btn button2">Add More</button>
                            </div>
                        </div>
                    </div><br><br>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hasAddonsCheckbox = document.getElementById('hasAddonsCheckbox');
            const addonsSection = document.getElementById('addonsSection');

            // Toggle the visibility of the add-ons section when the checkbox is clicked
            hasAddonsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    addonsSection.style.display = 'block';
                } else {
                    addonsSection.style.display = 'none';
                }
            });

            // Add functionality to add more add-on fields dynamically
            const addonContainer = document.getElementById('addon-container');

            // Show only the first add-on field initially
            const initialAddon = `
            <div class="addon-item" style="margin-top: 20px;">
                <label>Name: 
                <input type="text" name="addon_name[]" placeholder="Add-on Name" class="form-control"></label>
                <label>Price: 
                <input type="text" name="addon_price[]" placeholder="Add-on Price" class="form-control"></label>
                <label>Image (jpg/jpeg/png): 
                <input type="file" name="addon_image[]" accept=".jpg, .jpeg, .png" class="form-control"></label>
                <button type="button" class="add-addon-btn button2">Add More</button>
            </div>
        `;
            addonContainer.innerHTML = initialAddon;

            // Event delegation to handle clicks on dynamically added buttons
            addonContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('add-addon-btn')) {
                    // Add new add-on field
                    const newAddon = document.createElement('div');
                    newAddon.classList.add('addon-item');
                    newAddon.innerHTML = `
                    <div class="addon-item2">
                    <br/>
                    <br/>
                        <label>Name: 
                        <input type="text" name="addon_name[]" placeholder="Add-on Name" class="form-control"></label>
                        <label>Price: 
                        <input type="text" name="addon_price[]" placeholder="Add-on Price" class="form-control"></label>
                        <label>Image (jpg/jpeg/png): 
                        <input type="file" name="addon_image[]" accept=".jpg, .jpeg, .png" class="form-control"></label>
                        <button type="button" class="add-addon-btn button2">Add More</button>
                        <button type="button" class="remove-addon-btn button2">Remove</button>
                    </div>
                `;
                    addonContainer.appendChild(newAddon);

                    const previousAddons = addonContainer.querySelectorAll('.addon-item');
                    previousAddons.forEach(function(addon, index) {
                        if (index < previousAddons.length - 1) {
                            const addMoreBtn = addon.querySelector('.add-addon-btn');
                            if (addMoreBtn) {
                                addMoreBtn.style.display = 'none';
                            }
                        }
                    });
                }

                if (event.target.classList.contains('remove-addon-btn')) {
                    // Remove the add-on field
                    const addonItem = event.target.closest('.addon-item');
                    addonItem.remove();

                    const lastAddonItem = addonContainer.querySelector('.addon-item:last-child');
                    if (lastAddonItem) {
                        const lastAddMoreBtn = lastAddonItem.querySelector('.add-addon-btn');
                        if (lastAddMoreBtn) {
                            lastAddMoreBtn.style.display = 'inline-block';
                        }
                    }
                }
            });
        });
    </script>


</body>

</html>