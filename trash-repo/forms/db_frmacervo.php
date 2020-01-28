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

//MODULO: biblioteca
$clacervo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi09_nome");
$clrotulo->label("bi05_nome");
$clrotulo->label("bi02_nome");
$clrotulo->label("bi03_classificacao");
$clrotulo->label("bi04_forma");
$clrotulo->label("descrdepto");
$clrotulo->label("bi06_colecaoacervo");
$clrotulo->label("bi29_nome");

if(empty($bi06_dataregistro_dia)){
 $bi06_dataregistro_dia = date("d",db_getsession("DB_datausu"));
 $bi06_dataregistro_mes = date("m",db_getsession("DB_datausu"));
 $bi06_dataregistro_ano = date("Y",db_getsession("DB_datausu"));
}
?>
<form name="form1" method="post" action="" class="form-container">
  <fieldset class='separator'>
    <legend>Dados do Acervo</legend>
    <table border="0">
      <tr>
        <td nowrap="nowrap" class="field-size4" title="<?=@$Tbi06_seq?>">
         <?=@$Lbi06_seq?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_seq',10,$Ibi06_seq,true,'text',3,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_biblioteca?>">
         <?=@$Lbi06_biblioteca?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_biblioteca',10,$Ibi06_biblioteca,true,'text',3,"")?>
         <?db_input('bi17_nome',40,@$Ibi17_nome,true,'text',3,'')?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_titulo?>">
         <?=@$Lbi06_titulo?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_titulo',54,$Ibi06_titulo,true,'text',$db_opcao," onKeyPress='tab(event,15)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_dataregistro?>">
         <?=@$Lbi06_dataregistro?>
        </td>
        <td nowrap="nowrap">
         <?db_inputdata('bi06_dataregistro',@$bi06_dataregistro_dia,@$bi06_dataregistro_mes,@$bi06_dataregistro_ano,true,'text',$db_opcao," ")?>
        </td>
      </tr>
    </table>
  </fieldset>
  <fieldset class='separator'>
    <legend>Dados da Edi��o</legend>
    <table >
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_anoedicao?>">
         <?=@$Lbi06_anoedicao?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_anoedicao', 10, $Ibi06_anoedicao, true,'text',$db_opcao," onKeyPress='tab(event,15)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_edicao?>">
         <?=@$Lbi06_edicao?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_edicao',10,$Ibi06_edicao,true,'text',$db_opcao,"onKeyPress='tab(event,14)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_classcdd?>">
         <?=@$Lbi06_classcdd?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_classcdd',30,$Ibi06_classcdd,true,'text',$db_opcao," onKeyPress='tab(event,16)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_isbn?>">
         <?=@$Lbi06_isbn?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_isbn',30,$Ibi06_isbn,true,'text',$db_opcao," onKeyPress='tab(event,17)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_volume?>">
         <?=@$Lbi06_volume?>
        </td>
        <td nowrap="nowrap">
         <?db_input('bi06_volume',10,$Ibi06_volume,true,'text',$db_opcao," onKeyPress='tab(event,19)'")?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" class="field-size4" title="<?=@$Tbi06_tipoitem?>">
          <?db_ancora(@$Lbi06_tipoitem,"js_pesquisabi06_tipoitem(true);",$db_opcao);?>
        </td>
        <td nowrap="nowrap" > 
          <?db_input('bi06_tipoitem',10,$Ibi06_tipoitem,true,'text',$db_opcao," onchange='js_pesquisabi06_tipoitem(false);' onKeyPress='tab(event,4)'")?>
          <?db_input('bi05_nome',40,$Ibi05_nome,true,'text',3,'')?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_editora?>">
          <?db_ancora(@$Lbi06_editora,"js_pesquisabi06_editora(true);",$db_opcao);?>
        </td>
        <td nowrap="nowrap" > 
          <?db_input('bi06_editora',10,$Ibi06_editora,true,'text',$db_opcao," onchange='js_pesquisabi06_editora(false);' onKeyPress='tab(event,6)'")?>
          <?db_input('bi02_nome',40,$Ibi02_nome,true,'text',3,'')?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi06_classiliteraria?>">
          <?db_ancora(@$Lbi06_classiliteraria,"js_pesquisabi06_classiliteraria(true);",$db_opcao);?>
        </td>
        <td nowrap="nowrap" > 
          <?db_input('bi06_classiliteraria',10,$Ibi06_classiliteraria,true,'text',$db_opcao," onchange='js_pesquisabi06_classiliteraria(false);' onKeyPress='tab(event,8)'")?>
          <?db_input('bi03_classificacao',40,$Ibi03_classificacao,true,'text',3,'')?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" title="<?=@$Tbi29_nome?>">
          <?db_ancora("Cole��o:","js_pesquisaColecao(true);",$db_opcao);?>
        </td>
        <td nowrap="nowrap" > 
          <?db_input('bi06_colecaoacervo', 10, $Ibi06_colecaoacervo, true, 'text', $db_opcao, 
                     " onchange='js_pesquisaColecao(false);' ")?>
          <?db_input('bi29_nome', 40, $Ibi29_nome, true, 'text', 3, '')?>
        </td>
      </tr>
    </table>
  </fieldset>  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=$db_opcao==3?"onclick=\"return confirm('Ser�o apagados todos registros (Autores,Assuntos,Exemplares,Localiza��o) referentes a este acervo! Caso exista algum registro de empr�stimo ou reserva para algum exemplar deste acervo a exclus�o n�o ser� permitida. Confirma exclus�o?')\"":""?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
