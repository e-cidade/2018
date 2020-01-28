<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: veiculos
$clveicabastanu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve70_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend><b>Dados Da Anulação</b></legend>
          <table border="0">
            <tr>
              <td nowrap title="<?//=@$Tve74_codigo?>">
                 <?//=@$Lve74_codigo?>
              </td>
              <td> 
                <?
                db_input('ve74_codigo',10,$Ive74_codigo,true,'hidden',3,"");
                db_input('pesq',10,"",true,'hidden',3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tve74_veicabast?>">
                 <?
                 db_ancora(@$Lve74_veicabast,"js_pesquisa_abast();",$db_opcao);
                 ?>
              </td>
              <td> 
                <?
                db_input('ve74_veicabast',10,$Ive74_veicabast,true,'text',3," onchange='js_pesquisave74_veicabast(false);'");
                db_input('ve70_codigo',10,$Ive70_codigo,true,'hidden',3,'')
                 ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tve74_motivo?>">
                 <?=@$Lve74_motivo?>
              </td>
              <td> 
                <?
                db_textarea('ve74_motivo',0,50,$Ive74_motivo,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>  
    <tr>
      <td colspan="2" style="text-align: center">
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
               id="db_opcao"  value="<?=($db_opcao==1?"Anular":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
               <?=($db_botao==false?"disabled":"")?> >
       <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </td>
    </tr>    
  </table>
</center>
</form>
<script>
function js_pesquisave74_veicabast(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veicabast','func_veicabast.php?funcao_js=parent.js_mostraveicabast1|ve70_codigo|ve70_codigo','Pesquisa',true);
  }else{
     if(document.form1.ve74_veicabast.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veicabast','func_veicabast.php?pesquisa_chave='+document.form1.ve74_veicabast.value+'&funcao_js=parent.js_mostraveicabast','Pesquisa',false);
     }else{
       document.form1.ve70_codigo.value = ''; 
     }
  }
}
function js_mostraveicabast(chave,erro){
  document.form1.ve70_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ve74_veicabast.focus(); 
    document.form1.ve74_veicabast.value = ''; 
  }
}
function js_mostraveicabast1(chave1,chave2){
  document.form1.ve74_veicabast.value = chave1;
  document.form1.ve70_codigo.value = chave2;
  db_iframe_veicabast.hide();
}
function js_pesquisave74_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.ve74_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ve74_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.ve74_usuario.focus(); 
    document.form1.ve74_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.ve74_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa_abast(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicabast','func_veicabastalt.php?anul=true&funcao_js=parent.js_preenchepesquisa_abast|dl_Cod_Abast','Pesquisa',true);
}
function js_preenchepesquisa_abast(chave){
  db_iframe_veicabast.hide();
  <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?abast='+chave";  
  ?>
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicabastanu','func_veicabastanu.php?funcao_js=parent.js_preenchepesquisa|ve74_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicabastanu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>