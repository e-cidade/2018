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
 * Resultado final da avaliacao - diariofinal
 * @author  Fabio Esteves - fabio.esteves@dbseller.com.br
 * @package educacao
 * @subpackage avaliacao
 * @version $Revision: 1.16 $
 */
class AvaliacaoResultadoFinal {

  /**
   * Codigo do resultado final
   * @var integer
   */
  private $iCodigoResultadoFinal;

  /**
   * Codigo do diario
   * @var integer
   */
  private $iCodigoDiario;

  /**
   * Instancia de ResultadoAvaliacao
   * @var ResultadoAvaliacao
   */
  private $oResultadoAvaliacao;

  /**
   * Nota da avaliacao
   * @var mixed
   */
  private $mValorAprovacao = '';

  /**
   * Observacao para o resultado final
   * @var string
   */
  private $sObservacao;

  /**
   * Resultado da aprovacao
   * @var string
   */
  private $sResultadoAprovacao = '';

  /**
   * Resultado da frequencia;
   * @var string
   */
  private $sResultadoFrequencia;
  /**
   * Resultado final
   * @var string
   */
  private $sResultadoFinal = '';

  private $nPercentualFrequencia = 0;

  public function __construct(DiarioAvaliacaoDisciplina $oDiarioAvaliacaoDisciplina) {

    $this->iCodigoDiario = $oDiarioAvaliacaoDisciplina->getCodigoDiario();

    $oDaoDiarioFinal    = db_utils::getDao('diariofinal');
    $sSqlAvaliacaoFinal = $oDaoDiarioFinal->sql_query_file(null, "*", null, "ed74_i_diario={$this->iCodigoDiario}");
    $rsAvaliacaoFinal   = $oDaoDiarioFinal->sql_record($sSqlAvaliacaoFinal);

    if ($rsAvaliacaoFinal) {

      $oDados                      = db_utils::fieldsMemory($rsAvaliacaoFinal, 0);
      $this->iCodigoDiario         = $oDados->ed74_i_diario;
      $this->iProcResultado        = $oDados->ed74_i_procresultadoaprov;
      $this->iCodigoResultadoFinal = $oDados->ed74_i_codigo;
      $this->mValorAprovacao       = $oDados->ed74_c_valoraprov;
      $this->sResultadoAprovacao   = $oDados->ed74_c_resultadoaprov;
      $this->sResultadoFrequencia  = $oDados->ed74_c_resultadofreq;
      $this->nPercentualFrequencia = $oDados->ed74_i_percfreq;
      $this->sResultadoFinal       = $oDados->ed74_c_resultadofinal;
      $this->sObservacao           = $oDados->ed74_t_obs;
      $this->oResultadoAvaliacao   = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDados->ed74_i_procresultadoaprov);
    }
  }

  /**
   * Retorna o codigo do resultado final
   * @return integer
   */
  public function getCodigoResultadoFinal() {
    return $this->iCodigoResultadoFinal;
  }

  /**
   * Retorna o codigo do diario
   * @return integer
   */
  public function getCodigoDiario() {
    return $this->iCodigoDiario;
  }

  /**
   * Retorna a instancia de ResultadoAvaliacao
   * @return ResultadoAvaliacao
   */
  public function getResultadoAvaliacao() {
    return $this->oResultadoAvaliacao;
  }

  /**
   * Atribui uma instancia de ResultadoAvaliacao
   * @param ResultadoAvaliacao $oResultadoAvaliacao
   */
  public function setResultadoAvaliacao(ResultadoAvaliacao $oResultadoAvaliacao) {
    $this->oResultadoAvaliacao = $oResultadoAvaliacao;
  }

  /**
   * Retorna o valor da aprovacao
   * @return mixed
   */
  public function getValorAprovacao() {
    return $this->mValorAprovacao;
  }

  /**
   * Atribui o valor da nota de aprovacao
   * @param mixed $mValorAprovacao
   */
  public function setValorAprovacao($mValorAprovacao) {
    $this->mValorAprovacao = $mValorAprovacao;
  }

  /**
   * Retorna o resultado da aprovacao
   * @return string
   */
  public function getResultadoAprovacao() {
    return $this->sResultadoAprovacao;
  }

  /**
   * Atribui um resultado de aprovacao
   * @param string $sResultadoAprovacao
   */
  public function setResultadoAprovacao($sResultadoAprovacao) {
    $this->sResultadoAprovacao = $sResultadoAprovacao;
  }

  /**
   * Define o resultado final da disciplina
   * OS valores validos para aprovacao : 'A'  Aprovado 'R' Reprovado'
   * @param string $sResultadoFinal resultado final da Disciplina
   */
  public function setResultadoFinal($sResultadoFinal) {
    $this->sResultadoFinal = $sResultadoFinal;
  }

  /**
   * Retorna o resultado final da disciplina
   * Os valores validos para aprovacao : 'A'  Aprovado 'R' Reprovado'
   * @return string Resultado final da disciplina
   */
  public function getResultadoFinal() {
    return $this->sResultadoFinal;
  }

  /**
   * Retorna o resultado da frequencia
   * Os valores validos para aprovacao : 'A'  Aprovado 'R' Reprovado'
   * @return string Resultado frequencia da disciplina
   */
  public function getResultadoFrequencia() {
    return $this->sResultadoFrequencia;
  }

  /**
   * Retorna a observacao referente ao resultado final
   * @return string
   */

  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Atribui uma observacao ao resultado final
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao = '') {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna o percentual de frequencia
   * @return numeric
   */
  public function getPercentualFrequencia() {
    return $this->nPercentualFrequencia;
  }

  public function setPercentualFrequencia($nPercentual) {
    $this->nPercentualFrequencia = $nPercentual;
  }

  /**
   * Persiste as informacoes do diario final
   */
  public function salvar() {

  	if (!db_utils::inTransaction()) {
  		throw new DBException("Não existe transação com o banco de dados ativa");
  	}

    $oDaoDiarioFinal = db_Utils::getDao("diariofinal");
    if (empty($this->sResultadoFinal)) {
      $GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofinal"] = '';
    }

    if (empty($this->sResultadoFrequencia)) {
      $GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadofreq"] = '';
    }

    if (empty($this->sResultadoAprovacao)) {
      $GLOBALS["HTTP_POST_VARS"]["ed74_c_resultadoaprov"] = '';
    }
    
    if (empty($this->ed74_c_valoraprov)) {
      $GLOBALS["HTTP_POST_VARS"]["ed74_c_valoraprov"] = '';
    }

    /**
     * Caso o aluno está aprovado pelo conselho, o resultado final do mesmo deverá
     * ser sempre aprovado.
     */
    if ($this->getFormaAprovacaoConselho() != "") {
      $this->setResultadoFinal('A');
    }

    $oDaoDiarioFinal->ed74_i_procresultadoaprov = $this->getResultadoAvaliacao()->getCodigo();
    $oDaoDiarioFinal->ed74_c_valoraprov         = "{$this->getValorAprovacao()}";
    $oDaoDiarioFinal->ed74_c_resultadoaprov     = trim($this->getResultadoAprovacao());
    $oDaoDiarioFinal->ed74_i_procresultadofreq  = $this->getResultadoAvaliacao()->getCodigo();
    $oDaoDiarioFinal->ed74_i_percfreq           = "{$this->nPercentualFrequencia}";
    $oDaoDiarioFinal->ed74_c_resultadofreq      = $this->getResultadoFrequencia();
    $oDaoDiarioFinal->ed74_c_resultadofinal     = $this->getResultadoFinal();
    $oDaoDiarioFinal->ed74_i_calcfreq           = '1';
    if (empty($this->sObservacao)) {
      $GLOBALS["HTTP_POST_VARS"]["ed74_t_obs"] = '';
    }
    $oDaoDiarioFinal->ed74_t_obs = "{$this->getObservacao()}";

    $oDaoDiarioFinal->ed74_i_diario = $this->getCodigoDiario();
    if (!empty($this->iCodigoResultadoFinal)) {

      $oDaoDiarioFinal->ed74_i_codigo = $this->getCodigoResultadoFinal();
      $oDaoDiarioFinal->alterar($oDaoDiarioFinal->ed74_i_codigo);
    } else {

      $oDaoDiarioFinal->incluir(null);
      $this->iCodigoResultadoFinal = $oDaoDiarioFinal->ed74_i_codigo;
    }
    if ($oDaoDiarioFinal->erro_status == 0) {
      throw new BusinessException("Erro ao salvar o resultado final.\n{$oDaoDiarioFinal->erro_msg}");
    }
  }

  /**
   * Verifica se o aluno foi aprovado na disciplina atraves de progressao parcial
   * @return boolean
   */
  public function aprovadoPorProgressaoParcial() {

    $oDaoProgressaoParcialAluno = new cl_progressaoparcialalunodiariofinalorigem();
    $sWhere                     = "     ed107_diariofinal = {$this->getCodigoResultadoFinal()}";
    $sWhere                    .= " and ed114_situacaoeducacao <> ".ProgressaoParcialAluno::INATIVA;
    $sSqlProgressaoParcial      = $oDaoProgressaoParcialAluno->sql_query( null, '1', null, $sWhere );
    $rsProgressaoParcial        = $oDaoProgressaoParcialAluno->sql_record( $sSqlProgressaoParcial );
    
    if ($oDaoProgressaoParcialAluno->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Retorna se o aluno foi aprovado
   * @return boolean|string
   */
  public function isAprovado() {

    switch (trim($this->sResultadoFinal)) {

      case 'R':

        return false;
        break;
      case 'A':

        return true;
        break;
      default:

        return '';
        break;
    }

  }

  /**
   * Define o resultado final da frequencia
   * @param string $sResultadoFrequencia resultado da frequencia = A para aprovado R para reprovado
   */
  public function setResultadoFrequencia ($sResultadoFrequencia) {
     $this->sResultadoFrequencia = $sResultadoFrequencia;
  }

  /**
   * Retorna uma instancia de AprovacaoConselho, com os dados a aprovacao
   * @todo Mover Codigo do construtor para dentro da AprovacaoConselho
   * @return AprovacaoConselho
   */
  public function getFormaAprovacaoConselho() {

  	$oAprovacaoConselho  = null;
  	$oDaoAprovConselho   = new cl_aprovconselho();
  	$sWhereAprovConselho = "ed253_i_diario = {$this->iCodigoDiario}";
  	$sSqlAprovConselho   = $oDaoAprovConselho->sql_query_file(null, "*", null, $sWhereAprovConselho);
  	$rsAprovConselho     = $oDaoAprovConselho->sql_record($sSqlAprovConselho);
  	$iTotalAprovConselho = $oDaoAprovConselho->numrows;

  	if ($iTotalAprovConselho > 0) {

  		$oAprovacaoConselho  = new AprovacaoConselho($this);
  		$oDadosAprovConselho = db_utils::fieldsMemory($rsAprovConselho, 0);

  		$oAprovacaoConselho->setCodigo($oDadosAprovConselho->ed253_i_codigo);
  		$oAprovacaoConselho->setFormaAprovacao($oDadosAprovConselho->ed253_aprovconselhotipo);

  		if (!empty($oDadosAprovConselho->ed253_i_rechumano)) {
  		  $oAprovacaoConselho->setRecursoHumano($oDadosAprovConselho->ed253_i_rechumano);
  		}

  		if (!empty($oDadosAprovConselho->ed253_t_obs)) {
  		  $oAprovacaoConselho->setJustificativa($oDadosAprovConselho->ed253_t_obs);
  		}

  		$oAprovacaoConselho->setData(new DBDate(date("Y-m-d", $oDadosAprovConselho->ed253_i_data)));
  		$oAprovacaoConselho->setHora(date("H:i", $oDadosAprovConselho->ed253_i_data));
  		$oAprovacaoConselho->setUsuario(new UsuarioSistema($oDadosAprovConselho->ed253_i_usuario));
  	}

  	return $oAprovacaoConselho;
  }
}
?>