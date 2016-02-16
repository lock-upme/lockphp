<?php

class GD
{
	function __construct(){
	
	}

	function Thumb($srcfile, $thumbfile, $new_width=0, $new_height=0, $rate=95, $markwords='',$markimage='') {
		$v_srcfile = $srcfile;
		$v_thumbfile = $thumbfile;
	
		if (file_exists($thumbfile)) { return $v_thumbfile; }
		if ($new_width ==0 && $new_height == 0) { return ; }
		if (!file_exists($srcfile)) { return; }
		
		list($width, $height) = getimagesize($srcfile);
		if ($new_width > $width) { $new_width = $width; }
		
		// 图像类型
		$type = exif_imagetype($srcfile);
		$support_type = array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF);
		if(!in_array($type, $support_type,true)) { return; }
		//Load image
		switch($type) {
			case IMAGETYPE_JPEG :
				$src_img = imagecreatefromjpeg($srcfile);
				break;
			case IMAGETYPE_PNG :
				$src_img = imagecreatefrompng($srcfile);
				break;
			case IMAGETYPE_GIF :
				$src_img = imagecreatefromgif($srcfile);
				break;
			default:
				return;
		}
		$w = imagesx($src_img);
		$h = imagesy($src_img);
		if ($new_width == 0 ) { $new_width = $w * ($new_height/$h); }
		if ($new_height == 0 ) { $new_height = $h * ($new_width/$w); }
		$ratio_w = 1.0 * $new_width / $w;
		$ratio_h = 1.0 * $new_height / $h;
		$ratio = 1.0;
		// 生成的图像的高宽比原来的都小，或都大 ，原则是 取大比例放大，取大比例缩小（缩小的比例就比较小了）
		if ( ($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) {
			if ($ratio_w < $ratio_h) {
				$ratio = $ratio_h ; // 情况一，宽度的比例比高度方向的小，按照高度的比例标准来裁剪或放大
			} else {
				$ratio = $ratio_w ;
			}
			// 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
			$inter_w=(int)($new_width / $ratio);
			$inter_h=(int) ($new_height / $ratio);
			$inter_img=imagecreatetruecolor($inter_w , $inter_h);
			$srcx = (int)(($w - $inter_w)/2);
			$srcy = (int)(($h - $inter_h)/2);
			imagecopy($inter_img, $src_img, 0,0,$srcx,$srcy,$inter_w,$inter_h);
			// 生成一个以最大边长度为大小的是目标图像$ratio比例的临时图像
			// 定义一个新的图像
			$new_img = imagecreatetruecolor($new_width,$new_height);
			imagecopyresampled($new_img,$inter_img,0,0,0,0,$new_width,$new_height,$inter_w,$inter_h);
		} // end if 1
		// 2 目标图像 的一个边大于原图，一个边小于原图 ，先放大平普图像，然后裁剪
		// =if( ($ratio_w < 1 && $ratio_h > 1) || ($ratio_w >1 && $ratio_h <1) )
		else{
			$ratio = $ratio_h>$ratio_w? $ratio_h : $ratio_w; //取比例大的那个值
			// 定义一个中间的大图像，该图像的高或宽和目标图像相等，然后对原图放大
			$inter_w = (int)($w * $ratio);
			$inter_h = (int)($h * $ratio);
			if($ratio_h>$ratio_w) {
				$srcx = (int)(($inter_w - $w)/2);
				$srcy = 0;
			} else {
				$srcx = 0;
				$srcy = (int)(($inter_h - $h)/2);
			}
			$inter_img = imagecreatetruecolor($inter_w , $inter_h);
			//将原图缩放比例后裁剪
			imagecopyresampled($inter_img,$src_img,0,0,$srcx,$srcy,$inter_w,$inter_h,$w,$h);
			// 定义一个新的图像
			$new_img = imagecreatetruecolor($new_width,$new_height);
			imagecopy($new_img, $inter_img, 0,0,0,0,$new_width,$new_height); 
		}// if3
		switch($type) {
			case IMAGETYPE_JPEG :
				imagejpeg($new_img, $thumbfile, $rate); // 存储图像
				break;
			case IMAGETYPE_PNG :
				imagepng($new_img,$thumbfile, $rate);
				break;
			case IMAGETYPE_GIF :
				imagegif($new_img,$thumbfile, $rate);
				break;
			default:
				break;
		}
		return $v_thumbfile; 
	}
	
	function sThumb($SrcFile,$DstFile,$dstW,$dstH,$rate=75,$markwords='',$markimage='') {
		return $this->Thumb($SrcFile,$DstFile,$dstW,$dstH,$rate,$markwords,$markimage);
	}
	
}


?>