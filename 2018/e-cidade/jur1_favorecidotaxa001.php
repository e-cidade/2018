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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_favorecido_classe.php");
require_once("classes/db_favorecidotaxa_classe.php");
require_once("classes/db_favorecidotaxa_classe.php");
require_once("dbforms/db_funcoes.php");

$oGet                = db_utils::postmemory($_GET);
$oPost               = db_utils::postmemory($_POST);
$db_opcao            = 1;
$db_botao            = "";
$oDaoFavorecidoTaxa  = new cl_favorecidotaxa();
$oDaoFavorecido      = new cl_favorecido();
$oRotulos            = new rotulocampo;

$sSqlFavorecido      = $oDaoFavorecido->sql_query($oGet->v86_sequencial," v86_sequencial, z01_nome "); 
$rsSqlFavorecido     = $oDaoFavorecido->sql_record($sSqlFavorecido);  
$oFavorecido         = db_utils::fieldsMemory($rsSqlFavorecido,0);
$v87_favorecido      = $oFavorecido->v86_sequencial;
$z01_nome            = $oFavorecido->z01_nome;

$oRotulos->label("ar36_descricao");
$oRotulos->label("z01_nome");
$oRotulos->label("v87_favorecido");
$oRotulos->label("v87_sequencial");
$oRotulos->label("v87_taxa");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
   db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, estilos.css, grid.style.css");
  ?>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_montaGrid();" >
  <BR>
  <BR>
  <form name="form1" method="post" action="" onsubmit="return js_enviaDados();">
    <center>
      <fieldset style="width: 600px; ">
      	<legend><b>Dados Taxa:</b></legend>
      
        <table border="0">
          <tr>
              <td nowrap title="<?=@$Tv87_favorecido?>">
                <B>Favorecido: </B>
              </td>
            <td> 
              <?
                db_input('v87_favorecido',5,$Iv87_favorecido,true,'text',3);
                db_input('z01_nome',55,$Iz01_nome,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tv87_taxa?>">
              <?
              db_ancora(@$Lv87_taxa,"js_pesquisav87_taxa(true);",$db_opcao);
              ?>
            </td>
            <td> 
              <?
              db_input('v87_taxa',5,$Iv87_taxa,true,'text',$db_opcao," onchange='js_pesquisav87_taxa(false);'");
              db_input('ar36_descricao',55,$Iar36_descricao,true,'text',3,'')
              ?>
              </td>
          </tr>
        </table>
      </fieldset> 
      <BR>
      <input name="<?=($db_opcao==1?"incluir":"excluir")?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Excluir");?>">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </center>
  </form>
  <center>
    <fieldset style="width: 600;">
      <legend><B>Taxas Vinculadas: </B></legend>
      <div id="cntTaxas"></div>
    </fieldset>
  </center>
</body>

<script>

function js_excluir(ar36_sequencial){
  
  var sMsg = "Confirma exclusão da taxa?";
  if(confirm(sMsg)){
    
    oParam                  = new Object();
    oParam.exec             = 'Excluir';
    oParam.v87_sequencial   = ar36_sequencial;
    js_divCarregando('Excluindo registro ...', 'msgBox');
    
    var oAjax = new Ajax.Request("jur1_favorecidotaxa.RPC.php",
                                 {method    : 'post',
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: 
                                    function(oAjax) {
        
                                      js_removeObj('msgBox');
                                      var oRetorno = eval("("+oAjax.responseText+")");
                                      if(oRetorno.status != 2){
                                    	  getTaxas();
                                      }
                                      alert(oRetorno.message.urlDecode());
                                      
                                    }
                                 }
                                ) ;
  } 
}

function js_enviaDados(){
  
  oParam                  = new Object();
  oParam.exec             = 'Incluir';
  oParam.v87_favorecido   = $F('v87_favorecido');
  oParam.v87_taxa         = $F('v87_taxa');
  
  js_divCarregando('Gravando Dados', 'msgBox');
  var oAjax = new Ajax.Request("jur1_favorecidotaxa.RPC.php",
                               {method    : 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: 
                                  function(oAjax) {
      
                                    js_removeObj('msgBox');
                                    var oRetorno = eval("("+oAjax.responseText+")");
                                    alert(oRetorno.message.urlDecode());
                                    getTaxas();
                                  }
                               }
                              ) ;
  return false;
}

function js_pesquisav87_taxa(mostra) {
	
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_taxa','func_taxa.php?funcao_js=parent.js_mostrataxa1|ar36_sequencial|ar36_descricao','Pesquisa',true);
  }else{
	  
     if(document.form1.v87_taxa.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_taxa','func_taxa.php?pesquisa_chave='+document.form1.v87_taxa.value+'&funcao_js=parent.js_mostrataxa','Pesquisa',false);
     }else{
       document.form1.ar36_descricao.value = ''; 
     }
  }
}

function js_mostrataxa(chave,erro) {
	
  document.form1.ar36_descricao.value = chave; 
  if (erro==true) { 
	  
    document.form1.v87_taxa.focus(); 
    document.form1.v87_taxa.value = ''; 
  }
}
function js_mostrataxa1(chave1,chave2) {
	
  document.form1.v87_taxa.value = chave1;
  document.form1.ar36_descricao.value = chave2;
  db_iframe_taxa.hide();
}
function js_pesquisa() {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_favorecidotaxa','func_favorecidotaxa.php?funcao_js=parent.js_preenchepesquisa|v87_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(chave) {
  db_iframe_favorecidotaxa.hide();
}

function js_montaGrid() {

     oGridTaxas              = new DBGrid('oGridTaxas');
     oGridTaxas.nameInstance = 'oGridTaxas';
     oGridTaxas.sName        = 'oGridTaxas';
     oGridTaxas.setCellAlign  (new Array("left", "center"));
     aHeaders                = new Array('Descrição Taxa',"Ação");
     oGridTaxas.setHeader(aHeaders);
     oGridTaxas.show($('cntTaxas'));
  }
  
function getTaxas(){
    var me                      = this;
    this.sRPC                   = 'jur1_favorecidotaxa.RPC.php';
    var oParam                  = new Object();
    oParam.exec                 = 'getTaxas';
    oParam.v87_favorecido       = $F('v87_favorecido'); 
    js_divCarregando('Obtento Informações das Taxa dfavorecido', 'msgBox');
    var oAjax  = new Ajax.Request(me.sRPC,
                                {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                  if (oRetorno.status== "2") {
                                    alert(oRetorno.message);
                                  } else {
                                    oGridTaxas.clearAll(true);
                                    if (oRetorno.aTaxas.length > 0) {
                                          
                                      for (i = 0; i < oRetorno.aTaxas.length; i++) {
                                      
                                        with (oRetorno.aTaxas[i]) {
                                        
                                          var aLinha = new Array();
                                          aLinha[0]  = ar36_descricao; 
                                          aLinha[1]  = "<a href=\'#\' onClick=\'js_excluir("+v87_sequencial+");\'>Excluir</a>";          
                                          oGridTaxas.addRow(aLinha);             
                                        }
                                      }
                                      oGridTaxas.renderRows();
                                    }
                                  }
                                }
                              }) ;

}
getTaxas();

</script>