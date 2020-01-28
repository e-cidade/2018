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
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV as Relatorio;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoVII;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

/**
 * Class AnexoV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout
 */
class AnexoV {

  /**
   * Caminho onde estão localizadas as mensagens disparadas pela classe
   * @var string
   */
  const MENSAGENS = 'financeiro.contabilidade.con2_emissaoAnexoV.';

  /**
   * @var \PDFDocument
   */
  private $oPdf;

  /**
   * @var \stdClass[]
   */
  private $aLinhas;

  /**
   * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV
   */
  private $oAnexo;

  // largura maxima de 190
  const COLUNA1DEFAULT = 100;
  const COLUNA2DEFAULT = 30;
  const COLUNA2GRANDE  = 90;   //Quantidade de colunas menores
  const COLUNA2MEDIA   = 45; //Quantidade de colunas menores
  const COLUNA1GRANDE  = 145;
  const BGCOLOR        = 100;

  /**
   * @return \stdClass[]
   */
  public function getLinhas(){
    return $this->aLinhas;
  }

  /**
   * @param \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV $oAnexo
   */
  public function setAnexo(\ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoV $oAnexo) {
    $this->oAnexo = $oAnexo;
  }


  /**
   * Processa as informações que serão impressas no relatório
   */
  private function processar() {

    if (empty($this->oAnexo)) {
      throw new \ParameterException(_M(self::MENSAGENS . 'anexo_nao_informado'));
    }
    $this->aLinhas = $this->oAnexo->getLinhas();
  }


