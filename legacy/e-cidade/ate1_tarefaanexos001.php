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
include ("classes/db_tarefaanexos_classe.php");
include ("classes/db_tarefa_classe.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltarefaanexos = new cl_tarefaanexos;
$cltarefa = new cl_tarefa;
$db_opcao = 22;
$db_botao = false;
if (isset ($alterar) || isset ($excluir) || isset ($incluir)) {
	$sqlerro = false;
	/*
	$cltarefaanexos->at25_sequencial = $at25_sequencial;
	$cltarefaanexos->at25_tarefa = $at25_tarefa;
	$cltarefaanexos->at25_anexo = $at25_anexo;
	*/
}
if (isset ($incluir)) {
	if ($sqlerro == false) {
		db_inicio_transacao();
		$nomearq = $_FILES["anexoarq"]["name"];
		$nometmp = $_FILES["anexoarq"]["tmp_name"];
		$anexoarq = $nometmp;
		$localrecebeanexo = $anexoarq;
		if ($sqlerro == false && trim($localrecebeanexo) != "") {
			$arquivograva = fopen($localrecebeanexo, "rb");
			if ($arquivograva == false) {
				//echo "erro aruivograva";
				//exit;
			}
			$dados = fread($arquivograva, filesize($localrecebeanexo));
			if ($dados == false) {
				//echo "erro fread";
				//exit;
			}
			fclose($arquivograva);
			$oidgrava = pg_lo_create();
			if ($oidgrava == false) {
				// echo "erro pg_lo_create";
				//exit;
			}
			$cltarefaanexos->at25_anexo = $oidgrava;
			$cltarefaanexos->at25_nomearq = $nomearq;
			$cltarefaanexos->at25_tarefa = $at25_tarefa;
			$cltarefaanexos->at25_data = date("Y-m-d", db_getsession("DB_datausu"));
			$cltarefaanexos->at25_hora = db_hora();
			$cltarefaanexos->at25_obs = @$at25_obs;
			$cltarefaanexos->at25_usuario = db_getsession("DB_id_usuario");
			$cltarefaanexos->incluir($at25_sequencial);
			$erro_msg = $cltarefaanexos->erro_msg;
			if ($cltarefaanexos->erro_status == 0) {
				$sqlerro = true;
			}
			$objeto = pg_lo_open($conn, $oidgrava, "w");
			if ($objeto != false) {
				$erro = pg_lo_write($objeto, $dados);
				if ($erro == false) {
					//echo "erro pg_lo_write";
					//exit;
				}
				pg_lo_close($objeto);
			} else {
				$erro_msg ("Operação Cancelada!!");
				$sqlerro = true;
			}
		}
		db_fim_transacao($sqlerro);

	}
} else if (isset ($alterar)) {
	if ($sqlerro == false) {
		db_inicio_transacao();
		$result_oidexc = $cltarefaanexos->sql_record($cltarefaanexos->sql_query_file($at25_sequencial,"at25_anexo as oid_exc"));
		db_fieldsmemory($result_oidexc,0);
		$cltarefaanexos->excluir($at25_sequencial);
		$erro_msg = $cltarefaanexos->erro_msg;
		if ($cltarefaanexos->erro_status == 0) {
			$sqlerro = true;
		} else {
			pg_lo_unlink($conn, $oid_exc);
			$nomearq = $_FILES["anexoarq"]["name"];
			$nometmp = $_FILES["anexoarq"]["tmp_name"];
			$anexoarq = $nometmp;
			$localrecebeanexo = $anexoarq;
			if ($sqlerro == false && trim($localrecebeanexo) != "") {
				$arquivograva = fopen($localrecebeanexo, "rb");
				if ($arquivograva == false) {
					//echo "erro aruivograva";
					//exit;
				}
				$dados = fread($arquivograva, filesize($localrecebeanexo));
				if ($dados == false) {
					//echo "erro fread";
					//exit;
				}
				fclose($arquivograva);
				$oidgrava = pg_lo_create();
				if ($oidgrava == false) {
					// echo "erro pg_lo_create";
					//exit;
				}
				$cltarefaanexos->at25_anexo = $oidgrava;
				$cltarefaanexos->at25_nomearq = $nomearq;
				$cltarefaanexos->at25_obs = @$at25_obs;
				$cltarefaanexos->at25_tarefa = $at25_tarefa;
				$cltarefaanexos->at25_data = date("Y-m-d", db_getsession("DB_datausu"));
				$cltarefaanexos->at25_hora = db_hora();
				$cltarefaanexos->at25_usuario = db_getsession("DB_id_usuario");
				$cltarefaanexos->incluir($at25_sequencial);
				$erro_msg = $cltarefaanexos->erro_msg;
				if ($cltarefaanexos->erro_status == 0) {
					$sqlerro = true;
				}
				$objeto = pg_lo_open($conn, $oidgrava, "w");
				if ($objeto != false) {
					$erro = pg_lo_write($objeto, $dados);
					if ($erro == false) {
						//echo "erro pg_lo_write";
						//exit;
					}
					pg_lo_close($objeto);
				} else {
					$erro_msg ("Operação Cancelada!!");
					$sqlerro = true;
				}
			}
		}
		db_fim_transacao($sqlerro);
	}
} else if (isset ($excluir)) {
	if ($sqlerro == false) {
		db_inicio_transacao();
		$result_oidexc = $cltarefaanexos->sql_record($cltarefaanexos->sql_query_file($at25_sequencial,"at25_anexo as oid_exc"));
		db_fieldsmemory($result_oidexc,0);
		$cltarefaanexos->excluir($at25_sequencial);
		$erro_msg = $cltarefaanexos->erro_msg;
		if ($cltarefaanexos->erro_status == 0) {
			$sqlerro = true;
		} else {
			pg_lo_unlink($conn, $oid_exc);
		}
		db_fim_transacao($sqlerro);
	}
} else if (isset ($opcao)) {
	$result = $cltarefaanexos->sql_record($cltarefaanexos->sql_query($at25_sequencial));
	if ($result != false && $cltarefaanexos->numrows > 0) {
		db_fieldsmemory($result, 0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?


include ("forms/db_frmtarefaanexos.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?


if (isset ($alterar) || isset ($excluir) || isset ($incluir)) {
	db_msgbox($erro_msg);
	if ($cltarefaanexos->erro_campo != "") {
		echo "<script> document.form1.".$cltarefaanexos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		echo "<script> document.form1.".$cltarefaanexos->erro_campo.".focus();</script>";
	}
}
?>