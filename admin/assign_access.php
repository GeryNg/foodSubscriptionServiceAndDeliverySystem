<?php
$page_title = "View Assign List Information";
include_once '../partials/admin_nav.php';
include_once '../resource/Database.php';
include_once '../resource/session.php';

if (isset($_GET['seller_id'])) {
    $seller_id = $_GET['seller_id'];

    $query = "SELECT * FROM seller WHERE id = :seller_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':seller_id', $seller_id, PDO::PARAM_INT);
    $stmt->execute();
    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($seller) {
        $images = explode(',', $seller['image_urls']);
    } else {
        echo "<p>Seller not found.</p>";
        exit;
    }
} else {
    echo "<p>No seller selected.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .container {
            background: white;
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        label {
            margin-bottom: 10px;
            display: block;
            color: #666;
            font-weight: 1000;
        }

        .button {
            background-color: #5C67F2;
            color: white;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            float: right;
            margin-top: 10px;
            margin-left: 20px;
            font-weight: 600;
        }

        form {
            overflow: auto;
        }

        .documents-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        .document-image {
            max-width: 200px;
            max-height: 200px;
            margin-bottom: 10px;
            margin: 0;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .document-image:hover {
            transform: scale(1.05);
        }

        img {
            max-width: 200px;
            margin-bottom: 10px;
            cursor: pointer;
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
<h1 class="h3 mb-2 text-gray-800" style="margin: 20px;">Assign Table</h1><hr/>
    <div class="container">
        <h1>Seller Basic Information</h1><br />
        <form action="update_seller_access.php" method="post">
            <img src="<?php echo htmlspecialchars($seller['profile_pic']); ?>" alt="Seller Profile Picture" onclick="openModal(this)"><br>
            <label>Seller Name: </label>
            <p><?php echo htmlspecialchars($seller['name']); ?></p><br>
            <label>Detail: </label>
            <p><?php echo htmlspecialchars($seller['detail']); ?></p><br>
            <label>Contact Number: </label>
            <p><?php echo htmlspecialchars($seller['contact_number']); ?></p><br>
            <label>Address: </label>
            <p><?php echo htmlspecialchars($seller['address']); ?></p><br>
            <label>Bank Company: </label>
            <p><?php echo htmlspecialchars($seller['bank_company']); ?></p><br>
            <label>Bank Account: </label>
            <p><?php echo htmlspecialchars($seller['bank_account']); ?></p><br>
            <label>Document:</label><br>
            <div id="documentImagesField" class="documents-container">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Document Image" class="document-image" onclick="openModal(this)"><br>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($seller_id); ?>">
            <button type="submit" name="AcceptBtn" value="verify" class="button" style="background-color: #529E2F;">Accept</button>
            <button type="submit" name="RejectBtn" value="rejected" class="button" style="background-color: #B5281B;">Reject</button>
        </form>
    </div>
    <br />
    <br />
    <br />

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
        <div id="caption"></div>
    </div>

    <script>
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

</body>

</html>