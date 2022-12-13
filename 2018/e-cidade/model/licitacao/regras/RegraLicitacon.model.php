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

abstract class RegraLicitacon {

  protected $sMensagem = null;

  /**
   * @var licitacao
   */
  protected $oLicitacao;

  /**
   * @var array
   */
  protected $aAtributosDinamicos;

  /**
   * @var RegraLicitacon
   */
  protected $oProxima;

  /**
   * Mensagem de erro.
   * @return string
   */
  public function getMensagem() {
    return $this->sMensagem;
  }

  /**
   * @param licitacao $oLicitacao
   */
  public function setLicitacao(licitacao $oLicitacao) {
    $this->oLicitacao = $oLicitacao;
  }

  /**
   * @param array $aAtributosDinamicos
   */
  public function setAtributosDinamicos($aAtributosDinamicos) {
    $this->aAtributosDinamicos = $aAtributosDinamicos;
  }

  /**
   * Configura a próxima regra a ser aplicada e a retorna.
   * @param RegraLicitacon $oRegra
   * @return RegraLicitacon
   */
  public function encadearRegra(RegraLicitacon $oRegra) {

    $this->oProxima = $oRegra;
    $this->oProxima->setLicitacao($this->oLicitacao);
    $this->oProxima->setAtributosDinamicos($this->aAtributosDinamicos);
    return $this->oProxima;
  }

  /**
   * Aplica validação.
   * @return bool
   */
  protected abstract function regra();

  /**
   * Aplica a regra conforme padrão Chain of Responsability.
   * @return bool
   * @throws BusinessException
   */
  public function aplicarRegras() {

    if (!$this->regra()) {
      throw new BusinessException($this->getMensagem());
    }
    return $this->proximo();
  }

  /**
   *  Chama o próximo teste, se existir.
   * @return bool
   */
  protected function proximo() {

    if ($this->oProxima) {
      return $this->oProxima->aplicarRegras();
    }
    return true;
  }
}