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
 * Classe modelo para aluno
 * @package   Educacao
 * @author    Robson Inacio - robson@dbseller.com.br
 * @version   $Revision: 1.40 $
 */
class Aluno {

  /**
   * codigo do aluno
   * @var integer
   */
	private $iCodigoAluno = null;

	/**
	 * Nome do aluno
	 * @var string
	 */
	private $sNome = null;
	/**
	 * data de nascimento do aluno
	 * @var string
	 */
	private $sDataNascimento = null;

	/**
	 * nome do pai do aluno
	 * @var string
	 */
	private $sNomePai = null;

	/**
	 * Nome da mae do aluno
	 * @var string
	 */
	private $sNomeMae = null;

	/**
	 * Nome do responsavel legal
	 * @var string
	 */
	private $sNomeResponsavelLegal  = null;

	/**
	 * Retorna o ID da foto
	 * @var OID
	 */
	private $iOidFoto = null;

	/**
	 * Carteira de identifcacao do aluno
	 * @return CarteiraIdentificacao
	 */
	private $oCarteiraIdentificacao = null;

	/**
	 * Retorna o celular do responsavel pelo aluno
	 * var sting
	 */
	private $sCelularResponsavel    = null;

	/**
	 * Retorna o email do responsavel pelo aluno
	 * @var string
	 */
	private $sEmailResponsavel    = null;

	/**
   * Array com as matriculas do aluno
	 */
	private $aMatricula = array();

	/**
	 * Array com as progressoes parciais do Aluno
	 * @var ProgressaoParcialAluno[]
	 */
	private $aProgressaoParcial = array();

	/**
	 * Sexo do aluno
	 * @var char(1)
	 */
	private $sSexo;

	/**
	 * Raca do aluno
	 * @var string
	 */
	protected $sRaca;

	/**
	 * Informa se o aluno estudou pre-escola na rede
	 * @var boolean
	 */
	protected $lPreEscolaNaRede = false;

	/**
	 * Array com as necessidades especiais do aluno
	 * @var array
	 */
	protected $aNecessidadesEspeciais = array();

	/**
	 * Array com os recursos necessarios para avaliacao do INEP. Recursos existentes apenas quando o aluno possui alguma
	 * necessidade especial
	 * @var array
	 */
	protected $aRecursosAvaliacaoInep = array();

	/**
	 * Codigo do Inep
	 * @var string
	 */
	protected $iCodigoInep;

	/**
	 * Codigo da nacionalidade
	 * @var integer
	 */
	protected $iNacionalidade;

	/**
	 * Nacionalidade Brasileira
	 * @var integer
	 */
	const NACIONALIDADE_BRASILEIRA = 1;

	/**
	 * Nacionalidade Brasileira no exterior , ou naturalizado
	 * @var integer
	 */
	const NACIONALIDADE_NATURALIZADA = 2;

	/**
	 * Nacionalidade estrangeira
	 * @var integer
	 */
	const NACIONALIDADE_ESTRANGEIRA = 3;

	/**
	 * naturalidade do aluno
	 * @var CensoMunicipio
	 */
	protected $oNaturalidade;

	/**
	 * Municipio de Residencia
	 * @var CensoMunicipio
	 */
	protected $oMunicipioResidencia;

	/**
	 * Zona de Residencia
	 * @var string
	 */
	protected $sZona;

	/**
	 * Bairro de residencia
	 * @var string
	 */
	protected $sBairro;

	/**
	 * Endereco de Residencia
	 * @var integer
	 */
	protected $sEnderecoResidencia;

	/**
	 * numero do Endereco de Residencia
	 * @var integer
	 */
	protected $sNumeroResidencia;

	/**
	 * Complemento do endereco
	 * @var String
	 */
	protected $sComplemento;

	/**
	 * Codigo do Municipio da Naturalidade
	 * @var integer
	 */
	protected $iCodigoMunicipio;

