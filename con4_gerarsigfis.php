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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$periodo = array("1"  => " 1 - Janeiro          ",
                 "2"  => " 2 - Fevereiro (1 Bim)",
                 "3"  => " 3 - Março            ",
                 "4"  => " 4 - Abril     (2 Bim)",
                 "5"  => " 5 - Maio             ",
                 "6"  => " 6 - Junho     (3 Bim)",
                 "7"  => " 7 - Julho            ",
                 "8"  => " 8 - Agosto    (4 Bim)",
                 "9"  => " 9 - Setembro         ",
                 "10" => "10 - Outubro   (5 Bim)",
                 "11" => "11 - Novembro         ",
                 "12" => "12 - Dezembro  (6 Bim)");

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <style>
    legend {
      font-weight: bold;
    }
    #sigfis {
      width: 800px;
      height: 500px;
    }
    #arquivo-gerar {
      width: 548px;
      display: block;
      float: left;
      overflow: auto;
    }
    #lista-gerados {
      margin-top: 48px;
      width: 250px;
      float: left;
      overflow: auto;
    }
    #field-gerados {
      height: 430px;
    }

    .alinha-td-label {
      width: 200px;
      text-align: left;
    }

    .alinha-td-check {
      width: 30px;
      text-align: left;
    }

  </style>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
  <div style="margin-top: 40px;"></div>
  <center>
    <fieldset id='sigfis'>
      <legend>Gerar SIGFIS</legend>
      <div id='arquivo-gerar' align="left" >
        <div style="display: block; height:48px;" >
          <table>
            <tr>
              <td><span style="font-weight: bold; width: 75px;">Arquivos do :</span></td>
              <td>
                <?
                  $periodopad = date("m",db_getsession("DB_datausu"))-1;
                  if(db_getsession("DB_anousu") != date("Y",db_getsession("DB_datausu"))){
                    $periodopad = 12;
                  } else {

                    if($periodopad == 0)
                      $periodopad = 1;
                  }

                  db_select("periodosigap",$periodo,true,2);

                ?>
              </td>
            </tr>
            <tr>
              <td><span style="font-weight: bold; width: 75px;">Código do Tribunal: </span></td>
              <td><input type="text" id="codigoTribunal" name="codigoTribunal" maxlength="4"></td>
            </tr>
          </table>
        </div>
        <fieldset id='field-contabilidade'>
          <legend>Contabilidade</legend>
          <table id='contabilidade'>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='PlanoConta' value='PlanoConta' name="PlanoConta" />
              </td>
              <td class="alinha-td-label">
                <label for="PlanoConta">Plano de Contas</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='ProgramaPpa' value='ProgramaPpa' name="ProgramaPpa" />
              </td>
	            <td class="alinha-td-label">
                <label for="ProgramaPpa">Programas PPA</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='IndicadorPpa' value='IndicadorPpa' name="IndicadorPpa" />
              </td>
              <td class="alinha-td-label">
                <label for="IndicadorPpa">Indicadores PPA </label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='Orgao' value='Orgao' name="Orgao" />
              </td>
	            <td class="alinha-td-label">
                <label for="Orgao">Órgãos</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='UnidadeOrcamentaria' value='UnidadeOrcamentaria' name="UnidadeOrcamentaria" />
              </td>
              <td class="alinha-td-label">
                <label for="UnidadeOrcamentaria">Unidade Orçamentária</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='ProgramaOrcamento' value='ProgramaOrcamento' name="ProgramaOrcamento" />
              </td>
              <td class="alinha-td-label">
                <label for="ProgramaOrcamento">Programas Orçamento</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='ProjetoAtividade' value='ProjetoAtividade' name="ProjetoAtividade" />
              </td>
              <td class="alinha-td-label">
                <label for="ProjetoAtividade">Projeto/Atividade</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='ItemReceita' value='ItemReceita' name="ItemReceita" />
              </td>
              <td class="alinha-td-label">
                <label for="ItemReceita">Itens de Receita</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='ItemDespesa' value='ItemDespesa' name="ItemDespesa" />
              </td>
              <td class="alinha-td-label">
                <label for="ItemDespesa">Itens de Despesa</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='FonteRecurso' value='FonteRecurso' name="FonteRecurso" />
              </td>
              <td class="alinha-td-label">
                <label for="FonteRecurso">Fonte Recurso</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='PrevisaoReceita' value='PrevisaoReceita' name="PrevisaoReceita" />
              </td>
              <td class="alinha-td-label">
                <label for="PrevisaoReceita">Previsão de Receita</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='DotacaoOrcamentaria' value='DotacaoOrcamentaria' name="DotacaoOrcamentaria" />
              </td>
              <td class="alinha-td-label">
                <label for="DotacaoOrcamentaria">Dotações Orçamentárias</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='AlteracaoOrcamentaria' value='AlteracaoOrcamentaria'
                       name="AlteracaoOrcamentaria" />
              </td>
              <td class="alinha-td-label">
                <label for="AlteracaoOrcamentaria">Alteração Orçamentária</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='ReceitaArrecadada' value='ReceitaArrecadada' name="ReceitaArrecadada" />
              </td>
              <td class="alinha-td-label">
                <label for="ReceitaArrecadada">Receita Arrecadada</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='AtualizaPrevisaoReceita' value='AtualizaPrevisaoReceita'
                       name="AtualizaPrevisaoReceita" />
              </td>
              <td class="alinha-td-label">
                <label for="AtualizaPrevisaoReceita">Atualizar Previsão da Receita</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='MovimentoContabil' value='MovimentoContabil' name="MovimentoContabil" />
              </td>
              <td class="alinha-td-label">
                <label for="MovimentoContabil">Movimento Contábil</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='Empenho' value='Empenho' name="Empenho" />
              </td>
              <td class="alinha-td-label">
                <label for="Empenho">Empenhos</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='Estorno' value='Estorno' name="Estorno" />
              </td>
              <td class="alinha-td-label">
                <label for="Estorno">Estorno</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='Liquidacao' value='Liquidacao' name="Liquidacao" />
              </td>
              <td class="alinha-td-label">
                <label for="Liquidacao">Liquidação</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='Pagamento' value='Pagamento' name="Pagamento" />
              </td>
              <td class="alinha-td-label">
                <label for="Pagamento">Pagamentos</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='Retencao' value='Retencao' name="Retencao" />
              </td>
              <td class="alinha-td-label">
                <label for="Retencao">Retenções</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='NotaFiscal' value='NotaFiscal' name="NotaFiscal" />
              </td>
              <td class="alinha-td-label">
                <label for="NotaFiscal">Nota Fiscal</label>
              </td>
            </tr>
            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='Diverso' value='Diverso' name="Diverso" />
              </td>
              <td class="alinha-td-label">
                <label for="Diverso">Diversos</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='Especialidade' value='Especialidade' name="Especialidade" />
              </td>
              <td class="alinha-td-label">
                <label for="Especialidade">Especialidades</label>
              </td>
            </tr>

            <tr>
              <td class="alinha-td-check">
                <input type="checkbox" id='Concilia' value='Conciliações' name="Concilia" />
              </td>
              <td class="alinha-td-label">
                <label for="Concilia">Conciliações</label>
              </td>
              <td class="alinha-td-check">
                <input type="checkbox" id='Regulariza' value='Regularizações' name="Regulariza" />
              </td>
              <td class="alinha-td-label">
                <label for="Regulariza">Regularizações</label>
              </td>
            </tr>


          </table>
        </fieldset>
        <fieldset id='field-financeiro' style="display: none;">
          <legend>Financeiro</legend>
        </fieldset>
        <fieldset id='field-rh' style="display: none;">
          <legend>Recursos Humanos</legend>
        </fieldset>
      </div>
      <div id='lista-gerados'>
        <fieldset id='field-gerados'>
          <legend>Arquivos Gerados</legend>
          <div style='overflow:auto; text-align: left;' id='retorno'></div>
        </fieldset>
      </div>
      <div style="clear: both; margin-top: 20px">

      </div>
    </fieldset>
    <div style="margin-top: 10px;">
        <input type="button" id='selecionar-todos' value='Selecionar Todos' name='Selecionar Todos'
               onclick="js_marcaTodos();"/>
        <input type="button" id='limpar-selecao' value='Limpar Seleção' name='Limpar Seleção' onclick="js_desmarcar();" />
        <input type="button" id='processar' value='Processar' name='Processar' onclick="js_processar();" />
    </div>
  </center>

  <script type="text/javascript">

    var sURL = "con4_processarsigfis.RPC.php";

    var oContabilidade = new DBToogle($('field-contabilidade'), true);
    var oFinanceiro    = new DBToogle($('field-financeiro'), false);
    var oRH            = new DBToogle($('field-rh'), false);

    function js_processar() {

      var oParam             = new Object();
      oParam.exec            = "processarSigfis";
      oParam.iPeriodo        = $F('periodosigap');
      oParam.sCodigoTribunal = encodeURIComponent($F('codigoTribunal'));

      oParam.aArquivos  = new Array();
      var aArquivos     = $$("input[type='checkbox']");
      aArquivos.each(function (oCheckbox, id) {

        with (oCheckbox) {

          if (checked) {
            oParam.aArquivos.push(oCheckbox.name);
          }
        }

      });

      if (oParam.aArquivos.length == 0) {

        alert("Selecione ao menos uma Opção.");
        return false;
      }

      js_divCarregando('Aguarde, Processando Arquivos', 'msgBox');

      var oAjax = new Ajax.Request(sURL,
                                   {
                                     method:'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete:js_retornoProcessaSigap
                                   }
                                 );
   }

   function js_retornoProcessaSigap(oAjax) {

     js_removeObj('msgBox');

     var oRetorno = eval("("+oAjax.responseText+")");
     if (oRetorno.status == 1) {

       var sRetorno = "";
       //sRetorno    += "Verifique o arquivo SIGFIS.log.<br>";
       for (var i = 0; i < oRetorno.lista.length; i++) {

         with (oRetorno.lista[i]) {

           sRetorno += "<a  href='db_download.php?arquivo="+caminho+"'>"+nome+"</a><br>";
         }
       }

       $('retorno').innerHTML = sRetorno;
     } else {

       $('retorno').innerHTML = '';
       alert(oRetorno.message.urlDecode());
       return false;
     }
   }

   function js_marcaTodos() {

  	  var aCheckboxes = $$('input[type=checkbox]');
  	  aCheckboxes.each(function(oCheckbox) {
  	    oCheckbox.checked = true;
  	  });
  	}

   function js_desmarcar() {

  	  var aCheckboxes = $$('input[type=checkbox]');
  	  aCheckboxes.each(function (oCheckbox) {
  	    oCheckbox.checked = false;
  	  });
  	}

  </script>
  </body>
</html>
<? db_menu(db_getsession("DB_id_usuario"),
           db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>