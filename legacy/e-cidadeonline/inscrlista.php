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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_listainscr_classe.php");
include("classes/db_cgm_classe.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$cllistainscr = new cl_listainscr;
$result=$cllistainscr->sql_record($cllistainscr->sql_query($p12_codigo));
if($cllistainscr->numrows > 0 ){
  $numrows = $cllistainscr->numrows;
?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" target="_parent" action="listaescritorios001.php">
      <table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tr>
	  <td align="center">
	    <strong>Inscrição</strong>
	  </td>
	  <td align="center">
	    <strong>Data de início da inscrição</strong>
	  </td>
	  <td align="center">
	    <strong>Nome</strong>
	  </td>
	  <td align="center">
	    <strong>CNPJ</strong>
	  </td>
	  <td align="center">
	    <strong>Opções</strong>
	  </td>
	</tr>
<?
for($i=0;$i<$numrows;$i++){
  db_fieldsmemory($result,$i);
  if($i%2 == 0){
    $cor = "#9fc2b9";
  }else{
    $cor = "#f3dd9c";
  }
?>	      
	<tr bgcolor="<?=$cor?>">
	  <td align="center">
	    <strong><?=@$p12_inscr?></strong>
	  </td>
	  <td align="center">
	    <strong><?=db_formatar(@$q02_dtinic,'d')?></strong>
	  </td>
	  <td align="center">
	    <strong><?=@$z01_nome?></strong>
	  </td>
	  <td align="center">
	    <strong><?=db_formatar($p12_cnpj,'cnpj')?></strong>
	  </td>
	  <td align="center">
	    <input type="submit" value="Excluir" name="opcao" onclick="document.form1.p12_codigo_excluir.value='<?=$p12_codigo?>';document.form1.p12_inscr_excluir.value='<?=$p12_inscr?>'">
	  </td>
	</tr>
  <?
  }
  ?>	      
	<input type="hidden" value="" name="p12_codigo_excluir">
	<input type="hidden" value="" name="p12_inscr_excluir">
      </table>
    </td>
  </tr>
  <?
}else{
?>
  <tr> 
    <td colspan="3" align="center">
      <strong><font size="2">Sem listas cadastradas</font></strong>
    </td>
  </tr>
</table>  

<?
}
?>
</form>
</body>
</html>