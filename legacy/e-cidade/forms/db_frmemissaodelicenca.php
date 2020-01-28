<?php
/**
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

$clrotulo = new rotulocampo;
$clrotulo->label("am08_sequencial");
$clrotulo->label("am05_nome");
$clrotulo->label("am05_sequencial");

$db_opcao = 1;
?>
<form name="formEmissaoLicenca" id="formEmissaoLicenca" method="post" action="">

  <fieldset>
    <legend>Emissão de Licença</legend>

    <table class="form-container">
      <tr>
        <td nowrap title="<?php echo $Tam05_sequencial; ?>" width="170px">
         <?php
          db_ancora($Lam05_sequencial,' js_pesquisaEmpreendimento(true); ',1);
         ?>
        </td>
        <td>
         <?php
          db_input('am05_sequencial',5,1,true,'text',1,"onchange='js_pesquisaEmpreendimento(false)'");
          db_input('am05_nome',50,0,true,'text',3,"",null);
         ?>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <fieldset id="dadosLicenca" class="hide">
            <legend>Dados da Licença</legend>

            <input type="hidden" name="am08_sequencial" id="am08_sequencial" value="" />
            <strong>Número:</strong> <span id="codigoLicenca"></span> <br/>
            <strong>Tipo de Licença:</strong> <span id="tipoLicenca"></span> <br/>
            <strong>Tipo de Emissão:</strong> <span id="tipoEmissao"></span> <br/>
            <strong>Data de Vencimento:</strong> <span id="dataVencimento"></span> <br/>
            <strong>Número do Parecer Técnico:</strong> <span id="codigoParecerTecnico"></span> <br/>
            <strong>Código do Processo:</strong> <span id="codigoProcesso"></span> <br/>
            <strong>Data de Emissão do Parecer Técnico:</strong> <span id="dataEmissao"></span> <br/>
          </fieldset>
        </td>
      </tr>

    </table>
  </fieldset>

  <input name="emitir" type="button" id="emitir" value="Emitir" onclick="return js_validaEmissao();" disabled />
  <input name="limpar" type="reset"  id="limpar" value="Limpar" onclick="js_limparFormulario();"/>

</form>
<script type="text/javascript">

var sCaminhoMensagens = "tributario.meioambiente.frm_emissaodelicenca.";
var sRpc              = "amb4_emissaodelicenca.RPC.php";

function js_limparFormulario(){

  $('formEmissaoLicenca').reset();
  $('dadosLicenca').addClassName('hide');
  $('am08_sequencial').value = '';
  $('emitir').disabled       = true;
}

/**
 * Função que reseta o formulário, mantendo os dados do empreendimento
 */
function js_validaEmissao(){

  if( !isNumeric( $F('am08_sequencial') ) || empty( $F('am08_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  var oParametros = {
      sExecucao             : 'emitirLicenca',
      iCodigoParecerTecnico : $F('am08_sequencial')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {
      return false;
    }

    /**
     * Utilizar a DBDownload.widget.js
     */
    var oDownload  = new DBDownload();
    oDownload.addGroups( 'sxw', 'Licença');
    oDownload.addFile( oRetorno.sArquivoRetorno, 'Download da Licença', 'sxw' );
    oDownload.show();

    $('limpar').click();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_emissao' ) ).execute();
}

/**
 * Func Empreendimento
 */
function js_pesquisaEmpreendimento(mostra) {

  if( empty( $F('am05_sequencial') ) ){
    js_limparFormulario();
  }
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?funcao_js=parent.js_mostraempreendimento1|0|1','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?pesquisa_chave='+$F('am05_sequencial')+'&funcao_js=parent.js_mostraempreendimento','Pesquisa',false,0);
  }
}

function js_mostraempreendimento1(chave1,chave2) {

  $('am05_sequencial').value = chave1;
  $('am05_nome').value       = chave2;
  db_iframe_empreendimento.hide();
  js_getLicenca();
}

function js_mostraempreendimento(chave,erro) {

  $('am05_nome').value = chave;
  if (erro==true) {

    $('am05_sequencial').focus();
    $('am05_sequencial').value = '';
  }else{
    js_getLicenca();
  }
}

/**
 * Função que retorna a licença disponível para emissão
 */
function js_getLicenca(){

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){
    return false;
  }

  var oParametros = {
      sExecucao             : 'getLicencaValida',
      iCodigoEmpreendimento : $F('am05_sequencial')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      js_limparFormulario();
      return false;
    }

    /**
     * Mostramos os dados da licença e liberamos o botao emitir
     */
    $('dadosLicenca').removeClassName('hide');
    $('emitir').disabled = false;

    $('am08_sequencial').value          = oRetorno.codigoParecerTecnico;

    $('codigoLicenca').innerHTML        = oRetorno.codigoLicenca;
    $('codigoParecerTecnico').innerHTML = oRetorno.codigoParecerTecnico;
    $('codigoProcesso').innerHTML       = oRetorno.codigoProcesso;
    $('tipoLicenca').innerHTML          = oRetorno.tipoLicenca;
    $('tipoEmissao').innerHTML          = oRetorno.tipoEmissao;
    $('dataVencimento').innerHTML       = oRetorno.dataVencimento;
    $('dataEmissao').innerHTML          = oRetorno.dataEmissao;

  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_licenca' ) ).execute();
}
</script>