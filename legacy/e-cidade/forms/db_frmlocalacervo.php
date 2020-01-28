<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: biblioteca
$cllocalacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi09_nome");
$clrotulo->label("bi06_titulo");
$clrotulo->label("bi09_biblioteca");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td>
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Tbi20_codigo?>">
      <?=@$Lbi20_codigo?>
     </td>
     <td>
      <?db_input('bi20_codigo',10,$Ibi20_codigo,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tbi20_acervo?>">
      <?=@$Lbi20_acervo?>
     </td>
     <td>
      <?db_input('bi20_acervo',10,$Ibi20_acervo,true,'text',3,"")?>
      <?db_input('bi06_titulo',30,$Ibi06_titulo,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tbi20_localizacao?>">
      <?db_ancora(@$Lbi20_localizacao,"js_pesquisabi20_localizacao(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('bi20_localizacao',10,$Ibi20_localizacao,true,'text',$db_opcao," onchange='js_pesquisabi20_localizacao(false);'")?>
      <?db_input('bi09_nome',30,@$Ibi09_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="Ordem sequencia">
      <?=@$Lbi20_sequencia?>
     <td>
      <?db_input('bi20_sequencia',10,$Ibi20_sequencia,true,'text',$db_opcao)?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?
 if(isset($chavepesquisa)){
  $novo = false;
  ?>
  <tr>
   <td height="15" colspan="2"><hr></td>
  </tr>
  <tr>
   <td valign="top">
    <?if($clexemplar->numrows>1){?>
     <b><?=$bi20_acervo?> - <?=$bi06_titulo?>:</b><br>
     <table border="1" cellspacing="0" cellpading="2" width="95%" bgcolor="#f3f3f3">
      <tr align="center">
       <td>Exemplar</td>
       <td>Situação</td>
       <td>Letra</td>
      </tr>
      <?
      $alfabeto = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
      $sql = "SELECT bi23_codigo,bi23_codbarras,bi23_situacao,bi27_letra,bi27_exemplar,bi27_codigo
              FROM exemplar
               left join localexemplar on bi27_exemplar = bi23_codigo
               left join localacervo on bi20_codigo = bi27_localacervo
              WHERE bi23_acervo = $bi20_acervo
              AND bi23_situacao = 'S'
              ORDER BY bi20_sequencia,bi27_letra,bi23_codigo
             ";
      $result= db_query($sql);
      $linhas= pg_num_rows($result);
      for($i=0;$i<$linhas;$i++) {
       db_fieldsmemory($result,$i);
       $valorletra = $bi27_letra==""?$alfabeto[$i]:$bi27_letra;
       ?>
       <tr>
        <td>-> <?=$bi23_codigo." - ".$bi23_codbarras?></td>
        <td align="center"><?=$bi23_situacao=="S"?"ATIVO":"INATIVO"?></td>
        <td align="center">
         <input type="text" id="bi_letra" name="bi_letra[]" size="1" value="<?=$valorletra?>" style="text-transform:uppercase;font-weight:bold;text-align:center" readonly>
         <input type="hidden" id="bi_exemplar" name="bi_exemplar[]" value="<?=$bi23_codigo?>">
         <input type="hidden" id="bi_codigo" name="bi_codigo[]" value="<?=$bi27_codigo?>">
        </td>
       </tr>
       <?
      }
      for($i=0;$i<$linhas;$i++) {
       db_fieldsmemory($result,$i);
       if($bi27_letra==""){

        $novo = true;
        break;
       }
      }
      ?>
     </table>
     <?
    }
    $result = $cllocalexemplar->sql_record($cllocalexemplar->sql_query("","bi09_capacidade as capacidade,bi06_seq,bi20_sequencia,bi27_letra,bi06_titulo,bi23_codbarras","bi20_sequencia,bi27_letra"," bi20_localizacao=$chavepesquisa AND bi23_situacao = 'S'"));
    $linhas = $cllocalexemplar->numrows;
    if($linhas>0){
     $capacidade = pg_result($result,0,'capacidade');
     if($linhas>=$capacidade){
      $db_botao = false;
      db_msgbox("Esta localização já está com a capacidade de exemplares esgotada.\\nCapacicade: ($capacidade) Exemplares: ($linhas)");
     }
    }
    ?>
    <br>
    <input type="submit" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_valida();">
    <input type="hidden" name="chavepesquisa" value="<?=$chavepesquisa?>">
   </td>
   <td valign="top">
    <table>
     <tr>
      <td>
       <b>Ordem -> Exemplares em <?=$bi09_nome?>:</b><br>
       <select name="localacervo" id="localacervo" size="10" style="font-size:9px;width:330px;height:180px" multiple>
        <?
        if($linhas>0){
         for($i=0;$i<$linhas;$i++) {
          db_fieldsmemory($result,$i);
          $sequencia = $bi20_sequencia.(trim($bi27_letra)==""?"":"-$bi27_letra");
          echo "<option value='$bi06_seq' ".($bi20_acervo==$bi06_seq?"selected":"").">$sequencia -> $bi06_titulo - $bi23_codbarras </option>\n";
         }
        }else{
          echo "<option value=''>Nenhum acervo nesta localização.</option>\n";
        }
        ?>
       </select>
      </td>
     </tr>
    </table>
    <?
    if($novo==true || ($db_opcao==1 && !isset($incluir)) ){
     ?>
     <input type="hidden" id="nova_letra" name="nova_letra" value="<?=$novo?>">
     <script>document.getElementById("db_opcao").click();</script>
     <?
    }
    ?>
   </td>
  </tr>
  <?
 }
 ?>
</table>
</center>
</form>
<script>
function js_pesquisabi20_localizacao(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_localizacao','func_localizacao.php?funcao_js=parent.js_preenchepesquisa|bi09_codigo','Pesquisa',true);
 }else{
  if(document.form1.bi20_localizacao.value != ''){
   js_OpenJanelaIframe('','db_iframe_localizacao','func_localizacao.php?pesquisa_chave='+document.form1.bi20_localizacao.value+'&funcao_js=parent.js_mostralocalizacao','Pesquisa',false);
  }else{
   document.form1.bi09_nome.value = '';
  }
 }
}
function js_mostralocalizacao(chave,erro){
 document.form1.bi09_nome.value = chave;
 if(erro==true){
  document.form1.bi20_localizacao.focus();
  document.form1.bi20_localizacao.value = '';
 }else{
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?bi20_acervo=$bi20_acervo&bi06_titulo=$bi06_titulo&chavepesquisa='+document.form1.bi20_localizacao.value";
  ?>
 }
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_localacervo','func_localacervo.php?funcao_js=parent.js_preenchepesquisa|bi20_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_localizacao.hide();
 <?
 echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?bi20_acervo=$bi20_acervo&bi06_titulo=$bi06_titulo&chavepesquisa='+chave";
 ?>
}
function js_valida(){
 if(document.form1.bi_letra){
  qtde = document.form1.bi_letra.length;
  branco = false;
  for(i=0;i<qtde;i++){
   if(document.form1.bi_letra[i].value==""){
    branco = true;
    exemplar = document.form1.bi_exemplar[i].value;
    break;
   }
  }
  if(branco==true){
   alert("Campo Letra não informado para o exemplar "+exemplar);
   return false;
  }
 } 
 return true;
}
</script>