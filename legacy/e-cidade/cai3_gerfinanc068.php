<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
//echo "<br>$cgm<br>";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<form name ="form1">
  <input name="cgm" value="<?=@$cgm?>" type = "hidden">
  <input name="data1" value="<?=@$data1?>" type = "hidden">
  <input name="data2" value="<?=@$data2?>" type = "hidden">
</form>

<script>
  function js_emiterelatorio() {
    var data1 = document.form1.data1.value;
    var data2 = document.form1.data2.value;
    var cgm   = document.form1.cgm.value;
    window.open('cai3_gerfinanc069.php?&data1='+data1+'&data2='+data2+'&cgm='+cgm, 'Relatorio', 'location=0');
  }
</script>

<?
//echo "$data1 <br>$data2<br>cgm = $cgm<br>";

if (($data1 !="--") && ($data2!="--")){
	$where = " and q21_dataop>='$data1' and q21_dataop<='$data2' ";
}else{
	$where="";
}
$sqlprestador = "
				select      q20_numcgm as dl_cgm,
				            cgmtomador.z01_nome as dl_tomador,
				            q20_mes,
				            q20_ano,
				            q21_nota,
				            q21_serie,
				            q21_valorser,
				            q21_aliq,
				            q21_valor,
					          q21_dataop,
                    q20_numpre,
                    case when (select dtpago from arreidret inner join disbanco on disbanco.idret = arreidret.idret where arreidret.k00_numpre = q20_numpre and arreidret.k00_numpar = q20_mes) is not null then (select dtpago from arreidret inner join disbanco on disbanco.idret = arreidret.idret where arreidret.k00_numpre = q20_numpre and arreidret.k00_numpar = q20_mes) else (select min(k00_dtpaga) from arrepaga where k00_numpre = q20_numpre) end as k00_dtpaga,
                    (select min(k00_conta) from arrepaga where k00_numpre = q20_numpre) as k00_conta
				from cgm
				inner join issplanit      on cgm.z01_cgccpf = q21_cnpj
				inner join issplan        on q20_planilha   = q21_planilha
				inner join cgm cgmtomador on q20_numcgm     = cgmtomador.z01_numcgm
				where cgm.z01_numcgm= $cgm and q20_numcgm <> cgm.z01_numcgm and q20_situacao <> 5 and q21_status = 1
					$where
				";

$resultprestador = db_query($sqlprestador);
$linhasprestador=pg_num_rows($resultprestador);
	if($linhasprestador>0){
		echo "<center>";

		$total["q21_valorser"] = "q21_valorser";
		$total["q21_valor"]    = "q21_valor";
		$total["totalgeral"]   = "dl_tomador";


    $repassa["data1"] = $data1;
    $repassa["data2"] = $data2;
    $repassa["cgm"]   = $cgm;
		db_lovrot($sqlprestador,15,"","",'return false;',"","NoMe", $repassa,false, $total);
		?><input name="imprime" type="button" value="Imprimir" onclick="js_emiterelatorio();">
        </center>
        <?
	}else{
		echo "<center>";
		echo "<br><br><br><b><font color = red >NÃO TEM RETENÇÃO COMO PRESTADOR PARA ESTE PERÍODO</font><?b>";
        echo "</center>";

	}

?>
</body>
</html>
