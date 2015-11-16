<?php

class Nj_user extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->library('session');
    }

    function record_count() {
        return $this->db->count_all("users");
    }

    function getuserdata($limit = NULL, $start = NULL) {
        !empty($limit) ? $this->db->limit($limit, $start) : '';
        $str = $this->db->get("users"); //employee is a table in the database
        return $str->result_array();
    }
	
    function login($type = NULL) {
        $this->db->select('*');
        if ($type == "admin") {
            $this->db->from('admin');
            $data = array("username" => $_POST['username'], "password" => md5($_POST['password']));
        } elseif ($type == "web") {
            $this->db->from('users');
            $data = array("email" => $_POST['email'], "password" => $_POST['password'], "is_active" => 1, "status" => 1);
        } else {
            $this->db->from('users');
            $data = array("email" => $_GET['email'], "password" => $_GET['password'], "is_active" => 1, "status" => 1);
        }
        $this->db->where($data);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
    }

    function chk_dup($post, $table = NULL) {
        $uname = $post['mobile'];
        $this->db->select('count(*) as cnt');
        $this->db->from('users');
        $this->db->where('mobile', $uname);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
    }
    
    function chk_dup_fb($post, $table = NULL, $par = NULL) {
        $uname = $post['fb_id'];
        if ($par == "data") {
            $this->db->select('*');
        } else {
            $this->db->select('count(*) as cnt');
        }
        $this->db->from($table);
        $this->db->where("fb_id", $uname);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    function friends_record_count() {
        $query = $this->db->query("SELECT count(*) as cnt FROM `friends` f,users u where u.status = 1 and u.is_active = 1 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)  and f.status = 1 and u.j_id != " . $this->db->escape($_GET['j_id']) . " and (f.f_j_id = " . $this->db->escape($_GET['j_id']) . " or f.t_j_id = " . $this->db->escape($_GET['j_id']) . ")");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['cnt'];
        }
    }

    function getFriends_web($limit = NULL, $start = NULL) {
        $query = $this->db->query("SELECT * FROM `friends` f,users u where  u.status = 1 and u.is_active = 1 and  (f.t_j_id = u.j_id or f.f_j_id = u.j_id)  and f.status = 1 and u.j_id != " . $this->db->escape($_GET['j_id']) . " and (f.f_j_id = " . $this->db->escape($_GET['j_id']) . " or f.t_j_id = " . $this->db->escape($_GET['j_id']) . ") LIMIT " . $start . ", " . $limit . "");
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function search_friend_record_count($type = NULL) {
        if ($type == "incoming") {
            $str = "SELECT count(*) as cnt FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.t_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)";
        } elseif ($type == "outgoing") {
            $str = "SELECT count(*) as cnt FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.f_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)";
        } else {
            $str = "SELECT count(*) as cnt FROM `friends` f,users u where  u.status = 1 and u.is_active = 1 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)  and f.status = 1 and u.j_id != " . $this->db->escape($_POST['j_id']) . " and (f.f_j_id = " . $this->db->escape($_POST['j_id']) . " or f.t_j_id = " . $this->db->escape($_POST['j_id']) . ") ";
        }

        if (!empty($_POST['email'])) {
            $str .= " and u.email = " . $this->db->escape($_POST['j_id']) . "";
        }
        if (!empty($_POST['name'])) {
            $str .= " and u.name LIKE '%" . $_POST['name'] . "%'";
        }
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['cnt'];
        }
    }

    function search_friend_record($type = NULL, $limit = NULL, $start = NULL) {
        // echo $type.'---'.$limit.'---'.$start; die;
        if ($type == "incoming") {
            $str = "SELECT * FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.t_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id )";
        } elseif ($type == "outgoing") {
            $str = "SELECT * FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.f_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)";
        } else {
            $str = "SELECT * FROM `friends` f,users u where  u.status = 1 and u.is_active = 1 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)  and f.status = 1 and u.j_id != " . $this->db->escape($_POST['j_id']) . " and (f.f_j_id = " . $this->db->escape($_POST['j_id']) . " or f.t_j_id = " . $this->db->escape($_POST['j_id']) . ") ";
        }
        if (!empty($_POST['email'])) {
            $str .= " and email = " . $this->db->escape($_POST['j_id']) . "";
        }
        if (!empty($_POST['name'])) {
            $str .= " and name LIKE '%" . $_POST['name'] . "%'";
        }

        $str .= " LIMIT " . $start . ", " . $limit . "";

        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function friends_incoming_record_count() {
        $query = $this->db->query("SELECT count(*) as cnt FROM `friends` f, users u where u.status = 1 and u.is_active = 1 and f.t_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['cnt'];
        }
    }

    function friends_incoming_record($limit = NULL, $start = NULL) {
        $query = $this->db->query("SELECT * FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.t_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)  LIMIT " . $start . ", " . $limit . "");
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function friends_outgoing_record_count() {
        $query = $this->db->query("SELECT count(*) as cnt FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.f_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['cnt'];
        }
    }

    function friends_outgoing_record($limit = NULL, $start = NULL) {
        $query = $this->db->query("SELECT * FROM `friends` f, users u where  u.status = 1 and u.is_active = 1 and f.f_j_id = " . $this->db->escape($_POST['j_id']) . " and u.j_id != " . $this->db->escape($_POST['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id) LIMIT " . $start . ", " . $limit . "");
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function getFriends($type = NULL) {
        if ($type == "incoming") {
            $query = $this->db->query("SELECT * FROM `friends` f, users u where u.status = 1 and u.is_active = 1 and f.t_j_id = " . $this->db->escape($_GET['j_id']) . " and u.j_id != " . $this->db->escape($_GET['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)");
        } elseif ($type == "outgoing") {
            $query = $this->db->query("SELECT * FROM `friends` f, users u where u.status = 1 and u.is_active = 1 and f.f_j_id = " . $this->db->escape($_GET['j_id']) . " and u.j_id != " . $this->db->escape($_GET['j_id']) . " and f.status = 0 and (f.t_j_id = u.j_id or f.f_j_id = u.j_id)");
        } else {
            $query = $this->db->query("SELECT * FROM `friends` f, users u where u.status = 1 and u.is_active = 1 and  (f.t_j_id = u.j_id or f.f_j_id = u.j_id) and f.status = 1 and u.j_id != " . $this->db->escape($_GET['j_id']) . " and (f.f_j_id = " . $this->db->escape($_GET['j_id']) . " or f.t_j_id = " . $this->db->escape($_GET['j_id']) . " ) ");
        }
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    function fndReqAccept($type = NULL) {
        $this->db->where('f_id', $_POST['f_id']);
        $type == "reject" ? $this->db->delete('friends') : $this->db->update('friends', $_POST);
        $cnt = $this->db->affected_rows();
        if ($cnt >= 1) {
            return TRUE;
        }
    }

    function addnew() {
        $date = new DateTime();
        $_POST['time'] = $date->getTimestamp();
        $this->db->insert('friends', $_POST);
        $cnt = $this->db->affected_rows();
        if ($cnt >= 1) {
            return $this->db->insert_id();
        }
    }

    function chkConfirmId($conf_id) {
        $query = $this->db->query("SELECT count(*) as cnt FROM `users` WHERE BINARY `hashkey` = '" . $conf_id . "'");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if ($row['cnt'] > 0) {
                $this->db->query("update users set is_active = '1' where hashkey = '" . $conf_id . "'");
            }
            return $row;
        }
    }

    function update() {
        $this->db->where('j_id', $_POST['j_id']);
        $this->db->update('users', $_POST);
        $res = $this->db->get_where('users', array('j_id =' => $_POST['j_id']))->row_array();
        $cnt = $this->db->affected_rows();
        if ($cnt >= 1) {
            return $res;
        }
    }
    
      function user_update($post = NULL) {
        $this->db->where('fb_id', $_POST['fb_id']);
        $this->db->update('users', $_POST);
        return TRUE;
    }
    
    function user_signup() {
        $this->db->insert('users', $_POST);
        $id = $this->db->insert_id();
        if (!empty($id)) {
            return $id;
        }
    }

    function search_record_count() {
        $str = "select count(*) as cnt from users where 1 = 1";
        if (!empty($_POST['email'])) {
            $str .= " and email = '" . $_POST['email'] . "'";
        }
        if (!empty($_POST['name'])) {
            $str .= " and name LIKE '%" . $_POST['name'] . "%'";
        }
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['cnt'];
        }
    }

    function user_search($limit = NULL, $start = NULL) {
        $str = "select * from users where 1 = 1";

        if (!empty($_POST['email'])) {
            $str .= " and email = '" . $_POST['email'] . "'";
        }
        if (!empty($_POST['name'])) {
            $str .= " and name LIKE '%" . $_POST['name'] . "%'";
        }
        $str .= " LIMIT " . $start . ", " . $limit . "";
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            $row = $query->result_array();
            return $row;
        }
    }

    function profile($type = NULL) {

        if ($type == "profile") {
            $row = $this->db->get_where('users', array('status' => 1, 'is_active' => 1, 'email' => $_GET['t_j_id']))->row_array();
            return $row;
        } else {
            $row = $this->db->get_where('users', array('status' => 1, 'is_active' => 1, 'j_id' => $_GET['t_j_id']))->row_array();
        }

        $str = $this->db->query("select * from friends where f_j_id = " . $this->db->escape($_GET['f_j_id']) . " and t_j_id = " . $this->db->escape($_GET['t_j_id']) . " limit 1");
        $row_friend = $str->row_array();
        if (!empty($row_friend)) {
            $row['f_id'] = $row_friend['f_id'];
            !empty($row_friend['status']) ? $row['is_friend'] = 'true' : $row['is_friend'] = 'false';
            $row['is_friend_out'] = 'true';
            $row['is_friend_in'] = 'false';
            !empty($row_friend['reject']) ? $row['is_block'] = 'true' : $row['is_block'] = 'false';
        } else {
            $str = $this->db->query("select * from friends where f_j_id = " . $this->db->escape($_GET['t_j_id']) . " and t_j_id = " . $this->db->escape($_GET['f_j_id']) . " limit 1");
            $row_friend = $str->row_array();
            if (!empty($row_friend)) {
                $row['f_id'] = $row_friend['f_id'];
                !empty($row_friend['status']) ? $row['is_friend'] = 'true' : $row['is_friend'] = 'false';
                $row['is_friend_in'] = 'true';
                $row['is_friend_out'] = 'false';
                $row['is_block'] = 'false';
                if (!empty($row_friend['reject'])) {
                    unset($row);
                }
            } else {
                $row['is_friend'] = 'false';
                $row['is_friend_out'] = 'false';
                $row['is_friend_in'] = 'false';
                $row['is_block'] = 'false';
            }
        }
        return $row;
    }

    function user_search_mob($term) {
        $str = "select * from users where (email LIKE '%" . $term . "%' or LOWER(name) LIKE LOWER('%" . $term . "%')) and j_id != " . $this->db->escape($_GET['j_id']) . " and is_active = 1 and status = 1";
        $query = $this->db->query($str);
        if ($query->num_rows() > 0) {
            $row = $query->result_array();

            if (!empty($row)) {
                foreach ($row as $key => $val) {
                    $str = $this->db->query("select * from friends where f_j_id = '" . $_GET['j_id'] . "' and t_j_id = '" . $val['j_id'] . "' limit 1");
                    $row_friend = $str->row_array();
                    if (!empty($row_friend)) {
                        $row[$key]['f_id'] = $row_friend['f_id'];
                        !empty($row_friend['status']) ? $row[$key]['is_friend'] = 'true' : $row[$key]['is_friend'] = 'false';
                        $row[$key]['is_friend_out'] = 'true';
                        $row[$key]['is_friend_in'] = 'false';
                        !empty($row_friend['reject']) ? $row[$key]['is_block'] = 'true' : $row[$key]['is_block'] = 'false';
                    } else {
                        $str = $this->db->query("select * from friends where f_j_id = '" . $val['j_id'] . "' and t_j_id = '" . $_GET['j_id'] . "' limit 1");
                        $row_friend = $str->row_array();
                        if (!empty($row_friend)) {
                            $row[$key]['f_id'] = $row_friend['f_id'];
                            !empty($row_friend['status']) ? $row[$key]['is_friend'] = 'true' : $row[$key]['is_friend'] = 'false';
                            $row[$key]['is_friend_in'] = 'true';
                            $row[$key]['is_friend_out'] = 'false';
                            $row[$key]['is_block'] = 'false';
                            if (!empty($row_friend['reject'])) {
                                unset($row[$key]);
                            }
                        } else {
                            $row[$key]['is_friend'] = 'false';
                            $row[$key]['is_friend_out'] = 'false';
                            $row[$key]['is_friend_in'] = 'false';
                            $row[$key]['is_block'] = 'false';
                        }
                    }
                }
            }
            if (!empty($row)) {
                return $row;
            } else {
                return false;
            }
        }
    }

    function addblock() {
        $query = $this->db->query("select count(*) as cnt from friends where (f_j_id = " . $this->db->escape($_POST['f_j_id']) . " and t_j_id = " . $this->db->escape($_POST['t_j_id']) . ") or (f_j_id = " . $this->db->escape($_POST['t_j_id']) . " and t_j_id = " . $this->db->escape($_POST['f_j_id']) . ")");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if (empty($row['cnt'])) {
                $_POST['reject'] = 1;
                $this->db->insert('friends', $_POST);
                $id = $this->db->insert_id();
                if (!empty($id)) {
                    return $id;
                }
            } else {
                $this->db->query("update friends set reject = 1 where (f_j_id = " . $this->db->escape($_POST['f_j_id']) . " and t_j_id = " . $this->db->escape($_POST['t_j_id']) . ") or (f_j_id = " . $this->db->escape($_POST['t_j_id']) . " and t_j_id = " . $this->db->escape($_POST['f_j_id']) . ")");
                $cnt = $this->db->affected_rows();
                if ($cnt >= 1) {
                    return TRUE;
                }
            }
        }
    }

    function fndReqSend() {
        $query = $this->db->query("select count(*) as cnt from friends where (f_j_id = " . $this->db->escape($_POST['f_j_id']) . " and t_j_id = " . $this->db->escape($_POST['t_j_id']) . ") or (f_j_id = " . $this->db->escape($_POST['t_j_id']) . " and t_j_id = " . $this->db->escape($_POST['f_j_id']) . ")");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            if (empty($row['cnt'])) {
                $this->db->query("insert into friends(f_j_id,t_j_id,time)VALUES(" . $this->db->escape($_POST['f_j_id']) . "," . $this->db->escape($_POST['t_j_id']) . ",UNIX_TIMESTAMP(now()))");
                $id = $this->db->insert_id();
                if (!empty($id)) {
                    return $id;
                }
            }
        }
    }

    function del_key($table) {
        $data = array("user_id" => $_POST['user_id'], "key" => $_POST['key']);
        $this->db->where($data);
        $this->db->delete($table);
        $cnt = $this->db->affected_rows();
        if ($cnt >= 1) {
            return TRUE;
        }
    }

    function update_admin() {
        $this->db->where('username', $_POST['username']);
        $this->db->update('admin', array("password"=>md5($_POST['password'])));
        $cnt = $this->db->affected_rows();
        if ($cnt >= 1) {
            return $cnt;
        }
    }

}

?>