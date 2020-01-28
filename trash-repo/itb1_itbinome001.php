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
include("classes/db_itbinome_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbinomecgm_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_POST_VARS,2);
//db_postmemory($HTTP_SERVER_VARS,2);
$clitbinome = new cl_itbinome;
$clitbinomecgm = new cl_itbinomecgm;
$clcgm = new cl_cgm;

/*****************************/
$db_opcao = 1;
$db_botao = true;
/*****************************/
$sqlerro = false;
//db_msgbox($it03_seq." --- ".$it03_seq_old);
if(isset($mostraitbinomecgm) && $mostraitbinomecgm != ""){
  if(isset($it21_numcgm) && $it21_numcgm != ""){
    $rsDadoscgm = $clcgm->sql_record($clcgm->sql_query_file($it21_numcgm,"*",null," z01_numcgm = $it21_numcgm")); 
    if($clcgm->numrows > 0){
      db_fieldsmemory($rsDadoscgm,0);
      $it03_princ    = 't';
      $it03_nome     = $z01_nome;
      $it03_sexo     = $z01_sexo;
      $it03_cpfcnpj  = $z01_cgccpf;
      $it03_endereco = $z01_ender;
      $it03_numero   = $z01_numero;
      $it03_compl    = $z01_compl;
      $it03_cxpostal = $z01_cxpostal;
      $it03_bairro   = $z01_bairro;
      $it03_munic    = $z01_munic;
      $it03_uf       = $z01_uf;
      $it03_cep      = $z01_cep;
      $it03_mail     = $z01_email;
    }
  }
}

if((isset($HTTP_POST_VARS["bt_opcao"]) && $HTTP_POST_VARS["bt_opcao"])=="Incluir"){
  //die($clitbinome->sql_query_file(null,"*",null," it03_guia = $it03_guia and it03_seq != $it03_seq and it03_princ = 't' and upper(it03_tipo) = 'T'")); 
  db_inicio_transacao();
  $clitbinome->it03_tipo = "T"; 
  $clitbinome->incluir($it03_seq);
  if(isset($clitbinome->erro_status) && $clitbinome->erro_status == 0){
    $erro = $clitbinome->erro_msg;
    $sqlerro = true;
    db_msgbox("nome".$erro); 
  }
  
  if(isset($it21_numcgm) && $it21_numcgm != ""){
    $clitbinomecgm->it21_numcgm   = $it21_numcgm;
    $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
    $clitbinomecgm->incluir(null);
    if(isset($clitbinomecgm->erro_status) && $clitbinomecgm->erro_status == 0){
      $erro = $clitbinomecgm->erro_msg;
      $sqlerro = true;
      db_msgbox("nomecgm".$erro); 
    }
  }
  //die("chegou");
  $db_opcao = 1;
  echo "<script>
  parent.iframe_transm.location.href = 'itb1_itbinome001.php?it03_guia=".$it03_guia."&it03_seq=".$it03_seq."'; 
  js_novo();
  </script>";
  db_fim_transacao($sqlerro);
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
<form name="form1" method="post" action="">
  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>
      <?
        include("forms/db_frmitbinome.php");
      ?>
    </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clitbinome->erro_status=="0"){
    $clitbinome->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitbinome->erro_campo!=""){
      echo "<script> document.form1.".$clitbinome->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbinome->erro_campo.".focus();</script>";
      echo "<script>
      parent.iframe_compnome.location.href = 'itb1_itbinome001.php?it03_guia=".$it03_guia."'; 
      parent.iframe_constr.location.href = 'itb1_itbiconstr001.php?it08_guia=".$it03_guia."'; 
      parent.document.formaba.constr.disabled=false; 
      </script>";
    };
  }else{
    $clitbinome->erro(true,false);
    echo "<script>
    parent.iframe_compnome.location.href = 'itb1_itbinome001.php?it03_guia=".$it03_guia."'; 
    parent.iframe_constr.location.href = 'itb1_itbiconstr001.php?it08_guia=".$it03_guia."'; 
    parent.document.formaba.constr.disabled=false; 
    </script>";
  };
};
?>