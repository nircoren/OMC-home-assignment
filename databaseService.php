<?php

function connectToDb()
{
    $db_server = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "exchange_ratedb";
    $conn = "";
    try {
        $conn = mysqli_connect(
            $db_server,
            $db_user,
            $db_pass,
            $db_name
        );
    } catch (mysqli_sql_exception) {
        echo "error, connected";
    }
    if ($conn) {
        return $conn;
    } else {
        echo "not connected";
    }
}

function uploadToDb($conn, $xml)
{
    $parsed_xml = new SimpleXMLElement($xml);

    $series = $parsed_xml->children('message', true)->DataSet->children()->Series;
    $obs =  $series->Obs;

    $base_exchange_type = $series->attributes()->BASE_CURRENCY;
    $counter_exchange_type = $series->attributes()->COUNTER_CURRENCY;
    $exchange_type = $base_exchange_type . '_to_' . $counter_exchange_type;

    //update extraction time for each exchange_type
    $exractionTime = (string) $parsed_xml->children('message', true)->Header->Extracted;
    $exractionTime = UTCTimeToLocalTime($exractionTime, 'Asia/Tel_Aviv');

    $result = $conn->query("SELECT * FROM general_data WHERE exchange_type = '$exchange_type'");

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows == 0) {
        $query = "INSERT INTO general_data (exchange_type) VALUES('$exchange_type')";
        if (!$conn->query($query)) {
            die("Query failed: " . $conn->error);
        }
    }

    $query = "UPDATE general_data
    SET Extraction_TimeStamp = '$exractionTime'
    WHERE exchange_type = '$exchange_type'";

    if (!$conn->query($query)) {
        die("Query failed: " . $conn->error);
    }

    for ($i = 0; $i < count($obs); $i++) {
        $curr_obs = $obs[$i]->attributes();
        //see if theres a better way of verifying if the date already exists.
        $result = $conn->query("SELECT * FROM exchange_rate WHERE rate_date = '$curr_obs->TIME_PERIOD'");
        if ($result->num_rows == 0) {
            $query = "INSERT INTO exchange_rate (rate_date) VALUES('$curr_obs->TIME_PERIOD')";
            if (!$conn->query($query)) {
                die("Query failed: " . $conn->error);
            }
        }

        $query = "UPDATE exchange_rate
            SET $exchange_type = $curr_obs->OBS_VALUE
            WHERE rate_date = '$curr_obs->TIME_PERIOD'
            AND $exchange_type IS NULL;";
        if (!$conn->query($query)) {
            die("Query failed: " . $conn->error);
        }
    }
}

function fetch_data_from_db()
{
    $conn = connectToDb();
    $table_name = "exchange_rate";
    $columns = $_POST['exchange_types'];
    array_unshift($columns, 'rate_date');
    $start_period = $_POST['start_period'];
    $end_period = $_POST['end_period'];

    if (empty($conn)) {
        $msg = "Database connection error";
    } elseif (empty($columns) || !is_array($columns)) {
        $msg = "columns Name must be defined in an indexed array";
    } elseif (empty($table_name)) {
        $msg = "Table Name is empty";
    } else {
        $columnName = implode(", ", $columns);
        $query = "SELECT $columnName
        FROM exchange_rate
        WHERE rate_date BETWEEN  '$start_period' AND '$end_period' ;";
        // $query = "SELECT $columnName FROM $table_name ORDER BY rate_date DESC";
        $result = $conn->query($query);
        if ($result == true) {
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $msg = $row;
            } else {
                $msg = "No Data Found";
            }
        } else {
            $msg = mysqli_error($conn);
        }
    }
    $conn->close();
    return $msg;
}

include 'utils.php';

function pushDataToMysql($xmlArr)
{
    
    $conn = connectToDb();

    foreach ($xmlArr as $xml) {
        uploadToDb($conn, $xml);
    }

    $conn->close();
    echo "data pulled successfully";
}
