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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oDaoHistMpsDisc  = db_utils::getDao("histmpsdisc");
$oDaoHistoricoMps = db_utils::getDao("historicomps");
$oDaoAlunoCurso   = db_utils::getDao("alunocurso");
$oDaoDisciplina   = db_utils::getDao("disciplina");

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

if (isset($registrodisc)) {

  $array_registro = explode("|",$registrodisc);

  for ($y = 0; $y < count($array_registro); $y++) {

    $array_campos = explode(";",$array_registro[$y]);

    if (is_numeric($array_campos[6]) || is_int($array_campos[6]) || is_float($array_campos[6])) {

      $array_campos[8] = "N";
      $array_campos[6] = str_replace(",",".",$array_campos[6]);
      $array_campos[6] = $array_campos[6];

    } else {
      $array_campos[8] = "";
    }

    if ($array_campos[7] == "AMPARADO") {

      $array_campos[3] = $array_campos[3];
      $array_campos[8] = "A";
      $array_campos[5] = "A";
    } else if ($array_campos[7] == "NÃO OPTANTE") {

      $array_campos[4] = "null";
      $array_campos[5] = null;
    } else {
      $array_campos[3] = "null";
    }

    /**
     * Caso o valor do aproveitamento e termo final esteja vazio, alteramos para null, para que seja atualizado no banco,
     * pois caso uma disciplina tenha um valor e este seja apagado, a classe nao faz a alteracao para vazio
     */
    if ($array_campos[6] == '') {
      $array_campos[6] = null;
    }

    if ($array_campos[9] == '') {
      $array_campos[9] = null;
    }

    $oDaoHistMpsDisc->ed65_i_codigo             = $array_campos[1];
    $oDaoHistMpsDisc->ed65_i_historicomps       = $ed65_i_historicomps;
    $oDaoHistMpsDisc->ed65_i_disciplina         = $array_campos[2];
    $oDaoHistMpsDisc->ed65_i_justificativa      = $array_campos[3];
    $oDaoHistMpsDisc->ed65_i_qtdch              = $array_campos[4];
    $oDaoHistMpsDisc->ed65_c_resultadofinal     = $array_campos[5];
    $oDaoHistMpsDisc->ed65_t_resultobtido       = $array_campos[6];
    $oDaoHistMpsDisc->ed65_c_situacao           = $array_campos[7];
    $oDaoHistMpsDisc->ed65_c_tiporesultado      = $array_campos[8];
    $oDaoHistMpsDisc->ed65_c_termofinal         = $array_campos[9];
    $oDaoHistMpsDisc->ed65_lancamentoautomatico = 'false';

    if ($array_campos[0] == "true") {

      if ($array_campos[1] == "") {

        db_inicio_transacao();
        $oDaoHistMpsDisc->incluir($array_campos[1]);
        db_fim_transacao();

      } else {

        db_inicio_transacao();
        $oDaoHistMpsDisc->alterar($array_campos[1]);
        db_fim_transacao();

      }

    } else if ($array_campos[0] == "false") {

      if ($array_campos[1] != "") {

        db_inicio_transacao();
        $oDaoHistMpsDisc->excluir($array_campos[1]);
        db_fim_transacao();

      }

    }

  }

  $db_botao = false;
  db_msgbox("Inclusão efetuada com sucesso!");
  $result = $oDaoHistoricoMps->sql_record($oDaoHistoricoMps->sql_query($ed65_i_historicomps));
  db_fieldsmemory($result,0);

 ?>
  <script>
   parent.arvore.location.href     = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
   parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=<?=@$ed65_i_historicomps?>";
  </script>
 <?
  db_redireciona("edu1_historicomps002.php?chavepesquisa=$ed65_i_historicomps");
  exit;
}

if (isset($ed65_i_historicomps)) {

  $result = $oDaoHistoricoMps->sql_record($oDaoHistoricoMps->sql_query($ed65_i_historicomps));
  db_fieldsmemory($result,0);
  $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query("","ed56_c_situacao",""," ed56_i_aluno = $ed61_i_aluno");
  $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

  if ($oDaoAlunoCurso->numrows > 0) {

    db_fieldsmemory($rsAlunoCurso,0);
    $situacao = $ed56_c_situacao == "CONCLUÍDO" ? "CONCLUÍDO" : "EM ANDAMENTO";

  } else {
    $situacao = "CADASTRADO";
  }

  $db_opcao = 1;
  $db_botao = true;

}
?>

<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
     font-size: 10;

   }
  </style>
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <center>
      <fieldset style="width:95%;"><legend><b>Disciplinas - Etapa cursada na Rede Municipal</b></legend>
        <?include("forms/db_frmhistmpsdisc.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1","ed65_i_historicomps",true,1,"ed65_i_historicomps",true);
</script>