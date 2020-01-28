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
require_once modification("model/farmacia/interfaces/iArquivosIntegracaoHorus.interface.php");
/**
 * Gera o arquivo se Saida do Horus
 * Busca todas as saídas de medicamentos no estoque que não foram dispensados
 * (Baixa, Quebra)
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Iuri         <iuri@dbseller.com.br>
 * @package model\farmacia\horus
 *
 */
class HorusSaidaMedicamento extends HorusArquivoBase implements iArquivosHorus {

  const TIPO        = 'Saída';
  const NOME        = 'Saida.xml';

  protected $iCodigoTipo = 2;

  protected $aMedicamentos = array();

  /**
   * Tipos de saidas esperada do medicamento pelo webservice Hórus
   */
  const SAIDA_POR_PERDA            = 'S-PE';
  const SAIDA_POR_VALIDADE_VENCIDA = 'S-VV';

  /**
   * Lista de campos do arquivo xml
   * @var array
   */
  static $aCampos = array( "nuproduto"      => "nuProduto",
                           "vlitem"         => "vlItem",
                           "dtvalidade"     => "dtValidade",
                           "nulote"         => "nuLote",
                           "qtsaida"        => "qtSaida",
                           "dtsaida"        => "dtSaida",
                           "tpmovimentacao" => "tpMovimentacao",
                           "tpproduto"      => "tpProduto"
                         );

