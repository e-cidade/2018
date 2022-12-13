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

//MODULO: projetos

$clobras->rotulo->label();
$clobraspropri->rotulo->label();
$clobraslote->rotulo->label();
$clobraslotei->rotulo->label();
$clobrasender->rotulo->label();
$clobrasresp->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("ob02_descr");
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("z01_nome");
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("ob15_crea");
$clrotulo->label("j01_matric");
$clrotulo->label("p58_requer");


?>
<form class="container" name="form1" id="form1" method="post" action="">
  <fieldset>
    <legend>Obra</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tob01_codobra?>">
          <?=@$Lob01_codobra?>
        </td>
        <td> 
          <?
            db_input('ob01_codobra',10,$Iob01_codobra,true,'text',3,"");
            db_input('ob01_regular',10,$Iob01_regular,true,'hidden',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tob01_nomeobra?>">
          <?=@$Lob01_nomeobra?>
        </td>
        <td> 
          <?
            db_input('ob01_nomeobra', 54,$Iob01_nomeobra,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=@$Tob01_dtobra?>">
          <?=@$Lob01_dtobra?>
        </td>
        <td>
          <? 
            db_inputdata('ob01_dtobra', @$ob01_dtobra_dia, @$ob01_dtobra_mes, @$ob01_dtobra_ano, true, 'text', $db_opcao)
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tob01_tiporesp?>">
          <?=@$Lob01_tiporesp?>
        </td>
        <td id="tipo_responsavel"> 
          <?
            db_selectrecord("ob01_tiporesp",$clobrastiporesp->sql_record($clobrastiporesp->sql_query_file()),true,$db_opcao,"","ob01_tiporesp","","","js_mudaresp()");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tob03_numcgm?>">
          <?
            db_ancora(@$Lob03_numcgm,"js_pesquisaob03_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('ob03_numcgm',10,$Iob03_numcgm,true,'text',3," onchange='js_pesquisaob03_numcgm(false);'")
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
          ?>
        </td>
      </tr>
      <?
      if(@$ob01_regular == 't'){
      ?>
        <tr>
          <td title="<?=$Tj01_matric?>">
          <?
            db_ancora($Lj01_matric, 'js_pesquisaMatricula(true)', $db_opcao)
          ?>
          </td>
          <td>
          <?
            db_input('j01_matric', 10, $Ij01_matric, true, 'text', $db_opcao, "onchange='js_pesquisaMatricula(false)'");
            
            db_input('z01_nome_matricula'  , 40, $Ij01_matric, true, 'text', 3);
          ?>
          </td>
        </tr>          
      <?
      }else{
        db_input('ob05_idbql',6,$Iob05_idbql,true,'hidden',3," onchange='js_pesquisaob05_idbql(false);'");
      ?>
        <tr>
          <td>
            Localização:
          </td>
          <td colspan="2" nowrap title="<?=@$Tob06_setor?>" >
            <?=@$Lob06_setor?>
            <?
              db_input('ob06_setor',9,$Iob06_setor,true,'text',$db_opcao,"")
            ?>
            <?=@$Lob06_quadra?>
            <?
              db_input('ob06_quadra',9,$Iob06_quadra,true,'text',$db_opcao,"")
            ?>
            <?=@$Lob06_lote?>
            <?
              db_input('ob06_lote',9,$Iob06_lote,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      <?
      }
      ?>
      <tr>
        <td nowrap title="<?=@$Tob10_numcgm?>">
          <?
            db_ancora(@$Lob10_numcgm,"js_pesquisaob10_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('ob10_numcgm',10,$Iob10_numcgm,true,'text',3," onchange='js_pesquisaob10_numcgm(false);'");
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'','z01_nomeresp');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Técnico">
          <?
            db_ancora("Técnico:","js_pesquisaob01_tecnico(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?
            db_input('ob15_crea'      , 10, $Iob15_crea,true, 'text'  , 3, '');
            db_input('z01_nometec'    , 40, $Iz01_nome ,true, 'text'  , 3, '');
            db_input('ob15_sequencial', 40, ""         ,true, 'hidden', 3, '');
            db_input('ob20_sequencial', 40, ""         ,true, 'hidden', 3, '');
          ?>
        </td>
      </tr>
      <tr>
        <td title="Processo existente no sistema">
          Processo do Sistema
        </td>
        <td>
          <?          
            db_select('ob01_processosistema', array('S'=>'SIM', 'N'=>'NÃO'), true, 1, "onchange='js_trocaProcesso(this.value)'") 
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <div id="processoSistemaInterno">
            <fieldset class="separator">
              <legend>Dados do Processo</legend>
              <table class="form-container">
                <tr>
                  <td title="<?=@$Tob01_processo?>">
                    <?
                      db_ancora($Lob01_processo, 'js_pesquisaProcesso(true)', 1)
                    ?>
                  </td>
                  <td>
                    <? 
                    	if (isset($ob01_processo)  and ($ob01_processosistema == "S")){
    									  $ob01_processo_1       = $ob01_processo;
    									  $ob01_nometitularproc_1 = $ob01_nometitularproc;
                    	}
                      db_input('ob01_processo_1', 10, $Iob01_processo, true, 'text', $db_opcao, "onchange='js_pesquisaProcesso(false)'");
                      db_input('ob01_nometitularproc_1', 40, $Iob01_nometitularproc, true, 'text', 3);
                    ?>
                  </td>
                </tr>
              </table> 
            </fieldset>
          </div>
          <div id="processoSistemaExterno" style="display: none;   ">
            <fieldset class="separator">
              <legend>Dados do Processo</legend>
              <table class="form-container">
                <tr>
                  <td title="<?=$Tob01_processo?>">
                    <?=$Lob01_processo?>
                  </td>
                  <td>
                    <?
                      if (isset($ob01_processo) and ($ob01_processosistema == "N")){
                      	
                      	$ob01_processo_2        = $ob01_processo;
                      	$ob01_nometitularproc_2 = $ob01_nometitularproc;
                      	
                      }
                      
                      db_input('ob01_processo_2', 10, $Iob01_processo, true, 'text', 1)
                    ?>
                  </td>
                </tr>
                <tr>
                  <td title="<?=$Tob01_nometitularproc?>">
                    <?=$Lob01_nometitularproc?>
                  </td>
                  <td>
                    <?
                      db_input('ob01_nometitularproc_2', 40, $Iob01_nometitularproc, true, 'text', 1)
                    ?>
                  </td>
                </tr>
                <tr>
                  <td title="<?=$Tob01_dtprocesso?>">
                    <?=$Lob01_dtprocesso?>
                  </td>
                  <td>
                    <?
                      db_inputdata('ob01_dtprocesso', @$ob01_dtprocesso_dia, @$ob01_dtprocesso_mes, @$ob01_dtprocesso_ano, true, 'text', $db_opcao)
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
        <td>
      </tr>
      <tr>
        <td title=<?=$Tob01_obs?> colspan="2">
  	      <fieldset class="separator">
  	        <legend><?=$Lob01_obs ?></legend>
    	      <? 
    	        db_textarea('ob01_obs', 10, 50, $Iob01_obs, true, 'text', 1)
    	      ?>	        
  	      </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
	<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.ob01_processo.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }
   
}

function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.ob01_processo.value        = iCodProcesso;
  document.form1.ob01_nometitularproc.value = sRequerente;
  db_iframe_matric.hide();
  
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {
    document.form1.ob01_processo.value = "";
    document.form1.ob01_nometitularproc.value   = sNome;
  } else {
    document.form1.ob01_nometitularproc.value   = sNome;
  }

}

js_trocaProcesso(document.form1.ob01_processosistema.value);

function js_trocaProcesso(lProcessoSistema) {

  if(lProcessoSistema == 'S') {
	  
    document.getElementById('processoSistemaInterno').style.display = '';
    document.getElementById('processoSistemaExterno').style.display = 'none';

    if (document.getElementById('ob01_processo_2') == null) {
      
      document.getElementById('ob01_processo').setAttribute('name', 'ob01_processo_2');
      document.getElementById('ob01_processo').setAttribute('id'  , 'ob01_processo_2');
      
      document.getElementById('ob01_nometitularproc').setAttribute('name', 'ob01_nometitularproc_2');
      document.getElementById('ob01_nometitularproc').setAttribute('id'  , 'ob01_nometitularproc_2');

    }

    document.getElementById('ob01_processo_1').setAttribute('name', 'ob01_processo');
    document.getElementById('ob01_processo_1').setAttribute('id'  , 'ob01_processo');

    document.getElementById('ob01_nometitularproc_1').setAttribute('name', 'ob01_nometitularproc');
    document.getElementById('ob01_nometitularproc_1').setAttribute('id'  , 'ob01_nometitularproc');
    
  } else {
    
    document.getElementById('processoSistemaInterno').style.display = 'none';
    document.getElementById('processoSistemaExterno').style.display = '';

    if (document.getElementById('ob01_processo_1') == null) {
      
      document.getElementById('ob01_processo').setAttribute('name', 'ob01_processo_1');
      document.getElementById('ob01_processo').setAttribute('id'  , 'ob01_processo_1');

      document.getElementById('ob01_nometitularproc').setAttribute('name', 'ob01_nometitularproc_1');
      document.getElementById('ob01_nometitularproc').setAttribute('id'  , 'ob01_nometitularproc_1');

    }

    document.getElementById('ob01_processo_2').setAttribute('name', 'ob01_processo');
    document.getElementById('ob01_processo_2').setAttribute('id'  , 'ob01_processo');

    document.getElementById('ob01_nometitularproc_2').setAttribute('name', 'ob01_nometitularproc');
    document.getElementById('ob01_nometitularproc_2').setAttribute('id'  , 'ob01_nometitularproc');
    
  }

}

function js_pesquisaob01_tecnico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_obrastec','func_obrastec.php?chave_tecobra=true&funcao_js=parent.js_mostraTec|z01_nome|ob15_crea|ob15_sequencial','Pesquisa',true);
  }
}
function js_mostraTec(nomeTec,creaTec,seqTec){
  document.form1.z01_nometec.value     = nomeTec;
  document.form1.ob15_crea.value        = creaTec;
  document.form1.ob15_sequencial.value = seqTec;
  db_iframe_obrastec.hide();
}



