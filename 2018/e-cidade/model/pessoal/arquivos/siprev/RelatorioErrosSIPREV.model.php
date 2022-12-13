<?php
/**
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
require_once(modification("fpdf151/pdf.php"));

class RelatorioErrosSIPREV {

  const ALTURA_LINHA   = 5;
  const PREENCHE       = true;
  const QUEBRA         = true;
  const BORDA          = true;
  const LARGURA_MAXIMA = 279;
  private $arquivos = array(
    "01"   => "Servidores",
    "02"   => "Dependentes",
    "03"   => "Órgãos",
    "04"   => "Carreiras",
    "05"   => "Cargos",
    "06"   => "Alíquotas",
    "07"   => "Pensionistas",
    "08.1" => "Históricos Funcionais - RGPS",
    "08.2" => "Históricos Funcionais - RPPS",
    "09"   => "Históricos Financeiros",
    "10"   => "Benefícios dos Servidores",
    "11"   => "Benefícios dos Pensionistas",
    "12"   => "Tempo de Contribuição - RGPS",
    "13"   => "Tempo de Contribuição - RPPS",
    "14"   => "Tempos Fictícios",
    "15"   => "Tempo sem Contribuição",
    "16"   => "Funções Gratificadas",
  );
  private $aErros = array();
  private $pdf;

  /**
   * Construtor do Relatório
   */
  public function __construct(array $erros = array()) {

    global $head2;
    $head2 = "Relatório de Inconsistências SIPREV";

    $this->aErros = $erros;
    $this->pdf    = new PDF("L");
    $this->pdf->open();
    $this->pdf->aliasNbPages();
    $this->pdf->setAutoPageBreak(false);
    $this->pdf->setfillcolor(235);
    $this->pdf->setfont('arial', 'b', 8);
  }

  /**
   * [escreverBloco description]
   * @param  [type] $grupo [description]
   * @param  [type] $chave [description]
   * @param  FPDF   $pdf   [description]
   * @return [type]        [description]
   */
  private function escreverBloco($grupo, $chave, FPDF &$pdf) {

    /**
     * Item sem erro não será impresso
     */
    if (!$grupo) {
      return;
    }

    $cabecalhos = $this->getHeaders($chave);

    $this->escreverCabecalho("Arquivo:  {$chave} - " . $this->arquivos[$chave], $cabecalhos, $pdf);

    $linha = 0;

    foreach ($grupo as $dados) {

      if (++$linha == 31) {
        $linha = 0;
        $this->escreverCabecalho("Arquivo:  {$chave} - " . $this->arquivos[$chave], $cabecalhos, $pdf);
      }
      $this->escreverLinha($dados, $cabecalhos, $pdf);
    }
  }

  public function criar() {

    array_walk($this->aErros, array($this, 'escreverBloco'), $this->pdf);
    $this->pdf->output('tmp/inconsistencias_siprev.pdf', false, true);
  }

  private function escreverCabecalho($titulo, array $itens, FPDF &$pdf) {

    $pdf->addPage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(self::LARGURA_MAXIMA, self::ALTURA_LINHA, mb_strtoupper($titulo), self::BORDA, self::QUEBRA, "C", self::PREENCHE);

    for($indice = 1, $quantidade = count($itens); $indice <= $quantidade; $indice++) {

      $QUEBRA = $indice == $quantidade;
      $item   = $itens[$indice-1];
      $pdf->cell($item['largura'], self::ALTURA_LINHA, $item['conteudo'], self::BORDA, $QUEBRA, "C", self::PREENCHE);
    }
  }

  private function escreverLinha($dados, $cabecalhos, &$pdf) {

    $pdf->setfont('arial','',8);

    $itens = count($cabecalhos);
    $indice = 0;
    foreach ($cabecalhos as $item) {

      $QUEBRA = ++$indice == $itens;
      list($chave, $valor)      = each($dados);
      list($largura, $conteudo) = each($item);
      $pdf->cell($item['largura'], self::ALTURA_LINHA, " " .$valor, self::BORDA, $QUEBRA, "L", !self::PREENCHE);
    }
  }

  private function getHeaders($arquivoID) {

    switch($arquivoID) {
    case "01":
    case "02":
    case "08.1":
    case "08.2":
    case "10":
    case "11":
    case "16":
      $headers = array(
        array("largura" => 80,                   "conteudo" => "Instituição"),
        array("largura" => 100,                  "conteudo" => "Servidor"),
        array("largura" => self::LARGURA_MAXIMA - 180, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "03":
      $headers = array(
        array("largura" => 130,                  "conteudo" => "Instituição"),
        array("largura" => self::LARGURA_MAXIMA - 130, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "07":
      $headers = array(
        array("largura" => 80,                   "conteudo" => "Instituição"),
        array("largura" => 100,                  "conteudo" => "Pensionista"),
        array("largura" => self::LARGURA_MAXIMA - 180, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "12":
    case "13":
    case "14":
    case "15":
      $headers = array(
        array("largura" => 25,                   "conteudo" => "Assentamento"),
        array("largura" => 70,                   "conteudo" => "Instituição"),
        array("largura" => 100,                  "conteudo" => "Servidor"),
        array("largura" => self::LARGURA_MAXIMA - 195, "conteudo" => "Erro Encontrado"),
      );
      break;
    case "09":
    case "04":
    case "05":
    case "06":
      $headers = array();
      break;
    }

    return $headers;
  }
}
