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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$db_anousu= db_getsession("DB_anousu");

  $sqlunica = "select k00_dtvenc, k00_percdes, count(*) from recibounica inner join iptunump on k00_numpre = j20_numpre where k00_tipoger = 'G' and j20_anousu = " . db_getsession("DB_anousu") . " GROUP BY k00_dtvenc, k00_percdes";
  $resultunica = db_query($sqlunica);

if($agrupar=='m'){
  if($ordenar=="om")
    $ordem = " j01_matric";
  else if($ordenar =="on")
    $ordem = " proprietario ";
  else if($ordenar =="oa")
    $ordem = " valor ";
  else if($ordenar =="od")
    $ordem = " valor desc ";
 

   $left = "";
   $camposleft = "";

   for ($unica = 0; $unica < pg_numrows($resultunica); $unica++) {
     db_fieldsmemory($resultunica, $unica);

     $camposleft .= ($camposleft == ""?"":", ") . "r$unica.k00_numpre as k00_numpre_$unica, r$unica.k00_dtvenc as k00_dtvenc_$unica ";

     $left .= "left join recibounica r$unica on r$unica.k00_numpre 	= j20_numpre and 
						r$unica.k00_dtvenc 	= '$k00_dtvenc' and 
						r$unica.k00_percdes 	= $k00_percdes";

//						r$unica.k00_dtoper 	= '$k00_dtoper' and 

   }
  
 
  $sql="select xxx.* ".( !empty($camposleft)? ",".$camposleft :$camposleft) ."  

  		from 	(
  			select 	j01_matric,
				proprietario,
				nomepri, 
				j39_numero, 
				j39_compl, 
				j34_setor||'/'||j34_quadra||'/'||j34_lote as sql, 
				sum as valor,
				j20_numpre
			from
				(	
				select 	j21_matric,
					j20_numpre,
					sum(j21_valor)
				from iptucalv
	     			inner join iptunump on 	j20_anousu = j21_anousu and 
							j20_matric = j21_matric
				where j21_anousu = ".db_getsession("DB_anousu")." 
				group by j21_matric, j20_numpre
				) as calc
			inner join proprietario on j01_matric = j21_matric
			) as xxx
	  $left
     "; 

}else{
  
  if($ordenar =="on" || $ordenar == "om")
    $ordem = " proprietario ";
  else if($ordenar =="oa")
    $ordem = " valor ";
  else if($ordenar =="od")
    $ordem = " valor desc ";

  $sql="select 	proprietario,
		sum as valor
	from
	(	
	select j21_matric,sum(j21_valor)
	from iptucalv
	     inner join iptunump on j20_anousu = j21_anousu and j20_matric = j21_matric
	where j21_anousu = ".db_getsession("DB_anousu")." 
	group by j21_matric) as calc
	     inner join proprietario on j01_matric = j21_matric ";
 
}

if($valorm>0){
  if($valortipo=='maiores'){
    $sql = "select * from ($sql) as calculo
            where valor >= $valorm";
  }else{
    $sql = "select * from ($sql) as calculo
            where valor <= $valorm";
  }
}

$sql .= " order by ".$ordem;

if($quantidade > 0){
  $sql .= " limit $quantidade ";
}

