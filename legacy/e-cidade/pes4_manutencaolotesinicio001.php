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
// $Id: pes4_manutencaolotesinicio001.php,v 1.11 2016/08/29 16:52:45 dbrenan.silva Exp $
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet     = db_utils::postMemory($_GET);
$oPost    = db_utils::postMemory($_POST);
$oRotulo  = new rotulocampo();
$oRotulo->label('rh155_descricao'); //Para a validação do campo maiuculo
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js"); ?>
    <script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script src="scripts/datagrid.widget.js"></script>
    <style>
      .botoes_acao {
        width:55px;
      }
    </style>
  </head>
  <body>
   <div class="container">
     <fieldset>
       <legend>Manutenção dos Lotes</legend>
       <fieldset>
         <legend>Dados do Lote</legend>
  
         <table class="form-container">
  
           <tr>
             <td style="width: 100px">
               <label>Competência:</label>
             </td>

             <td>
               <input type="text" id="ano_folha"/> / <input type="text" id="mes_folha" />
             </td>
           </tr>
  
           <tr>
             <td style="width: 100px">
               <label>Descrição:</label>
             </td>
             <td>
               <?php db_input("rh155_descricao",40,0,true,'text',1, "class='field-size-max'"); ?>
             </td>
           </tr>

         </table>

       </fieldset>  

       <input type="button" value="Incluir" id="processar"> 
       <input type="button" value="Limpar"  id="limpar"> 

       <fieldset>
         <legend>Lotes Criados</legend>
         <div id="div_grid_lotes" style="width: 740px"></div>
       </fieldset>

     </fieldset>
   </div>
   <?php db_menu(); ?>
  </body>
</html>
<?php 
  $sMensagem  = "Este menu mudou para:\n";
  $sMensagem .= "Pessoal > Procedimentos > Manutenção do Ponto > Registros do Ponto em Lote > Manutenção do Lote\n";
  $sMensagem .= "A partir da próxima atualização o menu atual será retirado.";

  if(isset($oGet->menuDepreciado) && $oGet->menuDepreciado) {
    db_msgbox($sMensagem);
  }
?>
<script>

  (function(oWindow){
  
    var oCompetencia    = new DBViewFormularioFolha.CompetenciaFolha(true);
    oCompetencia.renderizaFormulario($('ano_folha'), $('mes_folha'));
    oCompetencia.desabilitarFormulario();

    oWindow.oGridLote   = new DBGrid("lotesCriados");
    oWindow.oGridLote.setHeader(["Situação","Descrição","Lançar Registros", "Ações"]);
    oWindow.oGridLote.setCellWidth(["10%", null,"120","180"]);
    oWindow.oGridLote.setCellAlign(["center","left","center","center"]); 

    oWindow.oGridLote.show( $('div_grid_lotes') );
    require_once('scripts/AjaxRequest.js');

    $('rh155_descricao').observe('keyup', function(oEvento) {

      if ( oEvento.keyCode == 13 ) {//Tecla enter

        $('processar').click();
        document.body.focus();
         oEvento.preventDefault();
         oEvento.stopPropagation();
      }
    });
    carregarLotes();

  })(window);
  aDadosLote = [];
  /**
   * Carrega os lotes
   */
  function carregarLotes(){

    var sDisabled;
    var oDataGrid    = window.oGridLote;
    var oParametros  = { 'exec': 'buscarLoteUsuario'};
    var oAjaxRequest = new AjaxRequest(
      'pes4_loteregistrosponto.RPC.php', 
      oParametros,
      function (oAjax, lResposta) {
        "use strict";


        aDadosLote = oAjax.aResposta;
        window.oGridLote.clearAll(true);
        
        for (var iLote = 0; iLote < oAjax.aResposta.length; iLote++) {

          var oLote = oAjax.aResposta[iLote];
          oLote.sDescricao = oLote.sDescricao.urlDecode();
          sDisabled = "";
          if ( oLote.sSituacao != "Aberto" ) {
             sDisabled = ' disabled ';
          }

          var sConteudoLancamento = ' <input '+sDisabled+' type="button" value="Servidor" class="botoes_acao" onClick=\'js_executarAcao("lancamento_servidor", '+iLote+');\'/>';
          sConteudoLancamento    += ' <input '+sDisabled+' type="button" value="Rubrica"  class="botoes_acao" onClick=\'js_executarAcao("lancamento_rubrica",  '+iLote+');\'/>';

          var sConteudoAcao       = ' <input '+sDisabled+' type="button" value="Alterar"  class="botoes_acao" onClick=\'js_executarAcao("editar",              '+iLote+');\'/>';
          sConteudoAcao          += ' <input '+sDisabled+' type="button" value="Excluir"  class="botoes_acao" onClick=\'js_executarAcao("excluir",             '+iLote+');\'/>';
          sConteudoAcao          += ' <input '+sDisabled+' type="button" value="Fechar"   class="botoes_acao" onClick=\'js_executarAcao("fechar",              '+iLote+');\'/>';

          window.oGridLote.addRow( [oLote.sSituacao, oLote.sDescricao, sConteudoLancamento, sConteudoAcao] ); 
        }
        
        aDadosLote = oAjax.aResposta;
        window.oGridLote.renderRows();
      }
    );
    oAjaxRequest.setMessage('Buscando Lotes...');
    oAjaxRequest.execute();
  }


    
  /**
   * Função que define qual ação deve ser tomada com base no que foi especificado no parametro
   */
  function js_executarAcao(sAcao, iIndiceMemoria) {

    if (!aDadosLote[iIndiceMemoria] ) {
      return;
    }
    var oDados = aDadosLote[iIndiceMemoria];
    
    oView.setCodigo(oDados.iCodigo);
    oView.setDescricao(oDados.sDescricao);
    oView.setAnoCompetencia(parseInt(oDados.sAnoCompetencia));
    oView.setMesCompetencia(parseInt(oDados.sMesCompetencia));

    switch (sAcao) {
    case 'lancamento_rubrica':
        return oView.lancarPorRubrica();
      break;
      case 'lancamento_servidor':
        return oView.lancarPorServidor();
      break;
      case 'editar':
        return oView.definirEstadoFormulario('alteracao');
      break;
      case 'excluir':
        return oView.definirEstadoFormulario('exclusao');
      break;
      case 'fechar':
        return oView.fechar();
      break;
      default:
        throw 'Operação inválida.';
      break;
    }
  }
  
require_once("scripts/classes/pessoal/loteregistrosponto/DBViewManutencaoLotesRegistroPonto.classe.js");
var oView = DBViewManutencaoLotesRegistroPonto.getInstance();
oView.definirEstadoFormulario('inclusao');
</script>
