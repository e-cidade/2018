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
include_once("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;

$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k31_obs');
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS);

$db_opcao = 1;
$db_botao = true;

$instit = db_getsession("DB_instit");



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
?>
<script>  
function js_emite(){

  var val1 = new Number(document.form1.k60_codigo.value);
  if(val1.valueOf() < 1){
     alert('A lista tem que ser selecionada.');
     return false;
  }
  jan = window.open('cai2_emitelista002.php?lista='+document.form1.k60_codigo.value+'&k31_obs='+document.form1.k31_obs.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}






function js_prescreve() {

  if (document.form1.k60_codigo.value == '') {
     alert('Selecione a lista de debitos!');
  } else if ($F('k31_obs') == ""){
     alert('Preencha o campo Observações');    
  } else if (confirm('Deseja realmente anular as prescrições informadas?')){
   
  var oParam             = new Object();
  oParam.exec            = 'Anulacao';
  oParam.linhas          = oGridPrescricoes.getNumRows();
  oParam.debitos         = new Array();
  var aLinhas            = oGridPrescricoes.getSelection();

  for (var i = 0; i < aLinhas.length; i++) {
    
    var oDebito = new Object();
    oDebito.numpre = aLinhas[i][2];
    oDebito.numpar = aLinhas[i][3];
    oParam.debitos.push(oDebito);
    
  }

  var oAjax              = new Ajax.Request('div4_anulaprescricao.RPC.php',
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retorno
                                             });   
  }
}

function js_retorno(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    alert("Prescrição anulada com sucesso");
  } else {
    alert(""+oRetorno.message+"");
  }

} 


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_gridPrescricoes()" bgcolor="#cccccc" >
    <center style="padding-top:45px;">
      <form name="form1" method="post" action="" >
         <fieldset style="display:inline;">
          <legend><strong>Anular Prescrição por Lista</strong></legend>
       <table>
				  <tr id="numcgm">
				    <td><?db_ancora("<strong>Numcgm</strong>","js_pesquisa_numcgm(true);",1);?></td>
				    <td> 
				        <? db_input('k31_numcgm',10,@$k31_numcgm,true,'text',$db_opcao,"onblur=js_pesquisa_numcgm(false);") ?>
				        <? db_input('k31_nome',50,@$k31_nome,true,'text',3) ?>
				    </td>    
				  </tr>
				  <tr id="matricula">
				     <td><?db_ancora("<strong>Matrícula</strong>","js_mostramatricula(true);",1);?></td>
				     <td> 
				        <? db_input('k31_matric',10,@$k31_matric,true,'text',$db_opcao,"onblur=js_mostramatricula(false);") ?>
				     </td>    
				   </tr>
				   <tr id="inscr">
				    <td><?db_ancora("<strong>Inscrição</strong>","js_mostrainscricao(true);",1);?></td>
				    <td> 
				        <? db_input('k31_inscr',10,@$k31_inscr,true,'text',$db_opcao,"onblur=js_mostrainscricao(false);") ?>
				    </td>    
				  </tr>
       
            <tr>
              <td align="right" colspan="2" ><fieldset><legend><b> <?=@$Lk31_obs?></b></legend> 
              <? db_textarea('k31_obs',2,70,$Ik31_obs,'','text',$db_opcao,"") ?></fieldset></td>
            </tr>

        </table>
        </fieldset>
        <p align="center">
           <input name="processar" type="button" id="processar" value="Pesquisar" onclick='js_completaPesquisa()' >
           <input name="processar" type="button" id="processar" value="Processar" onclick='js_prescreve();' >
        </p>
   </form>
</center>

<center>
<div id="debug">
</div>
    <fieldset style="display:inline;">
      <legend><b>Lista Prescrições</b></legend>
      <div id="gridPrescricoes">
      
      </div>  
    </fieldset>

</center>

<script>

function js_gridPrescricoes() {

  oGridPrescricoes = new DBGrid('prescricoes');
  oGridPrescricoes.nameInstance = 'oGridPrescricoes';
  oGridPrescricoes.setCheckbox(1);
  oGridPrescricoes.setCellWidth(new Array('10%', '20%', '30%', '10%', '10%', '10%',  '10%', '10%', '10%', '10%', '10%'));
  oGridPrescricoes.setCellAlign(new Array('left', 'left' , 'center', 'left', 'left',  'left','right', 'right', 'right', 'right', 'right'));
  oGridPrescricoes.setHeader(new Array('Exercício Dívida', 'Numpre', 'Parcela', 'Descrição Abreviada', 'Descrição Receita Tesouraria',  'VlrHis', 'VlrCor', 'VlrJuros', 'VlrMulta', 'VlrDesconto', 'VlrTotal'));
  oGridPrescricoes.setHeight(230);
  oGridPrescricoes.hasTotalizador = true;
  oGridPrescricoes.show($('gridPrescricoes'));
  oGridPrescricoes.clearAll(true);
  
        
  js_completaPesquisa();
}

