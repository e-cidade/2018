<?php
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

require_once("fpdf151/pdf3.php");
require_once("fpdf151/impcarne.php");
require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sql.php");
require_once("libs/db_libsys.php");
require_once("dbforms/db_funcoes.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");

db_postmemory($HTTP_SERVER_VARS);

$oGet           = db_utils::postMemory($_GET);
$iMatricula     = $oGet->matric;
$iCodigoIsencao = $oGet->codigoIsencao;

$cliptuisen = new cl_iptuisen;
$clcfiptu   = new cl_cfiptu;

$rsCfIptu                    = $clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession("DB_anousu"), "j18_templatecertidaoisencao"));
$j18_templatecertidaoisencao = db_utils::fieldsMemory($rsCfIptu, 0)->j18_templatecertidaoisencao;
if ($j18_templatecertidaoisencao != "") {

	$sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
	$sNomeRelatorio   = "tmp/CertidaoIsencao{$sDescrDoc}.pdf";
	$sCaminhoSalvoSxw = "tmp/CertidaoIsencao_{$sDescrDoc}_{$iMatricula}.sxw";

	$sAgt = "cadastro/certidao_isencao.agt";

	$aParam                  = array();
  $aParam['$matricula']     = $iMatricula;
	$aParam['$codigoisencao'] = $iCodigoIsencao;

	db_stdClass::oo2pdf(44, $j18_templatecertidaoisencao, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);
	exit;
}

global $dia;
global $mes;
global $ano;

$dia = date("d",db_getsession("DB_datausu"));
$mes = db_mes(date("m",db_getsession("DB_datausu")),2);
$ano = date("Y",db_getsession("DB_datausu"));

//Busca nome do usuário
$sqlnome = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
$resnome = db_query($sqlnome);
db_fieldsmemory($resnome,0);

$sqlparag = "select db02_texto
               from db_documento
                    inner join db_docparag  on db03_docum   = db04_docum
                    inner join db_tipodoc   on db08_codigo  = db03_tipodoc
                    inner join db_paragrafo on db04_idparag = db02_idparag
              where db03_tipodoc = 1017
                and db03_instit  = " . db_getsession("DB_instit")." order by db04_ordem ";
$resparag = db_query($sqlparag);

global $head1;

if ( pg_numrows($resparag) == 0 ) {
	$head1 = 'SECRETARIA DE FINANÇAS';
}else{

	db_fieldsmemory( $resparag, 0 );
 	$head1 = $db02_texto;
}

$pdf = new pdf3();
$pdf->Open();
$oPdf= new db_impcarne($pdf, '29');
$oPdf->objpdf->AddPage();
$oPdf->objpdf->SetTextColor(0, 0, 0);

$sqlMunic     = "select nomeinst, logo from db_config where codigo = ". db_getsession("DB_instit");
$rsMunic      = db_query($sqlMunic);
$numrowsmunic = pg_numrows($rsMunic);
if ($numrowsmunic == 0){

  db_redireciona('db_erros.php?fechar=true&db_erro=Nome da instituição não encontrado!');
  exit;
}

db_fieldsmemory($rsMunic,0);

$oPdf->isenprefeitura = $nomeinst;
$oPdf->isenlogo       = $logo;

$sqlparag = "select *
	             from db_documento
	                  inner join db_docparag  on db03_docum   = db04_docum
	                  inner join db_tipodoc   on db08_codigo  = db03_tipodoc
	                  inner join db_paragrafo on db04_idparag = db02_idparag
	            where db03_tipodoc = 1019
                and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
$resparag = db_query($sqlparag);
$numrows  = pg_numrows($resparag);
if ( $numrows == 0 ) {

 db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento da certidão de isenção!');
 exit;
}

for($cont=0;$cont<$numrows;$cont++){

  db_fieldsmemory($resparag,$cont);
  if($db04_ordem == 1){
    $oPdf->isenmsg1 = db_geratexto($db02_texto);     		// Cabec CERTIDÃO
  }
  if($db04_ordem == 2){
    $oPdf->isenmsg4 = db_geratexto($db02_texto);			// texto CERTIDÃO
  }
  if($db04_ordem == 3){
  	$oPdf->isenassinatura2 = db_geratexto($db02_texto);		// Assinatura Inspetor
  }
  if($db04_ordem == 4){
    $oPdf->isenassinatura  = db_geratexto($db02_texto);		// Assinatura Secretario
  }
}

$rsCfiptu  = $clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'),"*",null,""));
$numrows   = $clcfiptu->numrows;
if ($numrows==0){

  db_redireciona('db_erros.php?fechar=true&db_erro=Configure os parametros do modulo ');
  exit;
}

db_fieldsmemory($rsCfiptu,0);

$sWhereTipoPromitente = '';
if(isset($j18_dadoscertisen) && $j18_dadoscertisen == 1){
  $sWhereTipoPromitente = " and (j41_tipopro is true or j41_tipopro is null) ";
}

$j46_dtini = null;
$j46_dtfim = null;

$sSqlIsencao = $cliptuisen->sql_query_isen( null,
                                            " proprietario.*,
                                              cgm_propri.z01_cgccpf as cpfpropri,
                                              isenproc.*,
                                              cgm.z01_nome as nomepromi,
                                              cgm.z01_cgccpf as cpfpromi,
                                              j45_obscertidao,
                                              j41_tipopro,
                                              j46_dtini,
                                              j46_dtfim",
                                            null,
                                            " j46_matric = $iMatricula and j46_codigo = {$iCodigoIsencao} {$sWhereTipoPromitente}");
$rsIptuisen  = $cliptuisen->sql_record($sSqlIsencao);
$numrowsisen = $cliptuisen->numrows;
if ($numrowsisen==0){

  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi possível encontrar registros ');
  exit;
}
db_fieldsmemory($rsIptuisen,0);

$oPdf->isenmatric = $iMatricula;
$oPdf->isennome   = $proprietario;
$oPdf->isencgc    = $cpfpropri;

/**
 * Valida regra do promitente
 */
if(isset($j18_dadoscertisen) && $j18_dadoscertisen == 0){

	$oPdf->isennome = $proprietario;
	$oPdf->isencgc  = $cpfpropri;

}else if(isset($j18_dadoscertisen) && $j18_dadoscertisen == 1){

  if(isset($nomepromi) && $nomepromi != ""){

		$oPdf->isennome = $nomepromi;
		$oPdf->isencgc  = $cpfpromi;
	}else{

		$oPdf->isennome = $proprietario;
		$oPdf->isencgc  = $cpfpropri;
	}
}

$oPdf->isenmsg3 = "";
if(isset($j45_obscertidao) && $j45_obscertidao != ""){
  $oPdf->isenmsg3 = $j45_obscertidao;
}

$oPdf->isenender     = (isset($nomepri)&&$nomepri!=""?$nomepri.",".$j39_numero."/".$j39_compl:"Sem endereço cadastrado");
$oPdf->isenbairro    = $j34_bairro;
$oPdf->isendtini     = $j46_dtini;
$oPdf->isendtfim     = $j46_dtfim;
$oPdf->isenproc      = $j61_codproc;
$oPdf->isensetor     = $j34_setor;
$oPdf->isenquadra    = $j34_quadra;
$oPdf->isenlote      = $j34_lote;

$oPdf->j05_setorloc  = $j06_setorloc;
$oPdf->j05_quadraloc = $j06_quadraloc;
$oPdf->j05_loteloc   = $j06_lote;

$oPdf->imprime();
$oPdf->objpdf->Output();