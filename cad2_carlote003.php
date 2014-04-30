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
include("libs/db_usuariosonline.php");
include("classes/db_caracter_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sanitario_classe.php");
include("classes/db_iptucalh_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcaracter = new cl_caracter;
$cliptucalh = new cl_iptucalh;
$cliptucalh->rotulo->label();
$cliframe_seleciona = new cl_iframe_seleciona;
$clcaracter->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j39_dtlan");
$clrotulo->label("j32_grupo");
$clrotulo->label("j32_descr");
echo "<script>parent.iframe_g2.location.href = 'cad2_carlote004.php'</script>";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<form name="form1" method="post" action="cad2_carlote002.php" target="rel">
<center>
<table border="0">
  <tr>
    <td align="top" colspan="3">
   <?
      $cliframe_seleciona->campos  = "j31_codigo,j31_descr,j32_descr";
      $cliframe_seleciona->legenda="QUE CONTENHAM ESTAS CARACTERÍSTICAS DE CONSTRUÇÃO";
      $cliframe_seleciona->sql=$clcaracter->sql_query("","*","j32_descr"," j32_tipo = 'L'");
      $cliframe_seleciona->iframe_height ="150";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="caracteristicas";
      $cliframe_seleciona->chaves ="j31_codigo,j31_descr";
      $cliframe_seleciona->dbscript ="onClick='parent.js_nome(this)'";
      $cliframe_seleciona->marcador = true;
      $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
   ?>
   </td>
   <script>
   var x = false;
   function imprime(){
			 //alert(document.form1.setor.value);
			 //return false;
			 j14_comruas = "";
			 vir = "";
			 validacao = js_validaCheck();
			 if(!validacao){
				 return false;
			 }
			 for(y=0;y<parent.iframe_g5.document.getElementById('ruas').length;y++){
				 j14_comruas += vir + parent.iframe_g5.document.getElementById('ruas').options[y].value;
				 vir = ",";
			 }
			 document.form1.ruas.value = j14_comruas;
			 jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
			 jan.moveTo(0,0);
			 return js_gera_chaves();
			 return false;
			 //parent.iframe_g1.location.href='cad2_iptuconstr003.php';
   }
   function js_nome(obj){
     if(obj.checked == true){
       eval('parent.iframe_g2.ncaracteristicas.document.form1.'+obj.name+'.disabled = true');
     }else{
       eval('parent.iframe_g2.ncaracteristicas.document.form1.'+obj.name+'.disabled = false');
     }
   }
   </script>
 </tr>
  <tr>
    <td align="left" nowrap title="<?=@$Tj32_grupo?>">
       <?
       db_ancora($Lj32_grupo,"js_pesquisagrupo(true)",1);
       ?>
     </td>
     <td>
<?
db_input('j32_grupo',4,$Ij32_grupo,true,'text',1,"onChange='js_pesquisagrupo(false)'")
?>
<?
db_input('j32_descr',40,$Ij32_descr,true,'text',1,"")
?>
 <input type='hidden' name='chaves_caract'>
 <input type='hidden' name='quadra'>
 <input type='hidden' name='setor'>
 <input type='hidden' name='sosetor'>
 <input type='hidden' name='ruas'>
 <input type='hidden' name='temruas'>
    <td>
      <table>
      <tr>
    <td>
      <fieldset>
      <legend><strong>Modo: </strong></legend>
      <input type='radio' id='asc' name='order' checked value='asc'>
      <strong><label for="asc">Ascendente</label></strong> 
      <input type='radio' id='desc' name='order' value='desc'>
      <strong><label for="desc">Descendente</label></strong> 
      </fieldset>
    </td>
    </tr>
    </table>
  </td>  
  </tr>
  <tr nowrap>
    <td width="130" nowrap align="left" title="">
      <strong>Área do lote entre:</strong> 
    </td>
    <td nowrap colspan="2">
        <input type="text" name="loteini" size="6">
      <strong>&nbsp;e&nbsp;</strong>
        <input type="text" name="lotefim" size="6">
      <strong>&nbsp;m&sup2;</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <strong>Testada entre:</strong> 
        <input type="text" name="testini" size="6">
      <strong>&nbsp;e&nbsp;</strong>
        <input type="text" name="testfim" size="6">
      <strong>&nbsp;m&sup2;</strong>
    </td>
  </tr>  
  <tr nowrap>
    <td width="130" nowrap align="left" title="">
      <strong>Área construída entre:</strong> 
    </td>
    <td nowrap colspan="2">
        <input type="text" name="cini" size="6">
      <strong>&nbsp;e&nbsp;</strong>
        <input type="text" name="cfim" size="6">
      <strong>&nbsp;m&sup2;</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <strong>Pontuação entre:</strong> 
        <input type="text" name="pontini" size="6">
      <strong>&nbsp;e&nbsp;</strong>
        <input type="text" name="pontfim" size="6">
      <strong>&nbsp;</strong>
    </td>
  </tr>
    <tr>
    <td nowrap width="50" colspan=2> 
      <fieldset>
      <legend><strong>Considerar lotes : </strong></legend>
      <select name="comlotes">
        <option value="todos">Todos</option>
        <option value="so">Somente lotes com matrícula baixada</option>
        <option value="sem">Não considerar lotes com matrícula baixadas</option>
      </select>
      </fieldset>
    </td>
    <td nowrap width="50"> 
    </td>
    <fieldset>
  <tr>
    <td colspan="4">
    <table border="0">
    <tr>
    <td nowrap width="50"> 
      <fieldset>
      <legend><strong>Ordem: </strong></legend>
      <select name="ordem">
        <option value="idbql">Código do lote</option>
        <option value="area">Área</option>
        <option value="areacons">Área construída</option>
        <option value="zona">Zona fiscal</option>
        <option value="lograd">Logradouro</option>
        <option value="sql">Setor/Quadra/Lote</option>
      </select>
      </fieldset>
    </td>
    <td nowrap width="50"> 
    </td>
    <fieldset>
    <legend><strong>Valores a serem mostrados</strong></legend>
    <?
    //rotina q monta os checkbox na tela para selecionar as colunas de acordo com o historico da tabela iptucalh
    $rsResult=$cliptucalh->sql_record($cliptucalh->sql_query_file(null,"*",""));
    $numrows=$cliptucalh->numrows;
    if ($numrows != 0){
        db_fieldsmemory($rsResult,0);
    }else{
        db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
        exit;
    }
    for($x=0;$x<$numrows;$x++){ 
      db_fieldsmemory($rsResult,$x);
      echo "<input type='checkbox' checked name='check".$x."'value='".$j17_codhis."'><strong>".ucfirst(strtolower($j17_descr))."</strong>";
    }
    ?>  
    </fieldset>
    <td nowrap width="50"> 
    </td>
    </td>
    <td nowrap width="50">
        <fieldset>
        <legend><strong>Tipo: </strong></legend>
        <select name="resumido">
          <option value="t">Resumido</option>
          <option value="f" selected>Completo</option>
        </select>
        </fieldset>
   </td>
  </tr>
  </table>
  </td>
 <tr>
   <td align="center" colspan="3">
     <input type="submit" name="relatorio1" value="Relatório" onClick="return imprime();"> 
   </td>
 </tr>
  </table>
  </center>
</form>
<script>
function js_validaCheck(){
	frm = document.form1;	
	passa = false;
	for(ii=0; ii<frm.elements.length; ii++){
		if(frm.elements[ii].name){
	  	if(frm.elements[ii].name.substr(0,5) == 'check' && frm.elements[ii].checked == true){
		 	  passa = true;
		  }
		}
	}
	if(passa){
		return true;
	}else{
		alert('Selecione pelo menos um tipo de valor');
		return false;
	}
}
function js_pesquisagrupo(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_cargrup_rel.php?grupo=l&funcao_js=parent.js_mostracargrup1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_cargrup_rel.php?grupo=l&pesquisa_chave='+document.form1.j32_grupo.value+'&funcao_js=parent.js_mostracargrup';
  }
}
function js_mostracargrup(chave,erro){
  document.form1.j32_descr.value = chave; 
  if(erro==true){ 
    document.form1.j32_grupo.focus(); 
    document.form1.j32_grupo.value = ''; 
  }
}
function js_mostracargrup1(chave1,chave2){
  document.form1.j32_grupo.value = chave1;
  document.form1.j32_descr.value = chave2;
  db_iframe.hide();
}
</script>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=1;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>