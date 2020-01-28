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

//MODULO: merenda
//CLASSE DA ENTIDADE mer_desper_und
class cl_mer_desper_und { 
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
   var $me23_i_codigo = 0; 
   var $me23_f_quant = 0; 
   var $me23_t_obs = null; 
   var $me23_i_unidade = 0; 
   var $me23_i_desperdicio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me23_i_codigo = int4 = Codigo 
                 me23_f_quant = float4 = Quantidade 
                 me23_t_obs = text = Observação 
                 me23_i_unidade = int4 = Unidade 
                 me23_i_desperdicio = int4 = Codigo Desperdicio 
                 ";
   //funcao construtor da classe 
   function cl_mer_desper_und() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_desper_und"); 
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
       $this->me23_i_codigo = ($this->me23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_i_codigo"]:$this->me23_i_codigo);
       $this->me23_f_quant = ($this->me23_f_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_f_quant"]:$this->me23_f_quant);
       $this->me23_t_obs = ($this->me23_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_t_obs"]:$this->me23_t_obs);
       $this->me23_i_unidade = ($this->me23_i_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_i_unidade"]:$this->me23_i_unidade);
       $this->me23_i_desperdicio = ($this->me23_i_desperdicio == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_i_desperdicio"]:$this->me23_i_desperdicio);
     }else{
       $this->me23_i_codigo = ($this->me23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me23_i_codigo"]:$this->me23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me23_i_codigo){ 
      $this->atualizacampos();
     if($this->me23_f_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "me23_f_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me23_t_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "me23_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me23_i_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "me23_i_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me23_i_desperdicio == null ){ 
       $this->erro_sql = " Campo Codigo Desperdicio nao Informado.";
       $this->erro_campo = "me23_i_desperdicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me23_i_codigo == "" || $me23_i_codigo == null ){
       $result = db_query("select nextval('mer_desper_und_me23_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_desper_und_me23_codigo_seq do campo: me23_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me23_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_desper_und_me23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me23_i_codigo)){
         $this->erro_sql = " Campo me23_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me23_i_codigo = $me23_i_codigo; 
       }
     }
     if(($this->me23_i_codigo == null) || ($this->me23_i_codigo == "") ){ 
       $this->erro_sql = " Campo me23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_desper_und(
                                       me23_i_codigo 
                                      ,me23_f_quant 
                                      ,me23_t_obs 
                                      ,me23_i_unidade 
                                      ,me23_i_desperdicio 
                       )
                values (
                                $this->me23_i_codigo 
                               ,$this->me23_f_quant 
                               ,'$this->me23_t_obs' 
                               ,$this->me23_i_unidade 
                               ,$this->me23_i_desperdicio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Unidade de Desperdicio ($this->me23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Unidade de Desperdicio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Unidade de Desperdicio ($this->me23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me23_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13721,'$this->me23_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2398,13721,'','".AddSlashes(pg_result($resaco,0,'me23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2398,13722,'','".AddSlashes(pg_result($resaco,0,'me23_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2398,13723,'','".AddSlashes(pg_result($resaco,0,'me23_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2398,13724,'','".AddSlashes(pg_result($resaco,0,'me23_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2398,13725,'','".AddSlashes(pg_result($resaco,0,'me23_i_desperdicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me23_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_desper_und set ";
     $virgula = "";
     if(trim($this->me23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me23_i_codigo"])){ 
       $sql  .= $virgula." me23_i_codigo = $this->me23_i_codigo ";
       $virgula = ",";
       if(trim($this->me23_i_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "me23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me23_f_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me23_f_quant"])){ 
       $sql  .= $virgula." me23_f_quant = $this->me23_f_quant ";
       $virgula = ",";
       if(trim($this->me23_f_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "me23_f_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me23_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me23_t_obs"])){ 
       $sql  .= $virgula." me23_t_obs = '$this->me23_t_obs' ";
       $virgula = ",";
       if(trim($this->me23_t_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "me23_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me23_i_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me23_i_unidade"])){ 
       $sql  .= $virgula." me23_i_unidade = $this->me23_i_unidade ";
       $virgula = ",";
       if(trim($this->me23_i_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "me23_i_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me23_i_desperdicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me23_i_desperdicio"])){ 
       $sql  .= $virgula." me23_i_desperdicio = $this->me23_i_desperdicio ";
       $virgula = ",";
       if(trim($this->me23_i_desperdicio) == null ){ 
         $this->erro_sql = " Campo Codigo Desperdicio nao Informado.";
         $this->erro_campo = "me23_i_desperdicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me23_i_codigo!=null){
       $sql .= " me23_i_codigo = $this->me23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13721,'$this->me23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me23_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2398,13721,'".AddSlashes(pg_result($resaco,$conresaco,'me23_i_codigo'))."','$this->me23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me23_f_quant"]))
           $resac = db_query("insert into db_acount values($acount,2398,13722,'".AddSlashes(pg_result($resaco,$conresaco,'me23_f_quant'))."','$this->me23_f_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me23_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,2398,13723,'".AddSlashes(pg_result($resaco,$conresaco,'me23_t_obs'))."','$this->me23_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me23_i_unidade"]))
           $resac = db_query("insert into db_acount values($acount,2398,13724,'".AddSlashes(pg_result($resaco,$conresaco,'me23_i_unidade'))."','$this->me23_i_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me23_i_desperdicio"]))
           $resac = db_query("insert into db_acount values($acount,2398,13725,'".AddSlashes(pg_result($resaco,$conresaco,'me23_i_desperdicio'))."','$this->me23_i_desperdicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade de Desperdicio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidade de Desperdicio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me23_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me23_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13721,'$me23_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2398,13721,'','".AddSlashes(pg_result($resaco,$iresaco,'me23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2398,13722,'','".AddSlashes(pg_result($resaco,$iresaco,'me23_f_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2398,13723,'','".AddSlashes(pg_result($resaco,$iresaco,'me23_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2398,13724,'','".AddSlashes(pg_result($resaco,$iresaco,'me23_i_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2398,13725,'','".AddSlashes(pg_result($resaco,$iresaco,'me23_i_desperdicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_desper_und
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me23_i_codigo = $me23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Unidade de Desperdicio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Unidade de Desperdicio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me23_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_desper_und";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_desper_und ";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = mer_desper_und.me23_i_unidade";
     $sql .= "      inner join mer_desperdicio  on  mer_desperdicio.me22_i_codigo = mer_desper_und.me23_i_desperdicio";
     $sql .= "      inner join mer_cardapiodiaescola  on  mer_cardapiodiaescola.me37_i_codigo = mer_desperdicio.me22_i_cardapiodiaescola";
     $sql .= "      inner join mer_cardapiodia  on  mer_cardapiodia.me12_i_codigo = mer_cardapiodiaescola.me37_i_cardapiodia";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "      inner join mer_tprefeicao  on  mer_tprefeicao.me03_i_codigo = mer_cardapiodia.me12_i_tprefeicao";
     $sql2 = "";
     if($dbwhere==""){
       if($me23_i_codigo!=null ){
         $sql2 .= " where mer_desper_und.me23_i_codigo = $me23_i_codigo "; 
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
   function sql_query_file ( $me23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_desper_und ";
     $sql2 = "";
     if($dbwhere==""){
       if($me23_i_codigo!=null ){
         $sql2 .= " where mer_desper_und.me23_i_codigo = $me23_i_codigo "; 
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