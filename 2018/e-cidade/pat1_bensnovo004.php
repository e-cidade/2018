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

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clBensMedida     = new cl_bensmedida;
$clBensMarca      = new cl_bensmarca;
$clBensModelo     = new cl_bensmodelo;

$db_opcao = 1;
$db_botao = false;

$lUsaPCASP = "false";
if (USE_PCASP) {
    $lUsaPCASP = "true";
}

$lMostraViewNotasPendentes = 'true';
$iCodigoNota               = '0';
if (isset($oGet->iCodigoEmpNotaItem) && !empty($oGet->iCodigoEmpNotaItem)) {

  $db_opcao = 1;
  $db_botao = true;
	$lMostraViewNotasPendentes = 'false';
	$iCodigoNota               = $oGet->iCodigoEmpNotaItem;
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<?php
  db_app::load("scripts.js, prototype.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js");
  db_app::load("estilos.css, grid.style.css, classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js");
?>
<style type="text/css">
  .bold {
    font-weight: bold;
  }

  #fieldsetBensNovo table {
    border-collapse: collapse;
  }

  #fieldsetBensNovo table tr td{
    padding-top:4px;
    white-space:nowrap;
  }

  #fieldsetBensNovo table tr td:first-child{
    text-align: left;
    width: 130px;
  }

  /* pega a segunda td */
  #fieldsetBensNovo table tr td + td{

  }

  /* pega a terceira td */
  #fieldsetBensNovo table tr td + td + td{
    text-align: right;
    padding-left: 5px;
    width: 100px;
  }

  #fieldsetBensNovo table tr td + td + td + td{
    text-align: left;
    width: 150px;
  }

  .ancora {
    font-weight: bold;
  }

  .readOnly {
    backgroud-color: #DEB887;
  }

</style>
</head>
<body bgcolor="#CCCCCC" onload="js_carregaDadosForm(<?=$db_opcao?>);" >
<div style="margin-top: 25px;" ></div>
<center>
  <div align="center" style="width: 720px; display: block;">
    <?php
      include(modification("forms/db_frm_bensnovo.php"));
    ?>
  </div>
</center>
</body>
</html>

<script>

  lMostraViewNotasPendentes = <?php echo $lMostraViewNotasPendentes;?>;

  if (lMostraViewNotasPendentes == true) {

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
    oDBViewNotasPendentes.setCallBackDoubleClick(loadDadosBem);
    oDBViewNotasPendentes.setTextoRodape("<b>* Dois cliques sob a linha para carregar o bem</b>");
    oDBViewNotasPendentes.exibirItemFracionado(false);
	  oDBViewNotasPendentes.show();

  } else {

		var oParam             = new Object();
    oParam.exec            = "getDadosItemNota";
    oParam.iCodigoItemNota = <?php echo $iCodigoNota;?>;

    js_divCarregando(_M('patrimonial.patrimonio.db_frm_bensnovo.carregando'), "msgBox");
    var oAjax   = new Ajax.Request("pat1_bensnovo.RPC.php",
														        {method: 'post',
														         parameters: 'json='+Object.toJSON(oParam),
														         onComplete: js_preencheFormulario
														        });

  }

  function js_preencheFormulario(oAjax) {

		js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {

      oRetorno = oRetorno.aNotas[0];

      $("t52_dtaqu").value   = js_formatar(oRetorno.e69_dtnota, 'd');
      $("t52_numcgm").value  = oRetorno.e60_numcgm;
      $("z01_nome").value    = oRetorno.z01_nome;
      $("vlAquisicao").value = oRetorno.e72_valor;
      $("t52_descr").value   = oRetorno.pc01_descrmater;
      $("iCodigoItemNota").value = <?php echo $iCodigoNota;?>;

      $("t52_dtaqu").style.backgroundColor   = '#DEB887';
      $("t52_numcgm").style.backgroundColor  = '#DEB887';
      $("z01_nome").style.backgroundColor    = '#DEB887';
      $("vlAquisicao").style.backgroundColor = '#DEB887';
      $('tdFornecedor').innerHTML            = "<b>Fornecedor:</b>";

      $("t52_dtaqu").readOnly   = true;
      $("t52_numcgm").readOnly  = true;
      $("z01_nome").readOnly    = true;
      $("vlAquisicao").readOnly = true;
    }
  }
</script>

<?
if(isset($incluir)) {

  if (trim(@$erro_msg) != "") {
       db_msgbox($erro_msg);
  }
  if($sqlerro == true) {

    if($clbens->erro_campo != "") {

      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    }
  } else {
    db_redireciona("pat1_bensglobal001.php?".$parametros."liberaaba=true&chavepesquisa=$t52_bem");
  }
}
?>