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

class RelatorioDeDistribuicao {

  /**
   * @var PDFDocument
   */
  private $oPdf;

  /**
   * Largura padrão de uma linha do relatório.
   * @var integer
   */
  private $iLargura;
  
  /**
   * Altura padrão de uma linha do relatório.
   * @var integer
   */
  private $iAltura;

  /**
   * Data inicial do período.
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * Data final do período.
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * Id da instituição para filtro.
   * @var integer
   */
  private $iInstituicao;

  /**
   * Diz se o relatório deve ser agrupado por grupo/subgrupo.
   * @var bool
   */
  private $lAgruparGrupoSubgrupo;

  /**
   * Informa se deve buscar materias com distribuição zerada.
   * @var bool
   */
  private $lBuscarDistribuicaoZerada;

  /**
   * Tipo de quebra de página.
   * @var integer
   */
  private $iQuebraPagina;

  /**
   * Códigos dos grupos e subgrupos para filtro, separados por vírgula.
   * @var string
   */
  private $sGrupoSubgrupo;

  /**
   * Código dos departamentos para filtro, separados por vírgula.
   * @var string
   */
  private $sDepartamentos;

  /**
   * Grupos e Subgrupos para filtro dos materiais.
   * @var stdClass[]
   */
  private $aGrupos;

  /**
   * Departamentos usados para filtro do relatório.
   * @var stdClass[]
   */
  private $aDepartamentos = array();

  /**
   * Sem quebra de pagina
   * @var int
   */
  const SEM_QUEBRA_PAGINA = 0;

  /**
   * Quebra de pagina por departamento.
   * @var integer
   */
  const QUEBRA_PAGINA_DEPARTAMENTO = 1;

  /**
   * Valor da opção para agrupar por grupo e subgrupo.
   * @var integer
   */
  const OPCAO_AGRUPAR_GRUPO_SUBGRUPO = 1;

  /**
   * Valor da opção para exibição de distribuição zerada.
   * @var integer
   */
  const OPCAO_EXIBIR_DISTRIBUICAO_ZERADA = 1;

  private $aMeses = array();

  /**
   * @param DBDate  $oDataInicial
   * @param DBDate  $oDataFinal
   * @param integer $iInstituicao
   * @param bool    $lBuscarDistribuicaoZerada
   */
  function __construct(DBDate $oDataInicial, DBDate $oDataFinal, $iInstituicao, $lBuscarDistribuicaoZerada = false) {

    $this->oPdf                      = new PDFDocument(PDFDocument::PRINT_LANDSCAPE);
    $this->oDataInicial              = $oDataInicial;
    $this->oDataFinal                = $oDataFinal;
    $this->iInstituicao              = $iInstituicao;
    $this->lBuscarDistribuicaoZerada = $lBuscarDistribuicaoZerada;

    $aMeses = DBDate::getMesesNoIntervalo($this->oDataInicial, $this->oDataFinal, false);
    foreach ($aMeses  as $iAno => $aMes) {

       foreach ($aMes as $iMes ) {

         $oAno                            = new stdClass();
         $oAno->ano                       = $iAno;
         $oAno->mes                       = $iMes;
         $this->aMeses["{$iMes}/{$iAno}"] = $oAno;
       }
    }
    if (count($this->aMeses) > 12) {
      throw new BusinessException("O intervalo de emissão do relatório não pode ser maior que doze meses.");
    }
  }

  /**
   * Configura se o relatório deverá agrupar os dados pro grupo/subgrupo.
   * @param bool $lAgruparGrupoSubgrupo
   */
  public function setAgruparGrupoSubGrupo($lAgruparGrupoSubgrupo) {
    $this->lAgruparGrupoSubgrupo = $lAgruparGrupoSubgrupo;
  }

  /**
   * Informa como deve funcionar a quebra de página.
   * @param integer $iQuebraPagina
   */
  public function setQuebrarPagina($iQuebraPagina) {
    $this->iQuebraPagina = $iQuebraPagina;
  }

  /**
   * Seta os códigos dos grupos e subgrupos para filtro, separados por vírgula.
   * @param string $sGrupoSubgrupo
   */
  public function setGruposSubgrupos($sGrupoSubgrupo) {
    $this->sGrupoSubgrupo = $sGrupoSubgrupo;
  }

  /**
   * Seta os códigos dos departamentos para filtro, separados por vírgula.
   * @param string $sDepartamentos
   */
  public function setDepartamentos($sDepartamentos) {
    $this->sDepartamentos = $sDepartamentos;
  }

