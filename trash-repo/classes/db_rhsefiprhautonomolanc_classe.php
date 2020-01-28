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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhsefiprhautonomolanc
class cl_rhsefiprhautonomolanc { 
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
   var $rh92_sequencial = 0; 
   var $rh92_rhsefip = 0; 
   var $rh92_rhautonomolanc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh92_sequencial = int4 = Sequencial 
                 rh92_rhsefip = int4 = Geração da SEFIP 
                 rh92_rhautonomolanc = int4 = Lançamento de Autonomo 
                 ";
   //funcao construtor da classe 
   function cl_rhsefiprhautonomolanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhsefiprhautonomolanc"); 
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
       $this->rh92_sequencial = ($this->rh92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh92_sequencial"]:$this->rh92_sequencial);
       $this->rh92_rhsefip = ($this->rh92_rhsefip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh92_rhsefip"]:$this->rh92_rhsefip);
       $this->rh92_rhautonomolanc = ($this->rh92_rhautonomolanc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh92_rhautonomolanc"]:$this->rh92_rhautonomolanc);
     }else{
       $this->rh92_sequencial = ($this->rh92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh92_sequencial"]:$this->rh92_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh92_sequencial){ 
      $this->atualizacampos();
     if($this->rh92_rhsefip == null ){ 
       $this->erro_sql = " Campo Geração da SEFIP nao Informado.";
       $this->erro_campo = "rh92_rhsefip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh92_rhautonomolanc == null ){ 
       $this->erro_sql = " Campo Lançamento de Autonomo nao Informado.";
       $this->erro_campo = "rh92_rhautonomolanc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh92_sequencial == "" || $rh92_sequencial == null ){
       $result = db_query("select nextval('rhsefiprhautonomolanc_rh92_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhsefiprhautonomolanc_rh92_sequencial_seq do campo: rh92_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh92_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhsefiprhautonomolanc_rh92_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh92_sequencial)){
         $this->erro_sql = " Campo rh92_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh92_sequencial = $rh92_sequencial; 
       }
     }
     if(($this->rh92_sequencial == null) || ($this->rh92_sequencial == "") ){ 
       $this->erro_sql = " Campo rh92_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhsefiprhautonomolanc(
                                       rh92_sequencial 
                                      ,rh92_rhsefip 
                                      ,rh92_rhautonomolanc 
                       )
                values (
                                $this->rh92_sequencial 
                               ,$this->rh92_rhsefip 
                               ,$this->rh92_rhautonomolanc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Autonomos da Geração do SEFIP ($this->rh92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Autonomos da Geração do SEFIP já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Autonomos da Geração do SEFIP ($this->rh92_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh92_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh92_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17545,'$this->rh92_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3098,17545,'','".AddSlashes(pg_result($resaco,0,'rh92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3098,17547,'','".AddSlashes(pg_result($resaco,0,'rh92_rhsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3098,17546,'','".AddSlashes(pg_result($resaco,0,'rh92_rhautonomolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh92_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhsefiprhautonomolanc set ";
     $virgula = "";
     if(trim($this->rh92_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh92_sequencial"])){ 
       $sql  .= $virgula." rh92_sequencial = $this->rh92_sequencial ";
       $virgula = ",";
       if(trim($this->rh92_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh92_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh92_rhsefip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh92_rhsefip"])){ 
       $sql  .= $virgula." rh92_rhsefip = $this->rh92_rhsefip ";
       $virgula = ",";
       if(trim($this->rh92_rhsefip) == null ){ 
         $this->erro_sql = " Campo Geração da SEFIP nao Informado.";
         $this->erro_campo = "rh92_rhsefip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh92_rhautonomolanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh92_rhautonomolanc"])){ 
       $sql  .= $virgula." rh92_rhautonomolanc = $this->rh92_rhautonomolanc ";
       $virgula = ",";
       if(trim($this->rh92_rhautonomolanc) == null ){ 
         $this->erro_sql = " Campo Lançamento de Autonomo nao Informado.";
         $this->erro_campo = "rh92_rhautonomolanc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh92_sequencial!=null){
       $sql .= " rh92_sequencial = $this->rh92_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh92_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17545,'$this->rh92_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh92_sequencial"]) || $this->rh92_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3098,17545,'".AddSlashes(pg_result($resaco,$conresaco,'rh92_sequencial'))."','$this->rh92_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh92_rhsefip"]) || $this->rh92_rhsefip != "")
           $resac = db_query("insert into db_acount values($acount,3098,17547,'".AddSlashes(pg_result($resaco,$conresaco,'rh92_rhsefip'))."','$this->rh92_rhsefip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh92_rhautonomolanc"]) || $this->rh92_rhautonomolanc != "")
           $resac = db_query("insert into db_acount values($acount,3098,17546,'".AddSlashes(pg_result($resaco,$conresaco,'rh92_rhautonomolanc'))."','$this->rh92_rhautonomolanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autonomos da Geração do SEFIP nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autonomos da Geração do SEFIP nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh92_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh92_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17545,'$rh92_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3098,17545,'','".AddSlashes(pg_result($resaco,$iresaco,'rh92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3098,17547,'','".AddSlashes(pg_result($resaco,$iresaco,'rh92_rhsefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3098,17546,'','".AddSlashes(pg_result($resaco,$iresaco,'rh92_rhautonomolanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhsefiprhautonomolanc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh92_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh92_sequencial = $rh92_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Autonomos da Geração do SEFIP nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh92_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Autonomos da Geração do SEFIP nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh92_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh92_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhsefiprhautonomolanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefiprhautonomolanc ";
     $sql .= "      inner join rhautonomolanc  on  rhautonomolanc.rh89_sequencial = rhsefiprhautonomolanc.rh92_rhautonomolanc";
     $sql .= "      inner join rhsefip  on  rhsefip.rh90_sequencial = rhsefiprhautonomolanc.rh92_rhsefip";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhautonomolanc.rh89_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhautonomolanc.rh89_instit";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = rhautonomolanc.rh89_codord";
     $sql .= "      inner join db_config  as a on   a.codigo = rhsefip.rh90_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhsefip.rh90_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh92_sequencial!=null ){
         $sql2 .= " where rhsefiprhautonomolanc.rh92_sequencial = $rh92_sequencial "; 
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
   function sql_query_file ( $rh92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhsefiprhautonomolanc ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh92_sequencial!=null ){
         $sql2 .= " where rhsefiprhautonomolanc.rh92_sequencial = $rh92_sequencial "; 
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