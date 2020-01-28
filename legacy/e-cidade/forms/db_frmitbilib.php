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

//MODULO: itbI
$clitbi->rotulo->label();
$clitbiavalia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("");

?>

<table width="290" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
			<fieldset>
			  <legend>
			    <b>Alteração de ITBI Liberada</b>
			  </legend>
			  <table border="0">
			    <tr>
			      <td title="<?=@$Tit01_guia?>">
			        <?=@$Lit01_guia?>&nbsp;
			      </td>
			      <td>
			        <?
			          db_input('it01_guia',15,$Iit01_guia,true,'text',3,"");
			        ?>   
			      </td>
			    </tr>
			    <tr>
			      <td title="<?=@$Tit01_data?>">
			        <?=@$Lit01_data?>&nbsp;
			      </td>
			      <td>
			        <?
			          if ( isset($it01_data) && $it01_data != "" ){
			            
			            $data = split('-',$it01_data);
			            $dia  = $data[2];
			            $mes  = $data[1];
			            $ano  = $data[0];
			          }
			          
			          db_inputdata("it01_data",@$dia,@$mes,@$ano,true,'text',$db_opcao)
			        ?>  
			      </td>
			    </tr>    
			    <tr>
			      <td title="<?=@$Tit14_dtvenc?>">
			        <?=@$Lit14_dtvenc?>&nbsp;
			      </td>
			      <td>
			        <?
			          if ( isset($it14_dtvenc) && $it14_dtvenc != "" ) {
			            
			            $data = split('-',$it14_dtvenc);
			            $dia1 = $data[2];
			            $mes1 = $data[1];
			            $ano1 = $data[0];
			          }
			          
			          db_inputdata("it14_dtvenc",@$dia1,@$mes1,@$ano1,true,'text',$db_opcao);
			        ?>
			      </td>
			    </tr>  
			    <tr>
			      <td title="<?=@$Tit14_dtliber?>">
			        <?=@$Lit14_dtliber?>&nbsp;
			      </td>
			      <td>
			        <?
			          if ( isset($it14_dtliber) && $it14_dtliber != "" ){
			            
			            $data = split('-',$it14_dtliber);
			            $dia2 = $data[2];
			            $mes2 = $data[1];
			            $ano2 = $data[0];
			          }
			          
			          db_inputdata("it14_dtliber",@$dia2,@$mes2,@$ano2,true,'text',$db_opcao);
			        ?>
			      </td>
			    </tr>
			    <tr>              
			  </table>
			</fieldset>
			<table align="center" border="0">
			  <tr>
			    <td>
			      <input name="db_opcao" type="submit" id="db_opcao" 
			             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
			             <?=($db_botao==false?"disabled":"")?>  >  
			    </td>
			    <td>
			      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
			    </td>
			  </tr>
			</table> 
    </td>
  </tr>
</table>

<script>
function js_pesquisa(){
  js_OpenJanelaIframe('',
                      'db_iframe_itbi',
                      'func_itbilib.php?funcao_js=parent.js_preenchepesquisa|it01_guia','Pesquisa',true,0);
}

function js_preenchepesquisa(chave){
  db_iframe_itbi.hide();
  <?
    if($db_opcao == 2 || $db_opcao == 22){
      echo " location.href = 'itb1_itbilib002.php?chavepesquisa='+chave;";
    }
  ?>
}
</script>