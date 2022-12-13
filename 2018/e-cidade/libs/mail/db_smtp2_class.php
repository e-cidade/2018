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

 * Fifth Floor, Boston, MA  02110-1301  USA                                            *
 *                                                                                     *
 * XPertMailer SMTP & POP3 PHP Mail Client. Can send and read messages in MIME Format. *
 * Copyright (C) 2006  Tanase Laurentiu Iulian                                         *
 *                                                                                     *
 ***************************************************************************************/
require_once modification("libs/mail/func.php");
require_once modification("libs/mail/mime.php");

class SMTP extends MIME {

	var $_smtpconn;
	var $_subject;
	var $_content;
	var $_arrcon;
	var $_arrenc;

	var $_crlf = "\r\n";
	var $_port = 25;
	var $_unique = 0;
	var $_timeout = 30;
	var $_chanklen = 70;

	var $max_cl = 99;
	var $max_sl = 1024;
	var $result = 'unknown';

	var $_header = false;
	var $_attach = false;

	var $_toaddrs = false;
	var $_ccaddrs = false;
	var $_bccaddrs = false;
	var $_fromaddr = false;
	var $_fromhost = false;

	var $_relay = false;
	var $_chunk = false;
	var $_atext = false;
	var $_ahtml = false;
  var $sock   = false;
  
	function __construct() {
		$this->_smtpconn = array('local');
		$this->_arrcon = array('local' => '', 'client' => '', 'relay' => '');
		$this->_arrenc = array('7bit' => '', '8bit' => '', 'quoted-printable' => '', 'base64' => ''); // binary not alowed
	}

	function delivery($conn){

		$ret = false;
		if(is_string($conn)){
			$conn = trim($conn);
			if(FUNC::is_alpha($conn, false, '-')){
				$exp = explode('-', $conn);
				$rep = array();
				foreach($exp as $val){
					$val = strtolower($val);
					if(isset($this->_arrcon[$val])) $rep[] = $val;
					else throw new Exception('Invalid connection type value "'.$val.'", on class SMTP::delivery()', 512);
				}
				if(count($rep) > 0){
					$this->_smtpconn = $rep;
					$ret = true;
				}
			}else throw new Exception('Invalid parameter value, on class SMTP::delivery()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::delivery()', 512);
		return $ret;

	}

	function port($num){

		$ret = false;
		if(is_int($num)){
			$this->_port = $num;
			$ret = true;
		}else throw new Exception('Invalid parameter type value, on class SMTP::port()', 512);
		return $ret;

	}

	function timeout($num){

		$ret = false;
		if(is_int($num)){
			$this->_timeout = $num;
			$ret = true;
		}else throw new Exception('Invalid parameter type value, on class SMTP::timeout()', 512);
		return $ret;

	}

	function relay($raddr, $ruser = false, $rpass = false, $rport = 25, $rauth = 'autodetect', $rvssl = false){

		$ret = false;
		if(is_string($raddr)){
			$raddr = FUNC::str_clear($raddr, array(' '));
			$raddr = trim(strtolower($raddr));
			if($raddr != ""){
				$ret = true;
				if(FUNC::is_ipv4($raddr)) $rvip = $raddr;
				else{
					$rvip = gethostbyname($raddr);
					if($rvip == $raddr){
						$ret = false;
						throw new Exception('Invalid hostname value "'.$raddr.'", on class SMTP::relay()', 512);
					}
				}
			}else throw new Exception('Invalid hostname/ip value, on class SMTP::relay()', 512);
		}else throw new Exception('Invalid hostname/ip type value, on class SMTP::relay()', 512);
		if($ret){
			if(is_bool($ruser)){
				if(!$ruser) $ruser = '';
				else throw new Exception('Invalid username 1 type value, on class SMTP::relay()', 512);
			}elseif(is_string($ruser)){
				$ruser = FUNC::str_clear($ruser);
				$ruser = trim($ruser);
			}else{
				$ruser = '';
				throw new Exception('Invalid username 2 type value, on class SMTP::relay()', 512);
			}
			if(is_bool($rpass)){
				if(!$rpass) $rpass = '';
				else throw new Exception('Invalid password 1 type value, on class SMTP::relay()', 512);
			}elseif(is_string($rpass)){
				$rpass = FUNC::str_clear($rpass);
				$rpass = trim($rpass);
			}else{
				$rpass = '';
				throw new Exception('Invalid password type value, on class SMTP::relay()', 512);
			}
			if(($ruser != "" && $rpass == "") || ($ruser == "" && $rpass != "")){
				$ruser = $rpass = '';
				throw new Exception('Invalid username and password combination value, on class SMTP::relay()', 512);
			}
			if(!is_int($rport)){
				$rport = 25;
				throw new Exception('Invalid port type value, on class SMTP::relay()', 512);
			}
			if(is_string($rauth)){
				$rauth = trim(strtolower($rauth));
				if(!($rauth == "autodetect" || $rauth == "login" || $rauth == "plain")){
					$rauth = 'autodetect';
					throw new Exception('Invalid auth value, on class SMTP::relay()', 512);
				}
			}else{
				$rauth = 'autodetect';
				throw new Exception('Invalid auth type value, on class SMTP::relay()', 512);
			}
			if(is_string($rvssl)){
				$rvssl = FUNC::str_clear($rvssl);
				$rvssl = trim(strtolower($rvssl));
				if(!($rvssl == "tls" || $rvssl == "ssl")){
					$rvssl = false;
					throw new Exception('Invalid TLS/SSL value, on class SMTP::relay()', 512);
				}
			}else{
				if(is_bool($rvssl)){
					$rvssl = $rvssl ? 'tls' : false;
				}else{
					$rvssl = false;
					throw new Exception('Invalid TLS/SSL type value, on class SMTP::relay()', 512);
				}
			}
			$this->_relay = array('host' => $raddr, 'ip' => $rvip, 'user' => $ruser, 'pass' => $rpass, 'port' => $rport, 'auth' => $rauth, 'ssl' => $rvssl);
		}
		return $ret;

	}

	function addheader($hname, $hvalue){

		$ret = false;
		if(is_string($hname)){
			$hname = FUNC::str_clear($hname, array(' '));
			$hname = trim($hname);
			if($hname != ""){
				if(is_string($hvalue)){
					$hvalue = str_replace("\r\n\t", " ", $hvalue);
					$hvalue = FUNC::str_clear($hvalue);
					$hvalue = trim($hvalue);
					if($hvalue != ""){
						$vname = strtolower($hname);
						if($vname == "subject") throw new Exception('Can not set "Subject" header value, for this, use function "Send()", on class SMTP::addheader()', 512);
						elseif($vname == "from") throw new Exception('Can not set "From" header value, for this, use function "From()", on class SMTP::addheader()', 512);
						elseif($vname == "to") throw new Exception('Can not set "To" header value, for this, use function "AddTo()", on class SMTP::addheader()', 512);
						elseif($vname == "cc") throw new Exception('Can not set "Cc" header value, for this, use function "AddCc()", on class SMTP::addheader()', 512);
						elseif($vname == "bcc") throw new Exception('Can not set "Bcc" header value, for this, use function "AddBcc()", on class SMTP::addheader()', 512);
						elseif($vname == "date") throw new Exception('Can not set "Date" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						elseif($vname == "x-mailer") throw new Exception('Can not set "X-Mailer" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						elseif($vname == "content-type") throw new Exception('Can not set "Content-Type" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						elseif($vname == "content-transfer-encoding") throw new Exception('Can not set "Content-Transfer-Encoding" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						elseif($vname == "content-disposition") throw new Exception('Can not set "Content-Disposition" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						elseif($vname == "x-priority") throw new Exception('Can not set "X-Priority" header value, for this, use function "Priority()", on class SMTP::addheader()', 512);
						elseif($vname == "x-msmail-priority") throw new Exception('Can not set "X-MSMail-Priority" header value, for this, use function "Priority()", on class SMTP::addheader()', 512);
						elseif($vname == "mime-version") throw new Exception('Can not set "MIME-Version" header value, this value is automaticaly set, on class SMTP::addheader()', 512);
						else{
							$ret = true;
							$this->_header[] = array('name' => ucfirst($hname), 'value' => $hvalue);
						}
					}else throw new Exception('Invalid 2\'nd parameter value, on class SMTP::addheader()', 512);
				}else throw new Exception('Invalid 2\'nd parameter type value, on class SMTP::addheader()', 512);
			}else throw new Exception('Invalid 1\'st parameter value, on class SMTP::addheader()', 512);
		}else throw new Exception('Invalid 1\'st parameter type value, on class SMTP::addheader()', 512);
		return $ret;

	}

	function delheader($hname){

		$ret = false;
		if(is_string($hname)){
			$hname = FUNC::str_clear($hname, array(' '));
			$hname = trim($hname);
			if($hname != ""){
				if($this->_header && count($this->_header) > 0){
					$reparr = array();
					foreach($this->_header as $harr){
						if(strtolower($harr['name']) != strtolower($hname)) $reparr[] = $harr;
						else $ret = true;
					}
					$this->_header = $reparr;
				}
			}else throw new Exception('Invalid parameter value, on class SMTP::delheader()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::delheader()', 512);
		return $ret;

	}

	function addto($adrr, $name = ''){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != "" && FUNC::is_mail($adrr)){
				if(!isset($this->_toaddrs[$adrr])){
					$this->_toaddrs[$adrr] = '';
					$ret = true;
					if(is_string($name)){
						$name = FUNC::str_clear($name);
						$name = trim($name);
						if($name != "") $this->_toaddrs[$adrr] = $this->qpheader($name);
					}else throw new Exception('Invalid 2\'nd parameter type value, on class SMTP::addto()', 512);
				}else throw new Exception('Already exists, on class SMTP::addto()', 512);
			}else throw new Exception('Invalid 1\'st parameter value, on class SMTP::addto()', 512);
		}else throw new Exception('Invalid 1\'st parameter type value, on class SMTP::addto()', 512);
		return $ret;

	}

	function delto($adrr = 'all'){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != ""){
				if($adrr == "all"){
					$this->_toaddrs = false;
					$ret = true;
				}elseif(FUNC::is_mail($adrr)){
					if(is_array($this->_toaddrs) && count($this->_toaddrs) > 0){
						$reb = array();
						foreach($this->_toaddrs as $num => $val){
							if($num != $adrr) $reb[$num] = $val;
							else $ret = true;
						}
						$this->_toaddrs = $reb;
					}
				}else throw new Exception('Invalid 2 parameter value, on class SMTP::delto()', 512);
			}else throw new Exception('Invalid 1 parameter value, on class SMTP::delto()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::delto()', 512);
		return $ret;

	}

	function addcc($adrr, $name = ''){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != "" && FUNC::is_mail($adrr)){
				if(!isset($this->_ccaddrs[$adrr])){
					$this->_ccaddrs[$adrr] = '';
					$ret = true;
					if(is_string($name)){
						$name = FUNC::str_clear($name);
						$name = trim($name);
						if($name != "") $this->_ccaddrs[$adrr] = $this->qpheader($name);
					}else throw new Exception('Invalid 2\'nd parameter type value, on class SMTP::addcc()', 512);
				}else throw new Exception('Already exists, on class SMTP::addcc()', 512);
			}else throw new Exception('Invalid 1\'st parameter value, on class SMTP::addcc()', 512);
		}else throw new Exception('Invalid 1\'st parameter type value, on class SMTP::addcc()', 512);
		return $ret;

	}

