<div class='page'>
    <h2>Chatt-klient</h2>
    <div id='connection'>
            <div>
                <input id='user' type='text' placeholder='Ditt användarnamn' required/> 
                <label>Server: <input id='url' value='ws://127.0.0.1:3000'/></label>
            </div>
            <button id='connect'>Koppla upp</button>
            <button id='close' hidden>Koppla ner</button>
    </div>
    <div id='message'>
        <h3>Meddelande</h3>
        <input id='msgText' type='text'>
        <button id='send'>Skicka</button>
    </div>
    <div id='output'>
        <h3>Flödet</h3>
        <textarea id='outtext'></textarea>
    </div>
    <div id='users'>
        <h3>På chatten:</h3>
        <p id='online'></p>
    </div>
</div>
<?php 
    $srcUrl = $this->url->create('source.php') . '?path=' . $path;
?>
<p><em>"Hey Luke, use the <a href='<?=$srcUrl?>'>source</a>."</em></p>