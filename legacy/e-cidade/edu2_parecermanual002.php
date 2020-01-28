<?
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

require_once("fpdf151/scpdf.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_escola_classe.php");
require_once("classes/db_diarioresultado_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_procresultado_classe.php");
require_once("classes/db_procavaliacao_classe.php");
require_once("classes/db_regenciaperiodo_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libdocumento.php");
require_once("std/DBDate.php");
$cldiarioresultado = new cl_diarioresultado;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocresultado   = new cl_procresultado;
$clprocavaliacao   = new cl_procavaliacao;
$clregenciaperiodo = new cl_regenciaperiodo;
$clmatricula       = new cl_matricula;
$clregencia        = new cl_regencia;
$clturma           = new cl_turma;
$clDBConfig        = new cl_db_config();
$clEscola          = new cl_escola();
$escola            = db_getsession("DB_coddepto");
$result            = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                         "ed57_i_codigo as turma,
                                                                          serie.ed11_i_codigo as etapa",
                                                                         "",
                                                                         " ed220_i_codigo = $turma"
                                                                        )
                                         );
db_fieldsmemory($result,0);
$result  = $clmatricula->sql_record($clmatricula->sql_query("",
                                                            "*",
                                                            "ed47_v_nome",
                                                            " ed60_i_aluno in ($alunos) AND ed60_i_turma = $turma"
                                                           )
                                   );

$oTurma = TurmaRepository::getTurmaByCodigo($turma);
$oEtapa = EtapaRepository::getEtapaByCodigo($etapa);

//1 - período, 2 - aulas dadas
if ($oTurma->getFormaCalculoCargaHoraria() == 1) {
  $sLabelTipoAula = "Aulas Dadas:";
} else {
  $sLabelTipoAula = "Dias Letivos:";
}

$oProcedimentoAvalicao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa($oEtapa);

$periodo = explode("|",$periodo);
if ($periodo[0] == 'R') {

  $oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($periodo[1]);

  /**
   * Procurar todas as avaliacoes em que a ordem da avaliacao é menor que o resultado
   */
  $aAvaliacoesProcedimento = $oProcedimentoAvalicao->getAvaliacoes();
  $iOrdemSequencia         = $oElementoAvaliacao->getOrdemSequencia();

  foreach ($aAvaliacoesProcedimento as $oAvaliacao) {

    if ($oAvaliacao->getOrdemSequencia() < $iOrdemSequencia) {
      $aAvaliacoes[] = $oAvaliacao;
    }
  }
} else {

  $oElementoAvaliacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($periodo[1]);
  $aAvaliacoes        = array($oElementoAvaliacao);
}

if ($clmatricula->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma matrícula para a turma selecionada<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
}

