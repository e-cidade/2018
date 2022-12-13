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
 * classe para manipulação das linhas dos relatorios contábeis
 * Class linhaRelatorioContabil
 * @package Contabilidade
 */
class linhaRelatorioContabil {

  private $codigo    = null;
  private $relatorio = null;
  private $colunas   = array();
  private $lEncode   = false;
  private $iPeriodo  = null;
  private $iOrdem            = 0;
  private $lTotalizar        = null;
  private $sDescricao        = null;
  private $iNivel            = 0;
  private $lpermiteDesdobrar = false;

  /**
   * Origem dos dados da linha
   * @var int
   */
  private $iOrigemDados = 0;

  /**
   * Indica que a linnha nao possui origem dos dados definidas
   * @var integer;
   */
  const SEM_ORIGEM = 0;

  /**
   * Indica que os dados da linha tem a origem do balancete de Receita
   * @var integer
   */
  const ORIGEM_RECEITA = 1;

  /**
   * Indica que os dados da linha tem a origem do balancete de Despesa
   * @var integer
   */
  const ORIGEM_DESPESA = 2;

  /**
   * Indica que os dados da linha tem a origem do balancete de Verificação
   * @var integer
   */
  const ORIGEM_VERIFICACAO = 3;

  /**
   * Indica que os dados da linha tem a origem das movimentações de restos a pagar
   * @var integer
   */
  const ORIGEM_RESTOS_PAGAR = 4;

  /**
   * Instância uma nova linha do relatório contábil
   * @param integer $iCodRel código do relatório
   * @param integer $iCodLinha código da linha
   */
  public function __construct($iCodRel, $iCodLinha) {

    $this->codigo    = $iCodLinha;
    $this->relatorio = $iCodRel;
    $oDaoLinhasRel = db_utils::getDao("orcparamseq");

    $sSqlLinhaRel  = $oDaoLinhasRel->sql_query_file($this->relatorio, $this->codigo,
                                                                          "o69_labelrel,
                                                                          o69_totalizador,
                                                                          o69_desdobrarlinha,
                                                                          o69_ordem,
                                                                          o69_origem,
                                                                          coalesce(o69_nivellinha, 0) as o69_nivellinha"
                                                                           );
    $rsLinhas      = $oDaoLinhasRel->sql_record($sSqlLinhaRel);
    if ($oDaoLinhasRel->numrows == 0) {
      return null;
    }
    $oDadosLinha             = db_utils::fieldsMemory($rsLinhas, 0);

    $this->sDescricao        = $oDadosLinha->o69_labelrel == "" ? "": $oDadosLinha->o69_labelrel;
    $this->lTotalizar        = $oDadosLinha->o69_totalizador == "t" ? true : false;
    $this->iNivel            = $oDadosLinha->o69_nivellinha;
    $this->lpermiteDesdobrar = $oDadosLinha->o69_desdobrarlinha == "t" ? true : false;
    $this->iOrdem            = $oDadosLinha->o69_ordem;
    $this->iOrigemDados      = $oDadosLinha->o69_origem;
  }

  /**
   * retorna as colunas cadastradas para a linha no periodo indicado
   *
   * @param integer $iPeriodo codigo do periodo
   * @return array com as colunas cadastradas
   */
  public function getCols($iPeriodo = null) {

    $sSqlCols  = "select o115_sequencial,";
    $sSqlCols .= "       o115_anousu, ";
    $sSqlCols .= "       o115_descricao,";
    $sSqlCols .= "       o115_nomecoluna,";
    $sSqlCols .= "       o115_valoresdefault,";
    $sSqlCols .= "       o115_tipo,";
    $sSqlCols .= "       o116_ordem,";
    $sSqlCols .= "       o116_sequencial,";
    $sSqlCols .= "       o116_formula";
    $sSqlCols .= "  from orcparamseqorcparamseqcoluna   ";
    $sSqlCols .= "       inner join orcparamseqcoluna on o116_orcparamseqcoluna = o115_sequencial";
    $sSqlCols .= " where o116_codseq= {$this->codigo}";
    $sSqlCols .= "   and o116_codparamrel = {$this->relatorio}";
    if ($iPeriodo != null) {
      $sSqlCols .= "   and o116_periodo = {$iPeriodo}";
    }
    $sSqlCols .= " order by o116_ordem";
    $rsCols    = db_query($sSqlCols);
    $aCols     = db_utils::getCollectionByRecord($rsCols);
    return $aCols;

  }

  /**
   * retorna os valores cadastrados para cada coluna cada coluna
   *
   * @param integer $iLinha numero da linha cadastrada
   * @param integer $iCol código da coluna
   * @param string  $sListaInstit lista das instituicoes separadas por ","
   * @param integer $iAno ano dos valores
   * @return array com os valores das colunas
   */
  public function getValoresColunas($iLinha=null,$iCol=null, $sListaInstit=null, $iAno = null) {

    if (empty($sListaInstit)) {
      $sListaInstit = db_getsession("DB_instit");
    }
    $sWhere = "";
    if ($iLinha != null) {
      $sWhere .= " and o117_linha = {$iLinha}";
    }
    if ($iCol != null) {
      $sWhere .= " and o116_ordem = {$iCol}";
    }
    $sSqlLinhas  = "SELECT orcparamseqorcparamseqcolunavalor.*, ";
    $sSqlLinhas .= "       o116_orcparamseqcoluna,";
    $sSqlLinhas .= "       o115_tipo,";
    $sSqlLinhas .= "       o115_nomecoluna,";
    $sSqlLinhas .= "       o115_valoresdefault,";
    $sSqlLinhas .= "       o116_ordem";
    $sSqlLinhas .= "  from orcparamseqorcparamseqcolunavalor   ";
    $sSqlLinhas .= "       inner join orcparamseqorcparamseqcoluna  on o117_orcparamseqorcparamseqcoluna = o116_sequencial";
    $sSqlLinhas .= "       inner join orcparamseqcoluna             on o116_orcparamseqcoluna            = o115_sequencial";
    $sSqlLinhas .= " where o116_codparamrel = {$this->relatorio} ";
    $sSqlLinhas .= "    and o116_codseq     = {$this->codigo}    ";
    $sSqlLinhas .= "    and o117_instit     in({$sListaInstit})";
    if ($this->iPeriodo != null) {
       $sSqlLinhas .= "    and o116_periodo  = ".$this->iPeriodo;
    }
    if ($iAno != null) {
       $sSqlLinhas .= "    and o117_anousu  = {$iAno}";
    }
    $sSqlLinhas .= $sWhere;
    $sSqlLinhas .= "  order by o117_instit, o117_linha,o116_ordem ";
    $rsLinhas    = db_query($sSqlLinhas);
    $aLinhas     = array();
    $iLinha      = 0;
    $iNumRows    = pg_num_rows($rsLinhas);
    for ($i = 0; $i < $iNumRows; $i++) {

     // echo "iLinha: {$iLinha} - oLinha:{$oLinha->o117_linha}\n";

      $oLinha = db_utils::fieldsMemory($rsLinhas,$i,false,false, $this->lEncode);
      if  ($iLinha == $oLinha->o117_linha){

        $iCodigoLinha = count($aLinhas) -1;
        $aLinhas[$iCodigoLinha]->colunas[] = $oLinha;
        $aLinhas[$iCodigoLinha]->linha     = $oLinha->o117_linha;

      } else {

        $oLinhaRel = new stdClass();
        $oLinhaRel->colunas[]  = $oLinha;
        $oLinhaRel->linha      = $oLinha->o117_linha;
        $aLinhas[] = $oLinhaRel;

      }
      $iLinha = $oLinha->o117_linha;
    }
    return $aLinhas;
  }

