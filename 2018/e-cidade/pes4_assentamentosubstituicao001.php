<?php
/**
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
/**
 * Require's no arquivo pes4_assentaloteregistroponto001.php
 * 
 * Variáveis disponiveis: 
 * - $oGet
 * - $oPost 
 */
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js"); ?>
    <script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script src="scripts/datagrid.widget.js"></script>
    <script src="scripts/AjaxRequest.js"></script>
    <style>
      .botoes_acao {
        width:58px;
      }
      .botoes_especificos {
        width:60px;
      }
      .content > table {
        width: 100%;
        margin: 0 auto;
        table-layout: fixed;
      }
      .content > table select {
          font-size: 14px;
          width: 100%;
      }
      .content > table td.label {
          text-align: right;
      }
      .content > table tr:nth-child(2) > td:nth-child(2) input {
        width: 100%;
      }
      .content > table tr:nth-child(2) > td:last-child input {
        max-width: 357px;
      }
      #grid_servidor_assentamentos table tr td:nth-child(2) {
        display: none;
      }

    </style>
  </head>
  <body>
   <div id="container" class="container">
     <fieldset>
       <legend>Total de Substituições</legend>
         
         </br> 
         <div id="div_grid_servidores" style="width: 800px"></div>

     </fieldset>
     <input type='button' value='Voltar' onclick="window.location.href='pes4_assentaloteregistroponto001.php'" />
   </div>
   <?php db_menu(); ?>
  </body>
</html>
<script>

  (function(oWindow){
  
    oWindow.oGridServidores   = new DBGrid("servidoresAssentamentoSubsituitcao");
    oWindow.oGridServidores.setHeader(["Matrícula","Servidor","Dias a pagar", "Dias pagos", "Ações"]);
    oWindow.oGridServidores.setCellWidth(["70px", null,"80px", "80px","150px"]);
    oWindow.oGridServidores.setCellAlign(["center","left","center","center","center"]); 
    oWindow.oGridServidores.setHeight("450");
    oWindow.oGridServidores.show( $('div_grid_servidores') );

    carregarServidores();

  })(window);

  /**
   * Carrega os servidores
   */
  function carregarServidores(){

    var oDataGrid    = window.oGridServidores;
    var oParametros  = { 'exec' : 'buscarServidoresAssentamentoSubstituicao'};

    var oAjaxRequest = new AjaxRequest(
      'pes4_assentamento.RPC.php', 
      oParametros,
      function (oAjax, lResposta) {

        window.oGridServidores.clearAll(true);

        for (var iServidor = 0; iServidor < oAjax.aResposta.length; iServidor++) {

          var oServidor = oAjax.aResposta[iServidor];

          var sDisabledButtonLancar              = "";
          var sDisabledButtonCancelarLancamento  = "disabled"

          var sValueButtonLancar                 = "Lançar";
          var sValueButtonCancelarLancamento     = "Cancelar";

          var sAcaoButtonLancar                  = "lancar";
          var sAcaoButtonCancelarLancamento      = "cancelar";

          if(oServidor.nTotalDiasPagos > 0) {

            sDisabledButtonCancelarLancamento = "";

            if (!oAjax.estrutura_suplementar) {
           //   sDisabledButtonLancar = "disabled";
            }
          }

          if(oServidor.nTotalDiasPagar == 0) {
            sDisabledButtonLancar = "disabled";
          }

          if(oServidor.nTotalDiasPagos > 0) {
            sDisabledButtonCancelarLancamento = "";
          }
           
          
          var sConteudoAcao  = ' <input type="button" '+ sDisabledButtonLancar +' value="'+ sValueButtonLancar +'"  class="botoes_especificos" onClick=\'js_executarAcao("' + sAcaoButtonLancar  +'", "'+ oServidor.sMatricula  +'", "' + oServidor.sAnoCompetencia +'", "' + oServidor.sMesCompetencia +'");\'/>';
              sConteudoAcao += ' <input type="button" '+ sDisabledButtonCancelarLancamento +' value="'+ sValueButtonCancelarLancamento +'"  class="botoes_especificos" onClick=\'js_executarAcao("' + sAcaoButtonCancelarLancamento  +'", "'+ oServidor.sMatricula  +'", "' + oServidor.sAnoCompetencia +'", "' + oServidor.sMesCompetencia +'");\'/>';
          
          window.oGridServidores.addRow( [oServidor.sMatricula, 
                                          oServidor.sNome + '<input type="hidden" id="nomeServidor'+ oServidor.sMatricula +'" value="'+ oServidor.sNome +'"/>', 
                                          oServidor.nTotalDiasPagar+'',
                                          oServidor.nTotalDiasPagos+'', 
                                          sConteudoAcao] ); 
        }

        window.oGridServidores.renderRows();
      }
    );
    oAjaxRequest.setMessage('Buscando Servidores...');
    oAjaxRequest.execute();
  }

  /**
   * Função que define qual ação deve ser tomada com base no que foi especificado no parametro
   */
  function js_executarAcao(sAcao, sMatriculaServidor, sAnoCompetencia, sMesCompetencia) {

    oView.setAnoCompetencia(parseInt(sAnoCompetencia));
    oView.setMesCompetencia(parseInt(sMesCompetencia));
    var sNomeServidor = $F('nomeServidor'+sMatriculaServidor);

    switch (sAcao) {
      case 'lancar':
        return oView.lancar(sMatriculaServidor, sNomeServidor);
        break;
      case 'cancelar':
        return oView.cancelar(sMatriculaServidor, sNomeServidor);
        break;
      default:
        throw 'Operação inválida.';
        break;
    }
  }

  require_once("scripts/classes/pessoal/assentamentoloteregistroponto/DBViewManutencaoServidoresAssentamentoSubstituicao.classe.js");
  var oView = DBViewManutencaoServidoresAssentamentoSubstituicao.getInstance();
</script>
