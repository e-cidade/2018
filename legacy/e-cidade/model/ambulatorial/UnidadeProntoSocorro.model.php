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


/**
 * UPS (Unidade Pronto Socorro)
 *
 * @package ambulatorial
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.9 $
 *
 */
class UnidadeProntoSocorro {

  /**
   * Departamento vinculádo
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   * Código do CNES (Cadastro Nacional de Estabelecimentos de Saúde)
   * @var string
   */
  private $sCNES;

  /**
   * Código do alvará
   * @var string
   */
  private $sAlvara;

  /**
   * Código do tipo de unidade
   * @var integer
   */
  private $iTipoUnidade;

  /**
   * Descrição do tipo de unidade
   * @var string
   */
  private $sTipoUnidade;

  /**
   * CNPJ ou CPF
   * @var string
   */
  private $sCNPJCPF;

  /**
   * SIASUS
   * @var string
   */
  private $sSIASUS;

  /**
   * Situação
   * 1 - INDIVIDUAL
   * 2 - MANTIDO
   * @var integer
   */
  private $iSituacao;

  private $iRegiao;
  private $sMicroRegiao;

  /**
   * Esfera Administrativa
   * @var integer
   */
  private $iCodigoEsferaAdministrativa;

  /**
   * Atividade de Ensino
   * @var integer
   */
  private $iCodigoAtividade;

  /**
   * Retenção Tributos
   * @var integer
   */
  private $iCodigoRetencaoTributos;

  /**
   * Natureza Organização:
   * @var integer
   */
  private $iCodigoNaturezaOrganizacao;

  /**
   * Fluxo de Clientela
   * @var ineger
   */
  private $iCodigoFluxoClientela;

  /**
   * Turno de Atendimento
   * @var integer
   */
  private $iCodigoTurnoAtendimento;


  /**
   * Nível de Hierarquia
   * @var integer
   */
  private $iCodigoNivelHierarquia;

  /**
   * Data Expedição Alvará
   * @var [type]
   */
  private $oDtExpedicaoAlvara;

  /**
   * Orgão de Expedição do alvará
   * @var [type]
   */
  private $sOrgaoExpedicaoAlvara;

  /**
   * código do IBGE
   * @var integer
   */
  private $iIBGE;

  /**
   * Responsavel
   * @var object
   */
  private $oResponsavel;

  private $iCodigoDistrito;

  /**
   * Construtor
   * @param integer $iUnidade
   * @throws ParameterException
   */
  public function __construct($iUnidade = null) {

    if (!empty($iUnidade)) {

      $sCampos  = " sd02_i_codigo, sd02_v_cnes, sd02_v_num_alvara, sd42_i_tp_unid_id, sd42_v_descricao, ";
      $sCampos .= " sd02_cnpjcpf, sd02_c_siasus, sd02_i_situacao, sd02_i_regiao, sd02_v_microreg, sd02_i_cod_esfadm, ";
      $sCampos .= " sd02_i_cod_ativ, sd02_i_reten_trib, sd02_i_cod_natorg, sd02_i_cod_client, sd02_i_cod_turnat, ";
      $sCampos .= " sd02_i_codnivhier, sd02_d_data_exped, sd02_v_ind_orgexp, sd02_i_cidade, sd02_i_numcgm, sd02_i_distrito ";

      $oDaoUnidade = new cl_unidades();
      $sSqlUnidade = $oDaoUnidade->sql_query_model($iUnidade, $sCampos);
      $rsUnidade   = $oDaoUnidade->sql_record($sSqlUnidade);

      if ($oDaoUnidade->numrows == 0) {

        $oDepartamento          = new DBDepartamento( $iUnidade );
        $oParametrosMsg         = new stdClass();
        $oParametrosMsg->sCampo = $oDepartamento->getNomeDepartamento();
        throw new ParameterException(_M("saude.ambulatorial.UnidadeProntoSocorro.ups_nao_encontrada", $oParametrosMsg));
      }

      $oDadosUnidade                     = db_utils::fieldsMemory($rsUnidade, 0);
      $this->oDepartamento               = DBDepartamentoRepository::getDBDepartamentoByCodigo($oDadosUnidade->sd02_i_codigo);
      $this->sCNES                       = $oDadosUnidade->sd02_v_cnes;
      $this->sAlvara                     = $oDadosUnidade->sd02_v_num_alvara;
      $this->iTipoUnidade                = $oDadosUnidade->sd42_i_tp_unid_id;
      $this->sTipoUnidade                = $oDadosUnidade->sd42_v_descricao;
      $this->sCNPJCPF                    = $oDadosUnidade->sd02_cnpjcpf;
      $this->sSIASUS                     = $oDadosUnidade->sd02_c_siasus;
      $this->iSituacao                   = $oDadosUnidade->sd02_i_situacao;
      $this->iRegiao                     = $oDadosUnidade->sd02_i_regiao;
      $this->sMicroRegiao                = $oDadosUnidade->sd02_v_microreg;
      $this->iCodigoEsferaAdministrativa = $oDadosUnidade->sd02_i_cod_esfadm;
      $this->iCodigoAtividade            = $oDadosUnidade->sd02_i_cod_ativ;
      $this->iCodigoRetencaoTributos     = $oDadosUnidade->sd02_i_reten_trib;
      $this->iCodigoNaturezaOrganizacao  = $oDadosUnidade->sd02_i_cod_natorg;
      $this->iCodigoFluxoClientela       = $oDadosUnidade->sd02_i_cod_client;
      $this->iCodigoTurnoAtendimento     = $oDadosUnidade->sd02_i_cod_turnat;
      $this->iCodigoNivelHierarquia      = $oDadosUnidade->sd02_i_codnivhier;
      $this->sOrgaoExpedicaoAlvara       = $oDadosUnidade->sd02_v_ind_orgexp;
      $this->iIBGE                       = $oDadosUnidade->sd02_i_cidade;
      $this->iCodigoDistrito             = $oDadosUnidade->sd02_i_distrito;
      $this->oResponsavel                = CgmFactory::getInstanceByCgm($oDadosUnidade->sd02_i_numcgm);

      $this->oDtExpedicaoAlvara = null;
      if (!empty($oDadosUnidade->sd02_d_data_exped)) {
        $this->oDtExpedicaoAlvara = new DBDate($oDadosUnidade->sd02_d_data_exped);
      }
    }
  }

