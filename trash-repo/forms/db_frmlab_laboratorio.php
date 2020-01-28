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

//MODULO: Laboratório
$cllab_laboratorio->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table name="tabela1" id="tabela1" border="0">
   <tr>
       <td nowrap title="<?=@$Tla02_i_codigo?>">
           <?=@$Lla02_i_codigo?>
       </td>
       <td colspan="3"> 
           <?db_input('la02_i_codigo',10,$Ila02_i_codigo,true,'text',3,"")?>
       </td>
  </tr>
  <tr>
       <td nowrap title="<?=@$Tla02_i_tipo?>">
           <?=@$Lla02_i_tipo?>
       </td>
       <td Colspan="3"> 
           <?$y = array("0"=>"Selecione:::","1"=>"Interno","2"=>"Externo");
           db_select('la02_i_tipo',$y,true,($db_opcao==2)?@$iBloqueioTipo:$db_opcao," onchange='js_tipo(this.value);'");?>
       </td>
  </tr>
  <tr style="display:<?=(@$la02_i_tipo == 1) ? "" : "none"?>" id="linha_departamento">
        <td>
            <?db_ancora("<b>Departamento:</b>","js_pesquisala03_i_departamento(true);",$db_opcao);?>
        </td>
        <td colspan="3">         
            <?db_input('la03_i_departamento',10,"",true,'text',$db_opcao," onchange='js_pesquisala03_i_departamento(false);'")?>
            <?db_input('descrdepto',35,"",true,'text',3,'')?>         
        </td>
  </tr>
  <tr style="display:<?=(@$la02_i_tipo == 2)? "" : "none"?>" id="linha_cgm">
      <td>
           <?db_ancora("<b>CGM:</b>","js_pesquisaCgm(true);",$db_opcao);?>
      </td>    
      <td colspan="3">
         <?db_input('la04_i_cgm',10,"",true,'text',$db_opcao," onchange='js_pesquisaCgm(false);'")?>
         <?db_input('z01_nome',40,"",true,'text',3,'')?>
      </td>
  </tr>
  <tr>
       <td nowrap title="<?=@$Tla02_c_descr?>">
           <?=@$Lla02_c_descr?>
       </td>
       <td colspan="3"> 
           <?db_input('la02_c_descr',45,$Ila02_c_descr,true,'text',$db_opcao,"")?>
       </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla02_i_alvara?>">
       <?=@$Lla02_i_alvara?>
    </td>
    <td> 
<?
db_input('la02_i_alvara',10,$Ila02_i_alvara,true,'text',$db_opcao,"")
?>
    </td>
    <td align="right" nowrap title="<?=@$Tla02_i_cnes?>">
       <?=@$Lla02_i_cnes?>
    </td>
    <td> 
