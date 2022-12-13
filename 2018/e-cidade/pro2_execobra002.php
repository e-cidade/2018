<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_sql.php");
require_once("fpdf151/pdf1.php");
require_once("classes/db_obrasalvara_classe.php");
require_once("classes/db_obrasender_classe.php");
require_once("classes/db_obraslote_classe.php");
require_once("classes/db_obraslotei_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrastec_classe.php");
require_once("classes/db_obrastecnicos_classe.php");
require_once("classes/db_obrasconstr_classe.php");

$clobrasalvara   = new cl_obrasalvara;
$clobrasender	   = new cl_obrasender;
$clobraslote	   = new cl_obraslote;
$clobraslotei    = new cl_obraslotei;
$clobras			   = new cl_obras;
$clobrastec      = new cl_obrastec;
$clobrastecnicos = new cl_obrastecnicos;
$clobrasconstr   = new cl_obrasconstr;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(!isset($codigo) || $codigo==''){
  
  $sMsg = _M('tributario.projetos.pro2_execobra002.obra_nao_encontrada');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
}

$borda   = 1; 
$bordat  = 1;
$preenc  = 0;
$TPagina = 57;
$xnumpre = '';


$sCampos  = "cgm.z01_nome,                     ";
$sCampos .= "cgm.z01_cgccpf,                   ";
$sCampos .= "p58_codigo,          ";
$sCampos .= "obrasalvara.ob04_data,            ";
$sCampos .= "obrasalvara.ob04_dtvalidade,      ";
$sCampos .= "obrasalvara.ob04_obsprocesso,     ";
$sCampos .= "obrasiptubase.ob24_iptubase,      ";
$sCampos .= "setorloc.j05_codigoproprio||'-'|| ";
$sCampos .= "setorloc.j05_descr        ||'/'|| ";
$sCampos .= "loteloc.j06_quadraloc     ||'/'|| ";
$sCampos .= "loteloc.j06_lote as pql					 ";
$clobrasalvara->sql_query_cartaAlvara($sCampos, $codigo);
$rsObrasAlvara = $clobrasalvara->sql_record($clobrasalvara->sql_query_cartaAlvara($sCampos, $codigo));

if($clobrasalvara->numrows == 0){
  
  $oParms = new stdClass();
  $oParms->iCodigo = $codigo;
  $sMsg = _M('tributario.projetos.pro2_execobra002.obra_codigo_nao_encontrada', $oParms);
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit; 
}

db_fieldsmemory($rsObrasAlvara,0,true);

$result_obrasender=$clobrasender->sql_record($clobrasender->sql_query(null,"ob07_numero,j13_descr,j14_nome","",
" ob07_codobra=$codigo"));
if($clobrasender->numrows>0){
  db_fieldsmemory($result_obrasender,0);
}

$result_obraslote=$clobraslote->sql_record($clobraslote->sql_query($codigo,"j34_lote as lote,j34_quadra as quadra,j34_setor as setor"));
if($clobraslote->numrows>0){
  db_fieldsmemory($result_obraslote,0);
}else{
  $result_obraslotei=$clobraslotei->sql_record($clobraslotei->sql_query($codigo,"ob06_quadra as quadra,ob06_lote as lote,ob06_setor as setor"));
  if($clobraslotei->numrows>0){
    db_fieldsmemory($result_obraslotei,0);
  }
}

$rsObrasTecnicos = $clobrastecnicos->sql_record($clobrastecnicos->sql_query_file(null,"ob20_obrastec",null,"ob20_codobra = $codigo"));

if($clobrastecnicos->numrows>0){
  db_fieldsmemory($rsObrasTecnicos,0);
  
	$result_obrastec=$clobrastec->sql_record($clobrastec->sql_query($ob20_obrastec,"z01_nome as eng,ob15_crea"));
  
	if($clobrastec->numrows>0){
    db_fieldsmemory($result_obrastec,0);
  }
}

