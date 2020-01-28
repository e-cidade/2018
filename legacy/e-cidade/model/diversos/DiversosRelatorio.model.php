<?php
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

/**
 * Class responsavel pela emissão do relatório de importações para diversos
 *
 * @package  Diversos
 * @author    everton          <everton.heckler@dbseller.com.br>
 *
 * @revision  $Author  : $
 * @version  $Revision: 1.5 $
 */
class DiversosRelatorio {

	/**
	 * Cgm utilizado na pesquisa
	 * @var integer
	 */
	protected $iCgm;

	/**
	 * Matricula utilizada na pesquisa
	 * @var integer
	 */
	protected $iMatricula;

	/**
	 * Numpre utilizada na pesquisa
	 * @var integer
	 */
	protected $iNumpre;

	/**
	 * Data Inicial utilizada na pesquisa
	 * @var date
	 */
	protected $dDataInicial;

	/**
	 * Data Final utilizada na pesquisa
	 * @var date
	 */
	protected $dDataFinal;

	/**
	 * Tipo utilizada na pesquisa
	 * Analitico/Sintetico
	 * @var integer
	 */
	protected $sTipo;

	/**
	 * Origem utilizada na pesquisa
	 * IPTU/Agua
	 * @var string
	 */
	protected $sOrigem;

	/**
	 * formato utilizado para geraçao do relatório
	 * PDF/CSV
	 * @var string
	 */
	protected $sFormato;

	/**
	 * objeto contendo os dados retornados do sql
	 */
	protected $oDadosRetorno;

	/**
	 * Codigo do Diverso Importado
	 * @var integer
	 */
	protected $iCodDiverso;
	
	/**
	 * Código da inscrição de Alvará
	 * @var integer
	 */
	protected $iInscricao;

	/**
	 * Define o CGM
	 * @param integer $iCgm
	 */
	public function setCgm($iCgm) {

		$this->iCgm = $iCgm;
	}

	/**
	 * Define a Matricula
	 * @param integer $iMatricula
	 */
	public function setMatricula($iMatricula) {

		$this->iMatricula = $iMatricula;
	}
	
	/**
	 * Define o código da inscrição
	 * @param integer
	 */
	public function setInscricao($iInscricao) {
	  
	  $this->iInscricao = $iInscricao;
	  
	}

	/**
	 * Define o Numpre
	 * @param integer $iNumpre
	 */
	public function setNumpre($iNumpre) {

		$this->iNumpre = $iNumpre;
	}

	/**
	 * Define a Data Inicial
	 * @param date $dDataInicial
	 */
	public function setDataInicial($dDataInicial) {

		$this->dDataInicial = $dDataInicial;
	}

	/**
	 * Define a Data Final
	 * @param date $dDataFinal
	 */
	public function setDataFinal($dDataFinal) {

		$this->dDataFinal = $dDataFinal;
	}

	/**
	 * Define Tipo de Relatório
	 * Analitico/Sintético
	 * @param string $sTipo
	 */
	public function setTipo($sTipo) {

		if (empty($sTipo)) {
			throw new BusinessException('Não definido o tipo de Relatório.');
		}

		$this->sTipo = $sTipo;
	}

	/**
	 * Define a Origem
	 * IPTU/AGUA
	 * @param string $sOrigem
	 */
	public function setOrigem($sOrigem) {

		if (empty($sOrigem)) {
			throw new BusinessException('Não definido a origem do Relatório.');
		}

		$this->sOrigem = $sOrigem;
	}

	/**
	 * Define o Formato para geração
	 * PDF/CSV
	 * @param string $sFormato
	 */
	public function setFormato($sFormato) {

		if (empty($sFormato)) {
			throw new BusinessException('Não definido o formato do Relatório.');
		}

		$this->sFormato = $sFormato;
	}

	/**
	 * Define o codigo do diverso importado
	 * @param integer $iCodDiverso
	 */
	public function setCodDiverso($iCodDiverso) {

		if (empty($iCodDiverso)) {
			throw new BusinessException('Código Diverso não informado.');
		}

		$this->iCodDiverso = $iCodDiverso;
	}

