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

include_once ("classes/db_sau_config_classe.php");
include_once ("classes/db_cgs_und_classe.php");
include_once ("classes/db_cgs_cartaosus_classe.php");
include_once ("classes/db_sau_cadsus_classe.php");
include_once ("classes/db_sau_cadsusreg_classe.php");
include_once ("classes/db_sau_cadsusversao_classe.php");
include_once ("classes/db_cgs_classe.php");
include_once ('libs/db_stdlibwebseller.php');
require_once ("libs/db_utils.php");

/*
*  Fun��o atualiza cadsus
*
*  Parametros
*
*      Termometro : inteiro 0 = Chamada via cron
*                           1 = chamada via Rotina importa��o cart�o SUS Dbportal
*                           2 = Chamada via REquisi��o Ajax no cadastro cart�o sus
*/
function atualiza_cadsus($termometro = 0, $conn, $cod_cgs = null ,$DB_SERVIDOR ,$DB_BASE ,$DB_PORTA ,$DB_USUARIO ,$DB_SENHA){
	$clsau_config = new cl_sau_config ( );
	$clsau_cadsus = new cl_sau_cadsus ( );
	$user = db_getsession ( "DB_id_usuario" );
	$clcgs = new cl_cgs ( );
	$clcgs_und = new cl_cgs_und ( );
	$clcgs_cartaosus = new cl_cgs_cartaosus ( );
	$clcgs_cartaosusreg = new cl_sau_cadsusreg ( );
 	$clsau_cadsusversao = new cl_sau_cadsusversao ( );
	$erro = false;

	//criando arquivo de LOG
	$arq1 = "/tmp/log_importacao_cadsus_" . date ( "Y-m-d", db_getsession ( "DB_datausu" ) ) . ".txt";

	//buscar informa��es de conex�o
	$sql_conf = sql_query_ext ( "", "*", "", "" );
	$result = $clsau_config->sql_record ( $sql_conf );
	if ($clsau_config->numrows > 0) {

		$obj1 = db_utils::fieldsmemory ( $result, 0 );
		//Estabelecer conex��o com interbase

		//seleciona ip dinamico ou fixo
		if($obj1->s103_c_ipauto=='f'){
		   $ip_con=$obj1->s103_c_ip;
		}else{
		   $ip_con=$_SERVER['REMOTE_ADDR'];
	    }
	    $obj1->s103_c_sgdb=stripslashes($obj1->s103_c_sgdb);

        //echo$obj1->s103_c_ip . ":" . $obj1->s103_c_sgdb.",".$obj1->s103_c_usuario.",".$obj1->s103_c_senha;
		if($obj1->s103_i_tipodb==1){
            $con_cadsus = @ibase_connect ( $ip_con. ":" . $obj1->s103_c_sgdb, $obj1->s103_c_usuario, $obj1->s103_c_senha );
		}else{
            $strcon=" host=$ip_con port=$obj1->s103_i_porta dbname=$obj1->s103_c_sgdb user=$obj1->s103_c_usuario";
            if($obj1->s103_c_senha!=""){
               $strcon.=" password=$obj1->s103_c_senha ";
            };
            //echo"STR Connect = [ $strcon ] ";
           $con_cadsus = @pg_connect($strcon);
       }

    //Verificando conex�o
		if ($con_cadsus == true) {

      //Verificando versao do Banco
      $sql_ver="SELECT TB_MS_VERSAO_BANCO.NU_VERSAO as versao FROM TB_MS_VERSAO_BANCO;";
      if($obj1->s103_i_tipodb==1){
           $result_versao = @ibase_query ( $con_cadsus, $sql_ver ) or log_erro ( "Falha durante select na base cadsus", "Erro durante select da versao do banco",$arq1);
      }else{
           $result_versao = @db_query ( $con_cadsus, $sql_ver ) or log_erro ( "Falha durante select na base cadsus", "Erro durante select da versao do banco",$arq1);
      }
      //pegando a ver��o do banco
      if($obj1->s103_i_tipodb==1){
               $row = ibase_fetch_object ( $result_versao );
               $versao = $row->VERSAO;
      }else{
               $versao = pg_result($result_versao,0,0);
      }

      $sWhere = '';
      if($termometro==2){
				$sql_cgs="select * from cgs_und where z01_i_cgsund=$cod_cgs ";
			    $result_cgs=db_query($conn, $sql_cgs);
			    $linhas_cgs=pg_num_rows($result_cgs);
				if($linhas_cgs>0){
				    $obj_cgs = db_utils::fieldsmemory ( $result_cgs, 0 );
					if(($obj_cgs->z01_v_ident!="")&&($obj_cgs->z01_v_ident!="0")&&($obj_cgs->z01_v_ident!=null)){
				        $sWhere .= " where ( '$obj_cgs->z01_v_ident' in ( select rl_ms_usuario_documentos.nu_documento
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento     on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					            where tb_ms_tipo_documento.co_tipo_documento = '10'
					         ) or trim(tb_ms_usuario.NO_USUARIO) = trim('$obj_cgs->z01_v_nome')) ";
					}elseif(($obj_cgs->z01_v_nome!="")||($obj_cgs->z01_v_nome!="")){
						$sWhere .= " where trim(tb_ms_usuario.NO_USUARIO) = trim('$obj_cgs->z01_v_nome')";
					}else{
						return 4;
					}
			  }
		  }
// $sWhere = " where tb_ms_usuario.NO_USUARIO in ('LUIZ MARIO GONCALVES MAZZITELLI', 'EDSON LUIZ FIALHO DA ROSA') ";
// $sWhere = " where tb_ms_usuario.NO_USUARIO in ('LIZIANE FIUZA DA SILVA') ";
//$sWhere = " where tb_ms_usuario.NO_USUARIO in ('CLEIR MARTEGANHA RODRIGUES') ";
      //echo" OK! ";
			$sql  = "select  count(*) as linhas";
			$sql .= "        from tb_ms_usuario ";
			$sql .= "          left join tb_ms_usuario_cns_elos  tab_provisorio   on tab_provisorio.co_usuario = tb_ms_usuario.co_usuario and tab_provisorio.tp_cartao = 'P' and tab_provisorio.st_excluido = '0' ";
			$sql .= "          left join tb_ms_usuario_cns_elos  tab_definitivo   on tab_definitivo.co_usuario = tb_ms_usuario.co_usuario and tab_definitivo.tp_cartao = 'D' and tab_definitivo.st_excluido = '0' ";
			$sql .= "          left join rl_ms_usuario_endereco   on rl_ms_usuario_endereco.co_usuario = tb_ms_usuario.co_usuario and rl_ms_usuario_endereco.st_excluido = '0' ";
			$sql .= "          left join tb_ms_endereco           on tb_ms_endereco.co_endereco        = rl_ms_usuario_endereco.co_endereco ";
			$sql .= "          left join tb_ms_municipio          on tb_ms_municipio.co_municipio      = tb_ms_endereco.co_municipio ";
			$sql .= "          left join tb_ms_sexo               on tb_ms_sexo.co_sexo                = tb_ms_usuario.co_sexo ";
      $sql .= $sWhere;

			if($obj1->s103_i_tipodb==1){
         $ib_linhas = @ibase_query ( $con_cadsus, $sql ) or log_erro ( "Falha durante select na base cadsus", "erro durante a execucao do select na base cadsus SQL=[$sql]", $arq1 );
			}else{
         $ib_linhas = @db_query ( $con_cadsus, $sql ) or log_erro ( "Falha durante select na base cadsus", "erro durante a execucao do select na base cadsus SQL=[$sql]", $arq1 );
      }
			//Executando a query
			$sql = " select tab_provisorio.co_numero_cartao as provisorio,
					        tab_definitivo.co_numero_cartao as definitivo,
					        tb_ms_usuario.NO_USUARIO        as nome,
					        tb_ms_usuario.DT_NASCIMENTO     as nasc,
					        tb_ms_endereco.NO_LOGRADOURO    as endereco,
					        tb_ms_endereco.NU_LOGRADOURO    as numero,
					        tb_ms_endereco.NO_bairro        as bairro,
					        tb_ms_endereco.CO_CEP           as cep,
					        tb_ms_municipio.DS_MUNICIPIO    as cidade,
					        tb_ms_sexo.CO_SEXO              as sexo,
					        ( select rl_ms_usuario_documentos.nu_documento
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					            where tb_ms_tipo_documento.co_tipo_documento = '91'
					        ) as certidao,
					       ( select rl_ms_usuario_documentos.NU_LIVRO
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento     on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					            where tb_ms_tipo_documento.co_tipo_documento = '91'
					        ) as livro,
					        ( select rl_ms_usuario_documentos.NU_FOLHA
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento     on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					            where tb_ms_tipo_documento.co_tipo_documento = '91'
					        ) as folha,
					        ( select rl_ms_usuario_documentos.nu_documento
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento     on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					            where tb_ms_tipo_documento.co_tipo_documento = '10'
					         ) as ident,
					        ( select rl_ms_usuario_documentos.nu_documento
					            from rl_ms_usuario_documentos
					            inner join tb_ms_tipo_documento     on tb_ms_tipo_documento.co_tipo_documento = rl_ms_usuario_documentos.co_tipo_documento and rl_ms_usuario_documentos.co_usuario = tb_ms_usuario.co_usuario
					           where tb_ms_tipo_documento.co_tipo_documento = '02'
					         ) as cpf,
					         tb_ms_municipio.SG_uf    as uf
					        from tb_ms_usuario
					            left join tb_ms_usuario_cns_elos tab_provisorio   on tab_provisorio.co_usuario = tb_ms_usuario.co_usuario and tab_provisorio.tp_cartao = 'P' and tab_provisorio.st_excluido = '0'
					            left join tb_ms_usuario_cns_elos tab_definitivo   on tab_definitivo.co_usuario = tb_ms_usuario.co_usuario and tab_definitivo.tp_cartao = 'D' and tab_definitivo.st_excluido = '0'
					            left join rl_ms_usuario_endereco   on rl_ms_usuario_endereco.co_usuario = tb_ms_usuario.co_usuario and rl_ms_usuario_endereco.st_excluido = '0'
					            left join tb_ms_endereco           on tb_ms_endereco.co_endereco        = rl_ms_usuario_endereco.co_endereco
					            left join tb_ms_municipio          on tb_ms_municipio.co_municipio      = tb_ms_endereco.co_municipio
					            left join tb_ms_sexo               on tb_ms_sexo.co_sexo                = tb_ms_usuario.co_sexo ";
		  $sql .= $sWhere;
			$sql .= "order by tb_ms_usuario.no_usuario, tab_definitivo.co_numero_cartao;";
			// echo"\n SQL -> [$sql] \n";

      //query do CADSUS
      if($obj1->s103_i_tipodb==1){
			    $ib_result = @ibase_query ( $con_cadsus, $sql ) or log_erro ( "Falha durante select na base cadsus", "erro durante a execucao do select na base cadsus SQL=[$sql]", $arq1 );
			}else{
          $ib_result = @db_query ( $con_cadsus, $sql ) or log_erro ( "Falha durante select na base cadsus", "erro durante a execucao do select na base cadsus SQL=[$sql]", $arq1 );

      }

			if (! $ib_result) {
				log_erro ( "Falha durante select na base cadsus", "erro durante a execucao do select na base cadsus SQL=[$sql]", $arq1 );
			}

			//gerando termometro
		  if($obj1->s103_i_tipodb==1){
          $row = ibase_fetch_object ( $ib_linhas );
			    $limite = $row->LINHAS;
			}else{
          $limite = pg_num_rows($ib_result);// pg_result($ib_linhas,0,0);
          //$limite = $limite-1;
      }
			$ini = date ( "h:i:s" );
			$cont = 0;

			if($obj1->s103_i_tipodb==1){
         ibase_close ( $con_cadsus );
      }else{
         pg_close( $con_cadsus );
         pg_close( $conn );

         //iniciando novamente conex�o do sistema
         //echo"<br> host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA <br>";
         if(!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
             echo "Contate com Administrador do Sistema! (Conex�o Inv�lida.)   <br>Sess�o terminada, feche seu navegador!\n";
               session_destroy();
                 exit;
         }
         db_query("select fc_startsession();");
      }

      //buscando versao do banco no sistema
      $sql_versao=  $clsau_cadsusversao->sql_query("","*",""," s150_i_versao=$versao ");
      $result_versao= $clsau_cadsusversao->sql_record($sql_versao);
      if($clsau_cadsusversao->numrows==0){
           $erro = " Versao do Cadsus incompativel ";
           $log = " Versao do Cadsus Imcompativel ";
           log_erro_versao ( $versao );
      }

      //iniciar uma sec��o no postgres
      db_query ( 'begin' );

      //registrar atualiza��o
      $clsau_cadsus->s136_d_data = date ( "Y-m-d" );
      $clsau_cadsus->s136_c_hora = date ( "H:i" );
      $clsau_cadsus->s136_i_user = $user;
      $clsau_cadsus->incluir ( null );
      if ($clsau_cadsus->erro_status == 0) {
          $erro = " Inclusao cartaosus ";
          $log = "[Atualiza cart�o]Erro durante inclusao do clcgs_cartaosus -> " . $clsau_cadsus->erro_msg;
          $log .= "\n user=$user \n";
          log_erro ( $erro, $log, $arq1 );
      }
      $import = $clsau_cadsus->s136_i_codigo;


      // echo"<br> Limite= $limite";
      while ( $cont < $limite ) {
				flush ();
				//echo"\r $cont";
                try {
				   if($obj1->s103_i_tipodb==1){
                       $row = @ibase_fetch_object ( $ib_result );
                       if($row==false){
                          jogar_erro("Falha de conex�o(InterBase) ");
                       }
                       $row = convert_obj_interbase($row);
                   }else{
                       $row = @db_utils::fieldsmemory ( $ib_result,$cont);
                       if($row==false){
                          jogar_erro("Falha de conex�o(Postgres) ");
                       }
                   }
                } catch (Exception $e) {
                    $erro = "!".$e->getMessage() ;
                    $log = "Erro: ".$e->getMessage()."! Conex�o perdida durante o processo";
                    log_erro ( $erro, $log, $arq1 );
                }
				//incrementa termometro
				$cont ++;

				if ($termometro == 1) {
					db_atutermometro ( $cont, $limite, 'termometro', 1, "$cont - $limite" );
				} else {
					if($termometro == 0){
					   echo (" Tempo inicio:" . $ini . " | atual:" . date ( "h:i:s" ) . " [$cont|$limite] " . substr ( "" . $cont / ($limite / 100), 0, 4 ) . "% \r");
					}
			    }

				//1.procura no DBPortal pelo c�digo do cartao provisorio do CADSUS
				$result01 = null;
				if (($row->provisorio != "") && ($row->provisorio != null)) {
					$sql1 = "select * from cgs_cartaosus where s115_c_cartaosus='" . $row->provisorio . "'";
					$result01 = db_query ($conn, $sql1 ) or die ( "Erro 01 - " . pg_errormessage () . " sql -> [$sql1] " );;
				}

				//1.se achou um CGS no DBPortal faz uma verificacao
				if ((isset ( $result01 )) && (pg_num_rows ( @$result01 ) > 0)) {
					$obj3 = db_utils::fieldsmemory ( $result01, 0 );
					if ($obj3->s115_c_tipo == "P") {
						//      1.se o campo cartao SUS definitivo estiver preenchido no sistema do CADSUS,
            //        verifica se o CGS encontrado no DBPortal tem o cartao definitivo preenchido e se for igual
						$result02 = null;
						if (($row->definitivo != '') && ($row->definitivo != null)) {
							$sql2 = "select * from cgs_cartaosus where s115_c_cartaosus='$row->definitivo'";
							$result02 = db_query ($conn, $sql2 ) or die ( "Erro 02 - " . pg_errormessage () . " sql -> [$sql2] " );;
						}

						//      1.se tiver preenchido
					if ((isset ( $result02 )) && (pg_num_rows ( @$result02 ) > 0)) {
					$obj2 = db_utils::fieldsmemory ( $result02, 0 );
							//           1.se cart�o deninitivo do cadsus for igual ao definitivo do CGS
							//     	  1.atualizar os dados obrigatorios
							if (cruza_dados ( $row, $obj2->s115_i_cgs, $conn )) {
								if ($obj2->s115_c_cartaosus == $row->definitivo) {
									get_log ( $arq1, $import, 1, $row, $clcgs_cartaosusreg );
								} else {
									get_log ( $arq1, $import, 3, $row, $clcgs_cartaosusreg );
								}
								atualiza_dados ( $arq1, $row, $obj2->s115_i_cgs, 4, $clcgs_und, $clcgs_cartaosus );
							}
							//      2.se n�o estiver preenchido
						} else {
							//           1.atualiza a informacao do cartao definitivo no CGS
							//           2.atualizar os dados obrigatorios
							if (cruza_dados ( $row, $obj3->s115_i_cgs, $conn )) {
								atualiza_dados ( $arq1, $row, $obj3->s115_i_cgs, 2, $clcgs_und, $clcgs_cartaosus );
								get_log ( $arq1, $import, 2, $row, $clcgs_cartaosusreg );
							}else{
							    if(($row->definitivo!=null)&&($row->definitivo!=""  )){
									atualiza_cartao($arq1,null,$obj3->s115_i_cgs,$row->definitivo,"D");
							    }
							}
						}
					} else {
						if (cruza_dados ( $row, $obj3->s115_i_cgs, $conn )) {
							atualiza_dados ( $arq1, $row, $obj3->s115_i_cgs, 4, $clcgs_und, $clcgs_cartaosus );
							get_log ( $arq1, $import, 10, $row, $clcgs_cartaosusreg );
							atualiza_cartao ( $arq1, $obj3->s115_i_codigo, $obj3->s115_i_cgs, $row->provisorio, "P" );
						}
					}
					//2.se n�o achou
				} else {
				    //   1.procura pelo Cart�o definitivo
					   $result03 = null;
					   if (($row->definitivo != "") && ($row->definitivo != null)) {
						  $sql03 = "select * from cgs_cartaosus where s115_c_cartaosus='" . $row->definitivo . "' ";
						  $result03 = db_query ($conn, $sql03 ) or die ( "Erro 03 - " . pg_errormessage () . " sql -> [$sql03] " );
				       }
               //echo "<br> $sql03 <br>";
						//   1.se axou
						if ((isset ( $result03 )) && (pg_num_rows ( $result03 ) > 0)) {
							$obj4 = db_utils::fieldsmemory ( $result03, 0 );
							    if($obj4->s115_c_tipo!="D"){
                    //echo "<br> 332 <br>";
							    	atualiza_cartao ( $arq1, $obj4->s115_i_codigo, $obj4->s115_i_cgs, $row->definitivo, "D" );
							    }
							    if (cruza_dados ( $row, $obj4->s115_i_cgs, $conn )) {
                    //echo "<br> 336 <br> ";
								    atualiza_dados ( $arq1, $row, $obj4->s115_i_cgs, 3, $clcgs_und, $clcgs_cartaosus );
								    get_log ( $arq1, $import, 1, $row, $clcgs_cartaosusreg );
							    }else{
							          if(($row->provisorio!=null)&&($row->provisorio!="")){
										   atualiza_cartao($arq1,null,$obj4->s115_i_cgs,$row->provisorio,"P");
									  }
							    }

							//   2.se n�o axou
						} else {
							//       1.procura pelo RG no CGS
							$result04=null;
							if (($row->ident != '') && ($row->ident != null)) {
								$sql4 = "select * from cgs_und left join cgs_cartaosus on s115_i_cgs=z01_i_cgsund where z01_v_ident='$row->ident'";
								$result04 = db_query ($conn, $sql4 ) or die ( "Erro 04 - " . pg_errormessage () . " sql -> [$sql4] " );
							}
							//      1.se achou
							if ((isset ( $result04 )) && (pg_num_rows ( @$result04 ) > 0) && (($row->ident != '') && ($row->ident != null))) {
								$obj5 = db_utils::fieldsmemory ( $result04, 0 );
								//           1.Verifica se n�o tem um cart�o registrado errado
								if ((($obj5->s115_c_cartaosus != null) && ($obj5->s115_c_cartaosus != "")) && ($obj5->s115_c_cartaosus == $row->definitivo)) {
									$log = 5;
								} else {
									$log = 6;
								}
								$op = 1;
								//           2.atualiza todas informacoes cadastrais obrigatorias do CADSUS no CGS encontrado:
								if (($obj5->z01_i_cgsund != "") && ($obj5->z01_i_cgsund != null)) {
									if (cruza_dados ( $row, $obj5->z01_i_cgsund, $conn )) {
										atualiza_dados ( $arq1, $row, $obj5->z01_i_cgsund, $op, $clcgs_und, $clcgs_cartaosus );
										get_log ( $arq1, $import, $log, $row, $clcgs_cartaosusreg );
									}else{
										if(($row->provisorio!=null)&&($row->provisorio!="")){
										   atualiza_cartao($arq1,null,$obj5->z01_i_cgsund,$row->provisorio,"P");
										}
										if(($row->definitivo!=null)&&($row->definitivo!="")){
										   atualiza_cartao($arq1,null,$obj5->z01_i_cgsund,$row->definitivo,"D");
										}
									}
								}
								//      2.se n�o achou
							} else {
								//           1.procura pelos campos: 1. nro da certid�o 2. nro do livro 3. nro da folha
								$result05 = null;
								if (($row->certidao != '') && ($row->certidao != null) && ($row->livro != '') && ($row->livro != null) && ($row->folha != '') && ($row->folha != null)) {
									$sql = "select * from cgs_und where z01_c_certidaonum='$row->certidao' and z01_c_certidaolivro='$row->livro' and z01_c_certidaofolha='$row->folha' ";
									$result05 = db_query ($conn, $sql );
								}
								//           1.se achou
								if ((isset ( $result05 )) && (pg_num_rows ( @$result05 ) > 0)) {
    							//               1.faz o mesmo que o item 2.1.1.1 (achou pelo RG)
									$obj6 = db_utils::fieldsmemory ( $result05, 0 );
									if (cruza_dados ( $row, $obj6->z01_i_cgsund, $conn )) {
										atualiza_dados ( $arq1, $row, $obj6->z01_i_cgsund, 1, $clcgs_und, $clcgs_cartaosus );
										get_log ( $arq1, $import, 7, $row, $clcgs_cartaosusreg );
									}else{
										if(($row->provisorio!=null)&&($row->provisorio!="")){
										   atualiza_cartao($arq1,null,$obj6->z01_i_cgsund,$row->provisorio,"P");
										}
										if(($row->definitivo!=null)&&($row->definitivo!="")){
										   atualiza_cartao($arq1,null,$obj6->z01_i_cgsund,$row->definitivo,"D");
										}
									}
									//           2.se n�o achou
								} else {
									//                1.Procurar pelo nome
									$result06 = null;
									if (($row->nome != '') && ($row->nome != null)) {
										$sql = "select * from cgs_und left join cgs_cartaosus on s115_i_cgs=z01_i_cgsund where z01_v_nome like '" . nome40 ( $row->nome ) . "'";
										$result06 = db_query ($conn, $sql ) or die ( "Erro 06 - " . pg_errormessage () . " sql -> [$sql] " );
									}
									if ((isset ( $result06 )) && (pg_num_rows ( @$result06 ) > 0)) {
										//                     1.faz o mesmo que o item 2.1.1.1 (achou pelo RG)

										$cartaonovo = $row->provisorio==null?$row->definitivo:$row->provisorio;
										$tipocartao = $row->provisorio==null?"D":"P";

										for( $x=0; pg_num_rows ( @$result06 ) > $x; $x++){
											$obj7 = db_utils::fieldsmemory ( $result06, $x );
											if( $obj7->s115_c_cartaosus == $row->provisorio || $obj7->s115_c_cartaosus == $row->definitivo ){
												$cartaonovo = 0;
												break;
											}
										}

										$obj7 = db_utils::fieldsmemory ( $result06, 0 );
										//if( $cartaonovo != null ){
										//	atualiza_cartao ( $arq1, null, $obj7->z01_i_cgsund, $cartaonovo, $tipocartao );
										//}
									    if (cruza_dados ( $row, $obj7->z01_i_cgsund, $conn )) {
											    atualiza_dados ( $arq1, $row, $obj7->z01_i_cgsund, 1, $clcgs_und, $clcgs_cartaosus );
											    get_log ( $arq1, $import, 9, $row, $clcgs_cartaosusreg );
									    }else{
										    if(($row->provisorio!=null)&&($row->provisorio!="")){
										        atualiza_cartao($arq1,null,$obj7->z01_i_cgsund,$row->provisorio,"P");
										    }
										    if(($row->definitivo!=null)&&($row->definitivo!="")){
										        atualiza_cartao($arq1,null,$obj7->z01_i_cgsund,$row->definitivo,"D");
										    }
									   }
										//                2.se n�o achou
									} else {
										//                     1.criar um CGS novo com todas as informacoes vindas do Cartao SUS;
										novo_cgs ( $arq1, $row, $clcgs_cartaosus, $clcgs, $clcgs_und );
										get_log ( $arq1, $import, 8, $row, $clcgs_cartaosusreg );
									}
								}
							}
						}

				}

			}// fim while



			//selecione todos os cart�es com entrada manual = 1
			$result_cartaosus=db_query($conn,"select s115_c_cartaosus, s115_c_tipo from cgs_cartaosus where s115_i_entrada = 1");
			//pega numero de linhas
			$linhas_cartao=pg_num_rows($result_cartaosus);
			//percorre todos verificando se eles existem no cadastro do cadsus
			$cont=0;
			for($icartao=0;$icartao<$linhas_cartao;$icartao++){

          //pega o codigo do cart�o sus da tabela cgs_cart�osus
				  $obj_cartao = db_utils::fieldsmemory ($result_cartaosus,$icartao);
				  //Busca nos registros do cad sus o CGS que foi posto manuamente
				  $quant_cartao=0;
          for($y=0;$y<$limite;$y++){
             if($obj1->s103_i_tipodb==1){

                 $row = ibase_fetch_object ( $ib_result );
                 $row = convert_obj_interbase($row);

             }else{
                 $row = db_utils::fieldsmemory ( $ib_result,$cont) ;
             }
             if(($obj_cartao->s115_c_cartaosus==$row->definitivo)||($obj_cartao->s115_c_cartaosus==$row->provisorio)){
                 $quant_cartao=1;
			   	 	 }
          }
				  //se n�o encontrar o codigo no cadsus significa que ele n�o existe
				  if($quant_cartao==0){
				     //ent�o � excluido
				     $cont++;
				     db_query($conn, "delete from cgs_cartaosus where s115_c_cartaosus='$obj_cartao->s115_c_cartaosus' ");
				  }
			}


		} else {
			$erro = true;
			if ($termometro == 1) {
				db_msgbox ( "Erro - Ao conectar no banco CADSUS verifique os par�metros em procedimentos" );
			} else {
				if($termometro==0){
				    log_erro ( "Falha ao conectar no banco CADSUS", "Erro - Ao conectar no banco CADSUS verifique os par�metros em procedimentos", $arq1 );
				}else{
					return 3;
				}
			}

		}
	} else {
		$erro = true;
		if ($termometro == 1) {
			db_msgbox ( "Configura��es n�o encontradas! Oper��o Abortada!" );
		} else {
			if($termometro==0){
			    log_erro ( "Falha configura��es n�o encontradas!", "Configura��es n�o encontradas! Oper��o Abortada! [SQL = $sql_conf ]", $arq1 );
			}else{
				return 2;
			}
		}
	}
	//finalizando movimenta��es no banco
	db_query ( "commit" );
	//db_query("rollback");
	if ($erro == false) {
		if ($termometro == 1) {
			db_msgbox ( 'Opera��o concluida com Sucesso!' );
			//liberando bot�o emitir
			?><script>
             document.form1.emitir.disabled=false;
             document.form1.codigo.value=<?=$import?>
         </script><?
		} else {
            if($termometro==0){
			   echo ("\n Opera��o Concluida com Sucesso! \n");
            }else{
					return 1;
			}
		}
	}
}
// op = op��o especial
//   1: inclui D e P
//   2: inclui D
//   3: inclui P
//   4: inclui nenhum
function atualiza_dados($arq1, $row, $cgs, $op, $clcgs_und, $clcgs_cartaosus) {

	$clcgs_und->z01_i_cgsund = $cgs;
	$clcgs_und->z01_v_ident = $row->ident;
	$clcgs_und->z01_v_nome= nome40($row->nome);
	if ($row->nasc != null) {
		$clcgs_und->z01_d_nasc = $row->nasc;
	} else {
		$clcgs_und->z01_d_nasc = "";
	}
	$clcgs_und->z01_v_ender = nome40( $row->endereco );
	if (trim($row->numero) != "S/N") {
		$clcgs_und->z01_i_numero = $row->numero;
	}

	$clcgs_und->z01_v_bairro        = nome40 ( $row->bairro );
	$clcgs_und->z01_v_cep           = $row->cep;
	$clcgs_und->z01_v_munic         = nome40 ( $row->cidade );
	$clcgs_und->z01_v_sexo          = $row->sexo;
	$clcgs_und->z01_c_certidaonum   = str_replace ( "'", "",$row->certidao );
	$clcgs_und->z01_c_certidaolivro = str_replace ( "'", "",$row->livro );
	$clcgs_und->z01_c_certidaofolha = str_replace ( "'", "",$row->folha );
	$clcgs_und->z01_v_cgccpf        = $row->cpf;
	$clcgs_und->z01_v_uf            = $row->uf;
	$clcgs_und->z01_d_ultalt        = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
	$clcgs_und->z01_d_dthabilitacao = "";
	$clcgs_und->z01_d_dtemissao     = "";
	$clcgs_und->z01_c_certidaodata  = "";
	$clcgs_und->z01_d_dtvencimento  = "";
	$clcgs_und->z01_d_dtemissaoctps = "";
	$clcgs_und->z01_d_datapais      = "";
	$clcgs_und->z01_d_dtemissaocnh  = "";

  if(($clcgs_und->z01_v_bairro!="")&&($clcgs_und->z01_v_bairro!=null)
	    &&($clcgs_und->z01_v_cep!="")&&($clcgs_und->z01_v_cep!=null)
	    &&($clcgs_und->z01_v_munic!="")&&($clcgs_und->z01_v_munic!=null)
	    &&($clcgs_und->z01_v_ender!="")&&($clcgs_und->z01_v_ender!=null)
	    &&($clcgs_und->z01_v_nome!="")&&($clcgs_und->z01_v_nome!=null)
	    &&($clcgs_und->z01_v_sexo!="")&&($clcgs_und->z01_v_sexo!=null)
	    &&($clcgs_und->z01_v_uf!="")&&($clcgs_und->z01_v_uf!=null)
	){
  /*
    echo "Bairro: {$clcgs_und->z01_v_bairro}<br>";
    echo "CEP: {$clcgs_und->z01_v_cep}<br>";
    echo "Munic: {$clcgs_und->z01_v_munic} <br>";
    echo "Edner: {$clcgs_und->z01_v_ender} <br>";
    echo "Nome: {$clcgs_und->z01_v_nome} <br>";
    echo "Sexo: {$clcgs_und->z01_v_sexo} <br>";
    echo "UF: {$clcgs_und->z01_v_uf} <br>";
    */
		$clcgs_und->alterar ( $cgs );
	    if ($clcgs_und->erro_status == 0) {
	    	$erro = " Altera��o cartaosus \n";
	    	$log = "$erro Erro durante alteracao do clcgs_und -> " . $clcgs_und->erro_msg;
    		log_erro ( $erro, $log, $arq1 );
    	}
	    $inc = false;
	    if (($row->provisorio != null) && ($row->provisorio != "") && (($op == 1) || ($op == 3))) {

	       $erro = validaCNS($row->provisorio);

		   $clcgs_cartaosus->s115_i_codigo = "";
	       $clcgs_cartaosus->s115_c_cartaosus = $row->provisorio;
		   $clcgs_cartaosus->s115_i_cgs = $cgs;
		   $clcgs_cartaosus->s115_c_tipo = 'P';
		   $clcgs_cartaosus->s115_i_entrada = 2;
		   $clcgs_cartaosus->incluir ( null );
		   $inc = true;
	    }
	    if (($row->definitivo != null) && ($row->definitivo != "") && (($op == 1) || ($op == 2))) {

	    	$erro = validaCNS($row->definitivo);

	    	$clcgs_cartaosus->s115_i_codigo = "";
	    	$clcgs_cartaosus->s115_c_cartaosus = $row->definitivo;
	    	$clcgs_cartaosus->s115_i_cgs = $cgs;
	    	$clcgs_cartaosus->s115_c_tipo = 'D';
	    	$clcgs_cartaosus->s115_i_entrada = 2;
	    	$clcgs_cartaosus->incluir ( null );
	    	$inc = true;
	    }
	    if ($inc == true) {
	    	if ($clcgs_cartaosus->erro_status == 0) {
		    	$erro = " Inclusao cartaosus ";
		    	$log = "[Atualiza dados]Erro durante inclusao do clcgs_cartaosus -> " . $clcgs_cartaosus->erro_msg;
		    	$log .= "\n CGS=$cgs \n cart�o D=$row->definitivo \n cart�o p=$row->provisorio; \n";
		    	log_erro ( $erro, $log, $arq1 );
		    }
	    }
	}else{
    	system ( "> $arq1" );
	    $msg="Erro ao Atualizar CGS->$cgs Campos : [nome=$clcgs_und->z01_v_nome]
	          [cep=$clcgs_und->z01_v_cep] [munic=$clcgs_und->z01_v_munic] [ender=$clcgs_und->z01_v_ender]
	          [bairro=$clcgs_und->z01_v_bairro] [sexo=$clcgs_und->z01_v_sexo] [uf=$clcgs_und->z01_v_uf] \n";
    	system ( "echo \"$msg\" >> $arq1" );
    }
}
function atualiza_cartao($arq1, $codigo, $cgs, $cartao, $tipo) {
	//echo"Atualiza cart�o!!!!";

	$erro = validaCNS($cartao);

	$clcgs_cartaosus = new cl_cgs_cartaosus ( );
	$clcgs_cartaosus->s115_c_cartaosus = $cartao;
	$clcgs_cartaosus->s115_i_cgs       = $cgs;
	$clcgs_cartaosus->s115_c_tipo      = $tipo;
	$clcgs_cartaosus->s115_i_codigo    = $codigo;
	$clcgs_cartaosus->s115_i_entrada = 2;

	if( ($codigo == null)||($codigo=="") ){
		$clcgs_cartaosus->incluir ( null );
	}else{
		$clcgs_cartaosus->alterar ( $codigo );
	}

	if ($clcgs_cartaosus->erro_status == 0) {
		$erro = " Inclusao cartaosus ";
		$log = "[Atualiza cart�o]Erro durante inclusao do clcgs_cartaosus -> " . $clcgs_cartaosus->erro_msg;
		$log .= "\n CGS=$cgs \n cart�o=$cartao \n Tipo=$tipo \n";
		log_erro ( $erro, $log, $arq1 );
	}
}
function get_log($arq1, $import, $num, $row, $clcgs_cartaosusreg) {
	$clcgs_cartaosusreg->s137_i_codigo = "";
	$clcgs_cartaosusreg->s137_i_import = $import;
	$clcgs_cartaosusreg->s137_i_situacao = $num;
	$inc = false;
	if (($row->provisorio != null) && ($row->provisorio != "")) {
		$clcgs_cartaosusreg->s137_i_cadsus = $row->provisorio;
		$clcgs_cartaosusreg->incluir ( null );
		$inc = true;
	} else {
		if (($row->definitivo != null) && ($row->definitivo != "")) {
			$clcgs_cartaosusreg->s137_i_cadsus = $row->definitivo;
			$clcgs_cartaosusreg->incluir ( null );
			$inc = true;
		}
	}
	if ($inc == true) {
		if ($clcgs_cartaosusreg->erro_status == 0) {
			$erro = " Inclusao cadsusreg ";
			$log = "Erro durante inclusao do clcgs_cartaosusreg -> " . $clcgs_cartaosusreg->erro_msg;
			log_erro ( $erro, $log, $arq1 );
		}
	}
}
function novo_cgs($arq1, $row, $clcgs_cartaosus, $clcgs, $clcgs_und) {
	//echo"Novo CGS!!!";

  $inc=false;
  $clcgs->incluir ( null );
	if ($clcgs->erro_status == 0) {
		$erro = " Inclusao CGS ";
		$log = "Erro durante inclusao do cgs -> " . $clcgs->erro_msg;
		log_erro ( $erro, $log, $arq1 );
	}
	$clcgs_und->z01_i_cgsund = $clcgs->z01_i_numcgs;
	$clcgs_und->z01_v_ident = str_replace ( "'", "",$row->ident);
	$clcgs_und->z01_v_nome = nome40 ( $row->nome );
	if ($row->nasc != null) {
		$clcgs_und->z01_d_nasc = $row->nasc;
	}
	$clcgs_und->z01_v_ender = nome40 ( $row->endereco );
	if (trim($row->numero) != "S/N") {
		$clcgs_und->z01_i_numero = $row->numero;
	} else {
		$clcgs_und->z01_i_numero = "";
	}
	$clcgs_und->z01_v_bairro        = $row->bairro==null?" ":nome40 ( $row->bairro );
	$clcgs_und->z01_v_cep           = $row->cep;
	$clcgs_und->z01_v_munic         = nome40 ( $row->cidade );
	$clcgs_und->z01_v_sexo          = $row->sexo;
	$clcgs_und->z01_c_certidaonum   = str_replace ( "'", "",$row->certidao );
	$clcgs_und->z01_c_certidaolivro = str_replace ( "'", "",$row->livro );
	$clcgs_und->z01_c_certidaofolha = str_replace ( "'", "",$row->folha );
	$clcgs_und->z01_v_cgccpf        = $row->cpf;
	$clcgs_und->z01_v_uf            = $row->uf;
	$clcgs_und->z01_d_cadast        = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
	$clcgs_und->z01_i_login         = db_getsession ( "DB_id_usuario" );
  $clcgs_und->z01_o_oid           = 'null';
	if(($clcgs_und->z01_v_bairro!="")&&($clcgs_und->z01_v_bairro!=null)
	    &&($clcgs_und->z01_v_cep!="")&&($clcgs_und->z01_v_cep!=null)
	    &&($clcgs_und->z01_v_munic!="")&&($clcgs_und->z01_v_munic!=null)
	    &&($clcgs_und->z01_v_ender!="")&&($clcgs_und->z01_v_ender!=null)
	    &&($clcgs_und->z01_v_nome!="")&&($clcgs_und->z01_v_nome!=null)
	    &&($clcgs_und->z01_v_sexo!="")&&($clcgs_und->z01_v_sexo!=null)
	    &&($clcgs_und->z01_v_uf!="")&&($clcgs_und->z01_v_uf!=null)
	){
	   $clcgs_und->incluir ( $clcgs->z01_i_numcgs );
	   if ($clcgs_und->erro_status == 0) {
	  	  $erro = " Inclusao Novo CGS \n";
		    $log = "$erro Erro durante inclusao do cgs_und -> " . $row->nome . " \n " . $clcgs_und->erro_sql. " \n ".pg_errormessage();
		    log_erro ( $erro, $log, $arq1 );
	   }
	   if (($row->definitivo != null) && ($row->definitivo != "")) {

	   	  $erro = validaCNS($row->definitivo);

          $clcgs_cartaosus->s115_i_codigo = "";
	      $clcgs_cartaosus->s115_c_cartaosus = $row->definitivo;
	      $clcgs_cartaosus->s115_i_cgs = $clcgs->z01_i_numcgs;
	      $clcgs_cartaosus->s115_c_tipo = 'D';
	      $clcgs_cartaosus->s115_i_entrada = 2;
	      $clcgs_cartaosus->incluir ( null );
	      $inc = true;
	   }
	   if (($row->provisorio != null) && ($row->provisorio != "")) {

	   	   $erro = validaCNS($row->provisorio);

		   $clcgs_cartaosus->s115_i_codigo = "";
		   $clcgs_cartaosus->s115_c_cartaosus = $row->provisorio;
		   $clcgs_cartaosus->s115_i_cgs = $clcgs->z01_i_numcgs;
		   $clcgs_cartaosus->s115_c_tipo = 'P';
		   $clcgs_cartaosus->s115_i_entrada = 2;
		   $clcgs_cartaosus->incluir ( null );
		   $inc = true;
	   }
    }else{
    	system ( "> $arq1" );
	    $msg="Erro ao incluir CGS->$clcgs->z01_i_numcgs Campos : [nome=$clcgs_und->z01_v_nome]
	          [cep=$clcgs_und->z01_v_cep] [munic=$clcgs_und->z01_v_munic] [ender=$clcgs_und->z01_v_ender]
	          [bairro=$clcgs_und->z01_v_bairro] [sexo=$clcgs_und->z01_v_sexo] [uf=$clcgs_und->z01_v_uf] \n";
    	system ( "echo \"$msg\" >> $arq1" );
    }
	if ($inc == true) {
		if ($clcgs_cartaosus->erro_status == 0) {
			$erro = " Inclusao cadsus ";
			$log = "[Novo CGS]Erro durante inclusao do clcgs_cartaosus -> " . $clcgs_cartaosus->erro_msg;
            $log .= "\n CGS=$cgs \n cart�o D=$row->definitivo \n cart�o p=$row->provisorio; \n";
			log_erro ( $erro, $log, $arq1 );
		}
	}
}
function cruza_dados($row, $cgs, $conn) {
	//O registro n�o ser� atualizado at� que se prove nescesario
	$atualiza = false;

	//seleciona o CGS
	$sql = "select z01_v_nome,
	             z01_d_nasc,
	             z01_v_ender,
	             z01_i_numero,
	             z01_v_bairro,
	             z01_v_cep,
	             z01_v_munic,
	             z01_v_sexo,
	             z01_c_certidaonum,
	             z01_c_certidaolivro,
	             z01_c_certidaofolha,
	             z01_v_ident,
	             z01_v_cgccpf,
	             z01_v_uf
	      from cgs_und where z01_i_cgsund=$cgs";
	if (($cgs != "") && ($cgs != null)) {
		$result = db_query ($conn, $sql );
		if (pg_num_rows ( $result ) > 0 && $row->nome != null ) {
			//percorre todos os dados verificando
			$nome = nome40 ( $row->nome );
			$ende = nome40 ( $row->endereco );
			$bair = nome40 ( $row->bairro );
			$muni = nome40 ( $row->cidade );

      $objCGS = db_utils::fieldsMemory($result,0);
/*
			echo "<br> 1 -". $atualiza = ( $objCGS->z01_v_nome          != $nome )?true:$atualiza;
			echo "<br> 2 -". $atualiza = ( $objCGS->z01_d_nasc          != substr($row->nasc,0,10) && $row->nasc != null)?true:$atualiza;
	    echo "<br> 3 -". $atualiza = ( $objCGS->z01_v_ender         != $ende && $row->endereco != 'S/N' && $row->endereco != null )?true:$atualiza;
		  echo "<br> 4 -". $atualiza = ( $objCGS->z01_i_numero        != (int)$row->numero && $row->numero != null && $row->numero != 'S/N')?true:$atualiza;
		  echo "<br> 5 -". $atualiza = ( trim($objCGS->z01_v_bairro)  != trim($bair) && $bair != null )?true:$atualiza;
 		  echo "<br> 5 - $atualiza = ( $objCGS->z01_v_bairro        != $bair )?true:$atualiza; ";
		  echo "<br> 6 -". $atualiza = ( $objCGS->z01_v_cep           != $row->cep && $row->cep != null )?true:$atualiza;
		  echo "<br> 7 -". $atualiza = ( $objCGS->z01_v_munic         != $muni &&  $muni != null )?true:$atualiza;
		  echo "<br> 8 -". $atualiza = ( $objCGS->z01_v_sexo          != $row->sexo && $row->sexo != null )?true:$atualiza;
		  echo "<br> 9 -". $atualiza = ( $objCGS->z01_c_certidaonum   != str_replace ( "'", "",$row->certidao ) && $row->certidao != null )?true:$atualiza;
		  echo "<br> 0 -". $atualiza = ( $objCGS->z01_c_certidaolivro != str_replace ( "'", "",$row->livro ) && $row->livro != null )?true:$atualiza;
		  echo "<br>10 -". $atualiza = ( $objCGS->z01_c_certidaofolha != str_replace ( "'", "",$row->folha ) && $row->folha != null )?true:$atualiza;
		  echo "<br>11 -". $atualiza = ( $objCGS->z01_v_ident         != str_replace ( "'", "",$row->ident ) && $row->ident != null )?true:$atualiza;
		  echo "<br>12 -". $atualiza = ( $objCGS->z01_v_cgccpf        != $row->cpf && $row->cpf != null )?true:$atualiza;
		  echo "<br>13 -". $atualiza = ( $objCGS->z01_v_uf            != $row->uf && $row->uf != null )?true:$atualiza;
*/
		 	$atualiza = ( trim($objCGS->z01_v_nome)          != trim($nome) )?true:$atualiza;
			$atualiza = ( $objCGS->z01_d_nasc          != substr($row->nasc,0,10) && $row->nasc != null)?true:$atualiza;
	    $atualiza = ( trim($objCGS->z01_v_ender)         != trim($ende) && $row->endereco != "S/N" && $row->endereco != null )?true:$atualiza;
		  $atualiza = ( $objCGS->z01_i_numero        != (int)$row->numero && $row->numero != null && $row->numero != "S/N")?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_bairro)        != trim($bair) && $bair != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_cep)           != trim($row->cep) && $row->cep != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_munic)         != trim($muni) && $muni != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_sexo)          != trim($row->sexo) && $row->sexo != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_c_certidaonum)   != str_replace ( "'", "",trim($row->certidao) ) && $row->certidao != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_c_certidaolivro) != str_replace ( "'", "",trim($row->livro) ) && $row->livro != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_c_certidaofolha) != str_replace ( "'", "",trim($row->folha) ) && $row->folha != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_ident)         != str_replace ( "'", "",trim($row->ident) ) && $row->ident != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_cgccpf)        != trim($row->cpf) && $row->cpf != null )?true:$atualiza;
		  $atualiza = ( trim($objCGS->z01_v_uf)            != trim($row->uf) && $row->uf != null )?true:$atualiza;

			/*
			for($x = 0; $x < 14; $x ++) {
				if (($row [$x + 2] != "") && ($row [$x + 2] != null)) {
					$dado = pg_result ( $result, 0, $x );
					//tratamento de dados
					if ($x == 0) {
						$row [$x + 2] = nome40 ( $row [$x + 2] );
					}
					if (($x == 2) || ($x == 4)) {
						$row [$x + 2] = str_replace ( "'", "", substr ( $row [$x + 2], 0, 39 ) );
					}
					//discartando endere�o sem numero
					if ($x == 3) {
						if (($row [$x + 2] != $dado) && ($row [$x + 2] != "S/N")) {
							$atualiza = true;
						}
					} else {
						//Comparando
						if ($row [$x + 2] != $dado) {
							$atualiza = true;
						}
					}
				}
			}
			*/
		}
	}
	//retorna veredito
	//O registro deve ser atualizado? sim(true) n�o(false)
	return $atualiza;
}

