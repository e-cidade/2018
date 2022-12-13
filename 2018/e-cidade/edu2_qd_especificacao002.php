<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_calendario_classe.php");
include("classes/db_periodocalendario_classe.php");
include("classes/db_escoladiretor_classe.php");
include("classes/db_ensino_classe.php");
include("classes/db_edu_parametros_classe.php");
$clescoladiretor     = new cl_escoladiretor;
$clcalendario        = new cl_calendario;
$clperiodocalendario = new cl_periodocalendario;
$clensino            = new cl_ensino;
$cledu_parametros    = new cl_edu_parametros;
$sCampos             = "ed52_i_ano as ano_calendario,ed52_c_descr as descr_calendario";
$result_ano          = $clcalendario->sql_record($clcalendario->sql_query_file("",
                                                                               $sCampos,
                                                                               "",
                                                                               " ed52_i_codigo = $calendario"
                                                                              )
                                                );
db_fieldsmemory($result_ano,0);

/*
 * Definindo ultimo dia do mes
 */
if ($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12) {
  $dialimite = 31;
} else if ($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) {
  $dialimite = 30;
} else {
  $dialimite = 28;
}

$datalimite = $ano_calendario."-".(strlen($mes)==1?"0".$mes:$mes)."-".$dialimite;

$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("",
                                                                                "ed233_c_limitemov,ed233_c_database",
                                                                                "",
                                                                                " ed233_i_escola = $iEscola"
                                                                               )
                                                  );
if ($cledu_parametros->numrows > 0) {
	
  db_fieldsmemory($result_parametros,0);
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
  
  $limitemov     = explode("/",$ed233_c_limitemov);
  $dia_limitemov = $limitemov[0];
  $mes_limitemov = $limitemov[1];
  
  if (@!checkdate($mes_database,$dia_database,$ano_calendario)) {
  	 
    ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
           deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>
           Valor atual do parâmetro: <?=$ed233_c_database?><br>
           Data Base para Cálculo Idade: 
           <?=$dia_database."/".$mes_database."/".$ano_calendario?> (Data Inválida)<br><br></b>
        <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
    <?
    exit;
    
  }
  
  $databasecalc  = $ano_calendario."-".(strlen($mes_database)==1?"0".$mes_database:$mes_database);
  $databasecalc .= "-".(strlen($dia_database)==1?"0".$dia_database:$dia_database);
  $datalimitemov  = $ano_calendario."-".(strlen($mes_limitemov)==1?"0".$mes_limitemov:$mes_limitemov);
  $datalimitemov .= "-".(strlen($dia_limitemov)==1?"0".$dia_limitemov:$dia_limitemov);
  
} else {
  $databasecalc = $ano_calendario."-12-31";
  $datalimitemov = $ano_calendario."-01-01";
}

$sql    = " select * from aluno ";
$sql   .= "  inner join matricula on ed47_i_codigo=ed60_i_aluno ";
$sql   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
$sql   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
$sql   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
$sql   .= "  inner join calendario on ed52_i_codigo=ed57_i_calendario ";
$sql   .= "  where ed57_i_escola=$iEscola ";
$sql   .= "   and ed52_i_codigo=$calendario ";
$sql   .= "  and ed11_i_ensino in ($nivelensino) ";
$sql   .= "  limit 1 ";

