<?php

/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Emiss�o do arquivo do Refeisul
 * Class EmissaoArquivoRefeisul
 */
class EmissaoArquivoRefeisul {
	
	
	/**
	 * Competencia da folha
	 * @var DBCompetencia
	 */
	private $oCompetencia;
	
	/**
	 * Institui��o
	 * @var Instituicao
	 */
	private $oInstituicao;
	
	/**
	 * Arquivo de gera��o do arquivo em formato XML
	 * @var PHPExcel
	 */
	private $oArquivoRefeisul;
	
	/**
	 * Codigo da institui��o do refeisul
	 * @var integer
	 */
	private $iCodigoEmpresa;
	
	/**
	 * Numero de Linhas escritas at� o momento. IN
	 * @var int
	 */
	private $iTotalLinhaEscritas = 1; 
	
	/**
	 * Instancia a gera��o do arquivo de recarga do vale alimenta��o Refeisul
	 * EmissaoArquivoRefeisul constructor.
	 *
	 * @param DBCompetencia $oCompetencia
	 * @param Instituicao   $oInstituicao
	 */
	public function __construct(DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
		
		$this->oCompetencia = $oCompetencia;
		$this->oInstituicao = $oInstituicao;
		
	}
	
	/**
	 * @return int
	 * @throws BusinessException
	 */
	public function getCodigoEmpresa() {
		
		if (!empty($this->iCodigoEmpresa)) {
			return $this->iCodigoEmpresa;
		}
		
		$oDaoCodigoClienteRefeisul = new cl_codigoclienterefeisul();
		$sWhere                    = "rh171_instit = " . $this->oInstituicao->getCodigo();
		$sSqlCodigoClienteBanrisul = $oDaoCodigoClienteRefeisul->sql_query_file(null, "rh171_codigocliente", null, $sWhere);
		$rsCodigoCliente           = db_query($sSqlCodigoClienteBanrisul);
		if (!$rsCodigoCliente || pg_num_rows($rsCodigoCliente) == 0) {
			
			$sMensagem = "N�o foi encontrado a configura��o do c�digo da empresa para a institui��o.\n";
			$sMensagem .= "Configure na rotina Procedimentos > Manuten��o de Par�metros > Refeisul";
			throw new BusinessException($sMensagem);
		}
		$this->iCodigoEmpresa = db_utils::fieldsMemory($rsCodigoCliente, 0)->rh171_codigocliente;
		return $this->iCodigoEmpresa;
	}
	
	/**
	 * @return string
	 * @throws BusinessException
	 * @throws PHPExcel_Exception
	 * @throws PHPExcel_Reader_Exception
	 */
	public function gerar() {
		
		$iCodigoEmpresa = $this->getCodigoEmpresa();
		$this->oArquivoRefeisul = new PHPExcel();
		PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
		$this->setCabecalho();
		$oPlanilha = $this->oArquivoRefeisul->getActiveSheet();
		/**
		 * Iteramos sobre os registros para gerar aslinhas no arquivo
		 */
		$aServidores  = $this->getServidores();
		foreach	($aServidores as $oDadosServidor) {
		  $this->adicionarLinha($oDadosServidor, $oPlanilha);	
		}
		
		$oFormatoArquivo  = PHPExcel_IOFactory::createWriter($this->oArquivoRefeisul, 'Excel5');
		$sNomeArquivo     =  "tmp/ALTERAR_LIMITE_".date("dmY_His")."_{$iCodigoEmpresa}_02.xls";
		$oFormatoArquivo->save($sNomeArquivo);
		return $sNomeArquivo;
	}
	
