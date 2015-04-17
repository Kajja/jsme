<div class='page'>
    <h2>Klient för WebSocket-protokollet</h2>
    <div id='connection'>
        <input id='url' value='ws://dbwebb.se:1337'/>
        <select id='protocol'>
            <option>echo</option>
            <option>broadcast</option>
        </select>
        <button id='connect'>Connect</button>
        <button id='close'>Disconnect</button>
    </div>
    <div id='message'>
        <h3>Meddelande</h3>
        <input id='msgText' type='text'>
        <button id='send'>Send message</button>
    </div>
    <div id='output'>
        <h3>Statusinformation</h3>
        <textarea id='outtext'></textarea>
    </div>
</div>
<?php 
    $srcUrl = $this->url->create('source.php') . '?path=' . $path;
?>
<p><em>"Hey Luke, use the <a href='<?=$srcUrl?>'>source</a>."</em></p>