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
$clrotulo = new rotulocampo;
$clrotulo->label('rh61_regist');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$orderby = " z01_nome ";
$ordenacao = "Alfabética";
if($ordem == "n"){
  $ordenacao = "Numérico";
  $orderby = " rh01_regist ";
}

//// Variáveis do cabeçalho
//// $head1 = linha 1
//// $head2 = linha 2
///  ...
//// $head9 = linha 9

$head3 = "RELATÓRIO DE FÉRIAS";
$head4 = "ORDEM: ".$ordenacao;

////////////////////////////////////////////////

//// Cria a variável $sql com o sql criado
$sql = "select r30_regist, 
               z01_nome, 
	       r30_perai,
	       r30_peraf,
	       r30_per1i,
	       r30_per1f,
	       r30_paga13 
	       from cadferia 
	       inner join rhpessoal on rh01_regist = r30_regist 
	       inner join  cgm on z01_numcgm = rh01_numcgm 
	       where r30_anousu = $ano 
	         and r30_mesusu = $mes
					 and r30_instit = ".db_getsession("DB_instit")."
		  and r30_proc1 = '$ano/$mes' 
		  order by".$orderby;

// die($sql);
//// pg_exec - executa $sql no banco e gera um RECORDSET criado na variável $resultado_sql com os dados da execução
//// da variável $sql no banco
$resultado_sql = pg_exec($sql);
//// pg_numrows - verifica quantas linhas vieram no RECORDSET e coloca o resultado na variávei $qtd_linhas_sql
$qtd_linhas_sql = pg_numrows($resultado_sql);
if($qtd_linhas_sql == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período.');
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
     $pdf->addpage('L');
     $pdf->setfont('arial','b',7);

     $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
     $pdf->cell(65,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
     $pdf->cell(35,$alt,'PER. AQUISITIVO INICIAL',1,0,"C",1);
     $pdf->cell(35,$alt,'PER. AQUISITIVO FINAL',1,0,"C",1);
     $pdf->cell(35,$alt,'INICIO DO GOZO',1,0,"C",1);
     $pdf->cell(35,$alt,'FINAL DO GOZO',1,0,"C",1);
     $pdf->cell(25,$alt,'1/3 FÉRIAS',1,1,"C",1);
     $pre = 1;

     $imprime_cabecalho = false;
  }
  if ($pre == 1)
    $pre = 0;
  else
    $pre = 1;
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$r30_regist,0,0,"C",$pre);
  $pdf->cell(65,$alt,$z01_nome,0,0,"L",$pre);
  $pdf->cell(35,$alt,db_formatar($r30_perai,'d'),0,0,"C",$pre);
  $pdf->cell(35,$alt,db_formatar($r30_peraf,'d'),0,0,"C",$pre);
  $pdf->cell(35,$alt,db_formatar($r30_per1i,'d'),0,0,"C",$pre);
  $pdf->cell(35,$alt,db_formatar($r30_per1f,'d'),0,0,"C",$pre);  
  $pdf->cell(25,$alt,$r30_paga13,0,1,"C",$pre);
   

}

$pdf->setfont('arial','b',8);
$pdf->cell(250,$alt,'TOTAL DE REGISTROS  : '.$qtd_linhas_sql,"T",1,"C",0);
$pdf->Output();
?>