function js_completaPesquisa() {

   var oParam             = new Object();
   oParam.exec            = 'Consulta';
   oParam.k60_codigo      = $('k60_codigo').value;
   var msgDiv = "Aguarde ...";
   js_divCarregando(msgDiv,'msgBox');
   var oAjax              = new Ajax.Request('div4_anulaprescricao.RPC.php',
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retornoCompletaPesquisa
                                             });
  


}

function js_retornoCompletaPesquisa(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  
    if (oRetorno.status == 1) {
      
      oGridPrescricoes.clearAll(true);
      oRetorno.dados.each( 
                    function (oDado, iInd) {       
  
                            aRow = new Array();                                                              
                            aRow[0] = oDado.v01_exerc;
                            aRow[1] = oDado.v01_numpre;
                            aRow[2] = oDado.v01_numpar;
                            aRow[3] = oDado.k02_descr;
                            aRow[4] = oDado.k02_drecei;
                            aRow[5] = oDado.k30_valor;
                            aRow[6] = oDado.k30_vlrcorr;
                            aRow[7] = oDado.k30_vlrjuros;
                            aRow[8] = oDado.k30_multa;
                            aRow[9] = oDado.k30_desconto;
                            aRow[10] = oDado.total;
                            oGridPrescricoes.addRow(aRow);
                            oGridPrescricoes.renderRows();
                            
                       })          
    } 
        
  js_removeObj('msgBox');

}



function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

function js_pesquisalista(mostra){
     if(mostra==true){       
       var sUrl = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr'; 
     }else{
       var sUrl = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
     }
     js_OpenJanelaIframe('top.corpo','db_iframe',sUrl,'Pesquisa',mostra);
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}

function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
}

//Procura CGM
function js_pesquisa_numcgm(mostra){
 if(mostra == true){
   func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome';
   func_nome.mostraMsg();
   func_nome.show();
   func_nome.focus();
//  js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?funcao_js=parent.js_mostra1|z01_numcgm|z01_nome','Pesquisa',true);
 } else {
  if (document.form1.k31_numcgm.value != '') {
    func_nome.jan.location.href = 'func_nome.php?pesquisa_chave=' + document.form1.k31_numcgm.value + '&funcao_js=parent.js_mostra';
  } else{
      if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
      document.form1.k31_nome.value = "";
    }
  }
  //js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?pesquisa_chave='+document.form1.k31_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
 }
}

function js_mostracgm(chave1,chave2){
 document.form1.k31_matric.value = '';
 document.form1.k31_inscr.value = ''; 
 document.form1.k31_numcgm.value = chave1;
 document.form1.k31_nome.value = chave2;
 func_nome.hide();
 }


function js_mostra(erro, chave){
  document.form1.k31_nome.value = chave;
  if(erro==true){
   document.form1.k31_numcgm.focus();
   document.form1.k31_numcgm.value = '';
  }
 }

// Procura matricula
function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0|1|2';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    if (document.form1.k31_matric.value != '') {
      func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.k31_matric.value+'&funcao_js=parent.js_preenchematricula1';
  } else{
      if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
      document.form1.k31_nome.value = "";
    }
  }
  }
}
 function js_preenchematricula(chave,chave1,chave2){
   document.form1.k31_numcgm.value = '';
   document.form1.k31_inscr.value = '';
   document.form1.k31_matric.value = chave;
   document.form1.k31_nome.value = chave2;
   func_nome.hide();
 }
 function js_preenchematricula1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_matric.focus();
     document.form1.k31_matric.value = '';
   }
 }


//Procura ISSQN
function js_mostrainscricao(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1|2';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    if (document.form1.k31_inscr.value != '') {
      func_nome.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.k31_inscr.value+'&funcao_js=parent.js_preencheinscricao1';
  } else {
    if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
      document.form1.k31_nome.value = "";
    }
  }
  }
}
function js_preencheinscricao(chave,chave1,chave2){
   document.form1.k31_numcgm.value = '';  
   document.form1.k31_matric.value = '';  
   document.form1.k31_inscr.value = chave;
   document.form1.k31_nome.value = chave2;
   func_nome.hide();
 }
function js_preencheinscricao1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_inscr.focus();
     document.form1.k31_inscr.value = '';
   }
}

</script>  

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>