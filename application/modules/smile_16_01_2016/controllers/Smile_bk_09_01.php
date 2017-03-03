<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    require(APPPATH.'/third_party/facebook-php-sdk-v5/autoload.php');
class Smile extends MX_Controller
{
    private $module = 'smile';
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
    }
    
    // BƯỚC 1.
    function index()
    {
        if(!$this->session->userdata('userData')) redirect(PATH_URL.'thu-vien-tieng-cuoi');
        $num = $this->db->get(PREFIX.'connect_user');
        $data['title'] = "Cùng Con Bò Cười trao nhau Tiếng Cười Tết - nhận Quà hấp dẫn";
        $this->blade->render('FRONTEND.index', $data);
    }
    function ajaxGetFriendSearch(){
        $userData = $this->session->userdata('userData');
        if(!$userData) {echo -1; exit;}
        $user_id = $userData['user_id'];
        $name = $this->input->post('name');
        $record = 100;
        $start = 0;
        $friends = array();
        $friends = $this->model->getFriendsList($user_id, $record, $start, $name);
        // if($friends)
        //     foreach ($friends as $key => $value) {
        //         if(!$value->picture_url){
        //             $picture_url = $this->getImageFriend($value->oauth_uid); $id = $value->id;
        //             $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
        //             $value->picture_url = $picture_url;
        //             $friends[$key] = $value;
        //         }
        //     }
        $data['friends'] = $friends;
        $this->blade->render('FRONTEND.ajax_get_friends', $data);
    }
    // BƯỚC 2. TẠO VIDEO DEMO.
    function createVideoDemo(){
        $rs = array('path'=>0, 'idInsert'=> 0, 'status' => 0, 'msg'=>'Vui lòng đăng nhập');
        $userData = $this->session->userdata('userData');
        if(!$userData) {
            $rs['status'] = -1;
            echo json_encode($rs);
            exit;
        }
        $friends = $this->input->post('friends');
        if(!$friends){ $rs['msg'] = "Vui lòng chọn bạn đề cử"; echo json_encode($rs); exit;}
        $user_id = $userData['user_id'];
        $picture_url = $this->getImageFriend($userData['oauth_uid']);        
        $path = 'public/_data/images/'. $userData['oauth_uid'].'/avatar';
        $avatar_user = storeImageToUrl($picture_url, $path);
        $this->model->update(PREFIX.'users', array('avatar_image' => $avatar_user), "id = $user_id");    
        
        $dataFriend = $this->db->where_in('oauth_uid', $friends)->get(PREFIX.'friends_list');
        if($dataFriend->num_rows() == 0){
            $rs['msg'] = "Vui lòng chọn bạn đề cử"; echo json_encode($rs); exit;
        }

        $dataFriend = $dataFriend->result();
        $arrayAvatar = array(); array_push($arrayAvatar, $avatar_user);
        // $arrayA = array($userData['name'] => $avatar_user);
        $sql_like = $friends_list = '';
        foreach ($dataFriend as $key => $value) { 
            $friends_list .= $value->oauth_uid;
            if(!$value->avatar_image){
                $id = $value->id;
                if(!$value->picture_url){
                    $picture_url = $this->getImageFriend($value->oauth_uid);
                    $this->model->update(PREFIX.'friends_list', array('picture_url' => $picture_url), "id = $id");
                }else{
                    $picture_url = $value->picture_url;
                }
                $avatar = storeImageToUrl($picture_url, $path);
                $this->model->update(PREFIX.'friends_list', array('avatar_image' => $avatar), "id = $id");
            }else{
                $avatar = $value->avatar_image;
            }
            // $arrayA[$value->name] = $avatar;
            array_push($arrayAvatar, $avatar);
            $sql_like .= "friends_list like '%".$value->oauth_uid."%'";
            if(count($dataFriend) > 1 && $key < 1) $sql_like .= ' AND ';
        }
        $result = $this->model->fetch('friends_list, images_large, images_id, created_time', PREFIX.'images_tags', "user_id = $user_id AND $sql_like",'likes_count, comments_count',"DESC", 0, 9);
        /* -------------- KIỂM TRA SỐ LƯỢNG HÌNH NẾU NHỎ HƠN THÌ RETURN --------------------*/
        if(count($result) < 2) {
            $rs['msg'] = "Số lượng ảnh của bạn và người đề cử không đủ để tạo video"; echo json_encode($rs); exit;
        }

        $dataImage = array('user_id' => $user_id, 'friends_list' => substr($friends_list, 0, -1), 'status' => 1, 'created_at' => date("Y-m-d H:i:s"));
        $this->db->insert(PREFIX.'images_select', $dataImage);
        $idInsert = $this->db->insert_id();
        $path = "public/_data/images/".$userData['oauth_uid'].'/'.$idInsert.'/';

        // test thui
        $rs['path']     = $path;
        $rs['idInsert'] = $idInsert;
        $rs['status']   = 1;
        $rs['msg']      = "Tạo thành công";
        echo json_encode($rs);
        exit;

        $pathfinal = $path.'final'; 
        Newfolder($pathfinal);
        recurse_copy("public/frames", $path.'final');
        /* ------------------ FRAME 1 ------------------*/
        $pathtmp = $path.'tmp/';
        Newfolder($pathtmp);
        if(count($arrayAvatar) > 3) $arrayAvatar = array_slice($arrayAvatar, 0, 3);
        if(count($arrayAvatar) == 2){
            $fileTmp1 = $pathtmp.'1_1.png';
            $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 9, $fileTmp1);
            $fileTmp2 = $pathtmp.'1_2.png';
            $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], -9, $fileTmp2);
            // CHAY FOR DE TAO NHIU HINH
            for($i = 14; $i <= 34; $i++){
                $sourcefile = $path.'final/thumb00'.$i.'.png';
                $this->libWarterImage($sourcefile, $imagelib1, 45, 226);
                $this->libWarterImage($sourcefile, $imagelib2, 330,234);
            }
        }
        if(count($arrayAvatar) == 3){
            $fileTmp1 = $pathtmp.'1_1.png';
            $imagelib1 = $this->createImageLibFirst($arrayAvatar[0], 9, $fileTmp1);
            $fileTmp2 = $pathtmp.'1_2.png';
            $imagelib2 = $this->createImageLibFirst($arrayAvatar[1], -9, $fileTmp2);
            $fileTmp3 = $pathtmp.'1_3.png';
            $imagelib3 = $this->createImageLibFirst($arrayAvatar[2], -9, $fileTmp3);
            // CHAY FOR DE TAO NHIU HINH
            $sourcefile = $path.'final/thumb0002.png';
            $this->libWarterImage($sourcefile, $imagelib1, 28, 228);
            $this->libWarterImage($sourcefile, $imagelib2, 191, 230);
            $this->libWarterImage($sourcefile, $imagelib3, 352, 226);
        }
        
        /* ----------------------FRAME 2 --------------------*/
        $width = 288; $height = 268; $degrees = 0; $position = 42; $ofset = 127;
        $waterfile = $result[0]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 102; $i <= 128; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }

        /* ----------------------FRAME 3 --------------------*/
        $width = 270; $height = 258; $degrees = -1; $position = 194; $ofset = 130;
        $waterfile = $result[1]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 131; $i <= 160; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }

        /* ----------------------FRAME 4 --------------------*/
        $width = 370; $height = 348; $degrees = 1; $position = 79; $ofset = 101;
        $waterfile = $result[1]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 167; $i <= 193; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }
        
        /* ----------------------FRAME 5 --------------------*/
        $width = 305; $height = 287; $degrees = 1; $position = 146; $ofset = 96;
        $waterfile = $result[1]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 222; $i <= 270; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }

        /* ----------------------FRAME 6 --------------------*/
        $width = 380; $height = 365; $degrees = -4; $position = 120; $ofset = 107;
        $waterfile = $result[1]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 274; $i <= 337; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }
        /* ----------------------FRAME 7 --------------------*/
        $width = 393; $height = 377; $degrees = -10; $position = 40; $ofset = 93;
        $waterfile = $result[1]->images_large;
        $pathlib = $path.'tmp/2.png';
        $imagelib = $this->createImageLib($waterfile, $width, $height, $degrees, $pathlib);
        for($i = 342; $i <= 390; $i++){
            $sourcefile = $path.'final/thumb0'.$i.'.png';
            $this->libWarterImage($sourcefile, $imagelib, $position, $ofset);
        }
        $rs['path']     = $path;
        $rs['idInsert'] = $idInsert;
        $rs['status']   = 1;
        $rs['msg']      = "Tạo thành công";
        echo json_encode($rs);
        exit;
    }
    function getVideoFinal(){
        $path = $this->input->post('path'); $idupdate = $this->input->post('id');
        $pathvideo = $path.'video';
        Newfolder($pathvideo);
        recurse_copy('public/video', $pathvideo);
        $pathupdate = $pathvideo.'/dungtinemmanhme.mp4';
        $this->db->set('video_file', $pathupdate)->where('id', $idupdate)->update(PREFIX.'images_select');
        echo $idupdate;
    }
    function stepTwo(){
        $id = $this->uri->segment(2);
        $data['title'] = "Clip của bạn";
        $data['result'] = $this->model->get('*', PREFIX.'images_select',"id = $id");
        $this->blade->render('FRONTEND.step_two', $data);
        
    }
    function createImageNomal($width = 500, $height = 500, $sourcefile, $filename, $path){
        $this->load->library('image_moo');    
        $filesave = $path.$filename;
        Newfolder($sourcefile);
        $this->image_moo->load($sourcefile)->resize_crop($width, $height)->save($filesave, true);
        return $filesave;
    }
    function test(){
        // $userData = $this->session->userdata('userData'); 
        // $aa = array
        //     (
        //         "Nguyen Thao" => "public/_data/images/1253546244712734/avatar/11535918_888560854544610_4051201489961013421_n.jpg",
        //         "Su Ngoc Trang" => "public/_data/images/1253546244712734/avatar/13925319_832027566929120_1340359627918446175_n.jpg",
        //         "Nuong Le" => "public/_data/images/1253546244712734/avatar/13920847_663897797112683_7935216964213543212_n.jpg"
        //     );
        // $sourcefile = "public/images/frames/1/".count($aa).".jpg";
        // $newfile_1  = $this->createImageFirst($aa, $sourcefile);
        // $newfile_1  = 'public/_data/images/1253546244712734/frames/1483672548.png';
        // echo $this->watermarkText($newfile_1, $text='CON BÒ CƯỜI', 128, 166);

        $sourcefile = "public/images/frames/7/1.jpg";
        $width = 288; $height = 268; $degrees = -2; $position = 80; $ofset = 94;
        $waterfile = "https://scontent.xx.fbcdn.net/v/t1.0-9/13100731_785852988213245_4607221309673219369_n.jpg?oh=8f2cfbd30a3b2d51084acb39e0fe79f5&oe=58E2A064";
        $namefile = 'public/_data/images/1253546244712734/frames/8.png';
        echo $this->createImageLast($sourcefile, $waterfile, $namefile, $width, $height, $position, $ofset ,$degrees);
    }

    function createImageFirst($param, $path='', $sourcefile='public/images/frames/1/1.jpg'){
        $userData = $this->session->userdata('userData');
        $this->load->library('image_moo');

        foreach ($param as $key => $value) {
            $pic[] = $value;
            $text[] = $key;
        }
        $pathtmp = $path.'tmp';
        $pathfinal = $path.'final/';
        Newfolder($pathfinal);
        Newfolder($pathtmp);
        $newfile_1 = $sourcefile;//$pathfinal.'1.png';
        if(count($pic) == 2){
            $newfile = $pathtmp."/".time().'.png';
            // RESIZE IMAGE 1
            $this->image_moo->load($pic[0]);
            $this->image_moo->resize_crop(103,104)->save($newfile, true);
            // ROUND IMAGE 1
            $this->image_moo->roundPng($newfile, 20, $newfile);
            // ROTATE IMAGE 1
            ob_start(); 
            $degrees = 9;
            $im = imagecreatefrompng( $newfile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $newfile );
            imagedestroy( $im );
            imagedestroy( $rotated );
            // END MERGE IMAGE 1 TO SOURCE
            
           
            $this->image_moo->load($sourcefile)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark(45,226, true)
                                ->save($newfile_1, true);

            // BEGIN IMAGE 2
            // $newfile = $pathtmp."/".time().'.png';
            // RESIZE IMAGE 2
            $this->image_moo->load($pic[1]);
            $this->image_moo->resize_crop(103,104)->save($newfile, true);
            $this->image_moo->roundPng($newfile, 20, $newfile);
            ob_start(); 
            $degrees = -9;
            $im = imagecreatefrompng( $newfile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $newfile );
            imagedestroy( $im );
            imagedestroy( $rotated );
            
            $this->image_moo->load($newfile_1)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark(330,234, true)
                                ->save($newfile_1, true);
            return $newfile_1;

        }elseif(count($pic) == 3){
            $newfile = $pathtmp."/".time().'.png';
            // RESIZE IMAGE 1
            $this->image_moo->load($pic[0]);
            $this->image_moo->resize_crop(103,104)->save($newfile, true);
            // ROUND IMAGE 1
            $this->image_moo->roundPng($newfile, 20, $newfile);
            // ROTATE IMAGE 1
            ob_start(); 
            $degrees = 9;
            $im = imagecreatefrompng( $newfile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $newfile );
            imagedestroy( $im );
            imagedestroy( $rotated );
            $this->image_moo->load($sourcefile)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark(28,228, true)
                                ->save($newfile_1, true);

            // END MERGE IMAGE 1 TO SOURCE ------------- // BEGIN IMAGE 2
            
            $newfile = $pathtmp."/".time().'.png';
            // RESIZE IMAGE 2
            $this->image_moo->load($pic[1]);
            $this->image_moo->resize_crop(103,104)->save($newfile, true);
            $this->image_moo->roundPng($newfile, 20, $newfile);
            ob_start(); 
            $degrees = -9;
            $im = imagecreatefrompng( $newfile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $newfile );
            imagedestroy( $im );
            imagedestroy( $rotated );
            $this->image_moo->load($newfile_1)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark(191,230, true)
                                ->save($newfile_1, true);
            // END MERGE IMAGE 2 TO SOURCE ------------- // BEGIN IMAGE 3
            $this->image_moo->load($pic[2]);
            $this->image_moo->resize_crop(103,104)->save($newfile, true);
            $this->image_moo->roundPng($newfile, 20, $newfile);
            ob_start(); 
            $degrees = 9;
            $im = imagecreatefrompng( $newfile );
            $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
            $rotated = imagerotate( $im, $degrees, $transparency, 1);
            imagealphablending( $rotated, true );
            imagesavealpha( $rotated, true );
            imagepng($im);
            ob_end_clean();
            imagepng( $rotated, $newfile );
            imagedestroy( $im );
            imagedestroy( $rotated );
            $this->image_moo->load($newfile_1)
                                ->load_watermark($newfile)
                                ->set_watermark_transparency(1)->watermark(352,226, true)
                                ->save($newfile_1, true);
            return $newfile_1;
        }else return false;

    }
    function watermarkText($sourcefile='', $text='CON BÒ CƯỜI', $position=10, $ofset=10){
        $this->load->library('image_moo');
        $this->image_moo
                ->load($sourcefile)
                ->make_watermark_text($text, "public/fonts/AAA.TTF", 16, "#F00")
                ->watermark($position, $ofset)
                ->save($sourcefile, true);
        return $sourcefile;
    }
    function createImageLib($sourcefile, $width, $height, $degrees, $path ){
        $this->load->library('image_moo');
        // ------------- B1: Lưu ảnh về ----------------- //
        $infopath = pathinfo($path);
        $pathTmp = $infopath['dirname'];
        $file_path = $this->saveImage($sourcefile, $pathTmp);
        // ------------- B2: Resize Image ---------------//
        $this->image_moo->load($file_path);
        $this->image_moo->resize_crop($width,$height)->save($path, true);
         // --------------B3: XOAY HINH ------------------// 
        $this->rotate($path, $degrees);
        return $path;
    }
    function libWarterImage($sourcefile, $water, $left, $top){
        $this->load->library('image_moo');
        $this->image_moo->load($sourcefile)
                ->load_watermark($water)
                ->set_watermark_transparency(1)->watermark($left,$top, true)
                ->save($sourcefile, true);
    }
    function createImageLibFirst($sourcefile, $degrees = 0, $namefile){
        $this->load->library('image_moo');
        // RESIZE IMAGE 1
        $this->image_moo->load($sourcefile);
        $this->image_moo->resize_crop(103,104)->save($namefile, true);
        // ROUND IMAGE 1
        $this->image_moo->roundPng($namefile, 20, $namefile);
        // ROTATE IMAGE 1
        ob_start();
        $im = imagecreatefrompng( $namefile );
        $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
        $rotated = imagerotate( $im, $degrees, $transparency, 1);
        imagealphablending( $rotated, true );
        imagesavealpha( $rotated, true );
        imagepng($im);
        ob_end_clean();
        imagepng( $rotated, $namefile );
        imagedestroy( $im );
        imagedestroy( $rotated );
        return $namefile;
    }
    
    function saveImage($sourcefile='', $path=''){
        return storeImageToUrl($sourcefile, $path);   
    }
    function rotate($newfile, $degrees){
        ob_start(); 
        $im = imagecreatefrompng( $newfile );
        $transparency = imagecolorallocatealpha( $im,255, 255, 255, 127 );
        $rotated = imagerotate( $im, $degrees, $transparency, 1);
        imagealphablending( $rotated, true );
        imagesavealpha( $rotated, true );
        imagepng($im);
        ob_end_clean();
        imagepng( $rotated, $newfile );
        imagedestroy( $im );
        imagedestroy( $rotated );
        return true;
    }

    function stepOne(){
        $fb               = new Facebook\Facebook($this->config);
        $helper           = $fb->getRedirectLoginHelper();
        $permissions      = ['email', 'user_friends', 'user_photos'];
        $loginUrl         = $helper->getLoginUrl(PATH_URL.'smile/clientProcessLogin', $permissions);
        $total = $this->model->getTotalUser();
        $start=$this->input->get('p');
        $per_page = 64;
        $data['pageLink'] = pagination($total, $start, $per_page);
        $result= $this->model->listUser($per_page, $start);
        if($result)
        foreach ($result as $key => $value) {
            if(!$value->avatar_image){
                $idupdate = $value->id; $oauth_uid = $value->oauth_uid;
                $picture_url = $this->getImageFriend($oauth_uid);        
                $path = 'public/_data/images/'. $oauth_uid.'/avatar';
                $avatar_user = storeImageToUrl($picture_url, $path);
                $this->model->update(PREFIX.'users', array('avatar_image' => $avatar_user), "id = $idupdate");
                $value->avatar_image = $avatar_user;
                $result[$key] = $value;
            }
        }
        $data['total']    = $total;
        $data['result']   = $result;
        $data['loginUrl'] = htmlspecialchars($loginUrl);
        $data['title']    = "Thư viện tiếng cười";
        // pr($data,1);
        $this->blade->render('FRONTEND.step_one', $data);
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
            $userID = $this->model->checkUser($userData);        
            if(!empty($userID)){
                $userData['user_id'] = $userID;
                $this->session->set_userdata('userData',$userData);
                $userData['title'] = "Process Login";
                $this->blade->render('FRONTEND.client_process_login', $userData);
            } else {
                echo "Error Connect"; exit;
            }
        }else{
            $userData['title'] = "Process Login";
            $this->blade->render('FRONTEND.client_process_login', $userData);
        }
    }
    // LIBRARY FACEBOOK
    // LẤY ẢNH ĐẠI DIỆN CỦA BẠN
    function getImageFriend($oauth_uid){
        $accessToken = $_SESSION['fb_access_token'];
        if(!$accessToken) redirect(PATH_URL);
        $fbApp = new Facebook\FacebookApp(FB_CLIENT_ID, FB_CLIENT_SECRET);
        $fb = new Facebook\Facebook($this->config);
        $request = new Facebook\FacebookRequest($fbApp,
            $accessToken,
            'GET',
            "/$oauth_uid/",
            array(
                'fields' => 'picture.width(100)',
                'picture.height(100)' => 'null'
            )
        );
        try {
            $response = $fb->getClient()->sendRequest($request)->getdecodedBody();
            if(isset($response['picture'])) return $response['picture']['data']['url'];
            else return 0;
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return 0;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return 0;
        }    
    }
}