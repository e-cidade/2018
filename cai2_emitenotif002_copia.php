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

include("libs/db_sql.php");
include("fpdf151/pdf3.php");
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if ( $lista == '' ) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Lista não encontrada!');
   exit; 
}
$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
db_fieldsmemory(pg_exec($sqlinst),0,true);

$head1 = 'Secretaria de Finanças';
$pdf = new PDF3(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 

$pdf->SetAutoPageBreak(true,0); 

$sql = "select * from lista where k60_codigo = $lista";
$result = pg_exec($sql);
db_fieldsmemory($result,0);

$sqllistatipo = "select listatipos.*, k03_descr from listatipos inner join arretipo on k00_tipo = k62_tipodeb inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo where k62_lista = $lista";
//die($sqllistatipo);
$resultlistatipo = pg_exec($sqllistatipo);
$virgula = '';
$tipos = '';
$descrtipo = '';
for($yy = 0;$yy < pg_numrows($resultlistatipo);$yy++ ){
   db_fieldsmemory($resultlistatipo,$yy);
   $tipos .= $virgula.$k62_tipodeb;
   $descrtipo .= $virgula.trim($k03_descr);
   $virgula = ' , ';
}

$sqllistadoc = "select * from listadoc where k64_codigo = $lista";
$resultlistadoc = pg_exec($sqllistadoc);

if ($resultlistadoc == false) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Erro ao procurar documento desta lista!');
   exit; 
}

if (pg_numrows($resultlistadoc) == 0) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Nao encontrou documento desta lista!');
   exit; 
}