	/**
	 * Codigo do Municipio da Naturalidade
	 * @var integer
	 */
	protected $iCodigoMunicipioNaturalidade;

	/**
	 * Codigo do pais
	 * @var integer
	 */
	protected $iCodigoPais = null;

	/**
	 * País da naturalidade
	 * @var Pais
	 */
	protected $oPaisNaturalidade;

	/**
	 * Cep de Residencia do Aluno
	 * @var string
	 */
	protected $sCepResidencia;

	/**
	 * Estado Civil o aluno
	 * @var integer
	 */
	protected $iEstadoCivil;

	/**
	 * Numero de telefone convencional
	 * @var string
	 */
	protected $sNumeroTelefone;

	/**
	 * Numero do telefone celular
	 * @var string
	 */
	protected $sNumeroCelular;

  /**
   * Numero de identidade do Aluno
   * @var String
   */
  protected $sIdentidade;

  /**
   * Situação
   * @var SituacaoAluno
   */
  protected $oSituacaoAluno;

  /**
   * Email do aluno
   * @var String
   */
  protected $sEmailAluno;

	/**
   * Tipo Sanguineo do Aluno
   * @var integer
   */
  protected $iTiposanguineo;

  /**
   * @var Escola|EscolaProcedencia
   */
  protected $oEscolaProcedencia;

  /**
   * nome da foto salva ao aluno
   * @var string
   */
  protected $sFoto;

	/**
	 * Metodo construtor para classe aluno
	 *
	 * @param  integer $iCodigoAluno
	 * @return boolean
	 */
	public function __construct($iCodigoAluno = null) {

		if ($iCodigoAluno != null) {

      $oDaoAluno = db_utils::getDao('aluno');
      $sSqlAluno = $oDaoAluno->sql_query_file($iCodigoAluno);
      $rsAluno   = $oDaoAluno->sql_record($sSqlAluno);
      if ($rsAluno && $oDaoAluno->numrows > 0 ) {

        $oAluno = db_utils::fieldsMemory($rsAluno, 0);
        $this->iCodigoAluno                 = $oAluno->ed47_i_codigo;
			  $this->sNome                        = trim($oAluno->ed47_v_nome);
			  $this->sDataNascimento              = $oAluno->ed47_d_nasc;
			  $this->sNomePai                     = $oAluno->ed47_v_pai;
			  $this->sNomeMae                     = $oAluno->ed47_v_mae;
		    $this->sNomeResponsavelLegal        = $oAluno->ed47_c_nomeresp;
		    $this->iOidFoto                     = $oAluno->ed47_o_oid;
		    $this->sEmailResponsavel            = $oAluno->ed47_c_emailresp;
		    $this->sCelularResponsavel          = $oAluno->ed47_celularresponsavel;
		    $this->sSexo                        = $oAluno->ed47_v_sexo;
		    $this->sRaca                        = $oAluno->ed47_c_raca;
		    $this->iCodigoInep                  = $oAluno->ed47_c_codigoinep;
		    $this->iNacionalidade               = $oAluno->ed47_i_nacion;
		    $this->sBairro                      = $oAluno->ed47_v_bairro;
		    $this->sComplemento                 = $oAluno->ed47_v_compl;
		    $this->sZona                        = $oAluno->ed47_c_zona;
		    $this->sEnderecoResidencia          = $oAluno->ed47_v_ender;
		    $this->sNumeroResidencia            = $oAluno->ed47_c_numero;
		    $this->iCodigoMunicipioNaturalidade = $oAluno->ed47_i_censomunicnat;
		    $this->iCodigoMunicipio             = $oAluno->ed47_i_censomunicend;
		    $this->iCodigoPais                  = $oAluno->ed47_i_pais;
		    $this->sCepResidencia               = $oAluno->ed47_v_cep;
		    $this->iEstadoCivil                 = $oAluno->ed47_i_estciv;
		    $this->sNumeroTelefone              = $oAluno->ed47_v_telef;
		    $this->sNumeroCelular               = $oAluno->ed47_v_telcel;
        $this->sIdentidade                  = $oAluno->ed47_v_ident;
        $this->sEmailAluno                  = $oAluno->ed47_v_email;
        $this->iTiposanguineo               = $oAluno->ed47_tiposanguineo;
        $this->iTiposanguineo               = $oAluno->ed47_tiposanguineo;
        $this->sFoto                        = trim($oAluno->ed47_c_foto);
        unset($oAluno);
      }else{
        return false;
      }
    }
    return true;
	}

