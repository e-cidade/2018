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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("classes/db_matestoque_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatestoque = new cl_matestoque;

$dbwhere = " m70_coddepto = ".db_getsession("DB_coddepto");
if ($listasubgrupo != "") {
     $dbwhere .= " and pc04_codsubgrupo in ($listasubgrupo)";
}

$head3  = "Relatório de Estoque por Grupo/Subgrupo";
$anousu = db_getsession("DB_anousu");
$instit = db_getsession("DB_instit");

$sql    = "select pc03_codgrupo,
                  pc03_descrgrupo,
	          pc04_codsubgrupo,
	          pc04_descrsubgrupo,
	          sum(m70_quant) as m70_quant,
		  sum(m70_valor) as m70_valor,
		  sum(m70_valor/case when m70_quant < 1 then 1 else m70_quant end) as valor_unitario_medio
           from pcgrupo
	        inner join pcsubgrupo    on pc04_codgrupo    = pc03_codgrupo  
                inner join pcmater       on pc01_codsubgrupo = pc04_codsubgrupo
	        inner join (select distinct on (m63_codmatmater) * from transmater order by m63_codmatmater) as x on
		            x.m63_codpcmater = pc01_codmater
                inner join matmater      on m60_codmater     = x.m63_codmatmater
		                        and m60_ativo        is true
		inner join matunid       on m61_codmatunid   = m60_codmatunid
                inner join matestoque    on m70_codmatmater  = m60_codmater
		inner join db_depart     on coddepto         = m70_coddepto
		inner join db_departorg  on db01_coddepto    = coddepto 
		                        and db01_anousu      = $anousu
                inner join orcorgao      on o40_anousu       = $anousu  
		                        and o40_orgao        = db01_orgao 
					and o40_instit       = $instit 
	   where $dbwhere and m70_quant <> 0
	   group by pc03_codgrupo,
	            pc03_descrgrupo,
	            pc04_codsubgrupo,
	            pc04_descrsubgrupo
	   order by pc03_codgrupo,
	            pc04_codsubgrupo";
	    
$result  = $clmatestoque->sql_record($sql);
$numrows = $clmatestoque->numrows;

if ($numrows == 0) {
     db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$pdf->AddPage();
$alt   = 4;
$imp   = 1;
$troca = 1;
$borda = 0;
$p     = 0;
$col   = 60;
$total_quant    = 0;
$valor_totest   = 0;
$valor_totmedio = 0;
$grupo_ant      = "";

for ($i = 0; $i < $numrows; $i++) {
      db_fieldsmemory($result,$i);

      if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp==1) {
           if ($imp==0) {
                $pdf->addpage();
           }

	   $pdf->cell($col+15, $alt, "Grupo/SubGrupo", 1, 0, "C", 1);
	   $pdf->cell(30, $alt, "Quantidade", 1, 0, "C", 1);
	   $pdf->cell(40, $alt, "Valor Estoque - R$", 1, 0, "C", 1);
	   $pdf->cell(40, $alt, "Valor Unitário Médio - R$", 1, 1, "C", 1);
	   $troca = 0;
	   $imp   = 0;
	   $p     = 0;
      }		

      if ($grupo_ant != $pc03_codgrupo){
	   if ($grupo_ant != ""){
                $pdf->cell(165, $alt, " ", $borda, 1, "L", 0);
	   }	
           $pdf->setfont("Arial", "b", 8);
           $pdf->cell(165, $alt, $pc03_codgrupo." ".$pc03_descrgrupo, $borda, 1, "L", $p);
           $pdf->setfont("Arial", "", 8);

           if ($p == 0) {
                $p = 1;
           } else {
                $p = 0;
           }

           $grupo_ant = $pc03_codgrupo;
      }

      $x = $pdf->getX();
      $x += 15;
      $pdf->setX($x);
      $pdf->cell($col+15, $alt, $pc04_codsubgrupo." ".trim($pc04_descrsubgrupo), $borda, 0, "L", $p);
      $x = $pdf->getX();
      $x -= 15;
      $pdf->setX($x);
      $pdf->cell(30, $alt, db_formatar($m70_quant,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($m70_valor,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($valor_unitario_medio,"f"," ",0,"e",4), $borda, 1, "R", $p);

      if ($p == 0) {
           $p = 1;
      } else {
           $p = 0;
      }

      $total_quant    += $m70_quant;
      $valor_totest   += $m70_valor;
      $valor_totmedio += $valor_unitario_medio;
}

$pdf->setfont("Arial", "b", 8);
$pdf->cell(105, $alt+4, "TOTAL POR GRUPO/SUBGRUPO: ".db_formatar($total_quant,"f"),  "T", 0, "R", 0);
$pdf->cell(40,  $alt+4, db_formatar($valor_totest,"f"), "T", 0, "R", 0);
$pdf->cell(40,  $alt+4, db_formatar($valor_totmedio,"f"," ",0,"e",4), "T", 1, "R", 0);

$total_geral_quant = $total_quant;
$valor_geral_est   = $valor_totest;
$valor_geral_medio = $valor_totmedio;

$total_quant       = 0;
$valor_totest      = 0;
$valor_totmedio    = 0;

$imp   = 1;
$troca = 1;

$sql_mat = "select m60_codmater,
                   m60_descr,
                   m70_quant,
                   m70_valor,
                   (m70_valor/case when m70_quant < 1 then 1 else m70_quant end) as valor_unitario_medio
              from matestoque
             inner join matmater      on matmater.m60_codmater      = matestoque.m70_codmatmater
             left  join transmater    on transmater.m63_codmatmater = matestoque.m70_codmatmater
             inner join db_depart     on coddepto                   = m70_coddepto
             inner join pcmater       on m63_codpcmater             = pc01_codmater 
             inner join pcsubgrupo    on pc01_codsubgrupo           = pc04_codsubgrupo
             where m70_quant <> 0 and instit = $instit
               and $dbwhere 
               and m60_ativo is true
             order by m60_descr";

$result  = $clmatestoque->sql_record($sql_mat);
$numrows = $clmatestoque->numrows;

for($i = 0; $i < $numrows; $i++){
      db_fieldsmemory($result,$i);
      
      if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp==1) {
           if ($imp==0) {
                $pdf->Addpage();
           }

           $pdf->setfont("Arial", "b", 8);
     $pdf->cell(75, $alt, "Descrição", 1, 0, "C", 1);
     $pdf->cell(30, $alt, "Quantidade", 1, 0, "C", 1);
     $pdf->cell(40, $alt, "Valor Estoque - R$", 1, 0, "C", 1);
     $pdf->cell(40, $alt, "Valor Unitário Médio - R$", 1, 1, "C", 1);
           $pdf->setfont("Arial", "", 8);

     $troca = 0;
     $imp   = 0;
     $p     = 0;
      }   
      
      $pdf->setfont("Arial", "b", 8);
      $pdf->cell(75, $alt, $m60_codmater. " ".$m60_descr, $borda, 0, "L", $p);
      $pdf->setfont("Arial", "", 8);

      $pdf->cell(30, $alt, db_formatar($m70_quant,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($m70_valor,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($valor_unitario_medio,"f"," ",0,"e",4), $borda, 1, "R", $p);

      if ($p == 0) {
           $p = 1;
      } else {
           $p = 0;
      }

      $total_quant    += $m70_quant;
      $valor_totest   += $m70_valor;
      $valor_totmedio += $valor_unitario_medio;
}

$pdf->setfont("Arial", "b", 8);
$pdf->cell(105, $alt+2, "TOTAL DE ITENS: ".db_formatar($total_quant,"f"),  "T", 0, "R", 0);
$pdf->cell(40,  $alt+2, db_formatar($valor_totest,"f"), "T", 0, "R", 0);
$pdf->cell(40,  $alt+2, db_formatar($valor_totmedio,"f"," ",0,"e",4), "T", 1, "R", 0);

$pdf->AddPage();
$pdf->setfont("Arial", "b", 8);
$total_quant_semvinc    = 0 ;
$valor_totest_semvinc   = 0 ;
$valor_totmedio_semvinc = 0 ;
$pdf->cell(185, $alt+2, "ITENS SEM VINCULO COM COMPRAS ",  1, 1, "C", 1);

$sql_matsemvinc = "select distinct m60_codmater,
                          m60_descr,
                          m70_quant,
		          m70_valor,
		          (m70_valor/case when m70_quant < 1 then 1 else m70_quant end) as valor_unitario_medio
		   from matestoque
		        inner join matmater      on matmater.m60_codmater = matestoque.m70_codmatmater
			      left  join transmater    on transmater.m63_codmatmater = matestoque.m70_codmatmater
   	        inner join db_depart     on coddepto         = m70_coddepto
		   where m70_quant <> 0 and instit = $instit
		     nad m70_coddepto = ".db_getsession("DB_coddepto")." 
		     and m63_codmatmater is null
		     and m60_ativo is true
		   order by m60_descr";

$result  = $clmatestoque->sql_record($sql_matsemvinc);
$numrows = $clmatestoque->numrows;

for($i = 0; $i < $numrows; $i++){
      db_fieldsmemory($result,$i);
      
      if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $imp==1) {
           if ($imp==0) {
                $pdf->Addpage();
           }

           $pdf->setfont("Arial", "b", 8);
	   $pdf->cell(75, $alt, "Descrição", 1, 0, "C", 1);
	   $pdf->cell(30, $alt, "Quantidade", 1, 0, "C", 1);
	   $pdf->cell(40, $alt, "Valor Estoque - R$", 1, 0, "C", 1);
	   $pdf->cell(40, $alt, "Valor Unitário Médio - R$", 1, 1, "C", 1);
           $pdf->setfont("Arial", "", 8);

	   $troca = 0;
	   $imp   = 0;
	   $p     = 0;
      }		
      
      $pdf->setfont("Arial", "b", 8);
      $pdf->cell(75, $alt, $m60_codmater. " ".$m60_descr, $borda, 0, "L", $p);
      $pdf->setfont("Arial", "", 8);

      $pdf->cell(30, $alt, db_formatar($m70_quant,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($m70_valor,"f"), $borda, 0, "R", $p);
      $pdf->cell(40, $alt, db_formatar($valor_unitario_medio,"f"," ",0,"e",4), $borda, 1, "R", $p);

      if ($p == 0) {
           $p = 1;
      } else {
           $p = 0;
      }

      $total_quant_semvinc    += $m70_quant;
      $valor_totest_semvinc   += $m70_valor;
      $valor_totmedio_semvinc += $valor_unitario_medio;
}

$pdf->setfont("Arial", "b", 8);
$pdf->cell(105, $alt+2, "TOTAL DE ITENS SEM VINCULO COM COMPRAS: ".db_formatar($total_quant_semvinc,"f"),  "T", 0, "R", 0);
$pdf->cell(40,  $alt+2, db_formatar($valor_totest_semvinc,"f"), "T", 0, "R", 0);
$pdf->cell(40,  $alt+2, db_formatar($valor_totmedio_semvinc,"f"," ",0,"e",4), "T", 1, "R", 0);

$pdf->Output();

?>