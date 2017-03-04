<?php
namespace App\Engine;

/**
 *
 * @property Request $request
 * @property Log $log
 * @property Db $db
 */
class Controller
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var
     */
    protected $template;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Controller constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        return $this->registry->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    /**
     * 
     * @param array $data
     * @param string $template
     * @return string
     */
    protected function render()
    {
        $template = DIR_TEMPLATE . '' . $this->template . '.templ.php';


        if (file_exists($template)) {
            extract($this->data);

            ob_start();

            require DIR_TEMPLATE . 'header.templ.php';
            require($template);
            require DIR_TEMPLATE . 'footer.templ.php';

            $output = ob_get_contents();

            ob_end_clean();

            echo $output;
        } else {
            trigger_error('Error: Could not load template ' . $template . '!', E_ERROR);
        }
    }

    /**
     * Redirect
     * @param string $url
     * @param int $status
     */
    protected function redirect($url, $status = 302)
    {
        header('Status: ' . $status);
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
        exit();
    }
}