  /**
   * Busca todas saída/baixa de medicamentos no estoque
   *
   * @param  string $sFiltraMovimentacao condição pelo código da movimentação
   * @throws Exception
   */
  private function coletarDados( $sFiltraMovimentacao = null ) {

    $oDataInicial = new DBDate('01/'.$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));
    $iUltimoDia   = str_pad(cal_days_in_month(CAL_GREGORIAN, $this->oCompetencia->getMes(), $this->oCompetencia->getAno()), '0', 2);
    $oDataFinal  = new DBDate($iUltimoDia."/".$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));

    $aWhere[] = " m81_tipo = 2 ";
    $aWhere[] = " far_retirada.fa04_i_codigo is null";
    $aWhere[] = " matestoque.m70_coddepto = {$this->oUPS->getCodigo()} ";
    $aWhere[] = " m80_data between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";

    if ( !empty($sFiltraMovimentacao) ) {
      $aWhere[] = $sFiltraMovimentacao;
    }

    $sWhere       = implode(" and ", $aWhere);
    $oDaoMaterial = new cl_far_matersaude();

    $sSqlMaterial = $oDaoMaterial->saidaMedicamentoFarmaciaBasica($sWhere);
    $rsMaterial   = db_query($sSqlMaterial);

    if ( !$rsMaterial ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MENSAGEM . "erro_coletar_dados_saida", $oMsgErro) );
    }

    return db_utils::getCollectionByRecord($rsMaterial);
  }

  /**
   * Cria o arquivo xml no padrão do Horus
   * @return string Nome do arquivo e diretorio em que foi salvo
   */
  public function gerarArquivo() {

    $this->getCodigoIntegracaoCompetencia();

    if ( $this->iSituacao == self::SEM_DADOS ) {
      return false;
    }

    // Se não tem registros para gerar arquivo retorna false
    $this->getDadosCompetencia();
    if ( count($this->aMedicamentos) == 0) {
      return false;
    }

    $this->criarArquivo('S');
    foreach ($this->aMedicamentos as $oMedicamento) {
      $this->adicionarRegistro("registro", $oMedicamento, self::$aCampos);
    }
    return $this->fecharArquivo(self::NOME);
  }

  public function preProcessar() {

    parent::preProcessar();
    $this->getCodigoIntegracaoCompetencia(self::ARQUIVO_SAIDA);
    $this->consistirDadosCompetencia();
  }

  private function consistirDadosCompetencia() {

    $sWhere = " fa63_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciasaida();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "*", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    $oMsgErro = new stdClass();
    if ( !$rsDadosCompetencia ) {

      $oMsgErro->sErro = pg_last_error();
      $oMsgErro->sTipo = "saída";
      throw new DBException( _M(self::MENSAGEM . "erro_verifica_dados_competencia", $oMsgErro) );
    }

    if ( pg_num_rows($rsDadosCompetencia) > 0) {

      $aDadosCompetencia = db_utils::getCollectionByRecord($rsDadosCompetencia);
      $this->atualizarDadosCompetencia($aDadosCompetencia);
      return true;
    }

    $this->criarDadosCompetencia();
    return true;
  }

  /**
   * Valida se os dados inconsistentes já foram corrigidos e atualiza os dados da competencia
   *
   * @param  stdClass[] $aDadosCompetencia Todas as saídas na competencia
   * @return boolean
   */
  private function atualizarDadosCompetencia($aDadosCompetencia) {

    foreach ($aDadosCompetencia as $oDadoCompetencia) {

      // só processa os registros que estão como enviar false
      if($oDadoCompetencia->fa63_enviar == 't') {
        continue;
      }

      $sMovimentacao = " m82_codigo = {$oDadoCompetencia->fa63_matestoqueinimei} ";
      $aDadosSaida   = $this->coletarDados($sMovimentacao);
      $oDadosSaida   = $aDadosSaida[0];

      $lEnviar = true;
      if( empty($oDadosSaida->dtvalidade) || empty($oDadosSaida->nulote) ) {
        $lEnviar = false;
      }

      if ($oDadosSaida->vlitem < 0)  {
        $lEnviar = false;
      }

      if ( !$lEnviar ) {
        $this->lPossuiRegistroInconsistente = true;
      }
      $this->salvarDadosCompetencia($oDadoCompetencia->fa63_sequencial, $oDadosSaida, $lEnviar);
    }
    return true;
  }

  /**
   * Inclui os dados referente a competência validando os que podem serem enviádos para o hórus
   * @return boolean
   */
  private function criarDadosCompetencia() {

    $aMedicamentos = $this->coletarDados();

    if ( count($aMedicamentos) == 0 ) {
      $this->atualizaSituacaoIntegracao(self::SEM_DADOS);
    }

    foreach ($aMedicamentos as $oMedicamento) {

      $lEnviar = true;
      if( empty($oMedicamento->dtvalidade) || empty($oMedicamento->nulote) ) {
        $lEnviar = false;
      }

      if ($oMedicamento->vlitem < 0)  {
        $lEnviar = false;
      }

      if ( !$lEnviar ) {
        $this->lPossuiRegistroInconsistente = true;
      }

      $this->salvarDadosCompetencia(null, $oMedicamento, $lEnviar);

    }
    return true;
  }

  /**
   *  Persiste os dados de saida/baixa de medicamentos referente a competencia
   *
   * @param  integer  $iCodigoDadosCompetencia
   * @param  stdClass $oDadosMedicamento
   * @param  boolean  $lEnviar
   * @return boolean
   * @throws DBException
   */
  private function salvarDadosCompetencia($iCodigoDadosCompetencia = null, $oDadosMedicamento, $lEnviar ) {

    $oDaoDadosCompetencia                        = new cl_dadoscompetenciasaida();
    $oDaoDadosCompetencia->fa63_sequencial       = $iCodigoDadosCompetencia;
    $oDaoDadosCompetencia->fa63_integracaohorus  = $this->iCodigoIntegracaoCompetencia;
    $oDaoDadosCompetencia->fa63_matestoqueinimei = $oDadosMedicamento->m82_codigo;
    $oDaoDadosCompetencia->fa63_unidade          = $this->oUPS->getCodigo();
    $oDaoDadosCompetencia->fa63_enviar           = $lEnviar ? 'true' : 'false';
    $oDaoDadosCompetencia->fa63_validadohorus    = 'false';
    $oDaoDadosCompetencia->fa63_catmat           = $oDadosMedicamento->nuproduto;
    $oDaoDadosCompetencia->fa63_cnes             = $this->oUPS->getCNES();
    $oDaoDadosCompetencia->fa63_tipo             = $oDadosMedicamento->tpproduto;
    $oDaoDadosCompetencia->fa63_valor            = $oDadosMedicamento->vlitem;
    $oDaoDadosCompetencia->fa63_lote             = $oDadosMedicamento->nulote;
    $oDaoDadosCompetencia->fa63_validade         = $oDadosMedicamento->dtvalidade;
    $oDaoDadosCompetencia->fa63_quantidade       = $oDadosMedicamento->qtsaida;
    $oDaoDadosCompetencia->fa63_data             = $oDadosMedicamento->dtsaida;
    $oDaoDadosCompetencia->fa63_movimentacao     = self::SAIDA_POR_PERDA;

    if ( !empty($oDadosMedicamento->dtvalidade) ) {

      $oDataValidade = new DBDate($oDadosMedicamento->dtvalidade);
      $oDataSaida    = new DBDate($oDadosMedicamento->dtsaida);

      if ( $oDataSaida->getTimeStamp() > $oDataValidade->getTimeStamp() ) {
        $oDaoDadosCompetencia->fa63_movimentacao = self::SAIDA_POR_VALIDADE_VENCIDA;
      }
    }

    if(empty($iCodigoDadosCompetencia)) {
      $oDaoDadosCompetencia->incluir(null);
    } else {
      $oDaoDadosCompetencia->alterar($iCodigoDadosCompetencia);
    }

    $oMsgErro = new stdClass();
    if ( $oDaoDadosCompetencia->erro_status == 0 ) {

      $oMsgErro->sErro = $oDaoDadosCompetencia->erro_msg;
      $oMsgErro->sTipo = "saída";
      throw new DBException( _M( self::MENSAGEM . "erro_salvar_dados_competencia", $oMsgErro )  );
    }

    return true;
  }

  private function getDadosCompetencia() {

    $sCampos  = " fa63_sequencial   as codigo,         ";
    $sCampos .= " fa63_catmat       as nuproduto,      ";
    $sCampos .= " fa63_valor        as vlitem,         ";
    $sCampos .= " fa63_validade     as dtvalidade,     ";
    $sCampos .= " fa63_lote         as nulote,         ";
    $sCampos .= " fa63_quantidade   as qtsaida,        ";
    $sCampos .= " fa63_data         as dtsaida,        ";
    $sCampos .= " fa63_movimentacao as tpmovimentacao, ";
    $sCampos .= " fa63_tipo         as tpproduto       ";

    $sWhere  = "     fa63_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sWhere .= " and fa63_enviar is true ";
    $sWhere .= " and fa63_validadohorus is false ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciasaida();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, $sCampos, null, $sWhere );
    $rsDadosCompetencia   = db_query( $sSqlDadosCompetencia );

    $this->aMedicamentos  = db_utils::getCollectionByRecord($rsDadosCompetencia);

  }


  /**
   * Atualiza os dados da competencia, validando todos os dados enviados
   * @param  string $iProtocolo código do protocolo do envino na competencia
   * @return boolean
   */
  public function validarDadosEnviados($iProtocolo) {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M(self::MENSAGEM . "sem_transacao") );
    }

    /**
     * Busca todos as dispensações enviadas no protocolo
     */
    $sListaEnviados  = " select fa65_dadoscompetencia ";
    $sListaEnviados .= "    from integracaohorusenvio ";
    $sListaEnviados .= "   inner join integracaohorusenviodadoscompetencia on fa65_integracaohorusenvio = fa64_sequencial ";
    $sListaEnviados .= "   where fa64_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sListaEnviados .= "     and fa64_protocolo = '{$iProtocolo}' ";

    $sSqlValidaEnviados  = " update dadoscompetenciasaida set fa63_validadohorus = true ";
    $sSqlValidaEnviados .= " where  fa63_sequencial in ($sListaEnviados) ";
    $rsValidaEnviados    = db_query($sSqlValidaEnviados);

    $oMsgErro = new stdClass();
    if ( !$rsValidaEnviados ) {

      $oMsgErro->sErro    = pg_last_error();
      $oMsgErro->sArquivo = self::NOME;
      throw new Exception( _M(self::MENSAGEM . "erro_validar_envio", $oMsgErro ) );
    }

    $sWhere  = "     fa63_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sWhere .= " and fa63_enviar is false ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciasaida();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da saída.");
    }

    $iRegistrosNaoEnviados  = db_utils::fieldsMemory($rsDadosCompetencia, 0)->count;
    if ($iRegistrosNaoEnviados > 0) {

      $this->atualizaSituacaoIntegracao(self::PARCIALMENTE_ENVIADO);
      return true;
    }
    $this->atualizaSituacaoIntegracao(self::CONCLUIDO);
    return true;

  }

  public function atualizaSituacaoEnvio() {

    $sWhere = " fa63_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciasaida();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da saída.");
    }

    $iDadosCompetencia = db_utils::fieldsMemory($rsDadosCompetencia, 0)->count;
    $iDadosProcessados = count($this->aMedicamentos);

    if ( $iDadosProcessados == $iDadosCompetencia ) {
      $this->atualizaSituacaoIntegracao(self::AGUARDANDO_HORUS);
    } else {
      $this->atualizaSituacaoIntegracao(self::PARCIALMENTE_ENVIADO);
    }
  }

}