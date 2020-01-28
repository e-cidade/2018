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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadenderruacep
class cl_cadenderruacep { 
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
   var $db86_sequencial = 0; 
   var $db86_cadenderrua = 0; 
   var $db86_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db86_sequencial = int4 = Sequencial 
                 db86_cadenderrua = int4 = Código da Rua 
                 db86_cep = char(8) = Cep 
                 ";
   //funcao construtor da classe 
   function cl_cadenderruacep() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderruacep"); 
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
       $this->db86_sequencial = ($this->db86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db86_sequencial"]:$this->db86_sequencial);
       $this->db86_cadenderrua = ($this->db86_cadenderrua == ""?@$GLOBALS["HTTP_POST_VARS"]["db86_cadenderrua"]:$this->db86_cadenderrua);
       $this->db86_cep = ($this->db86_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["db86_cep"]:$this->db86_cep);
     }else{
       $this->db86_sequencial = ($this->db86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db86_sequencial"]:$this->db86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db86_sequencial){ 
      $this->atualizacampos();
     if($this->db86_cadenderrua == null ){ 
       $this->erro_sql = " Campo Código da Rua nao Informado.";
       $this->erro_campo = "db86_cadenderrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db86_cep == null ){ 
       $this->erro_sql = " Campo Cep nao Informado.";
       $this->erro_campo = "db86_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db86_sequencial == "" || $db86_sequencial == null ){
       $result = db_query("select nextval('cadenderruacep_db86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderruacep_db86_sequencial_seq do campo: db86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderruacep_db86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db86_sequencial)){
         $this->erro_sql = " Campo db86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db86_sequencial = $db86_sequencial; 
       }
     }
     if(($this->db86_sequencial == null) || ($this->db86_sequencial == "") ){ 
       $this->erro_sql = " Campo db86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderruacep(
                                       db86_sequencial 
                                      ,db86_cadenderrua 
                                      ,db86_cep 
                       )
                values (
                                $this->db86_sequencial 
                               ,$this->db86_cadenderrua 
                               ,'$this->db86_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de cep da rua ($this->db86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de cep da rua já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de cep da rua ($this->db86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16222,'$this->db86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2846,16222,'','".AddSlashes(pg_result($resaco,0,'db86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2846,16224,'','".AddSlashes(pg_result($resaco,0,'db86_cadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2846,16223,'','".AddSlashes(pg_result($resaco,0,'db86_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderruacep set ";
     $virgula = "";
     if(trim($this->db86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db86_sequencial"])){ 
       $sql  .= $virgula." db86_sequencial = $this->db86_sequencial ";
       $virgula = ",";
       if(trim($this->db86_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db86_cadenderrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db86_cadenderrua"])){ 
       $sql  .= $virgula." db86_cadenderrua = $this->db86_cadenderrua ";
       $virgula = ",";
       if(trim($this->db86_cadenderrua) == null ){ 
         $this->erro_sql = " Campo Código da Rua nao Informado.";
         $this->erro_campo = "db86_cadenderrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db86_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db86_cep"])){ 
       $sql  .= $virgula." db86_cep = '$this->db86_cep' ";
       $virgula = ",";
       if(trim($this->db86_cep) == null ){ 
         $this->erro_sql = " Campo Cep nao Informado.";
         $this->erro_campo = "db86_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db86_sequencial!=null){
       $sql .= " db86_sequencial = $this->db86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16222,'$this->db86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db86_sequencial"]) || $this->db86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2846,16222,'".AddSlashes(pg_result($resaco,$conresaco,'db86_sequencial'))."','$this->db86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db86_cadenderrua"]) || $this->db86_cadenderrua != "")
           $resac = db_query("insert into db_acount values($acount,2846,16224,'".AddSlashes(pg_result($resaco,$conresaco,'db86_cadenderrua'))."','$this->db86_cadenderrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db86_cep"]) || $this->db86_cep != "")
           $resac = db_query("insert into db_acount values($acount,2846,16223,'".AddSlashes(pg_result($resaco,$conresaco,'db86_cep'))."','$this->db86_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de cep da rua nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de cep da rua nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16222,'$db86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2846,16222,'','".AddSlashes(pg_result($resaco,$iresaco,'db86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2846,16224,'','".AddSlashes(pg_result($resaco,$iresaco,'db86_cadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2846,16223,'','".AddSlashes(pg_result($resaco,$iresaco,'db86_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderruacep
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db86_sequencial = $db86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de cep da rua nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de cep da rua nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderruacep";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderruacep ";
     $sql .= "      inner join cadenderrua  on  cadenderrua.db74_sequencial = cadenderruacep.db86_cadenderrua";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio";
     $sql2 = "";
     if($dbwhere==""){
       if($db86_sequencial!=null ){
         $sql2 .= " where cadenderruacep.db86_sequencial = $db86_sequencial "; 
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
   function sql_query_file ( $db86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderruacep ";
     $sql2 = "";
     if($dbwhere==""){
       if($db86_sequencial!=null ){
         $sql2 .= " where cadenderruacep.db86_sequencial = $db86_sequencial "; 
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
   function sql_query_cep ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from cadenderruacep  ";                                                                                                    
     $sql .= "    inner join cadenderrua          on db74_sequencial                = db86_cadenderrua                            ";                            
     $sql .= "    inner join cadendermunicipio    on db72_sequencial                = cadenderrua.db74_cadendermunicipio          ";          
     $sql .= "    inner join cadenderbairrocadenderrua on db87_cadenderrua          = cadenderrua.db74_sequencial                 ";
     $sql .= "    inner join cadenderbairro       on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro ";
     $sql .= "    inner join cadenderestado       on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado       ";
     $sql .= "    inner join cadenderpais         on cadenderpais.db70_sequencial   = cadenderestado.db71_cadenderpais            "; 
     $sql .= "    inner join cadenderruaruastipo  on cadenderruaruastipo.db85_cadenderrua = cadenderrua.db74_sequencial           "; 
     $sql .= "    inner join ruastipo             on ruastipo.j88_codigo            = cadenderruaruastipo.db85_ruastipo           ";
          
     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial "; 
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

  function sql_query_cep_sistema_externo ( $db75_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

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

     $sql .= " from cadenderruacep  ";
     $sql .= "      inner join cadenderrua               on db74_sequencial                      = db86_cadenderrua                              ";
     $sql .= "      inner join cadendermunicipio         on db72_sequencial                      = cadenderrua.db74_cadendermunicipio            ";
     $sql .= "      inner join cadenderbairrocadenderrua on db87_cadenderrua                     = cadenderrua.db74_sequencial                   ";
     $sql .= "      inner join cadenderbairro            on cadenderbairro.db73_sequencial       = cadenderbairrocadenderrua.db87_cadenderbairro ";
     $sql .= "      inner join cadenderestado            on cadenderestado.db71_sequencial       = cadendermunicipio.db72_cadenderestado         ";
     $sql .= "      inner join cadenderpais              on cadenderpais.db70_sequencial         = cadenderestado.db71_cadenderpais              ";
     $sql .= "      inner join cadenderruaruastipo       on cadenderruaruastipo.db85_cadenderrua = cadenderrua.db74_sequencial                   ";
     $sql .= "      inner join ruastipo                  on ruastipo.j88_codigo                  = cadenderruaruastipo.db85_ruastipo             ";
     $sql .= "      inner join cadendermunicipiosistema  on db125_cadendermunicipio              = db72_sequencial";
     $sql .= "      inner join cadenderestadosistema     on db300_cadenderestado                 = db71_sequencial";

     $sql2 = "";
     if($dbwhere==""){
       if($db75_sequencial!=null ){
         $sql2 .= " where cadenderlocal.db75_sequencial = $db75_sequencial ";
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