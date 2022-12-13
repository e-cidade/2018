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

$oDaoIssarquivoretencao = new cl_issarquivoretencao();
$oDaoIssarquivoretencao->rotulo->label();
?>
<form name="formularioRetencao" id="formularioRetencao" enctype="multipart/form-data" method="post">
   <fieldset>
     <legend>Processar Arquivo de Retorno - Retenção</legend>

      <table>
       <tr>
         <td nowrap title="<?php echo $Tq90_sequencial; ?>">
            <?php db_ancora("Código Arquivo de Retenção:", "js_pesquisa();", 1); ?>
         </td>
         <td>
           <?php
              db_input('q90_sequencial', 10, $Iq90_sequencial, true, 'text', 3);
              db_input('q90_nomearquivo', 40, $Iq90_nomearquivo, true, 'text', 3);
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?php echo $Tq90_valortotal; ?>">
          <label for="q90_valortotal"><?php echo $Lq90_valortotal; ?></label>
         </td>
         <td>
          <?php
            db_input('q90_valortotal', 10, $Iq90_valortotal, true, 'text', 3);
          ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?php echo $Tq90_quantidaderegistro; ?>">
           <label for="q90_quantidaderegistro"><?php echo $Lq90_quantidaderegistro; ?></label>
         </td>
         <td>
          <?php
            db_input('q90_quantidaderegistro', 10, $Iq90_quantidaderegistro, true, 'text', 3);
          ?>
         </td>
       </tr>
      </table>
   </fieldset>

  <input name="validar"   type="button" id="validar"   value="Validar"   onclick="js_validar();" disabled="disabled" />
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />

</form>
<script type="text/javascript">

var sUrlRPC           = "iss4_processaarquivoretencao.RPC.php";
var sCaminhoMensagens = "tributario.issqn.db_frmprocessaarquivoretencao.";

/*
 * Função para verificar as inconsistencias, antes do processamento do arquivo de retorno
 */
function js_validar(){

  if( !isNumeric( $F('q90_sequencial') ) || empty( $F('q90_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'arquivo_obrigatorio' ) );
    return false;
  }

  var oParametros = { sExecucao           : 'validarArquivo',
                      iIssArquivoRetencao : $F('q90_sequencial') };

  new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, erro) {

    $('validar').disabled = true;
    $('pesquisar').disabled = true;

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    js_visualizarInconsistencias( oRetorno.iCodigoArquivoRetencao );
    js_renderizarRegistros( oRetorno.aRegistrosInconsistentes );

  }).setMessage( _M( sCaminhoMensagens + 'validando' ) ).execute();

}

/**
 * Funçao que limpa o formulario e abre a func de pesquisa
 */
function js_limpar() {

  $("formularioRetencao").reset();
  js_pesquisa();

  /**
   * Desabilita botão validar por padrão
   */
  $('validar').disabled = true;
}

/**
 * Funçao de pesquisa da func cadban
 * @param  boolean mostra
 */
function js_pesquisacadban(mostra){

  $('processar').disabled = true;

  if (mostra==true){

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_cadban',
                        'func_cadban.php?method=sql_query_tabplan&iCodigoBanco=1&funcao_js=parent.js_mostracadban1|k15_codigo|z01_nome',
                        'Consulta Bancos',
                        true,
                        20);
  } else {

    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_cadban',
                        'func_cadban.php?method=sql_query_tabplan&pesquisa_chave='+$F('k15_codigo')+'&iCodigoBanco=1&funcao_js=parent.js_mostracadban',
                        'Consulta Bancos',
                        false,
                        0);
  }

  /**
   * Seta zIndex
   */
  $('Jandb_iframe_cadban').style.zIndex = '998';
}

/**
 * Callback da func cadban
 * @param  string chave1
 * @param  string chave2
 */
function js_mostracadban1(chave1, chave2){

  $('k15_codigo').value   = chave1;
  $('nomebanco').value    = chave2;
  $('processar').disabled = false;
  db_iframe_cadban.hide();
}

/**
 * Callback 2 da func cadban
 * @param  string  chave
 * @param  boolean erro
 */
function js_mostracadban(chave, erro){

  $('nomebanco').value = chave;
  if (erro == true) {

    $('k15_codigo').focus();
    $('k15_codigo').value = '';
  }

  $('processar').disabled = erro;
  if( empty( $F('k15_codigo') ) ){
    $('processar').disabled = true;
  }
}

/**
 * WindowAux com os registros inconsistentes
 */
