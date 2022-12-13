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
  include("classes/db_aguahidromatric_classe.php");
  include("classes/db_aguahidromatricleitura_classe.php");
  include("classes/db_agualeitura_classe.php");
  include("dbforms/db_funcoes.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);

  $claguahidromatric = new cl_aguahidromatric;
  $claguahidromatricleitura = new cl_aguahidromatricleitura;
  $clagualeitura = new cl_agualeitura;
  
  $db_botao = false;
  $db_opcao = 33;

  $existe_leitura_posterior = 3;
  
  if (isset($excluir)) {
    db_inicio_transacao();
    $db_opcao = 3;
    $sql_erro = false;
 
	  /*
	  $clagualeitura->excluir(null, "x21_codhidrometro = $x04_codhidrometro");
	  if($clagualeitura->erro_status == "0"){
	    $erro_msg = $clagualeitura->erro_msg;
	    $sql_erro = true;
	  }
	  */

    if ($sql_erro == false) {
      
      $claguahidromatricleitura->excluir(null, "x05_codhidrometro = $x04_codhidrometro");
      
      if ($claguahidromatricleitura->erro_status == "0") {
        $erro_msg = $claguahidromatricleitura->erro_msg;
        $sql_erro = true;
      }
    }

    if ($sql_erro == false) {
      
      $claguahidromatric->excluir($x04_codhidrometro);
      $erro_msg = $claguahidromatric->erro_msg;
      
      if ($claguahidromatric->erro_status == "0") {
        $sql_erro = true;
      }
    }
    
    db_fim_transacao();
  
  } else if (isset($chavepesquisa)) {
    
    $db_opcao = 3;
    $result = $claguahidromatric->sql_record($claguahidromatric->sql_query($chavepesquisa)); 
    db_fieldsmemory($result,0);

    $db_botao = true;
    $sql = $claguahidromatricleitura->sql_query(null, "*", null, "x05_codhidrometro = $chavepesquisa");
    $result = $claguahidromatricleitura->sql_record($sql);
  
    if ($claguahidromatricleitura->numrows>0) {
      db_fieldsmemory($result,0);
    }

    $result = $clagualeitura->sql_record($clagualeitura->sql_query_file(null, "x21_codhidrometro", "", " x21_codhidrometro = ".$chavepesquisa));
    
    if ($clagualeitura->numrows > 0) {
      $existe_leitura_posterior = 33;
      $db_botao = false;
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
    <center>
      <table width="790" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td> 
            <fieldset style="margin-top: 50px;">
              <legend><b>Cadastro Hidrometros - Exclusão</b></legend>
              <center>
                <?
	            include("forms/db_frmaguahidromatric.php");
                ?>
              </center>
            </fieldset>
          </td>
        </tr>
      </table>
    </center>    
    <?
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit")
             );
    ?>
  </body>
</html>

<script>
  js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>

<?
  if (isset($excluir)) {
    
    db_msgbox($erro_msg);
    
    if ($sql_erro == false) {
      echo "<script>location.href = 'agu1_aguahidromatric003.php'</script>";
    }
  }
  
  if ($existe_leitura_posterior == 33) {
    db_msgbox("Hidrômetro não poderá ser excluído.\\n\\nLeitura já informada.");
    $db_opcao = 33;
  }
  
  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>