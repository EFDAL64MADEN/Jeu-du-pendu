<?php 

include('pendu.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Pendu</title>
    <title>Document</title>
</head>
<body>
<div class='alert alert-dismissible alert-success'>
    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    <strong>Tu as gagné petit padawan!</strong>
</div>
<figure class="win">
    <img src="./img/youpi.gif" alt="victoire">
</figure>
<div class="boutons">
    <div class='bouton'><a class='restart-link' href='./restart.php'>Recommencer</a></div>
</div>
</body>
</html>