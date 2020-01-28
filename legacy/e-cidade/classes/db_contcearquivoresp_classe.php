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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contcearquivoresp
class cl_contcearquivoresp { 
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
   var $c12_sequencial = 0; 
   var $c12_nome = null; 
   var $c12_cargo = null; 
   var $c12_contcearquivo = 0; 
   var $c12_nrodoc = null; 
   var $c12_tipodoc = 0; 
   var $c12_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c12_sequencial = int4 = Codigo sequencial 
                 c12_nome = varchar(30) = Nome 
                 c12_cargo = varchar(30) = Cargo 
                 c12_contcearquivo = int4 = Codigo sequencial 
                 c12_nrodoc = varchar(20) = Numero do documento 
                 c12_tipodoc = int4 = Tipo de documento 
                 c12_tipo = int4 = Tipo de Responsavel 
                 ";
   //funcao construtor da classe 
   function cl_contcearquivoresp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contcearquivoresp"); 
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
       $this->c12_sequencial = ($this->c12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_sequencial"]:$this->c12_sequencial);
       $this->c12_nome = ($this->c12_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_nome"]:$this->c12_nome);
       $this->c12_cargo = ($this->c12_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_cargo"]:$this->c12_cargo);
       $this->c12_contcearquivo = ($this->c12_contcearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_contcearquivo"]:$this->c12_contcearquivo);
       $this->c12_nrodoc = ($this->c12_nrodoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_nrodoc"]:$this->c12_nrodoc);
       $this->c12_tipodoc = ($this->c12_tipodoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_tipodoc"]:$this->c12_tipodoc);
       $this->c12_tipo = ($this->c12_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_tipo"]:$this->c12_tipo);
     }else{
       $this->c12_sequencial = ($this->c12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c12_sequencial"]:$this->c12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c12_sequencial){ 
      $this->atualizacampos();
     if($this->c12_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "c12_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c12_cargo == null ){ 
       $this->erro_sql = " Campo Cargo nao Informado.";
       $this->erro_campo = "c12_cargo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c12_contcearquivo == null ){ 
       $this->erro_sql = " Campo Codigo sequencial nao Informado.";
       $this->erro_campo = "c12_contcearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c12_nrodoc == null ){ 
       $this->erro_sql = " Campo Numero do documento nao Informado.";
       $this->erro_campo = "c12_nrodoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c12_tipodoc == null ){ 
       $this->erro_sql = " Campo Tipo de documento nao Informado.";
       $this->erro_campo = "c12_tipodoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c12_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Responsavel nao Informado.";
       $this->erro_campo = "c12_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c12_sequencial == "" || $c12_sequencial == null ){
       $result = db_query("select nextval('contcearquivoresp_c12_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contcearquivoresp_c12_sequencial_seq do campo: c12_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c12_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from contcearquivoresp_c12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c12_sequencial)){
         $this->erro_sql = " Campo c12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c12_sequencial = $c12_sequencial; 
       }
     }
     if(($this->c12_sequencial == null) || ($this->c12_sequencial == "") ){ 
       $this->erro_sql = " Campo c12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contcearquivoresp(
                                       c12_sequencial 
                                      ,c12_nome 
                                      ,c12_cargo 
                                      ,c12_contcearquivo 
                                      ,c12_nrodoc 
                                      ,c12_tipodoc 
                                      ,c12_tipo 
                       )
                values (
                                $this->c12_sequencial 
                               ,'$this->c12_nome' 
                               ,'$this->c12_cargo' 
                               ,$this->c12_contcearquivo 
                               ,'$this->c12_nrodoc' 
                               ,$this->c12_tipodoc 
                               ,$this->c12_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Responsaveis pela geração do arquivo ($this->c12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Responsaveis pela geração do arquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Responsaveis pela geração do arquivo ($this->c12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c12_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11927,'$this->c12_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2062,11927,'','".AddSlashes(pg_result($resaco,0,'c12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11935,'','".AddSlashes(pg_result($resaco,0,'c12_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11936,'','".AddSlashes(pg_result($resaco,0,'c12_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11928,'','".AddSlashes(pg_result($resaco,0,'c12_contcearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11938,'','".AddSlashes(pg_result($resaco,0,'c12_nrodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11937,'','".AddSlashes(pg_result($resaco,0,'c12_tipodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2062,11929,'','".AddSlashes(pg_result($resaco,0,'c12_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c12_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update contcearquivoresp set ";
     $virgula = "";
     if(trim($this->c12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_sequencial"])){ 
       $sql  .= $virgula." c12_sequencial = $this->c12_sequencial ";
       $virgula = ",";
       if(trim($this->c12_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "c12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_nome"])){ 
       $sql  .= $virgula." c12_nome = '$this->c12_nome' ";
       $virgula = ",";
       if(trim($this->c12_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "c12_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_cargo"])){ 
       $sql  .= $virgula." c12_cargo = '$this->c12_cargo' ";
       $virgula = ",";
       if(trim($this->c12_cargo) == null ){ 
         $this->erro_sql = " Campo Cargo nao Informado.";
         $this->erro_campo = "c12_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_contcearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_contcearquivo"])){ 
       $sql  .= $virgula." c12_contcearquivo = $this->c12_contcearquivo ";
       $virgula = ",";
       if(trim($this->c12_contcearquivo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "c12_contcearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_nrodoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_nrodoc"])){ 
       $sql  .= $virgula." c12_nrodoc = '$this->c12_nrodoc' ";
       $virgula = ",";
       if(trim($this->c12_nrodoc) == null ){ 
         $this->erro_sql = " Campo Numero do documento nao Informado.";
         $this->erro_campo = "c12_nrodoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_tipodoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_tipodoc"])){ 
       $sql  .= $virgula." c12_tipodoc = $this->c12_tipodoc ";
       $virgula = ",";
       if(trim($this->c12_tipodoc) == null ){ 
         $this->erro_sql = " Campo Tipo de documento nao Informado.";
         $this->erro_campo = "c12_tipodoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c12_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c12_tipo"])){ 
       $sql  .= $virgula." c12_tipo = $this->c12_tipo ";
       $virgula = ",";
       if(trim($this->c12_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Responsavel nao Informado.";
         $this->erro_campo = "c12_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c12_sequencial!=null){
       $sql .= " c12_sequencial = $this->c12_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c12_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11927,'$this->c12_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2062,11927,'".AddSlashes(pg_result($resaco,$conresaco,'c12_sequencial'))."','$this->c12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_nome"]))
           $resac = db_query("insert into db_acount values($acount,2062,11935,'".AddSlashes(pg_result($resaco,$conresaco,'c12_nome'))."','$this->c12_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_cargo"]))
           $resac = db_query("insert into db_acount values($acount,2062,11936,'".AddSlashes(pg_result($resaco,$conresaco,'c12_cargo'))."','$this->c12_cargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_contcearquivo"]))
           $resac = db_query("insert into db_acount values($acount,2062,11928,'".AddSlashes(pg_result($resaco,$conresaco,'c12_contcearquivo'))."','$this->c12_contcearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_nrodoc"]))
           $resac = db_query("insert into db_acount values($acount,2062,11938,'".AddSlashes(pg_result($resaco,$conresaco,'c12_nrodoc'))."','$this->c12_nrodoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_tipodoc"]))
           $resac = db_query("insert into db_acount values($acount,2062,11937,'".AddSlashes(pg_result($resaco,$conresaco,'c12_tipodoc'))."','$this->c12_tipodoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c12_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2062,11929,'".AddSlashes(pg_result($resaco,$conresaco,'c12_tipo'))."','$this->c12_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Responsaveis pela geração do arquivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Responsaveis pela geração do arquivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c12_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c12_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11927,'$c12_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2062,11927,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11935,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11936,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11928,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_contcearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11938,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_nrodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11937,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_tipodoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2062,11929,'','".AddSlashes(pg_result($resaco,$iresaco,'c12_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contcearquivoresp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c12_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c12_sequencial = $c12_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Responsaveis pela geração do arquivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Responsaveis pela geração do arquivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:contcearquivoresp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contcearquivoresp ";
     $sql .= "      inner join contcearquivo  on  contcearquivo.c11_sequencial = contcearquivoresp.c12_contcearquivo";
     $sql .= "      inner join db_config  on  db_config.codigo = contcearquivo.c11_instit";
     $sql .= "      inner join concadtce  on  concadtce.c10_sequencial = contcearquivo.c11_concadtce";
     $sql2 = "";
     if($dbwhere==""){
       if($c12_sequencial!=null ){
         $sql2 .= " where contcearquivoresp.c12_sequencial = $c12_sequencial "; 
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
   function sql_query_file ( $c12_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contcearquivoresp ";
     $sql2 = "";
     if($dbwhere==""){
       if($c12_sequencial!=null ){
         $sql2 .= " where contcearquivoresp.c12_sequencial = $c12_sequencial "; 
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