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
class LicitaConValidacao {

  /**
   * @var licitacao
   */
  private $oLicitacao;

  /**
   * @var array
   */
  private $aAtributosDinamicos;

  /**
   * LicitaConValidacao constructor.
   *
   * @param licitacao $oLicitacao
   */
  public function __construct(licitacao $oLicitacao) {
    $this->oLicitacao = $oLicitacao;
  }

  /**
   * Atributos dinâmicos.
   * @param array $aAtributosDinamicos
   */
  public function setAtributosDinamicos($aAtributosDinamicos) {
    $this->aAtributosDinamicos = $aAtributosDinamicos;
  }

  /**
   * @return bool
   * @throws ParameterException
   */
  public function validar() {

    if (empty($this->oLicitacao)) {
      throw new ParameterException("A Licitação deve ser informada para realização da validação.");
    }

    if (empty($this->aAtributosDinamicos)) {
      throw new ParameterException("Os atributos dinâmicos da Licitação não foram informados.");
    }

    $oRegra = new RegraLicitaconRegimeExecucao();
    $oRegra->setLicitacao($this->oLicitacao);
    $oRegra->setAtributosDinamicos($this->aAtributosDinamicos);
    $oRegra->encadearRegra(new RegraLicitaconPermiteSubContratacao())
      ->encadearRegra(new RegraLicitaconTipoBeneficio())
      ->encadearRegra(new RegraLicitaconRegimeDiferenciadoContratacao())
      ->encadearRegra(new RegraLicitaconCredenciamento())
      ->encadearRegra(new RegraLicitaconAdesaoPrecoOutroOrgao())
      ->encadearRegra(new RegraLicitaconModalidadeTipoLicitacaoTipoObjeto())
      ->encadearRegra(new RegraLicitaconPermiteParticipacaoConsorcio())
      ->encadearRegra(new RegraLicitaconRecebeInscricaoPeriodoVigencia());

    return $oRegra->aplicarRegras();
  }

}