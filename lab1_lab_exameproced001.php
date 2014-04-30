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
include("classes/db_lab_exameproced_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_exameproced = new cl_lab_exameproced;
$db_opcao = 1;
$db_botao = true;
$db_botao1 = false;

if(isset($opcao)){
   if( $opcao == "alterar"){
       $db_opcao = 2;
       $db_botao1 = true;
   }else{
       if( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
           $db_opcao = 3;
           $db_botao1 = true;
       }else{
           if(isset($alterar)){
              $db_opcao = 2;
              $db_botao1 = true;
           }
       }
   }
}

if(isset($incluir)){
	
  db_inicio_transacao();
  $sSql=$cllab_exameproced->sql_query2("","*",""," la53_i_procedimento=$la53_i_procedimento and la53_i_exame=$la53_i_exame ");
  $cllab_exameproced->sql_record($sSql);
  if($cllab_exameproced->numrows!=0){
     $cllab_exameproced->erro_status="0";
  	 $cllab_exameproced->erro_msg=" Procedimento ja lançado par este exame! ";
  }else{
  	$cllab_exameproced->erro_status="1";
  }
  if($cllab_exameproced->erro_status!="0"){
    $iAtivo=0;
    if($la53_i_ativo==1){
    	$iAtivo=1;
    }
    $sSql=$cllab_exameproced->sql_query2("","*",""," la53_i_ativo=$iAtivo and la53_i_exame=$la53_i_exame ");
    $cllab_exameproced->sql_record($sSql);
    if($cllab_exameproced->numrows==0){
       $cllab_exameproced->incluir($la53_i_codigo);
    }else{
       $cllab_exameproced->erro_status="0";
       $cllab_exameproced->erro_msg=" Exame pode ter apenas um procedimento ativo! ";
    }
  }
  db_fim_transacao();
  
}else if(isset($alterar)){
	
  db_inicio_transacao();
  $db_opcao = 2;
  
  $sSql=$cllab_exameproced->sql_query2("","*",""," la53_i_procedimento=$la53_i_procedimento and la53_i_exame=$la53_i_exame ");
  $cllab_exameproced->sql_record($sSql);
  if($cllab_exameproced->numrows!=0){

  	 $cllab_exameproced->erro_status="0";
  	 $cllab_exameproced->erro_msg=" Procedimento ja lançado par este exame! ";
  	 
  }else{
  	$cllab_exameproced->erro_status="1";
  }
  if($cllab_exameproced->erro_status!="0"){
    $iAtivo=0;
    if($la53_i_ativo==1){
    	$iAtivo=1;
    }
    $sSql=$cllab_exameproced->sql_query2("","*",""," la53_i_ativo=$iAtivo and la53_i_exame=$la53_i_exame ");
    $cllab_exameproced->sql_record($sSql);
    if($cllab_exameproced->numrows==0){
        $cllab_exameproced->alterar($la53_i_codigo);
    }else{
    	 $cllab_exameproced->erro_status="0";
    	 $cllab_exameproced->erro_msg=" Exame pode ter apenas um procedimento ativo! ";
    }    
    db_fim_transacao();
  }
}else if(isset($excluir)){
	
  db_inicio_transacao();  
  $db_opcao = 3;
  $cllab_exameproced->excluir($la53_i_codigo);
  db_fim_transacao();
  
}else if(isset($chavepesquisa)){
	
   $db_opcao = 2;
   $result = $cllab_exameproced->sql_record($cllab_exameproced->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<fieldset style='width: 95%;'> <legend><b>Exame Procedimento</b></legend>
    <?
	include("forms/db_frmlab_exameproced.php");
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la53_i_ativo",true,1,"la53_i_ativo",true);
</script>
<?
if((isset($incluir))||(isset($alterar))||(isset($excluir))){
  if($cllab_exameproced->erro_status=="0"){
    $cllab_exameproced->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_exameproced->erro_campo!=""){
      echo "<script> document.form1.".$cllab_exameproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_exameproced->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_exameproced->erro(true,false);
    db_redireciona("lab1_lab_exameproced001.php?la53_i_exame=$la53_i_exame&la08_c_descr=$la08_c_descr");
  }
}
?>