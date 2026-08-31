<?php 
include 'db.php';
$link = getDb();

//megvizsgálja, hogy hány darabot szeretnénk rendelni és csak akkor hajtja végre, ha van annyi készleten
$create = false;
$rendelt_db=null;
$keszletdb_row=null;
if (isset($_POST['create'])) {
    $boltid = mysqli_real_escape_string($link, $_POST['boltid']);
    $ruhaid = mysqli_real_escape_string($link, $_POST['ruhaid']);
    $rendelt_db = mysqli_real_escape_string($link, $_POST['db']);
    $query2="SELECT db from ruha where id=$ruhaid";
    $query3 = mysqli_query($link, $query2) or die(mysqli_error($link));
    $keszletdb_row = mysqli_fetch_array($query3);

    if($rendelt_db<=$keszletdb_row['db']){

    $query = sprintf("INSERT INTO rendeles (boltid, ruhaid, db, datum) VALUES (%s, %s, %s, curdate())", $boltid, $ruhaid, $rendelt_db);
    mysqli_query($link, $query) or die(mysqli_error($link));

    $maradek=$keszletdb_row['db']-$rendelt_db;
    $query = sprintf("UPDATE ruha SET db='%s' WHERE id=%s",
                $maradek, $ruhaid);
    mysqli_query($link, $query) or die(mysqli_error($link));
    $create = true;
    }
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
    
        <form action="insert-rendeles.php" method="post">
        <h1>Új rendelés hozzáadása</h1>
        <?php
        //sikeres hozzáadás jelzése
         if ($create): ?>
            <span class="badge badge-success">Sikeresen hozzáadva</span>
        <?php endif; ?>
        <?php 
        //hibaüzenet
        if ($rendelt_db>$keszletdb_row['db']): ?>
            <span class="badge badge-danger">Nem sikerült hozzáadni, nincs ennyi készleten</span>
        <?php endif; 

        //a formba itt nem kell beírni, amit szeretnénk, hanem csak ki kell választani a lehetőségek közül
        //az összes ID-hez tartotó ruhát és boltot megjeleníti, viszont az ID-hez tartozó nevet jeleníti meg, 
        //zárójelben a címmel vagy a márkával, az ID nem látható
        //a mennyiséget viszont be kell írni
        //a dátum alapból mai dátummal jön létre, viszont szerkesztésnél meg lehet változtatni
        ?>
        <div class="form-group">
                            <label for='boltid'>Megrendelő</label>
                            <select class="form-control" name='boltid' id='boltid'>
                            <?php
                                $queryBoltok = 'SELECT id, nev, cim FROM bolt';
                                $resultQueryBoltok = mysqli_query($link, $queryBoltok) or die(mysqli_error($link));
                                while ($rowBolt = mysqli_fetch_array($resultQueryBoltok)):
                            ?>
                                <option value="<?=$rowBolt['id']?>"><?=$rowBolt['nev']?>(<?=$rowBolt['cim']?>)</option>
                            <?php endwhile; ?>
                            </select>
                        </div>

                         <div class="form-group">
                            <label for='ruhaid'>Ruhadarab neve</label>
                            <select class="form-control" name='ruhaid' id='ruhaid'>
                            <?php
                                $queryRuhak = 'SELECT id, nev, marka FROM ruha WHERE id NOT IN (SELECT ruhaid FROM rendeles WHERE db IS NULL)';
                                $resultQueryRuhak = mysqli_query($link, $queryRuhak) or die(mysqli_error($link));
                                while ($rowRuha = mysqli_fetch_array($resultQueryRuhak)):
                            ?>
                                <option value="<?=$rowRuha['id']?>"><?=$rowRuha['nev']?>(<?=$rowRuha['marka']?>)</option>
                            <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="db">Mennyiség(db)</label>
                            <input required class="form-control" name="db" id="db" type="number" />
                        </div>
                    
                        <input class="btn btn-primary" name="create" type="submit" value="Létrehozás mai dátummal" />
                      
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
