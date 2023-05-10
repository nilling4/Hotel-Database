<?php

// require_once('cpsc304-project.php');


$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = false; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function updateQueryRequest()
{
    global $db_conn;
    global $success;

    $array = array();
    $columns = array();
    $count = 1;


    if ($_POST["updID"] != "") {
        array_push($array, "reservation_id = " . $_POST["updID"]);
        array_push($columns, "Reservation ID");
        $count++;
    }

    if ($_POST["updStart"] != "") {
        array_push($array, "start_date = " . "'" . $_POST["updStart"] . "'");
        array_push($columns, "Start Date");
        $count++;
    }

    if ($_POST["updEnd"] != "") {
        array_push($array, "end_date = " . "'" . $_POST["updEnd"] . "'");
        array_push($columns, "End Date");
        $count++;
    }

    $newVal = $_POST['newVal'];
    $queryString = "UPDATE reservations SET ";

    $selectTable = $_POST['selectTable'];

    if ($selectTable == "Reservation ID") {
        $queryString = $queryString . "reservation_id = " . $newVal . " ";
    }

    if ($selectTable == "Start Date") {
        $queryString = $queryString . "start_date = '" . $newVal . "' ";
    }

    if ($selectTable == "End Date") {
        $queryString = $queryString . "end_date = '" . $newVal . "' ";
    }


    $queryString = $queryString . "WHERE ";


    for ($x = 0; $x < $count - 1; $x++) {
        if ($x + 1 < $count - 1) {
            $queryString = $queryString . $array[$x] . " AND ";
        } else {
            $queryString = $queryString . $array[$x];
        }
    }

    executePlainSQL($queryString);
    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printSuccess("Successful Update Operation");
}


function selectAttributeQueryRequest()
{
    global $db_conn;
    global $success;
    $value = $_GET['whereBody'];
    $column = $_GET['selectTable'];

    if (empty($value)) {
        return;
    }

    if ($column == "Start Date") {
        $column = "start_date";
    }
    if ($column == "End Date") {
        $column = "end_date";
    }
    if ($column == "Reservation ID") {
        $column = "reservation_id";
    }

    $query = "select reservation_id, start_date, end_date from reservations where " . $column . " = '" . $value . "'";

    $result = executePlainSQL($query);

    $arr = array();

    array_push($arr, "Reservation ID");
    array_push($arr, "Start Date");
    array_push($arr, "End Date");

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $arr, 'Reservation');
    printSuccess("Successful Selection Operation");
}


function projectTableRequest()
{

    global $db_conn;
    global $success;

    $val = $_GET['projectQueryRequest'];
    $array = array();
    $columns = array();

    $count = 1;

    if ($val == "Reservations") {
        $val = "reservations";

        if (isset($_GET['attrReservationsID'])) {
            array_push($array, "reservation_id");
            array_push($columns, "Reservation ID");
            $count += 1;
        }

        if (isset($_GET['attrStartDate'])) {
            array_push($array, "start_date");
            array_push($columns, "Start Date");
            $count += 1;
        }

        if (isset($_GET['attrEndDate'])) {
            array_push($array, "end_date");
            array_push($columns, "End Date");
            $count += 1;
        }
    } else if ($val == "Reserves") {
        $val = "reserves";

        if (isset($_GET['attrReservationsID'])) {
            array_push($array, "reservation_id");
            array_push($columns, "Reservation ID");
            $count += 1;
        }

        if (isset($_GET['attrRoomNumber'])) {
            array_push($array, "room_number");
            array_push($columns, "Room Number");
            $count += 1;
        }
    } else if ($val == "Room") {
        $val = "roomContains";

        if (isset($_GET['attrRoomNumber'])) {
            array_push($array, "room_number");
            array_push($columns, "Room Number");
            $count += 1;
        }

        if (isset($_GET['attrRoomType'])) {
            array_push($array, "room_type");
            array_push($columns, "Room Type");
            $count += 1;
        }

        if (isset($_GET['attrRoomFloor'])) {
            array_push($array, "floor");
            array_push($columns, "Floor");
            $count += 1;
        }

        if (isset($_GET['attrRoomStatus'])) {
            array_push($array, "status");
            array_push($columns, "Status");
            $count += 1;
        }

        if (isset($_GET['attrRoomPrice'])) {
            array_push($array, "price");
            array_push($columns, "Price");
            $count += 1;
        }
    }

    $queryString = "SELECT ";


    for ($x = 0; $x < $count - 1; $x++) {
        if ($count - 2 == $x) {
            $queryString = $queryString . $array[$x] . " ";
        } else {
            $queryString = $queryString . $array[$x] . ", ";
        }
    }

    $queryString = $queryString . "FROM " . $val;

    $result = executePlainSQL($queryString);

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $columns, $_GET['projectQueryRequest']);
    printSuccess("Successful Projection Operation");
}

