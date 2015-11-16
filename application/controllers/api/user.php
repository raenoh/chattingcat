<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class User extends BaseController {

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
//        $username = $this->session->userdata('username');
//
//        if (empty($username)) {
//            header('Location:' . $this->config->base_url() . 'login');
//        }

        $this->load->model("nj_user");
        $this->load->library("common");
        $this->load->library('session');
        $this->load->library("pagination");
    }

    public function search_get() {
        $res = $this->nj_user->user_search_mob($_GET['term']);
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function friends_get() {
        $res = $this->nj_user->getFriends();
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail", "data" => "NO_FRIEND_FOUND"));
        }
    }

    public function incoming_get() {

        $res = $this->nj_user->getFriends('incoming');
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function outgoing_get() {

        $res = $this->nj_user->getFriends('outgoing');
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function profile_get() {
        $res = $this->nj_user->profile();
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function add_post() {

        $res = $this->nj_user->fndReqSend();
        if (!empty($res)) {
            $res = $this->db->get_where('users', array('j_id' => $_POST['t_j_id']))->row_array();
			
           // $whatIWant = substr($_POST['f_j_id'], strpos($_POST['f_j_id'], "_") + 1);
           // $whatIWant = explode('@', $whatIWant);

            $config = $this->config->config;
            $this->common->gcm(array($res['token']), array("title" => "Friend request", "message" => $res['name'] . " wants to be your friend"), $config['google_api_key']);
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail", "data" => "ALREADY_SEND"));
        }
    }

    public function update_post($status = NULL) {
        if ($status != "format") {
            if ($status == "true") {
                $_POST['status'] = 1;
            } else {
                $_POST['status'] = 0;
            }
            $res = $this->nj_user->update();
        } else {
            if (!empty($_FILES['avatar']['name'])) {
                $type = exif_imagetype($_FILES['avatar']['tmp_name']);
                switch ($type) {
                    case 1:
                        $ext = 'gif';
                        break;
                    case 2:
                        $ext = 'jpg';
                        break;
                    case 3:
                        $ext = 'png';
                        break;
                    default:
                        $this->response(array("status" => "fail", "data" => "select only jpg,png and gif image"));
                }

                $path = $_FILES['avatar']['name'];
                // $ext = pathinfo($path, PATHINFO_EXTENSION);
                $rand = 'img_' . rand(1, 500) . rand(500, 1000) . rand(1, 500);
                $config['upload_path'] = BASEPATH . "../avatar/";
                $config['allowed_types'] = '*';
                $config['file_name'] = $rand;
                $this->load->library('upload', $config);
                $this->upload->overwrite = true;
                $this->upload->do_upload('avatar');
                $data = $this->upload->data();
                $_POST['avatar'] = $this->config->base_url() . "avatar/" . $rand . '.' . $ext;
            }
            $res = $this->nj_user->update();
        }
        if (!empty($res)) {
            $this->response(array("status" => "success", "data" => $res));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function request_post() {

        $res = $this->nj_user->fndReqSend();
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail", "data" => "ALREADY_SEND"));
        }
    }

    public function accept_post() {
        $_POST['status'] = 1;
        $_POST['reject'] = 0;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            $res_f = $this->db->get_where('friends', array('f_id' => $_POST['f_id']))->row_array();
            
            $res = $this->db->get_where('users', array('j_id' => $res_f['f_j_id']))->row_array();
			
           // $whatIWant = substr($res_f['t_j_id'], strpos($res_f['t_j_id'], "_") + 1);
           // $whatIWant = explode('@', $whatIWant);

            $config = $this->config->config;
            $this->common->gcm(array($res['token']), array("title" => "Friend request", "message" =>  $res['name'] . " accepted your friend request"), $config['google_api_key']);
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function reject_post() {

        $res = $this->nj_user->fndReqAccept('reject');
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function block_post() {

        $_POST['reject'] = 1;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function unblock_post() {

        $_POST['reject'] = 0;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function logout_post() {
        $res = $this->nj_user->del_key('keys');
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

    public function addblock_post() {

        $res = $this->nj_user->addblock();
        if (!empty($res)) {
            $this->response(array("status" => "success"));
        } else {
            $this->response(array("status" => "fail"));
        }
    }

}