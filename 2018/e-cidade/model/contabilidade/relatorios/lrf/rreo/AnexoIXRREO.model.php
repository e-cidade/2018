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
 * classe para controle dos valores do Anexo XI da RREO
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 *
 */

class AnexoIXRREO extends RelatoriosLegaisBase implements AnexoRREO {

  /**
   * @type int
   */
  const CODIGO_RELATORIO = 105;

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

     $oRetorno                     = new stdClass();
     $oRetorno->quadroreceitas     = array();
     $oRetorno->quadrodespesas     = array();
     $oRetorno->resultadoregraouro = new stdClass();


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
     //db_criatabela($this->rFonteReceita);
     /**
      *
      */
     $iProximaLinha = 1;
     for ($i = 1; $i <= 1; $i++) {

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
       $oRetorno->quadroreceitas[$iProximaLinha] = $oLinha;
       $iProximaLinha++;
     }

     /**
      * montamos o quadro da despesa
      */
     if (empty($this->rFonteDespesa)) {

       $sWhere = "o58_instit in ({$this->getInstituicoes()})";
       $this->rFonteDespesa = db_dotacaosaldo(8, 2, 3, true,
                                             $sWhere,
                                             $this->iAnoUsu,
                                             $sDataInicial,
                                             $sDataFinal);
     }

     $oDespesaCapital  = new stdClass();
     $oDespesaCapital->descricaolinha      = '  DESPESAS DE CAPITAL LÍQUIDA(II)';
     $oDespesaCapital->dotacaoatualizada   = 0;
     $oDespesaCapital->despesaliquidada    = 0;
     $oDespesaCapital->inscritasemrp       = 0;
     $oDespesaCapital->saldoaexecutar      = 0;
     $oRetorno->quadrodespesas[4]          = $oDespesaCapital;

     $sEspaco       = "  ";
     $iProximaLinha = 1;
     for ($i = 2; $i <= 4; $i++) {

       $oLinhaRelatorio            = new linhaRelatorioContabil($this->iCodigoRelatorio, $i);
       $oLinha                     = new stdClass();

       if ($i != 2) {
       	 $sEspaco = "    ";
       }

       $oLinha->descricaolinha     = $sEspaco.$oLinhaRelatorio->getDescricaoLinha();
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
       $iTotalLinhasDespesa = pg_num_rows($this->rFonteDespesa);
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
       $oRetorno->quadrodespesas[4]->dotacaoatualizada  += $oLinha->dotacaoatualizada;
       $oRetorno->quadrodespesas[4]->despesaliquidada   += $oLinha->despesaliquidada;
       $oRetorno->quadrodespesas[4]->inscritasemrp      += $oLinha->inscritasemrp;
       $oRetorno->quadrodespesas[4]->saldoaexecutar     += $oLinha->dotacaoatualizada -
                                                          ($oLinha->despesaliquidada +$oLinha->inscritasemrp);
       $oRetorno->quadrodespesas[$iProximaLinha]        = $oLinha;
       $iProximaLinha++;
     }

     ksort($oRetorno->quadrodespesas);
     /**
      * calculamos o regra ouro
      */
     $oRetorno->resultadoregraouro->valoratualizado = $oRetorno->quadroreceitas[1]->previsaoatualizada -
                                                      $oRetorno->quadrodespesas[4]->dotacaoatualizada;

     $oRetorno->resultadoregraouro->valorrealizado  = $oRetorno->quadroreceitas[1]->receitaatualizada -
                                                      ($oRetorno->quadrodespesas[4]->despesaliquidada +
                                                       $oRetorno->quadrodespesas[4]->inscritasemrp);

