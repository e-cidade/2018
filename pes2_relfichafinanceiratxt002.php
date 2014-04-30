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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhfuncao_classe.php");
include("classes/db_rhpescargo_classe.php");
include("classes/db_rhrubricas_classe.php");
$clrhpessoal = new cl_rhpessoal;
$clrhfuncao = new cl_rhfuncao;
$clrhpescargo = new cl_rhpescargo;
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$mesi = str_pad($mesi, 2, "0", STR_PAD_LEFT);
$arr_verifica_ano_mes_regist = Array();
$arr_mostrar = Array();

if($orde == "a"){
  $orderby  = " z01_nome ";
  $orderby2 = "nomefc,anousu,mesusu,rubric";
}else{
  $orderby  = " rh01_regist ";
  $orderby2 = "regist,anousu,mesusu,rubric";
}

$db_where_rubricas = "";
$virg_das_rubricas = "";
$impressao_rubricas = false;
if(trim($rubricas_selecionadas_text) != ""){
  $impressao_rubricas = true;
  $arr_das_rubricas = split(",",$rubricas_selecionadas_text);
  for($i=0; $i<count($arr_das_rubricas); $i++){
    $db_where_rubricas.= $virg_das_rubricas."'".$arr_das_rubricas[$i]."'";
    $virg_das_rubricas = ",";
  }
  $db_where_rubricas = " and rh27_rubric in (".$db_where_rubricas.") ";
}else if(isset($rh27_rubric1) && isset($rh27_rubric2) && (trim($rh27_rubric1) != "" || trim($rh27_rubric2) != "")){
  $impressao_rubricas = true;
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

$db_where_matriculas = "
                        (
                              rh02_anousu = ".db_anofolha()." and rh02_mesusu = ".db_mesfolha()."
                        )
                       ";

$sp_where_matriculas = "";
$virg_das_matriculas = "";
if(trim($matriculas_selecionadas_text) != ""){
  $arr_das_matriculas = split(",",$matriculas_selecionadas_text);
  for($i=0; $i<count($arr_das_matriculas); $i++){
    $sp_where_matriculas.= $virg_das_matriculas.$arr_das_matriculas[$i];
    $virg_das_matriculas = ",";
  }
  $db_where_matriculas .= " and rh01_regist in (".$sp_where_matriculas.") ";
}else if(isset($rh01_regist1) && isset($rh01_regist2) && (trim($rh01_regist1) != "" || trim($rh01_regist2) != "")){
  if(trim($rh01_regist1) != "" && trim($rh01_regist2) != ""){
    $db_where_matriculas .= " and rh01_regist between ".$rh01_regist1." and ".$rh01_regist2;
  }else{
    if(trim($rh01_regist1) != ""){
      $db_where_matriculas .= " and rh01_regist >= ".$rh01_regist1;
    }else{
      $db_where_matriculas .= " and rh01_regist <= ".$rh01_regist2;
    }
  }
}

// SQL para criar tabela temporria de auxlio
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
                                                         basesr float8,
                                                         tabela varchar(15)
                                                        )
                              ";

// SQL para ndices
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

//echo "<BR> ".$clrhpessoal->sql_query_cgm(null,"distinct rh01_regist as regist, z01_numcgm as numcgm, z01_nome as nomefc, r70_codigo as lotaca, r70_descr as dlotac, rh01_funcao as funcao",$orderby,$db_where_matriculas);
//exit;
$result_dados_pessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm(null,"distinct rh02_seqpes,rh01_regist as regist, z01_numcgm as numcgm, z01_nome as nomefc, r70_codigo as lotaca, r70_descr as dlotac, rh01_funcao as funcao",$orderby,$db_where_matriculas));

if($clrhpessoal->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
}

