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
include("classes/db_saniatividade_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sanitario_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsaniatividade = new cl_saniatividade;
$db_botao = false;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsanitario = new cl_sanitario;
$clsaniatividade->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y80_numcgm");
$clrotulo->label("q03_descr");
if(isset($HTTP_POST_VARS["db_opcao"]) && $db_opcao == "Alterar"){
    $HTTP_POST_VARS["y80_dtbaixa_dia"] = "";
    $HTTP_POST_VARS["y80_dtbaixa_mes"] = "";
    $HTTP_POST_VARS["y80_dtbaixa_ano"] = "";
    db_inicio_transacao();
      $clsanitario->alterar($y80_codsani);
      $clsanitario->erro(true,true);
    db_fim_transacao();
}else{
  echo "<script>js_pesquisay80_codsani(true);</script>";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty80_codsani?>">
       <?
       db_ancora(@$Ly80_codsani,"js_pesquisay80_codsani(true);",1);
       ?>
    </td>
    <td> 
<?
db_input('y80_codsani',10,$Iy80_codsani,true,'text',1," onchange='js_pesquisay80_codsani(false);'")
?>
       <?
db_input('z01_nome',35,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?@$Ty83_dtfim?>">
       <?@$Ly83_dtfim?>
    </td>
    <td> 
<?
/*if(empty($q07_datafim_dia)){
  $y83_dtfim_dia = date("d",db_getsession("DB_datausu"));
  $y83_dtfim_mes = date("m",db_getsession("DB_datausu"));
  $y83_dtfim_ano = date("Y",db_getsession("DB_datausu"));
} */
//db_inputdata('y83_dtfim',@$y83_dtfim_dia,@$y83_dtfim_mes,@$y83_dtfim_ano,true,'text',1,"")
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
<input name="db_opcao" type="submit" value="Alterar" <?=(!isset($y80_codsani) || $y80_codsani == ""?'disabled':'')?>>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisay80_codsani(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_mostrasanitario1|y80_codsani|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y80_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);
  }
}
function js_mostrasanitario(chave,erro){
  document.form1.z01_nome.value = erro;
  if(erro==true){ 
    document.form1.y80_codsani.focus(); 
    document.form1.y80_codsani.value = ''; 
  }
  document.form1.submit();
}
function js_mostrasanitario1(chave1,chave2){
  document.form1.y80_codsani.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_sanitario.hide();
  document.form1.submit();
}
</script>
    </center>
	</td>
  </tr>
</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>