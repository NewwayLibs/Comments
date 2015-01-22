<? use Newway\Comments\Route;?>
<style>
    .validation_on.status_on {
        background: #dff0d8 !important;
    }
    .nw_comment_block {
        padding-bottom:15px;
        background: #f2dede;
    }
</style>
<h3><?=$header?></h3>
<?foreach($comments as $comment):?>
    <div class="nw_comment_block col-md-12 <?= $comment['status'] ? ' status_on ' : ''?> <?= $comment['validation'] ? ' validation_on ' : '' ?>">
        <hr style="margin-top: 0;"/>
        <div class="col-md-6">
            <b>ID:</b> <?=$comment['id']?><br/>
            <b>Тип контента:</b> <?=$comment['content_type']?><br/>
            <b>Контент ID:</b> <?=$comment['content_id']?><br/>
            <b>Рейтинг:</b> <?=$comment['rating']?><br/>
            <b>Дата добавления:</b> <?=$comment['created_at']?><br/>
        </div>
        <div class="col-md-6">
            <b>Имя пользователя:</b> <?=$comment['user_name']?><br/>
            <b>Емейл:</b> <?=$comment['user_email']?><br/>
            <b>Телефон:</b> <?=$comment['user_phone']?><br/>
            <b>IP:</b> <?=$comment['user_ip']?><br/>
            <b>Ссылка:</b>
              <? if($comment['content_url_title']): ?>
                <a href="<?=$comment['content_url']?>"><?= $comment['content_url_title'] ?></a><br/>
              <? else: ?>
                <a href="<?=$comment['content_url']?>"><?= $comment['content_url'] ?></a><br/>
              <? endif; ?>
        </div>
        <div class="col-md-12"><?=$comment['body']?></div>
        <div class="col-md-12 text-right">
            <button
                type="button"
                class="btn btn-xs <?= $comment['status'] ? 'btn-success' : 'btn-danger' ?> toggle_status"
                data-url="<?= Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'toggle_status', 'c_id' => $comment['id']))); ?>"
            >
                <?= $comment['status'] ? 'Включен' : 'Выключен' ?>
            </button>
            <button
                class="btn btn-xs <?= $comment['validation'] ? 'btn-success' : 'btn-danger' ?> toggle_validation"
                data-url="<?= Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'toggle_validation', 'c_id' => $comment['id']))); ?>"
            >
                <?= $comment['validation'] ? 'Утверждено' : 'Не утверждено' ?>
            </button>
            <a class="btn btn-xs btn-default" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'edit', 'c_id' => $comment['id'])));?>">Редактировать</a>
            <button type="button" class="btn btn-xs btn-danger nw_comment_delete" data-url="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => '_delete', 'c_id' => $comment['id'])));?>">Удалить</button>
        </div>
    </div>
<?endforeach;?>
<?include("partials/pagination.php");?>


<script>
    $(document).ready(function () {
        $('.nw_comment_delete').click(function () {
            var that = $(this);
            if(confirm('Вы действительно хотите удалить запись?')) {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('data-url'),
                    success: function () {
                        that.parents('.nw_comment_block').hide();
                    }
                });
            }
            return false;
        });

        $('.toggle_status').click(function () {
            var that = $(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('data-url'),
                success: function () {
                    that.parents('.nw_comment_block').toggleClass('status_on');
                    that.toggleClass('btn-success').toggleClass('btn-danger');
                    if(that.hasClass('btn-success'))
                        that.html('Включен')
                    else
                        that.html('Выключен')
                }
            });
        });

        $('.toggle_validation').click(function () {
            var that = $(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('data-url'),
                success: function () {
                    that.parents('.nw_comment_block').toggleClass('validation_on');
                    that.toggleClass('btn-success').toggleClass('btn-danger');
                    if(that.hasClass('btn-success'))
                        that.html('Утверждено')
                    else
                        that.html('Не утверждено')
                }
            });
        });
    });
</script>