	/**
	 * Define os dados do retorno do sql
	 * @param array $aDadosRetorno
	 */
	public function setDadosRetorno($oDadosRetorno) {

		if (empty($oDadosRetorno)) {
			throw new BusinessException('Nenhum Registro Encontrado.');
		}

		$this->oDadosRetorno = $oDadosRetorno;
	}

	/**
	 *  Construtor da classe
	 *
	 * @param integer DiversosEmissao
	 */
	function __construct($iCodDiverso) {

		if ( !empty($iCodDiverso) ) {
			$this->setCodDiverso($iCodDiverso);
		}
	}

	/**
	 * monta objeto com o retorno do sql
	 */
	protected function objetoRetorno() {
		 
		$oWherePesquisa = new stdClass();

		$oWherePesquisa->iCgm         = $this->iCgm;
		$oWherePesquisa->iMatricula   = $this->iMatricula;
		$oWherePesquisa->iInscricao   = $this->iInscricao;
		$oWherePesquisa->iNumpre      = $this->iNumpre;
		$oWherePesquisa->dDataInicial = $this->dDataInicial;
		$oWherePesquisa->dDataFinal   = $this->dDataFinal;
		$oWherePesquisa->sTipo        = $this->sTipo;
		$oWherePesquisa->sOrigem      = $this->sOrigem;
		$oWherePesquisa->iCodDiverso  = $this->iCodDiverso;

    
		$oDaoDiverImporta = db_utils::getDao('diverimporta');
		$sSqlRelatorio    = $oDaoDiverImporta->sql_query_relatorio_importacao($oWherePesquisa);
		
		$rsDadosRelatorio = $oDaoDiverImporta->sql_record($sSqlRelatorio);
		
		$aDadosRetorno = Array();
		
		if ($oDaoDiverImporta->numrows > 0) {

			$aDadosRelatorio = db_utils::getCollectionByRecord($rsDadosRelatorio, true);

			foreach($aDadosRelatorio as $oDadosRelatorio) {

				$oDadosRetorno = new stdClass();
				$oDadosRetorno->codimportacao   = $oDadosRelatorio->codimportacao; 
				$oDadosRetorno->dv11_data       = $oDadosRelatorio->dv11_data;     
				$oDadosRetorno->dv11_hora       = $oDadosRelatorio->dv11_hora;    
				$oDadosRetorno->login           = $oDadosRelatorio->login;         
				$oDadosRetorno->dv05_numcgm     = $oDadosRelatorio->dv05_numcgm;   
				$oDadosRetorno->z01_nome        = $oDadosRelatorio->z01_nome;      
				$oDadosRetorno->matricula       = $oDadosRelatorio->matricula;     
				$oDadosRetorno->inscricao       = $oDadosRelatorio->inscricao;
				$oDadosRetorno->observacao      = $oDadosRelatorio->observacao;    
				$oDadosRetorno->aRegistros      = array();
				$aDadosRetorno[$oDadosRelatorio->codimportacao] = $oDadosRetorno;

			}
			
			foreach($aDadosRelatorio as $oDadosRelatorio) {
				$oRegistros    = new stdClass();

				$oRegistros->tipoprocedencia    = $oDadosRelatorio->tipoprocedencia;   
				$oRegistros->numpreantigo       = $oDadosRelatorio->numpreantigo;      
				$oRegistros->numparantigo       = $oDadosRelatorio->numparantigo;      
				$oRegistros->receitaantigo      = $oDadosRelatorio->receitaantigo;
				$oRegistros->procedencia        = $oDadosRelatorio->procedencia;       
				$oRegistros->numprenovo         = $oDadosRelatorio->numprenovo;        
				$oRegistros->numparnovo         = $oDadosRelatorio->numparnovo;        
				$oRegistros->dv05_vlrhis        = $oDadosRelatorio->dv05_vlrhis;
				$oRegistros->dv05_valor         = $oDadosRelatorio->dv05_valor;
				$oRegistros->juros              = $oDadosRelatorio->juros;             
				$oRegistros->multa              = $oDadosRelatorio->multa;             
				$oRegistros->total              = $oDadosRelatorio->total;             
				$oRegistros->descrreceitaantiga = $oDadosRelatorio->descrreceitaantiga;

				$aDadosRetorno[$oDadosRelatorio->codimportacao]->aRegistros[] = $oRegistros;

			}
			
		}
		
		return $aDadosRetorno;
	}

