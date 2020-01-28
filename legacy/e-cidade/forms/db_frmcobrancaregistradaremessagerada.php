<?php
/**
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
?>
<form action="" method="post" name="formRemessasGeradas" id="formRemessasGeradas">
  <fieldset style="width: 532px">
    <legend>Remessas Geradas</legend>
    <table class="form-container">
      <tr>
        <td><label class="bold" id="labelDataEmissao" for="sDataEmissaoInicio">Data de Emissão:</label></td>
        <td>
          <?php db_inputdata("sDataEmissaoInicio", date("d"), date("m"), date("Y"), true, null, 1); ?>
          <strong>a</strong>
          <?php db_inputdata("sDataEmissaoFim", date("d"), date("m"), date("Y"), true, null, 1); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" id="labelConvenio"><a href="javascript:;">Convênio:</a></label>
        </td>
        <td>
          <?php
            db_input("ar11_sequencial", 1, 1, true, "text", 1, "data='ar11_sequencial'", null, null, "width:90px");
            db_input("ar11_nome", 1, 1, true, "text", 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <div style="margin-top: 10px;">
    <input type="button" value="Pesquisar" name="processar" id="processar" onclick="return js_processar()" />
    <input type="reset" value="Limpar" name="limpar" id="limpar" onclick="return js_limpar()" />
  </div>
  <div id="containerGridRemessaGerada" style="margin-top: 10px;"></div>
</form>

<script type="text/javascript">

  const sRPC = "arr4_cobrancaregistrada.RPC.php";

  var oLookUpConvenio = new DBLookUp($("labelConvenio"), $("ar11_sequencial"), $("ar11_nome"), {
    "sArquivo"      : "func_cadconvenio.php",
    "sObjetoLookUp" : "db_iframe_cadconvenio",
    "sLabel"        : "Pesquisar Convênio"
  });

  oGridRemessaGerada = new DBGrid("containerGridRemessaGerada");
  oGridRemessaGerada.nameInstance = "oGridRemessaGerada";

  oGridRemessaGerada.setCellWidth(new Array("13%", "22%", "36%", "28%"));
  oGridRemessaGerada.setCellAlign(new Array("center", "center", "left", "center"));
  oGridRemessaGerada.setHeader(new Array("Sequencial", "Data/Hora Emissão", "Convênio", "Ações"));
  oGridRemessaGerada.setHeight("300");

  oGridRemessaGerada.show($("containerGridRemessaGerada"));
  oGridRemessaGerada.clearAll(true);

  function js_processar(){

    oGridRemessaGerada.clearAll(true);

    var oParametros = {
        sExecucao          : "getRemessasGeradas",
        sDataEmissaoInicio : $F("sDataEmissaoInicio"),
        sDataEmissaoFim    : $F("sDataEmissaoFim"),
        iConvenio          : $F("ar11_sequencial")
    }

    new AjaxRequest(sRPC, oParametros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMensagem.urlDecode());
        return false;
      }

      oRetorno.aRemessasGeradas.each(function(oDado, iIndice) {

        var aRow    = new Array();
            aRow[0] = oDado.sequencial;
            aRow[1] = oDado.data + " - " + oDado.hora;
            aRow[2] = oDado.codigo_convenio + " - " + oDado.nome_convenio.urlDecode();

            aRow[3]  = "<input type='button' value='Regerar' onclick='js_regerar(" + oDado.codigo + ")'> ";
            aRow[3] += "<input type='button' value='Baixar' onclick='js_baixar(" + oDado.codigo + ")'>";

        oGridRemessaGerada.addRow(aRow);
      });

      oGridRemessaGerada.renderRows();

    }).setMessage("Carregando...").execute();
  }

  function js_regerar(iCodigo){

    if (empty( iCodigo ) ) {
      return alert("Remessa não informada.");
    }

    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_carne',
      'arr4_cobrancaregistradaexportacaogeracao.php?codigo_remessa=' + iCodigo,
      'Processando Geração...',
      true
    );
  }

  function js_baixar(iCodigo){

    var oParametros = {
        sExecucao   : "getRemessaGeradaBaixar",
        iSequencial : iCodigo
    }

    new AjaxRequest(sRPC, oParametros, function(oRetorno, erro) {

      if (erro) {

        alert(oRetorno.sMensagem.urlDecode());
        return false;
      }

      var oDownload = new DBDownload();
      oDownload.addFile(oRetorno.sArquivo.urlDecode(), oRetorno.sArquivoNome.urlDecode());
      oDownload.show();

    }).setMessage("Carregando...").execute();
  }

  function js_limpar(){

    $("formRemessasGeradas").reset();
    oGridRemessaGerada.clearAll(true);
  }

</script>
