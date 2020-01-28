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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$escola = db_getsession("DB_coddepto");
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_c_nome");
if(!isset($opcaoescola)){
 $opcaoescola = "F";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td height="63" align="center" valign="top">
   <table width="35%" border="0" align="center" cellspacing="0">
    <form name="form2" method="post" action="" >
    <tr>
     <td width="4%" align="right" nowrap title="<?=$Ted18_c_nome?>">
      <?=$Led18_c_nome?>
     </td>
     <td width="96%" align="left" nowrap>
      <?db_input("ed18_c_nome",40,$Ied18_c_nome,true,"text",4,"","chave_ed18_c_nome");?>
      <select name="opcaoescola" onchange="location.href='?funcao_js=parent.js_mostraescolafora1|ed18_i_codigo|ed18_c_nome|ed261_c_nome|ed260_c_sigla|tipoescoladescr|tipoescola&opcaoescola='+this.value">
       <option value="F" <?=@$opcaoescola=="F"?"selected":""?>>Escolas Fora da Rede</option>
       <option value="M" <?=@$opcaoescola=="M"?"selected":""?>>Escolas da Rede</option>
      </select>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_escolafora.hide();">
     </td>
    </tr>
    </form>
   </table>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $where  = "";
   $where1 = "";
   if(isset($chave_ed18_c_nome) && (trim($chave_ed18_c_nome)!="") ){
    $where  = " AND ed18_c_nome like '%$chave_ed18_c_nome%' ";
    $where1 = " WHERE ed82_c_nome like '%$chave_ed18_c_nome%' ";
   }
   if($opcaoescola=="F"){
    $sql = "SELECT ed82_i_codigo as ed18_i_codigo,ed82_c_nome as ed18_c_nome,ed82_i_censomunic as ed261_c_nome,ed82_i_censouf as ed260_c_sigla,'FORA DA REDE' as tipoescoladescr,'F' as tipoescola
            FROM escolaproc
            $where1
            ORDER BY to_ascii(ed82_c_nome)
            ";
   }else{
    $sql = "SELECT ed18_i_codigo,ed18_c_nome,ed261_c_nome,ed260_c_sigla,'ESCOLA DA REDE' as tipoescoladescr,'M' as tipoescola
            FROM escola
             inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf
             inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic
            WHERE ed18_i_codigo not in ($escola)
            $where
            ORDER BY to_ascii(ed18_c_nome)
           ";
   }
   if(!isset($pesquisa_chave)){
    $repassa = array();
    if(isset($chave_ed18_c_nome)){
     $repassa = array("chave_ed18_c_nome"=>$chave_ed18_c_nome,"opcaoescola"=>$opcaoescola);
    }
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }
   ?>
   </td>
  </tr>
</table>
</body>
</html>
<?
	js_tabulacaoforms("form2","chave_ed18_c_nome",true,1,"chave_ed18_c_nome",true);
?>