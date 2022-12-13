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
 * Dados do historico do aluno
 * @author Iuri Guntchnigg
 * @package Educacao
 */
class HistoricoAluno {

  /**
   * aluno do Historico
   * @var Aluno
   */
  protected $oAluno;

  /**
   * Codigo do Historico
   * @fieldName ed61_i_historico
   * @var integer
   */
  protected $iCodigoHistorico;

  /**
   * Ano de Conclusao do Curso
   * @var integer
   */
  protected $iAnoConclusao;

  /**
   * Escola que incluiu o historico
   * @var Escola
   */
  protected $oEscola;

  /**
   * Curso do Historico
   * @var integer
   */
  protected $iCurso;

  /**
   * Curso ho historico
   * @var Curso
   */
  protected $oCurso;
  /**
   * Observaçoes do lancamento do historico
   * @var string
   */
  protected $sObervacao;
  /**
   * Etapas cursadas pelo aluno
   * @var Array
   */
  protected $aEtapasCursadas = array();
  /**
   * Método Construtor.
   * @param integer $iCodigoHistorico Código do Historico
   */
  protected $oMementoHistorico = null;

  public function __construct($iCodigoHistorico = null) {

    if (!empty($iCodigoHistorico)) {

      $oDaoHistorico      = new cl_historico;
      $sSqlDadosHistorico = $oDaoHistorico->sql_query_file($iCodigoHistorico);
      $rsDadosHistorico   = $oDaoHistorico->Sql_record($sSqlDadosHistorico);
      if ($oDaoHistorico->numrows > 0) {

        $oDadosHistorico         = db_utils::fieldsMemory($rsDadosHistorico, 0);
        $this->oMementoHistorico = $oDadosHistorico;
        $this->setAnoConclusao($oDadosHistorico->ed61_i_anoconc);
        $this->setCurso($oDadosHistorico->ed61_i_curso);
        $this->iCodigoHistorico = $oDadosHistorico->ed61_i_codigo;
        $this->sObervacao       = $oDadosHistorico->ed61_t_obs;
        $this->oEscola          = EscolaRepository::getEscolaByCodigo($oDadosHistorico->ed61_i_escola);
        $this->oAluno           = AlunoRepository::getAlunoByCodigo($oDadosHistorico->ed61_i_aluno);
      }
    }
  }

  /**
   * Retorna o ano de conclusao do curso
   * @return integer
   */
  public function getAnoConclusao() {
    return $this->iAnoConclusao;
  }

  /**
   * Define o Ano de Conclusao do curso
   * @param integer $iAnoConclusao
   */
  public function setAnoConclusao($iAnoConclusao) {
    $this->iAnoConclusao = $iAnoConclusao;
  }

  /**
   * Retorna o código do Historico
   * @return integer
   */
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * Retorna o curso do historico
   * @deprecated;
   * @return integer
   */
  public function getCurso() {
    return $this->iCurso;
  }

  /**
   * Define o curso do Historico
   * @param integer $iCurso
   */
  public function setCurso($iCurso) {
    $this->iCurso = $iCurso;
  }

  /**
   * Retorna o Aluno do Historico
   * @return Aluno
   */
  public function getAluno() {

    if (empty($this->oAluno)) {
      $this->oAluno = AlunoRepository::getAlunoByCodigo($this->oMementoHistorico->ed65_i_aluno);
    }
    return $this->oAluno;
  }

  /**
   * Define o Aluno do Historico
   * @param Aluno $oAluno instancia de Aluno
   */
  public function setAluno($oAluno) {
    $this->oAluno = $oAluno;
  }

  /**
   * Retorna a Escola do historico
   * @return Escola
   */
  public function getEscola() {

    if (empty($this->oEscola) && !empty($this->oMementoHistorico->ed65_i_escola)) {

      $this->oEscola = new Escola($this->oMementoHistorico->ed65_i_escola);
    } elseif (empty($this->oEscola) && empty($this->oMementoHistorico->ed65_i_escola)) {

      $this->setEscola(EscolaRepository::getEscolaByCodigo(db_getsession('DB_coddepto')));
    }
    return $this->oEscola;
  }

