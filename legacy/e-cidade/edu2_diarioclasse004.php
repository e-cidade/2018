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

include("fpdf151/pdfwebseller.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_matricula_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_procresultado_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_regenciaperiodo_classe.php");
include("classes/db_periodocalendario_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_avalcompoeres_classe.php");
include("classes/db_abonofalta_classe.php");
include("classes/db_edu_parametros_classe.php");
include("classes/db_turma_classe.php");
$clmatricula         = new cl_matricula;
$clregencia          = new cl_regencia;
$clescola            = new cl_escola;
$clprocavaliacao     = new cl_procavaliacao;
$clprocresultado     = new cl_procresultado;
$clregenciahorario   = new cl_regenciahorario;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clperiodocalendario = new cl_periodocalendario;
$cldiarioavaliacao   = new cl_diarioavaliacao;
$clavalcompoeres     = new cl_avalcompoeres;
$clabonofalta        = new cl_abonofalta;
$cledu_parametros    = new cl_edu_parametros;
$clturma             = new cl_turma;
$escola              = db_getsession("DB_coddepto");
$resultedu           = eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$discglob            = false;
$sSqlTurma           = $clturma->sql_query_turmaserie("","ed52_i_ano as ano_calendario",""," ed220_i_codigo = $turma");
$sResultTurma        = $clturma->sql_record($sSqlTurma);
db_fieldsmemory($sResultTurma,0);
$sSqlParametros      = $cledu_parametros->sql_query("","ed233_c_database",""," ed233_i_escola = $escola");
$sResultParametros   = $cledu_parametros->sql_record($sSqlParametros);

if ($cledu_parametros->numrows>0) {

  db_fieldsmemory($sResultParametros,0);
  if (!strstr($ed233_c_database,"/")) {
    ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
         deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
         Valor atual do parâmetro: <?=trim($ed233_c_database)==""?"Não informado":$ed233_c_database?><br><br></b>
         <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
    <?
    exit;
  }
  $database     = explode("/",$ed233_c_database);
  $dia_database = $database[0];
  $mes_database = $database[1];
  if (@!checkdate($mes_database,$dia_database,$ano_calendario)) {

    echo "<table width='100%'>";
    echo " <tr>";
    echo "  <td align='center'>";
    echo "   <font color='#FF0000' face='arial'>";
    echo "    <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>";
    echo "       deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>";
    echo "       Valor atual do parâmetro: $ed233_c_database<br>";
    echo "       Data Base para Cálculo Idade: $dia_database.'/'.$mes_database.'/'.$ano_calendario (Data Inválida)";
    echo "       <br><br></b><input type='button' value='Fechar' onclick='window.close()'>";
    echo "   </font>";
    echo "  </td>";
    echo " </tr>";
    echo "</table>";
    exit;

  }

  $databasecalc  = $ano_calendario."-".(strlen($mes_database)==1?"0".$mes_database:$mes_database);
  $databasecalc .= "-".(strlen($dia_database)==1?"0".$dia_database:$dia_database);

} else {
  $databasecalc = $ano_calendario."-12-31";
}

$result = $clregencia->sql_record($clregencia->sql_query("","*","ed59_i_ordenacao"," ed59_i_codigo in ($disciplinas)"));
if ($clregencia->numrows == 0) {

  echo "<table width='100%'>";
  echo " <tr>";
  echo "  <td align='center'>";
  echo "   <font color='#FF0000' face='arial'>";
  echo "    <b>Nenhuma matrícula para a turma selecionada<br>";
  echo "    <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "   </font>";
  echo "  </td>";
  echo " </tr>";
  echo "</table>";
  exit;

}

$sCamposProcResultado  = " ed43_i_codigo,ed37_c_tipo as tipores,ed43_c_arredmedia as arredmedia,";
$sCamposProcResultado .= " ed43_c_minimoaprov as minimoaprovres, ed43_c_obtencao as obtencao";
$sWhereProcResultado   = " ed43_c_geraresultado = 'S' AND ";
$sWhereProcResultado  .= " ed43_i_procedimento = ".pg_result($result,0,'ed220_i_procedimento')."";
$sSqlProcResultado     = $clprocresultado->sql_query("",$sCamposProcResultado,"",$sWhereProcResultado);
$sResultProcResultado  = $clprocresultado->sql_record($sSqlProcResultado);

