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

require("libs/db_stdlibwebseller.php");
include("fpdf151/scpdf.php");
include("classes/db_turma_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciaperiodo_classe.php");
include("classes/db_alunotransfturma_classe.php");
include("classes/db_aprovconselho_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_edu_parametros_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_edu_relatmodel_classe.php");
include("classes/db_telefoneescola_classe.php");
include("classes/db_cursoato_classe.php");
include("classes/db_aluno_classe.php");
require_once("libs/db_utils.php");
$clEduRelatmodel    = new cl_edu_relatmodel();
$clTelefoneEscola   = new cl_telefoneescola();
$clEscola           = new cl_escola();
$clcursoato         = new cl_cursoato;
$claluno            = new cl_aluno;
$escola             = db_getsession("DB_coddepto");
$resultedu          = eduparametros(db_getsession("DB_coddepto"));
$clturma            = new cl_turma();
$clalunotransfturma = new cl_alunotransfturma();
$clescola           = new cl_escola();
$clmatricula        = new cl_matricula();
$clregencia         = new cl_regencia();
$clregenciaperiodo  = new cl_regenciaperiodo();
$claprovconselho    = new cl_aprovconselho();
$clregenciahorario  = new cl_regenciahorario();
$cledu_parametros   = new cl_edu_parametros();
$clcalendario       = new cl_calendario();
$result             = $clturma->sql_record($clturma->sql_query_turmaserie("", 
                                                                          "*", 
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

$sCampos           = " ed18_c_nome as nome_escola,j14_nome as rua_escola,ed18_c_cep as cep_escola, ed18_codigoreferencia,";
$sCampos          .= " ed18_i_numero as num_escola,ed261_c_nome as mun_escola,ed260_c_sigla as uf_escola ";
$sSqlDadosEscola   = $clEscola->sql_query("",$sCampos,"","ed18_i_codigo = $escola");
$rsDadosEscola     = db_query($sSqlDadosEscola);
$oDadosEscola      = db_utils::fieldsMemory($rsDadosEscola,0);
$nome_escola       = $oDadosEscola->nome_escola;
$rua_escola        = $oDadosEscola->rua_escola;
$cep_escola        = $oDadosEscola->cep_escola;
$num_escola        = $oDadosEscola->num_escola;
$mun_escola        = $oDadosEscola->mun_escola;
$uf_escola         = $oDadosEscola->uf_escola;
$iCodigoReferencia = $oDadosEscola->ed18_codigoreferencia;

$sCampo              = "ed217_t_cabecalho,ed217_t_rodape,ed217_t_obs";
$sSqlDadosRelatModel = $clEduRelatmodel->sql_query("",$sCampo,"","ed217_i_codigo = $tipovar");
$rsDadosRelatModel   = $clEduRelatmodel->sql_record($sSqlDadosRelatModel);
if ($clEduRelatmodel->numrows > 0) {
    
  $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosRelatModel,0);
  $sCabecalho        = $oDadosRelatModel->ed217_t_cabecalho;
  $sObs              = $oDadosRelatModel->ed217_t_obs;
  
}
if ($clturma->numrows == 0) {
	
    ?>
   <table width='100%'>
    <tr>
        <td align='center'><font color='#FF0000' face='arial'> <b>Nenhuma
        turma para o curso selecionado<br>
        <input type='button' value='Fechar' onclick='window.close()'></b> </font>
        </td>
    </tr>
   </table>
<?
    exit ();
    
}
$ano_calendario    = pg_result($result, 0, 'ed52_i_ano');
$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("", 
                                                                                "ed233_c_database,ed233_c_limitemov", 
                                                                                "", 
                                                                                " ed233_i_escola = $escola"
                                                                               )
                                                  );
