<?php
session_start();
//调用函数生成4位数字验证码
getCode(4,60,20);

function getCode($num,$w,$h) {
	$code = "";
	//循环拼接验证码字符串，其中$num表示验证码个数
	for ($i = 0; $i < $num; $i++) {
		$code .= rand(0, 9);
	}
	
	//将生成的验证码写入session,备验证时使用
	$_SESSION["ValidateCode"] = $code;
	//定义图片头部
	header("Content-type: image/PNG");  // 告诉浏览器当前文件产生的结果以png形式进行输出
	$im = imagecreate($w, $h);          // 创建一张宽为$w,高为$h的画布
	//创建图片  定义颜色值
	$black = imagecolorallocate($im, 0, 0, 0);    //   三原色黑色
	$gray = imagecolorallocate($im, 200, 200, 200);   //   三原色灰色
	$bgcolor = imagecolorallocate($im, 255, 255, 255);  // 给画布加上背景颜色  三原色白色
	//填充背景色为灰色
	imagefill($im, 0, 0, $gray);  //填充背景色为灰色

	//画边框
	imagerectangle($im, 0, 0, $w-1, $h-1, $black);

	//随机绘制两条虚线，起干扰作用
	$style = array ($black,$black,$black,$black,$black,
			$gray,$gray,$gray,$gray,$gray
	);
	imagesetstyle($im, $style);
	$y1 = rand(0, $h);
	$y2 = rand(0, $h);
	$y3 = rand(0, $h);
	$y4 = rand(0, $h);
	imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
	imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

	// 给画布上随机生成大量黑点 ， 添加干扰元素
	for ($i = 0; $i < 80; $i++) {
		imagesetpixel($im, rand(0, $w), rand(0, $h), $black);  
	}
	// 将数字随机显示在画布上，字符的水平间距和位置都按一定波动范围随机生成
	$strx = rand(3, 8);
	for ($i = 0; $i < $num; $i++) {
		$strpos = rand(1, 6);
		imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
		$strx += rand(8, 12);
	}
	imagepng($im);//将图像以png形式输出
	imagedestroy($im);//将图像资源从内存中销毁,以节约资源	
}
?>