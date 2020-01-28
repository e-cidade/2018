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

//MODULO: educação
$clmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed57_i_turno");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed57_i_numvagas");
$clrotulo->label("ed57_i_nummatr");
$clrotulo->label("ed248_t_obs");
$clrotulo->label("ed248_i_motivo");
?>
<form name="form1" id='form1' method="post" action="">
 <center>
  <table border="0" width="100%">
   <tr>
    <td colspan="3">
     <fieldset style="width:97%"><legend><b>Turma</b></legend>
      <table border="0">
       <tr>
        <td nowrap title="<?=@$Ted60_i_turma?>">
         <?db_ancora(@$Led60_i_turma,"js_pesquisaed60_i_turma();",$db_opcao==3?$db_opcao1:$db_opcao);?>
        </td>
        <td>
         <?db_input('ed60_i_turma',15,$Ied60_i_turma,true,'text',3,'')?>
         <?db_input('ed57_c_descr',20,@$Ied57_c_descr,true,'text',3,'')?>
         <?=@$Led57_i_calendario?>
         <?db_input('ed52_c_descr',20,@$Ied52_c_descr,true,'text',3,'')?>
        </td>
       </tr>
       <tr>
       <td nowrap title="<?=@$Ted31_i_curso?>">
        <?=@$Led31_i_curso?>
       </td>
       <td>
        <?db_input('ed29_c_descr',40,@$Ied29_c_descr,true,'text',3,'')?>
        <?=@$Led223_i_serie?>
        <?db_input('nometapa',30,@$Inometapa,true,'text',3,'')?>
        <?=@$Led57_i_turno?>
        <?db_input('ed15_c_nome',20,@$Ied15_c_nome,true,'text',3,'')?>
       </td>
      </tr>
      <tr>
       <td nowrap title="<?=@$Ted31_i_curso?>">
        <?=@$Led57_i_numvagas?>
       </td>
       <td>
        <?db_input('ed57_i_numvagas',10,@$Ied57_i_numvagas,true,'text',3,'')?>
          &nbsp;&nbsp;&nbsp;&nbsp;
        <?=@$Led57_i_nummatr?>
        <?db_input('ed57_i_nummatr',10,@$Ied57_i_nummatr,true,'text',3,'')?>
          &nbsp;&nbsp;&nbsp;&nbsp;
        <b>Vagas Disponíveis:</b>
        <?db_input('restantes',10,@$Irestantes,true,'text',3,'')?>
       </td>
      </tr>
     </table>
    </fieldset>
   </td>
  </tr>
  <?if (isset($chavepesquisa) && $db_opcao == 1) { ?>
     <tr>
      <td valign="top">
    <?
      $sCampos  = "ed57_i_base as base,ed57_i_codigo,ed57_i_escola as escola, fc_codetapaturma(ed57_i_codigo) as serie,";
      $sCampos .= "ed57_i_turno as turno,ed57_i_calendario as calendario,ed52_i_ano as anoatual, ";
      $sCampos .= " ed29_i_avalparcial as parametroatual";
      $sSql     = $clturma->sql_query("",$sCampos,""," ed57_i_codigo = $ed60_i_turma");
      $rs       = $clturma->sql_record($sSql);
      db_fieldsmemory($rs,0);

      $sSqlCal = $clcalendario->sql_query_file("","ed52_i_calendant,ed52_i_periodo as periodo",
                                               ""," ed52_i_codigo = $calendario"
                                              );
      $rsCal   = $clcalendario->sql_record($sSqlCal);
      db_fieldsmemory($rsCal,0);

      $ed52_i_calendant = $ed52_i_calendant==""?"0":$ed52_i_calendant;

      if ($ed52_i_calendant != "0") {

      	$sSqlCalend = $clcalendario->sql_query_file("","ed52_i_ano as anoanterior",
      	                                            ""," ed52_i_codigo = $ed52_i_calendant"
      	                                           );
        $rsCalend   = $clcalendario->sql_record($sSqlCalend);
        db_fieldsmemory($rsCalend,0);

      } else {
        $anoanterior = 0;
      }

      $sSqlSerieEquiv  = " SELECT ARRAY(SELECT ed234_i_serieequiv FROM serieequiv ";
      $sSqlSerieEquiv .= " WHERE ed234_i_serie in ($serie)) as seriesequivalentes";
      $rsSerieEquiv    = db_query($sSqlSerieEquiv);
      db_fieldsmemory($rsSerieEquiv,0);

      $seriesequivalentes = str_replace("{","",$seriesequivalentes);
      $seriesequivalentes = str_replace("}","",$seriesequivalentes);

      if ($seriesequivalentes == "") {
        $seriesequivalentes = "($serie)";
      } else {
        $seriesequivalentes = "($serie,$seriesequivalentes)";
      }

      $sCamposAluno  = " DISTINCT                                                                         \n";
      $sCamposAluno .= " ed47_i_codigo,                                                                   \n";
      $sCamposAluno .= " ed47_v_nome,                                                                     \n";
      $sCamposAluno .= " ed56_c_situacao,                                                                 \n";
      $sCamposAluno .= " ed11_c_descr,                                                                    \n";
      $sCamposAluno .= " ed10_c_abrev,                                                                    \n";
      $sCamposAluno .= " ed29_i_avalparcial,                                                              \n";
   		$sCamposAluno .= " ed79_i_turmaant,                                                                 \n";
   		$sCamposAluno .= " ed79_c_resulant,                                                                 \n";
   		$sCamposAluno .= " ed79_i_serie                                                                     \n";
      
      $sCondicao     = " ed56_i_escola = $escola                                                          \n";
      $sCondicao    .= " AND ed79_i_serie in $seriesequivalentes                                          \n";
      $sCondicao    .= " AND (                                                                            \n";
      $sCondicao    .= "      (ed52_i_ano = $anoatual AND ed56_c_situacao = 'CANDIDATO' )                 \n";
      $sCondicao    .= "      OR                                                                          \n";
      $sCondicao    .= "     ((ed56_c_situacao = 'APROVADO' OR  ed56_c_situacao = 'MATRICULA TRANCADA'    \n";
      $sCondicao    .= "      OR ed56_c_situacao = 'APROVADO PARCIAL' OR ed56_c_situacao = 'REPETENTE'))  \n";
      $sCondicao    .= "    )                                                                             \n";
      $sCondicao    .= "  AND (                                                                           \n";
      $sCondicao    .= "        ( ed56_i_calendario = $calendario AND ed56_c_situacao = 'CANDIDATO' )     \n";
      $sCondicao    .= "       OR                                                                         \n";
      $sCondicao    .= "        (     ed56_i_calendario   = $ed52_i_calendant                             \n";
      $sCondicao    .= "          AND ( ed56_c_situacao   = 'APROVADO'                                    \n";
      $sCondicao    .= "               OR ed56_c_situacao = 'REPETENTE')                                   \n";
      $sCondicao    .= "          OR (ed56_c_situacao = 'APROVADO PARCIAL')                           \n";
      $sCondicao    .= "        )                                                                         \n";
      $sCondicao    .= "      )                                                                           \n";
      $sCondicao    .= " AND not exists(select * from alunocurso as alunocurso2                           \n";
      $sCondicao    .= "               where alunocurso2.ed56_i_aluno = ed47_i_codigo                     \n";
      $sCondicao    .= "               and alunocurso2.ed56_i_base != '$base'                             \n";
      $sCondicao    .= "               and alunocurso2.ed56_c_situacao = 'MATRICULADO'                    \n";
      $sCondicao    .= "              )                                                                   \n";
      $sSqlAluno    = $claluno->sql_query_matricula("", $sCamposAluno, "ed47_v_nome", $sCondicao);

      
     // ed59_c_encerrada
      
      $rsAluno = $claluno->sql_record($sSqlAluno);
      
      
      $iLinhasAluno = $claluno->numrows;
   ?>
      <b>Alunos em condição de matrícula:</b><br>
      <select name="alunospossib" id="alunospossib" size="10" onclick="js_desabinc()"
              style="font-size:9px;width:430px;height:180px" multiple> 
    <?
       if ($iLinhasAluno > 0) {

         for ($i = 0; $i < $iLinhasAluno; $i++) {

           db_fieldsmemory($rsAluno,$i);
           $mostra = true;

           if ($parametroatual == $ed29_i_avalparcial && $ed29_i_avalparcial == 2) {

             $sSqlReg  = " SELECT * ";
             $sSqlReg .= " FROM histmpsdisc";
             $sSqlReg .= "  inner join historicomps on ed62_i_codigo = ed65_i_historicomps";
             $sSqlReg .= "  inner join historico on ed61_i_codigo = ed62_i_historico";
             $sSqlReg .= " WHERE ed61_i_aluno  = $ed47_i_codigo";
             $sSqlReg .= " AND ed61_i_curso = $ed29_i_codigo";
             $sSqlReg .= " AND ed62_i_serie = $ed79_i_serie";
             $sSqlReg .= " AND ed62_c_resultadofinal = 'P'";
             $sSqlReg .= " AND exists(select * from regencia";
             $sSqlReg .= "            where ed59_i_turma = $ed60_i_turma";
             $sSqlReg .= "            and ed59_i_serie = $ed79_i_serie";
             $sSqlReg .= "            and ed59_i_disciplina = ed65_i_disciplina)";

           	 $rsReg    = db_query($sSqlReg);
   
           	 
             $iLinhas  = pg_num_rows($rsReg);
             if ($iLinhas > 0) {
               $mostra = false;
             }

           }
           if ($ed56_c_situacao == "APROVADO") {
             $sitdescr = "APROVADO (PARA $ed11_c_descr - $ed10_c_abrev)";
           } else if ($ed56_c_situacao == "APROVADO PARCIAL") {
             $sitdescr = "APROVADO PARCIAL (PARA $ed11_c_descr - $ed10_c_abrev)";
           } else if ($ed56_c_situacao == "REPETENTE") {
             $sitdescr = "REPETENTE (NA $ed11_c_descr - $ed10_c_abrev)";
           } else if ($ed56_c_situacao == "MATRICULA TRANCADA") {
             $sitdescr = "MATRICULA TRANCADA (NA $ed11_c_descr - $ed10_c_abrev)";
           } else {
             $sitdescr = "CANDIDATO (NA $ed11_c_descr - $ed10_c_abrev)";
           }
           if ($mostra == true) {
             echo "<option value='$ed47_i_codigo'> $ed47_i_codigo - $ed47_v_nome ---> $sitdescr</option>\n";
           }
         }

       }
    ?>
      </select>
     </td>
     <td align="center">
      <br>
       <table border="0">
        <tr>
         <td>
          <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_alunospossib();"
                 style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                        font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
         </td>
        </tr>
        <tr><td height="1"></td></tr>
        <tr>
         <td>
          <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
                style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                       background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;">
         </td>
        </tr>
        <tr><td height="8"></td></tr>
        <tr>
         <td>
          <hr>
         </td>
        </tr>
        <tr><td height="8"></td></tr>
        <tr>
         <td>
          <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                         background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
         </td>
        </tr>
        <tr><td height="1"></td></tr>
        <tr>
         <td>
           <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                         background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
         </td>
        </tr>
       </table>
      </td>
     <td valign="top">
      <table>
       <tr>
        <td valign="top">
          <b>Matricular na turma <?=@$ed57_c_descr?>:</b><br>
         <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
              style="font-size:9px;width:430px;height:180px" multiple>
         </select>
        </td>
       </tr>
      </table>
     </td>
    </tr>
 <?}?>

 <?if ($db_opcao == 3) {

     $exclusao = "yes";?>
     <tr>
      <td width="15%" nowrap title="<?=@$Ted60_matricula?>" colspan="3">
       <?=@$Led60_matricula?>
       <?db_input('ed60_matricula',15,$Ied60_matricula,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted60_i_aluno?>">
       <?db_ancora(@$Led60_i_aluno,"js_pesquisaed60_i_alunoexc(true);",$db_opcao1);?>
      </td>
      <td colspan="2">
       <?db_input('ed60_i_aluno',15,$Ied60_i_aluno,true,'text',$db_opcao1,
                  " onchange='js_pesquisaed60_i_alunoexc(false);'")?>
       <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led248_i_motivo?>
      </td>
      <td>
       <?
         $sql1    = "SELECT * FROM motivoexclusao order by ed249_c_motivo";
         $result1 = db_query($sql1);
         $linhas1 = pg_num_rows($result1);
       ?>
       <select name="ed248_i_motivo" style="height:16px;font-size:9px;">
        <option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
         <?
           for ($f = 0; $f < $linhas1; $f++) {
             db_fieldsmemory($result1,$f);
         ?>
             <option value="<?=$ed249_i_codigo?>" <?=@$ed248_i_motivo==$ed249_i_codigo?"selected":""?>>
               <?=$ed249_c_motivo?>
             </option>
         <?
           }
         ?>
       </select>
       <?
         if ($linhas1 == 0) {
           echo " (Cadastros-> Tabelas-> Motivo de Exclusão de Matrículas)";
         }
       ?>
      </td>
     </tr>
     <tr>
      <td>
       <?=@$Led248_t_obs?>
      </td>
      <td>
       <?db_textarea('ed248_t_obs',3,65,$Ied248_t_obs,true,'text',1,"")?>
      </td>
     </tr>
     <script>
       js_tabulacaoforms("form1","ed60_i_aluno",true,1,"ed60_i_aluno",true);
     </script>
 <?}?>

 <?if ($db_opcao == 2) {

     $exclusao = "no";?>
     <tr>
      <td width="15%" nowrap title="<?=@$Ted60_matricula?>" colspan="2">
       <?=@$Led60_matricula?>
       <?db_input('ed60_matricula',15,$Ied60_matricula,true,'text',3,"")?>
       <?=@$Led60_d_datamatricula?>
       <?db_inputdata('datamat',@$datamat_dia,@$datamat_mes,@$datamat_ano,true,'text',3,"")?>
       <?=@$Led60_d_datasaida?>
       <?db_inputdata('datasaida',@$datasaida_dia,@$datasaida_mes,@$datasaida_ano,true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted60_i_aluno?>">
       <?db_ancora(@$Led60_i_aluno,"js_pesquisaed60_i_aluno(true);",$db_opcao);?>
      </td>
      <td>
       <?db_input('ed60_i_aluno',15,$Ied60_i_aluno,true,'text',$db_opcao," onchange='js_pesquisaed60_i_aluno(false);'")?>
       <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td nowrap>
       <b>Situação Atual:</b>
      </td>
      <td>
       <?db_input('ed60_c_situacaoatual',20,'',true,'text',3,"")?>
      </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$Ted60_c_situacao?>">
       <?=@$Led60_c_situacao?>
      </td>
      <td>
       <?
         $x = array();
         db_select('ed60_c_situacao', $x ,
                   true,
                   $db_opcao,
                   " disabled onchange='js_eliminamov(this.value);js_procuraTurmaCorreta()'"
                   );
       ?>
       <span id="eliminar" style="visibility:hidden;">
        <input type="checkbox" name="eliminamov" value=""> Excluir movimentação anterior
       </span>
      </td>
     </tr>
     <tr id='turmaCorreta' style='display: none'>
       <td>
         <?db_ancora("<b>Nova Turma:</b>", "js_showTurmaCorreta()", $db_opcao);?>
       </td>
       <td>
       <?db_input('ed57_i_codigo',15, $Ied57_i_codigo,true,'text', 1, '', "ed57_novaturma")?>
       <?db_input('ed57_c_descr',20, @$Ied57_c_descr,true,'text',3,'', 'ed57_nometurma')?>
       </td>
     </tr>
     <tr id='importarAproveitamento' style='display: none'>
       <td>
         <b>Importar Aproveitamento:</b>
       </td>
       <td>
       <?
         db_select("cboImportarAproveitamento", array(0 =>"selecione", 1 => "Sim", 2 => "Não"), true, 1);
       ?>
       </td>
     </tr>
     <script>
      js_tabulacaoforms("form1","ed60_i_aluno",true,1,"ed60_i_aluno",true);
     </script>
 <?}?>

 <?if ($db_opcao == 1) {?>
     <tr>
      <td nowrap title="<?=@$Ted60_d_datamatricula?>" colspan="2">
       <?=@$Led60_d_datamatricula?>
       <?db_inputdata('ed60_d_datamatricula',@$ed60_d_datamatricula_dia,@$ed60_d_datamatricula_mes,
                      @$ed60_d_datamatricula_ano,true,'text',$db_opcao,""
                     )
       ?>
      </td>
     </tr>
 <?}?>

 <?if ($db_opcao == 2) { ?>
     <tr>
      <td nowrap title="<?=@$Ted60_d_datamodif?>">
       <?=@$Led60_d_datamodif?>
      </td>
      <td>
       <?db_inputdata('ed60_d_datamodif',@$ed60_d_datamodif_dia,@$ed60_d_datamodif_mes,
                      @$ed60_d_datamodif_ano,true,'text',$db_opcao,""
                     )
       ?>
      </td>
     </tr>
 <?}?>

 <?
   if (isset($chavepesquisa)) {

     $data      = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;
     $datamodif = @$ed60_d_datamodif_ano."-".@$ed60_d_datamodif_mes."-".@$ed60_d_datamodif_dia;
     $inicio    = @$ed52_d_inicio;
     $fim       = @$ed52_d_fim;

   } else {

     $data      = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;
     $datamodif = @$ed60_d_datamodif_ano."-".@$ed60_d_datamodif_mes."-".@$ed60_d_datamodif_dia;
     $inicio    = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;
     $fim       = @$ed60_d_datamatricula_ano."-".@$ed60_d_datamatricula_mes."-".@$ed60_d_datamatricula_dia;

 }
 ?>
 <tr>
  <td>
   <iframe name="verifmatricula" id="verifmatricula" src="" width="0" height="0"
           style="visibility:hidden;position:absolute;">
   </iframe>
  </td>
 </tr>
</table>
<?
if (isset($ed60_i_turma)) {

  $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query("","ed220_i_codigo",""," ed220_i_turma = $ed60_i_turma");
  $rsTurmaSerieRegimeMat   = $clturmaserieregimemat->sql_record($sSqlTurmaSerieRegimeMat);
  $iNumEtapas              = $clturmaserieregimemat->numrows;

} else {
  $iNumEtapas = 0;
}
?>
  <input name="ed57_i_escola" type="hidden" value="<?=@$ed57_i_escola?>">
  <input name="ed57_i_base" type="hidden" value="<?=@$ed57_i_base?>">
  <input name="ed57_i_calendario"  id="ed57_i_calendario" type="hidden" value="<?=@$ed57_i_calendario?>">
  <input name="codetapa" type="hidden" value="<?=@$codetapa?>">
  <input name="ed57_i_turno" type="hidden" value="<?=@$ed57_i_turno?>">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterarnada":"excluir"))?>"
         type="submit" id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                <?=($db_botao==false?"disabled":"")?>
                <?=($db_opcao==1?"onclick=\"return js_selecionar('$data','$inicio','$fim',$iNumEtapas)\"":"")?>
                <?=($db_opcao==2?"onclick=\"return js_conferemov('$datamodif','$inicio','$fim');\"":"")?>
                <?=($db_opcao==3?'onclick="return js_confirmarExclusao();"' : '')?>>
  <input name="alterar" type="submit" value="Alterar" style="visibility:hidden;position:absolute;">
  <iframe name="iframe_confere" src="edu1_matricula007.php" width="0" height="0" frameborder="0"
        style="visibility:hidden;position:absolute;">
  </iframe>
 </form>
</center>
<script>
function js_pesquisaed60_i_aluno(mostra) {

  if (document.form1.ed60_i_turma.value == "") {

    alert("Informe a Turma!");
    document.form1.ed60_i_aluno.value                 = '';
    document.form1.ed60_i_turma.style.backgroundColor = '#99A9AE';
    document.form1.ed60_i_turma.focus();

  } else {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_aluno',
    	                  'func_matricula.php?excluir=<?=@$exclusao?>&turma='+document.form1.ed60_i_turma.value+
    	                  '&funcao_js=parent.js_mostraaluno1|ed60_matricula|ed60_i_aluno|ed47_v_nome|ed60_c_situacao|'+
    	                  'ed60_d_datamatricula|ed60_d_datasaida','Pesquisa de Alunos',true
    	                 );

    } else {

      if (document.form1.ed60_i_aluno.value != '') {

        js_OpenJanelaIframe('','db_iframe_aluno',
                            'func_matricula.php?excluir=<?=@$exclusao?>&turma='+document.form1.ed60_i_turma.value+
                            '&pesquisa_chave='+document.form1.ed60_i_aluno.value+
                            '&funcao_js=parent.js_mostraaluno','Pesquisa',false
                           );

      } else {

        document.form1.ed47_v_nome.value          = '';
        document.form1.ed60_matricula.value        = '';
        document.form1.ed60_c_situacaoatual.value = '';
        document.form1.datamat.value              = '';
        document.form1.datasaida.value            = '';
        document.form1.alterarnada.disabled       = true;
        document.form1.alterar.disabled           = true;

      }

    }

  }

}

