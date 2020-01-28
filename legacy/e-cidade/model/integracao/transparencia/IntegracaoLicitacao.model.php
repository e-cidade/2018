<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */
require_once(modification("model/integracao/transparencia/IItemIntegracao.interface.php"));
require_once(modification("model/integracao/transparencia/IntegracaoBase.model.php"));

/**
 * Classe que realizar a integracao d
 */
class IntegracaoLicitacao extends IntegracaoBase implements IItemIntegracao {

  const DOCUMENTO_ATA    = 1;
  const DOCUMENTO_MINUTA = 2;
  const DOCUMENTO_EDITAL = 3;
  const DOCUMENTO_OUTROS = 4;

  /**
   * Metodo para processamento da Integracao
   */
  public function executar() {

    $this->carregarDadosLicitacao();
    $this->carregarItensDaLicitacao();
    $this->carregarDocumentosDaLicitacao();
  }


  /**
   * Realiza o processamento dos dados básicos da licitacao
   *
   * @throws Exception
   * @return IntegracaoLicitacao
   */
  private function carregarDadosLicitacao () {

    require_once modification(DB_CLASSES."classes/db_liclicita_classe.php");

    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO LICITAÇÕES");
    $sListaCamposImportar   = "l20_codigo as id,";
    $sListaCamposImportar  .= "l20_instit as instituicao_id,";
    $sListaCamposImportar  .= "l03_descr as tipocompra,";
    $sListaCamposImportar  .= "l20_numero as numero,";
    $sListaCamposImportar  .= "l20_datacria as datacriacao,";
    $sListaCamposImportar  .= "l20_horacria as horacriacao,";
    $sListaCamposImportar  .= "l20_horacria as horacriacao,";
    $sListaCamposImportar  .= "l20_dataaber as dataabertura,";
    $sListaCamposImportar  .= "l20_horaaber as horaabertura,";
    $sListaCamposImportar  .= "l20_dtpublic as datapublicacao,";
    $sListaCamposImportar  .= "l20_local as local,";
    $sListaCamposImportar  .= "l20_objeto as objeto,";
    $sListaCamposImportar  .= "l20_procadmin as processoadministrativo,";
    $sListaCamposImportar  .= "l08_descr as situacao,";
    $sListaCamposImportar  .= "l20_edital as edital,";
    $sListaCamposImportar  .= "l20_anousu as anousu";

    $oDaoLiclicita = new cl_liclicita();
    $sSqlLicitacao = $oDaoLiclicita->sql_query_licitacao_transparencia(null,
      $sListaCamposImportar,
      "l20_datacria",
      "l20_anousu >= {$this->iAnoInicioIntegracao}
                                                                       and exists(select 1
                                                                                    from liclicitem
                                                                                   where l21_codliclicita = l20_codigo)"
    );

    $rsLicitacoes           = db_query($this->rsConexaoOrigem, $sSqlLicitacao);
    $iTotalLicitacoes       = pg_num_rows($rsLicitacoes);
    $oLicitacaoTableManager = new tableDataManager($this->rsConexaoDestino, 'licitacoes', null, true, 500);
    for ($iLicitacao = 0; $iLicitacao < $iTotalLicitacoes; $iLicitacao++) {

      $oLicitacao         = db_utils::fieldsMemory($rsLicitacoes, $iLicitacao);
      $iCodigoInstituicao = $this->getCodigoInstituicoesNoTransparencia($oLicitacao->instituicao_id);
      if ($iCodigoInstituicao == null) {
        throw new Exception("Instituicao {$oLicitacao->instituicao_id} não encontrada no Portral da Transparência.");
      }
      $oLicitacao->instituicao_id = $iCodigoInstituicao;
      $this->inserirDadosPortalTransparencia($oLicitacao, $oLicitacaoTableManager);

    }
    $this->persistirDadosPortalTransparencia($oLicitacaoTableManager);
    return $this;
  }