	/**
	 * Geração do CSV
	 */
	protected function gerarCSV(){

		$aLinhas = array();
		$oCabecalho = new stdClass();
		$sArquivo   = 'tmp/relatorio_debitos_importados_'. date('Y-m-d_H:i') . '_' . db_getsession('DB_login').'.csv';
		$fArquivo = fopen($sArquivo, "w");
		
		$oCabecalho->sDatahora   = "Data-Hora";
		$oCabecalho->sLogin      = "Login";
		$oCabecalho->CGM         = "CGM";
		$oCabecalho->nome        = "Nome";
		$oCabecalho->Matrícula   = "Matricula";
		$oCabecalho->Inscricao   = "Inscricao";
		$oCabecalho->Observações = "Observações";
		$oCabecalho->Tipo        = "Tipo";
		$oCabecalho->Numpre      = "Numpre de origem";
		$oCabecalho->Parcela     = "Parcela de origem";
		$oCabecalho->Receita     = "Receita de origem";
		$oCabecalho->Procedencia = "Procedencia";
		$oCabecalho->Numpre      = "Numpre destino";
		$oCabecalho->Parcela     = "Parcela destino";
		$oCabecalho->Vlrhist     = "Valor Hist";
		$oCabecalho->Vlrcorr     = "Valor Corre";
		$oCabecalho->Juros       = "Juros";
		$oCabecalho->Multa       = "Multa";
		$oCabecalho->Total       = "Total";
		
		$aLinhas[] = $oCabecalho;
		
		foreach ($this->objetoRetorno() as $oDadosRelatorio) {
			
			foreach ($oDadosRelatorio->aRegistros as $oRegistro) {
				
			  $oConteudo = new stdClass();
			  
				$oConteudo->sDatahora   = $oDadosRelatorio->dv11_data ."-". $oDadosRelatorio->dv11_hora;
				$oConteudo->sLogin      = $oDadosRelatorio->login;
				$oConteudo->CGM         = $oDadosRelatorio->dv05_numcgm;
				$oConteudo->nome        = $oDadosRelatorio->z01_nome;
				$oConteudo->Matricula   = $oDadosRelatorio->matricula;
				$oConteudo->Inscricao   = $oDadosRelatorio->inscricao;
				$oConteudo->Observações = $oDadosRelatorio->observacao;
				$oConteudo->Tipo        = "";
				$oConteudo->Numpre      = $oRegistro->numpreantigo;
				$oConteudo->Parcela     = $oRegistro->numparantigo;
				$oConteudo->Receita     = $oRegistro->receitaantigo;
				$oConteudo->Procedencia = $oRegistro->tipoprocedencia;
				$oConteudo->Numpre      = $oRegistro->numprenovo;
				$oConteudo->Parcela     = $oRegistro->numparnovo;
				$oConteudo->Vlrhist     = $oRegistro->dv05_vlrhis;
				$oConteudo->Vlrcorr     = $oRegistro->dv05_valor;
				$oConteudo->Juros       = $oRegistro->juros;
				$oConteudo->Multa       = $oRegistro->multa;
				$oConteudo->Total       = $oRegistro->total;
				
				$aLinhas[] = $oConteudo;
			}
		}
		foreach ($aLinhas as $oLinha) {
		  
			fputcsv($fArquivo, (array)$oLinha, ";");
		}
		
		fclose($fArquivo);
		
    return $sArquivo;

	}

