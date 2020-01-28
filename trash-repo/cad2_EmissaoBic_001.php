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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
  function js_adicionar(){
    var retornou = false;
	var items = new Array();
	var F = document.form1;
	if(F.codlogradouro.value != ""){
 	  items.push(["L#"+F.codlogradouro.value]);
	  retornou = true;
	}
	if(F.codbairro.value != ""){
 	  items.push(["B#"+F.codbairro.value]);
	  retornou = true;
    }
	if((F.setor.value != "")||((F.quadra.value != ""))){
	  if((F.setor.value != "")&&((F.quadra.value != ""))){
 	    items.push(["SQ#"+F.setor.value+"#"+F.quadra.value]);
      }else if(F.quadra.value != ""){
 	    alert("O campo quadra n�o pode ser inclu�do sem o campo setor estar preenchido.");
		F.setor.focus();
      }else{
	    items.push(["S#"+F.setor.value]);
	  }
	  retornou = true;
    }
	if(F.codimobiliaria.value != ""){
 	  items.push(["I#"+F.codimobiliaria.value]);
	  retornou = true;
    }
	if(F.codcontribuinte.value != ""){
 	  items.push(["C#"+F.codcontribuinte.value]);
	  retornou = true;
    }
	if(retornou == false){
 	  alert("Deve haver ao menos um campo preenchido para ser adicionado � lista.");
	  return false;
	}else{
	  var qtdItems = F.elements["lista[]"].length;
      for(i=0;i<items.length;i++){
		F.elements["lista[]"].options[qtdItems] = new Option(items[i],items[i]);
        qtdItems++;
	  }
	  F.reset();
	}
  }
  function js_remover(){
    var F = document.form1.elements["lista[]"];
    if (F.selectedIndex == -1){
	  alert("selecione ao menos um item para remov�-lo.");
	} else{
      var qtditems = F.length;
      for(i=0;i<qtditems;i++){
        if ((i != 0)&&(i != 1)){
          if (F.options[i].selected){
            F.options[i] = null;
		  }
		}
	  }
	}
  }
  function js_abreJanelaLogradouro(){
    listaFuncRuas.jan.location.href = 'func_ruas.php?nomeRua=' + document.form1.logradouro.value+"&funcao_js=parent.js_insereCODLogradouro|0";
    listaFuncRuas.show();
	listaFuncRuas.focus();
  }
  function js_abreJanelaBairros(){
    listaFuncbairros.jan.location.href = 'func_bairros.php?nomeBairro=' + document.form1.bairro.value+"&funcao_js=parent.js_insereCODBairro|0";
    listaFuncbairros.show();
	listaFuncbairros.focus();
  }
  function js_abreJanelaImobiliaria(){
    listaFuncimobiliarias.jan.location.href = 'func_imobiliarias.php?nome_imobiliaria=' + document.form1.imobiliaria.value+"&funcao_js=parent.js_insereCODImobiliaria|0";
    listaFuncimobiliarias.show();
	listaFuncimobiliarias.focus();
  }
  function js_abreJanelaContribuinte(){
    listaFuncContribuintes.jan.location.href = 'func_nome.php?nomeDigitadoParaPesquisa=' + document.form1.contribuinte.value+"&funcao_js=parent.js_insereCODContribuinte|0";
    listaFuncContribuintes.show();
	listaFuncContribuintes.focus();
  }
  function js_abreJanelaCODLogradouro(){
    if (document.form1.codlogradouro.value != ""){
      listaFuncRuas.jan.location.href = 'func_ruas.php?codrua=' + document.form1.codlogradouro.value+"&funcao_js=parent.js_insereCODLogradouro|0";
      listaFuncRuas.show();
	  listaFuncRuas.focus();
	}
  }
  function js_abreJanelaCODBairro(){
    if (document.form1.codbairro.value != ""){
      listaFuncbairros.jan.location.href = 'func_bairros.php?codbairro=' + document.form1.codbairro.value+"&funcao_js=parent.js_insereCODBairro|0";
      listaFuncbairros.show();
	  listaFuncbairros.focus();
	}
  }
  function js_abreJanelaCODImobiliaria(){
    if (document.form1.codimobiliaria.value != ""){
      listaFuncimobiliarias.jan.location.href = 'func_imobiliarias.php?codimobiliaria=' + document.form1.codimobiliaria.value+"&funcao_js=parent.js_insereCODImobiliaria|0";
      listaFuncimobiliarias.show();
	  listaFuncimobiliarias.focus();
	}
  }
  function js_abreJanelaCODContribuinte(){
    if (document.form1.codcontribuinte.value != ""){
      listaFuncContribuintes.jan.location.href = 'func_nome.php?codnome=' + document.form1.codcontribuinte.value+"&funcao_js=parent.js_insereCODContribuinte|0";
      listaFuncContribuintes.show();
	  listaFuncContribuintes.focus();
	}
  }
  function js_insereCODLogradouro(valorEscolhido){
	document.form1.codlogradouro.value = valorEscolhido;
    listaFuncRuas.hide();
  }
  function js_insereCODBairro(valorEscolhido){
	document.form1.codbairro.value = valorEscolhido;
    listaFuncbairros.hide();
  }
  function js_insereCODImobiliaria(valorEscolhido){
	document.form1.codimobiliaria.value = valorEscolhido;
    listaFuncimobiliarias.hide();
  }
  function js_insereCODContribuinte(valorEscolhido){
	document.form1.codcontribuinte.value = valorEscolhido;
    listaFuncContribuintes.hide();
  }
