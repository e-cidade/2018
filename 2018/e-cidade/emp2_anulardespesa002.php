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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("libs/db_sql.php");

$classinatura = new cl_assinatura;

db_postmemory($HTTP_POST_VARS);

// Parametros
//e60_numemp         integer  NUmero do empenho 
//valor_anular       float8   Valor do Anular
//historico anular   text     Historico

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
db_fieldsmemory($resultpref,0);

if(isset($e60_numemp) && $e60_numemp != ''){
   $dbwhere =" e60_numemp = $e60_numemp ";
} else if (isset($e60_codemp) && $e60_codemp !=''){
	      $arr = split("/",$e60_codemp);
	      if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
		$dbwhere_ano = " and e60_anousu = ".$arr[1];
       	      }else{
		$dbwhere_ano = " and e60_anousu = ".db_getsession("DB_anousu");
	      }
      $dbwhere = "e60_codemp='".$arr[0]."'$dbwhere_ano";
	      
}
$sqlemp = "
	select empempenho.*,
	       cgm.* 
	from empempenho 
	     inner join cgm 		on z01_numcgm = e60_numcgm
	where  $dbwhere 
	";


 //echo "<br>".$sqlemp; exit;

$result = pg_exec($sqlemp);	
// db_criatabela($result);exit;

if (pg_numrows($result)==0){
   db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado !  ");
   exit;
}
db_fieldsmemory($result,0);


$pdf = new scpdf();
$pdf->Open();

//// modelo 43 - ANULAR DE DESPESA

$pdf1 = new db_impcarne($pdf,'43');
//$pdf1->modelo = 6;
$pdf1->objpdf->SetTextColor(0,0,0);

$pdf1->prefeitura       = $nomeinst;
$pdf1->logo			        = $logo;
$pdf1->cnpj             = $z01_cgccpf;
$pdf1->numcgm           = $z01_numcgm;
$pdf1->nome             = $z01_nome;
$pdf1->numemp           = $e60_numemp;
$pdf1->ano              = $e60_anousu;
$pdf1->codemp           = $e60_codemp;
$pdf1->empenhado        = $valor_anular;
$pdf1->historico        = "$historico_anular";
$pdf1->municpref        = $munic;


$cont = 'CONTADOR';
$pref = 'PREFEITO MUNICIPAL';
$sec  = 'SECRETARIO DA FAZENDA';
$tes  = 'TESOUREIRO';

$ass_pref = $classinatura->assinatura(9999, $pref);
$ass_sec  = $classinatura->assinatura(9999, $sec);
$ass_usu  = $classinatura->assinatura_usuario();
$ass_tes  = $classinatura->assinatura(9999, $tes);
$ass_cont = $classinatura->assinatura(9999, $cont);

$pdf1->assinatura1      = $ass_usu;
$pdf1->assinatura2      = $ass_cont;
$pdf1->assinatura3      = $ass_sec;
$pdf1->assinatura4      = $ass_pref;

$pdf1->imprime();
//include("fpdf151/geraarquivo.php");
$pdf1->objpdf->Output();

   
?>