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
include("classes/db_procrec_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocrec = new cl_procrec;
$clprocrec->rotulo->label("p52_codigo");
$clprocrec->rotulo->label("p52_codrec");
$clprocrec->rotulo->label("p52_codrec");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0"
	bgcolor="#CCCCCC">
	<tr>
		<td height="63" align="center" valign="top">
		<table width="35%" border="0" align="center" cellspacing="0">
			<form name="form2" method="post" action="">
			
			
			<tr>
				<td width="4%" align="right" nowrap title="<?=$Tp52_codigo?>"><?=$Lp52_codigo?>
				</td>
				<td width="96%" align="left" nowrap><?
				db_input("p52_codigo",3,$Ip52_codigo,true,"text",4,"","chave_p52_codigo");
				?></td>
			</tr>
			<tr>
				<td width="4%" align="right" nowrap title="<?=$Tp52_codrec?>"><?=$Lp52_codrec?>
				</td>
				<td width="96%" align="left" nowrap><?
				db_input("p52_codrec",3,$Ip52_codrec,true,"text",4,"","chave_p52_codrec");
				?></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input name="pesquisar" type="submit"
					id="pesquisar2" value="Pesquisar"> <input name="limpar"
					type="reset" id="limpar" value="Limpar"> <input name="Fechar"
					type="button" id="fechar" value="Fechar"
					onClick="parent.db_iframe.hide();"></td>
			</tr>
			</form>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top"><?
    $sWhere = " p51_instit = ".db_getSession("DB_instit");
		if(!isset($pesquisa_chave)){

		  $campos = "p52_codigo, p51_descr, p52_codrec,k02_drecei,p52_valor";
		   
		  if(isset($chave_p52_codigo) && (trim($chave_p52_codigo)!="") ){
		   
		    $sql = $clprocrec->sql_query(null,$chave_p52_codrec,$campos,"p52_codigo",
                                    "p52_codigo = $chave_p52_codigo and $sWhere");
		  }else if(isset($chave_p52_codrec) && (trim($chave_p52_codrec)!="") ){
		   
		    $sql = $clprocrec->sql_query("","",$campos,"p52_codrec"," p52_codrec like '$chave_p52_codrec%'  and $sWhere");
		  }else{
		   
		    $sql = $clprocrec->sql_query("","",$campos,"","$sWhere");
		  }
		 
		  db_lovrot($sql,15,"()","",$funcao_js);
		}else{
		  if($pesquisa_chave!=null && $pesquisa_chave!=""){
		    $result = $clprocrec->sql_record($clprocrec->sql_query(null,null,"*",null,"p52_codigo = $pesquisa_chave and $sWhere"));
		    if($clprocrec->numrows!=0){
		      db_fieldsmemory($result,0);
		      echo "<script> ".$funcao_js."('$p52_codrec',false);</script>";

		    }else{
		      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
		    }
		  }else{
		    echo "<script>".$funcao_js."('',false);</script>";
		  }
		}
		?></td>
	</tr>
</table>
</body>
</html>
		<?
		if(!isset($pesquisa_chave)){
		  ?>
<script>
document.form2.chave_p52_codigo.focus();
document.form2.chave_p52_codigo.select();
  </script>
		  <?
}
?>