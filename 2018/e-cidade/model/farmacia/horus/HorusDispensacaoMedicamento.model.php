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
 * Gera o arquivo se Dispensação do Horus
 * Busca todas as dispensação de medicamentos
 * (Medicamento entregue a paciente)
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author Iuri         <iuri@dbseller.com.br>
 * @package model\farmacia\horus
 *
 */
class HorusDispensacaoMedicamento extends HorusArquivoBase implements iArquivosHorus {

  const TIPO        = 'Dispensação';
  const NOME        = 'Dispensacao.xml';

  protected $iCodigoTipo = 3;

  protected $aMedicamentos = array();

  /**
   * Lista de campos do arquivo xml
   * @var array
   */
  static $aCampos = array("nuproduto"               => "nuProduto",
                          "tpproduto"               => "tpProduto",
                          "vlitem"                  => "vlItem",
                          "dtvalidade"              => "dtValidade",
                          "nulote"                  => "nuLote",
                          "qtmedicamentodispensada" => "qtMedicamentoDispensada",
                          "dtdispensacao"           => "dtDispensacao",
                          "nucnspaciente"           => "nuCnsPaciente"
                          );

  /**
   * Busca todos medicamentos que foram dispensados/entregues ao paciente (cgs)
   * @throws Exception
   */
  private function coletarDados( $FiltrarRetirada = null ) {

    $oDataInicial = new DBDate('01/'.$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));
    $iUltimoDia   = str_pad(cal_days_in_month(CAL_GREGORIAN, $this->oCompetencia->getMes(), $this->oCompetencia->getAno()), '0', 2);
    $oDataFinal   = new DBDate($iUltimoDia."/".$this->oCompetencia->getCompetencia(DBCompetencia::FORMATO_MMAAAA));

    $aWhere[] = " m81_tipo = 2 ";
    $aWhere[] = " m81_codtipo = 17 ";
    $aWhere[] = " matestoque.m70_coddepto = {$this->oUPS->getCodigo()} ";
    $aWhere[] = " m80_data between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}' ";

    if ( !empty($FiltrarRetirada) ) {
      $aWhere[] = $FiltrarRetirada;
    }

    $sWhere       = implode(" and ", $aWhere);
    $oDaoMaterial = new cl_far_matersaude();

    $sSqlMaterial = $oDaoMaterial->dispensacaoMedicamentoFarmaciaBasica($sWhere);
    $rsMaterial   = db_query($sSqlMaterial);

