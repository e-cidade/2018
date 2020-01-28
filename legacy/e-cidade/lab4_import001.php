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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$oDaoLabRequi     = db_utils::getdao('lab_requisicao');
$oDaoLabRequiitem = db_utils::getdao('lab_requiitem');
$db_opcao         = 1;
$db_botao         = true;
$iDepartamento    = db_getsession("DB_coddepto");
$iUsuario         = DB_getsession("DB_id_usuario");
$sNomeUsuario     = DB_getsession("DB_login");
$dHoje            = date("Y-m-d",db_getsession("DB_datausu"));

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado 
 */
function laboratorioLogado(){
  
  require_once('libs/db_utils.php');
  $iUsuario = db_getsession('DB_id_usuario');
  $iDepto = db_getsession('DB_coddepto');
  $oLab_labusuario = db_utils::getdao('lab_labusuario');
  $oLab_labdepart  = db_utils::getdao('lab_labdepart');
  $sql             = $oLab_labusuario->sql_query(null, 'la02_i_codigo, la02_c_descr', "la02_i_codigo", 
                                                 "la05_i_usuario = $iUsuario");
  $rResult=$oLab_labusuario->sql_record($sql);
  if ($oLab_labusuario->numrows == 0) {
      
      $sql     = $oLab_labdepart->sql_query(null, 
                                            'la02_i_codigo, la02_c_descr',
                                            "la02_i_codigo",
                                            "la03_i_departamento = $iDepto");
      $rResult = $oLab_labdepart->sql_record($sql);
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
  $sql = $oLab_labresp->sql_query_responsavel(null, 'la06_i_cbo', "", 
                                              "id_usuario = $iUsuario and la06_i_laboratorio=$iLaboratorioLogado ");
  $rResult=$oLab_labresp->sql_record($sql);
  if ($oLab_labresp->numrows == 0) {
      return 0;
  }
  $oLabResp = db_utils::getColectionByRecord($rResult);
  return $oLabResp[0]->la06_i_cbo;
  
}
$iResponsavelTecnico = responsavelTecnico($iLaboratorioLogado);

if (isset($confirma)) {

  $oDaoLabEmissao     = db_utils::getdao('lab_emissao');
  $oDaoLabconferencia = db_utils::getdao('lab_conferencia');
  db_inicio_transacao();
  $aDados = explode(",",$sDados);
  $aCod   = explode(",",$sCod);
  $iTam   = count($aDados);
  for ($iX=0; $iX < $iTam; $iX++) {
  	
    $oDaoLabEmissao->la34_i_forma     = 1;
    $oDaoLabEmissao->la34_i_usuario   = $iUsuario;
    $oDaoLabEmissao->la34_i_requiitem = $aCod[$iX];
    $oDaoLabEmissao->la34_d_data      = $dHoje;
    $oDaoLabEmissao->la34_c_hora      = date('H:i');
    $oDaoLabEmissao->la34_o_laudo     = db_geraArquivoOidfarmacia($_FILES['file'.$aDados[$iX]]['tmp_name'],"",1,$conn);
    $oDaoLabEmissao->la34_c_nomearq   = $_FILES['file'.$aDados[$iX]]['name']; 
	  $oDaoLabEmissao->incluir(null);
  
	  if ($oDaoLabEmissao->erro_status != "0") {
	
      $oDaoLabconferencia->la47_i_requiitem    = $aCod[$iX];
      $oDaoLabconferencia->la47_i_login        = $iUsuario; 
      $oDaoLabconferencia->la47_d_data         = $dHoje;
      $oDaoLabconferencia->la47_c_hora         = date('H:i');
      $oDaoLabconferencia->la47_i_resultado    = 1;
      $oDaoLabconferencia->la47_t_observacao   = "";
      eval("\$oDaoLabconferencia->la47_i_procedimento = \$iproc".$aDados[$iX].";");
		  $oDaoLabconferencia->incluir(null);
		  if ($oDaoLabconferencia->erro_status == "0") {
		  	
		  	$oDaoLabEmissao->erro_status = "0";
        $oDaoLabEmissao->erro_msg    = $oDaoLabconferencia->erro_msg;
        break;
        
		  }
    
	  }else{
		  break;
	  }
    if ($oDaoLabEmissao->erro_status != "0") {
    
      $oDaoLabRequiitem->la21_i_codigo   = $aCod[$iX]; 
      $oDaoLabRequiitem->la21_c_situacao = '6 - importado';
    	$oDaoLabRequiitem->alterar($aCod[$iX]);
      if ($oDaoLabRequiitem->erro_status == "0") {
        
        $oDaoLabEmissao->erro_status = "0";
        $oDaoLabEmissao->erro_msg    = $oDaoLabRequiitem->erro_msg;
        break;
        
      }
    
    } 
  }
	db_fim_transacao($oDaoLabEmissao->erro_status == "0");
  
  
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
<?if ($iResponsavelTecnico == 0) {

  echo"<br><br><center><strong><b> Usuario não é um profissional do laboratorio! ";
  echo"</b></strong></center></center></center>";
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
  exit;

}?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?include("forms/db_frmlab_import.php");?>
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
js_tabulacaoforms("form1", "vc20_d_dataini", true, 1, "vc20_d_dataini", true);
</script>
<?
if (isset($confirma)) {
  if ($oDaoLabEmissao->erro_status == "0") {
    $oDaoLabEmissao->erro(true,false);
  } else {

    $oDaoLabEmissao->erro(true,false);
    db_redireciona("lab4_import001.php");

  }
}
?>