  /**
   * Realiza a carga dos itens das licitacoes
   *
   * @throws Exception
   * @return IntegracaoLicitacao
   */
  private function carregarItensDaLicitacao() {


    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO ITENS DAS LICITAÇÕES");
    require_once modification(DB_CLASSES."classes/db_liclicitem_classe.php");
    $sListaCampos  = "l21_codigo       as id,";
    $sListaCampos .= "l21_codliclicita as licitacao_id,";
    $sListaCampos .= "pc01_descrmater  as material,";
    $sListaCampos .= "pc11_quant       as quantidade,";
    $sListaCampos .= "m61_descr        as unidade_medida,";
    $sListaCampos .= "pc11_resum       as resumo,";
    $sListaCampos .= "z01_nome         as fornecedor,";
    $sListaCampos .= "(case when pc24_pontuacao = 1 then pc23_valor else round(pc11_quant * pc11_vlrun, 2)  end ) as valor";

    $oDaoLIcilicitem = new cl_liclicitem();
    $sSqlLiclicitem  = $oDaoLIcilicitem->sql_query_portal_transparencia(null,
      $sListaCampos,
      'l21_codliclicita, l21_ordem',
      "l20_anousu >= {$this->iAnoInicioIntegracao}"
    );

    $rsItensLicitacao= db_query($this->rsConexaoOrigem, $sSqlLiclicitem);
    if (!$rsItensLicitacao) {
      throw new Exception("Erro ao pesquisar os itens da licitacao ".pg_last_error());
    }

    $iTotalItens                  = pg_num_rows($rsItensLicitacao);
    $oTableManagerLicitacoesItens = new tableDataManager($this->rsConexaoDestino, "licitacoes_itens", null, true, 500);
    for ($iItens = 0; $iItens < $iTotalItens; $iItens++) {

      $oItemLicitacao = db_utils::fieldsMemory($rsItensLicitacao, $iItens);

      $buscaSequencial = pg_query($this->rsConexaoDestino, "select nextval('licitacoes_itens_id_seq') as sequencial");
      $oItemLicitacao->id = pg_fetch_result($buscaSequencial, 0, 'sequencial');
      $this->inserirDadosPortalTransparencia($oItemLicitacao, $oTableManagerLicitacoesItens);
    }
    $this->persistirDadosPortalTransparencia($oTableManagerLicitacoesItens);
    return $this;
  }

