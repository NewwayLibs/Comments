<?php namespace Newway\Comments;

class Pagination
{
    private $total;
    private $pageParameterName;

    public function __construct($total, $perPage, $pageParameterName)
    {
        $this->total = $total;
        $this->lastPage = ceil($total / $perPage);
        $this->pageParameterName = $pageParameterName;
    }

    public function getLastPage()
    {
        return $this->lastPage;
    }

    public function getCurrentPage()
    {
        return (isset($_GET[$this->pageParameterName])) ? $_GET[$this->pageParameterName] : 1;
    }
    public function getUrlToPage($page) {
        if ($page > $this->getLastPage()) $page = 1;
        $params = $_GET;
        $params[$this->pageParameterName] = $page;
        return $_SERVER['SCRIPT_NAME'] . '?' . http_build_query($params);
    }
}
