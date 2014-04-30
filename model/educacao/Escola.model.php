<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe para representacao dos dados da escola
 * @author Iuri Guntchnigg
 * @package Educacao
 * @version $Revision: 1.13 $
 *
 */
require_once("IEscola.interface.php");
class Escola implements IEscola {

  protected $iCodigoEscola;

  /**
   * nome da Escola
   * @var string
   */
  protected $sNomeEscola;

  /**
   * Endereco da escola
   * @var string
   */
  protected $sEndereco;

  /**
   * Numero do endereco
   * @var integer
   */
  protected $iNumeroEndereco;

  /**
   * Complemento do endereco
   * @var string
   */
  protected $sComplementoEndereco;

  /**
   * Bairro da escola
   * @var string
   */
  protected $sBairro;

  /**
   * Municipio da escola
   * @var string
   */
  protected $sMunicipio;

  /**
   * UF da escola
   * @var string
   */
  protected $sUf;

  /**
   * Estado da escola
   * @var string
   */
  protected $sEstado;

  /**
   * Departamento da escola
   * @var DBDepartamento
   */
  protected $oDepartamento;
  /**
   * CEP da escola
   * @var string
   */
  protected $sCep;

  /**
   * Caminho do logo do municipio
   * @var string
   */
  protected $sLogo;

  /**
   * Caminho do logo da escola
   * @var string
   */
  protected $sLogoEscola;

  /**
   * Nome do estado escrito por extenso.
   * Ex.: ESTADO DO DISTRITO FEDERAL
   * @var string
   */
  protected $sNomeEstadoExtenso;

  /**
   * Array com os diretores da escola
   * @var array
   */
  protected $aDiretores = array();

  /**
   * Identifica que a escola oferece atendimento especial exclusivo
   * @var integer
   */
  CONST OFERECE_EXCLUSIVAMENTE_ATENDIMENTO_ESPECIAL = 1;

  /**
   * Identifica que a escola oferece atendimento especial
   * @var integer
   */
  CONST OFERECE_ATENDIMENTO_ESPECIAL = 2;

  /**
   * Identifica que a escola nao oferece atendimento especial
   * @var integer
   */
  CONST NAO_OFERECE_ATENDIMENTO_ESPECIAL = 3;

  /**
   * Identifica que a escola oferece atividade complementar exclusiva
   * @var integer
   */
  CONST OFERECE_EXCLUSIVAMENTE_ATIVIDADE_COMPLEMENTAR = 1;

  /**
   * Identifica que a escola oferece atividade complementar
   * @var integer
   */
  CONST OFERECE_ATIVIDADE_COMPLEMENTAR = 2;

  /**
   * Identifica que a escola nao oferece atividade complementar
   * @var integer
   */
  CONST NAO_OFERECE_ATIVIDADE_COMPLEMENTAR = 3;

  /**
   * Array com os telefones da escola
   * @var array
   */
  protected $aTelefonesEscola = array();

  /**
   * Email da escola
   * @var string
   */
  protected $sEmail;

  /**
   * URL da escola
   * @var string
   */
  protected $sUrl;

  /**
   * Atos legais da escola
   * @var array AtoLegal
   */
  protected $aAtosLegais = array();

