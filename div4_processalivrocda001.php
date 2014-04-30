<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_divida_classe.php");
include("classes/db_certidlivro_classe.php");
include("dbforms/db_funcoes.php");

$clrotulo         = new rotulocampo;
$cldivida         = new cl_divida;
$oDaoCertidLivro  = new cl_certidlivro;
$sSqlproximoLivro = "select coalesce(max(v25_numero),0)+1 as livro from certidlivro";
$rsProximoLivro   = $oDaoCertidLivro->sql_record($sSqlproximoLivro);
$livro            = db_utils::fieldsMemory($rsProximoLivro, 0)->livro; 
$pagina           = 1;
$clrotulo->label("v01_livro");
$clrotulo->label("v01_dtoper");
$clrotulo->label("v14_certid");
$db_opcao = 1;
db_postmemory($HTTP_POST_VARS);

?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
   db_app::load("scripts.js, estilos.css, prototype.js, strings.js");
  ?>
 </head>
 <body bgcolor=#CCCCCC onLoad="if(document.form1) document.form1.elements[0].focus()" >



<form class="container" name='form1' method="post">
  <fieldset>
    <legend>Processar Livro das CDA's </legend>
    <table class="form-container">
      <tr>
        <td>
          Tipo:
        </td>
        <td>
          <?
            $aTipos = array( 
                            1 => "Ambos",
                            2 => "Dívida",
                            3 => "Parcelamento"
                            );              
            db_select('tipo',$aTipos,true,4,"onchange='js_setArquivolookup(this.value)'");                 
                               
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <?
            db_ancora(@$Lv14_certid,"js_pesquisaparcel(true)",4)
          ?>   
        </td> 
        <td nowrap>   
          <?
            db_input('v14_certid',10,$Iv14_certid,true,'text',4,
                     "onchange='js_pesquisaparcel(false);'");
          ?>   

         <b> Até </b>
          <?
            db_ancora(@$Lv14_certid,"js_pesquisaparcel1(true)",4);
          ?>
          <? 
            db_input('v14_certid1',10,$Iv14_certid,true,'text',4,"onchange='js_pesquisaparcel1(false);'","v14_certid1");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Data Inicial: 
        </td>
        <td>
            <?=db_inputdata('datainicial','','','',true,'text',4)?>

          <b>A</b> 
          <?=db_inputdata('datafinal','','','',true,'text',4)?>
        </td>  
      </tr>
      <tr>
        <td>
          Complementar:
        </td>
        <td>
          <?
            $aComplementar = array( 
                                   1 => "Não",
                                   2 => "Sim",
                                  );              
            db_select('tipolivro',$aComplementar,true,4,"");                               
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Livro:
        </td> 
        <td>
          <?
            db_input("livro",10,4,true,"text");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Folha:
        </td> 
        <td>
          <?
            db_input("pagina",10,4,true,"text");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type='button' value='Processar Livro' id='processar' onclick='js_processaLivro()'>
</form>        
        

  <? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
 </body>
</html>
<script>
 sUrlRPC = 'div4_certidlivro.RPC.php';
 sLookUp = 'func_livrocda.php';
 function imprime() {
 
  jan = window.open('','livro','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  return true;
  
 }
 function js_pesquisaparcel(mostra){
     
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?funcao_js=parent.js_mostratermo1|0','Pesquisa',true);      
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?pesquisa_chave='+document.form1.v14_certid.value+'&funcao_js=parent.js_mostratermo','Pesquisa',false);       
     }
}
function js_mostratermo(chave,erro) {
  
  if(erro==true){
     document.form1.v14_certid.focus();
     document.form1.v14_certid.value = '';
     document.form1.v14_certid1.value = '';
  } else {
    document.form1.v14_certid1.value=document.form1.v14_certid.value;
  }
}

