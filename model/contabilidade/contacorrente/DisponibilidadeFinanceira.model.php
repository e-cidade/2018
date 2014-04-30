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
 * Model responsavel pela disponibilidade financeira de contas correntes
 * @author     Rafael Lopes <rafael.lopes@dbseller.com.br>
 * @package    contabilidade
 * @version    1.0 $
 */
class DisponibilidadeFinanceira extends ContaCorrenteBase implements IContaCorrente {

  /**
   * Recurso
   * @var Recurso
   */
  private $oRecurso;

  /**
   * ojeto do caracteristica peculiar
   * @var CaracteristicaPeculiar
   */
  private $oCaracteristicaPeculiar;

  /**
   * Constante que define a conta corrente
   * @var integer
   */
  const CONTA_CORRENTE = 1;

  /**
   * @param integer $iCodigoLancamento
   * @param integer $iCodigoReduzido
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);
    return true;
  }

  /**
   * metodo que incluira registros na contacorrentedetalhe
   * CC01
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("ERRO [1] - Nгo foi encontrado transaзгo com o banco de dados. Procedimento abortado.");
    }

    $sMetodoCaracteristicaPeculiar = "getCaracteristicaPeculiarCredito";
    if ($this->sTipoLancamento == "D") {
      $sMetodoCaracteristicaPeculiar = "getCaracteristicaPeculiarDebito";
    }

    if ($this->oLancamentoAuxiliar instanceof LancamentoAuxiliarEmpenhoPassivo ||
        $this->oLancamentoAuxiliar instanceof LancamentoAuxiliarEmpenho ||
        $this->oLancamentoAuxiliar instanceof LancamentoAuxiliarArrecadacaoReceita ||
        $this->oLancamentoAuxiliar instanceof LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria ||
    		$this->oLancamentoAuxiliar instanceof LancamentoAuxiliarContaCorrente ) {
        $sMetodoCaracteristicaPeculiar = "getCaracteristicaPeculiar";

    }

    $iCodigoRecurso = null;
    $oReflexao      = new ReflectionClass($this->oLancamentoAuxiliar);
    if ($oReflexao->hasMethod("getCodigoRecurso")) {
      $iCodigoRecurso = $this->oLancamentoAuxiliar->getCodigoRecurso();
    }

    if (empty($iCodigoRecurso)) {
      $iCodigoRecurso = $this->getContaPlano()->getRecurso();
    }
    $iAnoSessao               = db_getsession("DB_anousu");
    $oDaoContaCorrenteDetalhe = db_utils::getDao('contacorrentedetalhe');
    $sWhereContaCorrente      = "     c19_contacorrente       = " . self::CONTA_CORRENTE;
    $sWhereContaCorrente     .= " and c19_orctiporec          = {$iCodigoRecurso}";
    $sWhereContaCorrente     .= " and c19_instit              = {$this->getInstituicao()->getSequencial()}";
    $sWhereContaCorrente     .= " and c19_concarpeculiar      = '{$this->oLancamentoAuxiliar->$sMetodoCaracteristicaPeculiar()}'";
    $sWhereContaCorrente     .= " and c19_reduz               = {$this->iCodigoReduzido}";
    $sWhereContaCorrente     .= " and c19_conplanoreduzanousu = {$this->getContaPlano()->getAno()}";

    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhereContaCorrente);
    $rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);

    if ($oDaoContaCorrenteDetalhe->numrows == 1) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);
      $this->vincularLancamentos($iContaCorrenteDetalheSequencial, $sTipoLancamento);
    } else {

      $oDaoContaCorrenteDetalhe = db_utils::getDao("contacorrentedetalhe");
      $oDaoContaCorrenteDetalhe->c19_sequencial          = null;
      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_orctiporec          = $iCodigoRecurso;
      $oDaoContaCorrenteDetalhe->c19_instit              = $this->getInstituicao()->getSequencial();
      $oDaoContaCorrenteDetalhe->c19_concarpeculiar      = "'{$this->oLancamentoAuxiliar->$sMetodoCaracteristicaPeculiar()}'";
      $oDaoContaCorrenteDetalhe->c19_reduz               = $this->getContaPlano()->getReduzido();
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $this->getContaPlano()->getAno();
      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == "0") {
        throw new DBException("ERRO [2] - Nгo foi possнvel salvar os dados da disponibilidade financeira. {$oDaoContaCorrenteDetalhe->erro_msg}");
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
    return true;
  }

  /**
   * Retorna o objeto do Recurso
   * @return object
   */
  public function getRecurso()  {
    return $this->oRecurso;
  }

  /**
   * Recebe o objeto Recurso
   * @param $oRecurso
   */
  public function setRecurso(Recurso $oRecurso) {
    $this->oRecurso = $oRecurso;
  }

  /**
   * Retorna o objeto do Caracteristica Peculiar
   * @return object
   */
  public function getCaracteristicaPeculiar()  {
    return $this->oCaracteristicaPeculiar;
  }

  /**
   * Recebe o objeto Caracteristica Peculiar
   * @param $oCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar(CaracteristicaPeculiar $oCaracteristicaPeculiar) {
    $this->oCaracteristicaPeculiar = $oCaracteristicaPeculiar;
  }
}
?>