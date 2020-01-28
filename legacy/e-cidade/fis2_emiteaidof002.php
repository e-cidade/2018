<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include ("libs/db_utils.php");
include("classes/db_aidof_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_notasiss_classe.php");
include("classes/db_parfiscal_classe.php");
require_once "libs/db_libdocumento.php";

$claidof     = new cl_aidof;
$clcgm       = new cl_cgm;
$clissbase   = new cl_issbase;
$clnotasiss  = new cl_notasiss;
$clparfiscal = new cl_parfiscal;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sqlpref = "select * from db_config where codigo = ".db_getsession("DB_instit");
$resultpref = pg_exec($sqlpref);
if (pg_numrows($resultpref)!=0){
  db_fieldsmemory($resultpref,0);
}


$result_aidof = $claidof->sql_record($claidof->sql_query_file($codaidof));
if ($claidof->numrows!=0){
  db_fieldsmemory($result_aidof,0);
}

$result_notasiss=$clnotasiss->sql_record($clnotasiss->sql_query_file(null,"q09_descr as especie",null,"q09_codigo=$y08_nota"));
if ($clnotasiss->numrows!=0){
  db_fieldsmemory($result_notasiss,0);
}

$sSql = " z01_compl  as compl_graf,
                z01_nome   as nome_graf,
				z01_incest as inscr_est,
                z01_ender  as ender_graf,
                z01_numero as num_graf,
                z01_cgccpf as cgccpf_graf,
                z01_bairro as bairro_graf,
                z01_munic  as munic_graf,
                z01_telef  as telef_graf,
                z01_cep    as cep_graf ";

$result_graf=$clcgm->sql_record($clcgm->sql_query_file($y08_numcgm,$sSql));
if ($clcgm->numrows!=0){
  db_fieldsmemory($result_graf,0);
}

$result_graf_inscr=$clissbase->sql_record($clissbase->sql_query_file(null,"q02_inscr as inscr_graf",null,"q02_numcgm=$y08_numcgm"));
if ($clissbase->numrows!=0){
  db_fieldsmemory($result_graf_inscr,0);
}

$sql_empr=" select empresa.q02_inscr  as inscr_usu,
                   issbase.q02_regjuc as regjuc,
                   empresa.z01_nome   as nome_usu,
                   empresa.z01_ender  as ender_usu,
                   empresa.z01_compl  as compl_usu,
                   empresa.z01_numero as num_usu,
                   empresa.z01_incest as cadest_usu,
                   empresa.z01_cgccpf as cgccpf_usu,
                   empresa.z01_bairro as bairro_usu,
                   empresa.z01_nomefanta  as nome_fantasia,
                   empresa.z01_telef  as telefone,
                   empresa.z01_cep    as cep,
                   issruas.j14_codigo as codigo_rua,
                   empresa.z01_munic  as munic_usu 
              from empresa
                   inner join issruas on issruas.q02_inscr = empresa.q02_inscr
                   inner join issbase on issbase.q02_inscr = empresa.q02_inscr
             where empresa.q02_inscr = $y08_inscr ";           
           
$result_empresa=pg_exec($sql_empr);
if (pg_numrows($result_empresa)!=0){
  db_fieldsmemory($result_empresa,0);
}

$sqlDbUsuarioAutoriza = "select nome as nomeusu from db_usuarios where id_usuario = $y08_login";
$rsDbUsuarioAutoriza  = pg_query($sqlDbUsuarioAutoriza);
$iDbUsuarioAutoriza   = pg_numrows($rsDbUsuarioAutoriza);

if ($iDbUsuarioAutoriza > 0) {
  $oDbUsuarioAutoriza = db_utils::fieldsMemory($rsDbUsuarioAutoriza,0);
}


$sqlDbUsuario = "select nome as nomeusu from db_usuarios where id_usuario =".db_getsession("DB_id_usuario");
$rsDbUsuario  = pg_query($sqlDbUsuario);
$iDbUsuario   = pg_numrows($rsDbUsuario);

if ($iDbUsuario > 0) {
	$oDbUsuario = db_utils::fieldsMemory($rsDbUsuario,0);
}