</script>
<script>
  function js_AbreJanelaRelatorio(botao) {
    document.form1.origem.value = botao.value;
	var qtdItems = document.form1.elements["lista[]"].length;
	if (qtdItems<=2){
      alert("Adicione ao menos um item � lista para realizar a emiss�o da BIC");
	} else{
	  window.open('','EmissaoBic','width=500,height=500,scrollbars=1,resisable=1');
      for (i=0;i<qtdItems;i++){
        if ((i != 0)&&(i != 1)){
          document.form1.elements["lista[]"].options[i].selected = true;
		}
	  }
      document.form1.submit();
	}
	/*numElems = document.form1.ordem_relatorio.length;
	for (i=0;i<numElems;i++) {
	  if (document.form1.ordem_relatorio[i].checked) itemselecionado = i;
	}
	relatorio = document.form1.opcao_relatorio.value;
	ordem = document.form1.ordem_relatorio[itemselecionado].value;
    window.open('cad2_EmissaoBic_002.php?opcaoRelatorio='+relatorio+'&opcaoOrdem='+ordem+'&<?=db_getsession()?>');*/
	}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
<?
      // Cria a janela para visualizacao da func_ruas
      $listaFuncRuas = new janela("listaFuncRuas","");
	  $listaFuncRuas->posX=1;
	  $listaFuncRuas->posY=20;
	  $listaFuncRuas->largura=785;
	  $listaFuncRuas->altura=400;
	  $listaFuncRuas->titulo="Visualiza��o da lista com logradouros";
	  $listaFuncRuas->iniciarVisivel = false;
	  $listaFuncRuas->mostrar();

      // Cria a janela para visualizacao da func_bairros
      $listaFuncbairros = new janela("listaFuncbairros","");
	  $listaFuncbairros->posX=1;
	  $listaFuncbairros->posY=20;
	  $listaFuncbairros->largura=785;
	  $listaFuncbairros->altura=430;
	  $listaFuncbairros->titulo="Visualiza��o da lista com bairros";
	  $listaFuncbairros->iniciarVisivel = false;
	  $listaFuncbairros->mostrar();

      // Cria a janela para visualizacao da func_imobiliarias
      $listaFuncimobiliarias = new janela("listaFuncimobiliarias","");
	  $listaFuncimobiliarias->posX=1;
	  $listaFuncimobiliarias->posY=20;
	  $listaFuncimobiliarias->largura=785;
	  $listaFuncimobiliarias->altura=430;
	  $listaFuncimobiliarias->titulo="Visualiza��o da lista com imobiliarias";
	  $listaFuncimobiliarias->iniciarVisivel = false;
	  $listaFuncimobiliarias->mostrar();

      // Cria a janela para visualizacao da func_nome
      $listaFuncContribuintes = new janela("listaFuncContribuintes","");
	  $listaFuncContribuintes->posX=1;
	  $listaFuncContribuintes->posY=20;
	  $listaFuncContribuintes->largura=785;
	  $listaFuncContribuintes->altura=430;
	  $listaFuncContribuintes->titulo="Visualiza��o da lista com contribuintes";
	  $listaFuncContribuintes->iniciarVisivel = false;
	  $listaFuncContribuintes->mostrar();
