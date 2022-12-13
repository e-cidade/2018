<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("model/integracao/transparencia/IItemIntegracao.interface.php"));
require_once(modification("model/integracao/transparencia/IntegracaoBase.model.php"));

class IntegracaoContrato extends IntegracaoBase implements IItemIntegracao {

  /**
   * Metodo para processamento da Integracao
   */
  public function executar() {

    $this->importarAcordos();
    $this->importarAditamentos();
    $this->importarItens();
    $this->importarEmpenhos();
    $this->importarDocumentos();
  }

  /**
   * Importa os dados dos acordos
   * @throws Exception
   * @return boolean
   */
  private function importarAcordos() {

    require_once(modification(DB_CLASSES . "classes/db_acordo_classe.php"));
    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO ACORDOS");

    $sCampos  = "ac16_sequencial           as id,                     \n";
    $sCampos .= "ac17_descricao            as situacao,               \n";
    $sCampos .= "ac16_numero               as numero,                 \n";
    $sCampos .= "ac16_anousu               as anousu,                 \n";
    $sCampos .= "ac16_dataassinatura       as data_assinatura,        \n";
    $sCampos .= "z01_nome                  as contratado,             \n";
    $sCampos .= "ac16_datainicio           as data_inicio,            \n";
    $sCampos .= "ac16_datafim              as data_fim,               \n";
    $sCampos .= "ac16_objeto               as objeto,                 \n";
    $sCampos .= "ac16_instit               as instituicao_id,         \n";
    $sCampos .= "ac08_descricao            as comissao,               \n";
    $sCampos .= "ac16_lei                  as lei,                    \n";
    $sCampos .= "ac02_descricao            as grupo,                  \n";
    $sCampos .= "ac16_qtdrenovacao         as quantidade_renovacao,   \n";
    $sCampos .= "ac16_tipounidtempo        as unidade_tempo_renovacao,\n";
    $sCampos .= "ac16_numeroprocesso       as numero_processo,        \n";
    $sCampos .= "ac16_periodocomercial     as peridodo_comercial,     \n";
    $sCampos .= "ac16_qtdperiodo           as quantidade_vigencia,    \n";
    $sCampos .= "ac16_tipounidtempoperiodo as unidade_tempo_vigencia  \n";

    $oDaoAcordo    = new cl_acordo();
    $sSqlAcordo    = $oDaoAcordo->sql_query_transparencia($sCampos, null, "ac16_anousu >= {$this->iAnoInicioIntegracao}");
    $rsAcordos     = db_query($this->rsConexaoOrigem, $sSqlAcordo);
    $iTotalAcordos = pg_num_rows($rsAcordos);

    IntegracaoPortalTransparencia::escreverRegistrosProcessados($iTotalAcordos, $this->sArquivoLog, $this->iTipoLog);

    if (!$iTotalAcordos) {
      return false;
    }

    $oAcordoTableManager = new tableDataManager($this->rsConexaoDestino, 'acordos', null, true, 500);
    for ($iIndice = 0; $iIndice < $iTotalAcordos; $iIndice++) {

      $oAcordo      = db_utils::fieldsMemory($rsAcordos, $iIndice);
      $iInstituicao = $this->getCodigoInstituicoesNoTransparencia($oAcordo->instituicao_id);

      IntegracaoPortalTransparencia::logarProcessamento($iIndice, $iTotalAcordos, $this->sArquivoLog, $this->iTipoLog);

      if (empty($iInstituicao)) {
        throw new Exception("Instituicao {$oAcordo->instituicao_id} não encontrada no portal da transparencia.");
      }

      $oAcordo->instituicao_id = $iInstituicao;
      $this->inserirDadosPortalTransparencia($oAcordo, $oAcordoTableManager);
    }

    $this->persistirDadosPortalTransparencia($oAcordoTableManager);
    return true;
  }

