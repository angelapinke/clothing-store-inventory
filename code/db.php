<?php
//függvények az adatbázishoz való kapcsolódásra és "lekapcsolódásra"
function getDb() {
    $link = mysqli_connect("localhost", "root", "") 
           or die("Kapcsolódási hiba: " . mysqli_error());
    mysqli_select_db($link, "hazi");
    //biztosítja, hogy az ékezetes betűkkel se legyen gond
    mysqli_query ($link, "set character_set_results='utf8'");
    mysqli_query ($link, "set character_set_client='utf8'");
    return $link;   
}

function closeDb($link) {
    mysqli_close($link);
}


?>