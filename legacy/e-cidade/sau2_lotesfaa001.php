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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

include("classes/db_acervo_classe.php");
include("classes/db_reserva_classe.php");
include("classes/db_leitor_classe.php");
include("classes/db_carteira_classe.php");
include("classes/db_emprestimo_classe.php");
include("classes/db_emprestimoacervo_classe.php");
include("classes/db_devolucaoacervo_classe.php");
include("classes/db_exemplar_classe.php");
include("classes/db_bib_parametros_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table width="100%">
		<tr><td width="100%" height='30'  ></td></tr>
	</table>
	<form name="form1" method="post" action="">
		<table  align="center">
			<tr>
				<td colspan="2" align="center">
				<?
				$aux->cabecalho      = "<strong>Lotes de FAA</strong>";
				$aux->codigo         = "sd58_i_codigo"; //chave de retorno da func
				$aux->concatenar_codigo = "login";
				$aux->descr          = "login";   //chave de retorno
				//$aux->ordenar_itens  =  "sd58_i_codigo";
				$aux->nomeobjeto     = 'lotesfaa';
				$aux->funcao_js      = 'js_mostra';
				$aux->funcao_js_hide = 'js_mostra1';
				$aux->sql_exec       = "";
				$aux->func_arquivo   = "func_sau_lote.php";  //func a executar
				$aux->nomeiframe     = "db_iframe_sau_lote";
				$aux->localjan       = "";
				$aux->onclick        = "";
				$aux->db_opcao       = 2;
				$aux->tipo           = 2;
				$aux->top            = 0;
				$aux->linhas         = 7;
				$aux->vwidth         = 400;
				//$aux->executa_script_lost_focus_campo = "document.form1.db_lanca.onclick = js_insSelectemprestimo;document.form1.db_lanca.click();";
				$aux->funcao_gera_formulario();
				?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align = "center">
					<input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
				</td>
			</tr>
		</table>
	</form>
	<script>
		js_tabulacaoforms("form1","bi18_carteira",true,1,"bi18_carteira",true);
	</script>
	<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>

<script>
function js_emite(){
 var qtd=0;
 for(i=0;i<document.form1.length;i++){
  if(document.form1.elements[i].name == "lotesfaa[]"){
   vir="";
   lista="";
   for(x=0;x< document.form1.elements[i].length;x++){
    qtd = qtd+1;
    document.form1.elements[i].options[x].selected = true;
    lista+=vir+document.form1.lotesfaa.options[x].value;
    vir=",";
   }
  }
 }
 //-- ve se tem lista
 if (qtd == 0){
  alert('Lista de Lotes não pode ser vazia ! ');
  document.form1.sd58_i_codigo.style.backgroundColor="#99A9AE";
  document.form1.sd58_i_codigo.focus();
  return false;
 }
 x = 'sau2_lotesfaa002.php?lotesfaa='+lista;
 jan = window.open(x,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0); 
}

</script>