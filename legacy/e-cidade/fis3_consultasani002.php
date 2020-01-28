<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_saniatividade_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsanitario     = new cl_sanitario;
$clsaniatividade = new cl_saniatividade;
$clrotulo        = new rotulocampo;
$clsanitario->rotulo->label();
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$campos          = "sanitario.*";
$db_opcao        = 3;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
<div class="container">
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Alvará Sanitário</legend>
    <?php
    if(isset($y80_codsani) && (trim($y80_codsani)!="") ){

      $result = $clsanitario->sql_record($clsanitario->sql_query("","*","y80_codsani"," y80_codsani = $y80_codsani"));
      db_fieldsmemory($result,0);
    ?>
    <table>
      <tr>
        <td nowrap title="<?=@$Ty80_codsani?>">
          <?=@$Ly80_codsani?>
        </td>
        <td>
          <?php
          db_input('y80_codsani',10,$Iy80_codsani,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_codsani?>">
          <?=@$Ly80_numbloco?>
        </td>
        <td>
          <?php
          db_input('y80_numbloco', 10, $Iy80_codsani, true, 'text', 3, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tz01_nome?>">
          <?php
          db_ancora(@$Lz01_nome,"js_JanelaAutomatica('cgm',$y80_numcgm);",2);
          ?>
        </td>
        <td>
          <?php
          db_input('y80_numcgm',10,$Iy80_numcgm,true,'text',$db_opcao,"");
          db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj14_nome?>">
           <?php
           db_ancora(@$Lj14_nome,"js_pesquisaruas(true);",3);
           ?>
        </td>
        <td>
          <?php
          db_input('y80_codrua',10,$Iy80_codrua,true,'hidden',$db_opcao,"");
          db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tj13_descr?>">
          <?php
          db_ancora(@$Lj13_descr,"js_pesquisabairro(true);",3);
          ?>
        </td>
        <td>
          <?php
          db_input('y80_codbairro',10,$Iy80_codbairro,true,'hidden',$db_opcao,"");
          db_input('j13_descr',40,$Ij13_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_numero?>">
          <?=@$Ly80_numero?>
        </td>
        <td>
    <table>
    <tr>
        <td>
          <?php
          db_input('y80_numero',10,$Iy80_numero,true,'text',3,"");
          ?>
        </td>
        <td nowrap title="<?=@$Ty80_compl?>">
           <?=@$Ly80_compl?>
        </td>
        <td>
          <?php
          db_input('y80_compl',20,$Iy80_compl,true,'text',3,"");
          ?>
        </td>
    </tr>
    </table>
    </td>
    </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_data?>">
           <?=@$Ly80_data?>
        </td>
        <td>
          <?php
          db_inputdata('y80_data',@$y80_data_dia,@$y80_data_mes,@$y80_data_ano,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_obs?>">
           <?=@$Ly80_obs?>
        </td>
        <td>
          <?php
          db_textarea('y80_obs',3,50,$Iy80_obs,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
    <tr>
        <td nowrap title="<?=@$Ty80_texto?>">
           <?=@$Ly80_texto?>
        </td>
        <td>
          <?php
          db_textarea('y80_texto',3,50,$Iy80_texto,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ty80_area?>">
           <?=@$Ly80_area?>
        </td>
        <td>
          <?php
          db_input('y80_area',10,$Iy80_area,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      </table>
    </script>
<?php
  $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y80_codsani"));
  if($clsaniatividade->numrows > 0){
    $sql = $clsaniatividade->sql_query("",""," y83_seq,y83_dtini,y83_dtfim,y83_ativ,q03_descr",""," y83_codsani = $y80_codsani");
    db_lovrot($sql,5,"","","");
  }else{
    echo "<strong>Nenhuma Atividade Cadastrada!</strong><br/>";
  }
  /*
   * valida se há atividades em vigor para liberar
   * a impressão do Alvará
   */
  $dDataAtual           = date("Y-m-d", db_getsession('DB_datausu'));

  $oDaoSaniAtividade    = db_utils::getDao("saniatividade");
  $sWhereSaniAtividade  = "   (y83_codsani = {$y80_codsani}     ";
  $sWhereSaniAtividade .= "     AND y83_dtfim     is null       ";
  $sWhereSaniAtividade .= "   ) OR (                            ";
  $sWhereSaniAtividade .= "     y83_codsani = {$y80_codsani}    ";
  $sWhereSaniAtividade .= "     AND y83_dtfim is not null       ";
  $sWhereSaniAtividade .= "     AND y83_dtfim > '{$dDataAtual}' ";
  $sWhereSaniAtividade .= "   )                                 ";

  $sSqlSaniAtividade   = $oDaoSaniAtividade->sql_query_file(null, null, "*", null, $sWhereSaniAtividade);
  $rsSaniAtividade     = $oDaoSaniAtividade->sql_record($sSqlSaniAtividade);

  /*
   * trata o resultado da validação e trata a propriedade
   * 'disabled' do botão de impressao
   */
  $btnImprimeStyle = "";
  if ($oDaoSaniAtividade->numrows == 0) {
	 $btnImprimeStyle = "disabled = ''";
  }
?>
  </fieldset>
  <input type="button" name="Fechar" value="Fechar" onclick="parent.db_iframe_consulta.hide();" >
  <input type="button" name="Imprimir" value="Imprimir" onclick="js_imprime('<?=$y80_codsani?>');" >
  <input type="button" name="Impalvara" <?=$btnImprimeStyle ?> value="Imprimir Alvará" onclick="js_imprimealvara('<?=$y80_codsani?>');" >
</form>
</div>
</body>
</html>
<script type="text/javascript">
function js_imprime(chave){
    jan = window.open('fis3_relatoriosani.php?y80_codsani='+chave,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
function js_imprimealvara(chave){
    jan = window.open('fis3_imprimealvara.php?y80_codsani='+chave,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>
<?php
  exit;
} elseif (isset($y80_numcgm) && (trim($y80_numcgm) != "")) {
  $sql = $clsanitario->sql_query("",$campos,"y80_numcgm"," y80_numcgm = $y80_numcgm");
} elseif (isset($ativ) && (trim($ativ)!="")) {
  $sql = $clsanitario->sql_query("",$campos,"y80_codsani",'y80_codsani in ('.$clsaniatividade->sql_query("","","y83_codsani",""," y83_ativ = $ativ").')');
}elseif((isset($dataini) && trim($dataini) != "--") && (isset($datafim) && trim($datafim) != "--")){
  $sql = $clsanitario->sql_query("",$campos,"y80_codsani"," y80_data >= '$dataini' and y80_data <= '$datafim' ");
}else{
  $sql = $clsanitario->sql_query("",$campos,"y80_codsani","");
}
?>
<table height="100%" border="0"  align="center" cellspacing="0">
  <tr>
    <td align="center" valign="top">
      <?php
        db_lovrot($sql,15,"()","",$funcao_js);
      ?>
     </td>
   </tr>
  <tr>
    <td align="center" valign="top">
      <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" >
    </td>
  </tr>
</table>
</body>
</html>