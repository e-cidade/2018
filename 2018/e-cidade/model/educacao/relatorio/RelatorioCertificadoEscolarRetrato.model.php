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
 * Renderiza o certificado escolar no formato de retrato de acordo com os parâmetros
 *
 * @package educacao
 * @subpackage relatorio
 * @author andrio.costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.11 $
 */
class RelatorioCertificadoEscolarRetrato extends RelatorioHistoricoEscolarRetrato {


  public function __construct( FPDF $oPdf, Aluno $oAluno, Escola $oEscola, $iTipoRelatorio, $lExibirReclassificacao) {

    parent::__construct($oPdf, $oAluno, $oEscola, $iTipoRelatorio, $lExibirReclassificacao);
    $this->setExibirSomenteCursosEncerrados(true);
    $this->setTitulo("Certificado Escolar");
  }

  /**
   * Monta o quadro das observações
   * Ordem das informações
   * - Observação dos Parâmetros
   * - Observação do Histórico
   * @return string
   */
  public function montaQuadroObservacao() {

    $sObsParametros = $this->oParametros->observacao;
    $sObsHistorico  = implode("\n", $this->aObservacaoHistorico);

    $sObservacao  = "Observações: ";
    $sObservacao .= "{$sObsParametros}\n";
    $sObservacao .= "{$sObsHistorico}\n";

    $sObservacao .= "{$this->getObservacaoAprovadoPeloConselho()}\n";
    $sObservacao .= $this->getObservacaoTrocaSerie();

    $iTotalLinhasObservacao  = $this->oPdf->NbLines(195, $sObservacao);
    $iAlturaLinhasObservacao = $iTotalLinhasObservacao * self::ALTURA_LINHA;
    $iAlturaDisponivel       = $this->oPdf->getAvailHeight() - 50;
    $iBordaMulticell         = 1;
    $iPosicaoFinalObservacao = 0;
    if ($iAlturaLinhasObservacao <= $iAlturaDisponivel) {

      $iAlturaQuadro   = $this->oPdf->h - $this->oPdf->getY() - 55;
      $iBordaMulticell = 0;
      $this->oPdf->Rect($this->oPdf->getx(), $this->oPdf->GetY() + self::ALTURA_LINHA, 195, $iAlturaQuadro);
      $iPosicaoFinalObservacao   =  $this->oPdf->h - 50;
    }

    $this->oPdf->SetAutoPageBreak(true, 25);
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
   * Monta o quadro das informações do certificado
   */
  public function montarQuadroCertificado() {

    $sWhere  = "     ed61_i_aluno = {$this->oAluno->getCodigoAluno()} ";
    $sWhere .= " and ed61_i_anoconc is not null ";

    $oDaoHistorico = new cl_historico();
    $sSqlCurso     = $oDaoHistorico->sql_query(null, "ed29_c_descr, ed61_i_anoconc", null, $sWhere);
    $rsCurso       = $oDaoHistorico->sql_record($sSqlCurso);

    $oErro = new stdClass();
    $oErro->sNome = $this->oAluno->getNome();

    if ($oDaoHistorico->numrows == 0) {
    	db_redireciona("db_erros.php?fechar=true&db_erro="._M(self::MENSAGEM."aluno_nao_possui_curso_concluido", $oErro));
    }

    $iAlturaDisponivel = $this->oPdf->getAvailHeight();

    if ($iAlturaDisponivel < 30) {
    	$this->escreveCabecalho();
    }

    $oDadosConclusao = db_utils::fieldsMemory($rsCurso, 0);

    $sMsg  = "    Certifico que o(a) aluno(a) {$this->oAluno->getNome()} concluiu {$oDadosConclusao->ed29_c_descr}";
    $sMsg .= " no ano de {$oDadosConclusao->ed61_i_anoconc}, nos termos da Lei 9.394 de 20 de dezembro de 1996, ";
    $sMsg .= "Art. 24, Inciso VII e Regimento Escolar, tendo obtido os resultados constantes neste certificado.";

    $this->oPdf->ln(1);
    $iYAntes = $this->oPdf->GetY();
    $this->oPdf->SetFont("Arial", "", 8);
    $this->oPdf->Cell(195, self::ALTURA_LINHA, "Certificado de Conclusão:", 0, 1, "C");
    $this->oPdf->setMulticellBreakPageFunction( array($this, "escreveCabecalho") );
    $this->oPdf->MultiCell(195, self::ALTURA_LINHA, $sMsg, 0, "J");

    $nLinhas = $this->oPdf->NbLines(195, $sMsg);

    $this->oPdf->Rect($this->oPdf->lMargin, $iYAntes, 195, self::ALTURA_LINHA + ($nLinhas * self::ALTURA_LINHA));
    return ;
  }

}