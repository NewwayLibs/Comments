
<?if(isset($_SESSION['comments_messages'])):?>
    <?foreach ($_SESSION['comments_messages'] as $message):?>
        <p><?=$message?></p>
    <?endforeach;?>
    <?unset($_SESSION['comments_messages']);?>
<?endif;?>
