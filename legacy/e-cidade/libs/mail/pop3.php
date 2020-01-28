<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once 'libs/mail/func.php';

class POP3 {

	function connect($host, $user, $pass, $port = 110, $ssl = false, $timeout = 30){
		$setver = true;
		if(is_string($host)){
			$host = FUNC::str_clear($host);
			$host = trim($host);
			if($host != ""){
				if(FUNC::is_ipv4($host)) $iphost = $host;
				else{
					$iphost = gethostbyname($host);
					if($iphost == $host){
						$setver = false;
						throw new Exception('Invalid 3 hostname value "'.$host.'" (doesn\'t have an IPv4 address), on class POP3::connect()', 512);
					}
				}
			}else{
				$setver = false;
				throw new Exception('Invalid 2 hostname/ip value, on class POP3::connect()', 512);
			}
		}else{
			$setver = false;
			throw new Exception('Invalid 1 hostname/ip type value, on class POP3::connect()', 512);
		}
		if(is_string($user)){
			$user = FUNC::str_clear($user);
			$user = trim($user);
			if($user == ""){
				$setver = false;
				throw new Exception('Invalid 2 username value, on class POP3::connect()', 512);
			}
		}else{
			$setver = false;
			throw new Exception('Invalid 1 username type value, on class POP3::connect()', 512);
		}
		if(is_string($pass)){
			$pass = FUNC::str_clear($pass);
			$pass = trim($pass);
			if($pass == ""){
				$setver = false;
				throw new Exception('Invalid 2 password value, on class POP3::connect()', 512);
			}
		}else{
			$setver = false;
			throw new Exception('Invalid 1 password type value, on class POP3::connect()', 512);
		}
		if(!is_int($port)){
			$port = 110;
			throw new Exception('Invalid port type value, on class POP3::connect()', 512);
		}
		if(is_string($ssl)){
			$ssl = FUNC::str_clear($ssl);
			$ssl = trim(strtolower($ssl));
			if(!($ssl == "tls" || $ssl == "ssl")){
				$ssl = false;
				throw new Exception('Invalid TLS/SSL value, on class POP3::connect()', 512);
			}
		}else{
			if(is_bool($ssl)){
				$ssl = $ssl ? 'ssl' : false;
			}else{
				$ssl = false;
				throw new Exception('Invalid TLS/SSL type value, on class POP3::connect()', 512);
			}
		}
		if(!is_int($timeout)){
			$timeout = 30;
			throw new Exception('Invalid timeout type value, on class POP3::connect()', 512);
		}
		if($setver){
			$proto = $ssl ? $ssl.'://' : '';
			if(!$fp = fsockopen($proto.$iphost, $port, $err_num, $err_msg, $timeout)){
				$setver = false;
				throw new Exception('Response 1 error "'.$err_msg.'", on class POP3::connect()', 512);
			}else{
				stream_set_timeout($fp, $timeout);
				$rcv = fgets($fp, 1024);
				if(substr($rcv, 0, 3) != '+OK'){
					fclose($fp);
					$setver = false;
					throw new Exception('Response 2 error "'.$rcv.'", on class POP3::connect()', 512);
				}
				if($setver){
					fputs($fp, "USER ".$user."\r\n");
					$rcv = fgets($fp, 1024);
					if(substr($rcv, 0, 3) != '+OK'){
						fclose($fp);
						$setver = false;
						throw new Exception('Response 3 error "'.$rcv.'", on class POP3::connect()', 512);
					}
				}
				if($setver){
					fputs($fp, "PASS ".$pass."\r\n");
					$rcv = fgets($fp, 1024);
					if(substr($rcv, 0, 3) != '+OK'){
						fclose($fp);
						$setver = false;
						throw new Exception('Response 4 error "'.$rcv.'", on class POP3::connect()', 512);
					}
				}
				if($setver) $setver = $fp;
			}
		}
		return $setver;
	}

	function pstat($connection){
		$ret = false;
		if(FUNC::is_connection($connection)){
			fputs($connection, "STAT\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) == '+OK'){
				$get = substr($rcv, 4, -1*strlen("\r\n"));
				$exp = explode(' ', $get);
				if(count($exp) == 2){
					$val1 = intval($exp[0]);
					$val2 = intval($exp[1]);
					if(strval($val1) === $exp[0] && strval($val2) === $exp[1]) $ret = array($val1, $val2);
				}else throw new Exception('Response 2 error "'.$rcv.'", on class POP3::pstat()', 512);
			}else throw new Exception('Response 1 error "'.$rcv.'", on class POP3::pstat()', 512);
		}else throw new Exception('Invalid resource connection, on class POP3::pstat()', 512);
		return $ret;
	}

