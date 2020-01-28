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
include("classes/db_lab_requisicao_classe.php");
include("classes/db_lab_autoriza_classe.php");
include("classes/db_lab_requiitem_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);
$cllab_requisicao = new cl_lab_requisicao;
$cllab_autoriza = new cl_lab_autoriza;
$cllab_requiitem = new cl_lab_requiitem;

if(!isset($la48_d_data)){
  $la48_d_data     = date('Y-m-d',db_getsession("DB_datausu"));
  $la48_d_data_dia = date('d',db_getsession("DB_datausu"));
  $la48_d_data_mes = date('m',db_getsession("DB_datausu"));
  $la48_d_data_ano = date('Y',db_getsession("DB_datausu"));
}

$db_opcao = 1;
$db_botao = true;

/** para descobrir o laboratorio que o usuario esta logado
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

if(isset($incluir)){
  db_inicio_transacao();
  $cllab_autoriza->la48_i_requisicao = $la22_i_codigo;
  $cllab_autoriza->la48_d_data = date('Y-m-d',db_getsession("DB_datausu"));
  $cllab_autoriza->la48_c_hora = db_hora();
  $cllab_autoriza->la48_i_usuario =db_getsession("DB_id_usuario");
  $cllab_autoriza->incluir($la48_i_codigo);
  if($cllab_autoriza->erro_status!="0"){
     
  	 $cllab_requisicao->la22_i_autoriza = 2;  
     $cllab_requisicao->alterar($la22_i_codigo);
     if($cllab_requiitem->erro_status=="0"){
                       
          $cllab_autoriza->erro_status="0";
    	  $cllab_autoriza->erro_sql   = $cllab_requiitem->erro_sql;
          $cllab_autoriza->erro_campo = $cllab_requiitem->erro_campo;
          $cllab_autoriza->erro_banco = $cllab_requiitem->erro_banco;
          $cllab_autoriza->erro_msg   = $cllab_requiitem->erro_msg;
                     
     }
  }
  if($cllab_autoriza->erro_status!="0"){
    	
     $sSql=$cllab_requiitem->sql_query(""," la21_i_codigo ",""," la21_i_requisicao=$la22_i_codigo ");
     $rResult=$cllab_requiitem->sql_record($sSql);
     $cllab_requiitem->la21_c_situacao="8 - Autorizado";
     $iLinhas=$cllab_requiitem->numrows;
     for($x=0;$x<$iLinhas;$x++){
     	
  	 	 db_fieldsmemory($rResult,$x);
  	 	 $cllab_requiitem->la21_i_codigo=$la21_i_codigo;
         $cllab_requiitem->alterar($la21_i_codigo);
         if($cllab_requiitem->erro_status=="0"){
                       
             $cllab_autoriza->erro_status="0";
    	     $cllab_autoriza->erro_sql   = $cllab_requiitem->erro_sql;
             $cllab_autoriza->erro_campo = $cllab_requiitem->erro_campo;
             $cllab_autoriza->erro_banco = $cllab_requiitem->erro_banco;
             $cllab_autoriza->erro_msg   = $cllab_requiitem->erro_msg;
                     
         }
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
    <fieldset style='width: 75%;'> <legend><b>Autorização</b></legend>
	<?
	include("forms/db_frmlab_autoriza.php");
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
js_tabulacaoforms("form1","la23_c_descr",true,1,"la23_c_descr",true);
</script>
<?
if(isset($incluir)){
  if($cllab_autoriza->erro_status=="0"){
    $cllab_autoriza->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_autoriza->erro_campo!=""){
      echo "<script> document.form1.".$cllab_autoriza->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_autoriza->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_autoriza->erro(true,true);
  }
}
?>