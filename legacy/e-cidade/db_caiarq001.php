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

$clrotulo = new rotulocampo;
$clrotulo->label('k15_codbco');
$clrotulo->label('k15_codage');
$clrotulo->label('arqret');
?>
<script>
function js_verifica(){
  if(document.form1.k15_codbco.value==""){
    alert("Digite o código do Banco!");
	document.form1.k15_codbco.focus();
	return false;
  }
  if(document.form1.k15_codage.value==""){
    alert("Digite o código da Agência!");
	document.form1.k15_codage.focus();
	return false;
  }
  if(document.form1.arqret.value==""){
    alert("Selecione um arquivo de retorno!");
	document.form1.arqret.focus();
	return false;
  }
}

</script>
<center>
 <form name="form1" enctype="multipart/form-data" onsubmit=" return js_verifica()" method="post" action="">        
    <table width="58%" border="0" cellspacing="0">
      <tr> 
        <td width="56%">&nbsp;</td>
        <td width="44%">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap><?=$Lk15_codbco?></td>
        <td>
		<?
		db_input("k15_codbco",4,$Ik15_codbco,true,"text",4)
        ?>
		
		 <input name="tamanho" type="hidden" id="tamanho"></td>
      </tr>
      <tr>
        <td nowrap><?=$Lk15_codage?></td>
        <td>
		<?
		db_input("k15_codage",6,$Ik15_codage,true,"text",4)
        ?>
     </tr>
      <tr> 
        <td nowrap><?=$Larqret?> </td>
        <td> 
		<?
		db_input("arqret",50,$Iarqret,true,"file",4)
        ?>	
      </tr>
      <tr align="center"> 
        <td colspan="2"> 
          <input name="processar" type="submit" id="processar" value="Processar"></td>
      </tr>
    </table>
        </form>
     </center>