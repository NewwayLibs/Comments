<? use Newway\Comments\Route;?>
<h1><?=$header?></h1>
<div class="span12">
    <form role="form" action="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => '_edit', 'c_id' => $comment['id'])));?>" method="post">
        <div class="form-group">
            <label for="InputContentType1">Тип контента</label>
            <input type="text" class="form-control" id="InputContentType1" placeholder="Введите тип контента" name="content_type" value="<?=$comment['content_type']?>">
        </div>
        <div class="form-group">
            <label for="InputContentId1">ID контента</label>
            <input type="text" class="form-control" id="InputContentId1" placeholder="ID контента" name="content_id" value="<?=$comment['content_id']?>">
        </div>
        <div class="form-group">
            <label for="InputEmail1">Имя пользователя</label>
            <input type="text" class="form-control" id="InputEmail1" placeholder="Введите имя" name="user_name" value="<?=$comment['user_name']?>">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Емейл</label>
            <input type="email" class="form-control" id="InputEmail1" placeholder="Введите емейл" name="user_email" value="<?=$comment['user_email']?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPhone1">Телефон</label>
            <input type="text" class="form-control" id="exampleInputPhone1" placeholder="Введите телефон" name="user_phone" value="<?=$comment['user_phone']?>">
        </div>
        <div class="form-group">
            <label for="exampleInputIp1">Ip</label>
            <input type="text" class="form-control" id="exampleInputIp1" placeholder="Введите Ip" name="user_ip" value="<?=$comment['user_ip']?>">
        </div>
        <div class="form-group">
            <label for="InputDate1">Дата</label>
            <input type="text" class="form-control" id="InputDate1" placeholder="Введите дату" name="created_at" value="<?=$comment['created_at']?>">
        </div>
        <div class="form-group">
            <label for="inputContentUrl">Url комментируемой страницы</label>
            (<a onclick="window.location.href = document.getElementById('inputContentUrl').value" style="cursor:pointer;">перейти</a>)
            <input type="text" class="form-control" id="inputContentUrl" placeholder="Введите url комментируемой страницы" name="content_url" value="<?=$comment['content_url']?>">
        </div>
        <div class="form-group">
            <label for="InputStatus1">Статус</label>
            <select class="form-control" name="status">
                <option value="0" <?=($comment['status'] == 0) ? 'selected' : ''?>>Выключен</option>
                <option value="1" <?=($comment['status'] == 1) ? 'selected' : ''?>>Включен</option>
            </select>
        </div>

        <div class="form-group">
            <label for="InputDate1">Рейтинг: </label>
            <?for($i = 1; $i <= 5; $i++):?>
                <label class="radio-inline">
                    <input type="radio" name="rating" id="inlineRadio1" value="<?=$i?>" <?=($comment['rating'] == $i) ? 'checked' : ''?>> <?=$i?>
                </label>
            <?endfor;?>
        </div>

        <div class="form-group">
            <label for="InputBody1">Текст комментария</label>
            <textarea class="form-control" rows="3" id="InputBody1" name="body"><?=$comment['body']?></textarea>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
