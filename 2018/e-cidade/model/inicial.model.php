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

require_once(modification("model/juridico/ProcessoForo.model.php"));
class inicial {

  /**
   * Tipo de certidao de inicial
   * @var integer
   */
  private $iTipoCertidao = "";

  /**
   * Código da inicial do foro
   * @var integer
   */
  private $iCodigoInicial;

  /**
   * Codigo do advogado da inicial
   * @var integer
   */
  private $iCodigoAdvogado;

  /**
   * Data da inicial
   * @var date
   */
  private $dtData;

  /**
   * Código do usuário que gerou a inicial
   * @var integer
   */
  private $iUsuario;

  /**
   * Código da localização
   * @var integer
   */
  private $iCodigoLocal;

  /**
   * Código da instituição
   * @var integer
   */
  private $iInstituicao;

  /**
   * Código da situação da inicial
   * @var integer
   */
  private $iSituacao;

  /**
   * Código da movimentação da inicial
   * @var integer
   */
  private $iCodigoMovimentacao;

  /**
   * Coleção de certidão
   * @var array
   */
  private $aCertidao;

  /**
   * Observacao que sera lançada a movimentacao
   * @var unknown_type
   */
  private $sObservacaoMovimentacao;

  /**
   * Construtor da Classe
   */
  public function __construct($iCodigoInicial = null) {

    if (!empty($iCodigoInicial)) {

      $oDaoInicial = db_utils::getDao("inicial");
      $sSqlInicial = $oDaoInicial->sql_query_file($iCodigoInicial);
      $rsInicial   = $oDaoInicial->sql_record($sSqlInicial);

      if (!$rsInicial || $oDaoInicial->numrows == 0) {
        return false;
      }

      $oInicial = db_utils::fieldsMemory($rsInicial, 0);

      $this->setCodigoInicial     ($oInicial->v50_inicial);
      $this->setCodigoAdvogado    ($oInicial->v50_advog);
      $this->setData              ($oInicial->v50_data);
      $this->setUsuario           ($oInicial->v50_id_login);
      $this->setCodigoLocal       ($oInicial->v50_codlocal);
      $this->setCodigoMovimentacao($oInicial->v50_codmov);
      $this->setInstituicao       ($oInicial->v50_instit);
      $this->setSituacao          ($oInicial->v50_situacao);

    }
  }

  /**
   * Define o código de tipo de certidao
   * @param $iTipoCertidao Código do tipo de certidão
   */
  public function setTipoCertidao($iTipoCertidao) {
    $this->iTipoCertidao = $iTipoCertidao;
  }

  public function getTipoCertidao(){

    $oDaoPardiv = db_utils::getDao("pardiv");

    if (trim($this->iTipoCertidao) == "") {

      $rsPardiv = $oDaoPardiv->sql_record($oDaoPardiv->sql_query_file(db_getsession('DB_instit')));

      if ( $oDaoPardiv->numrows > 0 ) {
        $oPardiv = db_utils::fieldsMemory($rsPardiv,0);
        $this->iTipoCertidao = $oPardiv->v04_tipocertidao;
      } else {
        throw new Exception("Configure o parametro para o tipo de debito de certidao do foro ");
      }

    }

    return $this->iTipoCertidao;

  }

  /**
   * Retorna o código da inicial
   * @return integer
   */
  public function getCodigoInicial() {
    return $this->iCodigoInicial;
  }

  /**
   * Define o código da inicial do foro
   * @param $iCodigoInicial Código da inicial do foro
   */
  public function setCodigoInicial($iCodigoInicial) {
    $this->iCodigoInicial = $iCodigoInicial;
  }

  /**
   * Retorna o código do advogado
   * @return integer
   */
  public function getCodigoAdvogado() {
    return $this->iCodigoAdvogado;
  }

  /**
   * Define o código do advogado
   * @param $iCodigoAdvogado Código do advogado da inicial
   */
  public function setCodigoAdvogado($iCodigoAdvogado) {
    $this->iCodigoAdvogado = $iCodigoAdvogado;
  }

  /**
   * Retorna a data da inicial
   * @return date
   */
  public function getData() {
    return $this->dtData;
  }

  /**
   * Define a data da inicial
   * @param $dtData Data da inicial
   */
  public function setData($dtData) {
    $this->dtData = $dtData;
  }

