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
 //$rs = pg_exec($sql);
 $pdf = new pdf();
 $head3 = 'Resumo dos acidentes Em sapiranga';
 $head4 = 'Acidentos por Dia da Semana';
 $pdf->open();
 $pdf->addpage();
 $pdf->aliasNbPages();
 $pdf->setx(10);
 $pdf->setfillcolor(204);
 $pdf->cell(75,5,"DIAS DA SEMANA",1,1,"C",1);
 //$pdf->setxy(10)
 $pdf->cell(50,5,"DOMINGO",1,0,"L");
 $pdf->cell(25,5,"DOM",1,1,"C");
 $pdf->cell(50,5,"SEGUNDA-SEGUNDA",1,0,"L");
 $pdf->cell(25,5,"2ª",1,1,"C");
 $pdf->cell(50,5,"TERÇA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"3ª",1,1,"C");
 $pdf->cell(50,5,"QUARTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"4ª",1,1,"C");
 $pdf->cell(50,5,"QUINTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"5ª",1,1,"C");
 $pdf->cell(50,5,"SEXTA-FEIRA",1,0,"L");
 $pdf->cell(25,5,"6ª",1,1,"C");
 $pdf->cell(50,5,"SÁBADO",1,0,"L");
 $pdf->cell(25,5,"SAB",1,1,"C");
 $x = 0;
 for ($i = 1; $i <= 12 ;$i++){
      $sql = "select extract(dow from tr07_data) as dia_semana,
                    count(*) as quantidade
             from   Acidentes
             where  extract(month from tr07_data) = $i
             group by dia_semana order by dia_semana";
             }
 $pdf->output();