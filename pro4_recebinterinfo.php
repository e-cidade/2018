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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_proctransferint_classe.php");
include("classes/db_proctransferintand_classe.php");
include("classes/db_proctransferintusu_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_procandamint_classe.php");
include("classes/db_procandamintand_classe.php");

$clprocandamint = new cl_procandamint;
$clprocandamintand = new cl_procandamintand;
$clproctransferint = new cl_proctransferint;
$clproctransferintand = new cl_proctransferintand;
$clproctransferintusu = new cl_proctransferintusu;
$clprotprocesso = new cl_protprocesso;

$clproctransferint->rotulo->label();
$clprotprocesso->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p61_id_usuario");
$clrotulo->label("p68_codproc");
$clrotulo->label("p89_usuario");
$clrotulo->label("nome");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_fecha(){
  parent.db_iframe_recebinterinfo.hide();
}
</script>  
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" target="" action="pro4_tranferinter001.php">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
<tr>
  <td colspan=2 align='center'>
    <input name="fechar" type="button" value="Fechar" onclick='js_fecha();' >
  </td>
</tr>
<? $sql = "select * 
           from proctransferintand  
	          inner join procandam on p61_codandam = p87_codandam 
		  inner join protprocesso on p61_codproc = p58_codproc 
		  inner join cgm on p58_numcgm = z01_numcgm
           where p87_codtransferint = $p88_codigo
	   ";
       $result=pg_exec($sql);
       $numrows=pg_numrows($result);
       if($numrows>0){ 
          echo "
	  <br><br>
	  <table>
           <tr>
	     <td class='cabec' align='center'  title='$Tp58_codproc'>".str_replace(":","",$Lp58_codproc)."</td>
	     <td class='cabec' align='center'  title='$Tp58_requer'>".str_replace(":","",$Lp58_requer)."</td>
	     <td class='cabec' align='center'  title='$Tp58_dtproc'>".str_replace(":","",$Lp58_dtproc)."</td>
	     <td class='cabec' align='center'  title='$Tp58_hora'>".str_replace(":","",$Lp58_hora)."</td>
	     <td class='cabec' align='center'  title='$Tz01_nome'>".str_replace(":","",$Lz01_nome)."</td>
	   </tr>
          "; 	   
       }else{
         echo "<br><br><b>Sem Processos!!</b>";
       }
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
	 echo"
		   <tr>
		      <td  class='corpo'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		      <td  class='corpo'  align='center' title='$Tp58_requer'><label style=\"cursor: hand\"><small>$p58_requer</small></label></td>
		      <td  class='corpo'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>".db_formatar($p58_dtproc,'d')."</small></label></td>
		      <td  class='corpo'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		      <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
		   </tr>";
       }
       echo"
          </table>";	        
       

  ?>
  </td>
  </tr>
  </table>
  </form>
</center>
</body>
</html>