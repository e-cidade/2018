<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_bensmedida_classe.php"));
require_once(modification("classes/db_bensmodelo_classe.php"));
require_once(modification("classes/db_bensmarca_classe.php"));

$oGet = db_utils::postMemory($_GET);

$oDaoBensMedida = db_utils::getDao('bensmedida');
$oDaoBensMarca  = db_utils::getDao('bensmarca');
$oDaoBensModelo = db_utils::getDao('bensmodelo');

$db_opcao = 1;
$db_botao = true;

$lUsaPCASP = "false";
if (USE_PCASP) {
  $lUsaPCASP = "true";
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("estilos.css, grid.style.css");
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js, widgets/messageboard.widget.js");
  db_app::load("classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js");
?>
<style type="text/css">
  .bold {
    font-weight: bold;
  }
  div#fieldsetInclusaoBensGlobal table {
    border-collapse: collapse;
  }
  div#fieldsetInclusaoBensGlobal table tr td{
    padding-top:4px;
    white-space:nowrap;
  }
  div#fieldsetInclusaoBensGlobal table tr td:first-child{
    text-align: left;
    width: 130px;
  }
  /* pega a segunda td */
  div#fieldsetInclusaoBensGlobal table tr td + td{

  }
  /* pega a terceira td */
  div#fieldsetInclusaoBensGlobal table tr td + td + td{
    text-align: right;
    padding-left: 5px;
    width: 100px;
  }
  div#fieldsetInclusaoBensGlobal table tr td + td + td + td{
    text-align: left;
    width: 150px;
  }
  .ancora, legend {
    font-weight: bold;
  }
  .leitura {
    background-color: #DEB887;
  }
</style>
</head>
<body bgcolor="#CCCCCC" onload="js_carregaDadosNota(); js_carregaDadosForm(<?=$db_opcao?>);" >
<div style="margin-top: 25px;" ></div>
<center>
  <div align="center" style="width: 720px; display: block;">
    <?
      include(modification("forms/db_frm_bensglobalnovo.php"));
    ?>
  </div>
</center>
</body>
<script>

  var sUrl = window.location.search;
  var oUrl = null;
  var lViewNotasPendentes = true;
  if (sUrl) {

    oUrl = js_urlToObject(sUrl);
    if (oUrl.iCodigoEmpNotaItem != "") {
      lViewNotasPendentes = false;
    }
  }
  if (lViewNotasPendentes) {

    /**
     * Direciona o usuário para a inclusão de bens Individual ou Global, dependendo
     * da quantidade do item.
     */
    function loadDadosBem(oDadosLinha) {

      var iQuantidadeItem    = oDadosLinha.iQuantidadeItem;
      var iCodigoEmpNotaItem = oDadosLinha.iCodigoEmpNotaItem;

      var sUrlDireciona      = "";
      if (iQuantidadeItem == 1) {
        sUrlDireciona  = "pat1_bens001.php?iCodigoEmpNotaItem="+iCodigoEmpNotaItem;
      } else {
        sUrlDireciona  = "pat1_bensglobalnovo001.php?iCodigoEmpNotaItem="+iCodigoEmpNotaItem;
      }

      if ( oDBViewNotasPendentes.getLocationGlobal() ) {
        window.location = sUrlDireciona;
      } else {
        parent.window.location = sUrlDireciona;
      }
    }

    var oDBViewNotasPendentes = new DBViewNotasPendentes('oDBViewNotasPendentes', <?php echo $lUsaPCASP;?>);
    oDBViewNotasPendentes.setTextoRodape("<b>* Dois cliques sob a linha para carregar o bem</b>");
    oDBViewNotasPendentes.setLocationGlobal(true);
    oDBViewNotasPendentes.setCallBackDoubleClick(loadDadosBem);
    oDBViewNotasPendentes.exibirItemFracionado(false);
    oDBViewNotasPendentes.show();
  }
</script>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>