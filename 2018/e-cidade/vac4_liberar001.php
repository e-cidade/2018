<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_vac_vacinalote_classe.php");
include("classes/db_vac_sala_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
$clvac_vacinalote = new cl_vac_vacinalote;
$clvac_sala       = new cl_vac_sala;
$db_opcao         = 1;
$db_botao         = true;
$iDepartamento    = db_getsession("DB_coddepto");
$sSql             = $clvac_sala->sql_query_file("","*",""," vc01_i_unidade=$iDepartamento ");
$rsResult         = $clvac_sala->sql_record($sSql);

//altera exclui inicio
$db_botao1 = false;
if (isset($opcao)) {
	
  $sSql = $clvac_vacinalote->sql_query_matestoque("","*",""," vc15_i_codigo = $vc15_i_codigo ");
  $result1 = $clvac_vacinalote->sql_record($sSql);
  
  if ($clvac_vacinalote->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {

   $db_opcao = 2;
   $db_botao1 = true;

  } else {
  
    if( $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {

     $db_opcao = 3;
     $db_botao1 = true;

    } else {

      if (isset($alterar)) {

        $db_opcao = 2;
        $db_botao1 = true;

      }
    }
  }
}

if (isset($incluir)) {
	
  db_inicio_transacao();
  $clvac_vacinalote->vc15_c_hora  = date("H:i");
  $clvac_vacinalote->vc15_d_data  = date("Y-m-d",db_getsession("DB_datausu"));
  $clvac_vacinalote->vc15_i_logim = DB_getsession("DB_id_usuario");
  $clvac_vacinalote->incluir($vc15_i_codigo);
  db_fim_transacao();
  
} elseif (isset($alterar)) {

	  //[tag]
  //verificar se lote vinculado ja foi utilizado
  
  if (verificaLote($vc15_i_codigo) == 0) {

    db_inicio_transacao();
    $db_opcao = 2;
    $clvac_vacinalote->alterar($vc15_i_codigo);
    db_fim_transacao();

  } else {

    $clvac_vacinalote->erro_status     = "0";
    $clvac_vacinalote->erro_msg = " Já foram efetuadas vacinações nesse lote! ";

  }

} elseif(isset($excluir)) {

  
  if (verificaLote($vc15_i_codigo) == 0) {

    db_inicio_transacao();
    $db_opcao = 3;
    $clvac_vacinalote->excluir($vc15_i_codigo);
    db_fim_transacao();

  } else {

    $clvac_vacinalote->erro_status     = "0";
    $clvac_vacinalote->erro_msg = " Já foram efetuadas vacinações nesse lote! ";

  }
  

} elseif (isset($chavepesquisa)) {

  $db_opcao = 2;
  $result   = $clvac_vacinalote->sql_record($clvac_vacinalote->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
  $db_botao = true;

}
function verificaLote($iCodVacinaLote) {

  $oDaoAplicalote = db_utils::getdao('vac_aplicalote');
  $sSql           = $oDaoAplicalote->sql_query(null,"*",null," vc17_i_vacinalote=$iCodVacinaLote ");
  $oDaoAplicalote->sql_record($sSql);
  return $oDaoAplicalote->numrows;
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");


?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<center>
<?if ($clvac_sala->numrows == 0) {

    echo"<br><br><center><strong><b> Departamento não é um sala de vacinação! </b></strong></center></center></center>";
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;

  }?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?include("forms/db_frmvac_vacinalote.php");?>
    </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form2", "vc15_i_vacina", true, 1, "vc15_i_vacina", true);
</script>
<?
if ((isset($incluir)) || (isset($alterar)) || (isset($excluir))) {

  if ($clvac_vacinalote->erro_status == "0") {
  	
    $clvac_vacinalote->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clvac_vacinalote->erro_campo != "") {
    	
      echo "<script> document.form1.".$clvac_vacinalote->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvac_vacinalote->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $clvac_vacinalote->erro(true,false);
    db_redireciona("vac4_liberar001.php");
    
  }

}
?>