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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_transfescolarede_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_diariofinal_classe.php");
include("classes/db_amparo_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$resultedu= eduparametros(db_getsession("DB_coddepto"));
$cltransfescolarede = new cl_transfescolarede;
$clmatricula = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$clalunocurso = new cl_alunocurso;
$clregencia = new cl_regencia;
$clturma = new cl_turma;
$cldiario = new cl_diario;
$cldiarioavaliacao = new cl_diarioavaliacao;
$cldiarioresultado = new cl_diarioresultado;
$cldiariofinal = new cl_diariofinal;
$clamparo = new cl_amparo;
$db_opcao = 1;
$db_botao = true;
$escola = db_getsession("DB_coddepto");
if(isset($chavepesquisa)){
 $campos = "transfescolarede.ed103_i_codigo,
            transfescolarede.ed103_i_matricula,
            aluno.ed47_i_codigo,
            aluno.ed47_v_nome,
            escola.ed18_i_codigo as codescolaorig,
            escola.ed18_c_nome as nomeescolaorig,
            escoladestino.ed18_i_codigo as codescoladest,
            escoladestino.ed18_c_nome as nomeescoladest,
            atestvaga.ed102_i_base as codbasedest,
            base.ed31_c_descr as nomebasedest,
            cursoedu.ed29_i_avalparcial,
            atestvaga.ed102_i_calendario as codcaldest,
            calendario.ed52_c_descr as nomecaldest,
            calendario.ed52_i_ano as anocaldest,
            atestvaga.ed102_i_serie as codseriedest,
            serie.ed11_c_descr||' - '||ensino.ed10_c_abrev as nomeseriedest,
            atestvaga.ed102_i_turno as codturnodest,
            atestvaga.ed102_i_codigo as codatestdest,
            turno.ed15_c_nome as nometurnodest
           ";
 $result = $cltransfescolarede->sql_record($cltransfescolarede->sql_query("",$campos,""," ed103_i_codigo = $chavepesquisa"));
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b>Matricular Alunos Transferidos (REDE)</b></legend>
    <table border="0" width="100%">
     <tr>
      <td colspan="2">
       <?db_input('ed103_i_codigo',15,@$Ied103_i_codigo,true,'hidden',3,"")?>
       <?db_ancora("<b>Aluno:</b>","js_pesquisatransf();",$db_opcao);?>
       <?db_input('ed47_i_codigo',15,@$Ied47_i_codigo,true,'text',3,'')?>
       <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?>
      </td>
     </tr>
     <?if(isset($chavepesquisa)){
     $campos = "matricula.ed60_c_concluida,
                serie.ed11_i_codigo as codserieorig,
                serie.ed11_c_descr||' - '||ensino.ed10_c_abrev as nomeserieorig,
                base.ed31_i_codigo as codbaseorig,
                calendario.ed52_i_codigo as codcalorig,
                calendario.ed52_i_ano as anocalorig,
                cursoedu.ed29_i_codigo as codcursoorig,
                turma.ed57_i_codigo as codturmaorig,
                turma.ed57_c_descr as nometurmaorig,
                matricula.ed60_i_aluno,
                matricula.ed60_d_datamatricula,
                ed60_d_datasaida as datasaidaorig
               ";
     $result = $clmatricula->sql_record($clmatricula->sql_query("",$campos,""," ed60_i_codigo = $ed103_i_matricula"));
     db_fieldsmemory($result,0);
     $datamatriculaorig = db_formatar($ed60_d_datamatricula,'d');
     $datasaidaorig = db_formatar($datasaidaorig,'d');
     $concluida = $ed60_c_concluida=="S"?"CONCLUÍDA":"NÃO CONCLUÍDA";
     if($ed60_c_concluida=="S"){
      $sql1 = "SELECT ed56_i_base as codbaseorig
               FROM alunocurso
               WHERE ed56_i_aluno = $ed47_i_codigo
              ";
      $result1 = pg_query($sql1);
      db_fieldsmemory($result1,0);
     }
     $result1 = $clalunocurso->sql_record($clalunocurso->sql_query_file("","ed56_i_codigo, ed56_c_situacao",""," ed56_i_aluno = $ed47_i_codigo"));
     db_fieldsmemory($result1,0);
     ?>
     <tr>
      <td>
       <fieldset style="width:95%;"><legend><b>Dados de Origem</b></legend>
        <table>
         <tr>
          <td>
           <b>Matrícula:</b>
          </td>
          <td>
           <?db_input('ed103_i_matricula',10,@$ed103_i_matricula,true,'text',3,'')?>
           <?db_input('concluida',20,@$concluida,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Data Matrícula:</b>
          </td>
          <td>
           <?db_input('datamatriculaorig',10,@$datamatriculaorig,true,'text',3,'')?>
           <b>Data Saída:</b>
           <?db_input('datasaidaorig',10,@$datasaidaorig,true,'text',3,'')?>
           <?db_input('ed60_c_concluida',10,@$ed60_c_concluida,true,'hidden',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Escola:</b>
          </td>
          <td>
           <?db_input('codescolaorig',15,@$codescolaorig,true,'hidden',3,'')?>
           <?db_input('nomeescolaorig',40,@$nomeescolaorig,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Situação:</b>
          </td>
          <td>
           <?db_input('ed56_c_situacao',40,@$ed56_c_situacao,true,'text',3,'')?>
           <?db_input('codcursoorig',50,@$codcursoorig,true,'hidden',3,'')?>
           <?db_input('ed56_i_codigo',50,@$ed56_i_codigo,true,'hidden',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('codserieorig',15,@$codserieorig,true,'hidden',3,'')?>
           <?db_input('nomeserieorig',20,@$nomeserieorig,true,'text',3,'')?>
           <b>Turma:</b>
           <?db_input('codturmaorig',15,@$codturmaorig,true,'hidden',3,'')?>
           <?db_input('nometurmaorig',20,@$nometurmaorig,true,'text',3,'')?>
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
      <td>
       <fieldset style="width:95%;"><legend><b>Dados de Destino (Atestado de Vaga)</b></legend>
        <table border="0">
         <tr>
          <td>
           <b>Escola:</b>
          </td>
          <td>
           <?db_input('codescoladest',15,@$codescoladest,true,'hidden',3,'')?>
           <?db_input('nomeescoladest',40,@$nomeescoladest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Etapa:</b>
          </td>
          <td>
           <?db_input('codseriedest',15,@$codseriedest,true,'hidden',3,'')?>
           <?db_input('nomeseriedest',40,@$nomeseriedest,true,'text',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Base:</b>
          </td>
          <td>
           <?db_input('codbasedest',15,@$codbasedest,true,'hidden',3,'')?>
           <?db_input('nomebasedest',40,@$nomebasedest,true,'text',3,'')?>
           <?db_input('ed29_i_avalparcial',1,@$ed29_i_avalparcial,true,'hidden',3,'')?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Calendário:</b>
          </td>
          <td>
           <?db_input('codcaldest',15,@$codcaldest,true,'hidden',3,'')?>
           <?db_input('nomecaldest',40,@$nomecaldest,true,'text',3,'')?>
           <?if($ed60_c_concluida=="S"){?>
            <input type="button" name="alt_calendario" value="Alterar" onclick="js_destino(<?=$codatestdest?>)">
           <?}?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Turno:</b>
          </td>
          <td>
           <?db_input('codturnodest',15,@$codturnodest,true,'hidden',3,'')?>
           <?db_input('nometurnodest',40,@$nometurnodest,true,'text',3,'')?>
          </td>
         </tr>
        </table>
       </fieldset>
      </td>
     </tr>
     <?
     $camposant="turma.ed57_i_codigo as codturmadest,
                 turma.ed57_c_descr as nometurmadest,
                 calendario.ed52_d_inicio,
                 calendario.ed52_d_fim,
                 ed60_d_datasaida as datasaida,
                 ed60_i_codigo as matriculaante
                ";
     $result_verif = $clmatricula->sql_record($clmatricula->sql_query("",$camposant,""," ed60_i_aluno = $ed60_i_aluno AND calendario.ed52_i_codigo= $codcaldest AND ed60_c_situacao = 'TRANSFERIDO REDE' AND turma.ed57_i_escola = ".db_getsession("DB_coddepto")." AND ed60_c_ativa ='S'"));
     $linhas_verif = $clmatricula->numrows;
     if($clmatricula->numrows>0){
      db_fieldsmemory($result_verif,0);
      $datahj = date("Y-m-d");
      $datasaida_dia = substr($datasaida,8,2);
      $datasaida_mes = substr($datasaida,5,2);
      $datasaida_ano = substr($datasaida,0,4);
      $data_in = mktime(0,0,0,$datasaida_mes,$datasaida_dia,$datasaida_ano);
      $data_out = mktime(0,0,0,substr($datahj,5,2),substr($datahj,8,2),substr($datahj,0,4));
      $data_entre = $data_out - $data_in;
      $dias = ceil($data_entre/86400);
      ?>
      <tr>
       <td colspan="2">
        <font color="red"><b>Aluno (<?=$ed60_i_aluno?>) já possui matrícula nesta escola na turma abaixo relacionada, com situação de TRANSFERIDO REDE a <?=@$dias?> dia<?=@$dias>1?"(s)":""?>.</b></font>
       </td>
      </tr>
      <?
     }
     ?>
     <tr>
      <td colspan="2">
       <?db_ancora("<b>Turma Destino:</b>","js_pesquisaturmadest(true);",isset($datasaida)?3:1)?>
       <?db_input('codturmadest',15,@$Icodturmadest,true,'text',3,'')?>
       <?db_input('nometurmadest',50,@$Inometurmadest,true,'text',3,'')?>
       <?db_input('ed52_d_inicio',10,@$Ied52_d_inicio,true,'hidden',3,'')?>
       <?db_input('ed52_d_fim',10,@$Ied52_d_fim,true,'hidden',3,'')?>
       <?if($clmatricula->numrows>0){?>
        <b>Matrícula:</b>
        <?db_input('matriculaante',10,@$Imatriculaante,true,'text',3,'')?>
        <b>Data Sáida:</b>
        <?db_inputdata('datasaida',@$datasaida_dia,@$datasaida_mes,@$datasaida_ano,true,'text',3,"")?>
       <?}?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <iframe id="iframe_trocaturma" name="iframe_trocaturma" src="" width="100%" height="800" frameborder="0"></iframe>
      </td>
     </tr>
    <?}?>
    </table>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?if($clmatricula->numrows>0){?>
 <script>
  iframe_trocaturma.location.href = 'edu1_matriculatransf002.php?ed103_i_codigo=<?=$ed103_i_codigo?>&matricula=<?=$ed103_i_matricula?>&turmaorigem=<?=$codturmaorig?>&turmadestino=<?=$codturmadest?>&matriculaante=<?=$matriculaante?>';
  document.getElementById("iframe_trocaturma").style.visibility = "visible";
 </script>
<?}?>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
 js_tabulacaoforms("form1","ed103_i_codigo",true,1,"ed103_i_codigo",true);
 function js_pesquisaturmadest(mostra){
  document.getElementById("iframe_trocaturma").style.visibility = "hidden";
  if(mostra==true){
   js_OpenJanelaIframe('top.corpo',
                       'db_iframe_turma',
                       'func_turmatransfrede.php?avalparcial='+document.form1.ed29_i_avalparcial.value+
                       '&codaluno='+document.form1.ed47_i_codigo.value+
                       '&aluno='+document.form1.ed47_v_nome.value+
                       '&serie='+document.form1.codseriedest.value+
                       '&calendario='+document.form1.codcaldest.value+
                       '&turmasprogressao=f'+
                       '&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|ed52_d_inicio|ed52_d_fim',
                       'Pesquisa de Turma de Destino',
                       true
                      );
  }
 }
 function js_mostraturma1(chave1,chave2,chave3,chave4){
  document.form1.codturmadest.value = chave1;
  document.form1.nometurmadest.value = chave2;
  document.form1.ed52_d_inicio.value = chave3;
  document.form1.ed52_d_fim.value = chave4;
  db_iframe_turma.hide();
  iframe_trocaturma.location.href = 'edu1_matriculatransf002.php?ed103_i_codigo='+document.form1.ed103_i_codigo.value+'&matricula='+document.form1.ed103_i_matricula.value+'&turmaorigem='+document.form1.codturmaorig.value+'&turmadestino='+document.form1.codturmadest.value;
  document.getElementById("iframe_trocaturma").style.visibility = "visible";
 }
 function js_pesquisatransf(){
  js_OpenJanelaIframe('top.corpo','db_iframe_transfescolarede','func_transfescolarede.php?funcao_js=parent.js_preenchepesquisa|ed103_i_codigo','Pesquisa',true);
 }
 function js_preenchepesquisa(chave){
  db_iframe_transfescolarede.hide();
  <?
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
 }
 function js_destino(atestado){
  js_OpenJanelaIframe('top.corpo','db_iframe_atestvaga','edu1_atestvaga002.php?chavepesquisa='+atestado,'Alterar Calendário do Atestado de Vaga',true);
 }
</script>