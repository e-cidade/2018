<?
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_gerfcom_classe.php");
$db_opcao= 1;
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_utils::postMemory($_POST);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("estilos.css");
      db_app::load("grid.style.css");
      db_app::load("dbtextField.widget.js");
      db_app::load("dbcomboBox.widget.js");
      db_app::load("arrays.js");
      db_app::load("classes/DBViewTipoFiltrosFolha.js");
    ?>
  
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
   
    <?php
      if(!isset($tipo)){
        $tipo = "l";
      }
      if(!isset($filtro)){
        $filtro = "i";
      }
      if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
        $anofolha = db_anofolha();
      }
      if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
        $mesfolha = db_mesfolha();
      }
    ?>
  
      <form name="form1" class="container" style="width: 700px">
        <fieldset>
          <legend>Resumo da Folha: </legend>
          <fieldset>
          <legend>Filtros do Relatório: </legend>
          
          <table class="form-container">
            <tr>
              <td nowrap width="130" title="Ano / Mês de competência" >
                <b>Ano / Mês :</b>
              </td>
              <td>
                <?
                 $DBtxt23 = db_anofolha();
                 db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'class="field-size1" onchange="js_buscaComplementar()"')
                ?>
                <?
                 $DBtxt25 = db_mesfolha();
                 db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'class="field-size1" onchange="js_buscaComplementar()"')
                ?>
              </td>
            </tr>     
            <tr title="Seleção">
              <td>
                <?php
                  db_ancora("Seleção", "js_pesquisaSelecao(true)", 1);
                ?>
              </td>
              <td> 
                <?php
                  db_input('r44_selec', 8,  1, true, 'text', "",'class="field-size2" onchange="js_pesquisaSelecao(false)"');
                  db_input('r44_des',   30, "", true, 'text', 3,'class="field-size7"');
                ?> 
              </td>
            </tr>
          
            <tr title="Regime">
              <td>
                <b>Regime:</b>
              </td>
              <td>
               <div id="ContainerRegime"></div>
              </td>
            </tr>
            <tr>
              <td colspan="2" id="containnerTipoFiltrosFolha"></td>
            </tr>
            <tr title="Vinculo">
              <td width="130">
                <b>Vínculo: </b>
              </td>
              <td id="ContainerVinculo">
              </td>
            </tr>
            
            <tr title="Tabela de Previdência">
              <td>
                <b>Tabela de Previdência :</b>
              </td>
              <td>
                <div id="ContainerPrevidencia"></div>
              </td>
            </tr>
            
            <tr>
              <td colspan="2">
                <fieldset class="separator">
                  <legend><b>Tipo de Folha: </b></legend>
                    <div id="containerTipoFolha"></div>                       
                  </fieldset>
                </td>
              </tr>
            </table>
            </fieldset>
