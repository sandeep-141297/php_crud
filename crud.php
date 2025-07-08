<?php 
    require('conn.php');

    function imageUpload($img) {
        // 2 MB = 2 * 1024 * 1024 bytes
        $maxSize = 2 * 1024 * 1024;

        // Validate file size
        if ($img['size'] > $maxSize) {
            echo "File size exceeds 2MB limit.";
            return;
        }

        $temLoc = $img['tmp_name'];
        $newName = random_int(11111,99999).$img['name'];
        $newLoc = UPLOAD_SRC.$newName;
        if(!move_uploaded_file($temLoc,$newLoc)) {
            $_SESSION['error'] = "Image upload failed! Server down.";
            header('Location: index.php');
            exit;
            //echo "file not moved";
        } else {
            return $newName;
            //echo "file moved";
        }
    }

    function imageRemove($img) {
        if(!unlink(UPLOAD_SRC.$img)) {
            $_SESSION['error'] = "Image Remove failed! Server down.";
            header('Location: index.php');
            exit;
        }
    }

    // insert data
    if(isset($_POST['addProduct'])) {
        //print_r($_POST);
        //print_r($_FILES['file']);
        foreach($_POST as $key => $value) {
            $_POST[$key] = mysqli_real_escape_string($conn,$value);
        }

        $imgPath = imageUpload($_FILES['file']);

        $query = "INSERT INTO `products`(`name`, `price`, `description`, `image`) VALUES ('$_POST[name]','$_POST[price]','$_POST[des]','$imgPath')";

        if(mysqli_query($conn, $query)) {
            //header('location: index.php?success="ProductAdded"');
            $_SESSION['success'] = "Product Added successfully!";
        } else {
            //header('location: index.php?alert="AddFailed"');
            $_SESSION['error'] = "Product Add failed! Please try again.";
        }
        header('Location: index.php');
        exit;
    }

    //delete data
    if(isset($_GET['itemDelete']) && $_GET['itemDelete'] > 0) {
        $query = "SELECT * FROM `products` WHERE `id` = '$_GET[itemDelete]'";
        $result = mysqli_query($conn, $query);
        $fetch = mysqli_fetch_assoc($result);

        imageRemove($fetch['image']);
        $query = "DELETE FROM `products` WHERE `id` = '$fetch[id]'";

        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Product Deleted successfully!";
        } else {
            $_SESSION['error'] = "Product Delete failed! Please try again.";
        }
        header('Location: index.php');
        exit;
    }

    // update data
    if(isset($_POST['editProduct'])) { 
        foreach($_POST as $key => $value) {
            $_POST[$key] = mysqli_real_escape_string($conn,$value);
        }

        if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
            $query = "SELECT * FROM `products` WHERE `id` = '$_POST[prodId]'";
            $result = mysqli_query($conn, $query);
            $fetch = mysqli_fetch_assoc($result);

            imageRemove($fetch['image']);
            $imagePath = imageUpload($_FILES['file']);

            $update = "UPDATE `products` SET `name`='$_POST[name]',`price`='$_POST[price]',`description`='$_POST[des]',`image`='$imagePath' WHERE `id` = '$_POST[prodId]'";
        } else {
            $update = "UPDATE `products` SET `name`='$_POST[name]',`price`='$_POST[price]',`description`='$_POST[des]' WHERE `id` = '$_POST[prodId]'";
        }

        if(mysqli_query($conn, $update)) {
            $_SESSION['success'] = "Product Updated successfully!";
        } else {
            $_SESSION['error'] = "Product update failed! Please try again.";
        }
        header('Location: index.php');
        exit;
    }
?>