<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
<title>DBSeller Informática Ltda - Página Inicial</title>
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

  var oParam             = new Object();
      oParam.exec        = 'Anulacao';
      oParam.linhas      = oGridPrescricoes.getNumRows();
      oParam.debitos     = new Array();
      oParam.obs         = $F('k31_obs');
      
  var aLinhas            = oGridPrescricoes.getSelection();

  if ( aLinhas.length == 0 ) {
    alert('Nenhum registro selecionado!');
    return false;
  }

  if ($F('k31_numcgm') == "" && $F('k31_matric') == "" && $F('k31_inscr') == "" ){
     alert('Selecione um filtro!');    
  } else if ($F('k31_obs') == ""){
     alert('Preencha o campo Observações');   
  } else if (confirm('Deseja realmente anular as prescrições informadas?')){

    for (var i = 0; i < aLinhas.length; i++) {
      
      var oDebito     = new Object();
      oDebito.numpre  = aLinhas[i][2];
      oDebito.numpar  = aLinhas[i][3];
      oDebito.receita = aLinhas[i][4];
      oParam.debitos.push(oDebito);
      
    }
  
    var oAjax              = new Ajax.Request('div4_anulaprescricaoparcial.RPC.php',
                                               {method: "post",
                                                parameters:'json='+Object.toJSON(oParam),
                                                onComplete: js_retorno
                                               });   

  }
}

function js_retorno(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    alert("Anulação efetuada com sucesso!");
    js_completaPesquisa();
  } else {
    alert(""+oRetorno.message+"");
  }

} 


</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" onload="js_gridPrescricoes()" >
 
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Anular Prescrição Parcial de Dívida</legend>
    <table class="form-container">
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
        <td colspan="2" >
          <fieldset class="separator">
            <legend><?=@$Lk31_obs?></legend> 
            <? db_textarea('k31_obs',2,70,$Ik31_obs,'','text',$db_opcao,"") ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>        
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick='js_completaPesquisa()' >
  <input name="processar" type="button" id="processar" value="Processar" onclick='js_prescreve();' disabled >       
</form>


<center>
<div id="debug">
</div>
    <fieldset style="display:inline;">
      <legend><b>Lista Prescrições</b></legend>
        <fieldset id="fieldset_filtros" style="width: 350px; display: none;">
          <legend><b>Exercícios: </b></legend>
          <div id="filtro_exercicio"> </div>
          <input type="hidden" value='' id='mantem_cgm' size="3" />
          <input type="hidden" value='' id='mantem_matricula' size="3" />
          <input type="hidden" value='' id='mantem_inscricao' size="3" />
          <div id="botao_pesq_filtros"></div>  
        </fieldset>
      <div id="gridPrescricoes" style="margin-top: 10px;">
      
      </div>  
    </fieldset>

</center>

<script>

function js_gridPrescricoes() {

  oGridPrescricoes = new DBGrid('prescricoes');
  oGridPrescricoes.nameInstance = 'oGridPrescricoes';
  oGridPrescricoes.setCheckbox(1);
  oGridPrescricoes.setCellWidth(new Array('50px', 
                                          '50px', 
                                          '20px',
                                          '60px', 
                                          '180px', 
                                          '10px', 
                                          '60px',  
                                          '60px', 
                                          '60px', 
                                          '60px', 
                                          '60px', 
                                          '50px'));
  
  oGridPrescricoes.setCellAlign(new Array('center', 
                                          'center' , 
                                          'center',
                                          'center', 
                                          'left', 
                                          'left', 
                                          'right',
                                          'right', 
                                          'right', 
                                          'right', 
                                          'right', 
                                          'right'));
  
  oGridPrescricoes.setHeader(new Array('Exercício',
                                       'Numpre', 
                                       'Parcela',
                                       'Receita', 
                                       'Descrição Abreviada', 
                                       'Descrição Receita Tesouraria',  
                                       'VlrHis', 
                                       'VlrCor', 
                                       'VlrJuros', 
                                       'VlrMulta', 
                                       'VlrDesc', 
                                       'VlrTotal'));
                                       
  oGridPrescricoes.aHeaders[6].lDisplayed = false;
  oGridPrescricoes.setHeight(230);
  oGridPrescricoes.show($('gridPrescricoes'));
  oGridPrescricoes.clearAll(true);
        
}

