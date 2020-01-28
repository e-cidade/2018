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


    $str_arquivo = $_SERVER['PHP_SELF'];
    set_time_limit(0);

    require("libs/db_stdlib.php");
    require("libs/db_conn.php");

		// verificar porque numpre da inicialnumpre tem que ser igual a numpre da divida

    echo "Conectando...\n";

//		$DB_BASE = "ontem_20061109_1140";

    if(!($conn1 = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE       port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA")) )
    {
      echo "erro ao conectar...\n";
      exit;
    }
		
    if(!($conn2 = pg_connect("host='192.168.1.1' dbname='sam30' port=5432 user=postgres")) )
    {
      echo "erro ao conectar...\n";
      exit;
    }

    $str_hora = date("h:m:s");
  
    pg_exec( $conn1, "begin;");
    
    echo "Selecionando...\n";

    $erro = false;

		$codret = 1;

    $str_sql =	"	select k00_numpre, k00_numpar, disbanco.codret from disbanco
									inner join disarq on disarq.codret = disbanco.codret
									where classi is false and 
												dtretorno >= current_date - '3 day'::interval and
												length(trim(k00_numbco)) = 0";
								
    $res_select = pg_query( $conn1, $str_sql ) or die($str_sql);
    $int_linhas = pg_num_rows( $res_select );

		echo "int_linhas: $int_linhas\n";

    for( $i=0; $i<$int_linhas; $i++ ) {
       db_fieldsmemory( $res_select, $i );

			 echo "k00_numpre: $k00_numpre - k00_numpar: $k00_numpar\n";

       if ($k00_numpar > 0) {
			   $numpre_sam30 = str_pad($k00_numpre, 8, "0", STR_PAD_LEFT) . str_pad($k00_numpar, 2, "0", STR_PAD_LEFT);
			 } else {
			   $numpre_sam30 = str_pad($k00_numpre, 8, "0", STR_PAD_LEFT);
			 }

       $sql = "	select * from arrecad_2006_conv 
								where k05_numpre like '%$numpre_sam30%'";
			 $result = pg_exec( $conn2, $sql) or die($sql);
			 if (pg_num_rows($result) > 0) {
				 db_fieldsmemory($result, 0);

				 echo "matric: $k05_matric\n";

				 $sql2 = "select arrecad.k00_numpre as numpre_novo, arrecad.k00_numpar as numpar_novo from arrecad 
									inner join divida on v01_numpre = k00_numpre and v01_numpar = k00_numpar
									inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
									where k00_matric = $k05_matric " . ($k00_numpar > 0?" and extract (month from arrecad.k00_dtvenc) = $k00_numpar ":"") . "and v01_exerc = 2006";
			   $result2 = pg_exec( $conn1, $sql2) or die($sql2);
				 if (pg_num_rows($result2) == 1) {
					 db_fieldsmemory($result2, 0);

					 echo "correto - apenas 1 numpre...\n";
					 
					 $sqlupdate = "	update disbanco set k00_numpre = $numpre_novo, k00_numpar = $numpar_novo 
													where k00_numpre = $k00_numpre and k00_numpar = $k00_numpar and codret = $codret";
			     $resultupdate = pg_exec( $conn1, $sqlupdate) or die($sqlupdate);
					 echo "update... antigo: $k00_numpre / $k00_numpar - novo: $numpre_novo / $numpar_novo\n";

				 } elseif (pg_num_rows($result2) > 1) {

           $sqlnext  = "select nextval('numpref_k03_numpre_seq') as k03_numpre";
					 $resultnext = pg_exec($conn1, $sqlnext);
 					 db_fieldsmemory($resultnext, 0);

           for ($reg=0; $reg < pg_num_rows($result2); $reg++) {
						 db_fieldsmemory($result2, $reg);

						 echo "numpres para recibo: $numpre_novo/$numpar_novo\n";

						 $sqlrecibo = "insert into db_reciboweb values($numpre_novo, $numpar_novo, $k03_numpre, 0,'','')";
						 pg_exec($conn1, $sqlrecibo) or die($sqlrecibo);

					 }
					 $sqlgera = "select fc_recibo($k03_numpre,current_date,current_date, extract (year from current_date)::integer)";
					 $resultgera = pg_exec($conn1, $sqlgera) or die($sqlgera);
					 
					 echo "encontrou mais de 1 numpre... gerando recibo com numpre: $k03_numpre\n";
					 
					 $sqlupdate = "	update disbanco set k00_numpre = $k03_numpre, k00_numpar = 0 
													where k00_numpre = $k00_numpre and k00_numpar = $k00_numpar and codret = $codret";
			     $resultupdate = pg_exec( $conn1, $sqlupdate) or die($sqlupdate);
					 echo "update... antigo: $k00_numpre / $k00_numpar - novo: $k03_numpre\n";

				 } else {
					 echo "encontrou " . pg_num_rows($result2) . " registros...\n";
				 }
				 
			 } else {
				 echo "nao achou nada procurando por: $numpre_sam30\n";
			 }

    }

    if ($erro == false) {
      pg_exec( $conn1, "commit;");
			echo "comitando...\n";
    } else {
      pg_exec( $conn1, "rollback;");
    }

?>