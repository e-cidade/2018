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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE vinculos
class cl_vinculos { 
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
   var $h11_codigo = 0; 
   var $h11_funcao = 0; 
   var $h11_lei1 = 0; 
   var $h11_lei2 = 0; 
   var $h11_lei3 = 0; 
   var $h11_lei4 = 0; 
   var $h11_lei5 = 0; 
   var $h11_tipo = null; 
   var $h11_cert01 = null; 
   var $h11_cert02 = null; 
   var $h11_regime = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h11_codigo = int4 = Código do vínculo 
                 h11_funcao = int4 = Codigo da funcao 
                 h11_lei1 = int4 = Número da 1a. Lei 
                 h11_lei2 = int4 = Número da 2a. Lei 
                 h11_lei3 = int4 = Número da 3a. Lei 
                 h11_lei4 = int4 = Número da 4a. Lei 
                 h11_lei5 = int4 = Número da 5a. Lei 
                 h11_tipo = varchar(1) = Código do Tipo 
                 h11_cert01 = varchar(200) = Descrição da certidão 
                 h11_cert02 = varchar(200) = Descrição da certidão 
                 h11_regime = int4 = Regime 
                 ";
   //funcao construtor da classe 
   function cl_vinculos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("vinculos"); 
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
       $this->h11_codigo = ($this->h11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_codigo"]:$this->h11_codigo);
       $this->h11_funcao = ($this->h11_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_funcao"]:$this->h11_funcao);
       $this->h11_lei1 = ($this->h11_lei1 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_lei1"]:$this->h11_lei1);
       $this->h11_lei2 = ($this->h11_lei2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_lei2"]:$this->h11_lei2);
       $this->h11_lei3 = ($this->h11_lei3 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_lei3"]:$this->h11_lei3);
       $this->h11_lei4 = ($this->h11_lei4 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_lei4"]:$this->h11_lei4);
       $this->h11_lei5 = ($this->h11_lei5 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_lei5"]:$this->h11_lei5);
       $this->h11_tipo = ($this->h11_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_tipo"]:$this->h11_tipo);
       $this->h11_cert01 = ($this->h11_cert01 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_cert01"]:$this->h11_cert01);
       $this->h11_cert02 = ($this->h11_cert02 == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_cert02"]:$this->h11_cert02);
       $this->h11_regime = ($this->h11_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_regime"]:$this->h11_regime);
     }else{
       $this->h11_codigo = ($this->h11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h11_codigo"]:$this->h11_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($h11_codigo){ 
      $this->atualizacampos();
     if($this->h11_funcao == null ){ 
       $this->h11_funcao = "0";
     }
     if($this->h11_lei1 == null ){ 
       $this->erro_sql = " Campo Número da 1a. Lei nao Informado.";
       $this->erro_campo = "h11_lei1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h11_lei2 == null ){ 
       $this->h11_lei2 = "0";
     }
     if($this->h11_lei3 == null ){ 
       $this->h11_lei3 = "0";
     }
     if($this->h11_lei4 == null ){ 
       $this->h11_lei4 = "0";
     }
     if($this->h11_lei5 == null ){ 
       $this->h11_lei5 = "0";
     }
     if($this->h11_tipo == null ){ 
       $this->erro_sql = " Campo Código do Tipo nao Informado.";
       $this->erro_campo = "h11_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h11_regime == null ){ 
       $this->erro_sql = " Campo Regime nao Informado.";
       $this->erro_campo = "h11_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h11_codigo == "" || $h11_codigo == null ){
       $result = db_query("select nextval('vinculos_h11_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: vinculos_h11_codigo_seq do campo: h11_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h11_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from vinculos_h11_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h11_codigo)){
         $this->erro_sql = " Campo h11_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h11_codigo = $h11_codigo; 
       }
     }
     if(($this->h11_codigo == null) || ($this->h11_codigo == "") ){ 
       $this->erro_sql = " Campo h11_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into vinculos(
                                       h11_codigo 
                                      ,h11_funcao 
                                      ,h11_lei1 
                                      ,h11_lei2 
                                      ,h11_lei3 
                                      ,h11_lei4 
                                      ,h11_lei5 
                                      ,h11_tipo 
                                      ,h11_cert01 
                                      ,h11_cert02 
                                      ,h11_regime 
                       )
                values (
                                $this->h11_codigo 
                               ,$this->h11_funcao 
                               ,$this->h11_lei1 
                               ,$this->h11_lei2 
                               ,$this->h11_lei3 
                               ,$this->h11_lei4 
                               ,$this->h11_lei5 
                               ,'$this->h11_tipo' 
                               ,'$this->h11_cert01' 
                               ,'$this->h11_cert02' 
                               ,$this->h11_regime 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de vinculos(regimes) relacionado e funcoe ($this->h11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de vinculos(regimes) relacionado e funcoe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de vinculos(regimes) relacionado e funcoe ($this->h11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h11_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h11_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9523,'$this->h11_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,598,9523,'','".AddSlashes(pg_result($resaco,0,'h11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4513,'','".AddSlashes(pg_result($resaco,0,'h11_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4514,'','".AddSlashes(pg_result($resaco,0,'h11_lei1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4515,'','".AddSlashes(pg_result($resaco,0,'h11_lei2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4516,'','".AddSlashes(pg_result($resaco,0,'h11_lei3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4517,'','".AddSlashes(pg_result($resaco,0,'h11_lei4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4518,'','".AddSlashes(pg_result($resaco,0,'h11_lei5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4519,'','".AddSlashes(pg_result($resaco,0,'h11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4520,'','".AddSlashes(pg_result($resaco,0,'h11_cert01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4521,'','".AddSlashes(pg_result($resaco,0,'h11_cert02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,598,4522,'','".AddSlashes(pg_result($resaco,0,'h11_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h11_codigo=null) { 
      $this->atualizacampos();
     $sql = " update vinculos set ";
     $virgula = "";
     if(trim($this->h11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_codigo"])){ 
       $sql  .= $virgula." h11_codigo = $this->h11_codigo ";
       $virgula = ",";
       if(trim($this->h11_codigo) == null ){ 
         $this->erro_sql = " Campo Código do vínculo nao Informado.";
         $this->erro_campo = "h11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h11_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_funcao"])){ 
        if(trim($this->h11_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h11_funcao"])){ 
           $this->h11_funcao = "0" ; 
        } 
       $sql  .= $virgula." h11_funcao = $this->h11_funcao ";
       $virgula = ",";
     }
     if(trim($this->h11_lei1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_lei1"])){ 
       $sql  .= $virgula." h11_lei1 = $this->h11_lei1 ";
       $virgula = ",";
       if(trim($this->h11_lei1) == null ){ 
         $this->erro_sql = " Campo Número da 1a. Lei nao Informado.";
         $this->erro_campo = "h11_lei1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h11_lei2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_lei2"])){ 
        if(trim($this->h11_lei2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h11_lei2"])){ 
           $this->h11_lei2 = "0" ; 
        } 
       $sql  .= $virgula." h11_lei2 = $this->h11_lei2 ";
       $virgula = ",";
     }
     if(trim($this->h11_lei3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_lei3"])){ 
        if(trim($this->h11_lei3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h11_lei3"])){ 
           $this->h11_lei3 = "0" ; 
        } 
       $sql  .= $virgula." h11_lei3 = $this->h11_lei3 ";
       $virgula = ",";
     }
     if(trim($this->h11_lei4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_lei4"])){ 
        if(trim($this->h11_lei4)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h11_lei4"])){ 
           $this->h11_lei4 = "0" ; 
        } 
       $sql  .= $virgula." h11_lei4 = $this->h11_lei4 ";
       $virgula = ",";
     }
     if(trim($this->h11_lei5)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_lei5"])){ 
        if(trim($this->h11_lei5)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h11_lei5"])){ 
           $this->h11_lei5 = "0" ; 
        } 
       $sql  .= $virgula." h11_lei5 = $this->h11_lei5 ";
       $virgula = ",";
     }
     if(trim($this->h11_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_tipo"])){ 
       $sql  .= $virgula." h11_tipo = '$this->h11_tipo' ";
       $virgula = ",";
       if(trim($this->h11_tipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "h11_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h11_cert01)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_cert01"])){ 
       $sql  .= $virgula." h11_cert01 = '$this->h11_cert01' ";
       $virgula = ",";
     }
     if(trim($this->h11_cert02)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_cert02"])){ 
       $sql  .= $virgula." h11_cert02 = '$this->h11_cert02' ";
       $virgula = ",";
     }
     if(trim($this->h11_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h11_regime"])){ 
       $sql  .= $virgula." h11_regime = $this->h11_regime ";
       $virgula = ",";
       if(trim($this->h11_regime) == null ){ 
         $this->erro_sql = " Campo Regime nao Informado.";
         $this->erro_campo = "h11_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($h11_codigo!=null){
       $sql .= " h11_codigo = $this->h11_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h11_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9523,'$this->h11_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_codigo"]))
           $resac = db_query("insert into db_acount values($acount,598,9523,'".AddSlashes(pg_result($resaco,$conresaco,'h11_codigo'))."','$this->h11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_funcao"]))
           $resac = db_query("insert into db_acount values($acount,598,4513,'".AddSlashes(pg_result($resaco,$conresaco,'h11_funcao'))."','$this->h11_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_lei1"]))
           $resac = db_query("insert into db_acount values($acount,598,4514,'".AddSlashes(pg_result($resaco,$conresaco,'h11_lei1'))."','$this->h11_lei1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_lei2"]))
           $resac = db_query("insert into db_acount values($acount,598,4515,'".AddSlashes(pg_result($resaco,$conresaco,'h11_lei2'))."','$this->h11_lei2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_lei3"]))
           $resac = db_query("insert into db_acount values($acount,598,4516,'".AddSlashes(pg_result($resaco,$conresaco,'h11_lei3'))."','$this->h11_lei3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_lei4"]))
           $resac = db_query("insert into db_acount values($acount,598,4517,'".AddSlashes(pg_result($resaco,$conresaco,'h11_lei4'))."','$this->h11_lei4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_lei5"]))
           $resac = db_query("insert into db_acount values($acount,598,4518,'".AddSlashes(pg_result($resaco,$conresaco,'h11_lei5'))."','$this->h11_lei5',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_tipo"]))
           $resac = db_query("insert into db_acount values($acount,598,4519,'".AddSlashes(pg_result($resaco,$conresaco,'h11_tipo'))."','$this->h11_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_cert01"]))
           $resac = db_query("insert into db_acount values($acount,598,4520,'".AddSlashes(pg_result($resaco,$conresaco,'h11_cert01'))."','$this->h11_cert01',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_cert02"]))
           $resac = db_query("insert into db_acount values($acount,598,4521,'".AddSlashes(pg_result($resaco,$conresaco,'h11_cert02'))."','$this->h11_cert02',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h11_regime"]))
           $resac = db_query("insert into db_acount values($acount,598,4522,'".AddSlashes(pg_result($resaco,$conresaco,'h11_regime'))."','$this->h11_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de vinculos(regimes) relacionado e funcoe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de vinculos(regimes) relacionado e funcoe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h11_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h11_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9523,'$h11_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,598,9523,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4513,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4514,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_lei1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4515,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_lei2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4516,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_lei3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4517,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_lei4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4518,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_lei5'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4519,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4520,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_cert01'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4521,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_cert02'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,598,4522,'','".AddSlashes(pg_result($resaco,$iresaco,'h11_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from vinculos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h11_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h11_codigo = $h11_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de vinculos(regimes) relacionado e funcoe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de vinculos(regimes) relacionado e funcoe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h11_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:vinculos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vinculos ";
     $sql .= "      left join leis a  on a.h08_codlei = vinculos.h11_lei1 
                                     and a.h08_tipo   = vinculos.h11_tipo ";
     $sql .= "      left join leis b  on b.h08_codlei = vinculos.h11_lei2
                                     and b.h08_tipo   = vinculos.h11_tipo ";
     $sql .= "      left join leis c  on c.h08_codlei = vinculos.h11_lei3
                                     and c.h08_tipo   = vinculos.h11_tipo ";
     $sql .= "      left join leis d  on d.h08_codlei = vinculos.h11_lei4
                                     and d.h08_tipo   = vinculos.h11_tipo ";
     $sql .= "      left join leis e  on e.h08_codlei = vinculos.h11_lei5
                                     and e.h08_tipo   = vinculos.h11_tipo ";
     $sql .= "      left join rhcargo on rhcargo.rh04_codigo = vinculos.h11_funcao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h11_codigo!=null ){
         $sql2 .= " where vinculos.h11_codigo = $h11_codigo "; 
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
   function sql_query_file ( $h11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from vinculos ";
     $sql2 = "";
     if($dbwhere==""){
       if($h11_codigo!=null ){
         $sql2 .= " where vinculos.h11_codigo = $h11_codigo "; 
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