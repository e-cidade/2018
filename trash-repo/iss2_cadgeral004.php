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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_issbairro_classe.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona  = new cl_iframe_seleciona;
$clissbairro 				 = new cl_issbairro;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_nome(obj){
  j13_bairro = "";
  vir = "";
  x = 0;
  for(i=0;i<bairro.document.form1.length;i++){
   if(bairro.document.form1.elements[i].type == "checkbox"){
     if(bairro.document.form1.elements[i].checked == true){
       valor = bairro.document.form1.elements[i].value.split("_")
       j13_bairro += vir + valor[0];
       vir = ",";
       x += 1;
     }
   }
  }
   parent.iframe_g1.document.form1.bairroInscr.value = j13_bairro;
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<table>
	<tr>
		<td>
			<form name="form1" method="post" action="">
				<table align="center">
					<tr >
						<td colspan=2 >
							<?
								$cliframe_seleciona->campos  = "j13_codi,j13_descr";
								$cliframe_seleciona->legenda="Bairro da Inscrição";
								$cliframe_seleciona->sql=$clissbairro->sql_query("","distinct j13_codi, j13_descr","j13_codi, j13_descr","");
								$cliframe_seleciona->textocabec ="darkblue";
								$cliframe_seleciona->textocorpo ="black";
								$cliframe_seleciona->fundocabec ="#aacccc";
								$cliframe_seleciona->fundocorpo ="#ccddcc";
								$cliframe_seleciona->iframe_height ="250";
								$cliframe_seleciona->iframe_width ="700";
								$cliframe_seleciona->iframe_nome ="bairro";
								$cliframe_seleciona->chaves ="j13_codi,j13_descr";
								$cliframe_seleciona->dbscript ="onClick='parent.js_nome(this)'";
								$cliframe_seleciona->js_marcador="parent.js_nome()";
								$cliframe_seleciona->iframe_seleciona(@$db_opcao);
							?>
						</td>
					</tr>
				</table>
				<table align="center">
					<tr>
						<td >&nbsp;</td>
						<td >&nbsp;</td>
					</tr>
					<tr>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>	
</center>
</body>
</html>