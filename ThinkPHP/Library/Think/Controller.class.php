<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * ThinkPHP 鎺у埗鍣ㄥ熀绫� 鎶借薄绫�
 */
abstract class Controller {

    /**
     * 瑙嗗浘瀹炰緥瀵硅薄
     * @var view
     * @access protected
     */    
    protected $view     =  null;

    /**
     * 鎺у埗鍣ㄥ弬鏁�
     * @var config
     * @access protected
     */      
    protected $config   =   array();

   /**
     * 鏋舵瀯鍑芥暟 鍙栧緱妯℃澘瀵硅薄瀹炰緥
     * @access public
     */
    public function __construct() {
        Hook::listen('action_begin',$this->config);
        //瀹炰緥鍖栬鍥剧被
        $this->view     = Think::instance('Think\View');
        //鎺у埗鍣ㄥ垵濮嬪寲
        if(method_exists($this,'_initialize'))
            $this->_initialize();
    }

    /**
     * 妯℃澘鏄剧ず 璋冪敤鍐呯疆鐨勬ā鏉垮紩鎿庢樉绀烘柟娉曪紝
     * @access protected
     * @param string $templateFile 鎸囧畾瑕佽皟鐢ㄧ殑妯℃澘鏂囦欢
     * 榛樿涓虹┖ 鐢辩郴缁熻嚜鍔ㄥ畾浣嶆ā鏉挎枃浠�
     * @param string $charset 杈撳嚭缂栫爜
     * @param string $contentType 杈撳嚭绫诲瀷
     * @param string $content 杈撳嚭鍐呭
     * @param string $prefix 妯℃澘缂撳瓨鍓嶇紑
     * @return void
     */
    protected function display($templateFile='',$charset='',$contentType='',$content='',$prefix='') {
        $this->view->display($templateFile,$charset,$contentType,$content,$prefix);
    }

    /**
     * 杈撳嚭鍐呭鏂囨湰鍙互鍖呮嫭Html 骞舵敮鎸佸唴瀹硅В鏋�
     * @access protected
     * @param string $content 杈撳嚭鍐呭
     * @param string $charset 妯℃澘杈撳嚭瀛楃闆�
     * @param string $contentType 杈撳嚭绫诲瀷
     * @param string $prefix 妯℃澘缂撳瓨鍓嶇紑
     * @return mixed
     */
    protected function show($content,$charset='',$contentType='',$prefix='') {
        $this->view->display('',$charset,$contentType,$content,$prefix);
    }

    /**
     *  鑾峰彇杈撳嚭椤甸潰鍐呭
     * 璋冪敤鍐呯疆鐨勬ā鏉垮紩鎿巉etch鏂规硶锛�
     * @access protected
     * @param string $templateFile 鎸囧畾瑕佽皟鐢ㄧ殑妯℃澘鏂囦欢
     * 榛樿涓虹┖ 鐢辩郴缁熻嚜鍔ㄥ畾浣嶆ā鏉挎枃浠�
     * @param string $content 妯℃澘杈撳嚭鍐呭
     * @param string $prefix 妯℃澘缂撳瓨鍓嶇紑* 
     * @return string
     */
    protected function fetch($templateFile='',$content='',$prefix='') {
        return $this->view->fetch($templateFile,$content,$prefix);
    }

    /**
     *  鍒涘缓闈欐�侀〉闈�
     * @access protected
     * @htmlfile 鐢熸垚鐨勯潤鎬佹枃浠跺悕绉�
     * @htmlpath 鐢熸垚鐨勯潤鎬佹枃浠惰矾寰�
     * @param string $templateFile 鎸囧畾瑕佽皟鐢ㄧ殑妯℃澘鏂囦欢
     * 榛樿涓虹┖ 鐢辩郴缁熻嚜鍔ㄥ畾浣嶆ā鏉挎枃浠�
     * @return string
     */
    protected function buildHtml($htmlfile='',$htmlpath='',$templateFile='') {
        $content    =   $this->fetch($templateFile);
        $htmlpath   =   !empty($htmlpath)?$htmlpath:HTML_PATH;
        $htmlfile   =   $htmlpath.$htmlfile.C('HTML_FILE_SUFFIX');
        Storage::put($htmlfile,$content,'html');
        return $content;
    }
    /**
     * 妯℃澘涓婚璁剧疆
     * @access protected
     * @param string $theme 妯＄増涓婚
     * @return Action
     */
    protected function theme($theme){
        $this->view->theme($theme);
        return $this;
    }