function js_completaPesquisa() {

   var oParam    = new Object();
   oParam.exec   = 'Consulta';
   
   $('processar').disabled = true;

   if ( $('k31_numcgm').value == '' && $('k31_matric').value == '' &&  $('k31_inscr').value == '' ) {
     alert("Filtros não informados!");
     $('mantem_cgm').value       = '';
     $('mantem_matricula').value = '';
     $('mantem_inscricao').value = ''; 
     $('filtro_exercicio').innerHTML = '';  
     $('botao_pesq_filtros').innerHTML ='';
     $('fieldset_filtros').style.display='none';     
     return false;
   }

   oParam.sCgm        = $('k31_numcgm').value;
   oParam.sMatricula  = $('k31_matric').value;
   oParam.sInscricao  = $('k31_inscr').value;
   
   
   var msgDiv    = "Aguarde ...";
   js_divCarregando(msgDiv,'msgBox');
   var oAjax     = new Ajax.Request('div4_anulaprescricaoparcial.RPC.php',
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retornoCompletaPesquisa
                                             });
  

}

/*
 * Monta a Grid com os dados filtrados 
 */
function js_retornoCompletaPesquisa(oAjax) {

  js_removeObj('msgBox');

  var oRetorno = eval("("+oAjax.responseText+")");
  
    if (oRetorno.status == 1) {
      
      oGridPrescricoes.clearAll(true);
      
      if ( oRetorno.dados.length == 0 ) {
        
        alert('Nenhum registro encontrado!');
	      $('mantem_cgm').value       = $('k31_numcgm').value;
	      $('mantem_matricula').value = $('k31_matric').value;
	      $('mantem_inscricao').value = $('k31_inscr').value; 
	      $('filtro_exercicio').innerHTML = '';  
	      $('botao_pesq_filtros').innerHTML ='';
	      $('fieldset_filtros').style.display='none';     
        return false;
      } else {
        $('processar').disabled = false;
      }
      exercicios = "";
      virgula    = "";
      oRetorno.dados.each( 
                    function (oDado, iInd) {       
  
                            aRow = new Array();                                                              
                            aRow[0]  = oDado.v01_exerc;
                            aRow[1]  = oDado.v01_numpre;
                            aRow[2]  = oDado.v01_numpar;
                            aRow[3]  = oDado.k02_codigo;
                            aRow[4]  = oDado.k02_descr;
                            aRow[5]  = oDado.k02_drecei;
                            aRow[6]  = js_formatar(oDado.k30_valor,'f');
                            aRow[7]  = js_formatar(oDado.k30_vlrcorr,'f');
                            aRow[8]  = js_formatar(oDado.k30_vlrjuros,'f');
                            aRow[9]  = js_formatar(oDado.k30_multa,'f');
                            aRow[10]  = js_formatar(oDado.k30_desconto,'f');
                            aRow[11] = js_formatar(oDado.total,'f');
                            
                            oGridPrescricoes.addRow(aRow);
                            exercicios += virgula + aRow[0];
                            virgula = ",";
                            
                       }); 
			oGridPrescricoes.renderRows();
      /*
       * Cria uma Lista, sem repetir os valores dos exercicios,
       * para montar os checkbox com o ano dos exercicios
       */
      aExercicios    = new Array();
      aExercicios    = exercicios.split(",");  
      iTotExercicios = aExercicios.length;
      sDiferente     = "";
      sNovo          = "";
      virgula        = "";
      for (iTotal = 0; iTotal < iTotExercicios; iTotal++) {
        if (aExercicios[iTotal] != sDiferente) {

          sDiferente = aExercicios[iTotal];
          sNovo += virgula + sDiferente;  
          virgula = ",";
        } 
        
      }

    } 
  monta_filtro_exercicio(sNovo);
  $('mantem_cgm').value       = $('k31_numcgm').value;
  $('mantem_matricula').value = $('k31_matric').value;
  $('mantem_inscricao').value = $('k31_inscr').value;
  $('fieldset_filtros').style.display='inline';    
  
}
/*
 * função que pega a lista de anos sem repetição do bloco anterior 
 * e monta o checkbox com os valores
 */
function monta_filtro_exercicio(sLista) {

  aListaExercicio = new Array();
  aListaExercicio = sLista.split(",");
  iListaExercicio = aListaExercicio.length;
  valoratual = "";
  virgula    = "";
  for (iTotalLista = 0; iTotalLista < iListaExercicio; iTotalLista++) {
    
    valoratual += "<input type='checkbox' name='"+aListaExercicio[iTotalLista]+"' id='"+aListaExercicio[iTotalLista]+"' ";
    valoratual += "value='"+aListaExercicio[iTotalLista]+"' />"+aListaExercicio[iTotalLista];
        
    $('filtro_exercicio').innerHTML = valoratual;
    virgula = ",";
  }
  var eBotao  = "<input type='button' onclick='filtra_exercicios ();' ";
      eBotao += "style='margin-top: 5px;' value='Filtrar com os dados selecionados' >";
  $('botao_pesq_filtros').innerHTML = eBotao;
}
/*
 * Função que postará os valores dos exercicios marcados 
 * para uma nova consulta
 */
