<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("classes/db_empagetipo_classe.php"));
include(modification("classes/db_conplanoconta_classe.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

$clempagetipo 		= new cl_empagetipo;
$clconplanoconta 	= new cl_conplanoconta;

$db_opcao = 1;
$db_botao = true;
$iAnoUsu  = db_getsession('DB_anousu');

if (isset($incluir)) {

	$lerro    = false;
	$erro_msg = "";

  $sCampos = " e83_codtipo ";
  $sWhere  = " e83_conta = {$e83_conta} ";
  $sSql    = $clempagetipo->sql_query(null, $sCampos, null, $sWhere);

  $clempagetipo->sql_record($sSql);
  if ($clempagetipo->numrows > 0) {

    $lerro     = true;
    $erro_msg  = "A Conta informada já está vinculada a uma Conta Pagadora. \n";
    $erro_msg .= "Não é possível vincular uma Conta a mais de uma Conta Pagadora.";
  }

  if (!$lerro) {

    db_inicio_transacao();

    $sql = " select c61_codcon,                                            ";
    $sql .= "        c60_codsis,                                            ";
    $sql .= "        c63_codcon                                             ";
    $sql .= "   from conplano                                               ";
    $sql .= "        inner join conplanoreduz on c61_codcon = c60_codcon    ";
    $sql .= "                                and c61_anousu = c60_anousu    ";
    $sql .= "        left join conplanoconta on c61_codcon  = c63_codcon    ";
    $sql .= "                               and c60_anousu  = c63_anousu    ";
    $sql .= "   where c61_reduz  = {$e83_conta}                             ";
    $sql .= "     and c60_anousu = {$iAnoUsu}                               ";

    $rsSql = $clconplanoconta->sql_record($sql);

    if ($clconplanoconta->numrows > 0) {

      $oConPlan = db_utils::fieldsMemory($rsSql, 0);

      if ($oConPlan->c60_codsis == 6) {

        if ($oConPlan->c63_codcon == "") {

          $lerro    = true;
          $erro_msg = "Usuário:\\n\\nA conta selecionada não possui uma conta bancária cadastrada!\\n\\n";
        }
      }

    }

    if (!$lerro) {

      $clempagetipo->e83_codigocompromisso = str_pad($e83_codigocompromisso, 4, "0", STR_PAD_LEFT);
      $clempagetipo->incluir($e83_codtipo);
      $erro_msg = $clempagetipo->erro_msg;
      if ($clempagetipo->erro_status == "0") {
        $lerro = true;
      }
    }

    db_fim_transacao($lerro);
  }
}
if (isset($e83_conta) && $e83_conta != '' && $db_opcao == 1) {
  $e83_sequencia = $clempagetipo->getMaxCheque($e83_conta);
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
<table width="680" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top">
			<?
			  include(modification("forms/db_frmempagetipo.php"));
			?>
	  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($incluir)) {
	
	if (!$lerro) {
		db_msgbox($erro_msg);
	} else {

	  $db_botao = true;
	  db_msgbox($erro_msg);
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clempagetipo->erro_campo != "") {
        
      echo "<script> document.form1.".$clempagetipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempagetipo->erro_campo.".focus();</script>";
    }
	}
}
?>