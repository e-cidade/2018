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
include ("libs/db_liborcamento.php");
include ("libs/db_sql.php");

$tipo_mesini = 1;
$tipo_mesfim = 1;
$anousu = db_getsession("DB_anousu");

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}
$exercicios = "";
$sp = "";
for ($x = $anousu -3; $x < $anousu; $x ++) {
	$exercicios .= $sp.$x;
	$sp = ",";
}
/*
 *  o select abaixo traz a execução dos exercicios anteriores,
 *  e para o exercicio atual traz a previsao inicial da despesa
 *  
 * 
 */

$sql = "
            (select
                 o70_anousu,
                 fonte.o57_codfon,
                 fonte.o57_fonte,
                 fonte.o57_descr,
                 sum(o71_valor) as o71_valor
             from (
                  select o70_anousu,
                             o70_codrec,
                             substr(o57_fonte,1,7) as fonte,
                             orc.o57_fonte,
                             orc.o57_descr,
                             sum(coalesce(o71_valor,0)) as o71_valor
                   from orcreceita
                           inner join orcfontes orc on orc.o57_codfon=o70_codfon and o57_anousu = o70_anousu
                           left join orcreceitaval on o71_codrec = orcreceita.o70_codrec
                                          and o71_anousu = orcreceita.o70_anousu
                    WHERE O70_ANOUSU  IN ( $exercicios)
                    group by o70_anousu,o70_codrec,o57_fonte,o57_descr
                    order by o70_codrec,o70_anousu
                  ) as x
                      inner join orcfontes fonte  on fonte.o57_fonte   = fonte||'00000000' and o57_anousu = o70_anousu
                 group by o70_anousu, fonte.o57_codfon, fonte.o57_fonte,fonte.o57_descr             
              ) 
             union         
            ( select
                 o70_anousu,
                 fonte.o57_codfon,
                 fonte.o57_fonte,
                 fonte.o57_descr,
                 sum(o71_valor) as o71_valor
             from (
                  select o70_anousu,
                             o70_codrec,
                             substr(o57_fonte,1,7) as fonte,
                             orc.o57_fonte,
                             orc.o57_descr,
                             sum(coalesce(o70_valor,0)) as o71_valor
                   from orcreceita
                           inner join orcfontes orc on orc.o57_codfon=o70_codfon  and o57_anousu = o70_anousu                    
                    WHERE O70_ANOUSU  = $anousu 
                    group by o70_anousu,o70_codrec,o57_fonte,o57_descr
                    order by o70_codrec,o70_anousu
                  ) as x
                      inner join orcfontes fonte  on fonte.o57_fonte   = fonte||'00000000' and o57_anousu = o70_anousu
                 group by o70_anousu, fonte.o57_codfon, fonte.o57_fonte,fonte.o57_descr             
              )
             order by o57_codfon              
            ";               
$result = pg_exec($sql);
 // db_criatabela($result);
 // exit;

$head2 = "ANEXO I - RECEITA ARRECADADA";
$head4 = "EXERCICIO: ".db_getsession("DB_anousu");
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
$alt = 4;
$total = 0;
$tota2 = 0;
$tota3 = 0;

$pagina = 1;
$codigo = "";
$total_exercicio = 0;
$total_ant_1 = 0;
$total_ant_2 = 0;
$total_ant_3 = 0;
for ($i = 0; $i < pg_numrows($result); $i ++) {
	db_fieldsmemory($result, $i);

	if ($pdf->gety() > $pdf->h - 30 || $pagina == 1) {
		$pagina = 0;
		$pdf->addpage();
		$pdf->setfont('arial', 'b', 7);
      
		$pdf->cell(100, $alt, "FONTE", 0, 0, "L", 0);
		$pdf->cell(20, $alt,"Executada ".($anousu-3), 0, 0, "R", 0);
		$pdf->cell(20, $alt,"Executada ".($anousu-2), 0, 0, "R", 0);
		$pdf->cell(20, $alt,"Executada ".($anousu-1), 0, 0, "R", 0);      
        $pdf->cell(20, $alt,"Projeção ".$anousu, 1, 0, "R", 0);
        $pdf->Ln();
	    		
	}
    if ($codigo != $o57_codfon){
          $codigo = $o57_codfon;
          $pdf->Ln();
          $pdf->cell(100, $alt,"$o57_descr", 0, 0, "L", 0);    
    }
    $pdf->cell(20, $alt,db_formatar($o71_valor,'f'), 0, 0, "R", 0);
	
	if ($o70_anousu == $anousu)
       $total_exercicio += $o71_valor;
    else if ($o70_anousu == ($anousu -1))
       $total_ant_1 += $o71_valor;
    else if ($o70_anousu == ($anousu -2))
       $total_ant_2 += $o71_valor;
     else if ($o70_anousu == ($anousu -3))
       $total_ant_3 += $o71_valor;
            
}
// imprime totais
$pdf->Ln();
$pdf->cell(100, $alt,"TOTAL", 0, 0, "L", 0);
$pdf->cell(20, $alt,db_formatar($total_ant_3,'f'), 'T', 0, "R", 0);
$pdf->cell(20, $alt,db_formatar($total_ant_2,'f'), 'T', 0, "R", 0);
$pdf->cell(20, $alt,db_formatar($total_ant_1,'f'), 'T', 0, "R", 0);
$pdf->cell(20, $alt,db_formatar($total_exercicio,'f'), 'T', 0, "R", 0);

$pdf->Output();

?>