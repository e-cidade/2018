<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Model responsável pela criação dos cursos sociais
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package model
 * @subpackage social
 * @version $Revision: 1.8 $
 */
class CursoSocial {

  /**
   * Código sequencial da tabela
   * @var integer
   */
  private $iCodigo;

  /**
   * Nome do curso
   * @var string
   */
  private $sNome;

  /**
   * Descrição do conteúdo/objetivo/outros sobre o curso
   * @var string
   */
  private $sDetalhamento;

  /**
   * Vinculo com a Categoria
   * @var CursoCategoria
   */
  private $oCategoria ;

  /**
   * Data de inicio do curso
   * @var DBDate
   */
  private $oDtInicio;

  /**
   * Data de encerramento do curso
   * @var DBDate
   */
  private $oDtFim;

  /**
   * Número de horas por aula
   * @var float
   */
  private $nNumeroDeHorasAula;

  /**
   * Ministrante do curso
   * @var CgmBase
   */
  private $oMinistrante;

  /**
   * Responsável pelo curso
   * @var CgmBase
   */
  private $oResponsavel;

  /**
   * Array contendo os dias da Semana que o curso tem aula
   * Array de stdClass
   * @var array
   */
  private $aDiaSemanaCurso = array();

  /**
   * Array contendo os dias de aula  do curso
   * Array de DBDate
   * @var array
   */
  private $aDiasAula = array();

  /**
   * Array com os Cidadao matriculados no curso
   * @var array
   */
  private $aMatriculas = array();

  private $aMesesAbrangencia = array();

  public function __construct($iCodigoCurso = null) {

    if (!empty($iCodigoCurso)) {

      $oDaoCursoSocial = new cl_cursosocial();
      $sSqlCursoSocial = $oDaoCursoSocial->sql_query_file($iCodigoCurso);
      $rsCursoSocial   = $oDaoCursoSocial->sql_record($sSqlCursoSocial);

      if ($oDaoCursoSocial->numrows == 1) {

        $oDados = db_utils::fieldsMemory($rsCursoSocial, 0);

        $this->iCodigo            = $oDados->as19_sequencial;
        $this->sNome              = $oDados->as19_nome;
        $this->sDetalhamento      = $oDados->as19_detalhamento;
        $this->oCategoria         = new CursoCategoria($oDados->as19_tabcurritipo);
        $this->oDtInicio          = new DBDate($oDados->as19_inicio);
        $this->oDtFim             = new DBDate($oDados->as19_fim);
        $this->nNumeroDeHorasAula = $oDados->as19_horaaulasdia;
        $this->oMinistrante       = CgmFactory::getInstanceByCgm($oDados->as19_ministrante);
        $this->oResponsavel       = CgmFactory::getInstanceByCgm($oDados->as19_responsavel);
      }
    }
  }

  /**
   * Retorna Código sequencial da tabela
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna nome do curso
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Seta Nome do curso
   * @param $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna Descrição do conteúdo/objetivo/outros sobre o curso
   * @return string
   */
  public function getDetalhamento() {
    return $this->sDetalhamento;
  }

  /**
   * Seta Descrição do conteúdo/objetivo/outros sobre o curso
   * @param $sDetalhamento
   */
  public function setDetalhamento($sDetalhamento) {
    $this->sDetalhamento = $sDetalhamento;
  }

  /**
   * Retorna vinculo com a tabcurriculo
   * @return CursoCategoria
   */
  public function getCategoria() {
    return $this->oCategoria;
  }

  /**
   * Seta vinculo com a tabcurriculo
   * @param $oCategoria
   */
  public function setCategoria(CursoCategoria $oCategoria) {
    $this->oCategoria = $oCategoria;
  }

  /**
   * Retorna Data de inicio do curso
   * @return DBDate
   */
  public function getDataInicio() {
    return $this->oDtInicio;
  }

