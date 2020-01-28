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
$clbenstransf->rotulo->label();
$clbenstransfdes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nome");

$t93_instit = db_getsession("DB_instit");

$rsConsultaUsuario = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession("DB_id_usuario"),"nome"));

if($cldb_usuarios->numrows > 0){
  db_fieldsmemory($rsConsultaUsuario,0);
  $t93_id_usuario = db_getsession("DB_id_usuario");
}

?>
  <form class="container" name="form1" method="post" action="">
    <fieldset>
      <legend>Dados Transferência</legend>
      <table class="form-container">
			        <tr>
			          <td>
			            <?=@$Lt93_codtran?>
			          </td>  
			          <td>
							    <?
							      db_input('t93_codtran',10,$It93_codtran,true,'text',3,"");
							    ?>          
			          </td>
			        <tr>
			          <td align="right">
			            <?=@$Lt93_data?>
			          </td>
			          <td align="right">
									<?
									  if (!isset($t93_data)){
									    $t93_data_ano = date('Y',db_getsession("DB_datausu"));
									    $t93_data_mes = date('m',db_getsession("DB_datausu"));
									    $t93_data_dia = date('d',db_getsession("DB_datausu"));
									  }
									  db_inputdata('t93_data',@$t93_data_dia,@$t93_data_mes,@$t93_data_ano,true,'text',$db_opcao,"");
									?>          
			          </td>
			        </tr>
			        <tr>
			          <td>
					        <?=@$Lt93_id_usuario?>          
			          </td>  
			          <td>
								  <?
								    db_input('t93_id_usuario',10,$It93_id_usuario,true,'text',3,"");
								  ?>          
								  <?
								    db_input('nome',50,$Inome,true,'text',3,'');
								  ?>
								</td>  
			        </tr>        
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Depto. Origem</legend>
              <table class="form-container">
								<tr>
								  <td nowrap title="<?=@$Tt93_depart?>">
                    <?
                      db_ancora("<b>Depto.</b>","js_pesquisat93_depart(true);",$db_opcao);
                    ?>
								  </td>
								  <td> 
								    &emsp;&emsp;&emsp;
								    <?
								      db_input('t93_depart',10,@$It93_depart,true,'text',$db_opcao,"onChange='js_pesquisat93_depart(false);'");
								      db_input('descrdepto',40,$Idescrdepto,true,'text',3,'');
								    ?>
								  </td>
								</tr>              
                <?
                
                   if ( isset($t93_depart) && trim($t93_depart) != "" ) {
                    
                     $sCamposDiv  = " t30_codigo, ";
                     $sCamposDiv .= " t30_descr   ";
                     $sWhereDiv   = " t30_depto =  {$t93_depart} ";
                   
                     $rsConsultaDiv = $cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,$sCamposDiv,null,$sWhereDiv));
                
                     if ( $cldepartdiv->numrows > 0 ) {
                ?>   	
                <tr>
                  <td>
                    <b>Divisão :</b> 
                  </td>
                  <td> 
                    &emsp;&emsp;&emsp;
                    <?
                    
                      if ($db_opcao == 3 ) {
                      	
                        if ( $divOrigem == 0 ) {
                          $descrdivorigem = "Todas";
                        }
                         
                        db_input("divOrigem",10,"",true,"text",3);
                        db_input("descrdivorigem",40,"",true,"text",3);
                      } else {                       
                        db_selectrecord("divOrigem",$rsConsultaDiv,true,$db_opcao,"","","","0");
                      }  
    
                    ?>
                  </td>
                </tr>                      	
                <?  	
                     }
                   }
                ?>
                <tr>
                  <td>
                    <?
                      db_ancora("<b>Classificação:</b>","js_pesquisaClaBens(true);",$db_opcao);
                    ?>
                  </td>
                  <td> 
                    &emsp;&emsp;&emsp;
                    <?
                      db_input('codclabens'   ,10,"",true,'hidden',3,"");
                      db_input('estrutclabens',10,"",true,'text',$db_opcao,"onChange='js_pesquisaClaBens(false);'");
                      db_input('descrclabens' ,40,"",true,'text',3,"");
                    ?>
                  </td>
                </tr>                  
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2" >
            <fieldset class="separator">
              <legend>Depto. Destino</legend>
              <table class="form-container">
							  <tr>
							    <td nowrap title="<?=@$Tt94_depart?>">
							      <?
							        db_ancora("<b>Depto.</b>","js_pesquisat94_depart(true);",$db_opcao,"","","","0");
							      ?>
							    </td>
							    <td> 
							      &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
							      <?
							        db_input('t94_depart',10,$It94_depart,true,'text',$db_opcao," onchange='js_pesquisat94_depart(false);'");
          						db_input('descrdepto',40,$Idescrdepto,true,'text',3,'','depto_destino');
					       		?>
							    </td>
							  </tr>
                <?
                
                   if ( isset($t94_depart) && trim($t94_depart) != "" ) {
                    
                     $sCamposDiv  = " t30_codigo, ";
                     $sCamposDiv .= " t30_descr   ";
                     $sWhereDiv   = " t30_depto =  {$t94_depart} ";
                   
                     $rsConsultaDiv = $cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,$sCamposDiv,null,$sWhereDiv));
                  
                     if ( $cldepartdiv->numrows > 0 ) {
                ?>    
                <tr>
                  <td>
                    Divisão : 
                  </td>
                  <td> 
                  &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
                    <?
                    
                      if ($db_opcao == 3 ) {
                        if ( $divDestino == 0 ) {
                          $descrdivdestino = "Todas";
                        } 
                        db_input("divDestino",10,"",true,"text",3);
                        db_input("descrdivdestino",40,"",true,"text",3);
                      } else {
                        db_selectrecord("divDestino",$rsConsultaDiv,true,$db_opcao,"","","","0");
                      }
                    
                    ?>
                  </td>
                </tr>                       
                <?    
                     }
                   }
                ?>							  							                
              </table>
            </fieldset>          
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>Observações </legend>
							      <?php db_textarea("t93_obs",5,65,$It93_obs,true,"text",$db_opcao, null, null, null, 400); ?>
            </fieldset>          
          </td>        
        </tr>
      </table>
    </fieldset>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onClick="js_pesquisaTransf();">
    </form>
