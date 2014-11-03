<?php namespace Newway\Comments\Controllers;

use Newway\Comments\Comments;
use Newway\Comments\Route;
use Newway\Comments\Pagination;

class AdminController
{
    public $perPage = 10;
    public $pageParameterName = 'commentsPage';
    protected $template = 'template';

    public function getIndex()
    {
        $header = "Все комментарии";
        $comments = Comments::getInstance()->getListAll($this->perPage);
        $count = Comments::getInstance()->getListAllCount();
        $paginator = new Pagination($count, $this->perPage, $this->pageParameterName);
        ob_start();
        require(__DIR__ . '/../Views/admin/index.php');
        $content = ob_get_clean();
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function getGroup($group)
    {
        $header = "Комментарии группы $group";
        $comments = Comments::getInstance()->getList($group, $this->perPage);
        $count = Comments::getInstance()->getListCount($group);
        $paginator = new Pagination($count, $this->perPage, $this->pageParameterName);
        ob_start();
        require(__DIR__ . '/../Views/admin/index.php');
        $content = ob_get_clean();
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function getAddPage()
    {
        if ($_SESSION['comments_add_form']) {
            $oldForm = $_SESSION['comments_add_form'];
            unset($_SESSION['comments_add_form']);
        } else {
            $oldForm = false;
        }
        $header = "Добавление комментария";
        ob_start();
        require(__DIR__ . '/../Views/admin/add.php');
        $content = ob_get_clean();
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function getEditPage($id)
    {
        $header = "Редактирование комментария";
        $comments = Comments::getInstance();
        $comment = $comments->getComment($id);
        ob_start();
        require(__DIR__ . '/../Views/admin/edit.php');
        $content = ob_get_clean();
        if (empty($comment)) {
            $_SESSION['comments_messages'][] = implode('<br>', $comments->getErrors());
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
            exit;
        }
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function editComment($id)
    {
        $comments = Comments::getInstance();

        if ($comments->edit($id, $_POST)) {
            $_SESSION['comments_messages'][] = $comments->getSuccess();
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
        } else {
            $_SESSION['comments_add_form'] = $_POST;
            if ($errors = $comments->getValidationErrors()) {
                ob_start();
                require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
                $_SESSION['comments_messages'][] = ob_get_clean();
                header(
                    "Location: " . Route::replaceParameters(
                        Route::addParam($_GET, array('c_task' => 'edit', 'c_id' => $id))
                    )
                );
                exit;
            } else {
                $_SESSION['comments_messages'][] = implode('<br>', $comments->getErrors());
                header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
                exit;
            }
        }
    }

    public function addComment()
    {
        $comments = Comments::getInstance();

        if ($comments->create($_POST)) {
            $_SESSION['comments_messages'][] = $comments->getSuccess();
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
        } else {
            $_SESSION['comments_add_form'] = $_POST;
            if ($errors = $comments->getValidationErrors()) {
                ob_start();
                require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
                $_SESSION['comments_messages'][] = ob_get_clean();
                header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add'))));
                exit;
            } else {
                $_SESSION['comments_messages'][] = implode('<br>', $comments->getErrors());
                header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add'))));
                exit;
            }
        }
    }

    public function deleteComment($id)
    {
        $comments = Comments::getInstance();

        $comments->delete($id);
    }
}