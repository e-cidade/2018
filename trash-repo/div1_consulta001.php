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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("classes/db_divida_classe.php");
$cldivida = new cl_divida;
$clrotulocampo = new rotulocampo;
$clrotulocampo->label("z01_nome");
$clrotulocampo->label("inicial");
$clrotulocampo->label("j01_matric");
$clrotulocampo->label("v01_coddiv");
$clrotulocampo->label("v07_parcel");
$clrotulocampo->label("v13_certid");
$clrotulocampo->label("q02_inscr");
 ?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
	function js_pesquisar(){
		if (document.getElementById("z01_nome").value != ""){
			listaFuncNome.jan.location.href = 'func_nome.php?nomeDigitadoParaPesquisa=' + document.getElementById("z01_nome").value+"&funcao_js=parent.js_abreJaneladiv2|0";
			listaFuncNome.mostraMsg();
			listaFuncNome.show();
			listaFuncNome.focus();
			return true;
		}else if (document.getElementById("j01_matric").value != ""){
			listaDividas.jan.location.href = 'div1_consulta002.php?pesquisa_Matricula=' + document.getElementById("j01_matric").value+"&funcao_js=parent.js_abreJanelaDadosDivida|0";
			listaDividas.mostraMsg();
			listaDividas.show();
			listaDividas.focus();
			return true;
		}else if (document.getElementById("q02_inscr").value != ""){
			listaDividas.jan.location.href = 'div1_consulta002.php?pesquisa_Inscricao=' + document.getElementById("q02_inscr").value+"&funcao_js=parent.js_abreJanelaDadosDivida|0";
			listaDividas.mostraMsg();
			listaDividas.show();
			listaDividas.focus();
			return true;
		}else if (document.getElementById("v01_coddiv").value != ""){
			dadosDivida.jan.location.href = 'div1_consulta003.php?codDiv=' + document.getElementById("v01_coddiv").value;
			dadosDivida.mostraMsg();
			dadosDivida.show();
			dadosDivida.focus();
			return true;
		}else if (document.getElementById("v13_certid").value != ""){
			dadosCertidao.jan.location.href = 'div1_consulta003.php?codCert=' + document.getElementById("v13_certid").value;
			dadosCertidao.mostraMsg();
			dadosCertidao.show();
			dadosCertidao.focus();
			return true;
		}else if (document.getElementById("v07_parcel").value != ""){
			dadosTermo.jan.location.href = 'div1_consulta003.php?codTerm=' + document.getElementById("v07_parcel").value;
			dadosTermo.mostraMsg();
			dadosTermo.show();
			dadosTermo.focus();
			return true;
		}else if (document.getElementById("inicial").value != ""){
			dadosInicial.jan.location.href = 'div1_consulta003.php?codInicial=' + document.getElementById("inicial").value;
			dadosInicial.mostraMsg();
			dadosInicial.show();
			dadosInicial.focus();
			return true;
		}
		alert("Preencha ao menos um campo para sua pesquisa.");
		return false;
	}
	function js_abreJaneladiv2(valor){
		listaDividas.jan.location.href = 'div1_consulta002.php?pesquisa_CGM=' + valor+"&funcao_js=parent.js_abreJanelaDadosDivida|0";
		listaDividas.mostraMsg();
		listaDividas.show();
		listaDividas.focus();
	}
	function js_abreJanelaDadosDivida(codDiv){
		dadosDivida.jan.location.href = 'div1_consulta003.php?codDiv=' + codDiv;
		dadosDivida.mostraMsg();
		dadosDivida.show();
		dadosDivida.focus();
	}
	function js_abreJanelaDadosTermo(codTerm){
		dadosTermo.jan.location.href = 'div1_consulta003.php?codTerm=' + codTerm;
		dadosTermo.mostraMsg();
		dadosTermo.show();
		dadosTermo.focus();
	}
	function js_abreJanelaDadosInicial(codInic){
		dadosInicial.jan.location.href = 'div1_consulta003.php?codInicial=' + codInic;
		dadosInicial.mostraMsg();
		dadosInicial.show();
		dadosInicial.focus();
	}
	function js_abreJanelaTextoCertidao(cod){
		textoTermoOUCertidao.jan.location.href = 'div1_consulta002.php?textoCert=' + cod;
		textoTermoOUCertidao.mostraMsg();
		textoTermoOUCertidao.show();
		textoTermoOUCertidao.focus();
	}
	function js_abreJanelaTextoTermo(cod){
		textoTermoOUCertidao.jan.location.href = 'div1_consulta002.php?textoTerm=' + cod;
		textoTermoOUCertidao.mostraMsg();
		textoTermoOUCertidao.show();
		textoTermoOUCertidao.focus();
	}
	function js_fechaJanela(){
		listaDividas.hide();
	}
	function js_pesquisaMatricula(lMostra) {
	  var sQueryString = 'func_iptubase.php?';
	  if(lMostra) {
		  sQueryString += 'funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome';
	  } 
	  js_OpenJanelaIframe('top.corpo', 'db_iframe_iptubase', sQueryString, 'Pesquisa', lMostra, 20);
	}
	function js_mostraMatricula(iMatricula, sNome) {
		document.form1.j01_matric.value = iMatricula;
		db_iframe_iptubase.hide();
	}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="a=1" >


