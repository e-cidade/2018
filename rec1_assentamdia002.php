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
include("classes/db_assmeio_classe.php");
include("classes/db_assenta_classe.php");
include("classes/db_tipoasse_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$classmeio = new cl_assmeio;
$classenta = new cl_assenta;
$cltipoasse = new cl_tipoasse;
$db_opcao = 22;
$db_botao = false;
$quantidade = 1;
$h16_quant  = 1;
$opcao_dtterm = 3;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $classmeio->h22_codigo = $h16_codigo;
  $classmeio->h22_regist = $h16_regist; 
  $classmeio->h22_assent = $h16_assent;
  $classmeio->h22_dtconc = $h16_dtconc_ano . "-" . $h16_dtconc_mes . "-" . $h16_dtconc_dia;
  $classmeio->h22_histor = substr($h16_histor,   0, 240);
  $classmeio->h22_hist2  = substr($h16_histor, 240, 240);
  $classmeio->h22_nrport = $h16_nrport;
  $classmeio->h22_atofic = $h16_atofic;
  $classmeio->h22_quant  = $h16_quant;
  $classmeio->h22_perc   = "0";
  $classmeio->h22_dtterm = $h16_dtterm_ano . "-" . $h16_dtterm_mes . "-" . $h16_dtterm_dia;
  $classmeio->h22_login  = db_getsession("DB_id_usuario");
  $classmeio->h22_dtlanc = date("Y-m-d",db_getsession("DB_datausu"));
  $classmeio->alterar($h16_codigo);
  db_fim_transacao();
  $db_botao = true;
}else if(isset($chavepesquisa)){
  $db_opcao = 2;
  $result = $classmeio->sql_record($classmeio->sql_query($chavepesquisa, "assmeio.*, z01_nome, h12_assent, h12_descr ")); 
  db_fieldsmemory($result,0);
  $h16_codigo = $h22_codigo;
  $h16_regist = $h22_regist;
  $h16_assent = $h22_assent;
  $arr_dtconc = split('-',$h22_dtconc);
  $h16_dtconc_dia = $arr_dtconc[2];
  $h16_dtconc_mes = $arr_dtconc[1]; 
  $h16_dtconc_ano = $arr_dtconc[0]; 
  $h16_histor = $h22_histor;
  $h16_hist2  = $h22_hist2;
  $h16_nrport = $h22_nrport;
  $h16_atofic = $h22_atofic;
  $h16_quant  = $h22_quant;
  $h16_perc   = $h22_perc;
  $arr_dtterm = split('-',$h22_dtterm);
  $h16_dtterm_dia = $arr_dtterm[2];
  $h16_dtterm_mes = $arr_dtterm[1]; 
  $h16_dtterm_ano = $arr_dtterm[0]; 
  $h16_login  = $h22_login;
  $db_botao = true;
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
<?
if(isset($alterar)){
  if($classmeio->erro_status=="0"){
    $classmeio->erro(true,false);
  }else{
    $classmeio->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","h16_regist",true,1,"h16_regist",true);
</script>