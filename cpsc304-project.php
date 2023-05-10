<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password 

    Testing commits 
-->


<html>
<link rel="stylesheet" href="style.php" media="screen">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">



<head>
    <title>Hotel Management Application</title>
</head>

<body>
    <h1 class=""> Hotel Management Application</h1>









    <div class="body-container">

        <hr />

        <div class="page">
            <h2> Manage Reservations </h2>
            <form method="POST" action="update-page.php">
                <input type="submit" class='btn btn-sm btn-dark' value="Go to Managagement Page">
            </form>

            <h2> Find Specific Rooms/Reservations</h2>
            <form method="POST" action="query-page.php">
                <input type="submit" class='btn btn-sm btn-dark' value="Go to Query Page">
            </form>


            <h2>View All Reservations </h2>
            <form method="GET"> <!--refresh page when submitted-->
                <input type="submit" class='btn btn-sm btn-primary' name="viewReservations"></p>
            </form>

            <h2>Find Certain Reservations</h2>
            <form method="GET" action="cpsc304-project.php"> <!--refresh page when submitted-->
                <input type="hidden" name="selectAttributeQueryRequest" />
                Select a Column
                <select name="selectTable" class="btn btn-sm btn-secondary">


                    <?php
                    $tables = array("Reservation ID", "Start Date", "End Date");
                    foreach ($tables as $table) {
                        echo '<option value="' . $table . '"' . '>' . $table . '</option>';
                    }
                    ?>
                </select>
                <br />

                <p class="formfield">
                    <label for="whereBody" class="form-label"> Value </label>
                    <input class="form-control" type="text" name="whereBody">
                </p>
                <input type="submit" class='btn btn-sm btn-primary'>
            </form>

            <h2>Select a Table and Attributes to View</h2>
            <form method="GET">
                <p class="formfield">
                    Select a Table:
                    <select name="projectTable" class="btn btn-sm btn-secondary">
                        <?php
                        $tables = array("Reservations", "Reserves", "Room");
                        foreach ($tables as $table) {
                            echo '<option value="' . $table . '"' . (($_GET['projectTable'] == $table) ? 'selected = selected' : '') . '>' . $table . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="btn-sm btn btn-primary">
                </p>
            </form>
            <form method="GET">
                <div class="">

                    <?php
                    if (isset($_GET['projectTable'])) {

                        $aDoor = $_GET['projectTable'];
                        echo '<input type="hidden" id="projectQueryRequest" name="projectQueryRequest" value="' . $aDoor . '">';
                        echo '<strong> Select Attributes </strong>';
                        if ($aDoor == "Reservations") {
                            echo ' <p class="attribute"> Reservation ID <input type="checkbox" name="attrReservationsID" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Start Date <input type="checkbox" name="attrStartDate" value="Yes" /> </p>';
                            echo ' <p class="attribute"> End Date <input type="checkbox" name="attrEndDate" value="Yes" /> </p>';
                        } else if ($aDoor == "Reserves") {
                            echo ' <p class="attribute"> Reservation ID <input type="checkbox" name="attrReservationsID" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Room Number <input type="checkbox" name="attrRoomNumber" value="Yes" /> </p>';
                        } else {
                            echo ' <p class="attribute"> Number <input type="checkbox" name="attrRoomNumber" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Type <input type="checkbox" name="attrRoomType" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Floor <input type="checkbox" name="attrRoomFloor" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Status <input type="checkbox" name="attrRoomStatus" value="Yes" /> </p>';
                            echo ' <p class="attribute"> Price <input type="checkbox" name="attrRoomPrice" value="Yes" /> </p>';
                        }
                        echo '<br />';
                        echo ' <input type="submit"  class="btn-sm btn btn-primary"> ';
                    }
                    ?>


                </div> <br />
            </form>

            <h2>Join Reservations and Room on a Specific Condition</h2>
            <form method="GET" action="cpsc304-project.php"> <!--refresh page when submitted-->
                <input type="hidden" name="joinRequest" />
                Select a Column
                <select name="joinTable" class="btn btn-sm btn-secondary">
                    <?php
                    $tables = array("Reservation ID", "Start Date", "End Date", "Number", "Floor", "Type", "Status", "Price");
                    foreach ($tables as $table) {
                        echo '<option value="' . $table . '"' . '>' . $table . '</option>';
                    }
                    ?>
                </select>
                <br />

                <p class="formfield">
                    Value
                    <input class="form-control" type="text" name="joinValue">
                </p>
                <input type="submit" class='btn btn-sm btn-primary'>
            </form>

        </div>


        <div class="results">
            <?php


            require_once('db-requests.php');

            function printError($err)
            {
                foreach ($err as $msg) {
                    echo "<div class='text-danger'>" . $msg . "</div>";
                }
            }

            function printSuccess($msg)
            {
                echo "<div class='text-success'>" . $msg . "</div>";
            }

            function handleRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('selectAttributeQueryRequest', $_GET)) {
                        selectAttributeQueryRequest();
                    } else if (array_key_exists('viewReservations', $_GET)) {
                        viewReservationsRequest();
                    } else if (isset($_GET['resetTablesRequest'])) {
                        // resetReservationsRequest();
                    } else if (isset($_GET['projectQueryRequest'])) {
                        projectTableRequest();
                    } else if (isset($_GET['joinRequest'])) {
                        joinTableRequest();
                    }
                    disconnectFromDB();
                }
            }



            handleRequest();
            ?>
        </div>
    </div>
</body>

</html>