<?php namespace Newway\Comments;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Newway\Comments\Exceptions\NewwayCommentsException;
use Newway\Comments\Controllers\AdminController;

/**
 * Class Init
 * @package Newway\Comments
 */
class Init
{

    /**
     * Create comment table
     * @param bool $hardInit
     * @throws NewwayCommentsException
     */
    public static function init($hardInit = false)
    {
        if($hardInit) {
          Capsule::schema()->drop('comments');
        }
        if (Capsule::schema()->hasTable('comments')) {
            throw new NewwayCommentsException('Table comments already exist, drop table and try again or use hardInit');
        }
        Capsule::schema()->create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('content_type', 32)->index();
                $table->integer('content_id')->unsigned()->index();
                $table->string('content_url');
                $table->string('content_url_title')->nullable();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable();
                $table->string('user_phone')->nullable();
                $table->string('user_ip')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('validation')->default(0);
                $table->tinyInteger('rating')->default(0);
                $table->timestamp('created_at')->default(Capsule::connection()->raw('CURRENT_TIMESTAMP'));
                $table->text('body');
            }
        );
    }

    /**
     * Init database connection
     *
     * @param array $parameters
     */
    public static function initDatabase(array $parameters)
    {

        $capsule = new Capsule;

        $capsule->addConnection($parameters);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }

    /**
     * Routes list
     */
    public static function initCommentsAdmin()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $controller = new AdminController(
            isset($_GET['c_validation']) ? $_GET['c_validation'] : 2,
            isset($_GET['c_status']) ? $_GET['c_status'] : 2
        );
        if (!isset($_GET['c_task']) || $_GET['c_task'] == 'all') {
            $controller->getIndex();
        } elseif ($_GET['c_task'] == 'add') {
            $controller->getAddPage();
        } elseif ($_GET['c_task'] == 'group') {
            $controller->getGroup($_GET['content_type']);
        } elseif ($_GET['c_task'] == 'edit') {
            $controller->getEditPage($_GET['c_id']);
        } elseif ($_GET['c_task'] == '_add') {
            $controller->addComment();
        } elseif ($_GET['c_task'] == '_edit') {
            $controller->editComment($_GET['c_id']);
        } elseif ($_GET['c_task'] == '_delete') {
            $controller->deleteComment($_GET['c_id']);
        } elseif ($_GET['c_task'] == 'toggle_validation') {
            $controller->toggleValidation($_GET['c_id']);
        } elseif ($_GET['c_task'] == 'toggle_status') {
            $controller->toggleStatus($_GET['c_id']);
        }
    }

}
