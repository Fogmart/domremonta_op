<?php chdir(dirname(__FILE__));chdir("../..");if(!file_exists("config.php"))chdir("../..");require_once"config.php";$Mdo=false;if(!empty($_SERVER["HTTP_HOST"]))$Mdo=true;$Mgb=DIR_CACHE."lightning/".'b';$Mpw=rand(0,10000000);file_put_contents(DIR_CACHE."lightning/"."cron_working",$Mpw,LOCK_EX);file_put_contents(DIR_CACHE."lightning/".'d',$Mpw,LOCK_EX);if($Mdo)echo"\nCRON started\n";$Mpx=0;$Mpy=0;$Mdl=0;$Mou=0;while(true){set_time_limit(120);touch(DIR_CACHE."lightning/"."cron_working");if(!file_exists($Mgb.'aq')and($Mpx++<60)){sleep(1);clearstatcache();continue;}
$Mpx=0;if(file_get_contents(DIR_CACHE."lightning/"."cron_working")!=$Mpw){if($Mdo)echo"Next instance found, exiting\n";exit;}
if(!$Mdl)$Mdl=microtime(true);$Mou++;$Mlm=Wap(HTTP_SERVER."index.php?li_op=gen",false,"Lightning CRON Job");if(strpos($Mlm,"OX")===false){if($Mpy++>5){if($Mdo)echo"\n$Mpy faults, exiting\n";exit(1);}
}else$Mpy=0;}
exit;function Wap($Mbe,$Mhm=false,$Mhn="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36"){static$Mpz;if(!$Mpz)$Mbe=str_replace("https:","http:",$Mbe);static$Mho;if(!$Mho){$Mho=curl_init($Mbe);curl_setopt($Mho,CURLOPT_URL,$Mbe);curl_setopt($Mho,CURLOPT_RETURNTRANSFER,1);curl_setopt($Mho,CURLOPT_ENCODING,"");curl_setopt($Mho,CURLOPT_USERAGENT,$Mhn);curl_setopt($Mho,CURLOPT_TIMEOUT,200);curl_setopt($Mho,CURLOPT_CONNECTTIMEOUT,200);curl_setopt($Mho,CURLOPT_SSL_VERIFYPEER,false);curl_setopt($Mho,CURLOPT_SSL_VERIFYHOST,false);if($Mhm){curl_setopt($Mho,CURLOPT_POST,1);curl_setopt($Mho,CURLOPT_POSTFIELDS,$Mhm);}}
$Mhp=curl_exec($Mho);global$Mdf;$Mdf=curl_getinfo($Mho,CURLINFO_HTTP_CODE);$Mrw=0;while($Mdf>299&&$Mdf<399){$Mpz=true;if(phpversion()<"5.3.7"||$Mrw++>3){curl_close($Mho);$Mho=false;return false;}
$Mhq=curl_getinfo($Mho,CURLINFO_REDIRECT_URL);if(!$Mhq){$Mdf=500;curl_close($Mho);$Mho=false;return false;}
curl_setopt($Mho,CURLOPT_URL,$Mhq);$Mhp=curl_exec($Mho);$Mdf=curl_getinfo($Mho,CURLINFO_HTTP_CODE);}
return$Mhp;}