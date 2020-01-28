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
$clrotulo->label("am09_sequencial");

$db_opcao = 1;

$oTipoLicenca  = new TipoLicenca( );
$oTipoLicencas = $oTipoLicenca->getTiposDescricoes();
$aTipoLicencas = array(''  => 'Todos');
foreach ($oTipoLicencas as $aTipoLicenca) {
  $aTipoLicencas[$aTipoLicenca->am09_sequencial] = $aTipoLicenca->am09_descricao;
}

$aCondicoes = array('' => 'Selecione', 'vencidas' => 'Vencidas', 'vencer' => 'A vencer');

$aOrdem     = array('7' => 'Data de Vencimento',
                    '2' => 'Código do Empreendimento',
                    '1' => 'Número da Licença');
?>
<form name="formVencimentoLicencas" id="formVencimentoLicencas" method="post" action="">

  <fieldset style="width: 510px;">
    <legend>Vencimento de Licenças</legend>

    <table class="form-container">

       <tr>
          <td nowrap title="<?php echo $Tam09_sequencial; ?>" width="157px">
            <label for="<?php echo $Lam09_sequencial; ?>"><?php echo $Lam09_sequencial; ?></label>
          </td>
          <td>
            <?php
              db_select('am09_sequencial', $aTipoLicencas, true, $db_opcao);
            ?>
          </td>
       </tr>

       <tr>
          <td nowrap title="Condição das licenças a serem emitidas">
            <label for="condicao">Condição:</label>
          </td>
          <td>
            <?php
              db_select('condicao', $aCondicoes, true, $db_opcao, "onchange='js_alterarCondicao( this.value )'");
            ?>
          </td>
       </tr>

        <tr id="intervaloDataInicial" class="hide">
          <td nowrap title="Data Inicial">
            <label for="dataInicial">Data Inicial:</label>
          </td>
          <td>
            <?php

              $sDataHoje = db_getsession("DB_datausu");
              $sDia = date( "d", $sDataHoje );
              $sMes = date( "m", $sDataHoje );
              $sAno = date( "Y", $sDataHoje );

              db_inputdata('dataInicial', $sDia, $sMes, $sAno, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>

        <tr id="intervaloDataFinal" class="hide">
          <td nowrap title="Data Final">
            <label for="dataFinal">Data Final:</label>
          </td>
          <td>
            <?php
              db_inputdata('dataFinal', null, null, null, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>

       <tr>
          <td nowrap title="Ordem dos registros no relatório">
            <label for="ordem">Ordem:</label>
          </td>
          <td>
            <?php
              db_select('ordem', $aOrdem, true, $db_opcao);
            ?>
          </td>
       </tr>
    </table>

  </fieldset>

  <input name="imprimir" type="button" id="imprimir" value="Imprimir" onclick="return js_validaEmissao();"/>

</form>
<script type="text/javascript">

  var sCaminhoMensagens = "tributario.meioambiente.db_frmvencimentodelicencas.";

  function js_validaEmissao (){

    try {

      if( empty( $F('condicao') ) ){
        throw _M( sCaminhoMensagens + 'campo_obrigatorio', {sCampo: 'Condição'} );
      }

      /**
       * Caso seja a vencer as datas são obrigatórias
       */
      if( $F('condicao') == 'vencer'){

        if( empty( $F('dataInicial') ) ){
          throw _M( sCaminhoMensagens + 'campo_obrigatorio', {sCampo: 'Data Inicial'} );
        }

        if( empty( $F('dataFinal') ) ){
          throw _M( sCaminhoMensagens + 'campo_obrigatorio', {sCampo: 'Data Final'} );
        }

        if ( js_comparadata( $F('dataInicial'), $F('dataFinal'), ">" )  ) {
          throw _M( sCaminhoMensagens + 'datainicial_menor' );
        }

        var data = new Date();
        if ( js_comparadata( $F('dataInicial'), data.getDate() + '/' + (data.getMonth()+1)  + '/'+ data.getFullYear(), "<" )  ) {
          throw _M( sCaminhoMensagens + 'datainicial_menor_atual' );
        }
      }

      js_imprimir();

    } catch (erro) {

      alert(erro);
      return false;
    }

    js_alterarCondicao('');
    document.formVencimentoLicencas.reset();
    return true;
  }

  function js_imprimir() {

    sUrl    = 'amb2_vencimentodelicencas002.php?dataInicial='+$F('dataInicial')+'&dataFinal='+$F('dataFinal')+'&TipoLicenca='+$F('am09_sequencial')+'&ordem='+$F('ordem')+'&condicao='+$F('condicao');
    oJanela = window.open(sUrl, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ', scrollbars=1, location=0 ');
    oJanela.moveTo(0,0);
    return true;
  }

  function js_alterarCondicao ( sCondicaoSelecionada ) {

    switch ( sCondicaoSelecionada ) {

      case 'vencer':

        $('intervaloDataInicial').removeClassName('hide');
        $('intervaloDataFinal').removeClassName('hide');
        break;

      case 'vencidas':

        $('intervaloDataInicial').addClassName('hide');
        $('intervaloDataFinal').addClassName('hide');
        break;

      default:

        $('dataInicial').readOnly      = '';
        $('dtjs_dataInicial').disabled = '';
        $('intervaloDataInicial').addClassName('hide');
        $('intervaloDataFinal').addClassName('hide');
        break;
    }
  }
</script>