  /**
   * Retorna os valores das colunas cadastrados por instituicao.
   *
   * @param integer $iLinha numero da linha
   * @param integer $iCol   numero da coluna
   * @param string  $sListaInstit lista de instituições
   * @param integer $iAno Ano
   * @return array
   */
  public function getValoresColunasInstituicoes($iLinha=null,$iCol=null, $sListaInstit=null, $iAno = null) {

    if (empty($sListaInstit)) {
      $sListaInstit = db_getsession("DB_instit");
    }
    $sWhere = "";
    if ($iLinha != null) {
      $sWhere .= " and o117_linha = {$iLinha}";
    }
    if ($iCol != null) {
      $sWhere .= " and o116_ordem = {$iCol}";
    }
    $sSqlLinhas  = "SELECT orcparamseqorcparamseqcolunavalor.*, ";
    $sSqlLinhas .= "       o116_orcparamseqcoluna,";
    $sSqlLinhas .= "       o115_tipo,";
    $sSqlLinhas .= "       o115_nomecoluna,";
    $sSqlLinhas .= "       o115_valoresdefault,";
    $sSqlLinhas .= "       o117_linha||'_'||o117_instit as codigolinha,";
    $sSqlLinhas .= "       o116_ordem";
    $sSqlLinhas .= "  from orcparamseqorcparamseqcolunavalor   ";
    $sSqlLinhas .= "       inner join orcparamseqorcparamseqcoluna  on o117_orcparamseqorcparamseqcoluna = o116_sequencial";
    $sSqlLinhas .= "       inner join orcparamseqcoluna             on o116_orcparamseqcoluna            = o115_sequencial";
    $sSqlLinhas .= " where o116_codparamrel = {$this->relatorio} ";
    $sSqlLinhas .= "    and o116_codseq     = {$this->codigo}    ";
    $sSqlLinhas .= "    and o117_instit     in({$sListaInstit})";
    if ($this->iPeriodo != null) {
       $sSqlLinhas .= "    and o116_periodo  = ".$this->iPeriodo;
    }
    if ($iAno != null) {
       $sSqlLinhas .= "    and o117_anousu  = {$iAno}";
    }
    $sSqlLinhas .= $sWhere;
    $sSqlLinhas .= "  order by o117_instit, o117_linha,o116_ordem ";
    $rsLinhas    = db_query($sSqlLinhas);
    $aLinhas     = array();
    $iLinha      = 0;
    $iNumRows    = pg_num_rows($rsLinhas);
    for ($i = 0; $i < $iNumRows; $i++) {

     // echo "iLinha: {$iLinha} - oLinha:{$oLinha->o117_linha}\n";

      $oLinha = db_utils::fieldsMemory($rsLinhas,$i,false,false, $this->lEncode);
      if  ($iLinha == $oLinha->codigolinha){

        $aLinhas[$oLinha->codigolinha]->colunas[] = $oLinha;
        $aLinhas[$oLinha->codigolinha]->linha     = $oLinha->o117_linha;

      } else {

        $oLinhaRel = new stdClass();
        $oLinhaRel->colunas[]  = $oLinha;
        $oLinhaRel->linha      = $oLinha->o117_linha;
        $aLinhas[$oLinha->codigolinha] = $oLinhaRel;

      }
      $iLinha = $oLinha->codigolinha;
    }
    return $aLinhas;
  }

  /**
   * soma os valoes acumulado das colunas do relatorio
   * @param null $sListaInstit
   * @param string $iAno
   * @return array
   */
  public function getValoresSomadosColunas($sListaInstit=null, $iAno = '') {

    if (empty($sListaInstit)) {
      $sListaInstit = db_getsession("DB_instit");
    }
    $sWhere = "";
    $sSqlLinhas  = "SELECT sum(o117_valor::numeric) as o117_valor, ";
    $sSqlLinhas .= "       o116_orcparamseqcoluna,";
    $sSqlLinhas .= "       o115_tipo,";
    $sSqlLinhas .= "       o115_nomecoluna,";
    $sSqlLinhas .= "       o116_ordem,";
    $sSqlLinhas .= "       o117_linha";
    $sSqlLinhas .= "  from orcparamseqorcparamseqcoluna   ";
    $sSqlLinhas .= "       left join orcparamseqorcparamseqcolunavalor  on o117_orcparamseqorcparamseqcoluna = o116_sequencial";
    $sSqlLinhas .= "                                                   and o117_instit     in({$sListaInstit})";
    if ($iAno != null) {
       $sSqlLinhas .= "    and o117_anousu  = {$iAno}";
    }
    $sSqlLinhas .= "       inner join orcparamseqcoluna             on o116_orcparamseqcoluna            = o115_sequencial";
    $sSqlLinhas .= " where o116_codparamrel = {$this->relatorio} ";
    $sSqlLinhas .= "    and o116_codseq     = {$this->codigo}    ";
    if ($this->iPeriodo != null) {
       $sSqlLinhas .= "    and o116_periodo  = ".$this->iPeriodo;
    }
    $sSqlLinhas .= $sWhere;
    $sSqlLinhas .= " group by o117_linha,o116_orcparamseqcoluna,o115_tipo,o116_ordem,o115_nomecoluna";
    $sSqlLinhas .= " order by o117_linha,o116_ordem";
    $rsLinhas    = db_query($sSqlLinhas);
    $aLinhas     = array();
    $iLinha      = 0;
    $iNumRows    = pg_num_rows($rsLinhas);
    for ($i = 0; $i < $iNumRows; $i++) {

      $oLinha = db_utils::fieldsMemory($rsLinhas,$i,false,false, $this->lEncode);
      if  ($iLinha == $oLinha->o117_linha){

        $aLinhas[$oLinha->o117_linha]->colunas[$oLinha->o116_ordem] = $oLinha;
        $aLinhas[$oLinha->o117_linha]->linha     = $oLinha->o117_linha;

      } else {

        $oLinhaRel = new stdClass();
        $oLinhaRel->colunas[$oLinha->o116_ordem]  = $oLinha;
        $oLinhaRel->linha      = $oLinha->o117_linha;
        $aLinhas[$oLinha->o117_linha] = $oLinhaRel;

      }
      $iLinha = $oLinha->o117_linha;
    }
    return $aLinhas;
  }

  /**
   * @param $iLinha
   * @param null $iInstituicao
   * @param null $iAno
   * @throws Exception
   */
  public function excluirLinha($iLinha, $iInstituicao = null, $iAno = null) {

    $aLinhas = $this->getValoresColunas($iLinha, null, $iInstituicao);
    $oDaoColunaValor  = db_utils::getDao("orcparamseqorcparamseqcolunavalor");
    foreach ($aLinhas as $oLinha) {

      foreach ($oLinha->colunas as $oColuna) {

        $oDaoColunaValor->excluir($oColuna->o117_sequencial);
        if ($oDaoColunaValor->erro_status == 0) {
          throw new Exception($oDaoColunaValor->erro_msg);
        }

      }

      $sUpdate  = "update orcparamseqorcparamseqcolunavalor ";
      $sUpdate .= "   set o117_linha = o117_linha -1";
      $sUpdate .= "  from orcparamseqorcparamseqcoluna ";
      $sUpdate .= " where o117_orcparamseqorcparamseqcoluna = o116_sequencial";
      $sUpdate .= "   and o116_codparamrel = {$this->relatorio} ";
      $sUpdate .= "   and o116_codseq     = {$this->codigo}    ";
      $sUpdate .= "   and o117_instit     = {$iInstituicao}";
      $sUpdate .= "   and o117_linha      > {$iLinha}";
      if ($iAno != null) {
        $sUpdate .= "   and o117_anousu  = {$iAno}";
      }
      if ($this->iPeriodo != null) {
        $sUpdate .= "  and o117_periodo  = ".$this->iPeriodo;
      }
      $rsUpdate = db_query($sUpdate);
      if (!$rsUpdate) {
        throw new Exception("Não foi possível atualizar os dados da tabela orcparamseqorcparamseqcoluna.");
      }
    }
  }
  /**
   * Define o periodo para retorno das informacoes das colunas
   * @param integer $iPeriodo codigo do periodo
   */
  public function setPeriodo($iPeriodo) {
    $this->iPeriodo = $iPeriodo;
  }

  /**
   * @param $lEncode
   */
  public function setEncode($lEncode) {
    $this->lEncode = $lEncode;
  }

