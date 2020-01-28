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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_infaluno_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_periodocalendario_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmer_infaluno           = new cl_mer_infaluno;
$clcalendario             = new cl_calendario;
$clperiodocalendario      = new cl_periodocalendario;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$db_opcao                 = 1;
$db_opcao2                = 1;
$db_botao                 = true;
$db_botao1                = false;
if (isset($opcao)) {
    
  $result1 = $clmer_infaluno->sql_record(
                                                $clmer_infaluno->sql_query("",
                                                                                 "*,fc_idade(ed47_d_nasc,current_date) as idade",
                                                                                 "",
                                                                                 "me14_i_codigo = $me14_i_codigo"
                                                                                )
                                              );
  if ($clmer_infaluno->numrows>0) {
    db_fieldsmemory($result1,0);
  }
  if ( $opcao == "alterar") {
    
    $db_opcao  = 2;
    $db_botao1 = true;
  } else {
    
    if ( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
        
      $db_opcao  = 3;
      $db_botao1 = true;
    } else {
        
      if (isset($alterar)) {
        
        $db_opcao  = 2;
        $db_botao1 = true;
        
      }
    }
  }
}
if (isset($incluir)) {
	
  db_inicio_transacao();
  if(isset($periodoavaliacao) && $periodoavaliacao!=""){
   $clmer_infaluno->me14_i_periodocalendario = $periodoavaliacao;
   $clmer_infaluno->me14_i_mes = "";
   $clmer_infaluno->me14_i_ano = "";
  }elseif(isset($me14_i_mes) && $me14_i_mes!=""){
   $clmer_infaluno->me14_i_periodocalendario = null;
   $clmer_infaluno->me14_i_mes = $me14_i_mes;
   $clmer_infaluno->me14_i_ano = $me14_i_ano;
  }
  $clmer_infaluno->me14_d_data = date("Y-m-d",db_getsession("DB_datausu"));
  $clmer_infaluno->incluir($me14_i_codigo);
  db_fim_transacao();
 
}
if (isset($alterar)) {
	
  $db_opcao  = 2;
  $db_opcao2 = 3;
  db_inicio_transacao();
  if(isset($periodoavaliacao) && $periodoavaliacao!=""){
   $clmer_infaluno->me14_i_periodocalendario = $periodoavaliacao;
   $clmer_infaluno->me14_i_mes = "";
   $clmer_infaluno->me14_i_ano = "";
  }elseif(isset($me14_i_mes) && $me14_i_mes!=""){
   $clmer_infaluno->me14_i_periodocalendario = null;
   $clmer_infaluno->me14_i_mes = $me14_i_mes;
   $clmer_infaluno->me14_i_ano = $me14_i_ano;
  }
  $clmer_infaluno->me14_d_data = date("Y-m-d",db_getsession("DB_datausu"));
  $clmer_infaluno->alterar($me14_i_codigo);
  db_fim_transacao();
  
}   
if (isset($excluir)) {
	
  db_inicio_transacao();
  $db_opcao = 3;
  $clmer_infaluno->excluir($me14_i_codigo);
  db_fim_transacao();
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Informação do Aluno</b></legend>
    <?include("forms/db_frmmer_infaluno.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","me14_i_aluno",true,1,"me14_i_aluno",true);
</script>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
    
 if ($clmer_infaluno->erro_status == "0") {
    
  $clmer_infaluno->erro(true,false);
  $db_botao = true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  
  if ($clmer_infaluno->erro_campo!="") {
    
    echo "<script> document.form1.".$clmer_infaluno->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clmer_infaluno->erro_campo.".focus();</script>";
    
  }
 } else {
    
   $clmer_infaluno->erro(true,false);
   db_redireciona("mer1_mer_infaluno001.php?me14_i_aluno=$me14_i_aluno&ed47_v_nome=$ed47_v_nome");
   
 }
}

if (@$opcao == "alterar") {
  if(isset($me14_i_periodocalendario) && $me14_i_periodocalendario != ""){?>
  	<script> 
  	document.getElementById('periodo').style.display          = '';
    document.getElementById('calendario2').style.display = '';
  	</script>
  <?}
	if(isset($me14_i_ano) && $me14_i_ano!=""){?>
    <script> 
      
    document.getElementById('mes').style.display = '';
    document.getElementById('ano').style.display = '';  
    </script>
  <?}
}
?>