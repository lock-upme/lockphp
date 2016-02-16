<?php

class ImageMagick
{
	function __construct()
	{
	}

	function Thumb($SrcFile,$DstFile,$dstW,$dstH,$rate=75,$markwords=null,$markimage=null)
	{
		$dstW = (int)$dstW;
		$dstH = (int)$dstH;
		$rate = (int)$rate;
		$shell= ImageMagickPath."convert $SrcFile -thumbnail $dstW"."x$dstH -quality $rate $DstFile";
		$this->Action($shell);
	}

	function Rotate($SrcFile, $Angle)
	{
		$Angle = (int)$Angle;
		$shell = ImageMagickPath.'mogrify -rotate "'.$Angle.'" '.$SrcFile;
		$this->Action($shell);
	}


	function Cut($SrcFile, $DstFile, $dstW, $dstH, $Top, $Left)
	{
		$dstW = (int)$dstW;
		$dstH = (int)$dstH;
		$Top  = (int)$Top;
		$Left = (int)$Left;
		$shell= ImageMagickPath."convert -crop {$dstW}x{$dstH}+{$Top}+{$Left} $SrcFile $DstFile";
		$this->Action($shell);
	}


	function Action($shell)
	{
		$output = shell_exec($shell);
		//$this->InputFile('./Logs/GraphicsMagick/'.date('Y-m-d').'.log', $shell."\t".$output."\n", 'a+');
	}


	function InputFile($FileName,$data,$method="w") 
	{
		$filenum  = @fopen($FileName,$method);
		flock($filenum,LOCK_EX);
		$FileData = fwrite($filenum,$data);
		fclose($filenum);
		return $FileData;
	}
}


?>