  /**
   * Retorna o departamento vinculádo
   * @return DBDepartamento
   */
  public function getDepartamento() {

    return $this->oDepartamento;
  }

  /**
   * Retorna o codigo do departamento em que a unidade está vinculado
   * @return integer Código da Unidade
   */
  public function getCodigo() {
    return $this->getDepartamento()->getCodigo();
  }

  /**
   * Setter CNES
   * @param string
   */
  public function setCNES ($sCNES) {
    $this->sCNES = $sCNES;
  }

  /**
   * Retorna o código do CNES (Cadastro Nacional de Estabelecimentos de Saúde)
   * @return string
   */
  public function getCNES() {

    return $this->sCNES;
  }

  /**
   * Setter Alvara
   * @param string
   */
  public function setAlvara ($sAlvara) {
    $this->sAlvara = $sAlvara;
  }
  /**
   * Retorna o alvara do estabelecimento
   * @return string
   */
  public function getAlvara() {

    return $this->sAlvara;
  }

  /**
   * Retorna a destrição do tipo de unidade
   * @return string
   */
  public function getDescricaoTipoUnidade() {

    return $this->sTipoUnidade;
  }

  /**
   * Setter Tipo Unidade
   * @param integer
   */
  public function setCodigoTipoUnidade ($iCodigoTipoUnidade) {
    $this->iTipoUnidade = $iCodigoTipoUnidade;
  }

  /**
   * Retorna o códico do tipo de unidade
   * @return integer
   */
  public function getCodigoTipoUnidade() {

    return $this->iTipoUnidade;
  }


  /**
   * Setter CNPJCPF
   * @param string
   */
  public function setCNPJCPF ($sCNPJCPF) {
    $this->sCNPJCPF = $sCNPJCPF;
  }

  /**
   * Getter CNPJCPF
   * @param string
   */
  public function getCNPJCPF () {
    return $this->sCNPJCPF;
  }


  /**
   * Setter SIASUS
   * @param string
   */
  public function setSIASUS ($sSIASUS) {
    $this->sSIASUS = $sSIASUS;
  }

  /**
   * Getter SIASUS
   * @param string
   */
  public function getSIASUS () {
    return $this->sSIASUS;
  }