function js_mostraaluno(chave1,chave2,chave3,chave4,chave5,erro) {

  document.form1.ed47_v_nome.value          = chave1;
  document.form1.ed60_matricula.value        = chave2;
  document.form1.ed60_c_situacaoatual.value = chave3;

  if (chave4 != "") {
    document.form1.datamat.value = chave4.substr(8,2)+"/"+chave4.substr(5,2)+"/"+chave4.substr(0,4);
  }

  if(chave5 != "") {
    document.form1.datasaida.value = chave5.substr(8,2)+"/"+chave5.substr(5,2)+"/"+chave5.substr(0,4);
  }

  if (erro == true) {

    document.form1.ed60_i_aluno.focus();
    document.form1.ed60_i_aluno.value         = '';
    document.form1.ed60_matricula.value        = '';
    document.form1.ed60_c_situacaoatual.value = '';
    document.form1.datamat.value              = '';
    document.form1.datasaida.value            = '';
    document.form1.alterarnada.disabled       = true;
    document.form1.alterar.disabled           = true;

  } else {

    document.form1.alterarnada.disabled = false;
    document.form1.alterar.disabled     = false;
    js_situacao(chave3);

  }

}

function js_mostraaluno1(chave1,chave2,chave3,chave4,chave5,chave6) {

  document.form1.ed60_matricula.value        = chave1;
  document.form1.ed60_i_aluno.value         = chave2;
  document.form1.ed47_v_nome.value          = chave3;
  document.form1.ed60_c_situacaoatual.value = chave4;

  if (chave5 != "") {
    document.form1.datamat.value = chave5.substr(8,2)+"/"+chave5.substr(5,2)+"/"+chave5.substr(0,4);
  }

  if (chave6 != "") {
    document.form1.datasaida.value = chave6.substr(8,2)+"/"+chave6.substr(5,2)+"/"+chave6.substr(0,4);
  }

  document.form1.alterarnada.disabled = false;
  document.form1.alterar.disabled     = false;
  js_situacao(chave4);
  db_iframe_aluno.hide();

}

