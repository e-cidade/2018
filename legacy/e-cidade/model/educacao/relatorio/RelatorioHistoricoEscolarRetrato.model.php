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
 * Renderiza o relatório de acordo com os parâmetros
 *
 * @package educacao
 * @subpackage relatorio
 * @author andrio.costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.42 $
 */
class RelatorioHistoricoEscolarRetrato extends RelatorioHistoricoEscolar {

  const NUMERO_ETAPAS_PAGINA      = 9;
  const LARGURA_DISCIPLINA        = 60;
  const ALTURA_LINHA              = 4;
  const COR_PREENCHIMENTO         = 255;
  const DISPOSICAO_CABECALHO_1    = 1;
  const DISPOSICAO_CABECALHO_2    = 2;
  const MAXIMO_LINHAS_ATOS_LEGAIS = 4;
  const LARGURA_ASSINATURA        = 93;
  const LARGURA_TOTAL             = 203;

  private $sTituloRelatorio = "HISTÓRICO ESCOLAR";

  private static $aLarguraColunaEtapa = array(
    'etapa'                 => 25,
    'ano'                   => 8,
    'dias'                  => 8,
    'turma'                 => 15,
    'carga_horaria'         => 12,
    'percentual_frequencia' => 10,
    'resultado'             => 14,
    'escola'                => 58,
    'cidade'                => 40,
    'uf'                    => 5
  );

  private static $aLabelColunaEtapa   = array(
    'etapa'                 => 'ETAPA',
    'ano'                   => 'ANO',
    'dias'                  => 'DIAS',
    'turma'                 => 'TURMA',
    'carga_horaria'         => 'C.H.',
    'percentual_frequencia' => 'FREQ',
    'resultado'             => 'RESULTADO',
    'escola'                => 'ESCOLA',
    'cidade'                => 'CIDADE',
    'uf'                    => 'UF'
  );

  /**
   * Instancia da Biblioteca FPDF
   * @var FPDF
   */
  protected $oPdf ;

  /**
   * Disposdição do cabeçalho do relatorio
   * @var integer
   */
  private $iDisposicao = null;

  /**
   * Controla se deve ou não exibir somente as etapas cursadas ou todas do curso
   * @var boolean
   */
  private $lExibirTodasEtapasCurso = false;

  /**
   * Construtor da classe
   * @param FPDF    $oPdf
   * @param Aluno   $oAluno
   * @param Escola  $oEscola
   * @param integer $iTipoRelatorio
   * @param boolean $lExibirReclassificacao
   */
  public function __construct( FPDF $oPdf, Aluno $oAluno, Escola $oEscola, $iTipoRelatorio, $lExibirReclassificacao ) {

    parent::__construct($oAluno, $oEscola, $iTipoRelatorio, $lExibirReclassificacao);
    $this->oPdf        = $oPdf;
    $this->oPdf->AddPage();
    $this->oPdf->setfillcolor(223);
  }

  /**
   * Atribui a disposição selecionada para impressão
   * @param integer $iDisposicao
   */
  public function setDisposicao($iDisposicao = 1) {

    $this->iDisposicao = $iDisposicao;
  }

