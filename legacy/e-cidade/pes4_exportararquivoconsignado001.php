<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("db90_codban");
$clrotulo->label("db90_descr");

$clrotulo->label("db50_codigo");
$clrotulo->label("db50_descr");

$clrotulo->label("rh27_rubric");
$clrotulo->label("rh27_descr");
$db_opcao = 1;
$oCompetencia = DBPessoal::getCompetenciaFolha();

if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  $oCompetencia = $oCompetencia->getCompetenciaAnterior();
}

$r11_anousu = $oCompetencia->getAno();
$r11_mesusu = $oCompetencia->getMes();

$oGet    = db_utils::postMemory($_GET);

?>
<html>
<head>
  <title>DBSeller Informática Ltda</title>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("windowAux.widget.js");
  db_app::load("strings.js");
  db_app::load("AjaxRequest.js");
  db_app::load("widgets/DBDownload.widget.js");
  db_app::load("estilos.css,grid.style.css");
  ?>
  </head>
  <body>
  <div class="Container">
    <form name="formulario" method="post">
      <fieldset>
        <legend>Geração do Arquivo de Retorno</legend>
        <table class="form-container">
          <tr>
            <td>
              <label>
                Competência da Folha:
              </label>
            </td>
            <td>
              <div>
                  <span>
                    <?php
                    db_input("r11_anousu", 4 , 1, true, 'text', $db_opcao, "", "", "", "", 4);
                    ?>
                  </span>
                /
                  <span>
                    <?php
                    db_input('r11_mesusu', 2 , 1, true, 'text', $db_opcao, "", "", "", "", 2);
                    ?>
                  </span>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <label for="tipo_lancamento">Tipo de Lançamento:</label>
            </td>
            <td>
              <?php
              db_select('tipo_lancamento', array('S'=>'Selecione', 'M'=>'Manual', 'A'=>'Arquivo'), true, 1, "onchange='configuraTipoLancamento(this)'");//, $nomevar = "", $bgcolor = "")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Bancos" >
              <label class="bold" for="banco" id="lbl_ano_mes">
                Banco:
              </label>
            </td>
            <td>
              <select id="banco" style="width: 100%">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnProcessar" name="btnProcessar" value="Gerar">
    </form>
   </div>
  </body>
</html>
<?php
  db_menu();

  $sMensagem  = "Este menu mudou para:\n";
  $sMensagem .= "Pessoal > Procedimentos > Manutenção de Empréstimos Consignados > Arquivos > Exportar\n";
  $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

  if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
    db_msgbox($sMensagem);
  }
?>
<script>
function processar() {



  var iMesFolha = $F('r11_mesusu');
  var iAnoFolha = $F('r11_anousu');
  if (empty(iMesFolha)) {

    alert('O mês da competência deve ser informada.');
    return;
  }

  if (empty(iAnoFolha)) {

    alert('O ano da competência deve ser informada.');
    return;
  }
  if ($F('tipo_lancamento') == 'M') {

    var sUrl = 'pes4_retornocontratosconsignadosmanual.php';
    sUrl += '?banco='+$F('banco')+'&ano='+iAnoFolha+'&mes='+iMesFolha;
    window.open(sUrl, '', 'location=0');

    return false;
  }
  var oParam    = new Object();
  oParam.exec   = 'exportar';
  oParam.iAno   = iAnoFolha;
  oParam.iMes   = iMesFolha;
  oParam.iBanco = $F('banco');
  var sUrl      = 'pes4_exportararquivoconsignado.RPC.php';

  new AjaxRequest(sUrl, oParam, function(oRetorno, lErro) {

    var sMensagem = oRetorno.sMessage.urlDecode();

    alert(sMensagem.urlDecode());
    if (lErro) {
      return false;
    }


    var sNomeArquivo = oRetorno.sArquivo.urlDecode();
    mostrarJanelaDownload(sNomeArquivo);

  }).setMessage('Aguarde, processamento arquivo de retorno').execute();
}

/**
 * Exibe a janela para fazer download do arquivo de retorno do Consignet
 *
 * @param {String} sArquivo
 */
function mostrarJanelaDownload(sArquivo) {

  var oDownload = new DBDownload();
  if (!empty(sArquivo)) {

    var sNomeArquivo = sArquivo.split('/')[1];
    oDownload.addFile( sArquivo, sNomeArquivo);
    oDownload.show();
  }
}

function configuraTipoLancamento(node) {

  var oDadosRequisicaoBancos = {
    url :'pes4_importararquivoconsignado.RPC.php',
    acao: {exec:'getBancosConfigurados'}
  };

  switch(node.value) {
    case 'M':
      oDadosRequisicaoBancos.url       = 'pes4_manutencaocontratosconsignados.RPC.php';
      oDadosRequisicaoBancos.acao.exec = 'getBancos';
      getBancos(oDadosRequisicaoBancos);
      break;

    default:
      getBancos(oDadosRequisicaoBancos);
      break;
  }
}
$('btnProcessar').observe('click', function(){
  processar()
});
function getUrlAPI() {

  var url = 'pes4_conferenciaconsignados.RPC.php';
  if ($F('tipo_lancamento') == 'M') {
    url = 'pes4_manutencaocontratosconsignados.RPC.php';
  }
  return url;
}
function getBancos(oDadosRequisicaoBancos) {

  var oSelect = $('banco');
  oSelect.options.length = 0;
  if ($F('tipo_lancamento') == 'S') {
    return false;
  }
  new AjaxRequest(oDadosRequisicaoBancos.url, oDadosRequisicaoBancos.acao, function(oResponse) {

    if (oResponse.erro) {
      if(oResponse.sMessage) {
        alert(oResponse.sMessage.urlDecode());
      }
      if(oResponse.messagem) {
        alert(oResponse.messagem.urlDecode());
      }
    }

    oResponse.bancos.forEach(function(oBanco) {

      var oOption = new Option(oBanco.banco + ' - ' + oBanco.descricao, oBanco.banco);
      oSelect.add(oOption);
    });

    oSelect.disabled = false;

    if (oResponse.bancos.length > 0) {

      if($F('tipo_lancamento') == 'A') {
        carregarDadosGrid();
      }
    }

  }).setMessage('Buscando bancos...')
    .asynchronous(false)
    .execute();
}
</script>
