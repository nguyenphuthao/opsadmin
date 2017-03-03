<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    require(APPPATH.'/third_party/facebook-php-sdk-v5/autoload.php');
class Users extends MX_Controller
{
    private $module = 'users';
    private $table = 'users';
    private $fb = '';
    function __construct() {
        parent::__construct();
        $this->load->model($this->module . '_model', 'model');
        if ($this->uri->segment(1) == 'admincp') {
            if ($this->uri->segment(2) != 'login') {
                if (!$this->session->userdata('userInfo')) {
                    header('Location: ' . PATH_URL . 'admincp/login');
                    return false;
                }
            }
        }
    }
    /*------------------- BEGIN ADMIN -----------------------------*/
    function admincp_index(){
        $default_func = 'id';
        $default_sort = 'DESC';
        $data = array(
            'module' => $this->module,
            'module_name' => $this->session->userdata('Name_Module'),
            'default_func' => $default_func,
            'default_sort' => $default_sort
        );
        $data['title'] = "Admin Control Panel";
        $this->blade->render('BACKEND.index', $data); 
    }
    public function admincp_ajaxLoadContent() {
        $total = $this->model->getsearchContent(0,0,1);
        $record = $this->input->post('per_page');
        $start = $this->input->post('start');
        $data['pageLink'] = adminPagination($total, $start, $record);
        $data['result']= $this->model->getsearchContent($record, $start);
        $data['module'] = $this->module;
        $data['total'] = $total;
        $data['start'] = $start;
        $data['record'] = $record;
        $data['totalU'] = $this->model->getTotalUserCreateVideo();
        $data['totalS'] = $this->model->getTotalUserCreateVideo(1);
        $this->session->set_userdata('start', $start);
        $this->blade->render('BACKEND.loadContent', $data);
    }
    public function admincp_update($id = 0) {
        $score = 0;
        $result[0] = array(); $friend = array();
        if ($id != 0) {
            $user = $this->model->getDetailManagement($id);
            if($user){
                $data['totalVideo'] = $this->model->getTotalVideo($user[0]->id);
                $data['totalVideoSuccess'] = $this->model->getTotalVideo($user[0]->id, 1);
                $data['totalShare'] = $this->model->getTotalShare($user[0]->oauth_uid);
                $data['listVideo'] = $this->model->getListVideoUser($user[0]->id);
                $data['user'] = $user[0];                
                $data['title'] = "Users - Admin Control Panel";
                // pr($data,1);
                $this->blade->render('BACKEND.edit', $data);
            }else echo "not found";
        }else{
            echo "not found";
        }
    }
    function admincp_save(){
        $success =$this->model->saveManagement($this->input->post());        
        echo $success;
    }
    function admincp_delete(){
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if ($this->db->set('is_delete', 1)->update(PREFIX . $this->table)) {
                return true;
            }
        }
    }
    function admincp_listvideo(){
        $default_func = 'id';
        $default_sort = 'DESC';
        $data = array(
            'module' => $this->module,
            'module_name' => $this->session->userdata('Name_Module'),
            'default_func' => $default_func,
            'default_sort' => $default_sort
        );
        $data['title'] = "Admin Control Panel";
        $this->blade->render('BACKEND.listvideo', $data); 
    }
    function admincp_ajaxLoadVideo(){
        
        $total = $this->model->getTotalVideo(0, 1);
        $record = $this->input->post('per_page') ? $this->input->post('per_page') : 10;
        $start = $this->input->post('start');
        $data['totalVideo'] = $this->model->getTotalVideo(0, 0);
        $data['pageLink'] = adminPagination($total, $start, $record);
        $data['result']= $this->model->getListVideo($record, $start);
        $data['module'] = $this->module;
        $data['total'] = $total;
        $data['start'] = $start;
        $data['record'] = $record;
        $this->session->set_userdata('start', $start);
        $this->blade->render('BACKEND.ajaxLoadVideo', $data);

    }   
    
    /*------------------------ END ADMINCP ------------------------*/
    
}
