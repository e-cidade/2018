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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_classesgenericas.php");
require ("libs/db_stdlibwebseller.php");
require ("libs/db_utils.php");
db_postmemory ( $HTTP_POST_VARS );
$oDaoLabFechamento        = db_utils::getdao("lab_fechamento");
$cllab_bpamagnetico       = db_utils::getdao("lab_bpamagnetico");
$oDaoLabFechaConferencia  = db_utils::getdao("lab_fechaconferencia");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ();
$la54_i_login             = DB_getsession ( "DB_id_usuario" );
$hoje                     = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );

function somarDias($data, $dias) {

  if (strstr($data, "/" )) {
    $data = explode("/", $data);
  } else {
    $data = explode("-", $data);
  }
  $dia   = (int)$data[0];
  $mes   = (int)$data[1];
  $ano   = (int)$data[2];
  $data2 = date("Y-m-d", mktime(0, 0, 0, $mes, $dia + $dias, $ano));
  return $data2;

}

$db_opcao2 = 1; //alteracao
$db_opcao  = 1;
$db_botao  = true;
$db_opcao1 = 1;
if (isset ( $opcao )) {

  $db_botao1 = true;
  if ($opcao == "alterar") {

    $verifica  = true;
    $db_opcao  = 2;
    $db_opcao2 = 3; 
    $sSql      = $oDaoLabFechamento->sql_query($la54_i_codigo);
    $result    = $oDaoLabFechamento->sql_record($sSql);
    db_fieldsmemory ($result, 0);

  }

}

