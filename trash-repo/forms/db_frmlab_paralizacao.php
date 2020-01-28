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
$cllab_paralizacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la02_c_descr");
$clrotulo->label("s140_i_tipo");
$db_botao1 = false;
?>
<form name="form1" method="post" action="?la37_i_laboratorio=<?=$la37_i_laboratorio?>">
<center>
<table border='0' align='center'>
  <tr>
  <td>
  <table border="0">
  <tr>
    <td nowrap title="<?=@$Tla37_i_codigo?>">
       <?=@$Lla37_i_codigo?>
    </td>
    <td> 
<?
db_input('la37_i_codigo',10,$Ila37_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Tla37_i_laboratorio?>">
       <?
       db_ancora(@$Lla37_i_laboratorio,"js_pesquisala37_i_laboratorio(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('la37_i_laboratorio',10,$Ila37_i_laboratorio,true,'text',3," onchange='js_pesquisala37_i_laboratorio(false);'")
?>
       <?
db_input('la02_c_descr',50,$Ila02_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan='2'>
    <center>
      <table border='0' width='100%'>
        <tr>
          <td nowrap>
            <fieldset><legend><b>Dados</b></legend>
              <table>
                <tr>
                  <td>
                     <table border='0' valign='center'>
                        <tr>
                         <td>
  							<table border="0">
                       <tr>
                         <td nowrap title="<?=@$Ts140_i_tipo?>">
                          <?= @$Ls140_i_tipo?>
                         </td>
                         <td> 
                          <?
                          $sql = $clmotivo_ausencia->sql_query(null,"s139_i_codigo, s139_c_descr","s139_i_codigo");
                          $resultado = $clmotivo_ausencia->sql_record($sql);
                          if($resultado)
                            db_selectrecord('la37_i_motivo',$resultado,true,$db_opcao,'','la37_i_motivo','','','',1,"");
                          else
                          db_msgbox("Ocorreu um erro na busca dos motivos de ausencia!");
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
                            <fieldset><legend align='left'><b>Validade</b></legend>
                            <table border='0' width='100%'>
                              <tr>
                                <td nowrap title="<?=@$Tla37_d_ini?>">
                                  <?=@$Lla37_d_ini?>
                                </td>
                                <td nowrap> 
                                  <?
                                  if(isset($la37_d_ini)&&($la37_d_ini!="")){
                                       $vet=explode("/",$la37_d_ini);
                                       $la37_d_ini_dia=$vet[0];
                                       $la37_d_ini_mes=$vet[1];
                                       $la37_d_ini_ano=$vet[2];
                                  }
                                  db_inputdata('la37_d_ini',@$la37_d_ini_dia,@$la37_d_ini_mes,@$la37_d_ini_ano,true,'text',$db_opcao,"")
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <td nowrap title="<?=@$Tla37_d_fim?>">
                                  <?=@$Lla37_d_fim?>
                                </td>
                                <td nowrap> 
                                  <?
                                  if(isset($la37_d_fim)&&($la37_d_fim!="")){
                                       $vet=explode("/",$la37_d_ini);
                                       $la37_d_fim_dia=$vet[0];
                                       $la37_d_fim_mes=$vet[1];
                                       $la37_d_fim_ano=$vet[2];
                                  }
                                  db_inputdata('la37_d_fim',@$la37_d_fim_dia,@$la37_d_fim_mes,@$la37_d_fim_ano,true,'text',$db_opcao,"","","","")
                                  ?>
                               </td>
                            </tr>
                          </table>
                        </fieldset>
                        </center>
                      </td>
                    </tr>
               </table>
               </center>
              </td>
                  <td align='center' width='100%'>
                    <center>
                    <table width='50%' border='0' style='display: inline;'>
                      <tr>
                        <td align='center'>
                          <center>
                            <fieldset><legend align='left'><b>Hora</b></legend>
                            <table border='0' width='100%'>
                              <tr>
                                <td nowrap title="<?=@$Tla37_c_horaini?>">
                                  <?=@$Lla37_c_horaini?>
                                </td>
                                <td nowrap> 
                                  <?
                                   db_input('la37_c_horaini',5,$Ila37_c_horaini,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'la37_c_horaini', event)\"");
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <td nowrap title="<?=@$Tla37_c_horafim?>">
                                  <?=@$Lla37_c_horafim?>
                                </td>
                                <td nowrap> 
                                  <?
                                  db_input('la37_c_horafim',5,$Ila37_c_horafim,true,'text',$db_opcao,"onKeyUp=\"mascara_hora(this.value,'la37_c_horafim', event)\"");
                                  ?>
                               </td>
                            </tr>
                          </table>
                        </fieldset>
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
        type="submit" id="db_opcao" 
        value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
        <?=($db_botao==false?" disabled ":"")?> <?=($db_opcao != 3 ? " onclick=\"return js_valida();\" " : "")?>>
  <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="location.href='lab1_lab_paralizacao001.php?la37_i_laboratorio=<?=$la37_i_laboratorio?>&la02_c_descr=<?=$la02_c_descr?>';" >
</center>
</form>
<br>
<?php
$chavepri = array ("la37_i_codigo" => @$la37_i_codigo, 
                   "la02_c_descr"=>@$la02_c_descr, 
                   "la37_i_laboratorio" => @$la37_i_laboratorio, 
                   "la37_d_ini" => @$la37_d_ini, 
                   "la37_d_fim" => @$la37_d_fim,
                   "la37_c_horaini" => @$la37_c_horaini, 
                   "la37_c_horafim" => @$la37_c_horafim,
                   "la37_i_motivo" => @$la37_i_motivo
                  );
$cliframe_alterar_excluir->chavepri = $chavepri;
@$cliframe_alterar_excluir->sql = $cllab_paralizacao->sql_query ("", "*", "","la37_i_laboratorio= $la37_i_laboratorio" );			
$cliframe_alterar_excluir->campos = "la37_i_codigo,la37_d_ini,la37_d_fim,la37_c_horaini,la37_c_horafim";		
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
  </fieldset>
  </td>
  </tr>
</table>

<script>

function js_valida() {
      
  if(!js_validadata()) {
    return false;
  }
    
  if(!js_validahora()) {
    return false;
  }

  return true;

}

function js_validadata() {

  if(document.form1.la37_d_ini.value == "" || document.form1.la37_d_fim.value == "" ) {

    alert('As datas devem ser preenchidas.');
    return false;

  }

  aIni = document.form1.la37_d_ini.value.split('/');
  aFim = document.form1.la37_d_fim.value.split('/');
  dIni = new Date(aIni[2], aIni[1], aIni[0]);
  dFim = new Date(aFim[2], aFim[1], aFim[0]);

	if(dFim < dIni) {
		
    alert("Data final nao pode ser menor que a data inicial.");
	  document.form1.la37_d_fim.value = '';
	  document.form1.la37_d_fim.focus();
    return false;

	}	

  return true;
				
}

function js_validahora() {

  // Se os horários estiverem vazios, valida
  if (document.form1.la37_c_horaini.value == '' && document.form1.la37_c_horafim.value == '') { 
    return true;
  }

  if((document.form1.la37_c_horaini.value == '' && document.form1.la37_c_horafim.value != '')
     || (document.form1.la37_c_horaini.value != '' && document.form1.la37_c_horafim.value == '')) {
      
    alert('Preencha os horários de início e fim ou deixe ambos em branco!');
    return false;
   
  }

  if(document.form1.la37_c_horaini.value.length != 5 || document.form1.la37_c_horafim.value.length != 5) {
      
    alert('Preencha corretamente os horarios!');
    return false;
   
  }

  hr_ini  = (document.form1.la37_c_horaini.value.substring(0,2));
	mi_ini  = (document.form1.la37_c_horaini.value.substring(3,5));
  hr_fim  = (document.form1.la37_c_horafim.value.substring(0,2));
	mi_fim  = (document.form1.la37_c_horafim.value.substring(3,5));

  if(isNaN(hr_ini) || isNaN(mi_ini) ||  isNaN(hr_fim) || isNaN(mi_fim)) {
        
    alert('Preencha corretamente os horarios!');
    return false;

  }

 	hora_ini  = parseInt(hr_ini, 10) * 60 + parseInt(mi_ini, 10);
	hora_fim  = parseInt(hr_fim, 10) * 60 + parseInt(mi_fim, 10);

  if(hora_ini > hora_fim) {
      
    alert('A hora final deve ser maior que a inicial.');
    return false;

  }

  return true;

}

function js_pesquisala37_i_laboratorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_mostralab_laboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la37_i_laboratorio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_laboratorio','func_lab_laboratorio.php?pesquisa_chave='+document.form1.la37_i_laboratorio.value+'&funcao_js=parent.js_mostralab_laboratorio','Pesquisa',false);
     }else{
       document.form1.la02_c_descr.value = ''; 
     }
  }
}
function js_mostralab_laboratorio(chave,erro){
  document.form1.la02_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la37_i_laboratorio.focus(); 
    document.form1.la37_i_laboratorio.value = ''; 
  }
}
function js_mostralab_laboratorio1(chave1,chave2){
  document.form1.la37_i_laboratorio.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_lab_laboratorio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_paralizacao','func_lab_paralizacao.php?funcao_js=parent.js_preenchepesquisa|la37_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_paralizacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>