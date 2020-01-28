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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("e43_ordempagamento");
$oRotuloCampos->label("e42_dtpagamento");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="360" height="20">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <form name='form1' method="post">
  <center>
  <table>
     <tr>
       <td>
         <fieldset>
            <legend>
              <b>Manutenção da Ordem Auxiliar</b>
            </legend>
            <table>
              <tr>
                <td> 
                   <b>
                   <? db_ancora("<b>Código da Ordem Auxiliar</b>","js_pesquisae43_ordempagamento(true);",1);  ?>
                     
                   </b>
                </td>
                <td>
                  <?
                    db_input("e43_ordempagamento", 10, $Ie43_ordempagamento, true,"text",1,"onchange='js_pesquisae43_ordempagamento(false)';");     
                  ?>
                </td>
              </tr>
              <tr>
                <td> 
                   <b>
                     Data da Emissão
                   </b>
                </td>
                <td>
                  <?
                    db_inputdata("e42_dtpagamento", null,null,null,true,"text",1);     
                  ?>
                </td>
              </tr>
            </table>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td style="text-align: center">
         <input type="button" value='Continuar' onclick="js_carregaOPauxiliar()">
         <input type="button" value='Incluir Nova' onclick="js_novaOPAuxiliar()">
       </td>
     </tr>   
  </table>
  </center>
  </form>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
sUrl = "emp4_ordemPagamentoRPC.php";
function js_pesquisae43_ordempagamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'func_nome',
                        'func_empageordem.php?funcao_js=parent.js_mostraordem1|e42_sequencial|e42_dtpagamento&credor=false',
                        'Pesquisa OP Auxiliares',
                        true,
                        22,
                        0,
                        document.width-12,
                        document.body.scrollHeight-30
                        );
  } else {
    if ($F('e43_ordempagamento') != "") {
      js_OpenJanelaIframe('',
                         'func_nome',
                         'func_empageordem.php?pesquisa_chave='+$F('e43_ordempagamento')+'&funcao_js=parent.js_mostraordemagenda&credor=false',
                         'Pesquisa OP Auxiliares',
                         false,
                         22,
                         0,
                         document.width-12,
                         document.body.scrollHeight-30);
    } else {
      $('e43_ordempagamento').value = '';
    }   
  }
}

function js_mostraordem1(chave1,chave2){
  
  document.form1.e43_ordempagamento.value = chave1;
  document.form1.e42_dtpagamento.value = js_formatar(chave2,"d");
  func_nome.hide();
  
}

function js_mostraordemagenda(chave,erro){
  
  if(!erro) { 
    document.form1.e42_dtpagamento.value = chave;
  } else {
  
    document.form1.e43_ordempagamento.value  = ''; 
    document.form1.e42_dtpagamento.value = '';
    
  }
}

function js_carregaOPauxiliar() {

  if ($F('e43_ordempagamento') != "") {
    
    var iAlturaViewPort   = document.body.scrollHeight-30;
    var iLArguraViewPort  = document.body.scrollWidth-12;
    js_OpenJanelaIframe('top.corpo','db_iframe_op',
                        'emp4_empageordem002.php?e42_sequencial='+$F('e43_ordempagamento'),
                        'Manutenção OP Auxiliar',
                        true,
                        22,
                        0,
                        iLArguraViewPort,
                        iAlturaViewPort
                       );
  }
}

function js_novaOPAuxiliar() {

  if ($F('e42_dtpagamento') == "") {
    
    alert('Data de pagamento não informada!');
    return false;
   
  }
  var oParametros             = new Object();
  oParametros.exec            = "incluirOP";
  oParametros.e42_dtpagamento = $F('e42_dtpagamento');
  js_divCarregando("Aguarde, Gerando Autorização.","msgBox");
  var oAjax   = new Ajax.Request(
                           sUrl, 
                           {
                            method    : 'post', 
                            parameters: 'json='+Object.toJSON(oParametros), 
                            onComplete: function(oResponse) {
                            
                              js_removeObj("msgBox");
                              oRetorno = eval ("("+oResponse.responseText+")");
                              if (oRetorno.status == 1) {
                              
                                 $('e43_ordempagamento').value = oRetorno.iCodigoOPaxiliar;
                                 js_carregaOPauxiliar();
                                 
                              } else {
                                alert(oRetorno.message.urlDecode());
                              }
                            }
                          }
                          );
} 
</script>