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

require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$iIdUsuario     = db_getsession('DB_id_usuario');
$iAnoUsu        = db_getsession('DB_anousu');
$iDptoUsu       = db_getsession('DB_coddepto');

$sWhereUsuarios = "";
$sAnd           = "";
if (isset($oGet->listausuarios) && !empty($oGet->listausuarios)) {
  
  $sWhereUsuarios .= "{$sAnd} db_usupermemp.db21_id_usuario in ({$oGet->listausuarios})";
  $sAnd            = " and ";
}

if (isset($oGet->anousu) && !empty($oGet->anousu)) {
	
	$sWhereUsuarios .= "{$sAnd} db_permemp.db20_anousu = {$oGet->anousu}";
	$sAnd            = " and ";
}

if (isset($sWhereUsuarios) && !empty($sWhereUsuarios)) {
	$sWhereUsuarios  = " where {$sWhereUsuarios}                                                                        ";
} else {
	$sWhereUsuarios  = " where db_usupermemp.db21_id_usuario = {$iIdUsuario}                                            ";
	$sWhereUsuarios .= "   and db_permemp.db20_anousu        = {$iAnoUsu}                                               ";
}

$sWhereDeptos   = "";
$sInnerDeptos   = "";
$sAnd           = "";
if (isset($oGet->listadptos) && !empty($oGet->listadptos)) {
  
  $sWhereDeptos .= "{$sAnd} db_depusuemp.db22_coddepto in ({$oGet->listadptos})";
  $sAnd          = " and ";
}

if (isset($oGet->listainstit) && !empty($oGet->listainstit)) {
  
	$sInstit       = str_replace('-',',',$oGet->listainstit);
  $sWhereDeptos .= "{$sAnd} db_depart.instit in ({$sInstit})";
  $sAnd          = " and ";
}

if (isset($oGet->anousu) && !empty($oGet->anousu)) {
  
  $sWhereDeptos .= "{$sAnd} db_permemp.db20_anousu = {$oGet->anousu}";
  $sAnd          = " and ";
}

if (isset($oGet->listafiltros) && !empty($oGet->listafiltros)) {
  
	if ($oGet->listafiltros != 'geral') {
		
	  $clselorcdotacao = new cl_selorcdotacao();
	  $clselorcdotacao->setDados($oGet->listafiltros);  
	  
	  $sSelOrcDotacao  = $clselorcdotacao->getDados();
	  $sSelOrcDotacao  = substr($sSelOrcDotacao,4);
    $sSelOrcDotacao  = str_replace('e.o56_elemento','db_permemp.db20_codele',$sSelOrcDotacao);
	  $sSelOrcDotacao  = str_replace('w.','db_permemp.',$sSelOrcDotacao);
	  $sSelOrcDotacao  = str_replace('o58_','db20_',$sSelOrcDotacao);

	  $sWhereDeptos   .= " {$sSelOrcDotacao} ";	
	}
}

if (isset($sWhereDeptos) && !empty($sWhereDeptos)) {
  $sWhereDeptos  = " where {$sWhereDeptos}                                                                            ";
} else {
	
  $sWhereDeptos  = " where db_depusuemp.db22_coddepto = {$iDptoUsu}                                                   ";
  $sWhereDeptos .= "   and db_permemp.db20_anousu     = {$iAnoUsu}                                                    ";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <fieldset>
        <legend><b>Permissões do Usuário:</b></legend>
        <?
          $sCampos       = " db_usuarios.id_usuario,db_usuarios.nome,db_permemp.db20_orgao,db_permemp.db20_unidade,   ";
          $sCampos      .= " db_permemp.db20_funcao,db_permemp.db20_subfuncao,db_permemp.db20_programa,               ";
          $sCampos      .= " db_permemp.db20_projativ,db_permemp.db20_codele,db_permemp.db20_codigo,                  ";
          $sCampos      .= " db_permemp.db20_tipoperm                                                                 ";
          
          $sSqlUsuarios  = "  select {$sCampos}                                                                       ";
          $sSqlUsuarios .= "    from db_usupermemp                                                                    ";
          $sSqlUsuarios .= "         inner join db_permemp on db_permemp.db20_codperm = db_usupermemp.db21_codperm    ";
          $sSqlUsuarios .= "         inner join db_usuarios on db_usuarios.id_usuario = db_usupermemp.db21_id_usuario ";
          $sSqlUsuarios .= "         {$sWhereUsuarios}                                                                ";
          $sSqlUsuarios .= "order by db_usuarios.id_usuario,db_usuarios.nome                                          ";

          db_lovrot($sSqlUsuarios,15,"","%","_self","","permusuario");
        ?>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>  
  <tr>
    <td>
      <fieldset>
        <legend><b>Permissões do Departamento:</b></legend>
        <?
          $sCampos           = " db_depart.coddepto,db_depart.descrdepto,db_permemp.db20_orgao,                       ";
          $sCampos          .= " db_permemp.db20_unidade,db_permemp.db20_funcao,db_permemp.db20_subfuncao,            ";
          $sCampos          .= " db_permemp.db20_programa,db_permemp.db20_projativ,db_permemp.db20_codele,            ";
          $sCampos          .= " db_permemp.db20_codigo,db_permemp.db20_tipoperm                                      ";
          
          $sSqlDepartamento  = "  select {$sCampos}                                                                   ";
          $sSqlDepartamento .= "    from db_depusuemp                                                                 ";
          $sSqlDepartamento .= "         inner join db_permemp on db_permemp.db20_codperm = db_depusuemp.db22_codperm "; 
          $sSqlDepartamento .= "         inner join db_depart  on db_depart.coddepto      = db_depusuemp.db22_coddepto";
          $sSqlDepartamento .= "         {$sWhereDeptos}                                                              ";
          $sSqlDepartamento .= "order by db_depart.coddepto,db_depart.descrdepto                                      ";

          db_lovrot($sSqlDepartamento,15,"","%","_self","","permdpto");
        ?>
      </fieldset>
    </td>
  </tr>
</table>
</body>
</html>