//echo "tipo".$tipos ;exit;
if ($k60_tipo == 'M'){
    $xtipo    = 'Matrícula';
    $xcodigo  = 'matric';
//    $xcodigo1 = 'j01_matric';
    $xcodigo1 = 'j01_matric';
    $xxcodigo1 = 'k55_matric';
    $xxmatric = ' inner join notimatric on matric = k55_matric inner join proprietario_nome on j01_matric = matric ';
    $xxcodigo = 'k55_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and matric in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and matric not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}elseif($k60_tipo == 'I'){
    $xtipo    = 'Inscrição';
    $xcodigo  = 'inscr';
    $xcodigo1 = 'q02_inscr';
    $xxcodigo1 = 'k56_inscr';
    $xxmatric = ' inner join notiinscr on inscr = k56_inscr inner join issbase on q02_inscr = inscr 
    		inner join cgm on z01_numcgm = q02_numcgm';
    $xxcodigo = 'k56_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and inscr in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and inscr not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }

}elseif($k60_tipo == 'N'){
    $xtipo    = 'Numcgm';
    $xcodigo  = 'numcgm';
    $xcodigo1 = 'j01_numcgm';
    $xxcodigo1 = 'k57_numcgm';
    $xxmatric = ' inner join notinumcgm on numcgm = k57_numcgm inner join cgm on numcgm = z01_numcgm ';
    $xxcodigo = 'k57_notifica';
    if (isset($campo)){
       if ($tipo == 2){
          $contr = 'and numcgm in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
          $contr = 'and numcgm not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}

if($ordem == 'a'){
  $xxordem = ' order by z01_nome ';
}elseif($ordem == 't'){
  $xxordem = ' order by '.$xxcodigo;
}else{
  $xxordem = ' order by '.$xxcodigo1;
}
//echo $ordem."<br>";
//echo $xxordem."<br>";
if($fim > 0 && $intervalo == 'n'){
  $limite = 'and '.$xxcodigo.' >= '.$inicio.' and '.$xxcodigo.' <= '.$fim;
}else{
  $limite = '';
}

$sql999 = "select $xxcodigo as notifica,$xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
        from 
             (select distinct $xcodigo as $xcodigo1,
                     $xxcodigo,
                     z01_numcgm,
                     z01_nome,
                     valor_vencidos
              from 
                   (select distinct k61_numpre,k61_codigo,k60_datadeb 
 	            from listadeb 
	 	         inner join lista on k60_codigo = k61_codigo
                    where k61_codigo = $lista ) as a
              inner join devedores b on a.k61_numpre = b.numpre and b.data = a.k60_datadeb
              $xxmatric $limite $contr
              inner join cgm on z01_numcgm = b.numcgm
        where k61_codigo = $lista ) as y
        group by $xxcodigo,
                 $xcodigo1,
                 z01_numcgm,
                 z01_nome
        $xxordem
        ";
//die($sql999);

$sql = "select $xxcodigo as notifica,$xxcodigo1 as $xcodigo1,z01_numcgm,z01_nome,sum(valor_vencidos) as xvalor
        from lista
             inner join listanotifica on k63_codigo = k60_codigo
             inner join devedores on data = '$k60_datadeb' and numpre = k63_numpre
             $xxmatric $limite $contr and $xxcodigo = k63_notifica
        where k60_codigo = $lista 
        group by $xxcodigo,
                 $xxcodigo1,
                 z01_numcgm,
                 z01_nome
        $xxordem
        ";


//echo $sql;exit;
$result = pg_exec($sql);
//db_criatabela($result);
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhuma notificação gerada para a lista '.$lista);
   exit; 
} 


if($fim > 0 && $intervalo != 'n'){
  if($inicio > 0){
    $lim1 = $inicio - 1;
  }else{
    $lim = 0;
  }
  if($fim > pg_numrows($result)){
    $lim2 = pg_numrows($result);
  }else{
    $lim2 = $fim;
  }
}else{
  $lim1 = 0;
  $lim2 = pg_numrows($result);
}

if ( $tiporel == 1 ) {
  
   for($x=$lim1;$x < $lim2;$x++) {
      db_fieldsmemory($result,$x);

      $pdf->AddPage();
      $pdf->SetFont('Arial','',13);
      $numcgm = @$j01_numcgm;
      $matric = @$j01_matric;
      $inscr  = @$q02_inscr;
      if($matric != ''){
         $xmatinsc = " matric = ".$matric." and ";
         $xmatinsc22 = " k22_matric = ".$matric." and ";
         $matinsc = "sua matrícula n".chr(176)." ".$matric;
      }else if($inscr != ''){
         $xmatinsc = " inscr = ".$inscr." and ";
         $xmatinsc22 = " k22_inscr = ".$inscr." and ";
         $matinsc = "sua inscrição n".chr(176)." ".$inscr;
      }else{
         $xmatinsc = " numcgm = ".$numcgm." and ";
         $xmatinsc22 = " k22_numcgm = ".$numcgm." and ";
         $matinsc = "V.Sa.";
      }
      $matricula = $matric;
      $inscricao = $inscr;
      $jtipos = '';
      $num2 = 0;

      if ($tipos != ''){
	 $jtipos = ' tipo in ('.$tipos.') and ';
         $sql2 = "select tipo,k00_descr,sum(valor_vencidos) as valor from devedores inner join arretipo on k00_tipo = tipo where $xmatinsc tipo not in ($tipos) and data = '$k60_datadeb' group by tipo,k00_descr";
         $result2 = pg_exec($sql2);
	 $num2 = pg_numrows($result2);
      }

      if (1 == 2) {

	$sqltipos = "	select 	distinct cadtipo.k03_tipo, cadtipo.k03_descr 
			  from notidebitos
				  inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
				  inner join arretipo on k00_tipo = k22_tipo 
				  inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo
				  where k53_notifica = $notifica
				  ";
  //      die($sqltipos);
	$resultatipos = pg_exec($sqltipos);
	$virgula = '';
	$descrtipo = '';
	$codtipos = '';
	for($i = 0;$i < pg_numrows($resultatipos);$i++){
	   db_fieldsmemory($resultatipos,$i);
	   $descrtipo .= $virgula.$k03_descr;
	   $codtipos  .= $virgula.$k03_tipo;
	   $virgula = ', ';
	}

      }




      $cgm = $z01_numcgm;
      $sql1 = "select tipo,k00_descr,sum(valor_vencidos) as valor from devedores inner join arretipo on k00_tipo = tipo where $xmatinsc $jtipos data = '$k60_datadeb' group by tipo,k00_descr";
      $result1 = pg_exec($sql1);

      if ($k60_tipo == 'M'){

        $sqlpropri = "select z01_nome, codpri, nomepri, j39_numero, j39_compl, j34_setor, j34_quadra, j34_lote, j34_zona, j34_area, j01_tipoimp from proprietario where j01_matric = $matric";
        $resultpropri = pg_exec($sqlpropri);
	if (pg_numrows($resultpropri) > 0) {
	  db_fieldsmemory($resultpropri,0);
	 
	}

        $sqlender = "select fc_iptuender($matric)";
	//echo("matric: $matric<br>\n");
        $resultender = pg_exec($sqlender);
        db_fieldsmemory($resultender,0);

        $endereco = split("#",$fc_iptuender);

        $z01_ender    = $endereco[0];
        $z01_numero   = $endereco[1];
        $z01_compl    = $endereco[2];
        $z01_bairro   = $endereco[3];
        $z01_munic    = $endereco[4];
        $z01_uf       = $endereco[5];
        $z01_cep      = $endereco[6];
        $z01_cxpostal = $endereco[7];
	
      } elseif ($k60_tipo == 'I') {

	$imprime = "INSCRICAO: $q02_inscr";

	$sqlempresa = "select * from empresa where q02_inscr = $q02_inscr";
	$resultempresa = pg_exec($sqlempresa);
	if (pg_numrows($resultempresa) > 0) {
	  db_fieldsmemory($resultempresa,0);
	}

      } else {
	$sqlender = "select z01_ender, z01_numero, z01_compl, z01_bairro, z01_munic, z01_uf, z01_cep, z01_cxpostal from cgm where z01_numcgm = $cgm";
	$resultender = pg_exec($sqlender);
        db_fieldsmemory($resultender,0,true);
      }

      $impostos = '';
      $virgula  = '';
      $xvalor   = 0;
//      $notifica = 50; 

 
      $impostos  = 'DÍVIDA ATIVA' ;
      $impostos2 = '';
      $virgula2  = '';
      $xvalor2   = 0;

      $sqldocparagr = "select * from db_docparag 
      				inner join listadoc 		on db04_docum = k64_docum 
				inner join db_paragrafo 	on db02_idparag = db04_idparag 
				where k64_codigo = $lista
				order by db04_ordem";
      $resultdocparagr = pg_exec($sqldocparagr);
      global $db02_inicia;
      global $db02_espaca;
//-----------------DATA--------------------
      $sqltexto = "select munic from db_config where codigo = " . db_getsession("DB_instit");
      $resulttexto = pg_exec($sqltexto);
      db_fieldsmemory($resulttexto,0,true);
      $dia = date('d',strtotime($k60_datadeb));
      $mes = db_mes(date('m',strtotime($k60_datadeb)));
      $ano = date('Y',strtotime($k60_datadeb));
//---------------------------------
      $pdf->SetFont('Arial','',12);
      for ($doc = 0; $doc < pg_numrows($resultdocparagr); $doc++) {
        db_fieldsmemory($resultdocparagr,$doc);


        $texto=db_geratexto($db02_texto);

//				die("x: $texto - " . $pdf->GetStringWidth($texto));
	if (strtoupper($db02_descr) == "TOTALPORANO") {


	  $sqlanos = "	select 	extract (year from k22_dtoper) as k22_ano,
				    sum(k22_vlrcor) as k22_vlrcor, 
				    sum(k22_juros) as k22_juros, 
				    sum(k22_multa) as k22_multa, 
				    sum(k22_vlrcor+k22_juros+k22_multa) as k22_total 
			    from notidebitos
				    inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
				    inner join arretipo on k00_tipo = k22_tipo
				    where k53_notifica = $notifica
			    group by extract (year from k22_dtoper)";
	  //die($sqlanos);
	  $resultanos = pg_exec($sqlanos);
	  if ($resultanos == false) {
	    db_redireciona('db_erros.php?fechar=true&db_erro=Problemas ao gerar totais por anos! sql: '.$sqlanos);
	    exit;
	  }

	  
	  $pdf->cell(10,05,"",	             0,0,"C",0);
	  $pdf->setfillcolor(245);
	  $pdf->cell(15,05,"ANO",	     1,0,"C",1);
	  $pdf->cell(45,05,"VALOR CORRIGIDO",1,0,"C",1);
	  $pdf->cell(35,05,"JUROS",          1,0,"C",1);
	  $pdf->cell(35,05,"MULTA",          1,0,"C",1);
	  $pdf->cell(45,05,"VALOR TOTAL",    1,1,"C",1);
	  $pdf->setfillcolor(255,255,255);
	  
	  $totvlrcor=0;
	  $totjuros=0;
	  $totmulta=0;
	  $tottotal=0;
	  
	  for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {
	    db_fieldsmemory($resultanos,$totano);
	    $pdf->cell(10,05,"",	                        0,0,"C",0);
            $pdf->cell(15,05,$k22_ano,	                        1,0,"C",0);
            $pdf->cell(45,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);
            $pdf->cell(45,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

	    $totvlrcor+=$k22_vlrcor;
	    $totjuros+=$k22_juros;
	    $totmulta+=$k22_multa;
	    $tottotal+=$k22_total;

	  }
	  $pdf->setfillcolor(245);
	  $pdf->cell(25,05,"",                               0,0,"L",0);
	  $pdf->cell(45,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);
	  $pdf->cell(45,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
	  $pdf->setfillcolor(255,255,255);

	} elseif (strtoupper($db02_descr) == "TOTALGERALPORANO") {


	  $sqlanostipos = "	select 	extract (year from k22_dtoper) as k22_ano,
				    sum(k22_vlrcor) as k22_vlrcor, 
				    sum(k22_juros) as k22_juros, 
				    sum(k22_multa) as k22_multa, 
				    sum(k22_vlrcor+k22_juros+k22_multa) as k22_total 
				    from debitos
				    inner join arretipo on k00_tipo = k22_tipo
				    " . ($tipos == ""?"":" and k22_tipo in ($tipos)") .
				    " and $xmatinsc22
				    k22_data = '$k60_datadeb'
			    group by extract (year from k22_dtoper)";

          //die("sql: $sqlanostipos\n");

	  $resultanostipos = pg_exec($sqlanostipos);
	  if ($resultanostipos == false) {
	    db_redireciona('db_erros.php?fechar=true&db_erro=Problemas ao gerar totais por anos dos tipos selecionados! sql: '.$sqlanostipos);
	    exit;
	  }
	  

	  $pdf->cell(10,05,"",	             0,0,"C",0);
	  $pdf->setfillcolor(245);
	  $pdf->cell(15,05,"ANO",	     1,0,"C",1);
	  $pdf->cell(45,05,"VALOR CORRIGIDO",1,0,"C",1);
	  $pdf->cell(35,05,"JUROS",          1,0,"C",1);
	  $pdf->cell(35,05,"MULTA",          1,0,"C",1);
	  $pdf->cell(45,05,"VALOR TOTAL",    1,1,"C",1);
	  $pdf->setfillcolor(255,255,255);
	  
	  $totvlrcor=0;
	  $totjuros=0;
	  $totmulta=0;
	  $tottotal=0;
	  
	  for ($totano = 0; $totano < pg_numrows($resultanostipos); $totano++) {
	    db_fieldsmemory($resultanostipos,$totano);
	    $pdf->cell(10,05,"",	                        0,0,"C",0);
            $pdf->cell(15,05,$k22_ano,	                        1,0,"C",0);
            $pdf->cell(45,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);
            $pdf->cell(45,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

	    $totvlrcor+=$k22_vlrcor;
	    $totjuros+=$k22_juros;
	    $totmulta+=$k22_multa;
	    $tottotal+=$k22_total;

	  }
	  $pdf->setfillcolor(245);
	  $pdf->cell(25,05,"",                               0,0,"L",0);
	  $pdf->cell(45,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);
	  $pdf->cell(45,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
	  $pdf->setfillcolor(255,255,255);

	} elseif (strtoupper($db02_descr) == "TOTALPORANOETIPO") {


	  $sqlanostiposdeb = "	select 	
				    extract (year from k22_dtoper) as k22_ano,
				    k00_descr,
				    sum(k22_vlrcor) as k22_vlrcor, 
				    sum(k22_juros) as k22_juros, 
				    sum(k22_multa) as k22_multa, 
				    sum(k22_vlrcor+k22_juros+k22_multa) as k22_total 
			    from notidebitos
				    inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
				    inner join arretipo on k00_tipo = k22_tipo
				    where k53_notifica = $notifica
			    group by extract (year from k22_dtoper),
				     k00_descr";
	  //die($sqlanostiposdeb);
	  $resultanostiposdeb = pg_exec($sqlanostiposdeb);
	  if ($resultanostiposdeb == false) {
	    db_redireciona('db_erros.php?fechar=true&db_erro=Problemas ao gerar totais por anos dos tipos selecionados! sql: ' . $sqlanostiposdeb);
	    exit;
	  }



	  $pdf->setfillcolor(245);
	  $pdf->cell(15,05,"ANO",	     1,0,"C",1);
	  $pdf->cell(50,05,"TIPO DE DEBITO", 1,0,"C",1);
	  $pdf->cell(35,05,"VLR CORRIGIDO",1,0,"C",1);
	  $pdf->cell(25,05,"JUROS",          1,0,"C",1);
	  $pdf->cell(25,05,"MULTA",          1,0,"C",1);
	  $pdf->cell(35,05,"VLR TOTAL",    1,1,"C",1);
	  $pdf->setfillcolor(255,255,255);
	  
	  $totvlrcor=0;
	  $totjuros=0;
	  $totmulta=0;
	  $tottotal=0;
	  
	  for ($totano = 0; $totano < pg_numrows($resultanostiposdeb); $totano++) {
	    db_fieldsmemory($resultanostiposdeb,$totano);
            $pdf->cell(15,05,$k22_ano,	                        1,0,"C",0);
            $pdf->cell(50,05,$k00_descr,	                1,0,"C",0);
            $pdf->cell(35,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

	    $totvlrcor+=$k22_vlrcor;
	    $totjuros+=$k22_juros;
	    $totmulta+=$k22_multa;
	    $tottotal+=$k22_total;

	  }
	  $pdf->setfillcolor(245);
	  $pdf->cell(65,05,"",                               0,0,"L",0);
	  $pdf->cell(35,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
	  $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
	  $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
	  $pdf->setfillcolor(255,255,255);

	} elseif (strtoupper($db02_descr) == "TOTALPORANOEHISTORICO") {


	  $sqlanoshistdeb = "	select 	
				    extract (year from k22_dtoper) as k22_ano,
				    k01_descr,
				    sum(k22_vlrcor) as k22_vlrcor, 
				    sum(k22_juros) as k22_juros, 
				    sum(k22_multa) as k22_multa, 
				    sum(k22_vlrcor+k22_juros+k22_multa) as k22_total 
			    from notidebitos
				    inner join debitos on k22_numpre = k53_numpre and k22_numpar = k53_numpar and k22_data = '$k60_datadeb'
				    inner join arretipo on k00_tipo = k22_tipo
				    inner join histcalc on k01_codigo = k22_hist
				    where k53_notifica = $notifica
			    group by extract (year from k22_dtoper),
				     k01_descr";
          //die($sql);
	  $resultanoshistdeb = pg_exec($sqlanoshistdeb);
	  if ($resultanoshistdeb == false) {
	    db_redireciona('db_erros.php?fechar=true&db_erro=Problemas ao gerar totais por anos dos historicos! sql: '.$sqlanoshistdeb);
	    exit;
	  }


	  $pdf->setfillcolor(245);
	  $pdf->cell(15,05,"ANO",	     1,0,"C",1);
	  $pdf->cell(50,05,"HISTORICO",      1,0,"C",1);
	  $pdf->cell(35,05,"VLR CORRIGIDO",  1,0,"C",1);
	  $pdf->cell(25,05,"JUROS",          1,0,"C",1);
	  $pdf->cell(25,05,"MULTA",          1,0,"C",1);
	  $pdf->cell(35,05,"VLR TOTAL",      1,1,"C",1);
	  $pdf->setfillcolor(255,255,255);
	  
	  $totvlrcor=0;
	  $totjuros=0;
	  $totmulta=0;
	  $tottotal=0;
	  
	  for ($totano = 0; $totano < pg_numrows($resultanoshistdeb); $totano++) {
	    db_fieldsmemory($resultanoshistdeb,$totano);
            $pdf->cell(15,05,$k22_ano,	                        1,0,"C",0);
            $pdf->cell(50,05,$k01_descr,	                1,0,"C",0);
            $pdf->cell(35,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);
            $pdf->cell(35,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

	    $totvlrcor+=$k22_vlrcor;
	    $totjuros+=$k22_juros;
	    $totmulta+=$k22_multa;
	    $tottotal+=$k22_total;

	  }
	  
	  $pdf->setfillcolor(245);
	  $pdf->cell(65,05,"",                               0,0,"L",0);
	  $pdf->cell(35,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
	  $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
	  $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);
	  $pdf->cell(35,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
	  $pdf->setfillcolor(255,255,255);

        } elseif (strtoupper($db02_descr) == "DATA") {
//	  $posicao_assinatura=$pdf->gety();
	  $sqltexto = "select munic from db_config where codigo = " . db_getsession("DB_instit");
	  $resulttexto = pg_exec($sqltexto);
	  db_fieldsmemory($resulttexto,0,true);
	  $texto = $munic .', '.date('d',strtotime($k60_datadeb)).' de '.db_mes(date('m',strtotime($k60_datadeb))).' de ' . date('Y',strtotime($k60_datadeb)) .'.';
//          $pdf->sety($posicao_assinatura+10);
//          $pdf->cell($db02_inicia+0,4+$db02_espaca,"",0,0,"J",0);
	  $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","R",0,$db02_inicia+0);
	  $pdf->Ln(1);

        } elseif ($db02_descr == "ASSINATURA") {
//	  echo $posicao_assinatura; exit;
          $pdf->Image('imagens/files/assinatura_notificacao.jpg',140,$posicao_assinatura,45);
	  $pdf->sety($posicao_assinatura+43);
	  $pdf->MultiCell(170,5,$texto,0,"R",0);
//          $pdf->text(30,200,$texto);

      } elseif (strtoupper($db02_descr) == "SEED") {

          //$pdf->sety(210);
          $pdf->sety(190+35);
          $pdf->SetFont('Arial','',12);
	  $pdf->cell(40,5,"NOTIFICAÇÃO : ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
//	  die(db_formatar($notifica,'s','0',5,'e'));
	  $pdf->cell(50,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);
          
//		$this->objpdf->setfillcolor(245);
//		$this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
	  
    	  $pdf->setfillcolor(245);
	  $pdf->RoundedRect(5,190+35,145,29,0,'DF','1234');

          $pdf->SetFont('Arial','',12);
	  $pdf->ln(0);
	  $pdf->cell(40,5,"DESTINATÁRIO: ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(100,5,$z01_nome,0,1,"L",0);
          $pdf->SetFont('Arial','',12);
	  $pdf->cell(40,5,"ENDEREÇO: ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(50,5,trim($z01_ender).", ".trim($z01_numero)."  ".trim($z01_compl),0,1,"L",0);

          $pdf->SetFont('Arial','',12);
	  $pdf->cell(40,5,($z01_bairro == ""?"":"BAIRRO: "),0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(20,5,$z01_bairro,0,1,"L",0);

          $pdf->SetFont('Arial','',12);
	  $pdf->cell(40,5,"MUNICIPIO:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(50,5,$z01_munic ."/".$z01_uf . " - " . substr($z01_cep,0,5)."-".substr($z01_cep,5,3),0,1,"L",0);

          $pdf->SetFont('Arial','',12);
	  $pdf->cell(40,5,"NOTIFICAÇÃO: ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(30,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);

          $pdf->SetFont('Arial','',12);
	  if ($xcodigo == "numcgm") {
	    $pdf->cell(30,5,"CGM:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
	    $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
	  } elseif ($xcodigo == "matric") {
	    $pdf->cell(30,5,"MATRÍCULA:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
	    $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
	  } elseif ($xcodigo == "inscr") {
	    $pdf->cell(30,5,"INSCRIÇÃO:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
	    $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
	  }
	  
	  
	  $pdf->RoundedRect(150,190+35,55,67,0,'','1234');
	  $pdf->SetXY(150,190+35); 
	  $pdf->SetFont('Arial','',8);
	  $pdf->cell(55,5,"CARIMBO",0,0,"C",0);

          $pdf->SetXY(5,220+35); 
          $pdf->SetFont('Arial','B',8);
	  $pdf->cell(50,5,"Motivos da não entrega",1,0,"C",0);
          $pdf->SetFont('Arial','B',12);
	  $pdf->cell(95,5,"Comprovante de Entrega",1,1,"C",0);

          $pdf->SetFont('Arial','',7);
	  
	  $pdf->cell(2,3,"",0,1,"L",0);

	  $pdf->cell(20,5,"Mudou-se",0,0,"L",0);
	  $pdf->RoundedRect(7,229+35,2,2,0,'DF','1234');
	  $pdf->cell(2,5,"",0,0,"L",0);
          $pdf->cell(20,5,"Ausente",0,1,"L",0);
	  $pdf->RoundedRect(29,229+35,2,2,0,'DF','1234');
//	  $pdf->cell(2,5,"",0,0,"L",0);
//	  $pdf->cell(20,5,"Não Existe N".chr(176),0,1,"L",0);
//	  $pdf->RoundedRect(54,226+35,2,2,0,'DF','1234');
	  	    
//	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Recusado",0,0,"L",0);
	  $pdf->RoundedRect(7,234+35,2,2,0,'DF','1234');
	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Não procurado",0,1,"L",0);
	  $pdf->RoundedRect(29,234+35,2,2,0,'DF','1234');
//	  $pdf->cell(2,5,"",0,0,"L",0);
//	  $pdf->cell(20,5,"Endereço Insuficiente",0,1,"L",0);
//	  $pdf->RoundedRect(54,231+35,2,2,0,'DF','1234');
	  
//	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Desconhecido",0,0,"L",0);
	  $pdf->RoundedRect(7,239+35,2,2,0,'DF','1234');
	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Falecido",0,1,"L",0);
	  $pdf->RoundedRect(29,239+35,2,2,0,'DF','1234');
//	  $pdf->cell(2,5,"",0,0,"L",0);
//	  $pdf->cell(20,5,"Outros",0,1,"L",0);
//	  $pdf->RoundedRect(54,236+35,2,2,0,'DF','1234');

//	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Não existe n" . chr(176),0,0,"L",0);
	  $pdf->RoundedRect(7,244+35,2,2,0,'DF','1234');
	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Outros",0,1,"L",0);
	  $pdf->RoundedRect(29,244+35,2,2,0,'DF','1234');

//	  $pdf->cell(2,5,"",0,0,"L",0);
	  $pdf->cell(20,5,"Endereço insuficiente",0,0,"L",0);
	  $pdf->RoundedRect(7,249+35,2,2,0,'DF','1234');


/*
          $pdf->SetFont('Arial','B',6);
	  $pdf->cell(15,4,"Para uso",0,0,"L",0);
          $pdf->SetFont('Arial','',6);
	  $pdf->cell(15,3,"Data","LTR",0,"L",0);
	  $pdf->cell(20,3,"Entregador","LTR",0,"L",0);
	  $pdf->cell(19,3,"N".chr(176)." de se.","LTR",1,"L",0);
          $pdf->SetFont('Arial','B',6);
	  $pdf->cell(15,4,"dos Correios",0,0,"L",0);
          $pdf->SetFont('Arial','',6);
	  $pdf->cell(15,5,"","LBR",0,"L",0);
	  $pdf->cell(20,5,"","LBR",0,"L",0);
	  $pdf->cell(19,5,"","LBR",1,"L",0);
*/
	  $pdf->RoundedRect(5,220+35,50,37,0,'D','1234');
          
          $pdf->SetFont('Arial','B',8);
          $pdf->SetXY(57,225+38); 
          $pdf->SetX(57); 
	  $pdf->cell(35,7,"Assinatura Recebedor: _____________________________________ ",0,1,"L",0);

          $pdf->SetX(57); 
	  $pdf->cell(35,7,"Nome legível: _____________________________________________",0,1,"L",0);

          $pdf->SetX(57); 
	  $pdf->cell(50,7,"CI : ____________________ ",0,0,"L",0);

	  $pdf->cell(35,7,"Data : ______/______/_______ ",0,1,"L",0);

          $pdf->SetX(57); 
	  $pdf->cell(55,7,"Assinatura/ECT: ____________________",0,0,"L",0);
	  
	  $pdf->cell(15,7,"Matrícula : _____________",0,0,"L",0);

	  /*$pdf->Text(85,249,"Contribuinte: ");
	  $pdf->Text(160,249,"Notificação No.: ".db_formatar($notifica,'s','0',5,'e'));*/
	  $pdf->RoundedRect(55,220+35,95,37,0,'D','1234');

	} else {
//// 	  if (strlen($texto) <= 100) {

    
//   	echo("x: $texto - " . $pdf->GetStringWidth($texto) . "<br>");
	
 	  if ($pdf->GetStringWidth($texto) <= 100 and 1 == 2) {
	    //$pdf->cell($db02_inicia+0,4+$db02_espaca,str_repeat(" ",$db02_inicia+0),0,0,"L",0);
////			if ($db02_descr == 'ATENCIOSAMENTE,') {
////      	die("x: $texto - " . $pdf->GetStringWidth($texto) . " - db02_inicia: $db02_inicia - db02_espaca: $db02_espaca" . "<br>");
////			}

	    $pdf->cell($db02_inicia+0,4+$db02_espaca," ",0,0,"L",0);
////			$pdf->write(4+$db02_espaca, $texto);
	    //$pdf->cell($db02_inicia+0,4+$db02_espaca,$texto,0,1,"L",0);
  	  $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","L",0,$db02_inicia+0);
	  } else {
  	  $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
	  }
	  $posicao_assinatura=$pdf->gety();
	}
      }

   } 

} elseif( $tiporel == 2 ) {
   $pdf->addpage();
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,05,'Notificação',1,0,"c",1);
   $pdf->cell(15,05,$xtipo,1,0,"c",1);
   $pdf->cell(15,05,'Numcgm',1,0,"c",1);
   $pdf->cell(80,05,'Nome',1,1,"c",1);
   $pdf->setfont('arial','',8);
   $total = 0;
   for($x=$lim1;$x < $lim2;$x++){
//   for($x=0;$x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 35){
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,05,'Notificação',1,0,"c",1);
        $pdf->cell(15,05,$xtipo,1,0,"c",1);
        $pdf->cell(15,05,'Numcgm',1,0,"c",1);
        $pdf->cell(80,05,'Nome',1,1,"c",1);
        $pdf->setfont('arial','',8);
     }
     $pdf->cell(15,05,$notifica,0,0,"R",0);
     $pdf->cell(15,5,$$xcodigo1,0,0,"R",0);
     $pdf->cell(15,5,$z01_numcgm,0,0,"R",0);
     $pdf->cell(80,5,$z01_nome,0,1,"L",0);
     $total += 1;
   }
   $pdf->cell(125,05,'Total de Registros:   '.$total,1,1,"c",1);

}elseif ( $tiporel == 3 ){
//   for($x=0;$x < 2;$x++){
      $sqlparag = "select * 
                  from db_documento 
                  inner join db_docparag on db03_docum = db04_docum 
                  inner join db_paragrafo on db04_idparag = db02_idparag 
                  where db03_docum = 25 and db03_instit = " . db_getsession("DB_instit");
   $resparag = pg_query($sqlparag);
//   db_criatabela($resparag);
//   for($x=0;$x < 20;$x++){
//   for($x=0;$x < pg_numrows($result);$x++){


   for($x=$lim1;$x < $lim2;$x++){
      db_fieldsmemory($result,$x);
      $pdf->AddPage();
      $pdf->SetFont('Arial','',13);
      $numcgm = @$j01_numcgm;
      $matric = @$j01_matric;
      $inscr  = @$q02_inscr;
      if($matric != ''){
         $xmatinsc = " matric = ".$matric." and ";
         $matinsc = "sua matrícula n".chr(176)." ".$matric;
      }else if($inscr != ''){
         $xmatinsc = " inscr = ".$inscr." and ";
         $matinsc = "sua inscrição n".chr(176)." ".$inscr;
      }else{
         $xmatinsc = " numcgm = ".$numcgm." and ";
         $matinsc = "V.Sa.";
      }
      $matricula = $matric;
      $inscricao = $inscr;
      $cgm = $z01_numcgm;
      $sql10 = "select distinct tipo,k00_descr from devedores inner join arretipo on k00_tipo = tipo where $xmatinsc $jtipos data = '$k60_datadeb' ";
      $result10 = pg_exec($sql10);
      $xxtipos = '';
      $virgula = '';
      for($i = 0;$i < pg_numrows($result10);$i++){
         db_fieldsmemory($result10,$i);
         $xxtipos .= $virgula.$k00_descr;
         $virgula = ', ';
      }
//      $xxtipos = db_geratexto($xxtipos);

      $pdf->multicell(0,4,$munic.", ".date('d',$k60_datadeb)." de ".db_mes(date('m',$k60_datadeb))." de ".date('Y',$k60_datadeb).".",0,"R",0);
      $pdf->ln(10);
      
      for($ip = 0;$ip < pg_numrows($resparag);$ip++){
        db_fieldsmemory($resparag,$ip);
	if($db02_alinha != 0)
	  $pdf->setx($pdf->lMargin + $db02_alinha);
        $pdf->multicell(0,6,db_geratexto($db02_texto),0,"J",0,$db02_inicia);
	if($db02_espaca > 1)
	  $pdf->ln($db02_espaca);
      }
      $pdf->setx(100);
      $posicaoy = $pdf->gety();
      $pdf->Image('imagens/assinatura/shimi.jpg',115,$posicaoy+10,45);
      $pdf->MultiCell(90,6,"\n\n\n"."Jorge Alfredo Schmitt"."\n"."Coordenador de Unidade",0,"C",0,15);
 
      if ($k60_tipo == 'M') {
	 $sql3 = "select j43_ender as z01_ender, j43_numimo as z01_numero, j43_comple as z01_compl, j43_munic as z01_munic, j43_uf as z01_uf, j43_cep as z01_cep , j43_cxpost as z01_cxpostal from iptuender where j43_matric = $matric";
	 $result3 = pg_exec($sql3);
         if (pg_numrows($result3) > 0) {
            db_fieldsmemory($result3,0);
	    $sql3 = "select z01_nome from cgm where z01_numcgm = $cgm";
	    $result3 = pg_exec($sql3);
            db_fieldsmemory($result3,0);
         } else {
	    $sql3 = "select * from cgm where z01_numcgm = $cgm";
   	    $result3 = pg_exec($sql3);
         }
      } else { 
	 $sql3 = "select * from cgm where z01_numcgm = $cgm";
	 $result3 = pg_exec($sql3);
      }

      if (pg_numrows($result3) > 0){
         db_fieldsmemory($result3,0);
         $pdf->text(10,248,"Contribuinte: ");
         $pdf->SetFont('Arial','',10);
         $pdf->text(10,254,strtoupper($xtipo).' - '.$$xcodigo1);
         $pdf->text(10,259,$z01_nome);
         if ($z01_cxpostal==""){
            $pdf->text(10,264,$z01_ender.", ".$z01_numero." ".$z01_compl);
         } else { 
            $pdf->text(10,264,$z01_cxpostal);
         }
         $pdf->text(10,269,$z01_munic." - ".$z01_uf);
         $pdf->text(10,274,substr($z01_cep,0,5) . "-" . substr($z01_cep,5,3));
     }

   }

}

$pdf->Output();