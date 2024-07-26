<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .pull-right {
            float: right !important;
        }
        .btn {
            margin-top:10px
        }
        .document-image {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php
    $page_title = "Edit Seller Information";
    include_once "../partials/staff_nav.php";
    include_once '../resource/Database.php';
    include_once '../resource/session.php';

    $result = '';
    $form_errors = array();
    $name = '';
    $detail = '';
    $contact_number = '';
    $address = '';
    $bank_account = '';
    $documents = '';

    $user_id = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "SELECT * FROM seller WHERE user_id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->execute([':user_id' => $user_id]);
            $seller = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($seller) {
                $name = $seller['name'];
                $detail = $seller['detail'];
                $contact_number = $seller['contact_number'];
                $address = $seller['address'];
                $bank_account = $seller['bank_account'];
                $documents = $seller['image_urls'];
                $id = $seller['id'];
            } else {
                echo '<p>No seller data found.</p>';
            }
        } catch (PDOException $ex) {
            echo "Error fetching seller data: " . $ex->getMessage();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateSellerInformation'])) {
        $name = $_POST['name'];
        $detail = $_POST['detail'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];
        $bank_account = $_POST['bank_account'];
        $hidden_id = $_POST['hidden_id'];

        $existing_documents = $_POST['existing_documents'];

        $uploaded_files = isset($_FILES['document_image']['name']) ? $_FILES['document_image']['name'] : [];

        if (!empty($uploaded_files[0])) {
            $upload_paths = [];
            foreach ($_FILES['document_image']['tmp_name'] as $key => $tmp_name) {
                $file_ext = pathinfo($uploaded_files[$key], PATHINFO_EXTENSION);
                $unique_file_name = uniqid('seller_', true) . '.' . $file_ext;
                $file_path = '../document/' . $unique_file_name;
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
                $query = "UPDATE seller SET name = :name, detail = :detail, contact_number = :contact_number, address = :address, bank_account = :bank_account, image_urls = :documents WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    ':name' => $name,
                    ':detail' => $detail,
                    ':contact_number' => $contact_number,
                    ':address' => $address,
                    ':bank_account' => $bank_account,
                    ':documents' => $documents,
                    ':id' => $hidden_id
                ]);

                if ($stmt->rowCount() == 1) {
                    echo "<script type=\"text/javascript\">
                        swal({
                            title: \"Good job!\",
                            text: \"Profile updated successfully!\",
                            icon: \"success\",
                            button: \"OK\"
                        }).then(function() {
                            window.location.href = 'seller_profile.php';
                        });
                    </script>";
                } else {
                    echo "<script type=\"text/javascript\">
                        swal({
                            title: \"Nothing Happened\",
                            text: \"You have not made any changes\",
                            icon: \"info\"
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


    <div class="container" style="margin-top:20px;">
        <section class="col col-lg-7">
            <h2>Edit Profile</h2>

            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>

            <?php if ($seller): ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nameField">Name</label>
                        <input type="text" name="name" class="form-control" id="nameField" value="<?php echo htmlspecialchars($name); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="detailField">Detail</label>
                        <input type="text" name="detail" class="form-control" id="detailField" value="<?php echo htmlspecialchars($detail); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="contactNumberField">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" id="contactNumberField" value="<?php echo htmlspecialchars($contact_number); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="addressField">Address</label>
                        <input type="text" name="address" class="form-control" id="addressField" value="<?php echo htmlspecialchars($address); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="bankAccountField">Bank Account</label>
                        <input type="text" name="bank_account" class="form-control" id="bankAccountField" value="<?php echo htmlspecialchars($bank_account); ?>" />
                    </div>
                    
                    <div class="form-group">
                        <label for="documentImageField">Documents</label>
                        <?php if ($documents): ?>
                            <div class="d-flex flex-wrap">
                                <?php
                                $images = explode(',', $documents);
                                foreach ($images as $image): ?>
                                    <div class="p-2">
                                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Document Image" class="document-image" />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="document_image[]" class="form-control" id="documentImageField" multiple />
                        <input type="hidden" name="existing_documents" value="<?php echo htmlspecialchars($documents); ?>" />
                    </div>
                    <input type="hidden" name="hidden_id" value="<?php echo htmlspecialchars($id); ?>"/>
                    <button type="submit" name="updateSellerInformation" class="btn btn-primary pull-right">Update Information</button>
                </form>
            <?php else: ?>
                <p>Your data had been updated!</p>
            <?php endif; ?>
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
