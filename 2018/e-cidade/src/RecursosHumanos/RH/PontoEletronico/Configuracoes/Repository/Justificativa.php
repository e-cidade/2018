<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Repository;

use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Model\Justificativa as JustificativaModel;

/**
 * Classe para manutenção das justificativas
 * Class Justificativa
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Justificativa {

  /**
   * @var \cl_pontoeletronicojustificativa
   */
  private $oDao;

  /**
   * Justificativa constructor.
   */
  public function __construct() {
    $this->oDao = new \cl_pontoeletronicojustificativa();
  }

  /**
   * @param JustificativaModel $oJustificativa
   * @param \Instituicao $oInstituicao
   * @return JustificativaModel
   * @throws \DBException
   */
  public function add(JustificativaModel $oJustificativa, \Instituicao $oInstituicao) {

    $sAcao = $oJustificativa->getCodigo() == null ? 'incluir' : 'alterar';

    $this->oDao->rh194_sequencial  = $oJustificativa->getCodigo();
    $this->oDao->rh194_descricao   = $oJustificativa->getDescricao();
    $this->oDao->rh194_sigla       = $oJustificativa->getAbreviacao();
    $this->oDao->rh194_instituicao = $oInstituicao->getCodigo();
    $this->oDao->{$sAcao}($oJustificativa->getCodigo());

    if($this->oDao->erro_status == '0') {
      throw new \DBException($this->oDao->erro_msg);
    }

    $oJustificativa->setCodigo($this->oDao->rh194_sequencial);

    return $oJustificativa;
  }

  /**
   * @param JustificativaModel $oJustificativa
   * @throws \BusinessException
   * @throws \DBException
   */
  public function remove(JustificativaModel $oJustificativa) {

    if($oJustificativa->getCodigo() == null) {
      throw new \BusinessException('Código da justificativa não informado.');
    }

    $this->oDao->excluir($oJustificativa->getCodigo());

    if($this->oDao->erro_status == '0') {
      throw new \DBException($this->oDao->erro_msg);
    }
  }
}