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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgs_und_classe.php");
$cl_cgs_und = new cl_cgs_und;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<br>
<?
if(isset($Processar)){

   $campos=" z01_i_cgsund,z01_v_nome ";
	 $sql=$cl_cgs_und->sql_query("",$campos,""," z01_i_familiamicroarea = $z01_i_familiamicroarea ");
   $repassa = array("chave_z01_i_cgsund"=>@$chave_z01_i_cgsund);
   db_lovrot($sql,15,"()","","js_cgs|z01_i_cgsund|z01_v_nome");
    
}
?>
</center>
</body>
</html>
<script>
 function js_cgs(z01_i_cgsund,z01_v_nome){
         iTop = ( screen.availHeight-600 ) / 2;
         iLeft = ( screen.availWidth-950 ) / 2;
         parent.js_OpenJanelaIframe('','iframeprontuarios','sau3_pacientefamilia003.php?z01_i_cgsund='+z01_i_cgsund+'&z01_v_nome='+z01_v_nome,'Consulta prontuarios',true,iTop,iLeft,890,450);
 }
</script>