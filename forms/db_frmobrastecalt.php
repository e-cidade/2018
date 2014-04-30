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

//MODULO: projetos
$clobrastec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("ob01_codobra");
$clrotulo->label("ob01_nomeobra");
$clrotulo->label("ob01_tecnico");
$clrotulo->label("ob15_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tob01_codobra?>">
       <?=@$Lob01_codobra?>
    </td>
    <td> 
<?
db_input('ob15_numcgm',10,$Iob15_numcgm,true,'hidden',3,"","ob15_numcgm_old");
db_input('ob15_numcgm',10,$Iob15_numcgm,true,'hidden',3,"");
db_input('ob01_codobra',10,$Iob01_codobra,true,'text',3,"");
?>
<?
db_input('ob01_nomeobra',55,$Iob01_nomeobra,true,'text',3,"")
?>
    </td>
		</tr>
  <tr>
    <td nowrap title="<?=@$Tob01_tecnico?>">
       <?
       db_ancora(@$Lob01_tecnico,"js_pesquisaob01_tecnico(true);",$db_opcao);
       ?>
    </td>
    <td> 
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
<?
db_input('ob15_crea',10,$Iob15_crea,true,'text',3,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisaob01_tecnico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_obrastec','func_obrastec.php?funcao_js=parent.js_mostracgm1|z01_nome|ob15_crea|ob15_numcgm','Pesquisa',true);
  }
}
function js_mostracgm1(chave1,chave2,chave3){
  document.form1.z01_nome.value = chave1;
  document.form1.ob15_crea.value = chave2;
  document.form1.ob15_numcgm.value = chave3;
  db_iframe_obrastec.hide();
}
</script>