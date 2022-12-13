<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
if (isset($pesquisa)){
	$sql="select * from itbinome inner join itbi on it03_guia=it01_guia  where it03_cpfcnpj = '$cnpj'";   
	$result= db_query($sql);
	$linhas=pg_num_rows($result);
	if($linhas>0){
		echo"
		<script>
			location.href='itbi_consulta1.php?cnpj=$cnpj';
		</script>
		";
	}else{
		msgbox("Dados informados incorretos, digite novamente.");	
	}
}

?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
	<form name="form1" method="post" action="">
	    <table width="60%" border="0"  cellpadding="5" cellspacing="0" class="texto" align="center">
	    	<tr>
	            <td align="center" colspan="2">&nbsp;</td>
	        </tr>
	    	<tr>
	            <td align="center" colspan="2" class="titulo">Consulta ITBI</td>
	        </tr>
	    	<tr>
	            <td align="center" colspan="2">&nbsp;</td>
	        </tr>
	    	<tr>
	            <td width="50%" height="30" align="right">CNPJ/CPF:&nbsp;
	            </td>
	            <td width="50%" height="30"> <input name="cnpj" type="text" size="18" maxlength="18"
                  onChange='js_teclas(event);'
                  onKeyPress="FormataCPFeCNPJ(this,event); return js_teclas(event);">
	            </td>
	        </tr>
	        
	        <tr>
	            <td width="50%" height="30">&nbsp;
	            </td>
	            <td><input type="submit" class="botao" name="pesquisa" value="Pesquisa" >
	            </td>
	        </tr>
	    </table>
    </form>
</body>
</html>