<?php 
//szerkezetileg ugyanaz, mint az "insert-boltok.php"
include 'db.php';
$link = getDb();

$created = false;
if (isset($_POST['create'])) {
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $marka = mysqli_real_escape_string($link, $_POST['marka']);
    $kateg = mysqli_real_escape_string($link, $_POST['kateg']);
    $db = mysqli_real_escape_string($link, $_POST['db']);

    $createQuery = sprintf("INSERT INTO ruha(nev, marka, kateg, db) VALUES ('%s', '%s', '%s', '%d')",
        $nev,
        $marka,
        $kateg,
        $db
    );
    mysqli_query($link, $createQuery) or die(mysqli_error($link));
    $created = true;
}
?>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="margin.css">
    <title>Csodakezelő</title>
</head>
<body>
    <?php include 'menu.html'; ?>
    <div class="container main-content">
    
        <form action="insert-keszlet.php" method="post">
        <h1>Új termék hozzáadása</h1>
        <?php if ($created): ?>
            <span class="badge badge-success">Sikeresen hozzáadva</span>
        <?php endif; ?>

        <div class="form-group">
                <label for="nev">Név</label>
                <input required class="form-control" name="nev" id="nev" type="text"  />
            </div>
            <div class="form-group">
                <label for="marka">Márka</label>
                <input required class="form-control" name="marka" id="marka" type="text"  />
            </div>
            <div class="form-group">
                <label for="kateg">Kategória</label>
                <input required class="form-control" name="kateg" id="kateg" type="text"  />
            </div>
            <div class="form-group">
                <label for="db">Mennyiség(db)</label>
                <input required class="form-control" name="db" id="db" type="number"  />
            </div>
             
            <input class="btn btn-primary" name="create" type="submit" value="Létrehozás" />
            
        </form>
            
            <a class="btn btn-secondary" href="keszlet.php">
                <i class="fa fa-arrow-left"></i> Vissza
            </a>

        <?php
            closeDb($link);
        ?>
    </div>
</body>
</html>