  /**
   * Escreve o cabecalho do relatório
   *
   * @return void
   */
  public function escreveCabecalho () {

    $iAlturaLinha = self::ALTURA_LINHA;

    $sNomeMae  = trim($this->oAluno->getNomeMae());
    $sNomePai  = trim($this->oAluno->getNomePai());
    $sFiliacao = "";

    if (empty($sNomePai) && !empty($sNomeMae) ) {
      $sFiliacao = $sNomeMae;
    } else if (!empty($sNomePai) &&  empty($sNomeMae) ) {
      $sFiliacao = $sNomePai;
    } else if (!empty($sNomePai) && !empty($sNomeMae) ) {
      $sFiliacao = "{$sNomePai} e de {$sNomeMae}";
    }

    $aNacionalidade = array(
                            "1" => "BRASILEIRO",
                            "2" => "BRASILEIRO NASCIDO NO EXTERIOR OU NATURALIZADO",
                            "3" => "ESTRANGEIRO"
    );


    if ($this->oAluno->getDataNascimento() == "") {

      $sAluno = "{$this->oAluno->getCodigoAluno()} - {$this->oAluno->getNome()}";
      throw new Exception("Aluno {$sAluno} não possui data de Nascimento, atualize o cadastro.");
    }

    $oDtNascimento     = new DBDate($this->oAluno->getDataNascimento());
    $sLocalNascimento  = $oDtNascimento->convertTo(DBDate::DATA_PTBR);
    if ($this->oAluno->getNaturalidade() != "") {

      $sNaturalidade = "";

      if (!is_null($this->oAluno->getNaturalidade()->getCodigo())) {

        $sNaturalidade  = "{$this->oAluno->getNaturalidade()->getNome()} / ";
        $sNaturalidade .= "{$this->oAluno->getNaturalidade()->getUF()->getUF()}";
      }

      if ( $this->oAluno->getNacionalidade() == 1 ) {
        $sLocalNascimento .= " em {$sNaturalidade} ";
      }

    }

    $iInstituicao = db_getsession( "DB_instit" );
    $sImagem      = RelatorioHistoricoEscolar::getBrasao( $this->oParametros->brasao, new Instituicao( $iInstituicao ) );
    $this->oPdf->image( $sImagem, 10, 10, 20, 20 );
    $this->oPdf->setfont('arial', 'B', 6);

    if ( $this->iDisposicao == 1) {
      $this->escreverCabecalhoDisposicao1();
    } else {
      $this->escreverCabecalhoDisposicao2();
    }


    $this->oPdf->ln(2);
    $this->oPdf->setfont('arial', '', 6);
    $this->oPdf->cell(15, $iAlturaLinha, "Nome:", 0, 0, "L", 0);
    $this->oPdf->setfont('arial', 'b', 8);
    $this->oPdf->cell(95, $iAlturaLinha,  "{$this->oAluno->getCodigoAluno()} - {$this->oAluno->getNome()}", 0, 1, "L", 0);
    $this->oPdf->setfont('arial', '', 6);


    $this->oPdf->cell(15, $iAlturaLinha, "Filho(a) de:", 0, 0, "l", 0);
    $this->oPdf->cell(93, $iAlturaLinha, $sFiliacao    , 0, 1, "L", 0);

    $this->oPdf->cell(15, $iAlturaLinha, "Nacionalidade:", 0, 0, "L", 0);
    $this->oPdf->cell(25, $iAlturaLinha, $aNacionalidade[$this->oAluno->getNacionalidade()], 0, 1, "L", 0);
    $this->oPdf->cell(12, $iAlturaLinha, "Identidade: ", 0, 0, "L", 0);
    $this->oPdf->cell(30, $iAlturaLinha, $this->oAluno->getIdentidade(), 0, 0, "L", 0);

    $this->oPdf->cell(15, $iAlturaLinha, "Nascido(a) em:", 0, 0, "L", 0);
    $this->oPdf->cell(80, $iAlturaLinha, $sLocalNascimento, 0, 0, "L", 0 );
    $this->oPdf->cell(12, $iAlturaLinha, "ID INEP: ", 0, 0, "L", 0);
    $this->oPdf->cell(5,  $iAlturaLinha, $this->oAluno->getCodigoInep(), 0, 1, "L",0);

    $this->oPdf->SetFontSize($this->oParametros->fonte_observacao);
    return;
  }

  /**
   * Escreve Disposicao do Relatório
   *
   * @return void
   */
  private function escreverCabecalhoDisposicao1() {

    $iPosicaoXDadosEscola    = 110;
    $this->oPdf->setX(32);//POsicao Texto Cabecalho
    $this->oPdf->multicell(75, 4, $this->oParametros->cabecalho, 0, "C", 0, 0);

    /**
     * Monta a string dos atos
     */
    $aAtoEscola = $this->getAtosLegais();
    $sAtoEscola = "";
    $iLinhas    = 0;

    $this->oPdf->SetFontSize(6);
    foreach ($aAtoEscola as $sAtoLegal) {

      $iLinhas += $this->oPdf->NbLines(100, $sAtoLegal);
      if ($iLinhas > self::MAXIMO_LINHAS_ATOS_LEGAIS ) {
      	continue;
      }
      $sAtoEscola .= $sAtoLegal . "\n";
    }


    $sTelefoneEscola = "";
    $aTelefones      = $this->oEscola->getTelefones();
    if (count($aTelefones) > 0) {

      $sTelefoneEscola = "Fone: ({$aTelefones[0]->iDDD}) {$aTelefones[0]->iNumero}";
      $sTelefoneEscola.= !empty($aTelefones[0]->iRamal) ? "Ramal: {$aTelefones[0]->iRamal}" : "" ;
    }

    $sEnderecoEscola  = "{$this->oEscola->getEndereco()}, {$this->oEscola->getNumeroEndereco()}";
    $sEnderecoEscola .= " - Bairro : {$this->oEscola->getBairro()} ";
    $sEnderecoEscola .= "\nCEP: {$this->oEscola->getCep()} - {$this->oEscola->getMunicipio()}/ {$this->oEscola->getUf()} - ";
    $sEnderecoEscola .= $sTelefoneEscola;

    $sNomeEscola       = $this->oEscola->getNome();
    $iCodigoReferencia = $this->oEscola->getCodigoReferencia();

    if ( $iCodigoReferencia != null ) {
      $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
    }

    $mCabecalhoEscola  = $sNomeEscola;
    $mCabecalhoEscola .= "\nMantenedora: ";
    $mCabecalhoEscola .=  $this->oEscola->getDepartamento()->getInstituicao()->getDescricao();
    $mCabecalhoEscola .= "\nEndereço: {$sEnderecoEscola}";

    $this->oPdf->setxy($iPosicaoXDadosEscola, $this->oPdf->tMargin);
    $this->oPdf->setfont('arial', '', 6);
    $this->oPdf->multicell(95, 3, $mCabecalhoEscola, 0, "L", 0, 0);
    $this->oPdf->Ln(1);
    $this->oPdf->setX($iPosicaoXDadosEscola);
    $this->oPdf->setfont('arial', '', 6);
    $this->oPdf->multicell(95, 3, $sAtoEscola, 0, "L", 0, 0);
    $this->oPdf->setXY(100, 36);
    $this->oPdf->setfont('arial', 'b', 7);
    $this->oPdf->multicell(120, 2, $this->sTituloRelatorio, "", "L", 0, 0);
    $this->oPdf->setXY(80, 40);
    $this->oPdf->multicell(120, 2, $this->getNomeUltimoCurso()." Lei 9.394/96", "", "L", 0, 0);

    return;
  }