  /**
   * Construtor da classe. Recebe o codigo da escola como parametro. Caso seja diferente de null, seta o valor dos
   * atributos
   * @param integer $iCodigoEscola
   */
  function __construct($iCodigoEscola = null) {

    if (!empty($iCodigoEscola)) {

      $oDaoEscola = db_utils::getDao("escola");
      $sSqlEscola = $oDaoEscola->sql_query_estado($iCodigoEscola);
      $rsEscola   = $oDaoEscola->sql_record($sSqlEscola);

      if ($oDaoEscola->numrows > 0) {

        $oDadosEscola               = db_utils::fieldsMemory($rsEscola, 0);
        $this->iCodigoEscola        = $oDadosEscola->ed18_i_codigo;
        $this->sNomeEscola          = $oDadosEscola->ed18_c_nome;
        $this->iNumeroEndereco      = $oDadosEscola->ed18_i_numero;
        $this->sBairro              = $oDadosEscola->j13_descr;
        $this->sComplementoEndereco = $oDadosEscola->ed18_c_compl;
        $this->sEndereco            = $oDadosEscola->j14_nome;
        $this->sEstado              = $oDadosEscola->ed260_c_nome;
        $this->sMunicipio           = $oDadosEscola->ed261_c_nome;
        $this->sUf                  = $oDadosEscola->ed260_c_sigla;
        $this->sCep                 = $oDadosEscola->ed18_c_cep;
        $this->sLogo                = $oDadosEscola->logo;
        $this->sLogoEscola          = $oDadosEscola->ed18_c_logo;
        $this->sNomeEstadoExtenso   = $oDadosEscola->db12_extenso;
        $this->sEmail               = $oDadosEscola->ed18_c_email;
        $this->sUrl                 = $oDadosEscola->url;
        $this->oDepartamento        = DBDepartamentoRepository::getDBDepartamentoByCodigo($oDadosEscola->coddepto);
      }
    }
  }

  /**
   * Retorna o codigo da escola
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoEscola;
  }

  /**
   * Retorna o nome da Escola;
   * @return string
   */
  public function getNome() {
    return $this->sNomeEscola;
  }

  /**
   * Define o nome da Escola
   * @param string $sNome nome da escola
   *
   */
  public function setNome($sNome) {
    $this->sNomeEscola = $sNome;
  }

  /**
   * Verifica se a escola Oferece Atividade especial.
   * A validada é feita pelo cadastro da infra estrutura da escola
   * o retorno é um dos seguintes tipos:
   * Escola::OFERECE_EXCLUSIVAMENTE_ATIVIDADE_COMPLEMENTAR = exclusivamente
   * Escola::NAO_OFERECE_ATIVIDADE_COMPLEMENTAR            = não Oferece Exclusivamente
   * Escola::OFERECE_ATIVIDADE_COMPLEMENTAR                = não oferece
   * @return integer
   */
  public function ofereceAtividadeComplementar() {

    $iAtividadeComplementar                = 3;
    $oDaoDadosCenso                        = db_utils::getDao("escoladadoscenso");
    $iCodigoPerguntaAtividadeComplementar  = 3000021;
    $sWhere                                = "db104_avaliacaopergunta = {$iCodigoPerguntaAtividadeComplementar}";
    $sWhere                               .= " and ed308_escola = {$this->getCodigo()}";
    $sSqlPergunta                          = $oDaoDadosCenso->sql_query_resposta(null,
                                                                                "db104_sequencial as resposta",
                                                                                 null,
                                                                                 $sWhere
                                                                               );

    $rsPergunta = $oDaoDadosCenso->sql_record($sSqlPergunta);
    if ($oDaoDadosCenso->numrows > 0) {

      $iCodigoResposta = db_utils::fieldsMemory($rsPergunta, 0)->resposta;
      switch ($iCodigoResposta) {

        case 3000107:

          $iAtividadeComplementar = Escola::OFERECE_EXCLUSIVAMENTE_ATIVIDADE_COMPLEMENTAR;
          break;

        case 3000106:

          $iAtividadeComplementar = Escola::NAO_OFERECE_ATIVIDADE_COMPLEMENTAR;
          break;

        case 3000105:

          $iAtividadeComplementar = Escola::OFERECE_ATIVIDADE_COMPLEMENTAR;
          break;
      }
    }
    return $iAtividadeComplementar;
  }

/**
   * Verifica se a escola Oferece educacao Especializada
   * A validada é feita pelo cadastro da infra estrutura da escola
   * o retorno é um dos seguintes tipos:
   * Escola::OFERECE_EXCLUSIVAMENTE_ATENDIMENTO_ESPECIAL = exclusivamente
   * Escola::OFERECE_ATENDIMENTO_ESPECIAL                = não Oferece Exclusivamente
   * Escola::NAO_OFERECE_ATENDIMENTO_ESPECIAL            = não oferece
   * @return integer
   */
  public function ofereceEducacaoEspecializada() {

    $iAtividadeEspecial               = 3;
    $oDaoDadosCenso                   = db_utils::getDao("escoladadoscenso");
    $iCodigoPerguntaAtividadeEspecial = 3000022;
    $sWhere                           = "db104_avaliacaopergunta = {$iCodigoPerguntaAtividadeEspecial}";
    $sWhere                          .= " and ed308_escola = {$this->getCodigo()}";
    $sSqlPergunta                     = $oDaoDadosCenso->sql_query_resposta(null,
                                                                            "db104_sequencial as resposta",
                                                                             null,
                                                                             $sWhere
                                                                            );
    $rsPergunta = $oDaoDadosCenso->sql_record($sSqlPergunta);
    if ($oDaoDadosCenso->numrows > 0) {

      $iCodigoResposta = db_utils::fieldsMemory($rsPergunta, 0)->resposta;
      switch ($iCodigoResposta) {

        case 3000108:

          $iAtividadeEspecial = Escola::OFERECE_ATENDIMENTO_ESPECIAL;
          break;

        case 3000109:

          $iAtividadeEspecial = Escola::OFERECE_EXCLUSIVAMENTE_ATENDIMENTO_ESPECIAL;
          break;

        case 3000110:

          $iAtividadeEspecial = Escola::NAO_OFERECE_ATENDIMENTO_ESPECIAL;
          break;
      }
    }
    return $iAtividadeEspecial;
  }

