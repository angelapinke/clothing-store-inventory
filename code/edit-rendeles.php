<?php 
//hasonló, mint "edit-boltok.php" így csak az új dolgokat kommenteztem benne
include 'db.php';
$link = getDb(); 

//rendelés szerkesztésénél fontos figyelembe venni, hogy nem változtathatjuk nagyobb értékre a rendelést, mint
//amennyi a készletben rendelkezésünkre áll
$update = false;
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($link, $_POST['id']);
    $datum = mysqli_real_escape_string($link, $_POST['datum']);
    $ruhaid = mysqli_real_escape_string($link, $_POST['ruhaid']);
    $ujdb = mysqli_real_escape_string($link, $_POST['mennyiseg']);
    $kdatum = mysqli_real_escape_string($link, $_POST['kdatum']);

    $query2="SELECT db from ruha where id=$ruhaid";
    $query3 = mysqli_query($link, $query2) or die(mysqli_error($link));
    $keszletdb_row = mysqli_fetch_array($query3);

    $query2="SELECT db from rendeles where id=$id";
    $query3 = mysqli_query($link, $query2) or die(mysqli_error($link));
    $rendelesdb_row = mysqli_fetch_array($query3);

    $osszes=$keszletdb_row['db']+$rendelesdb_row['db'];

    if($osszes>=$ujdb){

    $updated_db=$osszes-$ujdb;
    $query = sprintf("UPDATE rendeles SET db='%s' WHERE id=%s",
            $ujdb, $id);
    mysqli_query($link, $query) or die(mysqli_error($link));

    $query = sprintf("UPDATE ruha SET db='%s' WHERE id=%s",
            $updated_db, $ruhaid);
    mysqli_query($link, $query) or die(mysqli_error($link));

    }
    else{
        die("Nincs annyi készleten");
    }

    $query = sprintf("UPDATE rendeles SET datum='%s' ,keszdatum='%s' WHERE id=%s",
            $datum, $kdatum, $id);
    mysqli_query($link, $query) or die(mysqli_error($link));
    $update = true;

} else if (isset($_POST['delete'])) {
    $query1 = sprintf('DELETE FROM rendeles WHERE id = %s', 
        mysqli_real_escape_string($link, $_POST['id']));
    $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    header("Location: rendeles.php");
    return;
} else if (isset($_GET['delete'])) {
    $query1 = sprintf('DELETE FROM rendeles WHERE id = %s', 
        mysqli_real_escape_string($link, $_GET['rendelesid']));
    $ret1 = mysqli_query($link, $query1) or die(mysqli_error($link));
    header("Location: rendeles.php");
    return;
}
//itt történik a mai dátum visszaadása a "kiszállítva" gomb megnyomásánál, vagyis a mai dátum adatbázisba írása
if (isset($_GET['retCurrDate'])) {
    $id = mysqli_real_escape_string($link, $_GET['rendelesid']);
    $query = sprintf("UPDATE rendeles SET keszdatum=curdate() WHERE id=%s",
            $id);
    mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: rendeles.php");
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
        //a kapott "rendelesid" alapján a megfelelő sor betöltése változóba
            if (!isset($_GET['rendelesid'])) {
                die('Nincs megadva rendelés azonosító');
                return;
            } 
            $rendelesid = $_GET['rendelesid'];
            $query = sprintf("SELECT rendeles.id as id, boltid, bolt.nev as boltnev, bolt.cim as boltcim, ruhaid, ruha.marka as ruhamarka, rendeles.db as mennyiseg, datum, ruha.nev as ruhanev, keszdatum FROM rendeles INNER JOIN ruha ON ruha.id = rendeles.ruhaid INNER JOIN bolt ON bolt.id = rendeles.boltid where rendeles.id = %s", 
                mysqli_real_escape_string($link, $rendelesid));
            $eredmeny = mysqli_query($link, $query) or die(mysqli_error($link));
            $row = mysqli_fetch_array($eredmeny);
            if (!$row) {
                die('Nincs ilyen azonosítójú rendelés');
                return;
            }
        ?>
        <h1>Rendelés adatai</h1>
        <?php if ($update): 
        //a formban a darabszám number és a dátum date típusú, így nem engedi, hogy érvénytelen adat kerüljön oda
        //valamint a "nem rendelés" tábla adatokat readonly típusúvá tettem, igy azok nem változtathatóak
            ?>
        <p>
            <span class="badge badge-success">Rendelés módosítva</span>
        </p>
        <?php endif; ?>
        <form method="post" action="edit-rendeles.php?rendelesid=<?=$rendelesid?>">
            <input type="hidden" name="id" id="id" value="<?=$rendelesid?>" />
            <input type="hidden" name="ruhaid" id="ruhaid" value="<?=$row['ruhaid']?>" />
            <div class="form-group">
                <label for='boltnev'>Megrendelő</label>
                <input id="boltnev" class="form-control" readonly type="text" value="<?=$row['boltnev']?>" />
            </div>

             <div class="form-group">
                <label for='boltcim'>Címe</label>
                <input class="form-control" readonly type="text" id="boltcim" value="<?=$row['boltcim']?>" />
            </div>

            <div class="form-group">
                <label for='ruhanev'>Ruhadarab neve</label>
                <input class="form-control" readonly type="text" id="ruhanev" value="<?=$row['ruhanev']?>" />
            </div>

            <div class="form-group">
                <label for='ruhamarka'>Márka</label>
                <input class="form-control" readonly type="text" id="ruhamarka" value="<?=$row['ruhamarka']?>" />
            </div>

            <div class="form-group">
                <label for='mennyiseg'>Mennyiség(db)</label>
                <input class="form-control" name="mennyiseg" type="number" id="mennyiseg" value="<?=$row['mennyiseg']?>" />
            </div>

             <div class="form-group">
                <label for='datum'>Megrendelés dátuma</label>
                <input class="form-control" name="datum" type="date" id="datum" value="<?=$row['datum']?>" />
            </div>

            <div class="form-group">
                <label for='kdatum'>Kiszállitás dátuma</label>
                <input class="form-control" name="kdatum" type="date" id="kdatum" value="<?=$row['keszdatum']?>" />
            </div>
            
            <input class="btn btn-primary" name="update" type="submit" value="Mentés" />
            <input class="btn btn-danger" name="delete" type="submit" value="Törlés" />
        </form>

        <a class="btn btn-secondary" href="rendeles.php">
                <i class="fa fa-arrow-left"></i> Vissza
            </a>

        <?php
            closeDb($link);
        ?>
    </div>
</body>
</html>