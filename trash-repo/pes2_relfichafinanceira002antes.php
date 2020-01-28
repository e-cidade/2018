<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhfuncao_classe.php");
include("classes/db_rhrubricas_classe.php");
$clrhpessoal = new cl_rhpessoal;
$clrhfuncao = new cl_rhfuncao;
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$mesi = str_pad($mesi, 2, "0", STR_PAD_LEFT);
$arr_verifica_ano_mes_regist = Array();
$arr_mostrar = Array();

if($orde == "a"){
  $orderby  = " rh01_regist ";
  $orderby2 = "regist";
}else{
  $orderby  = " z01_nome ";
  $orderby2 = "nomefc";
}

$db_where_rubricas = "";
$virg_das_rubricas = "";
if(trim($rubricas_selecionadas_text) != ""){
  $arr_das_rubricas = split(",",$rubricas_selecionadas_text);
  for($i=0; $i<count($arr_das_rubricas); $i++){
    $db_where_rubricas.= $virg_das_rubricas."'".$arr_das_rubricas[$i]."'";
    $virg_das_rubricas = ",";
  }
  $db_where_rubricas = " and rh27_rubric in (".$db_where_rubricas.") ";
}else if(isset($rh27_rubric1) && isset($rh27_rubric2) && (trim($rh27_rubric1) != "" || trim($rh27_rubric2) != "")){
  if(trim($rh27_rubric1) != "" && trim($rh27_rubric2) != ""){
  	$db_where_rubricas = " and rh27_rubric between '".$rh27_rubric1."' and '".$rh27_rubric2."' ";
  }else{
  	if(trim($rh27_rubric1) != ""){
      $db_where_rubricas = " and rh27_rubric >= '".$rh27_rubric1."' ";
  	}else{
      $db_where_rubricas = " and rh27_rubric <= '".$rh27_rubric2."' ";
  	}
  }
}
$db_where_matriculas = "";
$virg_das_matriculas = "";
if(trim($matriculas_selecionadas_text) != ""){
  $arr_das_matriculas = split(",",$matriculas_selecionadas_text);
  for($i=0; $i<count($arr_das_matriculas); $i++){
    $db_where_matriculas.= $virg_das_matriculas.$arr_das_matriculas[$i];
    $virg_das_matriculas = ",";
  }
  $db_where_matriculas = " rh01_regist in (".$db_where_matriculas.") ";
}else if(isset($rh01_regist1) && isset($rh01_regist2) && (trim($rh01_regist1) != "" || trim($rh01_regist2) != "")){
  if(trim($rh01_regist1) != "" && trim($rh01_regist2) != ""){
  	$db_where_matriculas = " rh01_regist between ".$rh01_regist1." and ".$rh01_regist2;
  }else{
  	if(trim($rh01_regist1) != ""){
      $db_where_matriculas = " rh01_regist >= ".$rh01_regist1;
  	}else{
      $db_where_matriculas = " rh01_regist <= ".$rh01_regist2;
  	}
  }
}

/*
// Conta a quantidade de anos que deve mostrar
$conta_anos = 0;
for($i=$anoi; $i<= $anof; $i++){
  $conta_anos++;
}

// Conta a quantidade de meses que deverá mostrar por matrícula
$anoii = $anoi;
$anofi = $anof;
$mesii = $mesi - 1;
$mesfi = 12 - $mesf;
$conta_mess = 0;
$conta_mess = $conta_anos * 12;
$conta_mess-= $mesii + $mesfi;

// Busca os meses de um ano concatenando ANO com MES ficando no formado aaaamm - 200501 (Janeiro de 2005)
$arr_mesano = Array();
$mes_no_arr = $mesi;
for($i=0; $i < $conta_mess; $i++){
  $indice_do_array = $anoii.str_pad($mes_no_arr, 2, "0", STR_PAD_LEFT);
  $arr_mesano[$indice_do_array] = $indice_do_array;
  $mes_no_arr++;
  if($mes_no_arr % 13 == 0){
  	$anoii ++;
  	$mes_no_arr = 1;
  }
}

// Monta WHERE de para selects *Selects executados para cada 3 meses*
$arr_mes_ano = Array();
$contamesano = 0;
$contaIarray = 0;
reset($arr_mesano);
for($i=0; $i<count($arr_mesano); $i++){
  $contamesano ++;
  if($contamesano == 1){
    $arr_mes_ano[$contaIarray] = "  where anousu||lpad(mesusu,2,0) = '".$arr_mesano[key($arr_mesano)]."'  ";
  }else if($contamesano == 2){
    $arr_mes_ano[$contaIarray].= "_ where anousu||lpad(mesusu,2,0) = '".$arr_mesano[key($arr_mesano)]."'  ";
  }else if($contamesano == 3){
    $arr_mes_ano[$contaIarray].= "_  where anousu||lpad(mesusu,2,0) = '".$arr_mesano[key($arr_mesano)]."'  ";
    $contamesano = 0;
    $contaIarray ++;
  }
  next($arr_mesano);
}
*/

