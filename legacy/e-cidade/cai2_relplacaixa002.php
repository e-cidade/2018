<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("libs/db_utils.php");

//$clslip = new cl_slip;

$clrotulo = new rotulocampo;
//$clrotulo->label('k17_codigo');
//$clrotulo->label('k17_data');
//$clrotulo->label('k17_debito');
//$clrotulo->label('k17_credito');
//$clrotulo->label('k17_valor');
//$clrotulo->label('k17_hist');
//$clrotulo->label('k17_texto');
//$clrotulo->label('k17_dtaut');
//$clrotulo->label('k17_autent');
//$clrotulo->label('c60_descr');
//$clrotulo->label('z01_nome');

$clrotulo->label('k81_seqpla');
$clrotulo->label('k81_codpla');
$clrotulo->label('k81_conta');
$clrotulo->label('k81_receita');
$clrotulo->label('k81_valor');
$clrotulo->label('k81_obs');
$clrotulo->label('k80_data');
$clrotulo->label('k80_instit');
$clrotulo->label('k80_dtaut');
$clrotulo->label('k13_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_GET_VARS,2);


$where = "where k80_instit = " . db_getsession('DB_instit');
$where1 =  "";
if (($data!="--")&&($data1!="--")) {
    $where .= " and  k80_data  between '$data' and '$data1'  ";
     }else if ($data!="--"){
	$where .=" and k80_data >= '$data'  ";
	}else if ($data1!="--"){
	   $where .=" and k80_data <= '$data1'   ";
	   }
				    
//$where1 = " and k81_conta = $conta";


//if ($conta=="a"){
//  $where1="  and k17_dtaut is not null  ";
//  }else if ($conta=="b"){
//    $where1="  and k17_dtaut is null  ";
//  }

if(trim($codigos)!=""){
  $where .= " and k81_conta ";  //" and k17_numcgm ";
  if($parametro=="N"){
    $where .= " not ";
  }
  $where .= " in ($codigos) ";
}

$head2 = "RELATÓRIO DE PLANILHAS DE RECOLHIMENTO ";
$head3 = "PERÍODO: ".db_formatar($data,"d")." A ".db_formatar($data1,"d");
                             /*slip.k17_codigo,
                             k17_data,
                             k17_debito,
	                     c1.c60_descr as debito_descr,
		             k17_credito,
			     c2.c60_descr as credito_descr,
			     k17_valor,
			     k17_hist,
			     k17_texto,
			     k17_dtaut,
			     k17_autent,
			     z01_numcgm,
			     z01_nome
                      from slip
	                     inner join conplanoreduz r1 on r1.c61_reduz = k17_debito
	                     inner join conplano c1 on c1.c60_codcon = r1.c61_codcon
	                     inner join conplanoreduz r2 on r2.c61_reduz = k17_credito
		             inner join conplano c2 on c2.c60_codcon = r2.c61_codcon
		             left  join slipnum on slipnum.k17_codigo = slip.k17_codigo
		             left  join cgm on cgm.z01_numcgm = slipnum.k17_numcgm
                      $where $where1
		      order by slip.k17_codigo" ); 
		      */
if ( isset($k144_numeroprocesso) && !empty($k144_numeroprocesso) ) {
  $where .= "  and k144_numeroprocesso = '{$k144_numeroprocesso}' ";
}

$sql =  " select distinct k80_codpla,
			           k80_data,
			           k80_instit,
			           k80_dtaut,
								 k12_estorn,
								 k12_data , 
								 k144_numeroprocesso 
            from placaixa	
		             inner join placaixarec on k80_codpla = k81_codpla
			           inner join saltes      on k81_conta  = k13_conta
								 left join  corplacaixa on k82_seqpla = k81_seqpla
							   left join corrente     on k82_id     = k12_id
												               and k82_data   = k12_data
												               and k82_autent = k12_autent
								 left join placaixaprocesso on  k80_codpla =  k144_placaixa     
          $where 
		      order by k80_codpla" ; 
			    
$result = db_query($sql);
$total  = pg_num_rows($result);
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');

}


