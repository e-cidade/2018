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
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$rotulo = new rotulocampo();
$rotulo->label("x21_exerc");
$rotulo->label("x21_mes");

$db_opcao = 1;

$aux = new cl_arquivo_auxiliar;

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  virgula = "";
  lista = "";
  for(x=0; x<document.form1.rota.length; x++){
    lista += virgula+document.form1.rota.options[x].value;
    virgula=",";
  }

  if(document.form1.tipoArquivo.value == 1) {
	  jan = window.open('agu2_planilhaleitura002.php?anousu='+document.form1.x21_exerc.value+'&mesusu='+document.form1.x21_mes.value+'&lista='+lista+'&condicao='+document.form1.ver.value+'&tipodoc='+document.form1.tipoArquivo.value+'&filtro='+document.form1.filtro.value ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    	
	  jan.moveTo(0,0);
  }else {
		//js_OpenJanelaIframe('top.corpo','db_iframe_tarefa','ate2_contarefa001.php?menu=false&chavepesquisa='+tarefa,'Pesquisa',true,'30');
		js_OpenJanelaIframe('top.corpo', 'db_iframe_arquivo', 'agu2_planilhaleitura002.php?anousu='+document.form1.x21_exerc.value+'&mesusu='+document.form1.x21_mes.value+'&lista='+lista+'&condicao='+document.form1.ver.value+'&tipodoc='+document.form1.tipoArquivo.value+'&filtro='+document.form1.filtro.value, 'Arquivo', false, 30);	
  }	
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>

<table  align="center">
<form name="form1" method="post" action="">
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Tx21_exerc?>" align="right">
			<b><?=@$RLx21_exerc?>&nbsp;/&nbsp;<?=@$RLx21_mes?>:</b>  
		</td>
		<td nowrap> 
		<?
			if(!isset($x21_exerc) || (isset($x21_exerc) && trim($x21_exerc) == "")){
				$x21_exerc = db_getsession("DB_anousu");
			}
			db_input('x21_exerc',4,$Ix21_exerc,true,'text',$db_opcao==1?1:3,"");
		?>
		<b>&nbsp;/&nbsp;</b>  
		<?
			if(!isset($x21_mes) || (isset($x21_mes) && trim($x21_mes) == "")){
				$x21_mes = date("m",db_getsession("DB_datausu"));
			}
			db_input('x21_mes',2,$Ix21_mes,true,'text',$db_opcao==1?1:3,"");
		?> 
		</td>
	</tr>

	<tr>
		<td align="right">
			<strong>Op&ccedil;&otilde;es:</strong>
		</td>
		<td>
			<select name="ver">
				<option name="condicao1" value="com">Com as rotas selecionados</option>
				<option name="condicao1" value="sem">Sem as rotas selecionados</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right"><strong>Tipo de Emiss&atilde;o:</strong></td>
		<td>
		<?
			$aTipoArquivo	=	array('1'=>'Relat&oacute;rio PDF', '2'=>'Arquivo CSV');
			db_select('tipoArquivo', $aTipoArquivo, true, 1, 'style="width: 200px;"')
		?>
		</td>
	</tr>
    <tr>
      <td align="right"><strong>Filtro:</strong>
      </td>
      <td>
      <?
         $x = array('1'=>'Todos os Logradouros', '2'=>'Somente logradouros não exportados para o coletor.');
         db_select("filtro", $x, true, 1);
      ?>
      </td>
    </tr>
	
	</table>
	<table align="center">
	<tr>
		<td>
		<?
			// $aux = new cl_arquivo_auxiliar;
			$aux->cabecalho 				= "<strong>Rotas</strong>";
			$aux->codigo 						= "x06_codrota"; //chave de retorno da func
			$aux->descr  						= "x06_descr";   //chave de retorno
			$aux->nomeobjeto 				= 'rota';
			$aux->funcao_js 				= 'js_mostra';
			$aux->funcao_js_hide 		= 'js_mostra1';
			$aux->sql_exec  				= "";
			$aux->func_arquivo 			= "func_aguarota.php";  //func a executar
			$aux->nomeiframe 				= "db_iframe_aguarota";
			$aux->localjan 					= "";
			$aux->onclick 					= "";
			$aux->db_opcao 					= 2;
			$aux->tipo 							= 2;
			$aux->top 							= 0;
			$aux->linhas 						= 10;
			$aux->vwidth 					  = 360;
			$aux->funcao_gera_formulario();
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align = "center"> 
		<input  name="emite2" id="emite2" type="button" value="Emitir Planilha" onclick="js_emite();" >
		</td>
	</tr>
</form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>