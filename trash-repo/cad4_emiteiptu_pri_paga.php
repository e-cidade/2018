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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptucalc_classe.php");
include("classes/db_iptunump_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_massamat_classe.php");
include("classes/db_iptuender_classe.php");

include("dbforms/db_funcoes.php");

// primeira paga

db_postmemory($HTTP_POST_VARS);
$erro = false;
  $descricao_erro = false;
  if(isset($geracarnes)){
    set_time_limit(0);
    $clabre_arquivo =  new cl_abre_arquivo();
    if($clabre_arquivo->arquivo!=false){
      $cliptucalc = new cl_iptucalc;
      $cliptuender = new cl_iptuender;
      $cliptunump = new cl_iptunump;
      $clmassamat = new cl_massamat;
      $resultprinc = $cliptucalc->sql_record($cliptucalc->sql_query($anousu,'','j23_matric#j23_vlrter#j23_aliq','z01_nome',''));
      if($resultprinc==false || $cliptucalc->numrows == 0 ){
	$erro = true;
	$descricao_erro =  "Não existe cálculo efetuado!";
      }else{
	$quantos = 0;
	$cliptubase = new cl_iptubase;
	
	pg_exec("truncate iptucarnes");
	
        $time_inicial_inicial = time();

      for($i=0;$i<pg_numrows($resultprinc);$i++){

//	for($i=0;$i<500;$i++){
	   db_fieldsmemory($resultprinc,$i);

	   $z01_cxpostal = "";

           $time_inicial = time();

	   if(empty($proc)){   
	      $clmassamat->sql_record($clmassamat->sql_query_file(null,$j23_matric)); 
	      if($clmassamat->numrows>0){
		continue;
	      }
	    }

  //          echo($cliptuender->sql_query_endereco($j23_matric)); exit;
	    $cliptuender->sql_record($cliptuender->sql_query_endereco($j23_matric)); 
  //	  echo "matricula: " . $j23_matric . " - " . $cliptuender->numrows;exit;
	    if($situacao=="com"){
	       if($cliptuender->numrows==0){
		 continue;
	       }
	    }else if($situacao=="sem"){
	       if($cliptuender->numrows > 0){
		 continue;
	       }
	    } 

	      $resultmat = $cliptubase->proprietario_record($cliptubase->proprietario_query($j23_matric));
	      db_fieldsmemory($resultmat,0);

              $achounumero = preg_match("/[0-9]/i",$z01_ender);

              if ($z01_numero == 0 and $j01_tipoimp == "Predial" and $achounumero == 0) {
		$z01_ender  = $nomepri;
		$z01_numero = $j39_numero;
		$z01_compl  = $j39_compl;
		$z01_munic  = 'GUAIBA';
		$z01_uf     = 'RS';
		$z01_cep    = '92500000';
	      }
	      
//	      $resultmat = $cliptubase->proprietario_record($cliptubase->proprietario_query($j23_matric));

	      $resultender = $cliptuender->sql_record($cliptuender->sql_query_file($j23_matric,"*")); 
	      if ($cliptuender->numrows > 0) {
  	        db_fieldsmemory($resultender,0);
	      } else {
                $j43_munic  = "";
		$j43_ender  = "";
		$j43_numimo = 0;
	      }

              $passa = false;

              if ($j43_munic != "" and $j43_ender != "" and $j43_numimo > 0) {
		$passa = true;
	      }
	      
	      if ($j01_tipoimp == "Predial") {
		$passa = true;
	      };

              if ($passa == false) {
		continue;
	      }

//              echo "j43_numimo.: $j43_numimo\n";
//	      echo "z01_numero.: $z01_numero\n";
//     	      echo "achounumero: $achounumero\n";
//              echo "z01_ender: $z01_ender\n";
//	      exit;

              if ($j43_numimo == 0 and $z01_numero == 0 and $achounumero == 0) continue;

	      if ($j01_matric == 4914400) continue;
	      if ($j01_matric == 80600) continue;

	      if ($j34_setor == "13" and $j34_quadra == "17") continue;
	      if ($j34_setor == "13" and $j34_quadra == "18") continue;
	      if ($j34_setor == "13" and $j34_quadra == "19") continue;
	      if ($j34_setor == "13" and $j34_quadra == "20") continue;
	      if ($j34_setor == "13" and $j34_quadra == "21") continue;
	      if ($j34_setor == "13" and $j34_quadra == "22") continue;
	      if ($j34_setor == "13" and $j34_quadra == "23") continue;
	      if ($j34_setor == "13" and $j34_quadra == "24") continue;
	      if ($j34_setor == "13" and $j34_quadra == "25") continue;
	      if ($j34_setor == "13" and $j34_quadra == "26") continue;
	      if ($j34_setor == "13" and $j34_quadra == "27") continue;
	      if ($j34_setor == "13" and $j34_quadra == "28") continue;
	      if ($j34_setor == "13" and $j34_quadra == "29") continue;
	      if ($j34_setor == "13" and $j34_quadra == "30") continue;
	      if ($j34_setor == "13" and $j34_quadra == "31") continue;
	      if ($j34_setor == "13" and $j34_quadra == "32") continue;
	      if ($j34_setor == "13" and $j34_quadra == "33") continue;
	      if ($j34_setor == "13" and $j34_quadra == "34") continue;
	      if ($j34_setor == "13" and $j34_quadra == "35") continue;
	      if ($j34_setor == "13" and $j34_quadra == "36") continue;
	      if ($j34_setor == "13" and $j34_quadra == "37") continue;
	      if ($j34_setor == "13" and $j34_quadra == "38") continue;
	      if ($j34_setor == "13" and $j34_quadra == "39") continue;
	      if ($j34_setor == "13" and $j34_quadra == "40") continue;
	      if ($j34_setor == "13" and $j34_quadra == "41") continue;
	      if ($j34_setor == "13" and $j34_quadra == "42") continue;
	      if ($j34_setor == "13" and $j34_quadra == "43") continue;
	      if ($j34_setor == "13" and $j34_quadra == "44") continue;
	      if ($j34_setor == "13" and $j34_quadra == "45") continue;
	      if ($j34_setor == "13" and $j34_quadra == "46") continue;
	      if ($j34_setor == "13" and $j34_quadra == "47") continue;
	      if ($j34_setor == "13" and $j34_quadra == "48") continue;
	      if ($j34_setor == "13" and $j34_quadra == "49") continue;
	      if ($j34_setor == "13" and $j34_quadra == "50") continue;
	      if ($j34_setor == "13" and $j34_quadra == "51") continue;
	      if ($j34_setor == "13" and $j34_quadra == "52") continue;
	      if ($j34_setor == "13" and $j34_quadra == "53") continue;
	      if ($j34_setor == "13" and $j34_quadra == "54") continue;
	      if ($j34_setor == "13" and $j34_quadra == "55") continue;
	      if ($j34_setor == "13" and $j34_quadra == "56") continue;
	      if ($j34_setor == "13" and $j34_quadra == "57") continue;
	      if ($j34_setor == "13" and $j34_quadra == "58") continue;
	      if ($j34_setor == "13" and $j34_quadra == "59") continue;

	      if ($j34_setor == "9" and $j34_quadra == "63") continue;
	      if ($j34_setor == "9" and $j34_quadra == "64") continue;
	      if ($j34_setor == "9" and $j34_quadra == "65") continue;
	      if ($j34_setor == "9" and $j34_quadra == "66") continue;
	      if ($j34_setor == "9" and $j34_quadra == "67") continue;
	      if ($j34_setor == "9" and $j34_quadra == "68") continue;
	      if ($j34_setor == "9" and $j34_quadra == "69") continue;
	      if ($j34_setor == "9" and $j34_quadra == "70") continue;
	      if ($j34_setor == "9" and $j34_quadra == "71") continue;
	      if ($j34_setor == "9" and $j34_quadra == "72") continue;
	      if ($j34_setor == "9" and $j34_quadra == "73") continue;
	      if ($j34_setor == "9" and $j34_quadra == "74") continue;

	      if ($j34_setor == "1" and $j34_quadra == "39") continue;
	      if ($j34_setor == "1" and $j34_quadra == "40") continue;
	      if ($j34_setor == "1" and $j34_quadra == "41") continue;
	      if ($j34_setor == "1" and $j34_quadra == "42") continue;
	      if ($j34_setor == "1" and $j34_quadra == "43") continue;
	      if ($j34_setor == "1" and $j34_quadra == "44") continue;
	      
	      if ($j34_setor == "12" and $j34_quadra == "173") continue;
	      if ($j34_setor == "12" and $j34_quadra == "174") continue;
	      if ($j34_setor == "12" and $j34_quadra == "175") continue;

	      if ($j34_setor == "14" and $j34_quadra == "72") continue;
	      if ($j34_setor == "14" and $j34_quadra == "73") continue;
	      if ($j34_setor == "14" and $j34_quadra == "74") continue;
	      if ($j34_setor == "14" and $j34_quadra == "75") continue;
	      if ($j34_setor == "14" and $j34_quadra == "76") continue;
	      if ($j34_setor == "14" and $j34_quadra == "77") continue;
	      if ($j34_setor == "14" and $j34_quadra == "78") continue;
	      if ($j34_setor == "14" and $j34_quadra == "79") continue;
	      if ($j34_setor == "14" and $j34_quadra == "80") continue;
	      if ($j34_setor == "14" and $j34_quadra == "81") continue;
	      if ($j34_setor == "14" and $j34_quadra == "82") continue;
	      if ($j34_setor == "14" and $j34_quadra == "83") continue;
	      if ($j34_setor == "14" and $j34_quadra == "84") continue;
	      if ($j34_setor == "14" and $j34_quadra == "85") continue;
	      if ($j34_setor == "14" and $j34_quadra == "86") continue;

  //	    $sqlachou = "select count(*) as totalarrecad from iptunump inner join arrecad on j20_numpre = k00_numpre where j20_matric = $j23_matric and j20_anousu = $anousu";
  //            $resultachou = pg_exec($sqlachou);
  //	    db_fieldsmemory($resultachou,0);

  //   if ($totalarrecad == 0) continue;

		      $sqlfin = "select *
					     from iptunump
								     where j20_anousu = $anousu
									   and j20_matric = $j23_matric
								     ";
		      $resultfin = pg_exec($sqlfin);
		      db_fieldsmemory($resultfin,0);

		      $sqlfin = "select a.k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_dtvenc,sum(k00_valor)::float8 as k00_valor 
					      from arrematric m 
									   inner join arrecad a on m.k00_numpre = a.k00_numpre 
								      where m.k00_numpre = $j20_numpre 
								      group by a.k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_dtvenc 
								     ";
		      $resultfin2 = pg_exec($sqlfin);

		      if (pg_numrows($resultfin2) == 0) continue;

		      $sqlfin = "select distinct a.k00_numpar
					      	from arrematric m 
					   	inner join arrepaga a on m.k00_numpre = a.k00_numpre 
 				      		where m.k00_numpre = $j20_numpre and a.k00_numpar in (1,2)";
//		      die($sqlfin);

		      $resultfin2 = pg_exec($sqlfin);

		      if (pg_numrows($resultfin2) <= 1) continue;

		      $sqlfin = "select distinct a.k00_numpar
					      	from arrematric m 
					   	inner join arrepaga a on m.k00_numpre = a.k00_numpre 
						where m.k00_numpre = $j20_numpre and a.k00_numpar = 3";
//		      die($sqlfin);

		      $resultfin2 = pg_exec($sqlfin);

		      if (pg_numrows($resultfin2) > 0) continue;

	          $resultmat = $cliptunump->sql_record($cliptunump->sql_query($anousu,$j23_matric));
	      
		  if($cliptunump->numrows>0){
		    $quantos ++;
		    fputs($clabre_arquivo->arquivo,str_pad($quantos,10));
		    fputs($clabre_arquivo->arquivo,str_pad($j01_tipoimp,11));
		    fputs($clabre_arquivo->arquivo,str_pad($j23_matric,10));
		    fputs($clabre_arquivo->arquivo,str_pad($j37_zona,2));
		    fputs($clabre_arquivo->arquivo,str_pad($j34_setor,4));
		    fputs($clabre_arquivo->arquivo,str_pad($j34_quadra,4));
		    if ($j40_refant == "") {
		      $j40_refant = "....";
		    }
		    $sqlsub = split('\.',$j40_refant);
		    fputs($clabre_arquivo->arquivo,str_pad($sqlsub[3],4));

		    $z01_nome     = str_replace(chr(128),"C",$z01_nome);
		    $proprietario = str_replace(chr(128),"C",$proprietario);
		    $z01_ender    = str_replace(chr(128),"C",$z01_ender);

		    if(substr($z01_nome,0,10)=='POSSUIDOR:') {
		      fputs($clabre_arquivo->arquivo,str_pad($z01_nome,40));
		      $propri1 = $z01_nome;
		    } else {
		      fputs($clabre_arquivo->arquivo,str_pad(' ',40));
		      $propri1 = '';
		    }
		    
		    fputs($clabre_arquivo->arquivo,str_pad($proprietario,40));

		    fputs($clabre_arquivo->arquivo,str_pad($z01_ender,40));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_numero,10));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_compl,20));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_munic,20));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_cep,8));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_uf,2));
		    fputs($clabre_arquivo->arquivo,str_pad($codpri,6));
		    fputs($clabre_arquivo->arquivo,str_pad($nomepri,50));
		    fputs($clabre_arquivo->arquivo,str_pad($j39_numero,10));
		    fputs($clabre_arquivo->arquivo,str_pad($j39_compl,20));

		    if ( 1 == 1) {

		      $sqlcalc = "select sum(j21_valor) as total_j21_valor
						  from iptucalv
						  where j21_anousu = $anousu
						  and j21_matric = $j23_matric";
		      $resultcalc = pg_exec($sqlcalc);
		      db_fieldsmemory($resultcalc,0,true);

		      fputs($clabre_arquivo->arquivo,db_formatar($total_j21_valor,'f',' ',15)); 

		      $sqlcalc = "select *
					     from iptucalv
									  left outer join iptucalh on j21_codhis = j17_codhis
								     where j21_anousu = $anousu
									    and j21_matric = $j23_matric
										    and ( j21_codhis in (1,3) )
								     order by j21_codhis";
//                      die($sqlcalc);
		      $resultcalc = pg_exec($sqlcalc);
		      
//                      $xxx = $time_inicial - time();
//		      echo "        matricula: $j23_matric - tempo: $xxx<br>";
		  
		      if (pg_numrows($resultcalc) > 0) {

			for($vlr=0;$vlr<pg_numrows($resultcalc);$vlr++){
			  db_fieldsmemory($resultcalc,$vlr);
			  fputs($clabre_arquivo->arquivo,str_pad($j17_descr,40)); 
			  fputs($clabre_arquivo->arquivo,str_pad($j21_quant,5)); 
			  fputs($clabre_arquivo->arquivo,db_formatar($j21_valor,'f',' ',15)); 
			}

			fputs($clabre_arquivo->arquivo,str_pad("",(5 - pg_numrows($resultcalc)) * 60," ")); 

//		      } else {
//			fputs($clabre_arquivo->arquivo,str_pad("",(5 - pg_numrows($resultcalc)) * 60," ")); 
		      }
		      
		      $resultcalc = pg_exec("select sum(j22_valor) as j22_valor
					     from iptucale
								     where j22_anousu = $anousu 
									    and j22_matric = $j23_matric
								     ");
		      
		      if(pg_numrows($resultcalc)>0) {
			 db_fieldsmemory($resultcalc,0);
		      } else {
			 $j22_valor = 0;
		      }

		      fputs($clabre_arquivo->arquivo,str_pad('Valor Venal:',12));
		      fputs($clabre_arquivo->arquivo,db_formatar($j23_vlrter+$j22_valor,'f',' ',15));
		      fputs($clabre_arquivo->arquivo,str_pad('Aliquota:',9));
		      fputs($clabre_arquivo->arquivo,str_pad($j23_aliq,6));

		      // valores das unica e parcelado
		      //
		     if(!empty($j20_matric)){
		       $sqlfin = "select *,
		       substr(fc_calcula,2,13)::float8 as uvlrhis,
		       substr(fc_calcula,15,13)::float8 as uvlrcor,
		       substr(fc_calcula,28,13)::float8 as uvlrjuros,
		       substr(fc_calcula,41,13)::float8 as uvlrmulta,
		       substr(fc_calcula,54,13)::float8 as uvlrdesconto,
		       (substr(fc_calcula,15,13)::float8+
		       substr(fc_calcula,28,13)::float8+
		       substr(fc_calcula,41,13)::float8-
		       substr(fc_calcula,54,13)::float8) as utotal
				       from (
				       select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
					      fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,$anousu)
		       from recibounica r
				       where r.k00_numpre = $j20_numpre and r.k00_dtvenc >= ".db_getsession("DB_datausu")."
				       ) as unica";
		      $sqlfin = "select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,
					      fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,$anousu)
		       from recibounica r
				       where r.k00_numpre = $j20_numpre and r.k00_dtvenc >= ".db_getsession("DB_datausu");
				       
			$sqlfin = "select dtpago, sum(k00_valor) as k00_valor
			 from arrepaga a
			 inner join disbanco b on a.k00_numpre = b.k00_numpre and a.k00_numpar = b.k00_numpar 
					 where a.k00_numpre = $j20_numpre and a.k00_numpar = 1 group by b.dtpago";

			$sqlfin = "select max(dtpago) as dtpago, sum(vlrpago) as k00_valor
			 from disbanco a
			 		inner join db_reciboweb b on a.k00_numpre = b.k99_numpre_n
				where b.k99_numpre = $j20_numpre and b.k99_numpar in (1,2) group by dtpago";

//                          die($sqlfin);
					 
//			  $resultfin = pg_query($sqlfin);

//			  if (pg_numrows($resultfin) > 0 and 1 == 2) {
			  if (1 == 2) {

			      for($unicont=0;$unicont<pg_numrows($resultfin);$unicont++){
				  db_fieldsmemory($resultfin,$unicont);
				  $valorpago = $k00_valor;
				  fputs($clabre_arquivo->arquivo,db_formatar($dtpago,'d')); 		  
				  fputs($clabre_arquivo->arquivo,db_formatar($k00_valor,'f'));
			      }

			  } else {


			      $sqlfin = "select max(dtpago) as dtpago, sum(k00_valor) as k00_valor
			       from arrepaga a
			       inner join disbanco b on a.k00_numpre = b.k00_numpre and a.k00_numpar = b.k00_numpar 
					       where a.k00_numpre = $j20_numpre and a.k00_numpar in (1,2) group by b.dtpago";

                              
//			      $resultfin = pg_query($sqlfin);

//			      if (pg_numrows($resultfin) > 0) {
			      if (1 == 2) {

				for($unicont=0;$unicont<pg_numrows($resultfin);$unicont++){
				    db_fieldsmemory($resultfin,$unicont);
				    $valorpago = $k00_valor;
				    fputs($clabre_arquivo->arquivo,db_formatar($dtpago,'d')); 		  
				    fputs($clabre_arquivo->arquivo,db_formatar($k00_valor,'f'));
				}
				

			      } else {
				$sqlfin = "select max(k00_dtpaga) as k00_dtpaga, sum(k00_valor) as k00_valor
				 from arrepaga a
						 where a.k00_numpre = $j20_numpre and a.k00_numpar in (1,2)";
//				die("matric: $j23_matric . \n" . $sqlfin);
				 $resultfin = pg_query($sqlfin);

				for($unicont=0;$unicont<pg_numrows($resultfin);$unicont++) {
				    db_fieldsmemory($resultfin,$unicont);
				    $valorpago = $k00_valor;
				    fputs($clabre_arquivo->arquivo,db_formatar($k00_dtpaga,'d')); 		  
				    fputs($clabre_arquivo->arquivo,db_formatar($k00_valor,'f'));
				}
			      }

			}

		      $sqlfin2 = "select k00_dtvenc, k00_numpre, k00_numpar, sum(k00_valor) as k00_valor
		       from arrecad 
				       where k00_numpre = $j20_numpre and k00_numpar = 3 group by  k00_dtvenc, k00_numpre, k00_numpar";
			  $resultfin2 = pg_query($sqlfin2);
			  
//                      $xxx = $time_inicial - time();
//		      echo "           matricula: $j23_matric - tempo: $xxx<br>";
		      
		  if($resultfin2!=false){

		    if (pg_numrows($resultfin2) > 0 and 1 == 1) {
		      for($unicont=0;$unicont<pg_numrows($resultfin2);$unicont++) {
			db_fieldsmemory($resultfin2,$unicont);

			fputs($clabre_arquivo->arquivo,db_formatar($k00_dtvenc,'d'));
		        fputs($clabre_arquivo->arquivo,db_formatar($k00_valor,'f',' ',15)); 
			$numpre = db_numpre($k00_numpre).'00'.$k00_numpar;
			$numpref = db_numpre($k00_numpre).'.00'.$k00_numpar;
			fputs($clabre_arquivo->arquivo,$numpref);
			$vlrbar = db_formatar(str_replace('.','',str_pad(number_format($k00_valor,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
			$resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = " . db_getsession("DB_instit"));
			db_fieldsmemory($resultnumbco,0) ;// deve ser tirado do db_config
			$datavencimento = $k00_dtvenc;

			if ($formvencfebraban == 1) {
			  $db_dtvenc = str_replace("-","",$datavencimento);
			  $vencbar = $db_dtvenc . '000000';
			} elseif ($formvencfebraban == 2) {
			  $db_dtvenc = str_replace("-","",$datavencimento);
			  $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
			  $vencbar = $db_dtvenc . '00000000';
			}

			$inibar="8" . $segmento . "6";
			$resultcod = pg_exec("select fc_febraban('$inibar'||'$vlrbar'||'".$numbanco."'||'".$vencbar."'||'$numpre')");
			db_fieldsmemory($resultcod,0);

			if ($fc_febraban == "") {
			  db_msgbox("Erro ao gerar codigo de barras (3)!");
			  exit;
			}

			fputs($clabre_arquivo->arquivo,$fc_febraban);
			
		      }

		    }
		     
		   } 
//		    fputs($clabre_arquivo->arquivo,str_repeat(" ",266));

		    fputs($clabre_arquivo->arquivo,str_pad($sqlsub[4],20));

		    $sqlcalc = "select sum(j21_valor) as j21_valor
					     from iptucalv
									  left outer join iptucalh on j21_codhis = j17_codhis
								     where j21_anousu = $anousu
									    and j21_matric = $j23_matric
										    and ( j21_codhis in (2,4) )";
		    $resultcalc = pg_exec($sqlcalc);
		    if (pg_numrows($resultcalc) > 0) {
		      for($vlr=0;$vlr<pg_numrows($resultcalc);$vlr++){
			db_fieldsmemory($resultcalc,$vlr);
			fputs($clabre_arquivo->arquivo,str_pad("LIMPEZA",40));
			fputs($clabre_arquivo->arquivo,substr(db_formatar(1,'f',' ',5),0,5));
			fputs($clabre_arquivo->arquivo,str_pad(db_formatar($j21_valor,'f',' ',18),18,' ',STR_PAD_RIGHT));
		      }
		    } else {
     	              fputs($clabre_arquivo->arquivo,str_repeat(" ",  63));
		    }

		    $sqlcalc = "select j17_descr, sum(j21_valor) as j21_valor
					     from iptucalv
									  left outer join iptucalh on j21_codhis = j17_codhis
								     where j21_anousu = $anousu
									    and j21_matric = $j23_matric
										    and ( j21_codhis = 5 )
								     group by j17_descr";
		    $resultcalc = pg_exec($sqlcalc);
		    if (pg_numrows($resultcalc) > 0) { 
		      for($vlr=0;$vlr<pg_numrows($resultcalc);$vlr++){
			db_fieldsmemory($resultcalc,$vlr);
			fputs($clabre_arquivo->arquivo,str_pad($j17_descr,40));
			fputs($clabre_arquivo->arquivo,substr(db_formatar(1,'f',' ',5),0,5));
			fputs($clabre_arquivo->arquivo,str_pad(db_formatar($j21_valor,'f',' ',18),18,' ',STR_PAD_RIGHT));
		      }
		    } else {
     	              fputs($clabre_arquivo->arquivo,str_repeat(" ",  63));
		    }

		  }

		  $sqltestada = "select j36_testad from iptubase inner join testada on j36_idbql = j01_idbql inner join testpri on j49_idbql = testada.j36_idbql where j01_matric = $j23_matric";
		  $resulttestada = pg_exec($sqltestada);
		  if (pg_numrows($resulttestada) > 0) {
		    db_fieldsmemory($resulttestada,0);
		  } else {
		    $j36_testad = 0;
		  }

		  $sqlareaconstr = "select sum(j39_area) as j39_area from iptuconstr where j39_dtdemo is null and j39_matric = $j23_matric";
		  $resultsqlareaconstr = pg_exec($sqlareaconstr);
		  if (pg_numrows($resulttestada) > 0) {
		    db_fieldsmemory($resultsqlareaconstr,0);
		  } else {
		    $j39_area = 0;
		  }

		  fputs($clabre_arquivo->arquivo,str_pad($z01_cgccpf,20));
		  fputs($clabre_arquivo->arquivo,str_pad($j36_testad,20));
		  fputs($clabre_arquivo->arquivo,str_pad($j34_area,20));
		  fputs($clabre_arquivo->arquivo,str_pad($j39_area,20));
		  fputs($clabre_arquivo->arquivo,str_pad($j13_descr,40));

		  $j43_ender    = str_replace(chr(128),"C",$j43_ender);
		  $j43_numimo   = (int) $j43_numimo;
		  $j43_munic    = str_replace(chr(128),"C",$j43_munic);
		  $j43_bairro   = str_replace(chr(128),"C",$j43_bairro);
		  $z01_munic    = str_replace(chr(128),"C",$z01_munic);
		  $z01_ender    = str_replace(chr(128),"C",$z01_ender);
		  $z01_bairro   = str_replace(chr(128),"C",$z01_bairro);

//                  echo "xxxxxx\n";

                  if ($j43_munic != "" and $j43_ender != "" and $j43_numimo > 0) {
  		    fputs($clabre_arquivo->arquivo,str_pad($j43_munic ,20));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_ender ,40));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_cep   ,8));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_uf    ,2));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_numimo,10));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_cxpost,10));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_comple,20));
		    fputs($clabre_arquivo->arquivo,str_pad($j43_bairro,40));
		    $sqliptucarnes = "insert into iptucarnes values ($j23_matric, '".addslashes($propri1)."', '".addslashes($proprietario)."','".addslashes($j43_ender)."', $j43_numimo, '$j43_comple', '".addslashes($j43_munic)."', '$j43_cep', '$j43_uf')";
		  } else {

		    $z01_numero = (int) $z01_numero;

		    fputs($clabre_arquivo->arquivo,str_pad($z01_munic,20));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_ender,40));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_cep,8));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_uf,2));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_numero,10));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_cxpostal,10));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_compl,20));
		    fputs($clabre_arquivo->arquivo,str_pad($z01_bairro,40));
		    $sqliptucarnes = "insert into iptucarnes values ($j23_matric, '".addslashes($propri1)."', '".addslashes($proprietario)."','".addslashes($z01_ender)."', $z01_numero, '$z01_compl', '".addslashes($z01_munic)."', '$z01_cep', '$z01_uf')";

		  }

		  $resultcarnes = pg_exec($sqliptucarnes);

		  if ($resultcarnes == false) {
		    echo $sqliptucarnes . "\n\n\n\n<br><br><br>";
		  }

		  fputs($clabre_arquivo->arquivo,"\n");
		  
		}
	 
	      }

	    }
	    
	    fclose($clabre_arquivo->arquivo);
	    $erro = true;
	    $descricao_erro =  "Carnes Gerados com Sucesso.";

