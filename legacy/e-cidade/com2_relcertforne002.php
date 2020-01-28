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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_pcsubgrupo_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clpcsubgrupo   = new cl_pcsubgrupo;

$descr_subgrupo = "";
$dbwhere        = "1=1";
if (isset($pc04_codsubgrupo) && trim($pc04_codsubgrupo) != "") {
     $dbwhere     .= " and pc04_codsubgrupo = $pc04_codsubgrupo";
     $res_subgrupo = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query_file($pc04_codsubgrupo,"pc04_descrsubgrupo"));

     if ($clpcsubgrupo->numrows > 0){
          db_fieldsmemory($res_subgrupo,0);
	  $descr_subgrupo = $pc04_descrsubgrupo;
     }
}

$dbordem = " order by ";
$info    = "Ordem: ";
if ($ordem == "A"){
     $dbordem .= "z01_nome";  
     $info    .= "Alfabética";
} else if($ordem == "N"){
     $dbordem .= "pc74_codigo";  
     $info    .= "Numérica";
} else if ($ordem == "D"){
     $dbordem .= "pc74_data desc";  
     $info    .= "Data";
}

$dbordem .= ", pc74_codigo, pc60_numcgm";
$dbagrupar = "group by pc60_numcgm, z01_nome, z01_cgccpf, z01_telcon, z01_fax, pc04_descrsubgrupo, pc74_codigo, pc74_data";

$head3   = "Relatório de Certificado de Fornecedores";
$head4   = $info;
$head5   = $descr_subgrupo; 

$sql     = "select distinct pc60_numcgm as cgm,
                            z01_nome    as fornecedor,
                            z01_cgccpf  as cnpj,
	                    z01_telcon||'/'||z01_fax as telefone,
		            pc04_descrsubgrupo       as subgrupo,
		            pc74_codigo              as crc,
		            pc74_data                as data
	    from cgm 
	         inner join pcforne         on pcforne.pc60_numcgm          = cgm.z01_numcgm
	         inner join pcfornecertif   on pcfornecertif.pc74_pcforne   = cgm.z01_numcgm
	         left  join pcfornesubgrupo on pcfornesubgrupo.pc76_pcforne = cgm.z01_numcgm
	         left  join pcsubgrupo      on pcsubgrupo.pc04_codsubgrupo  = pcfornesubgrupo.pc76_pcsubgrupo
	    where $dbwhere";

$sql    .= $dbagrupar;
$sql    .= $dbordem;

$result  = $clpcsubgrupo->sql_record($sql);
$numrows = $clpcsubgrupo->numrows;
//echo $sql; exit;
//db_criatabela($result); exit;

if ($numrows == 0) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->lMargin = 20;
$pdf->AddPage("L");
$alt     = 4;
$imp     = 1;
$imp_crc = 1;
$troca   = 1;
$borda   = 0;
$p       = 1;

$tot_reg = 0;
$cgm_ant = 0;
$crc_ant = 0;
$seq     = 0;

for ($i = 0; $i < $numrows; $i++) {
      db_fieldsmemory($result,$i);

      if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
           $pdf->setfont('arial', 'b', 8);
	   $pdf->cell(10,  $alt, "Seq.", 1, 0, "C", 1);
	   $pdf->cell(15,  $alt, "CGM", 1, 0, "C", 1);
	   $pdf->cell(100, $alt, "Fornecedor", 1, 0, "C", 1);
	   $pdf->cell(30,  $alt, "CNPJ", 1, 0, "C", 1);
	   $pdf->cell(50,  $alt, "Telefones", 1, 0, "C", 1);

	   $pdf->cell(30,  $alt, "CRC",  1, 0, "C", 1);
	   $pdf->cell(20,  $alt, "Data", 1, 1, "C", 1);
	   
	   if (trim($descr_subgrupo)==""){
	        $pdf->cell(255, $alt, "Subgrupo", 1, 1, "C", 1);
	   }

	   $troca = 0;
           $pdf->setfont('arial', '', 8);
      }		
     
      if ($cgm_ant != $cgm){
	   if ($cgm_ant != 0){
                $pdf->cell(255, ($alt+2), "", $borda, 1, "L", $p);
	   }
           $cgm_ant = $cgm;
   	   $imp     = 1;
      } else {
           $imp = 0;
      }

      if ($p == 1) {
           $p = 0;
      } else {
           $p = 1;
      }

      if ($crc_ant != $crc){
	   $crc_ant = $crc;
	   $imp_crc = 1;
      } else {
	   $imp_crc = 0;
      }

      if ($imp==1){
	   $seq++;

           $pdf->cell(10,  $alt, $seq, $borda, 0, "C", $p);
           $pdf->cell(15,  $alt, $cgm, $borda, 0, "R", $p);
           $pdf->cell(100, $alt, $fornecedor, $borda, 0, "L", $p);
           $pdf->cell(30,  $alt, db_formatar($cnpj,"cnpj"), $borda, 0, "C", $p);
           $pdf->cell(50,  $alt, $telefone, $borda, 0, "L", $p);

           $tot_reg++;
      }

      $p_ant = $p;

      if ($imp_crc==1){
	   if ($imp==0){
                if ($p == 1) {
                     $p = 0;
                } else {
                     $p = 1;
                }

	        $pdf->cell(205, $alt, "", $borda, 0, "L", $p);
	   }

           $pdf->cell(30,  $alt, $crc, $borda, 0, "R", $p);
           $pdf->cell(20,  $alt, db_formatar($data,"d"), $borda, 1, "C", $p);

	   if ($imp==0){
                $p     = 0;
                $p_ant = $p;
	   }
      }

      if ($p == 1) {
           $p = 0;
      } else {
           $p = 1;
      }

      if (trim($descr_subgrupo)==""){
           $pdf->cell(255, $alt, $subgrupo, $borda, 1, "L", $p);
      }

      $p = $p_ant;

      if ($pdf->gety() > $pdf->h - 41) {
	   $pdf->AddPage("L");

           $pdf->setfont('arial', 'b', 8);
	   $pdf->cell(10,  $alt, "Seq.", 1, 0, "C", 1);
	   $pdf->cell(15,  $alt, "CGM", 1, 0, "C", 1);
	   $pdf->cell(100, $alt, "Fornecedor", 1, 0, "C", 1);
	   $pdf->cell(30,  $alt, "CNPJ", 1, 0, "C", 1);
	   $pdf->cell(50,  $alt, "Telefones", 1, 0, "C", 1);

	   $pdf->cell(30,  $alt, "CRC",  1, 0, "C", 1);
	   $pdf->cell(20,  $alt, "Data", 1, 1, "C", 1);
	   
	   if (trim($descr_subgrupo)==""){
	        $pdf->cell(255, $alt, "Subgrupo", 1, 1, "C", 1);
	   }

           $pdf->setfont('arial', '', 8);
	   $p = 0;
      }
}

$pdf->setfont('arial', 'b', 8);
$pdf->cell(255, $alt+2, "TOTAL DE REGISTROS: ".$tot_reg, "T", 1, "R", 0);

$pdf->Output();
?>