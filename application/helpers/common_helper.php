<?php
if ( ! function_exists('asset')){
    function asset($asset_path) {
        return PUBLICPATH . $asset_path;
    }
}
if (!function_exists('product_price')) {
    function product_price($priceFloat) {
        $symbol = ' VNĐ';
        $symbol_thousand = '.';
        $decimal_place = 0;
        $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        return $price . $symbol;
    }
}
if (!function_exists('sub_date')) {
    function sub_date($date1, $date2) {
        $first_date = strtotime($date1);
        $second_date = strtotime($date2);
        $datediff = abs($first_date - $second_date);
        return floor($datediff / (60 * 60 * 24));
    }
}
if (!function_exists('tim_so_ngay')) {
    function tim_so_ngay($date1, $date2) {
        $songay = 0;
        if ($date1 < $date2) {
            $dates_range[] = $date1;
            $date1 = strtotime($date1);
            $date2 = strtotime($date2);
            $songay = 0;
            while ($date1 != $date2) {
                $date1 = mktime(0, 0, 0, date("m", $date1), date("d", $date1) + 1, date("Y", $date1));
                $dates_range[] = date('Y-m-d', $date1);
                $songay++;
            }
        }
        return $songay;
    }
}
if (!function_exists('subString')) {
    function subString($str, $len, $charset = "UTF-8") {
        $str = html_entity_decode($str, ENT_QUOTES, $charset);
        if (mb_strlen($str, $charset) > $len) {
            $arr = explode(' ', $str);
            $str = mb_substr($str, 0, $len, $charset);
            $arrRes = explode(' ', $str);
            $last = $arr[count($arrRes) - 1];
            unset($arr);
            if (strcasecmp($arrRes[count($arrRes) - 1], $last))
            unset($arrRes[count($arrRes) - 1]);
            return implode(' ', $arrRes) . "...";
        }
        return $str;
    }
}
if (!function_exists('getYoutubeID')) {
    function getYoutubeID($youtubeData = '') {
        preg_match('%(https://www\.youtube\.com/watch\?v=(.*)|https://www\.youtube\.com/embed/(.*)|https://www\.youtube\.com/v/(.*))%im', $youtubeData, $match);
        if ($match) {
            return trim($match[count($match) - 1]);
        }
        return trim($youtubeData);
    }
}
if (!function_exists('getYoutubeIDNew')) {
    function getYoutubeIDNew($url) {
        if (stristr($url, 'youtu.be/')) {
            preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID);
            return $final_ID[4];
        } else {
            @preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $IDD);
            return $IDD[5];
        }
    }
}
if (!function_exists('SEO')) {
    function SEO($name = '') {
        $name = v2e(trim($name));
        $name = preg_replace("/[^a-z,A-Z,0-9,_,-]/", "-", $name);
        $name = str_replace("---", "-", $name);
        $name = str_replace("--", "-", $name);
        return strtolower($name);
    }
}
if (!function_exists('adminPagination')) {
    function adminPagination($totalRows, $pageNum = 1, $pageSize, $limit = 3) {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
        return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
        return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
        $currentPage = 1;
        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;
        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
        $to = $totalPages;
        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '<span>';
        //Link URL
        $linkUrl = current_url();
        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get.= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            // $prev = "<li class='pageprev'><a href='$linkUrl$prevPage' ><span>&lt;</span></a>";
            $prev = '<a class="paginate_button previous disabled">
                <i class="fa fa-angle-left"></i>
            </a>';
        }
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            // $next = "<li class='pagenext'><a href='$linkUrl$nextPage' ><span>&gt;</span></a>";
            $next ='<a class="paginate_button next">
                <i class="fa fa-angle-right"></i>
            </a>';
        }
        if ($currentPage < $totalPages - 4) {
            $lastPage = $totalPages;
        }
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
            $link.= "<a href='javascript:;' onclick='searchContentType($i)' class='paginate_button current'>$i</a>";
            else
            $link.= "<a href='javascript:;' onclick='searchContentType($i)' class='paginate_button'>$i</a>";
        }
        $pagination = '<div class="dataTables_paginate paging_simple_numbers">' . $first . $prev . $link . $next . $last . '</div>';
        return $pagination;
    }
}
if (!function_exists('pagination')) {
    function pagination($totalRows, $pageNum = 1, $pageSize, $limit = 3) {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
        return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
        return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
        $currentPage = 1;
        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;
        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
        $to = $totalPages;
        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '';
        //Link URL
        $linkUrl = PATH_URL.uri_string();
        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get.= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            // $prev = "<li class='pageprev'><a href='$linkUrl$prevPage' ><span>&lt;</span></a>";
            $prev = "<li class='pageprev'><a href='$linkUrl$prevPage' ><span>Trước</span></a>";
        }
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            // $next = "<li class='pagenext'><a href='$linkUrl$nextPage' ><span>&gt;</span></a>";
            $next = "<li class='pagenext'><a href='$linkUrl$nextPage' ><span>Sau</span></a>";
        }
        if ($currentPage < $totalPages - 4) {
            $lastPage = $totalPages;
        }
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
            $link.= "<li class='active'><a href='javascript:;'>$i</a></li>";
            else
            $link.= "<li><a href='$linkUrl$i'>$i</a></li>";
        }
        $pagination = '<div class="pager"><ul class="pagination">' . $first . $prev . $link . $next . $last . '</ul></div>';
        return $pagination;
    }
    //pagelistLimited
}
if (!function_exists('pagination_ajax')) {
    function pagination_ajax($totalRows, $pageNum = 1, $pageSize, $limit = 3) {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
        return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
        return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
        $currentPage = 1;
        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;
        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
        $to = $totalPages;
        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '';
        //Link URL
        $linkUrl = current_url();
        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get.= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';
        $link = '';
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $prev = "<li><a href='javascript:void(0)' class='prev' onclick='histoty_transfer($prevPage)'><</a></li>";
        }
        /*         * *Next** */
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $next = "<li><a href='javascript:void(0)' onclick='histoty_transfer($nextPage)' class='next'>></a></li>";
        }
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
            $link.= "<li><strong>$i</strong></li>";
            else
            $link.= "<li><a href='javascript:void(0)' onclick='histoty_transfer($i)'>$i</a></li>";
        }
        $pagination = '<div class="pagination"><ul>' . $first . $prev . $link . $next . $last . '</ul></div>';
        return $pagination;
    }
}
if (!function_exists('random_string')) {
    function random_string($length = 4) {
        $sWord = '';
        $sChars = 'abcdefghjklmnprtwyzABCDEFGHJKLMNPRTWXYZ1234567890';
        for ($i = 1; $i <= $length; $i++) {
            $nNumber = rand(1, strlen($sChars));
            $sWord.= substr($sChars, $nNumber - 1, 1);
        }
        return $sWord;
    }
}
if (!function_exists('create_slug')) {
    function create_slug($str) {
        if (!$str)
        return false;
        $str = trim($str);
        $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'd' => 'đ',
        'D' => 'Đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ' ' => ' - |- | -',
        '(*)' => '.|-|“|”|?|!|' . "'|" . '"');
        foreach ($unicode as $khongdau => $codau) {
            $arr = explode("|", $codau);
            $str = str_replace($arr, $khongdau, $str);
        }
        $str = str_replace('(*)', '', $str);
        $str = preg_replace('/\s+/', '-', $str);
        $str = strtolower($str);
        return $str;
    }
}
function _token() {
    $token = md5(uniqid(rand(), TRUE)) . md5(uniqid(rand()) . time());
    return $token;
}
if (!function_exists('createFolder')) {
    function createFolder($folder){
        $arr_folder = explode('/', $folder);
        $fol        = '';
        foreach ($arr_folder as $row) {
            if (!empty($row)) {
                $fol .= $row . '/';
                if (!file_exists($fol)) {
                    @mkdir($fol, 0777);
                } else {
                    if ($row != 'static') {
                        $mod = substr(sprintf('%o', fileperms($fol)), -4);
                        if ($mod != 0777) {
                            @chmod($fol, 0777);
                        }
                    }
                }
            }
        }
    }
}
function v2e($value) {
    //---------------------------------SPECIAL
    $value = str_replace("&quot;", "", $value);
    $value = str_replace(".", "", $value);
    $value = str_replace("=", "", $value);
    $value = str_replace("+", "", $value);
    $value = str_replace("!", "", $value);
    $value = str_replace("@", "", $value);
    $value = str_replace("#", "", $value);
    $value = str_replace("$", "", $value);
    $value = str_replace("%", "", $value);
    $value = str_replace("^", "", $value);
    $value = str_replace("&", "", $value);
    $value = str_replace("*", "", $value);
    $value = str_replace("(", "", $value);
    $value = str_replace(")", "", $value);
    $value = str_replace("`", "", $value);
    $value = str_replace("~", "", $value);
    $value = str_replace(",", "", $value);
    $value = str_replace("/", "", $value);
    $value = str_replace("\\", "", $value);
    $value = str_replace('"', "", $value);
    $value = str_replace("'", "", $value);
    $value = str_replace(":", "", $value);
    $value = str_replace(";", "", $value);
    $value = str_replace("|", "", $value);
    $value = str_replace("[", "", $value);
    $value = str_replace("]", "", $value);
    $value = str_replace("{", "", $value);
    $value = str_replace("}", "", $value);
    $value = str_replace("(", "", $value);
    $value = str_replace(")", "", $value);
    $value = str_replace("?", "", $value);
    //---------------------------------a^
    $value = str_replace("â", "a", $value);
    $value = str_replace("ấ", "a", $value);
    $value = str_replace("ầ", "a", $value);
    $value = str_replace("ẩ", "a", $value);
    $value = str_replace("ẫ", "a", $value);
    $value = str_replace("ậ", "a", $value);
    //---------------------------------A^
    $value = str_replace("Â", "a", $value);
    $value = str_replace("Ấ", "a", $value);
    $value = str_replace("Ầ", "a", $value);
    $value = str_replace("Ẩ", "a", $value);
    $value = str_replace("Ẫ", "a", $value);
    $value = str_replace("Ậ", "a", $value);
    //---------------------------------a
    $value = str_replace("á", "a", $value);
    $value = str_replace("à", "a", $value);
    $value = str_replace("ả", "a", $value);
    $value = str_replace("ã", "a", $value);
    $value = str_replace("ạ", "a", $value);
    //---------------------------------A
    $value = str_replace("Á", "a", $value);
    $value = str_replace("À", "a", $value);
    $value = str_replace("Ả", "a", $value);
    $value = str_replace("Ã", "a", $value);
    $value = str_replace("Ạ", "a", $value);
    //---------------------------------a(
    $value = str_replace("ă", "a", $value);
    $value = str_replace("ắ", "a", $value);
    $value = str_replace("ằ", "a", $value);
    $value = str_replace("ẳ", "a", $value);
    $value = str_replace("ẵ", "a", $value);
    $value = str_replace("ặ", "a", $value);
    //---------------------------------A(
    $value = str_replace("Ă", "a", $value);
    $value = str_replace("Ắ", "a", $value);
    $value = str_replace("Ằ", "a", $value);
    $value = str_replace("Ẳ", "a", $value);
    $value = str_replace("Ẵ", "a", $value);
    $value = str_replace("Ặ", "a", $value);
    $value = str_replace("Ă", "a", $value);
    //---------------------------------e^
    $value = str_replace("ê", "e", $value);
    $value = str_replace("ế", "e", $value);
    $value = str_replace("ề", "e", $value);
    $value = str_replace("ể", "e", $value);
    $value = str_replace("ễ", "e", $value);
    $value = str_replace("ệ", "e", $value);
    //---------------------------------E^
    $value = str_replace("Ê", "e", $value);
    $value = str_replace("Ế", "e", $value);
    $value = str_replace("Ề", "e", $value);
    $value = str_replace("Ể", "e", $value);
    $value = str_replace("Ễ", "e", $value);
    $value = str_replace("Ệ", "e", $value);
    //---------------------------------e
    $value = str_replace("é", "e", $value);
    $value = str_replace("è", "e", $value);
    $value = str_replace("ẻ", "e", $value);
    $value = str_replace("ẽ", "e", $value);
    $value = str_replace("ẹ", "e", $value);
    //---------------------------------E
    $value = str_replace("É", "e", $value);
    $value = str_replace("È", "e", $value);
    $value = str_replace("Ẻ", "e", $value);
    $value = str_replace("Ẽ", "e", $value);
    $value = str_replace("Ẹ", "e", $value);
    //---------------------------------i
    $value = str_replace("í", "i", $value);
    $value = str_replace("ì", "i", $value);
    $value = str_replace("ỉ", "i", $value);
    $value = str_replace("ĩ", "i", $value);
    $value = str_replace("ị", "i", $value);
    //---------------------------------I
    $value = str_replace("Í", "i", $value);
    $value = str_replace("Í", "i", $value);
    $value = str_replace("Ỉ", "i", $value);
    $value = str_replace("Ĩ", "i", $value);
    $value = str_replace("Ị", "i", $value);
    //---------------------------------o^
    $value = str_replace("ô", "o", $value);
    $value = str_replace("ố", "o", $value);
    $value = str_replace("ồ", "o", $value);
    $value = str_replace("ổ", "o", $value);
    $value = str_replace("ỗ", "o", $value);
    $value = str_replace("ộ", "o", $value);
    //---------------------------------O^
    $value = str_replace("Ô", "o", $value);
    $value = str_replace("Ố", "o", $value);
    $value = str_replace("Ồ", "o", $value);
    $value = str_replace("Ổ", "o", $value);
    $value = str_replace("Ỗ", "o", $value);
    $value = str_replace("Ộ", "o", $value);
    //---------------------------------o*
    $value = str_replace("ơ", "o", $value);
    $value = str_replace("ớ", "o", $value);
    $value = str_replace("ờ", "o", $value);
    $value = str_replace("ở", "o", $value);
    $value = str_replace("ỡ", "o", $value);
    $value = str_replace("ợ", "o", $value);
    //---------------------------------O*
    $value = str_replace("Ơ", "o", $value);
    $value = str_replace("Ớ", "o", $value);
    $value = str_replace("Ờ", "o", $value);
    $value = str_replace("Ở", "o", $value);
    $value = str_replace("Ỡ", "o", $value);
    $value = str_replace("Ợ", "o", $value);
    //---------------------------------u*
    $value = str_replace("ư", "u", $value);
    $value = str_replace("ứ", "u", $value);
    $value = str_replace("ừ", "u", $value);
    $value = str_replace("ử", "u", $value);
    $value = str_replace("ữ", "u", $value);
    $value = str_replace("ự", "u", $value);
    //---------------------------------U*
    $value = str_replace("Ư", "u", $value);
    $value = str_replace("Ứ", "u", $value);
    $value = str_replace("Ừ", "u", $value);
    $value = str_replace("Ử", "u", $value);
    $value = str_replace("Ữ", "u", $value);
    $value = str_replace("Ự", "u", $value);
    //---------------------------------y
    $value = str_replace("ý", "y", $value);
    $value = str_replace("ỳ", "y", $value);
    $value = str_replace("ỷ", "y", $value);
    $value = str_replace("ỹ", "y", $value);
    $value = str_replace("ỵ", "y", $value);
    //---------------------------------Y
    $value = str_replace("Ý", "y", $value);
    $value = str_replace("Ỳ", "y", $value);
    $value = str_replace("Ỷ", "y", $value);
    $value = str_replace("Ỹ", "y", $value);
    $value = str_replace("Ỵ", "y", $value);
    //---------------------------------DD
    $value = str_replace("Đ", "d", $value);
    $value = str_replace("đ", "d", $value);
    //---------------------------------o
    $value = str_replace("ó", "o", $value);
    $value = str_replace("ò", "o", $value);
    $value = str_replace("ỏ", "o", $value);
    $value = str_replace("õ", "o", $value);
    $value = str_replace("ọ", "o", $value);
    //---------------------------------O
    $value = str_replace("Ó", "o", $value);
    $value = str_replace("Ò", "o", $value);
    $value = str_replace("Ỏ", "o", $value);
    $value = str_replace("Õ", "o", $value);
    $value = str_replace("Ọ", "o", $value);
    //---------------------------------u
    $value = str_replace("ú", "u", $value);
    $value = str_replace("ù", "u", $value);
    $value = str_replace("ủ", "u", $value);
    $value = str_replace("ũ", "u", $value);
    $value = str_replace("ụ", "u", $value);
    //---------------------------------U
    $value = str_replace("Ú", "u", $value);
    $value = str_replace("Ù", "u", $value);
    $value = str_replace("Ủ", "u", $value);
    $value = str_replace("Ũ", "u", $value);
    $value = str_replace("Ụ", "u", $value);
    //---------------------------------
    return $value;
}
function cleanUrl($str) {
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
    $clean = strtolower(trim($clean, '-'));
    return $clean;
}
if (!function_exists('Newfolder')) {
    function Newfolder($folder) {
        $arr_folder = explode('/', $folder);
        $fol = '';
        foreach ($arr_folder as $row) {
            if (!empty($row)) {
                $fol.= $row . '/';
                if (!file_exists($fol)) {
                    @mkdir($fol, 0777);
                } else {
                    if ($row != 'static') {
                        $mod = substr(sprintf('%o', fileperms($fol)), -4);
                        if ($mod != 0777) {
                            @chmod($fol, 0777);
                        }
                    }
                }
            }
        }
    }
}
if (!function_exists('get_folder')) {
    function get_folder() {
        $year = date('Y');
        $month = date('m');
        return $year . '/' . $month . '/';
    }
}
if (!function_exists('pr')) {
    function pr($data, $type = 0) {
        if (1) {
            print '<pre>';
            print_r($data);
            print '</pre>';
            if ($type != 0) {
                exit();
            }
        }
    }
}
if (!function_exists('CutText')) {
    function CutText($text, $n = 80) {
        // string is shorter than n, return as is
        if (strlen($text) <= $n) {
            return $text;
        }
        $text = substr($text, 0, $n);
        if ($text[$n - 1] == ' ') {
            return trim($text) . "...";
        }
        $x = explode(" ", $text);
        $sz = sizeof($x);
        if ($sz <= 1) {
            return $text . "...";
        }
        $x[$sz - 1] = '';
        return trim(implode(" ", $x)) . "...";
    }
}
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
         //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