  /**
   * Salva os parametros default da linha
   * @param $oFilter
   * @return $this
   * @throws Exception
   */
  public function salvarParametrosDefault($oFilter) {

    /**
     * Verificamos se existe a propriedade conta no objeto Filtro
     */
    /*
     * iniciamos a escrita o XML para a montagem do filtro
     */
    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0','ISO-8859-1');
    $oXmlWriter->endDtd();
    $oXmlWriter->startElement("filter");
    $oXmlWriter->startElement("contas");
    foreach ($oFilter->contas as $oConta) {

      $oXmlWriter->startElement("conta");
      $oXmlWriter->writeAttribute("estrutural", $oConta->estrutural);
      $oXmlWriter->writeAttribute("nivel", $oConta->nivel);
      $oXmlWriter->writeAttribute("exclusao", $oConta->exclusao==true?"true":"false");
      $oXmlWriter->writeAttribute("indicador", $oConta->indicador);
      $oXmlWriter->endElement();
    }
    $oXmlWriter->endElement();
    /**
     * gravamos os vinculo com o orcamento
     */
    $oXmlWriter->startElement("orgao");
    $oXmlWriter->writeAttribute("operador", $oFilter->orgao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->orgao->valor);
    $oXmlWriter->writeAttribute("id", "orgao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("unidade");
    $oXmlWriter->writeAttribute("operador", $oFilter->unidade->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->unidade->valor);
    $oXmlWriter->writeAttribute("id", "unidade");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("funcao");
    $oXmlWriter->writeAttribute("operador", $oFilter->funcao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->funcao->valor);
    $oXmlWriter->writeAttribute("id", "funcao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("subfuncao");
    $oXmlWriter->writeAttribute("operador", $oFilter->subfuncao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->subfuncao->valor);
    $oXmlWriter->writeAttribute("id", "subfuncao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("programa");
    $oXmlWriter->writeAttribute("operador", $oFilter->programa->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->programa->valor);
    $oXmlWriter->writeAttribute("id", "programa");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("projativ");
    $oXmlWriter->writeAttribute("operador", $oFilter->projativ->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->projativ->valor);
    $oXmlWriter->writeAttribute("id", "projativ");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("recurso");
    $oXmlWriter->writeAttribute("operador", $oFilter->recurso->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->recurso->valor);
    $oXmlWriter->writeAttribute("id", "recurso");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("recursocontalinha");
    $oXmlWriter->writeAttribute("numerolinha", $oFilter->numerolinharecurso->valor);
    $oXmlWriter->writeAttribute("id", "recursocontalinha");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("observacao");
    $oXmlWriter->writeAttribute("valor", $oFilter->observacao);
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("desdobrarlinha");
    $oXmlWriter->writeAttribute("valor", $oFilter->desdobrarlinha==true?"true":"false");
    $oXmlWriter->endElement();

    $oXmlWriter->endElement();
    $strBuffer  = $oXmlWriter->outputMemory();
    $oDaoLinhas = db_utils::getDao("orcparamseqfiltropadrao");
    $oDaoLinhas->o132_orcparamrel = $this->relatorio;
    $oDaoLinhas->o132_orcparamseq = $this->codigo;
    $oDaoLinhas->o132_anousu      = db_getsession("DB_anousu");
    $oDaoLinhas->o132_filtro      = $strBuffer;
    $sWhere  = "o132_orcparamseq = {$this->codigo} and o132_orcparamrel = {$this->relatorio} ";
    $sWhere .= " and o132_anousu = ".db_getsession("DB_anousu");
    $sSqlFiltroPadrao  = $oDaoLinhas->sql_query_file(null,"*", null, $sWhere);
    $rsFiltroPadrao    = $oDaoLinhas->sql_record($sSqlFiltroPadrao);
    if ($oDaoLinhas->numrows == 0) {
      $oDaoLinhas->incluir(null);
    } else {

      $iCodigoLinha = db_utils::fieldsMemory($rsFiltroPadrao,0)->o132_sequencial;
      $oDaoLinhas->o132_sequencial = $iCodigoLinha;
      $oDaoLinhas->alterar($iCodigoLinha);

    }
    if ($oDaoLinhas->erro_status == 0) {
      throw new Exception("Erro ao salvar parametros!\nErro retornado:{$oDaoLinhas->erro_msg}");
    }
    return $this;
  }

  /**
   * retorna os parametros cadastrados como padrao para cada linha
   * @param string $sInstit
   * @return stdClass
   */
  public function getParametrosPadrao($sInstit = '') {

    $oDaoLinhas = db_utils::getDao("orcparamseqfiltropadrao");
    $iAnoUsu    = db_getsession("DB_anousu");
    $sWhere = "o132_orcparamseq = {$this->codigo} and o132_orcparamrel = {$this->relatorio} and o132_anousu={$iAnoUsu}";
    $sSqlFiltroPadrao  = $oDaoLinhas->sql_query_file(null,"*", null, $sWhere);
    $rsFiltroPadrao    = $oDaoLinhas->sql_record($sSqlFiltroPadrao);
    $oFiltro = new stdClass();
    $oFiltro->contas = array();
    $oFiltro->orgao = new \stdClass();
    $oFiltro->orgao->operador = 'in';
    $oFiltro->orgao->valor    = '';

    $oFiltro->unidade = new \stdClass();
    $oFiltro->unidade->operador = 'in';
    $oFiltro->unidade->valor    = '';

    $oFiltro->funcao = new \stdClass();
    $oFiltro->funcao->operador = 'in';
    $oFiltro->funcao->valor    = '';

    $oFiltro->subfuncao = new \stdClass();
    $oFiltro->subfuncao->operador = 'in';
    $oFiltro->subfuncao->valor    = '';

    $oFiltro->programa = new \stdClass();
    $oFiltro->programa->operador = 'in';
    $oFiltro->programa->valor    = '';

    $oFiltro->projativ = new \stdClass();
    $oFiltro->projativ->operador = 'in';
    $oFiltro->projativ->valor    = '';

    $oFiltro->recurso = new \stdClass();
    $oFiltro->recurso->operador = 'in';
    $oFiltro->recurso->valor    = '';

    $oFiltro->caracteristica           = new \stdClass();
    $oFiltro->caracteristica->operador = 'in';
    $oFiltro->caracteristica->valor    = '';

    $oFiltro->observacao        = '';
    if ($oDaoLinhas->numrows > 0) {

      $oDadosFiltro = db_utils::fieldsMemory($rsFiltroPadrao, 0);
      $oDomXml  = new DOMDocument();
      $oDomXml->loadXML($oDadosFiltro->o132_filtro);
      $aContas = $oDomXml->getElementsByTagName("conta");
      foreach($aContas as $oConta) {

        $oContaAdicionar = new stdClass();
        $oContaAdicionar->estrutural = $oConta->getAttribute("estrutural");
        $oContaAdicionar->nivel      = $oConta->getAttribute("nivel");
        $oContaAdicionar->exclusao   = $oConta->getAttribute("exclusao")=="true"?true:false;
        $oContaAdicionar->indicador  = $oConta->getAttribute("indicador");
        $oFiltro->contas[] = $oContaAdicionar;

      }
      $oOrgao = $oDomXml->getElementsByTagName("orgao");
      $oFiltro->orgao->operador = $oOrgao->item(0)->getAttribute("operador");
      $oFiltro->orgao->valor    = $oOrgao->item(0)->getAttribute("valor");

      $oUnidade = $oDomXml->getElementsByTagName("unidade");
      $oFiltro->unidade->operador = $oUnidade->item(0)->getAttribute("operador");
      $oFiltro->unidade->valor    = $oUnidade->item(0)->getAttribute("valor");

      $oFuncao = $oDomXml->getElementsByTagName("funcao");
      $oFiltro->funcao->operador = $oFuncao->item(0)->getAttribute("operador");
      $oFiltro->funcao->valor    = $oFuncao->item(0)->getAttribute("valor");

      $oSubFuncao = $oDomXml->getElementsByTagName("subfuncao");
      $oFiltro->subfuncao->operador = $oSubFuncao->item(0)->getAttribute("operador");
      $oFiltro->subfuncao->valor    = $oSubFuncao->item(0)->getAttribute("valor");

      $oPrograma = $oDomXml->getElementsByTagName("programa");
      $oFiltro->programa->operador = $oPrograma->item(0)->getAttribute("operador");
      $oFiltro->programa->valor    = $oPrograma->item(0)->getAttribute("valor");

      $oProjAtiv = $oDomXml->getElementsByTagName("projativ");
      $oFiltro->projativ->operador = $oProjAtiv->item(0)->getAttribute("operador");
      $oFiltro->projativ->valor    = $oProjAtiv->item(0)->getAttribute("valor");

      $oRecurso = $oDomXml->getElementsByTagName("recurso");
      $oFiltro->recurso->operador = $oRecurso->item(0)->getAttribute("operador");
      $oFiltro->recurso->valor    = $oRecurso->item(0)->getAttribute("valor");

      $oCaracteristica = $oDomXml->getElementsByTagName("caracteristica");
      if ($oCaracteristica->length > 0) {

        $oFiltro->caracteristica->operador = $oCaracteristica->item(0)->getAttribute("operador");
        $oFiltro->caracteristica->valor    = $oCaracteristica->item(0)->getAttribute("valor");
      }

      $oFiltro->recursocontalinha          = '';
      $oFiltro->recursocontadescricaolinha = '';

      $observacao           = $oDomXml->getElementsByTagName("observacao");
      $oFiltro->observacao  = '';
      if ($observacao->item(0)) {
       $oFiltro->observacao  = $observacao->item(0)->getAttribute("valor");
      }
      $oRecursoLinha = $oDomXml->getElementsByTagName("recursocontalinha");
      if ($oRecursoLinha && $oRecursoLinha->item(0)) {

        if (trim($oRecursoLinha->item(0)->getAttribute("numerolinha")) != "") {

          $oFiltro->recursocontalinha   = $oRecursoLinha->item(0)->getAttribute("numerolinha");
          $aLinhasCadastradas = explode(",", trim($oRecursoLinha->item(0)->getAttribute("numerolinha")));
          $aRecursosLinha     = array();

          foreach ($aLinhasCadastradas as $iLinha) {

            $oLinhaBase = new linhaRelatorioContabil($this->relatorio, $iLinha);

            /**
             * consultamos as contas que a linha possui,
             * e verificamos no plano de contas, quais os recursos que a conta configurada nas contas possui
             */
            $aParametrosContaRecurso = $oLinhaBase->getParametrosPadrao($sInstit);
            $aContas                 = $aParametrosContaRecurso->contas;

            foreach ($aContas as $oConta) {

              switch ($oLinhaBase->getOrigemDados()) {

                case linhaRelatorioContabil::ORIGEM_RECEITA:

                  $aRecursos = $this->getRecursosOrigemReceita(
                    db_getsession("DB_anousu"),
                    $sInstit,
                    $oConta
                  );

                  break;

                case linhaRelatorioContabil::ORIGEM_DESPESA:

                  $aRecursos = $this->getRecursosOrigemDespesa(
                    db_getsession("DB_anousu"),
                    $sInstit,
                    $oConta
                  );

                  break;

                default:

                  $aRecursos = $this->getRecursosPlanoDeContas(
                    db_getsession("DB_anousu"),
                    $sInstit,
                    $oConta
                  );

              } // switch

              $aRecursosLinha = array_unique(array_merge($aRecursosLinha, $aRecursos));

            } // foreach

            $oFiltro->recurso->operador = "in";
            $oFiltro->recurso->valor    = implode(",", $aRecursosLinha);
          }
        }
      }
      /**
       * Verificamos se o usuário quer desdobrar a linha
       */
      $oDesdobrarLinha         = $oDomXml->getElementsByTagName("desdobrarlinha");
      $oFiltro->desdobrarlinha = false;
      if ($oDesdobrarLinha->item(0)) {
        $oFiltro->desdobrarlinha = $oDesdobrarLinha->item(0)->getAttribute("valor")=="true"?true:false;
      }
    }
    return $oFiltro;
  }

  /**
   * retorna os parametros cadastrados para a linha. Verifica os parametros cadastrados para o usuario e padrao
   *
   * @param integer $iAno ano dos parametros
   * @param string $sInstituicoes lista das instituiçoes, separadas por ","
   * @return stdClass com os dadso dos parametros configurados
   */
  public function getParametros($iAno, $sInstituicoes=null) {

    /**
     * Verificamos se o usuário registrou algum parametro. caso tenha parametro escolhido,
     * usamos a configuração do usuário;
     */
    $sSqlCodigoInstit  = "select codigo from db_config where prefeitura is true";
    $rsCodigoInstit    = db_query($sSqlCodigoInstit);
    if (pg_num_rows($rsCodigoInstit) == 0) {
      die("Não Existe instituição marcada como prefeitura");
    }

    $sSql  = "select * from orcparamseqfiltroorcamento";
    $sSql .= " where o133_orcparamseq = {$this->codigo}";
    $sSql .= "   and o133_orcparamrel = {$this->relatorio}";
    $sSql .= "   and o133_anousu      = {$iAno}";
    $rsParametros    = db_query($sSql);
    $oParametroLinha = new stdClass();
    if (pg_num_rows($rsParametros) == 0) {

      $oFiltro                 = $this->getParametrosPadrao($sInstituicoes);
    } else {
      $oFiltro                 = $this->getParametrosOrcamentoUsuario();
    }

    $oParametroLinha->contas = $oFiltro->contas;
    $oFiltro->orgao->valor   = explode(",", $oFiltro->orgao->valor);
    if (count($oFiltro->orgao->valor) == 1 && $oFiltro->orgao->valor[0] == "" ) {
       $oFiltro->orgao->valor = array();
    }
    $oParametroLinha->orcamento = new \stdClass();
    $oParametroLinha->orcamento->orgao  = $oFiltro->orgao;

    $oFiltro->unidade->valor   = explode(",", $oFiltro->unidade->valor);
    if (count($oFiltro->unidade->valor) == 1 && $oFiltro->unidade->valor[0] == "" ) {
       $oFiltro->unidade->valor = array();
    }
    $oParametroLinha->orcamento->unidade  = $oFiltro->unidade;

    $oFiltro->funcao->valor   = explode(",", $oFiltro->funcao->valor);
    if (count($oFiltro->funcao->valor) == 1 && $oFiltro->funcao->valor[0] == "" ) {
       $oFiltro->funcao->valor = array();
    }
    $oParametroLinha->orcamento->funcao  = $oFiltro->funcao;

    $oFiltro->subfuncao->valor  = explode(",", $oFiltro->subfuncao->valor);
    if (count($oFiltro->subfuncao->valor) == 1 && $oFiltro->subfuncao->valor[0] == "" ) {
       $oFiltro->subfuncao->valor = array();
    }
    $oParametroLinha->orcamento->subfuncao = $oFiltro->subfuncao;

    $oFiltro->programa->valor   = explode(",", $oFiltro->programa->valor);
    if (count($oFiltro->programa->valor) == 1 && $oFiltro->programa->valor[0] == "" ) {
       $oFiltro->programa->valor = array();
    }
    $oParametroLinha->orcamento->programa  = $oFiltro->programa;

    $oFiltro->projativ->valor   = explode(",", $oFiltro->projativ->valor);
   if (count($oFiltro->projativ->valor) == 1 && $oFiltro->projativ->valor[0] == "" ) {
       $oFiltro->projativ->valor = array();
    }
    $oParametroLinha->orcamento->projativ  = $oFiltro->projativ;

    $oFiltro->recurso->valor   = explode(",", trim($oFiltro->recurso->valor));
    if (count($oFiltro->recurso->valor) == 1 && $oFiltro->recurso->valor[0] == "" ) {
       $oFiltro->recurso->valor = array();
    }
    $oParametroLinha->orcamento->recurso  = $oFiltro->recurso;
    if (!isset($oFiltro->desdobrarlinha)) {
      $oFiltro->desdobrarlinha = false;
    }
    $oParametroLinha->orcamento->caracteristica  = $oFiltro->caracteristica;
    if (!isset($oFiltro->desdobrarlinha)) {
      $oFiltro->desdobrarlinha = false;
    }

    $oParametroLinha->desdobrarlinha      = $oFiltro->desdobrarlinha;
    return $oParametroLinha;
  }

  /**
   * verifica se uma determinada conta está configurada para linha, e se a conta confere com o parametro configurado
   *
   * @param stdClass $oConta Objeto com informacao das contas
   * @param stdClass $oOrcamento Objeto com o vinculo do orçamento. a Origem do parametro é da configuração da linha
   * @param stdClass $oDadosOrigem dados com a origem dos valores que seram verificados
   * @param integer $iOrigem codigo da origem dos dados 1 - receitasaldo 2 dotacaosaldo 3 - planosaldomatriz
   * @return objeto com informacoes da verificadao
   * <code>
   *   $oRetorno = $oTeste->match($oConta, $oOrcamento, $oReceitaSaldo, 1)
   * </code>
   */
  public function match($oConta, $oOrcamento, $oDadosOrigem, $iOrigem) {

    $oRetorno           = new stdClass();
    $oRetorno->match    = false;
    $oRetorno->exclusao = false;
    switch ($iOrigem) {

      case 1: //ReceitaSaldo


        $iContaParametro = $oDadosOrigem->o57_fonte;
        if (trim($oConta->nivel) != '' || $oConta->nivel != 0) {

          if ($oConta->estrutural == $iContaParametro) {
            return $oRetorno;
          } else {

            $oConta->estrutural      = substr($oConta->estrutural, 0, trim($oConta->nivel));
            $iContaParametro         = substr($iContaParametro   , 0, trim($oConta->nivel));
          }
        }
        if ($oConta->estrutural == $iContaParametro) {

          if ($oConta->exclusao) {
            $oRetorno->exclusao = true;
          }
          /**
           * verificamos o vinculo com o orcamento
           */
          if (count($oOrcamento->recurso->valor) == 0) {
            $oRetorno->match = true;
          } else {

            if ($oOrcamento->recurso->operador == "in") {
              $oRetorno->match = in_array($oDadosOrigem->o70_codigo, $oOrcamento->recurso->valor);
            } else {

              $oRetorno->match = false;
              if ($oDadosOrigem->o70_codigo > 0) {
                $oRetorno->match = !in_array($oDadosOrigem->o70_codigo, $oOrcamento->recurso->valor);
              }
            }
          }

        }
        break;

      case 2: //dotacaosaldo

        $iContaParametro = $oDadosOrigem->o58_elemento."00";
        $iContaVerificar = $oConta->estrutural;
        if (trim($oConta->nivel) != '' || $oConta->nivel != 0) {

          $iContaVerificar = substr($iContaVerificar, 0, trim($oConta->nivel));
          $iContaParametro = substr($iContaParametro , 0, trim($oConta->nivel));
        }
        if ($oConta->exclusao) {
          $oRetorno->exclusao = true;
        }
        if ($iContaVerificar == $iContaParametro) {
           $oRetorno->match = true;
        }
        /**
        * verificamos o vinculo com o orcamento
        */
        if ($oRetorno->match) {

          if (count($oOrcamento->orgao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->recurso->operador == "in") {
              $oRetorno->match = in_array($oDadosOrigem->o58_orgao, $oOrcamento->orgao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_orgao, $oOrcamento->orgao->valor)?true:false;
            }
          }
        }

        /**
         * Verificamos a unidade
         */
        if($oRetorno->match) {

          if (count($oOrcamento->unidade->valor) == 0) {
            $oRetorno->match = true;
          } else {

            if ($oOrcamento->unidade->operador == "in") {
              $oRetorno->match = in_array($oDadosOrigem->o58_unidade, $oOrcamento->unidade->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_unidade, $oOrcamento->unidade->valor)?true:false;
            }
          }
        }
        /**
         * Validamos a funcao
         */
        if($oRetorno->match) {

          if (count($oOrcamento->funcao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->funcao->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_funcao, $oOrcamento->funcao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_funcao, $oOrcamento->funcao->valor)?true:false;
            }
          }
        }
        /**
         * Validamos a subfuncao
         */
        if($oRetorno->match) {

          if (count($oOrcamento->subfuncao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->subfuncao->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_subfuncao, $oOrcamento->subfuncao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_subfuncao, $oOrcamento->subfuncao->valor)?true:false;
            }
          }
        }
        /**
         * Validamos o programa
         */
        if($oRetorno->match) {

          if (count($oOrcamento->programa->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->programa->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_programa, $oOrcamento->programa->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_programa, $oOrcamento->programa->valor)?true:false;
            }
          }
        }
        if($oRetorno->match) {

          if (count($oOrcamento->projativ->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->projativ->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_projativ, $oOrcamento->projativ->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_projativ, $oOrcamento->projativ->valor)?true:false;
            }
          }
        }
        /**
         * Validamos o recurso
         */
        if($oRetorno->match) {

          if (count($oOrcamento->recurso->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->recurso->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_codigo, $oOrcamento->recurso->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_codigo, $oOrcamento->recurso->valor)?true:false;
            }
          }
        }


