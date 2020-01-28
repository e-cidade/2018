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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/censo/DadosCenso.model.php");
require_once("classes/db_cursoedu_classe.php");
require_once("model/CgmFactory.model.php");

$oGet          = db_utils::postMemory($_GET);
$oDaoRecHumano = new cl_rechumano();

$sSqlMovimentacao = $oDaoRecHumano->sql_query_movimentacao_professor_cgm($oGet->cgm);
$rsMovimentacao   = $oDaoRecHumano->sql_record($sSqlMovimentacao);
$iRegistro        = $oDaoRecHumano->numrows;

$lSemMovimento  = false;

if ($iRegistro == 0) {
	$lSemMovimento = true;
}

$aMovimentos = array();

for ($i = 0; $i < $iRegistro; $i++) {

	$oMovimento = db_utils::fieldsMemory($rsMovimentacao, $i);

	if ($oMovimento->tipo == 'A') {

		$oAusencia            = new AusenciaDocente($oMovimento->codigo);
		$oMovimento->dtInicio = $oAusencia->getDataInicial()->getDate(DBDate::DATA_PTBR);

		$oMovimento->dtFinal  = '';
		if ($oAusencia->getDataFinal() != null) {
			$oMovimento->dtFinal  = $oAusencia->getDataFinal()->getDate(DBDate::DATA_PTBR);
		}

		$sTipo = "Ausência ";
    if ( $oAusencia->getTipoAusencia()->isLicenca() ) {
      $sTipo = "Licença ";
    }

		$sMsg  = "{$sTipo} -  Tipo: {$oAusencia->getTipoAusencia()->getDescricao()}";
		if ($oAusencia->getObservacao() != '') {
			$sMsg .= " Observação: {$oAusencia->getObservacao()}";
		}

		$oMovimento->sMessage = $sMsg;

	} elseif ($oMovimento->tipo == 'S') {

		$oSubstituicao        = new DocenteSubstituto($oMovimento->codigo);
		$oMovimento->dtInicio = $oSubstituicao->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
		$oMovimento->dtFinal  = '';
		if ($oSubstituicao->getPeriodoFinal() != null) {
			$oMovimento->dtFinal  = $oSubstituicao->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);
		}

		$sTipo = $oSubstituicao->getTipoVinculo() == 2 ? "PERMANENTE" : "TEMPORARIO";

		$sMsg  = "Professor Substituido : {$oSubstituicao->getAusente()->getDocente()->getProfessor()->getNome()}, ";
		$sMsg .= "Disciplina: {$oSubstituicao->getRegencia()->getDisciplina()->getNomeDisciplina()} ";
		$sMsg .= "Substituição: {$sTipo}";

		$oMovimento->sMessage = $sMsg;
	}
	$aMovimentos[] = $oMovimento;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.titulo {
  font-size: 11px;
  text-align: center;
  color: #DEB887;
  background-color:#444444;
  font-weight: bold;
  border: 1px solid #f3f3f3;
}

.aluno {
  color: #000000;
  font-family : Verdana;
  font-size: 10px;
}
.bold {
	font-weight: bold;
}
</style>
</head>
<body style=" background-color: #f3f3f3">
	<fieldset style="border: 2px solid">
		<legend class='bold'>Ausências / Licenças / Substituições</legend>
		<table bgcolor="#f3f3f3" border='1' style="width: 100%" cellspacing="0" cellpading="0">
			<tr class="titulo">
				<td>
					Data Inicial
				</td>
				<td>
					Data Final
				</td>
				<td>
					Ação Executada
				</td>
			</tr>
			<?php
			foreach ($aMovimentos as $oMovimento) {

				echo "<tr class='aluno'>";
				echo "  <td>";
				echo "    {$oMovimento->dtInicio} ";
				echo "  </td>";
				echo "  <td>";
				echo "    {$oMovimento->dtFinal} ";
				echo "  </td>";
				echo "  <td>";
				echo "    {$oMovimento->sMessage} ";
				echo "  </td>";
				echo "</tr>";
			}
			?>
		</table>
	</fieldset>
</body>
</html>