if (!function_exists('curl_copy')) {
    function curl_copy($source = '', $file = '') {
        $fh = fopen($file, 'w');
        if ($fh) {
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $source);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FILE, $fh);
            // grab URL and pass it to the browser
            curl_exec($ch);
            // close cURL resource, and free up system resources
            curl_close($ch);
            fclose($fh);
            return true;
        }
    }
}
if (!function_exists('SEO_TITLE')) {
    function SEO_TITLE($content = '') {
        $content = trim($content);
        $content = str_replace("'", '', $content);
        $content = str_replace("’", '', $content);
        $content = str_replace('"', '', $content);
        $content = str_replace('“', '', $content);
        $content = str_replace('”', '', $content);
        return $content . ' | Maxgame News';
    }
}
function resizeImage($src, $w = 0, $h = 0) {
    if ($w != 0 && $h != 0) {
        return PATH_URL . 'imgresize.php?width=' . $w . '&height=' . $h . '&cropratio=1:1&image=' . $src;
    } elseif ($w != 0) {
        return PATH_URL . 'imgresize.php?width=' . $w . '&image=' . $src;
    } elseif ($h != 0) {
        return PATH_URL . 'imgresize.php?height=' . $h . '&image=' . $src;
    }
}
if (!function_exists('img')) {
    function img($image_path, $width = 0, $height = 0) {
        //Get the Codeigniter object by reference
        $CI = & get_instance();
        //Alternative image if file was not found
        if (!file_exists($image_path) || !is_file($image_path)) {
            $image_path = 'static/images/img/no-image.jpg';
        }
        //The new generated filename we want
        $fileinfo = pathinfo($image_path);
        //MAKE A FOLDER
        if (!empty($width) && !empty($height)) {
            $uploadDir_Thumb = 'static/cache/' . $width . 'x' . $height . '/';
        } else {
            $uploadDir_Thumb = 'static/cache/thumb/';
        }
        Newfolder($uploadDir_Thumb);
        $new_image_path = $uploadDir_Thumb . $fileinfo['filename'] . '.' . $fileinfo['extension'];
        //The first time the image is requested
        //Or the original image is newer than our cache image
        if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            $CI->load->library('image_lib');
            //The original sizes
            $original_size = getimagesize($image_path);
            $original_width = $original_size[0];
            $original_height = $original_size[1];
            $ratio = $original_width / $original_height;
            //The requested sizes
            $requested_width = $width;
            $requested_height = $height;
            //if smaller then return
            if (($requested_width > $original_width) && $requested_height == 0)
            return PATH_URL . $image_path;
            elseif (($requested_height > $original_height) && $requested_width == 0)
            return PATH_URL . $image_path;
            //Initialising
            $new_width = 0;
            $new_height = 0;
            //Calculations
            if ($requested_width > $requested_height) {
                $new_width = $requested_width;
                $new_height = $new_width / $ratio;
                if ($requested_height == 0)
                $requested_height = $new_height;
                if ($new_height < $requested_height) {
                    $new_height = $requested_height;
                    $new_width = $new_height * $ratio;
                }
            } else {
                $new_height = $requested_height;
                $new_width = $new_height * $ratio;
                if ($requested_width == 0)
                $requested_width = $new_width;
                if ($new_width < $requested_width) {
                    $new_width = $requested_width;
                    $new_height = $new_width / $ratio;
                }
            }
            $new_width = ceil($new_width);
            $new_height = ceil($new_height);
            //Resizing
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = $new_image_path;
            $config['maintain_ratio'] = FALSE;
            $config['height'] = $new_height;
            $config['width'] = $new_width;
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
            //Crop if both width and height are not zero
            if (($width != 0) && ($height != 0)) {
                $x_axis = floor(($new_width - $width) / 2);
                $y_axis = floor(($new_height - $height) / 2);
                //Cropping
                $config = array();
                $config['source_image'] = $new_image_path;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = $new_image_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['x_axis'] = $x_axis;
                $config['y_axis'] = $y_axis;
                $config['quality'] = '100%';
                $CI->image_lib->initialize($config);
                $CI->image_lib->crop();
                $CI->image_lib->clear();
            }
        }
        return PATH_URL . $new_image_path;
    }
}
function check_dir_upload($targetDir) {
    if (!is_dir($targetDir . date('Y'))) {
        @mkdir($targetDir . date('Y'), 0777);
    }
    if (!is_dir($targetDir . date('Y') . '/' . date('m'))) {
        @mkdir($targetDir . date('Y') . '/' . date('m'), 0777);
    }
    if (!is_dir($targetDir . date('Y') . '/' . date('m') . '/' . date('d'))) {
        @mkdir($targetDir . date('Y') . '/' . date('m') . '/' . date('d'), 0777);
    }
}
if (!function_exists('datepost')) {
    function datepost($datepost = '') {
        return date('H:i | d/m/Y', strtotime($datepost));
    }
}
if (!function_exists('datepostMD')) {
    function datepostMD($datepost = '') {
        return date('d/m/Y', strtotime($datepost));
    }
}
if (!function_exists('datepostvn')) {
    function datepostvn($date = '') {
        $us = array(
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat',
        'Sun');
        $vn = array(
        'Thứ hai',
        'Thứ ba',
        'Thứ tư',
        'Thứ năm',
        'Thứ sáu',
        'Thứ bảy',
        'Chủ nhật');
        $D = date('D', strtotime($date));
        $key = array_search($D, $us);
        return $vn[$key] . ',' . date('d/m/Y - h:i', strtotime($date));
    }
}

