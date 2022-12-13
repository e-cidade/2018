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

      #gridservidoresAssentamento table {
       table-layout: auto !important;
      }

      #gridservidoresAssentamento table > tbody > tr > td:nth-child(5),
      #gridservidoresAssentamento table > tbody > tr > td:nth-child(6) {
        padding-right: 5px;
        padding-left: 0px;
      }
    </style>
  </head>
  <body>
   <div id="container" class="container">
     <fieldset>
       <legend>Assentamentos Lançados</legend>
         <fieldset id="fieldSetFolha">
           <legend>Folha de Pagamento</legend>
           <table class='form-container'>
             <tr>
               <td class="label">
                 <label>Folha:</label>
               </td>
               <td>
                 <?php
                   if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                     $aTiposFolha = array(0=>'Selecione');
                     if(FolhaPagamentoSalario::hasFolhaAberta()) {
                       $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SALARIO]      ='Salário';
                     } elseif(FolhaPagamentoSuplementar::hasFolhaAberta()){
                       $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR]  ='Suplementar';
                     }
                     if(FolhaPagamentoComplementar::hasFolhaAberta()){
                       $aTiposFolha[FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR] ='Complementar';
                     }
                   } else {
                     $aTiposFolha[FolhaPagamento::TIPO_FOLHA_SALARIO]        ='Salário';
                   }
                   db_select('folhapagamento', $aTiposFolha, '', 1, "", "", "");
                 ?>
               </td>
             </tr>
             <tr>
               <td class="label">
                 <label>Comportamento:</label>
               </td>
               <td>
                 <?php
                   $aComportamento = array('S'=>'Somar', 'T'=>'Substituir');
                   db_select('comportamento', $aComportamento, '', 1, "", "", "");
                 ?>
               </td>
             </tr>
           </table>
         </fieldset>
       <div id="painel_mensagens"></div>
       <div id="div_grid_servidores" style=""></div>
     </fieldset>
     <input value="Processar" id="processar" type="button" disabled onclick='processarDados()' />
     <input type='button' value='Voltar' onclick="window.location.href='pes4_assentaloteregistroponto001.php'" />
   </div>
   <?php db_menu(); ?>
  </body>
</html>
<script>

const INCLUIR  = 'incluir';
const CANCELAR = 'cancelar';

  (function(oWindow){
  
    oWindow.oGridServidores   = new DBGrid("servidoresAssentamento");
    oWindow.sNameInstance     = "window.oGridServidores";

    var sIncluir  = '<a href="#" id="incluir_todos" title="Incluir">Incluir</a>';
    var sCancelar = '<a href="#" id="cancelar_todos" title="Cancelar">Cancelar</a>';

    oWindow.oGridServidores.setHeader([  sIncluir, sCancelar, "Dias",    "Período",   "Quantidade",  "Valor"]);
    oWindow.oGridServidores.setCellWidth(["50px",    "50px",     "65px",    "140px",           "70px",     "90px"]);
    oWindow.oGridServidores.setCellAlign(["center",  "center",   "center",  "center",          "right",     "right"]);
    oWindow.oGridServidores.setHeight("450");
    oWindow.oGridServidores.show( $('div_grid_servidores') );

    carregarServidores();
    montarComportamentos(INCLUIR);
    montarComportamentos(CANCELAR);
  })(window);