<?
db_input('la02_i_cnes',10,$Ila02_i_cnes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla02_c_endereco?>">
       <?=@$Lla02_c_endereco?>
    </td>
    <td> 
<?
db_input('la02_c_endereco',45,$Ila02_c_endereco,true,'text',$db_opcao,"")
?>
    </td>
    <td align="right" nowrap title="<?=@$Tla02_c_numero?>">
       <?=@$Lla02_c_numero?>
    </td>
    <td> 
<?
db_input('la02_c_numero',10,$Ila02_c_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tla02_i_telefone?>">
       <?=@$Lla02_i_telefone?>
    </td>
    <td colspan="3"> 
<?
db_input('la02_i_telefone',11,$Ila02_i_telefone,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

  <tr>
     <td nowrap title="<?=@$Tla02_i_turnoatend?>">
         <?db_ancora(@$Lla02_i_turnoatend,"js_pesquisala02_i_turnoatend(true);",$db_opcao);?>
     </td>
     <td>
         <?db_input('la02_i_turnoatend',10,$Ila02_i_turnoatend,true,'text',$db_opcao,"onchange='js_pesquisala02_i_turnoatend(false);'")?>
         <?db_input('sd43_v_descricao',40,@$Isd43_v_descricao,true,'text',3,"")?>
     </td>
   </tr>
                                                
  </table>
  <br>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" onclick="return js_valida();" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
<?if(@$la02_i_tipo!=""){
    echo"js_tipo($la02_i_tipo);";
  }
?>

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_laboratorio','func_lab_laboratorio.php?funcao_js=parent.js_preenchepesquisa|la02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_laboratorio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
 
 //Objeto Formulario Global
 F=document.form1;
/**
//Box de horarios
   function js_delete(){
       if(confirm('Deseja excluir este horario?')){
          F.box_horario.remove(F.box_horario.selectedIndex);
       }
   }
   function js_lanca(){
       turno=F.turno.value;
       horaini=F.horaini.value;
       horafim=F.horafim.value;
       result=js_busca_boxhorario(turno);
       if(result==false){
          F.box_horario.add(new Option(F.turno.options[F.turno.selectedIndex].text+' - '+F.horaini.value+' , '+F.horafim.value,F.turno.options[F.turno.selectedIndex].value),null);
          F.horaini.value='';
          F.horafim.value='';
       }else{
          alert("Turno ja incluido!");
       }
   }
   function js_busca_boxhorario(turno){
      for(x=0;x<F.box_horario.length;x=x+1){
         if(turno==F.box_horario.options[x].value){
            return true;
         }
      }
      return false;
   }
 */

//Seletor de tipo
  function js_tipo(tipo){
    var table = document.getElementById('tabela1');
    cgm=depart='none';
    if(tipo==1){
        depart='';
        parent.document.formaba.a3.style.display = 'none';
    }else if(tipo==2){
        cgm='';
        if(<?=(@$la02_i_tipo == 2 ? 'true' : 'false')?>) {
          parent.document.formaba.a3.style.display = '';
        }
    }    
    for (var r = 0; r < table.rows.length; r++){
         var id2 = table.rows[r].id;
         if(id2=='linha_departamento'){
            table.rows[r].style.display = depart;
         }
         if(id2=='linha_cgm'){
            table.rows[r].style.display = cgm;
         }
    }                                        
  }


//lookup Departamento
 function js_pesquisala03_i_departamento(mostra){
     if(mostra==true){
        js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
     }else{
        if(document.form1.la03_i_departamento.value != ''){ 
           js_OpenJanelaIframe('','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.la03_i_departamento.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
        }else{
           document.form1.descrdepto.value = ''; 
        }
     }
}
function js_mostradb_depart(chave,erro){
    document.form1.descrdepto.value = chave; 
    if(erro==true){ 
       document.form1.la03_i_departamento.focus(); 
       document.form1.la03_i_departamento.value = ''; 
    }
}
function js_mostradb_depart1(chave1,chave2){
    document.form1.la03_i_departamento.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
}

//Lookup CGM
   function js_pesquisaCgm(mostra){
      if(mostra==true){
          js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
      }else{ 
          cgm_busca=F.la04_i_cgm.value;
          if(cgm_busca != ''){
             js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+cgm_busca+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
          }else{
             F.z01_nome.value = '';
          }
      }
   }   
   function js_mostracgm(erro,chave){
      document.form1.z01_nome.value = chave;
      if(erro==true){
         document.form1.la04_i_cgm.focus();
         document.form1.la04_i_cgm.value = '';
      }
   }
   function js_mostracgm1(chave1,chave2){
      document.form1.la04_i_cgm.value = chave1;
      document.form1.z01_nome.value = chave2;
      db_iframe_cgm.hide();
   } 
//lookup
  function js_pesquisala02_i_turnoatend(mostra){
      if(mostra==true){
         js_OpenJanelaIframe('','db_iframe_sau_turnoatend','func_sau_turnoatend.php?funcao_js=parent.js_mostrasau_turnoatend1|sd43_cod_turnat|sd43_v_descricao','Pesquisa de Turno de Atendimento',true);
         
      }else{
         if(document.form1.la02_i_turnoatend.value != ''){
            js_OpenJanelaIframe('','db_iframe_sau_turnoatend','func_sau_turnoatend.php?pesquisa_chave='+document.form1.la02_i_turnoatend.value+'&funcao_js=parent.js_mostrasau_turnoatend','Pesquisa',false);
         }else{
            document.form1.sd43_v_descricao.value = '';
         }
      }
  }
  function js_mostrasau_turnoatend(chave,erro){
     document.form1.sd43_v_descricao.value = chave;
     if(erro==true){
        document.form1.la02_i_turnoatend.focus();
        document.form1.la02_i_turnoatend.value = '';
     }
  }
  function js_mostrasau_turnoatend1(chave1,chave2){
      document.form1.la02_i_turnoatend.value = chave1;
      document.form1.sd43_v_descricao.value = chave2;
      db_iframe_sau_turnoatend.hide();
  }



//Valida dados e monta horario 
  function js_valida(){
      if(F.la02_i_tipo.value=='0'){
          alert('Selecione um tipo');
          return false;
      }
      return true;
  }
</script>