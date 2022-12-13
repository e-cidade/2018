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
 * classe para controle dos valores do Anexo XIV da RREO
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 *
 */

class AnexoXIRREO extends RelatoriosLegaisBase implements AnexoRREO {

  const CODIGO_RELATORIO = 107;

  private $oPdf;

  /**
   * Objeto com os dados do relatorio
   *
   * @var stdclass
   */
  protected $oDados;

  /**
   * recordset da despesa
   *
   * @var resourse
   */
  protected $rFonteDespesa;

  /**
   * recordset da receita
   *
   * @var resourse
   */
  protected $rFonteReceita;
  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
     parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  /**
   * retorna o recordset utilizado para o calculo dos dados da despesa
   * @return resourse
   */
  public function getFonteDespesa() {

    return $this->rFonteDespesa;
  }

  /**
   * define o recordset para a ultilizacao no calculo dos valores da despesa
   * @param resourse $rFonteDespesa
   */
  public function setFonteDespesa($rFonteDespesa) {

    $this->rFonteDespesa = $rFonteDespesa;
  }

  /**

   * retorna o recordset utilizado para o calculo dos dados da receita
   * @return resourse
   */
  public function getFonteReceita() {

    return $this->rFonteReceita;
  }

  /**
   * define o recordset para a ultilizacao no calculo dos valores da receita
   * @param resourse $rFonteReceita
   */
  public function setFonteReceita($rFonteReceita) {

    $this->rFonteReceita = $rFonteReceita;
  }


  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   *
   * @return array - Colecao de stdClass
   */
  public function getDados() {

     $oRetorno                        = new stdClass();
     $oRetorno->quadroreceitas        = array();
     $oRetorno->quadrodespesas        = array();
     $oRetorno->quadrosaldofinanceiro = array();

     /**
      * verificamos e somamos as linhas da receita, que atualmente são as linhas de 1-2 (na configuracao)
      * adicionaremos o totalizador junto no retorno.
      */

     $oReceitasCapital  = new stdClass();
     $oReceitasCapital->descricaolinha      = 'RECEITAS DE CAPITAL - ALIENAÇÃO DE ATIVOS (I)';
     $oReceitasCapital->previsaoatualizada  = 0;
     $oReceitasCapital->receitaatualizada   = 0;
     $oReceitasCapital->saldoaarealizar     = 0;
     $oRetorno->quadroreceitas[1]           = $oReceitasCapital;
     $iProximaLinha = 2;
     /**
      * Veriricamos se foi setado algum recordset para o processamento dos dados das linhas.
      * caso  não seja setado, devemos retornar os dados utilizando a funcao receitasaldo
      */
     $sDataInicial = "{$this->iAnoUsu}-01-01";
     $sDataFinal   = "{$this->iAnoUsu}-12-31";
     if (empty($this->rFonteReceita)) {

       $sWhere              = " o70_instit in ({$this->sListaInstit})";
       $this->rFonteReceita = db_receitasaldo(11, 1, 3, true, $sWhere, $this->iAnoUsu, $sDataInicial, $sDataFinal);
       db_query("drop table work_receita");
     }
     /**
      *
      */
     for ($i = 1; $i <= 2; $i++) {

       $oLinhaRelatorio            = new linhaRelatorioContabil($this->iCodigoRelatorio, $i);
       $oLinha                     = new stdClass();
       $oLinha->descricaolinha     = '  '.$oLinhaRelatorio->getDescricaoLinha();
       $oLinha->previsaoatualizada = 0;
       $oLinha->receitaatualizada  = 0;
       $oLinha->saldoaarealizar    = 0;

       $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
       $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
       foreach ($aValoresColunasLinhas as $oColuna) {

         $oLinha->previsaoatualizada += $oColuna->colunas[0]->o117_valor;
         $oLinha->receitaatualizada  += $oColuna->colunas[1]->o117_valor;
         /**
          * soma no totalizador 1
          */
       }
       /**
        * percorremos todas as contas da linha, e processamos os dados conforme o retornos
        */
       $iTotalLinhasReceita = pg_num_rows($this->rFonteReceita);
       $oParametro         = $oLinhaRelatorio->getParametros($this->iAnoUsu, $this->getInstituicoes());
       for ($iReceita = 0; $iReceita < $iTotalLinhasReceita; $iReceita++) {

         $oReceita   = db_utils::fieldsMemory($this->rFonteReceita, $iReceita);
         foreach ($oParametro->contas as $oEstrutural) {

           $oVerificacao    = $oLinhaRelatorio->match($oEstrutural, $oParametro->orcamento, $oReceita, 1);
           if ($oVerificacao->match) {

             if ($oVerificacao->exclusao) {

               $oReceita->saldo_inicial_prevadic     *= -1;
               $oReceita->saldo_inicial              *= -1;
               $oReceita->saldo_arrecadado_acumulado *= -1;
             }

             $oLinha->previsaoatualizada += $oReceita->saldo_inicial_prevadic;
             $oLinha->receitaatualizada  += $oReceita->saldo_arrecadado_acumulado;
           }
         }
       }

       $oLinha->saldoaarealizar  = $oLinha->previsaoatualizada - $oLinha->receitaatualizada;
       $oRetorno->quadroreceitas[1]->previsaoatualizada += $oLinha->previsaoatualizada;
       $oRetorno->quadroreceitas[1]->receitaatualizada  += $oLinha->receitaatualizada;
       $oRetorno->quadroreceitas[1]->saldoaarealizar    += $oLinha->previsaoatualizada - $oLinha->receitaatualizada;
       $oRetorno->quadroreceitas[$iProximaLinha] = $oLinha;
       $iProximaLinha++;
     }

     /**
      * montamos o quadro da despesa
      */
     if (empty($this->rFonteDespesa)) {

       $sWhere = "o58_instit in ({$this->getInstituicoes()})";
       $this->rFonteDespesa = @db_dotacaosaldo(8, 2, 3, true,
                                             $sWhere,
                                             $this->iAnoUsu,
                                             $sDataInicial,
                                             $sDataFinal);
     }
     $oAplicacaoRecurso  = new stdClass();
     $oAplicacaoRecurso->descricaolinha      = 'APLICAÇÃO DOS RECURSOS DA ALIENAÇÃO DE ATIVOS (II)';
     $oAplicacaoRecurso->dotacaoatualizada   = 0;
     $oAplicacaoRecurso->despesaliquidada    = 0;
     $oAplicacaoRecurso->inscritasemrp       = 0;
     $oAplicacaoRecurso->saldoaexecutar      = 0;
     $oRetorno->quadrodespesas[1]            = $oAplicacaoRecurso;

     $oDespesaCapital  = new stdClass();
     $oDespesaCapital->descricaolinha      = '  DESPESAS DE CAPITAL';
     $oDespesaCapital->dotacaoatualizada   = 0;
     $oDespesaCapital->despesaliquidada    = 0;
     $oDespesaCapital->inscritasemrp       = 0;
     $oDespesaCapital->saldoaexecutar      = 0;
     $oRetorno->quadrodespesas[2]          = $oDespesaCapital;

     $oDespesasRPPS  = new stdClass();
     $oDespesasRPPS->descricaolinha      = '  DESPESAS CORRENTES DOS REGIMES DE PREVIDÊNCIA';
     $oDespesasRPPS->dotacaoatualizada   = 0;
     $oDespesasRPPS->despesaliquidada    = 0;
     $oDespesasRPPS->inscritasemrp       = 0;
     $oDespesasRPPS->saldoaexecutar      = 0;
     $oRetorno->quadrodespesas[6]        = $oDespesasRPPS;
     $iProximaLinha = 3;
     for ($i = 3; $i <= 6; $i++) {

       $oLinhaRelatorio            = new linhaRelatorioContabil($this->iCodigoRelatorio, $i);
       $oLinha                     = new stdClass();
       $oLinha->descricaolinha     = '    '.$oLinhaRelatorio->getDescricaoLinha();
       $oLinha->dotacaoatualizada  = 0;
       $oLinha->despesaliquidada   = 0;
       $oLinha->inscritasemrp      = 0;
       $oLinha->saldoaexecutar     = 0;

       $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
       $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
       foreach ($aValoresColunasLinhas as $oColuna) {

         $oLinha->dotacaoatualizada += $oColuna->colunas[0]->o117_valor;
         $oLinha->despesaliquidada  += $oColuna->colunas[1]->o117_valor;
         $oLinha->inscritasemrp     += $oColuna->colunas[2]->o117_valor;
       }
       $iTotalLinhasDespesa = @pg_num_rows($this->rFonteDespesa);
       $oParametro          = $oLinhaRelatorio->getParametros($this->iAnoUsu, $this->getInstituicoes());
       for ($iDespesa = 0; $iDespesa < $iTotalLinhasDespesa; $iDespesa++) {

         $oDespesa   = db_utils::fieldsMemory($this->rFonteDespesa, $iDespesa);
         foreach ($oParametro->contas as $oConta) {

           $oVerificacao    = $oLinhaRelatorio->match($oConta, $oParametro->orcamento, $oDespesa, 2);
           if ($oVerificacao->match) {

             if ($oVerificacao->exclusao) {

               $oDespesa->dot_ini *= -1;
               $oDespesa->suplementado_acumulado *= -1;
               $oDespesa->reduzido_acumulado     *= -1;
               $oDespesa->liquidado_acumulado    *= -1;
               $oDespesa->empenhado_acumulado    *= -1;
               $oDespesa->anulado_acumulado      *= -1;
               $oDespesa->liquidado_acumulado    *= -1;
             }

             $oLinha->dotacaoatualizada += $oDespesa->dot_ini +
                                           ($oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
             $oLinha->despesaliquidada  += $oDespesa->liquidado_acumulado;
             $oLinha->inscritasemrp     += $oDespesa->empenhado_acumulado-$oDespesa->anulado_acumulado -
                                           $oDespesa->liquidado_acumulado;
           }
         }
       }
       $oLinha->saldoaexecutar = $oLinha->dotacaoatualizada - ($oLinha->despesaliquidada+$oLinha->inscritasemrp);
       /**
        * organizamos os totalizadores
        */
       if ($i < 6) {

         $oRetorno->quadrodespesas[2]->dotacaoatualizada  += $oLinha->dotacaoatualizada;
         $oRetorno->quadrodespesas[2]->despesaliquidada   += $oLinha->despesaliquidada;
         $oRetorno->quadrodespesas[2]->inscritasemrp      += $oLinha->inscritasemrp;
         $oRetorno->quadrodespesas[2]->saldoaexecutar     += $oLinha->dotacaoatualizada -
                                                            ($oLinha->despesaliquidada +$oLinha->inscritasemrp);


       } else {

         $oRetorno->quadrodespesas[6]->dotacaoatualizada  += $oLinha->dotacaoatualizada;
         $oRetorno->quadrodespesas[6]->despesaliquidada   += $oLinha->despesaliquidada;
         $oRetorno->quadrodespesas[6]->inscritasemrp      += $oLinha->inscritasemrp;
         $oRetorno->quadrodespesas[6]->saldoaexecutar     += $oLinha->dotacaoatualizada -
                                                            ($oLinha->despesaliquidada +$oLinha->inscritasemrp);
       }
       $oRetorno->quadrodespesas[1]->dotacaoatualizada  += $oLinha->dotacaoatualizada;
       $oRetorno->quadrodespesas[1]->despesaliquidada   += $oLinha->despesaliquidada;
       $oRetorno->quadrodespesas[1]->inscritasemrp      += $oLinha->inscritasemrp;
       $oRetorno->quadrodespesas[1]->saldoaexecutar     += $oLinha->dotacaoatualizada -
                                                          ($oLinha->despesaliquidada +$oLinha->inscritasemrp);

       if ($i == 6) {
         $iProximaLinha = 7;
       }
       $oRetorno->quadrodespesas[$iProximaLinha] = $oLinha;
       $iProximaLinha++;
     }
     ksort($oRetorno->quadrodespesas);
     /**
      * calculamos os valores do quadro dos saldos
      */
      $sWhereVerificacao = "c61_instit in ({$this->getInstituicoes()})";
      $rsVerificacao     = db_planocontassaldo_matriz($this->iAnoUsu,
                                                      $sDataInicial,
                                                      $sDataFinal,
                                                      false,
                                                      $sWhereVerificacao,
                                                      "",
                                                      "true",
                                                      "false"
                                                  );

     $oLinhaRelatorio            = new linhaRelatorioContabil($this->iCodigoRelatorio, 7);
     $oLinha                     = new stdClass();
     $oLinha->descricaolinha     = $oLinhaRelatorio->getDescricaoLinha();
     $oLinha->exercicioanterior  = 0;
     $oLinha->valorexercicio     = 0;
     $oLinha->saldoatual         = 0;

     $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
     $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(), $this->iAnoUsu);
     foreach ($aValoresColunasLinhas as $oColuna) {

       $oLinha->exercicioanterior += $oColuna->colunas[0]->o117_valor;
     }

     /**
      * verificamos os valores cadastrados na linha
      */
     $iTotalLinhasVerificacao = pg_num_rows($rsVerificacao);
     $oParametro   = $oLinhaRelatorio->getParametros($this->iAnoUsu, $this->getInstituicoes());
     for ($i = 0; $i < $iTotalLinhasVerificacao; $i++) {

       $oResultado = db_utils::fieldsMemory($rsVerificacao, $i);
       foreach ($oParametro->contas as $oConta) {

         $oVerificacao    = $oLinhaRelatorio->match($oConta, $oParametro->orcamento, $oResultado, 3);
         if ($oVerificacao->match) {

           if ($oVerificacao->exclusao) {

              $oResultado->saldo_anterior *= -1;
              $oResultado->saldo_final    *= -1;
           }
           $oLinha->exercicioanterior += $oResultado->saldo_anterior;
         }
       }
     }
     $oLinha->valorexercicio = $oRetorno->quadroreceitas[1]->receitaatualizada -
                              ($oRetorno->quadrodespesas[1]->despesaliquidada + $oRetorno->quadrodespesas[1]->inscritasemrp);
     $oLinha->saldoatual     =  $oLinha->exercicioanterior + $oLinha->valorexercicio;
     $oRetorno->quadrosaldofinanceiro[1] = $oLinha;
     $this->oDados  = $oRetorno;
     return $this->oDados;
  }

