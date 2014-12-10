<?php use Newway\Comments\Comments; ?>
<div class="comment-form" id="comment_form" >
    <h2>Добавить комментарий:</h2>
    <?if(!empty($messages)):?>
      <?foreach($messages as $type => $message):?>
          <div class="comments-messages <?=is_string($type) ? $type : 'error'?>">
            <?=$message?>
          </div>
      <?endforeach;?>
    <?endif;?>
    <?if(!empty($validationResult)):?>
      <?foreach($validationResult as $field => $messagesOfField):?>
        <?foreach($messagesOfField as $key => $message):?>
            <div class="comments-messages error">
              <?=$message?>
            </div>
        <?endforeach;?>
      <?endforeach;?>
    <?endif;?>
    <form method="post">
        <div class="row">
            <label for="user_name">Имя:</label>
            <input type="text" id="user_name" name="user_name" value="<?= (isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : null) ?>" />
            <div class="clr"></div>
        </div>

        <div class="row">
            <label for="user_email">Email:</label>
            <input type="text" name="user_email" id="user_email" value="<?= (isset($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : null) ?>" />
            <div class="clr"></div>
        </div>

        <div class="row">
            <label for="rating">Рейтинг:</label>
            <div class="starRating">
                <div>
                    <div>
                        <div>
                            <div>
                                <input id="rating1" type="radio" name="rating" value="1">
                                <label for="rating1"><span>1</span></label>
                            </div>
                            <input id="rating2" type="radio" name="rating" value="2">
                            <label for="rating2"><span>2</span></label>
                        </div>
                        <input id="rating3" type="radio" name="rating" value="3">
                        <label for="rating3"><span>3</span></label>
                    </div>
                    <input id="rating4" type="radio" name="rating" value="4">
                    <label for="rating4"><span>4</span></label>
                </div>
                <input id="rating5" type="radio" name="rating" value="5">
                <label for="rating5"><span>5</span></label>
            </div>
            <div class="clr"></div>
        </div>

        <div class="row">
            <label for="body">Текст:</label>
            <textarea id="body" name="body"><?= (isset($_POST['body']) ? htmlspecialchars($_POST['body']) : null) ?></textarea>
            <div class="clr"></div>
        </div>

        <div class="row">
            <input name="comment" id="comment" type="submit" value="Отправить"/>
            <div class="clr"></div>
        </div>
    </form>
</div>