function js_mudaresp(){
  if(document.form1.ob01_tiporesp.value == 1){
    document.form1.ob10_numcgm.disabled = true
  }else{
    document.form1.ob10_numcgm.disabled = false
  }
}
function js_pesquisaob10_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgmp1|0|1','Pesquisa',true);
  }else{
     if(document.form1.ob10_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ob10_numcgm.value+'&funcao_js=parent.js_mostracgmp','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgmp(erro,chave,erro){
  document.form1.z01_nomeresp.value = chave; 
  if(erro==true){ 
    document.form1.ob10_numcgm.focus(); 
    document.form1.ob10_numcgm.value = ''; 
  }
}
function js_mostracgmp1(chave1,chave2){
  document.form1.ob10_numcgm.value = chave1;
  document.form1.z01_nomeresp.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaob07_lograd(mostra){
  if(mostra==true){
    <?
    if(@$ob01_regular == "t"){
    ?>
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruasobras.php?pesquisa_chave='+document.form1.ob05_idbql.value+'&funcao_js=parent.js_mostraruas2|j36_codigo|j14_nome|j13_codi|j13_descr','Pesquisa',true);
    <?
    }else{
    ?>
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
    <?
    }
    ?>
  }else{
     if(document.form1.ob07_lograd.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.ob07_lograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.ob07_lograd.focus(); 
    document.form1.ob07_lograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_mostraruas2(chave1,chave2,cod,bai){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.ob07_bairro.value = cod;
  document.form1.j13_descr.value = bai;
  db_iframe_ruas.hide();
}
function js_pesquisaob07_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.ob07_bairro.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.ob07_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = ''; 
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.ob07_bairro.focus(); 
    document.form1.ob07_bairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.ob07_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisaob05_idbql(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lote','func_lote.php?funcao_js=parent.js_mostralote1|j34_idbql|j34_setor|j34_quadra|j34_lote|','Pesquisa',true);
  }else{
     if(document.form1.ob05_idbql.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lote','func_lote.php?pesquisa_chave='+document.form1.ob05_idbql.value+'&funcao_js=parent.js_mostralote','Pesquisa',false);
     }else{
       document.form1.j34_setor.value = ''; 
     }
  }
}
function js_mostralote(chave,erro){
  document.form1.j34_setor.value = chave; 
  if(erro==true){ 
    document.form1.ob05_idbql.focus(); 
    document.form1.ob05_idbql.value = ''; 
  }
}
function js_mostralote1(chave1,chave2,q,l){
  document.form1.ob05_idbql.value = chave1;
  document.form1.j34_setor.value = chave2;
  document.form1.j34_quadra.value = q;
  document.form1.j34_lote.value = l;
  db_iframe_lote.hide();
}
function js_pesquisaob03_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?testanome=true&funcao_js=parent.js_mostracgm1|0|1','Pesquisa',true);
  }else{
     if(document.form1.ob03_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.ob03_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ob03_numcgm.focus(); 
    document.form1.ob03_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ob03_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisaob01_tiporesp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_obrastiporesp','func_obrastiporesp.php?funcao_js=parent.js_mostraobrastiporesp1|ob02_cod|ob02_descr','Pesquisa',true);
  }else{
     if(document.form1.ob01_tiporesp.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_obrastiporesp','func_obrastiporesp.php?pesquisa_chave='+document.form1.ob01_tiporesp.value+'&funcao_js=parent.js_mostraobrastiporesp','Pesquisa',false);
     }else{
       document.form1.ob02_descr.value = ''; 
     }
  }
}
function js_mostraobrastiporesp(chave,erro){
  document.form1.ob02_descr.value = chave; 
  if(erro==true){ 
    document.form1.ob01_tiporesp.focus(); 
    document.form1.ob01_tiporesp.value = ''; 
  }
}
function js_mostraobrastiporesp1(chave1,chave2){
  document.form1.ob01_tiporesp.value = chave1;
  document.form1.ob02_descr.value = chave2;
  db_iframe_obrastiporesp.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_obras','func_obras.php?funcao_js=parent.js_preenchepesquisa|ob01_codobra','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obras.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'pro1_obras002.php?abas=1&chavepesquisa='+chave;";
    }elseif($db_opcao == 33 || $db_opcao == 3){
      echo " location.href = 'pro1_obras003.php?abas=1&chavepesquisa='+chave;";
    }
  ?>
}

function js_pesquisaMatricula(lMostra){

    
  if (lMostra == true){
    
    js_OpenJanelaIframe('','db_iframe_matric', 'func_iptubase.php?funcao_js=parent.js_mostraMatricula|j01_matric|z01_nome','Pesquisa',true);
    
  } else {
    
    js_OpenJanelaIframe('','db_iframe_matric', 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostraMatriculaHidden','Pesquisa',false);
    
  }
}

function js_mostraMatricula(iMatricula, sNome) {

  document.form1.j01_matric.value = iMatricula;
  document.form1.z01_nome_matricula.value   = sNome;
  
  db_iframe_matric.hide();
  
}

function js_mostraMatriculaHidden(sNome, lErro) {

  if(lErro == true) {
    document.form1.j01_matric.value = "";
    document.form1.z01_nome_matricula.value   = sNome;
  } else {
    document.form1.z01_nome_matricula.value   = sNome;
  }

}


<?
if($db_opcao == 2){
?>
js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave=<?=@$ob03_numcgm?>&funcao_js=parent.js_mostracgm','Pesquisa',false);
js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave=<?=@$ob10_numcgm?>&funcao_js=parent.js_mostracgmp','Pesquisa',false);
<?
}
?>
if(document.form1.ob01_tiporesp.value == 1){
  document.form1.ob10_numcgm.disabled = true
}
</script>
<script>

<?php if($db_opcao != 3 || $db_opcao == 33){?>
$("ob01_tiporesp").setAttribute("rel","ignore-css");
$("ob01_tiporesp").addClassName("field-size2");
$("ob01_tiporespdescr").setAttribute("rel","ignore-css");
$("ob01_tiporespdescr").addClassName("field-size7");
<?php }else{?>
$("ob02_descr").addClassName("field-size7");
<?php }?>

</script>