function insertQueryRequest($id, $start, $end, $rn)
{

    global $db_conn;
    global $success;

    $reservationstuple = array(
        ":bind1" => $start,
        ":bind2" => $end,
        ":bind3" => $id
    );

    $reservationstuples = array(
        $reservationstuple
    );

    $reservestuple = array(
        ":bind1" => $id,
        ":bind2" => $rn
    );

    $reservestuples = array(
        $reservestuple
    );

    executeBoundSQL("insert into reservations values (:bind1, :bind2, :bind3)", $reservationstuples);

    if ($success == false) {
        return;
    }
    executeBoundSQL("insert into reserves values (:bind1, :bind2)", $reservestuples);

    if ($success == false) {
        return;
    }

    updateRoomStatusToOccupied($rn);
    printSuccess("Successful Insert Operation");
    OCICommit($db_conn);
}

function updateRoomStatusToOccupied($rn)
{
    global $db_conn;
    global $success;
    executePlainSQL("UPDATE roomContains SET status = 'occupied' WHERE room_number = '" . $rn . "'");

    if ($success == False) {
        return;
    }

    OCICommit($db_conn);
}

function deleteQueryRequest()
{
    global $db_conn;
    global $success;


    $array = array();
    $columns = array();
    $count = 1;

    if ($_GET["delID"] != "") {
        array_push($array, "reservation_id = " . $_GET["delID"]);
        array_push($columns, "Reservation ID");
        $count++;
    }

    if ($_GET["delStart"] != "") {
        array_push($array, "start_date = " . "'" . $_GET["delStart"] . "'");
        array_push($columns, "Start Date");
        $count++;
    }

    if ($_GET["delEnd"] != "") {
        array_push($array, "end_date = " . "'" . $_GET["delEnd"] . "'");
        array_push($columns, "End Date");
        $count++;
    }


    $queryString = "DELETE FROM reservations WHERE ";

    for ($x = 0; $x < $count - 1; $x++) {
        if ($x + 1 < $count - 1) {
            $queryString = $queryString . $array[$x] . " AND ";
        } else {
            $queryString = $queryString . $array[$x];
        }
    }

    executePlainSQL($queryString);

    if ($success == False) {
        return;
    }

    OCICommit($db_conn);
    printSuccess("Successful Delete Operation");
}

function aggregationGroupByRequest($roomStatus)
{
    global $db_conn;
    global $success;

    $result = executePlainSQL("SELECT RC.room_type, COUNT(*) FROM roomContains RC WHERE RC.status = '" . $roomStatus . "' GROUP BY RC.room_type");

    $columns = array(
        "Room Type",
        "Count"
    );

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $columns, "Room");
    printSuccess("Successful Operation");
}

function aggregationHavingRequest($number)
{
    global $db_conn;
    global $success;

    $result = executePlainSQL("SELECT RC.floor, MIN(RC.price) FROM roomContains RC WHERE RC.status = 'vacant' GROUP BY RC.floor HAVING COUNT(*) > '" . $number . "'");


    $columns = array(
        "Floor",
        "Min Price"
    );

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $columns, "Room");
    printSuccess("Successful Operation");
}

