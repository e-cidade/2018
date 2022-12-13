<?
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("classes/db_orccenarioeconomicoparam_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/cronogramaFinanceiro.model.php"));
require_once(modification("model/relatorioContabil.model.php"));

$oGet	   = db_utils::postMemory($_GET);

// Código do Relatório
$iCodRel = $oGet->iCodRel;
// Array com os recursos selecionados

if ($oGet->slistaRecursos == '') {
	$aMRecursos = null;
} else {
	$aMRecursos = explode(',',$oGet->slistaRecursos);
}

// Lista das instituições selecionadas
$sListaInstit = str_replace('-', ',', $oGet->sListaInstit);

$cldb_config                = new cl_db_config;
$oRelatorioContabil         = new relatorioContabil($iCodRel);
$clcronogramaFinanceiro			= new cronogramaFinanceiro($oGet->iRec);
$clcronogramaFinanceiro->setInstituicoes(explode("-", $oGet->sListaInstit));

try {
	$aReceitas = $clcronogramaFinanceiro->getMetasReceita(null, $aMRecursos);
} catch (Exception $erro) {
	db_redireciona('db_erros.php?fechar=true&db_erro='.$erro->getMessage());
}

if ($oGet->iPeriodoImpr == 1 && $oGet->iFormaImpr == 1) {

	//Imprime por receita e mensal
	$descricao 	= "Receita";
	$descricao1 = "Mensal";

	$head2      = "Metas Mensais de Arrecadação";
  $head3      = "Art. 13, da Lei Complementar 101/2000";
	$head4      = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
	$head5      = "Valores expressos por conta de receita";

	$aRelatorio       = array();
	$aRelatorioTotais = array();
	$iNumRows         = count($aReceitas);
	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

		$aRelatorio[$iInd] = new stdClass();
		$aRelatorio[$iInd]->o70_codigo = $aReceitas[$iInd]->o57_fonte;
		$aRelatorio[$iInd]->o57_fonte  = $aReceitas[$iInd]->o57_fonte;
		$aRelatorio[$iInd]->o57_descr  = substr(urldecode($aReceitas[$iInd]->o57_descr),0,35);
		$iNumRowsDados                 = count($aReceitas[$iInd]->aMetas->dados);

		for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {

			$aRelatorio[$iInd]->aMetas->dados[$jInd]->valor = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
			if (!empty($aReceitas[$iInd]->o70_codigo)) {

				if (array_key_exists($jInd,$aRelatorioTotais)) {
					$aRelatorioTotais[$jInd] += $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
				} else {
					$aRelatorioTotais[$jInd]  = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
				}

			}

			$aRelatorio[$iInd]->aMetas->getValues = round($aReceitas[$iInd]->o70_valor, 2);
		}
	}
} else if($oGet->iPeriodoImpr == 1 && $oGet->iFormaImpr == 2) {

	$descricao 	= "Recursos";
	$descricao1 = "Mensal";
	$head2      = "Metas Mensais de Arrecadação";
  $head3      = "Art. 13, da Lei Complementar 101/2000";
	$head4      = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
	$head5      = "Valores expressos por recurso";

	$aRelatorio 			= array();
	$aRelatorioTotais = array();

	for ($jInd = 0; $jInd < 12; $jInd++) $aRelatorioTotais[$jInd] = 0;

	$iNumRows = count($aReceitas);
	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

		if (empty($aReceitas[$iInd]->o70_codigo)) {
			continue;
		}

		if (array_key_exists($aReceitas[$iInd]->o70_codigo, $aRelatorio)) {

			$iNumRowsDados                                                 = count($aReceitas[$iInd]->aMetas->dados);
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->getValues += round($aReceitas[$iInd]->o70_valor, 2);
			for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {

				$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->dados[$jInd]->valor += $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
				if (!empty($aReceitas[$iInd]->o70_codigo)) {
				 $aRelatorioTotais[$jInd] += $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
				}
			}
		} else {

			$aRelatorio[$aReceitas[$iInd]->o70_codigo] = new stdClass();
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o70_codigo = $aReceitas[$iInd]->o70_codigo;
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o57_fonte  = $aReceitas[$iInd]->o57_fonte;
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o57_descr  = substr(urldecode($aReceitas[$iInd]->o15_descr),0,35);
			$iNumRowsDados                                         = count($aReceitas[$iInd]->aMetas->dados);

			for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {

				$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->dados[$jInd]->valor = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor;
				$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->getValues           = round($aReceitas[$iInd]->o70_valor, 2);
				if (!empty($aReceitas[$iInd]->o70_codigo)) {
				  $aRelatorioTotais[$jInd] += $aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->dados[$jInd]->valor;
				}
			}
		}
	}
} else if($oGet->iPeriodoImpr == 2 && $oGet->iFormaImpr == 1) {

	//Imprime por receita e bimestral
	$descricao 	= "Receita";
	$descricao1 = "Bimestral";

	$head2      = "Metas Bimestrais de Arrecadação";
  $head3      = "Art. 13, da Lei Complementar 101/2000";
	$head4      = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
	$head5      = "Valores expressos por conta de receita";

	$aRelatorio       = array();
	$aRelatorioTotais = array();

	for ($iInd = 0; $iInd < 6; $iInd++) $aRelatorioTotais[$iInd] = 0;

	$iNumRows = count($aReceitas);
	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

		$aRelatorio[$iInd] = new stdClass();
		$aRelatorio[$iInd]->o70_codigo        = $aReceitas[$iInd]->o57_fonte;
		$aRelatorio[$iInd]->o57_fonte	        = $aReceitas[$iInd]->o57_fonte;
		$aRelatorio[$iInd]->o57_descr         = substr(urldecode($aReceitas[$iInd]->o57_descr),0,35);
		$iNumRowsDados                        = count($aReceitas[$iInd]->aMetas->dados);
		$aRelatorio[$iInd]->aMetas->getValues = round($aReceitas[$iInd]->o70_valor, 2);
		$indice                               = 0;

		for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {

			if ($jInd%2==0 || $jInd == 0) {

				$aRelatorio[$iInd]->aMetas->dados[$indice]->valor = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor +
				                                                    $aReceitas[$iInd]->aMetas->dados[$jInd+1]->valor;
				if (!empty($aReceitas[$iInd]->o70_codigo)) {
				  $aRelatorioTotais[$indice] += $aRelatorio[$iInd]->aMetas->dados[$indice]->valor;
				}

				$indice++;
			}
		}
	}
} else if ($oGet->iPeriodoImpr == 2 && $oGet->iFormaImpr == 2) {

	//Imprime por recurso e bimestral
	$descricao 	= "Recursos";
	$descricao1 = "Bimestral";

	$head2      = "Metas Bimestrais de Arrecadação";
  $head3      = "Art. 13, da Lei Complementar 101/2000";
	$head4      = "Orçamento do exercício de {$clcronogramaFinanceiro->getAno()}";
	$head5      = "Valores expressos por recurso";

	$aRelatorio 			= array();
	$aRelatorioTotais = array();

	for ($iInd = 0; $iInd < 6; $iInd++) $aRelatorioTotais[$iInd] = 0;

	$iNumRows = count($aReceitas);
	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

  	if (empty($aReceitas[$iInd]->o70_codigo)) {
      continue;
    }

		if (array_key_exists($aReceitas[$iInd]->o70_codigo, $aRelatorio)) {

			$iNumRowsDados = count($aReceitas[$iInd]->aMetas->dados);
			$indice        = 0;
			for($jInd = 0; $jInd < ($iNumRowsDados); $jInd++) {

				if ($jInd%2==0 || $jInd==0) {

					$soma = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor + $aReceitas[$iInd]->aMetas->dados[$jInd+1]->valor;
					$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->dados[$indice]->valor += $soma;
					$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->getValues             += round($soma, 2);
					if (!empty($aReceitas[$iInd]->o70_codigo)) {
					  $aRelatorioTotais[$indice] += $soma;
					}

					$indice++;
				}
			}
		} else {

			$aRelatorio[$aReceitas[$iInd]->o70_codigo] 						 = new stdClass();
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o70_codigo = $aReceitas[$iInd]->o70_codigo;
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o57_fonte  = $aReceitas[$iInd]->o57_fonte;
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->o57_descr  = substr(urldecode($aReceitas[$iInd]->o15_descr),0,35);

			$iNumRowsDados = count($aReceitas[$iInd]->aMetas->dados);
			$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->getValues = 0;
			$indice = 0;

			for ($jInd = 0; $jInd < ($iNumRowsDados); $jInd++) {

				if ($jInd%2==0 || $jInd==0) {

					$soma = $aReceitas[$iInd]->aMetas->dados[$jInd]->valor + $aReceitas[$iInd]->aMetas->dados[$jInd+1]->valor;
					$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->dados[$indice]->valor  = $soma;
					$aRelatorio[$aReceitas[$iInd]->o70_codigo]->aMetas->getValues 						 += round($soma, 2);
					if (!empty($aReceitas[$iInd]->o70_codigo)) {
					  $aRelatorioTotais[$indice] += $soma;
					}

					$indice++;
				}
			}
		}
	}
}

