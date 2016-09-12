<?php
/**
 * Created by PhpStorm.
 * User: wdmzj
 * Date: 2016/9/11
 * Time: 15:58
 */
header("Content-type: text/html; charset=utf-8");

session_start();
$studentNumber = $_SESSION['studentNumber'];//学号
$studentPsd = $_SESSION['password']; //密码

//模拟登录
function login_post($url, $post) {
    $curl = curl_init($url);//初始化curl模块
    curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
    curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
    curl_exec($curl);//执行cURL
    $info = curl_getinfo($curl);
    curl_close($curl);//关闭cURL资源，并且释放系统资源
    $url = $info['url'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($ch, CURLOPT_POST, 1);//post方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//要提交的信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
    curl_exec($ch);
    curl_close($ch);

    $preg = "/\([\s\S]+\)/";
    preg_match($preg, $info['url'], $specialParams);
    return $specialParams[0];
}

//主要为了获取导航
function get_content_noredirect($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
    $rs = curl_exec($ch);
    curl_close($ch);
    preg_match_all("/href=\"xsjxpj.aspx(\?xkkh=([\s\S]+?)\&[\s\S]+?)\"/", $rs, $evaluateUrl);
    return $evaluateUrl;
}

//获取评价页面中的veiwstate标识
function get_content($url) {
    global $specialParam, $studentNumber;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $header = array();
    $header[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
    $header[] = 'Accept-Encoding:gzip, deflate, sdch';
    $header[] = 'Accept-Language:zh-CN,zh;q=0.8';
    $header[] = 'Connection:keep-alive';
    $header[] = 'Cookie:safedog-flow-item=C64313934D923E8E75467AF0AB0698C9';
    $header[] = 'Host:218.25.35.27:8080';
    $header[] = 'Referer:http://218.25.35.27:8080/'.$specialParam.'/xs_main.aspx?xh='.$studentNumber;
    $header[] = 'Upgrade-Insecure-Requests:1';
    $header[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36';
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    $res = curl_exec($curl);
    curl_close($curl);

    preg_match("/name=\"__VIEWSTATE\" value=\"([\s\S]+?)\"/", $res, $viewstate);
    return $viewstate[1];
}

//填写评价表单
function post_info($url, $postContent) {
    global $studentNumber, $specialParam;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);//是否显示头信息
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息
    curl_setopt($ch, CURLOPT_POST, 1);//post方式提交
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postContent));//要提交的信息

    $header[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
    $header[] = 'Accept-Encoding:gzip, deflate, sdch';
    $header[] = 'Accept-Language:zh-CN,zh;q=0.8';
    $header[] = 'Connection:keep-alive';
    $header[] = 'Content-Type:application/x-www-form-urlencoded';
    $header[] = 'Cookie:safedog-flow-item=C64313934D923E8E75467AF0AB0698C9';
    $header[] = 'Host:218.25.35.27:8080';
    $header[] = 'Origin:http://218.25.35.27:8080';
    $header[] = 'Referer:http://218.25.35.27:8080/'.$specialParam.'/xsjxpj.aspx?xkkh='.$postContent['pjkc'].'&xh='.$studentNumber.'&gnmkdm=N12141';
    $header[] = 'Upgrade-Insecure-Requests:1';
    $header[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_exec($ch);
    curl_close($ch);
}

//登录地址
$url = 'http://218.25.35.27:8080/default2.aspx';

//设置post的数据
$postLogin = array (
    '__VIEWSTATE' => 'dDwxODI0OTM5NjI1Ozs+ErNwwEBfve9YGjMA8xEN6zdawEw=',
    'TextBox1' => $studentNumber,
    'TextBox2' => $studentPsd,
    'RadioButtonList1' => mb_convert_encoding('学生', "gbk", "UTF-8"),
    'Button1' => '',
    'lbLanguage' => ''
);

//登录后获取跳转特别标识符
$specialParam = login_post($url, $postLogin);

//主页地址
$mainUrl = "http://218.25.35.27:8080/".$specialParam."/xs_main.aspx?xh=".$studentNumber;

//获取主页信息(导航)
$evaluateUrl = get_content_noredirect($mainUrl);

//评价数据 9D1C
$evaluate = array(
    '__EVENTTARGET' => '',
    '__EVENTARGUMENT' => '',
    '__VIEWSTATE' => '',
    //教师1
    'DataGrid1:_ctl2:JS1' => 'A',
    'DataGrid1:_ctl3:JS1'=> 'B',
    'DataGrid1:_ctl4:JS1' => 'C',
    'DataGrid1:_ctl5:JS1'=> 'D',
    'DataGrid1:_ctl6:JS1' => 'D',
    'DataGrid1:_ctl7:JS1' => 'D',
    'DataGrid1:_ctl8:JS1' => 'D',
    'DataGrid1:_ctl9:JS1' => 'D',
    'DataGrid1:_ctl10:JS1' => 'D',
    'DataGrid1:_ctl11:JS1' => 'C',
    //教师2
    'DataGrid1:_ctl2:JS2' => 'D',
    'DataGrid1:_ctl3:JS2'=> 'D',
    'DataGrid1:_ctl4:JS2' => 'D',
    'DataGrid1:_ctl5:JS2'=> 'D',
    'DataGrid1:_ctl6:JS2' => 'D',
    'DataGrid1:_ctl7:JS2' => 'D',
    'DataGrid1:_ctl8:JS2' => 'D',
    'DataGrid1:_ctl9:JS2' => 'D',
    'DataGrid1:_ctl10:JS2' => 'D',
    'DataGrid1:_ctl11:JS2' => 'C',
    //教师3
    'DataGrid1:_ctl2:JS3' => 'D',
    'DataGrid1:_ctl3:JS3'=> 'D',
    'DataGrid1:_ctl4:JS3' => 'D',
    'DataGrid1:_ctl5:JS3'=> 'D',
    'DataGrid1:_ctl6:JS3' => 'D',
    'DataGrid1:_ctl7:JS3' => 'D',
    'DataGrid1:_ctl8:JS3' => 'D',
    'DataGrid1:_ctl9:JS3' => 'D',
    'DataGrid1:_ctl10:JS3' => 'D',
    'DataGrid1:_ctl11:JS3' => 'C',
    //教师4
    'DataGrid1:_ctl2:JS4' => 'D',
    'DataGrid1:_ctl3:JS4'=> 'D',
    'DataGrid1:_ctl4:JS4' => 'D',
    'DataGrid1:_ctl5:JS4'=> 'D',
    'DataGrid1:_ctl6:JS4' => 'D',
    'DataGrid1:_ctl7:JS4' => 'D',
    'DataGrid1:_ctl8:JS4' => 'D',
    'DataGrid1:_ctl9:JS4' => 'D',
    'DataGrid1:_ctl10:JS4' => 'D',
    'DataGrid1:_ctl11:JS4' => 'C',
    //教师5
    'DataGrid1:_ctl2:JS5' => 'D',
    'DataGrid1:_ctl3:JS5'=> 'D',
    'DataGrid1:_ctl4:JS5' => 'D',
    'DataGrid1:_ctl5:JS5'=> 'D',
    'DataGrid1:_ctl6:JS5' => 'D',
    'DataGrid1:_ctl7:JS5' => 'D',
    'DataGrid1:_ctl8:JS5' => 'D',
    'DataGrid1:_ctl9:JS5' => 'D',
    'DataGrid1:_ctl10:JS5' => 'D',
    'DataGrid1:_ctl11:JS5' => 'C',
    //评价课程编码
    'pjxx' => '',
    'txt1' => '',
    'TextBox1' => '0',
    'Button1'=> '保  存',
    'Button2' => ' 提  交 '
);

for($i = 0; $i < count($evaluateUrl[1]); $i++){
    $viewState = get_content("http://218.25.35.27:8080/".$specialParam.'/xsjxpj.aspx'.$evaluateUrl[1][$i]);
    $evaluate['__VIEWSTATE'] = $viewState;
    $evaluate['pjkc'] = $evaluateUrl[2][$i];
    post_info("http://218.25.35.27:8080/".$specialParam.'/xsjxpj.aspx'.$evaluateUrl[1][$i], $evaluate);
}

echo "评价成功";


