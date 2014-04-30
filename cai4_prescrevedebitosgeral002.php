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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$instit = db_getsession("DB_instit");

$sql = "select *from lista where k60_codigo = $lista";
$result= pg_query($sql);
db_fieldsmemory($result,0);
$head1 = "Descrição: ".$k60_descr;
$head2 = "Lista Número: ".$lista;
$head3 = "Data da lista: ".db_formatar($k60_datadeb,"d");

$sqlLista = " select	distinct
									listadeb.k61_codigo, 
									listadeb.k61_numpre, 
									listadeb.k61_numpar, 
									certter.v14_parcel, 
									termo.v07_parcel, 
									notidebitos.k53_numpre,
                                    certdiv.v14_certid,
                                    arrecad.k00_tipo,
                                    arretipo.k03_tipo,
                                    arretipo.k00_descr,
                                    arrenumcgm.k00_numcgm,
                                    arrematric.k00_matric,
                                    arreinscr.k00_inscr,
                                    cgm.z01_nome
									
			from listadeb
      inner join lista          on k61_codigo               = k60_codigo
                               and k60_instit               = $instit
      left  join divida         on listadeb.k61_numpre      = divida.v01_numpre
                               and listadeb.k61_numpar      = divida.v01_numpar
      left  join certdiv        on certdiv.v14_coddiv       = divida.v01_coddiv
      inner join arrecad        on listadeb.k61_numpre      = arrecad.k00_numpre 
                               and listadeb.k61_numpar      = arrecad.k00_numpar
      inner join arretipo       on arretipo.k00_tipo        = arrecad.k00_tipo
      left  join arrenumcgm     on arrenumcgm.k00_numpre    = arrecad.k00_numpre
      left  join cgm            on arrenumcgm.k00_numcgm    = cgm.z01_numcgm
      left  join arrematric     on arrematric.k00_numpre    = arrecad.k00_numpre
      left  join arreinscr      on arreinscr.k00_numpre     = arrecad.k00_numpre
      left  join inicialnumpre  on inicialnumpre.v59_numpre = divida.v01_numpre
      left  join termo          on termo.v07_numpre         = listadeb.k61_numpre
      left  join certter        on certter.v14_parcel       = termo.v07_parcel
      left  join notidebitos    on notidebitos.k53_numpre   = listadeb.k61_numpre
                               and notidebitos.k53_numpar   = listadeb.k61_numpar

      where k61_codigo = $lista and k03_tipo <> 5";
//die($sqlLista);
$resultLista = pg_query($sqlLista);
$linhasLista= pg_num_rows($resultLista);

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$alt = 5;
$pag = 1;
$totregistros=0;

for($x = 0; $x < $linhasLista; $x++){
  if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ){
    $pdf->addpage();
    $pag = 0;
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(20,$alt,"CGM","BT",0,"L",1);
    $pdf->Cell(65,$alt,"NOME","BT",0,"L",1);
    $pdf->Cell(20,$alt,"NUMPRE","BT",0,"L",1);
    $pdf->Cell(15,$alt,"NUMPAR","BT",0,"C",1);
    $pdf->Cell(20,$alt,"TIPO DÉBITO","BT",0,"C",1);
    $pdf->Cell(50,$alt,"NOME DO DÉBITO","BT",0,"L",1);
    $pdf->ln();

  }
  db_fieldsmemory($resultLista,$x);
   $pdf->SetFont('Arial','',8);
    $pdf->Cell(20,$alt,$k00_numcgm,"BT",0,"L",0);
    $pdf->Cell(65,$alt,$z01_nome,"BT",0,"L",0);
    $pdf->Cell(20,$alt,$k61_numpre,"BT",0,"L",0);
    $pdf->Cell(15,$alt,$k61_numpar,"BT",0,"C",0);
    $pdf->Cell(20,$alt,$k00_tipo,"BT",0,"C",0);
    $pdf->Cell(50,$alt,$k00_descr,"BT",0,"L",0);
    $pdf->ln();
}

$pdf->Output();
?>