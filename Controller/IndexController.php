<?php
namespace Controller;
class IndexController {
    public function display()
    {
        $loader = new \Twig_Loader_Filesystem('Template');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $tmpl = $twig->loadTemplate('index.html.twig');
        $tmpl->display(array());
    }
}