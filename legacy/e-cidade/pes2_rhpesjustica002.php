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
include("classes/db_rhpesjustica_classe.php");
$clrhpesjustica = new cl_rhpesjustica;
$clrotulo = new rotulocampo;
$clrotulo->label('rh61_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$orderby = " z01_nome ";
$ordenacao = "ALFABÉTICA";
if($ordem == "n"){
  $ordenacao = "NUMÉRICA";
  $orderby = " rh01_regist ";
}

$head3 = "RELATÓRIO DE FUNCIONÁRIOS NA JUSTIÇA";
$head4 = "ORDEM: ".$ordenacao;

$resultado_sql = $clrhpesjustica->sql_record($clrhpesjustica->sql_query_cgm(null,"rh61_regist, z01_nome",$orderby));
$qtd_linhas_sql = $clrhpesjustica->numrows;
if($qtd_linhas_sql == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários na justiça.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$imprime_cabecalho = true;
$alt = 4;

for($x=0; $x<$qtd_linhas_sql; $x++){

  db_fieldsmemory($resultado_sql, $x);

  if ($pdf->gety() > $pdf->h - 30 || $imprime_cabecalho == true){
     $pdf->addpage();
     $pdf->setfont('arial','b',8);

     $pdf->cell(20,$alt,$RLrh61_regist,1,0,"C",1);
     $pdf->cell(90,$alt,$RLz01_nome,1,1,"C",1);

     $imprime_cabecalho = false;
  }

  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$rh61_regist,0,0,"C",0);
  $pdf->cell(90,$alt,$z01_nome,0,1,"L",0);
}

$pdf->setfont('arial','b',8);
$pdf->cell(110,$alt,'TOTAL DE REGISTROS  : '.$qtd_linhas_sql,"T",1,"C",0);
$pdf->Output();
?>