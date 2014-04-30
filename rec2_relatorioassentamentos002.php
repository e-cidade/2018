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
 
set_time_limit(0);
session_cache_limiter('none');

define('FPDF_FONTPATH','fpdf151/font/');

require_once('fpdf151/fpdf.php');
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once('fpdf151/fpdf.php');
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_app::import('DBDate');

$oGet = db_utils::postmemory($_GET);

/**
 * Faltas
 * Cogido dos assentamentos de faltas
 */
$aCodigosFaltas = array('FNJ');

/**
 * Ausencias
 * Cogido dos assentamentos de ausencias
 */
$aCodigosAusencias = array('AM', 'AMF', 'FJ');

/**
 * Penalidades
 * Cogido dos assentamentos de penalidades
 */
$aCodigosPenalidades = array('ADVER', 'SUSP');

/**
 * Suspensoes
 * Cogido dos assentamentos de suspensoes
 */
$aCodigosSuspensoes = array('SEP');

/**
 * Array com os codigos dos assentamentos
 * - usado no sql para buscar assentamentos
 */
$aCodigos = array_unique( array_merge($aCodigosFaltas, $aCodigosAusencias, $aCodigosPenalidades, $aCodigosSuspensoes) );

$iInstituicao  = db_getsession('DB_instit');
$oDaoDBConfig  = db_utils::getDao('db_config');
$oDaoAssenta   = db_utils::getDao('assenta');
$oDaoCurric    = db_utils::getDao('curric');

$aDadosAssentamentos = array();

/**
 * Array com os assentamentos 
 */
$aFaltas      = array();
$aAusencias   = array();
$aPenalidades = array();
$aSuspensoes  = array();

/**
 * Array dos treinamentos 
 */
$aTreinamentos = array();

/**
 * Array com cgms dos servidores, usado para buscar treinamentos  
 */
$aCgmTreinamentos = array();

/**
 * Páginas dos servidores, um página por matricula 
 */
$aPaginas = array();
$iPagina  = 0;

/**
 * Filtros vindo do formulario 
 */
$dInicial   = null;
$dFinal     = null;
$iMatricula = null;

if ( !empty($oGet->iMatricula) ) {
  $iMatricula = $oGet->iMatricula;
}

if ( !empty($oGet->dDataInicial) ) {

  $oDataInicial = new DBDate($oGet->dDataInicial);
  $dInicial = $oDataInicial->getDate();
}

if ( !empty($oGet->dDataFinal) ) {

  $oDataFinal = new DBDate($oGet->dDataFinal);
  $dFinal = $oDataFinal->getDate(); 
}

/**
 * Filtros de pesquisa para assentamento 
 */
$sOrderAssenta = 'h16_regist, h16_dtconc, h16_dtterm';
$aWhereAssenta = array();

if ( !empty($iMatricula) ) {
  $aWhereAssenta[] = "h16_regist = {$iMatricula} ";
}

if ( !empty($dInicial) ) {
  $aWhereAssenta[] = "h16_dtconc >= '{$dInicial}' ";
}

if ( !empty($dFinal) ) {
  $aWhereAssenta[] = "h16_dtterm <= '{$dFinal}' ";
}

/**
 * Pesquisa assentamentos
 * filtros:
 * h12_assent : codigos de assentamentos
 * rh01_instit: instituicao logada
 * h16_dtterm : data de termino do assentamento
 */
$aWhereAssenta[] = "trim(h12_assent) in ('" . implode("','", $aCodigos) . "')";
$aWhereAssenta[] = "rh01_instit = " . db_getsession('DB_instit');
$aWhereAssenta[] = "h16_dtterm is not null";
$sWhereAssenta   = implode(' and ', $aWhereAssenta);
$sSqlAssenta     = $oDaoAssenta->sql_query_assentamentos('*', $sOrderAssenta, $sWhereAssenta);
$rsAssenta       = $oDaoAssenta->sql_record($sSqlAssenta);

/**
 * Nao encontrou nenhum assentamento para os filtros informados 
 */
