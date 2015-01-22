<?php namespace Newway\Comments;

class Route
{
    /**
     * Add parameters to array
     * @param $arr
     * @param $params
     * @return array
     */
    public static function addParam($arr, $params, $reset = true)
    {
        if ($reset) {
            unset($arr['content_type']);
            unset($arr['c_id']);
            unset($arr['commentsPage']);
        }
        $arr = array_merge($arr, $params);
        return $arr;
    }

    /**
     * Remove parameter
     * @param $arr
     * @param $param
     * @return array
     */
    public static function removeParam($arr, $param)
    {
        unset($arr[$param]);
        return $arr;
    }

    /**
     * Replace parameters in url
     * @param $arr
     * @return string
     */
    public static function replaceParameters($arr)
    {
        return $_SERVER['REDIRECT_URL'] . '?' . http_build_query($arr);
    }

}
