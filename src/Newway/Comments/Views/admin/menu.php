<? use Newway\Comments\Route;?>
<ul class="nav nav-list">
    <li class="nav-header">Меню</li>
    <li><a href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all')));?>">Все комментарии</a></li>
    <li class="nav-header">По групам:</li>
    <?foreach(Newway\Comments\Comments::getInstance()->getContentKeysList() as $contentKey):?>
        <li><a href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'group', 'content_type' => $contentKey)));?>"><?=$contentKey?></a></li>
    <?endforeach;?>
    <li class="nav-header">Управление:</li>
    <li><a href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add')));?>">Добавить</a></li>
    <? if(!isset($_GET['c_task']) ||  isset($_GET['c_task']) && ( in_array($_GET['c_task'], array('all', 'group')))): ?>
        <li class="nav-header">Прошедшие валидацию:</li>
        <li><a style="<?= isset($_GET['c_validation']) && $_GET['c_validation'] == 0 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_validation' => 0), false));?>">Нет</a></li>
        <li><a style="<?= isset($_GET['c_validation']) && $_GET['c_validation'] == 1 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_validation' => 1), false));?>">Да</a></li>
        <li><a style="<?= isset($_GET['c_validation']) && $_GET['c_validation'] == 2 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_validation' => 2), false));?>">Все</a></li>
        <li class="nav-header">Со статусом:</li>
        <li><a style="<?= isset($_GET['c_status']) && $_GET['c_status'] == 0 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_status' => 0), false));?>">Выключен</a></li>
        <li><a style="<?= isset($_GET['c_status']) && $_GET['c_status'] == 1 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_status' => 1), false));?>">Включен</a></li>
        <li><a style="<?= isset($_GET['c_status']) && $_GET['c_status'] == 2 ? ' background: #e8e8e8; ' : ''?>" href="<?=Route::replaceParameters(Route::addParam($_GET, array('c_status' => 2), false));?>">Все</a></li>
    <? endif; ?>
</ul>