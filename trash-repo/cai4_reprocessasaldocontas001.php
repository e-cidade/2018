<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * 
 * @author I
 * @revision $Author: dbluizmarcelo $
 * @version $Revision: 1.2 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_saltes_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
   <form name="form1" method="post">
    <table>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Reprocessamento dos saldos </b>
            </legend>
            <table>
              <tr>
                <td>
		               <?
		                 $aux = new cl_arquivo_auxiliar;
		                 $aux->cabecalho = "<strong>Contas Selecionadas</strong>";
		                 $aux->codigo = "k13_conta";
		                 $aux->descr  = "k13_descr";
		                 $aux->nomeobjeto = 'listasaltes';
		                 $aux->funcao_js = 'js_mostra';
		                 $aux->funcao_js_hide = 'js_mostra1';
		                 $aux->sql_exec  = "";
		                 $aux->func_arquivo = "func_saltes.php";
		                 $aux->nomeiframe = "db_iframe_saltes";
		                 $aux->localjan = "";
		                 $aux->onclick = "";
		                 $aux->db_opcao = 2;
		                 $aux->tipo = 2;
		                 $aux->top = 0;
		                 $aux->linhas = 10;
		                 $aux->vwhidth = 200;
		                 $aux->funcao_gera_formulario();
		               ?> 
                </td>
              </tr>
              <tr>
                <td>
                   <b>Data Reprocessamento:</b>&nbsp;
	                  <?
	                    $dtDia     = db_getsession("DB_datausu");
	                    $dDataBase = date("d/m/Y",mktime(0, 0, 0, Date("m", $dtDia), date("d", $dtDia)-1, date("Y", $dtDia)));
	                    $dtPartes  = explode("/",$dDataBase); 
	                    db_inputdata("datareprocessa", $dtPartes[0], $dtPartes[1], $dtPartes[2], true, "text", 1);
	                    
	                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan='2' style='text-align:center'>
           <input type='button' value='Reprocessar' onclick="return js_reprocessaSaldoContas()">
        </td>
      </tr>
    </table>
   </form>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>  

<script>
function js_reprocessaSaldoContas() {

   var dtDataBase = $F('datareprocessa');
   if (dtDataBase == "") {
     
     alert('Informe a Data do Reprocessamento!');
     return false;
   }
   
   var sMsg  = "Serão reprocessados os saldos das contas da tesouraria até "+dtDataBase+".\n";
   sMsg     += "Confirmar Procedemento?";
   
   if (!confirm(sMsg)) {
     return false;
   }
   
   var iItens    = $('listasaltes').options.length;
   var iItensSel = "";
   var sVrg      = "";
   for (i = 0; i < iItens; i++) {
     iItensSel = iItensSel+sVrg+$('listasaltes').options[i].value;
     sVrg     =',';
   }
   
   js_divCarregando("Aguarde.. Processando Contas","msgbox");
   var oParam      = new Object();
   oParam.exec     = "reprocessarSaldo";
   oParam.database = dtDataBase;
   oParam.itenssel = iItensSel;
   
   var oAjax       = new Ajax.Request(
                                      "cai4_saltesRPC.php", 
                                      {
                                      method    : 'post', 
                                      parameters: 'json='+js_objectToJson(oParam), 
                                      onComplete: js_retornoProcessamento
                                      }
                                    );
}

function js_retornoProcessamento (oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    alert('Reprocessamento efetuado com sucesso!');
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
</script>