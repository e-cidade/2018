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
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include ("libs/db_app.utils.php");

$clrotulo = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

// Verifica se Sistema de Agua esta em Uso
db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

if (isset($db21_usasisagua) && $db21_usasisagua != '') {
  
  $db21_usasisagua = ($db21_usasisagua == 't');
  
  if ($db21_usasisagua == true) {
    
    $j18_nomefunc = "func_aguabase.php";
    
  } else {
    
    $j18_nomefunc = "func_iptubase.php";
    
  }
  
} else {
  
  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 

  db_app::load('datagrid.widget.js');
  db_app::load('prototype.js');
  db_app::load('strings.js');
  db_app::load('scripts.js');
  db_app::load('widgets/windowAux.widget.js');
  
  db_app::load('estilos.css');
  db_app::load('grid.style.css');
?>

</head>
<body bgcolor=#CCCCCC onload="document.form1.j01_matric.focus()" >

<form name="form1" onsubmit="return validaForm()">

<table border="0" align="center" style="margin-top: 50px;">

  <tr> 
    <td title="<?=$Tz01_nome?>"> 
     <?
        db_ancora($Lz01_nome, 'js_mostranomes(true);', 4);
     ?>
    </td>
    <td> 
    <?
        db_input("z01_numcgm", 10, $Iz01_numcgm, true, 'text', 4, "onfocus=\"apagaInputs()\" onchange='js_mostranomes(false);'");
 
        db_input("z01_nome", 30, $Iz01_nome, true, 'text', 3);
    ?>
    </td>
    <td>
    <?
        $aRegraCGM = array('1'=>'Somente CGM', '2'=>'CGM Geral');

        db_select('regra_cgm', $aRegraCGM, true, 1);
    ?>  
    </td>
  </tr>
  
  <tr> 
    <td title="<?=$Tj01_matric?>"> 
    <?
      db_ancora($Lj01_matric, "js_mostramatricula(true,'$j18_nomefunc');", 2);
    ?>
    </td>
    <td> 
    <?
      db_input("j01_matric", 10, $Ij01_matric, true, 'text', 1, "onfocus=\"apagaInputs()\"  onchange=\"js_mostramatricula(false,'$j18_nomefunc')\"");
    ?>
    </td>
  </tr>

  <tr> 
    <td>     
    <?
      db_ancora($Lq02_inscr,' js_inscr(true); ',1);
    ?>
    </td>
    <td> 
    <?
      db_input('q02_inscr', 10, $Iq02_inscr,true,'text',1," onfocus=\"apagaInputs()\" onchange='js_inscr(false)'");
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <input type="button" name="consultar" value="Consultar" id="consultar" onclick="js_consultar_exercicio();"/>
    </td>
  </tr>
  
</table>

</form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="text/javascript">

document.form1.consultar.disabled = true;

function js_imprimir_declaracao(){

  var checkbox     = document.getElementsByName('exerc');
  var virgula      = '';
  var sQueryString = '';

  var iExercSel = '';
  var iMatric   = document.form1.j01_matric.value;
  var iInscr    = document.form1.q02_inscr.value;
  var iCGM      = document.form1.z01_numcgm.value;
  var sRegraCGM = document.form1.regra_cgm.value;
  
  if(checkbox.length > 0) {
	  for(var i = 0; i < checkbox.length; i++)
	  {
		  if(checkbox[i].checked == true) {
		    iExercSel += virgula+checkbox[i].value;
		    virgula = ',';
		  }
	  }
  } else {
	  alert('Nenhum exercício informado.');
	  return false;
  }

  sQueryString  = '?exercicios='+iExercSel;
  sQueryString += '&j01_matric='+iMatric;
  sQueryString += '&q02_inscr='+iInscr;
  sQueryString += '&z01_numcgm='+iCGM;
  sQueryString += '&regra_cgm='+sRegraCGM;

  jan = window.open('arr4_declquitacaoind002.php'+sQueryString, '', 'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');
  jan.moveTo(0,0);
  
}

function js_consultar_exercicio() {
	
	  var cgm      = $F('z01_numcgm');
	  var matric   = $F('j01_matric');
	  var inscr    = $F('q02_inscr');
	  var regracgm = $F('regra_cgm');
	  var codigo   = "";

	 if(cgm != '') {
		 origem = '1';
		 codigo = cgm;
	 }else if(matric != '') {
		 origem = '2';
		 codigo = matric;
	 }else if(inscr != '') {
		 origem = '3';
		 codigo = inscr;
	 }

	 if(codigo == '') {
		 alert('Nenhuma origem informada!');
		 return false;
	 }
	 
	 js_pesquisa_exercicio(origem, codigo, regracgm);
}

function js_pesquisa_exercicio(origem, codigo, regracgm) {

	var iOrigem   = origem;
	var iCodigo   = codigo;
	var iRegraCGM = regracgm;
	var oParam    = new Object();

	oParam.exec     = 'listaExercicios';
	oParam.origem   = iOrigem;
	oParam.codigo   = iCodigo;
	oParam.regracgm = iRegraCGM;

	js_divCarregando('Pesquisando, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request('arr4_declquitacao.RPC.php',
			                        {method: 'POST',
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: js_retorna_exerc
                              });
	
}

function js_retorna_exerc(oAjax) {

	js_removeObj('msgbox');
	
	var oRetorno        = eval("("+oAjax.responseText+")");
	var virgula         = '';

	if (oRetorno.status == 1) {
		
		js_monta_janela();
		js_init_table();
		
		oDataGrid.clearAll(true);

		if(oRetorno.exerc.length > 0) {
			for (var i = 0; i < oRetorno.exerc.length; i++) {
				
				with(oRetorno.exerc[i]) {
					aRow    = new Array();
					aRow[0] = '<input type="checkbox" name="exerc" id="exerc" value="'+exerc+'" />';
					aRow[1] = exerc;
					oDataGrid.addRow(aRow);
				}
			}
			oDataGrid.renderRows();
		}
		
	} else {
		
		alert(oRetorno.message);
		
	}

}

function js_init_table() {
	
  oDataGrid = new DBGrid('gridExerc');
  
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCellAlign(new Array('center', 'center'));
  oDataGrid.setCellWidth(new Array('20%', '80%'));
  oDataGrid.setHeader(new Array('M', 'Exercício'));
  oDataGrid.setHeight(150);
  oDataGrid.show($('grid'));
  
}

function js_monta_janela() {
	
	var sContent = "";

	sContent += '<div style="margin: 10px auto; text-align: center;">';
	sContent += '<div id="msgtopo" style="margin:0 auto; width: 250px; font-size:13px; font-weight: bold; background-color: #FFF;">'; 
	sContent += 'Selecione os exercícios abaixo que deseja imprimir a declaração de quitação.';
	sContent += '</div>';
	sContent += '<div style="width:250px; margin:10px auto;">';
	sContent += '<fieldset>';
	sContent +=	'<div id="grid"></div>';
	sContent += '</fieldset>';
	sContent += '<div style="margin: 10px auto;">';
	sContent += '<input type="button" name="imprimir" value="Imprimir" onclick="js_imprimir_declaracao()"/>&nbsp;&nbsp;';
	sContent += '<input type="button" name="cancelar" value="Cancelar" onclick="js_fechar_janela()"/>';
	sContent += '</div>';
	sContent += '</div>';
	sContent += '</div>';
	
	windowExerc  = new windowAux('wndexerc', 'Lista de Exercícios', 260, 380);
	windowExerc.setContent(sContent);


  var w = ((screen.width - 260) / 2);
  var h = ((screen.height / 2) - 380);
	
	windowExerc.show(h, w);
	$('window'+windowExerc.idWindow+'_btnclose').observe("click",js_fechar_janela);

}


function js_fechar_janela(){
	
  windowExerc.destroy();
  
} 

function apagaInputs(){
	
  document.form1.j01_matric.value = "";
  document.form1.q02_inscr.value  = "";   
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value   = "";
  
}

function js_mostranomes(mostra){
	
  document.form1.j01_matric.value = "";
  document.form1.q02_inscr.value  = "";
  
  if(mostra==true){
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_preenche|0|1','Pesquisa',true);
    
  }else{
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa',false);

  }
  
}

function js_preenche(chave,chave1){  
	
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value   = chave1;
  db_iframe_nomes.hide();
  document.form1.consultar.disabled = false;
  
}

function js_preenche1(chave,chave1){
  
  document.form1.j01_matric.value = "";
  document.form1.q02_inscr.value  = "";  
  document.form1.z01_nome.value   = chave1;
  
  if(chave==true){
	  
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value = chave1;
    document.form1.consultar.disabled = true;
    
  }else {
	  
    document.form1.consultar.disabled = false;
    
  }
  
  if(document.form1.z01_numcgm.value == ''){
	  
    document.form1.z01_nome.value = '';
    document.form1.consultar.disabled = true;
    
  }
  
}

function js_mostramatricula(mostra, nome_func){
	
  document.form1.z01_numcgm.value = "";
  document.form1.q02_inscr.value  = "";
    
  if(mostra==true){
	  
    if(nome_func != "func_iptubase.php") {
        
      js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula|0|1','Pesquisa',true);
      
    } else {
        
      js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula3|0|1|2','Pesquisa',true);
        
    }
    
  }else {
	  
    js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula2','Pesquisa',false);
    
  }
  
}

function js_preenchematricula3(chave,chave1,chave2){

	document.form1.j01_matric.value = chave;
	document.form1.z01_nome.value   = chave2;
	db_iframe_matric.hide();
  document.form1.consultar.disabled = false;
    
}

function js_preenchematricula(chave,chave1){
  
  document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value   = chave1;
  db_iframe_matric.hide();
  document.form1.consultar.disabled = false;
  
}

function js_preenchematricula2(chave,chave1){
  
  if(chave1 == false) {
	  
    document.form1.z01_nome.value = chave;
    db_iframe_matric.hide();
    document.form1.consultar.disabled = false;
    
  }else {
	  
    document.form1.j01_matric.value = "";
    document.form1.z01_nome.value   = chave;
    db_iframe_matric.hide();
    document.form1.consultar.disabled = true;
    
  }
  
  if(document.form1.j01_matric.value == ''){
	  
    document.form1.z01_nome.value   = '';
    document.form1.consultar.disabled = true;
    
  }  
  
}

function js_inscr(mostra){
	
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = "";
  
  if(mostra==true){
	  
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
    
  }else{
	  
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    
  }
  
}

function js_mostra(chave1,chave2,baixa){
	
  if (baixa!=""){
	  
    document.form1.q02_inscr.value = "";
    document.form1.z01_nome.value  = "";
    db_iframe.hide();
    alert("Inscrição já  Baixada");
    
  }else{
	  
    if(chave2 != false) {
           
      document.form1.q02_inscr.value = chave1;
      document.form1.z01_nome.value  = chave2;
      db_iframe.hide();
      document.form1.consultar.disabled = false;
      
    }else {
        
      document.form1.z01_nome.value  = chave1;
      db_iframe.hide();
      document.form1.consultar.disabled = false;
      
    }
    
  } 
   
  if(document.form1.q02_inscr.value == '') {
	  
    document.form1.z01_nome.value   = '';
    document.form1.consultar.disabled = true;
    
  } 
   
}

function validaForm() {
	
  var matricula = document.form1.j01_matric;
  var numerocgm = document.form1.z01_numcgm;
  var inscricao = document.form1.q02_inscr;
  var nome      = document.form1.z01_nome;
  
  if((matricula.value == "") &&
     (numerocgm.value == "") &&
     (inscricao.value == "") || 
     (nome.value      == "") &&
     (nome.value      == 'CHAVE('+matricula.value+') NÃO ENCONTRADO')||
     (nome.value      == 'CHAVE('+numerocgm.value+') NÃO ENCONTRADO')||
     (nome.value      == 'CHAVE('+inscricao.value+') NÃO ENCONTRADO')||
     (nome.value      == 'CÓDIGO ('+matricula.value+') NÃO ENCONTRADO')||
     (nome.value      == 'CÓDIGO ('+numerocgm.value+') NÃO ENCONTRADO')||
     (nome.value      == 'CÓDIGO ('+inscricao.value+') NÃO ENCONTRADO')) {
	     
     alert('Valor de pesquisa invalido.');  
     document.form1.consultar.disabled = true;
     return false;
     
  }
  
  var conta = 0;
  
  if(matricula.value != "") conta++;
  
  if(numerocgm.value != "") conta++;
  
  if(inscricao.value != "") conta++;

  if(conta > 1) {
	  
    alert('Nenhuma origem informada!');
    return false;
    
  }
  
}
</script>

</body>
</html>