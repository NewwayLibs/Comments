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
</ul>