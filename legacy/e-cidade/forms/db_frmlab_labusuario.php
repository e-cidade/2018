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

//MODULO: Laboratório
include ("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$cllab_labusuario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la02_c_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td>
       <tr>
         <td nowrap title="<?=@$Tla05_i_codigo?>">
            <?=@$Lla05_i_codigo?>
         </td>
         <td>
          <?          
          db_input('la05_i_codigo',10,$Ila05_i_codigo,true,'text',3,"");
          ?>
         </td>
         <td rowspan=11>
               <table border="0">
                 <tr>
                    <td valign="top">
                      <fieldset><legend><b>Validade</b></legend>
                      <table  width="90%"  border="0">
                      <tr>
                        <td nowrap align="right" title="<?=@$Tla05_d_inicio?>">
                           <?=@$Lla05_d_inicio?>
                         <?
                          if(isset($la05_d_inicio)&&($la05_d_inicio!="")){
                                       $vet=explode("/",$la05_d_inicio);
                                       $la05_d_inicio_dia=$vet[0];
                                       $la05_d_inicio_mes=$vet[1];
                                       $la05_d_inicio_ano=$vet[2];
                          }
                          db_inputdata ( 'la05_d_inicio', @$la05_d_inicio_dia, @$la05_d_inicio_mes, @$la05_d_inicio_ano, true, 'text', $db_opcao, "" )
                         ?>                      
                        </td>
                      </tr>
                      <tr>
                      <td nowrap align="right" title="<?=@$Tla05_d_fim?>">
                       <?=@$Lla05_d_fim?>
                       <?
                         if(isset($la05_d_fim)&&($la05_d_fim!="")){
                                       $vet=explode("/",$la05_d_fim);
                                       $la05_d_fim_dia=$vet[0];
                                       $la05_d_fim_mes=$vet[1];
                                       $la05_d_fim_ano=$vet[2];
                         }
                         db_inputdata ( 'la05_d_fim', @$la05_d_fim_dia, @$la05_d_fim_mes, @$la05_d_fim_ano, true, 'text', $db_opcao, "onchange=\"js_validadata();\"","","","parent.js_validadata();")
                        ?>                 
                        </td>
                      </tr>                     
                      </table>
                      </fieldset>
                    </td>
                 </tr>
                 </table>
         </td>
       </tr>
  <tr>
    <td nowrap title="<?=@$Tla05_i_laboratorio?>">
       <?
       db_ancora(@$Lla05_i_laboratorio,"js_pesquisala05_i_laboratorio(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('la05_i_laboratorio',10,$Ila05_i_laboratorio,true,'text',3," onchange='js_pesquisala05_i_laboratorio(false);'")
?>
       <?
db_input('la02_c_descr',50,$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla05_i_usuario?>">
       <?
       db_ancora(@$Lla05_i_usuario,"js_pesquisala05_i_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la05_i_usuario',10,$Ila05_i_usuario,true,'text',$db_opcao," onchange=\"js_pesquisala05_i_usuario(false);\"")
?>
       <?
db_input('nome',50,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancela" type="button" id="cancela" value="Cancelar" onclick="js_cancela();" >

<table width="100%">
  <tr>
    <td valign="top"><br>
  <?
    $chavepri = array ("la05_i_codigo" => @$la05_i_codigo, "la02_c_descr"=>@$la02_c_descr, "la05_i_laboratorio" => @$la05_i_laboratorio, "la05_i_usuario" => @$la05_usuario, "la05_d_inicio" => @$la05_d_inicio, "la05_d_fim" => @$la05_d_fim,"nome"=>@$nome);
    $cliframe_alterar_excluir->chavepri = $chavepri;
   @$cliframe_alterar_excluir->sql = $cllab_labusuario->sql_query ("","*",""," la05_i_laboratorio = $la05_i_laboratorio");
    $cliframe_alterar_excluir->campos = "la05_i_codigo,la05_i_usuario,nome,la05_d_inicio,la05_d_fim";
    $cliframe_alterar_excluir->legenda = "Registros";
    $cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec = "#DEB887";
    $cliframe_alterar_excluir->textocorpo = "#444444";
    $cliframe_alterar_excluir->fundocabec = "#444444";
    $cliframe_alterar_excluir->fundocorpo = "#eaeaea";
    $cliframe_alterar_excluir->iframe_height = "200";
    $cliframe_alterar_excluir->iframe_width = "100%";
    $cliframe_alterar_excluir->tamfontecabec = 9;
    $cliframe_alterar_excluir->tamfontecorpo = 9;
    $cliframe_alterar_excluir->formulario = false;
    $cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao );
    ?>
  </td>
  </tr>
</table>


</form>
<script>
if(document.form1.la05_i_usuario.value==''){
	   document.form1.la05_i_usuario.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}
function js_pesquisala05_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la05_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?pesquisa_chave='+document.form1.la05_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio','Pesquisa',false);
     }else{
       document.form1.la02_c_descr.value = ''; 
     }
  }
}
function js_mostralab_laboratorio(chave,erro){
  document.form1.la02_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la05_i_laboratorio.focus(); 
    document.form1.la05_i_laboratorio.value = ''; 
  }
}
function js_mostralab_laboratorio1(chave1,chave2){
  document.form1.la05_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_lab_laboratorio.hide();
}
function js_pesquisala05_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.la05_i_usuario.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.la05_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.la05_i_usuario.focus(); 
    document.form1.la05_i_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.la05_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_labusuario','func_lab_labusuario.php?funcao_js=parent.js_preenchepesquisa|la05_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_labusuario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){
     location.href='lab1_lab_labusuario001.php?la05_i_laboratorio=<?=$la05_i_laboratorio?>&la02_c_descr=<?=$la02_c_descr?>';
}

function js_validadata() {
  data=false;
  if(document.form1.la05_d_fim.value != ""  && document.form1.la05_d_inicio.value != "" ){
   	if(document.form1.la05_d_fim.value < document.form1.la05_d_inicio.value){
   	  alert("Data final menor que a data inicial");
   	  document.form1.la05_d_fim.value = "";
   	  document.form1.la05_d_fim_dia.value = "";
   	  document.form1.la05_d_fim_mes.value = "";
   	  document.form1.la05_d_fim_ano.value = "";
   	  data=false;
   	}				
  }
  return data;
}

</script>