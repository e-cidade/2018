<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_issmatric_classe.php");
require_once("classes/db_escrito_classe.php");
require_once("classes/db_issbairro_classe.php");
require_once("classes/db_bairro_classe.php");
require_once("classes/db_issquant_classe.php");
require_once("classes/db_isszona_classe.php");
require_once("classes/db_issruas_classe.php");
require_once("classes/db_issprocesso_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptuconstr_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_db_cgmruas_classe.php");
require_once("classes/db_db_cgmbairro_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_cgmcpf_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issporte_classe.php");
require_once("classes/db_issbaseporte_classe.php");
require_once("classes/db_socios_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_sanitarioinscr_classe.php");
require_once("model/logInscricao.model.php");

db_postmemory($_POST);
$oPost = db_utils::postMemory($_POST);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprotprocesso   = new cl_protprocesso;
$clsocios         = new cl_socios;
$clissporte       = new cl_issporte;
$clissbaseporte   = new cl_issbaseporte;
$clcgm            = new cl_cgm;
$cldb_cgmcpf      = new cl_db_cgmcpf;
$clescrito        = new cl_escrito;
$clissbase        = new cl_issbase;
$clissmatric      = new cl_issmatric;
$clissprocesso    = new cl_issprocesso;
$clissbairro      = new cl_issbairro;
$clissruas        = new cl_issruas;
$clissquant       = new cl_issquant;
$clisszona        = new cl_isszona;
$cliptuconstr     = new cl_iptuconstr;
$cliptubase       = new cl_iptubase;
$cldb_cgmruas     = new cl_db_cgmruas;
$cldb_config      = new cl_db_config;
$cldb_cgmbairro   = new cl_db_cgmbairro;
$clbairro         = new cl_bairro;
$clrotulo         = new rotulocampo;
$clsanitarioinscr = new cl_sanitarioinscr;
$cllogincricao    = new loginscricao;

$clissquant->rotulo->label();
$db_opcao = 22;

if(isset($q05_matric)){
  $db_opcao = 2;
}

$db_botao = true;
$data     = db_getsession("DB_datausu");
$iAnoUsu  = db_getsession("DB_anousu");
$result02 = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"cep,munic"));
db_fieldsmemory($result02,0);

