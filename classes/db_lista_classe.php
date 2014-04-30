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

//MODULO: caixa
//CLASSE DA ENTIDADE lista
class cl_lista { 
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
   var $k60_codigo = 0; 
   var $k60_descr = null; 
   var $k60_tipo = null; 
   var $k60_datadeb_dia = null; 
   var $k60_datadeb_mes = null; 
   var $k60_datadeb_ano = null; 
   var $k60_datadeb = null; 
   var $k60_filtros = null; 
   var $k60_usuario = 0; 
   var $k60_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k60_codigo = int4 = Código da lista 
                 k60_descr = varchar(100) = Descrição 
                 k60_tipo = varchar(1) = Tipo da Lista 
                 k60_datadeb = date = Data Base do Débito 
                 k60_filtros = text = Filtros 
                 k60_usuario = int4 = Usuário 
                 k60_instit = int4 = Cód. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_lista() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lista"); 
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
       $this->k60_codigo = ($this->k60_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_codigo"]:$this->k60_codigo);
       $this->k60_descr = ($this->k60_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_descr"]:$this->k60_descr);
       $this->k60_tipo = ($this->k60_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_tipo"]:$this->k60_tipo);
       if($this->k60_datadeb == ""){
         $this->k60_datadeb_dia = ($this->k60_datadeb_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_datadeb_dia"]:$this->k60_datadeb_dia);
         $this->k60_datadeb_mes = ($this->k60_datadeb_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_datadeb_mes"]:$this->k60_datadeb_mes);
         $this->k60_datadeb_ano = ($this->k60_datadeb_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_datadeb_ano"]:$this->k60_datadeb_ano);
         if($this->k60_datadeb_dia != ""){
            $this->k60_datadeb = $this->k60_datadeb_ano."-".$this->k60_datadeb_mes."-".$this->k60_datadeb_dia;
         }
       }
       $this->k60_filtros = ($this->k60_filtros == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_filtros"]:$this->k60_filtros);
       $this->k60_usuario = ($this->k60_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_usuario"]:$this->k60_usuario);
       $this->k60_instit = ($this->k60_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_instit"]:$this->k60_instit);
     }else{
       $this->k60_codigo = ($this->k60_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k60_codigo"]:$this->k60_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($k60_codigo){ 
      $this->atualizacampos();
     if($this->k60_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "k60_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k60_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Lista nao Informado.";
       $this->erro_campo = "k60_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k60_datadeb == null ){ 
       $this->erro_sql = " Campo Data Base do Débito nao Informado.";
       $this->erro_campo = "k60_datadeb_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k60_filtros == null ){ 
       $this->erro_sql = " Campo Filtros nao Informado.";
       $this->erro_campo = "k60_filtros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k60_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k60_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k60_instit == null ){ 
       $this->erro_sql = " Campo Cód. Instituição nao Informado.";
       $this->erro_campo = "k60_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k60_codigo == "" || $k60_codigo == null ){
       $result = db_query("select nextval('lista_k60_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lista_k60_codigo_seq do campo: k60_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k60_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lista_k60_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k60_codigo)){
         $this->erro_sql = " Campo k60_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k60_codigo = $k60_codigo; 
       }
     }
     if(($this->k60_codigo == null) || ($this->k60_codigo == "") ){ 
       $this->erro_sql = " Campo k60_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lista(
                                       k60_codigo 
                                      ,k60_descr 
                                      ,k60_tipo 
                                      ,k60_datadeb 
                                      ,k60_filtros 
                                      ,k60_usuario 
                                      ,k60_instit 
                       )
                values (
                                $this->k60_codigo 
                               ,'$this->k60_descr' 
                               ,'$this->k60_tipo' 
                               ,".($this->k60_datadeb == "null" || $this->k60_datadeb == ""?"null":"'".$this->k60_datadeb."'")." 
                               ,'$this->k60_filtros' 
                               ,$this->k60_usuario 
                               ,$this->k60_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lista  ($this->k60_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lista  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lista  ($this->k60_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k60_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k60_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4734,'$this->k60_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,631,4734,'','".AddSlashes(pg_result($resaco,0,'k60_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,4735,'','".AddSlashes(pg_result($resaco,0,'k60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,4736,'','".AddSlashes(pg_result($resaco,0,'k60_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,4737,'','".AddSlashes(pg_result($resaco,0,'k60_datadeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,9917,'','".AddSlashes(pg_result($resaco,0,'k60_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,9918,'','".AddSlashes(pg_result($resaco,0,'k60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,631,10724,'','".AddSlashes(pg_result($resaco,0,'k60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k60_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lista set ";
     $virgula = "";
     if(trim($this->k60_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_codigo"])){ 
       $sql  .= $virgula." k60_codigo = $this->k60_codigo ";
       $virgula = ",";
       if(trim($this->k60_codigo) == null ){ 
         $this->erro_sql = " Campo Código da lista nao Informado.";
         $this->erro_campo = "k60_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k60_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_descr"])){ 
       $sql  .= $virgula." k60_descr = '$this->k60_descr' ";
       $virgula = ",";
       if(trim($this->k60_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "k60_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k60_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_tipo"])){ 
       $sql  .= $virgula." k60_tipo = '$this->k60_tipo' ";
       $virgula = ",";
       if(trim($this->k60_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Lista nao Informado.";
         $this->erro_campo = "k60_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k60_datadeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_datadeb_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k60_datadeb_dia"] !="") ){ 
       $sql  .= $virgula." k60_datadeb = '$this->k60_datadeb' ";
       $virgula = ",";
       if(trim($this->k60_datadeb) == null ){ 
         $this->erro_sql = " Campo Data Base do Débito nao Informado.";
         $this->erro_campo = "k60_datadeb_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k60_datadeb_dia"])){ 
         $sql  .= $virgula." k60_datadeb = null ";
         $virgula = ",";
         if(trim($this->k60_datadeb) == null ){ 
           $this->erro_sql = " Campo Data Base do Débito nao Informado.";
           $this->erro_campo = "k60_datadeb_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k60_filtros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_filtros"])){ 
       $sql  .= $virgula." k60_filtros = '$this->k60_filtros' ";
       $virgula = ",";
       if(trim($this->k60_filtros) == null ){ 
         $this->erro_sql = " Campo Filtros nao Informado.";
         $this->erro_campo = "k60_filtros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k60_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_usuario"])){ 
       $sql  .= $virgula." k60_usuario = $this->k60_usuario ";
       $virgula = ",";
       if(trim($this->k60_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k60_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k60_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k60_instit"])){ 
       $sql  .= $virgula." k60_instit = $this->k60_instit ";
       $virgula = ",";
       if(trim($this->k60_instit) == null ){ 
         $this->erro_sql = " Campo Cód. Instituição nao Informado.";
         $this->erro_campo = "k60_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k60_codigo!=null){
       $sql .= " k60_codigo = $this->k60_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k60_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4734,'$this->k60_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_codigo"]))
           $resac = db_query("insert into db_acount values($acount,631,4734,'".AddSlashes(pg_result($resaco,$conresaco,'k60_codigo'))."','$this->k60_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_descr"]))
           $resac = db_query("insert into db_acount values($acount,631,4735,'".AddSlashes(pg_result($resaco,$conresaco,'k60_descr'))."','$this->k60_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_tipo"]))
           $resac = db_query("insert into db_acount values($acount,631,4736,'".AddSlashes(pg_result($resaco,$conresaco,'k60_tipo'))."','$this->k60_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_datadeb"]))
           $resac = db_query("insert into db_acount values($acount,631,4737,'".AddSlashes(pg_result($resaco,$conresaco,'k60_datadeb'))."','$this->k60_datadeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_filtros"]))
           $resac = db_query("insert into db_acount values($acount,631,9917,'".AddSlashes(pg_result($resaco,$conresaco,'k60_filtros'))."','$this->k60_filtros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_usuario"]))
           $resac = db_query("insert into db_acount values($acount,631,9918,'".AddSlashes(pg_result($resaco,$conresaco,'k60_usuario'))."','$this->k60_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k60_instit"]))
           $resac = db_query("insert into db_acount values($acount,631,10724,'".AddSlashes(pg_result($resaco,$conresaco,'k60_instit'))."','$this->k60_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lista  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k60_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lista  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k60_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k60_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4734,'$k60_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,631,4734,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,4735,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,4736,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,4737,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_datadeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,9917,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,9918,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,631,10724,'','".AddSlashes(pg_result($resaco,$iresaco,'k60_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lista
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k60_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k60_codigo = $k60_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lista  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k60_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lista  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k60_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k60_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lista";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k60_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lista ";
     $sql .= "      inner join db_config  on  db_config.codigo = lista.k60_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lista.k60_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($k60_codigo!=null ){
         $sql2 .= " where lista.k60_codigo = $k60_codigo "; 
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
   function sql_query_file ( $k60_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lista ";
     $sql2 = "";
     if($dbwhere==""){
       if($k60_codigo!=null ){
         $sql2 .= " where lista.k60_codigo = $k60_codigo "; 
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
  
   function sql_query_prescricao ( $k60_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lista ";
     $sql .= "      inner join prescricaolista  on  prescricaolista.k122_lista = lista.k60_codigo";
     $sql .= "      inner join prescricao  on  prescricao.k31_codigo = prescricaolista.k122_prescricao";

     $sql2 = "";
     if($dbwhere==""){
       if($k60_codigo!=null ){
         $sql2 .= " where lista.k60_codigo = $k60_codigo "; 
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