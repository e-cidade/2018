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
$clparecerresult->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed73_i_codigo");
$clrotulo->label("ed92_i_codigo");
$clrotulo->label("ed91_i_codigo");
$clrotulo->label("ed73_t_parecer");
?>
<fieldset style="width:95%"><legend><b>Parecer Padronizado</b></legend>
<table border="0" width="100%" cellspacing="0" cellpading="0">
 <tr>
  <td>
   <iframe name="parecer" id="parecer" src="edu1_parecerresult004.php?regencia=<?=$regencia?>&ed43_i_codigo=<?=$ed43_i_codigo?>&ed63_i_diarioresultado=<?=$ed63_i_diarioresultado?>&campo=<?=$campo?>&periodo=<?=$periodo?>&aluno=<?=$aluno?>&encerrado=<?=$encerrado?>&turma=<?=$turma?>&codaluno=<?=$codaluno?>&modelo=<?=$modelo?>" width="95%" height="180" frameborder="0"></iframe>
  </td>
 </tr>
</table>
</fieldset>
<fieldset style="width:95%"><legend><b>Parecer Descritivo</b></legend>
<form name="form1" method="post" action="" onsubmit="return js_check();">
<table border="0" width="100%" cellspacing="0" cellpading="0">
 <?
 $result = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("","ed73_t_parecer,ed59_i_serie",""," ed73_i_codigo = $ed63_i_diarioresultado"));
 db_fieldsmemory($result,0);
 ?>
 <tr>
  <td colspan="4" align="center">
  <br>
  <?db_textarea('ed73_t_parecer',5,120,@$Ied73_t_parecer,true,'text',$db_opcao,@$encerrado=="S"?"readonly onclick=\"alert('Aluno possui avaliações encerradas para esta disciplina!')\"":"")?>
   <br><br>
   <?
   $sql = "SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao
           FROM regencia
            inner join disciplina on ed12_i_codigo = ed59_i_disciplina
            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
           WHERE ed59_i_turma = $turma
           AND ed59_i_serie = $ed59_i_serie
           EXCEPT
           SELECT ed59_i_codigo,ed232_c_descr,ed59_i_ordenacao
           FROM diarioresultado
            inner join diario on ed95_i_codigo = ed73_i_diario
            inner join regencia on ed59_i_codigo = ed95_i_regencia
            inner join disciplina on ed12_i_codigo = ed59_i_disciplina
            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina
           WHERE ed73_i_codigo = $ed63_i_diarioresultado
           ORDER BY ed59_i_ordenacao
          ";
   $result = db_query($sql);
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
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar2":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Salvar":"Excluir"))?>" <?=($db_botao==false||@$encerrado=="S"?"disabled":"")?>>
  </td>
 </tr>
</table>
</fieldset>
<input name="ed63_i_diarioresultado" type="hidden" value="<?=$ed63_i_diarioresultado?>">
<input name="aluno" type="hidden" value="<?=$aluno?>">
<input name="periodo" type="hidden" value="<?=$periodo?>">
<input name="campo" type="hidden" value="<?=$campo?>">
<input name="regencia" type="hidden" value="<?=$regencia?>">
<input name="codaluno" type="hidden" value="<?=$codaluno?>">
<input name="turma" type="hidden" value="<?=$turma?>">
<input name="ed43_i_codigo" type="hidden" value="<?=$ed43_i_codigo?>">
</form>
<script>
function js_check() {
 }
</script>