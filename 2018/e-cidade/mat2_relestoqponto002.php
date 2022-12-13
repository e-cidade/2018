<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_matmaterestoque_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatmaterestoque = new cl_matmaterestoque;

$dbwhere = " where 1 = 1 ";
if (isset($coddeposito) && trim(@$coddeposito) != ""){
     $dbwhere .= " and m91_codigo in ($coddeposito)";
}
if($ponto == 'p'){
  $dbwhere .= " and (coalesce(m70_quant,0) - coalesce(m64_pontopedido,0)) <= 0";
}

$sql_estoqponto = "select m91_codigo,
                          coddepto,
                          descrdepto,
                          m64_matmater, 
                          m60_descr, 
                          m64_pontopedido, 
                          coalesce(m70_quant, 0) as m70_quant, 
                          (coalesce(m70_quant,0) - coalesce(m64_pontopedido,0)) as diferenca
                   from matmaterestoque
                        inner join matmater   on matmater.m60_codmater      = matmaterestoque.m64_matmater
                        inner join db_almox   on db_almox.m91_codigo        = matmaterestoque.m64_almox
                        inner join db_depart  on db_depart.coddepto         = db_almox.m91_depto
                        inner join matestoque on matestoque.m70_codmatmater = matmater.m60_codmater and
                                                 matestoque.m70_coddepto    = db_almox.m91_depto ";
if (strlen(trim(@$dbwhere)) > 0){
     $sql_estoqponto .= $dbwhere; 
}

if($ordem == 'a'){
  $sql_estoqponto .= " order by m91_codigo, trim(m60_descr) " ;
}else{
  $sql_estoqponto .= " order by m91_codigo, m64_matmater ";
}
$resultado = $clmatmaterestoque->sql_record($sql_estoqponto);
$numrows   = $clmatmaterestoque->numrows;
if ($numrows == 0){
     db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem registros cadastrados.');
}
// echo $sql_estoqponto;
// db_criatabela($resultado); exit;

$head3 = "Relatorio de Estoque de Ponto de Pedido";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->SetLeftMargin(5); 

$alt   = 4;
$troca = 1;
$p     = 0;
$imp   = 0;

$total_registros       = 0;

$total_geral_estoque   = 0;
$total_geral_pedido    = 0;
$total_geral_diferenca = 0;

$total_estoque         = 0;
$total_pedido          = 0;
$total_diferenca       = 0;

$codigo_ant = 0;
for($i = 0; $i < $numrows; $i++){
     db_fieldsmemory($resultado, $i);

	   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp == 1) {
		      if ($imp==0) {
		           $pdf->AddPage();
      		}

          $pdf->setfont("arial", "b", 8);
          
          $pdf->cell(20,$alt,"Cod. Material",         "TBRL",0,"C",1);
          $pdf->cell(90,$alt,"Descricao do Material","TBRL",0,"C",1);
          $pdf->cell(30,$alt,"Estoque Atual",         "TBRL",0,"C",1);
          $pdf->cell(30,$alt,"Ponto de Pedido",       "TBRL",0,"C",1);
          $pdf->cell(30,$alt,"Diferenca",             "TBRL",1,"C",1);

          $pdf->setfont("arial", "", 8);

          $imp   = 0;
          $troca = 0;
          $p     = 0;
     }

     if ($codigo_ant != $m91_codigo){
          if ($codigo_ant > 0){
               if ($p == 1){
                    $p = 0;
               } else {
                    $p = 1;
               }

               $pdf->setfont("arial", "b", 8);
               $pdf->ln();
               $pdf->cell(110, $alt, "SUBTOTAL:",                      "TB", 0, "R", $p);
               $pdf->cell(30, $alt, db_formatar($total_estoque,"f"),   "TB", 0, "R", $p);
               $pdf->cell(30, $alt, db_formatar($total_pedido,"f"),    "TB", 0, "R", $p);
               $pdf->cell(30, $alt, db_formatar($total_diferenca,"f"), "TB", 1, "R", $p);
               $pdf->setfont("arial", "", 8);

               $total_estoque   = 0;
               $total_pedido    = 0;
               $total_diferenca = 0;

		           $pdf->setfont("arial", "b", 8);
          }
     }

     if ($codigo_ant != $m91_codigo){
          $pdf->setfont("arial", "b", 8);
          $pdf->cell(170, ($alt+1), "Deposito: ".$coddepto." - ".$descrdepto, 0, 1, "L", $p);
          $pdf->setfont("arial", "", 8);
          
          $codigo_ant = $m91_codigo;
     }

     if ($p == 1){
          $p = 0;
     } else {
          $p = 1;
     }

     $pdf->setfont("arial", "", 8);

     $pdf->cell(20,$alt, $m64_matmater,                     0,0,"C",$p);
     $pdf->cell(90,$alt, $m60_descr,                       0,0,"L",$p);
     $pdf->cell(30,$alt, db_formatar($m70_quant,"f"),       0,0,"R",$p);
     $pdf->cell(30,$alt, db_formatar($m64_pontopedido,"f"), 0,0,"R",$p);
     $pdf->cell(30,$alt, db_formatar($diferenca,"f"),       0,1,"R",$p);

     $total_estoque   += $m70_quant;
     $total_pedido    += $m64_pontopedido;
     $total_diferenca += $diferenca;

     $total_geral_estoque   += $m70_quant;
     $total_geral_pedido    += $m64_pontopedido;
     $total_geral_diferenca += $diferenca;
     
     $total_registros++;
}

if ($total_estoque > 0 || $total_pedido > 0){
     $pdf->ln();
     $pdf->setfont("arial", "b", 8);
     $pdf->cell(110, $alt, "SUBTOTAL:",                      "TB", 0, "R", $p);
     $pdf->cell(30, $alt, db_formatar($total_estoque,"f"),   "TB", 0, "R", $p);
     $pdf->cell(30, $alt, db_formatar($total_pedido,"f"),    "TB", 0, "R", $p);
     $pdf->cell(30, $alt, db_formatar($total_diferenca,"f"), "TB", 1, "R", $p);

     $total_estoque   = 0;
     $total_pedido    = 0;
     $total_diferenca = 0;
}

$pdf->setfont("arial", "b", 8);
$pdf->cell(110, ($alt+1), "TOTAL GERAL:",                         "TB", 0, "R", 0);
$pdf->cell(30, ($alt+1), db_formatar($total_geral_estoque,"f"),   "TB", 0, "R", 0);
$pdf->cell(30, ($alt+1), db_formatar($total_geral_pedido,"f"),    "TB", 0, "R", 0);
$pdf->cell(30, ($alt+1), db_formatar($total_geral_diferenca,"f"), "TB", 1, "R", 0);

$pdf->ln();
$pdf->cell(170, $alt, "TOTAL DE REGISTROS: ".$total_registros, 0, 1, "L", 0);

$pdf->Output();
?>