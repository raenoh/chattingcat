<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model("nj_user");
        $this->load->library('session');
    }

    public function login() {
        $res = $this->nj_user->login('admin');
        if (!empty($res)) {
		 $this->session->unset_userdata('j_id');
            $this->session->set_userdata(array(
                'username' => $res['username'],
            ));
            header('Location:' . $this->config->base_url() . 'user/all');
        } else {
            $this->load->view('user/userlogin');
        }
    }

    public function reset() {
        $data = "";
        if (!empty($_POST)) {
            $_POST['password'] = $_POST['o_password'];
            $res = $this->nj_user->login('admin');
            if (!empty($res)) {
                $_POST['password'] = $_POST['n_password'];
                unset($_POST['n_password']);
                unset($_POST['o_password']);
                $res = $this->nj_user->update_admin();
                $this->session->set_userdata(array(
                    "msg" => 'Password updated successfully',
                ));
            } else {
                $this->session->set_userdata(array(
                    "emsg" => 'Error Occur: Old Password are wrong',
                ));
            }
        }
       // $this->session->set_userdata(array(
        //            "emsg" => 'You can not change password in demo',
      //  ));
        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/reset_pass');
        $this->load->view('layout/footer');
    }

}

?>