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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadenderbairrocadenderrua
class cl_cadenderbairrocadenderrua { 
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
   var $db87_sequencial = 0; 
   var $db87_cadenderrua = 0; 
   var $db87_cadenderbairro = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db87_sequencial = int4 = Sequencial 
                 db87_cadenderrua = int4 = Código da Rua 
                 db87_cadenderbairro = int4 = Código do Bairro 
                 ";
   //funcao construtor da classe 
   function cl_cadenderbairrocadenderrua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderbairrocadenderrua"); 
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
       $this->db87_sequencial = ($this->db87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db87_sequencial"]:$this->db87_sequencial);
       $this->db87_cadenderrua = ($this->db87_cadenderrua == ""?@$GLOBALS["HTTP_POST_VARS"]["db87_cadenderrua"]:$this->db87_cadenderrua);
       $this->db87_cadenderbairro = ($this->db87_cadenderbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["db87_cadenderbairro"]:$this->db87_cadenderbairro);
     }else{
       $this->db87_sequencial = ($this->db87_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db87_sequencial"]:$this->db87_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db87_sequencial){ 
      $this->atualizacampos();
     if($this->db87_cadenderrua == null ){ 
       $this->erro_sql = " Campo Código da Rua nao Informado.";
       $this->erro_campo = "db87_cadenderrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db87_cadenderbairro == null ){ 
       $this->erro_sql = " Campo Código do Bairro nao Informado.";
       $this->erro_campo = "db87_cadenderbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db87_sequencial == "" || $db87_sequencial == null ){
       $result = db_query("select nextval('cadenderbairrocadenderrua_db87_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderbairrocadenderrua_db87_sequencial_seq do campo: db87_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db87_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderbairrocadenderrua_db87_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db87_sequencial)){
         $this->erro_sql = " Campo db87_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db87_sequencial = $db87_sequencial; 
       }
     }
     if(($this->db87_sequencial == null) || ($this->db87_sequencial == "") ){ 
       $this->erro_sql = " Campo db87_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderbairrocadenderrua(
                                       db87_sequencial 
                                      ,db87_cadenderrua 
                                      ,db87_cadenderbairro 
                       )
                values (
                                $this->db87_sequencial 
                               ,$this->db87_cadenderrua 
                               ,$this->db87_cadenderbairro 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bairro Rua ($this->db87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bairro Rua já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bairro Rua ($this->db87_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db87_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db87_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16577,'$this->db87_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2910,16577,'','".AddSlashes(pg_result($resaco,0,'db87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2910,16578,'','".AddSlashes(pg_result($resaco,0,'db87_cadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2910,16579,'','".AddSlashes(pg_result($resaco,0,'db87_cadenderbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db87_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderbairrocadenderrua set ";
     $virgula = "";
     if(trim($this->db87_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db87_sequencial"])){ 
       $sql  .= $virgula." db87_sequencial = $this->db87_sequencial ";
       $virgula = ",";
       if(trim($this->db87_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db87_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db87_cadenderrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db87_cadenderrua"])){ 
       $sql  .= $virgula." db87_cadenderrua = $this->db87_cadenderrua ";
       $virgula = ",";
       if(trim($this->db87_cadenderrua) == null ){ 
         $this->erro_sql = " Campo Código da Rua nao Informado.";
         $this->erro_campo = "db87_cadenderrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db87_cadenderbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db87_cadenderbairro"])){ 
       $sql  .= $virgula." db87_cadenderbairro = $this->db87_cadenderbairro ";
       $virgula = ",";
       if(trim($this->db87_cadenderbairro) == null ){ 
         $this->erro_sql = " Campo Código do Bairro nao Informado.";
         $this->erro_campo = "db87_cadenderbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db87_sequencial!=null){
       $sql .= " db87_sequencial = $this->db87_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db87_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16577,'$this->db87_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db87_sequencial"]) || $this->db87_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2910,16577,'".AddSlashes(pg_result($resaco,$conresaco,'db87_sequencial'))."','$this->db87_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db87_cadenderrua"]) || $this->db87_cadenderrua != "")
           $resac = db_query("insert into db_acount values($acount,2910,16578,'".AddSlashes(pg_result($resaco,$conresaco,'db87_cadenderrua'))."','$this->db87_cadenderrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db87_cadenderbairro"]) || $this->db87_cadenderbairro != "")
           $resac = db_query("insert into db_acount values($acount,2910,16579,'".AddSlashes(pg_result($resaco,$conresaco,'db87_cadenderbairro'))."','$this->db87_cadenderbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bairro Rua nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bairro Rua nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db87_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db87_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16577,'$db87_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2910,16577,'','".AddSlashes(pg_result($resaco,$iresaco,'db87_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2910,16578,'','".AddSlashes(pg_result($resaco,$iresaco,'db87_cadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2910,16579,'','".AddSlashes(pg_result($resaco,$iresaco,'db87_cadenderbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderbairrocadenderrua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db87_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db87_sequencial = $db87_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bairro Rua nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db87_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bairro Rua nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db87_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db87_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderbairrocadenderrua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderbairrocadenderrua ";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua  on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadenderbairro.db73_cadendermunicipio";
     $sql .= "      inner join cadendermunicipio  as a on   a.db72_sequencial = cadenderrua.db74_cadendermunicipio";
     $sql2 = "";
     if($dbwhere==""){
       if($db87_sequencial!=null ){
         $sql2 .= " where cadenderbairrocadenderrua.db87_sequencial = $db87_sequencial "; 
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
   function sql_query_file ( $db87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderbairrocadenderrua ";
     $sql2 = "";
     if($dbwhere==""){
       if($db87_sequencial!=null ){
         $sql2 .= " where cadenderbairrocadenderrua.db87_sequencial = $db87_sequencial "; 
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
  
  function sql_query_completa ( $db87_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    
    $sql = "select ";
    if ($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from cadenderbairrocadenderrua ";
    $sql .= "      inner join cadenderbairro    on cadenderbairro.db73_sequencial    = cadenderbairrocadenderrua.db87_cadenderbairro";
    $sql .= "      inner join cadenderrua       on cadenderrua.db74_sequencial       = cadenderbairrocadenderrua.db87_cadenderrua";
    $sql .= "      inner join cadendermunicipio on cadendermunicipio.db72_sequencial = cadenderbairro.db73_cadendermunicipio";
    $sql .= "      inner join cadenderestado    on cadenderestado.db71_sequencial    = cadendermunicipio.db72_cadenderestado";
    $sql .= "      inner join cadenderpais      on cadenderpais.db70_sequencial      = cadenderestado.db71_cadenderpais";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($db87_sequencial != null ) {
        $sql2 .= " where cadenderbairrocadenderrua.db87_sequencial = $db87_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>