if ($cledu_parametros->numrows > 0) {
	
    db_fieldsmemory($result_parametros, 0);
    if (!strstr($ed233_c_database, "/") || !strstr($ed233_c_limitemov, "/")) {
    	
        ?>
      <table width='100%'>
       <tr>
         <td align='center'><font color='#FF0000' face='arial'> <b>Parâmetros
            Dia/Mês Limite da Movimentação e Data Base para Cálculo da Idade
            (Procedimentos->Parâmetros)<br>
            devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
            Valor atual do parâmetro Dia/Mês Limite da Movimentação: 
            <?=trim($ed233_c_limitemov) == "" ? "Não informado" : $ed233_c_limitemov?><br>
            Valor atual do parâmetro Data Base para Cálculo da Idade: 
            <?=trim($ed233_c_database) == "" ? "Não informado" : $ed233_c_database?><br>
            <br></b> 
            <input type='button' value='Fechar' onclick='window.close()'> </font>
        </td>
       </tr>
      </table>
<?
      exit ();
      
    }
    
    $database = explode("/", $ed233_c_database);
    $limitemov = explode("/", $ed233_c_limitemov);
    $dia_database = $database [0];
    $mes_database = $database [1];
    $dia_limitemov = $limitemov [0];
    $mes_limitemov = $limitemov [1];
    if (@!checkdate($mes_database, $dia_database, $ano_calendario) 
        || @!checkdate($mes_limitemov, $dia_limitemov, $ano_calendario)) {
        	
        ?>
      <table width='100%'>
        <tr>
          <td align='center'><font color='#FF0000' face='arial'> <b>Parâmetros
             Dia/Mês Limite da Movimentação e Data Base para Cálculo da Idade
             (Procedimentos->Parâmetros)<br>
             devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e devem
             ser uma data válida.<br><br>
             Valor atual do parâmetro Dia/Mês Limite da Movimentação: 
             <?=trim($ed233_c_limitemov) == "" ? "Não informado" : $ed233_c_limitemov?><br>
             Valor atual do parâmetro Data Base para Cálculo da Idade: 
             <?=trim($ed233_c_database) == "" ? "Não informado" : $ed233_c_database?><br>
             <br>
             Data Limite da Movimentação: <?=$dia_limitemov . "/" . $mes_limitemov . "/" . $ano_calendario?> 
             <?=@!checkdate($mes_limitemov, $dia_limitemov, $ano_calendario) ? "(Data Inválida)" : "(Data Válida)"?><br>
             Data Base para Cálculo Idade: <?=$dia_database . "/" . $mes_database . "/" . $ano_calendario?> 
             <?=@!checkdate($mes_database, $dia_database, $ano_calendario) ? "(Data Inválida)" : "(Data Válida)"?><br>
             <br></b> 
             <input type='button' value='Fechar' onclick='window.close()'> </font>
          </td>
         </tr>
        </table>
<?
      exit ();
      
    }
    $databasecalc  = $ano_calendario . "-" . (strlen($mes_database) == 1 ? "0" . $mes_database : $mes_database) . "-" . 
                                             (strlen($dia_database) == 1 ? "0" . $dia_database : $dia_database);
    $datalimitemov = $ano_calendario . "-" . (strlen($mes_limitemov) == 1 ? "0" . $mes_limitemov : $mes_limitemov) . "-" .
                                             (strlen($dia_limitemov) == 1 ? "0" . $dia_limitemov : $dia_limitemov);
                                             
} else {
	
  $databasecalc  = $ano_calendario . "-12-31";
  $datalimitemov = $ano_calendario . "-01-01";
  
}

if ($diretor != "") {
    
  $arr_diretor   = explode("|",$diretor);
  $nomediretor   = $arr_diretor[1];
  $funcaodiretor =(trim($arr_diretor[2]) != ""?" ($arr_diretor[2])":"");
  
} else {
    
  $nomediretor   = "";
  $funcaodiretor = "";
  
}

if ($secretario != "") {
    
  $arr_secretario   = explode("|",$secretario);
  $nomesecretario   = $arr_secretario[1];
  $funcaosecretario = (trim($arr_secretario[2]) != ""?" ($arr_secretario[2])":"");
  
} else {
    
  $nomesecretario   = "";
  $funcaosecretario = "";
  
}

if ( trim($iCodigoReferencia) != null ) {
  $nome_escola = "{$iCodigoReferencia} - {$nome_escola}";
}

