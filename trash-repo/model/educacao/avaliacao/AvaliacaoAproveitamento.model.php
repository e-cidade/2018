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
 * Aproveitamento das avaliacoes do aluno
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *         Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.19 $
 */
final class AvaliacaoAproveitamento {

  /**
   * Valor do aproveitamento do aluno para um periodo
   * @var mixed
   */
  private $oValorAproveitamento;

  /**
   * Total de faltas no periodo
   * @var integer
   */
  private $iNumeroFaltas;

  /**
   * Elemento da Avaliacao
   * @var iElementoAvaliacao
   */
  private $oElementoAvaliacao;

  /**
   * Ordem de apresentacao da avaliacao
   * @var integer
   */
  private $iOrdemSequencia;

  /**
   * C�digo do Aproveitamento
   * @var integer
   */
  private $iCodigo;

  /**
   * Define se o Aproveitamento foi superior ao aproveitamento minino
   * configurado para o aproveitamento;
   * @var boolean
   */
  private $lAproveitamentoMinimo = true;

  /**
   * parecer padronizado do periodo
   */
  private $sParecerPadronizado   = '';

  /**
   * define se essa avaliacao � uma Avaliacao Externa a escola
   * @var boolean
   */
  private $lAvaliacaoExterna = false;

  /**
   * Tipo da nota
   * @var string
   */
  private $sTipo = 'M';


  /**
   * Avaliacao est� amparada
   * @var boolean
   */
  private $lAmparado = false;

  /**
   * Instancia da escola que lancou a avaliacao
   * @var iEscola
   */
  private $iEscola;


  /**
   * Define se a nota foi convertida, v�lido s� quando nota externa true
   * @var bool
   */
  private $lConvertido = false;
  
  
  private $sObservacao = '';

  /**
   * Aluno em recuperacao
   * @var bool
   */

  private $lRecuperacao = false;
  /**
   * Caso a avaliacao seja externa, esta vari�vel tem os dados da Avalia��o de origem
   * @var AvaliacaoAproveitamento $oAvaliacaoAproveitamentoOrigem
   */
  private $oAvaliacaoAproveitamentoOrigem = null;

