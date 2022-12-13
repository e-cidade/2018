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

  
  //
  include("fpdf151/pdf.php");
  // variaveis de cabeçalho



  $campos = "z01_nome,z01_ender";
  $from = " cgm ";
  $where = " z01_nome like 'PAULO RICARDO DA S%'"; 
  $sql = "select ".$campos." from ".$from." where ".$where;
  $resultsql = pg_query($sql);
  if($resultsql==false){
    echo "Verifique os dados a serem gerados.<br>";
	echo $sql;
  }

  $head1 = "";
  $head2 = "";
  $head3 = "";
  $head4 = "";
  $head5 = "";
  $head6 = "";
  $head7 = "";
  $head8 = "";
  $head9 = "";
  
 $DB_instit = 1;
 
  $pdf = new PDF();

  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage();


  // monta cabecalho do relatório    
  
  $pdf->SetFont('Courier','B',9);
  $pdf->setX(5);
  

  $clrotulolov = new rotulolov; 
  $fm_numfields = pg_numfields($resultsql);
  $tamanho = array();
  for ($i = 0;$i < $fm_numfields;$i++){
    $clrotulolov->label(pg_fieldname($resultsql,$i));
    $pdf->Cell($clrotulolov->tamanho,4,$clrotulolov->titulo,"LRBT",($i==($fm_numfields-1)?1:0),"L",0);
    if($clrotulolov->tamanho==""){
  	  $tamanho[$i] = 10;
    }else{
  	  $tamanho[$i] = $clrotulolov->tamanho+4;
    }
  }

  // corpo do relatório


  $linha = 0;

  for ($xi=0;$xi < $resultsql;$xi++){
    $pdf->setX(5);
    //db_fieldsmemory($resultsql,$i);
    for ($c=0;$c<($fm_numfields-1);$c++){
	  $pdf->Cell($tamanho[$c],4,pg_result($resultsql,$xi,$c),"B",0,"L",0);
    }
	$pdf->Cell($tamanho[($fm_numfields-1)],4,pg_result($resultsql,$xi,($fm_numfields-1)),"B",1,"L",0);
    $linha += 1;
	if($linha>57){
	   $linha = 0;
       $pdf->AddPage();
       for ($cabe=0;$cabec < $fm_numfields;$cabec++){
         $clrotulolov->label(pg_fieldname($resultsql,$cabec));
         $pdf->Cell($clrotulolov->tamanho,4,$clrotulolov->titulo,"LRBT",($cabec==($fm_numfields-1)?1:0),"L",0);
       }	   
    }
	break;
  }

  $pdf->Output();

?>