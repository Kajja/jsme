<?php 
include('shop_setup.php');
?>

<!doctype html>
<html lang='en' class='no-js'>
    <head>
        <meta charset='utf-8' />
        <link rel="stylesheet/less" type="text/css" href="cart.less">
        <link rel="stylesheet" href="../css/jquery-ui.min.css">
        <script src="../js/less.min.js"></script>
        <script src="../js/modernizr.micke.js"></script>
    </head>
    <body>
        <div id='flash'>
            <h1><?=$shopType;?></h1>
            <div id='articles'>
                <table>
                    <thead><tr>
                        <?php foreach ($headers as $key => $value): ?>
                            <th><?=$value;?></th>    
                        <?php endforeach;?>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($articles as $value): ?>
                            <tr>
                                <td><img src='<?=$value['img'];?>' /></td>
                                <?php foreach ($value['info'] as $data): ?>
                                    <td><?=$data;?></td>
                                <?php endforeach; ?>
                                <td><button class='buy' data-id='<?=$value['article'];?>' data-name='<?=$value['info'][0]?>' data-price='<?=$value['info'][1]?>'>Köp</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div id='cart'>
                <header><img src='img/cart.png' /> Kundvagn</header>
                <table id='items'>
                    <thead><tr>
                        <th>Artikel</th>
                        <th>Antal</th>
                        <th>Summa</th>    
                    </tr></thead>
                    <tbody>
                    </tbody>
                </table>
                <p>Antal artiklar: <span id='num_art'>0</span> st</p>
                <p>Totalt pris: <span id='sum_art'>0</span> Sek</p>
                <button id='clear_btn'>Rensa</button>
                <a href='checkout.php' id='checkout'>Betala</a>
            </div>
        </div>

    <?php
        $path=__DIR__;
        $d = explode("/", trim($path, "/"));
        $srcUrl = '../source.php?dir=' . end($d) . '&amp;file=' . basename($_SERVER["PHP_SELF"]) . '#file';
    ?>
        <footer id='footer'>
            <p><em>"Hey Luke, use the <a href='<?=$srcUrl?>'>source</a>."</em></p>
        </footer>
        <script src="../js/jquery.js"></script>
        <script src='../js/jquery.validate.js'></script>
        <script src='../js/messages_sv.min.js'></script>
        <script src='../js/jquery-ui.min.js'></script>
        <script src="cart.js"></script>
    </body>
</html>