  /**
   * Escola responsavel pela inclusão do Histórico
   * @param Escola $oEscola
   */
  public function setEscola(Escola $oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * define as observações lancadas para o historico.
   * @param string $sObservacao observacoes do historico;
   */
  public function setObservacoes($sObservacao) {
    $this->sObervacao = $sObservacao;
  }

  /**
   * Retorna as obervações do historico
   * @return string
   */
  public function getObservacoes() {
    return $this->sObervacao;
  }

  /**
   * Salvar os dados do Historico
   * @throws ParameterException, BussinessException
   */
  public function salvar() {

    if ($this->getEscola() == "" || !($this->oEscola instanceof Escola)) {
      throw new ParameterException('Escola de lançamento do historico não é uma escola válida.');
    }

    if (empty($this->oAluno) || !($this->oAluno instanceof Aluno)) {
      throw new ParameterException('Aluno informado não Existe.');
    }

    $oDaoHistorico                     = new cl_historico();
    $oDaoHistorico->ed61_i_anoconc     = $this->getAnoConclusao();
    $oDaoHistorico->ed61_i_periodoconc = $this->getAnoConclusao();
    $oDaoHistorico->ed61_i_curso       = $this->getCurso();
    $oDaoHistorico->ed61_i_escola      = $this->getEscola()->getCodigo();
    $oDaoHistorico->ed61_t_obs         = pg_escape_string( $this->getObservacoes() );

    if (empty($this->iCodigoHistorico)) {

      $oDaoHistorico->ed61_i_aluno  = $this->oAluno->getCodigoAluno();
      $oDaoHistorico->incluir(null);
      $this->iCodigoHistorico = $oDaoHistorico->ed61_i_codigo;
    } else {

      $oDaoHistorico->ed61_i_codigo = $this->getCodigoHistorico();
      $oDaoHistorico->alterar($oDaoHistorico->ed61_i_codigo);
    }

    if ($oDaoHistorico->erro_status == 0) {
      throw new BusinessException("Erro ao salvar dados do historico.");
    }

    /**
     * Salvamos todos os dados da Etapa
     */
    foreach ($this->getEtapas() as $oEtapa) {
      $oEtapa->salvar($this->iCodigoHistorico);
    }
  }

  /**
   * Adiciona uma etapa ao historico
   * @param HistoricoEtapa Etapa cursada
   */
  public function adicionarEtapa(HistoricoEtapa $oEtapa) {
    $this->aEtapasCursadas[] = $oEtapa;
  }

  /**
   * Retorna as etapas presentes no historico
   * TODO implementar lazy loading
   * @return HistoricoEtapaForaRede[]|HistoricoEtapaRede Colecao de Etapa
   */
  public function getEtapas() {

    if (count($this->aEtapasCursadas) == 0) {

      $oDaoHistorico = new cl_historico;
      $sSqlEtapas    = $oDaoHistorico->sql_query_etapas_historico($this->getCodigoHistorico(),
                                                                  "*",
                                                                  "ano"
                                                                 );

      $rsEtapas     = $oDaoHistorico->sql_record($sSqlEtapas);
      if ($oDaoHistorico->numrows > 0) {

        for ($iEtapa = 0; $iEtapa < $oDaoHistorico->numrows; $iEtapa++) {

          $oDadosEtapa = db_utils::fieldsMemory($rsEtapas, $iEtapa);

          switch ($oDadosEtapa->tipo) {

            case HistoricoEtapa::ETAPA_REDE://1:

              $oEtapa = new HistoricoEtapaRede($oDadosEtapa->codigo);
              break;

            case HistoricoEtapa::ETAPA_FORA_REDE: //2:
              $oEtapa = new HistoricoEtapaForaRede($oDadosEtapa->codigo);
              break;
          }
          $this->aEtapasCursadas[] = $oEtapa;
          unset($oDadosEtapa);
        }
      }
    }
    return $this->aEtapasCursadas;
  }

  /**
   * Retorna a etapa cursada pelo codigo
   * @deprecated
   *
   * @param integer $iCodigoEtapa
   * @param integer $iTipoEtapa Tipo da etapa 1 para etapas na rede, 2 para etapas fora da rede
   * @return HistoricoEtapa
   */
  public function getEtapaDeCodigo($iCodigoEtapa, $iTipoEtapa) {

    $aEtapas       = $this->getEtapas();
    $oEtapaRetorno = false;
    $sClasse       = '';
    switch ($iTipoEtapa) {

      case HistoricoEtapa::ETAPA_REDE://1:
        $sClasse = "HistoricoEtapaRede";
      break;

      case HistoricoEtapa::ETAPA_FORA_REDE: //2:
        $sClasse = "HistoricoEtapaForaRede";
      break;
    }
    foreach ($aEtapas as $oEtapa) {

      if ($oEtapa->getCodigoEtapa() == $iCodigoEtapa && ($oEtapa instanceof $sClasse)) {

        $oEtapaRetorno = $oEtapa;
        break;
      }
    }
    return $oEtapaRetorno;
  }

  /**
   * Retorna a etapa cursada pelo ano.
   * @param Etapa $oEtapa
   * @param integer $iAno
   * @param integer $iTipoEtapa
   * @return HistoricoEtapa
   */
  public function getEtapaDoAno(Etapa $oEtapa, $iAno, $iTipoEtapa = 1) {

    $aEtapas       = $this->getEtapas();
    $oEtapaRetorno = null;
    $sClasse       = 'HistoricoEtapaRede';

    switch ($iTipoEtapa) {

    	case HistoricoEtapa::ETAPA_REDE://1:
    	  $sClasse = "HistoricoEtapaRede";
    	  break;

    	case HistoricoEtapa::ETAPA_FORA_REDE: //2:
    	  $sClasse = "HistoricoEtapaForaRede";
    	  break;
    }

    foreach ($aEtapas as $oEtapa) {

      if ($oEtapa->getAnoCurso() == $iAno && ($oEtapa instanceof $sClasse)) {

        $oEtapaRetorno = $oEtapa;
        break;
      }
    }
    return $oEtapaRetorno;
  }

  /**
   * Retorna o curso do histórico
   * @return Curso
   */
  public function getCursoHistorico() {

    if (empty($this->oCurso) && !empty($this->iCurso)) {
      $this->oCurso = CursoRepository::getByCodigo($this->iCurso);
    }
    return $this->oCurso;
  }

  /**
   * Retorna a ultima etapa cursada
   * @return HistoricoEtapaRede|HistoricoEtapaForaRede
   */
  public function getUltimaEtapaCursada() {

    return HistoricoEtapa::getUltimaEtapaAluno($this->oAluno);
  }
}