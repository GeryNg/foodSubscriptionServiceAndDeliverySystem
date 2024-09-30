<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if (!empty($_POST['postcode']) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $postcode = htmlspecialchars($_POST['postcode']);
    $stmt = $db->prepare("SELECT * FROM address_book WHERE postcode = ?");
    $stmt->execute([$postcode]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

if (isset($_POST['activeAccountBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = ['seller_name', 'description', 'contact_num', 'address', 'postcode', 'city', 'state', 'bank', 'bank_account_number', 'unit_number'];
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = ['seller_name' => 3, 'description' => 10, 'bank_account_number' => 10];
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Validate numeric value for bank account number
    $numeric_fields = ['bank_account_number'];
    $form_errors = array_merge($form_errors, check_numeric($numeric_fields));

    // Handle unit number field
    $unit_number = htmlspecialchars($_POST['unit_number']);

    // Validate and process profile picture upload
    $profilePicUrl = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileTmpName = $_FILES['profile_pic']['tmp_name'];
        $fileSize = $_FILES['profile_pic']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedFormats)) {
            if ($fileSize <= 2 * 1024 * 1024) { // Check for file size (e.g., 2MB limit)
                $newFileName = uniqid('', true) . "." . $fileExtension;
                $targetDir = "../seller_profile_pic/";
                $targetPath = $targetDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    $profilePicUrl = $targetPath;
                } else {
                    $form_errors[] = "Failed to move uploaded file: $fileName";
                }
            } else {
                $form_errors[] = "File size exceeds the limit: $fileName";
            }
        } else {
            $form_errors[] = "Invalid file format for: $fileName";
        }
    } else {
        $form_errors[] = "Profile picture is required.";
    }

    // Validate and process image uploads
    $imageUrls = [];
    if (!empty(array_filter($_FILES['images']['name']))) {
        $allowedFormats = ['jpg', 'jpeg', 'png'];
        $totalFiles = count($_FILES['images']['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['images']['name'][$i];
            $fileTmpName = $_FILES['images']['tmp_name'][$i];
            $fileSize = $_FILES['images']['size'][$i];
            $fileError = $_FILES['images']['error'][$i];

            if ($fileError === 0) {
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExtension, $allowedFormats)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // Check for file size (e.g., 2MB limit)
                        $newFileName = uniqid('', true) . "." . $fileExtension;
                        $targetDir = "../document/";
                        $targetPath = $targetDir . $newFileName;

                        if (move_uploaded_file($fileTmpName, $targetPath)) {
                            $imageUrls[] = $targetPath;
                        } else {
                            $form_errors[] = "Failed to move uploaded file: $fileName";
                        }
                    } else {
                        $form_errors[] = "File size exceeds the limit: $fileName";
                    }
                } else {
                    $form_errors[] = "Invalid file format for: $fileName";
                }
            } else {
                $form_errors[] = "Error uploading file $fileName: " . uploadErrorToString($fileError);
            }
        }
    }

    $postcode = htmlspecialchars($_POST['postcode']);
    $stmt = $db->prepare("SELECT * FROM address_book WHERE postcode = ?");
    $stmt->execute([$postcode]);

    if ($stmt->rowCount() === 0) {
        $form_errors[] = "Your location is not supported yet.";
    }

    if (empty($form_errors)) {
        try {
            $access = 'pending';
            $user_id = $_SESSION['id'];

            $stmt = $db->prepare("UPDATE seller SET name = ?, profile_pic = ?, detail = ?, contact_number = ?, unit_number = ?, address = ?, postcode = ?, city = ?, state = ?, bank_company = ?, bank_account = ?, access = ?, image_urls = ?, latitude = ?, longitude = ? WHERE user_id = ?");

            $stmt->execute([
                htmlspecialchars($_POST['seller_name']),
                htmlspecialchars($profilePicUrl),
                htmlspecialchars($_POST['description']),
                htmlspecialchars($_POST['contact_num']),
                htmlspecialchars($_POST['unit_number']),
                htmlspecialchars($_POST['address']),
                htmlspecialchars($_POST['postcode']),
                htmlspecialchars($_POST['city']),
                htmlspecialchars($_POST['state']),
                htmlspecialchars($_POST['bank']),
                htmlspecialchars($_POST['bank_account_number']),
                $access,
                implode(',', $imageUrls),
                htmlspecialchars($_POST['latitude']),
                htmlspecialchars($_POST['longitude']),
                $user_id
            ]);

            // Fetch updated seller row to get state id and access
            $stmt = $db->prepare("SELECT id, access FROM seller WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $sellerRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($sellerRow) {
                $_SESSION['access'] = $sellerRow['access'];
                $_SESSION['seller_id'] = $sellerRow['id'];
                echo "<script>
                swal({
                  title: \"Account Activated!\",
                  text: \"Your account has been activated successfully.\",
                  icon: 'success',
                  button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../profile_management/seller_profile.php';
                }, 3000);
                </script>";
                exit;
            } else {
                $form_errors[] = "Failed to fetch updated seller data.";
            }
        } catch (PDOException $e) {
            $form_errors[] = "Failed to activate the account: " . $e->getMessage();
        }
    }

    if (!empty($form_errors)) {
        $result = count($form_errors) == 1
            ? flashMessage("There was 1 error in the form<br>")
            : flashMessage("There were " . count($form_errors) . " errors in the form <br>");
    }
}
