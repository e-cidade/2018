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
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");

$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcorgao->rotulo->label();
$clorcunidade->rotulo->label();

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){
   sel_instit  = new Number(document.form1.db_selinstit.value);
   obj=document.form1;
   orgao= obj.o40_orgao.value;
   unidade= obj.o41_unidade.value;

   if(sel_instit == 0){
     alert('Você não escolheu nenhuma Instituição. Verifique!');
     return false;
   } else if (orgao == 0){
     alert('Selecione  Orgao !');
     return false;   
   }else{
     jan = window.open('orc2_reldespesas002_02.php?db_selinstit='+sel_instit+'&o40_orgao='+orgao+'&unidade='+unidade,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
     return true; 
   }
}
function js_troca(nome){
  document.form1.submit();
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
   <!-- orc2_reldespesas002.php -->
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">
	<?
	 db_selinstit('parent.js_limpa');
	?>
	</td>
    </tr>

   <tr>
     <td align="right"><?=$Lo40_orgao?></td>
     <td>
     <?
      $result = $clorcorgao->sql_record($clorcorgao->sql_query(null,null,"o40_orgao,o40_descr","o40_orgao","o40_anousu=".db_getsession("DB_anousu")." and o40_instit=".db_getsession("DB_instit")));
      db_selectrecord("o40_orgao",$result,true,2,"","","","0",$onchange=" js_troca('o40_orgao');");
     ?>
     </td>
   </tr>
   
  <tr>
    <td align="right"><?=$Lo41_unidade?></td>
    <td>
    <?
    if(isset($o40_orgao)){
      $result = $clorcunidade->sql_record($clorcunidade->sql_query(null,null,null,"o41_unidade,o41_descr","o41_unidade","o41_anousu=".db_getsession("DB_anousu")."  and o41_orgao=$o40_orgao " ));
      db_selectrecord("o41_unidade",$result,true,2,"","","",($clorcunidade->numrows>1?"0":""),$onchange="  js_troca('o41_unidade');");
    }else{
      db_input("o41_unidade",6,0,true,"hidden",0);
   }
   ?>
   </td>
  </tr>

      

  <tr>
    <td colspan="2" align = "center"> 
       <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
    </td>
  </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>