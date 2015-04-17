<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
<div id='flash'>
    <h1>Klient för WebSocket-protokollet</h1>
    <div id='connection'>
        <input id='url' value='ws://127.0.0.1:1337'/>
        <select id='protocol'>
            <option>echo</option>
            <option>broadcast</option>
        </select>
        <button id='connect'>Connect</button>
        <button id='close'>Disconnect</button>
    </div>
    <div id='message'>
        <h2>Meddelande</h2>
        <input id='msgText' type='text'>
        <button id='send'>Send message</button>
    </div>
    <div id='output'>
        <h2>Stautsinformation</h2>
        <textarea id='outtext'></textarea>
    </div>
</div>

<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>