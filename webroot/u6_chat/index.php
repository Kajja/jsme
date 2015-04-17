<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
<div id='flash'>
    <h1>Chat-klient</h1>
    <div id='connection'>
            <div>
                <input id='user' type='text' placeholder='Ditt användarnamn' required/> 
                <label>Server: <input id='url' value='ws://127.0.0.1:3000'/></label>
            </div>
            <button id='connect'>Koppla upp</button>
            <button id='close' hidden>Koppla ner</button>
    </div>
    <div id='message'>
        <h2>Meddelande</h2>
        <input id='msgText' type='text'>
        <button id='send'>Skicka</button>
    </div>
    <div id='output'>
        <h2>Flödet</h2>
        <textarea id='outtext'></textarea>
    </div>
    <div id='users'>
        <h2>På chatten:</h2>
        <p id='online'></p>
    </div>
</div>

<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>