<?php
namespace kufeisoft;

use kufeisoft\Tree;

class Form{
	public  static $data = array() , $isadmin=1, $doThumb=1, $doAttach=1;
	/*分类列表*/
	public  static function catid($info,$value, $category=[]){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$validate 	= 	self::getvalidate($info['setup']);
		$id 		= 	$field 	= 	$info['name'];
		$value 		= 	$value  ?   $value : '';
		$moduleid 	=	$info['moduleid'];
		$remark 	=	isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$readonly 	= 	isset($info['setup']['readonly']) ? 'readonly' : '';
		$disabled 	= 	isset($info['setup']['disabled']) ? 'disabled' : '';
		$class 		=	isset($info['setup']['class']) ? trim($info['setup']['class']) : 'layui-input';
		foreach ($Category as $r){
			$arr 	= 	explode(",", $r['arrchildid']);
			$show	=	0;
			foreach((array)$arr as $rr){
				if($Category[$rr]['moduleid'] == $moduleid) 	$show=1;
			}
			if(empty($show))	continue;
			$r['disabled'] = $r['child'] ? ' disabled' :'';
			$array[] = $r;
		}
		$str  		= 	"<option value='\$id' \$disabled \$selected>\$spacer \$catname</option>";
		$tree 		= 	new Tree($array);
		$tree->icon 	= 	['│','├','└'];
		$parseStr 	= 	'<select  id="'.$id.'" name="'.$field.'" class="' . $class . '" '. $validate . ' '. $readonly . ' ' . $disabled  . '>';
		$parseStr 	.= 	'<option value="">请选择对应栏目</option>';
		$parseStr 	.= 	$tree->get_tree(0, $str, $value);
		$parseStr 	.= 	'</select>';
		return $parseStr;
	}
	/*标题先这么用，没想好怎么写*/
	public  static function title($info,$value){
		return self::text($info,$value);
	}
	/* 文本框 */
	public  static function text($info,$value){
		$info['setup'] 	= 	is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field 	= 	$info['name'];
		$validate 		= 	self::getvalidate($info['setup']);
		$placeholder 	= 	isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  		= 	isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$readonly 		= 	isset($info['setup']['readonly']) ? 'readonly' : '';
		$disabled 		= 	isset($info['setup']['disabled']) ? 'disabled' : '';
		$title 			=	isset($info['setup']['title']) ? $info['setup']['title'] : '';
		$parseStr   		= 	' <input type="text" name="' . $field . '" placeholder="' . $placeholder . '" value="' . $value . '" class="layui-input" title="' . $title . '"' . $validate . ' '. $readonly . ' ' . $disabled .'><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}
	/* 密码框 */
	public  static function password($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$parseStr   = ' <input type="password" name="' . $field . '" placeholder="' . $placeholder  . '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . '><p class="help-block">' . $remark .'</p>';
		return $parseStr;
	}
	/* 坐标框 */
	public  static function point($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'], true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$parseStr   = ' <input type="text" name="' . $field . '" data-map="point" data-name="' . $field . '" data-title="坐标选择" placeholder="' . $placeholder. '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . '><p class="help-block">' . $remark . '</p>';
		return $parseStr;
	}
	/*文本域*/
	public  static function textarea($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$rows = isset($info['setup']['rows']) ? intval($info['setup']['rows']) : 10;
		$cols = isset($info['setup']['rows']) ? intval($info['setup']['cols']) : 185;
		$placeholder = isset($info['setup']['default']) ? trim($info['setup']['default']) : '';
		$remark =  isset($info['setup']['remark']) ? trim($info['setup']['remark']) : '';
		$parseStr   = '<textarea style="height:80px;resize:true;line-height:20px" class="layui-input" placeholder="' . $placeholder . '" name="'.$field.'"  rows="'. $rows .'" cols="' . $cols . '"  id="'.$id.'"   '.$validate.'>'.stripcslashes($value).'</textarea><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}
	/*单选按钮实现*/
	public  static function radio($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		if(!isset($info['setup']['option']) || empty(trim($info['setup']['option']))){
			return false;
		}
		$options  = explode('::' , trim($info['setup']['option']));
		$parseStr = '<div class="layui-input-block"  style="margin-left:0">';
		foreach($options as $k=>$v){
			$opts = explode('|', $v);
			if($value !== false && $value !== null && $value !== ''){
				$checked = $opts[0] == $value ? 'checked' : '';
			}else{
				$checked = $opts[0] == $info['setup']['default'] ? 'checked' : '';
			}

			if(!empty($v)){
				$parseStr .= '<input type="radio" name="' . $field . '" value="' . $opts[0] . '" ' . $checked . ' title="' . $opts[1] . '"/>';
			}

		}
		$parseStr .= '</div>';
		$remark = isset($info['setup']['remark']) ? trim($info['setup']['remark']) : '';
		$parseStr .= '<p class="help-block">' . $remark . '</p>';
		return $parseStr;
	}
	/*多选框实现*/
	public  static function checkbox($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		if(!isset($info['setup']['option']) || empty(trim($info['setup']['option']))){
			return false;
		}
		$options  = explode('::' , trim($info['setup']['option']));
		$parseStr = '<div class="layui-input-block" style="margin-left:0">';
		foreach($options as $option){
			//真正的内容
			$opts = explode('|', $option);
			if($value != '') $value = (!is_array($value) && strpos($value, ',')) ? explode(',', $value) :  $value ;
			$value = is_array($value) ? $value : array($value);
			$checked = ($value && in_array($opts[0], $value)) ? 'checked' : (in_array($opts[0], array(explode(',', $info['setup']['default']))) ? 'checked' : '');
			//$checked = isset($value) ? ($opts[0] == $value ? 'checked' : '') : ($opts[0] == $info['setup']['default'] ? 'checked' : '');
			$parseStr .= '<input type="checkbox" lay-skin="primary" id="' . $id . '_' . $i . '" name="'.$field.'[]" value="' . $opts[0] . '" ' . $checked . ' ' . $validate . ' title="' . $opts[1] . '"/>';
		}
		$parseStr .= '</div>';
		return $parseStr;
	}
	/*下拉列表实现*/
	public  static function select($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		if(!isset($info['setup']['option']) || empty(trim($info['setup']['option']))){
			return false;
		}
		$options  = explode('::' , trim($info['setup']['option']));
		$parseStr = '<select name="' . $field . '" class="layui-input">';
		foreach($options as $option){
			//真正的内容
			$opts = explode('|', $option);
			$selected = isset($value) ? ($opts[0] == $value ? 'selected' : '') : ($opts[0] == $info['setup']['default'] ? 'selected' : '');
			$parseStr .= '<option value="' . $opts[0] . '" ' . $selected . '>' .$opts[1] . '</option>' . "\n\r";
		}
		$remark = isset($info['setup']['remark']) ? trim($info['setup']['remark']) : '';
		$parseStr .= '</select><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}

	/*图像框*/
	public  static function image($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = 	isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$model 	=	isset($info['setup']['type']) ? isset($info['setup']['type']) : 'one';
		$exts 	=	isset($info['setup']['exts']) ? isset($info['setup']['exts']) : 'jpg,png,gif,jpeg';
		//$parseStr   = ' <input type="text" name="' . $field . '" placeholder="' . $placeholder . '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . ' readonly><p class="help-block"><a class="btn btn-link" data-file="'. $model .'" data-type="' . $exts . '" data-field="' . $field . '">上传</a>|<a href="javascript:void(0)" class="btn btn-link">预览</a>' . (empty($remark) ?  ' ': '　|　' . $remark) .'</p>';
		//$parseStr = '<a class="btn btn-link" data-file="'. $model .'" data-type="' . $exts . '" data-field="' . $field . '"><img data-tips-image id="img' . $field . '" style="height:auto;max-height:60px;min-width:60px;min-height: 60px;border:#ccc solid 1px" src="' . stripcslashes($value) . '"/></a><input type="hidden" name="' . $field . '" value="' . stripcslashes($value) . '" class="layui-input litup"  onchange="document.getElementById(\'img'. $field .'\').src=this.value;">';
		//$parseStr   = ' <input type="text" name="' . $field . '" placeholder="' . $placeholder . '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . '><p class="help-block">'. $remark .'</p>';
		$parseStr 	=	'<div class="input-file-show"><span class="show"><a href="javascript:void(0)" onclick="layer.open({type: 1,shade: 0.3,title: false, content: \'<div><img src=\' + $(\'#' . $field . '\').val() + \' style=\\\'width:100%;height:auto\\\'></div>\'});" title="点击查看预览图"><i id="img_i" class="fa fa-picture-o"></i></a></span><span class="type-file-box"><input type="text" id="' . $field . '" name="' . $field . '" value="' . stripcslashes($value) . '" class="type-file-text"><input type="button" name="button" id="button1" value="选择上传..." class="type-file-button"><input class="type-file-file" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效" data-file="' . $model . '" data-type="' . $exts . '" data-field="' . $field . '"></span></div><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}

	/*视频框*/
	public  static function video($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = 	isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$model 	=	isset($info['setup']['type']) ? isset($info['setup']['type']) : 'one';
		$exts 	=	isset($info['setup']['exts']) ? isset($info['setup']['exts']) : 'mp4';
		//$parseStr   = ' <input type="text" name="' . $field . '" placeholder="' . $placeholder . '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . ' readonly><p class="help-block"><a class="btn btn-link" data-file="'. $model .'" data-type="' . $exts . '" data-field="' . $field . '">上传</a>|<a href="javascript:void(0)" class="btn btn-link">预览</a>' . (empty($remark) ?  ' ': '　|　' . $remark) .'</p>';
		//$parseStr = '<a class="btn btn-link" data-file="'. $model .'" data-type="' . $exts . '" data-field="' . $field . '"><img data-tips-image id="img' . $field . '" style="height:auto;max-height:60px;min-width:60px;min-height: 60px;border:#ccc solid 1px" src="' . stripcslashes($value) . '"/></a><input type="hidden" name="' . $field . '" value="' . stripcslashes($value) . '" class="layui-input litup"  onchange="document.getElementById(\'img'. $field .'\').src=this.value;">';
		//$parseStr   = ' <input type="text" name="' . $field . '" placeholder="' . $placeholder . '" value="' . stripcslashes($value) . '" class="layui-input" ' . $validate . '><p class="help-block">'. $remark .'</p>';
		$parseStr 	=	'<div class="input-file-show"><span class="show"><a href="javascript:void(0)" onclick="layer.open({type: 1,shade: 0.3,title: false, area: [\'450px\', \'320px\'], content: \'<div style=\\\'width:450px;overflow:hidden;height:auto\\\'><video controls width=\\\'450\\\' height=\\\'320\\\'><source src=\' + $(\'#' . $field . '\').val() + \' style=\\\'width:450px;height:auto\\\' type=\\\'video/mp4\\\'></video></div>\'});" title="点击查看预览图"><i id="img_i" class="fa fa-picture-o"></i></a></span><span class="type-file-box"><input type="text" id="' . $field . '" name="' . $field . '" value="' . stripcslashes($value) . '" class="type-file-text"><input type="button" name="button" id="button1" value="选择上传..." class="type-file-button"><input class="type-file-file" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效" data-file="' . $model . '" data-type="' . $exts . '" data-field="' . $field . '"></span></div><p class="help-block">'. $remark .'</p><style>.layui-layer-page .layui-layer-content{overflow:hidden;!important}</style>';
		return $parseStr;
	}
	/*富文本框*/
	public static function editor($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$parseStr   = ' <textarea id="' . $field . '" name="' . $field . '" placeholder="' . $placeholder . '" class="layui-input layui-editor editor" ' . $validate . '>' . stripcslashes($value) . '</textarea><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}
	/*时间日期*/
	public static function datetime($info,$value){
		//<input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input" lay-key="1">
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark  = isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$readonly = isset($info['setup']['readonly']) ? 'readonly' : '';
		$disabled = isset($info['setup']['disabled']) ? 'disabled' : '';
		$parseStr   = ' <input type="text" name="' . $field . '" id="' . $field . '" placeholder="' . (empty($placeholder) ? date('Y-m-d H:i:s') : $placeholder) . '" value="' . (empty($value) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $value)) . '" class="layui-input" ' . $validate . ' '. $readonly . ' ' . $disabled .' lay-verify="date" autocomplete="off"><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}
	/* 验证实现 */
	public  static function getvalidate($info){
		$validate_data = array();
		$required =  $errormsg = '';
		if(isset($info['required'])){
			$required  = ' required = "' . $info['required'] . '" ';
		}
		if(isset($info['errormsg'])){
			$errormsg = ' errmsg="'.$info['errormsg'].'"';
		}
		$info = self::_clearset($info,['required','errormsg', 'default', 'option', 'remark', 'readonly', 'disabled','title','placeholder']);
		if(is_array($info)){
			foreach ($info as $k => $v) {
				$validate_data[] = ' ' . $k . ':' . $v;
			}
		}
		$validate = ltrim(implode(',',$validate_data), ' ');
		$validate = $validate ? 'validate="'.$validate.'" ' : '';
		return $required . $validate . $errormsg;
	}
	/*推荐位*/
	 public static function position($info, $value, $posids=[]){
	 	foreach($posids as $id => $title) {
      $options[] 	=	$id . '|' . $title;
		}
		$info['setup']	=	['option' 	=>	implode('::', $options)];
		return self::checkbox($info, $value);
    }
    /* 邮件测试*/
	public static function mailtest($info,$value){
		$info['setup'] = is_array($info['setup']) ? $info['setup'] : json_decode($info['setup'],true);
		$id = $field = $info['name'];
		$validate = self::getvalidate($info['setup']);
		$placeholder = isset($info['setup']['default']) && $info['setup']['default'] ? trim($info['setup']['default']) : '';
		$remark   = isset($info['setup']['remark']) ? $info['setup']['remark'] : '';
		$parseStr = '<div class="input-file-show input-mail-send" ><span class="show"></span><span class="type-file-box"><input type="text" id="' . $field . '" name="' . $field . '" class="type-file-text"><input type="button" name="button" id="testmail" value="点击发送" data-testmail class="type-file-button"></span></div><p class="help-block">'. $remark .'</p>';
		return $parseStr;
	}
	
	//删除数组不要的键
	private static function _clearset($array,$keys){
		foreach ($keys as $v) {
			if(isset($array[$v])){
				unset($array[$v]);
			}
		}
		return $array;
	}
}
?>
