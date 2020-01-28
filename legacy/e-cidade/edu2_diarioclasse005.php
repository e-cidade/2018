<?
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

require_once ("fpdf151/pdfwebseller.php");
require_once ("libs/db_stdlibwebseller.php");

$clmatricula         = new cl_matricula;
$clturma             = new cl_turma;
$clescola            = new cl_escola;
$clprocavaliacao     = new cl_procavaliacao;
$clregenciahorario   = new cl_regenciahorario;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clperiodocalendario = new cl_periodocalendario;

$escola   = db_getsession("DB_coddepto");
$discglob = false;

$sCamposTurma  = "ed57_i_codigo, ed57_i_calendario, ed57_c_descr, ed29_i_codigo, ed29_c_descr, ed52_c_descr";
$sCamposTurma .= ", fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descr";
$sSqlTurma     = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$turma}" );
$result        = $clturma->sql_record( $sSqlTurma );

if ( $clturma->numrows == 0 ) {?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}

function Abreviar( $nome, $max ) {

  if ( strlen( trim( $nome ) ) > $max ) {

    $strinv   = strrev(trim($nome));
    $ultnome  = substr($strinv, 0, strpos($strinv, " "));
    $ultnome  = strrev($ultnome);
    $nome     = strrev($strinv);
    $prinome  = substr($nome, 0, strpos($nome, " "));
    $nomes    = strtok($nome, " ");
    $iniciais = "";

    while($nomes):

      if (    ($nomes == 'E')
           || ($nomes == 'DE')
           || ($nomes == 'DOS')
           || ($nomes == 'DAS')
           || ($nomes == 'DA')
           || ($nomes == 'DO')) {

        $iniciais .= " ".$nomes;
        $nomes     = strtok(" ");
      } elseif ( ($nomes == $ultnome ) || ( $nomes == $prinome ) ) {

        $nome  = "";
        $nomes = strtok(" ");
      } else {

        $iniciais .= " ".$nomes[0].".";
        $nomes     = strtok(" ");
      }
    endwhile;

    $nome  =  $prinome;
    $nome .= $iniciais;
    $nome .= " ".$ultnome;
  }

  return trim($nome);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$linhas = $clturma->numrows;

for ( $x = 0; $x < $linhas; $x++ ) {

  db_fieldsmemory($result, $x);

  $sql0    = $clprocavaliacao->sql_query( "", "ed09_i_codigo, ed09_c_descr", "", "ed41_i_codigo = {$periodo}" );
  $result0 = $clprocavaliacao->sql_record( $sql0 );
  db_fieldsmemory($result0, 0);

  $sCamposPeriodoCalendario = "ed52_i_codigo, ed52_c_aulasabado, ed53_d_inicio, ed53_d_fim";
  $sWherePeriodoCalendario  = "ed53_i_calendario = {$ed57_i_calendario} AND ed53_i_periodoavaliacao = {$ed09_i_codigo}";
  $sSqlPeriodoCalendario    = $clperiodocalendario->sql_query( "", $sCamposPeriodoCalendario, "", $sWherePeriodoCalendario );
  $result22                 = $clperiodocalendario->sql_record( $sSqlPeriodoCalendario );

  if ( pg_num_rows( $result22 ) == 0) {

    $sMensagemErro = "Período de avaliação e período do calendário selecionados, não equivalentes.";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  }

  db_fieldsmemory($result22, 0);

  $dataperiodo = $ed09_c_descr." - ".db_formatar($ed53_d_inicio, 'd')." à ".db_formatar($ed53_d_fim, 'd');

  if ( $informadiasletivos == "S" ) {
    $colunas = DiasLetivos( $ed53_d_inicio, $ed53_d_fim, $ed52_c_aulasabado, $ed52_i_codigo, 1 );
  } else {
    $colunas = $qtdecolunas;
  }

  $larguraindiv   = round( 175 / $colunas, 1 );
  $larguracolunas = $colunas * $larguraindiv;

  $pdf->setfillcolor(235);
  $head1 = "DIÁRIO DE CLASSE";
  $head2 = "Turma: $ed57_c_descr";
  $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4 = "Calendário: $ed52_c_descr";
  $head5 = "Etapa: $ed11_c_descr";
  $head6 = "Período: $ed09_c_descr";

  $pdf->addpage('L');
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(105 + $larguracolunas, 4, @$dataperiodo, 0, 1, "C", 1);
  $pdf->cell(60, 4, "",      1, 0, "C", 0);
  $pdf->cell(10, 4, "Mês >", 1, 0, "R", 0);

  if ( $informadiasletivos == "S" ) {

    $array_meses = DiasLetivos($ed53_d_inicio, $ed53_d_fim, $ed52_c_aulasabado, $ed52_i_codigo, 3);
    $pdf->setfont('arial', 'b', 7);

    for ( $r = 0; $r < count($array_meses); $r++ ) {

      $qtd_diasmes = explode(",", $array_meses[$r]);
      $pdf->cell($larguraindiv * $qtd_diasmes[1], 4, $qtd_diasmes[0], 1, 0, "C", 0);
    }
  } else {
    $pdf->cell($larguracolunas, 4, "", 1, 0, "R", 0);
  }

  $pdf->setfont('arial','b',8);
  $pdf->cell( 20, 4, "Avaliações",            1, 0, "R", 0 );
  $pdf->cell( 15, 4, "",                      1, 1, "R", 0 );
  $pdf->cell( 5,  4, "N°",                    1, 0, "C", 0 );
  $pdf->cell( 55, 4, "Nome do Aluno / Etapa", 1, 0, "C", 0 );
  $pdf->cell( 10, 4, "Dia >",                 1, 0, "R", 0 );

  if ( $informadiasletivos == "S" ) {

    $n_dias = DiasLetivos( $ed53_d_inicio, $ed53_d_fim, $ed52_c_aulasabado, $ed52_i_codigo, 2 );
    $pdf->setfont( 'arial', 'b', 6 );

    for ( $r = 0; $r < count( $n_dias ); $r++ ) {

      $umdia = explode("-",$n_dias[$r]);
      $pdf->cell( $larguraindiv, 4, $umdia[0], 1, 0, "C", 0 );
    }
  } else {

    for ( $r = 0; $r < $colunas; $r++ ) {
      $pdf->cell( $larguraindiv, 4, "", 1, 0, "C", 0 );
    }
  }

  $pdf->setfont( 'arial', 'b', 8 );
  $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
  $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
  $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
  $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
  $pdf->cell(  5, 4, "N°", 1, 0, "C", 0 );
  $pdf->cell( 10, 4, "Ft", 1, 1, "C", 0 );

  $condicao = "";
  if ($active == "SIM") {
   $condicao .= "and ed60_c_situacao IN ('MATRICULADO', 'TROCA DE TURMA')";
  }

  if ($trocaTurma == 1) {
   $condicao .= " and ed60_c_situacao != 'TROCA DE TURMA'";
  }

  $sCamposMatricula = "ed60_i_aluno, ed60_i_codigo, ed60_i_numaluno, ed47_v_nome, ed60_c_situacao, ed11_c_abrev";
  $sWhereMatricula  = "ed60_i_turma = {$ed57_i_codigo} {$condicao}";
  $sOrderMatricula  = "ed11_i_sequencia, ed60_i_numaluno, to_ascii(ed47_v_nome), ed60_c_ativa";
  $sql1             = $clmatricula->sql_query( "", $sCamposMatricula, $sOrderMatricula, $sWhereMatricula );
  $result1          = $clmatricula->sql_record( $sql1 );

  $limite     = 33;
  $cont       = 0;
  $cont_geral = 0;
  $cor1       = 0;
  $cor2       = 1;
  $cor        = "";

  for ( $y = 0; $y < $clmatricula->numrows; $y++ ) {

    db_fieldsmemory( $result1, $y );

    /**
     * Caso tenha sido selecionado turno diferente de "TODOS" ( opção apresentada somente para turmas de ensino infantil ),
     * percorre os turnos da matrícula, verificando se há vínculo com o turno
     * Caso contrário, não imprime o aluno no relatório
     */
    if ( isset($iTurno) && $iTurno != 0 ) {

      $oMatricula      = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
      $aTurnos         = $oMatricula->getTurnosVinculados();
      $lTurnoVinculado = false;

      foreach ( $aTurnos as $oTurnoReferente ) {

        if ( $iTurno == $oTurnoReferente->ed336_turnoreferente ) {
          $lTurnoVinculado = true;
        }
      }

      if ( !$lTurnoVinculado ) {
        continue;
      }
    }

    $cont++;
    $cont_geral++;

    if ( $cor == $cor1 ) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $amparo = "";

    $pdf->setfont( 'arial', '', 6 );
    $pdf->cell( 5, 4, $ed60_i_numaluno, 1, 0, "C", 0 );

    if ( strlen( trim( $ed47_v_nome ) ) > 43 ) {
      $pdf->setfont( 'arial', '', 5 );
    }

    $pdf->cell( 55, 4, substr($ed47_v_nome, 0, 47), "LBT", 0, "L", 0 );
    $pdf->setfont('arial', '', 6);

    $pdf->cell( 10, 4, $ed11_c_abrev, "RBT", 0, "R", 0 );

    if ( $amparo == "S" ) {

      $pdf->setfont( 'arial', 'b', 11 );

      if ( $ed81_i_justificativa != "" ) {
        $pdf->cell( $larguraindiv * $colunas, 4, "AMPARADO", 1, 0, "C", 0 );
      } else {
        $pdf->cell( $larguraindiv * $colunas, 4, $ed250_c_abrev, 1, 0, "C", 0 );
      }

      $pdf->setfont( 'arial', 'b', 8 );
    } else {

      if( trim( $ed60_c_situacao ) != "MATRICULADO" ) {

        $pdf->setfont( 'arial', 'b', 11 );
        $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

        if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
          $sSituacao = "TRANSFERIDO";
        }

        $pdf->cell( $larguraindiv * $colunas, 4, $sSituacao, 1, 0, "C", 0 );
        $pdf->setfont( 'arial', 'b', 8 );
      } else {

        $pdf->setfont( 'arial', 'b', 8 );
        $at = $pdf->getY();
        $lg = $pdf->getX();

        for ( $r = 0; $r < $colunas; $r++ ) {

          $pdf->setfont( 'arial', 'b', 12 );
          $pdf->cell( $larguraindiv, 4, "", 1, 0, "C", 0 );
          $pdf->Text( $lg + ( $larguraindiv * 30 / 100 ), $at + 2, "." );
          $lg = $pdf->getX();
        }

        $pdf->setfont( 'arial', 'b', 8 );
      }
    }

    if ( trim( $ed60_c_situacao ) == "MATRICULADO" ) {

      for ( $r = 0; $r < 4; $r++ ) {
        $pdf->cell( 5, 4, "", 1, 0, "C", 0 );
      }

      $pdf->cell(  5, 4, $ed60_i_numaluno, 1, 0, "C", 0 );
      $pdf->cell( 10, 4, "",               1, 1, "C", 0 );
    } else {

      $pdf->setfont( 'arial', 'b', 11 );
      $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

      if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
        $sSituacao = "TRANSFERIDO";
      }

      $pdf->cell( 35, 4, $sSituacao, 1, 1, "C", 0 );
      $pdf->setfont( 'arial', 'b', 8 );
    }

    if ( $cont == $limite && $cont_geral < $clmatricula->numrows ) {

      $pdf->setfont( 'arial', 'b', 8 );
      $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Entregue em _____/_____/_____ POR_______________________",  1, 0, "L", 0 );
      $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Revisado em _____/_____/_____ POR_______________________",  1, 1, "L", 0 );
      $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Processado em _____/_____/_____ POR_____________________",  1, 0, "L", 0 );
      $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Assinatura do professor:_________________________________", 1, 1, "L", 0 );

      $pdf->line( 10, 47, ( $larguracolunas + 115 ), 47 );

      $pdf->addpage("L");
      $pdf->cell( 105 + $larguracolunas, 4, @$dataperiodo, 0, 1, "C", 1 );
      $pdf->cell( 60, 4, "",      1, 0, "C", 0 );
      $pdf->cell( 10, 4, "Mês >", 1, 0, "R", 0 );

      if($informadiasletivos=="S"){

        $array_meses = DiasLetivos( $ed53_d_inicio, $ed53_d_fim, $ed52_c_aulasabado, $ed52_i_codigo, 3 );

        $pdf->setfont( 'arial', 'b', 7 );

        for ( $r = 0; $r < count( $array_meses ); $r++ ) {

          $qtd_diasmes = explode( ",", $array_meses[$r] );
          $pdf->cell( $larguraindiv * $qtd_diasmes[1], 4, $qtd_diasmes[0], 1, 0, "C", 0 );
        }
      } else {
        $pdf->cell( $larguracolunas, 4, "", 1, 0, "R", 0 );
      }

      $pdf->setfont( 'arial', 'b', 8 );
      $pdf->cell( 20, 4, "Avaliações",            1, 0, "R", 0 );
      $pdf->cell( 15, 4, "",                      1, 1, "R", 0 );
      $pdf->cell(  5, 4, "N°",                    1, 0, "C", 0 );
      $pdf->cell( 55, 4, "Nome do Aluno / Etapa", 1, 0, "C", 0 );
      $pdf->cell( 10, 4, "Dia >",                 1, 0, "R", 0 );

      if ( $informadiasletivos == "S" ) {

        $n_dias = DiasLetivos( $ed53_d_inicio, $ed53_d_fim, $ed52_c_aulasabado, $ed52_i_codigo, 2 );

        $pdf->setfont( 'arial', 'b', 6 );

        for ( $r = 0; $r < count( $n_dias ); $r++ ) {

          $umdia = explode( "-", $n_dias[$r] );
          $pdf->cell( $larguraindiv, 4, $umdia[0], 1, 0, "C", 0 );
        }
      } else {

        for ( $r = 0; $r < $colunas; $r++ ) {
          $pdf->cell( $larguraindiv, 4, "", 1, 0, "C", 0 );
        }
      }

      $pdf->setfont( 'arial', 'b', 8 );
      $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
      $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
      $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
      $pdf->cell(  5, 4, "",   1, 0, "C", 0 );
      $pdf->cell(  5, 4, "N°", 1, 0, "C", 0 );
      $pdf->cell( 10, 4, "Ft", 1, 1, "C", 0 );
      $cont = 0;
    }
  }

  $termino = $pdf->getY();

  for ( $t = $cont; $t < $limite; $t++ ) {

    if ( $cor == $cor1 ) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $pdf->cell(  5, 4, "",     1, 0, "C", 0 );
    $pdf->cell( 55, 4, "", "LBT", 0, "C", 0 );
    $pdf->cell( 10, 4, "", "RBT", 0, "C", 0 );

    $pdf->setfont( 'arial', 'b', 8 );
    $at = $pdf->getY();
    $lg = $pdf->getX();

    for ( $r = 0; $r < $colunas; $r++ ) {

      $pdf->setfont( 'arial', 'b', 12 );
      $pdf->cell( $larguraindiv, 4, "", 1, 0, "C", 0 );
      $pdf->Text( $lg + ( $larguraindiv * 30 / 100 ), $at + 2, "." );
      $lg = $pdf->getX();
    }

    $pdf->setfont( 'arial', 'b', 8 );

    for ( $r = 0; $r < 4; $r++ ) {
      $pdf->cell( 5, 4, "", 1, 0, "C", 0 );
    }

    $pdf->cell(  5, 4, "", 1, 0, "C", 0 );
    $pdf->cell( 10, 4, "", 1, 1, "C", 0 );
  }

  $pdf->setfont( 'arial', 'b', 8 );
  $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Entregue em _____/_____/_____ POR_______________________",  1, 0, "L", 0 );
  $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Revisado em _____/_____/_____ POR_______________________",  1, 1, "L", 0 );
  $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Processado em _____/_____/_____ POR_____________________",  1, 0, "L", 0 );
  $pdf->cell( ( $larguracolunas + 105 ) / 2, 5, "Assinatura do professor:_________________________________", 1, 1, "L", 0 );

  $pdf->line( 10, 47, ( $larguracolunas + 115 ), 47 );
}

$pdf->Output();
?>