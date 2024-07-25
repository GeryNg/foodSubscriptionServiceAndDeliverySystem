<?php
include_once '../resource/session.php';
include_once '../resource/Database.php';
include_once '../resource/utilities.php';

if (isset($_POST['AddPlanBtn'])) {
    $form_errors = array();

    // Required fields
    $required_fields = ['plan_name', 'description', 'price', 'date_from', 'date_to'];
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

    // Fields to check length
    $fields_to_check_length = ['plan_name' => 3, 'description' => 10];
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

    // Validate numeric value for price
    $numeric_fields = ['price'];
    $form_errors = array_merge($form_errors, check_numeric($numeric_fields));

    // Validate checkboxes for sections
    if (!isset($_POST['sections']) || count($_POST['sections']) === 0) {
        $form_errors[] = "At least one section must be selected.";
    }

    // Validate date range
    $date_from = strtotime($_POST['date_from']);
    $date_to = strtotime($_POST['date_to']);
    $today = strtotime(date('Y-m-d'));

    if ($date_from < $today) {
        $form_errors[] = "Date From cannot be before today's date.";
    }

    if ($date_to < strtotime('+7 days', $date_from)) {
        $form_errors[] = "Date To must be at least 7 days after Date From.";
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
            $fileType = $_FILES['images']['type'][$i];

            if ($fileError === 0) {
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExtension, $allowedFormats)) {
                    if ($fileSize <= 2 * 1024 * 1024) { // Check for file size (e.g., 2MB limit)
                        $newFileName = uniqid('', true) . "." . $fileExtension;
                        $targetDir = "../plan/";
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
            // Default status to "active"
            $status = 'active';
            $seller_id = isset($_SESSION['seller_id']) ? $_SESSION['seller_id'] : null;
            $stmt = $db->prepare("INSERT INTO plan (plan_name, description, price, date_from, date_to, section, status, seller_id, image_urls) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                htmlspecialchars($_POST['plan_name']),
                htmlspecialchars($_POST['description']),
                htmlspecialchars($_POST['price']),
                htmlspecialchars($_POST['date_from']),
                htmlspecialchars($_POST['date_to']),
                implode(',', $_POST['sections']), // Assuming 'sections' is an array of selected values
                $status,
                $seller_id,
                implode(',', $imageUrls) // Assuming $imageUrls is an array of uploaded image paths
            ]);

            if ($stmt->rowCount() == 1) {
                echo "<script>
                swal({
                  title: \"Plan Added!\",
                  text: \"The plan has been added successfully.\",
                  icon: 'success',
                  button: \"OK\",
                });
                setTimeout(function(){
                window.location.href = '../plan_management/list_plan.php';
                }, 3000);
                </script>";
                exit;
            } else {
                $result = "Failed to save the plan. Please try again.";
            }

        } catch (PDOException $e) {
            $result = "Failed to save the plan: " . $e->getMessage();
        }
    } else {
        $result = count($form_errors) == 1
            ? flashMessage("There was 1 error in the form<br>")
            : flashMessage("There were " . count($form_errors) . " errors in the form <br>");
    }
}

function uploadErrorToString($errorCode)
{
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload';
        default:
            return 'Unknown upload error';
    }
}
?>
