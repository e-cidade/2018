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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libcontabilidade.php");
include ("libs/db_liborcamento.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_orcprojlan_classe.php");
include ("classes/db_orcprojeto_classe.php");
include ("classes/db_orcsuplemlan_classe.php");
include ("classes/db_orcsuplem_classe.php");
include ("classes/db_orcsuplemretif_classe.php");

include ("dbforms/db_suplementacao.php");

db_postmemory($HTTP_POST_VARS);

$clorcprojlan = new cl_orcprojlan;
$clorcprojeto = new cl_orcprojeto;
$clorcsuplemlan = new cl_orcsuplemlan;
$clorcsuplemretif = new cl_orcsuplemretif;

$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");
$usuario = db_getsession("DB_id_usuario");

/*
 *  se for um projeto retificador, excluir tamb�m os lan�amentos do processo retificado
 */
if (isset ($Desprocessar)) {

	$erro = false;
	db_inicio_transacao();

        $proj = $o39_codproj;

	$matriz = explode("#", $chaves);
	if ($matriz[0] == "") {
		unset ($matriz);
		$matriz = array ();
	}
	$res = $clorcprojlan->excluir($o39_codproj);
	if ($clorcprojlan->erro_status == 0) {
		db_msgbox($clorcprojlan->erro_msg);
		$erro = true;
	}
	for ($i = 0; $i < sizeof($matriz); $i ++) {
		$codsup = $matriz[$i];
		/**
		 *  passa codsup para fun��o fazer exclus�o		 
		 */
		// $erro = desprocessa_suplementacao($codsup, db_getsession("DB_anousu"));
		$erro = desprocessa_suplementacao2($codsup, db_getsession("DB_anousu"));

		if ($erro==true)
 		    break;
	}



	/**
	*  agora verifica se este � projeto retificador, se for excluir lan�amentos de estorno do projeto retificado
	*  seleciona o projeto que foi retificado 
	*/
	if ($erro == false) {
		$sql_retificador = "select o48_retificado
		                    from orcsuplemretif 
	        	            where o48_projeto = $proj";
		$result = pg_exec($sql_retificador);		
		if (pg_numrows($result) > 0) {
			db_fieldsmemory($result, 0);
			/**
			 *  busca as suplementa��es do projeto que foi retificado
			 */
			$sql = "select o46_codsup as codsup 
			             from orcsuplem 
			             where o46_codlei = $o48_retificado  ";
			$res = pg_exec($sql);
			if (pg_numrows($res) > 0) {
				for ($x = 0; $x < pg_numrows($res); $x ++) {
					db_fieldsmemory($res, $x);
					$erro = desprocessa_suplementacao($codsup, db_getsession("DB_anousu"), true);
					if ($erro==true) break;
				}
			}
			// retira da tabela de retifica��o
			$clorcsuplemretif->excluir (null," o48_projeto=$o39_codproj  ");
			if ($clorcsuplemretif->erro_status==0){
			db_msgbox($clorcsuplemretif->erro_msg);
		        $erro = true;
			}
		}
	}		

	db_fim_transacao($erro);

	if ($erro == false) {
		db_msgbox("Opera��o efetuada com sucesso.");
		db_redireciona("orc4_despsuplem001.php");
	}
} else
	if (isset ($chavepesquisa) && $chavepesquisa != "") {
		$rr = $clorcprojeto->sql_record($clorcprojeto->sql_query_file($chavepesquisa));
		db_fieldsmemory($rr, 0);
	} else {
		$db_opcao = 22;
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?



include ("forms/db_frmdespsuplem.php");
?>
    </center>
	</td>
  </tr>
</table>
<?



db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?



if ($db_opcao == 22)
	echo "js_pesquisao39_codproj(true);";
?>
</script>