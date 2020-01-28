<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_db_depart_classe.php");
require_once("classes/db_cfpatri_classe.php");
require_once("classes/db_bens_classe.php");
require_once("classes/db_clabens_classe.php");
require_once("classes/db_departdiv_classe.php");
require_once("libs/db_app.utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("estilos.css");
  db_app::load("scripts.js, prototype.js, widgets/DBToogle.widget.js");
?>
<style>
#fieldset_departamento, #fieldset_divisao, #fieldset_bens, #fieldset_contas, #fieldset_situabens, #fieldset_cedentes {
  width: 430px;
  text-align: center;
}
</style>
</head>
<body bgcolor=#CCCCCC>
    <form class="container" id="form1" name="form1">
      <fieldset>
        <legend>Bens Por Departamento</legend>
        <table>
          <tr id="tr_fieldset_departamento">
            <td colspan="1" >
              <?php
                /*
                 * Seleção de departamentos
                 */
                $oAuxDpto                              = new cl_arquivo_auxiliar();
                $oAuxDpto->cabecalho                   = "<strong>Departamento</strong>";
                $oAuxDpto->codigo                      = "coddepto"; //chave de retorno da func
                $oAuxDpto->descr                       = "descrdepto";   //chave de retorno
                $oAuxDpto->nomeobjeto                  = 'departamento';
                $oAuxDpto->funcao_js                   = 'js_mostra_departamento';
                $oAuxDpto->funcao_js_hide              = 'js_mostra_departamento1';
                $oAuxDpto->sql_exec                    = "";
                $oAuxDpto->func_arquivo                = "func_db_depart.php";  //func a executar
                $oAuxDpto->nomeiframe                  = "db_iframe_db_depart";
                $oAuxDpto->localjan                    = "";
                $oAuxDpto->executa_script_apos_incluir = "js_liberaDivisaoDpto(); js_buscaDepartamentoSelecionado();";
                $oAuxDpto->db_opcao                    = 2;
                $oAuxDpto->tipo                        = 2;
                $oAuxDpto->top                         = 0;
                $oAuxDpto->linhas                      = 5;
                $oAuxDpto->vwidth                      = 400;
                $oAuxDpto->nome_botao                  = 'db_lanca';
                $oAuxDpto->fieldset                    = false;
                $oAuxDpto->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr id="tr_fieldset_divisao" style="display: none;">
            <td colspan="2" align="center">
              <?php
                db_input('lista_departamento', 10, true, 3, 'hidden', 3);
                db_input('lista_divisaodepartamento', 10, true, 3, 'hidden', 3);
                
                
                $aTipos = array(0 => 'Sem as divisões selecionadas', 1 => 'Com as divisões selecionadas'); 
                db_select('usardivisao', $aTipos, true, 1);    
                      
                $oDptoDivisao                                =  new cl_arquivo_auxiliar();
                $oDptoDivisao->cabecalho                     = "<strong>Divisão de Departamento</strong>";
                $oDptoDivisao->codigo                        = "t30_codigo"; //chave de retorno da func
                $oDptoDivisao->descr                         = "t30_descr";   //chave de retorno
                $oDptoDivisao->nomeobjeto                    = 'divisao';
                $oDptoDivisao->funcao_js                     = 'js_mostra_divisao';
                $oDptoDivisao->funcao_js_hide                = 'js_mostra_divisao1';
                $oDptoDivisao->sql_exec                      = "";
                $oDptoDivisao->func_arquivo                  = "func_departdiv.php";  //func a executar
                $oDptoDivisao->nomeiframe                    = "db_iframe_departdiv";
                $oDptoDivisao->passar_query_string_para_func = "&departamentos='+document.form1.lista_departamento.value+'";
                $oDptoDivisao->executa_script_apos_incluir   = "js_buscaDivisaoDepartamentoSelecionado()";
                $oDptoDivisao->localjan                      = "";
                $oDptoDivisao->onclick                       = "";
                $oDptoDivisao->db_opcao                      = 2;
                $oDptoDivisao->tipo                          = 2;
                $oDptoDivisao->top                           = 0;
                $oDptoDivisao->linhas                        = 5;
                $oDptoDivisao->vwidth                        = 400;
                $oDptoDivisao->nome_botao                    = 'db_lancadivisao';
                $oDptoDivisao->fieldset                      = false; 
                $oDptoDivisao->funcao_gera_formulario(); 
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="1">
              <?php
                /*
                 * Bens
                 */
                $oAuxBem                                = new cl_arquivo_auxiliar();
                $oAuxBem->cabecalho                     = "<strong>Bens</strong>";
                $oAuxBem->codigo                        = "t52_bem"; //chave de retorno da func
                $oAuxBem->descr                         = "t52_descr";   //chave de retorno
                $oAuxBem->nomeobjeto                    = 'bens';
                $oAuxBem->funcao_js                     = 'js_mostra_bens';
                $oAuxBem->funcao_js_hide                = 'js_mostra_bens1';
                $oAuxBem->sql_exec                      = "";
                $oAuxBem->func_arquivo                  = "func_bens.php";  //func a executar
                $oAuxBem->passar_query_string_para_func = "&opcao=todos";
                $oAuxBem->nomeiframe                    = "db_iframe_bens";
                $oAuxBem->localjan                      = "";
                $oAuxBem->onclick                       = "";
                $oAuxBem->db_opcao                      = 2;
                $oAuxBem->tipo                          = 2;
                $oAuxBem->top                           = 0;
                $oAuxBem->linhas                        = 5;
                $oAuxBem->vwidth                        = 400;
                $oAuxBem->nome_botao                    = 'db_lanca_bem';
                $oAuxBem->fieldset                      = false;
                $oAuxBem->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="1">
            <?php 
              /*
               *  Estrutural dos Bens 
               */
              $oAuxEstrutural                 = new cl_arquivo_auxiliar();
              $oAuxEstrutural->cabecalho      = "<strong>Estruturais</strong>";
              $oAuxEstrutural->codigo         = "c60_codcon"; //chave de retorno da func
              $oAuxEstrutural->descr          = "c60_descr";  //chave de retorno
              $oAuxEstrutural->nomeobjeto     = 'contas';
              $oAuxEstrutural->funcao_js      = 'js_mostra_conta';
              $oAuxEstrutural->funcao_js_hide = 'js_mostra_conta1';
              $oAuxEstrutural->sql_exec       = "";
              $oAuxEstrutural->func_arquivo   = "func_clabensconta.php";  //func a executar
              $oAuxEstrutural->nomeiframe     = "db_iframe_conplano";
              $oAuxEstrutural->localjan       = "";
              $oAuxEstrutural->onclick        = "";
              $oAuxEstrutural->db_opcao       = 2;
              $oAuxEstrutural->tipo           = 2;
              $oAuxEstrutural->top            = 0;
              $oAuxEstrutural->linhas         = 5;
              $oAuxEstrutural->vwidth         = 400;
              $oAuxEstrutural->nome_botao     = 'db_lanca_conta';
              $oAuxEstrutural->funcao_gera_formulario();
            ?>
            </td>
          </tr>
          <tr>
            <td colspan="1">
              <?php
                /*
                 * Situação dos Bens
                 */
                $oAuxSituacaoBens                 = new cl_arquivo_auxiliar();
                $oAuxSituacaoBens->cabecalho      = "<strong>Situação do Bem</strong>";
                $oAuxSituacaoBens->codigo         = "t70_situac"; //chave de retorno da func
                $oAuxSituacaoBens->descr          = "t70_descr";  //chave de retorno
                $oAuxSituacaoBens->nomeobjeto     = 'situabens';
                $oAuxSituacaoBens->funcao_js      = 'js_mostra_situabens';
                $oAuxSituacaoBens->funcao_js_hide = 'js_mostra_situabens1';
                $oAuxSituacaoBens->sql_exec       = "";
                $oAuxSituacaoBens->func_arquivo   = "func_situabens.php";  //func a executar
                $oAuxSituacaoBens->nomeiframe     = "db_iframe_situabens";
                $oAuxSituacaoBens->localjan       = "";
                $oAuxSituacaoBens->onclick        = "";
                $oAuxSituacaoBens->db_opcao       = 2;
                $oAuxSituacaoBens->Labelancora    = "Situação do bem";
                $oAuxSituacaoBens->tipo           = 2;
                $oAuxSituacaoBens->top            = 0;
                $oAuxSituacaoBens->linhas         = 5;
                $oAuxSituacaoBens->vwidth         = 400;
                $oAuxSituacaoBens->nome_botao     = 'db_lanca_situabens';
                $oAuxSituacaoBens->funcao_gera_formulario();
              ?>
            </td>
          </tr>
        </table>
        <table class="form-container">
          <tr>
            <td>
              <?php 
                db_ancora("<b>Classificação:</b>", "js_abreLookupClassificacao(true);", 1);
              ?>
            </td>
            <td>
              <?php 
                db_input("t64_class", 8, false, true, "text", 1, "onchange='js_abreLookupClassificacao(false);'");
                db_input("t64_descr", 38, false, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>Data da Aquisição:</td>
            <td>
              <?php 
                db_inputdata("dtAquisicaoInicial", "", "", "", true, "text", 1);
                echo " a ";
                db_inputdata("dtAquisicaoFinal", "", "", "", true, "text", 1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap">Período da Baixa:</td>
            <td>
              <?php 
                db_inputdata("dtBaixaInicial", "", "", "", true, "text", 1);
                echo " a ";
                db_inputdata("dtBaixaFinal", "", "", "", true, "text", 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Convênio:</td>
            <td>
              <?php 
                $aConvenios = array (1 => "Ambos",
                                     2 => "Apenas vinculado a convênios",
                                     3 => "Apenas não vinculado a convênios");
              db_select("vinculoconvenio", $aConvenios, true, 2, "onchange='js_showCedentes()'");
              ?>
            </td>
          </tr>
          <tr id="tr_fieldset_cedentes" style="display: none;">
            <td colspan="1">
              <?php
                $oListaCedente                 = new cl_arquivo_auxiliar();
                $oListaCedente->cabecalho      = "<strong>Convênios</strong>";
                $oListaCedente->codigo         = "t04_sequencial"; //chave de retorno da func
                $oListaCedente->descr          = "z01_nome"; //chave de retorno
                $oListaCedente->nomeobjeto     = 'cedentes';
                $oListaCedente->funcao_js      = 'js_mostra';
                $oListaCedente->funcao_js_hide = 'js_mostra1';
                $oListaCedente->sql_exec       = "";
                $oListaCedente->nome_botao     = "lancacedentes";
                $oListaCedente->func_arquivo   = "func_benscadcedente.php";  //func a executar
                $oListaCedente->nomeiframe     = "db_iframe_cedentes";
                $oListaCedente->localjan       = "";
                $oListaCedente->onclick        = "";
                $oListaCedente->db_opcao       = 2;
                $oListaCedente->tipo           = 2;
                $oListaCedente->top            = 0;
                $oListaCedente->linhas         = 5;
                $oListaCedente->obrigarselecao = false;
                $oListaCedente->vwhidth        = '100%';
                $oListaCedente->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td>Descrição do Bem:</td>
            <td>
              <?php 
                db_input("sDescricaoBem", 34, false, true, "text", 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Características Adicionais do Bem:</td>
            <td>
              <?php 
                $aCaracteristicaAdicional = array("f" => "Não", "t" => "Sim");
                db_select("lCaracteristicaAdicional", $aCaracteristicaAdicional, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Imprimir Valor da Aquisição:</td>
            <td>
              <?php 
                $aValorAquisicao = array("f" => "Não", "t" => "Sim");
                db_select("lImprimeValorAquisicao", $aValorAquisicao, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Listar:</td>
            <td>
              <?php 
			          $aListarBens = array("1" => "Todos", "2" => "Não Baixados", "3" => "Baixados"); 
			          db_select("iListarBens", $aListarBens, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Ordem:</td>
            <td>
              <?php 
			          $aOrdemBens = array("1" => "Placa", "2" => "Código", "3" => "Descricao"); 
			          db_select("iOrdem", $aOrdemBens, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>Quebra de Página:</td>
            <td>
              <?php 
			          $aQuebraPagina = array("1" => "Não", "2" => "Departamento / Divisão");
			          db_select("iQuebraPagina", $aQuebraPagina, true, 1);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" >
    </form>
</body>
</html>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
<script>


  $("btnProcessar").observe("click", function() {

    var iClassificacao           = $F("t64_class");
    var sDepartamentos           = $F("lista_departamento");
    var lUsarDivisao             = $F("usardivisao");
    var sDivisoes                = $F("lista_divisaodepartamento");
    var sBens                    = "";
    var sEstruturais             = "";
    var sSituacaoBens            = "";
    var sCedentes                = "";
    var sVirgula                 = "";
    var dtAquisicaoInicial       = $F("dtAquisicaoInicial");
    var dtAquisicaoFinal         = $F("dtAquisicaoFinal");
    var dtBaixaInicial           = $F("dtBaixaInicial");
    var dtBaixaFinal             = $F("dtBaixaFinal");
    var iConvenio                = $F("vinculoconvenio");
    var sDescricaoBem            = $F("sDescricaoBem");
    var lCaracteristicaAdicional = $F("lCaracteristicaAdicional");
    var lImprimeValorAquisicao   = $F("lImprimeValorAquisicao");
    var iListarBens              = $F("iListarBens");
    var iOrdem                   = $F("iOrdem");
    var iQuebraPagina            = $F("iQuebraPagina");


    if (sDepartamentos == "") {
      if (!confirm(_M("patrimonial.patrimonio.pat2_benspordepartamento001.nenhum_departamento_selecionado"))) {
        return false;
      }
    }
    
    /*
     * FOR BENS
     */
    for (var i = 0; i < $('bens').length; i++) {

      sBens    += sVirgula+$('bens')[i].value;
      sVirgula  = ",";
    }
    sVirgula = "";

    /*
     * FOR ESTRUTURAL
     */
    for (var i = 0; i < $('contas').length; i++) {

      sEstruturais += sVirgula+$('contas')[i].value;
      sVirgula      = ",";
    }
    sVirgula = "";

    /*
     * FOR SITUAÇÃO BENS
     */
    for (var i = 0; i < $('situabens').length; i++) {

      sSituacaoBens += sVirgula+$('situabens')[i].value;
      sVirgula       = ",";
    }
    sVirgula = "";

    /*
     * FOR CEDENTES
     */
    for (var i = 0; i < $('cedentes').length; i++) {

      sCedentes += sVirgula+$('cedentes')[i].value;
      sVirgula   = ",";
    }
    sVirgula = "";

    var sQueryString  = "pat2_benspordepartamento002.php?";
    sQueryString     += "iClassificacao="+iClassificacao;
    sQueryString     += "&sDepartamentos="+sDepartamentos;
    sQueryString     += "&sDivisoes="+sDivisoes;
    sQueryString     += "&lUsarDivisao="+lUsarDivisao;
    sQueryString     += "&sBens="+sBens;
    sQueryString     += "&sContasEstruturais="+sEstruturais;
    sQueryString     += "&sSituacaoBens="+sSituacaoBens;
    sQueryString     += "&dtAquisicaoInicial="+dtAquisicaoInicial;
    sQueryString     += "&dtAquisicaoFinal="+dtAquisicaoFinal;
    sQueryString     += "&dtBaixaInicial="+dtBaixaInicial;
    sQueryString     += "&dtBaixaFinal="+dtBaixaFinal;
    sQueryString     += "&iConvenio="+iConvenio;
    sQueryString     += "&sCedentes="+sCedentes;
    sQueryString     += "&sDescricaoBem="+sDescricaoBem;
    sQueryString     += "&lCaracteristicaAdicional="+lCaracteristicaAdicional;
    sQueryString     += "&lImprimeValorAquisicao="+lImprimeValorAquisicao;
    sQueryString     += "&iListarBens="+iListarBens;
    sQueryString     += "&iOrdem="+iOrdem;
    sQueryString     += "&iQuebraPagina="+iQuebraPagina;
    
    var jan = window.open(sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);   
  });

  /**
   * Observa o double-click sobre o input de departamentos/divisao e atualiza o campo hidden 'lista_departamento'
   * com os departamentos que não foram excluidos no double-click
   */
  $("departamento").observe('dblclick', function () {
    js_buscaDepartamentoSelecionado();
  });
  $("divisao").observe('dblclick', function () {
    js_buscaDivisaoDepartamentoSelecionado();
  });

  /**
   * Abre função de pesquisa das classificações
   */
  function js_abreLookupClassificacao(lMostra) {

    if (lMostra == true) {

      var sUrlOpen = "func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr&analitica=true";
      js_OpenJanelaIframe('top.corpo', 'db_iframe_clabens', sUrlOpen, 'Pesquisa Classificação', true);  
    } else {

      testa = new String(document.form1.t64_class.value);     
       if(testa != '' && testa != 0){
         i = 0;       
         for(i = 0;i < document.form1.t64_class.value.length;i++){
           testa = testa.replace('.','');       
         }
         js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens&analitica=true','Pesquisa',false);
       }else{
         document.form1.t64_descr.value = '';
       }
    }
  }

  function js_mostraclabens(chave,erro){
    document.form1.t64_descr.value = chave;
    if(erro==true){
      document.form1.t64_class.value = '';
      document.form1.t64_class.focus();
    }
  }
  
  function js_mostraclabens1(chave1,chave2){
    document.form1.t64_class.value = chave1;
    document.form1.t64_descr.value = chave2;
    db_iframe_clabens.hide();
  }

  /**
   * Preenche um campo do tipo INPUT com os departamentos selecionados.
   */
  function js_buscaDepartamentoSelecionado() {

    iDptos       = document.getElementById("departamento").length;
    sValoresDpto = "";
      
    for (var i = 0; i < iDptos; i++) {
      
      if (sValoresDpto == "") {
        sValoresDpto += document.getElementById("departamento")[i].value;
      } else {
        sValoresDpto += ","+document.getElementById("departamento")[i].value;
      }
    }
    $('lista_departamento').value = sValoresDpto;
  }

  function js_buscaDivisaoDepartamentoSelecionado() {

    var oObjDivisao           = $('divisao');
    var iDivisoes             = oObjDivisao.length;
    var sDivisoesSelecionadas = "";
    var sVirgula              = "";

    for (var iRow = 0; iRow < iDivisoes; iRow++) {
      sDivisoesSelecionadas += sVirgula+oObjDivisao[iRow].value;
    }
    $("lista_divisaodepartamento").value = sDivisoesSelecionadas;
  } 

  /**
   * Libera a TR da tabela permitindo ao usuário informar as divisões do departamento
   */
  function js_liberaDivisaoDpto() {

    var iQuantidadeDepartamento = document.getElementById("departamento").length
    if (iQuantidadeDepartamento == 0) {
      $("tr_fieldset_divisao").style.display = 'none';
      $("fieldset_divisao").style.display    = 'none';
    } else {
      $("tr_fieldset_divisao").style.display = '';
      $("fieldset_divisao").style.display    = '';
    }

    for (var i = 0; i < document.getElementById("departamento").length; i++) {
      
      var oRegistro = document.getElementById("departamento");
      oRegistro[i].observe('dblclick', function () {
      
        if (document.form1.departamento.length == 1) { 

          $('tr_fieldset_divisao').style.display = "none";
          $('fieldset_divisao').style.display    = "none";
          $('lista_departamento').value          = '';
          $('lista_divdepartamento').value       = '';
        }
      });   
    }
  }

  /**
   * Mostra os cedentes de um convênio
   */
  function js_showCedentes() {
     
    if ($F('vinculoconvenio') == 2) {
      $('tr_fieldset_cedentes').style.display = '';
      $("fieldset_cedentes").style.display    = '';
    } else {
      
      $('tr_fieldset_cedentes').style.display = "none";
      $("fieldset_cedentes").style.display    = 'none';
    }
  }
  /**
   * Funções executadas ao abrir o arquivo
   */
  var oToogleDepartamento = new DBToogle("fieldset_departamento", false);
  var oToogleDivisao      = new DBToogle("fieldset_divisao", false);
  var oToogleBens         = new DBToogle("fieldset_bens", false);
  var oToogleEstrutural   = new DBToogle("fieldset_contas", false);
  var oToogleSituacaoBens = new DBToogle("fieldset_situabens", false);
  var oToogleCedentes     = new DBToogle("fieldset_cedentes", false);
  $("fieldset_divisao").style.display = 'none';
  $("fieldset_cedentes").style.display = 'none';
</script>

<script>

$("fieldset_departamento").addClassName("separator");
$("coddepto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("departamento").style.width = "100%";

$("fieldset_bens").addClassName("separator");
$("t52_bem").addClassName("field-size2");
$("t52_descr").addClassName("field-size7");
$("bens").style.width = "100%";

$("fieldset_contas").addClassName("separator");
$("c60_codcon").addClassName("field-size2");
$("c60_descr").addClassName("field-size7");
$("contas").style.width = "100%";

$("fieldset_situabens").addClassName("separator");
$("t70_situac").addClassName("field-size2");
$("t70_descr").addClassName("field-size7");
$("situabens").style.width = "100%";

$("fieldset_cedentes").addClassName("separator");
$("t04_sequencial").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("cedentes").style.width = "100%";

$("t64_class").addClassName("field-size2");
$("t64_descr").addClassName("field-size7");
$("dtAquisicaoInicial").addClassName("field-size2");
$("dtAquisicaoFinal").addClassName("field-size2");
$("dtBaixaInicial").addClassName("field-size2");
$("dtBaixaFinal").addClassName("field-size2");
$("sDescricaoBem").addClassName("field-size9");
$("t64_class").addClassName("field-size2");
$("t64_class").addClassName("field-size2");

</script>