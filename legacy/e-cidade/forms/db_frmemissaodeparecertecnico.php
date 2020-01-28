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
$clrotulo->label("am05_sequencial");
$clrotulo->label("am05_nome");
$clrotulo->label("am08_protprocesso");
$clrotulo->label("am08_tipolicenca");
$clrotulo->label("am08_dataemissao");
$clrotulo->label("am08_datavencimento");
$clrotulo->label("am08_observacao");
$clrotulo->label("am08_favoravel");

$db_opcao = 1;
?>
<form name="formEmissaoParecer" id="formEmissaoParecer" method="post" action="">

  <fieldset style="width: 590px;">
    <legend>Parecer Técnico</legend>

    <table class="form-container">

      <tr>
        <td colspan="2">
          <fieldset id="containerEmpreendimento">
            <legend>Dados do Empreendimento</legend>
            <table>
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
              <tr class="hide" id="tdNomeFantasia">
                <td> <strong>Nome Fantasia:</strong> </td>
                <td> <span style="font-weight: lighter;" id="sNomeFantasia"></span> </td>
              </tr>
              <tr class="hide" id="tdCNPJ">
                <td> <strong>CNPJ:</strong> </td>
                <td> <span style="font-weight: lighter;" id="iCNPJ"></span> </td>
              </tr>
              <tr class="hide" id="tdNomeEmpreendedor">
                <td> <strong>Empreendedor:</strong> </td>
                <td> <span style="font-weight: lighter;" id="sNomeEmpreendedor"></span> </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Tam08_protprocesso; ?>">
         <?php
          db_ancora('Código do Processo:',' js_pesquisaProcesso(true); ',1);
         ?>
        </td>
        <td>
         <?php
          db_input('am08_protprocesso',5,1,true,'text',1,"onchange='js_pesquisaProcesso(false)'");
          db_input('p51_descr',62,0,true,'text',3,"",null);
         ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Tam08_favoravel; ?>">
          <label for="am08_favoravel"><?php echo $Lam08_favoravel; ?></label>
        </td>
        <td>
          <?php
            $aOpcoes = array( ''      => 'Selecione',
                              'true'  => 'Sim',
                              'false' => 'Não'
                            );
            db_select('am08_favoravel', $aOpcoes, true, $db_opcao, "onchange='js_alterarFavoravel(this.value)'");
          ?>
        </td>
      </tr>
    </table>

    <div id="containerFormulario">

      <fieldset>

        <legend>Dados da Licença</legend>

        <table class="form-container">

          <tr>
            <td nowrap title="<?php echo $Tam08_tipolicenca; ?>" width="157px">
              <label for="<?php echo $Lam08_tipolicenca; ?>"><?php echo $Lam08_tipolicenca; ?></label>
            </td>
            <td>
              <?php
                $aOpcoes = array(''  => 'Selecione');
                db_select('am08_tipolicenca', $aOpcoes, true, $db_opcao, "onchange='js_getTiposEmissao()'");
              ?>
            </td>
          </tr>

           <tr>
            <td>
              <label for="tipoEmissao">Tipo de Emissão:</label>
            </td>
            <td>
              <?php
                db_select('tipoEmissao', $aOpcoes, true, $db_opcao);
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tam08_dataemissao; ?>">
              <label for="<?php echo $Lam08_dataemissao; ?>"><?php echo $Lam08_dataemissao; ?></label>
            </td>
            <td>
              <?php

                $sDataHoje = date( "d/m/Y", db_getsession("DB_datausu") );
                $aDataHoje = explode("/", $sDataHoje);
                $am08_dataemissao_dia = $aDataHoje[0];
                $am08_dataemissao_mes = $aDataHoje[1];
                $am08_dataemissao_ano = $aDataHoje[2];

                db_inputdata('am08_dataemissao',$am08_dataemissao_dia,$am08_dataemissao_mes,$am08_dataemissao_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tam08_datavencimento; ?>">
              <label for="<?php echo $Lam08_datavencimento; ?>"><?php echo $Lam08_datavencimento; ?></label>
            </td>
            <td>
              <?php

                $am08_datavencimento_dia = '';
                $am08_datavencimento_mes = '';
                $am08_datavencimento_ano = '';
                db_inputdata('am08_datavencimento',$am08_datavencimento_dia,$am08_datavencimento_mes,$am08_datavencimento_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>

        </table>

      </fieldset>


      <fieldset>

        <legend>Condicionantes</legend>

        <table class="form-container">
          <tr>
            <td width="157px"><label for="descricao">Descrição:</label></td>
            <td>
              <?php db_textarea('descricao', 2, 47, null, true, 'text', $db_opcao ); ?>
            </td>
            <td>
              <input type="button" name="lancarDescricao" id="lancarDescricao" value="Lançar" onclick="js_lancarDescricao()" disabled />
            </td>
          </tr>

          <tr>
            <td colspan="3" style="padding-top: 5px;">
              <div id="containerGridCondicionantes"></div>
            </td>
          </tr>
        </table>

      </fieldset>
    </div>

    <table id="containerObservacao" class="form-container">
      <tr>
         <td width="120px"><label for="am08_observacao">Observação:</label></td>
         <td>
           <?php db_textarea('am08_observacao', 5, 10, null, true, 'text', $db_opcao ); ?>
         </td>
      </tr>
    </table>

  </fieldset>

  <input name="emitir" type="button" id="emitir" value="Emitir" onclick="return js_validaEmissao();"/>
  <input name="limpar" type="reset"  id="limpar" value="Limpar" onclick="js_limpaFormulario()"/>

</form>
<style type="text/css">
#containerGridCondicionantes { width: 590px !important; }
#Condicionantesbody tbody td:nth-child(+1) {
  width: 21px !important;
  text-align: center;
}
#body-container-Condicionantes { overflow-x: hidden !important; }
#Condicionantesbody tbody td:nth-child(+3) { width: 560px !important; }
#Condicionantesbody tbody td:nth-child(+4) { display: none; }
#Condicionantesbody tbody td:nth-child(+5) { display: none; }
.form-container textarea{ min-height: 39px !important; }
</style>
<script type="text/javascript">

var sCaminhoMensagens = "tributario.meioambiente.db_frmemissaodeparecertecnico.";
var sRpc              = "amb4_emissaodeparecertecnico.RPC.php";

/**
 * Alterar Tipo de parecer
 */
function js_alterarFavoravel( iTipoParecer ){

  if( empty($F('am05_sequencial')) && iTipoParecer != 0  ){
    return false;
  }

  $$('label[for="am08_observacao"]').first().update('Observação:')
  $('containerObservacao').removeClassName('hide');

  switch ( iTipoParecer ) {

    case 'true': //Favoravel

      $('containerFormulario').removeClassName('hide');
      break;

    case 'false': // Nao Favoravel

      $$('label[for="am08_observacao"]').first().update('Justificativa:')
      $('containerFormulario').addClassName('hide');
      break;

    default:

      $('containerFormulario').addClassName('hide');
      $('containerObservacao').addClassName('hide');
      break;
  }

}
js_alterarFavoravel(0);
$('am08_favoravel').disabled = true;


/**
 * Função que reseta o formulário, mantendo os dados do empreendimento
 */
function js_limpaFormulario() {


  var iCodigoEmpreendimento = $F('am05_sequencial');
  $('formEmissaoParecer').reset();
  oGridCondicionantes.clearAll( true );
  js_alterarFavoravel( 0 );
  $('lancarDescricao').disabled = true;
  $('am08_favoravel').disabled  = true;

  $('sNomeFantasia').innerHTML     = "";
  $('iCNPJ').innerHTML             = "";
  $('sNomeEmpreendedor').innerHTML = "";

  $('tdNomeFantasia').addClassName("hide");
  $('tdCNPJ').addClassName("hide");
  $('tdNomeEmpreendedor').addClassName("hide");

  $('am05_sequencial').value = iCodigoEmpreendimento;
}

/**
 * Emissao de Parecer
 */
function js_validaEmissao(){

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  if( !isNumeric( $F('am08_protprocesso') ) || empty( $F('am08_protprocesso') )  ){

    alert( _M( sCaminhoMensagens + 'processo_obrigatorio' ) );
    return false;
  }

  if( empty( $F('am08_favoravel') ) ){

    alert( _M( sCaminhoMensagens + 'favoravel_obrigatorio' ) );
    return false;
  }

  /**
   * Quando parecer é Favorável
   */
  if( $F('am08_favoravel') == 'true' ){

    if( empty( $F('am08_tipolicenca') ) ){

      alert( _M( sCaminhoMensagens + 'tipo_de_licenca_obrigatorio' ) );
      return false;
    }

    if( empty( $F('tipoEmissao') ) ){

      alert( _M( sCaminhoMensagens + 'tipo_de_emissao_obrigatorio' ) );
      return false;
    }

    if( empty( $F('am08_dataemissao') ) ){

      alert( _M( sCaminhoMensagens + 'data_emissao_obrigatorio' ) );
      return false;
    }

    if( empty( $F('am08_datavencimento') ) ){

      alert( _M( sCaminhoMensagens + 'data_vencimento_obrigatorio' ) );
      return false;
    }

    /**
     * Buscamos todos os elementos marcados da grid para pegar seus dados
     */
    var aCondicionantes         = {};
    var aCondicionantesMarcados = oGridCondicionantes.getElementsByClass('marcado');

    for (var iLinha in aCondicionantesMarcados) {

      if (!isNumeric(iLinha)) {
        break;
      }

      var aElementos = aCondicionantesMarcados[iLinha].getElementsByTagName('td');

      /**
       * Buscamos o elemento da descrição da condicionante
       */
      var sDescricao = encodeURIComponent( aElementos[3].textContent );

      /**
       * Buscamos o elemento que contém o sequencial da condicionante, caso exista
       */
      var iRel = aElementos[4].textContent.trim();

      var aDadosCondicionantes = {
        sDescricao  : sDescricao,
        iSequencial : iRel
      }

      aCondicionantes[iLinha] = aDadosCondicionantes;
    }

    /**
     * Verificamos se houve alguma condicionante selecionada na grid
     */
    if (Object.keys(aCondicionantes).length == 0) {

      alert( _M( sCaminhoMensagens + 'condicionante_obrigatorio' ) );
      return false;
    }

    /**
     * Montamos o array com os dados do parecer, para serem enviados ao RPC
     */
    var oParametros = {
        sExecucao             : 'emitirParecer',
        iCodigoEmpreendimento : $F('am05_sequencial'),
        iCodigoProtocolo      : $F('am08_protprocesso'),
        iTipoLicenca          : $F('am08_tipolicenca'),
        iTipoEmissao          : $F('tipoEmissao'),
        sDataEmissao          : $F('am08_dataemissao'),
        sDataVencimento       : $F('am08_datavencimento'),
        sObservacao           : encodeURIComponent( $F('am08_observacao') ),
        lFavoravel            : $F('am08_favoravel'),
        aCondicionantes       : aCondicionantes
    }

  }else{

    if( empty( $F('am08_observacao') ) ){

      alert( _M( sCaminhoMensagens + 'justificativa_obrigatorio' ) );
      return false;
    }

    /**
     * Quando o Parecer é desfavorável
     */
    var oParametros = {
        sExecucao             : 'emitirParecer',
        iCodigoEmpreendimento : $F('am05_sequencial'),
        iCodigoProtocolo      : $F('am08_protprocesso'),
        sObservacao           : encodeURIComponent( $F('am08_observacao') ),
        lFavoravel            : $F('am08_favoravel'),
        aCondicionantes       : aCondicionantes
    }
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {
      return false;
    }

    /**
     * Utilizar a DBDownload.widget.js
     */
    var oDownload = new DBDownload();
    oDownload.addGroups( 'pdf', 'Parecer Técnico');
    oDownload.addFile( oRetorno.sArquivoRetorno, 'Download do Parecer Técnico', 'pdf' );
    oDownload.show();

    $('limpar').click();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_emissao' ) ).execute();
}

/**
 * Func Empreendimento
 */
function js_pesquisaEmpreendimento(mostra) {

  js_limpaFormulario();

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?funcao_js=parent.js_mostraempreendimento1|0|1','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?pesquisa_chave='+document.formEmissaoParecer.am05_sequencial.value+'&funcao_js=parent.js_mostraempreendimento','Pesquisa',false,0);
  }
}

function js_mostraempreendimento1(chave1,chave2) {

  document.formEmissaoParecer.am05_sequencial.value = chave1;
  document.formEmissaoParecer.am05_nome.value       = chave2;
  db_iframe_empreendimento.hide();
  js_getTiposLicenca();
  js_getDadosEmpreendimento();
}

function js_mostraempreendimento(chave,erro) {

  document.formEmissaoParecer.am05_nome.value = chave;
  if ( erro == true ) {

    document.formEmissaoParecer.am05_sequencial.focus();
    document.formEmissaoParecer.am05_sequencial.value = '';
  } else {

    js_getTiposLicenca();
    js_getDadosEmpreendimento();
  }
}

function js_getDadosEmpreendimento() {

  var oParametros = {
    sExecucao       : 'getDadosEmpreendimento',
    iEmpreendimento : $F('am05_sequencial')
  }

  new AjaxRequest( sRpc, oParametros, function( oRetorno, erro ) {

    if ( erro ) {

      alert( oRetorno.sMensagem.urlDecode() );
      return false;
    }

    $('sNomeFantasia').innerHTML     = oRetorno.sNomeFantasia.urlDecode();
    $('iCNPJ').innerHTML             = js_formatar( oRetorno.iCNPJ.urlDecode(), 'cpfcnpj', 0);
    $('sNomeEmpreendedor').innerHTML = oRetorno.sNomeEmpreendedor.urlDecode();

    if ( $('sNomeFantasia').innerHTML != "" ) {
      $('tdNomeFantasia').removeClassName("hide");
    }

    if ( $('iCNPJ').innerHTML != "" ) {
      $('tdCNPJ').removeClassName("hide");
    }

    if ( $('sNomeEmpreendedor').innerHTML != "" ) {
      $('tdNomeEmpreendedor').removeClassName("hide");
    }
  } ).setMessage( _M( sCaminhoMensagens + 'carregando_empreendimento' ) ).execute();
}

/**
 * Func protprocesso
 */
function js_pesquisaProcesso(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p51_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?pesquisa_chave='+document.formEmissaoParecer.am08_protprocesso.value+'&rettipoproc=true&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
  }
}

