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

include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");

db_postmemory($HTTP_GET_VARS);

global $cfpess,$subpes, $db21_codcli;

$subpes = db_anofolha().'/'.db_mesfolha();

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
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Geração da Dirf ...');
?>
</center>
<? 
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?

global $db_config, $r70_numcgm , $whererhlota;
  if ($r70_numcgm==0){
    db_selectmax("db_config","select ender,cgc,nomeinst,bairro,cep,munic,uf,telef, email, db21_codcli , cgc from db_config where codigo = ".db_getsession("DB_instit"));
    $whererhlota = "";
  }else{
    db_selectmax("db_config","select z01_cgccpf  as cgc, z01_nome as nomeinst,z01_ender as ender, z01_bairro as bairro, z01_cep as cep, z01_telef as telef, z01_munic as munic,z01_uf as uf, z01_email as email from cgm where z01_numcgm = $r70_numcgm");
    $whererhlota = " and rh02_lota in (select r70_codigo from rhlota where r70_instit = ".db_getsession("DB_instit")." and r70_numcgm = $r70_numcgm) ";

  }
global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email, $oriret; 

$d08_ender  = db_translate($db_config[0]["ender"]);
$d08_cgc    = $db_config[0]["cgc"];
$d08_nome   = db_translate($db_config[0]["nomeinst"]);
$d08_bairro = db_translate($db_config[0]["bairro"]);
$d08_cep    = $db_config[0]["cep"];
$d08_munic  = db_translate($db_config[0]["munic"]);
$d08_uf     = $db_config[0]["uf"];
$d08_telef  = $db_config[0]["telef"];
$d08_email  = $db_config[0]["email"];

$db21_codcli = $db_config[0]["db21_codcli"];

//echo "<br> d08_ender--> $d08_ender  d08_cgc --> $d08_cgc  d08_nome --> $d08_nome  d08_bairro --> $d08_bairro  d08_cep --> $d08_cep  d08_munic --> $d08_munic  d08_uf --> $d08_uf  d08_telef --> $d08_telef  d08_email --> $d08_email "; exit;

global $ano_base,$mes_base,$codmun,$obs,$cnpj_sind,$cnpj_asso,$w_asso,$w_sind;
$db_erro = false;
$sqlerro = false;
$nomearq = "/tmp/dirf.txt"; 
$nomepdf = "/tmp/dirf.pdf"; 
gera_dirf($nomearq);
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
db_redireciona("pes4_geradirf001.php");

function gera_dirf($nomearq){

   global $subpes, $ano_base, $codret, $obs, $nomeresp, $cpfresp, $foneresp, $subini,$dddresp,$pref_fun, $whererhlota ;
   global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$db21_codcli,$oriret;  
   

   $tipodirf = "o";
   $logradouro = str_pad($d08_ender,40);

   $numero = bb_space(6);
   $complemento = bb_space(20);
   $fone = bb_space(8);

$subini = $subpes;
$subpes = $ano_base."/12";

db_selectmax( "cfpess", "select * from cfpess".bb_condicaosubpes("r11_"));
cria_work_12h();
//echo "<BR> passou aqui !!";
$numcgm = 0;

$matriz1 = array();
$matriz2 = array();
$matriz1[1] = "w_numcgm";
$matriz1[2] = "w_cpf";
$matriz1[3] = "w_nome";

global $sel_B904,$sel_B905,$sel_B906,$sel_B907,$sel_B908,$sel_B909,$sel_B910,$sel_B911, $sel_B912, $basesr;

$condicaoaux  = " and r09_base = ".db_sqlformat( "B904" );
$sel_B904 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B904 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B904 .= ",'"; 
     } 
     $sel_B904 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B905" );
$sel_B905 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B905 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B905 .= ",'"; 
     } 
     $sel_B905 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B906" );
$sel_B906 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B906 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B906 .= ",'"; 
     } 
     $sel_B906 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B907" );
$sel_B907 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B907 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B907 .= ",'"; 
     } 
     $sel_B907 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}
$condicaoaux  = " and r09_base = ".db_sqlformat( "B908" );
$sel_B908 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B908 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B908 .= ",'"; 
     } 
     $sel_B908 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B909" );
$sel_B909 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B909 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B909 .= ",'"; 
     } 
     $sel_B909 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}


$condicaoaux  = " and r09_base = ".db_sqlformat( "B910" );
$sel_B910 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B910 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B910 .= ",'"; 
     } 
     $sel_B910 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B911" );
$sel_B911 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B911 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B911 .= ",'"; 
     } 
     $sel_B911 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and r09_base = ".db_sqlformat( "B912" );
$sel_B912 = "0";
if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpesproc("r09_", $subini).$condicaoaux )){
  $sel_B912 = "'";
  for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
     if($Ibasesr > 0){
        $sel_B912 .= ",'"; 
     } 
     $sel_B912 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
  }
}

$condicaoaux  = " and extract(year from rh01_admiss) <= ".db_sqlformat($ano_base);
//$condicaoaux .= " and rh01_regist < 20 ";

if(isset($pref_fun)){
  if( $pref_fun == 'p'){
    $condicaoaux .= " and  rh02_regist < 50000 ";
  }else if( $pref_fun == 'f'){
    $condicaoaux .= " and  rh02_regist >= 50000 ";
  }
}

$condicaoaux .= " and ( rh05_recis is null ";
$condicaoaux .= "      or  ( rh05_recis is not null  and extract(year from rh05_recis) >= " .db_sqlformat($ano_base)." ) ) ";
//$condicaoaux .= " and rh01_regist in ( 10231 , 10810 ) ";
$condicaoaux .=  $whererhlota ;
$condicaoaux .= " order by rh01_numcgm ";

global $pessoal,$work,$arquivo,$indice;

$sql_work = "insert into ".$arquivo."(w_numcgm,w_nome,w_cpf) 
                 select distinct(rh01_numcgm), z01_nome, z01_cgccpf 
                 from rhpessoalmov 
                    inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                    left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes 
                    inner join cgm on z01_numcgm = rhpessoal.rh01_numcgm ".bb_condicaosubpesproc( "rh02_",$subini ).$condicaoaux ;
//echo "<BR> $sql_work";exit;
$result = pg_exec($sql_work);
/*
for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){
      db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
   
      if( $pessoal[$Ipessoal]["r01_numcgm"] != $numcgm ){
         $numcgm = $pessoal[$Ipessoal]["r01_numcgm"];
         $condicaoaux = " where z01_numcgm = ".db_sqlformat($pessoal[$Ipessoal]["r01_numcgm"]);
         global $cgm;
         db_selectmax( "cgm", "select * from cgm ".$condicaoaux );
         $matriz2[1] = $pessoal[$Ipessoal]["r01_numcgm"];
         $matriz2[2] = trim($pessoal[$Ipessoal]["z01_cgccpf"]);
         $matriz2[3] = $pessoal[$Ipessoal]["z01_nome"];

         db_insert( $arquivo, $matriz1, $matriz2 );
      }
}*/

$indice = " order by w_cpf ";
db_selectmax("work", " select * from $arquivo $indice");
//db_criatabela(pg_query(" select * from $arquivo $indice"));
//exit;
ficha_12h();
//echo "<BR> fim !!!";
db_selectmax( "work", " select * from $arquivo $indice");

//db_criatabela(pg_query("select * from $arquivo $indice"));
//exit;
imprime_dirf_12h($nomearq);

$subpes = $subini;

}

