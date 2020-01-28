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
$cllab_labresp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db12_uf");
$clrotulo->label("la02_c_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("rh70_descr");
?>
<form name="form1" method="post" action="">
<center>
<table name="tabela1" id="tabela1" border="0">
<tr>
<td>
  <tr>
    <td nowrap title="<?=@$Tla06_i_codigo?>">
       <?=@$Lla06_i_codigo?>
    </td>
    <td> 
<?
db_input('la06_i_codigo',10,$Ila06_i_codigo,true,'text',3,"");
?>
    </td>
         <td rowspan=11>
               <table border="0">
                 <tr>
                    <td valign="top">
                      <fieldset><legend><b>Validade</b></legend>
                      <table  width="90%"  border="0">
                      <tr>
                        <td nowrap align="right" title="<?=@$Tla06_d_inicio?>">
                           <?=@$Lla06_d_inicio?>
                         <?
                          if(isset($la06_d_inicio)&&($la06_d_inicio!="")){
                                       $vet=explode("/",$la06_d_inicio);
                                       $la06_d_inicio_dia=$vet[0];
                                       $la06_d_inicio_mes=$vet[1];
                                       $la06_d_inicio_ano=$vet[2];
                          }
                          db_inputdata ( 'la06_d_inicio', @$la06_d_inicio_dia, @$la06_d_inicio_mes, @$la06_d_inicio_ano, true, 'text', $db_opcao, "");
                         ?>                         
                        </td>
                      </tr>
                      <tr>
                      <td nowrap align="right" title="<?=@$Tla06_d_fim?>">
                       <?=@$Lla06_d_fim?>
                       <?
                         if(isset($la06_d_fim)&&($la06_d_fim!="")){
                                       $vet=explode("/",$la06_d_fim);
                                       $la06_d_fim_dia=$vet[0];
                                       $la06_d_fim_mes=$vet[1];
                                       $la06_d_fim_ano=$vet[2];
                         }
                         db_inputdata ( 'la06_d_fim', @$la06_d_fim_dia, @$la06_d_fim_mes, @$la06_d_fim_ano, true, 'text', $db_opcao,"onchange=\"js_validadata();\"","","","parent.js_validadata();");
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
    <td nowrap title="<?=@$Tla06_i_laboratorio?>">
       <?
       db_ancora(@$Lla06_i_laboratorio,"js_pesquisala06_i_laboratorio(true);",3);
       ?>
    </td>
    <td>
<?
db_input('la06_i_laboratorio',10,$Ila06_i_laboratorio,true,'text',3,"")
?>
       <?
db_input('la02_c_descr',40,$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla06_i_cgm?>">
       <?
       db_ancora(@$Lla06_i_cgm,"js_pesquisala06_i_cgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la06_i_cgm',10,$Ila06_i_cgm,true,'text',$db_opcao," onchange='js_pesquisala06_i_cgm(false);'");
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla06_i_cbo?>">
       <?
       db_ancora(@$Lla06_i_cbo,"js_pesquisala06_i_cbo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la06_i_cbo',10,$Ila06_i_cbo,true,'text',$db_opcao," onchange='js_pesquisala06_i_cbo(false);'");
?>
       <?
db_input('rh70_descr',40,$Irh70_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla06_c_cns?>">
       <?=@$Lla06_c_cns?>
    </td>
    <td> 
<?
db_input('la06_c_cns',10,$Ila06_c_cns,true,'text',$db_opcao,"");
?>
    </td>
  </tr>  
  <tr style="display:none" id="linha_tec">
    <td nowrap title="<?=@$Tla06_c_orgaoclasse?>" id="linha_tec">
       <?=@$Lla06_c_orgaoclasse?>
    </td>
    <td> 
<?
db_input('la06_c_orgaoclasse',20,$Ila06_c_orgaoclasse,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr style="display:none" id="linha_tec">
    <td nowrap title="<?=@$Tla06_i_uf?>">
       <?
       db_ancora(@$Lla06_i_uf,"js_pesquisala06_i_uf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la06_i_uf',10,$Ila06_i_uf,true,'text',$db_opcao," onchange='js_pesquisala06_i_uf(false);'");
?>
       <?
db_input('db12_uf',2,$Idb12_uf,true,'text',3,'')
       ?>
    </td>
  </tr>

  <tr>
      <td nowrap title="<?=@$Tla06_i_tipo?>">
         <?=@$Lla06_i_tipo?>
      </td>
      <td>
         <?$y = array("0"=>"Selecione:::","1"=>"Tecnico","2"=>"Legal");
           db_select('la06_i_tipo',$y,true,$db_opcao," onchange='js_tipo(this.value);'");
         ?>
      </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?>
       onclick="return js_valida();" 
>
<input name="cancela" type="button" id="cancela" value="Cancelar" onclick="js_cancela();" >
<table width="100%">
  <tr>
    <td valign="top"><br>
      <?
       $chavepri = array ("la06_i_codigo" => @$la06_i_codigo,
                          "la02_c_descr" => @$la02_c_descr ,
                          "z01_nome"=> @$z01_nome,
                          "rh70_descr" => @$rh70_descr,
                          "la06_i_uf" => @$la06_i_uf,
                          "la06_i_laboratorio" => @$la06_i_laboratorio, 
                          "la06_i_cgm" => @$la06_i_cgm, 
                          "la06_i_cbo" => @$la06_i_cbo, 
                          "la06_d_inicio" => @$la06_d_inicio, 
                          "la06_d_fim" => @$la06_d_fim, 
                          "la06_i_tipo" => @$la06_i_tipo, 
                          "la06_c_orgaoclasse" => @$la06_c_orgaoclasse,
                          "la06_c_cns" => @$la06_c_cns);
       $cliframe_alterar_excluir->chavepri = $chavepri;
      @$cliframe_alterar_excluir->sql = $cllab_labresp->sql_query ("","*",""," la06_i_laboratorio =  $la06_i_laboratorio");
       $cliframe_alterar_excluir->campos = "la06_i_codigo, la06_i_tipo, la06_d_inicio, la06_d_fim, z01_nome";
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
F=document.form1;
if(document.form1.la06_i_cgm.value==''){
	   document.form1.la06_i_cgm.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}
function js_valida(){
    if(F.la06_i_tipo.value=='0'){
        alert('Escolha um tipo para o profissional!');
        return false;
    }
	return true;
}
function js_tipo(tipo){
    var table = document.getElementById('tabela1');
    tec='none';
    if(tipo==1){
      tec='';
    }
    for (var r = 0; r < table.rows.length; r++){
         var id2 = table.rows[r].id;
         if(id2=='linha_tec'){
            table.rows[r].style.display = tec;
         }
    }  
}

function js_pesquisala06_i_uf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_db_uf','func_db_uf.php?funcao_js=parent.js_mostradb_uf1|db12_codigo|db12_uf','Pesquisa',true);
  }else{
     if(document.form1.la06_i_uf.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_db_uf','func_db_uf.php?pesquisa_chave='+document.form1.la06_i_uf.value+'&funcao_js=parent.js_mostradb_uf','Pesquisa',false);
     }else{
       document.form1.db12_uf.value = ''; 
     }
  }
}
function js_mostradb_uf(chave,erro){
  document.form1.db12_uf.value = chave; 
  if(erro==true){ 
    document.form1.la06_i_uf.focus(); 
    document.form1.la06_i_uf.value = ''; 
  }
}
function js_mostradb_uf1(chave1,chave2){
  document.form1.la06_i_uf.value = chave1;
  document.form1.db12_uf.value = chave2;
  db_iframe_db_uf.hide();
}
function js_pesquisala06_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la06_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?pesquisa_chave='+document.form1.la06_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio','Pesquisa',false);
     }else{
       document.form1.la02_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_laboratorio(chave,erro){
  document.form1.la02_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la06_i_laboratorio.focus(); 
    document.form1.la06_i_laboratorio.value = ''; 
  }
}
function js_mostralab_laboratorio1(chave1,chave2){
  document.form1.la06_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_lab_laboratorio.hide();
}
function js_pesquisala06_i_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.la06_i_cgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.la06_i_cgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.la06_i_cgm.focus(); 
    document.form1.la06_i_cgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.la06_i_cgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisala06_i_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbo.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_descr','Pesquisa',true);
  }else{
     if(document.form1.la06_i_cbo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_rhcbo','func_rhcbo.php?pesquisa_chave='+document.form1.la06_i_cbo.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false);
     }else{
       document.form1.rh70_sequencial.value = ''; 
     }
  }
}
function js_mostrarhcbo(chave1,chave2,chave3,erro){
  document.form1.rh70_descr.value = chave2; 
  if(erro==true){ 
    document.form1.la06_i_cbo.focus(); 
    document.form1.la06_i_cbo.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2){
  document.form1.la06_i_cbo.value = chave1;
  document.form1.rh70_descr.value = chave2;
  db_iframe_rhcbo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_labresp','func_lab_labresp.php?funcao_js=parent.js_preenchepesquisa|la06_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_labresp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_cancela(){
   location.href='lab1_lab_labresp001?la06_i_laboratorio=<?=$la06_i_laboratorio?>&la02_c_descr=<?=$la02_c_descr?>';
}

function js_validadata() {
 data=false;
 if(document.form1.la06_d_fim.value != ""  && document.form1.la06_d_inicio.value != "" ){
   if(document.form1.la06_d_fim.value < document.form1.la06_d_inicio.value){
   		alert("Data final menor que a data inicial");
   		document.form1.la06_d_fim.value = "";
   		document.form1.la06_d_fim_dia.value = "";
   		document.form1.la06_d_fim_mes.value = "";
   		document.form1.la06_d_fim_ano.value = "";
   		data=false;
   }	   			
 }
 return data;
}
</script>