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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_requisitanteoutro
class cl_far_requisitanteoutro { 
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
   var $fa39_i_codigo = 0; 
   var $fa39_i_requisitante = 0; 
   var $fa39_c_nome = null; 
   var $fa39_c_ender = null; 
   var $fa39_i_numero = 0; 
   var $fa39_i_ident = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa39_i_codigo = int4 = Código 
                 fa39_i_requisitante = int4 = requisitante 
                 fa39_c_nome = char(100) = Nome 
                 fa39_c_ender = char(100) = Endereço 
                 fa39_i_numero = int4 = Numero 
                 fa39_i_ident = int4 = Identidade 
                 ";
   //funcao construtor da classe 
   function cl_far_requisitanteoutro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_requisitanteoutro"); 
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
       $this->fa39_i_codigo = ($this->fa39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_i_codigo"]:$this->fa39_i_codigo);
       $this->fa39_i_requisitante = ($this->fa39_i_requisitante == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_i_requisitante"]:$this->fa39_i_requisitante);
       $this->fa39_c_nome = ($this->fa39_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_c_nome"]:$this->fa39_c_nome);
       $this->fa39_c_ender = ($this->fa39_c_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_c_ender"]:$this->fa39_c_ender);
       $this->fa39_i_numero = ($this->fa39_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_i_numero"]:$this->fa39_i_numero);
       $this->fa39_i_ident = ($this->fa39_i_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_i_ident"]:$this->fa39_i_ident);
     }else{
       $this->fa39_i_codigo = ($this->fa39_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa39_i_codigo"]:$this->fa39_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa39_i_codigo){ 
      $this->atualizacampos();
      if($this->fa39_i_requisitante == null ){ 
       $this->erro_sql = " Campo requisitante nao Informado.";
       $this->erro_campo = "fa39_i_requisitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa39_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "fa39_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa39_c_ender == null ){ 
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "fa39_c_ender";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa39_i_numero == null ){ 
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "fa39_i_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa39_i_ident == null ){ 
       $this->erro_sql = " Campo Identidade nao Informado.";
       $this->erro_campo = "fa39_i_ident";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa39_i_codigo == "" || $fa39_i_codigo == null ){
       $result = db_query("select nextval('far_requisitanteoutro_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_requisitanteoutro_seq do campo: fa39_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa39_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_requisitanteoutro_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa39_i_codigo)){
         $this->erro_sql = " Campo fa39_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa39_i_codigo = $fa39_i_codigo; 
       }
     }
     if(($this->fa39_i_codigo == null) || ($this->fa39_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa39_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_requisitanteoutro(
                                       fa39_i_codigo 
                                      ,fa39_i_requisitante 
                                      ,fa39_c_nome 
                                      ,fa39_c_ender 
                                      ,fa39_i_numero 
                                      ,fa39_i_ident 
                       )
                values (
                                $this->fa39_i_codigo 
                               ,$this->fa39_i_requisitante 
                               ,'$this->fa39_c_nome' 
                               ,'$this->fa39_c_ender' 
                               ,$this->fa39_i_numero 
                               ,$this->fa39_i_ident 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_requisitanteoutro ($this->fa39_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_requisitanteoutro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_requisitanteoutro ($this->fa39_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa39_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa39_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14864,'$this->fa39_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2617,14864,'','".AddSlashes(pg_result($resaco,0,'fa39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2617,14865,'','".AddSlashes(pg_result($resaco,0,'fa39_i_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2617,14866,'','".AddSlashes(pg_result($resaco,0,'fa39_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2617,14867,'','".AddSlashes(pg_result($resaco,0,'fa39_c_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2617,14868,'','".AddSlashes(pg_result($resaco,0,'fa39_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2617,14869,'','".AddSlashes(pg_result($resaco,0,'fa39_i_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa39_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_requisitanteoutro set ";
     $virgula = "";
     if(trim($this->fa39_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_codigo"])){ 
       $sql  .= $virgula." fa39_i_codigo = $this->fa39_i_codigo ";
       $virgula = ",";
       if(trim($this->fa39_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa39_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa39_i_requisitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_requisitante"])){ 
       $sql  .= $virgula." fa39_i_requisitante = $this->fa39_i_requisitante ";
       $virgula = ",";
       if(trim($this->fa39_i_requisitante) == null ){ 
         $this->erro_sql = " Campo requisitante nao Informado.";
         $this->erro_campo = "fa39_i_requisitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa39_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_c_nome"])){ 
       $sql  .= $virgula." fa39_c_nome = '$this->fa39_c_nome' ";
       $virgula = ",";
       if(trim($this->fa39_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "fa39_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa39_c_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_c_ender"])){ 
       $sql  .= $virgula." fa39_c_ender = '$this->fa39_c_ender' ";
       $virgula = ",";
       if(trim($this->fa39_c_ender) == null ){ 
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "fa39_c_ender";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa39_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_numero"])){ 
       $sql  .= $virgula." fa39_i_numero = $this->fa39_i_numero ";
       $virgula = ",";
       if(trim($this->fa39_i_numero) == null ){ 
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "fa39_i_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa39_i_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_ident"])){ 
       $sql  .= $virgula." fa39_i_ident = $this->fa39_i_ident ";
       $virgula = ",";
       if(trim($this->fa39_i_ident) == null ){ 
         $this->erro_sql = " Campo Identidade nao Informado.";
         $this->erro_campo = "fa39_i_ident";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa39_i_codigo!=null){
       $sql .= " fa39_i_codigo = $this->fa39_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa39_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14864,'$this->fa39_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_codigo"]) || $this->fa39_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2617,14864,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_i_codigo'))."','$this->fa39_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_requisitante"]) || $this->fa39_i_requisitante != "")
           $resac = db_query("insert into db_acount values($acount,2617,14865,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_i_requisitante'))."','$this->fa39_i_requisitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_c_nome"]) || $this->fa39_c_nome != "")
           $resac = db_query("insert into db_acount values($acount,2617,14866,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_c_nome'))."','$this->fa39_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_c_ender"]) || $this->fa39_c_ender != "")
           $resac = db_query("insert into db_acount values($acount,2617,14867,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_c_ender'))."','$this->fa39_c_ender',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_numero"]) || $this->fa39_i_numero != "")
           $resac = db_query("insert into db_acount values($acount,2617,14868,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_i_numero'))."','$this->fa39_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa39_i_ident"]) || $this->fa39_i_ident != "")
           $resac = db_query("insert into db_acount values($acount,2617,14869,'".AddSlashes(pg_result($resaco,$conresaco,'fa39_i_ident'))."','$this->fa39_i_ident',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_requisitanteoutro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa39_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_requisitanteoutro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa39_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa39_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14864,'$fa39_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2617,14864,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2617,14865,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_i_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2617,14866,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2617,14867,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_c_ender'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2617,14868,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2617,14869,'','".AddSlashes(pg_result($resaco,$iresaco,'fa39_i_ident'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_requisitanteoutro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa39_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa39_i_codigo = $fa39_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_requisitanteoutro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa39_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_requisitanteoutro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa39_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa39_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_requisitanteoutro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa39_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_requisitanteoutro ";
     $sql .= "      inner join far_retiradarequisitante  on  far_retiradarequisitante.fa08_i_codigo = far_requisitanteoutro.fa39_i_requisitante";
     $sql .= "      inner join far_retirada  on  far_retirada.fa04_i_codigo = far_retiradarequisitante.fa08_i_retirada";
     $sql2 = "";
     if($dbwhere==""){
       if($fa39_i_codigo!=null ){
         $sql2 .= " where far_requisitanteoutro.fa39_i_codigo = $fa39_i_codigo "; 
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
   function sql_query_file ( $fa39_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_requisitanteoutro ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa39_i_codigo!=null ){
         $sql2 .= " where far_requisitanteoutro.fa39_i_codigo = $fa39_i_codigo "; 
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