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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("p58_numero");
$clrotulo->label("p58_dtproc");
$clrotulo->label("p58_hora");
$clrotulo->label("z01_nome");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  </tr>
    <td colspan=2 align='center' >
  <?  
       $sSqlProcessos  = "select * ";
       $sSqlProcessos .= "  from proctransferintand ";
       $sSqlProcessos .= "       inner join procandam    on p61_codandam = p87_codandam ";
       $sSqlProcessos .= "       inner join protprocesso on p61_codproc  = p58_codproc ";
       $sSqlProcessos .= "       inner join cgm          on z01_numcgm   = p58_numcgm ";
       $sSqlProcessos .= " where p87_codtransferint={$cod}";
       $result=pg_exec($sSqlProcessos);
       $numrows=pg_numrows($result);
       if($numrows>0){ 
          echo "
	  <br><br>
	  <table>
           <tr>
	     <td class='bordas'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='bordas' align='center'  title='$Tp58_codproc'>".str_replace(":","",$Lp58_codproc)."</td>
	     <td class='bordas' align='center'  title='$Tp58_numero'>".str_replace(":","",$Lp58_numero)."</td>
	     <td class='bordas' align='center'  title='$Tp58_dtproc'>".str_replace(":","",$Lp58_dtproc)."</td>
	     <td class='bordas' align='center'  title='$Tp58_hora'>".str_replace(":","",$Lp58_hora)."</td>
	     <td class='bordas' align='center'  title='$Tz01_nome'><b>Titular do Processo</b></td>
	   </tr>
          "; 	   
       }else{
         echo "<br><br><b>Sem Processos!!</b>";
       }
       $usuario=db_getsession("DB_id_usuario");
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
		 echo"
		   <tr>
		      <td  class='bordas2' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p61_codandam' id='CHECK_".$p61_codandam."'></td>
		      <td  class='bordas2'  align='center' title='$Tp58_codproc'><label style=\"cursor: hand\"><small>$p58_codproc</small></label></td>
		      <td  class='bordas2'  align='center' title='$Tp58_numero'><label style=\"cursor: hand\"><small>{$p58_numero}/{$p58_ano}</small></label></td>
		      <td  class='bordas2'  align='center' title='$Tp58_dtproc'><label style=\"cursor: hand\"><small>".db_formatar($p58_dtproc,'d')."</small></label></td>
		      <td  class='bordas2'  align='center' title='$Tp58_hora'><label style=\"cursor: hand\"><small>$p58_hora</small></label></td>
		      <td  class='bordas2'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
		   </tr>";
       } echo"
	   </table>";	        
       

  ?>
  </td>
  </tr>
</table>
</form>
<script>
</script>
</body>
</html>