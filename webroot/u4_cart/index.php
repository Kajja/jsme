<?php 

$title='Template for testprogram';
include(__DIR__ . '/../mall/header.php');

include('shop_setup.php');
?>

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
    </div>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>