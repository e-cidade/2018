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

//MODULO: patrim
$clcfpatri->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_descr");    
$clrotulo->label("t40_codigo");
$clrotulo->label("t40_descr");
$clrotulo->label("t71_descr");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Configuração de Parâmetros</legend>
  	<table class="form-container">
  	  <tr>
  	    <td title="<?=@$Tt06_codcla?>">
  	      <?
  	        db_ancora(@$Lt06_codcla,"js_pesquisat06_codcla(true);",$db_opcao);
  	      ?>
  	    </td>
  	    <td> 
  				<?
  				  db_input('t06_codcla',8,$It06_codcla,true,'text',$db_opcao," onchange='js_pesquisat06_codcla(false);'")
  				?>
  	      <?
  				  db_input('db77_descr',40,$Idb77_descr,true,'text',3,'')
  	      ?>
  	    </td>
  	  </tr>
  	  <tr>
  	    <td title="<?=@$Tt06_pesqorgao?>">
  	      <?=@$Lt06_pesqorgao?>
  	    </td>
  	    <td> 
    			<?php
      			$apesqorgao = array('f'=>'Não','t'=>'Sim');
      			db_select('t06_pesqorgao',$apesqorgao,true,$db_opcao);	
  			  ?>
  	    </td>
  	  </tr>
  	  <tr>
  	    <td title="<?=@$Tt06_bensmodeloetiqueta?>">
  	      <?php
            db_ancora(@$Lt06_bensmodeloetiqueta,"js_pesquisat06_bensmodeloetiqueta(true);",$db_opcao);
          ?>
  	    </td>
  	    <td> 
  	      <?php
            db_input('t06_bensmodeloetiqueta',8,$It06_bensmodeloetiqueta,true,'text',$db_opcao," onchange='js_pesquisat06_bensmodeloetiqueta(false);'")
          ?>
          <?php
            db_input('t71_descr',40,$It71_descr,true,'text',3,'');
          ?>
  	    </td>
  	  </tr>
  	    
  	  <tr>
  	    <td title="<?=$Tt06_controlaplacainstituicao?>" id="t06_controlaplacainstituicao_label">
  	      <?=$Lt06_controlaplacainstituicao?>
  	    </td>
  	    
    	  <td  title="<?=$Tt06_controlaplacainstituicao?>" id="t06_controlaplacainstituicao">
      	  <?php
      	  
      	  /**
      	   * Verifica se instituição é prefeitura
      	   * caso for prefeitura, terá acesso a modificação no parâmetro 
      	   */
      	  define("PREFEITURA", "1");
      	  define("OUTRA_INSTITUICAO", "3");
      	  $iOpcaoGlobal = OUTRA_INSTITUICAO;
      	  $iCodigoInstituicao = db_getsession("DB_instit");
      	  $oDaoDbconfig       = db_utils::getDao("db_config");
      	  $sWhere             = "codigo = {$iCodigoInstituicao} and prefeitura is true";   
      	  $sSQL               = $oDaoDbconfig->sql_query_file(null, "*", null, $sWhere);
      	  $rsResultado        = $oDaoDbconfig->sql_record($sSQL);
      	  
      	  if ($oDaoDbconfig->numrows > 0) {
            $iOpcaoGlobal = PREFEITURA;
      	  }
      	 
      	  $aOpcoes = array("t"=> "Sim", "f"=>"Não");
          db_select('t06_controlaplacainstituicao', $aOpcoes, true, $iOpcaoGlobal);
          ?>
    	  </td>
  	  </tr>
  	  
  	</table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"alterar"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Alterar"))?>" <?=($db_botao==false?"disabled":"")?> >
  <!-- <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" > -->
</form>
<script>
function js_pesquisat06_codcla(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura',
                        'func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.t06_codcla.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura',
                            'func_db_estrutura.php?pesquisa_chave='+document.form1.t06_codcla.value+'&funcao_js=parent.js_mostradb_estrutura',
                            'Pesquisa',false);
     }else{
       document.form1.db77_descr.value = ''; 
     }
  }
}
function js_mostradb_estrutura(chave,erro){
  document.form1.db77_descr.value = chave; 
  if(erro==true){ 
    document.form1.t06_codcla.focus(); 
    document.form1.t06_codcla.value = ''; 
  }
}
function js_mostradb_estrutura1(chave1,chave2){
  document.form1.t06_codcla.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframedb_estrutura.hide();
}

function js_pesquisat06_bensmodeloetiqueta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiqueta', 
                        'func_bensmodeloetiqueta.php?funcao_js=parent.js_bensmodeloetiqueta1'+
                        '|t71_sequencial|t71_descr',
                        'Pesquisa',
                        true
                        );
  }else{
     if(document.form1.t06_bensmodeloetiqueta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bensmodeloetiqueta',
                            'func_bensmodeloetiqueta.php?pesquisa_chave='+document.form1.t06_bensmodeloetiqueta.value+
                            '&funcao_js=parent.js_bensmodeloetiqueta',
                            'Pesquisa',false);
     }else{
       document.form1.t71_descr.value = ''; 
     }
  }
}
function js_bensmodeloetiqueta(chave,erro){
  document.form1.t71_descr.value = chave; 
  if(erro==true){ 
    document.form1.t06_bensmodeloetiqueta.focus(); 
    document.form1.t06_bensmodeloetiqueta.value = ''; 
  }
}
function js_bensmodeloetiqueta1(chave1,chave2){
  document.form1.t06_bensmodeloetiqueta.value = chave1;
  document.form1.t71_descr.value = chave2;
  db_iframe_bensmodeloetiqueta.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cfpatri','func_cfpatri.php?funcao_js=parent.js_preenchepesquisa|t06_codcla','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cfpatri.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>
<script>

$("t06_codcla").addClassName("field-size2");
$("db77_descr").addClassName("field-size7");
$("t06_bensmodeloetiqueta").addClassName("field-size2");
$("t71_descr").addClassName("field-size7");
$("t06_pesqorgao").setAttribute("rel","ignore-css");
$("t06_pesqorgao").addClassName("field-size2");

</script>