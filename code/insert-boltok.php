<?php 
include 'db.php';
$link = getDb();

//ha a "create" gomb meg lett nyomva, akkor a beírt értékeket betöltjük változókba
$created = false;
if (isset($_POST['create'])) {
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $cim = mysqli_real_escape_string($link, $_POST['cim']);
    $fonok = mysqli_real_escape_string($link, $_POST['fonok']);
    
    //a változók beírása az adatbázisba
    $createQuery = sprintf("INSERT INTO bolt(nev, cim, fonok) VALUES ('%s', '%s', '%s')",
        $nev,
        $cim,
        $fonok
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
    
        <form action="insert-boltok.php" method="post">
        <h1>Új bolt hozzáadása</h1>
        <?php if ($created): ?>
            <span class="badge badge-success">Sikeresen hozzáadva</span>
        <?php endif; 
        //minden adatot ki kell hogy töltsünk, valamint a form után itt is van vissza gomb
        ?>
                        <div class="form-group">
                            <label for="nev">Név</label>
                            <input required class="form-control" name="nev" id="nev" type="text" />
                        </div>
                        <div class="form-group">
                            <label for="cim">Cím</label>
                            <input required class="form-control" name="cim" id="cim" type="text"  />
                        </div>
                        <div class="form-group">
                            <label for="fonok">Főnök</label>
                            <input required class="form-control" name="fonok" id="fonok" type="text" />
                        </div>
                    
                        <input class="btn btn-primary" name="create" type="submit" value="Létrehozás" />
            </form>

            <a class="btn btn-secondary" href="boltok.php">
                <i class="fa fa-arrow-left"></i> Vissza
            </a>

        <?php
            closeDb($link);
        ?>
    </div>
</body>
</html>