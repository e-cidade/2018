<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_procavaliacao_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_procresultado_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_regenciaperiodo_classe.php");
require_once("classes/db_regenteconselho_classe.php");
db_app::import("educacao.ArredondamentoNota");
$resultedu= eduparametros(db_getsession("DB_coddepto"));
$permitenotaembranco = VerParametroNota(db_getsession("DB_coddepto"));
$clturma 					 = new cl_turma;
$clmatricula 			 = new cl_matricula;
$clregencia 		   = new cl_regencia;
$clprocavaliacao   = new cl_procavaliacao;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocresultado   = new cl_procresultado;
$clregenciaperiodo = new cl_regenciaperiodo;
$clregenteconselho = new cl_regenteconselho;
$periodo_001 = $periodo;
$escola = db_getsession("DB_coddepto");
db_postmemory($_GET);

$sCampos  = "distinct                                                   \n";
$sCampos .= "ed15_c_nome, ed11_c_descr, ed29_c_descr,                   \n";
$sCampos .= "ed57_i_codigo, ed223_i_serie, ed220_i_procedimento,        \n";
$sCampos .= "ed52_i_ano, ed52_d_resultfinal, ed57_c_descr, ed52_c_descr \n";

$sSql = $clturma->sql_query_turmaserie("",$sCampos,"ed57_c_descr"," ed220_i_codigo in ($turmas)");

