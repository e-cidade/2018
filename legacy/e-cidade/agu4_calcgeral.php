<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);
$aAnos = array(
	db_getsession('DB_anousu') => db_getsession('DB_anousu')
);

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="document.form1.anousu.focus();" >
	<div class="container">
		<form name="form1" action="" method="post" onSubmit="return js_verificacalculo();">
			<fieldset>
				<legend>Cálculo Geral</legend>
				<table>
					<tr>
						<td><label for="anousu" class="bold">Ano:</label></td>
						<td><?php db_select("anousu", $aAnos, true, 1); ?></td>
					</tr>

					<tr>
						<td><label for="parc_ini" class="bold">Parcela Inicial:</label></td>
						<td><?php db_select("parc_ini", DBDate::getMesesExtenso(), true, 1); ?></td>
					</tr>

					<tr>
						<td><label for="parc_fim" class="bold">Parcela Final:</label></td>
						<td><?php db_select("parc_fim", DBDate::getMesesExtenso(), true, 1); ?></td>
					</tr>
				</table>
			</fieldset>

			<input name="calcular"  type="submit" id="calcular" value="Calcular">
		</form>
	</div>

	<?php db_menu(); ?>

	<?php

	try {

		if (!empty($oPost->calcular)) {

			if ($oPost->parc_fim < $oPost->parc_ini) {
				throw new Exception("Parcela Final não pode ser menor que a Parcela Inicial.");
			}

			$oDaoAguaBase = new cl_aguabase();
			$sSql         = $oDaoAguaBase->sql_query_matriculas_ativas('distinct x01_matric');
			$rsAguaBase   = $oDaoAguaBase->sql_record($sSql);

			if (!$rsAguaBase) {
				throw new DBException("Não foi possível encontrar informações das Matrículas.");
			}

			$iTotalMatriculas = pg_numrows($rsAguaBase);
			if ($iTotalMatriculas == 0) {
				throw new DBException("Nenhuma Matrícula foi encontrada para realizar o calculo.");
			}

			db_criatermometro('termometro', 'Concluido...', 'blue', 1);

			for ($iMatricula = 0; $iMatricula < $iTotalMatriculas; $iMatricula++) {

				$oMatricula = db_utils::fieldsMemory($rsAguaBase, $iMatricula);
				db_atutermometro($iMatricula, $iTotalMatriculas, 'termometro');

				for ($iMes = $oPost->parc_ini; $iMes <= $oPost->parc_fim; $iMes++) {

					db_query("BEGIN;");
					db_query("SELECT fc_putsession('__status_tg_arreold_atu', 'disable');");
					db_query("SELECT fc_agua_calculoparcial({$oPost->anousu}, {$iMes}, {$oMatricula->x01_matric}, 2, true, true) ;");
					db_query("SELECT fc_putsession('__status_tg_arreold_atu', 'enable');");
					db_query("COMMIT;");
				}
			}

			db_msgbox("Cálculo Geral realizado com sucesso.");
		}

	} catch (Exception $exception) {
		db_msgbox($exception->getMessage());
	}
?>
</body>
</html>
<script>

  function js_verificacalculo() {

    var oImplantacaoTarifa = new Date(2017, 6);
    var iMesInicial, iMesFinal, iAno;

    iMesInicial = $F('parc_ini');
    iMesFinal   = $F('parc_fim');
    iAno        = $F('anousu');

    if (Number(iMesFinal) < Number(iMesInicial)) {
      alert('Parcela Final não pode ser menor que a Parcela Inicial.');
      return false;
    }

    var oPeriodoInicial = new Date(iAno, iMesInicial - 1);
    var oPeriodoFinal = new Date(iAno, iMesFinal - 1);
    if (oPeriodoInicial >= oImplantacaoTarifa || oPeriodoFinal >= oImplantacaoTarifa) {

      var mensagemAviso = "Não é possível executar o cálculo de taxas nesta rotina a partir do período de Julho/2017. \n\n";
      mensagemAviso    += "Para executar o cálculo de tarifas utilize a rotina:\nProcedimentos > Cálculo de Tarifas > Cálculo Geral";
      alert(mensagemAviso);
      return false;
    }
    return true;
  }
</script>