function js_mostraprocesso1(chave1,chave2){

  document.formEmissaoParecer.am08_protprocesso.value = chave1;
  document.formEmissaoParecer.p51_descr.value         = chave2;
  db_iframe_proc.hide();
}

function js_mostraprocesso(chave, sDescricao, lErro){

  document.formEmissaoParecer.p51_descr.value = sDescricao;
  if( lErro==true){

    document.formEmissaoParecer.am08_protprocesso.focus();
    document.formEmissaoParecer.am08_protprocesso.value = '';
  }
}

/**
 * Função que retorna os tipos de licença disponíveis para emissão
 */
function js_getTiposLicenca(){

  /**
   * Caso não possua empreendimento informado, bloqueamos o combo favoravel
   */
  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    $('am08_favoravel').disabled = true;
    return false;
  }

  $('am08_favoravel').disabled = false;

  var oParametros = {
      sExecucao             : 'getTiposLicenca',
      iCodigoEmpreendimento : $F('am05_sequencial')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Remove options e adiciona o padrão
     */
    var oSelectLicenca       = $('am08_tipolicenca');

    oSelectLicenca.innerHTML = '';
    var option               = document.createElement("option");
    option.text              = 'Selecione';
    option.value             = '';
    oSelectLicenca.add(option);

    for (var key in oRetorno.aTiposLicenca) {

      if (!isNumeric(key)) {
        break;
      }

      var option   = document.createElement("option");
      option.text  = oRetorno.aTiposLicenca[key];
      option.value = key;
      oSelectLicenca.add(option);
    }
  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_tipolicenca' ) ).execute();
}