function sql_query_ext($oid = null, $campos = "sau_config.oid,*", $ordem = null, $dbwhere = "") {
	$sql = "select ";
	if ($campos != "*") {
		$campos_sql = split ( "#", $campos );
		$virgula = "";
		for($i = 0; $i < sizeof ( $campos_sql ); $i ++) {
			$sql .= $virgula . $campos_sql [$i];
			$virgula = ",";
		}
	} else {
		$sql .= $campos;
	}
	$sql .= " from sau_config ";
	$sql .= "      left join sau_modalidade   on sau_modalidade.sd82_i_codigo = sau_config.s103_i_modalidade ";
	$sql2 = "";
	if ($dbwhere == "") {
		if ($oid != "" && $oid != null) {
			$sql2 = " where sau_config.oid = '$oid'";
		}
	} else if ($dbwhere != "") {
		$sql2 = " where $dbwhere";
	}
	$sql .= $sql2;
	if ($ordem != null) {
		$sql .= " order by ";
		$campos_sql = split ( "#", $ordem );
		$virgula = "";
		for($i = 0; $i < sizeof ( $campos_sql ); $i ++) {
			$sql .= $virgula . $campos_sql [$i];
			$virgula = ",";
		}
	}
	return $sql;
}
/*
 * Abrvia nome > 40
 */
