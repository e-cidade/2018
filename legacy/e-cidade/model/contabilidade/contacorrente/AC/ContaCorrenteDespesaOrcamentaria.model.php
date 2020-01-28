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
 * Class ContaCorrenteDespesaOrcamentaria
 */
class ContaCorrenteDespesaOrcamentaria extends ContaCorrenteBase {

  /**
   * @var integer
   */
  const CONTA_CORRENTE = 102;

  /**
   * Constantes armazenadas conforme o manual de referencia do TCE/AC
   */
  const PESSOA_FISICA   = 1;
  const PESSOA_JURIDICA = 2;

  /**
   * Campos que serão utilizados por esta conta corrente
   * @var array
   */
  public static $aCamposDetalhe = array(
    'c19_sequencial',
    'c19_contacorrente',
    'c19_orctiporec',
    'c19_instit',
    'c19_reduz',
    'c19_numemp',
    'c19_numcgm',
    'c19_conplanoreduzanousu',
    'c19_estrutural',
    'c19_orcdotacao',
    'c19_orcdotacaoanousu'
  );

  /**
   * @param int $iCodigoLancamento
   * @param int $iCodigoReduzido
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   */
  public function __construct($iCodigoLancamento, $iCodigoReduzido, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    parent::__construct($iCodigoLancamento, $iCodigoReduzido, $oLancamentoAuxiliar);
    $this->oContaCorrente = ContaCorrenteRepository::getContaCorrenteByCodigo(self::CONTA_CORRENTE);

    $this->validarInformacoes();
  }

  public function salvar($dtLancamento = null) {

    $iAnoUsuConplano = $this->getContaPlano()->getAno();
    $iReduzido       = $this->getContaPlano()->getReduzido();
    $iInstituicao    = $this->getInstituicao()->getSequencial();

    $oContaCorrenteDetalhe = $this->oContaCorrenteDetalhe;

    $aWhere = array(
       "c19_contacorrente       = ".self::CONTA_CORRENTE
      ,"c19_orctiporec          = {$oContaCorrenteDetalhe->getRecurso()->getCodigo()}"
      ,"c19_instit              = {$iInstituicao}"
      ,"c19_reduz               = {$iReduzido}"
      ,"c19_conplanoreduzanousu = {$iAnoUsuConplano}"
      ,"c19_estrutural          = '{$oContaCorrenteDetalhe->getDotacao()->getElemento()}'"
      ,"c19_orcdotacao          = {$oContaCorrenteDetalhe->getDotacao()->getCodigo()}"
      ,"c19_orcdotacaoanousu    = {$oContaCorrenteDetalhe->getDotacao()->getAno()}"
    );

    if ( $oContaCorrenteDetalhe->getEmpenho() instanceof EmpenhoFinanceiro) {

      $aWhere[] = "c19_numemp = {$oContaCorrenteDetalhe->getEmpenho()->getNumero()}";
      $aWhere[] = "c19_numcgm = {$oContaCorrenteDetalhe->getEmpenho()->getFornecedor()->getCodigo()}";

    } else if ($oContaCorrenteDetalhe->getCredor() instanceof CgmBase) {

      $aWhere[] = "c19_numcgm = {$oContaCorrenteDetalhe->getCredor()->getCodigo()}";
    }


    $sWhere = implode(' and ', $aWhere);
    $oDaoContaCorrenteDetalhe = new cl_contacorrentedetalhe();
    $sSqlContaCorrenteDetalhe = $oDaoContaCorrenteDetalhe->sql_query_file(null, "c19_sequencial", null, $sWhere);
    $rsContaCorrenteDetalhe   = db_query($sSqlContaCorrenteDetalhe);
    if (!$rsContaCorrenteDetalhe) {
      throw new BusinessException("Não foi possível buscar os dados da conta corrente {$this->oContaCorrente->getDescricao()}.");
    }


    if (pg_num_rows($rsContaCorrenteDetalhe) == 1) {

      $iContaCorrenteDetalheSequencial = db_utils::fieldsMemory($rsContaCorrenteDetalhe, 0)->c19_sequencial;
      $sTipoLancamento                 = $this->atualizarSaldo($iContaCorrenteDetalheSequencial, $dtLancamento);
      $this->vincularLancamentos($iContaCorrenteDetalheSequencial, $sTipoLancamento);

    } else {

      $oDaoContaCorrenteDetalhe->c19_contacorrente       = self::CONTA_CORRENTE;
      $oDaoContaCorrenteDetalhe->c19_orctiporec          = $this->oContaCorrenteDetalhe->getRecurso()->getCodigo();
      $oDaoContaCorrenteDetalhe->c19_instit              = $iInstituicao;
      $oDaoContaCorrenteDetalhe->c19_reduz               = $iReduzido;
      $oDaoContaCorrenteDetalhe->c19_conplanoreduzanousu = $iAnoUsuConplano;
      $oDaoContaCorrenteDetalhe->c19_estrutural          = "'{$this->oContaCorrenteDetalhe->getDotacao()->getElemento()}'";
      $oDaoContaCorrenteDetalhe->c19_orcdotacao          = $this->oContaCorrenteDetalhe->getDotacao()->getCodigo();
      $oDaoContaCorrenteDetalhe->c19_orcdotacaoanousu    = $this->oContaCorrenteDetalhe->getDotacao()->getAno();

      if ($oContaCorrenteDetalhe->getEmpenho() instanceof EmpenhoFinanceiro) {
        $oDaoContaCorrenteDetalhe->c19_numemp = $this->oContaCorrenteDetalhe->getEmpenho()->getNumero();
        $oDaoContaCorrenteDetalhe->c19_numcgm = $this->oContaCorrenteDetalhe->getEmpenho()->getFornecedor()->getCodigo();
      } else if ($oContaCorrenteDetalhe->getCredor() instanceof CgmBase) {
        $oDaoContaCorrenteDetalhe->c19_numcgm = $oContaCorrenteDetalhe->getCredor()->getCodigo();
      }

      $oDaoContaCorrenteDetalhe->incluir(null);

      if ($oDaoContaCorrenteDetalhe->erro_status == "0") {

        $sMensagemErro  = "Não foi possível inserir os dados da conta corrente {$this->oContaCorrente->getDescricao()}.";
        $sMensagemErro .= "\\n{$oDaoContaCorrenteDetalhe->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }

      $sTipoLancamento = $this->atualizarSaldo($oDaoContaCorrenteDetalhe->c19_sequencial, $dtLancamento);
      $this->vincularLancamentos($oDaoContaCorrenteDetalhe->c19_sequencial, $sTipoLancamento);
    }
    return true;
  }

  /**
   * Método que valida se as propriedades necessárias para a criação do conta corrente foram informadas corretamente.
   * @throws ParameterException
   */
  private function validarInformacoes() {

    if ( ! $this->oContaCorrenteDetalhe->getEmpenho() instanceof EmpenhoFinanceiro &&
         ! $this->oContaCorrenteDetalhe->getCredor()  instanceof CgmBase) {
      throw new ParameterException("Empenho e credor não informado.");
    }

    if ( ! $this->oContaCorrenteDetalhe->getDotacao() instanceof Dotacao) {
      throw new ParameterException("Dotação orçamentária não informada.");
    }

    if ( ! $this->oContaCorrenteDetalhe->getRecurso() instanceof Recurso) {
      throw new ParameterException("Recurso não informado.");
    }
    return true;
  }
}