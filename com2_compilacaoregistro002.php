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

require("fpdf151/scpdf.php");
include("fpdf151/impcarne.php");
include("libs/db_sql.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_pcsugforn_classe.php");
include("classes/db_empparametro_classe.php");
include("classes/db_solicitaregistropreco_classe.php");
include("classes/db_db_departorg_classe.php");

$clsolicitem 							= new cl_solicitem;
$classinatura 						= new cl_assinatura;
$clpcparam  							= new cl_pcparam;
$clpcsugforn 							= new cl_pcsugforn;
$clempparametro	  				= new cl_empparametro;
$clsolicitaregistropreco	= new cl_solicitaregistropreco;
$cldb_departorg 					= new cl_db_departorg;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

// die($clpcparam->sql_query_file(null,"pc30_comsaldo,pc30_permsemdotac,pc30_gerareserva,pc30_libdotac"));
$result_pcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_comsaldo,pc30_permsemdotac,pc30_gerareserva,pc30_libdotac"));
db_fieldsmemory($result_pcparam,0);

// die($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec"));
$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec"));
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
}

$where_solicita = "";
if(isset($ini) && trim($ini)!=""){
  $where_solicita = " pc10_numero >= $ini";
}
if(isset($fim) && trim($fim)!=""){
  if($where_solicita == ""){
    $where_solicita = " pc10_numero <= $fim";
  }else{
    $where_solicita = " pc10_numero between $ini and $fim";
  }
}
if($where_solicita != ""){
	$where_solicita .= " and pc10_solicitacaotipo = 6";
}else{
	$where_solicita .= " pc10_solicitacaotipo = 6";
}

$sCampos  = "distinct *, ";
$sCampos .= "(select pc52_sequencial";
$sCampos .= "   from solicitacaotipo inner join solicita st2 on pc52_sequencial = pc10_solicitacaotipo";
$sCampos .= "  where st2.pc10_numero = (select pc53_solicitapai";
$sCampos .= "                             from solicita s inner join solicitavinculo on pc53_solicitafilho = s.pc10_numero";
$sCampos .= "                            where s.pc10_numero = solicita.pc10_numero)) as tiposolicitacaopai,";
$sCampos .= "(select pc53_solicitapai";
$sCampos .= "   from solicita s inner join solicitavinculo on pc53_solicitafilho = s.pc10_numero";
$sCampos .= "  where s.pc10_numero = solicita.pc10_numero) as codigosolicitacaopai";

$result_pesq_solicita = $clsolicitaregistropreco->sql_record($clsolicitaregistropreco->sql_query(null,$sCampos,null,$where_solicita));
 
$numrows_solicita = $clsolicitaregistropreco->numrows;
if($numrows_solicita == 0){
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! ");
}
$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'68');
//$pdf1->modelo = 17;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";

