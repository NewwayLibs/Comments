<?php namespace Newway\Comments;

use Newway\Comments\Exceptions\CaptchaException;
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
     * Use or not captcha
     *
     * @var string
     */
    private $useCaptcha = true;

    /**
     * List of messages. Array contains validation_errors, success message, safe errors.
     * 'success' => 'one success message'
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
    private function __construct(array $config = array())
    {
        if(isset($config['createRules']))
            $this->setCreateRules($config['createRules']);
        if(isset($config['editRules']))
            $this->setEditRules($config['editRules']);
        if(isset($config['customMessages']))
            $this->setCustomMessages($config['customMessages']);
        if(isset($config['lang']))
            $this->lang = $config['lang'];
        if(isset($config['table']))
            $this->table = $config['table'];
        if(isset($config['captcha']))
            $this->useCaptcha = $config['captcha'];

        $this->createRules = array_merge(require(__DIR__ . "/../config/create_rules.php"), $this->createRules);
        $this->editRules = array_merge(require(__DIR__ . "/../config/edit_rules.php"), $this->editRules);
        $this->customMessages = array_merge(require(__DIR__ . "/../config/custom_messages.php"), $this->customMessages);
        $this->validator = new ValidatorFactory(new SymfonyTranslator($this->lang));
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

        if($this->useCaptcha) {
          if(!CommentsCaptcha::checkCaptcha($input['captcha'])) {
            throw new CaptchaException($this->customMessages['invalid_captcha']);
          }
        }

        $validator = $this->validator->make($input, $this->createRules, $this->customMessages, $this->customAttributes);
        if (!$validator->passes()) {
            throw new ValidationFailException($this->customMessages['validation_fail'], $validator->errors()->toArray());
        }

        $comment = new CommentsModel($input);
        if(empty($input['created_at']))
            $comment->setCreatedAt($comment->freshTimestamp());
        if($comment->save()){
            if(!$comment->content_url) {
              $comment->content_url = "{$_SERVER['HTTP_REFERER']}#comment_{$comment->id}";
              $comment->save();
            }
            $this->messages['success'] = $this->customMessages['successfully_added'];
        } else {
            throw new CreateCommentException($this->customMessages['add_error']);
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
            throw new CommentNotFoundException($this->customMessages['comment_not_found']);
        }
        $validator = $this->validator->make($input, $this->editRules, $this->customMessages, $this->customAttributes);
        if (!$validator->passes()) {
          throw new ValidationFailException("Validation fail", $validator->errors()->toArray());
        }
        if($comment->update($input)){
            $this->messages['success'] = $this->customMessages['successfully_edit'];
        } else {
            throw new UpdateCommentException($this->customMessages['edit_error']);
        }
    }

    public function getComment($id) {
        $this->clearMessages();
        if(!$comment = CommentsModel::find($id)) {
            throw new CommentNotFoundException($this->customMessages['comment_not_found']);
        }
        return $comment->toArray();
    }

    public function delete($id) {
        $this->clearMessages();
        if(CommentsModel::destroy($id)) {
            $this->messages['success'] = $this->customMessages['successfully_delete'];
            return true;
        } else {
          throw new CommentNotFoundException($this->customMessages['comment_not_found']);
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
     * @return mixed
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
     */
    public static function getInstance(array $config = array()) {
      if (null === self::$_instance) {
        self::$_instance = new self($config);
      }
      return self::$_instance;
    }

    public function clearMessages() {
        $this->messages = array(
            'success' => null,
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
    private function setCreateRules(array $createRules){
      $this->createRules = $createRules;
    }
    private function setEditRules(array $editRules){
      $this->editRules = $editRules;
    }
    private function setCustomMessages(array $customMessages){
      $this->customMessages = $customMessages;
      if(isset($customMessages['attributes']))
        $this->customAttributes = $customMessages['attributes'];
    }
    public function turnOnCaptcha() {
        $this->useCaptcha = 1;
    }
    public function turnOffCaptcha() {
        $this->useCaptcha = 0;
    }
}