// SQL para criar tabela temporária de auxílio
$sql_create_temporary_table = "
                               create temp table 
                                      work_ficha_financ (
                                                         anousu int4,
                                                         mesusu int4,
                                                         regist int4,
                                                         numcgm int4,
                                                         nomefc varchar(80),
                                                         lotaca int4,
                                                         dlotac varchar(80),
                                                         funcao int4,
                                                         dfunca varchar(80),
                                                         rubric varchar(4),
                                                         drubri varchar(80),
                                                         quanti float8,
                                                         proven float8,
                                                         descon float8,
                                                         tabela varchar(15)
                                                        )
                              ";

// SQL para índices
$sql_create_indexes_temp = "
                            create index work_anousu
                                   on work_ficha_financ(anousu);
                            create index work_mesusu
                                   on work_ficha_financ(mesusu);
                            create index work_regist
                                   on work_ficha_financ(regist);
                           ";

$result_create_temporary_table = pg_exec($sql_create_temporary_table);
$result_create_indexes_temp    = pg_exec($sql_create_indexes_temp);

//die($clrhpessoal->sql_query_cgm(null,"distinct rh01_regist as regist, z01_numcgm as numcgm, z01_nome as nomefc, r70_codigo as lotaca, r70_descr as dlotac, rh01_funcao as funcao",$orderby." limit 5",$db_where_matriculas));
$result_dados_pessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm(null,"distinct rh01_regist as regist, z01_numcgm as numcgm, z01_nome as nomefc, r70_codigo as lotaca, r70_descr as dlotac, rh01_funcao as funcao",$orderby,$db_where_matriculas));

