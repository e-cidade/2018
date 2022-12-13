<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet                 = db_utils::postMemory($_GET);
$oPost                = db_utils::postMemory($_POST);
$Squantidade_parcelas = "Quantidade de Parcelas";
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js");
      db_app::load("datagrid.widget.js");
      db_app::load("widgets/dbtextField.widget.js");
      db_app::load("widgets/dbtextFieldData.widget.js");
    ?>
  </head>
  <body class="body-default">
    <div class="container" style="width:700px !important;">

        <fieldset>
          <legend><strong>Importação Geral de Diversos</strong></legend>

                  <fieldset class="separator">
          					<legend><strong>Receitas Encontradas</strong></legend>

          					<div id="container-grid"></div>

          				</fieldset>

						      <table class='form-container'>
						        <tr>
						          <td style='width:140px'><strong>Quantidade de Parcelas:</strong></td>
						          <td>
						            <?php
						             db_input('quantidade_parcelas', "", 1, "", true, 'text', "class='field-size2'");
						            ?>
						          </td>
						        </tr>
						      </table>

          				<fieldset class='separator'>
          					<legend><strong>Observações</strong></legend>

						            <?php
						             db_textarea('observacoes', "", "", "", true, 'text', 1);
						            ?>
          				</fieldset>

        </fieldset>

      <input name="reemissao" type="button"  value="Processsar"  onclick="js_importacaoGeralDiversos();">
      <input name="limpar"    type="button"  value="Limpar"  		 onclick="js_limpa();">
    </div>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script type="text/javascript">