function ficha_12h(){
   global $tributo,$vlrdep,$retido,$indice,$cfpess,$subpes,$pref_fun,
          $previd ,$pensao,$tribs13,
          $vdep13, $rets13,$prev13,$vdeducao65,
          $pensao13,$vdeducao65_13, $vdeducao65_13, $mtributo,$mtribs13,$arquivo,$work,$arquivo;
   global $gerfsal, $gerfcom, $gerfres, $gerffer, $gerfs13,$ano_base,$subini;
   global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$db21_codcli,$oriret,
          $w_tribs13, $w_rets13, $w_deps13, $w_pres13, $w_pens13;
   
   $ant = $subpes;
   
   $tot_work = count($work)*12;
   $voltas=0;
   for($ind=1;$ind<=12;$ind++){
      global $diversos;
//echo "<BR> ind --> $ind";
      if( $db21_codcli == 55 ){
         // amparo faz os lancamentos de dezembro com janeiro do ano base e;
         // assim por diante ;
         if( $ind == 1){
            $subpes = db_str( db_val($ano_base)-1,4,0)."/12";
         }else{
            $subpes = $ano_base . "/" . db_str($ind-1,2,0,"0");
         }
      }else{
         $subpes = $ano_base . "/" . db_str($ind,2,0,"0");
      }
//      global $work;
//      db_selectmax( "work", "select * from $arquivo $indice" );
      $condicaoaux = " and r07_codigo = 'D902'";
      if( db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ).$condicaoaux )){
         $D902 = $diversos[0]["r07_valor"];
      }else{
         $D902 = 0;
      }
      $atual = 0;
      $mes = db_substr("janfevmarabrmaijunjulagosetoutnovdez",($ind*3)-2,3);
      $diasn = db_str(ndias(db_str($ind,2,0,"0")."/".$ano_base),2,0,"0");
      $datet = db_ctod($diasn."/".db_str($ind,2,0,"0")."/".$ano_base) ;
      for($Iwork=0;$Iwork<count($work);$Iwork++){
      $voltas++;
      db_atutermometro($voltas,$tot_work,'calculo_folha',1);
//   echo "<BR> linha de inicializacao...";
         $atual += 1;
         $condicaoaux = " and rh01_numcgm = ".db_sqlformat($work[$Iwork]["w_numcgm"] );
         
         if(isset($pref_fun)){
           if( $pref_fun == 'p'){
             $condicaoaux .= " and  rh02_regist < 50000 ";
           }else if( $pref_fun == 'f'){
             $condicaoaux .= " and  rh02_regist >= 50000 ";
           }
         }
         
         $condicaoaux .= "order by rh01_numcgm ";
	 global $pess,$Ipes;
//echo "<BR> select * from pessoal ".bb_condicaosubpesproc( "r01_",$subini ).$condicaoaux;
  $campos_pessoal  = "rh01_regist as r01_regist,
                      rh01_numcgm as r01_numcgm, 
                      trim(to_char(rh02_lota,'9999')) as r01_lotac,
                      rh01_nasc     as r01_nasc,
                      rh01_admiss   as r01_admiss,
                      rh01_instru   as r01_instru,
											rh05_recis    as r01_recis,
                      rh30_vinculo  as r01_tpvinc,
										  rh02_tbprev   as r01_tbprev	";

                      
		$sql = "select ".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes 
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
											                   and rhregime.rh30_instit = rhpessoalmov.rh02_instit
                       ".bb_condicaosubpesproc("rh02_",$subini).$condicaoaux ;

    db_selectmax("pess", $sql);

//db_criatabela(pg_query("select r01_admiss,r01_lotac,r01_recis,r01_numcgm,r01_nasc,r01_regist,r01_tbprev,r01_tpvinc from pessoal ".bb_condicaosubpesproc( "r01_",$subini ).$condicaoaux));

         $tributo = 0;
         $retido  = 0;
         $previd  = 0;
         $vlrdep  = 0;
         $vdep13  = 0;
         $tribs13 = 0;
         $prev13  = 0;
         $rets13  = 0;
         $depmes  = 0;
         $ina     = 0;
         $ina13   = 0;
         $pensao = 0;
         $pensao13 = 0;
         $idade = ver_idade(db_dtoc($datet),db_dtoc($pess[0]["r01_nasc"]));
         $depmes = 0;
         
         $pensao = 0;
         $pensao13 = 0;

         $previd = 0;
         $prev13 = 0;

         $vdeducao65 = 0;
         $vdeducao65_13= 0;

         $vdeducao = 0;
         $vdeducao_13 = 0;

         $registros = " ";
         // posicionado no pessoal indexado por cgm;
         for($Ipes=0;$Ipes<count($pess);$Ipes++){
//echo "<BR> r01_regist --> ".$pess[$Ipes]["r01_regist"];
            $mtributo = 0;
            $mtribs13 = 0;
            $deducao65 = 0;
            $deducao65_13 = 0;

            if( db_year($pess[$Ipes]["r01_admiss"]) <= db_val($ano_base) && ( db_empty($pess[$Ipes]["r01_recis"]) || (!db_empty($pess[$Ipes]["r01_recis"]) && db_year($pess[$Ipes]["r01_recis"])>=db_val($ano_base)))){
               if($Ipes < 9){
                  $registros .= db_str($pess[$Ipes]["r01_regist"],6)." / ";
               }

               $condicaoaux = " and r33_codtab = ".db_sqlformat( $pess[$Ipes]["r01_tbprev"]+2 );
	       global $inssirf;
               db_selectmax( "inssirf", "select r33_tipo from inssirf ".bb_condicaosubpesproc( "r33_", $subini ).$condicaoaux );
             

               $condicaoaux = " and r14_regist = ".db_sqlformat( $pess[$Ipes]["r01_regist"] );
               if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
                  soma2000($gerfsal,"r14_");
               }
              
               $condicaoaux = " and r48_regist = ".db_sqlformat($pess[$Ipes]["r01_regist"] );
               if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
                  soma2000($gerfcom,"r48_");
               }
               
               $condicaoaux = " and r20_regist = ".db_sqlformat( $pess[$Ipes]["r01_regist"] );
               if( db_selectmax( "gerfres", "select * from gerfres ".bb_condicaosubpes( "r20_" ).$condicaoaux )){
                  soma2000($gerfres,"r20_");
               }
               
               // somente ler ferias antes da mudanca de avaliacao de ferias;
               if( db_empty( $cfpess[0]["r11_altfer"] ) || $subpes < $cfpess[0]["r11_altfer"] ){

                  $condicaoaux = " and r31_regist = ".db_sqlformat($pess[$Ipes]["r01_regist"] );
                  if( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
                     soma2000($gerffer,"r31_");
                  }
               }
               $condicaoaux = " and r35_regist = ".db_sqlformat($pess[$Ipes]["r01_regist"] );
               if( db_selectmax( "gerfs13", "select * from gerfs13 ".bb_condicaosubpes( "r35_" ).$condicaoaux )){
                  soma2000($gerfs13,"r35_");
               }

               $depmes = $vlrdep;
//echo "<BR> entrou 1";
               if( db_at(strtolower($pess[$Ipes]["r01_tpvinc"]),"ip") > 0 && ( $idade > 65 || ( $idade==65 && db_month($pess[$Ipes]["r01_nasc"]) <= ( $db21_codcli == 55 ?db_val(db_substr($subpes,6,2)):$ind) ) )){
//echo "<BR> entrou 2";
/*
                  if( $subpes < $cfpess[0]["r11_altfer"] || db_empty( $cfpess[0]["r11_altfer"] )){

                         $vdeducao65 = $D902;
                         $vdeducao65_13 = $D902;
                  }else{
                    // para os funcionarios com + de 65 anos e que o valor ;
                    // dos proventos nao alcancaram o valor do $D902 no;
                    // calculo a valor da deducao R997 nao foi gravado no;
                    // arquivo;
                    if( $vdeducao65 == 0 && $tributo < $D902){
                        $vdeducao65 = $D902;
                    }
                    if( $vdeducao65_13 == 0 && $tribs13 < $D902){
                        $vdeducao65_13 = $D902;
                    }
                  }
                  
                  if( $mtributo >= $vdeducao65){
                     $ina     += $vdeducao65;
                     $tributo -= $vdeducao65;
                  }else{
                     $ina      += $mtributo;
                     $tributo  -= $mtributo;
                  }
                  
                  if( $mtribs13 >= $vdeducao65_13){
                     $ina13   += $vdeducao65_13;
                     $tribs13 -= $vdeducao65_13;
                  }else{
                     $ina13   += $mtribs13;
                     $tribs13 -= $mtribs13;

                  }*/
			  if($mtributo >= $D902) {
			    $ina     += $D902;
			    $tributo -= $D902;
			  }else {
			    $ina     += $tributo;
			    $tributo  = 0;
			  }
			  if($mtribs13 >= $D902) {
			    $ina      += $D902;
			    $mtribs13 -= $D902;
			  }elseif($mtribs13 > 0) {
			    $ina     += $mtribs13;
			    $mtribs13 = 0;
			  }
//echo "<BR> ina --> $ina    tributo --> $tributo ";
               }
            }
         }
         // no comprovante se nao existir valor de 13.salario (R982) e existir;
         // desconto de previdencia esta lancando o desconto na lista de;
         // deducoes...necessario consultar receita;
         // deducoes = dependentes+previdencia+pensao;

         $campo_wtrib = "w_trib".$mes;
         $campo_wret  = "w_ret".$mes;
         $campo_wdep  = "w_dep".$mes;
         $campo_wpre  = "w_pre".$mes;
         $campo_wpen  = "w_pen".$mes;

         $matriz1 = array();
	       $matriz2 = array();
         $matriz1[1] = $campo_wtrib;
         $matriz1[2] = $campo_wret;
         $matriz1[3] = $campo_wdep;
         $matriz1[4] = $campo_wpre;
         $matriz1[5] = $campo_wpen;
         $matriz1[6] = "w_regist";

         $matriz2[1]  = $tributo;
         $matriz2[2]  = $retido;
         $matriz2[3]  = $depmes;
         $matriz2[4]  = $previd;
         $matriz2[5] = $pensao;
         $matriz2[6] = $registros;

         $matriculacgm = $work[$Iwork]["w_numcgm"];
         $condicaoaux = " where w_numcgm = ".db_sqlformat( $matriculacgm );
         db_update( $arquivo, $matriz1, $matriz2, $condicaoaux );

         if( !db_empty($tribs13)){
           $matriz1 = array();
	         $matriz2 = array();
           $matriz1[1] = "w_tribs13";
           $matriz1[2] = "w_rets13";
           $matriz1[3] = "w_deps13";
           $matriz1[4] = "w_pres13";
           $matriz1[5] = "w_pens13";

           $res_work = pg_query("select w_tribs13,w_rets13,w_deps13,w_pres13,w_pens13 from $arquivo where w_numcgm = ".db_sqlformat( $matriculacgm ));
           if(pg_numrows($res_work) > 0){
             db_fieldsmemory($res_work,0);
             $matriz2[1] = $w_tribs13 + $tribs13;
             $matriz2[2] = $w_rets13  + $rets13;
             $matriz2[3] = $w_deps13  + $vdep13;
             $matriz2[4] = $w_pres13  + $prev13;
             $matriz2[5] = $w_pens13  + $pensao13;
           }else{
             $matriz2[1] = $tribs13;
             $matriz2[2] = $rets13;
             $matriz2[3] = $vdep13;
             $matriz2[4] = $prev13;
             $matriz2[5] = $pensao13;
           }
           db_update( $arquivo, $matriz1, $matriz2, $condicaoaux );
         }
         $previd = 0;
         $prev13 = 0;
      }
      
   }
   $subpes = $ant;
}