function nome40($nome) {
	$p = 2;
	$nome = $nome==null?"":str_replace ( "'", "", $nome );
  $nome = $nome==null?"":str_replace ( "_", "", $nome );
	while ( strlen ( $nome ) > 40 ) {
		$mtz_nome = explode ( " ", $nome );
		$nome40 = $nome;
		$nome = "";
		$mtz_nome [count ( $mtz_nome ) - $p] = substr ( $mtz_nome [count ( $mtz_nome ) - $p], 0, 1 );
		for($x = 0; $x < count ( $mtz_nome ); $x ++) {
			if ($mtz_nome [$x] != "") {
				$nome .= $mtz_nome [$x] . " ";
			}
		}
		$nome = trim ( $nome );

		$p = $p + 1;
		if ($p > count ( $mtz_nome )) {
			$nome = substr ( $nome, 0, 40 );
		}
	}
	return $nome;
}
function log_erro($erro, $log, $arq) {
	echo '<div align="center"><p><b> *** ERRO DURANTE O PROCESSO *** </b><br>';
	echo "ERRO [$erro] <br> <b>ARQUIVO CONTENDO LOG:</b> [$arq]";
	echo '<br> <fieldset style="width: 40%;"><legend>LOG</legend>'.str_replace("\\n", "<br>", $log)."</fieldset></div>";
	system ( "> $arq" );
	system ( "echo \"$log\" >> $arq" );
	db_query ( "rollback" );
	exit ();
}
function log_erro_versao($versao) {
    db_msgbox ("Caro usu�rio sua vers�o do Cadsus n�o � compativel entre em contato com o administrador do sistema (vers�o:".$versao.")");
    exit ();
}
function convert_obj_interbase($row){

     $obj->definitivo = @$row->DEFINITIVO;
     $obj->provisorio = @$row->PROVISORIO;
     $obj->nome       = @$row->NOME;
     $obj->nasc       = @$row->NASC;
     $obj->endereco   = @$row->ENDERECO;
     $obj->numero     = @$row->NUMERO;
     $obj->bairro     = @$row->BAIRRO;
     $obj->cep        = @$row->CEP;
     $obj->cidade     = @$row->CIDADE;
     $obj->sexo       = @$row->SEXO;
     $obj->certidao   = @$row->CERTIDAO;
     $obj->livro      = @$row->LIVRO;
     $obj->folha      = @$row->FOLHA;
     $obj->ident      = @$row->IDENT;
     $obj->cpf        = @$row->CPF;
     $obj->uf         = @$row->UF;
     return $obj;
}
function jogar_erro($msg){
	throw new Exception($msg);
}


