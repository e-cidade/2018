<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
?>
<fieldset> 
  <legend><b>Imóbiliarias relacionadas</b></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tj44_matric?>">
      <?=@$Lj44_matric?>
      </td>
      <td> 
        <?
          db_input('j44_matric',10,$Ij44_matric,true,'text',3);
          db_input("z01_nome",60,$Ij01_numcgm,true,"text",3,"","z01_nomematri");
        ?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=@$Tj44_numcgm?>">
        <?
          db_ancora($Lj44_numcgm,' js_cgm(true); ',1);
        ?>
      </td>
      <td> 
        <?
          db_input('j44_numcgm',10,$Ij44_numcgm,true,'text',1,"onchange='js_cgm(false)'");
          db_input('z01_nome',60,$Iz01_nome,true,'text',3,"");
        ?>
      </td>
    </tr>
  </table>
</fieldset>

<br />

<input name="atualizar" type="submit" id="atualizar" value="Atualizar" >
<input name="excluir"   type="submit" id="excluir"   value="Excluir" <?=($db_opcao==1?"disabled":"")?>>

<script>
function js_cgm(mostra) {

  if ( mostra == true ) { 
    js_OpenJanelaIframe('top.corpo.iframe_imobil','db_iframe','func_cadimobil.php?funcao_js=parent.js_mostra1|0|1','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_imobil','db_iframe','func_cadimobil.php?pesquisa_chave='+document.form1.j44_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false,0);
  }
}

function js_mostra1(chave1, chave2) {

  document.form1.j44_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra(chave,erro) {

  document.form1.z01_nome.value = chave; 

  if ( erro == true ) { 
  
    document.form1.j44_numcgm.focus();
    document.form1.j44_numcgm.value="";
  }
}
</script>