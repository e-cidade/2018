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
$clveicmanutencaomedida->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve01_codigo");
$clrotulo->label("nome");

$ve66_usuario = db_getsession("DB_id_usuario");
?>
<form name="form1" method="post" action="">
<fieldset style="margin-top:5px;">
<legend><b>Cadastro de Manutenção de Medida</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tve66_sequencial?>">
      <?=@$Lve66_sequencial?>
    </td>
    <td> 
      <?
      db_input('ve66_sequencial',10,$Ive66_sequencial,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve66_veiculo?>">
      <?
      db_ancora(@$Lve66_veiculo,"js_pesquisave66_veiculo(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('ve66_veiculo',10,$Ive66_veiculo,true,'text',$db_opcao," onchange='js_pesquisave66_veiculo(false);'")
      ?>
      <?
      db_input('ve01_codigo',20,$Ive01_codigo,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve66_medidaanterior?>">
      <?=@$Lve66_medidaanterior?>
    </td>
    <td> 
      <?
      db_input('ve66_medidaanterior',10,$Ive66_medidaanterior,true,'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve66_data?>">
      <?=@$Lve66_data?>
    </td>
    <td> 
      <?
      $ve66_data_dia = date("d", db_getsession("DB_datausu"));
      $ve66_data_mes = date("m", db_getsession("DB_datausu"));
      $ve66_data_ano = date("Y", db_getsession("DB_datausu"));
      db_inputdata('ve66_data',@$ve66_data_dia,@$ve66_data_mes,@$ve66_data_ano,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve66_hora?>">
      <?=@$Lve66_hora?>
    </td>
    <td> 
      <?
      db_input('ve66_hora',10,$Ive66_hora,true,'text',3,"")
      ?>
    </td>
  </tr>
  
  <?
  db_input('ve66_usuario',10,$Ive66_usuario,true,'hidden',$db_opcao," onchange='js_pesquisave66_usuario(false);'");
  ?>

  <tr>
    <td colspan="2">
      <fieldset>
        <legend><strong><?=@$Lve66_motivo?></strong></legend> 
          <?
          db_textarea('ve66_motivo',10,60,$Ive66_motivo,true,'text',$db_opcao,"")
          ?>
      </fieldset>
    </td>
  </tr>
  <tr style="display:none;">
    <td nowrap title="<?=@$Tve66_ativo?>">
      <?=@$Lve66_ativo?>
    </td>
    <td> 
      <?
      $x = array("t"=>"SIM","f"=>"NAO");
      db_select('ve66_ativo',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>
  </fieldset>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

$("ve66_data").value = "<?=date("d/m/Y", db_getsession("DB_datausu"))?>";
$("ve66_hora").value = "<?=date("H:i")?>";

function js_pesquisave66_veiculo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_mostraveiculos1|ve01_codigo|ve01_placa','Pesquisa',true);
  }else{
     if(document.form1.ve66_veiculo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiculos','func_veiculos.php?pesquisa_chave='+document.form1.ve66_veiculo.value+'&funcao_js=parent.js_mostraveiculos','Pesquisa',false);
     }else{
       document.form1.ve01_codigo.value = ''; 
     }
  }
}
function js_mostraveiculos(lErro, iCodigo, sPlaca, sDescr){

  document.form1.ve01_codigo.value = iCodigo; 
  if(lErro==true){ 
    document.form1.ve66_veiculo.focus(); 
    document.form1.ve66_veiculo.value = ''; 
  } else {

	  document.form1.ve01_codigo.value = sPlaca; 
    js_pesquisa_medida();
  }
}
function js_mostraveiculos1(chave1,chave2){

  document.form1.ve66_veiculo.value = chave1;
  document.form1.ve01_codigo.value = chave2;
  db_iframe_veiculos.hide();
  js_pesquisa_medida();
}
function js_pesquisave66_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.ve66_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.ve66_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.ve66_usuario.focus(); 
    document.form1.ve66_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.ve66_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicmanutencaomedida','func_veicmanutencaomedida.php?funcao_js=parent.js_preenchepesquisa|ve66_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicmanutencaomedida.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisa_medida() {
  var databanco = document.form1.ve66_data_ano.value + '-' + 
                  document.form1.ve66_data_mes.value + '-' +
                  document.form1.ve66_data_dia.value;
  var retirada  = '';
  js_OpenJanelaIframe('top.corpo', 'db_iframe_ultimamedida',
    'func_veiculos_medida.php?metodo=ultimamedida&veiculo='+document.form1.ve66_veiculo.value+
                                                '&data='+databanco+
                                                '&hora='+document.form1.ve66_hora.value+
                                                '&retirada='+retirada+
                                                '&funcao_js=parent.js_mostraultimamedida', 'Pesquisa Ultima Medida', false);

  return true;
}

function js_mostraultimamedida(ultimamedida,outro) {
  document.form1.ve66_medidaanterior.value = ultimamedida; 
  return true;
}
</script>