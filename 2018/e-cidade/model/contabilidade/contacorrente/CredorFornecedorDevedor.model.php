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

require_once ("interfaces/IContaCorrente.interface.php");
require_once ("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");

/**
 * Model responsavel pelo credor/fornecedor/devedor de contas correntes
 * @author     Rafael Lopes <rafael.lopes@dbseller.com.br>
 * @package    contabilidade
 * @subpackage contacorrente
 * @version    1.0 $
 */
class CredorFornecedorDevedor extends ContaCorrenteBase implements IContaCorrente {

  /**
   * dados do objeto CGM
   * @var object
   */
  private $oCgm;

  /**
   * Registro na tabela contacorrente
   * @var integer
   */
  const CONTA_CORRENTE = 3;

  /**
   * Seta os atributos necessбrios para o funcionamento da classe
   * Chamando o construtor da classe abstrada
   * @param integer $iCodigoLancamento               - Cуdigo do Lanзamento (conlancamval)
   * @param integer $iCodigoReduzido                 - Cуdigo reduzido da conta no plano de contas PCASP
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Objeto de Lanзamento Auxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);
    return true;
  }

  /**
   * metodo que incluira registros na contacorrentedetalhe
   * relativos ao  CC03
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("ERRO [1] - Nгo foi encontrada transaзгo com o banco de dados. Procedimento abortado.");
    }

    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");

    $iAnoUsuConplano = $this->getContaPlano()->getAno();
    $iReduzido       = $this->getContaPlano()->getReduzido();
    $iInstituicao    = $this->getInstituicao()->getSequencial();
    $iNumCgm         = $this->oLancamentoAuxiliar->getFavorecido();
    /**
     * @todo - verificar esta regra com o Leandro
     *         caso nao for informado cgm buscamos o cgm da prefeitura
     *         pode gerar problema caso haja bug no sistema, e nao seja lancado cgm
     *         vai gerar no cgm da prefeitura
     */
    if ( empty($iNumCgm) ) {

      return true;
//       $oDaoDBConfig    = db_utils::getDao('db_config');
//       $sSqlPrefeitura  = $oDaoDBConfig->sql_query_file(null,'numcgm',null,"prefeitura is true");
//       $rsCgmPrefeitura = $oDaoDBConfig->sql_record($sSqlPrefeitura);
//       if ($oDaoDBConfig->numrows == 0) {
//         throw new BusinessException("Nгo foi possнvel localizar CGM para lanзamentos do conta corrente");
//       }
//       $iNumCgm = db_utils::fieldsMemory($rsCgmPrefeitura, 0)->numcgm;
    }


    /**
     * Verificamos se jб existem os dados na tabela contacorrentedetalhe
     */
    $sWhere  = "     c19_contacorrente       = " . self::CONTA_CORRENTE;
    $sWhere .= " and c19_instit              = {$iInstituicao}";
    $sWhere .= " and c19_reduz               = {$iReduzido}";
    $sWhere .= " and c19_conplanoreduzanousu = {$iAnoUsuConplano}";
    $sWhere .= " and c19_numcgm              = {$iNumCgm}";

    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhere);
    $rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);

    /**
     * Caso jб exista sу precisamos fazer o vнnculo com a tabela contacorrentedetalheconlancamval
     */
    if ($oDaoContaCorrenteDetalhe->numrows == 1) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);
      $this->vincularLancamentos($iContaCorrenteDetalheSequencial, $sTipoLancamento);
    } else {

      /**
       * Se nгo, incluнmos na contacorrentedetalhe e em seguida fazemos o vнnculo com a
       * contacorrentedetalheconlancamval
       */
      $oDaoContaCorrenteDetalhe->c19_sequencial          = null;
      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      $oDaoContaCorrenteDetalhe->c19_numcgm              = $iNumCgm;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsuConplano;
      $oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == '0') {
        throw new DBException("Nгo foi possнvel inserir os dados da conta corrente {$this->oContaCorrente->getDescricao()}.");
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
  }

  /**
   * Seta o Objeto Conta Bancaria
   * @param ContaBancaria $oContaBancaria
   */
  public function setContaBancaria(ContaBancaria $oContaBancaria) {
    $this->oContaBancaria = $oContaBancaria;
  }

  /**
   * Retorna o objeto conta corrente
   * @return ContaCorrente
   */
  public function getContaCorrente() {
    return $this->oContaCorrente;
  }
}
?>