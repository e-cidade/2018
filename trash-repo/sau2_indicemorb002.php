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
include("classes/db_prontuarios_classe.php");
include("classes/db_unidades_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();
$unidade = @str_replace("X",",",$unidades);
$data1 = str_replace("X","-",$data1);
$data2 = str_replace("X","-",$data2);
$cid = @strtoupper($cid);
$bairro = @trim(strtoupper($bairro));
$paciente = @trim($paciente);
$ordercid = @trim($ordercid);
$orderpac = @trim($orderpac);
$posto = @$posto;
//Monta SQL
 $sql = "SELECT count(sd70_c_cid),
               sd70_c_cid,
               sd70_c_nome
         FROM prontcid
        INNER JOIN prontuarios 	on prontuarios.sd24_i_codigo = prontcid.sd55_i_prontuario
        INNER JOIN unidades    	on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade
        INNER JOIN db_depart   	on db_depart.coddepto = unidades.sd02_i_codigo
        INNER JOIN sau_cid     	on sau_cid.sd70_i_codigo = prontcid.sd55_i_cid
        INNER JOIN cgs    		on cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs
         LEFT JOIN cgs_cgm 		on cgs_cgm.z01_i_cgscgm = cgs.z01_i_numcgs
         LEFT JOIN cgm     		on cgm.z01_numcgm       = cgs_cgm.z01_i_numcgm
         LEFT JOIN cgs_und 		on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs
        INNER JOIN prontproced 	on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo
        WHERE prontproced.sd29_d_data BETWEEN '$data1' AND '$data2'
        ";
        if($posto != ""){
          $sql.="AND unidades.sd02_i_codigo = $posto ";
        }
        if($bairro != ""){
          $sql.="AND (trim(z01_bairro) = '$bairro' OR trim(z01_v_bairro) = '$bairro') ";
        }
        if($cid != ""){
          $sql.="AND sau_cid.sd70_c_cid = '$cid' ";
        }
        $sql .= "
        GROUP BY sd70_c_cid,
                 sd70_c_nome
        ORDER BY $ordercid
       ";

$query = pg_query($sql);
$linhas = pg_num_rows($query);
//db_criatabela($query);
//exit;
if($linhas == 0){
 echo "<table width='100%'>
        <tr>
         <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button' value='Fechar' onclick='window.close()'></b></font></td>
        </tr>
       </table>";
 exit;
}
$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head1 = "Relatório do Indice de Morbidade";
$head2 = "Periodo:".substr($data1,8,2)."/".substr($data1,5,2)."/".substr($data1,0,4)." A ".substr($data2,8,2)."/".substr($data2,5,2)."/".substr($data2,0,4);
if($posto!=""){
 $h_unidade = $posto;
}else{
 $h_unidade = "TODAS";
}
if($bairro!=""){
 $h_bairro = $bairro;
}else{
 $h_bairro = "TODOS";
}
$head3 = "Unidade: $h_unidade";
$head4 = "Bairro: $h_bairro";
$s_total = 0;
$s_cid = 0;
$pdf->addpage();
$cor_1 = "1";
$cor_2 = "0";
$cor_  = "";
for ($i = 0;$i < $linhas;$i++){
 $cont = 0;
 $Array = pg_fetch_array($query);
 if($paciente=="true"){
  $pdf->setfillcolor(200);
   $cor_ = "1";
   $borda1 = "BTL";
   $borda2 = "BTR";
 }else{
  $pdf->setfillcolor(230);
  $borda1 = "0";
  $borda2 = "0";
  if($cor_==$cor_1){
   $cor_ = $cor_2;
  }else{
   $cor_ = $cor_1;
  }
 }
 $pdf->setfont('arial','b',8);
 $pdf->cell(140,4,"CID: $Array[1] $Array[2]",$borda1,0,"L",$cor_);
 $pdf->cell(50,4,"Total deste CID: $Array[0]",$borda2,1,"R",$cor_);
 $s_total += $Array[0];
 if($paciente=="true"){
  $pdf->setfont('arial','',7);
  if(($pdf->gety() > $pdf->h -30)){
   if($cont==0){
    $pdf->setfillcolor(255);
    $pdf->rect(10,$pdf->getY()-4,190,10,'F');
    $pdf->setfillcolor(200);
   }
   $pdf->addpage();
   $pdf->setfillcolor(200);
   $pdf->setfont('arial','b',8);
   $pdf->cell(140,4,"CID: $Array[1] $Array[2]","BTL",0,"L",1);
   $pdf->cell(50,4,"Total deste CID: $Array[0]","BTR",1,"R",1);
  }
  $pdf->setfillcolor(240);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,4,"Paciente:",0,0,"L",0);
  $pdf->cell(30,4,"Bairro:",0,0,"L",0);
  $pdf->cell(15,4,"Nasc.:",0,0,"L",0);
  $pdf->cell(60,4,"Unidade:",0,0,"L",0);
  $pdf->cell(15,4,"Dt. Atend:",0,0,"L",0);
  $pdf->cell(30,4,"Qtd.:",0,1,"L",0);
  if(($pdf->gety() > $pdf->h -30)){
   if($cont==0){
    $pdf->setfillcolor(255);
    $pdf->rect(10,$pdf->getY()-8,190,10,'F');
    $pdf->setfillcolor(200);
   }
   $pdf->addpage();
   $pdf->setfillcolor(200);
   $pdf->setfont('arial','b',8);
   $pdf->cell(140,4,"CID: $Array[1] $Array[2]","BTL",0,"L",1);
   $pdf->cell(50,4,"Total deste CID: $Array[0]","BTR",1,"R",1);
   $pdf->setfont('arial','',7);
   $pdf->setfillcolor(240);
   $pdf->cell(60,4,"Paciente:",0,0,"L",0);
   $pdf->cell(30,4,"Bairro:",0,0,"L",0);
   $pdf->cell(20,4,"Nasc.:",0,0,"L",0);
   $pdf->cell(60,4,"Unidade:",0,0,"L",0);
   $pdf->cell(15,4,"Dt. Atend:",0,0,"L",0);
   $pdf->cell(30,4,"Qtd.:",0,1,"L",0);
  }
  $pdf->setfillcolor(200);
  $sql1 = "SELECT
                  count(*) as qtdpaciente,
                  case when cgm.z01_numcgm is null then
                   z01_v_nome
                  else
                   z01_nome
                  end as z01_nome,
                  case when cgm.z01_numcgm is null then
                   z01_v_bairro
                  else
                   z01_bairro
                  end as z01_bairro,
                  sd02_i_codigo,
                  descrdepto,
                  case when cgm.z01_numcgm is null then
                   z01_d_nasc
                  else
                   z01_nasc
                  end as z01_nasc,
                  sd29_d_data
	         FROM prontcid
	        INNER JOIN prontuarios 	on prontuarios.sd24_i_codigo = prontcid.sd55_i_prontuario
	        INNER JOIN unidades    	on unidades.sd02_i_codigo = prontuarios.sd24_i_unidade
	        INNER JOIN db_depart   	on db_depart.coddepto = unidades.sd02_i_codigo
	        INNER JOIN sau_cid     	on sau_cid.sd70_i_codigo = prontcid.sd55_i_cid
	        INNER JOIN cgs    		on cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs
	         LEFT JOIN cgs_cgm 		on cgs_cgm.z01_i_cgscgm = cgs.z01_i_numcgs
	         LEFT JOIN cgm     		on cgm.z01_numcgm       = cgs_cgm.z01_i_numcgm
	         LEFT JOIN cgs_und 		on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs
	        INNER JOIN prontproced 	on prontproced.sd29_i_prontuario = prontuarios.sd24_i_codigo
            WHERE prontproced.sd29_d_data BETWEEN '$data1' AND '$data2'
              AND sau_cid.sd70_c_cid = '$Array[1]'";
           if($posto != ""){
            $sql1.="AND unidades.sd02_i_codigo = $posto ";
           }
           if($bairro != ""){
            $sql1.="AND (trim(z01_bairro) = '$bairro' OR trim(z01_v_bairro) = '$bairro')";
           }
           $sql1.="GROUP BY z01_numcgm,z01_v_nome,z01_nome,z01_d_nasc, z01_nasc, sd29_d_data, z01_v_bairro,z01_bairro,sd02_i_codigo,descrdepto
                   ORDER BY $orderpac
                  ";
                   
  $result1 = pg_query($sql1);
  $linhas1 = pg_num_rows($result1);
  $cont = 0;
  $cor1 = "1";
  $cor2 = "0";
  $cor  = "";
  for ($x = 0;$x < $linhas1;$x++){
   db_fieldsmemory($result1,$x);
   if($cor==$cor1){
    $cor = $cor2;
   }else{
    $cor = $cor1;
   }
   if($Array[0]==1) $cor = "0";
   if(($pdf->gety() > $pdf->h -30)){
    if($cont==0){
     $pdf->setfillcolor(255);
     $pdf->rect(10,$pdf->getY()-8,190,10,'F');
     $pdf->setfillcolor(200);
    }
    $pdf->addpage();
    $pdf->setfillcolor(200);
    $pdf->setfont('arial','b',8);
    $pdf->cell(140,4,"CID: $Array[1] $Array[2]","BTL",0,"L",1);
    $pdf->cell(50,4,"Total deste CID: $Array[0]","BTR",1,"R",1);
    $pdf->setfont('arial','',4);
    $pdf->setfillcolor(240);
    $pdf->cell(60,4,"Paciente:",0,0,"L",0);
    $pdf->cell(15,4,"Nasc.:",0,0,"L",0);
    $pdf->cell(30,4,"Bairro:",0,0,"L",0);
    $pdf->cell(60,4,"Unidade:",0,0,"L",0);
    $pdf->cell(15,4,"Dt Atend.",0,0,"L",0);
    $pdf->cell(30,4,"Qtd:",0,1,"L",0);
   }
   $pdf->setfillcolor(240);
   $pdf->setfont('arial','',6);
   $pdf->cell(60,4,($x+1)." - ".$z01_nome,0,0,"L",$cor);
   $pdf->cell(30,4,$z01_bairro,0,0,"L",$cor);
   $dt_nasc = substr( $z01_nasc, 8, 2 )."/".substr( $z01_nasc, 5, 2 )."/".substr( $z01_nasc, 0, 4 );
   $dt_atend = substr( $sd29_d_data, 8, 2 )."/".substr( $sd29_d_data, 5, 2 )."/".substr( $sd29_d_data, 0, 4 );
   $pdf->cell(15,4,$dt_nasc,0,0,"L",$cor);
   $pdf->cell(60,4,$sd02_i_codigo." - ".$descrdepto,0,0,"L",$cor);
   $pdf->cell(15,4,$dt_atend,0,0,"L",$cor);
   $pdf->cell(10,4,$qtdpaciente,0,1,"L",$cor);
   $cont++;
  }
 }
}
$pdf->setfont('arial','b',9);
$pdf->cell(190,6,"Total Geral de CIDs: $s_total",1,1,"L",0);
$pdf->Output();
@pg_free_result($query);
?>