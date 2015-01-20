<? use Newway\Comments\Route;?>
<h1><?=$header?></h1>
<div class="span12">
    <form role="form" action="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => '_add')));?>" method="post">
        <div class="form-group">
            <label for="InputContentType1">Тип контента</label>
            <input type="text" class="form-control" id="InputContentType1" placeholder="Введите тип контента" name="content_type" value="<?=isset($oldForm['content_type']) ? $oldForm['content_type'] : ''?>">
        </div>
        <div class="form-group">
            <label for="InputContentId1">ID контента</label>
            <input type="text" class="form-control" id="InputContentId1" placeholder="ID контента" name="content_id" value="<?=isset($oldForm['content_id']) ? $oldForm['content_id'] : ''?>">
        </div>
        <div class="form-group">
            <label for="InputEmail1">Имя пользователя</label>
            <input type="text" class="form-control" id="InputEmail1" placeholder="Введите имя" name="user_name" value="<?=isset($oldForm['user_name']) ? $oldForm['user_name'] : ''?>">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Емейл</label>
            <input type="email" class="form-control" id="InputEmail1" placeholder="Введите емейл" name="user_email" value="<?=isset($oldForm['user_email']) ? $oldForm['user_email'] : ''?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPhone1">Телефон</label>
            <input type="text" class="form-control" id="exampleInputPhone1" placeholder="Введите телефон" name="user_phone" value="<?=isset($oldForm['user_phone']) ? $oldForm['user_phone'] : ''?>">
        </div>
        <div class="form-group">
            <label for="exampleInputIp1">Ip</label>
            <input type="text" class="form-control" id="exampleInputIp1" placeholder="Введите Ip" name="user_ip" value="<?=isset($oldForm['user_ip']) ? $oldForm['user_ip'] : ''?>">
        </div>
        <div class="form-group">
            <label for="InputDate1">Дата</label>
            <input type="text" class="form-control" id="InputDate1" placeholder="Введите дату" name="created_at" value="<?=isset($oldForm['created_at']) ? $oldForm['created_at'] : ''?>">
        </div>
        <div class="form-group">
          <label for="inputContentUrl">Url комментируемой страницы</label>
          <input type="text" class="form-control" id="inputContentUrl" placeholder="Введите url комментируемой страницы" name="content_url" value="<?=isset($oldForm['created_at']) ? $oldForm['created_at'] : ''?>">
        </div>
        <div class="form-group">
            <label for="InputStatus1">Статус</label>
            <select id="InputStatus1" class="form-control" name="status">
                <option value="0">Выключен</option>
                <option value="1">Активен</option>
            </select>
        </div>
        <div class="form-group">
            <label for="InputValidation1">Валидация</label>
            <select class="form-control" id="InputValidation1" name="validation">
                <option value="0">Нет</option>
                <option value="1">Да</option>
            </select>
        </div>

        <div class="form-group">
            <label for="InputDate1">Рейтинг: </label>
            <label class="radio-inline">
                <input type="radio" name="rating" id="inlineRadio1" value="1"> 1
            </label>
            <label class="radio-inline">
                <input type="radio" name="rating" id="inlineRadio2" value="2"> 2
            </label>
            <label class="radio-inline">
                <input type="radio" name="rating" id="inlineRadio3" value="3"> 3
            </label>
            <label class="radio-inline">
                <input type="radio" name="rating" id="inlineRadio3" value="4"> 4
            </label>
            <label class="radio-inline">
                <input type="radio" name="rating" id="inlineRadio3" value="5"> 5
            </label>
        </div>

        <div class="form-group">
            <label for="InputBody1">Текст комментария</label>
            <textarea class="form-control" rows="3" id="InputBody1" name="body"><?=isset($oldForm['body']) ? $oldForm['body'] : ''?></textarea>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
</div>
