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

//MODULO: atendimento
$clclientes->rotulo->label();
      if($db_opcao==1){
 	   $db_action="ate1_clientes004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="ate1_clientes005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="ate1_clientes006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
  <fieldset>
    <legend>
     <b>Cadastro de Clientes</b>
    </legend>
		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tat01_codcli?>">
		      <?=@$Lat01_codcli?>
		    </td>
		    <td> 
					<?
					  db_input('at01_codcli',5,$Iat01_codcli,true,'text',3,"")
					?>
		    </td>
        <td align="right" nowrap title="<?=@$Tat01_cnpj?>">
          <?=@$Lat01_cnpj?>
        </td>
        <td align="right"> 
          <?
            db_input('at01_cnpj',15,$Iat01_cnpj,true,'text',$db_opcao,"")
          ?>
        </td>		    
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tat01_nomecli?>">
		      <?=@$Lat01_nomecli?>
		    </td>
		    <td colspan="3"> 
					<?
					  db_input('at01_nomecli',40,$Iat01_nomecli,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
      <tr>
        <td nowrap title="<?=@$Tat01_uf?>">
          <?=@$Lat01_uf?>
        </td>
        <td colspan="3"> 
          <?

            require_once("classes/db_db_uf_classe.php");
            
            $clDBUf = new cl_db_uf();
            $rsUf   = $clDBUf->sql_record($clDBUf->sql_query_file(null,"db12_codigo,db12_uf"));
            
            db_selectrecord('at01_uf',$rsUf,true,$db_opcao,"style='width:90px'",'','','','',1);
          
          ?>
        </td>
      </tr>      
      <tr>
        <td nowrap title="<?=@$Tat01_cidade?>">
          <?=@$Lat01_cidade?>
        </td>
        <td colspan="3"> 
          <?
            db_input('at01_cidade',40,$Iat01_cidade,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tat01_ender?>">
          <?=@$Lat01_ender?>
        </td>
        <td colspan="3"> 
          <?
            db_input('at01_ender',40,$Iat01_ender,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tat01_cep?>">
          <?=@$Lat01_cep?>
        </td>
        <td> 
          <?
            db_input('at01_cep',10,$Iat01_cep,true,'text',$db_opcao,"")
          ?>
        </td>
        <td align="right" nowrap title="<?=@$Tat01_telefone?>">
          <?=@$Lat01_telefone?>
        </td>
        <td align="right"> 
          <?
            db_input('at01_telefone',15,$Iat01_telefone,true,'text',$db_opcao,"")
          ?>
        </td>        
      </tr>
		  <tr>
		    <td nowrap title="<?=@$Tat01_site?>">
		      <?=@$Lat01_site?>
		    </td>
		    <td colspan="3"> 
					<?
					  db_input('at01_site',40,$Iat01_site,true,'text',$db_opcao,"")
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tat01_status?>">
	        <?=@$Lat01_status?>
		    </td>
		    <td> 
					<?
						$x = array("f"=>"NAO","t"=>"SIM");
						db_select('at01_status',$x,true,$db_opcao,"style='width:90px'");
					?>
		    </td>
        <td align="right" nowrap title="<?=@$Tat01_sigla?>">
          <?=@$Lat01_sigla?>
        </td>
        <td align="right"> 
          <?
            db_input('at01_sigla',15,$Iat01_sigla,true,'text',$db_opcao,"")
          ?>
        </td>		    
		  </tr>
      <? if ( $db_opcao != 3 && $db_opcao != 33 ) { ?>      
		  <tr>
        <td nowrap title="<?=@$Tat01_codver?>">
          <?=@$Lat01_codver?>
        </td>
        <td colspan="3"> 
          <?
	            $sql = " select db30_codver , '2.'||db30_codversao ||'.'|| db30_codrelease as versao from db_versao order by db30_codver";
	            db_selectrecord("at01_codver",pg_exec($sql),true,$db_opcao,"style='width:90px'",'','','','',1);
          ?>
        </td>		    
		  </tr>
       <? } ?>
      <tr>
        <td nowrap title="<?=@$Tat01_tipocliente?>">
          <?=@$Lat01_tipocliente?>
        </td>
        <td colspan="3"> 
          <?
          
            $aTipoCliente = array("1"=>"Prefeitura",
                                  "2"=>"Câmara",
                                  "3"=>"RPPS",  
                                  "4"=>"Autarquias/Fundações",
                                  "5"=>"Autarquias de Saneamentos",
                                  "6"=>"Empresa Mista",
                                  "7"=>"Outros");
          
            db_select('at01_tipocliente',$aTipoCliente,true,$db_opcao,"style='width:300px'");
            
          ?>
        </td>
      </tr>           		  
		  <tr>
		    <td nowrap title="<?=@$Tat01_ativo?>">
		      <?=@$Lat01_ativo?>
		    </td>
		    <td> 
					<?
						$x = array("f"=>"NAO","t"=>"SIM");
						db_select('at01_ativo',$x,true,$db_opcao,"style='width:90px;'");
					?>
		    </td>
		    <td align="right" nowrap title="<?=@$Tat01_base?>">
	        <?=@$Lat01_base?>
		    </td>
		    <td align="right" > 
					<?
						$x = array("f"=>"NAO","t"=>"SIM");
						db_select('at01_base',$x,true,$db_opcao,"style='width:90px;'");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="4">
		      <fieldset>
		        <legend>
		          <?=@$Lat01_obs?>
		        </legend>
			      <table>
			        <tr>
				        <td> 
				          <?
				            db_textarea('at01_obs',10,48,$Iat01_obs,true,'text',$db_opcao,"")
				          ?>
				        </td>		        
			        </tr>
			      </table>
		      </fieldset>
		    </td>
		  </tr>
		  </table>
		</fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_clientes','db_iframe_clientes','func_clientes.php?funcao_js=parent.js_preenchepesquisa|at01_codcli','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_clientes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>