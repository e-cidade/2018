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
require_once("classes/db_promitente_classe.php");
require_once("classes/db_iptubase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao  = 1;
$db_opcao  = 1;
$numrows00 = 0;

$cliptubase   = new cl_iptubase;
$clpromitente = new cl_promitente;
$clrotulo     = new rotulocampo;
$rotulocampo  = new rotulocampo;

$cliptubase->rotulo->label();
$clpromitente->rotulo->label();
$clpromitente->rotulo->tlabel();
$rotulocampo->label("z01_nome");  
$clrotulo->label("j01_numcgm");

$db_op        = $db_opcao;
$db_op02      = $db_opcao;
$outros       = false;

if(isset($alterando)){
  $j41_matric = $j01_matric;
}

if(isset($incluir)){

   db_inicio_transacao();

   $verifica=false;
   $result = $clpromitente->sql_record($clpromitente->sql_query_file($j41_matric,"","j41_tipopro as tipopro#j41_numcgm as numcgm","",""));
   $numrows = $clpromitente->numrows;
   for($i=0;$i<$numrows;$i++){

     db_fieldsmemory($result,$i);

     if($j41_numcgm!=$numcgm){

       $clpromitente->j41_tipopro=($j41_tipopro=='t'?'false':$tipopro);
       $clpromitente->j41_promitipo=$j41_promitipo;  
       $clpromitente->j41_numcgm=$numcgm;  
       $clpromitente->alterar($j41_matric,$numcgm);
     }
   }

   
   $clpromitente->j41_numcgm=$j41_numcgm;
   $clpromitente->j41_tipopro=$j41_tipopro;
   $clpromitente->j41_promitipo=$j41_promitipo;  
   $clpromitente->incluir($j41_matric,$j41_numcgm);

   if($clpromitente->erro_status=="0"){
     $outros=true;
   }

   db_fim_transacao();

}else if(isset($alterar)){

   db_inicio_transacao();

   $result = $clpromitente->sql_record($clpromitente->sql_query_file($j41_matric,"","j41_tipopro as tipopro#j41_numcgm as numcgm","",""));
   $numrows = $clpromitente->numrows;
   for($i=0;$i<$numrows;$i++){

     db_fieldsmemory($result,$i);

     if($j41_numcgm!=$numcgm){

       $clpromitente->j41_tipopro=($j41_tipopro=='t'?'false':$tipopro);
       $clpromitente->j41_promitipo=$j41_promitipo;  
       $clpromitente->j41_numcgm=$numcgm;  
       $clpromitente->alterar($j41_matric,$numcgm);
     }
   }

   $clpromitente->j41_numcgm=$j41_numcgm;
   $clpromitente->j41_tipopro=$j41_tipopro;
   $clpromitente->j41_promitipo=$j41_promitipo;  
   $clpromitente->alterar($j41_matric,$j41_numcgm);
   db_fim_transacao();
   
}else if(isset($excluir)){
     $clpromitente->excluir($j41_matric,$j41_numcgm);
}else if(isset($j41_matric) && isset($j41_numcgm)){  

   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","promitente.*#cgm.z01_nome#a.z01_nome as z01_nomematri","","j41_numcgm=$j41_numcgm and j41_matric = $j41_matric "));
   db_fieldsmemory($result,0);

   if($j41_tipopro=='t'){

      $db_op='3';
      $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","promitente.*#cgm.z01_nome#a.z01_nome as z01_nomematri","","j41_matric = $j41_matric "));
      if($clpromitente->numrows>1){    
       $db_op02='3';
      } 
   }
   $db_opcao=2;
   $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","cgm.z01_nome"));
   $numcgm=$j41_numcgm;

   if($clpromitente->numrows > 0){

     $outros=true;
     $recol="ok";
   }
}else if(isset($j41_matric) && !isset($j41_numcgm)){  

  $result = $clpromitente->sql_record($clpromitente->sql_query($j41_matric,"","j41_promitipo#j41_tipopro#a.z01_nome as z01_nomematri","",""));
  $numrows00=$clpromitente->numrows;     
   if($clpromitente->numrows>0){
      @db_fieldsmemory($result,0);
	$db_opcao=1;
      $outros=true;
   }else{

      $result = $cliptubase->sql_record($cliptubase->sql_query($j41_matric,"z01_nome as z01_nomematri",""));
      @db_fieldsmemory($result,0);
        $db_opcao=1;
   }
}  
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
#j41_promitipo, #j41_tipopro {
  width:120px;
}
-->
</style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()">

  <br /><br />

  <table height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
    <tr>
      <td align="left" valign="top" bgcolor="#CCCCCC">
       <center> 
       <?
         include("forms/db_frmpromitentealt.php");
       ?> 
      </center>
      </td>
    </tr>
</form>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) ||isset($excluir)){
  if($clpromitente->erro_status=="0"){
    $clpromitente->erro(true,false);
    if($clpromitente->erro_campo!=""){
      echo "<script> document.form1.".$clpromitente->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpromitente->erro_campo.".focus();</script>";
    }
  }else{
    $clpromitente->erro(true,false);
    db_redireciona("cad1_promitentealt.php?j41_matric=$j41_matric&z01_nomematri=$z01_nomematri");
  }
}
?>