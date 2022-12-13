<?
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_turmaac_classe.php");
require_once ("classes/db_turmaacmatricula_classe.php");
require_once ("classes/db_escola_classe.php");
require_once ("classes/db_escolaestrutura_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_jsplibwebseller.php");
require_once ("model/educacao/Escola.model.php");

db_postmemory($HTTP_POST_VARS);
$clturmaac          = new cl_turmaac;
$clturmaacmatricula = new cl_turmaacmatricula;
$clescola           = new cl_escola;
$clescolaestrutura  = new cl_escolaestrutura;
$db_opcao           = 1;
$db_opcao1          = 1;
$db_botao           = true;
$db_botao2          = true;
$codigoescola       = db_getsession("DB_coddepto");
$oEscola            = new Escola($codigoescola);

$ed255_i_ativcomplementar = $oEscola->ofereceAtividadeComplementar();
$ed255_i_aee              = $oEscola->ofereceEducacaoEspecializada();

/**
 * Verificamos a mensagem a ser apresentada de acordo com a opcao de atividade complementar configurada para a escola
 * 1 - Oferece EXCLUSIVAMENTE
 * 2 - Oferece, mas NÃO EXCLUSIVAMENTE
 * 3 - Não oferece
 */
$sMensagemAtividadeComplementar = '';
switch($ed255_i_ativcomplementar) {
  
  case 1:
    
    $sMensagemAtividadeComplementar  = "<b>* Escola oferece EXCLUSIVAMENTE Atividade Complementar (Cadastros -> ";
    $sMensagemAtividadeComplementar .= "Dados da Escola -> Aba Infra Estrutura)</b>";
    break;
    
  case 2:
    
    $sMensagemAtividadeComplementar  = "<b>* Escola oferece Atividade Complementar (Cadastros -> ";
    $sMensagemAtividadeComplementar .= "Dados da Escola -> Aba Infra Estrutura)</b>";
    break;
    
  case 3:
  
    $sMensagemAtividadeComplementar  = "<b>* Escola NÃO oferece Atividade Complementar (Cadastros -> ";
    $sMensagemAtividadeComplementar .= "Dados da Escola -> Aba Infra Estrutura)</b>";
    break;
}

/**
 * Verificamos a mensagem a ser apresentada de acordo com a opcao de educacao especializada configurada para a escola
 * 1 - Oferece EXCLUSIVAMENTE
 * 2 - Oferece, mas NÃO EXCLUSIVAMENTE
 * 3 - Não oferece
 */
$sMensagemEducacaoEspecializada = '';
switch($ed255_i_aee) {

  case 1:

    $sMensagemEducacaoEspecializada  = "<b>* Escola oferece EXCLUSIVAMENTE Atendimento Educacional Especial - ";
    $sMensagemEducacaoEspecializada .= "AEE (Cadastros -> Dados da Escola -> Aba Infraestrutura)</b>";
    break;

  case 2:

    $sMensagemEducacaoEspecializada  = "<b>* Escola oferece Atendimento Educacional Especial - ";
    $sMensagemEducacaoEspecializada .= "AEE (Cadastros -> Dados da Escola -> Aba Infraestrutura)</b>";
    break;

  case 3:

    $sMensagemEducacaoEspecializada  = "<b>* Escola NÃO oferece Atendimento Educacional Especial - ";
    $sMensagemEducacaoEspecializada .= "AEE (Cadastros -> Dados da Escola -> Aba Infraestrutura)</b>";
    break;
}

echo "<div style='text-align:center'>";
echo $sMensagemAtividadeComplementar."<br>";
echo $sMensagemEducacaoEspecializada."<br>";
echo "</div>";

if ($ed255_i_aee == 3 && $ed255_i_ativcomplementar == 3) {
	
  $db_botao  = false;
  $db_botao2 = false;
  
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

if (isset($incluir)) {
	
  db_inicio_transacao();
  if ($ed268_i_tipoatend == 5) {
    
    $ed268_c_aee                = PegaValores($ed268_c_aee,12);
    $ed268_programamaiseducacao = null;
  } else {
    $ed268_c_aee = "";
  }
  
  $clturmaac->ed268_programamaiseducacao = $ed268_programamaiseducacao;
  $clturmaac->ed268_c_aee                = $ed268_c_aee;
  $clturmaac->ed268_c_descr              = trim($ed268_c_descr);
  $clturmaac->incluir($ed268_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Turma com Atividade Complementar / AEE</b></legend>
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
if (isset($incluir)) {
	
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
    $ultimo = $clturmaac->ed268_i_codigo;
    ?>
     <script>
      parent.location.href = "edu1_turmaacabas002.php?chavepesquisa=<?=$ultimo?>&tipoatendimento=<?=$ed268_i_tipoatend?>";
     </script>
    <?
    
  }
}
?>