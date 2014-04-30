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
 * Classe para controle do resultado final
 * Controle do resultado final de uma progressao parcial de um aluno
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package educacao
 * @subpackage progressaoparcial
 */
final class ProgressaoParcialAlunoResultadoFinal {

  /**
   * Código do resultado final
   * @var integer
   */
  private $iCodigoResultado;

  /**
   * Nota do resultado final
   * @var string
   */
  private $sNota;

  /**
   * Total de faltas que o o aluno Obteve
   * @var integer
   */
  private $iTotalFalta = "";

  /**
   * Resultado final da progressao
   * @var string
   */
  private $sResultado = "";

  /**
   * Progressao Parcial do Aluno
   * @var ProgressaoParcialVinculoDisciplina
   */
  private $oProgressaoVinculoDisciplina;

  /**
   * Cria um novo resultado final para a matricula da progressao
   * @param ProgressaoParcialVinculoDisciplina $oProgressaoParcialVinculoTurma
   */
  public function __construct(ProgressaoParcialVinculoDisciplina $oProgressaoParcialVinculoTurma) {

    $oDaoResultadoFinal = db_utils::getDao("progressaoparcialalunoresultadofinal");
    $sWhere             = "ed121_progressaoparcialalunomatricula = {$oProgressaoParcialVinculoTurma->getMatricula()}";
    $sSqlResultadoFinal = $oDaoResultadoFinal->sql_query_file(null, "*", null, $sWhere);
    $rsResultadoFinal   = $oDaoResultadoFinal->sql_record($sSqlResultadoFinal);

    $this->oProgressaoVinculoDisciplina  = $oProgressaoParcialVinculoTurma;
    if ($oDaoResultadoFinal->numrows > 0) {

      $oDados = db_utils::fieldsMemory($rsResultadoFinal, 0);
      $this->iCodigoResultado = $oDados->ed121_sequencial;
      $this->setNota($oDados->ed121_nota);
      $this->setTotalFalta($oDados->ed121_faltas);
      $this->setResultado($oDados->ed121_resultadofinal);
      unset($oDados);
    }

  }

  /**
   * Retorna o codigo do Resultado final
   * @return integer Codigo do Resultado Final
   */
  public function getCodigoResultado() {
    return $this->iCodigoResultado;
  }

  /**
   * Retorna a nota final do aluno
   * Retorna a nota que o aluno obteve na progressao parcial;
   * @return string nota Final
   */
  public function getNota() {
    return $this->sNota;
  }

  /**
   * Define a nota final do aluno
   * Define a nota que o aluno obteve na progressao parcial;
   * @param $sNota
   */
  public function setNota($sNota) {
    $this->sNota = $sNota;
  }

  /**
   * Total de faltas
   * Total de faltas do aluno durante as aulas de progressão
   * @return integer Total de faltas
   */
  public function getTotalFalta() {

    return $this->iTotalFalta;
  }

  /**
   * Total de faltas
   * Total de faltas do aluno durante as aulas de progressão
   * @param integer $iTotalFalta total de faltas
   */
  public function setTotalFalta($iTotalFalta) {
    $this->iTotalFalta = $iTotalFalta;
  }

  /**
   * Retorna o resultado obtido
   * Resultado final obtido pelo aluno
   * @return string
   */
  public function getResultado() {
    return $this->sResultado;
  }

  /**
   * Define o resultado final do aluno
   * @param string $sResultado resultado final do aluno
   */
  public function setResultado($sResultado) {
    $this->sResultado = $sResultado;
  }

  /**
   * Persiste os dados da progressao parcial do aluno.
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoResultadoFinal                                        = db_utils::getDao("progressaoparcialalunoresultadofinal");
    $oDaoResultadoFinal->ed121_faltas                          = $this->getTotalFalta();
    $oDaoResultadoFinal->ed121_nota                            = $this->getNota();
    $oDaoResultadoFinal->ed121_resultadofinal                  = $this->getResultado();
    $oDaoResultadoFinal->ed121_progressaoparcialalunomatricula = $this->oProgressaoVinculoDisciplina->getMatricula();
    if (empty($this->iCodigoResultado)) {

      $oDaoResultadoFinal->incluir(null);
      $this->iCodigoResultado = $oDaoResultadoFinal->ed121_sequencial;
    } else {

     $GLOBALS["HTTP_POST_VARS"]["ed121_nota"]           = '';
     $GLOBALS["HTTP_POST_VARS"]["ed121_faltas"]         = '';
     $GLOBALS["HTTP_POST_VARS"]["ed121_resultadofinal"] = '';
     $oDaoResultadoFinal->ed121_sequencial = $this->getCodigoResultado();
     $oDaoResultadoFinal->alterar($oDaoResultadoFinal->ed121_sequencial);
    }

    if ($oDaoResultadoFinal->erro_status == 0) {
      throw new BusinessException('Erro ao salvar dados do resultado final da progressão Parcial');
    }
  }

  /**
   * Remove os dados do resultado final do aluno
   */
  public function remover() {

    $oDaoResultadoFinal = db_utils::getDao("progressaoparcialalunoresultadofinal");
    $iCodigoVinculo     = $this->oProgressaoVinculoDisciplina->getMatricula();
    $sWhere             = "ed121_progressaoparcialalunomatricula = {$iCodigoVinculo}";
    $oDaoResultadoFinal->excluir(null, $sWhere);
    if ($oDaoResultadoFinal->erro_status == 0) {
      throw new BusinessException('Erro ao remover dados do resultado final da progressão parcial / Dependência');
    }

  }
}