  /**
   * Importa os dados dos Aditamentos
   * @throws Exception
   * @return boolean
   */
  private function importarAditamentos() {

    require_once DB_CLASSES . "classes/db_acordoposicao_classe.php";
    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO ADITAMENTOS");

    $sCampos  = "ac26_sequencial       as id,               ";
    $sCampos .= "ac26_acordo           as acordo_id,        ";
    $sCampos .= "ac27_descricao        as posicao_tipo,     ";
    $sCampos .= "ac26_numero           as numero,           ";
    $sCampos .= "ac17_descricao        as situacao,         ";
    $sCampos .= "ac26_data             as data,             ";
    $sCampos .= "ac26_emergencial      as emergencial,      ";
    $sCampos .= "ac26_observacao       as observacao,       ";
    $sCampos .= "ac26_numeroaditamento as numero_aditamento ";

    $oDaoAcordoposicao = new cl_acordoposicao();
    $sSqlAcordoPosicao = $oDaoAcordoposicao->sql_query_transparencia( $sCampos, null, "ac16_anousu >= {$this->iAnoInicioIntegracao}" );
    $rsAcordoPosicao   = db_query($this->rsConexaoOrigem, $sSqlAcordoPosicao);
    $iTotalPosicoes    = pg_num_rows($rsAcordoPosicao);

    IntegracaoPortalTransparencia::escreverRegistrosProcessados($iTotalPosicoes, $this->sArquivoLog, $this->iTipoLog);

    if (!$iTotalPosicoes) {
      return false;
    }

    $oAcordoPosicaoTableManager = new tableDataManager($this->rsConexaoDestino, 'acordo_aditamentos', null, true, 500);
    for ($iIndice = 0; $iIndice < $iTotalPosicoes; $iIndice++) {

      $oPosicao = db_utils::fieldsMemory($rsAcordoPosicao, $iIndice);
      IntegracaoPortalTransparencia::logarProcessamento($iIndice, $iTotalPosicoes, $this->sArquivoLog, $this->iTipoLog);

      $this->inserirDadosPortalTransparencia($oPosicao, $oAcordoPosicaoTableManager);
    }

    $this->persistirDadosPortalTransparencia($oAcordoPosicaoTableManager);
    return true;
  }

  /**
   * Importa os itens dos Aditamentos
   * @throws Exception
   * @return boolean
   */
  private function importarItens() {

    require_once DB_CLASSES . "classes/db_acordoitem_classe.php";
    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO ITENS DOS ADITAMENTOS");

    $sCampos  = "ac20_sequencial    as id,                   ";
    $sCampos .= "ac20_acordoposicao as acordo_aditamento_id, ";
    $sCampos .= "pc01_descrmater    as material,             ";
    $sCampos .= "ac20_quantidade    as quantidade,           ";
    $sCampos .= "ac20_valorunitario as valor_unitario,       ";
    $sCampos .= "ac20_valortotal    as valor_total,          ";
    $sCampos .= "ac20_elemento      as elemento,             ";
    $sCampos .= "ac20_ordem         as ordem,                ";
    $sCampos .= "m61_descr          as unidade,              ";
    $sCampos .= "ac20_resumo        as resumo,               ";
    $sCampos .= "ac20_tipocontrole  as tipo_controle         ";

    $oDaoAcordoitem = new cl_acordoitem();
    $sSqlAcordoItem = $oDaoAcordoitem->sql_query_transparencia($sCampos, null, "ac16_anousu >= {$this->iAnoInicioIntegracao}");
    $rsAcordoItem   = db_query($this->rsConexaoOrigem, $sSqlAcordoItem);
    $iTotalItens    = pg_num_rows($rsAcordoItem);

    IntegracaoPortalTransparencia::escreverRegistrosProcessados($iTotalItens, $this->sArquivoLog, $this->iTipoLog);

    if (!$iTotalItens) {
      return false;
    }

    $oAcordoItemTableManager = new tableDataManager($this->rsConexaoDestino, 'acordo_aditamento_itens', null, true, 500);
    for ($iIndice = 0; $iIndice < $iTotalItens; $iIndice++) {

      $oItem = db_utils::fieldsMemory($rsAcordoItem, $iIndice);
      IntegracaoPortalTransparencia::logarProcessamento($iIndice, $iTotalItens, $this->sArquivoLog, $this->iTipoLog);

      $this->inserirDadosPortalTransparencia($oItem, $oAcordoItemTableManager);
    }

    $this->persistirDadosPortalTransparencia($oAcordoItemTableManager);
    return true;
  }

  /**
   * Importa os Empenhos dos Acordos
   * @throws Exception
   * @return boolean
   */
  private function importarEmpenhos() {

    require_once DB_CLASSES . "classes/db_empempenhocontrato_classe.php";
    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO EMPENHOS DOS ACORDOS");

    db_query( $this->rsConexaoDestino, " create temp table tmp_acordo_empenhos( "
                                      ."   id integer,                          "
                                      ."   acordo_id integer,                   "
                                      ."   codigo character varying (15),       "
                                      ."   anousu integer                       "
                                      ." );                                     " );

    $sCampos  = "e100_sequencial as id,        ";
    $sCampos .= "e100_acordo     as acordo_id, ";
    $sCampos .= "e60_codemp      as codigo,    ";
    $sCampos .= "e60_anousu      as anousu     ";

    $oDaoEmpenhocontrato = new cl_empempenhocontrato();
    $sSqlAcordoEmpenhos  = $oDaoEmpenhocontrato->sql_query_transparencia($sCampos, null, "ac16_anousu >= {$this->iAnoInicioIntegracao}");
    $rsAcordoEmpenhos    = db_query($this->rsConexaoOrigem, $sSqlAcordoEmpenhos);
    $iTotalEmpenhos      = pg_num_rows($rsAcordoEmpenhos);

    IntegracaoPortalTransparencia::escreverRegistrosProcessados($iTotalEmpenhos, $this->sArquivoLog, $this->iTipoLog);

    if (!$iTotalEmpenhos) {
      return false;
    }

    $oAcordoEmpenhosTableManager = new tableDataManager($this->rsConexaoDestino, 'tmp_acordo_empenhos', null, true, 500);
    for ($iIndice = 0; $iIndice < $iTotalEmpenhos; $iIndice++) {

      $oEmpenho = db_utils::fieldsMemory($rsAcordoEmpenhos, $iIndice);
      IntegracaoPortalTransparencia::logarProcessamento($iIndice, $iTotalEmpenhos, $this->sArquivoLog, $this->iTipoLog);

      $this->inserirDadosPortalTransparencia($oEmpenho, $oAcordoEmpenhosTableManager);
    }

    $this->persistirDadosPortalTransparencia($oAcordoEmpenhosTableManager);

    db_query( $this->rsConexaoDestino,  " insert into acordo_empenhos                                         "
                                       ."        select nextval('acordo_empenhos_id_seq'::regclass),          "
                                       ."               tmp.acordo_id,                                        "
                                       ."               emp.id                                                "
                                       ."          from tmp_acordo_empenhos tmp                               "
                                       ."               inner join empenhos emp on tmp.codigo = emp.codigo    "
                                       ."                                      and tmp.anousu = emp.exercicio " );

    return true;
  }

