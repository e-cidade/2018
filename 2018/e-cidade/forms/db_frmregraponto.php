<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: pessoal
$clregraponto->rotulo->label();
$clregraponto->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("r44_descr");

$aComportamento  = array('1' => 'Aviso', '2' => 'Bloqueio');
$sLabelProcessar = '';
$sDisabled       = '';
$iInstituicao    = db_getsession('DB_instit');

switch ( $db_opcao ) {

  case 1 :
    $sLabelProcessar = 'Incluir';
    break;

  case 2 :
  case 22 :
    $sLabelProcessar = 'Alterar';
    break;

  case 3 :
  case 33 :
    $sLabelProcessar = 'Excluir';
    break;
}

if ( !$db_botao ) {
  $sDisabled = 'disabled';
}
?>

<center>

  <form name="form1" method="post" action="">

    <?php db_input('rh123_sequencial', 20, $Irh123_sequencial, true, 'hidden', $db_opcao); ?>

    <fieldset class="container" style="width:600px;">

      <legend>Manutenção de Regras</legend>

      <table border="0" align="center">

        <tr>
          <td nowrap title="<?php echo $Trh123_descricao; ?>">
            <label for="rh123_descricao">
              <?php echo $Lrh123_descricao; ?>
            </label>
          </td>
          <td>
            <?php db_input('rh123_descricao', 30, 0, true, 'text', $db_opcao, 'class="field-size-max"'); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh123_selecao; ?>">
            <label for="rh123_selecao">
              <?php db_ancora('<strong>Seleção:</strong>',"js_pesquisaSelecao(true);",$db_opcao); ?>
            </label>
          </td>
          <td>
            <?php
            db_input('rh123_selecao', 8, $Irh123_selecao, true, 'text', $db_opcao, "onchange='js_pesquisaSelecao(false);'");
            db_input('r44_descr', 50, $Ir44_descr, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <div id="rubricas"></div>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh123_comportamento; ?>">
            <label for="rh123_comportamento">
              <?php echo $Lrh123_comportamento; ?>
            </label>
          </td>
          <td>
            <?php db_select('rh123_comportamento', $aComportamento, true, $db_opcao); ?>
          </td>
        </tr>

      </table>

    </fieldset>

    <br />
    <input onClick="js_processar();" name="<?php echo strtolower($sLabelProcessar); ?>" type="button" id="processar" value="<?php echo $sLabelProcessar; ?>" <?php echo $sDisabled; ?> />

    <?php if ($db_opcao != 1) : ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php endif; ?>

  </form>

</center>

<script type="text/javascript">

  /**
   * RPC
   */
  var sUrlRPC = 'pes1_rhrubricas.RPC.php';

  /**
   * Instituicao da sessao
   */
  var iInstituicao = '<?php echo $iInstituicao; ?>';

  /**
   * dbpocao
   * 1 - incluir
   * 2 - alterar
   * 3 - excluir
   */
  var iOpcao    = '<?php echo $db_opcao; ?>';

  var lHabilita = '<?php echo $db_botao; ?>' == '1';

  /**
   * Cria lancador de rubricas
   */
  var oLancadorRubricas = new DBLancador('LancadorRubricas');
  oLancadorRubricas.setNomeInstancia('oLancadorRubricas');
  oLancadorRubricas.setGridHeight(150);
  oLancadorRubricas.setLabelAncora('Rubrica:');
  oLancadorRubricas.setTextoFieldset('Rubricas: ');
  oLancadorRubricas.setParametrosPesquisa('func_rhrubricas.php', ['rh27_rubric' , 'rh27_descr'], 'instit=' + iInstituicao);

  /**
   * Quando for exclusão, desabilita grid para lançar rubricas
   */
  oLancadorRubricas.setHabilitado( lHabilita );
  oLancadorRubricas.show( $('rubricas') );
  oLancadorRubricas.setTipoValidacao('3');

  /**
   * Alteração
   */
  if ( iOpcao == 2 || iOpcao == 3 ) {

    /**
     * Busca rubricas cadastrada para regra
     */
    (function() {

      if ( $('rh123_sequencial').value == '' ) {

        alert('Erro ao buscar código da regra.');
        return false;
      }

      var oParametros  = new Object();
      oParametros.sExecucao = 'getRubricasRegra';
      oParametros.iRegraPonto = $('rh123_sequencial').value;

      js_divCarregando('Carregando rubricas...', 'msgBox');

      var oAjax = new Ajax.Request(
        sUrlRPC,
        {
          method     : 'post',
          parameters : 'json=' + Object.toJSON(oParametros),
          onComplete : js_retornoCarregarRubricas
        }
      );

      /**
       * Retorno do RPC
       * - Adiciona rubricas da regas a grid
       *
       * @param Object $oAjax
       * @access public
       * @return void
       */
      function js_retornoCarregarRubricas(oAjax) {

        js_removeObj('msgBox');

        var oRetorno  = eval("("+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode().replace(/\\n/g, "\n");

        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        aRubricasLancadas = new Array();

        /**
         * Percorre o array retornado pelo RPC das rubricas da regra e adiciona a grid do DBLancador
         */
        for ( var iIndice = 0; iIndice < oRetorno.aRubricasLancadas.length; iIndice++ ) {

          var oRubricaLancada = oRetorno.aRubricasLancadas[iIndice];
          aRubricasLancadas.push([ oRubricaLancada.codigo, oRubricaLancada.descricao]);
        }

        oLancadorRubricas.carregarRegistros(aRubricasLancadas);
      }

    })();
  }

  /**
   * Processar
   * - Incluir, altera ou exclui regras
   *
   * @access public
   * @return void
   */
  function js_processar() {

    lValidacaoFormulario = js_validarFormulario();

    if ( !lValidacaoFormulario ) {
      return false;
    }

    var aRubricas    = new Array();
    var oParametros  = new Object();

    oLancadorRubricas.getRegistros().each(function(oLinhaRubrica) {
      aRubricas.push(oLinhaRubrica.sCodigo);
    });

    oParametros.sExecucao      = $('processar').name;
    oParametros.sDescricao     = encodeURIComponent( tagString( $('rh123_descricao').value ) );
    oParametros.iSelecao       = $('rh123_selecao').value;
    oParametros.aRubricas      = aRubricas;
    oParametros.iComportamento = $('rh123_comportamento').value;

    if ( iOpcao == 2 || iOpcao == 3 ) {
      oParametros.iRegraPonto = $('rh123_sequencial').value;
    }

    js_divCarregando('Processando...', 'msgBox');

    var oAjax = new Ajax.Request(
      sUrlRPC,
      {
        method     : 'post',
        parameters : 'json=' + Object.toJSON(oParametros),
        onComplete : js_retornoProcessar
      }
    );
  }

  function js_retornoProcessar(oAjax) {

    js_removeObj('msgBox');

    var oRetorno  = eval("("+oAjax.responseText+")");
    var sMensagem = oRetorno.sMensagem.urlDecode();

    if ( oRetorno.iStatus > 1 ) {

      alert(sMensagem);
      return false;
    }

    alert(sMensagem);
    location.href = '<?php echo basename($_SERVER['PHP_SELF']); ?>';
  }

  /**
   * Validacao do formulario
   *
   * @access public
   * @return boolan
   */
  function js_validarFormulario() {

    /**
     * Não valida formulario quando for exclusao
     */
    if ( iOpcao == 3 ) {
      return true
    }

    /**
     * Descrição
     */
    if ( $('rh123_descricao').value == '' ) {

      alert('Campo descrição não informado.');
      return false;
    }

    /**
     * Selecao
     */
    if ( $('rh123_selecao').value == '' ) {

      alert('Campo seleção não informado.');
      return false;
    }

    /**
     * Rubricas
     */
    if ( oLancadorRubricas.getRegistros() == '' ) {

      alert('Nenhuma rubrica informada.');
      return false;
    }

    return true;
  }

  /**
   * Pesquisa selecao
   *
   * @param boolean $lMostra
   * @access public
   * @return boolean
   */
  function js_pesquisaSelecao(lMostra) {

    /**
     * Func de pesquisa da selecao, passando parametro do id do grupo 2 - regras do ponto
     */
    var sPrograma = 'func_selecao.php?sGrupoSelecao=2&funcao_js=parent.';

    if ( lMostra ) {

      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_selecao', sPrograma + 'js_retornoPesquisaSelecaoOnClick|r44_selec|r44_descr&instit=' + iInstituicao, 'Pesquisa', true);
      return false;
    }

    if ( $F('rh123_selecao') == "" ) {

      $('r44_descr').setValue("");
      return false;
    }

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_selecao', sPrograma + 'js_retornoPesquisaSelecaoOnChange&instit=' + iInstituicao + '&pesquisa_chave=' + $F('rh123_selecao'), 'Pesquisa',false);
  }

  /**
   * Pesquisar selecao - onchange selecao
   * - Caso nao encontra selecao pelo codigo
   *   limpa campo rh123_selecao
   *   exibe msg de no campo r44_descr
   *   Move cursor para o input
   *
   * @param string $sDescricao
   * @param boolean $lErro
   * @access public
   * @return void
   */
  function js_retornoPesquisaSelecaoOnChange(sDescricao, lErro) {

    /**
     * Selecao nao encontrada
     */
    if ( lErro ) {

      $('rh123_selecao').setValue('');
      $('rh123_selecao').focus();
    }

    $('r44_descr').setValue(sDescricao);
  }

  /**
   * Pesquisar selecao - ancora
   * - Chamada ao clicar no ancora da selecao e selecionar uma item na grid(lovrot)
   * - Esconde iframe
   *
   * @param integer $iSelecao
   * @param string $sDescricao
   * @access public
   * @return void
   */
  function js_retornoPesquisaSelecaoOnClick(iSelecao, sDescricao) {

    $(rh123_selecao).setValue(iSelecao);

    if ( $('r44_descr') ) {
      $('r44_descr').setValue(sDescricao);
    }

    db_iframe_selecao.hide();
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_regraponto','func_regraponto.php?funcao_js=parent.js_preenchepesquisa|rh123_sequencial','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){

    db_iframe_regraponto.hide();
    <?php
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
</script>