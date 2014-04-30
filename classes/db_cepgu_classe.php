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

//MODULO: protocolo
//CLASSE DA ENTIDADE cepgu
class cl_cepgu { 
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
   var $cp07_codgu = 0; 
   var $cp07_gu = null; 
   var $cp07_cep = null; 
   var $cp07_sigla = null; 
   var $cp07_codlocalidade = 0; 
   var $cp07_codbairro = 0; 
   var $cp07_codlogradouro = 0; 
   var $cp07_adicional = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cp07_codgu = int8 = Codigo GU 
                 cp07_gu = varchar(72) = GU 
                 cp07_cep = varchar(8) = CEP 
                 cp07_sigla = varchar(2) = Sigla Estado 
                 cp07_codlocalidade = int8 = Codigo da Localidade 
                 cp07_codbairro = int8 = Codigo do Bairro 
                 cp07_codlogradouro = int8 = Codigo da Localidade 
                 cp07_adicional = varchar(72) = Adicional 
                 ";
   //funcao construtor da classe 
   function cl_cepgu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cepgu"); 
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
       $this->cp07_codgu = ($this->cp07_codgu == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_codgu"]:$this->cp07_codgu);
       $this->cp07_gu = ($this->cp07_gu == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_gu"]:$this->cp07_gu);
       $this->cp07_cep = ($this->cp07_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_cep"]:$this->cp07_cep);
       $this->cp07_sigla = ($this->cp07_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_sigla"]:$this->cp07_sigla);
       $this->cp07_codlocalidade = ($this->cp07_codlocalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_codlocalidade"]:$this->cp07_codlocalidade);
       $this->cp07_codbairro = ($this->cp07_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_codbairro"]:$this->cp07_codbairro);
       $this->cp07_codlogradouro = ($this->cp07_codlogradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_codlogradouro"]:$this->cp07_codlogradouro);
       $this->cp07_adicional = ($this->cp07_adicional == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_adicional"]:$this->cp07_adicional);
     }else{
       $this->cp07_codgu = ($this->cp07_codgu == ""?@$GLOBALS["HTTP_POST_VARS"]["cp07_codgu"]:$this->cp07_codgu);
     }
   }
   // funcao para inclusao
   function incluir ($cp07_codgu){ 
      $this->atualizacampos();
     if($this->cp07_gu == null ){ 
       $this->erro_sql = " Campo GU nao Informado.";
       $this->erro_campo = "cp07_gu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_cep == null ){ 
       $this->erro_sql = " Campo CEP nao Informado.";
       $this->erro_campo = "cp07_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_sigla == null ){ 
       $this->erro_sql = " Campo Sigla Estado nao Informado.";
       $this->erro_campo = "cp07_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_codlocalidade == null ){ 
       $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
       $this->erro_campo = "cp07_codlocalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_codbairro == null ){ 
       $this->erro_sql = " Campo Codigo do Bairro nao Informado.";
       $this->erro_campo = "cp07_codbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_codlogradouro == null ){ 
       $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
       $this->erro_campo = "cp07_codlogradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cp07_adicional == null ){ 
       $this->erro_sql = " Campo Adicional nao Informado.";
       $this->erro_campo = "cp07_adicional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->cp07_codgu = $cp07_codgu; 
     if(($this->cp07_codgu == null) || ($this->cp07_codgu == "") ){ 
       $this->erro_sql = " Campo cp07_codgu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cepgu(
                                       cp07_codgu 
                                      ,cp07_gu 
                                      ,cp07_cep 
                                      ,cp07_sigla 
                                      ,cp07_codlocalidade 
                                      ,cp07_codbairro 
                                      ,cp07_codlogradouro 
                                      ,cp07_adicional 
                       )
                values (
                                $this->cp07_codgu 
                               ,'$this->cp07_gu' 
                               ,'$this->cp07_cep' 
                               ,'$this->cp07_sigla' 
                               ,$this->cp07_codlocalidade 
                               ,$this->cp07_codbairro 
                               ,$this->cp07_codlogradouro 
                               ,'$this->cp07_adicional' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de GU ($this->cp07_codgu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de GU já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de GU ($this->cp07_codgu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp07_codgu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cp07_codgu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7209,'$this->cp07_codgu','I')");
       $resac = db_query("insert into db_acount values($acount,1198,7209,'','".AddSlashes(pg_result($resaco,0,'cp07_codgu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7210,'','".AddSlashes(pg_result($resaco,0,'cp07_gu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7211,'','".AddSlashes(pg_result($resaco,0,'cp07_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7212,'','".AddSlashes(pg_result($resaco,0,'cp07_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7213,'','".AddSlashes(pg_result($resaco,0,'cp07_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7214,'','".AddSlashes(pg_result($resaco,0,'cp07_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7215,'','".AddSlashes(pg_result($resaco,0,'cp07_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1198,7216,'','".AddSlashes(pg_result($resaco,0,'cp07_adicional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cp07_codgu=null) { 
      $this->atualizacampos();
     $sql = " update cepgu set ";
     $virgula = "";
     if(trim($this->cp07_codgu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_codgu"])){ 
       $sql  .= $virgula." cp07_codgu = $this->cp07_codgu ";
       $virgula = ",";
       if(trim($this->cp07_codgu) == null ){ 
         $this->erro_sql = " Campo Codigo GU nao Informado.";
         $this->erro_campo = "cp07_codgu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_gu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_gu"])){ 
       $sql  .= $virgula." cp07_gu = '$this->cp07_gu' ";
       $virgula = ",";
       if(trim($this->cp07_gu) == null ){ 
         $this->erro_sql = " Campo GU nao Informado.";
         $this->erro_campo = "cp07_gu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_cep"])){ 
       $sql  .= $virgula." cp07_cep = '$this->cp07_cep' ";
       $virgula = ",";
       if(trim($this->cp07_cep) == null ){ 
         $this->erro_sql = " Campo CEP nao Informado.";
         $this->erro_campo = "cp07_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_sigla"])){ 
       $sql  .= $virgula." cp07_sigla = '$this->cp07_sigla' ";
       $virgula = ",";
       if(trim($this->cp07_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla Estado nao Informado.";
         $this->erro_campo = "cp07_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_codlocalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_codlocalidade"])){ 
       $sql  .= $virgula." cp07_codlocalidade = $this->cp07_codlocalidade ";
       $virgula = ",";
       if(trim($this->cp07_codlocalidade) == null ){ 
         $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
         $this->erro_campo = "cp07_codlocalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_codbairro"])){ 
       $sql  .= $virgula." cp07_codbairro = $this->cp07_codbairro ";
       $virgula = ",";
       if(trim($this->cp07_codbairro) == null ){ 
         $this->erro_sql = " Campo Codigo do Bairro nao Informado.";
         $this->erro_campo = "cp07_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_codlogradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_codlogradouro"])){ 
       $sql  .= $virgula." cp07_codlogradouro = $this->cp07_codlogradouro ";
       $virgula = ",";
       if(trim($this->cp07_codlogradouro) == null ){ 
         $this->erro_sql = " Campo Codigo da Localidade nao Informado.";
         $this->erro_campo = "cp07_codlogradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cp07_adicional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cp07_adicional"])){ 
       $sql  .= $virgula." cp07_adicional = '$this->cp07_adicional' ";
       $virgula = ",";
       if(trim($this->cp07_adicional) == null ){ 
         $this->erro_sql = " Campo Adicional nao Informado.";
         $this->erro_campo = "cp07_adicional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cp07_codgu!=null){
       $sql .= " cp07_codgu = $this->cp07_codgu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cp07_codgu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7209,'$this->cp07_codgu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_codgu"]))
           $resac = db_query("insert into db_acount values($acount,1198,7209,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_codgu'))."','$this->cp07_codgu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_gu"]))
           $resac = db_query("insert into db_acount values($acount,1198,7210,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_gu'))."','$this->cp07_gu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_cep"]))
           $resac = db_query("insert into db_acount values($acount,1198,7211,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_cep'))."','$this->cp07_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1198,7212,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_sigla'))."','$this->cp07_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_codlocalidade"]))
           $resac = db_query("insert into db_acount values($acount,1198,7213,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_codlocalidade'))."','$this->cp07_codlocalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_codbairro"]))
           $resac = db_query("insert into db_acount values($acount,1198,7214,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_codbairro'))."','$this->cp07_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_codlogradouro"]))
           $resac = db_query("insert into db_acount values($acount,1198,7215,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_codlogradouro'))."','$this->cp07_codlogradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cp07_adicional"]))
           $resac = db_query("insert into db_acount values($acount,1198,7216,'".AddSlashes(pg_result($resaco,$conresaco,'cp07_adicional'))."','$this->cp07_adicional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de GU nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp07_codgu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de GU nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cp07_codgu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cp07_codgu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cp07_codgu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cp07_codgu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7209,'$cp07_codgu','E')");
         $resac = db_query("insert into db_acount values($acount,1198,7209,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_codgu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7210,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_gu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7211,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7212,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7213,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_codlocalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7214,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7215,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_codlogradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1198,7216,'','".AddSlashes(pg_result($resaco,$iresaco,'cp07_adicional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cepgu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cp07_codgu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cp07_codgu = $cp07_codgu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de GU nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cp07_codgu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de GU nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cp07_codgu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cp07_codgu;
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
        $this->erro_sql   = "Record Vazio na Tabela:cepgu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>