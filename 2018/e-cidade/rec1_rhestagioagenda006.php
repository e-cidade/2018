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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhestagioagenda_classe.php");
include("classes/db_rhestagioresultado_classe.php");
include("classes/db_rhestagioagendadata_classe.php");
include("classes/estagioAvaliacoes.classe.php");
$clrhestagioagenda     = new cl_rhestagioagenda;
$clrhestagioagendadata = new cl_rhestagioagendadata;
$clrhestagioresultado  = new cl_rhestagioresultado;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $rsResultado = $clrhestagioresultado->sql_record($clrhestagioresultado->sql_query(null,"*",null,"h65_rhestagioagenda={$h57_sequencial}"));
          echo pg_last_error();
  if ($clrhestagioresultado->numrows > 0){
    
      $sqlerro = true;
      $erro_msg = "Esse est�gio j� possui um resultado lan�ado.\\n N�o poder� ser exclu�do;";
  }
  if (!$sqlerro){
    
     $rsDatas = $clrhestagioagendadata->sql_record($clrhestagioagendadata->sql_query(null,"*",null,"h64_estagioagenda={$h57_sequencial}"));
     if ($clrhestagioagendadata->numrows > 0){

       $iNumRowsDatas = $clrhestagioagendadata->numrows;
       for ($i = 0;$i < $iNumRowsDatas; $i++){ 
        
          $oDatas                                = db_utils::fieldsMemory($rsDatas,$i);  
          $estagioAvaliacoes                     = new estagioAvaliacao($oDatas->h64_sequencial);
          $estagioAvaliacoes->excluirAvaliacao();
          echo pg_last_error();
          if ($estagioAvaliacoes->lSqlErro){

             $sqlerro  = true;
             $erro_msg = $estagioAvaliacoes->sErroMsg;
             break;
          }
          if (!$sqlerro){

            $clrhestagioagendadata->h64_sequencial = $oDatas->h64_sequencial;
            $clrhestagioagendadata->excluir($oDatas->h64_sequencial);
            echo pg_last_error();
            if ($clrhestagioagendadata->erro_status == 0){
                 
               $sqlerro  = true;
               $erro_msg = $clrhestagioagendadata->erro_msg;
               break;
            }
         }
       }
     }
  } 
  if (!$sqlerro){
   
     $clrhestagioagenda->excluir($h57_sequencial);
     if($clrhestagioagenda->erro_status==0){
       $sqlerro=true;
     } 
     $erro_msg = $clrhestagioagenda->erro_msg; 
  }
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $db_botao = true;
   $result = $clrhestagioagenda->sql_record($clrhestagioagenda->sql_query($chavepesquisa)); 
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhestagioagenda.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhestagioagenda->erro_campo!=""){
      echo "<script> document.form1.".$clrhestagioagenda->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhestagioagenda->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
 echo "
  <script>
    function js_db_tranca(){
      parent.location.href='rec1_rhestagioagenda003.php';
    }\n
    js_db_tranca();
  </script>\n
 ";
  }
}
if(isset($chavepesquisa)){
 echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.rhestagioagendadata.disabled=false;
         top.corpo.iframe_rhestagioagendadata.location.href='rec1_rhestagioagendadata001.php?db_opcaoal=33&h64_estagioagenda=".@$h57_sequencial."';
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('rhestagioagendadata');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>