function carregarGridServidores(aServidores) {


        window.oGridServidores.clearAll(true);

        var iCounterLinha = 0;
        var oServidor, sCabecalho, aDadosAssentamento, oAssentamento, sConteudoAcao, sDisabledCancelamento, sDisabledInclusao, sInputIncluir, sInputCancelar;

        for (var iServidor = 0; iServidor < aServidores.length; iServidor++) {

          oServidor  = aServidores[iServidor];
          sCabecalho = "<b><span style='width:100px;'>" + oServidor.matricula + "</span> ";
          sCabecalho+= " - "  + oServidor.nome.urlDecode() + "</b>";

          window.oGridServidores.addRow([sCabecalho]);
          window.oGridServidores.aRows[iCounterLinha].aCells[0].setUseColspan(true, 8);
          window.oGridServidores.aRows[iCounterLinha].aCells[0].sStyle  = "text-align:left; background-color: #dddddd; ";

          iCounterLinha++;

          for ( var iAssentamento=0; iAssentamento < oServidor.assentamentos.length; iAssentamento++ ) {


            oAssentamento = oServidor.assentamentos[iAssentamento];

            window.oDadosProcessamento[oAssentamento.codigo] = {
              'iSequencialAssentamento': oAssentamento.codigo,
              'iMatricula'             : oServidor.matricula,
              'nQuantidade'            : oAssentamento.quantidade,
              'nValor'                 : oAssentamento.valor,
              'sOpcao'                 : oAssentamento.lancado_ponto ? CANCELAR : INCLUIR
            };

            sInputIncluir         = '<input type="checkbox" value="'+oAssentamento.codigo+'" class="incluir_assentamento"  />';
            sInputCancelar        = '<input type="checkbox" value="'+oAssentamento.codigo+'" class="cancelar_assentamento" />';

            if ( oAssentamento.lancado_ponto ) {
              sInputIncluir  = '';
            } else {
              sInputCancelar = '';
            }

            if(!oAssentamento.folha_aberta) { //Disabilita o checkbox de cancelamento se o assentamento estiver lançado em uma folha fechada
              sInputCancelar = sInputCancelar.replace(/(\/>)/g, "disabled $1");
            }

            var sPeriodo = js_formatar(oAssentamento.data_inicio, "d");

            if(oAssentamento.data_termino) {
              sPeriodo += ' - ';
              sPeriodo += js_formatar(oAssentamento.data_termino, "d");
            }
              
            aDadosAssentamento = [
              sInputIncluir,
              sInputCancelar,
              oAssentamento.dias,
              sPeriodo,
              js_formatar(oAssentamento.quantidade, 'f'),
              js_formatar(oAssentamento.valor     , 'f')
            ];

            window.oGridServidores.addRow(aDadosAssentamento);
            window.oGridServidores.aRows[iCounterLinha].aCells[0].sStyle  = " ";
            iCounterLinha++;
          }
        }

        window.oGridServidores.renderRows();
        window.oGridServidores.setNumRows(aServidores.length);


}


  /**
   * Carrega os servidores
   */
  function carregarServidores(){

    window.oDadosProcessamento = {};

    var oGet         = js_urlToObject();
    var oDataGrid    = window.oGridServidores;

    var oParametros  = { 
      'exec'             : 'buscarServidoresAssentamento',
      'iTipoAssentamento': oGet.iTipoAssentamento
    };

    var oAjaxRequest = new AjaxRequest(
      'pes4_assentamento.RPC.php', 
      oParametros,
      function (oAjax, lErro) {

        if(lErro) {
          
          alert(oAjax.message.urlDecode());

        } else {

          carregarGridServidores(oAjax.servidores);
          montarComportamentosLinha(INCLUIR);
          montarComportamentosLinha(CANCELAR);
        }
      }
    );
    oAjaxRequest.setMessage('Buscando Servidores...');
    oAjaxRequest.execute();
  }


function verificarMarcacoes(sTipo) {

  var aElementos, 
      iElemento, 
      oElemento, 
      iMarcados, 
      iTotal,
      iDesabilitados;

  iMarcados       = 0;
  iDesabilitados  = 0;
  aElementos      = $$('.'+sTipo+'_assentamento');
  iTotal          = aElementos.length;
  
  for (var iElemento = 0; iElemento < aElementos.length; iElemento++ ) {

    oElemento = aElementos[iElemento];

    if ( oElemento.disabled ) {
      iDesabilitados++;
      continue;
    }

    if ( oElemento.checked )  {
      iMarcados++;
    }
  }
  return $(sTipo + '_todos').lTodosMarcados = (iMarcados+iDesabilitados) == iTotal;
}

function montarComportamentosLinha(sTipo) {

  $$('.' + sTipo +'_assentamento').each(function(oElemento){

    oElemento.observe('click', function() {

      verificarMarcacoes(sTipo); 
      liberarProcessamento();
    });
  });
  verificarMarcacoes(sTipo);
}

function montarComportamentos(sTipo) {

  $(sTipo + '_todos').observe('click', function() {
    marcarTodosAssentamentos(sTipo);
    liberarProcessamento();
  });
}   

function marcarTodosAssentamentos(sTipo) {


 $$('.' + sTipo +'_assentamento').each(function(oElemento){

    if ( oElemento.disabled ) {
     return $continue;
    }
   oElemento.checked = !$(sTipo + '_todos').lTodosMarcados;
 });

 $(sTipo + '_todos').lTodosMarcados = !$(sTipo + '_todos').lTodosMarcados;
 verificarMarcacoes(sTipo); 
}

function liberarProcessamento( sTipo ) {
  
 $$('.' + INCLUIR +'_assentamento, .'+  CANCELAR +'_assentamento').each(function(oElemento, iTipo){
   
   $('processar').disabled = true;

    if ( oElemento.disabled ) {
      return;
    }

   if ( oElemento.checked ) {
     
     $('processar').disabled = false;
     throw $break;
   }
 });
};

function processarDados() {


  var oGet         = js_urlToObject();
  var oParametros  = { 
    'exec'               : 'lancarAssentamentosPonto',
      'iTipoAssentamento': oGet.iTipoAssentamento,
      'iTipoFolha'       : $F('folhapagamento'),
      'sComportamento'   : $F('comportamento'),
      'aRegistros'       : coletarDados()
  };

  var oAjaxRequest = new AjaxRequest(
    'pes4_assentamento.RPC.php', 
    oParametros,
    function(oAjax, lErro) {

      if (oAjax.message ) {
        alert(oAjax.message.urlDecode());
      }

      if ( oAjax.servidores ) {
        carregarGridServidores(oAjax.servidores);
      }

    }
  );
  oAjaxRequest.setMessage("Aguarde...<BR>Processando informações.")
  oAjaxRequest.execute();
};

function  coletarDados() {
  
  var aDados = [];
  $$('.' + INCLUIR +'_assentamento, .'+  CANCELAR +'_assentamento').each(function(oElemento){

    if ( oElemento.disabled || !oElemento.checked ) {
      return $continue;
    }
    aDados.push(window.oDadosProcessamento[oElemento.value]);
  });
  return aDados;
}


</script>
