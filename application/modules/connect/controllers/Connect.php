<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    require(APPPATH.'/third_party/facebook-php-sdk-v5/autoload.php');
class Connect extends MX_Controller
{
    private $module = 'connect';
    private $table = 'connect_user';
    private $fb = '';
    private $config = array(
        'app_id' => FB_CLIENT_ID,
        'app_secret' => FB_CLIENT_SECRET,
        'default_graph_version' => 'v2.8',
      //'default_access_token' => '{access-token}', // optional
    );
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
        $this->session->set_userdata('start', $start);
        $this->blade->render('BACKEND.loadContent', $data);
    }
    public function admincp_update($id = 0) {
        $score = 0;
        $result[0] = array(); $friend = array();
        if ($id != 0) {
            $result = $this->model->getDetailManagement($id);
            $friend = $this->model->getFriend($id);
        }
        $data = array(
            'result' => $result[0],
            'friend' => $friend[0],
            'module' => $this->module,
            'id'     => $id,
        );
        $data['title'] = "Add Question - Admin Control Panel";
        $this->blade->render('BACKEND.edit', $data);
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

    function admincp_export(){

        $this->db->select('*');
        $this->db->order_by('t1.conn_date', 'ASC')->order_by('conn_time', 'ASC')->order_by('waiting', 'ASC');        
        $this->db->select('t1.fullname, t1.email, t1.phone, t1.conn_location, t1.conn_date, t1.conn_time, t2.fullname as fr_name, t2.phone as fr_phone, t2.email as fr_email, t1.waiting, t1.created_at');
        $this->db->join(PREFIX.'connect_friends AS t2', 't2.user_id = t1.id', 'left');
        $this->db->where('is_delete',0);
        $data  = $this->db->get(PREFIX.'connect_user'.' AS t1')->result();
        
        $this->load->library('excel');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C1', 'DANH SÁCH KẾT NỐI');
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4', 'STT')
                    ->setCellValue('B4', "Họ tên")
                    ->setCellValue('C4', "Email")
                    ->setCellValue('D4', "Số điện thoại")
                    ->setCellValue('E4', "Họ tên người thân")
                    ->setCellValue('F4', "Email người thân")
                    ->setCellValue('G4', "Số điện thoại người thân")
                    ->setCellValue('H4', "Địa điểm")
                    ->setCellValue('I4', "Ngày")
                    ->setCellValue('J4', "Thời gian")
                    ->setCellValue('K4', "Trạng thái")
                    ->setCellValue('L4', "Ngày tạo");
                    
        $i=5;
        foreach ($data as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $i-4)
                ->setCellValue("B$i", $value->fullname)
                ->setCellValue("C$i", $value->email)
                ->setCellValue("D$i", "'".$value->phone)
                ->setCellValue("E$i", $value->fr_name)
                ->setCellValue("F$i", $value->fr_email)
                ->setCellValue("G$i", "'".$value->fr_phone)
                ->setCellValue("H$i", $value->conn_location == 1 ? "Hồ Chí Minh / Hà Nội" : "Hà Nội / Hồ Chí Minh")
                ->setCellValue("I$i", $value->conn_date)
                ->setCellValue("J$i", $value->conn_time)
                ->setCellValue("K$i", $value->waiting == 1 ? "Chờ" : "Thành công")
                ->setCellValue("L$i", $value->created_at);
            $i++;  
        }
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Danh sách kết nối');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="List_Connect.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    /*------------------------ END ADMINCP ------------------------*/
    function loginWithFacebook(){
        $userData = $this->session->userdata('userData');       
        if($userData) redirect(PATH_URL.'connect/clientProcessLogin');
        else{
            $fb               = new Facebook\Facebook($this->config);
            $helper           = $fb->getRedirectLoginHelper();
            $permissions      = ['email', 'user_friends', 'user_photos'];
            $loginUrl         = $helper->getLoginUrl(PATH_URL.'connect/clientProcessLogin', $permissions);
            $data['title']    = "Login with Facebook";
            $data['loginUrl'] = htmlspecialchars($loginUrl);
            $this->blade->render('FRONTEND.login_with_facebook', $data);
        }
    }
    function clientProcessLogin(){
        $userData = $this->session->userdata('userData'); /// Tam thời thôi.
        if(!$userData){
            $fb = new Facebook\Facebook($this->config);        
            $helper = $fb->getRedirectLoginHelper();
            try {
                $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            if (! isset($accessToken)) {
                if ($helper->getError()) {
                  header('HTTP/1.0 401 Unauthorized');
                  echo "Error: " . $helper->getError() . "\n";
                  echo "Error Code: " . $helper->getErrorCode() . "\n";
                  echo "Error Reason: " . $helper->getErrorReason() . "\n";
                  echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                  header('HTTP/1.0 400 Bad Request');
                  echo 'Bad request';
                }
                exit;
            }
            $_SESSION['fb_access_token'] = (string) $accessToken;
            $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
            $request = new Facebook\FacebookRequest($fbApp,
                $accessToken,
                'GET',
                '/me',
                array(
                    'fields' => 'id,name,first_name,last_name,email,gender,locale,picture'
                )
            );
            $response = $fb->getClient()->sendRequest($request)->getdecodedBody();
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid'] = $response['id'];
            $userData['name'] = $response['name'];
            $userData['first_name'] = $response['first_name'];
            $userData['last_name'] = $response['last_name'];
            $userData['email'] = $response['email'];
            $userData['gender'] = $response['gender'];
            $userData['locale'] = $response['locale'];
            $userData['profile_url'] = 'https://www.facebook.com/'.$response['id'];
            $userData['picture_url'] = $response['picture']['data']['url'];
            $this->load->model('home/home_model');
            $userID = $this->home_model->checkUser($userData);        
            if(!empty($userID)){
                $userData['user_id'] = $userID;
                $this->session->set_userdata('userData',$userData);
                $userData['title'] = "Process Login";
                pr($userData);
                $this->blade->render('FRONTEND.client_process_login', $userData);
            } else {
                echo "Error Connect"; exit;
            }
        }else{
            $userData['title'] = "Process Login";
            $this->blade->render('FRONTEND.client_process_login', $userData);
        }
    }
    function index(){
        $num = $this->db->where('is_delete', 0)->get(PREFIX.'connect_user');
        $data['num'] = $num->num_rows();
        $data['title'] = "Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn";
        $this->blade->render('FRONTEND.index', $data);
    }
    function connectJoin(){
        $data['title'] = "Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn";
        $sql = "SELECT count(id) as total, conn_date, conn_time FROM `ops_connect_user` WHERE DATE(`conn_date`)='2017-01-07' GROUP BY conn_date, conn_time";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $opTime = $query->result();
            foreach ($query->result() as $value) {
                $arrTmp[$value->conn_time] = $value->total;
            }
            $data['opTime'] = $arrTmp;
        }
        else $data['opTime'] = 0;
        $this->blade->render('FRONTEND.connect_join', $data);
    }
    function processConnectDate(){
        $connDate = $this->input->post('conn_date');
        $sql = "SELECT count(id) as total, conn_date, conn_time FROM `ops_connect_user` WHERE DATE(`conn_date`)='$connDate' GROUP BY conn_date, conn_time";
        $query = $this->db->query($sql);
        $data['opTime'] = 0;
        if($query->num_rows() > 0){
            $opTime = $query->result();
            foreach ($query->result() as $value) {
                $arrTmp[$value->conn_time] = $value->total;
            }
            $data['opTime'] = $arrTmp;
        }
        $this->blade->render('FRONTEND.process_connect_date', $data);
    }
    function processConnect(){
        echo $this->model->processConnect($this->input->post());
    }
    function coverPhoto(){
        $newfile = 'public/_data/images/libs/1.png';
        $sourcefile = 'public/images/frames/frame1.png';
        $filewater = 'public/images/frames/1.png';
        $this->load->library('image_moo');
        // tao hinh tron
        // $this->image_moo->load($filewater)->round(80, false, array(true, true, true, true))->save($newfile, true);
        // 
        // $this->image_moo->load($filewater)->rotate(100)->round(5)->save($newfile, true);

        // TAO WATERMARK NGHIEN
        $fileRotate = $this->coverRotate($filewater, 20);
        $this->image_moo ->load($sourcefile)
                            ->load_watermark($fileRotate) ->set_jpeg_quality(100)
                            ->set_watermark_transparency(1)->watermark(107, 54, true)
                            ->save($newfile, true);

        // $this->image_moo->load($sourcefile)-9>load_watermark($filewater)->set_watermark_transparency(1)->watermark(107, 54, true)->save($newfile, true);

        echo "Done";        
    }
    function coverRotate($sourcefile, $rotate){
        $this->load->library('image_moo');
        $fileTmp = 'public/_data/images/libs/tmp'.time().'.png';
        $this->image_moo ->load($sourcefile)
                        ->rotate($rotate)
                        ->save($fileTmp, true);
        return $fileTmp;
    }
    function cover1(){
        $this->load->library('image_moo');
        $filewater = 'public/images/frames/1.png';
        $fileTmp = 'public/_data/images/libs/tmp'.time().'.png';
        echo $this->image_moo->imageCreateCorners($filewater, 20, $fileTmp);
        // echo "Done";     
    }
}