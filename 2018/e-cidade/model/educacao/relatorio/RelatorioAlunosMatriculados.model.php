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
 * Classe modelo para estatística dos alunos matriculados
 * @package    Educacao
 * @subpackage Relatorio
 * @author     André Mello  - andre.mello@dbseller.com.br
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.3 $
 */
class RelatorioAlunosMatriculados extends EstatisticaAlunosMatriculados {

  /**
   * valida se devemos exibir o percentual
   * @var boolean
   */
  private $lPercentual = false;

  const COR_ENSINO = '180';
  const COR_ETAPA  = '225';
  const COR_TURMA  = '255';

  /**
   * Instancia de FPDF
   * @var FPDF
   */
  private $oPdf;

  private $iHeight;

  /**
   * Construtor da classe.
   * @param Calendario $oCalendario Instancia do calendário
   * @param array      $aEtapa      Array com os códigos das etapas a serem filtradas
   * @param Escola     $oEscola     Instancia da escola
   * @param Boolean    $lPercentual Valida se mostra ou não os percentuais
   */
  public function __construct( Calendario $oCalendario, $aEtapa, Escola $oEscola, $lPercentual = false ) {

    parent::__construct( $oCalendario, $aEtapa, $oEscola );
    $this->getEstatisticaAlunosMatriculados();
    $this->getPercentual();

    global $head1;
    global $head2;
    global $head3;
    global $head4;

    $this->lPercentual = $lPercentual;

    $oEtapa = EtapaRepository::getEtapaByCodigo($aEtapa[0]);
    $sDescricaoEtapa = $oEtapa->getNome();

    if ( count($aEtapa) > 1 ) {
      $sDescricaoEtapa = "TODOS";
    }

    $this->oPdf = new PDF();
    $this->oPdf->setfont('arial', '', 8);
    $this->oPdf->Open();
    $this->oPdf->AliasNbPages();
    $this->oPdf->SetAutoPageBreak(true, 20);

    $head1 = 'RELATÓRIO DE ALUNOS MATRICULADOS';
    $head2 = "Calendário: {$oCalendario->getDescricao()}";
    $head3 = "Etapa : {$sDescricaoEtapa} ";
    $head4 = $lPercentual ? "Filtro: Turmas e Percentuais" : "Filtro: Turmas" ;

    $this->oPdf->setfillcolor(223);
    $this->oPdf->SetMargins(8, 8, 8);
    $this->oPdf->addpage('P');

  }



