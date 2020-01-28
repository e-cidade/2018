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

include("classes/db_prontuarios_classe.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");

include("dbforms/db_funcoes.php");

$z01_d_cadast_dia = date("d",db_getsession("DB_datausu"));
$z01_d_cadast_mes = date("m",db_getsession("DB_datausu"));
$z01_d_cadast_ano = date("Y",db_getsession("DB_datausu"));
$z01_i_login = DB_getsession("DB_id_usuario");


db_postmemory($HTTP_POST_VARS);

$clprontuarios = new cl_prontuarios;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
  db_inicio_transacao();
  
  $clprontuarios->sd24_i_numcgs = $cgs;
  if( empty( $chavepesquisaprontuario ) ){
    die("esta funcionando na aba 001")  ;
  }else{
     $clprontuarios->sd24_i_codigo = $chavepesquisaprontuario;
     $clprontuarios->alterar($chavepesquisaprontuario);
  }

  db_fim_transacao();
}else if(isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)){

   $result = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario));
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
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfichaatenddiag.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form1","sd24_t_diagnostico",true,1,"sd24_t_diagnostico",true);
  document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;

</script>
<?
if(isset($incluir)){
  if($clprontuarios->erro_status=="0"){
    $clprontuarios->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontuarios->erro_campo!=""){
      echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
    }
  }else{
    $clprontuarios->erro(true,false);
    ?>
     <script>
       //parent.document.formaba.a3.disabled = false;
       //parent.iframe_a4.location.href='sau4_fichaatendabas003.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'
       //parent.mo_camada('a4');
     </script>
    <?
  }


}
?>