if ( isset($k144_numeroprocesso) && !empty($k144_numeroprocesso) ) {
  
  if ($total >=1 ) {
    
    $sProcesso = db_utils::fieldsMemory($result,0)->k144_numeroprocesso;
    $head4 = "PROCESSO ADMINISTRATIVO: {$sProcesso}";
  }
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->addpage("L");
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->SetAutoPageBreak(0,15);
$pdf->setfont('arial','b',8);
$troca      = 1;
$prenc      = 0;
$alt        = 4;
$iCodPlaOld = 0;
$sPlaStorno = '';
for($x = 0; $x < pg_num_rows($result);$x++){
   
	 $fVlTotal = 0;
	 $troca    = 1;
   $prenc    = 0;
	 $oPla     = db_utils::fieldsMemory($result,$x);
	 
	 
	 $where2   = $oPla->k12_estorn == "t"?" and k12_estorn is true ":" and k12_estorn is false";
	 
   $sqlRec = "select distinct
                     k81_seqpla,
                     k13_conta,
	                   k13_descr,
										 k81_valor,
										 k81_codpla,
										 r.k02_codigo,
										 (case when k02_tipo = 'E'
										       then p.k02_reduz
													 when k02_tipo = 'O'
													 then o.k02_codrec
										 end
										 )as reduz,
  									 k02_descr,
										 k02_drecei,
										 k81_obs,
										 k12_estorn
							  from placaixarec 
								     inner join placaixa       on k81_codpla   = k80_codpla
                      left join placaixaprocesso on  k80_codpla =  k144_placaixa
          			     inner join saltes         on k81_conta    = k13_conta
										 inner join corplacaixa    on k81_seqpla   = k82_seqpla
										 inner join corrente       on k82_id       = k12_id
										                          and k82_data     = k12_data
										                          and k82_autent   = k12_autent
					 					 inner join tabrec r       on r.k02_codigo = k81_receita
										 left outer join tabplan p on p.k02_codigo = r.k02_codigo 
										                          and p.k02_anousu = ".db_getsession("DB_anousu")."
										 left outer join taborc o  on o.k02_codigo = r.k02_codigo 
										                          and o.k02_anousu = ".db_getsession("DB_anousu")."
							 $where																
							 $where2
						 	 and k81_codpla = ".$oPla->k80_codpla;
   
   $rsRec = db_query($sqlRec);
	 if (pg_num_rows($rsRec) > 0){

     for ($i = 0;$i < pg_num_rows($rsRec);$i++){

       $oRec  = db_utils::fieldsMemory($rsRec,$i);
				
				/*
				 * esse if controla o final de pagina. caso esteja no final da página, e a planilha e outra,
				 * comeca numa nova pagina, com uma margem maior.
				 * 
			   */
				if ($iCodPlaOld != $oRec->k81_codpla and $sPlaStorno != $oPla->k12_estorn){
          
					$iHeight = 48;
    
				}else{
           
					 $iHeight = 35;
			  }

			 /*
			  * Cabecalho
			  *
			 */
			  
	      if ($pdf->gety() > $pdf->h - $iHeight or $troca != 0){
            
				 $iYatual = 0;
				 if ($iCodPlaOld == 592){

			//		 echo $iHeight." --- ".$oRec->k12_estorn;
				 }
	       if ($pdf->gety() > $pdf->h - $iHeight){
					   
   	         $pdf->addpage("L");
						 $troca = 0;
		     }
         $pdf->setfont('arial','b',8); 
			   $pdf->cell(50,$alt,'Planilha',0,0);
			   $pdf->cell(50,$alt,$oPla->k80_codpla,0,1);
			   $pdf->cell(50,$alt,'Data da Criacao',0,0);
         $pdf->cell(50,$alt,db_formatar($oPla->k80_data,'d'),0,1);
			   $sEstorno = '';
			   if ($oPla->k12_estorn == 't'){


            $sEstorno = "(Estorno)";
			   }
         $pdf->cell(50,$alt,'Data Autenticação '.$sEstorno,0,0); 
         $pdf->cell(50,$alt,db_formatar($oPla->k12_data,'d'),0,1);
			   $pdf->ln(2);
			   $iYatual = $pdf->getY();	
			   $pdf->cell(90,$alt,"Conta Bancária","BT",1,"C",1);
			   $pdf->cell(20,$alt,"Código","BT",0,"C",1);
			   $pdf->cell(70,$alt,"Descrição","LBT",0,"C",1);
			   $pdf->setXY(90,$iYatual);
			   $pdf->cell(90,$alt,"Receita","BTL",1,"C",1);
			   $pdf->setX(90);
			   $pdf->cell(15,$alt,"Código","BTL",0,"C",1);
		   	 $pdf->cell(15,$alt,"Reduz","BTL",0,"C",1);
			   $pdf->cell(60,$alt,"Descrição","BTL",0,"C",1);
			   $pdf->setXY(180,$iYatual);
         $pdf->cell(90,($alt*2),"Histórico","BTL",0,"C",1); 
         $pdf->cell(20,($alt*2),'valor',"BTL",1,"C",1); 
				 $yNovo = $pdf->getY();
   
	    }
			//Fim do Cabecalho
      $troca = 0;
           
			$pdf->setfont('arial','',7);
			$pdf->setY($yNovo);
      $pdf->cell(20,$alt,$oRec->k13_conta,0,0,"C",$prenc);
      $pdf->cell(60,$alt,$oRec->k13_descr,0,0,"L",$prenc);
      $pdf->cell(15,$alt,$oRec->k02_codigo,0,0,"C",$prenc);
      $pdf->cell(15,$alt,$oRec->reduz,0,0,"C",$prenc);
      $pdf->cell(60,$alt,$oRec->k02_drecei,0,0,"L",$prenc);
			$iYatual2 = $pdf->getY();	
			$pdf->multicell(90,3,str_replace("\n","",trim($oRec->k81_obs)),0,"J",0,0); 
			$yNovo = $pdf->getY();
			$pdf->setxy(270,$iYatual2);
			if ($oRec->k81_valor < 0){
			    $pdf->setfont('arial','b',7);
			}

			$fValorPla = $oRec->k12_estorn == "t"?($oRec->k81_valor*-1):$oRec->k81_valor;
      $pdf->cell(20,$alt,number_format($fValorPla,2,",","."),0,1,"R",$prenc);
			$fVlTotal   += $fValorPla;
	    $iCodPlaOld = $oPla->k80_codpla;
			$sPlaStorno = $oPla->k12_estorn; //Planilha   estornada
    }
		$pdf->setY($yNovo+1);
	  $pdf->cell(260,$alt,"","BT",0,"C",1);
	  $pdf->cell(20,$alt,db_formatar($fVlTotal,'f'),"LBT",1,"R",1);
	  $pdf->ln();
	 }
}
$pdf->Output();
?>