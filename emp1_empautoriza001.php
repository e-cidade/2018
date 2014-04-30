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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas     = new cl_criaabas;
$clrotulo = new rotulocampo;
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<table width="100%" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
     <?
     $query = '';
     if(isset($e54_resumo) && isset($e54_destin)){
       $query = "?e54_resumo=$e54_resumo&e54_destin=$e54_destin&e54_numcgm=$e54_numcgm&z01_nome=$z01_nome";
     }
     if (isset($pesq_ult)&&$pesq_ult==true){
       if ($query==""){
	 $query.="?pesq_ult=true";
       }else{
	 $query.="&pesq_ult=true";
       }
     }
       // $clcriaabas->identifica = array("empautoriza"=>"Autorização","empautitem"=>"Itens","empautidot"=>"Dotação","prazos"=>"Prazos","empautret"=>"Retenções","anulacao"=>"Anulação"); 
       $clcriaabas->identifica = array("empautoriza"=>"Autorização","empautitem"=>"Itens","empautidot"=>"Dotação","prazos"=>"Prazos","anulacao"=>"Anulação"); 

       $clcriaabas->sizecampo =  array("anulacao"=>"20"); 
       // $clcriaabas->title      =  array("empautoriza"=>"Autorização","empautitem"=>"Itens","empautidot"=>"Dotação","prazos"=>"Prazos","empautret"=>"Retenções","anulacao"=>"Anulação de autorização"); 
       $clcriaabas->title      =  array("empautoriza"=>"Autorização","empautitem"=>"Itens","empautidot"=>"Dotação","prazos"=>"Prazos","anulacao"=>"Anulação de autorização"); 

       $clcriaabas->src = array("empautoriza"=>"emp1_empautoriza004.php$query");
       // $clcriaabas->disabled   =  array("empautitem"=>true,"empautidot"=>"true","prazos"=>"true","empautret"=>"true","anulacao"=>"true"); 
       $clcriaabas->disabled   =  array("empautitem"=>true,"empautidot"=>"true","prazos"=>"true","anulacao"=>"true"); 

       $clcriaabas->cria_abas();    
     ?> 
     </td>
  </tr>
</table>
<form name="form1">
</form>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>