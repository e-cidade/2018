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
include("classes/db_solicita_classe.php");
include("classes/db_solicitatipo_classe.php");
include("classes/db_solicitem_classe.php");
include("classes/db_solicitempcmater_classe.php");
include("classes/db_solicitemunid_classe.php");
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
include("classes/db_pcorcamitemproc_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcdotac_classe.php");
include("classes/db_pcproc_classe.php");
include("classes/db_pcprocitem_classe.php");
include("classes/db_solicitemprot_classe.php");
include("classes/db_solandam_classe.php");
$clsolicita         = new cl_solicita;
$clsolicitatipo     = new cl_solicitatipo;
$clsolicitem        = new cl_solicitem;
$clsolicitempcmater = new cl_solicitempcmater;
$clsolicitemunid    = new cl_solicitemunid;
$clpcorcam          = new cl_pcorcam;
$clpcorcamitem      = new cl_pcorcamitem;
$clpcorcamitemsol   = new cl_pcorcamitemsol;
$clpcorcamitemproc  = new cl_pcorcamitemproc;
$clpcorcamval       = new cl_pcorcamval;
$clpcorcamjulg      = new cl_pcorcamjulg;
$clpcdotac          = new cl_pcdotac;
$clpcproc           = new cl_pcproc;
$clpcprocitem       = new cl_pcprocitem;
$clsolicitemprot    = new cl_solicitemprot;
$clsolandam         = new cl_solandam;
$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();
$clsolicitatipo->rotulo->label();
$clsolicitem->rotulo->label();
$clsolicitempcmater->rotulo->label();
$clsolicitemunid->rotulo->label();
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">

</script>
<script>
function js_anda(codigo){
	js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','com3_anditem001.php?codigo='+codigo,'Andamentos',true);	
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
<center>
<form name="form1" method="post" action="com3_conssolic002.php">
<?
if(isset($solicitacao)){
	$result_and = $clsolicitemprot->sql_record($clsolicitemprot->sql_query_and(null," distinct pc11_codigo,pc01_codmater,pc01_descrmater",null,"pc11_numero=$numero"));
    if ($clsolicitemprot->numrows>0){
    	echo "<table>";
    	for($w=0;$w<$clsolicitemprot->numrows;$w++){
    		db_fieldsmemory($result_and,$w);
    		$result_loc=$clsolandam->sql_record($clsolandam->sql_query(null,"*","pc43_codigo desc limit 1","pc43_solicitem=$pc11_codigo"));
    		if ($clsolandam->numrows>0){
    			db_fieldsmemory($result_loc,0);
    		}    		
    		echo "<tr>";
    		echo "<td><b>Item:</b> <a href='#' onclick='js_anda($pc11_codigo);' > $pc11_codigo-$pc01_descrmater</a > <b>Andamento:</b>$coddepto-$descrdepto</td>";
    		echo "</tr>";    		    	
        }
        echo "</table>";
    }else{
    	echo "<b>Nenhum registro encontrado!!</b>";
    }    
}
?>
</form>
</center>
<script>
</script>
</body>
</html>