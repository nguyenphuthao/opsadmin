<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_model extends MY_Model {
	private $module = 'users';
	private $table = 'users';
 	private $retval = array(
        'status'    =>  0,
        'msg'       =>  ''
    );
    /* BEGIN ADMINCP ------------------------*/
    function getsearchContent($record,$start=0, $flag=0){
		// $this->db->select('*');
		$this->db->order_by($this->input->post('func_order_by'), $this->input->post('order_by'));
		if($this->input->post('name')!=''){
			$this->db->where('(`name` LIKE "%'.$this->input->post('name').'%" OR `first_name` LIKE "%'.$this->input->post('name').'%" OR `last_name` LIKE "%'.$this->input->post('name').'%" OR `email` LIKE "%'.$this->input->post('name').'%")');
		}	
		if($flag) return $this->db->count_all_results(PREFIX.$this->table);
		else{
			$start ? $start = ($start-1)*$record : $start;
			// $this->db->select('*');
			$this->db->limit($record,$start);
			return $this->db->get(PREFIX.$this->table)->result();
		}
	}
	function getDetailManagement($id){
		$this->db->select('*');
		$this->db->where('id',$id);
		$query = $this->db->get(PREFIX.$this->table);
		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	function getTotalUserCreateVideo($flag=0){
		$sql = 'SELECT COUNT(DISTINCT `user_id`) as total FROM ops_images_select';
		if($flag == 1) $sql = "SELECT COUNT(DISTINCT `user_id`) as total FROM ops_images_select WHERE video_file != ''";
		$query = $this->db->query($sql);
		return $query->row()->total;
	}
	function getTotalVideo($user_id, $flag = 0){
		if($user_id) $this->db->where('user_id', $user_id);
		if($flag) $this->db->where("`video_file` != ''");
		return $this->db->count_all_results(PREFIX."images_select");
	}
	function getListVideoUser($user_id){
		$this->db->where('user_id', $user_id);		
		$query = $this->db->get(PREFIX."images_select");
		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	function getTotalShare($oauth_uid){
		$this->db->where('oauth_uid', $oauth_uid);
		return $this->db->count_all_results(PREFIX."tracking_share");
	}
	function getListVideo($record, $start=0){
		$this->db->select('t1.video_file, t1.id, t2.name, t2.oauth_uid, t1.created_at, t2.id AS user_id');
		$this->db->where("`video_file` != ''");
		$start ? $start = ($start-1)*$record : $start;
		$this->db->limit($record,$start);
		$this->db->order_by('created_at', 'desc');
		$this->db->join(PREFIX.'users AS t2', "t2.id = t1.user_id", 'left');
		$query = $this->db->get(PREFIX."images_select AS t1");
		if($query->result()){
			return $query->result();
		}else{
			return false;
		}
	}
	/*--------------------- END ADMINCP ---------------------*/
}

?>