  /**
   * Emite o relatório em PDF
   */
  public function emitir() {

    $this->processar();

    $this->oPdf  = new \PDFDocument();

    $oPrefeitura  = \InstituicaoRepository::getInstituicaoPrefeitura();
    $sMesInicio   = mb_strtoupper( \DBDate::getMesExtenso($this->oAnexo->getDataInicial()->getMes()) );
    $sMesFim      = mb_strtoupper( \DBDate::getMesExtenso($this->oAnexo->getDataFinal()->getMes()) );
    $sMesInicialBimestre = mb_strtoupper( \DBDate::getMesExtenso($this->oAnexo->getDataFinal()->getMes() - 1) );
    $oInstituicao = \InstituicaoRepository::getInstituicaoSessao();
    $this->oPdf->addHeaderDescription('');

    $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oInstituicao));

    if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
      $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
    }

    $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA');
    $this->oPdf->addHeaderDescription('DEMONSTRATIVO DO RESULTADO NOMINAL');
    $this->oPdf->addHeaderDescription('ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL');
    $this->oPdf->addHeaderDescription($sMesInicio . ' A ' . $sMesFim . '/' . $this->oAnexo->getAno() . ' - BIMESTRE ' . $sMesInicialBimestre . '-' . $sMesFim);

    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->oPdf->setFillColor(235);

    $this->imprimeDividaFiscal();
    $this->imprimeResultadoNominal();
    $this->imprimeMetaFiscal();

    $classinatura = new \cl_assinatura;
    $oRelatorio   = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
    $oRelatorio->getNotaExplicativa($this->oPdf, $this->oAnexo->getPeriodo()->getCodigo());
    $this->oPdf->cell(0, 25, " ", "", 1, "");
    assinaturas($this->oPdf,$classinatura,'LRF');

    $this->oPdf->output();
  }

  private function imprimeValores($oLinha, $iCount, $iTamanho = self::COLUNA2DEFAULT, $aColunas=null){

    // Caso nao venha o array das colunas
    // é utilizado o valor default
    if (empty($aColunas)) {

      for ($i=0; $i < $iCount; $i++) {
        $aColunas[]  = $i;
      }
    }

    for ($i=0; $i < $iCount; $i++) {

      $sValor = 0;
      switch ($aColunas[$i]) {

        case 0:

          if(!empty($oLinha->vlrexanter)){
            $sValor = $oLinha->vlrexanter;
          }
        break;

        case 1:

          if(!empty($oLinha->saldo_bimestre_anterior)){
            $sValor = $oLinha->saldo_bimestre_anterior;
          }
        break;

        case 2:

          if(!empty($oLinha->saldo_bimestre_atual)){
            $sValor = $oLinha->saldo_bimestre_atual;
          }
        break;

        default:

          if(!empty($oLinha->vlrexanter)){
            $sValor = $oLinha->vlrexanter;
          }
        break;
      }

      if(empty($sValor)){

        $sValor = 0;
      }

      if($i == $iCount-1){
        $this->oPdf->cell($iTamanho, 5, db_formatar($sValor, "f"), "TB", 1, "R");
      } else {

        $this->oPdf->cell($iTamanho, 5, db_formatar($sValor, "f"), "TRB", 0, "R");
      }
    }
  }

  private function imprimeDividaFiscal() {

    $iAnoAnterior = ($this->oAnexo->getAno()-1);
    $sMesAtual    = \DBDate::getMesExtenso($this->oAnexo->getPeriodo()->getMesFinal());
    if ($this->oAnexo->getPeriodo()->getCodigo() == 6) {
      $sMesFinal = \DBDate::getMesExtenso(\DBDate::DEZEMBRO) . "/{$iAnoAnterior}";
    } else {
      $sMesFinal = \DBDate::getMesExtenso(($this->oAnexo->getPeriodo()->getMesFinal()-2)) ."/{$this->oAnexo->getAno()}";
    }

    $this->oPdf->cell(0,2, "", "", 1, "");
    $this->oPdf->setFontSize(5);
    $this->oPdf->cell(self::COLUNA1DEFAULT,3, "RREO: ANEXO 5(LRE, art 53, inciso III)", "", 0, "L");
    $this->oPdf->cell(self::COLUNA2GRANDE,3, "Em Reais", "", 1, "R");
    $this->oPdf->setBold(1);
    $this->oPdf->setUnderline(1);
    $this->oPdf->setFontSize(8);
    $this->oPdf->cell(self::COLUNA1DEFAULT, 10, 'DÍVIDA FISCAL LÍQUIDA', "TRB", 0, "C",1);
    $this->oPdf->setBold(0);
    $this->oPdf->setUnderline(0);
    $this->oPdf->setFontSize(5);
    $this->oPdf->cell(self::COLUNA2GRANDE,4, "SALDO", "T", 1, "C",1);
    $this->oPdf->cell(self::COLUNA1DEFAULT, 6, "", "", 0, "C");

    $iY = $this->oPdf->getY();
    $iX = $this->oPdf->getX();
    $this->oPdf->MultiCell(self::COLUNA2DEFAULT, 3,"Em 31/Dez/{$iAnoAnterior} \n (a)","TRB",'C',1);
    $this->oPdf->SetY($iY);
    $this->oPdf->SetX($iX+self::COLUNA2DEFAULT);

    $iY = $this->oPdf->getY();
    $iX = $this->oPdf->getX();
    $this->oPdf->MultiCell(self::COLUNA2DEFAULT, 3,"Em {$sMesFinal} \n (b)","TRB",'C',1);

    $this->oPdf->SetY($iY);
    $this->oPdf->SetX($iX+self::COLUNA2DEFAULT);
    $this->oPdf->MultiCell(self::COLUNA2DEFAULT, 3,"Em {$sMesAtual} \n (c)","TB",'C',1);

    $this->oPdf->setFontSize(8);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[1]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[1],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[2]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[2],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, \relatorioContabil::getIdentacao($this->aLinhas[3]->nivel) . $this->aLinhas[3]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[3],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, \relatorioContabil::getIdentacao($this->aLinhas[4]->nivel) . $this->aLinhas[4]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[4],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, \relatorioContabil::getIdentacao($this->aLinhas[5]->nivel) . $this->aLinhas[5]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[5],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, \relatorioContabil::getIdentacao($this->aLinhas[6]->nivel) . $this->aLinhas[6]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[6],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[7]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[7],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[8]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[8],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[9]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[9],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, $this->aLinhas[10]->descricao, "TRB", 0, "L");
    $this->imprimeValores($this->aLinhas[10],3);

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, " ", "", 1, "");
  }

  private function imprimeResultadoNominal(){

    $this->oPdf->setBold(1);
    $this->oPdf->setUnderline(1);
    $this->oPdf->setFontSize(8);
    $this->oPdf->cell(self::COLUNA1DEFAULT, 10, 'RESULTADO NOMINAL', "TRB", 0, "C",1);
    $this->oPdf->setBold(0);
    $this->oPdf->setUnderline(0);
    $this->oPdf->setFontSize(5);
    $this->oPdf->cell(self::COLUNA2GRANDE,4, "PERÍODO DE REFERÊNCIA", "T", 1, "C",1);
    $this->oPdf->cell(self::COLUNA1DEFAULT, 6, "", "", 0, "C");

    $iY = $this->oPdf->getY();
    $iX = $this->oPdf->getX();
    $this->oPdf->MultiCell(self::COLUNA2MEDIA, 3,"No Bimestre\n (VIc - VIb)","TRB",'C',1);

    $this->oPdf->SetY($iY);
    $this->oPdf->SetX($iX+self::COLUNA2MEDIA);
    $this->oPdf->MultiCell(self::COLUNA2MEDIA, 3,"Até o Bimestre\n (VIc - VIa)","TB",'C',1);

    $this->oPdf->setFontSize(8);
    $this->oPdf->cell(self::COLUNA1DEFAULT, 5,$this->aLinhas[11]->descricao,"TRB", 0,'L');
    $this->oPdf->cell(self::COLUNA2MEDIA, 5, db_formatar($this->aLinhas[11]->saldo_bimestre_atual, "f"), "TB", 0, "R");
    $this->oPdf->cell(self::COLUNA2MEDIA, 5, db_formatar($this->aLinhas[11]->saldo_bimestre_anterior, "f"), "TBL", 1, "R");

    $this->oPdf->cell(self::COLUNA1DEFAULT, 5, " ", "", 1, "");
  }

  private function imprimeMetaFiscal(){

    $this->oPdf->setBold(1);
    $this->oPdf->setUnderline(1);
    $this->oPdf->setFontSize(8);
    $this->oPdf->cell(self::COLUNA1GRANDE, 10, 'DISCRIMINAÇÃO DA META FISCAL', "TRB", 0, "C",1);
    $this->oPdf->setBold(0);
    $this->oPdf->setUnderline(0);
    $this->oPdf->cell(self::COLUNA2MEDIA,10, "VALOR CORRENTE", "T", 1, "C",1);
    $this->oPdf->setFontSize(6);
    $this->oPdf->cell(self::COLUNA1GRANDE, 5, $this->aLinhas[12]->descricao, "TRB", 0, "L");
    $this->oPdf->cell(self::COLUNA2MEDIA,5, db_formatar($this->aLinhas[12]->valor_corrente, 'f'), "TB", 1, "R");
  }
}
