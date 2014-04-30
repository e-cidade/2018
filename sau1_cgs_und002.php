<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require("libs/db_stdlibwebseller.php");

include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_ext_classe.php");
include("classes/db_cgs_cartaosus_classe.php");

include("dbforms/db_funcoes.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcgs           = new cl_cgs;
$clcgs_und       = new cl_cgs_und_ext;
$clcgs_cartaosus = new cl_cgs_cartaosus;

$db_opcao  = 22;
$db_opcao1 = 3;
$db_botao  = false;

if(isset($alterar)){

  db_inicio_transacao();
  $db_opcao  = 2;
  $db_opcao1 = 3;

  //Cartão SUS
  if (isset($s115_i_codigo) && (int)$s115_i_codigo > 0) {

    $clcgs_cartaosus->s115_c_cartaosus = $s115_c_cartaosus;
    $clcgs_cartaosus->s115_c_tipo      = $s115_c_tipo;  
    $clcgs_cartaosus->alterar($s115_i_codigo);
    if ($clcgs_cartaosus->erro_status == "0") {

      $clcgs_und->erro_status = $clcgs_cartaosus->erro_status;
      $clcgs_und->erro_msg    = $clcgs_cartaosus->erro_msg;

    }

  } elseif( $s115_c_cartaosus != "") {

    $clcgs_cartaosus->s115_i_cgs       = $z01_i_cgsund;
    $clcgs_cartaosus->s115_c_cartaosus = $s115_c_cartaosus;
    $clcgs_cartaosus->s115_c_tipo      = $s115_c_tipo;
    $clcgs_cartaosus->s115_i_entrada   = 1;  
    $clcgs_cartaosus->incluir(null);
    if ($clcgs_cartaosus->erro_status == "0") {

      $clcgs_und->erro_status = $clcgs_cartaosus->erro_status;
      $clcgs_und->erro_msg    = $clcgs_cartaosus->erro_msg;

    }

  }
  if ($clcgs_und->erro_status != '0') {

   $clcgs_und->z01_d_ultalt = date("Y-m-d");
   $clcgs_und->alterar($z01_i_cgsund);

  }

  db_fim_transacao();
  $db_botao = true;

} elseif (isset($chavepesquisa)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $result    = $clcgs_und->sql_record($clcgs_und->sql_query_ext($chavepesquisa));
  db_fieldsmemory($result,0);
  ?>
  <script>
    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a2.style.color = "black";
    top.corpo.iframe_a2.location.href='sau1_cgs_undoutros002.php?chavepesquisa=<?=$z01_i_cgsund?>&db_value=Alterar';
    //parent.mo_camada('a2');
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a3.style.color = "black";
    top.corpo.iframe_a3.location.href='sau1_cgs_doc002.php?chavepesquisa=<?=$z01_i_cgsund?>&db_value=Alterar';
    //parent.mo_camada('a2');
  </script>
  <?
  //$result_bairro = $clcgs_undbairro->sql_record($clcgs_undbairro->sql_query("","*",""," ed225_i_cgs_und = $chavepesquisa"));
  //if($clcgs_undbairro->numrows>0){
  // db_fieldsmemory($result_bairro,0);
  $j13_codi = @$ed225_i_bairro;
  //}
  $db_botao = true;
  $z01_d_ultalt_dia = $z01_d_ultalt_dia==""?date("d"):$z01_d_ultalt_dia;
  $z01_d_ultalt_mes = $z01_d_ultalt_mes==""?date("m"):$z01_d_ultalt_mes;
  $z01_d_ultalt_ano = $z01_d_ultalt_ano==""?date("Y"):$z01_d_ultalt_ano;
  $z01_d_cadast_dia = $z01_d_cadast_dia==""?date("d"):$z01_d_cadast_dia;
  $z01_d_cadast_mes = $z01_d_cadast_mes==""?date("m"):$z01_d_cadast_mes;
  $z01_d_cadast_ano = $z01_d_cadast_ano==""?date("Y"):$z01_d_cadast_ano;
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
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <center>
    <?include("forms/db_frmcgs_und.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>

<?
if(isset($alterar)){
  if($clcgs_und->erro_status=="0"){
    $clcgs_und->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcgs_und->erro_campo!=""){
      echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
    }
  }else{
    if($clcgs_cartaosus->erro_status=="0"){
      $clcgs_cartaosus->erro(true,false);
      $db_botao=true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if($clcgs_cartaosus->erro_campo!=""){
        echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".focus();</script>";
      }
    }else{
      $clcgs_und->erro(true,false);
      ?>
      <script>
        parent.document.formaba.a2.disabled = false;
        parent.document.formaba.a2.style.color = "black";
        top.corpo.iframe_a2.location.href='sau1_cgs_undoutros002.php?chavepesquisa=<?=$z01_i_cgsund?>&db_value=Alterar';
        parent.mo_camada('a2');
      </script>
      <?
    }
  }
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
if( isset( $retornacgs ) && isset($fechar)){
  if( $z01_d_nasc != ""){
            $retornacgs = str_replace( chr(92), "", $retornacgs );
            $retornacgs = str_replace( chr(39), "", $retornacgs );
            $retornacgs = str_replace( "p.", "parent.", $retornacgs );
            $retornanome = str_replace( chr(92), "", $retornanome );
            $retornanome = str_replace( chr(39), "", $retornanome );
            $retornanome = str_replace( "p.", "parent.", $retornanome );

            //$retornanome = "."'"."document.form1.z01_v_nome.value"."'".";
            echo "
            <script>
                     $retornacgs = document.form1.z01_i_cgsund.value;
                     $retornanome = document.form1.z01_v_nome.value;
                     parent.parent.db_iframe_cgs_und.hide();
             </script>
            ";
  }else{
    db_msgbox("Paciente sem Data de Nascimento, por favor atualize o Cadastro");
    echo "<script> document.form1.z01_d_nasc.style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.z01_d_nasc.focus();</script>";
  }

}

?>