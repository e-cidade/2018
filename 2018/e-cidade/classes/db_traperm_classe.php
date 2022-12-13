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

//MODULO: transito
//CLASSE DA ENTIDADE traperm
class cl_traperm { 
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
   var $tr20_id = 0; 
   var $tr20_dtalvara_dia = null; 
   var $tr20_dtalvara_mes = null; 
   var $tr20_dtalvara_ano = null; 
   var $tr20_dtalvara = null; 
   var $tr20_numcgm = 0; 
   var $tr20_ruaid = 0; 
   var $tr20_nro = null; 
   var $tr20_bairroid = 0; 
   var $tr20_complem = null; 
   var $tr20_fone = null; 
   var $tr20_prefixo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tr20_id = int4 = Código 
                 tr20_dtalvara = date = Data do Alvara 
                 tr20_numcgm = int4 = CGM 
                 tr20_ruaid = int4 = Logradouro 
                 tr20_nro = varchar(10) = Número 
                 tr20_bairroid = int4 = Bairro 
                 tr20_complem = varchar(255) = Complemento 
                 tr20_fone = varchar(15) = Fone 
                 tr20_prefixo = varchar(20) = Prefixo 
                 ";
   //funcao construtor da classe 
   function cl_traperm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("traperm"); 
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
       $this->tr20_id = ($this->tr20_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_id"]:$this->tr20_id);
       if($this->tr20_dtalvara == ""){
         $this->tr20_dtalvara_dia = ($this->tr20_dtalvara_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_dia"]:$this->tr20_dtalvara_dia);
         $this->tr20_dtalvara_mes = ($this->tr20_dtalvara_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_mes"]:$this->tr20_dtalvara_mes);
         $this->tr20_dtalvara_ano = ($this->tr20_dtalvara_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_ano"]:$this->tr20_dtalvara_ano);
         if($this->tr20_dtalvara_dia != ""){
            $this->tr20_dtalvara = $this->tr20_dtalvara_ano."-".$this->tr20_dtalvara_mes."-".$this->tr20_dtalvara_dia;
         }
       }
       $this->tr20_numcgm = ($this->tr20_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_numcgm"]:$this->tr20_numcgm);
       $this->tr20_ruaid = ($this->tr20_ruaid == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_ruaid"]:$this->tr20_ruaid);
       $this->tr20_nro = ($this->tr20_nro == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_nro"]:$this->tr20_nro);
       $this->tr20_bairroid = ($this->tr20_bairroid == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_bairroid"]:$this->tr20_bairroid);
       $this->tr20_complem = ($this->tr20_complem == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_complem"]:$this->tr20_complem);
       $this->tr20_fone = ($this->tr20_fone == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_fone"]:$this->tr20_fone);
       $this->tr20_prefixo = ($this->tr20_prefixo == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_prefixo"]:$this->tr20_prefixo);
     }else{
       $this->tr20_id = ($this->tr20_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr20_id"]:$this->tr20_id);
     }
   }
   // funcao para inclusao
   function incluir ($tr20_id){ 
      $this->atualizacampos();
     if($this->tr20_dtalvara == null ){ 
       $this->erro_sql = " Campo Data do Alvara nao Informado.";
       $this->erro_campo = "tr20_dtalvara_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr20_numcgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "tr20_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr20_ruaid == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "tr20_ruaid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr20_nro == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "tr20_nro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr20_bairroid == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "tr20_bairroid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->tr20_id = $tr20_id; 
     if(($this->tr20_id == null) || ($this->tr20_id == "") ){ 
       $this->erro_sql = " Campo tr20_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into traperm(
                                       tr20_id 
                                      ,tr20_dtalvara 
                                      ,tr20_numcgm 
                                      ,tr20_ruaid 
                                      ,tr20_nro 
                                      ,tr20_bairroid 
                                      ,tr20_complem 
                                      ,tr20_fone 
                                      ,tr20_prefixo 
                       )
                values (
                                $this->tr20_id 
                               ,".($this->tr20_dtalvara == "null" || $this->tr20_dtalvara == ""?"null":"'".$this->tr20_dtalvara."'")." 
                               ,$this->tr20_numcgm 
                               ,$this->tr20_ruaid 
                               ,'$this->tr20_nro' 
                               ,$this->tr20_bairroid 
                               ,'$this->tr20_complem' 
                               ,'$this->tr20_fone' 
                               ,'$this->tr20_prefixo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Permissionários ($this->tr20_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Permissionários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Permissionários ($this->tr20_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr20_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tr20_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11834,'$this->tr20_id','I')");
       $resac = db_query("insert into db_acount values($acount,2046,11834,'','".AddSlashes(pg_result($resaco,0,'tr20_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11835,'','".AddSlashes(pg_result($resaco,0,'tr20_dtalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11836,'','".AddSlashes(pg_result($resaco,0,'tr20_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11837,'','".AddSlashes(pg_result($resaco,0,'tr20_ruaid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11838,'','".AddSlashes(pg_result($resaco,0,'tr20_nro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11839,'','".AddSlashes(pg_result($resaco,0,'tr20_bairroid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11840,'','".AddSlashes(pg_result($resaco,0,'tr20_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11841,'','".AddSlashes(pg_result($resaco,0,'tr20_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2046,11842,'','".AddSlashes(pg_result($resaco,0,'tr20_prefixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tr20_id=null) { 
      $this->atualizacampos();
     $sql = " update traperm set ";
     $virgula = "";
     if(trim($this->tr20_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_id"])){ 
       $sql  .= $virgula." tr20_id = $this->tr20_id ";
       $virgula = ",";
       if(trim($this->tr20_id) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tr20_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr20_dtalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_dia"] !="") ){ 
       $sql  .= $virgula." tr20_dtalvara = '$this->tr20_dtalvara' ";
       $virgula = ",";
       if(trim($this->tr20_dtalvara) == null ){ 
         $this->erro_sql = " Campo Data do Alvara nao Informado.";
         $this->erro_campo = "tr20_dtalvara_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara_dia"])){ 
         $sql  .= $virgula." tr20_dtalvara = null ";
         $virgula = ",";
         if(trim($this->tr20_dtalvara) == null ){ 
           $this->erro_sql = " Campo Data do Alvara nao Informado.";
           $this->erro_campo = "tr20_dtalvara_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tr20_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_numcgm"])){ 
       $sql  .= $virgula." tr20_numcgm = $this->tr20_numcgm ";
       $virgula = ",";
       if(trim($this->tr20_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "tr20_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr20_ruaid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_ruaid"])){ 
       $sql  .= $virgula." tr20_ruaid = $this->tr20_ruaid ";
       $virgula = ",";
       if(trim($this->tr20_ruaid) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "tr20_ruaid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr20_nro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_nro"])){ 
       $sql  .= $virgula." tr20_nro = '$this->tr20_nro' ";
       $virgula = ",";
       if(trim($this->tr20_nro) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "tr20_nro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr20_bairroid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_bairroid"])){ 
       $sql  .= $virgula." tr20_bairroid = $this->tr20_bairroid ";
       $virgula = ",";
       if(trim($this->tr20_bairroid) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "tr20_bairroid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr20_complem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_complem"])){ 
       $sql  .= $virgula." tr20_complem = '$this->tr20_complem' ";
       $virgula = ",";
     }
     if(trim($this->tr20_fone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_fone"])){ 
       $sql  .= $virgula." tr20_fone = '$this->tr20_fone' ";
       $virgula = ",";
     }
     if(trim($this->tr20_prefixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr20_prefixo"])){ 
       $sql  .= $virgula." tr20_prefixo = '$this->tr20_prefixo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tr20_id!=null){
       $sql .= " tr20_id = $this->tr20_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tr20_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11834,'$this->tr20_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_id"]))
           $resac = db_query("insert into db_acount values($acount,2046,11834,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_id'))."','$this->tr20_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_dtalvara"]))
           $resac = db_query("insert into db_acount values($acount,2046,11835,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_dtalvara'))."','$this->tr20_dtalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,2046,11836,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_numcgm'))."','$this->tr20_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_ruaid"]))
           $resac = db_query("insert into db_acount values($acount,2046,11837,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_ruaid'))."','$this->tr20_ruaid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_nro"]))
           $resac = db_query("insert into db_acount values($acount,2046,11838,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_nro'))."','$this->tr20_nro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_bairroid"]))
           $resac = db_query("insert into db_acount values($acount,2046,11839,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_bairroid'))."','$this->tr20_bairroid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_complem"]))
           $resac = db_query("insert into db_acount values($acount,2046,11840,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_complem'))."','$this->tr20_complem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_fone"]))
           $resac = db_query("insert into db_acount values($acount,2046,11841,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_fone'))."','$this->tr20_fone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr20_prefixo"]))
           $resac = db_query("insert into db_acount values($acount,2046,11842,'".AddSlashes(pg_result($resaco,$conresaco,'tr20_prefixo'))."','$this->tr20_prefixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissionários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr20_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissionários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr20_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr20_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tr20_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tr20_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11834,'$tr20_id','E')");
         $resac = db_query("insert into db_acount values($acount,2046,11834,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11835,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_dtalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11836,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11837,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_ruaid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11838,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_nro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11839,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_bairroid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11840,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11841,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_fone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2046,11842,'','".AddSlashes(pg_result($resaco,$iresaco,'tr20_prefixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from traperm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tr20_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr20_id = $tr20_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissionários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tr20_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissionários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tr20_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tr20_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:traperm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>