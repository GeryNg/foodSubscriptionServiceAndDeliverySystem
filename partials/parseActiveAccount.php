<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if (isset($_POST['activeAccountBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = ['seller_name', 'description', 'contact_num', 'address', 'bank', 'bank_account_number'];
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = ['seller_name' => 3, 'description' => 10, 'bank_account_number' => 10];
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Validate numeric value for bank account number
    $numeric_fields = ['bank_account_number'];
    $form_errors = array_merge($form_errors, check_numeric($numeric_fields));

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
            $fileType = $_FILES['images']['type'][$i];

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

    if (empty($form_errors)) {
        try {
            $status = 'pending';
            $user_id = $_SESSION['id']; // Fetching the user ID from the session

            $stmt = $db->prepare("UPDATE seller SET name = ?, detail = ?, contact_number = ?, address = ?, bank_company = ?, bank_account = ?, status = ?, image_urls = ? WHERE user_id = ?");

            $stmt->execute([
                htmlspecialchars($_POST['seller_name']),
                htmlspecialchars($_POST['description']),
                htmlspecialchars($_POST['contact_num']),
                htmlspecialchars($_POST['address']),
                htmlspecialchars($_POST['bank']),
                htmlspecialchars($_POST['bank_account_number']),
                $status,
                implode(',', $imageUrls),
                $user_id // Update the row with the corresponding user_id
            ]);

            // Fetch updated seller row to get id and status
            $stmt = $db->prepare("SELECT id, status FROM seller WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $sellerRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($sellerRow) {
                $_SESSION['status'] = $sellerRow['status'];
                $_SESSION['seller_id'] = $sellerRow['id'];

                echo "<script>
                Swal.fire({
                  title: 'Account Activated!',
                  text: 'Your account has been activated successfully.',
                  icon: 'success',
                  button: 'OK'
                });
                setTimeout(function(){
                  window.location.href = '../profile_management/seller_profile.php';
                }, 1000);
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
?>