  /**
   * Setter Situacao
   * @param integer
   */
  public function setSituacao ($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Getter Situacao
   * @param integer
   */
  public function getSituacao () {
    return $this->iSituacao;
  }


  /**
   * Setter Regiao
   * @param integer
   */
  public function setRegiao ($iRegiao) {
    $this->iRegiao = $iRegiao;
  }

  /**
   * Getter Regiao
   * @param integer
   */
  public function getRegiao () {
    return $this->iRegiao;
  }


  /**
   * Setter Micro Regiao
   * @param string
   */
  public function setMicroRegiao ($sMicroRegiao) {
    $this->sMicroRegiao = $sMicroRegiao;
  }

  /**
   * Getter Micro Regiao
   * @param string
   */
  public function getMicroRegiao () {
    return $this->sMicroRegiao;
  }


  /**
   * Setter Codigo Esfera Administrativa
   * @param integer
   */
  public function setCodigoEsferaAdministrativa ($iCodigoEsferaAdministrativa) {
    $this->iCodigoEsferaAdministrativa = $iCodigoEsferaAdministrativa;
  }

  /**
   * Getter Codigo Esfera Administrativa
   * @param integer
   */
  public function getCodigoEsferaAdministrativa () {
    return $this->iCodigoEsferaAdministrativa;
  }


  /**
   * Setter Codigo Atividade
   * @param integer
   */
  public function setCodigoAtividade ($iCodigoAtividade) {
    $this->iCodigoAtividade = $iCodigoAtividade;
  }

  /**
   * Getter Codigo Atividade
   * @param integer
   */
  public function getCodigoAtividade () {
    return $this->iCodigoAtividade;
  }


  /**
   * Setter Codigo Retencao Tributos
   * @param integer
   */
  public function setCodigoRetencaoTributos ($iCodigoRetencaoTributos) {
    $this->iCodigoRetencaoTributos = $iCodigoRetencaoTributos;
  }

  /**
   * Getter Codigo Retencao Tributos
   * @param integer
   */
  public function getCodigoRetencaoTributos () {
    return $this->iCodigoRetencaoTributos;
  }


  /**
   * Setter Codigo Natureza Organizacao
   * @param integer
   */
  public function setCodigoNaturezaOrganizacao ($iCodigoNaturezaOrganizacao) {
    $this->iCodigoNaturezaOrganizacao = $iCodigoNaturezaOrganizacao;
  }

  /**
   * Getter Codigo Natureza Organizacao
   * @param integer
   */
  public function getCodigoNaturezaOrganizacao () {
    return $this->iCodigoNaturezaOrganizacao;
  }


  /**
   * Setter Codigo Fluxo Clientela
   * @param integer
   */
  public function setCodigoFluxoClientela ($iCodigoFluxoClientela) {
    $this->iCodigoFluxoClientela = $iCodigoFluxoClientela;
  }

  /**
   * Getter Codigo Fluxo Clientela
   * @param integer
   */
  public function getCodigoFluxoClientela () {
    return $this->iCodigoFluxoClientela;
  }


  /**
  * Setter Codigo Turno Atendimento
  * @param integer
  */
  public function setCodigoTurnoAtendimento ($iCodigoTurnoAtendimento) {
   $this->iCodigoTurnoAtendimento = $iCodigoTurnoAtendimento;
  }

  /**
  * Getter Codigo Turno Atendimento
  * @param integer
  */
  public function getCodigoTurnoAtendimento () {
   return $this->iCodigoTurnoAtendimento;
  }


  /**
   * Setter Codigo Nive lHierarquia
   * @param integer
   */
  public function setCodigoNivelHierarquia ($iCodigoNivelHierarquia) {
    $this->iCodigoNivelHierarquia = $iCodigoNivelHierarquia;
  }

  /**
   * Getter Codigo Nive lHierarquia
   * @param integer
   */
  public function getCodigoNivelHierarquia () {
    return $this->iCodigoNivelHierarquia;
  }



  /**
   * Setter Orgao Expedicao Alvara
   * @param string
   */
  public function setOrgaoExpedicaoAlvara ($sOrgaoExpedicaoAlvara) {
    $this->sOrgaoExpedicaoAlvara = $sOrgaoExpedicaoAlvara;
  }

  /**
   * Getter Orgao Expedicao Alvara
   * @param string
   */
  public function getOrgaoExpedicaoAlvara () {
    return $this->sOrgaoExpedicaoAlvara;
  }

  /**
   * Setter IBGE
   * @param integer
   */
  public function setIBGE ($iIBGE) {
    $this->iIBGE = $iIBGE;
  }

  /**
   * Getter IBGE
   * @param integer
   */
  public function getIBGE() {
    return $this->iIBGE;
  }


/**
 * Setter Expedicao Alvara
 * @param DBDate
 */
public function setExpedicaoAlvara (DBDate $oDtExpedicaoAlvara) {
  $this->oDtExpedicaoAlvara = $oDtExpedicaoAlvara;
}

/**
 * Getter Expedicao Alvara
 * @param DBDate
 */
public function getExpedicaoAlvara () {
  return $this->oDtExpedicaoAlvara;
}


/**
 * Setter Responsavel
 * @param Object
 */
public function setResponsavel (CgmBase $oResponsavel) {
  $this->oResponsavel = $oResponsavel;
}

/**
 * Getter Responsavel
 * @param Object
 */
public function getResponsavel () {
  return $this->oResponsavel;
}

  public function salvar( $oDepartamento = null ) {

    $oDaoUnidade = new cl_unidades();

    $oDaoUnidade->sd02_i_codigo      = null;
    $oDaoUnidade->sd02_v_cnes        = $this->sCNES;
    $oDaoUnidade->sd02_v_num_alvara  = $this->sAlvara;
    $oDaoUnidade->sd02_i_tp_unid_id  = $this->iTipoUnidade;
    $oDaoUnidade->sd02_cnpjcpf       = $this->sCNPJCPF;
    $oDaoUnidade->sd02_c_siasus      = $this->sSIASUS;
    $oDaoUnidade->sd02_i_situacao    = $this->iSituacao;
    $oDaoUnidade->sd02_i_regiao      = $this->iRegiao;
    $oDaoUnidade->sd02_v_microreg    = $this->sMicroRegiao;
    $oDaoUnidade->sd02_v_ind_orgexp  = $this->sOrgaoExpedicaoAlvara;
    $oDaoUnidade->sd02_i_cidade      = $this->iIBGE;
    $oDaoUnidade->sd02_i_numcgm      = $this->oResponsavel->getCodigo();
    $oDaoUnidade->sd02_i_distrito    = $this->iCodigoDistrito;

    $oDaoUnidade->sd02_d_data_exped  = '';
    if ( !empty($this->oDtExpedicaoAlvara)) {
      $oDaoUnidade->sd02_d_data_exped = $this->oDtExpedicaoAlvara->getDate();
    }

    if ( !empty($this->iCodigoEsferaAdministrativa) &&
         !empty($this->iCodigoAtividade) &&
         !empty($this->iCodigoRetencaoTributos) &&
         !empty($this->iCodigoNaturezaOrganizacao) &&
         !empty($this->iCodigoFluxoClientela) &&
         !empty($this->iCodigoNivelHierarquia) &&
         !empty($this->iCodigoTurnoAtendimento) ) {

      $oDaoUnidade->sd02_i_codnivhier  = $this->iCodigoNivelHierarquia;
      $oDaoUnidade->sd02_i_cod_esfadm  = $this->iCodigoEsferaAdministrativa;
      $oDaoUnidade->sd02_i_cod_ativ    = $this->iCodigoAtividade;
      $oDaoUnidade->sd02_i_reten_trib  = $this->iCodigoRetencaoTributos;
      $oDaoUnidade->sd02_i_cod_natorg  = $this->iCodigoNaturezaOrganizacao;
      $oDaoUnidade->sd02_i_cod_client  = $this->iCodigoFluxoClientela;
      $oDaoUnidade->sd02_i_cod_turnat  = $this->iCodigoTurnoAtendimento;
    }

    if ( is_null($this->oDepartamento) ) {

      $oDaoUnidade->incluir($oDepartamento->getCodigo());
      $this->oDepartamento = $oDepartamento;
    } else {

      $oDaoUnidade->sd02_i_codigo = $this->oDepartamento->getCodigo();
      $oDaoUnidade->alterar($this->oDepartamento->getCodigo());
    }

    if ($oDaoUnidade->erro_status == "0") {
      throw new DBException("Erro ao salvar unidade");
    }

    return true;
  }


  /**
   * Setter codigo distrito
   * @param integer
   */
  public function setDistrito($iCodigoDistrito) {
    $this->iCodigoDistrito = $iCodigoDistrito;
  }

  /**
   * Getter codigo distrito
   * @param integer
   */
  public function getDistrito() {
    return $this->iCodigoDistrito;
  }

}