$result_obrasconstr=$clobrasconstr->sql_record($clobrasconstr->sql_query_file(null,"sum(ob08_area) as areatotal","",
" ob08_codobra=$codigo"));
if($clobrasconstr->numrows>0){
  db_fieldsmemory($result_obrasconstr,0);
}

$dia = date("d");
$mes = date("m");
$ano = date("Y");
$mes_extenso = array("01"=>"janeiro","02"=>"fevereiro","03"=>"março","04"=>"abril","05"=>"maio","06"=>"junho","07"=>"julho","08"=>"agosto","09"=>"setembro","10"=>"outubro","11"=>"novembro","12"=>"dezembro");
$data="Guaíba, ".$dia." de ".$mes_extenso[$mes]." de ".$ano.".";

$head1 = 'Departamento de Cadastro Imobiliário';
$pdf = new PDF1(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','',11);
/////// TEXTOS E ASSINATURAS

$instit = db_getsession("DB_instit");
$sqltexto = "select * from db_textos where id_instit = $instit and ( descrtexto like 'alvara%' or descrtexto like 'ass_alvara%')";

$resulttexto = db_query($sqltexto);

if (pg_num_rows($resulttexto) == 0 || $resulttexto == false) {
  $sMsg = _M('tributario.projetos.pro2_execobra002.configure_parametros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}


for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
  db_fieldsmemory($resulttexto,$xx);
  $text  = $descrtexto;
  $$text = db_geratexto($conteudotexto);
}
////////relatorio
$pdf->SetFont('Arial','B',15);
$pdf->MultiCell(0,4,utf8_decode($alvara_tit),0,"C",0,0);
$pdf->Ln(15);
$alt=4;

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Nome do proprietário: ","LT",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$z01_nome,"TR",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"CPF/CNPJ: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
if(strlen(trim($z01_cgccpf))==11){
  $z01_cgccpf=db_formatar($z01_cgccpf,"cpf");
}else if(strlen(trim($z01_cgccpf))==14){
  $z01_cgccpf=db_formatar($z01_cgccpf,"cgc");
}
$pdf->Cell(0,$alt,@$z01_cgccpf,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Endereço: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$vir="";
if(isset($j14_nome) && isset($ob07_numero)){
  $vir=", ";
}
$pdf->Cell(0,$alt,@$j14_nome.$vir.@$ob07_numero,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Bairro: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$j13_descr,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Local da obra: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,"o mesmo","R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(15,$alt,"Setor: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,$alt,@$setor,0,0,"L",0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(15,$alt,"Quadra: ",0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20,$alt,@$quadra,0,0,"L",0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(15,$alt,"Lote: ",0,0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$lote,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Validade: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,"$ob04_data á $ob04_dtvalidade","R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"P/Q/L: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,$pql,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Processo: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,$p58_codigo,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Matrícula: ","L",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$ob24_iptubase,"R",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Observações: ","LB",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,$ob04_obsprocesso,"RB",1,"L",0);


$pdf->Ln(4);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"Engenheiro responsável: ","TL",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$eng,"TR",1,"L",0);

$pdf->SetFont('Arial','B',8);
$pdf->Cell(35,$alt,"CREA n°: ","LB",0,"L",0);
$pdf->SetFont('Arial','',8);
$pdf->Cell(0,$alt,@$ob15_crea,"BR",1,"L",0);

$pdf->Ln(4);

$pdf->MultiCell(0,5,$alvara_p1,1,"J",0,0);

$pdf->sety(200);
$pdf->SetFont('Arial','',11);
$pdf->Cell(95,5,"________________________________________",0,0,"C",0);
$pdf->Cell(95,5,"________________________________________",0,1,"C",0);
$pdf->Ln(2);
$pdf->Cell(95,5,$ass_alvara1,0,0,"C",0);
$pdf->MultiCell(95,5,$ass_alvara2,0,"C",0,0);

$pdf->sety(260);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,5,$alvara_rpe,1,"C",0,0);

$pdf->Output();
?>