function aggregationNestedRequest($price)
{
    global $db_conn;
    global $success;

    if ($price == "cheapest") {
        $result = executePlainSQL("SELECT RC.room_number FROM roomContains RC WHERE RC.status = 'vacant' AND RC.price <= all (SELECT MIN(RC2.price) FROM roomContains RC2 WHERE RC2.status = 'vacant' GROUP BY RC2.floor)");
    } else {
        $result = executePlainSQL("SELECT RC.room_number FROM roomContains RC WHERE RC.status = 'vacant' AND RC.price >= all (SELECT MAX(RC2.price) FROM roomContains RC2 WHERE RC2.status = 'vacant' GROUP BY RC2.floor)");
    }

    $columns = array(
        "Number",
    );

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $columns, "Room");
    printSuccess("Successful Operation");
}

function divisionRequest($floor)
{
    global $db_conn;
    global $success;

    $result = executePlainSQL("SELECT R.reservation_id FROM reservations R WHERE NOT EXISTS ((SELECT RC.room_number FROM roomContains RC WHERE RC.floor = '" . $floor . "') minus (SELECT Res.room_number FROM reserves Res WHERE R.reservation_id = Res.reservation_id))");

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }


    printResult($result, array("Reservation ID"), "Reservations");
    printSuccess("Successful Division Operation");
}


function viewReservationsRequest()
{
    global $db_conn;
    global $success;

    $result = executePlainSQL("SELECT reservation_id, start_date, end_date FROM reservations");

    $columns = array(
        "Reservation ID",
        "Start Date",
        "End Date"
    );

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $columns, "Reservations");
    printSuccess("Successful View Operation");
}


function joinTableRequest() {
    global $db_conn;
    global $success;
    $column = $_GET['joinTable'];
    $value = $_GET['joinValue'];

    if (empty($value)) {
        return;
    }

    if ($column == "Start Date") {
        $column = "start_date";
    }
    if ($column == "End Date") {
        $column = "end_date";
    }
    if ($column == "Reservation ID") {
        $column = "reservation_id";
    }

    if ($column == "Number") {
        $column = "room_number";
    }

    if ($column == "Floor") {
        $column = "floor";
    }

    if ($column == "Type") {
        $column = "room_type";
    }

    if ($column == "Status") {
        $column = "status";
    }

    if ($column == "Price") {
        $column = "price";
    }


    $query = "select reservation_id, room_number from reservations, roomContains where " . $column . " = '" . $value . "'";


    $result = executePlainSQL($query);

    $arr = array();

    array_push($arr, "Reservation ID");
    array_push($arr, "Room Number");

    OCICommit($db_conn);

    if ($success == False) {
        return;
    }

    printResult($result, $arr, 'Reservation and Room');
    printSuccess("Successful Selection Operation");
}

function executePlainSQL($cmdstr)
{ //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success, $err;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        printError(array(
            "<p> Failed to execute requested operation: </p>",
            htmlentities($e['message'])
        ));
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        printError(array(
            "<p> Failed to execute requested operation: </p>",
            htmlentities($e['message'])
        ));
        $success = False;
    }

    return $statement;
}





function executeBoundSQL($cmdstr, $list)
{
    global $db_conn, $success, $err;
    $statement = OCIParse($db_conn, $cmdstr);

    // echo "TEST";

    if (!$statement) {
        $e = OCI_Error($db_conn);

        printError(array(
            "<p> Failed to execute requested operation: </p>",
            htmlentities($e['message']),
        ));

        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            printError(array(
                "<p> Failed to execute requested operation: </p>",
                htmlentities($e['message'])
            ));

            $success = False;
        }
    }
}



function printResult($result, $columns, $name)
{


    echo "<div class=''><h3>Retrieved data from table " . $name . ":</h3>";
    echo "<table>";

    foreach ($columns as $column) {
        echo "<th>" . $column . "</th>";
    }

    while ($row = OCI_fetch_array($result, OCI_BOTH)) {

        echo "<tr>";
        for ($i = 0; $i < sizeof($row); $i++) {
            if ($row[$i] != "") {
                echo "<td>" . $row[$i] . "</td>";

            }
        }
        
        echo "</tr>";
    }

    echo "</table> </div>";
}

function connectToDB()
{
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_henryk02", "a32523722", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB()
{
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}
