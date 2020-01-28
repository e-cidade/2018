<?
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("classes/db_turmaturnoadicional_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$iAnoEtapaCenso        = null;
$clturma               = new cl_turma;
$clturmaturnoadicional = new cl_turmaturnoadicional;
$clescola              = new cl_escola;
$clescolaestrutura     = new cl_escolaestrutura;
$clregencia            = new cl_regencia;
$clregenciahorario     = new cl_regenciahorario;
$clmatricula           = new cl_matricula;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$db_opcao              = 22;
$db_opcao1             = 3;
$db_botao              = false;
$db_botao2             = true;
$codigoescola          = db_getsession("DB_coddepto");
$sCampos               = "ed255_i_ativcomplementar,ed255_i_aee";
$result_estr           = $clescolaestrutura->sql_record($clescolaestrutura->sql_query("",
                                                                                      $sCampos,
                                                                                      "",
                                                                                      " ed255_i_escola = $codigoescola"
                                                                                     )
                                                       );
if ($clescolaestrutura->numrows > 0) {
  db_fieldsmemory($result_estr,0);
  if ($ed255_i_aee == 2) {
  	$sString  = "<font color='red'><b>* Escola oferede EXCLUSIVAMENTE Atendimento Educacional Especial - AEE (Cadastros ->";
  	$sString .= "Dados da Escola -> Aba Infraestrutura)</b></font>";
    echo "$sString";
  }
  if ($ed255_i_ativcomplementar == 2) {
  	$sTexto  = "<br><font color='red'><b>* Escola oferece EXCLUSIVAMENTE Atividade Complementar (Cadastros -> ";
  	$sTexto .= "Dados da Escola -> Aba Infra Estrutura)</b></font>";
    echo "$sTexto";
  }
  if ($ed255_i_aee == 2 || $ed255_i_ativcomplementar == 2) {
    $db_botao  = false;
    $db_botao2 = false;
  }
}

if(isset($alterar)) {
  $db_opcao  = 2;
  $db_opcao1 = 3;
  db_inicio_transacao();
  $clturma->ed57_c_descr = trim($ed57_c_descr);
  $clturma->alterar($ed57_i_codigo);
  db_fim_transacao();
  $db_botao = true;
} else if(isset($chavepesquisa)) {
  $db_opcao  = 2;
  $db_opcao1 = 3;
  
  $oDaoTurmaCensoEtapa    = new cl_turmacensoetapa();
  $sWhereTurmaCensoEtapa  = " ed132_turma = {$chavepesquisa}";
  $sSqlTurmaCensoEtapa    = $oDaoTurmaCensoEtapa->sql_query_file(null, "ed132_ano", null, $sWhereTurmaCensoEtapa);
  $rsTurmaCensoEtapa      = db_query( $sSqlTurmaCensoEtapa );

  if ( !$rsTurmaCensoEtapa ) {
    throw new DBException("Não foi possivel buscar o vinculo da turma com o censo.");
  }

  if ( pg_num_rows( $rsTurmaCensoEtapa ) == 0 ) {
    throw new DBException("Não há vinculos do censo com a turma.");
  }

  $iAnoEtapaCenso = db_utils::fieldsMemory( $rsTurmaCensoEtapa, 0 )->ed132_ano;

  $result    = $clturma->sql_record($clturma->sql_query_turma_etapa_censo($chavepesquisa, $iAnoEtapaCenso));
  db_fieldsmemory($result,0);
  $db_botao = true;
  $result1  = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) ",""," ed60_i_turma = $ed57_i_codigo"));
  db_fieldsmemory($result1,0);
  $ed57_i_nummatr = $count;
  $result1        = $clturmaturnoadicional->sql_record($clturmaturnoadicional->sql_query("",
                                                                       "ed246_i_turno,ed15_c_nome as ed15_c_nomeadd",
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                             );
 if ($clturmaturnoadicional->numrows > 0) {
   db_fieldsmemory($result1,0);
 }
 ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;
   parent.document.formaba.a4.disabled = false;
   parent.document.formaba.a5.disabled = false;
   top.corpo.iframe_a2.location.href='edu1_regenciaabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>';
   top.corpo.iframe_a3.location.href='edu1_regenciahorarioabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_turno=<?=$ed57_i_turno?>';
   top.corpo.iframe_a4.location.href='edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
   top.corpo.iframe_a5.location.href='edu1_parecerturma001.php?ed105_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
  </script>
 <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Turma</b></legend>
    <?include("forms/db_frmturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed57_c_descr",true,1,"ed57_c_descr",true);
</script>
<?
if (isset($alterar)) {
  if ($clturma->erro_status == "0") {
    $clturma->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clturma->erro_campo != "") {
      echo "<script> document.form1.".$clturma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturma->erro_campo.".focus();</script>";
    }
  } else {
    $clturma->erro(true,false);
    $result = $clturma->sql_record($clturma->sql_query("",
                                                       "ed57_i_turno as turnoant,ed15_c_nome as descrant",
                                                       "",
                                                       " ed57_i_codigo = $ed57_i_codigo"
                                                      )
                                  );
    db_fieldsmemory($result,0);
    if ($turnoant != $ed57_i_turno) {
      $result1 = $clregencia->sql_record($clregencia->sql_query("",
                                                                "ed59_i_codigo as regencia",
                                                                "",
                                                                " ed59_i_turma = $ed57_i_codigo"
                                                               )
                                        );
      for ($c = 0; $c < $clregencia->numrows; $c++) {
        db_fieldsmemory($result1,$c);
        $clregenciahorario->excluir(""," ed58_i_regencia = $regencia");
      }
    }
    $sCampos = "ed246_i_codigo as codconf,ed246_i_turno as turnoaddant";
    $result2 = $clturmaturnoadicional->sql_record($clturmaturnoadicional->sql_query_file("",
                                                                       $sCampos,
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                        );
    $linhas2 = $clturmaturnoadicional->numrows;
    if ($linhas2 > 0) {
      $turnoaddant = pg_result($result2,0,'turnoaddant');
    }
    if ($ed246_i_turno == "") {
      if ($clturmaturnoadicional->numrows > 0) {
        db_fieldsmemory($result2,0);
        $clturmaturnoadicional->excluir($codconf);
        $exclusaoadd = true;
      }
    } else {
      if ($clturmaturnoadicional->numrows > 0) {
        db_fieldsmemory($result2,0);
        $clturmaturnoadicional->ed246_i_turma  = $ed57_i_codigo;
        $clturmaturnoadicional->ed246_i_codigo = $codconf;
        $clturmaturnoadicional->alterar($codconf);
      } else {
        $clturmaturnoadicional->ed246_i_turma = $ed57_i_codigo;
        $clturmaturnoadicional->incluir(null);
      }
  }
  if ($linhas2 > 0) {
    if (($ed246_i_turno != "" && $ed246_i_turno != $turnoaddant) || isset($exclusaoadd)) {
      $result1 = $clregenciahorario->sql_record($clregenciahorario->sql_query("","ed58_i_codigo as codreghora",""," ed59_i_turma = $ed57_i_codigo AND ed17_i_turno = $turnoaddant and ed58_ativo is true  "));
      $linhas11 = $clregenciahorario->numrows;
      for ($c = 0; $c < $linhas11; $c++) {
        db_fieldsmemory($result1,$c);
        $clregenciahorario->excluir($codreghora);
      }
    }
  }
  ?>
   <script>parent.document.form2.teste.click();</script>
  <?
 }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>