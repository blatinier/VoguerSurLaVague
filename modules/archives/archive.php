<?php

$moisfr = array(
    "01" => "Janvier",
    "02" => "Février",
    "03" => "Mars",
    "04" => "Avril",
    "05" => "Mai",
    "06" => "Juin",
    "07" => "Juillet",
    "08" => "Août",
    "09" => "Septembre",
    "10" => "Octobre",
    "11" => "Novembre",
    "12" => "Décembre");

if (!empty($_SESSION['ok']) && $_SESSION['ok']==1) {
    $whereclause = "1";
}
else {
    $whereclause = "pubdate < NOW()";
}
$query = "SELECT DISTINCT
            id,
            titre, 
            DATE_FORMAT(pubdate,'%Y') AS annee,
            DATE_FORMAT(pubdate,'%m') AS mois
        FROM mellismelau_articles 
        WHERE ".$whereclause."
        ORDER BY pubdate DESC";
$req = mysql_query($query)or die(mysql_error());

$annee = array();
$mois = array();
$archive = array();

while($res = mysql_fetch_assoc($req)){
    $y = $res['annee'];
    $m = $res['mois'];
    if(!in_array($y, $annee)){
        $annee[] = $y;
        $mois[$y] = array();
    }
    if(!in_array($m,$mois[$y])){
        $mois[$y][] = $m;
    }
    $archive[$y][$m][] = $res['id'];
    $archive_titre[$res['id']] = $res['titre'];
}

if(!empty($_GET['y']) && $_GET['y']){
    $req_year = $_GET['y'];
    $_SESSION['archive_y'] = $_GET['y'];
}
elseif(!empty($_SESSION['archive_y']) && $_SESSION['archive_y']){
    $req_year = $_SESSION['archive_y'];
}
else{
    $req_year = $annee[0];
}

if(!empty($_GET['m']) && $_GET['m']){
    $req_mounth = $_GET['m'];
    $_SESSION['archive_m'] = $_GET['m'];
}
elseif(!empty($_SESSION['archive_m']) && $_SESSION['archive_m']){
    $req_mounth = $_SESSION['archive_m'];
}
else{
    $req_mounth = $mois[$req_year][0];
}

echo '<ul class="archive_root">';
foreach ($mois as $year => $mounths) {
    echo '<li class="archive">
            <a href="index.php?y='.$year.'#archiveroot">'.$year.'</a>
        </li>';
    if ($year == $req_year) {
        echo '<ul class="archive_mounth">';
        foreach ($mounths as $m) {
            if ($m == $req_mounth) {
                $dispm = "block";
            }
            else {
                $dispm = "none";
            }

            echo '<li class="archive">
                    <a href="index.php?y='.$req_year.'&amp;m='.$m.'#archiveroot">'.$moisfr[$m].'</a>
                    <div id="m'.$m.'" style="display:'.$dispm.'"><ul class="archive">
            </ul></div></li>';
        }
        echo '</ul>';
    }
}
echo '</ul>';

?>