  /**
   * Método responsável por montar a linhas do pdf de acordo com as Turmas de cada Etapa por Ensino
   */
  public function imprimir() {

    $this->oPdf->setX(8);

    /**
     * Percorre os Ensinos montando as linhas por cada Ensino
     */
    foreach ( $this->aEnsino as $oEnsino ) {

      $this->oPdf->setfillcolor(self::COR_ENSINO);
      $this->oPdf->setfont('arial', 'b', 8);
      $this->oPdf->cell(195, 4, "{$oEnsino->sNome}", 1, 1, "L", 1);

      /**
       * Percorre as etapas de cada ensino montandos as linhas
       */
      foreach ( $oEnsino->aEtapa as $oEtapa ) {

        $this->oPdf->setfillcolor(self::COR_ETAPA);
        $this->oPdf->setfont('arial', 'b', 8);
        $this->oPdf->cell(60 ,4, "Etapa: {$oEtapa->sNome}", 1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "Matr. Inic.",             1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "EVAD.",                   1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "CANC.",                   1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "TRANS.",                  1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "PROGR.",                  1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "ÓBITO",                   1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "Matr. Efet.",             1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "Vagas",                   1, 0, "L", 1);
        $this->oPdf->cell(15 ,4, "Vag. Disp.",              1, 1, "L", 1);

        /**
         * Monta as linhas contendo as informações de matriculas por turma
         */
        foreach ( $oEtapa->aTurmas as $oTurma ) {

          $sTurma = substr("{$oTurma->sTurma} - {$oTurma->sTurno}", 0, 35);

          $this->oPdf->setfillcolor(self::COR_TURMA);
          $this->oPdf->setfont('arial', '', 8);
          $this->oPdf->cell(60, 4, "{$sTurma}",                        1, 0, "L", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matricula_inicial",       1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_evadidas",     1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_canceladas",   1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_transferidas", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_progredidas",  1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_falecidas",    1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->matriculas_efetivas",     1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->total_vagas",             1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "$oTurma->total_disponiveis",       1, 1, "C", 1);

        }

        /**
         * Monta os totais das Etapas
         */
        $this->oPdf->setfillcolor(self::COR_ETAPA);
        $this->oPdf->setfont('arial', 'b', 8);
        $this->oPdf->cell(60, 4, "Total da Etapa: {$oEtapa->sNome}", 1, 0, "R", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalMatriculaInicial}",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalEvadidos        }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalCancelados      }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalTransferidos    }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalProgredidos     }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalObitos          }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalMatriculaEfetiva}",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalVagas           }",    1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEtapa->iTotalVagasDisponiveis}",    1, 1, "C", 1);

        if ($this->lPercentual) {

          $this->oPdf->cell(60, 4, 'Percentuais: ',                           1, 0, "R", 1);
          $this->oPdf->cell(15, 4, "",                                        1, 0, "C" , 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualEvadidos        }%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualCancelados      }%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualTransferidos    }%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualProgredidos     }%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualObitos          }%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualMatriculaEfetiva}%", 1, 0, "C", 1);
          $this->oPdf->cell(15, 4, "",                                        1, 0, "C" , 1);
          $this->oPdf->cell(15, 4, "{$oEtapa->iPercentualVagasDisponiveis}%", 1, 1, "C", 1);
        }

      }

      /**
       * Monta os totais dos Ensinos
       */
      $this->oPdf->setfillcolor(self::COR_ENSINO);
      $this->oPdf->setfont('arial', 'b', 8);
      $this->oPdf->cell(60, 4, substr("Total {$oEnsino->sNome}", 0, 35), 1, 0, "R", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalMatriculaInicial}",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalEvadidos        }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalCancelados      }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalTransferidos    }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalProgredidos     }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalObitos          }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalMatriculaEfetiva}",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalVagas           }",         1, 0, "C", 1);
      $this->oPdf->cell(15, 4, "{$oEnsino->iTotalVagasDisponiveis}",         1, 1, "C", 1);

      if ($this->lPercentual) {

        $this->oPdf->cell(60, 4, 'Percentuais: ',                            1, 0, "R", 1);
        $this->oPdf->cell(15, 4, "",                                         1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualEvadidos        }%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualCancelados      }%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualTransferidos    }%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualProgredidos     }%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualObitos          }%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualMatriculaEfetiva}%", 1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "",                                         1, 0, "C", 1);
        $this->oPdf->cell(15, 4, "{$oEnsino->iPercentualVagasDisponiveis}%", 1, 1, "C", 1);
      }

    }

    /**
     * Monta a linha que imprime o total geral
     */
    $oTotalGeral = $this->getTotalGeral();

    $this->oPdf->ln();
    $this->oPdf->setfillcolor(self::COR_ENSINO);
    $this->oPdf->setfont('arial', 'b', 8);

    $this->oPdf->cell(195, 4, "TOTAL GERAL", 1, 1, "L", 1);

    $this->oPdf->cell(60 ,4, "",            1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "Matr. Inic.", 1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "EVAD.",       1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "CANC.",       1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "TRANS.",      1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "PROGR.",      1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "ÓBITO",       1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "Matr. Efet.", 1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "Vagas",       1, 0, "L", 1);
    $this->oPdf->cell(15 ,4, "Vag. Disp.",  1, 1, "L", 1);

    $this->oPdf->setfillcolor(self::COR_TURMA);
    $this->oPdf->setfont('arial', 'b', 8);
    $this->oPdf->cell(60, 4, "Somas: ",                            1, 0, "R", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalMatriculaInicial}", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalEvadidos        }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalCancelados      }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalTransferidos    }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalProgredidos     }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalObitos          }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalMatriculaEfetiva}", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalVagas           }", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iTotalVagasDisponiveis}", 1, 1, "C", 1);

    $this->oPdf->cell(60, 4, 'Percentuais: ',                                1, 0, "R", 1);
    $this->oPdf->cell(15, 4, "",                                             1, 0, "C" , 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualEvadidos        }%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualCancelados      }%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualTransferidos    }%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualProgredidos     }%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualObitos          }%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualMatriculaEfetiva}%", 1, 0, "C", 1);
    $this->oPdf->cell(15, 4, "",                                             1, 0, "C" , 1);
    $this->oPdf->cell(15, 4, "{$oTotalGeral->iPercentualVagasDisponiveis}%", 1, 1, "C", 1);
    $this->oPdf->Output();
  }

}