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
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("classes/db_conplano_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_conplanosis_classe.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_conplanoconta_classe.php");
include ("classes/db_conplanoref_classe.php");

$clconplanoref = new cl_conplanoref;
$clconplanoconta = new cl_conplanoconta;
$clconplanosis = new cl_conplanosis;
$clconplano = new cl_conplano;
$clorctiporec = new cl_orctiporec;
$cldb_config = new cl_db_config;
$clconplanoreduz = new cl_conplanoreduz;
$clrotulo = new rotulocampo;
$clrotulo->label("o15_descr");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$anousu = db_getsession("DB_anousu");

$instit = str_replace("-", ",", $instit);
function php_espaco($nivel) {
	$espaco = "";
	switch ($nivel) {
		case 1 :
			$espaco = "";
			break;
		case 2 :
			$espaco = " ";
			break;
		case 3 :
			$espaco = "    ";
			break;
		case 4 :
			$espaco = "       ";
			break;
		case 5 :
			$espaco = "           ";
			break;
		case 6 :
			$espaco = "              ";
			break;
		case 7 :
			$espaco = "                  ";
			break;
		case 8 :
			$espaco = "                      ";
			break;
	}
	return $espaco;
}
$where  =  " c60_anousu = $anousu and (c61_instit in ($instit) or c61_instit is null  )";
if(isset($estrutural)&&$estrutural!="") {
    $where .= " and c60_estrut like '".$estrutural."%'";
}
$result = $clconplano->sql_record($clconplano->sql_vs_planocontas(null, 
      "c60_estrut,c61_reduz,c60_descr,c51_descr,c52_descrred,c62_codrec,o15_descr,codigo as inst_codigo, 
      nomeinst as inst_nome,c63_banco,c63_agencia,c63_dvagencia,c63_conta,c63_dvconta,c61_codigo", 'c60_estrut,c61_instit', $where));
if ($clconplano->numrows == 0) {
	db_redireciona("db_erros.php?fechar=true&db_erro=Plano de Contas não Cadastrado. Exercício: ".db_getsession("DB_anousu"));
}

$head3 = "PLANO DE CONTAS ";
$head4 = "";
$head5 = "EXERCICIO: ".db_getsession("DB_anousu");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(245);

$alt = 4;
$pagina = 1;


for ($i = 0; $i < pg_numrows($result); $i ++) {
	db_fieldsmemory($result, $i);
	if ($pdf->gety() > $pdf->h - 40 || $pagina == 1) {	
		$pagina = 0;
		$pdf->addpage('L');
		$pdf->setfont('arial', 'b', 7);
		
		$pdf->cell(132,$alt, "PLANO DE CONTA", 1, 0, "C", 0);
		$pdf->cell(101, $alt, "REDUZIDO", 1, 0, "C", 0);
		$pdf->cell(40, $alt, "BANCO", 1,1, "C", 0);
		
		$pdf->cell(25,$alt, "Estrutural", 1, 0, "C", 0);
		$pdf->cell(15, $alt, "Reduz", 1, 0, "C", 0);
		$pdf->cell(80, $alt, "Descrição", 1, 0, "C", 0);
		$pdf->cell(6, $alt, "Clas", 1, 0, "C", 0);
		$pdf->cell(6, $alt, "Sist", 1, 0, "C", 0);
		$pdf->cell(11, $alt, "Recurso", 1, 0, "C", 0);
		$pdf->cell(30, $alt, $RLo15_descr, 1, 0, "C", 0);
		$pdf->cell(60, $alt, "Instituição", 1,0, "C", 0);		
		$pdf->cell(10, $alt, "BAN", 1,0, "C", 0);
		$pdf->cell(10, $alt, "AGE", 1,0, "C", 0);
		$pdf->cell(20, $alt, "CONTA", 1,1, "C", 0);
		$pdf->Ln(2);
	}
	
	($c61_reduz!="" ?$cfundo="1":$cfundo="0" ); 
	
	$nivel = db_le_mae_conplano($c60_estrut, true);
	$espaco = php_espaco($nivel);	
	$pdf->cell(25, $alt, $c60_estrut, 0, 0, "L", $cfundo);
	$pdf->cell(15, $alt, $c61_reduz, 0, 0, "C", $cfundo);
	$pdf->cell(80, $alt, "$espaco".substr($c60_descr,0,42), 0, 0, "L", $cfundo);
	$pdf->cell(6, $alt, substr($c51_descr, 0, 1), 0, 0, "L", $cfundo);
	$pdf->cell(6, $alt, "$c52_descrred", 0, 0, "L", $cfundo);
	 // $pdf->cell(11, $alt, "$c62_codrec", 0, 0, "C", $cfundo);
      	 // $pdf->cell(30, $alt, $o15_descr, 0, 0, "L", $cfundo);
	 // agora o recurso eh do complanoreduz
	$pdf->cell(11, $alt, "$c61_codigo", 0, 0, "C", $cfundo);
      	$pdf->cell(30, $alt, $o15_descr, 0, 0, "L", $cfundo);

	 
	$pdf->cell(60, $alt, $inst_codigo." ".$inst_nome, 0, 0, "L", $cfundo);
	$pdf->cell(10, $alt, $c63_banco, 0, 0, "L", $cfundo);	
	$pdf->cell(10, $alt, $c63_agencia." ".$c63_dvagencia, 0, 0, "L", $cfundo);
	$pdf->cell(10, $alt, $c63_conta." ".$c63_dvconta, 0, 1, "L", $cfundo);
	
	
}

$pdf->Output();
?>