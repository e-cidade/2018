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

//MODULO: biblioteca
$clemprestimo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bi10_numcgm");

if(empty($bi18_retirada_dia)){
  $bi18_retirada_dia = date("d",db_getsession("DB_datausu"));
  $bi18_retirada_mes = date("m",db_getsession("DB_datausu"));
  $bi18_retirada_ano = date("Y",db_getsession("DB_datausu"));
}
$opcao = @$bi18_codigo==""?1:3;
?>
<form name="form1" method="post" action="">
<center><br>
<table width="80%" border="0"><tr><td>
 <fieldset width="50%"><legend><b>Dados do Leitor:</b></legend>
 <table border="0">
  <tr>
    <td nowrap title="<?=@$Tbi18_leitor?>">
       <?
       db_ancora(@$Lbi18_leitor,"js_pesquisabi18_leitor(true);",$opcao);
       ?>
    </td>
    <td>
<?
db_input('bi18_leitor',10,$Ibi18_leitor,true,'text',$opcao," onchange='js_pesquisabi18_leitor(false);'")
?>
<?
db_input('z01_nome',50,@$z01_nome,true,'text',3," ")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi18_retirada?>">
       <?=@$Lbi18_retirada?>
    </td>
    <td> 
<?
db_inputdata('bi18_retirada',@$bi18_retirada_dia,@$bi18_retirada_mes,@$bi18_retirada_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbi18_devolucao?>">
       <?=@$Lbi18_devolucao?>
    </td>
    <td> 
<?
db_inputdata('bi18_devolucao',@$bi18_devolucao_dia,@$bi18_devolucao_mes,@$bi18_devolucao_ano,true,'text',$opcao,"")
?>
    </td>
   </tr>
  </table>
  </fieldset>
 </td></tr></table>
 <br>
 <?if(!empty($bi18_codigo)){?>
 <table border="0">
  <tr>
    <td colspan="2">
      <fieldset width="100%"><legend><b>Acervo:</b></legend>
      <?include("forms/db_frmemprestimoacervo.php");?>
      </fieldset>
    </td>
  </tr>
 </table>
 <?}?>
 <input name="<?=@$bi18_codigo==''?'incluir':'incluir2'?>" type="submit" id="<?=@$bi18_codigo==''?'incluir':'incluir2'?>" value="<?=@$bi18_codigo==''?'Próximo':'Salvar'?>" <?=@$bi19_codigo!=""?"disabled":""?> >
 <input name="excluir" type="submit" id="excluir" value="Excluir" <?=@$bi19_codigo==""?"disabled":""?> >
 <input name="cancelar" type="button" id="cancelar" value="Cancelar" <?=@$bi19_codigo==""?"disabled":""?> onclick="location='bib1_emprestimo001.php?bi18_codigo=<?=$bi18_codigo?>'">
 <br><br>
  <?
  if(!empty($bi18_codigo)){
    $chavepri= array("bi19_codigo"=>@$bi19_codigo,"bi06_seq"=>@$bi06_seq,"bi06_codbarras"=>@$bi06_codbarras,"bi06_titulo"=>@$bi06_titulo);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    @$cliframe_alterar_excluir->sql     = $clemprestimoacervo->sql_query("","bi19_codigo,bi06_seq,bi06_codbarras,bi06_titulo","","bi19_emprestimo = $bi18_codigo");
    $cliframe_alterar_excluir->campos  ="bi19_codigo,bi06_seq,bi06_codbarras,bi06_titulo";
    $cliframe_alterar_excluir->legenda="EMPRÉSTIMOS";
    $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="100";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  }
  ?>
 </center>
 <br>
</form>
<script>
function js_pesquisabi18_leitor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_leitor','func_leitor.php?funcao_js=parent.js_mostraleitor1|bi10_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.bi18_leitor.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_leitor','func_leitor.php?pesquisa_chave='+document.form1.bi18_leitor.value+'&funcao_js=parent.js_mostraleitor','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraleitor(chave1,chave2,erro){
  document.form1.z01_nome.value = chave1;
  if(erro==true){
    document.form1.bi18_leitor.focus(); 
    document.form1.bi18_leitor.value = ''; 
  }
}
function js_mostraleitor1(chave1,chave2){
  document.form1.bi18_leitor.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_leitor.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_emprestimo','func_emprestimo.php?funcao_js=parent.js_preenchepesquisa|bi18_codigo','Pesquisa',true);
}
function js_pesquisa2(){
  js_OpenJanelaIframe('top.corpo','db_iframe_emprestimo','func_leitorcategoria.php?funcao_js=parent.js_preenchepesquisa|bi07_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_emprestimo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_devolucao(){
 document.form1.dias.value = document.form1.bi18_leitor.value;
}
</script>