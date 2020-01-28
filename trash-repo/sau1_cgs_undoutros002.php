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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($_POST);
$clcgs     = new cl_cgs;
$clcgs_und = new cl_cgs_und;

$oPost = db_utils::postMemory($_POST);

$db_opcao = 2;
$db_opcao1 = 3;
$db_botao = true;


if(isset($alterar)) {
  
  $db_opcao = 2;
  $db_opcao1 = 3;
  $db_botao = true;
  
  $lErro = false;
  
  db_inicio_transacao();
  
  if (trim($localrecebefoto) != "") {
    $clcgs_und->z01_o_oid = pg_loimport($conn,$localrecebefoto) or die("Erro(15) importando imagem");
    $clcgs_und->z01_c_foto = trim($localrecebefoto);
  }
  $clcgs->z01_i_numcgs = $z01_i_cgsund;
  $clcgs->z01_c_cartaosus = empty($z01_c_cartaosus)?"'||null||'":$z01_c_cartaosus;
  $clcgs->alterar($z01_i_cgsund);
  
  $clcgs_und->z01_i_cgsund = $z01_i_cgsund;
  $clcgs_und->z01_i_familiamicroarea = (int)$z01_i_familiamicroarea==0?"null":$z01_i_familiamicroarea;
  $clcgs_und->alterar($z01_i_cgsund);
  
  $sWhere = "s201_cgs_unid = $z01_i_cgsund";
  
  $oDaoCgsEtnia = new cl_cgs_undetnia();
  $oDaoCgsEtnia->excluir(null, $sWhere);
  
  if ($oPost->z01_c_raca == "INDÍGENA") {
  	
    $oDaoCgsEtnia->s201_codigo   = null;
    $oDaoCgsEtnia->s201_cgs_unid = $z01_i_cgsund;
    $oDaoCgsEtnia->s201_etnia    = $oPost->s200_codigo;
    $oDaoCgsEtnia->incluir(null);
    
    if ($oDaoCgsEtnia->erro_banco == '0') {
      
      db_msgbox("Não foi possível incluir etnia.\n {$oDaoCgsEtnia->erro_msg}");
      $lErro = true;
    }
  }
  
  
  db_fim_transacao();
}

if(isset($excluirfoto)){
 $sql = "UPDATE cgs_und SET
          z01_c_foto = '',
          z01_o_oid = 0
         WHERE z01_i_codigo = $chavepesquisa
        ";
 $result = db_query($sql) or die ("ERRO: <br> $sql" );
}
if (isset($chavepesquisa)) {
  
  $db_opcao = 2;
  $db_opcao1 = 3;
  $result = $clcgs_und->sql_record($clcgs_und->sql_query_etnia($chavepesquisa));
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <br>
     <center>
    <?include("forms/db_frmcgs_undoutros.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?

if(isset($alterar) ){
 if($clcgs->erro_status=="0"){
  $clcgs->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcgs->erro_campo!=""){
   echo "<script> document.form1.".$clcgs->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcgs->erro_campo.".focus();</script>";
  }
 }else{
      if($clcgs_und->erro_status=="0"){
       $clcgs_und->erro(true,false);
       $db_botao=true;
       echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
       if($clcgs_und->erro_campo!=""){
        echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
       }
      }else{
       $clcgs_und->erro(true,false);
          if(isset ($redireciona) && $redireciona !=""){
          	echo "<script>
                     $redireciona; 
                 </script>";
          
       }else if( 1!=1 && isset( $retornacgs ) ){
            $retornacgs = str_replace( chr(92), "", $retornacgs );
            $retornacgs = str_replace( chr(39), "", $retornacgs );
            $retornacgs = str_replace( "p.", "parent.", $retornacgs );
            $retornanome = str_replace( chr(92), "", $retornanome );
            $retornanome = str_replace( chr(39), "", $retornanome );
            $retornanome = str_replace( "p.", "parent.", $retornanome );
            echo "<script>
                     $retornacgs = ".$clcgs_und->z01_i_cgsund.";
                     $retornanome = '".$clcgs_und->z01_v_nome."';
                     parent.parent.db_iframe_cgs_und.hide();
                 </script>";
            ?>                 
                <script>
                  parent.document.formaba.a3.disabled = false;
                  parent.document.formaba.a3.style.color = "black";
                  parent.iframe_a3.location.href="sau1_cgs_doc002.php?chavepesquisa=<?=$z01_i_cgsund?>";
                  parent.mo_camada('a3');
               </script>
            <?
          }else{
            ?>
                <script>
                  parent.document.formaba.a3.disabled = false;
                  parent.document.formaba.a3.style.color = "black";
                  parent.iframe_a3.location.href="sau1_cgs_doc002.php?chavepesquisa=<?=$z01_i_cgsund?>&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>";
                  parent.mo_camada('a3');
               </script>
            <?
          }
       db_redireciona("sau1_cgs_undoutros002.php?chavepesquisa=$z01_i_cgsund&db_value=Alterar");
      }
 }
}
if(isset($excluirfoto)){
 db_redireciona("sau1_cgs_und002.php?chavepesquisa=$chavepesquisa");
}

if( isset( $retornacgs ) && isset($fechar)){
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

}
?>