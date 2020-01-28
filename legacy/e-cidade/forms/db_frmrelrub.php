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

//MODULO: pessoal
$clrelrub->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r44_descr");
      if($db_opcao==1){
 	   $db_action="pes1_relrub004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="pes1_relrub005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="pes1_relrub006.php";
      }
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh45_codigo?>">
       <?=@$Lrh45_codigo?>
    </td>
    <td>
<?
db_input('rh45_codigo',6,$Irh45_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh45_descr?>">
       <?=@$Lrh45_descr?>
    </td>
    <td>
      <?php db_input('rh45_descr',46,$Irh45_descr,true,'text',$db_opcao,""); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh45_selecao?>">
      <?  db_ancora(@$Lrh45_selecao,"js_pesquisarh45_selecao(true);",$db_opcao); ?>
    </td>
    <td>
      <?php
        if(isset($rh45_selecao) && trim($rh45_selecao) != ""){

          $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($rh45_selecao,db_getsession("DB_instit"),"r44_descr"));
          if($clselecao->numrows > 0){
            db_fieldsmemory($result_selecao,0);
          }
        }

        db_input('rh45_selecao',3,$Irh45_selecao,true,'text',$db_opcao,"onchange='js_pesquisarh45_selecao(false);'");
        db_input('r44_descr',40,$Ir44_descr,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh45_form?>">
       <?=@$Lrh45_form?>
    </td>
    <td>
      <?php db_textarea('rh45_form', 2, 43, @$Irh45_form, true, 'text', $db_opcao); ?>
    </td>
  </tr>
  </table>
  </center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >

  <?php if ($db_opcao != 1) : ?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  <?php endif; ?>

</form>
<script>
function js_pesquisarh45_selecao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_relrub','db_iframe_selecao','func_selecao.php?funcao_js=top.corpo.iframe_relrub.js_mostraselecao1|r44_selec|r44_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.rh45_selecao.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_relrub','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.rh45_selecao.value+'&funcao_js=top.corpo.iframe_relrub.js_mostraselecao','Pesquisa',false,'0');
     }else{
       document.form1.r44_descr.value = '';
     }
  }
}
function js_mostraselecao(chave,erro){
  document.form1.r44_descr.value = chave;
  if(erro==true){
    document.form1.rh45_selecao.focus();
    document.form1.rh45_selecao.value = '';
  }
}
function js_mostraselecao1(chave1,chave2){
  document.form1.rh45_selecao.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_relrub','db_iframe_relrub','func_relrub.php?funcao_js=parent.js_preenchepesquisa|rh45_codigo','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_relrub.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>