$pdf = new FPDF();
$pdf->Open();
$pdf->AliasNbPages();
$m0         = 9;
$m1         = 14;
$m2         = 18;
$m3         = 22;
$m4         = 26;
$m5         = 30;
$m6         = 33;
$m7         = 3;
$m8         = 5;
$a          = 5;
$f          = 190;
$r          = 33;
$alturahead = $pdf->setY(3);
for ($x = 0; $x < $clmatricula->numrows; $x++) {
  db_fieldsmemory($result,$x);

  db_inicio_transacao();
  $oMatricula = MatriculaRepository::getMatriculaByCodigo($ed60_i_codigo);
  $oDiario = $oMatricula->getDiarioDeClasse();
  db_fim_transacao(false);

  $iTotalFaltas = 0;
  $iTotalAulas  = 0;

  /**
   * Conta o total de aulas e faltas por período de avaliação
   */
  foreach ($aAvaliacoes as $oAvaliacao) {

    $oPeriodoAvaliacao = $oAvaliacao->getPeriodoAvaliacao();

    foreach ($oDiario->getDisciplinas() as $oDiarioDisciplina) {

      $iTotalFaltas += $oDiarioDisciplina->getTotalFaltasPorPeriodo($oPeriodoAvaliacao);
      $iTotalAulas  += $oDiarioDisciplina->getRegencia()->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
    }
  }


  /**
   * Dados Instituição
   */
  $sCamposInstit   = "nomeinst as nome,ender,munic,uf,telef,email,url,logo";
  $sSqlDadosInstit = $clDBConfig->sql_query_file(db_getsession('DB_instit'),$sCamposInstit);
  $rsDadosInstit   = db_query($sSqlDadosInstit);
  $oDadosInstit    = db_utils::fieldsMemory($rsDadosInstit,0);
  $url             = $oDadosInstit->url;
  $nome            = $oDadosInstit->nome;
  $sLogoInstit     = $oDadosInstit->logo;
  $munic           = $oDadosInstit->munic;

  /**
   * Dados Escola
   */
  $sCamposEscola     = "ed18_i_codigo,ed18_c_nome,j14_nome,ed18_i_numero,j13_descr,ed261_c_nome,ed260_c_sigla, ";
  $sCamposEscola    .= "ed18_c_email,ed18_c_logo, ed18_codigoreferencia";
  $sSqlDadosEscola   = $clEscola->sql_query_dados(db_getsession("DB_coddepto"),$sCamposEscola);
  $rsDadosEscola     = db_query($sSqlDadosEscola);
  $oDadosEscola      = db_utils::fieldsMemory($rsDadosEscola,0);
  $sNomeEscola       = $oDadosEscola->ed18_c_nome;
  $sLogoEscola       = $oDadosEscola->ed18_c_logo;
  $iCodigoEscola     = $oDadosEscola->ed18_i_codigo;
  $ruaescola         = $oDadosEscola->j14_nome;
  $numescola         = $oDadosEscola->ed18_i_numero;
  $bairroescola      = $oDadosEscola->j13_descr;
  $cidadeescola      = $oDadosEscola->ed261_c_nome;
  $estadoescola      = $oDadosEscola->ed260_c_sigla;
  $emailescola       = $oDadosEscola->ed18_c_email;
  $iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

  /**
   * Valida se a turma possui Código Referência e o adiciona na frente do nome.
   */
  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $sSqlTelefoneEscola = $clEscola->sql_query_telefone("","ed26_i_numero,ed26_i_ddd","","ed26_i_escola= $iCodigoEscola");
  $rsTelefoneEscola   = db_query($sSqlTelefoneEscola);
  $oTelefoneEscola    = db_utils::fieldsMemory($rsTelefoneEscola,0);
  $iTelefoneEscola    = $oTelefoneEscola->ed26_i_numero;
  $iDTelefone         = $oTelefoneEscola->ed26_i_ddd;

  $DadosCabecalho = $sNomeEscola. " (".$iDTelefone.")" .$iTelefoneEscola;

  if ($periodo[0] == "A") {

    $tp_per  = "A";
    $sql1    = $clprocavaliacao->sql_query("","ed09_c_descr as periodoselecionado",""," ed41_i_codigo = $periodo[1]");
    $result1 = $clprocavaliacao->sql_record($sql1);
    db_fieldsmemory($result1,0);

  } else {

    $tp_per  = "R";
    $sql1    = $clprocresultado->sql_query("","ed42_c_descr  as periodoselecionado",""," ed43_i_codigo = $periodo[1]");
    $result1 = $clprocresultado->sql_record($sql1);
    db_fieldsmemory($result1,0);

  }



  $pdf->setfillcolor(223);
  $head1 = "BOLETIM POR PARECER DESCRITIVO";
  $head2 = "Aluno: $ed47_v_nome";
  $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4 = "Calendário: $ed52_c_descr";
  $head5 = "Período: $periodoselecionado";
  $head6 = "Etapa: $ed11_c_descr";
  $head7 = "Turma: $ed57_c_descr";
  $head8 = "Matrícula: $ed60_i_codigo";
  if ($punico == "yes") {
    $order = "ed232_c_descr LIMIT 1";
  } else {
    $order = "ed232_c_descr";
  }
  if (strlen($nome) > 42 || strlen($sNomeEscola) > 42 ) {
    $TamFonteNome = 8;
  } else {
    $TamFonteNome = 9;
  }

  $iPosXLogoEscola = 180;

  $pdf->AddPage('P');
  $m0         = 9;
  $m1         = 14;
  $m2         = 18;
  $m3         = 22;
  $m4         = 26;
  $m5         = 30;
  $m6         = 33;
  $m7         = 3;
  $m8         = 5;
  $a          = 5;
  $f          = 190;
  $r          = 33;
  $alturahead = $pdf->setY(6);
  $pdf->setfillcolor(225);
  $pdf->SetFont('arial','b',7);
  $margemesquerda  = $pdf->lMargin;

  $oLibDocumento = new libdocumento(5001,null);

  if ($oLibDocumento->lErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$oLibDocumento->sMsgErro}");
  }

  $aParagrafo = $oLibDocumento->getDocParagrafos();

  foreach ($aParagrafo as $oParagrafo ) {
    eval($oParagrafo->oParag->db02_texto);
  }
  $result2 = $clregencia->sql_record($clregencia->sql_query("","*",$order," ed59_i_codigo in ($disciplinas) "));
  $linhas2 = $clregencia->numrows;
  for ($y = 0; $y < $linhas2; $y++) {

    db_fieldsmemory($result2,$y);
    $pdf->setfont('arial','b',7);
    if ($punico == "yes") {
      $titulo = "PARECER ÚNICO";
    } else {
      $titulo = "Disciplina: $ed232_c_descr";
    }
    $pdf->cell(190,4,$titulo,1,1,"L",1);
    $pdf->cell(190,4,"","LR",1,"L",0);
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(30,4,$sLabelTipoAula,0,0,"L",0);
    $pdf->cell(150,4, $iTotalAulas,"R",1,"L",0);
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(30,4,"N° Faltas:",0,0,"L",0);
    $pdf->cell(150,4,$iTotalFaltas,"R",1,"L",0);
    $pdf->cell(190,4,"","LR",1,"L",0);
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(170,4,"PARECER DESCRITIVO:",0,0,"L",0);
    $pdf->cell(10,4,"","R",1,"L",0);

    for ($t = 0; $t < 30; $t++) {

      $pdf->cell(10,6,"","L",0,"L",0);
      $pdf->cell(170,6,"","B",0,"L",0);
      $pdf->cell(10,6,"","R",1,"L",0);

    }

    $pdf->cell(190,4,"","LR",1,"L",0);
    $pdf->cell(190,4,"","LR",1,"C",0);
    $pdf->cell(190,4,"__________________________________________________","LR",1,"C",0);
    $pdf->cell(190,4,"Assinatura do Regente","LR",1,"C",0);
    $pdf->cell(190,10,"","LBR",1,"L",0);

  }
}
$pdf->Output();
?>