if (!function_exists('EDITOR')) {
    function EDITOR($str, $width = '') {
        $str = str_replace('../../../static/uploads/editor/', PATH_URL . 'static/uploads/editor/', $str);
        $str = preg_replace('/http:\/\/www.youtube.com\/watch\?v=([A-Za-z0-9\-\_]+)&amp;feature=([A-Za-z0-9]+)/is', '<iframe width="500" height="289" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $str);
        $str = preg_replace('/http:\/\/www.youtube.com\/watch\?v=([A-Za-z0-9\-\_]+)/is', '<iframe width="500" height="289" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $str);
        if ($width != '') {
            preg_match_all('/src="(.*)"/msU', $str, $match);
            if (is_array($match[1])) {
                foreach ($match[1] as $item) {
                    if ($item != '') {
                        if (strstr(PATH_URL, $item)) {
                            $item_tmp = str_replace(PATH_URL, '', $item);
                            $str = str_replace($item, img($item_tmp, $width), $str);
                        }
                    }
                }
            }
        }
        return $str;
    }
}
function url_login_openid($type = 'google'){
    if ($type == 'google') {
        $params = array(
            'response_type' => 'token',
            'client_id' => G_CLIENT_ID,
            'redirect_uri' => REDIRECT_URL . '/openid',
            'state' => 'profile',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.google.com/m8/feeds'
        );
        $url    = 'https://accounts.google.com/o/oauth2/auth?';
        foreach ($params as $key => $param) {
            $url .= $key . '=' . urlencode($param) . '&';
        }
    }
    if ($type == 'yahoo') {
        $params = array(
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.mode' => 'checkid_setup',
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.realm' => PATH_URL,
            'openid.return_to' => REDIRECT_URL . '/login_openid/yahoo',
            'openid.ns.oauth' => 'http://specs.openid.net/extensions/oauth/1.0',
            'openid.oauth.consumer' => YH_CLIENT_KEY,
            'openid.ns.ax' => 'http://openid.net/srv/ax/1.0',
            'openid.ax.mode' => 'fetch_request',
            'openid.ax.required' => 'email,fullname,nickname,gender,language,timezone,image',
            'openid.ax.type.email' => 'http://axschema.org/contact/email',
            'openid.ax.type.fullname' => 'http://axschema.org/namePerson',
            'openid.ax.type.nickname' => 'http://axschema.org/namePerson/friendly',
            'openid.ax.type.gender' => 'http://axschema.org/person/gender',
            'openid.ax.type.image' => 'http://axschema.org/media/image/default'
        );
        $url    = 'https://open.login.yahooapis.com/openid/op/auth?';
        foreach ($params as $key => $param) {
            $url .= $key . '=' . urlencode($param) . '&';
        }
    }
    if ($type == 'fb') {
        $rUrl = REDIRECT_URL . '/openid';
        $url  = "http://www.facebook.com/dialog/oauth?" . "client_id=" . FB_CLIENT_ID . "&redirect_uri=" . urlencode($rUrl) . "&response_type=token&display=popup" . "&scope=email";
    }
    return $url;
}
function get_attr_profile_openid($type)
{
    $info = '';
    if ($type == 'google') {
        include_once('application/libraries/eac_curl.class.php');
        $options                        = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF']        = 1;
        $options['CURLOPT_NOPROGRESS']  = 1;
        $http                           = new cURL($options);
        $http->setOptions($options);
        $src     = $http->get("https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $_GET['access_token']);
        $contact = $http->get("https://www.google.com/m8/feeds/contacts/default/full?max-results=5000&oauth_token=" . $_GET['access_token']);
        $xml     = new SimpleXMLElement($contact);
        $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
        $result       = $xml->xpath('//gd:email');
        $profile      = json_decode($src);
        $info['type'] = 'google';
        if (isset($profile->id)) {
            $info['id'] = $profile->id;
        } else {
            $info['id'] = '';
        }
        if ($profile->email) {
            $info['email'] = $profile->email;
        } else {
            $info['email'] = '';
        }
        if ($profile->verified_email) {
            $info['verified_email'] = $profile->verified_email;
        } else {
            $info['verified_email'] = '';
        }
        if ($profile->name) {
            $info['full_name'] = $profile->name;
        } else {
            $info['full_name'] = '';
        }
        if ($profile->given_name) {
            $info['f_name'] = $profile->given_name;
        } else {
            $info['f_name'] = '';
        }
        if ($profile->family_name) {
            $info['l_name'] = $profile->family_name;
        } else {
            $info['l_name'] = '';
        }
        if (isset($profile->picture)) {
            $info['avatar'] = $profile->picture;
        } else {
            $info['avatar'] = '';
        }
        if (isset($profile->locale)) {
            $info['locale'] = $profile->locale;
        } else {
            $info['locale'] = '';
        }
        if (isset($profile->timezone)) {
            $info['timezone'] = $profile->timezone;
        } else {
            $info['timezone'] = '';
        }
        if (isset($profile->gender)) {
            $info['gender'] = $profile->gender;
        } else {
            $info['gender'] = '';
        }
        foreach ($result as $title) {
            $info['contact'][] = $title->attributes()->address;
        }
    } elseif ($type == 'yahoo') {
        $info['type'] = 'yahoo';
        if (isset($_REQUEST['openid_ax_value_email'])) {
            $info['email'] = $_REQUEST['openid_ax_value_email'];
        } else {
            $info['email'] = '';
        }
        if (isset($_REQUEST['openid_ax_value_fullname'])) {
            $info['full_name'] = $_REQUEST['openid_ax_value_fullname'];
        } else {
            $info['full_name'] = '';
        }
        if (isset($_REQUEST['openid_ax_value_nickname'])) {
            $info['nick_name'] = $_REQUEST['openid_ax_value_nickname'];
        } else {
            $info['nick_name'] = '';
        }
        if (isset($_REQUEST['openid_ax_value_gender'])) {
            $info['gender'] = $_REQUEST['openid_ax_value_gender'];
        } else {
            $info['gender'] = '';
        }
        if (isset($_REQUEST['openid_ax_value_image'])) {
            $info['avatar'] = $_REQUEST['openid_ax_value_image'];
        } else {
            $info['avatar'] = '';
        }
    } else {
        require_once APPPATH . 'libraries/eac_curl.class.php';
        if(!isset($_GET['access_token'])){
        }
        $options                        = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF']        = 1;
        $options['CURLOPT_NOPROGRESS']  = 1;
        $http                           = new cURL($options);
        $http->setOptions($options);
        $src     = $http->get("https://graph.facebook.com/me?access_token=" . $_GET['access_token']);
        $profile = json_decode($src);
        if (isset($profile->id)) {
            $info['id'] = $profile->id;
        }
        if (isset($profile->email)) {
            $info['email'] = $profile->email;
        }else{
            if($profile->name){
                $info['email'] = $profile->id.'@facebook.com';
            }
        }
        if ($profile->name) {
            $info['full_name'] = $profile->name;
        }
    }
    return $info;
}
if (!function_exists('getWeek')) {
    function getWeek() {
        $current_week = 1;
        $current_date = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
        $week_1_start = mktime('00', '00', '00', '11', '01', '2012');
        $week_1_end = mktime('23', '59', '59', '11', '07', '2012');
        $week_2_start = mktime('00', '00', '00', '11', '08', '2012');
        $week_2_end = mktime('23', '59', '59', '11', '14', '2012');
        $week_3_start = mktime('00', '00', '00', '11', '15', '2012');
        $week_3_end = mktime('23', '59', '59', '11', '21', '2012');
        $week_4_start = mktime('00', '00', '00', '11', '22', '2012');
        $week_4_end = mktime('23', '59', '59', '11', '30', '2012');
        $week_5_start = mktime('00', '00', '00', '11', '22', '2012');
        $week_5_end = mktime('23', '59', '59', '11', '30', '2012');
        $week_6_start = mktime('00', '00', '00', '11', '22', '2012');
        $week_6_end = mktime('23', '59', '59', '11', '30', '2012');
        $week_7_start = mktime('00', '00', '00', '11', '22', '2012');
        $week_7_end = mktime('23', '59', '59', '11', '30', '2012');
        $week_8_start = mktime('00', '00', '00', '11', '22', '2012');
        $week_8_end = mktime('23', '59', '59', '11', '30', '2012');
        if ($current_date >= $week_1_start && $current_date <= $week_1_end) {
            $current_week = 1;
        } elseif ($current_date >= $week_2_start && $current_date <= $week_2_end) {
            $current_week = 2;
        } elseif ($current_date >= $week_3_start && $current_date <= $week_3_end) {
            $current_week = 3;
        } elseif ($current_date >= $week_4_start && $current_date <= $week_4_end) {
            $current_week = 4;
        } elseif ($current_date >= $week_5_start && $current_date <= $week_5_end) {
            $current_week = 5;
        } elseif ($current_date >= $week_6_start && $current_date <= $week_6_end) {
            $current_week = 6;
        } elseif ($current_date >= $week_7_start && $current_date <= $week_7_end) {
            $current_week = 7;
        } elseif ($current_date >= $week_8_start && $current_date <= $week_8_end) {
            $current_week = 8;
        } else {
            $current_week = 1;
        }
        return $current_week;
    }
}
if (!function_exists('getNow')) {
    function getNow() {
        return date(DATETIME_FORMAT_DB);
    }
}
if (!function_exists('getDateFormat')) {
    function getDateFormat() {
        return date(DATETIME_FORMAT_NODATA);
    }
}
if (!function_exists('last_query')) {
    function last_query($exit = false) {
        $CI = & get_instance();
        echo $CI->db->last_query();
        if ($exit) {
            exit();
        }
    }
}
if (!function_exists('is_file')) {
    function is_file($link_file) {
        $img_temp = null;
        if (file_exists($link_file)) {
            $img_temp = $link_file;
        } else {
            $img_temp = 'default.jpg';
        }
        return $img_temp;
    }
}
if (!function_exists('validate_length')) {
    function validate_length($string, $min = NULL, $max = NULL, $is_number = true) {
        $str_length = strlen($string);
        if ($min != NULL && $max != NULL) {
            if ($str_length >= $min && $str_length <= $max) {
                return true;
            } else {
                return false;
            }
        } elseif ($min != NULL) {
            if ($str_length >= $min) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($str_length <= $max) {
                return true;
            } else {
                return false;
            }
        }
    }
}
/* * ****
* Kiem tra du lieu email
* Return : Boolean
*/
if (!function_exists('validate_email')) {
    function validate_email($email) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
    }
}
if (!function_exists('contest_upload_image')) {
    function contest_upload_image($file = '', $dir_file, $width, $height) {
        $CI = & get_instance();
        $CI->load->library('image_lib');
        $config_thumb_fb = array();
        $config_thumb_fb['image_library'] = 'gd2';
        $config_thumb_fb['source_image'] = DIR_CONTEST . $file;
        $config_thumb_fb['new_image'] = $dir_file . $file;
        $config_thumb_fb['create_thumb'] = FALSE;
        $config_thumb_fb['maintain_ratio'] = TRUE;
        $config_thumb_fb['width'] = $width;
        $config_thumb_fb['height'] = $height;
        $this->image_lib->initialize($config_thumb_fb);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }
}
// tạo field trong ajax_loadContent
if (!function_exists('generate_load_content')) {
    function generate_load_content($arrData) {
        $rs = '';
        foreach ($arrData as $item) {
            $noEdit = "";
            if (isset($item['noEdit'])) {
                if ($item['noEdit']) {
                    $noEdit = "disabled";
                }
            }
            if ($item['inputType'] == 'text') {
                $disable = 'block';
                if (isset($item['disable'])) {
                    $disable = 'none';
                }
                $rs.= '
                    <div class="row_text_field" style="display:' . $disable . '">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td class="left_text_field">' . $item['title'] . ':</td>
                                <td class="right_text_field"><input ' . $noEdit . ' value="' . $item['inputValue'] . '" type="' . $item['inputType'] . '" name="' . $item['inputName'] . '" id="' . $item['inputID'] . '"/></td>
                            </tr>
                        </table>
                    </div>
                ';
            } else if ($item['inputType'] == 'textarea') {
                $rs.= '<div class="row_text_field">';
                $rs.= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
                $rs.= '<tr>';
                $rs.= '<td class="left_text_field">' . $item['title'] . ':</td>';
                $rs.= '<td class="right_text_field" style="padding-right: 0;"><textarea name="' . $item['inputName'] . '" id="' . $item['inputID'] . '" cols="" rows="8">' . $item['inputValue'] . '</textarea>';
                if ($item['EditorVar'] != '') {
                    $rs.= '<script type="text/javascript">';
                    $rs.= 'var ' . $item['EditorVar'] . ' = new InnovaEditor("' . $item['EditorVar'] . '");';
                    $rs.= $item['EditorVar'] . '.width = "100%";';
                    $rs.= $item['EditorVar'] . '.cmdAssetManager="modalDialogShow(' . PATH_URL . 'static/editor/assetmanager/assetmanager.php,640,445);";';
                    $rs.= $item['EditorVar'] . '.REPLACE("' . $item['inputID'] . '")';
                    $rs.= '</script>';
                }
                $rs.= '</td>';
                $rs.= '</tr>';
                $rs.= '</table>';
                $rs.= '</div>';
            } else if ($item['inputType'] == 'select') {
                $rs.= '<div class="row_text_field">';
                $rs.= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
                $rs.= '<tr>';
                $rs.= '<td class="left_text_field">' . $item['title'] . ':</td>';
                $rs.= '<td class="right_text_field">';
                $rs.= '<select name="' . $item['inputName'] . '" id="' . $item['inputID'] . '">';
                $rs.= '<option value="0"> -- ' . $item['optionNo'] . ' -- </option>';
                if (count($item['optionArr']) > 0) {
                    foreach ($item['optionArr'] as $option) {
                        $selected = '';
                        if (isset($item['optionField'])) {
                            $option['name'] = $option[$item['optionField']];
                        }
                        if (isset($item['optionValue'])) {
                            $option['id'] = $option[$item['optionValue']];
                        }
                        if (isset($item['id'])) {
                            if ($item['id'] == $option['id']) {
                                $selected = "selected='selected'";
                            }
                        }
                        $rs.= '<option value="' . $option['id'] . '"' . $selected . '>' . $option['name'] . '</option>';
                    }
                }
                $rs.= '</select>';
                $rs.= '</td>';
                $rs.= '</tr>';
                $rs.= '</table>';
                $rs.= '</div>';
            } else if ($item['inputType'] == 'file') {
                $rs.= '<div class="row_text_field">';
                $rs.= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
                $rs.= '<tr>';
                $rs.= '<td class="left_text_field">' . $item['title'] . ':</td>';
                $rs.= '<td class="right_text_field">';
                $reviewString = '';
                if ($item['fileType'] == 'image') {
                    if (isset($item['inputValue'])) {
                        if ($item['inputValue'] != '') {
                            $reviewString.= '<a class="fancyboxClick" href="' . $item['linkRoot'] . $item['inputValue'] . '">Review</a>';
                        }
                    }
                } else if ($item['fileType'] == 'video' || $item['fileType'] == 'flash') {
                    if (isset($item['inputValue'])) {
                        if ($item['inputValue'] != '') {
                            $reviewString.= '<a class="fancyboxClick" href="#' . $item['idPlayer'] . '">Review</a>';
                            $reviewString.= '<div style="display:none;width:500px;height:300px;">';
                            $reviewString.= '<a href="' . $item['linkRoot'] . $item['inputValue'] . '"
                                               style="display:block;width:500px;height:300px;"
                                               id="' . $item['idPlayer'] . '">
                                            </a>';
                            $reviewString.= '</div>';
                            $reviewString.= '<script language="JavaScript">
                                              flowplayer("' . $item['idPlayer'] . '", "' . PATH_URL . '/static/js/flowplayer/flowplayer-3.2.15.swf", {
                                                 clip:  {
                                                      autoPlay: false,
                                                      autoBuffering: true
                                                  }
                                              });
                                            </script>';
                        }
                    }
                }
                $rs.= '<input type="file" name="fileAdmincp[' . $item['inputName'] . ']" />' . $reviewString;
                $rs.= '</td>';
                $rs.= '</tr>';
                $rs.= '</table>';
                $rs.= '</div>';
            }
        }
        return $rs;
    }
}
if (!function_exists('getDatePicker')) {
    function getDatePicker($date, $time = FALSE) {
        if (!$time) {
            $a_date = explode('-', $date);
            if (!empty($a_date)) {
                return $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
            } else
            return FALSE;
        } else {
            $date.= ':00';
            $pos = strpos($date, ' ');
            $date_v = substr($date, 0, $pos);
            $time = substr($date, $pos);
            $a_date = explode('-', $date_v);
            if (!empty($a_date))
            return $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0] . $time;
            else
            return FALSE;
        }
    }
}
function timthumb($path, $w = '', $h = '', $zc = 1) {
    $name = explode('/', $path);
    if (file_exists($path) && $name[count($name) - 1] != '') {
        if ($w != '' && $h != '') {
            return TIMTHUMB . PATH_URL . $path . "&amp;w=$w&amp;h=$h&amp;zc=$zc&amp;q=100";
        } else if ($w != '' && $h == '') {
            return TIMTHUMB . PATH_URL . $path . "&amp;w=$w&amp;zc=$zc&amp;q=100";
        } else {
            return TIMTHUMB . PATH_URL . $path;
        }
    } else {
        $image_path = 'static/images/img/no-image.jpg';
        return TIMTHUMB . PATH_URL . $image_path . "&amp;w=$w&amp;h=$h&amp;zc=$zc&amp;q=100";
    }
}
function timthumb2($path, $w = '', $h = '', $zc = 1) {
    $name = explode('/', $path);
    if (file_exists($path) && $name[count($name) - 1] != '') {
        if ($w != '' && $h != '') {
            return TIMTHUMB . PATH_URL . $path . "&w=$w&h=$h&zc=$zc&q=100";
        } else if ($w != '' && $h == '') {
            return TIMTHUMB . PATH_URL . $path . "&w=$w&zc=$zc&q=100";
        } else {
            return TIMTHUMB . PATH_URL . $path;
        }
    } else {
        $image_path = 'static/images/img/no-image.jpg';
        return TIMTHUMB . PATH_URL . $image_path . "&w=$w&h=$h&zc=$zc&q=100";
    }
}
function number_to_money($number) {
    $so = '';
    IF ($number > 1000000000000) {
        $nt = floor($number / 1000000000000);
        $number-= $nt * 1000000000000;
        $so.= $nt . ' Nghìn tỷ ';
        $t = floor($number / 1000000000);
        $number-= $t * 1000000000;
        if ($t > 0)
        $so.= $t . ' Tỷ ';
        $tr = floor($number / 1000000);
        $number-= $tr * 1000000;
        if ($tr > 0)
        $so.= $tr . ' Triệu ';
        $n = floor($number / 1000);
        $number-= $n * 1000;
        if ($n > 0)
        $so.= $n . ' Ngàn ';
    } ELSEIF ($number > 1000000000) {
        $t = floor($number / 1000000000);
        $number-= $t * 1000000000;
        $so.= $t . ' Tỷ ';
        $tr = floor($number / 1000000);
        $number-= $tr * 1000000;
        if ($tr > 0)
        $so.= $tr . ' Triệu ';
        $n = floor($number / 1000);
        $number-= $n * 1000;
        if ($n > 0)
        $so.= $n . ' Ngàn ';
    } ELSEIF ($number > 1000000) {
        $tr = floor($number / 1000000);
        $number-= $tr * 1000000;
        $so.= $tr . ' Triệu ';
        $n = floor($number / 1000);
        $number-= $n * 1000;
        if ($n > 0)
        $so.= $n . ' Ngàn ';
    } ELSEIF ($number > 1000) {
        $n = floor($number / 1000);
        $number-= $n * 1000;
        if ($n > 0)
        $so.= $n . ' Ngàn ';
    }
    return $so;
}
function vnd_to_usd($vnd) {
    $usd = 0;
    $tigia = 0;
    $xml = simplexml_load_file('http://www.vietcombank.com.vn/exchangerates/ExrateXML.aspx');
    foreach ($xml->Exrate as $v => $k) {
        if ($k['CurrencyCode'] == "USD") {
            $tigia = $k['Sell'];
        }
    }
    $usd = floor($vnd / $tigia);
    //$usd = number_format ( $usd , 0 , '.' , ',');
    return $usd . ' USD';
}
function tigia() {
    $usd = 0;
    $tigia = 0;
    $xml = simplexml_load_file('http://www.vietcombank.com.vn/exchangerates/ExrateXML.aspx');
    foreach ($xml->Exrate as $v => $k) {
        if ($k['CurrencyCode'] == "USD") {
            $tigia = $k['Sell'];
        }
    }
    return $tigia;
}
if (!function_exists('getVNDateTime')) {
    function getVNDateTime($datetime, $gio = false) {
        $date = '';
        if (!empty($datetime)) {
            if ($gio == true) {
                $date = date('d/m/Y H:i:s', strtotime($datetime));
            } else {
                $date = date('d/m/Y', strtotime($datetime));
            }
        }
        return $date;
    }
}
if (!function_exists('pagination_ajax')) {
    function pagination_ajax($totalRows, $pageNum = 1, $pageSize, $limit = 3, $link_url = "", $class = "") {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
        return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
        return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
        $currentPage = 1;
        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;
        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
        $to = $totalPages;
        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '';
        //Link URL
        $linkUrl = current_url();
        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get.= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';
        if ($currentPage > $limit + 2) {
            /** first */
            //$first= "<a href='$linkUrl' class='first'>...</a>&nbsp;";
        }
        /*         * **** prev ** */
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $prev = "<a href='Javascript:void(0);' class='prev ajax_a' ajax_class='$class' rel='$link_url' page='$prevPage' ></a>";
        }
        /*         * *Next** */
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $next = "<a href='Javascript:void(0);' class='next ajax_a' rel='$link_url' ajax_class='$class'  page='$nextPage'  ></a>";
        }
        /*         * *Last** */
        if ($currentPage < $totalPages - 4) {
            $lastPage = $totalPages;
            //$last= "<a href='$linkUrl$lastPage' class='last'>...</a>";
        }
        /*         * *Link** */
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
            $link.= "<span>$i</span>";
            else
            $link.= "<a class='ajax_a' href='Javascript:void(0);' rel='$link_url' ajax_class='$class' page='$i' >$i</a>";
        }
        $pagination = '<div class="pagination">' . $first . $prev . $link . $next . $last . '</div>';
        return $pagination;
    }
}
//pagelistLimited
/* Nodata */
function showParmas($param, $signal = "-") {
    return (!empty($param) ? $param : $signal);
}
/* * *************************** Nodata ************************ */
/* $thongsokythuat_list = ket qua cau lenh select tu bang cli_thongsokythuat
*   Truyen vao cau lenh select
*
* *************************************************************** */
if (!function_exists('videoThongSoKyThuat')) {
    function videoThongSoKyThuat($thongsokythuat_list, $car_detail) {
        $thongsokythuat_mapping = array();
        $CI = & get_instance();
        $CI->load->model('cars_model');
        if (!empty($thongsokythuat_list)) {
            foreach ($thongsokythuat_list as $thongsokythuat) {
                $field_name = $thongsokythuat->field_name;
                if (!empty($car_detail->$field_name)) {
                    $mota_value_of_car = trim($car_detail->$field_name);
                    $mota_value_of_car = mysql_real_escape_string($mota_value_of_car);
                    $mota_value_of_car_prefix = explode(' ', $mota_value_of_car);
                    if (!empty($mota_value_of_car_prefix[0])) {
                        $mota_value_of_car_prefix = $mota_value_of_car_prefix[0];
                        $mota_value_of_car_prefix = mysql_real_escape_string($mota_value_of_car_prefix);
                        $condition = "thongsokythuat_id = {$thongsokythuat->id} AND (mota_value LIKE '%{$mota_value_of_car}%' OR mota_value LIKE '{$mota_value_of_car_prefix}%')";
                        $thongsokythuat_value = $CI->cars_model->thongsokythuat_value($condition);
                        if (!empty($thongsokythuat_value)) {
                            $thongsokythuat->mota_video = $thongsokythuat_value[0]->mota_video;
                        }
                    }
                }
                $thongsokythuat_mapping[$field_name] = $thongsokythuat;
            }
        }
        return $thongsokythuat_mapping;
    }
}
/*
*
*   Xe lien quan
*   Trong phan detail xe
*/
function xelienquan() {
}
/* * ************************** BEGIN: EXCHANGE RATE - TY GIA VND/USD ************************** */
if (!function_exists('getExchangeRate')) {
    /*
    * Lay ty gia (exchange rate) cua ngay hom nay.
    * Neu khong co thi fill vao (selling2 lay tu ty gia cu).
    * Bat buoc phai co du lieu san trong db
    */
    function getExchangeRate() {
        $CI = & get_instance();
        $today = mktime(0, 0, 0, date("m"), date("d"), date("y"));
        $exchange_rate = $CI->model->fetch('id,code,modified,selling,selling2,created', 'cli_car_exchange_rate', "code = 'USD'", 'id', 'desc', 0, 1, true);
        if (!empty($exchange_rate)) {
             // Bat buoc phai ty gia cu trong db
            $exchange_rate = $exchange_rate[0];
            $created = strtotime($exchange_rate['created']);
            if ($created < $today) {
                $exchange_rate['id'] = '';
                $exchange_rate['code'] = 'USD';
                $exchange_rate['status'] = 1;
                $exchange_rate['created'] = getNow();
                $exchange_rate['modified'] = getNow();
                fillExchangeRate($exchange_rate);
                if ($CI->model->insert('cli_car_exchange_rate', $exchange_rate)) {
                }
            }
        }
        return $exchange_rate;
    }
}
if (!function_exists('fillExchangeRate')) {
    function fillExchangeRate(&$exchange_rate) {
        if (!empty($exchange_rate)) {
            $url = "http://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx";
            $xml = file_get_contents($url);
            $pattern = '/CurrencyCode=\"USD\"(.*)Sell=\"(.*)\"/';
            $offset = 0;
            $flag = PREG_OFFSET_CAPTURE;
            preg_match_all($pattern, $xml, $matches, $flag, $offset);
            if (!empty($matches[2][0][0])) {
                $selling = $matches[2][0][0];
                $exchange_rate['selling'] = $selling;
            }
        }
    }
}
/* Begin: Xe liên quan (Chi tiết xe) */
if (!function_exists('get_xelienquan_conditions')) {
    function get_xelienquan_conditions($car_detail, $exchange_rate) {
        $conditions = array();
        $CI = & get_instance();
        if (!empty($car_detail) && !empty($exchange_rate)) {
            $exchange_rate_revert = 1;
            if (!empty($exchange_rate['selling'])) {
                $exchange_rate_revert = 1000000 / $exchange_rate['selling'];
            }
            $percent = 10 / 100;
            $low = 0;
            $above = 0;
            if ($car_detail->price > 0) {
                $low = $car_detail->price - $percent * $car_detail->price;
                $above = $car_detail->price + $percent * $car_detail->price;
            } else if ($car_detail->price_average > 0) {
                $low = $car_detail->price_average - $percent * $car_detail->price_average;
                $above = $car_detail->price_average + $percent * $car_detail->price_average;
            }
            if ($car_detail->loaitien == 'VNĐ') {
                $low = $low * $exchange_rate_revert;
                $above = $above * $exchange_rate_revert;
            }
            $low = number_format($low, 0, '.', '');
            $above = number_format($above, 0, '.', '');
            $same_price_condition = "((currency_price*(POWER(1000000,ABS(cli_cars.base)))/(POWER({$exchange_rate['selling']},cli_cars.base))) BETWEEN $low AND $above)";
             //Duplicate currency_price
            $body_style_id_str = '';
            $body_style_list = $CI->model->fetch('id,body_style_id', 'cli_car_sub_body_style', "car_id = {$car_detail->id}", '', '', -1, 0, true);
            // Xe cùng dòng
            $related_condition = '1';
            if (!empty($body_style_list)) {
                foreach ($body_style_list as $body_style) {
                    $body_style_id_str.= $body_style['body_style_id'] . ',';
                }
                $body_style_id_str = substr($body_style_id_str, 0, -1);
                $related_condition = "body_type_id IN ({$body_style_id_str})";
            }
            // General condition
            $condition = "cli_cars.status = 1 AND cli_cars.sold = 0 AND cli_cars.id <> {$car_detail->id}";
            $conditions['xedonggia'] = "$condition AND $same_price_condition";
             //Giá chênh lệch 10%
            $conditions['xetuongtu'] = "$condition AND ABS(year_name - {$car_detail->year_name}) <= 1 AND manufacturer_id = {$car_detail->manufacturer_id} AND model_id = {$car_detail->model_id}";
             //Cùng thương hiệu - Cùng model - Năm sản xuất không quá 1 năm
            $conditions['xecungphankhuc'] = "$condition AND $same_price_condition AND $related_condition AND manufacturer_id <> {$car_detail->manufacturer_id} AND ABS(year_name - {$car_detail->year_name}) <= 1";
             //Khác thương hiệu - Cùng dòng xe - Cùng Model - Giá không quá 10% - Năm sản xuất không quá 1 năm
        }
        return $conditions;
    }
}
if (!function_exists('get_price')) {
    function get_price($car_detail, $separator = ' - ', &$is_from_to = false) {
        $final_price = '';
        $is_valid = false;
        if (is_array($car_detail)) {
            if (isset($car_detail['price']) && isset($car_detail['price_from']) && isset($car_detail['price_to']) && isset($car_detail['loaitien'])) {
                $price = $car_detail['price'];
                $price_from = $car_detail['price_from'];
                $price_to = $car_detail['price_to'];
                $loaitien = $car_detail['loaitien'];
                $is_valid = true;
            }
        } else {
            if (isset($car_detail->price) && isset($car_detail->price_from) && isset($car_detail->price_to) && isset($car_detail->loaitien)) {
                $price = $car_detail->price;
                $price_from = $car_detail->price_from;
                $price_to = $car_detail->price_to;
                $loaitien = $car_detail->loaitien;
                $is_valid = true;
            }
        }
        if ($is_valid) {
            if (!empty($price)) {
                 // Truong hop dang final_price = price
                $final_price = convertcurrencies($price, $loaitien);
            } else {
                if (!empty($price_to)) {
                     // Truong hop dang final_price = price_from - price_to
                    $price_from = convertcurrencies($price_from, $loaitien, false, true);
                    $price_to = convertcurrencies($price_to, $loaitien);
                    $final_price = $price_from . $separator . $price_to;
                    $is_from_to = true;
                } else {
                     // Truong hop dang final_price = price_from
                    $final_price = convertcurrencies($price_from, $loaitien);
                }
            }
        }
        return $final_price;
    }
}
if (!function_exists('get_price2')) {
    function get_price2($car_detail, $separator = ' - ', &$is_from_to = false) {
        $final_price = '';
        $is_valid = false;
        if (is_array($car_detail)) {
            if (isset($car_detail['price']) && isset($car_detail['price_from']) && isset($car_detail['price_to']) && isset($car_detail['loaitien'])) {
                $price = $car_detail['price'];
                $price_from = $car_detail['price_from'];
                $price_to = $car_detail['price_to'];
                $loaitien = $car_detail['loaitien'];
                $is_valid = true;
            }
        } else {
            if (isset($car_detail->price) && isset($car_detail->price_from) && isset($car_detail->price_to) && isset($car_detail->loaitien)) {
                $price = $car_detail->price;
                $price_from = $car_detail->price_from;
                $price_to = $car_detail->price_to;
                $loaitien = $car_detail->loaitien;
                $is_valid = true;
            }
        }
        if ($is_valid) {
            if (!empty($price)) {
                 // Truong hop dang final_price = price
                if ($loaitien == '$') {
                    $pricevn = ($price * tigia()) / 1000000;
                    $priceusd = $price;
                } else {
                    $pricevn = $price;
                    $priceusd = ($price * 1000000) / tigia();
                }
                $final_price['vnd'] = convertcurrencies($pricevn, 'vnd');
                $final_price['usd'] = convertcurrencies($priceusd, '');
            } else {
                if (!empty($price_to)) {
                     // Truong hop dang final_price = price_from - price_to
                    if ($loaitien == '$') {
                        $price_fromvn = ($price_from * tigia() / 1000000);
                        $price_fromusd = $price_from;
                        $price_tovn = ($price_to * tigia()) / 1000000;
                        $price_tousd = $price_to;
                    } else {
                        $price_fromvn = $price_from;
                        $price_fromusd = ($price_from * 1000000) / tigia();
                        $price_tovn = $price_to;
                        $price_tousd = ($price_to * 1000000) / tigia();
                    }
                    $price_from_vn = convertcurrencies($price_fromvn, 'vnd', false, true);
                    $price_tovn = convertcurrencies($price_tovn, 'vnd');
                    $price_from_usd = convertcurrencies($price_fromusd, '', false, true);
                    $price_to_usd = convertcurrencies($price_tousd, '');
                    $final_price['vnd'] = $price_from_vn . $separator . $price_tovn;
                    $final_price['usd'] = $price_from_usd . $separator . $price_to_usd;
                    $is_from_to = true;
                } else {
                     // Truong hop dang final_price = price_from
                    if ($loaitien == '$') {
                        $pricevn = ($price_from * tigia()) / 1000000;
                        $priceusd = $price_from;
                    } else {
                        $pricevn = $price_from;
                        $priceusd = ($price_from * 1000000) / tigia();
                    }
                    $final_price['vnd'] = convertcurrencies($pricevn, 'vnd');
                    $final_price['usd'] = convertcurrencies($priceusd, '');
                }
            }
        }
        return $final_price;
    }
}
if (!function_exists('convertcurrencies')) {
    function convertcurrencies($number = 0, $style = ' ', $is_vn_new_line = false, $notrieu = false) {
        $html = '';
        $number = (int)$number;
        if ($style == "" || $style == "0") {
            $style = '$';
        }
        $nombre_format_francais = number_format($number, 0, ',', '.');
        if ($style == '$') {
            $html = $style . $nombre_format_francais;
        } else {
            if ($is_vn_new_line) {
                $html = $nombre_format_francais . "<br/>Triệu " . $style;
            } else {
                $html = $nombre_format_francais . " Triệu " . $style;
                if ($notrieu) {
                    $html = $nombre_format_francais;
                }
            }
        }
        return $html;
    }
}
if (!function_exists('change_date_comat')) {
    function change_date_comat() {
    }
}
if (!function_exists('check_exists_file')) {
    function check_exists_file($filename) {
        if (file_exists($filename)) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('ngay_ra_mat')) {
    function ngay_ra_mat($data) {
        $now = getDateFormat();
        $date = strtotime($data) - strtotime($now);
        $count_date = date("d", $date);
        if ($count_date <= 0) {
            return 0;
        }
        return $count_date;
    }
}
/*
find an value in multi dimension array
@param :
$value : value to search
$array : container array
@return TRUE if found else FALSE
*/
if (!function_exists('value_in_array')) {
    function value_in_array($value, $array) {
        foreach ($array as $item) {
            if (!is_array($item)) {
                if ($item == $value)
                return true;
                else
                continue;
            }
            if (in_array($value, $item))
            return true;
            else if (value_in_array($value, $item))
            return true;
        }
        return false;
    }
}
// if (!function_exists('write_file_txt')) {
//     function write_file_txt($path, $value) {
//         if (is_writable($path)) {
//             // In our example we're opening $filename in append mode.
//             // The file pointer is at the bottom of the file hence
//             // that's where $somecontent will go when we fwrite() it.
//             if (!$handle = fopen($path, 'w')) {
//                 return false;
//             }
//             // Write $somecontent to our opened file.
//             if (fwrite($handle, $value) === FALSE) {
//                 return false;
//             }
//             return true;
//             fclose($handle);
//         } else {
//             return false;
//         }
//         return false;
//     }
// }
if (!function_exists('write_file_txt')) {
    function write_file_txt($path, $value) {
        if (is_writable($path)) {
            if (!$handle = fopen($path, 'a+')) {
                return false;
            }
            // Write $somecontent to our opened file.
            if (fwrite($handle, $value) === FALSE) {
                return false;
            }
            return true;
            fclose($handle);
        }else{
            if (!$handle = fopen($path, 'w')) {
                return false;
            }
            // Write $somecontent to our opened file.
            if (fwrite($handle, $value) === FALSE) {
                return false;
            }
            return true;
            fclose($handle);
        }
    }
}
if (!function_exists('read_file_txt')) {
    function read_file_txt($path) {
        $file = $path;
        $fp = @fopen($file, "r");
        $content = @fread($fp, filesize($file));
        return $content;
    }
}
if (!function_exists('count_time')) {
    function count_time($datestart) {
        $count_time = ceil(((((strtotime($datestart) - strtotime(date("Y-m-d H:i:s"))) / 60) / 60) / 24));
        if ($count_time < 0) {
            $count_time = 0;
        } else {
            $count_time = $count_time;
        }
        return $count_time;
    }
}
if (!function_exists('get_compare_link')) {
    function get_compare_link($car_id) {
        $CI = & get_instance();
        $session_cars = $CI->session->userdata('compare_list');
        if (empty($session_cars))
        $session_cars = array();
        if (!in_array($car_id, $session_cars)) {
            array_unshift($session_cars, $car_id);
        }
        $session_cars_re = array_reverse($session_cars);
        $str = implode('+', $session_cars_re);
        return PATH_URL . 'so-sanh-xe?maxe=' . $str;
    }
}
/**
 * getSiteName()
 *
 * @return
 */
if (!function_exists('getSiteName')) {
    function getSiteName() {
        $retval = '';
        $CI = & get_instance();
        $CI->db->where('slug', 'title-admincp');
        $row = $CI->db->get('admin_nqt_settings')->row();
        if (!empty($row))
        $retval = $row->content;
        return $retval;
    }
}
if (!function_exists('getCount')) {
    function getCount() {
        $retval = '';
        $CI = & get_instance();
        $CI->db->where('slug', 'count');
        $row = $CI->db->get('admin_nqt_settings')->row();
        if (!empty($row))
        $retval = $row->content;
        return $retval;
    }
}
if (!function_exists('getCategoryByName')) {
    function getCategoryByName($cate, &$array_dq = '') {
        $retval = '';
        $CI = & get_instance();
        if (!isset($cate->id)) {
            $CI->db->where(array('name' => "$cate"));
            $CI->db->or_where(array('slug' => "$cate"));
            $cate = $CI->db->get('cli_category')->row();
        }
        if (isset($cate->id)) {
            $array_dq = $cate;
            $CI->db->where(array('parent' => $cate->id));
            $CI->db->order_by("index", "asc");
            $children = $CI->db->get('cli_category')->result();
            if (!empty($children)) {
                $array_dq->children = $children;
                foreach ($children as $key => $value) {
                    getCategoryByName($value);
                }
            }
            return $array_dq;
        }
    }
}
if (!function_exists('getFullCategory')) {
    function getFullCategoryOption($id_active) {
        $parentId = 0;
        $array_dq = '';
        $html = "";
        $retval = '';
        $selected = "";
        $CI = & get_instance();
        if (!isset($cate->id)) {
            $CI->db->where(array('parent' => $parentId));
            $cate = $CI->db->get('cli_category')->result();
        }
        foreach ($cate as $key => $value) {
            $array_dq[$key] = $value;
            $CI->db->where(array('parent' => $value->id));
            $cate1 = $CI->db->get('cli_category')->result();
            $selected = "";
            if ($id_active == $value->id) {
                $selected = "selected='selected'";
            }
            $html.= "<option {$selected} class='level_0' value='{$value->id}' >{$value->name}</option>";
            if (!empty($cate1)) {
                foreach ($cate1 as $k1 => $v1) {
                    $array_dq[$key]->children[$k1] = $v1;
                    $selected = "";
                    if ($id_active == $v1->id)
                    $selected = "selected='selected'";
                    $html.= "<option {$selected} class='level_1' value='{$v1->id}' >&nbsp;&nbsp{$v1->name}</option>";
                    $CI->db->where(array('parent' => $v1->id));
                    $cate2 = $CI->db->get('cli_category')->result();
                    foreach ($cate2 as $k2 => $v2) {
                        $array_dq[$key]->children[$k1]->children[$k2] = $v2;
                        $selected = "";
                        if ($id_active == $v2->id)
                        $selected = "selected='selected'";
                        $html.= "<option {$selected} class='level_2' value='{$v2->id}' >&nbsp;&nbsp;&nbsp;&nbsp;{$v2->name}</option>";
                        $CI->db->where(array('parent' => $v2->id));
                        $cate3 = $CI->db->get('cli_category')->result();
                        foreach ($cate3 as $k3 => $v3) {
                            $array_dq[$key]->children[$k1]->children[$k2]->children[$k3] = $v3;
                            $selected = "";
                            if ($id_active == $v3->id)
                            $selected = "selected='selected'";
                            $html.= "<option {$selected} class='level_3' value='{$v3->id}' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp{$v3->name}</option>";
                        }
                    }
                }
            }
        }
        // pr($html);die;
        return $html;
        //  pr($array_dq."123123");die;
    }
}
function getCategoryTreeId($cate, &$ids = "") {
    $retval = '';
    $CI = & get_instance();
    if (!isset($cate->id)) {
        $CI->db->where(array('name' => "$cate"));
        $CI->db->or_where(array('slug' => "$cate"));
        $CI->db->or_where(array('id' => "$cate"));
        $cate = $CI->db->get('cli_category')->row();
    }
    if (isset($cate->id)) {
        $ids.= $cate->id . ",";
        $CI->db->where(array('parent' => $cate->id));
        $CI->db->order_by("index", "asc");
        $children = $CI->db->get('cli_category')->result();
        if (!empty($children)) {
            foreach ($children as $key => $value) {
                $ids.= $value->id . ",";
                getCategoryTreeId($value, $ids);
            }
        }
    }
    return substr($ids, 0, -1);
}
if (!function_exists('get_security')) {
    function get_security() {
        $retval = '';
        $CI = & get_instance();
        $CI->db->where('slug', 'pass_security');
        $row = $CI->db->get('admin_nqt_settings')->row();
        if (!empty($row))
        $retval = $row->content;
        return $retval;
    }
}
if (!function_exists('getVideo')) {
    function getVideo() {
        $retval = 'http://www.youtube.com/watch?v=Ql-NJBmTaBw';
        $CI = & get_instance();
        $CI->db->where('slug', 'video');
        $row = $CI->db->get('admin_nqt_settings')->row();
        if (!empty($row))
        $retval = $row->content;
        return $retval;
    }
}
if (!function_exists('rss_to_array')) {
    function rss_to_array($tag, $array, $url) {
        $doc = new DOMdocument();
        $doc->load($url);
        $rss_array = array();
        $items = array();
        foreach ($doc->getElementsByTagName($tag) AS $node) {
            foreach ($array AS $key => $value) {
                $items[$value] = $node->getElementsByTagName($value)->item(0)->nodeValue;
            }
            array_push($rss_array, $items);
        }
        return $rss_array;
    }
}
function load_time($day, $hour, $minute, $second) {
    $date_day = date("d");
    $date_hour = date("H");
    $date_minute = date("i");
    $date_second = date("s");
    $date_last = date("d H:i:s", mktime(12, 0, 0, 1, 17, 2013));
    $date_last_day = date("d", mktime(12, 0, 0, 1, $day - $date_day, 2013));
    $date_last_hour = date("d", mktime($hour - $date_hour, 0, 0, 1, 17, 2013));
    $date_last_minute = date("d", mktime(12, $minute - $date_minute, 0, 1, 17, 2013));
    $date_last_second = date("d", mktime(12, 0, $second - $date_second, 1, 17, 2013));
    $main_time = array();
    return $date_last_day;
    /* return $date_last_hour;
    return $date_last_minute;
    return $date_last_second; */
}
if (!function_exists('post')) {
    function post($link = '', $field = array()) {
        include_once ('application/libraries/eac_curl.class.php');
        $options = array();
        $fields = array();
        $options['CURLOPT_AUTOREFERER'] = 1;
        $options['CURLOPT_CRLF'] = 1;
        $options['CURLOPT_NOPROGRESS'] = 1;
        $options['CURLOPT_RETURNTRANSFER'] = 1;
        $http = new cURL($options);
        $http->setOptions($options);
        $result = $http->post($link, $field);
        return $result;
    }
}
function get_menu_data() {
    $CI = & get_instance();
    $data = $CI->model->fetch('title,slug', PREFIX . 'static_page', 'status = 1');
    foreach ($data as $key => $value) {
        $sub = $this->model->fetch('title,slug', PREFIX . 'news', " `type` ='Cẩm nang' AND status = 1", 'order', 'desc');
        $data->sub = $sub;
    }
    return $data;
}
if (!function_exists('is_local')) {
    function is_local($ip = '') {
        $ip_list = array('115.79.51.109','42.119.2.31');
        $ip_list2 = array();
        $CI = & get_instance();
        $sub = $CI->db->query("SELECT * from admin_nqt_settings WHERE slug = 'is_local'");
        if ($sub->num_rows > 0) {
            $result = $sub->result_array();
            $ip_list2 = json_decode($result[0]['content'], true);
        }
        $ip = hainamgetClientIP();
        if(in_array(trim($ip), array_merge($ip_list, $ip_list2)) ) {
            return TRUE;
        }else return FALSE;
    }
}
function hainamgetClientIP(){
    if (array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER)) { 
        return $_SERVER["HTTP_CF_CONNECTING_IP"]; 
    }else 
    if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
        return $_SERVER["REMOTE_ADDR"]; 
    }else
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
        $ip = explode(',',$_SERVER["HTTP_X_FORWARDED_FOR"]);
        return  $ip[0];  
    }else  if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER["HTTP_CLIENT_IP"]; 
    } 
    return '';
}
if (!function_exists('ban_ip')) {
    function ban_ip($ip = '') {
        $ip_list = array(
        '118.68.205.25',
        '113.165.55.41',
        '117.5.39.224');
        $ip = getIP();
        // echo $ip;die();
        if (in_array($ip, $ip_list)) {
            return TRUE;
        } else
        return FALSE;
    }
}
if (!function_exists('img_tintuc')) {
    function img_tintuc($image_path, $width = 0, $height = 0) {
        //Get the Codeigniter object by reference
        $CI = & get_instance();
        //Alternative image if file was not found
        if (!file_exists($image_path) || !is_file($image_path)) {
            $image_path = 'static/images/img/title-tintuc.png';
        }
        //The new generated filename we want
        $fileinfo = pathinfo($image_path);
        //MAKE A FOLDER
        if (!empty($width) && !empty($height)) {
            $uploadDir_Thumb = 'static/cache/' . $width . 'x' . $height . '/';
        } else {
            $uploadDir_Thumb = 'static/cache/thumb/';
        }
        Newfolder($uploadDir_Thumb);
        $new_image_path = $uploadDir_Thumb . $fileinfo['filename'] . '.' . $fileinfo['extension'];
        //The first time the image is requested
        //Or the original image is newer than our cache image
        if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            $CI->load->library('image_lib');
            //The original sizes
            $original_size = getimagesize($image_path);
            $original_width = $original_size[0];
            $original_height = $original_size[1];
            $ratio = $original_width / $original_height;
            //The requested sizes
            $requested_width = $width;
            $requested_height = $height;
            //if smaller then return
            if (($requested_width > $original_width) && $requested_height == 0)
            return PATH_URL . $image_path;
            elseif (($requested_height > $original_height) && $requested_width == 0)
            return PATH_URL . $image_path;
            //Initialising
            $new_width = 0;
            $new_height = 0;
            //Calculations
            if ($requested_width > $requested_height) {
                $new_width = $requested_width;
                $new_height = $new_width / $ratio;
                if ($requested_height == 0)
                $requested_height = $new_height;
                if ($new_height < $requested_height) {
                    $new_height = $requested_height;
                    $new_width = $new_height * $ratio;
                }
            } else {
                $new_height = $requested_height;
                $new_width = $new_height * $ratio;
                if ($requested_width == 0)
                $requested_width = $new_width;
                if ($new_width < $requested_width) {
                    $new_width = $requested_width;
                    $new_height = $new_width / $ratio;
                }
            }
            $new_width = ceil($new_width);
            $new_height = ceil($new_height);
            //Resizing
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = $new_image_path;
            $config['maintain_ratio'] = FALSE;
            $config['height'] = $new_height;
            $config['width'] = $new_width;
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
            //Crop if both width and height are not zero
            if (($width != 0) && ($height != 0)) {
                $x_axis = floor(($new_width - $width) / 2);
                $y_axis = floor(($new_height - $height) / 2);
                //Cropping
                $config = array();
                $config['source_image'] = $new_image_path;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = $new_image_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['x_axis'] = $x_axis;
                $config['y_axis'] = $y_axis;
                $config['quality'] = '100%';
                $CI->image_lib->initialize($config);
                $CI->image_lib->crop();
                $CI->image_lib->clear();
            }
        }
        return PATH_URL . $new_image_path;
    }
}
if (!function_exists('img_sukien')) {
    function img_sukien($image_path, $width = 0, $height = 0) {
        //Get the Codeigniter object by reference
        $CI = & get_instance();
        //Alternative image if file was not found
        if (!file_exists($image_path) || !is_file($image_path)) {
            $image_path = 'static/images/img/title-sukien.png';
        }
        //The new generated filename we want
        $fileinfo = pathinfo($image_path);
        //MAKE A FOLDER
        if (!empty($width) && !empty($height)) {
            $uploadDir_Thumb = 'static/cache/' . $width . 'x' . $height . '/';
        } else {
            $uploadDir_Thumb = 'static/cache/thumb/';
        }
        Newfolder($uploadDir_Thumb);
        $new_image_path = $uploadDir_Thumb . $fileinfo['filename'] . '.' . $fileinfo['extension'];
        //The first time the image is requested
        //Or the original image is newer than our cache image
        if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            $CI->load->library('image_lib');
            //The original sizes
            $original_size = getimagesize($image_path);
            $original_width = $original_size[0];
            $original_height = $original_size[1];
            $ratio = $original_width / $original_height;
            //The requested sizes
            $requested_width = $width;
            $requested_height = $height;
            //if smaller then return
            if (($requested_width > $original_width) && $requested_height == 0)
            return PATH_URL . $image_path;
            elseif (($requested_height > $original_height) && $requested_width == 0)
            return PATH_URL . $image_path;
            //Initialising
            $new_width = 0;
            $new_height = 0;
            //Calculations
            if ($requested_width > $requested_height) {
                $new_width = $requested_width;
                $new_height = $new_width / $ratio;
                if ($requested_height == 0)
                $requested_height = $new_height;
                if ($new_height < $requested_height) {
                    $new_height = $requested_height;
                    $new_width = $new_height * $ratio;
                }
            } else {
                $new_height = $requested_height;
                $new_width = $new_height * $ratio;
                if ($requested_width == 0)
                $requested_width = $new_width;
                if ($new_width < $requested_width) {
                    $new_width = $requested_width;
                    $new_height = $new_width / $ratio;
                }
            }
            $new_width = ceil($new_width);
            $new_height = ceil($new_height);
            //Resizing
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = $new_image_path;
            $config['maintain_ratio'] = FALSE;
            $config['height'] = $new_height;
            $config['width'] = $new_width;
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
            //Crop if both width and height are not zero
            if (($width != 0) && ($height != 0)) {
                $x_axis = floor(($new_width - $width) / 2);
                $y_axis = floor(($new_height - $height) / 2);
                //Cropping
                $config = array();
                $config['source_image'] = $new_image_path;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = $new_image_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['x_axis'] = $x_axis;
                $config['y_axis'] = $y_axis;
                $config['quality'] = '100%';
                $CI->image_lib->initialize($config);
                $CI->image_lib->crop();
                $CI->image_lib->clear();
            }
        }
        return PATH_URL . $new_image_path;
    }
}
if (!function_exists('img_congdong')) {
    function img_congdong($image_path, $width = 0, $height = 0) {
        //Get the Codeigniter object by reference
        $CI = & get_instance();
        //Alternative image if file was not found
        if (!file_exists($image_path) || !is_file($image_path)) {
            $image_path = 'static/images/img/title-congdong.png';
        }
        //The new generated filename we want
        $fileinfo = pathinfo($image_path);
        //MAKE A FOLDER
        if (!empty($width) && !empty($height)) {
            $uploadDir_Thumb = 'static/cache/' . $width . 'x' . $height . '/';
        } else {
            $uploadDir_Thumb = 'static/cache/thumb/';
        }
        Newfolder($uploadDir_Thumb);
        $new_image_path = $uploadDir_Thumb . $fileinfo['filename'] . '.' . $fileinfo['extension'];
        //The first time the image is requested
        //Or the original image is newer than our cache image
        if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            $CI->load->library('image_lib');
            //The original sizes
            $original_size = getimagesize($image_path);
            $original_width = $original_size[0];
            $original_height = $original_size[1];
            $ratio = $original_width / $original_height;
            //The requested sizes
            $requested_width = $width;
            $requested_height = $height;
            //if smaller then return
            if (($requested_width > $original_width) && $requested_height == 0)
            return PATH_URL . $image_path;
            elseif (($requested_height > $original_height) && $requested_width == 0)
            return PATH_URL . $image_path;
            //Initialising
            $new_width = 0;
            $new_height = 0;
            //Calculations
            if ($requested_width > $requested_height) {
                $new_width = $requested_width;
                $new_height = $new_width / $ratio;
                if ($requested_height == 0)
                $requested_height = $new_height;
                if ($new_height < $requested_height) {
                    $new_height = $requested_height;
                    $new_width = $new_height * $ratio;
                }
            } else {
                $new_height = $requested_height;
                $new_width = $new_height * $ratio;
                if ($requested_width == 0)
                $requested_width = $new_width;
                if ($new_width < $requested_width) {
                    $new_width = $requested_width;
                    $new_height = $new_width / $ratio;
                }
            }
            $new_width = ceil($new_width);
            $new_height = ceil($new_height);
            //Resizing
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = $new_image_path;
            $config['maintain_ratio'] = FALSE;
            $config['height'] = $new_height;
            $config['width'] = $new_width;
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
            //Crop if both width and height are not zero
            if (($width != 0) && ($height != 0)) {
                $x_axis = floor(($new_width - $width) / 2);
                $y_axis = floor(($new_height - $height) / 2);
                //Cropping
                $config = array();
                $config['source_image'] = $new_image_path;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = $new_image_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['x_axis'] = $x_axis;
                $config['y_axis'] = $y_axis;
                $config['quality'] = '100%';
                $CI->image_lib->initialize($config);
                $CI->image_lib->crop();
                $CI->image_lib->clear();
            }
        }
        return PATH_URL . $new_image_path;
    }
}

