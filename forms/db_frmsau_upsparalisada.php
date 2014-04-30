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

//MODULO: Ambulatorial
$clsau_upsparalisada->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("s139_i_codigo");
?>
<form name="form1" method="post" action="?chavepesquisa=<?=$chavepesquisa?>">
<center>
<table border='0' align='center'>
  <tr>
  <td>
  <fieldset> <legend align='center'><b>Paralisa&ccedil;&otilde;es</b></legend>
  <table border="0">
  <tr>
    <td nowrap title="<?=@$Ts140_i_codigo?>">
       <?=@$Ls140_i_unidade?>
    </td>
    <td> 
<?
db_input('s140_i_unidade',10,$Is140_i_unidade,true,'text',3,"");
db_input('descrdepto',75,$Is140_i_unidade,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
      <table border='0' width='100%'>
        <tr>
          <td nowrap>
            <fieldset><legend><b>Dados</b></legend>
              <table>
                <tr>
                  <td>
                     <table border='0' valign='center'>
                       <tr>
                         <td nowrap title="<?=@$Ts140_i_unidade?>">
                           <?=@$Ls140_i_codigo?>
                         </td>
                         <td> 
                           <?
                           db_input('s140_i_codigo',10,$Is140_i_codigo,true,'text',3,"");
                           ?>
                         </td>
                       </tr>
                       <tr>
                         <td nowrap title="<?=@$Ts140_i_tipo?>">
                          <?= @$Ls140_i_tipo?>
                         </td>
                         <td> 
                           <?
                           $sql = $clmotivo_ausencia->sql_query(null,"s139_i_codigo, s139_c_descr","s139_i_codigo");
                           $resultado = $clmotivo_ausencia->sql_record($sql);
                           if($resultado)
                             db_selectrecord('s140_i_tipo',$resultado,true,1,'','s140_i_tipo','','','',1);
                           else
                             db_msgbox("Ocorreu um erro na busca dos motivos de ausencia!");
                           ?>
                         </td>
                       </tr>
                       <tr>
                         <td nowrap title="<?=@$Ts140_i_unidade?>">
                           <b>Quantidade:</b>
                         </td>
                         <td> 
                           <?
                             db_input('s140_i_quantidade',10,$Is140_i_codigo,true,'text',3,"");
                           ?>
                         </td>
                       </tr>
                     </table>
                  </td>
                  <td align='center' width='100%'>
                    <center>
                    <table width='50%' border='0' style='display: inline;'>
                      <tr>
                        <td align='center'>
                          <center>
                          <table>
                          <tr>
                          <td>
                          <fieldset><legend align='left'><b>Data</b></legend>
                            <table border='0' width='100%'>
                              <tr>
                                <td nowrap title="<?=@$Ts140_d_inicio?>">
                                  <?=@$Ls140_d_inicio?>
                                </td>
                                <td nowrap> 
                                  <?
                                  db_inputdata('s140_d_inicio',@$s140_d_inicio_dia,@$s140_d_inicio_mes,@$s140_d_inicio_ano,true,'text',$db_opcao,"")
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <td nowrap title="<?=@$Ts140_d_fim?>">
                                  <?=@$Ls140_d_fim?>
                                </td>
                                <td nowrap> 
                                  <?
                                  db_inputdata('s140_d_fim',@$s140_d_fim_dia,@$s140_d_fim_mes,@$s140_d_fim_ano,true,'text',$db_opcao,"")
                                  ?>
                               </td>
                            </tr>
                          </table>
                        </fieldset>
                        </td>
                        <td>
                          <fieldset><legend>Horario</legend>
                            <table>
                              <tr>
                                <td nowrap title=""><?=$Ls140_c_horaini?></td>
                                <td><?db_input('s140_c_horaini',5,@$Is140_c_horaini,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'s140_c_horaini', event)\"");?></td>
                              </tr>
                              <tr>
                                <td nowrap title=""><?=$Ls140_c_horafim?></td>
                                <td><?db_input('s140_c_horafim',5,@$Is140_c_horafim,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'s140_c_horafim', event)\"");?></td>
                              </tr>
                            </table>
                          </fieldset>
                         </td>
                        </tr>
                        </table>
                        </center>
                      </td>
                    </tr>
               </table>
               </center>
              </td>
            </tr>
          </table>
          </fieldset>
        </td>
      </tr>
    </table>
  </center>
<center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
  type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>
  onclick="return <?=($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22 ? 'js_validadata();' : 'confirm(\'Realmente deseja excluir este registro?\');')?>">
  <input name="limpar" type="button" id="limpar" value="Limpar" onclick="location.href='sau1_upsparalisada001.php?chavepesquisa=<?=$chavepesquisa?>';" >