	function delcc($adrr = 'all'){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != ""){
				if($adrr == "all"){
					$this->_ccaddrs = false;
					$ret = true;
				}elseif(FUNC::is_mail($adrr)){
					if(is_array($this->_ccaddrs) && count($this->_ccaddrs) > 0){
						$reb = array();
						foreach($this->_ccaddrs as $num => $val){
							if($num != $adrr) $reb[$num] = $val;
							else $ret = true;
						}
						$this->_ccaddrs = $reb;
					}
				}else throw new Exception('Invalid 2 parameter value, on class SMTP::delcc()', 512);
			}else throw new Exception('Invalid 1 parameter value, on class SMTP::delcc()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::delcc()', 512);
		return $ret;

	}

	function addbcc($adrr){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != "" && FUNC::is_mail($adrr)){
				if(!isset($this->_bccaddrs[$adrr])){
					$this->_bccaddrs[$adrr] = '';
					$ret = true;
				}else throw new Exception('Already exists, on class SMTP::addbcc()', 512);
			}else throw new Exception('Invalid parameter value, on class SMTP::addbcc()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::addbcc()', 512);
		return $ret;

	}

	function delbcc($adrr = 'all'){

		$ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != ""){
				if($adrr == "all"){
					$this->_bccaddrs = false;
					$ret = true;
				}elseif(FUNC::is_mail($adrr)){
					if(is_array($this->_bccaddrs) && count($this->_bccaddrs) > 0){
						$reb = array();
						foreach($this->_bccaddrs as $num => $val){
							if($num != $adrr) $reb[$num] = $val;
							else $ret = true;
						}
						$this->_bccaddrs = $reb;
					}
				}else throw new Exception('Invalid 2 parameter value, on class SMTP::delbcc()', 512);
			}else throw new Exception('Invalid 1 parameter value, on class SMTP::delbcc()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::delbcc()', 512);
		return $ret;

	}

	function from($adrr, $name = ''){

		$this->_fromaddr = $ret = false;
		if(is_string($adrr)){
			$adrr = FUNC::str_clear($adrr, array(' '));
			$adrr = strtolower(trim($adrr));
			if($adrr != "" && FUNC::is_mail($adrr)){
				$ret = true;
				$this->_fromaddr = array('address' => $adrr, 'name' => '');
				if(is_string($name)){
					$name = FUNC::str_clear($name);
					$name = trim($name);
					if($name != "") $this->_fromaddr = array('address' => $adrr, 'name' => $this->qpheader($name));
				}else throw new Exception('Invalid 2\'nd parameter type value, on class SMTP::from()', 512);
			}else throw new Exception('Invalid mail address format, on class SMTP::from()', 512);
		}else throw new Exception('Invalid first parameter type value, on class SMTP::from()', 512);
		return $ret;

	}

	function fromhost($server, &$havemx){

		$this->_fromhost = $ret = $havemx = false;
		if(is_string($server)){
			$server = FUNC::str_clear($server, array(' '));
			$server = strtolower(trim($server));
			if($server != ""){
				$ret = true;
				$this->_fromhost = $server;
				if(FUNC::is_hostname($server)){
					$havemx = FUNC::is_win() ? FUNC::getmxrr_win($server, $mxhost) : getmxrr($server, $mxhost);
				}
			}else throw new Exception('Invalid parameter value, on class SMTP::fromhost()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::fromhost()', 512);
		return $ret;

	}

	function text($text, $charset = 'iso-8859-1', $encoding = 'quoted-printable', $disposition = 'inline'){

		$this->_atext = $ret = false;
		if(is_string($charset)){
			$charset = FUNC::str_clear($charset, array(' '));
			$charset = trim($charset);
			$charlen = strlen($charset);
			if(!($charlen > 1 && $charlen < 60)){
				$charset = 'us-ascii';
				throw new Exception('Invalid charset value, on class SMTP::text()', 512);
			}
		}else{
			$charset = 'us-ascii';
			throw new Exception('Invalid charset type value, on class SMTP::text()', 512);
		}
		if(is_string($encoding)){
			$encoding = FUNC::str_clear($encoding, array(' '));
			$encoding = trim(strtolower($encoding));
			if(!($encoding != "" && isset($this->_arrenc[$encoding]))){
				$encoding = 'quoted-printable';
				throw new Exception('Invalid encoding value, on class SMTP::text()', 512);
			}
		}else{
			$encoding = 'quoted-printable';
			throw new Exception('Invalid encoding type value, on class SMTP::text()', 512);
		}
		if(is_string($disposition)){
			$disposition = FUNC::str_clear($disposition, array(' '));
			$disposition = trim(strtolower($disposition));
			if(!($disposition == "attachment" || $disposition == "inline")){
				$disposition = 'inline';
				throw new Exception('Invalid disposition value, on class SMTP::text()', 512);
			}
		}else{
			$disposition = 'inline';
			throw new Exception('Invalid disposition type value, on class SMTP::text()', 512);
		}
		if(is_string($text)){
			$text = trim($text);
			if($text != ""){
				$htext = 'Content-Type: text/plain;'.$this->_crlf."\t".'charset="'.$charset.'"'.$this->_crlf.
					'Content-Transfer-Encoding: '.$encoding.$this->_crlf.
					'Content-Disposition: '.$disposition;
				$this->_atext = array($htext, $encoding, $text);
				$ret = true;
			}else throw new Exception('Invalid text/plain value, on class SMTP::text()', 512);
		}else throw new Exception('Invalid text/plain type value, on class SMTP::text()', 512);
		return $ret;

	}

	function html($html, $charset = 'iso-8859-1', $encoding = 'quoted-printable', $disposition = 'inline'){

		$this->_ahtml = $ret = false;
		if(is_string($charset)){
			$charset = FUNC::str_clear($charset, array(' '));
			$charset = trim($charset);
			$charlen = strlen($charset);
			if(!($charlen > 1 && $charlen < 60)){
				$charset = 'us-ascii';
				throw new Exception('Invalid charset value, on class SMTP::html()', 512);
			}
		}else{
			$charset = 'us-ascii';
			throw new Exception('Invalid charset type value, on class SMTP::html()', 512);
		}
		if(is_string($encoding)){
			$encoding = FUNC::str_clear($encoding, array(' '));
			$encoding = trim(strtolower($encoding));
			if(!($encoding != "" && isset($this->_arrenc[$encoding]))){
				$encoding = 'quoted-printable';
				throw new Exception('Invalid encoding value, on class SMTP::html()', 512);
			}
		}else{
			$encoding = 'quoted-printable';
			throw new Exception('Invalid encoding type value, on class SMTP::html()', 512);
		}
		if(is_string($disposition)){
			$disposition = FUNC::str_clear($disposition, array(' '));
			$disposition = trim(strtolower($disposition));
			if(!($disposition == "attachment" || $disposition == "inline")){
				$disposition = 'inline';
				throw new Exception('Invalid disposition value, on class SMTP::html()', 512);
			}
		}else{
			$disposition = 'inline';
			throw new Exception('Invalid disposition type value, on class SMTP::html()', 512);
		}
		if(is_string($html)){
			$html = trim($html);
			if($html != ""){
				$hhtml = 'Content-Type: text/html;'.$this->_crlf."\t".'charset="'.$charset.'"'.$this->_crlf.
					'Content-Transfer-Encoding: '.$encoding.$this->_crlf.
					'Content-Disposition: '.$disposition;
				$this->_ahtml = array($hhtml, $encoding, $html);
				$ret = true;
			}else throw new Exception('Invalid text/html value, on class SMTP::html()', 512);
		}else throw new Exception('Invalid text/html type value, on class SMTP::html()', 512);
		return $ret;

	}

	function attachsource($source, $name, $mimetype = 'autodetect', $disposition = 'attachment', $encoding = 'base64'){

		$ret = false;
		if(is_string($source) && $source != ""){
			if(is_string($name)){
				$name = FUNC::str_clear($name);
				$name = trim($name);
				if($name != ""){
					$ret = true;
					$mime = 'application/octet-stream';
					if(is_string($mimetype)){
						$mimetype = FUNC::str_clear($mimetype, array(' '));
						$mimetype = trim(strtolower($mimetype));
						$mime = ($mimetype == "autodetect" || $mimetype == "") ? FUNC::mimetype($name) : $mimetype;
					}
					$disp = 'attachment';
					if(is_string($disposition)){
						$disposition = FUNC::str_clear($disposition, array(' '));
						$disposition = trim(strtolower($disposition));
						if($disposition == "attachment" || $disposition == "inline") $disp = $disposition;
						else throw new Exception('Invalid disposition value, on class SMTP::attachsource()', 512);
					}else throw new Exception('Invalid disposition type value, on class SMTP::attachsource()', 512);
					$encode = 'base64';
					if(is_string($encoding)){
						$encoding = FUNC::str_clear($encoding, array(' '));
						$encoding = trim(strtolower($encoding));
						if($encoding != "" && isset($this->_arrenc[$encoding])) $encode = $encoding;
						else throw new Exception('Invalid encoding value, on class SMTP::attachsource()', 512);
					}else throw new Exception('Invalid encoding type value, on class SMTP::attachsource()', 512);
					$this->_attach[] = array('name' => $name, 'mime' => $mime, 'disp' => $disp, 'encode' => $encode, 'source' => $source);
				}else throw new Exception('Invalid name value, on class SMTP::attachsource()', 512);
			}else throw new Exception('Invalid name type value, on class SMTP::attachsource()', 512);
		}else throw new Exception('Invalid source value, on class SMTP::attachsource()', 512);
		return $ret;

	}

	function attachfile($file, $name = false, $mimetype = 'autodetect', $disposition = 'attachment', $encoding = 'base64'){

		$ret = false;
		if(is_string($file)){
			$file = FUNC::str_clear($file);
			$file = trim($file);
			if($file != "" && is_file($file) && is_readable($file)){
				if((is_bool($name) && !$name) || (is_string($name) && $name == '')){
					$exp1 = explode("/", $file);
					$name = $exp1[count($exp1)-1];
					$exp2 = explode("\\", $name);
					$name = $exp2[count($exp2)-1];
				}
				$ret = $this->attachsource(file_get_contents($file), $name, $mimetype, $disposition, $encoding);
			}else throw new Exception('Invalid file source, on class SMTP::attachfile()', 512);
		}else throw new Exception('Invalid file type value, on class SMTP::attachfile()', 512);
		return $ret;

	}

	function delattach($name = true){

		$ret = false;
		if(is_bool($name)){
			if($name){
				$this->_attach = false;
				$ret = true;
			}else throw new Exception('Invalid 2 file name type value, on class SMTP::delattach()', 512);
		}elseif(is_string($name)){
			$name = trim($name);
			if($name != ""){
				if($this->_attach && count($this->_attach) > 0){
					$rebatt = array();
					foreach($this->_attach as $attarr){
						if($attarr['name'] != $name) $rebatt[] = $attarr;
						else $ret = true;
					}
					if($ret) $this->_attach = $rebatt;
				}
			}else throw new Exception('Invalid file name value, on class SMTP::delattach()', 512);
		}else throw new Exception('Invalid file name type value, on class SMTP::delattach()', 512);
		return $ret;

	}

	function priority($level = 3){

		$ret = $set = false;
		if(is_int($level)){
			if($level == 1) $set = array('1', 'High');
			elseif($level == 3) $set = array('3', 'Normal');
			elseif($level == 5) $set = array('5', 'Low');
			else throw new Exception('Invalid 1 parameter value, on class SMTP::priority()', 512);
		}elseif(is_string($level)){
			$level = FUNC::str_clear($level, array(' '));
			$level = trim(strtolower($level));
			if($level == "high") $set = array('1', 'High');
			elseif($level == "normal") $set = array('3', 'Normal');
			elseif($level == "low") $set = array('5', 'Low');
			else throw new Exception('Invalid 2 parameter value, on class SMTP::priority()', 512);
		}else throw new Exception('Invalid parameter type value, on class SMTP::priority()', 512);
		if($set){
			$this->delheader('X-Priority');
			$this->delheader('X-MSMail-Priority');
			$this->_header[] = array('name' => 'X-Priority', 'value' => $set[0]);
			$this->_header[] = array('name' => 'X-MSMail-Priority', 'value' => $set[1]);
			$ret = true;
		}
		return $ret;

	}

	function _sendtoip($ip, $arrto, $isrelay){

		$ssl = '';
		$pnm = $this->_port;
		
		if($isrelay){
			$ssl = $this->_relay['ssl'] ? $this->_relay['ssl'].'://' : '';
			$pnm = $this->_relay['port'];
		}
		
		if(!$sock = fsockopen($ssl.$ip, $pnm, $errnum, $errmsg, $this->_timeout)){
			$this->result = 'Error 10: '.$errmsg;
			return false;
		}
    
		$this->sock = $sock;
    
		stream_set_timeout($sock, $this->_timeout);
		$loop = $rcv = 0;
		while(!feof($sock)){
			$loop++;
			if($rcv = fgets($sock, $this->max_sl)){
				if($loop == $this->max_cl || substr($rcv, 0, 4) != "220-") break;
			}else break;
		}
		if(!$rcv){
			$this->result = 'Error 11: can\'t read';
			 return false;
		}
		if(substr($rcv, 0, 4) != "220 "){
			$this->result = 'Error 12: '.$rcv;
			 return false;
		}
		if(!FUNC::is_connection($sock)){
			$this->result = 'Error 13: invalid resource connection';
			 return false;
		}
		if($isrelay && $this->_relay['user'] != "" && $this->_relay['pass'] != ""){
			if(!fputs($sock, 'EHLO '.$this->_fromhost.$this->_crlf)){
				$this->result = 'Error 20: can\'t write';
				 return false;
			}
			$loop = $rcv = 0;
			$getinfo = '';
			while(!feof($sock)){
				$loop++;
				if($rcv = fgets($sock, $this->max_sl)){
					$getinfo .= $rcv;
					if($loop == $this->max_cl || substr($rcv, 0, 4) != "250-") break;
				}else break;
			}
			if(!$rcv){
				$this->result = 'Error 21: can\'t read';
				 return false;
			}
			if(substr($rcv, 0, 4) != "250 "){
				if(!FUNC::is_connection($sock)){
					$this->result = 'Error 22: invalid resource connection';
					 return false;
				}
				if(!fputs($sock, 'HELO '.$this->_fromhost.$this->_crlf)){
					$this->result = 'Error 23: can\'t write';
					 return false;
				}
				$loop = $rcv = 0;
				$getinfo = '';
				while(!feof($sock)){
					$loop++;
					if($rcv = fgets($sock, $this->max_sl)){
						$getinfo .= $rcv;
						if($loop == $this->max_cl || substr($rcv, 0, 4) != "250-") break;
					}else break;
				}
				if(!$rcv){
					$this->result = 'Error 24: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "250 "){
					$this->result = 'Error 25: '.$rcv;
					 return false;
				}
			}
			$authlogin = strstr($getinfo, 'LOGIN');
			$authplain = strstr($getinfo, 'PLAIN');
			$authtype = 'login';
			if($this->_relay['auth'] == "autodetect" || $this->_relay['auth'] == "login"){
				if(!$authlogin){
					if($authplain) $authtype = 'plain';
				}
			}elseif($this->_relay['auth'] == "plain"){
				if($authplain) $authtype = 'plain';
			}
			if(!FUNC::is_connection($sock)){
				$this->result = 'Error 26: invalid resource connection';
				 return false;
			}
			if($authtype == "login"){
				if(!fputs($sock, 'AUTH LOGIN'.$this->_crlf)){
					$this->result = 'Error 270: can\'t write';
					 return false;
				}
				if(!$rcv = fgets($sock, $this->max_sl)){
					$this->result = 'Error 271: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "334 "){
					$this->result = 'Error 272: '.$rcv;
					 return false;
				}
				if(!FUNC::is_connection($sock)){
					$this->result = 'Error 273: invalid resource connection';
					 return false;
				}
				if(!fputs($sock, base64_encode($this->_relay['user']).$this->_crlf)){
					$this->result = 'Error 274: can\'t write';
					 return false;
				}
				if(!$rcv = fgets($sock, $this->max_sl)){
					$this->result = 'Error 275: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "334 "){
					$this->result = 'Error 276: '.$rcv;
					 return false;
				}
				if(!FUNC::is_connection($sock)){
					$this->result = 'Error 277: invalid resource connection';
					 return false;
				}
				if(!fputs($sock, base64_encode($this->_relay['pass']).$this->_crlf)){
					$this->result = 'Error 278: can\'t write';
					 return false;
				}
				if(!$rcv = fgets($sock, $this->max_sl)){
					$this->result = 'Error 279: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "235 "){
					$this->result = 'Error 280: '.$rcv;
					 return false;
				}
			}elseif($authtype == "plain"){
				if(!FUNC::is_connection($sock)){
					$this->result = 'Error 281: invalid resource connection';
					 return false;
				}
				if(!fputs($sock, 'AUTH PLAIN '.base64_encode($this->_relay['user'].chr(0).$this->_relay['user'].chr(0).$this->_relay['pass']).$this->_crlf)){
					$this->result = 'Error 282: can\'t write';
					 return false;
				}
				if(!$rcv = fgets($sock, $this->max_sl)){
					$this->result = 'Error 283: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "235 "){
					$this->result = 'Error 284: '.$rcv;
					 return false;
				}
			}
		}else{
			if(!fputs($sock, 'HELO '.$this->_fromhost.$this->_crlf)){
				$this->result = 'Error 30: can\'t write';
				 return false;
			}
			$loop = $rcv = 0;
			while(!feof($sock)){
				$loop++;
				if(!$rcv = fgets($sock, $this->max_sl)){
					if($loop == $this->max_cl || substr($rcv, 0, 4) != "250-") break;
				}else break;
			}
			if(!$rcv){
				$this->result = 'Error 31: can\'t read';
				 return false;
			}
			if(substr($rcv, 0, 4) != "250 "){
				if(!FUNC::is_connection($sock)){
					$this->result = 'Error 32: invalid resource connection';
					 return false;
				}
				if(!fputs($sock, 'EHLO '.$this->_fromhost.$this->_crlf)){
					$this->result = 'Error 33: can\'t write';
					 return false;
				}
				$loop = $rcv = 0;
				while(!feof($sock)){
					$loop++;
					if(!$rcv = fgets($sock, $this->max_sl)){
						if($loop == $this->max_cl || substr($rcv, 0, 4) != "250-") break;
					}else break;
				}
				if(!$rcv){
					$this->result = 'Error 34: can\'t read';
					 return false;
				}
				if(substr($rcv, 0, 4) != "250 "){
					$this->result = 'Error 35: '.$rcv;
					 return false;
				}
			}
		}
		if(!FUNC::is_connection($sock)){
			$this->result = 'Error 40: invalid resource connection';
			 return false;
		}
		if(!fputs($sock, 'MAIL FROM:<'.$this->_fromaddr['address'].'>'.$this->_crlf)){
			$this->result = 'Error 41: can\'t write';
			 return false;
		}
		if(!$rcv = fgets($sock, $this->max_sl)){
			$this->result = 'Error 42: can\'t read';
			 return false;
		}
		if(substr($rcv, 0, 4) != "250 "){
			$this->result = 'Error 43: '.$rcv;
			 return false;
		}
		$relayh = $isrelay ? '@'.$this->_relay['host'].':' : '';
		$setver = true;
		foreach($arrto as $arrval){
			if(!FUNC::is_connection($sock)){
				$this->result = 'Error 50: invalid resource connection';
				$setver = false;
				break;
			}
			if(!fputs($sock, 'RCPT TO:<'.$relayh.$arrval.'>'.$this->_crlf)){
				$this->result = 'Error 51: can\'t write';
				$setver = false;
				break;
			}
			if(!$rcv = fgets($sock, $this->max_sl)){
				$this->result = 'Error 52: can\'t read';
				$setver = false;
				break;
			}
			$submsg = substr($rcv, 0, 4);
			if(!($submsg == "250 " || $submsg == "251 ")){
				$this->result = 'Error 53: '.$rcv;
				$setver = false;
				break;
			}
		}
		if(!$setver)  return false;
		if(!FUNC::is_connection($sock)){
			$this->result = 'Error 60: invalid resource connection';
			 return false;
		}
		if(!fputs($sock, 'DATA'.$this->_crlf)){
			$this->result = 'Error 61: can\'t write';
			 return false;
		}
		if(!$rcv = fgets($sock, $this->max_sl)){
			$this->result = 'Error 62: can\'t read';
			 return false;
		}
		if(substr($rcv, 0, 4) != "354 "){
			$this->result = 'Error 63: '.$rcv;
			 return false;
		}
		if(!FUNC::is_connection($sock)){
			$this->result = 'Error 70: invalid resource connection';
			 return false;
		}
		if(!fputs($sock, $this->_content['header']['client'])){
			$this->result = 'Error 71: can\'t write';
			 return false;
		}
		$setver = true;
		foreach($this->_content['body'] as $partmsg){
			if(!FUNC::is_connection($sock)){
				$this->result = 'Error 72: invalid resource connection';
				$setver = false;
				break;
			}
			if(!fputs($sock, $partmsg)){
				$this->result = 'Error 73: can\'t write';
				$setver = false;
				break;
			}
		}
		if(!$setver)  return false;
		if(!FUNC::is_connection($sock)){
			$this->result = 'Error 80: invalid resource connection';
			 return false;
		}
		if(!fputs($sock, $this->_crlf.'.'.$this->_crlf)){
			$this->result = 'Error 81: can\'t write';
			 return false;
		}
		if(!$rcv = fgets($sock, $this->max_sl)){
			$this->result = 'Error 82: can\'t read';
			 return false;
		}
		if(substr($rcv, 0, 4) != "250 "){
			$this->result = 'Error 83: '.$rcv;
			 return false;
		}
		if(FUNC::is_connection($sock)){
			if(fputs($sock, 'RSET'.$this->_crlf)){
				if(FUNC::is_connection($sock)){
					if($rcvr = @fgets($sock, $this->max_sl)){
						if(substr($rcvr, 0, 3) == "250"){
							if(fputs($sock, 'QUIT'.$this->_crlf)){
								if(FUNC::is_connection($sock)){
									if($rcvq = @fgets($sock, $this->max_sl)) $rcv = $rcvq;
									FUNC::close($sock);
								}
							}
						}
					}
				}
			}
		}
		$this->result = 'Success: '.$rcv;
		return true;

	}

	function _sendtohost($hname, $arrto, $isrelay){

		$ret = false;
		if($hname == "localhost"){
			$ret = mail($this->_content['header']['to'], $this->_subject, implode('', $this->_content['body']), $this->_content['header']['local']);
			if(!$ret) $ret = $this->_sendtoip('127.0.0.1', $arrto, $isrelay);
		}else{
			if($isrelay) $ret = $this->_sendtoip($hname, $arrto, $isrelay);
			else{
				if(FUNC::is_ipv4($hname)) $ret = $this->_sendtoip($hname, $arrto, $isrelay);
				else{
					$resmx = FUNC::is_win() ? FUNC::getmxrr_win($hname, $mxhost) : getmxrr($hname, $mxhost);
					$iparr = array();
					if($resmx){
						foreach($mxhost as $hostname){
							$iphost = gethostbyname($hostname);
							if($iphost != $hname && FUNC::is_ipv4($iphost) && !isset($iparr[$iphost])) $iparr[$iphost] = $iphost;
						}
					}else{
						$iphost = gethostbyname($hname);
						if($iphost != $hname && FUNC::is_ipv4($iphost)) $iparr[$iphost] = $iphost;
					}
					if(count($iparr) > 0){
						foreach($iparr as $ipaddr) if($ret = $this->_sendtoip($ipaddr, $arrto, $isrelay)) break;
					}else throw new Exception('Can not find any valid ip address for hostname "'.$hname.'", on class SMTP::_sendtohost()', 512);
				}
			}
		}
		return $ret;

	}

	function _splitmsg($longmsg, $approx = 10240){

		$longmsg = str_replace(array(".\r\n", ".\n", ".\r"), ". ".$this->_crlf, $longmsg);
		$msgarr  = explode($this->_crlf, $longmsg);
		$addmsg  = "";
		$arrmsg  = array();
		foreach($msgarr as $inline){
			$addmsg .= $inline.$this->_crlf;
			if(strlen($addmsg) >= $approx){
				$arrmsg[] = $addmsg;
				$addmsg = "";
			}
		}
		if(count($arrmsg) > 0 && $addmsg != "") $arrmsg[] = $addmsg;
		else $arrmsg[] = $longmsg;
		return $arrmsg;

	}

	function _getunique(){
		return md5(microtime(1).$this->_unique++);
	}

	function _putcid($str, $ids){

		$find1 = $repl1 = array();
		foreach($ids as $name => $code){
			$find1[] = "=\"".$name;
			$repl1[] = "=\"cid:".$code;
			$find2[] = "=".$name;
			$repl2[] = "=cid:".$code;
		}
		$res = str_replace($find1, $repl1, $str);
		$res = str_replace($find2, $repl2, $res);
		return $res;

	}

	function _encodemsg($src, $enc){
		$res = '';
		if($enc == "7bit" || $enc == "8bit") $res .= chunk_split($src, $this->_chanklen, $this->_crlf);
		elseif($enc == "base64") $res .= chunk_split(base64_encode($src), $this->_chanklen, $this->_crlf);
		elseif($enc == "quoted-printable") $res .= $this->qpencode($src, $this->_chanklen, $this->_crlf);
		return $res;
	}

	function _writemsg(){

		if(!$this->_fromaddr){
			$fromaddr = ini_get('sendmail_from');
			if($fromaddr == ""){
				if(isset($_SERVER['SERVER_ADMIN']) && FUNC::is_mail($_SERVER['SERVER_ADMIN'])) $fromaddr = $_SERVER['SERVER_ADMIN'];
				elseif(isset($_SERVER['SERVER_NAME'])) $fromaddr = 'postmaster@'.$_SERVER['SERVER_NAME'];
				elseif(isset($_SERVER['HTTP_HOST']))   $fromaddr = 'postmaster@'.$_SERVER['HTTP_HOST'];
				elseif(isset($_SERVER['REMOTE_ADDR'])) $fromaddr = 'postmaster@'.$_SERVER['REMOTE_ADDR'];
				elseif(isset($_SERVER['SERVER_ADDR'])) $fromaddr = 'postmaster@'.$_SERVER['SERVER_ADDR'];
				else $fromaddr = 'postmaster@localhost';
			}
			$this->_fromaddr = array('address' => $fromaddr, 'name' => '');
		}
		if(!$this->_fromhost){
			if(isset($_SERVER['SERVER_NAME']))     $this->_fromhost = $_SERVER['SERVER_NAME'];
			elseif(isset($_SERVER['HTTP_HOST']))   $this->_fromhost = $_SERVER['HTTP_HOST'];
			elseif(isset($_SERVER['REMOTE_ADDR'])) $this->_fromhost = $_SERVER['REMOTE_ADDR'];
			elseif(isset($_SERVER['SERVER_ADDR'])) $this->_fromhost = $_SERVER['SERVER_ADDR'];
			else{
				$fexp = explode('@', $this->_fromaddr['address']);
				$this->_fromhost = $fexp[1];
			}
		}
		$tostr = $ccstr = $bccstr = '';
		foreach($this->_toaddrs as $taddr => $tname){
			if($tname == "") $tostr .= $taddr.', ';
			else $tostr .= '"'.str_replace('"', '\\"', $tname).'" <'.$taddr.'>, ';
		}
		$tostr = $hto = substr($tostr, 0, -2);
		if($this->_ccaddrs && count($this->_ccaddrs) > 0){
			foreach($this->_ccaddrs as $caddr => $cname){
				if($cname == "") $ccstr .= $caddr.', ';
				else $ccstr .= '"'.str_replace('"', '\\"', $cname).'" <'.$caddr.'>, ';
			}
			$ccstr = substr($ccstr, 0, -2);
		}
		if($this->_bccaddrs && count($this->_bccaddrs) > 0){
			foreach($this->_bccaddrs as $baddr => $bname) $bccstr .= $baddr.', ';
			$bccstr = substr($bccstr, 0, -2);
		}
		if($this->_fromaddr['name'] == "") $fromstr = $this->_fromaddr['address'];
		else $fromstr = '"'.str_replace('"', '\\"', $this->_fromaddr['name']).'" <'.$this->_fromaddr['address'].'>';
		$arrval1 = $arrval2 = array();
		$arrval1[] = array('name' => 'From', 'value' => $fromstr);
		$arrval2[] = array('name' => 'From', 'value' => $fromstr);
		$arrval2[] = array('name' => 'To', 'value' => $tostr);
		$arrval2[] = array('name' => 'Subject', 'value' => $this->_subject);
		if($ccstr != ""){
			$arrval1[] = array('name' => 'Cc', 'value' => $ccstr);
			$arrval2[] = array('name' => 'Cc', 'value' => $ccstr);
		}
		if($bccstr != "") $arrval1[] = array('name' => 'Bcc', 'value' => $bccstr);
		$arrval2[] = array('name' => 'Date', 'value' => date('r'));
		if($this->_header && count($this->_header) > 0){
			foreach($this->_header as $hvarr){
				$arrval1[] = $hvarr;
				$arrval2[] = $hvarr;
			}
		}
		$xmail = array('name' => base64_decode('WC1NYWlsZXI='), 'value' => base64_decode('WFBNMiB2LjAuMSA8d3d3LnhwZXJ0bWFpbGVyLmNvbT4='));
		$arrval1[] = $xmail;
		$arrval2[] = $xmail;
		$hval1 = $hval2 = $bval = '';
		foreach($arrval1 as $heach1) $hval1 .= $heach1['name'].': '.$heach1['value'].$this->_crlf;
		foreach($arrval2 as $heach2) $hval2 .= $heach2['name'].': '.$heach2['value'].$this->_crlf;
		$multipart = false;
		if($this->_atext && $this->_ahtml) $multipart = true;
		if($this->_attach && count($this->_attach) > 0) $multipart = true;
		if($multipart){
			$bval .= 'This is a message in MIME Format. If you see this, your mail reader does not support this format.'.$this->_crlf.$this->_crlf;
			$boundary1 = '=_'.$this->_getunique();
			$boundary2 = '=_'.$this->_getunique();
			$boundary3 = '=_'.$this->_getunique();
			$haveatt = ($this->_attach && count($this->_attach) > 0) ? true : false;
			$inline = $attachment = false;
			$idarr = array();
			if($haveatt){
				foreach($this->_attach as $attdesc){
					if($attdesc['disp'] == "inline"){
						$inline = true;
						$fname = $attdesc['name'];
						if(!isset($idarr[$fname])) $idarr[$fname] = $this->_getunique();
					}else $attachment = true;
				}
			}
			$hadd = '';
			if($this->_atext && $this->_ahtml){
				$vhtml = (count($idarr) > 0) ? $this->_putcid($this->_ahtml[2], $idarr) : $this->_ahtml[2];
				if($inline && $attachment){
					$hadd .= 'Content-Type: multipart/mixed;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						'Content-Type: multipart/related;'.$this->_crlf."\t".'boundary="'.$boundary2.'"'.$this->_crlf.$this->_crlf.
						'--'.$boundary2.$this->_crlf.
						'Content-Type: multipart/alternative;'.$this->_crlf."\t".'boundary="'.$boundary3.'"'.$this->_crlf.$this->_crlf.
						'--'.$boundary3.$this->_crlf.
						$this->_atext[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($this->_atext[2], $this->_atext[1]).
						$this->_crlf.'--'.$boundary3.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).
						$this->_crlf.'--'.$boundary3.'--'.$this->_crlf;
					foreach($this->_attach as $attdesc){
						if($attdesc['disp'] == "inline"){
							$bval .= '--'.$boundary2.$this->_crlf.
								'Content-Type: '.$attdesc['mime'].$this->_crlf.
								'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
								'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.
								'Content-ID: <'.$idarr[$attdesc['name']].'>'.$this->_crlf.$this->_crlf.
								$this->_encodemsg($attdesc['source'], $attdesc['encode']);
						}
					}
					$bval .= '--'.$boundary2.'--'.$this->_crlf;
					foreach($this->_attach as $attdesc){
						if($attdesc['disp'] == "attachment"){
							$bval .= '--'.$boundary1.$this->_crlf.
								'Content-Type: '.$attdesc['mime'].$this->_crlf.
								'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
								'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.$this->_crlf.
								$this->_encodemsg($attdesc['source'], $attdesc['encode']);
						}
					}
					$bval .= '--'.$boundary1.'--';
				}elseif($inline){
					$hadd .= 'Content-Type: multipart/related;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						'Content-Type: multipart/alternative;'.$this->_crlf."\t".'boundary="'.$boundary2.'"'.$this->_crlf.$this->_crlf.
						'--'.$boundary2.$this->_crlf.
						$this->_atext[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($this->_atext[2], $this->_atext[1]).
						$this->_crlf.'--'.$boundary2.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).
						$this->_crlf.'--'.$boundary2.'--'.$this->_crlf;
					foreach($this->_attach as $attdesc){
						$bval .= '--'.$boundary1.$this->_crlf.
							'Content-Type: '.$attdesc['mime'].$this->_crlf.
							'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
							'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.
							'Content-ID: <'.$idarr[$attdesc['name']].'>'.$this->_crlf.$this->_crlf.
							$this->_encodemsg($attdesc['source'], $attdesc['encode']);
					}
					$bval .= '--'.$boundary1.'--';
				}elseif($attachment){
					$hadd .= 'Content-Type: multipart/mixed;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						'Content-Type: multipart/alternative;'.$this->_crlf."\t".'boundary="'.$boundary2.'"'.$this->_crlf.$this->_crlf.
						'--'.$boundary2.$this->_crlf.
						$this->_atext[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($this->_atext[2], $this->_atext[1]).
						$this->_crlf.'--'.$boundary2.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).
						$this->_crlf.'--'.$boundary2.'--'.$this->_crlf;
					foreach($this->_attach as $attdesc){
						$bval .= '--'.$boundary1.$this->_crlf.
							'Content-Type: '.$attdesc['mime'].$this->_crlf.
							'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
							'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.$this->_crlf.
							$this->_encodemsg($attdesc['source'], $attdesc['encode']);
					}
					$bval .= '--'.$boundary1.'--';
				}else{
					$hadd .= 'Content-Type: multipart/alternative;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						$this->_atext[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($this->_atext[2], $this->_atext[1]).
						$this->_crlf.'--'.$boundary1.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).
						$this->_crlf.'--'.$boundary1.'--';
				}
			}elseif($this->_atext){
				$hadd .= 'Content-Type: multipart/mixed;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
				$bval .= '--'.$boundary1.$this->_crlf.
					$this->_atext[0].$this->_crlf.$this->_crlf.
					$this->_encodemsg($this->_atext[2], $this->_atext[1]).$this->_crlf;
				foreach($this->_attach as $attdesc){
					$bval .= '--'.$boundary1.$this->_crlf.
						'Content-Type: '.$attdesc['mime'].$this->_crlf.
						'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
						'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.$this->_crlf.
						$this->_encodemsg($attdesc['source'], $attdesc['encode']);
				}
				$bval .= '--'.$boundary1.'--';
			}elseif($this->_ahtml){
				$vhtml = (count($idarr) > 0) ? $this->_putcid($this->_ahtml[2], $idarr) : $this->_ahtml[2];
				if($inline && $attachment){
					$hadd .= 'Content-Type: multipart/mixed;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						'Content-Type: multipart/related;'.$this->_crlf."\t".'boundary="'.$boundary2.'"'.$this->_crlf.$this->_crlf.
						'--'.$boundary2.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).$this->_crlf;
					foreach($this->_attach as $attdesc){
						if($attdesc['disp'] == "inline"){
							$bval .= '--'.$boundary2.$this->_crlf.
								'Content-Type: '.$attdesc['mime'].$this->_crlf.
								'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
								'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.
								'Content-ID: <'.$idarr[$attdesc['name']].'>'.$this->_crlf.$this->_crlf.
								$this->_encodemsg($attdesc['source'], $attdesc['encode']);
						}
					}
					$bval .= '--'.$boundary2.'--'.$this->_crlf;
					foreach($this->_attach as $attdesc){
						if($attdesc['disp'] == "attachment"){
							$bval .= '--'.$boundary1.$this->_crlf.
								'Content-Type: '.$attdesc['mime'].$this->_crlf.
								'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
								'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.$this->_crlf.
								$this->_encodemsg($attdesc['source'], $attdesc['encode']);
						}
					}
					$bval .= '--'.$boundary1.'--';
				}elseif($inline){
					$hadd .= 'Content-Type: multipart/related;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).$this->_crlf;
					foreach($this->_attach as $attdesc){
						$bval .= '--'.$boundary1.$this->_crlf.
							'Content-Type: '.$attdesc['mime'].$this->_crlf.
							'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
							'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.
							'Content-ID: <'.$idarr[$attdesc['name']].'>'.$this->_crlf.$this->_crlf.
							$this->_encodemsg($attdesc['source'], $attdesc['encode']);
					}
					$bval .= '--'.$boundary1.'--';
				}elseif($attachment){
					$hadd .= 'Content-Type: multipart/mixed;'.$this->_crlf."\t".'boundary="'.$boundary1.'"'.$this->_crlf;
					$bval .= '--'.$boundary1.$this->_crlf.
						$this->_ahtml[0].$this->_crlf.$this->_crlf.
						$this->_encodemsg($vhtml, $this->_ahtml[1]).$this->_crlf;
					foreach($this->_attach as $attdesc){
						$bval .= '--'.$boundary1.$this->_crlf.
							'Content-Type: '.$attdesc['mime'].$this->_crlf.
							'Content-Transfer-Encoding: '.$attdesc['encode'].$this->_crlf.
							'Content-Disposition: '.$attdesc['disp'].';'.$this->_crlf."\t".'filename="'.$attdesc['name'].'"'.$this->_crlf.$this->_crlf.
							$this->_encodemsg($attdesc['source'], $attdesc['encode']);
					}
					$bval .= '--'.$boundary1.'--';
				}
			}
			$hadd  .= 'MIME-Version: 1.0';
			$hval1 .= $hadd;
			$hval2 .= $hadd;
		}else{
			if($this->_atext){
				$hval1 .= $this->_atext[0];
				$hval2 .= $this->_atext[0];
				$bval  .= $this->_encodemsg($this->_atext[2], $this->_atext[1]);
			}else{
				$hval1 .= $this->_ahtml[0];
				$hval2 .= $this->_ahtml[0];
				$bval  .= $this->_encodemsg($this->_ahtml[2], $this->_ahtml[1]);
			}
		}
		return array('header' => array('to' => $hto, 'local' => $hval1, 'client' => $hval2.$this->_crlf.$this->_crlf), 'body' => $this->_splitmsg($bval));

	}

	function send($subject){

		$ret = false;
		if(is_string($subject)){
			$subject = FUNC::str_clear($subject);
			$subject = trim($subject);
			if($subject != ""){
				$this->_subject = $this->qpheader($subject);
				$ver = true;
				if(!($this->_toaddrs && count($this->_toaddrs) > 0)){
					$ver = false;
					throw new Exception('You must set "To" e-mail address(es) using function "AddTo()", on class SMTP::send()', 512);
				}
				if(!($this->_atext || $this->_ahtml)){
					$ver = false;
					throw new Exception('You must set the mail message using function(s) "Text() or/and Html()", on class SMTP::send()', 512);
				}
				if($ver){
					if($this->_ccaddrs && count($this->_ccaddrs) > 0){
						$clearcc1 = array();
						foreach($this->_ccaddrs as $ccaddrs1 => $ccname1){
							$vercc1 = true;
							foreach($this->_toaddrs as $toaddrs1 => $toname1){
								if($ccaddrs1 == $toaddrs1) $vercc1 = false;
							}
							if($vercc1) $clearcc1[$ccaddrs1] = $ccname1;
							else throw new Exception('The e-mail address "'.$ccaddrs1.'" appear in To and Cc, on class SMTP::send()', 512);
						}
						$this->_ccaddrs = $clearcc1;
					}
					if($this->_bccaddrs && count($this->_bccaddrs) > 0){
						$clearbcc1 = array();
						foreach($this->_bccaddrs as $bccaddrs1 => $bccname1){
							$verbcc1 = true;
							foreach($this->_toaddrs as $toaddrs2 => $toname2){
								if($bccaddrs1 == $toaddrs2) $verbcc1 = false;
							}
							if($verbcc1) $clearbcc1[$bccaddrs1] = $bccname1;
							else throw new Exception('The e-mail address "'.$bccaddrs1.'" appear in To and Bcc, on class SMTP::send()', 512);
						}
						$this->_bccaddrs = $clearbcc1;
					}
					if($this->_bccaddrs && count($this->_bccaddrs) > 0 && $this->_ccaddrs && count($this->_ccaddrs) > 0){
						$clearbcc2 = array();
						foreach($this->_bccaddrs as $bccaddrs2 => $bccname2){
							$verbcc2 = true;
							foreach($this->_ccaddrs as $ccaddrs2 => $ccname2){
								if($bccaddrs2 == $ccaddrs2) $verbcc2 = false;
							}
							if($verbcc2) $clearbcc2[$bccaddrs2] = $bccname2;
							else throw new Exception('The e-mail address "'.$bccaddrs2.'" appear in Cc and Bcc, on class SMTP::send()', 512);
						}
						$this->_bccaddrs = $clearbcc2;
					}
					$group = $alldom = array();
					foreach($this->_toaddrs as $toaddrs3 => $toname3){
						$exp1 = explode('@', $toaddrs3);
						$group[$exp1[1]][] = $toaddrs3;
						$alldom[] = $toaddrs3;
					}
					if($this->_ccaddrs && count($this->_ccaddrs) > 0){
						foreach($this->_ccaddrs as $ccaddrs3 => $ccname3){
							$exp2 = explode('@', $ccaddrs3);
							$group[$exp2[1]][] = $ccaddrs3;
							$alldom[] = $ccaddrs3;
						}
					}
					if($this->_bccaddrs && count($this->_bccaddrs) > 0){
						foreach($this->_bccaddrs as $bccaddrs3 => $bccname3){
							$exp3 = explode('@', $bccaddrs3);
							$group[$exp3[1]][] = $bccaddrs3;
							$alldom[] = $bccaddrs3;
						}
					}
					$this->_content = $this->_writemsg();
					$success = false;
					foreach($this->_smtpconn as $conntype){
						if(!$success){
							if($conntype == "local"){
								$success = $this->_sendtohost('127.0.0.1', $alldom, false);
							}elseif($conntype == "relay"){
								if($this->_relay) $success = $this->_sendtohost($this->_relay['ip'], $alldom, true);
								else throw new Exception('You must set relay options with function "Relay()" in order to use relay connection, on class SMTP::send()', 512);
							}elseif($conntype == "client"){
								$back1 = $back2 = true;
								foreach($group as $hostname => $domaddrs){
									$back1 = $this->_sendtohost($hostname, $domaddrs, false);
									if(!$back1) $back2 = false;
								}
								$success = $back2;
							}
						}else break;
					}
					$ret = $success;
				}
			}else throw new Exception('Invalid subject value, on class SMTP::send()', 512);
		}else throw new Exception('Invalid subject type value, on class SMTP::send()', 512);
		return $ret;

	}

}

?>