  /**
   * Monta a árvore do grupos e subgrupos selcionados para a emissão do relatório.
   * @param $oGrupo
   */
  private function criarGrupoPai($oGrupo) {

    if ($oGrupo->grupoPai != 0 && !isset($this->aGrupos[$oGrupo->grupoPai])) {

      $oMaterialGrupo = new MaterialGrupo($oGrupo->codigo);
      $oGrupoPai      = $oMaterialGrupo->getEstruturaPai();

      if (empty($oGrupoPai)) {
        return;
      }

      $oGrupo             = new stdClass();
      $oGrupo->codigo     = $oGrupoPai->getCodigo();
      $oGrupo->estrutural = $oGrupoPai->getEstrutural();
      $oGrupo->descricao  = "{$oGrupo->estrutural} - {$oGrupoPai->getDescricao()}";
      $oGrupo->grupoPai   = 0;
      $oGrupo->nivel      = $oGrupoPai->getNivel();
      $oGrupo->itens      = array();
      $oGrupo->meses      = array();
      $this->processaMeses($oGrupo);

      if ($oGrupoPai->getEstruturaPai() != '') {
        $oGrupo->grupoPai = $oGrupoPai->getEstruturaPai()->getCodigo();
      }
      $this->aGrupos[$oGrupo->codigo] = $oGrupo;
      $this->criarGrupoPai($oGrupo);
    }
  }

  /**
   * Adiciona o valor zerado para os meses dentro do período que não possui valores.
   * @param stdClass $oLinha
   */
  private function processaMeses($oLinha) {

    foreach ($this->aMeses as $iMesAno => $oMes) {

      if (!isset($oLinha->meses[$iMesAno])) {
        $oLinha->meses[$iMesAno] = 0.0;
      }
    }
    //ksort($oLinha->meses);
  }

  /**
   * Totaliza uma linha.
   * @param stdClass $oLinha Linha para ser totalizada.
   */
  private function totalizarLinha($oLinha) {

    $oLinha->totalPeriodo = 0.0;
    $oLinha->mediaPeriodo = 0.0;

    foreach ($oLinha->meses as $iMes => $nValor) {
      $oLinha->totalPeriodo += $nValor;
    }
    $oLinha->mediaPeriodo = 0;
    if ( count($oLinha->meses) > 0) {
      $oLinha->mediaPeriodo = round($oLinha->totalPeriodo / count($oLinha->meses), 2);
    }
  }

  /**
   * Totaliza as linhas de um grupo.
   * @param stdClass $oMaterial Material que será totalizado no $oGrupo
   * @param stdClass $oGrupo    Grupo onde a totalização será feita.
   */
  private function totalizarGrupos($oMaterial, $oGrupo) {

    foreach($oMaterial->meses as $iMes => $nValorMes) {
      $oGrupo->meses[$iMes] += $nValorMes;
    }

    $oGrupo->totalPeriodo += $oMaterial->totalPeriodo;
    $oGrupo->mediaPeriodo += $oMaterial->mediaPeriodo;

    if ($oGrupo->grupoPai == 0) {
      return;
    }

    if (isset($this->aDepartamentos[$oMaterial->depto]->itens[$oGrupo->grupoPai])) {
      $this->totalizarGrupos($oMaterial, $this->aDepartamentos[$oMaterial->depto]->itens[$oGrupo->grupoPai]);
    }
  }

