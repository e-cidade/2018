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

require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");

db_postmemory($HTTP_POST_VARS);
$oDaoVacFechamento    = db_utils::getdao('vac_fechamento');
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

if(isset($confirma)){

  require_once("classes/requisicaoMaterial.model.php");
  $oDaoVacAplicalote    = db_utils::getdao('vac_aplicalote');
  $oDaoVacDescarte      = db_utils::getdao('vac_descarte');
  $oDaoVacFechaaplica   = db_utils::getdao('vac_fechaaplica');
  $oDaoVacFechadescarte = db_utils::getdao('vac_fechadescarte');
  $oDaoVacFecharequi    = db_utils::getdao('vac_fecharequi');
  $oDaoMatrequi         = db_utils::getdao('matrequi');
  $oDaoAtendrequi       = db_utils::getdao('atendrequi');
  $oDaoDbAlmox          = db_utils::getdao('db_almox');
  $oDaoMatrequiitem     = db_utils::getdao('matrequiitem');
  $sSql                 = $oDaoDbAlmox->sql_query_file(null, "*", null, "m91_depto=$iDepartamento");
  $rsDados              = $oDaoDbAlmox->sql_record($sSql);
  $dHoje                = date("Y-m-d",db_getsession("DB_datausu"));
  if ($oDaoDbAlmox->numrows > 0) {

    $oAlmox = db_utils::fieldsmemory($rsDados, 0);
    db_inicio_transacao();
    $oDaoVacFechamento->vc20_i_usuario = $iUsuario;
    $oDaoVacFechamento->incluir(null);

    if ($oDaoVacFechamento->erro_status != "0") {

      $sErro = "";
      $oDaoMatrequi->m40_data  = $dHoje;
      $oDaoMatrequi->m40_auto  = 't';
      $oDaoMatrequi->m40_depto = $iDepartamento;
      $oDaoMatrequi->m40_login = $iUsuario;
      $oDaoMatrequi->m40_almox = $oAlmox->m91_codigo;
      $oDaoMatrequi->m40_hora  = db_hora();
      $oDaoMatrequi->m40_obs   = "";
      $oDaoMatrequi->incluir(null);

      if ($oDaoMatrequi->erro_status == 0) {
        $sErro = $oDaoMatrequi->erro_msg;
      }
      if ($sErro == "") {

        $oDaoVacFecharequi->vc23_i_matrequi = $oDaoMatrequi->m40_codigo;
        $oDaoVacFecharequi->vc23_i_fechamento = $oDaoVacFechamento->vc20_i_codigo;
        $oDaoVacFecharequi->incluir(null);
        if ($oDaoVacFecharequi->erro_status == 0) {
          $sErro = $oDaoVacFecharequi->erro_msg;
        }

      }
      if ($sErro == "") {

        $oDaoAtendrequi->m42_login = $iUsuario;
        $oDaoAtendrequi->m42_depto = $iDepartamento;
        $oDaoAtendrequi->m42_data  = $dHoje;
        $oDaoAtendrequi->m42_hora  = db_hora();
        $oDaoAtendrequi->incluir(null);
        if ($oDaoAtendrequi->erro_status == 0) {
          $sErro = $oDaoAtendrequi->erro_msg;
        }

      }
      $aCodlotes = explode(",",$codlotes);
      $iTam      = count($aCodlotes);
      $aVacinas  = array();
      $aLotesAdd = array();
      $vc20_d_dataini = implode('-',array_reverse(explode("/",$vc20_d_dataini)));
      $vc20_d_datafim = implode('-',array_reverse(explode("/",$vc20_d_datafim)));
      for ($iX = 0; $iX < $iTam; $iX++) {

        $sWhere    = " vc16_d_data between '$vc20_d_dataini' and '$vc20_d_datafim' and m77_sequencial=".$aCodlotes[$iX];
        $sWhere   .= " and not exists (select * from vac_aplicaanula Where vc18_i_aplica = vac_aplica.vc16_i_codigo) ";
        $sSubanula = " and not exists (select * from vac_devfechaaplica Where ";
        $sSubanula.= " vc24_i_fechaaplica = vac_fechaaplica.vc21_i_codigo)";
        $sWhere   .= " and not exists (select * from vac_fechaaplica Where ";
        $sWhere   .= "vc21_i_aplicalote = vac_aplica.vc16_i_codigo $sSubanula )";
        $sSql      = $oDaoVacAplicalote->sql_query2(null,
                                                    "vc17_i_codigo,m71_codlanc,vc29_i_dose,m70_codmatmater,vc16_n_quant",
                                                    null,
                                                    $sWhere
                                                   );
        $rsDados   = $oDaoVacAplicalote->sql_record($sSql);
        for ($iY = 0; $iY < $oDaoVacAplicalote->numrows; $iY++) {

          if ($sErro == "") {

            $oDados = db_utils::fieldsmemory($rsDados,$iY);
            if (!isset($aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc])) {
              $aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc] = $oDados->vc16_n_quant;
            } else {
              $aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc] += $oDados->vc16_n_quant;
            }

            if (array_key_exists($oDados->m70_codmatmater,$aVacinas)) {
              $aVacinas[$oDados->m70_codmatmater] += $oDados->vc16_n_quant;
            } else {

              $aVacinas[$oDados->m70_codmatmater] = $oDados->vc16_n_quant;
              $aFator[$oDados->m70_codmatmater]   = $oDados->vc29_i_dose;

            }
            $oDaoVacFechaaplica->vc21_i_fechamento = $oDaoVacFechamento->vc20_i_codigo;
            $oDaoVacFechaaplica->vc21_i_aplicalote = $oDados->vc17_i_codigo;
            $oDaoVacFechaaplica->incluir(null);
            if ($oDaoVacFechaaplica->erro_status == "0") {
              $sErro = $oDaoVacFechaaplica->erro_msg;
            }

          }
        }
        $sWhere     = " vc19_d_data between '$vc20_d_dataini' and '$vc20_d_datafim' and m77_sequencial=".$aCodlotes[$iX];
        $sSubanula  = " and not exists (select * from vac_devfechadescarte Where ";
        $sSubanula .= "vc25_i_fechadescarte = vac_fechadescarte.vc22_i_codigo)";
        $sWhere    .= " and not exists (select * from vac_fechadescarte Where";
        $sWhere    .= " vc22_i_descarte = vac_descarte.vc19_i_codigo $sSubanula ) ";
        $sSql       = $oDaoVacDescarte->sql_query2(null,
                                                   "vc19_i_codigo,m71_codlanc,vc29_i_dose,m70_codmatmater,vc19_n_quant",
                                                   null,
                                                   $sWhere
                                                  );
        $rsDados    = $oDaoVacDescarte->sql_record($sSql);
        for ($iY = 0; $iY < $oDaoVacDescarte->numrows; $iY++) {

          if ($sErro == "") {

            $oDados = db_utils::fieldsmemory($rsDados,$iY);
            if (array_key_exists($oDados->m70_codmatmater,$aVacinas)) {
              $aVacinas[$oDados->m70_codmatmater] += $oDados->vc19_n_quant;
            } else {
              $aVacinas[$oDados->m70_codmatmater] = $oDados->vc19_n_quant;
              $aFator[$oDados->m70_codmatmater]   = $oDados->vc29_i_dose;
            }
            if (!isset($aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc])) {
              $aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc] = $oDados->vc19_n_quant;
            } else {
              $aLotesAdd[$oDados->m70_codmatmater][$oDados->m71_codlanc] += $oDados->vc19_n_quant;
            }
            $oDaoVacFechadescarte->vc22_i_fechamento = $oDaoVacFechamento->vc20_i_codigo;
            $oDaoVacFechadescarte->vc22_i_descarte   = $oDados->vc19_i_codigo;
            $oDaoVacFechadescarte->incluir(null);
            if ($oDaoVacFechadescarte->erro_status == "0") {
              $sErro = $oDaoVacFechadescarte->erro_msg;
            }

          }
        }

      }
      //Limpa Lotes da sessão
      $_SESSION ["LoteSessao"] = 1;
      foreach ( $aLotesAdd as $iCodMater => $aCodLanc ) {

        if (isset ( $_SESSION ["mat$iCodMater"] )) {
          unset ( $_SESSION ["mat$iCodMater"] );
        }
        foreach ( $aCodLanc as $iCodLanc => $iQuant ) {
          $_SESSION ["mat$iCodMater"] [$iCodLanc] = $iQuant/$aFator[$iCodMater];
        }
      }
      $iCont     = 0;
      $aSubItens = array();
      //Percorre todos os materiais captados na rotina acima
      foreach ($aVacinas as $iKey => $iValor) {

        if ($sErro == "") {

          $oDaoMatrequiitem->m41_codunid     = '1';
          $oDaoMatrequiitem->m41_codmatrequi = $oDaoMatrequi->m40_codigo;
          $oDaoMatrequiitem->m41_codmatmater = $iKey;
          $oDaoMatrequiitem->m41_quant       = $iValor/$aFator[$iKey];
          $oDaoMatrequiitem->m41_obs         = "";
          $oDaoMatrequiitem->incluir(null);
          if ($oDaoMatrequiitem->erro_status == "0") {
             $sErro = $oDaoMatrequiitem->erro_msg;
          } else {

            $aSubItens[$iCont]->iCodMater   = $iKey;
            $aSubItens[$iCont]->iCodItemReq = $oDaoMatrequiitem->m41_codigo;
            $aSubItens[$iCont]->iCodalmox   = $iDepartamento;
            $aSubItens[$iCont]->nQtde       = $iValor/$aFator[$iKey];
            $iCont++;

          }

        }
      }
      if ($iCont == 0 && $sErro == "") {
        $sErro = "Erro aplicações e descarte não encontrados!";
      }
      if ($sErro == "") {
        try {

          $oRequisicao = new requisicaoMaterial($oDaoMatrequi->m40_codigo);
          $oRequisicao->atenderRequisicao(17, $aSubItens, $iDepartamento,$oDaoAtendrequi->m42_codigo);

        } catch (Exception $eErro) {
          $sErro = " Material: ".$eErro->getMessage();
        }
      }
      if ($sErro != "") {

        $oDaoVacFechamento->erro_msg    = $sErro;
        $oDaoVacFechamento->erro_status = "0";

      }else{
        $oDaoVacFechamento->erro_msg = "Baixa no estoque efetuada com sucesso! Codigo da requição:$oDaoMatrequi->m40_codigo ";
      }
    }
    db_fim_transacao($oDaoVacFechamento->erro_status == "0");
  } else {

    $oDaoVacFechamento->erro_msg    = "Departamento não é um almoxarifado!";
    $oDaoVacFechamento->erro_status = "0";

  }
  if(isset($_SESSION ["LoteSessao"])){
    unset($_SESSION ["LoteSessao"]);
  }
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
      <?include("forms/db_frmvac_baixaestoque.php");?>
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
js_tabulacaoforms("form1", "vc20_d_dataini", true, 1, "vc20_d_dataini", true);
</script>
<?
if (isset($confirma)) {
  if ($oDaoVacFechamento->erro_status == "0") {
    $oDaoVacFechamento->erro(true,false);
  } else {

    $oDaoVacFechamento->erro(true,false);
    db_redireciona("vac4_baixaestoque.php");

  }
}
?>