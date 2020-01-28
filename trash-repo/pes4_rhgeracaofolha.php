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

//M�DULO PESSOAL > GERACA��O EM DISCO
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_folha_classe.php");
require_once("classes/db_rhpessoal_classe.php");

$oGet = db_utils::postMemory($_GET);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
 db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, dbmessageBoard.widget.js, estilos.css, grid.style.css");
?>
<style>
  .negativos {
    background-color: rgb(222, 184, 135);
    color: #999999;
  }
</style>
</head>
<body style="background-color:#CCCCCC; margin: 0px">
<center>
  <div class='geracaoFolha' style='text-align:center;padding:0px;width:100%'>
    <div id='ContainerMessage'></div>
    <div>
      <fieldset style='margin:0'><legend><b>Servidores</b></legend>
        <div id='ContainerGrid'></div>
     </fieldset>
    </div>
  </div>
  <br>
  <input type="submit" value="Confirma" onclick="js_enviaDados();" />
</center>
<script>
function js_mostraMessageBoard(){
  var sMsg  = "Abaixo os servidores dispon�veis para gera��o em disco \n";
      sMsg += "";
  oMessage  = new DBMessageBoard('msgboard', 
                                 'Selecione os servidores para Gera��o em Disco',
                                 sMsg,
                                 document.getElementById('ContainerMessage'));
  oMessage.show();
}
function js_montaGrid() {

  oGridGeracaoFolha              = new DBGrid('oGridGeracaoFolha');
  oGridGeracaoFolha.nameInstance = 'oGridGeracaoFolha';
  oGridGeracaoFolha.sName        = 'oGridGeracaoFolha';
  oGridGeracaoFolha.setHeight(300);
  oGridGeracaoFolha.setCheckbox(0);
  oGridGeracaoFolha.setCellAlign(new Array("center", "left", "right", "right", "right", "right","left", "left", "center", "center", "center", "center"));
  oGridGeracaoFolha.setHeader(new Array('Matr', 'Nome', 'Bruto','Desconto', 'Liquido', 'Valor Recebido','Cargo','Secretaria','Banco','Ag�ncia','CC','Tipo Folha'));
  oGridGeracaoFolha.show($('ContainerGrid'));
}
function appendCampo(sId,sValor, sAonde){
  var oInput = document.createElement("input");
  //oInput.display = none;
}
function js_getDados() {

  var oUrl      = js_urlToObject(window.location.search);
  var me        = this;
  this.sRPC     = 'pes4_rhgeracaofolha.RPC.php';
  var oParam    = new Object();
  oParam.exec   = 'getServidores';
  oParam.oDados = oUrl;
  js_divCarregando('Obtendo dados dos servidores', 'msgBox');
  var oAjax     = new Ajax.Request(me.sRPC,
                              {method: 'post',
                              parameters: 'json='+Object.toJSON(oParam), 
                              onComplete: function(oAjax) {
                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  if (oRetorno.status== "2") {
                                  alert(oRetorno.message.urlDecode());
                                } else {
                                  oGridGeracaoFolha.clearAll(true);
                                  if (oRetorno.aServidores.length > 0) {
                                     var sDados = "";
                                     for (i = 0; i < oRetorno.aServidores.length; i++) {

                                       with (oRetorno.aServidores[i]) {
                                    
                                         var aLinha   = new Array();
                                         aLinha[0]    = regist;             
                                         aLinha[1]    = z01_nome.urlDecode();             
                                         aLinha[2]    = js_formatar(proven,"f");             
                                         aLinha[3]    = js_formatar(descon,"f");  
                                         aLinha[4]    = js_formatar(liquido,"f");  
                                         aLinha[5]    = js_formatar(valor_recebido,"f");  
                                         aLinha[6]    = rh37_descr.urlDecode();
                                         aLinha[7]    = r70_descr.urlDecode();  
                                         aLinha[8]    = rh44_codban;  
                                         aLinha[9]    = rh44_agencia;  
                                         aLinha[10]   = rh44_conta;
                                         sDados       = "rh02_seqpes="+rh02_seqpes;
                                         sDados      += "&regist="+regist;
                                         sDados      += "&f010="+f010;                                                                                  
                                         sDados      += "&liquido="+liquido;
                                         sDados      += "&proven="+proven;
                                         sDados      += "&descon="+descon;                                         
                                         sDados      += "&tipo_folha="+tipo_folha;                                                    
                                         aLinha[11]   = label_tipo_folha.urlDecode();
                                         
                                         var iLiquido = new Number(liquido);
                                         var iRecebido= new Number(valor_recebido);
                                         if (iLiquido <= 0 || iLiquido <= iRecebido ) {
                                           
                                           oGridGeracaoFolha.addRow(aLinha,null,true);
                                           oGridGeracaoFolha.aRows[i].setClassName("negativos");
                                         } else {
                                         
                                           oGridGeracaoFolha.addRow(aLinha);
                                         }
    
                                       }
                                     }
                                     oGridGeracaoFolha.renderRows();
                                  }  
                                }
                               
                              }
                            }) ;

}
function js_enviaDados(){

  var oSaida    = new Array();

  oGridGeracaoFolha.getSelection().each(function(aCampo, iIndice){

    oSaida[iIndice] = aCampo[0];
  });
  parent.js_geraFolha(oSaida);
}
js_mostraMessageBoard()
js_montaGrid();
js_getDados();
</script>
</body>
</html>