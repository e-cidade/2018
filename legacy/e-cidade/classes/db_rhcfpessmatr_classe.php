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
//CLASSE DA ENTIDADE rhcfpessmatr
class cl_rhcfpessmatr { 
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
   var $rh13_sequencia = 0; 
   var $rh13_instit = 0; 
   var $rh13_matricula = 0; 
   var $rh13_unificada = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh13_sequencia = int4 = Sequencia 
                 rh13_instit = int4 = Instituição 
                 rh13_matricula = int4 = Próxima Matricula 
                 rh13_unificada = bool = Controle Unificado da Matrícula 
                 ";
   //funcao construtor da classe 
   function cl_rhcfpessmatr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhcfpessmatr"); 
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
       $this->rh13_sequencia = ($this->rh13_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"]:$this->rh13_sequencia);
       $this->rh13_instit = ($this->rh13_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh13_instit"]:$this->rh13_instit);
       $this->rh13_matricula = ($this->rh13_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["rh13_matricula"]:$this->rh13_matricula);
       $this->rh13_unificada = ($this->rh13_unificada == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh13_unificada"]:$this->rh13_unificada);
     }else{
       $this->rh13_sequencia = ($this->rh13_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"]:$this->rh13_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($rh13_sequencia){ 
      $this->atualizacampos();
     if($this->rh13_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh13_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh13_matricula == null ){ 
       $this->erro_sql = " Campo Próxima Matricula nao Informado.";
       $this->erro_campo = "rh13_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh13_unificada == null ){ 
       $this->erro_sql = " Campo Controle Unificado da Matrícula nao Informado.";
       $this->erro_campo = "rh13_unificada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh13_sequencia == "" || $rh13_sequencia == null ){
       $result = db_query("select nextval('rhcfpessmatr_rh13_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhcfpessmatr_rh13_sequencia_seq do campo: rh13_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh13_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhcfpessmatr_rh13_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh13_sequencia)){
         $this->erro_sql = " Campo rh13_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh13_sequencia = $rh13_sequencia; 
       }
     }
     if(($this->rh13_sequencia == null) || ($this->rh13_sequencia == "") ){ 
       $this->erro_sql = " Campo rh13_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhcfpessmatr(
                                       rh13_sequencia 
                                      ,rh13_instit 
                                      ,rh13_matricula 
                                      ,rh13_unificada 
                       )
                values (
                                $this->rh13_sequencia 
                               ,$this->rh13_instit 
                               ,$this->rh13_matricula 
                               ,'$this->rh13_unificada' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Controle de inclusão de matrículas ($this->rh13_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Controle de inclusão de matrículas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Controle de inclusão de matrículas ($this->rh13_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh13_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9977,'$this->rh13_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1713,9977,'','".AddSlashes(pg_result($resaco,0,'rh13_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1713,9978,'','".AddSlashes(pg_result($resaco,0,'rh13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1713,9979,'','".AddSlashes(pg_result($resaco,0,'rh13_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1713,9980,'','".AddSlashes(pg_result($resaco,0,'rh13_unificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh13_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update rhcfpessmatr set ";
     $virgula = "";
     if(trim($this->rh13_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"])){ 
       $sql  .= $virgula." rh13_sequencia = $this->rh13_sequencia ";
       $virgula = ",";
       if(trim($this->rh13_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "rh13_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_instit"])){ 
       $sql  .= $virgula." rh13_instit = $this->rh13_instit ";
       $virgula = ",";
       if(trim($this->rh13_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh13_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_matricula"])){ 
       $sql  .= $virgula." rh13_matricula = $this->rh13_matricula ";
       $virgula = ",";
       if(trim($this->rh13_matricula) == null ){ 
         $this->erro_sql = " Campo Próxima Matricula nao Informado.";
         $this->erro_campo = "rh13_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_unificada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_unificada"])){ 
       $sql  .= $virgula." rh13_unificada = '$this->rh13_unificada' ";
       $virgula = ",";
       if(trim($this->rh13_unificada) == null ){ 
         $this->erro_sql = " Campo Controle Unificado da Matrícula nao Informado.";
         $this->erro_campo = "rh13_unificada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh13_sequencia!=null){
       $sql .= " rh13_sequencia = $this->rh13_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh13_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9977,'$this->rh13_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1713,9977,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_sequencia'))."','$this->rh13_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_instit"]))
           $resac = db_query("insert into db_acount values($acount,1713,9978,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_instit'))."','$this->rh13_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_matricula"]))
           $resac = db_query("insert into db_acount values($acount,1713,9979,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_matricula'))."','$this->rh13_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_unificada"]))
           $resac = db_query("insert into db_acount values($acount,1713,9980,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_unificada'))."','$this->rh13_unificada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de inclusão de matrículas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de inclusão de matrículas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh13_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh13_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9977,'$rh13_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1713,9977,'','".AddSlashes(pg_result($resaco,$iresaco,'rh13_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1713,9978,'','".AddSlashes(pg_result($resaco,$iresaco,'rh13_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1713,9979,'','".AddSlashes(pg_result($resaco,$iresaco,'rh13_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1713,9980,'','".AddSlashes(pg_result($resaco,$iresaco,'rh13_unificada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhcfpessmatr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh13_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh13_sequencia = $rh13_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de inclusão de matrículas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh13_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de inclusão de matrículas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh13_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh13_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhcfpessmatr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh13_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcfpessmatr ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhcfpessmatr.rh13_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh13_sequencia!=null ){
         $sql2 .= " where rhcfpessmatr.rh13_sequencia = $rh13_sequencia "; 
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
   function sql_query_file ( $rh13_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhcfpessmatr ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh13_sequencia!=null ){
         $sql2 .= " where rhcfpessmatr.rh13_sequencia = $rh13_sequencia "; 
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
   function alterar_where ($rh13_sequencia=null,$dbwhere=null) { 
      $this->atualizacampos();
     $sql = " update rhcfpessmatr set ";
     $virgula = "";
     if(trim($this->rh13_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"])){ 
       $sql  .= $virgula." rh13_sequencia = $this->rh13_sequencia ";
       $virgula = ",";
       if(trim($this->rh13_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "rh13_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_instit"])){ 
       $sql  .= $virgula." rh13_instit = $this->rh13_instit ";
       $virgula = ",";
       if(trim($this->rh13_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh13_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_matricula"])){ 
       $sql  .= $virgula." rh13_matricula = $this->rh13_matricula ";
       $virgula = ",";
       if(trim($this->rh13_matricula) == null ){ 
         $this->erro_sql = " Campo Próxima Matricula nao Informado.";
         $this->erro_campo = "rh13_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh13_unificada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh13_unificada"])){ 
       $sql  .= $virgula." rh13_unificada = '$this->rh13_unificada' ";
       $virgula = ",";
       if(trim($this->rh13_unificada) == null ){ 
         $this->erro_sql = " Campo Controle Unificado da Matrícula nao Informado.";
         $this->erro_campo = "rh13_unificada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     
     $sql .= " where ";
		 /*
     if($rh13_sequencia!=null){
       $sql .= " rh13_sequencia = $this->rh13_sequencia";
     }
		 */
     $resaco = $this->sql_record($this->sql_query_file($this->rh13_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9977,'$this->rh13_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_sequencia"]))
           $resac = pg_query("insert into db_acount values($acount,1713,9977,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_sequencia'))."','$this->rh13_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_instit"]))
           $resac = pg_query("insert into db_acount values($acount,1713,9978,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_instit'))."','$this->rh13_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1713,9979,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_matricula'))."','$this->rh13_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh13_unificada"]))
           $resac = pg_query("insert into db_acount values($acount,1713,9980,'".AddSlashes(pg_result($resaco,$conresaco,'rh13_unificada'))."','$this->rh13_unificada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh13_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh13_sequencia = $rh13_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Controle de inclusão de matrículas nao Alterado. Alteracao Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Controle de inclusão de matrículas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh13_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
}
?>