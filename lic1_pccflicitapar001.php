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
include("classes/db_pccflicitapar_classe.php");
include("classes/db_cflicita_classe.php");
include("classes/db_liclicita_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpccflicitapar = new cl_pccflicitapar;
$clcflicita = new cl_cflicita;
$clliclicita = new cl_liclicita;
$db_opcao = 22;
$db_botao = false;
$anousu=db_getsession("DB_anousu");
$instit=db_getsession("DB_instit");
if (isset($alterar) || isset($excluir) || isset($incluir)) {
$sqlerro = false;
  /*
$clpccflicitapar->l25_codigo = $l25_codigo;
$clpccflicitapar->l25_codcflicita = $l25_codcflicita;
$clpccflicitapar->l25_anousu = $l25_anousu;
$clpccflicitapar->l25_numero = $l25_numero;
*/
}

if (isset($incluir)) {
  if ($sqlerro==false) {
       $result_verifica = $clliclicita->sql_record($clliclicita->sql_query_file(null,"*",null,"l20_codtipocom=$l25_codcflicita and l20_instit =$instit and l20_anousu=$l25_anousu" ));

      if ($clliclicita->numrows>0) {
        $erro_msg    = "Já existe licitação cadastrada com essa modalidade.Não foi possível incluir.";
        $clliclicita->erro_status = 0;
        $sqlerro  = true;
      }
      
   
    if (!$sqlerro) {
      db_inicio_transacao();

      $clpccflicitapar->l25_codcflicita=$l25_codcflicita;
      $clpccflicitapar->l25_anousu=$l25_anousu;
      $clpccflicitapar->incluir(null);
      $erro_msg = $clpccflicitapar->erro_msg;
      if ($clpccflicitapar->erro_status==0) {
        $sqlerro=true;
      }
      db_fim_transacao($sqlerro);
    }
  }
} else if (isset($alterar)) {
  if ($sqlerro==false) {
    db_inicio_transacao();
    $numero=$l25_numero+1;
     $result_verifica = $clliclicita->sql_record($clliclicita->sql_query_file(null,"*",null,"l20_codtipocom=$l25_codcflicita and l20_instit =$instit and l20_anousu=$l25_anousu and l20_numero=$numero"));  

     if ($clliclicita->numrows >0) {
         $erro_msg    ="Já existe licitação cadastrada com essa modalidade com numeração $numero.Não foi possível alterar.";
         $clliclicita->erro_status = 0;
         $sqlerro  = true;
      }
    if (!$sqlerro){
       $clpccflicitapar->alterar($l25_codigo);
       $erro_msg = $clpccflicitapar->erro_msg;
       if ($clpccflicitapar->erro_status==0) {
          $sqlerro=true;
       }
    }

    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {
  if ($sqlerro==false) {
    
      $result_verifica = $clliclicita->sql_record($clliclicita->sql_query_file(null,"*",null,"l20_codtipocom=$l25_codcflicita and l20_instit =$instit and l20_anousu=$l25_anousu" ));

   if ($clliclicita->numrows >0) {
      
        $erro_msg    ="Já existe licitação cadastrada com essa modalidade.Não foi possível excluir";
        $clliclicita->erro_status = 0;
        $sqlerro  = true;
      
    }
    
    if (!$sqlerro) {
      db_inicio_transacao();
      $clpccflicitapar->excluir($l25_codigo);
      $erro_msg = $clpccflicitapar->erro_msg;
      if ($clpccflicitapar->erro_status==0) {
        $sqlerro=true;
      }
      db_fim_transacao($sqlerro);
    }
    
    
  }
} else if (isset($opcao)) {
  
  $result = $clpccflicitapar->sql_record($clpccflicitapar->sql_query($l25_codigo));
  if ($result!=false && $clpccflicitapar->numrows>0) {
    db_fieldsmemory($result,0);
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
<table border="0" align="center" cellspacing="0" cellpadding="0" >
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
    <center>
      <?
        include("forms/db_frmpccflicitapar.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clpccflicitapar->erro_campo!=""){
        echo "<script> document.form1.".$clpccflicitapar->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clpccflicitapar->erro_campo.".focus();</script>";
    }
}
?>