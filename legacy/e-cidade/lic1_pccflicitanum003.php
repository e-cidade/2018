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
include("classes/db_pccflicitanum_classe.php");
include("classes/db_liclicita_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
include("std/db_stdClass.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clpccflicitanum = new cl_pccflicitanum;
$clliclicita = new cl_liclicita;
$db_opcao = 22;
$db_botao = false;
$anousu=db_getsession("DB_anousu");
$instit=db_getsession("DB_instit");

if(isset($alterar)){
	
  $result_geral=$clliclicita->sql_record($clliclicita->sql_query_file(null,"max(l20_edital) as edital",null,"l20_instit=$instit and l20_instit = ".db_getsession('DB_instit')));
  if ($clliclicita->numrows>0){
    db_fieldsmemory($result_geral,0,1);
    //verifica se existe edital maior ou igual.
    if ($l24_numero>=$edital){
      $numero=$l24_numero+1;
      $numero_geral=$clliclicita->sql_record($clliclicita->sql_query_file(null,"l20_edital",null,"l20_edital=$numero and l20_instit = ".db_getsession('DB_instit')));
      if ($clliclicita->numrows>0){
        db_msgbox("Já existe licitação com o edital número $numero");
        $erro=true;
      }
    }

    //verifica se existe edital menor
    if ($l24_numero<$edital){
      $numero=$l24_numero+1;
      $numero_geral=$clliclicita->sql_record($clliclicita->sql_query_file(null,"l20_edital",null,"l20_edital=$numero and l20_instit = ".db_getsession('DB_instit')));
      if ($clliclicita->numrows==0){
        $l20_edital=$l24_numero;
      }else{
        db_msgbox("Já existe licitação com o edital número $numero");
        $erro=true;
      }
    }
  }      

  if (!isset($erro)){
    db_inicio_transacao();
    $result = $clpccflicitanum->sql_record($clpccflicitanum->sql_query(null, "pccflicitanum.*, nomeinst", "l24_anousu desc", "l24_anousu = {$anousu} and l24_instit = {$instit}"));
    if($result==false || $clpccflicitanum->numrows==0){
      $clpccflicitanum->incluir();
    }else{
    	$clpccflicitanum->alterar(null, "l24_anousu = {$anousu} and l24_instit = {$instit}");
    }
    db_fim_transacao();
  }
} else {

  $db_opcao = 2;
  $result = $clpccflicitanum->sql_record($clpccflicitanum->sql_query(null, "pccflicitanum.*, nomeinst", "l24_anousu desc", "l24_anousu = {$anousu} and l24_instit = {$instit}"));
  if($clpccflicitanum->numrows>0){
    db_fieldsmemory($result,0);
  }else{
    $l24_numero = 0;
    $oInstit = db_stdClass::getDadosInstit($instit);
    $l24_instit = $instit;
    $nomeinstit = $oInstit->nomeinst;
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
<table align="center">
  <tr> 
    <td> 
    <center>
			<?
			  include("forms/db_frmpccflicitanum.php");
 		  ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clpccflicitanum->erro_status=="0"){
    $clpccflicitanum->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clpccflicitanum->erro_campo!=""){
      echo "<script> document.form1.".$clpccflicitanum->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpccflicitanum->erro_campo.".focus();</script>";
    }
  }else{
    $clpccflicitanum->erro(true,true);
  }
}
?>