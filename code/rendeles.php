<?php
//csak a "boltok.php"-tól különböző részeket kommentezem
include 'db.php';
$link = getDb(); 
?>

<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="margin.css">
    <title>Csodakezelő</title>
</head>
<body>
    <?php include 'menu.html'; ?>

    <div class="container main-content">

        <h1>Rendelések</h1>
        
        <?php
            
            $query = "SELECT rendeles.id, boltid, ruhaid, datum, rendeles.db as mennyiseg, ruha.nev as ruhanev, ruha.marka as ruhamarka, bolt.cim as boltcim, bolt.nev as boltnev, keszdatum 
            FROM rendeles INNER JOIN bolt ON bolt.id = boltid 
            INNER JOIN ruha ON ruhaid=ruha.id 
            ORDER BY datum";

            $eredmeny = mysqli_query($link, $query) or die(mysqli_error($link));
        ?>

            <a class="btn btn-primary" href="insert-rendeles.php">
                <i class="fa fa-plus"></i> Új rendelés hozzáadása
            </a>

            <table class="table table-active table-hover table-sm table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Megrendelő</th>
                        <th>Címe</th>   
                        <th>Ruhadarab neve</th>
                        <th>Márka</th>   
                        <th>Db</th>
                        <th>Megrendelés dátuma</th>    
                        <th>Kiszállítás dátuma</th>  
                        <th></th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
                    <tr>
                        <td><?=$row['boltnev']?></td>
                        <td><?=$row['boltcim']?></td>
                        <td><?=$row['ruhanev']?></td>
                        <td><?=$row['ruhamarka']?></td>
                        <td><?=$row['mennyiseg']?></td>
                        <td><?=$row['datum']?></td>

                        <td>
                            <?php
                            //hogyha nincsen kiszállítási dátum, akkor a rendelés folyamatban van
                            //ekkor megjelenik egy gomb, amire rá lehet nyomni ha ki lett szállítva a rendelés
                            //ekkor a mai dátum íródik oda
                            if ($row['keszdatum']): ?>
                                <?=$row['keszdatum']?>
                            <?php else: ?>
                                <a class="btn btn-secondary btn-sm" href="edit-rendeles.php?rendelesid=<?=$row['id']?>&retCurrDate">
                                    <i class="fa fa-truck"></i> Kiszállítva
                                </a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a class="btn btn-info btn-sm" href="edit-rendeles.php?rendelesid=<?=$row['id']?>">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td>
                        <a class="btn btn-danger btn-sm" href="edit-rendeles.php?rendelesid=<?=$row['id']?>&delete">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                         
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
            </table>

            <?php
                closeDb($link);
            ?>
    </div>
</body>
</html>