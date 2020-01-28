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
include ("fpdf151/assinatura.php");
include ("libs/db_sql.php");
include ("libs/db_libcontabilidade.php");
include ("libs/db_liborcamento.php");
include ("classes/db_orcparamrel_classe.php");
include ("classes/db_orcparamseq_classe.php");
include ("classes/db_orcparamelemento_classe.php");
include("libs/db_libtxt.php");
include("dbforms/db_funcoes.php");

$clorcparamrel = new cl_orcparamrel;
$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_SERVER_VARS);
$anousu   = db_getsession("DB_anousu");

$campos   = "*";
$campos_elem = "o44_sequencia, o69_descr, c60_estrut, c60_descr";
$sqlrelat = $clorcparamrel->sql_query($c69_codseq, $campos);
$sqlelem  = $clorcparamelemento->sql_query_estrutural ($anousu,$c69_codseq,null,null,db_getsession("DB_instit")." group by $campos_elem, o69_codseq ",$campos_elem,"o69_codseq");

$result1  = pg_exec($sqlrelat);
$result2  = pg_exec($sqlelem);

if(pg_numrows($result1)==0||pg_numrows($result2)==0) {
	db_msgbox("Não existem parâmetros cadastrados para este exercício!");
	echo "<script>window.close()</script>";
	exit(1);
}
//db_criatabela($result2); exit;
//db_criatabela($result1); exit;

db_fieldsmemory($result1,0);
db_fieldsmemory($result2,0);

$head3 = "RELATÓRIO DE ";
$head4 = "CONFIGURAÇÃO PARAMETROS";
$head5 = $o42_descrrel;

$somaemp = 0;
$somaliq = 0;
$somapag = 0;
$somaanu = 0;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$alt = 4;
$pagina = 1;
$pdf->addpage();

$pdf->setfont('arial', 'b', 6);
$pdf->setX(15);
$pdf->cell(25, $alt, "Sequencia", '', 0, "C", 0);
$pdf->cell(150, $alt, " Descrição", '',1, "C", 0);
$pdf->setfont('arial', '', 6);

for($i=0;$i< pg_numrows($result2);$i++) {
 db_fieldsmemory($result2,$i);
 if (!isset($seq) or ($seq != $o44_sequencia)){
 	$pdf->setfont('arial', 'b', 6);
 	$pdf->setX(15);
  	$pdf->cell(25, $alt,$o44_sequencia, 'TB', 0, "C", 0);
  	$pdf->cell(150, $alt,$o69_descr, 'TB', 1, "C", 0);
  	$pdf->setfont('arial', '', 6);
  	$seq = $o44_sequencia;
 } 
 $pdf->setX(30);
 $pdf->cell(25, $alt,$c60_estrut, '', 0, "C", 0);
 $pdf->cell(100, $alt,$c60_descr, '', 1, "L", 0);
}


	
/*
for($i=0;$i< pg_numrows($result);$i++) {
	db_fieldsmemory($result,$i);    
	$pdf->cell(15, $alt,$e60_numemp, 'R', 0, "R", 0);
	$pdf->cell(15, $alt,$e60_codemp, 'R', 0, "R", 0);
	$pdf->cell(15, $alt,$e60_emiss, 'R', 0, "C", 0);
	$pdf->cell(15, $alt,$e60_vencim, 'R', 0, "C", 0);
	$pdf->cell(43, $alt,substr($z01_nome,0,30), 'R', 0, "L", 0);
	$pdf->cell(22, $alt,db_formatar($e60_vlremp,'f'), 'R', 0, "R", 0);
	$pdf->cell(22, $alt,db_formatar($e60_vlrliq,'f'), 'R', 0, "R", 0);
	$pdf->cell(22, $alt,db_formatar($e60_vlrpag,'f'), 'R', 0, "R", 0);
	$pdf->cell(22, $alt,db_formatar($e60_vlranu,'f'), '', 1, "R", 0);
    $somaemp += $e60_vlremp;
	$somaliq    += $e60_vlrliq;
	$somapag += $e60_vlrpag;
	$somaanu += $e60_vlranu;
}
$pdf->setfont('arial', 'b', 6);
$pdf->cell(103, $alt,"Total : ".$i, 'TBR', 0, "C", 0);
$pdf->cell(22, $alt,db_formatar($somaemp,'f'), 'TBR', 0, "R", 0);
$pdf->cell(22, $alt,db_formatar($somaliq,'f'), 'TBR', 0, "R", 0);
$pdf->cell(22, $alt,db_formatar($somapag,'f'), 'TBR', 0, "R", 0);
$pdf->cell(22, $alt,db_formatar($somaanu,'f'), 'TB', 1, "R", 0);

*/


$pdf->Output();
?>