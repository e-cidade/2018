<?
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("libs/db_liborcamento.php"));
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
(string)$sRelNome = '';

$iAnoUsu = db_getsession("DB_anousu");
$sLabelMsg = "Anexo VII - Dem. de Restos a Pagar";

$oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
$lPrefeitura  = $oInstituicao->prefeitura();

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name="form1" method="post" action="">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan=3  class='table_header'>
        <?=$sLabelMsg?>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <legend><b>Filtros</b></legend>
          <table  align="center">
            <tr style="<?php echo $lPrefeitura ? '' : 'display:none;'?>">
              <td align="center" colspan="3" id="ctnInstituicao">
                <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
              </td>
            </tr>
            <tr>
              <td colspan=2 nowrap><b><label for="o116_periodo">Período:</label></b>
                <?
                if ($iAnoUsu < 2010) {
                  ?>
                  <select name="o116_periodo">
                    <option value="1B">Primeiro Bimestre </option>
                    <option value="2B">Segundo  Bimestre </option>
                    <option value="3B">Terceiro Bimestre </option>
                    <option value="4B">Quarto   Bimestre </option>
                    <option value="5B">Quinto   Bimestre </option>
                    <option value="6B">Sexto    Bimestre </option>
                  </select>
                  <?
                } else {

                  $oRelatorio = new relatorioContabil(97, false);
                  $aPeriodos = $oRelatorio->getPeriodos();
                  $aListaPeriodos = array();
                  foreach ($aPeriodos as $oPeriodo) {
                    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                  }
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
                }
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <table align="center">
          <tr>
            <td align="left">
              <br />
              <input  name="Imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite();">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>

<script>
  var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnInstituicao'));
  oViewInstituicao.show();

  var variavel = 1;
  var iAnoSessao = <?php echo db_getsession('DB_anousu'); ?>;

  function js_buscaEdicaoLrf(iAnousu,sFontePadrao){
    var url       = 'con4_lrfbuscaedicaoRPC.php';
    var parametro = 'ianousu='+iAnousu+'&sfontepadrao='+sFontePadrao ;
    var objAjax   = new Ajax.Request (url, { method:'post',
      parameters:parametro,
      onComplete:js_setNomeArquivo}
    );
  }

  function js_setNomeArquivo(oResposta){
    sNomeArquivoEdicao = oResposta.responseText;
  }

  js_buscaEdicaoLrf(iAnoSessao, "con2_lrfdemonstrativorp002");

  function js_emite(){

    var aInstituicoes = oViewInstituicao.getInstituicoesSelecionadas(true);

    sel_instit  = oViewInstituicao.getInstituicoesSelecionadas(true).join('-');
    $('db_selinstit').value = sel_instit;
    if(aInstituicoes.length == 0){
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
    }

    if (iAnoSessao <= 2016) {

      jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      document.form1.target = 'safo' + variavel++;
      document.form1.action = sNomeArquivoEdicao;
      setTimeout("document.form1.submit()",1000);
      return true;
    } else {

      var sPrograma = "con2_emissaoAnexoVII002.php?";
      sPrograma += "instituicoes="+sel_instit;
      sPrograma += "&periodo="+$F('o116_periodo');
      var oJanela = window.open(sPrograma, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);
    }


  }
</script>