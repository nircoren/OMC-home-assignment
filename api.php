<?php
header("Content-Type:application/json");
include 'databaseService.php';

function validateParams()
{
    return isset($_GET['exchange_type']) && isValidExchangeType($_GET['exchange_type']) &&
        isset($_GET['startperiod']) && isValidDate($_GET['startperiod'])  &&
        isset($_GET['endperiod']) && isValidDate($_GET['endperiod']);
}

function isValidDate($dateStr)
{
    $format = 'Y-m-d';
    $date = DateTime::createFromFormat($format, $dateStr);
    return $date && $date->format($format) === $dateStr;
}

function isValidExchangeType($exchange_type)
{
    $exchange_type_arr = ['USD_to_ILS', 'EUR_to_ILS', 'GBP_to_ILS'];
    return in_array($exchange_type, $exchange_type_arr);
}

if (validateParams()) {
    $conn = connectToDb();
    $exchange_type = $_GET['exchange_type'];
    $start_period = $_GET['startperiod'];
    $end_period = $_GET['endperiod'];

    $query = "SELECT $exchange_type , rate_date
        FROM exchange_rate
        WHERE rate_date BETWEEN '$start_period' AND '$end_period' ;";

    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $data = array(
        'attributes' => array(
            'exchange_type' => $exchange_type,
            'start_period' => $start_period,
            'end_period' => $end_period,
        ),
        'obs' => array()
    );

    while ($row = $result->fetch_assoc()) {
        $date = $row['rate_date'];
        $value = $row[$exchange_type];
        $data['obs'][$date] = $value;
    }

    $jsonData = json_encode($data);
    echo $jsonData;
    $conn->close();
} else {
    echo "Wrong query params. The correct format is \"http://localhost/website/api/{currency}_to_ILS?startperiod=yyyy-mm-dd&endperiod=yyyy-mm-dd\"";
}
