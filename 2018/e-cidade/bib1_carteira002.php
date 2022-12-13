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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_carteira_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcarteira = new cl_carteira;
$clcarteira->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi10_codigo");
$clrotulo->label("bi07_nome");
$clrotulo->label("z01_nome");
$depto = db_getsession("DB_coddepto");
$db_botao = false;
if(isset($alterar)){
 $db_opcao = 2;
 $opcao = 3;
 db_inicio_transacao();
 $clcarteira->bi16_valida = 'S';
 $clcarteira->alterar($bi16_codigo);
 db_fim_transacao();
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 $opcao = 3;
 $campos = "case
             when aluno.ed47_i_codigo is not null
              then aluno.ed47_v_nome
             when cgmrh.z01_numcgm is not null
              then cgmrh.z01_nome
             when cgmcgm.z01_numcgm is not null
              then cgmcgm.z01_nome
             else
              cgmpub.z01_nome
            end as z01_nome,
            carteira.*,
            bi07_nome
           ";

 $result = $clcarteira->sql_record($clcarteira->sql_query("",$campos,""," bi16_codigo = $chavepesquisa"));
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
<form name="form1" method="post" action="">
<table width="95%" border="0" align="center">
 <tr>
  <td>
   <fieldset width="50%"><legend><b>Dados do Leitor para Cadastro de Carteira:</b></legend>
    <table border="0">
     <tr>
      <td nowrap title="<?=@$Tbi16_leitor?>">
      <?db_ancora(@$Lbi16_leitor,"",$opcao);?>
      </td>
      <td>
       <?db_input('bi16_leitor',10,$Ibi16_leitor,true,'text',$opcao,"")?>
       <?db_input('z01_nome',50,@$z01_nome,true,'text',3," ")?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
<table border="0" width="95%" align="center">
 <tr>
  <td colspan="2">
   <fieldset width="100%"><legend><b>Nova Carteira:</b></legend>
    <?include("forms/db_frmcarteira.php");?>
   </fieldset>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?
if(isset($alterar)){
 if($clcarteira->erro_status=="0"){
  $clcarteira->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcarteira->erro_campo!=""){
   echo "<script> document.form1.".$clcarteira->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcarteira->erro_campo.".focus();</script>";
  };
 }else{
  $clcarteira->erro(true,false);
  db_redireciona("bib1_carteira001.php?bi16_leitor=$bi16_leitor&z01_nome=$z01_nome");
 };
};
if(isset($cancelar)){
 db_redireciona("bib1_carteira001.php?bi16_leitor=$bi16_leitor&z01_nome=$z01_nome");
}
?>