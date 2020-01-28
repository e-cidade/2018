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

set_time_limit(0);
include("libs/db_sql.php");
require("fpdf151/pdf.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);

//tratamento de erros

if ($atividades != "") {
  $atividades = str_replace("-", "," ,$atividades);
} else {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma atividade foi selecionada!");
}

/*
* varíavel ordem vem do post indicando o tipo de ordenação da consulta SQL
* tipos de valores que pode assumir de acordo pelo select do html:
* a = ordem alfabética
* v = valor descrescente (agrupa pelo valor total da atividade)
* n = código da atividade 
*/
switch ($ordem) {
// Alfabética
	case "a":
	/*
	* atividade  nome 
	* 200 a b c
	* 201 a b c
	*/      
	$ordem = " q07_ativ, z01_nome";
	break;	

// Valor decrescente
	case "v":
	// se marcado para quebrar por atividade  
	if ($quebrar == "s") {
		$ordem = "q07_ativ, ( sum(janeiro)+sum(fevereiro)+sum(marco)+sum(abril)+sum(maio)+sum(junho)+
		                   sum(julho)+sum(agosto)+sum(setembro)+sum(outubro)+sum(novembro)+sum(dezembro) ) desc";  
	} else {
		$ordem = "(sum(janeiro)+sum(fevereiro)+sum(marco)+sum(abril)+sum(maio)+sum(junho)+
		                    sum(julho)+sum(agosto)+sum(setembro)+sum(outubro)+sum(novembro)+sum(dezembro) ) desc";  
	}
	break;	

// Código da atividade
	case "n":
	$ordem = " q07_ativ";  
	break;		
}

// prepara o array com todos os meses do ano usado na consulta SQL
$aMeses			=array(); 
$aMeses[1]		="janeiro";
$aMeses[2]		="fevereiro";
$aMeses[3]		="marco";
$aMeses[4]		="abril";
$aMeses[5]		="maio";
$aMeses[6]		="junho";
$aMeses[7]		="julho";
$aMeses[8]		="agosto";    
$aMeses[9]		="setembro";
$aMeses[10]	="outubro";
$aMeses[11]	="novembro";
//mês de dezembro é retira a virgula do sql no laço for
//$aMeses[12] = "dezembro";

