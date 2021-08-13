<?php
    require 'lib/api.php';
    if (filter_var($_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP)) {
        $BackendAPI->addIP($_SERVER["REMOTE_ADDR"], $_SERVER['HTTP_USER_AGENT']);
    }

    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
        exit("This site is not supported on Internet Explorer. Please use a modern browser like Chrome, Edge, Firefox or Brave.");
    }

    $devmode = TRUE;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Million Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Follow Million Token to the moon on this live website with key statistics!">
    <meta name="keywords" content="crypto currency tracker track graph million token milliontoken lion coin techlead">

    <link rel="icon" type="image/x-icon" href="/assets/img/lion.png"/>
    <link rel="stylesheet" href="/css/lib/chart.min.css" />
    <?php if ($devmode === TRUE) { ?>
        <link rel="stylesheet" href="/css/standard.min.css?<?php echo rand(); ?>" />

        <script type="text/javascript" src="/js/lib/vue.dev.min.js"></script>
    <?php } else { ?>
        <link rel="stylesheet" href="/css/standard.min.css" />

        <script type="text/javascript" src="/js/lib/vue.min.js"></script>
    <?php } ?>
    <script type="text/javascript" src="/js/lib/axios.min.js"></script>
</head>
<body>
    <header>
        <div class="banner">
            <img src="/assets/img/lion.png" title="Million Token" alt="Million Token" />
            <h1>Million Tracker</h1>
        </div>
    </header>
    <main id="app">
        <?php
            include "pages/main.php";
        ?>
    </main>
    <footer>
        <p>Created and maintained by <a href="https://twitter.com/Norrzzz" title="Norrzzz Twitter" target="_blank">Norrzzz</a></p>
    </footer>

    <script src="https://kit.fontawesome.com/b212daa145.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/lib/moment.min.js?<?php echo rand(); ?>"></script>
    <script type="text/javascript" src="/js/lib/hammerjs.min.js"></script>
    <script type="text/javascript" src="/js/lib/chart.min.js"></script>
    <script type="text/javascript" src="/js/lib/chartjs-plugin-zoom.min.js"></script>
    <script type="text/javascript" src="/js/lib/chartjs-moment-adapter.min.js"></script>
    <script type="text/javascript" src="/js/common.min.js"></script>
    <script type="text/javascript" src="/js/api.min.js"></script>

    <?php if ($devmode === TRUE) { ?>
        <script type="text/javascript" src="/js/main.vue.min.js?<?php echo rand(); ?>"></script>
    <?php } else { ?>
        <script type="text/javascript" src="/js/main.vue.min.js"></script>
    <?php } ?>

    <noscript>
        Your browser does not support JavaScript! Enable it, or you'll be unable to use this site.
    </noscript>
</body>
</html>