if($clrhpessoal->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$testa = false;
for($i=0; $i < $clrhpessoal->numrows; $i++){
  db_fieldsmemory($result_dados_pessoal, $i);

  $result_funcao = $clrhfuncao->sql_record($clrhfuncao->sql_query_file($funcao,"rh37_descr as dfunca"));
  if($clrhfuncao->numrows > 0){
  	db_fieldsmemory($result_funcao,0);
  }

  $sql_dados_gerfs   = "
                        select r14_anousu as anousu,
                               r14_mesusu as mesusu,
                               r14_rubric as rubric,
                               rh27_descr as drubri,
                               sum(provento)  as proven,
                               sum(desconto)  as descon,
                               sum(r14_quant) as quanti,
                               tabela
                        from

                        (
                         select r14_anousu,
                                r14_mesusu,
                                r14_regist,
                                r14_rubric,
                                rh27_descr,
                                case when r14_pd = 1 then r14_valor else 0 end as provento,
                                case when r14_pd = 2 then r14_valor else 0 end as desconto,
                                r14_quant,
                                'gerfsal' as tabela
                         from gerfsal
                              inner join rhrubricas on rh27_rubric::char(4) = r14_rubric
                         where r14_anousu||lpad(r14_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."'
                           and r14_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r22_anousu,
                                r22_mesusu,
                                r22_regist,
                                r22_rubric,
                                rh27_descr,
                                case when r22_pd = 1 then r22_valor else 0 end as provento,
                                case when r22_pd = 2 then r22_valor else 0 end as desconto,
                                r22_quant,
                                'gerfadi' as tabela
                         from gerfadi
                              inner join rhrubricas on rh27_rubric::char(4) = r22_rubric
                         where r22_anousu||lpad(r22_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r22_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r48_anousu,
                                r48_mesusu,
                                r48_regist,
                                r48_rubric,
                                rh27_descr,
                                case when r48_pd = 1 then r48_valor else 0 end as provento,
                                case when r48_pd = 2 then r48_valor else 0 end as desconto,
                                r48_quant,
                                'gerfcom' as tabela
                         from gerfcom
                              inner join rhrubricas on rh27_rubric::char(4) = r48_rubric
                         where r48_anousu||lpad(r48_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r48_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r31_anousu,
                                r31_mesusu,
                                r31_regist,
                                r31_rubric,
                                rh27_descr,
                                case when r31_pd = 1 then r31_valor else 0 end as provento,
                                case when r31_pd = 2 then r31_valor else 0 end as desconto,
                                r31_quant,
                                'gerffer' as tabela
                         from gerffer
                              inner join rhrubricas on rh27_rubric::char(4) = r31_rubric
                         where r31_anousu||lpad(r31_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r31_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r20_anousu,
                                r20_mesusu,
                                r20_regist,
                                r20_rubric,
                                rh27_descr,
                                case when r20_pd = 1 then r20_valor else 0 end as provento,
                                case when r20_pd = 2 then r20_valor else 0 end as desconto,
                                r20_quant,
                                'gerfres' as tabela
                         from gerfres
                              inner join rhrubricas on rh27_rubric::char(4) = r20_rubric
                         where r20_anousu||lpad(r20_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r20_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r53_anousu,
                                r53_mesusu,
                                r53_regist,
                                r53_rubric,
                                rh27_descr,
                                case when r53_pd = 1 then r53_valor else 0 end as provento,
                                case when r53_pd = 2 then r53_valor else 0 end as desconto,
                                r53_quant,
                                'gerffx' as tabela
                         from gerffx
                              inner join rhrubricas on rh27_rubric::char(4) = r53_rubric
                         where r53_anousu||lpad(r53_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r53_regist = ".$regist."
                           ".$db_where_rubricas."

                         union

                         select r35_anousu,
                                r35_mesusu,
                                r35_regist,
                                r35_rubric,
                                rh27_descr,
                                case when r35_pd = 1 then r35_valor else 0 end as provento,
                                case when r35_pd = 2 then r35_valor else 0 end as desconto,
                                r35_quant,
                                'gerfs13' as tabela
                         from gerfs13
                              inner join rhrubricas on rh27_rubric::char(4) = r35_rubric
                         where r35_anousu||lpad(r35_mesusu,2,0) between '".$anoi.$mesi."' and '".$anof.$mesf."' 
                           and r35_regist = ".$regist."
                           ".$db_where_rubricas."
                        ) as x
                        group by r14_anousu,
                                 r14_mesusu,
                                 r14_regist,
                                 r14_rubric,
                                 rh27_descr,
                                 tabela
                        order by r14_regist,
                                 r14_anousu,
                                 r14_mesusu,
                                 r14_rubric
                       ";

  $result_dados_gerfs = pg_exec($sql_dados_gerfs);
  $numrows_dados_gerfs = pg_numrows($result_dados_gerfs);
  $mes_anterior = "";
  $ano_anterior = "";
  $contaIarray  = 1;
  $contamesano  = 0;

  for($ii=0; $ii<$numrows_dados_gerfs; $ii++){
  	db_fieldsmemory($result_dados_gerfs,$ii);

    if(!in_array($regist."_".$anousu."_".$mesusu,$arr_verifica_ano_mes_regist)){
      $arr_verifica_ano_mes_regist[$regist."_".$anousu."_".$mesusu] = $regist."_".$anousu."_".$mesusu;

      // Monta WHERE de para selects
      $contamesano ++;

      if($contamesano == 1){
        $arr_mostrar[$regist][$contaIarray] = $anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT);
        $arr_mes_ano[$regist][$contaIarray] = "  where anousu||lpad(mesusu,2,0) = '".$anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT)."' ";
      }else if($contamesano == 2){
        $arr_mostrar[$regist][$contaIarray].= "_".$anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT);
        $arr_mes_ano[$regist][$contaIarray].= "_ where anousu||lpad(mesusu,2,0) = '".$anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT)."' ";
      }else if($contamesano == 3){
        $arr_mostrar[$regist][$contaIarray].= "_".$anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT);
        $arr_mes_ano[$regist][$contaIarray].= "_ where anousu||lpad(mesusu,2,0) = '".$anousu.str_pad($mesusu, 2, "0", STR_PAD_LEFT)."' ";
        $contamesano = 0;
        $contaIarray ++;
      }
    }

    $testa = true;
    $sql_insert_na_temporary_table = "
                                      insert into 
                                             work_ficha_financ (
                                                                anousu,
                                                                mesusu,
                                                                regist,
                                                                numcgm,
                                                                nomefc,
                                                                lotaca,
                                                                dlotac,
                                                                funcao,
                                                                dfunca,
                                                                rubric,
                                                                drubri,
                                                                quanti,
                                                                proven,
                                                                descon,
                                                                tabela
                                                               )
                                                                values
                                                               (
                                                                $anousu,
                                                                $mesusu,
                                                                $regist,
                                                                $numcgm,
                                                                '".$nomefc."',
                                                                $lotaca,
                                                                '".$dlotac."',
                                                                $funcao,
                                                                '".$dfunca."',
                                                                '".$rubric."',
                                                                '".$drubri."',
                                                                $quanti,
                                                                $proven,
                                                                $descon,
                                                                '".$tabela."'
                                                               );
                                     ";
    $result_insert_na_temporary_table = pg_exec($sql_insert_na_temporary_table);
    if($result_insert_na_temporary_table == false){
      db_redireciona("db_erros.php?fechar=true&db_erro=Erro ao inserir na tabela auxiliar 'work_ficha_financ'. Contate o suporte.");
  	  break;
    }
  }
}

if($testa == false){
  db_redireciona("db_erros.php?fechar=true&db_erro=Sem dados para gerar relatório.");
  break;
}

if($tipo == "G"){
  $HEAD5 = "geral";
}else if($tipo == "L"){
  $HEAD5 = "lotação";
}else if($tipo == "M"){
  $HEAD5 = "matrícula";
}else if($tipo == "F"){
  $HEAD5 = "função";
}else if($tipo == "C"){
  $HEAD5 = "CGM";
}

if($orde == "a"){
  $HEAD7 = "alfabética";
}else{
  $HEAD7 = "numérica";
}

$head3 = "RELATÓRIO DE FICHA FINANCEIRA";
$head5 = "Resumo ".$HEAD5;
$head6 = "Período entre ".$anoi."/".$mesi." e ".$anof."/".$mesf;
$head7 = "Ordem ".$HEAD7;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$p = 1;
$alt = 4;

// Função para imprimir os dados do funcionário
function imprimefuncionario($newpagina, $registro, $nomeregi, $lotaccod, $lotacrec, $funcacod, $funcarec){

  global $alt;
  global $pdf;

  if($newpagina==true){

    $pdf->addpage();

  }else{

    $pdf->ln(2);

  }

  $pdf->setfont('arial','b',7);
  $pdf->cell(17,$alt,"Funcionário:",0,0,"R",0);
  $pdf->cell(17,$alt,$registro. " - " .db_CalculaDV($registro),0,0,"C",0);
  $pdf->cell( 0,$alt,$nomeregi,0,1,"L",0);

  $pdf->cell(17,$alt,"Função........:",0,0,"R",0);
  $pdf->cell(17,$alt,$funcacod,0,0,"C",0);
  $pdf->cell(61,$alt,$funcarec,0,0,"L",0);
  $pdf->cell(17,$alt,"Lotação.......:",0,0,"R",0);
  $pdf->cell(17,$alt,$lotaccod,0,0,"C",0);
  $pdf->cell(61,$alt,$funcarec,0,1,"L",0);

  return true;
}

function imprimecabecalho($anos_e_meses){

  global $alt;
  global $pdf;

  $arr_anos_e_meses = split("_",$anos_e_meses);

  $muda_linha1 = 0;
  $muda_linha2 = 0;
  if(count($arr_anos_e_meses) == 1){
    $muda_linha1 = 1;
  }else if(count($arr_anos_e_meses) == 2){
    $muda_linha2 = 1;
  }

  for($i=0; $i<count($arr_anos_e_meses); $i++){
  	$ano_mes = $arr_anos_e_meses[$i];
  	if($i==0){
  	  $ano1 = substr($ano_mes,0,4);
  	  $mes1 = substr($ano_mes,4,2);
  	}else if($i == 1){
  	  $ano2 = substr($ano_mes,0,4);
  	  $mes2 = substr($ano_mes,4,2);
  	}else if($i == 2){
  	  $ano3 = substr($ano_mes,0,4);
  	  $mes3 = substr($ano_mes,4,2);
  	}
  }

  $pdf->setfont('arial','b',7);
  if(isset($ano1)){
    $pdf->cell(60,3,                     "","LT" ,0,"R",0);
    $pdf->cell(40,3,$ano1."/".strtoupper(db_mes($mes1)),"TR",$muda_linha1,"R",0);
  }
  if(isset($ano2)){
    $pdf->cell(40,3,$ano2."/".strtoupper(db_mes($mes2)),"TR",$muda_linha2,"R",0);
  }
  if(isset($ano3)){
    $pdf->cell(40,3,$ano3."/".strtoupper(db_mes($mes3)),"TR",1,"R",0);
  }

  if(isset($ano1)){
    $pdf->cell(60,3,"RUBRICA","LB",0,"L",0);
    $pdf->cell(13.33,3,"QTD" ,"B" ,0,"C",0);
    $pdf->cell(13.33,3,"PROV","B" ,0,"C",0);
    $pdf->cell(13.33,3,"DESC","BR",$muda_linha1,"C",0);
  }

  if(isset($ano2)){
    $pdf->cell(13.33,3,"QTD" ,"B" ,0,"C",0);
    $pdf->cell(13.33,3,"PROV","B" ,0,"C",0);
    $pdf->cell(13.33,3,"DESC","BR",$muda_linha2,"C",0);
  }

  if(isset($ano3)){
    $pdf->cell(13.33,3,"QTD" ,"B" ,0,"C",0);
    $pdf->cell(13.33,3,"PROV","B" ,0,"C",0);
    $pdf->cell(13.33,3,"DESC","BR",1,"C",0);
  }
}

function imprimedadosrubrica($anos_e_meses,$crub,$drub,$q1,$q2,$q3,$p1,$p2,$p3,$d1,$d2,$d3){

  global $alt;
  global $pdf;

  $arr_anos_e_meses = split("_",$anos_e_meses);

  $muda_linha1 = 0;
  $muda_linha2 = 0;
  if(count($arr_anos_e_meses) == 1){
    $muda_linha1 = 1;
  }else if(count($arr_anos_e_meses) == 2){
    $muda_linha2 = 1;
  }

  $pdf->setfont('arial','',6);
  $pdf->cell(60,3,$crub." - ".$drub,"LTB" ,0,"L",0);
  $pdf->cell(13.33,3,$q1,"TB" ,0,"R",0);
  $pdf->cell(13.33,3,$p1,"TB" ,0,"R",0);
  $pdf->cell(13.33,3,$d1,"TBR",$muda_linha1,"R",0);

  if(count($arr_anos_e_meses) >= 2){
    $pdf->cell(13.33,3,$q2,"TB" ,0,"R",0);
    $pdf->cell(13.33,3,$p2,"TB" ,0,"R",0);
    $pdf->cell(13.33,3,$d2,"TBR",$muda_linha2,"R",0);
  }

  if(count($arr_anos_e_meses) == 3){
    $pdf->cell(13.33,3,$q3,"TB" ,0,"R",0);
    $pdf->cell(13.33,3,$p3,"TB" ,0,"R",0);
    $pdf->cell(13.33,3,$d3,"TBR",1,"R",0);
  }

}

// For para buscar dados dos funcionários
for($i=0; $i < $clrhpessoal->numrows; $i++){
  db_fieldsmemory($result_dados_pessoal, $i);

  $imprimir_cabecalho_registros = true;

  // For para buscar os WHERES concatenados por "_"
  if(!isset($arr_mes_ano[$regist])){
  	continue;
  }
  for($ii=1; $ii<=count($arr_mes_ano[$regist]); $ii++){
  	if(isset($arr_mes_ano[$regist][$ii]) && trim($arr_mes_ano[$regist][$ii]) == "" || !isset($arr_mes_ano[$regist][$ii])){
  	  continue;
  	}

    $anos_meses = $arr_mostrar[$regist][$ii];
    $arr_wheres = split("_",$arr_mes_ano[$regist][$ii]);
    $sql_dados_work_ficha_financ = "";

    // For para montar os SQL's
    $sql_dados_work_ficha_financ = "
                                    select *
                                    from (

                                          select 
                                                 distinct on (rubric1) rubric1,
                                                 drubri1,
                                                 sum(anousu1) as anousu1,
                                                 sum(mesusu1) as mesusu1,
                                                 sum(anousu2) as anousu2,
                                                 sum(mesusu2) as mesusu2,
                                                 sum(anousu3) as anousu3,
                                                 sum(mesusu3) as mesusu3,
                                                 sum(qmes1) as qmes1,
                                                 sum(pmes1) as pmes1,
                                                 sum(dmes1) as dmes1,
                                                 sum(qmes2) as qmes2,
                                                 sum(pmes2) as pmes2,
                                                 sum(dmes2) as dmes2,
                                                 sum(qmes3) as qmes3,
                                                 sum(pmes3) as pmes3,
                                                 sum(dmes3) as dmes3
                                          from (
                                   ";
    for($iii=0; $iii<count($arr_wheres); $iii++){

      if($iii != 0){
        $sql_dados_work_ficha_financ.= "   union  ";
      }

      // Veririca em qual posição deve colocar ZEROS
      $qpd23 = 1;
      if($iii != 0 && ($iii+1) % 2 == 0){
      	$qpd23 = 2;
      }else if($iii != 0 && ($iii+1) % 3 == 0){
      	$qpd23 = 3;
      }

      $sql_dados_work_ficha_financ.= "
                                      select 
                                             rubric as rubric1,
                                             drubri as drubri1,
                                     ";
      if($qpd23 == 1){
      $sql_dados_work_ficha_financ.= "
                                             anousu as anousu1,
                                             mesusu as mesusu1,
                                             0 as anousu2,
                                             0 as mesusu2,
                                             0 as anousu3,
                                             0 as mesusu3,
                                             quanti as qmes1,
                                             proven as pmes1,
                                             descon as dmes1,
                                             0 as qmes2,
                                             0 as pmes2,
                                             0 as dmes2,
                                             0 as qmes3,
                                             0 as pmes3,
                                             0 as dmes3
                                     ";
      }else if($qpd23 == 2){
      $sql_dados_work_ficha_financ.= "
                                             0 as anousu1,
                                             0 as mesusu1,
                                             anousu as anousu2,
                                             mesusu as mesusu2,
                                             0 as anousu3,
                                             0 as mesusu3,
                                             0 as qmes1,
                                             0 as pmes1,
                                             0 as dmes1,
                                             quanti as qmes2,
                                             proven as pmes2,
                                             descon as dmes2,
                                             0 as qmes3,
                                             0 as pmes3,
                                             0 as dmes3
                                     ";
      }else if($qpd23 == 3){
      $sql_dados_work_ficha_financ.= "
                                             0 as anousu1,
                                             0 as mesusu1,
                                             0 as anousu2,
                                             0 as mesusu2,
                                             anousu as anousu3,
                                             mesusu as mesusu3,
                                             0 as qmes1,
                                             0 as pmes1,
                                             0 as dmes1,
                                             0 as qmes2,
                                             0 as pmes2,
                                             0 as dmes2,
                                             quanti as qmes3,
                                             proven as pmes3,
                                             descon as dmes3
                                     ";
      }
      $sql_dados_work_ficha_financ.= "
                                      from work_ficha_financ
                                     "
                                     .$arr_wheres[$iii].
                                     "
                                      and regist = ".$regist."
                                     ";
    }
    $sql_dados_work_ficha_financ.= "
                                          ) as dados_rubricas
                                          group by rubric1, drubri1

                                    ) as dados_vindos_para_order_by
                                    order by rubric1

                                   ";
    // echo "<BR><BR>$sql_dados_work_ficha_financ";
    $result_dados_work_ficha_financ = pg_exec($sql_dados_work_ficha_financ);
    $numrows_dados_work_ficha_financ = pg_numrows($result_dados_work_ficha_financ);
    $imprimir_cabecalho_rubricas  = true;
    $conta_mes_ano = 0;

    for($iiii=0; $iiii<$numrows_dados_work_ficha_financ; $iiii++){
      db_fieldsmemory($result_dados_work_ficha_financ,$iiii);

      $novapagina = false;
      if($troca == 1 || $pdf->gety() > $pdf->h - 30){
      	$imprimir_cabecalho_registros = true;
        $novapagina = true;
        $troca = 0;
      }

      if($imprimir_cabecalho_registros == true){
      	$imprimir_cabecalho_registros = false;
      	$imprimir_cabecalho_rubricas  = true;
        imprimefuncionario($novapagina, $regist, $nomefc, $lotaca, $dlotac, $funcao, $dfunca);
      }

      if($imprimir_cabecalho_rubricas == true){
      	$imprimir_cabecalho_rubricas  = false;
        imprimecabecalho($anos_meses);
      }

      imprimedadosrubrica($anos_meses,$rubric1,$drubri1,$qmes1,$qmes2,$qmes3,$pmes1,$pmes2,$pmes3,$dmes1,$dmes2,$dmes3);

    }
  }

}


$pdf->Output();
?>