  /**
   * Retorna o endereco da escola
   * @return string
   */
  public function getEndereco() {
    return $this->sEndereco;
  }

  /**
   * Retorna o numero do endereco da escola
   * @return integer
   */
  public function getNumeroEndereco() {
    return $this->iNumeroEndereco;
  }

  /**
   * Retorna o complemento do endereco
   * @return string
   */
  public function getComplementoEndereco() {
    return $this->sComplementoEndereco;
  }

  /**
   * Retorna o bairro da escola
   * @return string
   */
  public function getBairro() {
    return $this->sBairro;
  }

  /**
   * Retorna o municipio da escola
   * @return string
   */
  public function getMunicipio() {
    return $this->sMunicipio;
  }

  /**
   * Retorna a UF da escola
   * @return string
   */
  public function getUf() {
    return $this->sUf;
  }

  /**
   * Retorna o estado da escola
   * @return string
   */
  public function getEstado() {
    return $this->sEstado;
  }

  /**
   * Retorna uma instância do departamento
   * @return DBDepartamento
   */
  public function getDepartamento() {
    return $this->oDepartamento;
  }

  /**
   * Retorna o CEP da escola
   * @return string
   */
  public function getCep() {
    return $this->sCep;
  }

  /**
   * Retorna o caminho do logo do municipio
   * @return string
   */
  public function getLogo() {
    return $this->sLogo;
  }

  /**
   * Retorna o caminho do logo da escola
   * @return string
   */
  public function getLogoEscola() {
    return $this->sLogoEscola;
  }

  /**
   * Busca o nome do estado por extenso
   * @return string
   */
  public function getNomeEstadoExtenso() {

    return $this->sNomeEstadoExtenso;
  }

  /**
   * Busca as legendas cadastradas para a escola
   * @return array
   */
  public function getLegendas() {

    $oDaoParecerLegenda = db_utils::getDao('parecerlegenda');

    $sCampos = "ed91_sigla, ed91_c_descr";
    $sWhere  = "     ed91_i_escola = {$this->getCodigo()}";
    $sWhere .= " and ed91_sigla is not null ";

    $sSqlParecerLegenda  = $oDaoParecerLegenda->sql_query_file(null, $sCampos, null, $sWhere);
    $rsParecerLegenda    = $oDaoParecerLegenda->sql_record($sSqlParecerLegenda);

    return db_utils::getCollectionByRecord($rsParecerLegenda);
  }

