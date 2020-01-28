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

//MODULO: issqn
$clissnotaavulsa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("q02_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q51_inscr");
$clrotulo->label("q51_numnota");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td>
 <fieldset><legend><b>Consulta de Notas Avulsas</b></legend>
 <table>
  <tr>
    <td nowrap title="<?=@$Tq51_numnota?>">
       <?
       db_ancora(@$Lq51_numnota,"js_pesquisaq51_sequencial(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
       db_input('q51_sequencial',10,$Iq51_sequencial,true,'hidden',$db_opcao," onchange='js_pesquisaq51_sequencial(false);'");
       db_input('q51_numnota',10,$Iq51_numnota,true,'text',$db_opcao," onchange='js_pesquisaq51_sequencial(false);'");
      ?>
    </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_inscr?>">
       <?
       db_ancora(@$Lq51_inscr,"js_pesquisaq51_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
       db_input('q51_inscr',10,$Iq51_inscr,true,'text',$db_opcao," onchange='js_pesquisaq51_inscr(false);'")
      ?>
      <?
      db_input('z01_nome',35,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
       <?
       db_ancora(@$Lz01_nome,"js_pesquisaz01_nome(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
       db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_nome(false);'")
      ?>
      <?
      db_input('z01_nome2',35,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Tq51_dtemiss?>">
       <?=@$Lq51_dtemiss?>
    </td>
    <td> 
      <?
       db_inputdata('q51_dtemissini',@$q51_dtemiss_dia,@$q51_dtemiss_mes,@$q51_dtemiss_ano,true,'text',$db_opcao,"")
      ?>
      <b>A</b> 
      <?
       db_inputdata('q51_dtemissfim',@$q51_dtemiss_dia,@$q51_dtemiss_mes,@$q51_dtemiss_ano,true,'text',$db_opcao,"")
      ?>
     
    </td>
     
  </tr>

  </table>
	</fieldset></td></tr>
	</table>
  </center>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_show();" >
</form>
<script>
function js_pesquisaq51_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.q51_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.q51_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_pesquisaq51_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_issnotaavulsaalt.php?funcao_js=parent.js_mostraq51_sequencial|q51_sequencial|q51_numnota','Pesquisa',true);
  }else{
     if(document.form1.q51_numnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_issnotaavulsaalt.php?pesquisa_chave='+document.form1.q51_numnota.value+'&funcao_js=parent.js_mostraq51_sequencial1','Pesquisa',false);
     }else{
      document.form1.q51_sequencial.value = '';

     }
  }
}

function js_mostraq51_sequencial1(chave,erro,chave2){
  if(erro==true){ 
    document.form1.q51_numnota.focus(); 
    document.form1.q51_numnota.value = ''; 
  }else{
     
     document.form1.q51_sequencial.value = chave2;
  }
}
function js_mostraq51_sequencial(chave1,chave2){
  document.form1.q51_sequencial.value = chave1;
  document.form1.q51_numnota.value    = chave2;
  db_iframe_db_usuarios.hide();
}


function js_pesquisaq51_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.q51_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q51_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostraissbase(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q51_inscr.focus(); 
    document.form1.q51_inscr.value = ''; 
  }
}

function js_mostraissbase1(chave1,chave2){
  document.form1.q51_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_issbase.hide();
}

function js_pesquisaz01_nome(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Consulta CGM',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Consulta CGM',false);
     }else{
       document.form1.z01_nome2.value = ''; 
     }
  }
}

function js_mostracgm(erro,chave){
  
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome2.value  = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_issnotaavulsa','db_iframe_issnotaavulsa','func_issnotaavulsa.php?funcao_js=parent.js_preenchepesquisa|q51_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issnotaavulsa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>