  /**
   * Mostra o cabeçalho das informadções conforme Disposicao 2 do Sistema
   *
   * @access private
   * @return void
   */
  private function escreverCabecalhoDisposicao2() {

    $sTelefoneEscola = "";
    $aTelefones      = $this->oEscola->getTelefones();
    if (count($aTelefones) > 0) {

      $sTelefoneEscola = "Fone: ({$aTelefones[0]->iDDD}) {$aTelefones[0]->iNumero}";
      $sTelefoneEscola.= !empty($aTelefones[0]->iRamal) ? " Ramal: {$aTelefones[0]->iRamal}" : "" ;
    }

    $sEnderecoEscola1  = "Endereço: {$this->oEscola->getEndereco()}, {$this->oEscola->getNumeroEndereco()}";
    $sEnderecoEscola1 .= " - Bairro : {$this->oEscola->getBairro()} ";
    $sEnderecoEscola2  = "CEP: {$this->oEscola->getCep()} - {$this->oEscola->getMunicipio()}/ {$this->oEscola->getUf()} - ";
    $sEnderecoEscola2 .= $sTelefoneEscola;

    $mCabecalhoEscola  = "Mantenedora: ";
    $mCabecalhoEscola .= $this->oEscola->getDepartamento()->getInstituicao()->getDescricao();

    /**
     * Monta a string dos atos
     */
    $aAtoEscola = $this->getAtosLegais();
    $sAtoEscola = "";
    $iLinhas    = 0;

    foreach ($aAtoEscola as $sAtoLegal) {

      $iLinhas += $this->oPdf->NbLines(100, $sAtoLegal);
      if ($iLinhas > self::MAXIMO_LINHAS_ATOS_LEGAIS ) {
      	continue;
      }
      $sAtoEscola .= $sAtoLegal . "\n";
    }

    $sNomeEscola       = $this->oEscola->getNome();
    $iCodigoReferencia = $this->oEscola->getCodigoReferencia();

    if ( $iCodigoReferencia != null ) {
      $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
    }

    $this->oPdf->SetY(15);
    $this->oPdf->setfont('arial', 'b', 6);
    $this->oPdf->SetX(60);
    $this->oPdf->multicell(100, 4, $this->oParametros->cabecalho, 0, "C", 0, 0);
    $this->oPdf->SetX(60);
    $this->oPdf->setfont('arial', 'b', 8);
    $this->oPdf->cell(100, 5, strtoupper($sNomeEscola), 0, 1, "C", 0);
    $this->oPdf->setfont('arial', 'b', 7);
    $this->oPdf->SetX(60);
    $this->oPdf->cell(100, 4, mb_strtoupper($this->sTituloRelatorio), 0, 1, "C", 0);
    $this->oPdf->ln();
    $this->oPdf->setfont('arial', '', 6);
    $this->oPdf->multicell(110, 3, $mCabecalhoEscola, 0, "L", 0, 0);
    $this->oPdf->setfont('arial', '', 6);
    if ($sAtoEscola != "") {
      $this->oPdf->multicell(110, 4, $sAtoEscola, "", "L", 0, 0);
    }
    $this->oPdf->cell(98, 5, $sEnderecoEscola1 ,0, 0, "L", 0);
    $this->oPdf->cell(98, 5, $sEnderecoEscola2 ,0, 1, "L", 0);
  }


