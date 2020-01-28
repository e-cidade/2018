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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matestoque_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_db_almox_classe.php");
require_once("classes/db_db_almoxdepto_classe.php");
require_once("classes/materialestoque.model.php");
require_once("libs/JSON.php");
$oJson                  = new Services_JSON();
$oGet = db_utils::postMemory($_GET);

$oDaoMatMater = db_utils::getDao('matmater');
require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
$sCamposMaterial  = "e69_numero, e69_codnota, e69_dtnota, ";
$sCamposMaterial .= "matestoqueitemnotafiscalmanual.m79_notafiscal, matestoqueitemnotafiscalmanual.m79_data";

$sWhereMaterial   = "    matmater.m60_codmater = {$oGet->codmater}";
$sWhereMaterial  .= "and matestoqueini.m80_codtipo in (1, 3, 12, 15)";
$sWhereMaterial  .= "and (empnota.e69_codnota is not null or matestoqueitemnotafiscalmanual.m79_matestoqueitem is not null)";
$sSqlMaterial     = $oDaoMatMater->sql_query_material_nota(null, $sCamposMaterial, null, $sWhereMaterial);
$rsBuscaMaterial  = $oDaoMatMater->sql_record($sSqlMaterial);
$aDadosNota = db_utils::getCollectionByRecord($rsBuscaMaterial);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<link href="" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #CCCCCC;">

<center>
  <fieldset style="width 700px;">
    <legend><b>Notas Fiscais</b></legend>
    <div id='divGridNotas'>
    </div>
  </fieldset>
</center>
</body>
</html>
<script>

var sNotasMateriais    = '<?php echo $oJson->encode($aDadosNota)?>';
var oGridNota          = new DBGrid('divGridNotas');
oGridNota.nameInstance = 'oGridNota';
var aHeaders           = new Array('Tipo Entrada',
                                   'Número',
                                   'Data');
oGridNota.setCellWidth(new Array('40%', '30%', '30%'));
oGridNota.setCellAlign(new Array('left', 'center', 'center'));
oGridNota.setHeader(aHeaders);
oGridNota.setHeight(250);
oGridNota.show($('divGridNotas'));
oGridNota.clearAll(true);

var aNotasMateriais = eval("("+sNotasMateriais+")");
aNotasMateriais.each(function (oNota, iSeq) {

  var sTipoEntradaMaterial = "Ordem de Compra";
  var iCodigoNota          = oNota.e69_numero;
  var dtDataNota           = oNota.e69_dtnota;

  if (oNota.e69_numero == "") {

    sTipoEntradaMaterial = "Manual";
    iCodigoNota          = oNota.m79_notafiscal;
    dtDataNota           = oNota.m79_data;
  }

  var aLinha = new Array();
  aLinha[0]  = sTipoEntradaMaterial;
  aLinha[1]  = iCodigoNota;
  aLinha[2]  = js_formatar(dtDataNota, 'd');
  oGridNota.addRow(aLinha);
});

oGridNota.renderRows();

</script>