$result = pg_query($sql);
$linhas = pg_num_rows($result);
if ($linhas == 0) {?>

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

if ($diretor != "") {
	
  $arr_assinatura = explode("-",$diretor);
  $z01_nome       = $arr_assinatura[1];
  $funcao         = $arr_assinatura[0].":";
  
} else {
	
  $z01_nome = "......................................................................................";
  $funcao   = "Emissor:";
  
}

if ($modalidade == "1") {
	
  $comecaidade  = 5;
  $terminaidade = 16;
  
} else if ($modalidade == "3") {
	
  $comecaidade  = 14;
  $terminaidade = 25;
  
}


$pdf        = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1      = "Quadro de Especificação";
$head2      = "Mês: ".db_mes($mes,1);
$head3      = "Calendário: ".$descr_calendario;
$head4      = "Data Base calculo da idade: ".db_formatar($databasecalc,'d');
$head5      = "Nível de ensino:";
$codensinos = explode(",",$nivelensino);

for ($x = 0; $x < count($codensinos); $x++) {
	
  $result10 = $clensino->sql_record($clensino->sql_query("",
                                                         "ed10_i_codigo as codigo_ensino,ed10_c_descr as descrensino",
                                                         "",
                                                         " ed10_i_codigo = $codensinos[$x]"
                                                        )
                                   );
  db_fieldsmemory($result10,0);
  $cabecalho  = "head".($x+6);
  $$cabecalho = "-> ".$codigo_ensino." - ".$descrensino;
  
}

$sql             = " select distinct ed11_i_codigo,ed11_c_abrev,ed11_i_ensino,ed10_c_abrev,ed10_i_codigo ";
$sql            .= " from serie ";
$sql            .= "  inner join matriculaserie on ed221_i_serie=ed11_i_codigo ";
$sql            .= "  inner join matricula on ed60_i_codigo=ed221_i_matricula "; 
$sql            .= "  inner join turma on ed57_i_codigo=ed60_i_turma "; 
$sql            .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
$sql            .= "  inner join ensino on ed10_i_codigo = ed11_i_ensino ";
$sql            .= " where ed57_i_escola=$iEscola ";
$sql            .= " and ed52_i_codigo=$calendario ";
$sql            .= " and ed221_c_origem = 'S' ";
$sql            .= " and ed11_i_ensino in ($nivelensino) ";
$sql            .= " order by ed11_i_ensino ";
$result1         = pg_query($sql);
$linhas1         = pg_num_rows($result1);
$largura_colunas = floor(162/$linhas1);
$pdf->Addpage("");
$cor = "0";
$pdf->setfillcolor(223);
$pdf->setfont('arial','',7);

/*
 * TABELA 1
 */
$sql_trans    = " SELECT count(*) as qtdaluno, ";
$sql_trans   .= "       ed47_v_sexo, ";
$sql_trans   .= "       coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
$sql_trans   .= "       ed221_i_serie as seriealuno ";
$sql_trans   .= " FROM matricula ";
$sql_trans   .= "  inner join aluno on ed60_i_aluno=ed47_i_codigo ";
$sql_trans   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
$sql_trans   .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
$sql_trans   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
$sql_trans   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
$sql_trans   .= " WHERE (ed60_c_situacao = 'TRANSFERIDO REDE' or ed60_c_situacao = 'TRANSFERIDO FORA') ";
$sql_trans   .= "        and (ed60_d_datasaida <= '$datalimite' and ed60_d_datasaida > '$datalimitemov') ";
$sql_trans   .= " AND ed52_i_codigo = $calendario ";
$sql_trans   .= " AND ed57_i_escola = $iEscola ";
$sql_trans   .= " AND ed11_i_ensino in ($nivelensino) ";
$sql_trans   .= " AND ed221_c_origem = 'S' ";
$sql_trans   .= " GROUP BY ed47_v_sexo,idadealuno,seriealuno ";
$sql_trans   .= " ORDER BY idadealuno,seriealuno";
$result_trans = pg_query($sql_trans);
$linhas_trans = pg_num_rows($result_trans);
$primeiro     = pg_result($result1,0,'ed10_i_codigo');
$pdf->cell($largura_colunas*$linhas1+28,4,"TRANSFERÊNCIAS",1,1,"C",$cor);
$pdf->cell(20,5,"",1,0,"C",$cor);
$cont = 0;
for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $cont++;
  
  if ($primeiro != $ed10_i_codigo) {
  	
    $pdf->cell($largura_colunas*($cont-1),5,pg_result($result1,$x-1,'ed10_i_codigo'),1,0,"C",$cor);
    $primeiro = $ed10_i_codigo;
    $cont     = 1;
    
  }
}

$pdf->cell($largura_colunas*$cont,5,$primeiro,1,0,"C",$cor);
$pdf->cell(8,5,"","LRT",1,"C",$cor);
$pdf->cell(20,5,"Etapa","LRT",0,"R",$cor);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas,5,$ed11_c_abrev,1,0,"C",$cor);
  
}

$pdf->cell(8,5,"","LRB",1,"C",$cor);
$pdf->cell(14,4,"Idade","LB",0,"L",$cor);
$pdf->cell(6,4,"Sexo","RB",0,"R",$cor);
$pdf->line(10,44,24,53);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas/2,4,"M",1,0,"C",$cor);
  $pdf->cell($largura_colunas/2,4,"F",1,0,"C",$cor);
  
}

$pdf->cell(8,4,"Total",1,1,"C",$cor);

