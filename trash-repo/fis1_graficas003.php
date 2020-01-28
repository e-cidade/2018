<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_graficas_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clgraficas = new cl_graficas;
$db_botao   = false;
$db_opcao   = 33;

if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir" ) {

  $db_opcao = 3;

} elseif ( isset($chavepesquisa) ) {

  $db_opcao = 3;
  $result = $clgraficas->sql_record($clgraficas->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
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

  <?php
    require("forms/db_frmgraficas.php");
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>

</body>
</html>
<?php

if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Excluir" ) {

  db_inicio_transacao();

  try {

    /**
     * Procura aidof com grafica, se encontrar dispara excesao 
     */
    $oDaoAidof = db_utils::getDao('aidof');
    $sSqlAidof = $oDaoAidof->sql_query_file(null, 'y08_codigo', null, "y08_numcgm = {$y20_grafica}");
    $rsAidof   = db_query($sSqlAidof);

    /**
     * erro na query 
     */
    if ( !$rsAidof ) {
      throw new Exception('Erro ao validar exclusão de AIDOF:\\n' . pg_last_error());
    }

    /**
     * Grafica vinculada a um AIDOF 
     */
    if ( pg_num_rows($rsAidof) > 0 ) {

      $oAidof = db_utils::fieldsMemory($rsAidof, 0);
      throw new Exception('Não é possivel excluir gráfica.\\nGráfica vinculada ao AIDOF: ' . $oAidof->y08_codigo);
    }

    $clgraficas->excluir($y20_grafica);

    /**
     * erro ao excluir grafica
     */
    if ( $clgraficas->erro_status == "0" ) {
      throw new Exception($clgraficas->erro_msg);
    }

    /**
     * commita e exibe mensagem de exclusao e redireciona para mesmo fonte 
     */
    db_fim_transacao();

    $sMensagem  = "Usuário: \\n\\nExclusão efetuada com Sucesso\\n\\n";
    $sMensagem .= "Valores : " . $y20_grafica;

    db_msgbox($sMensagem);
    db_redireciona();

  } catch(Exception $oExeption) {

    db_msgbox($oExeption->getMessage());
    db_fim_transacao(true);
  }

}
?>