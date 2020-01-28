<?
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
$clrhempenhofolhaexcecaorubrica = new cl_rhempenhofolhaexcecaorubrica;
$clrhempenhofolhaexcecaorubrica->rotulo->label();
$clrhempenhofolhaexcecaoregra   = new cl_rhempenhofolhaexcecaoregra;
$clrhempenhofolhaexcecaoregra->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label();
$clrotulo->label("o55_descr");
$clrotulo->label("o56_descr");
$clrotulo->label("o41_descr");
$clrotulo->label("o15_descr");
$clrotulo->label("o54_descr");
$clrotulo->label("o53_descr");
$clrotulo->label("o52_descr");
$clrotulo->label("rh128_descricao");
$clrotulo->label("rh74_tipofolha");
?>
<form class="container" name="form1" method="post" action="" style="width: 700px;">

    <?
      db_input('rh128_sequencial',10,null,true,'hidden',3);
      db_input('db_opcao',10,$db_opcao,true,'hidden',3);
    ?>

    <div id="abas"></div>

    <div id="regras">
      <fieldset>
        <legend>Regras</legend>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Trh74_anousu?>">
                <label id="lbl_rh74_anousu" for="rh74_anousu"><?=@$Lrh74_anousu?></label>
              </td>
              <td>
                <?php
                  db_input('rh74_anousu',10,$Irh74_anousu,true,'text',3," onchange='js_pesquisarh74_anousu(false);'");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label id="lbl_rh128_descricao" for="rh128_descricao"><?=$Lrh128_descricao?></label>
              </td>
              <td>
                <?php
                  db_input('rh128_descricao', 54, $Irh128_descricao, true, 'text', $db_opcao, null);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh74_orgao?>">
                <label id="lbl_rh74_orgao" for="rh74_orgao">
                <?
                  db_ancora(@$Lrh74_orgao,"js_pesquisarh74_orgao(true);",$db_opcao);
                ?>
                </label>
              </td>
              <td>
                <?
                  db_input('rh74_orgao',10,$Irh74_orgao,true,'text',$db_opcao," onchange='js_pesquisarh74_orgao(false);'");
                  db_input('o40_descr',40,$Io41_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh74_unidade?>">
                <label id="lbl_rh74_unidade" for="rh74_unidade">
                <?
                  db_ancora(@$Lrh74_unidade,"js_pesquisarh74_unidade(true);",$db_opcao);
                ?>
              </label>
              </td>
              <td>
                <?
                  db_input('rh74_unidade',10,$Irh74_unidade,true,'text',$db_opcao," onchange='js_pesquisarh74_unidade(false);'");
                  db_input('o41_descr',40,$Io41_descr,true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh74_projativ?>">
                <label id="lbl_rh74_projativ" for="rh74_projativ">
                <?
                  db_ancora(@$Lrh74_projativ,"js_pesquisarh74_projativ(true);",$db_opcao);
                ?>
                </labe>
              </td>
              <td>
                <?
                  db_input('rh74_projativ',10,$Irh74_projativ,true,'text',$db_opcao," onchange='js_pesquisarh74_projativ(false);'");
                  db_input('o55_descr',40,$Io55_descr,true,'text',3,'');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Trh74_recurso?>">
                <label id="lbl_rh74_recurso" for="rh74_recurso">
                <?
                  db_ancora(@$Lrh74_recurso,"js_pesquisarh74_recurso(true);",$db_opcao);
                ?>
                </label>
              </td>
              <td>
                <?
                  db_input('rh74_recurso',10,$Irh74_recurso,true,'text',$db_opcao," onchange='js_pesquisarh74_recurso(false);'");
                  db_input('o15_descr',40,$Io15_descr,true,'text',3,'');
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh74_programa; ?>">
                <label id="lbl_rh74_programa" for="rh74_programa">
                <?php db_ancora($Lrh74_programa, 'js_pesquisa_programa(true)', $db_opcao); ?>
                </label>
              </td>
              <td>
                <?php db_input('rh74_programa', 10, $Irh74_programa, true, 'text', $db_opcao, "onchange='js_pesquisa_programa(false)'"); ?>
                <?php db_input('o54_descr', 40, $Io54_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh74_funcao; ?>">
                <label id="lbl_rh74_funcao" for="rh74_funcao">
                <?php db_ancora($Lrh74_funcao, 'js_pesquisa_funcao(true)', $db_opcao); ?>
                </label>
              </td>
              <td>
                <?php db_input('rh74_funcao', 10, $Irh74_funcao, true, 'text', $db_opcao, "onchange='js_pesquisa_funcao(false)'"); ?>
                <?php db_input('o52_descr', 40, $Io52_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh74_subfuncao; ?>">
                <label id="lbl_rh74_subfuncao" for="rh74_subfuncao">
                <?php db_ancora($Lrh74_subfuncao, 'js_pesquisa_subfuncao(true)', $db_opcao); ?>
                </label>
              </td>
              <td>
                <?php db_input('rh74_subfuncao', 10, $Irh74_subfuncao, true, 'text', $db_opcao, "onchange='js_pesquisa_subfuncao(false)' "); ?>
                <?php db_input('o53_descr', 40, $Io53_descr, true, 'text', 3); ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Trh74_concarpeculiar?>">
                <label id="lbl_rh74_concarpeculiar" for="rh74_concarpeculiar">
                <?
                  db_ancora(@$Lrh74_concarpeculiar,"js_pesquisarh74_concarpeculiar(true);",$db_opcao);
                ?>
                </label>
              </td>
              <td>
                <?
                  db_input("rh74_concarpeculiar",10,$Irh74_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisarh74_concarpeculiar(false);'");
                  db_input("c58_descr",40,0,true,"text",3);
                ?>
              </td>
            </tr>
            <tr id='linhaDesdobramento'>
              <td>
                <label id="lbl_rh74_codele" for="rh74_codele">
                <?
                  db_ancora(@$Lrh74_codele,"js_pesquisarh74_codele(true);",$db_opcao);
                ?>
                </label>
              </td>
              <td>
              <?
                db_input('rh74_codele',10,$Irh74_codele,true,'text',$db_opcao," onchange='js_pesquisarh74_codele(false);'");
                db_input('o56_descr',40,$Io56_descr,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label id="lbl_rh74_tipofolha" for="rh74_tipofolha">
              <?=$Lrh74_tipofolha?>
              </label>
            </td>
            <td>
              <?
                $aTipoFolha = Array( "0" => "Todos",
                                     "1" => "Salário",
                                     "2" => "Complementar",
                                     "3" => "Rescisão",
                                     "4" => "13º Salário",
                                     "5" => "Adiantamento"
                                     );

                db_select("rh74_tipofolha", $aTipoFolha, true, $db_opcao, "onchange= 'js_getRubricas();'");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </div>

    <div id="rubricas">
      <fieldset>
        <legend>Rubricas</legend>

          <div id="gridRUbricas"></div>


      </fieldset>
    </div>
    <?php if ($db_opcao == 1) { ?>
            <input type="button" onclick="js_processar()" value="Incluir" />
    <?php } else  if($db_opcao == 2){ ?>
            <input type="button" onclick="js_processar()" value="Alterar" />
    <?php } else { ?>
            <input type="button" onclick="js_processar()" value="Excluir" />
    <?php } ?>

    <input type="button" value="Pesquisar" onclick="js_pesquisaRegra();" />
  </form>
<script type="text/javascript">

    <?php if ($db_opcao != 1) { ?>
            js_pesquisaRegra();
    <?php } ?>

    var sUrlRPC = "pes1_rhempenhofolhaexcecaorubrica.RPC.php";
    var MENSAGENS = "recursoshumanos.pessoal.pes1_rhempenhofolhaexcecaorubrica.";

    /**
     * Validação do formulário para envio
     */
    function js_validaEnvio() {

      try{

        if( $F('rh128_descricao') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_descricao_obrigatorio' ) );
        }

        if( $F('rh74_orgao') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_orgao_obrigatorio' ) );
        }

        if( $F('rh74_unidade') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_unidade_obrigatorio' ) );
        }

        if( $F('rh74_projativ') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_projativ_obrigatorio' ) );
        }

        if( $F('rh74_recurso') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_recurso_obrigatorio' ) );
        }

        if( $F('rh74_concarpeculiar') == '' ){
          throw( _M( MENSAGENS + 'preenchimento_concarpeculiar_obrigatorio' ) );
        }

        if( oGridRubricas.getSelection().length == 0){
          throw( _M( MENSAGENS + 'preenchimento_rubrica_obrigatorio' ) );
        }

        /**
         * Valida Rubricas
         */
        var aRubricasConflitantes = js_validaRubricasSelecionadas();

        if( aRubricasConflitantes.length > 0 ){
          throw( _M( MENSAGENS + 'rubrica_regra_mesmo_tipo_folha' , { sRubricas : aRubricasConflitantes.join(', ') }) );
        }

      } catch (oException) {
        alert(oException);
        return false;
      }

      return true;
    }

    /**
     * Cria as abas
     */
    var oAbas = new DBAbas($('abas'));
    oAbas.adicionarAba("Regras",   $('regras'));
    oAbas.adicionarAba("Rubricas", $('rubricas'));

    /**
     * Cria grid das rubricas
     */
    var oGridRubricas = new DBGrid("GridRubricas");

    oGridRubricas.nameInstance = 'oGridRubricas';
    oGridRubricas.setCellWidth(["&nbsp;", "10%", "90%"]);
    oGridRubricas.setCellAlign(["center", "left", "left"]);
    oGridRubricas.setCheckbox(1);
    oGridRubricas.setHeader(["&nbsp;", "Código", "Descrição"]);
    oGridRubricas.aHeaders[1].lDisplayed = false;
    oGridRubricas.show($("gridRUbricas"));
    oGridRubricas.clearAll(true);

    js_getRubricas();

    /**
     * Busca as rubricas de acordo com os parametros da tela
     */
    function js_getRubricas() {

      oGridRubricas.clearAll(true);

      var oParametros               = new Object();
      oParametros.sExecucao         = 'getRubricas';
      oParametros.iExcecaoRegra     = $F('rh128_sequencial');
      oParametros.iTipoFolha        = $F('rh74_tipofolha');

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);

      oDadosRequisicao.onComplete = function(oAjax) {

        var oRetorno = JSON.parse(oAjax.responseText)

        if (oRetorno.iStatus == 2) {
          alert(oRetorno.sMessage);
          return false;
        }

        oRetorno.aRubricas.forEach(function(oValue) {

          oGridRubricas.addRow(["", oValue.rh27_rubric, oValue.rh27_descr.urlDecode()], false, <?php echo $db_opcao!=33&&$db_opcao!=3 ? 'false':'true' ?> , oValue.lselecionado == "t");

        });

        oGridRubricas.renderRows(null, false);

      }
      new Ajax.Request(sUrlRPC, oDadosRequisicao);
    }

    /**
     * Busca rubricas e preenche Grid
     */
    function js_validaRubricasSelecionadas() {

      var oParametros                   = new Object();
      oParametros.sExecucao             = 'validaRubricasSelecionadas';
      oParametros.iTipoFolha            = $F('rh74_tipofolha');
      oParametros.iExcecaoRegra         = $F('rh128_sequencial');
      oParametros.aRubricasSelecionadas = oGridRubricas.getSelection().map(function(aRow) {
                                            return aRow[2];
                                          });

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);

      oDadosRequisicao.onComplete = function(oAjax) {

        var oRetorno = JSON.parse(oAjax.responseText)

        if ( oRetorno.iStatus == 2 ) {

          alert(oRetorno.sMessage);
          return false;
        }

        if ( oRetorno.lExisteRegra ){
          return true;
        }

        return false;
      }
      var oRetorno = new Ajax.Request( sUrlRPC, oDadosRequisicao );
      return JSON.parse( oRetorno.transport.responseText ).aRubricasConflitantes;
    }

    /**
     * Observer de envio do formulario
     */
    function js_processar() {

      if ($F('db_opcao') != 3) {

        js_pesquisarh74_concarpeculiar(false);
      }

      js_pesquisa_subfuncao(false);

      if(!js_validaAncoraNumeros($F('rh74_funcao'))){
        alert(_M( MENSAGENS + 'funcao_somente_numeros'));
        return false;
      }

      if(!js_validaAncoraNumeros($F('rh74_subfuncao'))){
        alert(_M( MENSAGENS + 'subfuncao_somente_numeros'));
        return false;
      }

      if(!js_validaAncoraNumeros($F('rh74_programa'))){
        alert(_M( MENSAGENS + 'programa_somente_numeros'));
        return false;
      }

      if(!js_validaAncoraNumeros($F('rh74_recurso'))){
        alert(_M( MENSAGENS + 'recurso_somente_numeros'));
        return false;
      }

      if(!js_validaAncoraNumeros($F('rh74_projativ'))){
        alert(_M( MENSAGENS + 'atividade_somente_numeros'));
        return false;
      }

      if(!js_validaAncoraNumeros($F('rh74_unidade'))){
        alert(_M( MENSAGENS + 'unidade_somente_numeros'));
        return false;
      }

      if ($F('db_opcao') != 3 && !js_validaEnvio()) {
        return false;
      }

      var oParametros               = new Object(),
          oDadosRequisicao          = new Object();

      if($F('db_opcao') == 3) {

        oParametros.sExecucao         = 'excluir';
        oParametros.iSequencial       = $F('rh128_sequencial');
      } else {

        oParametros.sExecucao         = 'salvar';
        oParametros.iSequencial       = $F('rh128_sequencial');
        oParametros.sDescricao        = $F('rh128_descricao');
        oParametros.iOrgao            = $F('rh74_orgao');
        oParametros.iUnidade          = $F('rh74_unidade');
        oParametros.iProjetoAtividade = $F('rh74_projativ');
        oParametros.iRecurso          = $F('rh74_recurso');
        oParametros.iPrograma         = $F('rh74_programa');
        oParametros.iFuncao           = $F('rh74_funcao');
        oParametros.iSubFuncao        = $F('rh74_subfuncao');
        oParametros.iCaracteristica   = $F('rh74_concarpeculiar');
        oParametros.iDesdobramento    = $F('rh74_codele');
        oParametros.iTipoFolha        = $F('rh74_tipofolha');
        oParametros.aRubricas         = oGridRubricas.getSelection().map(function(aRow) {
                                          return aRow[2];
                                        });
      }

      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = true;
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);

      oDadosRequisicao.onComplete = function(oAjax) {

        js_removeObj('oCarregando');

        var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

        if (oRetorno.iStatus == 2) {
          alert(oRetorno.sMessage);
          return false;
        }

        alert(oRetorno.sMessage);
        location.reload();
      }

      js_divCarregando(_M( MENSAGENS + 'salvando' ), 'oCarregando');
      new Ajax.Request(sUrlRPC, oDadosRequisicao);
      return false;
    }

    function js_validaAncoraNumeros(sValor) {

      if (!empty(sValor)){
        return js_validaSomenteNumeros(sValor)
      }
      return true;
    }

    function js_getDadosRegra() {

      var oParametros               = new Object();
      oParametros.sExecucao         = 'getDadosRegra';
      oParametros.iExcecaoRegra     = $F('rh128_sequencial');

      var oDadosRequisicao          = new Object();
      oDadosRequisicao.method       = 'POST';
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON(oParametros);

      oDadosRequisicao.onComplete   = function(oAjax) {

        var oRetorno = JSON.parse( oAjax.responseText.urlDecode() );

        if (oRetorno.iStatus == 2) {
          alert(oRetorno.sMessage);
          return false;
        }

        for (campo in oRetorno.oDadoRegra) {
          if (campo == 'sTipoFolha') {
            if (document.form1['rh74_tipofolha_select_descr']){
              $('rh74_tipofolha_select_descr').value = oRetorno.oDadoRegra[campo].urlDecode();
            }
            break;
          }
          $(campo).value = oRetorno.oDadoRegra[campo].urlDecode();
        }

        js_getRubricas();
        $("rh74_tipofolha").onchange();

      }

      new Ajax.Request(sUrlRPC, oDadosRequisicao);
    }

    function js_pesquisaRegra() {
      var sCampos = "|rh128_sequencial|rh128_descricao|rh74_orgao"
      js_OpenJanelaIframe('', 'db_iframe_rhempenhofolhaexcecaoregra', 'func_rhempenhofolhaexcecaoregra.php?funcao_js=parent.js_mostraregra'+sCampos, "Pesquisa");
    }

    function js_mostraregra(rh128_sequencial, rh128_descricao) {
      $("rh128_sequencial").value = rh128_sequencial;
      $("rh128_descricao").value  = rh128_descricao;
      db_iframe_rhempenhofolhaexcecaoregra.hide();
      js_getDadosRegra();
    }
    function js_pesquisarh74_codele(mostra){

      /**
       * Passa arvore inteira para buscar o elemento
       */
      var o58_anousu           = $F('rh74_anousu');
      var o58_orgao            = $F('rh74_orgao');
      var o58_unidade          = $F('rh74_unidade');
      var o58_subfuncao        = $F('rh74_subfuncao');
      var o58_projativ         = $F('rh74_projativ');
      var o58_funcao           = $F('rh74_funcao');
      var o58_programa         = $F('rh74_programa');
      var o58_instit           = <?php echo db_getsession("DB_instit"); ?>;
      var o58_concarpeculiar   = $F('rh74_concarpeculiar');
      var o58_codigo           = $F('rh74_recurso');

      var sQueryString  = "&o58_anousu="    + o58_anousu    + "&o58_orgao="    + o58_orgao    + "&o58_unidade="         + o58_unidade;
          sQueryString += "&o58_subfuncao=" + o58_subfuncao + "&o58_projativ=" + o58_projativ + "&o58_funcao="          + o58_funcao;
          sQueryString += "&o58_programa="  + o58_programa  + "&o58_instit="   + o58_instit   + "&o58_concarpeculiar="  + o58_concarpeculiar;
          sQueryString += "&o58_codigo="    + o58_codigo ;

      if( o58_anousu          == '' ||
          o58_orgao           == '' ||
          o58_unidade         == '' ||
          o58_projativ        == '' ||
          o58_concarpeculiar  == '' ||
          o58_codigo          == ''   ){

        alert( _M (MENSAGENS + 'preenchimento_arvore_obrigatorio' ) );
        return false;
      }

      if(mostra==true){
        js_OpenJanelaIframe('','db_iframe_orcelemento','func_orcelementosub.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_descr&lBuscaElementoExcecao=true'+sQueryString,'Pesquisa',true);
      }else{

        if(document.form1.rh74_codele.value != ''){
          js_OpenJanelaIframe('','db_iframe_orcelemento','func_orcelementosub.php?pesquisa_chave='+document.form1.rh74_codele.value+'&funcao_js=parent.js_mostraorcelemento&lBuscaElementoExcecao=true'+sQueryString,'Pesquisa',false);
        }else{
          document.form1.o56_descr.value = '';
        }
      }
    }

    function js_mostraorcelemento(chave,erro){

      document.form1.o56_descr.value = chave;
      if(erro==true){
        document.form1.rh74_codele.focus();
        document.form1.rh74_codele.value = '';
      }
    }

    function js_mostraorcelemento1(chave1,chave2){

      document.form1.rh74_codele.value = chave1;
      document.form1.o56_descr.value   = chave2;
      db_iframe_orcelemento.hide();
    }

    function js_pesquisarh74_concarpeculiar(mostra) {

      $('rh74_concarpeculiar').onkeyup = new Event(Event.CHANGE);

      if (mostra) {
        js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr&filtro=despesa', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_concarpeculiar.value != '') {
          js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?pesquisa_chave=' + document.form1.rh74_concarpeculiar.value + '&funcao_js=parent.js_mostraconcarpeculiar&filtro=despesa', 'Pesquisa', false);
        } else {
          document.form1.c58_descr.value = '';
        }
      }
    }

    function js_mostraconcarpeculiar(chave, erro) {
      document.form1.c58_descr.value = chave;
      if (erro) {
        document.form1.rh74_concarpeculiar.focus();
        document.form1.rh74_concarpeculiar.value = '';
      }
    }

    function js_mostraconcarpeculiar1(chave1, chave2) {
      document.form1.rh74_concarpeculiar.value = chave1;
      document.form1.c58_descr.value = chave2;
      db_iframe_concarpeculiar.hide();
    }


    function js_pesquisarh74_rubric(mostra) {
      if (mostra == true) {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_rhrubricas', 'func_rhrubricas.php?funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_rubric.value != '') {
          js_OpenJanelaIframe('top.corpo', 'db_iframe_rhrubricas', 'func_rhrubricas.php?pesquisa_chave=' + document.form1.rh74_rubric.value + '&funcao_js=parent.js_mostrarhrubricas', 'Pesquisa', false);
        } else {
          document.form1.rh27_descr.value = '';
        }
      }
    }

    function js_pesquisarh74_unidade(mostra) {

      if (document.form1.rh74_orgao.value == '') {
        alert('Selecione um Órgão!');
        $('rh74_unidade').value = '';
        $('o41_descr').value    = '';
        $('rh74_orgao').focus();
        return false;
      }

      if (mostra == true) {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_orcunidade', 'func_orcunidade.php?orgao=' + document.form1.rh74_orgao.value + '&funcao_js=parent.js_mostraunidade1|o41_unidade|o41_descr', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_unidade.value != '') {
          js_OpenJanelaIframe('top.corpo', 'db_iframe_orcunidade', 'func_orcunidade.php?orgao=' + document.form1.rh74_orgao.value + '&pesquisa_chave=' + document.form1.rh74_unidade.value + '&funcao_js=parent.js_mostraunidade', 'Pesquisa', false);
        } else {
          document.form1.o41_descr.value = '';
        }
      }
    }

    function js_mostraunidade(chave, erro) {
      document.form1.o41_descr.value = chave;
      if (erro == true) {
        document.form1.rh74_unidade.focus();
        document.form1.rh74_unidade.value = '';
      }
    }

    function js_mostraunidade1(chave1, chave2) {
      document.form1.rh74_unidade.value = chave1;
      document.form1.o41_descr.value = chave2;
      db_iframe_orcunidade.hide();
    }

    function js_pesquisarh74_orgao(mostra) {
      if (mostra == true) {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_orcorgao', 'func_orcorgao.php?funcao_js=parent.js_mostraorcorgao1|o40_orgao|o40_descr', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_orgao.value != '') {
          js_OpenJanelaIframe('top.corpo', 'db_iframe_orcorgao', 'func_orcorgao.php?pesquisa_chave=' + document.form1.rh74_orgao.value + '&funcao_js=parent.js_mostraorcorgao', 'Pesquisa', false);
        } else {
          document.form1.o40_descr.value = '';
        }
      }
    }

    function js_mostraorcorgao(chave, erro) {
      document.form1.o41_descr.value = '';
      document.form1.rh74_unidade.value = '';
      document.form1.o40_descr.value = chave;
      if (erro == true) {
        document.form1.rh74_orgao.focus();
        document.form1.rh74_orgao.value = '';
      }
    }

    function js_mostraorcorgao1(chave1, chave2) {
      document.form1.o41_descr.value = '';
      document.form1.rh74_unidade.value = '';
      document.form1.rh74_orgao.value = chave1;
      document.form1.o40_descr.value = chave2;
      db_iframe_orcorgao.hide();
    }

    function js_pesquisarh74_projativ(mostra) {
      if (mostra == true) {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_orcprojativ', 'func_orcprojativ.php?funcao_js=parent.js_mostraorcprojativ1|o55_projativ|o55_descr', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_projativ.value != '') {
          js_OpenJanelaIframe('top.corpo', 'db_iframe_orcprojativ', 'func_orcprojativ.php?pesquisa_chave=' + document.form1.rh74_projativ.value + '&funcao_js=parent.js_mostraorcprojativ', 'Pesquisa', false);
        } else {
          document.form1.o55_descr.value = '';
        }
      }
    }

    function js_mostraorcprojativ(chave, erro) {
      document.form1.o55_descr.value = chave;
      if (erro == true) {
        document.form1.rh74_projativ.focus();
        document.form1.rh74_projativ.value = '';
      }
    }

    function js_mostraorcprojativ1(chave1, chave2) {
      document.form1.rh74_projativ.value = chave1;
      document.form1.o55_descr.value = chave2;
      db_iframe_orcprojativ.hide();
    }

    function js_pesquisarh74_recurso(mostra) {
      if (mostra == true) {
        js_OpenJanelaIframe('top.corpo', 'db_iframe_orctiporec', 'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr', 'Pesquisa', true);
      } else {
        if (document.form1.rh74_recurso.value != '') {
          js_OpenJanelaIframe('top.corpo', 'db_iframe_orctiporec', 'func_orctiporec.php?pesquisa_chave=' + document.form1.rh74_recurso.value + '&funcao_js=parent.js_mostraorctiporec', 'Pesquisa', false);
        } else {
          document.form1.o15_descr.value = '';
        }
      }
    }

    function js_mostraorctiporec(chave, erro) {
      document.form1.o15_descr.value = chave;
      if (erro == true) {
        document.form1.rh74_recurso.focus();
        document.form1.rh74_recurso.value = '';
      }
    }

    function js_mostraorctiporec1(chave1, chave2) {
      document.form1.rh74_recurso.value = chave1;
      document.form1.o15_descr.value = chave2;
      db_iframe_orctiporec.hide();
    }

    function js_pesquisa_programa(lMostra) {

      if (lMostra) {

        js_OpenJanelaIframe('',
          'db_iframe_orcprograma',
          'func_orcprograma.php?funcao_js=parent.js_preenchePesquisaProgramaAncora|o54_programa|o54_descr',
          'Pesquisa',
          true);
        return;
      }

      var iPrograma = $('rh74_programa').value;

      js_OpenJanelaIframe('',
        'db_iframe_orcprograma',
        'func_orcprograma.php?pesquisa_chave=' + iPrograma + '&funcao_js=parent.js_preenchePesquisaProgramaInput',
        'Pesquisa',
        false);
    }

    function js_preenchePesquisaProgramaAncora(iPrograma, sDescricao, lErro) {

      $('o54_descr').value = sDescricao;
      $('rh74_programa').value = iPrograma;

      if (lErro) {
        $('o54_descr').value = '';
      }

      db_iframe_orcprograma.hide();
    }

    function js_preenchePesquisaProgramaInput(sDescricao, lErro) {

      $('o54_descr').value = sDescricao;

      if (lErro) {
        $('rh74_programa').value = '';
      }
    }

    function js_pesquisa_subfuncao(lMostra) {

      if (lMostra) {

        js_OpenJanelaIframe('',
          'db_iframe_orcsubfuncao',
          'func_orcsubfuncao.php?funcao_js=parent.js_preenchePesquisaSubfuncaoAncora|o53_subfuncao|o53_descr',
          'Pesquisa',
          true);
        return;
      }

      var iSubfuncao = $('rh74_subfuncao').value;

      js_OpenJanelaIframe('',
        'db_iframe_orcsubfuncao',
        'func_orcsubfuncao.php?pesquisa_chave=' + iSubfuncao + '&funcao_js=parent.js_preenchePesquisaSubfuncaoInput',
        'Pesquisa',
        false);
    }

    function js_preenchePesquisaSubfuncaoAncora(iSubfuncao, sDescricao, lErro) {

      $('o53_descr').value = sDescricao;
      $('rh74_subfuncao').value = iSubfuncao;

      if (lErro) {
        $('o53_descr').value = '';
      }

      db_iframe_orcsubfuncao.hide();
    }

    function js_preenchePesquisaSubfuncaoInput(sDescricao, lErro) {

      $('o53_descr').value = sDescricao;

      if (lErro) {
        $('rh74_subfuncao').value = '';
      }
    }

    function js_pesquisa_funcao(lMostra) {

      if (lMostra) {

        js_OpenJanelaIframe('',
          'db_iframe_orcfuncao',
          'func_orcfuncao.php?funcao_js=parent.js_preenchePesquisaFuncaoAncora|o52_funcao|o52_descr',
          'Pesquisa',
          true);
        return;
      }

      var iFuncao = $('rh74_funcao').value;

      js_OpenJanelaIframe('',
        'db_iframe_orcfuncao',
        'func_orcfuncao.php?pesquisa_chave=' + iFuncao + '&funcao_js=parent.js_preenchePesquisaFuncaoInput',
        'Pesquisa',
        false);
    }

    function js_preenchePesquisaFuncaoAncora(iFuncao, sDescricao, lErro) {

      $('o52_descr').value = sDescricao;
      $('rh74_funcao').value = iFuncao;

      if (lErro) {
        $('o52_descr').focus();
        $('o52_descr').value = '';
      }

      db_iframe_orcfuncao.hide();
    }

    function js_preenchePesquisaFuncaoInput(sDescricao, lErro) {

      $('o52_descr').value = sDescricao;

      if (lErro == true) {
        $('o52_descr').focus();
        $('o52_descr').value = '';
      }
    }

  </script>