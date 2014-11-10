<?php
/**
 * 
 * @author
 * @version 
 */
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View Helper 
 * 
 */
class GoogleAnalytics extends AbstractHelper
{
   
    public function __invoke($id)
    {
 
        $str = ''; 
        $isMe = false; 
        $isDev = false;
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipArr = array('192.168.33.1');
            $isMe = in_array($_SERVER['REMOTE_ADDR'], $ipArr);
        }
        if (isset($_SERVER['HTTP_HOST'])) {
            $isDev = preg_match('~dev\.~is', $_SERVER['HTTP_HOST']);
        }
        if ($isMe == false && $isDev == false) { 
            $str = "<script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
              ga('create', '$id', 'auto');
              ga('send', 'pageview');
            </script>";
        } 
        return $str;
    }
    
}