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

require_once("classes/db_aguabase_classe.php");
require_once("classes/db_aguaisencaorec_classe.php");
require_once("classes/db_aguaconstrcar_classe.php");
require_once("classes/db_agualeitura_classe.php");
require_once("classes/db_aguahidrotroca_classe.php");
require_once("classes/db_aguabasecorresp_classe.php");
require_once("classes/db_aguacortematmov_classe.php");
require_once("classes/db_histocorrenciamatric_classe.php");
require_once("classes/db_aguabasebaixa_classe.php");
require_once("classes/db_aguacondominiomat_classe.php");


  
/***
 *
 * ConsultaAguaBase
 *   . Classe responsavel por efetuar consultas no AguaBase e seus dependentes
 *
 */
class ConsultaAguaBase {
	var $_Matric;
	
	// Propriedades para classes do DBPortal
	var $_AguaBaseDAO;
	var $_AguaIsencaoRecDAO;
	var $_AguaConstrCarDAO;
	var $_AguaLeituraDAO;
	var $_AguaHidroTrocaDAO;
	var $_AguaBaseCorrespDAO;
	var $_AguaCorteMatMovDAO;
	var $_AguaCondominioMatDAO;

	function ConsultaAguaBase($Matric) {
		$this->SetMatric($Matric);
	}

	function SetMatric($Matric) {
		$this->_Matric = $Matric;
	}

	function GetMatric() {
		return $this->_Matric;
	}