function js_pesquisaed60_i_alunoexc(mostra) {

  if (document.form1.ed60_i_turma.value == "") {

    alert("Informe a Turma!");
    document.form1.ed60_i_aluno.value = '';
    document.form1.ed60_i_turma.style.backgroundColor='#99A9AE';
    document.form1.ed60_i_turma.focus();

  } else {

    if (mostra == true) {

      js_OpenJanelaIframe('','db_iframe_aluno',
    	                  'func_matricula.php?excluir=<?=@$exclusao?>&turma='+document.form1.ed60_i_turma.value+
    	                  '&funcao_js=parent.js_mostraalunoexc1|ed60_matricula|ed60_i_aluno|ed47_v_nome|ed60_c_situacao',
    	                  'Pesquisa de Alunos',true,0,0
    	                 );

    } else {

      if (document.form1.ed60_i_aluno.value != '') {

        js_OpenJanelaIframe('','db_iframe_aluno',
                            'func_matricula.php?excluir=<?=@$exclusao?>&turma='+document.form1.ed60_i_turma.value+
                            '&pesquisa_chave='+document.form1.ed60_i_aluno.value+'&funcao_js=parent.js_mostraalunoexc',
                            'Pesquisa',false
                           );

      } else {
        document.form1.ed47_v_nome.value = '';
      }

    }

  }

}

