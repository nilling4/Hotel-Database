<html>
<link rel="stylesheet" href="style.php" media="screen">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">

<body>

    <h1> Query Page </h1>

    <div class="formfield">
        <a href="cpsc304-project.php"> Back To Main Menu</a>
    </div>

    <div class="body-container">



        <div class="page">
            <h3> Find number of vacant or occupied rooms for each room type </h3>

            <form method="GET" action="query-page.php">
                <p class="formfield">
                    <select name="room-status-table" class="btn btn-secondary btn-sm">
                        <?php
                        $tables = array("vacant", "occupied");
                        foreach ($tables as $table) {
                            echo '<option value="' . $table . '"' . (($_GET['room-status-table'] == $table) ? 'selected = selected' : '') . '>' . $table . '</option>';
                        }
                        ?>
                    </select>

                    <input type="submit" class="btn btn-primary btn-sm" name="do_groupbyQueryRequest">
                </p>
                </p>

            </form>

            <h3>For each floor that has more than X available rooms, grab the cheapest available room</h3>
            <form method="GET" action="query-page.php"> <!--refresh page when submitted-->
                <!-- <input type="hidden" id="havingQueryRequest" name="havingQueryRequest" value="true"> -->
                <p class="formfield">
                     <select name="number-table" class="btn btn-secondary btn-sm">
                    <?php
                    $tables = array("0", "1", "2", "3");
                    foreach ($tables as $table) {
                        echo '<option value="' . $table . '"' . (($_GET['number-table'] == $table) ? 'selected = selected' : '') . '>' . $table . '</option>';
                    }
                    ?>
                    </select>
                    <input type="submit" class="btn btn-primary btn-sm" name="do_havingQueryRequest">
                </p>

            </form>



            <h3>Find the most cheap/expensive available room</h3>
            <form method="GET" action="query-page.php"> <!--refresh page when submitted-->
                <!-- <input type="hidden" id="nestedQueryRequest" name="nestedQueryRequest" value="true"> -->
                <p class="formfield">
                <select name="room-price-table" class="btn btn-secondary btn-sm">
                    <?php
                    $tables = array("cheapest", "most expensive");
                    foreach ($tables as $table) {
                        echo '<option value="' . $table . '"' . (($_GET['room-price-table'] == $table) ? 'selected = selected' : '') . '>' . $table . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" class="btn btn-primary btn-sm" name="do_nestedQueryRequest"></p>
            </form>


            <h3>Find the reservation that reserved all rooms on selected floor</h3>
            <form method="GET" action="query-page.php"> <!--refresh page when submitted-->
                <!-- <input type="hidden" id="divisionRequest" name="divisionRequest" value="true"> -->
              <p class="formfield">
                <select name="floor-table" class="btn btn-secondary btn-sm">
                    <?php
                    $tables = array("2", "3", "4", "5", "6", "7", "8");
                    foreach ($tables as $table) {
                        echo '<option value="' . $table . '"' . (($_GET['floor-table'] == $table) ? 'selected = selected' : '') . '>' . $table . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" class="btn btn-primary btn-sm" name="do_divisionRequest"></p>
            </form>

        </div>

        <div class="results">
            <?php

            require_once('db-requests.php');

            function handleRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('do_groupbyQueryRequest', $_GET)) {
                        if (isset($_GET['do_groupbyQueryRequest'])) {
                            aggregationGroupByRequest($_GET['room-status-table']);
                        }
                    } else if (array_key_exists('do_havingQueryRequest', $_GET)) {
                        if (isset($_GET['do_havingQueryRequest'])) {
                            aggregationHavingRequest($_GET['number-table']);
                        }
                    } else if (array_key_exists('do_nestedQueryRequest', $_GET)) {
                        if (isset($_GET['do_nestedQueryRequest'])) {
                            aggregationNestedRequest($_GET['room-price-table']);
                        }
                    } else if (array_key_exists('do_divisionRequest', $_GET)) {
                        if (isset($_GET['do_divisionRequest'])) {
                            divisionRequest($_GET['floor-table']);
                        }
                    }

                    disconnectFromDB();
                }
            }

            handleRequest();

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

        </div>
    </div>
</body>

</html>