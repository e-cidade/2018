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
include("classes/db_empautoriza_classe.php");
include("classes/db_empautitem_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_pctipocompra_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_db_config_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_db_departorg_classe.php");
require_once("classes/db_empautitempcprocitem_classe.php");

$clempautoriza          = new cl_empautoriza;
$clempautitem           = new cl_empautitem;
$clcgm                  = new cl_cgm;
$cldb_usuarios          = new cl_db_usuarios;
$clpctipocompra         = new cl_pctipocompra;
$clpcprocitem           = new cl_pcprocitem;
$cldb_config            = new cl_db_config;
$clorcelemento          = new cl_orcelemento;
$cldb_departorg         = new cl_db_departorg;
$clrotulo               = new rotulocampo;
$clempautitempcprocitem = new cl_empautitempcprocitem;

$clrotulo->label("e55_item");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e55_descr");
$clrotulo->label("e55_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("e55_sequen");
$clrotulo->label("e55_quant");
$clrotulo->label("e55_vltot");
$clempautoriza->rotulo->label();
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$and    = "";
$where  = "";
$info   = "";
function monta_where($inp="",$par="",$cod="",$descr_inp=""){
  global $and;
  $param_autoriza = "";
  $where_autorizacao = "";
  if(isset($cod) && trim($cod)!=""){
    $cod_autoriza = split("-",$cod);
    $ini_autoriza = $cod_autoriza[0];
    $fim_autoriza = $cod_autoriza[1];
    if($ini_autoriza!="NaN" && $fim_autoriza!="NaN"){
      $where_autorizacao .= $and.$descr_inp." between ".$ini_autoriza." and ".$fim_autoriza;
      $and = " and ";
    }else if($fim_autoriza=="NaN"){
      $where_autorizacao .= $and.$descr_inp." >= ". $ini_autoriza; 
      $and = " and ";
    }else if($ini_autoriza=="NaN"){
      $where_autorizacao .= $and.$descr_inp." <= ". $fim_autoriza; 
      $and = " and ";
    }
  }
  if(isset($inp) && trim($inp)!=""){
    if($par == "S"){
      $param_autoriza = " in ";
    }else if($par == "N"){
      $param_autoriza = " not in ";
    }
    $where_autorizacao .= $and.$descr_inp.$param_autoriza." (".$inp.") ";
    $and = " and ";
  }
  return $where_autorizacao;
}

$autori = monta_where($inp_autoriza ,$par_autoriza ,$cod_autoriza ," e54_autori ");
$dt_ini = "";
$dt_fim = "";
if(isset($e54_emissINI_dia) && trim($e54_emissINI_dia)!="" && isset($e54_emissINI_mes) && trim($e54_emissINI_mes)!="" && isset($e54_emissINI_ano) && trim($e54_emissINI_ano)!=""){
  $dt_ini = $e54_emissINI_ano."-".$e54_emissINI_mes."-".$e54_emissINI_dia;
}
if(isset($e54_emissFIM_dia) && trim($e54_emissFIM_dia)!="" && isset($e54_emissFIM_mes) && trim($e54_emissFIM_mes)!="" && isset($e54_emissFIM_ano) && trim($e54_emissFIM_ano)!=""){
  $dt_fim = $e54_emissFIM_ano."-".$e54_emissFIM_mes."-".$e54_emissFIM_dia;
}
if(isset($dt_ini) && trim($dt_ini)!="" || isset($dt_fim) && trim($dt_fim)!=""){
  if(isset($dt_ini) && isset($dt_fim)){
    $autori = $autori . $and ." e54_emiss between '".$dt_ini."' and '".$dt_fim."' ";
  }else if(isset($dt_ini)){
    $autori = $autori . $and ." e54_emiss >= '".$dt_ini."' ";
  }else if(isset($dt_fim)){
    $autori = $autori . $and ." e54_emiss <= '". $dt_fim."' ";
  }
  $and = " and ";
}
$cgm    = monta_where($inp_cgm      ,$par_cgm      ,$cod_cgm      ," e54_numcgm ");
$usuari = monta_where($inp_usuarios ,$par_usuarios ,$cod_usuarios ," e54_login  ");
$tipcom = monta_where($inp_tipcompra,$par_tipcompra,$cod_tipcompra," e54_codcom ");
$config = monta_where($inp_config   ,$par_config   ,$cod_config   ," e54_instit ");
$depart = monta_where($inp_depart   ,$par_depart   ,$cod_depart   ," e54_depto ");
if(isset($autori) && trim($autori)!=""){
  $where .= $autori;
}
if(isset($cgm) && trim($cgm)!=""){
  $where .= $cgm;
}
if(isset($usuari) && trim($usuari)!=""){
  $where .= $usuari;
}
if(isset($tipcom) && trim($tipcom)!=""){
  $where .= $tipcom;
}
if(isset($config) && trim($config)!=""){
  $where .= $config;
}
if(isset($depart) && trim($depart)!=""){
  $where .= $depart;
  }

$a="";
if ($where!=""){
   $a="and";
}
if ($tipo=='E'){
  $where.=" $a e61_numemp is not null ";
  $a="and";
  $infor="EMPENHADAS";
}else if ($tipo=='N'){
  $where.=" $a e61_numemp is null ";
  $a="and";
  $infor="NÃO EMPENHADAS";
}
if ($anula=='A'){
  $where.=" $a e54_anulad is not null ";
  $a="and";
  $inform="ANULADAS";
}else if ($anula=='N'){
  $inform="NÃO ANULADAS";
  $where.=" $a e54_anulad is null ";
  $a="and";
}
  

if($ordem == "e54_autori"){
  $info = "CÓDIGO DAS AUTORIZAÇÕES";
}else if ($ordem == "e54_emiss"){
  $info = "DATA DE EMISSÃO";
}else if ($ordem == "z01_nome"){
  $info = "NOME DO CREDOR";
}else if ($ordem == "nome"){
  $info = "NOME DO USUÁRIO";
}else if ($ordem == "pc50_descr"){
  $info = "TIPO DE COMPRA";
}else if ($ordem == "nomeinst"){
  $info = "INSTITUIÇÃO";
}
//die($where);
//die($clempautoriza->sql_query(null,"e54_autori,e54_emiss,z01_nome,nome,pc50_descr,nomeinst",$ordem,$where));


$result_empautoriza = $clempautoriza->sql_record($clempautoriza->sql_query_deptoautori(null,"distinct e54_autori,e54_emiss,e54_anousu,descrdepto as departamento,coddepto as codigododepartamento,z01_nome,nome,pc50_descr,nomeinst,e60_codemp as codemp,e60_numemp as numemp,fc_estruturaldotacao(e56_anousu,e56_coddot) as estrutural",$ordem,"$where"));

//db_criatabela($result_empautoriza);
//exit;
$numrows = $clempautoriza->numrows;
if($numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas autorizações com os dados informados.');
}
///////////////////////////////////////////////////////////////////////
$head3 = "AUTORIZAÇÕES DE EMPENHO";
if ($dt_ini == "" and $dt_fim == "") {
  $head4 = "SEM PERÍODO ESPECIFICADO";
} elseif ($dt_ini != "" and $dt_fim != "") {
  $head4 = "PERÍODO: " . db_formatar($dt_ini,'d') . " à " . db_formatar($dt_fim,'d');
} elseif ($dt_ini != "" and $dt_fim == "") {
  $head4 = "PERÍODO: A PARTIR DE " . db_formatar($dt_ini,'d');
} elseif ($dt_ini == "" and $dt_fim != "") {
  $head4 = "PERÍODO: ATÉ " . db_formatar($dt_fim,'d');
}
$head5 = @$infor;
$head6 = @$inform;
$head7 = "ORDEM DE SELEÇÃO POR ".$info;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 4;
$total = 0;
$c = 1;
$quanttot=0;
$valortot=0;
$conttot=0;
$totalzao=0;
//    $pdf->addpage("L");    
for($i=0;$i<$numrows;$i++){
  db_fieldsmemory($result_empautoriza,$i,true);
  if($c==1){$c=0;}else{$c=1;}
  if($pdf->gety() > $pdf->h - 32 || $troca != 0 ){
    $pdf->addpage("L");    
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,"Autorização",1,0,"C",1);
    $pdf->cell(25,$alt,"Emissão",1,0,"C",1);
    $pdf->cell(75,$alt,"Credor",1,0,"C",1);
    $pdf->cell(50,$alt,"Tipo de compra",1,0,"C",1);
    $pdf->cell(25,$alt,"Empenho",1,0,"C",1);
    $pdf->cell(25,$alt,"PC",1,0,"C",1);
    $pdf->cell(25,$alt,"Solicitação",1,0,"C",1);
    $pdf->cell(25,$alt,"Valor Total",1,1,"C",1);

    $pdf->cell(53,$alt,"Estrutural"  ,1,0,"C",1);
    $pdf->cell(60,$alt,"Elemento"    ,1,0,"C",1);
    $pdf->cell(60,$alt,"Secretaria"  ,1,0,"C",1); 
    $pdf->cell(60,$alt,"Departamento",1,0,"C",1); 
    $pdf->cell(42,$alt,"Usuário"     ,1,1,"C",1);

    if ($listar=='s'){
      $pdf->cell(15,$alt,$RLe55_item       ,1,0,"C",1);
      $pdf->cell(58,$alt,$RLpc01_descrmater,1,0,"C",1);
      $pdf->cell(58,$alt,$RLe55_descr	   ,1,0,"C",1);
      $pdf->cell(14,$alt,"Sub-ele."	   ,1,0,"C",1);
      $pdf->cell(22,$alt,"Estrutural"	   ,1,0,"C",1);
      $pdf->cell(58,$alt,$RLo56_descr	   ,1,0,"C",1); 
      $pdf->cell(15,$alt,$RLe55_sequen	   ,1,0,"C",1);
      $pdf->cell(15,$alt,"Quant."	   ,1,0,"C",1);
      $pdf->cell(20,$alt,'Valor'	   ,1,1,"C",1);
    }
    $c = 0;
    $troca=0;
  }
  
  $result_valortot=$clempautitem->sql_record($clempautitem->sql_query_file($e54_autori)); 
  $valortot=0;
  for($y=0;$y<$clempautitem->numrows;$y++){
    db_fieldsmemory($result_valortot,$y);
    $valortot=$valortot+$e55_vltot;
  }
  //dados empautoriza
  //-----------------
  $pdf->setfont('arial','b',7);
  $pdf->cell(25,$alt,@$e54_autori             ,0,0,"C",$c);
  $pdf->cell(25,$alt,@$e54_emiss              ,0,0,"C",$c);
  $pdf->cell(75,$alt,substr(@$z01_nome,0,31)  ,0,0,"L",$c);
  $pdf->cell(50,$alt,substr(@$pc50_descr,0,31),0,0,"L",$c);
  $pdf->cell(25,$alt,@$codemp                 ,0,0,"C",$c);
  $result_valortot=$clempautitem->sql_record($clempautitem->sql_query_file($e54_autori));
  $nempautitem = $clempautitem->numrows;
  for($y=0;$y<$clempautitem->numrows;$y++){  
    db_fieldsmemory($result_valortot,$y);
    break;
  }
  
  
  $sSqlEmpAutItemPcProcItem = $clempautitempcprocitem->sql_query(null, "pc81_codproc", null, "e54_autori = {$e54_autori}");
  $result_pcprocitem        = $clempautitempcprocitem->sql_record($sSqlEmpAutItemPcProcItem);
  
  if ($clempautitempcprocitem->numrows > 0) {
    db_fieldsmemory($result_pcprocitem,0);
    $sql_solicita = "select fc_solproc($pc81_codproc)";
    $result_solicita = pg_exec($sql_solicita);
    if (pg_numrows($result_solicita) > 0) {
      db_fieldsmemory($result_solicita,0);
      $pdf->cell(25,$alt,$fc_solproc          ,0,0,"C",$c);
    } else {
      $pdf->cell(25,$alt,""                   ,0,0,"C",$c);
    }
  } else {
    $pdf->cell(25,$alt,""                   ,0,0,"C",$c);
  }

  if ($clempautitempcprocitem->numrows > 0) {
    $pdf->cell(25,$alt,@$pc81_codproc         ,0,0,"C",$c);
  } else {
    $pdf->cell(25,$alt,""                     ,0,0,"C",$c);
  }
  
  $pdf->cell(25,$alt,db_formatar(@$valortot,'f'),0,1,"R",$c);
  $totalzao+=$valortot;
  
  $arr_estrutural = split("\.",$estrutural);
  $estrut_pesquisa= $arr_estrutural[6];
  $result_estruturalautori = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"o56_descr as elementoautori",""," o56_elemento='$estrut_pesquisa' and o56_anousu = $e54_anousu"));
  if($clorcelemento->numrows>0){
    db_fieldsmemory($result_estruturalautori,0);
  }

  $result_secretaria = $cldb_departorg->sql_record($cldb_departorg->sql_query_orgunid($codigododepartamento,$e54_anousu,"o40_descr as secretaria"));
  if($cldb_departorg->numrows>0){
    db_fieldsmemory($result_secretaria,0);
  }
  $pdf->cell(53,$alt,$estrutural                  ,0,0,"C",$c);
  $pdf->cell(60,$alt,substr(@$elementoautori,0,38),0,0,"L",$c);
  $pdf->cell(60,$alt,substr(@$secretaria,0,38)    ,0,0,"L",$c);
  $pdf->cell(60,$alt,substr(@$departamento,0,38)  ,0,0,"L",$c);
  $pdf->cell(42,$alt,substr(@$nome,0,25)          ,0,1,"L",$c);

  //------------------
  if ($listar=='s'){
  	//die($clempautitem->sql_query($e54_autori,null,"e55_item,pc01_descrmater,e55_descr,e55_codele,o56_descr,e55_sequen,e55_quant,e55_vltot"));
    $result_itens=$clempautitem->sql_record($clempautitem->sql_query($e54_autori,null,"e55_item,pc01_descrmater,e55_descr,e55_codele,o56_descr,e55_sequen,e55_quant,e55_vltot")); 
  //  fc_estruturaldotacao(e56_anousu,e56_coddot);
    $quanttot=0;
    $valortot=0;
    $conttot=0;
    for($y=0;$y<$clempautitem->numrows;$y++){
      if($pdf->gety() > $pdf->h - 32 || $troca != 0 ){
	$pdf->addpage("L");    
	$pdf->setfont('arial','b',8);
	$pdf->cell(25,$alt,"Autorização"    ,1,0,"L",1);
	$pdf->cell(25,$alt,"Emissão"        ,1,0,"C",1);
	$pdf->cell(75,$alt,"Credor"         ,1,0,"C",1);
	$pdf->cell(75,$alt,"Tipo de compra" ,1,0,"C",1);
	$pdf->cell(25,$alt,"Empenho"        ,1,0,"C",1);
	$pdf->cell(25,$alt,"Número"         ,1,0,"C",1);
	$pdf->cell(25,$alt,"Valor Total"    ,1,1,"C",1);

	$pdf->cell(53,$alt,"Estrutural"  ,1,0,"C",1);
	$pdf->cell(60,$alt,"Elemento"    ,1,0,"C",1);
	$pdf->cell(60,$alt,"Secretaria"  ,1,0,"C",1); 
	$pdf->cell(60,$alt,"Departamento",1,0,"C",1); 
	$pdf->cell(42,$alt,"Usuário"     ,1,1,"C",1);
	
	$pdf->cell(15,$alt,$RLe55_item       ,1,0,"C",1);
	$pdf->cell(58,$alt,$RLpc01_descrmater,1,0,"C",1);
	$pdf->cell(58,$alt,$RLe55_descr      ,1,0,"C",1);
	$pdf->cell(14,$alt,"Sub-Ele."        ,1,0,"C",1);
	$pdf->cell(22,$alt,"Estrutural"	     ,1,0,"C",1);
	$pdf->cell(58,$alt,$RLo56_descr	     ,1,0,"C",1); 
	$pdf->cell(15,$alt,$RLe55_sequen     ,1,0,"C",1);
	$pdf->cell(15,$alt,"Quant."          ,1,0,"C",1);
	$pdf->cell(20,$alt,'Valor'           ,1,1,"C",1);
	
	$c = 0;
	$troca=0;
      }
      db_fieldsmemory($result_itens,$y);
      //die($clorcelemento->sql_query_file($e55_codele,$e54_anousu,"o56_elemento as elementoitem"));
      $result_elementoitem = $clorcelemento->sql_record($clorcelemento->sql_query_file($e55_codele,$e54_anousu,"o56_elemento as elementoitem"));
      db_fieldsmemory($result_elementoitem,0);
      $pdf->setfont('arial','',7);
      $pdf->cell(15,$alt,$e55_item                    ,0,0,"C",$c);
      $pdf->cell(58,$alt,substr($pc01_descrmater,0,35),0,0,"L",$c);
      $pdf->cell(58,$alt,substr($e55_descr,0,35)      ,0,0,"L",$c);
      $pdf->cell(14,$alt,$e55_codele                  ,0,0,"C",$c);
      $pdf->cell(22,$alt,db_formatar($elementoitem,"elemento"),0,0,"C",$c);
      $pdf->cell(58,$alt,substr(@$o56_descr,0,35)     ,0,0,"L",$c); 
      $pdf->cell(15,$alt,$e55_sequen                  ,0,0,"C",$c);
      $pdf->cell(15,$alt,$e55_quant                   ,0,0,"C",$c);
      $pdf->cell(20,$alt,db_formatar($e55_vltot,'f')  ,0,1,"R",$c);
      $quanttot=$quanttot+$e55_quant;
      $valortot=$valortot+$e55_vltot;
      $conttot++;
    }
    if ($conttot>1){
      $pdf->setfont('arial','b',7);
      $pdf->cell(240,$alt,'TOTAL:',0,0,"R",$c);
      $pdf->cell(15,$alt,$quanttot,0,0,"C",$c);
      $pdf->cell(20,$alt,db_formatar($valortot,'f'),0,1,"R",$c);
    }
  }
  $pdf->cell(275,2,"","B",1,"R",$c);
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL DE AUTORIZAÇÕES: '.$total,"T",1,"L",0);
$pdf->cell(100,$alt,'TOTAL GERAL: ' . db_formatar($totalzao,'f'),"T",1,"",0);
//$pdf->addpage("L");

