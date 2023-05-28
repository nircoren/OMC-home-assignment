<?php

$usdXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_USD_ILS?startperiod=2023-01-01&endperiod=2024-01-01");
$eurXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_EUR_ILS?startperiod=2023-01-01&endperiod=2024-01-01");
$gbpXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_GBP_ILS?startperiod=2023-01-01&endperiod=2024-01-01");

$xmlArr = array(
    $usdXml,
    $eurXml,
    $gbpXml
);


function UTCTimeToLocalTime($time, $tz = '', $FromDateFormat = 'Y-m-d H:i:s', $ToDateFormat = 'Y-m-d H:i:s')
{
    $trimedTime = str_replace("T", " ", $time);

    if ($tz == '')
        $tz = date_default_timezone_get();

    $utc_datetime = DateTime::createFromFormat($FromDateFormat, $trimedTime, new
        DateTimeZone('UTC'));
    $local_datetime = $utc_datetime;

    $local_datetime->setTimeZone(new DateTimeZone($tz));
    return $local_datetime->format($ToDateFormat);
}
