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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_vac_sala_classe.php");
require_once("classes/db_vac_descarte_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE        = new cl_iframe_alterar_excluir;
$oDaoVacDescarte  = new cl_vac_descarte;
$oDaoVacSala      = new cl_vac_sala;
$iDepartamento    = db_getsession("DB_coddepto");
$sSql             = $oDaoVacSala->sql_query_file("","*",""," vc01_i_unidade=$iDepartamento ");
$rsResult         = $oDaoVacSala->sql_record($sSql);
$db_opcao         = 1;
$db_botao         = true;
$iDepartamento    = db_getsession("DB_coddepto");

if (isset($opcao)) {

  if ( $opcao == "alterar") {
   
    $sSql     = $oDaoVacDescarte->sql_query($vc19_i_codigo);
    $rsResult = $oDaoVacDescarte->sql_record($sSql);
    if($oDaoVacDescarte->numrows>0){
      db_fieldsmemory($rsResult,0);
    }
    $db_opcao = 2;

  } else {
  
    if ($opcao == 'excluir' || isset($db_opcao) && $db_opcao == 3) {

     $sSql     = $oDaoVacDescarte->sql_query($vc19_i_codigo);
     $rsResult = $oDaoVacDescarte->sql_record($sSql);
     if($oDaoVacDescarte->numrows>0){
       db_fieldsmemory($rsResult,0);
     }
     $db_opcao  = 3;
     $db_botao1 = true;

    } else {

      if (isset($alterar)) {

        $db_opcao = 2;
        $db_botao1 = true;

      }
    }
  }
}

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoVacDescarte->vc19_i_usuario = db_getsession('DB_id_usuario');
  $oDaoVacDescarte->vc19_d_data    = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoVacDescarte->vc19_c_hora    = date('H:i');
  $oDaoVacDescarte->incluir(null);
  db_fim_transacao($oDaoVacDescarte->erro_status == '0' ? true : false);

} elseif (isset($alterar)) {

  db_inicio_transacao();
  $opcao                          = 'alterar';
  $db_opcao                       = 2;
  $oDaoVacDescarte->vc19_i_codigo = $vc19_i_codigo; 
  $oDaoVacDescarte->alterar($vc19_i_codigo);
  db_fim_transacao($oDaoVacDescarte->erro_status == '0' ? true : false);

} elseif (isset($excluir)) {

  db_inicio_transacao();
  $opcao                          = 'excluir';
  $db_opcao                       = 3;
  $oDaoVacDescarte->vc19_i_codigo = $vc19_i_codigo; 
  $oDaoVacDescarte->excluir($vc19_i_codigo);
  db_fim_transacao($oDaoVacDescarte->erro_status == '0' ? true : false);

}
if (isset($vc19_i_codigo)) {
	$rsResult = $oDaoVacDescarte->sql_record($oDaoVacDescarte->sql_query2($vc19_i_codigo));
	if ($oDaoVacDescarte->numrows > 0) {
	  db_fieldsmemory($rsResult,0);
	} else {
		db_msgbox("Descarte não encontrado!");
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
<?
if ((isset($incluir)) || (isset($alterar)) || (isset($excluir))) {

  if ($oDaoVacDescarte->erro_status == "0") {

    $oDaoVacDescarte->erro(true, false);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

  } else {

    $oDaoVacDescarte->erro(true,false);
    db_redireciona("vac4_descartar001.php");

  }

}
  
if ($oDaoVacSala->numrows == 0) {

  echo"<br><br><center><strong><b> Departamento não é um sala de vacinação! </b></strong></center></center></center>";
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  exit;

}
  
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?
        require_once("forms/db_frmvac_descartar.php");
        ?>
      </center>
    </td>
  </tr>
</table>

<script>
js_tabulacaoforms("form1", "vc19_i_vacina", true, 1, "vc19_i_vacina", true);
</script>
</center>
</body>
</html>