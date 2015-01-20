<?php namespace Newway\Comments\Controllers;

use Newway\Comments\Comments;
use Newway\Comments\Exceptions\CommentNotFoundException;
use Newway\Comments\Route;
use Newway\Comments\Pagination;
use Newway\Comments\Exceptions\ValidationFailException;
use Newway\Comments\Exceptions\NewwayCommentsException;

class AdminController
{
    public $perPage = 10;
    public $pageParameterName = 'commentsPage';
    protected $template = 'template';

    /**
     * 0 - not validated, 1 - validated, 2 - all
     * @var int
     */
    protected $validation = 2;

    public function __construct($validation = 2) {
        $this->validation = $validation;
    }

    public function getIndex()
    {
        $_SESSION['comments_last_lists_task'] = $_GET['c_task'];
        $_SESSION['comments_last_lists_page'] = $_GET['commentsPage'];
        $header = "Все комментарии";
        $parameters = [];
        $parameters = $this->addValidation($parameters);
        $comments = Comments::getInstance()->getList(
            $parameters,
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
        $_SESSION['comments_last_lists_task'] = $_GET['c_task'];
        $_SESSION['comments_last_lists_page'] = $_GET['commentsPage'];
        $header = "Комментарии группы $group";
        $parameters = [
            'content_type' => $group
        ];
        $parameters = $this->addValidation($parameters);
        $comments = Comments::getInstance()->getList(
            $parameters,
            $this->perPage,
            'DESC'
        );
        $count = Comments::getInstance()->getListCount(
            $parameters
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
        try {
          $comment = $comments->getComment($id);
        } catch (CommentNotFoundException $e) {
            $_SESSION['comments_messages'][] = $e->getMessage();
            header(
                "Location: " . Route::replaceParameters(
                    Route::addParam(
                        $_GET,
                        array(
                            'c_task' => 'all'
                        )
                    )
                )
            );
            exit;
        }
        ob_start();
        require(__DIR__ . '/../Views/admin/edit.php');
        $content = ob_get_clean();
        include(__DIR__ . '/../Views/admin/template.php');
    }

    public function editComment($id)
    {
        $comments = Comments::getInstance();
        $_SESSION['comments_add_form'] = $_POST;

        try {
            $comments->edit($id, $_POST);
        } catch(ValidationFailException $e) {
            $errors = $e->getErrors();
            ob_start();
            require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
            $_SESSION['comments_messages'][] = ob_get_clean();
            header(
                "Location: " . Route::replaceParameters(
                    Route::addParam($_GET, array('c_task' => 'edit', 'c_id' => $id))
                )
            );
            exit;
        } catch(NewwayCommentsException $e) {
            $_SESSION['comments_messages'][] = $e->getMessage();
            header(
                "Location: " . Route::replaceParameters(
                    Route::addParam(
                        $_GET,
                        array(
                            'c_task' => !empty($_SESSION['comments_last_lists_task']) ? $_SESSION['comments_last_lists_task'] : 'all',
                            'commentsPage' => !empty($_SESSION['comments_last_lists_page']) ? $_SESSION['comments_last_lists_page'] : 1,
                            'content_type' => $_POST['type']
                        )
                    )
                )
            );
            exit;
        }

        $_SESSION['comments_messages'][] = $comments->getSuccess();
        header(
            "Location: " . Route::replaceParameters(
                Route::addParam(
                    $_GET,
                    array(
                        'c_task' => !empty($_SESSION['comments_last_lists_task']) ? $_SESSION['comments_last_lists_task'] : 'all',
                        'commentsPage' => !empty($_SESSION['comments_last_lists_page']) ? $_SESSION['comments_last_lists_page'] : 1,
                        'content_type' => $_POST['content_type']
                    )
                )
            )
        );
        exit;
    }

    public function addComment()
    {
        $comments = Comments::getInstance();

        $_SESSION['comments_add_form'] = $_POST;
        try{
            $comments->create($_POST);
        } catch(ValidationFailException $e) {
            $errors = $e->getErrors();
            ob_start();
            require(__DIR__ . '/../Views/admin/partials/validation_errors.php');
            $_SESSION['comments_messages'][] = ob_get_clean();
            header("Location: " . Route::replaceParameters(Route::addParam($_GET, array('c_task' => 'add'))));
            exit;
        } catch(NewwayCommentsException $e) {
            $_SESSION['comments_messages'][] = $e->getMessage();
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

    protected function addValidation($parameters) {
        if($this->validation != 2)
            $parameters['validation'] = $this->validation;
        return $parameters;
    }
}