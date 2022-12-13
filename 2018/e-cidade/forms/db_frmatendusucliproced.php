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
<form name="form1" method="post">
<?
db_input('at82_usucliitem',8,0,true,'hidden',3);
if(!isset($selecionar)){
?>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td> 
        <strong>Pesquisa:</strong>
	<br>
	<input name="procura" type="text" id="procura" onKeyUp="js_pesquisa(this.value.toLowerCase(),document.form1.modulos)" size="35"> 
      </td>
    </tr>
    <tr> 
      <td>
        <strong>M&oacute;dulo:</strong><br> 
        <?
        $result = $cldb_modulos->sql_record($cldb_modulos->sql_query_usermod(null,"m.id_item||'--'||m.descr_modulo||'||'||nome_modulo,m.nome_modulo as id_item,m.descr_modulo"," lower(m.nome_modulo)"));
        db_selectmultiple("modulos", $result, 18, 1,"", "", "", "", "", 'onDblClick="document.form1.selecionar.click();">');
        ?>
      </td>
    </tr>
    <tr> 
      <td>
        <input name="selecionar" type="submit" id="selecionar" value="Selecionar" onClick="if(document.form1.modulos.selectedIndex == -1) { alert('Voce precisa selecionar um modulo!'); return false; }">
        <input name="fechar"  type="button" id="dbfechar" value="Fechar" onclick="parent.db_iframe_menus.hide();">
      </td>
    </tr>
  </table>
<?
}else{
?>
  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td align="center" nowrap> <strong>Módulo: </strong> 
        <? 
        $aux = $modulos[0];
        echo substr(strstr($aux,"||"),2)."<br>";
        echo "<font style=\"font-size:10px\">(".substr($aux,strpos($aux,"--")+2,strpos($aux,"||")-3).")</font>";
        ?>
      </td>
    </tr>
    <tr> 
      <td align="center">
        <table border="1" cellspacing="0" cellpadding="5">
          <tr> 
            <td>
              <input type="submit" name="incluir" value="Enviar dados">
              <input name="fechar"  type="button" id="dbfechar" value="Fechar" onclick="parent.db_iframe_menus.hide();">
            </td>
          </tr>
	</table>
      </td>
    </tr>
    <tr> 
    <?
    $wid = 15;
    $conta = 0;
    function submenus($item,$id,$mod) {
      global $wid, $conta, $cldb_menu, $clatendusucliproced, $at82_usucliitem;
      
      $result_menus = $cldb_menu->sql_record($cldb_menu->sql_query_menus(null, " m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo ", " m.menusequencia ", " m.modulo = $mod and m.id_item = $item and i.itemativo = '1'"));
      $numrows_menus = $cldb_menu->numrows;
      if($numrows_menus > 0) {
        for($x=0; $x<$numrows_menus; $x++){
          db_fieldsmemory($result_menus, $x);
          global $id_item, $id_item_filho, $modulo, $descricao, $funcao, $help;

          $checked_campo = "";
          $result_checked = $clatendusucliproced->sql_record($clatendusucliproced->sql_query_file(null, " * ", "", " at82_usucliitem = ".$at82_usucliitem." and at82_id_item = ".$id_item." and at82_id_item_filho = ".$id_item_filho." and at82_modulo = ".$modulo));
	  if($clatendusucliproced->numrows > 0){
            $checked_campo = " checked ";
	  }

          $valor = $id_item."#".$id_item_filho."#".$modulo;
          echo "
                <img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\">
                <input type=\"checkbox\" id=\"ID".$valor."\" name=\"CHECK[]\" value=\"".$valor."\" ".$checked_campo.">
        	<label for=\"ID".$valor."\">
        	  ".$descricao."
        	</label>
        	<br>\n
               ";
          $wid += 15;
          $conta++;
          submenus($id_item_filho,$id,$mod); 
          $wid -= 15;
        }
      }
    }
    ?>
      <td>
        <table border="0" cellpadding="10" cellspacing="0">
          <tr>
          <?
          $result = $cldb_itensmenu->sql_record($cldb_itensmenu->sql_query_menus(null,"i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao ","m.menusequencia", " m.modulo = ".db_strpos($modulos[0],"--")." and m.id_item = ".db_strpos($modulos[0],"--")." and i.itemativo = 1"));
          for($i=0; $i<$cldb_itensmenu->numrows; $i++) {
            db_fieldsmemory($result, $i);
            $valor = $id_item."#".$id_item_filho."#".$modulo;

            $checked_campo = "";
            $result_checked = $clatendusucliproced->sql_record($clatendusucliproced->sql_query_file(null, " * ", "", " at82_usucliitem = ".$at82_usucliitem." and at82_id_item = ".$id_item." and at82_id_item_filho = ".$id_item_filho." and at82_modulo = ".$modulo));
	    if($clatendusucliproced->numrows > 0){
              $checked_campo = " checked ";
	    }

            echo "
                  <td id=\"col".$i."\" valign=\"top\" nowrap>
                    <input type=\"checkbox\" id=\"ID".$valor."\" name=\"CHECK[]\" value=\"".$valor."\" ".$checked_campo.">
                    <label for=\"ID".$valor."\">".$descricao."</label>
                    <br>
                 ";
            submenus($pai,"col".$i,db_strpos($modulos[0],"--"));
            echo "
                  </td>
                 ";
          }
          ?>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<?
}
?>
</form>