	function GetAguaBaseDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaBaseDAO) {
			$this->_AguaBaseDAO = new cl_aguabase();
		}
		return $this->_AguaBaseDAO;
	}
	
	function GetAguaIsencaoRecDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaIsencaoRecDAO) {
			$this->_AguaIsencaoRecDAO = new cl_aguaisencaorec();
		}
		return $this->_AguaIsencaoRecDAO;
	}

	function GetAguaConstrCarDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaConstrCarDAO) {
			$this->_AguaConstrCarDAO = new cl_aguaconstrcar();
		}
		return $this->_AguaConstrCarDAO;
	}
	
	function GetAguaLeituraDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaLeituraDAO) {
			$this->_AguaLeituraDAO = new cl_agualeitura();
		}
		return $this->_AguaLeituraDAO;
	}

	function GetAguaBaseCorrespDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaBaseCorrespDAO) {
			$this->_AguaBaseCorrespDAO = new cl_aguabasecorresp();
		}
		return $this->_AguaBaseCorrespDAO;
	}
  
	function GetAguaCorteMatMovDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaCorteMatMovDAO) {
			$this->_AguaCorteMatMovDAO = new cl_aguacortematmov();
		}
		return $this->_AguaCorteMatMovDAO;
	}
	
	function GetAguaCondominioMatDAO() {
		// Singleton (instancia unica)
		if(!$this->_AguaCondominioMatDAO) {
			$this->_AguaCondominioMatDAO = new cl_aguacondominiomat();
		}
		return $this->_AguaCondominioMatDAO;
	}

	function GetAguaBaseCorrespSQL() {
		$dao = $this->GetAguaBaseCorrespDAO();

		return $dao->sql_query($this->GetMatric(), 
			"aguacorresp.x02_codcorresp ,
			 aguacorresp.x02_codbairro  ,
			 bairro.j13_descr           ,
			 aguacorresp.x02_codrua     ,
			 ruas.j14_nome              ,
			 aguacorresp.x02_numero     ,
			 aguacorresp.x02_complemento ,
			 aguacorresp.x02_rota       ,
			 aguacorresp.x02_orientacao ");
	}

	function GetAguaIsencaoRecSQL() {
		$dao = $this->GetAguaIsencaoRecDAO();

		return $dao->sql_query(null, 
			"x10_dtini, 
			 x10_dtfim, 
			 x29_descr, 
			 x26_percentual, 
			 x25_descr, 
			 x10_obs",
			 null,
			 "x10_matric=".$this->GetMatric());
	}
  
	function GetAguaCorteMatMovSQL() {
		$dao = $this->GetAguaCorteMatMovDAO();

		return $dao->sql_query(null, 
			"x40_codcorte, 
			 x42_codsituacao, 
			 x43_descr, 
			 x42_data, 
			 x42_historico, 
			 x42_leitura 
       ",
			 "x42_data desc, x42_codmov desc",
			 "x41_matric=".$this->GetMatric());
	}

	function GetAguaLeituraSQL($_limit=0) {
		
		$matric = $this->GetMatric();

		$sql = "
			select  x21_exerc,
      			  x21_mes,
			        x17_descr,
			        x21_dtleitura,
			        x21_dtinc,
			        x21_leitura,
			        x21_consumo as x19_conspadrao,
              case 
                when x21_excesso >= 0 then x21_consumo + x21_excesso
                else x21_consumo
              end as x21_consumo, 
              case 
                when x21_excesso < 0 then 0
                else x21_excesso
              end as x21_excesso,
              x21_saldo,
             CASE WHEN x21_status in (2,3) THEN '0'
               ELSE fc_agua_saldocompensado(x21_exerc, x21_mes, x04_matric) 
             END as x34_saldoutilizado,
            CASE WHEN x21_tipo = 1 THEN 'Digitação Manual' 
                WHEN x21_tipo = 2 THEN 'Exportada Coletor' 
                ELSE 'Importada Coletor'
            END as x21_tipo, 
            CASE WHEN x21_status = 1 THEN 'Ativo' 
                WHEN x21_status = 2 THEN 'Inativo' 
                ELSE 'Cancelado'
              END as x21_status,
              z01_nome as x21_numcgm,
		    			login
			from    agualeitura
			left join aguahidromatric on aguahidromatric.x04_codhidrometro = agualeitura.x21_codhidrometro
			left join aguasitleitura  on aguasitleitura.x17_codigo = agualeitura.x21_situacao
			left join agualeiturista  on agualeiturista.x16_numcgm = agualeitura.x21_numcgm
			left join cgm             on cgm.z01_numcgm            = agualeitura.x21_numcgm
			left join db_usuarios     on db_usuarios.id_usuario    = agualeitura.x21_usuario
			where x04_matric = $matric
			order by x21_exerc desc, x21_mes desc, x21_dtleitura desc, x21_codleitura desc ";

		if( $_limit > 0 ) {
			$sql .= " limit ".$_limit;
		}
		
		return $sql;
	}


	function GetAguaBaseCarSQL() {
		return "
				select	j31_codigo,
						j31_descr,
						j32_grupo,
						j32_descr,
						j32_tipo
				from 	aguabasecar
				inner join caracter
				on 		x30_codigo = j31_codigo
				inner join cargrup
				on 		j31_grupo = j32_grupo
				where	x30_matric = ".$this->GetMatric();
	}

	function GetAguaHidroMatricSQL() {
		return "
				select	x04_nrohidro,
						x04_qtddigito,
						x04_dtinst,
						x04_leitinicial,
						x15_diametro,
						x03_nomemarca,
						x28_dttroca,
						x28_obs
				from	aguahidromatric
				left outer join aguahidrotroca on x28_codhidrometro = x04_codhidrometro
				inner join aguahidromarca on x03_codmarca = x04_codmarca
				inner join aguahidrodiametro on x15_coddiametro = x04_coddiametro
				where x04_matric = ".$this->GetMatric();
	}


  function GetAguaCondominioMatricSQL() {
    $dao = $this->GetAguaCondominioMatDAO();
    
    return $dao->sql_query(null, $this->GetMatric(),
                           "x31_codcondominio, x31_matric,
                           (select proprietario from proprietario_nome where j01_matric = x31_matric limit 1) as dl_Proprietario,
                           z01_cadast,
                           (select (id_usuario ||' - ' || nome) from db_usuarios where id_usuario = z01_login limit 1) as nome");
  
  }

	// Retorna RecordSet com consulta ao Aguabase
	function RecordSetAguaBase() {
		$dao = $this->GetAguaBaseDAO();

		$sql = $dao->sql_query($this->GetMatric());
		
		return $dao->sql_record($sql);
	}

	// Retorna RecordSet com consulta ao AguaBaseCorresp
	function RecordSetAguaBaseCorresp() {
		return db_query($this->GetAguaBaseCorrespSQL());
	}

	// Retorna RecordSet com consulta ao AguaBaseCar
	function RecordSetAguaBaseCar() {
		return db_query($this->GetAguaBaseCarSQL());
	}
	
	// Retorna RecordSet com consulta ao AguaIsencaoRec
	function RecordSetAguaIsencaoRec() {
		return db_query($this->GetAguaIsencaoRecSQL());
	}

	// Retorna RecordSet com consulta ao AguaHidroMatric
	function RecordSetAguaHidroMatric() {
		return db_query($this->GetAguaHidroMatricSQL());
	}

	// Retorna RecordSet com consulta ao AguaLeitura
	function RecordSetAguaLeitura($_limit=0) {
		return db_query($this->GetAguaLeituraSQL($_limit));
	}

	// Retorna RecordSet com consulta ao AguaConstrCar
	function RecordSetAguaConstrCar() {
		$dao = $this->GetAguaConstrCarDAO();

		$sql = $dao->sql_query(null,null,"*",null,"x01_matric=".$this->GetMatric());
	
		//return $dao->sql_record($sql);
		return db_query($sql);
	}

	// Retorna RecordSet com consulta ao AguaCalc
	function RecordSetAguaCalc() {
		$sql = "
			select 	aguacalc.*,
					aguaconsumo.*,
					fc_agua_consumo(x22_exerc, x22_mes, x22_matric) as x21_consumo,
					fc_agua_excesso(x22_exerc, x22_mes, x22_matric) as x21_excesso
			from 	aguacalc 
			inner join aguaconsumo on x19_codconsumo = x22_codconsumo
			where 	x22_matric = ".$this->GetMatric() ."
			order 
			by 		x22_exerc desc, 
					x22_mes desc";

		return db_query( $sql );
	}

	// Retorna RecordSet com consulta ao AguaCalc
	function RecordSetAguaCalcVal($Calculo) {
		$sql = "
				select	*
				from	aguacalcval
				inner join aguaconsumotipo on x25_codconsumotipo = x23_codconsumotipo
				inner join tabrec on x25_receit = k02_codigo
				where 	x23_codcalc = ".$Calculo;
			
		return db_query($sql);
	}

	// Retorna RecordSet com consulta ao AguaCondominio
	function RecordSetAguaCondominio() {
		return db_query($this->GetAguaCondominioMatricSQL());
	}
	
  function sqlmatriculas_ruas($pesquisaRua=0,$numero=0,$filtrotipo='todos'){
     $order_by = "";
   
     $sql = "select distinct a.x01_matric,
                    case
                     when ac.x11_codconstr is not null then 
                      'Predial'
                    else
                     'Territorial'
                    end ,
                    cgm1.z01_nome, a.x01_numero, ac.x11_complemento, cgm2.z01_nome as Promitente
               from aguabase as a
               left join aguaconstr as ac on   ac.x11_matric  = a.x01_matric
                                         and   ac.x11_tipo    = 'P'  
              inner join cgm as cgm1      on   a.x01_numcgm   = cgm1.z01_numcgm
               left join cgm as cgm2      on   a.x01_promit   = cgm2.z01_numcgm ";
     
     if($pesquisaRua!=0){
       $sql .= " where a.x01_codrua  = $pesquisaRua ";
       if($numero!=0) {
         $sql.= " and a.x01_numero = $numero ";
         $order_by = " order by a.x01_numero, a.x01_matric ";
       }
     }
     
     if($filtrotipo!='todos') {
       if($filtrotipo == "Predial") {
         $filtrotipo = " ac.x11_codconstr is not null ";
       }else{
         $filtrotipo = " ac.x11_codconstr is null ";
       }
       $sql .= "and $filtrotipo ";
     }
     
     if($order_by != ""){
       $sql .= " $order_by ";
     }else{
       $sql .= " order by a.x01_matric, a.x01_numero ";
     }
     
     return $sql;
  }
  
  function sqlmatriculas_bairros($pesquisaBairro=0,$numero=0,$filtrotipo='todos'){
    $order_by = "";
    
    $sql  = "select aguabase.x01_matric, 
			 				      case when aguaconstr.x11_codconstr is not null then 'Predial'
			 							else 'Territorial'
			 							end,
       							cgm1.z01_nome, aguabase.x01_numero, aguaconstr.x11_complemento, cgm2.z01_nome as Promitente
       				 from aguabase
       				      inner join cgm as cgm1  on aguabase.x01_numcgm = cgm1.z01_numcgm
                    left  join cgm as cgm2  on aguabase.x01_promit = cgm2.z01_numcgm
                    inner join aguaconstr   on aguabase.x01_matric = aguaconstr.x11_matric and
                                               aguaconstr.x11_tipo = 'P'";
   
     if($pesquisaBairro!=0){
       $sql .= " where aguabase.x01_codbairro = $pesquisaBairro ";
       
     }
     $sql .= " order by aguabase.x01_matric, aguabase.x01_numero ";
     
     return $sql;
  }
  
  function getHistOcorrenciaMatric(){
    
    
    $campos = "case when histocorrencia.ar23_tipo = '1' then 'Manual' else 'Automatica' end as Tipo, ";
    $campos .= "histocorrencia.ar23_descricao, ";
    $campos .= "histocorrencia.ar23_ocorrencia, ";
    $campos .= "histocorrencia.ar23_data, ";
    $campos .= "histocorrencia.ar23_hora, ";
    $campos .= "db_usuarios.login, ";
    $campos .= "db_modulos.nome_modulo ";
    $clhistocorrenciamatric = new cl_histocorrenciamatric;
    $sSql = $clhistocorrenciamatric->sql_query("", "$campos", "histocorrencia.ar23_data", "histocorrenciamatric.ar25_matric = ".$this->_Matric." and ar23_instit = ". db_getsession("DB_instit"));
    
    return $sSql;
  }
   
  function getHistAguaBaixaImoveis(){
    
    $campos  = "aguabasebaixa.x08_data, ";
    $campos .= "aguabasebaixa.x08_obs, ";
    $campos .= "aguabasebaixa.x08_usuario, ";
    $campos .= "db_usuarios.nome ";
    $claguabasebaixa = new cl_aguabasebaixa;
    
    $sSql = $claguabasebaixa->sql_query(null, $campos, "aguabasebaixa.x08_data", "aguabasebaixa.x08_matric = ".$this->_Matric);
    return $sSql;
  } 
  
}
?>