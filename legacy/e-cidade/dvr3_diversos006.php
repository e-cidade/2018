<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$cldiversos   = new cl_diversos;
$clcgm        = new cl_cgm;

$db_opcao     = 22;
$db_botao     = false;
$lErro        = false;
$sMsgErro     = '';

if ( isset($subtes) && $subtes=="ok" || isset($HTTP_POST_VARS["db_opcao"]) ) {
  $db_botao = true;
  $db_opcao = 2;
}

/**
 * Alteração
 */
if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar" ) {

  db_inicio_transacao();
  $sqlerro = false;

  $cldiversos->dv05_instit = db_getsession('DB_instit');

  /**
   * Objeto antes da alteração
   */
  $rsDiversoOld = $cldiversos->sql_record($cldiversos->sql_query_file($dv05_coddiver,"*",null," dv05_instit = ".db_getsession('DB_instit')." and dv05_coddiver = $dv05_coddiver "));
  $oDiversoOld  = db_utils::fieldsMemory($rsDiversoOld, 0);

  $cldiversos->alterar($dv05_coddiver);
  if($cldiversos->erro_status == '0'){
    $sqlerro = true;
  }

  /**
   * Objeto depois da alteração
   */
  $rsDiversoNew = $cldiversos->sql_record($cldiversos->sql_query_file($dv05_coddiver,"*",null," dv05_instit = ".db_getsession('DB_instit')." and dv05_coddiver = $dv05_coddiver "));
  $oDiversoNew  = db_utils::fieldsMemory($rsDiversoNew, 0);

  /**
   * Verifica se houve alteração do diverso, deve gerar numpre novo
   */
  $lGerarNovoNumpre = 'false';
  $lNaoGerarArreold = 'true';
  if( hasDiffObject($oDiversoOld, $oDiversoNew) ){
    $lGerarNovoNumpre = 'true';
    $lNaoGerarArreold = 'false';
  }

  $sSqlArretipo  = " select dv09_tipo as arretipo              ";
  $sSqlArretipo .= "   from procdiver                          ";
  $sSqlArretipo .= "  where dv09_procdiver = {$dv05_procdiver} ";
  $sSqlArretipo .= "    and dv09_instit    = ".db_getsession('DB_instit');
  $rsArretipo  = db_query($sSqlArretipo);

  if ( pg_num_rows($rsArretipo) > 0 ){
    db_fieldsmemory($rsArretipo,0);
  } else {

    db_msgbox(_M("tributario.diversos.db_frmdiversosalt.configure_tipo_debitos_destino"));
    db_redireciona("dvr3_diversos005.php");
    exit;
  }

  $sSqlGeraArrecad = "select fc_geraarrecad($arretipo, $dv05_numpre, $lNaoGerarArreold, 1, $lGerarNovoNumpre)";
  db_query($sSqlGeraArrecad) or die("Erro ao alterar em arrecad.");

  db_fim_transacao($sqlerro);

} else if(isset($chavepesquisa)) {

   $db_opcao = 2;
   $db_botao = true;

   $result   = $cldiversos->sql_record($cldiversos->sql_query_file($chavepesquisa,"*",null," dv05_instit = ".db_getsession('DB_instit')." and dv05_coddiver = $chavepesquisa "));
   db_fieldsmemory($result,0);

   $result44 = $clcgm->sql_record($clcgm->sql_query_file($dv05_numcgm,"z01_nome"));
   db_fieldsmemory($result44,0);

   $venc     = $dv05_privenc_ano."-".$dv05_privenc_mes."-".$dv05_privenc_dia;

   $sSqlProcdiver = " select tabrecjm.k02_corr,                                               ";
   $sSqlProcdiver.= "        procdiver.dv09_receit                                            ";
   $sSqlProcdiver.= "   from procdiver                                                        ";
   $sSqlProcdiver.= "        inner join tabrec   on tabrec.k02_codigo  = procdiver.dv09_receit";
   $sSqlProcdiver.= " 		   inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm     ";
   $sSqlProcdiver.= "  where dv09_instit = ".db_getsession('DB_instit')."                     ";
   $sSqlProcdiver.= "    and procdiver.dv09_procdiver = {$dv05_procdiver}                     ";

   $result03 = db_query($sSqlProcdiver);
   db_fieldsmemory($result03,0);

   $i02_codigo = $k02_corr;
   $sql_valida = "   select dv05_coddiver                                   ";
   $sql_valida.= "     from arrecad                                         ";
   $sql_valida.= "          inner join diversos on k00_numpre = dv05_numpre ";
   $sql_valida.= "    where dv05_coddiver = {$chavepesquisa}                ";
   $sql_valida.= " group by dv05_coddiver,dv05_numtot                       ";
   $sql_valida.= "   having count(k00_numpar) = dv05_numtot                 ";

   $cldiversos->sql_record($sql_valida);

   if($cldiversos->numrows==0){

     $sMsgErro = _M('tributario.diversos.db_frmdiversosalt.verifique_situacao_geral_financeira');
     $lErro    = true;

   } else {

     $sSqlPgtoParcial = "select fc_verifica_abatimento(1,( select dv05_numpre
                                                            from diversos
                                                           where dv05_coddiver = {$chavepesquisa}
                                                           limit 1 )) as pgtoparcial  ";
     $rsPgtoParcial = db_query($sSqlPgtoParcial);

     if (!$rsPgtoParcial || pg_num_rows($rsPgtoParcial) == 0 ) {

       $sMsgErro = _M('tributario.diversos.db_frmdiversosalt.verificar_pagamento_parcial');
       $lErro    = true;

     } else {

       $lPgtoParcial  = db_utils::fieldsMemory($rsPgtoParcial,0)->pgtoparcial;

       if ($lPgtoParcial == 't') {

         $sMsgErro = _M('tributario.diversos.db_frmdiversosalt.existe_pagamento_parcial');
         $lErro    = true;
       }
     }
   }

   if ( $lErro ) {

     $db_opcao = 22;
     $db_botao = false;
   }

}

$HTTP_SERVER_VARS['QUERY_STRING']="";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<?php

  include("forms/db_frmdiversosalt.php");
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?php

if ( $lErro ) {
  db_msgbox($sMsgErro);
}

if( $db_opcao==22 && !$lErro ) {
  echo "<script>js_pesquisa();</script>";
}

if( isset($HTTP_POST_VARS["db_opcao"]) ) {

  if( $cldiversos->erro_status == "0" ) {

    $cldiversos->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if( $cldiversos->erro_campo != "" ) {

      echo "<script> document.form1.".$cldiversos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldiversos->erro_campo.".focus();</script>";
    }

  }else{

    $cldiversos->erro(true,false);
    db_redireciona("dvr3_diversos006.php");
  }
}

/**
 * Verifica de existe alguma diferença entre os dois objetos apartir das
 * propriedades do primeiro objeto passado por parâmetro
 *
 * @param  objetc $oObject1
 * @param  object $oObject2
 * @return boolean
 */
function hasDiffObject($oObject1,$oObject2){

  $aPropriedades = get_object_vars($oObject1);
  $lDiff         = false;

  foreach ( $aPropriedades as $sNome => $sValor ) {

    if ( isset($oObject1->$sNome) && isset($oObject2->$sNome) ){
      if ( $oObject1->$sNome != $oObject2->$sNome ) {
        $lDiff = true;
      }
    }

  }

  return $lDiff;

}
?>