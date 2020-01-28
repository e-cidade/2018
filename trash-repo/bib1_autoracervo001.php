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
include("classes/db_autoracervo_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clautoracervo = new cl_autoracervo;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 $clautoracervo->pagina_retorno = "bib1_autoracervo001.php?bi21_acervo=$bi21_acervo&bi06_titulo=$bi06_titulo";
 $clautoracervo->incluir(null);
 db_fim_transacao();
}
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $clautoracervo->pagina_retorno = "bib1_autoracervo001.php?bi21_acervo=$bi21_acervo&bi06_titulo=$bi06_titulo";
 $clautoracervo->alterar();
 db_fim_transacao();
}
if(isset($excluir)){
 db_inicio_transacao();
 $db_opcao = 3;
 $clautoracervo->pagina_retorno = "bib1_autoracervo001.php?bi21_acervo=$bi21_acervo&bi06_titulo=$bi06_titulo";
 $clautoracervo->excluir("","bi21_acervo = $bi21_acervo and bi21_autor = $bi21_autor");
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:90%"><legend><b>Autores do Acervo</b></legend>
    <?include("forms/db_frmautoracervo.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<script>
js_tabulacaoforms("form1","bi21_autor",true,1,"bi21_autor",true);
</script>
</body>
</html>
<?
if(isset($incluir)){
  if($clautoracervo->erro_status=="0"){
    $clautoracervo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautoracervo->erro_campo!=""){
      echo "<script> document.form1.".$clautoracervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautoracervo->erro_campo.".focus();</script>";
    };
  }else{
    $clautoracervo->erro(true,true);
  };
};

if(isset($alterar)){
  if($clautoracervo->erro_status=="0"){
    $clautoracervo->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clautoracervo->erro_campo!=""){
      echo "<script> document.form1.".$clautoracervo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clautoracervo->erro_campo.".focus();</script>";
    };
  }else{
    $clautoracervo->erro(true,true);
  };
};
if(isset($excluir)){
  if($clautoracervo->erro_status=="0"){
    $clautoracervo->erro(true,false);
  }else{
    $clautoracervo->erro(true,true);
  };
};
if(isset($cancelar)){
 $clautoracervo->pagina_retorno = "bib1_autoracervo001.php?bi21_acervo=$bi21_acervo&bi06_titulo=$bi06_titulo";
 echo "<script>location.href='".$clautoracervo->pagina_retorno."'</script>";
}
?>