  /**
   * Verifica se o aluno cursou uma disciplina diversificada no seu histórico
   * @return boolean
   */
  private function possuiBaseDiversificada() {

    foreach ($this->aDadosOrganizados as $oEtapaCursada) {

      foreach ($oEtapaCursada->aDisicplinasEtapa as $oDisciplina ) {
        if (!$oDisciplina->lBaseComum) {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * Reorganiza a estrutura da grade agora devemos separar as disciplinas e as etapas pelo tipo da Base curricular;
   * @return array
   */
  private function reorganizaEstrutura() {

    $aGrade = array();

    $iPaginas          = 1;
    $iColunasImpressas = 0;

    $lPossuiBaseDiversificada = $this->possuiBaseDiversificada();

    foreach ($this->aDadosOrganizados as $oEtapaCursada) {

      if ( count( $oEtapaCursada->aDisicplinasEtapa ) == 0  && !$this->lExibirTodasEtapasCurso ) {
        continue;
      }

      if ($iColunasImpressas == RelatorioHistoricoEscolarRetrato::NUMERO_ETAPAS_PAGINA) {

        $iColunasImpressas = 0;
        $iPaginas ++;
      }
      $iColunasImpressas ++;
      $oEtapaComum               = new stdClass();
      $oEtapaComum->iEtapa       = $oEtapaCursada->iEtapa;
      $oEtapaComum->sEtapa       = $oEtapaCursada->sEtapa;
      $oEtapaComum->aDisciplinas = array();

      $oEtapaDiversificada               = new stdClass();
      $oEtapaDiversificada->iEtapa       = $oEtapaCursada->iEtapa;
      $oEtapaDiversificada->sEtapa       = $oEtapaCursada->sEtapa;
      $oEtapaDiversificada->aDisciplinas = array();

      foreach ($oEtapaCursada->aDisicplinasEtapa as $oDisciplina ) {

        if ( $oDisciplina->lBaseComum ) {
          $oEtapaComum->aDisciplinas[$oDisciplina->iCadDisciplina] = $oDisciplina;
        } else {
          $oEtapaDiversificada->aDisciplinas[$oDisciplina->iCadDisciplina] = $oDisciplina;
        }
      }

      $aGrade[$iPaginas]['comum'][] = $oEtapaComum;

      if ( $lPossuiBaseDiversificada ) {
        $aGrade[$iPaginas]['diversificada'][] = $oEtapaDiversificada;
      }
    }

    /**
     * Adiciona a grade as etapas ainda não cursadas pelo aluno
     */
    if ( $this->lExibirTodasEtapasCurso ) {

      foreach ($this->aEtapasPosterior as $oEtapaPosterior ) {

        if ($iColunasImpressas == RelatorioHistoricoEscolarRetrato::NUMERO_ETAPAS_PAGINA) {

          $iColunasImpressas = 0;
          $iPaginas ++;
        }

        $iColunasImpressas ++;
        $aGrade[$iPaginas]['comum'][] = $oEtapaPosterior;

        if ( $lPossuiBaseDiversificada ) {
          $aGrade[$iPaginas]['diversificada'][] = $oEtapaPosterior;
        }
      }
    }

    return $aGrade;
  }

  /**
   * Cria a tabela de ComponentesCurriculares
   */
  public function criarTabelaComponentesCurriculares() {

    $aDisciplinasCursadas = $this->disciplinasCursadas();

    /**
     * Reorganizamos as etapas em um array controlado por página
     */
    $aGrade = $this->reorganizaEstrutura();

    $iFonteGrade         = $this->oParametros->fonte_grade_nota;
    $iAlturaLinha        = RelatorioHistoricoEscolarRetrato::ALTURA_LINHA;
    $iLarguraDisciplina  = RelatorioHistoricoEscolarRetrato::LARGURA_DISCIPLINA;
    $iNumeroEtapasPagina = RelatorioHistoricoEscolarRetrato::NUMERO_ETAPAS_PAGINA;
    $iTotalEtapas        = 0;

    $iAlturaGradeComponenteCurricular = (count($aDisciplinasCursadas) +1)  * $iAlturaLinha;

    /**
     * Impressão da grade
     */
    foreach ($aGrade as $iPagina => $aTipoBase) {

      foreach ($aTipoBase as $sTipo => $aEtapa) {

        $iAlturaDisponivel = $this->oPdf->getAvailHeight();

        if ($iAlturaGradeComponenteCurricular > $iAlturaDisponivel) {

          $this->oPdf->AddPage();
          $this->escreveCabecalho();
        }

        $sBase = "Base Comum";
        if ($sTipo == 'diversificada') {
          $sBase = "Base Diversificada";
        }

        $this->oPdf->ln(0,5);
        $this->oPdf->SetFont("arial", "B", 8);
        $this->oPdf->Cell($iLarguraDisciplina, $iAlturaLinha, $sBase, 0, 1, "C");
        $this->oPdf->SetFont("arial", "B", $iFonteGrade);
        $this->oPdf->Cell($iLarguraDisciplina, $iAlturaLinha, "COMPONENTES CURRICULARES", 1, 0, "C");

        $iSubString = 10;
        if ($this->oPdf->FontSizePt > 6) {
          $iSubString = 8;
        }

        foreach ($aEtapa as $oEtapa) {

          $iTotalEtapas++;
          $this->oPdf->Cell(15, $iAlturaLinha, substr($oEtapa->sEtapa, 0, $iSubString), 1, 0, "C");
        }

        /**
         * Imprime as colunas em branco para header
         */
        $iNumeroEtapaImpressa = count($aEtapa);
        if (count($aEtapa) < $iNumeroEtapasPagina) {

          for ($i = $iNumeroEtapaImpressa; $i < $iNumeroEtapasPagina; $i++) {
            $this->oPdf->Cell(15, $iAlturaLinha, "", 1, 0, "C");
          }
        }

        $this->oPdf->SetFont("arial", "", $iFonteGrade);
        $this->oPdf->ln();

        /**
         * Imprime as disciplinas e avaliações
         */
        $lZebra                                       = false;
        $lImprimeLinhaComponentesCurricularesEmBranco = true;

        foreach ($aDisciplinasCursadas as $oDisciplina) {

          /**
           * Antes da impressao da Disciplina, validamos se o aluno tem avaliacoes em todas as etapas.
           * caso nao tenha nota em nenhuma etapa, a disciplina não deverá ser impressa
           */
          $lPossuiAvaliacaoNaDisciplina = false;
          foreach ($aEtapa as $oEtapa) {

            if (isset($oEtapa->aDisciplinas[$oDisciplina->iDisciplina])) {

              if (    $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->mAvaliacao != ""
                   || (    $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->mAvaliacao == ""
                        && $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->sSituacao == 'AMPARADO' )
              ) {

                $lPossuiAvaliacaoNaDisciplina = true;
                break;
              }
            }
          }

          if ( !$lPossuiAvaliacaoNaDisciplina ) {
            continue;
          }

          $lImprimeLinhaComponentesCurricularesEmBranco = false;
          $lZebra                                       = !$lZebra;

          $iAlturaCelula         = $this->oPdf->NbLines($iLarguraDisciplina, $oDisciplina->sDisciplina);
          $iAlturaAproveitamento = $iAlturaLinha * $iAlturaCelula;

          $this->oPdf->SetFont("arial", "B", $iFonteGrade);

          $iYInicio = $this->oPdf->GetY();
          $this->oPdf->MultiCell($iLarguraDisciplina, $iAlturaLinha, $oDisciplina->sDisciplina, 1, "L", $lZebra);
          $this->oPdf->SetXY($this->oPdf->GetLeftMargin() + $iLarguraDisciplina, $iYInicio );

          $this->oPdf->SetFont("arial", "", $iFonteGrade);

          foreach ($aEtapa as $oEtapa) {

            $sAproveitamento = ' - ';


            if ( isset($oEtapa->aDisciplinas[$oDisciplina->iDisciplina]) ) {

              if (  trim($oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->mAvaliacao) != ''
                 || ( trim($oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->mAvaliacao) == ''
                      && $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->sSituacao == 'AMPARADO' )
               ) {

                $sAproveitamento = $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->mAvaliacao;

                if( $oEtapa->aDisciplinas[$oDisciplina->iDisciplina]->sSituacao == 'AMPARADO' ) {
                  $sAproveitamento = 'AMPARO';
                }
              }
            }

            $this->oPdf->Cell(15, $iAlturaAproveitamento, $sAproveitamento, 1, 0, "C", $lZebra);
          }

          /**
           * Imprime as colunas em branco para disciplina
           */
          $iNumeroEtapaImpressa = count($aEtapa);
          if (count($aEtapa) < $iNumeroEtapasPagina) {

            for ($i = $iNumeroEtapaImpressa; $i < $iNumeroEtapasPagina; $i++) {
              $this->oPdf->Cell(15, $iAlturaAproveitamento, "  ", 1, 0, "C", $lZebra);
            }
          }
          $this->oPdf->ln(); // Quebra linha ao imprimir disciplina mais etapas
        }

        if ( $lImprimeLinhaComponentesCurricularesEmBranco ) {
          $this->imprimeLinhaEmBrancoComponenteCurricular($iTotalEtapas);
        }
      }
      $this->oPdf->Ln();
    }
  }

  /**
   * Imprime uma linha em branco contendo as colunas das etapas
   */
  private function imprimeLinhaEmBrancoComponenteCurricular($iTotalEtapas) {

    $iColunasEmBranco = self::NUMERO_ETAPAS_PAGINA - $iTotalEtapas;

    $this->oPdf->Cell( self::LARGURA_DISCIPLINA, 4, " - ", 1, 0, "", true );

    for ( $iContador = 0; $iContador < $iTotalEtapas; $iContador++ ) {
      $this->oPdf->Cell(  15, 4, " - ", 1, 0, "C", true );
    }

    for ( $iContador = 0; $iContador < $iColunasEmBranco; $iContador++ ) {
      $this->oPdf->Cell(  15, 4, " ", 1, 0, "C", true );
    }
    $this->oPdf->Ln();
  }

  /**
   * Cria a tabela de ComponentesCurriculares
   */
  public function criarTabelaResumoEtapas() {

    $iAlturaLinha    = RelatorioHistoricoEscolarRetrato::ALTURA_LINHA;
    $aDadosRelatorio = $this->montarEstruturaDeDados();
    $iFonteGrade     = $this->oParametros->fonte_grade_etapa;

    RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['escola'] = 58;
    RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['cidade'] = 40;

    if ( !$this->oParametros->exibe_turma ) {
      RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['escola'] += RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['turma'];
    }
    if ( !$this->oParametros->exibe_percentual_frequencia ) {
      RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['cidade'] += RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa['percentual_frequencia'];
    }

    $aLargura          = RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa;
    $aLabel            = RelatorioHistoricoEscolarRetrato::$aLabelColunaEtapa;
    $iAlturaTotalGrade = $iAlturaLinha * ( count($aDadosRelatorio) + 1 ); // + pois tem que escrever cabecalho
    $iAlturaDisponivel = $this->oPdf->getAvailHeight();

    if ( $iAlturaTotalGrade > $iAlturaDisponivel ) {

      $this->oPdf->AddPage();
      $this->escreveCabecalho();
    }

    /**
     * Impressão da grade
     */
    $this->oPdf->SetFont("arial", "B", 6);

    $iLinhasLabelTurma = 0;

    $this->oPdf->Cell( $aLargura['etapa'], $iAlturaLinha, $aLabel['etapa'], 1, 0, "C");
    $this->oPdf->Cell( $aLargura['ano'],   $iAlturaLinha, $aLabel['ano'],   1, 0, "C");
    $this->oPdf->Cell( $aLargura['dias'],  $iAlturaLinha, $aLabel['dias'],  1, 0, "C");

    if ( $this->oParametros->exibe_turma ) {
      $this->oPdf->Cell( $aLargura['turma'], $iAlturaLinha, $aLabel['turma'], 1, 0, "C");
    }

    $this->oPdf->Cell( $aLargura['carga_horaria'], $iAlturaLinha, $aLabel['carga_horaria'], 1, 0, "C");

    if ( $this->oParametros->exibe_percentual_frequencia ) {
      $this->oPdf->Cell( $aLargura['percentual_frequencia'], $iAlturaLinha, $aLabel['percentual_frequencia'], 1, 0, "C");
    }
    $this->oPdf->Cell( $aLargura['resultado'], $iAlturaLinha, $aLabel['resultado'], 1, 0, "C");
    $this->oPdf->Cell( $aLargura['escola'],  $iAlturaLinha, $aLabel['escola'],    1, 0, "C");
    $this->oPdf->Cell( $aLargura['cidade'],  $iAlturaLinha, $aLabel['cidade'],    1, 0, "C");
    $this->oPdf->Cell( $aLargura['uf'],        $iAlturaLinha, $aLabel['uf'],        1, 1, "C");

    $this->oPdf->SetFont("arial", "", $iFonteGrade);

    $iLinhaPadrao = RelatorioHistoricoEscolarRetrato::ALTURA_LINHA;

    foreach ( $aDadosRelatorio as $oDadosEtapa ) {
      $this->escreverResumoEtapas( $oDadosEtapa );
    }

    if ( $this->lExibirTodasEtapasCurso) {

      foreach ($this->aEtapasPosterior as $oEtapaPosterior) {
        $this->escreverResumoEtapas( $oEtapaPosterior );
      }
    }

    return;
  }

  /**
   * Organiza as disciplinas em uma segunda estrutura para facilitar impressão
   * @return multitype:stdClass
   */
  private function disciplinasCursadas() {

    $aDisciplinas = array();

    foreach ($this->aDadosOrganizados as $oEtapaCursada) {

      foreach ($oEtapaCursada->aDisicplinasEtapa as $oDisciplinaCursada) {

        if (array_key_exists($oDisciplinaCursada->sDisciplina, $aDisciplinas)) {
          continue;
        }
        $oDisciplina              = new stdClass();
        $oDisciplina->iDisciplina = $oDisciplinaCursada->iCadDisciplina;
        $oDisciplina->sDisciplina = $oDisciplinaCursada->sDisciplina;
        $aDisciplinas[$oDisciplinaCursada->sDisciplina] = $oDisciplina;
      }
    }

    ksort($aDisciplinas);
    return $aDisciplinas;
  }

  /**
   * Monta o quadro das observações
   * Ordem das informações
   * - Observação dos Parâmetros
   * - Convenções (Removido a pedido do Tiago)
   * - Observação do Histórico
   * - Aprovado pelo conselho
   * - Colocar na observação se os dados da trocou de série se houver
   * @return string
   */
  public function montaQuadroObservacao() {

    $sObsParametros = $this->oParametros->observacao;
    $sObsHistorico  = implode("\n", $this->aObservacaoHistorico);

    $sProAprovacaoComProgressao = "";
    if ($this->lAlunoTeveAprovacaoComProgressao) {
      $sProAprovacaoComProgressao .= " * = Aprovado com progressão parcial / Dependência";
    }

    $sObsEtapaHistorico       = implode( "\n", $this->getObservacaoHistoricoEtapa());
    $sObsAprovadoPeloConselho = $this->getObservacaoAprovadoPeloConselho();
    $sObsTrocaSerie           = $this->getObservacaoTrocaSerie();

    $sObservacao  = "Observações: ";

    $sObservacao .= !empty( $sObsParametros )           ? "{$sObsParametros}\n"             : '';
    $sObservacao .= !empty($sProAprovacaoComProgressao) ? "{$sProAprovacaoComProgressao}\n" : '';
    $sObservacao .= !empty($sObsHistorico)              ? "{$sObsHistorico}\n"              : '';
    $sObservacao .= !empty($sObsAprovadoPeloConselho)   ? "{$sObsAprovadoPeloConselho}\n"   : '';
    $sObservacao .= !empty($sObsTrocaSerie)             ? "{$sObsTrocaSerie}\n"             : '';
    $sObservacao .= "{$sObsEtapaHistorico}";

    /*PLUGIN DIARIO PROGRESSAO - ADICIONADO OBSERVAÇÕES DA EVASÃO DA PROGRESSÃO - NÃO APAGAR*/

    $this->oPdf->SetFontSize($this->oParametros->fonte_observacao);

    $iTotalLinhasObservacao  = $this->oPdf->NbLines(195, $sObservacao);
    $iAlturaLinhasObservacao = $iTotalLinhasObservacao * self::ALTURA_LINHA;
    $iAlturaDisponivel       = $this->oPdf->getAvailHeight() - 20;
    $iBordaMulticell         = 1;
    $iPosicaoFinalObservacao = 0;
    if ($iAlturaLinhasObservacao  <= $iAlturaDisponivel) {

      $iAlturaQuadro   = $this->oPdf->h - $this->oPdf->getY() - 40;
      $iBordaMulticell = 0;
      $this->oPdf->Rect($this->oPdf->getx(), $this->oPdf->GetY() + self::ALTURA_LINHA, 195, $iAlturaQuadro);
      $iPosicaoFinalObservacao   =  $this->oPdf->h - 38;
    }

    $this->oPdf->SetAutoPageBreak(true, 30);
    $this->oPdf->ln();
    $this->oPdf->setMulticellBreakPageFunction( array($this, "escreveCabecalho") );
    $this->oPdf->MultiCell(195, self::ALTURA_LINHA, $sObservacao, $iBordaMulticell, "L");
    $this->oPdf->SetAutoPageBreak(false);
    if ($iPosicaoFinalObservacao > 0) {
      $this->oPdf->sety($iPosicaoFinalObservacao);
    }
    return ;
  }

  /**
   * Retorna uma as convenções
   * @return string
   */
  private function getObservacaoConvencao() {

    $sConvencoes = "";
    if (!empty($this->iDisposicao) && $this->iDisposicao == 1) {

      $sConvencoes  = " Convenções: CH = Carga Horária RF = Resultado Final PL = Período Letivo ";
      $sConvencoes .= " ESC = Escola DL = Dias Letivos Aprov. = Aproveitamento";
    }

    return $sConvencoes;
  }

  /**
   * Retorna o nome do último curso cursado pelo aluno.
   */
  private function getNomeUltimoCurso() {

    $sCamposHist   = "*";
    $sWhereHist    = " ed61_i_aluno = {$this->oAluno->getCodigoAluno()}";
    $sOrderHist    = " ed47_v_nome ";

    $oDaoHistorico = new cl_historico();
    $sSqlHist      = $oDaoHistorico->sql_query("", $sCamposHist, $sOrderHist, $sWhereHist);
    $rsHist        = $oDaoHistorico->sql_record($sSqlHist);
    $iLinhasHist   = $oDaoHistorico->numrows;

    $sCurso        = "";
    /**
     * Aplicada mesma lógica do relátório
     */
    for ($iContHist = 0; $iContHist < $iLinhasHist; $iContHist++) {

      $oDadosHist     = db_utils::fieldsmemory($rsHist, $iContHist);
      if (!empty($oDadosHist->ed61_i_anoconc)) {
        $sCurso = $oDadosHist->ed29_c_descr;
      }

    }
    return $sCurso;
  }

  /**
   * Escreve a assinatura no Documento Definindo Posição Inicial
   *
   * @param String $sSecretario
   */
  private function escreverAssinatura($sTexto, $iPosicaoInicial) {

    if (empty($sTexto)) {
      return;
    }
    $iLarguraAssinatura = self::LARGURA_ASSINATURA;
    $aTexto             = explode("-", $sTexto);
    $sNome              = $aTexto[1];
    $sFuncao            = $aTexto[0].(trim($aTexto[2]) != "" ? " ($aTexto[2])":"");
    $sNomeCargo         = "{$sNome}\n {$sFuncao}";
    $iYAntesEscrever    = $this->oPdf->GetY();

    $this->oPdf->SetX($iPosicaoInicial);

    $this->oPdf->Line($this->oPdf->GetX(),
                      $this->oPdf->GetY(),
                      $this->oPdf->GetX() + $iLarguraAssinatura,
                      $this->oPdf->GetY());

    $this->oPdf->Ln(1);
    $this->oPdf->SetX($iPosicaoInicial);
    $this->oPdf->MultiCell($iLarguraAssinatura, 4, $sNomeCargo, 0, "C");
    $this->oPdf->SetY($iYAntesEscrever);
    return ;
  }

  /**
   * Escreve a data por extenso
   *
   * @return void
   */
  private function escreverDataPorExtenso() {

    $this->oPdf->Ln();
    $oData  = new DBDate(date("d/m/Y"));
    $sData  = "{$this->oEscola->getMunicipio()}, ";
    $sData .= $oData->dataPorExtenso();
    $this->oPdf->Cell(205, 4, $sData, 0, 1, "C");
    $this->oPdf->Ln();

  }

  /**
   * Define o Título do Relatório
   *
   * @param unknown $sTitulo
   */
  public function setTitulo($sTitulo) {

  	$this->sTituloRelatorio = $sTitulo;
  }

  /**
   * Escreve o Rodapé do Relatório
   *
   * @param String $sDiretor
   * @param String $sSecretario
   */
  public function escreverRodape( $sDiretor, $sSecretario ) {

    $this->oPdf->Ln();
    $iAlturaDisponivel = $this->oPdf->getAvailHeight() + $this->oPdf->bMargin;
    $iAlturaData       = 16;
    $iAlturaAssinatura = 16;

    $this->oPdf->setfont('arial', '', 6);
    $this->escreverDataPorExtenso();

    $this->escreverAssinatura($sSecretario, $this->oPdf->lMargin);
    $this->escreverAssinatura($sDiretor, 110);
    return;
  }

  private function escreverResumoEtapas( $oDadosEtapa ) {

    $iSubString = 18;
    if ($this->oPdf->FontSizePt > 6) {
      $iSubString = 15;
    }

    $aLargura           = RelatorioHistoricoEscolarRetrato::$aLarguraColunaEtapa;
    $iAlturaLinhaPadrao = RelatorioHistoricoEscolarRetrato::ALTURA_LINHA;

    /**
     * Calcula o maior número de linhas que será preciso para excrever uma descrição
     */
    $aLinhas = array();
    if ( $this->oParametros->exibe_turma ) {
      $aLinhas[] = $this->oPdf->NbLines($aLargura['turma'], $oDadosEtapa->sTurma);
    }

    $aLinhas[] = $this->oPdf->NbLines($aLargura['escola'], $oDadosEtapa->sEscola);
    $aLinhas[] = $this->oPdf->NbLines($aLargura['cidade'], $oDadosEtapa->sMunicipio);

    $iQuantidadeLinha = max($aLinhas) > 1 ? max($aLinhas) : 1;

    /**
     * Define a altura que a linha deve ter
     */
    $iAlturaLinha  = $iAlturaLinhaPadrao * $iQuantidadeLinha;
    $iLarguraTotal = RelatorioHistoricoEscolarRetrato::LARGURA_TOTAL;

    $iYInicial = $this->oPdf->GetY();
    $iXInicial = $this->oPdf->GetX();
    $this->oPdf->Cell($aLargura['etapa'], $iAlturaLinha, $oDadosEtapa->sEtapa, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);
    $this->oPdf->Cell($aLargura['ano'], $iAlturaLinha, $oDadosEtapa->iAno, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);
    $this->oPdf->Cell($aLargura['dias'], $iAlturaLinha, $oDadosEtapa->iDiasLetivos, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    if ( $this->oParametros->exibe_turma ) {

      $iYAtual = $this->oPdf->GetY();
      $iXAtual = $this->oPdf->GetX();
      $this->oPdf->MultiCell($aLargura['turma'], $iAlturaLinhaPadrao, $oDadosEtapa->sTurma, 0, 'L');
      $this->oPdf->setY( $iYAtual );
      $this->oPdf->SetX( $iXAtual + $aLargura['turma'] );
      $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);
    }

    $this->oPdf->Cell($aLargura['carga_horaria'], $iAlturaLinha, $oDadosEtapa->iCargaHoraria, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    if ( $this->oParametros->exibe_percentual_frequencia ) {

      $this->oPdf->Cell($aLargura['percentual_frequencia'], $iAlturaLinha, $oDadosEtapa->nPercentualFalta, 0, 0, 'R');
      $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);
    }

    $this->oPdf->Cell($aLargura['resultado'], $iAlturaLinha, $oDadosEtapa->sResultado, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    $iYAtual = $this->oPdf->GetY();
    $iXAtual = $this->oPdf->GetX();
    $this->oPdf->MultiCell($aLargura['escola'], $iAlturaLinhaPadrao, $oDadosEtapa->sEscola, 0, 'L');
    $this->oPdf->setY( $iYAtual );
    $this->oPdf->SetX( $iXAtual + $aLargura['escola'] );
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    $iYAtual = $this->oPdf->GetY();
    $iXAtual = $this->oPdf->GetX();
    $this->oPdf->MultiCell($aLargura['cidade'], $iAlturaLinhaPadrao, $oDadosEtapa->sMunicipio, 0, 'L');
    $this->oPdf->setY( $iYAtual );
    $this->oPdf->SetX( $iXAtual + $aLargura['cidade'] );
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    $this->oPdf->Cell($aLargura['uf'], $iAlturaLinha, $oDadosEtapa->sUF, 0, 0, 'C');
    $this->oPdf->Line( $this->oPdf->GetX(), $iYInicial, $this->oPdf->GetX(), $iYInicial + $iAlturaLinha);

    /**
     * Monta a grade utilizando line
     */
    $this->oPdf->Line( $iXInicial, $iYInicial, $iLarguraTotal, $iYInicial);

    $this->oPdf->SetY( $iYInicial + $iAlturaLinha );
    $this->oPdf->Line( $iXInicial,     $iYInicial,           $iXInicial,     $this->oPdf->getY());
    $this->oPdf->Line( $iLarguraTotal, $iYInicial,           $iLarguraTotal, $this->oPdf->getY());
    $this->oPdf->Line( $iXInicial,     $this->oPdf->getY() , $iLarguraTotal, $this->oPdf->getY());

  }

  /**
   * Define se deve exibir todas as etapas do curso ou somente as etapas cursadas
   * @param boolean $lExibirTodasEtapasCurso
   */
  public function setExibirTodasEtapasCurso( $lExibirTodasEtapasCurso ) {
    $this->lExibirTodasEtapasCurso = $lExibirTodasEtapasCurso;
  }
}