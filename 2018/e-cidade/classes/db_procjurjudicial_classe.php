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
//CLASSE DA ENTIDADE procjurjudicial
class cl_procjurjudicial { 
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
   var $v63_sequencial = 0; 
   var $v63_localiza = 0; 
   var $v63_procjur = 0; 
   var $v63_processoforo = null; 
   var $v63_vara = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v63_sequencial = int4 = Código do processo judicial 
                 v63_localiza = int4 = Localização 
                 v63_procjur = int4 = Processo Jurídico 
                 v63_processoforo = varchar(20) = Processo Foro 
                 v63_vara = int4 = Vara 
                 ";
   //funcao construtor da classe 
   function cl_procjurjudicial() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procjurjudicial"); 
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
       $this->v63_sequencial = ($this->v63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_sequencial"]:$this->v63_sequencial);
       $this->v63_localiza = ($this->v63_localiza == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_localiza"]:$this->v63_localiza);
       $this->v63_procjur = ($this->v63_procjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_procjur"]:$this->v63_procjur);
       $this->v63_processoforo = ($this->v63_processoforo == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_processoforo"]:$this->v63_processoforo);
       $this->v63_vara = ($this->v63_vara == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_vara"]:$this->v63_vara);
     }else{
       $this->v63_sequencial = ($this->v63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v63_sequencial"]:$this->v63_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v63_sequencial){ 
      $this->atualizacampos();
     if($this->v63_localiza == null ){ 
       $this->erro_sql = " Campo Localização nao Informado.";
       $this->erro_campo = "v63_localiza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v63_procjur == null ){ 
       $this->erro_sql = " Campo Processo Jurídico nao Informado.";
       $this->erro_campo = "v63_procjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v63_processoforo == null ){ 
       $this->erro_sql = " Campo Processo Foro nao Informado.";
       $this->erro_campo = "v63_processoforo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v63_vara == null ){ 
       $this->erro_sql = " Campo Vara nao Informado.";
       $this->erro_campo = "v63_vara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v63_sequencial == "" || $v63_sequencial == null ){
       $result = db_query("select nextval('procjurjudicial_v63_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procjurjudicial_v63_sequencial_seq do campo: v63_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v63_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procjurjudicial_v63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v63_sequencial)){
         $this->erro_sql = " Campo v63_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v63_sequencial = $v63_sequencial; 
       }
     }
     if(($this->v63_sequencial == null) || ($this->v63_sequencial == "") ){ 
       $this->erro_sql = " Campo v63_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procjurjudicial(
                                       v63_sequencial 
                                      ,v63_localiza 
                                      ,v63_procjur 
                                      ,v63_processoforo 
                                      ,v63_vara 
                       )
                values (
                                $this->v63_sequencial 
                               ,$this->v63_localiza 
                               ,$this->v63_procjur 
                               ,'$this->v63_processoforo' 
                               ,$this->v63_vara 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo Judicial ($this->v63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo Judicial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo Judicial ($this->v63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v63_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v63_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12681,'$this->v63_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2215,12681,'','".AddSlashes(pg_result($resaco,0,'v63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2215,12683,'','".AddSlashes(pg_result($resaco,0,'v63_localiza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2215,12682,'','".AddSlashes(pg_result($resaco,0,'v63_procjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2215,12684,'','".AddSlashes(pg_result($resaco,0,'v63_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2215,12685,'','".AddSlashes(pg_result($resaco,0,'v63_vara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v63_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update procjurjudicial set ";
     $virgula = "";
     if(trim($this->v63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v63_sequencial"])){ 
       $sql  .= $virgula." v63_sequencial = $this->v63_sequencial ";
       $virgula = ",";
       if(trim($this->v63_sequencial) == null ){ 
         $this->erro_sql = " Campo Código do processo judicial nao Informado.";
         $this->erro_campo = "v63_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v63_localiza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v63_localiza"])){ 
       $sql  .= $virgula." v63_localiza = $this->v63_localiza ";
       $virgula = ",";
       if(trim($this->v63_localiza) == null ){ 
         $this->erro_sql = " Campo Localização nao Informado.";
         $this->erro_campo = "v63_localiza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v63_procjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v63_procjur"])){ 
       $sql  .= $virgula." v63_procjur = $this->v63_procjur ";
       $virgula = ",";
       if(trim($this->v63_procjur) == null ){ 
         $this->erro_sql = " Campo Processo Jurídico nao Informado.";
         $this->erro_campo = "v63_procjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v63_processoforo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v63_processoforo"])){ 
       $sql  .= $virgula." v63_processoforo = '$this->v63_processoforo' ";
       $virgula = ",";
       if(trim($this->v63_processoforo) == null ){ 
         $this->erro_sql = " Campo Processo Foro nao Informado.";
         $this->erro_campo = "v63_processoforo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v63_vara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v63_vara"])){ 
       $sql  .= $virgula." v63_vara = $this->v63_vara ";
       $virgula = ",";
       if(trim($this->v63_vara) == null ){ 
         $this->erro_sql = " Campo Vara nao Informado.";
         $this->erro_campo = "v63_vara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v63_sequencial!=null){
       $sql .= " v63_sequencial = $this->v63_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v63_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12681,'$this->v63_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v63_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2215,12681,'".AddSlashes(pg_result($resaco,$conresaco,'v63_sequencial'))."','$this->v63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v63_localiza"]))
           $resac = db_query("insert into db_acount values($acount,2215,12683,'".AddSlashes(pg_result($resaco,$conresaco,'v63_localiza'))."','$this->v63_localiza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v63_procjur"]))
           $resac = db_query("insert into db_acount values($acount,2215,12682,'".AddSlashes(pg_result($resaco,$conresaco,'v63_procjur'))."','$this->v63_procjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v63_processoforo"]))
           $resac = db_query("insert into db_acount values($acount,2215,12684,'".AddSlashes(pg_result($resaco,$conresaco,'v63_processoforo'))."','$this->v63_processoforo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v63_vara"]))
           $resac = db_query("insert into db_acount values($acount,2215,12685,'".AddSlashes(pg_result($resaco,$conresaco,'v63_vara'))."','$this->v63_vara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Judicial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo Judicial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v63_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v63_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12681,'$v63_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2215,12681,'','".AddSlashes(pg_result($resaco,$iresaco,'v63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2215,12683,'','".AddSlashes(pg_result($resaco,$iresaco,'v63_localiza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2215,12682,'','".AddSlashes(pg_result($resaco,$iresaco,'v63_procjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2215,12684,'','".AddSlashes(pg_result($resaco,$iresaco,'v63_processoforo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2215,12685,'','".AddSlashes(pg_result($resaco,$iresaco,'v63_vara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procjurjudicial
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v63_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v63_sequencial = $v63_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Judicial nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo Judicial nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:procjurjudicial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procjurjudicial ";
     $sql .= "      inner join localiza  on  localiza.v54_codlocal = procjurjudicial.v63_localiza";
     $sql .= "      inner join procjur  on  procjur.v62_sequencial = procjurjudicial.v63_procjur";
     $sql .= "      inner join procjurtipo  on  procjurtipo.v66_sequencial = procjur.v62_procjurtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($v63_sequencial!=null ){
         $sql2 .= " where procjurjudicial.v63_sequencial = $v63_sequencial "; 
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
   function sql_query_file ( $v63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procjurjudicial ";
     $sql2 = "";
     if($dbwhere==""){
       if($v63_sequencial!=null ){
         $sql2 .= " where procjurjudicial.v63_sequencial = $v63_sequencial "; 
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