	 /**
   * @return integer codigo do aluno
   */
  public function getCodigoAluno() {
    return $this->iCodigoAluno;
  }

  /**
   * @return string codigo no Inep do aluno
   */
  public function getCodigoInep() {
    return $this->iCodigoInep;
  }

  /**
   * Seta o código INEP do aluno
   * @param integer $iCodigoInep
   */
  public function setCodigoInep($iCodigoInep) {
    $this->iCodigoInep = $iCodigoInep;
  }

  /**
   * @return CarteiraIdentificacao
   */
  public function getCarteiraIdentificacao() {

    if (empty($this->oCarteiraIdentificacao) && $this->getCodigoAluno() != "") {

      $oDaoCarteiraIdentificacao = db_utils::getDao("loteimpressaocartaoidentificacaoaluno");
      $sWhere                    = " ed306_aluno = {$this->getCodigoAluno()} ";
      $sSqlCarteiraIdentificacao = $oDaoCarteiraIdentificacao->sql_query(null,"ed306_sequencial",null,$sWhere);
      $rsCarteiraIdentificacao   = $oDaoCarteiraIdentificacao->sql_record($sSqlCarteiraIdentificacao);
      if ($rsCarteiraIdentificacao && $oDaoCarteiraIdentificacao->numrows > 0) {

        $oCarteiraIdentificacao       = db_utils::fieldsMemory($rsCarteiraIdentificacao, 0);
        $this->oCarteiraIdentificacao = new CarteiraIdentificacao($this);
      }
    }
    return $this->oCarteiraIdentificacao;
  }

  /**
   * @return string data de nascimento do aluno
   */
  public function getDataNascimento() {
    return $this->sDataNascimento;
  }

  /**
   * @return string nome do aluno
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * @return string nome da mae
   */
  public function getNomeMae() {
    return $this->sNomeMae;
  }

  /**
   * @return string nome do pai
   */
  public function getNomePai() {
    return $this->sNomePai;
  }

  /**
   * @return string nome do responsavel legal do aluno
   */
  public function getNomeResponsavelLegal() {
    return $this->sNomeResponsavelLegal;
  }

  /**
   * Metodo getFoto busca foto do aluno do banco de dados e retorna string com o caminho
   *
   * @return string com o caminho da foto gravada em disco
   */
  public function getFoto() {

    $sExtencao = 'jpg';
    if ( !empty($this->sFoto) ) {
      $sExtencao = end((explode('.', $this->sFoto)));
    }
  	$sPathFoto  = "tmp/foto_aluno_{$this->iCodigoAluno}.{$sExtencao}";
 		$lGerouFoto = DBLargeObject::leitura($this->iOidFoto, $sPathFoto);

  	if ($this->iOidFoto != 0 && $lGerouFoto) {
  		return $sPathFoto;
  	}
 	  return false;
  }

  /**
   * Retorna o HistoricoEscolar do Aluno
   * Retorna os dados do Historico do aluno
   *
   * @param $iCurso
   * @return HistoricoAluno
   */
  public function getHistoricoEscolar($iCurso) {

    $oDaoHistoricoEscolar = db_utils::getDao("historico");
    $sSqlHistorico        = $oDaoHistoricoEscolar->sql_query_file(null,
                                                                  "ed61_i_codigo",
                                                                  null,
                                                                  "ed61_i_aluno    = {$this->getCodigoAluno()}
                                                                  and ed61_i_curso = {$iCurso}"
                                                                 );
    $rsHistorico      = $oDaoHistoricoEscolar->sql_record($sSqlHistorico);
    $iCodigoHistorico = 0;
    if ($oDaoHistoricoEscolar->numrows == 1) {
      $iCodigoHistorico = db_utils::fieldsMemory($rsHistorico, 0)->ed61_i_codigo;
    }
    $oHistoricoEscolar = new HistoricoAluno($iCodigoHistorico);
    $oHistoricoEscolar->setAluno($this);
    return $oHistoricoEscolar;
  }

