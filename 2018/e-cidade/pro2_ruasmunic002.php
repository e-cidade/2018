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
include("libs/db_sql.php");
include("classes/db_ruas_classe.php");
$clruas   = new cl_ruas;
$clrotulo = new rotulocampo;
$clrotulo->label('j14_codigo');
$clrotulo->label('j14_nome');
$clrotulo->label('j13_codi');
$clrotulo->label('j13_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($ordem == "a") {
  $desc_ordem = "Alfabética";
  $order_by = "j14_nome";
}
else {
  $desc_ordem = "Numérica";
  $order_by = "j14_codigo";
}
 
$head3 = "RELATÓRIO DE RUAS DO MUNICÍPIO";
$head5 = "ORDEM $desc_ordem";

//die($clruas->sql_query_bairro(null,"*",$order_by));
#$sql = " select j14_codigo,j14_nome,j13_codi,j13_descr from ruas left join bairro on ruas.j14_bairro = bairro.j13_codi order by $order_by ";
$sql = "SELECT  distinct j14_codigo,
                j14_nome,
								j16_bairro,
								j13_descr,
                j13_codi,
                j29_cep
          FROM  ruas 
            LEFT JOIN ruasbairro  ON ruas.j14_codigo = ruasbairro.j16_lograd
            LEFT JOIN bairro      ON ruasbairro.j16_bairro = bairro.j13_codi
            LEFT JOIN ruascep     ON ruas.j14_codigo = ruascep.j29_codigo
				ORDER BY $order_by";

//die($sql);
$result = pg_query($sql);
$numrows = pg_numrows($result);

//db_criatabela($result);exit;

if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Nenhuma rua encontrada.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;

for($x=0;$x<$numrows;$x++){
  db_fieldsmemory($result,$x);
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("");
    $pdf->setfont('arial','b',8);
    $pdf->setfillcolor(210);
    $pdf->cell(15,$alt,"Rua",1,0,"C",1);
    $pdf->cell(60,$alt,$RLj14_nome,1,0,"C",1);
    $pdf->cell(15,$alt,"Bairro",1,0,"C",1);
    $pdf->cell(60,$alt,$RLj13_descr,1,0,"C",1);
    $pdf->cell(15,$alt,"CEP",1,1,"C",1);
    $troca = 0;
  }
	if ($x % 2 == 0 ){
     $pdf->setFillColor(255);
	}else{
     $pdf->setFillColor(236);
	}
  $pdf->setfont('arial','',7);
  $pdf->cell(15,$alt,$j14_codigo,0,0,"R",1);
  $pdf->cell(60,$alt,$j14_nome,0,0,"L",1);
  $pdf->cell(15,$alt,$j13_codi,0,0,"R",1);
  $pdf->cell(60,$alt,$j13_descr,0,0,"L",1);
  $pdf->cell(15,$alt,($j29_cep == 0?$j29_cep:db_formatar($j29_cep,'cep')),0,1,"R",1);
  $total++;
}

$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

$pdf->Output();
   
?>