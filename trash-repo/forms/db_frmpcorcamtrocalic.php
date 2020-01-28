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
?>
<form name="form1" method="post" action="lic1_pcorcamtroca001.php">
	<center>
		<table border="0" align="center">
		  <tr>
		    <td>&nbsp;</td>
		  </tr>
      <tr> 
        <td>
	        <? if ( $lListaModelos ) { ?>
            <fieldset>
              <legend align="left">
                <b>Gera��o de Atas</b>
              </legend>
	            <table>
	              <tr id="modelotemplate">
	                <td>
	                  <b>Modelos:</b>
	                </td>
	                <td>
	                  <?
	                    db_selectrecord('documentotemplateata', $rsModelos, true, $db_opcao);
	                  ?>
	                </td>               
	              </tr>
	            </table>
            </fieldset>
	        <? } ?>
        </td>
      </tr>
		  <tr align="center">
		    <td nowrap align="center">    
		      <?
			      db_input('pc20_codorc',    8,"",true,'hidden',3);
			      db_input('pc21_orcamforne',8,"",true,'hidden',3);
			      db_input('l20_codigo',     8,"",true,'hidden',3);
		      ?>
		      <fieldset>
            <legend align="left">
              <b>Lista de Itens</b>
            </legend>
            <table>
              <tr>
                <td>
				          <iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0" 
				                  frameborder="0" src="lic1_trocpcorcamtroca.php" width="1100px" height="300px">
				          </iframe>
                </td>
              </tr>
            </table>
		      </fieldset>
		    </td>
		  </tr>
		</table>
		<br>
		<table border="0">
		  <tr>
		    <td nowrap>
		      <input name="confirmar" type="submit" value="Confirmar" onClick="js_confirmar();" 
		             <?=($db_opcao==3?"disabled":"")?>>
		    </td>
		    <td nowrap>
		      <input name="voltar" type="button" value="Voltar" 
		             onClick="top.corpo.document.location.href = 'lic1_orcamlancval001.php?pc20_codorc=<?=$pc20_codorc?>&lic=true&l20_codigo=<?=$l20_codigo?>';">
		    </td>
		  </tr>
		</table>
	</center>
	<?
	  db_input("itens", 500, "", true, "hidden", 3);
	?>
</form>
<script>
  function js_confirmar() {
     document.form1.itens.value = iframe_solicitem.document.form2.itens.value;   

     document.form1.submit();
  }
</script>