  /**
   * Retorna uma colecao com as matriculas do aluno
   * @return Matricula
   */
  public function getMatriculas() {

    if (count($this->aMatricula) == 0 && $this->getCodigoAluno()) {

      $oDaoMatricula = db_utils::getDao('matricula');
      $sWhere        = " ed60_i_aluno = {$this->getCodigoAluno()}";
      $sSqlMatricula = $oDaoMatricula->sql_query_file(null, 'ed60_i_codigo, ed60_d_datamatricula', 'ed60_matricula',
                                                      $sWhere,'ed60_d_datamatricula');
      $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
      $iTotalLinhas  = $oDaoMatricula->numrows;

      if ($iTotalLinhas > 0) {

        for ($i = 0; $i < $iTotalLinhas; $i++) {

          $iMatricula                    = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;
          $this->aMatricula[$iMatricula] = new Matricula($iMatricula);
        }
      }
    }
    return $this->aMatricula;
  }

  /**
   * Retorna a matricula para a turma selecionada
   *
   * @param Turma $oTurma
   * @return Matricula
   */
  public function getMatriculaByTurma(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = "     ed60_i_aluno = {$this->getCodigoAluno()} ";
    $sWhere       .= " and ed60_i_turma = {$oTurma->getCodigo()} ";

    $sSqlMatricula = $oDaoMatricula->sql_query_file(null, 'ed60_i_codigo', null, $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

    if ( $rsMatricula && $oDaoMatricula->numrows > 0) {

      $iMatricula = db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo;
      if ( count($this->aMatricula) == 0 && !empty($this->iCodigoAluno) ) {
        $this->aMatricula[$iMatricula] = MatriculaRepository::getMatriculaByCodigo($iMatricula);
      }
      return $this->aMatricula[$iMatricula];
    }
    return null;
  }

  /**
   * Retorna o telefone do responsavel pelo aluno
   * junto com o DDD.
   * @return string
   */
  public function getCelularResponsavel() {
    return $this->sCelularResponsavel;
  }

  /**
   * Retorna o email do responsavel pelo aluno
   * @return string
   */
  public function getEmailResponsavel() {
    return $this->sEmailResponsavel;
  }

  /**
   * Retorna todas as progressoes parciais do aluno
   * @param bool $lOrdenarAno
   * @return ProgressaoParcialAluno[] Progressoes parciais do aluno
   */
  public function getProgressaoParcial( $lOrdenarAno = false ) {

    if (count($this->aProgressaoParcial) == 0 && !empty($this->iCodigoAluno)) {

      $oDaoProgressaoParcial = new cl_progressaoparcialaluno();
      $sWhere                = " ed114_aluno = {$this->getCodigoAluno()}";
      $sOrdenacao            = $lOrdenarAno ? "ed114_ano desc" : null;
      $sSqlProgressaoParcial = $oDaoProgressaoParcial->sql_query_file(null, "ed114_sequencial", $sOrdenacao, $sWhere);
      $rsProgressaoParcial   = $oDaoProgressaoParcial->sql_record($sSqlProgressaoParcial);
      $iRegistros            = $oDaoProgressaoParcial->numrows;

      if ($oDaoProgressaoParcial->numrows > 0) {

        for ($i = 0; $i < $iRegistros; $i++) {

          $iCodigoProgressao          = db_utils::fieldsMemory($rsProgressaoParcial, $i)->ed114_sequencial;
          $this->aProgressaoParcial[] = new ProgressaoParcialAluno($iCodigoProgressao);
        }
      }
    }

    return $this->aProgressaoParcial;
  }

  /**
   * Retorna o sexo do Aluno
   *    F para Feminino
   *    M para Masculino
   * @return string
   */
  public function getSexo() {

    return $this->sSexo;
  }

  /**
   * Define o sexo do aluno
   * @param string $sSexo
   */
  public function setSexo($sSexo) {

    $this->sSexo = $sSexo;
  }

  /**
   * Retorna a idade aproximada do aluno
   * @return integer
   */
  public function getIdade() {

    if( $this->getDataNascimento() == "") {
      return null;
    }

    $sData = date("Y-m-d", db_getsession("DB_datausu"));
    $iAno  = $this->getIdadeNaData($sData)->anos;
    return $iAno;

  }

  /**
   * Retorna um StdClass com os dados  da idade do Aluno
   * AS propriedade retornas sao : ano, meses e dias
   * @param string $dtBase data base para calculo da idade
   * @return stdClass objeto como s dados da idade
   */
  public function getIdadeNaData($dtBase) {

    $oIdade        = new stdClass();
    $oIdade->anos  = 0;
    $oIdade->meses = 0;
    $oIdade->dias  = 0;

    if ($this->getDataNascimento() == "") {
      return $oIdade;
    }
    $iDataNascimento = $this->getDataNascimento();
    $sSqlAnoMesDia   = "SELECT fc_idade_anomesdia('{$iDataNascimento}', '{$dtBase}', false) as dias;";
    $rsAnoMesDia     = db_query($sSqlAnoMesDia);
    if ($rsAnoMesDia && pg_num_rows($rsAnoMesDia) > 0) {

      $aDadosIdade   = explode(',', db_utils::fieldsMemory($rsAnoMesDia, 0)->dias);
      $oIdade->anos  = trim($aDadosIdade[0]);
      $oIdade->meses = trim($aDadosIdade[1]);
      $oIdade->dias  = trim($aDadosIdade[2]);

    }

    return $oIdade;
  }

  /**
   * Retorna a raca do aluno
   * @return string
   */
  public function getRaca() {
    return $this->sRaca;
  }

  /**
   * Retorna a nacionalidade do aluno
   * As Nacionalidades que retornam, são as definadas pelo INEP.
   * Retorna :
   *  Aluno::NACIONALIDADE_ESTRANGEIRA
   *  Aluno::NACIONALIDADE_NATURALIZADA
   *  Aluno::NACIONALIDADE_BRASILEIRA
   *
   * @return integer
   */
  public function getNacionalidade() {
    return $this->iNacionalidade;
  }

  /**
   * Retorna a naturalidade do Aluno
   * @return CensoMunicipio
   */
  public function getNaturalidade() {

    $this->oNaturalidade = new CensoMunicipio(null);

    if (!empty($this->iCodigoMunicipioNaturalidade)) {
      $this->oNaturalidade  = CensoMunicipioRepository::getMunicipioByCodigo($this->iCodigoMunicipioNaturalidade);
    }

    return $this->oNaturalidade;
  }

  /**
   * Retorna o endereço de residencia
   * @return string
   */
  public function getEnderecoResidencia() {
    return $this->sEnderecoResidencia;
  }

  /**
   * Retorna o Numero da Residencia do aluno
   * @return number
   */
  public function getNumeroResidencia() {
    return $this->sNumeroResidencia;
  }

  /**
   * Retorna o bairro de Residencia
   * @return string
   */
  public function getBairroResidencia() {
    return $this->sBairro;
  }

  /**
   * Complemento do endereço
   * @return string
   */
  public function getComplementoResidencia() {
    return $this->sComplemento;
  }

  /**
   * Zona de residencia do aluno
   * @return string
   */
  public function getZonaResidencia() {
    return $this->sZona;
  }

  /**
   * Retorna o Municipio de Residencia
   * @return CensoMunicipio
   */
  public function getMunicipioResidencia() {

    if (empty($this->oMunicipioResidencia) && !empty($this->iCodigoMunicipio)) {
      $this->oMunicipioResidencia  = CensoMunicipioRepository::getMunicipioByCodigo($this->iCodigoMunicipio);
    }
    return $this->oMunicipioResidencia;
  }

  /**
   * Retorna o País de naturalidade
   * @return Pais
   */
  public function getPaisNaturalidade() {

    if (empty($this->oPaisNaturalidade) && !empty($this->iCodigoPais)) {
      $this->oPaisNaturalidade = PaisRepository::getPaisByCodigo($this->iCodigoPais);
    }
    return $this->oPaisNaturalidade;
  }

  /**
   * Retorna o CEP de Residencia
   * @return string
   */
  public function getCepResidencia() {
    return $this->sCepResidencia;
  }

  /**
   * Retorna o estado civil doaluno
   * @return number
   */
  public function getEstadoCivil() {
    return $this->iEstadoCivil;
  }

  /**
   * Retorna o numero de telefone do aluno
   * @return number
   */
  public function getNumeroTelefone() {
    return $this->sNumeroTelefone;
  }

  /**
   * Retorna  o numero do celular do aluno
   * @return string
   */
  public function getNumeroCelular() {
    return $this->sNumeroCelular;
  }

  /**
   * Verifica se o aluno cursou  pre escola na rede municipal.
   * @return boolean
   */
  public function temPreEscolaNaRede() {

    $oDaoMatricula = db_utils::getDao("matricula");
    $sSqlMatricula = $oDaoMatricula->sql_query_matriculaserie(null,
                                                              "ed221_i_serie",
                                                              null,
                                                              "ed60_i_aluno = {$this->getCodigoAluno()}"
                                                              );

    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iLinhas       = $oDaoMatricula->numrows;

    if ($iLinhas > 0) {

      for ($iContador = 0; $iContador < $iLinhas; $iContador++) {

        $oEtapa = EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsMatricula, $iContador)->ed221_i_serie);
        if ($oEtapa->getEtapaCenso() == 2) {

          $this->lPreEscolaNaRede = true;
          break;
        }
      }
    }

