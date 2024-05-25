<?php include("../template/header.php"); ?>
<?php

$bookId = (isset($_POST['bookID'])) ? $_POST['bookID'] : "";
$bookName = (isset($_POST['bookName'])) ? $_POST['bookName'] : "";
$bookCover = (isset($_FILES['bookCover'])) ? $_FILES['bookCover']['name'] : "";
$action = (isset($_POST['action'])) ? $_POST['action'] : "";

echo $bookId . "<br/>" . $bookName . "<br/>" . $bookCover . "<br/>" . $action;

?>
<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de libro
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>ID:</label>
                    <input type="text" class="form-control" name="bookID" placeholder="ID">
                </div>
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="bookName" placeholder="Book name">
                </div>
                <div class="form-group">
                    <label>Cover:</label>
                    <input type="file" class="form-control" name="bookCover" placeholder="File...">
                </div>
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" class="btn btn-success" name="action" value="save">Save</button>
                    <button type="submit" class="btn btn-warning" name="action" value="edit">Modify</button>
                    <button type="submit" class="btn btn-info" name="action" value="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Cover</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2</td>
                <td>PHP</td>
                <td>imagen.jpg</td>
                <td>Select | Delete</td>
            </tr>
        </tbody>
    </table>
</div>
<?php include("../template/footer.php"); ?>