/**
 * Função que retorna os tipos de emissão disponíveis
 */
function js_getTiposEmissao(){

  oGridCondicionantes.clearAll( true );

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  if ( !isNumeric( $F('am08_tipolicenca') ) ) {
    return false;
  }

  var oParametros = {
      sExecucao             : 'getTiposEmissao',
      iCodigoEmpreendimento : $F('am05_sequencial'),
      iTipoLicenca          : $F('am08_tipolicenca')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Remove options e adiciona o padrão
     */
    var oSelectEmissao       = $('tipoEmissao');

    oSelectEmissao.innerHTML = '';
    var option               = document.createElement("option");
    option.text              = 'Selecione';
    option.value             = '';
    oSelectEmissao.add(option);

    for (var key in oRetorno.aTiposEmissao) {

      if (!isNumeric(key)) {
        break;
      }

      var option   = document.createElement("option");
      option.text  = oRetorno.aTiposEmissao[key];
      option.value = key;
      oSelectEmissao.add(option);
    }

    /**
     * Buscamos as condicionantes disponíveis para o tipo de licença
     * e atividades vinculadas ao empreendimento
     */
      var oParametros = {
          sExecucao             : 'getCondicionantesEmpreendimento',
          iCodigoEmpreendimento : $F('am05_sequencial'),
          iTipoLicenca          : $F('am08_tipolicenca')
      }

      new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

        if (erro) {

          alert(oRetorno.sMensagem.urlDecode());
          return false;
        }

        for (var key in oRetorno.aCondicionantes) {

          if (!isNumeric(key)) {
            break;
          }

          /**
           * Reduzimos a string para 82 caracteres e
           * adicionamos um hint com o texto inteiro
           */
          sDescricaoHint = oRetorno.aCondicionantes[key].am10_descricao.urlDecode();
          sDescricao     = sDescricaoHint.substr(0,82);
          if( sDescricaoHint.length > 82 ){
            var sDescricao   = sDescricao + '...';
          }

          iIndice     = 0;
          aItensGrid  = document.getElementsByClassName("checkboxCondicionantes");
          iIndice     = aItensGrid.length;

          /**
           * Adicionamos a condicionante e renderizamos o grid
           */
          var aLinha    = [];
              aLinha[0] = key;
              aLinha[1] = sDescricao;
              aLinha[2] = sDescricaoHint;
              aLinha[3] = oRetorno.aCondicionantes[key].am10_sequencial;

          lChecked = false;
          if (oRetorno.aCondicionantes[key].am10_padrao == 't') {
            lChecked = true;
          }

          oGridCondicionantes.addRow( aLinha, true, false, lChecked );
        }

        oGridCondicionantes.getRows().each( function( oLinha, iLinha ) {

          $(oLinha.getId()).setAttribute('rel', oRetorno.aCondicionantes[iLinha].am10_sequencial);
          oGridCondicionantes.setHint( oLinha.getRowNumber(), 2, oLinha.aCells[3].content );
        });

      }).setMessage( _M( sCaminhoMensagens + 'carregando_condicionantes' ) ).execute();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_tipolicenca' ) ).execute();

  $('lancarDescricao').disabled = false;
}

