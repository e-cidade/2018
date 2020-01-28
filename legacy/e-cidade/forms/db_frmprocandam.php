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

//MODULO: protocolo
$clprocandam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_requer");
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="Usuário">
      <b>Usuário:</b> 
    </td>
    <td> 
     <?
       $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
       echo pg_result(pg_exec($sql),0,"nome");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Usuário">
      <b>Departamento:</b> 
    </td>
    <td> 
     <?
       $sql = "select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto");
       echo pg_result(pg_exec($sql),0,"descrdepto");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp61_codandam?>">
       <?=@$Lp61_codandam?>
    </td>
    <td> 
<?
db_input('p61_codandam',5,$Ip61_codandam,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp61_codproc?>">
       <?
       db_ancora(@$Lp61_codproc,"js_pesquisap61_codproc(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p61_codproc',3,$Ip61_codproc,true,'text',$db_opcao," onchange='js_pesquisap61_codproc(false);'")
?>
       <?
db_input('p58_requer',80,$Ip58_requer,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp61_dtandam?>">
       <?=@$Lp61_dtandam?>
    </td>
    <td> 
<?
db_inputdata('p61_dtandam',@$p61_dtandam_dia,@$p61_dtandam_mes,@$p61_dtandam_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp61_despacho?>">
       <?=@$Lp61_despacho?>
    </td>
    <td> 
<?
db_textarea('p61_despacho',3,25,$Ip61_despacho,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisap61_codproc(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_protprocesso.php?pesquisa_chave='+document.form1.p61_codproc.value+'&funcao_js=parent.js_mostraprotprocesso';
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_requer.value = chave; 
  if(erro==true){ 
    document.form1.p61_codproc.focus(); 
    document.form1.p61_codproc.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.p61_codproc.value = chave1;
  document.form1.p58_requer.value = chave2;
  db_iframe.hide();
}
function js_pesquisap61_coddepto(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_depart.php?pesquisa_chave='+document.form1.p61_coddepto.value+'&funcao_js=parent.js_mostradb_depart';
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.p61_coddepto.focus(); 
    document.form1.p61_coddepto.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.p61_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_procandam.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>