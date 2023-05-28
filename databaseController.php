<?php

//validate $obs is sorted by date.
//add condition that if the date already exists, don't push to database.
//add condition that if value of coin already exists, dont continue (because it means all values on previous dates exists.), i need
// to make it go from now till the start to make it more efficeient.
function uploadToDb($conn, $xml)
{
    //make it a function?
    $parsed_xml = new SimpleXMLElement($xml);

    $series = $parsed_xml->children('message', true)->DataSet->children()->Series;
    $obs =  $series->Obs;

    $base_currency = $series->attributes()->BASE_CURRENCY;
    $counter_currency = $series->attributes()->COUNTER_CURRENCY;
    $exchange_type = $base_currency . '_to_' . $counter_currency;

    //update extraction time for each currency
    $exractionTime = (string) $parsed_xml->children('message', true)->Header->Extracted;
    $exractionTime = UTCTimeToLocalTime($exractionTime, 'Asia/Tel_Aviv');

    $result = $conn->query("SELECT * FROM general_data WHERE exchange_type = '$exchange_type'");

    if ($result->num_rows == 0) {
        $query = "INSERT INTO general_data (exchange_type) VALUES('$exchange_type')";
        $conn->query($query);
    }

    $query = "UPDATE general_data
    SET Extraction_TimeStamp = '$exractionTime'
    WHERE exchange_type = '$exchange_type'";

    $conn->query($query);



    for ($i = 0; $i < count($obs); $i++) {
        $curr_obs = $obs[$i]->attributes();
        //see if theres a better way of verifying if the date already exists.
        $result = $conn->query("SELECT * FROM exchange_rate WHERE rate_date = '$curr_obs->TIME_PERIOD'");
        if ($result->num_rows == 0) {
            $query = "INSERT INTO exchange_rate (rate_date) VALUES('$curr_obs->TIME_PERIOD')";
            $conn->query($query);
        }

        $query = "UPDATE exchange_rate
            SET $exchange_type = $curr_obs->OBS_VALUE
            WHERE rate_date = '$curr_obs->TIME_PERIOD'
            AND $exchange_type IS NULL;";
        if ($conn->query($query) === true) {
            //show success popup.
            // echo 'action successfull.';
        } else {
            echo 'Error: ' . $conn->error;
        }
    }
}

function pushDataToMysql($xmlArr)
{
    $conn = connectToDb();
    include 'utils.php';

    foreach ($xmlArr as $xml) {
        uploadToDb($conn, $xml);
    }

    $conn->close();
    echo "data pulled successfully";
}
