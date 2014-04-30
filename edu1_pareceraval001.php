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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pareceraval_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_periodoavaliacao_classe.php");
include("classes/db_parecer_classe.php");
include("classes/db_parecerturma_classe.php");
include("classes/db_parecerlegenda_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$resultedu          = eduparametros(db_getsession("DB_coddepto"));
$clpareceraval      = new cl_pareceraval;
$clparecerlegenda   = new cl_parecerlegenda;
$cldiarioavaliacao  = new cl_diarioavaliacao;
$cldiario           = new cl_diario;
$clparecer          = new cl_parecer;
$clparecerturma     = new cl_parecerturma;
$clregencia         = new cl_regencia;
$clperiodoavaliacao = new cl_periodoavaliacao;
$hoje               = date("Y-m-d",db_getsession("DB_datausu"));
$db_opcao           = 2;
$db_botao           = true;
$sWhere             = "ed72_i_codigo = $ed93_i_diarioavaliacao";
$result00           = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                                   "ed95_i_regencia as codregatual",
                                                                                   "",
                                                                                   $sWhere
                                                                                  )
                                                    );
db_fieldsmemory($result00,0);
if (isset($alterar2)) {

  db_inicio_transacao();
  $sql_reg    = " SELECT ed95_i_regencia as codregatual ";
  $sql_reg   .= " FROM diarioavaliacao ";
  $sql_reg   .= "  inner join diario on ed95_i_codigo = ed72_i_diario ";
  $sql_reg   .= " WHERE ed72_i_codigo = $ed93_i_diarioavaliacao ";
  $result_reg = db_query($sql_reg);
  db_fieldsmemory($result_reg,0);

  $cldiarioavaliacao->ed72_c_aprovmin  = "S";
  $cldiarioavaliacao->ed72_i_numfaltas = $faltas;
  $cldiarioavaliacao->ed72_t_parecer   = $ed72_t_parecer;

  $cldiarioavaliacao->ed72_i_codigo    = $ed93_i_diarioavaliacao;
  $cldiarioavaliacao->alterar($ed93_i_diarioavaliacao);

  $sql_r    = " SELECT DISTINCT max(ed09_i_sequencia) ";
  $sql_r   .= " FROM diarioavaliacao ";
  $sql_r   .= "  inner join diario on diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario ";
  $sql_r   .= "  inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao ";
  $sql_r   .= "  inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
  $sql_r   .= " WHERE diario.ed95_i_regencia = $codregatual ";
  $sql_r   .= " AND ";
  $sql_r   .= " (diarioavaliacao.ed72_i_numfaltas is not null ";
  $sql_r   .= "  OR diarioavaliacao.ed72_i_valornota is not null ";
  $sql_r   .= "  OR diarioavaliacao.ed72_c_valorconceito != '' ";
  $sql_r   .= "  OR diarioavaliacao.ed72_t_parecer != '') ";
  $result_r = db_query($sql_r);
  $linhas   = pg_num_rows($result_r);
  db_fieldsmemory($result_r,0);

  if ($max == "") {

    $clregencia->ed59_c_ultatualiz  = "SI";
    $clregencia->ed59_i_codigo      = $codregatual;
    $clregencia->ed59_d_dataatualiz = $hoje;
    $clregencia->alterar($codregatual);

  } else {

    $result_p = $clperiodoavaliacao->sql_record($clperiodoavaliacao->sql_query_file("",
                                                                                    "ed09_c_abrev",
                                                                                    "",
                                                                                    "ed09_i_sequencia = $max"
                                                                                   )
                                               );
    db_fieldsmemory($result_p,0);
    $clregencia->ed59_c_ultatualiz  = $ed09_c_abrev;
    $clregencia->ed59_i_codigo      = $codregatual;
    $clregencia->ed59_d_dataatualiz = $hoje;
    $clregencia->alterar($codregatual);

  }

  $tam = sizeof(@$reg_outras);
  if ($tam > 0) {

    $regs = "";
    $sep  = "";

    for ($x = 0; $x < $tam; $x++) {

      $regs .= $sep.$reg_outras[$x];
      $sep   = ",";

    }

    $sCampos    = "ed72_i_numfaltas,ed95_i_regencia as codregmais,ed72_i_codigo as ed93_i_diarioavaliacao";
    $sWhere     = "ed95_i_regencia in($regs) AND ed95_i_aluno = $codaluno AND ed72_i_procavaliacao = $codperiodo";
    $result     = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                               $sCampos,
                                                                               "",
                                                                               $sWhere
                                                                              )
                                                );
    $linhas_dia = pg_num_rows($result);
    for ($t = 0; $t < $linhas_dia; $t++) {

      db_fieldsmemory($result,$t);
      $sCampos      = "ed93_i_codigo as cod_paraval_jatem,ed93_t_parecer as parecer_jatem";
      $sWhere       = "ed95_i_regencia = $codregatual AND ed95_i_aluno = $codaluno AND ed72_i_procavaliacao = $codperiodo";
      $result_jatem = $clpareceraval->sql_record($clpareceraval->sql_query("",
                                                                           $sCampos,
                                                                           "",
                                                                           $sWhere
                                                                          )
                                                );
      $linhas_jatem = $clpareceraval->numrows;
      $cldiarioavaliacao->ed72_c_aprovmin  = "S";
      $cldiarioavaliacao->ed72_i_numfaltas = $ed72_i_numfaltas;
      $cldiarioavaliacao->ed72_t_parecer   = $ed72_t_parecer;
      $cldiarioavaliacao->ed72_i_codigo    = $ed93_i_diarioavaliacao;
      $cldiarioavaliacao->alterar($ed93_i_diarioavaliacao);

      $sql_r    = " SELECT DISTINCT max(ed09_i_sequencia) ";
      $sql_r   .= " FROM diarioavaliacao ";
      $sql_r   .= "  inner join diario on diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario ";
      $sql_r   .= "  inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao ";
      $sql_r   .= "  inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
      $sql_r   .= " WHERE diario.ed95_i_regencia = $codregmais ";
      $sql_r   .= " AND ";
      $sql_r   .= " (diarioavaliacao.ed72_i_numfaltas is not null ";
      $sql_r   .= "  OR diarioavaliacao.ed72_i_valornota is not null ";
      $sql_r   .= "  OR diarioavaliacao.ed72_c_valorconceito != '' ";
      $sql_r   .= "  OR diarioavaliacao.ed72_t_parecer != '') ";
      $result_r = db_query($sql_r);
      $linhas   = pg_num_rows($result_r);
      db_fieldsmemory($result_r,0);
      if ($max == "") {

        $clregencia->ed59_c_ultatualiz  = "SI";
        $clregencia->ed59_i_codigo      = $codregmais;
        $clregencia->ed59_d_dataatualiz = $hoje;
        $clregencia->alterar($codregmais);

      } else {

        $result_p = $clperiodoavaliacao->sql_record($clperiodoavaliacao->sql_query_file("",
                                                                                        "ed09_c_abrev",
                                                                                        "",
                                                                                        "ed09_i_sequencia = $max"
                                                                                       )
                                                   );
        db_fieldsmemory($result_p,0);
        $clregencia->ed59_c_ultatualiz  = $ed09_c_abrev;
        $clregencia->ed59_i_codigo      = $codregmais;
        $clregencia->ed59_d_dataatualiz = $hoje;
        $clregencia->alterar($codregmais);

      }
    }
  }
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo {

  font-size: 11;
  color: #DEB887;
  background-color:#444444;
  font-weight: bold;

}

