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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_assenta_classe.php");
include("classes/db_assmeio_classe.php");
include("classes/db_tipoasse_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$classenta = new cl_assenta;
$classmeio = new cl_assmeio;
$cltipoasse = new cl_tipoasse;
$db_opcao = 1;
$db_botao = true;
$quantidade = 1;
$h16_quant  = 1;
$opcao_dtterm = 3;
if(!isset($h12_tipo)){
  $h12_tipo   = "";
  $h12_tipefe = "";
}
if(isset($incluir)){
  db_inicio_transacao();
  $datadia = date("Y-m-d",db_getsession("DB_datausu"));
  $usuaatu = db_getsession("DB_id_usuario");
  $dtconc  = $h16_dtconc_ano . "-" . $h16_dtconc_mes . "-" . $h16_dtconc_dia;

  $sqlerro = false;
  $result_verifica_lancado = $classmeio->sql_record($classmeio->sql_query_file(null, "h22_codigo as codigo, h22_histor || ' ' || h22_hist2 as historico_mdia", "", " h22_regist = ".$h16_regist." and h22_assent = ".$h16_assent." and h22_data is null"));
  if($classmeio->numrows > 0){
    db_fieldsmemory($result_verifica_lancado, 0);
    $historico = $historico_mdia . "\\n" . $h16_histor;
    $classmeio->h22_data   = $dtconc;
    $classenta->h16_histor = substr($historico,   0, 240);
    $classenta->h16_hist2  = substr($historico, 240, 240);
    $classenta->h16_perc   = "0";
    $classenta->h16_dtlanc = $datadia;
    $classenta->h16_conver = "true";
    $classenta->h16_login  = $usuaatu;
    $classenta->incluir($h16_codigo);
    if($classenta->erro_status == "0"){
      $sqlerro = true;
      $erro_msg = $classenta->erro_msg;
    }
  }

  if($sqlerro == false){
    $classmeio->h22_regist = $h16_regist; 
    $classmeio->h22_assent = $h16_assent;
    $classmeio->h22_dtconc = $dtconc;
    $classmeio->h22_histor = substr($h16_histor,   0, 240);
    $classmeio->h22_hist2  = substr($h16_histor, 240, 240);
    $classmeio->h22_nrport = $h16_nrport;
    $classmeio->h22_atofic = $h16_atofic;
    $classmeio->h22_quant  = $h16_quant;
    $classmeio->h22_perc   = "0";
    $classmeio->h22_dtterm = $h16_dtterm_ano . "-" . $h16_dtterm_mes . "-" . $h16_dtterm_dia;
    $classmeio->h22_login  = $usuaatu;
    $classmeio->h22_dtlanc = $datadia;
    $classmeio->incluir(null);
    $erro_msg = $classmeio->erro_msg;
    if($classmeio->erro_status == "0"){
      $sqlerro = true;
    }else if(isset($codigo) && trim($codigo) != ""){
      $classmeio = new cl_assmeio;
      $classmeio->h22_histor = substr($historico_mdia,   0, 240);
      $classmeio->h22_hist2  = substr($historico_mdia, 240, 240);
      $classmeio->h22_data   = $dtconc;
      $classmeio->h22_codigo = $codigo;
      $classmeio->alterar($codigo);
      if($classmeio->erro_status == "0"){
        $sqlerro = true;
        $erro_msg = $classmeio->erro_msg;
      }
    }
  }

  db_fim_transacao($sqlerro);
}
if(isset($h16_assent) && trim($h16_assent) != ""){
  $result_assent = $cltipoasse->sql_record($cltipoasse->sql_query_file($h16_assent, "h12_tipo, h12_tipefe, h12_vinculaperiodoaquisitivo"));
  if($cltipoasse->numrows > 0){
    db_fieldsmemory($result_assent, 0);
  }
}

/**
 * Não habilita a funcionalidade de vinculo com os períodos aquisitivos
 */
$lBloqueiaPeriodoAquisitivo = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmassenta.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro == false){
    echo "<script>location.href = 'rec1_assentamdia001.php'</script>";
  }
}
?>