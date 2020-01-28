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
 * Classe representa a folha de pagamento 13º Salário
 * 
 * @author $Author: dbvinicius.martins $
 * @version $Revision: 1.6 $
 */
class FolhaPagamento13o extends FolhaPagamento {

  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamento13o.';
  
  /**
   * Contrutor da classe FolhaPagamentoAdiantamento
   * 
   * @param Integer $iSequencial
   */
  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_13o_SALARIO);
  }

  /**
   * Retorna a ultima folha aberta do tipo 13º Salário
   * 
   * @example FolhaPagamento13o::getFolhaAberta()
   * @return FolhaPagamento13o
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_13o_SALARIO, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamento13o($iCodigoFolha);
    }
    return false;
  }

  /**
   * Retorna se há uma folha aberta
   * 
   * @example FolhaPagamento13o::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta( DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_13o_SALARIO, $oCompetencia);
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
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_13o_SALARIO, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_13o_SALARIO,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

   /**
    * Retorna a ultima folha.
    *
    * @static
    * @return FolhaPagamento13o
    */
  public static function getUltimaFolha() {
    return new FolhaPagamento13o(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_13o_SALARIO)); 
  }

  /**
   * Função para fechamento da folha de 13o 
   * @return boolean
   */
  public function fechar() {
        
    $this->fecharFolha();
    return true;
  }

  /**
   * Função cancelamento da abertura da folha.
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

  /**
   * 
   * @throws DBException
   */
  public static function verificaLiberacaoDBPref() {

    /**
     * Verifica se o ponto já foi inicializado.
     */ 
    if (FolhaPagamentoSalario::hasFolha()) {

      $oCompetencia = DBPessoal::getCompetenciaFolha();

        /**
         * Verifica se NÃO existe folha de 13o.
         */
        if (!FolhaPagamento13o::hasFolha()) {

          /**
           * Cria folha do 13º salário.
           */
          $oFolha13oSalario = new FolhaPagamento13o();
          $oFolha13oSalario->setNumero(0);
          $oFolha13oSalario->setCompetenciaFolha($oCompetencia);
          $oFolha13oSalario->setCompetenciaReferencia($oCompetencia);
          $oFolha13oSalario->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));
          $oFolha13oSalario->setDescricao("Folha Rescisão - {$oCompetencia->getAno()}/{$oCompetencia->getMes()}");
          $oFolha13oSalario->salvar();

          /**
           * Verifica se NÃO existe folha do 13º salário aberta.
           */
        } elseif (!FolhaPagamento13o::hasFolhaAberta($oCompetencia)) {
          throw new BusinessException(_M(FolhaPagamento13o::MENSAGENS . "fechamento_folha_fechada"));
        }
    } else {
      throw new BusinessException(_M(FolhaPagamento::MENSAGENS . "ponto_nao_inicializado"));
    }
  }
 
}