$result = $clturma->sql_record($sSql);
if($clturma->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
db_fieldsmemory($result, 0);
$result_proc = $clprocresultado->sql_record($clprocresultado->sql_query("","ed43_i_codigo,ed37_c_tipo as tipores,ed43_c_arredmedia as arredmedia,ed43_c_minimoaprov as minimoaprovres, ed43_c_obtencao as obtencao",""," ed43_c_geraresultado = 'S' AND ed43_i_procedimento = ".pg_result($result,0,'ed220_i_procedimento').""));
if($clprocresultado->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum resultado do procedimento de avaliação desta turma tem a opção de gerar resultado final!<b><br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}else{
 db_fieldsmemory($result_proc,0);
}

$iCasasDecimais = ArredondamentoNota::getNumeroCasasDecimais($ed52_i_ano);
if ($iCasasDecimais == '') {
  $iCasasDecimais = 0;
}

function getTotadeFaltas($ed95_i_aluno, $ed59_i_turma,$ed59_i_serie, $ed72_i_procavaliacao) {

	$sSql =  "SELECT sum (ed72_i_numfaltas) as total
						  FROM diarioavaliacao   
						                        inner join diario              on ed95_i_codigo = ed72_i_diario
						                        inner join regencia            on ed59_i_codigo = ed95_i_regencia
						                        inner join disciplina          on ed12_i_codigo = ed59_i_disciplina
						                        inner join caddisciplina       on ed232_i_codigo = ed12_i_caddisciplina
						                        inner join turma               on turma.ed57_i_codigo = regencia.ed59_i_turma
						                        inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo
						                        inner join serieregimemat      on serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat
						                                                      and serieregimemat.ed223_i_serie = regencia.ed59_i_serie
						                        inner join base                on base.ed31_i_codigo = turma.ed57_i_base
						                        left join basediscglob         on basediscglob.ed89_i_codigo = base.ed31_i_codigo
						                        left join amparo               on ed81_i_diario = ed95_i_codigo
						                        left join convencaoamp         on ed250_i_codigo = ed81_i_convencaoamp
						                        left join procavaliacao        on ed41_i_codigo = ed72_i_procavaliacao
						                        left join formaavaliacao       on ed37_i_codigo = ed41_i_formaavaliacao
						WHERE ed95_i_aluno = $ed95_i_aluno
						  AND ed72_c_amparo <> 'S'
						  AND ed95_i_regencia in ( SELECT ed59_i_codigo
						                             FROM regencia
						                                           inner join disciplina          on disciplina.ed12_i_codigo = regencia.ed59_i_disciplina
						                                           inner join caddisciplina       on ed232_i_codigo= ed12_i_caddisciplina
						                                           inner join turma               on turma.ed57_i_codigo = regencia.ed59_i_turma
						                                           inner join ensino              on ensino.ed10_i_codigo = disciplina.ed12_i_ensino
						                                           inner join escola              on escola.ed18_i_codigo = turma.ed57_i_escola
						                                           inner join turno               on turno.ed15_i_codigo = turma.ed57_i_turno
						                                           inner join sala                on sala.ed16_i_codigo = turma.ed57_i_sala
						                                           inner join calendario          on calendario.ed52_i_codigo = turma.ed57_i_calendario
						                                           inner join base                on base.ed31_i_codigo = turma.ed57_i_base
						                         
						                                           left join basediscglob         on basediscglob.ed89_i_codigo = base.ed31_i_codigo
						                         
						                                           inner join cursoedu            on cursoedu.ed29_i_codigo = base.ed31_i_curso
						                                           inner join serie               on serie.ed11_i_codigo = regencia.ed59_i_serie
						                                           inner join serieregimemat      on serieregimemat.ed223_i_serie = serie.ed11_i_codigo
						                                           inner join turmaserieregimemat on turmaserieregimemat.ed220_i_serieregimemat = serieregimemat.ed223_i_codigo
						                                                                        and turmaserieregimemat.ed220_i_turma = regencia.ed59_i_turma
						                         
						                                           inner join procedimento        on procedimento.ed40_i_codigo = turmaserieregimemat.ed220_i_procedimento                                                                  
																			 WHERE ed59_i_turma = $ed59_i_turma
						                             AND ed59_i_serie = $ed59_i_serie )
						  												   AND ed72_i_procavaliacao = $ed72_i_procavaliacao";
	
	$resultado = db_query($sSql);
		
	$oTotal = db_utils::fieldsMemory($resultado,0);
	if ($oTotal->total) {
		
		return $oTotal->total;
	}else{
		
		return '';
	}
		
}

function checkPeriodo($ed41_i_procedimento, $ed41_i_periodoavaliacao){
	
	$sSql = "SELECT ed41_i_periodoavaliacao as retorno_periodo                                                                                                                                                                              
 					   FROM procavaliacao                 
				                      inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
				                      inner join formaavaliacao   on formaavaliacao.ed37_i_codigo   = procavaliacao.ed41_i_formaavaliacao
				                      inner join procedimento     on procedimento.ed40_i_codigo     = procavaliacao.ed41_i_procedimento
 					  WHERE ed41_i_procedimento = $ed41_i_procedimento
			   ORDER BY ed41_i_sequencia DESC       
				  	LIMIT 1";
	
	$resultado = db_query($sSql);
	
	$oUltimoPeriodo = db_utils::fieldsMemory($resultado,0);
	
	if ($oUltimoPeriodo->retorno_periodo == $ed41_i_periodoavaliacao) {
		
		return false;
	}else{
		
		return true;
	}
	
}

function Abreviar($nome,$max,$substr=false){
	
  if(strlen(trim($nome))>$max){
  	
  	$strinv = strrev(trim($nome));
  	$ultnome = substr($strinv,0,strpos($strinv," "));
  	$ultnome = strrev($ultnome);
  	$nome = strrev($strinv);
  	$prinome = substr($nome,0,strpos($nome," "));
  	$nomes = strtok($nome, " ");
  	$iniciais = "";
  	
	  while($nomes):
	   if(($nomes == 'E') || ($nomes == 'DE') || ($nomes == 'DOS') ||
	     ($nomes == 'DAS') || ($nomes == 'DA') || ($nomes == 'DO')){
	     $iniciais .= " ".$nomes;
	     $nomes = strtok(" ");
	   }elseif (($nomes == $ultnome) || ($nomes == $prinome)){
	     $nome = "";
	     $nomes = strtok(" ");
	   }else{
	     $iniciais .= " ".$nomes[0].".";
	     $nomes = strtok(" ");
	   }
	  endwhile;
	  
  	$nome =  $prinome;
  	$nome .= $iniciais;
  	$nome .= " ".$ultnome;
 }
 
 if (!$substr){
  return trim($nome);
 }else{
 	return substr(trim($nome),0,25);
 }
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$linhas = $clturma->numrows;

$iColunaRegenteWith = 281;
//Padrao mostra a classicação do aluno na turma
if(isset($classificacaoAlunoTurma)){
	if($classificacaoAlunoTurma == 2){
		$lShowNumAluno 			= true;
	}else{
		$lShowNumAluno 		  = false;
		$iColunaRegenteWith = 280;
	}
}else{
	$lShowNumAluno = true;
}

$lShowPareceres = checkPeriodo($ed220_i_procedimento, $periodo_001);
if($lShowPareceres){
	$iNumeroColunas 		= 9;
	$iColunaRegenteWith = 276;
}else{
	$iNumeroColunas 		= 10;
	
	 if($lShowNumAluno){
	 	$iColunaRegenteWith = 285;
	 }
}


for ($x=0;$x<$linhas;$x++) {

 db_fieldsmemory($result,$x);
 $result00 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo as periodo",""," ed41_i_procedimento = $ed220_i_procedimento AND ed09_i_codigo = $periodo_001"));
 
 db_fieldsmemory($result00,0);
 $result1 = $clregenciaperiodo->sql_record($clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie)"));
 
 db_fieldsmemory($result1,0);
 $result2 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed37_c_minimoaprov,ed37_c_tipo,ed09_c_descr,ed41_i_sequencia",""," ed41_i_codigo = $periodo"));
 
 db_fieldsmemory($result2,0);
 
 $pdf->setfillcolor(223);
 
 $dia = substr($ed52_d_resultfinal,8,2);
 $mes = db_mes(substr($ed52_d_resultfinal,5,2));
 $ano = substr($ed52_d_resultfinal,0,4);
 
 $result5 = $clregenteconselho->sql_record($clregenteconselho->sql_query("","case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente",""," ed235_i_turma = $ed57_i_codigo"));
 
 if($clregenteconselho->numrows>0){
  db_fieldsmemory($result5,0);
 }else{
  $regente = "";
 }
 
 $head1 = "CONSELHO DE CLASSE";
 $head2 = "Curso: $ed29_c_descr";
 $head3 = "Turma: $ed57_c_descr";
 $head4 = "Calendário: $ed52_c_descr";
 $head5 = "Etapa: $ed11_c_descr";
 $head6 = "Turno: $ed15_c_nome";
 $head7 = "Periodo: $ed09_c_descr";
 
 $pdf->addpage('L');
 //inicio cabeçalho
 $pdf->setfont('arial','b',7);
 $inicio = $pdf->getY();
  
 //Testa se é para mostrar a classificação do aluno na turma
 if($lShowNumAluno){
 	$pdf->cell(5,4,"","LRT",0,"C",0);
 }

 $pdf->cell(55,4,"","LRT",0,"R",0);
 
 //A coluna pareceres só deve aparecer caso NAO seja o ultimo periodo de avaliacao
 if($lShowPareceres){
 	$pdf->cell(18,4,"Pareceres","LRT",0,"C",0);
 }
 
 $sql2 = $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie");
 $result2 = $clregencia->sql_record($sql2);
 
 $cont = 0;
 $reg_pagina = 0;
 $sep = "";
 
 for($y=0;$y<$clregencia->numrows;$y++){
  db_fieldsmemory($result2,$y);
  
  if($y<$iNumeroColunas){
   $pdf->cell(21,4,$ed232_c_abrev,"LRT",0,"C",0);
   $pdf->cell(1,4,"","LRT",0,"C",0);
   $cont++;
   $reg_pagina .= $sep.$ed59_i_codigo;
   $sep = ",";
  }
  
 }
 
 for($y=$cont;$y<$iNumeroColunas;$y++){
  $pdf->cell(21,4,"","LRT",0,"C",0);
  $pdf->cell(1,4,"","LRT",0,"C",0);
 }
 
 $pdf->cell(5,4,"TF",1,1,"C",0);
 
 //Testa se é para mostrar a classificação do aluno na turma
 if($lShowNumAluno){
 	$pdf->cell(5,4,"N°",1,0,"C",0);
 }
 
 $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
 $pdf->cell(5,4,"S",1,0,"C",0);
 $pdf->cell(10,4,"Código",1,0,"C",0);
 
 //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
 if($lShowPareceres){ 
 	$pdf->cell(6,4,"",1,0,"C",0);
 	$pdf->cell(6,4,"",1,0,"C",0);
 	$pdf->cell(6,4,"",1,0,"C",0);
 }
 
 $cont2 = 0;
 for($y=0;$y<$clregencia->numrows;$y++){
  if($y<$iNumeroColunas){
   if($permitenotaembranco=="S"){
    $pdf->cell(9,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
    $pdf->cell(8,4,"NP",1,0,"C",0);
    $pdf->cell(4,4,"Ft.",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }else{
    $pdf->cell(15,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
    $pdf->cell(6,4,"Ft.",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }
   $cont2++;
  }
 }
 
 for($y=$cont2;$y<$iNumeroColunas;$y++){
  if($permitenotaembranco=="S"){
   $pdf->cell(9,4,"",1,0,"C",0);
   $pdf->cell(8,4,"",1,0,"C",0);
   $pdf->cell(4,4,"",1,0,"C",0);
   $pdf->cell(1,4,"",1,0,"C",0);
  }else{
   $pdf->cell(15,4,"",1,0,"C",0);
   $pdf->cell(6,4,"",1,0,"C",0);
   $pdf->cell(1,4,"",1,0,"C",0);
  }
 }
 
 $pdf->cell(5,4,"",1,1,"C",0);
 //fim cabeçalho
 $sql4= $clmatricula->sql_query("", "ed60_i_codigo, ed60_c_parecer, ed60_c_situacao, ed60_i_aluno, ed60_i_numaluno, ed47_v_nome, ed47_i_codigo", "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa"," ed60_i_turma = $ed57_i_codigo AND ed60_c_ativa = 'S' AND ed221_i_serie = $ed223_i_serie");
 
 $result4 = $clmatricula->sql_record($sql4);
 $cor1   = 0;
 $cor2   = 1;
 $cor    = "";
 $cont4  = 0;
 $cont_geral4 = 0;
 $limite = 34;
 
 for($z=0;$z<$clmatricula->numrows;$z++) {

  db_fieldsmemory($result4,$z);

  if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
    continue;
  }
  $cont4++;
  $cont_geral4++;
  if($cor==$cor1){
   $cor = $cor2;
  }else{
   $cor = $cor1;
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

    case 'CANCELADO':

      $ed60_c_situacao = 'C';
      break;

    case 'EVADIDO':

      $ed60_c_situacao = 'E';
      break;

   case 'FALECIDO':

      $ed60_c_situacao = 'F';
      break;
  }
  
  //Testa se é para mostrar a classificação do aluno na turma
  if($lShowNumAluno){
  	$pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",0);
  }
  
  $pdf->cell(40,4,Abreviar($ed47_v_nome,20,true),1,0,"L",0);
  $pdf->cell(5,4,trim($ed60_c_situacao)!="MATRICULADO"?$ed60_c_situacao:"",1,0,"L",0);
  $pdf->cell(10,4,$ed47_i_codigo,1,0,"C",0);
  
  //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
  if($lShowPareceres){
  	$pdf->cell(6,4,"",1,0,"L",0);
  	$pdf->cell(6,4,"",1,0,"L",0);
  	$pdf->cell(6,4,"",1,0,"L",0);
  }
  
  $pdf->setfont('arial','',8);
  $sql5 = "SELECT ed72_i_valornota, ed72_c_valorconceito, ed72_i_numfaltas, ed81_c_todoperiodo, ed37_c_tipo,
                  ed59_c_freqglob, ed89_i_disciplina, ed72_c_amparo,ed59_i_codigo, ed220_i_procedimento,
                  ed81_i_justificativa, ed81_i_convencaoamp, ed250_c_abrev, ed72_i_escola, ed72_c_tipo
           FROM diarioavaliacao
										            inner join diario   	   on ed95_i_codigo = ed72_i_diario
										            inner join regencia      on ed59_i_codigo = ed95_i_regencia
										            inner join disciplina    on ed12_i_codigo = ed59_i_disciplina
										            inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
										            inner join turma 				 on turma.ed57_i_codigo = regencia.ed59_i_turma
										            inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo
										            inner join serieregimemat      on serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat
										                                     	    and serieregimemat.ed223_i_serie = regencia.ed59_i_serie
										            inner join base  				 on  base.ed31_i_codigo = turma.ed57_i_base
										            left join basediscglob   on  basediscglob.ed89_i_codigo = base.ed31_i_codigo
										            left join amparo 				 on ed81_i_diario = ed95_i_codigo
										            left join convencaoamp   on ed250_i_codigo = ed81_i_convencaoamp
										            left join procavaliacao  on ed41_i_codigo = ed72_i_procavaliacao
										            left join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
           WHERE ed95_i_aluno       = $ed60_i_aluno
             AND ed95_i_regencia in ($reg_pagina)
             AND ed72_i_procavaliacao = $periodo
        ORDER BY ed59_i_ordenacao";
  $result5 = db_query($sql5);
  $linhas5 = pg_num_rows($result5);
  $cont3 = 0;
  $somafaltas = 0;
  if($linhas5>0){
   for($v=0;$v<$linhas5;$v++){
    db_fieldsmemory($result5,$v);
    if($ed60_c_parecer=="S"){
     $ed37_c_tipo = "PARECER";
    }
    if ((trim($ed37_c_tipo)=="NOTA") && $ed72_i_valornota != "") {
      $ed72_i_valornota = number_format(DBNumber::truncate($ed72_i_valornota, $iCasasDecimais), $iCasasDecimais, ".", "");
    }
    $result_proc = $clprocresultado->sql_record($clprocresultado->sql_query("","ed37_c_tipo as tipores,ed43_c_arredmedia as arredmedia, ed43_c_obtencao as obtencao",""," ed43_c_geraresultado = 'S' AND ed43_i_procedimento = $ed220_i_procedimento"));
    db_fieldsmemory($result_proc,0);
    if(trim($ed81_c_todoperiodo)=="S" || trim($ed72_c_amparo)=="S"){
     if($ed81_i_justificativa!=""){
      $aproveitamento = "AMP";
     }else{
      $aproveitamento = $ed250_c_abrev;
     }
     $frequencia = "";
    }else{
     if(trim($ed59_c_freqglob)=="F"){
      $aproveitamento = "-";
      $frequencia = $ed72_i_numfaltas;
     }elseif(trim($ed59_c_freqglob)=="A"){
      if(trim($ed37_c_tipo)=="NOTA"){
        $aproveitamento = $ed72_i_valornota!=""?($ed72_i_valornota):$ed72_i_valornota;
      }elseif(trim($ed37_c_tipo)=="PARECER"){
       $aproveitamento = "Parec";
      }else{
       $aproveitamento = $ed72_c_valorconceito;
      }
      $frequencia = $ed72_i_numfaltas;
     }else{
      if(trim($ed37_c_tipo)=="NOTA"){
       $aproveitamento = $ed72_i_valornota!=""?$ed72_i_valornota:$ed72_i_valornota;
      }elseif(trim($ed37_c_tipo)=="PARECER"){
       $aproveitamento = "Parec";
      }else{
       $aproveitamento = $ed72_c_valorconceito;
      }
      $frequencia = $ed72_i_numfaltas;
     }
    }
    
    $somafaltas += $frequencia;
    if($ed72_i_escola!=$escola||$ed72_c_tipo=="F"){
     $NE = "*";
    }else{
     $NE = "";
    }
    
    if($permitenotaembranco=="S" && $ed81_c_todoperiodo!="S" && ($obtencao=="ME" || $obtencao=="MP" || $obtencao=="SO" )){
     if(trim($ed37_c_tipo)=="NOTA"){
      if(trim($obtencao)=="ME"){
      	
        $result_media = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","sum(ed72_i_valornota)/count(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed09_c_somach = 'S' AND ed41_i_sequencia <= $ed41_i_sequencia"));
        db_fieldsmemory($result_media,0);
        $resfinal = $aprvto;
      } elseif(trim($obtencao) == "MP") {

      	//Calcula NP apenas se ha mais de periodo informado
        $sql_r = "SELECT sum(ed72_i_valornota*ed44_i_peso)/sum(ed44_i_peso) as aprvto
                    FROM diario
							                  left join diarioavaliacao  on ed72_i_diario = ed95_i_codigo
							                  left join procavaliacao    on ed41_i_codigo = ed72_i_procavaliacao
							                  left join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
							                  left join avalcompoeres    on ed44_i_procavaliacao = ed41_i_codigo
                   WHERE ed95_i_aluno    = $ed60_i_aluno
                     AND ed95_i_regencia = $ed59_i_codigo
                     AND ed72_c_amparo   = 'N'
                     AND ed72_i_valornota is not null
                     AND ed09_c_somach   = 'S'
                     AND ed41_i_sequencia <= $ed41_i_sequencia
        						 AND 2 <= ( SELECT COUNT(*)
							                    FROM diario
														                  left join diarioavaliacao  on ed72_i_diario = ed95_i_codigo
														                  left join procavaliacao    on ed41_i_codigo = ed72_i_procavaliacao
														                  left join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
														                  left join avalcompoeres    on ed44_i_procavaliacao = ed41_i_codigo
							                   WHERE ed95_i_aluno    = $ed60_i_aluno
							                     AND ed95_i_regencia = $ed59_i_codigo
							                     AND ed72_c_amparo   = 'N'
							                     AND ed72_i_valornota is not null
							                     AND ed09_c_somach   = 'S' )";
        
       $result_media = db_query($sql_r);
       db_fieldsmemory($result_media,0);
       if(isset($aprvto)){
       	$resfinal = $aprvto;
       }else{
       	$resfinal = '';
       }
         
      } elseif(trim($obtencao) == "SO") {

        $sql = $cldiarioavaliacao->sql_query("","sum(ed72_i_valornota) as aprvto,sum(to_number(ed37_c_minimoaprov,'999')) as somaminimo", ""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed09_c_somach = 'S' AND ed41_i_sequencia <= $ed41_i_sequencia");
        $result_soma = $cldiarioavaliacao->sql_record($sql);
        db_fieldsmemory($result_soma,0);
        $resfinal = $aprvto;
      } elseif(trim($obtencao)=="MN") {

        $result_maior = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","max(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null  AND ed41_i_sequencia <= $ed41_i_sequencia"));
        db_fieldsmemory($result_maior,0);
        $resfinal = $aprvto;
      } elseif(trim($obtencao)=="UN"){

        $result_ultima = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_c_amparo as ultamparo,ed72_i_valornota as aprvto","ed41_i_sequencia DESC LIMIT 1"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo"));
        db_fieldsmemory($result_ultima,0);
         $resfinal = $aprvto;
      }
      $resfinal = trim($ed60_c_situacao)!="MATRICULADO"||$aprvto==""?"":ArredondamentoNota::formatar($resfinal, $ed52_i_ano);
     }
    }
    $frequencia = trim($ed81_c_todoperiodo)=="S"?"":$frequencia;
    if($permitenotaembranco=="S"){
     $resfinal = trim($ed81_c_todoperiodo)=="S"?"":@$resfinal;
     if(trim($ed37_c_tipo)=="NOTA" && $aproveitamento<$ed37_c_minimoaprov){
      $pdf->setfont('arial','b',9);
      $pdf->cell(9,4,$NE.$aproveitamento,1,0,"C",0);
      $pdf->setfont('arial','',8);
     }else{
      $pdf->cell(9,4,$NE.$aproveitamento,1,0,"C",0);
     }
     if(trim($ed37_c_tipo)=="NOTA" && $resfinal < $ed37_c_minimoaprov){
      $pdf->setfont('arial','b',9);
      $pdf->cell(8,4, $ed41_i_sequencia == 1 ? '':$resfinal,1,0,"C",0);
      $pdf->setfont('arial','',8);
     }else{
      $pdf->cell(8,4, $ed41_i_sequencia == 1 ? '':$resfinal,1,0,"C",0);
     }
     $pdf->cell(4,4,$frequencia,1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     if(trim($ed37_c_tipo)=="NOTA" && $aproveitamento < $ed37_c_minimoaprov){
      $pdf->setfont('arial','b',9);
      $pdf->cell(15,4,$NE.$aproveitamento,1,0,"C",0);
      $pdf->setfont('arial','',8);
     }else{
      $pdf->cell(15,4,$NE.$aproveitamento,1,0,"C",0);
     }
     $pdf->cell(6,4,$frequencia,1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
    $cont3++;
    $resfinal = null;
   }
  }else{
   //se não possui registros na tabela diario e filhas
   if($permitenotaembranco=="S"){
    $pdf->cell(9,4,"",1,0,"C",0);
    $pdf->cell(8,4,"",1,0,"C",0);
    $pdf->cell(4,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }else{
    $pdf->cell(15,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }
   $cont3++;
  }
  for($y=$cont3;$y<$iNumeroColunas;$y++){
   if($permitenotaembranco=="S"){
    $pdf->cell(9,4,"",1,0,"C",0);
    $pdf->cell(8,4,"",1,0,"C",0);
    $pdf->cell(4,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }else{
    $pdf->cell(15,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }
  }

  $pdf->cell(5,4,getTotadeFaltas($ed60_i_aluno, $ed57_i_codigo, $ed223_i_serie, $periodo),1,1,"C",0);
  $pdf->setfont('arial','b',7);

  if($cont4==$limite && $cont_geral4<$clmatricula->numrows){
   //inicio rodape
   $pdf->cell($iColunaRegenteWith,6,"Regente Conselheiro:______________________________________________","TLR",1,"C",0);
   $pdf->cell($iColunaRegenteWith,4,"                    ".trim($regente),"LRB",1,"C",0);
   $pdf->addpage('L');
   //inicio cabeçalho
   $pdf->setfont('arial','b',7);
   $inicio = $pdf->getY();
   
   //Testa se é para mostrar a classificação do aluno na turma
   if($lShowNumAluno){
   	$pdf->cell(5,4,"","LRT",0,"C",0);
   }
   
   $pdf->cell(55,4,"","LRT",0,"R",0);
   
   //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
   if($lShowPareceres){
   	$pdf->cell(18,4,"Pareceres","LRT",0,"C",0);
   }
   
   $sql2 = $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_codigo in ($reg_pagina) AND ed59_i_serie = $ed223_i_serie");
   $result2 = $clregencia->sql_record($sql2);
   $cont = 0;
   $reg_pagina = 0;
   $sep = "";
   for($y=0;$y<$clregencia->numrows;$y++){
    db_fieldsmemory($result2,$y);
    if($y<$iNumeroColunas){
     $pdf->cell(21,4,$ed232_c_abrev,"LRT",0,"C",0);
     $pdf->cell(1,4,"","LRT",0,"C",0);
     $cont++;
     $reg_pagina .= $sep.$ed59_i_codigo;
     $sep = ",";
    }
   }
   for($y=$cont;$y<$iNumeroColunas;$y++){
    $pdf->cell(21,4,"","LRT",0,"C",0);
   }
   $pdf->cell(5,4,"TF",1,1,"C",0);
   
   //Testa se é para mostrar a classificação do aluno na turma
   if($lShowNumAluno){
   	 $pdf->cell(5,4,"N°",1,0,"C",0);
   }
   
   $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
   $pdf->cell(5,4,"S",1,0,"C",0);
   $pdf->cell(10,4,"Código",1,0,"C",0);
   
   //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
   if($lShowPareceres){
   	$pdf->cell(6,4,"",1,0,"C",0);
   	$pdf->cell(6,4,"",1,0,"C",0);
   	$pdf->cell(6,4,"",1,0,"C",0);
   }
   
   $cont2 = 0;
   for($y=0;$y<$clregencia->numrows;$y++){
    if($y<$iNumeroColunas){
     if($permitenotaembranco=="S"){
      $pdf->cell(9,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
      $pdf->cell(8,4,"NP",1,0,"C",0);
      $pdf->cell(4,4,"Ft.",1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }else{
      $pdf->cell(15,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
      $pdf->cell(6,4,"Ft.",1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }
     $cont2++;
    }
   }
   for($y=$cont2;$y<$iNumeroColunas;$y++){
    if($permitenotaembranco=="S"){
     $pdf->cell(9,4,"",1,0,"C",0);
     $pdf->cell(8,4,"",1,0,"C",0);
     $pdf->cell(4,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     $pdf->cell(15,4,"",1,0,"C",0);
     $pdf->cell(6,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
   }
   $pdf->cell(5,4,"",1,1,"C",0);
   //fim cabeçalho
   $cont4 = 0;
  }
 }
 for($z=$cont4;$z<$limite;$z++){
  if($cor==$cor1){
   $cor = $cor2;
  }else{
   $cor = $cor1;
  }
  
  //Testa se é para mostrar a classificação do aluno na turma
  if($lShowNumAluno){
  	$pdf->cell(5,4,"",1,0,"C",0);
  }
  
  $pdf->cell(40,4,"",1,0,"C",0);
  $pdf->cell(5,4,"",1,0,"C",0);
  $pdf->cell(10,4,"",1,0,"C",0);
  
  //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
  if($lShowPareceres){
  	$pdf->cell(6,4,"",1,0,"C",0);
  	$pdf->cell(6,4,"",1,0,"C",0);
	  $pdf->cell(6,4,"",1,0,"C",0);
  }
  
  for($q=0;$q<$iNumeroColunas;$q++){
   if($permitenotaembranco=="S"){
    $pdf->cell(9,4,"",1,0,"C",0);
    $pdf->cell(8,4,"",1,0,"C",0);
    $pdf->cell(4,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }else{
    $pdf->cell(15,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }
  }
  $pdf->cell(5,4,"",1,1,"C",0);
 }
 //inicio rodape
 $pdf->cell($iColunaRegenteWith,6,"Regente Conselheiro:______________________________________________","LRT",1,"C",0);
 $pdf->cell($iColunaRegenteWith,4,"                    ".trim($regente),"LRB",1,"C",0);
 //fim rodape
 $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $ed57_i_codigo AND ed59_i_codigo not in ($reg_pagina) AND ed59_i_serie = $ed223_i_serie");
 $result2 = $clregencia->sql_record($sql2);

 /////////////////////////////////////////////////////////////////////////////////////////

 if($clregencia->numrows>0){
  $pdf->addpage('L');
  $pdf->setfont('arial','b',7);
  $inicio = $pdf->getY();
  
  //Testa se é para mostrar a classificação do aluno na turma
  if($lShowNumAluno){
  	$pdf->cell(5,4,"","LRT",0,"C",0);
  }
  
  $pdf->cell(55,4,"","LRT",0,"R",0);
  
  //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
  if($lShowPareceres){
  	$pdf->cell(18,4,"Pareceres","LRT",0,"C",0);
  }
  
  $cont = 0;
  $reg_pagina = 0;
  $sep = "";
  for($y=0;$y<$clregencia->numrows;$y++){
   db_fieldsmemory($result2,$y);
   if($y<$iNumeroColunas){
    $pdf->cell(21,4,$ed232_c_abrev,"LRT",0,"C",0);
    $pdf->cell(1,4,"","LRT",0,"C",0);
    $cont++;
    $reg_pagina .= $sep.$ed59_i_codigo;
    $sep = ",";
   }
  }
  for($y=$cont;$y<$iNumeroColunas;$y++){
   $pdf->cell(21,4,"","LRT",0,"C",0);
   $pdf->cell(1,4,"","LRT",0,"C",0);
  }
  $pdf->cell(5,4,"TF",1,1,"C",0);
  
  //Testa se é para mostrar a classificação do aluno na turma
  if($lShowNumAluno){
  	$pdf->cell(5,4,"N°",1,0,"C",0);
  }
  
  $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
  $pdf->cell(5,4,"S",1,0,"C",0);
  $pdf->cell(10,4,"Código",1,0,"C",0);
  
  //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
  if($lShowPareceres){
  	$pdf->cell(6,4,"",1,0,"C",0);
  	$pdf->cell(6,4,"",1,0,"C",0);
  	$pdf->cell(6,4,"",1,0,"C",0);
  }
  
  $cont2 = 0;
  for($y=0;$y<$clregencia->numrows;$y++){
   if($y<$iNumeroColunas){
    if($permitenotaembranco=="S"){
     $pdf->cell(9,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
     $pdf->cell(8,4,"NP",1,0,"C",0);
     $pdf->cell(4,4,"Ft.",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     $pdf->cell(15,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
     $pdf->cell(6,4,"Ft.",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
    $cont2++;
   }
  }
  for($y=$cont2;$y<$iNumeroColunas;$y++){
   if($permitenotaembranco=="S"){
    $pdf->cell(9,4,"",1,0,"C",0);
    $pdf->cell(8,4,"",1,0,"C",0);
    $pdf->cell(4,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }else{
    $pdf->cell(15,4,"",1,0,"C",0);
    $pdf->cell(6,4,"",1,0,"C",0);
    $pdf->cell(1,4,"",1,0,"C",0);
   }
  }
  $pdf->cell(5,4,"",1,1,"C",0);
  //fim cabeçalho
  $sql4 = $clmatricula->sql_query("","ed60_i_codigo,ed60_c_parecer,ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed47_v_nome,ed47_i_codigo","ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa"," ed60_i_turma = $ed57_i_codigo AND ed60_c_ativa = 'S' AND ed221_i_serie = $ed223_i_serie");
  $result4 = $clmatricula->sql_record($sql4);
  $cor1 = 0;
  $cor2 = 1;
  $cor = "";
  $cont4 = 0;
  $cont_geral4 = 0;
  $limite = 34;
  for ($z=0;$z<$clmatricula->numrows;$z++) {

    if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
      continue;
    }
   db_fieldsmemory($result4,$z);
   if($cor==$cor1){
    $cor = $cor2;
   }else{
    $cor = $cor1;
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
   
   	case 'CANCELADO':
   
   		$ed60_c_situacao = 'C';
   		break;
   
   	case 'EVADIDO':
   
   		$ed60_c_situacao = 'E';
   		break;
   
   	case 'FALECIDO':
   
   		$ed60_c_situacao = 'F';
   		break;
   }
   
   $cont4++;
   $cont_geral4++;
   
   //Testa se é para mostrar a classificação do aluno na turma
   if($lShowNumAluno){
   		$pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",0);
   }
   
   $pdf->cell(40,4,Abreviar($ed47_v_nome,20,true),1,0,"L",0);
   $pdf->cell(5,4,trim($ed60_c_situacao)!="MATRICULADO"?substr($ed60_c_situacao,0,2):"",1,0,"L",0);
   $pdf->cell(10,4,$ed47_i_codigo,1,0,"C",0);
   
   //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
   if($lShowPareceres){
   	$pdf->cell(6,4,"",1,0,"L",0);
   	$pdf->cell(6,4,"",1,0,"L",0);
   	$pdf->cell(6,4,"",1,0,"L",0);
   }
   
   $pdf->setfont('arial','',8);
   $sql5 = "SELECT ed72_i_valornota,ed72_c_valorconceito,ed72_i_numfaltas,ed81_c_todoperiodo,ed37_c_tipo,ed59_c_freqglob,ed89_i_disciplina,
                   ed72_c_amparo,ed59_i_codigo,ed220_i_procedimento,ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev,
                   ed72_i_escola,ed72_c_tipo
           FROM diarioavaliacao
            inner join diario on ed95_i_codigo = ed72_i_diario
            inner join regencia on ed59_i_codigo = ed95_i_regencia
            inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma
            inner join turmaserieregimemat on turmaserieregimemat.ed220_i_turma = turma.ed57_i_codigo
            inner join serieregimemat on serieregimemat.ed223_i_codigo = turmaserieregimemat.ed220_i_serieregimemat
                                      and serieregimemat.ed223_i_serie = regencia.ed59_i_serie
            inner join base  on  base.ed31_i_codigo = turma.ed57_i_base
            left join basediscglob  on  basediscglob.ed89_i_codigo = base.ed31_i_codigo
            left join amparo on ed81_i_diario = ed95_i_codigo
            left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp
            left join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
            left join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed95_i_regencia in ($reg_pagina)
           AND ed72_i_procavaliacao = $periodo
           ORDER BY ed59_i_ordenacao";
   
   $result5 = db_query($sql5);
   $linhas5 = pg_num_rows($result5);
   $cont3 = 0;
   $somafaltas = 0;
   if($linhas5>0){
    for($v=0;$v<$linhas5;$v++){
     db_fieldsmemory($result5,$v);
     if($ed60_c_parecer=="S"){
      $ed37_c_tipo = "PARECER";
     }
     if ((trim($ed37_c_tipo)=="NOTA") && $ed72_i_valornota != "") {
      $ed72_i_valornota = number_format(DBNumber::truncate($ed72_i_valornota, $iCasasDecimais), $iCasasDecimais, ".", "");
     }
     if(trim($ed81_c_todoperiodo)=="S" || trim($ed72_c_amparo)=="S"){
      if($ed81_i_justificativa!=""){
       $aproveitamento = "AMP";
      }else{
       $aproveitamento = $ed250_c_abrev;
      }
      $frequencia = "";
     }else{
      if(trim($ed59_c_freqglob)=="F"){
       $aproveitamento = "-";
       $frequencia = $ed72_i_numfaltas;
      } elseif(trim($ed59_c_freqglob)=="A") {
       if(trim($ed37_c_tipo)=="NOTA"){
         $aproveitamento = $ed72_i_valornota!=""?($ed72_i_valornota):$ed72_i_valornota;
       }elseif(trim($ed37_c_tipo)=="PARECER"){
        $aproveitamento = "Parec";
       }else{
        $aproveitamento = $ed72_c_valorconceito;
       }
       $frequencia = $ed72_i_numfaltas;
      }else{
       if(trim($ed37_c_tipo)=="NOTA") {
         $aproveitamento = $ed72_i_valornota!=""?($ed72_i_valornota):$ed72_i_valornota;
       }elseif(trim($ed37_c_tipo)=="PARECER") {
        $aproveitamento = "Parec";
       }else{
        $aproveitamento = $ed72_c_valorconceito;
       }
       $frequencia = $ed72_i_numfaltas;
      }
     }
     $somafaltas += $frequencia;
     if($ed72_i_escola!=$escola||$ed72_c_tipo=="F"){
      $NE = "*";
     }else{
      $NE = "";
     }

     if($permitenotaembranco=="S" && $ed81_c_todoperiodo!="S"){
       if(trim($ed37_c_tipo=="NOTA")){
         if(trim($obtencao)=="ME"){

           $result_media = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","sum(ed72_i_valornota)/count(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed41_i_sequencia <= $ed41_i_sequencia"));
           db_fieldsmemory($result_media,0);
           $resfinal = $aprvto;
         } elseif (trim($obtencao)=="MP"){
           //Calcula NP apenas se ha mais de periodo informado
				   $sql_r = "SELECT sum(ed72_i_valornota*ed44_i_peso)/sum(ed44_i_peso) as aprvto
				               FROM diario
									                  left join diarioavaliacao  on ed72_i_diario = ed95_i_codigo
									                  left join procavaliacao    on ed41_i_codigo = ed72_i_procavaliacao
									                  left join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
									                  left join avalcompoeres    on ed44_i_procavaliacao = ed41_i_codigo
				              WHERE ed95_i_aluno    = $ed60_i_aluno
				                AND ed95_i_regencia = $ed59_i_codigo
				                AND ed72_c_amparo   = 'N'
				                AND ed72_i_valornota is not null
				                AND ed09_c_somach   = 'S'
				                AND ed41_i_sequencia <= $ed41_i_sequencia
				        			  AND 2 <= ( SELECT COUNT(*)
											               FROM diario
																                  left join diarioavaliacao  on ed72_i_diario = ed95_i_codigo
																                  left join procavaliacao    on ed41_i_codigo = ed72_i_procavaliacao
																                  left join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
																                  left join avalcompoeres    on ed44_i_procavaliacao = ed41_i_codigo
											              WHERE ed95_i_aluno    = $ed60_i_aluno
											                AND ed95_i_regencia = $ed59_i_codigo
											                AND ed72_c_amparo   = 'N'
											                AND ed72_i_valornota is not null
											                AND ed09_c_somach   = 'S' )";
				        
				   $result_media = db_query($sql_r);
				   db_fieldsmemory($result_media,0);
				   if(isset($aprvto)){
				    	$resfinal = $aprvto;
				   }else{
				     	$resfinal = '';
				   }
				         	
         }elseif(trim($obtencao)=="SO"){

           $result_soma = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","sum(ed72_i_valornota) as aprvto,sum(to_number(ed37_c_minimoaprov,'999')) as somaminimo",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed41_i_sequencia <= $ed41_i_sequencia"));
           db_fieldsmemory($result_soma,0);
           $resfinal = $aprvto;
         }elseif(trim($obtencao)=="MN"){
           $result_maior = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","max(ed72_i_valornota) as aprvto",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_c_amparo = 'N' AND ed72_i_valornota is not null AND ed41_i_sequencia <= $ed41_i_sequencia"));
           db_fieldsmemory($result_maior,0);
           $resfinal = $aprvto;
         }elseif(trim($obtencao)=="UN"){
           $result_ultima = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_c_amparo as ultamparo,ed72_i_valornota as aprvto","ed41_i_sequencia DESC LIMIT 1"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo"));
           db_fieldsmemory($result_ultima,0);
           $resfinal = $aprvto;
         }
         $resfinal = trim($ed60_c_situacao)!="MATRICULADO"||isset($aprvto)==""?"":ArredondamentoNota::formatar($resfinal, $ed52_i_ano);
       }
     }
     $frequencia = trim($ed81_c_todoperiodo)=="S"?"":$frequencia;
     if($permitenotaembranco=="S"){
      $resfinal = trim($ed81_c_todoperiodo)=="S"?"":$resfinal;
      if(trim($ed37_c_tipo)=="NOTA" && $aproveitamento<$ed37_c_minimoaprov){
       $pdf->setfont('arial','b',9);
       $pdf->cell(9,4,$NE.$aproveitamento,1,0,"C",0);
       $pdf->setfont('arial','',8);
      }else{
       $pdf->cell(9,4,$NE.$aproveitamento,1,0,"C",0);
      }
      if(trim($ed37_c_tipo)=="NOTA" && $resfinal<$ed37_c_minimoaprov){ 
       $pdf->setfont('arial','b',9);
       $pdf->cell(8,4, $ed41_i_sequencia == 1 ? '':$resfinal,1,0,"C",0);
       $pdf->setfont('arial','',8);
      }else{
       $pdf->cell(8,4, $ed41_i_sequencia == 1 ? '':$resfinal,1,0,"C",0);
      }
      $pdf->cell(4,4,$frequencia,1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }else{
      if(trim($ed37_c_tipo)=="NOTA" && $aproveitamento<$ed37_c_minimoaprov){
       $pdf->setfont('arial','b',9);
       $pdf->cell(15,4,$NE.$aproveitamento,1,0,"C",0);
       $pdf->setfont('arial','',8);
      }else{
       $pdf->cell(15,4,$NE.$aproveitamento,1,0,"C",0);
      }
      $pdf->cell(6,4,$frequencia,1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }
     $cont3++;
     $resfinal = null;
    }
   }else{
    if($permitenotaembranco=="S"){
     $pdf->cell(9,4,"",1,0,"C",0);
     $pdf->cell(8,4,"",1,0,"C",0);
     $pdf->cell(4,4,$frequencia,1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     $pdf->cell(15,4,"",1,0,"C",0);
     $pdf->cell(6,4,$frequencia,1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
    $cont3++;
   }
   for($y=$cont3;$y<$iNumeroColunas;$y++){
    if($permitenotaembranco=="S"){
     $pdf->cell(9,4,"",1,0,"C",0);
     $pdf->cell(8,4,"",1,0,"C",0);
     $pdf->cell(4,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     $pdf->cell(15,4,"",1,0,"C",0);
     $pdf->cell(6,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
   }
   $pdf->cell(5,4,getTotadeFaltas($ed60_i_aluno, $ed57_i_codigo, $ed223_i_serie, $periodo),1,1,"C",0);
   $pdf->setfont('arial','b',7);

   if($cont4==$limite && $cont_geral4<$clmatricula->numrows){
    //inicio rodape
    $pdf->cell($iColunaRegenteWith,6,"Regente Conselheiro:______________________________________________","LRT",1,"C",0);
    $pdf->cell($iColunaRegenteWith,4,"                    ".trim($regente),"LRB",1,"C",0);
    //fim rodape
    $pdf->addpage('L');
    //inicio cabeçalho
    $pdf->setfont('arial','b',7);
    $inicio = $pdf->getY();
    
    //Testa se é para mostrar a classificação do aluno na turma
    if($lShowNumAluno){
    	$pdf->cell(5,4,"","LRT",0,"C",0);
    }
    
    $pdf->cell(55,4,"","LRT",0,"R",0);
    $pdf->cell(18,4,"","LRT",0,"R",0);
    $sql2 = $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_codigo in ($reg_pagina) AND ed59_i_serie = $ed223_i_serie");
    $result2 = $clregencia->sql_record($sql2);
    $cont = 0;
    $reg_pagina = 0;
    $sep = "";
    for($y=0;$y<$clregencia->numrows;$y++){
     db_fieldsmemory($result2,$y);
     if($y<$iNumeroColunas){
      $pdf->cell(21,4,$ed232_c_abrev,"LRT",0,"C",0);
      $pdf->cell(1,4,"","LRT",0,"C",0);
      $cont++;
      $reg_pagina .= $sep.$ed59_i_codigo;
      $sep = ",";
     }
    }
    for($y=$cont;$y<$iNumeroColunas;$y++){
     $pdf->cell(21,4,"","LRT",0,"C",0);
     $pdf->cell(1,4,"","LRT",0,"C",0);
    }
    $pdf->cell(5,4,"TF",1,1,"C",0);
    
    //Testa se é para mostrar a classificação do aluno na turma
    if($lShowNumAluno){
    	$pdf->cell(5,4,"N°",1,0,"C",0);
    }
    
    $pdf->cell(40,4,"Nome do Aluno",1,0,"C",0);
    $pdf->cell(5,4,"S",1,0,"C",0);
    $pdf->cell(10,4,"Código",1,0,"C",0);
    
    //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
    if($lShowPareceres){
    	$pdf->cell(6,4,"",1,0,"C",0);
    	$pdf->cell(6,4,"",1,0,"C",0);
    	$pdf->cell(6,4,"",1,0,"C",0);
    }
    
    $cont2 = 0;
    for($y=0;$y<$clregencia->numrows;$y++){
     if($y<$iNumeroColunas){
      if($permitenotaembranco=="S"){
       $pdf->cell(9,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
       $pdf->cell(8,4,"NP",1,0,"C",0);
       $pdf->cell(4,4,"Ft.",1,0,"C",0);
       $pdf->cell(1,4,"",1,0,"C",0);
      }else{
       $pdf->cell(15,4,substr($ed37_c_tipo,0,5),1,0,"C",0);
       $pdf->cell(6,4,"Ft.",1,0,"C",0);
       $pdf->cell(1,4,"",1,0,"C",0);
      }
      $cont2++;
     }
    }
    for($y=$cont2;$y<$iNumeroColunas;$y++){
     if($permitenotaembranco=="S"){
      $pdf->cell(9,4,"",1,0,"C",0);
      $pdf->cell(8,4,"",1,0,"C",0);
      $pdf->cell(4,4,"",1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }else{
      $pdf->cell(15,4,"",1,0,"C",0);
      $pdf->cell(6,4,"",1,0,"C",0);
      $pdf->cell(1,4,"",1,0,"C",0);
     }
    }
    $pdf->cell(5,4,"",1,1,"C",0);
    //fim cabeçalho
    $cont4 = 0;
   }
  }
  for($z=$cont4;$z<$limite;$z++){
   if($cor==$cor1){
    $cor = $cor2;
   }else{
    $cor = $cor1;
   }
   
   //Testa se é para mostrar a classificação do aluno na turma
   if($lShowNumAluno){
   	$pdf->cell(5,4,"",1,0,"C",0);
   }
   
   $pdf->cell(40,4,"",1,0,"C",0);
   $pdf->cell(5,4,"",1,0,"C",0);
   $pdf->cell(10,4,"",1,0,"C",0);
   
   //A coluna pareceres só deve aparecer NAO caso seja o ultimo periodo de avaliacao
   if($lShowPareceres){
   	$pdf->cell(6,4,"",1,0,"C",0);
   	$pdf->cell(6,4,"",1,0,"C",0);
   	$pdf->cell(6,4,"",1,0,"C",0);
   }
   
   for($q=0;$q<$iNumeroColunas;$q++){
    if($permitenotaembranco=="S"){
     $pdf->cell(9,4,"",1,0,"C",0);
     $pdf->cell(8,4,"",1,0,"C",0);
     $pdf->cell(4,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }else{
     $pdf->cell(15,4,"",1,0,"C",0);
     $pdf->cell(6,4,"",1,0,"C",0);
     $pdf->cell(1,4,"",1,0,"C",0);
    }
   }
   $pdf->cell(5,4,"",1,1,"C",0);
  }
  //inicio rodape
  $pdf->cell($iColunaRegenteWith,6,"Regente Conselheiro:______________________________________________","LRT",1,"C",0);
  $pdf->cell($iColunaRegenteWith,4,"                    ".trim($regente),"LRB",1,"C",0);
  //fim rodape
 }
}
$pdf->Output();
?>