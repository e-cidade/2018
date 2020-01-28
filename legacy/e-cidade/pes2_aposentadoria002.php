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
include("libs/db_libpessoal.php");
include("classes/db_rhpessoal_classe.php");
$clrhpessoal = new cl_rhpessoal;
$clrotulo = new rotulocampo;
$clrotulo->label('r06_pd');
db_postmemory($HTTP_GET_VARS);

$result_regist = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($regis,"rh01_regist, rh01_numcgm, z01_nome, r70_estrut, r70_descr"));
if($clrhpessoal->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Matrícula '.$regis.' não encontrada.');
}

db_fieldsmemory($result_regist, 0);

$sql_base = "select r09_rubric from basesr where r09_base   = '$base1'  
                                             and r09_anousu = ".db_anofolha()."
                                  					 and r09_mesusu = ".db_mesfolha();

$result_base = pg_exec($sql_base);

$numrows_base = pg_numrows($result_base);
$sel_base = "'";
for($i=0; $i<$numrows_base; $i++){
   db_fieldsmemory($result_base, $i);
     if($i > 0){
        $sel_base .= ",'"; 
     } 
     $sel_base .= $r09_rubric."'"; 
   
}
if ($sel_base == "'"){
  $soma = "sum( #s#_valor ) as valorsalario,";
  $sql_Sal = " and #s#_rubric = 'R985' ";
  $sql_13o = " and #s#_rubric = 'R986' ";
}else{  
 $soma = "sum( case when #s#_rubric in ($sel_base) then #s#_valor*-1 else #s#_valor end ) as valorsalario,";
  $sql_Sal = " and #s#_rubric in ($sel_base,'R985') ";
  $sql_13o = " and #s#_rubric in ($sel_base,'R986') ";
}

$clgerasql = new cl_gera_sql_folha;
$clgerasql->inicio_rh = false;

