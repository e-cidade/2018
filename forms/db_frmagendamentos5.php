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

//MODULO: saude
$clagendamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd02_c_nome");
$clrotulo->label("sd03_c_nome");
$clrotulo->label("sd05_c_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd23_i_medico?>">
       <?db_ancora(@$Lsd23_i_unidade,"js_pesquisasd23_i_unidade(true);",$db_opcao);?>
    </td>
    <td>
       <?db_input('sd23_i_unidade',10,@$Isd23_i_unidade,true,'text',$db_opcao," onchange='js_pesquisasd23_i_unidade(false);'")?>
       <?db_input('sd02_c_nome',50,@$Isd02_c_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_medico?>">
       <?
       db_ancora(@$Lsd23_i_medico,"js_pesquisasd23_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd23_i_medico',10,$Isd23_i_medico,true,'text',3," onchange='js_pesquisasd23_i_medico(false);'")
?>
       <?
db_input('z01_nome',50,@$Isd03_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd23_i_especialidade?>">
       Especialidade
       <?//db_ancora(@$Lsd23_i_especialidade,"js_pesquisasd23_i_especialidade(true);",3);?>
    </td>
    <td> 
<?
db_input('sd23_i_especialidade',10,$Isd23_i_especialidade,true,'text',3," onchange='js_pesquisasd23_i_especialidade(false);'")
?>
       <?
db_input('sd05_c_descr',50,$Isd05_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
   <td colspan="2"><input type="button" value="Processar" onclick="js_valida()"></td>
  </tr>
  </table>
   Clique em Médico para buscar
  </center>
</form>
<script>

function js_valida(){
 if(document.form1.sd23_i_unidade.value==""){
  alert("Escolha a Unidade");
  document.form1.sd23_i_unidade.focus();
  return false;
 }
 if(document.form1.sd23_i_medico.value==""){
  alert("Escolha o Médico");
  document.form1.sd23_i_medico.focus();
  return false;
 }
 if(document.form1.sd23_i_especialidade.value==""){
  if(confirm("Especialidade está em branco\n\nA especialidade será 0 - NÃO OBRIGATÓRIO\n\nDeseja continuar?")){
   parent.document.formaba.a2.disabled=false;
   top.corpo.iframe_a2.location.href='sau1_agendamentos002.php?unidade='+document.form1.sd23_i_unidade.value+'&medico='+document.form1.sd23_i_medico.value+'&especialidade=0';
   parent.mo_camada('a2');
  }
 }else{
  parent.document.formaba.a2.disabled=false;
  top.corpo.iframe_a2.location.href='sau1_agendamentos002.php?unidade='+document.form1.sd23_i_unidade.value+'&medico='+document.form1.sd23_i_medico.value+'&especialidade='+document.form1.sd23_i_especialidade.value;
  parent.mo_camada('a2');
 }
}

function js_pesquisasd23_i_medico(){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_medicos','func_unidademedicos.php?unidade='+document.form1.sd23_i_unidade.value+'&funcao_js=parent.js_mostramedicos1|sd03_i_id|z01_nome|sd27_i_especialidade|sd05_c_descr','Pesquisa',true);
}

function js_mostramedicos1(chave1,chave2,chave3,chave4){
  document.form1.sd23_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.sd23_i_especialidade.value = chave3;
  document.form1.sd05_c_descr.value = chave4;
  db_iframe_medicos.hide();
}

function js_pesquisasd23_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd23_i_unidade.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd23_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.sd02_c_nome.value = chave;
  if(erro==true){
    document.form1.sd23_i_unidade.focus();
    document.form1.sd23_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd23_i_unidade.value = chave1;
  document.form1.sd02_c_nome.value = chave2;
  db_iframe_unidades.hide();
}
</script>