$testa = false;
for($i=0; $i < $clrhpessoal->numrows; $i++){
  db_fieldsmemory($result_dados_pessoal, $i);

  $result_cargo = $clrhpescargo->sql_record($clrhpescargo->sql_query_descr($rh02_seqpes,"rh20_cargo as funcao, rh04_descr as dfunca",null,"rh20_seqpes = $rh02_seqpes and rh04_instit = ".db_getsession("DB_instit")));
  if($clrhpescargo->numrows > 0){
    db_fieldsmemory($result_cargo,0);
  }else{
    $result_funcao = $clrhfuncao->sql_record($clrhfuncao->sql_query_file($funcao,db_getsession("DB_instit"),"rh37_descr as dfunca"));
    if($clrhfuncao->numrows > 0){
      db_fieldsmemory($result_funcao,0);
    }
  }
//  echo "<BR> dfunca --> $dfunca";
////  testar esta opcao depois: (r14_anousu = 2004 and r14_mesusu >= 3) or (r14_anousu > 2004 and r14_anousu < 2006 ) or (r14_anousu = 2006 and r14_mesusu <= 2)
  $sql_dados_gerfs   = "
                        select r14_anousu as anousu,
                               r14_mesusu as mesusu,
                               r14_rubric as rubric,
                               rh27_descr as drubri,
                               coalesce(sum(provento),0)  as proven,
                               coalesce(sum(desconto),0)  as descon,
                               coalesce(sum(basesr),0)    as basesr,
                               coalesce(sum(r14_quant),0) as quanti,
                               tabela
                        from

                        (
                         select r14_anousu,
                                r14_mesusu,
                                r14_regist,
                                r14_rubric,
                                rh27_descr,
                                case when r14_pd = 1 and r14_rubric < 'R950' then r14_valor else 0 end as provento,
                                case when r14_pd = 2 and r14_rubric < 'R950' then r14_valor else 0 end as desconto,
                                case when r14_rubric >= 'R950' then r14_valor else 0 end as basesr,
                                r14_quant,
                                'gerfsal' as tabela
                         from gerfsal
                              inner join rhrubricas on rh27_rubric = r14_rubric
															                     and rh27_instit = r14_instit
                         where fc_anousu_mesusu(r14_anousu, r14_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf)
                           and r14_regist = ".$regist."
                              ".$db_where_rubricas."

                         union

                         select r22_anousu,
                                r22_mesusu,
                                r22_regist,
                                r22_rubric,
                                rh27_descr,
                                case when r22_pd = 1 and r22_rubric < 'R950' then r22_valor else 0 end as provento,
                                case when r22_pd = 2 and r22_rubric < 'R950' then r22_valor else 0 end as desconto,
                                case when r22_rubric >= 'R950' then r22_valor else 0 end as basesr,
                                r22_quant,
                                'gerfadi' as tabela
                         from gerfadi
                              inner join rhrubricas on rh27_rubric = r22_rubric
															                     and rh27_instit = r22_instit
                         where fc_anousu_mesusu(r22_anousu, r22_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf)
                           and r22_regist = ".$regist."
                              ".$db_where_rubricas."

                         union

                         select r48_anousu,
                                r48_mesusu,
                                r48_regist,
                                r48_rubric,
                                rh27_descr,
                                case when r48_pd = 1 and r48_rubric < 'R950' then r48_valor else 0 end as provento,
                                case when r48_pd = 2 and r48_rubric < 'R950' then r48_valor else 0 end as desconto,
                                case when r48_rubric >= 'R950' then r48_valor else 0 end as basesr,
                                r48_quant,
                                'gerfcom' as tabela
                         from gerfcom
                              inner join rhrubricas on rh27_rubric = r48_rubric
															                     and rh27_instit = r48_instit
                         where fc_anousu_mesusu(r48_anousu, r48_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf)
                           and r48_regist = ".$regist."
                              ".$db_where_rubricas."

                         union

                         select r20_anousu,
                                r20_mesusu,
                                r20_regist,
                                r20_rubric,
                                rh27_descr,
                                case when r20_pd = 1 and r20_rubric < 'R950' then r20_valor else 0 end as provento,
                                case when r20_pd = 2 and r20_rubric < 'R950' then r20_valor else 0 end as desconto,
                                case when r20_rubric >= 'R950' then r20_valor else 0 end as basesr,
                                r20_quant,
                                'gerfres' as tabela
                         from gerfres
                              inner join rhrubricas on rh27_rubric = r20_rubric
															                     and rh27_instit = r20_instit
                         where fc_anousu_mesusu(r20_anousu, r20_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf)
                           and r20_regist = ".$regist."
                              ".$db_where_rubricas."

                         union

                         select r35_anousu,
                                r35_mesusu,
                                r35_regist,
                                r35_rubric,
                                rh27_descr,
                                case when r35_pd = 1 and r35_rubric < 'R950' then r35_valor else 0 end as provento,
                                case when r35_pd = 2 and r35_rubric < 'R950' then r35_valor else 0 end as desconto,
                                case when r35_rubric >= 'R950' then r35_valor else 0 end as basesr,
                                r35_quant,
                                'gerfs13' as tabela
                         from gerfs13
                              inner join rhrubricas on rh27_rubric = r35_rubric
															                     and rh27_instit = r35_instit
                         where fc_anousu_mesusu(r35_anousu, r35_mesusu) between fc_anousu_mesusu($anoi, $mesi) and fc_anousu_mesusu($anof, $mesf)
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
//echo "<BR> $sql_dados_gerfs";
//exit;
  $result_dados_gerfs = pg_exec($sql_dados_gerfs);
  $numrows_dados_gerfs = pg_numrows($result_dados_gerfs);
  $mes_anterior = "";
  $ano_anterior = "";
  $contaIarray  = 1;
  $contamesano  = 0;

  for($ii=0; $ii<$numrows_dados_gerfs; $ii++){
    db_fieldsmemory($result_dados_gerfs,$ii);

    if(!in_array($regist."_".$anousu."_".$mesusu,$arr_verifica_ano_mes_regist) && $impressao_rubricas == false){
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
                                                                basesr,
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
                                                                $basesr,
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

$arq = 'tmp/rel_ficha_financ_'.time().'.txt';
$arquivo = fopen($arq,'w');

$sql_rubr = "select distinct rubric as rubric1,drubri as drubri1 from work_ficha_financ order by rubric";
$result_rubr = pg_exec($sql_rubr);
$numrows_rubr = pg_numrows($result_rubr);
//db_criatabela($result_rubr);exit;
$sql_work = "select * from work_ficha_financ order by $orderby2";
$result_work = pg_exec($sql_work);
$numrows_work = pg_numrows($result_work);
//db_criatabela($result_work);exit;
$primatric = "";
$aRubrica = array();
for($x=0;$x<$numrows_work;$x++){
 db_fieldsmemory($result_work,$x);
 if($primatric!=$regist){
  $primatric = $regist;
  $prianomes = "";
  if($x>0){
   $sep1 = "";
   for($y=0;$y<$numrows_rubr;$y++){
    db_fieldsmemory($result_rubr,$y);
    if(array_key_exists ($rubric1,$aRubrica)){
     fputs($arquivo,$sep1.$aRubrica[$rubric1]);
    }else{
     fputs($arquivo,$sep1."          ");
    }
    $sep1 = "|";
   }
   $aRubrica = array();
  }
  fputs($arquivo,"\r\n\r\n".$regist." - ".$nomefc."\r\n");
  fputs($arquivo,"Rubrica| ");
  $sep2 = "";
  for($y=0;$y<$numrows_rubr;$y++){
   db_fieldsmemory($result_rubr,$y);
   fputs($arquivo,$sep2.str_pad($rubric1,10," ",STR_PAD_LEFT));
   $sep2 = "|";
  }
  fputs($arquivo,"\r\n");
 }
 if($prianomes!=$anousu.$mesusu){
  if($x>0){
   $sep3 = "";
   for($y=0;$y<$numrows_rubr;$y++){
    db_fieldsmemory($result_rubr,$y);
    if(array_key_exists ($rubric1,$aRubrica)){
     fputs($arquivo,$sep3.$aRubrica[$rubric1]);
    }else{
     fputs($arquivo,$sep3."          ");
    }
    $sep3 = "|";
   }
   $aRubrica = array();
  }
  fputs($arquivo,"\r\n".str_pad($mesusu,2,"0",STR_PAD_LEFT)."/".$anousu."| ");
  $prianomes = $anousu.$mesusu;
 }
 for($y=0;$y<$numrows_rubr;$y++){
  db_fieldsmemory($result_rubr,$y);
  if($proven!=0){
   $valor = $proven;
  }
  if($descon!=0){
   $valor = $descon;
  }
  if($basesr!=0){
   $valor = $basesr;
  }
  if($rubric==$rubric1){
   $aRubrica[$rubric] = str_pad(number_format($valor,2,",","."),10," ",STR_PAD_LEFT);
   break;
  }
 }
}
$sep4 = "";
for($y=0;$y<$numrows_rubr;$y++){
 db_fieldsmemory($result_rubr,$y);
 if(array_key_exists ($rubric1,$aRubrica)){
  fputs($arquivo,$sep4.$aRubrica[$rubric1]);
 }else{
  fputs($arquivo,$sep4."          ");
 }
 $sep4 = "|";
}
fputs($arquivo,"\r\n\r\n");
for($y=0;$y<$numrows_rubr;$y++){
 db_fieldsmemory($result_rubr,$y);
 fputs($arquivo,$rubric1." - ".$drubri1."\r\n");
}
fclose($arquivo);
?>
<center><a href="<?=$arq?>">Arquivo Gerado</a></center>