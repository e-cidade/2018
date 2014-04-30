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

db_postmemory($HTTP_POST_VARS);

$auxAtiv  = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label('q03_ativ');
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
 vir="";
 listativi="";
 for(x=0;x<document.form1.atividades.length;x++){
  listativi+=vir+document.form1.atividades.options[x].value;
  vir=",";
 }
 jan = window.open('iss2_cadgeral002.php?selOrdem='+document.form1.selOrdem.value+'&bairroInscr='+document.form1.bairroInscr.value+'&selAgrupa='+document.form1.selAgrupa.value+'&baix='+document.form1.baix.value+'&lista='+listativi+'&ver='+document.form1.ver.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
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
				<fieldset>
					<legend>
						<b>Opções</b>
					</legend>
					<table align="center">
						
						<tr> 
							<td colspan=2  align="right">
								<strong>Agrupar:</strong>
							</td>
							<td>
								<?
									$aAgrupa = array("n"=>"Nenhuma","a"=>"Atividade Principal","b"=>"Bairro","c"=>"Classe");
									db_select("selAgrupa",$aAgrupa,true,2);
								?>
							</td>
						</tr>
						
						<tr>
							<td colspan=2 align="right"  title="Todos/Não Baixados/Baixados" >
								<strong>Inscrições :</strong>
							</td>
							<td>
								<? 
									$tipo_ordem = array("t"=>"Todos","c"=>"Não Baixados" ,"b"=>"Baixados");
									db_select("baix",$tipo_ordem,true,2,"style='width:145px;'"); 
								?>
							</td>
						</tr>
					
						<tr>
							<td colspan=2 align="right">
								<strong>Ordenar :</strong>
							</td>
							<td>
								<? 
									$aOrdem = array("i"=>"Inscrição","c"=>"CGM","n"=>"Nome","a"=>"Atividade Principal","l"=>"Classe","b"=>"Bairro");
									db_select("selOrdem",$aOrdem,true,2,"style='width:145px;'"); 
								?>
							</td>
						</tr>

						<tr> 
							<td colspan=2  align="right">
								<strong>Opções:</strong>
							</td>
							<td>
								<select name="ver">
									<option name="condicao1" value="com">Com as Atividades selecionadas</option>
									<option name="condicao1" value="sem">Sem as atividades selecionados</option>
								</select>
							</td>
						</tr>
						
						<tr>
							<td>
								<input name="bairroInscr" type="hidden"/>
							</td>
						</tr>
					</table>
				</fieldset>
				<table align="center">
					<tr >
						<td colspan=2 >
							<?
								$auxAtiv->cabecalho = "<strong>Fornecedores</strong>";
								$auxAtiv->codigo = "q03_ativ"; //chave de retorno da func
								$auxAtiv->descr  = "q03_descr";   //chave de retorno
								$auxAtiv->nomeobjeto = 'atividades';
								$auxAtiv->funcao_js = 'js_mostra';
								$auxAtiv->funcao_js_hide = 'js_mostra1';
								$auxAtiv->sql_exec  = "";
								$auxAtiv->func_arquivo = "func_ativid.php";  //func a executar
								$auxAtiv->nomeiframe = "db_iframe_ativid";
								$auxAtiv->localjan = "";
								$auxAtiv->onclick = "";
								$auxAtiv->db_opcao = 2;
								$auxAtiv->tipo = 2;
								$auxAtiv->top = 0;
								$auxAtiv->linhas = 10;
								$auxAtiv->vwhidth = 400;
								$auxAtiv->funcao_gera_formulario();
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
						<td colspan="2" align = "center"> 
							<input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>	
</center>
</body>
</html>
<script>
</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>