function filtra_exercicios () {

   var oParametros      = new Object();
   oParametros.exec     = 'Consulta';
   var aListaCheckbox   = $$('#filtro_exercicio [type=checkbox]:checked');
   var aListaExercicios = new Array();
   
   aListaCheckbox.each(
     function ( eCheckbox ) {
       aListaExercicios.push(eCheckbox.value);      
    }
   );
   oParametros.sCgm             = $F('mantem_cgm');       // $F('id_text');  = retorna o valor do campo informado 
   oParametros.sMatricula       = $F('mantem_matricula');
   oParametros.sInscricao       = $F('mantem_inscricao');
   oParametros.sListaExercicios = aListaExercicios.join(',');
 
   var msgDiv    = "Aguarde ...";
   js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxFiltros  = new Ajax.Request('div4_anulaprescricaoparcial.RPC.php',
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaPesquisaFiltros
                                             }); 
}
/*
 * funcao para montar a grid com o retorno dos filtros
 *
 */ 
function js_retornoCompletaPesquisaFiltros(oAjax) {
  var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
      
      oGridPrescricoes.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        js_removeObj('msgBox');
        alert('Nenhum registro encontrado!');
        return false;
      } else {
        $('processar').disabled = false;
      }
      oRetorno.dados.each( 
                    function (oDado, iInd) {       
  
                            aRow = new Array();                                                              
                            aRow[0]  = oDado.v01_exerc;
                            aRow[1]  = oDado.v01_numpre;
                            aRow[2]  = oDado.v01_numpar;
                            aRow[3]  = oDado.k02_codigo;
                            aRow[4]  = oDado.k02_descr;
                            aRow[5]  = oDado.k02_drecei;
                            aRow[6]  = js_formatar(oDado.k30_valor,'f');
                            aRow[7]  = js_formatar(oDado.k30_vlrcorr,'f');
                            aRow[8]  = js_formatar(oDado.k30_vlrjuros,'f');
                            aRow[9]  = js_formatar(oDado.k30_multa,'f');
                            aRow[10]  = js_formatar(oDado.k30_desconto,'f');
                            aRow[11] = js_formatar(oDado.total,'f');
                            oGridPrescricoes.addRow(aRow);
                            oGridPrescricoes.renderRows();
                       }); 
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
   var sUrl = 'func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome';
 } else {
  var sUrl = 'func_cgm.php?pesquisa_chave='+document.form1.k31_numcgm.value+'&funcao_js=parent.js_mostra';
 }
  
 js_OpenJanelaIframe('','db_iframe_cgm',sUrl,'Pesquisa',mostra);
}

function js_mostracgm(chave1,chave2){
 document.form1.k31_matric.value = '';
 document.form1.k31_inscr.value = ''; 
 document.form1.k31_numcgm.value = chave1;
 document.form1.k31_nome.value = chave2;
 db_iframe_cgm.hide();
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
    var sUrl = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0|1|2';
  }else{
    var sUrl = 'func_iptubase.php?pesquisa_chave='+document.form1.k31_matric.value+'&funcao_js=parent.js_preenchematricula1';
  } 
  
  js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',mostra);

}
 function js_preenchematricula(chave,chave1,chave2){
   document.form1.k31_numcgm.value = '';
   document.form1.k31_inscr.value = '';
   document.form1.k31_matric.value = chave;
   document.form1.k31_nome.value = chave2;
   db_iframe_matricula.hide();
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
    var sUrl = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1|2';
  }else{
    sUrl = 'func_issbase.php?pesquisa_chave='+document.form1.k31_inscr.value+'&funcao_js=parent.js_preencheinscricao1';
  }
  js_OpenJanelaIframe('','db_iframe_inscricao',sUrl,'Pesquisa',mostra);
  
}
function js_preencheinscricao(chave,chave1,chave2){
   document.form1.k31_numcgm.value = '';  
   document.form1.k31_matric.value = '';  
   document.form1.k31_inscr.value = chave;
   document.form1.k31_nome.value = chave2;
   db_iframe_inscricao.hide();
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
<script>

$("k31_numcgm").addClassName("field-size2");
$("k31_matric").addClassName("field-size2");
$("k31_inscr").addClassName("field-size2");
$("k31_nome").addClassName("field-size9");

</script>