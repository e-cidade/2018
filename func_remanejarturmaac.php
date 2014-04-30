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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_turmalogac_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clturmalogac = new cl_turmalogac;
if(isset($turma)){

 $result  = $clturmalogac->sql_record(

                                   $clturmalogac->sql_query("",
                                                       "ed268_c_descr,ed268_i_codigo,ed268_i_tipoatend",
                                                       "ed268_i_codigo asc",
                                                       " ed288_i_turmaac in ($turma)"
                                                       )
                                  );
                                  
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 10;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<tr>
 <td align="center" valign="top">
  <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
   <tr class='cabec'>
    <td align='center' colspan='8'>
     Turmas Remanejadas
    </td>
   </tr>
   <?if($clturmalogac->numrows > 0){?>
   <tr><td height='2' colspan='8' bgcolor='#444444'></td></tr>
   <tr bgcolor="#DBDBDB" align="center">
    <td><b>Código</b></td>
    <td><b>Turma</b></td>
    <td colspan="2"><b>Opções</b></td>      
   </tr>
   <?   
   for ($c = 0; $c < $clturmalogac->numrows; $c++) {
     db_fieldsmemory($result,$c); 
    ?>
    <tr>
     <td class="aluno" width="5%" align="center"><?=$ed268_i_codigo?></td>
     <td class="aluno"><?=$ed268_c_descr?></td>   
     <td align="center"><a href="javascript:js_turmaac002(<?=$ed268_i_codigo?>)">Alterar</a></td>
     <td align="center"><a href="javascript:js_turmaac003(<?=$ed268_i_codigo?>)">Excluir</a></td>
    </td>
    </tr>
    <?
   }
   } else {
   	 echo "<tr><td align='center'>Nenhum registro encontrado!</td></tr>";
   }
   ?>
   <tr>
    <td align="center" colspan="4">
     <input type="button" name="novo" value="Nova Turma" Onclick = "js_novo();">
     <input type="button" name="fechar" value="Fechar" Onclick="js_fechar();">
     <input type="submit" name="teste" value="Teste" style = visibility:hidden;>
    </td>   
   </tr>
  </table>
 </td>
</tr>
</table>
</form>
</body>
</html>
<script>

function js_turmaac002(ed268_i_codigo) {
	
  top = ( screen.availHeight-710) / 2;
  left = ( screen.availWidth-800 ) / 2;   
<?if (isset($ed268_i_codigo)) {?>
	  
    js_OpenJanelaIframe("",
                        "db_iframe_remanejarturmaac",
                        "edu1_remanejarturmaac002.php?chavepesquisa="+ed268_i_codigo+"&tipoatendimento=<?=$ed268_i_tipoatend?>&abre=true",
                        "Alunos Matriculados na Turma",true,top, left, 900,400);
<?}?>
}

function js_turmaac003(ed268_i_codigo) {
	
	  top = ( screen.availHeight-710) / 2;
	  left = ( screen.availWidth-800 ) / 2;   
	<?if (isset($ed268_i_codigo)) {?>
	    js_OpenJanelaIframe("",
	                        "db_iframe_remanejarturmaac",
	                        "edu1_remanejarturmaac003.php?chavepesquisa="+ed268_i_codigo+"&tipoatendimento=<?=$ed268_i_tipoatend?>&abre=true",
	                        "Alunos Matriculados na Turma",true,top, left, 900,400);
	<?}?>
}

function js_novo() {
  parent.location="edu1_turmaacabas001.php";
}

function js_fechar(){
  parent.location="edu1_remanejarturmaac001.php";
}
</script>