    /**
     * 妯℃澘鍙橀噺璧嬪��
     * @access protected
     * @param mixed $name 瑕佹樉绀虹殑妯℃澘鍙橀噺
     * @param mixed $value 鍙橀噺鐨勫��
     * @return Action
     */
    protected function assign($name,$value='') {
        $this->view->assign($name,$value);
        return $this;
    }

    public function __set($name,$value) {
        $this->assign($name,$value);
    }

    /**
     * 鍙栧緱妯℃澘鏄剧ず鍙橀噺鐨勫��
     * @access protected
     * @param string $name 妯℃澘鏄剧ず鍙橀噺
     * @return mixed
     */
    public function get($name='') {
        return $this->view->get($name);      
    }

    public function __get($name) {
        return $this->get($name);
    }

    /**
     * 妫�娴嬫ā鏉垮彉閲忕殑鍊�
     * @access public
     * @param string $name 鍚嶇О
     * @return boolean
     */
    public function __isset($name) {
        return $this->get($name);
    }

    /**
     * 榄旀湳鏂规硶 鏈変笉瀛樺湪鐨勬搷浣滅殑鏃跺�欐墽琛�
     * @access public
     * @param string $method 鏂规硶鍚�
     * @param array $args 鍙傛暟
     * @return mixed
     */
    public function __call($method,$args) {
        if( 0 === strcasecmp($method,ACTION_NAME.C('ACTION_SUFFIX'))) {
            if(method_exists($this,'_empty')) {
                // 濡傛灉瀹氫箟浜哶empty鎿嶄綔 鍒欒皟鐢�
                $this->_empty($method,$args);
            }elseif(file_exists_case($this->view->parseTemplate())){
                // 妫�鏌ユ槸鍚﹀瓨鍦ㄩ粯璁ゆā鐗� 濡傛灉鏈夌洿鎺ヨ緭鍑烘ā鐗�
                $this->display();
            }else{
                E(L('_ERROR_ACTION_').':'.ACTION_NAME);
            }
        }else{
            E(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }

    /**
     * 鎿嶄綔閿欒璺宠浆鐨勫揩鎹锋柟娉�
     * @access protected
     * @param string $message 閿欒淇℃伅
     * @param string $jumpUrl 椤甸潰璺宠浆鍦板潃
     * @param mixed $ajax 鏄惁涓篈jax鏂瑰紡 褰撴暟瀛楁椂鎸囧畾璺宠浆鏃堕棿
     * @return void
     */
    protected function error($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,0,$jumpUrl,$ajax);
    }

    /**
     * 鎿嶄綔鎴愬姛璺宠浆鐨勫揩鎹锋柟娉�
     * @access protected
     * @param string $message 鎻愮ず淇℃伅
     * @param string $jumpUrl 椤甸潰璺宠浆鍦板潃
     * @param mixed $ajax 鏄惁涓篈jax鏂瑰紡 褰撴暟瀛楁椂鎸囧畾璺宠浆鏃堕棿
     * @return void
     */
    protected function success($message='',$jumpUrl='',$ajax=false) {
        $this->dispatchJump($message,1,$jumpUrl,$ajax);
    }

    /**
     * Ajax鏂瑰紡杩斿洖鏁版嵁鍒板鎴风
     * @access protected
     * @param mixed $data 瑕佽繑鍥炵殑鏁版嵁
     * @param String $type AJAX杩斿洖鏁版嵁鏍煎紡
     * @param int $json_option 浼犻�掔粰json_encode鐨刼ption鍙傛暟
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0) {
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        switch (strtoupper($type)){
            case 'JSON' :
                // 杩斿洖JSON鏁版嵁鏍煎紡鍒板鎴风 鍖呭惈鐘舵�佷俊鎭�
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data,$json_option));
            case 'XML'  :
                // 杩斿洖xml鏍煎紡鏁版嵁
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 杩斿洖JSON鏁版嵁鏍煎紡鍒板鎴风 鍖呭惈鐘舵�佷俊鎭�
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler.'('.json_encode($data,$json_option).');');  
            case 'EVAL' :
                // 杩斿洖鍙墽琛岀殑js鑴氭湰
                header('Content-Type:text/html; charset=utf-8');
                exit($data);            
            default     :
                // 鐢ㄤ簬鎵╁睍鍏朵粬杩斿洖鏍煎紡鏁版嵁
                Hook::listen('ajax_return',$data);
        }
    }

    /**
     * Action璺宠浆(URL閲嶅畾鍚戯級 鏀寔鎸囧畾妯″潡鍜屽欢鏃惰烦杞�
     * @access protected
     * @param string $url 璺宠浆鐨刄RL琛ㄨ揪寮�
     * @param array $params 鍏跺畠URL鍙傛暟
     * @param integer $delay 寤舵椂璺宠浆鐨勬椂闂� 鍗曚綅涓虹
     * @param string $msg 璺宠浆鎻愮ず淇℃伅
     * @return void
     */
    protected function redirect($url,$params=array(),$delay=0,$msg='') {
        $url    =   U($url,$params);
        redirect($url,$delay,$msg);
    }

    /**
     * 榛樿璺宠浆鎿嶄綔 鏀寔閿欒瀵煎悜鍜屾纭烦杞�
     * 璋冪敤妯℃澘鏄剧ず 榛樿涓簆ublic鐩綍涓嬮潰鐨剆uccess椤甸潰
     * 鎻愮ず椤甸潰涓哄彲閰嶇疆 鏀寔妯℃澘鏍囩
     * @param string $message 鎻愮ず淇℃伅
     * @param Boolean $status 鐘舵��
     * @param string $jumpUrl 椤甸潰璺宠浆鍦板潃
     * @param mixed $ajax 鏄惁涓篈jax鏂瑰紡 褰撴暟瀛楁椂鎸囧畾璺宠浆鏃堕棿
     * @access private
     * @return void
     */
    private function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false) {
        if(true === $ajax || IS_AJAX) {// AJAX鎻愪氦
            $data           =   is_array($ajax)?$ajax:array();
            $data['info']   =   $message;
            $data['status'] =   $status;
            $data['url']    =   $jumpUrl;
            $this->ajaxReturn($data);
        }
        if(is_int($ajax)) $this->assign('waitSecond',$ajax);
        if(!empty($jumpUrl)) $this->assign('jumpUrl',$jumpUrl);
        // 鎻愮ず鏍囬
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        //濡傛灉璁剧疆浜嗗叧闂獥鍙ｏ紝鍒欐彁绀哄畬姣曞悗鑷姩鍏抽棴绐楀彛
        if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
        $this->assign('status',$status);   // 鐘舵��
        //淇濊瘉杈撳嚭涓嶅彈闈欐�佺紦瀛樺奖鍝�
        C('HTML_CACHE_ON',false);
        if($status) { //鍙戦�佹垚鍔熶俊鎭�
            $this->assign('message',$message);// 鎻愮ず淇℃伅
            // 鎴愬姛鎿嶄綔鍚庨粯璁ゅ仠鐣�1绉�
            if(!isset($this->waitSecond))    $this->assign('waitSecond','1');
            // 榛樿鎿嶄綔鎴愬姛鑷姩杩斿洖鎿嶄綔鍓嶉〉闈�
            if(!isset($this->jumpUrl)) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
            $this->display(C('TMPL_ACTION_SUCCESS'));
        }else{
            $this->assign('error',$message);// 鎻愮ず淇℃伅
            //鍙戠敓閿欒鏃跺�欓粯璁ゅ仠鐣�3绉�
            if(!isset($this->waitSecond))    $this->assign('waitSecond','3');
            // 榛樿鍙戠敓閿欒鐨勮瘽鑷姩杩斿洖涓婇〉
            if(!isset($this->jumpUrl)) $this->assign('jumpUrl',"javascript:history.back(-1);");
            $this->display(C('TMPL_ACTION_ERROR'));
            // 涓鎵ц  閬垮厤鍑洪敊鍚庣户缁墽琛�
            exit ;
        }
    }

   /**
     * 鏋愭瀯鏂规硶
     * @access public
     */
    public function __destruct() {
        // 鎵ц鍚庣画鎿嶄綔
        Hook::listen('action_end');
    }
}
// 璁剧疆鎺у埗鍣ㄥ埆鍚� 渚夸簬鍗囩骇
class_alias('Think\Controller','Think\Action');
