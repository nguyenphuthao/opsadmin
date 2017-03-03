<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends MY_Model {
	private $module = 'home';
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
    public function checkFriendsList($data = array()){
    	$user_id = $data['user_id']; $oauth_uid = $data['oauth_uid'];
    	if(!$user_id) return;
        $data['created_at']   = date("Y-m-d H:i:s");
        $insert = $this->db->insert(PREFIX . 'friends_list_tmp', $data);
    	return;
    }
    function checkImagesTags($data=array()){
    	$user_id = $data['user_id']; $images_id = $data['images_id'];
    	$result  = $this->model->get('id', PREFIX.'images_tags', "user_id = '$user_id' AND images_id = '$images_id'");
    	if(!$result){
    		$data['created_at'] = date("Y-m-d H:i:s");
    		$insert = $this->db->insert(PREFIX . 'images_tags', $data);
    	}else{
            $data['update_at'] = date("Y-m-d H:i:s");
            $this->db->update(PREFIX . 'images_tags', $data, array('id'=>$result->id));
        }
    	return;
    }
    function getUser($id = 0, $oauth_uid = 0){
        if(!$id) return $this->model->get('*', PREFIX.'users',"oauth_uid = $oauth_uid");
        else return $this->model->get('*', PREFIX.'users',"id = $id");
    }
    function getCountFriends($user_id){
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results(PREFIX.'friends_list');
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
    function getAllFriendsNoAvartar($user_id){
        return $this->model->fetch('oauth_uid', PREFIX.'friends_list', "user_id = $user_id AND picture_url =''");
    }

}

?>