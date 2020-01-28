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

//MODULO: compras
//CLASSE DA ENTIDADE solandpadrao
class cl_solandpadrao { 
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
   var $pc47_codigo = 0; 
   var $pc47_solicitem = 0; 
   var $pc47_ordem = 0; 
   var $pc47_dias = 0; 
   var $pc47_pctipoandam = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc47_codigo = int8 = Código Seq. 
                 pc47_solicitem = int8 = Código do registro 
                 pc47_ordem = int4 = Ordem 
                 pc47_dias = int4 = Dias Aprox. 
                 pc47_pctipoandam = int4 = Código Tipo 
                 ";
   //funcao construtor da classe 
   function cl_solandpadrao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solandpadrao"); 
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
       $this->pc47_codigo = ($this->pc47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_codigo"]:$this->pc47_codigo);
       $this->pc47_solicitem = ($this->pc47_solicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_solicitem"]:$this->pc47_solicitem);
       $this->pc47_ordem = ($this->pc47_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_ordem"]:$this->pc47_ordem);
       $this->pc47_dias = ($this->pc47_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_dias"]:$this->pc47_dias);
       $this->pc47_pctipoandam = ($this->pc47_pctipoandam == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_pctipoandam"]:$this->pc47_pctipoandam);
     }else{
       $this->pc47_codigo = ($this->pc47_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc47_codigo"]:$this->pc47_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc47_codigo){ 
      $this->atualizacampos();
     if($this->pc47_solicitem == null ){ 
       $this->erro_sql = " Campo Código do registro nao Informado.";
       $this->erro_campo = "pc47_solicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc47_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "pc47_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc47_dias == null ){ 
       $this->erro_sql = " Campo Dias Aprox. nao Informado.";
       $this->erro_campo = "pc47_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc47_pctipoandam == null ){ 
       $this->erro_sql = " Campo Código Tipo nao Informado.";
       $this->erro_campo = "pc47_pctipoandam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc47_codigo == "" || $pc47_codigo == null ){
       $result = db_query("select nextval('solandpadrao_pc47_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solandpadrao_pc47_codigo_seq do campo: pc47_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc47_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from solandpadrao_pc47_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc47_codigo)){
         $this->erro_sql = " Campo pc47_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc47_codigo = $pc47_codigo; 
       }
     }
     if(($this->pc47_codigo == null) || ($this->pc47_codigo == "") ){ 
       $this->erro_sql = " Campo pc47_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solandpadrao(
                                       pc47_codigo 
                                      ,pc47_solicitem 
                                      ,pc47_ordem 
                                      ,pc47_dias 
                                      ,pc47_pctipoandam 
                       )
                values (
                                $this->pc47_codigo 
                               ,$this->pc47_solicitem 
                               ,$this->pc47_ordem 
                               ,$this->pc47_dias 
                               ,$this->pc47_pctipoandam 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Andamento Padrão da Solicitação ($this->pc47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Andamento Padrão da Solicitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Andamento Padrão da Solicitação ($this->pc47_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc47_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc47_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7842,'$this->pc47_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1314,7842,'','".AddSlashes(pg_result($resaco,0,'pc47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1314,7843,'','".AddSlashes(pg_result($resaco,0,'pc47_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1314,7844,'','".AddSlashes(pg_result($resaco,0,'pc47_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1314,7845,'','".AddSlashes(pg_result($resaco,0,'pc47_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1314,8000,'','".AddSlashes(pg_result($resaco,0,'pc47_pctipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc47_codigo=null) { 
      $this->atualizacampos();
     $sql = " update solandpadrao set ";
     $virgula = "";
     if(trim($this->pc47_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc47_codigo"])){ 
       $sql  .= $virgula." pc47_codigo = $this->pc47_codigo ";
       $virgula = ",";
       if(trim($this->pc47_codigo) == null ){ 
         $this->erro_sql = " Campo Código Seq. nao Informado.";
         $this->erro_campo = "pc47_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc47_solicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc47_solicitem"])){ 
       $sql  .= $virgula." pc47_solicitem = $this->pc47_solicitem ";
       $virgula = ",";
       if(trim($this->pc47_solicitem) == null ){ 
         $this->erro_sql = " Campo Código do registro nao Informado.";
         $this->erro_campo = "pc47_solicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc47_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc47_ordem"])){ 
       $sql  .= $virgula." pc47_ordem = $this->pc47_ordem ";
       $virgula = ",";
       if(trim($this->pc47_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "pc47_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc47_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc47_dias"])){ 
       $sql  .= $virgula." pc47_dias = $this->pc47_dias ";
       $virgula = ",";
       if(trim($this->pc47_dias) == null ){ 
         $this->erro_sql = " Campo Dias Aprox. nao Informado.";
         $this->erro_campo = "pc47_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc47_pctipoandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc47_pctipoandam"])){ 
       $sql  .= $virgula." pc47_pctipoandam = $this->pc47_pctipoandam ";
       $virgula = ",";
       if(trim($this->pc47_pctipoandam) == null ){ 
         $this->erro_sql = " Campo Código Tipo nao Informado.";
         $this->erro_campo = "pc47_pctipoandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc47_codigo!=null){
       $sql .= " pc47_codigo = $this->pc47_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc47_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7842,'$this->pc47_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc47_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1314,7842,'".AddSlashes(pg_result($resaco,$conresaco,'pc47_codigo'))."','$this->pc47_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc47_solicitem"]))
           $resac = db_query("insert into db_acount values($acount,1314,7843,'".AddSlashes(pg_result($resaco,$conresaco,'pc47_solicitem'))."','$this->pc47_solicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc47_ordem"]))
           $resac = db_query("insert into db_acount values($acount,1314,7844,'".AddSlashes(pg_result($resaco,$conresaco,'pc47_ordem'))."','$this->pc47_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc47_dias"]))
           $resac = db_query("insert into db_acount values($acount,1314,7845,'".AddSlashes(pg_result($resaco,$conresaco,'pc47_dias'))."','$this->pc47_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc47_pctipoandam"]))
           $resac = db_query("insert into db_acount values($acount,1314,8000,'".AddSlashes(pg_result($resaco,$conresaco,'pc47_pctipoandam'))."','$this->pc47_pctipoandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Padrão da Solicitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Padrão da Solicitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc47_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc47_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7842,'$pc47_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1314,7842,'','".AddSlashes(pg_result($resaco,$iresaco,'pc47_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1314,7843,'','".AddSlashes(pg_result($resaco,$iresaco,'pc47_solicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1314,7844,'','".AddSlashes(pg_result($resaco,$iresaco,'pc47_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1314,7845,'','".AddSlashes(pg_result($resaco,$iresaco,'pc47_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1314,8000,'','".AddSlashes(pg_result($resaco,$iresaco,'pc47_pctipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solandpadrao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc47_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc47_codigo = $pc47_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Andamento Padrão da Solicitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc47_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Andamento Padrão da Solicitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc47_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc47_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:solandpadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $pc47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandpadrao ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = solandpadrao.pc47_solicitem";
     $sql .= "      inner join pctipoandam  on  pctipoandam.pc44_codigo = solandpadrao.pc47_pctipoandam";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc47_codigo!=null ){
         $sql2 .= " where solandpadrao.pc47_codigo = $pc47_codigo "; 
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
   function sql_query_depto ( $pc47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandpadrao ";
     $sql .= "      left join solandpadraodepto on pc48_solandpadrao=pc47_codigo";     
     $sql2 = "";
     if($dbwhere==""){
       if($pc47_codigo!=null ){
         $sql2 .= " where solandpadrao.pc47_codigo = $pc47_codigo "; 
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
   function sql_query_file ( $pc47_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from solandpadrao ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc47_codigo!=null ){
         $sql2 .= " where solandpadrao.pc47_codigo = $pc47_codigo "; 
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