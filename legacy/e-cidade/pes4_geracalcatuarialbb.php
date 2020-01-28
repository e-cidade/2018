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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_selecao_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table>
<tr height=25><td>&nbsp;</td></tr>
</table>
<?
db_postmemory($HTTP_POST_VARS);
db_criatermometro('termometro','Concluido...','blue',1);
flush();
$wh = '';
$clselecao = null;

$xseparador = '';
$yseparador = '';
if($separador == 'S'){
  $xseparador = "||'#'";
  $yseparador = '#';
}

db_sel_instit();

//echo "nomeinst --> $nomeinst   db21_tipoinstit --> $db21_tipoinstit ";exit;

if ($_POST["r44_selec"] != ''){

 $clselecao = new cl_selecao;
 $rsselec   =  $clselecao->sql_record($clselecao->sql_query($r44_selec));
 db_fieldsmemory($rsselec,0);
 $wh  =  "and $r44_where";

}
if ($_POST["vinculo"] == "A"){
  
  $arq = 'tmp/calc_ativosbb.txt';
  $arquivo = fopen($arq,'w');  

  pg_query("drop sequence layout_ati_seq");
  pg_query("create sequence layout_ati_seq");

  $sql = "
    select rh01_regist as matricula,
         lpad(nextval('layout_ati_seq'),5,0)
       $xseparador
       ||lpad(rh01_regist,9,0)
       $xseparador
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       $xseparador
			 ||lpad(date_part('month',rh01_nasc),2,0)||date_part('year',rh01_nasc)
       $xseparador
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),9,0)
       $xseparador
			 ||lpad(date_part('month',rh01_admiss),2,0)||date_part('year',rh01_admiss)
       as todo
       
  from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $ano 
			                      and rh02_mesusu = $mes 
                            and rh02_instit   = ".db_getsession('DB_instit')."
     inner join rhlota       on r70_codigo = rh02_lota
                            and r70_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg 
                            and rh30_instit = rh02_instit
     inner join (select r14_regist,
                        sum(case when r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $ano 
		             and r14_mesusu = $mes
                 and r14_instit = ".db_getsession('DB_instit')."
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
  where rh30_vinculo = 'A'
  and rh30_regime = 1
	$wh
";
  
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
    
		db_atutermometro($x,$num,'termometro');
	  flush();

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
    
    $sql2 = "select lpad(count(*),2,0) as soma_filhos
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

    $meses_inss     = "000";
		$anos_trabalho  = "1";
		$meses_anterior = "000";

    if($db21_tipoinstit == 2){
      $tipoinstit = 2;
    }else{
      $tipoinstit = 1;
    }
		
  fputs($arquivo,pg_result($result,$x,'todo').$yseparador.
                                  $meses_inss.$yseparador.
                                      $dtconj.$yseparador.
                                      $cacula.$yseparador.
                                   $numfilhos.$yseparador.
                               $anos_trabalho.$yseparador.
                              $meses_anterior.$yseparador.
                                  $tipoinstit.$yseparador.
                                          '2'."\r\n");
  }
  fclose($arquivo);

} else if ($_POST["vinculo"] == "I"){


  $arq = 'tmp/calc_inativosbb.txt';


  $arquivo = fopen($arq,'w');  

  pg_query("drop sequence layout_ina_seq");
  pg_query("create sequence layout_ina_seq");

$sql = "
  select rh01_regist as matricula,
       lpad(nextval('layout_ina_seq'),5,0)
       $xseparador
       ||lpad(rh01_regist,9,0)
       $xseparador
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       $xseparador
			 ||lpad(date_part('month',rh01_nasc),2,0)||date_part('year',rh01_nasc)
       $xseparador
			 ||lpad(date_part('day',rh01_admiss),2,0)||lpad(date_part('month',rh01_admiss),2,0)||date_part('year',rh01_admiss)
       $xseparador
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),8,0)
       as todo
       
  from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $ano 
			                      and rh02_mesusu = $mes 
                            and rh02_instit   = ".db_getsession('DB_instit')."
     inner join rhlota       on r70_codigo = rh02_lota
                            and r70_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg 
                            and rh30_instit = rh02_instit
     inner join (select r14_regist,
                        sum(case when r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $ano 
		             and r14_mesusu = $mes
                 and r14_instit = ".db_getsession('DB_instit')."
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
  where rh30_vinculo = 'I' 
	$wh
";
  
  //echo $sql;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
    
		db_atutermometro($x,$num,'termometro');
	  flush();
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

    $meses_inss     = "000";
		$tipo_apos  = "1";
		$meses_anterior = "000";
		$valor_prov_inicial = "00000000";
		$tempo_contrib = "000";
		$tempo_total_contrib = "000";
		
  fputs($arquivo,pg_result($result,$x,'todo').$yseparador.
                                      $dtconj.$yseparador.
                                   $numfilhos.$yseparador.
                                      $cacula.$yseparador.
                                   $tipo_apos.$yseparador.
                              $meses_anterior.$yseparador.
                          $valor_prov_inicial.$yseparador.
                             $tempo_total_contrib."\r\n");
  }
  fclose($arquivo);

}else if ($_POST["vinculo"] == "P"){

  $arq = 'tmp/calc_pensbb.txt';
  $arquivo = fopen($arq,'w');  

  pg_query("drop sequence layout_pen_seq");
  pg_query("create sequence layout_pen_seq");

$sql = "
  select rh01_regist as matricula,
       lpad(nextval('layout_pen_seq'),5,0)
       $xseparador
       ||lpad(rh01_regist,9,0)
       $xseparador
       ||lpad(trim(translate(to_char(round(prov,2),'999999999.99'),'.','')),8,0)
       $xseparador
			 ||date_part('year',rh01_nasc)
       $xseparador
			 ||'0000'
       $xseparador
			 ||'0'
       $xseparador
			 ||'00000000'
       $xseparador
			 ||'000'
       $xseparador
			 ||'000'
       $xseparador
			 ||case when rh01_sexo = 'M' 
			      then 1 
						else 2 
			   end 
       $xseparador
			 ||lpad(date_part('month',rh01_admiss),2,0)||date_part('year',rh01_admiss)
       as todo
       
  from rhpessoal
     inner join rhpessoalmov on rh02_regist = rh01_regist 
                            and rh02_anousu = $ano 
			                      and rh02_mesusu = $mes 
                            and rh02_instit   = ".db_getsession('DB_instit')."
     inner join rhlota       on r70_codigo = rh02_lota
                            and r70_instit = rh02_instit
     inner join rhregime     on rh30_codreg = rh02_codreg 
                            and rh30_instit = rh02_instit
     inner join (select r14_regist,
                        sum(case when r14_pd = 1 then r14_valor else 0 end) as prov,
			                  sum(case when r14_pd = 2 then r14_valor else 0 end) as desco,
            			      sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base 
                 from gerfsal 
		             where r14_anousu = $ano 
		             and r14_mesusu = $mes
                 and r14_instit = ".db_getsession('DB_instit')."
		             group by r14_regist ) as sal on r14_regist = rh01_regist 
  where rh30_vinculo = 'P'
	$wh
";
  
//  echo $sql;
  $result = pg_query($sql);
  $num = pg_numrows($result);
  for($x = 0;$x < pg_numrows($result);$x++){
    
  	db_atutermometro($x,$num,'termometro');
	  flush();
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
    
    $sql2 = "select lpad(count(*),2,0) as soma_filhos
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
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>
<form name='form1' id='form1'></form>
<script>js_montarlista("<?=$arq?>#Arquivo gerado em: <?=$arq?>",'form1');
function js_manda(){
		location.href='pes4_geracalcaturial001.php?banco=001';
}
setTimeout(js_manda,300);
</script>
</body>
</html>