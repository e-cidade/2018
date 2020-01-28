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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_devolucaoacervo_classe.php");

db_postmemory($HTTP_POST_VARS);
$cldevolucaoacervo = new cl_devolucaoacervo;

$depto  = db_getsession("DB_coddepto");
$sql    = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = pg_query($sql);;
$linhas = pg_num_rows($result);

if ($linhas != 0) {
  db_fieldsmemory($result,0);
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
        <fieldset style="width:90%"><legend><b>Consulta de Empréstimos em Aberto</b></legend>
          <?
          $hoje = date("Y-m-d");
          $sSql = "SELECT bi23_codigo, bi06_titulo, bi18_retirada, bi18_devolucao, bi18_carteira, bi16_leitor, ov02_nome
                     FROM emprestimoacervo
                          inner join emprestimo       on bi18_codigo               = bi19_emprestimo
                          inner join carteira         on bi16_codigo               = bi18_carteira
                          inner join leitorcategoria  on bi07_codigo               = bi16_leitorcategoria
                          inner join biblioteca       on bi17_codigo               = bi07_biblioteca
                          inner join leitor           on bi10_codigo               = bi16_leitor
                          left  join leitorcidadao    on leitorcidadao.bi28_leitor = leitor.bi10_codigo                   
                          left  join cidadao          on cidadao.ov02_sequencial   = leitorcidadao.bi28_cidadao_sequencial
                                                     and cidadao.ov02_seq          = leitorcidadao.bi28_cidadao_seq
                          inner join exemplar         on bi23_codigo               = bi19_exemplar
                          inner join acervo           on bi06_seq                  = bi23_acervo
                    WHERE not exists(
                                     select *
                                       from devolucaoacervo
                                      where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                    )
                      AND bi17_codigo = $bi17_codigo
                    ORDER BY bi18_devolucao";
          $result = $cldevolucaoacervo->sql_record($sSql);
          if ($cldevolucaoacervo->numrows > 0) {
            ?>
            <form name="form" method="post" action="bib1_devolucao001.php">
              <table border="1" width="100%" cellspacing="0" cellpading="0">
                <tr bgcolor="#999999">
                  <td><b>Código do Exemplar</b></td>
                  <td><b>Acervo</b></td>
                  <td><b>Retirada</b></td>
                  <td><b>Devolver até</b></td>
                  <td><b>Leitor</b></td>
                  <td>&nbsp;</td>
                </tr>
                <?
                for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {

                  db_fieldsmemory($result, $x);
                ?>
                  <tr bgcolor="#f3f3f3">
                    <td><?=$bi23_codigo?><input type="hidden" name="bi06_seq" id="bi06_seq" value="<?=$bi06_seq?>"></td>
                    <td><?=strlen($bi06_titulo) < 16 ? $bi06_titulo : substr($bi06_titulo, 0, 15)."..."?></td>
                    <td><?=db_formatar($bi18_retirada, 'd')?></td>
                    <td><?=db_formatar($bi18_devolucao, 'd')?></td>
                    <td>
                      <a href="bib1_leitor000.php?opcao=2&chavepesquisa=<?=$bi16_leitor?>" 
                         title="Ver dados do leitor"><?=strlen($ov02_nome) < 33 ? $ov02_nome:substr($ov02_nome, 0, 32)."..."?></a>
                    </td>
                    <td>
                      <input name="devolver" 
                             type="button" 
                             id="devolver" 
                             value="Devolução" 
                             onclick="location.href='bib1_devolucao001.php?bi18_carteira=<?=$bi18_carteira?>&ov02_nome=<?=$ov02_nome?>'">
                    </td>
                  </tr>
              <?}?>
              </table>
            </form>
        <?} else {?>
            <table border="0" width="100%">
              <tr>
                <td>Nenhum empréstimo em aberto.</td>
              </tr>
            </table>
        <?}?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>