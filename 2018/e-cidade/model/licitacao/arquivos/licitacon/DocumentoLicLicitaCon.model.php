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

use ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao\Geral;

class DocumentoLicLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 239;
  const NOME_ARQUIVO  = "DOCUMENTO_LIC";

  /**
   *
   * @param CabecalhoLicitaCon $oCabecalho
   */
  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho, new Geral($oCabecalho->getDataGeracao()));
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @return stdClass[]
   * @throws DBException
   */
  public function getDados() {

    $aLinhas = array();
    $aCampos = array(
      'l20_codigo',
      'l47_tipodocumento',
      'l47_nomearquivo',
      'z01_numcgm',
      'l47_liclicitaevento',
      'l47_arquivo',
      'l46_fase',
      'l46_liclicitatipoevento'
    );

    $aWhere = LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao());
    $oDaoLicitacao = new cl_liclicita;
    $sSqlLicitacao = $oDaoLicitacao->sql_query_eventos_documentos(implode(',', $aCampos), implode(' and ', $aWhere));
    $rsLicitacao   = db_query($sSqlLicitacao);

    if (!$rsLicitacao) {
      throw new DBException('Não foi possível buscar os dados para geração do arquivo.');
    }

    for ($iRow = 0; $iRow < pg_num_rows($rsLicitacao); $iRow++) {

      $oStdDocumento = db_utils::fieldsMemory($rsLicitacao, $iRow);

      $sSiglaTipoDocumento = LicitaConTipoDocumento::$aSiglaTipoDocumento[$oStdDocumento->l47_tipodocumento];

      $sCaminhoArquivo = $this->sNomeArquivo . "\\" . preg_replace("/((?![\w\\.!@#$%*()_+= ,<>?\/^~-]).)/", "", $oStdDocumento->l47_nomearquivo);
			$sCaminhoArquivo = File::cutName($sCaminhoArquivo, $this->oRegra->getTamanhoNomeArquivo());

			$oLicitacao = LicitacaoRepository::getByCodigo($oStdDocumento->l20_codigo);

      $oStdLinha = new stdClass;
      $oStdLinha->NR_LICITACAO           = $oLicitacao->getEdital();
      $oStdLinha->ANO_LICITACAO          = $oLicitacao->getAno();
      $oStdLinha->CD_TIPO_MODALIDADE     = $oLicitacao->getModalidade()->getSiglaTipoCompraTribunal();
      $oStdLinha->CD_TIPO_DOCUMENTO      = $sSiglaTipoDocumento;
      $oStdLinha->NOME_ARQUIVO_DOCUMENTO = $sCaminhoArquivo;
      $oStdLinha->CD_TIPO_FASE           = LicitacaoLicitaCon::getSiglaFase($oStdDocumento->l46_fase);
      $oStdLinha->SQ_EVENTO              = $oStdDocumento->l46_liclicitatipoevento != LicitaConTipoEvento::EVENTO_NAO_INFORMADO ? $oStdDocumento->l47_liclicitaevento : '';
      $oStdLinha->TP_DOCUMENTO_LICITANTE = null;
      $oStdLinha->NR_DOCUMENTO_LICITANTE = null;

      if ($this->mostrarLicitante($oLicitacao->getModalidade()->getSiglaTipoCompraTribunal(), $sSiglaTipoDocumento)
          && $this->isAutorLicitante($oStdDocumento->z01_numcgm, $oStdDocumento->l20_codigo)) {

        $oStdLinha->NR_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getDocumentoPorCGM($oStdDocumento->z01_numcgm);
        $oStdLinha->TP_DOCUMENTO_LICITANTE = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdDocumento->z01_numcgm);
      }

      $aLinhas[] = $oStdLinha;
      $this->aAnexos[$oStdDocumento->l47_arquivo] = $sCaminhoArquivo;
    }

    return $aLinhas;
  }

  /**
   * Verifica se o autor do documento também é licitante da licitação.
   *
   * @param $iNumCgm
   * @param $iLicitacao
   *
   * @return bool
   * @throws DBException
   */
  private function isAutorLicitante($iNumCgm, $iLicitacao) {

    if (empty($iNumCgm)) {
      return false;
    }

    $sFornecedorLicitacao  = "select pc21_numcgm from liclicita";
    $sFornecedorLicitacao .= "   inner join liclicitem     on l21_codliclicita = l20_codigo";
    $sFornecedorLicitacao .= "   inner join pcorcamitemlic on pc26_liclicitem  = l21_codigo";
    $sFornecedorLicitacao .= "   inner join pcorcamitem    on pc22_orcamitem   = pc26_orcamitem";
    $sFornecedorLicitacao .= "   inner join pcorcamforne   on pc21_codorc      = pc22_codorc";
    $sFornecedorLicitacao .= " where l20_codigo = {$iLicitacao} and pc21_numcgm = {$iNumCgm} limit 1";

    $rsFornecedorLicitacao = db_query($sFornecedorLicitacao);
    if (!$rsFornecedorLicitacao) {
      throw new DBException("Houve um erro ao verificar os fornecedores da licitação.");
    }
    return pg_num_rows($rsFornecedorLicitacao) != 0;
  }

  /**
   * Verifica se é obrigatório o autor do documento.
   * @param string $sModalidade    Sigla da modalidade da licitaçao.
   * @param string $sTipoDocumento Sigla do tipo de documento.
   *
   * @return bool
   */
  private function mostrarLicitante($sModalidade, $sTipoDocumento) {

    if (in_array($sModalidade, array('PRD', 'PRI', 'RPO'))) {
      return false;
    }

    $aDocumentosObrigatorios = array();
    switch ($sModalidade) {

      case 'CHP':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'CPC':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        break;

      case 'CNC':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'CNS':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_PROJETOS;
        break;

      case 'CNV':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'LEI':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        break;

      case 'MAI':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_MANIFESTACAO_DE_INTERESSE;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_MODELOS_DE_NEGOCIO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANO_DE_TRABALHO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROJETO_FUNCIONAL_PRELIMINAR;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROJETOS_E_ESTUDOS_TECNICOS;
        break;

      case 'PRE':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'PRP':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'RDC':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'RIN':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;

      case 'TMP':

        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_DOCUMENTOS_DE_HABILITACAO;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PLANILHA_DE_PROPOSTAS;
        $aDocumentosObrigatorios[] = LicitaConTipoDocumento::TIPO_DOCUMENTO_PROPOSTAS_ORCAMENTO_PRECO;
        break;
    }

    return in_array($sTipoDocumento, $aDocumentosObrigatorios);
  }
}