$campos = "
           #s#_regist     as regist,
           #s#_anousu     as anousu,
           #s#_mesusu     as mesusu,
           $soma
           ''::char(15)   as obs,
           '".$infla."'::char(4) as i02_codigo,
           to_date(#s#_anousu||'-'||#s#_mesusu||'-'||ndias(#s#_anousu,#s#_mesusu),'yyyy-mm-dd') as datapagto 
          ";
if(isset($anoi) && isset($anof) && (trim($anoi) != "" || trim($anof) != "")){

$mesi=str_pad($mesi,2,"0",STR_PAD_LEFT);
$mesf=str_pad($mesf,2,"0",STR_PAD_LEFT);

$wheres = 
   "(    
    ( (substr(translate(to_char(#s#_anousu,'9999'),' ','0'),2,4)||substr(translate(to_char(#s#_mesusu,'999'),' ','0'),3,2) >= '".$anoi.$mesi."' 
       and
       substr(translate(to_char(#s#_anousu,'9999'),' ','0'),2,4)||substr(translate(to_char(#s#_mesusu,'999'),' ','0'),3,2) <= '".$anof.$mesf."') )
    )";
}

//$wheres .= " and #s#_regist = ".$rh01_regist."
//             and #s#_pd = 1
//             group by #s#_anousu, #s#_mesusu, #s#_regist
//           ";

$sql_Sal = $clgerasql->gerador_sql("r14", null, null, null, null, $campos.", 1::integer as tipo", "", $wheres." $sql_Sal and #s#_regist = $rh01_regist group by #s#_anousu, #s#_mesusu, #s#_regist");
$sql_13o = $clgerasql->gerador_sql("r35", null, null, null, null, $campos.", 1::integer as tipo", "", $wheres." $sql_13o and #s#_regist = $rh01_regist group by #s#_anousu, #s#_mesusu, #s#_regist");

if(isset($i13o)){
  $sql_Sal .= " union ".$sql_13o;
}

$ordenarcampos = " histvalor ";
if($dadosvalor == "t"){
  $ordenarcampos = " valor ";
}

$clgerasubsql = new cl_gera_sql_folha;
$clgerasubsql->usar_inf = true;
$clgerasubsql->subsql   = $sql_Sal;
$clgerasubsql->subsqlano= "anousu";
$clgerasubsql->subsqlmes= "mesusu";
$clgerasubsql->subsqlreg= "regist";
//echo $sql_Sal;

$sql_Sub = "
select anousu, 
       mesusu, 
       tipo, 
       sum(valorsalario) as histvalor, 
       sum((valorsalario * coalesce(i02_valor,1))) as valor, 
       coalesce(i02_valor,0) as indice, obs 
from ( $sql_Sal 
     ) x 
     left outer join infla on infla.i02_codigo = '$infla' 
                          and extract(year from infla.i02_data)  = x.anousu 
            		          and extract(month from infla.i02_data) = x.mesusu
                          and extract(day from infla.i02_data) = 1
group by anousu,
         mesusu,
	 tipo,
	 indice,
	 obs 
order by valor desc
";
//die($sql_Sub);
db_criatemp("wkapos","","","","","",$sql_Sub);

$retorno = db_selectmax("result_work_aposentadoria",null," wkapos "," * ", " valor desc ");
$numrows_work_aposentadoria = count($result_work_aposentadoria);
if($retorno == false || $numrows_work_aposentadoria == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para a matrícula '.$regis.'.');
}

$arr_campo = Array(1=>"obs");

$nRegLimite = (int) (($indi * $numrows_work_aposentadoria) / 100);
for($i=$nRegLimite; $i<$numrows_work_aposentadoria; $i++){

  $arr_valor = Array(1=>"DESCONSIDERADO");
  $where  = " anousu = ".$result_work_aposentadoria[$i]["anousu"];
  $where .= " and mesusu = ".$result_work_aposentadoria[$i]["mesusu"];
  $where .= " and tipo = ".$result_work_aposentadoria[$i]["tipo"];
  db_update("wkapos", $arr_campo, $arr_valor, $where);

}

unset($result_work_aposentadoria, $numrows_work_aposentadoria, $retorno);

$head1 = "RELATÓRIO DE APOSENTADORIA";
$head2 = "CFE PORTARIA MINISTERIAL N ".$nrport."  de  ".db_formatar($datanrport,"d");
$head4 = $rh01_regist." - ".$z01_nome;
$head5 = $r70_estrut." - ".$r70_descr;
$head6 = "Período: ".$anoi." / ".$mesi." a ".$anof." / ".$mesf;
$head7 = "Data de Correção: ".db_formatar($data,"d");
$head8 = "Código Inflator: ".$infla."";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$entrar = true;
$alt = 4;

$retorno = db_selectmax("result_work_aposentadoria",null," wkapos "," * ", " anousu, mesusu, tipo ");

$numrows_work_aposentadoria = count($result_work_aposentadoria);

$valor_total_hist = 0;
$valor_total_corr = 0;
$valor_total_hist80 = 0;
$valor_total_corr80 = 0;

for($i=0; $i<$numrows_work_aposentadoria; $i++){
  if($pdf->gety() > $pdf->h - 30 || $entrar != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,'Competência',1,0,"C",1);
    $pdf->cell(40,$alt,'Índice',1,0,"C",1);
    $pdf->cell(20,$alt,'Valor',1,0,"C",1);
    $pdf->cell(20,$alt,'Corrigido',1,0,"C",1);
    $pdf->cell(40,$alt,'Observação',1,0,"C",1);
    $pdf->cell(20,$alt,'Data base',1,1,"C",1);
    $entrar = false;
  }

  $anousu = $result_work_aposentadoria[$i]["anousu"];
  $mesusu = $result_work_aposentadoria[$i]["mesusu"];
  $indice = $result_work_aposentadoria[$i]["indice"];
  $valor  = $result_work_aposentadoria[$i]["histvalor"];
  $corrig = $result_work_aposentadoria[$i]["valor"];
  $observ = $result_work_aposentadoria[$i]["obs"];
  $tipo   = $result_work_aposentadoria[$i]["tipo"];

  $valor_total_hist += $valor;
  $valor_total_corr += $corrig;
  if(trim($observ) == ""){
    $valor_total_hist80 += $valor;
    $valor_total_corr80 += $corrig;
  }

  $mesusu = $mesusu < 10 ? "0" . $mesusu : $mesusu;

  $pdf->setfont('arial','',7);
//  $pdf->cell(20,$alt,$anousu."/".($tipo == 2 ? 13 : $mesusu),1,0,"C",0);
  $pdf->cell(20,$alt,$anousu."/".$mesusu,1,0,"C",0);
  $pdf->cell(40,$alt,$indice,1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($valor,"f"),1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($corrig,"f"),1,0,"R",0);
  $pdf->cell(40,$alt,$observ,1,0,"L",0);
  $pdf->cell(20,$alt,db_dias_mes($anousu, $mesusu)."/".$mesusu."/".$anousu,1,1,"C",0);
}

$mediaSalarial80 = $valor_total_corr80 / $nRegLimite;

$pdf->setfont('arial','b',7);

$pdf->cell(120,$alt,"Total geral ","LTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($valor_total_hist,"f"),"TB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($valor_total_corr,"f"),"RTB",1,"R",0);

$pdf->cell(120,$alt,"Total dos ".$indi."% maiores","LTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($valor_total_hist80,"f"),"TB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($valor_total_corr80,"f"),"RTB",1,"R",0);

$pdf->cell(120,$alt,"Número total de meses geral ","LTB",0,"R",0);
$pdf->cell(20,$alt,"","TB",0,"R",0);
$pdf->cell(20,$alt,$numrows_work_aposentadoria,"RTB",1,"R",0);

$pdf->cell(120,$alt,"Número total de meses dos ".$indi."% maiores","LTB",0,"R",0);
$pdf->cell(20,$alt,"","TB",0,"R",0);
$pdf->cell(20,$alt,$nRegLimite,"RTB",1,"R",0);

$pdf->cell(120,$alt,"Média salarial","LTB",0,"R",0);
$pdf->cell(20,$alt,"","TB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($mediaSalarial80,"f"),"RTB",1,"R",0);
$pdf->Output();
?>