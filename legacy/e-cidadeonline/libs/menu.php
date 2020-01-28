<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

function db_menu_dbpref($usuario, $modulo, $anousu, $instit,$cgm, $nomeusuario) {

	//#00#//db_menu
	//#10#//Esta funcao cria o menu nos programas
	//#15#//db_menu($usuario,$modulo,$anousu,$instit);
	//#20#//Usuario  : Id do usuário do arquivo |db_usuarios|
	//#20#//Modulo   : Código do Módulo
	//#20#//Anousu   : Exercício de Acesso
	//#20#//Instit   : Número da instituição

	global $HTTP_SERVER_VARS, $HTTP_SESSION_VARS;
	global $conn, $DB_SELLER;

  echo '<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>';
  $sub = 0;

  // entra aqui
	$usuario = (!isset($usuario)||trim($usuario)=='')?'NULL':$usuario;
	  $sql = "SELECT m.id_item,m.id_item_filho,m.menusequencia,i.descricao,i.help,i.funcao,i.desctec
	      FROM db_menu m
		  INNER JOIN db_permissao p ON p.id_item = m.id_item_filho
		  INNER JOIN db_itensmenu i ON i.id_item = m.id_item_filho
						   AND p.permissaoativa = '1'
						   AND p.anousu = $anousu
						   AND p.id_instit = $instit
						   AND p.id_modulo = $modulo
	     WHERE p.id_usuario = $usuario
	       AND m.modulo = $modulo AND i.itemativo = 1 ";

	  // entra aqui
	  if (!isset ($DB_SELLER))
		  $sql .= " and i.libcliente = true ";

	  $sql .= "

	     UNION

	     SELECT m.id_item,m.id_item_filho,m.menusequencia,i.descricao,i.help,i.funcao,i.desctec
	     FROM db_menu m
		  INNER JOIN db_permherda h on h.id_usuario = $usuario
		  INNER JOIN db_usuarios u on u.id_usuario = h.id_perfil and u.usuarioativo = '1'
		  INNER JOIN db_permissao p ON p.id_item = m.id_item_filho
		  INNER JOIN db_itensmenu i ON i.id_item = m.id_item_filho
						   AND p.permissaoativa = '1'
						   AND p.anousu = $anousu
						   AND p.id_instit = $instit
						   AND p.id_modulo = $modulo
	     WHERE p.id_usuario = h.id_perfil
	       AND m.modulo = $modulo AND i.itemativo = 1 ";
	  if (!isset ($DB_SELLER))
		  $sql .= " and i.libcliente = true ";
	  $sql .= "
	     ORDER BY MENUSEQUENCIA
	    ";

	$menu = db_query($sql);
	$NumMenu = pg_numrows($menu);
	$help_descricao = "";
	$rotinadb = "";

	// entra aqui
	if ($NumMenu != 0) { 
		echo "<div class=\"menuBar\" style=\"width:100%\" >\n";
		$gera_helps = "";
		for ($i = 0; $i < $NumMenu; $i ++) {

			//entra aqui
			if (pg_result($menu, $i, 0) == $modulo) {  // esses são os menus principais
				$funcao=trim(pg_result($menu, $i, 'funcao'));
				if ($funcao==""){
					echo "<a class=\"menuButton\" onmouseover=\"return buttonClick(event, 'Ijoao".pg_result($menu, $i, 1)."');\" onmouseover=\"buttonMouseover(event, 'Ijoao".pg_result($menu, $i, "id_item_filho")."');\">".pg_result($menu, $i, "descricao")."</a>\n";
				}else{
					echo "<a class=\"menuButton\" href=\"$funcao?".base64_encode("id_usuario=".@$cgm."&nomeusuario=".@$nomeusuario)."\" target=\"CentroPref\" onmouseover=\"return buttonClick(event, 'Ijoao".pg_result($menu, $i, 1)."');\" onmouseover=\"buttonMouseover(event, 'Ijoao".pg_result($menu, $i, "id_item_filho")."');\">".pg_result($menu, $i, "descricao") ."</a>\n";
				}
			}
			// não entra aqui
			if (strtolower(basename($HTTP_SERVER_VARS["PHP_SELF"])) == strtolower(pg_result($menu, $i, 5))) {
				$rotinadb = pg_result($menu, $i, 4);
			}
			// funções que o menu vai chamar
			$db_funcao = trim(pg_result($menu, $i, 'funcao'));

			if ($gera_helps == "" && $db_funcao == basename($HTTP_SERVER_VARS["PHP_SELF"])) {// não entra aki db_funcao é diferente de basename
				$gera_helps = pg_result($menu, $i, 'id_item_filho');
				$help_descricao = pg_result($menu, $i, 'desctec');
			}
		}

		// aqui coloca o menu modulos e help
		echo "</div>\n";

		for ($i = 0; $i < $NumMenu; $i ++) {
			for ($j = 0; $j < $NumMenu; $j ++) {
				if (pg_result($menu, $j, "id_item") == pg_result($menu, $i, "id_item_filho")) {
					// entra aqui

					$x=pg_result($menu, $j, "id_item");
					$y=pg_result($menu, $i, "id_item_filho");
					$id1="Ijoao".pg_result($menu, $i, "id_item_filho");

					echo "<div id=\"Ijoao".pg_result($menu, $i, "id_item_filho")."\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";
					for ($a = 0; $a < $NumMenu; $a ++) {
						if (pg_result($menu, $j, "id_item") == pg_result($menu, $a, "id_item")) {
							//não entra..
							$verifica = 1;
							for ($b = 0; $b < $NumMenu; $b ++) {
								if (pg_result($menu, $a, "id_item_filho") == pg_result($menu, $b, "id_item")) {
									// .....aqui é os nivel2 com filho
									echo "<a class=\"menuItem\" href=\"\" target=\"CentroPref\" onclick=\"return false;\"  onmouseover=\"menuItemMouseover(event, 'Ijoao".pg_result($menu, $a, "id_item_filho")."');\">\n";
									echo "<span class=\"menuItemText\">".pg_result($menu, $a, "descricao")."</span>\n";
									$sub = 1;
									$id2="Ijoao".pg_result($menu, $i, "id_item_filho");
									echo "<span class=\"menuItemArrow\"> >>> </span></a>\n";
									$verifica = 0;
									break;
								}
							}
							if ($verifica == 1) {// menu nivel 3 com filho
							global $funcao2;
							$funcao2 = trim(pg_result($menu, $a, 'funcao'));
								if($sub==1){
									echo "<a class=\"menuItem\" target=\"CentroPref\" id=\"DBmenu_".pg_result($menu, $a, "id_item_filho")."\" href=\"$funcao2?".base64_encode("id_usuario=".@$cgm."&nomeusuario=".@$nomeusuario)."\"  onclick=\"js_menu2(".$id1.",".$id2.");\">".ucfirst(pg_result($menu, $a, "descricao"))."</a>\n";
								}else{
									echo "<a class=\"menuItem\" target=\"CentroPref\" id=\"DBmenu_".pg_result($menu, $a, "id_item_filho")."\" href=\"$funcao2?".base64_encode("id_usuario=".@$cgm."&nomeusuario=".@$nomeusuario)."\"  onclick=\"js_menu(".$id1.");\">".ucfirst(pg_result($menu, $a, "descricao"))."</a>\n";
								}
							}
						}
					}
					echo "</div>\n";
					break;
				}
			}
		}

			$sqlmodulo = "select id_item, nome_modulo, max(anousu) as anousu from (
		  		select distinct i.id_modulo as id_item,m.nome_modulo,
						 case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
					  from
					      (
					       select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
					       from db_permissao p
						    inner join db_itensmenu i on p.id_item = i.id_item
					       where i.itemativo = 1
						    and p.id_usuario = $usuario
						    and p.id_instit = $instit
					      ) as i
					      inner join db_modulos m  on m.id_item = i.id_modulo
					      inner join db_itensmenu it on it.id_item = i.id_modulo
					      left outer join db_usumod u  on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario
					  where i.id_usuario = $usuario
						and i.id_instit = $instit
					  union
					  select distinct i.id_modulo as id_item,m.nome_modulo,
						 case when u.anousu is null then to_char(CURRENT_DATE,'YYYY')::int4 else u.anousu end
					  from
					      (
					       select distinct i.itemativo,p.id_modulo,p.id_usuario,p.id_instit
					       from db_permissao p
						    inner join db_permherda h on h.id_usuario = $usuario
						    inner join db_itensmenu i on p.id_item = i.id_item
					       where i.itemativo = 1
						    and p.id_usuario = h.id_perfil
						    and p.id_instit = $instit
					      ) as i
					      inner join db_modulos m  on m.id_item = i.id_modulo
					      inner join db_itensmenu it on it.id_item = i.id_modulo
					      left outer join db_usumod u  on u.id_item = i.id_modulo and u.id_usuario = i.id_usuario
					  where i.id_instit = $instit
					  order by nome_modulo) as x
					  group by id_item, nome_modulo
					  order by nome_modulo";
		  $resultmodulo = db_query($sqlmodulo) or die($sqlmodulo);

		$NumModulos = pg_numrows($resultmodulo);
		echo "<div id=\"IListaModulos\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";

		$listaoutros = 0;

		for ($i = 0; $i < $NumModulos; $i ++) {
			if ($i < 15) {
				echo "<a class=\"menuItem\" href=\"modulos.php?".base64_encode("anousu=".pg_result($resultmodulo, $i, "anousu")."&modulo=".pg_result($resultmodulo, $i, "id_item")."&nomemod=".pg_result($resultmodulo, $i, "nome_modulo"))."\"  onclick='return js_db_menu_confirma();'>".pg_result($resultmodulo, $i, 1)."</a>\n";
			} else {
				if ($listaoutros == 0) {
					$listaoutros = 1;
					echo "<a class=\"menuItem\" href=\"\"  onclick=\"return false;\"  onmouseover=\"menuItemMouseover(event, 'IListaModulosOutros');\">\n";
					echo "<span class=\"menuItemText\">Outros ...</span>\n";
					echo "<span class=\"menuItemArrow\"><strong>>>></strong></span></a>\n";
					echo "</div>";
					echo "<div id=\"IListaModulosOutros\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";
				}
				echo "<a class=\"menuItem\" href=\"modulos.php?".base64_encode("anousu=".pg_result($resultmodulo, $i, "anousu")."&modulo=".pg_result($resultmodulo, $i, "id_item")."&nomemod=".pg_result($resultmodulo, $i, "nome_modulo"))."\"  onclick='return js_db_menu_confirma();'>".pg_result($resultmodulo, $i, 1)."</a>\n";
			}
		}
		echo "</div>\n";

		// Menu do Help
		echo "<div id=\"IMostraHelpMenu\" class=\"menu\" onmouseover=\"menuMouseover(event)\">\n";
		echo "<a class=\"menuItem\" onMouseover=\"js_cria_objeto_div('help','$help_descricao')\" onMouseout=\"js_remove_objeto_div('help')\" id=\"menuhelp\" href=\"\" onclick=\"buttonHelp('".basename($HTTP_SERVER_VARS["PHP_SELF"])."','".$gera_helps."','".$modulo."',true);return false\">Help</a>\n";
		echo "<a class=\"menuItem\" onMouseover=\"js_cria_objeto_div('versao','$help_descricao')\" onMouseout=\"js_remove_objeto_div('versao')\" id=\"menuMostraVersao\" href=\"\" onclick=\"buttonHelp('".basename($HTTP_SERVER_VARS["PHP_SELF"])."','".$gera_helps."','".$modulo."',false);return false\">Versões</a>\n";
		echo "</div>\n";

		if (isset ($HTTP_SESSION_VARS["DB_coddepto"])) {
			$result = @ db_query("select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto"));
			if ($result != false && pg_numrows($result)>0) {
				$descrdep = "[<strong>".db_getsession("DB_coddepto")."-".substr(pg_result($result, 0, 'descrdepto'), 0, 20)."</strong>]";
			} else {
				$descrdep = "";
			}
		} else {
			$descrdep = "";
		}

		$msg = ucfirst(db_getsession("DB_nome_modulo")).$descrdep."->".ucfirst($rotinadb);

		echo "<script>
							if( parent.bstatus ){

		          parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;$msg' ;
		          parent.bstatus.document.getElementById('dtatual').innerHTML = '".date("d/m/Y", db_getsession("DB_datausu"))."' ;
		          parent.bstatus.document.getElementById('dtanousu').innerHTML = '$anousu' ;
							}


	function js_menu(x) {

       x.style.visibility = 'hidden';
	}
	function js_menu2(x,y) {
	   y.style.visibility = 'hidden';
       x.style.visibility = 'hidden';
    }

// não usa
function js_menu1() {
  for(i = 0; i < document.getElementsByTagName('div').length; i++){
	  obj = document.element[i];
      if(obj.id.substr(0,5) == 'Ijoao'){
         obj.style.visibility = hidden;
      }
   }
}
// não usa
				  function js_db_menu_confirma () {

					if( js_db_menu_retorno != null ){

		              var retorno  = js_db_menu_retorno();

					}else{

		              var retorno = true;

		            }

					return retorno;

                    }

		          </script>";

	} else {
		//echo "Sem permissao de menu!";
	}

}
?>