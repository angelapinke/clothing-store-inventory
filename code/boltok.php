<?php
include 'db.php';
$link = getDb(); 
//adatbázisra kapcsolódás
//majd stíluselemek beszúrása hivatkozásokkal
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

        <h1>Boltok</h1>

        <?php
        //"main-content" jelzi, hogy innentől kezdődik az oldal fő része, a "container" a bootstraphez.. 
        //kötődik, fix szélességűvé teszi az oldalt, nem megy ki a tartalom azon kívülre
        //itt pedig fv. arra, hogyha vki megnyomja a "keresés" gombot, akkor változóba tölti a keresett dolgot
            $search = null;
             if (isset($_POST['search'])) {
                 $search = $_POST['search'];
            }

        //a "keresés" form megalkotása
        ?>
        <form class="form-inline" method="post">
            <div class="card">
                <div class="card-body">
                    Keresés a bolt neve alapján: 
                    <input style="width:600px;margin-left:1em;" class="form-control" type="search" name="search" value="<?=$search?>">
                    <button class="btn btn-secondary" style="margin-left:1em;" type="submit" >Search</button>
                </div>
            </div>
        </form>


        <?php
        //átalakítja kisbetűssé a beírt szöveget és az adatbázisban lévőt is, majd így hasonlítja össze és..
        //írja ki az egyezőt
            $querySelect = "SELECT id, nev, cim, fonok FROM bolt ";
            if ($search) {
                $querySelect = $querySelect . sprintf(" WHERE LOWER(nev) LIKE '%%%s%%' ORDER BY cim", mysqli_real_escape_string($link, strtolower($search)));
                $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
            }
        // "else"-t amiatt hoztam létre, hogy keresés nélkül is rendezve legyen a táblázat    
            else{
                $querySelect = "SELECT id, nev, cim, fonok FROM bolt ORDER BY nev";
                $eredmeny = mysqli_query($link, $querySelect) or die(mysqli_error($link));
            }

            //új bolt hozzáadása gomb
        ?>
            <a class="btn btn-primary" href="insert-boltok.php">
                <i class="fa fa-plus"></i> Új bolt hozzáadása
            </a>

            <table class="table table-bordered table-active table-sm table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Név</th>     
                        <th>Cím</th>
                        <th>Főnök</th>
                        <th></th>
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php 
                //táblázat létrehozása, majd feltöltése
                while ($row = mysqli_fetch_array($eredmeny)): ?>
                    <tr>
                        <td><?=$row['nev']?></td>
                        <td><?=$row['cim']?></td>
                        <td><?=$row['fonok']?></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="edit-boltok.php?boltid=<?=$row['id']?>">
                                <i class="fa fa-edit"></i> Szerkesztés
                            </a>
                        </td>

                        <td>
                        <a class="btn btn-danger btn-sm" href="edit-boltok.php?boltid=<?=$row['id']?>&delete">
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