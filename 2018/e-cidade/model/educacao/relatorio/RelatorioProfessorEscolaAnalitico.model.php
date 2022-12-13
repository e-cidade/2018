<?php

/**
 *
 */
class RelatorioProfessorEscolaAnalitico extends RelatorioProfessorEscola {

  /**
   *
   * @param FpdfMultiCellBorder  $oPdf          instancia do FPDF
   * @param integer              $iEscola       Código da escola
   * @param integer              $iAreaTrabalho Código da Area de Trabalho
   * @param integer              $iTipoHora     Código do tipo de hora
   */
  public function __construct(FPDF $oPdf, $iEscola, $iAreaTrabalho = 0, $iTipoHora = 0) {

    parent::__construct($oPdf, $iEscola, $iAreaTrabalho, $iTipoHora);

    $oPdf->SetMargins(10, 10);
    $oPdf->SetAutoPageBreak(true, 10);

    global $head1;
    $head1 = $this->sNomeRelatorio;
  }

  /**
   * Organiza de forma analítica o array com os dados dos professores na escola
   * @param  stdClass $oEscola Dados dos professores na escoal
   * @return $aDadosOrganizados[]
   */
  private function organizaDados($oEscola) {

    $aDadosOrganizados = array();

    // foreach ( $oEscola->aProfessores as $oProfessor ) {


    //   if ( !array_key_exists($oProfessor->iCodigo, $aDadosOrganizados) ) {

    //     $oDadosProfessor->sNome       = $oProfessor->sNome;
    //     $oDadosProfessor->dtIngresso  = $oProfessor->dtIngresso;
    //     $oDadosProfessor->aAreaRegime = array();
    //     $aDadosOrganizados[$oProfessor->iCodigo] = $oDadosProfessor;
    //   }


    //   foreach ($oProfessor->aAreaTrabalho as $key => $oAreaRefime) {

    //     if ( !array_key_exists($key, $aDadosOrganizados[$oProfessor->iCodigo]->aAreaRegime) ) {

    //       $oDadosAreaRefime                = new stdClass();
    //       $oDadosAreaRefime->sAreaTrabalho = $oAreaRegime->sAreaTrabalho;
    //       $oDadosAreaRefime->sRegime       = $oAreaRegime->sRegime;
    //       $oDadosAreaRefime->aDisciplinas  = $oAreaRefime->aDisciplinas;
    //       $oDadosAreaRefime->aTipoHora     = $oAreaRefime->aTipoHora;

    //     }
    //     // $oDadosProfessor->aAreaRegime
    //   }

      // [iCodigo] => 3
      //                       [sNome] => 1 - RITA SANTOS DA SILVA
      //                       [dtIngresso] => DBDate Object
      //                           (
      //                               [iTimeStamp:DBDate:private] => 1365390000
      //                           )

    // }

  }

  /**
   * Imprime os dados do relatorio
   */
  public function imprimir() {

    $iLarguraLabelDisciplina = 23;

    $iIdentacaoArea       = 14; // margem + 4
    $iIdentacaoDias       = 18; // margem + 8
    $iIdentacaoDisciplina = $iIdentacaoDias;

    $iIdentacaoDescricaoDisciplina = $iLarguraLabelDisciplina + $iIdentacaoDisciplina;

    // echo"<pre>";
    foreach ($this->aDados as $iEscola => $oEscola) {

      global $head2;
      $head2 = "Escola: " . $oEscola->sNome;

      $this->oPdf->AddPage();
      foreach ( $oEscola->aProfessores as $oProfessor ) {

        $this->validaQuebraPagina(2);
        $this->oPdf->setfont('arial', 'B', 7);
        $this->oPdf->cell(160, 4, "Matrícula/CGM - Professor", "B", 0, 'L');
        $this->oPdf->cell( 31, 4, "Data Ingresso ",            "B", 1, 'L');
        $this->oPdf->setfont('arial', '', 7);
        $this->oPdf->cell(160, 4, $oProfessor->sNome,                                    0, 0, 'L');
        $this->oPdf->cell( 31, 4, $oProfessor->dtIngresso->convertTo(DBDate::DATA_PTBR), 0, 1, 'L');

        foreach ($oProfessor->aAreaTrabalho as $oAreaTrabalho) {

          $this->validaQuebraPagina(2);
          $this->oPdf->setfont('arial', 'B', 7);
          $this->oPdf->setX( $iIdentacaoArea );
          $this->oPdf->cell(100, 4, "Área de Trabalho",          "B", 0, 'L');
          $this->oPdf->cell( 87, 4, "Regime de Trabalho",        "B", 1, 'L');
          $this->oPdf->setfont('arial', '', 7);
          $this->oPdf->setX( $iIdentacaoArea );
          $this->oPdf->cell(100, 4, $oAreaTrabalho->sAreaTrabalho, 0, 0, 'L');
          $this->oPdf->cell( 87, 4, $oAreaTrabalho->sRegime,       0, 1, 'L');

          foreach ( $oAreaTrabalho->aTipoHora as $oTipoHora ) {

            foreach ( $oTipoHora->aHoraDia as $oHoraDia ) {

              $this->validaQuebraPagina(2);
              $this->oPdf->setX( $iIdentacaoArea );
              $this->oPdf->setfont('arial', 'B', 7);
              $this->oPdf->cell(15, 4, "Tipo Hora: ", 0, 0);
              $this->oPdf->setfont('arial', '', 7);
              $this->oPdf->cell(85, 4, $oTipoHora->sDescricao, 0, 0);
              $this->oPdf->setfont('arial', 'B', 7);
              $this->oPdf->cell(10, 4, "Turno: ", 0, 0, "R");
              $this->oPdf->setfont('arial', '', 7);
              $this->oPdf->cell(46, 4, $oTipoHora->sTurno, 0, 0);

              $this->oPdf->cell(70, 4, $oHoraDia->sHoraInicio . " às " . $oHoraDia->sHoraFim, 0, 1);

              /**
               * imprime dias da semana
               */
              $this->oPdf->setX( $iIdentacaoDias );
              $this->oPdf->setfont('arial', 'B', 7);
              $this->oPdf->cell(23, 4, "Dias da Semana: ", 0, 0);
              $this->oPdf->setfont('arial', '', 7);
              $this->oPdf->MultiCell(160, 4, implode(", ", $oHoraDia->aDias));

            }
          }
          if ( $this->lMostrarDisciplinas ) {

            $this->oPdf->setX( $iIdentacaoDisciplina );
            $this->oPdf->setfont('arial', 'B', 7);
            $this->oPdf->cell(23, 4, "Disciplina(s): ", 0, 0);

            $this->oPdf->setfont('arial', '', 7);
            foreach ($oAreaTrabalho->aDisciplinas as $iDisciplina) {

              $oDisciplina = DisciplinaRepository::getDisciplinaByCodigo($iDisciplina);

              $this->oPdf->setX( $iIdentacaoDescricaoDisciplina );
              $this->oPdf->cell(100, 4, $oDisciplina->getNomeDisciplina(), 0, 1, 'L');
            }


          }

          $this->oPdf->ln(2);
        }
        $this->oPdf->ln();

      }

      $this->imprimirTotalizador($iEscola);
    }

    /**
     * Se selecionado para imprimir todas as escolas
     */
    if ( $this->iEscola == 0) {

      global $head3;
      $head2 = "Escola: TODAS";
      $head3 = "TOTALIZADOR GERAL";
      $this->oPdf->AddPage();
      $this->imprimirTotalizador(0);
    }
  }

}