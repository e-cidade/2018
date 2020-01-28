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
 * Classe para mapear Programas, tabela orcprograma
 * @author bruno.silva
 * @author acacio.schneider
 * @package orcamento
 * @version $Revision: 1.10 $
 */
class Programa {

  /**
   * Ano do Programa
   * Campo: o54_anousu
   * @var integer
   */
  private $iAno;

  /**
   * Código do Programa
   * Campo: o54_programa
   * @var integer
   */
  private $iCodigoPrograma;

  /**
   * Descrição do Programa
   * Campo: o54_descr
   * @var string
   */
  private $sDescricao;

  /**
   * Objetivos vinculados ao programa
   * @var ProgramaObjetivo[]
   */
  private $aObjetivos;

  /**
   * Retorna a propriedade ano
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * Seta a propriedade ano
   * @param integer $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * Retorna o código do programa
   * @return   integer
   */
  public function getCodigoPrograma() {
    return $this->iCodigoPrograma;
  }

  /**
   * Seta codigo
   * @param integer $iCodigoPrograma
   */
  public function setCodigoPrograma($iCodigoPrograma) {
    $this->iCodigoPrograma = $iCodigoPrograma;
  }

  /**
   * Retorna descricao do programa
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta propriedade de descrição
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return ProgramaObjetivo[]
   */
  public function getObjetivos() {

    if (empty($this->aObjetivos)) {
      $this->buscaObjetivos();
    }
    return $this->aObjetivos;
  }

  /**
   * Construtor da classe
   * @param integer $iCodigoPrograma
   * @param integer $iAno
   */
  public function __construct($iCodigoPrograma = null, $iAno = null) {

    if (!is_null($iCodigoPrograma) && !empty($iAno) ) {

      $oDAOOrcprograma = db_utils::getDao("orcprograma");

      $sWhere          = "     o54_anousu   = {$iAno}";
      $sWhere         .= " and o54_programa = {$iCodigoPrograma}";
      $sSQL            = $oDAOOrcprograma->sql_query_file(null, null, "*", null, $sWhere);
      $rsResultado     = $oDAOOrcprograma->sql_record($sSQL);

      if ($oDAOOrcprograma->erro_status == "0") {

        $sMensagemErro  = "Erro Técnico: erro ao carregar dados do Programa {$iCodigoPrograma} do ano {$iAno}.";
        $sMensagemErro .= $oDAOOrcprograma->erro_msg;
        throw new DBException($sMensagemErro);
      }

      $oPrograma             = db_utils::fieldsMemory($rsResultado, 0);
      $this->iAno            = $iAno;
      $this->iCodigoPrograma = $iCodigoPrograma;
      $this->sDescricao      = $oPrograma->o54_descr;
    }
  }

  /**
   * Adiciona um objetivo ao programa
   * @param   ProgramaObjetivo $oObjetivo
   * @throws  DBException
   */
  public function adicionaObjetivo(ProgramaObjetivo $oObjetivo) {

    $oDaoOrcProgramVinculoObjetivo = db_utils::getDao("orcprogramavinculoobjetivo");
    $oDaoOrcProgramVinculoObjetivo->o144_orcprogramaprograma = $this->iCodigoPrograma;
    $oDaoOrcProgramVinculoObjetivo->o144_orcprogramaanousu   = $this->iAno;
    $oDaoOrcProgramVinculoObjetivo->o144_orcobjetivo         = $oObjetivo->getCodigoSequencial();
    $oDaoOrcProgramVinculoObjetivo->incluir(null);

    if ($oDaoOrcProgramVinculoObjetivo->erro_status == "0") {

      $sMensagemErro  = "Erro Técnico: erro ao salvar vínculo entre o Programa {$this->iCodigoPrograma}";
      $sMensagemErro .= " do ano {$this->iAno} e o Objetivo {$oObjetivo->getCodigoSequencial()}.";
      $sMensagemErro .= $oDaoOrcProgramVinculoObjetivo->erro_msg;
      throw new DBException($sMensagemErro);
    }
    $this->aObjetivos[$oObjetivo->getCodigoSequencial()] = $oObjetivo;
  }

  /**
   * Busca os objetivos do programa
   * @throws DBException
   */
  private function buscaObjetivos() {

    $oDaoOrcProgramaVinculoObjetivo = db_utils::getDao("orcprogramavinculoobjetivo");
    $sWhere                         = "     o144_orcprogramaprograma = {$this->iCodigoPrograma}";
    $sWhere                        .= " and o144_orcprogramaanousu   = {$this->iAno}";
    $sSQL                           = $oDaoOrcProgramaVinculoObjetivo->sql_query("null", "*", null, $sWhere);
    $rsResultado                    = db_query($sSQL);
    $iTotalObjetivos                = pg_num_rows($rsResultado);

    if (!$rsResultado) {

      $sMensagemErro  = "Erro Técnico: erro ao buscar vínculo entre o Programa {$this->iCodigoPrograma}";
      $sMensagemErro .= " e o Objetivos.";
      $sMensagemErro .= $oDaoOrcProgramaVinculoObjetivo->erro_msg;
      throw new DBException($sMensagemErro);
    }

    $this->aObjetivos = array();
    for ($iObjetivo = 0; $iObjetivo < $iTotalObjetivos; $iObjetivo++) {

      $oStdObjetivo                                      = db_utils::fieldsMemory($rsResultado, $iObjetivo);
      $oObjetivo                                         = new ProgramaObjetivo($oStdObjetivo->o144_orcobjetivo);
      $this->aObjetivos[$oStdObjetivo->o144_orcobjetivo] = $oObjetivo;
    }
  }

