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
 * Classe para conrole das informações do docente
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version $Revision: 1.11 $
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

  /**
   * Coleção das atividades do docente
   * @var array
   */
  private $aListaAtividades;

  /**
   * Instância de CGM
   * @var CgmBase|CgmFisico|CgmJuridico
   */
  private $oCgm;

  /**
   * Array de todas as turmas que o docente leciona em todas as escolas
   * @var array Turmas
   */
  private $aTurmas = array();

  /**
   * Número do NIS
   * @var string
   */
  private $sNis = '';

  /**
   * Número do Passaporte
   * @var string
   */
  private $sPassaporte = '';

  /**
   * Sigla da UF da Identidade
   * @var string
   */
  private $sUfIdentidadeSigla = '';

  /**
   * Data de expedição da Identidade
   * @var DBDate
   */
  private $oDataExpedicaoIdentidade = null;

  /**
   * Orgão emissor da Identidade
   * @var string
   */
  private $sOrgaoEmissorIdentidade = '';

  /**
   * Complemento dos documentos do Recurso Humano
   * @var string
   */
  private $sComplemento = '';

  /**
   * Titulo eleitoral
   * @var string
   */
  private $sTituloEleitoral = '';

  /**
   * Zona Eleitoral
   * @var string
   */
  private $sZonaEleitoral = '';

  /**
   * Seção Eleitoral
   * @var string
   */
  private $sSecaoEleitoral = '';

  /**
   * Número do CTPS
   * @var string
   */
  private $sCtps = '';

  /**
   * Número de Série do CTPS
   * @var string
   */
  private $sSerieCtps = '';

  /**
   * Sígla da UF do CTPS
   * @var string
   */
  private $sSiglaUfCtps = '';

  /**
   * Número do PIS/PASEP
   * @var string
   */
  private $sPisPasep = '';

  public function __construct($iCodigoDocente) {

    if (!empty($iCodigoDocente)) {

      $this->iCodigoDocente    = $iCodigoDocente;
      $this->oCgm              = CgmFactory::getInstanceByCgm($iCodigoDocente);
      $oDaoRecHumano           = db_utils::getDao("rechumano");
      $sCamposListaAtividades  = " distinct ed20_i_codigo, ed20_c_nis, ed20_c_passaporte, censoufident.ed260_c_sigla, ";
      $sCamposListaAtividades .= " ed20_d_dataident, ed20_c_identcompl, ed132_c_descr, rh16_titele, rh16_zonael, ";
      $sCamposListaAtividades .= " rh16_secaoe, rh16_ctps_n, rh16_ctps_s, rh16_ctps_uf, rh16_pis ";
      $sWhereListaAtividades   = " (rh01_numcgm = {$iCodigoDocente} or ed285_i_cgm = {$iCodigoDocente})";
      $sSqlListaAtividades     = $oDaoRecHumano->sql_query_escola(null,
                                                               $sCamposListaAtividades,
                                                               null,
                                                               $sWhereListaAtividades
                                                              );
      $rsListaAtividades      = $oDaoRecHumano->sql_record($sSqlListaAtividades);
      $iTotalLinhas           = $oDaoRecHumano->numrows;

      if ($iTotalLinhas > 0) {

        for ($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

          $oDadosListaAtividades = db_utils::fieldsMemory($rsListaAtividades, $iContador);

          $this->aListaAtividades[]       = $oDadosListaAtividades->ed20_i_codigo;
          $this->sNis                     = empty($this->sNis)        ? $oDadosListaAtividades->ed20_c_nis        : $this->sNis;
          $this->sPassaporte              = empty($this->sPassaporte) ? $oDadosListaAtividades->ed20_c_passaporte : $this->sPassaporte;
          $this->sUfIdentidadeSigla       = empty($this->sUfIdentidadeSigla) ? $oDadosListaAtividades->ed260_c_sigla : $this->sUfIdentidadeSigla;
          $this->oDataExpedicaoIdentidade = empty($this->oDataExpedicaoIdentidade) && !empty( $oDadosListaAtividades->ed20_d_dataident ) ? new DBDate($oDadosListaAtividades->ed20_d_dataident) : $this->oDataExpedicaoIdentidade;
          $this->sComplemento             = empty($this->sComplemento) ? $oDadosListaAtividades->ed20_c_identcompl : $this->sComplemento;
          $this->sOrgaoEmissorIdentidade  = empty($this->sOrgaoEmissorIdentidade) ? $oDadosListaAtividades->ed132_c_descr : $this->sOrgaoEmissorIdentidade;
          $this->sTituloEleitoral         = empty($this->sTituloEleitoral) ? $oDadosListaAtividades->rh16_titele : $this->sTituloEleitoral;
          $this->sZonaEleitoral           = empty($this->sZonaEleitoral) ? $oDadosListaAtividades->rh16_zonael : $this->sZonaEleitoral;
          $this->sSecaoEleitoral          = empty($this->sSecaoEleitoral) ? $oDadosListaAtividades->rh16_secaoe : $this->sSecaoEleitoral;
          $this->sCtps                    = empty($this->sCtps) ? $oDadosListaAtividades->rh16_ctps_n : $this->sCtps;
          $this->sSerieCtps               = empty($this->sSerieCtps) ? $oDadosListaAtividades->rh16_ctps_s : $this->sSerieCtps;
          $this->sSiglaUfCtps             = empty($this->sSiglaUfCtps) ? $oDadosListaAtividades->rh16_ctps_uf : $this->sSiglaUfCtps;
          $this->sPisPasep                = empty($this->sPisPasep) ? $oDadosListaAtividades->rh16_pis : $this->sPisPasep;
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
   * @return Disciplina[]
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
            $oDisciplina           = DisciplinaRepository::getDisciplinaByCodigo( $oDadosRelacaoTrabalho->ed12_i_codigo );
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

    if ( isset($this->aTurmas) && count($this->aTurmas) == 0 ) {

      if (count($this->aListaAtividades) > 0) {

        $sListaRecursoHumano    = implode(",", $this->aListaAtividades);
        $oDaoRegenciaHorario    = new cl_regenciahorario();
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
   * @return Turma[]
   */
  public function getTurmasPorEscola(Escola $oEscola) {

    $aTurmaPorEscola = array();
    $this->getTurmas();

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

    $aAtividades = array();

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

  /**
   * Retorna a Identidade do CGM
   * @return string
   */
  public function getIdentidade() {
    return $this->oCgm->getIdentidade();
  }

  /**
   * Retorna o CPF do CGM
   * @return string
   */
  public function getCpf() {
    return $this->oCgm->getCpf();
  }

  /**
   * Retorna o número NIS do Recurso Humano
   * @return string
   */
  public function getNis() {
    return $this->sNis;
  }

  /**
   * Retorna o passaporte do Recurso Humano
   * @return string
   */
  public function getPassaporte() {
    return $this->sPassaporte;
  }

  /**
   * Retorna a sigla da UF da Identidade do Recurso Humano
   * @return string
   */
  public function getUfIdentidadeSigla() {
    return $this->sUfIdentidadeSigla;
  }

  /**
   * Retorna a data de expedição da carteira de identidade do Recurso Humano
   * @return DBDate
   */
  public function getDataExpedicaoIdentidade() {
    return $this->oDataExpedicaoIdentidade;
  }

  /**
   * Retorna o complemento do Recurso Humano
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * Retorna o orgão emissor da identidade
   * @return string
   */
  public function getOrgaoEmissorIdentidade() {
    return $this->sOrgaoEmissorIdentidade;
  }

  /**
   * Retorna o Titulo Eleitoral
   * @return string
   */
  public function getTituloEleitoral() {
    return $this->sTituloEleitoral;
  }

  /**
   * Retorna a Zona Eleitoral
   * @return string
   */
  public function getZonaEleitoral() {
    return $this->sZonaEleitoral;
  }

  /**
   * Retorna a Seção Eleitoral
   * @return string
   */
  public function getSecaoEleitoral() {
    return $this->sSecaoEleitoral;
  }

  /**
   * Retorna a CTPS
   * @return string
   */
  public function getCtps() {
    return $this->sCtps;
  }

  /**
   * Retorna a Serie da CTPS
   * @return string
   */
  public function getSerieCtps() {
    return $this->sSerieCtps;
  }

  /**
   * Retorna a UF da CTPS
   * @return string
   */
  public function getSiglaUfCtps() {
    return $this->sSiglaUfCtps;
  }

  /**
   * Retorna PIS/PASEP
   * @return string
   */
  public function getPisPasep() {
    return $this->sPisPasep; 
  }

  /**
   * Retorna a instância de CGM
   * @return CgmBase|CgmFisico|CgmJuridico
   */
  public function getCgm() {
    return $this->oCgm;
  }
}