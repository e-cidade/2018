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
require_once ("interfaces/IContaCorrente.interface.php");
require_once ("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");

/**
 * @package Contabilidade
 * @subpackage contacorrente
 * @version 1.0
 */
class ContaCorrenteRestosPagar extends ContacorrenteBase implements IContaCorrente {

  /**
   * Registro na tabela contacorrente
   * @var integer
   */
  const CONTA_CORRENTE = 106;

  /**
   * Seta os atributos necessários para o funcionamento da classe
   * Chamando o construtor da classe abstrada
   *
   * @param integer $iCodigoLancamento               - Código do Lançamento (conlancamval)
   * @param integer $iCodigoReduzido                 - Código reduzido da conta no plano de contas PCASP
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar - Objeto de Lançamento Auxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);

    $this->validarInformacoes();
  }

  /**
   * Salva os dados na tabela contacorrentedetalhe
   * - se já existir, simplesmente vincula com a tabela contacorrentedetalheconlancamval
   *
   * @throws DBException
   */
  public function salvar($dtLancamento = null) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Não foi encontrada transação com o banco de dados. Procedimento abortado.");
    }

    $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();

    $iInstituicao = $this->getInstituicao()->getSequencial();
    $iReduzido    = $this->getContaPlano()->getReduzido();
    $iAnousu      = $this->getContaPlano()->getAno();
    $iEmpenho     = $this->oContaCorrenteDetalhe->getEmpenho()->getNumero();
    $oDotacao     = $this->oContaCorrenteDetalhe->getEmpenho()->getDotacao();
    $iDotacao     = $oDotacao->getCodigo();
    $iAnoDotacao  = $oDotacao->getAno();
    $sEstrutural  = $this->oContaCorrenteDetalhe->getEmpenho()->getContaOrcamento()->getEstrutural();

    /**
     * Verificamos se já existem os dados na tabela contacorrentedetalhe
     */
    $sWhere  = "     c19_contacorrente = " . self::CONTA_CORRENTE;
    $sWhere .= " and c19_instit = {$iInstituicao}";
    $sWhere .= " and c19_reduz = {$iReduzido}";
    $sWhere .= " and c19_conplanoreduzanousu = {$iAnousu}";
    $sWhere .= " and c19_numemp = {$iEmpenho}";
    $sWhere .= " and c19_orcdotacao = {$iDotacao}";
    $sWhere .= " and c19_orcdotacaoanousu = {$iAnoDotacao}";
    $sWhere .= " and c19_estrutural = '{$sEstrutural}'";

    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhere);
    $rsContaCorrenteDetalhe   = $oDaoContaCorrenteDetalhe->sql_record($sSqlContaCorrenteDetalhe);

    /**
     * Caso já exista só precisamos fazer o vínculo com a tabela contacorrentedetalheconlancamval
     */
    if ($oDaoContaCorrenteDetalhe->numrows == 1) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);

      $this->vincularLancamentos( $iContaCorrenteDetalheSequencial, $sTipoLancamento );
    } else {

      /**
       * Se não, incluímos na contacorrentedetalhe e em seguida fazemos o vínculo com a
       * contacorrentedetalheconlancamval
       */
      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      $oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnousu;
      $oDaoContaCorrenteDetalhe->c19_numemp              = $iEmpenho;
      $oDaoContaCorrenteDetalhe->c19_orcdotacao          = $iDotacao;
      $oDaoContaCorrenteDetalhe->c19_orcdotacaoanousu    = $iAnoDotacao;
      $oDaoContaCorrenteDetalhe->c19_estrutural          = "'{$sEstrutural}'";

      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == 0) {
        throw new DBException(
          "Não foi possível inserir os dados da conta corrente {$this->oContaCorrente->getDescricao()}\n " .
          $oDaoContaCorrenteDetalhe->erro_msg
        );
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }

  }

  /**
   * @return bool
   * @throws ParameterException
   */
  private function validarInformacoes() {

    if ( !$this->oContaCorrenteDetalhe->getEmpenho() instanceof EmpenhoFinanceiro) {
      throw new ParameterException("Empenho não informado para execução do conta corrente {$this->oContaCorrente->getDescricao()}.");
    }

    return true;
  }

}
