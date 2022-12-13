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

class DocumentoEventoLicitacao {

  const ATA_JULGAMENTO_CREDENCIAMENTO      = 4;
  const ATA_JULGAMENTO_RECURSOS            = 6;
  const ATA_REGISTRO_PRECO                 = 7;
  const ATA_JULGAMENTO_RECURSO             = 8;
  const ATA_PREGAO                         = 9;
  const ATA_PROCEDIMENTO_PRE_QUALIFICACAO  = 10;
  const ATA_RDC                            = 11;
  const ATA_HABILITACAO_PROPOSTAS          = 12;
  const ATA_HABILITACAO_PROPOSTAS_PROJETOS = 13;
  const AVISO_ALTERACAO_EDITAL_ERRATA      = 15;
  const EDITAL_PRE_QUALIFICACAO            = 32;
  const EDITAL_ANEXOS                      = 33;
  const EDITAL_ANEXOS_OUTRO_ORGAO          = 34;

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iCodigoEvento;

  /**
   * @var string
   */
  private $sNomeArquivo;

  /**
   * @var integer OID
   */
  private $iArquivo;

  /**
   * @var integer
   */
  private $iTipoDocumento;

  /**
   * @var string
   */
  private $sArquivoTemporario;

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string Nome do Arquivo
   */
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }

  /**
   * @param string $sNomeArquivo
   */
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * @return integer OID
   */
  public function getArquivo() {
    return $this->iArquivo;
  }

  /**
   * @param integer $iArquivo OID
   */
  public function setArquivo($iArquivo) {
    $this->iArquivo = $iArquivo;
  }

  /**
   * @return integer Tipo do Documento
   */
  public function getTipoDocumento() {
    return $this->iTipoDocumento;
  }

  /**
   * @param integer $iTipoDocumento
   */
  public function setTipoDocumento($iTipoDocumento) {
    $this->iTipoDocumento = $iTipoDocumento;
  }

  /**
   * @return integer
   */
  public function getCodigoEvento() {
    return $this->iCodigoEvento;
  }

  /**
   * @param integer $iCodigoEvento
   */
  public function setCodigoEvento($iCodigoEvento) {
    $this->iCodigoEvento = $iCodigoEvento;
  }

  /**
   * @param string $sArquivoTemporario
   */
  public function setArquivoTemporario($sArquivoTemporario) {
    $this->sArquivoTemporario = $sArquivoTemporario;
  }

  /**
   * @return string
   */
  public function getArquivoTemporario() {
    return $this->sArquivoTemporario;
  }

  /**
   * Construtor
   *
   * @throws DBException
   * @param integer $iCodigo Código do Arquivo
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;

    if ($this->iCodigo !== null) {

      $oDaoDocumento = new cl_liclicitaeventodocumento;
      $sSqlDocumento = $oDaoDocumento->sql_query($this->iCodigo);
      $rsDocumento   = db_query($sSqlDocumento);

      if ($rsDocumento === false || pg_num_rows($rsDocumento) === 0) {
        throw new DBException("Não possível encontrar o documento.");
      }

      $oStdDocumento = db_utils::fieldsMemory($rsDocumento, 0);

      $this->iArquivo       = $oStdDocumento->l47_arquivo;
      $this->sNomeArquivo   = $oStdDocumento->l47_nomearquivo;
      $this->iTipoDocumento = $oStdDocumento->l47_tipodocumento;
      $this->iCodigoEvento  = $oStdDocumento->l47_liclicitaevento;
    }
  }

  /**
   * Salva o registro e arquivo caso o arquivo temporário tenha sido informado
   *
   * @throws DBException
   * @throws ParameterException
   * @return integer Código do registro
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nenhuma transação com o banco de dados encontrada.");
    }

    $oDaoDocumento = new cl_liclicitaeventodocumento;
    $oDaoDocumento->l47_sequencial      = $this->getCodigo();
    $oDaoDocumento->l47_nomearquivo     = $this->getNomeArquivo();
    $oDaoDocumento->l47_tipodocumento   = $this->getTipoDocumento();
    $oDaoDocumento->l47_liclicitaevento = $this->getCodigoEvento();

    if ($this->getArquivoTemporario()) {

      if (!file_exists($this->getArquivoTemporario())) {
        throw new ParameterException('O arquivo temporário informado não existe.');
      }

      $iOid = pg_lo_import($this->getArquivoTemporario());
      if (!$iOid) {
        throw new DBException('Não foi possível importar o arquivo.');
      }

      $oDaoDocumento->l47_arquivo = $iOid;
    }

    if($this->iCodigo) {
      $oDaoDocumento->alterar($this->iCodigo);
    } else {

      $oDaoDocumento->incluir(null);
      $this->iCodigo = $oDaoDocumento->l47_sequencial;
    }

    if ($oDaoDocumento->erro_status == "0") {
      throw new DBException("Não foi possível salvar o documento.");
    }

    return $this->iCodigo;
  }

  /**
   * @param  string $sPastaDestino Pasta de destino onde o arquivo gravado.
   *
   * @throws DBException
   * @throws ParameterException
   * @return string Caminho completo do arquivo exportado.
   */
  public function exportarArquivo($sPastaDestino = 'tmp/') {

    if (!$this->iCodigo) {
      throw new ParameterException('O documento não foi carregado.');
    }

    if (!db_utils::inTransaction()) {
      throw new DBException("Nenhuma transação com o banco de dados encontrada.");
    }

    $sCaminhoFinal = $sPastaDestino . $this->getNomeArquivo();
    $lExportacao   = pg_lo_export($this->iArquivo, $sCaminhoFinal);
    if (!$lExportacao) {
      throw new DBException('Não foi possível exportar o arquivo.');
    }

    return $sCaminhoFinal;
  }

  /**
   * Apaga o registro e o OID no banco de dados.
   *
   * @throws ParameterException
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigo) {
      throw new ParameterException("O documento não foi carregado.");
    }

    if (!db_utils::inTransaction()) {
      throw new DBException("Nenhuma transação com o banco de dados encontrada.");
    }

    if (!pg_lo_unlink($this->iArquivo)) {
      throw new DBException("Não foi possível excluir o arquivo.");
    }

    $oDaoDocumento = new cl_liclicitaeventodocumento;
    $oDaoDocumento->excluir($this->iCodigo);

    if ($oDaoDocumento->erro_status == "0") {
      throw new DBException("Não foi possível excluir o documento.");
    }

    return true;
  }
}
