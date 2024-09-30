<?php
$page_title = "Edit Seller Information";
include_once '../partials/staff_nav.php';
include_once '../resource/utilities.php';
include_once '../partials/parseSellerEditInformation.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlspecialchars($page_title); ?></title>
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
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <h1>Edit Restaurant Profile</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="../partials/seller_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="../profile_management/seller_profile.php">Profile</a></li>
            <li class="breadcrumb-item active">Edit Restaurant Profile</li>
        </ol>
        <section class="col col-lg-7">
            <?php if (isset($result) || !empty($form_errors)): ?>
                <div>
                    <?php echo show_combined_messages($result, $form_errors); ?>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nameField">Name</label>
                    <input type="text" name="name" class="form-control" id="sellerName" value="<?php echo htmlspecialchars($name); ?>" required />
                </div>

                <div class="form-group">
                    <label for="profilePicField">Profile Picture</label>
                    <?php if ($profile_pic): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-picture" />
                        </div>
                    <?php endif; ?>
                    <input type="file" name="profile_pic" class="form-control" id="profilePic" accept=".jpg, .jpeg, .png" />
                    <input type="hidden" name="existing_profile_pic" value="<?php echo htmlspecialchars($profile_pic); ?>" />
                </div>

                <div class="form-group">
                    <label for="detailField">Detail</label>
                    <input type="text" name="detail" class="form-control" id="description" value="<?php echo htmlspecialchars($detail); ?>" required />
                </div>

                <div class="form-group">
                    <label for="contactNumberField">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" id="contactNum" value="<?php echo htmlspecialchars($contact_number); ?>" required />
                </div>

                <div class="form-group">
                    <label for="unitNumberField">Unit Number / Block / Door Number</label>
                    <input type="text" name="unit_number" class="form-control" id="unitNumber" value="<?php echo htmlspecialchars($unit_number); ?>" required />
                </div>

                <div class="form-group">
                    <label for="addressField">Address</label>
                    <input type="text" name="address" class="form-control" id="address" value="<?php echo htmlspecialchars($address); ?>" required />
                </div>

                <div class="form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" class="form-control" id="postcode" value="<?php echo htmlspecialchars($postcode); ?>" readonly required />
                </div>

                <div class="form-group">
                    <label for="cityField">City</label>
                    <input type="text" name="city" class="form-control" id="city" value="<?php echo htmlspecialchars($city); ?>" readonly required />
                </div>

                <div class="form-group">
                    <label for="stateField">State</label>
                    <input type="text" name="state" class="form-control" id="state" value="<?php echo htmlspecialchars($state); ?>" readonly required />
                </div>

                <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($latitude); ?>" required />
                <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($longitude); ?>"  required/>

                <!-- Bank Account Field -->
                <div class="form-group">
                    <label for="bankAccountField">Bank Account</label>
                    <input type="text" name="bank_account" class="form-control" id="bankAccount" value="<?php echo htmlspecialchars($bank_account); ?>" required />
                </div>

                <!-- Documents Field -->
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
                    <input type="file" name="document_image[]" class="form-control" id="documentImages" multiple accept=".jpg, .jpeg, .png" />
                    <input type="hidden" name="existing_documents" value="<?php echo htmlspecialchars($documents); ?>" />
                    <input type="hidden" id="deleted_images" name="deleted_images" value="" />
                </div>

                <input type="hidden" name="hidden_id" value="<?php if (isset($id)) echo htmlspecialchars($id); ?>" />
                <button type="submit" name="updateSellerInformation" class="btn btn-primary pull-right">Update Profile</button>
            </form>
        </section>
    </div>

    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <script>
        // Function to delete images from the documents
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

        // Function to open modal for image viewing
        function openModal(element) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");

            modal.style.display = "block";
            modalImg.src = element.src;
            captionText.innerHTML = element.alt;
        }

        // Close modal when the close button is clicked
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>
    <script>
        function initAutocomplete() {
            const addressField = document.getElementById('address');
            const autocomplete = new google.maps.places.Autocomplete(addressField);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                const location = place.geometry.location;
                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();

                let postcode = '',
                    city = '',
                    state = '';

                place.address_components.forEach(function(component) {
                    const types = component.types;

                    if (types.includes('postal_code')) {
                        postcode = component.long_name;
                    }
                    if (types.includes('locality')) {
                        city = component.long_name;
                    } else if (types.includes('administrative_area_level_2')) {
                        city = component.long_name;
                    }
                    if (types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    }
                });

                document.getElementById('city').value = city;
                document.getElementById('state').value = state;

                if (postcode) {
                    document.getElementById('postcode').value = postcode;
                    document.getElementById('postcode').readOnly = true;
                } else {
                    document.getElementById('postcode').value = "";
                    document.getElementById('postcode').readOnly = false;
                    alert('No postcode found for this address. Please enter manually.');
                }
            });
        }

        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>

</body>

</html>
