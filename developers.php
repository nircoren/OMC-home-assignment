<?php

$conn = connectToDb();
$tableName = "exchange_rate";
$columns = $_POST['exchange_types'];
array_unshift($columns, 'rate_date');
$start_period = $_POST['start_period'];
$end_period = $_POST['end_period'];
// $columns = $currencyArr;
// array_unshift($columns, "rate_date");
$fetchData = fetch_data($conn, $tableName, $columns, $start_period, $end_period);
function fetch_data($conn, $tableName, $columns, $start_period, $end_period)
{
    if (empty($conn)) {
        $msg = "Database connection error";
    } elseif (empty($columns) || !is_array($columns)) {
        $msg = "columns Name must be defined in an indexed array";
    } elseif (empty($tableName)) {
        $msg = "Table Name is empty";
    } else {
        $columnName = implode(", ", $columns);
        $query = "SELECT $columnName
        FROM exchange_rate
        WHERE rate_date BETWEEN  '$start_period' AND '$end_period' ;";
        // $query = "SELECT $columnName FROM $tableName ORDER BY rate_date DESC";
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
    return $msg;
}
