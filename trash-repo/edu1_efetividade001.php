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
include("classes/db_efetividade_classe.php");
include("classes/db_efetividaderh_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clefetividade = new cl_efetividade;
$clefetividaderh = new cl_efetividaderh;
$db_opcao = 1;
$db_botao = true;
$escola = db_getsession("DB_coddepto");
$result = $clefetividaderh->sql_record($clefetividaderh->sql_query("","*",""," ed98_i_codigo = $efetividaderh"));
db_fieldsmemory($result,0);
if(isset($registro)){
 $array_registro = explode("|",$registro);
 for($y=0;$y<count($array_registro);$y++){
  $array_campos = explode(";",$array_registro[$y]);
  $clefetividade->ed97_i_codigo        = $array_campos[1];
  $clefetividade->ed97_i_efetividaderh = $efetividaderh;
  $clefetividade->ed97_i_rechumano     = $array_campos[2];
  $clefetividade->ed97_i_diasletivos   = $array_campos[3];
  $clefetividade->ed97_i_faltaabon     = $array_campos[4];
  $clefetividade->ed97_i_faltanjust    = $array_campos[5];
  $clefetividade->ed97_t_licenca       = strtoupper($array_campos[6]);
  $clefetividade->ed97_t_horario       = strtoupper($array_campos[7]);
  $clefetividade->ed97_t_obs           = strtoupper($array_campos[8]);
  if($array_campos[0]=="true"){
   if($array_campos[1]==""){
    db_inicio_transacao();
    $clefetividade->incluir($array_campos[1]);
    db_fim_transacao();
   }else{
    db_inicio_transacao();
    $clefetividade->alterar($array_campos[1]);
    db_fim_transacao();
   }
  }elseif($array_campos[0]=="false"){
   if($array_campos[1]!=""){
    db_inicio_transacao();
    $clefetividade->excluir($array_campos[1]);
    db_fim_transacao();
   }
  }
 }
 db_redireciona("edu1_efetividade001.php?efetividaderh=$efetividaderh");
}
if(isset($registrofunc)){
 $array_registro = explode("|",$registrofunc);
 for($y=0;$y<count($array_registro);$y++){
  $array_campos = explode(";",$array_registro[$y]);
  $clefetividade->ed97_i_codigo        = $array_campos[1];
  $clefetividade->ed97_i_efetividaderh = $efetividaderh;
  $clefetividade->ed97_i_rechumano     = $array_campos[2];
  $clefetividade->ed97_i_faltaabon     = $array_campos[3];
  $clefetividade->ed97_i_faltanjust    = $array_campos[4];
  $clefetividade->ed97_i_horacinq      = $array_campos[5];
  $clefetividade->ed97_i_horacem       = $array_campos[6];
  $clefetividade->ed97_t_licenca       = strtoupper($array_campos[7]);
  $clefetividade->ed97_t_obs           = strtoupper($array_campos[8]);
  if($array_campos[0]=="true"){
   if($array_campos[1]==""){
    db_inicio_transacao();
    $clefetividade->incluir($array_campos[1]);
    db_fim_transacao();
   }else{
    db_inicio_transacao();
    $clefetividade->alterar($array_campos[1]);
    db_fim_transacao();
   }
  }elseif($array_campos[0]=="false"){
   if($array_campos[1]!=""){
    db_inicio_transacao();
    $clefetividade->excluir($array_campos[1]);
    db_fim_transacao();
   }
  }
 }
 db_redireciona("edu1_efetividade001.php?efetividaderh=$efetividaderh");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 font-size: 11;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" height="430" valign="top" bgcolor="#CCCCCC">
   <br>
    <?
    if($ed98_c_tipo=="P"){
     include("forms/db_frmefetividadeprof.php");
    }else{
     include("forms/db_frmefetividadefunc.php");
    }
    ?>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clefetividade->erro_status=="0"){
    $clefetividade->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clefetividade->erro_campo!=""){
      echo "<script> document.form1.".$clefetividade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clefetividade->erro_campo.".focus();</script>";
    }
  }else{
    $clefetividade->erro(true,true);
  }
}
?>