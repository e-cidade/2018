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

class RelatorioProfessorEscolaSintetico extends RelatorioProfessorEscola {

  /**
   *
   * @param FpdfMultiCellBorder  $oPdf          instancia do FPDF
   * @param integer              $iEscola       Código da escola
   * @param integer              $iAreaTrabalho [description]
   * @param integer              $iTipoHora     [description]
   */
  public function __construct(FPDF $oPdf, $iEscola, $iAreaTrabalho = 0, $iTipoHora = 0) {

    parent::__construct($oPdf, $iEscola, $iAreaTrabalho, $iTipoHora);

    global $head1;
    $head1 = $this->sNomeRelatorio;
  }

  /**
   * Organiza de forma sintética o array com os dados dos professores na escola
   * @param  stdClass $oEscola Dados dos professores na escoal
   * @return $aDadosOrganizados[]
   */
  private function organizaDados($oEscola) {

    $aDadosOrganizados = array();

    foreach ($oEscola->aProfessores as $oProfessor) {

      $oDadosProfissional = new stdClass();
      $oDadosProfissional->sNome  = $oProfessor->sNome;
      $oDadosProfissional->aAreas = array();


      foreach ($oProfessor->aAreaTrabalho as $oAreaTrabalho) {

        if ( !array_key_exists($oAreaTrabalho->iAreaTrabalho, $oDadosProfissional->aAreas) ) {

          $oArea                  = new stdClass();
          $oArea->sAreaTrabalho   = $oAreaTrabalho->sAreaTrabalho;
          $oArea->aRegimeTrabalho[$oAreaTrabalho->iRegime] = array();
          $oDadosProfissional->aAreas[$oAreaTrabalho->iAreaTrabalho] = $oArea;
        }

        foreach ($oAreaTrabalho->aTipoHora as $oTipoHora) {

          $oDadosRegime            = new stdClass();
          $oDadosRegime->sRegime   = $oAreaTrabalho->sRegime;
          $oDadosRegime->sTurno    = $oTipoHora->sTurno;
          $oDadosRegime->sTipoHora = $oTipoHora->sDescricao;
          $oDadosRegime->lRegente  = $oTipoHora->lRegente;
          $oDadosProfissional->aAreas[$oAreaTrabalho->iAreaTrabalho]->aRegimeTrabalho[$oAreaTrabalho->iRegime][] = $oDadosRegime;
        }
      }

      $aDadosOrganizados[] = $oDadosProfissional;
    }

    return $aDadosOrganizados;

  }

  /**
   * Imprime os dados do relatorio
   */
  public function imprimir() {

    foreach ($this->aDados as $iEscola => $oEscola) {

      global $head2;
      $head2 = "Escola: " . $oEscola->sNome;

      $this->oPdf->AddPage();
      foreach ($this->organizaDados($oEscola) as $oProfessor) {

        /**
         * Valida quebre de página considerando a escrita de pelo menos:
         * - nome do prodessor:
         * - Área de trabalho;
         * - Ao menos um registro da grade com o regime mais título da tabela
         */
        $this->validaQuebraPagina(4);
        $this->oPdf->setfont('arial', 'B', 7);
        $this->oPdf->cell(191, 4, "Professor: " . $oProfessor->sNome, 0, 1, 'L');

        foreach ( $oProfessor->aAreas as $oArea ) {

          $this->validaQuebraPagina(4);
          $this->oPdf->setfont('arial', 'B', 7);
          $this->oPdf->cell(191, 4, "Área de Trabalho: " . $oArea->sAreaTrabalho, 0, 1, 'L');

          $lImprimeCabecalho = true;

          foreach ($oArea->aRegimeTrabalho as $aRegime) {

            foreach ($aRegime as $oRegime) {

              if ($lImprimeCabecalho || $this->validaQuebraPagina(1) ) {

                $this->oPdf->cell(30,  4, "Turno",              1, 0, 'C', 1);
                $this->oPdf->cell(101, 4, "Regime de Trabalho", 1, 0, 'C', 1);
                $this->oPdf->cell(40,  4, "Tipo de Hora",       1, 0, 'C', 1);
                $this->oPdf->cell(20,  4, "Regente",            1, 1, 'C', 1);
                $lImprimeCabecalho = false;
              }

              $this->oPdf->setfont('arial', '', 7);
              $sRegente  = $oRegime->lRegente ? "Sim" : "Não";
              $this->oPdf->cell(30,  4, $oRegime->sTurno,    1, 0, 'L');
              $this->oPdf->cell(101, 4, $oRegime->sRegime,   1, 0, 'L');
              $this->oPdf->cell(40,  4, $oRegime->sTipoHora, 1, 0, 'L');
              $this->oPdf->cell(20,  4, $sRegente,           1, 1, 'L');
            }

          }
          $this->oPdf->ln(2);
        }
        $this->oPdf->ln();
      }
      $this->oPdf->ln(2);

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