  /**
   * Exclui vinculo entre um objetivo e um programa
   * @param  ProgramaObjetivo $oObjetivo
   * @throws DBException
   */
  public function excluirObjetivo(ProgramaObjetivo $oObjetivo) {

    $oDaoOrcProgramaVinculoObjetivo = db_utils::getDao("orcprogramavinculoobjetivo");
    $sWhere  = "     o144_orcobjetivo         = {$oObjetivo->getCodigoSequencial()}";
    $sWhere .= " and o144_orcprogramaprograma = {$this->iCodigoPrograma}";
    $sWhere .= " and o144_orcprogramaanousu   = {$this->iAno}";

    $oDaoOrcProgramaVinculoObjetivo->excluir(null, $sWhere);

    if ($oDaoOrcProgramaVinculoObjetivo->erro_status == "0") {

      $sMensagemErro  = "Erro Técnico: erro ao excluir vínculo entre o programa {$this->iCodigoPrograma}";
      $sMensagemErro .= " do ano {$this->iAno} e o Objetivo {$oObjetivo->getCodigoSequencial()}.";
      $sMensagemErro .= $oDaoOrcProgramaVinculoObjetivo->erro_msg;
      throw new DBException($oDaoOrcProgramaVinculoObjetivo);
    }

    unset($this->aObjetivos[$oObjetivo->getCodigoSequencial()]);
  }

  /**
   * Método que retorna um array indexado pelo ano contendo o valor global estimado para o programa
   * passado por parâmetro
   *
   * @param integer $iCodigoPrograma
   * @param integer $iAno
   * @param integer $iCodigoPerspectiva
   * @return array
   */
  public static function getValorGlobalEstimadoPPAPorAno($iCodigoPrograma, $iAno, $iCodigoPerspectiva) {

    $sWherePrograma  = "       ppadotacao.o08_ppaversao = {$iCodigoPerspectiva} ";
    $sWherePrograma .= "   and orcprograma.o54_programa in ({$iCodigoPrograma}) ";
    $sWherePrograma .= "   and o05_anoreferencia > {$iAno} ";
    $sWherePrograma .= " group by o05_anoreferencia ";
    $sWherePrograma .= " order by o05_anoreferencia ";
    $sCamposPrograma = "sum(o05_valor) as o05_valor, ppaestimativa.o05_anoreferencia";

    $aValoresRetorno          = array();
    $oDaoPPADotacao           = db_utils::getDao('ppadotacao');
    $sSqlBuscaValoresPrograma = $oDaoPPADotacao->sql_query_despesa_programa(null, $sCamposPrograma, null, $sWherePrograma);
    $rsBuscaValoresPrograma   = $oDaoPPADotacao->sql_record($sSqlBuscaValoresPrograma);

    for ($iRowPrograma = 0; $iRowPrograma < $oDaoPPADotacao->numrows; $iRowPrograma++) {

      $oStdValor = db_utils::fieldsMemory($rsBuscaValoresPrograma, $iRowPrograma);
      $aValoresRetorno[$oStdValor->o05_anoreferencia] = $oStdValor->o05_valor;
      unset($oStdValor);
    }
    return $aValoresRetorno;
  }

  /**
   * Método que retorna um array com os indicadores vinculados ao programa passado por parâmetro
   * @param integer $iCodigoPrograma
   * @param integer $iAno
   * @static
   * @access public
   * @todo fazer com que seja um método da insância e não static
   * @return array
   */
  public static function getDadosIndicadores($iCodigoPrograma, $iAno) {

    $sWhereIndicadores  = "     orcindicaprograma.o18_orcprograma = {$iCodigoPrograma}";
    $sWhereIndicadores .= " and orcindicaprograma.o18_anousu      = {$iAno}";
    $aCamposIndicadores = array();

    $sCamposIndicadores  = " orcindica.o10_descr as s_descricao,";
    $sCamposIndicadores .= " orcindica.o10_descrunidade as s_unidade,";
    $sCamposIndicadores .= " orcindicaindiceesperado.o25_anousu as i_ano,";
    $sCamposIndicadores .= " orcindicaindiceesperado.o25_valor as n_valor";
    $sOrderIndicadores   = " orcindica.o10_indica asc, ";
    $sOrderIndicadores  .= " orcindicaindiceesperado.o25_anousu asc";

    $oDaoIndicadores      = db_utils::getDao("orcindicaprograma");
    $sSqlIndicadores      = $oDaoIndicadores->sql_query_indicadores(null, $sCamposIndicadores, $sOrderIndicadores, $sWhereIndicadores);
    $rsIndicadores        = $oDaoIndicadores->sql_record($sSqlIndicadores);
    $aValoresRetorno = array();
    for ($iIndicador = 0; $iIndicador < $oDaoIndicadores->numrows; $iIndicador++) {

      $oStdIndicadores   = db_utils::fieldsMemory($rsIndicadores, $iIndicador);
      $aValoresRetorno[] = $oStdIndicadores;
      unset($oStdIndicadores);
    }
    return $aValoresRetorno;
  }
}

?>