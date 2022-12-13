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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVIII as Relatorio;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoVIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoVIII {

  const ALTURA_MINIMA                    = 35; // Distancia inicial do arquivo pdf em Paisagem
  const ALTURA_MAXIMA                    = 182; // Distancia do footer do arquivo pdf em Paisagem
  const COMPRIMENTO_MAXIMO               = 277;
  const RECEITA_RESULTANTE_IMPOSTO       = 0;
  const RECEITA_ADICIONAL                = 1;
  const RECEITA_FUNDEB                   = 2;
  const DESPESA_FUNDEB                   = 3;
  const DEDUCAO_FUNDEB                   = 4;
  const INDICADOR_FUNDEB                 = 5;
  const CONTROLE_USO_RECURSO_SUBSEQUENTE = 6;
  const DESPESA_MDE                      = 7;
  const DEDUCAO_LIMITE_CONSTITUCIONAL    = 8;
  const OUTRAS_DESPESAS_INF_CONTROLE     = 9;
  const RP_VINCULADO_ENSINO              = 10;
  const CONTROLE_FINANCEIRO              = 11;

  private $aReceita = array(
    self::RECEITA_RESULTANTE_IMPOSTO,
    self::RECEITA_ADICIONAL,
    self::RECEITA_FUNDEB,
  );

  private $aDespesa = array(
    self::DESPESA_FUNDEB,
    self::DESPESA_MDE,
    self::OUTRAS_DESPESAS_INF_CONTROLE,
  );

  private $aDeducao = array(
    self::DEDUCAO_FUNDEB,
    self::INDICADOR_FUNDEB,
    self::CONTROLE_USO_RECURSO_SUBSEQUENTE,
    self::DEDUCAO_LIMITE_CONSTITUCIONAL,
  );

  // Comprimentos das linhas
  private $aLinhaReceita;
  private $aLinhaDespesa;
  private $aLinhaDeducao;
  private $aLinhaCustomizado;


  private $aCustomizado = array(
    self::RP_VINCULADO_ENSINO,
  );

  /**
   * @var \PDFDocument
   */
  private $oPdf;

  /**
   * @var boolean
   */
  private $lREstoPagar = false;

  /**
   * @param $iAno
   */
  public function setAno($iAno){
    $this->iAno = $iAno;
  }

  /**
   * @return int
   */
  public function getAno(){
    return $this->iAno;
  }

  /**
   * @var \stdClass[]
   */
  private $aLinhas;

  /**
   * @param $aLinhas
   */
  public function setLinhas($aLinhas){
    $this->aLinhas = $aLinhas;
  }

  /**
   * @return \stdClass[]
   */
  public function getLinhas(){
    return $this->aLinhas;
  }

  /**
   * AnexoVIII constructor.
   *
   * @param integer      $iAno
   * @param \Periodo     $oPeriodo
   * @param \Instituicao $oInstituicao
   */
  public function __construct($iAno, \Periodo $oPeriodo, $sInstituicao) {

    $this->iAno         = $iAno;
    $this->oPeriodo     = $oPeriodo;
    $this->sInstituicao = $sInstituicao;

    $iColuna1  = self::COMPRIMENTO_MAXIMO/2;
    $iColuna2  = $iColuna1/3;
    $iColuna3  = $iColuna2/2;
    $iColuna4  = self::COMPRIMENTO_MAXIMO*0.4;
    $iColuna5  = ((self::COMPRIMENTO_MAXIMO-$iColuna4)*0.8)/6;
    $iColuna6  = ((self::COMPRIMENTO_MAXIMO-$iColuna4)*0.2);
    $iColuna7  = self::COMPRIMENTO_MAXIMO*0.75;
    $iColuna8  = self::COMPRIMENTO_MAXIMO-$iColuna7;
    $iColuna9  = self::COMPRIMENTO_MAXIMO*0.70;
    $iColuna10 = (self::COMPRIMENTO_MAXIMO-$iColuna9)/2;

    if($this->oPeriodo->getCodigo() == 11){
      $this->lREstoPagar = true;
    }

    $this->aLinhaReceita = array(
                            $iColuna1,
                            $iColuna2,
                            $iColuna2,
                            $iColuna3,
                            $iColuna3,
                          );

    $this->aLinhaDespesa = array(
                            $iColuna4,
                            $iColuna5,
                            $iColuna5,
                            $iColuna5,
                            $iColuna5,
                            $iColuna5,
                            $iColuna5,
                            $iColuna6,
                          );

    if(!$this->lREstoPagar){
      $iAux = ($iColuna6/2) + $iColuna5;
      $this->aLinhaDespesa = array(
                              $iColuna4,
                              $iAux,
                              $iAux,
                              $iColuna5,
                              $iColuna5,
                              $iColuna5,
                              $iColuna5,
                              $iColuna6,
                            );
    }

    $this->aLinhaDeducao = array(
                            $iColuna7,
                            $iColuna8,
                          );

    $this->aLinhaCustomizado = array(
                            $iColuna9,
                            $iColuna10,
                            $iColuna10,
                          );

    $this->aPosicoes = array(
                        self::RECEITA_RESULTANTE_IMPOSTO => array('inicio' => 1,   'fim' => 26), //0
                        self::RECEITA_ADICIONAL => array('inicio' => 27,  'fim' => 40), //1
                        self::RECEITA_FUNDEB => array('inicio' => 41,  'fim' => 52), //2
                        self::DESPESA_FUNDEB => array('inicio' => 53,  'fim' => 59), //3
                        self::DEDUCAO_FUNDEB => array('inicio' => 60,  'fim' => 66), //4
                        self::INDICADOR_FUNDEB => array('inicio' => 67,  'fim' => 70), //5
                        self::CONTROLE_USO_RECURSO_SUBSEQUENTE => array('inicio' => 71,  'fim' => 72), //6
                        self::DESPESA_MDE => array('inicio' => 73,  'fim' => 87), //7
                        self::DEDUCAO_LIMITE_CONSTITUCIONAL => array('inicio' => 88,  'fim' => 97), //8
                        self::OUTRAS_DESPESAS_INF_CONTROLE => array('inicio' => 98,  'fim' => 103),//9
                        self::RP_VINCULADO_ENSINO => array('inicio' => 104, 'fim' => 106),//10
                        self::CONTROLE_FINANCEIRO => array('inicio' => 107, 'fim' => 117),//11
                        12 => array('inicio' => 118, 'fim' => 128),//12
                      );
  }

  /**
   * Processa as informações que serão impressas no relatório
   */
  private function processar() {

    $oRelatorio          = new Relatorio($this->iAno, Relatorio::CODIGO_RELATORIO, $this->oPeriodo->getCodigo());
    $oRelatorio->setInstituicoes($this->sInstituicao);

    $this->aLinhas       = $oRelatorio->getLinhas();
    $this->oDataFinal    = $oRelatorio->getDataFinal();
    $this->oDataInicial  = $oRelatorio->getDataInicialPeriodo();
  }

  /*Emite o arquivo do relatório*/
  public function emitir() {

    $this->processar();
    $this->oPdf  = new \PDFDocument("L");

    $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
    $sMesInicio  = mb_strtoupper(\DBDate::getMesExtenso($this->oDataInicial->getMes()));
    $sMesFim     = mb_strtoupper(\DBDate::getMesExtenso($this->oDataFinal->getMes()));


    $aInstituicoes = explode(",", $this->sInstituicao);

    if (count($aInstituicoes) == 1) {

      $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

      if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
        $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
      }
    }else {
      $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));
    }
    
    $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DE RECEITAS E DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE');
    $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
    $this->oPdf->addHeaderDescription('JANEIRO' . ' A ' . $sMesFim . '/' . $this->getAno() . ' - BIMESTRE ' . $sMesInicio . '-' . $sMesFim);
    $this->oPdf->open();
    $this->oPdf->addPage();
    $iAnoAnterior = ($this->getAno()-1);
    $sMesAtual    = \DBDate::getMesExtenso($this->oPeriodo->getMesFinal());

    if ($this->oPeriodo->getCodigo() == 6) {
      $sMesFinal = \DBDate::getMesExtenso(\DBDate::DEZEMBRO) . "/{$iAnoAnterior}";
    } else {
      $sMesFinal = \DBDate::getMesExtenso(($this->oPeriodo->getMesFinal()-2)) ."/{$this->getAno()}";
    }

    $iY = $this->oPdf->getY();

    $this->oPdf->cell(0, 2, "", "", 1, "");
    $this->oPdf->setFontSize(5);
    $this->oPdf->cell(142,3, "RREO - Anexo 8 (LDB, Art. 72)", "", 0, "L");
    $this->oPdf->cell(135,3, "Em Reais", "", 1, "R");

    $this->imprimeTabelaReceita(self::RECEITA_RESULTANTE_IMPOSTO);      // Receitas Resultantes de Impostos
    $this->imprimeTabelaReceita(self::RECEITA_ADICIONAL);              // Receitas Adicionais
    $this->imprimeTabelaReceita(self::RECEITA_FUNDEB);                 // Receitas do FUNDEB
    $this->imprimeTabelaDespesa(self::DESPESA_FUNDEB);                 // Despesas do FUNDEB
    $this->imprimeTabelaDeducao(self::DEDUCAO_FUNDEB);                 // Deducoes do FUNDEB
    $this->imprimeTabelaDeducao(self::INDICADOR_FUNDEB);               // Indicadores do FUNDEB
    $this->imprimeTabelaDeducao(self::CONTROLE_USO_RECURSO_SUBSEQUENTE); // Controle de Recursos do exercicio subsequente
    $this->imprimeTabelaDespesa(self::DESPESA_MDE);                    // Despesas MDE
    $this->imprimeTabelaDeducao(self::DEDUCAO_LIMITE_CONSTITUCIONAL);   // DEDUCAO Institucionaç
    $this->imprimeTabelaDespesa(self::OUTRAS_DESPESAS_INF_CONTROLE);     // Outras Despesas
    $this->imprimeTabelaCustomizado(self::RP_VINCULADO_ENSINO);         //Restos a Pagar
    $this->imprimeTabelaFinanceiro(self::CONTROLE_FINANCEIRO);         //Tabela de dados do Fundeb/Salario Educacao

    $oRelatorio = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
    $classinatura = new \cl_assinatura;
    $oRelatorio->getNotaExplicativa($this->oPdf, $this->oPeriodo->getCodigo());

    $this->imprimeNotasRodape();
    $this->oPdf->cell(0, 25, " ", "", 1, "");

    assinaturas($this->oPdf,$classinatura,'LRF');
    $this->oPdf->showPDF("AnexoVIII");
  }

  // Imprime a tabela completa do controle financeiro
  private function imprimeTabelaFinanceiro($iOpcao=self::CONTROLE_FINANCEIRO){
    $this->imprimeCabecalhoFinanceiro($iOpcao);
    $this->imprimeValorFinanceiro($iOpcao);
  }

  // Imprime o cabeçalho da tabela do Controle Financeiro
  private function imprimeCabecalhoFinanceiro($iOpcao=self::CONTROLE_FINANCEIRO){

    $this->oPdf->setFontSize(6);
    $this->verificaCabecalho($iOpcao);

    switch ($iOpcao) {

      case self::CONTROLE_FINANCEIRO:

        $this->oPdf->setBold(1);
        $this->oPdf->cell($this->aLinhaCustomizado[0], 5, "CONTROLE DA DISPONIBILIDADE FINANCEIRA", "TBR", 0, "C", 0);
        $this->oPdf->cell($this->aLinhaCustomizado[1], 5, "FUNDEB", "TBR", 0, "C", 0 );
        $this->oPdf->cell($this->aLinhaCustomizado[2], 5, "SALÁRIO EDUCAÇÃO", "TB", 1, "C", 0);
        $this->oPdf->setBold(0);

      break;

      default:
        # code...
      break;
    }
  }

  // Imprime o cabeçalho da tabela de Receitas
  private function imprimeCabecalhoReceita($iReceita=self::RECEITA_RESULTANTE_IMPOSTO){

    $this->oPdf->setFontSize(6);

    switch ($iReceita) {

      case self::RECEITA_RESULTANTE_IMPOSTO:

        $this->oPdf->setUnderline(1);
        $this->oPdf->setBold(1);
        $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, "RECEITAS DO ENSINO", "TB", 1, "C",0);
        $this->oPdf->setUnderline(0);

        $sDescricao = "RECEITA RESULTANTE DE IMPOSTOS(caput do art.212 da Constituição)";
        $this->imprimeDadosCabecalhoReceita($sDescricao);
      break;

      case self::RECEITA_ADICIONAL:
        $sDescricao = "RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO";
        $this->imprimeDadosCabecalhoReceita($sDescricao);
      break;

      case self::RECEITA_FUNDEB:
        $this->oPdf->setUnderline(1);
        $this->oPdf->setBold(1);
        $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, "FUNDEB", "TB", 1, "C",0);
        $this->oPdf->setUnderline(0);
        $sDescricao = "RECEITAS DO FUNDEB";
        $this->imprimeDadosCabecalhoReceita($sDescricao);
      break;

      default:

      break;
    }
  }

  // Imprime a tabela de Receitas
  private function imprimeTabelaReceita($iReceita=self::RECEITA_RESULTANTE_IMPOSTO){

    // Imprime o cabeçalho
    $this->imprimeCabecalhoReceita($iReceita);
    // Imprime os valores
    $this->imprimeValorReceita($iReceita);

    // Verifica se é a tabela do FUNDEB
    if($iReceita == self::RECEITA_FUNDEB){

      $sTexto1 = "[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (12) > 0] = ACRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB";
      $sTexto2 = "[SE RESULTADO LÍQUIDO DA TRANSFERÊNCIA (12) < 0] = DECRÉSCIMO RESULTANTE DAS TRANSFERÊNCIAS DO FUNDEB";
      $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, $sTexto1, "TB", 1, "L",0);
      $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, $sTexto2, "TB", 1, "L",0);
    }
    $this->finalizaTabela();
  }

  private function imprimeTabelaDespesa($iDespesa=self::DESPESA_FUNDEB){
    $this->imprimeCabecalhoDespesa($iDespesa);
    $this->imprimevalorDespesa($iDespesa);
    $this->finalizaTabela();
  }

  private function imprimeTabelaDeducao($iDeducao=self::DEDUCAO_FUNDEB){
    $this->imprimeCabecalhoDeducao($iDeducao);
    $this->imprimeValorDeducao($iDeducao);
    $this->finalizaTabela();
  }

  private function imprimeTabelaCustomizado($iOpcao=self::RP_VINCULADO_ENSINO){
    $this->imprimeCabecalhoCustomizado($iOpcao);
    $this->imprimeValorCustomizado($iOpcao);
    $this->finalizaTabela();
  }

  private function imprimeCabecalhoDespesa($iDespesa=self::DESPESA_FUNDEB){

    $this->oPdf->setFontSize(6);
    $this->verificaCabecalho($iDespesa);

    switch ($iDespesa) {

      case self::DESPESA_FUNDEB:
        $sDescricao = "DESPESAS DO FUNDEB";
        $this->imprimeDadosCabecalhoDespesa($sDescricao);
      break;

      case self::DESPESA_MDE:
        $this->oPdf->setUnderline(1);
        $this->oPdf->setBold(1);
        $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, "MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - DESPESAS CUSTEADAS COM A RECEITA RESULTANTE DE IMPOSTOS E RECURSOS DO FUNDEB", "TB", 1, "C",0);
        $this->oPdf->setUnderline(0);
        $sDescricao = "DESPESAS COM AÇÕES TÍPICAS DE MDE";
        $this->imprimeDadosCabecalhoDespesa($sDescricao);
      break;

      case self::OUTRAS_DESPESAS_INF_CONTROLE:
        $this->oPdf->setUnderline(1);
        $this->oPdf->setBold(1);
        $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 3, "OUTRAS INFORMAÇÕES PARA CONTROLE", "TB", 1, "C",0);
        $this->oPdf->setUnderline(0);
        $sDescricao = "OUTRAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO";
        $this->imprimeDadosCabecalhoDespesa($sDescricao);
      break;

      default:

      break;
    }
  }

  private function imprimeCabecalhoDeducao($iDeducao=self::INDICADOR_FUNDEB){

    $this->oPdf->setFontSize(6);
    $this->verificaCabecalho($iDeducao);

    switch ($iDeducao) {

      case self::DEDUCAO_FUNDEB:
        $sDescricao = "DEDUÇÕES PARA FINS DO LIMITE DO FUNDEB";
        $this->imprimeDadosCabecalhoDeducao($sDescricao);
      break;

      case self::INDICADOR_FUNDEB:
        $sDescricao = "INDICADORES DO FUNDEB";
        $this->imprimeDadosCabecalhoDeducao($sDescricao);
      break;

      case self::CONTROLE_USO_RECURSO_SUBSEQUENTE:
        $sDescricao = "CONTROLE DA UTILIZAÇÃO DE RECURSOS NO EXERCÍCIO SUBSEQÜENTE";
        $this->imprimeDadosCabecalhoDeducao($sDescricao);
      break;

      case self::DEDUCAO_LIMITE_CONSTITUCIONAL:
        $sDescricao = "DEDUÇÕES CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL";
        $this->imprimeDadosCabecalhoDeducao($sDescricao);
      break;

      default:

      break;
    }
  }

  private function imprimeCabecalhoCustomizado($iOpcao=self::RP_VINCULADO_ENSINO){

    $this->oPdf->setFontSize(6);
    $this->verificaCabecalho($iOpcao);

    switch ($iOpcao) {

      case self::RP_VINCULADO_ENSINO:

        $aDescricao = array(
                        "RESTOS A PAGAR INSCRITOS COM DISPONIBILIDADE FINANCEIRA DE RECURSOS DE IMPOSTOS VINCULADOS AO ENSINO",
                        "SALDO ATÉ O BIMESTRE",
                        "CANCELADO EM <EXERCÍCIO> (j)",
                      );
        $this->imprimeDadosCabecalhoCustomizado($aDescricao);
      break;

      default:

      break;
    }
  }

  private function imprimeDadosCabecalhoReceita($sDescricao){

    $this->oPdf->setUnderline(0);
    $this->oPdf->setBold(1);
    $this->oPdf->cell($this->aLinhaReceita[0], 5, $sDescricao, "TRB", 0, "C",0);
    $this->oPdf->cell($this->aLinhaReceita[1], 5, "PREVISÃO INICIAL", "TRB", 0, "C",0);
    $iX  = $this->oPdf->getX();
    $iY  = $this->oPdf->getY();
    $this->oPdf->MultiCell($this->aLinhaReceita[2], 2.5,"PREVISÃO ATUALIZADA \n(a)","TRB",'C',0);
    $iX += $this->aLinhaReceita[2];
    $this->oPdf->setY($iY);
    $this->oPdf->setX($iX);

    $iTmp = $this->aLinhaReceita[3] + $this->aLinhaReceita[4];
    $this->oPdf->setX($iX);
    $this->oPdf->MultiCell($iTmp, 2.5,"RECEITAS REALIZADAS","TB",'C',0);
    $this->oPdf->setX($iX);
    $this->oPdf->cell($this->aLinhaReceita[3], 2.5, "Até o Bimestre (b)", "RB", 0, "C",0);
    $this->oPdf->cell($this->aLinhaReceita[4], 2.5, "%(c) = (b/a)x100", "B", 1, "C",0);
    $this->oPdf->setBold(0);
  }

  /**
   * @param $sDescricao
   */
  private function imprimeDadosCabecalhoDespesa($sDescricao){

    $this->oPdf->setUnderline(0);
    $this->oPdf->setBold(1);
    $this->oPdf->cell($this->aLinhaDespesa[0], 7.5, $sDescricao, "TRB", 0, "C",0);
    $this->oPdf->cell($this->aLinhaDespesa[1], 7.5, "DOTAÇÃO INICIAL", "TRB", 0, "C",0);
    $iX  = $this->oPdf->getX();
    $iY  = $this->oPdf->getY();
    $this->oPdf->MultiCell($this->aLinhaDespesa[2], 2.5,"DOTAÇÃO \n ATUALIZADA \n(d)","TRB",'C',0);
    $iX += $this->aLinhaDespesa[2];
    $this->oPdf->setY($iY);
    $this->oPdf->setX($iX);

    $iTmp = $this->aLinhaDespesa[3] + $this->aLinhaDespesa[4];
    $this->oPdf->setX($iX);
    $this->oPdf->MultiCell($iTmp, 3.75,"DESPESAS EMPENHADAS","TRB",'C',0);
    $this->oPdf->setX($iX);
    $this->oPdf->cell($this->aLinhaDespesa[3], 3.75, "Até o Bimestre (e)", "RB", 0, "C",0);
    $this->oPdf->cell($this->aLinhaDespesa[4], 3.75, "%(f) = (e/d)x100", "RB", 1, "C",0);

    $iX += $iTmp;
    $this->oPdf->setY($iY);
    $this->oPdf->setX($iX);

    if($this->lREstoPagar){

      $this->oPdf->MultiCell($iTmp, 3.75,"DESPESAS LIQUIDADAS","TRB",'C',0);
      $this->oPdf->setX($iX);
      $this->oPdf->cell($this->aLinhaDespesa[5], 3.75, "Até o Bimestre (g)", "RB", 0, "C",0);
      $this->oPdf->cell($this->aLinhaDespesa[6], 3.75, "%(h) = (g/d)x100", "RB", 1, "C",0);
      $iTmp = $this->aLinhaDespesa[5] + $this->aLinhaDespesa[6];
      $iX += $iTmp;
      $this->oPdf->setY($iY);
      $this->oPdf->setX($iX);
      $this->oPdf->MultiCell($this->aLinhaDespesa[7], 2.5,"INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS (i)","TB",'C',0);
    } else {

      $this->oPdf->MultiCell($iTmp, 3.75,"DESPESAS LIQUIDADAS","TB",'C',0);
      $this->oPdf->setX($iX);
      $this->oPdf->cell($this->aLinhaDespesa[5], 3.75, "Até o Bimestre (g)", "RB", 0, "C",0);
      $this->oPdf->cell($this->aLinhaDespesa[6], 3.75, "%(h) = (g/d)x100", "B", 1, "C",0);
    }

    $this->oPdf->setBold(0);
  }

  /**
   * @param string $sDescricao
   */
  private function imprimeDadosCabecalhoDeducao($sDescricao){

    $this->oPdf->setUnderline(0);
    $this->oPdf->setBold(1);
    $this->oPdf->cell($this->aLinhaDeducao[0], 5, $sDescricao, "TRB", 0, "C",0);
    $this->oPdf->cell($this->aLinhaDeducao[1], 5, "VALOR", "TB", 1, "C",0);
    $this->oPdf->setBold(0);
  }

  /**
   * @param $aDescricao
   */
  private function imprimeDadosCabecalhoCustomizado($aDescricao){

    $this->oPdf->setUnderline(0);
    $this->oPdf->setBold(1);

    for ($i=0; $i < sizeof($aDescricao); $i++) {

      $this->alteraDescricao($aDescricao[$i]);

      if($i == sizeof($aDescricao)-1){

        $this->oPdf->cell($this->aLinhaCustomizado[$i], 5, $aDescricao[$i], "TB", 1, "C",0);
      } else {
        $this->oPdf->cell($this->aLinhaCustomizado[$i], 5, $aDescricao[$i], "TRB", 0, "C",0);
      }
    }

    $this->oPdf->setBold(0);
  }

  /**
   * @param int $iPosicao
   */
  private function imprimeValorFinanceiro($iPosicao = self::CONTROLE_FINANCEIRO){

    for ($i=$this->aPosicoes[$iPosicao]['inicio']; $i <= $this->aPosicoes[$iPosicao]['fim'] ; $i++) {

      $this->verificaLinha($iPosicao);
      $sBorda = $this->getBorda($i, $this->aPosicoes[$iPosicao]['fim'], $this->aLinhas[$i]->totalizar);
      $this->getBold($this->aLinhas[$i]);

      $sDescricao = \relatorioContabil::getIdentacao($this->aLinhas[$i]->nivel).$this->aLinhas[$i]->descricao;
      $fValor     = db_formatar($this->aLinhas[$i]->valor, "f");
      $fValor2    = db_formatar($this->aLinhas[$i+11]->valor, "f");

      $this->alteraDescricao($sDescricao);
      $this->oPdf->cell($this->aLinhaCustomizado[0], 4, $sDescricao, $sBorda."R", 0, "L", 0);
      $this->oPdf->cell($this->aLinhaCustomizado[1], 4, $fValor, $sBorda."R", 0, "R", 0);
      $this->oPdf->cell($this->aLinhaCustomizado[2], 4, $fValor2, $sBorda, 1, "R", 0);
    }
  }

  /**
   * @param int $iPosicao
   */
  private function imprimeValorReceita($iPosicao = self::RECEITA_RESULTANTE_IMPOSTO){

    for ($i=$this->aPosicoes[$iPosicao]['inicio']; $i <= $this->aPosicoes[$iPosicao]['fim'] ; $i++) {

      /**
       * Reportado que a linha em questão(2.1.3- Parcela referente à CF, art. 159, I, alínea e) não existe no modelo STN,
       * e consequentemente não deve aparecer no relatório. Em conversa com o suporte/Matheus Felini, visto que o melhor
       * é apenas não apresentar a linha no relatório ao invés de excluir do cadastro, devido aos impactos/cálculos
       */
      if($this->aLinhas[$i]->ordem == 19) {
        continue;
      }

      $this->verificaLinha($iPosicao);

      /**
       * Validação necessária, pois a linha '1.4- Receita Resultante do Imposto de Renda Retido na Fonte' não possui
       * linhas com níveis abaixo, porém é uma 'totalizadora', necessitando ser destacada
       */
      if($this->aLinhas[$i]->ordem == 11) {
        $this->aLinhas[$i]->totalizar = true;
      }

      $sBorda = $this->getBorda($i, $this->aPosicoes[$iPosicao]['fim'], $this->aLinhas[$i]->totalizar);
      $this->getBold($this->aLinhas[$i]);

      $sDescricao   = \relatorioContabil::getIdentacao($this->aLinhas[$i]->nivel).$this->aLinhas[$i]->descricao;
      $this->alteraDescricao($sDescricao);

      $this->oPdf->cell($this->aLinhaReceita[0], 4, $sDescricao, $sBorda."R", 0, "L", 0);
      $fPorcentagem = ($this->aLinhas[$i]->recatebim && $this->aLinhas[$i]->previni) > 0 ? db_formatar(($this->aLinhas[$i]->recatebim/$this->aLinhas[$i]->previni)*100, "f") : db_formatar(0, "f");

      $fPrevisaoInicial = db_formatar($this->aLinhas[$i]->previni, "f");
      $this->oPdf->cell($this->aLinhaReceita[1], 4, $fPrevisaoInicial, $sBorda."R", 0, "R", 0);

      $fPrevisaoAtual = db_formatar($this->aLinhas[$i]->prevatu, "f");
      $this->oPdf->cell($this->aLinhaReceita[2], 4, $fPrevisaoAtual, $sBorda."R", 0, "R", 0);

      $fAteBim = db_formatar($this->aLinhas[$i]->recatebim, "f");
      $this->oPdf->cell($this->aLinhaReceita[3], 4, $fAteBim, $sBorda."R", 0, "R", 0);

      $this->oPdf->cell($this->aLinhaReceita[4], 4, $fPorcentagem, $sBorda, 1, "R", 0);
      $this->oPdf->setBold(0);
    }
  }

  /**
   * @param int $iPosicao
   */
  private function imprimeValorDespesa($iPosicao = self::DESPESA_FUNDEB){

    for ($i=$this->aPosicoes[$iPosicao]['inicio']; $i <= $this->aPosicoes[$iPosicao]['fim'] ; $i++) {

      $this->verificaLinha($iPosicao);

      $iAltura = 4;

      $sBorda = $this->getBorda($i, $this->aPosicoes[$iPosicao]['fim'], $this->aLinhas[$i]->totalizar);
      $this->getBold($this->aLinhas[$i]);

      $sDescricao       = \relatorioContabil::getIdentacao($this->aLinhas[$i]->nivel).$this->aLinhas[$i]->descricao;
      $this->alteraDescricao($sDescricao);

      $fDotacaoInicial  = db_formatar($this->aLinhas[$i]->dotini, "f");

      // f = (e/d)*100
      $fPorcentagemEmp  = ($this->aLinhas[$i]->empenhado_atebim && $this->aLinhas[$i]->dotatu) > 0 ? db_formatar(($this->aLinhas[$i]->empenhado_atebim/$this->aLinhas[$i]->dotatu)*100, "f"): db_formatar(0, "f");

      // h = (g/d)*100
      $fPorcentagemLiq  = ($this->aLinhas[$i]->liquidado_atebim && $this->aLinhas[$i]->dotatu) > 0 ? db_formatar(($this->aLinhas[$i]->liquidado_atebim/$this->aLinhas[$i]->dotatu)*100, "f"): db_formatar(0, "f");
      // d
      $fDotacaoAtual    = db_formatar($this->aLinhas[$i]->dotatu, "f");
      // e
      $fEmpenhadoAteBim = db_formatar($this->aLinhas[$i]->empenhado_atebim, "f");
      // f
      $fLiquidadoAteBim = db_formatar($this->aLinhas[$i]->liquidado_atebim, "f");

      if($this->oPdf->getStringWidth($sDescricao) > $this->aLinhaDespesa[0]){

        $iX = $this->oPdf->getX();
        $iY = $this->oPdf->getY();

        $this->oPdf->MultiCell($this->aLinhaDespesa[0], $iAltura, $sDescricao, $sBorda."R", "L", 0);

        $this->oPdf->setY($iY);
        $this->oPdf->setX($iX+$this->aLinhaDespesa[0]);

        $iAltura  = $iAltura * (ceil($this->oPdf->getStringWidth($sDescricao)/ $this->aLinhaDespesa[0]));
      } else {
        $this->oPdf->cell($this->aLinhaDespesa[0], $iAltura, $sDescricao, $sBorda."R", 0, "L", 0);
      }
      $this->oPdf->cell($this->aLinhaDespesa[1], $iAltura, $fDotacaoInicial, $sBorda."R", 0, "R", 0);
      $this->oPdf->cell($this->aLinhaDespesa[2], $iAltura, $fDotacaoAtual, $sBorda."R", 0, "R", 0);
      $this->oPdf->cell($this->aLinhaDespesa[3], $iAltura, $fEmpenhadoAteBim, $sBorda."R", 0, "R", 0);
      $this->oPdf->cell($this->aLinhaDespesa[4], $iAltura, $fPorcentagemEmp, $sBorda."R", 0, "R", 0);
      $this->oPdf->cell($this->aLinhaDespesa[5], $iAltura, $fLiquidadoAteBim, $sBorda."R", 0, "R", 0);

      if($this->lREstoPagar){

        $fRestoPagar      = db_formatar($this->aLinhas[$i]->rp_apagar, "f");
        $this->oPdf->cell($this->aLinhaDespesa[6], $iAltura, $fPorcentagemLiq, $sBorda."R", 0, "R", 0);
        $this->oPdf->cell($this->aLinhaDespesa[7], $iAltura, $fRestoPagar, $sBorda, 1, "R", 0);
      } else {

        $this->oPdf->cell($this->aLinhaDespesa[6], $iAltura, $fPorcentagemLiq, $sBorda, 1, "R", 0);
      }
      $this->oPdf->setBold(0);
    }
  }

  /**
   * @param int $iPosicao
   */
  private function imprimeValorDeducao($iPosicao = self::INDICADOR_FUNDEB){

    for ($i=$this->aPosicoes[$iPosicao]['inicio']; $i <= $this->aPosicoes[$iPosicao]['fim'] ; $i++) {

      $this->verificaLinha($iPosicao);

      $sBorda     = $this->getBorda($i, $this->aPosicoes[$iPosicao]['fim'], $this->aLinhas[$i]->totalizar);
      $sDescricao = \relatorioContabil::getIdentacao($this->aLinhas[$i]->nivel).$this->aLinhas[$i]->descricao;
      $fValor     = db_formatar($this->aLinhas[$i]->valor, "f");

      $this->alteraDescricao($sDescricao);
      $this->getBold($this->aLinhas[$i]);

      $this->oPdf->cell($this->aLinhaDeducao[0], 4, $sDescricao, $sBorda."R", 0, "L", 0);
      $this->oPdf->cell($this->aLinhaDeducao[1], 4, $fValor, $sBorda, 1, "R", 0);
      $this->oPdf->setBold(0);
    }
  }

  /**
   * @param int $iPosicao
   */
  private function imprimeValorCustomizado($iPosicao = self::RP_VINCULADO_ENSINO){

    for ($i=$this->aPosicoes[$iPosicao]['inicio']; $i <= $this->aPosicoes[$iPosicao]['fim'] ; $i++) {

      $this->getBold($this->aLinhas[$i]);
      $sBorda = $this->getBorda($i, $this->aPosicoes[$iPosicao]['fim'], $this->aLinhas[$i]->totalizar);

      $sDescricao = \relatorioContabil::getIdentacao($this->aLinhas[$i]->nivel).$this->aLinhas[$i]->descricao;
      $this->alteraDescricao($sDescricao);
      $this->oPdf->cell($this->aLinhaCustomizado[0], 4, $sDescricao, $sBorda."R", 0, "L", 0);

      foreach ($this->aLinhas[$i]->colunas as $key => $value) {

        $fValor = db_formatar($this->aLinhas[$i]->{$value->o115_nomecoluna}, "f");

        if($key == sizeof($this->aLinhas[$i]->colunas) -1){
          $this->oPdf->cell($this->aLinhaCustomizado[$key+1], 4, $fValor, $sBorda, 1, "R", 0);
        } else {
          $this->oPdf->cell($this->aLinhaCustomizado[$key+1], 4, $fValor, $sBorda."R", 0, "R", 0);
        }
      }
      $this->oPdf->setBold(0);
    }
  }

  /**
   * @param $i
   * @param $iFim
   * @param $lTotal
   *
   * @return string
   */
  private function getBorda($i, $iFim, $lTotal){

    if(($i == $iFim) && $lTotal){
      return "TB";
    } else if(($i == $iFim)){
      return "B";
    } else if($lTotal){
      return "T";
    }
    return "";
  }

  /**
   * @param $oLinha
   */
  private function getBold($oLinha){

    if($oLinha->totalizar || $oLinha->nivel == 1){
      $this->oPdf->setBold(1);
    }
  }

  /**
   *
   */
  private function finalizaTabela(){
    $this->oPdf->cell(0,5, "", "", 1, "");
  }

  /**
   * @param $sDescricao
   */
  private function alteraDescricao(&$sDescricao){

    $sDescricao = str_replace("<EXERCÍCIO >", $this->iAno, $sDescricao);
    $sDescricao = str_replace("<EXERCÍCIO>", $this->iAno, $sDescricao);
    $sDescricao = str_replace("<EXERCÍCIO ANTERIOR>", ($this->iAno-1), $sDescricao);
  }

  /**
   * @param int $iOpcao
   */
  private function verificaCabecalho($iOpcao = self::RECEITA_RESULTANTE_IMPOSTO){

    $iY = $this->oPdf->getY();

    if($iY >= self::ALTURA_MAXIMA-20){
      $this->oPdf->addPage();
    }
  }

  /**
   * @param int $iOpcao
   */
  private function verificaLinha($iOpcao = self::RECEITA_RESULTANTE_IMPOSTO){

    $iY = $this->oPdf->getY();

    if($iY <= self::ALTURA_MINIMA){
      if(in_array($iOpcao, $this->aReceita)){
        $this->imprimeCabecalhoReceita($iOpcao);
      } else if(in_array($iOpcao, $this->aDespesa)){
        $this->imprimeCabecalhoDespesa($iOpcao);
      } else if(in_array($iOpcao, $this->aDeducao)){
        $this->imprimeCabecalhoDeducao($iOpcao);
      } else if(in_array($iOpcao, $this->aCustomizado)){
        $this->imprimeCabecalhoCustomizado($iOpcao);
      }
    }

    if($iY >= self::ALTURA_MAXIMA){
      $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 0, 0, 'T');
      $this->oPdf->addPage();
      $this->verificaLinha($iOpcao);
    }
  }

  /**
   * @return void
   */
  private function imprimeNotasRodape(){

    $oDepartamento = \DBDepartamentoRepository::getDBDepartamentoByCodigo(db_getsession('DB_coddepto'));
    $sData         = date('d/m/Y');
    $sHora         = date('H:i:s');

    $sFonte  = "Fonte: Sistema E-Cidade, Unidade Responsável {$oDepartamento->getNomeDepartamento()}, Data de emissão";
    $sFonte .= " {$sData} e hora de emissão {$sHora}";

    $aNotas = array(
                  $sFonte,
                  "",
                  "1 Limites mínimos anuais a serem cumpridos no encerramento do exercício.",
                  '2 Art. 21,  2º, Lei 11.494/2007: "Até 5% dos recursos recebidos à conta dos Fundos, inclusive relativos à complementação da União recebidos nos termos do §1º do art. 6º desta Lei, poderão ser utilizados no 1º trimestre do exercício imediatamente subseqüente, mediante abertura de crédito adicional."',
                  "3 Caput do artigo 212 da CF/1988",
                  "4 Os valores referentes à parcela dos Restos a Pagar inscritos sem disponibilidade financeira vinculada à educação deverão ser informados somente no RREO do último bimestre do exercício.",
                  "5 Limites mínimos anuais a serem cumpridos no encerramento do exercício, no âmbito de atuação prioritária, conforme LDB, art. 11, V.",
                  "6 Nos cinco primeiros bimestres do exercício o acompanhamento poderá ser feito com base na despesa empenhada ou na despesa liquidada. No último bimestre do exercício, o valor deverá corresponder ao total da despesa empenhada.",
                  "7 Essa coluna poderá ser apresentada somente no último bimestre",
                );
    $this->oPdf->setFontSize(5);
    foreach ($aNotas as $sNota) {
      $this->oPdf->cell(self::COMPRIMENTO_MAXIMO, 4, $sNota, "", 1, "L");
    }
  }
}
