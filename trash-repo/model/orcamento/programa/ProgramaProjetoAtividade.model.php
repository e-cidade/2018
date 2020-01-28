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
 * Model respons�vel pela manuten��o de um projeto/atividade do or�amento
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package orcamento
 * @subpackage programa
 * @version $Revision: 1.4 $
 *
 * @todo
 * model constru�do de forma b�sica, � necess�rio implementar as demais propriedades e m�todos.
 */
class ProgramaProjetoAtividade {

  /**
   * Codigo do projeto/atividade
   * @var integer
   */
  protected $iCodigo;

  /**
   * Ano do projeto/atividade
   * @var integer
   */
  protected $iAno;

  /**
   * Descri��o do projeto
   * @var string
   */
  protected $sDescricao;

  /**
   * C�digo da Institui��o
   * @var unknown
   */
  protected $iCodigoInstituicao;

  /**
   * Objeto da Instituicao
   * @var Instituicao
   */
  protected $oInstituicao;

  /**
   * Array de iniciativas vinculadas ao projeto/atividade
   * @var array [] ProgramaIniciativa
   */
  protected $aIniciativas = array();


  /**
   * Constr�i o objeto de acordo com os par�metros passados no par�metro do m�todo.
   * @param integer $iCodigoProjetoAtividade
   * @param integer $iAnoProjetoAtividade
   * @throws BusinessException
   */
  public function __construct($iCodigoProjetoAtividade = null, $iAnoProjetoAtividade = null) {

    $this->iCodigo            = $iCodigoProjetoAtividade;
    $this->iAno               = $iAnoProjetoAtividade;
    if (!empty($this->iCodigo) && !empty($this->iAno)) {

      $oDaoProjAtiv     = db_utils::getDao("orcprojativ");
      $sSqlBuscaProjeto = $oDaoProjAtiv->sql_query_file($this->iAno, $this->iCodigo);
      $rsBuscaProjeto   = $oDaoProjAtiv->sql_record($sSqlBuscaProjeto);
      if ($oDaoProjAtiv->erro_status == "0") {

        $sMsgErro = "N�o foi poss�vel localizar o programa de c�digo {$this->iCodigo} para o ano {$this->iAno}.";
        throw new BusinessException($sMsgErro);
      }

      $oStdProjeto              = db_utils::fieldsMemory($rsBuscaProjeto, 0);
      $this->iAno               = $oStdProjeto->o55_anousu;
      $this->iCodigo            = $oStdProjeto->o55_projativ;
      $this->sDescricao         = $oStdProjeto->o55_descr;
      $this->iCodigoInstituicao = $oStdProjeto->o55_instit;
      unset($oStdProjeto);
    }
  }

  /**
   * Retorna o c�digo do programa
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o c�digo sequencial do programa, lembrando que o programa � por c�digo/ano
   * @param $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o ano do programa
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta o ano do programa
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna a descri��o do projeto
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descri��o do projeto/atividade
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o c�digo da institui��o a qual o projeto pertence
   * @return integer
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * Seta o c�digo da institui��o a qual o projeto pertence
   * @param $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * Retorna o objeto Instituicao da qual o projeto pertence
   * @return Instituicao
   */
  public function getInstituicao() {

    if (!empty($this->iCodigoInstituicao)) {
      $this->oInstituicao = new Instituicao($this->iCodigoInstituicao);
    }
    return $this->oInstituicao;
  }

  /**
   * Seta a Institui��o a qual o projeto pertence
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Retorna as iniciativas vinculadas ao projeto/atividade
   * @return array [] ProgramaIniciativa
   */
  public function getIniciativas() {

    if (count($this->aIniciativas) == 0) {

      $oDaoVinculoIniciativa      = db_utils::getDao("orciniciativavinculoprojativ");
      $sWhereProjetoAtividade     = "     o149_projativ = {$this->getCodigo()}";
      $sWhereProjetoAtividade    .= " and o149_anousu   = {$this->getAno()}";
      $sSqlBuscaVinculoIniciativa = $oDaoVinculoIniciativa->sql_query_file(null, "o149_iniciativa", null, $sWhereProjetoAtividade);
      $rsBuscaVinculoIniciativa   = $oDaoVinculoIniciativa->sql_record($sSqlBuscaVinculoIniciativa);
      if (!$rsBuscaVinculoIniciativa) {
        throw new BusinessException("N�o foi poss�vel buscar as iniciativas vinculadas ao projeto/atividade.");
      }

      for ($iRowVinculo = 0; $iRowVinculo < $oDaoVinculoIniciativa->numrows; $iRowVinculo++) {

        $iCodigoIniciativa = db_utils::fieldsMemory($rsBuscaVinculoIniciativa, $iRowVinculo)->o149_iniciativa;
        $this->aIniciativas[$iCodigoIniciativa] = new ProgramaIniciativa($iCodigoIniciativa);
      }
    }
    return $this->aIniciativas;
  }

