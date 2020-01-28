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


require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "classes/db_veiculos_classe.php";
require_once "classes/db_veicresp_classe.php";
require_once "classes/db_veicpatri_classe.php";
require_once "classes/db_veicparam_classe.php";
require_once "classes/db_veicbaixa_classe.php";
require_once "classes/db_veiculoscomb_classe.php";
require_once "classes/db_veictipoabast_classe.php";

$oDaoVeiculos = new cl_veiculos;
$oGet         = db_utils::postMemory($_GET);

/*
 * valida os valores passados por get e faz a manipulação
 * da query através dos mesmos
 */
$iCodigoInstituicao = db_getsession("DB_instit");
$aWhere[]           = "(instit = {$iCodigoInstituicao})";
if (isset($oGet->iVeiculo) && trim($oGet->iVeiculo) != "") {
  $aWhere[] = " ve01_codigo = {$oGet->iVeiculo}";
}

if (isset($oGet->sPlaca) && trim($oGet->sPlaca) != "") {
  $aWhere[] = " ve01_placa like '{$oGet->sPlaca}%'";
}

if (isset($oGet->iCentral) && trim($oGet->iCentral) != '') {
  $aWhere[] .= " ve40_veiccadcentral = {$oGet->iCentral}";
}
$sWhere        = implode(" and ", $aWhere);
$slistaCampos  = "distinct ve01_codigo,ve01_placa,ve20_descr,ve21_descr,"; 
$slistaCampos .= "ve22_descr,ve23_descr,ve01_chassi,ve01_certif,ve01_anofab,ve01_anomod";

$funcao_js     = 'carregaVeiculo|ve01_codigo';

/*
 * busca a query (strign) da consulta para executar dentro do db_lovrot
 */
$sSqlVeiculos  = $oDaoVeiculos->sql_query(null, $slistaCampos, 've01_codigo', $sWhere);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript">
  function carregaVeiculo(iCod) {
  
    var iWidth = document.body.scrollWidth-100;
    var sUrl = 'vei3_veiculos002.php?veiculo='+iCod;

	  parent.func_veiculo.hide();
    js_OpenJanelaIframe('top.corpo','func_veiculo_detalhes', sUrl,'Consulta de Veículos',true, 20, 0, iWidth);
  }
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td>

        <?php 
          db_lovrot($sSqlVeiculos, 15, "()", "%", $funcao_js, "", "NoMe", array(), true);
        ?>
  
      </td>
    </tr>
  </table>

</body>