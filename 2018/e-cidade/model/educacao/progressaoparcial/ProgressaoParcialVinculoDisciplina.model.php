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
 * Classe para vinculo de uma progressao parcial com uma regencia
 * Define as disciplinas que o o aluno está cursando
 * @author Iuri Guntchnigg iuri@dbseller.com
 * @package educacao
 * @subpackage progressaoparcial
 * @version $Revision: 1.4 $
 */
final class ProgressaoParcialVinculoDisciplina {

  /**
   * Regencia em que  aluno está vinculado
   * @var Regencia
   */
  private $oRegencia;

  /**
   * Código do vinculo
   * @var integer
   */
  private $iCodigoVinculo;

  /**
   * Data do vinculo
   * @var DBDate
   */
  private $dtVinculo;

  /**
   * Codigo da progressao parcial
   * @var integer
   */
  private $iCodigoProgressaoAluno;

  /**
   * Codigo da Matricula da progressao
   * @var integer
   */
  private $iCodigoMatricula;

  /**
   * Ano da matricula
   * @var integer
   */
  private $iAno;

  /**
   * Matricula encerrada
   * @var boolean
   */
  private $lEncerrado = false;


  /**
   * Resultado final da Progressao para a disciplina e matricula atual
   * @var ProgressaoParcialAlunoResultadoFinal
   */
  private $oResultadoFinal;

  /**
   * Metodo construtor da classe
   * Preenche os dados da classe
   * @param integer $iCodigoVinculo código do vinculo
   */
  public function __construct($iCodigoVinculo = null) {

    if (!empty($iCodigoVinculo)) {

      $oDaoProgressaoVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");
      $sSqlVinculo           = $oDaoProgressaoVinculo->sql_query_matricula($iCodigoVinculo);
      $rsVinculo             = $oDaoProgressaoVinculo->sql_record($sSqlVinculo);
      if ($oDaoProgressaoVinculo->numrows == 1) {

        $oDadosProgressao             = db_utils::fieldsMemory($rsVinculo, 0);
        $this->oRegencia              = new Regencia($oDadosProgressao->ed115_regencia);
        $this->iCodigoProgressaoAluno = $oDadosProgressao->ed150_progressaoparcialaluno;
        $this->iCodigoVinculo         = $oDadosProgressao->ed115_sequencial;
        $this->dtVinculo              = new DBDate($oDadosProgressao->ed115_datavinculo);
        $this->iAno                   = $oDadosProgressao->ed150_ano;
        $this->lEncerrado             = $oDadosProgressao->ed150_encerrado == 't' ? true : false;
        $this->iCodigoMatricula       = $oDadosProgressao->ed150_sequencial;
      }
    }
  }

  /**
   * Define a Regencia do vinculo
   * @param Regencia $oRegencia Instancia da regencia
   */
  public function setRegencia(Regencia $oRegencia) {
    $this->oRegencia = $oRegencia;
  }

  /**
   * Retorna a regencia em que a progressao está vinculada
   * @return Regencia
   */
  public function getRegencia() {
    return $this->oRegencia;
  }

  /**
   * Retorna o codigo da progressao do aluno
   * @return  integer
   */
  public function getCodigoProgressao() {
    return $this->iCodigoProgressaoAluno;
  }
  /**
   * Data do vinculo com a turma
   * Data em que o aluno foi vinculado a turma
   * @param DBDate $dtVinculo data em que o aluno foi vinculado a turma
   */
  public function setDataVinculo(DBDate $dtVinculo) {
    $this->dtVinculo = $dtVinculo;
  }

  /**
   * Retorna a data do vinculo do aluno com a turma
   * @return DBDate
   */
  public function getDataVinculo() {
    return $this->dtVinculo;
  }

  /**
   * Retorna o codigo do vinculo gerado
   * @return integer
   */
  public function getCodigoVinculo () {
    return  $this->iCodigoVinculo;
  }

