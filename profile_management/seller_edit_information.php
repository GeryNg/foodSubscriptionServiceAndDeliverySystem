

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
            margin-top: 10px;
        }

        .document-image {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
            margin: 10px;
            cursor: pointer;
        }

        .profile-picture {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
        }

        .documents-container {
            display: flex;
            flex-wrap: wrap;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 60px;
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
    </style>
</head>

<body>
<?php
    $page_title = "Edit Seller Information";
    include_once '../partials/staff_nav.php';
    include_once '../resource/utilities.php';
    include_once '../partials/parseSellerEditInformation.php';
?>

    <div class="container" style="margin-top:50px; margin-bottom:80px;">
        <section class="col col-lg-7">
            <h1>Edit Profile</h1>

            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?php if (!isset($_SESSION['username'])): ?>
                <p class="lead">You are not authorized to view this page. <a href="login.php">Login</a>
                    Not yet a member? <a href="../login_management/signup.php">Signup</a></p>
            <?php else: ?>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nameField">Name</label>
                        <input type="text" name="name" class="form-control" id="nameField" value="<?php echo htmlspecialchars($name); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="profilePicField">Profile Picture</label>
                        <?php if ($profile_pic): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-picture" />
                            </div>
                        <?php endif; ?>
                        <input type="file" name="profile_pic" class="form-control" id="profilePicField" />
                        <input type="hidden" name="existing_profile_pic" value="<?php echo htmlspecialchars($profile_pic); ?>" />
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
                        <label for="documentImagesField">Documents</label>
                        <div id="documentImagesField" class="documents-container">
                            <?php
                            if (!empty($documents)) {
                                $document_urls = explode(',', $documents);
                                foreach ($document_urls as $document_url) {
                                    echo '<div class="image-wrapper mb-2 position-relative">';
                                    echo '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 translate-middle" onclick="deleteImage(this, \'' . htmlspecialchars($document_url) . '\')">';
                                    echo '<i class="far fa-trash-alt"></i>';
                                    echo '</button>';
                                    echo '<img src="' . htmlspecialchars($document_url) . '" alt="Document Image" class="document-image" onclick="openModal(this)" />';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                        <input type="file" name="document_image[]" class="form-control" id="documentImagesField" multiple />
                        <input type="hidden" name="existing_documents" value="<?php echo htmlspecialchars($documents); ?>" />
                        <input type="hidden" id="deleted_images" name="deleted_images" value="" />
                    </div>

                    <input type="hidden" name="hidden_id" value="<?php if (isset($id)) echo $id; ?>" />
                    <button type="submit" name="updateSellerInformation" class="btn btn-primary pull-right">Update Profile</button>
                </form>
            <?php endif; ?>
        </section>
    </div>

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script>
        function deleteImage(button, imageUrl) {
            var imageWrapper = button.closest('.image-wrapper');
            imageWrapper.remove();

            var deletedImagesInput = document.getElementById('deleted_images');
            var deletedImages = deletedImagesInput.value ? deletedImagesInput.value.split(',') : [];

            // Add the imageUrl to the list of deleted images
            deletedImages.push(imageUrl);

            // Update the hidden input field with the updated list of deleted images
            deletedImagesInput.value = deletedImages.join(',');
        }

        // Modal for viewing the image
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        function openModal(element) {
            modal.style.display = "block";
            modalImg.src = element.src;
            captionText.innerHTML = element.alt;
        }

        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@12.4.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>

</html>