  /**
   * Importa os documentos dos Acordos
   * @throws Exception
   * @return boolean
   */
  private function importarDocumentos() {

    require_once DB_CLASSES . "classes/db_acordodocumento_classe.php";
    IntegracaoPortalTransparencia::escreverTitulo("IMPORTANDO DOCUMENTOS DOS ACORDOS");

    $sCampos  = "ac40_sequencial  as id,        ";
    $sCampos .= "ac40_acordo      as acordo_id, ";
    $sCampos .= "ac40_descricao   as descricao, ";
    $sCampos .= "ac40_nomearquivo as nome,      ";
    $sCampos .= "ac40_arquivo     as arquivo    ";

    $oDaoAcordodocumento = new cl_acordodocumento();
    $sSqlDocumentos      = $oDaoAcordodocumento->sql_query_transparencia($sCampos, null, "ac16_anousu >= {$this->iAnoInicioIntegracao}");
    $rsAcordoDocumentos  = db_query($this->rsConexaoOrigem, $sSqlDocumentos);
    $iTotalDocumentos    = pg_num_rows($rsAcordoDocumentos);

    IntegracaoPortalTransparencia::escreverRegistrosProcessados($iTotalDocumentos, $this->sArquivoLog, $this->iTipoLog);

    if (!$iTotalDocumentos) {
      return false;
    }

    $oAcordoDocumentoTableManager = new tableDataManager($this->rsConexaoDestino, 'acordo_documentos', null, true, $iTotalDocumentos);
    for ($iIndice = 0; $iIndice < $iTotalDocumentos; $iIndice++) {

      $oDocumento       = db_utils::fieldsMemory($rsAcordoDocumentos, $iIndice);
      if (!pg_lo_export($this->rsConexaoOrigem, $oDocumento->arquivo, "/tmp/{$oDocumento->arquivo}.dat")) {

        $sErro      = pg_last_error($this->rsConexaoOrigem);
        $sMensagem  = "Erro na exportação de arquivo do e-cidade: Documento de id " . $oDocumento->id;
        $sMensagem .= "e nome ". $oDocumento->nome . ". Erro: " . $sErro;
        throw new Exception($sMensagem);
      }

      if (!file_exists("/tmp/{$oDocumento->arquivo}.dat")) {

        $sMensagem  = "Arquivo para importação não existe (não foi exportado do e-cidade). Documento de id ";
        $sMensagem .= $oDocumento->id . " e nome " . $oDocumento->nome;
        throw new Exception($sMensagem);
      }

      if (!is_readable("/tmp/{$oDocumento->arquivo}.dat")) {

        $sMensagem  = "Arquivo exportado do e-cidade não é legível: Documento de id " . $oDocumento->id . " e nome ";
        $sMensagem .= $oDocumento->nome;
        throw new Exception($sMensagem);
      }

      $oDocumento->arquivo = pg_lo_import($this->rsConexaoDestino, "/tmp/{$oDocumento->arquivo}.dat");
      if (!$oDocumento->arquivo) {

        $sErro      = pg_last_error($this->rsConexaoDestino);
        $sMensagem  = "Erro na importação de arquivo no transparência: Documento de id " . $oDocumento->id;
        $sMensagem .= " e nome ".$oDocumento->nome . ". Erro: " . $sErro;
        throw new Exception($sMensagem);
      }
      IntegracaoPortalTransparencia::logarProcessamento($iIndice, $iTotalDocumentos, $this->sArquivoLog, $this->iTipoLog);
      $this->inserirDadosPortalTransparencia($oDocumento, $oAcordoDocumentoTableManager);
    }

    $this->persistirDadosPortalTransparencia($oAcordoDocumentoTableManager);

    return true;
  }

}
?>