     $oRetorno->resultadoregraouro->saldoarealizar  = $oRetorno->quadroreceitas[1]->saldoaarealizar -
                                                      $oRetorno->quadrodespesas[4]->saldoaexecutar;
     $this->oDados  = $oRetorno;
     return $this->oDados;
  }

  /**
   * processa os dados para o relatorio simplificado
   *
   */
  public function getDadosSimplificado() {

    $oDadosSimplificado = new stdClass();
    $oDadosSimplificado->receitadeoperacoescredito->valorapurado   = 0;
    $oDadosSimplificado->receitadeoperacoescredito->saldoarealizar = 0;

    $oDadosSimplificado->despesasdecapitalliquida->valorapurado    = 0;
    $oDadosSimplificado->despesasdecapitalliquida->saldoarealizar  = 0;
    if (empty($this->oDados)) {
      $this->getDados();
    }
    $oDadosSimplificado->receitadeoperacoescredito->valorapurado   = $this->oDados->quadroreceitas[1]->receitaatualizada;
    $oDadosSimplificado->receitadeoperacoescredito->saldoarealizar = $this->oDados->quadroreceitas[1]->saldoaarealizar;

    $oDadosSimplificado->despesasdecapitalliquida->valorapurado    = $this->oDados->quadrodespesas[4]->despesaliquidada
                                                                     + $this->oDados->quadrodespesas[4]->inscritasemrp;
    $oDadosSimplificado->despesasdecapitalliquida->saldoarealizar  = $this->oDados->quadrodespesas[4]->saldoaexecutar;
    return $oDadosSimplificado;
  }

  public function emitir() {

    $cldb_config   = new cl_db_config;

    $aDadosAnexoXI = $this->getDados();

    $iNumRows = count($aDadosAnexoXI);
    if ($iNumRows == 0){
      throw new BusinessException('Não existem registros cadastrados.');
    }

    $sWhere        = "prefeitura is true";
    $sSqlDbConfig  = $cldb_config->sql_query_file(null, "munic, uf, codigo, nomeinst, nomeinstabrev", null, $sWhere);
    $rsSqlDbConfig = $cldb_config->sql_record($sSqlDbConfig);
    $oMunicipio    = db_utils::fieldsMemory($rsSqlDbConfig, 0);

    $oPdf  = new PDFDocument();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->SetAutoPageBreak(false);
    $oPdf->SetFillColor(235);

    $oPdf->addHeaderDescription("MUNICÍPIO DE {$oMunicipio->munic} - {$oMunicipio->uf}");
    $oPdf->addHeaderDescription("RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA");
    $oPdf->addHeaderDescription("DEMONSTRATIVO DAS RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL");
    $oPdf->addHeaderDescription("ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL");
    $oPdf->addHeaderDescription("JANEIRO A DEZEMBRO DE ".db_getsession('DB_anousu'));

    $oPdf->AddPage("P");

    $iTamFonte = 5;
    $iAltCell  = 3;

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $oPdf->Cell(130, $iAltCell, 'RREO - ANEXO 9 (LRF, art.53, § 1o, inciso I)', 'B', 0, "L", 0);
    $oPdf->Cell(60, $iAltCell, 'R$ 1,00',                                   'B', 1, "R", 0);

    $oPdf->Cell(64, $iAltCell+9, 'RECEITAS',                   'TBR', 0, "C", 0);
    $oPdf->Cell(42, $iAltCell+9, 'PREVISÃO ATUALIZADA (a)',    'TBRL', 0, "C", 0);
    $oPdf->Cell(49, $iAltCell+9, 'RECEITAS REALIZADAS (b)',    'TBRL', 0, "C", 0);
    $oPdf->text(134,47.7,"Até o Bimestre");
    $oPdf->Cell(35, $iAltCell+9, 'SALDO NÃO REALIZADO (c)=(a-b)', 'TBL', 1, "C", 0);
    foreach ($aDadosAnexoXI->quadroreceitas as $oQuadroReceita) {

      $oPdf->SetFont('arial', '', $iTamFonte);
      $oPdf->Cell(64, $iAltCell, substr($oQuadroReceita->descricaolinha, 0, 70),        'R', 0, "L", 0);
      $oPdf->Cell(42, $iAltCell, db_formatar($oQuadroReceita->previsaoatualizada, 'f'), 'RL', 0, "R", 0);
      $oPdf->Cell(49, $iAltCell, db_formatar($oQuadroReceita->receitaatualizada, 'f'),  'RL', 0, "R", 0);
      $oPdf->Cell(35, $iAltCell, db_formatar($oQuadroReceita->saldoaarealizar, 'f'),    'L', 1, "R", 0);
    }

    $oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);

    $iPosicaoX = $oPdf->GetX();
    $iPosicaoY = $oPdf->GetY();

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $oPdf->Cell(64, $iAltCell+12, 'DESPESAS',                          'TBR', 0, "C", 0);
    $oPdf->Cell(42, $iAltCell+12, 'DOTAÇÃO ATUALIZADA (d)',            'TBRL', 0, "C", 0);
    $oPdf->Cell(49, $iAltCell+12, '',                                  'TBRL', 0, "C", 0);
    $oPdf->Cell(35, $iAltCell+12, 'SALDO NÃO EXECUTADO (g)=(d-(e+f))', 'TBL', 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+106, $iPosicaoY);
    $oPdf->Cell(49, $iAltCell, 'DESPESAS EXECUTADAS', 0, 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+106, $iPosicaoY+3);
    $oPdf->Cell(49, $iAltCell, 'Até o Bimestre', 'B', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+106, $iPosicaoY+6);
    $oPdf->Cell(24.5, $iAltCell, 'LIQUIDADAS', 'R', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+106, $iPosicaoY+9);
    $oPdf->Cell(24.5, $iAltCell, '', 'R', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+106, $iPosicaoY+12);
    $oPdf->Cell(24.5, $iAltCell, '(e)', 'R', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+6);
    $oPdf->Cell(24.5, $iAltCell, 'INSCRITAS EM', 'L', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+9);
    $oPdf->Cell(24.5, $iAltCell, 'RESTOS A PAGAR', 'L', 0, "C", 0);

    $oPdf->SetXY($iPosicaoX+130.5, $iPosicaoY+12);
    $oPdf->Cell(24.5, $iAltCell, 'NÃO PROCESSADOS (f)', 'L', 1, "C", 0);

    foreach ($aDadosAnexoXI->quadrodespesas as $oQuadroDespesas) {

      $oPdf->SetFont('arial', '', $iTamFonte);
      $oPdf->Cell(64, $iAltCell, substr($oQuadroDespesas->descricaolinha, 0, 70),        'R', 0, "L", 0);
      $oPdf->Cell(42, $iAltCell, db_formatar($oQuadroDespesas->dotacaoatualizada, 'f'),  'RL', 0, "R", 0);
      $oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->despesaliquidada, 'f'), 'R', 0, "R", 0);
      $oPdf->Cell(24.5, $iAltCell, db_formatar($oQuadroDespesas->inscritasemrp, 'f'),    'L', 0, "R", 0);
      $oPdf->Cell(35, $iAltCell, db_formatar($oQuadroDespesas->saldoaexecutar, 'f'),     'L', 1, "R", 0);
    }

    $oPdf->Cell(190, $iAltCell-2, '', 'TB', 1, "C", 0);

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $oPdf->Cell(64, $iAltCell+9, 'RESULTADO PARA APURAÇÃO DA REGRA DE OURO (III)=(I-II)', 'TBR', 0, "C", 0);
    $oPdf->Cell(42, $iAltCell+9, "(a-d)",                                                 'TBRL', 0, "C", 0);
    $oPdf->Cell(49, $iAltCell+9, "(b)-(e+f)",                                             'TBRL', 0, "C", 0);
    $oPdf->Cell(35, $iAltCell+9, "(c-g)",                                                 'TBL', 1, "C", 0);

    $nValorAtualizado = $aDadosAnexoXI->resultadoregraouro->valoratualizado;
    $nValorRealizado  = $aDadosAnexoXI->resultadoregraouro->valorrealizado;
    $nSaldoaRealizar  = $aDadosAnexoXI->resultadoregraouro->saldoarealizar;

    $oPdf->SetFont('arial', '', $iTamFonte);
    $oPdf->Cell(64, $iAltCell, '',                                  'TBR', 0, "R", 0);
    $oPdf->Cell(42, $iAltCell, db_formatar($nValorAtualizado, 'f'), 'TBRL', 0, "R", 0);
    $oPdf->Cell(49, $iAltCell, db_formatar($nValorRealizado, 'f'),  'TBRL', 0, "R", 0);
    $oPdf->Cell(35, $iAltCell, db_formatar($nSaldoaRealizar, 'f'),  'TBL', 1, "R", 0);

    $oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

    $this->getNotaExplicativa($oPdf, $this->iCodigoPeriodo);

    $this->getRelatorioContabil()->assinatura($oPdf, 'LRF');

    $oPdf->Output();
  }
}