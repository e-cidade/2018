<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
* 
*/
class ProcessamentoPontoConsignadosCaixa extends ProcessamentoPontoConsignados{

  /**
   * Controle de saldos do salario do servidor
   * @var array
   */
  private $aSaldoSalarioServidor = array();

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Realiza a inclusão do re
   * @param \ConfiguracaoConsignado $oConfiguracaoConsignado
   * @throws \DBException
   */
  public function importarDadosPreponto(ConfiguracaoConsignado $oConfiguracaoConsignado) {

    $this->oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    $oCompetencia       = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
    $oUltimoArquivo     = ArquivoConsignadoRepository::getUltimoArquivoNaCompetenciaDoBanco($this->oInstituicao, $oCompetencia, $oConfiguracaoConsignado->getBanco());
    if (empty($oUltimoArquivo)) {
      return true;
    }

    if ($oUltimoArquivo->isProcessado()) {
      return;
    }
    $aRegistrosValidos = $oUltimoArquivo->getRegistrosValidos(RegistroConsignadoRepository::ORDEM_MAIOR_VALOR_SERVIDOR);
    foreach ($aRegistrosValidos as $oRegistro) {

      $nSaldoServidor = $this->getSaldoDoServidor($oRegistro->getServidor());
      $oUltimoArquivo->adicionarRegistro($oRegistro);
      if ($oRegistro->getValorDescontar() > $nSaldoServidor) {

        $oRegistro->setMotivo(RegistroConsignado::MOTIVO_SALDO_INSUFICIENTE);
        continue;
      }

      /**
       * Calculamos o novo saldo do servidor
       */
      $nSaldo = $nSaldoServidor - $oRegistro->getValorDescontar();

      $this->setSaldoServidor($oRegistro->getServidor(), $nSaldo);
      $oRegistro->setValorDescontado($oRegistro->getValorDescontar());
      $this->salvarNoPonto($oRegistro);

    }

    $oUltimoArquivo->setProcessado(true);
    ArquivoConsignadoRepository::persist($oUltimoArquivo);
    return;
  }

  /**
   * @param \Servidor $oServidor
   * @return mixed
   * @throws \BusinessException
   */
  private function getSaldoDoServidor(Servidor $oServidor) {

    if (!isset($this->aSaldoSalarioServidor[$oServidor->getMatricula()])) {

      $oCalculo = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      $nValor   = 0;
      if (!empty($oCalculo)) {
        $nValor = $oCalculo->getValorLiquido();
      }
      $this->setSaldoServidor($oServidor, $nValor);
    }
    return $this->aSaldoSalarioServidor[$oServidor->getMatricula()];
  }

  /**
   * @param \Servidor $oServidor
   * @param           $nValor
   */
  private function setSaldoServidor(Servidor $oServidor, $nValor) {
    $this->aSaldoSalarioServidor[$oServidor->getMatricula()] = $nValor;
  }

  private function salvarNoPonto(RegistroConsignado $oRegistro) {

    $oDaoPrePonto = new cl_rhpreponto();
    $oDaoPrePonto->rh149_instit     = $this->oInstituicao->getSequencial();
    $oDaoPrePonto->rh149_regist     = $oRegistro->getServidor()->getMatricula();
    $oDaoPrePonto->rh149_rubric     = $oRegistro->getRubrica()->getCodigo();
    $oDaoPrePonto->rh149_valor      = $oRegistro->getValorDescontado();
    $oDaoPrePonto->rh149_quantidade = 1;
    $oDaoPrePonto->rh149_tipofolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
    $oDaoPrePonto->incluir(null);
    if ($oDaoPrePonto->erro_status == 0) {
      throw new BusinessException("Erro ao salvar Dados do pre-ponto");
    }
  }
}