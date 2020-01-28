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
 * Classe para conrole das informações do docente
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version $Revision: 1.5 $
 */
class Docente {

  /**
   * Código do docente
   * @var integer
   */
  private $iCodigoDocente;

  /**
   * Nome do docente
   * @var string
   */
  private $sNome;

  /**
   * Array com as disciplinas do docente
   * @var array Disciplinas
   */
  private $aDisciplinas;


  private $aListaAtividades;

  private $oCgm;
  /**
   * Array de todas as turmas que o docente leciona em todas as escolas
   * @var array Turmas
   */
  private $aTurmas;

  public function __construct($iCodigoDocente) {

    if (!empty($iCodigoDocente)) {

      $this->iCodigoDocente = $iCodigoDocente;
      $this->oCgm           = CgmFactory::getInstanceByCgm($iCodigoDocente);
      $oDaoRecHumano        = db_utils::getDao("rechumano");
      $sWhereListaAtividades = " (rh01_numcgm = {$iCodigoDocente} or ed285_i_cgm = {$iCodigoDocente})";
      $sSqlListaAtividades  = $oDaoRecHumano->sql_query_escola(null,
                                                               "distinct ed20_i_codigo",
                                                               null,
                                                               $sWhereListaAtividades
                                                              );
      $rsListaAtividades    = $oDaoRecHumano->sql_record($sSqlListaAtividades);
      $iTotalLinhas         = $oDaoRecHumano->numrows;

      if ($iTotalLinhas > 0) {

        for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

          $oDadosListaAtividades = db_utils::fieldsMemory($rsListaAtividades, $iContador);
          $this->aListaAtividades[] = $oDadosListaAtividades->ed20_i_codigo;
        }
      }
    }
  }

  /**
   * Retorna o código do docente
   * @return integer
   */
  public function getCodigoDocente() {
    return $this->iCodigoDocente;
  }

  /**
   * Retorna o nome do docente
   * @return string
   */
  public function getNome() {
    return $this->oCgm->getNome();
  }

  /**
   * Retorna um array de disciplinas do docente
   * @param integer $iCodigoRecHumano
   * @return array Disciplina
   */
  public function getDisciplinas() {

    if (!isset($this->aDisciplinas) && count($this->aDisciplinas) == 0) {

      if (count($this->aListaAtividades) > 0) {

        $sListaRecursoHumano    = implode(",", $this->aListaAtividades);
        $oDaoRelacaoTrabalho    = db_utils::getDao("relacaotrabalho");
        $sCamposRelacaoTrabalho = " distinct ed12_i_codigo";
        $sWhereRelacaoTrabalho  = " ed75_i_rechumano in({$sListaRecursoHumano}) ";
        $sOrderRelacaoTrabalho  = " ed12_i_codigo ";
        $sSqlRelacaoTrabalho    = $oDaoRelacaoTrabalho->sql_query(null,
                                                                  $sCamposRelacaoTrabalho,
                                                                  $sOrderRelacaoTrabalho,
                                                                  $sWhereRelacaoTrabalho);
        $rsRelacaoTrabalho      = $oDaoRelacaoTrabalho->sql_record($sSqlRelacaoTrabalho);
        $iTotalLinhas           = $oDaoRelacaoTrabalho->numrows;

        if ($iTotalLinhas > 0) {

          for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

            $oDadosRelacaoTrabalho = db_utils::fieldsMemory($rsRelacaoTrabalho, $iContador);
            $oDisciplina           = new Disciplina($oDadosRelacaoTrabalho->ed12_i_codigo);
            $this->aDisciplinas[]  = $oDisciplina;
          }
          unset($oDisciplina);
        }
      }
    }
    return $this->aDisciplinas;
  }

  /**
   * Retorna as disciplinas que o docente leciona por turma
   * @param Turma $oTurma
   * @return array Disciplina
   */
  public function getDisciplinasPorTurma(Turma $oTurma) {

    $aDisciplinasPorTurma = array();
    foreach ($this->getDisciplinas() as $oDisciplinas) {

      foreach ($oTurma->getDisciplinas() as $oRegencia) {

        if ($oDisciplinas->getCodigoDisciplina() == $oRegencia->getDisciplina()->getCodigoDisciplina()) {

          foreach ($oRegencia->getDocentes() as $oDocentes) {

            if ($oDocentes->getCodigoDocente() == $this->getCodigoDocente()) {
              $aDisciplinasPorTurma[] = $oRegencia->getDisciplina();
            }
          }
        }
      }
    }
    return $aDisciplinasPorTurma;
  }

  /**
   * Retorna todas as turmas que o docente leciona, de todas as escolas
   * @return Turma Colecao de Turmas
   */
  public function getTurmas() {

    if (!isset($this->aTurmas) && count($this->aTurmas) == 0) {

      if (count($this->aListaAtividades) > 0) {

        $sListaRecursoHumano    = implode(",", $this->aListaAtividades);
        $oDaoRegenciaHorario    = db_utils::getDao("regenciahorario");
        $sCamposRegenciaHorario = " DISTINCT ed57_i_codigo ";
        $sWhereRegenciaHorario  = " ed58_i_rechumano in({$sListaRecursoHumano}) and ed58_ativo is true ";
        $sOrderRegenciaHorario  = " ed57_i_codigo ";
        $sSqlRegenciaHorario    = $oDaoRegenciaHorario->sql_query(null,
                                                                  $sCamposRegenciaHorario,
                                                                  $sOrderRegenciaHorario,
                                                                  $sWhereRegenciaHorario);
        $rsRegenciaHorario      = $oDaoRegenciaHorario->sql_record($sSqlRegenciaHorario);
        $iTotalLinhas           = $oDaoRegenciaHorario->numrows;

        if ($iTotalLinhas > 0) {

          for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

            $oDadosRegenciaHorario = db_utils::fieldsMemory($rsRegenciaHorario, $iContador);
            $oTurma                = TurmaRepository::getTurmaByCodigo($oDadosRegenciaHorario->ed57_i_codigo);
            $this->aTurmas[]       = $oTurma;
          }
        }
      }
    }
    return $this->aTurmas;
  }

  /**
   * Retorna as turmas que o docente leciona por escola
   * @param Escola $oEscola
   * @return array Turma
   */
  public function getTurmasPorEscola(Escola $oEscola) {

    $aTurmaPorEscola = array();
    foreach ($this->aTurmas as $oTurma) {

      if ($oTurma->getEscola()->getCodigo() == $oEscola->getCodigo()) {
        $aTurmaPorEscola[] = $oTurma;
      }
    }
    return $aTurmaPorEscola;
  }

  /**
   * verifica se o docente Leciona a Regencia
   * @return boolean
   */
  public function lecionaRegencia(Regencia $oRegencia) {

    $aDocentesRegencia = $oRegencia->getDocentes();
    foreach ($aDocentesRegencia as $oDocenteRegencia) {
      if ($oDocenteRegencia->getCodigoDocente() == $this->getCodigoDocente()) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Retorna um array com as atividades do docente em uma escola
   * @param Escola $oEscola
   * @return array
   */
  public function getAtividades(Escola $oEscola) {
    
    if (count($this->aListaAtividades) > 0) {
      
      $aAtividades         = array(); 
      $sListaRecursoHumano = implode(",", $this->aListaAtividades);
      $oDaoRecHumanoAtiv   = db_utils::getDao("rechumanoativ");
      $sWhereRecHumanoAtiv = "ed75_i_rechumano in ({$sListaRecursoHumano}) AND ed75_i_escola = {$oEscola->getCodigo()}";
      $sSqlRecHumanoAtiv   = $oDaoRecHumanoAtiv->sql_query(null, "ed22_i_codigo", null, $sWhereRecHumanoAtiv);
      $rsRecHumanoAtiv     = $oDaoRecHumanoAtiv->sql_record($sSqlRecHumanoAtiv);
      $iTotalRecHumanoAtiv = $oDaoRecHumanoAtiv->numrows;
      
      if ($iTotalRecHumanoAtiv > 0) {
        
        for ($iContador = 0; $iContador < $iTotalRecHumanoAtiv; $iContador++) {
          
          $iRecHumanoAtiv    = db_utils::fieldsMemory($rsRecHumanoAtiv, $iContador)->ed22_i_codigo;
          $oDocenteAtividade = new DocenteAtividade($iRecHumanoAtiv);
          $aAtividades[]     = $oDocenteAtividade; 
        }
      }
    }
    return $aAtividades;
  }
}
?>