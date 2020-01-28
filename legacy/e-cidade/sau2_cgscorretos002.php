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

include ("libs/db_sql.php");
include ("fpdf151/pdf.php");
include ("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo ( );
$clrotulo->label ( 'j01_matric' );
$where = "";
if (@$processados == 'proc') {
	$where = " where s127_b_proc is true ";
} else if ($processados == 'nproc') {
	$where = " where s127_b_proc is false ";
}
$sql = "select s127_i_numcgs, z01_v_nome, s127_b_proc, s127_i_codigo from sau_cgscorreto ";
$sql .= "			inner join cgs_und on  cgs_und.z01_i_cgsund = sau_cgscorreto.s127_i_numcgs ";
$sql .= " $where order by $ordem";

//echo $sql;exit;
$result = pg_exec ( $sql );
$head1 = "CGS's CORRETOS";
$pdf = new PDF ( );
$pdf->Open ();
$pdf->AliasNbPages ();
$pdf->addpage ();
$pdf->setfillcolor ( 235 );
$pdf->setfont ( 'arial', 'b', 8 );

$pdf->cell ( 15, 05, "Codigo", 1, 0, "c", 1 );
$pdf->cell ( 15, 05, "Numcgs", 1, 0, "c", 1 );
$pdf->cell ( 140, 05, "Nome", 1, 0, "c", 1 );
$pdf->cell ( 20, 05, "Processado", 1, 1, "c", 1 );

$pdf->setfont ( 'arial', '', 8 );
$total = 0;
for($x = 0; $x < pg_numrows ( $result ); $x ++) {
	db_fieldsmemory ( $result, $x );
	if ($pdf->gety () > $pdf->h - 35) {
		$pdf->addpage ();
		$pdf->setfont ( 'arial', 'b', 8 );
		$pdf->cell ( 15, 05, "Codigo", 1, 0, "c", 1 );
		$pdf->cell ( 15, 05, "Numcgs", 1, 0, "c", 1 );
		$pdf->cell ( 140, 05, "Nome", 1, 0, "c", 1 );
		$pdf->cell ( 20, 05, "Processado", 1, 1, "c", 1 );
		$pdf->setfont ( 'arial', '', 8 );
	}
	$processado = ($s127_b_proc == 't') ? "Sim" : "Nao";
	$pdf->cell ( 15, 5, $s127_i_codigo, 1, 0, "C", 0 );
	$pdf->cell ( 15, 5, $s127_i_numcgs, 1, 0, "C", 0 );
	$pdf->cell ( 140, 5, $z01_v_nome, 1, 0, "L", 0 );
	$pdf->cell ( 20, 5, $processado, 1, 1, "C", 0 );
	$sql = "select s128_i_numcgs, s128_v_nome " . (isset ( $log ) ? ", s129_t_log " : "");
	$sql .= "  from sau_cgserrado ";
	$sql .= "       left  join cgs_und      on s128_i_numcgs = z01_i_cgsund ";
	$sql .= "       inner join sau_cgscorreto   on s128_i_codigo = s127_i_codigo ";
	if (isset ( $log )) {
		$sql .= "       left  join sau_cgserradolog on s129_i_codigo = s128_i_codigo ";
		$sql .= "                              and s129_i_numcgs = s128_i_numcgs ";
	}
	$sql .= " where s128_i_codigo = $s127_i_codigo";
	//die($sql);
	$res = pg_exec ( $sql );
	if (pg_numrows ( $res ) > 0) {
		for($y = 0; $y < pg_numrows ( $res ); $y ++) {
			db_fieldsmemory ( $res, $y );
			$pdf->setX ( 25 );
			$pdf->cell ( 15, 05, $s128_i_numcgs, 1, 0, "C", 1 );
			$pdf->cell ( 160, 05, $s128_v_nome, 1, 1, "L", 1 );
			if (isset ( $log )) {
				if ($s129_t_log != "") {
					$pdf->setX ( 25 );
					$pdf->Multicell ( 175, 5, "<<<< LOG's >>>> \n" . $s129_t_log, 1, 1, "L", 0 );
				}
			}
		}
	}
	$total += 1;
}
$pdf->cell ( 190, 05, 'Total de Registros:   ' . $total, 1, 0, "c", 1 );
$pdf->Output ();
?>