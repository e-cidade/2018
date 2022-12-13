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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_cgmtipoempresa_classe.php");
require_once("classes/db_tipoempresa_classe.php");

$oPost = db_utils::postMemory($_POST);
$db_opcao = 33;
$db_botao = false;

$clcgm            = new cl_cgm;
$clcgmtipoempresa = new cl_cgmtipoempresa;
$cltipoempresa    = new cl_tipoempresa;

$clcgm->rotulo->label();
$cltipoempresa->rotulo->label();
$clcgmtipoempresa->rotulo->label();

$lPessoaFisica = true;

if(isset($chavepesquisa)){
  
   $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa));
   if ($result !== false && $result != 0) {
    $db_opcao = 3;
    $db_botao = true;
    $oCgm = db_utils::fieldsMemory($result,0);
    
    if (strlen($oCgm->z01_cgccpf) == 14){
      $lPessoaFisica = false;
    }
   } else {
      //Exibir mensagem de erro
   }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbViewCadEndereco.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
		<?
		  require_once("forms/db_frmcadgeralmunic.php");
		?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.documentos.disabled=false;
         top.corpo.iframe_documentos.location.href='prot1_cadgeraldocumentos001.php?z01_numcgm=".$chavepesquisa."&opcao=3';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('documentos');";
         }
 echo"}\n
    js_db_libera();
    js_findCgm($chavepesquisa);
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>