if ( $oDaoAssenta->numrows == 0 ) {

  db_redireciona('db_erros.php?fechar=true&db_erro='. urlencode('Nenhum assentamento econtrado.'));
  exit;
}

/**
 * Percorre os assentamentos informados para montar dados do formulario 
 */
for ( $iIndice = 0; $iIndice < $oDaoAssenta->numrows; $iIndice++ ) {

  $oAssentamento = db_utils::fieldsMemory($rsAssenta, $iIndice);

  $iMesAssentamento = date('m', strtotime($oAssentamento->h16_dtterm));
  $iAnoAssentamento = date('Y', strtotime($oAssentamento->h16_dtterm));

  $oStdAssentamento = new StdClass;
  $oStdAssentamento->sCodigo      = $oAssentamento->h12_assent;
  $oStdAssentamento->iQuantidade  = "$oAssentamento->h16_quant";
  $oStdAssentamento->dInicio      = $oAssentamento->h16_dtconc;
  $oStdAssentamento->dFim         = $oAssentamento->h16_dtterm;
  $oStdAssentamento->sCompetencia = db_formatar($oAssentamento->h16_dtconc , 'd') . ' - ' . db_formatar($oAssentamento->h16_dtterm, 'd');

  if ( !empty($aDadosAssentamentos[$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo]) ) {
    $aDadosAssentamentos[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo]->iQuantidade += $oStdAssentamento->iQuantidade;
  } else {
    $aDadosAssentamentos[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo] = $oStdAssentamento;
  }

  /**
   * Array com informacoes das faltas 
   */
  if ( in_array($oStdAssentamento->sCodigo, $aCodigosFaltas) ) {
    $aFaltas[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo] = $oStdAssentamento;
  }

  /**
   * Array com informacoes das ausencias 
   */
  if ( in_array($oStdAssentamento->sCodigo, $aCodigosAusencias) ) {
    $aAusencias[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo] = $oStdAssentamento; 
  }

  /**
   * Array com informacoes das penalidades 
   */
  if ( in_array($oStdAssentamento->sCodigo, $aCodigosPenalidades) ) {
    $aPenalidades[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo] = $oStdAssentamento; 
  }

  /**
   * Array com informacoes das suspensoes 
   */
  if ( in_array($oStdAssentamento->sCodigo, $aCodigosSuspensoes) ) {
    $aSuspensoes[$oAssentamento->h16_regist][$iAnoAssentamento][$iMesAssentamento][$oStdAssentamento->sCodigo] = $oStdAssentamento; 
  }

  /**
   * Array com cgm dos servidores 
   * usado para pesquisar treinamentos
   */
  $aCgmTreinamentos[$oAssentamento->h16_regist] = $oAssentamento->rh01_numcgm; 

  /**
   * Informacoes dos servidores 
   */
  $oDadosServidor = new StdClass;
  $oDadosServidor->iMatricula = $oAssentamento->h16_regist; 
  $oDadosServidor->sNome      = $oAssentamento->z01_nome;
  $oDadosServidor->dNomeacao  = db_formatar($oAssentamento->rh01_admiss, 'd');
  $oDadosServidor->sCargo     = $oAssentamento->rh37_descr;

  /**
   * Array com informacoes dos servidores
   */
  $aServidores[$oAssentamento->h16_regist] = $oDadosServidor;
}

/**
 * Pesquisa treinamentos dos servidores
 */   
foreach ( $aCgmTreinamentos as $iMatricula => $iNumCgm ) {

  $aWhereTreinamentos = array();
  $aWhereTreinamentos[] = "h03_numcgm = " . $iNumCgm;

  if ( !empty($dInicial) ) {
    $aWhereTreinamentos[] = "h03_data >= '{$dInicial}'";
  }
  if ( !empty($dFinal) ) {
    $aWhereTreinamentos[] = "h03_data <= '{$dFinal}'";
  }

  $sWhereTreinamentos = implode(' and ', $aWhereTreinamentos);
  $sOrderTreinamentos = 'h03_data'; 
  $sSqlTreinamentos   = $oDaoCurric->sql_query_curric(null, '*', $sOrderTreinamentos, $sWhereTreinamentos);  
  $rsTreinamentos     = $oDaoCurric->sql_record($sSqlTreinamentos);

  if ( $oDaoCurric->numrows > 0 ) {

    for ( $iIndiceTreinamentos = 0; $iIndiceTreinamentos < $oDaoCurric->numrows; $iIndiceTreinamentos++ ) {
      $aTreinamentos[$iMatricula][] = db_utils::fieldsMemory($rsTreinamentos, $iIndiceTreinamentos);
    }
  }

}

