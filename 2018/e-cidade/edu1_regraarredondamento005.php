<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("dbforms/db_funcoes.php");

  db_app::import("educacao.*");

  $oDaoRegraArredondamento      = db_utils::getDao("regraarredondamento");
  $oDaoRegraArredondamentoFaixa = db_utils::getDao("regraarredondamentofaixa");

  db_postmemory($_POST);
  $db_opcao = 22;
  $db_botao = false;
  if (isset($alterar)) {

    $sqlerro=false;
    db_inicio_transacao();
    $oDaoRegraArredondamento->alterar($ed316_sequencial);
    if ($oDaoRegraArredondamento->erro_status==0) {
      $sqlerro=true;
    }
    $erro_msg = $oDaoRegraArredondamento->erro_msg;
    db_fim_transacao($sqlerro);
    $db_opcao = 2;
    $db_botao = true;
  }
  if (isset($chavepesquisa)) {

    $db_opcao = 2;
    $db_botao = true;
    $result = $oDaoRegraArredondamento->sql_record($oDaoRegraArredondamento->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
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
  if (isset($alterar)) {

    if ($sqlerro == true) {

      db_msgbox($erro_msg);
      if ($oDaoRegraArredondamento->erro_campo != "") {

        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".focus();</script>";
      }

    } else {

      db_msgbox($erro_msg);  
      $sUrl  = "edu1_regraarredondamentofaixa001.php?ed317_regraarredondamento={$ed316_sequencial}";
      $sUrl .= "&iCasasDecimais={$ed316_casasdecimaisarredondamento}";
      $sUrl .= "&sDisabled={$sDisabled}";
      echo "
           <script>
             function js_db_libera(){
               parent.document.formaba.regraarredondamentofaixa.disabled=false;
               top.corpo.iframe_regraarredondamentofaixa.location.href='{$sUrl}';";
           if(isset($liberaaba)){
             echo "  parent.mo_camada('regraarredondamentofaixa');";
           }
      echo "}\n
         js_db_libera();
       </script>\n
      ";
    }

  }
  if (isset($chavepesquisa)) {

    $sUrl  = "edu1_regraarredondamentofaixa001.php?ed317_regraarredondamento={$ed316_sequencial}";
    $sUrl .= "&iCasasDecimais={$ed316_casasdecimaisarredondamento}";
    $sUrl .= "&sDisabled={$sDisabled}";
    echo "
         <script>
           function js_db_libera(){
             parent.document.formaba.regraarredondamentofaixa.disabled=false;
             top.corpo.iframe_regraarredondamentofaixa.location.href='{$sUrl}';";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('regraarredondamentofaixa');";
         }
   echo "}\n
      js_db_libera();
    </script>\n
   ";
  }
  if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>