$oRelatorio = new stdClass();

$oRelatorio->linha                  = array();
$oRelatorio->linha[0]->descricao 		= $descricao;
$oRelatorio->linha[0]->tamanho 			= 86;
$oRelatorio->linha[1]->descricao 		= $descricao1;
$oRelatorio->linha[1]->tamanho 			= 194;

if ($oGet->iPeriodoImpr==1) {

	$tamanho	                 =	26;
	$aCabecalho                = array();
	$aCabecalho[0]->descricao  = "Estrutural";
	$aCabecalho[0]->tamanho 	 = 26;
	$aCabecalho[1]->descricao  = "Descricao";
	$aCabecalho[1]->tamanho 	 = 60;
	$aCabecalho[2]->descricao  = "Janeiro";
	$aCabecalho[2]->tamanho 	 = $tamanho;
	$aCabecalho[3]->descricao  = "Fevereiro";
	$aCabecalho[3]->tamanho 	 = $tamanho;
	$aCabecalho[4]->descricao  = "Março";
	$aCabecalho[4]->tamanho 	 = $tamanho;
	$aCabecalho[5]->descricao  = "Abril";
	$aCabecalho[5]->tamanho 	 = $tamanho;
	$aCabecalho[6]->descricao  = "Maio";
	$aCabecalho[6]->tamanho 	 = $tamanho;
	$aCabecalho[7]->descricao  = "Junho";
	$aCabecalho[7]->tamanho 	 = $tamanho;
	$aCabecalho[8]->descricao  = "Julho";
	$aCabecalho[8]->tamanho 	 = $tamanho;
	$aCabecalho[9]->descricao  = "Agosto";
	$aCabecalho[9]->tamanho 	 = $tamanho;
	$aCabecalho[10]->descricao = "Setembro";
	$aCabecalho[10]->tamanho 	 = $tamanho;
	$aCabecalho[11]->descricao = "Outubro";
	$aCabecalho[11]->tamanho 	 = $tamanho;
	$aCabecalho[12]->descricao = "Novembro";
	$aCabecalho[12]->tamanho 	 = $tamanho;
	$aCabecalho[13]->descricao = "Dezembro";
	$aCabecalho[13]->tamanho 	 = $tamanho;
	$aCabecalho[14]->descricao = "Total";
	$aCabecalho[14]->tamanho 	 = 38;
	$oRelatorio->iPeriocidade  = $aCabecalho;

	$oRelatorio->linhaTotal = new stdClass();
	$oRelatorio->linhaTotal->totalDescricao = "Totalização Geral";
	$oRelatorio->linhaTotal->totalTamanho   = $aCabecalho[0]->tamanho + $aCabecalho[1]->tamanho;
} else if($oGet->iPeriodoImpr == 2) {

	$aCabecalho               = array();
	$tamanho                  = 27;
	$aCabecalho[0]->descricao = "Estrutural";
	$aCabecalho[0]->tamanho 	= 26;
	$aCabecalho[1]->descricao = "Descricao";
	$aCabecalho[1]->tamanho 	= 60;
	$aCabecalho[2]->descricao = "1º Bimestre";
	$aCabecalho[2]->tamanho 	= $tamanho;
	$aCabecalho[3]->descricao = "2º Bimestre";
	$aCabecalho[3]->tamanho 	= $tamanho;
	$aCabecalho[4]->descricao = "3º Bimestre";
	$aCabecalho[4]->tamanho 	= $tamanho;
	$aCabecalho[5]->descricao = "4º Bimestre";
	$aCabecalho[5]->tamanho 	= $tamanho;
	$aCabecalho[6]->descricao = "5º Bimestre";
	$aCabecalho[6]->tamanho 	= $tamanho;
	$aCabecalho[7]->descricao = "6º Bimestre";
	$aCabecalho[7]->tamanho 	= $tamanho;
	$aCabecalho[8]->descricao = "Total";
	$aCabecalho[8]->tamanho 	= 32;
	$oRelatorio->iPeriocidade = $aCabecalho;

	$oRelatorio->linhaTotal = new stdClass();
	$oRelatorio->linhaTotal->totalDescricao 	= "Totalização Geral";
	$oRelatorio->linhaTotal->totalTamanho   	= $aCabecalho[0]->tamanho + $aCabecalho[1]->tamanho;
}

