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
include("classes/db_listainscrcab_classe.php");
include("classes/db_cgm_classe.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$cllistainscrcab = new cl_listainscrcab;

$result=$cllistainscrcab->sql_record($cllistainscrcab->sql_query("","*","p11_codigo desc"," z01_numcgm = $z01_numcgm"));
if($cllistainscrcab->numrows > 0 ){
  $numrows=$cllistainscrcab->numrows;
  ?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" target="_parent" action="listaescritorios.php">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" colspan="3">
      <table width="100%" class="tab">
	<tr>
	  <th align="center">
	    <strong>Código</strong>
	  </th>
	  <th align="center">
	    <strong>Data</strong>
	  </th>
	  <th align="center">
	    <strong>Hora</strong>
	  </th>
	  <th align="center">
	    <strong>Contato</strong>
	  </th>
	  <th align="center">
	    <strong>Opções</strong>
	  </th>
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
	<tr bgcolor="<?=$cor?>" nowrap>
	  <td align="left">
	    <strong><?=@$p11_codigo?></strong>
	  </td>
	  <td align="left">
	    <strong><?=db_formatar(@$p11_data,'d')?></strong>
	  </td>
	  <td align="left">
	    <strong><?=@$p11_hora?></strong>
	  </td>
	  <td align="left">
	    <strong><?=@$p11_contato?></strong>
	  </td>
	  <td align="left" nowrap>
	    <input class="botao" type="<?=($p11_fechado == 'f'?"submit":"button")?>" value="<?=($p11_fechado == 'f'?"Fechar Lista":"Imprimir Lista")?>" name="opcao" <?=($p11_fechado == 'f'?"onclick=\"document.form1.p11_codigo_fechar.value='$p11_codigo';return confirm('Após fechar a lista ela não pode mais ser alterada\\ndeseja fechar a lista?');\"":"onclick=\"js_imprimir('$p11_codigo')\"")?>>
	    <?
	    if($p11_processado == 'f' && $p11_fechado == 'f'){
	    ?>
	      <input type="submit" value="Alterar Lista" name="alterar" class="botao" onclick="document.form1.p11_codigo_alterar.value='<?=$p11_codigo?>'">
	    <?
	    }elseif($p11_processado == 't'){
	      echo "<strong><font size='1'>.: lista já processada na Prefeitura :.</font></strong>";
	    }else{
	      echo "<strong><font size='1'>.: lista já fechada pelo escritório :.</font></strong>";
	    }
	    if($p11_fechado == 'f'){
	    ?>
	    <input type="button" class="botao" value="Imprimir Lista" name="opcao" onclick="js_imprimir('<?=$p11_codigo?>')">
	    <?
	    }
	    ?>
	  </td>
	</tr>
<?
}
?>	      
	<input type="hidden" value="" name="p11_codigo_fechar">
	<input type="hidden" value="" name="p11_codigo_alterar">
      </table>
    </td>
  </tr>
  <?
}else{
?>
  <tr> 
    <td colspan="5" align="center">
      <strong><font size="2">Sem listas cadastradas</font></strong>
    </td>
  </tr>

<?
}
?>
</table> 
</form>
</body>
</html>
<script>
function js_imprimir(codigo){
  window.open('listaescritoriospdf.php?p12_codigo='+codigo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
}
</script>