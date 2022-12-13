<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

  require(modification("libs/db_stdlib.php"));
  require(modification("libs/db_conecta.php"));
  include(modification("libs/db_sessoes.php"));
  include(modification("libs/db_usuariosonline.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("classes/db_aguabase_classe.php"));
  include(modification("classes/db_aguabaseresp_classe.php"));
  include(modification("classes/db_aguabasecorresp_classe.php"));
  include(modification("classes/db_aguabasecar_classe.php"));
  include(modification("classes/db_aguaconstr_classe.php"));
  include(modification("classes/db_aguabasevenc_classe.php"));

  $claguabase = new cl_aguabase;
  $claguabasecar = new cl_aguabasecar;
  
  /*
    $claguabaseresp = new cl_aguabaseresp;
    $claguabasecorresp = new cl_aguabasecorresp;
    $claguaconstr = new cl_aguaconstr;
    $claguabasevenc = new cl_aguabasevenc;
  */

  db_postmemory($HTTP_POST_VARS);
  
  $db_opcao = 1;
  $db_botao = true;

  $x01_dtcadastro_dia = date("d");
  $x01_dtcadastro_mes = date("m");
  $x01_dtcadastro_ano = date("Y");

  if (isset($incluir)) {
    
    $sqlerro = false;
    
    db_inicio_transacao();
    
    $claguabase->incluir($x01_matric);
  
    if ($claguabase->erro_status == 0) {
      $sqlerro=true;
    } 
  
    $erro_msg = $claguabase->erro_msg; 

    $matriz = explode("X", $caracteristica);
  
    for ($i = 0; $i < sizeof($matriz); $i++) {
      $x30_codigo = $matriz[$i];
      
      if ($x30_codigo != "") {
        $claguabasecar->incluir($x01_matric, $x30_codigo);
      
        if ($claguabasecar->erro_status == 0) {
          $sqlerro = true;
        }
      }
    }
 
    db_fim_transacao($sqlerro);
   
    $x01_matric= $claguabase->x01_matric;
      
    $db_opcao = 1;
    $db_botao = true;
  }
?>

<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>    
	  <script type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
	            <?
	              include(modification("forms/db_frmaguabase.php"));
	            ?>
            </center>
	        </td>
        </tr>
      </table>
    </center>
  </body>
</html>

<?

  if (isset($incluir)) {
    
    if ($sqlerro == true) {
      db_msgbox($erro_msg);
    
      if ($claguabase->erro_campo != "") {
        echo "<script> document.form1." . $claguabase->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $claguabase->erro_campo . ".focus();</script>";
      }
      
    } else {
      db_msgbox($erro_msg);
      db_redireciona("agu1_aguabase005.php?liberaaba=true&chavepesquisa=$x01_matric");
    }
  }
  
?>