$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit')));
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

$head1 = "MUNICÍPIO DE ".strtoupper($oConfig->munic);

$pdf = new PDF('L');
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->AutoPageBreak = false;
$iAlt = 5;

if ($oGet->iPeriodoImpr == 1) {

	$background    = 1;
	$pdf_cabecalho = true;

	foreach ($aRelatorio as $key => $value) {

		if( $aRelatorio[$key]->aMetas->getValues == 0 ) {
			continue;
		}

		if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {

			$pdf->SetFont('Arial','B',8);
			$pdf_cabecalho = false;
			$pdf->AddPage();
			$pdf->cell($oRelatorio->linha[0]->tamanho,
										 $iAlt,
										 $oRelatorio->linha[0]->descricao ,
										 'T',0,'C',1);
			$pdf->cell($oRelatorio->linha[1]->tamanho,
										 $iAlt,
										 $oRelatorio->linha[1]->descricao ,
										 'TL',1,'C',1);

			$iNumRows = count($oRelatorio->iPeriocidade);
			for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

				if (($oGet->iPeriodoImpr == 1 && $iInd == 8)) {

					$pdf->cell($oRelatorio->iPeriocidade[14]->tamanho,
									 $iAlt,
									 $oRelatorio->iPeriocidade[14]->descricao ,
									 'TL',0,'C',1);

					$pdf->Ln();
					$pdf->cell($oRelatorio->iPeriocidade[0]->tamanho,
									 $iAlt,
									 "",
									 'B',0,'C',1);
					$pdf->cell($oRelatorio->iPeriocidade[1]->tamanho,
									 $iAlt,
									 "",
									 'BL',0,'C',1);

				}

				if (($oGet->iPeriodoImpr == 1 && $iInd ==14)) {

					$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho,
										 $iAlt,
										 '',
										 'BL',0,'C',1);
				} else {

					$borda = 'LTB';

					if($iInd == 0){
						 $borda = 'TB';
					}

					$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho,
										 $iAlt,
										 $oRelatorio->iPeriocidade[$iInd]->descricao,
										 $borda,0,'C',1);
				}
			}

			$pdf->Ln();
		}

		//Fim da impressao do cabeçalho
		//Imprime dados do relatorio inicio
			$background = $background == 1 ? 0 : 1;
			$pdf->SetFont('Arial','',7);
			$pdf->cell($oRelatorio->iPeriocidade[0]->tamanho, $iAlt, '', 'RT',0,'C',$background);
			$pdf->cell($oRelatorio->iPeriocidade[1]->tamanho, $iAlt, '', 'RLT',0,'L',$background);

			$iNumRowsDados = count($aRelatorio[$key]->aMetas->dados);
			for($jInd = 0; $jInd < $iNumRowsDados; $jInd++){
				if(($oGet->iPeriodoImpr == 1 && $jInd == 6)){
					$pdf->cell($oRelatorio->iPeriocidade[14]->tamanho, $iAlt, '', 'TL',0,'C',$background);

					$pdf->Ln();
					$pdf->cell($oRelatorio->iPeriocidade[0]->tamanho, $iAlt, $aRelatorio[$key]->o70_codigo, 'BR',0,'C',$background);
					$pdf->cell($oRelatorio->iPeriocidade[1]->tamanho, $iAlt, $aRelatorio[$key]->o57_descr , 'BLR',0,'L',$background);

				}
				$pdf->cell($oRelatorio->iPeriocidade[$jInd+2]->tamanho, $iAlt, db_formatar($aRelatorio[$key]->aMetas->dados[$jInd]->valor,'f'), "BTL",0,'R',$background);

			}
			$pdf->cell($oRelatorio->iPeriocidade[$jInd+2]->tamanho, $iAlt, db_formatar($aRelatorio[$key]->aMetas->getValues,'f'), 'LB', 0, 'R', $background);

			$pdf->Ln();
	}

