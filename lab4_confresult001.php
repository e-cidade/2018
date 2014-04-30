<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_lab_conferencia_classe.php");
include("classes/db_lab_requiitem_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$cllab_conferencia = new cl_lab_conferencia;
$cllab_requiitem = new cl_lab_requiitem;
$login = DB_getsession("DB_id_usuario");
$db_opcao = 1;
$db_botao = true;

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado 
 */
function laboratorioLogado(){
  
  require_once('libs/db_utils.php');
  $iUsuario = db_getsession('DB_id_usuario');
  $iDepto = db_getsession('DB_coddepto');
  $oLab_labusuario = db_utils::getdao('lab_labusuario');
  $oLab_labdepart = db_utils::getdao('lab_labdepart');
  $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", " la05_i_usuario = $iUsuario");
  $rResult=$oLab_labusuario->sql_record($sql);
  if ($oLab_labusuario->numrows == 0) {
      
  	  $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", " la03_i_departamento = $iDepto");
  	  $rResult=$oLab_labdepart->sql_record($sql);
      if ($oLab_labdepart->numrows == 0) {
      	  return 0;
      }
  }
  $oLab = db_utils::getColectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
  
}
$iLaboratorioLogado = laboratorioLogado();


/**
 * Função que retorna se o usuario é um tecnico responsavel
 * @return logico verdadeiro indica se o usuario é um profissinal 
 */
function responsavelTecnico($iLaboratorioLogado){
  
  require_once('libs/db_utils.php');
  $iUsuario = db_getsession('DB_id_usuario');
  $oLab_labresp = db_utils::getdao('lab_labresp');
  $sql = $oLab_labresp->sql_query_responsavel(null,'la06_i_cbo',"", " id_usuario = $iUsuario and la06_i_laboratorio=$iLaboratorioLogado ");
  $rResult=$oLab_labresp->sql_record($sql);
  if ($oLab_labresp->numrows == 0) {
      return 0;
  }
  $oLabResp = db_utils::getColectionByRecord($rResult);
  return $oLabResp[0]->la06_i_cbo;
  
}
$iResponsavelTecnico = responsavelTecnico($iLaboratorioLogado);

if($iResponsavelTecnico==0){
    db_msgbox("Usuario não é um profissional do laboratorio");
	$db_botao = false;
}


if(isset($incluir)){
  db_inicio_transacao();
  $cllab_conferencia->la47_d_data = date('Y-m-d',db_getsession("DB_datausu"));
  $cllab_conferencia->la47_c_hora = db_hora();
  $cllab_conferencia->la47_i_login =db_getsession("DB_id_usuario");
  $cllab_conferencia->la47_i_requiitem=$la47_i_requiitem;
  $cllab_conferencia->incluir($la47_i_codigo);
  if($cllab_conferencia->erro_status!="0"){
    	
       if($la47_i_resultado==1){
  	       $cllab_requiitem->la21_c_situacao="7 - Conferido";
       }else{
       	   $cllab_requiitem->la21_c_situacao="6 - Coletado";
       }
       $cllab_requiitem->la21_i_codigo=$la47_i_requiitem;
       $cllab_requiitem->alterar($la47_i_requiitem);
       if($cllab_requiitem->erro_status=="0"){
                       
          $cllab_conferencia->erro_status="0";
    	  $cllab_conferencia->erro_sql   = $cllab_requiitem->erro_sql;
          $cllab_conferencia->erro_campo = $cllab_requiitem->erro_campo;
          $cllab_conferencia->erro_banco = $cllab_requiitem->erro_banco;
          $cllab_conferencia->erro_msg   = $cllab_requiitem->erro_msg;
                     
       }
       
  }
  db_fim_transacao();
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
<center>
<br><br>
<?if($iLaboratorioLogado==0){ ?>
    <table width='100%'>
      <tr>
        <td align='center'>
          <br><br>
          <font color='#FF0000' face='arial'>
            <b>Usuário ou departamento não consta como laboratório!<br>
            </b>
          </font>
        </td>
      </tr>
    </table>
    </center>
    <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;
  }?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style='width: 75%;'> <legend><b>Conferência de Exames</b></legend>
	<?
	include("forms/db_frmlab_conferencia.php");
	?>
	</fieldset>
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
js_tabulacaoforms("form1","la15_c_descr",true,1,"la15_c_descr",true);
</script>
<?
if(isset($incluir)){
  if($cllab_conferencia->erro_status=="0"){
    $cllab_conferencia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_conferencia->erro_campo!=""){
      echo "<script> document.form1.".$cllab_conferencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_conferencia->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_conferencia->erro(true,true);
  }
}
?>