$sSql  = "select  q07_ativ,                                    ";                                   
$sSql .= "			   q03_descr,                                   ";
$sSql .= "            z01_nome,                                    ";
$sSql .= "case                                         ";
$sSql .= "when   q02_inscr is null or q02_inscr = 0    "; 
$sSql .= "then    'CGM : '||k00_numcgm                "; 
$sSql .= "else                                       "; 
$sSql .= "           cast(q02_inscr as varchar)               "; 
$sSql .= "			   end as k00_inscr,                            ";
$sSql .= "			   sum(janeiro) as janeiro,                     ";
$sSql .= " 		   sum(fevereiro) as fevereiro,                 ";
$sSql .= "			   sum(marco) as marco,                         ";
$sSql .= "			   sum(abril) as abril,                         ";
$sSql .= "  		   sum(maio) as maio,                           ";
$sSql .= "			   sum(junho) as junho,                         ";
$sSql .= "		       sum(julho) as julho,                         ";
$sSql .= "      	   sum(agosto) as agosto,                       ";
$sSql .= "			   sum(setembro) as setembro,                   ";
$sSql .= "			   sum(outubro) as outubro,                     ";
$sSql .= "			   sum(novembro) as novembro,                   ";
$sSql .= "			   sum(dezembro) as dezembro                    ";
$sSql .= "from (                                            ";
$sSql .= "			   select                                             ";
$sSql .= "						 q02_inscr,                                       ";
$sSql .= "						 k00_numcgm,                                      ";
$sSql .= "				 case                                         	   ";
$sSql .= "				 when tabativ.q07_ativ is null              		 ";
$sSql .= "				  then '99999'                             		 ";
$sSql .= "				   else tabativ.q07_ativ                      		 ";
$sSql .= "						  end as q07_ativ,                             		 ";
$sSql .= "				  case                                         	 	 ";
$sSql .= "				 when ativid.q03_descr is null             	   "; 
$sSql .= "				  then 'SEM INSCRIÇÃO CADASTRADA'      				 ";
$sSql .= "				   else  ativid.q03_descr                      		 ";
$sSql .= "					       end as q03_descr,                          		   ";
$sSql .= "						   coalesce(arreinscr.k00_inscr,0) as k00_inscr, ";
$sSql .= "				   case                                         ";
$sSql .= "				  when cgm_issbase.z01_nome is null          ";
$sSql .= "				   then cgm_debito.z01_nome                 ";
$sSql .= "				    else cgm_issbase.z01_nome                  ";
$sSql .= "							end as z01_nome,                             ";



  // A unica diferenca entre competencia e pagamento esta no subselect dos meses
  // COMPETÊNCIA
  if ($tipo == "c") {
    $head7 = "COMPETÊNCIA";	
	
    // quando for por competência temos que dar um inner join com issvar e filtar pelo campo q05_mes
    for($i=1; $i<12; $i++) {

      $sSql .= "      coalesce( (select sum(k00_valor)                                             											";
      $sSql .= "                    from arrepaga                                                  											";
      $sSql .= "                         inner join issvar a on a.q05_numpre = arrepaga.k00_numpre 											";
      $sSql .= "                                            and a.q05_numpar = arrepaga.k00_numpar 											";
      $sSql .= "                   where k00_numpre = issvar.q05_numpre  													 											";   
      $sSql .= "                     and q05_mes = $i and q05_mes < $mesfim                                                              ";
      $sSql .= "                     and q05_ano = {$ano}) ,0) as $aMeses[$i],                                          ";          

    }

    // tira a virgula da consulta quando for mês de dezembro
    $sSql .= "      coalesce( (select sum(k00_valor)                                             											";
    $sSql .= "                    from arrepaga                                                  											";
    $sSql .= "                         inner join issvar a on a.q05_numpre = arrepaga.k00_numpre 											";
    $sSql .= "                                            and a.q05_numpar = arrepaga.k00_numpar 											";
    $sSql .= "                   where k00_numpre = issvar.q05_numpre  	and q05_mes <= $mesfim												 											";   
    $sSql .= "                     and q05_mes = 12 and q05_ano = {$ano}) ,0) as dezembro "; 

  } else {

  // PAGAMENTO
  $head7 = "PAGAMENTO";
	  for ($i=1; $i<12; $i++) {

      $sSql .= "    coalesce( (select sum(k00_valor)                                                  "; 
      $sSql .= "                    from arrepaga                                                     ";
      $sSql .= "                   where k00_numpre = issvar.q05_numpre	                              ";
      $sSql .= "                         and extract(year from k00_dtpaga)  = {$ano}                  ";
			$sSql .= "                         and extract(month from k00_dtpaga)  < {$mesfim}              "; 
		  $sSql .= "                         and extract(month from k00_dtpaga) = $i) ,0) as $aMeses[$i], "; 

    }

    // mês de dezembro retira a virgula do SQL
    $sSql .= "    coalesce( (select sum(k00_valor)                                              "; 
    $sSql .= "                    from arrepaga                                                 ";
    $sSql .= "                   where k00_numpre = issvar.q05_numpre	                          ";
    $sSql .= "                         and extract(year from k00_dtpaga)  = {$ano}              ";
		$sSql .= "                         and extract(month from k00_dtpaga)  < {$mesfim}          ";  
		$sSql .= "                         and extract(month from k00_dtpaga) = 12) ,0) as dezembro "; 

  }	
	
  $sSql .= "  from issvar                                                                        ";
  $sSql .= "       inner join arrepaga         on arrepaga.k00_numpre    = issvar.q05_numpre     ";  
  $sSql .= "                                  and arrepaga.k00_numpar    = issvar.q05_numpar     ";
  $sSql .= "       left  join arreinscr        on arreinscr.k00_numpre   = issvar.q05_numpre     ";
  $sSql .= "       left  join issbase          on issbase.q02_inscr      = arreinscr.k00_inscr   ";
  $sSql .= "       left  join cgm cgm_issbase  on cgm_issbase.z01_numcgm = issbase.q02_numcgm    ";
  $sSql .= "       left  join cgm cgm_debito   on cgm_debito.z01_numcgm  = arrepaga.k00_numcgm   ";
	$sSql .= "       left  join db_cgmruas       on db_cgmruas.z01_numcgm  = cgm_debito.z01_numcgm ";
  $sSql .= "       left  join ativprinc        on ativprinc.q88_inscr    = issbase.q02_inscr     ";
  $sSql .= "       left  join tabativ          on tabativ.q07_inscr      = ativprinc.q88_inscr   ";    
  $sSql .= "                                  and tabativ.q07_seq        = ativprinc.q88_seq     ";
  $sSql .= "       left  join ativid           on ativid.q03_ativ        = tabativ.q07_ativ      ";
  $sSql .= "       left  join clasativ         on clasativ.q82_ativ      = tabativ.q07_ativ      ";

	if ($tipo == "c") {
	  // para competencia
    $sSql .= " where q05_ano = {$ano} and q05_mes between {$mesini} and {$mesfim} "; 
	} else {
    // para pagamento
    $sSql .= " where arrepaga.k00_dtpaga between '{$ano}-".$mesini."-01' and '{$ano}-".$mesfim."-01' "; 		
	}
	
  $sSql .= "   and ( clasativ.q82_classe in ( $classes ) or clasativ.q82_classe is null )        ";
  $sSql .= "   and ( ativid.q03_ativ     in ( $atividades ) or ativid.q03_ativ is null )         ";
  $sSql .= "  group by                                                                           ";
  $sSql .= "          q02_inscr,                                                                 ";
  $sSql .= "          q03_descr,                                                                 ";
  $sSql .= "          k00_numcgm,                                                                ";
  $sSql .= "          db_cgmruas.z01_numcgm,                                                     ";
  $sSql .= "          arreinscr.k00_inscr,                                                       ";
  $sSql .= "          issvar.q05_numpre,                                                         ";
	$sSql .= "           case                                                                      ";
  $sSql .= "             when tabativ.q07_ativ is null                                           ";
  $sSql .= "               then '99999'                                                          ";
  $sSql .= "             else tabativ.q07_ativ                                                   ";
  $sSql .= "           end,                                                                      ";
  $sSql .= "           case                                                                      ";
  $sSql .= "             when ativid.q03_descr is null                                           ";
  $sSql .= "               then 'FORA DO MUNICIPIO'                                              ";
  $sSql .= "             else ativid.q03_descr                                                   ";
  $sSql .= "           end,                                                                      ";
  $sSql .= "           coalesce(arreinscr.k00_inscr,0),                                          ";
  $sSql .= "           case                                                                      ";
  $sSql .= "             when cgm_issbase.z01_nome is null                                       ";
  $sSql .= "               then cgm_debito.z01_nome                                              ";
  $sSql .= "             else cgm_issbase.z01_nome                                               ";
  $sSql .= "           end                                                                       ";
  $sSql .= "  order by arreinscr.k00_inscr                                                       ";      
  $sSql .= " ) as x                                                                              ";                                                 
  $sSql .= " group by q02_inscr,                                                                 ";
  $sSql .= "          k00_numcgm,                                                                ";
  $sSql .= "          q07_ativ,                                                                  ";
  $sSql .= "          q03_descr,                                                                 ";
  $sSql .= "          k00_inscr,                                                                 ";
  $sSql .= "          z01_nome                                                                   ";
  $sSql .= " order by $ordem                                                                     ";



