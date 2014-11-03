<?php namespace Newway\Comments;

use Newway\Comments\Exceptions\NewwayCommentsExceptions;
use Newway\Comments\Models\Comments as CommentsModel;
use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

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
     * @var int
     */
    protected $perPage = 10;

    /**
     * @var
     */
    protected static $_instance;

    /**
     * @param array $rules
     * @param array $customMessages
     */
    public function __construct(array $config = array())
    {
        if(isset($config['createRules']))
            $this->createRules = $config['createRules'];
        if(isset($config['editRules']))
            $this->editRules = $config['editRules'];
        if(isset($config['customMessages']))
            $this->customMessages = $config['customMessages'];
        if(isset($config['customMessages']))
            $this->lang = $config['lang'];
        if(isset($config['lang']))
            $this->lang = $config['lang'];
        if(isset($config['table']))
            $this->table = $config['table'];

        $this->createRules = array_merge($this->createRules, require(__DIR__ . "/../config/create_rules.php"));
        $this->editRules = array_merge($this->editRules, require(__DIR__ . "/../config/edit_rules.php"));
        $this->customMessages = array_merge($this->customMessages, require(__DIR__ . "/../config/custom_messages.php"));
        $this->validator = new ValidatorFactory(new SymfonyTranslator($this->lang));
        self::$_instance = $this;
        return self::$_instance;
    }

    /**
     * Add new comment
     *
     * @param array $input
     * @return bool
     */
    public function create(array $input)
    {
        $this->clearMessages();
        $validator = $this->validator->make($input, $this->createRules, $this->customMessages);
        if (!$validator->passes()) {
            $this->messages['validation_errors'] = $validator->errors()->toArray();
            return false;
        }
        $comment = new CommentsModel($input);
        $comment->setCreatedAt($comment->freshTimestamp());
        if($comment->save()){
            $this->messages['success'] = $this->customMessages['successfully_added'];
            return true;
        } else {
            $this->messages['errors'][] = $this->customMessages['add_error'];
            return false;
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
            $this->messages['errors'][] = $this->customMessages['comment_not_found'];
            return false;
        }
        $validator = $this->validator->make($input, $this->editRules, $this->customMessages);
        if (!$validator->passes()) {
            $this->messages['validation_errors'] = $validator->errors()->toArray();
            return false;
        }
        $comment->setCreatedAt($comment->freshTimestamp());
        if($comment->update($input)){
            $this->messages['success'] = $this->customMessages['successfully_edit'];
            return true;
        } else {
            $this->messages['errors'][] = $this->customMessages['edit_error'];
            return false;
        }
    }

    public function getComment($id) {
        $this->clearMessages();
        if(!$comment = CommentsModel::find($id)) {
            $this->messages['errors'][] = $this->customMessages['comment_not_found'];
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
            $this->messages['errors'][] = $this->customMessages['comment_not_found'];
            return false;
        }
    }

    /**
     * Add new comment
     *
     * @param array $input
     * @return bool
     */
    public function getList($content_key, $perPage = false, $orderType = 'ACS')
    {
        $comments = new CommentsModel();
        if($perPage) {
            $commentsPage = isset($_REQUEST['commentsPage']) ? $_REQUEST['commentsPage'] : 1;
            $comments = CommentsModel::where('content_type', $content_key)->offset(
                ($commentsPage - 1) * $perPage
            )->limit($perPage);
        }
        if(!empty($order))
            $comments = $comments->order('create_at', $orderType);
        $comments = $comments->get()->toArray();
        return $comments;
    }
    public function getListCount($content_key) {
        return CommentsModel::where('content_type', $content_key)->count();
    }

    /**
     * Add new comment
     *
     * @param array $input
     * @return bool
     */
    public function getListAll($perPage = false, $orderType = 'ACS')
    {
        $comments = new CommentsModel();
        if($perPage) {
            $commentsPage = isset($_REQUEST['commentsPage']) ? $_REQUEST['commentsPage'] : 1;
            $comments = CommentsModel::offset(($commentsPage - 1) * $perPage)->limit($perPage);
        }
        if(!empty($order))
            $comments = $comments->order('create_at', $orderType);
        $comments = $comments->get()->toArray();
        return $comments;
    }
    public function getListAllCount() {
        return CommentsModel::count();
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
     * @throws NewwayCommentsExceptions
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            throw new NewwayCommentsExceptions('Class Comments not created, create class first');
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
    public function getErrors() {
        return $this->messages['errors'];
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

    public function setPerpage($perPage) {
        $this->perPage = $perPage;
    }
}
