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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once('libs/db_libcontabilidade.php');
require_once('libs/db_liborcamento.php');
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcparamrel_classe.php");

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoIRGF");

$classinatura       = new cl_assinatura();
$orcparamrel        = new cl_orcparamrel();
$oDaoDbConfig       = db_utils::getDao("db_config");
$oDaoPeriodo        = db_utils::getDao("periodo");

$oPost              = db_utils::postMemory($_POST);
$oGet               = db_utils::postMemory($_GET);
$iAnoUsu            = db_getsession('DB_anousu');
$sInstituicoes      = str_replace('-', ',', $oGet->db_selinstit);
$iCodigoRelatorio   = 89;

$oReltorioContabil  = new relatorioContabil($iCodigoRelatorio, false);
$oAnexoIRGF         = new AnexoIRGF($iAnoUsu, $iCodigoRelatorio, $oGet->periodo);
$oAnexoIRGF->setInstituicoes($sInstituicoes);

$aDadosAnexoIRGF    = $oAnexoIRGF->getDados();
$iNumRowsAnexoIRGF  = count($aDadosAnexoIRGF);
if ($iNumRowsAnexoIRGF == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem registros cadastrados.');
}

$sWhere             = "codigo in({$sInstituicoes})";
$sCampos            = "codigo, munic, nomeinst, nomeinstabrev, db21_tipoinstit, db21_codcli";
$sSqlDbConfig       = $oDaoDbConfig->sql_query_file(null, $sCampos, null, $sWhere);
$rsSqlDbConfig      = $oDaoDbConfig->sql_record($sSqlDbConfig);
$INumRowsDbConfig   = $oDaoDbConfig->numrows;

$sSqlDadosPeriodo   = $oDaoPeriodo->sql_query_file($oGet->periodo);
$rsPeriodo          = db_query($sSqlDadosPeriodo);
$oPeriodo           = db_utils::fieldsMemory($rsPeriodo, 0);

$sDescricaoInstituicao = '';
$sVirgula              = '';
$lTemPrefeitura        = false;
$lTemCamara            = false;
$lTemAdminD            = false;
$lTemMinisterio        = false;
$lFlagAbrev            = false;

/**
 * Percorre as institui��es para montar os header do relat�rio.
 */
for ($iInd = 0; $iInd < $INumRowsDbConfig; $iInd++) {

  $oMunicipio = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);

  if (strlen(trim($oMunicipio->nomeinstabrev)) > 0) {

    $sDescricaoInstituicao .= $sVirgula.$oMunicipio->nomeinstabrev;
    $lFlagAbrev  = true;
  } else {
    $sDescricaoInstituicao .= $sVirgula.$oMunicipio->nomeinst;
  }

  $sVirgula = ', ';
  if ($oMunicipio->db21_tipoinstit == 1) {
    $lTemPrefeitura = true;
  } elseif ($oMunicipio->db21_tipoinstit == 2) {
    $lTemCamara = true;
  } elseif ($oMunicipio->db21_tipoinstit == 5 || $oMunicipio->db21_tipoinstit == 7) {
    $lTemAdminD = true;
  } elseif ($oMunicipio->db21_tipoinstit == 101) {
    $lTemMinisterio = true;
  }
}

/**
 * Verifica se � preciso abreviar o nome da institui��o.
 */
if ($lFlagAbrev == false) {

  if (strlen($sDescricaoInstituicao) > 42) {
    $sDescricaoInstituicao = substr($sDescricaoInstituicao, 0, 100);
  }
}

if ($lTemCamara == true && ($lTemPrefeitura == true || $lTemAdminD == true)) {
  $head2 = "MUNIC�PIO DE ".strtoupper($oMunicipio->munic)." - PODERES EXECUTIVO E LEGISLATIVO";
}

if ($lTemCamara == true && $lTemPrefeitura == false && $lTemAdminD == false) {
  $head2 = "MUNIC�PIO DE ".strtoupper($oMunicipio->munic)." - PODER LEGISLATIVO";
}

