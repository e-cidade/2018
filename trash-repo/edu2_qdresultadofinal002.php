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

require("libs/db_stdlibwebseller.php");
include("fpdf151/scpdf.php");
include("classes/db_turma_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciaperiodo_classe.php");
include("classes/db_regenteconselho_classe.php");
include("classes/db_aprovconselho_classe.php");
include("classes/db_edu_relatmodel_classe.php");
include("classes/db_telefoneescola_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_cursoato_classe.php");
include("classes/db_aluno_classe.php");
require_once("libs/db_utils.php");
require_once("model/educacao/DBEducacaoTermo.model.php");

$resultedu         = eduparametros(db_getsession("DB_coddepto"));
$clturma           = new cl_turma;
$clmatricula       = new cl_matricula;
$clregencia        = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$clregenteconselho = new cl_regenteconselho;
$claprovconselho   = new cl_aprovconselho;
$clEduRelatmodel   = new cl_edu_relatmodel();
$clTelefoneEscola  = new cl_telefoneescola();
$clEscola          = new cl_escola();
$clcursoato        = new cl_cursoato;
$claluno           = new cl_aluno;
$escola            = db_getsession("DB_coddepto");
$resultedu         = eduparametros(db_getsession("DB_coddepto"));


$sCampos  = "distinct                                   \n";
$sCampos .= "ed52_i_ano, ed57_c_descr, ed29_i_codigo,   \n";
$sCampos .= "ed29_c_descr, ed52_c_descr, ed11_c_descr,  \n";
$sCampos .= "ed15_c_nome, ed57_i_codigo, ed223_i_serie, \n";
$sCampos .= "ed52_d_resultfinal                         \n";

$result            = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                         $sCampos,
                                                                         "ed57_c_descr",
                                                                         " ed220_i_codigo in ($turmas)"
                                                                        )
                                         );
                                         
                                        
$sSqlDadosTelEscola = $clTelefoneEscola->sql_query("",
                                                   "ed26_i_ddd,ed26_i_numero,ed26_i_ramal",
                                                   "",
                                                   "ed26_i_escola = $escola LIMIT 1"
                                                  );
$rsDadosTelEscola   = db_query($sSqlDadosTelEscola);
                                        
if ($clTelefoneEscola->numrows > 0) {
	
  $iDdd               = $oDadosInstit->url;
  $iNumero            = $oDadosInstit->nome;
  $iRamal             = $oDadosInstit->logo; 
  $oDadosTelEscola    = db_utils::fieldsMemory($rsDadosTelEscola,0);
  $sTelEscola         = "- Fone: ($iDdd) $iNumero ".($iRamal!=""?" Ramal: $iRamal":"");
  
} else {
  $sTelEscola = "";
}

$sCampos         = " ed18_c_nome as nome_escola,j14_nome as rua_escola,ed18_c_cep as cep_escola, ";
$sCampos        .= " ed18_i_numero as num_escola,ed261_c_nome as mun_escola,ed260_c_sigla as uf_escola ";
$sSqlDadosEscola = $clEscola->sql_query("",$sCampos,"","ed18_i_codigo = $escola");
$rsDadosEscola   = db_query($sSqlDadosEscola);
$oDadosEscola    = db_utils::fieldsMemory($rsDadosEscola,0);
$nome_escola     = $oDadosEscola->nome_escola;
$rua_escola      = $oDadosEscola->rua_escola;
$cep_escola      = $oDadosEscola->cep_escola;
$num_escola      = $oDadosEscola->num_escola;
$mun_escola      = $oDadosEscola->mun_escola;
$uf_escola       = $oDadosEscola->uf_escola;

$sCampo              = "ed217_t_cabecalho,ed217_t_rodape,ed217_t_obs";
$sSqlDadosRelatModel = $clEduRelatmodel->sql_query("",$sCampo,"","ed217_i_codigo = $tipovar");
$rsDadosRelatModel   = $clEduRelatmodel->sql_record($sSqlDadosRelatModel);
if ($clEduRelatmodel->numrows > 0) {
	
  $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosRelatModel,0);
  $sCabecalho        = $oDadosRelatModel->ed217_t_cabecalho;
  
}

if ($clturma->numrows == 0) {?>

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
    
  $arr_diretor   = explode("|",$diretor);
  $nomediretor   = $arr_diretor[1];
  $funcaodiretor = $arr_diretor[0].(trim($arr_diretor[2]) != ""?" ($arr_diretor[2])":"");
  
} else {
    
  $nomediretor   = "";
  $funcaodiretor = "";
  
}

if ($secretario != "") {
    
  $arr_secretario   = explode("|",$secretario);
  $nomesecretario   = $arr_secretario[1];
  $funcaosecretario = $arr_secretario[0].(trim($arr_secretario[2]) != ""?" ($arr_secretario[2])":"");
  
} else {
    
  $nomesecretario   = "";
  $funcaosecretario = "";
  
}

