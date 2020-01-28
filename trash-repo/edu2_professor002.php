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

require_once ("fpdf151/pdfwebseller.php");
require_once ("classes/db_rechumano_classe.php");
require_once ("classes/db_rechumanoescola_classe.php");
require_once ("classes/db_escola_classe.php");
require_once ("classes/db_rhpesdoc_classe.php");
require_once ("classes/db_periodoescola_classe.php");
require_once ("classes/db_diasemana_classe.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/censo/DadosCenso.model.php");
require_once ("classes/db_cursoedu_classe.php");
require_once ("model/CgmFactory.model.php");


db_app::import("exceptions.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.censo.*");
db_app::import("educacao.ausencia.*");
db_app::import("educacao.*");
db_app::import("configuracao.UsuarioSistema");

$clrechumano= new cl_rechumano;
$clrechumanoescola= new cl_rechumanoescola;
$clescola = new cl_escola;
$clrhpesdoc = new cl_rhpesdoc;
$clperiodoescola = new cl_periodoescola;
$cldiasemana = new cl_diasemana;
include("funcoes/db_func_rechumanonovo.php");

$result = $clrechumano->sql_record($clrechumano->sql_query_escola("","distinct ".$camposrechumano,"ed20_i_codigo"," case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $professor"));
if($clrechumano->numrows==0){	
?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}
db_fieldsmemory($result,0);
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(223);
$head1 = "RELAT�RIO DOS DADOS DO PROFESSOR";
$pdf->addpage('P');
$pdf->ln(5);

/////////////////////////////////////////////////////

