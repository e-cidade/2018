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

//MODULO: issqn
//CLASSE DA ENTIDADE ativtipo
class cl_ativtipo { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $q80_ativ = 0; 
   var $q80_tipcal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q80_ativ = int4 = codigo da atividade 
                 q80_tipcal = int4 = tipo de calculo 
                 ";
   //funcao construtor da classe 
   function cl_ativtipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ativtipo"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->q80_ativ = ($this->q80_ativ == ""?@$GLOBALS["HTTP_POST_VARS"]["q80_ativ"]:$this->q80_ativ);
       $this->q80_tipcal = ($this->q80_tipcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q80_tipcal"]:$this->q80_tipcal);
     }else{
       $this->q80_ativ = ($this->q80_ativ == ""?@$GLOBALS["HTTP_POST_VARS"]["q80_ativ"]:$this->q80_ativ);
       $this->q80_tipcal = ($this->q80_tipcal == ""?@$GLOBALS["HTTP_POST_VARS"]["q80_tipcal"]:$this->q80_tipcal);
     }
   }
   // funcao para inclusao
   function incluir ($q80_ativ,$q80_tipcal){ 
      $this->atualizacampos();
       $this->q80_ativ = $q80_ativ; 
       $this->q80_tipcal = $q80_tipcal; 
     if(($this->q80_ativ == null) || ($this->q80_ativ == "") ){ 
       $this->erro_sql = " Campo q80_ativ nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q80_tipcal == null) || ($this->q80_tipcal == "") ){ 
       $this->erro_sql = " Campo q80_tipcal nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ativtipo(
                                       q80_ativ 
                                      ,q80_tipcal 
                       )
                values (
                                $this->q80_ativ 
                               ,$this->q80_tipcal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q80_ativ."-".$this->q80_tipcal) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q80_ativ."-".$this->q80_tipcal) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q80_ativ."-".$this->q80_tipcal;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q80_ativ,$this->q80_tipcal));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,301,'$this->q80_ativ','I')");
       $resac = db_query("insert into db_acountkey values($acount,302,'$this->q80_tipcal','I')");
       $resac = db_query("insert into db_acount values($acount,50,301,'','".AddSlashes(pg_result($resaco,0,'q80_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,50,302,'','".AddSlashes(pg_result($resaco,0,'q80_tipcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q80_ativ=null,$q80_tipcal=null) { 
      $this->atualizacampos();
     $sql = " update ativtipo set ";
     $virgula = "";
     if(trim($this->q80_ativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q80_ativ"])){ 
       $sql  .= $virgula." q80_ativ = $this->q80_ativ ";
       $virgula = ",";
       if(trim($this->q80_ativ) == null ){ 
         $this->erro_sql = " Campo codigo da atividade nao Informado.";
         $this->erro_campo = "q80_ativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q80_tipcal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q80_tipcal"])){ 
       $sql  .= $virgula." q80_tipcal = $this->q80_tipcal ";
       $virgula = ",";
       if(trim($this->q80_tipcal) == null ){ 
         $this->erro_sql = " Campo tipo de calculo nao Informado.";
         $this->erro_campo = "q80_tipcal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q80_ativ!=null){
       $sql .= " q80_ativ = $this->q80_ativ";
     }
     if($q80_tipcal!=null){
       $sql .= " and  q80_tipcal = $this->q80_tipcal";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q80_ativ,$this->q80_tipcal));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,301,'$this->q80_ativ','A')");
         $resac = db_query("insert into db_acountkey values($acount,302,'$this->q80_tipcal','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q80_ativ"]))
           $resac = db_query("insert into db_acount values($acount,50,301,'".AddSlashes(pg_result($resaco,$conresaco,'q80_ativ'))."','$this->q80_ativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q80_tipcal"]))
           $resac = db_query("insert into db_acount values($acount,50,302,'".AddSlashes(pg_result($resaco,$conresaco,'q80_tipcal'))."','$this->q80_tipcal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q80_ativ."-".$this->q80_tipcal;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q80_ativ."-".$this->q80_tipcal;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q80_ativ."-".$this->q80_tipcal;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q80_ativ=null,$q80_tipcal=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q80_ativ,$q80_tipcal));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,301,'$q80_ativ','E')");
         $resac = db_query("insert into db_acountkey values($acount,302,'$q80_tipcal','E')");
         $resac = db_query("insert into db_acount values($acount,50,301,'','".AddSlashes(pg_result($resaco,$iresaco,'q80_ativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,50,302,'','".AddSlashes(pg_result($resaco,$iresaco,'q80_tipcal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ativtipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q80_ativ != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q80_ativ = $q80_ativ ";
        }
        if($q80_tipcal != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q80_tipcal = $q80_tipcal ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q80_ativ."-".$q80_tipcal;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q80_ativ."-".$q80_tipcal;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q80_ativ."-".$q80_tipcal;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:ativtipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   
  function sql_query ( $q80_ativ=null,$q80_tipcal=null,$campos="*",$ordem=null,$dbwhere="", $iAnoUsu = null){
  	
   	$sql = "select ";
   	if($campos != "*" ){
   		$campos_sql = split("#",$campos);
   		$virgula = "";
   		for($i=0;$i<sizeof($campos_sql);$i++){
   			$sql .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	}else{
   		$sql .= $campos;
   	}

   	if (empty($iAnoUsu)) {
   		$iAnoUsu = db_getsession('DB_anousu');
   	}

   	$sql .= " from ativtipo                                                                   ";
   	$sql .= "      inner join ativid       on  ativid.q03_ativ        = ativtipo.q80_ativ     ";
   	$sql .= "      inner join tipcalc      on  tipcalc.q81_codigo     = ativtipo.q80_tipcal   ";
   	$sql .= "      inner join tipcalcexe   on  tipcalcexe.q83_tipcalc = tipcalc.q81_codigo    ";
   	$sql .= "                             and  tipcalcexe.q83_anousu  = {$iAnoUsu}            ";
		$sql .= "      inner join cadcalc      on  cadcalc.q85_codigo     = tipcalc.q81_cadcalc   ";
   	$sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = tipcalcexe.q83_codven ";
   	$sql .= "      inner join geradesc     on  geradesc.q89_codigo    = tipcalc.q81_gera      ";
   	$sql2 = "";
   	
   	if($dbwhere==""){
   		if($q80_ativ!=null ){
   			$sql2 .= " where ativtipo.q80_ativ = $q80_ativ ";
   		}
   		if($q80_tipcal!=null ){
   			if($sql2!=""){
   				$sql2 .= " and ";
   			}else{
   				$sql2 .= " where ";
   			}
   			$sql2 .= " ativtipo.q80_tipcal = $q80_tipcal ";
   		}
   	}else if($dbwhere != ""){
   		$sql2 = " where $dbwhere";
   	}
   	$sql .= $sql2;
   	if($ordem != null ){
   		$sql .= " order by ";
   		$campos_sql = split("#",$ordem);
   		$virgula = "";
   		for($i=0;$i<sizeof($campos_sql);$i++){
   			$sql .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	}
   	return $sql;
   }
   function sql_query_file ( $q80_ativ=null,$q80_tipcal=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from ativtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q80_ativ!=null ){
         $sql2 .= " where ativtipo.q80_ativ = $q80_ativ "; 
       } 
       if($q80_tipcal!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " ativtipo.q80_tipcal = $q80_tipcal "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>