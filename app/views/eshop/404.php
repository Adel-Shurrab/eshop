<?php
renderHeader($data);
?>

<body>
    <div class="container text-center">
        <div class="content-404">
            <img src="<?= ASSETS . THEME ?>/images/404/404.png" class="img-responsive" alt="" />
            <h1><b>OPPS!</b> We Couldn’t Find this Page</h1>
            <p>Uh... So it looks like you brock something. The page you are looking for has up and Vanished.</p>
            <h2><a href="<?= BASE_URL ?>home">Bring me back Home</a></h2>
        </div>
    </div>


    <?php
    renderFooter($data);
    ?>