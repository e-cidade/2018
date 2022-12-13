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

$oDaoReciboUnicaGeracao = new cl_recibounicageracao;
$oDaoReciboUnicaGeracao->rotulo->label();

$oDaoReciboUnica        = new cl_recibounica;
$oDaoReciboUnica->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");

?>
 <form action="" method="post" name="formulario" id="formulario">

   <fieldset style="min-width: 690px;">
     <legend>Exclusão de Única Individual</legend>

     <fieldset>
       <legend>Origem</legend>

       <table class="form-container">
         <tr>
           <td style="width: 150px;"><a href="" id="labelCgm"><?php echo $Lz01_numcgm; ?></a></td>
           <td>
             <?php

               db_input("z01_numcgm", 4,  1, true, "text", 1);
               db_input("z01_nome",   40, 1, true, "text", 3);
             ?>
           </td>
         </tr>
         <tr>
           <td><a href="" id="labelMatricula"><?php echo $Lj01_matric; ?></a></td>
           <td>
             <?php
               db_input("j01_matric", 4,  1, true, "text", 1);
             ?>
           </td>
         </tr>
         <tr>
           <td><a href="" id="labelInscricao"><?php echo $Lq02_inscr; ?></a></td>
           <td>
             <?php
               db_input("q02_inscr", 4,  1, true, "text", 1);
             ?>
           </td>
         </tr>
       </table>
     </fieldset>

     <fieldset>
       <legend>Geração</legend>

       <table class="form-container">
         <tr>
           <td style="width: 150px;"><?php echo $Lar40_dtoperacao; ?></td>
           <td><?php db_inputdata("dataoperacao_inicial", "", "", "", true, "", 1); ?> <strong>a</strong>
               <?php db_inputdata("dataoperacao_final", "", "", "", true, "", 1);   ?></td>
         </tr>
         <tr>
           <td><?php echo $Lar40_dtvencimento; ?></td>
           <td><?php db_inputdata("datavencimento_inicial", "", "", "", true, "", 1); ?> <strong>a</strong>
               <?php db_inputdata("datavencimento_final", "", "", "", true, "", 1);   ?></td>
         </tr>
         <tr>
           <td><?php echo $Lar40_percentualdesconto; ?></td>
           <td colspan="2"><?php db_input("percentualdesconto",  10, 4, true, "text", 1 ); ?><strong>%</strong></td>
         </tr>
         <tr>
           <td><?php echo $Lar40_observacao; ?></td>
           <td colspan="2"><?php db_input("observacao", 57, "", true, "text", 1 ); ?></td>
         </tr>

       </table>
     </fieldset>

     <input type="button" value="Processar" name="processar" id="processar" onclick="return js_processar()" />
     <input type="reset"  value="Limpar"    name="limpar"    id="limpar"    onclick="return js_limpar()"    />

     <fieldset style="margin-top: 10px;">
       <legend>Únicas</legend>

       <div id="containerGridDadosUnica"></div>
     </fieldset>

     <input type="button" value="Excluir" name="excluir" id="excluir" onclick="return js_excluir()" disabled="disabled" />

   </fieldset>

</form>

