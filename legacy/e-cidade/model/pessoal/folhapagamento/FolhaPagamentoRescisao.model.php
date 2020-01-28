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
 * Classe representa a folha de pagamento Rescisão
 * 
 * @author $Author: dbvinicius.martins $
 * @version $Revision: 1.6 $
 */
class FolhaPagamentoRescisao extends FolhaPagamento {
  
  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamentoRescisao.';

  /**
   * Contrutor da classe FolhaPagamentoAdiantamento
   * 
   * @param Integer $iSequencial
   */
  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_RESCISAO);
  }

  /**
   * Retorna a ultima folha aberta do tipo rescisão
   * 
   * @example FolhaPagamentoRescisao::getFolhaAberta()
   * @return FolhaPagamentoRescisao
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_RESCISAO, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamentoRescisao($iCodigoFolha);
    }

    return false;
  }

  /**
   * Retorna se há uma folha aberta
   * 
   * @example FolhaPagamentoRescisao::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta( DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_RESCISAO, $oCompetencia);
  }

  /**
   * Verifica se existe algum registro do tipo folha Rescisao na
   * competencia passada por parametro ou caso não seja passado
   * pega a competencia atual
   * 
   * @param DBCompetencia $oCompetencia Opcional
   * @return Boolean
   */
  public static function hasFolha(DBCompetencia $oCompetencia = null) {

    if ($oCompetencia) {
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_RESCISAO, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_RESCISAO,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

 /**
  * Retorna a ultima folha
  * @return FolhaPagamentoRescisao
  */
  public static function getUltimaFolha() {
    return new FolhaPagamentoRescisao(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_RESCISAO)); 
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
         * Verifica se NÃO existe folha de rescisão.
         */
        if (!FolhaPagamentoRescisao::hasFolha()) {

          /**
           * Cria folha rescisão.
           */
          $oFolhaRescisao = new FolhaPagamentoRescisao();
          $oFolhaRescisao->setNumero(0);
          $oFolhaRescisao->setCompetenciaFolha($oCompetencia);
          $oFolhaRescisao->setCompetenciaReferencia($oCompetencia);
          $oFolhaRescisao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
          $oFolhaRescisao->setDescricao("Folha Rescisão - {$oCompetencia->getAno()}/{$oCompetencia->getMes()}");
          $oFolhaRescisao->salvar();

          /**
           * Verifica se NÃO existe folha de rescisão aberta.
           */
        } elseif (!FolhaPagamentoRescisao::hasFolhaAberta($oCompetencia)) {
          throw new BusinessException(_M(FolhaPagamentoRescisao::MENSAGENS . "fechamento_folha_fechada"));
        }
     } else {
      throw new BusinessException(_M(FolhaPagamento::MENSAGENS . "ponto_nao_inicializado"));
    }
  }

  /**
   * Função para fechamento da folha de Rescisão
   * @return boolean
   */
  public function fechar() {

    $this->fecharFolha();
    return true;
  }

  /**
   * Função para cancelamento da abertura da folha.
   */
  public function cancelarAbertura() {}

 /**
  * Este função é sobrecarga
  *
  * @return boolean
  * @throws DBException
  */
  public function cancelarFechamento() {
    parent::cancelarFechamento();
  }

}