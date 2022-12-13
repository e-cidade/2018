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

$sql = "
        select matestoqueitem.*,
               matmater.*,
               matestoqueitemlanc.m95_verificado,
               m70_coddepto,
               descrdepto
        from matestoqueitem
              inner join matestoque on m71_codmatestoque=m70_codigo
              inner join matmater on m60_codmater=m70_codmatmater
              inner join matestoqueitemlanc on m95_codlanc=matestoqueitem.m71_codlanc
              inner join db_depart on coddepto=m70_coddepto
        order by m60_codmater
       ";
$res = pg_query($sql);
$rows = pg_num_rows($res);
?>
<center>
<div id="itensConf" style="position:absolute; max-height:550px; width:600px; top:25px; visibility:visible; overflow:auto; align:center;">
  <table border=0 width="100%">
    <tr>
      <th width=25px> &nbsp;     </th>
      <th width=40px> Inserir    </th>
      <th width=40px> Cancelar   </th>
      <th width=25px> Ítem       </th>
      <th> Descrição </th>
      <th width=25px> Qtd        </th>
      <th colspan=2> Departamento </th>
    </tr>
  <?
  for($x=0;$x <$rows;$x++){
    db_fieldsmemory($res,$x);
    ?>
    <tr style="background-color:#FFFFFF;">
       <td><input type="radio" name="op_<?=$m71_codlanc?>" value=0 checked></td>
       <td><input type="radio" name="op_<?=$m71_codlanc?>" value=1 ></td>
       <td><input type="radio" name="op_<?=$m71_codlanc?>" value=2 ></td>

       <td align=right><?=$m60_codmater?></td>
       <td><?=$m60_descr?></td>
       <td align=right><?=$m71_quant?></td>
       <td align=right><?=$m70_coddepto?></td>
       <td><?=$descrdepto?></td>
    </tr>
    <?
  }
  ?>
  </table>
</div>
<div id="buttonConf" style="position:absolute; width:600px; top:585px; visibility:visible;">
  <input type=button name=Confirmar value=Confirmar onclick="js_valores();">
</div>
<center>