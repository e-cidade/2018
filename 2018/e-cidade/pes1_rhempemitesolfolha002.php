<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("classes/db_rhsolicita_classe.php");
include("classes/db_solicita_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcsugforn_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_orcreservasol_classe.php");
include("classes/db_pcparam_classe.php");
include("classes/db_empparametro_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//echo $HTTP_SERVER_VARS["QUERY_STRING"]; exit;

$clrhsolicita    = new cl_rhsolicita;
$clsolicita      = new cl_solicita;
$clsolicitem     = new cl_solicitem;
$clpcdotac       = new cl_pcdotac;
$clpcsugforn     = new cl_pcsugforn;
$cldb_departorg  = new cl_db_departorg;
$classinatura    = new cl_assinatura;
$clorcreservasol = new cl_orcreservasol;
$clpcparam       = new cl_pcparam;
$clempparametro	 = new cl_empparametro;

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref,0);

$result02 = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_nroviaaut,e30_numdec"));
if($clempparametro->numrows>0){
  db_fieldsmemory($result02,0);
}

$sequencia = "";

if (isset($ponto) && trim($ponto) != ""){
  if ($ponto == "s") {
    $siglaarq = "r14";
  } else if ($ponto == "c") {
    $siglaarq  = "r48";
    $sequencia = " and rh33_seqfolha = $rh40_sequencia ";
  } else if ($ponto == "a") {
    $siglaarq  = "r22";
  } else if ($ponto == "r") {
    $siglaarq  = "r20";
  } else if ($ponto == "d") {
    $siglaarq  = "r35";
  } else if ($ponto == "f") {
    $siglaarq  = "r31";
  }
}

$where_solicita = "";
if (isset($sol_ini) && trim($sol_ini)!=""){
  $where_solicita = "rh33_solicita >= $sol_ini";
}

if (isset($sol_fin) && trim($sol_fin)!=""){
  if ($where_solicita == ""){
    $where_solicita = "rh33_solicita <= $sol_fin";
  } else {
    $where_solicita = "rh33_solicita between $sol_ini and $sol_fin";
  }
}

if (!isset($sol_ini) || !isset($sol_fin)){
  $where_solicita = "rh33_anousu = $DBtxt23 and rh33_mesusu = $DBtxt25 and rh33_siglaarq = '$siglaarq' $sequencia";
}

$res_rhsolicita = $clrhsolicita->sql_record($clrhsolicita->sql_query_file(null,"rh33_solicita",null,"$where_solicita"));
if ($clrhsolicita->numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Esta(s) Solicitação(ões) não foram gerada(s) pela Folha ou não foram encontrada(s).Verifique!");
  exit;
}

$solicitacao = "";
$virgula     = "";
for($i=0; $i < $clrhsolicita->numrows; $i++){
  db_fieldsmemory($res_rhsolicita,$i);

  $solicitacao .= $virgula.$rh33_solicita;
  $virgula      = ",";
}

$where_solicita       = "pc10_numero in ($solicitacao)";
$result_pesq_solicita = $clsolicita->sql_record($clsolicita->sql_query_solicita(null," distinct pc67_sequencial, pc10_numero,pc10_data,pc10_resumo,pc12_vlrap,descrdepto,coddepto,nomeresponsavel,pc50_descr,pc10_login,nome",'pc10_numero',$where_solicita));
$numrows_solicita     = $clsolicita->numrows;
if($numrows_solicita==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum Registro Encontrado! Verifique seu departamento.");
  exit;
}

