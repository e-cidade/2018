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
require_once ("classes/db_leitor_classe.php");
require_once ("classes/db_devolucaoacervo_classe.php");
require_once ("classes/db_cidadao_classe.php");

$clleitor          = new cl_leitor;
$cldevolucaoacervo = new cl_devolucaoacervo;
$clcidadao         = new cl_cidadao;
$clrotulo          = new rotulocampo;

$clcidadao->rotulo->label();
$cldevolucaoacervo->rotulo->label();
$clrotulo->label("bi18_carteira");

$opcao = 1;
$depto = db_getsession("DB_coddepto");

$sql    = "SELECT bi17_codigo, bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.getElementById('bi18_carteira').focus()" >
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<br>
<fieldset style="width:95%"><legend><b>Consulta de Leitor</b></legend>
<table width="80%" border="0" align="center">
 <tr>
  <td>
   <fieldset width="50%"><legend><b>Dados do Leitor:</b></legend>
   <form name="form1" method="post" action="">
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Tbi18_carteira?>">
      <?db_ancora(@$Lbi18_carteira,"js_pesquisabi18_carteira(true);",$opcao);?>
     </td>
     <td>
      <?db_input('bi18_carteira', 10, $Ibi18_carteira, true, 'text', $opcao, " onchange='js_pesquisabi18_carteira(false);'")?>
      <?db_input('ov02_nome', 50, @$ov02_nome, true, 'text', 3, "")?>
      <input name="proximo" type="submit" id="proximo" value="Próximo" style="visibility:hidden;">
     </td>
    </tr>
   </table>
   </form>
   </fieldset>
  </td>
 </tr>
</table>
<br>
<?if (!empty($bi18_carteira)) {
  
  $result = $cldevolucaoacervo->sql_record("select * 
                                              from emprestimoacervo
                                                   inner join emprestimo      on bi18_codigo = bi19_emprestimo
                                                   inner join carteira        on bi16_codigo = bi18_carteira
                                                   inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
                                                   inner join biblioteca      on bi17_codigo = bi07_biblioteca
                                                   inner join leitor          on bi10_codigo = bi16_leitor
                                                   inner join exemplar        on bi23_codigo = bi19_exemplar
                                                   inner join acervo          on bi06_seq    = bi23_acervo
                                             where bi18_carteira = $bi18_carteira
                                               and bi17_codigo = $bi17_codigo
                                               and not exists(
                                                              select *
                                                                from devolucaoacervo
                                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                                             )");
  if ($cldevolucaoacervo->numrows == 0) {
    ?>
    <br><br>
    <center>
      <fieldset width="100%"><legend><b>Acervos Para Devolução:</b></legend>
        <br>Nenhum empréstimo para o Leitor selecionado (<?=@$ov02_nome?>).<br><br>
      </fieldset>
    </center>
    <?
  } else {
    ?>
    <table border="0" width="95%" align="center">
      <tr>
        <td colspan="2">
          <fieldset width="100%"><legend><b>Empréstimos deste Leitor:</b></legend>
            <table border="0" width="100%">
              <form name="form" method="post" action="bib1_devolucao001.php">
                <tr bgcolor="#999999">
                  <td><b>Código Exemplar</b></td>
                  <td><b>Nome</b></td>
                  <td><b>Emprestado</b></td>
                  <td><b>Devolver até</b></td>
                  <td><b>Situação</b></td>
                </tr>
                <?
                $cor1 = "#f3f3f3";
                $cor2 = "#ababab";
                $cor  = "";
                
                for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {

                  db_fieldsmemory($result,$x);
                  if ($cor == $cor1) {
                    $cor = $cor2;
                  } else {
                    $cor = $cor1;
                  }
                  //cor da situacao
                  if (str_replace("-", "", $bi18_devolucao) - date("Ymd") < 0) {

                    $situacao = "red";
                    $texto    = "ATRASADO";
                  } else {

                    $situacao = "green";
                    $texto    = "NORMAL";
                  }
                  ?>
                  <tr bgcolor="<?=$cor?>">
                    <td><?=$bi23_codigo?></td>
                    <td><?=$bi06_titulo?></td>
                    <td><?=db_formatar($bi18_retirada, 'd')?></td>
                    <td><?=db_formatar($bi18_devolucao, 'd')?></td>
                    <td align="center" style="color:#FFFFFF;" bgcolor="<?=@$situacao?>"><?=@$texto?></td>
                  </tr>
                  <?
                }
                ?>
                <tr height="30">
                  <td colspan="5">
                    <input type="hidden" name="bi18_carteira" id="bi18_carteira" value="<?=$bi18_carteira?>">
                    <input type="hidden" name="ov02_nome" id="ov02_nome" value="<?=$ov02_nome?>">
                    <input name="devolver" type="submit" id="devolver" value="Devolver Empréstimos">
                  </td>
                </tr>
              </form>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
 <?}?>
<?}?>
</fieldset>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisabi18_carteira(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome',
                        'Pesquisa',
                        true
                       );
  } else {
    
    if (document.form1.bi18_carteira.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_leitor',
                          'func_leitorproc.php?pesquisa_chave='+document.form1.bi18_carteira.value
                                            +'&funcao_js=parent.js_mostraleitor',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.ov02_nome.value = '';
    }
  }
}

function js_mostraleitor(chave, erro) {
  
  document.form1.ov02_nome.value = chave;
  document.form1.proximo.click();
  if (erro == true) {
    
    document.form1.bi18_carteira.focus();
    document.form1.bi18_carteira.value = '';
  }
}

function js_mostraleitor1(chave1, chave2) {
  
  document.form1.bi18_carteira.value = chave1;
  document.form1.ov02_nome.value     = chave2;
  db_iframe_leitor.hide();
  document.form1.proximo.click();
}
</script>