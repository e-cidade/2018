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


/* Formul�rio para consulta do total de ouvidorias para gera��o do relat�rio */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
include("dbforms/db_classesgenericas.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"  ></script>
<script language="JavaScript" src="scripts/strings.js"  ></script>
<script language="JavaScript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
    <form name="form1" method="get" action="">
       <table>
         <tr>
            <td>
              <fieldset><legend><b>Relat�rio de Ouvidorias</b></legend>
              <table>
                <tr height="25">
				  <td align="left"><strong>Periodo:</strong></td>
				  <td align="left">
				    <?
				      db_inputdata("dataini",@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',2);
				      echo "<strong> &nbsp a &nbsp</strong>";
				      db_inputdata("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',2);
				    ?>
				  </td>
				</tr>
               </table>
               <center>
               <table>
                 <tr>
                   <td colspan="2" align="center" style='text-align:center'>
                     <input  name="emite" id="emite" type="button" value="Processar" onclick="js_emite();" >
                   </td>
                </tr>
              </table>
              </center>
              </fieldset>
            </td>
          </tr>
       </table>
     </form>
   </center> 
</body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_emite(){
  qry  = 'dataini='+document.form1.dataini.value;
  qry += '&datafim='+document.form1.datafim.value;

  jan  = window.open('pre2_relatorioouvidoria002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>