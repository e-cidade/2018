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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_socios_classe.php");
require_once("classes/db_tabativ_classe.php");
require_once("classes/db_issmatric_classe.php");
require_once("classes/db_escrito_classe.php");
require_once("classes/db_issbairro_classe.php");
require_once("classes/db_bairro_classe.php");
require_once("classes/db_issquant_classe.php");
require_once("classes/db_isszona_classe.php");
require_once("classes/db_issruas_classe.php");
require_once("classes/db_issprocesso_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptuconstr_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_db_cgmruas_classe.php");
require_once("classes/db_db_cgmcpf_classe.php");
require_once("classes/db_db_cgmbairro_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issbaseporte_classe.php");
require_once("classes/db_issporte_classe.php");
require_once("model/logInscricao.model.php");
require_once("classes/db_issbasecaracteristica_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clissporte              = new cl_issporte;
$clissbaseporte          = new cl_issbaseporte;
$clcgm		               = new cl_cgm;
$clescrito       	       = new cl_escrito;
$cldb_cgmcpf 	           = new cl_db_cgmcpf;
$clarreinscr 	           = new cl_arreinscr;
$clissbase	             = new cl_issbase;
$cltabativ	             = new cl_tabativ;
$clsocios	               = new cl_socios;
$clissmatric 	           = new cl_issmatric;
$clissprocesso           = new cl_issprocesso;
$clprotprocesso          = new cl_protprocesso;
$clissbairro 	           = new cl_issbairro;
$clbairro 	             = new cl_bairro;
$clissruas 	             = new cl_issruas;
$clissquant 	           = new cl_issquant;
$clisszona 	             = new cl_isszona;
$cliptuconstr	           = new cl_iptuconstr;
$cliptubase	             = new cl_iptubase;
$cldb_cgmruas 	         = new cl_db_cgmruas;
$cldb_config	           = new cl_db_config;
$cldb_cgmbairro          = new cl_db_cgmbairro;
$clrotulo 	             = new rotulocampo;
$cllogincricao           = new loginscricao;
$clissbasecaracteristica = new cl_issbasecaracteristica;

$clissquant->rotulo->label();
$db_opcao = 33;
$db_botao = true;
$data=db_getsession("DB_datausu");

$result02=$cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"),"munic"));
db_fieldsmemory($result02,0);

if(isset($excluir)) {

  $sqlerro = false;
  db_inicio_transacao();

  $cltabativ->sql_record($cltabativ->sql_query_file($q02_inscr,"",'q07_inscr'));
  if ($cltabativ->numrows>0) {

    $cltabativ->q07_inscr=$q02_inscr;
    $cltabativ->excluir($q02_inscr);
    if($cltabativ->erro_status==0){
      $sqlerro=true;
    }
  }

  if (!$sqlerro) {
    $rsIssbasecaracteristicas = $clissbasecaracteristica->excluir(null, "q138_inscr = {$q02_inscr}");
    if ($clissbasecaracteristica->erro_status == "0") {
      $sqlerro=true;
    }
  }

  if(!$sqlerro){

    $result13  = $clissquant->sql_record($clissquant->sql_query_file("",$q02_inscr));
    $numrows13 = $clissquant->numrows;
    for ($i=0; $i<$numrows13; $i++) {

      db_fieldsmemory($result13,0);
      $clissquant->q30_anousu = $q30_anousu;
      $clissquant->q30_inscr  = $q02_inscr;

      $clissquant->excluir($q30_anousu,$q02_inscr);
          //$clissquant->erro(true,false);
      if($clissquant->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  if (!$sqlerro) {

    $resultzona=$clisszona->sql_record($clisszona->sql_query_file($q02_inscr,"*","",""));
    if ($clisszona->numrows>0) {

      $clisszona->q35_zona = $q35_zona;
      $clisszona->q35_inscr = $q02_inscr;
      $clisszona->excluir($q02_inscr);
    }
    if ($clisszona->erro_status==0) {

      $sqlerro=true;
      $erro=$clisszona->erro_msg;
    }
  }
  if (!$sqlerro) {

    $result12=$clissprocesso->sql_record($clissprocesso->sql_query_file($q02_inscr,"q14_proces"));
    if ($clissprocesso->numrows>0) {

      $clissprocesso->q14_inscr=$q02_inscr;
      $clissprocesso->q14_proces=$q14_proces;
      $clissprocesso->excluir($q02_inscr);
          //$clissprocesso->erro(true,false);
      if($clissprocesso->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  if (!$sqlerro) {

    $result_baseporte=$clissbaseporte->sql_record($clissbaseporte->sql_query_file($q02_inscr,"q45_codporte"));
    if ($clissbaseporte->numrows>0) {

      $clissbaseporte->q45_inscr=$q02_inscr;
      $clissbaseporte->excluir($q02_inscr);
          //$clissbaseporte->erro(true,false);
      if($clissbaseporte->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  if(!$sqlerro){

    $result10=$clescrito->sql_record($clescrito->sql_query($q02_inscr,"","q10_numcgm as xq10_numcgm"));
    if ($clescrito->numrows>0) {

      db_fieldsmemory($result10,0);
      $clescrito->q10_inscr  = $q02_inscr;
      $clescrito->q10_numcgm = $xq10_numcgm;
      $clescrito->excluir($q02_inscr,$xq10_numcgm);
          //$clescrito->erro(true,false);
      if($clescrito->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  if (!$sqlerro) {

    $result13=$clissruas->sql_record($clissruas->sql_query_file($q02_inscr));
    if ($clissruas->numrows>0) {

      $clissruas->q02_inscr  = $q02_inscr;
      $clissruas->q02_numero = $q02_numero;
      $clissruas->q02_compl  = $q02_compl;
      $clissruas->q02_cxpost = $q02_cxpost;
      $clissruas->z01_cep    = $cepIssRuas;
      $clissruas->excluir($q02_inscr);
            //$clissruas->erro(true,false);
      if($clissruas->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  if (!$sqlerro) {

    $result09=$clissbairro->sql_record($clissbairro->sql_query_file($q02_inscr,"q13_bairro as x"));
    if ($clissbairro->numrows > 0) {

      db_fieldsmemory($result09, 0);
      $clissbairro->q13_inscr  = $q02_inscr;
      $clissbairro->q13_bairro = $x;
      $clissbairro->excluir($q02_inscr);
            //$clissbairro->erro(true,false);
      if($clissbairro->erro_status==0){
        $sqlerro=true;
      }
    }
  }
  if(!$sqlerro) {

  	$result07 = $clissmatric->sql_record($clissmatric->sql_query_file($q02_inscr,'','q05_matric as c,q05_idcons as s'));
    if ($clissmatric->numrows>0) {

      db_fieldsmemory($result07,0);
      $clissmatric->q05_inscr  = $q02_inscr;
      $clissmatric->q05_matric = $c;
      $clissmatric->q05_idcons = $s;
      $clissmatric->excluir($q02_inscr,$c);
            //$clissmatric->erro(true,false);
      if($clissmatric->erro_status==0){
        $sqlerro=true;
      }
    }
  }

  if(!$sqlerro) {

    $clissbase->q02_inscr=$q02_inscr;
    $clissbase->excluir($q02_inscr);
          //$clissbase->erro(true,false);
    if($clissbase->erro_status==0){
      $sqlerro=true;
    }
  }

  if (!$sqlerro) {

    try {

      if (isset($q30_area) && !empty($q30_area)) {

        if (isset($xq10_numcgm) && !empty($xq10_numcgm)) {
           $cllogincricao->identificaAlteracao($clissbase->q02_inscr,3,10,$xq10_numcgm);
        }
        $cllogincricao->gravarLog();
      }
    } catch ( Exception $eExeption ){

      $sqlerro = true;
      $erro    = $eExeption->getMessage();
    }
  }

  db_fim_transacao($sqlerro);
} elseif(isset($chavepesquisa)) {

  $result05 = $clissbase->sql_record($clissbase->sql_query($chavepesquisa,'issbase.q02_inscr,q02_dtcada,q02_numcgm,z01_nome,q02_regjuc,q02_memo'));
  db_fieldsmemory($result05,0);

  $result06=$clcgm->sql_record($clcgm->sql_query_file($q02_numcgm,"z01_nomefanta,z01_cep,z01_munic,z01_incest,z01_cgccpf,z01_ender,z01_bairro,z01_compl,z01_numero,z01_cxpostal"));
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


  $result09=$clissbairro->sql_record($clissbairro->sql_query($chavepesquisa,"q13_bairro as j13_codi,bairro.j13_descr"));
  if($clissbairro->numrows>0){
    db_fieldsmemory($result09,0);
  }

  $result10=$clescrito->sql_record($clescrito->sql_query(null,"q10_numcgm,cgm.z01_nome as z01_nome_escrito",""," q10_inscr = $chavepesquisa ") );
  if($clescrito->numrows>0){
   db_fieldsmemory($result10,0);
  }
  $result11=$clissquant->sql_record($clissquant->sql_query_file(date('Y',$data),$chavepesquisa,"q30_quant,q30_mult,q30_anousu,q30_area","q30_anousu desc"));
  if($clissquant->numrows>0){
   db_fieldsmemory($result11,0);
  }
  $resultzona=$clisszona->sql_record($clisszona->sql_query_file($q02_inscr,"*","",""));
  if($clisszona->numrows>0){
   db_fieldsmemory($resultzona,0);
  }
  $result12=$clissprocesso->sql_record($clissprocesso->sql_query_file($chavepesquisa,"q14_proces"));
  if($clissprocesso->numrows>0){
    db_fieldsmemory($result12,0);
  }
  $result_proc=$clprotprocesso->sql_record($clprotprocesso->sql_query_file(@$q14_proces,"p58_requer"));
  if($clprotprocesso->numrows>0){
    db_fieldsmemory($result_proc,0);
  }
  $result_baseporte=$clissbaseporte->sql_record($clissbaseporte->sql_query_file($q02_inscr,"q45_codporte"));
  if($clissbaseporte->numrows>0){
    db_fieldsmemory($result_baseporte,0);
  }
  $result_descrporte=$clissporte->sql_record($clissporte->sql_query_file(@$q45_codporte,"q40_descr"));
  if($clissporte->numrows>0){
    db_fieldsmemory($result_descrporte,0);
  }
  //  die($clarreinscr->sql_query_file("",$chavepesquisa,"k00_numpre"));
  $clarreinscr->sql_record($clarreinscr->sql_query_file("",$chavepesquisa,"k00_numpre"));

  $db_botao = true;
  $db_opcao = 3;
  if ($clarreinscr->numrows > 0) {

    $db_botao  = false;
    $numpods   = true;
  }
}

if ($db_opcao==33) {
  $db_botao=false;
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
if(isset($numpods) && $numpods==true){
  db_msgbox("Inscrição está sendo utilizada em outra tabela, portanto não poderá ser excluida!");
}
if(isset($excluir)){
  if($clissbase->erro_status=="0"){
    $clissbase->erro(true,false);
  }else{
    $clissbase->erro(true,false);
    echo "
    <script>
     parent.location.href='iss1_issbase006.php';
   </script>
   ";
 }
}
?>