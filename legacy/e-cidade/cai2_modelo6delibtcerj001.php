<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");


?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?php
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js,estilos.css"); 
  ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
	  <div style="margin-top: 30px; width: 550px;">
	    <label style='background-color: #000099; width: 100%; display: block; margin-bottom: 10px; color:#FFFFFF'> 
	      <strong>Modelo 6 - Delib. 200/96 - TCE RJ</strong>
	    </label>
	    <form name="form1" enctype="multipart/form-data" method="post" action="">
  	    <fieldset>
  	      <legend><b> Dados da conciliacao:</b></legend>
          <table>
            <tr>
              <td nowrap="nowrap" ><strong>Contas:</strong></td>
              <td nowrap="nowrap" id="ctnCboContas"></td>
            </tr>
            <tr>
              <td nowrap="nowrap" ><strong>Ano:</strong></td>
              <td nowrap="nowrap" id="ctnCboAno"></td>
            </tr>
            <tr>
              <td nowrap="nowrap" ><strong>Mês:</strong></td>
              <td nowrap="nowrap" id="ctnCboMes"></td>
            </tr>
          </table>
        </fieldset>
        <input type="button" id ='imprime' value='Imprime' name='imprime' onclick="js_imprime();" />
      </form>      
    </div>
  </center>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script type="text/javascript">

var sRpc    = 'cai4_relconciliacao.RPC.php';
var aAnoMes = [];

var oCboContas = new DBComboBox("cboContas", "oCboContas", null, "400px");
    oCboContas.addItem("", "Selecione uma Conta");
    oCboContas.addEvent("onChange", "js_pesquisaAno();");
    oCboContas.show($('ctnCboContas'));

var oCboAno = new DBComboBox("cboAno", "oCboAno", null, "400px");
    oCboAno.addItem("", "Selecione um Ano");
    oCboAno.addEvent("onChange", "js_pesquisaMeses();");
    oCboAno.show($('ctnCboAno'));


var oCboMes = new DBComboBox("cboMes", "oCboMes", null, "400px");
    oCboMes.addItem("", "Selecione um Mes");
    oCboMes.show($('ctnCboMes'));

/**
 * Pesquisa as contas
 * se vier o codigo da conciliacao, busca somente a conta da conciliacao
 */
function js_pesquisaContas() {

  var oObject      = new Object();
      oObject.exec = "buscaContas";

  new Ajax.Request (sRpc,{
                         method:'post',
                         parameters:'json='+Object.toJSON(oObject), 
                         onComplete:js_retornoContas
                        }
                   );
}

function js_retornoContas(oJson) {

  var oRetorno = eval('('+oJson.responseText+')');

  oCboContas.clearItens();
  oCboContas.addItem("", "Selecione uma Conta");
  oRetorno.dados.each(function(oLinha, iContador) {
    
    oCboContas.addItem(oLinha.sequencial, oLinha.sequencial + " - " + oLinha.descricao.urlDecode());
  });

  if (oRetorno.dados.length == 1) {

    oCboContas.setValue(oRetorno.dados[0].sequencial);
    oCboContas.setDisable(true);
    js_pesquisaAno();
  }
}

function js_pesquisaAno() {

  var oObject    = new Object();
  oObject.exec   = 'buscaMesAno';
  oObject.conta  = oCboContas.getValue();

  new Ajax.Request (sRpc,{
                         method:'post',
                         parameters:'json='+Object.toJSON(oObject), 
                         onComplete:js_retornoAno
                        }
                   );
} 

function js_retornoAno(oJson) {

  var oRetorno = eval('('+oJson.responseText+')');
  oCboAno.clearItens();
  oCboAno.addItem("", "Selecione um Ano");
  aAnoMes = [];
  
  oRetorno.dados.each(function(oData, iContador) {

    var oAnoMes    = new Object();
    oAnoMes.ano    = oData.ano;
    oAnoMes.aMeses = oData.aMeses;
    aAnoMes.push(oAnoMes);
    
    oCboAno.addItem(oData.ano, oData.ano);
  });

}


function js_pesquisaMeses() {

  oCboMes.clearItens();
  oCboMes.addItem("", "Selecione um Mes");
  
  aAnoMes.each(function(oAno, iAno) {

    if (oAno.ano == oCboAno.getValue()) {

      oAno.aMeses.each(function (oMes, iMes) {

        var sMes = db_mes(new Number(oMes.mes));
        oCboMes.addItem(oMes.mes, sMes);
        delete sMes;
       
      });
    }
  });
}

/**
 * Imprime o relatorio
 */
function js_imprime() {

  if (oCboContas.getValue() == '') {

    alert('Selecione uma conta!');
    return false;
  }

  if (oCboAno.getValue() == '' || oCboMes.getValue() == '') {
    
    alert('Selecione um ano e um mês!');
    return false;
  }

  var iMes        = oCboMes.getValue();
  var sMes        = oCboMes.getLabel();           
  var iAno        = oCboAno.getValue();
  var iConta      = oCboContas.getValue();      
  var sUrl        = 'cai4_modelo6delibtcerj002.php?';
  var sParametro  = 'iMes='+iMes+'&sMes='+sMes+'&iConta='+iConta+'&iAno='+iAno;
  var oJanela     = window.open(sUrl+sParametro,'', 'location=0'); 
}
js_pesquisaContas();
</script>