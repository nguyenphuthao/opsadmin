<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Share_model extends MY_Model {
	private $module = 'share';
	private $table = 'tracking_share';
 	private $retval = array(
        'status'    =>  0,
        'msg'       =>  ''
    );
    /* BEGIN ADMINCP ------------------------*/
    function getsearchContent($record,$start=0, $flag=0){
		$this->db->select('*');
		$this->db->join(PREFIX.'users AS t2', 't2.oauth_uid = t1.oauth_uid', 'left');
		if($this->input->post('name')!=''){
			$this->db->where('(`t1.oauth_uid1` LIKE "%'.$this->input->post('name').'%" OR `t2.name` LIKE "%'.$this->input->post('name').'%")');
		}	
		if($flag) return $this->db->count_all_results(PREFIX.$this->table . " AS t1");
		else{
			$start ? $start = ($start-1)*$record : $start;
			$this->db->limit($record,$start);
			$this->db->order_by("t1.".$this->input->post('func_order_by'), $this->input->post('order_by'));
			return $this->db->get(PREFIX.$this->table ." AS t1")->result();
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
	function getTotalVideo($user_id, $flag = 0){
		$this->db->where('user_id', $user_id);
		if($flag) $this->db->where("`video_file` != ''");
		return $this->db->count_all_results(PREFIX."images_select");
	}
	function getListVideo($user_id){
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
	/*--------------------- END ADMINCP ---------------------*/
}

?>