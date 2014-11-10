<?php namespace Newway\Comments\Controllers;

use Newway\Comments\Comments;
use Newway\Comments\Route;
use Newway\Comments\Pagination;
use Newway\Comments\Exceptions\ValidationFailException;
use Newway\Comments\Exceptions\CreateCommentException;
use Newway\Comments\Exceptions\UpdateCommentException;

class AdminController
{
    public $perPage = 10;
    public $pageParameterName = 'commentsPage';
    protected $template = 'template';

    public function getIndex()
    {
        $header = "Все комментарии";
        $comments = Comments::getInstance()->getList(
            array(),
            $this->perPage,
            'DESC'
        );
        $count = Comments::getInstance()->getListCount();
        $paginator = new Pagination($count, $this->perPage, $this->pageParameterName);
        ob_start();
        require(__DIR__ . '/../Views/admin/index.php');
        $content = ob_get_clean();
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function getGroup($group)
    {
        $header = "Комментарии группы $group";
        $comments = Comments::getInstance()->getList(
            array(
                'content_type' => $group
            ),
            $this->perPage,
            'DESC'
        );
        $count = Comments::getInstance()->getListCount(
            array(
                'content_type' => $group
            )
        );
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
        $_SESSION['comments_add_form'] = $_POST;

        try {
            $comments->edit($id, $_POST);
        } catch(ValidationFailException $e) {
            $errors = $comments->getValidationErrors();
            ob_start();
            require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
            $_SESSION['comments_messages'][] = ob_get_clean();
            header(
                "Location: " . Route::replaceParameters(
                    Route::addParam($_GET, array('c_task' => 'edit', 'c_id' => $id))
                )
            );
            exit;
        } catch(UpdateCommentException $e) {
            $_SESSION['comments_messages'][] = $comments->getError();
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
            exit;
        }

        $_SESSION['comments_messages'][] = $comments->getSuccess();
        header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
        exit;
    }

    public function addComment()
    {
        $comments = Comments::getInstance();

        $_SESSION['comments_add_form'] = $_POST;
        try{
            $comments->create($_POST);
        } catch(ValidationFailException $e) {
            $errors = $comments->getValidationErrors();
            ob_start();
            require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
            $_SESSION['comments_messages'][] = ob_get_clean();
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add'))));
            exit;
        } catch(UpdateCommentException $e) {
            $_SESSION['comments_messages'][] = implode('<br>', $comments->getError());
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add'))));
            exit;
        }
        $_SESSION['comments_messages'][] = $comments->getSuccess();
        header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'all'))));
        exit;
    }

    public function deleteComment($id)
    {
        $comments = Comments::getInstance();

        $comments->delete($id);
    }
}