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
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<form name="form1" method="post" action="cad2_iptuconstr002.php" target="rel">
<center>
<table border="0">
  <tr>
      <td nowrap width="50%">
<?
$aux1 = new cl_arquivo_auxiliar;
$aux1->cabecalho = "<strong>QUE NÃO CONTENHAM ESTAS RUAS</strong>";
$aux1->codigo = "j14_codigo";
$aux1->descr  = "j14_nome";
$aux1->nomeobjeto = 'ruas1';
$aux1->funcao_js = 'js_mostraa';
$aux1->funcao_js_hide = 'js_mostraa1';
$aux1->sql_exec  = "";
$aux1->func_arquivo = "func_ruas.php";
$aux1->nomeiframe = "iframa_ruas1";
$aux1->onclick = "js_ver_rua()";
$aux1->localjan = "";
$aux1->db_opcao = 2;
$aux1->tipo = 2;
$aux1->linhas = 10;
$aux1->vwhidth = 400;
$aux1->funcao_gera_formulario();
?>    
<script>
function js_ver_rua(){
  for(i=0;i<parent.iframe_g5.document.form1.length;i++){
    if(parent.iframe_g5.document.form1.elements[i].name == "ruas[]"){
      for(x=0;x<parent.iframe_g5.document.form1.elements[i].length;x++){
        if(parent.iframe_g5.document.form1.elements[i].options[x].value == document.form1.j14_codigo.value){
	  alert('Rua já selecionada para constar no relatório')
	  document.form1.j14_codigo.value = '';
	}
      }
    }
  }
}
</script>
      </td>
    </tr>
  </table>
  </center>
</form>
<script>
</script>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
</script>