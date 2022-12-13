<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: cadastro
$cliptucalhconf->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j17_descr");
$clrotulo->label("j17_descr");
?>
<fieldset>
    <legend>Configuração de Histórico de Cálculo</legend>
    <form name="form1" method="post" action="">
        <center>
            <table border="0">
                <tr>
                    <td nowrap title="<?=@$Tj89_sequencial?>">
                      <?=@$Lj89_sequencial?>
                    </td>
                    <td>
                      <?
                      db_input('j89_sequencial',10,$Ij89_sequencial,true,'text',3,"")
                      ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tj89_codhis?>">
                      <?
                      db_ancora("<b>Histórico:</b>","js_pesquisaj89_codhis(true);",$db_opcao);
                      ?>
                    </td>
                    <td>
                      <?
                      db_input('j89_codhis',10,$Ij89_codhis,true,'text',$db_opcao," onchange='js_pesquisaj89_codhis(false);'")
                      ?>
                      <?
                      db_input('j17_descr',40,$Ij17_descr,true,'text',3,'')
                      ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?=@$Tj89_codhispai?>">
                      <?
                      db_ancora("<b>Histórico pai:</b>","js_pesquisaj89_codhispai(true);",$db_opcao);
                      ?>
                    </td>
                    <td>
                      <?
                      db_input('j89_codhispai',10,$Ij89_codhispai,true,'text',$db_opcao," onchange='js_pesquisaj89_codhispai(false);'")
                      ?>
                      <?
                      db_input('j17_descr2',40,$Ij17_descr,true,'text',3,'')
                      ?>
                    </td>
                </tr>
            </table>
        </center>
</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

    function js_pesquisaj89_codhis(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalh','func_iptucalh.php?funcao_js=parent.js_mostraiptucalh1|j17_codhis|j17_descr','Pesquisa',true);
        }else{
            if(document.form1.j89_codhis.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalh','func_iptucalh.php?pesquisa_chave='+document.form1.j89_codhis.value+'&funcao_js=parent.js_mostraiptucalh','Pesquisa',false);
            }else{
                document.form1.j17_descr.value = '';
            }
        }
    }

    function js_mostraiptucalh(chave,erro){
        document.form1.j17_descr.value = chave;
        if(erro==true){
            document.form1.j89_codhis.focus();
            document.form1.j89_codhis.value = '';
        }
    }

    function js_mostraiptucalh1(chave1,chave2){
//	alert(chave1+' - '+chave2);
        document.form1.j89_codhis.value = chave1;
        document.form1.j17_descr.value = chave2;
        db_iframe_iptucalh.hide();
    }

    /*----------------------------------------------------------------------------------*/

    function js_pesquisaj89_codhispai(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalhpai','func_iptucalh.php?funcao_js=parent.js_mostraiptucalhpai1|j17_codhis|j17_descr','Pesquisa',true);
        }else{
            if(document.form1.j89_codhispai.value != ''){
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalhpai','func_iptucalh.php?pesquisa_chave='+document.form1.j89_codhispai.value+'&funcao_js=parent.js_mostraiptucalhpai','Pesquisa',false);
            }else{
                document.form1.j17_descr2.value = '';
            }
        }
    }

    function js_mostraiptucalhpai(chave,erro){
        document.form1.j17_descr2.value = chave;
        if(erro==true){
            document.form1.j89_codhispai.focus();
            document.form1.j89_codhispai.value = '';
        }
    }

    function js_mostraiptucalhpai1(chave1,chave2){
        document.form1.j89_codhispai.value = chave1;
        document.form1.j17_descr2.value = chave2;
        db_iframe_iptucalhpai.hide();
    }

    function js_pesquisa(){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_iptucalhconf','func_iptucalhconf.php?funcao_js=parent.js_preenchepesquisa|j89_sequencial','Pesquisa',true);
    }

    function js_preenchepesquisa(chave){
        db_iframe_iptucalhconf.hide();
      <?
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
      ?>
    }
</script>