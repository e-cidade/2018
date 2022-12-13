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
require_once("libs/db_utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_issbaseporte_classe.php");
require_once("classes/db_issmatric_classe.php");
require_once("classes/db_escrito_classe.php");
require_once("classes/db_issbairro_classe.php");
require_once("classes/db_issquant_classe.php");
require_once("classes/db_issruas_classe.php");
require_once("classes/db_issprocesso_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_bairro_classe.php");
require_once("classes/db_iptuconstr_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_db_cgmruas_classe.php");
require_once("classes/db_db_cgmbairro_classe.php");
require_once("classes/db_db_cgmcpf_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_issporte_classe.php");
require_once("classes/db_socios_classe.php");
require_once("classes/db_isszona_classe.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_sanitarioinscr_classe.php");
require_once("classes/db_parissqn_classe.php");
require_once("model/logInscricao.model.php");

db_postmemory($_POST);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clissporte       = new cl_issporte;
$clsocios         = new cl_socios;
$clprotprocesso   = new cl_protprocesso;
$clcgm            = new cl_cgm;
$clbairro         = new cl_bairro;
$clescrito        = new cl_escrito;
$cldb_cgmcpf      = new cl_db_cgmcpf;
$clissbase        = new cl_issbase;
$clissbaseporte   = new cl_issbaseporte;
$clissmatric      = new cl_issmatric;
$clissprocesso    = new cl_issprocesso;
$clissbairro      = new cl_issbairro;
$clissruas        = new cl_issruas;
$clissquant       = new cl_issquant;
$cliptuconstr     = new cl_iptuconstr;
$cliptubase       = new cl_iptubase;
$cldb_cgmruas     = new cl_db_cgmruas;
$cldb_config      = new cl_db_config;
$cldb_cgmbairro   = new cl_db_cgmbairro;
$clisszona        = new cl_isszona;
$clsanitario      = new cl_sanitario;
$clsanitarioinscr = new cl_sanitarioinscr;
$clparissqn       = new cl_parissqn;
$cllogincricao    = new loginscricao;
$clrotulo         = new rotulocampo;

$clissquant->rotulo->label();
$clissbase->rotulo->label();

$db_opcao = 1;
$db_botao = true;

//verifica o parametro na tabela parissqn para gerar sanitario automaticamente apartir do ISSQN
$rsParissqn = $clparissqn->sql_record($clparissqn->sql_query(null,"*",null,""));
$numrowsParissqn = $clparissqn->numrows;
if ($numrowsParissqn > 0) {
 db_fieldsmemory($rsParissqn,0);
}else{
  db_msgbox("Configure os parametro do modulo issqn");
}

//************************************************************************************************//

$result02=$cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"munic,cep"));
db_fieldsmemory($result02,0);
if(isset($q02_numcgm) && empty($incluir)){

  $sCamposCgm  = "z01_ident, z01_munic, z01_nome, z01_nomefanta, z01_incest, z01_cgccpf, z01_cep, z01_ender, ";
  $sCamposCgm .= "z01_bairro, z01_compl, z01_numero, z01_cxpostal as q02_cxpost, z01_ender";
  $result01=$clcgm->sql_record( $clcgm->sql_query_file($q02_numcgm, $sCamposCgm) );

  if ($clcgm->numrows!=1) {
    echo "
    <script>
      parent.location.href='iss1_issbase004.php?invalido=true';
    </script>
    ";
    exit;
  } else {

    db_fieldsmemory($result01,0);
    if($z01_cep==""){
      echo "
      <script>
        parent.location.href='iss1_issbase004.php?cep=true';
      </script>
      ";
      exit;
    }
    if($z01_cgccpf==""){
      echo "
      <script>
        parent.location.href='iss1_issbase004.php?cgccpf=true';
      </script>
      ";
      exit;
    }

    $result14=$clissbase->sql_record($clissbase->sql_query_file("","q02_inscr as inscrs","","q02_numcgm=$q02_numcgm"));
    $numrows14=$clissbase->numrows;
    if ($numrows14>0) {

      $str_01="";
      $str_02="";
      for($r=0; $r<$numrows14; $r++){
        db_fieldsmemory($result14,$r);
        $str_01.=$str_02.$inscrs;
        $str_02=', ';
      }
    }
  }
}

