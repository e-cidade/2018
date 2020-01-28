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
  include("classes/db_aguabasecorresp_classe.php");
  include("classes/db_aguabase_classe.php");
  include("dbforms/db_funcoes.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);

  $claguabasecorresp = new cl_aguabasecorresp;
  $claguabase = new cl_aguabase;
  
  $db_opcao = 22;
  $db_botao = false;
  
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    $sqlerro = false;
    /*
     $claguabasecorresp->x32_codcorresp = $x32_codcorresp;
     $claguabasecorresp->x32_matric = $x32_matric;
    */
  }
  
  if (isset($incluir)) {
  
    if ($sqlerro == false) {
      
      db_inicio_transacao();
    
      $claguabasecorresp->incluir($x32_matric);
      
      $erro_msg = $claguabasecorresp->erro_msg;
    
      if ($claguabasecorresp->erro_status == 0) {
        $sqlerro = true;
      }
    
      db_fim_transacao($sqlerro);
    }

  } else if(isset($alterar)) {
    
    if ($sqlerro == false) {
    
      db_inicio_transacao();
    
      $claguabasecorresp->alterar($x32_matric);
      $erro_msg = $claguabasecorresp->erro_msg;
    
      if ($claguabasecorresp->erro_status == 0) {
        $sqlerro=true;
      }
      
      db_fim_transacao($sqlerro);
    }
  } else if(isset($excluir)) {
  
    if ($sqlerro == false) {
      
      db_inicio_transacao();
    
      $claguabasecorresp->excluir($x32_matric);
      $erro_msg = $claguabasecorresp->erro_msg;
    
      if ($claguabasecorresp->erro_status == 0) {
        $sqlerro = true;
      }
    
      db_fim_transacao($sqlerro);
    }
    
  } else if(isset($opcao)) {
    
    $result = $claguabasecorresp->sql_record($claguabasecorresp->sql_query($x32_matric));
    
    if ($result != false && $claguabasecorresp->numrows > 0) {
      db_fieldsmemory($result, 0);
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
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
	            <?
	              include("forms/db_frmaguabasecorresp.php");
	            ?>
            </center>
	        </td>
        </tr>
      </table>
    </center>
  </body>
</html>

<?
  if (isset($alterar) || isset($excluir) || isset($incluir)) {
    
    db_msgbox($erro_msg);
    
    if ($clpagordemrec->erro_campo != "") {
      echo "<script> document.form1." . $claguabasecorresp->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $claguabasecorresp->erro_campo . ".focus();</script>";
    }
  }
?>