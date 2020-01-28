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
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemnota_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empelemento_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_empnota_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_empnotaele_classe.php");
include("classes/db_empparametro_classe.php");
//db_postmemory($HTTP_POST_VARS,2);
//db_postmemory($HTTP_SERVER_VARS,2);
db_postmemory($HTTP_POST_VARS);
$clpagordem      = new cl_pagordem;
$clpagordemconta = new cl_pagordemconta;
$clpagordemele   = new cl_pagordemele;
$clempempenho    = new cl_empempenho;
$clempelemento   = new cl_empelemento;
$clempnota       = new cl_empnota;
$clempnotaele    = new cl_empnotaele;
$clpagordemnota  = new cl_pagordemnota;
$clempparametro  = new cl_empparametro;

$db_opcao = 11;
$db_botao = false;
if(isset($incluir) || isset($incluirimp)){
  $sqlerro=false;
  db_inicio_transacao();

   $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
   $res = pg_query($sql);
   
   
   //variaveis
   //$e50_numemp = $e50_numemp;
   //$e50_obs    = $e50_obs;
   //$dados = $elemento-$valor#elemento-valor#elem..
   //$chaves, é setado quando tiver notas

   include("emp1_pagordemarq.php");
   if($sqlerro == false && isset($z01_numcgm2) && $z01_numcgm2 != ''){
     $clpagordemconta->e49_codord = $e50_codord;
     $clpagordemconta->e49_numcgm = $z01_numcgm2;
     $clpagordemconta->incluir($e50_codord);
     if($clpagordemconta->erro_status==0){
       $sqlerro=true;
       $erro_msg = $clpagordemconta->erro_msg;
     }  
   }
  db_fim_transacao($sqlerro);

  $db_opcao = 1;
  $db_botao = true;
}
if(isset($chavepesquisa) || isset($e50_numemp) ){
   $db_opcao = 1;
   $db_botao = true;
   if(isset($chavepesquisa)){
     $e50_numemp = $chavepesquisa;
   }  
   //rotina que tras os dados do emepenho
   $result = $clempempenho->sql_record($clempempenho->sql_query_file($e50_numemp)); 
   db_fieldsmemory($result,0);
}else{
  // esta variavel vem do arquivo de liquidacao, pois quando inclui uma liquidacao
  // o sistema pergunta se quer gerar ordem, entao envia esta variavel para este programa
  if(isset($emite_automatico) && $emite_automatico!="" ){
    $e50_numemp = $emite_automatico;
    $db_opcao = 1;
    $db_botao = true;
    if(isset($chavepesquisa)){
      $e50_numemp = $chavepesquisa;
    }  
    //rotina que tras os dados do emepenho
    $result = $clempempenho->sql_record($clempempenho->sql_query_file($e50_numemp)); 
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e50_obs.select();" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpagordem.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($incluirimp)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clpagordem->erro_campo!=""){
      echo "<script> document.form1.".$clpagordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpagordem->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($ok_msg);
    if(isset($incluirimp)){
      echo "
             <script> js_imprimir(); </script>
      ";
    }
    echo "
           <script>
	     location.href='emp1_pagordem005.php?operan=true&chavepesquisa=$e50_codord&retornaliq=true';
           </script>
    ";
  }
}
  if($db_opcao==11){
    echo "<script>js_pesquisa_emp();</script>\n";
  }
    echo "<script>document.form.e50_obs.select();</script>\n";
?>