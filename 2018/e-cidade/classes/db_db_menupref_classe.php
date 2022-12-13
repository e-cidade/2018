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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_menupref
class cl_db_menupref { 
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
   var $m_codigo = 0; 
   var $m_descricao = null; 
   var $m_arquivo = null; 
   var $m_imgs = null; 
   var $m_ativo = null; 
   var $m_publico = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m_codigo = int4 = Código 
                 m_descricao = text = Descrição 
                 m_arquivo = varchar(100) = Arquivo 
                 m_imgs = varchar(200) = Imagens 
                 m_ativo = char(1) = Ativo 
                 m_publico = bool = Público 
                 ";
   //funcao construtor da classe 
   function cl_db_menupref() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_menupref"); 
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
       $this->m_codigo = ($this->m_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m_codigo"]:$this->m_codigo);
       $this->m_descricao = ($this->m_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["m_descricao"]:$this->m_descricao);
       $this->m_arquivo = ($this->m_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["m_arquivo"]:$this->m_arquivo);
       $this->m_imgs = ($this->m_imgs == ""?@$GLOBALS["HTTP_POST_VARS"]["m_imgs"]:$this->m_imgs);
       $this->m_ativo = ($this->m_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["m_ativo"]:$this->m_ativo);
       $this->m_publico = ($this->m_publico == "f"?@$GLOBALS["HTTP_POST_VARS"]["m_publico"]:$this->m_publico);
     }else{
       $this->m_codigo = ($this->m_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m_codigo"]:$this->m_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m_codigo){ 
      $this->atualizacampos();
     if($this->m_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "m_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "m_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m_imgs == null ){ 
       $this->erro_sql = " Campo Imagens nao Informado.";
       $this->erro_campo = "m_imgs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "m_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m_publico == null ){ 
       $this->erro_sql = " Campo Público nao Informado.";
       $this->erro_campo = "m_publico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m_codigo = $m_codigo; 
     if(($this->m_codigo == null) || ($this->m_codigo == "") ){ 
       $this->erro_sql = " Campo m_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_menupref(
                                       m_codigo 
                                      ,m_descricao 
                                      ,m_arquivo 
                                      ,m_imgs 
                                      ,m_ativo 
                                      ,m_publico 
                       )
                values (
                                $this->m_codigo 
                               ,'$this->m_descricao' 
                               ,'$this->m_arquivo' 
                               ,'$this->m_imgs' 
                               ,'$this->m_ativo' 
                               ,'$this->m_publico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Menu Prefeitura ($this->m_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Menu Prefeitura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Menu Prefeitura ($this->m_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1078,'$this->m_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,187,1078,'','".AddSlashes(pg_result($resaco,0,'m_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,187,1079,'','".AddSlashes(pg_result($resaco,0,'m_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,187,1080,'','".AddSlashes(pg_result($resaco,0,'m_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,187,1071,'','".AddSlashes(pg_result($resaco,0,'m_imgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,187,1072,'','".AddSlashes(pg_result($resaco,0,'m_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,187,4745,'','".AddSlashes(pg_result($resaco,0,'m_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_menupref set ";
     $virgula = "";
     if(trim($this->m_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_codigo"])){ 
        if(trim($this->m_codigo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["m_codigo"])){ 
           $this->m_codigo = "0" ; 
        } 
       $sql  .= $virgula." m_codigo = $this->m_codigo ";
       $virgula = ",";
       if(trim($this->m_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_descricao"])){ 
       $sql  .= $virgula." m_descricao = '$this->m_descricao' ";
       $virgula = ",";
       if(trim($this->m_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "m_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_arquivo"])){ 
       $sql  .= $virgula." m_arquivo = '$this->m_arquivo' ";
       $virgula = ",";
       if(trim($this->m_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "m_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m_imgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_imgs"])){ 
       $sql  .= $virgula." m_imgs = '$this->m_imgs' ";
       $virgula = ",";
       if(trim($this->m_imgs) == null ){ 
         $this->erro_sql = " Campo Imagens nao Informado.";
         $this->erro_campo = "m_imgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_ativo"])){ 
       $sql  .= $virgula." m_ativo = '$this->m_ativo' ";
       $virgula = ",";
       if(trim($this->m_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "m_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m_publico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m_publico"])){ 
       $sql  .= $virgula." m_publico = '$this->m_publico' ";
       $virgula = ",";
       if(trim($this->m_publico) == null ){ 
         $this->erro_sql = " Campo Público nao Informado.";
         $this->erro_campo = "m_publico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m_codigo!=null){
       $sql .= " m_codigo = $this->m_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1078,'$this->m_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_codigo"]))
           $resac = db_query("insert into db_acount values($acount,187,1078,'".AddSlashes(pg_result($resaco,$conresaco,'m_codigo'))."','$this->m_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_descricao"]))
           $resac = db_query("insert into db_acount values($acount,187,1079,'".AddSlashes(pg_result($resaco,$conresaco,'m_descricao'))."','$this->m_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_arquivo"]))
           $resac = db_query("insert into db_acount values($acount,187,1080,'".AddSlashes(pg_result($resaco,$conresaco,'m_arquivo'))."','$this->m_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_imgs"]))
           $resac = db_query("insert into db_acount values($acount,187,1071,'".AddSlashes(pg_result($resaco,$conresaco,'m_imgs'))."','$this->m_imgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_ativo"]))
           $resac = db_query("insert into db_acount values($acount,187,1072,'".AddSlashes(pg_result($resaco,$conresaco,'m_ativo'))."','$this->m_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m_publico"]))
           $resac = db_query("insert into db_acount values($acount,187,4745,'".AddSlashes(pg_result($resaco,$conresaco,'m_publico'))."','$this->m_publico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Menu Prefeitura nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Menu Prefeitura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1078,'$m_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,187,1078,'','".AddSlashes(pg_result($resaco,$iresaco,'m_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,187,1079,'','".AddSlashes(pg_result($resaco,$iresaco,'m_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,187,1080,'','".AddSlashes(pg_result($resaco,$iresaco,'m_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,187,1071,'','".AddSlashes(pg_result($resaco,$iresaco,'m_imgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,187,1072,'','".AddSlashes(pg_result($resaco,$iresaco,'m_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,187,4745,'','".AddSlashes(pg_result($resaco,$iresaco,'m_publico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_menupref
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m_codigo = $m_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Menu Prefeitura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Menu Prefeitura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_menupref";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_menupref ";
     $sql2 = "";
     if($dbwhere==""){
       if($m_codigo!=null ){
         $sql2 .= " where db_menupref.m_codigo = $m_codigo "; 
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
   function sql_queryacesso ( $m_codigo=null,$campos="*",$inner="",$ordem=null,$dbwhere=""){
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
   $sql .= " from db_menupref  $inner ";
   $sql2 = "";
   if($dbwhere==""){
     if($m_codigo!=null ){
       $sql2 .= " where db_menupref.m_codigo = $m_codigo ";
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
   function sql_query_file ( $m_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_menupref ";
     $sql2 = "";
     if($dbwhere==""){
       if($m_codigo!=null ){
         $sql2 .= " where db_menupref.m_codigo = $m_codigo "; 
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