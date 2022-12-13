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
// $Id: pes4_processamento_loteregistroponto.php,v 1.12 2016/08/29 16:52:39 dbrenan.silva Exp $
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet     = db_utils::postMemory($_GET);
$oPost    = db_utils::postMemory($_POST);

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

      #registrosLotebody{
        table-layout: auto !important;
      }

    </style>
  </head>
  <body>
   <div class="container">
     <fieldset>
       <legend>Processamento de Lotes</legend>
         
         <table class="form-container">
  
           <tr>
             <td>
               <label>Competência:</label>
               <input type="text" id="ano_folha"/> / <input type="text" id="mes_folha" />
             </td>
           </tr>

         </table>

         </br> 
         <div id="div_grid_lotes" style="width: 900px"></div>

     </fieldset>
   </div>
   <?php 
      db_menu();
      
      $sMensagem  = "Este menu mudou para:\n";
      $sMensagem .= "Pessoal > Procedimentos > Manutenção do Ponto > Registros do Ponto em Lote > Processar Lote\n";
      $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

      if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
        db_msgbox($sMensagem);
      }
  ?>
  </body>
</html>
<script>

  (function(oWindow){
  
    var oCompetencia    = new DBViewFormularioFolha.CompetenciaFolha(true);
    oCompetencia.renderizaFormulario($('ano_folha'), $('mes_folha'));
    oCompetencia.desabilitarFormulario();

    oWindow.oGridLote   = new DBGrid("lotesCriados");
    oWindow.oGridLote.setHeader(["Situação","Descrição","Usuário","Lançar Registros", "Ações"]);
    oWindow.oGridLote.setCellWidth(["10%", null,"130","120","250"]);
    oWindow.oGridLote.setCellAlign(["center","left","left","center","center"]); 

    oWindow.oGridLote.show( $('div_grid_lotes') );

    carregarLotes();

  })(window);

  /**
   * Carrega os lotes
   */
  function carregarLotes(){
    var oDataGrid    = window.oGridLote;
    var oParametros  = { 'exec': 'buscarLoteCompetencia',
                         'iAno': $F('ano_folha'),
                         'iMes': $F('mes_folha') };
    var oAjaxRequest = new AjaxRequest(
      'pes4_loteregistrosponto.RPC.php', 
      oParametros,
      function (oAjax, lResposta) {

        window.oGridLote.clearAll(true);

        for (var iLote = 0; iLote < oAjax.aResposta.length; iLote++) {

          var oLote = oAjax.aResposta[iLote];

          var sDisabledButtonExcluir   = "";
          var sDisabledButtonLServidor = "";
          var sDisabledButtonLRubrica  = "";
          var sDisabledButtonReabrir   = "";
          var sValueButtonReabrir      = "Reabrir";
          var sAcaoButtonReabrir       = "reabrir";
          var sDisabledButtonFechar    = "";
          var sValueButtonFechar       = "Fechar";
          var sAcaoButtonFechar        = "fechar";

          if ( oLote.sSituacao == "Aberto" ) {
            sDisabledButtonReabrir = "disabled";
          }

          if ( oLote.sSituacao == "Fechado" ) {

            sValueButtonFechar       = "Confirmar";
            sAcaoButtonFechar        = "confirmar";
          }

          if ( oLote.sSituacao == "Confirmado" ) {

            sDisabledButtonExcluir   = "disabled";
            sDisabledButtonLServidor = "disabled";
            sDisabledButtonLRubrica  = "disabled";
            sValueButtonReabrir      = "Desconfirma";
            sAcaoButtonReabrir       = "desconfirmar";
            sDisabledButtonFechar    = "disabled";
            sValueButtonFechar       = "Confirmar";
            sAcaoButtonFechar        = "confirmar";
          }

          var sConteudoLancamento    = ' <input type="button" '+ sDisabledButtonLServidor +' value="Servidor" class="botoes_acao" onClick=\'js_executarAcao("lancarServidor", '+ oLote.iCodigo      +', "'+  oLote.sDescricao.urlEncode() +'", "' + oLote.sAnoCompetencia +'", "' + oLote.sMesCompetencia +'");\'/>'; 
          sConteudoLancamento       += ' <input type="button" '+ sDisabledButtonLRubrica  +' value="Rubrica"  class="botoes_acao" onClick=\'js_executarAcao("lancarRubrica", ' + oLote.iCodigo      +', "'+  oLote.sDescricao.urlEncode() +'", "' + oLote.sAnoCompetencia +'", "' + oLote.sMesCompetencia +'");\'/>';

          var sConteudoAcao          = ' <input type="button" '+ sDisabledButtonFechar    +' value="'+ sValueButtonFechar +'"   class="botoes_especificos" onClick=\'js_executarAcao("'  + sAcaoButtonFechar  +'", '+ oLote.iCodigo     +', "'+  oLote.sDescricao.urlEncode() +'", "' + oLote.sAnoCompetencia +'", "' + oLote.sMesCompetencia +'");\'/>';
          sConteudoAcao             += ' <input type="button" '+ sDisabledButtonExcluir   +' value="Excluir"                    class="botoes_acao" onClick=\'js_executarAcao("excluir",                           '+ oLote.iCodigo     +', "'+  oLote.sDescricao.urlEncode() +'", "' + oLote.sAnoCompetencia +'", "' + oLote.sMesCompetencia +'");\'/>';
          sConteudoAcao             += ' <input type="button" '+ sDisabledButtonReabrir   +' value="'+ sValueButtonReabrir +'"  class="botoes_especificos" onClick=\'js_executarAcao("'  + sAcaoButtonReabrir +'", '+ oLote.iCodigo     +', "'+  oLote.sDescricao.urlEncode() +'", "' + oLote.sAnoCompetencia +'", "' + oLote.sMesCompetencia +'");\'/>';
          sConteudoAcao             += ' <input type="button" value="Consultar" class="botoes_especificos" onClick=\'js_consultarLote(' + oLote.iCodigo + ', " ' + oLote.sDescricao.urlEncode() + '");\'/>';
          
          window.oGridLote.addRow( [oLote.sSituacao, oLote.sDescricao, oLote.sUsuario, sConteudoLancamento, sConteudoAcao] ); 
        }

        window.oGridLote.renderRows();
      }
    );
    oAjaxRequest.setMessage('Buscando Lotes...');
    oAjaxRequest.execute();
  }

  /**
   * Função que define qual ação deve ser tomada com base no que foi especificado no parametro
   */
  function js_executarAcao(sAcao, iCodigoLote, sDescricao, sAnoCompetencia, sMesCompetencia) {

    oView.setCodigo(iCodigoLote);
    oView.setDescricao(sDescricao.urlDecode());
    oView.setAnoCompetencia(parseInt(sAnoCompetencia));
    oView.setMesCompetencia(parseInt(sMesCompetencia));
    sDescricao = tagString(sDescricao.urlDecode());

    var oParametros = {};

    switch (sAcao) {
      case 'lancarRubrica':
        return oView.lancarPorRubrica();
        break;
      case 'lancarServidor':
        return oView.lancarPorServidor();
        break;
      case 'fechar':
        oParametros = {
          "exec"         : "fecharLote",
          "iCodigoLote"  :  iCodigoLote,
          "sDescricao"   :  sDescricao
        };
        break;
      case 'excluir':
        if ( confirm('Deseja excluir o lote '+sDescricao+'?') ) {
          oParametros = {
            "exec"         : "excluirLote",
            "iCodigoLote"  :  iCodigoLote,
            "sDescricao"   :  sDescricao
          };
        };
        break;
      case 'reabrir':
        oParametros = {
          "exec"         : "cancelarFechamento",
          "iCodigoLote"  :  iCodigoLote,
          "sDescricao"   :  sDescricao
        };
        break;
      case 'confirmar':
        oParametros = {
          "exec"         : "confirmarLote",
          "iCodigoLote"  :  iCodigoLote,
          "sDescricao"   :  sDescricao
        };
        break;
      case 'desconfirmar':
        oParametros = {
          "exec"         : "cancelarConfirmacaoLote",
          "iCodigoLote"  :  iCodigoLote,
          "sDescricao"   :  sDescricao
        };
        break;
      default:
        throw 'Operação inválida.';
        break;
    }

    if ( sAcao != 'lancarRubrica' && sAcao != 'lancarServidor' ) {

      var oAjaxRequest = new AjaxRequest(
        'pes4_loteregistrosponto.RPC.php', 
        oParametros,
        function (oAjax, lResposta) {

          alert(oAjax.message.urlDecode());

          if(lResposta){
            return false;
          }

          carregarLotes();
        });
      oAjaxRequest.setMessage("Persistindo o lote...");
      oAjaxRequest.execute();
    }
  }
  
  /**
   * Abre windowAux com informações do lote
   */
  function js_consultarLote(iCodigoLote, sDescricao) {

    require_once('scripts/widgets/windowAux.widget.js');
    require_once('scripts/widgets/dbmessageBoard.widget.js');
    
    var oElemento       = document.createElement("div");
    oElemento.className = 'container-window-aux';
  
    var sConteudo  = "<fieldset>                                                               \n";
    sConteudo     += "  <legend>Informações dos Registros do Ponto</legend>                    \n";
    sConteudo     += "  <div id='divLote' style='width: 570px; height: 430px;'></div>          \n";
    sConteudo     += "</fieldset>                                                              \n";

    oElemento.innerHTML = sConteudo;
    
    if($('consultaLote')){
      $('consultaLote').remove();
    }

    var oWindowAux    =  new windowAux( 'consultaLote', 'Consultar Lote', 603, 554 );
    oWindowAux.setContent( oElemento );
    oWindowAux.setShutDownFunction(function() {oWindowAux.destroy()});
    oWindowAux.show(null, null, true);

    var oMessageBoard = new DBMessageBoard( null,
                                            'Informações do Lote ',
                                            sDescricao.urlDecode(),
                                            oElemento );
    oMessageBoard.show();
    
    (function(oWindowRegistros){
  
      oWindowRegistros.oGridRegistrosLote   = new DBGrid("registrosLote");
      oWindowRegistros.oGridRegistrosLote.setHeader(["Código","Rubrica", "Ano/Mês", "Quantidade","Valor"]);
      oWindowRegistros.oGridRegistrosLote.setCellWidth(["8%", "", "10%", "15%","15%"]);
      oWindowRegistros.oGridRegistrosLote.setCellAlign(["center","left", "center", "center","center"]); 
      oWindowRegistros.oGridRegistrosLote.setHeight(385);
      
      oWindowRegistros.oGridRegistrosLote.show( $('divLote') );

      carregarRegistrosLote(iCodigoLote);
    })(window);

  }


  /**
   * Carrega os Registros do Lote
   */
  function carregarRegistrosLote(iCodigoLote){

    var oDataGrid    = window.oGridRegistrosLote;
    var oParametros  = { 'exec'       : 'buscarRegistrosLote',
                         'iCodigoLote': iCodigoLote };

    var oAjaxRequest = new AjaxRequest(
      'pes4_loteregistrosponto.RPC.php', 
      oParametros,
      function (oAjax, lResposta) {

        window.oGridRegistrosLote.clearAll(true);

        var iCounterLinha = 0;
        
        for (iMatricula in oAjax.aRegistros) {

          oRubricas = oAjax.aRegistros[iMatricula];
          window.oGridRegistrosLote.addRow(["<b>Servidor: "+oRubricas[0].sMatricula+" - "+oRubricas[0].sNome+ "</b>"]);
          window.oGridRegistrosLote.aRows[iCounterLinha].aCells[0].setUseColspan(true, 5);
          window.oGridRegistrosLote.aRows[iCounterLinha].setClassName('nome-lote');
         
          for (var iRubrica = 0; iRubrica < oRubricas.length; iRubrica++) {

            var oRubrica = oRubricas[iRubrica];
            window.oGridRegistrosLote.addRow( [oRubrica.sRubrica, oRubrica.sNomeRubrica, oRubrica.sCompetencia, oRubrica.iQuantidade, oRubrica.nValor] );
            iCounterLinha++;
          }
          iCounterLinha++;
        }

        window.oGridRegistrosLote.renderRows();
      }
    );
    oAjaxRequest.setMessage('Buscando Registros...');
    oAjaxRequest.execute();
  };  

  require_once("scripts/classes/pessoal/loteregistrosponto/DBViewManutencaoLotesRegistroPonto.classe.js");
  var oView = DBViewManutencaoLotesRegistroPonto.getInstance();
</script>