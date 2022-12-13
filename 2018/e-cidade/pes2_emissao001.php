<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

  require_once(modification("libs/db_stdlib.php"));
  require_once(modification("libs/db_conecta.php"));
  require_once(modification("libs/db_sessoes.php"));
  require_once(modification("libs/db_usuariosonline.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_relrub_classe.php"));
  require_once(modification("classes/db_relrubmov_classe.php"));
  require_once(modification("classes/db_selecao_classe.php"));
  $clrelrub    = new cl_relrub;
  $clrelrubmov = new cl_relrubmov;
  $clselecao   = new cl_selecao;
  $clrotulo    = new rotulocampo;
  $clrotulo->label("rh45_codigo");
  $clrotulo->label("rh45_descr");
  db_postmemory($HTTP_POST_VARS);
  $db_opcao = 1;
  $db_botao = true;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" CONTENT="0" />
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
  </head>
  <body bgcolor="#ccc">

    <form class="container">
      <fieldset id="containerAbas" style="width: 740px">
        <legend>Emissão de Relatórios</legend>
        <table class="form-container">
          <tr>
            <td colspan="2">
              <fieldset class="sparator">
                <legend>Tipo de Folha:</legend>
                <div id="containerTipoFolha" rel="ignore-css"></div>
              </fieldset>
            </td>
          </tr>
            <td>
              <?php db_ancora(@$Lrh45_codigo,"js_pesquisarh45_codigo(true);",$db_opcao); ?>
            </td>
            <td>
              <?php
                db_input('rh45_codigo',8,$Irh45_codigo,true,'text',$db_opcao,"onchange='js_pesquisarh45_codigo(false);'");
                db_input('rh45_descr',68,$Irh45_descr,true,'text',3);
              ?>
            </td>
          </tr>
          <tr>
            <td id="labelCompetencia"></td>
            <td id="formularioCompetencia"></td>
          </tr>
          <tr>
            <td>
              <label>Tipo de Resumo</label>
            </td>
            <td>
              <?php
                $aTiposResumo = array(
                                      'g' => 'Geral',
                                      'o' => 'Órgão',
                                      'l' => 'Unidade',
                                      'lc'=> 'Lotação',
                                      'm' => 'Matrícula',
                                      't' => 'Local de Trabalho',
                                      'c' => 'Cargo',
                                      'r' => 'Regime'
                                     );
                db_select('tipo_resumo', $aTiposResumo, true, 4, "");

              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>Tipo de Arquivos: </label>
            </td>
            <td>
              <?php
                $aTipoArquivos = array(
                                       'pdf' => 'PDF', 
                                       'csv' => 'CSV' 
                                      );
                db_select('tipo_arquivo', $aTipoArquivos, true, 4, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>Modo de impressão: </label>
            </td>
            <td>
              <?php
                $aModoImpressao = array(
                                        'r' => 'Retrato', 
                                        'p' => 'Paisagem'
                                       );

                db_select('modo_impressao', $aModoImpressao, true, 4, "");
              ?>

            </td>
          </tr>
          <tr>
            <td>
              <label>Ordem: </label>
            </td>
            <td>
              <?php
                $aOrdem = array(
                                'a' => 'Alfabética', 
                                'n' => 'Numérica' 
                               );
                db_select('ordem', $aOrdem, true, 4, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>Vínculo: </label>
            </td>
            <td>
              <?php
                $aVinculo = array(
                                  'g' =>'Geral',
                                  'a' =>'Ativos',
                                  'i' =>'Inativos',
                                  'p'=>'Pensionistas',
                                  'ip' =>'Inativos / Pensionistas'
                                 );
                db_select('vinculo', $aVinculo, true, 4, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>Exibir Somente Totais:</label>
            </td>
            <td>
              <?php
                $aSomenteTotais = array(
                                        'n' => 'Não',
                                        's' => 'Sim'
                                       );

                db_select('somente_totais', $aSomenteTotais, true,4, "");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <table class="form-container" width="100%">
      <tr>
        <td align="center" rel="ignore-css">
          <input name="processar" type="button" onClick="js_processaArquivo()" value="Processar">            
        </td>
      </tr>
      </table>
    </form>

  <script type="text/javascript">

    
    /**
     * Recebe uma instância de GridView
     */
    var oGridTipoFolha;

    (function() {

      /**
      * Instância o Input Competencia Folha 
      */
      var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
      oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
      oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
      oCompetenciaFolha.setCallBack(function(){ 
                                      js_buscaComplementar()
                                    });

      /**
      * Monta a Grid com os Tipos de Folha
      */
      oGridTipoFolha              = new DBGrid('gridTipoFolha')
      oGridTipoFolha.nameInstance = "oGridTipoFolha" ;
      oGridTipoFolha.setCheckbox(0);
      oGridTipoFolha.setCellWidth(new Array( '0', '60%', '40%'));
      oGridTipoFolha.setCellAlign(new Array( 'left', 'left', 'left'));
      oGridTipoFolha.setHeader(new Array( 'Folha','Nome', 'Tipo'));
      oGridTipoFolha.aHeaders[1].lDisplayed = false;
      oGridTipoFolha.show($('containerTipoFolha'));
      oGridTipoFolha.clearAll(true);

      var aDadosTipoFolha = [
                              {folha:"gerfsal"    , descricao:"Salário"     , complementar: ''},
                              {folha:"gerfadi"    , descricao:"Adiantamento", complementar: ''},
                              {folha:"gerfcom"    , descricao:"Complementar", complementar: "<select id='selectComplementar' name='complementar'></select>"},
                              {folha:"gerffer"    , descricao:"Férias"      , complementar: ''},
                              {folha:"gerfres"    , descricao:"Rescisão"    , complementar: ''},
                              {folha:"gerfs13"    , descricao:"13o. Salário", complementar: ''}
                            ];

      aDadosTipoFolha.each (function(oTipoFolha, iIndiceTipoFolha) {
        oGridTipoFolha.addRow([oTipoFolha.folha, oTipoFolha.descricao, oTipoFolha.complementar]);
      });

      oGridTipoFolha.renderRows();

      js_buscaComplementar();
      $('selectComplementar').observe("change", function() {
        oGridTipoFolha.aRows[2].select(true);
      })
    })();

    /**
     * Realiza a pesquisa dos relatorios
     */
    function js_pesquisarh45_codigo(mostra) {
      var height = document.body.clientHeight;
      var width  = document.body.clientWidth -20; 

      if (mostra == true) {
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_relrub','func_relrub.php?funcao_js=parent.CurrentWindow.corpo.js_mostracodigo1|rh45_codigo|rh45_descr','Pesquisa',true,20, 5, width, height);
      } else {
         if ($('rh45_codigo').value != '') {  
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_relrub','func_relrub.php?pesquisa_chave='+$('rh45_codigo').value+'&funcao_js=parent.CurrentWindow.corpo.js_mostracodigo','Pesquisa',false,0,0, width, height);
         } else {
           $('rh45_descr').value = '';
         }
      }
    }

    /**
     *  Metodo de retorno do metodo js_pesquisarh45_codigo
     */
    function js_mostracodigo(chave,erro) {
      $('rh45_descr').value = chave; 
      if (erro==true) {  
        $('rh45_codigo').focus(); 
        $('rh45_codigo').value = ''; 
      }
    }

    /**
     *  Metodo de retorno do metodo js_pesquisarh45_codigo
     */
    function js_mostracodigo1(chave1,chave2) {
      $('rh45_codigo').value = chave1;
      $('rh45_descr').value = chave2;
      db_iframe_relrub.hide();
    }

    /**
     * Abrir Janela para Download do arquivo
     */
    function abrirDownload(sArquivo) {
      
      var oDownload = new DBDownload();

      oDownload.addFile(sArquivo, 'Download CSV.');
      oDownload.show();
    } 

    /**
     * Função responsavel pela validação e tratamento 
     * dos dados para geração do relatório.
     */
    function js_processaArquivo() {

      /**
       * Valida se foi selecionado pelo menos um tipo de folha
       */
      var aFolhasSelecionadas = oGridTipoFolha.getSelection()

      if (aFolhasSelecionadas.length == 0) {

        alert('Por Favor, selecione pelo menos 1 tipo de folha.');
        return false;
      }

      /**
       * Valida se voi informado o tipo de relatório
       */
      if ( $F('rh45_codigo') == '') {

        alert('Por Favor, informe um tipo de relatório');
        return false;
      }

      /**
       * Valida se o ano/competencia foram informados
       */
      if ($F('ano') == '' || $F('mes') == '') {

        alert('Por Favor, infome o mês/ano da competência')
        return false;
      }

      /**
       * Monta um array com as folhas selecionas, pegando somente o Indice 0
       */
      var aTipoFolhas     = [];
      var aNomeTipoFolhas = [];
      aFolhasSelecionadas.each (function(oTipoFolha, iIndice) {
         
         aTipoFolhas[iIndice]     = oTipoFolha[0];
         aNomeTipoFolhas[iIndice] = oTipoFolha[2].urlEncode();
      })

      /**
       * Envia os dados para a geração do relatório
       */
      var oQuery = {}
          oQuery.aTipoFolha      = aTipoFolhas;
          oQuery.aNomeTipoFolha  = aNomeTipoFolhas;
          oQuery.sComplementar   = $F('selectComplementar');
          oQuery.iAnoCompetencia = $F('ano');
          oQuery.iMesCompetencia = $F('mes');
          oQuery.iRelatorio      = $F('rh45_codigo');
          oQuery.sTipoResumo     = $F('tipo_resumo');
          oQuery.sTipoArquivo    = $F('tipo_arquivo');
          oQuery.sModoImpressao  = $F('modo_impressao');
          oQuery.sOrdem          = $F('ordem');
          oQuery.sSomenteTotais  = $F('somente_totais');
          oQuery.sVinculo        = $F('vinculo');
      
      var oJanela = window.open('pes2_emissao002.php?json='+window.btoa(Object.toJSON(oQuery)),'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);
      return false;
    }

    /**
     * Realiza a busca dos Complementar usando Ano e Mes.
     */
    function js_buscaComplementar() {
      var iAno = $F('ano');
      var iMes = $F('mes');
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

  </script>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>  
  </body>
</html>