?>
	  <table border="0" width="80%" border="0" cellpadding="0" cellspacing="0">
  <col width="50%" align="left" valign="center">
  <col width="50%" align="left" valign="center">
  <thead>
    <form name="form1" method="post"  target="EmissaoBic"  action="cad2_EmissaoBic_002.php">
	<input type="hidden" name="session" value="<?=db_getsession()?>"
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
      <td colspan="2" align="center"> Emiss�o de BICS </td>
    </tr>
  </thead>
  <tbody>
    <tr valign="top">
      <td nowrap>
        <table border="0" width="100%" border="0" cellpadding="0">
          <tr>
            <td colspan="3">&nbsp;</td>
		  </tr>
          <tr><td><a style="cursor:hand" onclick="js_abreJanelaLogradouro();">&nbsp;Logradouro:&nbsp;</a></td>
		  <td>&nbsp;<input type="text" name="codlogradouro" size=8 maxlength=7 onblur="js_abreJanelaCODLogradouro()">&nbsp;</td>
			<td>&nbsp;<input type="text" name="logradouro" size=30 maxlength=20>
			</td>
		  </tr>
          <tr>
		  <td><a style="cursor:hand" onclick="js_abreJanelaBairros();">&nbsp;Bairro:&nbsp;</a></td>
		  <td>&nbsp;<input type="text" name="codbairro" size=8 maxlength=7 onblur="js_abreJanelaCODBairro()"></td>
			<td>&nbsp;<input type="text" name="bairro" size=30 maxlength=20>
			</td>
		  </tr>
          <tr>
		  <td>&nbsp;Setor:</td>
			<td>&nbsp;<input type="text" name="setor" size=8 maxlength=7></td>
			<td>&nbsp;Quadra:&nbsp;<input type="text" name="quadra" size=5 maxlength=4></td>
		  </tr>
          <tr >
		  <td ><a style="cursor:hand" onclick="js_abreJanelaImobiliaria();">&nbsp;Imobili�ria:&nbsp;</td>            <td>&nbsp;<input type="text" name="codimobiliaria" size=8 maxlength=7 onblur="js_abreJanelaCODImobiliaria()"></td>
            <td>&nbsp;<input type="text" name="imobiliaria" size=30 maxlength=20>
			</td>
		  </tr>
          <tr><td><a style="cursor:hand" onclick="js_abreJanelaContribuinte();">&nbsp;Contribuinte:&nbsp;</td>
		  <td>&nbsp;<input type="text" name="codcontribuinte" size=8 maxlength=7 onblur="js_abreJanelaCODContribuinte()"></td>
			<td>&nbsp;<input type="text" name="contribuinte" size=30 maxlength=20>
			</td>
		  </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
		  </tr>
          <tr>
            <td align="center" colspan="4"><input  onclick="return js_adicionar()" type="button" name="adicionar" value="Adicionar � lista">&nbsp;</td>
		  </tr>
		</table>
		</td>
      <td>
        <table border="0" width="100%" border="0" cellpadding="0">
          <tr>
            <td>&nbsp;</td>
		  </tr>
          <tr>
            <td align="center"><select name="lista[]" multiple size="17" onDblClick="return js_remover()">
                        &nbsp;
                        <option value="titulo">Tipo # C�digo</option>
                        <option value="titulo2">--------------------------</option>
						&nbsp;
                      </select></td>
		  </tr>
          <tr>
            <td>&nbsp;</td>
		  </tr>
          <tr>
            <td align="center"><input type="button" name="remover" value="Remover da Lista" onclick="return js_remover()">&nbsp;</td>
		  </tr>
		</table>
		</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;  </td>
    </tr>
    <tr>
	  <input type="hidden" name="origem" value="">
      <td align="right"><input type="button" name="emitir" value="Emitir BICS" onclick="js_AbreJanelaRelatorio(this)">  </td>
      <td align="left"><input type="button" name="relatorio" value="Emitir Relatorio" onclick="js_AbreJanelaRelatorio(this)">  </td>
    </tr>
	</form>
  </tbody>
</table>

	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>