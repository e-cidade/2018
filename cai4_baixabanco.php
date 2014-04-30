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
db_postmemory($HTTP_POST_VARS);
$situacao = "";
if (isset ($processar))
{
	// recebe codbco,codage,tamanho
	// verifica banco e agencia
	db_postmemory($_FILES["arqret"]);
	$arq_name = basename($name);
	$arq_type = $type;
	$arq_tmpname = basename($tmp_name);
	$arq_size = $size;
	$arq_array = file($tmp_name);

	system("cp -f ".$tmp_name." ".$DOCUMENT_ROOT."/tmp");

	$result = pg_exec("select k15_codbco,k15_codage, k15_taman
	                     from cadban
	                     where k15_codbco = $k15_codbco and 
						       k15_codage  = '$k15_codage'");

	if (pg_numrows($result) == 0)
	{
		echo "Banco / Agencia nao cadastrados.";

		exit;
	}
	db_fieldsmemory($result, 0);
	if (strlen($arq_array[0]) != $k15_taman)
	{
		echo "Tamanho do registro [".strlen($arq_array[0])."] Sistema : [".$k15_taman."] Inválido.";
		exit;
	}
	$acodbco = substr($arq_array[0], substr($posbco, 0, 3), substr($posbco, 3, 3));
	$acodage = substr($arq_array[0], substr($posage, 0, 3), substr($posage, 3, 3));

	if ($codbco != $acodbco)
	{
		echo "Arquivo nao pertence ao Banco especificado.";
	}

	if ($codage != $acodage)
	{
		echo "Arquivo nao pertence a Agencia especificada.";
	}
	$totalproc = sizeof($arq_array) - 2;
	$situacao = 1;
} else
	if (isset ($geradisbanco))
	{
		$situacao = 2;
		$result = pg_exec("select k15_codbco,k15_codage,k15_posbco,k15_poslan,k15_pospag,k15_posvlr,k15_posacr,k15_posdes,k15_posced,k15_poscon,k15_posjur,k15_posmul, k15_taman, k15_posdta
		                     from cadban
		                     where k15_codbco = $k15_codbco and 
							       k15_codage  = '$k15_codage'");
		db_fieldsmemory($result, 0);
		$arq_array = file($DOCUMENT_ROOT."/tmp/".$arqret);
	}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="forms/scripts/scripts.js"></script>
<link href="forms/estilos.css" rel="stylesheet" type="text/css">
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
<?



if ($situacao == "")
{
	include ("forms/forms/db_caiarq001.php");
} else
	if ($situacao == 1)
	{
		include ("forms/forms/db_caiarq002.php");
	} else
		if ($situacao == 2)
		{
			include ("forms/forms/db_caiarq003.php");
		}
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if ($situacao == 2)
{

	echo "<script>
	        function js_termometro(xvar){
			document.form1.processa.value = xvar;
	        }
	        </script>";

	flush();
	// grava arquivo disarq
	pg_exec("begin");

	$dtarquivo = substr($arq_array[0], substr($k15_posdta, 0, 3) - 1, substr($k15_posdta, 3, 3));

	$result = pg_exec("select nextval('disarq_codret_seq') as codret");
	db_fieldsmemory($result, 0);
	$result = pg_exec("insert into disarq (codret ,k15_codbco ,k15_codage   ,arqret   ,dtretorno,id_usuario, dtarquivo)
	                                 values ($codret,$k15_codbco,'$k15_codage','$arqret','".date('Y-m-d', db_getsession("DB_datausu"))."',".db_getsession("DB_id_usuario").",'".$dtarquivo."')");

	for ($i = 1; $i <= $totalproc; $i ++)
	{
		// grava arquivo disbanco

		$numbco = substr($arq_array[$i], substr($k15_posbco, 0, 3) - 1, substr($k15_posbco, 3, 3));
		$dtarq = substr($arq_array[$i], substr($k15_poslan, 0, 3) - 1, substr($k15_poslan, 3, 3));
		$dtpago = substr($arq_array[$i], substr($k15_pospag, 0, 3) - 1, substr($k15_pospag, 3, 3));
		$vlrpago = substr($arq_array[$i], substr($k15_posvlr, 0, 3) - 1, substr($k15_posvlr, 3, 3));
		$vlrjuros = substr($arq_array[$i], substr($k15_posjur, 0, 3) - 1, substr($k15_posjur, 3, 3));
		$vlrmulta = substr($arq_array[$i], substr($k15_posmul, 0, 3) - 1, substr($k15_posmul, 3, 3));
		$vlracres = substr($arq_array[$i], substr($k15_posacr, 0, 3) - 1, substr($k15_posacr, 3, 3));
		$vlrdesco = substr($arq_array[$i], substr($k15_posdes, 0, 3) - 1, substr($k15_posdes, 3, 3));
		$vlrabat = substr($arq_array[$i], substr($k15_poscon, 0, 3) - 1, substr($k15_poscon, 3, 3));
		$cedente = substr($arq_array[$i], substr($k15_posced, 0, 3) - 1, substr($k15_posced, 3, 3));

		/*    echo "<script>";
		    echo "js_termometro($i);";
			echo "alert('registro: $i ');";
		    echo "alert('codret: $codret');";
		    echo "alert('codage: $codage');";
		    echo "alert('numbco: $numbco');";
		    echo "alert('dtarq: $dtarq');";
		    echo "alert('dtpago: $dtpago');";
		    echo "alert('vlrpago: $vlrpago');";
		    echo "alert('vlrjuros: $vlrjuros');";
		    echo "alert('vlrmulta: $vlrmulta');";
		    echo "alert('vlracres: $vlracres');";
		    echo "alert('vlrdesco: $vlrdesco');";
		    echo "alert('vlrabat: $vlrabat');";
		    echo "alert('cedente: $cedente');</script>";
			exit;
		*/
		$result = pg_exec("select nextval('disbanco_idret_seq') as idret");
		db_fieldsmemory($result, 0);

		$result = pg_exec(" insert into disbanco
		                       (codret,idret,k15_codbco,k15_codage,k00_numbco,dtarq,dtpago,vlrpago,
		                        vlrjuros,vlrmulta,vlracres,vlrdesco,cedente)
							 values 
							   ($codret,$idret,$k15_codbco,'$k15_codage','$numbco','$dtarq','$dtpago',($vlrpago)/100::float8,
							   ($vlrjuros)/100::float8,($vlrmulta)/100::float8,($vlracres)/100::float8,($vlrdesco+$vlrabat)/100::float8, '$cedente')");
		echo "<script>js_termometro(".$i.");</script>";

		flush();

	}
	echo "<script>alert('Documento processado.');location.href='cai4_baixabanco.php';</script>";
	pg_exec("end");
}
?>