  /**
   * Busca os materiais de acordo com os filtros definidos nos atributos da classe.
   * @return stdClass[] Materiais
   * @throws ParameterException
   */
  private function getMateriais() {

    $oDaoMaterial = new cl_matmater();
    $sCampos      = " m60_codmater as codigo, m60_descr as descricao, m61_descr as unidade";

    $sCamposGrupo = " , 0 as codigogrupo ";
    if ($this->lAgruparGrupoSubgrupo) {
      $sCamposGrupo = " , m65_sequencial as codigogrupo ";
    }
    $sCampos .= $sCamposGrupo;

    $aOrdem   = array();
    $aWhere   = array();
    $aWhere[] = " instit = {$this->iInstituicao} ";

    $aOrdem[] = " m60_descr ";

    if ($this->sDepartamentos) {
      $aWhere[] = " m70_coddepto in ({$this->sDepartamentos}) ";
    }

    if ($this->sGrupoSubgrupo) {
      $aWhere[] = " db121_sequencial in ({$this->sGrupoSubgrupo}) ";
    }

    $sCampoDepartamento = ", 0 as depto, '' as departamento";
    if ($this->iQuebraPagina == RelatorioDeDistribuicao::QUEBRA_PAGINA_DEPARTAMENTO) {
      $sCampoDepartamento = ", m70_coddepto as depto, descrdepto as departamento ";
    }
    $sCampos .= $sCampoDepartamento;
    $sWhere   = " where " . implode(" and  ", $aWhere);
    $sOrdem   = " order by " . implode(",  ", $aOrdem);

    $sql  = " select distinct {$sCampos}  ";
    $sql .= " from matmater ";
    $sql .= "      inner join matunid                      on m60_codmatunid = m61_codmatunid ";
    $sql .= "      inner join matmatermaterialestoquegrupo on m60_codmater = m68_matmater ";
    $sql .= "      inner join materialestoquegrupo         on m68_materialestoquegrupo = m65_sequencial ";
    $sql .= "      inner join db_estruturavalor            on db121_sequencial = m65_db_estruturavalor ";
    $sql .= "      inner join matestoque                   on m60_codmater = m70_codmatmater ";
    $sql .= "      inner join db_depart                    on coddepto     = m70_coddepto ";


    $sql .= "{$sWhere} {$sOrdem} ";

    $rsMateriais = $oDaoMaterial->sql_record($sql);

    $aMateriais = array();
    for ($i = 0; $i < $oDaoMaterial->numrows; $i++) {

      $oMaterial               = db_utils::fieldsMemory($rsMateriais, $i);
      $oMaterial->totalPeriodo = 0.0;
      $oMaterial->mediaPeriodo = 0.0;
      $aMateriais[]            = $oMaterial;

      if (!isset($this->aDepartamentos[$oMaterial->depto])) {

        $oDeptartamento            = new stdClass();
        $oDeptartamento->codigo    = $oMaterial->depto;
        $oDeptartamento->descricao = $oMaterial->departamento;
        $oDeptartamento->itens     = array();
        $this->aDepartamentos[$oDeptartamento->codigo] = $oDeptartamento;
      }
    }
    return $aMateriais;
  }

  /**
   * Busca as distribuições mensais para os materiais.
   * @param stdClass[] $aMateriais
   *
   * @throws ParameterException
   */
  private function getDistribuicaoMensal($aMateriais) {

    $sDataInicial = $this->oDataInicial->convertTo("Y-m-d");
    $sDataFinal   = $this->oDataFinal->convertTo("Y-m-d");

    $sCampos  = " EXTRACT(MONTH FROM m80_data) as mes, EXTRACT(YEAR FROM m80_data) as ano, sum(m82_quant) as quantidade ";
    $sSqlDadosMovimentacao  = " select {$sCampos}  ";
    $sSqlDadosMovimentacao .= " from  matestoque ";
    $sSqlDadosMovimentacao .= "       inner join matestoqueitem   on m71_codmatestoque = m70_codigo ";
    $sSqlDadosMovimentacao .= "       inner join matestoqueinimei on m82_matestoqueitem = m71_codlanc ";
    $sSqlDadosMovimentacao .= "       inner join matestoqueini    on m80_codigo = m82_matestoqueini ";
    $sSqlDadosMovimentacao .= "       inner join matestoquetipo   on m81_codtipo = m80_codtipo ";
    $sSqlDadosMovimentacao .= "       inner join db_depart        on coddepto = m70_coddepto ";

    $sOrdem   = " ano, mes ";
    $aWhere[] = " m70_codmatmater  = $1 ";
    $aWhere[] = " m70_codmatmater  = $1 ";
    $aWhere[] = " m81_tipo = 2";
    $aWhere[] = " m80_data BETWEEN $2 and $3 ";
    $aWhere[] = " instit = $4 ";

    switch ($this->iQuebraPagina) {

      case RelatorioDeDistribuicao::SEM_QUEBRA_PAGINA:

        if (!empty($this->sDepartamentos)) {
          $aWhere[] = " m70_coddepto in ($this->sDepartamentos) ";
        }
        break;
      case RelatorioDeDistribuicao::QUEBRA_PAGINA_DEPARTAMENTO:

        $aWhere[] = " m70_coddepto = $5 ";
        break;
    }
    $sWhere    = implode(" and ", $aWhere);

    $sAgrupar = "mes, ano ";

    $sSqlDadosMovimentacao .= " where {$sWhere} group by {$sAgrupar} order by {$sOrdem} ";
    pg_prepare("dados_movimentacao", $sSqlDadosMovimentacao);

    foreach ($aMateriais as $iIndice => $oMaterial) {

      $aParametros = array($oMaterial->codigo, $sDataInicial, $sDataFinal, $this->iInstituicao);

      if ($this->iQuebraPagina == self::QUEBRA_PAGINA_DEPARTAMENTO) {
        $aParametros[] = $oMaterial->depto;
      }

      $rsDistribuicao = pg_execute("dados_movimentacao", $aParametros);
      $iTotalLinhas   = pg_num_rows($rsDistribuicao);
      $aDistribuicao  = array();

      for ($iDistribuicao = 0; $iDistribuicao < $iTotalLinhas; $iDistribuicao++) {

        $oMes = db_utils::fieldsMemory($rsDistribuicao, $iDistribuicao);
        $aDistribuicao["{$oMes->mes}/{$oMes->ano}"] = $oMes->quantidade;
        unset($oMes);
      }
      $oMaterial->meses = $aDistribuicao;
      $this->processaMeses($oMaterial);
      $this->totalizarLinha($oMaterial);
    }
  }