$pdf->setfont('arial','b',7);
$pdf->cell(190,4,"DADOS PESSOAIS",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$alt_geral = $pdf->getY();
$pdf->setfont('arial','',7);
$pdf->cell(5,24,"","L",0,"C",0);
$pdf->cell(30,4,"CGM:",0,2,"L",0);
$pdf->cell(30,4,"Nome:",0,2,"L",0);
$pdf->cell(30,4,"Endere�o:",0,2,"L",0);
$pdf->cell(30,4,"Bairro:",0,2,"L",0);
$pdf->cell(30,4,"Munic�pio / UF:",0,2,"L",0);
$pdf->cell(30,4,"CEP:",0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(40);
$pdf->setfont('arial','b',7);
$pdf->cell(65,4,$professor,0,2,"L",0);
$pdf->cell(65,4,$z01_nome,0,2,"L",0);
$pdf->cell(65,4,$z01_ender." ".$z01_numero." ".$z01_compl,0,2,"L",0);
$pdf->cell(65,4,$z01_bairro,0,2,"L",0);
$pdf->cell(65,4,$z01_munic." / ".$z01_uf,0,2,"L",0);
$pdf->cell(65,4,$z01_cep,0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(105);
$pdf->setfont('arial','',7);
$pdf->cell(40,4,"Nascimento:",0,2,"L",0);
$pdf->cell(40,4,"Naturalidade:",0,2,"L",0);
$pdf->cell(40,4,"Sexo:",0,2,"L",0);
$pdf->cell(40,4,"Estado Civil:",0,2,"L",0);
$pdf->cell(40,4,"Telefone",0,2,"L",0);
$pdf->cell(40,4,"Telefone Celular:",0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(145);
$pdf->setfont('arial','b',7);
$pdf->cell(55,4,db_formatar($rh01_nasc,'d'),"R",2,"L",0);
$pdf->cell(55,4,$rh01_natura,"R",2,"L",0);
$pdf->cell(55,4,$z01_sexo=="M"?"MASCULINO":"FEMININO","R",2,"L",0);
if($z01_estciv==1){
 $z01_estciv = "SOLTEIRO";
}elseif($z01_estciv==2){
 $z01_estciv = "CASADO";
}elseif($z01_estciv==3){
 $z01_estciv = "VI�VO";
}else{
 $z01_estciv = "DIVORCIADO";
}
$pdf->cell(55,4,$z01_estciv,"R",2,"L",0);
$pdf->cell(55,4,$z01_telef,"R",2,"L",0);
$pdf->cell(55,4,$z01_telcel,"R",1,"L",0);
$pdf->cell(190,4,"","LR",1,"C",0);

////////////////////////////////////////////////////

$pdf->setfont('arial','b',7);
$pdf->cell(190,5,"DADOS ADMISSIONAIS",1,1,"C",1);
$pdf->cell(190,5,"","LR",1,"C",0);
for($x=0;$x<$clrechumano->numrows;$x++){
 db_fieldsmemory($result,$x);
 $alt_geral = $pdf->getY();
 $pdf->setfont('arial','',7);
 $pdf->cell(5,24,"","L",0,"C",0);
 $pdf->cell(30,4,$ed20_i_tiposervidor==1?"Matr�cula:":"CGM:",0,2,"L",0);
  if($ed20_i_tiposervidor==1){
  $pdf->cell(30,4,"Cargo:",0,2,"L",0);
  $pdf->cell(30,4,"Admissao:",0,2,"L",0);
 }
 $pdf->setY($alt_geral);
 $pdf->setX(40);
 $pdf->setfont('arial','b',7);
 $pdf->cell(65,4,$identificacao,0,2,"L",0);
  if($ed20_i_tiposervidor==1){ 
  $pdf->cell(65,4,$rh37_descr,0,2,"L",0);
  $pdf->cell(65,4,db_formatar($rh01_admiss,'d'),0,2,"L",0);
 }
 $pdf->setY($alt_geral);
 $pdf->setX(105);
 $pdf->setfont('arial','',7);
 $pdf->cell(40,4,"Regime:",0,2,"L",0);
 if($ed20_i_tiposervidor==1){
  $pdf->cell(40,4,"Lota��o:",0,2,"L",0); 
  $pdf->cell(40,4,"Tipo Admiss�o:",0,2,"L",0);
 }
 $pdf->setY($alt_geral);
 $pdf->setX(145);
 $pdf->setfont('arial','b',7);
 $pdf->cell(55,4,$rh30_descr,"R",2,"L",0);
 if($ed20_i_tiposervidor==1){
  $pdf->cell(55,4,$r70_descr,"R",2,"L",0);
  if(@$rh01_tipadm==1){
   $rh01_tipadm =  "Admissao do 1o emprego";
  }elseif(@$rh01_tipadm==2){
   $rh01_tipadm = "Admissao c/ emprego anterior";
  }elseif(@$rh01_tipadm==3){
   $rh01_tipadm = "Transf de empreg s/ onus p/ a cedente";
  }elseif(@$rh01_tipadm==4){
   $rh01_tipadm = "Transf de empreg c/ onus p/ a cedente";
  }else{
   $rh01_tipadm = "N�o Informado";
  }
  $pdf->cell(55,4,$rh01_tipadm,"R",2,"L",0);
 }
 $pdf->cell(55,4,"","R",1,"L",0);
 $pdf->cell(190,4,"","LR",1,"C",0);
}

/////////////////////////////////////////////////////////////

$pdf->setfont('arial','b',7);
$pdf->cell(190,4,"DOCUMENTOS",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$alt_geral = $pdf->getY();
$pdf->setfont('arial','',7);
$pdf->cell(5,32,"","L",0,"C",0);
$pdf->cell(30,4,"Identidade:",0,2,"L",0);
$pdf->cell(30,4,"CNPJ/CPF:",0,2,"L",0);
$pdf->cell(30,4,"N� T�tulo Eleitoral:",0,2,"L",0);
$pdf->cell(30,4,"Zona Eleitoral:",0,2,"L",0);
$pdf->cell(30,4,"Se��o Eleitoral:",0,2,"L",0);
$pdf->cell(30,4,"Cart. de Habilita��o:",0,2,"L",0);
$pdf->cell(30,4,"Categoria da CNH:",0,2,"L",0);
$pdf->cell(30,4,"Validade da CNH:",0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(40);
$pdf->setfont('arial','b',7);
$pdf->cell(65,4,$z01_ident,0,2,"L",0);
$pdf->cell(65,4,$z01_cgccpf,0,2,"L",0);
$pdf->cell(65,4,$rh16_titele,0,2,"L",0);
$pdf->cell(65,4,$rh16_zonael,0,2,"L",0);
$pdf->cell(65,4,$rh16_secaoe,0,2,"L",0);
$pdf->cell(65,4,$rh16_carth_n,0,2,"L",0);
$pdf->cell(65,4,$r16_carth_cat,0,2,"L",0);
$pdf->cell(65,4,$rh16_carth_val,0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(105);
$pdf->setfont('arial','',7);
$pdf->cell(40,4,"N� da CTPS:",0,2,"L",0);
$pdf->cell(40,4,"S�rie da CTPS:",0,2,"L",0);
$pdf->cell(40,4,"D�gito da CTPS:",0,2,"L",0);
$pdf->cell(40,4,"UF da CTPS:",0,2,"L",0);
$pdf->cell(40,4,"Cert. Reservista",0,2,"L",0);
$pdf->cell(40,4,"Categoria:",0,2,"L",0);
$pdf->cell(40,4,"PIS/PASEP:",0,2,"L",0);
$pdf->cell(40,4,"",0,2,"L",0);
$pdf->setY($alt_geral);
$pdf->setX(145);
$pdf->setfont('arial','b',7);
$pdf->cell(55,4,$rh16_ctps_n,"R",2,"L",0);
$pdf->cell(55,4,$rh16_ctps_s,"R",2,"L",0);
$pdf->cell(55,4,$rh16_ctps_d,"R",2,"L",0);
$pdf->cell(55,4,$rh16_ctps_uf,"R",2,"L",0);
$pdf->cell(55,4,$rh16_reserv,"R",2,"L",0);
$pdf->cell(55,4,$rh16_catres,"R",2,"L",0);
$pdf->cell(55,4,$rh16_pis,"R",2,"L",0);
$pdf->cell(55,4,"","R",1,"L",0);
$pdf->cell(190,4,"","LR",1,"C",0);

/////////////////////////////////////////////////////////////

$pdf->setfont('arial','b',7);
$pdf->cell(190,4,"ESCOLAS",1,1,"C",1);
$pdf->cell(190,4,"","LR",1,"C",0);
$result2 = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("","case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao,ed20_i_tiposervidor,ed20_i_codigo,ed18_i_codigo,ed18_c_nome,ed75_d_ingresso","ed20_i_codigo"," case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $professor"));
if($clrechumanoescola->numrows>0){
 $pdf->setfont('arial','b',7);
 $pdf->cell(5,4,"","L",0,"C",0);
 $pdf->cell(30,4,"",0,0,"L",0);
 $pdf->cell(80,4,"Nome da Escola:",0,0,"L",0);
 $pdf->cell(70,4,"Data de Ingresso no Sistema:",0,0,"C",0);
 $pdf->cell(5,4,"","R",1,"C",0);
 $pdf->setfont('arial','',7);
 for($x=0;$x<$clrechumanoescola->numrows;$x++){
  db_fieldsmemory($result2,$x);
  $pdf->cell(5,4,"","L",0,"C",0);
  $pdf->cell(15,4,($ed20_i_tiposervidor==1?"Matr�cula:":"CGM:"),0,0,"L",0);
  $pdf->cell(15,4,$identificacao,0,0,"L",0);  
  $pdf->cell(80,4,$ed18_i_codigo." - ".trim($ed18_c_nome),0,0,"L",0);
  $pdf->cell(70,4,db_formatar($ed75_d_ingresso,'d'),0,0,"C",0);
  $pdf->cell(5,4,"","R",1,"C",0);
 }
}else{
 $pdf->setfont('arial','',7);
 $pdf->cell(190,4,"Nenhum registro.","LR",0,"C",0);
}

$oDaoRecHumano = db_utils::getDao('rechumano');


$sSqlMovimentacao = $oDaoRecHumano->sql_query_movimentacao_professor_cgm($professor);
$rsMovimentacao   = $oDaoRecHumano->sql_record($sSqlMovimentacao);
$iRegistro        = $oDaoRecHumano->numrows;

$aMovimentos = array();

$pdf->setfont('arial','b',7);
$pdf->cell(190, 4,"MOVIMENTA��O",   1, 1, "C",1);
$pdf->cell(190, 4, '', "LR", 1);
$pdf->setfont('arial','b',7);
$pdf->cell(20,  4,"Data Inicial",   "LB", 0, "C", 0);  
$pdf->cell(20,  4,"Data Final ",     "B", 0, "C", 0);  
$pdf->cell(150, 4,"A��o Executada",  "RB", 1, "C", 0);

if ($iRegistro > 0) {

	for ($i = 0; $i < $iRegistro; $i++) {
		
		$oMovimento = db_utils::fieldsMemory($rsMovimentacao, $i);
		
		if ($oMovimento->tipo == 'A') {
			
			$oAusencia            = new AusenciaDocente($oMovimento->codigo);
			$oMovimento->dtInicio = $oAusencia->getDataInicial()->getDate(DBDate::DATA_PTBR);
			
			$oMovimento->dtFinal  = '';
			if ($oAusencia->getDataFinal() != null) {
				$oMovimento->dtFinal  = $oAusencia->getDataFinal()->getDate(DBDate::DATA_PTBR);
			}
			
			$sMsg  = "Ausente, Tipo: {$oAusencia->getTipoAusencia()->getDescricao()}";
			if ($oAusencia->getObservacao() != '') {
				$sMsg .= " Observa��o: {$oAusencia->getObservacao()}"; 
			}
			
			$oMovimento->sMessage = $sMsg;
			
		} elseif ($oMovimento->tipo == 'S') {
			
			$oSubstituicao        = new DocenteSubstituto($oMovimento->codigo);
			$oMovimento->dtInicio = $oSubstituicao->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
			$oMovimento->dtFinal  = '';
			if ($oSubstituicao->getPeriodoFinal() != null) {
				$oMovimento->dtFinal  = $oSubstituicao->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);
			}
	
			$sTipo = $oSubstituicao->getTipoVinculo() == 2 ? "PERMANENTE" : "TEMPORARIO";
			
			$sMsg  = "Professor Substituido : {$oSubstituicao->getAusente()->getDocente()->getProfessor()->getNome()}, ";
			$sMsg .= "Disciplina: {$oSubstituicao->getRegencia()->getDisciplina()->getNomeDisciplina()} ";
			$sMsg .= "Substitui��o: {$sTipo}"; 
			
			$oMovimento->sMessage = $sMsg;
		}
		$aMovimentos[] = $oMovimento;
	}
	
	$pdf->setfont('arial','',7);
	foreach ($aMovimentos as $oMovimento) {
	
		$pdf->cell(20,  4, $oMovimento->dtInicio, "L", 0, "C");
		$pdf->cell(20,  4, $oMovimento->dtFinal,  0, 0, "C");
		$pdf->cell(150, 4, substr($oMovimento->sMessage, 0, 110), "R", 1, "L");

	}
} else {
	
}


$pdf->cell(190,4,"","LR",1,"C",0);
$pdf->cell(190,1,"","LRB",1,"C",0);

$pdf->Output();

?>