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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_liborcamento.php");
require_once modification("model/relatorioContabil.model.php");

$oGet = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('o116_periodo');
$oRelatorio = new relatorioContabil($oGet->codrel);

$anousu = db_getsession('DB_anousu');

(string)$sRelName = '';

$sLabelMsg = "Anexo IV - Dem. das Receitas e Despesas Previd. dos RPPS";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
  <form name="form1" method="post" action="con2_lrfmde002.php" >
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
            <table align="center">
              <!--
			     esse relatorio � somente do rpps
			    <tr>
			        <td align="center" colspan="3">
			       <? db_selinstit('',300,100); ?>
			      </td>
			    </tr>
			    -->
              <tr>
                <td colspan=2 nowrap><b>Per�odo :</b>
                  <?
                  if ($anousu < 2010 ) {

                    $aListaPeriodos = array(
                      "1B" => "1 � Bimestre",
                      "2B" => "2 � Bimestre",
                      "3B" => "3 � Bimestre",
                      "4B" => "4 � Bimestre",
                      "5B" => "5 � Bimestre",
                      "6B" => "6 � Bimestre",
                    );
                  } else {

                    $aPeriodos = $oRelatorio->getPeriodos();
                    $aListaPeriodos = array();
                    $aListaPeriodos[0] = "Selecione";
                    foreach ($aPeriodos as $oPeriodo) {
                      $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                    }
                  }
                  db_select("o116_periodo", $aListaPeriodos, true, 1);
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>

        </td>
      </tr>
    </table>
    <br/>
    <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
  </form>
</div>
</body>
</html>
<script>
  var variavel = 1;
  var iAnoSessao = <?php echo $anousu; ?>;

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

  function js_emite(){

    if (iAnoSessao >= 2017) {
      sNomeArquivoEdicao = 'con2_emissaoAnexoIV002.php'
    }
    periodo = document.form1.o116_periodo.value;
    var jan = window.open(sNomeArquivoEdicao+'?periodo='+periodo,'',
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

  js_buscaEdicaoLrf(<?=db_getsession("DB_anousu")?>,"con2_lrfrecdesprpps002");

</script>