if ($lTemPrefeitura == true && $lTemCamara == false && ($lTemAdminD == false || $lTemAdminD == true)) {
  $head2 = "MUNIC�PIO DE ".strtoupper($oMunicipio->munic)." - PODER EXECUTIVO/ADM. INDIRETA";
}

if ($lTemMinisterio == true && $oMunicipio->db21_codcli == 70) {
	$head2 = "ESTADO DO AMAP� - PODER EXECUTIVO";
}

/**
 * Monta os header do relat�rio.
 */
if ($lTemCamara == true && $lTemPrefeitura == false && $lTemAdminD == false) {

  $head3 = $sDescricaoInstituicao;
  $head4 = "RELAT�RIO DE GEST�O FISCAL";
  $head5 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
  $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
} else {

	if ($lTemMinisterio == true) {

		$head3 = $sDescricaoInstituicao;
    $head4 = "RELAT�RIO DE GEST�O FISCAL";
    $head5 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
    $head6 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
	} else {

	  $head3 = "RELAT�RIO DE GEST�O FISCAL";
	  $head4 = "DEMONSTRATIVO DA DESPESA COM PESSOAL";
	  $head5 = "ORCAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
	}
}

/**
 * Procura data inical do exerc�cio anterior.
 */
$dtInicialAnterior = explode("-", $oAnexoIRGF->getDataFinal());
if ($dtInicialAnterior[1] == "12") {
  $dtInicialAnterior[1] = 11;
}

$dtInicialAnterior = ($iAnoUsu-1)."-".($dtInicialAnterior[1]+1)."-01";
$dtInicial         = explode('-', $dtInicialAnterior);
$dtFinal           = explode('-', $oAnexoIRGF->getDataFinal());

/**
 * Monta a descri��o por per�odo.
 */
if ($oPeriodo->o114_sigla == "3Q" || $oPeriodo->o114_sigla == "2S"  || $oPeriodo->o114_sigla == "DEZ") {

  $sDescricaoPeriodo  = "JANEIRO/{$iAnoUsu} A ".strtoupper(db_mes($dtFinal[1]))."";
  $sDescricaoPeriodo .= " DE {$iAnoUsu}";
} else {

  $sDescricaoPeriodo  = strtoupper(db_mes($dtInicial[1]))."/{$dtInicial[0]} A ";
  $sDescricaoPeriodo .= strtoupper(db_mes($dtFinal[1]))." DE {$iAnoUsu}";
}

/**
 * Mostra header da descri��o por per�odo.
 */
if ($lTemCamara == true && $lTemPrefeitura == false && $lTemAdminD == false) {
  $head7 = $sDescricaoPeriodo;
} else {

	if ($lTemMinisterio == true) {
		$head7 = $sDescricaoPeriodo;
	} else {
    $head6 = $sDescricaoPeriodo;
	}
}

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(235);

/**
 * Monta o relat�rio da forma de emiss�o.
 *
 * 1 - Publica��o Oficial.
 * 2 - Detalhamento Mensal.
 */
