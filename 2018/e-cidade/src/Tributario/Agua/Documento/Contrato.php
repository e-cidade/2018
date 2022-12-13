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

namespace ECidade\Tributario\Agua\Documento;

use PhpOffice\PhpWord\TemplateProcessor;
use DocumentConverter;
use documentoTemplate;

class Contrato {

  const CODIGO_TIPO_DOCUMENTO = 55;

  /**
   * @var int
   */
  private $iCodigoContrato;

  /**
   * @var \AguaEmissao
   */
  private $oAguaEmissao;

  /**
   * @var \DBDate
   */
  private $oDataDocumento;

  /**
   * @param $iCodigoContrato
   */
  public function setCodigoContrato($iCodigoContrato) {
    $this->iCodigoContrato = $iCodigoContrato;
  }

  /**
   * @return int
   */
  public function getCodigoContrato() {
    return $this->iCodigoContrato;
  }

  /**
   * @param \AguaEmissao $oAguaEmissao
   */
  public function setAguaEmissao(\AguaEmissao $oAguaEmissao) {
    $this->oAguaEmissao = $oAguaEmissao;
  }

  /**
   * @return \AguaEmissao
   */
  public function getAguaEmissao() {
    return $this->oAguaEmissao;
  }

  /**
   * @return \DBDate
   */
  public function getDataDocumento() {
    return $this->oDataDocumento;
  }

  /**
   * @param \DBDate $oDataDocumento
   */
  public function setDataDocumento(\DBDate $oDataDocumento) {
    $this->oDataDocumento = $oDataDocumento;
  }

  /**
   * Consulta os dados do contrato
   * @return \stdClass
   * @throws \DBException
   */
  private function getInformacoesContrato() {

    $sWhere = "x54_sequencial = {$this->iCodigoContrato}";
    $sOrderBy = implode(', ', array(
      'entrega_zona',
      'entrega_codigo_logradouro',
      'entrega_orientacao',
      'entrega_numero',
      'entrega_complemento',
      'codigo_matricula',
    ));
    $sQuery = $this->oAguaEmissao->queryInformacoesContratos($sWhere, $sOrderBy);
    $rsContratos = db_query($sQuery);

    if (!$rsContratos || pg_num_rows($rsContratos) === 0) {
      throw new \DBException('Não foi possível encontrar as informações do Contrato.');
    }

    return pg_fetch_object($rsContratos);
  }

  /**
   * @return array
   * @throws \ParameterException
   */
  public function emitir() {

    if (!$this->iCodigoContrato) {
      throw new \ParameterException('Código do contrato não informado.');
    }

    if (!$this->oAguaEmissao) {
      throw new \ParameterException('Model AguaEmissao não foi informado.');
    }

    if (!$this->oDataDocumento) {
      throw new \ParameterException('Data do Documento não foi informada.');
    }

    $oInformacoes = $this->getInformacoesContrato();
    $oTemplate = new documentoTemplate(self::CODIGO_TIPO_DOCUMENTO);

    /**
     * RG e CPF
     */
    $sIdentidade = '';
    $sDocumento = trim($oInformacoes->documento_responsavel);
    if (strlen($sDocumento) === 11 && trim($oInformacoes->numero_identidade)) {
      $sIdentidade = ", Identidade: {$oInformacoes->numero_identidade}";
    }
    $sDocumento = db_cgccpf($sDocumento);

    /**
     * Endereço do imóvel
     */
    $sEnderecoCompleto = sprintf(
      '%s, %s %s - %s',
      trim($oInformacoes->nome_logradouro),
      'Nro ' . trim($oInformacoes->numero),
      trim($oInformacoes->complemento),
      trim($oInformacoes->bairro)
    );

    $sDataDocumento = $this->oDataDocumento->dataPorExtenso();
    $oDocumento = new TemplateProcessor($oTemplate->getArquivoTemplate());
    $oDocumento->setValue('imovel-codigo', $oInformacoes->codigo_matricula);
    $oDocumento->setValue('imovel-logradouro', trim($oInformacoes->nome_logradouro));
    $oDocumento->setValue('contrato-codigo', $oInformacoes->codigo_contrato);
    $oDocumento->setValue('contrato-nome', trim($oInformacoes->nome_responsavel));
    $oDocumento->setValue('contrato-documento', trim($sDocumento));
    $oDocumento->setValue('contrato-identidade', $sIdentidade);
    $oDocumento->setValue('contrato-endereco', trim($sEnderecoCompleto));
    $oDocumento->setValue('data-documento', $sDataDocumento);

    $sNomeArquivo = 'tmp/contrato_' . time() . '.docx';
    $oDocumento->saveAs($sNomeArquivo);
    $sNomeArquivoConvertido = DocumentConverter::docToPdf($sNomeArquivo);

    return array(
      'name' => 'Contrato Nº ' . $oInformacoes->codigo_contrato,
      'path' => $sNomeArquivoConvertido,
    );
  }
}
