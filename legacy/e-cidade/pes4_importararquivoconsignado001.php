<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$sPosScripts = '';
$sMensagens  = "recursoshumanos.pessoal.pes4_importacaoarquivoconsignado.";

define('MENSAGENS', $sMensagens);

$oPost       = db_utils::postMemory($_POST);
$oGet        = db_utils::postMemory($_GET);
$oFiles      = db_utils::postMemory($_FILES);

$oCompetencia= DBPessoal::getCompetenciaFolha();
$iAnoFolha   = $oCompetencia->getAno();
$iMesFolha   = $oCompetencia->getMes();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <fieldset>
      <legend>Importa��o do Arquivo Consignado</legend>
      <table>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend>Aten��o</legend>
              <table style="background-color: #fff; padding: 05px 10px;">
                <tr>
                  <td>
                    Aten��o ap�s a importa��o deve ser efetuado o processamento do ponto.<br/>
                    Procedimentos > Processamento de Dados do Ponto
                  </td>
                </tr>
              </table>
            </fieldset>
        </tr>
        <tr>
          <td nowrap title="Ano / M�s">
            <label class="bold">
              Ano / M�s:
            </label>
          </td>
          <td id="formularioCompetencia"></td>
        </tr>
        <tr>
          <td nowrap title="Arquivo" >
            <label class="bold" for="aArquivoMovimento" id="lblArquivo">
              Arquivo:
            </label>
          </td>
          <td>
            <?php db_input('aArquivoMovimento', 35, '', true, 'file', 1, ' style="height: initial"'); ?>
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
    <input name="incluir" type="button" id="db_opcao" value="Importar" onclick="return js_validaCampo();">
  </form>
</div>
<?php 
  db_menu();

  $sMensagem  = "Este menu mudou para:\n";
  $sMensagem .= "Pessoal > Procedimentos > Manuten��o de Empr�stimos Consignados > Arquivos > Importar\n";
  $sMensagem .= "A partir da pr�xima atualiza��o o menu atual ser� retirado.";

  if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
    db_msgbox($sMensagem);
  }
?>
</body>
<script>

  var sUrlRPC = 'pes4_importararquivoconsignado.RPC.php';
  function js_validaCampo() {

    if ($('aArquivoMovimento').value == '') {

      alert( _M("recursoshumanos.pessoal.pes4_importacaoarquivoconsignado.arquivo_invalido") );
      return false;
    }
    if ($F('banco') == "") {

      alert("O campo Banco � de preenchimento obrigat�rio.");
      return false;
    }
    var oParam = {
      exec  : 'importarArquivo',
      banco : $F('banco')
    }

    new AjaxRequest(sUrlRPC, oParam, function(oRetorno, lErro) {

      alert(oRetorno.sMessage.urlDecode());
      if (lErro) {
        return false;
      }

      location.href = 'pes4_conferenciaconsignados001.php';
    }).setMessage('Aguarde, processando arquivo')
      .addFileInput($('aArquivoMovimento'))
      .execute();

  }
  (function() {

    var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);

    oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
    oCompetenciaFolha.desabilitarFormulario();
    var oParam = {
      exec  : 'getBancosConfigurados',
    }

    new AjaxRequest(sUrlRPC, oParam, function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.sMessage.urlDecode());
        return
      }

      var oSelect = $('banco');
      oSelect.options.lenght = 1;

      for (oBanco of oRetorno.bancos) {

        var oOption = new Option(oBanco.banco+" - "+oBanco.descricao.urlDecode(), oBanco.banco);
        oSelect.add(oOption);
      }

    }).setMessage('Aguarde, pesquisando configura��es')
      .execute();

  })();
</script>
</html>