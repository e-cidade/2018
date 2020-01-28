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
  include("classes/db_pcparam_classe.php");

  db_postmemory($HTTP_GET_VARS);

  $clpcparam  = new cl_pcparam;
  $clcriaabas = new cl_criaabas;

  $db_opcao   = 1;
  $erro       = false;

  $result_tipo = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"), "*"));
  if($clpcparam->numrows>0){
      db_fieldsmemory($result_tipo,0);
  } else {
      $erro = true;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
     <td>
     <?
        if (isset($param) && trim($param) != ""){
	     $parametro = "?param=".$param;
	} else {
	     $parametro = "";
	}

        if(isset($pc30_sugforn) && $pc30_sugforn=='t'){
            $clcriaabas->identifica = array("solicita"=>"Solicitação","solicitem"=>"Itens/Dotações","sugforn"=>"Fornecedores sugeridos");//nome do iframe e o label    
            $clcriaabas->src = array("solicita"=>"com1_solicita004.php".$parametro);    
            $clcriaabas->title      = array("solicita"=>"Solicitação de compras","solicitem"=>"Itens/Dotações","sugforn"=>"Fornecedores Sugeridos");//nome do iframe e o label    
            $clcriaabas->sizecampo  = array("solicita"=>"20","solicitem"=>"20","sugforn"=>"25");       
            $clcriaabas->disabled = array("solicitem"=>"true","sugforn"=>"true");
        } else {
            $clcriaabas->identifica = array("solicita"=>"Solicitação","solicitem"=>"Itens/Dotações");//nome do iframe e o label    
            $clcriaabas->src = array("solicita"=>"com1_solicita004.php".$parametro);    
            $clcriaabas->title      = array("solicita"=>"Solicitação de compras","solicitem"=>"Itens/Dotações");//nome do iframe e o label    
            $clcriaabas->sizecampo  = array("solicita"=>"20","solicitem"=>"20");
            $clcriaabas->disabled = array("solicitem"=>"true");
        }

        $clcriaabas->cria_abas();           
     ?> 
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($erro == true){
  db_msgbox("Parâmetros do compras não configurados");
}
?>