$msgErro = "Ocorreu erro durante o processamento das informações!<br />Entre em contato com o Administrador do Sistema!";
$result = pg_exec($sSql) or die(pg_last_error());
$num = pg_numrows($result);
if ($num == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe pagamentos efetuados no período de '.$mesini."/".$ano." até ".$mesfim."/".$ano);
}

$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setleftmargin(5);

$head2 = "RELATÓRIO DOS PAGAMENTOS";
$head3 = "ISSQN VARIÁVEL";
$head5 = "PERÍODO DE : ".$mesini."/".$ano." A ".$mesfim."/".$ano;

$pdf->addpage('L');
$pdf->SetFillColor(220);
$pdf->SetTextColor(0,0,0);

$espaco = 16.5;
$altura = 4.5;

// xtotal
$xtotaljaneiro   = 0;
$xtotalfevereiro = 0;
$xtotalmarco     = 0;
$xtotalabril     = 0;
$xtotalmaio      = 0;
$xtotaljunho     = 0;
$xtotaljulho     = 0;
$xtotalagosto    = 0;
$xtotalsetembro  = 0;
$xtotaloutubro   = 0;
$xtotalnovembro  = 0;
$xtotaldezembro  = 0;
$xtotalgeral     = 0;

// total
$totaljaneiro    = 0;
$totalfevereiro  = 0;
$totalmarco      = 0;
$totalabril      = 0;
$totalmaio       = 0;
$totaljunho      = 0;
$totaljulho      = 0;
$totalagosto     = 0;
$totalsetembro   = 0;
$totaloutubro    = 0;
$totalnovembro   = 0;
$totaldezembro   = 0;
$totalgeral      = 0;