  /**
   * Busca os grupos/subgrupos pelos filtros.
   * @return stdClass[]
   */
  private function getGrupoSubgrupo() {

    $oGrupo               = new stdClass();
    $oGrupo->codigo       = 0;
    $oGrupo->estrutural   = "";
    $oGrupo->descricao    = "";
    $oGrupo->grupoPai     = 0;
    $oGrupo->nivel        = 1;
    $oGrupo->totalPeriodo = 0.0;
    $oGrupo->mediaPeriodo = 0.0;
    $oGrupo->itens        = array();
    $oGrupo->meses        = array();
    $this->processaMeses($oGrupo);

    $aGrupos[$oGrupo->codigo] = $oGrupo;
    $this->aGrupos = $aGrupos;

    if ($this->lAgruparGrupoSubgrupo) {

      $aGrupos = array();

      $oDaoGrupoSubGrupo = new cl_materialestoquegrupo();

      /**
       * Busca todos os grupos e subgrupos e seus respectivos grupos pais.
       */
      $sCampos         = " db121_sequencial, m65_sequencial, db121_estrutural, db121_descricao, db121_estruturavalorpai, db121_nivel ";
      $sWhere          = " db121_sequencial in ({$this->sGrupoSubgrupo}) ";
      $sOrdem          = " db121_estrutural ";
      $sql             = $oDaoGrupoSubGrupo->sql_query(null, $sCampos, $sOrdem, $sWhere);
      $rsGrupoSubgrupo = $oDaoGrupoSubGrupo->sql_record($sql);

      for ($i = 0; $i < $oDaoGrupoSubGrupo->numrows; $i++) {

        $oGrupoAux          = db_utils::fieldsMemory($rsGrupoSubgrupo, $i);
        $oGrupoMaterial     = new MaterialGrupo($oGrupoAux->m65_sequencial);
        $oGrupo             = new stdClass();
        $oGrupo->codigo     = $oGrupoAux->m65_sequencial;
        $oGrupo->estrutural = $oGrupoAux->db121_estrutural;
        $oGrupo->descricao  = $oGrupo->estrutural . " - " . $oGrupoAux->db121_descricao;
        $oGrupo->grupoPai   = 0;
        if ($oGrupoMaterial->getEstruturaPai() != '') {
          $oGrupo->grupoPai = $oGrupoMaterial->getEstruturaPai()->getCodigo();
        }
        $oGrupo->nivel        = $oGrupoAux->db121_nivel;
        $oGrupo->totalPeriodo = 0.0;
        $oGrupo->mediaPeriodo = 0.0;
        $oGrupo->itens        = array();
        $oGrupo->meses        = array();
        $this->processaMeses($oGrupo);

        $aGrupos[$oGrupo->codigo] = $oGrupo;
      }

      $this->aGrupos = $aGrupos;
      /**
       * Agrupamos os grupos conforme sua organizacao
       */
      foreach ($this->aGrupos as $oGrupo) {
        $this->criarGrupoPai($oGrupo);
      }
      uasort($this->aGrupos,
        function ($oGrupo, $oGrupoDepois) {
          return $oGrupo->estrutural > $oGrupoDepois->estrutural;
        }
      );
    }

    foreach ($this->aDepartamentos as $oDepartamento) {

      foreach ($this->aGrupos as $oGrupo) {
        $oDepartamento->itens[$oGrupo->codigo] = clone $oGrupo;
      }
    }
    return $this->aGrupos;
  }

