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

//MODULO: caixa
$clcorrentemov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k11_local");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk12_idmov?>">
       <?=@$Lk12_idmov?>
    </td>
    <td> 
<?
db_input('k12_idmov',6,$Ik12_idmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_idautent?>">
       <?
       db_ancora(@$Lk12_idautent,"js_pesquisak12_idautent(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k12_idautent',6,$Ik12_idautent,true,'text',$db_opcao," onchange='js_pesquisak12_idautent(false);'")
?>
       <?
db_input('k11_local',30,$Ik11_local,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_dtmov?>">
       <?=@$Lk12_dtmov?>
    </td>
    <td> 
<?
$k12_dtmov_dia = date('d');
$k12_dtmov_mes = date('m');
$k12_dtmov_ano = date('Y');

$k12_horamov   = date('H:i');
db_inputdata('k12_dtmov',@$k12_dtmov_dia,@$k12_dtmov_mes,@$k12_dtmov_ano,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_horamov?>">
       <?=@$Lk12_horamov?>
    </td>
    <td> 
<?
db_input('k12_horamov',5,$Ik12_horamov,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_valormov?>">
       <?=@$Lk12_valormov?>
    </td>
    <td> 
<?
db_input('k12_valormov',15,$Ik12_valormov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_tipomov?>">
       <?=@$Lk12_tipomov?>
    </td>
    <td> 
      <?
        $x = array("0"=>"Débito","1"=>"Crédito");
	db_select('k12_tipomov',$x,true,$db_opcao);
				
//db_input('k12_tipomov',2,$Ik12_tipomov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk12_obsmov?>">
       <?=@$Lk12_obsmov?>
    </td>
    <td> 
<?
db_textarea('k12_obsmov',6,70,$Ik12_obsmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak12_idautent(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_cfautent.php?funcao_js=parent.js_mostracfautent1|0|5';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_cfautent.php?pesquisa_chave='+document.form1.k12_idautent.value+'&funcao_js=parent.js_mostracfautent';
  }
}
function js_mostracfautent(chave,erro){
  document.form1.k11_local.value = chave; 
  if(erro==true){ 
    document.form1.k12_idautent.focus(); 
    document.form1.k12_idautent.value = ''; 
  }
}
function js_mostracfautent1(chave1,chave2){
  document.form1.k12_idautent.value = chave1;
  document.form1.k11_local.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_correntemov.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>