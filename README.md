## Newway PHP comments

### Installation

**Require `newway/comments` in composer.json and run `composer update`.**

    "repositories": [
        {
            "type": "git",
            "url" : "https://github.com/newway/comments.git",
            "reference": "master"
        }
        ...
    ],
    "require": {
        "php": ">=5.3.0",
        "newway/comments": "dev-master"
        ...
    },
  ...
  
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

**Using admin panel**
Init admin module on the right place (next code generate admin HTML block):

    Newway\Comments\Init::initCommentsAdmin();
  
**CRUD comments**
Use `Newway\Comments\Comments` class to CRUD operations.

    $comments = new Newway\Comments\Comments();
    $comments->create($id, [
        'name' => 'newway',
        'email' => 'newway@newway.com',
        'body' => 'My text'
      ]);
    ...
    $comments->delete($id);
