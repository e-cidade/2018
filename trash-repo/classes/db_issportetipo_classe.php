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
//CLASSE DA ENTIDADE issportetipo
class cl_issportetipo { 
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
   var $q41_codigo = 0; 
   var $q41_codporte = 0; 
   var $q41_codclasse = 0; 
   var $q41_codtipcalc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q41_codigo = int8 = Codigo 
                 q41_codporte = int8 = Cod. Porte 
                 q41_codclasse = int4 = codigo da classe 
                 q41_codtipcalc = int4 = codigo do tipo de calculo 
                 ";
   //funcao construtor da classe 
   function cl_issportetipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issportetipo"); 
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
       $this->q41_codigo = ($this->q41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q41_codigo"]:$this->q41_codigo);
       $this->q41_codporte = ($this->q41_codporte == ""?@$GLOBALS["HTTP_POST_VARS"]["q41_codporte"]:$this->q41_codporte);
       $this->q41_codclasse = ($this->q41_codclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["q41_codclasse"]:$this->q41_codclasse);
       $this->q41_codtipcalc = ($this->q41_codtipcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q41_codtipcalc"]:$this->q41_codtipcalc);
     }else{
       $this->q41_codigo = ($this->q41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q41_codigo"]:$this->q41_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q41_codigo){ 
      $this->atualizacampos();
     if($this->q41_codporte == null ){ 
       $this->erro_sql = " Campo Cod. Porte nao Informado.";
       $this->erro_campo = "q41_codporte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q41_codclasse == null ){ 
       $this->erro_sql = " Campo codigo da classe nao Informado.";
       $this->erro_campo = "q41_codclasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q41_codtipcalc == null ){ 
       $this->erro_sql = " Campo codigo do tipo de calculo nao Informado.";
       $this->erro_campo = "q41_codtipcalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q41_codigo == "" || $q41_codigo == null ){
       $result = db_query("select nextval('issportetipo_q41_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issportetipo_q41_codigo_seq do campo: q41_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q41_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issportetipo_q41_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q41_codigo)){
         $this->erro_sql = " Campo q41_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q41_codigo = $q41_codigo; 
       }
     }
     if(($this->q41_codigo == null) || ($this->q41_codigo == "") ){ 
       $this->erro_sql = " Campo q41_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issportetipo(
                                       q41_codigo 
                                      ,q41_codporte 
                                      ,q41_codclasse 
                                      ,q41_codtipcalc 
                       )
                values (
                                $this->q41_codigo 
                               ,$this->q41_codporte 
                               ,$this->q41_codclasse 
                               ,$this->q41_codtipcalc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de calculo por porte e classe ($this->q41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de calculo por porte e classe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de calculo por porte e classe ($this->q41_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q41_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q41_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6437,'$this->q41_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1048,6437,'','".AddSlashes(pg_result($resaco,0,'q41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1048,6393,'','".AddSlashes(pg_result($resaco,0,'q41_codporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1048,6394,'','".AddSlashes(pg_result($resaco,0,'q41_codclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1048,6395,'','".AddSlashes(pg_result($resaco,0,'q41_codtipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q41_codigo=null) { 
      $this->atualizacampos();
     $sql = " update issportetipo set ";
     $virgula = "";
     if(trim($this->q41_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q41_codigo"])){ 
       $sql  .= $virgula." q41_codigo = $this->q41_codigo ";
       $virgula = ",";
       if(trim($this->q41_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "q41_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q41_codporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q41_codporte"])){ 
       $sql  .= $virgula." q41_codporte = $this->q41_codporte ";
       $virgula = ",";
       if(trim($this->q41_codporte) == null ){ 
         $this->erro_sql = " Campo Cod. Porte nao Informado.";
         $this->erro_campo = "q41_codporte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q41_codclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q41_codclasse"])){ 
       $sql  .= $virgula." q41_codclasse = $this->q41_codclasse ";
       $virgula = ",";
       if(trim($this->q41_codclasse) == null ){ 
         $this->erro_sql = " Campo codigo da classe nao Informado.";
         $this->erro_campo = "q41_codclasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q41_codtipcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q41_codtipcalc"])){ 
       $sql  .= $virgula." q41_codtipcalc = $this->q41_codtipcalc ";
       $virgula = ",";
       if(trim($this->q41_codtipcalc) == null ){ 
         $this->erro_sql = " Campo codigo do tipo de calculo nao Informado.";
         $this->erro_campo = "q41_codtipcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q41_codigo!=null){
       $sql .= " q41_codigo = $this->q41_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q41_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6437,'$this->q41_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q41_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1048,6437,'".AddSlashes(pg_result($resaco,$conresaco,'q41_codigo'))."','$this->q41_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q41_codporte"]))
           $resac = db_query("insert into db_acount values($acount,1048,6393,'".AddSlashes(pg_result($resaco,$conresaco,'q41_codporte'))."','$this->q41_codporte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q41_codclasse"]))
           $resac = db_query("insert into db_acount values($acount,1048,6394,'".AddSlashes(pg_result($resaco,$conresaco,'q41_codclasse'))."','$this->q41_codclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q41_codtipcalc"]))
           $resac = db_query("insert into db_acount values($acount,1048,6395,'".AddSlashes(pg_result($resaco,$conresaco,'q41_codtipcalc'))."','$this->q41_codtipcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de calculo por porte e classe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de calculo por porte e classe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q41_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q41_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6437,'$q41_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1048,6437,'','".AddSlashes(pg_result($resaco,$iresaco,'q41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1048,6393,'','".AddSlashes(pg_result($resaco,$iresaco,'q41_codporte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1048,6394,'','".AddSlashes(pg_result($resaco,$iresaco,'q41_codclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1048,6395,'','".AddSlashes(pg_result($resaco,$iresaco,'q41_codtipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issportetipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q41_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q41_codigo = $q41_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de calculo por porte e classe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q41_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de calculo por porte e classe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q41_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q41_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:issportetipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

	function sql_query ( $q41_codigo=null,$campos="*",$ordem=null,$dbwhere="",$iAnoUsu = null){
		
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

   	if(empty($iAnoUsu)) {
   		$iAnoUsu = db_getsession('DB_anousu');
   	}

   	$sql .= " from issportetipo 																																	 ";
   	$sql .= "      inner join classe       on  classe.q12_classe      = issportetipo.q41_codclasse ";
   	$sql .= "      inner join tipcalc      on  tipcalc.q81_codigo     = issportetipo.q41_codtipcalc";
   	$sql .= "      inner join tipcalcexe   on  tipcalcexe.q83_tipcalc = tipcalc.q81_codigo         ";
   	$sql .= "                             and  tipcalcexe.q83_anousu  = {$iAnoUsu}                 ";
   	$sql .= "      inner join issporte     on  issporte.q40_codporte  = issportetipo.q41_codporte  ";
   	$sql .= "      inner join cadcalc      on  cadcalc.q85_codigo     = tipcalc.q81_cadcalc			   ";
   	$sql .= "      inner join cadvencdesc  on  cadvencdesc.q92_codigo = tipcalcexe.q83_codven			 ";
   	$sql .= "      inner join geradesc     on  geradesc.q89_codigo    = tipcalc.q81_gera					 ";
   	$sql .= "      inner join tabrec       on  tabrec.k02_codigo      = tipcalc.q81_recexe         ";
   	$sql .= "                             and  tabrec.k02_codigo      = tipcalc.q81_recpro         ";
   	$sql2 = "";
   	if($dbwhere==""){
   		if($q41_codigo!=null ){
   			$sql2 .= " where issportetipo.q41_codigo = $q41_codigo ";
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
   function sql_query_file ( $q41_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issportetipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($q41_codigo!=null ){
         $sql2 .= " where issportetipo.q41_codigo = $q41_codigo "; 
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