    return $this->lPreEscolaNaRede;
  }

  /**
   * Retorna um array das necessidades especiais do aluno, caso possua
   * @return array
   */
  public function getNecessidadesEspeciais() {

    $oDaoAlunoNecessidade    = db_utils::getDao("alunonecessidade");
    $sWhereAlunoNecessidade  = "ed214_i_aluno = {$this->getCodigoAluno()}";
    $sCamposAlunoNecessidade = "ed48_i_codigo, ed48_c_descr";
    $sSqlAlunoNecessidade    = $oDaoAlunoNecessidade->sql_query(null, $sCamposAlunoNecessidade, null, $sWhereAlunoNecessidade);
    $rsAlunoNecessidade      = $oDaoAlunoNecessidade->sql_record($sSqlAlunoNecessidade);
    $iTotalAlunoNecessidade  = $oDaoAlunoNecessidade->numrows;

    if ($iTotalAlunoNecessidade > 0) {

      for ($iContador = 0; $iContador < $iTotalAlunoNecessidade; $iContador++) {

        $oDadosNecessidadeEspecial        = db_utils::fieldsMemory($rsAlunoNecessidade, $iContador);
        $oNecessidadeEspecial             =  new stdClass();
        $oNecessidadeEspecial->iCodigo    = $oDadosNecessidadeEspecial->ed48_i_codigo;
        $oNecessidadeEspecial->sDescricao = $oDadosNecessidadeEspecial->ed48_c_descr;
        $this->aNecessidadesEspeciais[]   = $oNecessidadeEspecial;
        unset($oNecessidadeEspecial);
      }
    }
    unset($oDadosNecessidadeEspecial);

    return $this->aNecessidadesEspeciais;
  }

  /**
   * Retorna os recursos necessarios para avaliacao do INEP. Recursos existentes apenas quando o aluno possui alguma
   * necessidade especial
   * @return array
   */
  public function getRecursosAvaliacao() {

    $oDaoAlunoRecursosAvaliacao    = db_utils::getDao("alunorecursosavaliacaoinep");
    $sWhereAlunoRecursosAvaliacao  = "ed327_aluno = {$this->getCodigoAluno()}";
    $sCamposAlunoRecursosAvaliacao = "ed326_sequencial, ed326_descricao";
    $sSqlAlunoRecursosAvaliacao    = $oDaoAlunoRecursosAvaliacao->sql_query(
                                                                             null,
                                                                             $sCamposAlunoRecursosAvaliacao,
                                                                             null,
                                                                             $sWhereAlunoRecursosAvaliacao
                                                                           );
    $rsAlunoRecursosAvaliacao     = $oDaoAlunoRecursosAvaliacao->sql_record($sSqlAlunoRecursosAvaliacao);
    $iTotalAlunoRecursosAvaliacao = $oDaoAlunoRecursosAvaliacao->numrows;

    if ($iTotalAlunoRecursosAvaliacao > 0) {

      for ($iContador = 0; $iContador < $iTotalAlunoRecursosAvaliacao; $iContador++) {

        $oDadosAlunoRecursosAvaliacao        = db_utils::fieldsMemory($rsAlunoRecursosAvaliacao, $iContador);
        $oAlunoRecursosAvaliacao             =  new stdClass();
        $oAlunoRecursosAvaliacao->iCodigo    = $oDadosAlunoRecursosAvaliacao->ed326_sequencial;
        $oAlunoRecursosAvaliacao->sDescricao = $oDadosAlunoRecursosAvaliacao->ed326_descricao;
        $this->aRecursosAvaliacaoInep[]      = $oAlunoRecursosAvaliacao;
        unset($oAlunoRecursosAvaliacao);
      }
    }

    unset($oDadosAlunoRecursosAvaliacao);
    return $this->aRecursosAvaliacaoInep;
  }

  /**
   * Retorna uma instancia de Cidadao do responsável pelo aluno, caso exista
   * @return Cidadao
   */
  public function getCidadaoResponsavel() {

    $oCidadaoResponsavel  = null;
    $oDaoAlunoResponsavel = new cl_alunocidadaoresponsavel();
    $sSqlAlunoResponsavel = $oDaoAlunoResponsavel->sql_query_file(
                                                                   null,
                                                                   "ed331_cidadao",
                                                                   null,
                                                                   "ed331_aluno = {$this->getCodigoAluno()}"
	                                                               );
    $rsAlunoResponsavel = $oDaoAlunoResponsavel->sql_record($sSqlAlunoResponsavel);

    if ($oDaoAlunoResponsavel->numrows > 0) {

      $iResponsavel        = db_utils::fieldsMemory($rsAlunoResponsavel, 0)->ed331_cidadao;
      $oCidadaoResponsavel = CidadaoRepository::getCidadaoByCodigo($iResponsavel);
    }

    return $oCidadaoResponsavel;
  }

  /**
   * Retorna uma instancia de Cidadao do contato do aluno, caso exista
   * @return Cidadao
   */
  public function getCidadaoContato() {

    $oCidadaoContato  = null;
    $oDaoAlunoContato = new cl_alunocidadaocontato();
    $sSqlAlunoContato = $oDaoAlunoContato->sql_query_file(
                                                           null,
                                                           "ed332_cidadao",
                                                           null,
                                                           "ed332_aluno = {$this->getCodigoAluno()}"
	                                                       );
    $rsAlunoContato = $oDaoAlunoContato->sql_record($sSqlAlunoContato);

    if ($oDaoAlunoContato->numrows > 0) {

      $iContato        = db_utils::fieldsMemory($rsAlunoContato, 0)->ed332_cidadao;
      $oCidadaoContato = CidadaoRepository::getCidadaoByCodigo($iContato);
    }

    return $oCidadaoContato;
  }

  /**
   * Define a numero do RG do Aluno.
   *
   * @param string $sIdentidade
   */
  public function setIdentidade($sIdentidade)  {

    $this->sIdentidade = $sIdentidade;
    return;
  }

  /**
   * Retorna o RG do Aluno
   *
   * @return string
   */
  public function getIdentidade() {
    return $this->sIdentidade;
  }

  /**
   * Retorna instância de SituacaoAluno
   * @return SituacaoAluno
   */
  public function getSituacao() {

    if (is_null($this->oSituacaoAluno)) {
      $this->oSituacaoAluno = new SituacaoAluno($this);
    }
    return $this->oSituacaoAluno;
  }

  public function salvar() {


    if (!db_utils::inTransaction()) {
      throw new DBException("Nenhuma transação no banco.");
    }

    $oDaoAluno = db_utils::getDao("aluno");

    $oDaoAluno->ed47_i_codigo = $this->getCodigoAluno();
    $oDaoAluno->ed47_c_codigoinep = $this->getCodigoInep();

    if (empty($this->iCodigoAluno)) {
      $oDaoAluno->incluir(null);
    } else {
      $oDaoAluno->alterar($this->iCodigoAluno);
    }

    if ($oDaoAluno->erro_status == "0") {
      throw new DBException("Erro ao salvar os dados do aluno");
    }

    $this->iCodigoAluno = $oDaoAluno->ed47_i_codigo;

    return;
  }

  /**
   * Retorna o emial do aluno
   * @return string
   */
  public function getEmail() {

    return $this->sEmailAluno;
  }

  /**
   * Define o emial do aluno
   * @param string
   */
  public function setEmail($sEmail) {

    $this->sEmailAluno = $sEmail;
  }

  /**
   * Retorna uma instancia de AlunoMatriculaCenso
   * @param integer $iAno
   * @return AlunoMatriculaCenso
   */
  public function getAlunoMatriculaCenso( $iAno ) {

    if ($this->getCodigoAluno() == null) {
      return null;
    }

    return new AlunoMatriculaCenso( $this, $iAno );
  }

  /**
   * Retorna o Fator Rh do aluno
   * @return string
   */
  public function getTipoSanguineo() {

    if ( empty($this->iTiposanguineo) ) {
      return '';
    }
    return TipoSanguineoRepository::getByCodigo($this->iTiposanguineo);
  }

  /**
   * Retorna a escola de Procedencia do aluni
   *
   * @return Escola|EscolaProcedencia
   * @throws BusinessException
   */
  public function getEscolaDeProcedencia() {

    if (!empty($this->oEscolaProcedencia)) {
      return $this->oEscolaProcedencia;
    }
    $oDaoAlunoPrimat = new cl_alunoprimat();
    $sSqlDadosEscola = $oDaoAlunoPrimat->sql_query_file(null,
                                                       "ed76_i_escola, ed76_c_tipo",
                                                       null,
                                                       "ed76_i_aluno = {$this->getCodigoAluno()}"
                                                      );
    $rsEscolaProcedencia = db_query($sSqlDadosEscola);
    if (!$rsEscolaProcedencia) {
      throw new BusinessException("Erro ao pesquisar dados da escola de procedência do aluno.");
    }
    if (pg_num_rows($rsEscolaProcedencia) > 0) {

      $oDadosEscola = db_utils::fieldsMemory($rsEscolaProcedencia, 0);
      switch ($oDadosEscola->ed76_c_tipo) {

        case 'M':

          $this->oEscolaProcedencia = EscolaRepository::getEscolaByCodigo($oDadosEscola->ed76_i_escola);
          break;

        case 'F':

          $this->oEscolaProcedencia = EscolaProcedenciaRepository::getEscolaByCodigo($oDadosEscola->ed76_i_escola);
          break;
      }
      return $this->oEscolaProcedencia;
    }
  }
}