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
include("classes/db_pcmater_classe.php");
include("classes/db_pcmaterele_classe.php");
include("classes/db_pcgrupo_classe.php");
include("classes/db_pcsubgrupo_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpcmater = new cl_pcmater;
$clpcmaterele = new cl_pcmaterele;
$clpcgrupo = new cl_pcgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clpcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc03_codgrupo");
$clrotulo->label("pc04_codsubgrupo");
$clrotulo->label("pc04_descrsubgrupo");
$clrotulo->label("pc03_descrgrupo");
$db_opcao = 3;
$db_botao = false;
if(isset($pc01_codmater)){   
   $db_botao = true;
   $result = $clpcmater->sql_record($clpcmater->sql_query($pc01_codmater)); 
   db_fieldsmemory($result,0);   
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0" cellspacing="0" cellpadding="0">
	<tr> 
    	<td  align="left" valign="top" bgcolor="#CCCCCC"> 
    		<center>
			<form name="form1" method="post" action="">
				<table border="0">
  					<tr>
    					<td nowrap title="<?=@$Tpc01_codmater?>"><?=@$Lpc01_codmater?></td>
    					<td><?db_input('pc01_codmater',6,$Ipc01_codmater,true,'text',3,"");?></td>
  					</tr>
  					<tr>
    					<td nowrap title="<?=@$Tpc01_descrmater?>"> <?=@$Lpc01_descrmater?></td>
    					<td><?db_input('pc01_descrmater',80,$Ipc01_descrmater,true,'text',$db_opcao,"")?></td>
  					</tr>
  					<tr>
    					<td nowrap title="<?=@$Tpc01_complmater?>"><?=@$Lpc01_complmater?></td>
    					<td><?db_textarea('pc01_complmater',0,50,$Ipc01_complmater,true,'text',$db_opcao,"")?></td>
  					</tr>
  					<tr>
    					<td nowrap title="<?=@$Tpc01_libaut?>"><?=@$Lpc01_libaut?></td>
    					<td>
    					<?
    					$arrlibaut_truefalse = array('t'=>'Sim','f'=>'Não');
    					db_select("pc01_libaut",$arrlibaut_truefalse,true,$db_opcao);
    					?>
    					<?=$Lpc01_ativo?> 
    					<?
    					$arr_truefalse = array('f'=>'Não','t'=>'Sim');
    					db_select("pc01_ativo",$arr_truefalse,true,$db_opcao);
    					?>  
					   	</td>
  					</tr>
  					<tr>
    					<td><?=$Lpc03_codgrupo?></td>
    					<td align='left'>
    					<?
        				db_input('pc04_codgrupo',6,$Ipc01_codsubgrupo,true,'text',3,"");
        				db_input('pc03_descrgrupo',60,$Ipc04_descrsubgrupo,true,'text',3,"");
        				?>  
    				    </td>
  					</tr> 
   				    <tr>
       					<td><?=$Lpc04_codsubgrupo?></td>
       					<td align='left'>
        				<?
        				db_input('pc01_codsubgrupo',6,$Ipc01_codsubgrupo,true,'text',3,"");
        				db_input('pc04_descrsubgrupo',60,$Ipc04_descrsubgrupo,true,'text',3,"");
				       	?>  
      					</td>
      				</tr>    
  					<tr>
					    <td colspan="2" align="center">
					    	<div align="center"><b>Lista de desdobramentos<b></div>    
      						<iframe width="630" height="200" name="lista" src="com3_consultaitem003.php?pc01_codmater=<?=@$pc01_codmater?>"></iframe>
    					</td>
  					</tr>
  					<tr>
  					   	<td colspan="2" align="center">
  					   		<input type="button" value="Solicitações" onclick="js_mostrainfo('sol');";>
  					   		<input type="button" value="Autorizações" onclick="js_mostrainfo('aut');";>
  					   		<input type="button" value="Empenhos" onclick="js_mostrainfo('emp');";>  					   		
  					   	</td>
  					</tr>
  				</table>
  			</form>
		    </center>
		</td>
  	</tr>
</table>
</center>
</body>
</html>
<script>
function js_mostrainfo(mostra){	
		js_OpenJanelaIframe('top.corpo','db_iframe_mostrainfo','com3_consultaiteminfo.php?pc01_codmater='+document.form1.pc01_codmater.value+'&mostra='+mostra,'..::Consulta Item::..',true);	
}
</script>