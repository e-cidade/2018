<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: cadastro
$clloteloc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j34_setor");
$clrotulo->label("j05_descr");
if(isset($id_setor)){
 echo $id_setor;  
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj06_setorloc?>">
       <?
       echo $Lj06_setorloc;
       $sqlset = " select setorloc.j05_codigo,setorloc.j05_codigoproprio||' - '||setorloc.j05_descr from setorloc order by j05_codigoproprio ";
       $resultset = db_query($sqlset);
       
       //db_ancora(@$Lj06_setorloc,"js_pesquisaj06_setorloc(true);",1);
       ?>
    </td>
    <td> 
<?
db_selectrecord('j06_setorloc',$resultset,true,1,'','','');
//db_input('j06_setorloc',6,$Ij06_setorloc,true,'text',$db_opcao," onblur='js_pesquisaj06_setorloc();'")
?>
       <?
//db_input('j06_descr',40,$Ij06_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj06_quadraloc?>">
       <?=@$Lj06_quadraloc?>
    </td>
    <td> 
<?
db_input('j06_quadraloc',5,$Ij06_quadraloc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj06_lote?>">
       <?=@$Lj06_lote?>
    </td>
    <td> 
<?
db_input('j06_lote',10,$Ij06_lote,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="enviar" type="button" id="db_opcao" value="Enviar" <?=($db_botao==false?"disabled":"")?> onclick=" return js_self(); ">
</form>
<script>


function js_self(){
  if(document.form1.j06_setorloc.value == ''){
   alert('Setor de Localização não informado!');
   return false;
  }else if(document.form1.j06_quadraloc.value == ''){
   alert('Quadra não informada!');
   return false;
  }else if(document.form1.j06_lote.value == ''){
   alert('Lote de Localização não informado!');
   return false;
  }
  
  parent.document.form1.j06_setorloc.value  = document.form1.j06_setorloc.value;
  parent.document.form1.j06_quadraloc.value = document.form1.j06_quadraloc.value;
  parent.document.form1.j06_lote.value      = document.form1.j06_lote.value;
  parent.js_loteloc(2); 
}
</script>