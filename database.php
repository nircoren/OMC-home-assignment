<?php
//Connect to db.
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
