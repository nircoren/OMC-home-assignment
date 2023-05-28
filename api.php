<?php
header("Content-Type:application/json");

//validate the date is typed correctly.
function validateParams(){
    return isset($_GET['currency']) && $_GET['currency'] != "" &&
    isset($_GET['startperiod']) && $_GET['startperiod'] != "" &&
    isset($_GET['endperiod']) && $_GET['endperiod'] != "";
}

if (validateParams()) {
    include 'database.php';
    $conn = connectToDb();
    $currency = $_GET['currency'];
    $start_period = $_GET['startperiod'];
    $end_period = $_GET['endperiod'];
    $result = mysqli_query(
        $conn,
        "SELECT 
    CASE
        WHEN USD_to_ILS = $currency THEN 'USD_to_ILS'
        WHEN EUR_to_ILS = $currency THEN 'EUR_to_ILS'
        WHEN GBP_to_ILS = $currency THEN 'GBP_to_ILS'
        ELSE 'Not found'
    END AS matched_column
FROM exchange_rate;"
    );

    $query = "SELECT $currency , rate_date
FROM exchange_rate
WHERE rate_date BETWEEN '$start_period' AND '$end_period' ;";

    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = array(
        'attributes' => array(
            'currency' => $currency,
            'start_period' => $start_period,
            'end_period' => $end_period,
        ),
        'obs' => array()
    );

    while ($row = $result->fetch_assoc()) {
        $date = $row['rate_date'];
        $value = $row[$currency];
        $data['obs'][$date] = $value;
    }

    $jsonData = json_encode($data);
    echo $jsonData;
    $conn->close();
}else{
    echo"Wrong query params. The correct format is \"http://localhost/website/api/XXX_to_ILS?startperiod=0000-00-00&endperiod=0000-00-00\"";
}
