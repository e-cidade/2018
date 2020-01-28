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
 * Classe representa a folha de pagamento do salário
 * @author $Author: dbrafael.nery $
 * @version $Revision: 1.14 $
 */
class FolhaPagamentoSalario extends FolhaPagamento {
  
  /**
   * Contrutor da classe FolhaPagamentoSalario
   * 
   * @param Integer $iSequencial
   */
  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_SALARIO);
  }

  /**
   * Retorna a ultima folha aberta do tipo salario
   * 
   * @example  FolhaPagamentoSalario::getFolhaAberta()
   * @return  FolhaPagamentoSalario Instância com todos os dados setados
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SALARIO, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamentoSalario($iCodigoFolha);
    }
    return false;
  }

  public static function getUltimaFolha() {
   return new FolhaPagamentoSalario(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SALARIO) ); 
  }


  /**
   * Retorna se há uma folha aberta
   * 
   * @example FolhaPagamentoSalario::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta(DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_SALARIO, $oCompetencia);
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
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SALARIO, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SALARIO,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

  /**
   * Retorna o ultimo número unico da folha pagamento, conforme o tipo passado.
   * 
   * @example  FolhaPagamento:getProximoNumero(FolhaPagamento::TIPO_FOLHA_SALARIO)
   * @return   Integer  Próximo número da folha salário
   */
  public static function getProximoNumero() {
    return FolhaPagamento::getProximoNumero(FolhaPagamento::TIPO_FOLHA_SALARIO);
  }

  /**
   * Realiza o fechamento da folha salário
   * @return boolean
   */
  public function fechar() {

    /**
     * Verifica se existe alguma complementar aberta.
     */
    if (FolhaPagamentoComplementar::hasFolhaAberta($this->getCompetencia())) {
      throw new DBException(_M(self::MENSAGENS . "folha_complementar_aberta"));
    }

    /**
     * Verifica se a folha esta aberta
     */
    if ( !$this->isAberto() ) {
      throw new DBException( _M(self::MENSAGENS . "fechamento_folha_fechada"));
    }

    /**
     * Verifica se existe pelo menos um registro
     * para a folha salário.
     */
    $aServidoresPontoFs = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($this);

    if ( count($aServidoresPontoFs) == 0 ) {
      throw new BusinessException( _M(self::MENSAGENS . "sem_registro_ponto"));
    }

    /**
     * Remove os pontos lançados para a folha atual.
     */
    $oDaoPontoFs    = new cl_pontofs();
    $sWherePontoFs  = "     r10_anousu = {$this->getCompetencia()->getAno()}";
    $sWherePontoFs .= " and r10_mesusu = {$this->getCompetencia()->getMes()}";
    $sWherePontoFs .= " and r10_instit = {$this->getInstituicao()->getSequencial()}";
    $oDaoPontoFs->excluir(null, null, null, null, $sWherePontoFs);

    if ($oDaoPontoFs->erro_status == 0) { 
      throw new DBException( _M(self::MENSAGENS . "erro_excluir_pontofs"));
    }

    $this->fecharFolha();
    
    return true;
  }

  /**
   * Metodo não implementado pois não existe a rotina para cancelar a Abertura, 
   * pois a folha é aberta automatica na virada mensal e não pode ser cancelada.
   * @return 
   */
  public function cancelarAbertura(){
    return false;
  }

  /**
   * Realiza o cancelamento do fechamento da Folha Salario, as seguintes 
   * regras devem ser respeitadas:
   * - Não pode existir uma folha suplementar
   * - Folha informada estar fechada
   * - Não pode existir outra folha do mesmo tipo em aberto
   * - A folha informada não pode estar empenhada
   * @return boolean
   */
  public function cancelarFechamento() {
  
    if (FolhaPagamentoSuplementar::hasFolha()) {
      throw new DBException(_M(self::MENSAGENS . "erro_existe_suplementar"));
    }

    return parent::cancelarFechamento();
  }

  /**
   * Retorna todas as folhas de salário fechadas na compentência
   *
   * @param  DBCompetencia $oCompetencia Competencia da Folha
   * @return array folhas de pagamentos de salários 
   */
  public static function getFolhasFechadasCompetencia( DBCompetencia $oCompetencia ) {
    return FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO);
  }
}