if ($oGet->emissao == 1) {

  $iTamFonte = 7;
  $iAltCell  = 4;

  $oPdf->AddPage("P");

  /**
   * Monta detalhe do cabe�alho do relat�rio.
   */
  $oPdf->SetFont('arial', 'b', $iTamFonte);
  $oPdf->Cell(135, $iAltCell, 'RGF - ANEXO I(LRF, art. 55, inciso I, al�nea "a")', 'B', 0, "L", 0);
  $oPdf->Cell(60, $iAltCell, 'R$ 1,00', 'B', 1, "R", 0);

  $oPdf->Cell(135, $iAltCell, "", 'R', 0, "C", 0);
  $oPdf->Cell(60, $iAltCell, "DESPESAS EXECUTADAS", '', 1, "C", 0);

  $oPdf->Cell(135, $iAltCell, "", 'R', 0, "C", 0);
  $oPdf->Cell(60, $iAltCell, "(�ltimos 12 meses)", 'LB', 1, "C", 0);

  $oPdf->Cell(135, $iAltCell, "DESPESA COM PESSOAL", 'R', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "", '', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "INSCRITAS EM", 'L', 0, "C", 0);
  $oPdf->Ln();

  $oPdf->Cell(135, $iAltCell, "", 'R', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "LIQUIDADAS(a)", '', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "RESTOS A PAGAR", 'L', 0, "C", 0);
  $oPdf->Ln();

  $oPdf->Cell(135, $iAltCell, "", 'R', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "", '', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "NAO", 'L', 0, "C", 0);
  $oPdf->Ln();

  $oPdf->Cell(135, $iAltCell, "", 'R', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "", '', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "PROCESSADOS(b)", 'L', 0, "C", 0);
  $oPdf->Ln();

  $oPdf->Cell(135,$iAltCell,"",'RB',0,"C",0);
  $oPdf->Cell(30, $iAltCell, "", 'RB', 0, "C", 0);
  $oPdf->Cell(30, $iAltCell, "", 'B', 0, "C", 0);
  $oPdf->Ln();

  /**
   * Monta as linhas de cada quadro de totaliza��o.
   */
  foreach ($aDadosAnexoIRGF as $sIndice => $oDadoQuadroLinha) {

    /**
     * Verifica se for quadrodespesaliquida poem borda conforme havia no relat�rio padr�o.
     */
    $oPdf->SetFont('arial', 'b', $iTamFonte);
    if ($sIndice == 'quadrodespesaliquida') {

      $oPdf->Cell(135, $iAltCell, $oDadoQuadroLinha->quadrodescricao, 'TBR', 0, "L", 0);
      $oPdf->Cell(30, $iAltCell, db_formatar($oDadoQuadroLinha->exercicio, 'f'), 'TBR', 0, "R", 0);
      $oPdf->Cell(30, $iAltCell, db_formatar($oDadoQuadroLinha->inscritas, 'f'), 'TBL', 1, "R", 0);
    } else {

      if ($sIndice == 'quadrodespesatotalcompessoal') {

        /**
         * Imprime linhas quadro despesa total com pessoal.
         */
			  $oPdf->Cell(135, $iAltCell, $oDadoQuadroLinha->quadrodescricao,"RTB", 0, "L", 0);
			  $oPdf->Cell(60, $iAltCell, db_formatar($oDadoQuadroLinha->valorapurado, 'f'), "TB", 1, "R", 0);

        $oPdf->Cell(284, $iAltCell, "",0, 1, "C", 0);

			  $oPdf->SetFont('arial', 'b', $iTamFonte);
			  $oPdf->Cell(135, $iAltCell, "APURA��O DO CUMPRIMENTO DO LIMITE LEGAL","RTB", 0, "C", 0);
			  $oPdf->Cell(60, $iAltCell, "VALOR", "TB", 1, "C", 0);
      } else {

       	if ($sIndice == 'quadrodespesabruta'
       	   || $sIndice == 'quadrodespesanaocomputadas'
       	   || $sIndice == 'quadrodespesaliquida') {

          /**
           * Imprime linha quadro despesa bruta.
           * Imprime linha quadro despesa nao computadas.
           * Imprime linha quadro despesa liquida.
           */
	        $oPdf->Cell(135, $iAltCell, $oDadoQuadroLinha->quadrodescricao, 'R', 0, "L", 0);
	        $oPdf->Cell(30, $iAltCell, db_formatar($oDadoQuadroLinha->exercicio, 'f'), 'R', 0, "R", 0);
	        $oPdf->Cell(30, $iAltCell, db_formatar($oDadoQuadroLinha->inscritas, 'f'), 'L', 1, "R", 0);
       	} else {

          /**
           * Imprime linha quadro receita total corrente l�quida.
           * Imprime linha quadro despesa total com pessoal sem rcl.
           * Imprime linha quadro limite m�ximo.
           * Imprime linha quadro limite prudencial.
           */
       		$sPercentual = "";
       		if ($sIndice == 'quadrolimitemaximo' || $sIndice == 'quadrolimiteprudencial' || $sIndice == 'quadrolimitealerta') {
       		  $sPercentual = " - {$oDadoQuadroLinha->percentuallimite}%";
       		}

				  $oPdf->Cell(135, $iAltCell, $oDadoQuadroLinha->quadrodescricao.$sPercentual,"RTB", 0, "L", 0);
				  $oPdf->Cell(60, $iAltCell, db_formatar($oDadoQuadroLinha->valorapurado, 'f'), "TB", 1, "R", 0);

      	}
      }
    }

    /**
     * Preenche as linhas internas do quadro.
     */
    foreach ($oDadoQuadroLinha->linhas as $oDadoLinha) {

      $oPdf->SetFont('arial', '', $iTamFonte);
      $oPdf->Cell(135, $iAltCell, $oDadoLinha->descricao, 'R', 0, "L", 0);
      $oPdf->Cell(30, $iAltCell, db_formatar($oDadoLinha->exercicio, 'f'), 'R', 0, "R", 0);
      $oPdf->Cell(30, $iAltCell, db_formatar($oDadoLinha->inscritas, 'f'), 'L', 0, "R", 0);
      $oPdf->Ln();
    }
  }

  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa(&$oPdf, $oGet->periodo, 195);

  $oPdf->Ln(5);

  /**
   * Assinaturas notas explicativas
   */
  $oPdf->SetFont('arial', '', $iTamFonte-2);
  $oPdf->Ln(20);

  assinaturas(&$oPdf, &$classinatura, 'GF');
} else {

  $oPdf->AddPage("L");

  $iTamCell  = 16;
  $iAltCell  = 4;
  $iTamFonte = 6;

  /**
   * Monta detalhe do cabe�alho do relat�rio.
   */
  $oPdf->SetFont('arial', 'b', $iTamFonte);
  $oPdf->Cell(60, $iAltCell,'RGF - ANEXO I(LRF, art. 55, inciso I, al�nea "a")', "b", 0, "L", 0);
  $oPdf->Cell(($iTamCell*14), $iAltCell, 'R$ 1,00', "b", 1, "R", 0);

  /**
   * Pega a possi��o inicial para ajustar o cabe�alhos dos meses de cada periodo.
   */
  $iPosXMes = $oPdf->GetX();
  $iPosYMes = $oPdf->GetY();

  /**
   * Detalhe das despesas executadas.
   */
  $oPdf->Cell(50, $iAltCell*3, "", 'RT', 0, "C", 0);
  $oPdf->Cell(($iTamCell*12), $iAltCell, "DESPESAS EXECUTADAS (�ltimos 12 meses)", 'RTB', 0, "C", 0);

  /**
   * Pega a posi��o inicial para ajustar o cabe�alhos das totaliza��es do �ltimo m�s.
   */
  $iPosX = $oPdf->GetX();
  $iPosY = $oPdf->GetY();

  /**
   * Monta cabe�alho de totaliza��es liquidadas(a)
   */
  $oPdf->MultiCell($iTamCell+5, $iAltCell*4, 'LIQUIDADAS(a)', 'TBR', 'C', 0, 0);

  /**
   * Monta totaliza��es inscritas em rp n�o processados
   */
  $oPdf->SetXY($iPosX+21, $iPosY);
  $oPdf->MultiCell($iTamCell+5, $iAltCell, "INSCRITAS EM RP N�O PROCESSADOS\n(b)", 'TBL', 'C', 0, 0);
  $oPdf->Ln();

  $oPdf->SetXY($iPosXMes, $iPosYMes+4);
  $oPdf->Cell(50, $iAltCell*3, "DESPESA COM PESSOAL", 'BR', 0, "C", 0);

  /**
   * Lista meses do per�odo anterior ( exerc�cio anterior ) para mostrar a descri��o dos mesmos.
   */
  $oPdf->SetFont('arial', '', $iTamFonte);
  $aDadosColuna = $oAnexoIRGF->getDadosColuna();
  foreach ($aDadosColuna as $oDadoColuna) {
    $oPdf->Cell($iTamCell, $iAltCell*3, $oDadoColuna->sDescricao, 'TBR', 0, "C", 0);
  }

  $oPdf->SetFont('arial', 'b', $iTamFonte);
  $oPdf->Ln();

  /**
   * Monta as linhas de cada quadro de totaliza��o.
   */
  foreach ($aDadosAnexoIRGF as $sIndice => $oDadoQuadroLinha) {

    $oPdf->SetFont('arial', 'b', $iTamFonte);

    $iPosX = $oPdf->GetX();
    $iPosY = $oPdf->GetY();

    /**
     * Verifica se for o quadrodespesanaocomputadas poem multicell para o
     * campo $oDadoQuadroLinha->quadrodescricao n�o sobrepor a linha e ajusta as demais.
     */
    if ($sIndice == 'quadrodespesanaocomputadas') {

      /**
       * Imprime linha quadro despesa nao computadas.
       */
      $sDescricao = str_replace("AS (�", "AS \n (�", $oDadoQuadroLinha->quadrodescricao);
      $oPdf->MultiCell(50, $iAltCell, $sDescricao, 'R', 'L', 0, 0);
      $oPdf->SetXY($iPosX+50, $iPosY);
    } else {

      /**
       * Verifica se for quadrodespesaliquida poem borda conforme havia no relat�rio padr�o.
       */
      if ($sIndice == 'quadrodespesaliquida') {

        /**
         * Imprime linha quadro despesa l�quida.
         */
        $oPdf->Cell(50, $iAltCell, $oDadoQuadroLinha->quadrodescricao, 'TR', 0, "L", 0);
      } else {

        if ($sIndice == 'quadrodespesatotalcompessoal') {

          /**
           * Imprime linhas quadro despesa total com pessoal.
           */
          $oPdf->Cell(242, $iAltCell, $oDadoQuadroLinha->quadrodescricao,"RTB", 0, "L", 0);
          $oPdf->Cell(42, $iAltCell, db_formatar($oDadoQuadroLinha->valorapurado, 'f'), "TB", 1, "R", 0);

          $oPdf->Cell(284, $iAltCell, "",0, 1, "C", 0);

          $oPdf->Cell(242, $iAltCell, "APURA��O DO CUMPRIMENTO DO LIMITE LEGAL","RTB", 0, "C", 0);
          $oPdf->Cell(42, $iAltCell, "VALOR", "TB", 0, "C", 0);
        } else {

          if ($sIndice == 'quadrodespesabruta') {

            /**
             * Imprime linha quadro despesa bruta.
             */
            $oPdf->Cell(50, $iAltCell, $oDadoQuadroLinha->quadrodescricao, 'R', 0, "L", 0);
          } else {

            /**
             * Imprime linha quadro receita total corrente l�quida.
             * Imprime linha quadro despesa total com pessoal sem rcl.
             * Imprime linha quadro limite m�ximo.
             * Imprime linha quadro limite prudencial.
             */
            $sPercentual = "";
            if ($sIndice == 'quadrolimitemaximo' || $sIndice == 'quadrolimiteprudencial' || $sIndice == 'quadrolimitealerta') {
              $sPercentual = " {$oDadoQuadroLinha->percentuallimite}%";
            }

            $oPdf->Cell(242, $iAltCell, $oDadoQuadroLinha->quadrodescricao.$sPercentual,"RTB", 0, "L", 0);
            $oPdf->Cell(42, $iAltCell, db_formatar($oDadoQuadroLinha->valorapurado, 'f'), "TB", 0, "R", 0);
          }
        }
      }
    }

    /**
     * Preenche os registros dos �ltimos 12 meses no relat�rio.
     */
    foreach ($oDadoQuadroLinha->colunameses as $oDadoQuadroLinhaColuna) {

      /**
       * Verifica se for o quadrodespesanaocomputadas poem multicell para o
       * campo $oDadoQuadroLinha->quadrodescricao n�o sobrepor a linha e ajusta as demais.
       */
      if ($sIndice == 'quadrodespesanaocomputadas') {
        $oPdf->Cell($iTamCell, $iAltCell+4, db_formatar($oDadoQuadroLinhaColuna->nValor, 'f'), 'R', 0, "R", 0);
      } else {

        /**
         * Verifica se for quadrodespesaliquida poem borda conforme havia no relat�rio padr�o.
         */
        if ($sIndice == 'quadrodespesaliquida') {
          $oPdf->Cell($iTamCell, $iAltCell, db_formatar($oDadoQuadroLinhaColuna->nValor, 'f'), 1, 0, "R", 0);
        } else {
          $oPdf->Cell($iTamCell, $iAltCell, db_formatar($oDadoQuadroLinhaColuna->nValor, 'f'), 'R', 0, "R", 0);
        }
      }
    }

    /**
     * Verifica se for o quadrodespesanaocomputadas poem multicell para o
     * campo $oDadoQuadroLinha->quadrodescricao n�o sobrepor a linha e ajusta as demais.
     */
    if ($sIndice == 'quadrodespesanaocomputadas') {

      $oPdf->Cell($iTamCell+5, $iAltCell+4, db_formatar($oDadoQuadroLinha->exercicio, 'f'), 'R', 0, "R", 0);
      $oPdf->Cell($iTamCell+5, $iAltCell+4, db_formatar($oDadoQuadroLinha->inscritas, 'f'), 'L', 1, "R", 0);
    } else {

      /**
       * Verifica se for quadrodespesaliquida poem borda conforme havia no relat�rio padr�o.
       */
      if ($sIndice == 'quadrodespesaliquida') {

        $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoQuadroLinha->exercicio, 'f'), 1, 0, "R", 0);
        $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoQuadroLinha->inscritas, 'f'), 'TBL', 0, "R", 0);
      } else {

        if ($sIndice == 'quadrodespesabruta') {

          /**
           * Imprime valores linha quadro despesa bruta.
           */
          $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoQuadroLinha->exercicio, 'f'), 'R', 0, "R", 0);
          $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoQuadroLinha->inscritas, 'f'), 'L', 0, "R", 0);
        }
      }

      $oPdf->Ln();
    }

    /**
     * Preenche as linhas internas dos quadros despesa bruta e despesa l�quida.
     */
    foreach ($oDadoQuadroLinha->linhas as $oDadoLinha) {

      $oPdf->SetFont('arial', '', $iTamFonte);
      $oPdf->Cell(50, $iAltCell, $oDadoLinha->descricao, 'R', 0, "L", 0);

      /**
       * Percorre os �ltimos 12 meses do periodo informado.
       */
      foreach ($oDadoLinha->colunameses as $oDadoMesLinha) {
        $oPdf->Cell($iTamCell, $iAltCell, db_formatar($oDadoMesLinha->nValor, 'f'), 'R', 0, "R", 0);
      }

      $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoLinha->exercicio, 'f'), 'R', 0, "R", 0);
      $oPdf->Cell($iTamCell+5, $iAltCell, db_formatar($oDadoLinha->inscritas, 'f'), 'L', 0, "R", 0);
      $oPdf->Ln();
    }
  }

  /**
   * Assinaturas e notas explicativas.
   */
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa(&$oPdf, $oGet->periodo, 284);

  $oPdf->Ln(5);

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Ln(20);

  assinaturas(&$oPdf, &$classinatura, 'GF');
}

$oPdf->Output();
?>