    if ( !$rsMaterial ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MENSAGEM . "erro_coletar_dados_dispensacao", $oMsgErro) );
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

    $oXmlWriter = $this->criarArquivo("DP");
    foreach ($this->aMedicamentos as $oMedicamento) {
      $this->adicionarRegistro("dispensacao", $oMedicamento, self::$aCampos);
    }
    return $this->fecharArquivo(self::NOME);
  }


  /**
   * Realiza um pré processamento da competencia, validando os dados e identificando os registros que podem ser enviados
   * para o Hórus...
   * @throws DBException
   * @throws BusinessException
   */
  public function preProcessar() {

    parent::preProcessar();
    $this->getCodigoIntegracaoCompetencia(self::ARQUIVO_DISPENSACAO);
    $this->consistirDadosCompetencia();

  }

  /**
   * Busca os dados referente a competencia e valida se pode ser enviado ao servidor do Hórus
   * @return boolean
   */
  protected function consistirDadosCompetencia() {

    $sWhere = " fa61_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciadispensacao();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "*", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    $oMsgErro = new stdClass();
    if ( !$rsDadosCompetencia ) {

      $oMsgErro->sErro = pg_last_error();
      $oMsgErro->sTipo = "dispensação";
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
   * Inclui os dados referente a competencia
   */
  private function criarDadosCompetencia() {

    $aMedicamentos = $this->coletarDados();
    if ( count($aMedicamentos) == 0 ) {
      $this->atualizaSituacaoIntegracao(self::SEM_DADOS);
    }

    foreach ($this->coletarDados() as $oMedicamento) {

      $lEnviar = true;
      if( empty($oMedicamento->dtvalidade) || empty($oMedicamento->nulote) ) {
        $lEnviar = false;
      }

      if(!validaCnsDefinitivo($oMedicamento->nucnspaciente) && !validaCnsProvisorio($oMedicamento->nucnspaciente)) {
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

  }

  /**
   * Valida se os dados inconsistentes já foram corrigidos e atualiza os dados da competencia
   * @param stdClass[] $aDadosCompetencia
   */
  private function atualizarDadosCompetencia($aDadosCompetencia) {

    foreach($aDadosCompetencia as $oDadosCompetencia) {

      if($oDadosCompetencia->fa61_enviar == 't') {
        continue;
      }

      $sItem             = " fa06_i_codigo = {$oDadosCompetencia->fa61_far_retiradaitens} ";
      $aDadosDispensacao = $this->coletarDados($sItem);
      $oDadosDispensacao = $aDadosDispensacao[0];

      $lEnviar = true;
      if( empty($oDadosDispensacao->dtvalidade) || empty($oDadosDispensacao->nulote) ) {
        $lEnviar = false;
      }

      if ($oDadosDispensacao->vlitem < 0)  {
        $lEnviar = false;
      }

      if(!validaCnsDefinitivo($oDadosDispensacao->nucnspaciente) && !validaCnsProvisorio($oDadosDispensacao->nucnspaciente)) {
        $lEnviar = false;
      }

      // Se os dados ainda não foram atualizados, vai para próxima dispensação
      if(!$lEnviar) {

        $this->lPossuiRegistroInconsistente = false;
        continue;
      }

      $this->salvarDadosCompetencia($oDadosCompetencia->fa61_sequencial, $oDadosDispensacao, $lEnviar);
    }

  }

  /**
   * Persiste os dados de dispensação referente a competencia
   *
   * @param  integer     $iCodigoDadosCompetencia
   * @param  stdClass    $oDadosDispensacao
   * @param  boolean     $lEnviar
   * @return boolean
   * @throws DBException
   */
  private function salvarDadosCompetencia($iCodigoDadosCompetencia = null, $oDadosDispensacao, $lEnviar){

    $oDaoDadosCompetencia = new cl_dadoscompetenciadispensacao();

    $oDaoDadosCompetencia->fa61_sequencial        = $iCodigoDadosCompetencia;
    $oDaoDadosCompetencia->fa61_far_retiradaitens = $oDadosDispensacao->fa06_i_codigo;
    $oDaoDadosCompetencia->fa61_integracaohorus   = $this->iCodigoIntegracaoCompetencia;
    $oDaoDadosCompetencia->fa61_enviar            = $lEnviar ? 'true' : 'false';
    $oDaoDadosCompetencia->fa61_validadohorus     = 'false';
    $oDaoDadosCompetencia->fa61_unidade           = $this->oUPS->getCodigo();
    $oDaoDadosCompetencia->fa61_cnes              = $this->oUPS->getCNES();
    $oDaoDadosCompetencia->fa61_catmat            = $oDadosDispensacao->nuproduto;
    $oDaoDadosCompetencia->fa61_tipo              = $oDadosDispensacao->tpproduto;
    $oDaoDadosCompetencia->fa61_valor             = $oDadosDispensacao->vlitem;
    $oDaoDadosCompetencia->fa61_validade          = empty($oDadosDispensacao->dtvalidade) ? null : $oDadosDispensacao->dtvalidade;
    $oDaoDadosCompetencia->fa61_lote              = $oDadosDispensacao->nulote;
    $oDaoDadosCompetencia->fa61_quantidade        = $oDadosDispensacao->qtmedicamentodispensada;
    $oDaoDadosCompetencia->fa61_dispensacao       = $oDadosDispensacao->dtdispensacao;
    $oDaoDadosCompetencia->fa61_cns               = $oDadosDispensacao->nucnspaciente;

    if(empty($iCodigoDadosCompetencia)) {
      $oDaoDadosCompetencia->incluir(null);
    } else {
      $oDaoDadosCompetencia->alterar($iCodigoDadosCompetencia);
    }

    $oMsgErro = new stdClass();
    if ( $oDaoDadosCompetencia->erro_status == 0 ) {

      $oMsgErro->sErro = $oDaoDadosCompetencia->erro_msg;
      $oMsgErro->sTipo = "dispensação";
      throw new DBException( _M( self::MENSAGEM . "erro_salvar_dados_competencia", $oMsgErro )  );
    }

    return true;
  }

  /**
   * Retorna os dados da competencia
   */
  private function getDadosCompetencia() {

    $oDaoDadosCompetencia      = new cl_dadoscompetenciadispensacao();
    $iCodigoArquivoDispensacao = $this->getCodigoIntegracaoCompetencia(self::ARQUIVO_DISPENSACAO);

    $sCampos  = "  fa61_sequencial  as codigo ";
    $sCampos .= " ,fa61_catmat      as nuproduto ";
    $sCampos .= " ,fa61_tipo        as tpproduto ";
    $sCampos .= " ,fa61_valor       as vlitem ";
    $sCampos .= " ,fa61_validade    as dtvalidade ";
    $sCampos .= " ,fa61_lote        as nulote ";
    $sCampos .= " ,fa61_quantidade  as qtmedicamentodispensada ";
    $sCampos .= " ,fa61_dispensacao as dtdispensacao ";
    $sCampos .= " ,fa61_cns         as nucnspaciente ";

    $sWhere  = " fa61_enviar is true ";
    $sWhere .= " and fa61_validadohorus is false ";
    $sWhere .= " and fa61_integracaohorus = {$iCodigoArquivoDispensacao} ";

    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, $sCampos, null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);
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

    $sSqlValidaEnviados  = " update dadoscompetenciadispensacao set fa61_validadohorus = true ";
    $sSqlValidaEnviados .= " where  fa61_sequencial in ($sListaEnviados) ";
    $rsValidaEnviados    = db_query($sSqlValidaEnviados);

    $oMsgErro = new stdClass();
    if ( !$rsValidaEnviados ) {

      $oMsgErro->sErro    = pg_last_error();
      $oMsgErro->sArquivo = self::NOME;
      throw new Exception( _M(self::MENSAGEM . "erro_validar_envio", $oMsgErro ) );
    }

    $sWhere  = "     fa61_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";
    $sWhere .= " and fa61_enviar is false ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciadispensacao();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da dispensação.");
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

    $sWhere = " fa61_integracaohorus = {$this->iCodigoIntegracaoCompetencia} ";

    $oDaoDadosCompetencia = new cl_dadoscompetenciadispensacao();
    $sSqlDadosCompetencia = $oDaoDadosCompetencia->sql_query_file(null, "count(*)", null, $sWhere);
    $rsDadosCompetencia   = db_query($sSqlDadosCompetencia);

    if ( !$rsDadosCompetencia ) {
      throw new Exception("Erro ao busar dados da dispensação.");
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