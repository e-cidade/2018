<?php
/**
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
 * Classe representa a folha de pagamento do adiantamento
 * 
 * @author $Author: dbdiogo $
 * @version $Revision: 1.6 $
 */
class FolhaPagamentoAdiantamento extends FolhaPagamento {

  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamentoAdiantamento.';

  /**
   * Contrutor da classe FolhaPagamentoAdiantamento
   * 
   * @param Integer $iSequencial
   */
  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO);
  }

  /**
   * Retorna a ultima folha aberta do tipo adiantamento
   * 
   * @example  FolhaPagamentoAdiantamento::getFolhaAberta()
   * @return  FolhaPagamentoAdiantamento Instância com todos os dados setados
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamentoAdiantamento($iCodigoFolha);
    }
    return false;
  }

  /**
   * Retorna a ultima folja de pagamento deste tipoa
   *
   * @static
   * @access 
   * @return FolhaPagamentoAdiantamento
   */
  public static function getUltimaFolha() {
    return new FolhaPagamentoAdiantamento(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO) ); 
  }

  /**
   * Retorna se há uma folha aberta
   * 
   * @example FolhaPagamentoAdiantamento::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta(DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO, $oCompetencia);
  }

  /**
   * Verifica se existe algum registro do tipo folha adiantamento na
   * competencia passada por parametro ou caso não seja passado
   * pega a competencia atual
   * 
   * @param DBCompetencia $oCompetencia Opcional
   * @return Boolean
   */
  public static function hasFolha(DBCompetencia $oCompetencia = null) {

    if ($oCompetencia) {
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

  /**
   * Retorna o ultimo número unico da folha pagamento, conforme o tipo passado.
   * 
   * @example  FolhaPagamento:getProximoNumero(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO)
   * @return   Integer  Próximo número da folha adiantamento
   */
  public static function getProximoNumero() {
    return FolhaPagamento::getProximoNumero(FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO);
  }

  /**
   * Realiza o fechamento da folha adiantamento
   * @return boolean
   */
  public function fechar() {

    $this->fecharFolha();
    return true;
  }

  /**
   * Metodo não implementado pois não existe a rotina para cancelar a Abertura, 
   * @return 
   */
  public function cancelarAbertura(){
    return false;
  }

  /**
   * Realiza o cancelamento do fechamento da Folha Adiantamento, as seguintes 
   * regras devem ser respeitadas:
   * - Não pode existir uma folha suplementar
   * - Folha informada estar fechada
   * - Não pode existir outra folha do mesmo tipo em aberto
   * - A folha informada não pode estar empenhada
   * @return boolean
   */
  public function cancelarFechamento() {
    return parent::cancelarFechamento();
  }

  /**
   * Retorna todas as folhas de adiantamento fechadas na compentência
   *
   * @param  DBCompetencia $oCompetencia Competencia da Folha
   * @return array folhas de pagamentos de adiantamentos 
   */
  public static function getFolhasFechadasCompetencia( DBCompetencia $oCompetencia ) {
    return FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO);
  }
  
  /**
   * 
   * @throws DBException
   */
  public static function verificaLiberacaoDBPref() {

    /**
     * Verifica se o ponto já foi inicializado.
     */ 
    if (FolhaPagamentoSalario::hasFolha()) {

      $oCompetencia = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

      /**
       * Verifica se NÃO existe folha adiantamento.
       */
      if (!FolhaPagamentoAdiantamento::hasFolha()) {

        /**
         * Cria folha adiantamento.
         */
        $oFolhaAdiantamento = new FolhaPagamentoAdiantamento();

        $oFolhaAdiantamento->setNumero(0);
        $oFolhaAdiantamento->setCompetenciaFolha($oCompetencia);
        $oFolhaAdiantamento->setCompetenciaReferencia($oCompetencia);
        $oFolhaAdiantamento->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
        $oFolhaAdiantamento->setDescricao("Folha adiantamento - {$oCompetencia->getAno()}/{$oCompetencia->getMes()}");
        $oFolhaAdiantamento->salvar();

      /**
       * Verifica se existe folha adiantamento aberta.
       */
      } elseif (!FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetencia)) {
       throw new BusinessException(_M(self::MENSAGENS . 'fechamento_folha_fechada'));
      }
    } else {
      throw new BusinessException(_M(parent::MENSAGENS . 'ponto_nao_inicializado'));
    }
  }
}