  /**
   * processa os dados para o relatorio simplificado
   *
   */
  public function getDadosSimplificado() {

    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->receitadecapital->valorapurado   = 0;
    $oDadosSimplificado->receitadecapital->saldoarealizar = 0;

    $oDadosSimplificado->aplicacaodosrecursos->valorapurado   = 0;
    $oDadosSimplificado->aplicacaodosrecursos->saldoarealizar = 0;
    if (empty($this->oDados)) {
      $this->getDados();
    }
    $oDadosSimplificado->receitadecapital->valorapurado   = $this->oDados->quadroreceitas[1]->receitaatualizada;
    $oDadosSimplificado->receitadecapital->saldoarealizar = $this->oDados->quadroreceitas[1]->saldoaarealizar;

    $oDadosSimplificado->aplicacaodosrecursos->valorapurado   = $this->oDados->quadrodespesas[1]->despesaliquidada +
                                                                $this->oDados->quadrodespesas[1]->inscritasemrp;
    $oDadosSimplificado->aplicacaodosrecursos->saldoarealizar = $this->oDados->quadrodespesas[1]->saldoaexecutar;
    return $oDadosSimplificado;
  }

  public function emitir() {

    $oPrefeitura           = InstituicaoRepository::getInstituicaoPrefeitura();
    $sDescricaoInstituicao = "{$oPrefeitura->getDescricao()} - {$oPrefeitura->getUf()}";
    $aDados                = $this->getDados();

    $this->oPdf  = new PDFDocument();
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(false);

    $this->oPdf->SetFillColor(235);

    $this->oPdf->addHeaderDescription($sDescricaoInstituicao);
    $this->oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $this->oPdf->addHeaderDescription("DEMONSTRATIVO DA RECEITA DE ALIENAÇÃO DE ATIVOS E APLICAÇÃO DOS RECURSOS");
    $this->oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $this->oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE {$iAnoUsu}");

    $this->oPdf->AddPage("P");

    $iTamFonte = 5;
    $iAltCell  = 3;

    $this->oPdf->SetFont('arial', 'b', $iTamFonte);
    $this->oPdf->Cell(130, $iAltCell, 'RREO - ANEXO 11 (LRF, art. 53, § 1o, inciso III )', 'B', 0, "L", 0);
    $this->oPdf->Cell(60, $iAltCell, 'R$ 1,00',                                      'B', 1, "R", 0);

    $this->oPdf->Cell(64, $iAltCell+9, 'RECEITAS',                   'TBR', 0, "C", 0);
    $this->oPdf->Cell(42, $iAltCell+9, 'PREVISÃO ATUALIZADA (a)',    'TBRL', 0, "C", 0);
    $this->oPdf->Cell(49, $iAltCell+9, 'RECEITAS REALIZADAS (b)',    'TBRL', 0, "C", 0);
    $this->oPdf->Cell(35, $iAltCell+9, 'SALDO A REALIZAR (c)=(a-b)', 'TBL', 1, "C", 0);

    foreach ($aDados->quadroreceitas as $oQuadroReceita) {

      $this->oPdf->SetFont('arial', '', $iTamFonte);
      $this->oPdf->Cell(64, $iAltCell, substr($oQuadroReceita->descricaolinha, 0, 70),        'R', 0, "L", 0);
      $this->oPdf->Cell(42, $iAltCell, db_formatar($oQuadroReceita->previsaoatualizada, 'f'), 'RL', 0, "R", 0);
      $this->oPdf->Cell(49, $iAltCell, db_formatar($oQuadroReceita->receitaatualizada, 'f'),  'RL', 0, "R", 0);
      $this->oPdf->Cell(35, $iAltCell, db_formatar($oQuadroReceita->saldoaarealizar, 'f'),    'L', 1, "R", 0);
    }

    $this->oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);

