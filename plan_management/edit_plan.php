<?php
$page_title = "Edit Plan";
include_once "../partials/staff_nav.php";
include_once '../resource/Database.php';
include_once '../resource/session.php';

$result = '';
$form_errors = array();
$plan_name = '';
$description = '';
$price = '';
$date_from = '';
$date_to = '';
$section = '';
$documents = '';
$plan = null;

$seller_id = $_SESSION['seller_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $plan_id = $_GET['id'] ?? null;
        if ($plan_id) {
            $query = "SELECT * FROM plan WHERE seller_id = :seller_id AND id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute([':seller_id' => $seller_id, ':id' => $plan_id]);
            $plan = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($plan) {
                $plan_name = $plan['plan_name'];
                $description = $plan['description'];
                $price = $plan['price'];
                $date_from = $plan['date_from'];
                $date_to = $plan['date_to'];
                $section = $plan['section'];
                $documents = $plan['image_urls'];
                $id = $plan['id'];

                // Fetch the related add-ons
                $addon_query = "SELECT * FROM addons WHERE plan_id = :plan_id";
                $addon_stmt = $db->prepare($addon_query);
                $addon_stmt->execute([':plan_id' => $plan_id]);
                $addons = $addon_stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                echo '<p>No plan data found for this seller.</p>';
            }
        } else {
            echo '<p>No plan ID provided.</p>';
        }
    } catch (PDOException $ex) {
        echo "Error fetching plan data: " . $ex->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePlan'])) {
    $form_errors = array();

    // Retrieve form data
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $section = isset($_POST['section']) ? implode(',', $_POST['section']) : '';
    $hidden_id = $_POST['hidden_id'];
    $existing_documents = $_POST['existing_documents'];

    $documents = $existing_documents;

    // Handle uploaded plan images
    if (!empty($_FILES['document_image']['name'][0])) {
        $upload_paths = [];
        foreach ($_FILES['document_image']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['document_image']['name'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $unique_file_name = uniqid('plan_', true) . '.' . $file_ext;
            $file_path = '../plan_images/' . $unique_file_name;

            if (move_uploaded_file($tmp_name, $file_path)) {
                $upload_paths[] = $file_path;
            } else {
                $form_errors[] = "Failed to upload file: " . htmlspecialchars($file_name);
            }
        }

        // Append new uploads to existing documents
        if (!empty($upload_paths)) {
            $documents_array = !empty($existing_documents) ? explode(',', $existing_documents) : [];
            $documents_array = array_merge($documents_array, $upload_paths);
            $documents = implode(',', $documents_array);
        }
    }

    // Process deleted images
    if (!empty($_POST['deleted_images'])) {
        $deleted_images = explode(',', $_POST['deleted_images']);
        $documents_array = explode(',', $documents);

        // Remove deleted images from the array
        $documents_array = array_diff($documents_array, $deleted_images);
        $documents = implode(',', $documents_array);

        // Delete the actual files from the server
        foreach ($deleted_images as $deleted_image) {
            if (file_exists($deleted_image)) {
                unlink($deleted_image);
            }
        }
    }

    if (empty($form_errors)) {
        try {
            // Update plan in the database
            $sqlUpdate = "UPDATE plan SET 
                            plan_name = :plan_name, 
                            description = :description, 
                            price = :price, 
                            date_from = :date_from, 
                            date_to = :date_to, 
                            section = :section, 
                            image_urls = :documents 
                          WHERE id = :id";
            $statement = $db->prepare($sqlUpdate);
            $statement->execute([
                ':plan_name' => $plan_name,
                ':description' => $description,
                ':price' => $price,
                ':date_from' => $date_from,
                ':date_to' => $date_to,
                ':section' => $section,
                ':documents' => $documents,
                ':id' => $hidden_id
            ]);

            // Process add-ons
            $addonNames = $_POST['addon_name'] ?? [];
            $addonPrices = $_POST['addon_price'] ?? [];
            $addonImages = $_FILES['addon_image'] ?? [];

            // Delete all current add-ons and re-insert them
            $deleteAddonQuery = "DELETE FROM addons WHERE plan_id = :plan_id";
            $deleteAddonStmt = $db->prepare($deleteAddonQuery);
            $deleteAddonStmt->execute([':plan_id' => $hidden_id]);

            // Re-insert add-ons
            $addonStmt = $db->prepare("INSERT INTO addons (plan_id, addon_name, addon_price, addon_image) VALUES (?, ?, ?, ?)");

            foreach ($addonNames as $index => $addonName) {
                if (!empty($addonName)) {
                    // Handle image upload for add-ons
                    $addonImage = !empty($_POST['existing_addon_images'][$index]) ? $_POST['existing_addon_images'][$index] : null;

                    if (!empty($addonImages['name'][$index])) {
                        $fileName = $addonImages['name'][$index];
                        $fileTmpName = $addonImages['tmp_name'][$index];
                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $allowedExts = ['jpg', 'jpeg', 'png'];

                        if (in_array($fileExt, $allowedExts)) {
                            $newFileName = uniqid('addon_', true) . '.' . $fileExt;
                            $targetDir = '../addon_images/';
                            $targetFilePath = $targetDir . $newFileName;

                            if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                                $addonImage = $targetFilePath;
                            }
                        }
                    }

                    $addonStmt->execute([$hidden_id, $addonName, $addonPrices[$index], $addonImage]);
                }
            }

            // SweetAlert for success or no changes
            if ($statement->rowCount() > 0 || $addonStmt->rowCount() > 0) {
                echo "<script type=\"text/javascript\">
                    swal({
                        title: \"Good job!\",
                        text: \"Plan updated successfully!\",
                        icon: \"success\",
                        button: \"OK\"
                    }).then(function() {
                        window.location.href = 'list_plan.php';
                    });
                </script>";
            } else {
                echo "<script type=\"text/javascript\">
                    swal({
                        title: \"Nothing Changed\",
                        text: \"You have not made any changes\",
                        icon: \"info\",
                        button: \"OK\"
                    }).then(function() {
                        window.location.href = 'list_plan.php';
                    });
                </script>";
            }
        } catch (PDOException $ex) {
            $result = flashMessage("An error occurred: " . $ex->getMessage());
        }
    } else {
        $result = flashMessage("There were errors in the form<br>");
        $result .= show_errors($form_errors);
    }
}
?>

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
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

        label {
            margin-bottom: 10px;
            display: block;
            font-weight: bold;
            color: #666;
        }

        .pull-right {
            float: right !important;
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

        .button1:hover {
            background-color: #7a85ff;
        }

        .document-image {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 500px;
        }

        .modal-content,
        #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff !important;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }

        .btn-primary {
            background-color: #3e64d3 !important;
            font-weight: 500;
        }

        .addon-item {
            padding: 40px;
            border-radius: 10px;
            background-color: #f2f2f2;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1>Edit Plan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../plan_management/list_plan.php">List Plan</a></li>
            <li class="breadcrumb-item active">Edit Plan</li>
        </ol>
        <div class="container1">
            <br />
            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>

            <?php if ($plan): ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="planNameField">Plan Name</label>
                        <input type="text" name="plan_name" class="form-control" id="planNameField" value="<?php echo htmlspecialchars($plan_name); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="descriptionField">Description</label>
                        <input type="text" name="description" class="form-control" id="descriptionField" value="<?php echo htmlspecialchars($description); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="priceField">Price</label>
                        <input type="text" name="price" class="form-control" id="priceField" value="<?php echo htmlspecialchars($price); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="dateFromField">Date From</label>
                        <input type="date" name="date_from" class="form-control" id="dateFromField" value="<?php echo htmlspecialchars($date_from); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="dateToField">Date To</label>
                        <input type="date" name="date_to" class="form-control" id="dateToField" value="<?php echo htmlspecialchars($date_to); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="sectionField">Section</label>
                        <?php
                        $sections = ['Lunch', 'Dinner'];
                        $selected_sections = explode(',', $section);
                        foreach ($sections as $sec): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="section[]" value="<?php echo htmlspecialchars($sec); ?>"
                                    <?php echo in_array($sec, $selected_sections) ? 'checked' : ''; ?> />
                                <label class="form-check-label"><?php echo htmlspecialchars($sec); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group">
                        <label for="documentImageField">Plan Image</label>
                        <?php if ($documents): ?>
                            <div class="d-flex flex-wrap" style="margin-top: 20px;">
                                <?php
                                $images = explode(',', $documents);
                                foreach ($images as $image): ?>
                                    <div class="position-relative p-2">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 translate-middle" onclick="deleteImage(this, '<?php echo htmlspecialchars($image); ?>')">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Plan Image" class="document-image" onclick="openModal(this)" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="document_image[]" class="form-control" id="documentImageField" multiple />
                        <input type="hidden" name="existing_documents" value="<?php echo htmlspecialchars($documents); ?>" />
                        <input type="hidden" id="deleted_images" name="deleted_images" value="" />
                    </div>
                    <hr/>
                    <br/>                    
                    <div class="form-group">
                        <label for="addons">Add-ons</label>
                        <div id="addon-container">
                            <?php if (!empty($addons)): ?>
                                <?php foreach ($addons as $index => $addon): ?>
                                    <div class="addon-item">
                                        <label>Name:</label>
                                        <input type="text" name="addon_name[]" class="form-control" value="<?php echo htmlspecialchars($addon['addon_name']); ?>" /><br/>
                                        <label>Price:</label>
                                        <input type="text" name="addon_price[]" class="form-control" value="<?php echo htmlspecialchars($addon['addon_price']); ?>" /><br/>
                                        <label>Image:</label>
                                        <?php if (!empty($addon['addon_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($addon['addon_image']); ?>" alt="Addon Image" class="document-image" /><br/>
                                            <input type="hidden" name="existing_addon_images[]" value="<?php echo htmlspecialchars($addon['addon_image']); ?>" />
                                        <?php endif; ?>
                                        <input type="file" name="addon_image[]" class="form-control" /><br/>
                                        <button type="button" class="remove-addon-btn btn btn-danger">Remove Add-on</button><br/><br/>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="addon-item">
                                    <label>Name:</label>
                                    <input type="text" name="addon_name[]" class="form-control" /><br/>
                                    <label>Price:</label>
                                    <input type="text" name="addon_price[]" class="form-control" /><br/>
                                    <label>Image:</label>
                                    <input type="file" name="addon_image[]" class="form-control" /><br/>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-primary" id="add-addon-btn">Add More Add-ons</button>
                    </div>

                    <input type="hidden" name="hidden_id" value="<?php echo htmlspecialchars($id); ?>">
                    <button type="submit" name="updatePlan" class="button1">Update Plan</button>
                </form>
            <?php endif; ?>
            <br />
            <br />
            <br />
        </div>
    </div>

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script>
        function deleteImage(button, imageUrl) {
            var imageWrapper = button.closest('.position-relative');
            imageWrapper.remove();

            var deletedImagesInput = document.getElementById('deleted_images');
            var deletedImages = deletedImagesInput.value ? deletedImagesInput.value.split(',') : [];

            // Add the imageUrl to the list of deleted images
            deletedImages.push(imageUrl);

            // Update the hidden input field with the updated list of deleted images
            deletedImagesInput.value = deletedImages.join(',');
        }

        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        document.querySelectorAll('.document-image').forEach(img => {
            img.onclick = function() {
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }
        });

        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }
    </script>

    <script>
        document.getElementById('add-addon-btn').addEventListener('click', function() {
            const addonContainer = document.getElementById('addon-container');
            const addonItem = document.createElement('div');
            addonItem.classList.add('addon-item');
            addonItem.innerHTML = `
                <label>Name:</label>
                <input type="text" name="addon_name[]" class="form-control" />
                <label>Price:</label>
                <input type="text" name="addon_price[]" class="form-control" />
                <label>Image:</label>
                <input type="file" name="addon_image[]" class="form-control" />
                <button type="button" class="remove-addon-btn btn btn-danger">Remove Add-on</button>
            `;
            addonContainer.appendChild(addonItem);

            // Add event listener for the remove button
            addonItem.querySelector('.remove-addon-btn').addEventListener('click', function() {
                addonItem.remove();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>

</html>