//            $xxx = $time_inicial_inicial - time();
//	    echo "tempo total: $xxx<br>";

	  }
    }else{
    $erro = true;
    $descricao_erro =  "Erro ao Criar arquivo: $arquivo";
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" >
	    <table width="292" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="25">Quantidade:</td>
            <td height="25"><?=@$quantos?></td>
          </tr>
          <tr> 
            <td width="69" height="25">Arquivo:</td>
            <td width="223" height="25">
			<?
			if(@$quantos!=0){
			  $clabre_arquivo->arquivo;
			}
			?>
			</td>
          </tr>
	  <tr>
	    <td>
	     Situação:
	    </td>
	    <td>
<?	    
      $xy = array("0"=>"Com/sem endereço","com"=>"Com endereço","sem"=>"Sem endereço");
      db_select('situacao',$xy,true,1);
?>      
              
                  
	    </td>
	  </tr>
	  <tr>
	    <td>
	      Processar massa falida:
	    </td>
            <td>
	      <input type='checkbox' name='proc'>
	    </td>
	  </tr>
          <tr> 
	   <td height="25">
	      Ano:
            </td>
            <td height="25">
              <?
	      $result=pg_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
	      if(pg_numrows($result) > 0){
		?>
		<select name="anousu">
		<?
  	        for($i=0;$i<pg_numrows($result);$i++){
		db_fieldsmemory($result,$i);
	        ?>
	        <option value='<?=$j18_anousu?>' <?=(isset($anousu)&&$anousu==$j18_anousu?"selected":"")?>><?=$j18_anousu?></option>
	        <?
	        }
		?>
		</select>
		<?
	      }
	        ?>
            </td>
          </tr>
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25"> <input name="geracarnes"  type="submit" id="geracarnes" value="Gera Carnes" onclick="js_mostra_processando();"> 
            </td>
          </tr>
		  <script>
		  function js_mostra_processando(){
		     document.form1.processando.style.visibility='visible';
		  }
		  </script>
          <tr > 
            <td colspan="2" height="25" align="center" colspan="2" > <input name="processando" id="processando" style='color:red;border:none;visibility:hidden' type="button"  readonly value="Processando. Aguarde..."> 
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($erro==true){
  echo "<script>alert('$descricao_erro');</script>";
}
?>