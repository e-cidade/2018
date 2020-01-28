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
<p>&nbsp;</p>
  <form name="form1" method="post" action="con1_depusur001.php">
    
  <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td colspan="2" width="8%" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center"><div align="center"><strong>Inclusao de usuarios 
          por departamento</strong></div></td>
    </tr>
    <tr> 
      <td width="50%" nowrap  style="font-size:13px" align="center"><div align="center"><strong>Usuarios :</strong></div></td>
      <td width="50%" nowrap style="font-size:13px" align="center"><div align="center"> <strong>Departamentos :</strong> </div></td>
    </tr>
    <tr> 
      <td align="center" nowrap> <select name="usuarios" onClick="document.form1.action='con1_depusur001.php?selecionar=1';document.form1.submit()" id="usuarios" size="10">
          <?  // carrega lista de usuarios
            $result = pg_exec("select id_usuario, nome, login from db_usuarios order by nome");
            $numrows = pg_numrows($result);
			for ($i=0;$i<$numrows;$i++) {
			  echo "<option value=\"".pg_result($result,$i,"id_usuario")."\" ".(pg_result($result,$i,"id_usuario")==@$HTTP_POST_VARS["usuarios"]?"selected":"").">".pg_result($result,$i,"nome") . " - " . pg_result($result,$i,"login")." </option>\n";
			}
          ?>
        </select> </td>
      <td height="41" nowrap> <div align="center"> 
          <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td width="54%" rowspan="8"> <div align="right"> 
                  <select name="depto[]" id="select" size="10" multiple>
                    <?  // carrega lista de departamentos
			  if(isset($HTTP_POST_VARS["usuarios"])) {
			    $depusu = pg_exec("select coddepto from db_depusu where id_usuario = ".$HTTP_POST_VARS["usuarios"]);
				$depusu_coddepto[0] = "";
			    for($i=0;$i<pg_numrows($depusu);$i++)				
				  $depusu_coddepto[$i] = pg_result($depusu,$i,0);
			  }
            $depart = pg_exec("select * from db_depart order by descrdepto");
            $numrows = pg_numrows($depart);
			for ($i=0;$i<$numrows;$i++) {
			  echo "<option value=\"".pg_result($depart,$i,"coddepto")."\" ".(in_array(pg_result($depart,$i,"coddepto"),$depusu_coddepto)?"selected":"" ).">".pg_result($depart,$i,"coddepto") . " - " . pg_result($depart,$i,"descrdepto")." </option>\n";
			}
          ?>
                  </select>
                </div></td>
              <td width="46%"><input name="desmarcar" type="button" id="desmarcar" onClick="js_desmarcar()" value="Desmarcar todos"></td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td><input name="marcar" type="button" id="marcar" onClick="js_marcar()" value="    Marcar todos   "></td>
            </tr>
          </table>
        </div>
        <div align="center"> </div>
        <div align="left"> </div>
        <div align="left"> </div>
        <div align="left"> </div>
        <div align="left"> </div>
        <div align="left"> </div></td>
    </tr>
    <tr> 
      <td colspan="2" nowrap><div align="center"> 
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="18%"><div align="center"></div></td>
              <td width="26%"><div align="center"> 
                  <input name="incluir" type="submit" id="incluir" value="Incluir">
                </div></td>
              <td width="13%"><div align="center"></div></td>
              <td width="16%"><div align="center"> 
                  <input name="cancelar" type="submit" id="cancelar" value="Cancelar">
                </div></td>
              <td width="27%"><div align="center"></div></td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>
  </form>
</center>
<p>
  <script>
function js_desmarcar() {
  var F = document.form1.elements['depto[]'];
  if(F.selectedIndex != -1) {
    for(i = 0;i < F.length;i++) {
      F.options[i] = new Option(F.options[i].text,F.options[i].value);
    }
	js_trocacordeselect();
  }
}
function js_marcar() {
  var F = document.form1.elements['depto[]'];
  for(i = 0;i < F.length;i++) {
    F.options[i].selected = true;
  }
  js_trocacordeselect();
}

</script>
</p>