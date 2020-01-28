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

$oDaoCondicionante = new cl_condicionante();
$oDaoCondicionante->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("am09_descricao");

$lHabilitaLancador = "true";
$sShowPesquisar    = "";
$sJsBotao          = "js_validarFormulario";

switch ( $db_opcao ) {

  case 2:
    $sBotao = "Alterar";
  break;

  case 3:

    $sBotao            = "Excluir";
    $lHabilitaLancador = "false";
    $sJsBotao          = "js_excluirCondicionante";
  break;

  default:

    $sBotao         = "Incluir";
    $sShowPesquisar = "hide";
  break;
}

$aOpcoes = array("false"=>"Não", "true"=>"Sim");
?>
<form name="formCondicionantes" id="formCondicionantes" method="post" action="">

<?php db_input( "am10_sequencial", null, null, true, "hidden" ); ?>

<fieldset style="width: 510px;">
  <legend>Condicionantes</legend>

  <table class="form-container" witdh="100%">
    <tr>
      <td title="<?php echo $Tam10_descricao; ?>" width="25%"><label for="am10_descricao"><?php echo $Lam10_descricao; ?></label></td>
      <td>
        <?php db_textarea('am10_descricao', 3, 10, null, true, 'text', $db_opcao ); ?>
      </td>
    </tr>

    <tr>
      <td title="<?php echo $Tam10_padrao; ?>"><label for="am10_padrao"><?php echo $Lam10_padrao; ?></label></td>
      <td>
      <?php
        db_select( 'am10_padrao', $aOpcoes, true, $db_opcao );
      ?>
      </td>
    </tr>

    <tr>
      <td title="<?php echo $Tam10_vinculatodasatividades; ?>"><label for="am10_vinculatodasatividades"><?php echo $Lam10_vinculatodasatividades; ?></label></td>
      <td>
      <?php
        db_select( 'am10_vinculatodasatividades', $aOpcoes, true, $db_opcao, "onchange='js_bloqueiaLancador(this.value)'");
      ?>
      </td>
    </tr>

    <tr>
      <td colspan="2"><div id="ctnLancadorAtividade" name="ctnLancadorAtividade" style=''></div></td>
    </tr>

    <tr>
      <td colspan="2">

      <fieldset class='separator'>
        <legend>Tipo de Licença</legend>
        <table class="form-container" witdh="100%">

        <?php

          $oTipoLicenca  = new TipoLicenca();
          $aTiposLicenca = $oTipoLicenca->getTiposDescricoes();

          $sDisabled = "";

          if ($db_opcao == 3) {
            $sDisabled = "disabled";
          }

          foreach ($aTiposLicenca as $aTipos) {

            $sIdCampo = "tipoLicenca_{$aTipos->am09_sequencial}";
            $sHtml    = "<tr>
                           <td width='10px'><input type='checkbox' id='{$sIdCampo}' name='tipoLicenca'
                                                   class='tipoLicenca' value='{$aTipos->am09_sequencial}' {$sDisabled}/></td>
                           <td><label for='{$sIdCampo}'>{$aTipos->am09_descricao}</label></td>
                         </tr>";
            echo $sHtml;
          }
        ?>
        </table>
      </fieldset>

      </td>
    </tr>
  </table>

</fieldset>

<input name="enviar"    type="button" id="enviar"    value="<?php echo $sBotao; ?>" onclick="return <?php echo $sJsBotao; ?>();" />
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"              onclick="return js_pesquisaCondicionante();" class="<?php echo $sShowPesquisar; ?>" />

</form>
<style type="text/css">
.form-container textarea{ min-height: 39px !important; }
</style>
<script type="text/javascript">

var iOpcao               = <?php echo $db_opcao; ?>;
var lHabilita            = <?php echo $lHabilitaLancador; ?>;
var lTodasAtividades     = <?php echo $am10_vinculatodasatividades; ?>;

var sCaminhoMensagens    = "tributario.meioambiente.db_frmcondicionante.";
var sUrlRPCCondicionante = "amb1_condicionante.RPC.php";

 $('am10_descricao').addClassName('field-size9');

function js_validarFormulario(){

  $('enviar').disabled = true;
  var lTodasAtividades = $F('am10_vinculatodasatividades');
  var aAtividades      = oLancadorAtividade.getRegistros();

  try {

    if ( empty( $F('am10_descricao') ) ) {
      throw ( _M( sCaminhoMensagens + 'descricao_obrigatorio' ) );
    }

    if (aAtividades.length == 0 && lTodasAtividades == 'false') {
      throw ( _M( sCaminhoMensagens + 'atividade_obrigatorio' ) );
    }

    var aTiposLicenca = [];
    $$('.tipoLicenca').each( function(oElemento) {

      if( $(oElemento).checked ) {
        aTiposLicenca.push($(oElemento).value);
      }
    });

    if ( aTiposLicenca.length == 0 ) {
      throw ( _M( sCaminhoMensagens + 'tipolicenca_obrigatorio' ) );
    }

  }catch ( sMensagemErro ) {

    alert( sMensagemErro );
    $('enviar').disabled = false;
    return false;
  }

  var sExecucao   = 'incluirCondicionante';
  var iSequencial = $F('am10_sequencial');
  if( !js_empty(iSequencial) ){
    sExecucao = 'alterarCondicionante';
  }

  var aParametros = {
    sExecucao        : sExecucao,
    iSequencial      : iSequencial,
    sDescricao       : encodeURIComponent( tagString( $F('am10_descricao') ) ),
    aTipoLicenca     : aTiposLicenca,
    lPadrao          : $F('am10_padrao'),
    lTodasAtividades : lTodasAtividades,
    aAtividades      : aAtividades
  };

  new AjaxRequest(sUrlRPCCondicionante, aParametros, function(oRetorno, erro) {

    alert(oRetorno.sMessage.urlDecode());

    if (erro) {
      return false;
    }

    js_limparFormulario();

    if (iOpcao == 2) {
      $('pesquisar').click();
    }

  }).setMessage(_M( sCaminhoMensagens + 'incluindo_condicionante' ) ).execute();

  $('enviar').disabled = '';
  return true;
}

