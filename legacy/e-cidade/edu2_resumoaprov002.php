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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("fpdf151/pdfwebseller.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_regenciaperiodo_classe.php");
require_once("classes/db_regenciahorario_classe.php");
require_once("classes/db_escola_classe.php");
require_once("classes/db_procavaliacao_classe.php");
require_once("classes/db_procresultado_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_aprovconselho_classe.php");
require_once("classes/db_periodocalendario_classe.php");
$resultedu = eduparametros(db_getsession("DB_coddepto"));
db_app::import("educacao.ArredondamentoNota");
db_app::import("educacao.DBEducacaoTermo");

$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$clmatricula = new cl_matricula;
$clregencia = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$clregenciahorario = new cl_regenciahorario;
$clprocavaliacao = new cl_procavaliacao;
$clprocresultado = new cl_procresultado;
$cldiarioavaliacao = new cl_diarioavaliacao;
$claprovconselho = new cl_aprovconselho;
$clperiodocalendario = new cl_periodocalendario;
$clescola = new cl_escola;
$escola = db_getsession("DB_coddepto");
$discglob = false;
$result = $clregencia->sql_record($clregencia->sql_query("","*","ed59_i_ordenacao"," ed59_i_codigo in ($disciplinas)"));
if($clregencia->numrows==0){?>
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
$iAno = db_utils::fieldsMemory($result, 0)->ed52_i_ano;
$obs_cons = '';
$iCasasDecimais = ArredondamentoNota::getNumeroCasasDecimais($iAno);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$linhas = $clregencia->numrows;
for($x=0;$x<$linhas;$x++){
 $minimoaprov = '';
 $obtencao    = '';
 db_fieldsmemory($result,$x);
 $result_proc = $clprocresultado->sql_record($clprocresultado->sql_query("","ed37_c_minimoaprov as minimoaprov,ed37_c_tipo as tipores,ed43_c_arredmedia as arredmedia, ed43_c_obtencao as obtencao",""," ed43_c_geraresultado = 'S' AND ed43_i_procedimento = $ed220_i_procedimento"));
 db_fieldsmemory($result_proc,0);
 $result_a = $clregenciaperiodo->sql_record($clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas)as aulasdadas",""," ed78_i_regencia = $ed59_i_codigo"));
 if($clregenciaperiodo->numrows>0){
  db_fieldsmemory($result_a,0);
 }
 $result5 = $clregenciahorario->sql_record($clregenciahorario->sql_query("","case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente",""," ed58_i_regencia = $ed59_i_codigo and ed58_ativo is true  "));
 if($clregenciahorario->numrows>0){
  db_fieldsmemory($result5,0);
 }else{
  $regente = "";
 }
 $pdf->setfillcolor(223);
 $head1 = "FICHA DE RESUMO DE APROVEITAMENTO";
 $head2 = "Curso: $ed29_i_codigo - $ed29_c_descr";
 $head3 = "Turno: $ed15_c_nome";
 $head4 = "Calendário: $ed52_c_descr";
 $head5 = "Turma: $ed57_c_descr";
 $head6 = "Disciplina: $ed232_c_descr";
 $head7 = "Etapa: $ed11_c_descr";
 $head8 = "Regente: ".$regente;
 $pdf->addpage('L');
 $pdf->ln(5);
 $pdf->setfont('arial','b',7);
 $pdf->cell(5,4,"",1,0,"C",0);
 $pdf->cell(65,4,"",1,0,"C",0);
 $sql_d = $clprocavaliacao->sql_query("","ed37_c_tipo,ed41_i_codigo,ed09_i_codigo,ed09_c_abrev","ed41_i_sequencia"," ed41_i_procedimento = $ed220_i_procedimento");
 $result_d = $clprocavaliacao->sql_record($sql_d);
 $cont = 0;
 for($y=0;$y<$clprocavaliacao->numrows;$y++){
  db_fieldsmemory($result_d,$y);
  $pdf->cell(20,4,$ed09_c_abrev,1,0,"C",0);
  $cont++;
 }
 if($permitenotaembranco=="S"){
  $pdf->cell(20,4,"NP",1,0,"C",0);
  $cont++;
 }
 for($y=$cont;$y<9;$y++){
  $pdf->cell(20,4,"",1,0,"C",0);
  $cont++;
 }
 $pdf->cell(30,4,"",1,1,"C",0);
 $pdf->cell(5,4,"N°",1,0,"C",0);
 $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
 $cont = 0;
 for($y=0;$y<$clprocavaliacao->numrows;$y++){
  $pdf->cell(15,4,$ed37_c_tipo,1,0,"C",0);
  $pdf->cell(5,4,"Ft",1,0,"C",0);
  $cont++;
 }
 for($y=$cont;$y<9;$y++){
  $pdf->cell(15,4,"",1,0,"C",0);
  $pdf->cell(5,4,"",1,0,"C",0);
 }
 $pdf->cell(10,4,"Aprov",1,0,"C",0);
 $pdf->cell(10,4,"% Freq",1,0,"C",0);
 $pdf->cell(10,4,"RF",1,1,"C",0);
 $cor1 = 0;
 $cor2 = 0;
 $cor = "";
 $cont1 = 0;
 $limite = 31;
 $sql2 = "SELECT ed95_i_codigo,
                 ed60_i_aluno,
                 ed60_i_codigo,
                 ed47_v_nome,
                 ed60_c_parecer,
                 ed60_i_numaluno,
                 ed74_c_valoraprov,
                 ed74_c_resultadofinal,
                 ed74_i_percfreq,
                 ed60_c_situacao,
                 ed81_c_todoperiodo,
                 ed81_i_justificativa,
                 ed81_i_convencaoamp,
                 ed250_c_abrev,
                 ed59_c_freqglob,
                 ed74_c_resultadofreq,
                 ed11_i_ensino
          FROM matricula
           inner join aluno on ed47_i_codigo = ed60_i_aluno
           inner join diario on ed95_i_aluno = ed47_i_codigo
           inner join diariofinal on ed74_i_diario = ed95_i_codigo
           inner join regencia on ed59_i_codigo = ed95_i_regencia
           inner join serie on ed11_i_codigo = ed59_i_serie
           left join amparo on ed81_i_diario = ed95_i_codigo
           left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp
          WHERE ed60_i_turma = $ed59_i_turma
          AND ed95_i_regencia = $ed59_i_codigo
          ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa
         ";
 $result2 = db_query($sql2);
 //db_criatabela($result2);
 $linhas2 = pg_num_rows($result2);
 for ($y = 0; $y < $linhas2; $y++) {

  db_fieldsmemory($result2, $y);

  if ($trocaTurma == 1 && $ed60_c_situacao == "TROCA DE TURMA" ) {
    continue;
  }

  switch (trim($ed60_c_situacao)) {

    case 'MATRICULA TRANCADA' :

      $ed60_c_situacao = 'MT';
      break;

    case 'MATRICULA INDEFERIDA' :

      $ed60_c_situacao = 'IN';
      break;

    case 'MATRICULA INDEVIDA' :

      $ed60_c_situacao = 'MI';
      break;

    case 'TRANSFERIDO REDE':

      $ed60_c_situacao = 'TE';
      break;

    case 'TRANSFERIDO FORA':

      $ed60_c_situacao = 'TF';
      break;

    case 'TROCA DE MODALIDADE':

      $ed60_c_situacao = 'TM';
      break;

  }
  if($cor==$cor1){
   $cor = $cor2;
  }else{
   $cor = $cor1;
  }
  if(trim($ed37_c_tipo)=="NOTA"){
   $campoaval = "ed72_i_valornota is null";
  }elseif(trim($ed37_c_tipo)=="NIVEL"){
   $campoaval = "ed72_c_valorconceito = ''";
  }elseif(trim($ed37_c_tipo)=="PARECER"){
   $campoaval = "ed72_t_parecer = '' ";
  }
  $result33 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_i_codigo","ed41_i_sequencia"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND $campoaval AND ed72_c_amparo = 'N' AND ed09_c_somach = 'S' AND ed37_c_tipo = '$ed37_c_tipo'"));
  $linhas33 = $cldiarioavaliacao->numrows;
  $pdf->setfont('arial','',7);
  $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",$cor);
  $pdf->cell(65,4,$ed47_v_nome,1,0,"L",$cor);
  $sql_p = $cldiarioavaliacao->sql_query("","*","ed41_i_sequencia"," ed41_i_procedimento = $ed220_i_procedimento AND ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo");
  $result_p = $cldiarioavaliacao->sql_record($sql_p);
  $cont2 = 0;
  for($t=0;$t<$cldiarioavaliacao->numrows;$t++){

   db_fieldsmemory($result_p,$t);
   if($ed60_c_parecer=="S"){
    $ed37_c_tipo = "PARECER";
   }
   if ((trim($ed37_c_tipo)=="NOTA") && $ed72_i_valornota != "") {
      $ed72_i_valornota = number_format(DBNumber::truncate($ed72_i_valornota, $iCasasDecimais), $iCasasDecimais, ".", "");
   }
   if(trim($ed60_c_situacao)=="MATRICULADO"){
    if(trim($ed37_c_tipo)=="PARECER"){
     $aprov = "Parecer";
    }elseif(trim($ed37_c_tipo)=="NOTA" && $ed72_i_valornota!=""){
     $aprov = $ed72_i_valornota;
    }elseif(trim($ed37_c_tipo)=="NIVEL" && $ed72_c_valorconceito!=""){
     $aprov = $ed72_c_valorconceito;
    }else{
     $aprov = "";
    }
    if(trim($ed72_c_amparo)=="S"){
     if($ed81_i_justificativa!=""){
      $aprov = "Amparado";
     }else{
      $aprov = $ed250_c_abrev;
     }
     $ed72_i_numfaltas = "";
    }
    if(trim($ed59_c_freqglob)=="A"){
     $ed72_i_numfaltas = "-";
    }elseif(trim($ed59_c_freqglob)=="F"){
     $aprov = "-";
    }
    $pdf->setfont('arial','',9);
    if(trim($ed37_c_tipo)=="NOTA" && $aprov<$minimoaprov){
     $pdf->setfont('arial','b',10);
     $pdf->cell(15,4,$aprov,1,0,"C",$cor);
     $pdf->setfont('arial','',9);
    }else{
     $pdf->cell(15,4,$aprov,1,0,"C",$cor);
    }
    $pdf->cell(5,4,$ed72_i_numfaltas,1,0,"C",$cor);
   }else{
    $pdf->setfont('arial','',7);
    $pdf->cell(20,4,substr(Situacao($ed60_c_situacao,$ed60_i_codigo),0,10),1,0,"C",$cor);
    $pdf->setfont('arial','',9);
   }
   $cont2++;
   $aprov = "";
   $ed72_i_numfaltas = "";
   $tipo = $ed37_c_tipo;
  }
  $sql66 = "SELECT ed74_c_resultadofinal as verificarf
            FROM diariofinal
             inner join diario on ed95_i_codigo = ed74_i_diario
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed95_i_regencia = $ed59_i_codigo
          ";
  $result66 = db_query($sql66);
  $linhas66 = pg_num_rows($result66);
  if($linhas66>0){
   db_fieldsmemory($result66,0);
  }else{
   $verificarf = "";
  }
  if($permitenotaembranco=="S" && $linhas33>0 && $verificarf=="" && $ed81_c_todoperiodo!="S"){
   $aprvto = '';
   if(trim($ed37_c_tipo)=="NOTA"){
    if(trim($obtencao)=="ME"){
      $result_media = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","sum(ed72_i_valornota)/count(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed09_c_somach = 'S'"));
      db_fieldsmemory($result_media,0);
      $resfinal = $aprvto;
    } else if (trim($obtencao) == "MP") {
     $sql_r = "SELECT sum(ed72_i_valornota*ed44_i_peso)/sum(ed44_i_peso) as aprvto
               FROM diario
                left join diarioavaliacao on ed72_i_diario = ed95_i_codigo
                left join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
                left join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                left join avalcompoeres on ed44_i_procavaliacao = ed41_i_codigo
               WHERE ed95_i_aluno = $ed60_i_aluno
               AND ed95_i_regencia = $ed59_i_codigo
               AND ed72_c_amparo = 'N'
               AND ed72_i_valornota is not null
               AND ed09_c_somach = 'S'
              ";
     $result_media = db_query($sql_r);
     db_fieldsmemory($result_media,0);
      $resfinal = $aprvto;
    }elseif(trim($obtencao)=="SO"){
     $result_soma = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","sum(ed72_i_valornota) as aprvto,sum(to_number(ed37_c_minimoaprov,'999')) as somaminimo",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed09_c_somach = 'S'"));
     db_fieldsmemory($result_soma,0);
       $resfinal = $aprvto;
    }elseif(trim($obtencao)=="MN"){
     $result_maior = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","max(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null"));
     db_fieldsmemory($result_maior,0);
       $resfinal = $aprvto;
    } elseif(trim($obtencao)=="UN"){
      $result_ultima = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_c_amparo as ultamparo,ed72_i_valornota as aprvto","ed41_i_sequencia DESC LIMIT 1"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo"));
      db_fieldsmemory($result_ultima,0);
       $resfinal = $aprvto;
    } else {
      $resfinal = $aprvto;
    }
    $pdf->setfont('arial','',9);
    $resfinal = trim($ed60_c_situacao)!="MATRICULADO"||$aprvto==""?"":ArredondamentoNota::arredondar($resfinal);
    if(trim($ed37_c_tipo)=="NOTA" && $resfinal<$minimoaprov) {

     $pdf->setfont('arial','b',10);
     $pdf->cell(15,4,$resfinal,1,0,"C",$cor);
     $pdf->setfont('arial','',9);
    } else {
      $pdf->cell(15,4,$resfinal,1,0,"C",$cor);
    }
    $pdf->cell(5,4,"",1,0,"C",$cor);
    $cont2++;
   }
  }
  for ($t = $cont2; $t < 9; $t++) {

   $pdf->cell(15,4,"",1,0,"C",$cor);
   $pdf->cell(5,4,"",1,0,"C",$cor);
  }
  if(trim($ed60_c_situacao)=="MATRICULADO"){
   if(trim($ed81_c_todoperiodo)=="S"){
    if($ed81_i_justificativa!=""){
     $ed74_c_valoraprov = "Amp";
    }else{
     $ed74_c_valoraprov = $ed250_c_abrev;
    }
    $ed74_i_percfreq = "";
   }else{
    if($resultedu=='S'){
     $ed74_i_percfreq = $ed74_i_percfreq!=""?number_format($ed74_i_percfreq,2,".","."):"";
    }else{
     $ed74_i_percfreq = $ed74_i_percfreq!=""?number_format($ed74_i_percfreq,0):"";
    }
    if($tipo=="NOTA"){
      $ed74_c_valoraprov = ArredondamentoNota::formatar($ed74_c_valoraprov, $iAno);
    }elseif($tipo=="PARECER"){
      $ed74_c_valoraprov = "Parec";
    }else{
      $ed74_c_valoraprov = $ed74_c_valoraprov;
    }
   }
   if(trim($ed59_c_freqglob)=="A"){

    $sql_f = "SELECT ed59_c_freqglob, ed74_i_percfreq
              FROM diariofinal
               inner join diario on ed95_i_codigo = ed74_i_diario
               inner join regencia on ed59_i_codigo = ed95_i_regencia
               inner join turma on ed57_i_codigo = ed59_i_turma
              WHERE ed57_i_codigo = $ed57_i_codigo
              AND ed59_c_freqglob = 'F'
              AND ed95_i_aluno = $ed60_i_aluno
              AND ed95_i_regencia = $ed59_i_codigo
             ";
    $result_f = db_query($sql_f);
    $linhas_f = pg_num_rows($result_f);
    if ($resultedu == 'S') {
      $ed74_i_percfreq = $ed74_i_percfreq!=""?number_format(pg_result($result_f,0,'ed74_i_percfreq'),2,".","."):"";
    } else {
      $ed74_i_percfreq = $ed74_i_percfreq!=""?number_format(pg_result($result_f,0,'ed74_i_percfreq'),0):"";
    }
   }elseif(trim($ed59_c_freqglob)=="F"){

     if (!empty($ed74_c_resultadofreq) && !empty($ed11_i_ensino)) {

       $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed74_c_resultadofreq, $iAno);
       if (isset($aDadosTermo[0])) {
         $ed74_c_valoraprov = $aDadosTermo[0]->sAbreviatura;
       } else {
         $ed74_c_valoraprov = "-";
       }
     }
   }
   if ($ed74_c_resultadofinal == "") {
     $ed74_c_resultadofinal = "";
   } elseif ($ed74_c_resultadofinal == "A") {

     $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed74_c_resultadofinal, $iAno);
     if (isset($aDadosTermo[0])) {
       $ed74_c_resultadofinal = $aDadosTermo[0]->sAbreviatura;
     } else {
       $ed74_c_resultadofinal = "Apr";
     }
   } elseif ($ed74_c_resultadofinal == "R") {

     $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed74_c_resultadofinal, $iAno);
     if (isset($aDadosTermo[0])) {
       $ed74_c_resultadofinal = $aDadosTermo[0]->sAbreviatura;
     } else {
       $ed74_c_resultadofinal = "Rep";
     }
   }
   /*
   if($permitenotaembranco=="S" && $linhas33>0){
    $ed74_c_valoraprov = "";
    $ed74_c_resultadofinal = "";
   }
   */
   $pdf->cell(10,4,$ed74_c_resultadofinal==""?"":$ed74_c_valoraprov,1,0,"C",$cor);
   $pdf->cell(10,4,$ed74_i_percfreq,1,0,"C",$cor);
   $pdf->cell(10,4,$ed74_c_resultadofinal,1,1,"C",$cor);
   $pdf->line(10,48,290,48);
  }else{
   $pdf->setfont('arial','',7);
   $pdf->cell(30,4,Situacao($ed60_c_situacao,$ed60_i_codigo),1,1,"C",$cor);
   $pdf->setfont('arial','',9);
  }
  if($cont1==$limite){
   //aulas dadas
   $pdf->setfont('arial','b',7);
   $pdf->cell(70,4,"Aulas Previstas",1,0,"R",0);
   $pdf->setfont('arial','',9);
   $cont3 = 0;
   for($r=0;$r<$clprocavaliacao->numrows;$r++){
    db_fieldsmemory($result_d,$r);
    $sql_pc = $clperiodocalendario->sql_query("","ed53_i_semletivas",""," ed53_i_calendario = $ed57_i_calendario AND ed53_i_periodoavaliacao = $ed09_i_codigo");
    $result_pc = $clperiodocalendario->sql_record($sql_pc);
    db_fieldsmemory($result_pc,0);
    $aulasdd = ($ed59_i_qtdperiodo==0||$ed53_i_semletivas==0)?"0":$ed53_i_semletivas*$ed59_i_qtdperiodo;
    $pdf->cell(20,4,$aulasdd,1,0,"C",0);
    $cont3++;
   }
   for($r=$cont3;$r<9;$r++){
    $pdf->cell(20,4,"",1,0,"C",0);
   }
   $pdf->cell(30,4,"",1,1,"R",0);
   //aulas previstas
   $pdf->setfont('arial','b',7);
   $pdf->cell(70,4,"Aulas Dadas",1,0,"R",0);
   $pdf->setfont('arial','',9);
   $cont3 = 0;
   for($r=0;$r<$clprocavaliacao->numrows;$r++){
    db_fieldsmemory($result_d,$r);
    $sql_rp = $clregenciaperiodo->sql_query("","ed78_i_aulasdadas",""," ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $ed41_i_codigo");
    $result_rp = $clregenciaperiodo->sql_record($sql_rp);
    db_fieldsmemory($result_rp,0);
    $aulaspp = $ed78_i_aulasdadas==""?"0":$ed78_i_aulasdadas;
    $pdf->cell(20,4,$aulaspp,1,0,"C",0);
    $cont3++;
   }
   for($r=$cont3;$r<9;$r++){
    $pdf->cell(20,4,"",1,0,"C",0);
   }
   $pdf->cell(30,4,"",1,1,"R",0);
   //aprovado pelo conselho
   $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("","case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao","ed59_i_ordenacao","ed95_i_regencia = $ed59_i_codigo AND ed59_i_serie = $ed59_i_serie"));
   if($claprovconselho->numrows>0){
    $obs_cons = "";
    $sepobs = "";
    for($g=0;$g<$claprovconselho->numrows;$g++){
     db_fieldsmemory($result_cons,$g);
     $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
     $sepobs = "\n";
    }
   }
   $pdf->setfont('arial','b',7);
   $pdf->multicell(280,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
   $pdf->addpage('L');
   $pdf->ln(5);
   $pdf->setfont('arial','b',7);
   $pdf->cell(5,4,"",1,0,"C",0);
   $pdf->cell(65,4,"",1,0,"C",0);
   $sql_d = $clprocavaliacao->sql_query("","ed41_i_codigo,ed09_i_codigo,ed09_c_abrev","ed41_i_sequencia"," ed41_i_procedimento = $ed220_i_procedimento");
   $result_d = $clprocavaliacao->sql_record($sql_d);
   $cont = 0;
   for($w=0;$w<$clprocavaliacao->numrows;$w++){
    db_fieldsmemory($result_d,$w);
    $pdf->cell(20,4,$ed09_c_abrev,1,0,"C",0);
    $cont++;
   }
   if($permitenotaembranco=="S"){
    $pdf->cell(20,4,"NP",1,0,"C",0);
    $cont++;
   }
   for($w=$cont;$w<9;$w++){
    $pdf->cell(20,4,"",1,0,"C",0);
    $cont++;
   }
   $pdf->cell(30,4,"",1,1,"C",0);
   $pdf->cell(5,4,"N°",1,0,"C",0);
   $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
   $cont = 0;
   for($w=0;$w<$clprocavaliacao->numrows;$w++){
    $pdf->cell(15,4,$ed37_c_tipo,1,0,"C",0);
    $pdf->cell(5,4,"Ft",1,0,"C",0);
    $cont++;
   }
   for($w=$cont;$w<9;$w++){
    $pdf->cell(15,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
   }
   $pdf->cell(10,4,"Aprov",1,0,"C",0);
   $pdf->cell(10,4,"% Freq",1,0,"C",0);
   $pdf->cell(10,4,"RF",1,1,"C",0);
   $cont1 = -1;
  }
  $cont1++;
 }
 for($y=$cont1;$y<$limite;$y++){
  $pdf->cell(5,4,"",1,0,"C",0);
  $pdf->cell(65,4,"",1,0,"L",0);
  for($t=0;$t<9;$t++){
   $pdf->cell(15,4,"",1,0,"C",0);
   $pdf->cell(5,4,"",1,0,"C",0);
  }
  $pdf->cell(10,4,"",1,0,"C",0);
  $pdf->cell(10,4,"",1,0,"C",0);
  $pdf->cell(10,4,"",1,1,"C",0);
 }
 //aulas previstas
 $pdf->setfont('arial','b',7);
 $pdf->cell(70,4,"Aulas Previstas",1,0,"R",0);
 $pdf->setfont('arial','',9);
 $cont3 = 0;
 for($y=0;$y<$clprocavaliacao->numrows;$y++){
  db_fieldsmemory($result_d,$y);
  $sql_pc = $clperiodocalendario->sql_query("","ed53_i_semletivas",""," ed53_i_calendario = $ed57_i_calendario AND ed53_i_periodoavaliacao = $ed09_i_codigo");
  $result_pc = $clperiodocalendario->sql_record($sql_pc);
  if($clperiodocalendario->numrows>0){
   db_fieldsmemory($result_pc,0);
  }else{
   $ed53_i_semletivas = 0;
  }
  $aulasdd = ($ed59_i_qtdperiodo==0||$ed53_i_semletivas==0)?"0":$ed53_i_semletivas*$ed59_i_qtdperiodo;
  $pdf->cell(20,4,$aulasdd,1,0,"C",0);
  $cont3++;
 }
 for($y=$cont3;$y<9;$y++){
  $pdf->cell(20,4,"",1,0,"C",0);
 }
 $pdf->cell(30,4,"",1,1,"R",0);
 //aulas dadas
 $pdf->setfont('arial','b',7);
 $pdf->cell(70,4,"Aulas Dadas",1,0,"R",0);
 $pdf->setfont('arial','',9);
 $cont3 = 0;
 for($y=0;$y<$clprocavaliacao->numrows;$y++){
  db_fieldsmemory($result_d,$y);
  $sql_rp = $clregenciaperiodo->sql_query("","ed78_i_aulasdadas",""," ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $ed41_i_codigo");
  $result_rp = $clregenciaperiodo->sql_record($sql_rp);
  if($clregenciaperiodo->numrows>0){
   db_fieldsmemory($result_rp,0);
   $aulaspp = $ed78_i_aulasdadas==""?"0":$ed78_i_aulasdadas;
   $pdf->cell(20,4,$aulaspp,1,0,"C",0);
  }else{
   $pdf->cell(20,4,"",1,0,"C",0);
  }
  $cont3++;
 }
 for($y=$cont3;$y<9;$y++){
  $pdf->cell(20,4,"",1,0,"C",0);
 }
 $pdf->cell(30,4,"",1,1,"R",0);
 //aprovado pelo conselho
 $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("","case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao","ed59_i_ordenacao","ed95_i_regencia = $ed59_i_codigo AND ed59_i_serie = $ed59_i_serie"));
 if($claprovconselho->numrows>0){
  $obs_cons = "";
  $sepobs = "";
  for($g=0;$g<$claprovconselho->numrows;$g++){
   db_fieldsmemory($result_cons,$g);
   $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
   $sepobs = "\n";
  }
 }
 $pdf->setfont('arial','b',7);
 $pdf->multicell(280,4,(isset($obs_cons)&&$obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
 $pdf->setfont('arial','',6);
 $sMsgLegenda  = "MT = Matrícula Trancada MI = Matrícula Indevida IN = Matricula Indeferida, TE = Transferido Rede ";
 $sMsgLegenda .= "TF = Transferido Fora TM = Troca de Modalidade";
 $pdf->cell(280,4, $sMsgLegenda,0);
}
$pdf->Output();
?>