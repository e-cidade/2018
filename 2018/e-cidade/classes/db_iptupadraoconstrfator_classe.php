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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptupadraoconstrfator
class cl_iptupadraoconstrfator { 
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
   var $j117_sequencial = 0; 
   var $j117_iptupadraoconstr = 0; 
   var $j117_fator = 0; 
   var $j117_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j117_sequencial = int4 = Sequencial 
                 j117_iptupadraoconstr = int4 = Iptu Padrão Construção 
                 j117_fator = float8 = Fator 
                 j117_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_iptupadraoconstrfator() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptupadraoconstrfator"); 
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
       $this->j117_sequencial = ($this->j117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j117_sequencial"]:$this->j117_sequencial);
       $this->j117_iptupadraoconstr = ($this->j117_iptupadraoconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j117_iptupadraoconstr"]:$this->j117_iptupadraoconstr);
       $this->j117_fator = ($this->j117_fator == ""?@$GLOBALS["HTTP_POST_VARS"]["j117_fator"]:$this->j117_fator);
       $this->j117_anousu = ($this->j117_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j117_anousu"]:$this->j117_anousu);
     }else{
       $this->j117_sequencial = ($this->j117_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j117_sequencial"]:$this->j117_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j117_sequencial){ 
      $this->atualizacampos();
     if($this->j117_iptupadraoconstr == null ){ 
       $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
       $this->erro_campo = "j117_iptupadraoconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j117_fator == null ){ 
       $this->erro_sql = " Campo Fator nao Informado.";
       $this->erro_campo = "j117_fator";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j117_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j117_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j117_sequencial == "" || $j117_sequencial == null ){
       $result = db_query("select nextval('iptupadraoconstrfator_j117_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptupadraoconstrfator_j117_sequencial_seq do campo: j117_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j117_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptupadraoconstrfator_j117_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j117_sequencial)){
         $this->erro_sql = " Campo j117_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j117_sequencial = $j117_sequencial; 
       }
     }
     if(($this->j117_sequencial == null) || ($this->j117_sequencial == "") ){ 
       $this->erro_sql = " Campo j117_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptupadraoconstrfator(
                                       j117_sequencial 
                                      ,j117_iptupadraoconstr 
                                      ,j117_fator 
                                      ,j117_anousu 
                       )
                values (
                                $this->j117_sequencial 
                               ,$this->j117_iptupadraoconstr 
                               ,$this->j117_fator 
                               ,$this->j117_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Iptu Padrão Construção Fator ($this->j117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Iptu Padrão Construção Fator já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Iptu Padrão Construção Fator ($this->j117_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j117_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j117_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15170,'$this->j117_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2672,15170,'','".AddSlashes(pg_result($resaco,0,'j117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2672,15171,'','".AddSlashes(pg_result($resaco,0,'j117_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2672,15173,'','".AddSlashes(pg_result($resaco,0,'j117_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2672,15174,'','".AddSlashes(pg_result($resaco,0,'j117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j117_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptupadraoconstrfator set ";
     $virgula = "";
     if(trim($this->j117_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j117_sequencial"])){ 
       $sql  .= $virgula." j117_sequencial = $this->j117_sequencial ";
       $virgula = ",";
       if(trim($this->j117_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j117_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j117_iptupadraoconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j117_iptupadraoconstr"])){ 
       $sql  .= $virgula." j117_iptupadraoconstr = $this->j117_iptupadraoconstr ";
       $virgula = ",";
       if(trim($this->j117_iptupadraoconstr) == null ){ 
         $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
         $this->erro_campo = "j117_iptupadraoconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j117_fator)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j117_fator"])){ 
       $sql  .= $virgula." j117_fator = $this->j117_fator ";
       $virgula = ",";
       if(trim($this->j117_fator) == null ){ 
         $this->erro_sql = " Campo Fator nao Informado.";
         $this->erro_campo = "j117_fator";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j117_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j117_anousu"])){ 
       $sql  .= $virgula." j117_anousu = $this->j117_anousu ";
       $virgula = ",";
       if(trim($this->j117_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j117_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j117_sequencial!=null){
       $sql .= " j117_sequencial = $this->j117_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j117_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15170,'$this->j117_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j117_sequencial"]) || $this->j117_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2672,15170,'".AddSlashes(pg_result($resaco,$conresaco,'j117_sequencial'))."','$this->j117_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j117_iptupadraoconstr"]) || $this->j117_iptupadraoconstr != "")
           $resac = db_query("insert into db_acount values($acount,2672,15171,'".AddSlashes(pg_result($resaco,$conresaco,'j117_iptupadraoconstr'))."','$this->j117_iptupadraoconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j117_fator"]) || $this->j117_fator != "")
           $resac = db_query("insert into db_acount values($acount,2672,15173,'".AddSlashes(pg_result($resaco,$conresaco,'j117_fator'))."','$this->j117_fator',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j117_anousu"]) || $this->j117_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2672,15174,'".AddSlashes(pg_result($resaco,$conresaco,'j117_anousu'))."','$this->j117_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iptu Padrão Construção Fator nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iptu Padrão Construção Fator nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j117_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j117_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15170,'$j117_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2672,15170,'','".AddSlashes(pg_result($resaco,$iresaco,'j117_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2672,15171,'','".AddSlashes(pg_result($resaco,$iresaco,'j117_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2672,15173,'','".AddSlashes(pg_result($resaco,$iresaco,'j117_fator'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2672,15174,'','".AddSlashes(pg_result($resaco,$iresaco,'j117_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptupadraoconstrfator
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j117_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j117_sequencial = $j117_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iptu Padrão Construção Fator nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j117_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iptu Padrão Construção Fator nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j117_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j117_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptupadraoconstrfator";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrfator ";
     $sql .= "      inner join iptupadraoconstr  on  iptupadraoconstr.j115_sequencial = iptupadraoconstrfator.j117_iptupadraoconstr";
     $sql2 = "";
     if($dbwhere==""){
       if($j117_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrfator.j117_sequencial = $j117_sequencial "; 
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
   function sql_query_file ( $j117_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrfator ";
     $sql2 = "";
     if($dbwhere==""){
       if($j117_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrfator.j117_sequencial = $j117_sequencial "; 
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