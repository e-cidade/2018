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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("classes/db_selecao_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clselecao = new cl_selecao();
//db_postmemory($HTTP_SERVER_VARS,2);exit;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<center>
<? 
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Geracao calc_ativos.txt');
db_criatermometro('calculo_folha1','Concluido...','blue',1,'Efetuando Geracao calc_inativos.txt');
db_criatermometro('calculo_folha2','Concluido...','blue',1,'Efetuando Geracao calc_pens.txt');
?>

</center>
</body>
<?
$where = " ";
if(trim($selecao) != ""){
  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao," r44_descr, r44_where ",db_getsession("DB_instit")));
  if($clselecao->numrows > 0){
    db_fieldsmemory($result_selecao, 0);
    $where = " and ".$r44_where;
    $head8 = "SELEÇÃO: ".$selecao." - ".$r44_descr;
  }
}

$db_erro = false;

if($banco == 1){
$erro_msg = calcatua_bb($anofolha,$mesfolha,$where);
}else{
$erro_msg = calcatua_cef($anofolha,$mesfolha,$where);
}

if(empty($erro_msg)){
  echo "
  <script>
    parent.js_detectaarquivo('/tmp/calc_ativos.txt','/tmp/calc_inativos.txt','/tmp/calc_pens.txt');
  </script>
  ";
}else{
  echo "
  <script>
    parent.js_erro('$erro_msg');
  </script>
  ";
}
//echo "<BR> antes do fim db_fim_transacao()";
//flush();
db_redireciona("pes2_calcatua001.php");


function calcatua_bb($anofolha,$mesfolha,$where){

  $arq = '/tmp/calc_ativos.txt';
  $arquivo = fopen($arq,'w');  

pg_query("drop sequence layout_ati_seq");
pg_query("create sequence layout_ati_seq");

$sql = " select rh01_regist as matricula,
       lpad(nextval('layout_ati_seq')::text,5,'0')
       ||'#'
       ||lpad(rh01_regist::text,9,'0')
       ||'#'
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       ||'#'
			 ||lpad(date_part('month',rh01_nasc)::text,2,'0')||date_part('year',rh01_nasc)::text
       ||'#'
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),9,0)
       ||'#'
			 ||lpad(date_part('month',rh01_admiss)::text,2,'0')||date_part('year',rh01_admiss)::text
       as todo
       
from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha
                            and rh01_instit = ".db_getsession('DB_instit')."
     inner join rhlota       on r70_codigo  = rh02_lota
                            and r70_instit  = rh02_instit
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
                            and rh30_instit = rh02_instit
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $anofolha 
		             and r14_mesusu = $mesfolha
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'A'
  and rh30_regime = 1
  $where ";
  
//  echo $sql;exit;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
//    echo 'Total de : '.$num.' / '.$x."\r";
   db_atutermometro($x,$num,'calculo_folha',1);
    
    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '0000';
      $temconj = 'N'; 
    }
    
    ////  verifica numero de filhos
    
    $sql2 = "select lpad(count(*)::text,2,'0') as soma_filhos
             from rhdepend
             where rh31_gparen = 'F' 
	             and rh31_regist = $matric 
	          ";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $numfilhos = pg_result($res2,0,'soma_filhos');
    }else{
      $numfilhos = '00';
    }
    
    ////  verifica ano de nasc do filho cacula
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $cacula = pg_result($res3,0,'nasc');
    }else{
      $cacula = '0000';
    }

    $mesfolhaes_inss     = "000";
		$anofolhas_trabalho  = "1";
		$mesfolhaes_anterior = "000";
		
  fputs($arquivo,pg_result($result,$x,'todo')."#".$mesfolhaes_inss."#".$dtconj."#".$cacula."#".$numfilhos."#".$anofolhas_trabalho."#".$mesfolhaes_anterior."\r\n");
  }
  fclose($arquivo);


//echo "\n\n"."inativos "."\n\n";

  $arq1 = '/tmp/calc_inativos.txt';

//echo "arquivo : ".$arq."\n\n";

  $arquivo = fopen($arq1,'w');  

