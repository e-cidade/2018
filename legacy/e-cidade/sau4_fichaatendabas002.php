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
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$z01_d_cadast_dia = date("d",db_getsession("DB_datausu"));
$z01_d_cadast_mes = date("m",db_getsession("DB_datausu"));
$z01_d_cadast_ano = date("Y",db_getsession("DB_datausu"));
$z01_i_login      = DB_getsession("DB_id_usuario");
$clprontuarios    = new cl_prontuarios;
$db_opcao         = 1;
$db_botao         = true;

$oSauConfig = loadConfig("sau_config");

if(isset($incluir)){
  db_inicio_transacao();
  
  $clprontuarios->sd24_i_numcgs = $cgs;
  if( empty( $chavepesquisaprontuario ) ){
    /*
     //gera numatend
     $sql_fc    = "select fc_numatend()";
     $query_fc  = pg_query($sql_fc) or die(pg_errormessage().$sql_fc);
     $fc_numatend = explode(",",pg_result($query_fc,0,0));
     $clprontuarios->sd24_i_ano = trim($fc_numatend[0]);
     $clprontuarios->sd24_i_mes = trim($fc_numatend[1]);
     $clprontuarios->sd24_i_seq = trim($fc_numatend[2]);
     //$clprontuarios->sd24_i_unidade = db_getsession("DB_coddepto");
     $clprontuarios->sd24_i_cid = $sd24_i_cid;
     $clprontuarios->sd24_i_unidade = $sd24_i_unidade;
     $clprontuarios->sd24_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
     $clprontuarios->sd24_c_cadastro = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
     
     $clprontuarios->incluir("");
     $chavepesquisaprontuario = $clprontuarios->sd24_i_codigo;
   */
    //die("esta funcionando na aba 001")  ;
  }else{
     $clprontuarios->sd24_i_codigo = $chavepesquisaprontuario;
     $clprontuarios->alterar($chavepesquisaprontuario);
  }

  db_fim_transacao();
}else if(isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)){

   //$result = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario));
   $result = $clprontuarios->sql_record($clprontuarios->sql_query(null, "*, m.z01_nome as profissional", null, " prontuarios.sd24_i_codigo = $chavepesquisaprontuario" ));
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfichaatendpront.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form1","sd24_v_motivo",true,1,"sd24_v_motivo",true);
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
    ?>
     <script>
       parent.document.formaba.a3.disabled = false;
       //parent.document.formaba.a4.disabled = false;
       parent.iframe_a3.location.href='sau4_fichaatendabas003.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>&cgs=<?=$cgs?>'
       //parent.iframe_a4.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>&cgs=<?=$cgs?>'
       parent.mo_camada('a3');
     </script>
    <?
  }
}
?>