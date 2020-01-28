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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cflicita_classe.php");
require_once("classes/db_pccflicitapar_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_cflicitatemplate_classe.php");
require_once("classes/db_cflicitatemplateata_classe.php");
require_once("classes/db_pctipocompratribunal_classe.php");
require_once("classes/db_cflicitatemplateminuta_classe.php");

$clcflicita               = new cl_cflicita();
$clliclicita              = new cl_liclicita();
$clpccflicitapar          = new cl_pccflicitapar();
$clcflicitatemplate       = new cl_cflicitatemplate();
$clcflicitatemplateata    = new cl_cflicitatemplateata();
$clcflicitatemplateminuta = new cl_cflicitatemplateminuta();
$clpctipocompratribunal   = new cl_pctipocompratribunal();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;
$iInstit  = db_getsession('DB_instit');

if (isset($excluir)) {
	
  $sqlerro = false;
  
  db_inicio_transacao();

  $sSqlVerifica    = $clliclicita->sql_query_file(null,"*",null,"l20_codtipocom={$l03_codigo} and l20_instit ={$iInstit}");
  $result_verifica = $clliclicita->sql_record($sSqlVerifica);
  
  if ( $clliclicita->numrows > 0 ) {
     $erro_msg = "Existe licitação cadastrada com essa modalidade.Exclusão não efetuada.";
     $clliclicita->erro_status = 0;
     $sqlerro  = true;
  }

	if (!$sqlerro) {
		
		$clcflicitatemplate->excluir(null,"l35_cflicita = {$l03_codigo}");
    if( $clcflicitatemplate->erro_status == 0 ){
      $sqlerro = true;
    }
    
    $erro_msg = $clcflicitatemplate->erro_msg;		
    
    if ( !$sqlerro ) {
    	
	    $clcflicitatemplateata->excluir(null,"l37_cflicita = {$l03_codigo}");
	    if( $clcflicitatemplateata->erro_status == 0 ){
	      $sqlerro = true;
	    }          
	    $erro_msg = $clcflicitatemplateata->erro_msg;
    }
		
	  if ( !$sqlerro ) {
      $clcflicitatemplateminuta->excluir(null,"l41_cflicita = {$l03_codigo}");
      if( $clcflicitatemplateminuta->erro_status == 0 ){
        $sqlerro = true;
      }          
      $erro_msg = $clcflicitatemplateminuta->erro_msg;
    }
    
    if ( !$sqlerro ) {
		  $clpccflicitapar->excluir(null,"l25_codcflicita = {$l03_codigo}");
		  if($clpccflicitapar->erro_status==0){
		    $sqlerro = true;
		  } 
		  $erro_msg = $clpccflicitapar->erro_msg; 
    }
    
    if ( !$sqlerro ) {
		  $clcflicita->excluir($l03_codigo);
		  if($clcflicita->erro_status==0){
		    $sqlerro = true;
		  } 
		  $erro_msg = $clcflicita->erro_msg;
    }
	
	}  
  
  db_fim_transacao($sqlerro);
  
  $db_opcao = 3;
  $db_botao = true;
} else if(isset($chavepesquisa)) {
	
  $db_opcao = 3;
  $db_botao = true;
  
  $result   = $clcflicita->sql_record($clcflicita->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
        include("forms/db_frmcflicita.php");
      ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($excluir)) {
	
  if ($sqlerro == true) {
  	
    db_msgbox($erro_msg);
    if($clcflicita->erro_campo!=""){
      echo "<script> document.form1.".$clcflicita->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcflicita->erro_campo.".focus();</script>";
    };
  } else {
  	
   db_msgbox($erro_msg);
	 echo "
	  <script>
	    function js_db_tranca(){
	      parent.location.href='lic1_cflicita003.php';
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
         parent.document.formaba.pccflicitapar.disabled=false;
         parent.document.formaba.template.disabled=false;
         parent.document.formaba.templateata.disabled=false;    
         parent.document.formaba.faixavalores.disabled=false;  
         parent.document.formaba.templateminuta.disabled=false;
         top.corpo.iframe_pccflicitapar.location.href='lic1_pccflicitapar001.php?db_opcaoal=33&l25_codcflicita=".@$l03_codigo."';
         top.corpo.iframe_template.location.href='lic1_cflicitatemplate001.php?db_opcaoal=33&l35_cflicita=".@$l03_codigo."';
         top.corpo.iframe_templateata.location.href='lic1_cflicitatemplateata001.php?db_opcaoal=33&l37_cflicita=".@$l03_codigo."'; 
         top.corpo.iframe_templateminuta.location.href='lic1_cflicitatemplateminuta001.php?db_opcaoal=33&l41_cflicita=".@$l03_codigo."'; 
         top.corpo.iframe_faixavalores.location.href='lic1_cflicitafaixavalor001.php?l37_cflicita=".@$l03_codigo."';       
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('pccflicitapar');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}

if ($db_opcao==22||$db_opcao==33) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>