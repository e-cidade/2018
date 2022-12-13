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

//MODULO: pessoal
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>EMISSÃO CAGED</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="Ano / Mês de competência">
              <b>Ano / Mês:</b>
            </td>
            <td nowrap>
              <?
              if(!isset($mesusu)){
                $mesusu = db_mesfolha();
              }
              if(!isset($anousu)){
                $anousu = db_anofolha();
              }
	      $mesusu = db_formatar($mesusu,"s","0",2,"e",0);
              db_input('anousu',4,1,true,'text',1);
              ?>
              <b>/</b>
              <?
              db_input('mesusu',2,1,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Número da autorização e dígito verificador">
              <b>Autorização:</b>
            </td>
            <td> 
              <?
              $autorizacao = 0;
              db_input('autorizacao',6,1,true,'text',1);
              db_input('digautoriza',1,1,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Efetuar ou não alguma alteração de cadastro">
              <b>Alteração:</b>
            </td>
            <td> 
              <?
              $alteracao = 1;
              $arr_alteracao= array("1"=>"Nada a alterar","2"=>"Alterar dados cadastrais","3"=>"Encerramento de atividades");
              db_select('alteracao',$arr_alteracao,true,1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Primeira declaração">
              <b>Primeira declaração:</b>
            </td>
            <td> 
              <?
              $primeiradeclaracao = 2;
              $arr_primeiradeclaracao= array("1"=>"Primeira declaração","2"=>"Já informou");
              db_select('primeiradeclaracao',$arr_primeiradeclaracao,true,1,"");
	      ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><b>CONTATO</b></legend>
        <table>
          <tr>
            <td nowrap align="right" title="DDD, número do telefone do contato">
              <b>DDD / Telefone:</b>
            </td>
            <td> 
              <?
	      echo "<b></b>";
              db_input('ddd',4,1,true,'text',1);
              db_input('codarea',4,1,true,'text',1);
	      echo "<b>-</b>";
              db_input('telefone',4,1,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Número do ramal do contato ">
              <b>Ramal:</b>
            </td>
            <td> 
              <?
              db_input('ramal',4,1,true,'text',1);
	      ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<input name="gerar" type="submit" id="gerar" value="Gerar CAGED" onblur='js_tabulacaoforms("form1","anousu",true,1,"anousu",true);'>
</center>
</form>
<script>
function js_controla_anomes(opcao){
  anodig = new Number(document.form1.anousu.value);
  mesdig = new Number(document.form1.mesusu.value);
  anofol = new Number("<?=db_anofolha()?>");
  mesfol = new Number("<?=db_mesfolha()?>");
  erro = 0;
  if(anodig.toFixed(2) != anofol.toFixed(2)){
    alert("Usuário:\n\nAno diferente do ano corrente. Verifique.");
    erro ++;
  }else if(mesdig.toFixed(2) != mesfol.toFixed(2) && mesdig != 13){
    alert("Usuário:\n\nMês diferente do mês corrente e não é 13o. Verifique.");
    erro ++;
  }
  if(erro > 0){
    if(opcao == "a"){
      document.form1.anousu.value = "<?=db_anofolha()?>";
      document.form1.anousu.focus();
    }else{
      document.form1.mesusu.value = "<?=db_mesfolha()?>";
      document.form1.mesusu.focus();
    }
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_codmovsefip','func_codmovsefip.php?funcao_js=parent.js_preenchepesquisa|r66_anousu|r66_mesusu|r66_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_codmovsefip.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>