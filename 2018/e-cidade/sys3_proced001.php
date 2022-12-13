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
include("dbforms/db_funcoes.php");
include("classes/db_db_sysmodulo_classe.php");
include("classes/db_db_syscadproced_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldb_sysmodulo	= new cl_db_sysmodulo;
$cldb_syscadproced 	= new cl_db_syscadproced;
$clrotulo = new rotulocampo;
$clrotulo->label("codmod");

$db_opcao=1;
$db_botao=true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_pesquisa_codmod(codmod){

  document.form1.submit();
  
}
function js_listaitensproced (codproced){

  js_OpenJanelaIframe('top.corpo','DBI_itensproced','sys3_proced002.php?codproced='+codproced);
  
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table border="0" cellspacing="0" cellpadding="0" height='10%'>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  <td>
  </tr>
</table>
<table>
  <tr>
    <form name="form1" method="post">
    <td align="right" nowrap title="<?=@$Tcodmod?>">
      <strong><?=$Lcodmod?> </strong>
    </td>
    <td align="left" nowrap>
    
    <?
    if(isset($codmod)){
      global $codmod;
    }

    db_selectrecord('codmod',$cldb_sysmodulo->sql_record($cldb_sysmodulo->sql_query(null,"codmod, nomemod","nomemod","")),true,1,'','','','0','js_pesquisa_codmod(this.value)');

    $sql = $cldb_syscadproced->sql_query(null,"db_syscadproced.codmod,nomemod,codproced,descrproced,obsproced",null,(isset($codmod)&&$codmod!=0?" db_syscadproced.codmod = $codmod":null));
    
    echo "</td>
          </form>
          </tr>
          <tr><td colspan=2>";
	
	db_lovrot($sql,20,"()","","js_listaitensproced|codproced");

	echo "</td>";
	echo "</tr>";
    
    ?>
    </td>
  </tr>
</table>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>