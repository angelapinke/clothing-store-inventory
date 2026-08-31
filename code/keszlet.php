<?php
//szerkezetileg ugyanaz, mint "boltok.php", így ezt nem kommenteztem újra
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

        <h1>Rendelhető ruhák</h1>

        <?php
            $search = null;
             if (isset($_POST['search'])) {
                 $search = $_POST['search'];
            }
        ?>

        <form class="form-inline" method="post">
            <div class="card">
                <div class="card-body">
                    Keresés kategória alapján: 
                    <input style="width:600px;margin-left:1em;" class="form-control" type="search" name="search" value="<?=$search?>">
                    <button class="btn btn-secondary" style="margin-left:1em;" type="submit" >Search</button>
                </div>
            </div>
        </form>


        <?php
            $querySelect = "SELECT id, nev, marka, kateg, db FROM ruha";
            if ($search) {
                $querySelect = $querySelect . sprintf(" WHERE LOWER(kateg) LIKE '%%%s%%' ORDER BY marka", mysqli_real_escape_string($link, strtolower($search)));
                $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
            }
            else{
            $querySelect = "SELECT id, nev, marka, kateg, db FROM ruha ORDER BY marka";
            $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
            }
        ?>


            <a class="btn btn-primary" href="insert-keszlet.php">
                <i class="fa fa-plus"></i> Új áru feltöltése
            </a>

            
            <table class="table table-active table-hover table-sm table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Név</th>
                        <th>Márka</th>      
                        <th>Kategória</th>
                        <th>Mennyiség(db)</th>      
                        <th></th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($eredmeny)): ?>
                    <tr>
                        <td><?=$row['nev']?></td>
                        <td><?=$row['marka']?></td>
                        <td><?=$row['kateg']?></td>
                        <td><?=$row['db']?></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="edit-keszlet.php?ruhaid=<?=$row['id']?>">
                                <i class="fa fa-edit"></i> Szerkesztés
                            </a>
                        </td>

                        <td>
                        <a class="btn btn-danger btn-sm" href="edit-keszlet.php?ruhaid=<?=$row['id']?>&delete">
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