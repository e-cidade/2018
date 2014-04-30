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

require_once("libs/db_conn.php");


function db_parse_ini_file($file, $ProcessSections=false){
  $lines  = file($file);

  if(!$lines) {
    return false;
  }

  $return = Array();
  $inSect = false;
  foreach($lines as $line){
    $line = trim($line);
    if(!$line || $line[0] == "#" || $line[0] == ";")
      continue;
    if($line[0] == "[" && $endIdx = strpos($line, "]")){
      $inSect = substr($line, 1, $endIdx-1);
      continue;
    }
    if(!strpos($line, '=')) // (We don't use "=== false" because value 0 is not valid as well)
      continue;

    $tmp = explode("=", $line, 2);
    if($ProcessSections && $inSect)
      $return[$inSect][trim($tmp[0])] = ltrim($tmp[1]);
    else
      $return[trim($tmp[0])] = ltrim($tmp[1]);
  }
  return $return;
}


$file = $_GET["file"];


if( $file == "bkp-ecidade" ) {

  $date = $_GET["date"];

  if( empty($date) ) {
    echo "Data não informada";
    exit;
  }

  if( !($bkp_conf = db_parse_ini_file("/etc/dbseller/bkp-ecidade.conf")) ) {
    exit;
  }

  $dir = $bkp_conf["db_dirbkp"];

  $file = "{$bkp_conf["db_dirbkp"]}/dump_{$DB_BASE}_{$date}.pgbkp";

  $mime = "application/bzip";
} else {
  $mime = "application/octet-stream";
}

if (file_exists($file)) {
  header('Content-Description: File Transfer');
  header('Content-Type: '.$mime);
  header('Content-Disposition: attachment; filename='.basename($file));
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($file));
  ob_clean();
  flush();
  readfile($file);
  exit;
}
?>