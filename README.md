## Newway PHP comments

### Installation

**Require `daegon/comments` in composer.json and run `composer update`.**

  "repositories": [
      {
          "type": "git",
          "url" : "https://github.com/Daegon/comments.git",
          "reference": "master"
      }
      ...
  ],
  "require": {
      "php": ">=5.3.0",
      "daegon/comments": "dev-master"
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
      'name' => 'daegon',
      'email' => 'daegon@daegon.com',
      'body' => 'My text'
    ]);
  ...
  $comments->delete($id);