  /**
   * string do parecer
   * @var string
   */
  private $sParecer = '';

  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
  }

  /**
   * Define valor do aproveitamento do aluno para um periodo
   * @param ValorAproveitamento $oValorAproveitamento
   */
  public function setValorAproveitamento(ValorAproveitamento $oValorAproveitamento) {

    $this->oValorAproveitamento = $oValorAproveitamento;
  }

  /**
   * Return valor do aproveitamento do aluno para um periodo
   * @return ValorAproveitamento
   */
  public function getValorAproveitamento() {
    return $this->oValorAproveitamento;
  }

  /**
   * Define numero de faltas do Aluno
   * @param integer $iNumeroFaltas
   */
  public function setNumeroFaltas($iNumeroFaltas) {

    $this->iNumeroFaltas = $iNumeroFaltas;
  }

  /**
   * Retorna o numero de faltas do Aluno
   */
  public function getNumeroFaltas() {

    return $this->iNumeroFaltas;
  }

  /**
   * Define o periodo de alaviacao
   * @param IElementoAvaliacao $oPeriodoAvaliacao
   */
  public function setElementoAvaliacao(IElementoAvaliacao $oElementoAvaliacao) {

    $this->oElementoAvaliacao = $oElementoAvaliacao;
  }

  /**
   * Retorna o periodo de alaviacao
   * @return IElementoAvaliacao
   */
  public function getElementoAvaliacao() {

    return $this->oElementoAvaliacao;
  }

  /**
   * Retorna a ordem do lancamento da nota
   * @return integer
   */
  public function getOrdemSequencia() {
    return $this->oElementoAvaliacao->getOrdemSequencia();
  }

  /**
   * define se o aproveitamento tem um valor acima do aproveitamentoMinimo
   * @param boolean $lTemAproveitamentoMinimo True ou false
   */
  public function setAproveitamentoMinimo($lTemAproveitamentoMinimo) {

    $this->lAproveitamentoMinimo = $lTemAproveitamentoMinimo;
  }


  /**
   * retorna o aproveitamento minimo atigindo
   * @return boolean
   */
  public function temAproveitamentoMinimo() {

    return $this->lAproveitamentoMinimo;
  }

  /**
   * Retorna o codigo do aproveitamento
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o codigo do aproveitamento
   */
  public function setCodigo($iCodigo) {
     $this->iCodigo = $iCodigo;
  }

  /**
   * Define o parecer padronizado do aluno
   * @param string $sParecer texto do parecer
   */
  public function setParecerPadronizado($sParecer = '') {
    $this->sParecerPadronizado = $sParecer;
  }

  /**
   * Retorna o texto do paracer padronizado
   * @return string
   */
  public function getParecerPadronizado() {
    return $this->sParecerPadronizado;
  }

  /**
   * Define se a avaliacao � externa
   */
  public function setAvaliacaoExterna($lAvaliacaoExterna) {

    if (is_bool($lAvaliacaoExterna)) {
      $this->lAvaliacaoExterna = $lAvaliacaoExterna;
    }
  }

  /**
   * Verifica se a avaliacao � externa a Escola.
   * @return string
   */
  public function isAvaliacaoExterna() {

    return $this->lAvaliacaoExterna;
  }

  /**
   * Define o tipo da nota
   * @param string $sTipo define o tipo da nota . os valores aceitos s�o F = para notas de escolas de fora de rede,.
   * e M para escolas da rede.
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  }

  /**
   * Retorna o tipo da nota;
   * os Tipos de Notas retornados sao : F = para notas de escolas de fora de rede,.
   * e M para escolas da rede.
   * @return string Tipo da avaliacao F = Notas de fora da rede , M Para notas da Escola da Rede
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * define a escola que  codigo da escola que lancou a avaliacao
   */
  public function setEscola(iEscola $iEscola) {

    $this->iEscola = $iEscola;
  }

  /**
   * Retorna o codigo da escola que lancou a avaliacao
   * @return iEscola
   */
  public function getEscola() {

    return $this->iEscola;
  }

  /**
   * Retorna o total de faltas abonadas
   * @return integer
   */
  public function getFaltasAbonadas() {

    $iFaltasAbonadas    = 0;

    $oDaoAbonoFalta     = db_utils::getDao("abonofalta");
    $sWhere             = " ed80_i_diarioavaliacao = {$this->getCodigo()} ";
    $sCampos            = " sum(ed80_i_numfaltas) as ed80_i_numfaltas ";
    $sSqlFaltasAbonadas = $oDaoAbonoFalta->sql_query_file(null, $sCampos, null, $sWhere);
    $rsFaltasAbonadas   = $oDaoAbonoFalta->sql_record($sSqlFaltasAbonadas);

    if ($oDaoAbonoFalta->numrows > 0) {
      $iFaltasAbonadas = db_utils::fieldsMemory($rsFaltasAbonadas, 0)->ed80_i_numfaltas;
    }

    return $iFaltasAbonadas;
  }

  /**
   * Define se o aluno est� amparado no per�odo
   * @param boolean $lAmparado true para amparado
   */
  public function setAmparado($lAmparado) {
    $this->lAmparado = $lAmparado;
  }


  /**
   * Verifica se o aluno est� amparado no per�odo
   * @return bool
   */
  public function isAmparado() {
    return $this->lAmparado;
  }

  /**
   * Salvamos um abono para um DiarioAvaliacao
   * @param Justificativa $oJustificativa
   * @param DiarioAvaliacaoDisciplina $oDiarioAvaliacao
   * @param integer $iNumeroFaltasAbonadas
   * @throws DBException
   */
  public function salvarAbono(Justificativa $oJustificativa, $iDiarioAvaliacao, $iFaltasAbonadas) {

  	$oDaoAbonoFalta   = db_utils::getDao("abonofalta");
  	$sWhereAbonoFalta = "ed80_i_diarioavaliacao = {$iDiarioAvaliacao}";
  	$sSqlAbonoFalta   = $oDaoAbonoFalta->sql_query_file(null, "ed80_i_codigo", null, $sWhereAbonoFalta);
  	$rsAbonoFalta     = $oDaoAbonoFalta->sql_record($sSqlAbonoFalta);

  	$oDaoAbonoFalta->ed80_i_justificativa = $oJustificativa->getCodigo();
  	$oDaoAbonoFalta->ed80_i_numfaltas     = $iFaltasAbonadas;

  	if ($oDaoAbonoFalta->numrows > 0) {

  		$iCodigo                       = db_utils::fieldsMemory($rsAbonoFalta, 0)->ed80_i_codigo;
  		$oDaoAbonoFalta->ed80_i_codigo = $iCodigo;
  		$oDaoAbonoFalta->alterar($iCodigo);
  	} else {

  		$oDaoAbonoFalta->ed80_i_diarioavaliacao = $iDiarioAvaliacao;
  		$oDaoAbonoFalta->incluir(null);
  	}

  	if ($oDaoAbonoFalta->erro_status == "0") {
  		throw new DBException($oDaoAbonoFalta->erro_msg);
  	}

  	unset($oDaoAbonoFalta);
  }

  /**
   * Retorna um stdClass com as informacoes do abono para diarioavaliacao, caso exista
   * @return stdClass
   */
  public function getAbono() {

  	$oDadosAbono        = new stdClass();
  	$oDaoAbonoFalta     = db_utils::getDao("abonofalta");
  	$sWhere             = " ed80_i_diarioavaliacao = {$this->getCodigo()} ";
  	$sSqlFaltasAbonadas = $oDaoAbonoFalta->sql_query_file(null, "*", null, $sWhere);
  	$rsFaltasAbonadas   = $oDaoAbonoFalta->sql_record($sSqlFaltasAbonadas);

  	if ($oDaoAbonoFalta->numrows > 0) {

  		$oResultAbonoFalta             = db_utils::fieldsMemory($rsFaltasAbonadas, 0);
  		$oDadosAbono->iCodigo          = $oResultAbonoFalta->ed80_i_codigo;
  		$oDadosAbono->iDiarioAvaliacao = $this->getCodigo();
  		$oDadosAbono->iJustificativa   = $oResultAbonoFalta->ed80_i_justificativa;
  		$oDadosAbono->iFaltasAbonadas  = $oResultAbonoFalta->ed80_i_numfaltas;
  	}

  	return $oDadosAbono;
  }

  /**
   * Excluimos o abono da falta de um DiarioAvaliacao
   * @param integer $iDiarioAvaliacao
   * @throws DBException
   */
  public function excluirAbono($iDiarioAvaliacao) {

  	if (!empty($iDiarioAvaliacao)) {

  		$oDaoAbonoFalta   = db_utils::getDao("abonofalta");
  		$sWhereAbonoFalta = "ed80_i_diarioavaliacao = {$iDiarioAvaliacao}";
  		$sSqlAbonoFalta   = $oDaoAbonoFalta->sql_query_file(null, "ed80_i_codigo", null, $sWhereAbonoFalta);
  		$rsAbonoFalta     = $oDaoAbonoFalta->sql_record($sSqlAbonoFalta);

  		if ($oDaoAbonoFalta->numrows > 0) {

  			$oDaoAbonoFalta->excluir(null, $sWhereAbonoFalta);
  			if ($oDaoAbonoFalta->erro_status == "0") {
  				throw new DBException($oDaoAbonoFalta->erro_msg);
  			}
  		}
  	}
  }

  /**
   * Seta o aproveitamento de origem
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamentoOrigem
   */
  public function setAvaliacaoAproveitamentoOrigem(AvaliacaoAproveitamento $oAvaliacaoAproveitamentoOrigem) {

    $this->oAvaliacaoAproveitamentoOrigem = $oAvaliacaoAproveitamentoOrigem;
  }


  /**
   * Retorna a instancia do Aproveitamento de Origem
   * @return AvaliacaoAproveitamento
   */
  public function getAproveitamentoOrigem() {

    if ($this->isAvaliacaoExterna() && empty($this->oAvaliacaoAproveitamentoOrigem)) {

      $oDaoTransAprov   = db_utils::getDao('transfaprov');
      $sWhereTransAprov = "ed251_i_diariodestino = " . $this->getCodigo();
      $sSqlTransAprov   = $oDaoTransAprov->sql_query_file(null, "ed251_i_diarioorigem", null, $sWhereTransAprov);
      $rsTransAprov     = $oDaoTransAprov->sql_record($sSqlTransAprov);

      if ($oDaoTransAprov->numrows > 0) {

        $iDiarioAvaliacaoDestino = db_utils::fieldsMemory($rsTransAprov, 0)->ed251_i_diarioorigem;

        $oDaoDiarioAvaliacao   = db_utils::getDao('diarioavaliacao');
        $sWhereDiarioAvaliacao = "ed72_i_codigo = {$iDiarioAvaliacaoDestino}";
        $sSqlDiario            = $oDaoDiarioAvaliacao->sql_query_file(null, "ed72_i_diario", null, $sWhereDiarioAvaliacao);
        $rsDiario              = $oDaoDiarioAvaliacao->sql_record($sSqlDiario);

        if ($oDaoDiarioAvaliacao->numrows == 0) {
          return null;
        }

        $iCodigoDiario = db_utils::fieldsMemory($rsDiario, 0)->ed72_i_diario;

        $oDaoDiario          = db_utils::getDao("diario");
        $sSqlDiarioAvaliacao = $oDaoDiario->sql_query_avaliacoes_periodo($iCodigoDiario);
        $rsDiarioAvaliacao   = $oDaoDiario->sql_record($sSqlDiarioAvaliacao);
        $iTotalLinhas        = $oDaoDiario->numrows;

        for($i = 0; $i < $iTotalLinhas; $i++) {

          $oDadosDiario  = db_utils::fieldsMemory($rsDiarioAvaliacao, $i);

          if ($oDadosDiario->codigo == $iDiarioAvaliacaoDestino) {

            $oAvaliacaoAproveitamentoOrigem = new AvaliacaoAproveitamento($oDadosDiario->codigo);
            if ($oDadosDiario->tipo_elemento == "A") {

              $oElementoAvaliacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDadosDiario->codigo_elemento);
            } else {
              $oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosDiario->codigo_elemento);
            }

            $oAvaliacaoAproveitamentoOrigem->setElementoAvaliacao($oElementoAvaliacao);
            $oAvaliacaoAproveitamentoOrigem->setNumeroFaltas($oDadosDiario->numero_faltas);
            $oAvaliacaoAproveitamentoOrigem->setParecerPadronizado($oDadosDiario->parecerpadronizado);
            $oAvaliacaoAproveitamentoOrigem->setAmparado(trim($oDadosDiario->amparo) == "S" ? true : false);
            $oAvaliacaoAproveitamentoOrigem->setConvertido(trim($oDadosDiario->convertido) == "S" ? true : false);
            switch ($oElementoAvaliacao->getFormaDeAvaliacao()->getTipo()) {

              case 'NOTA' :

                $oValorAproveitamento = new ValorAproveitamentoNota($oDadosDiario->valor_nota);
                break;

              case 'PARECER' :

                $oValorAproveitamento = new ValorAproveitamentoParecer($oDadosDiario->parecer);
                break;

              case 'NIVEL' :

                $oValorAproveitamento = new ValorAproveitamentoNivel($oDadosDiario->valor_conceito,
                $oDadosDiario->ordem_conceito);
                break;
            }
            $oAvaliacaoAproveitamentoOrigem->setValorAproveitamento($oValorAproveitamento);
            $oAvaliacaoAproveitamentoOrigem->setAproveitamentoMinimo($oDadosDiario->minimo == "S" ? true : false);
            $lAvaliacaoExterna = false;

            /**
             * a Nota sera externa quando a escola que lancou a avaliacao for
             * diferente da escola atual, ou a origem da nota for 'F', que informa que a nota � de fora da escola.
             */
            if ($oAvaliacaoAproveitamentoOrigem->getTipo() == "F") {

              $oEscolaProcedencia = EscolaProcedenciaRepository::getEscolaByCodigo($oDadosDiario->escola);
              $oAvaliacaoAproveitamentoOrigem->setEscola($oEscolaProcedencia);
            } else {
              $oAvaliacaoAproveitamentoOrigem->setEscola(EscolaRepository::getEscolaByCodigo($oDadosDiario->escola));
            }
            if ($oDadosDiario->tipo_elemento == "A") {

              $oAvaliacaoAproveitamentoOrigem->setTipo($oDadosDiario->origem);
              if ($oAvaliacaoAproveitamentoOrigem->getTipo() == 'F') {
                $lAvaliacaoExterna = true;
              }
            }

            $oAvaliacaoAproveitamentoOrigem->setAvaliacaoExterna($lAvaliacaoExterna);
            $this->setAvaliacaoAproveitamentoOrigem($oAvaliacaoAproveitamentoOrigem);
          }
        }
      }
    }
    return $this->oAvaliacaoAproveitamentoOrigem;
  }


  /**
   * Seta se a nota foi convertida, v�lido s� quando nota externa true
   * @param bool $this->lConvertido
   */
  public function setConvertido($lConvertido) {

    $this->lConvertido = $lConvertido;
  }

  /**
   * Retorna se a nota foi convertida, v�lido s� quando nota externa true
   * @return bool $this->lConvertido
   */
  public function isConvertido() {

    return $this->lConvertido;
  }

  /**
   * seta a observa��o
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
  
  /**
   * retorna a observa��o
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }
  
  /**
   * Retorna a string do parecer
   * @return string
   */
  public function getParecer() {
  
    return $this->sParecer;
  }
  
  /**
   * Seta a string do parecer
   * @param string $sParecer
   */
  public function setParecer($sParecer = '') {
  
    $this->sParecer = $sParecer;
  }
  
  /**
   * Retorna o total n�mero real de faltas
   * Faltas - Faltas abonadas
   * @return number
   */
  public function getTotalFaltas() {
    
    return (int) $this->getNumeroFaltas() - (int) $this->getFaltasAbonadas();
  }

  /**
   * Define o aluno como em recuperacao na disciplina
   * @param boolean $lEmRecuperacao Define o aluno como em recuperacao na disciplina
   */
  public function setEmRecuperacao($lEmRecuperacao) {
    $this->lRecuperacao = $lEmRecuperacao;
  }

  /**
   * Verifica se o aluno est� em recuperacao no per�odo
   * @return bool aluno em recuperacao
   */
  public function emRecuperacao() {
    return $this->lRecuperacao;
  }
  
}