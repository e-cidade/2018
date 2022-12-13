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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcsuplemtipo
class cl_orcsuplemtipo { 
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
   var $o48_tiposup = 0; 
   var $o48_descr = null; 
   var $o48_coddocsup = 0; 
   var $o48_coddocred = 0; 
   var $o48_arrecadmaior = 0; 
   var $o48_superavit = 'f'; 
   var $o48_suplcreditoespecial = 0; 
   var $o48_redcreditoespecial = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o48_tiposup = int4 = Tipo Suplementação 
                 o48_descr = varchar(60) = Descrição 
                 o48_coddocsup = int4 = Documento Suplementação 
                 o48_coddocred = int4 = Documento Redução 
                 o48_arrecadmaior = int8 = Arrecadação a Maior 
                 o48_superavit = bool = Superavit de Arrecadação 
                 o48_suplcreditoespecial = int4 = Suplementação por Crédito Especial 
                 o48_redcreditoespecial = int4 = Redução por Crédito Especial 
                 ";
   //funcao construtor da classe 
   function cl_orcsuplemtipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcsuplemtipo"); 
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
       $this->o48_tiposup = ($this->o48_tiposup == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_tiposup"]:$this->o48_tiposup);
       $this->o48_descr = ($this->o48_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_descr"]:$this->o48_descr);
       $this->o48_coddocsup = ($this->o48_coddocsup == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_coddocsup"]:$this->o48_coddocsup);
       $this->o48_coddocred = ($this->o48_coddocred == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_coddocred"]:$this->o48_coddocred);
       $this->o48_arrecadmaior = ($this->o48_arrecadmaior == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_arrecadmaior"]:$this->o48_arrecadmaior);
       $this->o48_superavit = ($this->o48_superavit == "f"?@$GLOBALS["HTTP_POST_VARS"]["o48_superavit"]:$this->o48_superavit);
       $this->o48_suplcreditoespecial = ($this->o48_suplcreditoespecial == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_suplcreditoespecial"]:$this->o48_suplcreditoespecial);
       $this->o48_redcreditoespecial = ($this->o48_redcreditoespecial == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_redcreditoespecial"]:$this->o48_redcreditoespecial);
     }else{
       $this->o48_tiposup = ($this->o48_tiposup == ""?@$GLOBALS["HTTP_POST_VARS"]["o48_tiposup"]:$this->o48_tiposup);
     }
   }
   // funcao para inclusao
   function incluir ($o48_tiposup){ 
      $this->atualizacampos();
     if($this->o48_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o48_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_coddocsup == null ){ 
       $this->erro_sql = " Campo Documento Suplementação nao Informado.";
       $this->erro_campo = "o48_coddocsup";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_coddocred == null ){ 
       $this->erro_sql = " Campo Documento Redução nao Informado.";
       $this->erro_campo = "o48_coddocred";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_arrecadmaior == null ){ 
       $this->erro_sql = " Campo Arrecadação a Maior nao Informado.";
       $this->erro_campo = "o48_arrecadmaior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_superavit == null ){ 
       $this->erro_sql = " Campo Superavit de Arrecadação nao Informado.";
       $this->erro_campo = "o48_superavit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o48_suplcreditoespecial == null ){ 
       $this->o48_suplcreditoespecial = "null";
     }
     if($this->o48_redcreditoespecial == null ){ 
       $this->o48_redcreditoespecial = "null";
     }
       $this->o48_tiposup = $o48_tiposup; 
     if(($this->o48_tiposup == null) || ($this->o48_tiposup == "") ){ 
       $this->erro_sql = " Campo o48_tiposup nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsuplemtipo(
                                       o48_tiposup 
                                      ,o48_descr 
                                      ,o48_coddocsup 
                                      ,o48_coddocred 
                                      ,o48_arrecadmaior 
                                      ,o48_superavit 
                                      ,o48_suplcreditoespecial 
                                      ,o48_redcreditoespecial 
                       )
                values (
                                $this->o48_tiposup 
                               ,'$this->o48_descr' 
                               ,$this->o48_coddocsup 
                               ,$this->o48_coddocred 
                               ,$this->o48_arrecadmaior 
                               ,'$this->o48_superavit' 
                               ,$this->o48_suplcreditoespecial 
                               ,$this->o48_redcreditoespecial 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Suplementação ($this->o48_tiposup) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Suplementação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Suplementação ($this->o48_tiposup) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o48_tiposup;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o48_tiposup));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5352,'$this->o48_tiposup','I')");
       $resac = db_query("insert into db_acount values($acount,775,5352,'','".AddSlashes(pg_result($resaco,0,'o48_tiposup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,5353,'','".AddSlashes(pg_result($resaco,0,'o48_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,5354,'','".AddSlashes(pg_result($resaco,0,'o48_coddocsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,5355,'','".AddSlashes(pg_result($resaco,0,'o48_coddocred'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,6085,'','".AddSlashes(pg_result($resaco,0,'o48_arrecadmaior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,6086,'','".AddSlashes(pg_result($resaco,0,'o48_superavit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,15312,'','".AddSlashes(pg_result($resaco,0,'o48_suplcreditoespecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,775,15313,'','".AddSlashes(pg_result($resaco,0,'o48_redcreditoespecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o48_tiposup=null) { 
      $this->atualizacampos();
     $sql = " update orcsuplemtipo set ";
     $virgula = "";
     if(trim($this->o48_tiposup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_tiposup"])){ 
       $sql  .= $virgula." o48_tiposup = $this->o48_tiposup ";
       $virgula = ",";
       if(trim($this->o48_tiposup) == null ){ 
         $this->erro_sql = " Campo Tipo Suplementação nao Informado.";
         $this->erro_campo = "o48_tiposup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_descr"])){ 
       $sql  .= $virgula." o48_descr = '$this->o48_descr' ";
       $virgula = ",";
       if(trim($this->o48_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o48_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_coddocsup)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_coddocsup"])){ 
       $sql  .= $virgula." o48_coddocsup = $this->o48_coddocsup ";
       $virgula = ",";
       if(trim($this->o48_coddocsup) == null ){ 
         $this->erro_sql = " Campo Documento Suplementação nao Informado.";
         $this->erro_campo = "o48_coddocsup";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_coddocred)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_coddocred"])){ 
       $sql  .= $virgula." o48_coddocred = $this->o48_coddocred ";
       $virgula = ",";
       if(trim($this->o48_coddocred) == null ){ 
         $this->erro_sql = " Campo Documento Redução nao Informado.";
         $this->erro_campo = "o48_coddocred";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_arrecadmaior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_arrecadmaior"])){ 
       $sql  .= $virgula." o48_arrecadmaior = $this->o48_arrecadmaior ";
       $virgula = ",";
       if(trim($this->o48_arrecadmaior) == null ){ 
         $this->erro_sql = " Campo Arrecadação a Maior nao Informado.";
         $this->erro_campo = "o48_arrecadmaior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_superavit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_superavit"])){ 
       $sql  .= $virgula." o48_superavit = '$this->o48_superavit' ";
       $virgula = ",";
       if(trim($this->o48_superavit) == null ){ 
         $this->erro_sql = " Campo Superavit de Arrecadação nao Informado.";
         $this->erro_campo = "o48_superavit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o48_suplcreditoespecial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_suplcreditoespecial"])){ 
        if(trim($this->o48_suplcreditoespecial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o48_suplcreditoespecial"])){ 
           $this->o48_suplcreditoespecial = "null" ; 
        } 
       $sql  .= $virgula." o48_suplcreditoespecial = $this->o48_suplcreditoespecial ";
       $virgula = ",";
     }
     if(trim($this->o48_redcreditoespecial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o48_redcreditoespecial"])){ 
        if(trim($this->o48_redcreditoespecial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o48_redcreditoespecial"])){ 
           $this->o48_redcreditoespecial = "null" ; 
        } 
       $sql  .= $virgula." o48_redcreditoespecial = $this->o48_redcreditoespecial ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o48_tiposup!=null){
       $sql .= " o48_tiposup = $this->o48_tiposup";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o48_tiposup));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5352,'$this->o48_tiposup','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_tiposup"]) || $this->o48_tiposup != "")
           $resac = db_query("insert into db_acount values($acount,775,5352,'".AddSlashes(pg_result($resaco,$conresaco,'o48_tiposup'))."','$this->o48_tiposup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_descr"]) || $this->o48_descr != "")
           $resac = db_query("insert into db_acount values($acount,775,5353,'".AddSlashes(pg_result($resaco,$conresaco,'o48_descr'))."','$this->o48_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_coddocsup"]) || $this->o48_coddocsup != "")
           $resac = db_query("insert into db_acount values($acount,775,5354,'".AddSlashes(pg_result($resaco,$conresaco,'o48_coddocsup'))."','$this->o48_coddocsup',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_coddocred"]) || $this->o48_coddocred != "")
           $resac = db_query("insert into db_acount values($acount,775,5355,'".AddSlashes(pg_result($resaco,$conresaco,'o48_coddocred'))."','$this->o48_coddocred',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_arrecadmaior"]) || $this->o48_arrecadmaior != "")
           $resac = db_query("insert into db_acount values($acount,775,6085,'".AddSlashes(pg_result($resaco,$conresaco,'o48_arrecadmaior'))."','$this->o48_arrecadmaior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_superavit"]) || $this->o48_superavit != "")
           $resac = db_query("insert into db_acount values($acount,775,6086,'".AddSlashes(pg_result($resaco,$conresaco,'o48_superavit'))."','$this->o48_superavit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_suplcreditoespecial"]) || $this->o48_suplcreditoespecial != "")
           $resac = db_query("insert into db_acount values($acount,775,15312,'".AddSlashes(pg_result($resaco,$conresaco,'o48_suplcreditoespecial'))."','$this->o48_suplcreditoespecial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o48_redcreditoespecial"]) || $this->o48_redcreditoespecial != "")
           $resac = db_query("insert into db_acount values($acount,775,15313,'".AddSlashes(pg_result($resaco,$conresaco,'o48_redcreditoespecial'))."','$this->o48_redcreditoespecial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Suplementação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o48_tiposup;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Suplementação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o48_tiposup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o48_tiposup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o48_tiposup=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o48_tiposup));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5352,'$o48_tiposup','E')");
         $resac = db_query("insert into db_acount values($acount,775,5352,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_tiposup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,5353,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,5354,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_coddocsup'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,5355,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_coddocred'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,6085,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_arrecadmaior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,6086,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_superavit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,15312,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_suplcreditoespecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,775,15313,'','".AddSlashes(pg_result($resaco,$iresaco,'o48_redcreditoespecial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcsuplemtipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o48_tiposup != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o48_tiposup = $o48_tiposup ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Suplementação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o48_tiposup;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Suplementação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o48_tiposup;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o48_tiposup;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsuplemtipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o48_tiposup=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemtipo ";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = orcsuplemtipo.o48_coddocsup";
     $sql2 = "";
     if($dbwhere==""){
       if($o48_tiposup!=null ){
         $sql2 .= " where orcsuplemtipo.o48_tiposup = $o48_tiposup "; 
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
   function sql_query_file ( $o48_tiposup=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcsuplemtipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($o48_tiposup!=null ){
         $sql2 .= " where orcsuplemtipo.o48_tiposup = $o48_tiposup "; 
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