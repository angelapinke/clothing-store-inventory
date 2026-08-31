<?php
//a boltokban levő ruhák kilistázva
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

        <h1>Boltokban lévő ruhák</h1>

        <?php
            $search = null;
             if (isset($_POST['search'])) {
                 $search = $_POST['search'];
            }
        ?>

        <form class="form-inline" method="post">
            <div class="card">
                <div class="card-body">
                    Keresés a termék neve alapján: 
                    <input style="width:600px;margin-left:1em;" class="form-control" type="search" name="search" value="<?=$search?>">
                    <button class="btn btn-primary" style="margin-left:1em;" type="submit" >Search</button>
                </div>
            </div>
        </form>


        <?php
        //kiválasztjuk az összes boltban lévő ruhát(vagyis a rendelések közül a kiszállítottakat)
        //ha több sor is van, amiben a bolt címe és a termék neve is ugyanaz, akkor abból egy sort csinál 
        //és a sorok darabszámát összeadja
        //így látjuk, hogy melyik boltban van a keresett ruha és hány darab van belőle
        //cím szerint sorrendben
            $querySelect = "SELECT distinct ruha.nev as ruhanev, ruha.marka as ruhamarka, bolt.cim as boltcim, bolt.nev as boltnev, sum(rendeles.db) as darab 
                FROM rendeles INNER JOIN bolt ON bolt.id = boltid 
                INNER JOIN ruha ON ruhaid=ruha.id  
                WHERE keszdatum is not null";
            if ($search) {
                $querySelect = $querySelect . sprintf(" and LOWER(ruha.nev) LIKE '%%%s%%' GROUP BY ruha.nev, bolt.cim ORDER BY ruhanev", mysqli_real_escape_string($link, strtolower($search)));
                $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
            }
            else{
                
                $query = "SELECT distinct ruha.nev as ruhanev, ruha.marka as ruhamarka, bolt.cim as boltcim, bolt.nev as boltnev, sum(rendeles.db) as darab FROM rendeles INNER JOIN bolt ON bolt.id = boltid INNER JOIN ruha ON ruhaid=ruha.id WHERE keszdatum is not null GROUP BY ruhanev, boltcim ORDER BY ruhanev";
                $eredmeny = mysqli_query($link, $query) or die(mysqli_error($link));
            
            }
        ?>

<table class="table table-info table-hover table-sm table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Ruhadarab neve</th>
                        <th>Márka</th>   
                        <th>Bolt</th>
                        <th>Cím</th> 
                        <th>Db</th>  
                        
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
                    <tr>
                        <td><?=$row['ruhanev']?></td>
                        <td><?=$row['ruhamarka']?></td>
                        <td><?=$row['boltnev']?></td>
                        <td><?=$row['boltcim']?></td>
                        <td><?=$row['darab']?></td>
                        
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