<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <h1>Login med Ajax</h1>
    <p><small>(Anv: kalle ,  Lösn: abc)</small></p>
    <form id='lform'>
        <p><label>Användare:<br><input type='text' name='user' /></label></p>
        <p><label>Lösenord:<br><input type='password' name='passw' /></label></p>
        <div id='buttons'>
            <input type='button' id='login' value='Login' />
            <input type='button' id='logout' value='Logout' />
            <input type='button' id='status' value='Status' />
        </div>
            <p ><output id='out'></output></p>
    </form>

</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>