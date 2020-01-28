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

//MODULO: agua
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claguacortematmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x43_descr");
$clrotulo->label("x41_matric");
$clrotulo->label("nome");
if(isset($db_opcaoal)){
    $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
     
      $x42_usuario      = db_getsession("DB_id_usuario");
      $x42_data         = db_getsession("DB_datausu");
      $x42_data_dia     = date("d", $x42_data);
      $x42_data_mes     = date("m", $x42_data);
      $x42_data_ano     = date("Y", $x42_data);

   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx42_codmov?>">
       <?=@$Lx42_codmov?>
    </td>
    <td> 
<?
db_input('x42_codmov',10,$Ix42_codmov,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx42_codcortemat?>">
       <?=@$Lx42_codcortemat
       //db_ancora(@$Lx42_codcortemat,"js_pesquisax42_codcortemat(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x42_codcortemat',10,$Ix42_codcortemat,true,'text',3," onchange='js_pesquisax42_codcortemat(false);'")
?>
       <?
//db_input('x41_matric',10,$Ix41_matric,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tx42_codsituacao?>">
       <?
       db_ancora(@$Lx42_codsituacao,"js_pesquisax42_codsituacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x42_codsituacao',10,$Ix42_codsituacao,true,'text',$db_opcao," onchange='js_pesquisax42_codsituacao(false);'")
?>
       <?
db_input('x43_descr',40,$Ix43_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
	
	<tr>
    <td nowrap title="<?=@$Tx42_leitura?>">
       <?=@$Lx42_leitura?>
    </td>
    <td><? 
    db_input('x42_leitura',10,$Ix42_leitura, true,'text',$db_opcao);
    ?></td>
    </tr> 

  <tr>
    <td nowrap title="<?=@$Tx42_historico?>">
       <?=@$Lx42_historico?>
    </td>
    <td> 
<?
db_textarea('x42_historico',4,60,$Ix42_historico,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx42_data?>">
       <?=@$Lx42_data?>
    </td>
    <td> 
<?
db_inputdata('x42_data',@$x42_data_dia,@$x42_data_mes,@$x42_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx42_usuario?>">
       <?=@$Lx42_usuario?>
    </td>
    <td> 
<?
db_input('x42_usuario',10,$Ix42_usuario,true,'text',3,"");
db_input('nome',40,$Inome,true,'text',3,'');
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("x42_codmov"=>@$x42_codmov);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $claguacortematmov->sql_query(null,"*","x42_codmov desc, x42_data desc","x42_codcortemat=".$x42_codcortemat);
	 $cliframe_alterar_excluir->campos  ="x42_codmov,x42_data,x42_codsituacao,x43_descr,x42_codcortemat,x42_leitura,x42_historico,x42_usuario,nome";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="800";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisax42_codsituacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_aguacortesituacao','func_aguacortesituacao.php?funcao_js=parent.js_mostraaguacortesituacao1|x43_codsituacao|x43_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x42_codsituacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_aguacortesituacao','func_aguacortesituacao.php?pesquisa_chave='+document.form1.x42_codsituacao.value+'&funcao_js=parent.js_mostraaguacortesituacao','Pesquisa',false);
     }else{
       document.form1.x43_descr.value = ''; 
     }
  }
}
function js_mostraaguacortesituacao(chave,erro){
  document.form1.x43_descr.value = chave; 
  if(erro==true){ 
    document.form1.x42_codsituacao.focus(); 
    document.form1.x42_codsituacao.value = ''; 
  }
}
function js_mostraaguacortesituacao1(chave1,chave2){
  document.form1.x42_codsituacao.value = chave1;
  document.form1.x43_descr.value = chave2;
  db_iframe_aguacortesituacao.hide();
}
function js_pesquisax42_codcortemat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_aguacortemat','func_aguacortemat.php?funcao_js=parent.js_mostraaguacortemat1|x41_codcortemat|x41_matric','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x42_codcortemat.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_aguacortemat','func_aguacortemat.php?pesquisa_chave='+document.form1.x42_codcortemat.value+'&funcao_js=parent.js_mostraaguacortemat','Pesquisa',false);
     }else{
       document.form1.x41_matric.value = ''; 
     }
  }
}
function js_mostraaguacortemat(chave,erro){
  document.form1.x41_matric.value = chave; 
  if(erro==true){ 
    document.form1.x42_codcortemat.focus(); 
    document.form1.x42_codcortemat.value = ''; 
  }
}
function js_mostraaguacortemat1(chave1,chave2){
  document.form1.x42_codcortemat.value = chave1;
  document.form1.x41_matric.value = chave2;
  db_iframe_aguacortemat.hide();
}
function js_pesquisax42_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.x42_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguacortematmov','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.x42_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.nome.value = ''; 
     }
  }
}

function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.x42_usuario.focus(); 
    document.form1.x42_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.x42_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}


js_pesquisax42_usuario(false);
</script>