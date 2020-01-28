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

//MODULO: issqn
$clrotulo = new rotulocampo;
$clrotulo->label("pc76_pcforne");
$clrotulo->label("z01_nome");
$result=$clpcforne->sql_record($clpcforne->sql_query($pc76_pcforne));
if ($clpcforne->numrows>0){
  db_fieldsmemory($result,0);
}
?>

<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc76_pcforne?>" align="right">
       <?=@$Lpc76_pcforne?>
    </td>
    <td> 
<?


db_input('pc76_pcforne', 8, $Ipc76_pcforne, true, 'text', 3)
?>
       <?

 db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="atualizar" type="button"  id="db_opcao" value="Atualizar" onclick="subgrupo.js_atualizar();" >
    </td>
  </tr>
  <tr>
    <td colspan="2">
       <iframe id="subgrupo"  frameborder="0" name="subgrupo"   leftmargin="0" topmargin="0" src="com1_pcfornesubiframe.php?pc76_pcforne=<?=@$pc76_pcforne?>" height="300" width="500">
       </iframe> 
    </td>  
  </tr>
  </table>
  </center>
</form>