<?php
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $seller_id = $_GET['seller_id'] ?? $_SESSION['seller_id'];

    try {
        $sqlQuery = "SELECT * FROM seller WHERE id = :seller_id";
        $statement = $db->prepare($sqlQuery);
        $statement->execute([':seller_id' => $seller_id]);

        if ($seller = $statement->fetch(PDO::FETCH_ASSOC)) {
            // Populate variables with database values initially
            $name = $seller['name'];
            $detail = $seller['detail'];
            $contact_number = $seller['contact_number'];
            $address = $seller['address'];
            $postcode = $seller['postcode'];
            $city = $seller['city'];
            $state = $seller['state'];
            $unit_number = $seller['unit_number'];
            $latitude = $seller['latitude'];
            $longitude = $seller['longitude'];
            $bank_account = $seller['bank_account'];
            $documents = $seller['image_urls'];
            $profile_pic = $seller['profile_pic'];
            $id = $seller['id'];
        } else {
            echo '<p>No seller data found.</p>';
        }
    } catch (PDOException $ex) {
        echo "Error fetching seller data: " . $ex->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateSellerInformation'])) {
    $form_errors = array();

    // Retrieve form data from $_POST so user-corrected input can be retained after validation
    $name = htmlspecialchars($_POST['name']);
    $detail = htmlspecialchars($_POST['detail']);
    $contact_number = htmlspecialchars($_POST['contact_number']);
    $address = htmlspecialchars($_POST['address']);
    $postcode = htmlspecialchars($_POST['postcode']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $unit_number = htmlspecialchars($_POST['unit_number']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $bank_account = htmlspecialchars($_POST['bank_account']);
    $hidden_id = htmlspecialchars($_POST['hidden_id']);
    $existing_documents = htmlspecialchars($_POST['existing_documents']);
    $existing_profile_pic = htmlspecialchars($_POST['existing_profile_pic']);

    // Validation for contact number
    if (!preg_match('/^[\+\-\d\s]+$/', $contact_number)) {
        $form_errors[] = "Contact number can only contain digits, and must be in Phone Number Format.";
    }

    // Validate bank account number
    if (!preg_match('/^\d+$/', $bank_account)) {
        $form_errors[] = "Bank account number can only contain digits.";
    }

    // Document handling (preserve existing documents)
    $documents = $existing_documents;

    // Handle uploaded document images
    if (!empty($_FILES['document_image']['name'][0])) {
        $upload_paths = [];
        foreach ($_FILES['document_image']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['document_image']['name'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $unique_file_name = uniqid('seller_', true) . '.' . $file_ext;
            $file_path = '../document/' . $unique_file_name;

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

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $profile_pic_name = $_FILES['profile_pic']['name'];
        $file_ext = pathinfo($profile_pic_name, PATHINFO_EXTENSION);
        $unique_file_name = uniqid('profile_', true) . '.' . $file_ext;
        $profile_pic_path = '../seller_profile_pic/' . $unique_file_name;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic_path)) {
            // Optionally delete old profile picture
            if (file_exists($existing_profile_pic)) {
                unlink($existing_profile_pic);
            }
            $profile_pic = $profile_pic_path;
        } else {
            $form_errors[] = "Failed to upload profile picture.";
            $profile_pic = $existing_profile_pic;
        }
    } else {
        $profile_pic = $existing_profile_pic;
    }

    // Process deleted images
    if (!empty($_POST['deleted_images'])) {
        $deleted_images = explode(',', $_POST['deleted_images']);
        $documents_array = explode(',', $documents);

        $documents_array = array_diff($documents_array, $deleted_images);
        $documents = implode(',', $documents_array);

        foreach ($deleted_images as $deleted_image) {
            if (file_exists($deleted_image)) {
                unlink($deleted_image);
            }
        }
    }

    // Check for postcode in the database
    $stmt = $db->prepare("SELECT * FROM address_book WHERE postcode = ?");
    $stmt->execute([$postcode]);

    if ($stmt->rowCount() === 0) {
        $form_errors[] = "Your location is not supported yet.";
    }

    if (empty($form_errors)) {
        try {
            $sqlUpdate = "UPDATE seller SET 
                            name = :name, 
                            detail = :detail, 
                            contact_number = :contact_number, 
                            address = :address, 
                            postcode = :postcode,
                            city = :city,
                            state = :state,
                            unit_number = :unit_number,    
                            latitude = :latitude,          
                            longitude = :longitude,        
                            bank_account = :bank_account,  
                            image_urls = :documents, 
                            profile_pic = :profile_pic 
                          WHERE id = :id";
            $statement = $db->prepare($sqlUpdate);
            $statement->execute([
                ':name' => $name,
                ':detail' => $detail,
                ':contact_number' => $contact_number,
                ':address' => $address,
                ':postcode' => $postcode,
                ':city' => $city,
                ':state' => $state,
                ':unit_number' => $unit_number,
                ':latitude' => $latitude,
                ':longitude' => $longitude,
                ':bank_account' => $bank_account,
                ':documents' => $documents,
                ':profile_pic' => $profile_pic,
                ':id' => $hidden_id
            ]);

            if ($statement->rowCount() > 0) {
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
                    }).then(function() {
                        window.location.href = 'seller_profile.php';
                    });
                </script>";
            }
        } catch (PDOException $ex) {
            $result = flashMessage("An error occurred: " . $ex->getMessage());
        }
    } else {
        if (count($form_errors) == 1) {
            $result = flashMessage("There was 1 error in the form<br>");
        } else {
            $result = flashMessage("There were " . count($form_errors) . " errors in the form <br>");
        }
    }
}