  /**
   * Retorna os diretores da escola
   * @return array
   */
  public function getDiretor($iDiretor = null) {

    $oDaoEscolaDiretor = db_utils::getdao('escoladiretor');
    $sCamposDiretor    = "ed254_i_codigo, ";
    $sCamposDiretor   .= "case when ed20_i_tiposervidor = 1 then ";
    $sCamposDiretor   .= "          cgmrh.z01_nome ";
    $sCamposDiretor   .= "     else cgmcgm.z01_nome ";
    $sCamposDiretor   .= "end as nome, ed83_c_descr, ed05_c_numero";
    $sWhereDiretor     = " ed254_i_escola = {$this->iCodigoEscola} AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";

    if (!empty($iDiretor)) {
      $sWhereDiretor .= " AND ed254_i_codigo = {$iDiretor}";
    }

    $sSqlDiretor       = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);
    $rsDiretor         = $oDaoEscolaDiretor->sql_record($sSqlDiretor);
    $iLinhasDiretor    = $oDaoEscolaDiretor->numrows;

    if ($iLinhasDiretor > 0) {

      for ($iContador = 0; $iContador < $iLinhasDiretor; $iContador++) {

        $oDiretor            = new stdClass();
        $oDadosDiretor       = db_utils::fieldsMemory($rsDiretor, $iContador);
        $oDiretor->iCodigo   = $oDadosDiretor->ed254_i_codigo;
        $oDiretor->sNome     = $oDadosDiretor->nome;
        $oDiretor->sAtoLegal = $oDadosDiretor->ed83_c_descr;
        $oDiretor->iNumero   = $oDadosDiretor->ed05_c_numero;
        $this->aDiretores[]  = $oDiretor;
      }
    }

    return $this->aDiretores;
  }

  /**
   * Retorna uma colecao de telefones cadastrados para a escola
   * @return array
   */
  public function getTelefones() {

    $oDaoTelefoneEscola     = new cl_telefoneescola();
    $sCamposTelefoneEscola  = "escola.*, telefoneescola.*, tipotelefone.*";
    $sWhereTelefoneEscola   = "ed26_i_escola = {$this->getCodigo()}";
    $sSqlTelefoneEscola     = $oDaoTelefoneEscola->sql_query(null, $sCamposTelefoneEscola, null, $sWhereTelefoneEscola);
    $rsTelefoneEscola       = $oDaoTelefoneEscola->sql_record($sSqlTelefoneEscola);
    $iTotalTelefoneEscola   = $oDaoTelefoneEscola->numrows;

    if ($iTotalTelefoneEscola > 0) {

      for ($iContador = 0; $iContador < $iTotalTelefoneEscola; $iContador++) {

        $oDadosTelefoneEscola           = db_utils::fieldsMemory($rsTelefoneEscola, $iContador);
        $oTelefoneEscola                = new stdClass();
        $oTelefoneEscola->iDDD          = $oDadosTelefoneEscola->ed26_i_ddd;
        $oTelefoneEscola->iNumero       = $oDadosTelefoneEscola->ed26_i_numero;
        $oTelefoneEscola->iRamal        = $oDadosTelefoneEscola->ed26_i_ramal;
        $oTelefoneEscola->sObservacao   = $oDadosTelefoneEscola->ed26_t_obs;
        $oTelefoneEscola->iTipoTelefone = $oDadosTelefoneEscola->ed26_i_tipotelefone;
        $oTelefoneEscola->sTipoTelefone = $oDadosTelefoneEscola->ed13_c_descr;

        $this->aTelefonesEscola[] = $oTelefoneEscola;
      }
    }

    return $this->aTelefonesEscola;
  }

  /**
   * Retorna o email da escola
   * @return string
   */
  public function getEmail() {
    return $this->sEmail;
  }

  /**
   * Seta um email para a escola
   * @param string $sEmail
   */
  public function setEmail($sEmail) {
    $this->sEmail = $sEmail;
  }

  /**
   * Retorna a URL da escola
   * @return string
   */
  public function getUrl() {
    return $this->sUrl;
  }

  /**
   * Seta a URL da escola
   * @param string $sUrl
   */
  public function setUrl($sUrl) {
    $this->sUrl = $sUrl;
  }

  /**
   * Retorna um array de objetos dos atos legais da escola que não estão vinculados a cursos
   * @return array $aAtosLegais
   */
  public function getAtosLegais() {

    if (count($this->aAtosLegais) == 0) {
      $this->aAtosLegais = AtoLegalRepository::getAtosLegaisByEscola($this);
    }
    
    return $this->aAtosLegais;
  }
}

?>