function soma2000($arq,$sigla){

global $tributo,$vlrdep,$retido,$subpes,$cfpess,$subini,$inssirf,$pess,$Ipes,
       $previd ,$pensao,$tribs13,
       $vdep13, $rets13,$prev13,$vdeducao65,
       $pensao13,$vdeducao65_13, $vdeducao65_13, $mtributo,$mtribs13,$basesr;

global $sel_B904,$sel_B905,$sel_B906,$sel_B907,$sel_B908,$sel_B909,$sel_B910,$sel_B911, $sel_B912, $basesr;


// situacao de ferias novas e nova forma de ver as bases da complementar;
$lercomplementar = ($subpes >= $cfpess[0]["r11_altfer"]?true:false);

for($Iarq=0;$Iarq<count($arq);$Iarq++){
//  echo "<BR> rubric --> ".$arq[$Iarq][$sigla."rubric"]." sigla --> $sigla";
   // salario + ferias (base bruta p/ irf);
   if( $arq[$Iarq][$sigla."rubric"] == "R981" || $arq[$Iarq][$sigla."rubric"] == "R983"){
      if( ( $sigla != "r48_" || ( $sigla == "r48_" && $lercomplementar ) )){
         $tributo += $arq[$Iarq][$sigla."valor"];
         $mtributo += $arq[$Iarq][$sigla."valor"];
      }
   }else{
      // 13o salario (base bruta p/ irf);
      if( $arq[$Iarq][$sigla."rubric"] == "R982"){
         if( $sigla != "r48_" || ( $sigla == "r48_" && $lercomplementar ) ){
            $tribs13 += $arq[$Iarq][$sigla."valor"];
            $mtribs13 += $arq[$Iarq][$sigla."valor"];

         }
      }else{
         // vlr ref dependentes p/ irf;
         if( $arq[$Iarq][$sigla."rubric"] == "R984"){
            if( $sigla == "r35_" ){
               if( !db_empty($vdep13) && !db_empty($arq[$Iarq][$sigla."valor"]) ){
                  $vdep13 = 0;
               }
               $vdep13 += $arq[$Iarq][$sigla."valor"];
            }else if($sigla == "r20_" && $mtribs13 > 0 ){ 
               if( !db_empty($vdep13) && !db_empty($arq[$Iarq][$sigla."valor"]) ){
                  $vdep13 = 0;
               }
               $vdep13  += $arq[$Iarq][$sigla."valor"];
            }else if($sigla == "r48_" && $lercomplementar){
               // somente ler o dependente da complementar se este nao;
               // estiver no salario ( que foi lido primeiro );
               if( db_empty( $vlrdep )){
                  $vlrdep += $arq[$Iarq][$sigla."valor"];
               }
            }else if($sigla != "r48_"){
               if( db_empty( $vlrdep )){
                 $vlrdep += $arq[$Iarq][$sigla."valor"];
               }
            }
         }
         // deducao +65 anos para salario e 13.salario;
         if( $lercomplementar && $arq[$Iarq][$sigla."rubric"] == "R997" || $arq[$Iarq][$sigla."rubric"] == "R999"){
            if( $sigla == "r35_" ||  $arq[$Iarq][$sigla."rubric"] == "R999"){
               if( !db_empty($vdeducao65_13) && !db_empty($arq[$Iarq][$sigla."valor"]) ){
                  $vdeducao65_13 = 0;
               }
               $vdeducao65_13  += $arq[$Iarq][$sigla."valor"];
            }else if($sigla == "r48_" && $lercomplementar){
               if( db_empty( $vdeducao65 )){
                  $vdeducao65 += $arq[$Iarq][$sigla."valor"];
               }
            }else if($sigla == "r31_"){
               if( db_empty( $vdeducao65 )){
                  $vdeducao65 += $arq[$Iarq][$sigla."valor"];
               }
            }else if($sigla == "r48_"){
               $vdeducao65 += $arq[$Iarq][$sigla."valor"];
            }
         }
         
         $mrubr = $arq[$Iarq][$sigla."rubric"];
	 
         //*** o arquivo bases e lido a partir do mes de processamento (inicial);
         
         if( db_at($mrubr,$sel_B911) > 0){
            if( $arq[$Iarq][$sigla."pd"] == 2 ){
               $tributo -= $arq[$Iarq][$sigla."valor"];
            }else{
               $tributo += $arq[$Iarq][$sigla."valor"];
            }
         }

//         if( db_at($mrubr,$sel_B912) > 0 ){
//           if($arq[$Iarq][$sigla."anousu"] == 2009 && $arq[$Iarq][$sigla."mesusu"] == 1 ){
//
//             if( $arq[$Iarq][$sigla."pd"] == 2 ){
//                $tributo += $arq[$Iarq][$sigla."valor"];
//             }else{
//                $tributo -= $arq[$Iarq][$sigla."valor"];
//             }
//           }else{  
//           }
//         }
         
         
         if( ( $sigla != "r48_" || ( $sigla == "r48_" && $lercomplementar ) )){

            // busca irf (menos 13o salario);
            if( db_at($mrubr,$sel_B906) > 0){
               if( $arq[$Iarq][$sigla."pd"] == 2 ){
                  $retido += $arq[$Iarq][$sigla."valor"];
               }else{
                  $retido -= $arq[$Iarq][$sigla."valor"];
               }
            }
            // busca irf (13o salario);
            if( db_at($mrubr,$sel_B909) > 0){
               if( $arq[$Iarq][$sigla."pd"] == 2 ){
                  $rets13 += $arq[$Iarq][$sigla."valor"];
               }else{
                  $rets13 -= $arq[$Iarq][$sigla."valor"];
               }
            }
            
            // prev 13o salario;
            if( db_at($mrubr,$sel_B908) > 0){
               if( $arq[$Iarq][$sigla."pd"] == 2 ){
                  $prev13 += $arq[$Iarq][$sigla."valor"];
               }else{
                  $prev13 -= $arq[$Iarq][$sigla."valor"];
               }
            }

            // busca previd (menos de 13o salario);
            if( db_at($mrubr,$sel_B907) > 0){

               if( strtolower($inssirf[0]["r33_tipo"]) == "o" && $pess[$Ipes]["r01_tbprev"] != 0 ){
                  if( $arq[$Iarq][$sigla."pd"] == 2){
                     $previd += $arq[$Iarq][$sigla."valor"];
                  }else{
                     $previd -= $arq[$Iarq][$sigla."valor"];
                  }
               }else{
                  // previdencia privada ;
                  // mantive como no comprovante porem neste todas as previdencia;
                  // ficam como deducoes ;
                  if( $arq[$Iarq][$sigla."pd"] == 2){
                     $previd += $arq[$Iarq][$sigla."valor"];
                  }else{
                     $previd -= $arq[$Iarq][$sigla."valor"];
                  }
               }
            }
            // nao estava lendo esta base na dirf e no comprovante estava...;
            // previdencia privada tambem e deducao;
            if( db_at($mrubr,$sel_B910) > 0){
               if( $arq[$Iarq][$sigla."pd"] == 2){
                  $previd += $arq[$Iarq][$sigla."valor"];
               }else{
                  $previd -= $arq[$Iarq][$sigla."valor"];
               }
            }
         }
         //                    ;
         // busca vlrs pensao alimenticia ;
         if( db_at($mrubr,$sel_B905) > 0){

            if( $sigla == "r35_" ||  ( db_val($mrubr) >= 4000 && db_val($mrubr) < 6000 )){
               if( $arq[$Iarq][$sigla."pd"] == 1){
                  $pensao13 -= $arq[$Iarq][$sigla."valor"];
               }else{
                  $pensao13 += $arq[$Iarq][$sigla."valor"];
               }
            }else{
               if( $arq[$Iarq][$sigla."pd"] == 1){
                  $pensao -= $arq[$Iarq][$sigla."valor"];
               }else{
                  $pensao += $arq[$Iarq][$sigla."valor"];
               }
               // se for forma antiga (R994) deve levar em conta que a;
               // pensao ja estava descontada na base "bruta";
               if( db_at($mrubr,$sel_B904) > 0){
                  if( $arq[$Iarq][$sigla."pd"] == 1){
                     $tributo -= $arq[$Iarq][$sigla."valor"];
                  }else{
                     $tributo += $arq[$Iarq][$sigla."valor"];
                  }
               }
            }

         }
      }
   }
}

}

