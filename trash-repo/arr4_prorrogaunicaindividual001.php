<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ("fpdf151/scpdf.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("fpdf151/impcarne.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_libtributario.php");
require_once ("dbforms/db_funcoes.php");


require_once("classes/db_cadban_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_docparag_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_listadoc_classe.php");
require_once("classes/db_db_layouttxtgeracao_classe.php");
require_once("libs/db_app.utils.php");

$cldb_config           = new cl_db_config;
$cldb_docparag         = new cl_db_docparag;

db_postmemory($HTTP_POST_VARS);

?>
<html>
 <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <script language="JavaScript" type="text/javascript" src="scripts/libJsonJs.js"></script> 
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");  
  db_app::load("DBHint.widget.js");   
?>          
</head>
<style>
  legend {
    font-weight: bold;
  }
  a {
    font-weight: bold;
  }
  td {
    font-weight: bold;
    white-space: nowrap;
  }
</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  onload="">
<form name="form1" action="" method="post">

  <table align="center" width="450" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top" bgcolor="#CCCCCC"  style="padding-top:30px;">
      
        <fieldset>
          <legend align="left">
            Pesquisa
          </legend>
          
            <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="35%" align="left" >
                  <?db_ancora("Código da Geração :", "js_pesquisalista(true);", 4);?>
                </td>
                <td align="left">
                  <?
                    db_input("ar40_sequencial",  10, "", true, "text", 4, "onchange='js_pesquisalista(false);'");
                  ?>
                </td>
              </tr>          
            </table>
         </fieldset>
         
       </td>
     </tr>
     
     <!-- 
     <tr>
       <td align="center">
         <input style="margin-top: 10px;" name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="">
         
       </td>
     </tr>
     -->
     
     
    <tr>
      <td height="100%" align="center" valign="top" bgcolor="#CCCCCC"  style="padding-top:30px;">
      
        <fieldset>
          <legend align="left">
            Informações
          </legend>
          <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
          
            <tr>
              <td width="50%" >Tipo de Geração :</td>
              <td ><?db_input("sTipoGeracao", 10, "", true, "text",3); ?></td>
            </tr>
            <tr>
              <td >Data Vencimento :</td>
              <td ><?db_inputdata("dtVencimento", "", "", "", true, "", 1); ?></td>
            </tr>
            <tr>
              <td >Data do Lançamento :</td>
              <td ><?db_inputdata("dtLancamento", "", "", "", true, "", 1); ?></td>
            </tr>
            <tr>
              <td >Percentual de Desconto :</td>
              <td ><?db_input("desconto",  10, "", true, "text", 1 ); ?></td>
            </tr>
            <tr>
              <td >Observações :</td>
              <td ><? db_textarea("obs",3, 35, "", true, "", 1); ?></td>
            </tr>                                                
          
          </table>
          
         </fieldset>
         
       </td>
     </tr>
     <tr>
       <td align="center">
         <input style="margin-top: 10px;" name="alterar" type="button" id="alterar" value="Alterar" onclick="js_prorrogar();">
         
       </td>
     </tr>     
     
     
   </table>
 </form>  
 
  <? db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
 </body>
</html>
<script>

function js_prorrogar() {

   var msgDiv                = "Aguarde ...";
   var oParametros           = new Object();
   var sUrlRPC               = "arr4_recibounicaGeracao.RPC.php";
   
   var iCodGeracao           = $F('ar40_sequencial');
   var dtVencimento          = $F('dtVencimento');
   var dtLancamento          = $F('dtLancamento');
   var iPercDesconto         = $F('desconto');
   var sObs                  = $F('obs');
   
   oParametros.exec          = 'prorrogar';
   oParametros.iCodGeracao   = iCodGeracao;
   oParametros.dtVencimento  = dtVencimento;
   oParametros.dtLancamento  = dtLancamento;
   oParametros.iPercDesconto = iPercDesconto;
   oParametros.sObs          = sObs;
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoProrrogacao
                                             });
                                            
}

function js_retornoProrrogacao(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {   
      
      alert('Atualização realizada');
      return false;
    } else {
    
      alert(oRetorno.msg);
      return false;
    }
}


function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lista','func_geracao.php?funcao_js=parent.js_mostralista1|ar40_sequencial|k60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_lista','func_geracao.php?pesquisa_chave='+document.form1.ar40_sequencial.value+'&funcao_js=parent.js_mostralista','Pesquisa', false);
  }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
    document.form1.k60_descr.focus();
    document.form1.k60_descr.value = '';
  }
  db_iframe_lista.hide();
  js_habilita();
}

function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe_lista.hide();
  js_habilita();
}



/*
var aEventoShow = new Array('onMouseover','onFocus');
var aEventoHide = new Array('onMouseout' ,'onBlur');


var oDbHintQuantidade = new DBHint('oDbHintQuantidade');
    oDbHintQuantidade.setText('Quantidade de registros a processar ');
    oDbHintQuantidade.setShowEvents(aEventoShow);
    oDbHintQuantidade.setHideEvents(aEventoHide);
    oDbHintQuantidade.make($('quantidade'));
    
var oDbHintProcessarmovimentacao = new DBHint('oDbHintProcessarmovimentacao');
    oDbHintProcessarmovimentacao.setText('Processar apenas registros com movimentação nos últimos anos especificados ');
    oDbHintProcessarmovimentacao.setShowEvents(aEventoShow);
    oDbHintProcessarmovimentacao.setHideEvents(aEventoHide);
    oDbHintProcessarmovimentacao.make($('processarmovimentacao'));    

*/

</script>