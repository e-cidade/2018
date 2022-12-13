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
 * Classe para controle dos documentos de eventos
 * Class DocumentoEventoAcordo
 *
 */
class DocumentoEventoAcordo {

  /**
   * Código do evento
   * @var integer
   */
  private $iCodigo;

  /**
   * Tipo do documento
   * @var integer
   */
  private $iTipoDocumento;

  /**
   * Documento do contrato
   * @var AcordoDocumento
   */
  private $oAcordoDocumento;

  /**
   * Evento
   * @var AcordoEvento
   */
  private $oEvento;

  /**
   * Codigo do documento;
   * @var integer
   */
  private $iCodigoDocumento;

  /**
   * DocumentoEventoAcordo constructor.
   * @param null $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }
    $oDaoDocumento = new cl_acordodocumentoevento();
    if (!$oDadosDocumento = db_utils::getRowFromDao($oDaoDocumento, array($iCodigo))) {
      throw new BusinessException("Documento nao encontrato no sistema");
    }

    $this->setTipoDocumento($oDadosDocumento->ac57_tipodocumento);
    $this->setCodigo($oDadosDocumento->ac57_sequencial);
    $this->setEvento(new AcordoEvento($oDadosDocumento->ac57_acordoevento));
    $this->iCodigoDocumento = $oDadosDocumento->ac57_acordodocumento;
  }

  /**
   * @return mixed
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param mixed $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return mixed
   */
  public function getTipoDocumento() {
    return $this->iTipoDocumento;
  }

  /**
   * @param mixed $iTipoDocumento
   */
  public function setTipoDocumento($iTipoDocumento) {
    $this->iTipoDocumento = $iTipoDocumento;
  }

  /**
   * @return \AcordoDocumento
   */
  public function getAcordoDocumento() {
    if (empty($this->oAcordoDocumento) && !empty($this->iCodigoDocumento)) {
      $this->oAcordoDocumento = new AcordoDocumento($this->iCodigoDocumento);
    }
    return $this->oAcordoDocumento;
  }

  /**
   * @param mixed $oAcordoDocumento
   */
  public function setAcordoDocumento(AcordoDocumento $oAcordoDocumento) {
    $this->oAcordoDocumento = $oAcordoDocumento;
  }

  /**
   * @return AcordoEvento
   */
  public function getEvento() {
    return $this->oEvento;
  }

  /**
   * @param mixed $iEvento
   */
  public function setEvento($iEvento) {
    $this->oEvento = $iEvento;
  }

  /**
   * @throws DBException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Transação com o banco de dados nao encontrada");
    }

    $oDaoEventoDocumento = new cl_acordodocumentoevento();
    $oDaoEventoDocumento->ac57_acordodocumento = $this->getAcordoDocumento()->getCodigo();
    $oDaoEventoDocumento->ac57_tipodocumento   = $this->getTipoDocumento();
    $oDaoEventoDocumento->ac57_acordoevento    = $this->getEvento()->getCodigo();
    $oDaoEventoDocumento->ac57_sequencial      = $this->getCodigo();

    if (empty($this->iCodigo)) {

      $oDaoEventoDocumento->incluir(null);
      $this->setCodigo($oDaoEventoDocumento->ac57_sequencial);
    } else {
      $oDaoEventoDocumento->alterar($oDaoEventoDocumento->ac57_sequencial);
    }

    if ($oDaoEventoDocumento->erro_status == 0) {

      throw new BusinessException("Erro ao vincular um documento ao evento");
    }
  }

  /**
   * @throws Exception
   */
  public function remover() {

    $oDaoEventoDocumento = new cl_acordodocumentoevento();
    $oDaoEventoDocumento->excluir($this->getCodigo());

    if ($oDaoEventoDocumento->erro_status == 0) {
      throw new BusinessException("Erro ao remover o documento");
    }

    $this->getAcordoDocumento()->remover();
  }

}