function js_visualizarInconsistencias( iCodigoArquivoRetencao ) {

  var iLarguraJanela = screen.availWidth  - 300;
  var iAlturaJanela  = screen.availHeight - 300;

  windowInconsistencias   = new windowAux( 'windowInconsistencias',
                                           'Inconsistências Encontradas',
                                           iLarguraJanela,
                                           iAlturaJanela );

  windowInconsistencias.allowDrag(false);

  var sWindowAuxInconsistencias  = "<div style='overflow: hidden !important;'>";
      sWindowAuxInconsistencias += "  <div id='sTituloWindow'></div> ";
      sWindowAuxInconsistencias += "  <div id='containerGrid'></div> ";
      sWindowAuxInconsistencias += "  <div id='fildsetCadban' class='container' style='display: none;'> ";
      sWindowAuxInconsistencias += "    <fieldset> ";
      sWindowAuxInconsistencias += "      <legend>Banco</legend> ";
      sWindowAuxInconsistencias += "      <tr> ";
      sWindowAuxInconsistencias += "        <td nowrap='' title='Codigo do banco Campo:k15_codigo '> ";
      sWindowAuxInconsistencias += "          <label for='k15_codigo'><a class='dbancora' onclick='js_pesquisacadban(true);' href='#'>Banco:</a></label>";
      sWindowAuxInconsistencias += "        </td> ";
      sWindowAuxInconsistencias += "        <td> ";
      sWindowAuxInconsistencias += "          <input id='k15_codigo' type='text' autocomplete='off' style='background-color:#FFFFFF;' maxlength='10' size='10' value='' name='k15_codigo' title='Codigo do banco Campo:k15_codigo'";
      sWindowAuxInconsistencias += "          onchange='js_pesquisacadban(false);' onkeydown='return js_controla_tecla_enter(this,event);' oninput='js_ValidaCampos(this,1,\"Código do banco\",\"t\",\"f\",event);' onblur='js_ValidaMaiusculo(this,\"f\",event);'>";
      sWindowAuxInconsistencias += "          <input id='nomebanco' type='text' style='background-color:#DEB887;' size='60' readonly='readonly' name='nomebanco'> ";
      sWindowAuxInconsistencias += "        </td> ";
      sWindowAuxInconsistencias += "      </tr> ";
      sWindowAuxInconsistencias += "    </fieldset> ";
      sWindowAuxInconsistencias += "  </div> ";
      sWindowAuxInconsistencias += "  <div id='ctnGerarRelatorio' style='margin-top:10px; text-align: center;'>";
      sWindowAuxInconsistencias += "    <input type='button' id='emitir'    value='Emitir Relatório'  onclick='js_emitirRelatorio( "+iCodigoArquivoRetencao+" );' disabled='disabled' />";
      sWindowAuxInconsistencias += "    <input type='button' id='processar' value='Processar Arquivo' onclick='js_processar( "+iCodigoArquivoRetencao+" );' disabled='disabled' />";
      sWindowAuxInconsistencias += "    <input type='button' value='Cancelar' onclick='windowInconsistencias.destroy();js_limpar();' />";
      sWindowAuxInconsistencias += "  </div> ";
      sWindowAuxInconsistencias += "</div>";

   windowInconsistencias.setContent(sWindowAuxInconsistencias);

  /**
   * Message board
   */
  var sTextoMessageBoard  = 'Verifique o(s) registro(s) inconsistente(s) abaixo para habilitar o processamento do arquivo.';
      messageBoard        = new DBMessageBoard('msgboard1',
                                               'Dados das Inconsistências do Arquivo.',
                                                sTextoMessageBoard,
                                                $('sTituloWindow'));

   windowInconsistencias.setShutDownFunction(function () {

     windowInconsistencias.destroy();
     js_limpar();
   });

   windowInconsistencias.show();
   messageBoard.show();

   js_montaGridInconsistencias();
}

/**
 * Processa os dados do arquivo selecionado para a baixa de banco
 */
function js_processar( iCodigoArquivoRetencao ) {

  if( !isNumeric( iCodigoArquivoRetencao ) || empty( iCodigoArquivoRetencao ) ){

    alert( _M( sCaminhoMensagens + 'arquivo_obrigatorio' ) );
    return false;
  }

  var iCodbco = $F('k15_codigo');

  if( !isNumeric( iCodbco ) || empty( iCodbco ) ){

    alert( _M( sCaminhoMensagens + ' banco_obrigatorio' ) );
    return false;
  }

  var oParametros = { sExecucao                : 'processarArquivo',
                      'iCodigoArquivoRetencao' : iCodigoArquivoRetencao,
                      'iCodbco'                : iCodbco
                    };

  new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {
      return false;
    }

    windowInconsistencias.destroy();
    js_limpar();

  }).setMessage( _M( sCaminhoMensagens + 'processando' ) ).execute();
}