<script type="text/javascript">

  const MENSAGEM = 'tributario.arrecadacao.db_frmexclusaounicaparcial.';
  const RPC      = 'arr4_exclusaounicaparcial.RPC.php';

  /**
   * Grid dos Dados da Unica
   * @type {DBGrid}
   */
  oGridDadosUnica              = new DBGrid('containerGridDadosUnica');
  oGridDadosUnica.nameInstance = 'oGridDadosUnica';
  oGridDadosUnica.hasCheckbox  = true;
  oGridDadosUnica.setCheckbox(1);

  oGridDadosUnica.setCellWidth(new Array('0', '20%', '20%', '12%',  '48%'));
  oGridDadosUnica.setCellAlign(new Array('center', 'center', 'center', 'center', 'left'));
  oGridDadosUnica.setHeader(new Array('id', 'Data de Operação', 'Data de Vencimento', 'Percentual', 'Origem'));
  oGridDadosUnica.setHeight('300');

  oGridDadosUnica.aHeaders[1].lDisplayed = false;

  oGridDadosUnica.show($('containerGridDadosUnica'));
  oGridDadosUnica.clearAll(true);

  /**
   * Ancora para CGM
   * @type  {DBLookUp}
   */
  var oLookUpCgm = new DBLookUp($('labelCgm'), $('z01_numcgm'), $('z01_nome'), {
    'sArquivo'              : 'func_nome.php',
    'sObjetoLookUp'         : 'db_iframe_cgm',
    'sLabel'                : 'Pesquisar CGM',
    'fCallBack'             : js_callBackCgm,
    'oBotaoParaDesabilitar' : $('excluir')
  });

  /**
   * Ancora para CGM
   * @type  {DBLookUp}
   */
  var oLookUpMatricula = new DBLookUp($('labelMatricula'), $('j01_matric'), $('z01_nome'), {
    'sArquivo'              : 'func_iptubase.php',
    'sObjetoLookUp'         : 'db_iframe_iptubase',
    'sLabel'                : 'Pesquisar Matrícula',
    'fCallBack'             : js_callBackMatricula,
    'oBotaoParaDesabilitar' : $('excluir')
  });

  /**
   * Ancora para CGM
   * @type  {DBLookUp}
   */
  var oLookUpInscricao = new DBLookUp($('labelInscricao'), $('q02_inscr'), $('z01_nome'), {
    'sArquivo'              : 'func_issbase.php',
    'sObjetoLookUp'         : 'db_iframe_issbase',
    'sLabel'                : 'Pesquisar Inscrição',
    'fCallBack'             : js_callBackInscricao,
    'oBotaoParaDesabilitar' : $('excluir')
  });

  function js_callBackInscricao (){

    $('j01_matric').value = '';
    $('z01_numcgm').value = '';
  }

  function js_callBackMatricula (){

    $('q02_inscr').value  = '';
    $('z01_numcgm').value = '';
  }

  function js_callBackCgm (){

    $('q02_inscr').value  = '';
    $('j01_matric').value = '';
  }

  /**
   * Efetuamos a validação do formulario e envio dos dados para o RPC
   */
  function js_processar(){

    js_habilitaBotoes(true);
    oGridDadosUnica.clearAll(true);

    try {

      /**
       * Validamos o intervalo de datas de operacao, caso sejam informados
       */
      if( !empty($F('dataoperacao_inicial')) || !empty($F('dataoperacao_final')) ){

        var lDataValida = js_comparadata( $F('dataoperacao_inicial'), $F('dataoperacao_final'), "<");
        if( !lDataValida && ($F('dataoperacao_inicial') != $F('dataoperacao_final'))){
          throw ( _M ( MENSAGEM + 'erro_data_operacao_invalida' ) );
        }
      }

      /**
       * Validamos o intervalo de datas de vencimento, caso sejam informados
       */
      if( !empty($F('datavencimento_inicial')) || !empty($F('datavencimento_final')) ){

        var lDataValida = js_comparadata( $F('datavencimento_inicial'), $F('datavencimento_final'), "<");
        if( !lDataValida && ($F('datavencimento_inicial') != $F('datavencimento_final'))){
          throw ( _M ( MENSAGEM + 'erro_data_vencimento_invalida' ) );
        }
      }

      /**
       * Verificamos se pelo menos algum campo do formulario esta preenchido
       */
      var oFormulario            = $('formulario');
      var oElementos             = oFormulario.elements;
      var lExisteCampoPreenchido = false;
      for (var iIndice in oElementos) {

        var oComponente = oElementos[iIndice];

        if( typeof(oComponente.type) != 'undefined' ){

          if( oComponente.type == 'text' ){

            if( oComponente.value != '' ){
              lExisteCampoPreenchido = true;
            }
          }
        }
      }

      if(!lExisteCampoPreenchido){
        throw ( _M ( MENSAGEM + 'sem_filtro_selecionado' ) );
      }

    }catch ( sMensagemErro ) {

      alert(sMensagemErro);
      js_habilitaBotoes(false);
      return false;
    }

    js_habilitaBotoes(false);

    var oParametros = {
        sExecucao              : 'getDadosUnica',
        iCgm                   : $F('z01_numcgm'),
        iMatricula             : $F('j01_matric'),
        iInscricao             : $F('q02_inscr'),
        sDataOperacaoInicial   : $F('dataoperacao_inicial'),
        sDataOperacaoFinal     : $F('dataoperacao_final'),
        sDataVencimentoInicial : $F('datavencimento_inicial'),
        sDataVencimentoFinal   : $F('datavencimento_final'),
        nPercentualDesconto    : $F('percentualdesconto'),
        sObservacao            : encodeURIComponent(tagString($F('observacao')))
    }

    new AjaxRequest(RPC, oParametros, function(oRetorno, erro) {

      if ( erro ) {

        alert(oRetorno.sMessage.urlDecode());
        return false;
      }

      if( oRetorno.aCotaUnicaParcial.length == 0 ){

        alert( _M( MENSAGEM + 'sem_registros_para_filtro' ) );
        return false;
      }

      oRetorno.aCotaUnicaParcial.each( function (oDado, iIndice) {

        var aRow    = new Array();
            aRow[0] = oDado.k00_sequencial;
            aRow[1] = oDado.k00_dtoper;
            aRow[2] = oDado.k00_dtvenc;
            aRow[3] = oDado.k00_percdes + "%";
            aRow[4] = oDado.origem.urlDecode();

        oGridDadosUnica.addRow(aRow);
      });

      oGridDadosUnica.renderRows();

      oRetorno.aCotaUnicaParcial.each( function (oDado, iIndice) {

        var sHintOrigem = oDado.origem.urlDecode();
        oGridDadosUnica.setHint(iIndice, 5, sHintOrigem);
      });

    }).setMessage( _M( MENSAGEM + 'carregando_dadosunica' ) ).execute();

  }

  function js_excluir(){

    /**
     * Validamos se ha registros selecionados
     */
    aUnicasSelecionadas = oGridDadosUnica.getSelection();

    if(aUnicasSelecionadas == 0){

      alert( _M( MENSAGEM + 'sem_unica_selecionada' ) );
      return false;
    }

    if( !confirm( _M( MENSAGEM + 'confirma_exclusao') ) ){
      return false;
    }

    aUnicasSelecionadas = [];

    oGridDadosUnica.getSelection().each(function(oDadosUnica, iIndice){
      aUnicasSelecionadas[iIndice] = oDadosUnica[1];
    });

    var oParametros = {
      sExecucao    : 'excluirUnica',
      aCodigoUnica : aUnicasSelecionadas
    }

    new AjaxRequest(RPC, oParametros, function(oRetorno, erro) {

      alert(oRetorno.sMessage.urlDecode());

      if(erro){
        return false;
      }

      js_limpar();
      $('excluir').disabled = true;
    }).setMessage( _M( MENSAGEM + 'carregando_exclusao_unica' ) ).execute();

  }

  function js_habilitaBotoes( lHabilita = true ){

    $('processar').disabled = lHabilita;
    $('limpar').disabled    = lHabilita;
    $('excluir').disabled   = lHabilita;
  }

  function js_limpar(){

    $('formulario').reset();
    oGridDadosUnica.clearAll(true);
  }
</script>