  /**
   * Persiste os dados de vinculo de uma progressao com uma disciplina;
   * @param integer $oProgressaoParcial Progressao Parcial
   * @throws BusinessException
   */
  public function salvar(ProgressaoParcialAluno $oProgressaoParcial) {

    if ($this->oRegencia == null) {
      throw new BusinessException("Regência não informada para a realizacao do vinculo.");
    }

    if ($this->dtVinculo == null) {
      throw new BusinessException("Data de vinculo não informado para a realizacao do vinculo.");
    }
    /**
     * vinculamos a turma ao registro do ano
     */
    $oDaoProgressaoMatricula = db_Utils::getDao("progressaoparcialalunomatricula");
    if ($this->iCodigoMatricula == null) {

      $oDaoProgressaoMatricula->ed150_ano                    = $this->getAno();
      $oDaoProgressaoMatricula->ed150_progressaoparcialaluno = $oProgressaoParcial->getCodigoProgressaoParcial();
      $oDaoProgressaoMatricula->ed150_encerrado              = $this->isEncerrado() ? "true" : "false";
      $oDaoProgressaoMatricula->incluir(null);
      if ($oDaoProgressaoMatricula->erro_status == 0) {

        $sMsgErro = "Erro ao realizar vinculo da regência.\nErro Técnico:\n{$oDaoProgressaoMatricula->erro_msg}";
        throw new BusinessException($sMsgErro);
      }
      $this->iCodigoMatricula = $oDaoProgressaoMatricula->ed150_sequencial;
    }

    $oDaoProgressaoVinculo                    = db_utils::getDao("progressaoparcialalunoturmaregencia");
    $oDaoProgressaoVinculo->ed115_datavinculo = $this->getDataVinculo()->convertTo(DBDate::DATA_EN);
    $oDaoProgressaoVinculo->ed115_regencia    = $this->getRegencia()->getCodigo();
    if (empty($this->iCodigoVinculo)) {

      $oDaoProgressaoVinculo->ed115_progressaoparcialalunomatricula = $this->iCodigoMatricula;
      $oDaoProgressaoVinculo->incluir(null);
      $this->iCodigoVinculo = $oDaoProgressaoVinculo->ed115_sequencial;
    } else {

      $oDaoProgressaoVinculo->ed115_sequencial = $this->iCodigoVinculo;
      $oDaoProgressaoVinculo->alterar($oDaoProgressaoVinculo->ed115_sequencial);
      /**
       * Alteramos a situacao do vinculo
       */
      $oDaoProgressaoMatricula->ed150_encerrado  = $this->isEncerrado() ? "true" : "false";
      $oDaoProgressaoMatricula->ed150_sequencial = $this->iCodigoMatricula;
      $oDaoProgressaoMatricula->alterar($this->iCodigoMatricula);
      if ($oDaoProgressaoMatricula->erro_status == 0) {

        $sMsgErro = "Erro ao salvar dados do vinculo da regência.\nErro Técnico:\n{$oDaoProgressaoMatricula->erro_msg}";
        throw new BusinessException($sMsgErro);
      }
    }

    if ($oDaoProgressaoVinculo->erro_status == 0) {
      throw new BusinessException("Erro ao realizar vinculo da regência.\nErro Técnico:\n{$oDaoProgressaoVinculo->erro_msg}");
    }
  }

  /**
   * Remove o vinculo.
   * Realiza e a exclusao do vinculo da dependencia com a progressao, permitindo realizar
   * novos vínculos para dependencia, e remove tambem a matricula vinculada a dependencia
   */
  public function remover() {

    if (!empty($this->iCodigoVinculo)) {

      $this->getResultadoFinal()->remover();
      $oDaoVinculo = db_utils::getDao("progressaoparcialalunoturmaregencia");
      $oDaoVinculo->excluir($this->iCodigoVinculo);
      if ($oDaoVinculo->erro_status == 0) {

        $sMsgErro = "Erro ao salvar dados do vinculo da regência.\nErro Técnico:\n{$oDaoVinculo->erro_msg}";
        throw new BusinessException($sMsgErro);
      }

      $oDaoProgressaoMatricula = db_utils::getDao("progressaoparcialalunomatricula");
      $oDaoProgressaoMatricula->excluir($this->iCodigoMatricula);
      if ($oDaoProgressaoMatricula->erro_status == 0) {

        $sMsgErro = "Erro ao salvar dados do vinculo da regência.\nErro Técnico:\n{$oDaoProgressaoMatricula->erro_msg}";
        throw new BusinessException($sMsgErro);
      }
      unset($this);
    }
  }

  /**
   * Define o ano da matricula
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o ano em que o aluno foi matricula
   * @return integer
   */
  public function getAno() {

    return $this->iAno;
  }

  /**
   * Define os dados do resultado final da progressao final do aluno
   * @param string  $sNota
   * @param integer $iTotalFaltas
   * @param string $sResultadoFinal
   */
  public function setResultadoFinal($sNota, $iTotalFaltas, $sResultadoFinal) {

    $oResultadoFinal = $this->getResultadoFinal();
    $oResultadoFinal->setNota($sNota);
    $oResultadoFinal->setTotalFalta($iTotalFaltas);
    $oResultadoFinal->setResultado($sResultadoFinal);
    $this->oResultadoFinal = $oResultadoFinal;
  }


  /**
   * Retorna a matricula da Progressao parcial
   * @return number
   */
  public function getMatricula () {

    return $this->iCodigoMatricula;
  }

  /**
   * Retorna a instancia de ProgressaoParcialAlunoResultadoFinal
   * @return ProgressaoParcialAlunoResultadoFinal
   */
  public function getResultadoFinal () {

    if (empty($this->oResultadoFinal)) {
      $this->oResultadoFinal = new ProgressaoParcialAlunoResultadoFinal($this);
    }
    return $this->oResultadoFinal;
  }

  /**
   * Define se a matricula foi encerrada
   * @param boolean $lEncerrado
   */
  public function setEncerrado($lEncerrado) {
    $this->lEncerrado = $lEncerrado;
  }

  /**
   * Retorna se a matricula esta encerrada
   * @return boolean
   */
  public function isEncerrado() {

    return $this->lEncerrado;
  }
}