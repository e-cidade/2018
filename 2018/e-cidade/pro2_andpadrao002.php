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
 //include("libs/db_stdlib.php");
 $processo = '';
 $total    = ''; 
 $sql = "select   p51_codigo,
                  p51_descr,
                  upper(descrdepto) as descrdepto,
                  p53_dias,
		  p53_ordem
         from     andpadrao A inner join tipoproc P 
                  on p53_codigo    = p51_codigo
                  inner join db_depart 
                  on p53_coddepto  = coddepto
         where  p51_instit         = ".db_getsession("DB_instit")."         
         order by p51_descr,p53_ordem";
 $rs = pg_exec($sql);
 $pdf = new pdf();
 $head3 = 'Quantidade Estimada de Dias'; 
 $head4 = 'e Andamento Padrão Por tipo de Processo'; 
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->cell(75,5,'Tipo do Processo',1,0,'C');
 $pdf->cell(75,5,'Departamento',1,0,'C');
 $pdf->cell(20,5,'Ordem',1,0,'C');
 $pdf->cell(20,5,'Dias',1,1,'C');
 $linhas = pg_numrows($rs);
 $pdf->setfillcolor(243);
 for ($i = 0;$i < $linhas;$i++){ 
     db_fieldsmemory($rs,$i);
     if ($pdf->gety() > $pdf->h-30){
         $pdf->addpage();
         $pdf->cell(75,5,'Tipo do Processo',1,0,'C');
         $pdf->cell(75,5,'Departamento',1,0,'C');
	 $pdf->cell(20,5,'Ordem',1,0,'C');
         $pdf->cell(20,5,'Dias',1,1,'C');
     }

     if ($processo != $p51_descr){
         if ($i !=  0){
            $pdf->cell(150,4,'Total de Dias:','T',0,'L',1);
            $pdf->cell(40,4,$total,'T',1,'R',1);
	    $pdf->ln(2);
         }
         $pdf->cell(75,4,$p51_codigo.' - '.$p51_descr,0,0,'L');
         $total = 0;
     }else{
         $pdf->cell(75,4,'',0,0);
     }
     $pdf->cell(75,4,$descrdepto,0,0,'L');
     $pdf->cell(20,4,$p53_ordem,0,0,'R');
     $pdf->cell(20,4,$p53_dias,0,1,'R');
     $total += $p53_dias;
     $processo = $p51_descr;
 }
 
 $pdf->cell(150,4,'Total de Dias:','T',0,'L',1);
 $pdf->cell(40,4,$total,'T',1,'R',1);
 $pdf->output();
?>