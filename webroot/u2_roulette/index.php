<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <div id='rtable'></div>
    <form id='bet_form'>
        <label>Ditt konto: <input type='text' id='funds' /></label>
        <label>Satsning: <input type='text' id='amount'/></label>
        <label>Färg: 
            <select id='color'>
                <option value='red'>Rött</option>
                <option value='black'>Svart</option>
                <option value='green'>Grönt</option>
            </select>
        </label>
        <input type='button' id='spin_btn' value='Snurra!'/></label>
    </form>
    <div id='output'>
        <h2>Välkommen till roulettebordet!</h2>
        <div id='text'></div>
    </div>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>