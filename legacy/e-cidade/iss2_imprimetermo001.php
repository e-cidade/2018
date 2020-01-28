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

include("libs/db_sql.php");
include("fpdf151/pdf1.php");
include("classes/db_varfix_classe.php");
include("classes/db_varfixval_classe.php");
include("classes/db_db_docparag_classe.php");

$cldb_docparag = new cl_db_docparag;
$clvarfix      = new cl_varfix;
$clvarfixval   = new cl_varfixval;

$clrotulo = new rotulocampo;
$clrotulo->label('y30_codnoti');
$clrotulo->label('y30_nome');

//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

//-----------------------BUSCA AS INFORMAÇÕES DO TERMO--------------------------

//die($clvarfix->sql_query_termo(null,"z01_nome,q33_data,j14_nome,q02_numero,q02_compl,j13_descr,issbase.q02_inscr,q34_valor,q34_inflat,q33_obs",null," issbase.q02_inscr = $inscr"));


$result = $clvarfix->sql_record($clvarfix->sql_query_termo(null,"z01_nome,z01_cgccpf,q33_data,q33_hora,j14_nome,q02_numero,q02_compl,j13_descr,issbase.q02_inscr,q33_codigo,q36_processo,q34_valor,q33_tiporeg,q34_inflat,q81_valexe,q33_obs",null," issbase.q02_inscr = $inscr"));

//db_criatabela($result);exit;


if ($clvarfix->numrows > 0){
	db_fieldsmemory($result,0);
}else{
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');

}
$impostomensal=($q34_valor*$q81_valexe)/100;
$impostomensal=trim(db_formatar($impostomensal,'f'));

db_sel_instit( null,"munic");

$q33_data_dia=db_subdata($q33_data,'d');
$q33_data_mes=db_subdata($q33_data,'m');
$q33_data_mes=db_mes($q33_data_mes,0);
$q33_data_ano=db_subdata($q33_data,'a');
$data=$q33_data_dia. " de " .$q33_data_mes. " de " .$q33_data_ano;

 if ($q33_tiporeg=='a'){
$tipotermo='ARBITRAMENTO';

 }
 else {
   $tipotermo='ESTIMATIVA';
}

if ($q02_compl!=""){
$txt="/ ";
$virgula=",";
$q02_compl=$txt.$q02_compl.$virgula;
}
//--------------------- DISPONIBILIZA AS VARIAVEiIS PARA SER USADA NOS PARAGRAFOS COM OS NOMES ABAIXO----


$munic         = $numic;
$tipotermo     = $tipotermo;
$numtermo      = $q33_codigo;
$data          = $data;
$nome          = $z01_nome;
$rua           = $j14_nome;
$numresidencia = $q02_numero;
$complement    = $q02_compl;
$bairo         = $j13_descr;
$inscr         = $q02_inscr;
$cgc_cpf       = $z01_cgccpf;
$processo      = $q36_processo;
$notificacao   = "";
$fatmensal     = trim(db_formatar($q34_valor,'f'));
$impmensal     = $impostomensal;
$inflator      = $q34_inflat;
$hora          = $q33_hora;
$obs           = $q33_obs;



//---------------------------------------------------------------------------------------

//-----------------------BUSCA --------------------------
/*
$result_fiscais=$clfiscalusuario->sql_record($clfiscalusuario->sql_query($codfiscal,null,"nome as nome_fiscal"));
for($w=0;$w<$clfiscalusuario->numrows;$w++){
	db_fieldsmemory($result_fiscais,$w);
	$fiscal .= $vir.$nome_fiscal;
	$vir = ", ";	
}
*/
//---------------------------------------------------------------------------------------

/*$sqlhead = "select db02_texto
			   from db_documento
			    	inner join db_docparag on db03_docum = db04_docum
        			inner join db_tipodoc on db08_codigo  = db03_tipodoc
		     		inner join db_paragrafo on db04_idparag = db02_idparag
			 where db03_tipodoc = 8 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";			 
$reshead = pg_query($sqlhead);
*/

$head1 = 'SECRETARIA DA FAZENDA';

$pdf = new PDF1(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->setfont('arial','b',8);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->rect(2,2, 206, 285, 2, 'DF');
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query("","","db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem","db03_tipodoc=8"));
$numrows = $cldb_docparag->numrows;
if ($numrows==0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe documento cadastrado.');
	exit;
}
$pdf->SetXY('10','60');
$pdf->SetFont('Arial','b',14);
$pdf->cell(0,15,"$tipotermo N° $numtermo",0,1,"C",0);
$pdf->cell(0,8,"",0,1,"R",0);
$bord=0;
for($i=0; $i<$numrows; $i++){
   db_fieldsmemory($result,$i);
   $pdf->SetFont('Arial','',12);
   $pdf->SetX($db02_alinha);
   $texto=db_geratexto($db02_texto);
   $pdf->SetFont('Arial','',12);
   $pdf->MultiCell("0",4+$db02_espaca,$texto,$bord,"J",0,$db02_inicia+0);
   //$pdf->MultiCell("0",6+$db02_espaca,$texto,$bord,"J",0,$db02_inicia+0);
   $pdf->cell(0,4,"",0,1,"R",0);
}
$pdf->cell(0,5,"",0,1,"R",0);
$pdf->SetFont('Arial','b',10);
//$pdf->cell(180,20,"$munic,$data",0,150,"R",0);
//$pdf->cell(180,5,"_________________________",0,10,"R",0);
//$pdf->cell(170,5,"AGENTE FISCAL",0,10,"R",0);
//$pdf->cell(90,4,"Notificado",0,1,"C",0);
//$pdf->cell(0,10,"",0,1,"R",0);
//$pdf->cell(0,4,"Data:___/___/_____ ",0,1,"R",0);
$pdf->Output();
?>