/**
 * Percorre dados de assentamentos e monta as paginas do relatorio 
 */
foreach ( $aDadosAssentamentos as $iMatricula => $aDadosMatricula ) {

  $iLinhaFaltas      = 0;
  $iLinhaAusencias   = 0;
  $iLinhaPenalidades = 0;
  $iLinhaSuspensoes  = 0;

  $aLinhasFaltas      = array();
  $aLinhasAusencias   = array();
  $aLinhasPenalidades = array();
  $aLinhasSuspensoes  = array();

  /**
   * Matricula
   * Dados de matricula 
   */
  foreach ( $aDadosMatricula as $iAno => $aDadosAno ) {
  
    /**
     * Ano
     * Assentamentos separados por ano  
     */
    foreach ( $aDadosAno as $iMes => $aDadosMes ) {

      /**
       * Mes
       * Assentamentos separados por mes
       */
      foreach ( $aDadosMes as $sCodigo => $oAssentamento ) {
        
        /**
         * Linhas de faltas 
         */
        if ( !empty( $aFaltas[$iMatricula][$iAno][$iMes][$sCodigo] ) ) {

          $aLinhasFaltas[$iLinhaFaltas] = $oAssentamento;
          $iLinhaFaltas++;
        } 

        /**
         * Linhas de ausencias 
         */
        if ( !empty( $aAusencias[$iMatricula][$iAno][$iMes][$sCodigo] ) ) {

          $aLinhasAusencias[$iLinhaAusencias] = $oAssentamento; 
          $iLinhaAusencias++;
        } 

        /**
         * Linhas de penalidades 
         */
        if ( !empty( $aPenalidades[$iMatricula][$iAno][$iMes][$sCodigo] ) ) {

          $aLinhasPenalidades[$iLinhaPenalidades] = $oAssentamento;  
          $iLinhaPenalidades++;
        } 
        
        /**
         * Linhas de suspensoes 
         */
        if ( !empty( $aSuspensoes[$iMatricula][$iAno][$iMes][$sCodigo] ) ) {

          $aLinhasSuspensoes[$iLinhaSuspensoes] = $oAssentamento;
          $iLinhaSuspensoes++;
        } 

      }

    }

  }  

  $oDadosPagina = new StdClass;

  $oDadosPagina->oServidor  = $aServidores[$iMatricula];

  $oDadosPagina->aLinhasFaltas      = $aLinhasFaltas;
  $oDadosPagina->aLinhasAusencias   = $aLinhasAusencias;
  $oDadosPagina->aLinhasPenalidades = $aLinhasPenalidades;
  $oDadosPagina->aLinhasSuspensoes  = $aLinhasSuspensoes;

  /**
   * Total de linhas
   * - Pega a maior das linhas 
   */
  $oDadosPagina->iTotalLinhas = max(array($iLinhaFaltas, $iLinhaAusencias, $iLinhaPenalidades, $iLinhaSuspensoes));

  /**
   * Treinamentos  
   * - se matricula possui treinamento passa variabel para objeto das paginas
   */
  if ( !empty($aTreinamentos[$iMatricula]) ) {

    $oDadosPagina->aTreinamentos = $aTreinamentos[$iMatricula];
  }

  $aPaginas[$iPagina] = $oDadosPagina;
  $iPagina++;
}

/**
 * StdClass com todas as informacoes da instituicao
 */   
$oInstituicao = $oDaoDBConfig->getParametrosInstituicao($iInstituicao); 

/**
 * StdClass com informacoes da instituicao  
 */