    $iPosicaoX = $this->oPdf->GetX();
    $iPosicaoY = $this->oPdf->GetY();

    $this->oPdf->SetFont('arial', 'b', $iTamFonte);
    $this->oPdf->Cell(64, $iAltCell+12, 'DESPESAS',                       'TBR', 0, "C", 0);
    $this->oPdf->Cell(42, $iAltCell+12, 'DOTAÇÃO ATUALIZADA (d)',         'TBRL', 0, "C", 0);
    $this->oPdf->Cell(49, $iAltCell+12, '',                               'TBRL', 0, "C", 0);
    $this->oPdf->Cell(35, $iAltCell+12, 'SALDO A EXECUTAR (g)=(d-(e+f))', 'TBL', 1, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+106, $iPosicaoY);
    $this->oPdf->Cell(49, $iAltCell, 'DESPESAS EXECUTADAS', 0, 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+106, $iPosicaoY+3);
    $this->oPdf->Cell(49, $iAltCell, 'Até o Bimestre', 'B', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+106, $iPosicaoY+6);
    $this->oPdf->Cell(24.5, $iAltCell, 'LIQUIDADAS', 'R', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+106, $iPosicaoY+9);
    $this->oPdf->Cell(24.5, $iAltCell, '', 'R', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+106, $iPosicaoY+12);
    $this->oPdf->Cell(24.5, $iAltCell, '(e)', 'R', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+6);
    $this->oPdf->Cell(24.5, $iAltCell, 'INSCRITAS EM', 'L', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+9);
    $this->oPdf->Cell(24.5, $iAltCell, 'RESTOS A PAGAR', 'L', 0, "C", 0);

