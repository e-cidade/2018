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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acervo_classe.php");
require_once("classes/db_biblioteca_classe.php");

$depto        = db_getsession("DB_coddepto");
$clbiblioteca = new cl_biblioteca;

$sSqlBiblioteca = $clbiblioteca->sql_query("","bi17_codigo,bi17_nome",""," bi17_coddepto = $depto");
$result         = $clbiblioteca->sql_record($sSqlBiblioteca);

if ($clbiblioteca->numrows != 0) {
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?php
$clacervo = new cl_acervo;

if (isset($valor)) {

  if ($data_ini != "--" && $data_fim != "--") {

    $where  = " bi18_retirada between '$data_ini' and '$data_fim' and ";
    $titulo = " - Período: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d');
  } else {

    $where  = "";
    $titulo = " - Período: Não Informado";
  }

  $sql  = "SELECT bi06_seq, bi06_titulo, bi06_edicao, bi06_volume, bi06_tipoitem, bi05_nome, bi29_nome, count(*) ";
  $sql .=  "  FROM acervo                                                                                        ";
  $sql .=  "       inner join exemplar         on bi23_acervo = bi06_seq                                         ";
  $sql .=  "       inner join emprestimoacervo on bi19_exemplar = bi23_codigo                                    ";
  $sql .=  "       inner join emprestimo       on bi18_codigo = bi19_emprestimo                                  ";
  $sql .=  "       inner join tipoitem         on bi05_codigo = bi06_tipoitem                                    ";
  $sql .=  "        left join colecaoacervo    on bi06_colecaoacervo = bi29_sequencial                           ";
  $sql .=  " WHERE $where exists(select * from emprestimoacervo where bi19_exemplar = bi23_codigo)               ";
  $sql .=  "   AND bi06_seq = $valor                                                                             ";
  $sql .=  " GROUP BY bi06_seq, bi06_titulo, bi06_edicao, bi06_volume, bi06_tipoitem, bi05_nome, bi29_nome       ";

  $result = db_query($sql);
  $linhas = pg_numrows($result);
?>
  <table width="100%" border="0" cellspacing="1" cellpading="3" >
    <form method="POST" name="form2">
      <tr>
        <td colspan="4">
          <b>Consulta de Empréstimos por Acervo <?=$titulo?></b>
        </td>
        <td colspan="4" align="right">
          <?
            if ($todos == "false" && $linhas != 0) {
          ?>
              <input type="checkbox" name="todos" value="true" onclick="js_vertodos()">Ver todos empréstimos
          <?
            } else if ($todos == "true" && $linhas != 0) {
          ?>
              <input type="checkbox" name="todos" value="false" onclick="js_vertodos()">Ocultar empréstimos
          <?}?>
        </td>
      </tr>
      <tr bgcolor="#999999">
        <td align="center" width="5%"><b>Código Acervo</b></td>
        <td width="35%"><b>Titulo</b></td>
        <td width="30%"><b>Coleção</b></td>
        <td align="center" width="5%"><b>Edição</b></td>
        <td align="center" width="5%"><b>Volume</b></td>
        <td align="center" width="15%"><b>Tipo</b></td>
        <td align="center" width="10%"><b>Empréstimos</b></td>
      </tr>
      <?
      $cor1 = "#ababab";
      $cor2 = "#f3f3f3";
      $cor  = $cor1;

      if ($linhas != 0) {

        for ($i = 0; $i < $linhas; $i++) {

          db_fieldsmemory($result,$i);
          if ($cor == $cor1) {
            $cor=$cor2;
          } else {
            $cor=$cor1;
          }
        ?>
        <tr bgcolor="<?=$cor?>">
          <td align="center"><?=$bi06_seq?></td>
          <td><?=$bi06_titulo?></td>
          <td><?=$bi29_nome?></td>
          <td align="center"><?=$bi06_edicao?></td>
          <td align="center"><?=$bi06_volume?></td>
          <td align="center"><?=$bi05_nome?></td>
          <td align="center"><?=$count?></td>
        </tr>

        <?php
          if ($todos == "true") {

            $sql_emp = "SELECT bi23_codigo, bi18_retirada, bi18_devolucao, bi21_entrega, ov02_nome, bi16_codigo
                          FROM emprestimoacervo
                               left  join devolucaoacervo  on bi21_codigo               = bi19_codigo
                               inner join emprestimo       on bi18_codigo               = bi19_emprestimo
                               inner join carteira         on bi16_codigo               = bi18_carteira
                               inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
                               inner join biblioteca       on bi17_codigo               = bi07_biblioteca
                               inner join leitor           on bi10_codigo               = bi16_leitor
                               left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo
                               left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                                          and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
                               inner join exemplar on bi23_codigo = bi19_exemplar
                               inner join acervo on bi06_seq = bi23_acervo
                         WHERE $where bi06_seq = $bi06_seq
                           AND bi17_codigo = $bi17_codigo
                         ORDER BY bi18_retirada desc, bi21_entrega desc";
            $result_emp = db_query($sql_emp);
            $linhas_emp = pg_numrows($result_emp);?>
            <tr bgcolor="<?=$cor?>">
              <td></td>
              <td align="center" colspan="5">
                <table width="100%" cellspacing="0" cellpading="3">
                  <tr>
                    <td align="center"><b>Código Exemplar</b></td>
                    <td><b>Leitor</b></td>
                    <td align="center"><b>Retirada</b></td>
                    <td align="center"><b>Devolução</b></td>
                    <td align="center"><b>Devolvido em:</b></td>
                  </tr>
                  <tr><td colspan="5" height="1" bgcolor="#CCCCCC"></td></tr>
                  <?
                  $cor3  = "#DEB887";
                  $cor4  = "#FFE0C1";
                  $cor_e = $cor3;

                  for ($t = 0; $t < $linhas_emp; $t++) {

                    db_fieldsmemory($result_emp,$t);
                    if ($cor_e == $cor3) {
                      $cor_e=$cor4;
                    } else {
                      $cor_e=$cor3;
                    }
                  ?>
                    <tr bgcolor="<?=$cor_e?>">
                      <td align="center"><?=$bi23_codigo?></td>
                      <td><?=$bi16_codigo?> - <?=$ov02_nome?></td>
                      <td align="center"><?=db_formatar($bi18_retirada,'d')?></td>
                      <td align="center"><?=db_formatar($bi18_devolucao,'d')?></td>
                      <td align="center">
                        <b>
                        <?if ($bi21_entrega == "") {?>
                            <a title="Devolver Empréstimo"
                               href="javascript:parent.location.href='bib1_devolucao001.php?bi18_leitor=<?=$bi16_codigo?>&ov02_nome=<?=$ov02_nome?>'">
                               <font color='red'>Não devolvido</font>
                            </a>
                        <?} else {?>
                            <font color='green'><?=db_formatar($bi21_entrega,'d')?></font>
                        <?}?>
                        </b>
                      </td>
                   </tr>
                  <?}?>
                </table>
              </td>
            </tr>
        <?}?>

    <?}?>
    <table>
    </form>
  <?} else {?>
     <tr>
       <td colspan="6" align="center">Nenhum empréstimo para este acervo.</td>
     </tr>
  </table>
  <?}
} else {
  echo "<div align='center'><br>Escolha o acervo e clique em pesquisar</div>";
}
?>
<br><br>
<script>
function js_vertodos(){
 <?$pagina = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?valor=".@$valor;?>
 location.href = "<?=$pagina?>&data_ini=<?=$data_ini?>&data_fim=<?=$data_fim?>&todos="+document.form2.todos.value;
}
</script>
</body>
</html>