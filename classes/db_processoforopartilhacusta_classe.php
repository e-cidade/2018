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

//MODULO: juridico
//CLASSE DA ENTIDADE processoforopartilhacusta
class cl_processoforopartilhacusta { 
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
   var $v77_sequencial = 0; 
   var $v77_taxa = 0; 
   var $v77_processoforopartilha = 0; 
   var $v77_valor = 0; 
   var $v77_numnov = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v77_sequencial = int4 = Sequencial 
                 v77_taxa = int4 = Taxa 
                 v77_processoforopartilha = int4 = Partilha do Processo 
                 v77_valor = float8 = Valor 
                 v77_numnov = int8 = Numpre do Recibo 
                 ";
   //funcao construtor da classe 
   function cl_processoforopartilhacusta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processoforopartilhacusta"); 
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
       $this->v77_sequencial = ($this->v77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_sequencial"]:$this->v77_sequencial);
       $this->v77_taxa = ($this->v77_taxa == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_taxa"]:$this->v77_taxa);
       $this->v77_processoforopartilha = ($this->v77_processoforopartilha == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_processoforopartilha"]:$this->v77_processoforopartilha);
       $this->v77_valor = ($this->v77_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_valor"]:$this->v77_valor);
       $this->v77_numnov = ($this->v77_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_numnov"]:$this->v77_numnov);
     }else{
       $this->v77_sequencial = ($this->v77_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v77_sequencial"]:$this->v77_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v77_sequencial){ 
      $this->atualizacampos();
     if($this->v77_taxa == null ){ 
       $this->erro_sql = " Campo Taxa nao Informado.";
       $this->erro_campo = "v77_taxa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v77_processoforopartilha == null ){ 
       $this->erro_sql = " Campo Partilha do Processo nao Informado.";
       $this->erro_campo = "v77_processoforopartilha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v77_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "v77_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v77_numnov == null ){ 
       $this->v77_numnov = "0";
     }
     if($v77_sequencial == "" || $v77_sequencial == null ){
       $result = db_query("select nextval('processoforopartilhacusta_v77_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processoforopartilhacusta_v77_sequencial_seq do campo: v77_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v77_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processoforopartilhacusta_v77_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v77_sequencial)){
         $this->erro_sql = " Campo v77_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v77_sequencial = $v77_sequencial; 
       }
     }
     if(($this->v77_sequencial == null) || ($this->v77_sequencial == "") ){ 
       $this->erro_sql = " Campo v77_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processoforopartilhacusta(
                                       v77_sequencial 
                                      ,v77_taxa 
                                      ,v77_processoforopartilha 
                                      ,v77_valor 
                                      ,v77_numnov 
                       )
                values (
                                $this->v77_sequencial 
                               ,$this->v77_taxa 
                               ,$this->v77_processoforopartilha 
                               ,$this->v77_valor 
                               ,$this->v77_numnov 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custas vinculadas a partilha do processo ($this->v77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custas vinculadas a partilha do processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custas vinculadas a partilha do processo ($this->v77_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v77_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v77_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18256,'$this->v77_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3230,18256,'','".AddSlashes(pg_result($resaco,0,'v77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3230,18263,'','".AddSlashes(pg_result($resaco,0,'v77_taxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3230,18264,'','".AddSlashes(pg_result($resaco,0,'v77_processoforopartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3230,18265,'','".AddSlashes(pg_result($resaco,0,'v77_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3230,18266,'','".AddSlashes(pg_result($resaco,0,'v77_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v77_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processoforopartilhacusta set ";
     $virgula = "";
     if(trim($this->v77_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v77_sequencial"])){ 
       $sql  .= $virgula." v77_sequencial = $this->v77_sequencial ";
       $virgula = ",";
       if(trim($this->v77_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v77_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v77_taxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v77_taxa"])){ 
       $sql  .= $virgula." v77_taxa = $this->v77_taxa ";
       $virgula = ",";
       if(trim($this->v77_taxa) == null ){ 
         $this->erro_sql = " Campo Taxa nao Informado.";
         $this->erro_campo = "v77_taxa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v77_processoforopartilha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v77_processoforopartilha"])){ 
       $sql  .= $virgula." v77_processoforopartilha = $this->v77_processoforopartilha ";
       $virgula = ",";
       if(trim($this->v77_processoforopartilha) == null ){ 
         $this->erro_sql = " Campo Partilha do Processo nao Informado.";
         $this->erro_campo = "v77_processoforopartilha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v77_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v77_valor"])){ 
       $sql  .= $virgula." v77_valor = $this->v77_valor ";
       $virgula = ",";
       if(trim($this->v77_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "v77_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v77_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v77_numnov"])){ 
        if(trim($this->v77_numnov)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v77_numnov"])){ 
           $this->v77_numnov = "0" ; 
        } 
       $sql  .= $virgula." v77_numnov = $this->v77_numnov ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v77_sequencial!=null){
       $sql .= " v77_sequencial = $this->v77_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v77_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18256,'$this->v77_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v77_sequencial"]) || $this->v77_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3230,18256,'".AddSlashes(pg_result($resaco,$conresaco,'v77_sequencial'))."','$this->v77_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v77_taxa"]) || $this->v77_taxa != "")
           $resac = db_query("insert into db_acount values($acount,3230,18263,'".AddSlashes(pg_result($resaco,$conresaco,'v77_taxa'))."','$this->v77_taxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v77_processoforopartilha"]) || $this->v77_processoforopartilha != "")
           $resac = db_query("insert into db_acount values($acount,3230,18264,'".AddSlashes(pg_result($resaco,$conresaco,'v77_processoforopartilha'))."','$this->v77_processoforopartilha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v77_valor"]) || $this->v77_valor != "")
           $resac = db_query("insert into db_acount values($acount,3230,18265,'".AddSlashes(pg_result($resaco,$conresaco,'v77_valor'))."','$this->v77_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v77_numnov"]) || $this->v77_numnov != "")
           $resac = db_query("insert into db_acount values($acount,3230,18266,'".AddSlashes(pg_result($resaco,$conresaco,'v77_numnov'))."','$this->v77_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custas vinculadas a partilha do processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custas vinculadas a partilha do processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v77_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v77_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18256,'$v77_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3230,18256,'','".AddSlashes(pg_result($resaco,$iresaco,'v77_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3230,18263,'','".AddSlashes(pg_result($resaco,$iresaco,'v77_taxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3230,18264,'','".AddSlashes(pg_result($resaco,$iresaco,'v77_processoforopartilha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3230,18265,'','".AddSlashes(pg_result($resaco,$iresaco,'v77_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3230,18266,'','".AddSlashes(pg_result($resaco,$iresaco,'v77_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processoforopartilhacusta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v77_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v77_sequencial = $v77_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custas vinculadas a partilha do processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v77_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custas vinculadas a partilha do processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v77_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v77_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processoforopartilhacusta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforopartilhacusta ";
     $sql .= "      inner join taxa  on  taxa.ar36_sequencial = processoforopartilhacusta.v77_taxa";
     $sql .= "      inner join processoforopartilha  on  processoforopartilha.v76_sequencial = processoforopartilhacusta.v77_processoforopartilha";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = taxa.ar36_receita";
     $sql .= "      inner join grupotaxa  on  grupotaxa.ar37_sequencial = taxa.ar36_grupotaxa";
     $sql .= "      inner join processoforo  as a on   a.v70_sequencial = processoforopartilha.v76_processoforo";
     $sql2 = "";
     if($dbwhere==""){
       if($v77_sequencial!=null ){
         $sql2 .= " where processoforopartilhacusta.v77_sequencial = $v77_sequencial "; 
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
   function sql_query_file ( $v77_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processoforopartilhacusta ";
     $sql2 = "";
     if($dbwhere==""){
       if($v77_sequencial!=null ){
         $sql2 .= " where processoforopartilhacusta.v77_sequencial = $v77_sequencial "; 
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

  /**
   * SQL Com os Dados  do recibo da partilha do processo do foro e suas custas
   * @param integer $v77_sequencial
   * @param string  $campos
   * @param string  $ordem
   * @param string  $dbwhere
   * @return string
   */
   function sql_query_recibo( $v77_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
     
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
     $sql .= " from processoforopartilhacusta ";
     $sql .= "      inner join taxa                  on  taxa.ar36_sequencial                 = processoforopartilhacusta.v77_taxa                 ";
     $sql .= "      inner join tabrec                on  tabrec.k02_codigo                    = taxa.ar36_receita                                  ";
     $sql .= "      inner join grupotaxa             on  grupotaxa.ar37_sequencial            = taxa.ar36_grupotaxa                                ";
     $sql .= "      inner join favorecidotaxa        on  favorecidotaxa.v87_taxa              = taxa.ar36_sequencial                               ";
     $sql .= "      inner join favorecido            on  favorecido.v86_sequencial            = favorecidotaxa.v87_favorecido                      ";
     $sql .= "      inner join cgm as cgmfavorecido  on  cgmfavorecido.z01_numcgm             = favorecido.v86_numcgm                              ";                        
     $sql .= "      inner join contabancaria         on  contabancaria.db83_sequencial        = favorecido.v86_contabancaria                       ";
     $sql .= "      inner join bancoagencia          on  contabancaria.db83_bancoagencia      = bancoagencia.db89_sequencial                       ";
     $sql .= "      inner join processoforopartilha  on  processoforopartilha.v76_sequencial  = processoforopartilhacusta.v77_processoforopartilha ";
     $sql .= "      inner join processoforo          on  processoforo.v70_sequencial          = processoforopartilha.v76_processoforo              ";
     $sql .= "      inner join processoforoinicial   on  processoforoinicial.v71_processoforo = processoforo.v70_sequencial                        ";
     $sql .= "      inner join inicial               on  processoforoinicial.v71_inicial      = inicial.v50_inicial                                ";     
     $sql .= "      inner join inicialcert           on  inicialcert.v51_inicial              = inicial.v50_inicial                                ";     
     $sql .= "       left join recibopaga            on  recibopaga.k00_numnov                = processoforopartilhacusta.v77_numnov               ";
     $sql .= "       left join cancrecibopaga        on  cancrecibopaga.k134_numnov           = processoforopartilhacusta.v77_numnov               ";     
     $sql .= "       left join cgm as cgmrecibopaga  on  cgmrecibopaga.z01_numcgm             = recibopaga.k00_numcgm                              ";
               
     $sql2 = "";
     if ($dbwhere=="") {
       if($v77_sequencial!=null ){
         $sql2 .= " where processoforopartilhacusta.v77_sequencial = $v77_sequencial "; 
       }
     } else if($dbwhere != "") {
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

  /**
   * Retorna valor para base de cálculo das custas do processo
   *
   * @param  integer $iNumpreRecibo - numpre novo do recibo emitido
   * @param  integer $iProcessoForo - Sequencial do processo do foro de qual é buscado o valor
   * @param  date    $dtVencimento  - Data de vencimento para base de cálculo
   * @param  integer $iCadTipo      - Agrupador do tipo de débito da tabela arretipo (campo k03_tipo)
   * @param  integer $iTipoDebito   - Tipo de débito da tabela arretipo (campo k00_tipo)
   */
  function getCustasProcesso($iNumpreRecibo, $iCodigoProcessoForo, $dtVencimento, $iCadTipo){

    $iAnoVencimento   = substr($dtVencimento, 0, 4);
    $aNumpresProcesso = $this->getNumpresOrigemProcessoForo($iCodigoProcessoForo, $iCadTipo);

    $sNumpresProcesso = implode(",", $aNumpresProcesso);


    $sSqlValor        = " select sum( substr( fc_calcula, 15, 13)::numeric(10,2) )  as vlr_corrigido,                        \n";
    $sSqlValor       .= "        sum( substr( fc_calcula, 28, 13)::numeric(10,2) )  as vlr_juros,                            \n";
    $sSqlValor       .= "        sum( substr( fc_calcula, 41, 13)::numeric(10,2) )  as vlr_multa,                            \n";
    $sSqlValor       .= "        sum( substr( fc_calcula, 54, 13)::numeric(10,2) )  as vlr_desconto                          \n";
    $sSqlValor       .= "  from (  select k00_numpre,                                                                        \n";

    if ( $iCadTipo == 18 ) {
      $sSqlValor     .= "fc_calcula(k00_numpre, k00_numpar, k00_receit, '{$dtVencimento}', '{$dtVencimento}', $iAnoVencimento) \n";
    } else {
      $sSqlValor     .= "fc_calcula(k00_numpre, k00_numpar, k00_receit, k00_dtvenc, k00_dtvenc, extract(year from k00_dtvenc)::integer ) \n";
    }
    $sSqlValor       .= "            from arrecad                                                                            \n";
    $sSqlValor       .= "           where k00_numpre in ({$sNumpresProcesso})                                                \n";
    $sSqlValor       .= "        group by k00_numpre,                                                                        \n";
    $sSqlValor       .= "                 k00_numpar,                                                                        \n";
    $sSqlValor       .= "                 k00_receit,                                                                        \n";
    $sSqlValor       .= "                 k00_dtvenc                                                                         \n";
    $sSqlValor       .= "       ) as calculo;                                                                                \n";

    $rsValor          = db_query($sSqlValor);
    $oValor           = db_utils::fieldsMemory($rsValor,0);

    $nCustasProcesso  = $oValor->vlr_corrigido +
    $oValor->vlr_juros     +
    $oValor->vlr_multa     -
    $oValor->vlr_desconto;


    if ( !empty($iNumpreRecibo) ) {

      /**
       * Valida se a regra se existe regra de desconto definida
       */
      $sSqlDBReciboWeb  = " select distinct k99_desconto from db_reciboweb where k99_numpre_n = {$iNumpreRecibo}";
      $rsDBReciboWeb    = db_query($sSqlDBReciboWeb);

      if ( $rsDBReciboWeb && pg_num_rows($rsDBReciboWeb) > 0 ) {
         
        $iCodigoDesconto   = db_utils::fieldsMemory($rsDBReciboWeb,0)->k99_desconto;
         
        $sSqlDadosDesconto = " select descmul,                                         \n";
        $sSqlDadosDesconto.= "        descjur,                                         \n";
        $sSqlDadosDesconto.= "        descvlr                                          \n";
        $sSqlDadosDesconto.= "   from cadtipoparc                                      \n";
        $sSqlDadosDesconto.= "        inner join tipoparc on cadtipoparc = k40_codigo  \n";
        $sSqlDadosDesconto.= "  where k40_codigo = {$iCodigoDesconto}                  \n";
        $sSqlDadosDesconto.= "    and maxparc =1;                                      \n";
         
        $rsDesconto        = db_query($sSqlDadosDesconto);
         
         
        if ( $rsDesconto &&  pg_num_rows($rsDesconto) > 0 ) {

          $oDesconto         = db_utils::fieldsMemory($rsDesconto, 0);
          $nCustasProcesso   = ( $oValor->vlr_corrigido - round ( ($oValor->vlr_corrigido  *  ( $oDesconto->descvlr / 100 ) ), 2) ) +
          ( $oValor->vlr_juros     - round ( ($oValor->vlr_juros      *  ( $oDesconto->descjur / 100 ) ), 2) ) +
          ( $oValor->vlr_multa     - round ( ($oValor->vlr_multa      *  ( $oDesconto->descmul / 100 ) ), 2) ) -
          $oValor->vlr_desconto;
          $nCustasProcesso   = round($nCustasProcesso, 2);
        }
      }
    }
    return $nCustasProcesso;
  }

  /**
   * Retorna os numpres de origem de um processo do Foro
   * @param  integer   $iProcessoForo - Sequencial do processo do foro de qual é buscado o valor
   * @param  integer   $iCadTipo      - Agrupador do tipo de débito da tabela arretipo (campo k03_tipo)
   * @return array                    - Array Com os numpres de origem
   */
  function getNumpresOrigemProcessoForo($iProcessoForo, $iCadTipo) {
     
    /**
     * Array com os Numnpres de Origem do Processo do foro
     * @var array
     */
    $aNumpresOrigem = array();

    switch((int)$iCadTipo){

      case 18: //INICIAL DO FORO

        $sSqlProcessoForo  = " select v59_numpre,                                                          ";
        $sSqlProcessoForo .= "        v07_dtlanc                                                           ";
        $sSqlProcessoForo .= "   from inicialnumpre                                                        ";
        $sSqlProcessoForo .= "        inner join processoforoinicial on v59_inicial      = v71_inicial     ";
        $sSqlProcessoForo .= "        inner join processoforo        on v71_processoforo = v70_sequencial  ";
        $sSqlProcessoForo .= "        left  join termoini            on inicial          = v59_inicial     ";
        $sSqlProcessoForo .= "        left  join termo               on parcel           = v07_parcel      ";
        $sSqlProcessoForo .= "  where v70_sequencial = {$iProcessoForo}                                    ";
        $rsProcessoForo    = db_query($sSqlProcessoForo);

        if($rsProcessoForo && pg_num_rows($rsProcessoForo) > 0){

          $aNumpresProcessoForo = db_utils::getColectionByRecord($rsProcessoForo);

          foreach ($aNumpresProcessoForo as $oNumpres){
            $aNumpresOrigem[$oNumpres->v59_numpre] = $oNumpres->v59_numpre;
          }
        } else {
          throw new Exception("Erro ao buscar os débitos do processo : ".$iProcessoForo);
        }

        break;
      case 13: // PARCELAMENTO DO FORO

        $sSqlNumpresProcessoForo = " select distinct v07_numpre                                                 \n";
        $sSqlNumpresProcessoForo.= "   from processoforo                                                        \n";
        $sSqlNumpresProcessoForo.= "        inner join processoforoinicial on v71_processoforo = v70_sequencial \n";
        $sSqlNumpresProcessoForo.= "        inner join termoini            on v71_inicial      = inicial        \n";
        $sSqlNumpresProcessoForo.= "        inner join termo               on parcel           = v07_parcel     \n";
        $sSqlNumpresProcessoForo.= "        inner join arrecad             on k00_numpre       = v07_numpre     \n";
        $sSqlNumpresProcessoForo.= "  where v70_sequencial = {$iProcessoForo}                                   \n";

        $rsNumpresProcessoForo   = db_query($sSqlNumpresProcessoForo);

        if ( $rsNumpresProcessoForo && pg_num_rows($rsNumpresProcessoForo) > 0 ) {
           
          $aNumpresOrigem[] = db_utils::fieldsMemory($rsNumpresProcessoForo,0)->v07_numpre;

          return $aNumpresOrigem;
        } else {
          throw new Exception("Nenhum Débito para este processo do Foro");
        }
        break;
      default:
        throw new Exception("Tipo de Débito sem taxas configuradas");
        break;
    }
    return $aNumpresOrigem;
  }

  /**
   * Retorna o codigo sequencial da tabela processoforo
   * @param   integer $iNumpre - Numpre do Parcelamento
   * @return  integer
   */
  function getProcessoForoByNumprePacelamento($iNumpre, $iCadTipo) {
    
    $aRetorno       = array();
    $aProcessoForo  = array();
    if ($iCadTipo == 13) {
    
      $sSqlProcessoForo  = "select distinct processoforoinicial.v71_processoforo           \n ";
      $sSqlProcessoForo .= "  from termo                                                   \n ";
      $sSqlProcessoForo .= "       inner join termoini            on parcel  = v07_parcel  \n ";
      $sSqlProcessoForo .= "       inner join processoforoinicial on inicial = v71_inicial \n ";
      $sSqlProcessoForo .= " where v07_numpre = {$iNumpre}                                 \n ";
    } elseif ($iCadTipo == 18) {
      
      $sSqlProcessoForo  = "select distinct processoforoinicial.v71_processoforo                ";
      $sSqlProcessoForo .= "  from inicialnumpre                                                ";
      $sSqlProcessoForo .= "       inner join processoforoinicial on  v71_inicial = v59_inicial ";
      $sSqlProcessoForo .= " where v59_numpre = {$iNumpre}                                      ";
    } else {
      return $aRetorno; 
    }
    $rsProcessoForo    = db_query($sSqlProcessoForo);
      
    if($rsProcessoForo && pg_num_rows($rsProcessoForo) > 0){
      
      $aProcessoForo   = db_utils::getColectionByRecord($rsProcessoForo);
      
      foreach ($aProcessoForo as $oProcessoForo) {
        $aRetorno[] = $oProcessoForo->v71_processoforo;
      }
    }
    
    return $aRetorno;
  }

  /**
   * Retorna o sql das taxas de favorecidos
   *
   * @param integer $iCodigoProcesso
   * @param string  $sCampos
   * @access public
   * @return string - sql
   */
  function sql_query_taxasFavorecidos($iCodigoProcesso, $sCampos = '*', $iNumnov=null ) {

    $sSql  = "select {$sCampos}                                                                    ";
    $sSql .= "  from processoforopartilhacusta                                                     ";
    $sSql .= "       inner join processoforopartilha on v76_sequencial  = v77_processoforopartilha ";
    $sSql .= "       inner join favorecidotaxa       on v87_taxa        = v77_taxa                 ";
    $sSql .= "       inner join favorecido           on v86_sequencial  = v87_favorecido           ";
    $sSql .= "       inner join cgm                  on z01_numcgm      = v86_numcgm               ";
    $sSql .= "       inner join taxa                 on ar36_sequencial = v87_taxa                 ";
    $sSql .= " where v76_processoforo = {$iCodigoProcesso}                                         ";

    if (!empty($iNumnov)) {

      $sSql .= "   and v77_numnov       = {$iNumnov}";
    }

    return $sSql;
  }

/**
 * Retorna o código do prpocesso foro através de um numpre de recibo
 * @param integer $iNumpreRecibo
 * @access public
 * @return integer - Código do Processo do Foro
 */
function getProcessoForoByNumpreRecibo($iNumpreRecibo) {

  $sSql  = "select distinct                                                                     \n";
  $sSql .= "       v76_processoforo                                                             \n";
  $sSql .= "  from processoforopartilhacusta                                                    \n";
  $sSql .= "       inner join processoforopartilha on v76_sequencial  = v77_processoforopartilha\n";
  $sSql .= " where v77_numnov = {$iNumpreRecibo};                                               \n";

  $rsSql = db_query($sSql);

  if ( !$rsSql || pg_num_rows($rsSql) == 0 ) {
    return null;
  }

  return db_utils::fieldsMemory($rsSql, 0)->v76_processoforo;
}


  /**
   * Retorna dados para envio do arquivo remessa para o banco. (otimizado)
   *
   * @param integer $v77_sequencial
   * @param string  $campos
   * @param string  $sOrdem
   * @param string  $sWhere
   * @access public
   * @return string - $sSql
   */
  function sql_query_recibo_banco( $v77_sequencial = null, $sCampos = "*", $sOrdem = null, $sWhere = "") {
   
    $sSql = "select ";
    
    if ( $sCampos != "*" ) {
      
      $campos_sql = split("#", $sCampos);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sSql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      
      $sSql .= $sCampos. " \n";
    }
    
    $sSql .= " from processoforopartilha                                                                                                \n";
    $sSql .= "     inner join processoforopartilhacusta on v77_processoforopartilha             = v76_sequencial                        \n";
    $sSql .= "     inner join taxa                      on taxa.ar36_sequencial                 = processoforopartilhacusta.v77_taxa    \n";
    $sSql .= "     inner join favorecidotaxa            on favorecidotaxa.v87_taxa              = taxa.ar36_sequencial                  \n";
    $sSql .= "     inner join favorecido                on favorecido.v86_sequencial            = favorecidotaxa.v87_favorecido         \n";
    $sSql .= "     inner join cgm as cgmfavorecido      on cgmfavorecido.z01_numcgm             = favorecido.v86_numcgm                 \n";
    $sSql .= "     inner join contabancaria             on contabancaria.db83_sequencial        = favorecido.v86_contabancaria          \n";
    $sSql .= "     inner join bancoagencia              on contabancaria.db83_bancoagencia      = bancoagencia.db89_sequencial          \n";
    $sSql .= "     inner join processoforo              on processoforo.v70_sequencial          = processoforopartilha.v76_processoforo \n";
    $sSql .= "     inner join processoforoinicial       on processoforoinicial.v71_processoforo = processoforo.v70_sequencial           \n";
    $sSql .= "     inner join inicial                   on processoforoinicial.v71_inicial      = inicial.v50_inicial                   \n";
    $sSql .= "     inner join inicialcert               on inicialcert.v51_inicial              = inicial.v50_inicial                   \n";
    $sSql .= "     inner join arrebanco                 on arrebanco.k00_numpre                 = processoforopartilhacusta.v77_numnov  \n";
    $sSql .= "                                         and cast(trim(k00_numbco) as numeric)    <> 0                                    \n";
    $sSql .= "     inner join recibopagaboleto          on recibopagaboleto.k138_numnov         = processoforopartilhacusta.v77_numnov  \n";
    $sSql .= "     inner join recibopaga                on recibopaga.k00_numnov                = recibopagaboleto.k138_numnov          \n";
    $sSql .= "     left  join cancrecibopaga            on cancrecibopaga.k134_numnov           = recibopagaboleto.k138_numnov          \n";
    $sSql .= "     inner join cgm as cgmrecibopaga      on cgmrecibopaga.z01_numcgm             = recibopaga.k00_numcgm                 \n";
    
    $sSql2 = "";
     
    if ( $sWhere == "" ) {
      
      if( $v77_sequencial!=null ){
        
        $sSql2 .= " where processoforopartilhacusta.v77_sequencial = $v77_sequencial ";
      }
    } else if ( $sWhere != "" ) {
      
      $sSql2 = " where $sWhere \n";
    }
    
    $sSql .= $sSql2;
      
    if ( $sOrdem != null ) {
      
      $sSql       .= " order by ";
      $campos_sql = split("#", $sOrdem);
      $virgula    = "";
      
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sSql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
       
    return $sSql;
  }
  
  
}
?>