var aRegistros = [];
var sUrlRPC    = 'dvr3_importacaoiptu.RPC.php';

    (function(){

    		oGridReceitas                 = new DBGrid('GridReceitas');
        oGridReceitas.nameInstance    = 'oGridReceitas';
        oGridReceitas.sName           = 'GridReceitas';
        oGridReceitas.setCellAlign    (new Array("left","center","center"));
        oGridReceitas.setHeader       (["Receita","Procedência","Primeiro Vencimento"]);
        oGridReceitas.aWidths			    = new Array('45%','35%','20%');
        oGridReceitas.show($('container-grid'));
        oGridReceitas.clearAll(true);

        var oDadosRequisicao    		  = new Object();
        oDadosRequisicao.method 		  = 'post';
        oDadosRequisicao.asynchronous = false;
        oDadosRequisicao.parameters   = 'json='+Object.toJSON({sExec:'getReceitasProcedencias',iCadTipo:1});
        oDadosRequisicao.onComplete   = function(oAjax){

          var oRetorno = eval("("+oAjax.responseText+")");
          if (oRetorno.status == "2") {

             alert(oRetorno.message.urlDecode());
             return;
          }

          var aIdColunasProcedencia = [];
          var aIdColunasVencimento  = [];

          for(var iReceita=0; iReceita < oRetorno.aReceitas.length; iReceita++ ){

            var oDadosReceita = oRetorno.aReceitas[iReceita];

        	  oGridReceitas.addRow([oDadosReceita.k00_receit + " - " + oDadosReceita.k02_descr.urlDecode(),'','']);

        	  var oLinhaGrid         = oGridReceitas.aRows[iReceita];
        	  var oCelulaProcedencia = oLinhaGrid.aCells[1];
        	  var oCelulaVencimento  = oLinhaGrid.aCells[2];

            aIdColunasProcedencia.push(oCelulaProcedencia.sId);
            aIdColunasVencimento.push(oCelulaVencimento.sId);

            aRegistros.push({"iCodigoReceita"     : oDadosReceita.k00_receit,
                             "iCodigoProcedencia" : 0,
                             "iVencimento"        : ""});

          }
          oGridReceitas.renderRows();

          /**
           * Montando procedencias
           */
           for ( var iElementoOpcao = 0;iElementoOpcao < aIdColunasProcedencia.length; iElementoOpcao++) {

        	   var oCelula 		 = $(aIdColunasProcedencia[iElementoOpcao]);
             var oSelect 	   = document.createElement("select");
             		 oSelect.id  = 'ProcedenciaReceita_'+iElementoOpcao;
             		 oSelect.rel = iElementoOpcao;
             		 oSelect.onchange = function(){

             			 aRegistros[this.rel].iCodigoProcedencia = this.value;
                 }

        	   for ( var iProcedencia = 0; iProcedencia < oRetorno.aProcedencias.length; iProcedencia++) {

        		   if(iProcedencia == 0){
             		 var oOpcao        = document.createElement("option");
             		 oOpcao.value 		 = 0;
                 oOpcao.text  		 = 'Selecione...';
                 oSelect.appendChild(oOpcao);
               }
               var oDadosProcedencia = oRetorno.aProcedencias[iProcedencia];
               var oOpcao            = document.createElement("option");
                   oOpcao.value 		 = oDadosProcedencia.dv09_procdiver;
                   oOpcao.text  		 = oDadosProcedencia.dv09_procdiver+' - '+oDadosProcedencia.dv09_descra.urlDecode();
                   oSelect.appendChild(oOpcao);
             }

             oCelula.appendChild(oSelect);
           }

          /**
           * Montando vencimentos
           */
           for ( var iElementoOpcao = 0;iElementoOpcao < aIdColunasVencimento.length; iElementoOpcao++) {

        	   var oCelula 							= $(aIdColunasVencimento[iElementoOpcao]);
        	   oDataPrimeiroVencimento  = new DBTextFieldData( 'ProcedenciaReceitaVencimento'+iElementoOpcao );

        	   oDataPrimeiroVencimento.rel = iElementoOpcao;

             oDataPrimeiroVencimento.show(oCelula);
             var oElemento   = oDataPrimeiroVencimento.getElement();
             oElemento.rel   = iElementoOpcao;
             oElemento.onchange = function(){
       			   aRegistros[this.rel].iVencimento = this.value;
             }
           }
        };

        var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
      }
     )();

		 function js_importacaoGeralDiversos() {

			 try{

         var lBloqueioProcedencia 					= true;
         var lBloqueioVencimentoProcedencia = true;
				 var lValidadeQuantidade						= false;

				 for (var iLinha = 0; iLinha < aRegistros.length; iLinha++) {

			     if ( aRegistros[iLinha].iCodigoProcedencia != 0 ) {
			       lBloqueioProcedencia 					= false;
			     }

			     if ( aRegistros[iLinha].iVencimento != '' && aRegistros[iLinha].iCodigoProcedencia == 0 ) {
			       lBloqueioVencimentoProcedencia = false;
				   }

				   if ( aRegistros[iLinha].iVencimento != '' ){
					   lValidadeQuantidade					  = true;
					 }
				 }

				 if( lValidadeQuantidade ) {
				   if ($F('quantidade_parcelas') == "" ) {
					   throw( _M('tributario.diversos.dvr3_importacaoiptu.preenchimento_quantidade_parcelas_obrigatorio') );
				   }
				 }

				 if ( lBloqueioProcedencia ) {
           throw( _M('tributario.diversos.dvr3_importacaoiptu.preenchimento_procedencias_obrigatorio') );
				 }

				 if ( !lBloqueioVencimentoProcedencia ) {
					 throw( _M('tributario.diversos.dvr3_importacaoiptu.preenchimento_procedencias_obrigatorio') );
				 }

			 } catch (oException) {
 				alert(oException);
 				return false;
			 }

			 if( !confirm(_M('tributario.diversos.dvr3_importacaoiptu.deseja_efetuar_importacao_geral')) ) {
				  return false;
			 }

			 var sMsg = _M('tributario.diversos.dvr3_importacaoiptu.processando_importacao_geral');
			 js_divCarregando(sMsg, 'msgbox');

			 /**
        * Envia dados para processamento
        */
			 var oParametros                  = new Object();
			 oParametros.sExec                = 'importacaoGeralDiversos';
			 oParametros.aDados					      = aRegistros;
			 oParametros.iQuantidadeParcelas  = $F('quantidade_parcelas');
			 oParametros.sObservacoes			    = $F('observacoes');

			 var oDadosRequisicao    		  		 = new Object();
		       oDadosRequisicao.method 		   = 'POST';
		       oDadosRequisicao.asynchronous = false;
		       oDadosRequisicao.parameters   = 'json='+Object.toJSON(oParametros);
		       oDadosRequisicao.onComplete   = function(oAjax){

		         js_removeObj('msgbox');

		    	   var oRetorno = eval("("+oAjax.responseText+")");
		         if (oRetorno.status == "2") {

		            alert(oRetorno.message.urlDecode());
		            return;
		         }

		         alert(_M("tributario.diversos.dvr3_importacaoiptu.sucesso_importacao_geral"));
		         window.location = 'dvr3_impgeraliptu001.php';
			     }

       var oAjax  = new Ajax.Request( sUrlRPC, oDadosRequisicao );
		 }

		 function js_limpa(iValorCampo, iIdCampo) {
			  window.location = 'dvr3_impgeraliptu001.php';
 		 }

</script>