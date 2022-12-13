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


require_once("model/contabilidade/contacorrente/DomicilioBancario.model.php");
require_once("model/contabilidade/contacorrente/DomicilioBancarioRepository.model.php");
require_once("model/contabilidade/contacorrente/AdiantamentoConcessao.model.php");
require_once("model/contabilidade/contacorrente/AdiantamentoConcessaoRepository.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedor.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedorRepository.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceira.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceiraRepository.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteContrato.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteContratoRepository.model.php");

/**
 * Factory que retorna o objeto adequado com os dados para os relatórios
 * @package contabilidade
 * @subpackage contacorrente
 * @author Acácio Schneider <acacio.schneider@dbseller.com.br>
 */
class ContaCorrenteRepositoryFactory {

  public function __construct(){}

  /**
   * @param integer $iConta   - Sequencial da tabela contacorrente, conforme este parâmetro, criamos os objetos
   * @param string $dtInicial - Data inicial para o relatório
   * @param string $dtFinal   - Data final para o relatório
   * @return Object           - Objeto criado com os dados para o relatório, conforme filtros
   */
  public static function getInstance($iConta, $dtInicial, $dtFinal) {

    switch($iConta) {

      case DomicilioBancario::CONTA_CORRENTE:

        $oRetorno = new DomicilioBancarioRepository($dtInicial, $dtFinal);
        break;

      case AdiantamentoConcessao::CONTA_CORRENTE:

        $oRetorno = new AdiantamentoConcessaoRepository($dtInicial, $dtFinal);
        break;

      case CredorFornecedorDevedor::CONTA_CORRENTE:

        $oRetorno = new CredorFornecedorDevedorRepository($dtInicial, $dtFinal);
        break;

      case DisponibilidadeFinanceira::CONTA_CORRENTE:

        $oRetorno = new DisponibilidadeFinanceiraRepository($dtInicial, $dtFinal);
        break;

      case ContaCorrenteContrato::CONTA_CORRENTE:

          $oRetorno = new ContaCorrenteContratoRepository($dtInicial, $dtFinal);
          break;


    }

    return $oRetorno;
  }
}

?>