  /**
   * Seta Data de inicio do curso
   * @param $oDtInicio
   */
  public function setDataInicio($oDtInicio) {
    $this->oDtInicio = $oDtInicio;
  }

  /**
   * Retorna Data de encerramento do curso
   * @return DBDate
   */
  public function getDataFim() {
    return $this->oDtFim;
  }

  /**
   * Seta Data de encerramento do curso
   * @param $oDtFim
   */
  public function setDataFim($oDtFim) {
    $this->oDtFim = $oDtFim;
  }

  /**
   * Retorna Número de horas por aula
   * @return float
   */
  public function getNumeroDeHorasAula() {
    return $this->nNumeroDeHorasAula;
  }

  /**
   * Seta Número de horas por aula
   * @param $nNumeroDeHorasAula
   */
  public function setNumeroDeHorasAula($nNumeroDeHorasAula) {
    $this->nNumeroDeHorasAula = $nNumeroDeHorasAula;
  }

  /**
   * Retorna Ministrante do curso
   * @return CgmBase
   */
  public function getMinistrante() {
    return $this->oMinistrante;
  }

  /**
   * Seta Ministrante do curso
   * @param $oMinistrante
   */
  public function setMinistrante($oMinistrante) {
    $this->oMinistrante = $oMinistrante;
  }

  /**
   * Retorna Responsavel do curso
   * @return CgmBase
   */
  public function getResponsavel() {
    return $this->oResponsavel;
  }

  /**
   * Seta Responsavel do curso
   * @param $oResponsavel
   */
  public function setResponsavel($oResponsavel) {
    $this->oResponsavel = $oResponsavel;
  }

