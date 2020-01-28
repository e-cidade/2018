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
$clrotulo = new rotulocampo;
$clrotulo->label("e54_anulad");
$clrotulo->label("e54_autori");

$ac="emp1_empautoriza006.php";
?>
<script>
  function js_reativar(){
    obj=document.form1;
    obj.e54_anulad_dia.value='';
    obj.e54_anulad_mes.value='';
    obj.e54_anulad_ano.value='';
    return true;
    
  }  
  function js_verificar(){
    obj=document.form1;
    d=new Number(obj.e54_anulad_dia.value);
    m=new Number(obj.e54_anulad_mes.value);
    a=new Number(obj.e54_anulad_ano.value);
    if(isNaN(d) || d=="" || isNaN(m) || m=="" ||  isNaN(a) || a==""){
      alert("Verifique a data de anulação!");
      obj.e54_anulad_dia.focus();
      return false;
    }
      return true;
  }
</script>
<form name="form1" method="post" action="<?=$ac?>">
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
    <td nowrap title="<?=@$Te54_anulad?>">
       <?=@$Le54_anulad?>
    </td>
    <td> 
<?
db_inputdata('e54_anulad',@$e54_anulad_dia,@$e54_anulad_mes,@$e54_anulad_ano,true,'text',$db_opcao);
?>
    <td>
  <tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"anular":"reativar")?>" type="submit" <?=($db_opcao==1?"onclick=\"return js_verificar();\"":"onclick=\"return js_reativar();\"")?> id="db_opcao" value="<?=($db_opcao==1?"Anular autorização":"Reativar autorização")?>" >
</form>