</center>
</form>
<br>
<?php
$chavepri= array("s140_i_codigo"=>@$s140_i_codigoo);
$cliframe_alterar_excluir->chavepri=$chavepri;
@$cliframe_alterar_excluir->sql = "select s140_i_codigo, s140_d_inicio, s140_d_fim, (s140_d_fim - s140_d_inicio) + 1 as sd27_i_quantidade, sau_motivo_ausencia.s139_c_descr as s140_i_tipo,s140_c_horaini,s140_c_horafim
                                           from sau_upsparalisada
                                           inner join sau_motivo_ausencia on sau_upsparalisada.s140_i_tipo = s139_i_codigo
                                           inner join unidades on sd02_i_codigo = $chavepesquisa and sd02_i_codigo = sau_upsparalisada.s140_i_unidade order by s140_d_inicio desc";
//echo $cliframe_alterar_excluir->sql;
$sCampos = "s140_i_codigo, s140_d_inicio, s140_d_fim, s140_c_horaini,s140_c_horafim, s140_i_tipo, sd27_i_quantidade";
@$cliframe_alterar_excluir->campos = $sCampos;
$cliframe_alterar_excluir->legenda="Grade de Paralisa&ccedil;&otilde;es da UPS";
$cliframe_alterar_excluir->alignlegenda = "left";
//$cliframe_alterar_excluir->iframe_height ="200";
$cliframe_alterar_excluir->iframe_width ="100%";
$cliframe_alterar_excluir->tamfontecabec = 9;
$cliframe_alterar_excluir->tamfontecorpo = 9;
$cliframe_alterar_excluir->formulario = false;
@$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao2);

?>
  </fieldset>
  </td>
  </tr>
</table>

<script>
function js_limpacampos(){
   if(document.form1.elements == undefined)
     return false;

  for(var i = 0; i < document.form1.elements.length; i++){
    if(document.form1.elements[i].type != 'button' && document.form1.elements[i].type != 'submit')
    {
      if(document.form1.elements[i].type == 'select-one')
        document.form1.elements[i].value = '1';
      else
        document.form1.elements[i].value = "";
    }
  }
}

function js_validadata(){
  inicio = new Date(document.form1.s140_d_inicio.value.substring(6,10),
                    document.form1.s140_d_inicio.value.substring(3,5),
                    document.form1.s140_d_inicio.value.substring(0,2));
  fim    = new Date(document.form1.s140_d_fim.value.substring(6,10),
                    document.form1.s140_d_fim.value.substring(3,5),
                    document.form1.s140_d_fim.value.substring(0,2));

  if(document.form1.s140_d_inicio.value == "" || document.form1.s140_d_fim.value == ""){

    alert('ERRO: os campos data de inicio e de fim devem sem preenchidos.');
    return false;

  }

  if( inicio > fim){

    alert('ERRO: A data de Inicio esta maior que a data de Fim.');
    document.form1.s140_d_inicio.value = '';
    document.form1.s140_d_fim.value = '';
    document.form1.s140_d_inicio.focus();
    return false;

  }

  horaini = document.form1.s140_c_horaini.value;
  horafim = document.form1.s140_c_horafim.value;
  if ((horaini != "") && (horaini != "")) {

    aVet1=horaini.split(':');
    aVet2=horafim.split(':');
    if(parseInt(aVet1[0]+aVet1[1],10)>parseInt(aVet2[0]+aVet2[1],10)){

      alert('A hora final não pode ser maior que a inicial!');
      return false

    }
  }
  return true;
}

function js_pesquisas140_i_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sau_motivo_ausencia','func_sau_motivo_ausencia.php?funcao_js=parent.js_mostrasau_motivo_ausencia1|s139_i_codigo|s139_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.s140_i_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_sau_motivo_ausencia','func_sau_motivo_ausencia.php?pesquisa_chave='+document.form1.s140_i_tipo.value+'&funcao_js=parent.js_mostrasau_motivo_ausencia','Pesquisa',false);
     }else{
       document.form1.s139_i_codigo.value = ''; 
     }
  }
}
function js_mostrasau_motivo_ausencia(chave,erro){
  document.form1.s139_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.s140_i_tipo.focus(); 
    document.form1.s140_i_tipo.value = ''; 
  }
}
function js_mostrasau_motivo_ausencia1(chave1,chave2){
  document.form1.s140_i_tipo.value = chave1;
  document.form1.s139_i_codigo.value = chave2;
  db_iframe_sau_motivo_ausencia.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_upsparalisada','func_sau_upsparalisada.php?funcao_js=parent.js_preenchepesquisa|s140_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_upsparalisada.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>