	/**
	 * Retorna os servidores que possuem vale alimenta��o no m�s
	 * @throws BusinessException
	 */
	private function getServidores() {
		
		$iMesFolha      = $this->getCompetencia()->getMes();
		$iAnoFolha      = $this->getCompetencia()->getAno();
		
		$sWhereDadosRefeisul = "rh49_instit= ".$this->getInstituicao()->getCodigo();
		$sWhereDadosRefeisul .= "and rh49_anousu = {$iAnoFolha} and rh49_mesusu = {$iMesFolha} ";
		
		$oDaoVisaVale    = new cl_rhvisavalecad();
		$sCampos         = "rh01_regist as matricula, z01_nome as nome, z01_cgccpf as cpf, rh49_valormes as valor";
		$sSqlDados       = $oDaoVisaVale->sql_query(null, $sCampos, "z01_nome", $sWhereDadosRefeisul);
		$rsDadosRefeisul = db_query($sSqlDados);
		if (!$rsDadosRefeisul) {
			throw new BusinessException("Erro ao pesquisar dados para gera��o do refeisul.");
		}
		
		$aServidores  = array();
		$iTotalLinhas = pg_num_rows($rsDadosRefeisul);
		if ($iTotalLinhas == 0) {
			
			$sMensagem  = "N�o foram encontrados registros para a gera��o do arquivo nessa compet�ncia ";
			$sMensagem .= "({$this->getCompetencia()->getCompetencia()}).";
			throw new BusinessException($sMensagem);
		}
	
		for ($iServidor = 0; $iServidor < $iTotalLinhas; $iServidor++) {
	    $aServidores[] = db_utils::fieldsMemory($rsDadosRefeisul, $iServidor); 
		}
		return $aServidores;
	}
	
	/**
	 * Retorna a competencia que est� sendo gerado o arquivo
	 * @return DBCompetencia
	 */
	private function getCompetencia() {
		return $this->oCompetencia;
	}
	
	/**
	 * Retorna a institui��o que est� gerando o arquivo
	 * @return Instituicao
	 */
	private function getInstituicao() {
		return $this->oInstituicao;
	}
	
	/**
	 * Escreve o cabecalho do arquivo
	 * @throws PHPExcel_Exception
	 */
	private function setCabecalho() {
		
		$oArquivoRefeisul = $this->oArquivoRefeisul;
		$oPlanilha        = $oArquivoRefeisul->setActiveSheetIndex(0);
		
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$oArquivoRefeisul->getActiveSheet()->getColumnDimension('k')->setAutoSize(true);
		
		
		$oPlanilha->setCellValue('A1', 'Nome');
		$oPlanilha->setCellValue('B1', 'Matricula');
		$oPlanilha->setCellValue('C1', 'CPF');
		$oPlanilha->setCellValue('D1', 'CNPJ');
		$oPlanilha->setCellValue('E1', 'Farmacia');
		$oPlanilha->setCellValue('F1', 'Sacola');
		$oPlanilha->setCellValue('G1', 'Padrao');
		$oPlanilha->setCellValue('H1', 'Servico');
		$oPlanilha->setCellValue('I1', 'CodigoSetor');
		$oPlanilha->setCellValue('J1', 'NomeSetor');
		$oPlanilha->setCellValue('K1', 'CodigoEmpresa');
		$oPlanilha->setCellValue('L1', 'TipoOperacao');
		$this->iTotalLinhaEscritas++;
		
	}
	
	/**
	 * Adiciona uma linha ao arquivo
	 * @param                    $oDadosServidor
	 * @param PHPExcel_Worksheet $oPlanilha
	 */
	private function adicionarLinha($oDadosServidor, PHPExcel_Worksheet $oPlanilha) {
		
		$iLinhaInicio = $this->iTotalLinhaEscritas;
		$sCpfFormatado = str_pad($oDadosServidor->cpf, 11, "0", STR_PAD_LEFT);
		$oPlanilha->setCellValue('A'.$iLinhaInicio, utf8_encode($oDadosServidor->nome));
		$oPlanilha->setCellValue('B'.$iLinhaInicio, $oDadosServidor->matricula);
		$oPlanilha->setCellValueExplicit('C'.$iLinhaInicio, $sCpfFormatado, PHPExcel_Cell_DataType::TYPE_STRING);
		$oPlanilha->setCellValue('D'.$iLinhaInicio, '0');
		$oPlanilha->setCellValue('E'.$iLinhaInicio, '0');
		$oPlanilha->setCellValue('F'.$iLinhaInicio, number_format($oDadosServidor->valor, 2, ".", ""));
		$oPlanilha->setCellValue('G'.$iLinhaInicio, '0');
		$oPlanilha->setCellValue('H'.$iLinhaInicio, '0');
		$oPlanilha->setCellValue('I'.$iLinhaInicio, '');
		$oPlanilha->setCellValue('J'.$iLinhaInicio, '');
		$oPlanilha->setCellValue('K'.$iLinhaInicio, $this->iCodigoEmpresa);
		$oPlanilha->setCellValue('L'.$iLinhaInicio, '002');
		
		
		$this->oArquivoRefeisul->getActiveSheet()->getRowDimension($iLinhaInicio)->setRowHeight(18);
		$this->iTotalLinhaEscritas++;
	} 
}