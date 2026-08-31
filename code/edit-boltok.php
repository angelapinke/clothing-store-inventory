<?php 
include 'db.php';
$link = getDb(); 

//ha a "mentés" gombot megnyomjuk, a form értékei beírodnak változókba, majd ezek segítségével
//az adatbázisba is bekerül a változtatás
$update = false;
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $cim = mysqli_real_escape_string($link, $_POST['cim']);
    $fonok = mysqli_real_escape_string($link, $_POST['fonok']);
    
        $query = sprintf("UPDATE bolt SET nev='%s', cim='%s', fonok='%s' WHERE id=%s",
                $nev, $cim, $fonok, $id);

        mysqli_query($link, $query) or die(mysqli_error($link));
        $update = true;
    }
//ha a törlés gombot nyomjuk meg, akkor először ki kell törölnünk a rendelésben szereplő boltokat és csak
//azután a boltot, mert a rendelés a boltból (is) épült fel, így magában nem engedné törölni
//majd visszatér a boltok oldalához
else if (isset($_POST['delete'])) {
    $query1 = sprintf('DELETE FROM rendeles WHERE boltid = %s', 
        mysqli_real_escape_string($link, $_POST['id']));
    $query = sprintf('DELETE FROM bolt WHERE id = %s', 
        mysqli_real_escape_string($link, $_POST['id']));
    $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    $ret = mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: boltok.php");
    return;
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
        <?php
        //itt szerepelnek a "boltok.php" oldalról jövő kérések
            if (!isset($_GET['boltid'])) {
                header("Location: boltok.php");
                return ;
            } 
            // a boltok.php oldalról kapott "boltid" alapján kiválasztjuk a megfelelő sort az adatbázisból
            $boltid = $_GET['boltid'];
            $query = sprintf("SELECT id, nev, cim, fonok FROM bolt where id = %s", 
                mysqli_real_escape_string($link, $boltid)) or die(mysqli_error($link));
            $eredmeny = mysqli_query($link, $query);
            $row = mysqli_fetch_array($eredmeny);
            if (!$row) {
                header("Location: boltok.php");
                return;
            }
            //törlés ugyanúgy, csak most a "boltok.php" oldalról jön a kérés
            if (isset($_GET['delete'])) {
                $query = sprintf('DELETE FROM rendeles WHERE boltid = %s', 
                    mysqli_real_escape_string($link, $_GET['boltid']));
                $query1 = sprintf('DELETE FROM bolt WHERE id = %s', 
                    mysqli_real_escape_string($link, $_GET['boltid']));
                $ret = mysqli_query($link, $query) or die(mysqli_error($link));
                $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
                header("Location: boltok.php");
                return;
            }
        ?>
        <h1>Bolt adatainak módosítása</h1>
        <?php if ($update): 
        //felugró szöveg, ha az update sikeres
            ?>
        <p>
            <span class="badge badge-success">Bolt adatai sikeresen módosítva</span>
        </p>
        <?php endif; 
        //form létrehozása, megjelennek a bolt adatai a "boltid" alapján kiválasztott sor felhasználásával
        //és ezeket lehet átírni
        //a form után pedig egy vissza gomb
        ?>
        <form method="post" action="">
            <input type="hidden" name="id" id="id" value="<?=$boltid?>" />
            <div class="form-group">
                <label for="nev">Név</label>
                <input required class="form-control" name="nev" id="nev" type="text" value="<?=$row['nev']?>" />
            </div>
            <div class="form-group">
                <label for="cim">Cím</label>
                <input required class="form-control" name="cim" id="cim" type="text" value="<?=$row['cim']?>" />
            </div>
            <div class="form-group">
                <label for="fonok">Főnök</label>
                <input required class="form-control" name="fonok" id="fonok" type="text" value="<?=$row['fonok']?>" />
            </div>

            <input class="btn btn-primary" name="update" type="submit" value="Mentés" />
            <input class="btn btn-danger" name="delete" type="submit" value="Törlés" />
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