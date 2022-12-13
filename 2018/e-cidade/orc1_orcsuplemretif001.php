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
include ("classes/db_orcsuplemretif_classe.php");
include ("dbforms/db_funcoes.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_orcprojlan_classe.php");

db_postmemory($HTTP_POST_VARS);

$clorcsuplemretif = new cl_orcsuplemretif;
$clorcsuplem  = new cl_orcsuplem;
$clorcprojlan = new cl_orcprojlan;

include ("dbforms/db_suplementacao.php");

$db_opcao = 1;
$db_botao = true;

if (isset ($processar) && ($processar == "Processar")) {
	db_inicio_transacao();
	//$clorcsuplemretif->incluir($o48_seq);
	//db_fim_transacao();
	/*
	 * pega todas as suplementações do projeto retificador, processa e fecha o projeto 
	 * 
	 */
	$erro = false; 
	$anousu = db_getsession("DB_anousu");
	$data = "$o48_data_ano-$o48_data_mes-$o48_data_dia";
	$usuario = db_getsession("DB_id_usuario");
	
	// -- processamento do projeto retificador
    $matriz = array();    
	$sql = " select o46_codsup as chave
			      from orcsuplem
					         inner join orcprojeto on o46_codlei=o39_codproj
					         left outer join orcsuplemlan on o49_codsup = o46_codsup
			      where o46_codlei=$o48_projeto and
				             o49_codsup is null
				           ";				           
	$res = $clorcsuplem->sql_record($sql);	
	if (($clorcsuplem->numrows) > 0) {
		for ($i = 0; $i < pg_numrows($res); $i ++) {
			db_fieldsmemory($res, $i);
			$matriz[$i] = $chave;
		}
	}
	// fecha projeto
	$clorcprojlan->o51_id_usuario = $usuario;
	$clorcprojlan->o51_data = $data;
	$clorcprojlan->incluir($o48_projeto); // fecha o projeto

	if (!isset($matriz[0]) || $matriz[0] == "") {
		$erro = true;
		db_msgbox("Projeto   $o48_projeto não possui nenhuma suplementação lançada ! Verifique ! ");
	} 
	//--
	if ($erro==false){
	   for ($i = 0; $i < sizeof($matriz); $i ++) {
		  $o46_codsup = $matriz[$i];

		  $teste = processa_suplementacao($o46_codsup, $data, $usuario);
		  if ($teste != false) {
			  $erro = true;
		  }
		  /* @@ LEMBRETE @@
		   *  Para variáveis boleanas o php retorna vazio para resultados 'false' e retorna '1' para resultados 'true' 
		   */
	   }
	}
	/*
	 *  projeto a ser retificado
	 *    8 = estorno de suplementação
	 *  10 = estorno de credito especial
	 *  12 = estorno de redução
	 */	 	 
    $matriz = array();    
	$sql = " select o46_codsup as chave
			      from orcsuplem
					         inner join orcprojeto on o46_codlei=o39_codproj
					         left outer join orcsuplemlan on o49_codsup = o46_codsup
			      where o46_codlei=$o48_retificado and
				            o49_codsup is not null
				           ";				           
	$res = $clorcsuplem->sql_record($sql);	
	if (($clorcsuplem->numrows) > 0) {
		for ($i = 0; $i < pg_numrows($res); $i ++) {
			db_fieldsmemory($res, $i);
			$matriz[$i] = $chave;
		}
	}	
	if (!isset($matriz[0]) || $matriz[0] == "") {
		$erro = true;
		db_msgbox("Projeto retificado $o48_retificado com diferenças na estrutura !  Verifique ! ");	  		
	}	
	if ($erro==false){
	  for ($i = 0; $i < sizeof($matriz); $i ++) {
 		   $o46_codsup = $matriz[$i];
           $estorno = true; 
		   $teste = processa_suplementacao($o46_codsup, $data, $usuario,$estorno);
	  	   if ($teste != false) {
	 	 	  $erro = true;
		   }
		  /* *
		   *   Para variáveis boleanas o php retorna vazio para resultados 'false' e retorna '1' para resultados 'true' 
		   */
	    }
	} 	
	if ($erro==false){
		// agora gravamos a tabela orcsuplemretif
		//$clorcsuplemretif
		$clorcsuplemretif->o48_id_usuario = db_getsession("DB_id_usuario");	
		$clorcsuplemretif->incluir("");
		if ($clorcsuplemretif->erro_status==0){
			db_msgbox($clorcsuplemretif->erro_msg);
			$erro=true;
		}	
	}	
	db_fim_transacao($erro);
	if ($erro==false){
		  db_redireciona();
	}
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" width="100%" align="center" valign="top" bgcolor="#CCCCCC">
    
	<?


include ("forms/db_frmorcsuplemretif.php");
?>
    
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($incluir)) {
	if ($clorcsuplemretif->erro_status == "0") {
		$clorcsuplemretif->erro(true, false);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if ($clorcsuplemretif->erro_campo != "") {
			echo "<script> document.form1.".$clorcsuplemretif->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clorcsuplemretif->erro_campo.".focus();</script>";
		};
	} else {
		$clorcsuplemretif->erro(true, true);
	};
};
?>