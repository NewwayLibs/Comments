<?php namespace Newway\Comments\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Newway\Comments\Comments as CommentsClass;

class Comments extends Eloquent {

    public $timestamps = false;

    protected $fillable = array('content_type', 'content_id', 'status', 'rating', 'user_name', 'user_email', 'user_phone', 'user_ip', 'body');

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = array()) {

        $this->table = CommentsClass::getInstance()->getTable();

        parent::__construct($attributes);
    }

}