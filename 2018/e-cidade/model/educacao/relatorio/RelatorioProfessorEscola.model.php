<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


abstract class RelatorioProfessorEscola {

  const MSG_RELATORIOPROFESSORESCOLA = "educacao.escola.RelatorioProfessorEscola.";

  protected $sNomeRelatorio = "RELATÓRIO DE PROFESSORES POR ESCOLA";

  const POR_REGIME_TRABALHO  = 1;
  const POR_TIPO_HORA        = 2;

  protected $iTipoTotalizador;
  protected $lMostrarDisciplinas  = false;
  protected $aTotalizadorRegime   = array();
  protected $aTotalizadorTipoHora = array();

  protected $iMargimTopBottom = 10;
  protected $iAlturaLinha     = 4;

  /**
   * Escola que esta imprimindo o relatorio
   * valor = 0 siginifica impressão de todas escolas
   * @var integer
   */
  protected $iEscola   = 0;

  /**
   * instancia de FPDF
   * @var FPDF
   */
  protected $oPdf;


  protected $aDados  = array();
  static    $aTurnos = array(1 => "Manhã", 2 => "Tarde", 3 => "Noite");

  public function __construct( FPDF $oPdf, $iEscola, $iAreaTrabalho = 0, $iTipoHora = 0 ) {

    $this->iEscola = $iEscola;
    $this->oPdf    = $oPdf;

    $aWhere   = array();
    $aWhere[] = " ed75_i_saidaescola is null ";
    $aWhere[] = " ed01_c_docencia = 'S' ";

    if (!empty($iEscola)) {
      $aWhere[] = " ed75_i_escola = $iEscola ";
    }

    if (!empty($iAreaTrabalho)) {
      $aWhere[] = " ed23_i_areatrabalho = $iAreaTrabalho ";
    }

    if (!empty($iTipoHora)) {
      $aWhere[] = " ed129_tipohoratrabalho = $iTipoHora ";
    }

    $sCamposAlias  = " ,ed22_i_codigo         as codigo_atividade_professor  ";
    $sCamposAlias .= " ,trim(ed18_c_nome)     as escola                      ";
    $sCamposAlias .= " ,trim(ed18_c_abrev)    as escola_abrev                ";
    $sCamposAlias .= " ,ed75_i_codigo         as codigo_professor_escola     ";
    $sCamposAlias .= " ,ed22_i_atividade      as codigo_atividade            ";
    $sCamposAlias .= " ,trim(ed24_c_descr)    as regime_trabalho             ";
    $sCamposAlias .= " ,trim(ed25_c_descr)    as area_trabalho               ";
    $sCamposAlias .= " ,ed128_descricao       as tipo_hora_trabalho          ";

    $sCampos  = "  ed75_i_escola          ";
    $sCampos .= " ,ed75_d_ingresso        ";
    $sCampos .= " ,ed20_i_codigo          ";
    $sCampos .= " ,ed20_i_tiposervidor    ";
    $sCampos .= " ,ed23_i_regimetrabalho  ";
    $sCampos .= " ,ed23_i_areatrabalho    ";
    $sCampos .= " ,ed129_tipohoratrabalho ";
    $sCampos .= " ,ed129_turno            ";
    $sCampos .= " ,ed129_horainicio       ";
    $sCampos .= " ,ed129_horafim          ";

    $sGroup  = $sCampos;
    $sGroup .= " ,codigo_atividade_professor ";
    $sGroup .= " ,escola                     ";
    $sGroup .= " ,escola_abrev               ";
    $sGroup .= " ,codigo_professor_escola    ";
    $sGroup .= " ,codigo_atividade           ";
    $sGroup .= " ,regime_trabalho            ";
    $sGroup .= " ,area_trabalho              ";
    $sGroup .= " ,tipo_hora_trabalho         ";

    $sSql  = " select {$sCampos} ";
    $sSql .= "        {$sCamposAlias} ";
    $sSql .= "        ,array_to_string(array_accum( distinct ed23_i_disciplina), ', ') as disciplinas   ";
    $sSql .= "        ,array_to_string(array_accum( distinct ed129_diasemana), ', ')   as dias_semana   ";
    $sSql .= "   from rechumanoescola ";
    $sSql .= "  inner join escola            on ed18_i_codigo          = ed75_i_escola ";
    $sSql .= "  inner join rechumano         on ed20_i_codigo          = ed75_i_rechumano ";
    $sSql .= "  inner join rechumanoativ     on ed22_i_rechumanoescola = ed75_i_codigo ";
    $sSql .= "  inner join agendaatividade   on ed129_rechumanoativ    = ed22_i_codigo ";
    $sSql .= "  inner join tipohoratrabalho  on ed128_codigo           = ed129_tipohoratrabalho ";
    $sSql .= "  inner join diasemana         on ed32_i_codigo          = ed129_diasemana ";
    $sSql .= "  inner join atividaderh       on ed01_i_codigo          = ed22_i_atividade ";
    $sSql .= "  inner join relacaotrabalho   on ed23_i_rechumanoescola = ed75_i_codigo ";
    $sSql .= "                              and ed23_tipohoratrabalho  = ed129_tipohoratrabalho ";
    $sSql .= "  inner join regimetrabalho    on ed24_i_codigo          = ed23_i_regimetrabalho ";
    $sSql .= "  inner join areatrabalho      on ed25_i_codigo          = ed23_i_areatrabalho ";
    $sSql .= "  inner join disciplina        on ed12_i_codigo          = ed23_i_disciplina ";
    $sSql .= "  inner join caddisciplina     on ed232_i_codigo         = ed232_i_codigo ";

    $sSql .= " where " . implode(" and ", $aWhere );
    $sSql .= " group by $sGroup ";
    $sSql .= "  order by ed75_i_escola, ed23_i_areatrabalho, ed129_tipohoratrabalho ";
    $rs    = db_query($sSql);

    $oMsgErro = new stdClass();
    if (!$rs) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_RELATORIOPROFESSORESCOLA . "erro_buscar_dados", $oMsgErro) );
    }

    if (pg_num_rows($rs) == 0) {
      throw new Exception( _M(self::MSG_RELATORIOPROFESSORESCOLA . "sem_registros_para_filtro_selecionado") );
    }

    $iLinhas = pg_num_rows($rs);
    for ($i = 0; $i < $iLinhas; $i++) {

      $oDados = db_utils::fieldsMemory($rs, $i);

      $iEscola = $oDados->ed75_i_escola;
      if ( !array_key_exists($iEscola, $this->aDados) ) {

        $oEscola               = new stdClass();
        $oEscola->iCodigo      = $oDados->ed75_i_escola;
        $oEscola->sNome        = $oDados->escola;
        $oEscola->sNomeAbrev   = $oDados->escola_abrev;
        $oEscola->aProfessores = array();

        $this->aDados[$iEscola] = $oEscola;
      }

      $iProfEscola  = $oDados->codigo_professor_escola;
      $aProfessores = $this->aDados[$iEscola]->aProfessores;

      if ( !array_key_exists($iProfEscola, $aProfessores) ) {

        $oProfessor                 = new stdClass();
        $oProfessor->iCodigo        = $oDados->codigo_professor_escola;
        $oProfessor->sNome          = $this->getNomeProfessor($oDados->ed20_i_tiposervidor, $oDados->ed20_i_codigo);
        $oProfessor->dtIngresso     = new DBDate( $oDados->ed75_d_ingresso );
        $oProfessor->aAreaTrabalho  = array();

        $this->aDados[$iEscola]->aProfessores[$iProfEscola] = $oProfessor;
      }

      $aAreaRegime     = $this->aDados[$iEscola]->aProfessores[$iProfEscola]->aAreaTrabalho;
      $sHashAreaRegime = "{$oDados->ed23_i_areatrabalho}#{$oDados->ed23_i_regimetrabalho}" ;

      if ( !array_key_exists($sHashAreaRegime, $aAreaRegime) ) {

        $oAreaTrabalhoRegime                = new stdClass();
        $oAreaTrabalhoRegime->iAreaTrabalho = $oDados->ed23_i_areatrabalho;
        $oAreaTrabalhoRegime->sAreaTrabalho = $oDados->area_trabalho;
        $oAreaTrabalhoRegime->iRegime       = $oDados->ed23_i_regimetrabalho;
        $oAreaTrabalhoRegime->sRegime       = $oDados->regime_trabalho;
        $oAreaTrabalhoRegime->aDisciplinas  = explode(", ", $oDados->disciplinas);
        $oAreaTrabalhoRegime->aTipoHora     = array();

        $this->aDados[$iEscola]->aProfessores[$iProfEscola]->aAreaTrabalho[$sHashAreaRegime] = $oAreaTrabalhoRegime;
      }

      /**
       * Atenção, logo a baixo eu peguei a referencia do array.
       * qualquer coisa que afetar $aTipoHora irá afetar $this->aDados
       */
      $aTipoHora = &$this->aDados[$iEscola]->aProfessores[$iProfEscola]->aAreaTrabalho[$sHashAreaRegime]->aTipoHora;
      $iTipoHora = $oDados->ed129_tipohoratrabalho . "#" . $oDados->ed129_turno;

      if ( !array_key_exists($iTipoHora, $aTipoHora) ) {

        $oTipoHora               = new stdClass();
        $oTipoHora->iCodigo      = $oDados->ed129_tipohoratrabalho;
        $oTipoHora->sDescricao   = $oDados->tipo_hora_trabalho;
        $oTipoHora->iTurno       = $oDados->ed129_turno;
        $oTipoHora->sTurno       = Turno::getDescricaoTurno($oDados->ed129_turno);
        $oTipoHora->lRegente     = $this->isRegente($oDados->codigo_professor_escola, $oDados->ed129_turno, $oDados->ed129_tipohoratrabalho);
        $oTipoHora->aHoraDia     = array();
        $aTipoHora[$iTipoHora]   = $oTipoHora;

      }

      $sHashDias    = str_replace(", ", "|", $oDados->dias_semana);
      $sHashHoraDia = "$iTipoHora#{$sHashDias}";

      if ( !array_key_exists($sHashHoraDia, $aTipoHora[$iTipoHora]->aHoraDia) ) {

        $oHoraDia              = new stdClass();
        $oHoraDia->sHoraInicio = $oDados->ed129_horainicio;
        $oHoraDia->sHoraFim    = $oDados->ed129_horafim;
        $oHoraDia->aDias       = array();
        foreach (explode(", ", $oDados->dias_semana) as $iDia) {
          $oHoraDia->aDias[] = DBDate::getLabelDiaSemana($iDia - 1);
        }

        $aTipoHora[$iTipoHora]->aHoraDia[$sHashHoraDia] = $oHoraDia;
      }
    }
  }

  /**
   * Se deve mostrar as disciplinas
   * @param [type] $lMostrarDisciplinas [description]
   */
  public function setMostrarDisciplinas($lMostrarDisciplinas) {
    $this->lMostrarDisciplinas = $lMostrarDisciplinas;
  }

  /**
   * Calcula o número de vezes que um regime foi impresso agrupando por escola
   * @return aTotalizadorRegime[]
   */
  private function calcularTotalizadorPorRegime() {

    if ( count($this->aTotalizadorRegime) == 0 ) {

      foreach ($this->aDados as $oEscola) {

        $this->aTotalizadorRegime[$oEscola->iCodigo] = array();

        foreach ($oEscola->aProfessores as $oProfessor) {

          foreach ($oProfessor->aAreaTrabalho as $oAreaTrabalhoRegime) {

            if ( !array_key_exists($oAreaTrabalhoRegime->iRegime, $this->aTotalizadorRegime[$oEscola->iCodigo]) ) {

              $oRegime                   = new stdClass();
              $oRegime->sEscola          = $oEscola->sNome;
              $oRegime->sEscolaAbreviado = $oEscola->sNomeAbrev;
              $oRegime->sRegime          = $oAreaTrabalhoRegime->sRegime;
              $oRegime->iTotal           = 0;
              $this->aTotalizadorRegime[$oEscola->iCodigo][$oAreaTrabalhoRegime->iRegime] = $oRegime;
            }
            $this->aTotalizadorRegime[$oEscola->iCodigo][$oAreaTrabalhoRegime->iRegime]->iTotal += 1;
          }
        }
      }
    }
    return $this->aTotalizadorRegime;
  }


  /**
   * Faz um somatório por escola dos tipos de hora
   * @return aTotalizadorTipoHora[]
   */
  private function calcularTotalizadorTipoHora() {

    if ( count($this->aTotalizadorTipoHora) == 0 ) {

      foreach ($this->aDados as $oEscola) {

        $this->aTotalizadorTipoHora[$oEscola->iCodigo] = array();

        foreach ($oEscola->aProfessores as $oProfessor) {

          foreach ($oProfessor->aAreaTrabalho as $sIndex => $oAreaTrabalhoRegime) {


            foreach ($oAreaTrabalhoRegime->aTipoHora as $iCodigoTipoHora => $oTipohora) {

              if (!array_key_exists($oTipohora->iCodigo, $this->aTotalizadorTipoHora[$oEscola->iCodigo]) ) {

                $oDadosTipoHora             = new stdClass;
                $oDadosTipoHora->iCodigo    = $oTipohora->iCodigo;
                $oDadosTipoHora->sDescricao = $oTipohora->sDescricao;
                $oDadosTipoHora->iTotal    += 0;
                $this->aTotalizadorTipoHora[$oEscola->iCodigo][$oTipohora->iCodigo] = $oDadosTipoHora;
              }
              $this->aTotalizadorTipoHora[$oEscola->iCodigo][$oTipohora->iCodigo]->iTotal += 1;
              // $this->aTotalizadorTipoHora[$oEscola->iCodigo][$iCodigoTipoHora]->iTotal += count($oTipohora->aHoraDia);
            }
          }
        }
      }
    }

    return $this->aTotalizadorTipoHora;
  }

  /**
   * Cria a tabela do totalizador dos regimes por escola
   * @param integer $iEscola código da Escola. Se $iEscola = 0 imprimir de todas escolas
   */
  private function totalizarPorRegime( $iEscola ) {

    $this->calcularTotalizadorPorRegime();

    if ( $iEscola != 0 ) {

      foreach ($this->aTotalizadorRegime as $iCodigoEscola => $aRegime) {

        if ($iCodigoEscola != $iEscola ) {
          continue;
        }

        $this->validaQuebraPagina(count($aRegime));
        $this->imprimeCabecalhoTotalizador("Regimes de Trabalho");
        foreach ($aRegime as $oRegime) {

          $this->oPdf->cell(171, 4, $oRegime->sRegime, 1, 0, 'L');
          $this->oPdf->cell( 20, 4, $oRegime->iTotal,  1, 1, 'C');
        }
      }
    } else {

      $aSomatorio = array();

      foreach ($this->aTotalizadorRegime as $iCodigoEscola => $aRegime) {

        foreach ($aRegime as $key => $oRegime) {

          if ( !array_key_exists($key, $aSomatorio) ) {

            $oSomatorio             = new stdClass();
            $oSomatorio->sDescricao = $oRegime->sRegime;
            $oSomatorio->iTotal     = 0;
            $aSomatorio[$key]       = $oSomatorio;
          }
          $aSomatorio[$key]->iTotal += $oRegime->iTotal;
        }
      }

      $this->validaQuebraPagina(count($aSomatorio));
      $this->imprimeCabecalhoTotalizador("Regimes de Trabalho Geral");
      foreach ($aSomatorio as $aSomatorio) {

        $this->oPdf->cell(171, 4, $aSomatorio->sDescricao, 1, 0, 'L');
        $this->oPdf->cell( 20, 4, $aSomatorio->iTotal,     1, 1, 'C');
      }
    }
  }

  /**
   * Cria a tabela do totalizador dos regimes por escola
   * @param integer $iEscola código da Escola.
   */
  private function totalizarPorTipoHora( $iEscola ) {

    $this->calcularTotalizadorTipoHora();

    if ( $iEscola != 0 ) {

      foreach ($this->aTotalizadorTipoHora as $iCodigoEscola => $aTipoHora) {

        if ($iCodigoEscola != $iEscola ) {
          continue;
        }

        $iLinhasTotalizador = count($aTipoHora);
        $this->validaQuebraPagina($iLinhasTotalizador);
        $this->imprimeCabecalhoTotalizador("Tipo de Hora de Trabalho");
        foreach ($aTipoHora as $oTotalTipoHora) {

          $this->oPdf->cell(171, 4, $oTotalTipoHora->sDescricao, 1, 0, 'L');
          $this->oPdf->cell( 20, 4, $oTotalTipoHora->iTotal,     1, 1, 'C');
        }
      }
    } else {

      $aSomatorio = array();
      foreach ($this->aTotalizadorTipoHora as $iCodigoEscola => $aTipoHora) {

        foreach ($aTipoHora as $oTotalTipoHora) {

          $sKey = trim($oTotalTipoHora->sDescricao);
          if ( !array_key_exists($sKey, $aSomatorio) ) {

            $oSomatorio             = new stdClass();
            $oSomatorio->sDescricao = $oTotalTipoHora->sDescricao;
            $oSomatorio->iTotal     = 0;
            $aSomatorio[$sKey]      = $oSomatorio;
          }
          $aSomatorio[$sKey]->iTotal += $oTotalTipoHora->iTotal;
        }
      }


      $this->validaQuebraPagina(count($aSomatorio));
      $this->imprimeCabecalhoTotalizador("Tipo de Hora de Trabalho Geral");
      foreach ($aSomatorio as $aSomatorio) {

        $this->oPdf->cell(171, 4, $aSomatorio->sDescricao, 1, 0, 'L');
        $this->oPdf->cell( 20, 4, $aSomatorio->iTotal,     1, 1, 'C');
      }
    }
  }

  /**
   * Valida se deve haver quebra de pagina, se sim já quebra a pagina
   * @param  integer $iLinhasTotalizador numero de linhas que ainda deseja imprimir
   * @return booleam
   */
  protected function validaQuebraPagina($iLinhasTotalizador) {

    $iAlturaLinhas = $this->iAlturaLinha * ($iLinhasTotalizador + 1);

    if ( ($this->oPdf->getY() + $iAlturaLinhas) > ($this->oPdf->h - $this->iMargimTopBottom) ) {

      $this->oPdf->addPage();
      return true;
    }
    return false;

  }

  /**
   * Cabeçalho dos totalizadores
   * @param  string $sDescricao Label da coluna
   * @return void
   */
  private function imprimeCabecalhoTotalizador( $sDescricao ) {

    $this->oPdf->setfont('arial', 'B', 7);
    $this->oPdf->cell(171, 4, "{$sDescricao}", 1, 0, 'C', 1);
    $this->oPdf->cell( 20, 4, "Total",         1, 1, 'C', 1);
    $this->oPdf->setfont('arial', '', 7);
  }

  /**
   * Define o totalizador do relatorio
   * @param integer $iTipo
   */
  public function setTipoTotalizador($iTipo) {

    switch ($iTipo) {
      case RelatorioProfessorEscola::POR_REGIME_TRABALHO :

        $this->iTipoTotalizador = RelatorioProfessorEscola::POR_REGIME_TRABALHO;
        break;

      case RelatorioProfessorEscola::POR_TIPO_HORA :

        $this->iTipoTotalizador = RelatorioProfessorEscola::POR_TIPO_HORA;
        break;
      default:
        throw new Exception( _M(MSG_RELATORIOPROFESSORESCOLA . "tipo_totalizado_invalido" ) );
        break;
    }
  }

  /**
   * Valida tipo do to
   * @param  integer $iEscola Escola a qual vamos imprimir o totalizador, se igual a 0, imprime de todas escolas
   */
  protected function imprimirTotalizador($iEscola) {

    if ( $this->iTipoTotalizador == RelatorioProfessorEscola::POR_TIPO_HORA ) {

      $this->totalizarPorTipoHora($iEscola);
      return;
    }

    $this->totalizarPorRegime($iEscola);
    return;

  }

  /**
   * Retorna o nome do professor
   * @param  integer $iTipoServidor Tipo do servidor
   * @param  integer $iRecHumano    Código do professor na escola
   * @return string                 Código da Matricula/CGM - Nome do professor
   */
  private function getNomeProfessor($iTipoServidor, $iRecHumano) {

    $sSqlNomeProfessor = "";
    switch ($iTipoServidor) {
      case 1:

        $sSqlNomeProfessor .= " select rh01_regist as codigo, z01_nome ";
        $sSqlNomeProfessor .= "   from rechumanopessoal ";
        $sSqlNomeProfessor .= "  inner join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
        $sSqlNomeProfessor .= "  inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm ";
        $sSqlNomeProfessor .= "  where ed284_i_rechumano = {$iRecHumano} ";

        break;
      case 2:
        $sSqlNomeProfessor .= " select z01_numcgm as codigo,  z01_nome ";
        $sSqlNomeProfessor .= "   from rechumanocgm ";
        $sSqlNomeProfessor .= "  inner join cgm on cgm.z01_numcgm = rechumanocgm.ed285_i_cgm ";
        $sSqlNomeProfessor .= "  where ed285_i_rechumano = $iRecHumano ";
        break;

      default:
        throw new Exception( _M(MSG_RELATORIOPROFESSORESCOLA . "tipo_servidor_indefinido" ) );
        break;
    }

    $rsNomeProfessor = db_query($sSqlNomeProfessor);

    $oMsgErro = new stdClass();
    if ( !$rsNomeProfessor ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception(_M(MSG_RELATORIOPROFESSORESCOLA . "erro_buscar_nome", $oMsgErro ));
    }

    $oDadosProfessor = db_utils::fieldsMemory( $rsNomeProfessor, 0 );
    return "{$oDadosProfessor->codigo} - {$oDadosProfessor->z01_nome}";

  }

  /**
   * Verifica se professor tem horários de regencia cadastrado para o turno e tipo de hora de trabalho
   * @param  integer  $iRecHumanoEscola  Código do profissional na escola
   * @param  integer  $iTurnoReferente   Código do turno de referencia
   * @param  integer  $iTipoHoraTrabalho Código da hora de trabalho vinculada
   * @return boolean                     true se possue vinculo
   */
  private function isRegente($iRecHumanoEscola, $iTurnoReferente, $iTipoHoraTrabalho) {

    $oDaoRecHumanoAtiv = new cl_rechumanoativ();

    $sWhere  = "     ed22_i_rechumanoescola = {$iRecHumanoEscola}  ";
    $sWhere .= " and ed129_tipohoratrabalho = {$iTipoHoraTrabalho} ";
    $sWhere .= " and ed129_turno            = {$iTurnoReferente}   ";

    $sSqlIsRegente = $oDaoRecHumanoAtiv->sql_query_horarios_regencia(null, "distinct 1", null, $sWhere);
    $rsIsRegente   = db_query($sSqlIsRegente);

    $oMsgErro = new stdClass();
    if ( !$rsIsRegente ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException(_M(MSG_RELATORIOPROFESSORESCOLA . "erro_verificar_regencia", $oMsgErro ));
    }

    return pg_num_rows($rsIsRegente) > 0;
  }
}