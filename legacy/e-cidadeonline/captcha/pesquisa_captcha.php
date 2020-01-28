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

session_start();

include 'securimage.php';
$img = new securimage();
$get  = $img->getCode();
$chek = $img->checkCode();

if ( !isset($_GET["code"]) ) {
  exit;
}

if ( !session_is_registered("DB_processacaptcha") ) {  
      $processa = true;
} else {
  if ( $_SESSION["DB_processacaptcha"]== true ) {
    $processa = true;
  } else {
    $processa = false;
    $_SESSION["DB_processacaptcha"] = true;
  }
}

//checa se o código digitado é igual ao do captcha
if($processa==true) {
  
  if ( $img->check($code) ) {
    echo "true";
  } else {
    echo "false";
  }
}

?>