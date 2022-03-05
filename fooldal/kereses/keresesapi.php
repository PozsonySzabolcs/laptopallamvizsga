<?php
session_start();
require_once "../connection.php";


$action = isset($_GET['action']) ? $_GET['action'] : "";
$action2 = isset($_POST['action']) ? $_POST['action'] : "";
if (isset($con)) {
    $con;
}


switch ($action) {

    case "listazas" :
        $privateid = $_SESSION['userid'];

        $sql = "SELECT idhouse, addres, totalhprice, ownPerson, ownMobil, users_id ,housedate FROM house WHERE users_id = '$privateid' ";
        $result = mysqli_query($con, $sql);
        while ($obj = $result->fetch_assoc()) {
            $res[] = $obj;
        }
        echo json_encode($res);
        break;
    case "munkahelylistazas" :
        $privateid = $_SESSION['userid'];

        $sql = "SELECT idworkplace, workplacename, workplaceaddres, users_id, position,salary,workdate FROM workplace WHERE users_id = '$privateid' ";
        $result = mysqli_query($con, $sql);
        while ($obj = $result->fetch_assoc()) {

            $res[] = $obj;

        }
        echo json_encode($res);
        break;
    case "kiadaslistazasa" :
        $privateid = $_SESSION['userid'];

        $sql = "SELECT idexpense, broker, brokername, tax, hrenovation, users_id,expensedate FROM expense WHERE users_id = '$privateid' ";
        $result = mysqli_query($con, $sql);
        while ($obj = $result->fetch_assoc()) {
            $res[] = $obj;
        }
        echo json_encode($res);
        break;

}
switch ($action2) {
    case "inserthouse":
        $addres = mysqli_real_escape_string($con, $_POST['utca']);
        $totalhprice = mysqli_real_escape_string($con, $_POST['ertek']);
        $ownPerson = mysqli_real_escape_string($con, $_POST['gondozo']);
        $ownMobil = mysqli_real_escape_string($con, $_POST['gtelefon']);

        $privateid = $_SESSION['userid'];
        $sql = "INSERT INTO `house` ( `users_id`,`addres`, `totalhprice`, `ownPerson`, `ownMobil`,`housedate`) VALUES ('$privateid','$addres','$totalhprice','$ownPerson','$ownMobil',now())";


        if (mysqli_query($con, $sql)) {
            echo json_encode(array("Valasz" => True, "Uzenet" => "Sikeresen rogzitett adat"));
            header("Location:/laptopallamvizsga/fooldal/index.php");
        } else {
            echo json_encode(array("Valasz" => False, "Uzenet" => "Sikertelenul rogzitett adat"));
        }
        mysqli_close($con);

        break;
    case "insertworkplace":
        $munka = mysqli_real_escape_string($con, $_POST['munka']);
        $cim = mysqli_real_escape_string($con, $_POST['cim']);
        $beosztas = mysqli_real_escape_string($con, $_POST['beosztas']);
        $salary = mysqli_real_escape_string($con, $_POST['fizetes']);

        $privateid = $_SESSION['userid'];
        $sql = "INSERT INTO `workplace` (`workplacename`, `workplaceaddres`, `users_id`,`position` ,`salary`,`workdate`) VALUES ('$munka', '$cim', '$privateid','$beosztas','$salary',now())";


        if (mysqli_query($con, $sql)) {
            echo json_encode(array("Valasz" => True, "Uzenet" => "Sikeresen rogzitett adat"));
        } else {
            echo json_encode(array("Valasz" => False, "Uzenet" => "Sikertelenul rogzitett adat"));
        }
        mysqli_close($con);

        break;
    case "insertexpense":
        $brokername = mysqli_real_escape_string($con, $_POST['brokername']);
        $broker = mysqli_real_escape_string($con, $_POST['broker']);
        $tax = mysqli_real_escape_string($con, $_POST['tax']);
        $hrenovation = mysqli_real_escape_string($con, $_POST['hrenovation']);


        $privateid = $_SESSION['userid'];
        $sql = "INSERT INTO `expense` ( broker, brokername, tax, hrenovation, users_id,expensedate) VALUES ('$broker', '$brokername', '$tax','$hrenovation', '$privateid',now())";

        if (mysqli_query($con, $sql)) {
            echo json_encode(array("Valasz" => True, "Uzenet" => "Sikeresen rogzitett adat"));
        } else {
            echo json_encode(array("Valasz" => False, "Uzenet" => "Sikertelenul rogzitett adat"));
        }
        mysqli_close($con);

        break;
    case "kriptokereses":
        $return = '';
        if (isset($_POST["query"])) {
            $search = mysqli_real_escape_string($con, $_POST["query"]);
            $query = "SELECT * FROM crypto
	WHERE cryptosymbol LIKE '%" . $search . "%' ";
        } else {
            $query = "SELECT * FROM crypto";
        }
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            $return .= '
	<div >
	<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
	<tr  style="background-color:whitesmoke">
        <th>rangsor</th>
        <th>kép</th>
		<th>simbolúm</th>
        <th>kriptó neve</th>
		<th>ára</th>
		<th>piaci kapitalizáció</th>
		<th>beszurás</th>
	
	</tr>';
            while ($row1 = mysqli_fetch_array($result)) {
                $kep = $row1["cryptoimg"];
                $id = $row1["idcrypto"];
                $return .= '
        <tr  style="background-color:rgba(223,204,65,0.1)">
		<td >' . $row1["rank"] . '</td>
	    <td>' . "<img src=$kep alt='nem betölthető a kép' width='35' height='35'>" . '</td>
		<td>' . $row1["cryptosymbol"] . '</td>
        <td>' . $row1["cryptoname"] . '</td>
        <td>' . $row1["lastprice"] . '</td>
		<td>' . $row1["marketCap"] . '</td>
		<td>' . "<a href=kripsql.php?idcrypto=$id>Beszurás</a>" . '</td>
		</tr>';
            }
            echo $return;
        } else {
            echo 'Nem található eredmény';
        }

        break;
    case "stocksearch":
        $return = '';
        if (isset($_POST["query"])) {
            $search = mysqli_real_escape_string($con, $_POST["query"]);
            $query = "SELECT * FROM stocks
	WHERE stocksymbol  LIKE '%" . $search . "%' ";
        } else {
            $query = "SELECT * FROM stocks";
        }
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            $return .= '
	<div class="table-responsive">
	<table class="table table bordered">
	<tr>
		<th>Részvény neve:</th>
		<th>Részvények szimbolum</th>
		<th>Kibocsátó ország</th>
		<th>Ipar:</th>
		<th>Terület:</th>
        <th>Részvény jelene ár</th>
        <th>Beszurás</th>

	
	</tr>';
            while ($row1 = mysqli_fetch_array($result)) {
                $id = $row1["idstocks"];

                $return .= '
		<tr>
		<td>' . $row1["stockname"] . '</td>
		<td>' . $row1["stocksymbol"] . '</td>
		<td>' . $row1["stockcountry"] . '</td>
		<td>' . $row1["stockindustry"] . '</td>
		<td>' . $row1["stocksector"] . '</td>
        <td>' . $row1["newPrice"] . '</td>
        <td>' . "<a class='button is-warning'  href=stocksql.php?idstocks=$id>Beszurás</a>" . '</td>


	
		</tr>';
            }
            echo $return;
        } else {
            echo 'Nem található eredmény';
        }

        break;
    case "metalsearch":

        $return = '';
        if (isset($_POST["query"])) {
            $search = mysqli_real_escape_string($con, $_POST["query"]);
            $query = "SELECT * FROM metals
	WHERE metalsymbol LIKE '%" . $search . "%' ";
        } else {
            $query = "SELECT * FROM metals";
        }
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) > 0) {
            $return .= '
	<div class="table-responsive">
   <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
	<tr style="background-color:whitesmoke">
		<th>Nemesfém szimbolum:</th>
		<th>Nemesfém ár:</th>
		<th>Beszurás</th>
	
	</tr>';
            while ($row1 = mysqli_fetch_array($result)) {
                $id = $row1["idmetals"];

                $return .= '
		<tr>
		<td>' . $row1["metalsymbol"] . '</td>
		<td>' . $row1["newPrice"] . '</td>
        <td>' . "<a class='button is-warning'  href=metalsql.php?idmetals=$id>Beszurás</a>" . '</td>	
		</tr>';
            }
            echo $return;
        } else {
            echo 'Nem található eredmény';
        }

        break;

}


?>