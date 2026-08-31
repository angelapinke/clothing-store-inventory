<?php 
//szerkezetileg ugyanaz mint az "edit-boltok.php" így ezt nem kommenteztem
include 'db.php';
$link = getDb(); 

$update = false;
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $nev = mysqli_real_escape_string($link, $_POST['nev']);
    $marka = mysqli_real_escape_string($link, $_POST['marka']);
    $kateg = mysqli_real_escape_string($link, $_POST['kateg']);
    $db = mysqli_real_escape_string($link, $_POST['db']);
    
        $query = sprintf("UPDATE ruha SET nev='%s', marka='%s', kateg='%s', db='%s' WHERE id=%s",
                $nev, $marka, $kateg, $db, $id);

        mysqli_query($link, $query) or die(mysqli_error($link));
        $update = true;
    }

else if (isset($_POST['delete'])) {
    $query1 = sprintf('DELETE FROM rendeles WHERE ruhaid = %s', 
        mysqli_real_escape_string($link, $_POST['id']));
    $query = sprintf('DELETE FROM ruha WHERE id = %s', 
        mysqli_real_escape_string($link, $_POST['id']));
    $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    $ret = mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: keszlet.php");
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
            if (!isset($_GET['ruhaid'])) {
                header("Location: keszlet.php");
                return;
            } 
            $ruhaid = $_GET['ruhaid'];
            $query = sprintf("SELECT id, nev, marka, kateg, db FROM ruha where id = %s", 
                mysqli_real_escape_string($link, $ruhaid));
            $eredmeny = mysqli_query($link, $query) or die(mysqli_error($link));
            $row = mysqli_fetch_array($eredmeny);
            if (!$row) {
                header("Location: keszlet.php");
                return;
            }
            if (isset($_GET['delete'])) {
                $query1 = sprintf('DELETE FROM rendeles WHERE ruhaid = %s', 
                    mysqli_real_escape_string($link, $_GET['ruhaid']));
                $query2 = sprintf('DELETE FROM ruha WHERE id = %s', 
                    mysqli_real_escape_string($link, $_GET['ruhaid']));
                $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
                $ret2 = mysqli_query($link, $query2) or die(mysqli_error($link));
                header("Location: keszlet.php");
                return;
            }
        ?>
        <h1>Ruha adatainak módosítása</h1>
        <?php if ($update): ?>
            <span class="badge badge-success">A ruha adatai sikeresen módosítva</span>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="id" id="id" value="<?=$ruhaid?>" />
            <div class="form-group">
                <label for="nev">Név</label>
                <input class="form-control" name="nev" id="nev" type="text" value="<?=$row['nev']?>" />
            </div>
            <div class="form-group">
                <label for="marka">Márka</label>
                <input required class="form-control" name="marka" id="marka" type="text" value="<?=$row['marka']?>" />
            </div>
            <div class="form-group">
                <label for="kateg">Kategória</label>
                <input class="form-control" name="kateg" id="kateg" type="text" value="<?=$row['kateg']?>" />
            </div>
            <div class="form-group">
                <label for="db">Mennyiség(db)</label>
                <input class="form-control" name="db" id="db" type="text" value="<?=$row['db']?>" />
            </div>
             
            <input class="btn btn-primary" name="update" type="submit" value="Mentés" />
            <input class="btn btn-danger" name="delete" type="submit" value="Törlés" />
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