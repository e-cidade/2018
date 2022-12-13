<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  include("classes/db_aguarotarua_classe.php");
  include("classes/db_aguarota_classe.php");
  include("dbforms/db_funcoes.php");
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($_POST);

  $claguarotarua = new cl_aguarotarua;
  $claguarota = new cl_aguarota;
  $db_opcao = 22;
  $db_botao = false;
  $x07_ordem = 0;
  
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    $sqlerro = false;
    $claguarotarua->x07_codrotarua = $x07_codrotarua;
    $claguarotarua->x07_codrota = $x07_codrota;
    $claguarotarua->x07_codrua = $x07_codrua;
    $claguarotarua->x07_ordem = $x07_ordem;
    $claguarotarua->x07_nroini = $x07_nroini;
    $claguarotarua->x07_nrofim = $x07_nrofim;
  
    $x07_codrua = "";
    $j14_nome   = "";
    $x07_nroini = "";
    $x07_nrofim = "";
  }

  if (isset($incluir)) {
    if ($sqlerro == false) {
      db_inicio_transacao();
      $claguarotarua->incluir($x07_codrotarua);
      $erro_msg = $claguarotarua->erro_msg;
      
      if ($claguarotarua->erro_status == 0) {
        $sqlerro=true;
      }
      
      db_fim_transacao($sqlerro);
    }
    
  } else if(isset($alterar))  {
    
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      $claguarotarua->alterar($x07_codrotarua);
      $erro_msg = $claguarotarua->erro_msg;
      
      if ($claguarotarua->erro_status == 0) {
        $sqlerro = true;
      }
    
      db_fim_transacao($sqlerro);
    }

  } else if(isset($excluir)) {
  
    if ($sqlerro == false) {
      
      db_inicio_transacao();
      $claguarotarua->excluir($x07_codrotarua);
      $erro_msg = $claguarotarua->erro_msg;
    
      if ($claguarotarua->erro_status == 0) {
        $sqlerro=true;
      }
    
      db_fim_transacao($sqlerro);
    }

  } else if(isset($opcao)) {
   
     $result = $claguarotarua->sql_record($claguarotarua->sql_query($x07_codrotarua));
   
     if ($result != false && $claguarotarua->numrows > 0) {
       db_fieldsmemory($result,0);
     }
  }
?>

<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    
    
    <table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr> 
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
          <center>
            <?
              include("forms/db_frmaguarotarua.php");
	          ?>
          </center>
	      </td>
      </tr>
    </table>
    
    
  </body>
</html>

<?
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    db_msgbox($erro_msg);
    
    if ($claguarotarua->erro_campo != "") {
      echo "<script> document.form1.".$claguarotarua->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$claguarotarua->erro_campo.".focus();</script>";
    }
  }
?>