  /**
   * Busca as informações para as linhas do relatórios.
   * @return stdClass[] Dados para gerar as linhas dos relatório.
   */
  private function getDados() {

    $aMateriais = $this->getMateriais();
    $this->getDistribuicaoMensal($aMateriais);

    $this->aGrupos = $this->getGrupoSubgrupo();
    foreach ($aMateriais as $oMaterial) {

      $oGrupo          = $this->aDepartamentos[$oMaterial->depto]->itens[$oMaterial->codigogrupo];
      $oGrupo->itens[] = $oMaterial;
      $this->totalizarGrupos($oMaterial, $oGrupo);
    }

    return $this->aDepartamentos;
  }

  /**
   * Realizar as configurações iniciais do pdf.
   */
  private function configurarPdf() {

    $iMargin        = 10;
    $this->iAltura  = 4;
    $this->iLargura = $this->oPdf->getAvailWidth() - $iMargin;

    $sQuebraDePagina     = $this->iQuebraPagina == self::QUEBRA_PAGINA_DEPARTAMENTO ? "Sim" : "Não";
    $sAgrupadoGrupo      = $this->lAgruparGrupoSubgrupo                             ? "Sim" : "Não";
    $sDistribuicaoZerada = $this->lBuscarDistribuicaoZerada                         ? "Sim" : "Não";

    $this->oPdf->SetLeftMargin($iMargin);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(true);
    $this->oPdf->SetFillcolor(235);
    $this->oPdf->SetFont('arial', '', 6);
    $this->oPdf->addHeaderDescription("Relatório de Distribuição Mensal de Materiais");
    $this->oPdf->addHeaderDescription("Período: {$this->oDataInicial->getMes()}/{$this->oDataInicial->getAno()} até {$this->oDataFinal->getMes()}/{$this->oDataFinal->getAno()}");
    $this->oPdf->addHeaderDescription("Distribuição Zerada: {$sDistribuicaoZerada}");
    $this->oPdf->addHeaderDescription("Agrupado por Grupo/Subgrupo: {$sAgrupadoGrupo}");
    $this->oPdf->addHeaderDescription("Quebra de Página por Departamento: {$sQuebraDePagina}");
  }