if (isset($alterar)) {

  if ($j14_codigo=="") {
    $cep=$z01_cep;
  }

  $sqlerro = false;
  $ano     = db_getsession("DB_datausu");

  db_inicio_transacao();

  if(($q02_dtjunta_ano!="")&&($q02_dtjunta_mes!="")&&($q02_dtjunta_dia!="")){
    $q02_dtjunta = $q02_dtjunta_ano."-".$q02_dtjunta_mes."-".$q02_dtjunta_dia;
  }else{
    $q02_dtjunta = "";
  }

  $clissbase->q02_inscr    = $q02_inscr;
  $clissbase->q02_numcgm   = $q02_numcgm;
  $clissbase->q02_tiplic   = "0";
  $clissbase->q02_fantaold = "0";
  $clissbase->q02_regjuc   = $q02_regjuc;
  $clissbase->q02_inscmu   = $q02_inscmu;
  $clissbase->q02_dtalt    = date("Y-m-d",$ano);
  $clissbase->q02_dtjunta  = $q02_dtjunta;
  $clissbase->q02_dtbaix   = null;
  $clissbase->q02_capit    = "0";
  $clissbase->q02_cep      = $cep;
  $clissbase->alterar($q02_inscr);

  $erromsg = $clissbase->erro_msg;
  if ($clissbase->erro_status == 0) {
    $sqlerro=true;
  }

  $sAreaAntiga   = "";
  $sSqlIssQuant  = $clissquant->sql_query($iAnoUsu,$clissbase->q02_inscr,"q30_area as areateste",null,"");
  $rsSqlIssQuant = $clissquant->sql_record($sSqlIssQuant);

  $q30_anousu             = date('Y',db_getsession("DB_datausu"));
  $clissquant->q30_anousu = $q30_anousu;
  $clissquant->q30_inscr  = $q02_inscr;
  $clissquant->q30_quant  = $q30_quant;
  $clissquant->q30_mult   = $q30_mult;
  $clissquant->q30_area   = $q30_area;
  if ($clissquant->numrows > 0) {

    $oIssQuant   = db_utils::fieldsMemory($rsSqlIssQuant,0);
    $sAreaAntiga = $oIssQuant->areateste;

    if (!$sqlerro) {
     $clissquant->alterar($q30_anousu, $q02_inscr);
   }
  } else {

    if (!$sqlerro) {
      $clissquant->incluir($q30_anousu, $q02_inscr);
    }
  }

  $erromsg=$clissquant->erro_msg;
  if ($clissquant->erro_status == 0) {
    $sqlerro = true;
  }

  if ($sqlerro==false) {

    if (isset($q35_zona) && $q35_zona != ""){
      $zona_ant=$q35_zona;
    }
    $resultzona=$clisszona->sql_record($clisszona->sql_query_file($q02_inscr,"*","",""));
    if ($clisszona->numrows>0) {

     $clisszona->q35_zona  = $q35_zona;
     $clisszona->q35_inscr = $q02_inscr;
     $clisszona->excluir($q02_inscr);

     if ($clisszona->erro_status==0) {

       $sqlerro=true;
       $erro=$clisszona->erro_msg;
     }
    }
  }

  if (isset($zona_ant) && $zona_ant != "") {

    if ($sqlerro==false) {

      $clisszona->q35_zona  = $zona_ant;
      $clisszona->q35_inscr = $q02_inscr;
      $clisszona->incluir($q02_inscr);
      if ($clisszona->erro_status==0) {

        $sqlerro=true;
        $erro=$clisszona->erro_msg;
      }
    }
  }

  if ($sqlerro==false) {

    $result12=$clissprocesso->sql_record($clissprocesso->sql_query_file($q02_inscr,"q14_proces"));
    if($clissprocesso->numrows>0) {

      $clissprocesso->q14_inscr  = $q02_inscr;
      $clissprocesso->q14_proces = $q14_proces;
      $clissprocesso->excluir($q02_inscr);
      $erromsg=$clissprocesso->erro_msg;
      if ($clissprocesso->erro_status=='0') {
        $sqlerro=true;
      }
    }
    if ($q14_proces!="") {

      $clissprocesso->q14_inscr  = $q02_inscr;
      $clissprocesso->q14_proces = $q14_proces;
      $clissprocesso->incluir($q02_inscr);
      $erromsg=$clissprocesso->erro_msg;
      if($clissprocesso->erro_status==0) {
        $sqlerro=true;
      }
    }
  }
  if ($sqlerro==false) {

    $result_baseporte=$clissbaseporte->sql_record($clissbaseporte->sql_query_file($q02_inscr,"q45_codporte"));
    if ($clissbaseporte->numrows>0) {

      $clissbaseporte->q45_inscr=$q02_inscr;
      $clissbaseporte->excluir($q02_inscr);
      $erromsg=$clissbaseporte->erro_msg;
      if ($clissbaseporte->erro_status=='0') {
        $sqlerro=true;
      }
    }
    if ($q45_codporte!="") {

      $clissbaseporte->q45_inscr    = $q02_inscr;
      $clissbaseporte->q45_codporte = $q45_codporte;
      $clissbaseporte->incluir($q02_inscr);
      $erromsg=$clissbaseporte->erro_msg;
      if($clissbaseporte->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  if ($sqlerro==false) {

    $result10=$clescrito->sql_record($clescrito->sql_query(null, "q10_numcgm as xq10_numcgm",null," q10_inscr = $q02_inscr"));
    if ($clescrito->numrows>0) {

      db_fieldsmemory($result10,0);
      $clescrito->q10_inscr  = $q02_inscr;
      $clescrito->q10_numcgm = $xq10_numcgm;
      $clescrito->excluir(null, "q10_inscr = $q02_inscr");
      $erromsg=$clescrito->erro_msg;
      if ($clescrito->erro_status==0) {
        $sqlerro=true;
      }
    }

    if ($q10_numcgm!="") {

      $clescrito->q10_inscr  = $q02_inscr;
      $clescrito->q10_numcgm = $q10_numcgm;
      $clescrito->incluir(null);
      $erromsg=$clescrito->erro_msg;
      if($clescrito->erro_status==0) {
        $sqlerro=true;
      }
    }
  }

  if($j14_codigo!="") {

    if ($sqlerro==false) {

      $result21=$clissruas->sql_record($clissruas->sql_query_file($q02_inscr,"j14_codigo"));
      if ($clissruas->numrows>0) {

        $clissruas->excluir($q02_inscr);
        $erromsg=$clissruas->erro_msg;
        if ($clissruas->erro_status==0) {
          $sqlerro=true;
        }
      }
    }
    if ($sqlerro==false) {

      $clissruas->q02_inscr  = $q02_inscr;
      $clissruas->j14_codigo = $j14_codigo;
      $clissruas->q02_numero = $q02_numero;
      $clissruas->q02_compl  = $q02_compl;
      $clissruas->q02_cxpost = $q02_cxpost;
      $clissruas->z01_cep    = $cepIssRuas;
      $clissruas->incluir($q02_inscr);
      $erromsg=$clissruas->erro_msg;
      if ($clissruas->erro_status==0) {
        $sqlerro=true;
      }
    }
  } else {

    if ($sqlerro==false) {

      $result21=$clissruas->sql_record($clissruas->sql_query_file($q02_inscr,"j14_codigo"));
      if ($clissruas->numrows>0) {

        $clissruas->excluir($q02_inscr);
        $erromsg=$clissruas->erro_msg;
        if($clissruas->erro_status==0){
          $sqlerro=true;
        }
      }
    }
  }
  if ($sqlerro==false) {

    $result09=$clissbairro->sql_record($clissbairro->sql_query_file($q02_inscr,"q13_bairro as x"));
    if ($clissbairro->numrows>0) {

      db_fieldsmemory($result09,0);
      $clissbairro->q13_inscr=$q02_inscr;
      $clissbairro->q13_bairro=$x;
      $clissbairro->excluir($q02_inscr);
      $erromsg=$clissbairro->erro_msg;
      if($clissbairro->erro_status==0){
        $sqlerro=true;
      }
    }
    $clissbairro->q13_inscr=$q02_inscr;
    $clissbairro->q13_bairro=$j13_codi;
    $clissbairro->incluir($q02_inscr);
    $erromsg=$clissbairro->erro_msg;
    if($clissbairro->erro_status==0){
      $sqlerro=true;
    }
  }

  if (strtoupper($munic)==strtoupper($z01_munic)) {

    if ($sqlerro==false) {

      $result07 = $clissmatric->sql_record($clissmatric->sql_query_file($q02_inscr,'','q05_matric as c,q05_idcons as s'));
      if ($clissmatric->numrows>0) {

        db_fieldsmemory($result07,0);
        $clissmatric->q05_inscr=$q02_inscr;
        $clissmatric->q05_matric=$c;
        $clissmatric->q05_idcons=$s;
        $clissmatric->excluir($q02_inscr,$c);
        $erromsg=$clissmatric->erro_msg;
             //$clissmatric->erro(true,false);
        if($clissmatric->erro_status==0){
          $sqlerro=true;
        }
      }
      if ($q05_matric!="") {

        $clissmatric->q05_inscr  = $q02_inscr;
        $clissmatric->q05_matric = $q05_matric;
        $clissmatric->q05_idcons = $q05_idcons;
        $clissmatric->incluir($q02_inscr,$q05_matric);
        $erromsg=$clissmatric->erro_msg;
        if($clissmatric->erro_status==0){
          $sqlerro=true;
        }
      }

    }
  }

  if ($sqlerro==false) {

  		//$clsanitarioinscr->y18_codsani = $y80_codsani ;
    $clsanitarioinscr->y18_inscr   = $q02_inscr;
    $clsanitarioinscr->excluir(null,$q02_inscr) ;
    if($clsanitarioinscr->erro_status==0){

      $sqlerro=true;
      $erro=$clsanitarioinscr->erro_msg;
    }

    if ($y80_codsani!="") {

      $clsanitarioinscr->y18_codsani = $y80_codsani ;
      $clsanitarioinscr->y18_inscr   = $q02_inscr;
      $clsanitarioinscr->incluir($y80_codsani,$q02_inscr) ;
      if ($clsanitarioinscr->erro_status==0) {

        $sqlerro=true;
        $erro=$clsanitarioinscr->erro_msg;
      }
    }
  }

if (!$sqlerro) {

  try {

    if (isset($q30_area) && !empty($q30_area)) {

      $cllogincricao->identificaAlteracao($clissbase->q02_inscr,2,11,"",$q30_area,$sAreaAntiga);
      $cllogincricao->gravarLog();
    }
  } catch ( Exception $eExeption ){

    $sqlerro = true;
    $erro    = $eExeption->getMessage();
  }
}

if (!$sqlerro) {
      //Valida o CGM se foi alterado o nome fantasia

  $rsCgm = $clcgm->sql_record($clcgm->sql_query($oPost->q02_numcgm, "z01_nomefanta", null, null));
  if ($rsCgm !== false) {

    $sZ01_nomefanta = db_utils::fieldsMemory($rsCgm,0)->z01_nomefanta;

    if (trim($sZ01_nomefanta) != trim($oPost->z01_nomefanta)) {
        //update na cgm
      $clcgm->z01_numcgm    = $oPost->q02_numcgm;
      $clcgm->z01_nomefanta = $oPost->z01_nomefanta;
      $clcgm->alterar($oPost->q02_numcgm);

      if ($clcgm->erro_status == "0") {
        $sqlerro = true;
        $erro = $clcgm->erro_msg;
      }
    }

  } else {

    $sqlerro = true;
    $erro = $clcgm->erro_msg;
  }
}


$db_botao = true;
$db_opcao=2;

db_fim_transacao($sqlerro);
} elseif(isset($chavepesquisa) && empty($q05_matric)) {

  $result05 = $clissbase->sql_record($clissbase->sql_query($chavepesquisa,'issbase.q02_inscr,q02_dtcada,q02_numcgm,z01_nome,q02_regjuc,q02_memo,q02_obs'));
  db_fieldsmemory($result05,0);

  $result06=$clcgm->sql_record($clcgm->sql_query_file($q02_numcgm,"z01_nomefanta,z01_cep,z01_munic,z01_incest,z01_cgccpf,z01_ender,z01_bairro,z01_compl,z01_numero,z01_cxpostal,z01_ident"));
  db_fieldsmemory($result06,0);


  $result07 = $clissmatric->sql_record($clissmatric->sql_query($chavepesquisa,'','q05_matric,q05_idcons,z01_nome as z01_nome_matric'));
  if($clissmatric->numrows>0){
    db_fieldsmemory($result07,0);
  }
  $result08 = $clissruas->sql_record($clissruas->sql_query($chavepesquisa,'ruas.j14_nome,ruas.j14_codigo,q02_numero,q02_compl,q02_cxpost'));
  if($clissruas->numrows>0){
    db_fieldsmemory($result08,0);
  }else{
    $q02_numero=$z01_numero;
    $q02_compl=$z01_compl;
    $q02_cxpot=$z01_cxpostal;
  }
  $result09=$clissbairro->sql_record($clissbairro->sql_query($chavepesquisa,"q13_bairro as j13_codi,j13_descr"));
  if($clissbairro->numrows>0){
    db_fieldsmemory($result09,0);
  }

  $result10=$clescrito->sql_record($clescrito->sql_query(null,"q10_numcgm,cgm.z01_nome as z01_nome_escrito",""," q10_inscr = $chavepesquisa "));
  if($clescrito->numrows>0){
    db_fieldsmemory($result10,0);
  }

  $result11=$clissquant->sql_record($clissquant->sql_query_file(date('Y',$data),$chavepesquisa,"q30_quant,q30_mult,q30_anousu,q30_area","q30_anousu desc"));
  if($clissquant->numrows>0){
    db_fieldsmemory($result11,0);
  }
   ////////////////////////////////////////////////////////////////////////////////////////
  $resultzona=$clisszona->sql_record($clisszona->sql_query($chavepesquisa,"q35_zona,j50_descr","",""));
  if($clisszona->numrows>0){
    db_fieldsmemory($resultzona,0);
  }
   ////////////////////////////////////////////////////////////////////////////////////////

  $result12=$clissprocesso->sql_record($clissprocesso->sql_query_file($chavepesquisa,"q14_proces"));
  if ($clissprocesso->numrows>0) {

    db_fieldsmemory($result12,0);
    $result_proc=$clprotprocesso->sql_record($clprotprocesso->sql_query_file(@$q14_proces,"p58_requer"));
    if ($clprotprocesso->numrows>0) {
      db_fieldsmemory($result_proc,0);
    }
  }

  $result_baseporte=$clissbaseporte->sql_record($clissbaseporte->sql_query_file($chavepesquisa,"q45_codporte"));
  if($clissbaseporte->numrows>0){
    db_fieldsmemory($result_baseporte,0);
  }
  $result_descrporte=$clissporte->sql_record($clissporte->sql_query_file(@$q45_codporte,"q40_descr"));
  if($clissporte->numrows>0){
    db_fieldsmemory($result_descrporte,0);
  }
  $sql_sani    = $clsanitarioinscr->sql_query_file("","","*","","y18_inscr = $q02_inscr");
  $result_sani = $clsanitarioinscr->sql_record($sql_sani);

  if ($clsanitarioinscr->numrows>0) {

    db_fieldsmemory($result_sani,0);
    $y80_codsani = $y18_codsani;
    $z01_nome1 = $z01_nome;
  }

  $db_botao = true;
  $db_opcao = 2;
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class ='container'>

    <?php
    include(Modification::getFile("forms/db_frmissbasealt.php"));
    ?>
  </div>
</body>

</html>
<?php

if (isset($alterar)) {

  if($sqlerro==true){
    db_msgbox($erromsg);
  }else{
    $clissbase->erro(true,false);
  }
}
?>