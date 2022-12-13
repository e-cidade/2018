<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_vac_aplica_classe.php");
include("classes/db_vac_sala_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
$clvac_aplica  = new cl_vac_aplica;
$clvac_sala    = new cl_vac_sala;
$db_opcao      = 1;
$db_botao      = true;
$iDepartamento = db_getsession("DB_coddepto");
$sDepartamento = db_getsession("DB_nomedepto");
$sSql          = $clvac_sala->sql_query_file("","*",""," vc01_i_unidade=$iDepartamento ");
$rsResult      = $clvac_sala->sql_record($sSql);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load("scripts.js, grid.style.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<?if ($clvac_sala->numrows == 0) {
    echo"<br><br><center><strong><b> Departamento não é um sala de vacinação! </b></strong></center>";
    exit;
  }?>
 <center>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
   <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
     <center>
       <?include("forms/db_frmvac_aerograma.php");?>
     </center>
    </td>
   </tr>
  </table>
 </center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
 js_tabulacaoforms("form1", "vc16_i_cgs", true, 1, "vc16_i_cgs", true);
</script>