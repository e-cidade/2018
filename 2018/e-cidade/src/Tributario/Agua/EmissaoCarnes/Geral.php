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

namespace ECidade\Tributario\Agua\EmissaoCarnes;

use regraEmissao;
use AguaEmissao;
use AguaContrato;
use DBLog;

class Geral {

  /**
   * @var integer
   */
  private $iAno;

  /**
   * @var integer
   */
  private $iMesInicial;

  /**
   * @var integer
   */
  private $iMesFinal;

  /**
   * @var regraEmissao
   */
  private $oRegraEmissao;

  /**
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   * @var AguaEmissao
   */
  private $oAguaEmissao;

  /**
   * @var integer
   */
  private $iCodigoTipoArrecadacao;

  /**
   * @var DBLog
   */
  private $oLogger;

  /**
   * @var \DateTime
   */
  private $oDataEmissao;

  /**
   * @var \stdClass
   */
  private $oBarraProgresso;

  /**
   * @param $oBarraProgresso
   */
  public function setBarraProgresso($oBarraProgresso) {
    $this->oBarraProgresso = $oBarraProgresso;
  }

  /**
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return integer
   */
  public function getMesInicial() {
    return $this->iMesInicial;
  }

  /**
   * @param integer $iMesInicial
   */
  public function setMesInicial($iMesInicial) {
    $this->iMesInicial = $iMesInicial;
  }

  /**
   * @return integer
   */
  public function getMesFinal() {
    return $this->iMesFinal;
  }

  /**
   * @param integer $iMesFinal
   */
  public function setMesFinal($iMesFinal) {
    $this->iMesFinal = $iMesFinal;
  }

  /**
   * @return regraEmissao
   */
  public function getRegraEmissao() {
    return $this->oRegraEmissao;
  }

  /**
   * @param regraEmissao $oRegraEmissao
   */
  public function setRegraEmissao(regraEmissao $oRegraEmissao) {
    $this->oRegraEmissao = $oRegraEmissao;
  }

  /**
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param integer $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return AguaEmissao
   */
  public function getAguaEmissao() {
    return $this->oAguaEmissao;
  }

  /**
   * @param AguaEmissao $oAguaEmissao
   */
  public function setAguaEmissao(AguaEmissao $oAguaEmissao) {
    $this->oAguaEmissao = $oAguaEmissao;
  }

  /**
   * @return integer
   */
  public function getCodigoTipoArrecadacao() {
    return $this->iCodigoTipoArrecadacao;
  }

  /**
   * @param integer $iCodigoTipoArrecadacao
   */
  public function setCodigoTipoArrecadacao($iCodigoTipoArrecadacao) {
    $this->iCodigoTipoArrecadacao = $iCodigoTipoArrecadacao;
  }

  /**
   * @return DBLog
   */
  public function getLogger() {
    return $this->oLogger;
  }

  /**
   * @param DBLog $oLogger
   */
  public function setLogger(DBLog $oLogger) {
    $this->oLogger = $oLogger;
  }

  /**
   * @return \DateTime
   */
  public function getDataEmissao() {
    return $this->oDataEmissao;
  }

  /**
   * @param \DateTime $oDataEmissao
   */
  public function setDataEmissao(\DateTime $oDataEmissao) {
    $this->oDataEmissao = $oDataEmissao;
  }

  public function log(AguaContrato $oContrato, $sMensagem) {

    if (!$this->oLogger) {
      throw new \ParameterException('Logger não informado.');
    }

    $this->oLogger->escreverLog("Contrato: {$oContrato->getCodigo()} - {$sMensagem}");
  }

  public function emitir() {

    $this->oBarraProgresso->setMessageLog('(1/3) Buscando Informações dos Contratos...');
    $this->oLogger->escreverLog('Iniciada Emissão Geral.');

    $rsInformacoesEmissao = $this->oAguaEmissao->getInformacoesEmissao();

    $this->oAguaEmissao->removerTabelaTemporaria();
    $this->oAguaEmissao->criarTabelaTemporaria();

    $iContador = 0;
    $iContadorLogradouro = 0;
    $iCodigoLogradouro = null;

    $sArquivo = 'tmp/emissao_geral_tarifa_' . time() . '.txt';
    $sArquivoLayout = 'tmp/emissao_geral_tarifa_layout.txt';
    $oProcessamento = new Processamento($sArquivo, $sArquivoLayout);

    $iTotalContratos = pg_num_rows($rsInformacoesEmissao);
    $iTotalContratosProcessados = 0;

    $this->oBarraProgresso->updateMaxProgress($iTotalContratos);
    $this->oBarraProgresso->setMessageLog('(2/3) Processando Informações...');

    while ($oInformacoes = pg_fetch_object($rsInformacoesEmissao)) {

      try {

        if ($oInformacoes->entrega_codigo_logradouro <> $iCodigoLogradouro) {
          $iContadorLogradouro = 0;
        }

        $oContrato = new AguaContrato($oInformacoes->codigo_contrato);

        $oParcial = new Parcial;
        $oParcial->setContrato($oContrato);
        $oParcial->setCodigoInstituicao($this->iCodigoInstituicao);
        $oParcial->setAguaEmissao($this->oAguaEmissao);
        $oParcial->setInformacoesEmissao($oInformacoes);
        $oParcial->setMesInicial($this->iMesInicial);
        $oParcial->setMesFinal($this->iMesFinal);
        $oParcial->setAno($this->iAno);
        $oParcial->setRegraEmissao($this->oRegraEmissao);
        $oParcial->setCodigoTipoArrecadacao($this->iCodigoTipoArrecadacao);
        $oParcial->setContador($iContador);
        $oParcial->setContadorLogradouro($iContadorLogradouro);
        $oParcial->setDataEmissao($this->oDataEmissao);

        $oDadosParciais = $oParcial->emitir();

        $iContador = $oParcial->getContador();
        $iContadorLogradouro = $oParcial->getContadorLogradouro();
        $iCodigoLogradouro = $oInformacoes->entrega_codigo_logradouro;

        $oProcessamento->escrever($oDadosParciais);
      } catch (\Exception $oErro) {
        $this->log($oContrato, $oErro->getMessage());
      }

      $iTotalContratosProcessados++;
      $this->oBarraProgresso->updatePercentual($iTotalContratosProcessados);
    }

    $this->oBarraProgresso->setMessageLog('(3/3) Preparando Arquivo...');
    $this->oAguaEmissao->removerTabelaTemporaria();
    $oProcessamento->finalizar();

    $this->oLogger->escreverLog('Emissão Geral Concluída.');

    return array(
      'arquivo' => $sArquivo,
      'layout' => $sArquivoLayout
    );
  }
}
