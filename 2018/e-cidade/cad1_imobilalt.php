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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_imobil_classe.php");
require_once("classes/db_iptubase_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_botao=1;
$db_opcao=1;

$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$climobil = new cl_imobil;
$climobil->rotulo->label();
$climobil->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("j01_numcgm");
$rotulocampo = new rotulocampo;
$rotulocampo->label("z01_nome");  

if(isset($alterando)){
  $j44_matric = $j01_matric;
}

if(isset($atualizar)){
   db_inicio_transacao();
   $result = $climobil->sql_record($climobil->sql_query_file($j44_matric,"*","",""));
   @db_fieldsmemory($result,0);
   if($climobil->numrows==0){
     $climobil->incluir($j44_matric);
   }else{
     $climobil->alterar($j44_matric);
   }
   db_fim_transacao();
}else if(isset($excluir)){
  $climobil->excluir($j44_matric);
  $db_opcao=3;    
    
}else if(isset($j44_matric)){
 $result = $climobil->sql_record($climobil->sql_query($j44_matric,"j44_numcgm#cgm.z01_nome#a.z01_nome as z01_nomematri","",""));
 if($climobil->numrows!=0){
   @db_fieldsmemory($result,0);
   $db_opcao=3;
 }else{
   $result = $cliptubase->sql_record($cliptubase->sql_query($j44_matric,"z01_nome as z01_nomematri",""));
   @db_fieldsmemory($result,0);
   $db_opcao=1;  
 } 
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <br /><br />
    <table height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
      <form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
        <tr>
          <td valign="top" bgcolor="#CCCCCC">
            <center>
              <?
              include("forms/db_frmimobilalt.php");
              ?>  
            </center>
          </td>
        </tr>
      </form>
    </table>
  </body>
</html>
<?
if(isset($excluir)){
  if($climobil->erro_status=="0"){
    $climobil->erro(true,false);
    if($climobil->erro_campo!=""){
      echo "<script> document.form1.".$climobil->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$climobil->erro_campo.".focus();</script>";
    }
  }else{
    $climobil->erro(true,false);
    db_redireciona("cad1_imobilalt.php?j44_matric=$j44_matric&z01_nomematri=$z01_nomematri");
  }
}
if(isset($atualizar)){
  if($climobil->erro_status=="0"){
    $climobil->erro(true,false);
    if($climobil->erro_campo!=""){
      echo "<script> document.form1.".$climobil->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$climobil->erro_campo.".focus();</script>";
    }
  }else{
    $climobil->erro(true,false);
    db_redireciona("cad1_imobilalt.php?j44_matric=$j44_matric");
  }
}
?>