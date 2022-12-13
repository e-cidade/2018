<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_acervo_classe.php"));
require_once(modification("classes/db_biblioteca_classe.php"));

$iDepto = db_getsession("DB_coddepto");
$clbiblioteca = new cl_biblioteca;
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = {$iDepto}"));
if($clbiblioteca->numrows!=0){
 db_fieldsmemory($result,0);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<?php

if (isset($valor)) {

  $sSqlAcervoAutor  = "select distinct bi06_seq                                                    ";
  $sSqlAcervoAutor .= "       ,bi06_titulo                                                 ";
  $sSqlAcervoAutor .= "       ,bi06_subtitulo                                              ";
  $sSqlAcervoAutor .= "       ,bi06_edicao                                                 ";
  $sSqlAcervoAutor .= "       ,bi06_volume                                                 ";
  $sSqlAcervoAutor .= "       ,bi06_tipoitem                                               ";
  $sSqlAcervoAutor .= "       ,bi05_nome                                                   ";
  $sSqlAcervoAutor .= "       ,trim(bi17_nome) as biblioteca                               ";
  $sSqlAcervoAutor .= "       ,bi29_nome                                                    ";
  $sSqlAcervoAutor .= "       ,(SELECT count(*) FROM exemplar WHERE bi23_acervo = bi06_seq) as exemplares ";
  $sSqlAcervoAutor .= "  from acervo                                                       ";
  $sSqlAcervoAutor .= "   inner join autoracervo   on bi21_acervo = bi06_seq               ";
  $sSqlAcervoAutor .= "   inner join tipoitem      on bi05_codigo = bi06_tipoitem          ";
  $sSqlAcervoAutor .= "   inner join biblioteca    on bi17_codigo = bi06_biblioteca          ";
  $sSqlAcervoAutor .= "    left join colecaoacervo on bi06_colecaoacervo = bi29_sequencial ";
  $sSqlAcervoAutor .= "   where bi21_autor      = {$valor} and                             ";
  $sSqlAcervoAutor .= "         bi06_biblioteca = {$bi17_codigo}                           ";
  $sSqlAcervoAutor .= "   order by bi06_titulo, bi06_subtitulo                           ";

  $rsAcervoAutor = db_query($sSqlAcervoAutor);
  $iLinhas       = pg_numrows($rsAcervoAutor);

 ?>

  <table width="100%" border="0" cellspacing="1" cellpading="3" >
  <form method="POST" name="form2">
  <tr>
   <td colspan="8" align="right">
    <?if($todos=="false" and $iLinhas!=0){?>
     <input type="checkbox" name="todos" value="true" onclick="js_vertodos()">Ver exemplares
    <?}elseif($todos=="true" and $iLinhas!=0){?>
     <input type="checkbox" name="todos" value="false" onclick="js_vertodos()">Ocultar exemplares
    <?}?>
   </td>
  </tr>
  <tr bgcolor="#999999">
   <td align="center" width="5%"><b>C�digo Acervo</b></td>
   <td width="20%"><b>T�tulo</b></td>
   <td width="20%"><b>Subt�tulo</b></td>
   <td width="10%"><b>Cole��o</b></td>
   <td align="center" width="15%"><b>Biblioteca</b></td>
   <td align="center" width="4%"><b>Edi��o</b></td>
   <td align="center" width="4%"><b>Volume</b></td>
   <td align="center" width="10%"><b>Tipo</b></td>
   <td align="center" width="3%"><b>Exemplares</b></td>
  </tr>
  <?php
   $cor1 = "#cbcbcb";
   $cor2 = "#f3f3f3";
   $cor=$cor1;
   if($iLinhas!=0){
    for($i=0;$i<$iLinhas;$i++){
     db_fieldsmemory($rsAcervoAutor,$i);
     if($cor==$cor1){
      $cor=$cor2;
     }else{
      $cor=$cor1;
     }
     ?>
     <tr><td colspan="9" height="1" bgcolor="#000000"></td></tr>
     <tr bgcolor="<?=$cor?>">
      <td align="center"><?=$bi06_seq?></td>
      <td><b><?=$bi06_titulo?></b></td>
      <td><b><?=$bi06_subtitulo?></b></td>
      <td><b><?=$bi29_nome?></b></td>
      <td><b><?=$biblioteca?></b></td>
      <td align="center"><?=$bi06_edicao?></td>
      <td align="center"><?=$bi06_volume?></td>
      <td><b><?=$bi05_nome?></b></td>
      <td align="center"><?=$exemplares?></td>

     </tr>

     <?php
       if($todos=="true"){
         $sql_emp = "SELECT *
                     FROM exemplar
                      inner join aquisicao on bi04_codigo = bi23_aquisicao
                     WHERE bi23_acervo = $bi06_seq
                     ORDER BY bi23_codigo";
         $rsAcervoAutor_emp = db_query($sql_emp);
         $iLinhas_emp = pg_numrows($rsAcervoAutor_emp);
     ?>
       <tr bgcolor="<?=$cor?>">
        <td></td>
        <td align="center" colspan="5">
         <table width="100%" cellspacing="0" cellpading="3">
          <tr>
           <td align="center" width="20%"><b>C�digo Exemplar</b></td>
           <td width="15%"><b>C�d. Barras</b></td>
           <td align="center" width="25%"><b>Aquisi��o</b></td>
           <td align="center" width="15%"><b>Data Aquisi��o</b></td>
           <td align="center" width="15%"><b>Situa��o</b></td>
           <td align="center" width="10%"><b>Empr�stimo</b></td>
          </tr>
          <tr><td colspan="6" height="1" bgcolor="#CCCCCC"></td></tr>
          <?php
          if($iLinhas_emp>0){
           $cor3 = "#DEB887";
           $cor4 = "#FFE0C1";
           $cor_e =$cor3;
           for($t=0;$t<$iLinhas_emp;$t++){
            db_fieldsmemory($rsAcervoAutor_emp,$t);
             if($cor_e==$cor3){
              $cor_e=$cor4;
             }else{
              $cor_e=$cor3;
             }?>
            <tr bgcolor="<?=$cor_e?>">
             <td align="center"><?=$bi23_codigo?></td>
             <td><?=$bi23_codbarras?></td>
             <td align="center"><?=$bi04_forma?></td>
             <td align="center"><?=db_formatar($bi23_dataaquisicao,'d')?></td>
             <td align="center"><?=$bi23_situacao=="N"?"INATIVO":"ATIVO"?></td>
             <td align="center">
              <?php
              $sql1 = "SELECT bi23_codigo
                       FROM exemplar
                       WHERE not exists(select * from emprestimoacervo
                                        where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                                        and not exists(select *
                                                       from devolucaoacervo
                                                       where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                                      )
                                        )
                       AND bi23_codigo = {$bi23_codigo}
                      ";
              $rsAcervoAutor1 = db_query($sql1);
              $iLinhas1 = pg_num_rows($rsAcervoAutor1);
              if($iLinhas1==0 || $bi23_situacao=='N'){
               echo "Indispon�vel";
              }else{
               ?>
               <a href="#" onclick="parent.location.href='bib1_emprestimo001.php?bi23_codigo=<?=$bi23_codigo?>&bi06_titulo=<?=$bi06_titulo?>&assunto'" title="Realizar Empr�stimo">Dispon�vel</a>
               <?
              }
              ?>
              <b></b>
             </td>
            </tr>
           <?
           }
          }else{
             echo "<tr><td align='center' colspan='6'>Nenhum exemplar cadastrado para este acervo.</td></tr>";
          }
          ?>
         </table>
        </td>
       </tr>
     <?}?>

    <?}?>
    <table>
    </form>
   <?}else{?>
    <tr>
     <td colspan="6" align="center">Nenhum acervo para este autor.</td>
    </tr>
    </table>
   <?}
  }else{
     echo "<div align='center'><br>Escolha o autor e clique em pesquisar</div>";
  }
?>
<br><br>
<script>
function js_vertodos(){
 <?$pagina = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?valor=".@$valor;?>
 location.href = "<?=$pagina?>&todos="+document.form2.todos.value;
}
</script>
</body>
</html>
