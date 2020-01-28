<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_regenciaperiodo_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_diario_classe.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_diarioresultado_classe.php");
require_once("classes/db_diariofinal_classe.php");
require_once("classes/db_procavaliacao_classe.php");
$db_opcao = 1;
$clregencia = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$clmatricula = new cl_matricula;
$cldiario = new cl_diario;
$cldiarioavaliacao = new cl_diarioavaliacao;
$cldiarioresultado = new cl_diarioresultado;
$cldiariofinal = new cl_diariofinal;
$clprocavaliacao = new cl_procavaliacao;
$codescola = db_getsession("DB_coddepto");
$escola = db_getsession("DB_nomedepto");
$campos_proc = "ed59_i_codigo,
                ed232_c_abrev,
                ed232_c_descr,
                ed59_i_ordenacao,
                case when ed59_c_ultatualiz = 'SI'
                 then '' else ed59_c_ultatualiz
                end as ed59_c_ultatualiz,
                case when ed59_c_encerrada = 'N'
                 then 'NÃO' else 'SIM'
                end as ed59_c_encerrada,
                to_char(ed59_d_dataatualiz,'DD-MM-YYYY') as dataatualizacao,
                ed57_i_escola,
                ed57_i_calendario,
                ed220_i_procedimento,
                ed59_c_freqglob,
                ed57_c_descr as descrturma,
                ed232_c_descr as descrdisc,
                ed52_c_descr as descrcal,
                case when ed57_i_tipoturma = 2
                 then fc_nomeetapaturma(ed59_i_turma)
                 else ed11_c_descr
                end as descrserie,
                ed59_c_condicao,
                ed10_i_codigo,
                ed31_i_codigo,
                ed29_i_codigo
               ";
