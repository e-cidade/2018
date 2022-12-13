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

/**
 * Model para os parametros de mensageria das licenças a vencer.
 * Class MensageriaLicenca
 * @package Acordo
 */
class MensageriaLicenca {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sAssunto;

  /**
   * @var string
   */
  private $sMensagem;

  /**
   * Construtor - lazy load
   * @throws BusinessException
   * @return MensageriaAcordo
   */
  public function __construct() {

    $oDaoMensageriaLicenca = db_utils::getDao('mensagerialicenca');
    $sSqlMensageriaLicenca = $oDaoMensageriaLicenca->sql_query_file();
    $rsMensageriaLicenca   = $oDaoMensageriaLicenca->sql_record($sSqlMensageriaLicenca);

    if ($oDaoMensageriaLicenca->erro_status == '0') {
      throw new BusinessException(_M('configuracao.configuracao.MensageriaAcordo.erro_buscar_dados'));
    }

    $oStdMensageriaLicenca = db_utils::fieldsMemory($rsMensageriaLicenca, 0);
    $this->iCodigo         = $oStdMensageriaLicenca->am14_sequencial;
    $this->sAssunto        = $oStdMensageriaLicenca->am14_assunto;
    $this->sMensagem       = $oStdMensageriaLicenca->am14_mensagem;
  }

  /**
   * @paragm integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @paragm string $sAssunto
   */
  public function setAssunto($sAssunto) {
    $this->sAssunto = $sAssunto;
  }

  /**
   * @return string
   */
  public function getAssunto() {
    return $this->sAssunto;
  }

  /**
   * @paragm string $sMensagem
   */
  public function setMensagem($sMensagem) {
    $this->sMensagem = $sMensagem;
  }

  /**
   * @return string
   */
  public function getMensagem() {
    return $this->sMensagem;
  }

  /**
   * @return bool
   * @throws BusinessException
   */
  public function salvar() {

    if (empty($this->iCodigo)) {
      throw new BusinessException(_M('configuracao.configuracao.MensageriaAcordo.erro_codigo_nao_definido'));
    }

    $oDaoMensageriaLicenca = db_utils::getDao('mensagerialicenca');
    $oDaoMensageriaLicenca->am14_sequencial = $this->iCodigo;
    $oDaoMensageriaLicenca->am14_assunto    = $this->sAssunto;
    $oDaoMensageriaLicenca->am14_mensagem   = $this->sMensagem;
    $oDaoMensageriaLicenca->alterar($this->iCodigo);

    if ($oDaoMensageriaLicenca->erro_status == '0') {
      throw new BusinessException(_M('configuracao.configuracao.MensageriaAcordo.erro_salvar'));
    }

    return true;
  }

}
