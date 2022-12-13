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
require_once("classes/db_propri_classe.php");
require_once("classes/db_iptubase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao    = 1;
$db_opcao    = 1;
$outros      = false; 
$testasel    = false; 

$cliptubase  = new cl_iptubase;
$clpropri    = new cl_propri;
$clrotulo    = new rotulocampo;
$rotulocampo = new rotulocampo;

$cliptubase->rotulo->label();
$clpropri->rotulo->label();
$clrotulo->label("j01_numcgm");
$rotulocampo->label("z01_nome");  

if(isset($alterando)){
  $j42_matric = $j01_matric;
}

if(isset($atualizar)){
  db_redireciona("cad1_proprialt.php?j42_matric=$j42_matric" );
}

if(isset($incluir)){

   db_inicio_transacao();
   $clpropri->incluir($j42_matric,$j42_numcgm);
   db_fim_transacao();
   $j42_numcgm="";
   $z01_nome="";
   $outros = true; 
}else if(isset($excluir)){

   $clpropri->excluir($j42_matric,$j42_numcgm);
   $j42_numcgm="";
   $z01_nome="";
}else if(isset($j42_matric)){  

   if(isset($j42_matric) && isset($j42_numcgm)){

     $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,$j42_numcgm,"propri.*#cgm.z01_nome#a.z01_nome as z01_nomematri"));
     db_fieldsmemory($result,0);
     $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","propri.*#cgm.z01_nome"));
     $j42_numalt=$j42_numcgm; 
     if($clpropri->numrows > 1){ 
       $outros=true; 
     }else{
       $outros = false;
     } 

   }else{

     $result = $clpropri->sql_record($clpropri->sql_query($j42_matric,"","a.z01_nome as z01_nomematri"));
     @db_fieldsmemory($result,0);
     if($clpropri->numrows!=0){

       $db_opcao=2;
       $outros=true; 
     }else{

       $result = $cliptubase->sql_record($cliptubase->sql_query($j42_matric,"z01_nome as z01_nomematri",""));
       @db_fieldsmemory($result,0);
       $db_opcao=1;
     }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()">
  <br /><br />
  <table height="430" align="center" width="790" border="0" cellspacing="0" cellpadding="0">
    <form name="form1" method="post" onSubmit="return js_verifica_campos_digitados();" action="">
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC">
          <center>
            <?
            require_once("forms/db_frmproprialt.php");
            ?> 
          </center> 
        </td>
      </tr>         
    </form>
  </table>
</body>
</html>
<?
if(isset($incluir)||isset($excluir)){
  if($clpropri->erro_status=="0"){
    $clpropri->erro(true,false);
    if($clpropri->erro_campo!=""){
      echo "<script> document.form1.".$clpropri->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpropri->erro_campo.".focus();</script>";
    }
  }else{
    $clpropri->erro(true,false);
    db_redireciona("cad1_proprialt.php?j42_matric=$j42_matric" );
  }
}
?>