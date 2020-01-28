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

use ECidade\Tributario\Integracao\JuntaComercial\Model\Atividade;
use ECidade\Tributario\Integracao\JuntaComercial\Model\QSA;

require_once(modification("model/CgmFactory.model.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("model/issqn/Debitos.model.php"));

/**
 * Classe que representa uma Empresa (ISSQN/Alvara) no e-Cidade
 *
 * @package ISSQN
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 */
class Empresa {

  const MENSAGENS = 'tributario.issqn.Empresa.';

  /**
   * Cgm da Empresa
   * @var CgmBase
   */
  protected $oCgmEmpresa;

  /**
   * Data de Inscricao da Empresa
   * @var DBDate
   */
  protected $oDataInicioAtividades;

  /**
   * Inscricao da empresa
   *
   * @var integer
   * @access protected
   */
  protected $iInscricao;

  /**
   * Situacao da empresa, ativa ou baixada
   *
   * @var bool
   * @access protected
   */
  protected $lAtiva;

  /**
   * @var Debitos
   */
  protected $oDebitos;

  /**
   * @var $iPorte
   */
  protected $iPorte;

  /**
   * @var $oDataCadastramento
   */
  protected $oDataCadastramento;

  /**
   * @var $oDataJunta
   */
  protected $oDataJunta;

  /**
   * @var $iArea
   */
  protected $iArea = 0;

  /**
   * @var $iCodigoLogradouro
   */
  protected $iCodigoLogradouro;

  /**
   * @var $sComplemento
   */
  protected $sComplemento;

  /**
   * @var $sBairro
   */
  protected $sBairro;

  /**
   * @var $sUF
   */
  protected $sUF;

  /**
   * @var $sPais
   */
  protected $sPais;

  /**
   * @var integer
   */
  protected $iValorCapital = "0";

  /**
   * @var string
   */
  protected $iNumero = "0";

  /**
   * @var integer
   */
  protected $iCep;

  /**
   * @var string $sObservacao
   */
  protected $sObservacao;

  /**
   * @var integer $iMatricula
   */
  protected $iMatricula;

  /**
   * @var Atividade[] $aAtividades
   */
  protected $aAtividades = array();

  /**
   * @var array[QSA]
   */
  protected $aQsa = array();

  /**
   * Construtor da Classe
   * @param integer $iIncricaoMunicipal
   * @throws DBException
   */
  public function __construct( $iIncricaoMunicipal = null ) {

    if ( !empty($iIncricaoMunicipal) ) {

      $oDaoIssBase    = new cl_issbase();
      $sSql           = $oDaoIssBase->sql_query_file($iIncricaoMunicipal);
      $rsDadosEmpresa = $oDaoIssBase->sql_record($sSql);

      /**
       * Não encontrou inscricao
       */
      if ( pg_numrows($rsDadosEmpresa) == 0 ) {
        return null;
      }

      if ( $oDaoIssBase->erro_status == "0" ) {
        throw new DBException(_M(self::MENSAGENS . 'erro_buscar_inscricao'));
      }

      $oDadosEmpresa               = db_utils::fieldsMemory($rsDadosEmpresa, 0);
      $this->iInscricao            = $oDadosEmpresa->q02_inscr;
      $this->oCgmEmpresa           = CgmFactory::getInstanceByCgm($oDadosEmpresa->q02_numcgm);
      $this->oDataInicioAtividades = new DBDate($oDadosEmpresa->q02_dtinic);
      $this->lAtiva                = empty($oDadosEmpresa->q02_dtbaix);
      $this->oDebitos              = new Debitos($this->getInscricao());
    }
  }

  /**
   * @return array
   */
  public function getQsa()
  {
    return $this->aQsa;
  }

  /**
   * @param QSA $qsa
   */
  public function adicionarQsa(QSA $qsa)
  {
    $this->aQsa[] = $qsa;
  }

  /**
   * @return Atividade[]
   */
  public function getAtividades()
  {
    if (count($this->aAtividades) == 0) {

      $oDAoAtividades = new cl_tabativ();
      $sSqlAtividades = $oDAoAtividades->sql_query_file($this->getInscricao(), null, "*", "q07_Seq");
      $rsAtividades   = db_query($sSqlAtividades);
      if (!$rsAtividades) {
        throw new \DBException("Erro ao pesquisar atiividades da inscrição {$this->getInscricao()}");
      }
      $this->aAtividades = db_utils::makeCollectionFromRecord($rsAtividades, function($oDados) {

        $oAtividade = new Atividade($this->getInscricao());
        $oAtividade->setSequencial($oDados->q07_seq);
        $oAtividade->setHoraInicio($oDados->q07_horaini);
        $oAtividade->setHoraFim($oDados->q07_horafim);
        $oAtividade->setDataInicio(new \DateTime($oDados->q07_datain));
        if (!empty($oDados->datafi)) {
          $oAtividade->setDataFim(new \DateTime($oDados->q07_datafi));
        }
        if (!empty($oDados->databx)) {
          $oAtividade->setDataBaixa(new \DateTime($oDados->q07_databx));
        }
        $oAtividade->setCodigo($oDados->q07_ativ);
        $oAtividade->setPermanente($oDados->q07_perman == 't');
        $oAtividade->setTipoBaixa($oDados->q07_tipbx);
      });
    }

    return $this->aAtividades;
  }

  /**
   * @param Atividade[] $aAtividades
   */
  public function setAtividades($aAtividades)
  {
    $this->aAtividades = $aAtividades;
  }

  public function adicionarAtividade(Atividade $oAtividade)
  {
    $this->aAtividades[] = $oAtividade;
  }

  /**
   * @return int
   */
  public function getMatricula()
  {
    return $this->iMatricula;
  }

  /**
   * @param int $iMatricula
   */
  public function setMatricula($iMatricula)
  {
    $this->iMatricula = $iMatricula;
  }

  /**
   * @return string
   */
  public function getObservacao()
  {
    return $this->sObservacao;
  }

  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao)
  {
    $this->sObservacao = $sObservacao;
  }

  /**
   * @return int
   */
  public function getCep()
  {
    return $this->iCep;
  }

  /**
   * @param int $iCep
   */
  public function setCep($iCep)
  {
    $this->iCep = $iCep;
  }

  /**
   * @return integer
   */
  public function getNumero()
  {
    return $this->iNumero;
  }

  /**
   * @param mixed $iNumero
   */
  public function setNumero($iNumero)
  {
    if(is_numeric($iNumero)) {
      $this->iNumero = $iNumero;
    }
  }

  /**
   * Retorna a data de inicio das atividades
   * @return DBDate
   */
  public function getDataInicioAtividades() {
    return $this->oDataInicioAtividades;
  }

  /**
   * @param CgmBase $oCgmEmpresa
   */
  public function setCgmEmpresa($oCgmEmpresa)
  {
    $this->oCgmEmpresa = $oCgmEmpresa;
  }

  /**
   * Retorna o CGM da Empresa
   * @return CgmBase - Cgm da Empresa
   */
  public function getCgmEmpresa() {
    return $this->oCgmEmpresa;
  }

  /**
   * Define a inscricao da empresa
   *
   * @param integer $iInscricao
   * @access public
   * @return void
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }

  /**
   * Define inscricao da empresa
   *
   * @access public
   * @return integer
   */
  public function getInscricao() {
    return $this->iInscricao;
  }

  /**
   * @return mixed
   */
  public function getPorte()
  {
    return $this->iPorte;
  }

  /**
   * @param mixed $iPorte
   */
  public function setPorte($iPorte)
  {
    $this->iPorte = $iPorte;
  }

  /**
   * @return mixed
   */
  public function getDataCadastramento()
  {
    return $this->oDataCadastramento;
  }

  /**
   * @param \DateTime $oDataCadastramento
   */
  public function setDataCadastramento($oDataCadastramento)
  {
    $this->oDataCadastramento = $oDataCadastramento;
  }

  /**
   * @return \DateTime
   */
  public function getDataJunta()
  {
    return $this->oDataJunta;
  }

  /**
   * @param \DateTime $oDataJunta
   */
  public function setDataJunta($oDataJunta)
  {
    $this->oDataJunta = $oDataJunta;
  }

  /**
   * @return mixed
   */
  public function getArea()
  {
    return $this->iArea;
  }

  /**
   * @param mixed $iArea
   */
  public function setArea($iArea)
  {
    $this->iArea = $iArea;
  }

  /**
   * @return mixed
   */
  public function getCodigoLogradouro()
  {
    return $this->iCodigoLogradouro;
  }

  /**
   * @param mixed $iCodigoLogradouro
   */
  public function setCodigoLogradouro($iCodigoLogradouro)
  {
    $this->iCodigoLogradouro = $iCodigoLogradouro;
  }

  /**
   * @return mixed
   */
  public function getComplemento()
  {
    return $this->sComplemento;
  }

  /**
   * @param mixed $sComplemento
   */
  public function setComplemento($sComplemento)
  {
    $this->sComplemento = $sComplemento;
  }

  /**
   * @return mixed
   */
  public function getBairro()
  {
    return $this->sBairro;
  }

  /**
   * @param mixed $sBairro
   */
  public function setBairro($sBairro)
  {
    $this->sBairro = $sBairro;
  }

  /**
   * @return mixed
   */
  public function getSUF()
  {
    return $this->sUF;
  }

  /**
   * @param mixed $uf
   */
  public function setUf($uf)
  {
    $this->sUF = $uf;
  }

  /**
   * @return mixed
   */
  public function getSPais()
  {
    return $this->sPais;
  }

  /**
   * @param mixed $pais
   */
  public function setPais($pais)
  {
    $this->sPais = $pais;
  }


  /**
   * Situacao da empresa, ativa ou baixada
   *
   * @access public
   * @return bool
   */
  public function isAtiva() {
    return $this->lAtiva;
  }

  /**
   * @return mixed
   */
  public function getValorCapital()
  {
    return $this->iValorCapital;
  }

  /**
   * @param mixed $iValorCapital
   */
  public function setValorCapital($iValorCapital)
  {
    $iValorCapital = trim($iValorCapital);
    if (is_numeric($iValorCapital)) {
      $this->iValorCapital = "{$iValorCapital}";
    }
  }



  /**
   * Verifica se empresa esta paralisada
   *
   * @access public
   * @return bool
   * @throws Exception
   */
  public function isParalisada() {

    /**
     * Inscricao da empresa nao definida
     */
    if ( empty($this->iInscricao) ) {
      return false;
    }

    $oDaoIssbaseparalisacao = db_utils::getDao('issbaseparalisacao');

    $sWhereParalisacao  = " q140_issbase = " . $this->getInscricao();
    $sWhereParalisacao .= " and ( ";

    /**
     * - Data do sistema maior ou igual a data inicial da paralisacao
     * - Data final da paralisacao não informada
     */
    $sWhereParalisacao .= "     '" . date('Y-m-d', db_getsession('DB_datausu')) . "' >= q140_datainicio and q140_datafim is null";

    /**
     * Data do sistema esta entre a data inicial e final da paralisacao
     */
    $sWhereParalisacao .= "  or '" . date('Y-m-d', db_getsession('DB_datausu')) . "' between q140_datainicio and q140_datafim ";
    $sWhereParalisacao .= " ) ";

    $sSqlParalisacoes = $oDaoIssbaseparalisacao->sql_query_file(null, "q140_sequencial", null, $sWhereParalisacao);
    $rsParalisacoes   = db_query($sSqlParalisacoes);

    /**
     * Erro na query, ao buscar paralisacoes
     */
    if ( !$rsParalisacoes ) {

      $oErroMensagem = (object) array('sErroBanco' => pg_last_error());
      throw new Exception(_M(self::MENSAGENS . 'erro_buscar_paralisacoes', $oErroMensagem));
    }

    /**
     * Retorna true caso empresa estiver paralisada
     */
    return pg_num_rows($rsParalisacoes) > 0;
  }

  /**
   * Retorna um objeto do tipo Debitos
   * @return Debitos classe de debitos
   */
  public function getDebitos() {
    return $this->oDebitos;
  }





}
