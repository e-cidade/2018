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
include("classes/db_db_syscadproceditem_classe.php");
include("classes/db_db_syscadproced_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_syscadproceditem = new cl_db_syscadproceditem;
$cldb_syscadproceditempesquisa = new cl_db_syscadproceditem;
$cldb_syscadproced = new cl_db_syscadproced;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$cldb_syscadproceditem->seqproitem = $seqproitem;
$cldb_syscadproceditem->codproced = $codproced;
$cldb_syscadproceditem->id_item = $id_item;
  */
}
if(isset($incluir) || isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $itens = split("-",$itens);
		if( count($itens) > 0 ){
		  for($i=0;$i<count($itens);$i++){
			  if($itens[$i]==null)
					continue;
        $cldb_syscadproceditem->codproced = $codproced;
        $cldb_syscadproceditem->id_item   = $itens[$i];
				
        $result = $cldb_syscadproceditempesquisa->sql_record($cldb_syscadproceditempesquisa->sql_query_file(null,"*",null," codproced = $codproced and id_item = ".$itens[$i]));
				if( $cldb_syscadproceditempesquisa->numrows==0){
          $cldb_syscadproceditem->incluir(0);
          $erro_msg = $cldb_syscadproceditem->erro_msg;
          if($cldb_syscadproceditem->erro_status==0){
            $sqlerro=true;
          }
				}
		  }
    }
		db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_syscadproceditem->excluir($seqproitem);
    $erro_msg = $cldb_syscadproceditem->erro_msg;
    if($cldb_syscadproceditem->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $cldb_syscadproceditem->sql_record($cldb_syscadproceditem->sql_query($seqproitem));
   if($result!=false && $cldb_syscadproceditem->numrows>0){
     db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_syscadproceditem.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
}
?>