function js_mostratermo1(chave1) {

     document.form1.v14_certid.value  = chave1;
     document.form1.v14_certid1.value = chave1;
     db_iframe.hide();
}
function js_pesquisaparcel1(mostra){
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?funcao_js=parent.js_mostratermo11|0','Pesquisa',true);       
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?pesquisa_chave='+document.form1.v14_certid1.value+'&funcao_js=parent.js_mostratermo2','Pesquisa',false);       
     }
}
function js_mostratermo2(chave,erro){
  if(erro==true){
     document.form1.v14_certid1.focus();
     document.form1.v14_certid1.value = '';
  }
}
function js_mostratermo11(chave1){
     document.form1.v14_certid1.value = chave1;
     db_iframe.hide();
}
function js_verifica(){
  var val1 = new Number(document.form1.DBtxt10.value);
  var val2 = new Number(document.form1.DBtxt11.value);
  if(val1.valueOf() >= val2.valueOf()){
     alert('Valor máximo menor que o valor mínimo.');
     return false;
  }
  return true;
}    

function js_setArquivolookup(tipo) {

  switch (tipo) {
    
    case '1': 
      
      sLookUp = 'func_livrocda.php';
      break;
      
    case '2':
    
      sLookUp = 'func_certdiv.php';
      break;  
    
    case '3':
    
      sLookUp = 'func_certter.php';
      break;   
  }
  
  $('v14_certid').value  = '';
  $('v14_certid1').value = '';
  
}


function getProximaPagina() {

  var oParam          = new Object();
      oParam.exec     = "getProximaPagina";
      oParam.livro    = $F('livro');
      
      var oAjax = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoGetProximaPagina
                          }
                        );
      
}

function js_retornoGetProximaPagina(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");
   if (oRetorno.status == 1) {
     $('pagina').value = oRetorno.proximapagina;
   }
}


function js_verificaPagina() {
  
  var iTipoLivro = $F('tipolivro');
  if (iTipoLivro == 2) {
    
    $('livro').readOnly = false;
    $('livro').style.backgroundColor='#FFFFFF';
    $('livro').observe("change",getProximaPagina);
    getProximaPagina();
        
  } else {
    
    $('pagina').value   = 1;  
    $('livro').value    = <?=$livro?>;
    $('livro').readOnly = true;
    $('livro').stopObserving("change", getProximaPagina);
    $('livro').style.backgroundColor='#DEB887';    
    
  }
}

function js_processaLivro() {

  var oParam             = new Object();
      oParam.exec        = "processaLivro";
      oParam.options     = new Object();
      
      oParam.options.v14_inicial = $F('v14_certid');
      oParam.options.v14_final   = $F('v14_certid1');
      oParam.options.datainicial = $F('datainicial');
      oParam.options.datafinal   = $F('datafinal');
      oParam.options.tipo        = $F('tipo');
      oParam.options.tipolivro   = $F('tipolivro');
      oParam.options.livro       = $F('livro');
      js_divCarregando('Aguarde, processando o livro', 'msgbox');
      $('processar').disabled = true; 
      var oAjax = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoProcessaLivro
                          }
                        );
                        
                       
}

function js_retornoProcessaLivro(oAjax) {
    
    js_removeObj('msgbox');
    $('processar').disabled = false;
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
    
      alert('Livro processado com sucesso!');
      location.href='div4_processalivrocda001.php';
      
    } else {
    
      alert(oRetorno.message.urlDecode());
      $('processar').disabled = false;
      
    }
} 
$('tipolivro').observe("change", js_verificaPagina);
</script>
<script>

$("v14_certid").addClassName("field-size2");
$("v14_certid1").addClassName("field-size2");
$("datainicial").addClassName("field-size2");
$("datafinal").addClassName("field-size2");
$("tipolivro").setAttribute("rel","ignore-css");
$("tipolivro").addClassName("field-size2");
$("livro").addClassName("field-size2");
$("pagina").addClassName("field-size2");

</script>