$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'11');
//$pdf1->modelo = 11;
$pdf1->objpdf->SetTextColor(0,0,0);
$pdf1->Snumero_ant = "";
for($contador=0;$contador<$numrows_solicita;$contador++){
  db_fieldsmemory($result_pesq_solicita,$contador);
  $pdf1->anulada    = !empty($pc67_sequencial);
  $pdf1->prefeitura = $nomeinst;
  $pdf1->enderpref  = $ender;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $telef;
  $pdf1->emailpref  = $email;
  $pdf1->logo				= $logo;
	$pdf1->emissao    = date("Y-m-d",db_getsession("DB_datausu"));
  $pdf1->cgcpref    = $cgc;
  $sec  = "______________________________"."\n"."Secretaria da Fazenda";
  $pref = "______________________________"."\n"."Prefeito";

  $pdf1->casadec    = $e30_numdec;
  $pdf1->secfaz     = $classinatura->assinatura(1002);
  $pdf1->nompre     = $classinatura->assinatura(1000);

  $pdf1->Snumero    = $pc10_numero;
  $pdf1->Sdata      = $pc10_data;
  $pdf1->Svalor     = $pc12_vlrap;
  $pdf1->Sresumo    = stripslashes(addslashes($pc10_resumo));
  $pdf1->Stipcom    = $pc50_descr;
  $pdf1->Sdepart    = $descrdepto;
  $pdf1->Srespdepart= $nomeresponsavel;
  $pdf1->Susuarioger= $nome;

  $result_orgunid   = $cldb_departorg->sql_record($cldb_departorg->sql_query_orgunid($coddepto,db_getsession('DB_anousu'),"o40_descr,o41_descr"));
  db_fieldsmemory($result_orgunid,0);
  $pdf1->Sorgao     = $o40_descr;
  $pdf1->Sunidade   = $o41_descr;

  $result_pesq_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_rel(null,"distinct pc01_servico,pc11_seq,pc11_codigo,pc11_seq,pc11_quant,pc11_vlrun,pc11_prazo,pc11_pgto,pc11_resum,pc11_just,m61_abrev,m61_descr,pc17_quant,pc01_codmater,pc01_descrmater,(pc11_quant*pc11_vlrun) as pc11_valtot,m61_usaquant,o56_elemento as so56_elemento,o56_descr as descrele",'pc11_seq'," pc11_numero=$pc10_numero"));
  $numrows_solicitem = $clsolicitem->numrows;
  $pdf1->recorddositens = $result_pesq_solicitem;
  $pdf1->linhasdositens = $numrows_solicitem;
  $pdf1->item	        = 'pc11_seq';
  $pdf1->quantitem      = 'pc11_quant';
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

  $result_pesq_pcdotac = $clpcdotac->sql_record($clpcdotac->sql_query_dotreserva(null,null,null,"pc13_codigo,pc13_anousu,pc13_coddot,pc13_quant,pc13_valor,o56_elemento as do56_elemento",'pc13_codigo',"pc11_numero=$pc10_numero"));
  $numrows_pcdotac = $clpcdotac->numrows;
  $pdf1->recorddasdotac = $result_pesq_pcdotac;
  $pdf1->linhasdasdotac = $numrows_pcdotac;
  $pdf1->dcodigo        = 'pc13_codigo';
  $pdf1->dcoddot        = 'pc13_coddot';
  $pdf1->danousu        = 'pc13_anousu';
  $pdf1->dquant         = 'pc13_quant';
  $pdf1->dvalor         = 'pc13_valor';
  $pdf1->delemento      = 'do56_elemento';


  $result_pesq_pcsugforn = $clpcsugforn->sql_record($clpcsugforn->sql_query($pc10_numero,null,"distinct z01_numcgm,z01_nome,z01_ender,z01_numero,z01_munic,z01_telef,z01_cgccpf",'z01_numcgm'));
  $numrows_pcsugforn = $clpcsugforn->numrows;
  $pdf1->recorddosfornec = $result_pesq_pcsugforn;
  $pdf1->linhasdosfornec = $numrows_pcsugforn;
  $pdf1->cgmforn         = 'z01_numcgm';
  $pdf1->nomeforn        = 'z01_nome';
  $pdf1->enderforn       = 'z01_ender';
  $pdf1->numforn         = 'z01_numero';
  $pdf1->municforn       = 'z01_munic';
  $pdf1->foneforn        = 'z01_telef';
  $pdf1->cgccpf          = 'z01_cgccpf';
  $pdf1->imprime();
  $pdf1->Snumero_ant = $pc10_numero;
}
if(isset($argv[1])){
  $pdf1->objpdf->Output("/tmp/teste.pdf");
}else{
  $pdf1->objpdf->Output();
}
?>