if (isset($incluir)) {

  $result_testa_processo=$clprotprocesso->sql_record($clprotprocesso->sql_query_file($q14_proces,"*",null,""));
  if ($clprotprocesso->numrows==0) {
    $sqlerro=true;
    $erro="Cod. do Processo não existe!!";
  }

  if ($j14_codigo=="") {
    $cep=$z01_cep;
  }
  $ano     = db_getsession("DB_datausu");
  $sqlerro = false;

  db_inicio_transacao();

  if (($q02_dtjunta_ano!="")&&($q02_dtjunta_mes!="")&&($q02_dtjunta_dia!="")) {
    $q02_dtjunta = $q02_dtjunta_ano."-".$q02_dtjunta_mes."-".$q02_dtjunta_dia;
  }
  if (($q02_dtcada_ano!="")&&($q02_dtcada_mes!="")&&($q02_dtcada_dia!="")) {
    $q02_dtcada = $q02_dtcada_ano."-".$q02_dtcada_mes."-".$q02_dtcada_dia;
  }

  $clissbase->q02_numcgm   = $q02_numcgm;
  $clissbase->q02_memo     = '';
  $clissbase->q02_tiplic   = "0";
  $clissbase->q02_fantaold = "0";
  $clissbase->q02_regjuc   = $q02_regjuc;
  $clissbase->q02_inscmu   = $q02_inscmu;
  $clissbase->q02_obs      = '';
  $clissbase->q02_dtcada   = $q02_dtcada;
  $clissbase->q02_dtinic   = date("Y-m-d",$ano);
  $clissbase->q02_ultalt   = date("Y-m-d",$ano);
  $clissbase->q02_dtalt    = date("Y-m-d",$ano);
  $clissbase->q02_dtjunta  = $q02_dtjunta;
  $clissbase->q02_dtbaix   = null;
  $clissbase->q02_capit    = "0";
  $clissbase->q02_cep      = $cep;
  $clissbase->incluirNumeracaoContinua($q02_inscr);

  if ($clissbase->erro_status==0) {

    $sqlerro = true;
    $erro    = $clissbase->erro_msg;
  }

  if (!$sqlerro) {

    $clissquant->q30_anousu=date("Y",$ano);
    $clissquant->q30_inscr=$clissbase->q02_inscr;
    $clissquant->q30_quant=$q30_quant;
    $clissquant->q30_mult=$q30_mult;
    $clissquant->q30_area = $q30_area;
    $clissquant->incluir(date("Y",$ano),$clissbase->q02_inscr);
      //$clissquant->erro(true,false);
    if ($clissquant->erro_status==0) {

      $sqlerro=true;
      $erro=$clissquant->erro_msg;
    }
  }
////////////////////////////////////////////////////////////////////////////////////////////////
//  db_msgbox($q02_inscr." inscr");
  if (!$sqlerro) {

    if (isset($q35_zona)&&$q35_zona!="") {

     $clisszona->q35_zona  = $q35_zona;
     $clisszona->q35_inscr = $clissbase->q02_inscr;
     $clisszona->incluir($clissbase->q02_inscr);
     if ($clisszona->erro_status==0) {

       $sqlerro=true;
       $erro=$clisszona->erro_msg;
     }
   }
 }
//////////////////////////////////////////////////////////////////////////////////////////////

  if (!$sqlerro) {

    if ($q14_proces!="") {

      $clissprocesso->q14_inscr  = $clissbase->q02_inscr;
      $clissprocesso->q14_proces = $q14_proces;
      $clissprocesso->incluir($clissbase->q02_inscr);
          //$clissprocesso->erro(true,false);
      if ($clissprocesso->erro_status==0) {

        $sqlerro = true;
        $erro    = $clissprocesso->erro_msg;
      }
    }
  }
  if (!$sqlerro) {

    if (@$q45_codporte!="") {

      $clissbaseporte->q45_inscr    = $clissbase->q02_inscr;
      $clissbaseporte->q45_codporte = $q45_codporte;
      $clissbaseporte->incluir($clissbase->q02_inscr);
          //$clissbaseporte->erro(true,false);
      if ($clissbaseporte->erro_status==0) {

        $sqlerro = true;
        $erro    = $clissbaseporte->erro_msg;
      }
    }
  }
  if (!$sqlerro) {

    if ($q10_numcgm!="") {

      $clescrito->q10_inscr  = $clissbase->q02_inscr;
      $clescrito->q10_numcgm = $q10_numcgm;
      $clescrito->incluir(null);
      if ($clescrito->erro_status==0) {

        $sqlerro=true;
        $erro=$clescrito->erro_msg;
      }
    }
  }


  if (!$sqlerro) {

    $clissruas->q02_inscr  = $clissbase->q02_inscr;
    $clissruas->j14_codigo = $j14_codigo;
    $clissruas->q02_numero = $q02_numero;
    $clissruas->q02_compl  = $q02_compl;
    $clissruas->q02_cxpost = $q02_cxpost;
    $clissruas->z01_cep    = $cepIssRuas;
    $clissruas->incluir($clissbase->q02_inscr);
    if ($clissruas->erro_status==0) {

      $sqlerro = true;
      $erro    = $clissruas->erro_msg;
    }
  }

  if(!$sqlerro){

    $clissbairro->q13_inscr  = $clissbase->q02_inscr;
    $clissbairro->q13_bairro = $j13_codi;
    $clissbairro->incluir($clissbase->q02_inscr);
    if($clissbairro->erro_status==0){

      $sqlerro=true;
      $erro=$clissbairro->erro_msg;
    }
  }
  if (!$sqlerro) {
  	  	// sanitario ..........
    if ($y80_codsani!="") {

      $clsanitarioinscr->y18_codsani = $y80_codsani ;
      $clsanitarioinscr->y18_inscr   = $clissbase->q02_inscr;
      $clsanitarioinscr->incluir($y80_codsani,$clissbase->q02_inscr) ;
      if ($clsanitarioinscr->erro_status==0) {

        $sqlerro = true;
        $erro    = $clsanitarioinscr->erro_msg;
      }
    }
  }
  if (strtoupper($munic)==strtoupper($z01_munic)) {

    if (!$sqlerro) {

      if ($q05_matric!="") {

        $clissmatric->q05_inscr  = $clissbase->q02_inscr;
        $clissmatric->q05_matric = $q05_matric;
        $clissmatric->q05_idcons = $q05_idcons;
        $clissmatric->incluir($clissbase->q02_inscr,$q05_matric);
        if ($clissmatric->erro_status==0) {

          $sqlerro = true;
          $erro    = $clissmatric->erro_msg;
        }
      }
    }
  }

  if (!$sqlerro) {

    try {

      $cllogincricao->identificaAlteracao($clissbase->q02_inscr,1,1);
      if (isset($q10_numcgm) && !empty($q10_numcgm)) {
        $cllogincricao->identificaAlteracao($clissbase->q02_inscr,1,9,$q10_numcgm);
      }

      $cllogincricao->gravarLog();
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
          $erro    = $clcgm->erro_msg;
        }
      }

    } else {

      $sqlerro = true;
      $erro    = $clcgm->erro_msg;
    }
  }

  db_fim_transacao($sqlerro);
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
    include("forms/db_frmissbasealt.php");
    ?>
  </div>