.cabec1 {

  font-size: 11;
  color: #000000;
  background-color:#999999;
  font-weight: bold;

}

.aluno {

  color: #000000;
  font-family : Tahoma;
  font-size: 9;

}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td class="titulo">
      Parecer para o aluno <?=$aluno?> no período <?=$periodo?>
     </td>
     <td align="right" class="titulo">
      <input type="button" id="voltar" name="voltar" value="Fechar" title="Fechar"
             onclick="parent.location.href = 'edu1_diarioavaliacao001.php?regencia=<?=$codregatual?>
                                              &ed41_i_codigo=<?=$codperiodo?>';
                      parent.db_iframe_parecer.hide();">
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <center>
      <?include("forms/db_frmpareceraval001.php");?>
      </center>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<?
if (isset($alterar2) && $ed72_t_parecer !="") {
  ?>
  <script>
    parent.db_iframe_parecer.hide();
    alert("Alteração efetuada com Sucesso");

    parent.document.form1.<?=$campo?>.value = "<?=str_replace("\r", "", str_replace("\n", "", addslashes($ed72_t_parecer)))?>";
  </script>
 <?

}
?>
<script>
  parent.db_iframe_parecer.liberarJanBTFechar('false');
  parent.db_iframe_parecer.liberarJanBTMinimizar('false');
  parent.db_iframe_parecer.liberarJanBTMaximizar('false');
</script>