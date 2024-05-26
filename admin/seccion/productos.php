<?php include("../template/header.php"); ?>
<?php

$bookId = (isset($_POST['bookID'])) ? $_POST['bookID'] : "";
$bookName = (isset($_POST['bookName'])) ? $_POST['bookName'] : "";
$bookCover = (isset($_FILES['bookCover'])) ? $_FILES['bookCover']['name'] : "";
$action = (isset($_POST['action'])) ? $_POST['action'] : "";
$bookActionId = (isset($_POST['bookActionID'])) ? $_POST['bookActionID'] : "";

$host = "localhost";
$db = "sitio";
$user = "root";
$password = "";

include("../config/db.php");



switch ($action) {
    case 'save':

        $sentenciaSQL = $conection->prepare("INSERT INTO libros (nombre, cover) VALUES (:nombre, :cover)");
        $sentenciaSQL->bindParam(':nombre', $bookName);

        $date = new DateTime();
        $fileName = ($bookName != "") ? $date->getTimeStamp() . "_" . $bookCover : "imagen.jpg";
        $tmpImage = $_FILES['bookCover']['tmp_name'];
        if ($tmpImage != "") {
            move_uploaded_file($tmpImage, "../../img/" . $fileName);
        }

        $sentenciaSQL->bindParam(':cover', $fileName);
        $sentenciaSQL->execute();
        break;
    case 'edit':
        $sentenciaSQL = $conection->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $bookName);
        $sentenciaSQL->bindParam(':id', $bookId);
        $sentenciaSQL->execute();

        if ($bookCover != "") {

            $date = new DateTime();
            $fileName = ($bookName != "") ? $date->getTimeStamp() . "_" . $bookCover : "imagen.jpg";
            $tmpImage = $_FILES['bookCover']['tmp_name'];
            move_uploaded_file($tmpImage, "../../img/" . $fileName);

            $sentenciaSQL = $conection->prepare("SELECT cover FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $bookId);
            $sentenciaSQL->execute();
            $book = $sentenciaSQL->fetch((PDO::FETCH_LAZY));

            if (isset($book["cover"]) && ($book['cover'] != "imagen.jpg")) {
                if (file_exists("../../img/" . $book['cover'])) {
                    unlink("../../img/" . $book['cover']);
                }
            }

            $sentenciaSQL = $conection->prepare("UPDATE libros SET cover=:cover WHERE id=:id");
            $sentenciaSQL->bindParam(':cover', $fileName);
            $sentenciaSQL->bindParam(':id', $bookId);
            $sentenciaSQL->execute();
        }

        break;
    case 'cancel':
        echo "Action cancel";
        break;
    case 'Select':
        $sentenciaSQL = $conection->prepare("SELECT * FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $bookActionId);
        $sentenciaSQL->execute();
        $book = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $bookName = $book['nombre'];
        $bookCover = $book['cover'];
        break;
    case 'Delete':
        $sentenciaSQL = $conection->prepare("SELECT cover FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $bookActionId);
        $sentenciaSQL->execute();
        $book = $sentenciaSQL->fetch((PDO::FETCH_LAZY));

        if (isset($book["cover"]) && ($book['cover'] != "imagen.jpg")) {
            if (file_exists("../../img/" . $book['cover'])) {
                unlink("../../img/" . $book['cover']);
            }
        }
        $sentenciaSQL = $conection->prepare("DELETE FROM libros WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $bookActionId);
        $sentenciaSQL->execute();
        break;
}
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
                    <input type="text" class="form-control" name="bookID" value="<?php echo $bookActionId; ?>" placeholder="ID">
                </div>
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="bookName" value="<?php echo $bookName; ?>" placeholder="Book name">
                </div>
                <div class="form-group">
                    <label>Cover:<?php echo $bookCover; ?></label>
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
            <?php
            $sentenciaSQL = $conection->prepare("SELECT * FROM libros");
            $sentenciaSQL->execute();
            $bookList = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
            foreach ($bookList as $libro) { ?>
                <tr>
                    <td><?php echo $libro['id'] ?></td>
                    <td><?php echo $libro['nombre'] ?></td>
                    <td><?php echo $libro['cover'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="bookActionID" value="<?php echo $libro['id']; ?>">
                            <input type="submit" name="action" value="Select" class="btn btn-primary">
                            <input type="submit" name="action" value="Delete" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            <?php }
            ?>

        </tbody>
    </table>
</div>
<?php include("../template/footer.php"); ?>