pg_query("drop sequence layout_ina_seq");
pg_query("create sequence layout_ina_seq");
//echo "entrou no select"."\n\n";

$sql = "select rh01_regist as matricula,
       lpad(nextval('layout_pen_seq')::text,5,'0')
       ||'#'
       ||lpad(rh01_regist::text,9,'0')
       ||'#'
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       ||'#'
			 ||lpad(date_part('month',rh01_nasc)::text,2,'0')||date_part('year',rh01_nasc)::text
       ||'#'
			 ||lpad(date_part('day',rh01_admiss)::text,2,'0')||lpad(date_part('month',rh01_admiss)::text,2,'0')||date_part('year',rh01_admiss)::text
       ||'#'
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),9,'0')
       as todo
       
from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha 
                            and rh02_instit = ".db_getsession('DB_instit')."
     inner join rhlota       on r70_codigo  = rh02_lota 
                            and r70_instit  = rh02_instit
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg
                            and rh30_instit = rh02_instit
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $anofolha 
		             and r14_mesusu = $mesfolha
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'I' 
  $where ";
  
//  echo $sql;
  $result = pg_query($sql);
//  echo "gerou o result"."\n\n";
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
//    echo 'Total de : '.$num.' / '.$x."\r";
    
   db_atutermometro($x,$num,'calculo_folha1',1);

    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '0000';
      $temconj = 'N'; 
    }
    
    ////  verifica numero de filhos
    
    $sql2 = "select count(*) as soma_filhos
             from rhdepend
             where rh31_gparen = 'F' 
	             and rh31_regist = $matric 
	          ";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $numfilhos = pg_result($res2,0,'soma_filhos');
    }else{
      $numfilhos = '0';
    }
    
    ////  verifica ano de nasc do filho cacula
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $cacula = pg_result($res3,0,'nasc');
    }else{
      $cacula = '0000';
    }

    $mesfolhaes_inss     = "000";
		$tipo_apos  = "1";
		$mesfolhaes_anterior = "000";
		$valor_prov_inicial = "00000000";
		$tempo_contrib = "000";
		$tempo_total_contrib = "000";
		
  fputs($arquivo,pg_result($result,$x,'todo')."#".$dtconj."#".$numfilhos."#".$cacula."#".$tipo_apos."#".$mesfolhaes_anterior."#".$valor_prov_inicial."#".$tempo_contrib."#".$tempo_total_contrib."\r\n");
  }
  fclose($arquivo);




//echo "pensionistas"."\n\n";

  $arq2 = '/tmp/calc_pens.txt';

//echo "arquivo : ".$arq."\n\n";

  $arquivo = fopen($arq2,'w');  

pg_query("drop sequence layout_pen_seq");
pg_query("create sequence layout_pen_seq");

//echo "entrou no select"."\n\n";

$sql = " select rh01_regist as matricula,
       lpad(nextval('layout_pen_seq')::text,5,'0')
       ||'#'
       ||lpad(rh01_regist::text,9,'0')
       ||'#'
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),9,0)
       ||'#'
			 ||date_part('year',rh01_nasc)::text
       ||'#'
			 ||'0000'
       ||'#'
			 ||'0'
       ||'#'
			 ||'00000000'
       ||'#'
			 ||'000'
       ||'#'
			 ||'000'
       ||'#'
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       ||'#'
			 ||lpad(date_part('month',rh01_admiss)::text,2,'0')||date_part('year',rh01_admiss)::text
       as todo
       
from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha 
     inner join rhlota       on r70_codigo = rh02_lota 
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime on rh30_codreg = rh02_codreg
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $anofolha 
		             and r14_mesusu = $mesfolha
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'P'
  $where ";
  
//  echo $sql;
  $result = pg_query($sql);