<?
	$listaFuncNome = new janela("listaFuncNome","");
	$listaFuncNome->posX=1;
	$listaFuncNome->posY=20;
	$listaFuncNome->largura=775;
	$listaFuncNome->altura=430;
	$listaFuncNome->titulo="Visualização da lista com contribuintes";
	$listaFuncNome->iniciarVisivel = false;
	$listaFuncNome->mostrar();

	$dadosCertidao = new janela("dadosCertidao","");
	$dadosCertidao->posX=1;
	$dadosCertidao->posY=20;
	$dadosCertidao->largura=775;
	$dadosCertidao->altura=430;
	$dadosCertidao->titulo="Visualização dos dados da certidão";
	$dadosCertidao->iniciarVisivel = false;
	$dadosCertidao->mostrar();

	$dadosInicial = new janela("dadosInicial","");
	$dadosInicial->posX=1;
	$dadosInicial->posY=20;
	$dadosInicial->largura=775;
	$dadosInicial->altura=430;
	$dadosInicial->titulo="Visualização dos dados da parcela inicial";
	$dadosInicial->iniciarVisivel = false;
	$dadosInicial->mostrar();

	$dadosTermo = new janela("dadosTermo","");
	$dadosTermo->posX=1;
	$dadosTermo->posY=20;
	$dadosTermo->largura=775;
	$dadosTermo->altura=430;
	$dadosTermo->titulo="Visualização dos dados da certidão";
	$dadosTermo->iniciarVisivel = false;
	$dadosTermo->mostrar();

	$textoTermoOUCertidao = new janela("textoTermoOUCertidao","");
	$textoTermoOUCertidao->posX=1;
	$textoTermoOUCertidao->posY=20;
	$textoTermoOUCertidao->largura=775;
	$textoTermoOUCertidao->altura=430;
	$textoTermoOUCertidao->titulo="Visualização do Texto";
	$textoTermoOUCertidao->iniciarVisivel = false;
	$textoTermoOUCertidao->mostrar();

	$listaDividas = new janela("listaDividas","");
	$listaDividas->posX=1;
	$listaDividas->posY=20;
	$listaDividas->largura=775;
	$listaDividas->altura=430;
	$listaDividas->titulo="Visualização da lista de dívidas ativas";
	$listaDividas->iniciarVisivel = false;
	$listaDividas->mostrar();

	$dadosDivida = new janela("dadosDivida","");
	$dadosDivida->posX=1;
	$dadosDivida->posY=20;
	$dadosDivida->largura=775;
	$dadosDivida->altura=430;
	$dadosDivida->titulo="Visualização dos dados da dívida";
	$dadosDivida->iniciarVisivel = false;
	$dadosDivida->mostrar();
?>
<form class="container" name="form1" id="form1" >
<fieldset>
<legend>Consulta aos Dados da Dívida Ativa</legend>
		<table class="form-container">
			<tr>
				<td title="<?=$Tz01_nome?>"><?=$Lz01_nome?>
				</td>
				<td><?
				db_input("z01_nome",40,$Iz01_nome,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tj01_matric?>">
				<?
					db_ancora($Lj01_matric, 'js_pesquisaMatricula(true)', 1);
			  ?>
				</td>
				<td><?
				db_input("j01_matric",10,$Ij01_matric,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tq02_inscr?>"><?=$Lq02_inscr?>
				</td>
				<td><?
				db_input("q02_inscr",10,$Iq02_inscr,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tv01_coddiv?>"><?=$Lv01_coddiv?>
				</td>
				<td><?
				db_input("v01_coddiv",10,$Iv01_coddiv,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tv13_certid?>"><?=$Lv13_certid?>
				</td>
				<td><?
				db_input("v13_certid",10,$Iv13_certid,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tv07_parcel?>"><?=$Lv07_parcel?>
				</td>
				<td><?
				db_input("v07_parcel",10,$Iv07_parcel,true,"text",4)
				?>
				</td>
			</tr>
			<tr>
				<td title="<?=$Tinicial?>"><?=$Linicial?>
				</td>
				<td><?
				db_input("inicial",10,$Iinicial,true,"text",4)
				?>
				</td>
			</tr>
		</table>
      </fieldset>
      <input type="button" name="pesquisar" value="Pesquisar" id="pesquisar" onclick="return js_pesquisar()">

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</form>
</body>
</html>
<script>

$("z01_nome").addClassName("field-size7");
$("j01_matric").addClassName("field-size2");
$("q02_inscr").addClassName("field-size2");
$("v01_coddiv").addClassName("field-size2");
$("v13_certid").addClassName("field-size2");
$("v07_parcel").addClassName("field-size2");
$("inicial").addClassName("field-size2");

</script>