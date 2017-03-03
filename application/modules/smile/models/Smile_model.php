<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Smile_model extends MY_Model {
	private $module = 'smile';
	private $table = 'users';
    public function checkUser($data = array()){
        $this->db->select('id');
        $this->db->from(PREFIX . $this->table);
        $this->db->where(array('oauth_provider'=>$data['oauth_provider'],'oauth_uid'=>$data['oauth_uid']));
        $query = $this->db->get();
        $numRow = $query->num_rows();
        
        if($numRow > 0){
            $resultUser = $query->row_array();
            $data['update_at'] = date("Y-m-d H:i:s");
            $this->db->set('login_count', 'login_count+1', FALSE);
            $update = $this->db->update(PREFIX . $this->table, $data, array('id'=>$resultUser['id']));
            $userID = $resultUser['id'];
        }else{
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['update_at'] = date("Y-m-d H:i:s");
            $insert = $this->db->insert(PREFIX . $this->table, $data);
            $userID = $this->db->insert_id();
        }
        return $userID?$userID:FALSE;
    }
    function getFriendsList($user_id, $record,$start=0, $name=''){
        if($start != 0){
            $start = ($start)*$record;
        }
        $this->db->where('user_id', $user_id);
        if( $name ) $this->db->like('name', $name);
        $this->db->limit($record, $start)->order_by('appear_count', 'desc');
        $query = $this->db->get(PREFIX.'friends_list');
        if($query->result()){
            return $query->result();
        }else{
            return false;
        }
    }
    function getTotalUser(){
        $queryTotal = $this->db->get(PREFIX.$this->table);
        return $queryTotal->num_rows();
    }
    function listUser($record,$start=0){
        $this->db->order_by('created_at','desc');
        if($start != 0){
            $start = ($start-1)*$record;
        }
        $this->db->limit($record,$start);
        $query = $this->db->get(PREFIX.$this->table);
        if($query->result()){
            return $query->result();
        }else{
            return false;
        }   
    }
}

?>