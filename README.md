## Newway PHP comments

### Installation

**Require `newway/comments` in composer.json and run `composer update`.**

    "require": {
        "newway/comments": "dev-master"
        ...
    }
  
**Include "vendor/autoload.php" file to your project.**

    include ("../vendor/autoload.php");
  
**Configurate database conection:**

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

**Сreate an instance of the class**

    //create instance with standart parameters
    $comments = new Newway\Comments\Comments(
    
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

Using `Newway\Comments\Comments` class for CRUD operations.
    
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

    if ($comments->edit($id, $_POST)) {
        //if TRUE - updating without errors    
    } else {
        if ($errors = $comments->getValidationErrors()) {
            //validation errors
        } else {
            $errors = $comments->getErrors()
            //some other errors    
        }
    }
    
**Using comments admin panel**

To use package admin panel you must include Bootstap and jQuery libraries to your page:

    //Bootstrap from Google
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="dist/js/bootstrap.min.js" rel="stylesheet">
    //jQuery from Google
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

And init admin panel in the right place:

    //Generate admin HTML
    Newway\Comments\Init::initCommentsAdmin();
