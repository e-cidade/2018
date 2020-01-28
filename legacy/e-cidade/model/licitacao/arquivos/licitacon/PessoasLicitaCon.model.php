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

class PessoasLicitaCon extends ArquivoLicitaCon {

  const CODIGO_LAYOUT = 231;
  const NOME_ARQUIVO  = "PESSOAS";

  /**
   * PessoasLicitaCon constructor.
   *
   * @param CabecalhoLicitaCon $oCabecalho
   */
  public function __construct(CabecalhoLicitaCon $oCabecalho) {

    parent::__construct($oCabecalho);
    $this->sNomeArquivo  = self::NOME_ARQUIVO;
    $this->iCodigoLayout = self::CODIGO_LAYOUT;
  }

  /**
   * @return stdClass[]
   * @throws DBException
   */
  public function getDados() {

    $iInstit    = $this->oCabecalho->getInstituicao()->getCodigo();
    $sDataAtual = $this->oCabecalho->getDataGeracao()->getDate(DBDate::DATA_EN);

    $oDaoOrcamItemLic   = new cl_pcorcamitemlic();
    $oDaoLicLicita      = new cl_liclicita();
    $oDaoCgm            = new cl_cgm();
    $oDaoEventos        = new cl_liclicitaevento();
    $oDaoAcordos        = new cl_acordo();
    $oDaoAcordoComissao = new cl_acordocomissaomembro();

    $sCamposFornecedores  = " pc21_numcgm ";
    $sCamposComissao      = " l31_numcgm ";
    $sWhereSubQuery       = implode(' and ', LicitacaoLicitaCon::getWhereLicitacao($this->oCabecalho->getInstituicao(), $this->oCabecalho->getDataGeracao()));
    $sWhereSubQueryAcordo = " ac16_instit = {$iInstit} and (ac58_sequencial is null or ac58_data >= '{$sDataAtual}') ";

    $sSqlFornecedores   = $oDaoOrcamItemLic->sql_query_fornecedores_licitacon($sCamposFornecedores, $sWhereSubQuery);
    $sSqlRepresentantes = $oDaoOrcamItemLic->sql_query_representantes_fornecedores_licitacon("z01_numcgm", $sWhereSubQuery);
    $sSqlComissao       = $oDaoLicLicita->sql_query_licitacao_comissao($sCamposComissao, $sWhereSubQuery);
    $sSqlEventos        = $oDaoEventos->sql_query_licitacao_encerramento("l46_cgm", $sWhereSubQuery);
    $sSqlAcordos        = $oDaoAcordos->sql_query_encerramento("distinct ac16_contratado", $sWhereSubQueryAcordo);
    $sSqlAcordoComissao = $oDaoAcordoComissao->sql_query_acordo("distinct ac07_numcgm", $sWhereSubQueryAcordo);

    $sCamposCgm   = " distinct z01_nome, z01_cgccpf, z01_numcgm, z01_uf, z01_ender, cgm.z01_numero, z01_compl, db125_codigosistema ";
    $sWhereCgm    = " z01_numcgm in ({$sSqlComissao}) or z01_numcgm in ({$sSqlFornecedores}) or z01_numcgm in ({$sSqlEventos}) or z01_numcgm in ({$sSqlRepresentantes}) ";
    $sWhereCgm   .= " or z01_numcgm in ({$sSqlAcordos}) or z01_numcgm in ({$sSqlAcordoComissao}) ";
    $sOrderByCgm  = " z01_cgccpf ";

    $sSqlCgm = $oDaoCgm->sql_query_endereco_licitacon($sCamposCgm, $sOrderByCgm, $sWhereCgm);

    $rsCgm = db_query($sSqlCgm);

    if ($rsCgm === false) {
      throw new DBException("Não foi possível buscar informações para o arquivo {$this->sNomeArquivo} no LicitaCon.");
    }

    $aCgms = array();
    for ($i = 0; $i < pg_num_rows($rsCgm); $i++) {

      $oStdClass = db_utils::fieldsMemory($rsCgm, $i);

      $oDados = new stdClass();
      $oDados->TP_DOCUMENTO = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdClass->z01_numcgm);
      $oDados->NR_DOCUMENTO = LicitanteLicitaCon::getDocumentoPorCGM($oStdClass->z01_numcgm);
      $oDados->TP_PESSOA    = LicitanteLicitaCon::getTipoDocumentoPorCGM($oStdClass->z01_numcgm);
      $oDados->NM_PESSOA    = $oStdClass->z01_nome;

      $oDados->SG_UF             = $oStdClass->z01_uf;
      $oDados->CD_MUNICIPIO_IBGE = $oStdClass->db125_codigosistema;
      $oDados->LOGRADOURO        = $oStdClass->z01_ender;
      $oDados->NR_ENDERECO       = $oStdClass->z01_numero;
      $oDados->COMPLEMENTO       = $oStdClass->z01_compl;

      // Opcionais não serão aplicados.
      $oDados->DS_OBJETO_SOCIAL              = null;
      $oDados->NR_INSCRICAO_ESTADUAL         = null;
      $oDados->NR_INSCRICAO_MUNICIPAL        = null;
      $oDados->CD_TIPO_CONSELHO_PROFISSIONAL = null;
      $oDados->NR_CONSELHO_PROFISSIONAL      = null;
      $oDados->SG_UF_CONSELHO_PROFISSIONAL   = null;
      $oDados->DS_EMAIL                      = null;
      $oDados->DS_PAGINA_INTERNET            = null;
      $oDados->BAIRRO                        = null;
      $oDados->CEP                           = null;
      $oDados->TELEFONE                      = null;

      $aCgms[] = $oDados;
    }

    return $aCgms;
  }
}