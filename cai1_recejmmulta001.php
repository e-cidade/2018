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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tabrecjm_classe.php");
require_once("classes/db_tabrecjmmulta_classe.php");

db_postmemory($HTTP_POST_VARS);

$oDaoTabrecjm      = new cl_tabrecjm;
$oDaoTabrecjmmulta = new cl_tabrecjmmulta;
$db_opcao          = 1;
$db_botao          = false;

$k02_dtfrac_dia = null;
$k02_dtfrac_mes = null;
$k02_dtfrac_ano = null;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoTabrecjm->rotulo->label();
$oDaoTabrecjmmulta->rotulo->label();

if (isset($alterar) || isset($excluir) || isset($incluir) || isset($salvar)) {
  $sqlerro = false;
}

if (isset($incluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $oDaoTabrecjmmulta->incluir(null);
    $erro_msg = $oDaoTabrecjmmulta->erro_msg;

    if ($oDaoTabrecjmmulta->erro_status == "0") {
      $sqlerro = true;
    }
    
    if ($tipo_multa_atual == 1 and !$sqlerro) {

      $oDaoTabrecjm->k02_codjm  = $k140_tabrecjm;
      $oDaoTabrecjm->k02_limmul = '0';
      $oDaoTabrecjm->k02_mulfra = '0';
      $oDaoTabrecjm->k02_dtfrac_dia = null;
      $oDaoTabrecjm->k02_dtfrac_mes = null;
      $oDaoTabrecjm->k02_dtfrac_ano = null;
      $oDaoTabrecjm->k02_dtfrac     = null;

      $GLOBALS['HTTP_POST_VARS']['k02_dtfrac_dia'] = false;
      
      $k02_limmul               = '0';
      $k02_mulfra               = '0';
      $k02_dtfrac_dia = null;
      $k02_dtfrac_mes = null;
      $k02_dtfrac_ano = null;

      $oDaoTabrecjm->alterar($k140_tabrecjm);
      $erro_msg = $oDaoTabrecjm->erro_msg;
      
      if ($oDaoTabrecjm->erro_status == "0") {      
        $sqlerro = true;
      }
    }    
    
    db_fim_transacao($sqlerro);
  }
  
} elseif (isset($alterar)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $oDaoTabrecjmmulta->alterar($k140_sequencial);
    $erro_msg = $oDaoTabrecjmmulta->erro_msg;

    if ($oDaoTabrecjmmulta->erro_status == "0") {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
  }
  
} elseif (isset($excluir)) {

  if ($sqlerro == false) {

    db_inicio_transacao();
    $oDaoTabrecjmmulta->excluir($k140_sequencial);
    $erro_msg = $oDaoTabrecjmmulta->erro_msg;

    if ($oDaoTabrecjmmulta->erro_status == "0") {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
  }
  
} elseif (isset($salvar)) {
   
  if ($sqlerro == false) {
  
    db_inicio_transacao();
    $oDaoTabrecjm->k02_codjm = $k140_tabrecjm;
    $oDaoTabrecjm->alterar($k140_tabrecjm);
    $erro_msg = $oDaoTabrecjm->erro_msg;
  
    if ($oDaoTabrecjm->erro_status == "0") {
      $sqlerro = true;
    }
    
    if (!$sqlerro) {
      
      $oDaoTabrecjmmulta->excluir(null, "k140_tabrecjm = {$k140_tabrecjm}");
      
      if ($oDaoTabrecjmmulta->erro_status == "0") {
        $oDaoTabrecjmmulta->erro(true, false);
      }
      
    }
    
    $oDaoTabrecjm->erro(true, false);
        
    db_fim_transacao($sqlerro);
  }
  
} else {
   
   
   if (isset($opcao)) {
     $sSqlTipoMulta = $oDaoTabrecjmmulta->sql_query($k140_sequencial, "*", "k140_multa");
   } else {
     $sSqlTipoMulta = $oDaoTabrecjmmulta->sql_query(null, "*", "k140_multa", "k140_tabrecjm = {$k140_tabrecjm}");
   }
   $rTabrecjmmulta = $oDaoTabrecjmmulta->sql_record($sSqlTipoMulta);
   
   if ($rTabrecjmmulta != false && $oDaoTabrecjmmulta->numrows > 0) {
     
     if (isset($opcao)) {
       db_fieldsmemory($rTabrecjmmulta, 0);
     }
            
     $tipoMulta = 2;
             
   } else {
     
     $sSqlMultaDiaria = $oDaoTabrecjm->sql_query($k140_tabrecjm);
     $rMultaDiaria    = $oDaoTabrecjm->sql_record($sSqlMultaDiaria);
     
     if ($rMultaDiaria != false && $oDaoTabrecjm->numrows > 0) {
       
       $tipoMulta = 1;
       db_fieldsmemory($rMultaDiaria, 0);
       
       if ($k02_mulfra == 0 and $k02_limmul == 0 and $k02_dtfrac == null) {
         $tipoMulta = 0;
       }
     }
   }
}

if ( isset($k02_dtfrac) && $k02_dtfrac != '' && $tipoMulta == 1) {

  if( count(explode('-', $k02_dtfrac)) > 1 ) { 
    $dDataInicial = explode('-', $k02_dtfrac);
  } else {
    $dDataInicial = explode('/', $k02_dtfrac);
    $dDataInicial = array_reverse($dDataInicial);
  }

  $k02_dtfrac_dia = $dDataInicial[2];
  $k02_dtfrac_mes = $dDataInicial[1];
  $k02_dtfrac_ano = $dDataInicial[0];
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
<center>
<table width="790" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <?php
        include("forms/db_frmrecejmmulta.php");
      ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {

    db_msgbox($erro_msg);
    if ($oDaoTabrecjmmulta->erro_campo != "") {

        echo "<script> document.form1.".$oDaoTabrecjmmulta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoTabrecjmmulta->erro_campo.".focus();</script>";
    }
}
?>