<?php

class Thumb
{
	var $Thumb;

	function __construct($ActionType)
	{
		require_once "$ActionType.php";
		$this->Thumb = new $ActionType;
	}

	function Thumb($SrcFile,$DstFile,$dstW,$dstH,$rate=75,$markwords=null,$markimage=null)
	{
		$GetImageSize = GetImageSize($SrcFile);
		if($GetImageSize[0]>$dstW || $GetImageSize[1]>$dstH)
			$this->Thumb->Thumb($SrcFile,$DstFile,$dstW,$dstH,$rate,$markwords,$markimage);
		else
			@copy($SrcFile,$DstFile);
		@chmod($DstFile, 0666);
		return $return;
	}

	function sThumb($SrcFile,$DstFile,$dstW,$dstH,$rate=75,$markwords=null,$markimage=null)
	{
		$this->Thumb->sThumb($SrcFile,$DstFile,$dstW,$dstH,$rate,$markwords,$markimage);
	}

	function fThumb($SrcFile,$DstFile,$dstW,$dstH,$rate=75,$markwords=null,$markimage=null)
	{
		$this->Thumb->fThumb($SrcFile,$DstFile,$dstW,$dstH,$rate,$markwords,$markimage);
	}

	function Rotate($SrcFile, $Angle)
	{
		return $this->Thumb->Rotate($SrcFile, $Angle);
	}

	function Cut($SrcFile, $DstFile, $dstW, $dstH, $Top, $Left)
	{
		return $this->Thumb->Cut($SrcFile, $DstFile, $dstW, $dstH, $Top, $Left);
	}

}


?>