$sNomeArquivo = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
$sNomeArquivo = substr($sNomeArquivo,strrpos($sNomeArquivo,"/")+1);

if (isset($oDbUsuario->nomeusu) && $oDbUsuario->nomeusu != "") {
  $emissor = $oDbUsuario->nomeusu;
} else {
  $emissor = @$GLOBALS["DB_login"];
}

$sBase      = @$GLOBALS["DB_NBASE"];
$sArquivo   = $sNomeArquivo;
$sEmissor   = substr(ucwords(strtolower($emissor)),0,30);
$sExercicio = db_getsession("DB_anousu");
$sData      = date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s");

$rsParfiscal = $clparfiscal->sql_record($clparfiscal->sql_query());

if ($rsParfiscal == false || $clparfiscal->numrows > 0) {
	$oParfiscal = db_utils::fieldsMemory($rsParfiscal,0);
}


$pdf = new scpdf();
$pdf->Open();

if ($oParfiscal->y32_modaidof == 1) {
  $pdf1 = new db_impcarne($pdf,'14');
} else if ($oParfiscal->y32_modaidof == 2)  {
  $pdf1 = new db_impcarne($pdf,'14.novo');
}

//$pdf1->modelo = 14;
$pdf1->objpdf->SetTextColor(0,0,0);

for($i = 0;$i < pg_numrows($result);$i++){
  db_fieldsmemory($result,0);

  $pdf1->enderpref     = $ender;
  $pdf1->logo			     = $logo;
  $pdf1->prefeitura    = $nomeinst;
  $pdf1->municpref     = $munic;
  $pdf1->uf            = $uf;
  $pdf1->telefpref     = $telef;
  $pdf1->emailpref     = $email;
  $pdf1->codaidof      = @$y08_codigo;
  $pdf1->ano           = substr($y08_dtlanc,0,4);
  $pdf1->nome_graf     = @$nome_graf;
  $pdf1->ender_graf    = @$ender_graf.".".$num_graf."-".@$bairro_graf."/".@$munic_graf;
  if($compl_graf != ""){
    $pdf1->ender_graf	.= " COMPL: ".@$compl_graf;
  }
  $pdf1->inscr_est     = @$inscr_est;
  $pdf1->inscr_graf    = @$inscr_graf;
  $pdf1->cnpj_graf     = @$cgccpf_graf;
  $pdf1->telef_graf    = @$telef_graf;
  $pdf1->cep_graf      = @$cep_graf;
  $pdf1->nome_usu      = @$nome_usu;
  $pdf1->ender_usu     = @$ender_usu.".".@$num_usu."-".@$bairro_usu."/".@$munic_usu;
	if($compl_usu != ""){
  	$pdf1->ender_usu  .= " COMPL: ".@$compl_usu;
  }
  $pdf1->inscr_usu     = @$inscr_usu;
  $pdf1->cadest_usu    = @$cadest_usu;
  $pdf1->cnpj_usu      = @$cgccpf_usu;
  $pdf1->notaini       = @$y08_notain;
  $pdf1->notafin       = @$y08_notafi;
  $pdf1->quant         = @$y08_quantlib;
  $pdf1->especie       = @$especie;
  $pdf1->obs           = @$y08_obs;
  $pdf1->nome_fantasia = @$nome_fantasia;
  $pdf1->telefone      = @$telefone;
  $pdf1->cep           = @$cep;
  $pdf1->codigo_rua    = @$codigo_rua;
  
	/*
	*  Rodape   
	*/  
  
  $pdf1->base         = @$sBase;
  $pdf1->arquivo      = @$sArquivo;  
  $pdf1->emissor      = @$sEmissor;
  $pdf1->exercicio    = @$sExercicio;
  $pdf1->data         = @$sData;

  $pdf1->autoriza_usuario = $oDbUsuarioAutoriza->nomeusu;
  $pdf1->autoriza_data    = db_formatar($y08_dtlanc,'d');

  $pdf1->imprime();
   
}

$pdf1->objpdf->Output();

?>