  /**
   * Retorna o código do usuário do cadastro da inicial
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }

  /**
   * Define o código do usuário do cadastro da inicial
   * @param $iUsuario Código do usuário do cadastro da inicial
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Retorna o código da localização da inicial
   * @return integer
   */
  public function getCodigoLocal(){
    return $this->iCodigoLocal;
  }

  /**
   * Define o código da localização da inicial
   * @param integer $iCodigoLocal Código de localização da inicial
   */
  public function setCodigoLocal($iCodigoLocal) {
    $this->iCodigoLocal = $iCodigoLocal;
  }

  /**
   * Retorna o código da instituição
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Define o código da instituição da inicial
   * @param $iInstituicao Código da instituição da inicial
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna o código da situação da inicial
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Define o código da situação da inicial
   * @param $iSituacao Código da situação da inicial
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Retorna o código da movimentação
   * @return integer
   */
  public function getCodigoMovimentacao() {
    return $this->iCodigoMovimentacao;
  }

  /**
   *  Define o código da movimentação
   * @param $iCodigoMovimentacao Código da movimentação
   */
  public function setCodigoMovimentacao($iCodigoMovimentacao) {
    $this->iCodigoMovimentacao = $iCodigoMovimentacao;
  }

  public function setObservacaoMovimentacao($sObservacaoMovimentacao) {
    $this->sObservacaoMovimentacao = $sObservacaoMovimentacao;
  }

  public function getObservacaoMovimentacao() {
    return $this->sObservacaoMovimentacao;
  }

