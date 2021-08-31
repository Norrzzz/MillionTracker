<?php

    declare(strict_types=1);

    spl_autoload_register(function ($class_name) {
        $filename = str_replace("\\", DIRECTORY_SEPARATOR, $class_name) . ".php";
        include $filename;
    });

    // ini_set("display_errors", "1");
    // ini_set("display_startup_errors", "1");
    // error_reporting(E_ALL);

    // Only allow GET requests
    if (strtoupper($_SERVER["REQUEST_METHOD"]) != "GET") {
        exitError("Only GET requests are allowed");
    }

    $type = isset($_GET["type"]) ? ucfirst($_GET["type"]) : "NA";

    if ($type === "NA") {
        exit("Please specify the type of chart");
    } elseif (!in_array($type, array("Price", "Holders"))) {
        exit("Invalid chart type");
    }

    require "lib/utilities.php";
    require "lib/validation.php";
    require "lib/api.php";

    use pChart\pColor;
    use pChart\pDraw;
    use pChart\pCharts;

    $itemVal = array();
    $timeVal = array();

    $itemUnit = "$";

    if ($type === "Price") {
        $itemHistory = $BackendAPI->getPrice(7);

        foreach ($itemHistory as $item) {
            array_push($itemVal, $item["price"]);
            array_push($timeVal, (new DateTime($item["time"]))->format("j/n"));
        }
    } elseif($type === "Holders") {
        $itemHistory = $BackendAPI->getHolders(7);

        foreach ($itemHistory as $item) {
            array_push($itemVal, $item["total"]);
            array_push($timeVal, (new DateTime($item["time"]))->format("j/n"));
        }

        $itemUnit = "";
    }

    /* SETTINGS START */

    $width = 1200;
    $height = 650;
    $chartBgColor = new pColor(57, 57, 57); #2e2e2e
    $chartBorderColor = new pColor(46, 46, 46); #2e2e2e
    $chartBgFadeTop = new pColor(26, 20, 45); #1C132D
    $chartBgFadeBot = new pColor(35, 24, 60); #23183C
    $textColor = new pColor(253, 253, 253); #fdfdfd
    $titleColor = new pColor(123, 112, 154); #7B709A
    $gridColor = new pColor(92, 63, 158, 70); #382660
    $red = new pColor(207, 2, 43); #cf022b
    $golden = new pColor(191, 161, 67); #BFA143

    $title = "Million Token";

    /* SETTINGS END */

    # Create a pChart object and associate your dataset
    $draw = new pDraw($width, $height);

    # Add data in your dataset
    $draw->myData->addPoints($itemVal, "item1");
    $draw->myData->setSerieDescription("item1","Price");
    $draw->myData->setPalette("item1", $golden);
    $draw->myData->setAxisUnit(0, $itemUnit);

    $draw->myData->addPoints($timeVal, "time1");
    $draw->myData->setSerieDescription("time1","Time");
    $draw->myData->setAbscissa("time1");

    $maxVal = $draw->myData->getMax("item1");
    $minVal = $draw->myData->getMin("item1");

    $subTitle = "$type last 7 days - Min: $itemUnit$minVal, Max: $itemUnit$maxVal";
    
    # Draw background
    $draw->drawGradientArea(0, 0, $width, $height, DIRECTION_VERTICAL, [
        "StartColor"=> $chartBgFadeTop,
        "EndColor" => $chartBgFadeBot
    ]);

    $draw->drawFromPNG($width - 320, $height - 320, "assets/img/lion_chart_400.png");

    # Choose a nice font
    $draw->setFontProperties([
        "FontName" => "pChart/fonts/Cairo-Regular.ttf",
        "FontSize" => 10,
        "Color" => $textColor
    ]);

    # Draw chart title and logo

    $draw->drawFromPNG(35, 10, "assets/img/lion_chart_48.png");

    $draw->drawText(252, 35, $title, [
        "Align" => TEXT_ALIGN_MIDDLEMIDDLE,
        "FontSize" => 42,
        "Color" => $titleColor
    ]);

    $draw->drawText(($width / 2) + 310, 40, $subTitle, [
        "Align" => TEXT_ALIGN_MIDDLEMIDDLE,
        "FontSize" => 20,
        "Color" => $titleColor
    ]);
  

    # Define the boundaries of the graph area
    $draw->setGraphArea(60, 70, $width - 30, $height - 40);


    # Draw the scale, keep everything automatic
    $Settings = [
        "Pos" => SCALE_POS_LEFTRIGHT,
        "Mode" => SCALE_MODE_FLOATING,
        "LabelingMethod" => LABELING_DIFFERENT,
        "LabelRotation" => 0,
        "XMargin" => 5,
        "YMargin" => 10,
        "GridColor"=> $gridColor,
        "GridTicks" => 10,
        "AxisColor" => $textColor
    ];
    $draw->drawScale($Settings);


    /* Draw the scale, keep everything automatic */ 
    (new pCharts($draw))->drawLineChart();

    /* Render the picture (choose the best way) */
    $draw->autoOutput("temp/example.basic.png");
?>