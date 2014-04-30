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
//CLASSE DA ENTIDADE traconduttaxi
class cl_traconduttaxi { 
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
   var $tr22_id = 0; 
   var $tr22_numcgm = 0; 
   var $tr22_idveicperm = 0; 
   var $tr22_cnh = null; 
   var $tr22_categoria = null; 
   var $tr22_dtvalidcnh_dia = null; 
   var $tr22_dtvalidcnh_mes = null; 
   var $tr22_dtvalidcnh_ano = null; 
   var $tr22_dtvalidcnh = null; 
   var $tr22_ruaid = 0; 
   var $tr22_nro = null; 
   var $tr22_bairroid = 0; 
   var $tr22_complem = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tr22_id = int4 = Código 
                 tr22_numcgm = int4 = CGM 
                 tr22_idveicperm = int4 = permissionário 
                 tr22_cnh = varchar(11) = CNH 
                 tr22_categoria = char(2) = Categoria 
                 tr22_dtvalidcnh = date = Validade CNH 
                 tr22_ruaid = int4 = Código da Rua 
                 tr22_nro = varchar(10) = Número 
                 tr22_bairroid = int4 = Bairro 
                 tr22_complem = varchar(255) = Complemento 
                 ";
   //funcao construtor da classe 
   function cl_traconduttaxi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("traconduttaxi"); 
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
       $this->tr22_id = ($this->tr22_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_id"]:$this->tr22_id);
       $this->tr22_numcgm = ($this->tr22_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_numcgm"]:$this->tr22_numcgm);
       $this->tr22_idveicperm = ($this->tr22_idveicperm == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_idveicperm"]:$this->tr22_idveicperm);
       $this->tr22_cnh = ($this->tr22_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_cnh"]:$this->tr22_cnh);
       $this->tr22_categoria = ($this->tr22_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_categoria"]:$this->tr22_categoria);
       if($this->tr22_dtvalidcnh == ""){
         $this->tr22_dtvalidcnh_dia = ($this->tr22_dtvalidcnh_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_dia"]:$this->tr22_dtvalidcnh_dia);
         $this->tr22_dtvalidcnh_mes = ($this->tr22_dtvalidcnh_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_mes"]:$this->tr22_dtvalidcnh_mes);
         $this->tr22_dtvalidcnh_ano = ($this->tr22_dtvalidcnh_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_ano"]:$this->tr22_dtvalidcnh_ano);
         if($this->tr22_dtvalidcnh_dia != ""){
            $this->tr22_dtvalidcnh = $this->tr22_dtvalidcnh_ano."-".$this->tr22_dtvalidcnh_mes."-".$this->tr22_dtvalidcnh_dia;
         }
       }
       $this->tr22_ruaid = ($this->tr22_ruaid == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_ruaid"]:$this->tr22_ruaid);
       $this->tr22_nro = ($this->tr22_nro == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_nro"]:$this->tr22_nro);
       $this->tr22_bairroid = ($this->tr22_bairroid == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_bairroid"]:$this->tr22_bairroid);
       $this->tr22_complem = ($this->tr22_complem == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_complem"]:$this->tr22_complem);
     }else{
       $this->tr22_id = ($this->tr22_id == ""?@$GLOBALS["HTTP_POST_VARS"]["tr22_id"]:$this->tr22_id);
     }
   }
   // funcao para inclusao
   function incluir ($tr22_id){ 
      $this->atualizacampos();
     if($this->tr22_numcgm == null ){ 
       $this->erro_sql = " Campo CGM nao Informado.";
       $this->erro_campo = "tr22_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_idveicperm == null ){ 
       $this->erro_sql = " Campo permissionário nao Informado.";
       $this->erro_campo = "tr22_idveicperm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_cnh == null ){ 
       $this->erro_sql = " Campo CNH nao Informado.";
       $this->erro_campo = "tr22_cnh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_categoria == null ){ 
       $this->erro_sql = " Campo Categoria nao Informado.";
       $this->erro_campo = "tr22_categoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_dtvalidcnh == null ){ 
       $this->tr22_dtvalidcnh = "null";
     }
     if($this->tr22_ruaid == null ){ 
       $this->tr22_ruaid = "0";
     }
     if($this->tr22_nro == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "tr22_nro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_bairroid == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "tr22_bairroid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tr22_complem == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "tr22_complem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->tr22_id = $tr22_id; 
     if(($this->tr22_id == null) || ($this->tr22_id == "") ){ 
       $this->erro_sql = " Campo tr22_id nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into traconduttaxi(
                                       tr22_id 
                                      ,tr22_numcgm 
                                      ,tr22_idveicperm 
                                      ,tr22_cnh 
                                      ,tr22_categoria 
                                      ,tr22_dtvalidcnh 
                                      ,tr22_ruaid 
                                      ,tr22_nro 
                                      ,tr22_bairroid 
                                      ,tr22_complem 
                       )
                values (
                                $this->tr22_id 
                               ,$this->tr22_numcgm 
                               ,$this->tr22_idveicperm 
                               ,'$this->tr22_cnh' 
                               ,'$this->tr22_categoria' 
                               ,".($this->tr22_dtvalidcnh == "null" || $this->tr22_dtvalidcnh == ""?"null":"'".$this->tr22_dtvalidcnh."'")." 
                               ,$this->tr22_ruaid 
                               ,'$this->tr22_nro' 
                               ,$this->tr22_bairroid 
                               ,'$this->tr22_complem' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Condutores ($this->tr22_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Condutores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Condutores ($this->tr22_id) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr22_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tr22_id));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11843,'$this->tr22_id','I')");
       $resac = db_query("insert into db_acount values($acount,2047,11843,'','".AddSlashes(pg_result($resaco,0,'tr22_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11844,'','".AddSlashes(pg_result($resaco,0,'tr22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11845,'','".AddSlashes(pg_result($resaco,0,'tr22_idveicperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11846,'','".AddSlashes(pg_result($resaco,0,'tr22_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11847,'','".AddSlashes(pg_result($resaco,0,'tr22_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11848,'','".AddSlashes(pg_result($resaco,0,'tr22_dtvalidcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11849,'','".AddSlashes(pg_result($resaco,0,'tr22_ruaid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11850,'','".AddSlashes(pg_result($resaco,0,'tr22_nro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11851,'','".AddSlashes(pg_result($resaco,0,'tr22_bairroid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2047,11852,'','".AddSlashes(pg_result($resaco,0,'tr22_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tr22_id=null) { 
      $this->atualizacampos();
     $sql = " update traconduttaxi set ";
     $virgula = "";
     if(trim($this->tr22_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_id"])){ 
       $sql  .= $virgula." tr22_id = $this->tr22_id ";
       $virgula = ",";
       if(trim($this->tr22_id) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tr22_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_numcgm"])){ 
       $sql  .= $virgula." tr22_numcgm = $this->tr22_numcgm ";
       $virgula = ",";
       if(trim($this->tr22_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM nao Informado.";
         $this->erro_campo = "tr22_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_idveicperm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_idveicperm"])){ 
       $sql  .= $virgula." tr22_idveicperm = $this->tr22_idveicperm ";
       $virgula = ",";
       if(trim($this->tr22_idveicperm) == null ){ 
         $this->erro_sql = " Campo permissionário nao Informado.";
         $this->erro_campo = "tr22_idveicperm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_cnh"])){ 
       $sql  .= $virgula." tr22_cnh = '$this->tr22_cnh' ";
       $virgula = ",";
       if(trim($this->tr22_cnh) == null ){ 
         $this->erro_sql = " Campo CNH nao Informado.";
         $this->erro_campo = "tr22_cnh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_categoria"])){ 
       $sql  .= $virgula." tr22_categoria = '$this->tr22_categoria' ";
       $virgula = ",";
       if(trim($this->tr22_categoria) == null ){ 
         $this->erro_sql = " Campo Categoria nao Informado.";
         $this->erro_campo = "tr22_categoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_dtvalidcnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_dia"] !="") ){ 
       $sql  .= $virgula." tr22_dtvalidcnh = '$this->tr22_dtvalidcnh' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh_dia"])){ 
         $sql  .= $virgula." tr22_dtvalidcnh = null ";
         $virgula = ",";
       }
     }
     if(trim($this->tr22_ruaid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_ruaid"])){ 
        if(trim($this->tr22_ruaid)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tr22_ruaid"])){ 
           $this->tr22_ruaid = "0" ; 
        } 
       $sql  .= $virgula." tr22_ruaid = $this->tr22_ruaid ";
       $virgula = ",";
     }
     if(trim($this->tr22_nro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_nro"])){ 
       $sql  .= $virgula." tr22_nro = '$this->tr22_nro' ";
       $virgula = ",";
       if(trim($this->tr22_nro) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "tr22_nro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_bairroid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_bairroid"])){ 
       $sql  .= $virgula." tr22_bairroid = $this->tr22_bairroid ";
       $virgula = ",";
       if(trim($this->tr22_bairroid) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "tr22_bairroid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tr22_complem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tr22_complem"])){ 
       $sql  .= $virgula." tr22_complem = '$this->tr22_complem' ";
       $virgula = ",";
       if(trim($this->tr22_complem) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "tr22_complem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tr22_id!=null){
       $sql .= " tr22_id = $this->tr22_id";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tr22_id));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11843,'$this->tr22_id','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_id"]))
           $resac = db_query("insert into db_acount values($acount,2047,11843,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_id'))."','$this->tr22_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,2047,11844,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_numcgm'))."','$this->tr22_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_idveicperm"]))
           $resac = db_query("insert into db_acount values($acount,2047,11845,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_idveicperm'))."','$this->tr22_idveicperm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_cnh"]))
           $resac = db_query("insert into db_acount values($acount,2047,11846,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_cnh'))."','$this->tr22_cnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_categoria"]))
           $resac = db_query("insert into db_acount values($acount,2047,11847,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_categoria'))."','$this->tr22_categoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_dtvalidcnh"]))
           $resac = db_query("insert into db_acount values($acount,2047,11848,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_dtvalidcnh'))."','$this->tr22_dtvalidcnh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_ruaid"]))
           $resac = db_query("insert into db_acount values($acount,2047,11849,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_ruaid'))."','$this->tr22_ruaid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_nro"]))
           $resac = db_query("insert into db_acount values($acount,2047,11850,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_nro'))."','$this->tr22_nro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_bairroid"]))
           $resac = db_query("insert into db_acount values($acount,2047,11851,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_bairroid'))."','$this->tr22_bairroid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tr22_complem"]))
           $resac = db_query("insert into db_acount values($acount,2047,11852,'".AddSlashes(pg_result($resaco,$conresaco,'tr22_complem'))."','$this->tr22_complem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condutores nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr22_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Condutores nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tr22_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tr22_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tr22_id=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tr22_id));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11843,'$tr22_id','E')");
         $resac = db_query("insert into db_acount values($acount,2047,11843,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11844,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11845,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_idveicperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11846,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_cnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11847,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_categoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11848,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_dtvalidcnh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11849,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_ruaid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11850,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_nro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11851,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_bairroid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2047,11852,'','".AddSlashes(pg_result($resaco,$iresaco,'tr22_complem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from traconduttaxi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tr22_id != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tr22_id = $tr22_id ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condutores nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tr22_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Condutores nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tr22_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tr22_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:traconduttaxi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>