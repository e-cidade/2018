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
// variaveis de cabeçalho
db_postmemory($HTTP_SERVER_VARS);
// decodifica sql
$sql=base64_decode($sql);
$sql=urldecode($sql);

$resultsql = pg_exec(str_replace('\\','',$sql));
  if($resultsql==false){
    echo "Verifique os dados a serem gerados.<br>";
	echo $sql;
  }
/*
  $head1 = "$nome";
  $head2 = "";
  $head3 = "";
  $head4 = "$titulo";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "substr($finalidade, 0,30)";
  $head9 = "substr($finalidade,30,30)";
*/
  $head1 = "";
  $head2 = "";
  $head3 = "";
  $head4 = "";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "";
  $head9 = "";
  
    
  $DB_instit = db_getsession("DB_instit");
 
  $pdf = new PDF();

//  $pdf->gera_txt_paulo = true;

  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage("L");

  // monta cabecalho do relatório    




  $pdf->SetFillColor(235);



  
  $pdf->SetFont('Courier','B',9);
  
  $pdf->setY(40);
  $pdf->setX(5);

  $clrotulolov = new rotulolov; 
  $fm_numfields = pg_numfields($resultsql);
  $tamanho = array();
  for ($i = 0;$i < $fm_numfields;$i++){
    $clrotulolov->label(pg_fieldname($resultsql,$i));
    $pdf->Cell((($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2,4,$clrotulolov->titulo,"LRBT",($i==($fm_numfields-1)?1:0),"L",0);
    if($clrotulolov->tamanho==""){
  	  $tamanho[$i] = 20;
    }else{
  	  $tamanho[$i] = (($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2;
    }
  }

  // corpo do relatório


  $linha = 0;

  for ($xi=0;$xi<pg_numrows($resultsql);$xi++){
    $pdf->setX(5);
    //db_fieldsmemory($resultsql,$i);
    for ($c=0;$c<($fm_numfields-1);$c++){
//	  $pdf->Cell($tamanho[$c],4,pg_result($resultsql,$xi,$c),"",0,"L",0);
	  $pdf->Cell($tamanho[$c],4,pg_result($resultsql,$xi,$c),"",0,"L",($linha%2==0?0:1));
    }
	$pdf->Cell($tamanho[($fm_numfields-1)],4,pg_result($resultsql,$xi,($fm_numfields-1)),"",1,"L",($linha%2==0?0:1));
        $linha += 1;
	if($linha>35){
	   $linha = 0;
       $pdf->AddPage("L");
       $pdf->setX(5);
       for ($cabec=0;$cabec < $fm_numfields;$cabec++){
         $clrotulolov->label(pg_fieldname($resultsql,$cabec));
         //$pdf->Cell($clrotulolov->tamanho*2,4,$clrotulolov->titulo,"LRBT",($cabec==($fm_numfields-1)?1:0),"L",0);
         $pdf->Cell((($clrotulolov->tamanho>strlen($clrotulolov->titulo)?$clrotulolov->tamanho:strlen($clrotulolov->titulo))*2)+2,4,$clrotulolov->titulo,"LRBT",($cabec==($fm_numfields-1)?1:0),"L",0);
       }	   
    }
  }

  $pdf->Cell(200,2,"","",1,"L",0);
  $pdf->Cell(200,0,"","LRBT",1,"L",0);

  $tmpfile=tempnam("tmp","tmp.pdf");
  $pdf->Output();

//  echo "<script> location.href='".$tmpfile."'</script>";

?>