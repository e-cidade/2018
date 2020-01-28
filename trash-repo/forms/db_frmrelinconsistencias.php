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
$clissarqsimples->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q17_nomearq");
$clrotulo->label("k15_codbco");
$clrotulo->label("k15_codage");
$clrotulo->label("q49_tipo");
?>
  <form name='form1' enctype="multipart/form-data" method='post'> 
  <center>
  <table>
  <tr><td>
    <fieldset><legend><b>Relat�rio de Inconsist�ncias - Simples Nacional</b></legend>
         <table>
          <tr>
           <td nowrap title="<?=@$Tq17_sequencial?>"><b>
          <?
           db_ancora("C�digo:","js_pesquisaq17_sequencial(true);",1);
           ?>
           </b>
         </td>
         <td> 
         <?
            db_input('q17_sequencial',10,$Iq17_sequencial,true,'text',1,"onchange=js_pesquisaq17_sequencial(false)");
            
            db_input('q17_nomearq',60,$Iq17_nomearq,true,'text',3,'');
         ?>
          </td>
          </tr>
          <tr>
           <td nowrap title="<?=@$Tq49_tipo?>"><b>
          <b>Tipo:</b> 
           </b>
         </td>
         <td> 
         <?
           $array = array(1 => "Inconsist�ncias",2 => "Avisos",3 => "Inconsist�ncias e Avisos");
            db_select("q49_tipo",$array,true,1);
            
         ?>
          </td>
          </tr>
          </table>   
     </fieldset>
   </td></tr>  
</table>
 </form>
  <div id='message'></div>
 </center>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="emite" type="button" disabled id="emite" value="Emitir" onclick="js_emiteinconsistencias();" >
</form>
<script>
function js_pesquisaq17_sequencial(mostra){
  if (mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?semproc=1&funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq','Arquivos de Retorno',true);
  }else{
     js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?semproc=1&pesquisa_chave='+document.form1.q17_sequencial.value+'&funcao_js=parent.js_mostraissarqsimples','Arquivos de Retorno',false);
  }
}

function js_mostraissarqsimples1(chave1,chave2){
  document.form1.q17_sequencial.value = chave1;
  document.form1.q17_nomearq.value    = chave2;
  document.form1.emite.disabled       = false;
  db_iframe_issarqsimples.hide();
}
function js_mostraissarqsimples(chave,erro){
  if (erro){

    document.form1.q17_sequencial.value = '';
    document.form1.q17_nomearq.value    = chave;

  }else{
    document.form1.q17_nomearq.value    = chave;
    document.form1.emite.disabled       = false;
  }
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_issarqsimples','func_issarqsimples.php?funcao_js=parent.js_mostraissarqsimples1|q17_sequencial|q17_nomearq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_issarqsimples.hide();
}
</script>