/**
 * Grid Condicionantes
 * @type {DBGrid}
 */
oGridCondicionantes = new DBGrid('Condicionantes');
oGridCondicionantes.nameInstance = 'oGridCondicionantes';
oGridCondicionantes.setCheckbox(0);
oGridCondicionantes.hasCheckbox  = true;
oGridCondicionantes.setCellWidth( new Array( '11px', '560px', '1px', '1px') );
oGridCondicionantes.setCellAlign( new Array( 'center', 'left', 'center', 'center' ) );
oGridCondicionantes.setHeader( new Array( '', 'Descrição', '', '' ) );
oGridCondicionantes.setHeight( 200 );

oGridCondicionantes.show( $('containerGridCondicionantes') );
oGridCondicionantes.showColumn( false, 1 );
oGridCondicionantes.clearAll( true );
oGridCondicionantes.setStatus("Selecione as condicionantes que serão adicionadas ao parecer.");

/**
 * Lançar Condicionante Complementar
 */
function js_lancarDescricao(){

  sDescricao = $F('descricao');

  if( empty( sDescricao ) ){

    alert(_M( sCaminhoMensagens + 'descricao_obrigatorio' ) );
    return false;
  }

  /**
   * Reduzimos a string para 82 caracteres e
   * adicionamos um hint com o texto inteiro
   */
  sDescricaoHint = sDescricao.urlDecode();
  sDescricao     = sDescricaoHint.substr(0,82);
  if( sDescricaoHint.length > 82 ){
    var sDescricao   = sDescricao + '...';
  }

  /**
   * Buscamos o índice do último elemento da grid para que
   * sejam adicionados elementos após
   */
  iIndice     = 0;
  aItensGrid  = document.getElementsByClassName("checkboxCondicionantes");
  iIndice     = aItensGrid.length;

  /**
   * Adicionamos a condicionante e renderizamos o grid
   */
  var aLinha    = [];
      aLinha[0] = iIndice;
      aLinha[1] = sDescricao;
      aLinha[2] = sDescricaoHint;
      aLinha[3] = '';

  /**
   * Armazenamos os itens selecionados antes de renderizar a grid
   */
  var oSelecionados = oGridCondicionantes.getSelection("object");

  oGridCondicionantes.addRow( aLinha, true, false, true );

  /**
   * Verificamos a segunda coluna e recriamos os hint's
   */
  oGridCondicionantes.getRows().each( function( oLinha, iLinha ) {
    oGridCondicionantes.setHint( oLinha.getRowNumber(), 2, oLinha.aCells[3].content );
  });

 /**
   * Varremos a grid selecionando os itens que estao dentro do
   * array de objetos que armazenamos antes do renderRows
   */
  aItensGrid = document.getElementsByClassName("checkboxCondicionantes");

  for (var iIndice = 0; iIndice < aItensGrid.length; iIndice++){

    var sIdLinha = 'CondicionantesrowCondicionantes' + iIndice;

    aItensGrid[iIndice].checked = false;
    $( sIdLinha ).className     = 'normal';
    oGridCondicionantes.aRows[iIndice].isSelected = false;

    /**
     * Verificamos o array e batemos contra o id da linha
     */
    oSelecionados.each( function( oRowSelecionada ) {

      if( sIdLinha == oRowSelecionada.getId() ){

        aItensGrid[iIndice].checked = true;
        $( sIdLinha ).className     = 'marcado';
        oGridCondicionantes.aRows[iIndice].isSelected = true;
      }
    });
   }

   /**
    * Por padrao o ultimo item adicionando sempre fica marcado
    */
   iIndice = parseInt(iIndice - 1);
   aItensGrid[iIndice].checked = true;
   $( sIdLinha ).className = 'marcado';
   oGridCondicionantes.aRows[iIndice].isSelected = true;

   $('descricao').value = '';
}

</script>