//  echo "gerou o result"."\n\n";
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
//    echo 'Total de : '.$num.' / '.$x."\r";
    
   db_atutermometro($x,$num,'calculo_folha2',1);

    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '0000';
      $temconj = 'N'; 
    }
    
    ////  verifica numero de filhos
    
    $sql2 = "select lpad(count(*)::text,2,'0') as soma_filhos
             from rhdepend
             where rh31_gparen = 'F' 
	             and rh31_regist = $matric 
	          ";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $numfilhos = pg_result($res2,0,'soma_filhos');
    }else{
      $numfilhos = '00';
    }
    
    ////  verifica ano de nasc do filho cacula
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $cacula = pg_result($res3,0,'nasc');
    }else{
      $cacula = '0000';
    }

  fputs($arquivo,pg_result($result,$x,'todo')."\r\n");
  }
  fclose($arquivo);

}



function calcatua_cef($mesfolha,$anofolha,$where){

  $arq = '/tmp/calc_ativos.txt';
  $arquivo = fopen($arq,'w'); 

$sql = "
select rh01_regist as matricula,
       trim(substr(z01_nome,1,40))
       ||'#'
       ||trim(substr(z01_cgccpf,1,11))
       ||'#'
       ||'PREF. MUN. DE BAGE  '
       ||'#'
       ||trim(to_char(rh01_regist,'999999'))
       ||'#'
       ||trim(to_char(rh30_regime,'9'))
       ||'#'
       ||case when rh30_regime = 1 then 'S' else 'N' end 
       ||'#'
       ||rh01_sexo
       ||'#'
       ||to_char(rh01_nasc,'DD/MM/YYYY') 
       ||'#'
       ||to_char(rh01_admiss,'DD/MM/YYYY')
       ||'#'
       ||to_char(rh01_admiss,'DD/MM/YYYY')
       ||'#'
       ||trim(translate(to_char(round(base,2),'99999999,99'),',',''))
       ||'#'
       ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
       ||'#'
       ||case when r70_codigo in (802,804,805) then '2' else '4' end
       ||'#'
       ||'0' as todo
       
from rhpessoal 
     inner join cgm          on rh01_numcgm = z01_numcgm 
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha 
     inner join rhlota       on r70_codigo = rh02_lota 
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime on rh30_codreg = rh02_codreg
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
                   			sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		 where r14_anousu = $anofolha 
		   and r14_mesusu = $mesfolha
		   group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'A' 
  and rh30_regime = 1
  $where
";

//  echo $sql;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
//    echo 'Total de : '.$num.' / '.$x."\r";

   db_atutermometro($x,$num,'calculo_folha',1);
    
    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais
    
    $sql2 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend = 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $dtespec = pg_result($res2,0,'nasc');
    }else{
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend <> 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $dtnespec = pg_result($res3,0,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($result,$x,'todo')."#".$temconj."#".$dtconj."#".$dtespec."#".$dtnespec."#".chr(13)."\r\n");
  }
  fclose($arquivo);


  $arq1 = '/tmp/calc_inativos.txt';

  $arquivo = fopen($arq1,'w');  
$sql = "
select rh01_regist as matricula,
       trim(substr(z01_nome,1,40))
       ||'#'
       ||trim(substr(z01_cgccpf,1,11))
       ||'#'
       ||trim(to_char(rh01_regist,'999999'))
       ||'#'
       ||rh01_sexo
       ||'#'
       ||to_char(rh01_nasc,'DD/MM/YYYY') 
       ||'#'
       ||trim(translate(to_char(round(prov,2),'99999999,99'),',',''))
       ||'#'
       ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
       ||'#'
       ||'2'
       ||'#'
       ||to_char(rh01_admiss,'DD/MM/YYYY') as todo
       
from rhpessoal 
     inner join cgm          on rh01_numcgm = z01_numcgm 
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha 
     inner join rhlota       on r70_codigo = rh02_lota 
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime on rh30_codreg = rh02_codreg
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
			sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		 where r14_anousu = $anofolha
		   and r14_mesusu = $mesfolha
		   group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'I'
  $where
";
  
