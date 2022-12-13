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

//MODULO: ouvidoria >> procedimentos >> vincular atendimento a procresso

$clprotprocesso = new cl_protprocesso;
$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");

?>
<form name="form1" method="post" action="ouv4_ouvidoriapesqvincatendprocesso001.php">
<center>
<table border="0" style="margin-top: 20px;">
  <tr align="center">
    <td colspan="2">
     <fieldset>
     <legend><b>Vincular Atendimento a Processo</b></legend>
	  <table>
	    <tr>
		  <td nowrap title="<?=$Tp58_codproc?>" align="right">
		  <?
		    db_ancora('<b>Processo:</b>',"js_pesquisap58_codproc(true);","");
		  ?>
		  </td>
		  <td> 
		  <?
		    db_input("p58_codproc",10,$Ip58_codproc,true,'text',""," onchange='js_pesquisap58_codproc(false);'");
		    db_input("p58_requer",50,$Ip58_requer,true,'text',3,'');
		  ?>
		  </td>
	    </tr>
      </table>
     </fieldset>
	  <table>
	    <tr>
		  <td> 
            <input type="submit" name="pesquisar" id="pesquisar" value="Pesquisar" OnClick="return js_pesquisar();">
		  </td>
	    </tr>
      </table>     
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisap58_codproc(mostra){
  var mostra = mostra;

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_vincatendproc',
                        'func_protprocessoarquivouvidoria.php?funcao_js=parent.js_mostra1|p58_codproc|p58_requer&grupo=2',
                        'Pesquisa',true);
  }else{
     if(document.form1.p58_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_vincatendproc',
                            'func_protprocessoarquivouvidoria.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostra&grupo=2',
                            'Pesquisa',false);
     }else{
         document.form1.p58_codproc.value = '';
         document.form1.p58_requer.value = ''; 
     }
  }
}

function js_mostra(chave1,erro){
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = '';
    document.form1.p58_requer.value  = ''; 
  } else {
    document.form1.p58_requer.value  = chave1;  
  }
}

function js_mostra1(chave1,chave2){
  document.form1.p58_codproc.value = chave1;
  document.form1.p58_requer.value  = chave2;
  db_iframe_vincatendproc.hide();
}

function js_pesquisar(){
  var p58_codproc = document.form1.p58_codproc.value;
  var p58_requer  = document.form1.p58_requer.value;
  
  if (p58_codproc == "" || p58_requer == "") {
     alert("Processo não informado!");
     return false;
  } else {
     return true;
  }  
}
</script>