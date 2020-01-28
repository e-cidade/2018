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

/*
 * Class ProcessamentoRubricaPeriodo
 * Controle das rubricas que devem ser lançadas por um período de tempo.
 */
class ProcessamentoRubricasPeriodo implements InicializacaoPontoInterface {

  /**
   * @var Servidor;
   */
  private $oServidor = null;
  /**
   * Realizamos o processamento das rubricas por período.
   * @param \Servidor $oServidor
   */
  public function processar(Servidor $oServidor) {

    $this->oServidor = $oServidor;

    $aRubricas = PontoRubricaPeriodoRepository::getPontoRubricasByServidor($this->oServidor);

    /**
     * Percorremos as rubricas configuradas com data inicio e fim para o servidor que está sendo processado.
     * Verificamos se a competencia atual coincide com a data inicial ou final, caso sim executamos a proprcionalização
     * e adicionamos ou excluimos a rubrica do ponto dixo.
     */
    foreach ($aRubricas as $oPontoRubricaPeriodo) {
      $this->verificaRegistro($oPontoRubricaPeriodo);
    }
  }


  /**
   * Fazemos o tratamento para cada registro, verificando se é inicio ou fim.
   * Caso seja inicio adiciona o valor proporcional no ponto de salario. E o valor 'cheio' no ponto fixo
   * Caso seja fim adicionamos o valor proporcional ao ponto de salário e removemos do ponto fixo.
   * 
   * @param  PontoRubricaPeriodo $oPontoRubricaPeriodo 
   * @return void
   */
  private function verificaRegistro(PontoRubricaPeriodo $oPontoRubricaPeriodo) {
    
    $oRegistroPonto = $this->proporcionaliza($oPontoRubricaPeriodo);

    if ($oRegistroPonto->getValor() > 0 || $oRegistroPonto->getQuantidade() > 0) {
      $this->adicionarPontoSalario($oRegistroPonto);
    }

    if ( $this->verificaMesmaCompetencia($oPontoRubricaPeriodo->getDataInicio()) && !$this->verificaMesmaCompetencia($oPontoRubricaPeriodo->getDataFim())) {
      $this->adicionaPontoFixo($oPontoRubricaPeriodo);
    }

    if ( !$this->verificaMesmaCompetencia($oPontoRubricaPeriodo->getDataInicio()) && $this->verificaMesmaCompetencia($oPontoRubricaPeriodo->getDataFim())) {
      PontoRubricaPeriodoRepository::remove($oPontoRubricaPeriodo->getCodigo());
      $this->removerPontoFixo($oPontoRubricaPeriodo);
    }
  }

  /**
   * Adicionamos a rubrica ao ponto fixo.
   * @param  \PontoRubricaPeriodo $oPontoRubricaPeriodo
   * @return void
   */
  private function adicionaPontoFixo(PontoRubricaPeriodo $oPontoRubricaPeriodo) {

    $oPontoFixo     = $this->oServidor->getPonto(Ponto::FIXO);
    $oRegistroPonto = new RegistroPonto();
    $oRegistroPonto->setQuantidade($oPontoRubricaPeriodo->getQuantidade());
    $oRegistroPonto->setValor($oPontoRubricaPeriodo->getValor());
    $oRegistroPonto->setServidor($this->oServidor);
    $oRegistroPonto->setRubrica($oPontoRubricaPeriodo->getRubrica());
    $oPontoFixo->carregarRegistros($oRegistroPonto->getRubrica()->getCodigo());
    $oPontoFixo->limpar($oRegistroPonto->getRubrica()->getCodigo());
    $oPontoFixo->adicionarRegistro($oRegistroPonto);
    $oPontoFixo->salvar();
  }