    $this->oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+12);
    $this->oPdf->Cell(24.5, $iAltCell, 'NÃO PROCESSADOS (f)', 'L', 1, "C", 0);

    foreach ($aDados->quadrodespesas as $oQuadroDespesas) {

      $this->oPdf->SetFont('arial', '', $iTamFonte);
      $this->oPdf->Cell(64, $iAltCell, substr($oQuadroDespesas->descricaolinha, 0, 70),        'R', 0, "L", 0);
      $this->oPdf->Cell(42, $iAltCell, db_formatar($oQuadroDespesas->dotacaoatualizada, 'f'),  'RL', 0, "R", 0);
      $this->oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->despesaliquidada, 'f'), 'R', 0, "R", 0);
      $this->oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->inscritasemrp, 'f'),    'L', 0, "R", 0);
      $this->oPdf->Cell(35, $iAltCell, db_formatar($oQuadroDespesas->saldoaexecutar, 'f'),     'L', 1, "R", 0);
    }

    $this->oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);

    $iAnoAnterior = ($iAnoUsu-1);
    $this->oPdf->SetFont('arial', 'b', $iTamFonte);
    $this->oPdf->Cell(64, $iAltCell+9, 'SALDO FINANCEIRO A APLICAR',     'TBR', 0, "C", 0);
    $this->oPdf->Cell(42, $iAltCell+9, "{$iAnoAnterior} (h)",            'TBRL', 0, "C", 0);
    $this->oPdf->Cell(49, $iAltCell+9, "{$iAnoUsu} (i)=(Ib-(IIe+IIf))",  'TBRL', 0, "C", 0);
    $this->oPdf->Cell(35, $iAltCell+9, 'SALDO ATUAL (j)=(IIIh+IIIi)',    'TBL', 1, "C", 0);

    foreach ($aDados->quadrosaldofinanceiro as $oQuadroSaldoFinanceiro) {

      $this->oPdf->SetFont('arial', '', $iTamFonte);
      $this->oPdf->Cell(64, $iAltCell, substr($oQuadroSaldoFinanceiro->descricaolinha, 0, 70),       'TBR', 0, "L", 0);
      $this->oPdf->Cell(42, $iAltCell, db_formatar($oQuadroSaldoFinanceiro->exercicioanterior, 'f'), 'TBRL', 0, "R", 0);
      $this->oPdf->Cell(49, $iAltCell, db_formatar($oQuadroSaldoFinanceiro->valorexercicio, 'f'),    'TBRL', 0, "R", 0);
      $this->oPdf->Cell(35, $iAltCell, db_formatar($oQuadroSaldoFinanceiro->saldoatual, 'f'),        'TBL', 1, "R", 0);
    }

    $this->oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

    $this->oRelatorioLegal->getNotaExplicativa($this->oPdf, $oGet->periodo);
    $this->oRelatorioLegal->assinatura($this->oPdf, 'LRF', false);
    $this->oPdf->showPDF("AnexoXIRREO_" . time());
  }

}