  /**
   * Salva os dados do curso social
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }

    $oDaoCursoSocial = new cl_cursosocial();
    $oDaoCursoSocial->as19_sequencial   = null;
    $oDaoCursoSocial->as19_nome         = $this->sNome;
    $oDaoCursoSocial->as19_detalhamento = $this->sDetalhamento;
    $oDaoCursoSocial->as19_tabcurritipo = $this->oCategoria->getCodigo();
    $oDaoCursoSocial->as19_inicio       = $this->oDtInicio->convertTo(DBDate::DATA_EN);
    $oDaoCursoSocial->as19_fim          = $this->oDtFim->convertTo(DBDate::DATA_EN);
    $oDaoCursoSocial->as19_horaaulasdia = $this->nNumeroDeHorasAula;
    $oDaoCursoSocial->as19_ministrante  = $this->oMinistrante->getCodigo();
    $oDaoCursoSocial->as19_responsavel  = $this->oResponsavel->getCodigo();

    if (!empty($this->iCodigo)) {

      $oDaoCursoSocial->as19_sequencial = $this->iCodigo;
      $oDaoCursoSocial->alterar($this->iCodigo);
    } else {

      $oDaoCursoSocial->incluir(null);
      $this->iCodigo = $oDaoCursoSocial->as19_sequencial;
    }

    if ($oDaoCursoSocial->erro_status == 0) {

      $sMsgErro  = "Não foi possível salvar o curso".
      $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoSocial->erro_msg);
      throw new BusinessException($sMsgErro);
    }

    /**
     * Salva os dias da semana
     */
    foreach ($this->aDiaSemanaCurso as $iDiaSemana) {

      $oDaoCursoDiaSemana = new cl_cursosocialdiasemana();
      $sWhere             = " as20_diasemana       = {$iDiaSemana}";
      $sWhere            .= " and as20_cursosocial = {$this->iCodigo}";
      $oDaoCursoDiaSemana->excluir(null, $sWhere);

      $oDaoCursoDiaSemana->as20_sequencial  = null;
      $oDaoCursoDiaSemana->as20_diasemana   = $iDiaSemana;
      $oDaoCursoDiaSemana->as20_cursosocial = $this->iCodigo;

      $oDaoCursoDiaSemana->incluir(null);
      if ($oDaoCursoDiaSemana->erro_status == 0) {

        $sMsgErro  = "Não foi possível incluir o dia da semana do curso.\n".
        $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoDiaSemana->erro_msg);
        throw new BusinessException($sMsgErro);
      }
    }

    return true;
  }


  /**
   * Remove um curso social somente quando não há matricula vinculada ao curso
   * @throws BusinessException
   * @return boolean
   */
  public function removerCurso () {

    $oDaoCursoSocial    = new cl_cursosocial();
    $oDaoCursoAula      = new cl_cursosocialaula();
    $oDaoCursoCidadao   = new cl_cursosocialcidadao();
    $oDaoCursoDiaSemana = new cl_cursosocialdiasemana();

    /**
     * Valida se tem cidadao matriculado
     */
    $sWhereCidadao = " as22_cursosocial = {$this->iCodigo} ";
    $sSqlCidadao   = $oDaoCursoCidadao->sql_query_file(null, "1", null, $sWhereCidadao);
    $rsCidadao     = $oDaoCursoCidadao->sql_record($sSqlCidadao);

    if ($oDaoCursoCidadao->numrows > 0) {

      $sMsgErro  = "Existem cidadãos matriculados no curso {$this->getNome()}.\n";
      $sMsgErro .= "Remova as matrículas vinculadas a este curso, para então removê-lo.";
      throw new BusinessException($sMsgErro);
    }

    /**
     * Exclui os dias da semana
     */
    $sWhereDiaSemana = " as20_cursosocial = {$this->iCodigo} ";
    $oDaoCursoDiaSemana->excluir(null, $sWhereDiaSemana);
    if ($oDaoCursoDiaSemana->erro_status == 0) {

      $sMsgErro  = "Não foi possível excluir os dias da semana.\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoDiaSemana->erro_msg);
      throw new BusinessException($sMsgErro);
    }

    /**
     * Exclui os dias de aula
     */
    $sWhereCursoAula = "as21_cursosocial = {$this->iCodigo}";
    $oDaoCursoAula->excluir(null, $sWhereCursoAula);
    if ($oDaoCursoAula->erro_status == 0) {

      $sMsgErro  = "Não foi possível excluir os dias de aula.\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoAula->erro_msg);
      throw new BusinessException($sMsgErro);
    }

    /**
     * Exclui o curso
     */
    $sWhereCursoSocial = "as19_sequencial = {$this->iCodigo}";
    $oDaoCursoSocial->excluir(null, $sWhereCursoSocial);
    if ($oDaoCursoSocial->erro_status == 0) {

      $sMsgErro  = "Não foi possível excluir o curso.\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoSocial->erro_msg);
      throw new BusinessException($sMsgErro);
    }
    return true;
  }


  /**
   * Recebe o código do dia da semana
   * $iDiaSemana é o código da tabela diasemana
   * @param integer $iDiaSemana
   */
  public function adicionarDiaSemana($iDiaSemana) {

    $this->getDiasSemana();
    $lDiaSemanaJaAdicionado = false;
    foreach ($this->aDiaSemanaCurso as $iDiaSemanaCurso) {

      if ($iDiaSemanaCurso == $iDiaSemana) {

        $lDiaSemanaJaAdicionado = true;
        break;
      }
    }

    if (!$lDiaSemanaJaAdicionado) {
      $this->aDiaSemanaCurso[] = $iDiaSemana;
    }
  }

  /**
   * Remove um dia da semana do curso
   * Recebe o código do dia da semana
   * $iDiaSemana é o código da tabela "diasemana"
   * @param integer $iDiaSemana
   * @throws BusinessException
   */
  public function removerDiaSemana($iDiaSemana) {

    if (empty($this->iCodigo)) {
      throw new BusinessException("Nenhum curso definido.");
    }

    $this->getDiasSemana();
    foreach ($this->aDiaSemanaCurso as $iPosicao => $iDiaSemanaCurso) {

      if ($iDiaSemanaCurso == $iDiaSemana) {

        unset($this->aDiaSemanaCurso[$iPosicao]);

        /**
         * Removemos do banco de dados
         */
        $oDaoCursoDiaSemana = new cl_cursosocialdiasemana();
        $sWhere             = " as20_diasemana       = {$iDiaSemanaCurso}";
        $sWhere            .= " and as20_cursosocial = {$this->iCodigo}";
        $oDaoCursoDiaSemana->excluir(null, $sWhere);

        if ($oDaoCursoDiaSemana->erro_status == 0) {

          $sMsgErro  = "Não foi possível remover o dia da semana do curso\n".
          $sMsgErro .= str_replace("\\n", "\n", $oDaoCursoDiaSemana->erro_msg);
          throw new BusinessException($sMsgErro);
        }

        break;
      }
    }
  }

  /**
   * Retorna os dias de semana que o curso tem aulas
   * @return array de dias da semana de acordo com a tabela "diasemana"
   */
  public function getDiasSemana() {

    if (count($this->aDiaSemanaCurso) == 0 && !empty($this->iCodigo)) {

      $oDaoCursoDiaSemana = new cl_cursosocialdiasemana();
      $sWhere             = " as20_cursosocial = {$this->iCodigo}";
      $sSqlCursoDiaSemana = $oDaoCursoDiaSemana->sql_query_file(null, "as20_diasemana", "as20_diasemana", $sWhere);
      $rsCursoDiaSemana   = $oDaoCursoDiaSemana->sql_record($sSqlCursoDiaSemana);
      $iLinhas            = $oDaoCursoDiaSemana->numrows;

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {
          $this->aDiaSemanaCurso[] = db_utils::fieldsMemory($rsCursoDiaSemana, $i)->as20_diasemana;
        }
      }
    }

    return $this->aDiaSemanaCurso;
  }

  /**
   * Adiciona um dia de aula ao array {aDiasAula} mais antes valida se o dia já não existe no array
   * @throws BusinessException
   * @throws DBException
   * @param DBDate $oData
   */
  public function adicionarDiaDeAula(DBDate $oData) {

    $lDataJaAdicionada = false;

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }

    if (empty($this->iCodigo)) {
      throw new BusinessException("Antes de adicionar um dia de aula, deve ser selecionado um curso.");
    }

    $this->getDiasDeAula();

    if ($oData < $this->getDataInicio() || $oData > $this->getDataFim()) {

      $sMsgErro  = " Dia: " . $oData->convertTo(DBDate::DATA_PTBR) . " está fora do período do curso (";
      $sMsgErro .= $this->getDataInicio()->convertTo(DBDate::DATA_PTBR) ." - ";
      $sMsgErro .= $this->getDataFim()->convertTo(DBDate::DATA_PTBR) .")";
      throw new BusinessException($sMsgErro);
    }
    /**
     * Verificamos se a data já não foi adicionada
     */
    foreach ($this->aDiasAula as $oDiaAula) {

      if ($oDiaAula->oDataAula->getTimeStamp() == $oData->getTimeStamp()) {

        if ($oData == $oDiaAula->oDataAula) {
          throw new BusinessException("Dia de aula não adicionado. Já existe aula agendada para este dia .");
        }
      }
    }

    /**
     * Se for uma data nova, adicionamos a data
     */
    if (!$lDataJaAdicionada) {

      $oDaoDiaAula  = new cl_cursosocialaula();
      $oDaoDiaAula->as21_sequencial  = null;
      $oDaoDiaAula->as21_cursosocial = $this->iCodigo;
      $oDaoDiaAula->as21_dataaula    = $oData->convertTo(DBDate::DATA_EN);
      $oDaoDiaAula->incluir(null);

      if ($oDaoDiaAula->erro_status == 0) {

        $dtAula    = $oData->convertTo(DBDate::DATA_PTBR);
        $sMsgErro  = "Não foi possível incluir o dia de aula {$dtAula} para o curso\n".
        $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
        throw new BusinessException($sMsgErro);
      }

      $oDiaAula            = new stdClass();
      $oDiaAula->iCodigo   = $oDaoDiaAula->as21_sequencial;
      $oDiaAula->oDataAula = $oData;

      $this->aDiasAula[] = $oDiaAula;
    }
  }

  /**
   * Remove um dia de aula do array {aDiasAula}
   * @param integer $iCodigoDiaAula sequencial da tabela: "cursosocialaula"
   */
  public function removerDiaDeAula($iCodigoDiaAula) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados");
    }

    if (empty($this->iCodigo)) {
      throw new BusinessException("Nenhum curso definido.");
    }

    $this->getDiasDeAula();

    foreach ($this->aDiasAula as $iPosicao => $oDiaAula) {

      if ($oDiaAula->iCodigo == $iCodigoDiaAula) {

        /**
         * Valida se um aluno tem ausencia lançada
         */
        $oDaoAusencia = new cl_cursosocialcidadaoausencia();
        $sWhere       = " as22_cursosocial         = {$this->iCodigo} ";
        $sWhere      .= " and as18_cursosocialaula = {$iCodigoDiaAula} ";
        $sSqlAusencia = $oDaoAusencia->sql_query(null, "1", null, $sWhere);
        $rsAusencia   = $oDaoAusencia->sql_record($sSqlAusencia);

        if ($oDaoAusencia->numrows > 0) {

          $sMsgErro  = "Não é foi possível excluir este dia pois existe um ou mais aluno com ausência lançada \n".
          $sMsgErro .= str_replace("\\n", "\n", $oDaoAusencia->erro_msg);
          throw new BusinessException($sMsgErro);
        }

        /**
         * Remove dia de aula
         */
        $oDaoDiaAula = new cl_cursosocialaula();
        $oDaoDiaAula->excluir($iCodigoDiaAula);

        if ($oDaoDiaAula->erro_status == 0) {

          $sMsgErro  = "Não foi possível excluir o dia de aula para o curso\n".
          $sMsgErro .= str_replace("\\n", "\n", $oDaoDiaAula->erro_msg);
          throw new BusinessException($sMsgErro);
        }

        unset($this->aDiasAula[$iPosicao]);
        break;
      }
    }
  }

  /**
   * Retorna os dias em que o curso tem aula
   */
  public function getDiasDeAula() {

    if (count($this->aDiasAula) == 0 && !empty($this->iCodigo)) {

      $oDaoDiaAula = new cl_cursosocialaula();
      $sWhere      = "as21_cursosocial = {$this->iCodigo}";
      $sSqlDiaAula = $oDaoDiaAula->sql_query_file(null, "as21_dataaula, as21_sequencial", "as21_dataaula", $sWhere);
      $rsDiaAula   = $oDaoDiaAula->sql_record($sSqlDiaAula);
      $iLinhas     = $oDaoDiaAula->numrows;

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $oDados = db_utils::fieldsMemory($rsDiaAula, $i);

          $oDiaAula            = new stdClass();
          $oDiaAula->iCodigo   = $oDados->as21_sequencial;
          $oDiaAula->oDataAula = new DBDate($oDados->as21_dataaula);

          $this->aDiasAula[] = $oDiaAula;
        }
      }
    }
    return $this->aDiasAula;
  }

  /**
   * Marcada esta função como deprecated pois se o curso abrangir mais de um ano, não vai retornar os dados corrétos 
   * @deprecated 
   * @see $this->getDiasDeAulaPorMesAno
   * @param unknown $iMes
   * @return multitype:unknown
   */
  public function getDiasDeAulaPorMes ($iMes) {

    $aDiasMes = array();
    foreach ($this->getDiasDeAula() as $oDia) {

      if ((int) $oDia->oDataAula->getMes() == $iMes) {
        $aDiasMes[] = $oDia;
      }
    }
    return $aDiasMes;
  }
  
  
  /**
   * Retorna os dias de aula do curso para o mes selecionado
   * @param integer $iMes
   * @param integer $iAno
   * @return multitype:sdtClass
   */
  public function getDiasDeAulaPorMesAno ($iMes, $iAno) {
  
    $aDiasMes = array();
    foreach ($this->getDiasDeAula() as $oDia) {
  
      if ((int) $oDia->oDataAula->getMes() == $iMes && $oDia->oDataAula->getAno() == $iAno) {
        $aDiasMes[] = $oDia;
      }
    }
    return $aDiasMes;
  }

  /**
   * Retorna um array com os dias do curso que possuem falta lançada
   * @return multitype:NULL
   */
  public function diasComAusencia () {

    $aDiaComFalta = array();
    $oDaoAusencia = new cl_cursosocialcidadaoausencia();
    $sWhere       = " as22_cursosocial = {$this->iCodigo} ";
    $sSqlAusencia = $oDaoAusencia->sql_query(null, "distinct as21_dataaula", "as21_dataaula", $sWhere);
    $rsAusencia   = $oDaoAusencia->sql_record($sSqlAusencia);
    $iLinhas      = $oDaoAusencia->numrows;

    if ($oDaoAusencia->numrows > 0) {

      for ($i = 0; $i < $iLinhas; $i++) {
        $aDiaComFalta[] = db_utils::fieldsMemory($rsAusencia, $i)->as21_dataaula;
      }
    }
    return $aDiaComFalta;
  }

  /**
   * Valida a alteracao da data inicial e/ou final do curso, verificando se dentro das aulas existentes, ha algum dia
   * que nao esteja no novo intervalo de datas
   * @param DBDate $oDtInicio
   * @param DBDate $oDtFim
   * @return boolean
   */
  public function permiteAlteracaoDataCurso(DBDate $oDtInicio, DBDate $oDtFim) {

    $lPermiteAlteracao = true;
    foreach ($this->getDiasDeAula() as $oDiaAula) {

      if (!DBDate::dataEstaNoIntervalo($oDiaAula->oDataAula, $oDtInicio, $oDtFim)) {
        $lPermiteAlteracao = false;
      }
    }

    return $lPermiteAlteracao;
  }

  /**
   * Retorna os Cidadaos matriculados no CursoSocial
   * @return multitype:CidadaoMatriculaCursoSocial
   */
  public function getCidadaosMatriculados() {

    if (count($this->aMatriculas) == 0) {

      $oDaoCursoCidadao = new cl_cursosocialcidadao();
      $sWhere           = " as22_cursosocial = {$this->iCodigo}";
      $sCampos          = "ov02_nome, as22_sequencial";
      $sSqlCursoCidadao = $oDaoCursoCidadao->sql_query(null, $sCampos, 'ov02_nome', $sWhere);
      $rsCursoCidadao   = $oDaoCursoCidadao->sql_record($sSqlCursoCidadao);
      $iLinhas          = $oDaoCursoCidadao->numrows;

      if ($iLinhas > 0) {

        for ($i = 0; $i < $iLinhas; $i++) {

          $iMatricula          = db_utils::fieldsMemory($rsCursoCidadao, $i)->as22_sequencial;
          $this->aMatriculas[] = new CidadaoMatriculaCursoSocial($iMatricula);
        }
      }
    }

    return $this->aMatriculas;

  }

  /**
   * Retorna os meses de abrangencia do curso
   * @return multiple:Meses
   */
  public function getMesesDeAbrangencia() {

    if (count($this->aMesesAbrangencia) == 0) {
      $this->aMesesAbrangencia = DBDate::getMesesNoIntervalo($this->oDtInicio, $this->oDtFim);
    }
    return $this->aMesesAbrangencia;
  }
}