function js_mostraalunoexc(chave1,chave2,chave3,erro) {

  document.form1.ed47_v_nome.value = chave1;
  document.form1.ed60_matricula.value = chave2;

  if (erro == true) {

    document.form1.ed60_i_aluno.focus();
    document.form1.ed60_i_aluno.value = '';
    document.form1.excluir.disabled   = true;

  } else {

    document.form1.excluir.disabled = false;
    iframe_confere.location.href    = "edu1_matricula007.php?matricula_exc&aluno="+document.form1.ed60_i_aluno.value+
                                      "&turma="+document.form1.ed60_i_turma.value;

  }

}

function js_mostraalunoexc1(chave1,chave2,chave3,chave4) {

  document.form1.ed60_matricula.value = chave1;
  document.form1.ed60_i_aluno.value  = chave2;
  document.form1.ed47_v_nome.value   = chave3;
  document.form1.excluir.disabled    = false;
  iframe_confere.location.href       = "edu1_matricula007.php?matricula_exc&aluno="+document.form1.ed60_i_aluno.value+
                                       "&turma="+document.form1.ed60_i_turma.value;
  db_iframe_aluno.hide();

}

function js_pesquisaed60_i_turma() {

  js_OpenJanelaIframe('','db_iframe_turma',
                     'func_turma.php?funcao_js=parent.js_preenchepesquisaturma|ed57_i_codigo&turmasprogressao=f',
		              'Pesquisa de Turmas',true,0,0
		             );

}

