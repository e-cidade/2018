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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_leitor_classe.php");
require_once ("classes/db_biblioteca_classe.php");

$clleitor     = new cl_leitor;
$clbiblioteca = new cl_biblioteca;

$depto          = db_getsession("DB_coddepto");
$sSqlBiblioteca = $clbiblioteca->sql_query("", "bi17_codigo,bi17_nome", "", " bi17_coddepto = $depto");
$result         = $clbiblioteca->sql_record($sSqlBiblioteca);

if ($clbiblioteca->numrows != 0) {
  db_fieldsmemory($result, 0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
if (isset($valor)) {
  
  if ($data_ini != "--" && $data_fim != "--") {
    
    $where  = " bi18_retirada between '$data_ini' and '$data_fim' and ";
    $titulo = " - Período: ".db_formatar($data_ini,'d')." até ".db_formatar($data_fim,'d');
  } else {
    
    $where  = "";
    $titulo = " - Período: Não Informado";
  }
  $sql = "SELECT bi16_codigo, ov02_sequencial, ov02_nome, ov02_cnpjcpf, bi07_nome, count(*)
            FROM leitor
                 left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo                   
                 left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                            and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
                 inner join carteira         on bi16_leitor               = bi10_codigo
                 inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
                 inner join biblioteca       on bi17_codigo               = bi07_biblioteca
                 inner join emprestimo       on bi18_carteira             = bi16_codigo
                 inner join emprestimoacervo on bi19_emprestimo           = bi18_codigo
          WHERE $where bi16_codigo = $valor
            AND bi17_codigo = $bi17_codigo
          GROUP BY bi16_codigo, ov02_sequencial, ov02_nome, ov02_cnpjcpf, bi07_nome";
  $result = pg_exec($sql);
  $linhas = pg_numrows($result);
  ?>
  <table width="100%" border="0" cellspacing="1" cellpading="3" >
    <form method="POST" name="form2">
      <tr>
        <td colspan="2">
          <b>Consulta de Empréstimos por Leitor <?=$titulo?></b>
        </td>
        <td colspan="3" align="right">
         <?if ($todos == "false" && $linhas != 0) {?>
             <input type="checkbox" name="todos" value="true" onclick="js_vertodos()">Ver todos empréstimos
         <?} else if ($todos == "true" && $linhas != 0) {?>
             <input type="checkbox" name="todos" value="false" checked onclick="js_vertodos()">Ocultar empréstimos
         <?}?>
        </td>
      </tr>
      <tr bgcolor="#999999">
        <td align="center"><b>Código</b></td>
        <td><b>Leitor</b></td>
        <td align="center"><b>CPF/CNPJ</b></td>
        <td align="center"><b>Categoria</b></td>
        <td align="center"><b>Empréstimos</b></td>
      </tr>
      <?
        $cor1 = "#ababab";
        $cor2 = "#f3f3f3";
        $cor  = $cor1;
        
        if ($linhas != 0) {

          for ($i = 0 ; $i < $linhas; $i++) {
          
            db_fieldsmemory($result,$i);
            if ($cor == $cor1) {
              $cor = $cor2;
            } else {
              $cor=$cor1;
            }
      ?>
            <tr bgcolor="<?=$cor?>">
              <td align="center"><?=$bi16_codigo?></td>
              <td><?=$ov02_nome?></td>
              <td align="center"><?=$ov02_cnpjcpf?></td>
              <td align="center"><?=$bi07_nome?></td>
              <td align="center"><?=$count?></td>
            </tr>
            <?if ($todos == "true") {
              
                if ($data_ini != "--" && $data_fim != "--") {
                  $where2 = " and bi18_retirada between '$data_ini' and '$data_fim' ";
                } else {
                  $where2 = "";
                }
                $sql_emp = "SELECT bi23_codigo, bi18_retirada, bi18_devolucao, bi21_entrega, bi06_titulo
                              FROM emprestimoacervo
                                   left  join devolucaoacervo on bi21_codigo = bi19_codigo
                                   inner join emprestimo      on bi18_codigo = bi19_emprestimo
                                   inner join exemplar        on bi23_codigo = bi19_exemplar
                                   inner join acervo          on bi06_seq    = bi23_acervo
                             WHERE bi18_carteira = $bi16_codigo
                             $where2
                             ORDER BY bi18_retirada desc,bi21_entrega desc";
                $result_emp = pg_exec($sql_emp);
                $linhas_emp = pg_numrows($result_emp);
            ?>
            <tr bgcolor="<?=$cor?>">
              <td></td>
              <td align="center" colspan="5">
                <table width="100%" cellspacing="0" cellpading="3">
                  <tr>
                    <td align="center"><b>Código Exemplar</b></td>
                    <td><b>Acervo</b></td>
                    <td align="center"><b>Retirada</b></td>
                    <td align="center"><b>Devolver em:</b></td>
                    <td align="center"><b>Devolvido em:</b></td>
                  </tr>
                  <tr><td colspan="5" height="1" bgcolor="#CCCCCC"></td></tr>
                  <?
                  $cor3  = "#DEB887";
                  $cor4  = "#FFE0C1";
                  $cor_e = $cor3;
                  
                  for ($t = 0; $t < $linhas_emp; $t++) {

                    db_fieldsmemory($result_emp, $t);
                    if ($cor_e == $cor3) {
                      $cor_e=$cor4;
                    } else {
                      $cor_e=$cor3;
                    }
                  ?>
                    <tr bgcolor="<?=$cor_e?>">
                      <td align="center"><?=$bi23_codigo?></td>
                      <td><?=$bi06_titulo?></td>
                      <td align="center"><?=db_formatar($bi18_retirada,'d')?></td>
                      <td align="center"><?=db_formatar($bi18_devolucao,'d')?></td>
                      <td align="center">
                        <b>
                        <?if ($bi21_entrega == "") {?>
                            <!-- No Location a variavel bi18_leitor foi trocada por bi18_carteira por que o codigo utilizado na rotina de devolucao é o codigo da carteira guilherme 19/06-->
                            <a title="Devolver Empréstimo" 
                               href="javascript:parent.location.href='bib1_devolucao001.php?bi18_carteira=<?=$bi16_codigo?>
                                                                                           &z01_nome=<?=$z01_nome?>'">
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
      <td colspan="6" align="center">Nenhum empréstimo para este período ou para este leitor.</td>
    </tr>
    </table>
   <?}
  } else {
    echo "<div align='center'><br>Digite o período, escolha o leitor e clique em pesquisar</div>";
  }
?>
<br><br>
<script>
function js_vertodos() {
  
  <?$pagina = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?valor=".@$valor."&data_ini=".@$data_ini."&data_fim=".@$data_fim;?>
  location.href = "<?=$pagina?>&todos="+document.form2.todos.value;
}
</script>
</body>
</html>