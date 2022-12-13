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
  
  $db_opcao = 22;
  $db_botao = false;
  $erro_msg = '';

  // Se não existe leitura posterior à leitura automática, será possível alterar a Leitura Inicial, Ano, Mês e Situação
  $existe_leitura_posterior = 1;
  
  if (isset($alterar)) {
    
    db_inicio_transacao();
    
    $db_opcao = 2;
    $sql_erro = false;

    $sql = $claguahidromatricleitura->sql_query(null, "*", null, "x05_codhidrometro = $x04_codhidrometro");
    $result = $claguahidromatricleitura->sql_record($sql);
    
    if ($claguahidromatricleitura->numrows>0) {
      
      db_fieldsmemory($result,0);
    
    } else {

      // Desabilita triggers de calculo de Consumo e Excesso
      /*db_query("update pg_class set reltriggers = 0 where relname = 'agualeitura'");
      
      $clagualeitura->x21_codhidrometro = $x04_codhidrometro;
      $clagualeitura->x21_numcgm        = "0"; // Leiturista ADM do Sistema

      $clagualeitura->x21_dtleitura_dia = $x04_dtinst_dia;
      $clagualeitura->x21_dtleitura_mes = $x04_dtinst_mes;
      $clagualeitura->x21_dtleitura_ano = $x04_dtinst_ano;

      $clagualeitura->x21_usuario       = db_getsession("DB_id_usuario");

      $clagualeitura->x21_dtinc_dia     = date("d", db_getsession("DB_datausu"));
      $clagualeitura->x21_dtinc_mes     = date("m", db_getsession("DB_datausu"));
      $clagualeitura->x21_dtinc_ano     = date("Y", db_getsession("DB_datausu"));

      $clagualeitura->x21_leitura       = $x04_leitinicial;
      $clagualeitura->x21_consumo       = "0";
      $clagualeitura->x21_excesso       = "0";
      $clagualeitura->x21_virou         = "false";
      $clagualeitura->x21_tipo          = "1";
      $clagualeitura->x21_status        = "1";
      $clagualeitura->x21_situacao      = $x21_situacao;

      $clagualeitura->incluir(null);
    
      if ($clagualeitura->erro_status == "0") {
        $sql_erro = true;
        $erro_msg .= '\nERRO: Arquivo agualeitura - ' . $clagualeitura->erro_msg;
      }

      $claguahidromatricleitura->x05_codhidrometro = $x04_codhidrometro;
      $claguahidromatricleitura->x05_codleitura    = $clagualeitura->x21_codleitura;

      $claguahidromatricleitura->incluir(null);

      if ($claguahidromatricleitura->erro_status == "0") {
        
        $sql_erro = true;
        $erro_msg.= "ERRO: Arquivo aguahidromatricleitura - " . $claguahidromatricleitura->erro_msg;
      
      }
      
      // Re-habilita triggers
      db_query("UPDATE pg_class 
                  SET reltriggers = (SELECT count(*) FROM pg_trigger WHERE pg_class.oid = tgrelid) 
                WHERE relname = 'agualeitura' ");
      */
    }

    if ($sql_erro == false) {

      $claguahidromatric->alterar($x04_codhidrometro);
    
    }else{
      
      echo "<script>alert(\"Ocorreu algum erro durante o processamento! $erro_msg \");</script>";
    
    }

    db_fim_transacao($sql_erro);
  
    $db_botao = true;
  
  } else if (isset($chavepesquisa)) {
  
    $db_opcao = 2;
    $result = $claguahidromatric->sql_record($claguahidromatric->sql_query($chavepesquisa)); 
    db_fieldsmemory($result,0);

    $sql = $claguahidromatricleitura->sql_query(null, "*", null, "x05_codhidrometro = $chavepesquisa");
    $result = $claguahidromatricleitura->sql_record($sql);
    
    if ($claguahidromatricleitura->numrows>0) {
      
      db_fieldsmemory($result, 0);
    
      $result = $clagualeitura->sql_record($clagualeitura->sql_query_file(null,
                                                                          "x21_codhidrometro",
                                                                          "",
                                                                          " x21_codhidrometro = " . $x05_codhidrometro . 
                                                                          " and x21_codleitura > ".$x05_codleitura));
      if ($clagualeitura->numrows > 0) {
        // Se existe leitura posterior à leitura automática,
        // não será possível alterar a Leitura Inicial, Ano, Mês e Situação
        $existe_leitura_posterior = 3;
      
      }
    }
 
    $db_botao = true;
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
              <legend><b>Cadastro Hidrometros - Alteração</b></legend>
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
  js_tabulacaoforms("form1", "x04_matric", true, 1, "x04_matric", true);
</script>

<?
  if (isset($alterar)) {
    
    if ($claguahidromatric->erro_status == "0") {
    
      $claguahidromatric->erro(true, false);
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
      if ($claguahidromatric->erro_campo != "") {
        echo "<script> document.form1." . $claguahidromatric->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $claguahidromatric->erro_campo . ".focus();</script>";
      }
  
    } else {
      $claguahidromatric->erro(true, true);
    }
  }

  if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>