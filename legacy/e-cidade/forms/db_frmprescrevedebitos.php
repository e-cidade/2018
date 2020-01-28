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

/*
Cristian
10/2005
*/
//MODULO: caixa
$clprescricao->rotulo->label();
$clarreprescr->rotulo->label();

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
  </tr>
  <tr id="numcgm">
    <td><?db_ancora("<strong>Numcgm</strong>","js_pesquisa_numcgm(true);",1);?></td>
    <td> 
        <? db_input('k31_numcgm',10,@$k31_numcgm,true,'text',$db_opcao,"onblur=js_pesquisa_numcgm(false);") ?>
        <? db_input('k31_nome',50,@$k31_nome,true,'text',3) ?>
    </td>    
  </tr>
  <tr id="matricula">
     <td><?db_ancora("<strong>Matrícula</strong>","js_mostramatricula(true);",1);?></td>
     <td> 
        <? db_input('k31_matric',10,@$k31_matric,true,'text',$db_opcao,"onblur=js_mostramatricula(false);") ?>
     </td>    
   </tr>
   <tr id="inscr">
    <td><?db_ancora("<strong>Inscrição</strong>","js_mostrainscricao(true);",1);?></td>
    <td> 
        <? db_input('k31_inscr',10,@$k31_inscr,true,'text',$db_opcao,"onblur=js_mostrainscricao(false);") ?>
    </td>    
  </tr>
 <tr>
     <td><strong>Data</strong></td>
     <td> 
         <?
        	   $k31_data_dia = date('d');
        	   $k31_data_mes = date('m');
        	   $k31_data_ano = date('Y');
        	   db_inputdata('k31_data',@$k31_data_dia,@$k31_data_mes,@$k31_data_ano,true,'text',3,"")
      	 ?>
    </td>
 </tr>
<tr>
    <td><strong>Hora</strong></td>
    <td> 
       <?       
         $k31_hora = date( 'H:i' );
         db_input('k31_hora',5,@$k31_hora,true,'text',3)
       ?>
    </td>
 </tr>
 <tr>
     <td><strong>Usuário</strong></td>
     <td> 
         <? 
      	  $k31_usuario = db_getsession("DB_id_usuario");
      	  db_input('k31_usuario',10, $k31_usuario ,true,'text',3,"");
          db_input('db_usunome',50,$db_usunome,true,'text',3);
	       ?>
    </td>
 </tr>
</table>
</center>
<input name="processar" type="button" value="Pesquisar" onclick="return js_verifica()">
</form>
<script>
function js_verifica(){
  numcgm    = '';
  matric = '';
  inscr  = '';
  if( document.form1.k31_numcgm.value == "" && document.form1.k31_matric.value == "" && document.form1.k31_inscr.value == "" && document.form1.k31_obs.value == "" ){
    alert('Favor preencher pelo menos uma das informações de origem da pesquisa(CGM, Matricula ou Inscrição).');
    return false;
  }
  if( confirm('Confirma Prescrição dos Débitos' ) == false ){
    return false;
  }
  querystring = 'numcgm='+numcgm+'&matric='+matric+'&inscr='+inscr;
  js_OpenJanelaIframe('','db_iframe_proc','func_prescreverdivida.php?'+querystring,'Prescrição de Divida',true);
}  
function js_prescreve_gp( opcao ){
  if( opcao == 1 ){
    document.getElementById('numcgm').style.visibility="visible";
    document.getElementById('matricula').style.visibility="visible";;
    document.getElementById('inscr').style.visibility="visible";;
  }
  else {
    document.getElementById('numcgm').style.visibility='hidden';
    document.getElementById('matricula').style.visibility='hidden';
    document.getElementById('inscr').style.visibility='hidden';
  }
}

//Procura CGM
function js_pesquisa_numcgm(mostra){
 if(mostra == true){
   func_nome.jan.location.href = 'func_cgm.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome';
   func_nome.mostraMsg();
   func_nome.show();
   func_nome.focus();
//  js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?funcao_js=parent.js_mostra1|z01_numcgm|z01_nome','Pesquisa',true);
 }else{
    func_nome.jan.location.href = 'func_cgm.php?pesquisa_chave='+document.form1.k31_numcgm.value+'&funcao_js=parent.js_mostra';
  //js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?pesquisa_chave='+document.form1.k31_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
 }
}

function js_mostracgm(chave1,chave2){
 document.form1.k31_numcgm.value = chave1;
 document.form1.k31_nome.value = chave2;
 func_nome.hide();
 }


function js_mostra(erro, chave){
  document.form1.k31_nome.value = chave;
  if(erro==true){
   document.form1.k31_numcgm.focus();
   document.form1.k31_numcgm.value = '';
  }
 }

// Procura matricula
function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.k31_matric.value+'&funcao_js=parent.js_preenchematricula1';
  }
}
 function js_preenchematricula(chave,chave1){
   document.form1.k31_matric.value = chave;
   document.form1.k31_nome.value = chave1;
   func_nome.hide();
 }
 function js_preenchematricula1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_matric.focus();
     document.form1.k31_matric.value = '';
   }
 }


//Procura ISSQN
function js_mostrainscricao(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.k31_inscr.value+'&funcao_js=parent.js_preencheinscricao1';
  }
}
function js_preencheinscricao(chave,chave1){
   document.form1.k31_inscr.value = chave;
   document.form1.k31_nome.value = chave1;
   func_nome.hide();
 }
function js_preencheinscricao1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_inscr.focus();
     document.form1.k31_inscr.value = '';
   }
}
 
</script>