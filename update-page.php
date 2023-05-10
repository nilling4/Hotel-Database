<html>
<link rel="stylesheet" href="style.php" media="screen">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">


<body>
    </br>
    <h1> Update Page </h1>
    </br>

    <?php

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

    ?>


    <div class="formfield">
        <a href="cpsc304-project.php"> Back To Main Menu</a>
    </div>


    <div class="body-container">
        <div class="pageupdate">

            <div class="update">
                <h2>Update a Reservation</h2>
                <form method="POST" action="update-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
                    <p class="formfield">
                        Reservation ID: <input type="text" class="form-control" name="updID">
                    </p>
                    <p class="formfield">
                        Start Date: <input type="text" class="form-control" name="updStart">
                    </p>
                    <p class="formfield">
                        End Date: <input type="text" class="form-control" name="updEnd">
                    </p>

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
                        New Value: <input type="text" class="form-control" name="newVal">
                    </p>

                    <input type="submit" class='btn btn-sm btn-primary'></p>
                </form>



                <h2>Delete a Reservation</h2>
                <form method="GET" action="update-page.php"> <!--refresh page when submitted-->
                    <p class="formfield">
                        Reservation ID: <input type="text" class="form-control" name="delID">
                        Start Date: <input type="text" class="form-control" name="delStart">
                        End Date: <input type="text" class="form-control" name="delEnd">
                    </p>
                    <input type="submit" class='btn btn-sm btn-primary' name="deleteQueryRequest"></p>
                </form>

            </div>
            <div class="update">
                <h2>View All Reservations </h2>
                <form method="GET"> <!--refresh page when submitted-->
                    <input type="submit" class='btn btn-sm btn-primary' name="viewReservations"></p>
                </form>

                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                  


                <h2>Insert a Reservation</h2>
                <form method="POST" action="update-page.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                    <div class="formfield">
                        Reservation ID: <input type="text" class="form-control" name="insID">
                    </div>

                    <div class="formfield">

                        Start Date: <input type="text" class="form-control" name=" insStart">
                    </div>
                    <div class="formfield">

                        End Date: <input type="text" class="form-control" name=" insEnd">
                    </div>
                    <div class="formfield">

                        Room Number: <input type="text" class="form-control" name=" insRN">
                    </div>
                    <br />
                    <input type="submit" class='btn btn-sm btn-primary'></p>
                </form>

            </div>
        </div>

        <div class="results">
            <?php
            require_once('db-requests.php');

            function handleRequest()
            {

                if (connectToDB()) {

                    if (array_key_exists('insertQueryRequest', $_POST)) {
                        if (isset($_POST["insID"]) && isset($_POST["insStart"]) && isset($_POST["insEnd"]) && isset($_POST["insRN"])) {
                            insertQueryRequest($_POST["insID"], $_POST["insStart"], $_POST["insEnd"], $_POST["insRN"]);
                        } else {
                            printError(array("Missing An Attribute"));
                        }
                    } else if (array_key_exists('deleteQueryRequest', $_GET)) {
                        if (isset($_GET["delID"])) {
                            deleteQueryRequest($_GET["delID"]);
                        } else {
                            printError(array("Missing Reservation ID"));
                        }
                    } else if (array_key_exists('updateQueryRequest', $_POST)) {
                        if (isset($_POST["updID"]) || isset($_GET["updStart"]) || isset($_GET["updEnd"])) {
                            updateQueryRequest();
                        } else {
                            printError(array("Missing Reservation ID"));
                        }
                    } else if (array_key_exists('viewReservations', $_GET)) {
                        viewReservationsRequest();
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