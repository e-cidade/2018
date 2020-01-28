<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 *
 * Resultados de um atributo
 * Class ResultadoExameAtributo
 */
class ResultadoExameAtributo {

  const FONTE_MSG = "saude.laboratorio.ResultadoExameAtributo.";

  protected $oAtributo;

  protected $iCodigo;

  protected $sValor;

  protected $nValorPercentual;

  /**
   * faixa de referencia NUmerica Utilizada
   * @var AtributoValorReferenciaNumerico
   */
  protected $oFaixaUtilizada = null;

  /**
   * Titulação do atributo do exame
   * @var string
   */
  protected $sTitulacao;

  /**
   * instancia o valor do Resultado
   * @param null $iCodigo
   */
  public function __construct($iCodigo = null) {
    $this->iCodigo  = $iCodigo;
  }

  /**
   * @return null
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param AtributoExame $oAtributo
   */
  public function setAtributo(AtributoExame $oAtributo) {
    $this->oAtributo = $oAtributo;
  }

  /**
   * @return AtributoExame
   */
  public function getAtributo() {
    return $this->oAtributo;
  }

  /**
   * @param mixed $sValor
   */
  public function setValorAbsoluto($sValor) {
    $this->sValor = $sValor;
  }

  /**
   * Retorna o valor Absoluto
   * @return mixed
   */
  public function getValorAbsoluto() {
    return $this->sValor;
  }


  /**
   * Define o valor percentual
   * @return mixed
   */
  public function setValorPercentual($nValorPercentual) {
    $this->nValorPercentual = $nValorPercentual;
  }

  /**
   * Retorna o valor percentual calculado
   * @return mixed
   */
  public function getValorPercentual() {
    return $this->nValorPercentual;
  }

  /**
   * define a faixa Utilizada como referência
   *
   * @param AtributoValorReferenciaNumerico $oReferencia
   */
  public function setFaixaUtilizada(AtributoValorReferenciaNumerico $oReferencia) {
    $this->oFaixaUtilizada  = $oReferencia;
  }


  /**
   * Retorna a faixa Utilizada
   * @return AtributoValorReferenciaNumerico
   */
  public function getFaixaUtilizada() {
    return $this->oFaixaUtilizada;
  }

  /**
   * Salva os dados do Atributo
   *
   * @param null $iCodigoResultado
   * @throws BusinessException
   */
  public function salvar($iCodigoResultado = null) {

    $oDaoResultadoItem = new cl_lab_resultadoitem();

    /**
     * Quando já temos o atributo do exame salvo, podemos alterar apenas a titulação
     */
    if ( !empty($this->iCodigo) ) {

      $oDaoResultadoItem->la39_i_codigo  = $this->iCodigo;
      $oDaoResultadoItem->la39_titulacao = $this->sTitulacao;
      $oDaoResultadoItem->alterar($this->iCodigo);
    }

    // Inclui o atributo do exame
    if (empty($this->iCodigo)) {

      $oDaoResultadoItem                   = new cl_lab_resultadoitem();
      $oDaoResultadoItem->la39_i_atributo  = $this->getAtributo()->getCodigo();
      $oDaoResultadoItem->la39_i_resultado = $iCodigoResultado;
      $oDaoResultadoItem->la39_titulacao   = $this->sTitulacao;
      $oDaoResultadoItem->incluir(null);

      $this->iCodigo = $oDaoResultadoItem->la39_i_codigo;
    }

    if ($oDaoResultadoItem->erro_status == 0) {
      throw new BusinessException( _M( self::FONTE_MSG . "erro_salvar_resultado") );
    }

    switch ($this->getAtributo()->getTipoReferencia()) {

      case AtributoExame::REFERENCIA_NUMERICA:

        $this->salvarResultadoNumerico();
        break;

      case AtributoExame::REFERENCIA_SELECIONAVEL:

        $this->salvarResultadoAlfaNumerico(true);
        break;

      case AtributoExame::REFERENCIA_FIXA:

        $this->salvarResultadoAlfaNumerico(false);
        break;
    }
  }

  /**
   * Persiste os dados de resultado alfa numerico
   * @throws BusinessException
   */
  protected  function salvarResultadoNumerico() {

    $oDaoResultadoItemNumerico = new cl_lab_resultadonum();
    /**
     * Deletamos os resultados lancados
     */
    $oDaoResultadoItemNumerico->excluir(null, "la41_i_result = {$this->iCodigo}");
    if ($oDaoResultadoItemNumerico->erro_status == 0) {
      throw new BusinessException("Erro ao salvar resultados numericos do atributo para o exame");
    }
    $oDaoResultadoItemNumerico->la41_f_valor         = "{$this->getValorAbsoluto()}";
    $oDaoResultadoItemNumerico->la41_valorpercentual = "{$this->getValorPercentual()}";
    $oDaoResultadoItemNumerico->la41_i_result        = $this->iCodigo;
    if (!empty($this->oFaixaUtilizada)) {
      $oDaoResultadoItemNumerico->la41_faixaescolhida = $this->getFaixaUtilizada()->getCodigo();
    }
    $oDaoResultadoItemNumerico->incluir(null);
    if ($oDaoResultadoItemNumerico->erro_status == 0) {
      throw new BusinessException("Erro ao salvar resultados numericos do atributo para o exame".$oDaoResultadoItemNumerico->erro_msg);
    }
  }

  /**
   * Persiste os resultados alfa numericos
   * @param $lSelecionavel
   * @throws BusinessException
   */
  protected function salvarResultadoAlfaNumerico($lSelecionavel) {

    $oDaoResultadoItemAlfa = new cl_lab_resultadoalfa();
    $oDaoResultadoItemAlfa->excluir(null, "la40_i_result = {$this->iCodigo}");
    if ($oDaoResultadoItemAlfa->erro_status == 0) {
      throw new BusinessException("Erro ao salvar resultados alfanumericos do atributo para o exame");
    }

    $oDaoResultadoItemAlfa->la40_i_result = $this->iCodigo;
    if ($lSelecionavel) {
      $oDaoResultadoItemAlfa->la40_i_valorrefsel = $this->getValorAbsoluto();
    } else {
      $oDaoResultadoItemAlfa->la40_c_valor= $this->getValorAbsoluto();
    }
    $oDaoResultadoItemAlfa->incluir(null);
    if ($oDaoResultadoItemAlfa->erro_status == 0) {
      throw new BusinessException("Erro ao salvar resultados alfanumericos do atributo para o exame");
    }
  }

  /**
   * Informa uma titulação do atributo do exame
   * @param string $sTitulacao
   */
  public function setTitulacao($sTitulacao) {

    $this->sTitulacao = $sTitulacao;
  }

  /**
   * retorna a titulação do atributo do exame
   * @return string
   */
  public function getTitulacao() {

    return $this->sTitulacao;
  }
}