	function plist($connection, $msg = 0){
		$ret = $num = false;
		if($msg){
			if(is_int($msg)) $num = true;
			else{
				throw new Exception('Invalid message number, on class POP3::plist()', 512);
				return false;
			}
		}
		if(FUNC::is_connection($connection)){
			fputs($connection, "LIST".($num ? " ".$msg : "")."\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) != '+OK') throw new Exception('Response error "'.$rcv.'", on class POP3::plist()', 512);
			else{
				$arr = array();
				if($num){
					$get = substr($rcv, 4, -1*strlen("\r\n"));
					$exp = explode(' ', $get);
					if(count($exp) == 2){
						$val1 = intval($exp[0]);
						$val2 = intval($exp[1]);
						if(strval($val1) === $exp[0] && strval($val2) === $exp[1]) $arr[$val1] = $val2;
					}
				}else{
					$list = "";
					while(!feof($connection)){
						$rcv = fgets($connection, 1024);
						$list .= $rcv;
						if(substr($rcv, 0, 1) == ".") break;
					}
					$data = substr($list, 0, -1*strlen("\r\n.\r\n"));
					if(!empty($data)){
						$exp1 = explode("\r\n", $data);
						foreach($exp1 as $line){
							$exp2 = explode(' ', $line);
							if(count($exp2) == 2){
								$val1 = intval($exp2[0]);
								$val2 = intval($exp2[1]);
								if(strval($val1) === $exp2[0] && strval($val2) === $exp2[1]) $arr[$val1] = $val2;
							}
						}
					}
				}
				if(count($arr) > 0) $ret = $arr;
			}
		}else throw new Exception('Invalid resource connection, on class POP3::plist()', 512);
		return $ret;
	}

	function pretr($connection, $msg){
		$ret = false;
		if(!(is_int($msg) && $msg > 0)){
			throw new Exception('Invalid message number, on class POP3::pretr()', 512);
			return false;
		}
		if(FUNC::is_connection($connection)){
			fputs($connection, "RETR ".$msg."\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) != '+OK') throw new Exception('Response error "'.$rcv.'", on class POP3::pretr()', 512);
			else{
				$ret = "";
				while(!feof($connection)){
					$line = fgets($connection, 1024);
					if($line == ".\r\n") break;
					$ret .= $line;
				}
			}
		}else throw new Exception('Invalid resource connection, on class POP3::pretr()', 512);
		return $ret;
	}

	function pdele($connection, $msg){
		$ret = false;
		if(!(is_int($msg) && $msg > 0)){
			throw new Exception('Invalid message number, on class POP3::pdele()', 512);
			return false;
		}
		if(FUNC::is_connection($connection)){
			fputs($connection, "DELE ".$msg."\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) == '+OK') $ret = true;
			else throw new Exception('Response error "'.$rcv.'", on class POP3::pdele()', 512);
		}else throw new Exception('Invalid resource connection, on class POP3::pdele()', 512);
		return $ret;
	}

	function pnoop($connection){
		$ret = false;
		if(FUNC::is_connection($connection)){
			fputs($connection, "NOOP\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) == '+OK') $ret = true;
			else throw new Exception('Response error "'.$rcv.'", on class POP3::pnoop()', 512);
		}else throw new Exception('Invalid resource connection, on class POP3::pnoop()', 512);
		return $ret;
	}

	function prset($connection){
		$ret = false;
		if(FUNC::is_connection($connection)){
			fputs($connection, "RSET\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) == '+OK') $ret = true;
			else throw new Exception('Response error "'.$rcv.'", on class POP3::prset()', 512);
		}else throw new Exception('Invalid resource connection, on class POP3::prset()', 512);
		return $ret;
	}

	function pquit($connection){
		$ret = false;
		if(FUNC::is_connection($connection)){
			fputs($connection, "QUIT\r\n");
			$rcv = fgets($connection, 1024);
			if(substr($rcv, 0, 3) == '+OK') $ret = true;
			else throw new Exception('Response error "'.$rcv.'", on class POP3::pquit()', 512);
			FUNC::close($connection);
		}else throw new Exception('Invalid resource connection, on class POP3::pquit()', 512);
		return $ret;
	}

}

?>