//Imprime a linha final das totalizações

	$iNumRows = count($oRelatorio->iPeriocidade);
	$iNumRowsTotais = count($aRelatorioTotais);
	$somaTotal = 0;
	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

		if (($oGet->iPeriodoImpr == 1 && $iInd == 8)){

					$pdf->cell($oRelatorio->iPeriocidade[14]->tamanho, $iAlt, '', 'TL',0,'C',1);
					$pdf->SetFont('Arial','B',8);
					$pdf->Ln();
					$pdf->cell($oRelatorio->linhaTotal->totalTamanho, $iAlt, $oRelatorio->linhaTotal->totalDescricao, 'RB',0,'R',1);
				}
				$pdf->SetFont('Arial','',8);
				if ($iInd == 0){
					$pdf->cell($oRelatorio->linhaTotal->totalTamanho, $iAlt, "", 'RT',0,'R',1);
				}else if($iInd > 1 && $iInd < $iNumRowsTotais+2){

					$somaTotal += $aRelatorioTotais[$iInd-2];

					$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, db_formatar($aRelatorioTotais[$iInd-2],'f'), 'LTB',0,'C',1);
				}
				if($iInd+1 == $iNumRows){
					$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, db_formatar($somaTotal,'f'),'LB',0,'R',1);
				}
	}


	$pdf->Ln();

} else if($oGet->iPeriodoImpr == 2) {

	$pdf_cabecalho = true;
	$background    = 1;

	foreach ($aRelatorio as $key=>$value){

	  if ( $aRelatorio[$key]->aMetas->getValues == 0 ) {
      continue;
    }

		if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {

			$pdf->SetFont('Arial','B',8);
			$pdf->AddPage();

			$pdf_cabecalho = false;
			$pdf->cell($oRelatorio->linha[0]->tamanho, $iAlt, $oRelatorio->linha[0]->descricao, 'TB',0,'C',1);
			$pdf->cell($oRelatorio->linha[1]->tamanho, $iAlt, $oRelatorio->linha[1]->descricao, 'TBL',1,'C',1);

			$iNumRows = count($oRelatorio->iPeriocidade);
			for($iInd=0; $iInd < $iNumRows; $iInd++){
					if($iInd == 0){
						$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, $oRelatorio->iPeriocidade[$iInd]->descricao, 'TB',0,'C',1);
					}else{
						$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, $oRelatorio->iPeriocidade[$iInd]->descricao, 'TBL',0,'C',1);
					}
			}

			$pdf->Ln();
		}

		$background = $background == 1 ? 0 : 1;
		$pdf->SetFont('Arial','',7);
			$pdf->cell($oRelatorio->iPeriocidade[0]->tamanho, $iAlt, $aRelatorio[$key]->o70_codigo, 'TB',0,'C',$background);

			$pdf->cell($oRelatorio->iPeriocidade[1]->tamanho, $iAlt, $aRelatorio[$key]->o57_descr, 'LTB',0,'L',$background);
			$iNumRowsDados = count($aRelatorio[$key]->aMetas->dados);
			for ($jInd = 0; $jInd < $iNumRowsDados; $jInd++) {
			  $pdf->cell($oRelatorio->iPeriocidade[$jInd+2]->tamanho, $iAlt, db_formatar($aRelatorio[$key]->aMetas->dados[$jInd]->valor,'f'), 1,0,'R',$background);
			}

			$pdf->cell($oRelatorio->iPeriocidade[$jInd+2]->tamanho, $iAlt, db_formatar($aRelatorio[$key]->aMetas->getValues,'f'), 'LTB',0,'R',$background);
			$pdf->Ln();
	}


//Imprime a linha final das totalizações

	$iNumRows       = count($oRelatorio->iPeriocidade);
	$iNumRowsTotais = count($aRelatorioTotais);
	$somaTotal      = 0;

	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {

		if ($iInd == 0){

			$pdf->SetFont('Arial','B',8);
			$pdf->cell($oRelatorio->linhaTotal->totalTamanho, $iAlt, $oRelatorio->linhaTotal->totalDescricao , 'TB',0,'R',1);
		  $pdf->SetFont('Arial','',7);

		} else if ($iInd > 1 && $iInd < $iNumRowsTotais+2) {

			if ($aRelatorio)
			$somaTotal += $aRelatorioTotais[$iInd-2];
			$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, db_formatar($aRelatorioTotais[$iInd-2],'f') , 'TBL',0,'R',1);
		}

		if($iInd+1 == $iNumRows){
			$pdf->cell($oRelatorio->iPeriocidade[$iInd]->tamanho, $iAlt, db_formatar($somaTotal,'f') , 'LTB',0,'R',1);
		}
	}

	$pdf->Ln();
}

$pdf->Ln();
$oRelatorioContabil->getNotaExplicativa($pdf,1);
$pdf->Output();
