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

require_once "model/contabilidade/contacorrente/ContaCorrenteBase.model.php";
require_once "interfaces/IContaCorrente.interface.php";

/**
 * Model para controle da conta corrente originada por contrato
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage contacorrente
 * @version $Revision: 1.4 $
 */
class ContaCorrenteContrato extends ContaCorrenteBase implements IContaCorrente {

  /**
   * Conta corrente cadastrada no sistema
   * @var integer
   */
  const CONTA_CORRENTE = 25;

  /**
   * Contrato
   * @var Acordo
   */
  private $oContrato;

  /**
   * Seta as propriedades para a utilizaзгo da conta corrente Contrato
   * @param integer $iCodigoLancamento
   * @param integer $iCodigoReduzido
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return boolean
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);
    if ( !$oLancamentoAuxiliar->getAcordo() instanceof Acordo) {
      $oLancamentoAuxiliar->setAcordo(new Acordo($oLancamentoAuxiliar->getAcordo()));
    }
    $this->setAcordo($oLancamentoAuxiliar->getAcordo());
    return true;
  }

  /**
   * Salva ou atualiza os dados da conta corrente
   * @see IContaCorrente::salvar()
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nгo foi encontrada transaзгo com o banco de dados. Procedimento abortado.");
    }

    $iAnoUsuConplano = $this->getContaPlano()->getAno();
    $iReduzido       = $this->getContaPlano()->getReduzido();
    $iInstituicao    = $this->getInstituicao()->getSequencial();

    /**
     * Verificamos se jб existem os dados na tabela contacorrentedetalhe
    */
    $sWhere  = "     c19_contacorrente       = ".self::CONTA_CORRENTE;
    $sWhere .= " and c19_instit              = {$iInstituicao}";
    $sWhere .= " and c19_reduz               = {$iReduzido}";
    $sWhere .= " and c19_conplanoreduzanousu = {$iAnoUsuConplano}";
    $sWhere .= " and c19_acordo              = {$this->getAcordo()->getCodigoAcordo()}";
    $sWhere .= " and c19_numcgm              = {$this->getAcordo()->getContratado()->getCodigo()}";

    $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
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
      $oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsuConplano;
      $oDaoContaCorrenteDetalhe->c19_acordo              = $this->getAcordo()->getCodigoAcordo();
      $oDaoContaCorrenteDetalhe->c19_numcgm              = $this->getAcordo()->getContratado()->getCodigo();
      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == "0") {

        $sMensagemErro  = "Nгo foi possнvel inserir os dados da conta corrente {$this->oContaCorrente->getDescricao()}.";
        $sMensagemErro .= "\\n{$oDaoContaCorrenteDetalhe->erro_msg}";
        throw new DBException($sMensagemErro);
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
    return true;
  }

  /**
   * Seta o um acordo do sistema
   * @param Acordo $oContrato
   */
  public function setAcordo(Acordo $oContrato) {
    $this->oContrato = $oContrato;
  }

  /**
   * Retorna o contrato do sistema
   * @return Acordo
   */
  public function getAcordo() {
    return $this->oContrato;
  }
}
?>