</body>
</html>
<?
if(isset($str_01) && $str_01!="" && !isset($incluir) && empty($q05_matric) && empty($first)){
  if($r==1){
   db_msgbox('CGM já esta sendo usado na inscrição '.$str_01);
 }else{
   db_msgbox('CGM já está sendo usado nas inscrições '.$str_01);
 }
}
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro);
  }else{
    $clissbase->erro(true,false);
    echo "<script>
    parent.document.formaba.observacao.disabled=false;
    parent.document.formaba.atividades.disabled=false;
    parent.document.formaba.socios.disabled=false;
    parent.document.formaba.calculo.disabled=false;
    parent.document.formaba.caracteristicas.disabled=false;
    top.corpo.iframe_observacao.location.href='iss1_issbase017.php?z01_nome=$z01_nome&q02_inscr=$clissbase->q02_inscr&Z01_numcgm=$q02_numcgm&opcao=1';
    top.corpo.iframe_atividades.location.href='iss1_tabativ004.php?z01_nome=$z01_nome&q07_inscr=$clissbase->q02_inscr';
    top.corpo.iframe_socios.location.href='iss1_socios004.php?q95_cgmpri=$q02_numcgm&z01_nome=$z01_nome';
    top.corpo.iframe_calculo.location.href='iss1_isscalc004.php?q07_inscr=$clissbase->q02_inscr&z01_nome=$z01_nome';
    top.corpo.iframe_documentos.location.href='iss1_isscalc004.php?q123_inscr=$clissbase->q02_inscr&z01_nome=$z01_nome';
    top.corpo.iframe_caracteristicas.location.href=\"iss4_issbasecaracteristicas001.php?q123_inscr=$clissbase->q02_inscr&z01_nome=$z01_nome\";\n
    parent.mo_camada('observacao');
  </script>";
  db_redireciona("iss1_issbase015.php?nomenu=nops&chavepesquisa=$clissbase->q02_inscr");
}
}
?>