        break;

      case 3: //ReceitaSaldo

        $iContaParametro = $oDadosOrigem->estrutural;
        $iEstrutural     = $oConta->estrutural;
        if (trim($oConta->nivel) != '' || $oConta->nivel != 0) {

          if ($iEstrutural == $iContaParametro) {
            return $oRetorno;
          } else {

            $iEstrutural     = substr($iEstrutural, 0, trim($oConta->nivel));
            $iContaParametro = substr($iContaParametro   , 0, trim($oConta->nivel));
          }
        }

        if ((float)$iEstrutural == (float)$iContaParametro) {
          if ($oConta->exclusao) {
            $oRetorno->exclusao = true;
          }

          $oRetorno->match = true;

          if (trim($oConta->indicador) != '') {
            $oRetorno->match = (trim($oConta->indicador) == $oDadosOrigem->isf);
          }

          /**
           * verificamos o vinculo com o orcamento
           */

          if ($oRetorno->match) {

            if (count($oOrcamento->recurso->valor) == 0) {
              $oRetorno->match = true;
            } else {

              if ($oOrcamento->recurso->operador == "in") {
                $oRetorno->match = in_array($oDadosOrigem->c61_codigo, $oOrcamento->recurso->valor);
              } else {

                $oRetorno->match = false;
                if ($oDadosOrigem->c61_codigo > 0) {
                  $oRetorno->match = !in_array($oDadosOrigem->c61_codigo, $oOrcamento->recurso->valor);
                }

                /*
                if ($oDadosOrigem->c61_codigo > 0) {
                  $oRetorno->match = !in_array($oDadosOrigem->c61_codigo, $oOrcamento->recurso->valor);
                } else {
                  $oRetorno->match = false;
                }
                */
              }
            }
          }

        }
        break;