$pdf1->casadec    = $e30_numdec;
for($contador=0;$contador<$numrows_solicita;$contador++){
  db_fieldsmemory($result_pesq_solicita,$contador);
  $pdf1->prefeitura = $nomeinst;
  $pdf1->logo			  = $logo;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $sec  = "______________________________"."\n"."Secretaria da Fazenda";
  $pref = "______________________________"."\n"."Prefeito";

  $pdf1->secfaz           = $classinatura->assinatura(1002);
  $pdf1->nompre           = $classinatura->assinatura(1000);
                          
  $pdf1->Snumero          = $pc10_numero;
  $pdf1->Sdata            = $pc10_data;
  $pdf1->Svalor           = 0; //$pc12_vlrap;
  $pdf1->Sresumo          = $pc10_resumo;
  $pdf1->Stipcom          = ''; //$pc50_descr;  
  $pdf1->Sdepart          = $descrdepto;
  $pdf1->Srespdepart      = $nomeresponsavel;
  $pdf1->Susuarioger      = $nome;
	$pdf1->StipoSolicitacao = $pc52_sequencial.$pc52_descricao;
	$pdf1->Stiposolicitacaopai   = $tiposolicitacaopai;
	$pdf1->Scodigosolicitacaopai = $codigosolicitacaopai;
	
	$result_orgunid   = $cldb_departorg->sql_record($cldb_departorg->sql_query_orgunid($coddepto,db_getsession('DB_anousu'),"o40_descr,o41_descr"));
  db_fieldsmemory($result_orgunid,0);  
  $pdf1->Sorgao     = $o40_descr;
  $pdf1->Sunidade   = $o41_descr;

//  die($clsolicitem->sql_query_relmod2(null,"distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural,o55_descr,o15_descr,b.o56_descr as descrestrutural,pc13_codigo,pc13_anousu,pc13_coddot,pc13_quant,pc13_valor,b.o56_elemento as do56_elemento,pc05_servico,pc11_seq,pc11_codigo,pc11_quant,pc11_vlrun,pc11_prazo,pc11_pgto,pc11_resum,pc11_just,m61_abrev,m61_descr,pc17_quant,pc01_codmater,pc01_descrmater,(pc13_valor/pc13_quant) as pc13_valtot,(pc11_vlrun*pc11_quant) as pc11_valtot,m61_usaquant,a.o56_elemento as so56_elemento,a.o56_descr as descrele",'pc13_coddot,pc13_codigo',"pc11_numero=$pc10_numero"));
  
  $result_pesq_pcdotac = $clsolicitem->sql_record(
  														$clsolicitem->sql_query_relmod2(null,
  																			"distinct '' as estrutural,
  																								'' as o55_projativ,
  																								'' as o55_descr,
  																								'' as o15_codigo,
  																								'' as o15_descr,
  																								'' as descrestrutural,
  																								'' as pc13_codigo,
  																								'' as pc13_anousu,
  																								'' as pc13_coddot,
  																								'' as pc13_quant,
  																								'' as pc13_valor,
  																								'' as do56_elemento,
  																								'' as pc01_servico,
  																								pc11_seq,
  																								pc11_codigo,
  																								pc11_quant,
  																								pc11_vlrun,
  																								pc11_prazo,
  																								pc11_pgto,
  																								pc11_resum,
  																								pc11_just,
  																								'' as m61_abrev,
  																								m61_descr,
  																								'' as pc17_quant,
  																								pc01_codmater,
  																								pc01_descrmater,
  																								0 as pc13_valtot,
  																								0 as pc11_valtot,
  																								0 as m61_usaquant,
  																								'' as so56_elemento,
  																								'' as descrele,
  																								'' as o41_descr,
  																								pc57_quantmax,
  																								pc57_quantmin",
  																								'pc11_seq,
  																								pc13_codigo',
  																								"pc11_numero=$pc10_numero"));
  $numrows_pcdotac = $clsolicitem->numrows;
  $pdf1->recorddasdotac = $result_pesq_pcdotac;
  $pdf1->linhasdasdotac = $numrows_pcdotac;
  $pdf1->dcodigo        = 'pc13_codigo';
  $pdf1->dcoddot        = 'pc13_coddot';
  $pdf1->danousu        = 'pc13_anousu';
  $pdf1->dquant         = 'pc13_quant';
  $pdf1->dvalor         = 'pc13_valor';
  $pdf1->delemento      = 'estrutural';
  $pdf1->dvalortot      = 'pc13_valtot';

  $pdf1->descrunid      = 'o41_descr';
  $pdf1->dcprojativ     = 'o55_projativ';
  $pdf1->dctiporec      = 'o15_codigo';
  $pdf1->dprojativ      = 'o55_descr';
  $pdf1->dtiporec       = 'o15_descr';
  $pdf1->ddescrest      = 'descrestrutural';

  $pdf1->item	        	= 'pc11_seq';
  $pdf1->quantitem      = 'pc11_quant';
  $pdf1->quantitemmin   = 'pc57_quantmin';
  $pdf1->quantitemmax   = 'pc57_quantmax';
  $pdf1->valoritem      = 'pc11_vlrun';
  $pdf1->descricaoitem  = 'pc01_descrmater';
  $pdf1->squantunid     = 'pc17_quant';
  $pdf1->sprazo         = 'pc11_prazo';
  $pdf1->spgto          = 'pc11_pgto';
  $pdf1->sresum         = 'pc11_resum';
  $pdf1->sjust          = 'pc11_just';
  $pdf1->sunidade       = 'm61_descr';
  $pdf1->sabrevunidade  = 'm61_abrev';
  $pdf1->sservico       = 'pc01_servico';
  $pdf1->svalortot      = 'pc11_valtot';
  $pdf1->susaquant      = 'm61_usaquant';
  $pdf1->scodpcmater    = 'pc01_codmater';
  $pdf1->selemento      = 'so56_elemento';
  $pdf1->sdelemento     = 'descrele';

  $result_pesq_pcsugforn = $clpcsugforn->sql_record($clpcsugforn->sql_query($pc10_numero,null,"distinct z01_numcgm,z01_nome,z01_ender,z01_numero,z01_munic,z01_telef,z01_cgccpf",'z01_numcgm'));
  $numrows_pcsugforn = $clpcsugforn->numrows;
  $pdf1->recorddosfornec = $result_pesq_pcsugforn;
  $pdf1->linhasdosfornec = $numrows_pcsugforn;
  $pdf1->cgmforn        = 'z01_numcgm';
  $pdf1->nomeforn       = 'z01_nome';
  $pdf1->enderforn      = 'z01_ender';
  $pdf1->numforn        = 'z01_numero';
  $pdf1->municforn      = 'z01_munic';
  $pdf1->foneforn       = 'z01_telef';
  $pdf1->cgccpf         = 'z01_cgccpf';
  $pdf1->imprime();
  $pdf1->Snumero_ant = $pc10_numero;
}
if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>