function js_preenchepesquisaturma(chave) {

  db_iframe_turma.hide();
  <?
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>

}

function js_calcvagas() {

  if (document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value < 0) {
    document.form1.restantes.value = 0;
  } else {
    document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
  }

}


function js_situacao(atual) {

  var F = document.getElementById("ed60_c_situacao");

  for (i = 0; i < F.length; i++) {
    F.options[i] = null;
  }
  atual = atual.replace(/^\s+|\s+$/g, '');

  if (atual == "MATRICULADO") {
    opcoes = new Array("CANCELADO|CANCELADO",
                       "EVADIDO|EVADIDO",
                       "FALECIDO|FALECIDO",
                       "MATRICULA TRANCADA|MATRICULA TRANCADA",
                       "MATRICULA INDEFERIDA|MATRICULA INDEFERIDA",
                        "MATRICULA INDEVIDA|MATRICULA INDEVIDA"
                       );
  } else if (atual == "CANCELADO") {
    opcoes = new Array("MATRICULADO|RETORNO","EVADIDO|EVADIDO","FALECIDO|FALECIDO");
  } else if (atual == "EVADIDO") {
    opcoes = new Array("MATRICULADO|RETORNO","FALECIDO|FALECIDO");
  } else if (atual == "FALECIDO") {
    opcoes = new Array("MATRICULADO|RETORNO","CANCELADO|CANCELADO","EVADIDO|EVADIDO");
  } else if (atual == "MATRICULA TRANCADA") {
    opcoes = new Array("MATRICULADO|RETORNO","EVADIDO|EVADIDO","FALECIDO|FALECIDO");
  } else if (atual == "MATRICULA INDEFERIDA") {
    opcoes = new Array("MATRICULADO|RETORNO","EVADIDO|EVADIDO","FALECIDO|FALECIDO");
  } else if (atual == "MATRICULA INDEVIDA") {
    opcoes = new Array("CANCELADO|CANCELADO", "EVADIDO|EVADIDO", "FALECIDO|FALECIDO");
  }

  for (i = 0; i < opcoes.length; i++) {

    v_array = opcoes[i].split("|");
    document.form1.elements["ed60_c_situacao"].options[i] = new Option(v_array[1],v_array[0]);

    if (v_array[0] == atual) {
      F.options[i] = null;
    }

  }

  for (i = 0; i < F.length; i++) {

    if (F.options[i].text == atual) {
      F.options[i] = null;
    }

  }

  document.form1.ed60_c_situacao.disabled = false;
  if (F.options[0].value == "MATRICULADO") {

    document.getElementById("eliminar").style.visibility = "visible";
    document.form1.eliminamov.checked                    = true;

  }

}

