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
$clcgs->rotulo->label();
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if($db_opcao == 2){
    echo "<input type='button' value='Emitir Ficha Matricial' onclick='top.corpo.iframe_a3.";if($tp==1){ echo "emite_ficha($numero,1)'>";}else{ echo "emite_ficha1($numero,1)'>";}
    echo "<input type='button' value='Emitir Ficha Outra' onclick='top.corpo.iframe_a3.";if($tp==1){ echo "emite_ficha($numero,2)'>";}else{ echo "emite_ficha1($numero,2)'>";}
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td><b>Cgm:</b></td><td><?=$z01_numcgm." - ".$z01_nome?></td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd01_c_siasus?>">
       <?=@$Lsd01_c_siasus?>
    </td>
    <td> 
<?
db_input('sd01_c_siasus',18,$Isd01_c_siasus,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
 <?if($db_opcao == 2){?>
  <tr>
   <td><?=@$Lz01_cep?></td>
   <td>
    <?db_input('z01_cep',9,$Iz01_cep,true,'text',$db_opcao);?>
   </td>
  </tr>
  <tr>
   <td><?db_ancora(@$Lz01_ender,"js_pesquisarua(true);",$db_opcao);?></td>
   <td>
    <?db_input('z01_ender',40,$Iz01_ender,true,'text',3);?>
   </td>
  </tr>
  <tr><td><?=@$Lz01_numero?></td><td><?db_input('z01_numero',8,$Iz01_numero,true,'text',$db_opcao);?></td></tr>
  <tr><td><?=@$Lz01_compl?></td><td><?db_input('z01_compl',10,$Iz01_compl,true,'text',$db_opcao);?></td></tr>
  <tr>
   <td><?db_ancora(@$Lz01_bairro,"js_pesquisabairro(true);",$db_opcao);?></td>
   <td>
    <?db_input('z01_bairro',25,$Iz01_bairro,true,'text',3);?>
   </td>
  </tr>
  <tr><td><?=@$Lz01_telef?></td><td><?db_input('z01_telef',12,$Iz01_telef,true,'text',$db_opcao);?></td></tr>
  <tr><td><?=@$Lz01_telcel?></td><td><?db_input('z01_telcel',12,$Iz01_telcel,true,'text',$db_opcao);?></td></tr>
  <?}?>
  <tr>
    <td nowrap title="<?=@$Tsd01_c_parentesco?>">
       <?=@$Lsd01_c_parentesco?>
    </td>
    <td> 
<?
$x = array('TITULAR'=>'TITULAR','ESPOSO(A)'=>'ESPOSO(A)','COMPANHEIRO(A)'=>'COMPANHEIRO(A)','FILHO(A)'=>'FILHO(A)','PAI'=>'PAI','MÃE'=>'MÃE','SOGRO(A)'=>'SOGRO(A)','FILHO(A) ADOTIVO(A)'=>'FILHO(A) ADOTIVO(A)','OUTROS'=>'OUTROS');
db_select('sd01_c_parentesco',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd01_c_sangue?>">
       <?=@$Lsd01_c_sangue?>
    </td>
    <td> 
<?
$x = array('A+'=>'A+','A-'=>'A-','O+'=>'O+','O-'=>'O-','AB'=>'AB');
db_select('sd01_c_sangue',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd01_i_familia?>">
       <?=@$Lsd01_i_familia?>
    </td>
    <td> 
<?
db_input('sd01_i_familia',8,$Isd01_i_familia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd01_c_alfabetizado?>">
       <?=@$Lsd01_c_alfabetizado?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('sd01_c_alfabetizado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd01_c_escola?>">
       <?=@$Lsd01_c_escola?>
    </td>
    <td> 
<?
$x = array('t'=>'SIM','f'=>'NÃO');
db_select('sd01_c_escola',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisarua(){
 js_OpenJanelaIframe('top.corpo.iframe_a4','db_iframe_rua','func_ruas.php?funcao_js=parent.js_mostrarua|j14_codigo|j14_nome','Pesquisa',true);
}

function js_mostrarua(chave1,chave2){
//  js_cep(chave1);
  document.form1.z01_ender.value = chave2;
  db_iframe_rua.hide();
}

/*function js_cep(rua){
 alert(rua);
 js_OpenJanelaIframe('top.corpo.iframe_a4','db_iframe_cep','func_cep.php?db11_logradouro='+rua+'&funcao_js=parent.js_preenchecep|cep','Pesquisa',true);
} */

function js_preenchecep(chave){
 alert(chave);
 document.form1.z01_cep.value = chave;
}

function js_pesquisabairro(){
    js_OpenJanelaIframe('top.corpo.iframe_a4','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro|j13_descr','Pesquisa',true);
}

function js_mostrabairro(chave1){
  document.form1.z01_bairro.value = chave1;
  db_iframe_bairro.hide();
}
</script>