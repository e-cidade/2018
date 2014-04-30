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

//MODULO: empenho
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");

?>
<script>
function js_relatorio(){
  jan = window.open('emp2_emiteautori002.php?e54_autori=<?=$e54_autori?>&instit=<?=db_getsession("DB_instit")?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te54_autori?>">
       <?=@$Le54_autori?>
    </td>
    <td> 
<?
db_input('e54_autori',6,$Ie54_autori,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_numsol?>">
       <?=@$Le54_numsol?>
    </td>
    <td> 
<?
db_input('e54_numsol',8,$Ie54_numsol,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_praent?>">
       <?=@$Le54_praent?>
    </td>
    <td> 
<?
db_input('e54_praent',30,$Ie54_praent,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_entpar?>">
       <?=@$Le54_entpar?>
    </td>
    <td> 
<?
db_input('e54_entpar',30,$Ie54_entpar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_conpag?>">
       <?=@$Le54_conpag?>
    </td>
    <td> 
<?
db_input('e54_conpag',30,$Ie54_conpag,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codout?>">
       <?=@$Le54_codout?>
    </td>
    <td> 
<?
db_input('e54_codout',30,$Ie54_codout,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_contat?>">
       <?=@$Le54_contat?>
    </td>
    <td> 
<?
db_input('e54_contat',20,$Ie54_contat,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_telef?>">
       <?=@$Le54_telef?>
    </td>
    <td> 
<?
db_input('e54_telef',20,$Ie54_telef,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="confirmar" type="submit" id="db_opcao" value="Confirmar"  <?=($db_botao==false?"disabled":"")?>>
<input name="relatorio" type="button" id="db_opcao" value="Relatório de autorização" onclick="js_relatorio();"  <?=($db_botao==false?"disabled":"")?>>
</form>