<?php namespace Newway\Comments;

use Newway\Comments\Interfaces\CommentsTemplateInterface;
use Newway\Comments\Exceptions\TemplateFilesException;

class CommentsTemplate implements CommentsTemplateInterface
{

    private $cssPath = false;
    private $viewPath = false;

    /**
     * @param array $params (css path, view path)
     */
    public function __construct($params = array())
    {
        $this->cssPath = !empty($params['css_path']) ? $params['css_path'] : __DIR__ . '/Views/comments/assets/css/style.css';
        $this->viewPath = !empty($params['view_path']) ? $params['view_path'] : __DIR__ . '/Views/comments/views/index.php';
    }

    public function displayCss($print = true) {
        if(is_file($this->cssPath))
            if($content = file_get_contents($this->cssPath)) {
                $content = "<style>$content</style>";
                if(!$print)
                    return $content;
                echo $content;
                return true;
            }
        throw new TemplateFilesException("File $this->cssPath not found!");
    }

    public function display($list, $print = true) {
        if (!is_file($this->viewPath))
            throw new TemplateFilesException("File $this->viewPath not found!");
        ob_start();
        require($this->viewPath);
        $content = ob_get_clean();
        if(!$print)
            return $content;
        echo $content;
        return true;
    }

}