function js_alunospossib() {

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;

  for (x = 0; x < Tam; x++) {

    if (F.alunospossib.options[x].selected == true) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[x].text,
    	                                                                                F.alunospossib.options[x].value)
      F.alunospossib.options[x] = null;
      Tam--;
      x--;

    }

  }
  if (document.form1.alunospossib.length > 0) {
    document.form1.alunospossib.options[0].selected = true;
  } else {

    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;

  }

  document.form1.incluir.disabled = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunospossib.focus();

}

function js_incluirtodos() {

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;

  for (i = 0; i < Tam; i++) {

    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[0].text,
    	                                                                               F.alunospossib.options[0].value);
    F.alunospossib.options[0] = null;

  }
  document.form1.incluirum.disabled = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.incluir.disabled = false;
  document.form1.alunos.focus();

}

function js_excluir() {

  var F = document.getElementById("alunos");
  Tam   = F.length;

  for (x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[x].text,
    	                                                                                   F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;

    }

  }

  if (document.form1.alunos.length > 0) {
    document.form1.alunos.options[0].selected = true;
  }

  if (F.length == 0) {

    document.form1.incluir.disabled      = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;

  }

  document.form1.incluirtodos.disabled = false;
  document.form1.alunos.focus();

}

function js_excluirtodos() {

  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");

  for (i = 0; i < Tam; i++) {

    document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[0].text,
    	                                                                                 F.options[0].value);
    F.options[0] = null;

  }

  if (F.length == 0) {

    document.form1.incluir.disabled      = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;

  }

  document.form1.alunospossib.focus();

}

