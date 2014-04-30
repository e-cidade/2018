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
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
//db_postmemory($HTTP_POST_VARS,2);
db_postmemory($HTTP_SERVER_VARS);
//echo " previdencia  --> $prev";
global $cfpess,$subpes,$db21_codcli, $prev,$sal_dec;

$subpes = $xano.'/'.$xmes;

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_"));

db_inicio_transacao();

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
//db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Geracao da Rais');
?>

</center>

<?
?>
</body>
<?


global $db_config;
db_selectmax("db_config","select numero,
                                 ender,
                                 cgc,
                                 nomeinst,
                                 bairro,
                                 cep,
                                 munic,
                                 uf,
                                 telef,
                                 email,
                                 db21_codcli ,
                                 cgc
                             from db_config
                             where codigo = ".db_getsession("DB_instit"));

global $vinculo,$prev,$sal_dec,$d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$d08_numero;

$d08_ender  = $db_config[0]["ender"];
$d08_cgc    = $db_config[0]["cgc"];
$d08_nome   = $db_config[0]["nomeinst"];
$d08_bairro = $db_config[0]["bairro"];
$d08_cep    = $db_config[0]["cep"];
$d08_munic  = $db_config[0]["munic"];
$d08_uf     = $db_config[0]["uf"];
$d08_telef  = $db_config[0]["telef"];
$d08_email  = $db_config[0]["email"];
$d08_numero = $db_config[0]["numero"];

$db21_codcli = $db_config[0]["db21_codcli"];


$db_erro = false;
$sqlerro = false;
$nomearq = "/tmp/layout_ideal.txt";
$nomepdf = "/tmp/layout_ideal.pdf";


emite_layoutideal($nomearq,$xmes,$xano,$sal_dec);

//exit;
if($sqlerro == false){
  echo "
  <script>
    parent.js_detectaarquivo('$nomearq','$nomepdf');
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
db_fim_transacao();
//flush();
db_redireciona("pes2_layoutideal001.php");


function emite_layoutideal($nomearq,$xmes,$xano,$sal_dec){
  
global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$d08_numero;
global $vinculo,$prev,$rubrica_familia, $ls, $lg, $sf,$rubrica_gestante,$perc_patronal,$sal_dec,$work, $work1, $Iwork, $pdf;

$ano = db_str($xano,4);
$mes = db_str($xmes,2,0,"0");

if( $sal_dec  == "S" ){
   $arquivo  = "gerfsal" ;
   $sigla    = "r14_" ;
   $arquivoc = "gerfcom" ;
   $siglac   = "r48_" ;
   $arquivor = "gerfres" ;
   $siglar   = "r20_" ;
   $rub_base = "('R985','R986')";
   if($prev == 1){
     $rub_desc        = "('R901', 'R902')";
     $rub_desc_ferias = "('R903')";
   }elseif($prev == 2){
     $rub_desc        = "('R904', 'R905')";
     $rub_desc_ferias = "('R906')";
   }elseif($prev == 3){
     $rub_desc        = "('R907', 'R908')";
     $rub_desc_ferias = "('R909')";
   }elseif($prev == 4){
     $rub_desc        = "('R910', 'R911')";
     $rub_desc_ferias = "('R912')";
   }
   $rub_base_ferias = "R987";
}else{
   $arquivo  = "gerfs13" ;
   $sigla    = "r35_" ;
   $arquivor = "gerfres" ;
   $siglar   = "r20_" ;
   $rub_base = "('R985','R986')" ;
   if($prev == 1){
     $rub_desc        = "('R901', 'R902')";
   }elseif($prev == 2){
     $rub_desc        = "('R904', 'R905')";
   }elseif($prev == 3){
     $rub_desc        = "('R907', 'R908')";
   }elseif($prev == 4){
     $rub_desc        = "('R910', 'R911')";
   }
}

global $cfpess,$subpes, $db21_codcli ;

$subpes = $xano.'/'.$xmes;


// previdencia 3 + 2 da posicao dentro do arquivo = 5;
$sql_prev  = "select * from inssirf 
              where r33_anousu = ".db_sqlformat( $xano )."
                and r33_mesusu = ".db_sqlformat( $xmes )."
                and r33_codtab = $prev + 2 
              limit 1";
//echo '   VINCULO --> '.$vinculo;exit;
global $r33_ppatro, $r33_rubsau, $r33_rubmat;
$res_prev = pg_query($sql_prev);

db_fieldsmemory($res_prev,0);
$perc_patronal = $r33_ppatro;
$rubrica_saude = "('')";
if(trim($r33_rubsau) != '' && $ls == 's' ){
 $rubrica_saude = "('$r33_rubsau')";
}

$rubrica_gestante = "('')";
if(trim($r33_rubmat) != '' && $lg == 's' ){
 $rubrica_gestante = "('$r33_rubmat')";
}

$rubrica_familia = "('')";
if($sf == 's' ){
  $rubrica_familia = "('R918')";
}

//echo "<br><br> rubrica_familia --> $rubrica_familia   rubrica_saude --> $rubrica_saude   rubrica_gestante --> $rubrica_gestante"; exit;;
///$rubrica_familia, $rubrica_gestante,$perc_patronal,
if($vinculo == 'i'){
  $where_vin = " and rh30_vinculo = 'I'";
}elseif($vinculo == 'p'){
  $where_vin = " and rh30_vinculo = 'P'";
}elseif($vinculo == 'ip'){
  $where_vin = " and rh30_vinculo <> 'A'";
}else{
  $where_vin = '';
}


$varp = '0';

//echo "  sal_dec ---> $sal_dec   perc_patronal --> $perc_patronal   rubrica_gestante --> $rubrica_gestante   rubrica_gestante --> $rubrica_gestante <br><br>";exit;

$sql  = "select * from (
         select ".$varp."||lpad(rh01_regist,9,'0') as regist,
                 rh01_regist as r01_regist,
                 substr(z01_nome,1,40) as nome,
                 to_char(rh01_admiss,'ddmmyyyy') as admissao,
                 to_char(rh05_recis,'ddmmyyyy') as rescisao,
                 r70_estrut as lotacao,
                 case rh30_vinculo 
                      when 'A' then '01'
                      when 'I' then '02'
                      when 'P' then '03'
                      else 'ER' 
                 end as situacao,
                 substr(z01_cgccpf,1,11) as cpf,
                 rpad(trim(substr(z01_ender,1,36)),36,' ') as z01_ender,
                 rpad(trim( substr(z01_bairro,1,14)),14,' ') as z01_bairro,
                 rpad(trim(substr(z01_munic,1,20)),20,' ') as z01_munic,
                 case when trim(z01_uf) = '' or z01_uf is null then '$d08_uf' else z01_uf end as z01_uf,
                 to_char(rh01_admiss,'ddmmyyyy') as apos,
                 rh01_admiss as r01_admiss, 
                 '0' as cod_apos,";

if( $sal_dec == "S" ){
   // salario + complementar;
   $sql .= " coalesce(lpad(translate(ltrim(to_char((round(coalesce(prev.".$sigla."valor,0)+coalesce(prevc.".$siglac."valor,0)+coalesce(prevrf.".$siglar."valor,0)+coalesce(prevrs.".$siglar."valor,0),2)),'99999999.99')),',.',''),14,0),'00000000000000') as base_prev,
             coalesce(lpad(translate(ltrim(to_char((round(coalesce(descon.".$sigla."valor,0),2)+round(coalesce(desconc.".$siglac."valor,0),2)+round(coalesce(desconrf.".$siglar."valor,0),2)+round(coalesce(desconrs.".$siglar."valor,0),2)),'99999999.99')),',.',''),14,0),'00000000000000') as desc_prev,
             coalesce(lpad(translate(ltrim(to_char( round(coalesce(familia.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salfamilia,
             coalesce(lpad(translate(ltrim(to_char( round(coalesce(gestante.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salgestante,
             coalesce(lpad(translate(ltrim(to_char( round(coalesce(saude.".$sigla."valor,0),2),'99999999.99')),',.',''),8,0),'00000000') as salsaude," ;

}else{
   // 13.salario;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round(coalesce(prev.".$sigla."valor,0),2)+round(coalesce(prevr.".$siglar."valor,0),2),'99999999.99')),',.',''),14,0),'00000000000000') as base_prev,
             coalesce(lpad(translate(ltrim(to_char(round(coalesce(descon.".$sigla."valor,0),2)+round(coalesce(desconr.".$siglar."valor,0),2),'99999999.99')),',.',''),14,0),'00000000000000') as desc_prev," ;
}

if( $sal_dec == "S" ){
   // acrescenta a complementar;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconc.".$siglac."quant,2),'99999999.99')),',.',''),5,0),'00000') as quant_desc_prevc," ;
   // acrescenta a rescisao;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconrs.".$siglar."quant,2),'99999999.99')),',.',''),5,0),'00000')  as quant_desc_prevr,";
}else{
   // acrescenta a rescisao;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round(desconr.".$siglar."quant,2),'99999999.99')),',.',''),5,0),'00000')  as quant_desc_prevr,";
}

$sql .= " coalesce(lpad(translate(ltrim(to_char(round(descon.".$sigla."quant,2),'99999999.99')),',.',''),5,0),'00000') as quant_desc_prev, ";

if( $sal_dec == "S" ){
   // salario + complementar;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round(round(coalesce(prev.".$sigla."valor,0)+coalesce(prevc.".$siglac."valor,0)+coalesce(prevrf.".$siglar."valor,0)+coalesce(prevrs.".$siglar."valor,0),2)/100*".db_str($perc_patronal,2).",2),'999999.99')),',.',''),14,0),'00000000000000') as cont_ent," ;
}else{
   // 13.salario;
   $sql .= " coalesce(lpad(translate(ltrim(to_char(round( (coalesce(prev.".$sigla."valor,0) + coalesce(prevr.".$siglar."valor,0))  /100*".db_str($perc_patronal,2).",2),'999999.99')),',.',''),14,0),'00000000000000') as cont_ent," ;
}

$sql .= "        lpad(case when trim(z01_cep) = '' or z01_cep is null or to_number(z01_cep,'99999999') = 0 then '$d08_cep' else z01_cep end,8,0) as z01_cep  ," ;
$sql .= "        to_char(rh01_nasc,'ddmmyyyy') as nascimento, " ;

if( $sal_dec == "S" ){
   $sql .= "        coalesce(lpad(translate(ltrim(to_char(round(   
                            (select coalesce( sum(".$sigla."valor ),0)   
                                 from ".$arquivo."
                                where ".$sigla."anousu=".db_str($xano,4)." and ". 
                                        $sigla."mesusu=".db_str($xmes,2)." and ". 
                                        $sigla."instit=".db_getsession("DB_instit")." and ".
                                        $sigla."pd = 1 and ".
                                        $sigla."regist = rh01_regist   
                            )
                            +  
                            (select coalesce( sum(".$siglac."valor),0 )    
                                 from ".$arquivoc."
                                where ".$siglac."anousu=".db_str($xano,4)." and ". 
                                        $siglac."mesusu=".db_str($xmes,2)." and ". 
                                        $siglac."instit=".db_getsession("DB_instit")." and ". 
                                        $siglac."pd = 1 and ". 
                                        $siglac."regist = rh01_regist  
                            ) 
                      ,2),'99999999.99')),',.',''),14,0),'00000000000000') as proventos";

}else{

   $sql .= "        coalesce(lpad(translate(ltrim(to_char(round(   
                           (select coalesce( sum(".$sigla."valor ),0)    
                                from ".$arquivo."
                               where ".$sigla."anousu=".db_str($xano,4)." and ". 
                                       $sigla."mesusu=".db_str($xmes,2)." and ".
                                       $sigla."instit=".db_getsession("DB_instit")." and ".
                                       $sigla."pd = 1 and ".
                                       $sigla."regist = rh01_regist 
                           ) 
                    +
                           (select coalesce( sum(".$siglar."valor),0 ) 
                                from ".$arquivor."
                               where ".$siglar."rubric between '4000' and '5999' and ". 
                                       $siglar."anousu=".db_str($xano,4)." and ". 
                                       $siglar."mesusu=".db_str($xmes,2)." and ". 
                                       $siglar."instit=".db_getsession("DB_instit")." and ". 
                                       $siglar."pd = 1 and ". 
                                       $siglar."regist = rh01_regist   
                           )    
                     ,2),'99999999.99')),',.',''),14,0),'00000000000000') as proventos";

}

$sql .= " from   rhpessoal
                 inner join cgm          on rh01_numcgm = z01_numcgm    
                 inner join rhpessoalmov on rh02_anousu = $xano
                                        and rh02_mesusu = $xmes
                                        and rh02_regist = rh01_regist
                                        and rh02_instit = ".db_getsession("DB_instit")."
                 inner join rhlota       on r70_codigo  = rh02_lota
                                        and r70_instit  = rh02_instit
                 left join rhpesrescisao on rh05_seqpes = rh02_seqpes
                 left join rhregime   on rh30_codreg    = rhpessoalmov.rh02_codreg
                                     and rh30_instit    = ".db_getsession("DB_instit")."
                                     $where_vin
                 left outer join (select ".$sigla."regist,   
                                         sum(".$sigla."valor) as ".$sigla."valor    
                                  from ".$arquivo."  
                                  where ".$sigla."rubric in ".$rub_base." and 
                                        ".$sigla."anousu=".db_str($xano,4)." and 
                                        ".$sigla."mesusu=".db_str($xmes,2)." and 
                                        ".$sigla."instit=".$sigla."instit
				  group by ".$sigla."regist) 
                                  as prev
                                  on rh01_regist = prev.".$sigla."regist 
                 left outer join (select ".$sigla."regist,
                                         sum(".$sigla."valor) as ".$sigla."valor,
                                         sum(".$sigla."quant) as ".$sigla."quant 
                                  from ".$arquivo ."
                                  where ".$sigla."rubric in ".$rub_desc." and 
                                        ".$sigla."anousu=".db_str($xano,4)." and 
                                        ".$sigla."mesusu=".db_str($xmes,2)." and 
                                        ".$sigla."instit=".$sigla."instit
				  group by ".$sigla."regist) 
                                  as descon 
                                  on rh01_regist = descon.".$sigla."regist" ;

if( $sal_dec == "S" ){
   $sql .= "        left outer join (select ".$siglac."regist,
                                        sum(".$siglac."valor) as ".$siglac."valor    
                                      from ".$arquivoc."
                                      where ".$siglac."rubric in ".$rub_base." and ". 
                                             $siglac."anousu=".db_str($xano,4)." and ". 
                                             $siglac."mesusu=".db_str($xmes,2)." and ". 
                                             $siglac."instit=".db_getsession("DB_instit")."
				      group by ".$siglac."regist) 
                                      as prevc 
                                      on rh01_regist=prevc.".$siglac."regist  
                     left outer join (select ".$siglac."regist,
		                             sum(".$siglac."valor) as ".$siglac."valor,
                                             sum(".$siglac."quant) as ".$siglac."quant
                                      from ".$arquivoc."
                                      where ".$siglac."rubric in ".$rub_desc." and ". 
                                              $siglac."anousu=".db_str($xano,4)." and ". 
                                              $siglac."mesusu=".db_str($xmes,2)." and ". 
                                              $siglac."instit=".db_getsession("DB_instit")."
				      group by ".$siglac."regist)   
                                      as desconc    
                                      on rh01_regist=desconc.".$siglac."regist    
                     left outer join (select ".$siglar."regist, 
                                             sum(".$siglar."valor) as ".$siglar."valor    
                                      from ".$arquivor."
                                      where ".$siglar."rubric = '".$rub_base_ferias."' and ". 
                                             $siglar."anousu=".db_str($xano,4)." and ". 
                                             $siglar."mesusu=".db_str($xmes,2)." and ". 
                                             $siglar."instit=".db_getsession("DB_instit")."
				      group by ".$siglar."regist) 
                                      as prevrf 
                                      on rh01_regist=prevrf.".$siglar."regist  
                     left outer join (select ".$siglar."regist, 
                                             sum(".$siglar."valor) as ".$siglar."valor , 
                                             sum(".$siglar."quant) as ".$siglar."quant
                                      from ".$arquivor."
                                      where ".$siglar."rubric in ".$rub_desc_ferias." and ". 
                                              $siglar."anousu=".db_str($xano,4)." and ". 
                                              $siglar."mesusu=".db_str($xmes,2)." and ". 
                                              $siglar."instit=".db_getsession("DB_instit")."
				      group by ".$siglar."regist) 
                                      as desconrf 
                                      on rh01_regist=desconrf.".$siglar."regist   
                     left outer join (select ".$siglar."regist,
                                             sum(".$siglar."valor) as ".$siglar."valor    
                                      from ".$arquivor."
                                      where ".$siglar."rubric in ".$rub_base." and ". 
                                             $siglar."anousu=".db_str($xano,4)." and ". 
                                             $siglar."mesusu=".db_str($xmes,2)." and ". 
                                             $siglar."instit=".db_getsession("DB_instit")."
				      group by ".$siglar."regist)   
                                      as prevrs    
                                      on rh01_regist=prevrs.".$siglar."regist     
                     left outer join (select ".$siglar."regist, 
                                             sum(".$siglar."valor) as ".$siglar."valor, 
                                             sum(".$siglar."quant) as ".$siglar."quant     
                                      from ".$arquivor."
                                      where ".$siglar."rubric in ".$rub_desc." and ". 
                                              $siglar."anousu=".db_str($xano,4)." and ". 
                                              $siglar."mesusu=".db_str($xmes,2)." and ". 
                                              $siglar."instit=".db_getsession("DB_instit")."
				      group by ".$siglar."regist)   
                                      as desconrs    
                                      on rh01_regist=desconrs.".$siglar."regist    
                     left outer join (select ".$sigla."regist, 
                                             sum(".$sigla."valor) as ".$sigla."valor, 
                                             sum(".$sigla."quant) as ".$sigla."quant     
                                      from ".$arquivo."
                                      where ".$sigla."rubric in ".$rubrica_familia." and ". 
                                              $sigla."anousu=".db_str($xano,4)." and ". 
                                              $sigla."mesusu=".db_str($xmes,2)." and ". 
                                              $sigla."instit=".db_getsession("DB_instit")."
				      group by ".$sigla."regist) 
                                      as familia    
                                      on rh01_regist=familia.".$sigla."regist    
                     left outer join (select ".$sigla."regist, 
                                             sum(".$sigla."valor) as ".$sigla."valor, 
                                             sum(".$sigla."quant) as ".$sigla."quant    
                                      from ".$arquivo."
                                      where ".$sigla."rubric in ".$rubrica_gestante." and ". 
                                              $sigla."anousu=".db_str($xano,4)." and ". 
                                              $sigla."mesusu=".db_str($xmes,2)." and ". 
                                              $sigla."instit=".db_getsession("DB_instit")."
				      group by ".$sigla."regist)   
                                      as gestante    
                                      on rh01_regist=gestante.".$sigla."regist    
                     left outer join (select ".$sigla."regist, 
                                             sum(".$sigla."valor) as ".$sigla."valor, 
                                             sum(".$sigla."quant) as ".$sigla."quant     
                                      from ".$arquivo."
                                      where ".$sigla."rubric in ".$rubrica_saude." and ". 
                                              $sigla."anousu=".db_str($xano,4)." and ". 
                                              $sigla."mesusu=".db_str($xmes,2)." and ". 
                                              $sigla."instit=".db_getsession("DB_instit")."
				      group by ".$sigla."regist)   
                                      as saude    
                                      on rh01_regist=saude.".$sigla."regist " ;

}else{
   $sql .= "        left outer join (select ".$siglar."regist, 
                                            sum(".$siglar."valor) as ".$siglar."valor    
                                     from ".$arquivor."
                                     where ".$siglar."rubric in ".$rub_base." and ". 
                                            $siglar."anousu=".db_str($xano,4)." and ". 
                                            $siglar."mesusu=".db_str($xmes,2)." and ". 
                                            $siglar."instit=".db_getsession("DB_instit")."
				     group by ".$siglar."regist)   
                                     as prevr    
                                     on rh01_regist=prevr.".$siglar."regist     
                    left outer join (select ".$siglar."regist, 
                                            sum(".$siglar."valor) as ".$siglar."valor, 
                                            sum(".$siglar."quant) as ".$siglar."quant     
                                     from ".$arquivor."
                                     where ".$siglar."rubric in ".$rub_desc." and ". 
                                             $siglar."anousu=".db_str($xano,4)." and ". 
                                             $siglar."mesusu=".db_str($xmes,2)." and ". 
                                             $siglar."instit=".db_getsession("DB_instit")."
				     group by ".$siglar."regist)    
                                     as desconr    
                                     on rh01_regist=desconr.".$siglar."regist " ;


}

$sql .= " where rh02_tbprev = $prev
          order by rh01_regist 
          ) as x
	  where
          coalesce(base_prev,'0')::float8+ 
          coalesce(desc_prev,'0')::float8+ 
          coalesce(salfamilia,'0')::float8+ 
          coalesce(salgestante,'0')::float8+ 
          coalesce(salsaude,'0')::float8+ 
          coalesce(quant_desc_prevc,'0')::float8+ 
          coalesce(quant_desc_prevr,'0')::float8+ 
          coalesce(quant_desc_prev,'0')::float8+ 
          coalesce(cont_ent,'0')::float8+ 
          coalesce(proventos,'0')::float8 > 0 ";

//echo $sql;exit;
$result_princ = pg_query($sql);
if($result_princ == false){
  echo "<br>    ERRO AO GERAR O SQL. CONTATE SUPORTE.";
}elseif(pg_numrows($result_princ) == 0){
  echo "<br>    NÃO EXISTEM DADOS PARA O ANO/MÊS ESCOLHIDO.";
} 

/*
// dependentes 
$sql_dep  = " select lpad(rh31_regist,10,0) as regist,   
               rh31_nome as nome,   
               coalesce(to_char(rh31_dtnasc,'ddmmyyyy'),'00000000') as dtnasc,   
               case rh31_gparen    
                    when 'F' then '01'    
                    when 'C' then '02'    
                    when 'P' then '03'    
                    when 'M' then '03'    
               else '90'    
               end as gparen    
           from rhdepend   
             inner join rhpessoal    on  rhpessoal.rh01_regist = rhdepend.rh31_regist  
             inner join rhpessoalmov on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                    and  rhpessoalmov.rh02_anousu = ".$xano."
                                    and  rhpessoalmov.rh02_mesusu = ".$xmes."
                                    and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."   
             inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm  
        where    rh02_tbprev = $prev
          and    rh31_regist = rh01_regist
        order by rh01_regist" ;

$result_dep = pg_query($sql_dep);
if($result_dep == false){
  echo "<br>    ERRO AO GERAR O SQL DOS DEPENDENTES . CONTATE SUPORTE.";
}elseif(pg_numrows($result_dep) == 0){
  echo "<br>    NÃO EXISTEM DEPENDENTES CADASTRADOS.";
} 
*/

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt   = 4;
$head2 = "LAYOUT IDEAL SISTEMAS - CONFERENCIA";


$arquivo = fopen($nomearq,"w");


//             header do arquivo

$lin  = "1;";                                       // 1 fixo tipo de reg. ;
$lin .= db_str($xmes,2,0,"0").db_str($xano,4).";";  // ano ;

if( $sal_dec == "S" ){
   $lin .= "MENSAL            " ;
}else{
   $lin .= "13 SALARIO        " ;
}

fputs($arquivo,$lin."\n");  

//            detalhe dos funcionarios 

$pag            = 1;
$num_seq        = 2;
$total_geral    = 0;
$quant_creditos = 0;
$total_base     = 0;
$total_patronal = 0;
$total_desc     = 0;
//echo "<br> result_princ --> ".pg_numrows($result_princ);exit;
global $regist, $r01_regist,$nome,$admissao,$rescisao,$lotacao ,$situacao,$cpf,$z01_ender,$z01_bairro,$z01_munic,$z01_uf,$apos,$r01_admiss,$cod_apos,$base_prev,
       $desc_prev,$salfamilia,$salgestante,$salsaude,$quant_desc_prevc,$quant_desc_prevr,$quant_desc_prev,$cont_ent,$z01_cep,$nascimento,$proventos,
       $dtnasc, $gparen;

for($i = 0; $i < pg_numrows($result_princ);$i++){
   db_fieldsmemory($result_princ,$i);
   if(( $base_prev+$desc_prev+$salfamilia+$salgestante+$salsaude+$quant_desc_prevc+$quant_desc_prevr+$quant_desc_prev ) == 0){
     continue;
   }

//echo "<br> regist--> $regist, nome --> $nome, admissao --> $admissao,rescisao --> $rescisao, lotacao --> $lotacao , situacao --> $situacao";
//echo "<br> cpf --> $cpf, z01_ender --> $z01_ender, z01_bairro --> $z01_bairro, z01_munic --> $z01_munic, z01_uf --> $z01_uf, apos --> $apos";
//echo "<br> r01_admiss --> $r01_admiss, cod_apos --> $cod_apos, base_prev --> $base_prev, desc_prev --> $desc_prev, salfamilia --> $salfamilia";
//echo "<br> salgestante --> $salgestante, salsaude --> $salsaude, quant_desc_prevc --> $quant_desc_prevc, quant_desc_prevr --> $quant_desc_prevr";
//echo "<br> quant_desc_prev --> $quant_desc_prev, cont_ent --> $cont_ent, z01_cep --> $z01_cep, dtnasc --> $dtnasc, proventos --> $proventos";exit;       

   $lin = "2;";                                            // tipo de reg. fixo 2 ;
   $lin .= $regist.";";                                    // codigo ;
   $lin .= db_formatar($nome,'s',' ',40,'d').";";          // nome ;
   $lin .= $admissao.";";                                  // data de admissao  ;
   $lin .= db_formatar($lotacao,'s',' ',20,'d').";";       // localizacao ;
   $lin .= $situacao.";";		                               // situacao ;
   $lin .= db_formatar($cpf,'s','0',11,'e').";";           // cpf ;
   $lin .= db_formatar($z01_ender,'s',' ',36,'d').";";     // endereco ;
   $lin .= db_formatar($z01_bairro,'s',' ',14,'d').";";		 // bairro ;
   $lin .= db_formatar($z01_munic,'s',' ',20,'d').";";		 // cidade ;
   $lin .= $z01_uf.";";			                               // estado ;
   if( $situacao == '02' ){
      $lin .= $apos.";";                                   // inicio aposentadoria ;
   }else{
      $lin .= "00000000;" ;
   }
   $lin .= $cod_apos.";";		                               // codigo aposentadoria ;
   if( $situacao == '03' ){
      $lin .= $apos.";";                                   // inicio pensao ;
   }else{
      $lin .= "00000000;";
   }
   $lin .= $admissao.";";		                               // data inicio contr. fundo ;
   $lin .= db_formatar($rescisao,'s','0',8,'e').";";       // data demissao ;



   $bases = $base_prev + $proventos;



   if( db_at($situacao,"02-03") > 0  || $bases <= 0){      // inativos e pencionistas
       $lin .= "00000;";                                   // patronal;
   }else{
       $lin .=db_str( int($perc_patronal*100),5,0,"0").";";// perc. contribuicao da entidade ;
   }
   if( $sal_dec == "S" ){
       // utilizar o percentual do salario e caso vazio o da complementar;
       if(db_val( $quant_desc_prev ) > 0){
           $lin .= $quant_desc_prev.";";		               // perc. contribuicao do func. ;
       }else if(db_val( $quant_desc_prevc ) > 0){ 
           $lin .= $quant_desc_prevc.";";		               // perc. contribuicao do func. ;
       }else{
           $lin .= $quant_desc_prevr.";";		               // perc. contribuicao do func. ;
       }
    }else{
       // 13.salario;
       if(db_val( $quant_desc_prev ) > 0){
           $lin .= $quant_desc_prev.";";		               // perc. contribuicao do func. ;
       }else{
           $lin .= $quant_desc_prevr.";";		               // perc. contribuicao do func. ;
       }
    }


   if( $situacao == '01' ){
      $lin .= $base_prev.";";		                           // base contribuicao ;
      $total_base .= bb_round(db_val($base_prev)/100,2 );
   }else{
      $lin .= $proventos.";";                              // para inativos e pensionistas - soma proventos;
      $total_base .= bb_round(db_val($proventos)/100,2);
   }
   $lin .= "00000000000000;";                              // fixo zeros - valor liquido ;

   $lin .= $desc_prev.";";		                             // desc. previdencia ;
   $total_desc .= bb_round(db_val($desc_prev)/100,2);

   if( $situacao == '01' ){
      $lin .= $cont_ent.";";		                           // contribuicao da entidade ;
      $total_patronal .= bb_round(db_val($cont_ent)/100,2);
   }else{
      $lin .= "00000000000000;";		                       // nao lancar contribuicao da entidade para inativos / pensionistas;
   }

   if( $sal_dec == "S" ){
     $lin .= "20;";	                                       // fixo  - salario familia R918;
     $lin .= $salfamilia.";";	                             // fixo ;
     $lin .= "21;";
     $lin .= $salgestante.";";	                           // fixo  - licenca gestante - ver tabela inssirf rubrica relacionada;
     $lin .= "22;";			                                   // fixo - licenca saude - ver tabela inssirf;
     $lin .= $salsaude.";";
   }else{
     $lin .= "20;";			                                   // fixo  - salario familia R918;
     $lin .= "00000000;";		                               // fixo ;
     $lin .= "21;";
     $lin .= "00000000;";		                               // fixo  ;
     $lin .= "22;";			                                   // fixo - licenca saude - ver tabela inssirf;
     $lin .= "00000000;";		                               // fixo ;
   }
   $lin .= "23;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "24;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "25;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "26;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "27;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "28;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= "29;";			                                     // fixo ;
   $lin .= "00000000;";		                                 // fixo ;
   $lin .= $z01_cep.";"; 		                               // cep ;
   $lin .= "30;";			                                     // fixo ;
   $lin .= "00000000000000;";                              // fixo  - amortizacao;
   $lin .= "00000;";                                       // fixo ;
   $lin .= "00000000000000;";                              // fixo ;
   $lin .= "00000000000000;";                              // fixo ;
   $lin .= $nascimento;			                               // data de nascimento ;
   $lin .= ";";
   
   fputs($arquivo,$lin."\n");  


   // dependentes 
   $sql_dep  = " select lpad(rh31_regist,10,0) as regist,   
                  rh31_nome as nome,   
                  coalesce(to_char(rh31_dtnasc,'ddmmyyyy'),'00000000') as dtnasc,   
                  case rh31_gparen    
                       when 'F' then '01'    
                       when 'C' then '02'    
                       when 'P' then '03'    
                       when 'M' then '03'    
                  else '90'    
                  end as gparen    
              from rhdepend   
                inner join rhpessoal    on  rhpessoal.rh01_regist = rhdepend.rh31_regist  
                inner join rhpessoalmov on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                       and  rhpessoalmov.rh02_anousu = ".$xano."
                                       and  rhpessoalmov.rh02_mesusu = ".$xmes."
                                       and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")."   
                inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm  
           where    rh02_tbprev = $prev
             and    rh31_regist = $regist
           order by rh01_regist" ;

   $result_dep = pg_query($sql_dep);
   for($ii=0;$ii<pg_numrows($result_dep);$ii++){
      db_fieldsmemory($result_dep,$ii);
      $lin = "3;";                                         // tipo de reg. fixo 2 ;
      $lin .= $regist.";";                                 // codigo ;
      $lin .= db_formatar($nome,'s',' ',40,'d').";";       // nome ;
      $lin .= $dtnasc.";";                                 // data de nascimento ;
      $lin .= $gparen.";";                                 // grau de parentesco ;
      $lin .= " ; ; ;";                                    // campos nao disponiveis;
      fputs($arquivo,$lin."\n");  
   }


   // -------------------------------- Relatorio em PDF ----------------------------------------------

   if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $troca = 0;
      $pdf->cell(0,$alt,'',"T",1,"C",0);
      $pdf->cell(25,$alt,"Regist ".trim($regist),0,0,"L",0);
      $pdf->cell(40,$alt,"Nome  ",0,0,"L",0);
      $pdf->cell(20,$alt,"Lot. ",0,0,"L",0);
      $pdf->cell(25,$alt,"Inicio",0,1,"L",0);
      $pdf->cell(10,$alt,"Sit.",0,0,"L",0);
      $pdf->cell(18,$alt,"Cod. ",0,0,"L",0);
      $pdf->cell(20,$alt,"Base/Prov",0,0,"L",0);
      $pdf->cell(48,$alt,"Contrib. Entid %.",0,0,"L",0);
      $pdf->cell(48,$alt,"Contrib. Serv. %",0,1,"L",0);
   }

   $pdf->cell(25,$alt,$regist,0,0,"R",0);
   $pdf->cell(40,$alt,db_substr($nome,1,30),0,0,"R",0);
   $pdf->cell(20,$alt,$lotacao ,0,0,"R",0);
   $pdf->cell(25,$alt,$r01_admiss,0,0,"R",0);
   $pdf->cell(10,$alt,$situacao,0,0,"R",0);
   if( $situacao == '03'){
      $pdf->cell(18,$alt,$cod_apos,0,0,"R",0);;
   }
   if( $situacao == '01' ){
      $pdf->cell(20,$alt,db_formatar( bb_round(db_val($base_prev)/100,2),'f'),0,0,"R",0);
   }else{
      $pdf->cell(20,$alt,db_formatar( bb_round(db_val($proventos)/100,2),'f'),0,0,"R",0);
   }


   if(( db_at($situacao,"02-03") > 0)  || $bases <= 0){     // inativos e pencionistas
       $pdf->cell(48,$alt,db_formatar(0,'f')."%".db_formatar(0,'f'),0,0,"R",0) ;
   }else{
       // somentedb_ativos tem desconto de previdencia;
       $pdf->cell(48,$alt,db_formatar($perc_patronal,'f')."%".db_formatar( bb_round(db_val($cont_ent)/100,2),'f'),0,0,"R",0);
   }
   if( $sal_dec == "S" ){
      // utilizar o percentual do salario e caso vazio o da complementar;
      if ( db_val( $quant_desc_prev ) > 0){
          $pdf->cell(30,$alt,db_formatar( bb_round(db_val($quant_desc_prev)/100,2),'f')."%",0,0,"R",0);
      }else if(db_val( $quant_desc_prevc ) > 0){ 
          $pdf->cell(30,$alt,db_formatar( bb_round(db_val($quant_desc_prevc)/100,2),'f')."%",0,0,"R",0);
      }else{
          $pdf->cell(30,$alt,db_formatar( bb_round(db_val($quant_desc_prevr)/100,2),'f')."%",0,0,"R",0);
      }
   }else{
      // 13.salario;
      if(db_val( $quant_desc_prev ) > 0){
         $pdf->cell(30,$alt,db_formatar( bb_round(db_val($quant_desc_prev)/100,2),'f')."%",0,0,"R",0);
      }else{
         $pdf->cell(30,$alt,db_formatar( bb_round(db_val($quant_desc_prevr)/100,2),'f')."%",0,0,"R",0);
      }
   }
   $pdf->cell(18,$alt,db_formatar( bb_round(db_val($desc_prev)/100,2),'f'),0,1,"R",0);

   $quant_creditos++ ;
   $num_seq++ ;
}

$pdf->cell(18,$alt,db_formatar( $total_base,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar( $total_patronal,'f'),0,0,"R",0);
$pdf->cell(18,$alt,db_formatar( $total_desc,'f'),0,0,"R",0);

/*
//                       detalhe dos dependentes
 
$lin = 99 ;
$pag = 1 ;
$num_seq = 2 ;
$total_geral = 0;
$quant_creditos = 0;

for($ii=0;$ii<pg_numrows($result_dep);$ii++){
   db_fieldsmemory($result_dep,$ii);
   $lin = "3;";                           // tipo de reg. fixo 2 ;
   $lin .= $regist.";";                   // codigo ;
   $lin .= db_formatar($nome,'s',' ',40,'d').";";                     // nome ;
   $lin .= $dtnasc.";";                   // data de nascimento ;
   $lin .= $gparen.";";                   // grau de parentesco ;
   $lin .= " ; ; ;";                                       // campos nao disponiveis;
   fputs($arquivo,$lin."\n");  
}
*/

 $pdf->Output('/tmp/layout_ideal.pdf',false,true);
 fclose($arquivo);
 return 0;

}


?>