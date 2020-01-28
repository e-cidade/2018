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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

$sLabelLegend = "Inclusao";
if (isset($estorno) && $estorno == "true") {
  $sLabelLegend = "Estorno";  
}

$iInstituicao = db_getsession("DB_instit");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
// Includes padrão
db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, webseller.js");
db_app::load("estilos.css, grid.style.css");

db_app::load("widgets/dbcomboBox.widget.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("dbmessageBoard.widget.js");
db_app::load("classes/DBViewContabilizaDepreciacao.classe.js")
?>
</head>
<body bgcolor="#cccccc" style='margin-top: 30px'>
<center>
  <form name='form1' >
    <fieldset style="margin-top:25px; width: 250px;">
      <legend><b><?php echo $sLabelLegend;?> Contabilização da Depreciação</b></legend>
      <table>
        <tr>
          <td><b>Ano:</b></td>
          <td>
            <?php
              db_input("iAno", 10, false, true, "text", 3);
              db_input("iInstituicao", 10, false, true, "hidden", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Mês:</b></td>
          <td><select id="iMes" name="iMes">
          </select>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value='Processar' name='processar' id='processar'>
  </form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

var sUrlRPC = "con4_contabilizacaodepreciacao.RPC.php";
var oGet    = js_urlToObject(window.location.search);

function js_getMeses() {

  var oParam  			= new Object();
  oParam.exec       = "getUltimaCompetencia";
  var lEstorno = false;
  if (oGet.estorno) {
    lEstorno = true;
  }
  oParam.lEstorno   = lEstorno;  

  js_divCarregando("Carregando meses, aguarde...", "msgBox");
  
	var oAjax = new Ajax.Request(sUrlRPC,
                              {method: 'post',
                               asynchronous: false,
                               parameters: 'json='+Object.toJSON(oParam),
                               onComplete: js_preencheMesesDepreciados
                              }
                             );
}
/**
 * Preenche o combobox com os meses que o usuário pode depreciar.
 */
function js_preencheMesesDepreciados(Ajax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+Ajax.responseText+")");

  if ( oRetorno.iStatus == 2 ) {

	  alert(oRetorno.message.urlDecode());	  
	  return false;
  } 
  
  $("iMes").options.length = 0;
  var aMeses = new Array();
  aMeses[0]  = "Janeiro";
  aMeses[1]  = "Fevereiro";
  aMeses[2]  = "Março";
  aMeses[3]  = "Abril";
  aMeses[4]  = "Maio";
  aMeses[5]  = "Junho";
  aMeses[6]  = "Julho";
  aMeses[7]  = "Agosto";
  aMeses[8]  = "Setembro";
  aMeses[9]  = "Outubro";
  aMeses[10] = "Novembro";
  aMeses[11] = "Dezembro";

  $('iAno').value = oRetorno.oDados.iAno;
  
  /**
   * Percorre o array de meses bloqueando os meses que não poderam ser
   * processados pois já estão processados.
   */
  sMesSelecionado      = "";
  var oOptionSelecione = new Option("Selecione", 0);
  $("iMes").appendChild(oOptionSelecione);
  
  aMeses.each(function (sMes, iMes) {

    iMesCorrente = (iMes+1);
		var oOption  = new Option(sMes, iMesCorrente);
		Option.id    = iMesCorrente; 

		if (iMesCorrente != oRetorno.oDados.iMes) {

			oOption.disabled = true;
		} else {
			
		  sMesSelecionado = sMes;
		  iMesProcessar   = iMesCorrente;
		}
		$("iMes").appendChild(oOption);
	});
}

$('processar').observe("click", function () {

  if ($F('iMes') == 0) {
    
    alert ('Você deve selecionar um mês para processar.');
    return false;
  }

  var lEstorno = false;
  if (oGet.estorno) {
    lEstorno = true;
  }

  oViewContabilizaDepreciacao = new DBViewContabilizaDepreciacao("oViewContabilizaDepreciacao", 
                                                                  $F('iMes'),
                                                                  $F('iAno'),
                                                                  $F('iInstituicao'), 
                                                                  lEstorno);
  oViewContabilizaDepreciacao.show();
});

js_getMeses();

js_validarIntegracaoContabilidade();

function js_validarIntegracaoContabilidade() {

  var oParam = new Object();

  oParam.exec = "validarIntegracaoContabilidade";  
  js_divCarregando('Aguarde, validando integração com Contabilidade','msgBox');

  var oAjax  = new Ajax.Request (
    sUrlRPC,
    {
      method:'post',
      parameters:'json='+Object.toJSON(oParam), 
      onComplete: function(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval("("+oAjax.responseText+")");
        
        /**  
         * Integração com contabilidade habilidade
         */
        if (oRetorno.iStatus == 1) {
          return false;
        }

        $('processar').disabled = true;        
        $('iMes').disabled = true;
        alert(oRetorno.message.urlDecode());
      }
   });
     
}

</script>