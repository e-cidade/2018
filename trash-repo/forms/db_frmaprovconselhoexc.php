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
$claprovconselho->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed95_i_codigo");
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("nome");
if(isset($escolhido) && $escolhido !=""){
 $campos_exc = "aprovconselho.*,
                case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,
                case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao,
                db_usuarios.nome";
 $result4 = $claprovconselho->sql_record($claprovconselho->sql_query("",$campos_exc,""," ed95_i_regencia = $regencia AND ed253_i_diario = $escolhido AND ed95_c_encerrado = 'N'"));
 @db_fieldsmemory($result4,0);
}
?>
<center>
<form name="form1" method="post" action="">
<?
$result3 = $claprovconselho->sql_record($claprovconselho->sql_query("","ed253_i_diario,ed47_v_nome","to_ascii(ed47_v_nome)"," ed95_i_regencia = $regencia AND ed95_c_encerrado = 'N'"));
if($claprovconselho->numrows>0){
 ?>
 <table border="0">
  <?db_input('ed253_i_codigo',20,$Ied253_i_codigo,true,'hidden',$db_opcao,"")?>
  <tr>
   <td>
    <b>Aluno:</b>
   </td>
   <td>
    <select name="ed253_i_diario" onchange="location.href='edu1_aprovconselho003.php?regencia=<?=$regencia?>&escolhido='+this.value+'&iTrocaTurma=<?=$iTrocaTurma?>'">
     <option value=""></option>
     <?
     for($t=0;$t<$claprovconselho->numrows;$t++){
      db_fieldsmemory($result3,$t);
      $selected = $ed253_i_diario==@$escolhido?"selected":"";
      echo "<option value='$ed253_i_diario' $selected>$ed47_v_nome</option>";
     }
     ?>
    </select>
   </td>
  </tr>
  <?if(isset($escolhido) && $escolhido !=""){
  $db_botao = true;
  ?>
  <tr>
   <td nowrap title="<?=@$Ted253_t_obs?>">
    <?=@$Led253_t_obs?>
   </td>
   <td>
    <?db_textarea('ed253_t_obs',3,60,$Ied253_t_obs,true,'text',$db_opcao,"")?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted253_i_rechumano?>">
    <?db_ancora(@$Led253_i_rechumano,"",$db_opcao);?>
   </td>
   <td>
    <?db_input('ed253_i_rechumano',20,$Ied253_i_rechumano,true,'hidden',$db_opcao,"")?>
    <?db_input('identificacao',20,@$identificacao,true,'text',3,'')?>
    <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted253_i_data?>">
    <?=@$Led253_i_data?>
   </td>
   <td>
    <?
    $ed253_i_data = date("d/m/Y H:i:s",$ed253_i_data);
    db_input('ed253_i_data',20,$Ied253_i_data,true,'text',$db_opcao,"")
    ?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted253_i_usuario?>">
    <?=@$Led253_i_usuario?>
   </td>
   <td>
    <?db_input('nome',50,$Inome,true,'text',$db_opcao,"")?>
   </td>
  </tr>
  <?php
  $oDaoAprovConselhoTipo = db_utils::getDao("aprovconselhotipo");
  $sSqlAprovTipo         = $oDaoAprovConselhoTipo->sql_query_file();
  $rsAprovConselhoTipo   = $oDaoAprovConselhoTipo->sql_record($sSqlAprovTipo);
  $aTipos = array();
  for ($i = 0; $i < $oDaoAprovConselhoTipo->numrows; $i++) {

    $oDados = db_utils::fieldsmemory($rsAprovConselhoTipo, $i);
    $aTipos[$oDados->ed122_sequencial] = $oDados->ed122_descricao;
  }
  ?>
   <tr>
     <td>
      <b>Forma de Aprovação:</b>
     </td>
     <td>
     <?php
      db_select("ed253_aprovconselhotipo", $aTipos, true);
     ?>
     </td>
   </tr>
  <tr>
   <td colspan="2" align="center">
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   </td>
  </tr>
  <?}?>
 </table>
 <?
}else{
 ?>
 <table border="0">
  <?db_input('ed253_i_codigo',20,$Ied253_i_codigo,true,'hidden',$db_opcao,"")?>
  <tr>
   <td>
    <b>Nenhum aluno não encerrado teve o resultado final alterado nesta disciplina.</b>
   </td>
  </tr>
 </table>
 <?
}
?>
</center>
</form>