$oDadosInstituicao             = new StdClass;
$oDadosInstituicao->sNome      = $oInstituicao->nomeinst;
$oDadosInstituicao->sMunicipio = $oInstituicao->munic;
$oDadosInstituicao->sLogo      = $oInstituicao->logo;

define('PDF_LARGURA', 277);

define('PDF_ALTURA_TITULO',    5);
define('PDF_ALTURA_SUBTITULO', 5);

define('PDF_FONTE_TEXTO',     8);
define('PDF_FONTE_TITULO',    9);
define('PDF_FONTE_SUBTITULO', 9);

define('PDF_BRANCO', 245);
define('PDF_CINZA',  200);

/**
 * Classe para montar relatorio 
 * 
 * @uses fpdf
 */
class PDFHelper extends fpdf {

  public static $aPaginasInternas     = array();
  public $iAlturaMaxima = 0;
  public $oHelper; 
  public $lHeaderAssentamentos = true;
  
  public function __construct($sOrientation = 'P', $sUnit = 'mm', $sFormat = 'A4') {
    parent::__construct($sOrientation, $sUnit, $sFormat);
  }

  public function Header() {

    $this->SetXY(PDF_LARGURA, 10 );
    $this->SetFont('Arial','I',8);
    
    $this->Cell(0,10,'Página '.self::getPaginaInterna($this->oDadosServidor->iMatricula),0,0,'C');
    
    $this->setXY(10,10);

    $this->Image('imagens/files/'. $this->oDadosInstituicao->sLogo,140,8,15);
    $this->Setfont('Arial', 'B', 12);
    $this->setY(30);
    $this->cell(0,5, $this->oDadosInstituicao->sNome, 0, 1, "C");
    $this->Setfont('Arial', 'b', 10);
    $this->cell(0,5,"GABINETE DO EXECUTIVO MUNICIPAL",0,1,"C");
    $this->Setfont('Arial', 'b', 9);
    $this->setY(40);
    $this->cell(0,5,'COMISSÃO ESPECIAL DE AVALIAÇÃO DE DESEMPENHO NO ESTÁGIO PROBÁTORIO',0,1,"C");
    $this->Setfont('Arial', '', 9);
    $this->line(10,$this->gety(), 287, $this->gety());
    $this->ln();

    $this->Setfont('Arial', 'b', 9);
    $this->cell(35,5,'Nome do Funcionário:',0,0,"L");
    $this->Setfont('Arial', '', 9);
    $this->cell(70,5, $this->oDadosServidor->sNome, 0,1,"L");

    $this->Setfont('Arial', 'b', 9);
    $this->cell(15,5,'Matrícula: ',0,0,"L");
    $this->Setfont('Arial', '', 9);
    $this->cell(20,5, ' '.$this->oDadosServidor->iMatricula, 0, 0,"L");
    $this->Setfont('Arial', '', 9);

    $this->Setfont('Arial', 'b', 9);
    $this->cell(30,5,'Data da Nomeação:',0,0);
    $this->Setfont('Arial', '', 9);
    $this->cell(40,5, $this->oDadosServidor->dNomeacao, 0, 0);

    $this->Setfont('Arial', 'b', 9);
    $this->cell(15,5,'Cargo:',0,0);
    $this->Setfont('Arial', '', 9);
    $this->cell(70,5, $this->oDadosServidor->sCargo, 0, 1);
    $this->ln();

    /**
     * Titulo das colunas
     * - false : imprime header dos treinamentos
     * - true  : imprime header dos assentamentos
     */
    if ( !$this->lHeaderAssentamentos ) {

      $this->setfillcolor(PDF_CINZA);

      $this->Setfont('Arial', 'b', PDF_FONTE_TITULO);
      $this->cell( $this->largura(), PDF_ALTURA_SUBTITULO, 'Treinamentos',  1, 0, 'C', 1);
      $this->ln();

      $this->Setfont('Arial', 'b', PDF_FONTE_SUBTITULO);

      $this->cell( $this->largura(15), PDF_ALTURA_SUBTITULO, 'Tipo',       1, 0, 'C');
      $this->cell( $this->largura(10), PDF_ALTURA_SUBTITULO, 'Data',       1, 0, 'C');
      $this->cell( $this->largura(75), PDF_ALTURA_SUBTITULO, 'Descrição',  1, 0, 'C');
      $this->ln();

      $this->Setfont('Arial', '', PDF_FONTE_TEXTO);
      return;
    }

    /**
     * Titulos
     */   
    $this->setfillcolor(PDF_CINZA);
    $this->Setfont('Arial', 'b', PDF_FONTE_TITULO);

    $this->cell( $this->largura(25), PDF_ALTURA_TITULO, 'Faltas Descontadas', 1, 0, 'C', 1);
    $this->cell( $this->largura(25), PDF_ALTURA_TITULO, 'Ausências Abonadas', 1, 0, 'C', 1);
    $this->cell( $this->largura(25), PDF_ALTURA_TITULO, 'Penalidade disciplinar', 1, 0, 'C', 1);
    $this->cell( $this->largura(25), PDF_ALTURA_TITULO, 'Suspensões do Periodo de Estágio', 1, 0, 'C', 1);

    /**
     * Subtitulos  
     */
    $this->ln();
    $this->Setfont('Arial', 'b', PDF_FONTE_SUBTITULO);

    for ($iIndice = 0; $iIndice < 4; $iIndice++) {

      $this->cell( $this->largura(12.33), PDF_ALTURA_SUBTITULO, 'Competência',    1, 0, 'C');
      $this->cell( $this->largura(5.33), PDF_ALTURA_SUBTITULO, 'Código', 1, 0, 'C');
      $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, 'Quantidade', 1, 0, 'C');
    }