  /**
   * Escreve o cabeçalho a tabela.
   * @throws ParameterException
   */
  private function escreverCabecalhoTabela() {

    $this->oPdf->setBold(true);
    $aMeses = array();

    $this->oPdf->Cell($this->iLargura * 0.28, $this->iAltura, "Material", 'TBR', 0, 'C');

    $nLarguraMes = ($this->iLargura * 0.6) / count($this->aMeses);

    foreach ($this->aMeses as $oMes) {

      $sLabelMes = substr(DBDate::getMesExtenso($oMes->mes), 0, 3)."/{$oMes->ano}";
      $this->oPdf->Cell($nLarguraMes, $this->iAltura, $sLabelMes, 'TBL', 0, 'C');
    }

    $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, "Total", 'TBL', 0, 'C');
    $this->oPdf->Cell($this->iLargura * 0.06, $this->iAltura, "Média Período", 'TBL', 1, 'C');
    $this->oPdf->setBold(false);
  }

  /**
   * Adiciona uma nova página com os cabeçalho da página e da tabela.
   */
  private function escreverCabecalhos() {

    $this->oPdf->AddPage();
    $this->escreverCabecalhoTabela();
  }

  /**
   * Totaliza as linhas de um departamento e retorna como um objeto.
   * @param stdClass $oDepartamento
   *
   * @return stdClass
   */
  private function totalizarDepartamento($oDepartamento) {

    $oLinhaTotal               = new stdClass();
    $oLinhaTotal->codigo       = "";
    $oLinhaTotal->descricao    = "Total Geral Mensal";
    $oLinhaTotal->meses        = array();
    $oLinhaTotal->totalPeriodo = 0.0;
    $oLinhaTotal->mediaPeriodo = 0.0;
    $this->processaMeses($oLinhaTotal);
    foreach ($oDepartamento->itens as $oGrupo) {

      foreach ($oGrupo->itens as $oLinha) {

        foreach ($oLinha->meses as $iMes => $nValor) {

          if (empty($oLinha->estrutural)) {
            $oLinhaTotal->meses[$iMes] += $nValor;
          }
        }
      }
    }
    $oLinhaTotal->totalPeriodo = array_sum($oLinhaTotal->meses);
    $oLinhaTotal->mediaPeriodo = $oLinhaTotal->totalPeriodo / count($oLinhaTotal->meses);
    return $oLinhaTotal;
  }

  /**
   * Escreve uma linha do relatório.
   * @param stdClass $oLinha             Objeto com mas informações da linha a ser escrita.
   * @param bool     $lPintar            Informa se deve pintar a linha.
   * @param bool     $lQuebrarLinhaAntes Informa se deve deixar uma linha em branco antes de escrever a nova linha.
   */
  private function escreverLinha(stdClass $oLinha, $lPintar = false, $lQuebrarLinhaAntes = false) {

    if (!$this->lBuscarDistribuicaoZerada && $oLinha->totalPeriodo == 0) {
     return;
    }

    if ($lQuebrarLinhaAntes) {
      $this->oPdf->ln();
    }

    $iAlturaMulticell = $this->oPdf->getMultiCellHeight($this->iLargura * 0.28, $this->iAltura, $oLinha->descricao);
    if ($this->oPdf->getAvailHeight() - 12 < $iAlturaMulticell) {

      $lBold = $this->oPdf->getBold();
      $this->escreverCabecalhos();
      $this->oPdf->setBold($lBold);
    }

    $this->oPdf->setAutoNewLineMulticell(false);
    $this->oPdf->MultiCell($this->iLargura * 0.28, $this->iAltura, $oLinha->descricao, 'TBR', 'L', $lPintar);

    $nLarguraMes = ($this->iLargura * 0.6) / count($oLinha->meses);
    foreach ($this->aMeses as $sMes  => $oMes) {
      $this->oPdf->Cell($nLarguraMes, $iAlturaMulticell , db_formatar($oLinha->meses[$sMes], 'f'), 'TBL', 0, 'R', $lPintar);
    }

    $this->oPdf->Cell($this->iLargura * 0.06, $iAlturaMulticell , db_formatar($oLinha->totalPeriodo, 'f'), 'TBL', 0, 'R', $lPintar);
    $this->oPdf->Cell($this->iLargura * 0.06, $iAlturaMulticell , db_formatar($oLinha->mediaPeriodo, 'f'), 'TBL', 1, 'R', $lPintar);
    $this->oPdf->setBold(false);
  }

  /**
   * Verifica se há valores para o relatório.
   * @throws Exception
   */
  private function validaDados() {

    foreach ($this->aDepartamentos as $oDepartamento) {

      $oDepartamentoTotalizador = $this->totalizarDepartamento($oDepartamento);
      if ($this->lBuscarDistribuicaoZerada || $oDepartamentoTotalizador->totalPeriodo != 0) {
        return;
      }
    }
    throw new Exception("Não existem dados para os filtros informados.");
  }

  /**
   * Emite o relatório.
   */
  public function emitir() {

    $this->getDados();
    $this->configurarPdf();
    $this->validaDados();

    foreach ($this->aDepartamentos as $oDepartamento) {

      $oDepartamentoTotalizador = $this->totalizarDepartamento($oDepartamento);
      if (!$this->lBuscarDistribuicaoZerada && $oDepartamentoTotalizador->totalPeriodo == 0) {
        continue;
      }

      $lQuebrarLinha = false;
      $this->oPdf->AddPage();
      if ($this->iQuebraPagina == self::QUEBRA_PAGINA_DEPARTAMENTO) {

        $this->oPdf->setBold(true);
        $this->oPdf->Cell($this->iLargura, $this->iAltura, "Departamento: {$oDepartamento->descricao}", 'T', 1);
        $this->oPdf->setBold(false);
      }
      $this->escreverCabecalhoTabela();

      foreach($oDepartamento->itens as $oGrupo) {

        if ($this->lAgruparGrupoSubgrupo) {

          $this->oPdf->setBold(true);
          $lQuebrarLinha =  $oGrupo->nivel == 1 && $lQuebrarLinha;
          $this->escreverLinha($oGrupo, true, $lQuebrarLinha);
          $this->oPdf->setBold(false);
          $lQuebrarLinha = true;
        }

        if (count($oGrupo->itens) > 0) {

          foreach ($oGrupo->itens as $oMaterial) {
            $this->escreverLinha($oMaterial);
          }
        }
      }
      $this->oPdf->setBold(true);
      $this->escreverLinha($oDepartamentoTotalizador);
    }

    $this->oPdf->showPDF('relatorio_de_distribuicao');
  }
}