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

//MODULO: biblioteca
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_biblioteca_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbiblioteca = new cl_biblioteca;
$clrotulo = new rotulocampo;
$clrotulo->label("bi06_seq");
$clrotulo->label("bi06_titulo");
$depto = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo",""," bi17_coddepto = $depto"));
if($clbiblioteca->numrows!=0){
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
    <td width="4%" align="right" nowrap title="<?=$Tbi06_seq?>">
     <?=$Lbi06_seq?>
    </td>
    <td width="96%" align="left" nowrap>
     <?db_input("bi06_seq",10,$Ibi06_seq,true,"text",4,"","chave_bi06_seq");?>
    </td>
   </tr>
   <tr>
    <td width="4%" align="right" nowrap title="<?=$Tbi06_titulo?>">
     <?=$Lbi06_titulo?>
    </td>
    <td width="96%" align="left" nowrap>
     <?db_input("bi06_titulo",40,$Ibi06_titulo,true,"text",4,"","chave_bi06_titulo");?>
    </td>
   </tr>
   <tr>
    <td colspan="2" align="center">
     <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
     <input name="limpar" type="reset" id="limpar" value="Limpar" >
     <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acervo.hide();">
     <input name="reserva" type="hidden" id="reserva" value="<?=$reserva?>">
    </td>
   </tr>
  </form>
  </table>
   </td>
 </tr>
 <tr>
  <td align="center" valign="top">
   <?
   $where = "";
   if(isset($chave_bi06_seq) && (trim($chave_bi06_seq)!="") ){
    $where = " AND bi06_seq = $chave_bi06_seq";
   }else if(isset($chave_bi06_titulo) && (trim($chave_bi06_titulo)!="") ){
    $where = " AND bi06_titulo like '$chave_bi06_titulo%'";
   }
   if(isset($pesquisa_chave)){
    $where1 = " AND bi06_seq = $pesquisa_chave";
   }else{
    $where1 = "";
   }
   $hoje = date("Y-m-d");
   $sql = "SELECT * FROM
           (SELECT DISTINCT ON (bi06_seq,bi06_titulo,bi23_codigo,bi23_codbarras)
                                bi06_seq,
                                bi06_titulo,
                                bi23_codigo,
                                bi23_codbarras,
                                bi18_devolucao
            from (SELECT bi06_seq,
                         bi06_titulo,
                         bi23_codigo,
                         bi23_codbarras,
                         case
                          when bi18_devolucao is null
                           then '$hoje'
                          when bi18_devolucao is not null
                               AND bi18_devolucao > '$hoje'
                               AND (select bi21_entrega from devolucaoacervo
                                    where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo) is null
                           then bi18_devolucao
                          when bi18_devolucao is not null
                               AND bi18_devolucao > '$hoje'
                               AND (select bi21_entrega from devolucaoacervo
                                    where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo) is not null
                           then '$hoje'
                          when bi18_devolucao is not null
                               AND bi18_devolucao < '$hoje'
                               AND (select bi21_entrega from devolucaoacervo
                                    where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo) is null
                           then null
                          when bi18_devolucao is not null
                               AND bi18_devolucao < '$hoje'
                               AND (select bi21_entrega from devolucaoacervo
                                    where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo) is not null
                           then '$hoje'
                          else null
                         end as bi18_devolucao
                  FROM exemplar
                  inner join acervo on acervo.bi06_seq = exemplar.bi23_acervo
                  left join emprestimoacervo on emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                  left join emprestimo on emprestimo.bi18_codigo = emprestimoacervo.bi19_emprestimo
                  WHERE bi23_situacao = 'S'
                  AND bi06_biblioteca = $bi17_codigo
                  $where
                  $where1
                  order by bi18_devolucao desc
                 ) as x
           ) as x
           ";
   $repassa = array();
   if(isset($chave_bi23_codigo)){
    $repassa = array("chave_bi06_seq"=>$chave_bi06_seq,"chave_bi06_titulo"=>$chave_bi06_titulo,"reserva"=>$reserva);
   }
   if(!isset($pesquisa_chave)){
    $sql .= " ORDER BY bi18_devolucao DESC";
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }else{
    if($pesquisa_chave!=null && $pesquisa_chave!=""){
     $result = pg_query($sql);
     $linhas = pg_num_rows($result);
     if($linhas!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$bi06_titulo','$bi18_devolucao',false);</script>";
     }else{
      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
     }
    }else{
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
js_tabulacaoforms("form2","chave_bi06_titulo",true,1,"chave_bi06_titulo",true);
</script>