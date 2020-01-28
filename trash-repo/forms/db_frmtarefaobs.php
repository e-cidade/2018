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


include("dbforms/db_classesgenericas.php");
$cltarefa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at40_obs");
$clrotulo->label("at42_tarefa");

?>
<form name="form1" method="post">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat42_tarefa?>">
       <?=@$Lat42_tarefa?>
    </td>
    <td> 
    <?
      db_input('at42_tarefa',10,$Iat42_tarefa,true,'text',3,"");
    ?>
    </td>
  </tr>
    <td nowrap title="<?=@$Tat40_obs?>">
       <?=@$Lat40_obs?>
    </td>
    <td> 
<?
db_textarea('at40_obs',30,100,"",true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="alterar" type="submit" id="db_opcao" value="Alterar">
    </td>
  </tr>
  </table>
  </center>
</form>