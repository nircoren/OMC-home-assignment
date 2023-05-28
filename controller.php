<?php
$usdXml = file_get_contents("localdata/file1.xml");
$eurXml = file_get_contents("localdata/file2.xml");
$gbpXml = file_get_contents("localdata/file3.xml");

//i dont understand if its supposed to be async.
// $usdXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_USD_ILS?startperiod=2023-01-01&endperiod=2024-01-01");
// $eurXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_EUR_ILS?startperiod=2023-01-01&endperiod=2024-01-01");
// $gbpXml = file_get_contents("https://edge.boi.gov.il/FusionEdgeServer/sdmx/v2/data/dataflow/BOI.STATISTICS/EXR/1.0/RER_GBP_ILS?startperiod=2023-01-01&endperiod=2024-01-01");

$xmlArr = array(
    $usdXml,
    $eurXml,
    $gbpXml
);
