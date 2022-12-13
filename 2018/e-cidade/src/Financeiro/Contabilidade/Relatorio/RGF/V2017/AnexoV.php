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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017;

/**
 * Class AnexoV
 * @package Ecidade\Financeiro\Contabilidade\Relatorio\RGF\V2017
 */
class AnexoV extends \RelatoriosLegaisBase
{
    /**
     * @type integer
     */
    const CODIGO_RELATORIO = 174;

    /**
     * Linhas que devem ser processadas no relatório
     * @type array
     */
    private $linhasAnaliticas = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15);

    /**
     * AnexoV constructor.
     * @param int $anoSessao
     * @param int $codigoPeriodo
     */
    public function __construct($anoSessao, $codigoPeriodo)
    {
        parent::__construct($anoSessao, self::CODIGO_RELATORIO, $codigoPeriodo);
    }

    /**
     * Retorna os dados previamente tratados para impressão do relatório
     * @return array
     * @throws \DBException
     * @throws \Exception
     * @throws \ParameterException
     */
    public function getDados()
    {

        parent::getDados();

        $this->processarColunaCaixaBruta();
        $this->processarColunaRestosAPagar();
        $this->processarColunaBalanceteDespesa();
        $this->processarObrigacoesFinanceiras();

        foreach ($this->linhasAnaliticas as $linha) {
            $this->processarFormulaDaLinhaEColuna($linha, 6);
        }

        $this->processarFormulaDaLinha(1);
        $this->processarFormulaDaLinha(14);
        $this->processarFormulaDaLinha(16);

        $this->arredondarValores(2);

        return $this->aLinhasConsistencia;
    }

    /**
     * Processa os valores para a coluna Disponibilidade de Caixa Bruta
     */
    protected function processarColunaCaixaBruta()
    {

        $sWhereVerificacao = "c61_instit in({$this->getInstituicoes()})";
        $rsBalanceteVerificacao = db_planocontassaldo_matriz($this->iAnoUsu, $this->getDataInicial()->getDate(),
            $this->getDataFinal()->getDate(), false, $sWhereVerificacao, '', 'true', 'false');

        foreach ($this->linhasAnaliticas as $linha) {

            $oLinha = $this->aLinhasConsistencia[$linha];

            /* Pula o registro caso não exista recurso configurado para linha */
            if (empty($oLinha->parametros->orcamento->recurso->valor)) {
                continue;
            }

            $oLinha->colunas[0]->o116_formula = "(substr(#estrutural, 0, 1) == 1 && #sinal_final == 'C') || (substr(#estrutural, 0, 1) == 2 && #sinal_final == 'D') ? #saldo_final *= -1 : #saldo_final";

            $oLinha->parametros->contas_origem = $oLinha->parametros->contas;
            $oLinha->parametros->contas = array();
            $oLinha->parametros->contas[] = (object)array(
                'estrutural' => '111000000000000',
                'nivel' => '3',
                'exclusao' => '',
                'indicador' => ''
            );

            $oLinha->parametros->contas[] = (object)array(
                'estrutural' => '114000000000000',
                'nivel' => '3',
                'exclusao' => '',
                'indicador' => ''
            );

            $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(0));
            \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao, $oLinha, $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO);

            $this->limparEstruturaBalanceteVerificacao();
            $oLinha->parametros->contas = $oLinha->parametros->contas_origem;
        }
    }

    /**
     * Processa os valores para as colunas B|D
     */
    protected function processarColunaRestosAPagar()
    {

        $liquidado_anterior = "(#e91_vlremp - #e91_vlranu - #e91_vlrliq) + (#e91_vlrliq - #e91_vlrpag)";
        $apagargeral = "(({$liquidado_anterior}) - #vlranu - #vlrpag - #vlrpagnproc)";
        $aliquidargeral = "(#e91_vlremp - ((#e91_vlranu + #vlranu) + (#vlrliq + #e91_vlrliq - #vlranuliq)))";
        $formulaColuna_B = "abs({$apagargeral} - {$aliquidargeral})";

        $oDaoRestosAPagar = new \cl_empresto();
        $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
        $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo($this->iAnoUsu, $sWhereRestoPagar,
            $this->getDataInicial()->getDate(), $this->getDataFinal()->getDate());
        $rsRestosPagar = db_query($sSqlRestosaPagar);

        foreach ($this->linhasAnaliticas as $linha) {

            $stdLinha = $this->aLinhasConsistencia[$linha];
            /* Pula o registro caso não exista recurso configurado para linha */
            if (empty($stdLinha->parametros->orcamento->recurso->valor)) {
                continue;
            }
            $stdLinha->colunas[1]->o116_formula = $formulaColuna_B;
            $stdLinha->colunas[3]->o116_formula = $aliquidargeral;

            $aColunasProcessar = $this->getColunasPorLinha($stdLinha, array(1, 3));
            \RelatoriosLegaisBase::calcularValorDaLinha($rsRestosPagar, $stdLinha, $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_RESTO);
        }

    }

    /**
     * Processa a coluna C do relatorio
     */
    protected function processarColunaBalanceteDespesa()
    {

        $sWhereDespesa = " o58_instit in({$this->getInstituicoes()})";
        $rsBalanceteDespesa = db_dotacaosaldo(8, 2, 2, true, $sWhereDespesa, $this->iAnoUsu,
            $this->getDataInicial()->getDate(), $this->getDataFinal()->getDate());

        foreach ($this->linhasAnaliticas as $linha) {

            $oLinha = $this->aLinhasConsistencia[$linha];

            if (empty($oLinha->parametros->orcamento->recurso->valor)) {
                continue;
            }
            $oLinha->colunas[2]->o116_formula = '#atual_a_pagar_liquidado';
            $oLinha->colunas[7]->o116_formula = '((#empenhado_acumulado - #anulado_acumulado) - #liquidado_acumulado)';
            $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(2, 7));
            \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa, $oLinha, $aColunasProcessar,
                \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);

            $this->limparEstruturaBalanceteDespesa();
        }

    }

    /**
     * @throws \DBException
     * @throws \ParameterException
     */
    protected function processarObrigacoesFinanceiras()
    {

        $daoReduzido = new \cl_conplanoreduz();
        foreach ($this->linhasAnaliticas as $linha) {

            $stdLinha = $this->aLinhasConsistencia[$linha];
            $recursos = $stdLinha->parametros->orcamento->recurso->valor;
            if (empty($recursos)) {
                continue;
            }
            $instituicoesSelecionadas = $this->getInstituicoes(true);
            foreach ($instituicoesSelecionadas as $instituicao) {

                $where = implode(' and ', array(
                    "conplanoreduz.c61_instit = {$instituicao->getCodigo()}",
                    "conplanoreduz.c61_anousu = {$this->getAno()}",
                    "(((c60_estrut ilike '8211302%') or (c60_estrut ilike '8211303%')))",
                ));
                $buscaReduzido = $daoReduzido->sql_query_plano_reduzido('c61_reduz', $where);
                $resBuscaReduzido = db_query($buscaReduzido);
                if (!$resBuscaReduzido) {
                    throw new \DBException("Ocorreu um erro ao consultar o reduzido.");
                }
                $totalReduzidos = pg_num_rows($resBuscaReduzido);
                if ($totalReduzidos === 0) {
                    continue;
                }

                for ($rowReduzido = 0; $rowReduzido < $totalReduzidos; $rowReduzido++) {

                    $codigoReduzido = \db_utils::fieldsMemory($resBuscaReduzido, $rowReduzido)->c61_reduz;
                    $relatorio = new \RelatorioDisponibilidadeFinanceira($instituicao, $this->getDataInicial(),
                        $this->getDataFinal(), \RelatorioDisponibilidadeFinanceira::AGRUPAMENTO_RECURSO);
                    $relatorio->setReduzido($codigoReduzido);
                    $relatorio->setRecursos($recursos);
                    $dadosRelatorio = $relatorio->getDadosSimplificado();
                    $stdLinha->financeira += $dadosRelatorio->totalDebitoMenosCredito;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getLinhasAnaliticas()
    {
        return $this->linhasAnaliticas;
    }
}