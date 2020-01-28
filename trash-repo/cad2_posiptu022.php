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

set_time_limit(0);
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_iptunump_classe.php");
include("classes/db_iptucalv_classe.php");
include("classes/db_arrepaga_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_debitos_classe.php");

$cliptunump = new cl_iptunump;
$cliptucalv = new cl_iptucalv;
$clarrepaga = new cl_arrepaga;
$clarrecad = new cl_arrecad; 
$cldebitos = new cl_debitos;
 
$clrotulo = new rotulocampo;
$clrotulo->label('');
$instit = db_getsession("DB_instit");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
  
$result_ult_data = $cldebitos->sql_record($cldebitos->sql_query_file(null,"k22_data as ult_data","k22_data desc limit 1"," k22_instit = $instit"));
if ($cldebitos->numrows!=0){
  db_fieldsmemory($result_ult_data,0);
}

$txt_where="j20_anousu=$ano";

if ($bairros!=""){
  if (isset($verbairro) and $verbairro=="com"){
      $txt_where.=" and j34_bairro in ($bairros) ";
  } else {
      $txt_where.=" and j34_bairro not in  ($bairros) ";
  }	 
}  

if ($ruas!=""){
  if (isset($verrua) and $verrua=="com"){
      $txt_where.=" and j36_codigo in ($ruas) ";
  } else {
      $txt_where.=" and j36_codigo not in  ($ruas) ";
  }	 
}  

if ($zonas!=""){
  if (isset($verzona) and $verzona=="com"){
      $txt_where.=" and j34_zona in ($zonas) ";
  } else {
      $txt_where.=" and j34_zona not in  ($zonas) ";
  }	 
}
  
if ($setores!=""){
   $txt_where.=" and j34_setor in ($setores) ";
}  

if ($quadras!=""){
   $txt_where.=" and j34_quadra in ($quadras) ";
}  



