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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoAreaConhecimento = db_utils::getdao('areaconhecimento');

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

  db_inicio_transacao();

  $oDaoAreaConhecimento->incluir($ed293_sequencial);
  
  db_fim_transacao();

} 
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    
    <?
      $sLibs  = "scripts.js,prototype.js,webseller.js,strings.js,datagrid.widget.js,";
      $sLibs .= "estilos.css,grid.style.css";
      db_app::load($sLibs);
    ?>

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
    <center>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
          <center>
	          <?
	            include("forms/db_frmareaconhecimento.php");
	          ?>
          </center>
	      </td>
      </tr>
    </table>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),db_getsession("DB_instit")
             );
    ?>
  </body>
</html>

<script>
  js_tabulacaoforms("form1", "ed293_descr", true, 1, "ed293_descr", true);
</script>

<?

if (isset($incluir)) {

  if ($oDaoAreaConhecimento->erro_status == "0") {

    $oDaoAreaConhecimento->erro(true, false);
    $db_botao = true;
    
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoAreaConhecimento->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAreaConhecimento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAreaConhecimento->erro_campo.".focus();</script>";
    
    }

  } else {
    $oDaoAreaConhecimento->erro(true, false);
    db_redireciona("edu1_areaconhecimento001.php");
  }

}

?>