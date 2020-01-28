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
 * Model para os parametros de mensageria dos acordos a vencer.
 * Class MensageriaAcordo
 * @package Acordo
 */
class MensageriaAcordo {

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

    $oDaoMensageriaAcordo = new cl_mensageriaacordo();
    $sSqlMensageriaAcordo = $oDaoMensageriaAcordo->sql_query_file();
    $rsMensageriaAcordo = $oDaoMensageriaAcordo->sql_record($sSqlMensageriaAcordo);

    if ($oDaoMensageriaAcordo->erro_status == '0') {
      throw new BusinessException(_M('configuracao.configuracao.MensageriaAcordo.erro_buscar_dados'));
    }

    $oStdMensageriaAcordo = db_utils::fieldsMemory($rsMensageriaAcordo, 0);
    $this->iCodigo = $oStdMensageriaAcordo->ac51_sequencial;
    $this->sAssunto = $oStdMensageriaAcordo->ac51_assunto;
    $this->sMensagem = $oStdMensageriaAcordo->ac51_mensagem;
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

    $oDaoMensageriaAcordo = new cl_mensageriaacordo();
    $oDaoMensageriaAcordo->ac51_sequencial = $this->iCodigo;
    $oDaoMensageriaAcordo->ac51_assunto = $this->sAssunto;
    $oDaoMensageriaAcordo->ac51_mensagem = $this->sMensagem;
    $oDaoMensageriaAcordo->alterar($this->iCodigo);

    if ($oDaoMensageriaAcordo->erro_status == '0') {
      throw new BusinessException(_M('configuracao.configuracao.MensageriaAcordo.erro_salvar'));
    }

    return true;
  }

}
