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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_matestoque_classe.php");
require_once("classes/db_matestoqueitem_classe.php");
require_once("classes/db_matestoqueitemfabric_classe.php");
require_once("classes/db_matestoqueitemlote_classe.php");
require_once("classes/db_matestoquedevitemmei_classe.php");
require_once("classes/db_matestoquedevitem_classe.php");
require_once("classes/db_matestoquedev_classe.php");
require_once("classes/db_atendrequiitemmei_classe.php");
require_once("classes/db_atendrequiitem_classe.php");
require_once("classes/db_atendrequi_classe.php");
require_once("classes/db_matestoqueinimeiari_classe.php");
require_once("classes/db_matestoqueinimeimdi_classe.php");
require_once("classes/db_matrequi_classe.php");
require_once("classes/db_matrequiitem_classe.php");
require_once("classes/db_matestoqueini_classe.php");
require_once("classes/db_matestoqueinil_classe.php");
require_once("classes/db_matestoqueinill_classe.php");
require_once("classes/db_matestoquetransf_classe.php");
require_once("classes/db_matestoqueinimei_classe.php");
require_once("classes/db_matanulitemrequi_classe.php");
require_once("classes/db_matestoqueinimeipm_classe.php");
require_once("classes/db_matpedidotransf_classe.php");
require_once("classes/db_matestoqueinimeimatpedidoitem_classe.php");
require_once("classes/db_matmaterprecomedio_classe.php");
require_once("classes/db_matmaterprecomedioini_classe.php");
require_once("classes/db_matestoqueitemnota_classe.php");
require_once("classes/db_matestoqueitemoc_classe.php");
require_once("classes/db_matestoqueitemunid_classe.php");
require_once("classes/db_empnotaitembenspendente_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_matestoquetransferencia_classe.php");
require_once("classes/db_conlancammatestoqueinimei_classe.php");
require_once("classes/db_matestoqueitemnotafiscalmanual_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueitemfabric = new cl_matestoqueitemfabric;
$clmatestoqueitemlote = new cl_matestoqueitemlote;
$clmatestoquedevitemmei = new cl_matestoquedevitemmei;
$clmatestoquedevitem = new cl_matestoquedevitem;
$clmatestoquedev = new cl_matestoquedev;
$clatendrequiitemmei = new cl_atendrequiitemmei;
$clatendrequiitem = new cl_atendrequiitem;
$clatendrequi = new cl_atendrequi;
$clmatestoqueinimeiari = new cl_matestoqueinimeiari;
$clmatestoqueinimeimdi = new cl_matestoqueinimeimdi;
$clmatrequi = new cl_matrequi;
$clmatrequiitem = new cl_matrequiitem;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatestoqueinimeipm = new cl_matestoqueinimeipm;
$clmatmaterprecomedio  = new cl_matmaterprecomedio;
$clmatmaterprecomedioini  = new cl_matmaterprecomedioini;
$clmatestoqueitemnota = new cl_matestoqueitemnota;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatestoqueitemunid = new cl_matestoqueitemunid;
$clmatestoquetransf = new cl_matestoquetransf;
$clmatpedidotransf = new cl_matpedidotransf;
$cl_matestoquepeditoitem = new cl_matestoqueinimeimatpedidoitem;
$cl_empnotaitembenspendente = new cl_empnotaitembenspendente;
$clmatanulitemrequi = new cl_matanulitemrequi;
$clconlancammatestoqueinimei = new cl_conlancammatestoqueinimei();
$clmatestoqueitemnotafiscalmanual = new cl_matestoqueitemnotafiscalmanual();


$db_botao = true;
$db_opcao = 1;
$clrotulo = new rotulocampo();
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");


if (isset ($excluir)) {

  db_inicio_transacao();
  if ( !empty($m60_codmater) || !empty($codigos_materiais) ){
    $codmatmater = $m60_codmater;
    if (!empty($codigos_materiais)){
      $codmatmater = $codigos_materiais;
    }
  }else{
    db_msgbox("Material não informado!!");
    echo "<script>location.href='mat4_zeraestitem001.php';</script>";
  }
  $sqlerro = false;
  $erro_msg = "";
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $vir = "";
  $cod = "";
  $codmei = "";
  $coditem = "";
  $info = "";
  $sSqlDevItemMei = $clmatestoquedevitem->sql_query_file(null,"*",null,"m46_codmatmater in ({$codmatmater})");
  $result_mei     = $clmatestoquedevitemmei->sql_record($sSqlDevItemMei);

  for ($w = 0; $w < $clmatestoquedevitemmei->numrows; $w ++) {
    db_fieldsmemory($result_mei, $w);
    $codmei  .= $vir.$m46_codigo;
    $coditem .= $vir.$m46_codigo;
    $vir = ",";
  }
  if ($sqlerro == false) {
    if ($codmei!=""){
      $clmatestoquedevitemmei->excluir(null, "m47_codmatestoquedevitem in ($codmei)");
      if ($clmatestoquedevitemmei->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoquedevitemmei->erro_msg;
        db_msgbox("Erro!!MATESTOQUEDEVITEMMEI!!");
      }
    }
  }
  $vir = "";
  if ($coditem!="") {

    $sSqlDevItem = $clmatestoquedevitem->sql_query_file(null, "*", null, "m46_codigo in ($coditem)");
    $result_item = $clmatestoquedevitem->sql_record($sSqlDevItem);

    for ($w = 0; $w < $clmatestoquedevitem->numrows; $w ++) {

      db_fieldsmemory($result_item, $w);
      $cod .= $vir.$m46_codmatestoquedev;
      $vir = ",";
    }
  }
  if ($sqlerro == false) {
    if ($coditem!=""){

      $clmatestoqueinimeimdi->excluir(null, "m50_codmatestoquedevitem in ($coditem)");
      if ($clmatestoqueinimeimdi->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoqueinimeimdi->erro_msg;
        db_msgbox("Erro!!matestoqueinimeimdi!!");
      }
    }
  }

  if ($sqlerro == false) {
    if ($coditem!=""){

      $clmatestoquedevitem->excluir(null, "m46_codigo in ($coditem)");
      if ($clmatestoquedevitem->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoquedevitem->erro_msg;
        db_msgbox("Erro!!MATESTOQUEDEVITEM!!");
      }
    }
  }
  $info = split(",",$cod);
  for ($w = 0; $w < count($info); $w++) {

    if ($sqlerro == false) {
      if ($info[$w] != "") {

        $sSqlDev = "select * from matestoquedevitem where m46_codmatestoquedev = ".$info[$w];
        $rsDev   = db_query($sSqlDev);
        if (pg_num_rows($rsDev) > 0) {
          continue;
        }

        $clmatestoquedev->excluir(null, "m45_codigo =".$info[$w]);
         if ($clmatestoquedev->erro_status == 0) {

           $sqlerro  = true;
          $erro_msg = $clmatestoquedev->erro_msg;
          db_msgbox("Erro!!MATESTOQUEDEV!!{$erro_msg}");
        }
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $vir = "";
  $cod = "";
  $codmei = "";
  $coditem = "";
  $info = "";
  $sSqlItemAtendidos  = $clatendrequiitem->sql_query(null, "*", null, "m41_codmatmater in ($codmatmater)");
  $result_mei = $clatendrequiitem->sql_record($sSqlItemAtendidos);
  for ($w = 0; $w < $clatendrequiitem->numrows; $w ++) {
    db_fieldsmemory($result_mei, $w);
    $coditem .= $vir.$m43_codigo;
    $vir = ",";
  }
  if ($sqlerro == false) {
    if ($coditem!=""){

      $clatendrequiitemmei->excluir(null, "m44_codatendreqitem in ($coditem)");
      if ($clatendrequiitemmei->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clatendrequiitemmei->erro_msg;
        db_msgbox("Erro!!atendrequiITEMMEI!!");
      }
    }
  }
  $vir = "";
  if ($coditem!=""){
    $sSql = $clatendrequiitem->sql_query_file(null, "*", null, "m43_codigo in ($coditem)");

    $result_item = $clatendrequiitem->sql_record($sSql);
    for ($w = 0; $w < $clatendrequiitem->numrows; $w ++) {
      db_fieldsmemory($result_item, $w);
      $cod .= $vir.$m43_codatendrequi;
      $vir = ",";
    }
  }
  if ($sqlerro == false) {
    if ($coditem!=""){

      $clmatestoqueinimeiari->excluir(null, "m49_codatendrequiitem in ($coditem)");
      if ($clmatestoqueinimeiari->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoqueinimeiari->erro_msg;
        db_msgbox("Erro!!matestoqueinimeiari!!");
      }
    }
  }
  if ($sqlerro == false) {

    if ($coditem!=""){

      $clatendrequiitem->excluir(null, "m43_codigo in ($coditem)");
      if ($clatendrequiitem->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clatendrequiitem->erro_msg;
        db_msgbox("Erro!!{$coditem} atendrequiITEM!!\\n{$erro_msg}");
      }
    }
  }
  $info=split(",",$cod);
  for($w=0;$w<count($info);$w++){
    if ($info[$w]!=""){
      $result_atenditem = $clatendrequiitem->sql_record($clatendrequiitem->sql_query_file(null, "*", null, "m43_codatendrequi=".$info[$w]));
      if ($clatendrequiitem->numrows==0){
        if ($sqlerro == false) {

          $clatendrequi->excluir(null, "m42_codigo =".$info[$w] );
          if ($clatendrequi->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clatendrequi->erro_msg;
            db_msgbox("Erro!!atendrequi!!\\n{$erro_msg}");
          }
        }
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $vir = "";
  $cod = "";
  $sCodMatRequiItem = "";
  $info = "";
  $sSql = $clmatrequiitem->sql_query_file(null,"*",null,"m41_codmatmater in ($codmatmater)");

  $result_item = $clmatrequiitem->sql_record($sSql);

  for ($w = 0; $w < $clmatrequiitem->numrows; $w++) {
    db_fieldsmemory($result_item, $w);
    $cod .= $vir.$m41_codmatrequi;
    $codMatRequiItem .= $vir . $m41_codigo;
    $vir=",";
  }

  $info = split(",", $cod);

  for($w = 0; $w < count($info); $w++) {

    if ($info[$w] != "") {

      if ($sqlerro == false) {

        $clmatanulitemrequi->excluir(null,"m102_matrequiitem in (".$codMatRequiItem.")");

        if ($clmatanulitemrequi->erro_status == 0) {

          $sqlerro  = true;
          $erro_msg = $clmatanulitemrequi->erro_msg;
          db_msgbox("Erro!!matanulitemrequi!!");
        }
      }
    }
  }

  if ($sqlerro == false) {

    $clmatrequiitem->excluir(null,"m41_codmatmater in ($codmatmater)");
    if ($clmatrequiitem->erro_status==0){
      $sqlerro=true;
      $erro_msg = $clmatrequiitem->erro_msg;
      db_msgbox("Erro!!MATREQUIITEM!!");
    }
  }
  $info=split(",",$cod);
  for($w=0;$w<count($info);$w++){
    if ($info[$w] != "") {
      $result_matrequiitem = $clmatrequiitem->sql_record($clmatrequiitem->sql_query_file(null,"*",null,"m41_codmatrequi=".$info[$w]));
      if ($clmatrequiitem->numrows==0){
        if ($sqlerro == false) {
          if($info[$w]!=""){
            $clmatrequi->excluir(null,"m40_codigo=".$info[$w]);
            if ($clmatrequi->erro_status==0){
              $sqlerro=true;
              $erro_msg = $clmatrequi->erro_msg;
              db_msgbox("Erro!!MATREQUI!!");
            }
          }
        }
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $vir = "";
  $cod = "";
  $codmei = "";
  $info = "";

  $result_mei = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query(null,"distinct m82_codigo",null,"m70_codmatmater in ($codmatmater)"));
  for($w=0;$w<$clmatestoqueinimei->numrows;$w++){
    db_fieldsmemory($result_mei,$w);
    $codmei.=$vir.$m82_codigo;
    $vir=",";
  }

  $vir = "";
  $result_mei = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query(null,"distinct m82_matestoqueini",null,"m70_codmatmater in ($codmatmater)"));
  for($w=0;$w<$clmatestoqueinimei->numrows;$w++){
    db_fieldsmemory($result_mei,$w);
    $cod.=$vir.$m82_matestoqueini;
    $vir=",";
  }
  if ($sqlerro == false) {
    if ($codmei!=""){

      $clmatestoqueinimeipm->excluir(null, "m89_matestoqueinimei in ($codmei)");
      if ($clmatestoqueinimeipm->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoqueinimeipm->erro_msg;
        db_msgbox("Erro!!matestoqueinimeipm!!");
      }
    }
  }
  if ($sqlerro == false) {
    if ($codmei!=""){

      $cl_matestoquepeditoitem->excluir(null, "m99_matestoqueinimei in ($codmei)");
      if ($cl_matestoquepeditoitem->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cl_matestoquepeditoitem->erro_msg;
        db_msgbox("Erro!!matestoqueinimeimatpedidoitem!!");
      }
    }
  }

  if ($sqlerro == false) {
    if ($codmei!=""){

      $clmatestoqueinimei->excluir(null, "m82_codigo in ($codmei)");
      if ($clmatestoqueinimei->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $clmatestoqueinimei->erro_msg;
        db_msgbox("Erro!!matestoqueinimei!!\\n{$erro_msg}");
      }
    }
  }
  $info=split(",",$cod);
  $vir="";
  $codini="";
  for($w=0;$w<count($info);$w++){
    if($info[$w]!=""){
      $result_matestoqueinimei = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_file(null,"*",null,"m82_matestoqueini=".$info[$w]));
      if ($clmatestoqueinimei->numrows==0){
        $codini.=$vir.$info[$w];
        $vir=",";
      }
    }
  }
  if ($codini!=""){
    if ($sqlerro == false) {
      $clmatestoqueinill->excluir(null,"m87_matestoqueini in ($codini)");
      if ($clmatestoqueinill->erro_status==0){
        $sqlerro = true;
        $erro_msg = $clmatestoqueinill->erro_msg;
        db_msgbox("Erro!!matestoqueinill!!");
      }
    }
    if ($sqlerro == false) {
      $clmatestoqueinil->excluir(null,"m86_matestoqueini in ($codini)");
      if ($clmatestoqueinil->erro_status==0){
        $sqlerro = true;
        $erro_msg = $clmatestoqueinil->erro_msg;
        db_msgbox("Erro!!matestoqueinil!!");
        db_msgbox($erro_msg);
      }
    }
    if ($sqlerro == false) {

      $clmatestoquetransf->excluir(null,"m83_matestoqueini in ($codini)");
      if ($clmatestoquetransf->erro_status==0){
        $sqlerro = true;
        $erro_msg = $clmatestoquetransf->erro_msg;
        db_msgbox("Erro!!matestoquetransf!!");
        db_msgbox($erro_msg);
      }
    }
    if ($sqlerro == false) {

      $clmatpedidotransf->excluir(null,"m100_matestoqueini in ($codini)");
      if ($clmatpedidotransf->erro_status==0){
        $sqlerro = true;
        $erro_msg = $clmatpedidotransf->erro_msg;
        db_msgbox("Erro!!matpedidotransf!!");
        db_msgbox($erro_msg);
      }
    }
    if ($sqlerro == false) {

      $clmatmaterprecomedioini->excluir(null, "m88_matestoqueini in ($codini)");
      if ($clmatmaterprecomedioini->erro_status==0) {

        $sqlerro = true;
        $erro_msg = $clmatmaterprecomedioini->erro_msg;
        db_msgbox("Erro!!matmaterprecomedioini!!");
        db_msgbox($erro_msg);
      }
    }

    if ($sqlerro == false) {

      $sSqlMatestoqueinimei = "select m82_codigo from matestoqueinimei where m82_matestoqueini in({$codini})";
    	$clconlancammatestoqueinimei->excluir(null, "c103_matestoqueinimei in ({$sSqlMatestoqueinimei})");

    	if ($clconlancammatestoqueinimei->erro_status==0){
        
    		$sqlerro = true;
    		$erro_msg = $clconlancammatestoqueinimei->erro_msg;
    		db_msgbox("Erro!!conlancammatestoqueinimei!");
    		db_msgbox($erro_msg);
    	}

    	if ($sqlerro == false) {
    	
    	  $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
    	  $oDaoMatEstoqueTransferencia->excluir(null, " m84_matestoqueini in ({$codini})");
    	  if ($oDaoMatEstoqueTransferencia->erro_status == 0) {
    	
    	    $sqlerro   = true;
    	    $erro_msg  = "Erro ao excluir da tabela matestoquetransferencia.";
    	    $erro_msg .= $oDaoMatEstoqueTransferencia->erro_msg;
    	    db_msgbox($erro_msg);
    	  }
    	}
    	
    	
      $clmatestoqueini->excluir(null,"m80_codigo in ($codini)");
      if ($clmatestoqueini->erro_status==0){
        $sqlerro = true;
        $erro_msg = $clmatestoqueini->erro_msg;
        db_msgbox("Erro!!matestoqueini!!");
        db_msgbox($erro_msg);
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $result_item = $clmatestoqueitemnota->sql_record($clmatestoqueitemnota->sql_query(null,null,"*",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitemnota->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clmatestoqueitemnota->excluir($m74_codmatestoqueitem);
      if ($clmatestoqueitemnota->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitemnota->erro_msg;
        db_msgbox("Erro!!MATESTOQUEITEMNOTA!!");
        db_msgbox($erro_msg);
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $result_item = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"*",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitemoc->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clmatestoqueitemoc->excluir($m73_codmatestoqueitem);
      if ($clmatestoqueitemoc->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitemoc->erro_msg;
        db_msgbox("Erro!!MATESTOQUEITEMoc!!");
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $result_item = $clmatestoqueitemunid->sql_record($clmatestoqueitemunid->sql_query(null,"*",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitemunid->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clmatestoqueitemunid->excluir($m75_codmatestoqueitem);
      if ($clmatestoqueitemunid->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitemunid->erro_msg;
        db_msgbox("Erro!!matestoqueitemunid!!");
      }
    }
  }
    $result_item = $clmatestoqueitemlote->sql_record($clmatestoqueitemlote->sql_query(null,"*",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitemlote->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clmatestoqueitemlote->excluir($m77_sequencial);
      if ($clmatestoqueitemlote->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitemlote->erro_msg;
        db_msgbox("Erro!!matestoqueitemlote!!");
      }
    }
  }

  $result_item = $clmatestoqueitemfabric->sql_record($clmatestoqueitemfabric->sql_query_cgm(null,"m78_sequencial",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitemfabric->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $clmatestoqueitemfabric->excluir($m78_sequencial);
      if ($clmatestoqueitemfabric->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitemfabric->erro_msg;
        db_msgbox("Erro!!matestoqueitemfabric!!");
      }
    }
  }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $result_item = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query(null,"*",null,"m70_codmatmater in ($codmatmater)"));
  $numrows=$clmatestoqueitem->numrows;
  for($w=0;$w<$numrows;$w++){
    db_fieldsmemory($result_item,$w);
    if ($sqlerro==false){
      $cl_empnotaitembenspendente->excluir(null,"e137_matestoqueitem = $m71_codlanc");
      if ($cl_empnotaitembenspendente->erro_status==0){
        $sqlerro=true;
        $erro_msg=$cl_empnotaitembenspendente->erro_msg;
        db_msgbox("Erro!!empnotaitembenspendente!!");
        echo pg_last_error();
      }
    }

    if ($sqlerro == false) {

      $oDaoMatEstoqueTransferencia = db_utils::getDao("matestoquetransferencia");
      $oDaoMatEstoqueTransferencia->excluir(null, " m84_matestoqueitem = {$m71_codlanc}");
      if ($oDaoMatEstoqueTransferencia->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg  = "Erro ao excluir da tabela matestoquetransferencia.";
        $erro_msg .= $oDaoMatEstoqueTransferencia->erro_msg;
        db_msgbox($erro_msg);
      }
    }

    if ($sqlerro == false) {

      $oDaoMatEstoqueItemNotaFiscalManual = db_utils::getDao("matestoqueitemnotafiscalmanual");
      $oDaoMatEstoqueItemNotaFiscalManual->excluir(null, " m79_matestoqueitem = {$m71_codlanc}");
      if ($oDaoMatEstoqueItemNotaFiscalManual->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg  = "Erro ao excluir da tabela matestoqueitemnotafiscalmanual.";
        $erro_msg .= $oDaoMatEstoqueItemNotaFiscalManual->erro_msg;
        db_msgbox($erro_msg);
      }
    }

    if ($sqlerro==false){
      $clmatestoqueitem->excluir($m71_codlanc);
      if ($clmatestoqueitem->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clmatestoqueitem->erro_msg;
        db_msgbox("Erro!!matestoqueitem!!");
        echo pg_last_error();
      }
    }
  }
  if ($sqlerro==false){
    $clmatestoque->excluir(null,"m70_codmatmater in ($codmatmater)");
    if ($clmatestoque->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clmatestoque->erro_msg;
      db_msgbox("Erro!!matestoque!!");
    }
  }
  if ($sqlerro==false) {

    $sQuery = "delete from matmaterprecomedioini using matmaterprecomedio where m85_sequencial = m88_matmaterprecomedio
               and  m85_matmater in ($codmatmater) ";
    $rsdelete = db_query($sQuery);
    if (!$rsdelete) {

      $sqlerro=true;
      $erro_msg=pg_last_error();
      db_msgbox("Erro!!matmaterprecomedioini!!$erro_msg");
    }
  }
  if ($sqlerro==false) {

    $clmatmaterprecomedio->excluir(null,"m85_matmater in ($codmatmater)");
    if ($clmatmaterprecomedio->erro_status==0){

      $sqlerro=true;
      $erro_msg=$clmatmaterprecomedio->erro_msg;
      db_msgbox("Erro!!matmaterprecomedio!!$erro_msg");
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
  <?


if (db_getsession('DB_login') != 'dbseller') {
  db_msgbox("Rotina administrativa do sistema ! Acesso restrito !");
}else{
  include ("forms/db_frmzeraestitem.php");
}
?>
    </center>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?



if (isset ($excluir)) {
  if ($clmatestoque->erro_status == "0") {
    $clmatestoque->erro(true, false);
  } else {
    $clmatestoque->erro(true, true);
  };
};
?>
