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
 * A classe representa a importação dos eventos financeiros
 * @author $Author: dbrenan $
 * @version $Revision: 1.3 $
 */

class ImportacaoEventosFinanceiros {
  
  const MENSAGEM = "recursoshumanos.pessoal.ImportacaoEventosFinanceiros.";
  
  /**
   *
   * @var DBCompetencia 
   */
  private $oCompetencia;
  
  /**
   *
   * @var Instituicao
   */
  private $oInstituicao;
  
  /**
   *
   * @var EventoFinanceiroFolha[]
   */
  private $aEventosFinanceiros;
  
  /**
   *
   * @var String
   */
  private $sTipoFolha;
  
  /**
   * Construtor da classe
   * 
   * @return ImportacaoEventosFinanceiros
   */
  function __construct() {
    
    $iInstituicao = db_getsession("DB_instit");
    $this->setInstituicao(new Instituicao($iInstituicao));
    $this->setCompetencia(DBPessoal::getCompetenciaFolha());
    return;
  }
  
  /**
   * Retorna a competência da importação
   * 
   * @access public
   * @return DBCompetencia
   */
  public function getCompetencia() {
    return $this->oCompetencia;
  }

  /**
   * Retorna a instituição da importação
   * 
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }
    
  /**
   * Retorna os eventos financeiros da importação
   * 
   * @access public
   * @return EventoFinanceiroFolha[]
   */
  public function getEventosFinanceiros() {
    return $this->aEventosFinanceiros;
  }

  /**
   * Retorna o tipo da folha da importação
   * 
   * @access public
   * @return String
   */
  public function getTipoFolha() {
    return $this->sTipoFolha;
  }

  /**
   * Seta a competência da importação
   * 
   * @access private
   * @param DBCompetencia $oCompetencia
   */
  private function setCompetencia(DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Seta a instituição da importação
   * 
   * @access private
   * @param Instituicao $oInstituicao
   */
  private function setInstituicao(Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }
    
  /**
   * Seta os eventos financeiros da importação
   * 
   * @access public
   * @param EventoFinanceiroFolha[] $aEventosFinanceiros
   */
  public function setEventosFinanceiros($aEventosFinanceiros) {
    $this->aEventosFinanceiros = $aEventosFinanceiros;
  }

  /**
   * Seta a o tipo da folha da importação
   * 
   * @access public
   * @param String $sTipoFolha
   */
  public function setTipoFolha($sTipoFolha) {
    $this->sTipoFolha = $sTipoFolha;
  }
    
  /**
   * Verifica se o ponto foi inicializado.
   * @access public
   * @return boolean
   */
  public function validarPontoInicializado(){

    /**
     * Se utiliza a estrutura nova de complementar verifica 
     * se existe uma folha de salário aberta.
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      $lFolhaAberta = FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_SALARIO, $this->oCompetencia);

      if (!$lFolhaAberta){
        throw new BusinessException(_M( self::MENSAGEM . 'salario_fechado'));
      }
    }

    /**
     * Se não utilizar a estrutura nova de complementar
     * verifica se existe dados no pontofs para a competência, 
     * se existir é porque o ponto foi inicializado.
     */
    $oDaoPontoFs = new cl_pontofs();
    $sSqlPontoFs = $oDaoPontoFs->sql_query_file ( $this->oCompetencia->getAno(), 
                                                  $this->oCompetencia->getMes(), 
                                                  null, 
                                                  null, 
                                                  "r10_rubric"
                                                );
    $rsPontoFs = db_query($sSqlPontoFs);

    if (!$rsPontoFs) {
      throw new DBException(_M(self::MENSAGEM . 'erro_ponto'));
    }

    if (pg_num_rows($rsPontoFs) == 0) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_ponto_nao_inicializado'));
    }
    
    return true;
  }
  
  /**
   * 
   */
  public function processarDados() {
    
    $aDadosPonto = array();
    
    foreach ($this->getEventosFinanceiros() as $oEventoFinanceiro) {
      
      $oDaoPonto             = new stdClass();
      $oDaoPonto->r29_anousu = $this->getCompetencia()->getAno();
      $oDaoPonto->r29_mesusu = $this->getCompetencia()->getMes();
      $oDaoPonto->r29_regist = $oEventoFinanceiro->getServidor()->getMatricula();
      $oDaoPonto->r29_rubric = $oEventoFinanceiro->getRubrica()->getCodigo();
      $oDaoPonto->r29_valor  = $oEventoFinanceiro->getValor();
      $oDaoPonto->r29_quant  = $oEventoFinanceiro->getQuantidade();
      $oDaoPonto->r29_lotac  = $oEventoFinanceiro->getServidor()->getCodigoLotacao();
      $oDaoPonto->r29_instit = $this->getInstituicao()->getCodigo();
      
      if ($this->getTipoFolha() == pontoFolha::PONTO_FERIAS || $this->getTipoFolha() == pontoFolha::PONTO_RESCISAO) {
        $oDaoPonto->r29_tpp = "";
      }
      
      $aDadosPonto[] = $oDaoPonto;
    }
    
    try {
      
      $oPontoFolha = new pontoFolha();
      $oPontoFolha->excluiRubricaPonto($this->getTipoFolha(), $aDadosPonto);
      $oPontoFolha->incluiRubricaPonto($this->getTipoFolha(), $aDadosPonto);
    
    } catch (Exception $ex) {
      throw new BusinessException(_M($ex->getMessage()));
    }
  }
  
}





