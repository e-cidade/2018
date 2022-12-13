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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDaoHistMpsDiscFora  = db_utils::getDao("histmpsdiscfora");
$oDaoHistoricompsFora = db_utils::getDao("historicompsfora");
$oDaoAlunoCurso       = db_utils::getDao("alunocurso");
$oDaoDisciplina       = db_utils::getDao("disciplina");

db_postmemory($HTTP_POST_VARS);
$db_opcao           = 1;
$db_botao           = true;

if(isset($registrodisc)){
 $array_registro = explode("|",$registrodisc);
 for($y=0;$y<count($array_registro);$y++){
  $array_campos = explode(";",$array_registro[$y]);
  if(is_numeric($array_campos[6]) || is_int($array_campos[6]) || is_float($array_campos[6])){
   $array_campos[8] = "N";
   $array_campos[6] = str_replace(",",".",$array_campos[6]);
  }else{
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

  $oDaoHistMpsDiscFora->ed100_i_codigo           = $array_campos[1];
  $oDaoHistMpsDiscFora->ed100_i_historicompsfora = $ed100_i_historicompsfora;
  $oDaoHistMpsDiscFora->ed100_i_disciplina       = $array_campos[2];
  $oDaoHistMpsDiscFora->ed100_i_justificativa    = $array_campos[3];
  $oDaoHistMpsDiscFora->ed100_i_qtdch            = $array_campos[4];
  $oDaoHistMpsDiscFora->ed100_c_resultadofinal   = $array_campos[5];
  $oDaoHistMpsDiscFora->ed100_t_resultobtido     = $array_campos[6];
  $oDaoHistMpsDiscFora->ed100_c_situacao         = $array_campos[7];
  $oDaoHistMpsDiscFora->ed100_c_tiporesultado    = $array_campos[8];
  $oDaoHistMpsDiscFora->ed100_c_termofinal       = $array_campos[9];

  if($array_campos[0]=="true"){
    if($array_campos[1]==""){

      db_inicio_transacao();
      $oDaoHistMpsDiscFora->incluir($array_campos[1]);
      db_fim_transacao();
    }else{

      db_inicio_transacao();
      $oDaoHistMpsDiscFora->alterar($array_campos[1]);
      db_fim_transacao();
    }
  }elseif($array_campos[0]=="false"){
   if($array_campos[1]!=""){

     db_inicio_transacao();
     $oDaoHistMpsDiscFora->excluir($array_campos[1]);
     db_fim_transacao();
   }
  }
 }
 db_msgbox("Inclusão efetuada com sucesso!");
 $result = $oDaoHistoricompsFora->sql_record($oDaoHistoricompsFora->sql_query($ed100_i_historicompsfora));
 db_fieldsmemory($result,0);
 ?>
 <script>
  parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
  parent.disciplina.location.href = "edu1_historicodisciplinafora.php?ed100_i_historicompsfora=<?=@$ed99_i_codigo?>";
 </script>
 <?
 db_redireciona("edu1_historicompsfora002.php?chavepesquisa=$ed100_i_historicompsfora");
 exit;
}
if(isset($ed100_i_historicompsfora)){

  $result = $oDaoHistoricompsFora->sql_record($oDaoHistoricompsFora->sql_query($ed100_i_historicompsfora));
  db_fieldsmemory($result,0);
  $result = $oDaoAlunoCurso->sql_record($oDaoAlunoCurso->sql_query("","ed56_c_situacao",""," ed56_i_aluno = $ed61_i_aluno"));
  if($oDaoAlunoCurso->numrows>0) {
    db_fieldsmemory($result,0);
    $situacao = $ed56_c_situacao=="CONCLUÍDO"?"CONCLUÍDO":"EM ANDAMENTO";
  }else{
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
<script type="text/javascript" src="scripts/classes/educacao/escola/HistoricoEscolar.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
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
   <fieldset style="width:95%;"><legend><b>Disciplinas - Etapa cursada fora da Rede Municipal</b></legend>
    <?include(modification("forms/db_frmhistmpsdiscfora.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed100_i_historicompsfora",true,1,"ed100_i_historicompsfora",true);
</script>