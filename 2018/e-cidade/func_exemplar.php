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

//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_exemplar_classe.php");
include("classes/db_biblioteca_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clexemplar   = new cl_exemplar;
$clbiblioteca = new cl_biblioteca;
$clrotulo     = new rotulocampo;
$clrotulo->label("bi06_colecaoacervo");
$clrotulo->label("bi29_nome");
$clexemplar->rotulo->label("bi23_codigo");
$clrotulo->label("bi06_titulo");
$depto  = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo",""," bi17_coddepto = $depto"));
if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($result,0);
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
    <td width="4%" align="right" nowrap="nowrap" ><?=$Lbi06_titulo?></td>
    <td width="96%" align="left" nowrap="nowrap" title="<?=$Tbi23_codigo?>">
      <?db_input("bi23_codigo",10,$Ibi23_codigo,true,"text",4,"","chave_bi23_codigo");?>
    </td>
    <td width="96%" align="left" nowrap="nowrap" title="<?=$Tbi06_titulo?>">
      <?db_input("bi06_titulo",40,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
    </td>
   </tr>
   <tr>
    <td width="4%" class='bold' align="right" nowrap="nowrap" >Coleção:</td>
    <td width="96%" align="left" nowrap="nowrap" title="<?=$Tbi06_colecaoacervo?>">
      <?db_input("bi06_colecaoacervo", 10, $Ibi06_colecaoacervo, true, "text", 4, "","chave_bi06_colecaoacervo");?>
    </td>
    <td width="96%" align="left" nowrap="nowrap"  title="<?=$Tbi29_nome?>">
      <?db_input("bi29_nome",40,$Ibi29_nome, true, "text",4,"","chave_bi29_nome");?>
    </td>
   </tr>
   <tr>
    <td colspan="2" align="center">
     <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
     <input name="limpar" type="reset" id="limpar" value="Limpar" >
     <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_exemplar.hide();">
    </td>
   </tr>
  </form>
  </table>
   </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   if(isset($campos)==false){
    if(file_exists("funcoes/db_func_exemplar.php")==true){
     include("funcoes/db_func_exemplar.php");
    }else{
     $campos = "exemplar.*";
    }
   }
   $sql = "SELECT $campos
           FROM exemplar
            inner join acervo on acervo.bi06_seq = exemplar.bi23_acervo
            inner join aquisicao on aquisicao.bi04_codigo = exemplar.bi23_aquisicao
            left  join colecaoacervo  on  colecaoacervo.bi29_sequencial = acervo.bi06_colecaoacervo
           WHERE not exists(select * from emprestimoacervo
                            where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                            and not exists(select *
                                           from devolucaoacervo
                                           where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                          )
                            )
           AND bi23_situacao = 'S'
           AND bi06_biblioteca = $bi17_codigo
          ";
   if(isset($chave_bi23_codigo) && (trim($chave_bi23_codigo)!="") ){
     $sql .= " AND bi23_codigo = $chave_bi23_codigo ORDER BY bi06_titulo";
   }else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
     $sql .= " AND bi06_titulo like '$chave_bi06_titulo%' ORDER BY bi06_titulo";
   } else if (isset($chave_bi06_colecaoacervo) && (trim($chave_bi06_colecaoacervo) != "")) {
     $sql .= " AND bi06_colecaoacervo = {$chave_bi06_colecaoacervo} ORDER BY bi29_nome";
   } else if (isset($chave_bi29_nome) && (trim($chave_bi29_nome) != "")) {
     $sql .= " AND bi29_nome ilike '$chave_bi29_nome%' ORDER BY bi29_nome";
   }
   
   $repassa = array();
   if (isset($chave_bi23_codigo)) {
     $repassa = array("chave_bi23_codigo"=>$chave_bi23_codigo,"chave_bi06_titulo"=>$chave_bi06_titulo);
   }
   if (!isset($pesquisa_chave)) {
     @db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   } else {
     if ($pesquisa_chave!=null && $pesquisa_chave!="") {
       $sql .= " AND bi23_codigo = $pesquisa_chave ORDER BY bi06_titulo";
       $result = db_query($sql);
       $linhas = pg_num_rows($result);
       if ($linhas != 0) {

         db_fieldsmemory($result,0);
         echo "<script>".$funcao_js."('$bi06_titulo',false);</script>";
       } else {
         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
       }
    } else {
     echo "<script>".$funcao_js."('',false);</script>";
    }
   }
   ?>
   </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_bi23_codigo",true,1,"chave_bi23_codigo",true);
</script>