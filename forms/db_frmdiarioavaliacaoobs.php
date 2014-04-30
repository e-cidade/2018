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
$clrotulo = new rotulocampo;
$clrotulo->label("ed72_i_codigo");
$clrotulo->label("ed72_t_obs");
$result_obs = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed72_t_obs,ed59_i_serie",""," ed72_i_codigo = $ed93_i_diarioavaliacao"));
db_fieldsmemory($result_obs,0);
?>
<form name="form1" method="post" action="">
<table border="1" width="100%" cellspacing="0" cellpading="0">
 <tr>
  <td class='titulo' colspan="3">
   Observações para o aluno <?=$aluno?> no período <?=$periodo?>
  </td>
 </tr>
 <tr>
  <td colspan="3" align="center">
   <b>Observações</b>:
   <br>
   <?db_textarea('ed72_t_obs',5,120,@$Ied72_t_obs,true,'text',$db_opcao,@$encerrado=="S"?"readonly onclick=\"alert('Aluno possui avaliações encerradas para esta disciplina!')\"":"")?>
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
    <b>Selecione outras disciplinas para conter<br>esta observação no período <?=$periodo?></b>:<br>
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
<input name="ed93_i_diarioavaliacao" type="hidden" value="<?=$ed93_i_diarioavaliacao?>">
<input name="aluno" type="hidden" value="<?=$aluno?>">
<input name="periodo" type="hidden" value="<?=$periodo?>">
<input name="faltas" type="hidden" value="<?=$faltas?>">
<input name="turma" type="hidden" value="<?=$turma?>">
<input name="codaluno" type="hidden" value="<?=$codaluno?>">
<input name="codperiodo" type="hidden" value="<?=$codperiodo?>">
<input name="nota" type="hidden" value="<?=@$nota?>">
<input name="parecer" type="hidden" value="<?=@$parecer?>">
<input name="conceito" type="hidden" value="<?=@$conceito?>">
</form>