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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_procandam_classe.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_proctransand_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clprocandam    = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clprotprocesso = new cl_protprocesso;
$clproctransand = new cl_proctransand;
$rotulo = new rotulocampo();
$rotulo->label("p63_codtran");
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
   function js_processo(processo){
       window.db_iframe.jan.location.href='pro4_mostraprocesso.php?codtran='+processo;
       document.form2.p63_codtran.value = processo;
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
 }


</script>
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
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <table cellspacing = 0>
      <tr>
         <td title="<?=$Tp63_codtran;?>">
              <?=$Lp63_codtran;?>
         </td>
         <td>
           <form method="post" action="" name="form2">
              <input name="p63_codtran" size=5 value="">
              <input type="submit" name="db_opcao" value="Receber" onClick="return js_campo()">
           </form>
	   <script>
	   function js_campo(){
	     if(document.form2.p63_codtran.value == ""){
	       alert('Escolha o processo a ser Recebido!')
	       return false
	     }else{
	       return true
	     }
	     return false
	   }
	   </script>
         </td>
      </tr>
     </table>
		<?
    if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Receber"){

      echo "<table cellspacing = 0>";
      echo "  <tr>";
	    echo "    <td align='center'>";

      db_criatermometro('termometro', 'Concluido...', 'blue', 1);

			db_inicio_transacao();
			$sqlerro = false;
			$sql = "
							select *
								from proctransferproc
					inner join proctransfer on p63_codtran = p62_codtran
					 left join proctransand on p64_codtran = p63_codtran
					 left join arqproc      on p68_codproc = p63_codproc
							 where p63_codtran = $p63_codtran
								 and p64_codtran is null
								 and p68_codproc is null
								 and p62_coddeptorec   = ".db_getsession("DB_coddepto")."
								 and ( p62_id_usorec   = ".db_getsession("DB_id_usuario")."
								  or   p62_id_usorec   = 0 )";

			$rs = db_query($sql);
			$erro = 0;
			if (pg_num_rows($rs) > 0) {
				for ($i = 0; $i < pg_num_rows($rs); $i++) {

          db_atutermometro($i, pg_num_rows($rs), 'termometro');
					db_fieldsmemory($rs,$i);
					$sqlproc = "select p58_despacho,p58_publico  from protprocesso where p58_codproc = ".$p63_codproc;

					$rsproc = db_query($sqlproc);
					//inclui o andamento
					$despach = pg_result($rsproc,0,"p58_despacho");
					$publico = pg_result($rsproc,0,"p58_publico");
					$despach =  str_replace("'","",$despach);
					$publico =  str_replace("'","",$publico);

					$publico = ($publico=='f'?"false":"true");

					$result=$clprocandam->sql_record($clprocandam->sql_query_file(null,"*",null,"p61_codproc=$p63_codproc"));
					$numrows=$clprocandam->numrows;

					if ($numrows==0) {
						$clprocandam->p61_despacho ='Tramite Inicial';
					} else {
						$clprocandam->p61_despacho = $despach;
					}

					$clprocandam->p61_publico    = $publico;
					$clprocandam->p61_codproc    = $p63_codproc;
					$data                        = date('Y-m-d');
					$hora                        = db_hora();
					$clprocandam->p61_dtandam    = $data;
					$clprocandam->p61_hora       = $hora;
					$clprocandam->p61_id_usuario = db_getsession("DB_id_usuario");
					$clprocandam->p61_coddepto   = db_getsession("DB_coddepto");
					$clprocandam->incluir(null);

					if ($clprocandam->erro_status == "1") {
						$erro = 0;
					} else {
						$clprocandam->erro(true,false);
						$erro = 1;
						$sqlerro =  true;
						break;

					}

					//inclui a transferencia e o andamento do processo na tabela proctransand
					$clproctransand->p64_codtran  = $p63_codtran;
					$clproctransand->p64_codandam = $clprocandam->p61_codandam;
					$clproctransand->incluir(null);

					if ($clproctransand->erro_status == "1") {
						$erro = 0;
					} else {
						$clproctransand->erro(true,false);
						$erro = 1;
						$sqlerro = true;
						break;
					}

					//atualiza codandam da tabela protprocesso;
					$clprotprocesso->p58_codproc  = $p63_codproc;
					$clprotprocesso->p58_codandam = $clprocandam->p61_codandam;
					$clprotprocesso->p58_despacho = " ";
          $clprotprocesso->p58_instit   = db_getsession("DB_instit");
					$clprotprocesso->alterar($p63_codproc);

					if ($clprotprocesso->erro_status == "1") {
						$erro = 0;
					} else {
						$clprotprocesso->erro(true,false);
						$sqlerro=true;
						$erro = 1;
						break;
					}
				}

				if ($erro == 0) {
					echo "<script>alert('Recebimento efetuado com sucesso!')</script>";
				}
			} else {
				echo "<script>alert('Recebimento inválido ou já efetuado');</script>";
			}
			db_fim_transacao($sqlerro);

      echo "		</td>";
      echo "	</tr>";
			echo "</table>";
      echo "  <tr>";

			db_redireciona();

		} else {

      $sqltran="
						 select distinct
						        p62_codtran,
										p62_dttran,
										p62_hora,
										descrdepto,
										login,
							array_to_string( array_accum( p58_numero||'/'||p58_ano ), ', ') as p63_codproc,
--										case
--											when count(*) = 1 then
--												(select  cast(protprocesso.p58_numero||'/'||p58_ano as varchar)
--													 from proctransferproc x
--													      inner join protprocesso on p63_codproc = p58_codproc
--													where x.p63_codtran = p62_codtran
--													limit 1)
--											else
--												null
--										end as p63_codproc,
                    p58_requer,
                    p51_descr as p58_codigo
							 from proctransferproc
				 inner join proctransfer on p62_codtran = p63_codtran
				 inner join protprocesso on p58_codproc = p63_codproc
				 inner join tipoproc     on p58_codigo  = p51_codigo
				 inner join db_depart    on coddepto    = p62_coddepto
				 inner join db_usuarios  on id_usuario  = p62_id_usuario
					left join proctransand on p64_codtran = p62_codtran
					left join arqproc      on p68_codproc = p63_codproc
							where	p62_coddeptorec   = ".db_getsession("DB_coddepto")."
								and ( p62_id_usorec   = ".db_getsession("DB_id_usuario")."
								 or   p62_id_usorec   = 0 )
								and p64_codtran is null
								and p68_codproc is null
					 group by p62_codtran,
										p62_dttran,
										p62_hora,
										descrdepto,
                    login,
                    p63_codproc,
                    p58_requer,
                    p51_descr
					 order by p62_codtran desc";

      //echo $sqltran;
      db_lovrot($sqltran,15,"()","","js_processo|p62_codtran");
		}
    ?>
   </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=750;
$func_iframe->altura=400;
$func_iframe->titulo='Processos da transferência';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
</body>
</html>
