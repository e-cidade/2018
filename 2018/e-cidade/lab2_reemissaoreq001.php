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
include("classes/db_lab_requiitem_classe.php");
include("classes/db_lab_laboratorio_classe.php");
include("classes/db_lab_exame_classe.php");
include("classes/db_lab_setorexame_classe.php");
include("classes/db_lab_medico_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$cllab_requisicao  = new cl_lab_requisicao;
$cllab_requiitem   = new cl_lab_requiitem;
$cllab_laboratorio = new cl_lab_laboratorio;
$cllab_exame       = new cl_lab_exame;
$cllab_setorexame  = new cl_lab_setorexame;
$cllab_medico      = new cl_lab_medico;
$departamento=db_getsession("DB_coddepto");

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

$db_opcao = 3;
$db_botao = true;
 if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cllab_requisicao->sql_record($cllab_requisicao->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
      $sSql=$cllab_requisicao->sql_query_requiitem("","lab_requiitem.*,lab_exame.*,lab_laboratorio.*",""," la21_i_requisicao=$chavepesquisa ");
      $result = $cllab_requisicao->sql_record($sSql);
      $alinhasgrid=Array();
      for($x=0;$x<$cllab_requisicao->numrows;$x++){
          db_fieldsmemory($result,$x);
          //montar array com linhas do grid
          $aData=explode("-",$la21_d_data);
          $alinhasgrid[$x]="$la21_i_setorexame#$la02_c_descr#$la08_c_descr#".$aData[2]."/".$aData[1]."/".$aData[0]."#$la21_c_hora#$la08_i_dias#$la21_i_emergencia";
      }
      $result = $cllab_medico->sql_record($cllab_medico->sql_query(""," la38_i_medico,z01_nome as  la22_c_medico,sd03_i_crm ",""," la38_i_requisicao = $chavepesquisa "));
      if( $cllab_medico->numrows>0){
          db_fieldsmemory($result,0);
      }
   $db_botao = true;
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
<br><br></br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlab_requisicao001.php");
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
js_tabulacaoforms("form1","la22_i_laboratorio",true,1,"la22_i_laboratorio",true);
</script>