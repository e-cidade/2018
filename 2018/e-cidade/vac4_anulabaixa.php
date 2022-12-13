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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_matparam_classe.php");
db_postmemory($HTTP_POST_VARS);
$oDaoVacDevolucao     = db_utils::getdao('vac_devolucao');
$oDaoVacSala          = db_utils::getdao('vac_sala');
$db_opcao             = 1;
$db_botao             = true;
$iDepartamento        = db_getsession("DB_coddepto");
$iUsuario             = DB_getsession("DB_id_usuario");
$sSql                 = $oDaoVacSala->sql_query("",
                                                     "vc01_i_unidade,descrdepto",
                                                     "",
                                                     " vc01_i_unidade=$iDepartamento ");
$rsSala               = $oDaoVacSala->sql_record($sSql);

if (isset($confirma)) {
	
  require_once("far1_far_devolucaomed001_func.php");
  $oDaoVacDevFechaAplica   = db_utils::getdao('vac_devfechaaplica');
  $oDaoVacDevFechaDescarte = db_utils::getdao('vac_devfechadescarte');
  $oDaoVacFechaaplica      = db_utils::getdao('vac_fechaaplica');
  $oDaoVacFechadescarte    = db_utils::getdao('vac_fechadescarte');
  $oDaoVacFechamento       = db_utils::getdao('vac_fechamento');
  $dHoje                   = date("Y-m-d",db_getsession("DB_datausu"));
  db_inicio_transacao();
    $aFechamentos      = explode(",",$codretirada);
    $aMotivos        = explode("##",$motivos);
    $iTamFechamentos = count($aFechamentos);
    $sErro = "";
    //Busca dados sobre o lote selecionado
    for ($iX=0; $iX < $iTamFechamentos; $iX++) {
    	
      if ($sErro == "") {

        $sSql    = $oDaoVacDevolucao->sql_query_file(null,'vc26_i_codigo',null," vc26_i_fechamento=$aFechamentos[$iX] ");
        $rsDados = $oDaoVacDevolucao->sql_record($sSql);
        if ($oDaoVacDevolucao->numrows == 0) {

          $oDaoVacDevolucao->vc26_i_fechamento = $aFechamentos[$iX];
          $oDaoVacDevolucao->vc26_i_usuario    = $iUsuario;
          $oDaoVacDevolucao->vc26_d_data       = $dHoje;
          $oDaoVacDevolucao->incluir(null);
          if ($oDaoVacDevolucao->erro_status == "0") {
            $sErro = $oDaoVacDevolucao->erro_msg;
          }

        } else {

          $oDados = db_utils::fieldsmemory($rsDados, 0);
          $oDaoVacDevolucao->vc26_i_codigo = $oDados->vc26_i_codigo;
          $oDaoVacDevolucao->erro_status = "1";

        }
        if ($oDaoVacDevolucao->erro_status != "0") {
        	
          //Busca os fecha aplicações e vincula com a devolução
          $sCampos = " vc21_i_codigo ";
          $sWhere  = " vc21_i_fechamento=$aFechamentos[$iX] and vc17_i_matetoqueitemlote=$m77_sequencial";
          $sSql    = $oDaoVacFechaaplica->sql_query(null,$sCampos,null,$sWhere);
          $rsDados = $oDaoVacFechaaplica->sql_record($sSql);
          for ($iY = 0; $iY < $oDaoVacFechaaplica->numrows; $iY++) {

            if ($sErro=="") {
            	
              $oDados = db_utils::fieldsmemory($rsDados,$iY);
              $oDaoVacDevFechaAplica->vc24_i_fechaaplica = $oDados->vc21_i_codigo;
              $oDaoVacDevFechaAplica->vc24_i_devolucao   = $oDaoVacDevolucao->vc26_i_codigo;
              $oDaoVacDevFechaAplica->incluir(null);
              
              if ($oDaoVacDevFechaAplica->erro_status == "0") {
                $sErro = $oDaoVacDevFechaAplica->erro_msg;
              }
            }

          }
          //Busca os fecha descartes e vincula com a devolução
          $sCampos = " vc22_i_codigo ";
          $sWhere  = "vc22_i_fechamento=$aFechamentos[$iX] and vc19_i_matetoqueitemlote=$m77_sequencial";
          $sSql    = $oDaoVacFechadescarte->sql_query(null,$sCampos,null,$sWhere);
          $rsDados = $oDaoVacFechadescarte->sql_record($sSql);
          for ($iY = 0; $iY < $oDaoVacFechadescarte->numrows; $iY++) {
          
            $oDados = db_utils::fieldsmemory($rsDados,$iY);
            $oDaoVacDevFechaDescarte->vc25_i_fechadescarte = $oDados->vc22_i_codigo;
            $oDaoVacDevFechaDescarte->vc25_i_devolucao     = $oDaoVacDevolucao->vc26_i_codigo;
            $oDaoVacDevFechaDescarte->incluir(null);
            if ($oDaoVacDevFechaDescarte->erro_status == "0") {
              $sErro = $oDaoVacDevFechaDescarte->erro_msg;
            }

          }
          $sCampos  = "m41_codmatmater,";
          $sCampos .= "m41_codigo,";
          $sCampos .= "m43_quantatend,";
          $sCampos .= "m43_codigo,";
          $sCampos .= "m43_codatendrequi,";
          $sCampos .= "m49_codmatestoqueinimei";
          $sWhere   = " m77_sequencial=$m77_sequencial and vc23_i_fechamento=$aFechamentos[$iX] ";
          $sSql     = $oDaoVacFechamento->sql_query_atendrequiitem(null,$sCampos,null,$sWhere);
          $rsDados  = $oDaoVacFechamento->sql_record($sSql);
          if ($oDaoVacFechamento->numrows > 0) {
            $oLote = db_utils::fieldsmemory($rsDados,0);
            
            //efetua a devolução no estoque
            $sStr  = "quant_".$oLote->m43_codigo."_".$oLote->m41_codmatmater."_".$oLote->m41_codigo;
            $sStr .= "_".$oLote->m43_quantatend."_".$oLote->m49_codmatestoqueinimei;
            $lErro = devolveMaterial($sStr, 
                                     $oLote->m43_codatendrequi, 
                                     $aMotivos[$iX]);
            
            if ($lErro == true) {
              $sErro  = "Erro Durante a baixa a devolução no material ";
            }
            
          } else {
          	
          	$lErro = false;
            $sErro = "Erro Lote($m77_sequencial) no Fechamento(".$aFechamentos[$iX].") é invalido!";
            die("<br><br> SQL = $sSql <br>");
            
          }
        }
      }
    }
    if ($sErro != "") {
    
      $oDaoVacDevolucao->erro_msg    = $sErro;
      $oDaoVacDevolucao->erro_status = "0";
    
    } else {
      $oDaoVacDevolucao->erro_msg    = " Anulação de baixa efetuada com sucesso! ";
    }
  db_fim_transacao($oDaoVacDevolucao->erro_status == "0");
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");


?>
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
<br><br>
<center>
<?if ($oDaoVacSala->numrows == 0) {
    
    echo"<br><br><center><strong><b> Departamento não tem sala de vacinação cadastradas! </b></strong></center></center></center>";
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    exit;

  } else {
  	
    $oSala = db_utils::fieldsmemory($rsSala, 0);
    $vc01_i_unidade = $oSala->vc01_i_unidade;
    $descrdepto   = $oSala->descrdepto;
    
  }?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?include("forms/db_frmvac_devolveestoque.php");?>
    </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1", "vc01_i_unidade", true, 1, "vc01_i_unidade", true);
</script>
<?
if (isset($confirma)) {
  if ($oDaoVacDevolucao->erro_status == "0") {
    $oDaoVacDevolucao->erro(true,false);
  } else {

    $oDaoVacDevolucao->erro(true,false);
    db_redireciona("vac4_anulabaixa.php");

  }
}
?>