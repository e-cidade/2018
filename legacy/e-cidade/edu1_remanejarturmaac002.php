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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turmaac_classe.php");
include("classes/db_turmaacmatricula_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clturmaac          = new cl_turmaac;
$clturmaacmatricula = new cl_turmaacmatricula;
$clescola           = new cl_escola;
$clescolaestrutura  = new cl_escolaestrutura;
$db_opcao           = 22;
$db_opcao1          = 3;
$db_botao           = false;
$db_botao2          = true;
$codigoescola       = db_getsession("DB_coddepto");
$result_estr        = $clescolaestrutura->sql_record($clescolaestrutura->sql_query("",
                                                                                   "ed255_i_ativcomplementar,ed255_i_aee",
                                                                                   "",
                                                                                   " ed255_i_escola = $codigoescola" 
                                                                                  )
                                                    );
if ($clescolaestrutura->numrows > 0) {
	
  db_fieldsmemory($result_estr,0);
  if ($ed255_i_ativcomplementar == 0) {
  	
    if ($ed255_i_aee == 2) {
    	
      $sString = "<font color='red'><b>* Escola oferede EXCLUSIVAMENTE Atendimento Educacional Especial -" ;
      $sString .= "AEE (Cadastros -> Dados da Escola -> Aba Infraestrutura)</b></font>";
      echo $sString;
       
    } else {
    	
      $sFrase  = "<font color='red'><b>* Escola NÃO OFERECE Atividade Complementar (Cadastros -> Dados da Escola ->";
      $sFrase .= "Aba Infraestrutura)</b></font>";
      echo  $sFrase;
      
    }
  }
  
  if ($ed255_i_aee == 0) {
  	
    if ($ed255_i_ativcomplementar == 2) {
    	
      $sString  = "<br><font color='red'><b>* Escola oferece EXCLUSIVAMENTE Atividade Complementar (Cadastros -> ";
      $sString .= "Dados da Escola -> Aba Infra Estrutura)</b></font>";
      echo $sString;
      
    } else {
    	
      $sFrase  = "<br><font color='red'><b>* Escola NÃO OFERECE Atendimento Educacional Especial - AEE (Cadastros ->";
      $sFrase .= " Dados da Escola -> Aba Infra Estrutura)</b></font>";
      echo  $sFrase;
      
    }
  }
  if ($ed255_i_aee == 0 && $ed255_i_ativcomplementar == 0) {
    $db_botao  = false;
    $db_botao2 = false;
  }
}

function PegaValores($array,$tamanho) {
	
  $retorno = "";
  for ($x = 1; $x <= $tamanho; $x++) {
  	
    $tem = false;
    for ($y = 0; $y < count($array); $y++) {
    	
      if ($array[$y] == $x) {
      	
        $retorno .= "1";
        $tem      = true;
        break;
        
      }
      
    }
    if ($tem == false) {
      $retorno .= "0";
    }
  }
  return $retorno;
}

if (isset($alterar)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;
  db_inicio_transacao();
  
  if ($ed268_i_tipoatend == 5) {
    $ed268_c_aee = PegaValores($ed268_c_aee,11);
  } else {
    $ed268_c_aee = "";
  }
  
  $clturmaac->ed268_c_aee   = $ed268_c_aee;
  $clturmaac->ed268_c_descr = trim($ed268_c_descr);
  $clturmaac->alterar($ed268_i_codigo);
  db_fim_transacao();
  $db_botao = true;
  
} else if(isset($chavepesquisa)) {
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $result    = $clturmaac->sql_record($clturmaac->sql_query($chavepesquisa));
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Turma com Atividade Complementar / AEE</b></legend>
    <?include("forms/db_frmturmaac.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed268_c_descr",true,1,"ed268_c_descr",true);
</script>
<?
if (isset($alterar)) {
  if ($clturmaac->erro_status == "0") {
    $clturmaac->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clturmaac->erro_campo != "") {
      echo "<script> document.form1.".$clturmaac->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturmaac->erro_campo.".focus();</script>";
    };
  } else {
    $clturmaac->erro(true,false);
?>
   <script>parent.document.form2.teste.click();</script>
  <?} 
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>