      case 4: //Restos a pagar

        $iContaParametro = $oDadosOrigem->o56_elemento."00";
        $iContaVerificar = $oConta->estrutural;
        if (trim($oConta->nivel) != '' || $oConta->nivel != 0) {

          $iContaVerificar = substr($iContaVerificar, 0, trim($oConta->nivel));
          $iContaParametro = substr($iContaParametro , 0, trim($oConta->nivel));
        }
        if ($oConta->exclusao) {
          $oRetorno->exclusao = true;
        }
        if ($iContaVerificar == $iContaParametro) {
           $oRetorno->match = true;
        }
       /**
        * verificamos o vinculo com o orcamento
        */
       if ($oRetorno->match) {

          if (count($oOrcamento->orgao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->recurso->operador == "in") {
              $oRetorno->match = in_array($oDadosOrigem->o58_orgao, $oOrcamento->orgao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_orgao, $oOrcamento->orgao->valor)?true:false;
            }
          }
        }
        /**
         * Verificamos a unidade
         */
        if($oRetorno->match) {

          if (count($oOrcamento->unidade->valor) == 0) {
            $oRetorno->match = true;
          } else {

            if ($oOrcamento->unidade->operador == "in") {
              $oRetorno->match = in_array($oDadosOrigem->o58_orgao."-".$oDadosOrigem->unidade,
                                          $oOrcamento->unidade->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_orgao."-".$oDadosOrigem->unidade,
                                           $oOrcamento->unidade->valor)?true:false;
            }
          }
        }
        /**
         * Validamos a funcao
         */
        if($oRetorno->match) {

          if (count($oOrcamento->funcao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->funcao->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_funcao, $oOrcamento->funcao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_funcao, $oOrcamento->funcao->valor)?true:false;
            }
          }
        }
        /**
         * Validamos a subfuncao
         */
        if($oRetorno->match) {

          if (count($oOrcamento->subfuncao->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->subfuncao->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_subfuncao, $oOrcamento->subfuncao->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_subfuncao, $oOrcamento->subfuncao->valor)?true:false;
            }
          }
        }
        /**
         * Validamos o programa
         */
        if($oRetorno->match) {

          if (count($oOrcamento->programa->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->programa->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_programa, $oOrcamento->programa->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_programa, $oOrcamento->programa->valor)?true:false;
            }
          }
        }
        if($oRetorno->match) {

          if (count($oOrcamento->projativ->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->projativ->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_projativ, $oOrcamento->projativ->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_projativ, $oOrcamento->projativ->valor)?true:false;
            }
          }
        }
        /**
         * Validamos o recurso
         */
        if($oRetorno->match) {

          if (count($oOrcamento->recurso->valor) == 0) {

            $oRetorno->match = true;
          } else {

            if ($oOrcamento->recurso->operador == "in") {

              $oRetorno->match = in_array($oDadosOrigem->o58_codigo, $oOrcamento->recurso->valor)?true:false;
            } else {
              $oRetorno->match = !in_array($oDadosOrigem->o58_codigo, $oOrcamento->recurso->valor)?true:false;
            }
          }
        }

        break;
    }
    unset($oDadosOrigem);
    return $oRetorno;
  }

  /**
   * Salva os parametros da linha
   * @param $oFilter
   * @return $this
   * @throws Exception
   */
  public function salvarParametros($oFilter) {

    /*
     * iniciamos a escrita o XML para a montagem do filtro
     */
    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0','ISO-8859-1');
    $oXmlWriter->endDtd();
    $oXmlWriter->startElement("filter");

    $oXmlWriter->startElement("contas");
    /**
     * Verificamos se existe a propriedade conta no objeto Filtro
     */
    if (isset($oFilter->contas)) {

      foreach ($oFilter->contas as $oConta) {

        $oXmlWriter->startElement("conta");
        $oXmlWriter->writeAttribute("estrutural",$oConta->estrutural);
        $oXmlWriter->writeAttribute("nivel",$oConta->nivel);
        $oXmlWriter->writeAttribute("exclusao", $oConta->exclusao==true?"true":"false");
        $oXmlWriter->writeAttribute("indicador", $oConta->indicador);
        $oXmlWriter->endElement();
      }
    }
    $oXmlWriter->endElement();

    /**
     * gravamos os vinculo com o orcamento
     */
     /**
     * gravamos os vinculo com o orcamento
     */
    $oXmlWriter->startElement("orgao");
    $oXmlWriter->writeAttribute("operador",$oFilter->orgao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->orgao->valor);
    $oXmlWriter->writeAttribute("id", "orgao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("unidade");
    $oXmlWriter->writeAttribute("operador",$oFilter->unidade->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->unidade->valor);
    $oXmlWriter->writeAttribute("id", "unidade");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("funcao");
    $oXmlWriter->writeAttribute("operador",$oFilter->funcao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->funcao->valor);
    $oXmlWriter->writeAttribute("id", "funcao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("subfuncao");
    $oXmlWriter->writeAttribute("operador",$oFilter->subfuncao->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->subfuncao->valor);
    $oXmlWriter->writeAttribute("id", "subfuncao");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("programa");
    $oXmlWriter->writeAttribute("operador",$oFilter->programa->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->programa->valor);
    $oXmlWriter->writeAttribute("id", "programa");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("projativ");
    $oXmlWriter->writeAttribute("operador",$oFilter->projativ->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->projativ->valor);
    $oXmlWriter->writeAttribute("id", "projativ");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("recurso");
    $oXmlWriter->writeAttribute("operador",$oFilter->recurso->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->recurso->valor);
    $oXmlWriter->writeAttribute("id", "recurso");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("caracteristica");
    $oXmlWriter->writeAttribute("operador",$oFilter->caracteristica->operator);
    $oXmlWriter->writeAttribute("valor", $oFilter->caracteristica->valor);
    $oXmlWriter->writeAttribute("id", "caracteristica");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("recursocontalinha");
    $oXmlWriter->writeAttribute("numerolinha", $oFilter->numerolinharecurso->valor);
    $oXmlWriter->writeAttribute("id", "recursocontalinha");
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("observacao");
    $oXmlWriter->writeAttribute("valor",   $oFilter->observacao);
    $oXmlWriter->endElement();

    $oXmlWriter->startElement("desdobrarlinha");
    $oXmlWriter->writeAttribute("valor",$oFilter->desdobrarlinha==true?"true":"false");
    $oXmlWriter->endElement();

    $oXmlWriter->endElement();
    $strBuffer  = $oXmlWriter->outputMemory();
    $oDaoLinhas = db_utils::getDao("orcparamseqfiltroorcamento");
    $iAnoUsu    = db_getsession("DB_anousu");
    $oDaoLinhas->o133_orcparamrel = $this->relatorio;
    $oDaoLinhas->o133_orcparamseq = $this->codigo;
    $oDaoLinhas->o133_anousu      = db_getsession("DB_anousu");
    $oDaoLinhas->o133_filtro      = $strBuffer;

    $sWhere = "o133_orcparamseq = {$this->codigo} and o133_orcparamrel = {$this->relatorio} and o133_anousu = {$iAnoUsu}";
    $sSqlFiltro  = $oDaoLinhas->sql_query_file(null,"*", null, $sWhere);
    $rsFiltro    = $oDaoLinhas->sql_record($sSqlFiltro);
    if ($oDaoLinhas->numrows == 0) {
      $oDaoLinhas->incluir(null);
    } else {

      $iCodigoLinha = db_utils::fieldsMemory($rsFiltro, 0)->o133_sequencial;
      $oDaoLinhas->o133_sequencial = $iCodigoLinha;
      $oDaoLinhas->alterar($iCodigoLinha);

    }
    if ($oDaoLinhas->erro_status == 0) {
      throw new Exception("Erro ao salvar parametros!\nErro retornado:{$oDaoLinhas->erro_msg}");
    }
    return $this;
  }

  /**
   * Retorn os parametros do orcamento cadastrados pelo usuário
   *
   * @return unknown
   */
  function getParametrosOrcamentoUsuario() {

    $oDaoLinhas = db_utils::getDao("orcparamseqfiltroorcamento");
    $iAnoUsu    = db_getsession("DB_anousu");
    $sWhere = "o133_orcparamseq = {$this->codigo} and o133_orcparamrel = {$this->relatorio} and o133_anousu={$iAnoUsu}";
    $sSqlFiltroPadrao  = $oDaoLinhas->sql_query_file(null,"*", null, $sWhere);
    $rsFiltroPadrao    = $oDaoLinhas->sql_record($sSqlFiltroPadrao);
    $oFiltro = new stdClass();
    $oFiltro->contas = array();

    $oFiltro->orgao = new stdClass();
    $oFiltro->orgao->operador = 'in';
    $oFiltro->orgao->valor    = '';

    $oFiltro->unidade = new stdClass();
    $oFiltro->unidade->operador = 'in';
    $oFiltro->unidade->valor    = '';

    $oFiltro->funcao = new stdClass();
    $oFiltro->funcao->operador = 'in';
    $oFiltro->funcao->valor    = '';

    $oFiltro->subfuncao = new stdClass();
    $oFiltro->subfuncao->operador = 'in';
    $oFiltro->subfuncao->valor    = '';

    $oFiltro->programa = new stdClass();
    $oFiltro->programa->operador = 'in';
    $oFiltro->programa->valor    = '';

    $oFiltro->projativ = new stdClass();
    $oFiltro->projativ->operador = 'in';
    $oFiltro->projativ->valor    = '';

    $oFiltro->recurso = new stdClass();
    $oFiltro->recurso->operador = 'in';
    $oFiltro->recurso->valor    = '';

    $oFiltro->caracteristica = new stdClass();
    $oFiltro->caracteristica->operador = 'in';
    $oFiltro->caracteristica->valor    = '';

    $oFiltro->observacao        = '';
    if ($oDaoLinhas->numrows > 0) {

      $oDadosFiltro = db_utils::fieldsMemory($rsFiltroPadrao, 0);
      $oDomXml  = new DOMDocument();
      $oDomXml->loadXML($oDadosFiltro->o133_filtro);
      $aContas = $oDomXml->getElementsByTagName("conta");
      foreach($aContas as $oConta) {

        $oContaAdicionar = new stdClass();
        $oContaAdicionar->estrutural = $oConta->getAttribute("estrutural");
        $oContaAdicionar->nivel      = $oConta->getAttribute("nivel");
        $oContaAdicionar->exclusao   = $oConta->getAttribute("exclusao")=="true"?true:false;
        $oContaAdicionar->indicador  = $oConta->getAttribute("indicador");
        $oFiltro->contas[] = $oContaAdicionar;

      }
      $oOrgao = $oDomXml->getElementsByTagName("orgao");
      $oFiltro->orgao->operador = $oOrgao->item(0)->getAttribute("operador");
      $oFiltro->orgao->valor    = $oOrgao->item(0)->getAttribute("valor");

      $oUnidade = $oDomXml->getElementsByTagName("unidade");
      $oFiltro->unidade->operador = $oUnidade->item(0)->getAttribute("operador");
      $oFiltro->unidade->valor    = $oUnidade->item(0)->getAttribute("valor");

      $oFuncao = $oDomXml->getElementsByTagName("funcao");
      $oFiltro->funcao->operador = $oFuncao->item(0)->getAttribute("operador");
      $oFiltro->funcao->valor    = $oFuncao->item(0)->getAttribute("valor");

      $oSubFuncao = $oDomXml->getElementsByTagName("subfuncao");
      $oFiltro->subfuncao->operador = $oSubFuncao->item(0)->getAttribute("operador");
      $oFiltro->subfuncao->valor    = $oSubFuncao->item(0)->getAttribute("valor");

      $oPrograma = $oDomXml->getElementsByTagName("programa");
      $oFiltro->programa->operador = $oPrograma->item(0)->getAttribute("operador");
      $oFiltro->programa->valor    = $oPrograma->item(0)->getAttribute("valor");

      $oProjAtiv = $oDomXml->getElementsByTagName("projativ");
      $oFiltro->projativ->operador = $oProjAtiv->item(0)->getAttribute("operador");
      $oFiltro->projativ->valor    = $oProjAtiv->item(0)->getAttribute("valor");

      $oRecurso = $oDomXml->getElementsByTagName("recurso");
      $oFiltro->recurso->operador = $oRecurso->item(0)->getAttribute("operador");
      $oFiltro->recurso->valor    = $oRecurso->item(0)->getAttribute("valor");

      $oCaracteristica = $oDomXml->getElementsByTagName("caracteristica");
      if ($oCaracteristica->length > 0) {

        $oFiltro->caracteristica->operador = $oCaracteristica->item(0)->getAttribute("operador");
        $oFiltro->caracteristica->valor    = $oCaracteristica->item(0)->getAttribute("valor");
      }

      $oFiltro->recursocontalinha          = '';
      $oFiltro->recursocontadescricaolinha = '';

      $observacao           = $oDomXml->getElementsByTagName("observacao");
      $oFiltro->observacao  = '';
      if ($observacao->item(0)) {
       $oFiltro->observacao  = $observacao->item(0)->getAttribute("valor");
      }
      $oRecursoLinha = $oDomXml->getElementsByTagName("recursocontalinha");
      if ($oRecursoLinha && $oRecursoLinha->item(0)) {

        if (trim($oRecursoLinha->item(0)->getAttribute("numerolinha")) != "") {

          $oFiltro->recursocontalinha   = $oRecursoLinha->item(0)->getAttribute("numerolinha");
          $aLinhasCadastradas = explode(",", trim($oRecursoLinha->item(0)->getAttribute("numerolinha")));
          $aRecursosLinha     = array();

          foreach ($aLinhasCadastradas as $iLinha) {

            $oLinhaBase      = new linhaRelatorioContabil($this->relatorio, $iLinha);

            /**
             * consultamos as contas que a linha possui,
             * e verificamos no plano de contas, quais os recursos que a conta configurada nas contas possui
             */
            $aParametrosContaRecurso = $oLinhaBase->getParametrosPadrao();
            $aContas                 = $aParametrosContaRecurso->contas;

            foreach ($aContas as $oConta) {

              switch ($oLinhaBase->getOrigemDados()) {

                case linhaRelatorioContabil::ORIGEM_RECEITA:

                  $aRecursos = $this->getRecursosOrigemReceita(
                    db_getsession("DB_anousu"),
                    db_getsession("DB_instit"),
                    $oConta
                  );

                  break;

                case linhaRelatorioContabil::ORIGEM_DESPESA:

                  $aRecursos = $this->getRecursosOrigemDespesa(
                    db_getsession("DB_anousu"),
                    db_getsession("DB_instit"),
                    $oConta
                  );

                  break;

                default:

                  $aRecursos = $this->getRecursosPlanoDeContas(
                    db_getsession("DB_anousu"),
                    db_getsession("DB_instit"),
                    $oConta
                  );

              } // switch

              $aRecursosLinha = array_unique(array_merge($aRecursosLinha, $aRecursos));

            } // foreach

            $oFiltro->recurso->operador = "in";
            $oFiltro->recurso->valor    = implode(",", $aRecursosLinha);
          }
        }
      }

      /**
       * Verificamos se o usuário quer desdobrar a linha
       */
      $oDesdobrarLinha          = $oDomXml->getElementsByTagName("desdobrarlinha");
      $oFiltro->desdobrarlinha  = false;
      if ($oDesdobrarLinha->item(0)) {
        $oFiltro->desdobrarlinha = $oDesdobrarLinha->item(0)->getAttribute("valor")=="true"?true:false;
      }
    }
    return $oFiltro;
  }

  /**
   * exclui os parametros cadastrados pelo usuário da linha
   * @param $iAno
   * @throws Exception
   */
  public function excluirFiltroUsuario($iAno) {

    $oDaoLinhas = db_utils::getDao("orcparamseqfiltroorcamento");
    $oDaoLinhas->excluir(null,
                         "o133_orcparamseq    = {$this->codigo}
                         and o133_orcparamrel = {$this->relatorio}
                         and o133_anousu      = {$iAno}"
                         );
    if ($oDaoLinhas->erro_status == 0) {

      throw new Exception('Erro ao Excluir parâmetros.');
    }
  }

  /**
   * @param $iAno
   * @throws Exception
   */
  public function importarParametros($iAno) {

    $oDaoParametro        = db_utils::getDao("orcparamseqfiltropadrao");
    $oDaoParametroUsuario = db_utils::getDao("orcparamseqfiltroorcamento");

    $sWhere               = "o132_orcparamseq = {$this->codigo} and o132_orcparamrel = {$this->relatorio}";
    $sWhere              .= "and o132_anousu  = {$iAno}";
    $sSqlFiltroPadrao     = $oDaoParametro->sql_query_file(null,"*", null, $sWhere);
    $rsFiltroPadrao       = $oDaoParametro->sql_record($sSqlFiltroPadrao);
    if ($oDaoParametro->numrows > 0) {

      $oParametro = db_utils::fieldsMemory($rsFiltroPadrao, 0);
      $oDaoParametroUsuario->o133_orcparamrel = $this->relatorio;
      $oDaoParametroUsuario->o133_orcparamseq = $this->codigo;
      $oDaoParametroUsuario->o133_anousu      = $iAno;
      $oDaoParametroUsuario->o133_filtro      = $oParametro->o132_filtro;
      $sWhere       = "o133_orcparamseq = {$this->codigo} and o133_orcparamrel = {$this->relatorio}";
      $sWhere      .= "and o133_anousu  = {$iAno}";

      $sSqlFiltro  = $oDaoParametroUsuario->sql_query_file(null,"*", null, $sWhere);
      $rsFiltro    = $oDaoParametroUsuario->sql_record($sSqlFiltro);
      if ($oDaoParametroUsuario->numrows == 0) {
        $oDaoParametroUsuario->incluir(null);
      } else {

        $iCodigoLinha = db_utils::fieldsMemory($rsFiltro, 0)->o133_sequencial;
        $oDaoParametroUsuario->o133_sequencial = $iCodigoLinha;
        $oDaoParametroUsuario->alterar($iCodigoLinha);

      }
      if ($oDaoParametroUsuario->erro_status == 0) {
        throw new Exception("Erro ao salvar parametros!\nErro retornado:{$oDaoParametroUsuario->erro_msg}");
      }
    }
  }
  /**
   * retorna a descrição da linha
   *
   * @return string
   */
  function getDescricaoLinha() {
    return $this->sDescricao;
  }

  /**
   * verifica se linha é totalizadora
   *
   * @return boolean
   */
  public function isTotalizador() {
    return $this->lTotalizar;
  }

  /**
   * retorna o nivel de identação da conta.
   *
   * @return integer
   */
  public function getNivel() {
    return $this->iNivel;
  }

  /**
   * Busca dados vinculo SIGAP
   * @return bool|stdClass
   */
  public function getVinculoSigap() {

    $oDaoOrcParamSeqSigap  = db_utils::getDao("orcparamseqsigap");
    $iAnoUsu               = db_getsession("DB_anousu");
    $sWhere                = "o141_orcparamseq = {$this->codigo}        ";
    $sWhere               .= "and o141_orcparamrel = {$this->relatorio} ";
    $sWhere               .= "and o141_ano = {$iAnoUsu}                 ";
    $sSqlOrcParamSeqSigap  = $oDaoOrcParamSeqSigap->sql_query_file(null, "*", null, $sWhere);
    $rsOrcParamSeqSigap    = $oDaoOrcParamSeqSigap->sql_record($sSqlOrcParamSeqSigap);
    if ($oDaoOrcParamSeqSigap->numrows > 0) {

      $oDadosOrcParamSeqSigap = db_utils::fieldsMemory($rsOrcParamSeqSigap, 0);

      $oFiltro = new stdClass();
      $oFiltro->sequencial    = $oDadosOrcParamSeqSigap->o141_sequencial;
      $oFiltro->contasigap    = $oDadosOrcParamSeqSigap->o141_contasigap;
      $oFiltro->descricao     = $oDadosOrcParamSeqSigap->o141_descricao;
      $oFiltro->estrutural    = $oDadosOrcParamSeqSigap->o141_estrutural;
      $oFiltro->orcparamseq   = $oDadosOrcParamSeqSigap->o141_orcparamseq;
      $oFiltro->orcparamrel   = $oDadosOrcParamSeqSigap->o141_orcparamrel;
      $oFiltro->ano           = $oDadosOrcParamSeqSigap->o141_ano;
    } else {
    	$oFiltro = false;
    }

    return $oFiltro;
  }

  /**
   * Salva um vinculo SIGAP
   * @param $oFilter
   * @return $this
   * @throws Exception
   */
  public function salvarVinculoSigap($oFilter) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.\nProcessamento cancelado.");
    }


  	if (!isset($oFilter)) {
  		throw new Exception("Nenhum filtro informado!");
  	}

    $oDaoOrcParamSeqSigap  = db_utils::getDao("orcparamseqsigap");
    $iAnoUsu               = db_getsession("DB_anousu");

    $oDaoOrcParamSeqSigap->o141_contasigap  = $oFilter->contasigap;
    $oDaoOrcParamSeqSigap->o141_descricao   = $oFilter->descricao;
    $oDaoOrcParamSeqSigap->o141_estrutural  = $oFilter->estrutural;
    $oDaoOrcParamSeqSigap->o141_orcparamseq = $this->codigo;
    $oDaoOrcParamSeqSigap->o141_orcparamrel = $this->relatorio;
    $oDaoOrcParamSeqSigap->o141_ano         = $iAnoUsu;

    $sWhere                = "o141_orcparamseq = {$this->codigo}        ";
    $sWhere               .= "and o141_orcparamrel = {$this->relatorio} ";
    $sWhere               .= "and o141_ano = {$iAnoUsu}                 ";
    $sSqlOrcParamSeqSigap  = $oDaoOrcParamSeqSigap->sql_query_file(null, "*", null, $sWhere);
    $rsOrcParamSeqSigap    = $oDaoOrcParamSeqSigap->sql_record($sSqlOrcParamSeqSigap);
    if ($oDaoOrcParamSeqSigap->numrows == 0) {
      $oDaoOrcParamSeqSigap->incluir(null);
    } else {

      $iCodigoLinha = db_utils::fieldsMemory($rsOrcParamSeqSigap, 0)->o141_sequencial;
      $oDaoOrcParamSeqSigap->o141_sequencial = $iCodigoLinha;
      $oDaoOrcParamSeqSigap->alterar($iCodigoLinha);
    }

    if ($oDaoOrcParamSeqSigap->erro_status == 0) {
      throw new Exception("Erro ao salvar vinculo SIGAP!\nErro retornado:{$oDaoOrcParamSeqSigap->erro_msg}");
    }

    return $this;
  }

  /**
   * @param $iAno
   * @throws Exception
   */
  public function excluirVinculoSigap($iAno) {

    $oDaoOrcParamSeqSigap  = db_utils::getDao("orcparamseqsigap");
    $oDaoOrcParamSeqSigap->excluir(null,
                                   "o141_orcparamseq     = {$this->codigo}
                                    and o141_orcparamrel = {$this->relatorio}
                                    and o141_ano         = {$iAno}"
                                  );
    if ($oDaoOrcParamSeqSigap->erro_status == 0) {
      throw new Exception("Erro ao excluir vinculo SIGAP!\nErro retornado:{$oDaoOrcParamSeqSigap->erro_msg}");
    }
  }

  /**
   * Permite listar no relatório as contas que foram configurados para o calculo da linha
   * @return boolean
   */
  public function desdobraLinha() {
    return $this->lpermiteDesdobrar;
  }

  /**
   * Retorna a ordem da linha dentro do relatório
   * @return int
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Retorna a origem dos dados da linha
   * @return int
   */
  public function getOrigemDados() {
    return $this->iOrigemDados;
  }

  /**
   * @param $iAno
   * @param $sInstituicao
   * @param StdClass $oConta
   * @return array
   * @throws Exception
   */
  private function getRecursosOrigemReceita($iAno, $sInstituicao, StdClass $oConta) {

    $sWhere = " o70_anousu = {$iAno}";
    $aRecursos = array();

    if ($oConta->nivel != "") {
      $sWhere .= " and o57_fonte ilike '".substr($oConta->estrutural, 0, $oConta->nivel)."%' ";
    } else {
      $sWhere .= " and o57_fonte = '{$oConta->estrutural}'";
    }

    $sWhere .= "and o70_instit in ({$sInstituicao})";
    $oDaoOrcReceita   = new cl_orcreceita();
    $sSqlBuscaRecurso = $oDaoOrcReceita->sql_query_receita("distinct o70_codigo as recurso", null, $sWhere);
    $rsRecursos       = $oDaoOrcReceita->sql_record($sSqlBuscaRecurso);

    if ($oDaoOrcReceita->numrows == 0) {
      return $aRecursos;
    }

    for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++ ) {

      $iRecurso = db_utils::fieldsMemory($rsRecursos, $i)->recurso;
      if (!in_array($iRecurso, $aRecursos)) {
        array_push($aRecursos, $iRecurso);
      }
    }

    return $aRecursos;
  }

  /**
   * @param $iAno
   * @param $sInstituicao
   * @param StdClass $oConta
   * @return array
   * @throws Exception
   */
  private function getRecursosOrigemDespesa($iAno, $sInstituicao, StdClass $oConta) {

    $sWhere = " c61_anousu = {$iAno}";
    $aRecursos = array();

    if ($oConta->nivel != "") {
      $sWhere .= " and c60_estrut like '".substr($oConta->estrutural, 0, $oConta->nivel)."%' ";
    } else {
      $sWhere .= " and c60_estrut = '{$oConta->estrutural}'";
    }

    $sWhere .= "and c61_instit in ({$sInstituicao})";
    $oDaoOrcElemento  = new cl_orcelemento();
    $sSqlBuscaRecurso = $oDaoOrcElemento->sql_query_despesa_orcamento('distinct c61_codigo as recurso', null, $sWhere);
    $rsRecursos       = $oDaoOrcElemento->sql_record($sSqlBuscaRecurso);

    if ($oDaoOrcElemento->numrows == 0) {
      return $aRecursos;
    }

    for ($i = 0; $i < $oDaoOrcElemento->numrows; $i++ ) {

      $iRecurso = db_utils::fieldsMemory($rsRecursos, $i)->recurso;
      if (!in_array($iRecurso, $aRecursos)) {
        array_push($aRecursos, $iRecurso);
      }
    }

    return $aRecursos;
  }

  /**
   * @param $iAno
   * @param $sInstituicao
   * @param StdClass $oConta
   * @return array
   * @throws Exception
   */
  private function getRecursosPlanoDeContas($iAno, $sInstituicao, StdClass $oConta) {

    $oDaoConplanoReduz  = new cl_conplanoreduz();
    $sWhere = " c61_anousu = {$iAno}";
    $aRecursos = array();

    if ($oConta->nivel != "") {
      $sWhere .= " and c60_estrut like '".substr($oConta->estrutural, 0, $oConta->nivel)."%' ";
    } else {
      $sWhere .= " and c60_estrut = '{$oConta->estrutural}'";
    }

    $sWhere .= "and c61_instit in ({$sInstituicao})";
    $sSqlRecursos   = analiseQueryPlanoOrcamento($oDaoConplanoReduz->sql_query(null, null,"distinct c61_codigo as recurso", null, $sWhere));
    $rsRecursos     = db_query($sSqlRecursos);

    if (!$rsRecursos) {
      throw new Exception("Erro ao buscar recurso com origem plano de contas.");
    }

    for ($i = 0; $i < pg_num_rows($rsRecursos); $i++ ) {

      $iRecurso = db_utils::fieldsMemory($rsRecursos, $i)->recurso;
      if (!in_array($iRecurso, $aRecursos)) {
        array_push($aRecursos, $iRecurso);
      }
    }

    return $aRecursos;
  }

}