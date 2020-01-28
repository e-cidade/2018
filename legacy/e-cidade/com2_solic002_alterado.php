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
include("classes/db_solicita_classe.php");
include("classes/db_solicitatipo_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pctipocompra_classe.php");
include("classes/db_db_depart_classe.php");
$clsolicita     = new cl_solicita;
$clsolicitatipo = new cl_solicitatipo;
$clsolicitem    = new cl_solicitem;
$clpcdotac      = new cl_pcdotac;
$clpctipocompra = new cl_pctipocompra;
$cldb_depart    = new cl_db_depart;
$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$and    = "";
$and2   = "";
$where  = "";
$info   = "";
function monta_where($inp="",$par="",$descr_inp=""){
  global $and;
  $param_autoriza = "";
  $where_autorizacao = "";
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

$wsolicita = monta_where($inp_depart,$par_depart ," pc10_depto ");
if(isset($pc10_dataINI_dia) && trim($pc10_dataINI_dia)!="" && isset($pc10_dataINI_mes) && trim($pc10_dataINI_mes)!="" && isset($pc10_dataINI_ano) && trim($pc10_dataINI_ano)!=""){
  $dt_ini = $pc10_dataINI_ano."-".$pc10_dataINI_mes."-".$pc10_dataINI_dia;
}
if(isset($pc10_dataFIM_dia) && trim($pc10_dataFIM_dia)!="" && isset($pc10_dataFIM_mes) && trim($pc10_dataFIM_mes)!="" && isset($pc10_dataFIM_ano) && trim($pc10_dataFIM_ano)!=""){
  $dt_fim = $pc10_dataFIM_ano."-".$pc10_dataFIM_mes."-".$pc10_dataFIM_dia;
}
$msg_head = '';
if(isset($dt_ini) && trim($dt_ini)!="" || isset($dt_fim) && trim($dt_fim)!=""){
  if(isset($dt_ini) && isset($dt_fim)){
    $wsolicita = $wsolicita . $and ." pc10_data between '".$dt_ini."' and '".$dt_fim."' ";
    $msg_head  = "Período de solicitação entre ".db_formatar($dt_ini,"d")." e ".db_formatar($dt_fim,"d");
  }else if(isset($dt_ini)){
    $wsolicita = $wsolicita . $and ." pc10_data >= '".$dt_ini."' ";
    $msg_head  = "Período de solicitação posterior a ".db_formatar($dt_ini,"d");
  }else if(isset($dt_fim)){
    $wsolicita = $wsolicita . $and ." pc10_data <= '". $dt_fim."' ";
    $msg_head  = "Período de solicitação anterior a ".db_formatar($dt_fim,"d");
  }
  $and = " and ";
}
$wtipcompra = monta_where($inp_tipcom,$par_tipcom," pc12_tipo ");
$wmateriais = monta_where($inp_mater ,$par_mater ," pc16_codmater  ");
$wdotacoes  = monta_where($inp_dotac ,$par_dotac ," pc13_coddot ");
if(isset($wsolicita) && trim($wsolicita)!=""){
  $where .= $wsolicita;
}
if(isset($pc10_numeroINI) && $pc10_numeroINI!="" || isset($pc10_numeroFIM) && $pc10_numeroFIM!=""){
  $where_param = "";
  if(isset($pc10_numeroINI) && isset($pc10_numeroFIM)){
    $where_param = " pc10_numero between $pc10_numeroINI and $pc10_numeroFIM ";    
  }else if($pc10_numeroINI){
    $where_param = " pc10_numero >= $pc10_numeroINI ";
  }else if($pc10_numeroFIM){
    $where_param = " pc10_numero <= $pc10_numeroFIM ";
  }
  $where .= $and.$where_param;
}
if(isset($wtipcompra) && trim($wtipcompra)!=""){
  $where .= $wtipcompra;
}
if(isset($wmateriais) && trim($wmateriais)!=""){
  $where .= $wmateriais;
}
if(isset($wdotacoes) && trim($wdotacoes)!=""){
  $where .= $wdotacoes;
}

if($ordem == "pc10_numero"){
  $info = "CÓDIGO DAS SOLICITAÇÕES";
}else if ($ordem == "pc10_data"){
  $info = "DATA DE EMISSÃO";
}else if ($ordem == "descrdepto"){
  $info = "DEPARTAMENTO";
}else if ($ordem == "pc50_descr"){
  $info = "TIPO DE COMPRA";
}

$result_solicita = $clsolicita->sql_record($clsolicita->sql_query_rel(null,'distinct pc10_numero,pc16_codmater,pc12_tipo,pc10_data,pc10_depto,descrdepto,pc12_vlrap,pc50_descr,pc11_codigo,pc01_descrmater,pc11_quant,pc11_vlrun,pc13_anousu,pc13_coddot,pc13_quant,pc13_valor',$ordem,$where));

$numrows = $clsolicita->numrows;
if($numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontradas solicitações com os dados informados.');
}
///////////////////////////////////////////////////////////////////////
$head3 = "SOLICITAÇÃO DE COMPRA";
$head6 = "ORDEM DE SELEÇÃO POR ".$info;
$head7 = $msg_head;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$troca = 1;
$alt = 5;
$total = 0;
$c = 0;
//    $pdf->addpage("L");
$pc10_numero_ant = null;
$pc11_codigo_ant = null;
$valortotal      = 0;

for($i = 0; $i < $numrows;$i++){
  db_fieldsmemory($result_solicita,$i,true);
  $pass = false;
  if($pdf->gety() > $pdf->h - 32 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell(22,$alt,"Solicitação",1,0,"C",1);
    $pdf->cell(22,$alt,"Emissão",1,0,"C",1);
    $pdf->cell(91,$alt,"Departamento",1,0,"C",1);
    $pdf->cell(105,$alt,"Tipo",1,0,"C",1);
    $pdf->cell(31,$alt,"Val. Aprox",1,1,"C",1);

    $pdf->cell(84,$alt,"",0,0,"C",0);
    $pdf->cell(22,$alt,"Item",1,0,"C",1);
    $pdf->cell(90,$alt,"Material",1,0,"C",1);
    $pdf->cell(22,$alt,"Quantidade",1,0,"C",1);
    $pdf->cell(22,$alt,"Val. Unit.",1,0,"C",1);
    $pdf->cell(31,$alt,"Total",1,1,"C",1);

    $pdf->cell(152,$alt,"",0,0,"C",0);
    $pdf->cell(22,$alt,"Dotação",1,0,"C",1);
    $pdf->cell(22,$alt,"Ano",1,0,"C",1);
    $pdf->cell(22,$alt,"Quantidade",1,0,"C",1);
    $pdf->cell(22,$alt,"Val. Unit",1,0,"C",1);
    $pdf->cell(31,$alt,"Total",1,1,"C",1);
    $troca=0;
    $pass = true;
  }
  //dados empautoriza
  if($pc10_numero_ant!=$pc10_numero){
    if($pass==false){
      $pdf->cell(271,2,'',"T",1,"L",0);
    }
    $pdf->setfont('arial','B',8);
    $pdf->cell(22,$alt,@$pc10_numero,0,0,"C",0);
    $pdf->cell(22,$alt,@$pc10_data,0,0,"C",0);
    $pdf->cell(91,$alt,@$descrdepto,0,0,"L",0);
    $pdf->cell(105,$alt,@$pc50_descr,0,0,"L",0);
    $pdf->cell(31,$alt,@$pc12_vlrap,0,1,"R",0);
    $total++;
    $valortotal += @$pc12_vlrap;
  }
  if($pc11_codigo_ant!=$pc11_codigo){
    $pdf->setfont('arial','B',7);
    $valor_tot = db_formatar(@$pc11_quant*@$pc11_vlrun,'f');
    $pdf->cell(84,$alt,"",0,0,"C",0);
    $pdf->cell(22,$alt,@$pc11_codigo,0,0,"C",0);
    $pdf->cell(90,$alt,substr(@$pc01_descrmater,0,50),0,0,"L",0);
    $pdf->cell(22,$alt,@$pc11_quant,0,0,"C",0);
    $pdf->cell(22,$alt,@$pc11_vlrun,0,0,"R",0);
    $pdf->cell(31,$alt,@$valor_tot,0,1,"R",0);
  }
  $pdf->setfont('arial','',7);
  if(isset($pc13_coddot) && $pc13_coddot!=""){
    if(isset($pc13_quant) && $pc13_quant!=0){
      $valor_uni = db_formatar(@$pc13_valor/@$pc13_quant,'f');
    }
    $pdf->cell(152,$alt,"",0,0,"C",0);
    $pdf->cell(22,$alt,@$pc13_coddot,0,0,"C",0);
    $pdf->cell(22,$alt,@$pc13_anousu,0,0,"C",0);
    $pdf->cell(22,$alt,@$pc13_quant,0,0,"C",0);
    $pdf->cell(22,$alt,@$valor_uni,0,0,"R",0);
    $pdf->cell(31,$alt,@$pc13_valor,0,1,"R",0);
  }

  $pc10_numero_ant = $pc10_numero;
  $pc11_codigo_ant = $pc11_codigo;
}
$pdf->setfont('arial','b',8);
$pdf->ln($alt);
$pdf->cell(240,$alt+2,'Valor total',"T",0,"R",1);
$pdf->cell(31,$alt+2,$valortotal,"T",1,"R",1);
$pdf->cell(271,$alt,'TOTAL DE SOLICITAÇÕES  :  '.$total,"T",1,"L",0);

$and = "";
$wdepar = monta_where($inp_depart,$par_depart ," pc10_depto ");
$wtipco = monta_where($inp_tipcom,$par_tipcom ," pc12_tipo ");
$wmater = monta_where($inp_mater ,$par_mater ," pc16_codmater  ");
$wdotac = monta_where($inp_dotac ,$par_dotac ," pc13_coddot ");

$wtotal = "";
$wtotal = $wdepar.$wtipco.$wmater.$wdotac;
if($wtotal==""){
  $wtotal= " 1=1 ";
}

$sql_total = "from  solicita
	            inner join solicitatipo on solicitatipo.pc12_numero=solicita.pc10_numero
	            inner join solicitem on solicitem.pc11_numero=solicita.pc10_numero
	            left  join solicitempcmater on solicitempcmater.pc16_solicitem=solicitem.pc11_codigo
	            inner join solicitemunid on solicitemunid.pc17_codigo=solicitem.pc11_codigo
		    inner join db_depart on db_depart.coddepto=solicita.pc10_depto
	      where $wtotal \n";

if(isset($totdepar)){
  $sql_totdepart = "select 
                          pc10_depto,
			  descrdepto,
			  sum(pc12_vlrap) as pc12_vlrap \n"; 
  $sql_totdepart.= $sql_total;
  $sql_totdepart.= "\ngroup by pc10_depto,descrdepto";
//  echo $sql_totdepart;
  $rdepart = $clsolicita->sql_record($sql_totdepart);  
  $nrdepart= $clsolicita->numrows;  
  $c = 1;
  $valtot = 0;
  $pass   = false;
  for($i=0;$i<$nrdepart;$i++){
    db_fieldsmemory($rdepart,$i,true);
    $pass = true;
    if($c==0){$c=1;}else{$c=0;}
    if($pdf->gety() > $pdf->h - 32 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(150,$alt,"TOTALIZAÇÃO POR DEPARTAMENTO",1,1,"C",1);
      $pdf->cell(25,$alt,"Código",1,0,"C",1);
      $pdf->cell(100,$alt,"Departamento",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor",1,1,"C",1);
      $pdf->ln($alt);
    }
    $pdf->cell(25,$alt,@$pc10_depto,0,0,"C",$c);
    $pdf->cell(100,$alt,@$descrdepto,0,0,"L",$c);
    $pdf->cell(25,$alt,@$pc12_vlrap,0,1,"R",$c);
    $valtot+=@$pc12_vlrap;
  }
  if($pass==true){
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total","T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($valtot,"f"),"T",1,"R",1);
  }
}

if(isset($tottipco)){
  $sql_tottipcom = "select 
                          pc12_tipo,
   			  pc50_descr,
  			  sum(pc12_vlrap) as pc12_vlrap \n"; 
  $sql_tottipcom.= $sql_total;
  $sql_tottipcom.= "\ngroup by pc12_tipo,pc50_descr";
//  echo $sql_tottipcom;
  $rtipcom = $clsolicita->sql_record($sql_tottipcom);
  $nrtipcom= $clsolicita->numrows;
  $c = 1;
  $valtot = 0;
  $pass   = false;
  for($i=0;$i<$nrtipcom;$i++){
    db_fieldsmemory($rtipcom,$i,true);
    $pass = true;
    if($c==0){$c=1;}else{$c=0;}
    if($pdf->gety() > $pdf->h - 32 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(150,$alt,"TOTALIZAÇÃO POR TIPO DE COMPRA",1,1,"C",1);
      $pdf->cell(25,$alt,"Código",1,0,"C",1);
      $pdf->cell(100,$alt,"Tipo de compra",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor",1,1,"C",1);
      $pdf->ln($alt);
    }
    $pdf->cell(25,$alt,@$pc12_tipo,0,0,"C",$c);
    $pdf->cell(100,$alt,@$pc50_descr,0,0,"L",$c);
    $pdf->cell(25,$alt,@$pc12_vlrap,0,1,"R",$c);
    $valtot+=@$pc12_vlrap;
  }
  if($pass==true){
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total","T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($valtot,"f"),"T",1,"R",1);
  }
}

if(isset($totmater)){
  $sql_totmateri = "select 
                          pc16_codmater,
  			  pc01_descrmater,
  			  sum(pc11_quant*pc11_vlrun) as pc12_vlrap\n"; 
  $sql_totmateri.= $sql_total;
  $sql_totmateri.= "\ngroup by pc16_codmater,pc01_descrmater";
//  echo $sql_totmateri;
  $rmater = $clsolicita->sql_record($sql_totmateri);
  $nrmater= $clsolicita->numrows;
  $c = 1;
  $valtot = 0;
  $pass   = false;
  for($i=0;$i<$nrmater;$i++){
    db_fieldsmemory($rmater,$i,true);
    $pass = true;
    if($c==0){$c=1;}else{$c=0;}
    if($pdf->gety() > $pdf->h - 32 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(150,$alt,"TOTALIZAÇÃO POR MATERIAIS",1,1,"C",1);
      $pdf->cell(25,$alt,"Código",1,0,"C",1);
      $pdf->cell(100,$alt,"Material",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor",1,1,"C",1);
      $pdf->ln($alt);
    }
    $pdf->cell(25,$alt,@$pc16_codmater,0,0,"C",$c);
    $pdf->cell(100,$alt,@$pc01_descrmater,0,0,"L",$c);
    $pdf->cell(25,$alt,@$pc12_vlrap,0,1,"R",$c);
    $valtot+=@$pc12_vlrap;
  }
  if($pass==true){
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total","T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($valtot,"f"),"T",1,"R",1);
  }
}

if(isset($totdotac)){

  $sql_totmateri = "select 
                          pc13_coddot,
			  pc13_anousu,
			  sum(pc13_valor) as pc13_valor\n"; 
  $sql_totmateri.= $sql_total;
  $sql_totmateri.= "\ngroup by pc13_coddot,pc13_anousu";
//  echo $sql_totdotaca;
  $rdotac = $clsolicita->sql_record($sql_totdotaca);
  $nrdotac= $clsolicita->numrows;
  $c = 1;
  $valtot = 0;
  $pass   = false;
  for($i=0;$i<$nrdotac;$i++){
    db_fieldsmemory($rdotac,$i,true);
    $pass = true;
    if($c==0){$c=1;}else{$c=0;}
    if($pdf->gety() > $pdf->h - 32 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(150,$alt,"TOTALIZAÇÃO POR DOTAÇÕES",1,1,"C",1);
      $pdf->cell(25,$alt,"Código",1,0,"C",1);
      $pdf->cell(100,$alt,"Ano",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor",1,1,"C",1);
      $pdf->ln($alt);
    }
    $pdf->cell(25,$alt,@$pc13_coddot,0,0,"C",$c);
    $pdf->cell(100,$alt,@$pc13_anousu,0,0,"L",$c);
    $pdf->cell(25,$alt,@$pc13_valor,0,1,"R",$c);
    $valtot+=@$pc13_valor;
  }
  if($pass==true){
    $pdf->ln($alt);
    $pdf->cell(125,$alt,"Total","T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($valtot,"f"),"T",1,"R",1);
  }
}
$pdf->Output();
?>