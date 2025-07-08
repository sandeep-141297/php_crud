<?php
    require("conn.php");
    //print_r($_SERVER);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  </head>
  <body class="bg-light">
    <div class="container bg-dark text-light p-3 my-4">
        <div class="d-flex align-items-center justify-content-between px-3">
            <h2><a href="index.php" class="text-white text-decoration-none"> <i class="bi bi-basket"></i> Product Store</a></h2>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addProduct">
              <i class="bi bi-plus"></i> Add Product
            </button>
        </div>
    </div>

    <div class="container">
      <?php 
        /*if(isset($_GET['alert'])) {
          if($_GET['alert'] == 'imgUpload') {
            echo<<<alert
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Image Upload Failed!</strong> Server Down,Please try after some time.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            alert;
          }
        } else if(isset($_GET['success'])) {
        }*/
      ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success:</strong> <?= $_SESSION['success'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error:</strong> <?= $_SESSION['error'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
    </div>

    <div class="container mt-3 p-0">
      <table class="table table-striped table-hover text-center">
        <thead class="table-dark text-light">
          <tr>
            <th width="10%" scope="col" class="rounded-start">Product Id</th>
            <th width="15%" scope="col">Image</th>
            <th width="10%" scope="col">Name</th>
            <th width="10%" scope="col">Price</th>
            <th width="35%" scope="col">Description</th>
            <th width="20%" scope="col" class="rounded-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $query = "SELECT * FROM `products`";
            $getData = mysqli_query($conn,$query);
            $i = 1;
            $fetchSrc = FETCH_SRC;

            while($fetch = mysqli_fetch_assoc($getData)) {
              $priceFormatted = number_format($fetch['price'], 2);
              echo <<<product
                <tr class="align-middle">
                  <th scope="row">$i</th>
                  <td><img src="$fetchSrc$fetch[image]" width="150px"/></td>
                  <td>$fetch[name]</td>
                  <td>₹$priceFormatted</td>
                  <td>$fetch[description]</td>
                  <td>
                    <a href="?edit=$fetch[id]" class="btn btn-warning me-2"><i class="bi bi-pencil-square"></i></a>
                    <button onclick="confirmTrash($fetch[id])" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></button>
                  </td>
                </tr>
              product;
              $i++;
            }
          ?>
        
        
        </tbody>
      </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <script>
      function confirmTrash(id) {
        if(confirm("Are you sure, you want to delete this item")){
          window.location.href = 'crud.php?itemDelete='+id;
        }
      }
    </script>

    <!-- Add Modal -->
    <div class="modal fade" id="addProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="crud.php" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="addProductLabel">Add Product</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="input-group mb-3">
                <span class="input-group-text">Product Name</span>
                <input type="text" class="form-control" placeholder="Enter Product Name" name="name" id="name" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">Price</span>
                <input type="number" class="form-control" placeholder="Enter Product Price" name="price" min="1" step="0.01" id="price" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">Description</span>
                <textarea class="form-control" name="des" required></textarea>
              </div>
              <div class="input-group mb-3">
                <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png,.svg" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-success" name="addProduct">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form action="crud.php" method="post" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="editProductLabel">Edit Product</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="input-group mb-3">
                <span class="input-group-text">Product Name</span>
                <input type="text" class="form-control" placeholder="Enter Product Name" id="editName" name="name" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">Price</span>
                <input type="number" class="form-control" placeholder="Enter Product Price" id="editPrice" min="1" step="0.01" name="price" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text">Description</span>
                <textarea class="form-control" name="des" id="editDesc" required></textarea>
              </div>
              <img src="" alt="showImage" id="imageShow" width="100%" class="mb-3"><br>
              <div class="input-group mb-3">
                <input type="file" class="form-control" id="editFile" name="file" accept=".jpg,.jpeg,.png,.svg">
              </div>
            </div>
            <input type="hidden" id="editId" name="prodId">
            <div class="modal-footer">
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-outline-success" name="editProduct">Edit</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <?php 
      if(isset($_GET['edit']) && $_GET['edit'] > 0) {
        $query = "SELECT * FROM `products` WHERE `id` = '$_GET[edit]'";
        $result = mysqli_query($conn, $query);
        $fetch = mysqli_fetch_assoc($result);
        echo "
          <script>
            const editProduct = new bootstrap.Modal('#editProduct', {
              keyboard: false
            });
            document.querySelector('#editName').value=`$fetch[name]`;
            document.querySelector('#editPrice').value=`$fetch[price]`;
            document.querySelector('#editDesc').value=`$fetch[description]`;
            document.querySelector('#imageShow').src=`$fetchSrc$fetch[image]`;
            document.querySelector('#editId').value=`$fetch[id]`;
            editProduct.show();
          </script>
        ";
      }
    ?>

  </body>
</html>