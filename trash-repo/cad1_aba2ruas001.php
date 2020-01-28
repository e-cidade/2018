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
include("classes/db_ruas_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_logradcep_classe.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$cllogradcep = new cl_logradcep;
$aux = new cl_arquivo_auxiliar;

$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("cp06_codlogradouro");
$clrotulo->label("cp06_logradouro");


$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $logs_split = split(',',$logs);
  for($i=0;$i<count($logs_split);$i++){
     $cllogradcep->incluir($j14_codigo,$logs_split[$i]); 
  }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
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
	     <td  align="left" nowrap title="<?=$Tj14_codigo?>"><?db_ancora(@$Lj14_codigo,"js_pesquisa_ruas(true);",3);?>
	     <td align="left" nowrap>
	        <?
		  db_input("j14_codigo",6,$Ij14_codigo,true,"text",3,"onchange='js_pesquisa_ruas(false);'");
	          db_input("j14_nome",40,$Ij14_nome,true,"text",3);
		?>
	     </td>
          </tr>
	  
          <tr>
             <td nowrap>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Ruas Correios</strong>";
                 $aux->codigo = "cp06_codlogradouro"; //chave de retorno da func
                 $aux->descr  = "cp06_logradouro";   //chave de retorno
                 $aux->nomeobjeto = 'logradouro';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_config_alt.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_log";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
             </td>
          </tr>
	  <tr>
	  <td align="center" colspan = "2">
              <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_busca_dados();" >
	  </td>
	  </tr>
	</table>
	</center>
	<?
	   db_input("logs",50,"",true,"hidden",3);
	?>
	</form>
    
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
function js_pesquisa_ruas(mostra){
  if(mostra == true){
     js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true,0);
  }else{
    if(document.form1.j14_codigo.value != ''){
     js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_preencheruas2','Pesquisa',false,0);
    }else{
      document.form1.j14_codigo.value = '';
      }
   }  
}
function js_preencheruas(chave,chave1){
  document.form1.j14_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();

}
function js_preencheruas2(chave_classe,erro_classe){
  document.form1.j14_nome.value = chave_classe ;
  if(erro_classe == true){
    document.form1.j14_codigo.value = '';
  }
  document.form1.submit();
}

function js_pesquisa_log(mostra){
  if(mostra == true){
     js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_log','func_db_config_alt.php?rural=1&funcao_js=parent.js_preenchelog|cp06_codlogradouro|cp06_logradouro','Pesquisa',true,0);
  }else{
    if(document.form1.cp06_codlogradouro.value != ''){
     js_OpenJanelaIframe('top.corpo.iframe_g2','db_iframe_log','func_db_config_alt.php?pesquisa_chave='+document.form1.cp06_codlogradouro.value+'&funcao_js=parent.js_preenchelog2','Pesquisa',false,0);
    }else{
      document.form1.cp06_codlogradouro.value = '';
      }
   }  
}
function js_preenchelog(chave,chave1){
  document.form1.cp06_codlogradouro.value = chave;
  document.form1.cp06_logradouro.value = chave1;
  db_iframe_log.hide();

}
function js_preenchelog2(chave_classe,erro_classe){
  document.form1.cp06_logradouro.value = chave_classe ;
  if(erro_classe == true){
    document.form1.cp06_codlogradouro.value = '';
  }
  document.form1.submit();
}

function js_busca_dados(){
 vir="";
 lista="";
 for(x=0;x<document.form1.logradouro.length;x++){
  lista+=vir+document.form1.logradouro.options[x].value;
  vir=",";
 }
 document.form1.logs.value = lista; 
 return true;
}
</script>