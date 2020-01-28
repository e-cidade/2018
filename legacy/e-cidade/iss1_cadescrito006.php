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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cadescrito_classe.php");
require_once("classes/db_cadescritoresp_classe.php");

$clcadescrito     = new cl_cadescrito;
$clcadescritoresp = new cl_cadescritoresp;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;

if ( isset($excluir) ) {
  
	$sqlerro=false;
  
  db_inicio_transacao();

  /**
   * Verifica se ja nao tem empresa com escritorio
   */
  $oEscrito    = db_utils::getDao('escrito');
  $sSqlEscrito = $oEscrito->sql_query_file(null, 'q10_inscr', null, "q10_numcgm = {$q86_numcgm}");
  $rsEscrito   = db_query($sSqlEscrito);  

  if ( !$rsEscrito ) {

    $erro_msg = 'Erro ao buscar escritórios: \\n' . pg_last_error();
    $sqlerro  = true;
  }

  if ( pg_num_rows($rsEscrito) > 0 ) {

    $oEscrito = db_utils::fieldsMemory($rsEscrito, 0);
    $erro_msg = 'Não é possivel excluir escritório.\\nEscritório vinculado a inscrição: ' . $oEscrito->q10_inscr;
    $sqlerro  = true;
  }

  /**
   * Remove da cadescritoresp 
   */
  if ( !$sqlerro ) {    

    $clcadescritoresp->excluir(null, 'q84_cadescrito='.$q86_numcgm);

    if ( $clcadescritoresp->erro_status == "0" ) {

      $sqlerro  = true;
      $erro_msg = $clcadescritoresp->erro_msg;    
    }
  }
  
  /**
   * Remove da cadescrito 
   */
  if ( !$sqlerro ) {    

    $clcadescrito->excluir($q86_numcgm);

    if ( $clcadescrito->erro_status == "0" ) {
      $sqlerro = true;
    } 

    $erro_msg = $clcadescrito->erro_msg;
  }
   
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
  
} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result = $clcadescrito->sql_record($clcadescrito->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	    <?php include("forms/db_frmcadescrito.php"); ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){

  if($sqlerro==true){

    db_msgbox($erro_msg);

    if($clcadescrito->erro_campo!=""){

      echo "<script> document.form1.".$clcadescrito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadescrito->erro_campo.".focus();</script>";
    };

  } else {

   db_msgbox($erro_msg);
   echo "
    <script>
      function js_db_tranca(){
        parent.location.href='iss1_cadescrito003.php';
      }\n
      js_db_tranca();
    </script>\n
   ";
  }
}

if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.cadescritoresp.disabled=false;
         top.corpo.iframe_cadescritoresp.location.href='iss1_cadescritoresp001.php?db_opcaoal=33&q84_cadescrito=".@$q86_numcgm."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('cadescritoresp');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>