//  echo $sql;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
    //echo 'Total de : '.$num.' / '.$x."\r";
    
    db_atutermometro($x,$num,'calculo_folha1',1);

    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais
    
    $sql2 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend = 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $dtespec = pg_result($res2,0,'nasc');
    }else{
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend <> 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $dtnespec = pg_result($res3,0,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($result,$x,'todo')."#".$temconj."#".$dtconj."#".$dtespec."#".$dtnespec."#".chr(13)."\r\n");
  }
  fclose($arquivo);




//echo "\n\n"."pensionistas"."\n\n";

  $arq2 = '/tmp/calc_pens.txt';

  $arquivo = fopen($arq2,'w');  
$sql = "
select rh01_regist as matricula,
       case when c.z01_nome is null then 'NAO CADASTRADO' else trim(substr(c.z01_nome,1,40)) end
       ||'#'
       ||lpad(cgm.z01_cgccpf,11,0)
       ||'#'
       ||trim(to_char(rh01_regist,'999999'))
       ||'#'
       ||trim(translate(to_char(round(prov,2),'99999999,99'),',',''))
       ||'#'
       ||trim(translate(to_char(round(prov-desco,2),'99999999,99'),',',''))
       ||'#'
       ||'2'
       ||'#'
       ||rh01_sexo as todo
       
from rhpessoal
     inner join pessoal p    on p.r01_anousu = $anofolha 
                            and p.r01_mesusu = $mesfolha
			    and p.r01_regist = rh01_regist
     left join  pessoal q    on q.r01_regist = p.r01_origp
                            and q.r01_anousu = $anofolha
			                      and q.r01_mesusu = $mesfolha
     left join  cgm c        on c.z01_numcgm = q.r01_numcgm
     inner join cgm          on rh01_numcgm = cgm.z01_numcgm 
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $anofolha 
			                      and rh02_mesusu = $mesfolha 
     inner join rhlota       on r70_codigo = rh02_lota 
     inner join rhfuncao     on rh37_funcao = rh01_funcao
                            and rh37_instit = rh02_instit
     inner join rhregime on rh30_codreg = rh02_codreg
     inner join (select r14_regist,
                        sum(case when r14_pd != 3 and r14_pd = 1 then r14_valor else 0 end) as prov,
			sum(case when r14_pd != 3 and r14_pd = 2 then r14_valor else 0 end) as desco,
			sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		 where r14_anousu = $anofolha 
		   and r14_mesusu = $mesfolha
		   group by r14_regist ) as sal on r14_regist = rh01_regist 
where rh30_vinculo = 'P' 
  $where
";
  
//  echo $sql;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
  //  echo 'Total de : '.$num.' / '.$x."\r";
    
    db_atutermometro($x,$num,'calculo_folha2',1);

    $matric = pg_result($result,$x,'matricula');
    
    ////  verifica se tem conjuge
    
    $sql1 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
	     where rh31_gparen = 'C' 
	       and rh31_regist = $matric 
	     limit 1";
				 
    $res1 = pg_query($sql1);
    
    if(pg_numrows($res1) > 0){
      $dtconj  = pg_result($res1,0,'nasc');
      $temconj = 'S'; 
    }else{
      $dtconj = '';
      $temconj = 'N'; 
    }
    
    ////  verifica se tem filhos especiais
    
    $sql2 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend = 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res2 = pg_query($sql2);
    
    if(pg_numrows($res2) > 0){
      $dtespec = pg_result($res2,0,'nasc');
    }else{
      $dtespec = '';
    }
    
    ////  verifica se tem filhos nao especiais
    
    $sql3 = "select rh31_regist,
                    to_char(rh31_dtnasc,'DD/MM/YYYY') as nasc
             from rhdepend
             where rh31_gparen = 'F' 
	       and rh31_depend <> 'S'
	       and rh31_regist = $matric 
	       order by rh31_dtnasc desc
	     limit 1";
				 
    $res3 = pg_query($sql3);
    
    if(pg_numrows($res3) > 0){
      $dtnespec = pg_result($res3,0,'nasc');
    }else{
      $dtnespec = '';
    }
  fputs($arquivo,pg_result($result,$x,'todo')."#"."#".$dtespec."#".$dtnespec."#".chr(13)."\r\n");
  }
  fclose($arquivo);

}

?>