db_fieldsmemory($result,0);

$xativid = $q07_ativ;
$xdescr  = $q03_descr;

$pdf->SetFont('Arial','B',10);
if ($totais == "t") {
  $pdf->cell(50,$altura,'',0,0,'C',0);
}

if ($quebrar == "s") {
  $pdf->cell(150,8,$q07_ativ.' - '.$q03_descr,0,1,"L",0);
} else {
  $pdf->cell(150,8,"",0,1,"L",0);
}
$pdf->SetFont('Arial','B',6);
if ($totais == "t") {
  $pdf->cell(50,$altura,'',0,0,'C',0);
}
$pdf->cell(16,$altura,'Inscr.',1,0,'C',1);
$pdf->cell(50,$altura,'Nome/Razão Social',1,0,'C',1);
if ($totais == "m") {
  for ($x = 1; $x < 13; $x++) {
    if ($x < 12){
      $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
    } else {
      $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
    }
     
     $mesini += 1;
     if ($mesini > 12){
	     $mesini = 1;
     }
   }
}
$pdf->cell(20,$altura,'Total',1,1,'C',1);
for ( $i = 0; $i < $num; $i++) {
  db_fieldsmemory($result,$i);
   
  if ( $pdf->gety() > $pdf->h - 40 || $xativid <> $q07_ativ) {
   
    if ($pdf->gety() > $pdf->h - 40) {
      $pdf->addpage('L');
      $pdf->ln(3);
      $pdf->SetFont('Arial','B',10);
	 
	  if ($totais == "t") {
	    $pdf->cell(50,$altura,'',0,0,'C',0);
	  }
	
     if ($quebrar == "s") {
        $pdf->cell(150,8,$xativid.' - '.$xdescr,0,1,"L",0);
	  } else {
	    $pdf->cell(150,8,"",0,1,"L",0);
	  }
          $pdf->SetFont('Arial','B',6);
	 
	  if ($totais == "t") {
	    $pdf->cell(50,$altura,'',0,0,'C',0);
	  }
        $pdf->cell(16,$altura,'Inscr.',1,0,'C',1);
        
				$pdf->cell(50,$altura,'Nome/Razão Social',1,0,'C',1);
      
      if ($totais == "m") {
	    for ($x = 1; $x < 13;$x++) {
	      if ($x < 12) {
	        $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
	      } else {
	        $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
	      } 
	      $mesini += 1;
	      if ($mesini > 12) {
	        $mesini = 1;
	      }
	    }
      }
     
	  $pdf->cell(20,$altura,'Total',1,1,'C',1);
	 
  }
      if ($xativid <> $q07_ativ and ($quebrar == "s")){
	 if ($totais == "t") {
	   $pdf->cell(50,$altura,'',0,0,'C',0);
	 }
         $pdf->cell(66,$altura,'Total da Atividade',1,0,'C',0);
     if ($totais == "m") {
	   $pdf->cell($espaco,$altura,db_formatar($xtotaljaneiro,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalfevereiro,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalmarco,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalabril,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalmaio,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotaljunho,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotaljulho,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalagosto,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalsetembro,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotaloutubro,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotalnovembro,'f'),1,0,'R',0);
	   $pdf->cell($espaco,$altura,db_formatar($xtotaldezembro,'f'),1,0,'R',0);
	 }
         $pdf->cell(20,$altura,db_formatar($xtotalgeral,'f'),1,1,'R',0);
         $xtotaljaneiro   = 0;
         $xtotalfevereiro = 0;
         $xtotalmarco     = 0;
         $xtotalabril     = 0;
         $xtotalmaio      = 0;
         $xtotaljunho     = 0;
         $xtotaljulho     = 0;
         $xtotalagosto    = 0;
         $xtotalsetembro  = 0;
         $xtotaloutubro   = 0;
         $xtotalnovembro  = 0;
         $xtotaldezembro  = 0;
         $xtotalgeral     = 0;
         $pdf->ln(3);
         $pdf->SetFont('Arial','B',10);
	 if ($totais == "t") {
	   $pdf->cell(50,$altura,'',0,0,'C',0);
	 }
         $pdf->cell(150,8,$q07_ativ.' - '.$q03_descr,0,1,"L",0);
         $pdf->SetFont('Arial','B',6);
	 if ($totais == "t") {
	   $pdf->cell(50,$altura,'',0,0,'C',0);
	 }
         $pdf->cell(16,$altura,'Inscr.',1,0,'C',1);
         $pdf->cell(50,$altura,'Nome/Razão Social',1,0,'C',1);
         if ($totais == "m") {
	   for ($x = 1; $x < 13;$x++){
	      if ($x < 12){
		 $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
	      }else{
		 $pdf->cell($espaco,$altura,db_formatar($mesini,'s','0',2,'e').'/'.$ano,1,0,'C',1);
	      }
	      $mesini += 1;
	      if ($mesini > 12){
  		    $mesini = 1;
	      }
	   }
	 }
         $pdf->cell(20,$altura,'Total',1,1,'C',1);
      }
   }
   $pdf->SetFont('Arial','',6);
   $total = $janeiro+$fevereiro+$marco+$abril+$maio+$junho+$julho+$agosto+$setembro+$outubro+$novembro+$dezembro;
   if ($total > 0) {
      if ($totais == "t") {
        $pdf->cell(50,$altura,'',0,0,'C',0);
      }
			// linhas da coluna Inscr.
      $pdf->cell(16,$altura,$k00_inscr,1,0,'C',0);
			
      $pdf->cell(50,$altura,substr($z01_nome,0,35),1,0,'L',0);
      if ($totais == "m") {
	$pdf->cell($espaco,$altura,db_formatar($janeiro,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($fevereiro,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($marco,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($abril,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($maio,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($junho,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($julho,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($agosto,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($setembro,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($outubro,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($novembro,'f'),1,0,'R',0);
	$pdf->cell($espaco,$altura,db_formatar($dezembro,'f'),1,0,'R',0);
      }
      $pdf->cell(20,$altura,db_formatar($total,'f'),1,1,'R',0);
      $xtotaljaneiro   += $janeiro;
      $xtotalfevereiro += $fevereiro;
      $xtotalmarco     += $marco;
      $xtotalabril     += $abril;
      $xtotalmaio      += $maio;
      $xtotaljunho     += $junho;
      $xtotaljulho     += $julho;
      $xtotalagosto    += $agosto;
      $xtotalsetembro  += $setembro;
      $xtotaloutubro   += $outubro;
      $xtotalnovembro  += $novembro;
      $xtotaldezembro  += $dezembro;
      $xtotalgeral     += $total;
      
      $xativid = $q07_ativ;
      $xdescr  = $q03_descr;
      
      $totaljaneiro   += $janeiro;
      $totalfevereiro += $fevereiro;
      $totalmarco     += $marco;
      $totalabril     += $abril;
      $totalmaio      += $maio;
      $totaljunho     += $junho;
      $totaljulho     += $julho;
      $totalagosto    += $agosto;
      $totalsetembro  += $setembro;
      $totaloutubro   += $outubro;
      $totalnovembro  += $novembro;
      $totaldezembro  += $dezembro;
      $totalgeral     += $total;
   }
}
if ($totais == "t") {
$pdf->cell(50,$altura,'',0,0,'C',0);
}
if ($quebrar == "s") {
  $pdf->cell(66,$altura,'Total da Atividade',1,0,'C',0);
  if ($totais == "m") {
    $pdf->cell($espaco,$altura,db_formatar($xtotaljaneiro,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalfevereiro,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalmarco,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalabril,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalmaio,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotaljunho,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotaljulho,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalagosto,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalsetembro,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotaloutubro,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotalnovembro,'f'),1,0,'R',0);
    $pdf->cell($espaco,$altura,db_formatar($xtotaldezembro,'f'),1,0,'R',0);
  }
  $pdf->cell(20,$altura,db_formatar($xtotalgeral,'f'),1,1,'R',0);
  $pdf->ln(5);
} else {
  $pdf->ln(0);
}
if ($totais == "t") {
$pdf->cell(50,$altura,'',0,0,'C',0);
}
$pdf->cell(66,$altura,'Total Geral',1,0,'C',0);
if ($totais == "m") {
  $pdf->cell($espaco,$altura,db_formatar($totaljaneiro,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalfevereiro,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalmarco,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalabril,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalmaio,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totaljunho,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totaljulho,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalagosto,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalsetembro,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totaloutubro,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totalnovembro,'f'),1,0,'R',0);
  $pdf->cell($espaco,$altura,db_formatar($totaldezembro,'f'),1,0,'R',0);
}
$pdf->cell(20,$altura,db_formatar($totalgeral,'f'),1,1,'R',0);

$pdf->ln(2);
// $pdf->cell(60,$altura,'0 = EMPRESAS DE OUTRO MUNICIPIO',0,0,'R',0);

$pdf->Output();
?>