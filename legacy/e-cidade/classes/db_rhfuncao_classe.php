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
//CLASSE DA ENTIDADE rhfuncao
class cl_rhfuncao { 
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
   var $rh37_instit = 0; 
   var $rh37_funcao = 0; 
   var $rh37_descr = null; 
   var $rh37_vagas = 0; 
   var $rh37_cbo = null; 
   var $rh37_lei = null; 
   var $rh37_class = null; 
   var $rh37_ativo = 'f'; 
   var $rh37_funcaogrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh37_instit = int4 = Cod. Instituição 
                 rh37_funcao = int4 = Cargo 
                 rh37_descr = varchar(30) = Descrição 
                 rh37_vagas = int4 = Vagas 
                 rh37_cbo = varchar(6) = CBO 
                 rh37_lei = text = Lei 
                 rh37_class = varchar(5) = Classificação 
                 rh37_ativo = bool = Ativo 
                 rh37_funcaogrupo = int4 = Grupo 
                 ";
   //funcao construtor da classe 
   function cl_rhfuncao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhfuncao"); 
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
       $this->rh37_instit = ($this->rh37_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_instit"]:$this->rh37_instit);
       $this->rh37_funcao = ($this->rh37_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_funcao"]:$this->rh37_funcao);
       $this->rh37_descr = ($this->rh37_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_descr"]:$this->rh37_descr);
       $this->rh37_vagas = ($this->rh37_vagas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_vagas"]:$this->rh37_vagas);
       $this->rh37_cbo = ($this->rh37_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_cbo"]:$this->rh37_cbo);
       $this->rh37_lei = ($this->rh37_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_lei"]:$this->rh37_lei);
       $this->rh37_class = ($this->rh37_class == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_class"]:$this->rh37_class);
       $this->rh37_ativo = ($this->rh37_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh37_ativo"]:$this->rh37_ativo);
       $this->rh37_funcaogrupo = ($this->rh37_funcaogrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_funcaogrupo"]:$this->rh37_funcaogrupo);
     }else{
       $this->rh37_instit = ($this->rh37_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_instit"]:$this->rh37_instit);
       $this->rh37_funcao = ($this->rh37_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh37_funcao"]:$this->rh37_funcao);
     }
   }
   // funcao para inclusao
   function incluir ($rh37_funcao,$rh37_instit){ 
      $this->atualizacampos();
     if($this->rh37_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "rh37_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh37_vagas == null ){ 
       $this->rh37_vagas = "0";
     }
     if($this->rh37_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "rh37_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh37_funcaogrupo == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "rh37_funcaogrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->rh37_funcao = $rh37_funcao; 
       $this->rh37_instit = $rh37_instit; 
     if(($this->rh37_funcao == null) || ($this->rh37_funcao == "") ){ 
       $this->erro_sql = " Campo rh37_funcao nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh37_instit == null) || ($this->rh37_instit == "") ){ 
       $this->erro_sql = " Campo rh37_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfuncao(
                                       rh37_instit 
                                      ,rh37_funcao 
                                      ,rh37_descr 
                                      ,rh37_vagas 
                                      ,rh37_cbo 
                                      ,rh37_lei 
                                      ,rh37_class 
                                      ,rh37_ativo 
                                      ,rh37_funcaogrupo 
                       )
                values (
                                $this->rh37_instit 
                               ,$this->rh37_funcao 
                               ,'$this->rh37_descr' 
                               ,$this->rh37_vagas 
                               ,'$this->rh37_cbo' 
                               ,'$this->rh37_lei' 
                               ,'$this->rh37_class' 
                               ,'$this->rh37_ativo' 
                               ,$this->rh37_funcaogrupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de funções ($this->rh37_funcao."-".$this->rh37_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de funções já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de funções ($this->rh37_funcao."-".$this->rh37_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh37_funcao."-".$this->rh37_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh37_funcao,$this->rh37_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7094,'$this->rh37_funcao','I')");
       $resac = db_query("insert into db_acountkey values($acount,9906,'$this->rh37_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1174,9906,'','".AddSlashes(pg_result($resaco,0,'rh37_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7094,'','".AddSlashes(pg_result($resaco,0,'rh37_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7095,'','".AddSlashes(pg_result($resaco,0,'rh37_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7096,'','".AddSlashes(pg_result($resaco,0,'rh37_vagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7097,'','".AddSlashes(pg_result($resaco,0,'rh37_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7098,'','".AddSlashes(pg_result($resaco,0,'rh37_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,7099,'','".AddSlashes(pg_result($resaco,0,'rh37_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,15329,'','".AddSlashes(pg_result($resaco,0,'rh37_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1174,17909,'','".AddSlashes(pg_result($resaco,0,'rh37_funcaogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh37_funcao=null,$rh37_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhfuncao set ";
     $virgula = "";
     if(trim($this->rh37_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_instit"])){ 
       $sql  .= $virgula." rh37_instit = $this->rh37_instit ";
       $virgula = ",";
       if(trim($this->rh37_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh37_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh37_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_funcao"])){ 
       $sql  .= $virgula." rh37_funcao = $this->rh37_funcao ";
       $virgula = ",";
       if(trim($this->rh37_funcao) == null ){ 
         $this->erro_sql = " Campo Cargo nao Informado.";
         $this->erro_campo = "rh37_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh37_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_descr"])){ 
       $sql  .= $virgula." rh37_descr = '$this->rh37_descr' ";
       $virgula = ",";
       if(trim($this->rh37_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "rh37_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh37_vagas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_vagas"])){ 
        if(trim($this->rh37_vagas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh37_vagas"])){ 
           $this->rh37_vagas = "0" ; 
        } 
       $sql  .= $virgula." rh37_vagas = $this->rh37_vagas ";
       $virgula = ",";
     }
     if(trim($this->rh37_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_cbo"])){ 
       $sql  .= $virgula." rh37_cbo = '$this->rh37_cbo' ";
       $virgula = ",";
     }
     if(trim($this->rh37_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_lei"])){ 
       $sql  .= $virgula." rh37_lei = '$this->rh37_lei' ";
       $virgula = ",";
     }
     if(trim($this->rh37_class)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_class"])){ 
       $sql  .= $virgula." rh37_class = '$this->rh37_class' ";
       $virgula = ",";
     }
     if(trim($this->rh37_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_ativo"])){ 
       $sql  .= $virgula." rh37_ativo = '$this->rh37_ativo' ";
       $virgula = ",";
       if(trim($this->rh37_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "rh37_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh37_funcaogrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh37_funcaogrupo"])){ 
       $sql  .= $virgula." rh37_funcaogrupo = $this->rh37_funcaogrupo ";
       $virgula = ",";
       if(trim($this->rh37_funcaogrupo) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "rh37_funcaogrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh37_funcao!=null){
       $sql .= " rh37_funcao = $this->rh37_funcao";
     }
     if($rh37_instit!=null){
       $sql .= " and  rh37_instit = $this->rh37_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh37_funcao,$this->rh37_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7094,'$this->rh37_funcao','A')");
         $resac = db_query("insert into db_acountkey values($acount,9906,'$this->rh37_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_instit"]) || $this->rh37_instit != "")
           $resac = db_query("insert into db_acount values($acount,1174,9906,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_instit'))."','$this->rh37_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_funcao"]) || $this->rh37_funcao != "")
           $resac = db_query("insert into db_acount values($acount,1174,7094,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_funcao'))."','$this->rh37_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_descr"]) || $this->rh37_descr != "")
           $resac = db_query("insert into db_acount values($acount,1174,7095,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_descr'))."','$this->rh37_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_vagas"]) || $this->rh37_vagas != "")
           $resac = db_query("insert into db_acount values($acount,1174,7096,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_vagas'))."','$this->rh37_vagas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_cbo"]) || $this->rh37_cbo != "")
           $resac = db_query("insert into db_acount values($acount,1174,7097,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_cbo'))."','$this->rh37_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_lei"]) || $this->rh37_lei != "")
           $resac = db_query("insert into db_acount values($acount,1174,7098,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_lei'))."','$this->rh37_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_class"]) || $this->rh37_class != "")
           $resac = db_query("insert into db_acount values($acount,1174,7099,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_class'))."','$this->rh37_class',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_ativo"]) || $this->rh37_ativo != "")
           $resac = db_query("insert into db_acount values($acount,1174,15329,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_ativo'))."','$this->rh37_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh37_funcaogrupo"]) || $this->rh37_funcaogrupo != "")
           $resac = db_query("insert into db_acount values($acount,1174,17909,'".AddSlashes(pg_result($resaco,$conresaco,'rh37_funcaogrupo'))."','$this->rh37_funcaogrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funções nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh37_funcao."-".$this->rh37_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funções nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh37_funcao."-".$this->rh37_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh37_funcao."-".$this->rh37_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh37_funcao=null,$rh37_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh37_funcao,$rh37_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7094,'$rh37_funcao','E')");
         $resac = db_query("insert into db_acountkey values($acount,9906,'$rh37_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1174,9906,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7094,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7095,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7096,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_vagas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7097,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7098,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,7099,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_class'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,15329,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1174,17909,'','".AddSlashes(pg_result($resaco,$iresaco,'rh37_funcaogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhfuncao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh37_funcao != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh37_funcao = $rh37_funcao ";
        }
        if($rh37_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh37_instit = $rh37_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de funções nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh37_funcao."-".$rh37_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de funções nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh37_funcao."-".$rh37_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh37_funcao."-".$rh37_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfuncao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh37_funcao=null,$rh37_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfuncao ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhfuncao.rh37_instit";
     $sql .= "      inner join rhfuncaogrupo  on  rhfuncaogrupo.rh100_sequencial = rhfuncao.rh37_funcaogrupo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh37_funcao!=null ){
         $sql2 .= " where rhfuncao.rh37_funcao = $rh37_funcao "; 
       } 
       if($rh37_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhfuncao.rh37_instit = $rh37_instit "; 
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
   function sql_query_file ( $rh37_funcao=null,$rh37_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfuncao ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh37_funcao!=null ){
         $sql2 .= " where rhfuncao.rh37_funcao = $rh37_funcao "; 
       } 
       if($rh37_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhfuncao.rh37_instit = $rh37_instit "; 
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
   function sql_query_cgm ( $rh37_funcao=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhfuncao ";
     $sql .= "      inner join rhpessoal     on rhpessoal.rh01_funcao = rhfuncao.rh37_funcao ";
     $sql .= "      inner join rhpessoalmov  on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
		                                        and rhpessoalmov.rh02_anousu = ".db_anofolha()."
		                                        and rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
		                                        and rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ";
     $sql .= "      inner join rhregime  on rhregime.rh30_codreg  = rhpessoalmov.rh02_codreg
		                                    and rhregime.rh30_instit  = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm ";
     $sql .= "      inner join rhlota    on rhlota.r70_codigo     = rhpessoalmov.rh02_lota
		                                    and rhlota.r70_instit     = rhpessoalmov.rh02_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh37_funcao!=null ){
         $sql2 .= " where rhfuncao.rh37_funcao = $rh37_funcao "; 
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