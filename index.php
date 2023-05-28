<?php
//fix data flow.
//not sure if this is good practice to share vars like that (i use vars that are declared here in the next file).
include 'database.php';

if (array_key_exists('reqObsData', $_POST)) {
    include 'controller.php';
    pushDataToMysql($xmlArr);
} elseif (array_key_exists('filterData', $_POST)) {
    if (array_key_exists('exchange_types', $_POST)) {
        include 'developers.php';
    } else {
        echo "please select the exchange type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="formsContainer">
        <div class="topActionsContainers">
            <form method="post" class="reqObsForm">
                <h4>Last extraction:
                    <?php
                    $conn = connectToDb();
                    $sql = "SELECT Extraction_TimeStamp FROM general_data WHERE exchange_type = 'USD_to_ILS';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                    }
                    print_r($row['Extraction_TimeStamp']);
                    $conn->close();

                    ?>
                </h4>
                <input type="submit" name="reqObsData" id="reqObsData" value="Get data from BOI" target="_blank" /><br />
            </form>

            <label for="">Alert when greater than:</label>
            <input type="number" id="">
        </div>

        <form method="post">
            <div>
                <h6>Select currencies to show:</h6>
                <div>
                    <input type="checkbox" id="USD_to_ILS" name="exchange_types[]" value="USD_to_ILS">
                    <label for="USD_to_ILS">USD_to_ILS</label>

                </div>
                <div>
                    <input type="checkbox" id="EUR_to_ILS" name="exchange_types[]" value="EUR_to_ILS">
                    <label for="EUR_to_ILS">EUR_to_ILS</label>

                </div>

                <div>
                    <input type="checkbox" id="GBP_to_ILS" name="exchange_types[]" value="GBP_to_ILS">
                    <label for="GBP_to_ILS">GBP_to_ILS</label>

                </div>
            </div>


            <label for="start">Start date:</label>
            <input type="date" name="start_period" value="2023-01-01" min="2023-01-01" max="<?= date('Y-m-d', strtotime('now')); ?>" />

            <label for="end">End date:</label>
            <input type="date" name="end_period" value="<?php echo date('Y-m-d'); ?>" min="2023-01-01" max="<?= date('Y-m-d', strtotime('now')); ?>" />

            <div class="filterBtnContainer" style="margin-block:20px;">
                <input type="submit" name="filterData" id="filterData" value="show and filter" />
            </div>
            <!-- <button id="button" type="" name="filterData" id="filterData" value="show and filter">Submit</button> -->

        </form>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <?php echo $deleteMsg ?? ''; ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>

                                <?php
                                if (array_key_exists('exchange_types', $_POST)) {
                                    foreach ($_POST['exchange_types'] as $type) { ?>
                                        <th><?php echo $type ?></th>
                                <?php }
                                }
                                ?>
                        </thead>
                        <tbody>
                            <?php
                            if (array_key_exists('filterData', $_POST) && array_key_exists('exchange_types', $_POST) && is_array($fetchData)) {
                                $sn = 1;
                                foreach ($fetchData as $data) {
                            ?>
                                    <tr>
                                        <td><?php echo $data['rate_date'] ?? ''; ?></td>
                                        <?php
                                        if (array_key_exists('exchange_types', $_POST)) {
                                            foreach ($_POST['exchange_types'] as $type) { ?>
                                                <td><?php echo $data[$type] ?? ''; ?></td>
                                        <?php }
                                        }
                                        ?>

                                    </tr>
                                <?php
                                    $sn++;
                                }
                                include 'test.php';
                            } else { ?>
                                <tr>
                                    <td colspan="8">
                                    </td>
                                <tr>
                                <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>