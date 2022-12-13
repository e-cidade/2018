<?php
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

//MODULO: orcamento
$clcronogramaperspectiva->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o119_versao");
$clrotulo->label("nome");
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>
         <b>Abrir Perpespectiva Cronograma</b>
      </legend>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$To124_descricao?>">
             <?=@$Lo124_descricao?>
          </td>
          <td>
           <?
           db_input('o124_descricao',40,$Io124_descricao,true,'text',$db_opcao,"")
           ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To124_datacriacao?>">
             <?=@$Lo124_datacriacao?>
          </td>
          <td>
            <?
            db_inputdata('o124_datacriacao',@$o124_datacriacao_dia,@$o124_datacriacao_mes,@$o124_datacriacao_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
           <td>
             <b>Perpectiva do PPA:</b>
           </td>
          <td align="left" nowrap>
          <?
           $oDaoPPaVersao    = new cl_ppaversao;
           $sSqlPerspectivas = $oDaoPPaVersao->sql_query_integracao(null,
                                                                    "ppaversao.*",
                                                                    "o119_versao",
                                                                    "o123_situacao = 1");
           $rsPerspectivas   = $oDaoPPaVersao->sql_record($sSqlPerspectivas);
           $aPerspectivas    = array();
           for ($i = 0; $i < $oDaoPPaVersao->numrows; $i++ ) {

             $oPerspectiva  = db_utils::fieldsMemory($rsPerspectivas, $i);
             $aPerspectivas[$oPerspectiva->o119_sequencial] = "P{$oPerspectiva->o119_versao}";

           }
           if (!isset($o119_versao)) {
             $o119_versao = $oPerspectiva->o119_sequencial;
           }
           db_select("o119_ppaversao", $aPerspectivas, true, 1,"style='width:95px'");
           ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</div>
<script type="text/javascript">
function js_pesquisao124_ppaversao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ppaversao','func_ppaversao.php?funcao_js=parent.js_mostrappaversao1|o119_sequencial|o119_versao','Pesquisa',true);
  }else{
     if(document.form1.o124_ppaversao.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_ppaversao','func_ppaversao.php?pesquisa_chave='+document.form1.o124_ppaversao.value+'&funcao_js=parent.js_mostrappaversao','Pesquisa',false);
     }else{
       document.form1.o119_versao.value = '';
     }
  }
}
function js_mostrappaversao(chave,erro){
  document.form1.o119_versao.value = chave;
  if(erro==true){
    document.form1.o124_ppaversao.focus();
    document.form1.o124_ppaversao.value = '';
  }
}
function js_mostrappaversao1(chave1,chave2){
  document.form1.o124_ppaversao.value = chave1;
  document.form1.o119_versao.value = chave2;
  db_iframe_ppaversao.hide();
}
function js_pesquisao124_idusuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.o124_idusuario.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.o124_idusuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.o124_idusuario.focus();
    document.form1.o124_idusuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.o124_idusuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cronogramaperspectiva','func_cronogramaperspectiva.php?funcao_js=parent.js_preenchepesquisa|o124_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cronogramaperspectiva.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>