    $this->ln();

    $this->Setfont('Arial', '', PDF_FONTE_TEXTO);
  }

  /**
   * Retorna a largura em porcertagem da largura total  
   * 
   * @param float $nPorcentagem - porcentagem da largura total  
   * @access public
   * @return float
   */
  public function largura($nPorcentagem = 0) {

    $iColuna = 0;
    $iTotalLinha = PDF_LARGURA;

    if ( $nPorcentagem == 0 ) {
      return $iTotalLinha;
    }

    $iColuna = $nPorcentagem / 100 * $iTotalLinha;
    $iColuna = round($iColuna, 2);

    return $iColuna;
  }

  /**
   * Escreve colunas, compentecia, quantidade e codigo 
   * 
   * @param string $sCompetencia - competencia, mes e ano
   * @param intger $iQuantidade  - quantidade 
   * @param string $sCodigo      - codigo do assentamento
   * @access public
   * @return void
   */
  public function escreveColunas($sCompetencia = '', $iQuantidade = '', $sCodigo = '') {
  
    $this->cell( $this->largura(12.33), PDF_ALTURA_SUBTITULO, $sCompetencia, 1, 0, 'C');
    $this->cell( $this->largura(5.33), PDF_ALTURA_SUBTITULO, $sCodigo,      1, 0, 'C');
    $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, $iQuantidade,  1, 0, 'C');
  }

  /**
   * Escreve coluna de totais 
   * 
   * @param integer $iTotalFaltas 
   * @param integer $iTotalAusencias 
   * @param integer $iTotalPenalidades 
   * @param integer $iTotalSuspensoes 
   * @access public
   * @return void
   */
  public function escreveTotais($iTotalFaltas, $iTotalAusencias, $iTotalPenalidades, $iTotalSuspensoes) {
  
    $this->Setfont('Arial', 'b');
    $this->cell( $this->largura(17.66), PDF_ALTURA_SUBTITULO, 'Total', 1, 0, 'C');
    $this->Setfont('Arial', '');
    $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, $iTotalFaltas,  1, 0, 'C');
  
    $this->Setfont('Arial', 'b');
    $this->cell( $this->largura(17.66), PDF_ALTURA_SUBTITULO, 'Total', 1, 0, 'C');
    $this->Setfont('Arial', '');
    $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, $iTotalAusencias,  1, 0, 'C');

    $this->Setfont('Arial', 'b');
    $this->cell( $this->largura(17.66), PDF_ALTURA_SUBTITULO, 'Total', 1, 0, 'C');
    $this->Setfont('Arial', '');
    $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, $iTotalPenalidades,  1, 0, 'C');

    $this->Setfont('Arial', 'b');
    $this->cell( $this->largura(17.66), PDF_ALTURA_SUBTITULO, 'Total', 1, 0, 'C');
    $this->Setfont('Arial', '');
    $this->cell( $this->largura(7.33), PDF_ALTURA_SUBTITULO, $iTotalSuspensoes,  1, 0, 'C');

    $this->ln();
  }

  /**
   * Escreve treinamentos do servidor 
   * 
   * @param array $aTreinamentos - array com os treinamentos do servidor 
   * @access public
   * @return void
   */
  public function escreveTreinamentos($aTreinamentos) {

    /**
     * Titulo das colunas
     * Ao trocar de pagina nao imprime mais header das colunas
     */
    $this->lHeaderAssentamentos = false;

    $this->ln(5);
    $this->setfillcolor(PDF_CINZA);
    
    $this->Setfont('Arial', 'b', PDF_FONTE_TITULO);
    $this->cell( $this->largura(), PDF_ALTURA_SUBTITULO, 'Treinamentos',  1, 0, 'C', 1);
    $this->ln();
    
    $this->Setfont('Arial', 'b', PDF_FONTE_SUBTITULO);

    $this->cell( $this->largura(15), PDF_ALTURA_SUBTITULO, 'Tipo',       1, 0, 'C');
    $this->cell( $this->largura(10), PDF_ALTURA_SUBTITULO, 'Data',       1, 0, 'C');
    $this->cell( $this->largura(75), PDF_ALTURA_SUBTITULO, 'Descrição',  1, 0, 'C');
    $this->ln();

    $this->Setfont('Arial', '', PDF_FONTE_TEXTO);
    foreach ( $aTreinamentos as $oTreinamento )  {

      $this->cell( $this->largura(15), PDF_ALTURA_SUBTITULO, $oTreinamento->h02_descr, 1, 0, 'C');
      $this->cell( $this->largura(10), PDF_ALTURA_SUBTITULO, db_formatar($oTreinamento->h03_data, 'd'), 1, 0, 'C');
      $this->cell( $this->largura(75), PDF_ALTURA_SUBTITULO, $oTreinamento->h01_descr,  1, 0, 'C');
      $this->ln();
    }
  }

  /**
   * Adicionar pagina interna 
   * 
   * @param string $sNome 
   * @static
   * @access public
   * @return void
   */
  public static function adicionarPaginaInterna( $sNome ) {
  
    if ( !isset( self::$aPaginasInternas[$sNome] ) ) {
      self::$aPaginasInternas[$sNome] = 1;
    } else {
      self::$aPaginasInternas[$sNome] += 1;
    }
  }

  /**
   * Retorna numero da pagina atual 
   * 
   * @param string $sNome 
   * @static
   * @access public
   * @return integer
   */
  public static function getPaginaInterna ( $sNome ) {
    
    self::adicionarPaginaInterna($sNome);
    return self::$aPaginasInternas[$sNome];
  }

}

