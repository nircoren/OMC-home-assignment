<?php
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