  /**
   * Remove o v�nculo entre a iniciativa e um projeto/atividade
   * @param ProgramaIniciativa $oProgramaIniciativa
   * @throws BusinessException
   * @return boolean true
   */
  public function removerIniciativa(ProgramaIniciativa $oProgramaIniciativa) {

  	$iCodigoProjeto        = $oProgramaIniciativa->getProjetoAtividade()->getCodigo();
    $oDaoVinculoIniciativa = db_utils::getDao("orciniciativavinculoprojativ");
    
    $sWhereExclusao  = "     o149_iniciativa = {$oProgramaIniciativa->getCodigoSequencial()} ";
    $sWhereExclusao .= " and o149_projativ   = {$this->iCodigoProjeto} ";
    $sWhereExclusao .= " and o149_anousu    >= {$this->iAno} ";
    
    $oDaoVinculoIniciativa->excluir(null, $sWhereExclusao);
    if ($oDaoVinculoIniciativa->erro_status == "0") {
      throw new BusinessException("N�o foi poss�vel excluir o v�nculo entre a iniciativa e o projeto/atividade.");
    }

    unset($this->aIniciativas[$oProgramaIniciativa->getCodigoSequencial()]);
    return true;
  }


  /**
   * M�todo que vincula uma iniciativa a um projeto/atividade
   * @param ProgramaIniciativa $oProgramaIniciativa
   * @throws BusinessException
   * @return boolean
   */
  public function vincularIniciativa(ProgramaIniciativa $oProgramaIniciativa) {

    $oDaoVinculoIniciativa = db_utils::getDao("orciniciativavinculoprojativ");
    $sWhereIniciativa      = "     o149_iniciativa = {$oProgramaIniciativa->getCodigoSequencial()}";
    $sWhereIniciativa     .= " and o149_projativ   = {$this->getCodigo()}";
    $sWhereIniciativa     .= " and o149_anousu     = {$this->getAno()} ";
    $sSqlBuscaIniciativa   = $oDaoVinculoIniciativa->sql_query_file(null, "*", null, $sWhereIniciativa);
    $rsBuscaIniciativa     = $oDaoVinculoIniciativa->sql_record($sSqlBuscaIniciativa);
    if ($oDaoVinculoIniciativa->numrows == 1) {

      $oStdDadoVinculo = db_utils::fieldsMemory($rsBuscaIniciativa, 0);
      $sMsgErro        = "Iniciativa {$oProgramaIniciativa->getCodigoSequencial()} vinculada ao ";
      $sMsgErro       .= "programa {$oStdDadoVinculo->o149_projativ}.";
      throw new BusinessException($sMsgErro);
    }

    $oDaoVinculoIniciativa->o149_sequencial = null;
    $oDaoVinculoIniciativa->o149_iniciativa = $oProgramaIniciativa->getCodigoSequencial();
    $oDaoVinculoIniciativa->o149_projativ   = $this->getCodigo();
    $oDaoVinculoIniciativa->o149_anousu     = $this->getAno();
    $oDaoVinculoIniciativa->incluir(null);
    if ($oDaoVinculoIniciativa->erro_status == "0") {

      $sMsgErro  = "N�o foi poss�vel vincular a iniciativa [{$oProgramaIniciativa->getCodigoSequencial()} - ";
      $sMsgErro .= "{$oProgramaIniciativa->getIniciativa()}] ao projeto/atividade [{$this->getDescricao()}]";
      throw new BusinessException($sMsgErro);
    }

    $this->aIniciativas[$oProgramaIniciativa->getCodigoSequencial()] = $oProgramaIniciativa;
    return true;
  }
}
?>