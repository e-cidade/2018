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

class MIME {

	var $_qpkeys = array(
			"\x00","\x01","\x02","\x03","\x04","\x05","\x06","\x07",
			"\x08","\x09","\x0A","\x0B","\x0C","\x0D","\x0E","\x0F",
			"\x10","\x11","\x12","\x13","\x14","\x15","\x16","\x17",
			"\x18","\x19","\x1A","\x1B","\x1C","\x1D","\x1E","\x1F",
			"\x7F","\x80","\x81","\x82","\x83","\x84","\x85","\x86",
			"\x87","\x88","\x89","\x8A","\x8B","\x8C","\x8D","\x8E",
			"\x8F","\x90","\x91","\x92","\x93","\x94","\x95","\x96",
			"\x97","\x98","\x99","\x9A","\x9B","\x9C","\x9D","\x9E",
			"\x9F","\xA0","\xA1","\xA2","\xA3","\xA4","\xA5","\xA6",
			"\xA7","\xA8","\xA9","\xAA","\xAB","\xAC","\xAD","\xAE",
			"\xAF","\xB0","\xB1","\xB2","\xB3","\xB4","\xB5","\xB6",
			"\xB7","\xB8","\xB9","\xBA","\xBB","\xBC","\xBD","\xBE",
			"\xBF","\xC0","\xC1","\xC2","\xC3","\xC4","\xC5","\xC6",
			"\xC7","\xC8","\xC9","\xCA","\xCB","\xCC","\xCD","\xCE",
			"\xCF","\xD0","\xD1","\xD2","\xD3","\xD4","\xD5","\xD6",
			"\xD7","\xD8","\xD9","\xDA","\xDB","\xDC","\xDD","\xDE",
			"\xDF","\xE0","\xE1","\xE2","\xE3","\xE4","\xE5","\xE6",
			"\xE7","\xE8","\xE9","\xEA","\xEB","\xEC","\xED","\xEE",
			"\xEF","\xF0","\xF1","\xF2","\xF3","\xF4","\xF5","\xF6",
			"\xF7","\xF8","\xF9","\xFA","\xFB","\xFC","\xFD","\xFE",
			"\xFF");
	var $_qpvrep = array(
			"=00","=01","=02","=03","=04","=05","=06","=07",
			"=08","=09","=0A","=0B","=0C","=0D","=0E","=0F",
			"=10","=11","=12","=13","=14","=15","=16","=17",
			"=18","=19","=1A","=1B","=1C","=1D","=1E","=1F",
			"=7F","=80","=81","=82","=83","=84","=85","=86",
			"=87","=88","=89","=8A","=8B","=8C","=8D","=8E",
			"=8F","=90","=91","=92","=93","=94","=95","=96",
			"=97","=98","=99","=9A","=9B","=9C","=9D","=9E",
			"=9F","=A0","=A1","=A2","=A3","=A4","=A5","=A6",
			"=A7","=A8","=A9","=AA","=AB","=AC","=AD","=AE",
			"=AF","=B0","=B1","=B2","=B3","=B4","=B5","=B6",
			"=B7","=B8","=B9","=BA","=BB","=BC","=BD","=BE",
			"=BF","=C0","=C1","=C2","=C3","=C4","=C5","=C6",
			"=C7","=C8","=C9","=CA","=CB","=CC","=CD","=CE",
			"=CF","=D0","=D1","=D2","=D3","=D4","=D5","=D6",
			"=D7","=D8","=D9","=DA","=DB","=DC","=DD","=DE",
			"=DF","=E0","=E1","=E2","=E3","=E4","=E5","=E6",
			"=E7","=E8","=E9","=EA","=EB","=EC","=ED","=EE",
			"=EF","=F0","=F1","=F2","=F3","=F4","=F5","=F6",
			"=F7","=F8","=F9","=FA","=FB","=FC","=FD","=FE",
			"=FF");
	var $_qpkstr = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F\x7F\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F\xA0\xA1\xA2\xA3\xA4\xA5\xA6\xA7\xA8\xA9\xAA\xAB\xAC\xAD\xAE\xAF\xB0\xB1\xB2\xB3\xB4\xB5\xB6\xB7\xB8\xB9\xBA\xBB\xBC\xBD\xBE\xBF\xC0\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD6\xD7\xD8\xD9\xDA\xDB\xDC\xDD\xDE\xDF\xE0\xE1\xE2\xE3\xE4\xE5\xE6\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF6\xF7\xF8\xF9\xFA\xFB\xFC\xFD\xFE\xFF";

	function MIME(){
		//
	}

	function is_printable($str){
		return (strcspn($str, $this->_qpkstr) == strlen($str));
	}

	function qpencode($str, $len = 74, $end = "\r\n"){

		$out = '';
        $exp = explode($end, $str);
        foreach($exp as $line){
			$line = str_replace('=', '=3D', $line);
            $line = str_replace($this->_qpkeys, $this->_qpvrep, $line);
            preg_match_all('/.{1,'.$len.'}([^=]{0,2})?/', $line, $match);
            for($i = 0; $i < count($match[0]); $i++){
				$line = (substr($match[0][$i], -1) == " ") ? substr($match[0][$i], 0, -1).'=20' : $match[0][$i];
                if(($i+1) < count($match[0])) $line .= '=';
                $out .= $line.$end;
            }
        }
        if($out == '') return '';
        return substr($out, 0, -(strlen($end)));

    }

	function qpheader($str, $charset = "iso-8859-1"){

		if($this->is_printable($str)) return $str;
		else{
			$vquoted = $this->qpencode($str);
			$vquoted = str_replace('?', '=3F', $vquoted);
			return '=?'.$charset.'?Q?'.$vquoted.'?=';
		}

	}

}

?>