	/**
	 * Geração do PDF
	 */
	protected function gerarPDF($aTipoRel) {
		 
		global $head1, $head2, $head3, $head4, $head5, $head6;
		
		$head1 = "Relatório de Importação para Diversos";
		$head2 = 'Filtros Utilizados:';
		
		if (!empty($this->iCgm)) {
			$head3 = 'CGM: '.$this->iCgm;
		}
		
		if (!empty($this->iMatricula)) {
		  
			$head4 = 'Matricula: '.$this->iMatricula;
			
		} if (!empty($this->iInscricao)) {
		  
		  $head4 = 'Matricula: '.$this->iInscricao;
		  
		}
		
		if (!empty($this->iNumpre)) {
			$head5 = 'Numpre: '.$this->iNumpre;
		}
		
		if (!empty($this->dDataInicial) and !empty($this->dDataFinal)) {
			$head6 = 'Periodo de: '.$this->dDataInicial . ' até ' . $this->dDataFinal;
		}
		
		if (!empty($this->dDataInicial) and empty($this->dDataFinal)) {
			$head6 = 'Data Inicial: '.$this->dDataInicial;
		}
		
		if (empty($this->dDataInicial) and !empty($this->dDataFinal)) {
			$head6 = 'Data Final: '.$this->dDataFinal;
		}
		
		
		$oPdf = new PDF();

		$oPdf->Open();
		$oPdf->AliasNbPages();
		$oPdf->setfillcolor(235);
		
		$troca  = 1;
		$alt    = 4;
		$p      = 0;

		$iCodImpotacao = "";
		$total         = 0;
		$totalog       = 0;

		$fTotalValorHist       = 0;
		$fTotalValor           = 0;
		$fTotalValorJuro       = 0;
		$fTotalValorMulta      = 0;
		$fTotalValorTotal      = 0;
		$fGeralTotalValorHist  = 0;
		$fGeralTotalValor      = 0;
		$fGeralTotalValorJuro  = 0;
		$fGeralTotalValorMulta = 0;
		$fGeralTotalValorTotal = 0;
    
		foreach($this->oDadosRetorno as $oDadosRelatorio ) {
				
			if ($aTipoRel == "Analitico") {
				
				if ($iCodImpotacao != $oDadosRelatorio ->codimportacao) {

					if ($iCodImpotacao != "") {
						 
						$oPdf->setfont('arial', 'b', 8);
						$oPdf->cell(125, $alt, 'TOTAL: '         , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, db_formatar($fTotalValorHist,'f')  , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, db_formatar($fTotalValor,'f')      , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, db_formatar($fTotalValorJuro,'f')  , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, db_formatar($fTotalValorMulta,'f') , "T", 0, "R", 1);
						$oPdf->cell(30 , $alt, db_formatar($fTotalValorTotal,'f') , "T", 1, "R", 1);

						$oPdf->ln();
						$total = 0;

						$fTotalValorHist  = 0;
						$fTotalValor      = 0;
						$fTotalValorJuro  = 0;
						$fTotalValorMulta = 0;
						$fTotalValorTotal = 0;
					}

					if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {

						$oPdf->addpage("L");
						$oPdf->setrightmargin(0.5);
						$troca = 0;
					}

					$oPdf->setfont('arial', 'b', 8);
					$oPdf->cell(35,  $alt, 'Data - Hora', 1, 0, "C", 1);
					$oPdf->cell(30,  $alt, 'Login'      , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'CGM'        , 1, 0, "C", 1);
					$oPdf->cell(60,  $alt, 'Nome'       , 1, 0, "C", 1);
					
					if (!empty($oDadosRelatorio->matricula)) {
					  $oPdf->cell(20,  $alt, 'Matrícula'  , 1, 0, "C", 1);
					} else {
					  $oPdf->cell(20,  $alt, 'Inscrição'  , 1, 0, "C", 1);
					}
					
					$oPdf->cell(110, $alt, 'Observação' , 1, 1, "C", 1);

					$oPdf->setfont('arial', '', 8);
					$oPdf->cell(35,  $alt, "{$oDadosRelatorio ->dv11_data} - {$oDadosRelatorio ->dv11_hora}"   , 0, 0, "C", 0);
					$oPdf->cell(30,  $alt, $oDadosRelatorio ->login       , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->dv05_numcgm , 0, 0, "C", 0);
					$oPdf->cell(60,  $alt, $oDadosRelatorio ->z01_nome    , 0, 0, "C", 0);
					
					if (!empty($oDadosRelatorio->matricula)) {
					  $oPdf->cell(20,  $alt, $oDadosRelatorio ->matricula   , 0, 0, "C", 0);
					} else {
					  $oPdf->cell(20,  $alt, $oDadosRelatorio ->inscricao   , 0, 0, "C", 0);
					}
					
					$oPdf->cell(110, $alt, $oDadosRelatorio ->observacao  , 0, 1, "L", 0);

					$p             = 0;
					$iCodImpotacao = $oDadosRelatorio ->codimportacao;
					$totalog++;
				}

				if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){

					$oPdf->addpage("L");
					$oPdf->setrightmargin(0.5);
					 
					$oPdf->setfont('arial', 'b', 8);
					$oPdf->cell(35,  $alt, 'Data - Hora', 1, 0, "C", 1);
					$oPdf->cell(30,  $alt, 'Login'      , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'CGM'        , 1, 0, "C", 1);
					$oPdf->cell(60,  $alt, 'Nome'       , 1, 0, "C", 1);
					$oPdf->cell(20,  $alt, 'Matricula'  , 1, 0, "C", 1);
					$oPdf->cell(110, $alt, 'Observação' , 1, 1, "C", 1);
					 
					$oPdf->setfont('arial', '', 8);
					$oPdf->cell(35,  $alt, "{$oDadosRelatorio ->dv11_data} - {$oDadosRelatorio ->dv11_hora}"   , 0, 0, "C", 0);
					$oPdf->cell(30,  $alt, $oDadosRelatorio ->login       , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->dv05_numcgm , 0, 0, "C", 0);
					$oPdf->cell(60,  $alt, $oDadosRelatorio ->z01_nome    , 0, 0, "C", 0);
					$oPdf->cell(20,  $alt, $oDadosRelatorio ->matricula   , 0, 0, "C", 0);
					$oPdf->cell(110, $alt, $oDadosRelatorio ->observacao  , 0, 1, "L", 0);

					$p     = 0;
					$troca = 0;
				}

				$oPdf->setfont('arial', 'b', 7);
					
				$oPdf->cell(84, $alt, 'Dados de Origem'  , 1, 0, "C", 1);
				$oPdf->cell(191, $alt, 'Dados de Destino' , 1, 1, "C", 1);
				
				$oPdf->cell(30, $alt, 'Tipo'       , 1, 0, "C", 1);
				$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
				$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Receita'    , 1, 0, "C", 1);
				$oPdf->cell(17, $alt, 'Procedência', 1, 0, "C", 1);
				$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
				$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Vlr hist'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Vlr corr(*)', 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Juros(*)'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Multa(*)'   , 1, 0, "C", 1);
				$oPdf->cell(30, $alt, 'Total(*)'   , 1, 1, "C", 1);
					
			} else {
				
				if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){

					$oPdf->addpage("L");
					$oPdf->setrightmargin(0.5);
					$troca = 0;
					
					$oPdf->cell(84, $alt, 'Dados de Origem'   , 1, 0, "C", 1);
					$oPdf->cell(191, $alt, 'Dados de Destino' , 1, 1, "C", 1);
					
					$oPdf->setfont('arial', 'b', 7);
					$oPdf->cell(30, $alt, 'Tipo'       , 1, 0, "C", 1);
					$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
					$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Receita'    , 1, 0, "C", 1);
					$oPdf->cell(17, $alt, 'Procedência', 1, 0, "C", 1);
					$oPdf->cell(14, $alt, 'Numpre'     , 1, 0, "C", 1);
					$oPdf->cell(10, $alt, 'Parcela'    , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Vlr hist'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Vlr corr(*)', 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Juros(*)'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Multa(*)'   , 1, 0, "C", 1);
					$oPdf->cell(30, $alt, 'Total(*)'   , 1, 1, "C", 1);
						
						
				}
			}

			foreach($oDadosRelatorio->aRegistros as $oRegistros ) {
				
				$oPdf->setfont('arial', '', 8);
				$oPdf->cell(30, $alt, $oRegistros ->tipoprocedencia    , 0, 0, "C", 0);
				$oPdf->cell(14, $alt, $oRegistros ->numpreantigo       , 0, 0, "C", 0);
				$oPdf->cell(10, $alt, $oRegistros ->numparantigo       , 0, 0, "C", 0);
				$oPdf->cell(30, $alt, $oRegistros ->descrreceitaantiga , 0, 0, "C", 0);
				$oPdf->cell(17, $alt, $oRegistros ->procedencia        , 0, 0, "C", 0);
				$oPdf->cell(14, $alt, $oRegistros ->numprenovo         , 0, 0, "C", 0);
				$oPdf->cell(10, $alt, $oRegistros ->numparnovo         , 0, 0, "C", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->dv05_vlrhis, 'f') , 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->dv05_valor, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->juros, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->multa, 'f'), 0, 0, "R", 0);
				$oPdf->cell(30, $alt, db_formatar($oRegistros ->total, 'f'), 0, 1, "R", 0);
					
					
				$fTotalValorHist   = $fTotalValorHist  + $oRegistros ->dv05_vlrhis;
				$fTotalValor       = $fTotalValor      + $oRegistros ->dv05_valor;
				$fTotalValorJuro   = $fTotalValorJuro  + $oRegistros ->juros;
				$fTotalValorMulta  = $fTotalValorMulta + $oRegistros ->multa;
				
				$fTotalValorTotal  = ($fTotalValor > 0 ? $fTotalValor : $fTotalValorHist) + $fTotalValorJuro + $fTotalValorMulta;
					
				$fGeralTotalValorHist  = $fGeralTotalValorHist  + $fTotalValorHist;
				$fGeralTotalValor      = $fGeralTotalValor      + $fTotalValor;
				$fGeralTotalValorJuro  = $fGeralTotalValorJuro  + $fTotalValorJuro;
				$fGeralTotalValorMulta = $fGeralTotalValorMulta + $fTotalValorMulta;
				$fGeralTotalValorTotal = $fGeralTotalValorTotal + $fTotalValorTotal;
				
			}
			$total++;

		}
		if ($aTipoRel == "Analitico") {
				
			$oPdf->setfont('arial', 'b', 8);
			$oPdf->cell(125, $alt, 'TOTAL: '         , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, db_formatar($fTotalValorHist,'f')  , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, db_formatar($fTotalValor,'f')      , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, db_formatar($fTotalValorJuro,'f')  , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, db_formatar($fTotalValorMulta,'f') , "T", 0, "R", 1);
			$oPdf->cell(30 , $alt, db_formatar($fTotalValorTotal,'f') , "T", 1, "R", 1);
			$oPdf->ln();

		}

		$oPdf->cell(125, $alt, 'TOTAL GERAL: '        , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, db_formatar($fGeralTotalValorHist,'f')  , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, db_formatar($fGeralTotalValor,'f')      , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, db_formatar($fGeralTotalValorJuro,'f')  , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, db_formatar($fGeralTotalValorMulta,'f') , "T", 0, "R", 1);
		$oPdf->cell(30 , $alt, db_formatar($fGeralTotalValorTotal,'f') , "T", 1, "R", 1);
		$oPdf->ln();

		$oPdf->Output();

		return $sArquivo;

	}

	/**
	 * função responsavel pela geração do relatório
	 * @throws BusinessException
	 */
	public function gerarRelatorio() {

		if (empty($this->sTipo)) {
			throw new BusinessException('[1] - Tipo para emissão não informado.');
		}

		if (empty($this->sOrigem)) {
			throw new BusinessException('[2] - origem não informada emissão.');
		}

		if (empty($this->sFormato)) {
			throw new BusinessException('[3] - Formato para emissão não informado.');
		}

		$this->setDadosRetorno($this->objetoRetorno());
		
		if ($this->sFormato == "PDF") {
			 
			$sNomeArquivo = $this->gerarPDF($this->sTipo);
				
		} else if ($this->sFormato == "CSV") {
				
			$sNomeArquivo = $this->gerarCSV();
				
		} else {
			throw new BusinessException('[3] - Erro na definição de um  formato para o relatório.');
		}
		return $sNomeArquivo;
	}


}