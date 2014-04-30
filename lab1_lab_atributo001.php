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
include("classes/db_lab_atributo_classe.php");
include("classes/db_lab_exameatributoligacao_classe.php");
include("classes/db_lab_parametros_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_atributo = new cl_lab_atributo;
$cllab_exameatributoligacao = new cl_lab_exameatributoligacao;
$cllab_parametros = new cl_lab_parametros;

$sSql=$cllab_parametros->sql_query();
$rResult=$cllab_parametros->sql_record($sSql);

$la49_c_estrutural="";
$tamanho=0;
if($cllab_parametros->numrows>0){
    db_fieldsmemory($rResult,0);
    $aVet=explode(".",$la49_c_estrutural);
    $tamanho=count($aVet);
}

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
     
      $cllab_atributo->incluir($la25_i_codigo);
      
      if ($cllab_atributo->erro_status != "0") {
         if($la26_i_exameatributopai!=""){
         	  $cllab_exameatributoligacao->la26_i_exameatributofilho=$cllab_atributo->la25_i_codigo;
         	  $cllab_exameatributoligacao->la26_i_exameatributopai=$la26_i_exameatributopai;
         	  $cllab_exameatributoligacao->incluir(null);
              if ($cllab_exameatributoligacao->erro_status == "0"){
                        
                  $cllab_atributo->erro_status=0;
                  $cllab_atributo->erro_sql   = $cllab_exameatributoligacao->erro_sql;
                  $cllab_atributo->erro_campo = $cllab_exameatributoligacao->erro_campo;
                  $cllab_atributo->erro_banco = $cllab_exameatributoligacao->erro_banco;
                  $cllab_atributo->erro_msg   = $cllab_exameatributoligacao->erro_msg;
                  
              }
         }
      }
      
  db_fim_transacao();
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    if($cllab_parametros->numrows>0){
        include("forms/db_frmlab_atributo.php");
    } else {
        db_msgbox("Estrutural não foi informado nos parâmetros.");
    }
    
    ?>
    </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la26_i_exameatributopai",true,1,"la26_i_exameatributopai",true);
</script>
<?
if(isset($incluir)){
  if($cllab_atributo->erro_status=="0"){
    $cllab_atributo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_atributo->erro_campo!=""){
      echo "<script> document.form1.".$cllab_atributo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_atributo->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_atributo->erro(true,true);
  }
}
?>