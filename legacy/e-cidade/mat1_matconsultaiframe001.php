<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_matestoque_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_matparam_classe.php"));
include(modification("classes/db_db_departorg_classe.php"));
include(modification("classes/db_db_almox_classe.php"));
include(modification("classes/db_db_almoxdepto_classe.php"));
require_once(modification("classes/materialestoque.model.php"));
require_once modification("libs/db_app.utils.php");
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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmatparam = new cl_matparam;
$cldb_departorg = new  cl_db_departorg;
$cldb_almox = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;
$clmatestoque = new cl_matestoque;
$clrotulo = new rotulocampo;
$clrotulo->label("");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?//$cor="#999999"?>
.bordas{
    border: 2px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #999999;
}
.bordas_corp{
    border: 1px solid #cccccc;
    border-top-color: #999999;
    border-right-color: #999999;
    border-left-color: #999999;
    border-bottom-color: #999999;
    background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr>
<td  align="center" valign="top" bgcolor="#CCCCCC">

<table border='0'>
<?php
// novo metodo para calculo preço medio
$oMaterialEstoque = new materialEstoque($codmater);
$pr_medio = $oMaterialEstoque->getPrecoMedioMaterial();

//echo $pr_medio;
//$dt_prmedio = date("Y-m-d");

if (isset($codmater)&&$codmater!="") {

  $sSqlTotaisTransferencias  = "select sum(coalesce(case when m81_tipo = 4 then m82_quant end, 0)) as saida";
  $sSqlTotaisTransferencias .= "  from matestoqueinimei ";
  $sSqlTotaisTransferencias .= "       inner join matestoqueitem on m71_codlanc       = m82_matestoqueitem";
  $sSqlTotaisTransferencias .= "       inner join matestoque trans  on m71_codmatestoque = trans.m70_codigo ";
  $sSqlTotaisTransferencias .= "       inner join matestoqueini  on m80_codigo        = m82_matestoqueini ";
  $sSqlTotaisTransferencias .= "       left  join matestoqueinil on m80_codigo        = m86_matestoqueini ";
  $sSqlTotaisTransferencias .= "       inner join matestoquetipo on m80_codtipo       = m81_codtipo ";
  $sSqlTotaisTransferencias .= " where trans.m70_codigo  = matestoque.m70_codigo ";
  $sSqlTotaisTransferencias .= "   and m81_codtipo = 7";
  $sSqlTotaisTransferencias .= "   and m86_matestoqueini IS NULL";
  $sql=$clmatestoque->sql_query_almox(null,"distinct m70_coddepto,descrdepto,m70_quant,
                                      round((m70_quant*$pr_medio),2)::float as m70_valor,
                                      coalesce(({$sSqlTotaisTransferencias}),0) as dl_transferencias",
                                      null,
                                      "m70_codmatmater=$codmater",
                                      "",true);
//  echo "$codmater\n";
  //die($sql);
  db_lovrot($sql,15,"()","","");

}
?>
</table>

</td>
</tr>
</table>
</body>
</html>
