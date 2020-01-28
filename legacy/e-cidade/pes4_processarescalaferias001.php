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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");

$sTitulo = "Processamento de escala de férias";

if ( $_GET['sAcao'] == 'cancelar' ) {
  $sTitulo = "Cancelamento de escala de férias";  
}


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
  </head>
  <body bgcolor="#CCCCCC">
    <form action="" method="POST" class="container">
      <fieldset style="width: 700px">
        <legend><?php echo $sTitulo?></legend>

        <table cellpadding="0" cellspacing="0" class="form-container">
          <tr>
            <td id="labelCompetencia" width="100"></td>
            <td id="formularioCompetencia" width="500"></td>
          </tr>
        </table>
        <br />
        <div id="grioPeriodosGozo"></div>
        
      </fieldset>
      <input name="processar" onclick="return js_processar()" value="Processar" type="submit">
    </form>

    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
    <script>

      /**
       * Instancia de GridView
       */
      var oGridPeriodosGozo;
      var sUrl = 'pes4_ferias.RPC.php';
      var oGet = js_urlToObject();

      (function() {

        /**
         * Instância o Input Competencia Folha 
         */
        var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
        oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
        oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
        oCompetenciaFolha.desabilitarFormulario();

        /**
         * Instancia de GridView
         */
        oGridPeriodosGozo              = new DBGrid('GridPeriodosGozo');
        oGridPeriodosGozo.nameInstance = 'oGridPeriodosGozo';
        oGridPeriodosGozo.setCellWidth(new Array( 0, '10%', '30%', '10%', '25%', '25%'));
        oGridPeriodosGozo.setCellAlign(new Array( 'center', 'center', 'center', 'center', 'center', 'center', 'center'));
        oGridPeriodosGozo.setCheckbox(1);
        oGridPeriodosGozo.setHeader(new Array( 'PeriodoFerias', 'Matrícula', 'Servidor', 'Dias', 'Periodo Aquisitivo', 'Periodo de Gozo'));
        oGridPeriodosGozo.aHeaders[1].lDisplayed = false;
        oGridPeriodosGozo.show($('grioPeriodosGozo'));
        oGridPeriodosGozo.clearAll(true);

        /**
         * Realiza a busca dos periodos Gozo para o mês/ano da competência atual
         */
        js_BuscaPeriodosGozo();

      })()

      function js_pesquisaMatricula(mostra){

        if(mostra==true){
          js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome','Pesquisa',true);
        }else{
           if($F('r90_regist') != ''){ 
              js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+$F('r90_regist')+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
           }
        }
      }

      function js_mostrapessoal(chave,erro){

        $('z01_nome').value = chave; 

        if(erro==true){ 
          $('r90_regist').focus(); 
          $('r90_regist').value = ''; 
        }
      }

      function js_mostrapessoal1(chave1,chave2){

        $('r90_regist').value = chave1;
        $('z01_nome').value   = chave2;
        db_iframe_rhpessoal.hide();
      }

      /**
       * Realiza a Busca dos periodos de gozo para o mês/ano da comepetência atual
       * @return void
       */

      function js_BuscaPeriodosGozo() {

        var iAnoCompetencia = $F('ano');
        var iMesCompetencia = $F('mes');

        var oParametros = {};
        
        oParametros.iAnoCompetencia = iAnoCompetencia;
        oParametros.iMesCompetencia = iMesCompetencia;
        oParametros.sExecucao       = 'BuscaPeriodosGozo';
        oParametros.sAcao           = oGet.sAcao;

        var oAjax = new Ajax.Request(sUrl, {
                                              method: 'post',
                                              parameters: 'json='+Object.toJSON(oParametros),
                                              onComplete: js_RetornoPeriodosGozo
                                           }
                                    );
      }

      /**
       * Trata o retorno da função js_BuscaPeriodosGozo, populando a grid com os dados 
       * retornados da consulta realizada pelo RPC    
       * @param  Object oPeriodosGozo retorno da consulta RPC
       * @return void
       */
      function js_RetornoPeriodosGozo(oRetornoPeriodosGozo){

        var oRetorno  = eval("("+oRetornoPeriodosGozo.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();
        
        if ( oRetorno.iStatus > 1 ) {

          alert(sMensagem);
          return false;
        }

        var aPeriodosGozo = oRetorno.oPeriodosGozo; 

        for (var iIndicePeriodoGozo = 0; iIndicePeriodoGozo < aPeriodosGozo.length; iIndicePeriodoGozo++ ) {
           
          var oPeriodoGozo = aPeriodosGozo[iIndicePeriodoGozo];

          oGridPeriodosGozo.addRow([
                                    oPeriodoGozo.rh110_sequencial,
                                    oPeriodoGozo.rh109_regist,
                                    oPeriodoGozo.z01_nome,
                                    oPeriodoGozo.rh110_dias,
                                    oPeriodoGozo.periodoaquisitivoinicial + " - " + oPeriodoGozo.periodoaquisitivofinal,
                                    oPeriodoGozo.rh110_datainicial + " - " + oPeriodoGozo.rh110_datafinal
                                  ]);
        }

        oGridPeriodosGozo.renderRows();
      }

      function js_processar() {

        var aPeriodosSelecionados = oGridPeriodosGozo.getSelection();

        if ( aPeriodosSelecionados.length == 0) { 

          alert('Por favor, selecione pelo menos 1 servidor');
          return false;
        }

        var aPeriodosGozo = [];

        aPeriodosSelecionados.each(function(oPeriodo, iIndicePeriodo){
          aPeriodosGozo.push(oPeriodo[1]);
        });

        var oParametros       = {}
        oParametros.sExecucao = 'processarEscalaFerias';

        if ( oGet.sAcao == 'cancelar' ) {
          oParametros.sExecucao = 'cancelarPeriodoGozo';  
        }

        oParametros.aPeriodosGozo = aPeriodosGozo;

        var oAjax = new Ajax.Request(sUrl, {
                                              method: 'post',
                                              parameters: 'json='+Object.toJSON(oParametros),
                                              onComplete: js_RetornoProcessamento
                                           }
                                    );

        return false;
      }

      function js_RetornoProcessamento(oAjax) {

        var oRetorno  = eval("("+oAjax.responseText+")");
        var sMensagem = oRetorno.sMensagem.urlDecode();

        if (oRetorno.iStatus > 1) {
          alert(sMensagem);
          return false
        }

        alert(sMensagem);
        location.href = location.href;
      }

    </script>
  </body>
</html>