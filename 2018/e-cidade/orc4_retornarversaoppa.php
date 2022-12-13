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
 * @author Iuri guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ppaversao_classe.php");
$clppaversao = new cl_ppaversao;
$clppaversao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("o01_descricao");
$db_opcao = 3;
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
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC">
    <table width="790" border="0" cellpadding="0" cellspacing="0" >
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
      <form name='frmNovaVersaPPA'>
      <table>
        <tr>
          <td>
            <fieldset>
              <legend>
                 <b>Retornar Versão do PPA</b>
              </legend>
              <table>
                <tr>
                  <td nowrap title="<?=@$To119_versao?>">
                    <b>Versão Atual:</b>
                  </td>
                  <td> 
                   <?
                    db_input('o119_sequencial',10,$Io119_versao,true,'hidden',$db_opcao,"");
                    db_input('o119_versao',10,$Io119_versao,true,'text',$db_opcao,"");
                   ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$To119_datainicio?>">
                    <?=@$Lo119_datainicio?>
                 </td> 
                 <td> 
                   <?
                    db_inputdata('o119_datainicio',null,null,null,true,'text',$db_opcao,"");
                   ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$To119_datatermino?>">
                    <?=@$Lo119_datatermino?>
                 </td> 
                 <td> 
                   <?
                    db_inputdata('o119_datatermino',null,null,null,true,'text',$db_opcao,"");
                   ?>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="<?=@$To119_versao?>">
                    <b>Versão Base:</b>
                  </td>
                  <td> 
                   <?
                    db_select('versaobase',array(), true, 1);
                   ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
           <td style='text-align: center'>
             <input type="button" value='Confirmar' onclick='js_retornaVersaoPPA()'>
             <input type="button" value='pesquisar'         onclick='js_pesquisaVersoes()'>
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
sUrlRPC = "orc4_ppaRPC.php";
function js_pesquisaVersoes() {
  
  js_OpenJanelaIframe('',
                      'db_iframe_ppaversao',
                      'func_ppaversao.php?funcao_js=parent.js_mostraversao|o119_sequencial|o119_ppalei',
                      'Versões do  PPA',
                       true);
} 
function js_mostraversao(iVersao, iLei) {

   var oParam           = new Object();
   oParam.iCodigoLei    = iLei;
   oParam.iCodigoVersao = iVersao;
   oParam.iTipo         = 0;
   oParam.lVersoes      = 1;
   oParam.exec          = "getDadosVersao";
   var oAjax = new Ajax.Request (
                                 sUrlRPC,
                                 {
                                  method    : 'post', 
                                  parameters: 'json='+js_objectToJson(oParam), 
                                  onComplete: js_retornoMostraVersao
                                 }
                                ); 
}  

function js_retornoMostraVersao(oAjax) {
   
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    
    var a = $$('input[type=text]');
    a.each(function(input,id) {
       
       var valor   = eval("oRetorno."+input.id);
       input.value = valor;
       
     });
     if (oRetorno.itens.length > 0 ) {
       
       oRetorno.itens.each(function (oVersao, id) {
         
         var sStringVersao = "V"+oVersao.o119_versao+" - "+oVersao.o119_datainicio+" - "+oVersao.o119_datatermino;
         $('versaobase').add(new Option(sStringVersao, oVersao.o119_sequencial), null);
         
       });
     }
  }
  $('o119_sequencial').value = oRetorno.o119_sequencial;
  db_iframe_ppaversao.hide();
}


function js_retornaVersaoPPA() {
   
   
  js_controleBotoes(true);
  var sMsg  = "Versão Atual do ppa ("+$F('o119_versao')+") será atualizada com os dados da versao \n"; 
  sMsg     += $('versaobase').options[$('versaobase').selectedIndex].innerHTML+".\n";
  sMsg     += "IMPORTANTE: Os dados de outras instituições também serão revertidos.\n"
  sMsg     +="Confirmar O procedimento?"; 
  if (!confirm(sMsg)) {
   
    js_controleBotoes(false);
    return false;
  }
  js_divCarregando("Aguarde, processando o retorno da versão do ppa","msgbox");
  var oParam                   = new Object();
  oParam.iCodigoVersao         = $F('o119_sequencial');
  oParam.iCodigoVersaoRetornar = $F('versaobase');
  oParam.iTipo                 = 0;
  oParam.exec                  = "retornaVersaoPPA";
  var oAjax = new Ajax.Request (
                                sUrlRPC,
                                {
                                 method    : 'post', 
                                 parameters: 'json='+js_objectToJson(oParam), 
                                 onComplete: js_retornoNewVersaoPPA
                                }
                               ); 
}
function js_retornoNewVersaoPPA(oAjax) {
  
  js_removeObj("msgbox");
  js_controleBotoes(false);   
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {
    js_pesquisaVersoes();
  }
} 

function js_controleBotoes(lDisabled) {
   
   var aItens = $$('input[type=submit], input[type=button], button');
   aItens.each(function(input,id) {
       
     input.disabled = lDisabled;
       
   });
} 
js_pesquisaVersoes();  
</script>