$result=$cliptunump->sql_record($cliptunump->sql_query_ender(null,null,"j34_bairro,j13_descr,j34_setor,j30_descr,j36_codigo,j14_nome,j34_zona,j50_descr,j20_matric,j20_anousu,j20_numpre","j13_descr","$txt_where"));
$numrows = $cliptunump->numrows;
if ($cliptunump->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$head3 = "POSIÇÃO DO IPTU ";
$head5 = "$ano";
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;

/*
$aBairro=array();
$aRuas=array();
$aSetor=array();
$aZona=array();
*/

for($x = 0; $x < $numrows;$x++){
  db_fieldsmemory($result,$x);
  
  $result_calculado = $cliptucalv->sql_record($cliptucalv->sql_query_file('','sum(j21_valor) as calculado','',"j21_anousu=$j20_anousu and j21_matric=$j20_matric")); 
  if ($cliptucalv->numrows!=0){
    db_fieldsmemory($result_calculado,0);
  }
   
  $result_pago = $clarrepaga->sql_record($clarrepaga->sql_query_file('','sum(k00_valor) as pago','',"k00_numpre=$j20_numpre")); 
  if ($clarrepaga->numrows!=0){
    db_fieldsmemory($result_pago,0);
  }
                                       
  $result_devido = $clarrecad->sql_record($clarrecad->sql_query_file_instit('','sum(k00_valor) as devido','',"arrecad.k00_numpre=$j20_numpre and k00_dtvenc <= '".date("Y-m-d",db_getsession("DB_datausu"))."' and k00_instit = ".db_getsession('DB_instit') )); 
  if ($clarrecad->numrows!=0){
    db_fieldsmemory($result_devido,0);
  }

  $result_apagar = $clarrecad->sql_record($clarrecad->sql_query_file_instit('','sum(k00_valor) as apagar','',"arrecad.k00_numpre=$j20_numpre and k00_dtvenc > '".date("Y-m-d",db_getsession("DB_datausu"))."' and k00_instit = ".db_getsession('DB_instit')  )); 
  $apagar=0;
  if ($clarrecad->numrows!=0){
    db_fieldsmemory($result_apagar,0);
  }


/*
    $result_debitos = $cldebitos->sql_record($cldebitos->sql_query_file(null,"sum(k22_vlrcor)as corrigido,sum(k22_juros)as juros,sum(k22_multa)as multa,sum(k22_desconto)as desconto",'',"k22_data=$ult_data and k22_numpre=$j20_numpre"));
    if ($cldebitos->numrows!=0){
       db_fieldsmemory($result_debitos,0);
   }
  */
  
  
   if (isset($aBairro[$j34_bairro][$j13_descr]["calculado"])){
   	$aBairro[$j34_bairro][$j13_descr]["calculado"]+=$calculado;
    $aBairro[$j34_bairro][$j13_descr]["pago"]+=$pago;
    $aBairro[$j34_bairro][$j13_descr]["devido"]+=$devido;
    $aBairro[$j34_bairro][$j13_descr]["apagar"]+=$apagar;
    $aBairro[$j34_bairro][$j13_descr]["quant"]+=1;
    if($devido>0){
      $aBairro[$j34_bairro][$j13_descr]["quantd"]+=1;
    }else{
      $aBairro[$j34_bairro][$j13_descr]["quantd"]+=0;      
    }
    if($devido<=0)
      $aBairro[$j34_bairro][$j13_descr]["quanta"]+=1;
      
/*
      $aBairro[$j34_bairro][$j13_descr]["corrigido"]+=$corrigido;
      $aBairro[$j34_bairro][$j13_descr]["juros"]+=$juros;
      $aBairro[$j34_bairro][$j13_descr]["multa"]+=$multa;
      $aBairro[$j34_bairro][$j13_descr]["desconto"]+=$desconto;
*/  
   }else{
    $aBairro[$j34_bairro][$j13_descr]["calculado"]=$calculado;
    $aBairro[$j34_bairro][$j13_descr]["pago"]=$pago;
    $aBairro[$j34_bairro][$j13_descr]["devido"]=$devido;
    $aBairro[$j34_bairro][$j13_descr]["apagar"]=$apagar;
    $aBairro[$j34_bairro][$j13_descr]["quant"]=1;
    if($devido>0){
      $aBairro[$j34_bairro][$j13_descr]["quantd"]=1;
    }else{
      $aBairro[$j34_bairro][$j13_descr]["quantd"]=0;      
    }
    if($devido<=0) {
      $aBairro[$j34_bairro][$j13_descr]["quanta"]=1;
    } else {
      $aBairro[$j34_bairro][$j13_descr]["quanta"]=0;
    }

  /*
      $aBairro[$j34_bairro][$j13_descr]["corrigido"]=$corrigido;
      $aBairro[$j34_bairro][$j13_descr]["juros"]=$juros;
      $aBairro[$j34_bairro][$j13_descr]["multa"]=$multa;
      $aBairro[$j34_bairro][$j13_descr]["desconto"]=$desconto;
    */
   }
   
//   if (array_key_exists($j34_setor,$aSetor)){
//     $aSetor[$j34_setor]["calculado"]+=$calculado;
//     $aSetor[$j34_setor]["pago"]+=$pago;
//     $aSetor[$j34_setor]["devido"]+=$devido;
     /*
     $aSetor[$j34_setor]["corrigido"]+=$corrigido;
     $aSetor[$j34_setor]["juros"]+=$juros;
     $aSetor[$j34_setor]["multa"]+=$multa;
     $aSetor[$j34_setor]["desconto"]+=$desconto;
     */
//   }else{
//     $aSetor[$j34_setor]["calculado"]=$calculado;
//     $aSetor[$j34_setor]["pago"]=$pago;
//     $aSetor[$j34_setor]["devido"]=$devido;
     /*
     $aSetor[$j34_setor]["corrigido"]=$corrigido;
     $aSetor[$j34_setor]["juros"]=$juros;
     $aSetor[$j34_setor]["multa"]=$multa;
     $aSetor[$j34_setor]["desconto"]=$desconto;
     */
//   }
    
//   if (array_key_exists($j34_zona,$aZona)){
//    $aZona[$j34_zona]["calculado"]+=$calculado;
//     $aZona[$j34_zona]["pago"]+=$pago;
//     $aZona[$j34_zona]["devido"]+=$devido;
     /*
     $aZona[$j34_zona]["corrigido"]+=$corrigido;
     $aZona[$j34_zona]["juros"]+=$juros;
     $aZona[$j34_zona]["multa"]+=$multa;
     $aZona[$j34_zona]["desconto"]+=$desconto;
     */
//   }else{
//   	 $aZona[$j34_zona]["calculado"]=$calculado;
//     $aZona[$j34_zona]["pago"]=$pago;
//     $aZona[$j34_zona]["devido"]=$devido;
     /*
     $aZona[$j34_zona]["corrigido"]=$corrigido;
     $aZona[$j34_zona]["juros"]=$juros;
     $aZona[$j34_zona]["multa"]=$multa;
     $aZona[$j34_zona]["desconto"]=$desconto;
     */
//   }
//   if (array_key_exists($j34_zona,$aZona)){
//   	 $aRuas[$j36_codigo]["calculado"]+=$calculado;
//     $aRuas[$j36_codigo]["pago"]+=$pago;
//     $aRuas[$j36_codigo]["devido"]+=$devido;
     /*
     $aRuas[$j36_codigo]["corrigido"]+=$corrigido;
     $aRuas[$j36_codigo]["juros"]+=$juros;
     $aRuas[$j36_codigo]["multa"]+=$multa;
     $aRuas[$j36_codigo]["desconto"]+=$desconto;
     */
//   }else{
//     $aRuas[$j36_codigo]["calculado"]=$calculado;
//     $aRuas[$j36_codigo]["pago"]=$pago;
//     $aRuas[$j36_codigo]["devido"]=$devido;
     /*
     $aRuas[$j36_codigo]["corrigido"]=$corrigido;
     $aRuas[$j36_codigo]["juros"]=$juros;
     $aRuas[$j36_codigo]["multa"]=$multa;
     $aRuas[$j36_codigo]["desconto"]=$desconto;
     */
//   }

}
$totalcalc=0;
$totalpag=0;
$totaldev=0;
$totalcor=0;
$totaljur=0;
$totalmul=0;
$totaldesc=0;
$totalapagar=0;
$totalquant=0;
$totalquantd=0;
$totalquanta=0;

for ($y=0;$y<count($aBairro);$y++){
  $cod=key($aBairro);
  $descr=key($aBairro[$cod]);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',10);
      
      if ($bairros!=""){
        if (isset($verbairro) and $verbairro=="com"){
           $pdf->cell(200,$alt,'Bairro:'.$bairros,0,1);
        } else {
           $pdf->cell(200,$alt,'Sem os Bairro:'.$bairros,0,1);
        }	 
      }  

      if ($ruas!=""){
        if (isset($verrua) and $verrua=="com"){
           $pdf->cell(200,$alt,'Logradouros:'.$ruas,0,1);
        } else {
           $pdf->cell(200,$alt,'Sem os Logradouros:'.$ruas,0,1);
        }	 
      }  

      if ($zonas!=""){
        if (isset($verzona) and $verzona=="com"){
           $pdf->cell(200,$alt,'Zonas:'.$zonas,0,1);
        } else {
           $pdf->cell(200,$alt,'Sem as Zonas:'.$zonas,0,1);
        }	 
      }
  
      if ($setores!=""){
         $pdf->cell(200,$alt,'Setores:'.$setores,0,1);
      }  

      if ($quadras!=""){
         $pdf->cell(200,$alt,'Quadras:'.$quadras,0,1);
      }  
      $pdf->setfont('arial','b',8);
      
      $pdf->cell(15,$alt,'Cod.',1,0,"C",1);
      $pdf->cell(60,$alt,'Bairro',1,0,"C",1);
      $pdf->cell(25,$alt,'Calculado',1,0,"C",1);
      $pdf->cell(25,$alt,'Pago',1,0,"C",1);
      $pdf->cell(25,$alt,'Vencido',1,0,"C",1);
      $pdf->cell(25,$alt,'A Vencer',1,0,"C",1);
      $pdf->cell(25,$alt,'Quantidade',1,0,"C",1);
      $pdf->cell(25,$alt,'Inadimplentes',1,0,"C",1);
      $pdf->cell(10,$alt,'%',1,0,"C",1);
      $pdf->cell(25,$alt,'Adimplentes',1,0,"C",1);
      $pdf->cell(10,$alt,'%',1,1,"C",1);
      /*
      $pdf->cell(25,$alt,'Devido',1,0,"C",1);
      
      $pdf->cell(25,$alt,'Corrigido',1,0,"C",1);
      $pdf->cell(25,$alt,'Juros',1,0,"C",1);
      $pdf->cell(25,$alt,'Multa',1,0,"C",1);
      $pdf->cell(25,$alt,'Desconto',1,1,"C",1);
       */
      
      
      $p=0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$cod,0,0,"C",$p);
   $pdf->cell(60,$alt,$descr,0,0,"L",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['calculado'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['pago'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['devido'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['apagar'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,$aBairro[$cod][$descr]['quant'],0,0,"R",$p);
   $pdf->cell(25,$alt,$aBairro[$cod][$descr]['quantd'],0,0,"R",$p);
   
   if($aBairro[$cod][$descr]['quant']>0)
     $pdf->cell(10,$alt,db_formatar(($aBairro[$cod][$descr]['quantd']*100)/$aBairro[$cod][$descr]['quant'],'p'),0,0,"R",$p);
   else
     $pdf->cell(10,$alt,'',0,0,"R",$p);
    
   $pdf->cell(25,$alt,$aBairro[$cod][$descr]['quanta'],0,0,"R",$p);

   if($aBairro[$cod][$descr]['quant']>0)
     $pdf->cell(10,$alt,db_formatar(($aBairro[$cod][$descr]['quanta']*100)/$aBairro[$cod][$descr]['quant'],'p'),0,1,"R",$p);
   else
     $pdf->cell(10,$alt,'',0,1,"R",$p);

   /*
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['devido'],'f'),0,0,"R",$p);
   
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['corrigido'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['juros'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['multa'],'f'),0,0,"R",$p);
   $pdf->cell(25,$alt,db_formatar($aBairro[$cod][$descr]['desconto'],'f'),0,1,"R",$p);
   */
   	 
   
   $totalcalc+=$aBairro[$cod][$descr]['calculado'];
   $totalpag+=$aBairro[$cod][$descr]['pago'];
   $totaldev+=$aBairro[$cod][$descr]['devido'];
   $totalapagar+=$aBairro[$cod][$descr]['apagar'];
   $totalquant+=$aBairro[$cod][$descr]['quant'];
   $totalquantd+=$aBairro[$cod][$descr]['quantd'];
   $totalquanta+=$aBairro[$cod][$descr]['quanta'];
   /*
   $totalcor+=$aBairro[$cod][$descr]['corrigido'];
   $totaljur+=$aBairro[$cod][$descr]['juros'];
   $totalmul+=$aBairro[$cod][$descr]['multa'];
   $totaldesc+=$aBairro[$cod][$descr]['desconto'];
   */
   if ($p==0){
   	$p=1;
   }else $p=0;   
   $total++;
   next($aBairro);
}
$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAIS : ',"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totalcalc,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totalpag,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totaldev,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totalapagar,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,$totalquant,"T",0,"R",0);
$pdf->cell(25,$alt,$totalquantd,"T",0,"R",0);

if($totalquantd>0)
  $pdf->cell(10,$alt,db_formatar(($totalquantd*100)/$totalquant,'p'),"T",0,"R",0);
else
  $pdf->cell(10,$alt,'',"T",0,"R",0);

$pdf->cell(25,$alt,$totalquanta,"T",0,"R",0);
if($totalquantd>0)
  $pdf->cell(10,$alt,db_formatar(($totalquanta*100)/$totalquant,'p'),"T",1,"R",0);
else
  $pdf->cell(10,$alt,'',"T",1,"R",0);
/*
$pdf->cell(25,$alt,db_formatar($totaldev,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totalcor,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totaljur,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totalmul,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($totaldesc,'f'),"T",1,"R",0);
*/
	

$pdf->cell(150,$alt,'TOTAL DE BAIRROS : '.$total,"T",0,"R",0);
$pdf->Ln();
$pdf->cell(150,$alt,'Obs: Os valores pagos foram considerados os descontos concedidos.',"T",0,"R",0);
$pdf->Output();

?>