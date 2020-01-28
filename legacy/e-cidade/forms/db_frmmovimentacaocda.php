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

$oDaoCertid = new cl_certid();
$clrotulo   = new rotulocampo;
$oDaoCertid->rotulo->label();

$db_opcao = 1;
?>
<form name="formMovimentacaoCDA" id="formMovimentacaoCDA" method="post" action="">

  <fieldset>
    <legend>Movimentação de CDA</legend>

    <table class="form-container">

      <tr>
        <td nowrap title="<?=$Tv13_certid?>">
          <?
            db_ancora($Lv13_certid,"js_pesquisa_certid_ini(true);",1);
          ?>
        </td>
        <td>
          <strong>
          <?
            db_input("v13_certidini",10,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_ini(false);'");

            db_ancora("até","js_pesquisa_certid_fim(true);",1);

            db_input("v13_certidfim",10,$Iv13_certid,true,"text",4,"onchange='js_pesquisa_certid_fim(false);'");
          ?>
          </strong>
        </td>
      </tr>

      <tr>
        <td nowrap title="Tipo">
          <label id="tipo" for="tipoMovimentacao">Tipo:</label>
        </td>
        <td>
          <?php
            $aOpcoes = array("" => "Selecione", "2" => "Protestada", "3" => "Resgatada");
            db_select('tipoMovimentacao', $aOpcoes, true, $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Tk00_dtvenc; ?>">
          <label for="k00_dtvenc">Data:</label>
        </td>
        <td>
          <?php
            db_inputdata('k00_dtvenc',null,null,null,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>

  </fieldset>

  <input name="processar" type="button" id="processar" value="Processar" onclick="return js_validaProcessar();"/>
  <input name="limpar"    type="reset"  id="limpar"    value="Limpar" onclick="return js_limpar();"/>

</form>

<script type="text/javascript">

var sCaminhoMensagens = "tributario.divida.db_frmmovimentacaocda.";
var sRpc              = "div4_movimentacaocda.RPC.php";
var aCertidoesValidas;

function js_validaProcessar(){

  try {

    if ( empty($F('v13_certidini')) || empty($F('v13_certidfim')) ){
      throw _M( sCaminhoMensagens + 'certidao_obrigatorio' );
    }

    if ( empty($F('tipoMovimentacao')) ){
      throw _M( sCaminhoMensagens + 'tipo_obrigatorio' );
    }

    if ( empty($F('k00_dtvenc')) ){
      throw _M( sCaminhoMensagens + 'datavencimento_obrigatorio' );
    }

    var oParametros = {
      sExecucao         : "validarMovimentacao",
      iCertidaoInicial  : $F('v13_certidini'),
      iCertidaoFinal    : $F('v13_certidfim'),
      dDataMovimentacao : $F('k00_dtvenc'),
      iTipoMovimentacao : $F('tipoMovimentacao')
    }

    new AjaxRequest( sRpc, oParametros, function( oRetorno, erro ) {

      if (oRetorno.erro) {

        alert( oRetorno.sMensagem.urlDecode() );
        return false;
      }

      aCertidoesValidas = oRetorno.aCertidoesValidas;

      js_janelaInconsistencias();
      js_renderizaInconsistencias( oRetorno.aInconsistencias );

      if ( aCertidoesValidas.length == 0 ) {
        $('movimentar').disabled = "true";
      }

    }).setMessage( _M( sCaminhoMensagens + 'carregando_movimentacao' ) ).execute();

  } catch (erro) {

    alert(erro);
    return false;
  }

  return false;
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
      sWindowAuxInconsistencias += "  <div id='ctnGerarRelatorio' style='margin-top:10px; text-align: center;'>";
      sWindowAuxInconsistencias += "    <input type='button' id='movimentar' value='Movimentar' onclick='js_processar();' />";
      sWindowAuxInconsistencias += "    <input type='button' value='Cancelar' onclick=\"$('oWindowInconsistencias').remove();\" />";
      sWindowAuxInconsistencias += "  </div> ";
      sWindowAuxInconsistencias += "</div>";

  oWindowInconsistencias.setShutDownFunction(function () {

    oWindowInconsistencias.destroy();
    js_limpar();
  });

  oWindowInconsistencias.setContent(sWindowAuxInconsistencias);


  var sTextoMessageBoard  = '<p style="padding-left:15px; text-indent:0 !important">- Certidões com Inconsistências do tipo <strong>ERRO</strong> não poderão ser movimentadas.</p>';

  var messageBoard        = new DBMessageBoard( 'msgboard1',
                                                'Verifique a(s) Inconsistência(s) encontrada(s).',
                                                sTextoMessageBoard,
                                                $('sTituloWindow') );

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

function js_renderizaInconsistencias(aInconsistencias) {

  if ( aInconsistencias.length == 0 ) {

    var aRow = new Array();
    aRow[0]  =  _M( sCaminhoMensagens + 'nenhuma_inconsistencia' );
    oGridInconsistencias.addRow(aRow);
    oGridInconsistencias.aRows[0].aCells[0].setUseColspan(true, 3);
    oGridInconsistencias.renderRows();

  } else {

    aInconsistencias.each(function (oDado, iInd) {

                            var aRow    = new Array();

                            aRow[0] = oDado.iCertidao;
                            aRow[1] = oDado.sInconsistencia.urlDecode();

                            var sTipoInconsistencia = 'Aviso';
                            if ( oDado.lIsErro ) {
                              sTipoInconsistencia = '<span style="color:#FF0000; font-weight:bold;">Erro</span>';
                            }
                            aRow[2] = sTipoInconsistencia;

                            oGridInconsistencias.addRow(aRow);
                          });
    oGridInconsistencias.renderRows();
  }
}

function js_processar() {

  var oParametros = {
    sExecucao         : "processaMovimentacao",
    aCertidoes        : aCertidoesValidas,
    iTipo             : $F('tipoMovimentacao'),
    dDataMovimentacao : $F('k00_dtvenc')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    $('processar').disabled = "";
    $('limpar').disabled = "";

    $('oWindowInconsistencias').remove();
    alert(oRetorno.sMensagem.urlDecode());

    if (oRetorno.erro) {
      return false;
    }

    js_limpar();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_movimentacao' ) ).execute();
}

/**
 * Lookup certidão
 */
function js_pesquisa_certid_ini(mostra) {

  var certid = $F('v13_certidini');
  if( mostra == true ) {

    js_OpenJanelaIframe( 'top.corpo',
                         'db_iframe' ,
                         'func_certid.php?funcao_js=parent.js_mostracertid_ini1|0' ,
                         'Pesquisa' ,
                         true );
  } else {

    if ( $F('v13_certidini') != '' ) {

      js_OpenJanelaIframe( 'top.corpo',
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
    document.formMovimentacaoCDA.v13_certidini.focus();
  }
}

function js_mostracertid_ini1(chave1) {

  $('v13_certidini').value = chave1;
  $('v13_certidfim').value = chave1;
  db_iframe.hide();
}

function js_pesquisa_certid_fim(mostra) {

  if( mostra == true ) {

    js_OpenJanelaIframe( 'top.corpo',
                         'db_iframe',
                         'func_certid.php?funcao_js=parent.js_mostracertid_fim1|v13_certid',
                         'Pesquisa',
                         true );
  } else {

    if ($F('v13_certidfim') != '') {

      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe',
                           'func_certid.php?pesquisa_chave=' + $F('v13_certidfim') + '&funcao_js=parent.js_mostracertidfim',
                           'Pesquisa',
                           false );
    } else {
      $('v13_certidfim').value = '';
    }
  }
}

function js_mostracertidfim(chave, erro) {

  if ( erro == true ) {

    $('v13_certidfim').value = '';
    document.formMovimentacaoCDA.v13_certidfim.focus();
  }
}

function js_mostracertid_fim1(chave1) {

  $('v13_certidfim').value = chave1;
  db_iframe.hide();
}

function js_limpar() {

  $('v13_certidfim').value = "";
  $('v13_certidini').value = "";
  $('tipoMovimentacao').value = "";
  $('k00_dtvenc').value = "";

  $('processar').disabled = "";
}
</script>