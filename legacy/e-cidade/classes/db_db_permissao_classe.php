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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_permissao
class cl_db_permissao { 
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
   var $id_usuario = 0; 
   var $id_item = 0; 
   var $permissaoativa = null; 
   var $anousu = 0; 
   var $id_instit = 0; 
   var $id_modulo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 id_usuario = int4 = Cod. Usuário 
                 id_item = int4 = Código do ítem 
                 permissaoativa = char(1) = Permissão ativa 
                 anousu = int4 = Exercício 
                 id_instit = int4 = Instituição 
                 id_modulo = int4 = ID módulo 
                 ";
   //funcao construtor da classe 
   function cl_db_permissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_permissao"); 
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
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->permissaoativa = ($this->permissaoativa == ""?@$GLOBALS["HTTP_POST_VARS"]["permissaoativa"]:$this->permissaoativa);
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->id_instit = ($this->id_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["id_instit"]:$this->id_instit);
       $this->id_modulo = ($this->id_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["id_modulo"]:$this->id_modulo);
     }else{
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->id_item = ($this->id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["id_item"]:$this->id_item);
       $this->anousu = ($this->anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["anousu"]:$this->anousu);
       $this->id_instit = ($this->id_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["id_instit"]:$this->id_instit);
       $this->id_modulo = ($this->id_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["id_modulo"]:$this->id_modulo);
     }
   }
   // funcao para inclusao
   function incluir ($id_usuario,$id_item,$anousu,$id_instit,$id_modulo){ 
      $this->atualizacampos();
     if($this->permissaoativa == null ){ 
       $this->erro_sql = " Campo Permissão ativa nao Informado.";
       $this->erro_campo = "permissaoativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->id_usuario = $id_usuario; 
       $this->id_item = $id_item; 
       $this->anousu = $anousu; 
       $this->id_instit = $id_instit; 
       $this->id_modulo = $id_modulo; 
     if(($this->id_usuario == null) || ($this->id_usuario == "") ){ 
       $this->erro_sql = " Campo id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->id_item == null) || ($this->id_item == "") ){ 
       $this->erro_sql = " Campo id_item nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->anousu == null) || ($this->anousu == "") ){ 
       $this->erro_sql = " Campo anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->id_instit == null) || ($this->id_instit == "") ){ 
       $this->erro_sql = " Campo id_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->id_modulo == null) || ($this->id_modulo == "") ){ 
       $this->erro_sql = " Campo id_modulo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_permissao(
                                       id_usuario 
                                      ,id_item 
                                      ,permissaoativa 
                                      ,anousu 
                                      ,id_instit 
                                      ,id_modulo 
                       )
                values (
                                $this->id_usuario 
                               ,$this->id_item 
                               ,'$this->permissaoativa' 
                               ,$this->anousu 
                               ,$this->id_instit 
                               ,$this->id_modulo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Permissão ($this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Permissão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Permissão ($this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario,$this->id_item,$this->anousu,$this->id_instit,$this->id_modulo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','I')");
       $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','I')");
       $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,1020,'$this->id_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,1021,'$this->id_modulo','I')");
       $resac = db_query("insert into db_acount values($acount,174,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,174,821,'','".AddSlashes(pg_result($resaco,0,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,174,1018,'','".AddSlashes(pg_result($resaco,0,'permissaoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,174,1019,'','".AddSlashes(pg_result($resaco,0,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,174,1020,'','".AddSlashes(pg_result($resaco,0,'id_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,174,1021,'','".AddSlashes(pg_result($resaco,0,'id_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($id_usuario=null,$id_item=null,$anousu=null,$id_instit=null,$id_modulo=null) { 
      $this->atualizacampos();
     $sql = " update db_permissao set ";
     $virgula = "";
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item"])){ 
       $sql  .= $virgula." id_item = $this->id_item ";
       $virgula = ",";
       if(trim($this->id_item) == null ){ 
         $this->erro_sql = " Campo Código do ítem nao Informado.";
         $this->erro_campo = "id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->permissaoativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["permissaoativa"])){ 
       $sql  .= $virgula." permissaoativa = '$this->permissaoativa' ";
       $virgula = ",";
       if(trim($this->permissaoativa) == null ){ 
         $this->erro_sql = " Campo Permissão ativa nao Informado.";
         $this->erro_campo = "permissaoativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_instit"])){ 
       $sql  .= $virgula." id_instit = $this->id_instit ";
       $virgula = ",";
       if(trim($this->id_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "id_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_modulo"])){ 
       $sql  .= $virgula." id_modulo = $this->id_modulo ";
       $virgula = ",";
       if(trim($this->id_modulo) == null ){ 
         $this->erro_sql = " Campo ID módulo nao Informado.";
         $this->erro_campo = "id_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_usuario!=null){
       $sql .= " id_usuario = $this->id_usuario";
     }
     if($id_item!=null){
       $sql .= " and  id_item = $this->id_item";
     }
     if($anousu!=null){
       $sql .= " and  anousu = $this->anousu";
     }
     if($id_instit!=null){
       $sql .= " and  id_instit = $this->id_instit";
     }
     if($id_modulo!=null){
       $sql .= " and  id_modulo = $this->id_modulo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario,$this->id_item,$this->anousu,$this->id_instit,$this->id_modulo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','A')");
         $resac = db_query("insert into db_acountkey values($acount,821,'$this->id_item','A')");
         $resac = db_query("insert into db_acountkey values($acount,1019,'$this->anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,1020,'$this->id_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,1021,'$this->id_modulo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]) || $this->id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,174,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_item"]) || $this->id_item != "")
           $resac = db_query("insert into db_acount values($acount,174,821,'".AddSlashes(pg_result($resaco,$conresaco,'id_item'))."','$this->id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["permissaoativa"]) || $this->permissaoativa != "")
           $resac = db_query("insert into db_acount values($acount,174,1018,'".AddSlashes(pg_result($resaco,$conresaco,'permissaoativa'))."','$this->permissaoativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["anousu"]) || $this->anousu != "")
           $resac = db_query("insert into db_acount values($acount,174,1019,'".AddSlashes(pg_result($resaco,$conresaco,'anousu'))."','$this->anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_instit"]) || $this->id_instit != "")
           $resac = db_query("insert into db_acount values($acount,174,1020,'".AddSlashes(pg_result($resaco,$conresaco,'id_instit'))."','$this->id_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_modulo"]) || $this->id_modulo != "")
           $resac = db_query("insert into db_acount values($acount,174,1021,'".AddSlashes(pg_result($resaco,$conresaco,'id_modulo'))."','$this->id_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario."-".$this->id_item."-".$this->anousu."-".$this->id_instit."-".$this->id_modulo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($id_usuario=null,$id_item=null,$anousu=null,$id_instit=null,$id_modulo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_usuario,$id_item,$anousu,$id_instit,$id_modulo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey    values($acount,568 ,'".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."','E')");
         $resac = db_query("insert into db_acountkey    values($acount,821 ,'".AddSlashes(pg_result($resaco,$iresaco,'id_item'))."'   ,'E')");
         $resac = db_query("insert into db_acountkey    values($acount,1019,'".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."'    ,'E')");
         $resac = db_query("insert into db_acountkey    values($acount,1020,'".AddSlashes(pg_result($resaco,$iresaco,'id_instit'))."' ,'E')");
         $resac = db_query("insert into db_acountkey    values($acount,1021,'".AddSlashes(pg_result($resaco,$iresaco,'id_modulo'))."' ,'E')");
         
         $resac = db_query("insert into db_acount values($acount,174,568 ,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,174,821 ,'','".AddSlashes(pg_result($resaco,$iresaco,'id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,174,1018,'','".AddSlashes(pg_result($resaco,$iresaco,'permissaoativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,174,1019,'','".AddSlashes(pg_result($resaco,$iresaco,'anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,174,1020,'','".AddSlashes(pg_result($resaco,$iresaco,'id_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,174,1021,'','".AddSlashes(pg_result($resaco,$iresaco,'id_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_permissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_usuario = $id_usuario ";
        }
        if($id_item != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_item = $id_item ";
        }
        if($anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " anousu = $anousu ";
        }
        if($id_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_instit = $id_instit ";
        }
        if($id_modulo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_modulo = $id_modulo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_usuario."-".$id_item."-".$anousu."-".$id_instit."-".$id_modulo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_usuario."-".$id_item."-".$anousu."-".$id_instit."-".$id_modulo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_usuario."-".$id_item."-".$anousu."-".$id_instit."-".$id_modulo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_permissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $id_usuario=null,$id_item=null,$anousu=null,$id_instit=null,$id_modulo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_permissao.id_usuario = $id_usuario "; 
       } 
       if($id_item!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_item = $id_item "; 
       } 
       if($anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.anousu = $anousu "; 
       } 
       if($id_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_instit = $id_instit "; 
       } 
       if($id_modulo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_modulo = $id_modulo "; 
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
   function sql_query_file ( $id_usuario=null,$id_item=null,$anousu=null,$id_instit=null,$id_modulo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_permissao.id_usuario = $id_usuario "; 
       } 
       if($id_item!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_item = $id_item "; 
       } 
       if($anousu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.anousu = $anousu "; 
       } 
       if($id_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_instit = $id_instit "; 
       } 
       if($id_modulo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_permissao.id_modulo = $id_modulo "; 
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
   function alterar_where( $where=null ) { 
      $this->atualizacampos();
     $sql = " update db_permissao set ";
     $virgula = "";
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_item"])){ 
       $sql  .= $virgula." id_item = $this->id_item ";
       $virgula = ",";
       if(trim($this->id_item) == null ){ 
         $this->erro_sql = " Campo Código do ítem nao Informado.";
         $this->erro_campo = "id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->permissaoativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["permissaoativa"])){ 
       $sql  .= $virgula." permissaoativa = '$this->permissaoativa' ";
       $virgula = ",";
       if(trim($this->permissaoativa) == null ){ 
         $this->erro_sql = " Campo Permissão ativa nao Informado.";
         $this->erro_campo = "permissaoativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["anousu"])){ 
       $sql  .= $virgula." anousu = $this->anousu ";
       $virgula = ",";
       if(trim($this->anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_instit"])){ 
       $sql  .= $virgula." id_instit = $this->id_instit ";
       $virgula = ",";
       if(trim($this->id_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "id_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_modulo"])){ 
       $sql  .= $virgula." id_modulo = $this->id_modulo ";
       $virgula = ",";
       if(trim($this->id_modulo) == null ){ 
         $this->erro_sql = " Campo ID módulo nao Informado.";
         $this->erro_campo = "id_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if ($where != null && $where != "") {
       $sql .= " where $where ";
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
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