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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_db_depart_classe.php");
include("classes/db_escolalivros_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldb_depart = new cl_db_depart;
$clescolalivros = new cl_escolalivros;
$clescolalivros->rotulo->label();
$ed134_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome = db_getsession("DB_nomedepto");
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
 db_inicio_transacao();
 for($t=0;$t<count($ed134_i_codigo);$t++){
  if($ed134_i_codigo[$t]==""){
   $clescolalivros->ed134_i_escola = $ed134_i_escola;
   $clescolalivros->ed134_i_ano = $ed134_i_ano;
   $clescolalivros->ed134_i_serie = $ed134_i_serie[$t];
   $clescolalivros->ed134_i_devolucao = $ed134_i_devolucao_x[$t];
   $clescolalivros->ed134_i_reutilizavel = $ed134_i_reutilizavel_x[$t];
   $clescolalivros->incluir(null);
  }else{
   $clescolalivros->ed134_i_escola = $ed134_i_escola;
   $clescolalivros->ed134_i_ano = $ed134_i_ano;
   $clescolalivros->ed134_i_serie = $ed134_i_serie[$t];
   $clescolalivros->ed134_i_devolucao = $ed134_i_devolucao_x[$t];
   $clescolalivros->ed134_i_reutilizavel = $ed134_i_reutilizavel_x[$t];
   $clescolalivros->ed134_i_codigo = $ed134_i_codigo[$t];
   $clescolalivros->alterar($ed134_i_codigo[$t]);
  }
 }
 db_fim_transacao();
 $clescolalivros->erro(true,true);
}elseif(isset($chavepesquisa)){
 $result = $clescolalivros->sql_record($clescolalivros->sql_query_file("","ed134_i_codigo as nada",""," ed134_i_escola = $ed134_i_escola AND ed134_i_ano = $chavepesquisa"));
 if($clescolalivros->numrows!=0){
  $db_opcao = 2;
 }else{
  $db_opcao = 1;
 }
 $ed134_i_ano = $chavepesquisa;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:98%;padding:1px;"><legend><b>Devolução de Livros Didáticos</b></legend>
    <table width="100%" >
     <tr>
      <td valign="top">
       <?=$Led134_i_escola?>
      </td>
      <td>
       <?db_input('ed134_i_escola',20,$Ied134_i_escola,true,'text',3,"")?>
       <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td>
       <?=$Led134_i_ano?>
      </td>
      <td>
       <?db_input('ed134_i_ano',4,@$Ied134_i_ano,true,'text',$db_opcao,"")?>
       <input name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisar();">
      </td>
     </tr>
     <?if(isset($chavepesquisa)){?>
     <tr>
      <td colspan="2">
       <br><br>
       <table border="1" cellspacing="0" cellpadding="2" width="95%">
        <tr align="center" bgcolor="#DBDBDB">
         <td><?=$Led134_i_serie?></td>
         <td><?=$Led134_i_devolucao?></td>
         <td><?=$Led134_i_reutilizavel?></td>
        </tr>
        <?
        $arr_serie = array('1'=>'2ª SÉRIE / 3º ANO','2'=>'3ª SÉRIE / 4º ANO','3'=>'4ª SÉRIE / 5º ANO','4'=>'5ª SÉRIE / 6º ANO','5'=>'6ª SÉRIE / 7º ANO','6'=>'7ª SÉRIE / 8º ANO','7'=>'8ª SÉRIE / 9º ANO');
        for($x=0;$x<7;$x++){
         $linha = $x+1;
         $result3 = $clescolalivros->sql_record($clescolalivros->sql_query_file("","*",""," ed134_i_escola = $ed134_i_escola AND ed134_i_ano = $chavepesquisa AND ed134_i_serie = $linha"));
         if($clescolalivros->numrows>0){
          db_fieldsmemory($result3,0);
         }
         ?>
         <tr align="center" bgcolor="#f3f3f3">
          <td><input name="ed134_i_serie[]" id="ed134_i_serie" type="hidden" value="<?=($x+1)?>" size="5">
              <input name="ed134_i_codigo[]" id="ed134_i_codigo" type="hidden" value="<?=@$ed134_i_codigo?>" size="10">
              <input name="descricao" id="descricao" type="text" value="<?=$arr_serie[$x+1]?>" size="30" style="background:#DEB887" readOnly></td>
          <td><input name="ed134_i_devolucao_x[]" id="ed134_i_devolucao_x" type="text" value="<?=@$ed134_i_devolucao?>" size="4" maxlength="4" onchange="js_valida(this,1,<?=$x?>);" style="text-align:center;"></td>
          <td><input name="ed134_i_reutilizavel_x[]" id="ed134_i_reutilizavel_x" type="text" value="<?=@$ed134_i_reutilizavel?>" size="4" maxlength="4" onchange="js_valida(this,2,<?=$x?>);" style="text-align:center;"></td>
         </tr>
         <?
        }
        ?>
       </table>
      </td>
     </tr>
     <tr>
      <td colspan="2" align="center">
       <input name="incluir" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
      </td>
     </tr>
     <?}?>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed134_i_ano",true,1,"ed134_i_ano",true);
</script>
<script>
function js_pesquisar(){
 if(document.form1.ed134_i_ano.value!=""){
  location.href = "edu1_escolalivros001.php?chavepesquisa="+document.form1.ed134_i_ano.value;
 }else{
  alert("Informe o Ano Referente!");
 }
}
function js_valida(campo,tipo,linha){
 if(campo.value=="0"){
  alert("Quantidade inválida!");
  campo.value = "";
  campo.focus();
 }
 if(campo.value!=""){
  if(tipo==1){
   if(document.form1.ed134_i_devolucao_x[linha].value<document.form1.ed134_i_reutilizavel_x[linha].value){
    alert("Quantidade de Livros Devolvidos deve ser maior quantidade que Livros com Reutilização!"); 
    campo.value = "";
    campo.focus();
   }
  }
  if(tipo==2){
   if(document.form1.ed134_i_reutilizavel_x[linha].value>document.form1.ed134_i_devolucao_x[linha].value){
    alert("Quantidade de Livros com Reutilização deve ser menor quantidade que Livros Devolvidos!"); 
    campo.value = "";
    campo.focus();
   }
  }
 }
}
</script>