<input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
</form>
<script>

function js_pesquisaColecao(lMostra) {

  var sUrl = 'func_colecaoacervo.php';
   
  if (lMostra) {

    sUrl += '?funcao_js=parent.js_mostraColecao1|bi29_sequencial|bi29_nome';
    js_OpenJanelaIframe('','db_iframe_colecaoacervo', sUrl, 'Pesquisa Cole��o', true);
  } else if ($F('bi06_colecaoacervo') != '') {

    sUrl += '?pesquisa_chave='+$F('bi06_colecaoacervo');
    sUrl += '&funcao_js=parent.js_mostraColecao';
    js_OpenJanelaIframe('','db_iframe_colecaoacervo', sUrl,'Pesquisa Cole��o',false);
  } else {
    $('bi29_nome').value = '';
  }
}
function js_mostraColecao(sColecao, lErro) {

  $('bi29_nome').value = sColecao;
  if (lErro) {
    
    $('bi06_colecaoacervo').focus();
    $('bi06_colecaoacervo').value = '';
  }
}
function js_mostraColecao1(iColecao, sColecao) {
  
  $('bi06_colecaoacervo').value = iColecao;
  $('bi29_nome').value          = sColecao;
  db_iframe_colecaoacervo.hide();
}
          
function js_pesquisabi06_tipoitem(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_tipoitem','func_tipoitem.php?funcao_js=parent.js_mostratipoitem1|bi05_codigo|bi05_nome','Pesquisa',true);
 }else{
  if(document.form1.bi06_tipoitem.value != ''){
   js_OpenJanelaIframe('','db_iframe_tipoitem','func_tipoitem.php?pesquisa_chave='+document.form1.bi06_tipoitem.value+'&funcao_js=parent.js_mostratipoitem','Pesquisa',false);
  }else{
   document.form1.bi05_nome.value = '';
  }
 }
}
function js_mostratipoitem(chave,erro){
 document.form1.bi05_nome.value = chave;
 if(erro==true){
  document.form1.bi06_tipoitem.focus();
  document.form1.bi06_tipoitem.value = '';
 }
}
function js_mostratipoitem1(chave1,chave2){
 document.form1.bi06_tipoitem.value = chave1;
 document.form1.bi05_nome.value = chave2;
 db_iframe_tipoitem.hide();
}
function js_pesquisabi06_editora(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_editora','func_editora.php?funcao_js=parent.js_mostraeditora1|bi02_codigo|bi02_nome','Pesquisa',true);
 }else{
  if(document.form1.bi06_editora.value != ''){
   js_OpenJanelaIframe('','db_iframe_editora','func_editora.php?pesquisa_chave='+document.form1.bi06_editora.value+'&funcao_js=parent.js_mostraeditora','Pesquisa',false);
  }else{
   document.form1.bi02_nome.value = '';
  }
 }
}
function js_mostraeditora(chave,erro){
 document.form1.bi02_nome.value = chave;
 if(erro==true){
  document.form1.bi06_editora.focus();
  document.form1.bi06_editora.value = '';
 }
}
function js_mostraeditora1(chave1,chave2){
 document.form1.bi06_editora.value = chave1;
 document.form1.bi02_nome.value = chave2;
 db_iframe_editora.hide();
}
function js_pesquisabi06_classiliteraria(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_classiliteraria','func_classiliteraria.php?funcao_js=parent.js_mostraclassiliteraria1|bi03_codigo|bi03_classificacao','Pesquisa',true);
 }else{
  if(document.form1.bi06_classiliteraria.value != ''){
   js_OpenJanelaIframe('','db_iframe_classiliteraria','func_classiliteraria.php?pesquisa_chave='+document.form1.bi06_classiliteraria.value+'&funcao_js=parent.js_mostraclassiliteraria','Pesquisa',false);
  }else{
   document.form1.bi03_classificacao.value = '';
  }
 }
}
function js_mostraclassiliteraria(chave,erro){
 document.form1.bi03_classificacao.value = chave;
 if(erro==true){
  document.form1.bi06_classiliteraria.focus();
  document.form1.bi06_classiliteraria.value = '';
 }
}
function js_mostraclassiliteraria1(chave1,chave2){
 document.form1.bi06_classiliteraria.value = chave1;
 document.form1.bi03_classificacao.value = chave2;
 db_iframe_classiliteraria.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_acervo','func_acervo.php?funcao_js=parent.js_preenchepesquisa|bi06_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
 db_iframe_acervo.hide();
 <?
 if($db_opcao!=1){
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
 }
 ?>
}

function tab(event,form){
 e = event;
 k = e.keyCode;
 if(k == 13){
  document.form1[form].focus()
 }
}

function js_novo(){
 parent.location.href="bib1_acervo000.php?opcao=1";
}
</script>