if (isset ($incluir)) {
  
  //Verifica se tem registro para gerar o BPA
  $la54_d_ini            = substr($la54_d_ini, 6, 4)."-".substr($la54_d_ini, 3, 2)."-".substr($la54_d_ini, 0, 2);
  $la54_d_fim            = substr($la54_d_fim, 6, 4)."-".substr($la54_d_fim, 3, 2)."-".substr($la54_d_fim, 0, 2);
  $oDados->sTipo         = '03';
  $oDados->iCompano      = date('Y');
  $oDados->iCompmes      = date('m');
  $oDados->iCidade       = '000000';
  $oDados->dIni          = $la54_d_ini;
  $oDados->dFim          = $la54_d_fim;
  $oDados->iUnidade      = "";
  $oDados->sSigla        = "";
  $oDados->financiamento = $la54_i_financiamento;
  $sSql                  = $cllab_bpamagnetico->sql_querry_prd_bpa($oDados);
  $rsResult              = $cllab_bpamagnetico->sql_record($sSql);
  if ($cllab_bpamagnetico->numrows > 0) {
    
    db_inicio_transacao ();
    $oDaoLabFechamento->la54_d_ini     = $la54_d_ini;
    $oDaoLabFechamento->la54_d_fim     = $la54_d_fim;
    $oDaoLabFechamento->la54_i_compmes = $la54_i_compmes;
    $oDaoLabFechamento->la54_i_compano = $la54_i_compano;
    $oDaoLabFechamento->la54_d_data    = $hoje;
    $oDaoLabFechamento->la54_c_hora    = db_hora ();
    $oDaoLabFechamento->la54_i_login   = db_getsession("DB_id_usuario");
    $oDaoLabFechamento->incluir ( null );
    if ($oDaoLabFechamento->erro_status != "0") {

      $sQl  = "insert into lab_fechaconferencia (la58_i_codigo,la58_i_fechamento,la58_i_conferencia) "; 
      $sQl .= " select nextval('lab_fechaconferencia_la58_i_codigo_seq'),".$oDaoLabFechamento->la54_i_codigo.",la47_i_codigo ";
      $sQl .= " from lab_requiitem ";
      $sQl .= "  inner join (select distinct on (la47_i_requiitem) lab_conferencia.*  from lab_conferencia "; 
      $sQl .= "              order by la47_i_requiitem,la47_d_data desc,la47_c_hora desc) "; 
      $sQl .= "              as lab_conferencia    on lab_conferencia.la47_i_requiitem = lab_requiitem.la21_i_codigo ";
      $sQl .= "  inner join lab_coletaitem    on lab_coletaitem.la32_i_requiitem  = lab_requiitem.la21_i_codigo ";
      $sQl .= "  inner join sau_procedimento  on sau_procedimento.sd63_i_codigo   = lab_conferencia.la47_i_procedimento ";
      $sQl .= "  inner join sau_financiamento on sau_financiamento.sd65_i_codigo  = sau_procedimento.sd63_i_financiamento ";
      $sQl .= " where lab_coletaitem.la32_d_data between '$la54_d_ini' and  '$la54_d_fim' ";
      if ($la54_i_financiamento != "0") {
        $sQl .= " and  sd65_c_financiamento=(select sd65_c_financiamento from sau_financiamento where sd65_i_codigo=$la54_i_financiamento)";
      }
      $rsResult = pg_query($sQl);
      if (pg_affected_rows($rsResult) == 0 || pg_affected_rows($rsResult) == false) {

        $oDaoLabFechamento->erro_msg    = " Nenhum registro encontrado![2] ";
        $oDaoLabFechamento->erro_status = "0";

      }
    }
    db_fim_transacao ($oDaoLabFechamento->erro_status == "0");
  } else {
    
    $oDaoLabFechamento->erro_status = "0";
    $oDaoLabFechamento->erro_msg    = " Nenhum registro encontrado![1] ";
    
  }
} elseif (isset ($alterar)) {
  
  //Verifica se tem registro para gerar o BPA
  $la54_d_ini             = substr(la54_d_ini, 6, 4)."-".substr($la54_d_ini, 3, 2)."-".substr($la54_d_ini, 0, 2);
  $la54_d_fim             = substr($la54_d_fim, 6, 4)."-".substr($la54_d_fim, 3, 2)."-".substr($la54_d_fim, 0, 2);
  $oDados->sTipo          = '03';
  $oDados->iCompano       = date('Y');
  $oDados->icompmes       = date('m');
  $oDados->iCidade        = '000000';
  $oDados->dIni           = $la54_d_ini;
  $oDados->dFim           = $la54_d_fim;
  $oDados->iUnidade       = "";
  $oDados->sSigla         = "";
  $oDados->financiamento  = $la54_i_financiamento;
  $sSql                   = $cllab_bpamagnetico->sql_querry_prd_bpa($oDados);
  $rsResult               = $cllab_bpamagnetico->sql_record($sSql);
  if ($cllab_bpamagnetico->numrows > 0) {

    db_inicio_transacao ();
    $oDaoLabFechamento->la54_i_compmes = $la54_i_compmes;
    $oDaoLabFechamento->la54_i_compano = $la54_i_compano;
    $oDaoLabFechamento->la54_c_hora    = db_hora ();
    $oDaoLabFechamento->la54_i_codigo  =$la54_i_codigo;
    $oDaoLabFechamento->alterar($la54_i_codigo);
    if ($oDaoLabFechamento->erro_status != "0") {
      $oDaoLabFechaConferencia->excluir("", "la58_i_fechamento= $la54_i_codigo");
      if ($oDaoLabFechaConferencia->erro_status != "0") {
      
        $sQl  = "insert into lab_fechaconferencia (la58_i_codigo,la58_i_fechamento,la58_i_conferencia) "; 
        $sQl .= " select nextval('lab_fechaconferencia_la58_i_codigo_seq'),".$la54_i_codigo.",la47_i_codigo ";
        $sQl .= " from lab_requiitem ";
        $sQl .= "  inner join (select distinct on (la47_i_requiitem) lab_conferencia.*  from lab_conferencia "; 
        $sQl .= "              order by la47_i_requiitem,la47_d_data desc,la47_c_hora desc) "; 
        $sQl .= "              as lab_conferencia    on lab_conferencia.la47_i_requiitem = lab_requiitem.la21_i_codigo ";
        $sQl .= "  inner join lab_coletaitem    on lab_coletaitem.la32_i_requiitem  = lab_requiitem.la21_i_codigo ";
        $sQl .= "  inner join sau_procedimento  on sau_procedimento.sd63_i_codigo   = lab_conferencia.la47_i_procedimento ";
        $sQl .= "  inner join sau_financiamento on sau_financiamento.sd65_i_codigo  = sau_procedimento.sd63_i_financiamento ";
        $sQl .= " where lab_coletaitem.la32_d_data between '$la54_d_ini' and  '$la54_d_fim' ";
        
        if ($la54_i_financiamento != "0") {
          
          $sQl .= " and  sd65_c_financiamento= ";
          $sQl .= " (select sd65_c_financiamento from sau_financiamento where sd65_i_codigo=$la54_i_financiamento) ";
          
        }
        $rsResult = pg_query($sQl);
        if (pg_affected_rows($rsResult) == 0 || pg_affected_rows($rsResult) == false) {
          
          $oDaoLabFechamento->erro_msg    = " Nenhum registro de produção adicionado! ";
          $oDaoLabFechamento->erro_status = "0";
          
        }
      } else {
        
        $oDaoLabFechaConferencia->erro_status = "0";
        $oDaoLabFechaConferencia->erro_msg    = " Erro ao Excluir: ".$oDaoLabFechaConferencia->erro_msg;
          
      }
    }  
    db_fim_transacao ();
    
  } else {

    $oDaoLabFechamento->erro_status = "0";
    $oDaoLabFechamento->erro_msg    = " Nenhum registro encontrado! ";

  }
  
} elseif ($db_opcao == 1 && !isset($la54_d_ini)) {

  //Pega o ultimo fechamento e incrementa os valores
  $sSql =  $oDaoLabFechamento->sql_query_file ("", 
                                               "la54_i_compmes, la54_i_compano, la54_d_fim",
                                               "la54_i_compano, la54_i_compmes desc limit 1"
                                              );
  $result1 = $oDaoLabFechamento->sql_record ($sSql);
  if ($oDaoLabFechamento->numrows > 0) {
  
    $oFechamento = db_utils::fieldsmemory($result1, 0);
    if ($oFechamento->la54_i_compmes == 12) {

      $la54_i_compmes = $oFechamento->la54_i_compmes = 1;
      $la54_i_compano = $oFechamento->la54_i_compano+1;

    } else {

      $la54_i_compmes = $oFechamento->la54_i_compmes+1;
      $la54_i_compano = $oFechamento->la54_i_compano;

    }
    $aDataIni       = explode('-',$oFechamento->la54_d_fim);
    $la54_d_ini     = somaDataDiaMesAno($aDataIni[2], $aDataIni[1], $aDataIni[0], 1, 0, 0);
    $aData          = explode('/', $la54_d_ini);
    $la54_d_ini_dia = $aData[0];
    $la54_d_ini_mes = $aData[1];
    $la54_d_ini_ano = $aData[2];
    $la54_d_fim_dia = "";
    $la54_d_fim_mes = "";
    $la54_d_fim_ano = "";
    $la54_d_fim     = "";

  }
  

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
  src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
  marginheight="0" onLoad="a=1">
<table width="790" border="0" cellpadding="0" cellspacing="0"
  bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><br>
    <br>
    <br>
    <?
      include ("forms/db_frmlab_fechamento.php");
    ?>
  </td>
  </tr>
</table>
</center>
<?
  db_menu (db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
           db_getsession("DB_instit" ));
?>
</body>
</html>
<script>
  js_tabulacaoforms("form1", "sd97_i_login", true, 1, "sd97_i_login", true);
</script>
<?
/////mensagem de inclusao efetuada com sucesso
if (isset ($incluir) || isset($alterar)) {
  if ($oDaoLabFechamento->erro_status == "0") {
    $oDaoLabFechamento->erro (true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoLabFechamento->erro_campo != "") {
      echo "<script> document.form1." . $oDaoLabFechamento->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $oDaoLabFechamento->erro_campo . ".focus();</script>";
    }
  } else {
    $oDaoLabFechamento->erro (true, true);
  }
}
if ($db_opcao == 33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>