if ($clprocresultado->numrows == 0) {

  echo "<table width='100%'>";
  echo " <tr>";
  echo "  <td align='center'>";
  echo "   <font color='#FF0000' face='arial'>";
  echo "    <b>Nenhum resultado do procedimento de avaliação desta turma tem a opção de gerar resultado final!<b><br>";
  echo "    <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "   </font>";
  echo "  </td>";
  echo " </tr> ";
  echo "</table>";
  exit;

} else {
 db_fieldsmemory($sResultProcResultado,0);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$cont_geral = 0;
$iLinhas    = $clregencia->numrows;
for ($x = 0; $x < $iLinhas; $x++) {

  db_fieldsmemory($result,$x);
  $sWhereAvalComperes  = " ed43_c_geraresultado = 'S' AND ed43_i_procedimento = $ed220_i_procedimento";
  $sSqlAvalComperes    = $clavalcompoeres->sql_query("","count(*) as qtdaval","",$sWhereAvalComperes);
  $sResultAvalComperes = $clavalcompoeres->sql_record($sSqlAvalComperes);
  db_fieldsmemory($sResultAvalComperes,0);

  $sWhereProcAvaliacao  = " ed09_c_somach = 'S' AND ed41_i_procedimento = $ed220_i_procedimento";
  $sSqlProcAvaliacao    = $clprocavaliacao->sql_query("","min(ed41_i_sequencia) as priaval","",$sWhereProcAvaliacao);
  $sResultProcAvaliacao = $clprocavaliacao->sql_record($sSqlProcAvaliacao);
  db_fieldsmemory($sResultProcAvaliacao,0);

  $sWhereProcAval  =  " ed09_c_somach = 'S' AND ed41_i_procedimento = $ed220_i_procedimento";
  $sSqlProcAval    = $clprocavaliacao->sql_query("","max(ed41_i_sequencia) as ultaval","",$sWhereProcAval);
  $sResultProcAval = $clprocavaliacao->sql_record($sSqlProcAval);
  db_fieldsmemory($sResultProcAval,0);

  $sCamposProcAvali = "ed37_c_tipo,ed09_i_codigo,ed09_c_descr,ed41_i_sequencia";
  $sSqlProcAvali    = $clprocavaliacao->sql_query("",$sCamposProcAvali,""," ed41_i_codigo = $periodo");
  $sResultProcAvali = $clprocavaliacao->sql_record($sSqlProcAvali);
  db_fieldsmemory($sResultProcAvali,0);

  /**
   * Variável criada para validar o tipo da avaliação do procedimento, pois a $ed37_c_tipo é sobrescrita mais abaixo,
   * gerando erro no cálculo da nota projetada
   */
  $sTipo = db_utils::fieldsMemory($sResultProcAvali, 0)->ed37_c_tipo;

  $sCamposPerCalendario     = "ed52_i_codigo,ed52_c_aulasabado,ed53_d_inicio,ed53_d_fim";
  $sWherePerCalendario      = " ed53_i_calendario = $ed57_i_calendario AND ed53_i_periodoavaliacao = $ed09_i_codigo";
  $sSqlPeriodoCalendario    = $clperiodocalendario->sql_query("",$sCamposPerCalendario,"",$sWherePerCalendario);
  $sResultPeriodoCalendario = $clperiodocalendario->sql_record($sSqlPeriodoCalendario);

  if ( pg_num_rows( $sResultPeriodoCalendario ) == 0) {

    $sMensagemErro = "Período de avaliação e período do calendário selecionados, não equivalentes.";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  }

  db_fieldsmemory($sResultPeriodoCalendario,0);

  $dataperiodo            = $ed09_c_descr." - ".db_formatar($ed53_d_inicio,'d')." à ".db_formatar($ed53_d_fim,'d');
  $sCamposRegHorario      = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente";
  $sSqlRegenciaHorario    = $clregenciahorario->sql_query("",$sCamposRegHorario,""," ed58_i_regencia = $ed59_i_codigo and ed58_ativo is true  ");
  $sResultRegenciaHorario = $clregenciahorario->sql_record($sSqlRegenciaHorario);

  if ($clregenciahorario->numrows > 0) {
    db_fieldsmemory($sResultRegenciaHorario,0);
  } else {
    $regente = "";
  }

  $sWhereRegPeriodo = " ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $periodo";
  $sSqlRegPeriodo   = $clregenciaperiodo->sql_query("","ed78_i_aulasdadas as aulas","",$sWhereRegPeriodo);
  $sResultRegPriodo = $clregenciaperiodo->sql_record($sSqlRegPeriodo);

  if ($clregenciaperiodo->numrows > 0) {
    db_fieldsmemory($sResultRegPriodo,0);
  } else {
    $aulas = "";
  }
  if ($informadiasletivos == "S") {
    $colunas = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,1);
  } else {
    $colunas = $qtdecolunas;
  }
  $larguraindiv   = round(190/$colunas,1);
  $larguracolunas = $colunas*$larguraindiv;
  $pdf->setfillcolor(235);
  $head1 = "DIÁRIO DE CLASSE";
  $head2 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head3 = "Calendário: $ed52_c_descr";
  $head4 = "Etapa: $ed11_c_descr";
  $head5 = "Período: $ed09_c_descr";
  $head7 = "Disciplina: $ed232_c_descr";
  $head8 = "Regente: $regente";
  $head6 = "Turma: $ed57_c_descr";
  $head9 = "Aulas Dadas: $aulas";

 ////////////Pagina 1 - Presenï¿½as

 $pdf->addpage('L');
 $pdf->cell(80,4,"",1,0,"C",0);
 $pdf->cell(10,4,"Mês >",1,0,"R",0);
 if ($informadiasletivos == "S") {

   $array_meses = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,3);
   $pdf->setfont('arial','b',7);
   for ($r = 0; $r < count($array_meses); $r++) {

     $qtd_diasmes = explode(",",$array_meses[$r]);
     $pdf->cell($larguraindiv*$qtd_diasmes[1],4,$qtd_diasmes[0],1,0,"C",0);

   }

 } else {
   $pdf->cell($larguracolunas,4,"",1,0,"R",0);
 }
 $pdf->setfont('arial','b',8);
 $pdf->cell(1,4,"",1,1,"R",0);
 $pdf->setfont('arial','',8);
 $pdf->cell(5,4,"Nº",1,0,"C",0);
 $pdf->cell(75,4,"Nome do Aluno",1,0,"C",0);
 $pdf->setfont('arial','b',8);
 $pdf->cell(10,4,"Dia >",1,0,"R",0);
 if ($informadiasletivos == "S") {

   $n_dias = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,2);
   $pdf->setfont('arial','b',6);

   for ($r = 0; $r < count($n_dias); $r++) {

     $umdia = explode("-",$n_dias[$r]);
     $pdf->cell($larguraindiv,4,$umdia[0],1,0,"C",0);

   }

  } else {

    for ($r = 0; $r < $colunas; $r++) {
      $pdf->cell($larguraindiv,4,"",1,0,"C",0);
    }
  }
  $pdf->cell(1,4,"",1,1,"C",0);
  //VERIFICA SE O CHECKBOX ESTA SELECIONADO
  $condicao = "";
  if ($active == "SIM") {
    $condicao .= " and ed60_c_situacao in('MATRICULADO', 'TROCA DE TURMA')";
  }

  if ($trocaTurma == 1) {
    $condicao .= " and ed60_c_situacao != 'TROCA DE TURMA'";
  }


  $sCamposMatricula  = "ed60_i_aluno,ed60_i_codigo,ed60_i_numaluno,ed60_c_parecer,ed47_v_nome,ed47_d_nasc,";
  $sCamposMatricula .= " fc_idade(ed47_d_nasc,'$databasecalc'::date) as idadealuno,ed60_c_situacao, ed60_matricula";
  $sWhereMatricula   = " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed59_i_serie $condicao";
  $sOrder            = "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa";
  $sSqlMatricula     = $clmatricula->sql_query("",$sCamposMatricula,$sOrder,$sWhereMatricula);
  $sResultMatricula  = $clmatricula->sql_record($sSqlMatricula);
  $limite            = 35;
  $cont              = 0;
  $cor1              = 0;
  $cor2              = 1;
  $cor               = "";
  for ($y = 0; $y < $clmatricula->numrows; $y++) {

    db_fieldsmemory($sResultMatricula, $y);
    
    /**
     * Verifica se foi selecionado apenas um turno referente e adiciona apenas os alunos que precisam aparecer
     * no relatório
     */
    if ($iTurno != 0 ) {
      
      $oMatricula = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
      $aTurnos    = $oMatricula->getTurnosVinculados();
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
    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }
    $sSqlDirAval    = " SELECT ed72_c_amparo as amparo,ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
    $sSqlDirAval   .= "       FROM diarioavaliacao ";
    $sSqlDirAval   .= "        inner join diario on ed95_i_codigo = ed72_i_diario ";
    $sSqlDirAval   .= "        left join amparo on ed81_i_diario = ed95_i_codigo ";
    $sSqlDirAval   .= "        left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
    $sSqlDirAval   .= "       WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sSqlDirAval   .= "       AND ed95_i_regencia = $ed59_i_codigo AND ed72_i_procavaliacao = $periodo ";
    $sResultDirAval = db_query($sSqlDirAval);
    $iLinhasDirAval = pg_num_rows($sResultDirAval);
    if ($iLinhasDirAval > 0) {
      db_fieldsmemory($sResultDirAval,0);
    } else {
      $amparo = "";
    }
    $pdf->setfont('arial','',8);
    $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",0);
    $pdf->cell(80,4,$ed47_v_nome,1,0,"L",0);
    $pdf->cell(5,4,$idadealuno,1,0,"C",0);
    if ($amparo == "S") {

      $pdf->setfont('arial','b',11);
      if ($ed81_i_justificativa != "") {
        $pdf->cell($larguraindiv*$colunas,4,"AMPARADO",1,0,"C",0);
      } else {
        $pdf->cell($larguraindiv*$colunas,4,"$ed250_c_abrev",1,0,"C",0);
      }
      $pdf->setfont('arial','b',8);

    } else {

        if (trim($ed60_c_situacao) != "MATRICULADO") {

        $pdf->setfont('arial','b',11);

        $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

        if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
          $sSituacao = "TRANSFERIDO";
        }

        $pdf->cell($larguraindiv*$colunas,4,$sSituacao,1,0,"C",0);
        $pdf->setfont('arial','b',8);

        } else {

        $pdf->setfont('arial','b',8);
        $at = $pdf->getY();
        $lg = $pdf->getX();
        //tira as colunas do meio
        for ($r = 0; $r < $colunas; $r++) {

          $pdf->setfont('arial','b',12);
          $pdf->cell($larguraindiv,4,"",1,0,"C",0);
          $pdf->Text($lg+($larguraindiv*30/100),$at+2,".");
          $lg = $pdf->getX();

        }
        $pdf->setfont('arial','b',8);

      }
    }

    $pdf->cell(1,4,"",1,1,"C",0);
    if ($cont == $limite && $limite < $clmatricula->numrows) {

      $pdf->setfont('arial','b',8);
      $pdf->cell(($larguracolunas+81),5,"Assinatura do professooor:_________________________________",1,1,"L",0);
      $pdf->line(10,43,($larguracolunas+91),43);
      $pdf->addpage("L");
      $pdf->setfont('arial','b',7);
      $pdf->cell(70,4,"",1,0,"C",0);
      $pdf->cell(10,4,"Mês >",1,0,"R",0);

      if ($informadiasletivos == "S") {

        $array_meses = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,3);
        for ($r = 0; $r < count($array_meses); $r++) {

          $qtd_diasmes = explode(",",$array_meses[$r]);
          $pdf->cell($larguraindiv*$qtd_diasmes[1],4,$qtd_diasmes[0],1,0,"C",0);

        }

      } else {
        $pdf->cell($larguracolunas,4,"",1,0,"R",0);
      }

      $pdf->cell(1,4,"",1,1,"R",0);
      $pdf->setfont('arial','',8);
      $pdf->cell(5,4,"Nº",1,0,"C",0);
      $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,4,"Dia >",1,0,"R",0);
      if ($informadiasletivos == "S") {

        $n_dias = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,2);
        $pdf->setfont('arial','b',6);

        for ($r = 0; $r < count($n_dias); $r++) {

          $umdia = explode("-",$n_dias[$r]);
          $pdf->cell($larguraindiv,4,$umdia[0],1,0,"C",0);

        }

      } else {

        for ($r = 0; $r < $colunas; $r++) {
          $pdf->cell($larguraindiv,4,"",1,0,"C",0);
        }
      }

      $pdf->cell(1,4,"",1,1,"C",0);
      $cont = 0;

    }
  }

  $termino = $pdf->getY();

  for ($t = $cont; $t < $limite; $t++) {

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(80,4,"",1,0,"L",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $at = $pdf->getY();
    $lg = $pdf->getX();

    for ($r = 0; $r < $colunas; $r++) {

      $pdf->setfont('arial','b',12);
      $pdf->cell($larguraindiv,4,"",1,0,"C",0);
      $pdf->Text($lg+($larguraindiv*30/100),$at+2,".");
      $lg = $pdf->getX();

    }
    $pdf->cell(1,4,"",1,1,"C",0);
  }

  $pdf->setfont('arial','b',8);
  $pdf->cell(($larguracolunas+91),5,"Assinatura do professor:_________________________________",1,1,"L",0);
  $pdf->line(10,43,($larguracolunas+91),43);

  ////////////Pagina 2 - Avaliações

  $pdf->addpage('L');
  $pdf->setfont('arial','b',7);
  $largmp = 0;
  if ($permitenotaembranco == "S" && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

    if (($priaval+2) <= $ed41_i_sequencia) {
      $largmp += 10;
    }
    if ($ed41_i_sequencia == $ultaval) {
      $largmp += 10;
    }
  }

  $quadros              = 25;
  $sWhere               = " ed41_i_procedimento = $ed220_i_procedimento AND ed41_i_sequencia < $ed41_i_sequencia";
  $sSqlProcAvaliacao    = $clprocavaliacao->sql_query("","ed09_c_abrev","ed41_i_sequencia asc",$sWhere);
  $sResultProcAvaliacao = $clprocavaliacao->sql_record($sSqlProcAvaliacao);
  $quadros             -= $clprocavaliacao->numrows*2;

  if ($largmp == 20) {
    $quadros = $quadros;
  } else if ($largmp == 10) {
    $quadros += 2;
  } else {
    $quadros += 4;
  }
  $pdf->setfont('arial','',8);
  $pdf->cell(5,4,"Nº",1,0,"C",0);
  $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
  $pdf->setfont('arial','b',8);
  if ($sexo == "true") {
    $pdf->cell(5,4,"S",1,0,"R",0);
  }
  if ($nasc == "true") {
    $pdf->cell(20,4,"Nascimento",1,0,"C",0);
  }
  if ($idade == "true") {
  	$pdf->setfont('arial','',8);
    $pdf->cell(5,4,"I",1,0,"C",0);

  }
  if ($resultant == "true") {
  	$pdf->setfont('arial','b',8);
    $pdf->cell(5,4,"RA",1,0,"C",0);
  }

  for ($y = 0; $y < $clprocavaliacao->numrows; $y++) {

    db_fieldsmemory($sResultProcAvaliacao,$y);
    $pdf->cell(10,4,$ed09_c_abrev,1,0,"C",0);

  }

  if ($permitenotaembranco == "S" && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

    if (($priaval+2) <= $ed41_i_sequencia) {
      $pdf->cell(10,4,"NP",1,0,"C",0);
    }
    if ($ed41_i_sequencia == $ultaval) {
      $pdf->cell(10,4,"P",1,0,"C",0);
    }
  }

  $pdf->cell(10,4,substr($sTipo,0,5),1,0,"C",0);
  $pdf->cell(5,4,"F",1,0,"C",0);
  if ($abono == "true") {
    $pdf->cell(5,4,"FA",1,0,"C",0);
  }
  if ($totalfal == "true") {
    $pdf->cell(5,4,"TF",1,0,"C",0);
  }
  if ($codigo == "true") {
    $pdf->cell(12,4,"Código",1,0,"C",0);
  }
  if ($parecer == "true") {
    $pdf->cell(18,4,"Parecer",1,0,"C",0);
  }
  if ($sexo == "false") {
    $pdf->cell(5,4,"",1,0,"C",0);
  }
  if ($idade == "false") {
    $pdf->cell(5,4,"",1,0,"C",0);
  }
  if ($abono == "false") {
    $pdf->cell(5,4,"",1,0,"C",0);
  }

  if ($codigo == "false") {

    $pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);

  }

  if ($nasc == "false") {

    $pdf->cell(5,4,"",1,0,"C",0);
 	$pdf->cell(5,4,"",1,0,"C",0);
 	$pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);

  }

  if ($resultant == "false") {
 	$pdf->cell(5,4,"",1,0,"C",0);
  }
  if ($totalfal == "false") {
 	$pdf->cell(5,4,"",1,0,"C",0);
  }

  if ($parecer == "false") {

 	$pdf->cell(6,4,"",1,0,"C",0);
 	$pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);

  }

  for ($y = 0; $y < $quadros; $y++) {
    $pdf->cell(5,4,"",1,$y == ($quadros-1)?1:0,"C",0);
  }
  //VERIFICA SE O CHECBOX ESTA SELECIONADO
  $pdf->setfont('arial','b',8);

  $condicao = "";
  if ($active == "SIM") {
    $condicao .= "and ed60_c_situacao IN ('MATRICULADO', 'TROCA DE TURMA')";
  }

  if ($trocaTurma == 1) {
    $condicao .= " and ed60_c_situacao != 'TROCA DE TURMA'";
  }

  $sCamposMat  = "ed60_i_codigo,ed60_c_rfanterior,ed60_i_aluno,ed60_i_numaluno,ed60_c_parecer,ed47_v_nome,";
  $sCamposMat .= "ed47_v_sexo,ed47_d_nasc,fc_idade(ed47_d_nasc,'$databasecalc'::date) as idadealuno,ed60_c_situacao";
  $sWhereMat   = " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed59_i_serie $condicao";
  $sSqlMat     = $clmatricula->sql_query("",$sCamposMat,"ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",$sWhereMat);
  $sResultMat  = $clmatricula->sql_record($sSqlMat);
  $limite      = 35;
  $cont        = 0;
  $cont_geral  = 0;
  $cor1        = 0;
  $cor2        = 1;
  $cor         = "";
  for ($y = 0; $y < $clmatricula->numrows; $y++) {

    db_fieldsmemory($sResultMat,$y);
    
    /**
     * Verifica se foi selecionado apenas um turno referente e adiciona apenas os alunos que precisam aparecer
     * no relatório
     */
    if ($iTurno != 0 ) {
      
      $oMatricula = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
      $aTurnos    = $oMatricula->getTurnosVinculados();
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
    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    if (trim($sTipo) == "NOTA") {

      $campoaval = "ed72_i_valornota is null";
      $campoaval2 = "ed72_i_valornota is not null";

    } else if (trim($sTipo) == "NIVEL") {

      $campoaval = "ed72_c_valorconceito = ''";
      $campoaval2 = "ed72_c_valorconceito != ''";

    } else if (trim($sTipo) == "PARECER") {

      $campoaval = "ed72_t_parecer = '' ";
      $campoaval2 = "ed72_t_parecer != ''";

    }

    $sWhereDiarioAvaliacao  = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo ";
    $sWhereDiarioAvaliacao .= "AND $campoaval AND ed72_c_amparo = 'N' AND ed09_c_somach = 'S' AND ed37_c_tipo = '$tipores'";
    $sSqlDiarioAvaliacao    = $cldiarioavaliacao->sql_query("","ed72_i_codigo","ed41_i_sequencia",$sWhereDiarioAvaliacao);
    $sResultDiarioAvaliacao = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliacao);
    $iLinhasDiarioAvaliacao = $cldiarioavaliacao->numrows;

    $sWhereDiarioAval       = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND $campoaval2 ";
    $sWhereDiarioAval      .= " AND ed72_c_amparo = 'N' AND ed09_c_somach = 'S' AND ed37_c_tipo = '$tipores' ";
    $sWhereDiarioAval      .= "AND ed41_i_sequencia < $ed41_i_sequencia";
    $sSqlDiarioAval         = $cldiarioavaliacao->sql_query("","ed72_i_codigo","ed41_i_sequencia",$sWhereDiarioAval);
    $sResultDiarioAval      = $cldiarioavaliacao->sql_record($sSqlDiarioAval);
    $iLinhasDiarioAval      = $cldiarioavaliacao->numrows;

    $iLinhasDiarioAval      = $iLinhasDiarioAval==0?1:$iLinhasDiarioAval;

    $pdf->setfont('arial','',8);
    $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",0);
    $pdf->cell(40,4,substr($ed47_v_nome,0,20),1,0,"L",0);

    if ($sexo == "true") {
      $pdf->cell(5,4,$ed47_v_sexo,1,0,"C",0);
    }
    if ($nasc == "true") {
      $pdf->cell(20,4,db_formatar($ed47_d_nasc,'d'),1,0,"C",0);
    }
    if ($idade == "true") {
    	$pdf->setfont('arial','',8);
      $pdf->cell(5,4,$idadealuno,1,0,"C",0);
    }
    $inf_ant = explode("|",RFanterior($ed60_i_codigo));
    $rfant   = substr($inf_ant[1],0,1);
    if ($resultant == "true") {
      $pdf->cell(5,4,$rfant,1,0,"C",0);
    }
    if (trim($ed60_c_situacao) == "MATRICULADO") {

      $sCamposDiario        = "ed37_c_minimoaprov as minperiodo,ed72_i_procavaliacao,ed72_c_valorconceito,";
  	  $sCamposDiario       .= " ed72_i_valornota,ed72_c_amparo,ed37_c_tipo,ed72_i_escola,ed72_c_tipo,";
  	  $sCamposDiario       .= " ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev";
  	  $sWhereDiario         = " ed95_i_regencia = $ed59_i_codigo AND ";
  	  $sWhereDiario        .= " ed95_i_aluno = $ed60_i_aluno AND ed41_i_sequencia < $ed41_i_sequencia";
  	  $sSqlDiarioAvaliac    = $cldiarioavaliacao->sql_query("",$sCamposDiario,"ed41_i_sequencia asc",$sWhereDiario);
  	  $sResultDiarioAvaliac = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliac);
      $iLinhasDiarioAva     = $cldiarioavaliacao->numrows;

      ////BIMESTRES ANTERIORES

      if ($cldiarioavaliacao->numrows > 0) {

          for ($w = 0; $w < $cldiarioavaliacao->numrows; $w++) {

          db_fieldsmemory($sResultDiarioAvaliac,$w);
          if ($ed60_c_parecer == "S") {
            $ed37_c_tipo = "PARECER";
          }
          if ($ed72_i_escola != $escola || $ed72_c_tipo == "F") {
            $NE = "*";
          } else {
            $NE = "";
          }
          if ($ed72_c_amparo == "S") {

            if ($ed81_i_justificativa != "") {
              $pdf->cell(10,4,"AMP",1,0,"C",0);
            } else {
              $pdf->cell(10,4,$ed250_c_abrev,1,0,"C",0);
            }

          } else {

              if ($ed37_c_tipo == "NOTA" && $ed72_i_valornota != "") {

              if ($resultedu == "S") {
                $aprov = !empty( $ed72_i_valornota ) ? number_format($ed72_i_valornota,2,".",".") : "";
              } else {
                $aprov = !empty( $ed72_i_valornota ) ? number_format($ed72_i_valornota,0) : "";
              }

            } else if ($ed37_c_tipo == "NOTA" && $ed72_i_valornota == "") {
              $aprov = $ed72_i_valornota;
            } else if ($ed37_c_tipo == "NIVEL") {
              $aprov = $ed72_c_valorconceito;
            } else {
              $aprov = "";
            }

            if (trim($ed37_c_tipo) == "NOTA" && $aprov < $minperiodo) {

              $pdf->setfont('arial','b',10);
              $pdf->cell(10,4,$NE.$aprov,1,0,"C",0);
              $pdf->setfont('arial','',10);

            } else {

              $pdf->setfont('arial','',10);
              $pdf->cell(10,4,$NE.$aprov,1,0,"C",0);

            }
          }
        }
      } else {

        for ($w = 0; $w < $clprocavaliacao->numrows; $w++) {
          $pdf->cell(10,4,"",1,0,"C",0);
        }

      }

      $sSqlDiarioAvalia    = " SELECT ed72_c_amparo as amparo ";
      $sSqlDiarioAvalia   .= "      FROM diarioavaliacao ";
      $sSqlDiarioAvalia   .= "             inner join diario on ed95_i_codigo = ed72_i_diario ";
      $sSqlDiarioAvalia   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
      $sSqlDiarioAvalia   .= "      AND ed95_i_regencia = $ed59_i_codigo ";
      $sSqlDiarioAvalia   .= "      AND ed72_i_procavaliacao = $periodo ";
      $sResultDiarioAvalia = db_query($sSqlDiarioAvalia);
      $iLinhasDir          = pg_num_rows($sResultDiarioAvalia);

      if ($iLinhasDir > 0) {
        db_fieldsmemory($sResultDiarioAvalia,0);
      } else {
        $amparo = "";
      }

      $sSqlDiarioFinal    = " SELECT ed74_c_resultadofinal as verificarf ";
      $sSqlDiarioFinal   .= "      FROM diariofinal ";
      $sSqlDiarioFinal   .= "        inner join diario on ed95_i_codigo = ed74_i_diario ";
      $sSqlDiarioFinal   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
      $sSqlDiarioFinal   .= "            AND ed95_i_regencia = $ed59_i_codigo ";
      $sResultDiarioFinal = db_query($sSqlDiarioFinal);
      $iLinhasDiarioFinal = pg_num_rows($sResultDiarioFinal);

      if ($iLinhasDiarioFinal > 0) {
        db_fieldsmemory($sResultDiarioFinal,0);
      } else {
        $verificarf = "";
      }
      $resfinal      = "";
      $notaprojetada = "";

      ////NOTA PARCIAL (NP) E NOTA PROJETADA (P)

      if ($permitenotaembranco == "S" && $iLinhasDiarioAva > 0
          && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

        if (trim($ed37_c_tipo) == "NOTA") {

          if (trim($obtencao) == "ME") {

     	    $sCamposDiarioAva  = "sum(ed72_i_valornota)/count(ed72_i_valornota) as aprvto,";
     	    $sCamposDiarioAva .=" ($minimoaprovres*(count(ed72_i_valornota)+1))-sum(ed72_i_valornota) as projetada";
     	    $sWhereDiarioAva   = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' ";
     	    $sWhereDiarioAva  .= "AND ed72_i_valornota is not null AND ed09_c_somach = 'S' AND";
     	    $sWhereDiarioAva  .= " ed41_i_sequencia < $ed41_i_sequencia AND ed37_c_tipo = '$tipores'";
         	$sSqlDiarioAva     = $cldiarioavaliacao->sql_query("",$sCamposDiarioAva,"",$sWhereDiarioAva);
            $sResultDiarioAva  = $cldiarioavaliacao->sql_record($sSqlDiarioAva);
            db_fieldsmemory($sResultDiarioAva,0);
            $projetada = $projetada<0?0:$projetada;

            if ($arredmedia == "S") {

              if ($resultedu == "S") {

                $resfinal      = !empty($aprvto)    ? number_format(round($aprvto),2,".",".")    : "";
                $notaprojetada = !empty($projetada) ? number_format(round($projetada),2,".",".") : "";

              } else {

                $resfinal      = !empty($aprvto)    ? number_format(round($aprvto),0)    : "";
                $notaprojetada = !empty($projetada) ? number_format(round($projetada),0) : "";

              }
            } else {

              if ($resultedu == "S") {

                $resfinal      = !empty($aprvto)    ? number_format($aprvto,2,".",".")    : "";
                $notaprojetada = !empty($projetada) ? number_format($projetada,2,".",".") : "";

              } else {

                $resfinal      = !empty($aprvto)    ? number_format($aprvto,0)    : "";
                $notaprojetada = !empty($projetada) ? number_format($projetada,0) : "";

              }
            }
          } else if (trim($obtencao) == "MP") {

     	    $sWhereAvalComp  = " ed44_i_procresultado = $ed43_i_codigo AND ed41_i_sequencia < $ed41_i_sequencia";
     	    $sSqlAvalComp    = $clavalcompoeres->sql_query("","sum(ed44_i_peso) as somapeso","",$sWhereAvalComp);
            $sResultAvalComp = $clavalcompoeres->sql_record($sSqlAvalComp);
            db_fieldsmemory($sResultAvalComp,0);
            $somapeso         = $somapeso == ""?0:$somapeso;

            $sSqlAvaliacao    = " SELECT sum(ed72_i_valornota*ed44_i_peso)/sum(ed44_i_peso) as aprvto, ";
            $sSqlAvaliacao   .= "        (( ".number_format($minimoaprovres,0)." * ($qtdaval + ";
            $sSqlAvaliacao   .= "            ((sum(ed44_i_peso)*$iLinhasDiarioAval)/count(*)))) - ";
            $sSqlAvaliacao   .= "            (sum(ed72_i_valornota*ed44_i_peso)/(count(*)/$iLinhasDiarioAval)))/$qtdaval as projetada ";
            $sSqlAvaliacao   .= "      FROM diarioavaliacao ";
            $sSqlAvaliacao   .= "           inner join diario on ed95_i_codigo = ed72_i_diario ";
            $sSqlAvaliacao   .= "           inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao ";
            $sSqlAvaliacao   .= "           inner join formaavaliacao on ed41_i_codigo = ed72_i_procavaliacao ";
            $sSqlAvaliacao   .= "           inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao ";
            $sSqlAvaliacao   .= "           inner join avalcompoeres on ed44_i_procavaliacao = ed72_i_procavaliacao ";
            $sSqlAvaliacao   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
            $sSqlAvaliacao   .= "            AND ed95_i_regencia = $ed59_i_codigo ";
            $sSqlAvaliacao   .= "            AND ed72_c_amparo = 'N' ";
            $sSqlAvaliacao   .= "            AND ed72_i_valornota is not null ";
            $sSqlAvaliacao   .= "            AND ed09_c_somach = 'S' ";
            $sSqlAvaliacao   .= "            AND ed41_i_sequencia < $ed41_i_sequencia ";
            $sResultAvaliacao = db_query($sSqlAvaliacao);
            db_fieldsmemory($sResultAvaliacao,0);
            $projetada = $projetada<0?0:$projetada;

            if ($arredmedia == "S") {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),0) : "";
              }
            } else {

              if ($resultedu == "S") {

                if ( $aprvto != '' ) {
                  $resfinal      = !empty( $aprvto ) ? number_format($aprvto,2,".",".") : "";
                }

                if ( $projetada != '' ) {
                  $notaprojetada = !empty( $projetada ) ? number_format($projetada,2,".",".") : "";
                }
              } else {

                if ( $aprvto != '' ) {
                  $resfinal      = !empty( $aprvto ) ? number_format($aprvto,0) : "";
                }

                if ( $projetada != '' ) {
                  $notaprojetada = !empty( $projetada ) ? number_format($projetada,0) : "";
                }
              }
            }
          } else if (trim($obtencao) == "SO") {

     	    $sCampos                = "sum(ed72_i_valornota) as aprvto, ";
     	    $sCampos               .= " $minimoaprovres-sum(ed72_i_valornota) as projetada";
     	    $sWhere                 = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo ";
     	    $sWhere                .= " AND ed72_c_amparo = 'N' AND ";
     	    $sWhere                .= " ed72_i_valornota is not null AND ed09_c_somach = 'S' ";
     	    $sWhere                .= " AND ed41_i_sequencia < $ed41_i_sequencia";
     	    $sSqlDiarioAvaliacao    = $cldiarioavaliacao->sql_query("",$sCampos,"",$sWhere);
     	    $sResultDiarioAvaliacao = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliacao);
            db_fieldsmemory($sResultDiarioAvaliacao,0);
            $projetada = $projetada<0?0:$projetada;

            if ($arredmedia == "S") {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),0) : "";
              }
            } else {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,0) : "";
              }
            }
          } else if (trim($obtencao) == "MN") {

     	    $sWhereDir  = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' ";
     	    $sWhereDir .= " AND ed72_i_valornota is not null  AND ed41_i_sequencia < $ed41_i_sequencia";
     	    $sSql       = $cldiarioavaliacao->sql_query("","max(ed72_i_valornota) as aprvto","",$sWhereDir);
            $sResult    = $cldiarioavaliacao->sql_record($sSql);
            db_fieldsmemory($result_maior,0);
            $projetada = $aprvto >= $minimoaprovres?0:$minimoaprovres-$aprvto;

            if ($arredmedia == "S") {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),0) : "";
              }
            } else {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,0) : "";
              }
            }
          } else if (trim($obtencao) == "UN") {

     	    $sCamposAvaliacao = "ed72_c_amparo as ultamparo,ed72_i_valornota as aprvto";
     	    $sWhereAvaliacao  = " ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo";
     	    $sOrder           = "ed41_i_sequencia DESC LIMIT 1";
         	$sSqlAvaliacao    = $cldiarioavaliacao->sql_query("",$sCamposAvaliacao,$sOrder,$sWhereAvaliacao);
            $sResultAvaliacao = $cldiarioavaliacao->sql_record($sSqlAvaliacao);
            db_fieldsmemory($result_ultima,0);
            $projetada = $aprvto >= $minimoaprovres?0:$minimoaprovres-$aprvto;

            if ($arredmedia == "S") {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format(round($aprvto),0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format(round($projetada),0) : "";
              }
            } else {

              if ($resultedu == "S") {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,2,".",".")    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,2,".",".") : "";
              } else {

                $resfinal      = !empty( $aprvto )    ? number_format($aprvto,0)    : "";
                $notaprojetada = !empty( $projetada ) ? number_format($projetada,0) : "";
              }
            }
          }

          $resfinal      = trim($ed60_c_situacao)!="MATRICULADO"||$aprvto==""?"":$resfinal;
          $notaprojetada = trim($ed60_c_situacao)!="MATRICULADO"||$projetada==""?"":$notaprojetada;

          if ($iLinhasDiarioAval == 0) {

            $resfinal      = "";
            $notaprojetada = "";

          }
          if (($priaval+2) <= $ed41_i_sequencia) {

            if (trim($ed37_c_tipo) == "NOTA" && $resfinal < @$minimoaprovres) {

              $pdf->setfont('arial','b',10);
              $pdf->cell(10,4,$resfinal,1,0,"C",0);
              $pdf->setfont('arial','',10);

            } else {
              $pdf->cell(10,4,$resfinal,1,0,"C",0);
            }
          }

          if ($ed41_i_sequencia == $ultaval) {
            $pdf->cell(10,4,"$notaprojetada",1,0,"C",0);
          }

        } else {

          if (($priaval+2) <= $ed41_i_sequencia) {
            $pdf->cell(10,4,"",1,0,"C",0);
          }
          if ($ed41_i_sequencia == $ultaval) {
            $pdf->cell(10,4,"",1,0,"C",0);
          }
        }
      } else if ($permitenotaembranco == "S" && $iLinhasDiarioAvaliacao > 0 && $iLinhasDiarioAva > 0
          && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

        if (($priaval) <= $ed41_i_sequencia) {
          $pdf->cell(10,4,"",1,0,"C",0);
        }
        if ($ed41_i_sequencia == $ultaval) {
          $pdf->cell(10,4,"",1,0,"C",0);
        }

      } else if ($permitenotaembranco == "S" && $iLinhasDiarioAvaliacao == 0
          && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

        if (($priaval+2) <= $ed41_i_sequencia) {
          $pdf->cell(10,4,"",1,0,"C",0);
        }
        if ($ed41_i_sequencia == $ultaval) {
          $pdf->cell(10,4,"",1,0,"C",0);
        }
      }

      ////BIMESTRES ATUAL
      $sCamposAval  = "ed72_c_valorconceito,ed72_i_valornota,ed37_c_tipo,ed72_i_escola,ed72_c_tipo,ed81_i_justificativa,";
      $sCamposAval .= " ed81_i_convencaoamp,ed250_c_abrev";
      $sWhereAval   = " ed95_i_regencia = $ed59_i_codigo AND ed95_i_aluno = $ed60_i_aluno ";
      $sWhereAval  .=" AND ed41_i_sequencia = $ed41_i_sequencia";
      $sSqlAval     = $cldiarioavaliacao->sql_query("",$sCamposAval,"ed41_i_sequencia",$sWhereAval);
      $sResultAval  = $cldiarioavaliacao->sql_record($sSqlAval);

      if ($cldiarioavaliacao->numrows > 0) {
        db_fieldsmemory($sResultAval,0);
      }
      if ($amparo == "S") {

        $pdf->setfont('arial','',8);
        if ($ed81_i_justificativa != "") {
          $pdf->cell(10,4,"AMP",1,0,"C",0);
        } else {
          $pdf->cell(10,4,$ed250_c_abrev,1,0,"C",0);
        }
        $pdf->setfont('arial','',10);

      } else {

        if ($cldiarioavaliacao->numrows > 0) {

          db_fieldsmemory($sResultAval,0);
          if ($ed60_c_parecer == "S") {
            $ed37_c_tipo = "PARECER";
          }
          if ($ed72_i_escola != $escola || $ed72_c_tipo == "F") {
            $NE = "*";
          } else {
            $NE = "";
          }
          if ($ed37_c_tipo == "NOTA" && $ed72_i_valornota != "") {

            if ($resultedu == "S") {
              $aprov_atual = !empty( $ed72_i_valornota ) ? number_format($ed72_i_valornota,2,".",".") : "";
            } else {
              $aprov_atual = !empty( $ed72_i_valornota ) ? number_format($ed72_i_valornota,0) : "";
            }

          } else if ($ed37_c_tipo == "NOTA" && $ed72_i_valornota == "") {
            $aprov_atual = $ed72_i_valornota;
          } else if ($ed37_c_tipo == "NIVEL") {
            $aprov_atual = $ed72_c_valorconceito;
          } else {
            $aprov_atual = "";
          }

          if (trim($ed37_c_tipo) == "NOTA" && $aprov_atual < @$minimoaprovres) {

            $pdf->setfont('arial','b',10);
            $pdf->cell(10,4,$NE.$aprov_atual,1,0,"C",0);
            $pdf->setfont('arial','',10);

          } else {
            $pdf->cell(10,4,$NE.$aprov_atual,1,0,"C",0);
          }
        } else {
          $pdf->cell(10,4,"",1,0,"C",0);
        }
      }

    //FALTAS
      $sWhereFaltas  = " ed95_i_regencia = $ed59_i_codigo AND ed95_i_aluno = $ed60_i_aluno ";
      $sWhereFaltas .= "AND ed41_i_sequencia < $ed41_i_sequencia";
      $sSqlFaltas    = $cldiarioavaliacao->sql_query("","sum(ed72_i_numfaltas) as faltas","",$sWhereFaltas);
      $sResutlFaltas = $cldiarioavaliacao->sql_record($sSqlFaltas);
      if ($cldiarioavaliacao->numrows > 0) {
        db_fieldsmemory($sResutlFaltas,0);
      } else {
        $faltas = 0;
      }
      $sWhereAbonoFalta  = " ed95_i_regencia = $ed59_i_codigo AND ed95_i_aluno = $ed60_i_aluno ";
      $sWhereAbonoFalta .= "AND ed41_i_sequencia < $ed41_i_sequencia";
      $sSqlAbonoFalta    = $clabonofalta->sql_query("","sum(ed80_i_numfaltas) as faltasabonadas","",$sWhereAbonoFalta);
      $sResultAbonoFalta = $clabonofalta->sql_record($sSqlAbonoFalta);

      if ($clabonofalta->numrows > 0) {
        db_fieldsmemory($sResultAbonoFalta,0);
      } else {
        $faltasabonadas = 0;
      }
      $pdf->cell(5,4,"",1,0,"C",0);
      //$pdf->cell(5,4,$faltas,1,0,"C",0);
      if ($abono == "true") {
        $pdf->cell(5,4,"",1,0,"C",0);
      }
      //$pdf->cell(5,4,$faltasabonadas,1,0,"C",0);
      if ($totalfal == "true") {
        $pdf->cell(5,4,$faltas+$faltasabonadas,1,0,"C",0);
      }
    } else {

      $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

      if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
        $sSituacao = "TRANSFERIDO";
      }

      $pdf->cell($clprocavaliacao->numrows*10+25+$largmp,4,$sSituacao,1,0,"C",0);
    }
    //escreve codigo aluno
    $pdf->setfont('arial','',8);
    if ($codigo == "true") {
      $pdf->cell(12,4,$ed60_i_aluno,1,0,"R",0);
    }
    $pdf->setfont('arial','',8);
    if (trim($ed60_c_situacao) != "MATRICULADO") {

      $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

      if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
        $sSituacao = "TRANSFERIDO";
      }

      $pdf->cell(18+($quadros*5),4,$sSituacao,1,1,"C",0);

    } else {

      if ($amparo == "S") {

        if ($ed81_i_justificativa != "") {
          $pdf->cell(18+($quadros*5),4,"AMPARO",1,1,"C",0);
        } else {
          $pdf->cell(18+($quadros*5),4,$ed250_c_abrev,1,1,"C",0);
        }

      } else {

        if ($parecer == "true") {

          $pdf->cell(6,4,"",1,0,"C",0);
          $pdf->cell(6,4,"",1,0,"C",0);
          $pdf->cell(6,4,"",1,0,"C",0);

        }

        if ($sexo == "false") {
          $pdf->cell(5,4,"",1,0,"C",0);
        }
        if ($idade == "false") {
          $pdf->cell(5,4,"",1,0,"C",0);
        }
        if ($abono == "false") {
          $pdf->cell(5,4,"",1,0,"C",0);
        }

        if ($codigo == "false") {

          $pdf->cell(6,4,"",1,0,"C",0);
          $pdf->cell(6,4,"",1,0,"C",0);

        }

        if ($nasc == "false") {

          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);

        }

        if ($resultant == "false") {
          $pdf->cell(5,4,"",1,0,"C",0);
        }
        if ($totalfal == "false") {
          $pdf->cell(5,4,"",1,0,"C",0);
        }

        if ($parecer == "false") {

          $pdf->cell(6,4,"",1,0,"C",0);
          $pdf->cell(6,4,"",1,0,"C",0);
          $pdf->cell(6,4,"",1,0,"C",0);

        }

        for ($d = 0; $d < $quadros; $d++) {
          $pdf->cell(5,4,"",1,$d==($quadros-1)?1:0,"C",0);
        }
      }
    }

    if ($cont == $limite && $cont_geral < $clmatricula->numrows) {

      $pdf->setfont('arial','b',7);
      if ($sexo == "true") {
  	    $s = " S - Sexo |";
      } else {
  	    $s = "";
      }
      if ($idade == "true") {
        $i = "I - Idade |";
      } else {
  	    $i = "";
      }
      if ($totalfal == "true") {
  	    $totfaltas = "TF - Total de Faltas |";
      } else {
  	    $totfaltas = "";
      }
      if ($resultant == "true") {
  	    $ra = "RA - (A-Aprovado R-Reprovado T-Tranferido C-Cancelado E-Evadido F-Falecido) |";
      } else {
  	    $ra = "";
      }
      if ($abono == "true") {
  	    $fa = "FA - Faltas Abonadas |";
      } else {
  	    $fa = "";
      }

      $pdf->setfont('arial','b',7);
      $sMsg  = "$ra  F - Faltas |  $i  $s ";
      $sMsg .= "$fa  $totfaltas AMP - Amparado | NP - Nota Parcial ";
      $sMsg .= " | P - Nota Projetada | * - Nota Externa";
      $pdf->cell(135+($clprocavaliacao->numrows*10)+($quadros*5)+$largmp,5,$sMsg,1,1,"L",0);
      $sMsgAssin = "Assinatura do professor:_________________________________";
      $pdf->cell(135+($clprocavaliacao->numrows*10)+($quadros*5)+$largmp,5,$sMsgAssin,1,1,"L",0);
      $pdf->addpage("L");
      $pdf->setfont('arial','',8);
      $pdf->cell(5,4,"Nº",1,0,"C",0);
      $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
      $pdf->setfont('arial','b',8);
      if ($sexo == "true") {
        $pdf->cell(5,4,"S",1,0,"R",0);
      }
      if ($nasc == "true") {
        $pdf->cell(20,4,"Nascimento",1,0,"C",0);
      }
      if ($idade == "true") {
        $pdf->cell(5,4,"I",1,0,"C",0);
      }
      if ($resultant == "true") {
        $pdf->cell(5,4,"RA",1,0,"C",0);
      }

      for ($p = 0; $p < $clprocavaliacao->numrows; $p++) {

        db_fieldsmemory($result0,$p);
        $pdf->cell(10,4,$ed09_c_abrev,1,0,"C",0);

      }

      if ($permitenotaembranco == "S" && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

        if (($priaval+2) <= $ed41_i_sequencia) {
          $pdf->cell(10,4,"NP",1,0,"C",0);
        }
        if ($ed41_i_sequencia == $ultaval) {
          $pdf->cell(10,4,"P",1,0,"C",0);
        }
      }

      $pdf->cell(10,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
      $pdf->cell(5,4,"F",1,0,"C",0);

      if ($abono == "true") {
        $pdf->cell(5,4,"FA",1,0,"C",0);
      }
      if ($totalfal == "true") {
        $pdf->cell(5,4,"TF",1,0,"C",0);
      }
      if ($codigo == "true") {
        $pdf->cell(12,4,"Código",1,0,"C",0);
      }
      if ($parecer == "true") {
        $pdf->cell(18,4,"Parecer",1,0,"C",0);
      }
    if ($sexo == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($idade == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($abono == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }

    if ($codigo == "false") {

  	  $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);

    }

    if ($nasc == "false") {

  	  $pdf->cell(5,4,"",1,0,"C",0);
  	  $pdf->cell(5,4,"",1,0,"C",0);
  	  $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);

    }

    if ($resultant == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($totalfal == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }

    if ($parecer == "false") {

  	  $pdf->cell(6,4,"",1,0,"C",0);
  	  $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);

    }
      for ($p = 0; $p < $quadros; $p++) {
        $pdf->cell(5,4,"",1,$p==($quadros-1)?1:0,"C",0);
      }
      $cont = 0;
    }
  }
  $termino = $pdf->getY();
  for ($t = $cont; $t < $limite; $t++) {

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(40,4,"",1,0,"C",0);
    if ($sexo == "true") {
      $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($nasc == "true") {
      $pdf->cell(20,4,"",1,0,"C",0);
    }
    if ($idade == "true") {
      $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($resultant == "true") {
      $pdf->cell(5,4,"",1,0,"C",0);
    }
    for ($w = 0; $w < $clprocavaliacao->numrows; $w++) {
      $pdf->cell(10,4,"",1,0,"C",0);
    }
    if ($permitenotaembranco == "S" && ($obtencao == "ME" || $obtencao == "MP" || $obtencao == "SO" )) {

      if (($priaval+2) <= $ed41_i_sequencia) {
        $pdf->cell(10,4,"",1,0,"C",0);
      }
      if ($ed41_i_sequencia == $ultaval) {
        $pdf->cell(10,4,"",1,0,"C",0);
      }
    }

    $pdf->cell(10,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);

    if ($abono == "true") {
      $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($totalfal == "true") {
      $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($codigo == "true") {
      $pdf->cell(12,4,"",1,0,"C",0);
    }

    if ($parecer == "true") {

      $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);

    }

    if ($sexo == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($idade == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($abono == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }

    if ($codigo == "false") {

  	  $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);

    }

    if ($nasc == "false") {

  	  $pdf->cell(5,4,"",1,0,"C",0);
  	  $pdf->cell(5,4,"",1,0,"C",0);
  	  $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);

    }

    if ($resultant == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }
    if ($totalfal == "false") {
  	  $pdf->cell(5,4,"",1,0,"C",0);
    }

    if ($parecer == "false") {

  	  $pdf->cell(6,4,"",1,0,"C",0);
  	  $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);

    }

    for ($p = 0; $p < $quadros; $p++) {
      $pdf->cell(5,4,"",1,$p == ($quadros-1)?1:0,"C",0);
    }
  }
  if ($sexo == "true") {
  	$s = " S - Sexo |";
  } else {
  	$s = "";
  }
  if ($idade == "true") {
    $i = "I - Idade |";
  } else {
  	$i = "";
  }
  if ($totalfal == "true") {
  	$totfaltas = "TF - Total de Faltas |";
  } else {
  	$totfaltas = "";
  }
  if ($resultant == "true") {
  	$ra = "RA - (A-Aprovado R-Reprovado T-Transferido C-Cancelado E-Evadido F-Falecido) |";
  } else {
  	$ra = "";
  }
  if ($abono == "true") {
  	$fa = "FA - Faltas Abonadas |";
  } else {
  	$fa = "";
  }

  $pdf->setfont('arial','b',7);
  $sMsg  = "$ra  F - Faltas |  $i  $s ";
  $sMsg .= "$fa  $totfaltas AMP - Amparado | NP - Nota Parcial ";
  $sMsg .= " | P - Nota Projetada | * - Nota Externa";
  $pdf->cell(135+($clprocavaliacao->numrows*10)+($quadros*5)+$largmp,5,$sMsg,1,1,"L",0);
  $sMsgAssinatura = "Assinatura do professor:_________________________________";
  $pdf->cell(135+($clprocavaliacao->numrows*10)+($quadros*5)+$largmp,5,$sMsgAssinatura,1,1,"L",0);
}
$pdf->Output();
?>