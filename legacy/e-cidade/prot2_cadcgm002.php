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

include("classes/db_cgm_classe.php");
include("classes/db_cgmalt_classe.php");
include("fpdf151/pdf.php");

db_postmemory($HTTP_GET_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcgm	  = new cl_cgm;
$clcgmalt = new cl_cgmalt;

$clcgm->rotulo->label();
$clcgmalt->rotulo->label();

if(isset($seqalt) && trim($seqalt) != ""){
	$result = $clcgmalt->sql_record($clcgmalt->sql_query_file($seqalt));
}else{
	$result = $clcgm->sql_record($clcgm->sql_query_file($numcgm));
	if($clcgm->numrows=0){
			db_redireciona("db_erros.php?fechar=true&db_erro=Número do CGM nao Encontrado");
				exit;
	} 
}

db_fieldsmemory($result,0,true);

$head4 = "RELATÓRIO CADASTRO DO CGM";
$tam = 0;
for($ii=0;$ii<pg_numfields($result);$ii++){
   $tamlabel = "RL".pg_fieldname($result,$ii);
//   echo 'campo : '.$tamlabel.'   tammanho : '.strlen($tamlabel).'<br>';
   if ( $tam < strlen($tamlabel)){
      $tam = strlen($tamlabel);
   }
}
$tam= $tam*3;
$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
for($i=0;$i<pg_numfields($result);$i++){
   $label = "RL".pg_fieldname($result,$i);
   $campo = pg_fieldname($result,$i);
   $pdf->SetFont('courier','B',8);
   $pdf->cell($tam,6,db_formatar(strtoupper(@$$label),'s',".",$tam/2+3,"d"),0,0,"L",0);
   $pdf->cell(2,6,':',0,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(100,6,@$$campo,0,1,"L",0);
}
$pdf->Output();
?>