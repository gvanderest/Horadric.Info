<?php
/**
 * Example Application
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class Example_Application extends Exo_Controller
{
    /**
     * Example method
     * @param array $args (optional)
     * @return bool
     */
    public function hello_world($args)
    {
        $output = '';
        $output .= '<h1>Hello World</h1>';
        $output .= '<p>The arguments you sent this controller/method are:</p>';
        $output .= '<pre>' . var_export($args, TRUE) . '</pre>';

        $this->data['content'] = $output;

        $this->load->view('default');
    }
}
