<?php

class GraphicsMagick
{
	function __construct()
	{
	}

	function Thumb($SrcFile,$DstFile,$dstW,$dstH,$rate=95,$markwords=null,$markimage=null)
	{
		$shell = '';
		if (strpos($DstFile, '.gif?') !== false) { //gif workaround
		    list($DstFile, $width, $height) = explode('?', $DstFile);
		    $dst = substr($DstFile, 0, -3). 'jpg';
		    if ($dstW >= $width || $dstH >= $height) {  //big thumb workaround
		        copy($SrcFile, $dst);
		        return;
		    }
		    $tmpfile = 'temp/'. md5($SrcFile). '.gif';	    
		    if (!is_file($tmpfile)) {
		        $shell .= ImageMagickPath. " gm convert {$SrcFile} -coalesce -dispose none $tmpfile && ";
		    }
		    $SrcFile = $tmpfile;
		    $DstFile = "-deconstruct {$tmpfile}_0 && mv {$tmpfile}_0 $dst";
		}		
		$shell .= ImageMagickPath. " gm convert {$SrcFile} +profile '*' -quality 95 -thumbnail {$dstW}x{$dstH} {$DstFile}";
		//echo $shell;
		$this->Action($shell);
	}

	function sThumb($SrcFile,$DstFile,$dstW,$dstH,$rate=95,$markwords=null,$markimage=null)
	{
		$shell = '';
		if (strpos($DstFile, '.gif?') !== false) { //gif workaround
		    list($DstFile, $width, $height) = explode('?', $DstFile);
		    $dst = substr($DstFile, 0, -3). 'jpg';
		    if ($dstW >= $width || $dstH >= $height) {  //big thumb workaround
		        copy($SrcFile, $dst);
		        return;
		    }
		    $tmpfile = 'temp/'. md5($SrcFile). '.gif';		    
		    if (!is_file($tmpfile)) {
		        $shell .= ImageMagickPath. " gm convert {$SrcFile} -coalesce -dispose none $tmpfile && ";
		    }
		    $SrcFile = $tmpfile;
		    $DstFile = "-deconstruct {$tmpfile}_0 && mv {$tmpfile}_0 $dst";
		}
		if(GM_DEBUG=='y')
			$shell .= ImageMagickPath. " gm convert {$SrcFile} +profile '*' -quality 95 -thumbnail {$dstW}x{$dstH}^ -gravity center -extent {$dstW}x{$dstH} {$DstFile}";
		else
			$shell .= ImageMagickPath. " gm convert {$SrcFile} +profile '*' -quality 95 -thumbnail '{$dstW}x{$dstH}^' -gravity center -extent {$dstW}x{$dstH} {$DstFile}";
		$this->Action($shell);
	}

	function fThumb($SrcFile,$DstFile,$dstW,$dstH,$rate=95,$markwords=null,$markimage=null)
	{
		$shell = '';
		if (strpos($DstFile, '.gif?') !== false) { //gif workaround
		    list($DstFile, $width, $height) = explode('?', $DstFile);
		    $dst = substr($DstFile, 0, -3). 'jpg';
		    if ($dstW >= $width || $dstH >= $height) {  //big thumb workaround
		        copy($SrcFile, $dst);
		        return;
		    }
		    $tmpfile = '/temp/'. md5($SrcFile). '.gif';	    
		    if (!is_file($tmpfile)) {
		        $shell .= ImageMagickPath. " gm convert {$SrcFile} -coalesce -dispose none $tmpfile && ";
		    }
		    $SrcFile = $tmpfile;
		    $DstFile = "-deconstruct {$tmpfile}_0 && mv {$tmpfile}_0 $dst";
		}	
		$chengji = $dstW*$dstH;
		$shell .= ImageMagickPath. " gm convert {$SrcFile} -thumbnail '{$chengji}@' -background gray -gravity center -extent {$dstW}x{$dstH} {$DstFile}";
		$this->Action($shell);
	}


	function Action($shell)
	{
		$this->InputFile('./Logs/GraphicsMagick/'.date('Y-m-d').'.log', "cmd:".$shell."\n", 'a+');
		$output = shell_exec($shell);

		if($output!='')
			$this->InputFile('./Logs/GraphicsMagick/'.date('Y-m-d').'.log', $output."\n\n", 'a+');
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
