<?
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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$oRotulo = new rotulocampo();
$oRotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>
<style>
  .field {
    border : 0px;
    border-top: 2px groove white; 
  }
 fieldset.field table tr td:FIRST-CHILD {
   width: 150px;
 	 white-space: nowrap;
}  
 .link_botao {
    color: blue;
    cursor: pointer;
    text-decoration: underline;
  }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad=" a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="">
  <fieldset style="margin-top:50px; width: 700px;">
    <legend><strong>Situação do CPF</strong></legend>
    <table  align="center" width="100%" cellpadding="" border="0">
      <tr>
        <td><b>
				  <?
				    db_ancora($Lz01_nome, 'js_mostranomes(true);', 4)
				  ?></b>
        </td>
        <td>
			   <input type="text" name="z01_numcgm" id="z01_numcgm" maxlength="8" size="8" onchange="js_mostranomes(false);" />
			  
			   <?
			     db_input("z01_nome", 40,"", true, 'text', 3)
			   ?>
        </td>        
      </tr>
    
      <tr>
        <td>
           <b>Situação do CPF :</b>   
        </td>
        <td>
           <?
             $aSituacao = array( '0' => 'Selecione...',
                                 '1' => 'Regular',
                                 '2' => 'Irregular',
                                 '3' => 'Suspenso' );
             
             db_select('situacao',$aSituacao,true,1,'');            
          ?>
        </td>        
      </tr>
     
    </table>
  </fieldset>
    <table  align="center" width="100%" cellpadding="5" border="0">  
      <tr>
         <td colspan="2" align="center">
         
           <input type="button" style="margin-left: 10px; margin-top: 10px;" id='salvar_selecao' value="Salvar" 
                                                                                       onclick="js_salvasituacao();" />
         </td>
      </tr> 
    </table>       
</form>   

<div id='ficha'>
</div>

</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

var sUrlRPC = 'hab4_situacaocpf.RPC.php';  

function js_mostranomes(mostra){

  $('situacao').value = '0'; 
  
  if(mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?filtro=1&funcao_js=parent.js_preenche|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?filtro=1&pesquisa_chave='+$F('z01_numcgm')+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}
function js_preenche(chave,chave1){
  
  js_consultaSituacao(chave);
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value = chave1;
  db_iframe_nomes.hide();
}
function js_preenche1(chave,chave1){

  document.form1.z01_nome.value = chave1;
  
  if(chave){
  
    document.form1.z01_numcgm.value = "";
    document.form1.z01_numcgm.focus();
  } else {
    js_consultaSituacao(document.form1.z01_numcgm.value);
  }
}
//////////////////////

function js_salvasituacao() {

  var iCgm      = $F('z01_numcgm');
  var iSituacao = $F('situacao');
  var msgDiv    = "Salvando ...";
  
  if (iCgm == null || iCgm == '') {
    alert('Selecione um CGM ');
    return false;
  }
  if (iSituacao == null || iSituacao == '') {
    alert('Selecione uma Situação');
    return false;
  }  
  
   
   var oParametros    = new Object();
   oParametros.exec   = 'salva_situacao'; 
   
   oParametros.iCgm      = iCgm;
   oParametros.iSituacao = iSituacao;  
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters: 'json='+Object.toJSON(oParametros),
                                              onComplete: js_situacaosalvo
                                              });  
  
}
function js_situacaosalvo(oAjax) {

    js_removeObj('msgBox');
    

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {
    
      //ogridAtividades.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        alert('Nenhum registro encontrado!');
        return false;
      } 
      oRetorno.dados.each( 
       function (oDado, iInd) {       

         aRow     = new Array();  
         aRow[0]  = oDado.salvo;

       });
       
       alert(aRow[0]);             
    } 

}


function js_consultaSituacao(iCgm) {

  if ( iCgm == '') {
  
    alert('CGM não informado!');
    return false;
  }
   
  var oParametros   = new Object();
  oParametros.exec  = 'consultaSituacaoCPF'; 
  oParametros.iCgm  = iCgm;
    
  js_divCarregando("Consultando situação do CPF ...",'msgBox');
 
  var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                            {method: "post",
                                             parameters: 'json='+Object.toJSON(oParametros),
                                             onComplete: js_retornoConsulta
                                            });  
  
}

function js_retornoConsulta(oAjax){

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode());
    return false;
  } else {
    $('situacao').value = oRetorno.iSituacao;  
  } 
}


</script>