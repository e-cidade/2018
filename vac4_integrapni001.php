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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_dbf_class.php");
include_once('dbforms/db_layouttxt.php');
db_postmemory($HTTP_POST_VARS);
$oDaoVacAplicalote    = db_utils::getdao('vac_aplicalote');
$oDaoVacSala          = db_utils::getdao('vac_sala');
$db_opcao             = 1;
$db_botao             = true;
$iDepartamento        = db_getsession("DB_coddepto");
$iUsuario             = DB_getsession("DB_id_usuario");
$dHoje                = date("Y-m-d",db_getsession("DB_datausu"));
$aHoje                = explode("-",$dHoje);
$mes                  = $aHoje[1];
$ano                  = $aHoje[0];
$sSql                 = $oDaoVacSala->sql_query("",
                                                "vc01_i_unidade,descrdepto",
                                                "",
                                                " vc01_i_unidade=$iDepartamento ");
$rsSala               = $oDaoVacSala->sql_record($sSql);
if(!isset($confirma)){
  $sEstado = " Aguardando... ";
}else{
  $sEstado = " Processando... ";
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
  	
    $oSala          = db_utils::fieldsmemory($rsSala, 0);
    $vc01_i_unidade = $oSala->vc01_i_unidade;
    $descrdepto     = $oSala->descrdepto;
    
  }?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?include("forms/db_frmvac_integrapni.php");?>
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

  
	$oDaoUnidades         = db_utils::getdao('unidades');
  $oDaoVacboletim       = db_utils::getdao('vac_boletim');
  $oDaoVacArquivopni    = db_utils::getdao('vac_arquivopni');
  $oDaoVacArquivopnireg = db_utils::getdao('vac_arquivopnireg');
  $oDaoVacCampanha      = db_utils::getdao('vac_campanha');
  $sSql                 = $oDaoUnidades->sql_query($iDepartamento);
  $rsResult             = $oDaoUnidades->sql_record($sSql);
  $oDados               = db_utils::fieldsMemory($rsResult,0);
  $aVet                 = array(1=>"r",2=>"c");
  $sT                   = $aVet[$estrategia];
  $sL                   = "m";
  $sUF                  = strtolower($oDados->z01_uf);
  $sX                   = strtolower($oDados->sd02_i_cidade);
  if($mes < 10){
    $sM = $mes;
  } elseif ($mes == 10) {
    $sM = '0';
  } elseif ($mes == 11) {
  	$sM = 'a';
  } elseif ($mes == 12) {
  	$sM = 'b';
  }
  $sAA                  = substr($ano,2,2);
  $sArquivo             = "/tmp/$sT$sL$sUF$sX.$sM$sAA";  
  $oBanco = new db_dbf_class($sArquivo);
  $oBanco->addColuna('cod_UB','C',7);
  $oBanco->addColuna('Mes_Vac','N',2);
  $oBanco->addColuna('Imuno','C',2);
  $oBanco->addColuna('Dose','C',2);
  $oBanco->addColuna('Qt_FE01','N',7);
  $oBanco->addColuna('Qt_FE02','N',7);
  $oBanco->addColuna('Qt_FE03','N',7);
  $oBanco->addColuna('Qt_FE04','N',7);
  $oBanco->addColuna('Qt_FE05','N',7);
  $oBanco->addColuna('Qt_FE06','N',7);
  $oBanco->addColuna('Qt_FE07','N',7);
  $oBanco->addColuna('Qt_FE08','N',7);
  $oBanco->addColuna('Qt_FE09','N',7);
  $oBanco->addColuna('Qt_FE10','N',7);
  $dIni   = "$ano-$mes-01";
  $dFim   = date("Y-m-d",mktime(0, 0, 0, $mes+1, -1, $ano));
  $sSql   = $oDaoVacSala->sql_query("","vc01_i_unidade,sd02_v_cnes");
  $sSql  .= " group by vc01_i_unidade,sd02_v_cnes";
  $rsSala = $oDaoVacSala->sql_record($sSql);
  $oDaoVacAplicalote->erro_staus = null;
  if ($oDaoVacSala->numrows == 0) {
  	
    $oDaoVacAplicalote->erro_msg = "Nenhuma sala de vacinação cadastrada na unidade!";
    $oDaoVacAplicalote->erro_staus = "0";
    
  }
  
  //verificar se tem campanha de vacinação
  $sWhere     = " '$dIni' between vc11_d_inicio and vc11_d_fim "; 
  $sWhere     = " or '$dFim' between vc11_d_inicio and vc11_d_fim ";
  $sSql       = $oDaoVacCampanha->sql_query("","*","",$sWhere);
  $rsCampanha = $oDaoVacCampanha->sql_query($sSql);
  if (($oDaoVacCampanha->numrows > 0 && $estrategia == 1) || ($oDaoVacCampanha->numrows == 0 && $estrategia == 2)) {
    
    if ($estrategia == 1) {
      $sStr = "Existe";
    } else {
      $sStr = "Nenhuma"; 
    }
    $oDaoVacAplicalote->erro_msg = "$sStr campanha de vacinação no mes selecionado!";
    $oDaoVacAplicalote->erro_staus = "0";
    
  }
  
  if ($oDaoVacAplicalote->erro_staus != "0") {
    $iRegistro = 0;
    for ($iUnid = 0; $iUnid<$oDaoVacSala->numrows;$iUnid++){

      $oUnid     = db_utils::fieldsMemory($rsSala,$iUnid);
      $sCampos   = " vc16_i_dosevacina, ";
      $sCampos  .= "$oUnid->sd02_v_cnes as cod_ub, ";
      $sCampos  .= str_pad($mes, 2, '0', STR_PAD_LEFT)." as mes_vac, ";
      $sCampos  .= 'vc06_c_codpni as imuno, ';
      $sCampos  .= 'vc03_c_codpni as dose, ';
      $sSql      = $oDaoVacboletim->sql_query();
      $rsBoletim = $oDaoVacboletim->sql_record($sSql);
      for ($iY=1; $iY <= 10; $iY++) {

        if (($iY-1) < $oDaoVacboletim->numrows) {

          $oBoletim    = db_utils::fieldsMemory($rsBoletim,$iY-1);
          $dIniBoletim = somaDataDiaMesAno($aHoje[2],
                                         $aHoje[1],
                                         $aHoje[0],
                                         -$oBoletim->vc13_i_diafim,
                                         -$oBoletim->vc13_i_mesfim,
                                         -$oBoletim->vc13_i_anofim,
                                         2);
          $dFimBoletim = somaDataDiaMesAno($aHoje[2],
                                         $aHoje[1],
                                         $aHoje[0],
                                         -$oBoletim->vc13_i_diaini,
                                         -$oBoletim->vc13_i_mesini,
                                         -$oBoletim->vc13_i_anoini,
                                         2);

          $aDatas[$iY][0] = $dIniBoletim;
          $aDatas[$iY][1] = $dFimBoletim;
          $sWhere         = " vc07_i_codigo=b.vc16_i_dosevacina ";
          $sWhere        .= " and cgs_und.z01_d_nasc between '$dIniBoletim' and '$dFimBoletim' ";
          $sWhere        .= " and b.vc16_d_dataaplicada between '$dIni' and '$dFim' ";
          $sWhere        .= "  and not exists (select * from vac_arquivopnireg Where vc28_i_aplicalote = a.vc17_i_codigo) ";
          $sInnerJoin     = " inner join vac_aplica as b on b.vc16_i_codigo = a.vc17_i_aplica ";
          $sInnerJoin    .= " inner join cgs_und on cgs_und.z01_i_cgsund = b.vc16_i_cgs ";
          $sSubSql        = " select lpad(coalesce(sum(b.vc16_n_quant),0),7,'0') from ";
          $sSubSql       .= " vac_aplicalote as a $sInnerJoin where $sWhere";
        
        } else {

          $aDatas[$iY][0] = 0;
          $aDatas[$iY][1] = 0;
          $sSubSql        = " '0000000' ";

        }
        $sVirg = ",";
        if ($iY == 10) {
          $sVirg = "";
        }
        $sCampos .= " ($sSubSql) as qt_fe".str_pad($iY, 2, '0', STR_PAD_LEFT)." $sVirg";
      }
      $sWhere    = " vc01_i_unidade = $oUnid->vc01_i_unidade ";
      $sWhere   .= " and vc16_d_dataaplicada between '$dIni' and '$dFim' ";
      $sWhere   .= "  and not exists (select * from vac_arquivopnireg Where vc28_i_aplicalote = vc17_i_codigo) ";
      $sWhere   .= " group by vc16_i_dosevacina,vc06_c_codpni,vc03_c_codpni,vc07_i_codigo ";
      $sSql      = $oDaoVacAplicalote->sql_query2(null,$sCampos,null,$sWhere);
      $rsVacina  = $oDaoVacAplicalote->sql_record($sSql);

      $aAplicaLote = array();
      $iVacinas = $oDaoVacAplicalote->numrows;
      for ($iX = 0; $iX < $iVacinas; $iX++) {

        $oRegistro = db_utils::fieldsMemory($rsVacina,$iX);
        $iTam      = count($aDatas);
        for ($iInd = 1; $iInd <= $iTam; $iInd++) {
          if ($aDatas[$iInd][0] != 0) {
        
            $sWhere      = " b.vc16_i_dosevacina=$oRegistro->vc16_i_dosevacina ";
            $sWhere     .= " and cgs_und.z01_d_nasc between '".$aDatas[$iInd][0]."' and '".$aDatas[$iInd][1]."' ";
            $sWhere     .= " and b.vc16_d_dataaplicada between '".$dIni."' and '".$dFim."' ";
            $sWhere     .= "  and not exists (select * from vac_arquivopnireg Where vc28_i_aplicalote = vc17_i_codigo) ";
            $sInnerJoin  = " inner join vac_aplica as b on b.vc16_i_codigo = a.vc17_i_aplica ";
            $sInnerJoin .= " inner join cgs_und on cgs_und.z01_i_cgsund = b.vc16_i_cgs ";
            $sSql        = " select vc17_i_codigo from vac_aplicalote as a $sInnerJoin where $sWhere";
            $rsResult    = $oDaoVacAplicalote->sql_record($sSql);
            if ($oDaoVacAplicalote->numrows > 0) {

              for($iIndAplicalote=0; $iIndAplicalote < $oDaoVacAplicalote->numrows; $iIndAplicalote++){

                $oDados        = db_utils::fieldsMemory($rsResult,$iIndAplicalote);
                $aAplicaLote[] = $oDados->vc17_i_codigo;

              }

            }
          
          }
        }
        db_atutermometro ($iX, $iVacinas, 'termometro' );
        $iRegistro = 1;
        $aDados = Array($oRegistro->cod_ub,
                        $oRegistro->mes_vac,
                        $oRegistro->imuno,
                        $oRegistro->dose,
                        $oRegistro->qt_fe01,
                        $oRegistro->qt_fe02,
                        $oRegistro->qt_fe03,
                        $oRegistro->qt_fe04,
                        $oRegistro->qt_fe05,
                        $oRegistro->qt_fe06,
                        $oRegistro->qt_fe07,
                        $oRegistro->qt_fe08,
                        $oRegistro->qt_fe09,
                        $oRegistro->qt_fe10);
        $oBanco->addRegistro($aDados);

      }
    }

    if($iRegistro == 0){

      $oDaoVacArquivopni->erro_msg   = "Nenhuma vacina aplicada o periodo!";
      $oDaoVacArquivopni->erro_status = "0";

    } 
  }  
  if ($oDaoVacArquivopni->erro_status != "0") {
  	
  	$aDados = Array('HEAD9.9','0','R','A',0,0,0,0,0,0,0,0,0,0);
    $oBanco->addRegistro($aDados);
  	$oBanco->criar($dHoje);
    db_inicio_transacao ();
    
    $oDaoVacArquivopni->vc27_d_data     = $dHoje;
    $oDaoVacArquivopni->vc27_c_hora     = date("H:i");
    $oDaoVacArquivopni->vc27_c_nome     = $sArquivo;
    $oDaoVacArquivopni->vc27_o_arquivo  = db_geraArquivoOidfarmacia($sArquivo,"",1,$conn);
    $oDaoVacArquivopni->vc27_i_situacao = 1;
    $oDaoVacArquivopni->incluir(null);
    $iTam = count($aAplicaLote);
    for($iInd=0; $iInd<$iTam; $iInd++){

    	if($oDaoVacArquivopni->erro_status != "0"){

        $oDaoVacArquivopnireg->vc28_i_arquivopni = $oDaoVacArquivopni->vc27_i_codigo;
        $oDaoVacArquivopnireg->vc28_i_aplicalote = $aAplicaLote[$iInd];
        $oDaoVacArquivopnireg->incluir(null);
        if($oDaoVacArquivopnireg->erro_status == "0"){
        	
          $oDaoVacArquivopni->erro_status = "0";
          $oDaoVacArquivopni->erro_msg    = $oDaoVacArquivopnireg->erro_msg;

        }
      }
    }
    db_fim_transacao ($oDaoVacArquivopni->erro_status == "0");
  }
  
  //Depois de concluida a rotina
  if ($oDaoVacArquivopni->erro_status == "0") {
    $oDaoVacArquivopni->erro(true,false);
  } else {

    db_msgbox("Arquivo gerado com sucesso! ($sArquivo)");
    ?>
      <script>
        listagem = '<?=$sArquivo?>#Download arquivo DBF (PNI)|';
        js_montarlista(listagem,'form1');
      </script>
    <?
    db_redireciona("vac4_integrapni001.php");

  }
}
?>