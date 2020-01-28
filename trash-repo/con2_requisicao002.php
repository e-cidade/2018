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

  // variaveis de cabeçalho;
  db_postmemory($HTTP_SERVER_VARS);
  
  $sql = "select
               e54_numsol,
	       e54_autori,
               e54_emiss,
               e54_valor,
               e54_numcgm,
	       z01_nome
          from empautoriza
             left outer join empempaut on e61_autori = e54_autori
	     inner join cgm on z01_numcgm = e54_numcgm
          where e54_anulad is null
                and e61_autori is null
                and e54_numsol  > 0 
          order by e54_emiss		
		";

  $res = pg_exec($sql) ;
  if (pg_numrows($res) == 0 ){
    db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
  } 

//  $head1 = $nome;
  $head2 = "Solicitações Autorizadas";
  $head3 = "";
//  $head4 = $titulo;
  $head5 = "";
  $head6 = "";
  $head7 = "";
 // $head8 = substr($finalidade, 0,30);
 // $head9 = substr($finalidade,30,30);
  $DB_instit = db_getsession("DB_instit");

 $pdf = new PDF();
 $pdf->Open();
 $pdf->AliasNbPages();
// $pdf->AddPage();
 // monta cabecalho do relatório
 $pdf->setY(40);
 $pdf->setX(5);
 $tam=4;
 $header = true;

 for ($x = 0;$x < pg_numrows($res);$x++){
     if (($pdf->gety() > $pdf->h - 40) || ($header==true)) {
 	 $pdf->addpage(); 
	 $header = false;
         $pdf->setX(15);
         $pdf->Cell(20,$tam,"SOLICITACAO",'TB',0,"R",0);
         $pdf->Cell(20,$tam,"AUTORIZACAO",'TB',0,"R",0);
         $pdf->Cell(20,$tam,"DATA",'TB', 0,"C",0);
         $pdf->Cell(20,$tam,"CGM",'TB',0,"R",0);
         $pdf->Cell(80,$tam,"NOME",'TB',0,"L",0);
         $pdf->Cell(20,$tam,"VALOR",'TB' ,1,"R",0);
	 $pdf->Ln(4);
      }
      db_fieldsmemory($res,$x,true);
      
      $pdf->setX(15);
      $pdf->Cell(20,$tam,"$e54_numsol",'0',0,"R",0);
      $pdf->Cell(20,$tam,"$e54_autori",'0',0,"R",0);
      $pdf->Cell(20,$tam,"$e54_emiss",'0', 0,"C",0);
      $pdf->Cell(20,$tam,"$e54_numcgm",'0',0,"R",0);
      $pdf->Cell(80,$tam,"$z01_nome",'0',0,"L",0);
      $pdf->Cell(20,$tam,"$e54_valor",'0' ,1,"R",0);




 }

 
 //$tmpfile=tempnam("tmp","tmp.pdf"); 
 $pdf->Output();
?>