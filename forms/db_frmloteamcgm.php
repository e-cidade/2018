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

//MODULO: cemiterio
$clloteam->rotulo->label();
$clloteamcgm->rotulo->label();
$clcgm->rotulo->label();
?>
<form name="form1" method="post" action="">
<table align="center" style="padding-top:15px;" border="0">
  <tr>
    <td>
      <table border="0" align="center">
        <tr>
          <td>
		        <?
		          db_input('j34_loteam',10,$Ij34_loteam,true,'text',3,"");
		          
		          if ( isset($oPost->j120_sequencial) ) {
		          	db_input('j120_sequencial',10,$Ij120_sequencial,true,'hidden',3,"");
		          }
		        ?>          
          </td>
          <td>
            <?
              db_input('j34_descr',40,$Ij34_descr,true,'text',3,"");
            ?>          
          </td>          
        </tr>
        <tr>
          <td>
            <?
              db_ancora('<b>CGM:</b>',' js_pesquisacgm(true); ',$db_opcao);
            ?>
          </td>
          <td> 
            <?
              db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisacgm(false)'");
              db_input('z01_nome',30,0,true,'text',3,"");
            ?>
          </td>
        </tr>          
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" 
             id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
             <?=($db_botao==false?"disabled":"")?>>
      <input name="novo" type="submit" id="cancelar" value="Novo" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  <tr>
    <td>
      <table>
        <tr>
          <td valign="top"  align="center">  
            <?
            
              $aChavePri = array( "j120_sequencial" => @$j120_sequencial,
                                  "j120_loteam"     => @$j120_cgm,
                                  "j120_cgm"        => @$j120_cgm);
        
              $sCampo    = "j120_sequencial,j120_loteam,j120_cgm";
              
              $cliframe_alterar_excluir->chavepri      = $aChavePri;
              $cliframe_alterar_excluir->sql           = $clloteamcgm->sql_query(null,"loteamcgm.*",null," j120_loteam = {$oGet->codigo}");
              $cliframe_alterar_excluir->campos        = $sCampo;
              $cliframe_alterar_excluir->legenda       = "Loteamento CGM";
              $cliframe_alterar_excluir->iframe_height = "160";
              $cliframe_alterar_excluir->iframe_width  = "700";
              $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
              
            ?>
          </td>
        </tr>
      </table>    
    </td>
  </tr>
</table>   
</body>
</html>
</form>
<script>
function js_pesquisacgm(mostra) {

  var cgm = $('z01_numcgm').value;
  var sUrl1 = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
  var sUrl2 = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_cgm',sUrl1,'Pesquisa',true,0);
  } else {
    js_OpenJanelaIframe('','db_iframe_cgm',sUrl2,'Pesquisa',false);
  }
}

function js_mostracgm(chave1,chave2) {

  $('z01_numcgm').value      = chave1;
  $('z01_nome').value = chave2;
  db_iframe_cgm.hide();
}

function js_mostracgm1(erro,chave) {

  $('z01_nome').value = chave; 
  if (erro == true) { 
  
    $('z01_numcgm').focus(); 
    $('z01_numcgm').value  = ''; 
    $('z01_nome').value = '';
  }
}
</script>