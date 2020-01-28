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
include("classes/db_sepulturas_classe.php");
include("classes/db_lotecemit_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsepulturas = new cl_sepulturas;
$cllotecemit = new cl_lotecemit;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){


  db_inicio_transacao();
  $db_opcao = 2;
  $erro=false;
  $clsepulturas->alterar($cm05_i_codigo);

  /*$cllotecemit->sql_record( $cllotecemit->sql_query("","*","","cm23_i_codigo = $cm05_i_lotecemit and cm23_b_selecionado=false" ) );
  if( $cllotecemit->numrows != 0 and ($cm05_i_lotecemit_ant != $cm05_i_lotecemit)){
     $sql1 = " update lotecemit set cm23_b_selecionado = 'true' where cm23_i_codigo = $cm05_i_lotecemit";
     $sql2 = " update lotecemit set cm23_b_selecionado = 'false' where cm23_i_codigo = $cm05_i_lotecemit_ant";
     @pg_exec($sql1);
     @pg_exec($sql2);
  }else if($cm05_i_lotecemit_ant != $cm05_i_lotecemit){
      $erro = true;
      $db_opcao = 22;
      db_msgbox('Aviso:\nLote já foi selecionado para um Ossoário/Jazigo ou Sepultura'); 
      unset($alterar);
  }*/

  db_fim_transacao($erro);
  
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   if(file_exists("funcoes/db_func_sepulturas.php")==true){
      include("funcoes/db_func_sepulturas.php");
   }else{
      $campos = "*";
   }
   $result = $clsepulturas->sql_record($clsepulturas->sql_query($chavepesquisa, $campos));
   db_fieldsmemory($result,0);
   $cm05_i_lotecemit_ant = $cm05_i_lotecemit;
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <br><br>
     <?
     include("forms/db_frmsepulturas.php");
     ?>
    </center>
     </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clsepulturas->erro_status=="0"){
    $clsepulturas->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsepulturas->erro_campo!=""){
      echo "<script> document.form1.".$clsepulturas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsepulturas->erro_campo.".focus();</script>";
    }
  }else{
    $clsepulturas->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","cm05_i_campa",true,1,"cm05_i_campa",true);
</script>