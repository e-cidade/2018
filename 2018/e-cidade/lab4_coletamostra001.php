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
include("classes/db_lab_coletaitem_classe.php");
include("classes/db_lab_requiitem_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$cllab_coletaitem = new cl_lab_coletaitem;
$cllab_requiitem = new cl_lab_requiitem;

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

$db_opcao = 1;
$db_botao = true;
if((isset($incluir))||(isset($falta))){
  if(!isset($avisa)){
  	 $la32_i_avisapaciente=1;
  }else{
  	 $la32_i_avisapaciente=2;
  }
  db_inicio_transacao();
     $aVet=explode("/",$la32_d_data);
     $cllab_coletaitem->la32_d_data=$la32_d_data=$aVet[2]."-".$aVet[1]."-".$aVet[0];
     if($la32_d_entrega!=""){
       $aVet=explode("/",$la32_d_entrega);
       $cllab_coletaitem->la32_d_entrega=$la32_d_entrega=$aVet[2]."-".$aVet[1]."-".$aVet[0];
     }
     $cllab_coletaitem->la32_i_avisapaciente=$la32_i_avisapaciente;
     $cllab_coletaitem->la32_d_entrega=$la32_d_entrega;
     $cllab_coletaitem->la32_i_requiitem=$rad_exame;
     $cllab_coletaitem->la32_i_usuario=db_getsession("DB_id_usuario");
     
     $cllab_coletaitem->incluir($la32_i_codigo);
     
     if($cllab_coletaitem->erro_status!="0"){
         
     	 if(isset($falta)){
     	 	$cllab_requiitem->la21_c_situacao="f - falta material";
     	 }else{
     	 	$cllab_requiitem->la21_c_situacao="6 - Coletado";
     	 }
     	 
     	 $cllab_requiitem->la21_i_codigo=$rad_exame;
         $cllab_requiitem->alterar($rad_exame);
         
         if($cllab_requiitem->erro_status=="0"){
               
              $cllab_coletaitem->erro_status=0;
              $cllab_coletaitem->erro_sql   = $cllab_requiitem->erro_sql;
              $cllab_coletaitem->erro_campo = $cllab_requiitem->erro_campo;
              $cllab_coletaitem->erro_banco = $cllab_requiitem->erro_banco;
              $cllab_coletaitem->erro_msg   = $cllab_requiitem->erro_msg;
              
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
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlab_coletaitem.php");
	?>
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
js_tabulacaoforms("form1","la22_i_codigo",true,1,"la22_i_codigo",true);
</script>
<?
if((isset($incluir))||(isset($falta))){
  if($cllab_coletaitem->erro_status=="0"){
    $cllab_coletaitem->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_coletaitem->erro_campo!=""){
      echo "<script> document.form1.".$cllab_coletaitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_coletaitem->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_coletaitem->erro(true,false);
    db_redireciona("lab4_coletamostra001.php?la22_i_codigo=".$la22_i_codigo."&z01_v_nome=$z01_v_nome");
  }
}
?>