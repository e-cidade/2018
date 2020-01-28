<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_aguacoletorexporta_classe.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claguacoletorexporta = new cl_aguacoletorexporta;
$claguacoletorexporta->rotulo->label("x49_sequencial");

$rotulo = new rotulocampo();
$rotulo->label("x21_exerc");
$rotulo->label("x21_mes");
$rotulo->label("x46_sequencial");
$rotulo->label("x46_descricao");

$anousu = db_getsession("DB_anousu");
$mesusu = date('m');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
	<tr>
		<td height="63" align="center" valign="top">
		<table width="35%" border="0" align="center" cellspacing="0">
			<form name="form2" method="post" action="">
			
			<tr>
				<td width="4%" align="right" nowrap title="<?=$Tx49_sequencial?>"><?=$Lx49_sequencial?>
				</td>
				<td width="96%" align="left" nowrap>
				<?
				db_input("x49_sequencial",10,$Ix49_sequencial,true,"text",4,"","chave_x49_sequencial");
				?>
				</td>
			</tr>
			
			<tr>
				<td nowrap title="<?=@$Tx21_exerc?>" align="right"><b><?=@$RLx21_exerc?>:</b>
				</td>
				<td colspan="2">
				<?
				
				$vAno = array(""=>"", db_getsession("DB_anousu") => db_getsession("DB_anousu"), db_getsession("DB_anousu") + 1 => db_getsession("DB_anousu") + 1);
				
				db_select("x21_exerc",$vAno,true,1,"style=\"width: 90px\"","chave_x21_exerc","","","");
				?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tx21_mes?>" align="right"><b><?=@$RLx21_mes?>:</b>
				</td>
				<td colspan="2">
				<?
				$result=array(""=>"", "1"=>"Janeiro","2"=>"Feveireiro","3"=>"Março","4"=>"Abril","5"=>"Maio","6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
				db_select("x21_mes",$result,true,1,"style=\"width: 90px\"","chave_x21_mes");
					
				?>
				</td>
			</tr>

			<!-- 
			 
			<tr>
				<td width="4%" align="right" nowrap title="<?=$Tx49_sequencial?>"><?=$Lx49_sequencial?>
				</td>
				<td width="96%" align="left" nowrap>
				<?
				db_input("x49_sequencial",10,$Ix49_sequencial,true,"text",4,"","chave_x49_sequencial");
				?>
				</td>
			</tr>
			-->
			
		    <tr>
    			<td nowrap title="<?=@$Tx46_sequencial?>" align="right">
    	  		<?
    	  		db_ancora(@$Lx46_sequencial, "js_pesquisa();", 1);
    	  		?>
    			</td>
    			<td>
    			<?
    			db_input('x46_sequencial', 10, $Ix46_sequencial, true, 'text', 1, "", "");
    			?>
    			</td>
    			<td>
    			<?
    			db_input('x46_descricao', 30, $Ix46_descricao, true, 'text', 3, "")
    			?>
    			</td>
    		</tr>
			<tr>
				<td colspan="3" align="center">
				<input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
				<input name="limpar" type="reset" id="limpar" value="Limpar"> 
				<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguacoletorexporta.hide();">
				</td>
			</tr>
			</form>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top"><?
		if(!isset($pesquisa_chave)){
			if(isset($campos)==false){
				if(file_exists("funcoes/db_func_aguacoletorexporta.php")==true){
					include("funcoes/db_func_aguacoletorexporta.php");
				}else{
					$campos = "aguacoletorexporta.x49_sequencial,
                    		   aguacoletor.x46_descricao,
                    		   db_config.nomeinst, 
                    		   aguacoletorexporta.x49_anousu, 
                               aguacoletorexporta.x49_mesusu, 
                               case 
                               when aguacoletorexporta.x49_situacao = 1
                               then 'Exportado'
                               when aguacoletorexporta.x49_situacao = 2
                               then 'Importado'
                               else 'Cancelado'
                               end as x49_situacao";
				}
			}
			
			if(isset($chave_x49_sequencial) && (trim($chave_x49_sequencial)!="") ){
				$sql = $claguacoletorexporta->sql_query(null,$campos,"x49_sequencial", "x49_sequencial = $chave_x49_sequencial and x49_situacao = 1");
			}else if(isset($x21_exerc) && (trim($x21_exerc) != "")) {
				$sql = $claguacoletorexporta->sql_query(null, $campos, "x49_sequencial", "x49_anousu = $x21_exerc and x49_situacao = 1");
			}else if(isset($x21_mes) && (trim($x21_mes != "")))  {
				$sql = $claguacoletorexporta->sql_query(null, $campos, "x49_sequencial", "x49_mesusu = $x21_mes and x49_situacao = 1");
			}else if(isset($x46_sequencial) && (trim($x46_sequencial) != "")) {
				$sql = $claguacoletorexporta->sql_query(null, $campos, "x49_sequencial", "x49_aguacoletor = $x46_sequencial and x49_situacao = 1");
			}else {
				$sql = $claguacoletorexporta->sql_query('',$campos,"x49_sequencial", "x49_situacao = 1");
			}			
			db_lovrot($sql, 15, "()", "", $funcao_js,"","NoMe");
			
		}else{
			if($pesquisa_chave!=null && $pesquisa_chave!=""){
				$result = $claguacoletorexporta->sql_record($claguacoletorexporta->sql_query($pesquisa_chave));
				if($claguacoletorexporta->numrows!=0){
					db_fieldsmemory($result,0);
					echo "<script>".$funcao_js."('$x49_sequencial',false);</script>";
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
			
			<?
		}
		?>
		
<script>
//js_tabulacaoforms("form2","chave_x49_sequencial",true,1,"chave_x49_sequencial",true);

function js_pesquisa(){
	js_OpenJanelaIframe('','db_iframe_aguacoletor','func_aguacoletor.php?funcao_js=parent.js_preenchepesquisa|x46_sequencial|x46_descricao','Pesquisa',true);
}
function js_preenchepesquisa(chave1,chave2){

	db_iframe_aguacoletor.hide();
 	document.form2.x46_sequencial.value = chave1;
 	document.form2.x46_descricao.value = chave2;
}
</script>