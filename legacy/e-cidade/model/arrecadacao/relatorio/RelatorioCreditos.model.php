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

class RelatorioCreditos {

  /** @var PDFDocument */
  private $oPdf;

  /** @var array */
  public $aTipoOrigem = array(
    "numcgm" => "Numcgm",
    "matric" => "Matrícula",
    "inscr" => "Inscrição"
  );

  /** @var array */
  public $aOrdenador = array(
    "id" => "Identificador",
    "nome" => "Nome"
  );

  /** @var array */
  public $aOrdenacao = array(
    "asc" => "Ascendente",
    "desc" => "Descendente"
  );

  /**
   * @var object
   */
  private $oFiltros;

  /**
   * RelatorioCreditos constructor.
   */
  public function __construct() {

    $this->oFiltros = (object) array(
      "tipo_origem" => "numcgm",
      "ordenador" => "id",
      "ordenacao" => "asc"
    );
  }

  /**
   * @param stdClass $filtro
   */
  public function setFiltros(stdClass $filtro) {
    $this->oFiltros = $filtro;
  }

  /**
   * Adiciona Cabeçalho aos Registros do Relatório
   */
  private function adicionarCabecalho() {

    $sOrigem = $this->aTipoOrigem[$this->oFiltros->tipo_origem];

    $this->oPdf->setBold(true);
    $this->oPdf->cell(25, 4, $sOrigem,         1, 0, PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->cell(80, 4, "Nome",           1, 0, PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->cell(55, 4, "Tipo de Débito", 1, 0, PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->cell(30, 4, "Valor",          1, 1, PDFDocument::ALIGN_CENTER, 1);
    $this->oPdf->setBold(false);
  }

  /**
   * Adiciona Filtros ao Cabeçalho do Relatório
   */
  private function adicionarFiltrosCabecalho() {

    $sOrigem     = $this->aTipoOrigem[$this->oFiltros->tipo_origem];
    $sOrdenacao  = "{$this->aOrdenador[$this->oFiltros->ordenador]} - ";
    $sOrdenacao .= "{$this->aOrdenacao[$this->oFiltros->ordenacao]}";

    $this->oPdf->addHeaderDescription("Relatório de Créditos");
    $this->oPdf->addHeaderDescription("");
    $this->oPdf->addHeaderDescription("Origem: {$sOrigem}");
    $this->oPdf->addHeaderDescription("Ordenação: {$sOrdenacao}");
  }

  /**
   * @throws DBException
   * @throws ParameterException
   */
  public function emitir() {

    $rsCreditos = $this->getDados();
    $this->oPdf = new PDFDocument();
    $this->oPdf->SetFillColor(220);
    $this->adicionarFiltrosCabecalho();
    $this->oPdf->SetFontSize(6);
    $this->oPdf->open();
    $this->oPdf->addPage();
    $this->adicionarCabecalho();

    $lPreencher = false;
    $iTotalCreditos = pg_num_rows($rsCreditos);
    for ($iIndice = 0; $iIndice <= $iTotalCreditos; $iIndice++) {

      $oCredito = db_utils::fieldsMemory($rsCreditos, $iIndice);

      if ($this->oPdf->getAvailHeight() < 5) {
        $this->oPdf->addPage();
        $this->adicionarCabecalho();
      }

      $this->oPdf->cell(25, 4, $oCredito->id,                      0, 0, PDFDocument::ALIGN_CENTER, $lPreencher);
      $this->oPdf->cell(80, 4, $oCredito->nome,                    0, 0, PDFDocument::ALIGN_LEFT,   $lPreencher);
      $this->oPdf->cell(55, 4, $oCredito->origem,                  0, 0, PDFDocument::ALIGN_CENTER, $lPreencher);
      $this->oPdf->cell(30, 4, db_formatar($oCredito->valor, 'f'), 0, 1, PDFDocument::ALIGN_RIGHT,  $lPreencher);

      $lPreencher = !$lPreencher;
    }

    $this->oPdf->showPDF();
  }

  /**
   * @return resource
   * @throws DBException
   * @throws ParameterException
   */
  private function getDados() {

    $sJoins = "";
    db_sel_instit(db_getsession("DB_instit"), "db21_regracgmiptu");

    if (!isset($db21_regracgmiptu)) {
      $db21_regracgmiptu = 0;
    }

    switch ($this->oFiltros->tipo_origem) {
      case "matric":
        $sCampos = "arrematric.k00_matric as id, (select rvnome  from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'M', arrematric.k00_matric) limit 1) as nome, ";
        $sJoins .= " inner join arrematric on arrematric.k00_numpre = abatimentorecibo.k127_numprerecibo ";
        break;

      case "numcgm":
        $sCampos = "arrenumcgm.k00_numcgm as id, cgm.z01_nome as nome, ";
        $sJoins .= " inner join arrenumcgm on arrenumcgm.k00_numpre = abatimentorecibo.k127_numprerecibo ";
        $sJoins .= " inner join cgm on cgm.z01_numcgm = arrenumcgm.k00_numcgm ";
        break;

      case "inscr":
        $sCampos = "arreinscr.k00_inscr as id, (select rvnome  from fc_busca_envolvidos(true, {$db21_regracgmiptu}, 'I', arreinscr.k00_inscr) limit 1) as nome, ";
        $sJoins .= " inner join arreinscr on arreinscr.k00_numpre = abatimentorecibo.k127_numprerecibo ";
        break;

      default:
        throw new ParameterException("Opção de Origem é inválida.");
    }

    $sCampos .= " arretipo.k00_descr as origem, k125_valordisponivel as valor";
    $sSql = "select {$sCampos}
      from abatimentorecibo
        inner join abatimento             on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento
        inner join recibo                 on recibo.k00_numpre = abatimentorecibo.k127_numprerecibo
        inner join abatimentoarreckey     on abatimentoarreckey.k128_abatimento   = abatimento.k125_sequencial
        inner join arreckey               on arreckey.k00_sequencial              = abatimentoarreckey.k128_arreckey
        inner join arretipo               on arretipo.k00_tipo                    = arreckey.k00_tipo
        inner join tabrec                 on tabrec.k02_codigo = recibo.k00_receit
        inner join histcalc               on histcalc.k01_codigo = recibo.k00_hist
        left join abatimentotransferencia on k158_abatimentoorigem = k125_sequencial
        left join abatimentoutilizacao    on k157_sequencial = k158_abatimentoutilizacao
    ";
    $sSql .= $sJoins;
    $sSql .= " where abatimento.k125_tipoabatimento = 3";
    $sSql .= " group by id, origem, nome, valor, recibo.k00_numpre,";
    $sSql .= " recibo.k00_receit, recibo.k00_hist, tabrec.k02_descr";

    $sOrdenador = pg_escape_string($this->oFiltros->ordenador);
    $sOrdenacao = pg_escape_string($this->oFiltros->ordenacao);
    $sSql .= " order by {$sOrdenador} {$sOrdenacao} ";

    $rsCreditos = db_query($sSql);

    if (!$rsCreditos && pg_num_rows($rsCreditos) == 0) {
      throw new DBException("Nenhum registro encontrado para os filtros selecionados.");
    }

    return $rsCreditos;
  }
}
