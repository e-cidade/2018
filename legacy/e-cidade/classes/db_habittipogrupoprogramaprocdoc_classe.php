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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habittipogrupoprogramaprocdoc
class cl_habittipogrupoprogramaprocdoc { 
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
   var $ht09_sequencial = 0; 
   var $ht09_habittipogrupoprograma = 0; 
   var $ht09_procdoc = 0; 
   var $ht09_obrigatorio = 'f'; 
   var $ht09_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht09_sequencial = int4 = Sequencial 
                 ht09_habittipogrupoprograma = int4 = Tipo de Grupo 
                 ht09_procdoc = int4 = Documento 
                 ht09_obrigatorio = bool = Obrigatório 
                 ht09_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_habittipogrupoprogramaprocdoc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habittipogrupoprogramaprocdoc"); 
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
       $this->ht09_sequencial = ($this->ht09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht09_sequencial"]:$this->ht09_sequencial);
       $this->ht09_habittipogrupoprograma = ($this->ht09_habittipogrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht09_habittipogrupoprograma"]:$this->ht09_habittipogrupoprograma);
       $this->ht09_procdoc = ($this->ht09_procdoc == ""?@$GLOBALS["HTTP_POST_VARS"]["ht09_procdoc"]:$this->ht09_procdoc);
       $this->ht09_obrigatorio = ($this->ht09_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["ht09_obrigatorio"]:$this->ht09_obrigatorio);
       $this->ht09_obs = ($this->ht09_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht09_obs"]:$this->ht09_obs);
     }else{
       $this->ht09_sequencial = ($this->ht09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht09_sequencial"]:$this->ht09_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht09_sequencial){ 
      $this->atualizacampos();
     if($this->ht09_habittipogrupoprograma == null ){ 
       $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
       $this->erro_campo = "ht09_habittipogrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht09_procdoc == null ){ 
       $this->erro_sql = " Campo Documento nao Informado.";
       $this->erro_campo = "ht09_procdoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht09_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "ht09_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht09_sequencial == "" || $ht09_sequencial == null ){
       $result = db_query("select nextval('habittipogrupoprogramaprocdoc_ht09_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habittipogrupoprogramaprocdoc_ht09_sequencial_seq do campo: ht09_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht09_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habittipogrupoprogramaprocdoc_ht09_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht09_sequencial)){
         $this->erro_sql = " Campo ht09_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht09_sequencial = $ht09_sequencial; 
       }
     }
     if(($this->ht09_sequencial == null) || ($this->ht09_sequencial == "") ){ 
       $this->erro_sql = " Campo ht09_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habittipogrupoprogramaprocdoc(
                                       ht09_sequencial 
                                      ,ht09_habittipogrupoprograma 
                                      ,ht09_procdoc 
                                      ,ht09_obrigatorio 
                                      ,ht09_obs 
                       )
                values (
                                $this->ht09_sequencial 
                               ,$this->ht09_habittipogrupoprograma 
                               ,$this->ht09_procdoc 
                               ,'$this->ht09_obrigatorio' 
                               ,'$this->ht09_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Documentos do Tipo de Grupo de Programa ($this->ht09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Documentos do Tipo de Grupo de Programa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Documentos do Tipo de Grupo de Programa ($this->ht09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht09_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht09_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16984,'$this->ht09_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2997,16984,'','".AddSlashes(pg_result($resaco,0,'ht09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2997,16985,'','".AddSlashes(pg_result($resaco,0,'ht09_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2997,16986,'','".AddSlashes(pg_result($resaco,0,'ht09_procdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2997,17081,'','".AddSlashes(pg_result($resaco,0,'ht09_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2997,17082,'','".AddSlashes(pg_result($resaco,0,'ht09_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht09_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habittipogrupoprogramaprocdoc set ";
     $virgula = "";
     if(trim($this->ht09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht09_sequencial"])){ 
       $sql  .= $virgula." ht09_sequencial = $this->ht09_sequencial ";
       $virgula = ",";
       if(trim($this->ht09_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht09_habittipogrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht09_habittipogrupoprograma"])){ 
       $sql  .= $virgula." ht09_habittipogrupoprograma = $this->ht09_habittipogrupoprograma ";
       $virgula = ",";
       if(trim($this->ht09_habittipogrupoprograma) == null ){ 
         $this->erro_sql = " Campo Tipo de Grupo nao Informado.";
         $this->erro_campo = "ht09_habittipogrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht09_procdoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht09_procdoc"])){ 
       $sql  .= $virgula." ht09_procdoc = $this->ht09_procdoc ";
       $virgula = ",";
       if(trim($this->ht09_procdoc) == null ){ 
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "ht09_procdoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht09_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht09_obrigatorio"])){ 
       $sql  .= $virgula." ht09_obrigatorio = '$this->ht09_obrigatorio' ";
       $virgula = ",";
       if(trim($this->ht09_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "ht09_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht09_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht09_obs"])){ 
       $sql  .= $virgula." ht09_obs = '$this->ht09_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ht09_sequencial!=null){
       $sql .= " ht09_sequencial = $this->ht09_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht09_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16984,'$this->ht09_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht09_sequencial"]) || $this->ht09_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2997,16984,'".AddSlashes(pg_result($resaco,$conresaco,'ht09_sequencial'))."','$this->ht09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht09_habittipogrupoprograma"]) || $this->ht09_habittipogrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2997,16985,'".AddSlashes(pg_result($resaco,$conresaco,'ht09_habittipogrupoprograma'))."','$this->ht09_habittipogrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht09_procdoc"]) || $this->ht09_procdoc != "")
           $resac = db_query("insert into db_acount values($acount,2997,16986,'".AddSlashes(pg_result($resaco,$conresaco,'ht09_procdoc'))."','$this->ht09_procdoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht09_obrigatorio"]) || $this->ht09_obrigatorio != "")
           $resac = db_query("insert into db_acount values($acount,2997,17081,'".AddSlashes(pg_result($resaco,$conresaco,'ht09_obrigatorio'))."','$this->ht09_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht09_obs"]) || $this->ht09_obs != "")
           $resac = db_query("insert into db_acount values($acount,2997,17082,'".AddSlashes(pg_result($resaco,$conresaco,'ht09_obs'))."','$this->ht09_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do Tipo de Grupo de Programa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do Tipo de Grupo de Programa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht09_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht09_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16984,'$ht09_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2997,16984,'','".AddSlashes(pg_result($resaco,$iresaco,'ht09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2997,16985,'','".AddSlashes(pg_result($resaco,$iresaco,'ht09_habittipogrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2997,16986,'','".AddSlashes(pg_result($resaco,$iresaco,'ht09_procdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2997,17081,'','".AddSlashes(pg_result($resaco,$iresaco,'ht09_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2997,17082,'','".AddSlashes(pg_result($resaco,$iresaco,'ht09_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habittipogrupoprogramaprocdoc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht09_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht09_sequencial = $ht09_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Documentos do Tipo de Grupo de Programa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Documentos do Tipo de Grupo de Programa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht09_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habittipogrupoprogramaprocdoc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprogramaprocdoc ";
     $sql .= "      inner join procdoc  on  procdoc.p56_coddoc = habittipogrupoprogramaprocdoc.ht09_procdoc";
     $sql .= "      inner join habittipogrupoprograma  on  habittipogrupoprograma.ht02_sequencial = habittipogrupoprogramaprocdoc.ht09_habittipogrupoprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($ht09_sequencial!=null ){
         $sql2 .= " where habittipogrupoprogramaprocdoc.ht09_sequencial = $ht09_sequencial "; 
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
   function sql_query_file ( $ht09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habittipogrupoprogramaprocdoc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht09_sequencial!=null ){
         $sql2 .= " where habittipogrupoprogramaprocdoc.ht09_sequencial = $ht09_sequencial "; 
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