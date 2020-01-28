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

//MODULO: licitação
//CLASSE DA ENTIDADE liclocal
class cl_liclocal { 
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
   var $l26_codigo = 0; 
   var $l26_lograd = 0; 
   var $l26_numero = 0; 
   var $l26_compl = null; 
   var $l26_bairro = 0; 
   var $l26_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l26_codigo = int4 = Código Sequencial 
                 l26_lograd = int4 = Cód. Logradouro 
                 l26_numero = int4 = Numero 
                 l26_compl = varchar(20) = Compl. 
                 l26_bairro = int4 = Cód. do Bairro 
                 l26_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_liclocal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclocal"); 
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
       $this->l26_codigo = ($this->l26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_codigo"]:$this->l26_codigo);
       $this->l26_lograd = ($this->l26_lograd == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_lograd"]:$this->l26_lograd);
       $this->l26_numero = ($this->l26_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_numero"]:$this->l26_numero);
       $this->l26_compl = ($this->l26_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_compl"]:$this->l26_compl);
       $this->l26_bairro = ($this->l26_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_bairro"]:$this->l26_bairro);
       $this->l26_obs = ($this->l26_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_obs"]:$this->l26_obs);
     }else{
       $this->l26_codigo = ($this->l26_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l26_codigo"]:$this->l26_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l26_codigo){ 
      $this->atualizacampos();
     if($this->l26_lograd == null ){ 
       $this->erro_sql = " Campo Cód. Logradouro nao Informado.";
       $this->erro_campo = "l26_lograd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l26_numero == null ){ 
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "l26_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l26_bairro == null ){ 
       $this->erro_sql = " Campo Cód. do Bairro nao Informado.";
       $this->erro_campo = "l26_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l26_codigo == "" || $l26_codigo == null ){
       $result = db_query("select nextval('liclocal_l26_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclocal_l26_codigo_seq do campo: l26_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l26_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclocal_l26_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l26_codigo)){
         $this->erro_sql = " Campo l26_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l26_codigo = $l26_codigo; 
       }
     }
     if(($this->l26_codigo == null) || ($this->l26_codigo == "") ){ 
       $this->erro_sql = " Campo l26_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclocal(
                                       l26_codigo 
                                      ,l26_lograd 
                                      ,l26_numero 
                                      ,l26_compl 
                                      ,l26_bairro 
                                      ,l26_obs 
                       )
                values (
                                $this->l26_codigo 
                               ,$this->l26_lograd 
                               ,$this->l26_numero 
                               ,'$this->l26_compl' 
                               ,$this->l26_bairro 
                               ,'$this->l26_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Local das Licitações ($this->l26_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Local das Licitações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Local das Licitações ($this->l26_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l26_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l26_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7897,'$this->l26_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1323,7897,'','".AddSlashes(pg_result($resaco,0,'l26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1323,7898,'','".AddSlashes(pg_result($resaco,0,'l26_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1323,7899,'','".AddSlashes(pg_result($resaco,0,'l26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1323,7900,'','".AddSlashes(pg_result($resaco,0,'l26_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1323,7901,'','".AddSlashes(pg_result($resaco,0,'l26_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1323,7914,'','".AddSlashes(pg_result($resaco,0,'l26_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l26_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclocal set ";
     $virgula = "";
     if(trim($this->l26_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_codigo"])){ 
       $sql  .= $virgula." l26_codigo = $this->l26_codigo ";
       $virgula = ",";
       if(trim($this->l26_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "l26_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l26_lograd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_lograd"])){ 
       $sql  .= $virgula." l26_lograd = $this->l26_lograd ";
       $virgula = ",";
       if(trim($this->l26_lograd) == null ){ 
         $this->erro_sql = " Campo Cód. Logradouro nao Informado.";
         $this->erro_campo = "l26_lograd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l26_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_numero"])){ 
       $sql  .= $virgula." l26_numero = $this->l26_numero ";
       $virgula = ",";
       if(trim($this->l26_numero) == null ){ 
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "l26_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l26_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_compl"])){ 
       $sql  .= $virgula." l26_compl = '$this->l26_compl' ";
       $virgula = ",";
     }
     if(trim($this->l26_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_bairro"])){ 
       $sql  .= $virgula." l26_bairro = $this->l26_bairro ";
       $virgula = ",";
       if(trim($this->l26_bairro) == null ){ 
         $this->erro_sql = " Campo Cód. do Bairro nao Informado.";
         $this->erro_campo = "l26_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l26_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l26_obs"])){ 
       $sql  .= $virgula." l26_obs = '$this->l26_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($l26_codigo!=null){
       $sql .= " l26_codigo = $this->l26_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l26_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7897,'$this->l26_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1323,7897,'".AddSlashes(pg_result($resaco,$conresaco,'l26_codigo'))."','$this->l26_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_lograd"]))
           $resac = db_query("insert into db_acount values($acount,1323,7898,'".AddSlashes(pg_result($resaco,$conresaco,'l26_lograd'))."','$this->l26_lograd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_numero"]))
           $resac = db_query("insert into db_acount values($acount,1323,7899,'".AddSlashes(pg_result($resaco,$conresaco,'l26_numero'))."','$this->l26_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_compl"]))
           $resac = db_query("insert into db_acount values($acount,1323,7900,'".AddSlashes(pg_result($resaco,$conresaco,'l26_compl'))."','$this->l26_compl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_bairro"]))
           $resac = db_query("insert into db_acount values($acount,1323,7901,'".AddSlashes(pg_result($resaco,$conresaco,'l26_bairro'))."','$this->l26_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l26_obs"]))
           $resac = db_query("insert into db_acount values($acount,1323,7914,'".AddSlashes(pg_result($resaco,$conresaco,'l26_obs'))."','$this->l26_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local das Licitações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local das Licitações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l26_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l26_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7897,'$l26_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1323,7897,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1323,7898,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_lograd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1323,7899,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1323,7900,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_compl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1323,7901,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1323,7914,'','".AddSlashes(pg_result($resaco,$iresaco,'l26_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclocal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l26_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l26_codigo = $l26_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Local das Licitações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l26_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Local das Licitações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l26_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l26_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclocal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l26_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclocal ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = liclocal.l26_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = liclocal.l26_lograd";
     $sql2 = "";
     if($dbwhere==""){
       if($l26_codigo!=null ){
         $sql2 .= " where liclocal.l26_codigo = $l26_codigo "; 
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
   function sql_query_file ( $l26_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclocal ";
     $sql2 = "";
     if($dbwhere==""){
       if($l26_codigo!=null ){
         $sql2 .= " where liclocal.l26_codigo = $l26_codigo "; 
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