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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE procjurjudicialadvog
class cl_procjurjudicialadvog { 
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
   var $v65_sequencial = 0; 
   var $v65_advog = 0; 
   var $v65_procjurjudicial = 0; 
   var $v65_principal = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v65_sequencial = int4 = Sequencial 
                 v65_advog = int4 = CGM do advogado 
                 v65_procjurjudicial = int4 = Processo Judicial 
                 v65_principal = bool = Principal 
                 ";
   //funcao construtor da classe 
   function cl_procjurjudicialadvog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procjurjudicialadvog"); 
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
       $this->v65_sequencial = ($this->v65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v65_sequencial"]:$this->v65_sequencial);
       $this->v65_advog = ($this->v65_advog == ""?@$GLOBALS["HTTP_POST_VARS"]["v65_advog"]:$this->v65_advog);
       $this->v65_procjurjudicial = ($this->v65_procjurjudicial == ""?@$GLOBALS["HTTP_POST_VARS"]["v65_procjurjudicial"]:$this->v65_procjurjudicial);
       $this->v65_principal = ($this->v65_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["v65_principal"]:$this->v65_principal);
     }else{
       $this->v65_sequencial = ($this->v65_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v65_sequencial"]:$this->v65_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v65_sequencial){ 
      $this->atualizacampos();
     if($this->v65_advog == null ){ 
       $this->erro_sql = " Campo CGM do advogado nao Informado.";
       $this->erro_campo = "v65_advog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v65_procjurjudicial == null ){ 
       $this->erro_sql = " Campo Processo Judicial nao Informado.";
       $this->erro_campo = "v65_procjurjudicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v65_principal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "v65_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v65_sequencial == "" || $v65_sequencial == null ){
       $result = db_query("select nextval('procjurjudicialadvog_v65_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procjurjudicialadvog_v65_sequencial_seq do campo: v65_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v65_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procjurjudicialadvog_v65_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v65_sequencial)){
         $this->erro_sql = " Campo v65_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v65_sequencial = $v65_sequencial; 
       }
     }
     if(($this->v65_sequencial == null) || ($this->v65_sequencial == "") ){ 
       $this->erro_sql = " Campo v65_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procjurjudicialadvog(
                                       v65_sequencial 
                                      ,v65_advog 
                                      ,v65_procjurjudicial 
                                      ,v65_principal 
                       )
                values (
                                $this->v65_sequencial 
                               ,$this->v65_advog 
                               ,$this->v65_procjurjudicial 
                               ,'$this->v65_principal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Advogado do processo judicial ($this->v65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Advogado do processo judicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Advogado do processo judicial ($this->v65_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v65_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v65_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12686,'$this->v65_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2216,12686,'','".AddSlashes(pg_result($resaco,0,'v65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2216,12689,'','".AddSlashes(pg_result($resaco,0,'v65_advog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2216,12687,'','".AddSlashes(pg_result($resaco,0,'v65_procjurjudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2216,12688,'','".AddSlashes(pg_result($resaco,0,'v65_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v65_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update procjurjudicialadvog set ";
     $virgula = "";
     if(trim($this->v65_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v65_sequencial"])){ 
       $sql  .= $virgula." v65_sequencial = $this->v65_sequencial ";
       $virgula = ",";
       if(trim($this->v65_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v65_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v65_advog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v65_advog"])){ 
       $sql  .= $virgula." v65_advog = $this->v65_advog ";
       $virgula = ",";
       if(trim($this->v65_advog) == null ){ 
         $this->erro_sql = " Campo CGM do advogado nao Informado.";
         $this->erro_campo = "v65_advog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v65_procjurjudicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v65_procjurjudicial"])){ 
       $sql  .= $virgula." v65_procjurjudicial = $this->v65_procjurjudicial ";
       $virgula = ",";
       if(trim($this->v65_procjurjudicial) == null ){ 
         $this->erro_sql = " Campo Processo Judicial nao Informado.";
         $this->erro_campo = "v65_procjurjudicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v65_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v65_principal"])){ 
       $sql  .= $virgula." v65_principal = '$this->v65_principal' ";
       $virgula = ",";
       if(trim($this->v65_principal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "v65_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v65_sequencial!=null){
       $sql .= " v65_sequencial = $this->v65_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v65_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12686,'$this->v65_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v65_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2216,12686,'".AddSlashes(pg_result($resaco,$conresaco,'v65_sequencial'))."','$this->v65_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v65_advog"]))
           $resac = db_query("insert into db_acount values($acount,2216,12689,'".AddSlashes(pg_result($resaco,$conresaco,'v65_advog'))."','$this->v65_advog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v65_procjurjudicial"]))
           $resac = db_query("insert into db_acount values($acount,2216,12687,'".AddSlashes(pg_result($resaco,$conresaco,'v65_procjurjudicial'))."','$this->v65_procjurjudicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v65_principal"]))
           $resac = db_query("insert into db_acount values($acount,2216,12688,'".AddSlashes(pg_result($resaco,$conresaco,'v65_principal'))."','$this->v65_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Advogado do processo judicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Advogado do processo judicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v65_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v65_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12686,'$v65_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2216,12686,'','".AddSlashes(pg_result($resaco,$iresaco,'v65_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2216,12689,'','".AddSlashes(pg_result($resaco,$iresaco,'v65_advog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2216,12687,'','".AddSlashes(pg_result($resaco,$iresaco,'v65_procjurjudicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2216,12688,'','".AddSlashes(pg_result($resaco,$iresaco,'v65_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procjurjudicialadvog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v65_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v65_sequencial = $v65_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Advogado do processo judicial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v65_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Advogado do processo judicial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v65_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v65_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procjurjudicialadvog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procjurjudicialadvog ";
     $sql .= "      inner join advog  on  advog.v57_numcgm = procjurjudicialadvog.v65_advog";
     $sql .= "      inner join procjurjudicial  on  procjurjudicial.v63_sequencial = procjurjudicialadvog.v65_procjurjudicial";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = advog.v57_numcgm";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = procjurjudicial.v63_localiza";
     $sql .= "      inner join procjur  as a on   a.v62_sequencial = procjurjudicial.v63_procjur";
     $sql2 = "";
     if($dbwhere==""){
       if($v65_sequencial!=null ){
         $sql2 .= " where procjurjudicialadvog.v65_sequencial = $v65_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $v65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procjurjudicialadvog ";
     $sql2 = "";
     if($dbwhere==""){
       if($v65_sequencial!=null ){
         $sql2 .= " where procjurjudicialadvog.v65_sequencial = $v65_sequencial "; 
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
  
  
   function sql_query_susp ( $v65_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procjurjudicialadvog ";
     $sql .= "      inner join advog  			on advog.v57_numcgm 			  = procjurjudicialadvog.v65_advog			 ";
     $sql .= "      inner join procjurjudicial  on procjurjudicial.v63_sequencial = procjurjudicialadvog.v65_procjurjudicial ";
     $sql .= "      inner join cgm  			on cgm.z01_numcgm 				  = advog.v57_numcgm						 ";
     $sql .= "      inner join localiza  		on localiza.v54_codlocal 		  = procjurjudicial.v63_localiza 			 ";
     $sql .= "      inner join procjur 	 		on procjur.v62_sequencial 		  = procjurjudicial.v63_procjur  			 ";
     $sql .= "      inner join suspensao   		on suspensao.ar18_procjur	 	  = procjur.v62_sequencial		 			 ";
     $sql2 = "";
     if($dbwhere==""){
       if($v65_sequencial!=null ){
         $sql2 .= " where procjurjudicialadvog.v65_sequencial = $v65_sequencial "; 
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