for ($idade = $comecaidade; $idade < $terminaidade; $idade++) {
	
  if ($modalidade == "1") {
  	
    if ($idade == 5) {
      $pdf->cell(20,4,"-6",1,0,"C",$cor);
    } else if ($idade == 15) {
      $pdf->cell(20,4,"+14",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,$idade,1,0,"C",$cor);
    }
    
  } else if ($modalidade == "3") {
  	
    if ($idade == 14) {
      $pdf->cell(20,4,"-15",1,0,"C",$cor);
    } else if ($idade == 22) {
      $pdf->cell(20,4,"22/35",1,0,"C",$cor);
    } else if ($idade == 23) {
      $pdf->cell(20,4,"36/50",1,0,"C",$cor);
    } else if ($idade == 24) {
      $pdf->cell(20,4,"+50",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,"$idade",1,0,"C",$cor);
    }
  }
  $tlinha = 0;
  $vcont  = 0;
  for ($c1 = 0; $c1 < $linhas1; $c1++) {
  	
    db_fieldsmemory($result1,$c1);
    $masculino = 0;
    $feminino  = 0;
    for ($t1 = 0; $t1 < $linhas_trans; $t1++) {
    	
      db_fieldsmemory($result_trans,$t1);
      if ($modalidade == "1") {
      	
        if ($idade == 5) {
        	
          if ($idadealuno < 6 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 15) {
        	
          if ($idadealuno > 14 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        } 
        
      } else if ($modalidade == "3") {
      	
        if ($idade == 14) {
        	
          if ($idadealuno < 15 && $ed11_i_codigo == $seriealuno) {
       
          	if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 22) {
        	
          if ($idadealuno > 21 && $idadealuno < 36 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 23) {
        	
          if ($idadealuno > 35 && $idadealuno < 51 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 24) {
        	
          if ($idadealuno > 50 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        }
      }
    }
    $tlinha       = $tlinha+($masculino+$feminino);
    @$vet[$vcont] = $vet[$vcont]+$masculino;
    $vcont        = $vcont+1;
    @$vet[$vcont] = $vet[$vcont]+$feminino;
    $vcont        = $vcont+1;
    if ($c1 != $linhas1-1) {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      
    } else {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      $pdf->cell(8,4,"$tlinha",1,1,"C",$cor);
      
    }
  }
  $pdf->setfont('arial','',7);
}

$pdf->cell(20,6,"Total",1,0,"C",$cor);
$total = 0;
for ($x = 0; $x < ($linhas1*2); $x++) {
	
  $pdf->cell($largura_colunas/2,6,"$vet[$x]",1,0,"C",$cor);
  $total = $total+$vet[$x];
  
}

$pdf->cell(8,6,$total,1,1,"C",$cor);
$pdf->cell(190,4,"",0,1,"C",$cor);
unset($vet);

/*
 * TABELA 2
 */
$sql_trans    = " SELECT count(*) as qtdaluno, ";
$sql_trans   .= "       ed47_v_sexo, ";
$sql_trans   .= "       coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
$sql_trans   .= "       ed221_i_serie as seriealuno ";
$sql_trans   .= " FROM matricula ";
$sql_trans   .= "  inner join aluno on ed60_i_aluno=ed47_i_codigo ";
$sql_trans   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
$sql_trans   .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
$sql_trans   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
$sql_trans   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
$sql_trans   .= " WHERE ed60_d_datasaida <= '$datalimite' ";
$sql_trans   .= " AND ed52_i_codigo = $calendario ";
$sql_trans   .= " AND ed57_i_escola = $iEscola ";
$sql_trans   .= " AND ed11_i_ensino in ($nivelensino) ";
$sql_trans   .= " AND (trim(ed60_c_situacao) in('CANCELADO', 'EVADIDO', 'MATRICULA TRANCADA', 'MATRICULA INDEFERIDA')) ";
$sql_trans   .= " AND ed221_c_origem = 'S' ";
$sql_trans   .= " GROUP BY ed47_v_sexo,idadealuno,seriealuno ";
$sql_trans   .= " ORDER BY idadealuno,seriealuno ";
$result_trans = pg_query($sql_trans);
$linhas_trans = pg_num_rows($result_trans);
$primeiro     = pg_result($result1,0,'ed10_i_codigo');
$pdf->cell($largura_colunas*$linhas1+28,4,"EVASÃO / CANCELAMENTO / MATRICULA TRANCADA / MATRICULA INDEFERIDA",1,1,"C",$cor);
$pdf->cell(20,5,"",1,0,"C",$cor);
$cont = 0;
for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $cont++;
  
  if ($primeiro != $ed10_i_codigo) {
  	
    $pdf->cell($largura_colunas*($cont-1),5,pg_result($result1,$x-1,'ed10_i_codigo'),1,0,"C",$cor);
    $primeiro = $ed10_i_codigo;
    $cont = 1;
    
  }
}

$pdf->cell($largura_colunas*$cont,5,$primeiro,1,0,"C",$cor);
$pdf->cell(8,5,"","LRT",1,"C",$cor);
$pdf->cell(20,5,"Etapa","LRT",0,"R",$cor);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas,5,$ed11_c_abrev,1,0,"C",$cor);
  
}

$pdf->cell(8,5,"","LRB",1,"C",$cor);
$pdf->cell(14,4,"Idade","LB",0,"L",$cor);
$pdf->cell(6,4,"Sexo","RB",0,"R",$cor);
$pdf->line(10,44,24,53);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas/2,4,"M",1,0,"C",$cor);
  $pdf->cell($largura_colunas/2,4,"F",1,0,"C",$cor);
  
}

$pdf->cell(8,4,"Total",1,1,"C",$cor);
for ($idade = $comecaidade; $idade < $terminaidade; $idade++) {
	
  if ($modalidade == "1") {
  	
    if ($idade == 5) {
      $pdf->cell(20,4,"-6",1,0,"C",$cor);
    } else if ($idade == 15) {
      $pdf->cell(20,4,"+14",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,$idade,1,0,"C",$cor);
    }
    
  } else if ($modalidade == "3") {
  	
    if ($idade == 14) {
      $pdf->cell(20,4,"-15",1,0,"C",$cor);
    } else if ($idade == 22) {
      $pdf->cell(20,4,"22/35",1,0,"C",$cor);
    } else if ($idade == 23) {
      $pdf->cell(20,4,"36/50",1,0,"C",$cor);
    } else if ($idade == 24) {
      $pdf->cell(20,4,"+50",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,"$idade",1,0,"C",$cor);
    }    
  }
  
  $tlinha = 0;
  $vcont  = 0;
  for ($c1 = 0; $c1 < $linhas1; $c1++) {
  	
    db_fieldsmemory($result1,$c1);
    $masculino = 0;
    $feminino  = 0;
    for ($t1 = 0; $t1 < $linhas_trans; $t1++) {
    	
      db_fieldsmemory($result_trans,$t1);
      if ($modalidade == "1") {
      	
        if ($idade == 5) {
        	
          if ($idadealuno < 6 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 15) {
        	
          if ($idadealuno > 14 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        }
        
      } else if ($modalidade == "3") {
      	
        if ($idade == 14) {
        	
          if ($idadealuno < 15 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 22) {
        	
          if ($idadealuno > 21 && $idadealuno < 36 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 23) {
        	
          if ($idadealuno > 35 && $idadealuno < 51 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 24) {
        	
          if ($idadealuno > 50 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        }
      }
    }
    $tlinha       = $tlinha+($masculino+$feminino);
    @$vet[$vcont] = $vet[$vcont]+$masculino;
    $vcont        = $vcont+1;
    @$vet[$vcont] = $vet[$vcont]+$feminino;
    $vcont        = $vcont+1;
    if ($c1 != $linhas1-1) {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      
    } else {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      $pdf->cell(8,4,"$tlinha",1,1,"C",$cor);
      
    }
  }
  $pdf->setfont('arial','',7);
}

$pdf->cell(20,6,"Total",1,0,"C",$cor);
$total = 0;
for ($x = 0; $x < ($linhas1*2); $x++) {
	
  $pdf->cell($largura_colunas/2,6,"$vet[$x]",1,0,"C",$cor);
  $total=$total+$vet[$x];
  
}

$pdf->cell(8,6,"$total",1,1,"C",$cor);
$pdf->cell(190,4,"",0,1,"C",$cor);
unset($vet);

/*
 * TABELA 3
 */
$sql_trans    = " SELECT count(*) as qtdaluno, ";
$sql_trans   .= "       ed47_v_sexo, ";
$sql_trans   .= "       coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
$sql_trans   .= "       ed221_i_serie as seriealuno ";
$sql_trans   .= " FROM matricula ";
$sql_trans   .= "  inner join aluno on ed60_i_aluno=ed47_i_codigo ";
$sql_trans   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
$sql_trans   .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
$sql_trans   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
$sql_trans   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
$sql_trans   .= " WHERE ed60_d_datasaida <= '$datalimite' ";
$sql_trans   .= " AND ed52_i_codigo = $calendario ";
$sql_trans   .= " AND ed57_i_escola = $iEscola ";
$sql_trans   .= " AND ed11_i_ensino in ($nivelensino) ";
$sql_trans   .= " AND ed60_c_situacao = 'FALECIDO' ";
$sql_trans   .= " AND ed221_c_origem = 'S' ";
$sql_trans   .= " GROUP BY ed47_v_sexo,idadealuno,seriealuno ";
$sql_trans   .= " ORDER BY idadealuno,seriealuno"; 
$result_trans = pg_query($sql_trans);
$linhas_trans = pg_num_rows($result_trans);
$primeiro     = pg_result($result1,0,'ed10_i_codigo');
$pdf->cell($largura_colunas*$linhas1+28,4,"FALECIMENTO",1,1,"C",$cor);
$pdf->cell(20,5,"",1,0,"C",$cor);
$cont = 0;

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $cont++;
  if ($primeiro != $ed10_i_codigo) {
  	
    $pdf->cell($largura_colunas*($cont-1),5,pg_result($result1,$x-1,'ed10_i_codigo'),1,0,"C",$cor);
    $primeiro = $ed10_i_codigo;
    $cont = 1;
    
  }
}

$pdf->cell($largura_colunas*$cont,5,$primeiro,1,0,"C",$cor);
$pdf->cell(8,5,"","LRT",1,"C",$cor);
$pdf->cell(20,5,"Etapa","LRT",0,"R",$cor);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas,5,$ed11_c_abrev,1,0,"C",$cor);
  
}

$pdf->cell(8,5,"","LRB",1,"C",$cor);
$pdf->cell(14,4,"Idade","LB",0,"L",$cor);
$pdf->cell(6,4,"Sexo","RB",0,"R",$cor);
$pdf->line(10,44,24,53);

for ($x = 0; $x < $linhas1; $x++) {
	
  db_fieldsmemory($result1,$x);
  $pdf->cell($largura_colunas/2,4,"M",1,0,"C",$cor);
  $pdf->cell($largura_colunas/2,4,"F",1,0,"C",$cor);
  
}

$pdf->cell(8,4,"Total",1,1,"C",$cor);
for ($idade = $comecaidade; $idade < $terminaidade; $idade++) {
	
  if ($modalidade == "1") {
  	
    if ($idade == 5) {
      $pdf->cell(20,4,"-6",1,0,"C",$cor);
    } else if ($idade == 15) {
      $pdf->cell(20,4,"+14",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,$idade,1,0,"C",$cor);
    }
  } else if ($modalidade == "3") {
  	
    if ($idade == 14) {
      $pdf->cell(20,4,"-15",1,0,"C",$cor);
    } else if ($idade == 22) {
      $pdf->cell(20,4,"22/35",1,0,"C",$cor);
    } else if ($idade == 23) {
      $pdf->cell(20,4,"36/50",1,0,"C",$cor);
    } else if ($idade == 24) {
      $pdf->cell(20,4,"+50",1,0,"C",$cor);
    } else {
      $pdf->cell(20,4,"$idade",1,0,"C",$cor);
    }
  }
  $tlinha = 0;
  $vcont  = 0;
  for ($c1 = 0; $c1 < $linhas1; $c1++) {
  	
    db_fieldsmemory($result1,$c1);
    $masculino = 0;
    $feminino  = 0;
    for ($t1 = 0; $t1 < $linhas_trans; $t1++) {
    	
      db_fieldsmemory($result_trans,$t1);
      if ($modalidade == "1") {
      	
        if ($idade == 5) {
        	
          if ($idadealuno < 6 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 15) {
        	
          if ($idadealuno > 14 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        }
        
      } else if ($modalidade == "3") {
      	
        if ($idade == 14) {
        	
          if ($idadealuno < 15 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 22) {
        	
          if ($idadealuno > 21 && $idadealuno < 36 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else { 
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 23) {
        	
          if ($idadealuno > 35 && $idadealuno < 51 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else if ($idade == 24) {
        	
          if ($idadealuno > 50 && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
          
        } else {
        	
          if ($idadealuno == $idade && $ed11_i_codigo == $seriealuno) {
          	
            if ($ed47_v_sexo == "M") {
              $masculino += $qtdaluno;
            } else {
              $feminino += $qtdaluno;
            }
          }
        }
      }
    }
    $tlinha       = $tlinha+($masculino+$feminino);
    @$vet[$vcont] = $vet[$vcont]+$masculino;
    $vcont        = $vcont+1;
    @$vet[$vcont] = $vet[$vcont]+$feminino;
    $vcont        = $vcont+1;
    
    if ($c1 != $linhas1-1) {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      
    } else {
    	
      $pdf->cell($largura_colunas/2,4,$masculino==0?'':$masculino,1,0,"C",$cor);
      $pdf->cell($largura_colunas/2,4,$feminino==0?'':$feminino,1,0,"C",$cor);
      $pdf->cell(8,4,"$tlinha",1,1,"C",$cor);
      
    }
  }
  $pdf->setfont('arial','',7);
}

$pdf->cell(20,6,"Total",1,0,"C",$cor);
$total = 0;
for ($x = 0; $x < ($linhas1*2); $x++) {
	
 $pdf->cell($largura_colunas/2,6,"$vet[$x]",1,0,"C",$cor);
 $total = $total+$vet[$x];
 
}

$pdf->cell(8,6,"$total",1,1,"C",$cor);
$pdf->cell(180,8,"",0,1,"C",$cor);
$pdf->cell(90,4,$funcao." ".$z01_nome,0,0,"L",$cor);
$pdf->cell(90,4,"Data: ..........................................................",0,1,"L",$cor);
$pdf->cell(90,4,"Recebimento: ......................................................................................",
           0,0,"L",$cor);
$pdf->cell(90,4,"Data: ..........................................................",0,1,"L",$cor);

/*
 * LISTAGEM DE ALUNOS
 */
if ($imprimelista == "yes") {
/* 
 * TRANSFERÊNCIAS
 */
  $pdf->Addpage();
  $pdf->setfillcolor(223);
  $sql_trans    = " select ed47_i_codigo, ";
  $sql_trans   .= "       ed47_v_nome, ";
  $sql_trans   .= "       ed47_v_sexo, ";
  $sql_trans   .= "       ed47_d_nasc, ";
  $sql_trans   .= "       coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
  $sql_trans   .= "       ed11_c_descr, ";
  $sql_trans   .= "       ed60_c_situacao, ";
  $sql_trans   .= "       ed60_d_datamatricula,ed60_d_datasaida "; 
  $sql_trans   .= " FROM matricula ";
  $sql_trans   .= "  inner join aluno on ed60_i_aluno=ed47_i_codigo ";
  $sql_trans   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
  $sql_trans   .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
  $sql_trans   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
  $sql_trans   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
  $sql_trans   .= " WHERE (ed60_d_datasaida <= '$datalimite' and ed60_d_datasaida > '$datalimitemov') ";
  $sql_trans   .= " AND ed52_i_codigo = $calendario ";
  $sql_trans   .= " AND ed57_i_escola = $iEscola ";
  $sql_trans   .= " AND ed11_i_ensino in ($nivelensino) ";
  $sql_trans   .= " AND (ed60_c_situacao = 'TRANSFERIDO FORA' OR ed60_c_situacao = 'TRANSFERIDO REDE') ";
  $sql_trans   .= " AND ed221_c_origem = 'S' ";
  $sql_trans   .= " ORDER BY idadealuno,ed47_d_nasc,ed47_v_nome";
  $result_trans = pg_query($sql_trans);
  $linhas_trans = pg_num_rows($result_trans);
  $primeiro     = "";
  $contador     = 0;
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"TRANFERÊNCIAS",1,1,"L",1);
  $pdf->cell(190,4,"",0,1,"L",0);
  for ($f = 0; $f < $linhas_trans; $f++) {
  	
    db_fieldsmemory($result_trans,$f);
    if ($primeiro != $idadealuno) {
    	
      $primeiro = $idadealuno;
      if ($f > 0) {
      	
        $pdf->setfont('arial','',7);
        $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
        
      }
      
      $pdf->setfont('arial','b',10);
      $pdf->cell(190,4,"Idade: $idadealuno","B",1,"L",0);
      $pdf->setfont('arial','b',7);
      $pdf->cell(10,4,"Seq","B",0,"C",0);
      $pdf->cell(10,4,"Idade","B",0,"C",0);
      $pdf->cell(15,4,"Nascimento","B",0,"C",0);
      $pdf->cell(10,4,"Codigo","B",0,"C",0);
      $pdf->cell(60,4,"Nome","B",0,"L",0);
      $pdf->cell(30,4,"Situação","B",0,"L",0);
      $pdf->cell(10,4,"Sexo","B",0,"C",0);
      $pdf->cell(25,4,"Serie/Ano","B",0,"C",0);
      $pdf->cell(20,4,"Data Matrícula","B",1,"C",0);
      $contador = 0;
      
    }
    
    $contador++;
    $pdf->setfont('arial','',7);
    $pdf->cell(10,4,$contador,0,0,"C",0);
    $pdf->cell(10,4,$idadealuno,0,0,"C",0);
    $pdf->cell(15,4,trim($ed47_d_nasc)==""?"Nao Informado":db_formatar($ed47_d_nasc,'d'),0,0,"C",0);
    $pdf->cell(10,4,$ed47_i_codigo,0,0,"C",0);
    $pdf->cell(60,4,$ed47_v_nome,0,0,"L",0);
    $pdf->cell(30,4,$ed60_c_situacao,0,0,"L",0);
    $pdf->cell(10,4,$ed47_v_sexo,0,0,"C",0);
    $pdf->cell(25,4,$ed11_c_descr,0,0,"C",0);
    $pdf->cell(20,4,db_formatar($ed60_d_datamatricula,'d'),0,1,"C",0);
    
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
  $pdf->setfont('arial','b',9);
  $pdf->cell(190,4,"Total de alunos: $linhas_trans",0,1,"L",0);

 /*
  * EVASÃO / CANCELAMENTO
  */
  $pdf->setfillcolor(223);
  $sql_evadi    = " select ed47_i_codigo, ";
  $sql_evadi   .= "      ed47_v_nome,";
  $sql_evadi   .= "      ed47_v_sexo, ";
  $sql_evadi   .= "      ed47_d_nasc, ";
  $sql_evadi   .= "      coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
  $sql_evadi   .= "      ed11_c_descr, ";
  $sql_evadi   .= "      ed60_c_situacao, ";
  $sql_evadi   .= "      ed60_d_datamatricula ";
  $sql_evadi   .= " FROM matricula ";
  $sql_evadi   .= "  inner join aluno on ed60_i_aluno=ed47_i_codigo ";
  $sql_evadi   .= "  inner join turma on ed60_i_turma=ed57_i_codigo ";
  $sql_evadi   .= "  inner join calendario on ed57_i_calendario=ed52_i_codigo ";
  $sql_evadi   .= "  inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
  $sql_evadi   .= "  inner join serie on ed11_i_codigo=ed221_i_serie ";
  $sql_evadi   .= " WHERE (ed60_d_datasaida <= '$datalimite' and ed60_d_datasaida > '$datalimitemov') ";
  $sql_evadi   .= " AND ed52_i_codigo = $calendario ";
  $sql_evadi   .= " AND ed57_i_escola = $iEscola ";
  $sql_evadi   .= " AND ed11_i_ensino in ($nivelensino) ";
  $sql_evadi   .= " AND (ed60_c_situacao = 'EVADIDO' OR ed60_c_situacao = 'CANCELADO') ";
  $sql_evadi   .= " AND ed221_c_origem = 'S' ";
  $sql_evadi   .= " ORDER BY idadealuno,ed47_d_nasc,ed47_v_nome";
  $result_evadi = pg_query($sql_evadi);
  $linhas_evadi = pg_num_rows($result_evadi);
  $primeiro     = "";
  $contador     = 0;
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"",0,1,"L",0);
  $pdf->cell(190,4,"EVASÃO / CANCELAMENTO",1,1,"L",1);
  $pdf->cell(190,4,"",0,1,"L",0);
  for ($f = 0; $f < $linhas_evadi; $f++) {
  	
    db_fieldsmemory($result_evadi,$f);
    if ($primeiro != $idadealuno) {
    	
      $primeiro = $idadealuno;
      if ($f > 0) {
      	
        $pdf->setfont('arial','',7);
        $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
        
      }
      
      $pdf->setfont('arial','b',10);
      $pdf->cell(190,4,"Idade: $idadealuno","B",1,"L",0);
      $pdf->setfont('arial','b',7);
      $pdf->cell(10,4,"Seq","B",0,"C",0);
      $pdf->cell(10,4,"Idade","B",0,"C",0);
      $pdf->cell(15,4,"Nascimento","B",0,"C",0);
      $pdf->cell(10,4,"Codigo","B",0,"C",0);
      $pdf->cell(60,4,"Nome","B",0,"L",0);
      $pdf->cell(30,4,"Situação","B",0,"L",0);
      $pdf->cell(10,4,"Sexo","B",0,"C",0);
      $pdf->cell(25,4,"Serie/Ano","B",0,"C",0);
      $pdf->cell(20,4,"Data Matrícula","B",1,"C",0);
      $contador = 0;
      
    }
    
    $contador++;
    $pdf->setfont('arial','',7);
    $pdf->cell(10,4,$contador,0,0,"C",0);
    $pdf->cell(10,4,$idadealuno,0,0,"C",0);
    $pdf->cell(15,4,trim($ed47_d_nasc)==""?"Nao Informado":db_formatar($ed47_d_nasc,'d'),0,0,"C",0);
    $pdf->cell(10,4,$ed47_i_codigo,0,0,"C",0);
    $pdf->cell(60,4,$ed47_v_nome,0,0,"L",0);
    $pdf->cell(30,4,$ed60_c_situacao,0,0,"L",0);
    $pdf->cell(10,4,$ed47_v_sexo,0,0,"C",0);
    $pdf->cell(25,4,$ed11_c_descr,0,0,"C",0);
    $pdf->cell(20,4,db_formatar($ed60_d_datamatricula,'d'),0,1,"C",0);
    
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
  $pdf->setfont('arial','b',9);
  $pdf->cell(190,4,"Total de alunos: $linhas_evadi",0,1,"L",0);

 /* 
  * FALECIMENTO
  */
  $pdf->setfillcolor(223);
  $sql_falec    = " select ed47_i_codigo, ";
  $sql_falec   .= "      ed47_v_nome, ";
  $sql_falec   .= "      ed47_v_sexo, ";
  $sql_falec   .= "      ed47_d_nasc, ";
  $sql_falec   .= "      coalesce(fc_idade(ed47_d_nasc,'$databasecalc'::date),0) as idadealuno, ";
  $sql_falec   .= "      ed11_c_descr, ";
  $sql_falec   .= "      ed60_c_situacao, ";
  $sql_falec   .= "      ed60_d_datamatricula ";
  $sql_falec   .= "FROM matricula ";
  $sql_falec   .= " inner join aluno on ed60_i_aluno=ed47_i_codigo ";
  $sql_falec   .= " inner join turma on ed60_i_turma=ed57_i_codigo ";
  $sql_falec   .= " inner join calendario on ed57_i_calendario=ed52_i_codigo ";
  $sql_falec   .= " inner join matriculaserie on ed221_i_matricula=ed60_i_codigo ";
  $sql_falec   .= " inner join serie on ed11_i_codigo=ed221_i_serie ";
  $sql_falec   .= "WHERE (ed60_d_datasaida <= '$datalimite' and ed60_d_datasaida > '$datalimitemov') ";
  $sql_falec   .= "AND ed52_i_codigo = $calendario ";
  $sql_falec   .= "AND ed57_i_escola = $iEscola ";
  $sql_falec   .= "AND ed11_i_ensino in ($nivelensino) ";
  $sql_falec   .= "AND ed60_c_situacao = 'FALECIDO' ";
  $sql_falec   .= "AND ed221_c_origem = 'S' ";
  $sql_falec   .= "ORDER BY idadealuno,ed47_d_nasc,ed47_v_nome ";
  $result_falec = pg_query($sql_falec);
  $linhas_falec = pg_num_rows($result_falec);
  $primeiro     = "";
  $contador     = 0;
  $pdf->setfont('arial','b',7);
  $pdf->cell(190,4,"",0,1,"L",0);
  $pdf->cell(190,4,"FALECIMENTO",1,1,"L",1);
  $pdf->cell(190,4,"",0,1,"L",0);
  
  for ($f = 0; $f < $linhas_falec; $f++) {
  	
    db_fieldsmemory($result_falec,$f);
    if ($primeiro != $idadealuno) {
    	
      $primeiro = $idadealuno;
      if ($f > 0) {
      	
        $pdf->setfont('arial','',7);
        $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
        
      }
      $pdf->setfont('arial','b',10);
      $pdf->cell(190,4,"Idade: $idadealuno","B",1,"L",0);
      $pdf->setfont('arial','b',7);
      $pdf->cell(10,4,"Seq","B",0,"C",0);
      $pdf->cell(10,4,"Idade","B",0,"C",0);
      $pdf->cell(15,4,"Nascimento","B",0,"C",0);
      $pdf->cell(10,4,"Codigo","B",0,"C",0);
      $pdf->cell(60,4,"Nome","B",0,"L",0);
      $pdf->cell(30,4,"Situação","B",0,"L",0);
      $pdf->cell(10,4,"Sexo","B",0,"C",0);
      $pdf->cell(25,4,"Serie/Ano","B",0,"C",0);
      $pdf->cell(20,4,"Data Matrícula","B",1,"C",0);
      $contador = 0;
      
    }    
    $contador++;
    $pdf->setfont('arial','',7);
    $pdf->cell(10,4,$contador,0,0,"C",0);
    $pdf->cell(10,4,$idadealuno,0,0,"C",0);
    $pdf->cell(15,4,trim($ed47_d_nasc)==""?"Nao Informado":db_formatar($ed47_d_nasc,'d'),0,0,"C",0);
    $pdf->cell(10,4,$ed47_i_codigo,0,0,"C",0);
    $pdf->cell(60,4,$ed47_v_nome,0,0,"L",0);
    $pdf->cell(30,4,$ed60_c_situacao,0,0,"L",0);
    $pdf->cell(10,4,$ed47_v_sexo,0,0,"C",0);
    $pdf->cell(25,4,$ed11_c_descr,0,0,"C",0);
    $pdf->cell(20,4,db_formatar($ed60_d_datamatricula,'d'),0,1,"C",0);
    
  }
  
  $pdf->setfont('arial','',7);
  $pdf->cell(190,4,"Subtotal de alunos: $contador",0,1,"R",0);
  $pdf->setfont('arial','b',9);
  $pdf->cell(190,4,"Total de alunos: $linhas_falec",0,1,"L",0);
}
$pdf->Output();
?>