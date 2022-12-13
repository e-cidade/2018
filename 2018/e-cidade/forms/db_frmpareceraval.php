<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
$clpareceraval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed72_i_codigo");
$clrotulo->label("ed92_i_codigo");
$clrotulo->label("ed91_i_codigo");
$clrotulo->label("ed72_t_parecer");
$result = $clpareceraval->sql_record($clpareceraval->sql_query("","*",""," ed93_i_diarioavaliacao = $ed93_i_diarioavaliacao"));
if($clpareceraval->numrows>0){
 db_fieldsmemory($result,0);
}
?>
<form name="form1" method="post" action="">
<fieldset style="width:95%"><legend><b>Parecer Padronizado</b></legend>
 <table border="1" width="100%" cellspacing="0" cellpading="0">
 <?
  $escola = db_getsession("DB_coddepto");
  $result = $clparecerturma->sql_record($clparecerturma->sql_query("","*","ed92_i_sequencial"," ed105_i_turma = $turma"));
  $cor1 = "#f3f3f3";
  $cor2 = "#DBDBDB";
  $cor = "";
  $ed93_i_parecerlegenda = "";
  if($clparecerturma->numrows>0){
   ?>
   <tr>
    <td align="center" width="5%" class='cabec1'>&nbsp;</td>
    <td align="center" width="5%" class='cabec1'>Sequencial</td>
    <td class='cabec1'>Parecer</td>
    <td class='cabec1'>Resultado</td>
   </tr>
   <?
   for($x=0;$x<$clparecerturma->numrows;$x++){
    db_fieldsmemory($result,$x);
    $result1 = $clpareceraval->sql_record($clpareceraval->sql_query_file("","*","","ed93_i_parecer = $ed92_i_codigo AND ed93_i_diarioavaliacao = $ed93_i_diarioavaliacao"));
    if($clpareceraval->numrows>0){
     db_fieldsmemory($result1,0);
     $checked = "checked";
     $disabled = "";
    }else{
     $checked = "";
     $disabled = "disabled";
    }
    if($cor==$cor1){
     $cor = $cor2;
    }else{
     $cor = $cor1;
    }
    ?>
    <tr bgcolor="<?=$cor?>">
     <td align="center" ><input type="checkbox" name="<?=$ed92_i_codigo?>" value="ativo" <?=$checked?> onclick="js_desab(this,'ed93_i_parecerlegenda<?=$x?>')" <?=trim(@$encerrado)=="S"?"disabled":""?> style="width:12px;"></td>
     <td align="center" class='aluno'><?=$ed92_i_sequencial==""?"&nbsp;":$ed92_i_sequencial?></td>
     <td class='aluno'><?=$ed92_c_descr?></td>
     <td>
      <select name="ed93_i_parecerlegenda<?=$x?>" <?=$disabled?> <?=$encerrado=="S"?"disabled style=\"background:#f3f3f3;\"":"style=\"height:17px;font-size:10px;padding:0px;\""?>>
      <option value=""></option>
      <?
      $result_leg = $clparecerlegenda->sql_record($clparecerlegenda->sql_query("","ed91_i_codigo,ed91_c_descr","ed91_c_descr desc"," ed91_i_escola = $escola"));
      for($y=0;$y<$clparecerlegenda->numrows;$y++){
       db_fieldsmemory($result_leg,$y);
       ?>
        <option value="<?=$ed91_i_codigo?>" <?=@$ed93_i_parecerlegenda==$ed91_i_codigo?"selected":""?>><?=trim($ed91_c_descr)?></option>
       <?
      }
      ?>
      </td>
     </td>
    </tr>
    <?
    $ed93_i_parecerlegenda = "";
   }
  }
  $result = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query_file("","ed72_t_parecer",""," ed72_i_codigo = $ed93_i_diarioavaliacao"));
  db_fieldsmemory($result,0);
  ?>
 </table>
</fieldset>
<fieldset style="width:95%"><legend><b>Parecer Descritivo</b></legend>
 <table border="0" width="100%" cellspacing="0" cellpading="0">
  <tr>
   <td colspan="4" align="center">
    <br>
    <?db_textarea('ed72_t_parecer',5,120,@$Ied72_t_parecer,true,'text',$db_opcao,@$encerrado=="S"?"readonly onclick=\"alert('Aluno possui avaliações encerradas para esta disciplina!')\"":"")?>
    <br><br>
    <?
    $sql = "SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao
            FROM regencia
             inner join disciplina on ed12_i_codigo = ed59_i_disciplina
             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
            WHERE ed59_i_turma = $turma
            EXCEPT
            SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao
            FROM diarioavaliacao
             inner join diario on ed95_i_codigo = ed72_i_diario
             inner join regencia on ed59_i_codigo = ed95_i_regencia
             inner join disciplina on ed12_i_codigo = ed59_i_disciplina
             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
            WHERE ed72_i_codigo = $ed93_i_diarioavaliacao
            ORDER BY ed59_i_ordenacao
           ";
    $result = pg_query($sql);
    $linhas = pg_num_rows($result);
    if($linhas>0){
     ?>
     <b>Selecione outras disciplinas para conter<br>este parecer no período <?=$periodo?></b>:<br>
     <select name="reg_outras[]" id="reg_outras" size="10" style="width:200px;font-size:10px;padding:0px;" multiple <?=@$encerrado=="S"?"readonly onclick=\"alert('Aluno possui avaliações encerradas para esta disciplina!')\"":""?> >
     <?
     for($r=0;$r<$linhas;$r++){
      db_fieldsmemory($result,$r);
      ?>
       <option value="<?=$ed59_i_codigo?>"> <?=$ed232_c_descr?></option>
      <?
     }
     ?>
     </select>
     <br><br>
    <?}?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Salvar":"Excluir"))?>" <?=($db_botao==false||@$encerrado=="S"?"disabled":"")?> >
   </td>
  </tr>
</table>
</fieldset>
<input name="ed93_i_diarioavaliacao" type="hidden" value="<?=$ed93_i_diarioavaliacao?>">
<input name="aluno" type="hidden" value="<?=$aluno?>">
<input name="periodo" type="hidden" value="<?=$periodo?>">
<input name="campo" type="hidden" value="<?=@$campo?>">
<input name="faltas" type="hidden" value="<?=$faltas?>">
<input name="turma" type="hidden" value="<?=$turma?>">
<input name="codaluno" type="hidden" value="<?=$codaluno?>">
<input name="codperiodo" type="hidden" value="<?=$codperiodo?>">
<input name="conceito" type="hidden" value="<?=$conceito?>">
<input name="nota" type="hidden" value="<?=@$nota?>">
</form>
<script>
function js_desab(campo,legenda){
 if(campo.checked==true){
  eval('document.form1.' + legenda + '.disabled = false');
 }else{
  eval('document.form1.' + legenda + '.disabled = true');
 }
}
</script>