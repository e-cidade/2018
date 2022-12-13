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

//MODULO: issqn
$clissarqsimples->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q17_nomearq");
$clrotulo->label("k15_codbco");
$clrotulo->label("k15_codage");
?>
  <form name='form1' id='form1' enctype="multipart/form-data" method='post'> 
  <center>
  <table>
  <tr><td>
     <fieldset><legend><b>Processa Arquivo de retorno - Simples Nacional</b></legend>
         <table>
          <tr>
           <td nowrap title="<?=@$Tq17_sequencial?>"><b>
          <?
           db_ancora("Código","js_pesquisaq17_sequencial(true);",$db_opcao);
           ?>
           </b>
         </td>
         <td> 
         <?
            db_input('q17_sequencial',10,$Iq17_sequencial,true,'text',3);
            db_input('q17_nomearq',60,$Iq17_nomearq,true,'text',3,'');
         ?>
          </td>
          </tr>
          <tr>
           <td nowrap title="<?=@$Tk15_codbco?>">
           <B>
          <?
           db_ancora("$Lk15_codbco","js_pesquisacadban(true);",$db_opcao);
           ?>
           </b>
         </td>
         <td> 
         <?
            db_input('k15_codbco',10,$Ik15_codbco,true,'text',3);
            db_input('nomebanco',60,'',true,'text',3,'');
         ?>
          </td>
          </tr>
              <tr>
                <td><?=$Lk15_codage;?><b>/Conta</b></td>
                <td>
            <?
              db_input('k15_codage',10,$Ik15_codage,true,'text',3,'');
              db_input('k15_conta',20,'',true,'text',3,'');
              
              ?>
            </td>
             </tr>
          </tr>
<!--              <tr>
                <td><b>Autentica:</b></td>
                <td>
            <?$autentica = array( 't' => "Sim",'f'=>"Não");
             db_select('autenticar',$autentica,true,$db_opcao);
             ?>
             </td>
             </tr> -->
               </table>   
     </fieldset>
   </td></tr>  
</table>
 
  <div id='message'></div>
 </center>
<input name="processar" type="submit" onclick='return confirm("Confirma Processamento?");' id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq17_sequencial(mostra){
  if (mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?semproc=1&funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq','Arquivos de Retorno',true);
  }
}
function js_pesquisacadban(mostra){
  if (mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe_cadban','func_cadban.php?method=sql_query_tabplan&funcao_js=parent.js_mostracadban|k15_codbco|k15_codage|z01_nome|k15_conta','Consulta Bancos',true);
  }
}

function js_mostraissarqsimples1(chave1,chave2){
  document.form1.q17_sequencial.value = chave1;
  document.form1.q17_nomearq.value    = chave2;
  db_iframe_issarqsimples.hide();
  if (document.form1.k15_codbco.value == ''){
     js_pesquisacadban(true);
  }
}

function js_mostracadban(chave1,chave2,chave3, chave4){
  document.form1.k15_codbco.value = chave1;
  document.form1.k15_codage.value = chave2;
  document.form1.nomebanco.value  = chave3;
  document.form1.k15_conta.value  = chave4;
  db_iframe_cadban.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?semproc=1&funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimples.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if (!isset($post->processar)){
 echo " js_pesquisa();\n";
}
?>
</script>