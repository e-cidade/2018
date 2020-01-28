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

include("libs/db_sql.php");
include("fpdf151/pdf1.php");
include("classes/db_fiscal_classe.php");
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fiscalusuario_classe.php");
include("classes/db_fiscalocal_classe.php");
include("classes/db_fiscalmatric_classe.php");
include("classes/db_db_docparag_classe.php");
$cldb_docparag   = new cl_db_docparag;
$clfiscal        = new cl_fiscal;
$clfiscaltipo    = new cl_fiscaltipo;
$clfiscalusuario = new cl_fiscalusuario;
$clfiscalocal    = new cl_fiscalocal;
$clfiscalmatric  = new cl_fiscalmatric;
$clrotulo        = new rotulocampo;
$clrotulo->label('y30_codnoti');
$clrotulo->label('y30_nome');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
//-----------------------BUSCA AS INFORMAÇÕES DA NOTIFICAÇÃO--------------------------
$result = $clfiscal->sql_record($clfiscal->sql_query_info($codfiscal,"*"," y30_codnoti = $codfiscal and y30_instit = ".db_getsession('DB_instit') ));
if ($clfiscal->numrows>0){
	db_fieldsmemory($result,0,true);
}else{
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
	exit;
}
//------------------------E DISPONIBILIZA AS VARIAVESI PARA SER USADA NOS PARAGRAFOS COM OS NOMES ABAIXO----
$identificacao = (trim($z01_nomecomple) != ''?$z01_nomecomple:$z01_nome);
$cpf           = $z01_cgccpf;
$hora          = $y30_hora;
$arr_data      = split("/",$y30_data);
$dia           = $arr_data[0];
$mes           = db_mes($arr_data[1]);
$ano           = $arr_data[2];
$prazorec      = $y30_prazorec;
$prazorec2     = @db_formatar($y30_prazorec,"d");
$observacao    = $y30_obs;
//---------------------------------------------------------------------------------------

//-----------------------BUSCA FISCAIS DA NOTIFICAÇÃO--------------------------
$fiscal="";
$vir = "";
$result_fiscais=$clfiscalusuario->sql_record($clfiscalusuario->sql_query($codfiscal,null,"nome as nome_fiscal"));
for($w=0;$w<$clfiscalusuario->numrows;$w++){
	db_fieldsmemory($result_fiscais,$w,true);
	$fiscal .= $vir.$nome_fiscal;
	$vir = ", ";	
}
//---------------------------------------------------------------------------------------

//-----------------------BUSCA AS PROCEDENCIAS DA NOTIFICAÇÃO--------------------------
//------------------------E DISPONIBILIZA PARA SER USADA NOS PARAGRAFOS COM OS NOMES ABAIXO----
$procedencia = "";
$descrobs = "";
$descrobscomquebra = "";
$vir = "";

$result_proced=$clfiscaltipo->sql_record($clfiscaltipo->sql_query($codfiscal,
                                                                  null,
																  "*",
																  null,
																  " y31_codnoti = ".$codfiscal." and y30_instit = ".db_getsession('DB_instit') ));
for($w=0;$w<$clfiscaltipo->numrows;$w++){
	db_fieldsmemory($result_proced,$w,true);
	$procedencia .= $vir.$y29_descr;
	$descrobs .= $vir.$y29_descr_obs;
	$descrobscomquebra .= $y29_descr_obs . "\n";
	$vir = ", ";
}
//---------------------------------------------------------------------------------------

$rua = "";
$ruacodigo = "";
$bairro = "";
$complemento = "";
$numero = "";

//-----------------------BUSCA ENDEREÇO REGISTRADO DA NOTIFICAÇÃO--------------------------
$result_ender=$clfiscalocal->sql_record($clfiscalocal->sql_query($codfiscal));
if ($clfiscalocal->numrows>0){
	db_fieldsmemory($result_ender,0,true);
	$rua         = $j14_nome;
	$ruacodigo   = $j14_codigo . " - " . $j14_nome;
	$bairro      = $j13_descr;
	$complemento = $y12_compl;
	$numero      = $y12_numero;	
}

$bql   = "";
$setor = "";
$result_sql=$clfiscalmatric->sql_record($clfiscalmatric->sql_query(null,"j34_setor, j34_quadra,j34_lote,j30_descr",null," y35_codnoti= {$codfiscal} and  y30_instit = ".db_getsession('DB_instit') ));
if ($clfiscalmatric->numrows>0){
	db_fieldsmemory($result_sql,0,true);
	$bql   = $j34_setor . "/" . $j34_quadra . "/" . $j34_lote;
	$setor = $j34_setor . " - " . trim($j30_descr);
}


//---------------------------------------------------------------------------------------
$sqlhead = "select db02_texto
			   from db_documento
			    	inner join db_docparag on db03_docum = db04_docum
        			inner join db_tipodoc on db08_codigo  = db03_tipodoc
		     		inner join db_paragrafo on db04_idparag = db02_idparag
			 where db03_tipodoc = 1017 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";			 
$reshead = db_query($sqlhead);

if ( pg_numrows($reshead) == 0 ) {
//     $head1 = 'Departamento de Fazenda';
     $head1 = 'SECRETARIA DE FINANÇAS';
}else{
     db_fieldsmemory( $reshead, 0 , true);
     $head1 = $db02_texto;
}
$pdf = new PDF1(); 
$pdf->Open();
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->setfont('arial','b',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->setmargins(30,30,20);
$pdf->setrightmargin(20);
$result  = $cldb_docparag->sql_record($cldb_docparag->sql_query("","","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem","db03_tipodoc=7"));
$numrows = $cldb_docparag->numrows;
if ($numrows==0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe documento cadastrado.');
	exit;
}
//$pdf->SetXY('20','60');
$pdf->SetFont('Arial','b',14);
$pdf->cell(0,10,"NOTIFICAÇÃO FISCAL N° $codfiscal",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$bord=0;
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i,true);
   $pdf->SetFont('Arial','',12);
//   $pdf->SetX(@$db02_alinha);
   $texto = db_geratexto(@$db02_texto);
   $pdf->SetFont('Arial','',12);
   /*
   if ($i==2){
   	$bord="TRL";
   }else if ($i==3){
   	$bord="RL";
   }else if ($i==4){
   	$bord="BRL";
   }*/
   $pdf->MultiCell(0,6+@$db02_espaca,$texto,$bord,"J",0,$db02_inicia+0);
   $pdf->cell(0,6,"",0,1,"R",0);
}
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(90,4,"___________________________",0,0,"C",0);
$pdf->cell(90,4,"___________________________",0,1,"C",0);
$pdf->cell(90,4,"Fiscal",0,0,"C",0);
$pdf->cell(90,4,"Notificado",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->cell(0,4,"Data:___/___/_____ ",0,1,"R",0);
$pdf->Output();
?>