function imprime_dirf_12h($nomearq){
  
   global $work,$ano_base,$cpfresp,$nomeresp,$dddresp,$foneresp,$codret,$obs,$pdf,$head1,$head2,$head3;
   
   global $d08_ender,$d08_cgc,$d08_nome,$d08_bairro,$d08_cep,$d08_munic,$d08_uf,$d08_telef,$d08_email,$oriret;  
   $seq = 1;
   
   $tot_geral_trib = 0;
   $tot_geral_dep = 0;
   $tot_geral_pre = 0;
   $tot_geral_pen = 0;
   $tot_geral_ret = 0;
   $tot_trib_13 = 0;
   $tot_dep_13 = 0;
   $tot_pre_13 = 0;
   $tot_pen_13 = 0;
   $tot_ret_13 = 0;

   $pdf = new PDF();
   $pdf->Open();
   $pdf->AliasNbPages();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $troca = 1;
   $alt   = 4;
   $head2 = "RELATÓRIO DE SIMPLES CONFERÊNCIA - DIRF";
   $head3 = "ANO BASE :".$ano_base;
   
   $arquivo = fopen($nomearq,"w");
   $tributo = array();
   $retido  = array();
   $vlrdep  = array();
   $vlrpre  = array();
   $vlrpen  = array();
   $tributo = array_fill(1,13,0);
   $retido  = array_fill(1,13,0);
   $vlrdep  = array_fill(1,13,0);
   $vlrpre  = array_fill(1,13,0);
   $vlrpen  = array_fill(1,13,0);
   $zeros = str_repeat("0",15);
//   echo "<BR> linha de inicializacao...";
   $lin  = "00000001";               // da 01 a 08;
   $lin .= "1";                      // da 09 a 091;
   $lin .= str_pad($d08_cgc,14,'0'); // da 10 a 23;
   $lin .= "Dirf";                   // da 24 a 27;
   $lin .= $ano_base;                // da 28 a 31 ;
   $lin .= $oriret;                  // da 32 a 32 - original / retificadora;
   $lin .= "1";                      //            - declaracao normal;
   $lin .= "2";                      // da 34 a 34 - declarante e pess.juridica ;
   $lin .= "2";                      // da 35 a 35 - administracao publica municipal;
   $lin .= "0";                      // da 36 a 36 - pelo menos um func.com valor;
   $lin .= db_str(db_anofolha(),4,"0");// da 37 a 40 - sempre 2003;
   $lin .= "00";                     // da 41 a 42 ;
   $lin .= str_pad(substr($d08_nome,0,60),60);    // da 43 a 102;
   $lin .= str_pad(substr($cpfresp,0,11),11);    // da 103 a 113 - cpf $respons. 
   $lin .= bb_space(292);             // da 114 a 150 - filler;
   $lin .= str_pad(trim($cpfresp),11,'0'); // da 406 a 416 - cpf $respons. 
   $lin .= str_pad(substr($nomeresp,0,60),60);    // da 417 a 476 - $responsavel 
   $lin .= str_pad(substr($dddresp,0,4),4,"0",0); // da 481 a 488 - fone $resp 
   $lin .= str_pad(substr($foneresp,0,8),8,"0",0); 
   $lin .= "00000000000000";            // da 489 a 729 - ramal;
   $lin .= bb_space(227);            // da 489 a 729 - ramal;
   $lin .= "9";                      // da 730 a 730;
   fputs($arquivo,$lin."\n");

   for($Iwork=0;$Iwork<count($work);$Iwork++){
     /*
      if( !db_empty($work[$Iwork]["w_tribjan"] + $work[$Iwork]["w_tribfev"] + $work[$Iwork]["w_tribmar"] + 
                $work[$Iwork]["w_tribabr"] + $work[$Iwork]["w_tribmai"] + $work[$Iwork]["w_tribjun"] + 
                $work[$Iwork]["w_tribjul"] + $work[$Iwork]["w_tribago"] + $work[$Iwork]["w_tribset"] + 
                $work[$Iwork]["w_tribout"] + $work[$Iwork]["w_tribnov"] + $work[$Iwork]["w_tribdez"]) 
	  && 
          !db_empty($work[$Iwork]["w_retjan"] + $work[$Iwork]["w_retfev"] + $work[$Iwork]["w_retmar"] + 
                  $work[$Iwork]["w_retabr"] + $work[$Iwork]["w_retmai"] + $work[$Iwork]["w_retjun"] + 
                  $work[$Iwork]["w_retjul"] + $work[$Iwork]["w_retago"] + $work[$Iwork]["w_retset"] + 
                  $work[$Iwork]["w_retout"] + $work[$Iwork]["w_retnov"] + $work[$Iwork]["w_retdez"] + $work[$Iwork]["w_rets13"])){
    */
//         echo "<BR> seq --> $seq";
      if( ($work[$Iwork]["w_tribjan"] + $work[$Iwork]["w_tribfev"] + $work[$Iwork]["w_tribmar"] + 
                $work[$Iwork]["w_tribabr"] + $work[$Iwork]["w_tribmai"] + $work[$Iwork]["w_tribjun"] + 
                $work[$Iwork]["w_tribjul"] + $work[$Iwork]["w_tribago"] + $work[$Iwork]["w_tribset"] + 
                $work[$Iwork]["w_tribout"] + $work[$Iwork]["w_tribnov"] + $work[$Iwork]["w_tribdez"] ) >  6000 
          ||      
          ($work[$Iwork]["w_retjan"] + $work[$Iwork]["w_retfev"] + $work[$Iwork]["w_retmar"] + 
                  $work[$Iwork]["w_retabr"] + $work[$Iwork]["w_retmai"] + $work[$Iwork]["w_retjun"] + 
                  $work[$Iwork]["w_retjul"] + $work[$Iwork]["w_retago"] + $work[$Iwork]["w_retset"] + 
                  $work[$Iwork]["w_retout"] + $work[$Iwork]["w_retnov"] + $work[$Iwork]["w_retdez"] + $work[$Iwork]["w_rets13"]) <> 0){

         $seq += 1;
         $lin = db_str($seq,8,0,"0");
         $lin .= "2";
         $lin .= str_pad($d08_cgc,14,'0');
         $lin .= str_pad(substr($codret,0,4),4,'0',0);                               // codigo de retencao
         $lin .= "1";
         $lin .= "000".(db_empty($work[$Iwork]["w_cpf"])?"00000000000000":substr($work[$Iwork]["w_cpf"],0,11));
         $lin .= str_pad(substr($work[$Iwork]["w_nome"],0,59),60);
         
         $lin .= valor_12h($work[$Iwork]["w_tribjan"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retjan"]);
         $lin .= valor_12h($work[$Iwork]["w_tribfev"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retfev"]);
         $lin .= valor_12h($work[$Iwork]["w_tribmar"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retmar"]);
         $lin .= valor_12h($work[$Iwork]["w_tribabr"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retabr"]);
         $lin .= valor_12h($work[$Iwork]["w_tribmai"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retmai"]);
         $lin .= valor_12h($work[$Iwork]["w_tribjun"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retjun"]);
         $lin .= valor_12h($work[$Iwork]["w_tribjul"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retjul"]);
         $lin .= valor_12h($work[$Iwork]["w_tribago"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retago"]);
         $lin .= valor_12h($work[$Iwork]["w_tribset"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retset"]);
         $lin .= valor_12h($work[$Iwork]["w_tribout"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retout"]);
         $lin .= valor_12h($work[$Iwork]["w_tribnov"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retnov"]);
         $lin .= valor_12h($work[$Iwork]["w_tribdez"]) . '000000000000000' . valor_12h($work[$Iwork]["w_retdez"]);
         $lin .= valor_12h($work[$Iwork]["w_tribs13"]) . '000000000000000' . valor_12h($work[$Iwork]["w_rets13"]);

         $lin .= "00".bb_space(40);
         $lin .= "9";                      // da 730 a 730;

         fputs($arquivo,$lin."\n");

////  este registro só será criado se o contribuinte tiver algum valor de dedução(previdencia, pensao, dependente) no ano
         if( valor_12h($work[$Iwork]["w_prejan"]) + valor_12h($work[$Iwork]["w_depjan"]) + valor_12h($work[$Iwork]["w_penjan"])+
             valor_12h($work[$Iwork]["w_prefev"]) + valor_12h($work[$Iwork]["w_depfev"]) + valor_12h($work[$Iwork]["w_penfev"])+
             valor_12h($work[$Iwork]["w_premar"]) + valor_12h($work[$Iwork]["w_depmar"]) + valor_12h($work[$Iwork]["w_penmar"])+
             valor_12h($work[$Iwork]["w_preabr"]) + valor_12h($work[$Iwork]["w_depabr"]) + valor_12h($work[$Iwork]["w_penabr"])+
             valor_12h($work[$Iwork]["w_premai"]) + valor_12h($work[$Iwork]["w_depmai"]) + valor_12h($work[$Iwork]["w_penmai"])+
             valor_12h($work[$Iwork]["w_prejun"]) + valor_12h($work[$Iwork]["w_depjun"]) + valor_12h($work[$Iwork]["w_penjun"])+
             valor_12h($work[$Iwork]["w_prejul"]) + valor_12h($work[$Iwork]["w_depjul"]) + valor_12h($work[$Iwork]["w_penjul"])+
             valor_12h($work[$Iwork]["w_preago"]) + valor_12h($work[$Iwork]["w_depago"]) + valor_12h($work[$Iwork]["w_penago"])+
             valor_12h($work[$Iwork]["w_preset"]) + valor_12h($work[$Iwork]["w_depset"]) + valor_12h($work[$Iwork]["w_penset"])+
             valor_12h($work[$Iwork]["w_preout"]) + valor_12h($work[$Iwork]["w_depout"]) + valor_12h($work[$Iwork]["w_penout"])+
             valor_12h($work[$Iwork]["w_prenov"]) + valor_12h($work[$Iwork]["w_depnov"]) + valor_12h($work[$Iwork]["w_pennov"])+
             valor_12h($work[$Iwork]["w_predez"]) + valor_12h($work[$Iwork]["w_depdez"]) + valor_12h($work[$Iwork]["w_pendez"])+
             valor_12h($work[$Iwork]["w_pres13"]) + valor_12h($work[$Iwork]["w_deps13"]) + valor_12h($work[$Iwork]["w_pens13"]) > 0){


             $seq += 1;
             $lin = db_str($seq,8,0,"0");
             $lin .= "2";
             $lin .= str_pad($d08_cgc,14,'0');
             $lin .= str_pad(substr($codret,0,4),4,'0',0);                               // codigo de retencao
             $lin .= "1";
             $lin .= "000".(db_empty($work[$Iwork]["w_cpf"])?"00000000000000":substr($work[$Iwork]["w_cpf"],0,11));
             $lin .= str_pad(substr($work[$Iwork]["w_nome"],0,59),60);
             
             $lin .= valor_12h($work[$Iwork]["w_prejan"]) . valor_12h($work[$Iwork]["w_depjan"]) . valor_12h($work[$Iwork]["w_penjan"]);
             $lin .= valor_12h($work[$Iwork]["w_prefev"]) . valor_12h($work[$Iwork]["w_depfev"]) . valor_12h($work[$Iwork]["w_penfev"]);
             $lin .= valor_12h($work[$Iwork]["w_premar"]) . valor_12h($work[$Iwork]["w_depmar"]) . valor_12h($work[$Iwork]["w_penmar"]);
             $lin .= valor_12h($work[$Iwork]["w_preabr"]) . valor_12h($work[$Iwork]["w_depabr"]) . valor_12h($work[$Iwork]["w_penabr"]);
             $lin .= valor_12h($work[$Iwork]["w_premai"]) . valor_12h($work[$Iwork]["w_depmai"]) . valor_12h($work[$Iwork]["w_penmai"]);
             $lin .= valor_12h($work[$Iwork]["w_prejun"]) . valor_12h($work[$Iwork]["w_depjun"]) . valor_12h($work[$Iwork]["w_penjun"]);
             $lin .= valor_12h($work[$Iwork]["w_prejul"]) . valor_12h($work[$Iwork]["w_depjul"]) . valor_12h($work[$Iwork]["w_penjul"]);
             $lin .= valor_12h($work[$Iwork]["w_preago"]) . valor_12h($work[$Iwork]["w_depago"]) . valor_12h($work[$Iwork]["w_penago"]);
             $lin .= valor_12h($work[$Iwork]["w_preset"]) . valor_12h($work[$Iwork]["w_depset"]) . valor_12h($work[$Iwork]["w_penset"]);
             $lin .= valor_12h($work[$Iwork]["w_preout"]) . valor_12h($work[$Iwork]["w_depout"]) . valor_12h($work[$Iwork]["w_penout"]);
             $lin .= valor_12h($work[$Iwork]["w_prenov"]) . valor_12h($work[$Iwork]["w_depnov"]) . valor_12h($work[$Iwork]["w_pennov"]);
             $lin .= valor_12h($work[$Iwork]["w_predez"]) . valor_12h($work[$Iwork]["w_depdez"]) . valor_12h($work[$Iwork]["w_pendez"]);
             $lin .= valor_12h($work[$Iwork]["w_pres13"]) . valor_12h($work[$Iwork]["w_deps13"]) . valor_12h($work[$Iwork]["w_pens13"]);
         
             $lin .= "01".bb_space(40);
             $lin .= "9";                      // da 730 a 730;
             fputs($arquivo,$lin."\n");
         }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//         $lin .= str_pad(db_str($work[$Iwork]["w_numcgm"],13),13);


         $tributo[1]  += round($work[$Iwork]["w_tribjan"],2);
         $tributo[2]  += round($work[$Iwork]["w_tribfev"],2);
         $tributo[3]  += round($work[$Iwork]["w_tribmar"],2);
         $tributo[4]  += round($work[$Iwork]["w_tribabr"],2);
         $tributo[5]  += round($work[$Iwork]["w_tribmai"],2);
         $tributo[6]  += round($work[$Iwork]["w_tribjun"],2);
         $tributo[7]  += round($work[$Iwork]["w_tribjul"],2);
         $tributo[8]  += round($work[$Iwork]["w_tribago"],2);
         $tributo[9]  += round($work[$Iwork]["w_tribset"],2);
         $tributo[10] += round($work[$Iwork]["w_tribout"],2);
         $tributo[11] += round($work[$Iwork]["w_tribnov"],2);
         $tributo[12] += round($work[$Iwork]["w_tribdez"],2);
         $tributo[13] += round($work[$Iwork]["w_tribs13"],2);
	 
         $retido[1]  += round($work[$Iwork]["w_retjan"],2);
         $retido[2]  += round($work[$Iwork]["w_retfev"],2);
         $retido[3]  += round($work[$Iwork]["w_retmar"],2);
         $retido[4]  += round($work[$Iwork]["w_retabr"],2);
         $retido[5]  += round($work[$Iwork]["w_retmai"],2);
         $retido[6]  += round($work[$Iwork]["w_retjun"],2);
         $retido[7]  += round($work[$Iwork]["w_retjul"],2);
         $retido[8]  += round($work[$Iwork]["w_retago"],2);
         $retido[9]  += round($work[$Iwork]["w_retset"],2);
         $retido[10] += round($work[$Iwork]["w_retout"],2);
         $retido[11] += round($work[$Iwork]["w_retnov"],2);
         $retido[12] += round($work[$Iwork]["w_retdez"],2);
         $retido[13] += round($work[$Iwork]["w_rets13"],2);
	 
         $vlrdep[1]  += round($work[$Iwork]["w_depjan"],2);
         $vlrdep[2]  += round($work[$Iwork]["w_depfev"],2);
         $vlrdep[3]  += round($work[$Iwork]["w_depmar"],2);
         $vlrdep[4]  += round($work[$Iwork]["w_depabr"],2);
         $vlrdep[5]  += round($work[$Iwork]["w_depmai"],2);
         $vlrdep[6]  += round($work[$Iwork]["w_depjun"],2);
         $vlrdep[7]  += round($work[$Iwork]["w_depjul"],2);
         $vlrdep[8]  += round($work[$Iwork]["w_depago"],2);
         $vlrdep[9]  += round($work[$Iwork]["w_depset"],2);
         $vlrdep[10] += round($work[$Iwork]["w_depout"],2);
         $vlrdep[11] += round($work[$Iwork]["w_depnov"],2);
         $vlrdep[12] += round($work[$Iwork]["w_depdez"],2);
         $vlrdep[13] += round($work[$Iwork]["w_deps13"],2);

         $vlrpre[1]  += round($work[$Iwork]["w_prejan"],2);
         $vlrpre[2]  += round($work[$Iwork]["w_prefev"],2);
         $vlrpre[3]  += round($work[$Iwork]["w_premar"],2);
         $vlrpre[4]  += round($work[$Iwork]["w_preabr"],2);
         $vlrpre[5]  += round($work[$Iwork]["w_premai"],2);
         $vlrpre[6]  += round($work[$Iwork]["w_prejun"],2);
         $vlrpre[7]  += round($work[$Iwork]["w_prejul"],2);
         $vlrpre[8]  += round($work[$Iwork]["w_preago"],2);
         $vlrpre[9]  += round($work[$Iwork]["w_preset"],2);
         $vlrpre[10] += round($work[$Iwork]["w_preout"],2);
         $vlrpre[11] += round($work[$Iwork]["w_prenov"],2);
         $vlrpre[12] += round($work[$Iwork]["w_predez"],2);
         $vlrpre[13] += round($work[$Iwork]["w_pres13"],2);

         $vlrpen[1]  += round($work[$Iwork]["w_penjan"],2);
         $vlrpen[2]  += round($work[$Iwork]["w_penfev"],2);
         $vlrpen[3]  += round($work[$Iwork]["w_penmar"],2);
         $vlrpen[4]  += round($work[$Iwork]["w_penabr"],2);
         $vlrpen[5]  += round($work[$Iwork]["w_penmai"],2);
         $vlrpen[6]  += round($work[$Iwork]["w_penjun"],2);
         $vlrpen[7]  += round($work[$Iwork]["w_penjul"],2);
         $vlrpen[8]  += round($work[$Iwork]["w_penago"],2);
         $vlrpen[9]  += round($work[$Iwork]["w_penset"],2);
         $vlrpen[10] += round($work[$Iwork]["w_penout"],2);
         $vlrpen[11] += round($work[$Iwork]["w_pennov"],2);
         $vlrpen[12] += round($work[$Iwork]["w_pendez"],2);
         $vlrpen[13] += round($work[$Iwork]["w_pens13"],2);

       if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	   $pdf->addpage('L');
	   $pdf->setfont('arial','b',8);
	   $troca = 0;
       }
       $pdf->ln($alt);
       $pdf->cell(25,$alt,"Nr cgm : ".str_pad($work[$Iwork]["w_numcgm"],6),1,0,"L",1);
       $pdf->cell(80,$alt,"Nome : ".str_pad(trim($work[$Iwork]["w_nome"]),40),1,0,"L",1);
       $pdf->cell(62,$alt,"Reg.: ".trim($work[$Iwork]["w_regist"] ),1,0,"L",1);
       $pdf->cell(25,$alt,"Cpf : ".trim($work[$Iwork]["w_cpf"]),1,1,"L",1);

       $tot_trib  = $work[$Iwork]["w_tribjan"] +$work[$Iwork]["w_tribfev"] +$work[$Iwork]["w_tribmar"] +$work[$Iwork]["w_tribabr"] +$work[$Iwork]["w_tribmai"] +$work[$Iwork]["w_tribjun"];
       $tot_trib += $work[$Iwork]["w_tribjul"] +$work[$Iwork]["w_tribago"] +$work[$Iwork]["w_tribset"] +$work[$Iwork]["w_tribout"] +$work[$Iwork]["w_tribnov"] +$work[$Iwork]["w_tribdez"];
       $tot_geral_trib += $tot_trib;
       $tot_trib_13 += $work[$Iwork]["w_tribs13"];
       
       $tot_dep  = $work[$Iwork]["w_depjan"] +$work[$Iwork]["w_depfev"] +$work[$Iwork]["w_depmar"] +$work[$Iwork]["w_depabr"] +$work[$Iwork]["w_depmai"] +$work[$Iwork]["w_depjun"];
       $tot_dep += $work[$Iwork]["w_depjul"] +$work[$Iwork]["w_depago"] +$work[$Iwork]["w_depset"] +$work[$Iwork]["w_depout"] +$work[$Iwork]["w_depnov"] +$work[$Iwork]["w_depdez"];
       $tot_geral_dep += $tot_dep;
       $tot_dep_13 += $work[$Iwork]["w_deps13"];
       
       $tot_ret  = $work[$Iwork]["w_retjan"] +$work[$Iwork]["w_retfev"] +$work[$Iwork]["w_retmar"] +$work[$Iwork]["w_retabr"] +$work[$Iwork]["w_retmai"] +$work[$Iwork]["w_retjun"];
       $tot_ret += $work[$Iwork]["w_retjul"] +$work[$Iwork]["w_retago"] +$work[$Iwork]["w_retset"] +$work[$Iwork]["w_retout"] +$work[$Iwork]["w_retnov"] +$work[$Iwork]["w_retdez"];
       $tot_geral_ret += $tot_ret;
       $tot_ret_13 += $work[$Iwork]["w_rets13"];
       
       $tot_pre  = $work[$Iwork]["w_prejan"] +$work[$Iwork]["w_prefev"] +$work[$Iwork]["w_premar"] +$work[$Iwork]["w_preabr"] +$work[$Iwork]["w_premai"] +$work[$Iwork]["w_prejun"];
       $tot_pre += $work[$Iwork]["w_prejul"] +$work[$Iwork]["w_preago"] +$work[$Iwork]["w_preset"] +$work[$Iwork]["w_preout"] +$work[$Iwork]["w_prenov"] +$work[$Iwork]["w_predez"];
       $tot_geral_pre += $tot_pre;
       $tot_pre_13 += $work[$Iwork]["w_pres13"];
       
       $tot_pen  = $work[$Iwork]["w_penjan"] +$work[$Iwork]["w_penfev"] +$work[$Iwork]["w_penmar"] +$work[$Iwork]["w_penabr"] +$work[$Iwork]["w_penmai"] +$work[$Iwork]["w_penjun"];
       $tot_pen += $work[$Iwork]["w_penjul"] +$work[$Iwork]["w_penago"] +$work[$Iwork]["w_penset"] +$work[$Iwork]["w_penout"] +$work[$Iwork]["w_pennov"] +$work[$Iwork]["w_pendez"];
       $tot_geral_pen += $tot_pen;
       $tot_pen_13 += $work[$Iwork]["w_pens13"];
       
       $pdf->ln($alt);
       $pdf->cell(10,$alt, "Mes",1,0,"C",1);
       $pdf->cell(16,$alt, "Tributo",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Previd",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Depend",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Pensão",1,0,"C",1);
       $pdf->cell(16,$alt, "Imposto",1,0,"C",1);
       
       $pdf->cell(10,$alt, "Mes",1,0,"C",1);
       $pdf->cell(16,$alt, "Tributo",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Previd",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Depend",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Pensão",1,0,"C",1);
       $pdf->cell(16,$alt, "Imposto",1,0,"C",1);
       
       $pdf->cell(10,$alt, "Mes",1,0,"C",1);
       $pdf->cell(16,$alt, "Tributo",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Previd",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Depend",1,0,"C",1);
       $pdf->cell(16,$alt, "Vlr Pensão",1,0,"C",1);
       $pdf->cell(16,$alt, "Imposto",1,1,"C",1);

       $pdf->cell(10,$alt, "Jan",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribjan"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_prejan"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depjan"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penjan"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retjan"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Fev",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribfev"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_prefev"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depfev"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penfev"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retfev"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Mar",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribmar"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_premar"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depmar"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penmar"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retmar"]  ,'f'),0,1,"R",0);
              
       $pdf->cell(10,$alt, "Abr",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribabr"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_preabr"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depabr"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penabr"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retabr"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Mai",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribmai"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_premai"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depmai"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penmai"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retmai"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Jun",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribjun"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_prejun"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depjun"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penjun"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retjun"]  ,'f'),0,1,"R",0);
             
       $pdf->cell(10,$alt, "Jul",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribjul"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_prejul"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depjul"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penjul"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retjul"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Ago",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribago"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_preago"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depago"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penago"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retago"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Set",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribset"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_preset"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depset"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penset"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retset"]  ,'f'),0,1,"R",0);
             
       $pdf->cell(10,$alt, "Out",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribout"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_preout"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depout"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_penout"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retout"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Nov",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribnov"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_prenov"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depnov"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_pennov"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retnov"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Dez",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribdez"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_predez"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_depdez"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_pendez"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_retdez"]  ,'f'),0,1,"R",0);
       
       $pdf->cell(10,$alt, "13s",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_tribs13"] ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_pres13"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_deps13"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_pens13"]  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $work[$Iwork]["w_rets13"]  ,'f'),0,0,"R",0);

       $pdf->cell(10,$alt, "Totais",1,0,"C",1);
       $pdf->cell(16,$alt,db_formatar( $tot_trib ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $tot_pre  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $tot_dep  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $tot_pen  ,'f'),0,0,"R",0);
       $pdf->cell(16,$alt,db_formatar( $tot_ret  ,'f'),0,1,"R",0);
       
         }
      }
      $seq += 1;
      $lin  = db_str($seq,8,0,"0");                                                     // sequencial do arquivo
      $lin .= "3";                                                                      // identificacao do registro
      $lin .= str_pad($d08_cgc,14,'0');                                                 // cnpj
      $lin .= str_pad(substr($codret,0,4),4,'0',0);                                     // codigo de retencao
      $lin .= db_str($seq-2,8,0,"0");                                                   // total de registros do tipo 2
      $lin .= bb_space(67);                                                             // brancos
      $lin .= valor_12h($tributo[1])  . valor_12h($vlrdep[1])  . valor_12h($retido[1]);   // janeiro
      $lin .= valor_12h($tributo[2])  . valor_12h($vlrdep[2])  . valor_12h($retido[2]);   // fevereiro
      $lin .= valor_12h($tributo[3])  . valor_12h($vlrdep[3])  . valor_12h($retido[3]);   // marco
      $lin .= valor_12h($tributo[4])  . valor_12h($vlrdep[4])  . valor_12h($retido[4]);   // abril
      $lin .= valor_12h($tributo[5])  . valor_12h($vlrdep[5])  . valor_12h($retido[5]);   // maio
      $lin .= valor_12h($tributo[6])  . valor_12h($vlrdep[6])  . valor_12h($retido[6]);   // junho
      $lin .= valor_12h($tributo[7])  . valor_12h($vlrdep[7])  . valor_12h($retido[7]);   // julho
      $lin .= valor_12h($tributo[8])  . valor_12h($vlrdep[8])  . valor_12h($retido[8]);   // agosto
      $lin .= valor_12h($tributo[9])  . valor_12h($vlrdep[9])  . valor_12h($retido[9]);   // setembro
      $lin .= valor_12h($tributo[10]) . valor_12h($vlrdep[10]) . valor_12h($retido[10]);// outubro
      $lin .= valor_12h($tributo[11]) . valor_12h($vlrdep[11]) . valor_12h($retido[11]);// novembro
      $lin .= valor_12h($tributo[12]) . valor_12h($vlrdep[12]) . valor_12h($retido[12]);// dezembro
      $lin .= valor_12h($tributo[13]) . valor_12h($vlrdep[13]) . valor_12h($retido[13]);// 13o salario
      $lin .= bb_space(42);
      $lin .= "9";                      // da 730 a 730;
      fputs($arquivo,$lin."\n");

      fclose($arquivo);
  
      if( $tot_geral_trib > 0 ){
         $pdf->ln($alt);
         $pdf->cell(20,$alt, "Totais Gerais: ",1,0,"C",1);
         $pdf->ln($alt);
         $pdf->cell(15,$alt, "13s",1,0,"C",1);
         $pdf->cell(18,$alt, db_formatar($tot_trib_13 ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_pre_13  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_dep_13  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_pen_13  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_ret_13  ,'f'),0,1,"R",0);
         $pdf->cell(15,$alt, "Totais",1,0,"C",1);
         $pdf->cell(18,$alt, db_formatar($tot_geral_trib ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_geral_pre  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_geral_dep  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_geral_pen  ,'f'),0,0,"R",0);
         $pdf->cell(18,$alt, db_formatar($tot_geral_ret  ,'f'),0,1,"R",0);
      }
//      echo "<BR> passou aqui!!";

    $pdf->Output('/tmp/dirf.pdf',false,true);

}

function valor_12h($numero){
   return str_replace('.','',str_pad(number_format($numero, 2, ".", ""), 16, "0", STR_PAD_LEFT));
}


function cria_work_12h(){
  
   global $arquivo;
   $arquivo = "dirf";
   $sql_create_table_dirf   = "create temporary table dirf( w_numcgm integer default 0 ,
                                                  w_nome char(40) ,
                                                  w_cpf char(14) ,
                                                  w_tribjan float8 default 0 ,
                                                  w_tribfev float8 default 0 ,
                                                  w_tribmar float8 default 0 ,
                                                  w_tribabr float8 default 0 ,
                                                  w_tribmai float8 default 0 ,
                                                  w_tribjun float8 default 0 ,
                                                  w_tribjul float8 default 0 ,
                                                  w_tribago float8 default 0 ,
                                                  w_tribset float8 default 0 ,
                                                  w_tribout float8 default 0 ,
                                                  w_tribnov float8 default 0 ,
                                                  w_tribdez float8 default 0 ,
                                                  w_tribs13 float8 default 0 ,
                                                  w_retjan float8 default 0 ,
                                                  w_retfev float8 default 0 ,
                                                  w_retmar float8 default 0 ,
                                                  w_retabr float8 default 0 ,
                                                  w_retmai float8 default 0 ,
                                                  w_retjun float8 default 0 ,
                                                  w_retjul float8 default 0 ,
                                                  w_retago float8 default 0 ,
                                                  w_retset float8 default 0 ,
                                                  w_retout float8 default 0 ,
                                                  w_retnov float8 default 0 ,
                                                  w_retdez float8 default 0 ,
                                                  w_rets13 float8 default 0 ,
                                                  w_depjan float8 default 0 ,
                                                  w_depfev float8 default 0 ,
                                                  w_depmar float8 default 0 ,
                                                  w_depabr float8 default 0 ,
                                                  w_depmai float8 default 0 ,
                                                  w_depjun float8 default 0 ,
                                                  w_depjul float8 default 0 ,
                                                  w_depago float8 default 0 ,
                                                  w_depset float8 default 0 ,
                                                  w_depout float8 default 0 ,
                                                  w_depnov float8 default 0 ,
                                                  w_depdez float8 default 0 ,
                                                  w_deps13 float8 default 0 ,
                                                  w_prejan float8 default 0 ,
                                                  w_prefev float8 default 0 ,
                                                  w_premar float8 default 0 ,
                                                  w_preabr float8 default 0 ,
                                                  w_premai float8 default 0 ,
                                                  w_prejun float8 default 0 ,
                                                  w_prejul float8 default 0 ,
                                                  w_preago float8 default 0 ,
                                                  w_preset float8 default 0 ,
                                                  w_preout float8 default 0 ,
                                                  w_prenov float8 default 0 ,
                                                  w_predez float8 default 0 ,
                                                  w_pres13 float8 default 0 ,
                                                  w_penjan float8 default 0 ,
                                                  w_penfev float8 default 0 ,
                                                  w_penmar float8 default 0 ,
                                                  w_penabr float8 default 0 ,
                                                  w_penmai float8 default 0 ,
                                                  w_penjun float8 default 0 ,
                                                  w_penjul float8 default 0 ,
                                                  w_penago float8 default 0 ,
                                                  w_penset float8 default 0 ,
                                                  w_penout float8 default 0 ,
                                                  w_pennov float8 default 0 ,
                                                  w_pendez float8 default 0 ,
                                                  w_pens13 float8 default 0 ,
                                                  w_regist varchar(80) );";   
    $result_create_table_dirf      = pg_exec($sql_create_table_dirf);
    $result_create_indexes_temp    = pg_exec("create index work_anousu on dirf(w_cpf); ");
}

?>