  public function anulaInicial($iInicial="",$iCodSituacao="", $sObservacao = null){

    if ( trim($iInicial) == "" ) {
      throw new Exception("Anualção abortada, nº da inicial inválido!");
    }

    if ( trim($iCodSituacao) == "" ) {
      throw new Exception("Anualção abortada, código de situação inválido!");
    }

    $oDaoArrecad               = db_utils::getDao("arrecad");
    $oDaoTermoini              = db_utils::getDao("termoini");
    $oDaoProcessoForoInicial   = db_utils::getDao("processoforoinicial");
    $oDaoInicialNumpre         = db_utils::getDao("inicialnumpre");
    $oDaoInicialCert           = db_utils::getDao("inicialcert");
    $oDaoInicialCertAnula      = db_utils::getDao("inicialcert_anulada");
    $oDaoInicial               = db_utils::getDao("inicial");
    $oDaoInicialmov            = db_utils::getDao("inicialmov");

    // Procura na Termo com situação 1, 3
    $sSqlTermoini = $oDaoTermoini->sql_query(null, null, "*, v07_situacao", null, "inicial = {$iInicial} and v07_situacao in (1, 3)");
    $rsTermo      = $oDaoTermoini->sql_record($sSqlTermoini);
    if($oDaoTermoini->numrows > 0){

      $iSituacao = db_utils::fieldsMemory($rsTermo, 0)->v07_situacao;
      $sSituacao = "está parcelada";
      if($iSituacao == 3){
        $sSituacao = "foi reparcelada";
      }
      throw new BusinessException("Não foi possivel anular, a inicial {$sSituacao}.");
    }

    // Verifica se Inicial tem processo no foro
    $rsForo = $oDaoProcessoForoInicial->sql_record($oDaoProcessoForoInicial->sql_query_file("","*", "", " v71_inicial = {$iInicial}"));

    if ($oDaoProcessoForoInicial->numrows > 0) {

      $oDaoProcessoForoInicial->excluir($iInicial);

      if($oDaoProcessoForoInicial->erro_status == 0){
        throw new Exception($oDaoProcessoForoInicial->erro_msg);
      }
    }

    // Altera Inicial para Anulada
    $oDaoInicial->v50_inicial  = $iInicial;
    $oDaoInicial->v50_situacao = 2;
    $oDaoInicial->alterar($iInicial);

    if($oDaoInicial->erro_status == 0){
      throw new Exception($oDaoInicial->erro_msg);
    }

    // Altera Situacao na InicialMov para Anulada
    if (trim($this->getObservacaoMovimentacao()) != '') {
      $oDaoInicialmov->v56_obs = $this->getObservacaoMovimentacao();
    }

    $oDaoInicialmov->atuinicialmov($iInicial,$iCodSituacao);

    if($oDaoInicialmov->erro_status==0){
      throw new Exception($oDaoInicialmov->erro_msg);
    }

    /**
     * Volta Tipo no Arrecad
     */
    $sSqlNumpresInicial  = " select distinct v01_numpre                                                              ";
    $sSqlNumpresInicial .= "   from ( select certid.v13_certid,                                                      ";
    $sSqlNumpresInicial .= "                 divida.v01_numpre                                                       ";
    $sSqlNumpresInicial .= "            from certid                                                                  ";
    $sSqlNumpresInicial .= "                 inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid  ";
    $sSqlNumpresInicial .= "                 inner join certdiv     on certdiv.v14_certid    = certid.v13_certid     ";
    $sSqlNumpresInicial .= "                 inner join divida      on divida.v01_coddiv     = certdiv.v14_coddiv    ";
    $sSqlNumpresInicial .= "           where v51_inicial  = {$iInicial}                                              ";
    $sSqlNumpresInicial .= "      union                                                                              ";
    $sSqlNumpresInicial .= "          select certid.v13_certid,                                                      ";
    $sSqlNumpresInicial .= "                 termo.v07_numpre                                                        ";
    $sSqlNumpresInicial .= "            from certid                                                                  ";
    $sSqlNumpresInicial .= "                 inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid  ";
    $sSqlNumpresInicial .= "                 inner join certter    on certter.v14_certid       = certid.v13_certid   ";
    $sSqlNumpresInicial .= "                 inner join termo       on termo.v07_parcel       = certter.v14_parcel   ";
    $sSqlNumpresInicial .= "           where v51_inicial  = {$iInicial} ) as x                                       ";

    $rsNumpresInicial = db_query($sSqlNumpresInicial);
    $iLinhasNumpres   = pg_numrows($rsNumpresInicial);

    for($iInd = 0;$iInd < $iLinhasNumpres; $iInd++){

      $oNumpresInicial = db_utils::fieldsMemory($rsNumpresInicial,$iInd);

      $oDaoInicialNumpre->excluir(null," v59_inicial = {$iInicial} and v59_numpre = {$oNumpresInicial->v01_numpre} ");

      if($oDaoInicialNumpre->erro_status == 0){
        throw new Exception($oDaoInicialNumpre->erro_msg);
      }

      try {
        $oDaoArrecad->k00_tipo = $this->getTipoCertidao();
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oDaoArrecad->alterar_arrecad(" k00_numpre = {$oNumpresInicial->v01_numpre} ");

      if($oDaoArrecad->erro_status == 0){
        throw new Exception($oDaoArrecad->erro_msg);
      }

    }


    $rsProcuraCertidao = $oDaoInicialCert->sql_record($oDaoInicialCert->sql_query_file(null,null,"v51_certidao",null," v51_inicial = {$iInicial} "));
    $iLinhasCertidao   = $oDaoInicialCert->numrows;

    for ($iIndCertid=0; $iIndCertid < $iLinhasCertidao; $iIndCertid++) {

      $oInicialCertidao = db_utils::fieldsMemory($rsProcuraCertidao,$iIndCertid);

      $oDaoInicialCert->v51_inicial  = $iInicial;
      $oDaoInicialCert->v51_certidao = $oInicialCertidao->v51_certidao;
      $oDaoInicialCert->excluir($iInicial,$oInicialCertidao->v51_certidao);

      $oDaoInicialCertAnula->v49_inicial  = $iInicial;
      $oDaoInicialCertAnula->v49_certidao = $oInicialCertidao->v51_certidao;
      $oDaoInicialCertAnula->incluir();

      if($oDaoInicialCertAnula->erro_status == 0){
        throw new Exception($oDaoInicialCertAnula->erro_msg);
      }
    }
  }


  public function excluiCertidaoInicial($iCertidao="",$iInicial=""){

    if ( trim($iCertidao) == "" ) {
      throw new Exception("Exclusão de certidão da inicial abortada, nº de certidão inválido!");
    }

    if ( trim($iInicial) == "" ) {
      throw new Exception("Exclusão de certidão da inicial abortada, nº da inicial inválido!");
    }

    $oDaoArrecad        = db_utils::getDao("arrecad");
    $oDaoInicialNumpre  = db_utils::getDao("inicialnumpre");
    $oDaoInicialCert    = db_utils::getDao("inicialcert");

    /**
     * Verificamos se é última certidão da inicial, caso seja então a inicial será anulada
     */
    $sSql = $oDaoInicialCert->sql_query_file($iInicial,null,"distinct v51_certidao");
    $rsVerificaCertidoes = $oDaoInicialCert->sql_record($sSql);

    if ( $oDaoInicialCert->numrows > 1 ) {

      $sSqlNumpresInicial  = " select distinct v01_numpre                                                             ";
      $sSqlNumpresInicial .= "   from ( select certid.v13_certid,                                                     ";
      $sSqlNumpresInicial .= "                 divida.v01_numpre                                                      ";
      $sSqlNumpresInicial .= "            from certid                                                                 ";
      $sSqlNumpresInicial .= "                 inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid ";
      $sSqlNumpresInicial .= "                 inner join certdiv     on certdiv.v14_certid    = certid.v13_certid    ";
      $sSqlNumpresInicial .= "                 inner join divida      on divida.v01_coddiv     = certdiv.v14_coddiv   ";
      $sSqlNumpresInicial .= "           where v51_inicial  = {$iInicial}                                             ";
      $sSqlNumpresInicial .= "             and v51_certidao = {$iCertidao}                                            ";
      $sSqlNumpresInicial .= "      union                                                                             ";
      $sSqlNumpresInicial .= "          select certid.v13_certid,                                                     ";
      $sSqlNumpresInicial .= "                 termo.v07_numpre                                                       ";
      $sSqlNumpresInicial .= "            from certid                                                                 ";
      $sSqlNumpresInicial .= "                 inner join inicialcert on inicialcert.v51_certidao = certid.v13_certid ";
      $sSqlNumpresInicial .= "                 inner join certter    on certter.v14_certid       = certid.v13_certid  ";
      $sSqlNumpresInicial .= "                 inner join termo       on termo.v07_parcel       = certter.v14_parcel  ";
      $sSqlNumpresInicial .= "           where v51_inicial  = {$iInicial}                                             ";
      $sSqlNumpresInicial .= "             and v51_certidao = {$iCertidao} ) as x                                     ";

      $rsNumpresInicial = db_query($sSqlNumpresInicial);
      $iLinhasNumpres   = pg_numrows($rsNumpresInicial);

      for($iInd = 0;$iInd < $iLinhasNumpres; $iInd++){

        $oNumpresInicial = db_utils::fieldsMemory($rsNumpresInicial,$iInd);

        $oDaoInicialNumpre->excluir(null," v59_inicial = {$iInicial} and v59_numpre = {$oNumpresInicial->v01_numpre} ");

        if($oDaoInicialNumpre->erro_status == 0){
          throw new Exception($oDaoInicialNumpre->erro_msg);
        }

        try {
          $oDaoArrecad->k00_tipo = $this->getTipoCertidao();
        } catch (Exception $eException){
          throw new Exception($eException->getMessage());
        }

        $oDaoArrecad->alterar_arrecad(" k00_numpre = {$oNumpresInicial->v01_numpre} ");

        if($oDaoArrecad->erro_status == 0){
          throw new Exception($oDaoArrecad->erro_msg);
        }

      }

      $oDaoInicialCert->v51_inicial  = $iInicial;
      $oDaoInicialCert->v51_certidao = $iCertidao;
      $oDaoInicialCert->excluir($iInicial,$iCertidao);

      if($oDaoInicialCert->erro_status == 0){
        throw new Exception($oDaoInicialCert->erro_msg);
      }

    } else {
      $this->anulaInicial($iInicial,9);
    }
  }

  /**
   * Retorna instancia do model ProcessoForo passando código do processo
   * @throws Exception Nenhum processo do foro encontrado
   * @return ProcessoForo
   */
  public function getProcessoForo() {

    if ( empty($this->iCodigoInicial) ) {
      throw new Exception ('Código da inicial não informado ou inválido.');
    }

    $oDaoProcessoForo = db_utils::getDao('processoforoinicial');
    $sSqlProcessoForo = $oDaoProcessoForo->sql_query_file(null,
                                                          "v71_processoforo",
                                                          null,
                                                          "v71_inicial = {$this->iCodigoInicial}");

    $rsProcessoForo   = $oDaoProcessoForo->sql_record($sSqlProcessoForo);

    $iCodigoProcessoForo = null;
    if ($oDaoProcessoForo->numrows > 0) {
      $iCodigoProcessoForo = db_utils::fieldsMemory($rsProcessoForo, 0)->v71_processoforo;
    }

    return new ProcessoForo($iCodigoProcessoForo);

  }

  /**
   * Verifica se a inicial pertence a um processo do foro
   * @throws Exception Código da inicial não informado
   * @return boolean
   */
  public function hasProcessoForo () {

    if ( empty($this->iCodigoInicial) ) {
      throw new Exception ('Código da inicial não informado ou inválido.');
    }

    $oDaoProcessoForo = db_utils::getDao('processoforoinicial');
    $sSqlProcessoForo = $oDaoProcessoForo->sql_query_file(null,
                                                          "v71_processoforo",
                                                          null,
                                                          "v71_inicial = {$this->iCodigoInicial}");

    $rsProcessoForo   = $oDaoProcessoForo->sql_record($sSqlProcessoForo);

    if ($oDaoProcessoForo->numrows == 0) {
      return false;
    }

    return true;

  }

  /**
   * Inclui ou altera uma inicial, dependendo se o código estiver setado
   * @throws DBException erro na inclusão
   */
  public function salvar() {

    $oDaoInicial = new cl_inicial();

    $oDaoInicial->v50_advog    = $this->getCodigoAdvogado();
    $oDaoInicial->v50_data     = $this->getData();
    $oDaoInicial->v50_id_login = $this->getUsuario();
    $oDaoInicial->v50_codlocal = $this->getCodigoLocal();
    $oDaoInicial->v50_codmov   = $this->getCodigoMovimentacao();
    $oDaoInicial->v50_instit   = $this->getInstituicao();
    $oDaoInicial->v50_situacao = $this->getSituacao();

    if (empty($this->iCodigoInicial)) {

      $oDaoInicial->incluir(null);

      if ($oDaoInicial->erro_status == "0") {
        throw new DBException('Erro ao incluir inicial. ERRO: ' . $oDaoInicial->erro_msg);
      }

      $this->setCodigoInicial($oDaoInicial->v50_inicial);
      return true;
    }

    $oDaoInicial->v50_inicial = $this->getCodigoInicial();
    $oDaoInicial->alterar($this->getCodigoInicial());

    if ($oDaoInicial->erro_status == "0") {
      throw new DBException('Erro ao alterar inicial. ERRO: ' . $oDaoInicial->erro_msg);
    }

    return true;
  }

  /**
   * Adiciona uma movimentação a inicial
   * @param integer $iCodigoSituacaoMovimentacao
   * @param string  $sObservacoes
   * @throws DBException
   */
  public function adicionarMovimentacao($iCodigoSituacaoMovimentacao, $sObservacoes) {

    $oDaoInicialMov = db_utils::getDao('inicialmov');

    $oDaoInicialMov->v56_inicial  = $this->getCodigoInicial();
    $oDaoInicialMov->v56_codsit   = $iCodigoSituacaoMovimentacao;
    $oDaoInicialMov->v56_obs      = $sObservacoes;
    $oDaoInicialMov->v56_data     = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoInicialMov->v56_id_login = db_getsession('DB_id_usuario');
    $oDaoInicialMov->incluir(null);

    if ($oDaoInicialMov->erro_status == '0') {
      throw new DBException('Erro ao incluir movimentação da inicial. ERRO: ' . $oDaoInicialMov->erro_msg);
    }

    $this->setCodigoMovimentacao($oDaoInicialMov->v56_codmov);

  }

  /**
   * Altera a situação e observação de uma inicial
   * @param integer $iCodigoMovimentacao
   * @param integer $iCodigoSituacaoMovimentacao
   * @param sObservacoes $sObservacoes
   * @throws DBException A movimentação não foi encontrada
   */
  public function alterarMovimentacao($iCodigoMovimentacao, $iCodigoSituacaoMovimentacao, $sObservacoes) {

    $oDaoInicialMov = db_utils::getDao('inicialmov');

    $sSqlInicialMov = $oDaoInicialMov->sql_query_file($iCodigoMovimentacao);

    $rsInicialMov   = $oDaoInicialMov->sql_record($sSqlInicialMov);

    if ($oDaoInicialMov->numrows == 0) {

      throw new DBException('Movimento da inicial não encontrado.');

    }

    $oInicialMov                  = db_utils::fieldsMemory($rsInicialMov, 0);
    $oDaoInicialMov->v56_data     = $oInicialMov->v56_data;
    $oDaoInicialMov->v56_id_login = $oInicialMov->v56_id_login;
    $oDaoInicialMov->v56_inicial  = $oInicialMov->v56_inicial;

    $oDaoInicialMov->v56_codmov   = $iCodigoMovimentacao;
    $oDaoInicialMov->v56_codsit   = $iCodigoSituacaoMovimentacao;
    $oDaoInicialMov->v56_obs      = $sObservacoes;

    $oDaoInicialMov->alterar($iCodigoMovimentacao);

    if ($oDaoInicialMov->erro_status == '0') {
      throw new DBException('Erro ao incluir movimentação da inicial. ERRO: ' . $oDaoInicialMov->erro_msg);
    }

    $this->setCodigoMovimentacao($oDaoInicialMov->v56_codmov);

  }

  /**
   * Exclui a ultima movimentacao de uma inicial, setando a penultima na tabela inicial novamente
   * @param integer $iCodigoMovimentacao
   * @throws DBException
   */
  public function excluirMovimentacao($iCodigoMovimentacao) {

    $oDaoInicialMov = db_utils::getDao('inicialmov');

    $oDaoInicialMov->excluir($iCodigoMovimentacao);

    if ($oDaoInicialMov->erro_status == '0') {
      throw new DBException('Erro ao incluir movimentação da inicial. ERRO: ' . $oDaoInicialMov->erro_msg);
    }

    $sSqlInicialMov = $oDaoInicialMov->sql_query_file(null, "coalesce(max(v56_codmov), 0) as codigo_movimentacao", null, "v56_inicial = {$this->getCodigoInicial()}");

    $rsInicialMov   = $oDaoInicialMov->sql_record($sSqlInicialMov);

    $iCodigoMovimentacao = db_utils::fieldsMemory($rsInicialMov, 0)->codigo_movimentacao;

    /**
     * caso seja excluida a ultima movimentação, atribuimos 0 ao codigo da movimentação
     */
    if ($oDaoInicialMov->numrows > 0 and $iCodigoMovimentacao <> 0) {

      $this->setCodigoMovimentacao($iCodigoMovimentacao);

    } else {

      $this->setCodigoMovimentacao('0');

    }

  }


  /**
   * Retorna um objeto contendo a ultima movimentação lançada de uma inicial
   * @return stdClass
   */
  public function getUltimaMovimentacao () {

    $oDaoInicialMov      = db_utils::getDao('inicialmov');

    $sSqlInicialMov      = $oDaoInicialMov->sql_query_file($this->getCodigoMovimentacao());

    $rsInicialMov        = $oDaoInicialMov->sql_record($sSqlInicialMov);

    $oUltimaMovimentacao = new stdClass();

    $oUltimaMovimentacao->iCodigoMovimentacao = '';
    $oUltimaMovimentacao->iCodigoInicial      = '';
    $oUltimaMovimentacao->iCodigoSituacao     = '';
    $oUltimaMovimentacao->sObservacao         = '';
    $oUltimaMovimentacao->dDataMovimentacao   = '';
    $oUltimaMovimentacao->iCodigoUsuario      = '';

    if ($oDaoInicialMov->numrows > 0) {

      $oInicialMov = db_utils::fieldsMemory($rsInicialMov, 0);

      $oUltimaMovimentacao->iCodigoMovimentacao = $oInicialMov->v56_codmov  ;
      $oUltimaMovimentacao->iCodigoInicial      = $oInicialMov->v56_inicial ;
      $oUltimaMovimentacao->iCodigoSituacao     = $oInicialMov->v56_codsit  ;
      $oUltimaMovimentacao->sObservacao         = $oInicialMov->v56_obs     ;
      $oUltimaMovimentacao->dDataMovimentacao   = $oInicialMov->v56_data    ;
      $oUltimaMovimentacao->iCodigoUsuario      = $oInicialMov->v56_id_login;

    }

    return $oUltimaMovimentacao;

  }

}