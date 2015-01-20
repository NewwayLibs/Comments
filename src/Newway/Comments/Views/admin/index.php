<? use Newway\Comments\Route;?>

<h3><?=$header?></h3>
<?foreach($comments as $comment):?>
    <div class="nw_comment_block col-md-12" style="<?=$comment['status'] && $comment['validation'] ? ' background: #dff0d8; ' : ' background: #f2dede; '?> padding-bottom:15px;">
        <hr style="margin-top: 0;"/>
        <div class="col-md-6">
            <b>ID:</b> <?=$comment['id']?><br/>
            <b>Тип контента:</b> <?=$comment['content_type']?><br/>
            <b>Контент ID:</b> <?=$comment['content_id']?><br/>
            <b>Статус:</b> <?=($comment['status']) ? "Включен" : "Выключен" ?><br/>
            <b>Валидация:</b> <?=($comment['validation']) ? "Да" : "Нет" ?><br/>
            <b>Рейтинг:</b> <?=$comment['rating']?><br/>
        </div>
        <div class="col-md-6">
            <b>Имя пользователя:</b> <?=$comment['user_name']?><br/>
            <b>Емейл:</b> <?=$comment['user_email']?><br/>
            <b>Телефон:</b> <?=$comment['user_phone']?><br/>
            <b>IP:</b> <?=$comment['user_ip']?><br/>
            <b>Дата добавления:</b> <?=$comment['created_at']?><br/>
            <b>Ссылка:</b>
              <? if($comment['content_url_title']): ?>
                <a href="<?=$comment['content_url']?>"><?= $comment['content_url_title'] ?></a><br/>
              <? else: ?>
                <a href="<?=$comment['content_url']?>"><?= $comment['content_url'] ?></a><br/>
              <? endif; ?>
        </div>
        <div class="col-md-12"><?=$comment['body']?></div>
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-xs btn-danger nw_comment_delete" data-url="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => '_delete', 'c_id' => $comment['id'])));?>">Удалить</button>
            <a class="btn btn-xs btn-default" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'edit', 'c_id' => $comment['id'])));?>">Редактировать</a>
        </div>
    </div>
<?endforeach;?>
<?include("partials/pagination.php");?>


<script>
    $(document).ready(function () {
        $('.nw_comment_delete').click(function () {
            var that = $(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('data-url'),
                success: function () {
                    that.parents('.nw_comment_block').hide();
                }
            });
        });
    });
</script>