function createEditor($id, $value = '', $width = '100%', $height = '600px', $path = '../../../../') {
    $CI = & get_instance();
    $CI->load->library('ckeditor');
    $CI->load->library('ckfinder');

    //Thư mục asset chứa ckeditor và ckfinder
    $CI->ckeditor->basePath = PATH_URL_PUBLIC.'public/admin/assets/ckeditor/';
    //Thiết lập các tool icon SetupCKEditor
    $CI->ckeditor->config['toolbar'] = array(
    array(
    'autosave',
    'Youtube',
    'Source',
    'Undo',
    'Redo',
    '-',
    'Bold',
    'Italic',
    'Underline',
    '-',
    'JustifyLeft',
    'JustifyCenter',
    'JustifyRight',
    'JustifyBlock',
    '-',
    'Outdent',
    'Indent',
    '-',
    'Cut',
    'Copy',
    'Paste',
    'PasteText',
    'PasteFromWord',
    '-',
    '-',
    'NumberedList',
    'BulletedList',
    '-',
    'Blockquote',
    'CreateDiv',
    'Find',
    'Replace',
    '-',
    'SelectAll',
    '-',
    'SpellChecker',
    'Scayt',
    'Link',
    'Unlink',
    'Anchor',
    'Image',
    'Flash',
    'Table',
    'PageBreak',
    '-',
    'Subscript',
    'Superscript',
    '-',
    'RemoveFormat',
    'Styles',
    'Format',
    'Font',
    'FontSize',
    'TextColor',
    'BGColor',
    'Code'));
    // Thiết lập ngôn ngữ hiển thị en => english, vi => Việt Nam , fr => Pháp
    $CI->ckeditor->config['language'] = 'en';
    $CI->ckeditor->config['width'] = $width;
    $CI->ckeditor->config['height'] = $height;
    //Thêm ckfinder vào
   
    $CI->ckfinder->SetupCKEditor($CI->ckeditor, $path . 'public/admin/assets/ckfinder/');
    return $CI->ckeditor->editor($id, $value);
}
function cURLGet($url = '', $params = '') {
    include_once ('application/libraries/eac_curl.class.php');
    $options = array();
    $options['CURLOPT_AUTOREFERER'] = 1;
    $options['CURLOPT_CRLF'] = 1;
    $options['CURLOPT_NOPROGRESS'] = 1;
    $http = new cURL($options);
    $http->setOptions($options);
    if ($params) {
        if (substr($url, -1) != '?')
        $url.= "?";
        $url = $url . http_build_query($params);
    }
    $src = $http->get($url);
    // if(is_local()){
    //             pr($src,1);
    //         }
    return $src;
}
function callAPI($action = '', $ipserver = '', $server = 0, $params = "", $test = FALSE) {
    //pr($ipserver,1);
    if ($action && $params && $server) {
        $url = "http://{$ipserver}/game_s{$server}/{$action}.php?";
        //pr($url,1);
        //pr($params,1);
        $query_string = http_build_query($params);
        //pr($url . $query_string);
        // if(is_local())
        // var_dump(cURLGet($url . $query_string));die();
        return str_replace('""', '"', cURLGet($url . $query_string));
    }
    return FALSE;
}
function getSetting($slug = '') {
    $CI = & get_instance();
    $setting = $CI->model->get('content', 'admin_nqt_settings', "`slug` = '{$slug}'");
    if ($setting)
    return $setting->content;
    else
    return FALSE;
}
function gamename($name, $sid) {
    return strtolower($name . "_" . $sid);
}
function makeURLGame($user, $server) {
    $gamename = (empty($user->rand_username)) ? $user->username : $user->rand_username;
    $gamename2 = $gamename . "_" . md5($user->id . $user->token);
    // pr($gamename2,1);
    $uname = rawurlencode(base64_encode(strtolower($gamename2)));
    $upass = rawurlencode(base64_encode($user->password));
    $server->sub_folder = $server->sub_folder ? $server->sub_folder : '/game/games_online.php';
    $url = "http://" . $server->ip . ":" . $server->port_game . $server->sub_folder . "?username={$uname}&password={$upass}&server_id={$server->id}";
    return $url;
}
function getServiceURL($server, $nameservice) {
    $ip = strpos($server->url_service, 'http://') === FALSE ? "http://" . $server->url_service : $server->url_service;
    $tmp = substr($ip, -1);
    $url_service = $tmp == "/" ? $ip : $ip . "/";
    $url = $url_service . "API/" . $nameservice;
    return $url;
}
if (!function_exists('uploadToHost')) {
    function uploadToHost($tmpFile = "", $name_file = "", $folder = "no_dir") {
        $info_file = pathinfo($name_file);
        $info_image = getimagesize($tmpFile);
        $image_type = $info_image[2];     
        if(!in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
        return false;
        if (isset($info_image[0])) {
            $name_file = $info_file['filename'] . "_" . date('y') . "_" . date('m') . "_" . date('d') . "_" . date('i') . "_" . date('s') . "_" . $info_image[0] . "x" . $info_image[1] . "." . $info_file['extension'];
        } else {
            $name_file = $info_file['filename'] . "_" . date('y') . "_" . date('m') . "_" . date('d') . "_" . date('i') . "_" . date('s') . "." . $info_file['extension'];
        }
        $array_dir = array(
        DIR_UPLOAD . $folder,
        DIR_UPLOAD . $folder . "/" . date('Y') . "/",
        DIR_UPLOAD . $folder . "/" . date('Y') . "/" . date('m') . "/",
        DIR_UPLOAD . $folder . "/" . date('Y') . "/" . date('m') . "/" . date('d') . "/");
        foreach ($array_dir as $key => $value) {
            if (!is_dir($value)) {
                mkdir($value, 0777);
            }
        }
        $remote_file = end($array_dir) . $name_file;
        if (move_uploaded_file($tmpFile, $remote_file)) {
            return strstr($remote_file, 'uploads/');
        } else {
            return 'false';
        }
        return 'false';
    }
}
if (!function_exists('getPathViewImage')) {
    function getPathViewImage($path) {
		$path = str_replace("static/", "", $path);
        return PATH_URL . "static/" . $path;
    }
}
if (!function_exists('getCacheImageByUrl')) {
    function getCacheImageByUrl($image_path, $width = 0, $height = 0, $type = 1) {
        if (isset($image_path)) {
            $url_timthum = PATH_URL . "timthumb.php?src=" . $image_path . "&w={$width}&h={$height}&zc={$type}";
            return $url_timthum;
        } else {
            $image_path = PATH_URL . 'static/images/img/no-image.jpg';
            $url_timthum = PATH_URL . "timthumb.php?src=" . $image_path . "&w={$width}&h={$height}&zc=2";
            return $url_timthum;
        }
    }
}
if (!function_exists('getCacheImage')) {
    function getCacheImage($image_path, $width = 0, $height = 0,$type=1){
        if(CACHE_UPLOAD==1){
            $url_img = CACHE_URL_IMAGE.$image_path;
        }else{
            $url_img = PATH_URL.$image_path;
        }
        if(!$image_path){
                $url_img = PATH_URL.'static/images/img/no-image.jpg';
        }
        $url_timthum=PATH_URL."timthumb.php?src=".$url_img."&w={$width}&h={$height}&zc={$type}";
        return $url_timthum;
    }
}
if (!function_exists('getCacheImage2')) {
    function getCacheImage2($image_path, $width = 0, $height = 0, $type = 1) {
        $url_img = $image_path;
        if (!$image_path) {
            //  $url_img = PATH_URL.'static/images/img/no-image.jpg';
        }
        $url_timthum = PATH_URL . "timthumb.php?src=" . $url_img . "&w={$width}&h={$height}&zc={$type}";
        return $url_timthum;
    }
}
function delete_file_ftp($file) {
    $conn_id = ftp_connect(CACHE_FTP_HOST, CACHE_FTP_PORT) or die("Couldn't connect to ");
    $login_result = ftp_login($conn_id, CACHE_FTP_USER, CACHE_FTP_PASS);
    if (ftp_delete($conn_id, URL_IMAGE . $file)) {
        return "true";
    } else {
        return "false";
    }
}
function paser_image2($content) {
    $output = preg_match_all('/<img(.*?)src=[\'"]([^\'"]+)[\'"](.*?)>/i', $content, $matches);
    $first_img = $matches[2][0];
    return $first_img;
}
function paser_image($content) {
    $output = preg_match_all('/<img(.*?)src=[\'"]([^\'"]+)[\'"](.*?)>/i', $content, $matches);
    if (isset($matches[2][0])) {
        $first_img = $matches[2][0];
        return $first_img;
    }
}
if (!function_exists('getCacheImage')) {
    function getCacheImageBACKUP($image_path, $width = 0, $height = 0, $type = 1) {
        $url_timthum = PATH_URL . "timthumb.php?src=" . CACHE_URL_IMAGE . $image_path . "&w={$width}&h={$height}&zc={$type}";
        return $url_timthum;
        // @$imageArray = getimagesize(URL_IMAGE.$image_path);
        //if(@fopen(URL_IMAGE.$image_path)){
        // $image_path = 'static/images/img/no-image.jpg';
        // }else{
        $image_path = URL_IMAGE . $image_path;
        // }
        $CI = & get_instance();
        if (!empty($width) && !empty($height)) {
            $uploadDir_Thumb = 'static/cache/' . $width . 'x' . $height . '/';
        } else {
            $uploadDir_Thumb = 'static/cache/thumb/';
        }
        $fileinfo = pathinfo($image_path);
        Newfolder($uploadDir_Thumb);
        $new_image_path = $uploadDir_Thumb . $fileinfo['filename'] . '.' . $fileinfo['extension'];
        if (url_exists(URL_IMAGE . $new_image_path)) {
            //if (@getimagesize(URL_FTP_HOST.$new_image_path)){
            return URL_FTP_HOST . $new_image_path;
        }
        if (copy($image_path, 'static/uploads/tmp/' . $fileinfo['basename'])) {
            $image_path = 'static/uploads/tmp/' . $fileinfo['basename'];
        }
        //The first time the image is requested
        //Or the original image is newer than our cache image
        if ((!file_exists($new_image_path)) || filemtime($new_image_path) < filemtime($image_path)) {
            $CI->load->library('image_lib');
            //The original sizes
            $original_size = getimagesize($image_path);
            $original_width = $original_size[0];
            $original_height = $original_size[1];
            $ratio = $original_width / $original_height;
            //The requested sizes
            $requested_width = $width;
            $requested_height = $height;
            //if smaller then return
            if (($requested_width > $original_width) && $requested_height == 0)
            return PATH_URL . $image_path;
            elseif (($requested_height > $original_height) && $requested_width == 0)
            return PATH_URL . $image_path;
            //Initialising
            $new_width = 0;
            $new_height = 0;
            //Calculations
            if ($requested_width > $requested_height) {
                $new_width = $requested_width;
                $new_height = $new_width / $ratio;
                if ($requested_height == 0)
                $requested_height = $new_height;
                if ($new_height < $requested_height) {
                    $new_height = $requested_height;
                    $new_width = $new_height * $ratio;
                }
            } else {
                $new_height = $requested_height;
                $new_width = $new_height * $ratio;
                if ($requested_width == 0)
                $requested_width = $new_width;
                if ($new_width < $requested_width) {
                    $new_width = $requested_width;
                    $new_height = $new_width / $ratio;
                }
            }
            $new_width = ceil($new_width);
            $new_height = ceil($new_height);
            //Resizing
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['new_image'] = $new_image_path;
            $config['maintain_ratio'] = FALSE;
            $config['height'] = $new_height;
            $config['width'] = $new_width;
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
            //Crop if both width and height are not zero
            if (($width != 0) && ($height != 0)) {
                $x_axis = floor(($new_width - $width) / 2);
                $y_axis = floor(($new_height - $height) / 2);
                //Cropping
                $config = array();
                $config['source_image'] = $new_image_path;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = $new_image_path;
                $config['width'] = $width;
                $config['height'] = $height;
                $config['x_axis'] = $x_axis;
                $config['y_axis'] = $y_axis;
                $config['quality'] = '100%';
                $CI->image_lib->initialize($config);
                $CI->image_lib->crop();
                $CI->image_lib->clear();
            }
            $conn_id = ftp_connect(CACHE_FTP_HOST, CACHE_FTP_PORT) or die("Couldn't connect to ");
            $login_result = ftp_login($conn_id, CACHE_FTP_USER, CACHE_FTP_PASS);
            $tmpFile = $new_image_path;
            //$remote_file=
            $remote_file = CACHE_ROOT . $new_image_path;
            @ftp_mkdir($conn_id, CACHE_ROOT . $uploadDir_Thumb);
            //pr(URL_FTP_HOST.$uploadDir_Thumb);die;
            if (ftp_put($conn_id, $remote_file, $tmpFile, FTP_BINARY)) {
                return URL_FTP_HOST . $new_image_path;
            }
        }
        return URL_FTP_HOST . $new_image_path;
    }
}
function get_first_image_url($content) {
    $output = preg_match_all('/<img(.*?)src=[\'"]([^\'"]+)[\'"](.*?)>/i', $content, $matches);
    $first_img = $matches[2][0];
    return $first_img;
}
function getAddressWebsite() {
    $ci = & get_instance();
    $return = $ci->config->site_url() . $ci->uri->uri_string();
    if (count($_GET) > 0) {
        $get = array();
        foreach ($_GET as $key => $val) {
            $get[] = $key . '=' . $val;
        }
        $return.= '?' . implode('&', $get);
    }
    return $return;
}
if (!function_exists('pagination_transaction')) {
    function pagination_transaction($totalRows, $pageNum = 1, $pageSize, $limit = 3) {
        settype($totalRows, "int");
        settype($pageSize, "int");
        if ($totalRows <= 0)
        return "";
        $totalPages = ceil($totalRows / $pageSize);
        if ($totalPages <= 1)
        return "";
        $currentPage = $pageNum;
        if ($currentPage <= 0 || $currentPage > $totalPages)
        $currentPage = 1;
        //From to
        $form = $currentPage - $limit;
        $to = $currentPage + $limit;
        //Tinh toan From to
        if ($form <= 0) {
            $form = 1;
            $to = $limit * 2;
        };
        if ($to > $totalPages)
        $to = $totalPages;
        //Tinh toan nut first prev next last
        $first = '';
        $prev = '';
        $next = '';
        $last = '';
        $link = '';
        //Link URL
        $linkUrl = current_url();
        $get = '';
        $querystring = '';
        if ($_GET) {
            foreach ($_GET as $k => $v) {
                if ($k != 'p')
                $querystring = $querystring . "&{$k}={$v}";
            }
            $querystring = substr($querystring, 1);
            $get.= '?' . $querystring;
        }
        $sep = (!empty($querystring)) ? '&' : '';
        $linkUrl = $linkUrl . '?' . $querystring . $sep . 'p=';
        $link = '';
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $prev = "<li><a href='javascript:void(0)' class='prev' onclick='transaction($prevPage)'>Prev</a></li>";
        }
        /*         * *Next** */
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $next = "<li><a href='javascript:void(0)' onclick='transaction($nextPage)' class='next'>Next</a></li>";
        }
        for ($i = $form; $i <= $to; $i++) {
            if ($currentPage == $i)
            $link.= "<li><strong>$i</strong></li>";
            else
            $link.= "<li><a href='javascript:void(0)' onclick='transaction($i)'>$i</a></li>";
        }
        $page = "<div class='page'><ul>" . $first . $prev . $link . $next . $last . "</ul></div>";
        return $page;
    }
}
/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = TRUE) {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        // Output
        if ($echo == TRUE) {
            echo $output;
        } else {
            return $output;
        }
    }
}
if (!function_exists('random_username')) {
    function random_username() {
        $ho = array("Nguyễn", "Trần", "Lê", "Phạm", "Hoàng", "Phan", "Vũ", "Võ", "Đặng", "Bùi", "Đỗ", "Hồ", "Ngô", "Dương", "Lý", "An", "Ánh", "Ân", "Ấu", "Bá", "Bạc", "Bạch", "Bàng", "Bành", "Bì", "Biện", "Bình", "Bồ", "Ca", "Cái", "Cam", "Cao", "Cát", "Cầm", "Cấn", "Chế", "Chiêm", "Chu", "Châu", "Chung", "Chương", "Chử", "Cổ", "Cù", "Cung", "Cự", "Dã", "Danh", "Diêm", "Đàm", "Đan", "Đào", "Đậu", "Điền", "Đinh", "Đoàn", "Đôn", "Đồng", "Đổng", "Doãn", "Dư", "Diệp", "Đàm", "Đan", "Đào", "Đậu", "Điền", "Đinh", "Đoàn", "Đôn", "Đồng", "Đổng", "Hoa", "Hồng", "Hùng", "Hứa", "Kha", "Khương", "Khâu", "Khưu", "Khoa", "Khổng", "Khu", "Khuất", "Khúc", "Kiều", "Kim", "La", "Lỳ", "Ma", "Mạc", "Mạch", "Mai", "Mai", "Mang", "Mâu", "Mộc", "Nhâm", "Nhiệm", "Ngân", "Nghiêm", "Nghị", "Ngụy", "Nhữ", "Nông", "Ong", "Phi", "Phó", "Phùng", "Phương", "Quản", "Quách", "Sơn", "Sử", "Tạ", "Tào", "Tăng", "Thạch", "Thái", "Thào", "Thân", "Thập", "Thiều", "Thịnh", "Thôi", "Tiêu", "Tiếp", "Tòng", "Tô", "Tôn", "Tông", "Tông", "Trang", "Trà", "Trác", "Triệu", "Trịnh", "Trịnh", "Trưng", "Trương", "Từ", "Ty", "Vương", "Xa", "Yên", "Trung", "Dao");
        $lot = array("Thị", "Văn", "Hồng", "Linh", "Hoàng");
        $ten = array("Nguyễn", "Trần", "Lê", "Phạm", "Hoàng", "Phan", "Vũ", "Võ", "Đặng", "Bùi", "Đỗ", "Hồ", "Ngô", "Dương", "Lý", "An", "Ánh", "Ân", "Ấu", "Bá", "Bạc", "Bạch", "Bàng", "Bành", "Bì", "Biện", "Bình", "Bồ", "Ca", "Cái", "Cam", "Cao", "Cát", "Cầm", "Cấn", "Chế", "Chiêm", "Chu", "Châu", "Chung", "Chương", "Chử", "Cổ", "Cù", "Cung", "Cự", "Dã", "Danh", "Diêm", "Đàm", "Đan", "Đào", "Đậu", "Điền", "Đinh", "Đoàn", "Đôn", "Đồng", "Đổng", "Doãn", "Dư", "Diệp", "Đàm", "Đan", "Đào", "Đậu", "Điền", "Đinh", "Đoàn", "Đôn", "Đồng", "Đổng", "Hoa", "Hồng", "Hùng", "Hứa", "Kha", "Khương", "Khâu", "Khưu", "Khoa", "Khổng", "Khu", "Khuất", "Khúc");
        $hovaten = $ho[rand(0, count($ho) - 1) ] . " " . $lot[rand(0, count($lot) - 1) ] . " " . $ten[rand(0, count($ten) - 1) ];
        $hovaten = trim(str_replace(" ", "", $hovaten) . "_" . rand(100000, 9999999));
        return strtolower(v2e($hovaten));
    }
}

if (!function_exists('storeImageToUrl')) {
    function storeImageToUrl($url='', $path='public/images'){
        Newfolder($path);
        $info                   = pathinfo($url);
        $file_name              = $info['filename'];
        if(!isset($info['extension'])) return "Error";
        $ext                    = $info['extension'];
        if(strpos($ext, "jpeg") !== FALSE) $ext = '.jpg';
        else $ext               = ".".substr($ext, 0, 3);
        $file_name              .= $ext;
        $path .= '/'.$file_name;
        if(check_exists_file($path) == false){
            @$rawImage = file_get_contents($url);
            if($rawImage){
                file_put_contents($path, $rawImage);
                return $path;
            }
            return "Error";
        }else return $path;
    }
}
if (!function_exists('getUrlContent')) {
    function getUrlContent($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode == 412) return false;
        else return ($httpcode>=200 && $httpcode<300) ? $data : false;
    }
}
if (!function_exists('recurse_copy')) {
    function recurse_copy($src,$dst) {
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
}