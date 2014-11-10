<?php namespace Newway\Comments;

use Newway\Comments\Exceptions\NewwayCommentsException;
use Newway\Comments\Exceptions\ValidationFailException;
use Newway\Comments\Exceptions\CreateCommentException;
use Newway\Comments\Exceptions\UpdateCommentException;
use Newway\Comments\Exceptions\CommentNotFoundException;
use Newway\Comments\Models\Comments as CommentsModel;
use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\Translator as SymfonyTranslator;
use Newway\Comments\Interfaces\CommentsTemplateInterface;

class Comments
{
    /**
     * Validation rules
     * @var array
     */
    private $createRules = array();

    /**
     * Validation rules
     * @var array
     */
    private $editRules = array();

    /**
     * Language for validator
     * @var string
     */
    private  $lang = 'ru';

    /**
     * @var array
     */
    private $customMessages = array();

    private $customAttributes = array();

    /**
     * Table for comments
     *
     * @var string
     */
    private $table = 'comments';

    /**
     * List of messages. Array contains validation_errors, success message, safe errors.
     * 'validation_errors' => [ 'field name' => [ 0 => 'first error' ... ] ... ]
     * 'success' => 'one success message'
     * 'errors' => [ 0 => 'first error' ... ]
     *
     * @var array
     */
    protected $messages;

    /**
     * @var ValidatorFactory
     */
    protected $validator;

    /**
     * @var
     */
    protected static $_instance;

        /**
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        if(isset($config['createRules']))
            $this->createRules = $config['createRules'];
        if(isset($config['editRules']))
            $this->editRules = $config['editRules'];
        if(isset($config['customMessages']))
            $this->customMessages = $config['customMessages'];
        if(isset($config['customMessages']['attributes']))
            $this->customAttributes = $config['customMessages']['attributes'];
        if(isset($config['lang']))
            $this->lang = $config['lang'];
        if(isset($config['table']))
            $this->table = $config['table'];

        $this->createRules = array_merge(require(__DIR__ . "/../config/create_rules.php"), $this->createRules);
        $this->editRules = array_merge(require(__DIR__ . "/../config/edit_rules.php"), $this->editRules);
        $this->customMessages = array_merge(require(__DIR__ . "/../config/custom_messages.php"), $this->customMessages);
        $this->validator = new ValidatorFactory(new SymfonyTranslator($this->lang));
        self::$_instance = $this;
        return self::$_instance;
    }

    /**
     * Add new comment
     *
     * @param array $input
     * @throws \Exception
     * @return bool
     */
    public function create(array $input)
    {
        $this->clearMessages();
        $validator = $this->validator->make($input, $this->createRules, $this->customMessages, $this->customAttributes);
        if (!$validator->passes()) {
            $this->messages['validation_errors'] = $validator->errors()->toArray();
            throw new ValidationFailException;
        }
        $comment = new CommentsModel($input);
        $comment->setCreatedAt($comment->freshTimestamp());
        if($comment->save()){
            $this->messages['success'] = $this->customMessages['successfully_added'];
        } else {
            $this->messages['error'] = $this->customMessages['add_error'];
            throw new CreateCommentException;
        }
    }

    /**
     * Edit comment
     *
     * @param array $input
     * @return bool
     */
    public function edit($id, array $input)
    {
        $this->clearMessages();
        if(!$comment = CommentsModel::find($id)) {
            $this->messages['error'] = $this->customMessages['comment_not_found'];
            throw new CommentNotFoundException;
        }
        $validator = $this->validator->make($input, $this->editRules, $this->customMessages, $this->customAttributes);
        if (!$validator->passes()) {
            $this->messages['validation_errors'] = $validator->errors()->toArray();
            throw new ValidationFailException;
        }
        $comment->setCreatedAt($comment->freshTimestamp());
        if($comment->update($input)){
            $this->messages['success'] = $this->customMessages['successfully_edit'];
        } else {
            $this->messages['error'] = $this->customMessages['edit_error'];
            throw new UpdateCommentException;
        }
    }

    public function getComment($id) {
        $this->clearMessages();
        if(!$comment = CommentsModel::find($id)) {
            $this->messages['error'] = $this->customMessages['comment_not_found'];
            return array();
        }
        return $comment->toArray();
    }

    public function delete($id) {
        $this->clearMessages();
        if(CommentsModel::destroy($id)) {
            $this->messages['success'] = $this->customMessages['successfully_delete'];
            return true;
        } else {
            $this->messages['error'] = $this->customMessages['comment_not_found'];
            return false;
        }
    }

    /**
     * @param $content_key
     * @param bool $perPage
     * @param string $orderType
     * @return CommentsModel
     */
    public function getList(array $params = array(), $perPage = false, $orderType = 'ASC')
    {
        $comments = new CommentsModel();
        foreach($params as $name => $value) {
            $comments = $comments->where($name, $value);
        }
        if($perPage) {
            $commentsPage = isset($_REQUEST['commentsPage']) ? $_REQUEST['commentsPage'] : 1;
            $comments = $comments->offset(($commentsPage - 1) * $perPage)->limit($perPage);
        }
        if(!empty($orderType))
            $comments = $comments->orderBy('created_at', $orderType);
        $comments = $comments->get()->toArray();
        return $comments;
    }
    public function getListCount(array $params = array()) {
        $comments = new CommentsModel();
        foreach($params as $name => $value) {
            $comments = $comments->where($name, $value);
        }
        return $comments->count();
    }

    /**
     * Add new comment
     *
     * @param array $input
     * @return array
     */
    public function getContentKeysList()
    {
        $contentKeys = CommentsModel::distinct('content_type')->select('content_type')->get()->lists('content_type');
        return $contentKeys;
    }

    /**
     * Get instance of Comments
     *
     * @return Comments
     * @throws NewwayCommentsException
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            throw new NewwayCommentsException('Class Comments not created, create class first');
        }
        return self::$_instance;
    }

    public function clearMessages() {
        $this->messages = array(
            'validation_errors' => array(),
            'success' => null,
            'errors' => array()
        );
    }
    /**
     * Get all messages array
     *
     * @return null
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Get errors array
     *
     * @return array
     */
    public function getError() {
        return $this->messages['error'];
    }

    /**
     * Get errors array
     *
     * @return array
     */
    public function getValidationErrors() {
        return $this->messages['validation_errors'];
    }

    /**
     * Get success message
     *
     * @return string
     */
    public function getSuccess() {
        return $this->messages['success'];
    }

    /**
     * Get table
     *
     * @return null|string
     */
    public function getTable() {
        return $this->table;
    }
}
