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
include("classes/db_renovacoes_classe.php");
include("classes/db_sepulta_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS); 
$clsepulta   = new cl_sepulta;
$clrenovacoes = new cl_renovacoes;
$db_botao = true;
if(isset($incluir)){
 
$data1 = strtotime(substr($cm01_d_falecimento,6,4).'-'.substr($cm01_d_falecimento,3,2).'-'.substr($cm01_d_falecimento,0,2)); 
$data2 = strtotime(substr($cm07_d_vencimento,6,4).'-'.substr($cm07_d_vencimento,3,2).'-'.substr($cm07_d_vencimento,0,2));

  if($data1 > $data2){
   db_msgbox('Aviso:\nA data Informada de vencimento da Renovação da Sepultura é inferior a data de Falecimento do Sepultamento');
   $erro = true;
  }else{
   $clrenovacoes->cm07_d_ultima = date('Y-m-d',db_getsession("DB_datausu"));
   db_inicio_transacao();
   
   $clrenovacoes->incluir(null);
   
   db_fim_transacao();
  }

}
if(isset($processar)){
      $campos = "cm07_i_sepultamento,
                 cgm1.z01_nome ,
                 cm07_i_codigo, 
            case when cm07_d_ultima is null 
                 then cm07_d_vencimento
                 else cm07_d_ultima end as cm07_d_ultima,
                 cm07_d_vencimento,
                 to_char(cm01_d_falecimento,'DD/MM/YYYY') as cm01_d_falecimento";
   $result = $clrenovacoes->sql_record($clrenovacoes->sql_query(null,"$campos","cm07_i_codigo desc limit 1","cm07_i_sepultamento = ".$cm07_i_sepultamento));
   $result_sepulta = $clsepulta->sql_record($clsepulta->sql_query("","cm24_i_sepultamento, to_char(cm01_d_falecimento,'DD/MM/YYYY') as cm01_d_falecimento","","cm24_i_sepultamento = $cm07_i_sepultamento"));
    if($clsepulta->numrows == 0){
     db_msgbox('Aviso:\nO Sepultamento não está localizado em uma sepultura!\n\n Obs.:Só é permitida a Renovação de Sepulturas');
     echo "<script>location='';</script>";
    }
  @db_fieldsmemory($result_sepulta,0);
  @db_fieldsmemory($result,0);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center> <br>
     <?
     include("forms/db_frmrenovacoes.php");
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
<?
if(isset($incluir) || isset($alterar)){
  if($clrenovacoes->erro_status=="0"){
    db_msgbox($clrenovacoes->erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clrenovacoes->erro_campo!=""){
      echo "<script> document.form1.".$clrenovacoes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrenovacoes->erro_campo.".focus();</script>";
    };
  }else{
   if(@$erro != true){
    db_msgbox($clrenovacoes->erro_msg);
    echo "<script>";
    echo "jan = window.open('cem2_renovacoes.php?&cod=".$clrenovacoes->cm07_i_codigo."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    echo "jan.moveTo(0,0);";
    echo "</script>";
   }
  };
};
?>