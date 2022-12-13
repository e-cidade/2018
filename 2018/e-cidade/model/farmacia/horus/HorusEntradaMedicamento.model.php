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
 * Gera o arquivo se Entrada do Horus
 * Busca todas as entradas de medicamentos no estoque
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Iuri         <iuri@dbseller.com.br>
 * @package model\farmacia\horus
 *
 */
class HorusEntradaMedicamento extends HorusArquivoBase implements iArquivosHorus {

  const TIPO        = 'Entrada';
  const NOME        = 'Entrada.xml';

  protected $iCodigoTipo = 1;

  protected $aMedicamentos = array();

  /**
   * Lista de campos do arquivo xml
   * @var array
   */
  static $aCampos = array( "nuproduto"      => "nuProduto",
                           "vlitem"         => "vlItem",
                           "dtvalidade"     => "dtValidade",
                           "nulote"         => "nuLote",
                           "qtadquirida"    => "qtAdquirida",
                           "dtrecebimento"  => "dtRecebimento",
                           "tpproduto"      => "tpProduto",
                           "tpmovimentacao" => "tpMovimentacao" );

  /**
   * Busca todas entradas de medicamentos no estoque
   * @throws Exception
   */
  private function coletarDados( $sFiltraMovimentacao = null ) {

    $oDataInicial = new DBDate('01/'.$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));
    $iUltimoDia   = str_pad(cal_days_in_month(CAL_GREGORIAN, $this->oCompetencia->getMes(), $this->oCompetencia->getAno()), '0', 2);
    $oDataFinal  = new DBDate($iUltimoDia."/".$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));

    $aWhere[] = " m81_tipo = 1 ";
    $aWhere[] = " matestoque.m70_coddepto = {$this->oUPS->getCodigo()} ";
    $aWhere[] = " m80_data between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";

    if ( !empty($sFiltraMovimentacao) ) {
      $aWhere[] = $sFiltraMovimentacao;
    }

    $sWhere       = implode(" and ", $aWhere);
    $oDaoMaterial = new cl_far_matersaude();

    $sSqlMaterial = $oDaoMaterial->entradaMedicamentoFarmaciaBasica($sWhere);
    $rsMaterial   = db_query($sSqlMaterial);

    if ( !$rsMaterial ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MENSAGEM . "erro_coletar_dados_entrada", $oMsgErro) );
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

    $this->criarArquivo("E");
    foreach ($this->aMedicamentos as $oMedicamento) {
      $this->adicionarRegistro("registro", $oMedicamento, self::$aCampos);
    }
    return $this->fecharArquivo(self::NOME);
  }

  public function preProcessar() {

    parent::preProcessar();
    $this->getCodigoIntegracaoCompetencia(self::ARQUIVO_ENTRADA);
    $this->consistirDadosCompetencia();
  }

  private function consistirDadosCompetencia() {

    $sWhere = " fa62_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciaentrada();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "*", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    $oMsgErro = new stdClass();
    if ( !$rsDadosCompetencia ) {

      $oMsgErro->sErro = pg_last_error();
      $oMsgErro->sTipo = "entrada";
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
   * Valida se os dados inconsistentes já foram corrigidos e atualiza os dados da competencia
   *
   * @param  stdClass[] $aDadosCompetencia Todas as entradas na competência
   * @return boolean
   */
  private function atualizarDadosCompetencia($aDadosCompetencia) {

    foreach ($aDadosCompetencia as $oDadoCompetencia) {

      // só processa os registros que estão como enviar false
      if($oDadoCompetencia->fa62_enviar == 't') {
        continue;
      }

      $sMovimentacao = " m82_codigo = {$oDadoCompetencia->fa62_matestoqueinimei} ";
      $aDadosEntrada   = $this->coletarDados($sMovimentacao);
      $oDadosEntrada   = $aDadosEntrada[0];

      $lEnviar = true;
      if( empty($oDadosEntrada->dtvalidade) || empty($oDadosEntrada->nulote) ) {
        $lEnviar = false;
      }

      if ($oDadosEntrada->vlitem < 0)  {
        $lEnviar = false;
      }

      if ( !$lEnviar ) {
        $this->lPossuiRegistroInconsistente = true;
      }
      $this->salvarDadosCompetencia($oDadoCompetencia->fa62_sequencial, $oDadosEntrada, $lEnviar);
    }
    return true;
  }

  private function salvarDadosCompetencia($iCodigoDadosCompetencia = null, $oDadosMedicamento, $lEnviar ) {

    $oDaoDadosCompetencia                        = new cl_dadoscompetenciaentrada();
    $oDaoDadosCompetencia->fa62_sequencial       = $iCodigoDadosCompetencia;
    $oDaoDadosCompetencia->fa62_integracaohorus  = $this->iCodigoIntegracaoCompetencia;
    $oDaoDadosCompetencia->fa62_matestoqueinimei = $oDadosMedicamento->m82_codigo;
    $oDaoDadosCompetencia->fa62_unidade          = $this->oUPS->getCodigo();
    $oDaoDadosCompetencia->fa62_enviar           = $lEnviar ? 'true' : 'false';
    $oDaoDadosCompetencia->fa62_validadohorus    = 'false';
    $oDaoDadosCompetencia->fa62_cnes             = $this->oUPS->getCNES();
    $oDaoDadosCompetencia->fa62_catmat           = $oDadosMedicamento->nuproduto;
    $oDaoDadosCompetencia->fa62_tipo             = $oDadosMedicamento->tpproduto;
    $oDaoDadosCompetencia->fa62_valor            = $oDadosMedicamento->vlitem;
    $oDaoDadosCompetencia->fa62_validade         = $oDadosMedicamento->dtvalidade;
    $oDaoDadosCompetencia->fa62_lote             = $oDadosMedicamento->nulote;
    $oDaoDadosCompetencia->fa62_quantidade       = $oDadosMedicamento->qtadquirida;
    $oDaoDadosCompetencia->fa62_recebimento      = $oDadosMedicamento->dtrecebimento;
    $oDaoDadosCompetencia->fa62_movimentacao     = "E-AE";

    if(empty($iCodigoDadosCompetencia)) {
      $oDaoDadosCompetencia->incluir(null);
    } else {
      $oDaoDadosCompetencia->alterar($iCodigoDadosCompetencia);
    }

    $oMsgErro = new stdClass();
    if ( $oDaoDadosCompetencia->erro_status == 0 ) {

      $oMsgErro->sErro = $oDaoDadosCompetencia->erro_msg;
      $oMsgErro->sTipo = "entrada";
      throw new DBException( _M( self::MENSAGEM . "erro_salvar_dados_competencia", $oMsgErro )  );
    }

    return true;
  }

  /**
   * busca os dados de entrada de medicamentos para competencia
   * @return stdClass[]
   */
  private function getDadosCompetencia() {

    $sCampos  = " fa62_sequencial   as codigo,      ";
    $sCampos .= " fa62_catmat       as nuproduto,      ";
    $sCampos .= " fa62_valor        as vlitem,         ";
    $sCampos .= " fa62_validade     as dtvalidade,     ";
    $sCampos .= " fa62_lote         as nulote,         ";
    $sCampos .= " fa62_quantidade   as qtadquirida,    ";
    $sCampos .= " fa62_recebimento  as dtrecebimento,  ";
    $sCampos .= " fa62_movimentacao as tpmovimentacao, ";
    $sCampos .= " fa62_tipo         as tpproduto       ";

    $sWhere  = "     fa62_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sWhere .= " and fa62_enviar is true ";
    $sWhere .= " and fa62_validadohorus is false ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciaentrada();
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

    $sSqlValidaEnviados  = " update dadoscompetenciaentrada set fa62_validadohorus = true ";
    $sSqlValidaEnviados .= " where  fa62_sequencial in ($sListaEnviados) ";
    $rsValidaEnviados    = db_query($sSqlValidaEnviados);

    $oMsgErro = new stdClass();
    if ( !$rsValidaEnviados ) {

      $oMsgErro->sErro    = pg_last_error();
      $oMsgErro->sArquivo = self::NOME;
      throw new Exception( _M(self::MENSAGEM . "erro_validar_envio", $oMsgErro ) );
    }

    $sWhere  = "     fa62_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sWhere .= " and fa62_enviar is false ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciaentrada();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da entrada.");
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

    $sWhere = " fa62_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciaentrada();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da entrada.");
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