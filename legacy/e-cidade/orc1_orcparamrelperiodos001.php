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
include("classes/db_orcparamrelperiodos_classe.php");
include("classes/db_orcparamrel_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($_GET);
db_postmemory($_POST);

$clorcparamrelperiodos = new cl_orcparamrelperiodos;
$clorcparamrel = new cl_orcparamrel;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clorcparamrelperiodos->o113_sequencial = $o113_sequencial;
$clorcparamrelperiodos->o113_periodo = $o113_periodo;
$clorcparamrelperiodos->o113_orcparamrel = $o113_orcparamrel;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clorcparamrelperiodos->incluir($o113_sequencial);
    $erro_msg = $clorcparamrelperiodos->erro_msg;
    if($clorcparamrelperiodos->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clorcparamrelperiodos->alterar($o113_sequencial);
    $erro_msg = $clorcparamrelperiodos->erro_msg;
    if($clorcparamrelperiodos->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clorcparamrelperiodos->excluir($o113_sequencial);
    $erro_msg = $clorcparamrelperiodos->erro_msg;
    if($clorcparamrelperiodos->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clorcparamrelperiodos->sql_record($clorcparamrelperiodos->sql_query($o113_sequencial));
   if($result!=false && $clorcparamrelperiodos->numrows>0){
     db_fieldsmemory($result,0);
   }
}

if(isset($o113_orcparamrel)){
   $rsOrcparamRel = $clorcparamrel->sql_record($clorcparamrel->sql_query($o113_orcparamrel));
   if($rsOrcparamRel!=false && $clorcparamrel->numrows>0){
     db_fieldsmemory($rsOrcparamRel,0);
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<br /> <br />
<center>
	<?
	  include("forms/db_frmorcparamrelperiodos.php");
	?>
</center>

</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clorcparamrelperiodos->erro_campo!=""){
        echo "<script> document.form1.".$clorcparamrelperiodos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clorcparamrelperiodos->erro_campo.".focus();</script>";
    }
}
?>