function js_selecionar(data,inicio,fim,numetapas) {

  if (document.form1.ed60_d_datamatricula.value == "") {

    alert("Informe a data para matricular o aluno!");
    document.form1.ed60_d_datamatricula.focus();
    document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
    return false;

  } else {

    datamat = document.form1.ed60_d_datamatricula_ano.value+"-"+document.form1.ed60_d_datamatricula_mes.value+
              "-"+document.form1.ed60_d_datamatricula_dia.value;
    dataini = inicio;
    datafim = fim;
    check   = js_validata(datamat,dataini,datafim);

    if (check == false) {

      data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
      data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
      alert("Data da matrícula fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
      document.form1.ed60_d_datamatricula.focus();
      document.form1.ed60_d_datamatricula.style.backgroundColor='#99A9AE';
      return false;

    }

  }

  var F = document.getElementById("alunos").options;
  for (var i = 0; i < F.length; i++) {
    F[i].selected = true;
  }

  if (F.length > document.form1.restantes.value) {

    alert("Número de alunos selecionados é maior que as vagas disponíveis!");
    return false;

  }

  if (numetapas > 1) {

    alunos = "";
    sep    = "";

    for (var i = 0; i < F.length; i++) {

      alunos += sep+F[i].value;
      sep     = ",";

    }

    js_OpenJanelaIframe('','db_iframe_matric','edu1_matriculaetapas001.php?turma='+document.form1.ed60_i_turma.value+
    	                '&codalunos='+alunos+'&datamat='+document.form1.ed60_d_datamatricula.value,
    	                'Matrícular Alunos',true
    	               );
    return false;

  }

  return true;

}

function js_desabinc() {

  for (i = 0; i < document.form1.alunospossib.length; i++) {

    if (document.form1.alunospossib.length > 0 && document.form1.alunospossib.options[i].selected) {

      if (document.form1.alunos.length > 0) {
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;

    }

  }

}

function js_desabexc() {

  for (i = 0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {

      if (document.form1.alunospossib.length > 0) {
        document.form1.alunospossib.options[0].selected = false;
      }

      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;

    }

  }

}

function js_conferemov(data,inicio,fim) {


  if (($F('ed60_c_situacao') == 'MATRICULA INDEVIDA') && $F('ed57_novaturma') == ''){

    alert('Selecione uma nova turma para alterar.');
    return false;
  }

  if (document.form1.ed60_d_datamodif.value == "") {

    alert("Informe a Data de Modificação!");
    document.form1.ed60_d_datamodif.focus();
    document.form1.ed60_d_datamodif.style.backgroundColor = '#99A9AE';
    return false;

  } else {

    datamodif = document.form1.ed60_d_datamodif_ano.value+"-"+document.form1.ed60_d_datamodif_mes.value+
                "-"+document.form1.ed60_d_datamodif_dia.value;
    dataini   = inicio;
    datafim   = fim;
    check     = js_validata(datamodif,dataini,datafim);

    if (check == false) {

      data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
      data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
      alert("Data de modificação fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
      document.form1.ed60_d_datamodif.focus();
      document.form1.ed60_d_datamodif.style.backgroundColor = '#99A9AE';
      return false;

    }

    datamat    = document.form1.datamat.value;
    datamat    = datamat.substr(6,4)+""+datamat.substr(3,2)+""+datamat.substr(0,2);
    datamodif2 = document.form1.ed60_d_datamodif.value;
    datamodif2 = datamodif2.substr(6,4)+""+datamodif2.substr(3,2)+""+datamodif2.substr(0,2);

    if (parseInt(datamodif2) < parseInt(datamat)) {

      alert("Data de Modificação menor que a Data da Matrícula!");
      document.form1.ed60_d_datamodif.focus();
      document.form1.ed60_d_datamodif.style.backgroundColor='#99A9AE';
      return false;

    }

    if (document.form1.datasaida.value != "") {

      datasaida = document.form1.datasaida.value;
      datasaida = datasaida.substr(6,4)+""+datasaida.substr(3,2)+""+datasaida.substr(0,2);

      if (parseInt(datamodif2) < parseInt(datasaida)) {

        alert("Data de Modificação menor que a Data da Saída!");
        document.form1.ed60_d_datamodif.focus();
        document.form1.ed60_d_datamodif.style.backgroundColor='#99A9AE';
        return false;

      }

    }

    data                         = document.form1.ed60_d_datamodif_ano.value+
                                   "-"+document.form1.ed60_d_datamodif_mes.value+
                                   "-"+document.form1.ed60_d_datamodif_dia.value;
    iframe_confere.location.href = "edu1_matricula007.php?matricula="+document.form1.ed60_matricula.value+
                                   "&situacao="+document.form1.ed60_c_situacao.value+"&data="+data;

  }

  return false;

}

function js_eliminamov(valor) {

  if (valor == "MATRICULADO") {

    document.getElementById("eliminar").style.visibility = "visible";
    document.form1.eliminamov.checked                    = true;

  } else {

    document.getElementById("eliminar").style.visibility = "hidden";
    document.form1.eliminamov.checked                    = false;

  }

}

<?
  if ($db_opcao == 1 && isset($chavepesquisa)) {?>

    if (document.form1.alunospossib.length == 0) {
      document.form1.incluirtodos.disabled = true;
    }

<?}?>

if (document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value < 0) {
  document.form1.restantes.value = 0;
} else {
  document.form1.restantes.value = document.form1.ed57_i_numvagas.value-document.form1.ed57_i_nummatr.value;
}

<?
if ($db_opcao == 1 && isset($chavepesquisa)) {?>

  if (document.form1.restantes.value == 0) {

    alert("Não há vagas disponíveis nesta turma!");
    document.form1.incluirtodos.disabled         = true;
    document.form1.alunospossib.disabled         = true;
    document.form1.alunos.disabled               = true;
    document.form1.alunospossib.style.background = "#CCCCCC";
    document.form1.alunos.style.background       = "#CCCCCC";

  }

<?
}
?>

function js_confirmarExclusao() {

 return confirm('A classificação da turma terá sua sequência reiniciada caso a numeração já tenha sido gerada, '+
                'de forma a acomodar as demais matrículas no espaço vago deixado por esta exclusão.'+
                '\nConfirmar exclusão?'
               );
}

function js_showTurmaCorreta() {


  js_OpenJanelaIframe('',
                     'db_iframe_turmas',
                     'func_alunocursoalterarturma.php?aluno='+$F('ed60_i_aluno')+
                     '&iCalendario='+$F('ed57_i_calendario')+'&funcao_js=parent.js_preencheTurmaNova|ed57_i_codigo|ed57_c_descr',
                      'Trocar Turma',
                      true
                     );

  $('Jandb_iframe_turmas').style.zIndex = '100000';
}

function js_preencheTurmaNova(iCodigo, sDescricao) {

   $('ed57_novaturma').value = iCodigo;
   $('ed57_nometurma').value = sDescricao;
   db_iframe_turmas.hide();
}

function js_procuraTurmaCorreta() {

  if ($F('ed60_c_situacao') == 'MATRICULA INDEVIDA') {

    $('turmaCorreta').style.display           = 'table-row';
  } else {

   $('ed57_novaturma').value                 = '';
   $('ed57_nometurma').value                 = '';
   $('turmaCorreta').style.display           = 'none';
  }
}
</script>