  public function carregarDocumentosDaLicitacao() {

    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO DOCUMENTOS DAS LICITAÇÕES");

    $sDiretorioLicitacaoes        = "/tmp";
    $oDaoLicitacaoAta             = new cl_liclicitaata();
    $oDaoLicitacaoMinuta          = new cl_liclicitaminuta();
    $oDaoLicitacaoEdital          = new cl_liclicitaedital();
    $oDaoLicitacaoEventoDocumento = new cl_liclicitaeventodocumento();
    $oDaoLicitacaoOutros          = new cl_documentolicitacaotransparencia();

    $sWhere  = " l20_anousu >= {$this->iAnoInicioIntegracao} ";
    $sWhere .= " and exists(select 1 from liclicitem where l21_codliclicita = l20_codigo) ";

    $aQueryesDocumentos = array();
    $sCamposAta  = "null as id, l39_liclicita as licitacao_id,  l39_arquivo as documento, ";
    $sCamposAta .= "'{$sDiretorioLicitacaoes}/arquivo_1_'||l39_arquivo||'.dat' as nome_arquivo_importacao, ";
    $sCamposAta .= " l39_arqnome as nome,";
    $sCamposAta .= self::DOCUMENTO_ATA . " as tipo";

    $aQueryesDocumentos[] = $oDaoLicitacaoAta->sql_query(null, $sCamposAta, null, $sWhere);

    $sCamposMinuta  = "null as id, l43_liclicita as licitacao_id, l43_arquivo,";
    $sCamposMinuta .= "'{$sDiretorioLicitacaoes}/arquivo_2_'||l43_arquivo||'.dat' as nome_arquivo_importacao,l43_arqnome as nome, ";
    $sCamposMinuta .= self::DOCUMENTO_MINUTA . " as tipo";

    $aQueryesDocumentos[] = $oDaoLicitacaoMinuta->sql_query(null, $sCamposMinuta, null, $sWhere);

    $sCamposEdital  = "null as id, l27_liclicita as licitacao_id, l27_arquivo, ";
    $sCamposEdital .= "'{$sDiretorioLicitacaoes}/arquivo_3_'||l27_arquivo||'.dat' as nome_arquivo_importacao, l27_arqnome as nome, ";
    $sCamposEdital .= self::DOCUMENTO_EDITAL . " as tipo";

    $aQueryesDocumentos[] = $oDaoLicitacaoEdital->sql_query(null, $sCamposEdital, null, $sWhere);

    /**
     * Documentos de licitação vinculados a eventos
     */
    $aCamposEventos = array(
      'null as id',
      'l46_liclicita as licitacao_id',
      'l47_arquivo as documento',
      "'{$sDiretorioLicitacaoes}/arquivo_'||l47_arquivo||'.dat' as nome_arquivo_importacao",
      'l47_nomearquivo as nome',
    );

    $aTiposDocumentoAta = array(
      DocumentoEventoLicitacao::ATA_JULGAMENTO_CREDENCIAMENTO,
      DocumentoEventoLicitacao::ATA_JULGAMENTO_RECURSOS,
      DocumentoEventoLicitacao::ATA_REGISTRO_PRECO,
      DocumentoEventoLicitacao::ATA_JULGAMENTO_RECURSO,
      DocumentoEventoLicitacao::ATA_PREGAO,
      DocumentoEventoLicitacao::ATA_PROCEDIMENTO_PRE_QUALIFICACAO,
      DocumentoEventoLicitacao::ATA_RDC,
      DocumentoEventoLicitacao::ATA_HABILITACAO_PROPOSTAS,
      DocumentoEventoLicitacao::ATA_HABILITACAO_PROPOSTAS_PROJETOS
    );
    $sTiposDocumentoAta = implode(', ', array_map(function($iTipo) {
      return "'" . $iTipo . "'";
    }, $aTiposDocumentoAta));

    $aTiposDocumentoEdital = array(
      DocumentoEventoLicitacao::AVISO_ALTERACAO_EDITAL_ERRATA,
      DocumentoEventoLicitacao::EDITAL_PRE_QUALIFICACAO,
      DocumentoEventoLicitacao::EDITAL_ANEXOS,
      DocumentoEventoLicitacao::EDITAL_ANEXOS_OUTRO_ORGAO,
    );
    $sTiposDocumentoEdital = implode(', ', array_map(function($iTipo) {
      return "'" . $iTipo . "'";
    }, $aTiposDocumentoEdital));

    // Verifica se esta configurado a exportacao de outros tipos de arquivos enviados para o LICITACON
    $sqlOutros = $oDaoLicitacaoOutros->sql_query();
    $rsOutros  = db_query($this->rsConexaoOrigem, $sqlOutros);

    // Se existir, monta adiciona os tipos na busca como tipo 4 - outros
    if ($rsOutros) {
        $aTipoOutros      = array();
        $quantidadeOutros = pg_num_rows($rsOutros);
        for ( $i=0; $i < $quantidadeOutros; $i++ ) {
            $outro = db_utils::fieldsMemory($rsOutros,$i);
            $aTipoOutros[] = $outro->l48_documento;
        }
        if (sizeof($aTipoOutros) > 0) {
            $sTiposOutros = implode(', ', array_map(function($iTipo) {
                return "'" . $iTipo . "'";
            }, $aTipoOutros));
            $sCamposEventos       = implode(', ', array_merge($aCamposEventos, array(self::DOCUMENTO_OUTROS. ' as tipo')));
            $sWhereEventos        = "{$sWhere} and l47_tipodocumento in({$sTiposOutros})";
            $aQueryesDocumentos[] = $oDaoLicitacaoEventoDocumento->sql_query(null, $sCamposEventos, null, $sWhereEventos);
        }
    }

    $sCamposEventos       = implode(', ', array_merge($aCamposEventos, array(self::DOCUMENTO_ATA. ' as tipo')));
    $sWhereEventos        = "{$sWhere} and l47_tipodocumento in({$sTiposDocumentoAta})";
    $aQueryesDocumentos[] = $oDaoLicitacaoEventoDocumento->sql_query(null, $sCamposEventos, null, $sWhereEventos);

    $sCamposEventos       = implode(', ', array_merge($aCamposEventos, array(self::DOCUMENTO_EDITAL . ' as tipo')));
    $sWhereEventos        = "{$sWhere} and l47_tipodocumento in({$sTiposDocumentoEdital})";
    $aQueryesDocumentos[] = $oDaoLicitacaoEventoDocumento->sql_query(null, $sCamposEventos, null, $sWhereEventos);

    $sQueryDocumentos       = implode(" union ", $aQueryesDocumentos);
    $rsDocumentosLicitacao  = db_query($this->rsConexaoOrigem, $sQueryDocumentos);
    if (!$rsDocumentosLicitacao) {
      throw new Exception("Erro ao importar os documentos das licitações: " . pg_last_error());
    }

    $iTotalDocumentos                  = pg_num_rows($rsDocumentosLicitacao);
    $oTableManagerLicitacoesDocumentos = new tableDataManager($this->rsConexaoDestino,
      "licitacoes_documentos", "id", true, 500
    );

    $aListaArquivos  = array();
    for ($iDocumento = 0 ; $iDocumento < $iTotalDocumentos; $iDocumento++) {

      $oDocumento = db_utils::fieldsMemory($rsDocumentosLicitacao, $iDocumento);
      $lExport = pg_lo_export($oDocumento->documento, $oDocumento->nome_arquivo_importacao, $this->rsConexaoOrigem);
      $aListaArquivos[$oDocumento->documento] = $oDocumento->nome_arquivo_importacao;
      $this->inserirDadosPortalTransparencia($oDocumento, $oTableManagerLicitacoesDocumentos);
    }

    $this->persistirDadosPortalTransparencia($oTableManagerLicitacoesDocumentos);
    foreach ($aListaArquivos as $iOId => $sArquivo) {

      $iOidNovo          = pg_lo_import($this->rsConexaoDestino, $sArquivo);
      $sUpdateAcertoOID  = "UPDATE licitacoes_documentos ";
      $sUpdateAcertoOID .= "   SET documento = {$iOidNovo}";
      $sUpdateAcertoOID .= " where documento = {$iOId}";
      $rsQueryAcertoDocumentos = db_query($this->rsConexaoDestino, $sUpdateAcertoOID);
      if (!$rsQueryAcertoDocumentos) {
        throw new Exception("Erro ao corrigir os documentos das licitacoes: " . pg_last_error());
      }
      //unlink($sArquivo);
    }
  }

}