$result_proc = $clregencia->sql_record($clregencia->sql_query("",$campos_proc,"ed59_c_condicao,ed59_i_ordenacao","ed59_i_turma = $turma AND ed59_i_serie = $codserieregencia"));
//db_criatabela($result_proc);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<?
    db_app::load("scripts.js, prototype.js, DBFormCache.js, DBFormSelectCache.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
}
.cabec{
 font-size: 11;
 color: #444444;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 font-size: 10;
}
</style>
</head>
<script>
function js_avaliacoes(regencia,turma,disciplina,calendario){

  var iTrocaTurma = $F('iTrocaTurma');
  js_OpenJanelaIframe('parent',
                      'db_iframe_avaliacoes'+regencia,
                      'edu1_diarioclasse005.php?regencia='+regencia+'&iTrocaTurma='+iTrocaTurma,
                      'Diário de Classe Turma '+turma,
                      true,20,0,screen.availWidth-15,2000);
  oDBFormCache.save();
}
</script>
<body bgcolor="#cccccc" leftmargin="15" marginheight="0" marginwidth="3" topmargin="5">
<?
if($clregencia->numrows==0){
 echo "<br><br><center><b>Nenhuma disciplina cadastrada nesta turma.<br>(Cadastros/Turmas/Aba Disciplinas)</b></center>";
 exit;
}
?>
<table width="100%" align="left" valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left">
   <?
   $descserie = pg_result($result_proc,0,'descrserie');
   $descrturma = pg_result($result_proc,0,'descrturma');
   $descrcal = pg_result($result_proc,0,'descrcal');
   $ed220_i_procedimento = pg_result($result_proc,0,'ed220_i_procedimento');
   $titulo = "Disciplinas da Turma $descrturma Etapa $descserie em $descrcal";
   ?>
   <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
    <tr>
     <td class='titulo' colspan="7">&nbsp;<b><?=$titulo?></b></td>
    </tr>
    <tr>
     <td class='cabec' align="center">Código</td>
     <td class='cabec' align="center">Disciplina</td>
     <td class='cabec' align="center">Descrição</td>
     <td class='cabec' align="center">Atualizado até</td>
     <td class='cabec' align="center">Encerrada</td>
     <td class='cabec' align="center">Data Atualiz.</td>
     <td class='cabec' align="center">Matrícula</td>
    </tr>
    <?
    $cor1 = "#f3f3f3";
    $cor2 = "#DBDBDB";
    $cor = "";
    $sql3 = "SELECT ed41_i_codigo,
                    ed09_c_abrev,
                    ed09_c_descr,
                    case
                     when ed41_i_codigo>0 then 'A' end as tipo,
                    ed37_c_tipo,
                    ed37_i_menorvalor,
                    ed37_i_maiorvalor,
                    ed37_i_variacao,
                    ed37_c_minimoaprov,
                    ed41_i_formaavaliacao,
                    ed41_i_sequencia
             FROM procavaliacao
              inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao
              inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao
             WHERE ed41_i_procedimento = $ed220_i_procedimento
             UNION
             SELECT ed43_i_codigo,
                    ed42_c_abrev,
                    ed42_c_descr,
                    case
                     when ed43_i_codigo>0 then 'R' end as tipo,
                    ed37_c_tipo,
                    ed37_i_menorvalor,
                    ed37_i_maiorvalor,
                    ed37_i_variacao,
                    ed37_c_minimoaprov,
                    ed43_i_formaavaliacao,
                    ed43_i_sequencia
             FROM procresultado
              inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado
              inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao
             WHERE ed43_i_procedimento = $ed220_i_procedimento
             ORDER BY ed41_i_sequencia
            ";
    $result3 = db_query($sql3);
    $linhas3 = pg_num_rows($result3) or die (pg_errormessage());
    for($c=0;$c<$clregencia->numrows;$c++){
     db_fieldsmemory($result_proc,$c);
     if($cor==$cor1){
      $cor = $cor2;
     }else{
      $cor = $cor1;
     }
     //incluir no diario,diarioavaliacao,diarioresultado,diariofinal
     $result1 = $clmatricula->sql_record($clmatricula->sql_query("","ed60_c_parecer, ed60_i_aluno,ed60_c_situacao as sitatual,ed221_i_serie as etapaorigem",""," ed60_i_turma = $turma AND ed221_i_serie = $codserieregencia AND ed60_c_ativa = 'S'"));
     for($x=0;$x<$clmatricula->numrows;$x++){
      db_fieldsmemory($result1,$x);
      $result2 = $cldiario->sql_record($cldiario->sql_query_file("","ed95_i_codigo",""," ed95_i_regencia = $ed59_i_codigo AND ed95_i_aluno = $ed60_i_aluno"));
      if($cldiario->numrows==0){
       db_inicio_transacao();
       if($sitatual=="TRANSFERIDO FORA" || $sitatual=="TRANSFERIDO REDE" || $sitatual=="CLASSIFICADO" || $sitatual=="AVANÇADO"){
        $diario_encerrado = "S";
       }else{
        $diario_encerrado = "N";
       }
       $cldiario->ed95_c_encerrado = $diario_encerrado;
       $cldiario->ed95_i_escola = $ed57_i_escola;
       $cldiario->ed95_i_calendario = $ed57_i_calendario;
       $cldiario->ed95_i_aluno = $ed60_i_aluno;
       $cldiario->ed95_i_serie = $etapaorigem;
       $cldiario->ed95_i_regencia = $ed59_i_codigo;
       $cldiario->incluir(null);
       db_fim_transacao();
       $ed95_i_codigo = $cldiario->ed95_i_codigo;
      }else{
       db_fieldsmemory($result2,0);
      }
      $result9 = $cldiariofinal->sql_record($cldiariofinal->sql_query_file("","ed74_i_diario",""," ed74_i_diario = $ed95_i_codigo"));
      if ( $cldiariofinal->numrows == 0 ) {


        $iProcResultado      = null;
        $oDaoProcResultado   = new cl_procresultado();
        $sWhereProcResultado = "ed43_i_procedimento  = {$ed220_i_procedimento} and ed43_c_geraresultado = 'S'";
        $sSqlProcResultado   = $oDaoProcResultado->sql_query_file( null, "ed43_i_codigo", null, $sWhereProcResultado );
        $rsProcResultado     = $oDaoProcResultado->sql_record( $sSqlProcResultado );

        if ( $oDaoProcResultado->numrows > 0 ) {

          $iProcResultado                           = db_utils::fieldsMemory( $rsProcResultado, 0 )->ed43_i_codigo;
          $cldiariofinal->ed74_i_procresultadoaprov = isset( $ed41_i_codigo ) && !empty( $ed41_i_codigo ) ? $ed41_i_codigo : null;
          $cldiariofinal->ed74_i_procresultadofreq  = isset( $ed41_i_codigo ) && !empty( $ed41_i_codigo ) ? $ed41_i_codigo : null;
        }

        db_inicio_transacao();
        $ed74_i_codigo                = "";
        $cldiariofinal->ed74_i_diario = $ed95_i_codigo;
        $cldiariofinal->incluir($ed74_i_codigo);
        db_fim_transacao();
      }
      for($q=0;$q<$linhas3;$q++){
       db_fieldsmemory($result3,$q);
       if(trim($tipo)=="A"){
        $result5 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query_file("","ed72_i_diario",""," ed72_i_diario = $ed95_i_codigo AND ed72_i_procavaliacao = $ed41_i_codigo"));
        if($cldiarioavaliacao->numrows==0){
         db_inicio_transacao();
         $cldiarioavaliacao->ed72_i_diario = $ed95_i_codigo;
         $cldiarioavaliacao->ed72_i_procavaliacao = $ed41_i_codigo;

         $sAprovMinimo = 'N';
         if (trim($ed37_c_tipo) == "PARECER") {
           $sAprovMinimo = 'S';
         }
         $cldiarioavaliacao->ed72_c_aprovmin = $sAprovMinimo;
         $cldiarioavaliacao->ed72_c_amparo = "N";
         $cldiarioavaliacao->ed72_i_escola = db_getsession("DB_coddepto");
         $cldiarioavaliacao->ed72_c_tipo = "M";
         $cldiarioavaliacao->ed72_c_convertido = "N";
         $cldiarioavaliacao->incluir(null);
         db_fim_transacao();
        }
       }else{
        if($ed59_c_freqglob!="F"){
         $result5 = $cldiarioresultado->sql_record($cldiarioresultado->sql_query_file("","ed73_i_diario",""," ed73_i_diario = $ed95_i_codigo AND ed73_i_procresultado = $ed41_i_codigo"));
         if($cldiarioresultado->numrows==0){
          db_inicio_transacao();
          $aprovmin = trim($ed37_c_tipo) == "PARECER" ? "S" : "N";
          if ($ed60_c_parecer == 'S' || trim($ed37_c_tipo) == "PARECER") {
						$aprovmin = 'S';
          }
          $cldiarioresultado->ed73_i_diario = $ed95_i_codigo;
          $cldiarioresultado->ed73_i_procresultado = $ed41_i_codigo;
          $cldiarioresultado->ed73_c_aprovmin = $aprovmin;
          $cldiarioresultado->incluir(null);
          db_fim_transacao();
         }
        }
       }
      }
     }
     //incluir em regenciaperiodo
     $result1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo as procaval","ed09_i_sequencia"," ed41_i_procedimento = $ed220_i_procedimento"));
     for($y=0;$y<$clprocavaliacao->numrows;$y++){
      db_fieldsmemory($result1,$y);
      $result2 = $clregenciaperiodo->sql_record($clregenciaperiodo->sql_query_file("","*","","ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $procaval"));
      if($clregenciaperiodo->numrows==0){
       db_inicio_transacao();
       $clregenciaperiodo->ed78_i_regencia = $ed59_i_codigo;
       $clregenciaperiodo->ed78_i_procavaliacao = $procaval;
       $clregenciaperiodo->ed78_i_aulasdadas = null;
       $clregenciaperiodo->incluir(null);
       db_fim_transacao();
      }
     }

     $sSql  = "select ed95_i_codigo,ed95_i_regencia as fimregencia ";
     $sSql .= "  from diario  ";
     $sSql .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
     $sSql .= " where ed95_i_regencia  = {$ed59_i_codigo} ";
     $sSql .= "   AND ed95_c_encerrado = 'N' ";
     $sSql .= "   AND ed59_c_encerrada = 'N' ";

     $result_dia = $cldiario->sql_record($sSql);
     if($cldiario->numrows==0){
      //se todos diarios foram encerrados , finaliza regencia
      $sql_upreg = "UPDATE regencia SET ed59_c_encerrada = 'S' where ed59_i_codigo = $ed59_i_codigo";
      $result_upreg = db_query($sql_upreg);
     }else{
      $sql_upreg = "UPDATE regencia SET ed59_c_encerrada = 'N' where ed59_i_codigo = $ed59_i_codigo";
      $result_upreg = db_query($sql_upreg);
     }
    ?>
    <tr bgcolor="<?=$cor?>" onclick="javascript:js_avaliacoes(<?=$ed59_i_codigo?>,'<?=$descrturma?>','<?=$descrdisc?>','<?=$descrcal?>');" style="Cursor='hand';" onmouseover="bgColor='#DEB887'" onmouseout="bgColor='<?=$cor?>'">
     <td class='aluno' align="center"><?=$ed59_i_codigo?></td>
     <td class='aluno' align="left"><?=$ed232_c_abrev?></td>
     <td class='aluno' align="left"><?=$ed232_c_descr?></td>
     <td class='aluno' align="center"><?=$ed59_c_ultatualiz==""?"&nbsp;":$ed59_c_ultatualiz?></td>
      <?if($cldiario->numrows==0){?>
       <td class='aluno' align="center">SIM</td>
      <?}else{?>
       <td class='aluno' align="center">NÃO</td>
      <?}?>
      </td>
     <td class='aluno' align="center"><?=$dataatualizacao?></td>
     <td class='aluno' align="center"><?=$ed59_c_condicao?></td>
    </tr>
   <?}?>
   </table>
  </td>
 </tr>
 <tr>
   <td align="center">
     <b>Exibir Trocas de Turma: </b>
     <?php
       $aTrocaTurma = array("1" => "Não", "2" => "Sim");
       db_select("iTrocaTurma", $aTrocaTurma, true, 1);
     ?>
   </td>
 </tr>
 <tr>
  <td align="center" colspan="7">
   <br>
   <input type="button" value="Encerrar Avaliações" name="encerrar" onclick="js_encerrar(<?=$turma?>,'<?=$descrturma?>',<?=$codserieregencia?>,<?=$ed31_i_codigo?>,<?=$ed29_i_codigo?>)" style='height:20px;border: 2px outset #f3f3f3;font-size:10px;padding:0px;'>
   &nbsp;&nbsp;&nbsp;
   <input type="button" value="Cancelar Encerramento Avaliações" name="cancelar" onclick="js_cancelar(<?=$turma?>,'<?=$descrturma?>',<?=$codserieregencia?>)" style='height:20px;border: 2px outset #f3f3f3;font-size:10px;padding:0px;'>
  </td>
 </tr>
</table>
</body>
</html>
<script>
var oDBFormCache = new DBFormCache('oDBFormCache', 'edu1_diarioclasse004.php');
oDBFormCache.setElements(new Array($('iTrocaTurma')));
oDBFormCache.load();

function js_encerrar(codturma,turma,codserieregencia, ed31_i_codigo, ed29_i_codigo){
 js_OpenJanelaIframe('parent','db_iframe_encerrar'+codturma,'edu1_encerraraval001.php?turma='+codturma+'&codserieregencia='+codserieregencia+'&base='+ed31_i_codigo+'&curso='+ed29_i_codigo,'Encerramento de Avaliações Turma '+turma,true);
}
function js_cancelar(codturma,turma,codserieregencia){
 js_OpenJanelaIframe('parent','db_iframe_cancelar'+codturma,'edu1_cancelaraval001.php?turma='+codturma+'&codserieregencia='+codserieregencia,'Cancelar Encerramento de Avaliações Turma '+turma,true);
}
parent.document.getElementById("tab_aguarde").style.visibility = "hidden";
</script>