<script>

function js_pesquisat94_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradb_departdestino1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t94_depart.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t94_depart.value+'&funcao_js=parent.js_mostradb_departdestino','Pesquisa',false);
     }else{
       document.form1.t94_depart.value = ''; 
     }
  }
}

function js_mostradb_departdestino(chave,erro){
  document.form1.depto_destino.value = chave; 
  if(erro==true){ 
    document.form1.t94_depart.focus(); 
    document.form1.t94_depart.value = ''; 
  } else {
    document.form1.submit();
  }
}

function js_mostradb_departdestino1(chave1,chave2){
  document.form1.t94_depart.value = chave1;
  document.form1.depto_destino.value = chave2;
  db_iframe_depart.hide();
  document.form1.submit();
}



function js_pesquisat93_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto&chave_t93_depart='+document.form1.t93_depart.value,'Pesquisa',true);
  }else{
     if(document.form1.t93_depart.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t93_depart.value+'&funcao_js=parent.js_mostradb_depart&chave_t93_depart='+document.form1.t93_depart.value,'Pesquisa',false);
     }else{
       document.form1.t93_depart.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t93_depart.focus(); 
    document.form1.t93_depart.value = ''; 
  }
  document.form1.submit();
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t93_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_depart.hide();
  document.form1.submit();
}

function js_pesquisaClaBens(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_clabens','func_clabensestrut.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_codcla|t64_descr','Pesquisa',true);
  }else{
     if(document.form1.estrutclabens.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_clabens','func_clabensestrut.php?pesquisa_chave='+document.form1.estrutclabens.value+'&funcao_js=parent.js_mostraclabens','Pesquisa',false);
     }else{
       document.form1.estrutclabens.value = ''; 
     }
  }
}

function js_mostraclabens(chave1,chave2,erro){
  document.form1.descrclabens.value = chave1;
  document.form1.codclabens.value   = chave2; 
  if(erro==true){ 
    document.form1.estrutclabens.focus(); 
    document.form1.estrutclabens.value = ''; 
  }
  
}

function js_mostraclabens1(chave1,chave2,chave3){
  document.form1.estrutclabens.value   = chave1;
  document.form1.codclabens.value      = chave2;
  document.form1.descrclabens.value    = chave3;
  db_iframe_clabens.hide();
}

function js_pesquisaTransf(){
  js_OpenJanelaIframe('','db_iframe_benstransf','func_benstransflote001.php?funcao_js=parent.js_preenchepesquisa|t93_codtran','Pesquisa',true);
}


function js_preenchepesquisa(chave){
  db_iframe_benstransf.hide();
  <?
   if($db_opcao!=1){
     echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
   }
  ?>
}

</script>
<script>

$("t93_codtran").addClassName("field-size2");
$("t93_data").addClassName("field-size2");
$("t93_id_usuario").addClassName("field-size2");
$("nome").addClassName("field-size7");
$("t93_depart").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("estrutclabens").addClassName("field-size2");
$("descrclabens").addClassName("field-size7");
$("t94_depart").addClassName("field-size2");
$("depto_destino").addClassName("field-size7");
$("t93_obs").setAttribute("rel","ignore-css");
$("t93_obs").style.width = "100%";

if ( $("divOrigem") ) {
	
  $("divOrigem").setAttribute("rel","ignore-css");
  $("divOrigem").addClassName("field-size2");
  $("divOrigemdescr").setAttribute("rel","ignore-css");
  $("divOrigemdescr").addClassName("field-size7");
}

if ( $("divDestinodescr") ) {
  $("divDestino").setAttribute("rel","ignore-css");
  $("divDestino").addClassName("field-size2");
  $("divDestinodescr").setAttribute("rel","ignore-css");
  $("divDestinodescr").addClassName("field-size7");
}
</script>