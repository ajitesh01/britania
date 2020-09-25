<?php
    $serverName = "britannia.database.windows.net"; // update me
    $connectionOptions = array(
        "Database" => "ccdb", // update me
        "Uid" => "beta=X'X^-1X'y", // update me
        "PWD" => "your_password" // update me
    );
    //Establishes the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    $tsql= "select TOP 20 * from PromotionBudgetDetails";
    $getResults= sqlsrv_query($conn, $tsql);
    echo ("Reading data from table" . PHP_EOL);
    if ($getResults == FALSE)
        echo (sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
     echo ($row['Territory'] . " " . $row['Brand'] . PHP_EOL);
    }
    sqlsrv_free_stmt($getResults);
?>