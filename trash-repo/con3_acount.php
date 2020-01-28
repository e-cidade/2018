<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" action="" method="post">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><table width="100%" border="0" cellspacing="0">
          <tr> 
            <td width="26%" align="right"><strong>M&oacute;dulo:</strong></td>
            <td width="15%"><select name="dbh_modulo" size="1" onChange="this.form.submit();">
                <?
			  echo '<option value="0">Nenhum...</option>';
			  $result = pg_exec("select codmod,nomemod from db_sysmodulo order by nomemod");
			  for($i=0;$i<pg_numrows($result);$i++){
			    echo '<option value="'.pg_result($result,$i,"codmod").'" '.(isset($HTTP_POST_VARS["dbh_modulo"]) && $HTTP_POST_VARS["dbh_modulo"] == pg_result($result,$i,"codmod")?"selected":"").'>'.pg_result($result,$i,"nomemod").'</option>';
			  }
			  ?>
              </select> </td>
            <td width="21%" align="right"><strong>Arquivo:</strong></td>
            <td width="38%"><select name="dbh_tabela" size="1" onChange="this.form.submit();">
                <?
			  echo '<option value="0">Nenhum...</option>';
			  $sql = "select m.codarq,nomearq 
			          from db_sysarquivo a
						   inner join db_sysarqmod m on a.codarq = m.codarq ";
  		      $sql .= " where m.codmod = ".$HTTP_POST_VARS["dbh_modulo"];						  
			  $sql .= " order by nomearq";
			  $result = pg_exec($sql);
			  for($i=0;$i<pg_numrows($result);$i++){
			    echo '<option value="'.pg_result($result,$i,"codarq").'" '.(isset($HTTP_POST_VARS["dbh_tabela"]) && $HTTP_POST_VARS["dbh_tabela"] == pg_result($result,$i,"codarq")?"selected":"").'>'.pg_result($result,$i,"nomearq").'</option>';
			  }
			  ?>
              </select></td>
          </tr>
          <tr> 
            <td align="right"><strong>Campo:</strong></td>
            <td><select name="dbh_campo" size="1">
                <?
			  echo '<option value="0">Nenhum...</option>';
 			  $sql = "select c.codcam,nomecam 
			          from db_syscampo c
						   inner join db_sysarqcamp m on c.codcam = m.codcam 
		              where m.codarq = ".$HTTP_POST_VARS["dbh_tabela"];						  
			  $sql .= " order by m.seqarq";
			  $result = pg_exec($sql);
			  for($i=0;$i<pg_numrows($result);$i++){
			    echo '<option value="'.pg_result($result,$i,"codcam").'" '.(isset($HTTP_POST_VARS["dbh_campo"]) && $HTTP_POST_VARS["dbh_campo"] == pg_result($result,$i,"codcam")?"selected":"").'>'.pg_result($result,$i,"nomecam").'</option>';
			  }
			  ?>
              </select></td>
            <td align="right"><strong>Usu&aacute;rio:</strong></td>
            <td><select name="dbh_usuario" size="1" >
                <?
			  echo '<option value="0">Nenhum...</option>';
			  $sql = "select id_usuario,login 
			          from db_usuarios
			          order by nome";
			  $result = pg_exec($sql);
			  for($i=0;$i<pg_numrows($result);$i++){
			    echo '<option value="'.pg_result($result,$i,"id_usuario").'" '.(isset($HTTP_POST_VARS["dbh_usuario"]) && $HTTP_POST_VARS["dbh_usuario"] == pg_result($result,$i,"id_usuario")?"selected":"").'>'.str_pad(pg_result($result,$i,"login"),20).'</option>';
			  }
			  ?>
              </select></td>
          </tr>
          <tr> 
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr valign="top"> 
            <td height="21" colspan="4"> <table width="100%" border="0" cellspacing="0">
                <tr align="center"> 
                  <td colspan="2"><strong>Chaves de Acesso:</strong></td>
                </tr>
                <?
                if(isset($HTTP_POST_VARS["dbh_tabela"]) && $HTTP_POST_VARS["dbh_tabela"] != '0'){
				  $result = pg_query("select c.codcam,nomecam,tamanho
				                      from db_sysprikey p
									       inner join db_syscampo c on c.codcam = p.codcam
					  				  where codarq = $dbh_tabela");
 				  $clrotulocampo = new rotulocampo;
				  for($x=0;$x<pg_numrows($result);$x++){
				    db_fieldsmemory($result,$x);
				    $qcampos[$x] = $codcam;
				    $vcampos[$x] = $nomecam;
					$clrotulocampo->label($nomecam);
                    echo "<tr>\n"; 
					$campo = "L".$nomecam;
                    echo "  <td width=\"50%\" align=\"right\">".$$campo."</td>\n";
                    echo "  <td width=\"50%\" align=\"left\">";
					if($tamanho>60) $tamanho = 60;
					$campo = "I".$nomecam;
					db_input($nomecam,$tamanho,$$campo,true,'text',4);
					echo "  </td>\n";
					echo "</tr>\n";
				  }
                  echo "<tr>\n"; 
                  echo "  <td width=\"50%\" align=\"right\" title=\"Data Inicial da Pesquisa\"><strong>Data:</strong></td>\n";
                  echo "  <td width=\"50%\" align=\"left\">";
				  $clrotulocampo->label('k00_dtoper');
   				  db_inputdata('k00_dtoper',@$k00_dtoper_dia,@$k00_dtoper_mes,@$k00_dtoper_ano,true,'text',4);
				  echo "  </td>\n";
				  echo "</tr>\n";
				  ?>
                <tr align="center"> 
                  <td colspan="2"><input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar"></td>
                </tr>
                <?
				}
				?>
              </table></td>
          </tr>
        </table>
        <?	  
        if(isset($HTTP_POST_VARS["pesquisar"])){
		?>
          
        <table width="100%" border="1" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="4%" bgcolor="#FFCC00">Data</td>
            <td width="4%" bgcolor="#FFCC00">Hora</td>
            <td width="8%" bgcolor="#FFCC00">Usu&aacute;rio</td>
            <td width="8%" bgcolor="#FFCC00">Campo</td>
            <td width="3%" bgcolor="#FFCC00">Tipo</td>
            <td width="28%" bgcolor="#FFCC00">Conte&uacute;do Atual</td>
            <td width="45%" bgcolor="#FFCC00">Conte&uacute;do Anterior</td>
          </tr>
          <?
                  $sqlkey = "";
                  $sqlor = "";
      $contador = 0;
		  for($c=0;$c<sizeof($vcampos);$c++){
		    if(trim($$vcampos[$c])!=""){
          $contador += 1 ;
 		      $sqlkey .= $sqlor." ( id_codcam = ".$qcampos[$c]." and campotext = '".$$vcampos[$c]."' ) ";
                      $sqlor = " or ";
		    }
		  }


		  $sql = "select distinct d.*,c.nomecam, u.nome
		          from db_acount d
				       inner join db_syscampo c on c.codcam = d.codcam
				       inner join db_usuarios u on u.id_usuario = d.id_usuario
				  where d.codarq = $dbh_tabela and 
				       d.id_acount in ";
      if( $contador == 0 ){
        $sql .= " ( select id_acount from db_acountkey ".($sqlkey!=""?" where ".$sqlkey:"")." ) ";
      }else{
        $sql .= " ( select id_acount from db_acountkey ".($sqlkey!=""?" where ".$sqlkey:"")." group by id_acount having count(*) = $contador ) ";
      }
		  if($dbh_campo!=0){
		     $sql .= " and d.codcam = $dbh_campo ";
		  }
		  if($dbh_usuario!=0){
		     $sql .= " and d.id_usuario = $dbh_usuario ";
		  }
		  if($k00_dtoper_dia!=0){
		     $sql .= " and d.datahr >= ".mktime(0,0,0,$k00_dtoper_mes,$k00_dtoper_dia,$k00_dtoper_ano);
		  }
 		 $sql .= " order by id_acount ";
//		    echo "<tr>\n"; 
//		    echo "  <td colspan=\"6\" width=\"11%\">".$sql."</td>\n";
//		    echo "</tr>\n";

		  $result = pg_query($sql);

      if ( pg_numrows($result) > 0 ) {
        $id_acount_ant = pg_result($result,0,"id_acount");
        $cor="#CCCCCC";
      }

		  for($x=0;$x<pg_numrows($result);$x++){

        if ( pg_result($result,$x,"id_acount") != $id_acount_ant ) {
          $id_acount_ant = pg_result($result,$x,"id_acount");
          if ( $cor=="#7F7F7F" ) {
            $cor="#CCCCCC";
          } else {
            $cor="#7F7F7F";
          }
        }

                    $chavetitle = "";
		    db_fieldsmemory($result,$x);
 		    $sql = "select campotext as keychave ,c.nomecam as nomecamkey, actipo
                            from db_acountkey y
                                 inner join db_syscampo c on codcam = id_codcam
                            where id_acount = $id_acount";
                    $res = pg_query($sql);
                    if($res!=false){
                      for($ii=0;$ii<pg_numrows($res);$ii++){
                        db_fieldsmemory($res,$ii);
                        $chavetitle .= $nomecamkey."->".$keychave."\n";
                      }
		    }
        	    $processa = true;
                    if($dbh_campo!=0){
		      if ($dbh_campo != $codcam )
                         $processa = false;
                    }
                    if($processa){
		      echo "<tr>\n"; 
		      echo "  <td bgcolor=$cor width=\"11%\" title=\"".$chavetitle."\">".date("d-m-Y",$datahr)."</td>\n";
		      echo "  <td bgcolor=$cor width=\"10%\" title=\"".$chavetitle."\">".date("H-i",$datahr)."</td>\n";
		      echo "  <td bgcolor=$cor width=\"22%\" title=\"".$chavetitle."\">(".$id_usuario.")".substr($nome,0,20)."</td>\n";
		      echo "  <td bgcolor=$cor width=\"22%\" title=\"".$chavetitle."\">".$nomecam."</td>\n";
		      echo "  <td bgcolor=$cor width=\"2%\"  title=\"".$chavetitle."\">".$actipo."</td>\n";
		      echo "  <td bgcolor=$cor width=\"16%\">".$contatu."&nbsp;</td>\n";
		      echo "  <td bgcolor=$cor width=\"17%\">".$contant."&nbsp;</td>\n";
		      echo "</tr>\n";
                    }

		  }
		  ?>
        </table>
    	<?
		}
		?>
     </td>
  </tr>
</form>
</table>
      <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

</body>
</html>