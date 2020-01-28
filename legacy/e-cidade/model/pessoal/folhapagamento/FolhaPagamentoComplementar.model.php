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
 * Classe representa a folha de pagamento do complementar
 * 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.20 $
 */

class FolhaPagamentoComplementar extends FolhaPagamento {
  
  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamentoComplementar.';

  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR);
  }

  /**
   * Retorna a ultima folha aberta do tipo complementar
   * 
   * @example FolhaPagamentoComplementar::getFolhaAberta()
   * @return FolhaPagamentoComplementar
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamentoComplementar($iCodigoFolha);
    }
    return false;
  }

  /**
   * Retorna se há uma folha aberta
   * 
   * @example FolhaPagamentoComplementar::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta( DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, $oCompetencia);
  }

  /**
   * Verifica se existe algum registro do tipo folha salario na
   * competencia passada por parametro ou caso não seja passado
   * pega a competencia atual
   * 
   * @param DBCompetencia $oCompetencia Opcional
   * @return Boolean
   */
  public static function hasFolha(DBCompetencia $oCompetencia = null) {

    if ($oCompetencia) {
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

  public static function getUltimaFolha( DBCompetencia $oCompetencia = null ) {
    return new FolhaPagamentoComplementar(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, null, $oCompetencia) ); 
  }

  /**
   * Retorna o ultimo número unico da folha pagamento, conforme o tipo passado.
   * 
   * @example  FolhaPagamento:getProximoNumero(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)
   * @return   Integer  Próximo numero de folha complementar
   */
  public static function getProximoNumero() {
    return FolhaPagamento::getProximoNumero(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR); 
 }

  /**
   * Função que verifica se existe pelo menos um registro no 
   * ponto de complementar na competência e instituição atual. 
   * @return boolean
   */
  public function pesquisarPonto() {
    
    /**
     * Verifica se existe pelo menos um registro
     * para a folha complementar.
     */
    $oDaoPontoCom =  new cl_pontocom();
    
    $sWherePonto  = "    r47_anousu  = {$this->getCompetencia()->getAno()}";
    $sWherePonto .= " and r47_mesusu = {$this->getCompetencia()->getMes()}";
    $sWherePonto .= " and r47_instit = {$this->getInstituicao()->getSequencial()}";

    $sSqlPontoCom = $oDaoPontoCom->sql_query_file(
      null, 
      null, 
      null, 
      null, 
      "distinct r47_regist", 
      null, 
      $sWherePonto
    );
    $rsPontoCom   = db_query($sSqlPontoCom);

    if (!$rsPontoCom) {
      throw new DBException(_M(self::MENSAGENS . "erro_ponto"));
    }

    if(pg_num_rows($rsPontoCom) != 0) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Função para fechamento da folha, antes de realizar o fechamento verifica 
   * se existe pelo menos um registro no ponto de complementar na competência e 
   * instituição atual.
   * @return boolean
   */
  public function fechar() {
    
    /**
     * Verifica se a folha esta aberta
     */
    if (!$this->isAberto()){ 
      throw new DBException( _M(self::MENSAGENS . "fechamento_folha_fechada"));
    }
    
    /**
     * Verifica se existe pelo menos um registro
     * para a folha complementar.
     */
    $aServidoresPontoCom = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($this);

    if ( count($aServidoresPontoCom) == 0 ) {
      throw new BusinessException(_M(self::MENSAGENS . "sem_registro_ponto"));
    }
    
    /**
     * Remove os pontos lançados para a folha atual
     */
    $oDaoPontoCom    = new cl_pontocom;
    $sWherePontoCom  = "     r47_anousu = {$this->getCompetencia()->getAno()}";
    $sWherePontoCom .= " and r47_mesusu = {$this->getCompetencia()->getMes()}";
    $sWherePontoCom .= " and r47_instit = {$this->getInstituicao()->getSequencial()}";
    $oDaoPontoCom->excluir(null, null, null, null, $sWherePontoCom);

    /**
     * Faz update no semest para ficar com o 
     * numero atual da folha de pagamento.
     */
    $oDaoGerfCom             = new cl_gerfcom();
    $oDaoGerfCom->r48_anousu = $this->getCompetencia()->getAno();
    $oDaoGerfCom->r48_mesusu = $this->getCompetencia()->getMes();
    $oDaoGerfCom->r48_semest = $this->getNumero();

    $oDaoGerfCom->alterar($this->getCompetencia()->getAno(), $this->getCompetencia()->getMes());

    $this->fecharFolha();
       
    return true;
  }

  /**
   * Função abstrata para cancelamento da abertura da folha.
   * - Verifica se a folha esta aberta
   * - Remove os calculos lançados para a folha atual.
   * - Remove os pontos lançados para a folha atual.
   *
   * @return 
   */
  public function cancelarAbertura(){

    /**
     * Verifica se a folha esta aberta.
     */
    if (!$this->isAberto()) {
      throw new BusinessException(_M(self::MENSAGENS . 'folha_fechada'));
    }

    /**
     * Remove os calculos lançados para a folha atual
     */
    $oDaoGerfCom = new cl_gerfcom();

    $sWhereGerfCom  = "     r48_anousu = {$this->getCompetencia()->getAno()}";
    $sWhereGerfCom .= " and r48_mesusu = {$this->getCompetencia()->getMes()}";
    $sWhereGerfCom .= " and r48_semest = {$this->getNumero()}";
    $sWhereGerfCom .= " and r48_instit = {$this->getInstituicao()->getSequencial()}";

    $oDaoGerfCom->excluir(null, null, null, null, $sWhereGerfCom);

    if ($oDaoGerfCom->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_gerfcom'));
    }

    /**
     * Remove os pontos lançados para a folha atual
     */
    $oDaoPontoCom = new cl_pontocom;

    $sWherePontoCom  = "     r47_anousu = {$this->getCompetencia()->getAno()}";
    $sWherePontoCom .= " and r47_mesusu = {$this->getCompetencia()->getMes()}";
    $sWherePontoCom .= " and r47_instit = {$this->getInstituicao()->getSequencial()}";

    $oDaoPontoCom->excluir(null, null, null, null, $sWherePontoCom);

    if ($oDaoPontoCom->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_pontocom'));
    }

    $this->excluir();

    $oFolhaAnterior = FolhaPagamentoComplementar::getUltimaFolha();

    if ( !!$oFolhaAnterior->getSequencial() ) {
       $oFolhaAnterior->retornarCalculo();
       
       $oDaoGerfCom             = new cl_gerfcom();
       $oDaoGerfCom->r48_anousu = $oFolhaAnterior->getCompetencia()->getAno();
       $oDaoGerfCom->r48_mesusu = $oFolhaAnterior->getCompetencia()->getMes();
       $oDaoGerfCom->r48_semest = $oFolhaAnterior->getNumero();

       $oDaoGerfCom->alterar($oFolhaAnterior->getCompetencia()->getAno(), $oFolhaAnterior->getCompetencia()->getMes());
    }

    return true;     
  }
  
 /**
  * Este função é sobrecarga
  * 
  * @return boolean
  * @throws DBException
  */
  public function cancelarFechamento() {
    
    parent::cancelarFechamento();

    $oDaoGerfCom             = new cl_gerfcom();
    $oDaoGerfCom->r48_anousu = $this->getCompetencia()->getAno();
    $oDaoGerfCom->r48_mesusu = $this->getCompetencia()->getMes();
    $oDaoGerfCom->r48_semest = "0";
    $oDaoGerfCom->alterar($this->getCompetencia()->getAno(), $this->getCompetencia()->getMes());

    if ($oDaoGerfCom->erro_status == "0") {
      throw new DBException($oDaoGerfCom->erro_msg);
    }
    return true;
  }

  /**
   * Retorna todas as folhas complementares fechadas na compentência
   * @param  DBCompetencia $oCompetencia Competencia da Folha
   * @return FolhaPagamentoComplementar[] 
   */
  public static function getFolhasFechadasCompetencia( DBCompetencia $oCompetencia ) {
    return FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR);
  }
}