$fpdf   = new FPDF();
$fpdf->Open();
$fpdf->AliasNbPages();
$linhas = $clturma->numrows;
for ($x = 0; $x < $linhas; $x++) {
	
  db_fieldsmemory($result,$x); 
  $obs_cons    = "";
  $sCampos     = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao ";
  $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("",
                                                                          $sCampos,
                                                                          "ed59_i_ordenacao",
                                                                          "ed59_i_turma = $ed57_i_codigo 
                                                                           AND ed59_i_serie = $ed223_i_serie"
                                                                         )
                                             );
  $sWhere      =  "ed78_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo "; 
  $sWhere     .=  "          AND ed59_i_serie = $ed223_i_serie) ";                                           
  $result1     = $clregenciaperiodo->sql_record($clregenciaperiodo->sql_query("",
                                                                          "sum(ed78_i_aulasdadas) as aulas",
                                                                          "",
                                                                          $sWhere
                                                                         )
                                           );
  db_fieldsmemory($result1,0);
  $result5 = $clregenteconselho->sql_record($clregenteconselho->sql_query("",
                                                                          "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente",
                                                                          "",
                                                                          " ed235_i_turma = $ed57_i_codigo"
                                                                         )
                                           );
  if ($clregenteconselho->numrows > 0) {
    db_fieldsmemory($result5,0);
  } else {
    $regente = "";
  }
  $fpdf->setfillcolor(223);
  $dia = substr($ed52_d_resultfinal,8,2);
  $mes = db_mes(substr($ed52_d_resultfinal,5,2));
  $ano = substr($ed52_d_resultfinal,0,4);
  $fpdf->addpage('L');
  $sSql = "select nomeinst,trim(ender)||','||trim(numero::varchar) as ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit");
  $dados = pg_exec($conn,$sSql);
    $url = @pg_result($dados,0,"url");
    $fpdf->SetXY(1,1);
  if ($brasao == "b1") {
    $fpdf->Image('imagens/files/'.pg_result($dados,0,"logo"),7,3,20);
  } else {
    $fpdf->Image('',7,3,20);  
  } 
  $fpdf->setfont('arial','b',8);
  $fpdf->setXY(20,5);
  $fpdf->multicell(120,4,$sCabecalho,0,"C",0,0);
  $fpdf->setXY(175,5);
  $result_mant     = pg_exec($conn,"select nomeinst from db_config where codigo = ".db_getsession("DB_instit"));
  $mantenedora     = pg_result($result_mant,0,'nomeinst');
  $sCampos         = "ed05_c_finalidade,ed05_c_numero,ed05_d_vigora,ed05_d_publicado";
  $sWhere          = " ed29_i_codigo in ($ed29_i_codigo) AND ed18_i_codigo = $escola";
  $result_cursoato = $clcursoato->sql_record($clcursoato->sql_query("",
                                                                    $sCampos,
                                                                    "",
                                                                    $sWhere
                                                                   )
                                            );
  if ($clcursoato->numrows > 0) {
  	
    $ato_escola = "";
    $sep_escola = "";
    for ($x=0; $x < $clcursoato->numrows; $x++) {
    	
      db_fieldsmemory($result_cursoato,$x);
      $ato_escola .= $sep_escola."$ed05_c_finalidade N° $ed05_c_numero ";
      $ato_escola .= " Data: ".db_formatar($ed05_d_vigora,'d')." D.O.: ".db_formatar($ed05_d_publicado,'d');
      $sep_escola = "\n";
      
    }
  } else {
    $ato_escola = "";
  }
  $cabecalho_escola  = "$nome_escola\nMantenedora: $mantenedora\nEndereço: $rua_escola , ";
  $cabecalho_escola .= " $num_escola\nCEP: $cep_escola - $mun_escola / $uf_escola $sTelEscola";
  $fpdf->multicell(110,3,$cabecalho_escola,0,"L",0,0);
  $fpdf->setX(175);
  $fpdf->multicell(110,2,"","","L",0,0);
  $fpdf->setX(175);
  $fpdf->setfont('arial','b',6);
  $fpdf->multicell(110,2,$ato_escola,"","L",0,0);
  $fpdf->setY(26);
  $fpdf->setfont('arial', '', 7);
  $fpdf->cell(19, 4, "Tipo de Ensino :", 0, 0, "L", 0);
  $fpdf->cell(188, 4, $ed10_c_descr, 0, 0, "L", 0);
  $fpdf->cell(10, 4, "Curso :", 0, 0, "L", 0);
  $fpdf->cell(15, 4, $ed29_c_descr, 0, 1, "L", 0);
  $fpdf->cell(9, 4, "Etapa :", 0, 0, "L", 0);
  $fpdf->cell(100, 4, $ed11_c_descr, 0, 0, "L", 0);
  $fpdf->cell(7, 4, "Ano :", 0, 0, "L", 0);
  $fpdf->cell(91, 4, $ed52_i_ano, 0, 0, "L", 0);
  $fpdf->cell(7, 4, "C.H :", 0, 0, "L", 0);
  $fpdf->cell(15, 4, $aulas, 0, 1, "L", 0);  
  $fpdf->cell(10, 4, "Turma :", 0, 0, "L", 0);
  $fpdf->cell(99, 4, $ed57_c_descr, 0, 0, "L", 0);
  $fpdf->cell(17, 4, "Dias Letivos :", 0, 0, "L", 0);
  $fpdf->cell(81, 4, $ed52_i_diasletivos, 0,0, "L", 0);
  $fpdf->cell(10, 4, "Turno :", 0, 0, "L", 0);
  $fpdf->cell(20, 4, $ed15_c_nome, 0, 1, "L", 0);
  $fpdf->setX(90);
  $fpdf->setY(40);
  $inicio = $fpdf->getY();
  $fpdf->cell(5,4,"","LRT",0,"C",0);
  $fpdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
  $sCampos = "ed59_i_codigo, ed232_c_abrev, ed232_c_descr, ed59_i_ordenacao";
  $sWhere  = " ed59_i_turma = $ed57_i_codigo AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
  $sql2    = $clregencia->sql_query("",
                                    $sCampos,
                                    "ed59_i_ordenacao",
                                    $sWhere
                               );
  $result2    = $clregencia->sql_record($sql2);
  $cont       = 0;
  $reg_pagina = 0;
  $sep        = "";
  for ($y = 0; $y < $clregencia->numrows; $y++) {
  	
    db_fieldsmemory($result2,$y);
    if ($y < 9) {
    	
      $fpdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
      $cont++;
      $reg_pagina .= $sep.$ed59_i_codigo;
      $sep         = ",";       
    }
  }
  for ($y = $cont; $y < 9; $y++) {
    $fpdf->cell(22,4,"","LRT",0,"C",0);
  }
  $fpdf->cell(10,4,"",1,1,"C",0);
  $fpdf->cell(5,4,"N°",1,0,"C",0);
  $fpdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
  $cont2 = 0;
  for ($y=0; $y < $clregencia->numrows; $y++) {
  	
    if ($y < 9) {
    	
      $fpdf->cell(12,4,"Aprov",1,0,"C",0);
      $fpdf->cell(10,4,"% Freq",1,0,"C",0);
      $cont2++;
      
    }
  }
  for ($y = $cont2; $y < 9; $y++) {
  	
    $fpdf->cell(12,4,"",1,0,"C",0);
    $fpdf->cell(10,4,"",1,0,"C",0);
    
  }
  $fpdf->cell(10,4,"RF",1,1,"C",0);
  $sCampo  = "ed60_i_codigo, ed60_c_parecer, ed60_c_situacao, ed60_i_aluno, ed60_i_numaluno, ed47_v_nome";
  $sql4    = $clmatricula->sql_query("",
                                     $sCampo,
                                     "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",
                                     " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed223_i_serie"
                                    );
                                    
  $result4 = $clmatricula->sql_record($sql4);
  $cor1    = 0;
  $cor2    = 0;
  $cor     = "";
  $cont4   = 0;
  if ($claprovconselho->numrows == 0) {
    $limite = 30;
  } else {
    $limite = 27;
  }
  $cont_geral = 0;
  for ($z = 0; $z < $clmatricula->numrows; $z++) {
  	
    db_fieldsmemory($result4,$z);
    
    if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
      continue;
    }
    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }
    $fpdf->cell(5,4,$ed60_i_numaluno,1,0,"C",$cor);
    $fpdf->cell(65,4,$ed47_v_nome,1,0,"L",$cor);
    $sCampos  = "ed74_c_valoraprov,ed74_i_percfreq,ed81_c_todoperiodo,ed37_c_tipo,ed59_c_freqglob,ed89_i_disciplina,";
    $sCampos .= "ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev, ed74_c_resultadofreq, ed11_i_ensino";
    $sql5     = " SELECT $sCampos";    
    $sql5    .= "      FROM diariofinal ";
    $sql5    .= "       inner join diario on ed95_i_codigo = ed74_i_diario ";
    $sql5    .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sql5   .= "       inner join serie on ed11_i_codigo = ed59_i_serie ";
    $sql5    .= "       inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
    $sql5    .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
    $sql5    .= "       inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma ";
    $sql5    .= "       inner join base  on  base.ed31_i_codigo = turma.ed57_i_base ";
    $sql5    .= "       left join basediscglob  on  basediscglob.ed89_i_codigo = base.ed31_i_codigo ";
    $sql5    .= "       left join amparo on ed81_i_diario = ed95_i_codigo ";
    $sql5    .= "       left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
    $sql5    .= "       left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
    $sql5    .= "       left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
    $sql5    .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sql5    .= "      AND ed95_i_regencia in ($reg_pagina) ";
	$sql5    .= "  AND ed59_c_condicao = 'OB' ";
    $sql5    .= "      ORDER BY ed59_i_ordenacao ";
    $result5  = pg_query($sql5);
    $linhas5  = pg_num_rows($result5);
    $cont3    = 0;
    if ($linhas5 > 0) {
    	
      for ($v = 0; $v < $linhas5; $v++) {
      	
        db_fieldsmemory($result5,$v);
        if ($ed60_c_parecer == "S") {
          $ed37_c_tipo = "PARECER";
        }
        if (trim($ed60_c_situacao) != "MATRICULADO") {
        	
          $aproveitamento = substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5);
          $frequencia     = "";
          
        } else {
        	
         if (trim($ed81_c_todoperiodo) == "S") {
         	
           if ($ed81_i_justificativa != "") {
             $aproveitamento = "AMP.";
           } else {
             $aproveitamento = $ed250_c_abrev;
           }
           $frequencia = "";
         } else {
         	
           if (trim($ed59_c_freqglob) == "F") {
           	
             $aproveitamento = "-";
             if ($resultedu == 'S') { 
               $frequencia = number_format($ed74_i_percfreq,2,".",".");
             } else {
               $frequencia = number_format($ed74_i_percfreq,0);
             }
             
           } else if(trim($ed59_c_freqglob) == "A") {
           	
             if (trim($ed37_c_tipo) == "NOTA") {
             	
               if ($resultedu == 'S') {
                 $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
               } else {
                 $aproveitamento = number_format($ed74_c_valoraprov,0);
               }
               
             } else if (trim($ed37_c_tipo) == "PARECER") {
               $aproveitamento = "Parecer";
             } else {
               $aproveitamento = $ed74_c_valoraprov;
             }
             
             $sql_f    = " SELECT ed74_i_percfreq ";
             $sql_f   .= "   FROM diariofinal ";
             $sql_f   .= "        inner join diario on ed95_i_codigo = ed74_i_diario ";
             $sql_f   .= "        inner join regencia on ed59_i_codigo = ed95_i_regencia ";
             $sql_f   .= "        inner join turma on ed57_i_codigo = ed59_i_turma ";
             $sql_f   .= "   WHERE ed57_i_codigo = $ed57_i_codigo ";
             $sql_f   .= "         AND ed59_c_freqglob = 'F' ";
             $sql_f   .= "         AND ed95_i_aluno = $ed60_i_aluno ";
             $sql_f   .= "         AND ed95_i_regencia = $ed59_i_codigo ";
             $result_f = pg_query($sql_f);
             $linhas_f = pg_num_rows($result_f);
             if ($resultedu == 'S') {
               $frequencia = number_format(pg_result($result_f,0,'ed74_i_percfreq'),2,".",".");
             } else {
               $frequencia = number_format(pg_result($result_f,0,'ed74_i_percfreq'),0);
             }
           } else {
           	
             if (trim($ed37_c_tipo) == "NOTA") {
             	
               if ($resultedu == 'S') {
                 $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
               } else {
                 $aproveitamento = number_format($ed74_c_valoraprov,0);
               }
               
             } else if (trim($ed37_c_tipo) == "PARECER") {
               $aproveitamento = "Parecer";
             } else {
               $aproveitamento = $ed74_c_valoraprov;
             }
             
             if ($resultedu == 'S') {
               $frequencia = number_format($ed74_i_percfreq,2,".",".");
             } else {
               $frequencia = number_format($ed74_i_percfreq,0);
             }
           }
         }
        }
        $fpdf->setfont('arial','',9);
        $fpdf->cell(12,4,$aproveitamento,1,0,"C",$cor);
        $fpdf->cell(10,4,$frequencia,1,0,"C",$cor);
        $fpdf->setfont('arial','b',7);
        $cont3++;
      }
    } else {
    	
      $fpdf->setfont('arial','b',7);
      $fpdf->cell(12,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),1,0,"C",$cor);
      $fpdf->cell(10,4,"",1,0,"C",$cor);
      $fpdf->setfont('arial','',9);
      $cont3++;
      
    }
    for ($y = $cont3; $y < 9; $y++) {
    	
      $fpdf->cell(12,4,"",1,0,"C",$cor);
      $fpdf->cell(10,4,"",1,0,"C",$cor);
      
    }
    $sql6    = " SELECT ed95_i_codigo ";
    $sql6   .= "      FROM diario ";
    $sql6   .= "          inner join aluno on ed47_i_codigo = ed95_i_aluno ";
    $sql6   .= "          inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
    $sql6   .= "          inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sql6   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sql6   .= "      AND ed95_i_regencia in (select ed59_i_codigo from regencia ";
    $sql6   .= "                                                         where ed59_i_turma = $ed57_i_codigo ";
    $sql6   .= "                                                               AND ed59_i_serie = $ed223_i_serie) ";
    $sql6   .= "      AND ed59_c_condicao = 'OB' AND ed74_c_resultadofinal != 'A' ";
    $result6 = pg_query($sql6);
    $linhas6 = pg_num_rows($result6);
    if (trim($ed60_c_situacao) != "MATRICULADO" || $linhas5 == 0) {
      $rf = "";
    } else {
    	
     if ($linhas6 == 0) {
       $rf = "APR";
     } else {
       $rf = "REP";
     }
     if ($ed74_c_valoraprov == "") {
       
       if (trim($ed59_c_freqglob) == "F") {
          
         $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed74_c_resultadofreq);
         if (isset($aDadosTermo[0])) {
           $rf = $aDadosTermo[0]->sAbreviatura;
         }
       } else {
         $rf = "";
       }
     }
    }
    $fpdf->cell(10,4,$rf,1,1,"C",$cor);
    if ($cont4 == $limite && ($cont_geral+1) < $clmatricula->numrows) {    
      $fpdf->setfont('arial','b',6);
      $alt_conv = $fpdf->getY();
      $cont5    = 0;
      $quebra   = "0";
      $sWhere   = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
      $sql2     = $clregencia->sql_query("",
                                         "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                         "ed59_i_ordenacao",
                                         $sWhere
                                        );
      $result2  = $clregencia->sql_record($sql2);
      for ($y = 0; $y < $clregencia->numrows; $y++) {
      	
        db_fieldsmemory($result2,$y);
        $fpdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),1,0,"L",0);
        
      }
      $fpdf->setY($alt_conv+10);
      $fpdf->cell(20,4,"",0,0,"L",0);
      $fpdf->cell(100, 5, "______________________________________________________________________", 0, 0, "C", 0);
      $fpdf->cell(160, 5, "______________________________________________________________________", 0, 1, "C", 0);
      $fpdf->cell(120, 5, $nomesecretario." - ".$funcaosecretario, 0, 0, "C", 0);
      $fpdf->cell(180, 5, $nomediretor." - ".$funcaodiretor, 0, 1, "C", 0);
      $fpdf->setY($alt_conv);
      $fpdf->setX(10);
      $fpdf->cell(278,4,"",1,1,"L",0);
      if ($claprovconselho->numrows > 0) {
      	
        $fpdf->cell(278,4,"Observações",1,1,"C",0);
        $sepobs = "";
        
        for ($g=0;$g<$claprovconselho->numrows;$g++) {
        	
          db_fieldsmemory($result_cons,$g);
          $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs";
          $sepobs = "\n";
          
        }
        $fpdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
      }
      $fpdf->addpage('L');
      $fpdf->setfont('arial','b',7);
      $inicio = $fpdf->getY();
      $fpdf->cell(5,4,"","LRT",0,"C",0);
      $fpdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
      $sWhere     = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
      $sql2       = $clregencia->sql_query("",
                                           "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                           "ed59_i_ordenacao",
                                           $sWhere
                                          );
      $result2    = $clregencia->sql_record($sql2);
      $cont       = 0;
      $reg_pagina = 0;
      $sep        = "";
      for ($y = 0; $y < $clregencia->numrows; $y++) {
      	
        db_fieldsmemory($result2,$y);
        if ($y < 9) {
        	
          $fpdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
          $cont++;
          $reg_pagina .= $sep.$ed59_i_codigo;
          $sep         = ",";
          
        }
      }
      for ($y = $cont; $y < 9; $y++) {
        $fpdf->cell(22,4,"","LRT",0,"C",0);
      }
      
      $fpdf->cell(10,4,"RF",1,1,"C",0);
      $fpdf->cell(5,4,"N°",1,0,"C",0);
      $fpdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
      $cont2 = 0;
      for ($y = 0; $y < $clregencia->numrows; $y++) {
      	
        if ($y < 9) {
        	
          $fpdf->cell(12,4,"Aprov",1,0,"C",0);
          $fpdf->cell(10,4,"% Freq",1,0,"C",0);
          $cont2++;
          
        }
      }
      for ($y = $cont2; $y < 9; $y++) {
      	
        $fpdf->cell(12,4,"",1,0,"C",0);
        $fpdf->cell(10,4,"",1,0,"C",0);
        
      }
      $fpdf->cell(10,4,"",1,1,"C",0);
      $cont4 = -1;
    }
    $cont4++;
    $cont_geral++;
  }
  for ($z = $cont4; $z < $limite; $z++) {
  	
    $fpdf->cell(5,4,"",1,0,"C",0);
    $fpdf->cell(65,4,"",1,0,"L",0);
    for($t = 0; $t < 9; $t++){
    	
      $fpdf->cell(12,4,"",1,0,"C",0);
      $fpdf->cell(10,4,"",1,0,"C",0);
      
    }
    $fpdf->cell(10,4,"",1,1,"C",0);
  }
  
  $alt_conv = $fpdf->getY();
  $cont5    = 0;
  $quebra   = "0";
  $sWhere   = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
  $sql2     = $clregencia->sql_query("", 
                                     "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                     "ed59_i_ordenacao",
                                     $sWhere
                                    );
  $result2 = $clregencia->sql_record($sql2);
  for ($y = 0; $y < $clregencia->numrows; $y++) {
  	
    db_fieldsmemory($result2,$y);
    $fpdf->cell(30.9,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),1,0,"L",0);
    
  }
  $fpdf->setY($alt_conv+10);
  $fpdf->cell(20,4,"",0,0,"L",0);
  $fpdf->cell(100, 5, "______________________________________________________________________", 0, 0, "C", 0);
  $fpdf->cell(160, 5, "______________________________________________________________________", 0, 1, "C", 0);
  $fpdf->cell(120, 5, $nomesecretario." - ".$funcaosecretario, 0, 0, "C", 0);
  $fpdf->cell(180, 5, $nomediretor." - ".$funcaodiretor, 0, 1, "C", 0);
  $fpdf->setY($alt_conv);
  $fpdf->setX(10);
  $fpdf->cell(278,4,"",1,1,"L",0);
   
  if ($claprovconselho->numrows > 0) {
  	
    $fpdf->cell(278,4,"Observações",1,1,"C",0);
    $sepobs = "";
    for ($g = 0; $g < $claprovconselho->numrows; $g++) {
    	
      db_fieldsmemory($result_cons,$g);
      $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs";
      $sepobs    = "\n";
      
    }
    $fpdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
  }

  $sWhere  = " ed59_i_turma = $ed57_i_codigo AND ed59_i_codigo not in ($reg_pagina) ";
  $sWhere .= " AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie ";
  $sql2    = $clregencia->sql_query("",
                                    "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                    "ed59_i_ordenacao",
                                    $sWhere
                                   );
  $result2 = $clregencia->sql_record($sql2);

  if ($clregencia->numrows > 0) {
  	
    $fpdf->addpage('L');
    $fpdf->setfont('arial','b',7);
    $inicio = $fpdf->getY();
    $fpdf->cell(5,4,"","LRT",0,"C",0);
    $fpdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
    $cont       = 0;
    $reg_pagina = 0;
    $sep        = "";
    for ($y = 0; $y < $clregencia->numrows; $y++) {
    	
      db_fieldsmemory($result2,$y);
      if ($y < 9) {
      	
        $fpdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
        $cont++;
        $reg_pagina .= $sep.$ed59_i_codigo;
        $sep         = ",";
        
      }
    }
    for($y = $cont; $y < 9; $y++) {
      $fpdf->cell(22,4,"","LRT",0,"C",0);
    }
    $fpdf->cell(10,4,"RF",1,1,"C",0);
    $fpdf->cell(5,4,"N°",1,0,"C",0);
    $fpdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
    $cont2 = 0;
    for ($y = 0; $y < $clregencia->numrows; $y++) {
    	
      if ($y < 9) {
      	
        $fpdf->cell(12,4,"Aprov",1,0,"C",0);
        $fpdf->cell(10,4,"% Freq",1,0,"C",0);
        $cont2++;
        
      }
    }
    for ($y = $cont2; $y < 9; $y++) {
    	
      $fpdf->cell(12,4,"",1,0,"C",0);
      $fpdf->cell(10,4,"",1,0,"C",0);
      
    }
    $fpdf->cell(10,4,"",1,1,"C",0);
    $sCampo  = "ed60_i_codigo,ed60_c_parecer,ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed47_v_nome";
    $sql4    = $clmatricula->sql_query("",
                                       $sCampo,
                                       "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",
                                       " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed223_i_serie"
                                      );
    $result4 = $clmatricula->sql_record($sql4);
    $cor1    = 0;
    $cor2    = 0;
    $cor     = "";
    $cont4   = 0;
    if ($claprovconselho->numrows == 0) {
      $limite = 32;
    } else {
      $limite = 30;
    }
    $cont_geral = 0;
    for ($z = 0; $z < $clmatricula->numrows; $z++) {
    	
      db_fieldsmemory($result4,$z);
      
      if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
        continue;
      }
      if ($cor == $cor1) {
        $cor = $cor2;
      } else {
        $cor = $cor1;
      }
      $fpdf->cell(5,4,$ed60_i_numaluno,1,0,"C",$cor);
      $fpdf->cell(65,4,$ed47_v_nome,1,0,"L",$cor);
      $sql5    = " SELECT ed74_c_valoraprov,ed74_i_percfreq,ed81_c_todoperiodo,ed37_c_tipo,ed59_c_freqglob, ";
      $sql5   .= "            ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
      $sql5   .= "     FROM diariofinal ";
      $sql5   .= "      inner join diario on ed95_i_codigo = ed74_i_diario ";
      $sql5   .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia ";
      $sql5   .= "      inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
      $sql5   .= "      inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
      $sql5   .= "      left join amparo on ed81_i_diario = ed95_i_codigo ";
      $sql5   .= "      left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
      $sql5   .= "      left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
      $sql5   .= "      left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
      $sql5   .= "     WHERE ed95_i_aluno = $ed60_i_aluno ";
      $sql5   .= "     AND ed95_i_regencia in ($reg_pagina) ";
      $sql5   .= "     AND ed59_c_condicao = 'OB' ";
      $sql5   .= "     ORDER BY ed59_i_ordenacao ";
      $result5 = pg_query($sql5);
      $linhas5 = pg_num_rows($result5);
      $cont3   = 0;
      if ($linhas5 > 0) {
      	
        for ($v = 0; $v < $linhas5; $v++) {
        	
          db_fieldsmemory($result5,$v);
          if ($ed60_c_parecer == "S") {
            $ed37_c_tipo = "PARECER";
          }
          if (trim($ed60_c_situacao) != "MATRICULADO") {
          	
            $aproveitamento = substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5);
            $frequencia = "";
            
          } else {
          	
            if (trim($ed81_c_todoperiodo) == "S") {
            	
              if ($ed81_i_justificativa != "") {
                $aproveitamento = "AMP.";
              } else {
                $aproveitamento = $ed250_c_abrev;
              }
              $frequencia = "";
            } else {
            	
              if (trim($ed59_c_freqglob) == "F") {
              	
                $aproveitamento = "-";
                if ($resultedu == 'S') {
                  $frequencia = number_format($ed74_i_percfreq,2,".",".");
                } else {
                  $frequencia = number_format($ed74_i_percfreq,0);
                }
                
              } else if (trim($ed59_c_freqglob) == "A") {
              	
                if (trim($ed37_c_tipo) == "NOTA") {
                	
                  if ($rsultedu=='S') {
                    $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
                  } else {
                    $aproveitamento = number_format($ed74_c_valoraprov,0);
                  }
                  
                } else if (trim($ed37_c_tipo) == "PARECER") {
                  $aproveitamento = "Parecer";
                } else {
                  $aproveitamento = $ed74_c_valoraprov;
                }
                $sql_f    = " SELECT ed74_i_percfreq ";
                $sql_f   .= "        FROM diariofinal ";
                $sql_f   .= "            inner join diario on ed95_i_codigo = ed74_i_diario ";
                $sql_f   .= "            inner join regencia on ed59_i_codigo = ed95_i_regencia ";
                $sql_f   .= "            inner join turma on ed57_i_codigo = ed59_i_turma ";
                $sql_f   .= "        WHERE ed57_i_codigo = $ed57_i_codigo ";
                $sql_f   .= "              AND ed59_c_freqglob = 'F' ";
                $sql_f   .= "              AND ed95_i_aluno = $ed60_i_aluno ";
                $sql_f   .= "              AND ed95_i_regencia = $ed59_i_codigo ";
                $result_f = pg_query($sql_f);
                $linhas_f = pg_num_rows($result_f);
                if ($resultedu == 'S') {
                  $frequencia = number_format(pg_result($result_f,0,'ed74_i_percfreq'),2,".",".");
                } else {
                  $frequencia = number_format(pg_result($result_f,0,'ed74_i_percfreq'),0);
                }
                
              } else {
              	
                if (trim($ed37_c_tipo) == "NOTA") {
                	
                  if ($resultedu == 'S') {
                    $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
                  } else {
                    $aproveitamento = number_format($ed74_c_valoraprov,0);
                  }
                  
                } else if (trim($ed37_c_tipo) == "PARECER") {
                  $aproveitamento = "Parecer";
                } else {
                  $aproveitamento = $ed74_c_valoraprov;
                }
                
                if ($resultedu == 'S') {
                  $frequencia = number_format($ed74_i_percfreq,2,".",".");
                } else {
                  $frequencia = number_format($ed74_i_percfreq,0);
                }
              }
            }
          }
          $fpdf->setfont('arial','',9);
          $fpdf->cell(12,4,$aproveitamento,1,0,"C",$cor);
          $fpdf->cell(10,4,$frequencia,1,0,"C",$cor);
          $fpdf->setfont('arial','b',7);
          $cont3++;
          
        }
        
      } else {
      	
        $fpdf->setfont('arial','b',7);
        $fpdf->cell(12,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),1,0,"C",$cor);
        $fpdf->cell(10,4,"",1,0,"C",$cor);
        $fpdf->setfont('arial','',9);
        $cont3++;
        
      }
      for ($y = $cont3; $y < 9; $y++) {
      	
        $fpdf->cell(12,4,"",1,0,"C",$cor);
        $fpdf->cell(10,4,"",1,0,"C",$cor);
        
      }
      $sql6    = " SELECT ed95_i_codigo ";
      $sql6   .= "     FROM diario ";
      $sql6   .= "      inner join aluno on ed47_i_codigo = ed95_i_aluno ";
      $sql6   .= "      inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
      $sql6   .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia ";
      $sql6   .= "     WHERE ed95_i_aluno = $ed60_i_aluno ";
      $sql6   .= "     AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo ";
      $sql6   .= "                                                                  AND ed59_i_serie = $ed223_i_serie)";
      $sql6   .= "     AND ed59_c_condicao = 'OB' ";
      $sql6   .= "     AND ed74_c_resultadofinal != 'A' ";
      $result6 = pg_query($sql6);
      $linhas6 = pg_num_rows($result6);
      if (trim($ed60_c_situacao)!="MATRICULADO") {
        $rf = "";
      } else {
      	
        if ($linhas6==0) {
          $rf = "APR";
        } else {
          $rf = "REP";
        }
        if (@$ed74_c_valoraprov=="") {
          $rf = "";
        }
      }
      $fpdf->cell(10,4,$rf,1,1,"C",$cor);
      if ($cont4 == $limite && ($cont_geral+1) < $clmatricula->numrows) {
      
        $alt_conv = $pdf->getY();
        $cont5    = 0;
        $quebra   = "0";
        $sWhere   = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
        $sql2     = $clregencia->sql_query("",
                                           "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                           "ed59_i_ordenacao",
                                           $sWhere
                                          );
        $result2  = $clregencia->sql_record($sql2);
        for ($y = 0; $y < $clregencia->numrows; $y++) {
        	
          db_fieldsmemory($result2,$y);
          $fpdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),1,0,"L",0);
          
        }
        $fpdf->setY($alt_conv+10);
        $fpdf->cell(20,4,"",0,0,"L",0);
        $fpdf->cell(100, 5, "______________________________________________________________________", 0, 0, "C", 0);
        $fpdf->cell(160, 5, "______________________________________________________________________", 0, 1, "C", 0);
        $fpdf->cell(120, 5, $nomesecretario." - ".$funcaosecretario, 0, 0, "C", 0);
        $fpdf->cell(180, 5, $nomediretor." - ".$funcaodiretor, 0, 1, "C", 0);
        $fpdf->setY($alt_conv);
        $fpdf->setX(10);
        $fpdf->cell(278,4,"",1,1,"L",0);
        
        if ($claprovconselho->numrows > 0) {
        	
          $fpdf->cell(278,4,"Observações",1,1,"C",0);
          $sepobs = "";
          for ($g = 0; $g < $claprovconselho->numrows; $g++) {
          	
            db_fieldsmemory($result_cons,$g);
            $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe.";
            $obs_cons .= " Justificativa: $ed253_t_obs";
            $sepobs    = "\n";
            $fpdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
            
          }
        }
        $fpdf->addpage('L');
        $fpdf->setfont('arial','b',7);
        $inicio = $pdf->getY();
        $fpdf->cell(5,4,"","LRT",0,"C",0);
        $fpdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
        $sWhere     = " ed59_i_codigo in ($reg_pagina)  AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
        $sql2       = $clregencia->sql_query("",
                                             "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                             "ed59_i_ordenacao",
                                             $sWhere
                                            );
        $result2    = $clregencia->sql_record($sql2);
        $cont       = 0;
        $reg_pagina = 0;
        $sep        = "";
        for ($y = 0; $y < $clregencia->numrows; $y++) {
        	
          db_fieldsmemory($result2,$y);
          if ($y < 9) {
          	
            $fpdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
            $cont++;
            $reg_pagina .= $sep.$ed59_i_codigo;
            $sep         = ",";
            
          }
        }
        
        for ($y=$cont;$y<9;$y++) {
          $fpdf->cell(22,4,"","LRT",0,"C",0);
        } 
        $fpdf->cell(10,4,"RF",1,1,"C",0);
        $fpdf->cell(5,4,"N°",1,0,"C",0);
        $fpdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
        $cont2 = 0;
        for ($y = 0; $y < $clregencia->numrows; $y++) {
        	
          if ($y < 9) {
          	
            $fpdf->cell(12,4,"Aprov",1,0,"C",0);
            $fpdf->cell(10,4,"% Freq",1,0,"C",0);
            $cont2++;
            
          }
        }
        for ($y = $cont2; $y < 9; $y++) {
        	
          $fpdf->cell(12,4,"",1,0,"C",0);
          $fpdf->cell(10,4,"",1,0,"C",0);
          
        }
        $fpdf->cell(10,4,"",1,1,"C",0);
        $cont4 = -1;
      }
      $cont4++;
    }
    for ($z = $cont4; $z < $limite; $z++) {
    	
      $fpdf->cell(5,4,"",1,0,"C",0);
      $fpdf->cell(65,4,"",1,0,"L",0);
      for ($t = 0; $t < 9; $t++) {
      	
        $fpdf->cell(12,4,"",1,0,"C",0);
        $fpdf->cell(10,4,"",1,0,"C",0);
        
      }
      $fpdf->cell(10,4,"",1,1,"C",0);
    }
    $alt_conv = $fpdf->getY();
    $cont5    = 0;
    $quebra   = "0";
    $sWhere   =  " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
    $sql2     = $clregencia->sql_query("",
                                       "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                       "ed59_i_ordenacao",
                                       $sWhere
                                      );
    $result2 = $clregencia->sql_record($sql2);
    
    for ($y = 0; $y < $clregencia->numrows; $y++) {
    	
      db_fieldsmemory($result2,$y);
      $fpdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),1,0,"L",0);
      
    }
    $fpdf->setY($alt_conv+10);
    $fpdf->cell(20,4,"",0,0,"L",0);
    $fpdf->cell(100, 5, "______________________________________________________________________", 0, 0, "C", 0);
    $fpdf->cell(160, 5, "______________________________________________________________________", 0, 1, "C", 0);
    $fpdf->cell(120, 5, $nomesecretario." - ".$funcaosecretario, 0, 0, "C", 0);
    $fpdf->cell(180, 5, $nomediretor." - ".$funcaodiretor, 0, 1, "C", 0);
    $fpdf->setY($alt_conv);
    $fpdf->setX(10);
    $fpdf->cell(278,4,"",1,1,"L",0);  
    if ($claprovconselho->numrows > 0) {
    	
      $fpdf->cell(278,4,"Observações",1,1,"C",0);
      $sepobs = "";
      
      for ($g = 0; $g < $claprovconselho->numrows; $g++) {
      	
        db_fieldsmemory($result_cons,$g);
        $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs";
        $sepobs    = "\n";
        
      }
      $fpdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
    }
  }
}
$fpdf->Output();
?>