function js_renderizarRegistros( aInconsistencias ){

  if( aInconsistencias.length == 0 ){

    $('fildsetCadban').style = "display: '';";

    var aRow = new Array();
    aRow[0]  =  _M( sCaminhoMensagens + 'nenhuma_inconsistencia' ) ;
    oGridInconsistencias.addRow(aRow);
    oGridInconsistencias.aRows[0].aCells[0].setUseColspan(true, 3);
    oGridInconsistencias.renderRows();

    return true;
  }

  $('emitir').disabled = false;

  var sMensagemInconsistencia = '';
  var sDocumentoInconsistente = '';
  var iCodigoArquivoRetencao  = null;

  aInconsistencias.each(
                        function (oDado, iInd) {
                          var aRow    = new Array();

                          iDocumentoInconsistente = 'CPF';
                          if( oDado.registro.length == 14){
                            iDocumentoInconsistente = 'CNPJ';
                          }

                          aRow[0] = oDado.sequencial_registro;
                          aRow[1] = js_formatar( oDado.registro, 'cpfcnpj');
                          aRow[2] = _M( sCaminhoMensagens + oDado.erro, { iDocumento : iDocumentoInconsistente } );
                          oGridInconsistencias.addRow(aRow);
                        });
  oGridInconsistencias.renderRows();
}

/**
 * Grid com os registros inconsistentes
 */
function js_montaGridInconsistencias() {

  oGridInconsistencias = new DBGrid('Registros Inconsistentes');
  oGridInconsistencias.nameInstance = 'oGridInconsistencias';
  oGridInconsistencias.allowSelectColumns(false);

  oGridInconsistencias.setCellWidth( new Array('20%', '20%', '70%') );
  oGridInconsistencias.setCellAlign( new Array('center', 'center', 'left') );
  oGridInconsistencias.setHeader( new Array('Linha do Arquivo', 'CPF/CNPJ ', 'Inconsistência') );

  oGridInconsistencias.setHeight(300);
  oGridInconsistencias.show($('containerGrid'));
  oGridInconsistencias.clearAll(true);
}

/**
 * Emite relatório com as inconsistências
 */
function js_emitirRelatorio( iCodigoArquivoRetencao ) {

  if( !isNumeric( iCodigoArquivoRetencao ) || empty( iCodigoArquivoRetencao ) ){

    alert( _M( sCaminhoMensagens + 'arquivo_obrigatorio' ) );
    return false;
  }

  var aInconsistencias = new Array;

  oGridInconsistencias.getRows().each( function( oLinha ) {

    var aRegistros = {
      sequencial_registro : oLinha.aCells[0].content,
      registro            : encodeURIComponent( oLinha.aCells[1].content ),
      mensagem            : encodeURIComponent( oLinha.aCells[2].content )
    };

    aInconsistencias.push(aRegistros);
  });

  var oParametros = { sExecucao                : 'emitirRelatorio',
                      'iCodigoArquivoRetencao' : iCodigoArquivoRetencao,
                      aRegistrosInconsistentes : aInconsistencias
                    };

  new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    js_downloadRelatorio( oRetorno.sArquivoRelatorio );

  }).setMessage( _M( sCaminhoMensagens + 'validando' ) ).execute();

}

/**
* Abre o Download do relatorio
*/
function js_downloadRelatorio( sArquivoRelatorio ) {

  var sUrl = "iss2_processaarquivoretencao002.php?sArquivo=" + sArquivoRelatorio;
  window.open(sUrl, '', 'location=0');
}

function js_mostraissarquivoretencao(chave1, chave2, chave3, chave4){

  $('q90_sequencial').value         = chave1;
  $('q90_nomearquivo').value        = chave2;
  $('q90_quantidaderegistro').value = chave3;
  $('q90_valortotal').value         = js_formatar(chave4, 'f');
  $('validar').disabled             = false;
  $('pesquisar').disabled           = false;
  db_iframe_issarquivoretencao.hide();
}

function js_pesquisa(){

   js_OpenJanelaIframe( 'CurrentWindow.corpo',
                        'db_iframe_issarquivoretencao',
                        'func_issarquivoretencao.php?lProcessados=false&funcao_js=parent.js_mostraissarquivoretencao|q90_sequencial|q90_nomearquivo|q90_quantidaderegistro|q90_valortotal',
                        'Arquivos de Retorno - Retenção',
                        true );

  /**
   * Seta zIndex
   */
  $('Jandb_iframe_issarquivoretencao').style.zIndex = '998';
}
</script>
<style type="text/css">
  .gridcontainer { width: 99.8% !important; }
  #msgboard1 { width: 99.9% !important; }
</style>
