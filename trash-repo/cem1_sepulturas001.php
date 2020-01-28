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
include("classes/db_sepulturas_classe.php");
include("classes/db_quadracemit_classe.php");
include("classes/db_lotecemit_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clsepulturas  = new cl_sepulturas;
$cllotecemit   = new cl_lotecemit; 
$clquadracemit = new cl_quadracemit;
$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){

  $erro = false;

  $clsepulturas->sql_record( $clsepulturas->sql_query("","*","","cm23_i_codigo = $cm05_i_lotecemit and cm05_c_numero = $cm05_c_numero" ) );

  //verifica o tipo de quadra
 $result_quadra = $clquadracemit->sql_record( $clquadracemit->sql_query("","cm22_c_tipo","","cm22_i_codigo = $cm23_i_quadracemit") );

  db_fieldsmemory($result_quadra,0);

  if( $clsepulturas->numrows == 0 ){

    if($cm22_c_tipo != "C"){
     $erro = true;
     db_msgbox('AVISO:\nOperação não Efetuada!\n\nLote ou Numero informado já foi selecionada para uma Sepultura.\nPara cadastrar mais de uma Sepultura na mesma Quadra e Lote, o tipo de quadra deve ser Campa');
     unset($incluir);
    }
  
   $cllotecemit->cm23_i_codigo = $cm05_i_lotecemit;
   $cllotecemit->cm23_b_selecionado = 'true';

  }else{

   $erro = true;
   db_msgbox('AVISO:\nOperação não Efetuada\n\nNumero de Sepultura informado já foi selecionada para uma Sepultura.\nPara cadastrar mais de uma Sepultura na mesma Quadra e Lote, o tipo de quadra deve ser Campa');
   unset($incluir);
    
  }

  db_inicio_transacao();

  $clsepulturas->incluir($cm05_i_codigo);
  $cllotecemit->alterar($cm05_i_lotecemit);

  db_fim_transacao($erro);
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
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <br>
     <?
     include("forms/db_frmsepulturas.php");
     ?>
    </center>
     </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","cm05_i_campa",true,1,"cm05_i_campa",true);
</script>
<?
if(isset($incluir)){
  if($clsepulturas->erro_status=="0"){
    $clsepulturas->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsepulturas->erro_campo!=""){
      echo "<script> document.form1.".$clsepulturas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsepulturas->erro_campo.".focus();</script>";
    }
  }else{
    $clsepulturas->erro(true,true);
  }
}
?>