$result=db_query($sql) or die($sql);
if(pg_numrows($result)==0){
  // db_redireciona('db_erros.php?fechar=true&db_erro=Não existem matrículas calculadas: Exercício:'.$db_anousu);
   exit;
}else{

  //  db_criatabela($result);

  $head4 = "RELATÓRIO CARNES DO IPTU ";
  $head5 = "EXERCÍCIO DE " . $db_anousu;
  $borda = 1; 
  $bordat = 1;
  $preenc = 1;
  $TPagina = 57;

  $pdf = new PDF("L"); // abre a classe
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  $pdf->AddPage(); // adiciona uma pagina
  $pdf->SetTextColor(0,0,0);
  $pdf->SetFillColor(235);
  $pdf->SetFont('Courier','',7);
  $preenc = "0";
  $linha = 0;
  $bordat = 0;
  $conta=0;
  $valortot=0;
  
  $pagina = 1;


  for ($unica = 0; $unica < pg_numrows($resultunica); $unica++) {
    $vartotal = "total$unica";
    $$vartotal = 0;
  }

  
  for($i = 0;$i < pg_numrows($result);$i++) {
//  for($i = 0;$i < 200;$i++) {
    db_fieldsmemory($result,$i);

//    echo "matric: $j01_matric - $i - " . pg_numrows($result) . "<br>";
//    flush();
    
    if ( $agrupar!="n" && ($pdf->gety() > $pdf->h - 30 or $pagina == 1)){
      $pagina = 0;
      $pdf->ln();
      $pdf->SetFont('Courier','B',7);
      $pdf->Cell(15,4,"MATRÍCULA",$bordat,0,"L",$preenc);
      $pdf->Cell(70,4,"PROPRIETÁRIO",$bordat,0,"L",$preenc);
      $pdf->Cell(60,4,"ENDEREÇO",$bordat,0,"L",$preenc);
      $pdf->Cell(10,4,"NÚMERO",$bordat,0,"L",$preenc);
      $pdf->Cell(35,4,"COMPLEMENTO",$bordat,0,"L",$preenc);
      $pdf->Cell(25,4,"S/Q/L",$bordat,0,"L",$preenc);
      $pdf->Cell(20,4,"VALOR TOTAL",$bordat,0,"R",$preenc);

      for ($unica = 0; $unica < pg_numrows($resultunica); $unica++) {
	       db_fieldsmemory($resultunica, $unica);
	       $pdf->Cell(20,4,"UNICA " . $k00_percdes . "%",$bordat,0,"R",$preenc);
      }
      $pdf->SetFont('Courier','',7);
      $pdf->ln();
    }

    if ($i%2==0){
       $preenc=1;
    } else {
       $preenc=0;
    }
    if($agrupar=="n"){

      $pdf->Cell(70,4,$proprietario,$bordat,0,"L",$preenc);
      $pdf->Cell(40,4,db_formatar($valor,'f'),$bordat,1,"R",$preenc);
    
    }else{

      $pdf->Cell(15,4,$j01_matric,$bordat,0,"L",$preenc);
      $pdf->Cell(70,4,$proprietario,$bordat,0,"L",$preenc);
      $pdf->Cell(60,4,$nomepri,$bordat,0,"L",$preenc);
      $pdf->Cell(10,4,$j39_numero,$bordat,0,"L",$preenc);
      $pdf->Cell(35,4,$j39_compl,$bordat,0,"L",$preenc);
      $pdf->Cell(25,4,$sql,$bordat,0,"L",$preenc);
      $pdf->Cell(20,4,db_formatar($valor,'f'),$bordat,0,"R",$preenc);

      for ($unica = 0; $unica < pg_numrows($resultunica); $unica++) {
	       db_fieldsmemory($resultunica, $unica);

	       $varnumpre = "k00_numpre_$unica";
	       $varvenc   = "k00_dtvenc_$unica";

        if ($$varnumpre != "") {

				  $sqlvalunica = "select fc_calcula(" . $$varnumpre . ",0,0,'" . $$varvenc . "','" . $$varvenc . "'," . db_getsession("DB_anousu") . ")";
				  $resultvalunica = pg_exec($sqlvalunica);
				  db_fieldsmemory($resultvalunica, 0);
			
				  $uvlrhis =  substr($fc_calcula,1,13);
				  $uvlrcor = substr($fc_calcula,14,13);
				  $uvlrjuros = substr($fc_calcula,27,13);
				  $uvlrmulta = substr($fc_calcula,40,13);
				  $uvlrdesconto = substr($fc_calcula,53,13);
				  $utotal = $uvlrcor + $uvlrjuros + $uvlrmulta - $uvlrdesconto;
			
				  $vartotal = "total$unica";
				  $$vartotal += $utotal;
				  
				  $pdf->Cell(20,4,db_formatar($utotal,'f'),$bordat,0,"R",$preenc);
			
				} else {
				  $pdf->Cell(20,4,db_formatar(0,'f'),$bordat,0,"R",$preenc);
				}
	
      }
      $pdf->ln();
      
    }
    $valortot += $valor;

    $linha ++;
  }
  
  $bordat= '0';
  $pdf->Ln(2);

  $pdf->SetFont('Courier','B',7);
  if($agrupar=="n"){
    $pdf->Cell(40,4,"TOTAL DE REGISTROS",$bordat,0,"L",$preenc);
    $pdf->Cell(30,4,$linha,$bordat,0,"L",$preenc);
    $pdf->Cell(20,4,'Total:',$bordat,0,"L",$preenc);
    $pdf->Cell(20,4,$valortot,$bordat,1,"R",$preenc);
  }else{
    $pdf->Cell(30,4,"TOTAL DE REGISTROS",$bordat,0,"L",$preenc);
    $pdf->Cell(105,4,$linha,$bordat,0,"L",$preenc);
    $pdf->Cell(80,4,"",$bordat,0,"R",$preenc);
    $pdf->Cell(20,4,db_formatar($valortot, 'f'),$bordat,0,"R",$preenc);

    for ($unica = 0; $unica < pg_numrows($resultunica); $unica++) {

      $vartotal = "total$unica";
      $pdf->Cell(20,4,db_formatar($$vartotal,'f'),$bordat,0,"R",$preenc);
      
    }
    $pdf->ln();

  }
  $pdf->Output();
}
?>