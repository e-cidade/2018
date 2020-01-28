<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: protocolo
$clprocarquiv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_requer");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
      <fieldset>
        <legend><b>Dados do Arquivamento</legend>
        <table>
					<tr>
        		<td><b>Processo:</b></td>
        		<td>
        			<?php 
        				db_input('p58_codproc', 10, false, true, 'text', 3);
        			?>
        		</td>
        	</tr>
					<tr>
        		<td><b>Requerente:</b></td>
        		<td>
        			<?php 
        				db_input('p58_requer', 50, false, true, 'text', 3);
        			?>
        		</td>
        	</tr>
          <tr>
            <td nowrap title="Usuário">
              <b>Usuário:</b> 
            </td>
            <td> 
             <?
               $rsUsuario = db_query("select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario"));
               echo pg_result($rsUsuario,0,"nome");  
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Departamento">
              <b>Departamento:</b> 
            </td>
            <td> 
             <?
               $rsDepartamento = db_query("select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto"));
               echo pg_result($rsDepartamento,0,"descrdepto");  
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp67_codarquiv?>">
               <?=@$Lp67_codarquiv?>
            </td>
            <td> 
              <?
              db_input('p67_codarquiv',10,$Ip67_codarquiv,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp67_dtarq?>">
               <?=@$Lp67_dtarq?>
            </td>
            <td> 
              <?
              if (empty($y30_data_dia)) {
                
                $p67_dtarq_dia = date("d",db_getsession("DB_datausu"));
                $p67_dtarq_mes = date("m",db_getsession("DB_datausu"));
                $p67_dtarq_ano = date("Y",db_getsession("DB_datausu"));
              } 
              db_inputdata('p67_dtarq',@$p67_dtarq_dia,@$p67_dtarq_mes,@$p67_dtarq_ano,true,'text',$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tp67_historico?>" colspan="2">
            	<fieldset>
            		<legend><b><?=@$Lp67_historico?>:</b></legend>
	              <?php 
	              	db_textarea('p67_historico',6,65,$Ip67_historico,true,'text',$db_opcao,"");
	              ?>
            	</fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>      
  </tr>
</table>
<br/>
<input type="hidden" id="grupo" name="grupo" value="<?=$grupo?>">
<input name="db_opcao"  type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="btnPesquisaProcessoOuvidoria" type="button" id="btnPesquisaProcessoOuvidoria" 
       value="Pesquisar" onclick="js_pesquisaProcessoOuvidoria(true);" >
<input name="pesquisar" type="button" id="pesquisar" 
       value="Pesquisar Arquivamento" onclick="js_pesquisa();" >
</center>
</form>
<script>

<?

  if ( $grupo == 1 ) {
    
    echo "function js_pesquisap67_codproc(mostra){
					  if(mostra==true){
					    db_iframe.jan.location.href = 'func_protprocessoarquiv.php?grupo=1&atend=false&funcao_js=parent.js_mostraprotprocesso1|0|1';
					    db_iframe.mostraMsg();
					    db_iframe.show();
					    db_iframe.focus();
					  }else{
					    db_iframe.jan.location.href = 'func_protprocessoarquiv.php?grupo=1&atend=false&pesquisa_chave='+document.form1.p67_codproc.value+'&funcao_js=parent.js_mostraprotprocesso';
					  }
					}";  	
  } else {
    echo "function js_pesquisap67_codproc(mostra){
            if(mostra==true){
              db_iframe.jan.location.href = 'func_protprocessoarquivouvidoria.php?funcao_js=parent.js_mostraprotprocesso1|0|1';
              db_iframe.mostraMsg();
              db_iframe.show();
              db_iframe.focus();
            }else{
              db_iframe.jan.location.href = 'func_protprocessoarquivouvidoria.php?pesquisa_chave='+document.form1.p67_codproc.value+'&funcao_js=parent.js_mostraprotprocesso';
            }
          }";
  }

?>


function js_pesquisaProcessoOuvidoria(lMostra) {

  var iGrupo = $('grupo').value;
  var sUrlOpenProcessoOuvidoria = '';
  
  if(iGrupo == 1) {
    sUrlOpenProcessoOuvidoria = "func_protprocessoarquiv.php?";
  } else {
    sUrlOpenProcessoOuvidoria = "func_protprocessoarquivouvidoria.php?";
  }  
	if (lMostra) {
	  sUrlOpenProcessoOuvidoria = sUrlOpenProcessoOuvidoria+"funcao_js=parent.js_preenchePesquisa|p58_codproc|p58_requer";
	}
  js_OpenJanelaIframe('', 'db_iframe_protprocessoarquivouvidoria', sUrlOpenProcessoOuvidoria, "Pesquisa Processo de Ouvidoria", lMostra);
}

function js_preenchePesquisa(iProcesso, sRequerente) {

  $('p58_codproc').value = iProcesso;
  $('p58_requer').value  = sRequerente;
  db_iframe_protprocessoarquivouvidoria.hide();
}

function js_pesquisa(){
  db_iframe.jan.location.href = 'func_procarquiv.php?funcao_js=parent.js_preenchepesquisa|p67_codarquiv ';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
js_pesquisaProcessoOuvidoria(true);
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>