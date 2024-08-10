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
    $plan_name = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $section = isset($_POST['section']) ? implode(',', $_POST['section']) : '';
    $hidden_id = $_POST['hidden_id'];
    $existing_documents = $_POST['existing_documents'];

    $uploaded_files = isset($_FILES['document_image']['name']) ? $_FILES['document_image']['name'] : [];

    if (!empty($uploaded_files[0])) {
        $upload_paths = [];
        foreach ($_FILES['document_image']['tmp_name'] as $key => $tmp_name) {
            $file_ext = pathinfo($uploaded_files[$key], PATHINFO_EXTENSION);
            $unique_file_name = uniqid('plan_', true) . '.' . $file_ext;
            $file_path = '../plan/temp/' . $unique_file_name;
            if (move_uploaded_file($tmp_name, $file_path)) {
                $upload_paths[] = $file_path;
            } else {
                $form_errors[] = "Failed to upload file: " . htmlspecialchars($uploaded_files[$key]);
            }
        }
        if (!empty($upload_paths)) {
            $documents = empty($existing_documents) ? implode(',', $upload_paths) : $existing_documents . ',' . implode(',', $upload_paths);
        }
    } else {
        $documents = $existing_documents;
    }

    if (empty($form_errors)) {
        try {
            $query = "UPDATE plan SET plan_name = :plan_name, description = :description, price = :price, date_from = :date_from, date_to = :date_to, section = :section, image_urls = :documents WHERE id = :id AND seller_id = :seller_id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':plan_name' => $plan_name,
                ':description' => $description,
                ':price' => $price,
                ':date_from' => $date_from,
                ':date_to' => $date_to,
                ':section' => $section,
                ':documents' => $documents,
                ':id' => $hidden_id,
                ':seller_id' => $seller_id
            ]);

            if ($stmt->rowCount() == 1) {
                echo "<script type=\"text/javascript\">
                                    swal({
                                    title: \"Good job!\",
                                    text: \"Plan updated successfully!\",
                                    icon: 'success',
                                    button: \"OK\",
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .pull-right {
            float: right !important;
        }

        .btn {
            margin-top: 10px
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
        .btn-primary{
            background-color: #3e64d3 !important;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <div class="container">
            <section class="col col-lg-7">
                <h1 class="h1 mb-2 text-gray-800" style="font-weight: 600;">Edit Plan</h1>
                <br/>
                <br/>
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
                                <div class="d-flex flex-wrap">
                                    <?php
                                    $images = explode(',', $documents);
                                    foreach ($images as $image): ?>
                                        <div class="p-2">
                                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Plan Image" class="document-image" onclick="openModal('<?php echo htmlspecialchars($image); ?>')" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="document_image[]" class="form-control" id="documentImageField" multiple />
                            <input type="hidden" name="existing_documents" value="<?php echo htmlspecialchars($documents); ?>" />
                        </div>

                        <input type="hidden" name="hidden_id" value="<?php echo htmlspecialchars($id); ?>">
                        <button type="submit" name="updatePlan" class="btn btn-primary pull-right">Update Plan</button>
                    </form>
                <?php endif; ?>
            </section>
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-q/gThh3Fv0LVQNADnE8wrfFHTX9pSR4xD6oJ/bh1SvQOgavPaOvInlK0UrrXkgx4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-ym9WY18K7F4+DA8BZBQ8nK7K5bGyQXTKBRUjog9pa7BrpprAP+KEKWDDYV9oHBB8" crossorigin="anonymous"></script>
</body>

</html>