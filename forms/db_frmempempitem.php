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

$clempautitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e54_anousu");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc01_descrmater");

?>
<script>
function e(){
  return false;
}
</script>
<form name="form1" method="post" action="">
<center>
<table>
    <tr>
      <td><b>Total de itens:</b>
  <?
  $result02 = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"count(e55_sequen) as tot_item")); 
   db_fieldsmemory($result02,0);

  if($tot_item>0){
     $result = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"sum(e55_vltot) as tot_valor")); 
     db_fieldsmemory($result,0);
     if(empty($tot_valor) ||  $tot_valor==""){
       $tot_valor='0';
       $tot_item='0';
     }else{
       $tot_valor= number_format($tot_valor,2,".","");
     }
  }else{
    
    $tot_valor='0';
    $tot_item='0';
  }
  db_input('tot_item',8,0,true,'text',3);
  ?>
      <b>Total dos valores:</b>
  <?
  db_input('tot_valor',13,0,true,'text',3,"onchange=\"js_calcula('quant');\"")
  ?>
      
      </td>
    </tr>
  <tr>
    <td valign="top"  align='center'>  
     <?
//     $sql     = $clempautitem->sql_query($e55_autori,null,"e55_autori,e55_item,pc01_descrmater,e55_sequen,e55_descr,e55_quant,e55_vltot");
  $sql =        $clempautitem->sql_query_pcmaterele($e55_autori,null,"e55_autori,e55_item,pc07_codele,e55_sequen,e55_descr,e55_quant,e55_vltot,pc01_descrmater");
      
        //db_lovrot($sql,20,"","");
        db_lovrot($sql,20,"()","",'e');
     ?>
    </td>
  </tr>
  </table>
  </center>
</form>