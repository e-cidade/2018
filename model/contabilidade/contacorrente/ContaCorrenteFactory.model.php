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
 * Model responsável por descobrir a qual conta corrente o reduzido do plano de contas pertence
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 * @version $Revision: 1.11 $
 * @package contabilidade
 * @subpackage contacorrente
 */
class ContaCorrenteFactory {

  private function __construct() {}

  /**
   * Descobre a que conta corrente o código reduzido do plano de contas pertence
   * @param integer $iCodigoLancamento - Código da tabela conlancamval
   * @param integer $iCodigoReduzido   - Código reduzido do plano de contas PCASP
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Classe que implemente a interface ILancamentoAuxiliar
   * @return IContaCorrente - Retorna um objeto conta corrente que implementa a inteface IContaCorrente
   */
  public static function getInstance($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $iAnoSessao        = db_getsession('DB_anousu');
    $sChaveRepository  = "cc{$iCodigoReduzido}{$iAnoSessao}";
    $oStdContaCorrente = DBRegistry::get($sChaveRepository);
    if ($oStdContaCorrente === null) {

      $oDaoConplanoContaCorrente = db_utils::getDao("conplanocontacorrente");
      $sWhereContaCorrente       = "    conplanoreduz.c61_reduz  = {$iCodigoReduzido} ";
      $sWhereContaCorrente      .= "and conplanoreduz.c61_anousu = {$iAnoSessao}";
      $sSqlBuscaContaCorrente    = $oDaoConplanoContaCorrente->sql_query_conplano_contacorrente(null,
                                                                                                "contacorrente.*",
                                                                                                null,
                                                                                                $sWhereContaCorrente);
      $rsBuscaContaCorrente      = $oDaoConplanoContaCorrente->sql_record($sSqlBuscaContaCorrente);

      /**
       * Algumas contas podem não ter vínculo com a conta corrente
       */
      if ($oDaoConplanoContaCorrente->numrows == 0) {

        DBRegistry::add($sChaveRepository, false);
        return false;
      }


      /**
       * Verificamos se o sequencial da conta corrente está no array associativo assima. Caso esteja retornamos o objeto,
       * do contrário lançamos uma exception parando o processo
       */
      $oStdContaCorrente = db_utils::fieldsMemory($rsBuscaContaCorrente, 0);
      DBRegistry::add($sChaveRepository, $oStdContaCorrente);
    }

    /**
     * Nao existe conta corrente para a conta selecionada.
     * fou incluido no repositorio como false;
     */
    if (!$oStdContaCorrente instanceof stdClass) {
      return false;
    }
    $aClassesContaCorrente = array('1'  => 'DisponibilidadeFinanceira',
                                   '2'  => 'DomicilioBancario',
                                   '3'  => 'CredorFornecedorDevedor',
                                   '19' => 'AdiantamentoConcessao',
                                   '25' => 'ContaCorrenteContrato');
    if (array_key_exists($oStdContaCorrente->c17_sequencial, $aClassesContaCorrente)) {

      $sNomeClasse = $aClassesContaCorrente[$oStdContaCorrente->c17_sequencial];
      require_once "model/contabilidade/contacorrente/{$sNomeClasse}.model.php";
      return new $sNomeClasse($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    } else {

      $sMensagemErro  = "A conta corrente {$oStdContaCorrente->c17_contacorrente} - {$oStdContaCorrente->c17_descricao} ";
      $sMensagemErro .= "não foi localizada.\nContate o suporte";
      throw new BusinessException($sMensagemErro);
    }
  }
}
?>