/**
 * Controla a edição do lançador
 * @param  boolean lTodasAtividades
 * @return void
 */
function js_bloqueiaLancador( lBloqueiaLancador ) {

  oLancadorAtividade.setHabilitado( lBloqueiaLancador );

  if (lBloqueiaLancador == 'true') {

    oLancadorAtividade.setHabilitado( false );
    oLancadorAtividade.clearAll();
  }

  oLancadorAtividade.show($("ctnLancadorAtividade"));
}

function js_limparFormulario() {

  $('formCondicionantes').reset();
  $('am10_descricao').value              = "";
  $('am10_padrao').value                 = "false";
  $('am10_vinculatodasatividades').value = "false";
  oLancadorAtividade.clearAll();
  oLancadorAtividade.setHabilitado( true );

  if (iOpcao == 3) {
    oLancadorAtividade.setHabilitado( false );
  }

  oLancadorAtividade.show($("ctnLancadorAtividade"));

  $$('.tipoLicenca').each( function( oElemento ) {
    $(oElemento).checked = false;
  });
}

function js_excluirCondicionante(){

  if( !confirm( _M ( sCaminhoMensagens + 'confirma_exclusao_condicionante' ) ) ) {
    return false;
  }

  var aParametros = {
      sExecucao   : 'excluirCondicionante',
      iSequencial : $F('am10_sequencial')
    };

  new AjaxRequest(sUrlRPCCondicionante, aParametros, function(oRetorno, erro) {

    alert(oRetorno.sMessage.urlDecode());

    if (erro) {
      return false;
    }

    js_limparFormulario();
    js_pesquisaCondicionante();

  }).setMessage(_M( sCaminhoMensagens + 'excluindo_condicionante' ) ).execute();

  return true;
}

/**
 * Instanciamos o Lançador das Atividades
 */
oLancadorAtividade = new DBLancador("oLancadorAtividade");
oLancadorAtividade.setNomeInstancia("oLancadorAtividade");
oLancadorAtividade.setLabelAncora("Atividade:");
oLancadorAtividade.setTextoFieldset("Atividades");
oLancadorAtividade.setParametrosPesquisa("func_atividadeimpacto.php", ['am03_sequencial', 'am03_descricao']);
oLancadorAtividade.setGridHeight(200);
oLancadorAtividade.setTipoValidacao(1);

/**
 * Quando for exclusão, desabilita grid para lançar atividades
 */
oLancadorAtividade.setHabilitado( true );

/**
 * Verifica se é alteração
 */
if( iOpcao != 1 ) {

  if ( $F('am10_sequencial') != "" ) {

    oLancadorAtividade.setHabilitado(false);

    /**
     * Verificamos se devemos buscar as atividades para carregar na grid
     */
    if ( !lTodasAtividades ) {

      oLancadorAtividade.setHabilitado(true);

      var aParametros = {
            sExecucao   : 'getAtividadesCondicionante',
            iSequencial : $F('am10_sequencial')
          };

      new AjaxRequest( sUrlRPCCondicionante, aParametros, function( oRetorno, erro ) {

        if (erro) {
          return false;
        }

        aAtividadesLancadas = new Array();

        /**
         * Percorre o array retornado pelo RPC das Atividades e adiciona a grid do DBLancador
         */
        for( var iIndiceAtividade in oRetorno.aAtividadesLancadas ) {
          aAtividadesLancadas.push([ iIndiceAtividade, oRetorno.aAtividadesLancadas[iIndiceAtividade]]);
        }

        oLancadorAtividade.carregarRegistros(aAtividadesLancadas);

      }).setMessage(_M( sCaminhoMensagens + 'pesquisando_atividades' ) ).execute();
    }

    /**
     * Buscamos os tipos de licença
     */
    var aParametros = {
          sExecucao   : 'getTiposLicencaCondicionante',
          iSequencial : $F('am10_sequencial')
        };

    new AjaxRequest( sUrlRPCCondicionante, aParametros, function( oRetorno, erro ) {

      if (erro) {
        return false;
      }

      /**
       * Marcos os checkbox de acordo com os tipos de licenças retornados do RPC
       */
      for( var iIndiceTipo in oRetorno.aTiposLicenca ) {
        $('tipoLicenca_' + oRetorno.aTiposLicenca[iIndiceTipo]).checked = true;
      }

    }).setMessage(_M( sCaminhoMensagens + 'pesquisando_atividades' ) ).execute();

  }

}

if ( iOpcao == 3 ) {
  oLancadorAtividade.setHabilitado(false);
}

oLancadorAtividade.show($("ctnLancadorAtividade"));

function js_pesquisaCondicionante() {
  js_OpenJanelaIframe('top.corpo','db_iframe_condicionante','func_condicionante.php?funcao_js=parent.js_preenchepesquisaCondicionante|am10_sequencial','Pesquisa',true);
}

function js_preenchepesquisaCondicionante(chave) {

  db_iframe_condicionante.hide();
  <?php

    $sRedireciona   = " location.href = 'amb1_condicionante003.php?chavepesquisa='+chave ";
    if ( $db_opcao == 2 ) {
      $sRedireciona = " location.href = 'amb1_condicionante002.php?chavepesquisa='+chave ";
    }
    echo $sRedireciona;
  ?>
}
</script>