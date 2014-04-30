<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


/***************************************************************************************
 *                                                                                     *
 * This file is part of the XPertMailer package (http://xpertmailer.sourceforge.net/)  *
 *                                                                                     *
 * XPertMailer is free software; you can redistribute it and/or modify it under the    *
 * terms of the GNU General Public License as published by the Free Software           *
 * Foundation; either version 2 of the License, or (at your option) any later version. *
 *                                                                                     *
 * XPertMailer is distributed in the hope that it will be useful, but WITHOUT ANY      *
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A     *
 * PARTICULAR PURPOSE.  See the GNU General Public License for more details.           *
 *                                                                                     *
 * You should have received a copy of the GNU General Public License along with        *
 * XPertMailer; if not, write to the Free Software Foundation, Inc., 51 Franklin St,   *
 * Fifth Floor, Boston, MA  02110-1301  USA                                            *
 *                                                                                     *
 * XPertMailer SMTP & POP3 PHP Mail Client. Can send and read messages in MIME Format. *
 * Copyright (C) 2006  Tanase Laurentiu Iulian                                         *
 *                                                                                     *
 ***************************************************************************************/

class FUNC {

	function is_win(){
		return (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
	}

	function str_clear($strval, $addrep = array()){
		$ret = '';
		$rep = array("\r", "\n", "\t");
		if(is_array($addrep) && count($addrep) > 0){
			foreach($addrep as $strrep){
				if(is_string($strrep) && $strrep != "") $rep[] = $strrep;
				else throw new Exception('Invalid array component value, on class FUNC::str_clear()', 512);
			}
		}
		if(is_string($strval)) $ret = ($strval == "") ? '' : str_replace($rep, '', $strval);
		else throw new Exception('Invalid parameter type value, on class FUNC::str_clear()', 512);
		return $ret;
	}

	function is_alpha($strval, $numeric = true, $addstr = ''){
		if(is_string($strval) && $strval != ""){
			$lists = "abcdefghijklmnoqprstuvwxyzABCDEFGHIJKLMNOQPRSTUVWXYZ";
			if(is_bool($numeric)){
				if($numeric) $lists .= "1234567890";
			}else throw new Exception('Invalid 2\'nd parameter type value, on class FUNC::is_alpha()', 512);
			if(is_string($addstr)) $lists .= $addstr;
			else throw new Exception('Invalid 3\'rd parameter type value, on class FUNC::is_alpha()', 512);
			$match = true;
			$len1 = strlen($strval);
			$len2 = strlen($lists);
			for($i = 0; $i < $len1; $i++){
				$found = false;
				for($j = 0; $j < $len2; $j++){
					if($lists{$j} == $strval{$i}){
						$found = true;
						break;
					}
				}
				if(!$found){
					$match = false;
					break;
				}
			}
			return $match;
		}else{
			throw new Exception('Invalid 1\'st parameter type value, on class FUNC::is_alpha()', 512);
			return false;
		}
	}

	function is_hostname($strhost){
		$ret = false;
		if(is_string($strhost) && $strhost != ""){
			if(FUNC::is_alpha($strhost, true, "-.")){
				$exphost1 = explode('.', $strhost);
				$exphost2 = explode('-', $strhost);
				if(count($exphost1) > 1 && !(strstr($strhost, '.-') || strstr($strhost, '-.'))){
					$set1 = $set2 = true;
					foreach($exphost1 as $expstr1){
						if($expstr1 == ""){
							$set1 = false;
							break;
						}
					}
					foreach($exphost2 as $expstr2){
						if($expstr2 == ""){
							$set2 = false;
							break;
						}
					}
					$ext = $exphost1[count($exphost1)-1];
					$len = strlen($ext);
					if($set1 && $set2 && $len > 1 && $len < 7 && FUNC::is_alpha($ext, false)) $ret = true;
				}
			}
		}
		return $ret;
	}

	function getmxrr_win($hostname, &$mxhosts){
		$mxhosts = array();
		if(is_string($hostname) && $hostname != ""){
			if(FUNC::is_hostname($hostname)){
				$hostname = strtolower($hostname);
				$retstr = exec('nslookup -type=mx '.$hostname, $retarr);
				if($retstr && count($retarr) > 0){
					foreach($retarr as $line){
						if(preg_match('/.*mail exchanger = (.*)/', $line, $matches)) $mxhosts[] = $matches[1];
					}
				}
			}else throw new Exception('Invalid parameter format, on class FUNC::getmxrr_win()', 512);
		}else throw new Exception('Invalid parameter type value, on class FUNC::getmxrr_win()', 512);
		return (count($mxhosts) > 0);
	}

	function is_ipv4($ipval){
		$ret = false;
		if(is_string($ipval) && $ipval != ""){
			$expips = explode('.', $ipval);
			if(count($expips) == 4){
				$each = true;
				foreach($expips as $number){
					$partno = intval($number);
					if(!($number === strval($partno) && $partno >= 0 && $partno <= 255)){
						$each = false;
						break;
					}
				}
				$ret = $each;
			}
		}else throw new Exception('Invalid parameter type value, on class FUNC::is_ipv4()', 512);
		return $ret;
	}

	function is_connection($connection){
		$ret = false;
		if($connection && is_resource($connection)){
			$status = stream_get_meta_data($connection);
			if(!$status['timed_out']) $ret = true;
		}
		return $ret;
	}

	function close($connection){
		$ret = false;
		if(FUNC::is_connection($connection)) $ret = fclose($connection);
		return $ret;
	}

	function is_mail($addr, $vermx = false){
		$ret = false;
		if(is_string($addr) && $addr != ""){
			$regs = '^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$';
			if(eregi($regs, $addr)){
				if(is_bool($vermx)){
					if($vermx){
						$exp = explode('@', $addr);
						$ret = FUNC::is_win() ? FUNC::getmxrr_win($exp[1], $mxh) : getmxrr($exp[1], $mxh);
					}else $ret = true;
				}else throw new Exception('Invalid secound parameter type value, on class FUNC::is_mail()', 512);
			}
		}else throw new Exception('Invalid first parameter type value, on class FUNC::is_mail()', 512);
		return $ret;
	}

	function delwspace($str){
		if(is_string($str)){
			if(strstr($str, '  ')){
				$str = str_replace('  ', ' ', $str);
				return FUNC::delwspace($str);
			}
		}else throw new Exception('Invalid parameter type value, on class FUNC::delwspace()', 512);
		return $str;
	}

	function split_msg($msg){
		$ret = false;
		if(is_string($msg) && $msg != ""){
			$sep = "\n\n";
			$arr['header'] = $arr['body'] = array();
			$exp1 = explode($sep, $msg);
			if(!(count($exp1) > 1)){
				$sep = "\r\n\r\n";
				$exp1 = explode($sep, $msg);
			}
			if(count($exp1) > 1){
				$multipart = false;
				$head = str_replace(array("\r\n\t", "\r\n "), " ", $exp1[0]);
				$exp2 = explode("\r\n", $head);
				if(count($exp2) > 1){
					foreach($exp2 as $hval){
						$exp3 = explode(': ', $hval);
						$name = trim($exp3[0]);
						if(count($exp3) > 1 && $name != "" && !strstr($name, ' ')){
							$sval = strstr($hval, ': ');
							$sval = substr($sval, 2);
							$sval = FUNC::str_clear($sval);
							$sval = trim(FUNC::delwspace($sval));
							$arr['header'][] = array($name => $sval);
							$hnm = strtolower($name);
							if($hnm == "content-type"){
								if(strstr($sval, 'multipart/') &&  strstr($sval, '; boundary=')){
									$bex1 = explode('; boundary=', $sval);
									if(count($bex1) > 1){
										$data1 = trim($bex1[1]);
										if($data1 != ""){
											$bex2 = explode('; ', $data1);
											$boundary = str_replace('"', '', $bex2[0]);
											$boundary = trim($boundary);
											if($boundary != ""){
												$mex1 = explode('multipart/', $sval);
												if(count($mex1) > 1){
													$data2 = trim($mex1[1]);
													if($data2 != ""){
														$mex2 = explode('; ', $data2);
														$mtype = trim(strtolower($mex2[0]));
														if($mtype == "mixed" || $mtype == "related" || $mtype == "alternative") $multipart = $mtype;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
				if(count($arr['header']) > 0){
					$body = strstr($msg, $sep);
					$body = substr($body, strlen($sep));
					if($multipart){
						$arr['multipart'] = $multipart;
						$arr['boundary']  = $boundary;
					}
					$arr['body'] = $body;
					$ret = $arr;
				}else throw new Exception('Invalid 3 message value, on class FUNC::split_msg()', 512);
			}else throw new Exception('Invalid 2 message value, on class FUNC::split_msg()', 512);
		}else throw new Exception('Invalid 1 message value, on class FUNC::split_msg()', 512);
		return $ret;
	}

	function split_reverse($body, $multipart, $boundary){
		$ret = array();
		if(strstr($body, '--'.$boundary.'--')){
			$exp1 = explode('--'.$boundary.'--', $body);
			if(strstr($exp1[0], "--".$boundary."\r\n")){
				$exp2 = explode("--".$boundary."\r\n", $exp1[0]);
				foreach($exp2 as $part){
					if(stristr($part, 'Content-Type: ')){
						$exp31 = explode('Content-Type: ', $part);
						$exp32 = explode('Content-type: ', $part);
						if(count($exp31) > 1 && substr($exp31[1], 0, 10) == "multipart/") $data = $exp31[1];
						elseif(count($exp32) > 1 && substr($exp32[1], 0, 10) == "multipart/") $data = $exp32[1];
						else $data = false;
						if($data && strstr($data, 'boundary=')){
							$exp4 = explode('multipart/', $data);
							$exp5 = explode(';', $exp4[1]);
							$multipart2 = $exp5[0];
							if($multipart2 == "mixed" || $multipart2 == "related" || $multipart2 == "alternative"){
								$exp6 = explode('boundary=', $data);
								$exp7 = explode("\n", $exp6[1]);
								$exp8 = explode("\r\n", $exp6[1]);
								$boundary2 = (strlen($exp7[0]) <= strlen($exp8[0])) ? $exp7[0] : $exp8[0];
								$boundary2 = str_replace('"', '', $boundary2);
								if($boundary2 != "") $ret = FUNC::split_reverse($part, $multipart.', '.$multipart2, $boundary2);
							}
						}else{
							if($res = FUNC::split_msg($part)){
								$one = array();
								foreach($res['header'] as $harr){
									foreach($harr as $hnm => $hvl) if(strstr($hnm, 'Content-')) $one[$hnm] = $hvl;
								}
								$one['Multipart'] = $multipart;
								$one['Data'] = $res['body'];
								$ret[] = $one;
							}
						}
					}
				}
			}
		}
		return $ret;
	}

	function split_content($str){
		$ret = false;
		if(is_string($str) && $str != ""){
			if($res = FUNC::split_msg($str)){
				$arr = array();
				if(isset($res['multipart'], $res['boundary'])){
					$arr['header'] = $res['header'];
					$arr['multipart'] = 'yes';
					$arr['body'] = FUNC::split_reverse($res['body'], $res['multipart'], $res['boundary']);
				}else{
					foreach($res['header'] as $harr){
						foreach($harr as $hnm => $hvl) if(strstr($hnm, 'Content-')) $content[$hnm] = $hvl;
					}
					$content['Data'] = $res['body'];
					$arr['header'] = $res['header'];
					$arr['multipart'] = 'no';
					$arr['body'][] = $content;
				}
				$ret = $arr;
			}else throw new Exception('Invalid 2 message value, on class FUNC::split_content()', 512);
		}else throw new Exception('Invalid 1 message value, on class FUNC::split_content()', 512);
		return $ret;
	}

	function decode_content($str, $decode = "base64"){
		if(is_string($str) && is_string($decode)){
			$ret = $str;
			$decode = trim(strtolower($decode));
			if($decode == "base64"){
				$str = FUNC::str_clear($str);
				$str = trim($str);
				$ret = base64_decode($str);
			}elseif($decode == "quoted-printable"){
				$ret = quoted_printable_decode($str);
			}
		}else throw new Exception('Invalid parameter(s), on class FUNC::decode_content()', 512);
		return $ret;
	}

	function mimetype($filename){
		$retm = "application/octet-stream";
		$mime = array(
			'z'    => "application/x-compress", 
			'xls'  => "application/x-excel", 
			'gtar' => "application/x-gtar", 
			'gz'   => "application/x-gzip", 
			'cgi'  => "application/x-httpd-cgi", 
			'php'  => "application/x-httpd-php", 
			'js'   => "application/x-javascript", 
			'swf'  => "application/x-shockwave-flash", 
			'tar'  => "application/x-tar", 
			'tgz'  => "application/x-tar", 
			'tcl'  => "application/x-tcl", 
			'src'  => "application/x-wais-source", 
			'zip'  => "application/zip", 
			'kar'  => "audio/midi", 
			'mid'  => "audio/midi", 
			'midi' => "audio/midi", 
			'mp2'  => "audio/mpeg", 
			'mp3'  => "audio/mpeg", 
			'mpga' => "audio/mpeg", 
			'ram'  => "audio/x-pn-realaudio", 
			'rm'   => "audio/x-pn-realaudio", 
			'rpm'  => "audio/x-pn-realaudio-plugin", 
			'wav'  => "audio/x-wav", 
			'bmp'  => "image/bmp", 
			'fif'  => "image/fif", 
			'gif'  => "image/gif", 
			'ief'  => "image/ief", 
			'jpe'  => "image/jpeg", 
			'jpeg' => "image/jpeg", 
			'jpg'  => "image/jpeg", 
			'png'  => "image/png", 
			'tif'  => "image/tiff", 
			'tiff' => "image/tiff", 
			'css'  => "text/css", 
			'htm'  => "text/html", 
			'html' => "text/html", 
			'txt'  => "text/plain", 
			'rtx'  => "text/richtext", 
			'vcf'  => "text/x-vcard", 
			'xml'  => "text/xml", 
			'xsl'  => "text/xsl", 
			'mpe'  => "video/mpeg", 
			'mpeg' => "video/mpeg", 
			'mpg'  => "video/mpeg", 
			'mov'  => "video/quicktime", 
			'qt'   => "video/quicktime", 
			'asf'  => "video/x-ms-asf", 
			'asx'  => "video/x-ms-asf", 
			'avi'  => "video/x-msvideo", 
			'vrml' => "x-world/x-vrml", 
			'wrl'  => "x-world/x-vrml"
		);
		if(is_string($filename)){
			$filename = FUNC::str_clear($filename);
			$filename = trim($filename);
			if($filename != ""){
				$expext = explode(".", $filename);
				if(count($expext) >= 2){
					$extnam = strtolower($expext[count($expext)-1]);
					if(isset($mime[$extnam])) $retm = $mime[$extnam];
				}
			}else throw new Exception('Invalid parameter value, on class FUNC::mimetype()', 512);
		}else throw new Exception('Invalid parameter type value, on class FUNC::mimetype()', 512);
		return $retm;
	}

}

?>