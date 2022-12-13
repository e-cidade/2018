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
 * Classe para controle de Disciplinas
 * @author Iuri Guntchnigg
 * @package Educacao
 */
class Disciplina {

  /**
   * Codigo da disciplina vinculada ao ensino
   */
  protected $iCodigoDisciplina;

  /**
   * Codigo unico da disciplina
   */
  protected $iCodigoDisciplinaGeral;

  /**
   * Nome da Disciplina
   * @var String
   */
  protected $sNomeDisciplina;

  /**
   *
   * Disciplinas no censo
   * @var DisciplinaCenso
   */
  protected $aDisciplinasCenso;

  /**
   * Abreviatura da Disciplina
   * @var string
   */
  protected $sAbreviatura;

  /**
   * Ensino da Disciplina
   * @var Ensino
   */
  protected $oEnsino;

  /**
   * Codigo do Censo Disciplina
   * @var integer
   */
  protected $iCodigoCensoDisciplina;

  /**
   * Carrega dos dados da disciplina;
   * @param integer $iCodigoDisciplina código da disciplina
   */
  function __construct($iCodigoDisciplina = null) {

    if (!empty($iCodigoDisciplina)) {

      $oDaoDisciplina      = db_Utils::getDao("disciplina");
      $sSqlDadosDisciplina = $oDaoDisciplina->sql_query_disciplina_censo($iCodigoDisciplina);
      $rsDadosDisciplina   = $oDaoDisciplina->sql_record($sSqlDadosDisciplina);
      if ($oDaoDisciplina->numrows > 0) {

        $oDadosDisciplina             = db_utils::fieldsMemory($rsDadosDisciplina, 0);
        $this->iCodigoDisciplina      = $oDadosDisciplina->ed12_i_codigo;
        $oEnsino                      = EnsinoRepository::getEnsinoByCodigo($oDadosDisciplina->ed10_i_codigo);
        $this->iCodigoDisciplinaGeral = $oDadosDisciplina->ed232_i_codigo;

        $this->setNomeDisciplina(trim($oDadosDisciplina->ed232_c_descr));
        $oEnsino->setNome($oDadosDisciplina->ed10_c_descr);

        $this->setAbreviatura(trim($oDadosDisciplina->ed232_c_abrev));
        $this->setEnsino($oEnsino);
        $this->setCodigoCensoDisciplina($oDadosDisciplina->ed294_censodisciplina);
        unset($oDadosDisciplina);
      }
    }
  }

  /**
   * Retorna o código da Disciplina vinculada ao ensinoi.
   * @return integer
   */
  public function getCodigoDisciplina() {
    return $this->iCodigoDisciplina;
  }

  /**
   * Retorna o codigo geral da Disciplina
   * @return string
   */
  public function getCodigoDisciplinaGeral() {
    return $this->iCodigoDisciplinaGeral;
  }

  /**
   * @return Ensino
   */
  public function getEnsino() {
    return $this->oEnsino;
  }

  /**
   * Define o tipo de Ensino da Disciplina
   * @param Ensino $oEnsino
   */
  public function setEnsino(Ensino $oEnsino) {
    $this->oEnsino = $oEnsino;
  }

  /**
   * Retorna a descricao abreviada da disciplina
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Define a abreviatura da Disciplina
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna o nome da Disciplina
   * @return String
   */
  public function getNomeDisciplina() {
    return $this->sNomeDisciplina;
  }

  /**
   * Define o nome da Disciplina
   * @param String $sNomeDisciplina
   */
  public function setNomeDisciplina($sNomeDisciplina) {
    $this->sNomeDisciplina = $sNomeDisciplina;
  }

  /**
   * Define Codigo Censo Disciplina
   * @param integer
   */
  public function setCodigoCensoDisciplina ($iCodigoCensoDisciplina) {
    $this->iCodigoCensoDisciplina = $iCodigoCensoDisciplina;
  }

  /**
   * Retorna Codigo Censo Disciplina
   * @return integer
   */
  public function getCodigoCensoDisciplina () {
    return $this->iCodigoCensoDisciplina;
  }
}