$oHelper = new PDFHelper('L', 'mm', 'A4');
$oHelper->open();
$oHelper->AliasNbPages();
$oHelper->SetAutoPageBreak(true, 10);
$oHelper->SetMargins(10, 10, 10);
$oHelper->oDadosInstituicao = $oDadosInstituicao;

/**
 * Escreve uma pagina por matricula 
 */
foreach ( $aPaginas as $iPagina => $oDadosPagina ) {

  $iTotalFaltas      = 0;
  $iTotalAusencias   = 0;
  $iTotalPenalidades = 0;
  $iTotalSuspensoes  = 0;

  $aLinhasFaltas      = $oDadosPagina->aLinhasFaltas;      
  $aLinhasAusencias   = $oDadosPagina->aLinhasAusencias;   
  $aLinhasPenalidades = $oDadosPagina->aLinhasPenalidades; 
  $aLinhasSuspensoes  = $oDadosPagina->aLinhasSuspensoes;  

  $iTotalLinhas = $oDadosPagina->iTotalLinhas;

  $oHelper->oDadosServidor = $oDadosPagina->oServidor;

  $oHelper->lHeaderAssentamentos = true;
  $oHelper->AddPage();

  /**
   * Escreve as linhas 
   * Se as alguma coluna nao tiver valor, imprime celulas vazias
   * Se todas as colunas de uma linha forem vazias entao nao escreve ela 
   */
  for ( $iIndice = 0; $iIndice < $iTotalLinhas; $iIndice++ ) {

    /**
     * Verifica se todas as colunas estao vazias e vai para o proximo indice 
     */
    if ( empty($aLinhasFaltas[$iIndice]) && empty($aLinhasAusencias[$iIndice]) && empty($aLinhasPenalidades[$iIndice]) && empty( $aLinhasSuspensoes[$iIndice]) ) {
      continue;
    }

    /**
     * Faltas
     * Escreve as colunas de falta
     * - se linha atual for vazia entao preenche com celulaas vazias
     */
    if ( !empty( $aLinhasFaltas[$iIndice] ) ) {

      $oHelper->escreveColunas( $aLinhasFaltas[$iIndice]->sCompetencia, $aLinhasFaltas[$iIndice]->iQuantidade, $aLinhasFaltas[$iIndice]->sCodigo );
      $iTotalFaltas += $aLinhasFaltas[$iIndice]->iQuantidade;
    } else {
      $oHelper->escreveColunas();
    } 

    /**
     * Ausencias
     * Escreve as colunas de ausencias
     * - se linha atual for vazia entao preenche com celulaas vazias
     */
    if ( !empty( $aLinhasAusencias[$iIndice] ) ) {

      $oHelper->escreveColunas( $aLinhasAusencias[$iIndice]->sCompetencia, $aLinhasAusencias[$iIndice]->iQuantidade, $aLinhasAusencias[$iIndice]->sCodigo );
      $iTotalAusencias += $aLinhasAusencias[$iIndice]->iQuantidade; 
    } else {
      $oHelper->escreveColunas();
    } 

    /**
     * Penalidades
     * Escreve as colunas de penalidades
     * - se linha atual for vazia entao preenche com celulaas vazias
     */
    if ( !empty( $aLinhasPenalidades[$iIndice] ) ) {

      $oHelper->escreveColunas( $aLinhasPenalidades[$iIndice]->sCompetencia, $aLinhasPenalidades[$iIndice]->iQuantidade, $aLinhasPenalidades[$iIndice]->sCodigo );
      $iTotalPenalidades += $aLinhasPenalidades[$iIndice]->iQuantidade;
    } else {
      $oHelper->escreveColunas();
    } 

    /**
     * Suspensoes
     * Escreve as colunas de suspensoes
     * - se linha atual for vazia entao preenche com celulaas vazias
     */
    if ( !empty( $aLinhasSuspensoes[$iIndice] ) ) {

      $oHelper->escreveColunas( $aLinhasSuspensoes[$iIndice]->sCompetencia, $aLinhasSuspensoes[$iIndice]->iQuantidade, $aLinhasSuspensoes[$iIndice]->sCodigo );
      $iTotalSuspensoes += $aLinhasSuspensoes[$iIndice]->iQuantidade;
    } else {
      $oHelper->escreveColunas();
    } 

    $oHelper->ln();
  }

  /**
   * Totais  
   */
  $oHelper->escreveTotais($iTotalFaltas, $iTotalAusencias, $iTotalPenalidades, $iTotalSuspensoes);

  /**
   * Treinamentos 
   * verifica se tem treinamentos para o servidor e escreve
   */
  if ( !empty($oDadosPagina->aTreinamentos) ) {
    $oHelper->escreveTreinamentos($oDadosPagina->aTreinamentos);
  }

}

/**
 * Output  
 */
$oHelper->Output();