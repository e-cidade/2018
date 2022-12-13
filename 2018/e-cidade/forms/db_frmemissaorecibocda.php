<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

$clrotulo = new rotulocampo;
$clrotulo->label('v13_certid');
$clrotulo->label('k00_dtvenc');
$clrotulo->label('v82_sequencial');

$db_opcao = 1;
?>
<form name="formEmissaoReciboCDA" id="formEmissaoReciboCDA" method="post" action="">

  <fieldset>
    <legend>Emissão de Recibo para CDA</legend>

    <table class="form-container">

      <tr>
        <td title="<?php echo $Tv13_certid; ?>">
          <?php
            db_ancora($Lv13_certid,"js_pesquisa_certid_ini(true);",1);
          ?>
        </td>
        <td>
          <strong>
          <?php

            db_input("v13_certidini",10,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_ini(false);'");
            db_ancora("até","js_pesquisa_certid_fim(true);",1);
            db_input("v13_certidfim",10,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_fim(false);'");
          ?>
          </strong>
        </td>
      </tr>
      <tr>
        <td title="<?php echo $Tv82_sequencial; ?>">
           <?php
             db_ancora("Cartório: ","js_pesquisaCartorio(true);",$db_opcao);
           ?>
        </td>
        <td>
           <?php
            db_input('v82_sequencial', 10, 1, true, "text", $db_opcao, " onchange='js_pesquisaCartorio(false);'");
            db_input('v82_descricao', 40, 1, true, "text", 3, '');
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tk00_dtvenc; ?>">
          <label for="k00_dtvenc">Data de Vencimento:</label>
        </td>
        <td>
          <?php
            db_inputdata('k00_dtvenc',null,null,null,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>

    </table>

  </fieldset>

  <input name="emitir" type="button" id="emitir" value="Emitir" onclick="return js_validaEmissao();"/>
  <input name="limpar" type="reset"  id="limpar" value="Limpar" />

</form>
<script type="text/javascript">

var sCaminhoMensagens = "tributario.divida.db_frmemissaorecibocda.";
var sRpc              = "div4_emissaorecibocda.RPC.php";
var aCertidoesEmissao = new Array();
var sDataSessao       = "<?php echo date("d/m/Y", db_getsession("DB_datausu")); ?>";

function js_validaEmissao() {

  $('emitir').disabled = true;
  $('limpar').disabled = true;

  try {

    if ($F('v13_certidini') == "") {

      throw ( _M ( sCaminhoMensagens + "erro_certidao_obrigatorio" ) );
      $('v13_certidini').focus();
    }

    if ($F('v13_certidfim') == "") {

      throw ( _M ( sCaminhoMensagens + "erro_certidao_obrigatorio" ) );
      $('v13_certidfim').focus();
    }

    if ( $F('v82_sequencial') == "" ) {
      throw ( _M ( sCaminhoMensagens + "erro_cartorio_obrigatorio" ) );
    }

    if ( $F('k00_dtvenc') == "" ) {

      throw ( _M ( sCaminhoMensagens + "erro_data_vencimento_obrigatorio" ) );
      $('k00_dtvenc').focus();
    }

    var lDataValida = js_comparadata( $F('k00_dtvenc'), sDataSessao, "<=");
    if( lDataValida ){
      throw ( _M ( sCaminhoMensagens + "erro_data_vencimento_invalida" ) );
    }

  }catch ( sMensagemErro ) {

    alert(sMensagemErro);
    $('emitir').disabled = false;
    $('limpar').disabled = false;
    return false;
  }

  var oParametros = {
      sExecucao       : "validarReciboCDA",
      certidaoInicial : $F('v13_certidini'),
      certidaoFinal   : $F('v13_certidfim'),
      cartorio        : $F('v82_sequencial'),
      dataVencimento  : $F('k00_dtvenc')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {
      return false;
    }

    aCertidoesEmissao = oRetorno.aRecibosEmissao;
    js_janelaInconsistencias();
    js_renderizaInconsistencias(oRetorno.aInconsistencias);

    if( aCertidoesEmissao.length == 0 ){
      $('processar').disabled = true;
    }

  }).setMessage( _M( sCaminhoMensagens + 'validando_emissao' ) ).execute();
}

function js_processar() {

  var oParametros = {
    aCertidoes     : aCertidoesEmissao,
    iCartorio      : $F('v82_sequencial'),
    sExecucao      : "emiteReciboCDA",
    dataVencimento : $F('k00_dtvenc')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    $('emitir').disabled = "";
    $('limpar').disabled = "";

    $('oWindowInconsistencias').remove();

    if (oRetorno.erro) {
      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    var oDownload = new DBDownload();
    oDownload.addGroups( 'zip', 'Lote de Cobrança');
    oDownload.addFile( oRetorno.sNomeArquivoZipCobranca, 'Download do Lote de Cobrança', 'zip' );
    oDownload.addFile( oRetorno.sNomeArquivoRelatorio, 'Download do Relatório Recibo/Certidão', 'zip' );
    oDownload.show();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_emissao' ) ).execute();
}

function js_renderizaInconsistencias(aInconsistencias) {

  if ( aInconsistencias.length == 0 ) {

    var aRow = new Array();
    aRow[0]  =  _M( sCaminhoMensagens + 'nenhuma_inconsistencia' ) ;
    oGridInconsistencias.addRow(aRow);
    oGridInconsistencias.aRows[0].aCells[0].setUseColspan(true, 3);
    oGridInconsistencias.renderRows();

  } else {

    aInconsistencias.each(function (oDado, iInd) {

                            var aRow = new Array();

                            aRow[0] = oDado.iCertidao;
                            aRow[1] = oDado.sInconsistencia.urlDecode();

                            var sTipoInconsistencia = 'Aviso';
                            if ( oDado.lIsErro ){
                              sTipoInconsistencia = '<span style="color:#FF0000; font-weight:bold;">Erro</span>';
                            }
                            aRow[2] = sTipoInconsistencia;

                            oGridInconsistencias.addRow(aRow);
                          });
    oGridInconsistencias.renderRows();
  }
}

function js_janelaInconsistencias() {

  var iLargura = screen.availWidth - 80;
  var iAltura  = screen.availHeight - 250;

  var oWindowInconsistencias = new windowAux( "oWindowInconsistencias",
                                              "Certidões Inconsistentes",
                                              iLargura,
                                              iAltura );

  oWindowInconsistencias.allowDrag(false);

  var sWindowAuxInconsistencias  = "<div style='overflow: hidden !important;'>";
      sWindowAuxInconsistencias += "  <div id='sTituloWindow'></div> ";
      sWindowAuxInconsistencias += "  <div id='containerGrid'></div> ";
      sWindowAuxInconsistencias += "  <div id='recibos'></div> ";
      sWindowAuxInconsistencias += "  <div id='ctnGerarRelatorio' style='margin-top:10px; text-align: center;'>";
      sWindowAuxInconsistencias += "    <input type='button' id='processar' value='Processar' onclick='js_processar();' />";
      sWindowAuxInconsistencias += "    <input type='button' value='Cancelar' onclick=\"$('oWindowInconsistencias').remove();js_limpar();\" />";
      sWindowAuxInconsistencias += "  </div> ";
      sWindowAuxInconsistencias += "</div>";

  oWindowInconsistencias.setShutDownFunction(function () {

    oWindowInconsistencias.destroy();
    js_limpar();
  });

  oWindowInconsistencias.setContent(sWindowAuxInconsistencias);

  var sTextoMessageBoard  = '<p style="padding-left:15px; text-indent:0 !important">- Certidões com Inconsistências do tipo <strong>AVISO</strong> emitirão Recibo; <br />';
      sTextoMessageBoard += '- Certidões com Inconsistências do tipo <strong>ERRO</strong> não emitirão Recibo. </p>';

  var messageBoard        = new DBMessageBoard('msgboard1',
                                               'Verifique a(s) Inconsistência(s) encontrada(s).',
                                                sTextoMessageBoard,
                                                $('sTituloWindow'));

  messageBoard.show();

  oWindowInconsistencias.show();
  js_montaGridInconsistencias();
}

function js_montaGridInconsistencias() {

  oGridInconsistencias = new DBGrid('Registros Inconsistentes');
  oGridInconsistencias.nameInstance = 'oGridInconsistencias';
  oGridInconsistencias.allowSelectColumns(false);

  oGridInconsistencias.setCellWidth( new Array('20%', '60%', '20%') );
  oGridInconsistencias.setCellAlign( new Array('center', 'left', 'center') );
  oGridInconsistencias.setHeader( new Array('Certidão', 'Inconsistência', 'Tipo da Inconsistência') );

  oGridInconsistencias.setHeight(300);
  oGridInconsistencias.show($('containerGrid'));
  oGridInconsistencias.clearAll(true);
}

function js_pesquisa_certid_ini(mostra) {

  var certid = $F('v13_certidini');
  if( mostra == true ) {
    js_OpenJanelaIframe( 'CurrentWindow.corpo',
                         'db_iframe',
                         'func_certid.php?funcao_js=parent.js_mostracertid_ini1|0',
                         'Pesquisa',
                         true );
  } else {

    if ($F('v13_certidini') != '') {

      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe',
                           'func_certid.php?pesquisa_chave=' + $F('v13_certidini') + '&funcao_js=parent.js_mostracertid_ini',
                           'Pesquisa',
                           false );
      $('v13_certidfim').value = $F('v13_certidini');
    } else {
      $('v13_certidini').value = '';
    }
  }
}

function js_mostracertid_ini( chave, erro ) {

  if( erro == true ) {

    $('v13_certidini').value = '';
    $('v13_certidfim').value = '';
    document.formEmissaoReciboCDA.v13_certidini.focus();
  }
}

function js_mostracertid_ini1(chave1) {

  $('v13_certidini').value = chave1;
  $('v13_certidfim').value = chave1;
  db_iframe.hide();
}

function js_pesquisa_certid_fim(mostra) {

  if( mostra == true ) {

    js_OpenJanelaIframe( 'CurrentWindow.corpo',
                         'db_iframe',
                         'func_certid.php?funcao_js=parent.js_mostracertid_fim1|v13_certid',
                         'Pesquisa',
                         true );
  } else {

    if ($F('v13_certidfim') != '') {

      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe',
                           'func_certid.php?pesquisa_chave=' + $F('v13_certidfim') + '&funcao_js=parent.js_mostracertidfim',
                           'Pesquisa',
                           false);
    } else {
      $('v13_certidfim').value = '';
    }
  }
}

function js_mostracertidfim(chave, erro) {

  if ( erro == true ) {

    $('v13_certidfim').value = '';
    document.formEmissaoReciboCDA.v13_certidfim.focus();
  }
}

function js_mostracertid_fim1(chave1) {

  $('v13_certidfim').value = chave1;
  db_iframe.hide();
}

function js_pesquisaCartorio(mostra) {

  if (mostra == true) {

    var sUrl = 'func_cartorio.php?v82_extrajudicial=true&funcao_js=parent.js_mostraCartorio1|v82_sequencial|v82_descricao';
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cartorio', sUrl, 'Pesquisa', true);
  } else {

    if ($('v82_sequencial').value != '') {

      var sUrl = 'func_cartorio.php?v82_extrajudicial=true&pesquisa_chave='+document.formEmissaoReciboCDA.v82_sequencial.value+'&funcao_js=parent.js_mostraCartorio';
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cartorio', sUrl, 'Pesquisa', false);
    } else {
      $('v82_descricao').value = '';
    }
  }
}

function js_mostraCartorio(chave1, chave2, erro) {

  $('v82_descricao').value  = chave1;
  $('v82_sequencial').value = "";

  if (erro == false) {

    $('v82_sequencial').value = chave1;
    $('v82_descricao').value = chave2;
  }
}

function js_mostraCartorio1(chave1, chave2) {
  $('v82_sequencial').value   = chave1;
  $('v82_descricao').value    = chave2;
  db_iframe_cartorio.hide();
}

function js_limpar() {

  $('formEmissaoReciboCDA').reset();
  $('emitir').disabled = "";
  $('limpar').disabled = "";
}
</script>
