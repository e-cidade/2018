<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE solicitapendencia
class cl_solicitapendencia { 
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
   var $pc91_sequencial = 0; 
   var $pc91_solicita = 0; 
   var $pc91_usuario = 0; 
   var $pc91_pendencia = null; 
   var $pc91_datainclusao_dia = null; 
   var $pc91_datainclusao_mes = null; 
   var $pc91_datainclusao_ano = null; 
   var $pc91_datainclusao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc91_sequencial = int4 = Código 
                 pc91_solicita = int4 = Solicitação 
                 pc91_usuario = int4 = Usuário 
                 pc91_pendencia = text = Pendencia 
                 pc91_datainclusao = date = Data Inclusao 
                 ";
   //funcao construtor da classe 
   function cl_solicitapendencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitapendencia"); 
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
       $this->pc91_sequencial = ($this->pc91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_sequencial"]:$this->pc91_sequencial);
       $this->pc91_solicita = ($this->pc91_solicita == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_solicita"]:$this->pc91_solicita);
       $this->pc91_usuario = ($this->pc91_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_usuario"]:$this->pc91_usuario);
       $this->pc91_pendencia = ($this->pc91_pendencia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_pendencia"]:$this->pc91_pendencia);
       if($this->pc91_datainclusao == ""){
         $this->pc91_datainclusao_dia = ($this->pc91_datainclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_dia"]:$this->pc91_datainclusao_dia);
         $this->pc91_datainclusao_mes = ($this->pc91_datainclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_mes"]:$this->pc91_datainclusao_mes);
         $this->pc91_datainclusao_ano = ($this->pc91_datainclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_ano"]:$this->pc91_datainclusao_ano);
         if($this->pc91_datainclusao_dia != ""){
            $this->pc91_datainclusao = $this->pc91_datainclusao_ano."-".$this->pc91_datainclusao_mes."-".$this->pc91_datainclusao_dia;
         }
       }
     }else{
       $this->pc91_sequencial = ($this->pc91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc91_sequencial"]:$this->pc91_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc91_sequencial){ 
      $this->atualizacampos();
     if($this->pc91_solicita == null ){ 
       $this->erro_sql = " Campo Solicitação nao Informado.";
       $this->erro_campo = "pc91_solicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc91_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "pc91_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc91_pendencia == null ){ 
       $this->erro_sql = " Campo Pendencia nao Informado.";
       $this->erro_campo = "pc91_pendencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc91_datainclusao == null ){ 
       $this->erro_sql = " Campo Data Inclusao nao Informado.";
       $this->erro_campo = "pc91_datainclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc91_sequencial == "" || $pc91_sequencial == null ){
       $result = db_query("select nextval('solicitapendencia_pc91_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitapendencia_pc91_sequencial_seq do campo: pc91_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc91_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solicitapendencia_pc91_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc91_sequencial)){
         $this->erro_sql = " Campo pc91_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc91_sequencial = $pc91_sequencial; 
       }
     }
     if(($this->pc91_sequencial == null) || ($this->pc91_sequencial == "") ){ 
       $this->erro_sql = " Campo pc91_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitapendencia(
                                       pc91_sequencial 
                                      ,pc91_solicita 
                                      ,pc91_usuario 
                                      ,pc91_pendencia 
                                      ,pc91_datainclusao 
                       )
                values (
                                $this->pc91_sequencial 
                               ,$this->pc91_solicita 
                               ,$this->pc91_usuario 
                               ,'$this->pc91_pendencia' 
                               ,".($this->pc91_datainclusao == "null" || $this->pc91_datainclusao == ""?"null":"'".$this->pc91_datainclusao."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pendencias da Solicitação ($this->pc91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pendencias da Solicitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pendencias da Solicitação ($this->pc91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc91_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc91_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18823,'$this->pc91_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3338,18823,'','".AddSlashes(pg_result($resaco,0,'pc91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3338,18824,'','".AddSlashes(pg_result($resaco,0,'pc91_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3338,18825,'','".AddSlashes(pg_result($resaco,0,'pc91_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3338,18826,'','".AddSlashes(pg_result($resaco,0,'pc91_pendencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3338,18827,'','".AddSlashes(pg_result($resaco,0,'pc91_datainclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc91_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update solicitapendencia set ";
     $virgula = "";
     if(trim($this->pc91_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc91_sequencial"])){ 
       $sql  .= $virgula." pc91_sequencial = $this->pc91_sequencial ";
       $virgula = ",";
       if(trim($this->pc91_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "pc91_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc91_solicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc91_solicita"])){ 
       $sql  .= $virgula." pc91_solicita = $this->pc91_solicita ";
       $virgula = ",";
       if(trim($this->pc91_solicita) == null ){ 
         $this->erro_sql = " Campo Solicitação nao Informado.";
         $this->erro_campo = "pc91_solicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc91_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc91_usuario"])){ 
       $sql  .= $virgula." pc91_usuario = $this->pc91_usuario ";
       $virgula = ",";
       if(trim($this->pc91_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "pc91_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc91_pendencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc91_pendencia"])){ 
       $sql  .= $virgula." pc91_pendencia = '$this->pc91_pendencia' ";
       $virgula = ",";
       if(trim($this->pc91_pendencia) == null ){ 
         $this->erro_sql = " Campo Pendencia nao Informado.";
         $this->erro_campo = "pc91_pendencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc91_datainclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_dia"] !="") ){ 
       $sql  .= $virgula." pc91_datainclusao = '$this->pc91_datainclusao' ";
       $virgula = ",";
       if(trim($this->pc91_datainclusao) == null ){ 
         $this->erro_sql = " Campo Data Inclusao nao Informado.";
         $this->erro_campo = "pc91_datainclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao_dia"])){ 
         $sql  .= $virgula." pc91_datainclusao = null ";
         $virgula = ",";
         if(trim($this->pc91_datainclusao) == null ){ 
           $this->erro_sql = " Campo Data Inclusao nao Informado.";
           $this->erro_campo = "pc91_datainclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($pc91_sequencial!=null){
       $sql .= " pc91_sequencial = $this->pc91_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc91_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18823,'$this->pc91_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_sequencial"]) || $this->pc91_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3338,18823,'".AddSlashes(pg_result($resaco,$conresaco,'pc91_sequencial'))."','$this->pc91_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_solicita"]) || $this->pc91_solicita != "")
           $resac = db_query("insert into db_acount values($acount,3338,18824,'".AddSlashes(pg_result($resaco,$conresaco,'pc91_solicita'))."','$this->pc91_solicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_usuario"]) || $this->pc91_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3338,18825,'".AddSlashes(pg_result($resaco,$conresaco,'pc91_usuario'))."','$this->pc91_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_pendencia"]) || $this->pc91_pendencia != "")
           $resac = db_query("insert into db_acount values($acount,3338,18826,'".AddSlashes(pg_result($resaco,$conresaco,'pc91_pendencia'))."','$this->pc91_pendencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc91_datainclusao"]) || $this->pc91_datainclusao != "")
           $resac = db_query("insert into db_acount values($acount,3338,18827,'".AddSlashes(pg_result($resaco,$conresaco,'pc91_datainclusao'))."','$this->pc91_datainclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias da Solicitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias da Solicitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc91_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc91_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18823,'$pc91_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3338,18823,'','".AddSlashes(pg_result($resaco,$iresaco,'pc91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3338,18824,'','".AddSlashes(pg_result($resaco,$iresaco,'pc91_solicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3338,18825,'','".AddSlashes(pg_result($resaco,$iresaco,'pc91_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3338,18826,'','".AddSlashes(pg_result($resaco,$iresaco,'pc91_pendencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3338,18827,'','".AddSlashes(pg_result($resaco,$iresaco,'pc91_datainclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitapendencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc91_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc91_sequencial = $pc91_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pendencias da Solicitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pendencias da Solicitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc91_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitapendencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitapendencia ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicitapendencia.pc91_usuario";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitapendencia.pc91_solicita";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     // linha comentada pois gerava conflito na query fazendo dois inners na mesma tabela
     //$sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = solicita.pc10_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      inner join solicitacaotipo  on  solicitacaotipo.pc52_sequencial = solicita.pc10_solicitacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc91_sequencial!=null ){
         $sql2 .= " where solicitapendencia.pc91_sequencial = $pc91_sequencial "; 
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
  
  function sql_query_itens_solicitacao ( $pc91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitapendencia ";
    $sql .= "      inner join solicita  on solicita.pc10_numero  = solicitapendencia.pc91_solicita ";
    $sql .= "      inner join solicitem on solicitem.pc11_numero = solicita.pc10_numero           ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc91_sequencial!=null ){
        $sql2 .= " where solicitapendencia.pc91_sequencial = $pc91_sequencial ";
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
   function sql_query_file ( $pc91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solicitapendencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc91_sequencial!=null ){
         $sql2 .= " where solicitapendencia.pc91_sequencial = $pc91_sequencial "; 
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