function validaCNS($cns) {

	$lErro = false;
	if ((strlen(trim($cns))) != 15) {
		$lErro = true;
	}

	$iInicioNumero = substr(trim($cns),0,1);
	if ($iInicioNumero == 1 || $iInicioNumero == 2) {
	  $pis = substr($cns,0,11);
	  $soma = (((substr($pis, 0,1)) * 15) +
	  		   ((substr($pis, 1,1)) * 14) +
	  		   ((substr($pis, 2,1)) * 13) +
	  		   ((substr($pis, 3,1)) * 12) +
	  		   ((substr($pis, 4,1)) * 11) +
	  		   ((substr($pis, 5,1)) * 10) +
	  		   ((substr($pis, 6,1)) * 9) +
	  		   ((substr($pis, 7,1)) * 8) +
	  		   ((substr($pis, 8,1)) * 7) +
	  		   ((substr($pis, 9,1)) * 6) +
	  		   ((substr($pis, 10,1)) * 5));
	  $resto = fmod($soma, 11);
	  $dv = 11  - $resto;
	  if ($dv == 11) {
	  	$dv = 0;
	  }
	  if ($dv == 10) {
	  	$soma = ((((substr($pis, 0,1)) * 15) +
	  			((substr($pis, 1,1)) * 14) +
	  			((substr($pis, 2,1)) * 13) +
	  			((substr($pis, 3,1)) * 12) +
	  			((substr($pis, 4,1)) * 11) +
	  			((substr($pis, 5,1)) * 10) +
	  			((substr($pis, 6,1)) * 9) +
	  			((substr($pis, 7,1)) * 8) +
	  			((substr($pis, 8,1)) * 7) +
	  			((substr($pis, 9,1)) * 6) +
	  			((substr($pis, 10,1)) * 5)) + 2);
	  	$resto = fmod($soma, 11);
	  	$dv = 11  - $resto;
	  	$resultado = $pis."001".$dv;
	  } else {
	  	$resultado = $pis."000".$dv;
	  }

	  if ($cns != $resultado){
	  	$lErro = true;
	  }

	} else {

		$soma = (((substr($cns,0,1)) * 15) +
				((substr($cns,1,1)) * 14) +
				((substr($cns,2,1)) * 13) +
				((substr($cns,3,1)) * 12) +
				((substr($cns,4,1)) * 11) +
				((substr($cns,5,1)) * 10) +
				((substr($cns,6,1)) * 9) +
				((substr($cns,7,1)) * 8) +
				((substr($cns,8,1)) * 7) +
				((substr($cns,9,1)) * 6) +
				((substr($cns,10,1)) * 5) +
				((substr($cns,11,1)) * 4) +
				((substr($cns,12,1)) * 3) +
				((substr($cns,13,1)) * 2) +
				((substr($cns,14,1)) * 1));
		$resto = fmod($soma,11);
		if ($resto != 0) {
			$lErro = true;
		}

	}

	if ($lErro == true) {

	  $erro = " Numero do Cart�o Sus Inv�lido! ";
	  $log  = " Erro durante manuten��o dos dados do cgs - Numero do Cart�o Sus Inv�lido!";
	  $log .= "\n CGS=$cgs \n cart�o D=$row->definitivo \n cart�o p=$row->provisorio; \n";
	  log_erro ( $erro, $log, $arq1 );

	  return $erro;
	}

}
?>