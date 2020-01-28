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

/* variaveis de configuração do formulario */
$borda = 0;
/*  */
?>
<form name="form1" enctype="multipart/form-data" method="post" >
  <table width='50%' border=<?=$borda?>>
    <tr>
      <td colspan=2 bgcolor='#000099'>
         <center><b><font color='#FFFFFF'> Carga de Arquivo CNAB240 </font></b></center>
      </td>
    </tr>
    <tr>
      <td align='left' width='50%'>
        <!-- dados do extrato bancario -->
        <fieldset>
        <Legend align="left"><b> Selecione o arquivo : </b></Legend>
          <table border=<?=$borda?> width='100%'>

            <tr>
              <td nowrap title="Caminho do arquivo do extrato bancário">
                 <b> Extrato bancário : </b>
              </td>
              <td nowrap> 
                <?
                  db_input('arquivo',30,'',true,'file',$db_opcao,"onChange='js_enablebotao(this.value);'",'','','');
                ?>
                <input name="carregar" type="submit" id="carregar" value="Carregar Extrato" disabled onclick="js_carregaArq($('arquivo').value);" >
              </td>
            </tr>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</form>
<script>
  function js_carregaArq(arquivo){
    if(arquivo == ''){
      alert('Escolha um arquivo para carregar no sistema !');
      return false;
    }
   // var url       = 'cai4_geraextrato.php';
    var parametro = 'arqname='+arquivo; 
    js_OpenJanelaIframe('top.corpo','db_iframe_carga','cai4_geraextrato.php?'+parametro,'Processando ... ',true);
  }

  function js_ajaxRetorno(resposta){
    alert(new String(resposta.responseText));
  }

  function js_enablebotao(valor){
    if(valor == ''){
      $('carregar').disabled = true;	
    }else{
      $('carregar').disabled = false;
    }
  }

  function js_carregar(){
    return true;
  }

</script>