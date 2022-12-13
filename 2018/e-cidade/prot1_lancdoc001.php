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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_caddocumento_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($_POST);
db_postmemory($_GET);

  if (isset($_GET['consulta'])) {
    $consulta = true;
  } else {
    $consulta = false;
  }
      
$db_opcao = 1;
$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<?
  db_app::load("scripts.js, 
                prototype.js, 
                strings.js, 
                datagrid.widget.js, 
                dbmessageBoard.widget.js, 
                classes/dbViewCadastroDocumento.js, 
                widgets/windowAux.widget.js,
                widgets/dbtextField.widget.js,
                widgets/dbtextFieldData.widget.js");
  
  db_app::load("estilos.css,
                grid.style.css");
  

?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="1" marginheight="0" 
      onLoad="<?=isset($z06_numcgm) ? 'js_consultaDocumentos();':'';?>" >
<?
//Se for uma consulta remove tabela de identação
  if (isset($consulta)) {
    if ($consulta == false) {
?>
      <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
        <tr> 
          <td width="360" height="18">&nbsp;</td>
          <td width="263">&nbsp;</td>
          <td width="25">&nbsp;</td>
          <td width="140">&nbsp;</td>
        </tr>
      </table>
<?
    }
  }
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
  <?
  include("forms/db_frmlancdoc.php");
  ?>
    </center>
  </td>
  </tr>
</table>


<?
//Se for uma consulta remove os menus
if (isset($consulta)) {
  if ($consulta == false) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
}
?>
</body>
</html>