<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acervo_classe.php");
require_once("classes/db_emprestimoacervo_classe.php");
require_once("classes/db_autoracervo_classe.php");
require_once("classes/db_localexemplar_classe.php");
require_once("classes/db_exemplar_classe.php");
require_once("classes/db_db_depart_classe.php");

$clacervo = new cl_acervo;
$clautoracervo = new cl_autoracervo;
$cllocalexemplar = new cl_localexemplar;
$cldb_depart = new cl_db_depart;
$clemprestimoacervo = new cl_emprestimoacervo;
$clexemplar = new cl_exemplar;
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);;
$linhas = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
}
if(isset($chavepesquisa)){
 $result = $clacervo->sql_record($clacervo->sql_query("","*","","bi06_seq = $chavepesquisa"));
 db_fieldsmemory($result,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto").""); ?>

<center>
<fieldset style="width:95%;margin-top:25px"><legend><b>Consulta de Acervo</b></legend>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td width="50%" align="center" valign="top">
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td colspan="2" align="center" bgcolor="#999999"><b>Dados do Acervo:</b></td>
    </tr>
    <tr>
     <td width="35%"><b>C�digo do Acervo</b></td>
     <td>&nbsp;<?=@$bi06_seq?></td>
    </tr>
    <tr>
     <td><b>Biblioteca:</b></td>
     <td>&nbsp;<?=@$bi17_codigo?> - <?=@$bi17_nome?></td>
    </tr>
    <tr>
     <td><b>Data de Registro</b></td>
     <td>&nbsp;<?=db_formatar(@$bi06_dataregistro,'d')?></td>
    </tr>
    <tr>
     <td><b>Ano de Edi��o</b></td>
     <td>&nbsp;<?=$bi06_anoedicao?></td>
    </tr>
    <tr>
     <td><b>Edi��o:</b></td>
     <td>&nbsp;<?=@$bi06_edicao?></td>
    </tr>
    <tr>
     <td><b>T�tulo:</b></td>
     <td>&nbsp;<?=@$bi06_titulo?></td>
    </tr>
    <tr>
     <td><b>Clas. CDD:</b></td>
     <td>&nbsp;<?=@$bi06_classcdd?></td>
    </tr>
    <tr>
     <td><b>ISBN:</b></td>
     <td>&nbsp;<?=@$bi06_isbn?></td>
    </tr>
    <tr>
     <td><b>Volume:</b></td>
     <td>&nbsp;<?=@$bi06_volume?></td>
    </tr>
    <tr>
     <td><b>Tipo Item:</b></td>
     <td>&nbsp;<?=@$bi05_nome?></td>
    </tr>
    <tr>
     <td><b>Editora:</b></td>
     <td>&nbsp;<?=@$bi02_nome?></td>
    </tr>
    <tr>
     <td><b>Clas. Liter�ria:</b></td>
     <td>&nbsp;<?=@$bi03_classificacao?></td>
    </tr>
    <tr>
     <td><b>Cole��o:</b></td>
     <td>&nbsp;<?=@$bi29_nome?></td>
    </tr>
   </table>
  </td>
  <td width="40%" align="center" valign="top">
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Autores:</b></td>
    </tr>
    <?
    //mostra autores do acervo
    $result = $clautoracervo->sql_record($clautoracervo->sql_query("","*","","bi21_acervo = ".@$bi06_seq.""));
    for($x=0;$x<$clautoracervo->numrows;$x++){
     db_fieldsmemory($result,$x);?>
     <tr>
     <td>&nbsp;&nbsp;<?=$bi01_nome?></td>
     </tr><?
    }
    ?>
   </table>
   <br>
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Exemplares:</b></td>
    </tr>
    <?
    //mostra exemplares do acervo
    $result = $clexemplar->sql_record($clexemplar->sql_query("","*","bi23_codigo","bi23_acervo = ".@$bi06_seq.""));
    $iLinhas = $clexemplar->numrows;
    for($x = 0; $x < $iLinhas; $x++) {

     db_fieldsmemory($result, $x);
     $result0 = $cllocalexemplar->sql_record($cllocalexemplar->sql_query("","bi20_sequencia,bi27_letra,bi09_nome",""," bi27_exemplar = $bi23_codigo"));
     if($cllocalexemplar->numrows > 0) {

      db_fieldsmemory($result0,0);
      $sequencia  = $bi23_situacao ==" N" ? "" : "&nbsp;&nbsp;Ordena��o: ";
      $sequencia .= $bi20_sequencia != "" ? $bi20_sequencia : "";
      $sequencia .= ($bi27_letra!=""?"-".$bi27_letra:"");
     }else{
      $sequencia = "";
     }
     ?>
     <tr>
     <td>
      &nbsp;&nbsp;<?=$bi23_codigo?> - <?=$bi23_codbarras?>
      &nbsp;&nbsp;Adquirido em <?=db_formatar($bi23_dataaquisicao,'d')?>
      <br>
      &nbsp;&nbsp;Situa��o: <?=$bi23_situacao=="S"?"ATIVO":"INATIVO"?> &nbsp;&nbsp; Aquisicao: <?=$bi04_forma?>
      <br>
      &nbsp;&nbsp;Empr�stimo:
      <?
      $sql1 = "SELECT bi23_codigo
               FROM exemplar
               WHERE not exists(select * from emprestimoacervo
                                where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                                and not exists(select *
                                               from devolucaoacervo
                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                              )
                                )
               AND bi23_codigo = $bi23_codigo
              ";
      $result1 = db_query($sql1);
      $linhas1 = pg_num_rows($result1);
      if($linhas1==0 || $bi23_situacao=='N'){
       echo "Indispon�vel";
      }else{
       ?>
       <a href="#" onclick="location.href='bib1_emprestimo001.php?bi23_codigo=<?=$bi23_codigo?>&bi06_titulo=<?=$bi06_titulo?>&assunto'" title="Realizar Empr�stimo">Dispon�vel</a>
       <?
      }
      ?>
      <br>
      &nbsp;&nbsp;Localiza��o:
      <?=$bi09_nome?><br><?=$sequencia?>
     </td>
     </tr><?
    }
    ?>
   </table>
   <br>
   <table bgcolor="#f3f3f3" valign="top" marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
     <td align="center" bgcolor="#999999"><b>Empr�stimos Abertos:</b></td>
    </tr>
    <?
    if(!isset($chavepesquisa)){
     $bi06_seq = 0;
    }
    $sql = "SELECT bi23_codigo,
                   bi06_titulo,
                   bi18_retirada,
                   bi18_devolucao
            FROM emprestimoacervo
             inner join emprestimo on bi18_codigo = bi19_emprestimo
             inner join carteira on bi16_codigo = bi18_carteira
             inner join leitor on bi10_codigo = bi16_leitor
             inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
             inner join biblioteca on bi17_codigo = bi07_biblioteca
             inner join exemplar on bi23_codigo = bi19_exemplar
             inner join acervo on bi06_seq = bi23_acervo
            WHERE bi06_seq = $bi06_seq
            AND bi07_biblioteca = $bi17_codigo
            AND not exists(select *
                           from devolucaoacervo
                           where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                          )
            ";
    $result = @db_query($sql);
    $linhas = @pg_num_rows($result);
    if($linhas>0){
     for($x=0;$x<$linhas;$x++){
      db_fieldsmemory($result,$x);
      ?>
      <tr>
       <td>
        &nbsp;&nbsp;Retirada:<?=db_formatar($bi18_retirada,'d')?> - Devolu��o:<?=db_formatar($bi18_devolucao,'d')?>
       </td>
      </tr>
      <?
     }
    }else{
     echo "<tr><td align='center'>Nenhum empr�stimo pendente.</td></tr>";
    }
    ?>
   </table>
  </td>
 </tr>
</table>
<br>
</fieldset>
<input type="button" name="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_acervo','func_acervo.php?funcao_js=parent.js_preenchepesquisa|bi06_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_acervo.hide();
 <?echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 ?>
}
</script>
<?
if(!isset($chavepesquisa)){
 echo "<script>js_pesquisa();</script>";
}
?>