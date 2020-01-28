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

//MODULO: orcamento
$clorcparamrelopcre->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o69_codparamrel");
?>
<form name="form1" method="post" action="">
    <table border="0">
      <td>
        <fieldset>
        <legend>
          <b>Dados</b>
        </legend>
        <table>
          <tr style=''>
            <td nowrap title="<?=@$To98_sequencial?>">
              <?=@$Lo98_sequencial?>
            </td>
            <td>
              <?
              db_input('o98_sequencial',10,$Io98_sequencial,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_orcparamseq?>">
              <?=@$Lo98_orcparamseq?>
            </td>
            <td>
              <?      
               $result = $clorcparamseq->sql_record(
                         $clorcparamseq->sql_query($oGet->c83_codrel,null, "o69_codseq,o69_descr"));
               db_selectrecord("o98_orcparamseq",$result,true,$db_opcao);
       
       ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_anousu?>">
              <?=@$Lo98_anousu?>
            </td>
            <td>
              <?
              $o98_anousu = db_getsession('DB_anousu');
              db_input('o98_anousu',10,$Io98_anousu,true,'text',3,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_identificacao?>">
              <?=@$Lo98_identificacao?>
            </td>
            <td>
              <?
              db_input('o98_identificacao',50,$Io98_identificacao,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_credor?>">
              <?=@$Lo98_credor?>
            </td>
            <td>
              <?
               db_input('o98_credor',75,$Io98_credor,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_periodo?>">
              <?=@$Lo98_periodo?>
            </td>
            <td>
              <select name="o98_periodo">
                <option value="1Q">Primeiro Quadrimestre</option>
                <option value="2Q">Segundo  Quadrimestre</option>
                <option value="3Q">Terceiro Quadrimestre</option>
                <option value="1S">Primeiro Semestre</option>
                <option value="2S">Segundo  Semestre</option>
              </select>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To98_valor?>">
              <?=@$Lo98_valor?>
            </td>
            <td>
              <?
              db_input('o98_valor',15,$Io98_valor,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
        </fieldset>
        </td>
        </table>

        
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?>>
        <input name="novo" type="submit" id="Novo" value="Novo">
      
      <?
       $aChavesPri                         = array(
                                                   "o98_sequencial"=>'',
                                                  );
       $cliframe_alterar_excluir->chavepri = $aChavesPri;
       $sWhere                             = " o98_anousu     = ".db_getsession("DB_anousu");
       $sWhere                            .= " and o98_instit = ".db_getsession("DB_instit");                 
       $cliframe_alterar_excluir->sql      = $clorcparamrelopcre->sql_query(
                                                                            null,"*",
                                                                            "o98_sequencial",
                                                                            $sWhere
                                                                     );
       $cliframe_alterar_excluir->campos = "o69_descr, o98_identificacao, o98_credor, o98_periodo, o98_valor";
       $cliframe_alterar_excluir->legenda= "Variaveis";     
       $cliframe_alterar_excluir->iframe_height ="240";
       $cliframe_alterar_excluir->iframe_width  ="100%";
       $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?> 
      </form>
      <script>
        function js_pesquisao98_orcparamseq(mostra){
          if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codparamrel|o69_codparamrel','Pesquisa',true);
          }else{
             if(document.form1.o98_orcparamseq.value != ''){ 
                js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o98_orcparamseq.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
             }else{
               document.form1.o69_codparamrel.value = ''; 
             }
          }
        }
        function js_mostraorcparamseq(chave,erro){
          document.form1.o69_codparamrel.value = chave; 
          if(erro==true){ 
            document.form1.o98_orcparamseq.focus(); 
            document.form1.o98_orcparamseq.value = ''; 
          }
        }
        function js_mostraorcparamseq1(chave1,chave2){
          document.form1.o98_orcparamseq.value = chave1;
          document.form1.o69_codparamrel.value = chave2;
          db_iframe_orcparamseq.hide();
        }
        function js_pesquisao98_orcparamseq(mostra){
          if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codseq|o69_codparamrel','Pesquisa',true);
          }else{
             if(document.form1.o98_orcparamseq.value != ''){ 
                js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o98_orcparamseq.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
             }else{
               document.form1.o69_codparamrel.value = ''; 
             }
          }
        }
        function js_mostraorcparamseq(chave,erro){
          document.form1.o69_codparamrel.value = chave; 
          if(erro==true){ 
            document.form1.o98_orcparamseq.focus(); 
            document.form1.o98_orcparamseq.value = ''; 
          }
        }
        function js_mostraorcparamseq1(chave1,chave2){
          document.form1.o98_orcparamseq.value = chave1;
          document.form1.o69_codparamrel.value = chave2;
          db_iframe_orcparamseq.hide();
        }
        function js_pesquisao98_orcparamrel(mostra){
          if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codparamrel|o69_codparamrel','Pesquisa',true);
          }else{
             if(document.form1.o98_orcparamrel.value != ''){ 
                js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o98_orcparamrel.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
             }else{
               document.form1.o69_codparamrel.value = ''; 
             }
          }
        }
        function js_mostraorcparamseq(chave,erro){
          document.form1.o69_codparamrel.value = chave; 
          if(erro==true){ 
            document.form1.o98_orcparamrel.focus(); 
            document.form1.o98_orcparamrel.value = ''; 
          }
        }
        function js_mostraorcparamseq1(chave1,chave2){
          document.form1.o98_orcparamrel.value = chave1;
          document.form1.o69_codparamrel.value = chave2;
          db_iframe_orcparamseq.hide();
        }
        function js_pesquisao98_orcparamrel(mostra){
          if(mostra==true){
            js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?funcao_js=parent.js_mostraorcparamseq1|o69_codseq|o69_codparamrel','Pesquisa',true);
          }else{
             if(document.form1.o98_orcparamrel.value != ''){ 
                js_OpenJanelaIframe('top.corpo','db_iframe_orcparamseq','func_orcparamseq.php?pesquisa_chave='+document.form1.o98_orcparamrel.value+'&funcao_js=parent.js_mostraorcparamseq','Pesquisa',false);
             }else{
               document.form1.o69_codparamrel.value = ''; 
             }
          }
        }
        function js_mostraorcparamseq(chave,erro){
          document.form1.o69_codparamrel.value = chave; 
          if(erro==true){ 
            document.form1.o98_orcparamrel.focus(); 
            document.form1.o98_orcparamrel.value = ''; 
          }
        }
        function js_mostraorcparamseq1(chave1,chave2){
          document.form1.o98_orcparamrel.value = chave1;
          document.form1.o69_codparamrel.value = chave2;
          db_iframe_orcparamseq.hide();
        }
        function js_pesquisa(){
          js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrelopcre','func_orcparamrelopcre.php?funcao_js=parent.js_preenchepesquisa|o98_sequencial','Pesquisa',true);
        }
        function js_preenchepesquisa(chave){
          db_iframe_orcparamrelopcre.hide();
          <?
          if($db_opcao!=1){
            echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
          }
          ?>
        }
      </script>