  /**
   * Realizamos a proporção do valor ou da quantidade da rubrica, 
   * levando em consideração a data inicio ou a data fim.
   * @param  \PontoRubricaPeriodo $oPontoRubricaPeriodo
   * @param  boolean -true: Proporcionaliza para data inicial de pagamento.
   *                 -false: Proporcionaliza para a data final de pagamento.
   * @return RegistroPonto $oRegistroPonto
   */
  private function proporcionaliza(PontoRubricaPeriodo $oPontoRubricaPeriodo) {

    $iQuantidade      = $oPontoRubricaPeriodo->getQuantidade();
    $nValor           = $oPontoRubricaPeriodo->getValor();
    $iQuantidadeDias = $this->verificaQuantidadeDias($oPontoRubricaPeriodo);
    $nValor          = $iQuantidadeDias * $nValor / 30;
    $iQuantidade     = $iQuantidadeDias * $iQuantidade / 30;

    $oRegistroPonto = new RegistroPonto();
    $oRegistroPonto->setQuantidade(round($iQuantidade, 2));
    $oRegistroPonto->setValor(round($nValor, 2));
    $oRegistroPonto->setServidor($this->oServidor);
    $oRegistroPonto->setRubrica($oPontoRubricaPeriodo->getRubrica());

    return $oRegistroPonto;
  }

  /**
   * Verifica a quantidade de dias que deve ser proporcionalizado, levando em conideração
   * se a competência atual é inicio ou fim do pagamento.
   * 
   * @param  PontoRubricaPeriodo $oPontoRubricaPeriodo
   * @return integer Quantidade de dias
   */
  private function verificaQuantidadeDias(PontoRubricaPeriodo $oPontoRubricaPeriodo) {

     $oDataInicio = $oPontoRubricaPeriodo->getDataInicio();
     $oDataFim   = $oPontoRubricaPeriodo->getDataFim();
     

     /**
      * Proporcionalizamos quando o inicio e fim acontece na mesma competencia.
      */
     if ( $this->verificaMesmaCompetencia($oDataInicio) && $this->verificaMesmaCompetencia($oDataFim)) {
       return DBDate::calculaIntervaloEntreDatas($oDataFim, $oDataInicio, 'd') + 1;
     }

     /**
      * Proporcionalizamos para quando inicia o pagamento nesta competencia
      */
     if ( $this->verificaMesmaCompetencia($oDataInicio)) {

       $oDataFim = new DBDate(date('Y-m-30',$oDataInicio->getTimeStamp()));
       return DBDate::calculaIntervaloEntreDatas($oDataFim, $oDataInicio, 'd') + 1;
     }

     /**
      * Proporcionalizamos para quando o termino do pagamento é nesta competência.
      */
     if ( $this->verificaMesmaCompetencia($oDataFim)) {

       $oDataInicio = new DBDate(date('Y-m-1',$oDataFim->getTimeStamp()));
       $iQuantidadeDias = DBDate::calculaIntervaloEntreDatas($oDataFim, $oDataInicio, 'd') + 1;
       return ($iQuantidadeDias > 30) ? 30 : $iQuantidadeDias;
     }
  }

  /**
   * Verificamos se a data informada por parâmetro pertence a competencia atual
   * @param  DBDate $oData
   * @return boolean - true: Data informada pertence a competência atual.
   *                 - false: Data informada não pertence a competência atual.
   */
  private function verificaMesmaCompetencia(DBDate $oData) {

    if (DBPessoal::getAnoFolha() == $oData->getAno() && DBPessoal::getMesFolha() == $oData->getMes()) {
      return true;
    }

    return false;
  }

  /**
   * Adiciona o Registro do ponto ao ponto de salario.
   * @param  RegistroPonto $oRegistroPonto
   * @return void
   */
  private function adicionarPontoSalario(RegistroPonto $oRegistroPonto) {

    $oPontoSalario = $this->oServidor->getPonto(Ponto::SALARIO);
    $oPontoSalario->carregarRegistros($oRegistroPonto->getRubrica()->getCodigo());
    $oPontoSalario->limpar($oRegistroPonto->getRubrica()->getCodigo());    
    $oPontoSalario->adicionarRegistro($oRegistroPonto);
    $oPontoSalario->salvar();
  }

  /**
   * Removemos a rubrica do ponto fixo
   * @param  PontoRubricaPeriodo $oRegistroPonto
   * @return void
   */
  private function removerPontoFixo(PontoRubricaPeriodo $oRegistroPonto) {

    $oPontoFixo     = $this->oServidor->getPonto(Ponto::FIXO);
    $oPontoFixo->carregarRegistros($oRegistroPonto->getRubrica()->getCodigo());
    $oPontoFixo->limpar($oRegistroPonto->getRubrica()->getCodigo());
    $oPontoFixo->salvar();
  }
}