$fpdf = new FPDF();
$fpdf->Open();
$fpdf->AliasNbPages();
$fpdf->SetMargins(5,1,5);
$linhas = $clturma->numrows;
for ($o = 0; $o < $linhas; $o ++) {

  db_fieldsmemory($result, $o);
  if ($ed57_c_medfreq == "PERÌODOS") {
  	
  	$sWhere  = " ed78_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo";
  	$sWhere .= "                                                         AND ed59_i_serie = $ed223_i_serie)";
    $sql1    = $clregenciaperiodo->sql_query("",
                                             "sum(ed78_i_aulasdadas) as aulas", 
                                             "", 
                                             $sWhere
                                            );
    $result1 = $clregenciaperiodo->sql_record($sql1);
    db_fieldsmemory($result1, 0);
    $descr_num = "Carga Horária";
    
  } else {
  	
    $aulas     = $ed52_i_diasletivos * 4;
    $descr_num = "Dias Letivos";
    
  }
  
  $fpdf->setfillcolor(223);
  $dia      = substr($ed52_d_resultfinal, 8, 2);
  $mes      = db_mes(substr($ed52_d_resultfinal, 5, 2));
  $ano      = substr($ed52_d_resultfinal, 0, 4);
  $result11 = $clescola->sql_record($clescola->sql_query("", "ed261_c_nome", "", " ed18_i_codigo = $escola"));
  db_fieldsmemory($result11, 0);
  $fpdf->addpage('P');
  $fpdf->ln(5);
  $dados = db_query($conn,"select nomeinst,trim(ender)||','||trim(numero) as ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
  $url = @pg_result($dados,0,"url");
  $fpdf->setXY(120,4);
  if ($brasao == "b1") {
    $fpdf->Image('imagens/files/'.pg_result($dados,0,"logo"),5,3,20);    
  } else {   
    $fpdf->Image('',5,3,20);
  }
  $fpdf->setfont('arial','b',8);
  $fpdf->setXY(24,5);
  $fpdf->multicell(77,4,$sCabecalho,0,"C",0,0);
  $fpdf->setXY(115,5);
  $result_mant     = db_query($conn,"select nomeinst from db_config where codigo = ".db_getsession("DB_instit"));
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
      $sep_escola  = "\n";
      
    }
  } else {
    $ato_escola = "";
  }
  $cabecalho_escola  = "$nome_escola\nMantenedora: $mantenedora\nEndereço: $rua_escola , ";
  $cabecalho_escola .= " $num_escola\nCEP: $cep_escola - $mun_escola / $uf_escola $sTelEscola";
  $fpdf->multicell(105,3,$cabecalho_escola,0,"L",0,0);
  $fpdf->setX(90);
  $fpdf->multicell(100,2,"","","L",0,0);
  $fpdf->setX(115);
  $fpdf->setfont('arial','b',6);
  $fpdf->multicell(100,3,$ato_escola,"","L",0,0);
  $fpdf->setY(28);
  $fpdf->setfont('arial', '', 7);
  $fpdf->setX(5);
  $fpdf->cell(20, 4, "Tipo de Ensino :", 0, 0, "L", 0);
  $fpdf->cell(90, 4, $ed10_c_descr, 0, 1, "L", 0);
  
  $fpdf->setX(5);
  $fpdf->cell(10, 4, "Etapa :", 0, 0, "L", 0);
  $fpdf->cell(66, 4, $ed11_c_descr, 0, 0, "L", 0);
  $fpdf->cell(7, 4, "Ano :", 0, 0, "L", 0);
  $fpdf->cell(27, 4, $ed52_i_ano, 0, 0, "L", 0);
  $fpdf->cell(7, 4, "C.H :", 0, 0, "L", 0);
  $fpdf->cell(15, 4, $aulas, 0, 0, "L", 0); 
  $fpdf->cell(10, 4, "Curso :", 0, 0, "L", 0);
  $fpdf->cell(15, 4, $ed29_c_descr, 0, 1, "L", 0); 
  $fpdf->setX(5);
  $fpdf->cell(10, 4, "Turma :", 0, 0, "L", 0);
  $fpdf->cell(66, 4, $ed57_c_descr, 0, 0, "L", 0);
  $fpdf->cell(17, 4, "Dias Letivos :", 0, 0, "L", 0);
  $fpdf->cell(17, 4, $ed52_i_diasletivos, 0,0, "L", 0);
  $fpdf->cell(10, 4, "Turno :", 0, 0, "L", 0);
  $fpdf->cell(20, 4, $ed15_c_nome, 0, 1, "L", 0);
  $fpdf->setX(90);
  $fpdf->setY(40);
  $inicio = $fpdf->getY ();
  $fpdf->cell(5, 4, "", "LRT", 0, "C", 0);
  $fpdf->cell(70, 4, "Disciplinas", "LRT", 0, "R", 0);
  $sWhere     = " ed59_i_turma = $ed57_i_codigo AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
  $sql2       = $clregencia->sql_query("",
                                       "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao", 
                                       "ed59_i_ordenacao", 
                                       $sWhere
                                      );
  $result2    = $clregencia->sql_record($sql2);
  $cont       = 0;
  $reg_pagina = 0;
  $sep        = "";
  for ($y = 0; $y < $clregencia->numrows; $y ++) {
  	
	db_fieldsmemory($result2, $y);
	if ($y < 10) {
		
	  $fpdf->cell(11, 4, $ed232_c_abrev, "LRT", 0, "C", 0);
	  $cont ++;
	  $reg_pagina .= $sep . $ed59_i_codigo;
	  $sep = ",";
	  
	}
  }
  for ($y = $cont; $y < 10; $y ++) {
	$fpdf->cell(11, 4, "", "LRT", 0, "C", 0);
  }
  if ($ed57_c_medfreq == "PERÌODOS") {
   	
   	 $altura       = 8;
   	 $alturainicio = 4;
  } else {
   	
   	 $altura       = 6;
   	 $alturainicio = 2;
   	 
  }
  $fpdf->cell(11, $altura, "RF", 1, 1, "C", 0);
  $fpdf->setY($inicio + $alturainicio);
  $fpdf->cell(5, 4, "", "LRB", 0, "C", 0);
  if ($ed57_c_medfreq == "PERÌODOS") {
    $fpdf->cell(70, 4, $descr_num, "LRB", 0, "R", 0);
  } else {
   	$fpdf->cell(70, 4, "", "LRB", 0, "R", 0);
  }
  $cont1 = 0;
  for ($y = 0; $y < $clregencia->numrows; $y ++) {
  	
    db_fieldsmemory($result2, $y);
    $sql3    = $clregenciaperiodo->sql_query("",
                                             "sum(ed78_i_aulasdadas) as aulas", 
                                             "", 
                                             " ed78_i_regencia = $ed59_i_codigo AND ed09_c_somach = 'S'"
                                            );
    $result3 = $clregenciaperiodo->sql_record($sql3);
    db_fieldsmemory($result3, 0);
    if ($ed57_c_medfreq == "PERÌODOS") {
      $aulamostra = $aulas == "" ? "0" : $aulas;
    } else {
      $aulamostra = "";
    }
    if ($y < 10) {
    	
      $fpdf->cell(11, 4,$aulamostra, "LRB", 0, "C", 0);
      $cont1 ++;
      
    }
  }

  for ($y = $cont1; $y < 10; $y ++) {
	$fpdf->cell(11, 4, "", "LRB", 0, "C", 0);
  }
  $fpdf->cell(11, 4, "", 0, 1, "C", 0);
  $fpdf->cell(5, 4, "N°", 1, 0, "C", 0);
  $fpdf->cell(70, 4, "Nome do Aluno", 1, 0, "C", 0);
  $cont2 = 0;
  for ($y = 0; $y < $clregencia->numrows; $y ++) {
  	
	if ($y < 10) {
		
	  $fpdf->cell(11, 4, "Aprov", 1, 0, "C", 0);
	  $cont2 ++;
	  
	}
  }
  for ($y = $cont2; $y < 10; $y ++) {
	$fpdf->cell(11, 4, "", 1, 0, "C", 0);
  }
  $fpdf->cell(11, 4, "", 1, 1, "C", 0);
  if (@$active == "SIM") {
	$condicao = "and ed60_c_situacao='MATRICULADO'";
  } else {
	$condicao = "";
  }
  $campodtsaida  = " to_char(ed60_d_datasaida,'DD/MM/YYYY') as datasaida,ed47_i_codigo,ed47_v_nome, ed47_d_nasc, ";
  $campodtsaida .= " ed47_v_sexo,ed60_c_situacao,ed60_d_datamatricula,ed60_i_codigo,ed60_c_tipo, ";
  $campodtsaida .= " fc_idade(ed47_d_nasc,'$databasecalc'::date) as idadealuno,ed60_i_codigo,ed60_c_parecer, ";
  $campodtsaida .= " ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed60_i_turma,ed47_v_nome,";
  $campodtsaida .= " ed60_d_datamodif,ed60_d_datasaida";
  $sWhere        = " ed60_i_turma = $ed57_i_codigo AND ed60_c_ativa = 'S' AND ed221_i_serie = $ed223_i_serie ";
  $sWhere       .= "                                                      and ed60_c_situacao!='TROCA DE MODALIDADE'";
  $sql4          = $clmatricula->sql_query("",
                                           $campodtsaida, 
                                           "to_ascii(ed47_v_nome)", 
                                           $sWhere
                                          );	
  $result4       = $clmatricula->sql_record($sql4);
  $cor1          = 0;
  $cor2          = 1;
  $cor           = "";
  $cont4         = 0;
  $limite        = 41;
  $cont_geral    = 0;
  for ($z = 0; $z < $clmatricula->numrows; $z ++) {
  	
	db_fieldsmemory($result4, $z);
	if ($datasaida != "") {
		
	  $comp_datasaida = explode("/", $datasaida);
	  $comp_datasaida = $comp_datasaida [2] . $comp_datasaida [1] . $comp_datasaida [0];
	  
	} else {
	  $comp_datasaida = 0;
	}
	
	$comp_datalimitemov = str_replace("-", "", $datalimitemov);
	if ($datasaida == "" or ($datasaida != "" and $comp_datasaida > $comp_datalimitemov)) {
		
	  if ($cor == $cor1) {
		$cor = $cor2;
	  } else {
		$cor = $cor1;
	  }
	  $fpdf->cell(5, 4, $ed60_i_numaluno, "LR", 0, "C", $cor);
	  $fpdf->setfont('arial', 'b', 6);
	  $fpdf->cell(70, 4, $ed47_v_nome, "LR", 0, "L", $cor);
	  
	}
	$sql5    = " SELECT ed74_c_resultadofinal,ed74_c_valoraprov,ed74_i_percfreq,ed81_c_todoperiodo,ed37_c_tipo, ";
    $sql5   .= "             ed59_c_freqglob,ed74_i_diario,ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
    $sql5   .= "      FROM diariofinal ";
    $sql5   .= "       inner join diario on ed95_i_codigo = ed74_i_diario ";
    $sql5   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sql5   .= "       inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
    $sql5   .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
    $sql5   .= "       left join amparo on ed81_i_diario = ed95_i_codigo ";
    $sql5   .= "       left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
    $sql5   .= "      left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
    $sql5   .= "       left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
    $sql5   .= "      WHERE ed95_i_aluno = $ed60_i_aluno "; 
    $sql5   .= "      AND ed95_i_regencia in ($reg_pagina) ";
    $sql5   .= "      AND ed59_c_condicao = 'OB' ";
    $sql5   .= "      ORDER BY ed59_i_ordenacao ";
	$result5 = db_query($sql5);
	$linhas5 = pg_num_rows($result5);
	$cont3   = 0;
	if ($linhas5 > 0) {
		
	  for ($v = 0; $v < $linhas5; $v ++) {
	  	
		db_fieldsmemory ( $result5, $v );
		if ($ed60_c_parecer == "S") {
		  $ed37_c_tipo = "PARECER";
		}
		$naomatric = false;
		if ($datasaida == "" or ($datasaida != "" and $comp_datasaida > $comp_datalimitemov)) {
			
		  if (trim($ed60_c_situacao) != "MATRICULADO") {
		  	
			$aproveitamento = trim(Situacao($ed60_c_situacao, $ed60_i_codigo)) . " em " . db_formatar($ed60_d_datasaida, 'd');
			$naomatric = true;
			$fpdf->cell(55, 4, $aproveitamento, "LR", 0, "L", $cor);
			$fpdf->cell(11, 4, "", "LR", 0, "L", $cor);
			$fpdf->cell(11, 4, "", "LR", 0, "L", $cor);
			$fpdf->cell(11, 4, "", "LR", 0, "L", $cor);
			$fpdf->cell(11, 4, "", "LR", 0, "L", $cor);
			$fpdf->cell(11, 4, "", "LR", 0, "L", $cor);			
			break;
			
		  } else {
		  	
			if (trim($ed81_c_todoperiodo) == "S") {
				
			  if ($ed81_i_justificativa) {
				$aproveitamento = "AMPARO";
			  } else {
				$aproveitamento = $ed250_c_abrev;
			  }
			  
			} else {
				
			  if (trim($ed37_c_tipo) == "NOTA") {
			  	
				if ($resultedu == 'S') {
				  $aproveitamento = number_format($ed74_c_valoraprov, 2, ".", ".");
				} else {
				  $aproveitamento = number_format($ed74_c_valoraprov, 0, ".", ".");
				}
				
			  } else if (trim($ed37_c_tipo) == "PARECER") {
				$aproveitamento = "Parec";
			  } else {
				$aproveitamento = $ed74_c_valoraprov;
			  }
			}
			if (trim($ed59_c_freqglob) == "F") {
			  $aproveitamento = $ed74_i_percfreq . "%";
            }
			$fpdf->cell(11, 4, $aproveitamento, "LR", 0, "C", $cor);
			$naomatric = false;
			$cont3 ++;
			
		  }
		}
	  }
	} else {
		
	  $fpdf->cell(11, 4, substr(trim(Situacao($ed60_c_situacao, $ed60_i_codigo)), 0, 5), "LR", 0, "C", $cor);
	  $cont3 ++;
	  
    }
	if ($datasaida == "" or ($datasaida != "" and $comp_datasaida > $comp_datalimitemov)) {
		
	  if (@$naomatric == false) {
	  	
		for ($y = $cont3; $y < 10; $y ++) {
		  $fpdf->cell(11, 4, "", "LR", 0, "C", $cor);
		}
		
	  }
	}
    $sql6    = " SELECT ed95_i_codigo ";
    $sql6   .= "      FROM diario ";
    $sql6   .= "      inner join aluno on ed47_i_codigo = ed95_i_aluno ";
    $sql6   .= "       inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
    $sql6   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sql6   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sql6   .= "      AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo ";
    $sql6   .= "                                                                   AND ed59_i_serie = $ed223_i_serie)";
    $sql6   .= "      AND ed59_c_condicao = 'OB' ";
    $sql6   .= "      AND ed74_c_resultadofinal != 'A' ";		
	$result6 = db_query($sql6);
	$linhas6 = pg_num_rows($result6);	
	if (trim($ed60_c_situacao) != "MATRICULADO") {
	  $rf = "";
	} else {
		
	  if ($linhas6 == 0) {
		$rf = "A";
	  } else {
		$rf = "R";
	  }
	  
	}
	
	if ($datasaida == "" or ($datasaida != "" and $comp_datasaida > $comp_datalimitemov)) {
		
	  $fpdf->cell(11, 4, $rf, "LR", 1, "C", $cor);	 
	  
	}
	
    if ($cont4 == $limite && ($cont_geral + 1) < $clmatricula->numrows) {
    	
	  $fpdf->cell(100, 4, "Observações", 1, 0, "C", 0);
	  $fpdf->cell(93, 4, "Convenções", 1, 1, "C", 0);
	  $sWhere   = " ed59_i_codigo in ($reg_pagina)  AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
	  $sql2     = $clregencia->sql_query("",
	                                     "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
	                                     "ed59_i_ordenacao", 
	                                     $sWhere
	                                    );
	  $result2  = $clregencia->sql_record($sql2);
	  $alt_conv = $fpdf->getY();
	  $cont5    = 0;
	  $borda    = "L";
	  $quebra   = "0";
	  $fpdf->setY($alt_conv);
	  $fpdf->setX(105);
	  $fpdf->rect(105, $alt_conv, 96, 24);
	  for ($y = 0; $y < $clregencia->numrows; $y ++) {
	  	
		db_fieldsmemory($result2, $y);
		$cont5 ++;
		$fpdf->setfont('arial', 'b', 6);
		$fpdf->cell(70, 4, $ed232_c_abrev . " - " . $ed232_c_descr, $borda, $quebra, "L", 0);
		if (($cont5 % 2) != 0) {
			
		  $borda  = "R";
		  $quebra = "1";
		  $fpdf->setX(160);
		  
		} else {
			
		  $borda  = "L";
		  $quebra = "0";
		  $fpdf->setX(105);
		  
		}
		
	  }
	  
	  if ($quebra == "1") {
	  	
		$fpdf->cell(96, 4, "", "R", 1, "L", 0);
		$cont5 ++;
		
	  }
	  
	  for ($y = ($cont5 / 2); $y < 12; $y ++) {
		$fpdf->cell(96, 4, "", "L", 1, "L", 0);
	  }
	  $sCampos       = " ed69_d_datatransf,ed47_v_nome,cursoedu.ed29_i_ensino as origem,";
	  $sCampos      .= " cursodestino.ed29_i_ensino as destino";
	  $sql7          = $clalunotransfturma->sql_query("",
	                                                  $sCampos, 
	                                                  "", 
	                                                  "ed69_i_turmaorigem = $ed57_i_codigo"
	                                                 );
	  $result7       = $clalunotransfturma->sql_record($sql7);
	  $cont          = 0;
	  $transf_pagina = "";						
	  if ($transfer == "yes") {
	  					
        for ($d = 0; $d < $clalunotransfturma->numrows; $d ++) {
        	
		  db_fieldsmemory($result7, $d);
		  if ($origem == $destino) {
			$teste = "trocou de turma em";
		  } else {
			$teste = "trocou de modalidade em";
		  }
		  $transf_pagina .= "-Aluno(a) " . $ed47_v_nome . " " . $teste . " " .db_formatar($ed69_d_datatransf,'d')."\n";
	    }
	    
	  }	
	  $obs_cons    = "";
	  $sCampos     = "z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao";
	  $sWhere      = "ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie";
	  $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("",
	                                                                          $sCampos, 
	                                                                          "ed59_i_ordenacao", 
	                                                                          $sWhere
	                                                                         )
	                                             );
	  if ($claprovconselho->numrows > 0) {
	  	
		$sepobs = "";
		for ($g = 0; $g < $claprovconselho->numrows; $g ++) {
			
		   db_fieldsmemory($result_cons, $g);
		   $obs_cons .= $sepobs . "-Aluno(a) $ed47_v_nome na Disciplina $disc_conselho foi aprovado pelo Conselho ";
		   $obs_cons .= " de Classe. Justificativa: $ed253_t_obs";
		   $sepobs    = "\n";
		   
		}
		
	  }
	  $fpdf->setY($alt_conv);
	  $fpdf->rect(5, $alt_conv, 100, 48);
	  $fpdf->setfont('arial', 'b', 6);
	  $fpdf->multicell(100, 4,($sObs != "" ? $sObs . "\n" : "") , "L", "J", 0, 0);  
      $fpdf->setY($alt_conv+25 );
   	  $fpdf->multicell(100, 4,($ed57_t_obs != "" ? $ed57_t_obs . "\n" : "") . 
   	                         ($transf_pagina != "" ? $transf_pagina . "\n" : "") . 
   	                         ($obs_cons != "" ? $obs_cons . "\n" : ""), "L", "J", 0, 0);
	  $fpdf->setY($alt_conv + 24);
	  $fpdf->setX(105);
	  $fpdf->cell(96, 4, "E, para constar, foi lavrada esta ata.", "LTR", 2, "C", 0);
  	  $fpdf->cell(96, 4, "$ed261_c_nome, " . date("d", db_getsession("DB_datausu")) . " de " . 
  	                                        db_mes(date("m", db_getsession("DB_datausu"))) . " de " . 
  	                                        date("Y", db_getsession("DB_datausu")), "LR", 2, "C", 0);
	  $fpdf->cell(96, 4, "", "LR", 2, "L", 0);
	  $fpdf->cell(48, 4, "_______________________", "L", 0, "C", 0);
	  $fpdf->cell(48, 4, "_______________________", "R", 1, "C", 0 );
	  $fpdf->setX(105);
	  $fpdf->cell(48, 4, "SECRETÁRIO(A)", "L", 0, "C", 0);
	  $fpdf->cell(48, 4, "DIRETOR(A)", "R", 1, "C", 0);
      $fpdf->setX(105);
      $fpdf->cell(48, 4, $nomesecretario, 0, 0, "C", 0);
      $fpdf->cell(48, 4, $nomediretor, "R", 1, "C", 0);       
      $fpdf->cell(196, 2, "", 1, 1, "C", 0);       
	  $fpdf->addpage('P');
      $fpdf->ln(5);
	  $fpdf->setfont('arial', 'b', 7);
	  $inicio = $fpdf->getY();
	  $fpdf->cell(5, 4, "", "LRT", 0, "C", 0);
	  $fpdf->cell(70, 4, "Disciplinas", "LRT", 0, "R", 0);
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
	  for ($y = 0; $y < $clregencia->numrows; $y ++) {
	  	
		db_fieldsmemory($result2, $y);
		if ($y < 10) {
			
		  $fpdf->cell(11, 4, $ed232_c_abrev, "LRT", 0, "C", 0);
		  $cont ++;
		  $reg_pagina .= $sep . $ed59_i_codigo;
		  $sep         = ",";
		  
		}
	  }
	  for ($y = $cont; $y < 10; $y ++) {
		$fpdf->cell(11, 4, "", "LRT", 0, "C", 0);
	  }
	  $fpdf->cell(11, 8, "RF", 1, 1, "C", 0);
	  $fpdf->setY($inicio + 4 );
	  $fpdf->cell(5, 4, "", "LRB", 0, "C", 0);
	  $fpdf->cell(70, 4, $descr_num, "LRB", 0, "R", 0);
	  $cont1 = 0;
	  for ($y = 0; $y < $clregencia->numrows; $y ++) {
	  	
		db_fieldsmemory($result2, $y);
		$sql3    = $clregenciaperiodo->sql_query("",
		                                         "sum(ed78_i_aulasdadas) as aulas", 
		                                         "", 
		                                         " ed78_i_regencia = $ed59_i_codigo"
		                                        );
		$result3 = $clregenciaperiodo->sql_record($sql3);
		db_fieldsmemory($result3, 0);
		if ($y < 10) {
			
		  $fpdf->cell(11, 4, $aulas == "" ? "0" : $aulas, "LRB", 0, "C", 0);
		  $cont1 ++;
		  
		}
		
	  }
	  for ($y = $cont1; $y < 10; $y ++) {
		$fpdf->cell(11, 4, "", "LRB", 0, "C", 0);
	  }
      $fpdf->cell(8, 4, "", 0, 1, "C", 0);
	  $fpdf->cell(5, 4, "N°", 1, 0, "C", 0);
	  $fpdf->cell(70, 4, "Nome do Aluno", 1, 0, "C", 0);
	  $cont2 = 0;
	  for ($y = 0; $y < $clregencia->numrows; $y ++) {
	  	
		if ($y < 10) {
			
		  $fpdf->cell(11, 4, "Aprov", 1, 0, "C", 0);
		  $cont2 ++;
		  
		}
		
	  }
	  for ($y = $cont2; $y < 10; $y ++) {
		$fpdf->cell(11, 4, "", 1, 0, "C", 0);
	  }
	  $fpdf->cell(8, 4, "", 1, 1, "C", 0);		
	  $cont4 = - 1;
    }
    
	$cont4 ++;
	$cont_geral ++;
	
  }
  
  for ($z = $cont4; $z < $limite + 1; $z ++) {
  	
	$fpdf->cell(5, 4, "", "LR", 0, "C", 0);
	$fpdf->cell(70, 4, "", "LR", 0, "L", 0);
	
	for ($t = 0; $t < 10; $t ++) {
	  $fpdf->cell(11, 4, "", "LR", 0, "C", 0);
	}
	$fpdf->cell(11, 4, "", "LR", 1, "C", 0);
	
  }
  $fpdf->cell(100, 4, "Observações", 1, 0, "C", 0);
  $fpdf->cell(96, 4, "Convenções", 1, 1, "C", 0);
  $sWhere   = " ed59_i_codigo in ($reg_pagina)  AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
  $sql2     = $clregencia->sql_query("",
                                     "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao", 
                                     "ed59_i_ordenacao", 
                                     $sWhere
                                    );
  $result2  = $clregencia->sql_record($sql2);
  $alt_conv = $fpdf->getY();
  $cont5    = 0;
  $borda    = "L";
  $quebra   = "0";
  $fpdf->setY($alt_conv);
  $fpdf->setX(105);
  $fpdf->rect(105, $alt_conv, 96, 24);
  for ($y = 0; $y < $clregencia->numrows; $y ++) {
  	
	db_fieldsmemory($result2, $y);
	$cont5 ++;
	$fpdf->setfont('arial', 'b', 6);
	$fpdf->cell(70, 4, $ed232_c_abrev . " - " . $ed232_c_descr, $borda, $quebra, "L", 0);
	
	if (($cont5 % 2) != 0) {
		
	  $borda  = "R";
	  $quebra = "1";
	  $fpdf->setX(160);
	  
	} else {
		
      $borda  = "L";
	  $quebra = "0";
	  $fpdf->setX(105);
	  
	}
	
  }
  
  if ($quebra == "1") {
  	
	$fpdf->cell(96, 4, "", "R", 1, "L", 0);
	$cont5 ++;
	
  }
  
  for ($y = ($cont5 / 2); $y < 12; $y ++) {
	$fpdf->cell(96, 4, "", "L", 1, "L", 0);
  }
  	
  $sCampos       = "ed69_d_datatransf,ed47_v_nome,cursoedu.ed29_i_ensino as origem,cursodestino.ed29_i_ensino as destino";
  $sql7          = $clalunotransfturma->sql_query("",
                                                  $sCampos, 
                                                  "", 
                                                  "ed69_i_turmaorigem = $ed57_i_codigo"
                                                 );
  $result7       = $clalunotransfturma->sql_record($sql7);
  $cont          = 0;
  $transf_pagina = "";
  if ($transfer == "yes") {
  		
	for ($d = 0; $d < $clalunotransfturma->numrows; $d ++) {
		
	  db_fieldsmemory($result7, $d);
	  if ($origem == $destino) {
		$teste = "trocou de turma em";
	  } else {
		$teste = "trocou de modalidade em";
	  }
	  $transf_pagina .= "-Aluno(a) " . $ed47_v_nome . " " . $teste . " " . db_formatar($ed69_d_datatransf, 'd') . "\n";
	}
	
  }	
  $obs_cons    = "";
  $sCampos     = "z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao";
  $sWhere      = "ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie";
  $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("",
                                                                          $sCampos, 
                                                                          "ed59_i_ordenacao", 
                                                                          $sWhere
                                                                         )
                                             );
  if ($claprovconselho->numrows > 0) {
  	
	$sepobs = "";
	for ($g = 0; $g < $claprovconselho->numrows; $g ++) {
		
	  db_fieldsmemory($result_cons, $g);
	  $obs_cons .= $sepobs . "-Aluno(a) $ed47_v_nome na Disciplina $disc_conselho foi aprovado pelo Conselho de Classe.";
	  $obs_cons .= " Justificativa: $ed253_t_obs";
	  $sepobs    = "\n";
	  
	}
	
  }
  $fpdf->setY($alt_conv);
  $fpdf->rect(5, $alt_conv, 100, 48);
  $fpdf->setfont('arial', 'b', 6);
  $fpdf->multicell(100, 4,($sObs != "" ? $sObs . "\n" : "") , "L", "J", 0, 0);  
  $fpdf->setY($alt_conv+25);
  $fpdf->multicell(100, 4,($ed57_t_obs != "" ? $ed57_t_obs . "\n" : "") . 
	                     ($transf_pagina != "" ? $transf_pagina . "\n" : "") . 
	                     ($obs_cons != "" ? $obs_cons . "\n" : ""), "L", "J", 0, 0);
  $fpdf->setY( $alt_conv + 24 );
  $fpdf->setX(105);
  $fpdf->cell(96, 4, "E, para constar, foi lavrada esta ata.", "LTR", 2, "C", 0);
  $fpdf->cell(96, 4, "$ed261_c_nome, " . date("d", db_getsession("DB_datausu")) . " de " . 
  	                                        db_mes(date("m", db_getsession("DB_datausu"))) . " de " . 
  	                                        date("Y", db_getsession("DB_datausu")), "LR", 2, "C", 0);
  $fpdf->cell(96, 4, "", "LR", 2, "L", 0);
  $fpdf->cell(48, 4, "_______________________", "L", 0, "C", 0);
  $fpdf->cell(48, 4, "_______________________", "R", 1, "C", 0 );
  $fpdf->setX(105);
  $fpdf->cell(48, 4, "SECRETÁRIO(A)", "L", 0, "C", 0);
  $fpdf->cell(48, 4, "DIRETOR(A)", "R", 1, "C", 0);
  $fpdf->setX(105);
  $fpdf->cell(48, 4, $nomesecretario, 0, 0, "C", 0);
  $fpdf->cell(48, 4, $nomediretor, "R", 1, "C", 0);       
  $fpdf->cell(196, 2, "", 1, 1, "C", 0); 
  $sWhere  = " ed59_i_turma = $ed57_i_codigo AND ed59_i_codigo not in ($reg_pagina) AND ed59_c_condicao = 'OB'";
  $sWhere .= "                                                                AND ed59_i_serie = $ed223_i_serie";  
  $fpdf->addpage('P');
  $fpdf->ln(5);
  $dados = db_query($conn,"select nomeinst,trim(ender)||','||trim(numero) as ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
  $url = @pg_result($dados,0,"url");
  $fpdf->setXY(120,4);
  if ($brasao == "b1") {
    $fpdf->Image('imagens/files/'.pg_result($dados,0,"logo"),5,3,20);    
  } else {   
    $fpdf->Image('',5,3,20);
  }
  $fpdf->setfont('arial','b',8);
  $fpdf->setXY(24,5);
  $fpdf->multicell(77,4,$sCabecalho,0,"C",0,0);
  $fpdf->setXY(115,5);
  $result_mant     = db_query($conn,"select nomeinst from db_config where codigo = ".db_getsession("DB_instit"));
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
      $sep_escola  = "\n";
      
    }
  } else {
    $ato_escola = "";
  }
  $cabecalho_escola  = "$nome_escola\nMantenedora: $mantenedora\nEndereço: $rua_escola , ";
  $cabecalho_escola .= " $num_escola\nCEP: $cep_escola - $mun_escola / $uf_escola $sTelEscola";
  $fpdf->multicell(105,3,$cabecalho_escola,0,"L",0,0);
  $fpdf->setX(90);
  $fpdf->multicell(100,2,"","","L",0,0);
  $fpdf->setX(115);
  $fpdf->setfont('arial','b',6);
  $fpdf->multicell(100,3,$ato_escola,"","L",0,0);
  $fpdf->setY(28);
  $sql99    = $clregenciahorario->sql_query("", 
	                                          "distinct ed20_i_codigo,case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome", 
	                                          "", 
	                                          " ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie and ed58_ativo is true  "
	                                         );
	                                         
	$result99 = $clregenciahorario->sql_record($sql99);
	$linhas99 = $clregenciahorario->numrows;
	if ($clregenciahorario->numrows > 0) {
		
	  for ($p = 0; $p < $linhas99; $p ++) {
	  	
		db_fieldsmemory($result99, $p);
		$fpdf->cell(10, 8, "", 0, 1, "L", 0);
		$fpdf->line(10, $fpdf->getY(), 60, $fpdf->getY());
		$fpdf->cell(190, 4, "Professor", 0, 1, "L", 0);
		$fpdf->cell(190, 4, $z01_nome . "-" . $ed20_i_codigo, 0, 1, "L", 0);
		$sql100    = $clregenciahorario->sql_query("",
		                                           "distinct ed232_c_descr", 
		                                           "", 
		                                           " ed58_i_rechumano = $ed20_i_codigo AND ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie and ed58_ativo is true  "
		                                          );
		$result100 = $clregenciahorario->sql_record($sql100);
		
		for ($w = 0; $w < $clregenciahorario->numrows; $w ++) {
			
		  db_fieldsmemory ($result100, $w);
		  $fpdf->cell(190, 4, $ed232_c_descr, 0, 1, "L", 0);
		  $fpdf->cell(220, 2, "", 0, 1, "L", 0);
		  
		}
		
	  }
	  
	} else {
	  $fpdf->cell(220, 10, "Nenhum Professor Informado!", 0, 1, "L", 0);
	}
}
$fpdf->Output ();
?>