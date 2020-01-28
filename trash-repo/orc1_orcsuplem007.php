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
include("classes/db_orcsuplem_classe.php");
include("dbforms/db_funcoes.php");
require("classes/db_conhistdoc_classe.php");
include("classes/db_orcsuplemtipo_classe.php");
include("classes/db_orcsuplemval_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clorcsuplemtipo = new cl_orcsuplemtipo;
$clorcsuplem = new cl_orcsuplem;
$clorcsuplemval = new cl_orcsuplemval;
$clconhistdoc = new cl_conhistdoc;
$anousu= db_getsession("DB_anousu");

$db_opcao = 1;
$db_botao = true;

if (isset($alterar)){
       db_inicio_transacao();
       $db_opcao = 2;
       $clorcsuplem->alterar($o46_codsup);
       db_fim_transacao();
       $db_botao = true;
} else if (isset($excluir)){
       $db_opcao = 3;
       // exclui registros filhos 
       $codsup = $o46_codsup;
       $clorcsuplemval->sql_record($clorcsuplemval->sql_query_file($codsup));
       if($clorcsuplemval->numrows > 0 ){
	  $clorcsuplemval->o47_codsup = $codsup;
          $clorcsuplemval->excluir($codsup); 
       }
       // exclui o pai
       $clorcsuplem->excluir($codsup);  
       $projeto = db_getsession("codproj");
       echo "<script> parent.location='orc1_orcsuplem001.php?chave=$projeto'; </script>";
       //-------------------
} else if (isset($incluir)){
     if ($o46_data_ano == $anousu) {   
        db_inicio_transacao();
        $clorcsuplem->o46_instit=$GLOBALS["DB_instit"];
	// $clorcsuplem->o46_codsup=null;
        $clorcsuplem->incluir(null);
        $o46_codsup= $clorcsuplem->o46_codsup;
        db_fim_transacao();
    }else {
        echo "<script>\n 
               alert('Data não confere com o Ano do  Exercício Atual ! ');\n
    	      </script>\n
	      ";
        $clorcsuplem->erro_status=0;	   
    }    
}
 //
 if (isset($o46_tiposup)){
         $result=$clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file($o46_tiposup));  
         if ($clorcsuplemtipo->numrows > 0) {
            db_fieldsmemory($result,0);
        }  
  }  
  //--------
 if (isset($o46_codsup) && ($o46_codsup!="")){
        $db_opcao=2;
        $result=$clorcsuplem->sql_record($clorcsuplem->sql_query_file($o46_codsup));  
        if ($clorcsuplem->numrows > 0) {
            db_fieldsmemory($result,0);
        }  
  }  
 //-----
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
<br><br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
            include("forms/db_frmorcsuplem.php");
        ?>

    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
//--- libera abas
if (isset($o46_codsup) && ($o46_codsup!="")){
   if ($qt_abas=="2"){
         if($o48_arrecadmaior !=0){
	    echo "<script>
      	          // libera suplementação
	          parent.document.formaba.orcsuplemrec.disabled=false;\n
	          top.corpo.iframe_orcsuplemrec.location.href='orc1_orcsuplemrec007.php?o85_codsup=$o46_codsup';\n
		  </script>
                  ";
	 }   
         echo "<script>
      	       // libera suplementação
	       parent.document.formaba.orcsuplemval.disabled=false;\n
	       top.corpo.iframe_orcsuplemval.location.href='orc1_orcsuplemval007.php?o47_codsup=$o46_codsup';\n
	       // envia para reducao
               parent.mo_camada('orcsuplemval');    //envia direto para outra aba     
               </script>";    

   } else {
         if($o48_arrecadmaior!=0){
	    echo "<script>
      	          // libera suplementação
	          parent.document.formaba.orcsuplemrec.disabled=false;\n
	          top.corpo.iframe_orcsuplemrec.location.href='orc1_orcsuplemrec007.php?o85_codsup=$o46_codsup';\n
		  </script>
                  ";
	 }    
         echo "<script>
  	       // libera reducao	         
               parent.document.formaba.reducao.disabled=false;\n
               top.corpo.iframe_reducao.location.href='orc1_orcsuplemval001.php?o47_codsup=$o46_codsup';\n
               // libera suplementação
	       parent.document.formaba.orcsuplemval.disabled=false;\n
	       top.corpo.iframe_orcsuplemval.location.href='orc1_orcsuplemval007.php?o47_codsup=$o46_codsup';\n
	       // envia para reducao
               parent.mo_camada('reducao');    //envia direto para outra aba     
          </script>";

   }  
 
}
//------   

if (isset($incluir)){
  if($clorcsuplem->erro_status=="0"){
       $clorcsuplem->erro(true,false);
       if($clorcsuplem->erro_campo!=""){
             echo "<script> document.form1.".$clorcsuplem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
             echo "<script> document.form1.".$clorcsuplem->erro_campo.".focus();</script>";
        };
  }else{
     $clorcsuplem->erro(true,false);
     db_redireciona("orc1_orcsuplem007.php?o46_codsup=$o46_codsup&o46_tiposup=$o46_tiposup&qt_abas=$qt_abas");
     // Redireciona para tela de alteração e libera abas
  };
};

?>