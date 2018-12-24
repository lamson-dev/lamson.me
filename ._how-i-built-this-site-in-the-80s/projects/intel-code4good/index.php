<?php require_once('layouts/header.php'); ?>
    <div class="menu">
        <a href="main_frame.html" target="content">
            <div class="menu_block">Game</div> 
        </a>
        <a href="questions/question.html" target="content">
            <div class="menu_block">Question</div>
        </a>
        <a href="comingsoon.html" target="content">
            <div class="menu_block">Scavanger Hunt</div>
        </a>
    </div>
    <div class="frame">
        <iframe name="content" id="content" src="main_frame.html" width="800" height="480" scrolling="no" frameborder="1">
        </iframe>
    </div>

<?php require_once('layouts/footer.php'); ?>  