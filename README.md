## Newway PHP comments

### Installation

**1) Require `newway/comments` in composer.json and run `composer update`.**

    "require": {
        "newway/comments": "dev-master"
        ...
    }
  
**2) Include "vendor/autoload.php" file to your project.**

    include ("../vendor/autoload.php");
  
**3) Configurate database conection:**

    Newway\Comments\Init::initDatabase(array(
          'driver' => 'mysql',
          'host' => 'localhost',
          'database' => 'database',
          'username' => 'username',
          'password' => 'password',
          'charset'   => 'utf8',
          'collation' => 'utf8_unicode_ci',
          'prefix'    => '',
      ));

**4) Create comments table:**

Run `Newway\Comments\Init::init()` once in any part of application, after `Newway\Comments\Init::initDatabase` method, to create comments table. You must run once this code, and delete it.
Init() metod create table with `id`, `content_type`, `content_id`, `user_name`, `user_email`, `user_phone`, `user_ip`, `status`, `rating`, `created_at` and `body` fields.

**5) Finaly create an instance of the class**

    //create instance with standart parameters
    $comments = new Newway\Comments\Comments();
    
    //or create instance with your configuration
    $comments = new Newway\Comments\Comments(
        array(
            'customMessages' => array(
                'required' => 'Field &laquo;:attribute&raquo; is required.',
                'attributes' => array(
                    'user_name' => 'Nickname',
                    'user_email' => 'Email',
                ),
            ),
            'createRules' => array(
                'user_email' => 'required',
            ),
            'updateRules' => array(
                'user_email' => 'required',
            ),
            'table' => 'comments_table'
        )
    );


### Using

**Operations with comments**

Use `Newway\Comments\Comments` class for CRUD operations.
    
    $comments = Comments::getInstance();
    
    //Create new comment
    $comments->create($id, [
        'name' => 'newway',
        'email' => 'newway@newway.com',
        'body' => 'My text'
      ]);
    
    //Read comment
    $pageComments = $comments->getList('pages');
    $allComments = getListAll();

    //Update
    $comments->update($id, $_POST);

    //Delete
    $comments->delete($id);

You can see saving or updating results: 

    try{
        $comments->edit($id, $_POST))
    } catch (Newway\Comments\Exceptions\ValidationFailException $e) {
        $errors = $comments->getValidationErrors();
    } catch (Newway\Comments\Exceptions\UpdateCommentException $e) {
        $error = $comments->getError();
    }

**Using comments admin panel**

To use package admin panel you must:

1) Include Bootstap and jQuery libraries to your page:

    //Bootstrap from Google
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="dist/js/bootstrap.min.js" rel="stylesheet">
    //jQuery from Google
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

2) Init admin panel in the right place:

    //Generate admin HTML
    Newway\Comments\Init::initCommentsAdmin();

**Using standard comments template**

    //Create objects
    $c = new Newway\Comments\Comments();
    $cTemplate = new Newway\Comments\CommentsTemplate();

    //Render styles and HTML
    $cTemplate->displayCss();
    $cTemplate->display(
        $c->getList(
            array(
                'content_type' => 'page',
                'content_id' => 1,
                'status' => 1,
            )
        )
    );