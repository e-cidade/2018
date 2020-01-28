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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  
  $oDaoRegraArredondamento      = db_utils::getDao("regraarredondamento");
  $oDaoRegraArredondamentoFaixa = db_utils::getDao("regraarredondamentofaixa");
  
  db_postmemory($_POST);
  $db_opcao = 1;
  $db_botao = true;
  if (isset($incluir)) {

    $sqlerro = false;
    db_inicio_transacao();
    $oDaoRegraArredondamento->incluir($ed316_sequencial);
    $ed316_sequencial = $oDaoRegraArredondamento->ed316_sequencial;
    if ($oDaoRegraArredondamento->erro_status == 0) {
      $sqlerro=true;
    } 
    $erro_msg = $oDaoRegraArredondamento->erro_msg; 
    db_fim_transacao($sqlerro);
    $db_opcao         = 1;
    $db_botao         = true;
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
    	<?
    	  require_once("forms/db_frmregraarredondamento.php");
    	?>
    </center>
  </body>
</html>
<?
  if (isset($incluir)) {
    
    if ($sqlerro==true) {
      db_msgbox($erro_msg);
      if ($oDaoRegraArredondamento->erro_campo!="") {
        
        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".focus();</script>";
      }
    } else {
      
     db_msgbox($erro_msg);
     db_redireciona("edu1_regraarredondamento005.php?liberaaba=true&chavepesquisa=$ed316_sequencial");
    }
  }
?>