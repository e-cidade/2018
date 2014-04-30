<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//require("libs/db_stdlib.php");
include("fpdf151/pdf.php");
require("libs/db_conecta.php");
include("libs/db_sql.php");
include("classes/db_aguacortematmov_classe.php");

db_postmemory($HTTP_GET_VARS);
//echo "situacao = $situacao";

$claguacortematmov = new cl_aguacortematmov();


$sql .= "
	select x01_matric,
	       case
	         when prom.z01_nome is not null then
	           prom.z01_nome
	         else
	           prop.z01_nome
	       end as z01_nome,
	       j14_nome,
	       x01_numero,
	       x11_complemento,
	       x01_letra,
	       x04_nrohidro,
         x04_qtddigito,
	       x21_exerc,
	       x21_mes,
         x21_leitura,
		     j88_sigla
	  from aguabase
	       left  join aguaconstr                    on x11_matric = x01_matric
	                                               and x11_tipo   = 'P'
	       inner join aguahidromatric               on x04_matric = x01_matric
                                                 and fc_agua_hidrometroativo(x04_codhidrometro) is true";
if((isset($ano)) and (isset($mes))){

	$sql .= "
				 inner join agualeitura                   on x21_codhidrometro = x04_codhidrometro";
}else {
	
	$sql .= "
	       inner join aguahidromatricultimaleitura  on x09_codhidrometro = x04_codhidrometro
	       inner join agualeitura                   on x21_codleitura = x09_codleitura";
}

$sql .= "
	       inner join aguasitleitura                on x17_codigo = x21_situacao
	       inner join cgm prop                      on prop.z01_numcgm = x01_numcgm
	       left  join cgm prom                      on prom.z01_numcgm = x01_promit
	       inner join ruas                          on j14_codigo = x01_codrua
		     inner join ruastipo                      on j14_tipo = j88_codigo
	 where x21_situacao = $situacao";

if((isset($ano)) and (isset($mes))){
	$sql .= " and x21_mes = $mes and x21_exerc = $ano ";
}

$sql .= "
	order by x01_codrua, x01_letra, x01_numero, x11_complemento
	";


$result = pg_query($sql);
$linhas = pg_num_rows($result);
if($linhas==0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$sqlsit = "select x17_descr from aguasitleitura where x17_codigo = $situacao" ;
$resultsit = pg_query($sqlsit);
db_fieldsmemory($resultsit,0);
			
$head2 = "Relatório de Situação de Hidrômetros";
$head3 = "Situação: {$situacao} - {$x17_descr}";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$total = 0;
$troca = 1;
$alt   = 4;
$total = 0;
$p     = 0;

for($x = 0; $x < $linhas;$x++){
	
   db_fieldsmemory($result,$x);
   
   $sSqlSitCorte = $claguacortematmov->sql_query(null, "x42_codsituacao", "x42_data desc, x42_codmov desc limit 1", "x41_matric = $x01_matric");
   $rsSitCorte   = $claguacortematmov->sql_record($sSqlSitCorte);
   
   if(($filtro == 1) || ($filtro == 2)) {
     
	   if($claguacortematmov->numrows > 0) { 
	     
	     db_fieldsmemory($rsSitCorte, 0);
	    
	   }else {
	     
	     if($filtro == 2) {
	       
	       continue;  
	       
	     } else {
	       
	       $x42_codsituacao = '';
	       
	     }
	      
	   }
	   
   } else {
     
     if($claguacortematmov->numrows > 0) {
       
       continue;
       
     }
     
   }

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
   	
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);

      $pdf->cell(20, $alt, "Matricula"     , 1, 0, "C", 1);
      $pdf->cell(70, $alt, "Proprietário"  , 1, 0, "C", 1);      
      $pdf->cell(70, $alt, "Endereço"      , 1, 0, "C", 1);
      $pdf->cell(15, $alt, "Número"        , 1, 0, "C", 1);
      $pdf->cell(25, $alt, "Complemento"   , 1, 0, "C", 1);
      $pdf->cell(25, $alt, "Nº Hidrômetro" , 1, 0, "C", 1);  
      $pdf->cell(10, $alt, "Ano"           , 1, 0, "C", 1);  
      $pdf->cell(10, $alt, "Mês"           , 1, 0, "C", 1);        
      $pdf->cell(15, $alt, "Leitura"       , 1, 0, "C", 1);        
      $pdf->cell(20, $alt, "Sit Corte"     , 1, 1, "C", 1);   
      $troca = 0;
      $p     = 0;
      
   }
   
   $pdf->setfont('arial','',7);
      
   $pdf->cell(20, $alt, $x01_matric,              0, 0, "C", $p);
   $pdf->cell(70, $alt, $z01_nome,                0, 0, "L", $p);
   $pdf->cell(70, $alt, $j88_sigla.". ".$j14_nome,0, 0, "L", $p);
   
   $letra = trim($x01_letra);
   $numero = "$x01_numero" . (empty($letra)?"":"/".$letra);
   
   $pdf->cell(15, $alt, $numero,                  0, 0, "R", $p);   
   $pdf->cell(25, $alt, $x11_complemento,         0, 0, "L", $p);
   $pdf->cell(25, $alt, $x04_nrohidro,            0, 0, "L", $p);
   $pdf->cell(10, $alt, $x21_exerc,               0, 0, "C", $p);
   $pdf->cell(10, $alt, $x21_mes,                 0, 0, "C", $p);
   $pdf->cell(15, $alt, $x21_leitura,             0, 0, "C", $p);
   $pdf->cell(20, $alt, @$x42_codsituacao,        0, 1, "C", $p);
   
   if ($p == 0) {
   	$p = 1;
   }
   else { 
   	$p = 0;  
   }
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE REGISTROS : '.$total,"T",0,"L",0);
$pdf->Output();

?>