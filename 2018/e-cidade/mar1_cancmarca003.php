<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("classes/db_cancmarca_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_marca_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcancmarca = new cl_cancmarca;
$clcgm = new cl_cgm;
$clmarca = new cl_marca;
$clcgm->rotulo->label();
$db_botao = false;
@$ma03_d_data_dia = date("d",db_getsession("DB_datausu"));
@$ma03_d_data_mes = date("m",db_getsession("DB_datausu"));
@$ma03_d_data_ano = date("Y",db_getsession("DB_datausu"));
$db_opcao = 33;
if(isset($reativar)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clcancmarca->msg = 3;
  $clcancmarca->ma03_c_tipo = "R";
  $clcancmarca->incluir($ma03_i_codigo);
  $clmarca->ma01_c_ativo = 'S';
  $clmarca->ma01_i_codigo = $ma03_i_marca;
  $clmarca->alterar($clmarca->ma01_c_ativo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $campos = "cancmarca.ma03_i_marca,a.z01_nome";
   $db_opcao = 3;
   $result = $clcancmarca->sql_record($clcancmarca->sql_query("",$campos,"","ma03_i_marca = $chavepesquisa"));
   db_fieldsmemory($result,0);
   $ma03_i_codigo = '';
   $p58_codproc = '';
   $ma03_i_codproc = '';
   $ma03_t_obs = '';
   $db_botao = true;
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
        include("forms/db_frmcancmarca.php");
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
if(isset($reativar)){
  if($clcancmarca->erro_status=="0"){
    $clcancmarca->erro(true,false);
  }else{
    $clcancmarca->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>