$result_totestr=$clempautitem->sql_record($clempautitem->sql_query_autoridot(null,null,"fc_estruturaldotacao(e56_anousu,e56_coddot)as estrutural, sum(e55_vltot) as valor","","$where  group by estrutural"));
$p=0;
$valortotalele=0;
$totalreg=0;
$troca1=1;
for($ii = 0; $ii < $clempautitem->numrows;$ii++){
  db_fieldsmemory($result_totestr,$ii);
  if($p==1){$p=0;}else{$p=1;}
  if($pdf->gety() > $pdf->h - 32 || $troca1 != 0 ){
    $pdf->addpage("L");    
    $pdf->setfont('arial','b',8);
    if ($troca1==1){
      $pdf->cell(120,$alt,"Totalização por Estrutural",1,1,"C",1);
    }
    $pdf->cell(60,$alt,"Estrutural",1,0,"C",1);
    $pdf->cell(60,$alt,"Valor",1,1,"C",1);
    $p= 0;
    $troca1=0;
  }
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(60,$alt,@$estrutural,0,0,"L",$p);
  $pdf->cell(60,$alt,db_formatar(@$valor,'f'),0,1,"R",$p);
  $valortotalele = $valortotalele + $valor;
  $totalreg++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(120,$alt,'TOTAL DE REGISTROS :    '.$totalreg.'     VALOR TOTAL :   '.db_formatar($valortotalele,"f"),"T",1,"R",0);


$pdf->addpage("L");
$pdf->ln(5);

$total_autori = 0;
$msg_nautoriza = "";
if($par_autoriza=="N"){$msg_nautoriza=" NÃO ";}
if(isset($inp_autoriza) && trim($inp_autoriza)!=""){
	//die($clempautoriza->sql_query_file(null,"e54_autori,e54_emiss","e54_autori"," e54_autori in (".$inp_autoriza.")"));
  $result_selautori = $clempautoriza->sql_record($clempautoriza->sql_query_file(null,"e54_autori,e54_emiss","e54_autori"," e54_autori in (".$inp_autoriza.")"));
  $numrows_selautori = $clempautoriza->numrows;
  $c=1;
  if($numrows_selautori){
    for($i=0;$i<$numrows_selautori;$i++){
      if($c==1){$c=0;}else{$c=1;}
      db_fieldsmemory($result_selautori,$i,true);
      if($pdf->gety() > $pdf->h - 32 || $i == 0 ){
        if($pdf->gety()>$pdf->h - 32){
	  $pdf->addpage("L");    
        }
	$pdf->setfont('arial','b',8);
	$pdf->cell(90,$alt,"AUTORIZAÇÕES $msg_nautoriza SELECIONADAS",1,1,"C",1);
	$pdf->cell(45,$alt,"Código",1,0,"C",1);
	$pdf->cell(45,$alt,"Emissão",1,1,"C",1);
	$c = 0;
	$troca=0;
      }
      //dados empautoriza
      $pdf->setfont('arial','',7);
      $pdf->cell(45,$alt,@$e54_autori,"L",0,"C",$c);
      $pdf->cell(45,$alt,@$e54_emiss,"R",1,"C",$c);
      $total_autori++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,"TOTAL DE AUTORIZAÇÕES $msg_nautoriza SELECIONADAS  :  ".$total_autori,"T",1,"L",0);
  }
}else{
  $pdf->cell(90,$alt,"TODAS AUTORIZAÇÕES SELECIONADAS",1,1,"C",1);
}
$pdf->ln(5);
$total_cgm = 0;
if(isset($inp_cgm) && trim($inp_cgm)!=""){
  $result_selcgm = $clcgm->sql_record($clcgm->sql_query_file(null,"z01_numcgm,z01_nome","z01_numcgm"," z01_numcgm in (".$inp_cgm.")"));
  $numrows_selcgm = $clcgm->numrows;
  $c=1;
  if($numrows_selcgm){
    for($i=0;$i<$numrows_selcgm;$i++){
      if($c==1){$c=0;}else{$c=1;}
      $msg_ncgm = "";
      if($par_cgm=="N"){$msg_ncgm=" NÃO ";}
      db_fieldsmemory($result_selcgm,$i,true);
      if($pdf->gety() > $pdf->h - 32 || $i == 0 ){
        if($pdf->gety()>$pdf->h - 32){
	  $pdf->addpage("L");    
        }
	$pdf->setfont('arial','b',8);
	$pdf->cell(90,$alt,"CREDORES $msg_ncgm SELECIONADOS",1,1,"C",1);
	$pdf->cell(20,$alt,"CGM",1,0,"C",1);
	$pdf->cell(70,$alt,"NOME",1,1,"C",1);
	$c = 0;
	$troca=0;
      }
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,@$z01_numcgm,"L",0,"L",$c);
      $pdf->cell(70,$alt,@$z01_nome,"R",1,"L",$c);
      $total_cgm++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,"TOTAL DE CREDORES $msg_ncgm SELECIONADOS  :  ".$total_cgm,"T",1,"L",0);
  }
}else{
  $pdf->cell(90,$alt,"TODOS CREDORES SELECIONADOS",1,1,"C",1);
}
$pdf->ln(5);
$total_usuarios = 0;
if(isset($inp_usuarios) && trim($inp_usuarios)!=""){
  $result_selusuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(null,"id_usuario,nome","id_usuario"," id_usuario in (".$inp_usuarios.")"));
  $numrows_selusuarios = $cldb_usuarios->numrows;
  $c=1;
  if($numrows_selusuarios){
    for($i=0;$i<$numrows_selusuarios;$i++){
      if($c==1){$c=0;}else{$c=1;}
      $msg_nusuarios = "";
      if($par_usuarios=="N"){$msg_nusuarios=" NÃO ";}
      db_fieldsmemory($result_selusuarios,$i,true);
      if($pdf->gety() > $pdf->h - 32 || $i == 0 ){
        if($pdf->gety()>$pdf->h - 32){
	  $pdf->addpage("L");    
        }
	$pdf->setfont('arial','b',8);
	$pdf->cell(90,$alt,"USUÁRIOS $msg_nusuarios SELECIONADOS",1,1,"C",1);
	$pdf->cell(20,$alt,"ID usuário",1,0,"C",1);
	$pdf->cell(70,$alt,"Nome",1,1,"C",1);
	$c = 0;
	$troca=0;
      }
      //dados empautoriza
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,@$id_usuario,"L",0,"C",$c);
      $pdf->cell(70,$alt,substr(@$nome,0,45),"R",1,"L",$c);
      $total_usuarios++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,"TOTAL DE USUÁRIOS $msg_nusuarios SELECIONADOS  :  ".$total_usuarios,"T",1,"L",0);
  }
}else{
  $pdf->cell(90,$alt,"TODOS USUÁRIOS SELECIONADOS",1,1,"C",1);
}
$pdf->ln(5);
$total_tipcompra = 0;
if(isset($inp_tipcompra) && trim($inp_tipcompra)!=""){
  $result_seltipcompra = $clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom,pc50_descr","pc50_codcom"," pc50_codcom in (".$inp_tipcompra.")"));
  $numrows_seltipcompra = $clpctipocompra->numrows;
  $c=1;
  if($numrows_seltipcompra){
    for($i=0;$i<$numrows_seltipcompra;$i++){
      if($c==1){$c=0;}else{$c=1;}
      $msg_ntipcompra = "";
      if($par_tipcompra=="N"){$msg_ntipcompra=" NÃO ";}
      db_fieldsmemory($result_seltipcompra,$i,true);
      if($pdf->gety() > $pdf->h - 32 || $i == 0 ){
        if($pdf->gety()>$pdf->h - 32){
	  $pdf->addpage("L");    
        }
	$pdf->setfont('arial','b',8);
	$pdf->cell(90,$alt,"TIPOS DE COMPRA $msg_nautoriza SELECIONADOS",1,1,"C",1);
	$pdf->cell(20,$alt,"Código",1,0,"C",1);
	$pdf->cell(70,$alt,"Descrição",1,1,"C",1);
	$c = 0;
	$troca=0;
      }
      //dados empautoriza
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,@$pc50_codcom,"L",0,"C",$c);
      $pdf->cell(70,$alt,substr(@$pc50_descr,0,45),"R",1,"L",$c);
      $total_tipcompra++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,"TOTAL DE TIPOS DE COMPRA $msg_nautoriza SELECIONADOS  :  ".$total_tipcompra,"T",1,"L",0);
  }
}else{
  $pdf->cell(90,$alt,"TODOS TIPOS DE COMPRA SELECIONADOS",1,1,"C",1);
}
$pdf->ln(5);
$total_config = 0;
if(isset($inp_config) && trim($inp_config)!=""){
  $result_selconfig = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo,nomeinst","codigo"," codigo in (".$inp_config.")"));
  $numrows_selconfig = $cldb_config->numrows;
  $c=1;
  if($numrows_selconfig){
    for($i=0;$i<$numrows_selconfig;$i++){
      if($c==1){$c=0;}else{$c=1;}
      $msg_nconfig = "";
      if($par_config=="N"){$msg_nconfig=" NÃO ";}
      db_fieldsmemory($result_selconfig,$i,true);
      if($pdf->gety() > $pdf->h - 32 || $i == 0 ){
        if($pdf->gety()>$pdf->h - 32){
	  $pdf->addpage("L");    
        }
	$pdf->setfont('arial','b',8);
	$pdf->cell(90,$alt,"INSTITUIÇÕES $msg_ncgm SELECIONADAS",1,1,"C",1);
	$pdf->cell(20,$alt,"Código",1,0,"C",1);
	$pdf->cell(70,$alt,"Nome",1,1,"C",1);
	$c = 0;
	$troca=0;
      }
      //dados empautoriza
      $pdf->setfont('arial','',7);
      $pdf->cell(20,$alt,@$codigo,"L",0,"C",$c);
      $pdf->cell(70,$alt,substr(@$nomeinst,0,45),"R",1,"L",$c);
      $total_config++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(90,$alt,"TOTAL DE INSTITUIÇÕES $msg_nconfig SELECIONADAS  :  ".$total_config,"T",1,"L",0);
  }
}else{
  $pdf->cell(90,$alt,"TODAS INSTITUIÇÕES SELECIONADAS",1,1,"C",1);
}
$pdf->Output();
?>