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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clliclicitaforne->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
  $op=1;
  $db_botao = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
  $op=1;
  $db_botao = true;
}else{  
  $db_opcao = 1;

  
} 

?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
      <table border="0" cellspacing="1" cellpadding="1">
	<tr>
	  <td nowrap align="right" title="<?=@$Tl22_codliclicita?> ">
	     
	          <b>Licitação :</b>
	  </td>
	  <td nowrap title="<?=@$Tl22_codliclicita?>">
         
         <?
         if (!isset($l22_codliclicita)||trim($l22_codliclicita)==""){
            $l22_codliclicita=@$l20_codigo;
         }
         db_input('l22_codliclicita',10,$Il22_codliclicita,true,'text',3,"");
         db_input('l22_codigo',10,$Il22_codigo,true,'hidden',3,"");
	     ?>
	   </td>
	  </tr>
 <tr> 
    <td align="right" nowrap title="<?=$Tl22_numcgm?>"><?db_ancora(@$Ll22_numcgm,"js_pesquisal22_numcgm(true);",$db_opcao);?></td>
    <td align="right" nowrap>
      <? db_input("l22_numcgm",6,$Il22_numcgm,true,"text",$db_opcao,"onchange='js_pesquisal22_numcgm(false);'");
         db_input("z01_nome",40,"$Iz01_nome",true,"text",3);  
        ?></td>
  </tr>	  
  <tr>
    <td align="right" nowrap title="<?=@$Tl22_dtretira?>">
       <?=@$Ll22_dtretira?>
    </td>
    <td> 
<?
$l22_dtretira_dia=date('d',db_getsession("DB_datausu"));
$l22_dtretira_mes=date('m',db_getsession("DB_datausu"));
$l22_dtretira_ano=date('Y',db_getsession("DB_datausu"));
db_inputdata("l22_dtretira",@$l22_dtretira_dia,@$l22_dtretira_mes,@$l22_dtretira_ano,true,'text',$db_opcao);
?>
         
    </td>
  </tr>
  
	  <tr>
	  <td align="right" nowrap title="<?=@$Tl22_nomeretira?>">
	     <?=@$Ll22_nomeretira?> 
	  </td>
	  <td nowrap title="<?=@$Tl22_nomeretira?>">
         <?
         db_input('l22_nomeretira',50,$Il22_nomeretira,true,'text',$db_opcao,"");
	     ?>
	  </td>
	  
	  </tr>
	<tr>
	<td colspan=2 align='center'>
	      <input name=<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir")) ?> type="submit" id="db_opcao" value= <?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir")) ?> <?=($db_botao==false?"disabled":"") ?> >
	      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
        </td>
      </tr>
      </table>
      <table>
  <tr>
    <td valign="top"> 
    <?
     $chavepri= array("l22_codigo"=>@$l22_codigo,"l22_codliclicita"=>@$l22_codliclicita,"l22_numcgm"=>@$l22_numcgm,"l22_dtretira"=>@$l22_dtretira,"l22_nomeretira"=>@$l22_nomeretira,"z01_nome"=>@$z01_nome);
     $cliframe_alterar_excluir->chavepri=$chavepri;
     $cliframe_alterar_excluir->sql = $clliclicitaforne->sql_query(null,'*',null,"l22_codliclicita=".@$l20_codigo);
      //$cliframe_alterar_excluir->sql_disabled = 
      $cliframe_alterar_excluir->campos  ="l22_codliclicita,l22_numcgm,z01_nome,l22_dtretira,l22_nomeretira";
      $cliframe_alterar_excluir->legenda="FORNECEDORES";
      $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum Registro.";
      $cliframe_alterar_excluir->textocabec ="darkblue";
      $cliframe_alterar_excluir->textocorpo ="black";
      $cliframe_alterar_excluir->fundocabec ="#aacccc";
      $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
      $cliframe_alterar_excluir->iframe_width ="710";
      $cliframe_alterar_excluir->iframe_height ="130";
      $lib=1;
      if ($db_opcao==3||$db_opcao==33){
       	$lib=4;
      }
      $cliframe_alterar_excluir->opcoes = @$lib;
      $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);   
      db_input('db_opcao',10,'',true,'hidden',3);
    ?>
   </td>
 </tr>
 </table>
</form>
<script>
function js_pesquisal22_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.l22_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.l22_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.l22_numcgm.focus(); 
    document.form1.l22_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.l22_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_liclicita','func_liclicita.php?funcao_js=parent.js_preenchepesquisa|l20_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_liclicita.hide();
  <?
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
   		
  
  ?>
}
</script>