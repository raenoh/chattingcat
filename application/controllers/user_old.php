<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

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
        $this->load->library("common");
        $this->load->library('session');
        $this->load->library("pagination");
    }

    public function search_web() {
        $_GET['j_id'] = $this->session->userdata('j_id');
        $data['result'] = $this->nj_user->user_search_mob($_GET['term']);

        $this->load->view('user/user_search', $data);
    }

    public function add() {
        $_POST['f_j_id'] = $this->session->userdata('j_id');
        $_POST['t_j_id'] = $_POST['f_id'];
        unset($_POST['f_id']);
        $res = $this->nj_user->fndReqSend();
        if (!empty($res)) {
            $res = $this->db->get_where('users', array('j_id' => $_POST['t_j_id']))->row_array();
            $whatIWant = substr($_POST['f_j_id'], strpos($_POST['f_j_id'], "_") + 1);
            $whatIWant = explode('@', $whatIWant);

            $config = $this->config->config;
            $this->common->gcm(array($res['token']), array("title" => "Friend request", "message" => $whatIWant[0] . " wants to be your friend"), $config['google_api_key']);
            echo 'success';
        }
    }

    public function addblock() {
        $_POST['f_j_id'] = $this->session->userdata('j_id');
        $_POST['t_j_id'] = $_POST['f_id'];
        $res = $this->nj_user->addblock();
        $res = $this->nj_user->addnew();
        if (!empty($res)) {
            echo 'success';
        }
    }

    public function all() {
        $username = $this->session->userdata('username');
        if (empty($username)) {
            header('Location:' . $this->config->base_url() . 'login');
        }

        $config = array();
        $config["base_url"] = $this->config->base_url() . "user/all";
        $config["total_rows"] = $this->nj_user->record_count();
        $data['user_cnt'] = $config["total_rows"];
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["result"] = $this->nj_user->
                getuserdata($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();
        //$data['result'] = $this->nj_user->getuserdata();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/user_list', $data);
        $this->load->view('layout/footer');
    }

    public function update($status = NULL) {
        if ($status == "true") {
            $_POST['status'] = 1;
        } else {
            $_POST['status'] = 0;
        }
        $res = $this->nj_user->update();
        if (!empty($res)) {
            echo 'success';
        }
    }

    public function search() {

        $username = $this->session->userdata('username');

        if (empty($username)) {
            header('Location:' . $this->config->base_url() . 'login');
        }
        if (empty($_POST['email']) && empty($_POST['name'])) {
            header('Location:' . $this->config->base_url() . 'user/all');
        }
        !empty($_POST['email']) ? $data['email'] = $_POST['email'] : '';
        !empty($_POST['name']) ? $data['name'] = $_POST['name'] : '';

        $config = array();
        $config["base_url"] = $this->config->base_url() . "user/search";
        $config["total_rows"] = $this->nj_user->search_record_count();

        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["result"] = $this->nj_user->
                user_search($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/user_list', $data);
        $this->load->view('layout/footer');
    }

    public function register() {
		if (!empty($_POST['email'])) {
            error_reporting(E_ERROR | E_PARSE);
            $dup = $this->nj_user->chk_dup($_POST);
            $randomString = $this->common->Generate_hash(16);
            if (empty($dup['cnt'])) {
                  $_POST['hashkey'] = $randomString;
                    $pass = $_POST['password'];
                    // encrypt password
                    $_POST['password'] = $this->common->encrypt_string($pass, $randomString);

                    $res = $this->nj_user->user_signup();
					
					$res = $this->db->get_where("users",array("user_id"=>$res))->row_array();
					
					$this->load->helper('string');
					$rand = random_string('alnum', 8) . random_string('numeric', 8) . random_string('alnum', 8) . random_string('numeric', 8);

					$data = array(
						"user_id" => $res['user_id'],
						"key" => $rand
					);
					$this->db->insert("keys", $data);

					$cnt = $this->db->affected_rows();
					if ($cnt > 0) {
						$res['key'] = $rand;
					}
                    if (!empty($res)) {
                        $data = array("status" => "success","data"=>$res);
                        $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode($data));
                    } else {
                        $data = array("status" => "fail", "data" => "Not Inserted");
                        $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode($data));
                    }
                
            } else {
            	$this->db->where('email',$_POST['email']);
            	$this->db->update('users', $_POST);
       		
				$res = $this->db->get_where("users",array("email"=>$_POST['email']))->row_array();
				
					$this->load->helper('string');
					$rand = random_string('alnum', 8) . random_string('numeric', 8) . random_string('alnum', 8) . random_string('numeric', 8);

					$data = array(
						"user_id" => $res['user_id'],
						"key" => $rand
					);
					$this->db->insert("keys", $data);

					$cnt = $this->db->affected_rows();
					if ($cnt > 0) {
						$res['key'] = $rand;
					}
				
                $data = array("status" => "success", "data" => $res);
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($data));
            }
        }
    }

    public function confirm($conf_id = null) {
        $data = '';
        if (!empty($conf_id)) {
            $resid = $this->nj_user->chkConfirmId($conf_id);
            if (!empty($resid['cnt'])) {
                $data['msg'] = "You are successfully registered";
            } else {
                $data['emsg'] = "Error Occur: Try again";
            }
            $this->load->view("user/confirm", $data);
        }
    }

    public function friends_search() {

        !empty($_POST['type']) ? $type = $_POST['type'] : $type = "";
        $j_id = $this->session->userdata('j_id');
        if (empty($j_id)) {
            header('Location:' . $this->config->base_url() . 'login');
        }

        if (empty($_POST['email']) && empty($_POST['name'])) {
            if (!empty($type)) {

                header('location:' . $this->config->base_url() . 'user/' . $type);
            } else {
                header('location:' . $this->config->base_url() . 'user/friends');
            }
        }
        !empty($_POST['email']) ? $data['email'] = $_POST['email'] : '';
        !empty($_POST['name']) ? $data['name'] = $_POST['name'] : '';

        $config = array();
        $_POST['j_id'] = $j_id;
        if (!empty($type)) {
            $config["base_url"] = $this->config->base_url() . "user/friends_search/" . $type;
            $config["total_rows"] = $this->nj_user->search_friend_record_count($type);
        } else {
            $config["base_url"] = $this->config->base_url() . "user/friends_search";
            $config["total_rows"] = $this->nj_user->search_friend_record_count();
        }
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if (!empty($type)) {
            $data["result"] = $this->nj_user->search_friend_record($type, $config["per_page"], $page);
        } else {
            $data["result"] = $this->nj_user->search_friend_record('', $config["per_page"], $page);
        }

        $data["links"] = $this->pagination->create_links();
        !empty($type) ? $page_view = $type : $page_view = 'friends';

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/' . $page_view, $data);
        $this->load->view('layout/footer');
    }

    public function friends() {
        $j_id = $this->session->userdata('j_id');
        if (empty($j_id)) {
            header('Location:' . $this->config->base_url() . 'login');
        } else {
            $_GET['j_id'] = $j_id;
        }
        $config = array();
        $config["base_url"] = $this->config->base_url() . "user/all";
        $config["total_rows"] = $this->nj_user->friends_record_count();
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["result"] = $this->nj_user->
                getFriends_web($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();


        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/friends', $data);
        $this->load->view('layout/footer');
    }

    public function login() {
        $_GET['t_j_id'] = $_GET['email'];
        $res = $this->nj_user->profile('profile');

        if (!empty($res['hashkey']) && !empty($res['password'])) {
            $password = $this->common->decrypt_string($res['password'], $res['hashkey']);
            if ($password == $_GET['password']) {
                $this->load->helper('string');
                $rand = random_string('alnum', 8) . random_string('numeric', 8) . random_string('alnum', 8) . random_string('numeric', 8);

                $data = array(
                    "user_id" => $res['user_id'],
                    "key" => $rand
                );
                $this->db->insert("keys", $data);

                $cnt = $this->db->affected_rows();
                if ($cnt > 0) {
                    $res['key'] = $rand;
                }
                $res['password'] = $password;
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array("status" => "success", "data" => $res)));
            } else {
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array("status" => "fail")));
            }
        } else {
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array("status" => "fail")));
        }
    }

    public function login_web() {
        $_GET['t_j_id'] = $_POST['username'];
        $res = $this->nj_user->profile('profile');
        if (!empty($res['hashkey']) && !empty($res['password'])) {
            $password = $this->common->decrypt_string($res['password'], $res['hashkey']);
            if ($password == $_POST['password']) {
                if (!empty($res)) {
                    $this->session->set_userdata(array(
                        'j_id' => $res['j_id'],
                        'email' => $res['email'],
                        'name' => $res['name'],
                        'avatar' => $res['avatar'],
                        'user_status' => $res['user_status'],
                        'mobile' => $res['mobile']
                    ));
                    header('Location:' . $this->config->base_url() . "user/friends");
                }
            } else {
                $this->session->set_userdata(array(
                    "emsg" => 'Email and Password are wrong',
                ));
                header('Location:' . $this->config->base_url() . "login");
            }
        } else {
            $this->session->set_userdata(array(
                "emsg" => 'Email and Password are wrong',
            ));
            header('Location:' . $this->config->base_url() . "login");
        }
    }

    public function reject() {
        $res = $this->nj_user->fndReqAccept('reject');
        if (!empty($res)) {
            echo 'success';
        }
    }

    public function block() {
        $_POST['reject'] = 1;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            echo 'success';
        }
    }

    public function unblock() {
        $_POST['reject'] = 0;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            echo 'success';
        }
    }

    public function accept() {
        $_POST['status'] = 1;
        $_POST['reject'] = 0;
        $res = $this->nj_user->fndReqAccept();
        if (!empty($res)) {
            $res_f = $this->db->get_where('friends', array('f_id' => $_POST['f_id']))->row_array();

            $res = $this->db->get_where('users', array('j_id' => $res_f['f_j_id']))->row_array();
            $whatIWant = substr($res_f['t_j_id'], strpos($res_f['t_j_id'], "_") + 1);
            $whatIWant = explode('@', $whatIWant);

            $config = $this->config->config;
            $this->common->gcm(array($res['token']), array("title" => "Friend request", "message" => $whatIWant[0] . " accept your friend request"), $config['google_api_key']);
            echo 'success';
        }
    }

    public function incoming() {

        // $res = $this->nj_user->getFriends('incoming');

        $j_id = $this->session->userdata('j_id');
        if (empty($j_id)) {
            header('Location:' . $this->config->base_url() . 'login');
        } else {
            $_POST['j_id'] = $j_id;
        }
        $config = array();
        $config["base_url"] = $this->config->base_url() . "user/incoming";
        $config["total_rows"] = $this->nj_user->friends_incoming_record_count();
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["result"] = $this->nj_user->
                friends_incoming_record($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/incoming', $data);
        $this->load->view('layout/footer');
    }

    public function outgoing() {
        $j_id = $this->session->userdata('j_id');
        if (empty($j_id)) {
            header('Location:' . $this->config->base_url() . 'login');
        } else {
            $_POST['j_id'] = $j_id;
        }
        $config = array();
        $config["base_url"] = $this->config->base_url() . "user/outgoing";
        $config["total_rows"] = $this->nj_user->friends_outgoing_record_count();
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="active">';
        $config['first_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="disabled">';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="disabled">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active">';
        $config['cur_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li class="waves-effect">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["result"] = $this->nj_user->
                friends_outgoing_record($config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();

        $this->load->view('layout/header');
        $this->load->view('layout/sidebar');
        $this->load->view('user/outgoing', $data);
        $this->load->view('layout/footer');
    }

    public function logout() {
        $j_id = $this->session->userdata('j_id');
        $this->session->sess_destroy();
        if (empty($j_id)) {
            header('Location:' . $this->config->base_url() . "login/admin");
        } else {
            header('Location:' . $this->config->base_url() . "login");
        }
    }

}