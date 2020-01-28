<?php
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

//MODULO: patrim
$clbenstransf->rotulo->label();
$clbenstransfdes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");
      if($db_opcao==1){
 	   $db_action="pat1_benstransf004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="pat1_benstransf005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="pat1_benstransf006.php";
      }        
$idus       = db_getsession("DB_id_usuario");
$iddepart   = db_getsession("DB_coddepto");
$t93_instit = db_getsession("DB_instit");

$libera = false;
if($db_opcao==2 || isset($chavepesquisa)){
  if(isset($pesquisachave) && $chavepesquisa!=''){
    $t93_codtran = $chavepesquisa;
  }
  $result_libera = $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file($t93_codtran));
  if($clbenstransfcodigo->numrows>0){
    $libera=true;
  }
}

$usu = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($idus,"nome"));
if($cldb_usuarios->numrows > 0){
  db_fieldsmemory($usu,0);
  $t93_id_usuario = $idus;
}
$depart = $cldb_depart->sql_record($cldb_depart->sql_query_file($iddepart,"descrdepto"));
if($cldb_depart->numrows){
  db_fieldsmemory($depart,0);
  $t93_depart = $iddepart;
}
?>
<form class="container" name="form1" method="post" action="<?=$db_action?>">
  <fieldset>
    <legend>Transferência de Bens</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tt93_codtran?>">
          <?=@$Lt93_codtran?>
        </td>
        <td> 
          <?
            db_input('t93_instit',10,$It93_instit,true,"hidden",3,"");
            db_input('t93_codtran',8,$It93_codtran,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt93_id_usuario?>">
          <?
            db_ancora(@$Lt93_id_usuario,"",3);
          ?>
        </td>
        <td> 
          <?
            db_input('t93_id_usuario',8,$It93_id_usuario,true,'text',3,"");
            db_input('nome',40,$Inome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt93_depart?>">
          <?
            db_ancora(@$Lt93_depart,"",3);
          ?>
        </td>
        <td> 
          <?
            db_input('t93_depart',8,@$It93_depart,true,'text',3,"");
            db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt94_depart?>">
          <?
            db_ancora(@$Lt94_depart,"js_pesquisat94_depart(true);",$db_opcao);
          ?>
        </td>
        <td> 
          <?
            db_input('t94_depart',8,$It94_depart,true,'text',$db_opcao," onchange='js_pesquisat94_depart(false);'");
            db_input('descrdepto',40,$Idescrdepto,true,'text',3,'',"depto_destino");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tt93_data?>">
          <?=@$Lt93_data?>
        </td>
        <td> 
          <?
            if (!isset($t93_data)){
            	$t93_data_ano = date('Y',db_getsession("DB_datausu"));
            	$t93_data_mes = date('m',db_getsession("DB_datausu"));
            	$t93_data_dia = date('d',db_getsession("DB_datausu"));
            }
            db_inputdata('t93_data',@$t93_data_dia,@$t93_data_mes,@$t93_data_ano,true,'text',$db_opcao,"");
            db_input('db_param',3,0,true,'hidden',3)
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" title="<?=@$Tt93_obs?>">
          <fieldset class="separator">
            <legend><?=@$Lt93_obs?></legend>
            <?php db_textarea("t93_obs",10,50,$It93_obs,true,"text",$db_opcao, null, null, null, 400); ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  <?=(($db_opcao==1||$db_opcao==2||$db_opcao==22)?"onClick = 'return ver_depto_destino()'":"")?>>
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
  <input name="relatorio" type="button" id="relatorio" value="Relatório" onclick="js_imprime();" <?=($libera==true&&$db_opcao!=3&&$db_opcao!=33&&$db_opcao!=1)?"":"disabled"?>>
</form>
<script>
function ver_depto_destino(){
  if ( document.form1.t94_depart.value == "" || document.form1.t94_depart.value == null ) {
	  
    alert(_M("patrimonial.patrimonio.db_frmbenstransf.infrome_departamento"));
    document.form1.t94_depart.style.backgroundColor='#99A9AE';
    document.form1.t94_depart.focus();
    return false;
  }else{
    document.form1.t94_depart.style.backgroundColor='';
    return true; 
  }
}

function js_pesquisat94_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_benstransf','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto&chave_t93_depart='+document.form1.t93_depart.value+'&db_param=<?=($db_param)?>','Pesquisa',true);
  }else{
     if(document.form1.t94_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_benstransf','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t94_depart.value+'&funcao_js=parent.js_mostradb_depart&chave_t93_depart='+document.form1.t93_depart.value+'&db_param=<?=($db_param)?>','Pesquisa',false);
     }else{
       document.form1.t94_depart.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.depto_destino.value = chave; 
  if(erro==true){ 
    document.form1.t94_depart.focus(); 
    document.form1.t94_depart.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t94_depart.value = chave1;
  document.form1.depto_destino.value = chave2;
  db_iframe_depart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_benstransf','db_iframe_benstransf','func_benstransf001.php?funcao_js=parent.js_preenchepesquisa|t93_codtran&t93=true&db_param=<?=($db_param)?>','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_benstransf.hide();
  <?
    if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&db_param=$db_param'";
  }
  ?>
}
function js_imprime(){
  jan = window.open('pat2_relbenstransf002.php?t96_codtran='+document.form1.t93_codtran.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);  
}
</script>
<script>

$("t93_codtran").addClassName("field-size2");
$("t93_id_usuario").addClassName("field-size2");
$("nome").addClassName("field-size7");
$("t93_depart").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("t94_depart").addClassName("field-size2");
$("depto_destino").addClassName("field-size7");
$("t93_data").addClassName("field-size2");


</script>