<table class="form-container" style="width: 100%">
              
              <tr title="Tipo de ordem">
                <td width="130"><b>Ordenação:</b></td>
                <td>
                 <?
                   $aTipoOrdem = array("n"=>"Numérica","a"=>"Alfabética");
                   db_select('tipo_filtro',$aTipoOrdem,true,4,"");
                 ?>
                </td>
              </tr>
              
          </table>
            </fieldset>
        <p align="center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </p>
      </form>
    <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
  <script src="scripts/classes/DBViewFormularioFolha/ComboRegime.js"      type="text/javascript"></script>
  <script src="scripts/classes/DBViewFormularioFolha/ComboPrevidencia.js" type="text/javascript"></script>
  <script src="scripts/classes/DBViewFormularioFolha/ComboVinculo.js"     type="text/javascript"></script>
  <script>
    var oTiposFiltrosFolha;
  
    /**
     * Realiza a busca de Lotação;
     */
    function js_pesquisaLotacao(oInput) {

      var sFuncaoJS;
      
      if ( oInput == 'lotai' ) {
        sFuncaoJS = 'js_mostralotacaoInicio';
      } else {
        sFuncaoJS = 'js_mostralotacaoFinal';
      }
        
      js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_rhlota.php?funcao_js=parent.'+sFuncaoJS+'|r70_codigo&instit<?=db_getsession("DB_instit")?>','Pesquisa',true);
    } 

   /**
    * Trata o retorno da função js_pesquisaLotacao();
    */
    function js_mostralotacaoInicio(sChave1) {
      $(lotai).setValue(sChave1);
      db_iframe_selecao.hide();
    }

    function js_mostralotacaoFinal(sChave1) {
      $(lotaf).setValue(sChave1);
      db_iframe_selecao.hide();
    }

  
    /**
     * Realiza a busca de seleções retornando o código e descrição da rubrica escolhida;
     */
    function js_pesquisaSelecao(lMostra) {
            
      if ( lMostra ) {
        js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_geraform_mostraselecao1|r44_selec|r44_descr&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
      } else {
        if ( $F(r44_selec) != "" ) {
          js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave=' + $F(r44_selec) + '&funcao_js=parent.js_geraform_mostraselecao&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
        } else {
          $(r44_des).setValue(""); 
        }
      }
    }
  
    /**
    * Trata o retorno da função js_pesquisaSelecao().
    */
    function js_geraform_mostraselecao(sDescricao, lErro) {
      
      if ( lErro ) { 
  
        $(r44_selec).setValue('');
        $(r44_selec).focus(); 
      }
      
      $(r44_des).setValue(sDescricao); 
    }
  
    /**
    * Trata o retorno da função js_pesquisaSelecao();
    */
    function js_geraform_mostraselecao1(sChave1, sChave2) {
        
      $(r44_selec).setValue(sChave1);
      
      if( $(r44_des) ) {
        $(r44_des).setValue(sChave2);
      }
      
      db_iframe_selecao.hide();
    }

    /**
     * Funcao responsavel por realizar a validaçãoo dos dados e tratar os dados para a geração do relatório  
     */
    function js_emite() {

      /**
       * Validar ano/mes vazio
       */
      if ($F('DBtxt23') == '' || $F('DBtxt25') == '') {
        
        alert('Por favor, informe Ano/Mês para a emissão do relatório.');
        return false;
      }


			if ($F('DBtxt23') <= 0) {
				alert('Ano da folha informado é invalido.');
				return false;
			}

			if ($F('DBtxt25') <= 0 || $F('DBtxt25') > 12) {
				alert('Mês da folha informado é invalido.');
				return false;
			}
				
      /**
       * Valida se tem pelo menos uma folha selecionada
       */
       var aFolhasSelecionadas = oGridTipoFolha.getSelection();
       if (aFolhasSelecionadas.length == 0) {

         alert('Por favor, selecione pelo menos 1 tipo de folha.');
         return false;
       }

       /**
        * Se o tipo de relatorio dif  rente de geral e tipo de filtro igual a selecionado,
        * obrigar o lançamento de 1 registro no respectivo lançador.
        */
        var oTipoRelatorio = $F('oCboTipoRelatorio');
        var oTipoFiltro    = $F('oCboTipoFiltro');

        if (oTipoRelatorio != 0 && oTipoFiltro == 2) {
          
          var oLancadorSelecionado = oTiposFiltrosFolha.getLancadorAtivo().getRegistros();
          if (oLancadorSelecionado.length == 0) {

            alert('Por Favor, realize pelo menos o lançamento de 1 registro.');
            return false 
          }
        }

       /**
        * Se o tipo de relatorio diferente de geral e tipo de filtro igual a Intervalo,
        * obrigar o preenchimento de intervalo.
        */
       if (oTipoRelatorio != 0 && oTipoFiltro == 1) {

         if ($F('InputIntervaloInicial') == '' || $F('InputIntervaloFinal') == '') {

           alert('Por favor, informe o intervalo para geração do relatório.');
           return false;
         }

       }

      /**
       * Envia os dados para a geração do relatório.
       */       
       var oQuery = {};
       oQuery.iAno           = $F('DBtxt23');
       oQuery.iMes           = $F('DBtxt25');
       oQuery.iSelecao       = $F('r44_selec');
       oQuery.iRegime        = $F('Regime');
       oQuery.iTipoRelatorio = $F('oCboTipoRelatorio');
       oQuery.iTipoFiltro    = $F('oCboTipoFiltro');

       /**
        * Verifica se o tipo escolhido foi intervalo 
        */
       if (oTipoFiltro == 1) {
         
         oQuery.iIntervaloInicial = $F('InputIntervaloInicial');
         oQuery.iIntervaloFinal   = $F('InputIntervaloFinal');
       }

       /**
        * Verifica se o tipo escolhido foi seleção
        */
       if (oTipoFiltro == 2 ) {
         
         var aSelecionados = [];
         var oTipoFiltros = oTiposFiltrosFolha.getLancadorAtivo().getRegistros();

         /**
          * Percorre os itens selecionados no lancador
          */
         oTipoFiltros.each (function(oFiltro, iIndice) {
           aSelecionados[iIndice] = oFiltro.sCodigo;
         });
         
         oQuery.iRegistros = aSelecionados;
       }

       oQuery.sVinculo     = $F('Vinculo');
       oQuery.iPrevidencia = $F('Previdencia');

       var aPrevidencias = $('Previdencia').options;

       for(var iIndice = 0; iIndice < aPrevidencias.length; iIndice++) {

         var previdencia = aPrevidencias[iIndice];

         if ( previdencia.selected ) {
           oQuery.sPrevidencia = previdencia.text;
         }
       }

       oQuery.sOrdem = $F('tipo_filtro');

       /**
        * Percorre os tipos de folha selecionado.
        */
       var aTipoFolhas = [];
       aFolhasSelecionadas.each (function(oTipoFolha, iIndice) {
         
         aTipoFolhas[iIndice] = oTipoFolha[0];
         
         //Se complementar tiver sido selecionada.
         if (oTipoFolha[0] == 'gerfcom') {
           oQuery.iComplementar = $F('selectComplementar');
         }         
       })

       oQuery.aTiposFolhas = aTipoFolhas;
       var oJanela = window.open('pes2_resumofolha002.php?json='+Object.toJSON(oQuery),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       oJanela.moveTo(0,0);

      return false;
    }    

    /**
     * Realiza a busca dos Complementar usando Ano e Mes.
     */
    function js_buscaComplementar() {
      
      var iAno = $F('DBtxt23');
      var iMes = $F('DBtxt25');
      var sUrl = 'pes1_rhempenhofolhaRPC.php';
      
      var sQuery  = 'sMethod=consultaPontoComplementar';
          sQuery += '&iAnoFolha='+iAno;
          sQuery += '&iMesFolha='+iMes;
          sQuery += '&sSigla="Complementar"';
          
      
      var oAjax   = new Ajax.Request( sUrl, {
                                              method: 'post', 
                                              parameters: sQuery, 
                                              onComplete: js_retornoComplementar
                                            }
                                    );
    }

    /**
     * Trata o Retorno do complementar
     */
    function js_retornoComplementar(oComplementar) {

      $('selectComplementar').options.length = 0;
      var aRetorno = eval("("+oComplementar.responseText+")");
      
      if (aRetorno.aSemestre.length > 0) {

        var oOptionDefault = new Option('Todos', '');
        $('selectComplementar').add(oOptionDefault);

        /**
         * Percorre o Array de retorno montando os Options
         */
        for (var iIndiceComplementar = 0; iIndiceComplementar < aRetorno.aSemestre.length; iIndiceComplementar++) {
  
          var iComplementar       = aRetorno.aSemestre[iIndiceComplementar].semestre;
          var oOptionComplementar = new Option(iComplementar, iComplementar);
          $('selectComplementar').add(oOptionComplementar);
        }
      }
    }

    
    (function() {

      /**
       * Monta os componentes para o formulario
       */
      var oComboRegime       = new DBViewFormularioFolha.ComboRegime();
      var oComboPrevidencia  = new DBViewFormularioFolha.ComboPrevidencia();
      var oComboVinculo      = new DBViewFormularioFolha.ComboVinculo();
      oTiposFiltrosFolha     = new DBViewFormularioFolha.DBViewTipoFiltrosFolha(<?=db_getsession("DB_instit")?>);
      oTiposFiltrosFolha.sInstancia     = 'oTiposFiltrosFolha';
      
      /**
       * Renderiza os componentes em seus respectivos contaners
       */
      oComboRegime.show($('ContainerRegime'));
      oComboPrevidencia.show($('ContainerPrevidencia'));
      oComboVinculo.show($('ContainerVinculo'));
      oTiposFiltrosFolha.show($('containnerTipoFiltrosFolha'));


      /**
       * Monta a Grid com os Tipos de Folha
       */
      oGridTipoFolha              = new DBGrid('gridTipoFolha');
      oGridTipoFolha.nameInstance = "oGridTipoFolha";
      oGridTipoFolha.setCheckbox(0);
      oGridTipoFolha.setCellWidth(new Array( '0', '70%', '30%'));
      oGridTipoFolha.setCellAlign(new Array( 'left', 'left', 'left'));
      oGridTipoFolha.setHeader(new Array( 'Folha','Nome', 'Complementar'));
      oGridTipoFolha.aHeaders[1].lDisplayed = false;
      oGridTipoFolha.show($('containerTipoFolha'));
      oGridTipoFolha.clearAll(true);

      aDadosTipoFolha = [
                          {folha:"gerfsal"    , descricao:"Salário",              complementar: ''},
                          {folha:"gerfcom"    , descricao:"Complementar",         complementar: "<select id='selectComplementar' name='complementar'></select>"},
                          {folha:"gerfres"    , descricao:"Rescisão",             complementar: ''},
                          {folha:"gerfs13"    , descricao:"13o. Salário",         complementar: ''},
                          {folha:"gerfadi"    , descricao:"Adiantamento",         complementar: ''},
                          {folha:"gerfprovfer", descricao:"Provisão de Férias",   complementar: ''},
                          {folha:"gerfprovs13", descricao:"Provisão 13o. Salário",complementar: ''}
                        ];

      aDadosTipoFolha.each (function(oTipoFolha, iIndiceTipoFolha) {
        oGridTipoFolha.addRow([oTipoFolha.folha, oTipoFolha.descricao, oTipoFolha.complementar]);
      });
      oGridTipoFolha.renderRows();
      
      js_buscaComplementar();
      $('selectComplementar').observe("change", function() {
        oGridTipoFolha.aRows[1].select(true);
      })
    })();
  </script>
  
</html>